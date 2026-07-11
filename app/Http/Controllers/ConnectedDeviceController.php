<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\File;

class ConnectedDeviceController extends Controller
{
    public function show()
    {
        return redirect()->route('connected.app');
    }

    public function app(?string $path = null)
    {
        $base = storage_path('app/distribution/novaskol-connected-latest');
        $path = trim((string) $path, '/');
        $file = $path === '' ? $base.DIRECTORY_SEPARATOR.'index.html' : $base.DIRECTORY_SEPARATOR.str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $path);
        $realBase = realpath($base);
        $realFile = realpath($file);

        if (! $realBase || ! $realFile || ! str_starts_with($realFile, $realBase) || ! File::isFile($realFile)) {
            abort(404);
        }

        return response()->file($realFile, [
            'Content-Type' => $this->mimeType($realFile),
            'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ]);
    }

    private function mimeType(string $file): string
    {
        return match (strtolower(pathinfo($file, PATHINFO_EXTENSION))) {
            'html' => 'text/html; charset=UTF-8',
            'css' => 'text/css; charset=UTF-8',
            'js', 'mjs' => 'application/javascript; charset=UTF-8',
            'json', 'webmanifest' => 'application/manifest+json; charset=UTF-8',
            'svg' => 'image/svg+xml',
            default => File::mimeType($file) ?: 'application/octet-stream',
        };
    }
}
