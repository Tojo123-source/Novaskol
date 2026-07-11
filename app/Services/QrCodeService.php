<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class QrCodeService
{
    public function generateUniqueToken(): string
    {
        return 'nvs_' . Str::random(12) . dechex(time());
    }

    public function ensureToken(string $table, string $column, int $id): string
    {
        $row = DB::table($table)->where('id', $id)->value($column);
        if ($row) {
            return $row;
        }
        $token = $this->generateUniqueToken();
        DB::table($table)->where('id', $id)->update([$column => $token]);
        return $token;
    }

    public function payloadForToken(string $token): string
    {
        return 'novaskol:qr:v1:' . $this->normalizeToken($token);
    }

    public function normalizeToken(string $raw): string
    {
        $value = trim(rawurldecode($raw));
        if ($value === '') {
            return '';
        }

        if (preg_match('/novaskol:qr:v1:([A-Za-z0-9_\\-]+)/', $value, $matches)) {
            return $matches[1];
        }

        $parts = parse_url($value);
        if (is_array($parts)) {
            if (!empty($parts['query'])) {
                parse_str($parts['query'], $query);
                if (!empty($query['token']) && is_string($query['token'])) {
                    return trim($query['token']);
                }
            }

            if (!empty($parts['path'])) {
                $segments = array_values(array_filter(explode('/', $parts['path'])));
                $count = count($segments);
                for ($i = 0; $i < $count - 1; $i++) {
                    if (in_array($segments[$i], ['qr-code', 'qr-presence'], true)) {
                        return trim($segments[$i + 1]);
                    }
                }
                if ($count > 0 && str_starts_with($segments[$count - 1], 'nvs_')) {
                    return trim($segments[$count - 1]);
                }
            }
        }

        if (str_contains($value, '/')) {
            $segments = array_values(array_filter(explode('/', $value)));
            $value = end($segments) ?: $value;
        }

        return trim($value);
    }

    public function resolveToken(string $token): ?array
    {
        $token = $this->normalizeToken($token);
        if ($token === '') {
            return null;
        }

        $tables = [
            'eleves' => 'eleve',
            'enseignants' => 'enseignant',
            'professeurs' => 'enseignant',
            'staff' => 'staff',
            'utilisateurs' => 'utilisateur',
        ];

        foreach ($tables as $table => $type) {
            $row = DB::table($table)->where('qr_token', $token)->first();
            if ($row) {
                return ['type' => $type, 'table' => $table, 'data' => $row];
            }
        }

        return null;
    }
}
