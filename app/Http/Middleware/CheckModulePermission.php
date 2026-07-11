<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class CheckModulePermission
{
    public function handle(Request $request, Closure $next, string $module): Response
    {
        $user = session('utilisateur');
        abort_unless($user && isset($user['id']), 403);

        if (($user['role'] ?? '') === 'admin') {
            return $next($request);
        }

        $userId = (int) $user['id'];

        $access = Cache::remember("perm.$userId.$module", 60, function () use ($userId, $module) {
            return $this->normalizeAccess(\DB::table('permissions')
                ->where('utilisateur_id', $userId)
                ->where('module', $module)
                ->value('acces'));
        });

        if (! in_array($access, ['lecture', 'ecriture'], true)) {
            return $this->deny($request);
        }

        if ($request->isMethodSafe()) {
            return $next($request);
        }

        if ($access !== 'ecriture') {
            return $this->deny($request);
        }

        return $next($request);
    }

    private function normalizeAccess(?string $access): string
    {
        $access = strtolower(trim((string) $access));

        return match ($access) {
            'écriture', 'ecriture', 'write' => 'ecriture',
            'lecture', 'read' => 'lecture',
            'masquer', 'cache', 'hidden', 'aucun', 'none', '' => 'masquer',
            default => $access,
        };
    }

    private function deny(Request $request): Response
    {
        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Acces refuse pour ce module.',
            ], 403);
        }

        abort(403, 'Acces refuse pour ce module.');
    }
}
