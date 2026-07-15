<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class InjectBrandingAssets
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (!$response->headers->has('Content-Type') || !str_starts_with($response->headers->get('Content-Type'), 'text/html')) {
            return $response;
        }

        $content = $response->getContent();
        if (!is_string($content) || !str_contains($content, '</head>') || str_contains($content, 'novaskol.webmanifest')) {
            return $response;
        }

        $branding = "\n    <meta name=\"theme-color\" content=\"#00c853\">\n"
            . '    <link rel="icon" type="image/png" href="'.asset('novaskol-icon.png').'" sizes="any">'
            . "\n" . '    <link rel="manifest" href="'.asset('novaskol.webmanifest').'">'
            . "\n";

        $response->setContent(str_replace('</head>', $branding."\n</head>", $content));
        return $response;
    }
}
