<?php

namespace App\Services;

class QrCodeRenderer
{
    private array $matrix;
    private int $size;
    private int $version;

    private const QR_SPEC_TABLE = [
        1 => [21, 26, 26], 2 => [25, 44, 44], 3 => [29, 70, 70], 4 => [33, 100, 100],
        5 => [37, 134, 134], 6 => [41, 172, 172], 7 => [45, 196, 196], 8 => [49, 242, 242],
        9 => [53, 292, 292], 10 => [57, 346, 346], 11 => [61, 404, 404],
        12 => [65, 466, 466], 13 => [69, 532, 532], 14 => [73, 581, 581],
        15 => [77, 655, 655], 16 => [81, 733, 733], 17 => [85, 815, 815],
        18 => [89, 901, 901], 19 => [93, 991, 991], 20 => [97, 1085, 1085],
    ];

    private const QUIET_ZONE = 4;

    private array $gf_exp;
    private array $gf_log;

    public function __construct()
    {
        $this->initGaloisField();
    }

    private function initGaloisField(): void
    {
        $this->gf_exp = array_fill(0, 512, 0);
        $this->gf_log = array_fill(0, 256, 0);
        $x = 1;
        for ($i = 0; $i < 255; $i++) {
            $this->gf_exp[$i] = $x;
            $this->gf_log[$x] = $i;
            $x = ($x << 1) ^ ($x >= 128 ? 0x11d : 0);
        }
        for ($i = 255; $i < 512; $i++) {
            $this->gf_exp[$i] = $this->gf_exp[$i - 255];
        }
    }

    private function gfMul(int $a, int $b): int
    {
        if ($a === 0 || $b === 0) return 0;
        return $this->gf_exp[$this->gf_log[$a] + $this->gf_log[$b]];
    }

    private function gfPow(int $a, int $b): int
    {
        return $this->gf_exp[($this->gf_log[$a] * $b) % 255];
    }

    private function rsEncode(array $data, int $ecBytes): array
    {
        $gen = [1];
        for ($i = 0; $i < $ecBytes; $i++) {
            $gen[] = 1;
            for ($j = count($gen) - 2; $j >= 0; $j--) {
                $gen[$j + 1] = $gen[$j + 1] ^ $this->gfMul($gen[$j], $this->gf_exp[$i]);
                if ($j === 0) {
                    $gen[0] = $this->gfMul($gen[0], $this->gf_exp[$i]);
                }
            }
        }

        $dataOut = array_merge($data, array_fill(0, $ecBytes, 0));
        for ($i = 0; $i < count($data); $i++) {
            if ($dataOut[$i] !== 0) {
                $factor = $this->gf_log[$dataOut[$i]];
                for ($j = 0; $j < count($gen); $j++) {
                    $dataOut[$i + $j] ^= $this->gfMul($gen[$j], $this->gf_exp[$factor]);
                }
            }
        }
        return array_slice($dataOut, count($data));
    }

    public function render(string $data, int $size = 200): string
    {
        $this->version = $this->selectVersion(strlen($data));
        $this->size = self::QR_SPEC_TABLE[$this->version][0];
        $this->matrix = array_fill(0, $this->size, array_fill(0, $this->size, 0));

        $encoded = $this->encodeData($data);
        $this->placeFinderPatterns();
        $this->placeTimingPatterns();
        $this->placeData($encoded);
        $this->applyMask(0);
        $this->placeFormatInfo(0);

        return $this->toPng($size);
    }

    private function selectVersion(int $dataLen): int
    {
        for ($v = 1; $v <= 20; $v++) {
            $totalCodewords = self::QR_SPEC_TABLE[$v][1];
            if ($totalCodewords - 10 >= $dataLen + 2) {
                return $v;
            }
        }
        return 20;
    }

