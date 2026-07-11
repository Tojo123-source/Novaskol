(function() {
    'use strict';

    var QRCode = function(canvas, text) {
        if (typeof canvas === 'string') {
            canvas = document.getElementById(canvas);
        }
        if (!canvas || !canvas.getContext) return;

        var matrix = generateMatrix(text);
        var size = matrix.length;
        var cw = canvas.width || 200;
        var ch = canvas.height || 200;
        var dim = Math.min(cw, ch);
        var quiet = Math.max(Math.ceil(dim * 0.04), 2);
        var cellSize = Math.floor((dim - quiet * 2) / size);
        var totalSize = cellSize * size + quiet * 2;

        canvas.width = totalSize;
        canvas.height = totalSize;

        var ctx = canvas.getContext('2d');
        ctx.fillStyle = '#ffffff';
        ctx.fillRect(0, 0, totalSize, totalSize);
        ctx.fillStyle = '#000000';

        for (var r = 0; r < size; r++) {
            for (var c = 0; c < size; c++) {
                if (matrix[r][c] === 1 || matrix[r][c] === -1) {
                    ctx.fillRect(quiet + c * cellSize, quiet + r * cellSize, cellSize, cellSize);
                }
            }
        }
    };

    function generateMatrix(text) {
        var data = encodeData(text);
        var version = 2;
        var size = 25;
        var matrix = createEmpty(size);

        placeFinder(matrix, 0, 0);
        placeFinder(matrix, size - 7, 0);
        placeFinder(matrix, 0, size - 7);
        placeTiming(matrix, size);
        placeData(matrix, data, size);
        applyMask(matrix, size);
        placeFormatInfo(matrix, size);

        return matrix;
    }

    function createEmpty(size) {
        var m = [];
        for (var r = 0; r < size; r++) {
            m[r] = [];
            for (var c = 0; c < size; c++) m[r][c] = 0;
        }
        return m;
    }

    function placeFinder(m, row, col) {
        for (var r = -1; r <= 7; r++) {
            for (var c = -1; c <= 7; c++) {
                var rr = row + r, cc = col + c;
                if (rr < 0 || rr >= m.length || cc < 0 || cc >= m.length) continue;
                if (r === -1 || r === 7 || c === -1 || c === 7) { m[rr][cc] = 0; continue; }
                if (r === 0 || r === 6 || c === 0 || c === 6) { m[rr][cc] = 1; continue; }
                if (r >= 2 && r <= 4 && c >= 2 && c <= 4) { m[rr][cc] = 1; continue; }
                m[rr][cc] = 0;
            }
        }
        addSeparator(m, row, col);
    }

    function addSeparator(m, row, col) {
        for (var i = -1; i <= 7; i++) {
            var r1 = row + i, c1 = col - 1;
            var r2 = row - 1, c2 = col + i;
            var r3 = row + i, c3 = col + 7;
            var r4 = row + 7, c4 = col + i;
            if (r1 >= 0 && r1 < m.length && c1 >= 0 && c1 < m.length) m[r1][c1] = 0;
            if (r2 >= 0 && r2 < m.length && c2 >= 0 && c2 < m.length) m[r2][c2] = 0;
            if (r3 >= 0 && r3 < m.length && c3 >= 0 && c3 < m.length) m[r3][c3] = 0;
            if (r4 >= 0 && r4 < m.length && c4 >= 0 && c4 < m.length) m[r4][c4] = 0;
        }
    }

    function placeTiming(m, size) {
        for (var i = 8; i < size - 8; i++) {
            if (m[6][i] === 0) m[6][i] = (i % 2 === 0) ? 1 : 0;
            if (m[i][6] === 0) m[i][6] = (i % 2 === 0) ? 1 : 0;
        }
    }

    function encodeData(text) {
        var bytes = [];
        for (var i = 0; i < text.length; i++) {
            var code = text.charCodeAt(i);
            if (code < 128) {
                bytes.push(code);
            } else if (code < 2048) {
                bytes.push(0xC0 | (code >> 6));
                bytes.push(0x80 | (code & 0x3F));
            } else {
                bytes.push(0xE0 | (code >> 12));
                bytes.push(0x80 | ((code >> 6) & 0x3F));
                bytes.push(0x80 | (code & 0x3F));
            }
        }

        var maxBytes = 44;
        var mode = [0, 1, 0, 0];
        var lenArr = [];
        var len = bytes.length;
        if (len < 128) {
            lenArr = [len];
        } else {
            lenArr = [0xC0 | (len >> 8), len & 0xFF];
        }

        var allBits = mode.concat(lenArr.map(function(b) { return bits8(b); }).flat());
        for (var i = 0; i < bytes.length; i++) {
            allBits = allBits.concat(bits8(bytes[i]));
        }

        while (allBits.length % 8 !== 0) allBits.push(0);

        var result = [];
        for (var i = 0; i < allBits.length; i += 8) {
            var byte = 0;
            for (var j = 0; j < 8; j++) byte = (byte << 1) | (allBits[i + j] || 0);
            result.push(byte);
        }

        var pad = [0xEC, 0x11];
        for (var i = result.length; i < maxBytes; i++) {
            result.push(pad[(i - bytes.length - 4) % 2]);
        }

        var dataPart = result.slice(0, maxBytes - 10);
        var ecPart = rsEncode(dataPart, 10);
        return dataPart.concat(ecPart);
    }

    function bits8(b) {
        var bits = [];
        for (var i = 7; i >= 0; i--) bits.push((b >> i) & 1);
        return bits;
    }

    var gfExp = [], gfLog = [];
    (function() {
        var x = 1;
        for (var i = 0; i < 255; i++) {
            gfExp[i] = x;
            gfLog[x] = i;
            x = (x << 1) ^ (x >= 128 ? 0x11d : 0);
        }
        for (var i = 255; i < 512; i++) gfExp[i] = gfExp[i - 255];
    })();

    function gfMul(a, b) {
        if (a === 0 || b === 0) return 0;
        return gfExp[gfLog[a] + gfLog[b]];
    }

    function rsEncode(data, ecBytes) {
        var gen = [1];
        for (var i = 0; i < ecBytes; i++) {
            gen.push(1);
            for (var j = gen.length - 2; j >= 0; j--) {
                gen[j + 1] = gen[j + 1] ^ gfMul(gen[j], gfExp[i]);
                if (j === 0) gen[0] = gfMul(gen[0], gfExp[i]);
            }
        }

        var dataOut = data.concat(new Array(ecBytes).fill(0));
        for (var i = 0; i < data.length; i++) {
            if (dataOut[i] !== 0) {
                var factor = gfLog[dataOut[i]];
                for (var j = 0; j < gen.length; j++) {
                    dataOut[i + j] ^= gfMul(gen[j], gfExp[factor]);
                }
            }
        }
        return dataOut.slice(data.length);
    }

    function placeData(m, data, size) {
        var bitIdx = 0;
        for (var col = size - 1; col >= 1; col -= 2) {
            if (col === 6) col = 5;
            for (var row = 0; row < size; row++) {
                for (var c = 0; c < 2; c++) {
                    var cc = col - c;
                    if (cc < 0) continue;
                    var r = (col % 4 === 1) ? size - 1 - row : row;
                    if (r < 0 || r >= size || cc >= size) continue;
                    if (m[r][cc] !== 0) continue;
                    if (bitIdx < data.length * 8) {
                        var byteIdx = Math.floor(bitIdx / 8);
                        var bitPos = 7 - (bitIdx % 8);
                        m[r][cc] = ((data[byteIdx] >> bitPos) & 1) ? 1 : -1;
                        bitIdx++;
                    }
                }
            }
        }
    }

    function applyMask(m, size) {
        for (var r = 0; r < size; r++) {
            for (var c = 0; c < size; c++) {
                if (m[r][c] === -1 || m[r][c] === 1) {
                    var apply = (r + c) % 2 === 0;
                    if (apply) m[r][c] = (m[r][c] === 1) ? -1 : 1;
                } else if (m[r][c] !== 0) {
                    m[r][c] = 1;
                }
            }
        }
    }

    function placeFormatInfo(m, size) {
        var bits = [1, 0, 1, 0, 1, 0, 0, 0, 0, 0, 1, 0, 0, 1, 0];
        var pos1 = [], pos2 = [];
        for (var i = 0; i <= 5; i++) { pos1.push([8, i]); }
        pos1.push([8, 7]); pos1.push([8, 8]); pos1.push([7, 8]);
        for (var i = 9; i <= 14; i++) pos1.push([14 - i + 9, 8]);
        for (var i = 0; i <= 7; i++) pos2.push([size - 1 - i, 8]);
        for (var i = 8; i <= 14; i++) pos2.push([8, size - 15 + i]);

        pos1.concat(pos2).forEach(function(p, idx) {
            if (idx < bits.length && p[0] < size && p[1] < size) {
                m[p[0]][p[1]] = bits[idx] ? 1 : 0;
            }
        });
    }

    window.QRCode = QRCode;
})();
