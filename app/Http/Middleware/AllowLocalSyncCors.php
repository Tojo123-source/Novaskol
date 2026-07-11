<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AllowLocalSyncCors
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->is('reseau-local/ping')
            && ! $request->is('reseau-local/manifest-appareil')
            && ! $request->is('reseau-local/appairer-appareil')
            && ! $request->is('reseau-local/bootstrap-appareil')
            && ! $request->is('reseau-local/recevoir-lot')) {
            return $next($request);
        }

        if ($request->isMethod('OPTIONS')) {
            return $this->withHeaders(response()->noContent());
        }

        return $this->withHeaders($next($request));
    }

    private function withHeaders(Response $response): Response
    {
        $response->headers->set('Access-Control-Allow-Origin', '*');
        $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, PATCH, DELETE, OPTIONS');
        $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Accept, X-Requested-With');
        $response->headers->set('Access-Control-Max-Age', '86400');

        return $response;
    }
}