    private function encodeData(string $data): array
    {
        $bytes = array_values(unpack('C*', $data));
        $maxBytes = self::QR_SPEC_TABLE[$this->version][2];

        $mode = [0, 0, 0, 1];  // Byte mode: 0100
        $lenBits = strlen($data);
        $lenBytes = $lenBits < 128 ? [$lenBits] : [0xC0 | ($lenBits >> 8), $lenBits & 0xFF];

        $encoded = array_merge($mode, $this->toBits($lenBytes, 8), $this->toBits($bytes, 8));

        $totalBits = count($encoded);
        while ($totalBits % 8 !== 0) {
            $encoded[] = 0;
            $totalBits++;
        }

        $encodedBytes = [];
        for ($i = 0; $i < $totalBits; $i += 8) {
            $byte = 0;
            for ($j = 0; $j < 8; $j++) {
                $byte = ($byte << 1) | ($encoded[$i + $j] ?? 0);
            }
            $encodedBytes[] = $byte;
        }

        while (count($encodedBytes) < $maxBytes) {
            $encodedBytes[] = (count($encodedBytes) % 2 === 0) ? 0xEC : 0x11;
        }

        $ecBytes = 10;
        $ec = $this->rsEncode(array_slice($encodedBytes, 0, $maxBytes - $ecBytes), $ecBytes);

        return array_merge(array_slice($encodedBytes, 0, $maxBytes - $ecBytes), $ec);
    }

    private function toBits(array $data, int $bitsPerItem): array
    {
        $result = [];
        foreach ($data as $item) {
            for ($i = $bitsPerItem - 1; $i >= 0; $i--) {
                $result[] = ($item >> $i) & 1;
            }
        }
        return $result;
    }

    private function placeFinderPatterns(): void
    {
        $positions = [[0, 0], [$this->size - 7, 0], [0, $this->size - 7]];
        foreach ($positions as [$row, $col]) {
            for ($r = -1; $r <= 7; $r++) {
                for ($c = -1; $c <= 7; $c++) {
                    $rr = $row + $r;
                    $cc = $col + $c;
                    if ($rr < 0 || $rr >= $this->size || $cc < 0 || $cc >= $this->size) continue;
                    $isBorder = $r === -1 || $r === 7 || $c === -1 || $c === 7;
                    $isOuter = ($r >= 0 && $r <= 6 && $c >= 0 && $c <= 6) &&
                        ($r === 0 || $r === 6 || $c === 0 || $c === 6);
                    $isInner = ($r >= 2 && $r <= 4 && $c >= 2 && $c <= 4);
                    if ($isBorder) {
                        $this->matrix[$rr][$cc] = 0x80;
                    } elseif ($isOuter || $isInner) {
                        $this->matrix[$rr][$cc] = 0x80;
                    }
                }
            }
        }
    }

    private function placeTimingPatterns(): void
    {
        for ($i = 8; $i < $this->size - 8; $i++) {
            $val = $i % 2 === 0 ? 0x80 : 0;
            if ($this->matrix[6][$i] === 0) $this->matrix[6][$i] = $val;
            if ($this->matrix[$i][6] === 0) $this->matrix[$i][6] = $val;
        }
    }

    private function placeData(array $data): void
    {
        $bitIndex = 0;
        $totalBits = count($data) * 8;

        for ($col = $this->size - 1; $col >= 1; $col -= 2) {
            if ($col === 6) $col = 5;
            for ($row = 0; $row < $this->size; $row++) {
                for ($c = 0; $c < 2; $c++) {
                    $cc = $col - $c;
                    if ($cc < 0) continue;
                    $r = ($col % 4 === 1) ? $this->size - 1 - $row : $row;
                    if ($r < 0 || $r >= $this->size) continue;
                    if ($this->matrix[$r][$cc] !== 0) continue;

                    if ($bitIndex < $totalBits) {
                        $byteIndex = intdiv($bitIndex, 8);
                        $bitPos = 7 - ($bitIndex % 8);
                        $this->matrix[$r][$cc] = (($data[$byteIndex] >> $bitPos) & 1) ? 1 : -1;
                        $bitIndex++;
                    }
                }
            }
        }
    }

