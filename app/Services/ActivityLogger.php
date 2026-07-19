<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class ActivityLogger
{
    public static function log(
        string $action,
        ?string $module = null,
        ?string $cibleType = null,
        ?int $cibleId = null,
        ?array $details = null,
        ?int $utilisateurId = null
    ): void {
        try {
            DB::table('historique_actions')->insert([
                'utilisateur_id' => $utilisateurId ?? session('utilisateur.id'),
                'action' => $action,
                'module' => $module,
                'cible_type' => $cibleType,
                'cible_id' => $cibleId,
                'details' => $details ? json_encode($details, JSON_UNESCAPED_UNICODE) : null,
                'ip' => request()->ip(),
                'user_agent' => mb_substr((string) request()->userAgent(), 0, 500),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } catch (\Throwable) {}
    }
}