    private function applyMask(int $mask): void
    {
        for ($r = 0; $r < $this->size; $r++) {
            for ($c = 0; $c < $this->size; $c++) {
                if ($this->matrix[$r][$c] === 0) continue;
                $val = $this->matrix[$r][$c];
                if ($val === -1 || $val === 1) {
                    $apply = false;
                    switch ($mask) {
                        case 0: $apply = ($r + $c) % 2 === 0; break;
                        case 1: $apply = $r % 2 === 0; break;
                        case 2: $apply = $c % 3 === 0; break;
                        case 3: $apply = ($r + $c) % 3 === 0; break;
                        case 4: $apply = (intdiv($r, 2) + intdiv($c, 3)) % 2 === 0; break;
                        case 5: $apply = ($r * $c) % 2 + ($r * $c) % 3 === 0; break;
                        case 6: $apply = (($r * $c) % 2 + ($r * $c) % 3) % 2 === 0; break;
                        case 7: $apply = (($r + $c) % 2 + ($r * $c) % 3) % 2 === 0; break;
                    }
                    if ($apply) {
                        $this->matrix[$r][$c] = ($val === 1) ? -1 : 1;
                    }
                } elseif ($val === 0x80 || $val === 0) {
                    $this->matrix[$r][$c] = $val === 0x80 ? 1 : 0;
                }
            }
        }
    }

    private function placeFormatInfo(int $mask): void
    {
        $ecLevel = 0; // M = 00
        $formatData = ($ecLevel << 3) | $mask;
        $formatBits = [1, 0, 1, 0, 1, 0, 0, 0, 0, 0, 1, 0, 0, 1, 0]; // pre-computed for M + mask 0

        $positions = [];
        for ($i = 0; $i <= 5; $i++) $positions[] = [8, $i];
        $positions[] = [8, 7]; $positions[] = [8, 8]; $positions[] = [7, 8];
        for ($i = 9; $i <= 14; $i++) $positions[] = [14 - $i + 9, 8];

        $positions2 = [];
        for ($i = 0; $i <= 7; $i++) $positions2[] = [$this->size - 1 - $i, 8];
        for ($i = 8; $i <= 14; $i++) $positions2[] = [8, $this->size - 15 + $i];

        foreach ($positions as $idx => [$r, $c]) {
            if ($r < $this->size && $c < $this->size && isset($formatBits[$idx])) {
                $this->matrix[$r][$c] = $formatBits[$idx] ? 0x80 : 0;
            }
        }
        foreach ($positions2 as $idx => [$r, $c]) {
            if ($r < $this->size && $c < $this->size && isset($formatBits[$idx])) {
                $this->matrix[$r][$c] = $formatBits[$idx] ? 0x80 : 0;
            }
        }
    }

    private function toPng(int $outputSize): string
    {
        $margin = self::QUIET_ZONE;
        $totalModules = $this->size + 2 * $margin;
        $scale = max(1, intdiv($outputSize, $totalModules));
        $imgSize = $totalModules * $scale;

        $img = imagecreatetruecolor($imgSize, $imgSize);
        $white = imagecolorallocate($img, 255, 255, 255);
        $black = imagecolorallocate($img, 0, 0, 0);
        imagefill($img, 0, 0, $white);

        for ($r = 0; $r < $this->size; $r++) {
            for ($c = 0; $c < $this->size; $c++) {
                $val = $this->matrix[$r][$c] ?? 0;
                $isBlack = ($val === 1 || $val === 0x80);
                if ($isBlack) {
                    $x = ($c + $margin) * $scale;
                    $y = ($r + $margin) * $scale;
                    imagefilledrectangle($img, $x, $y, $x + $scale - 1, $y + $scale - 1, $black);
                }
            }
        }

        ob_start();
        imagepng($img);
        $png = ob_get_clean();
        imagedestroy($img);

        return $png;
    }
}
