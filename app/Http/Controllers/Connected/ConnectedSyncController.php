<?php

namespace App\Http\Controllers\Connected;

use App\Http\Controllers\Controller;
use App\Services\ConnectedLocalSynchronizer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ConnectedSyncController extends Controller
{
    public function status(Request $request, ConnectedLocalSynchronizer $synchronizer)
    {
        $paired = $this->paired();
        if (! $paired) {
            return response()->json(['success' => false, 'message' => 'Appareil non appaire.'], 401);
        }

        return response()->json($synchronizer->status($paired));
    }

    public function run(Request $request, ConnectedLocalSynchronizer $synchronizer)
    {
        $paired = $this->paired();
        if (! $paired) {
            return response()->json(['success' => false, 'message' => 'Appareil non appaire.'], 401);
        }

        try {
            $result = $synchronizer->sync($paired);
        } catch (\Throwable $e) {
            logger()->error('Sync error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json([
                'success' => false,
                'message' => 'Erreur sync: ' . $e->getMessage(),
            ], 500);
        }

        if (($result['success'] ?? false) && isset($result['paired_update']) && is_array($result['paired_update'])) {
            try {
                $this->updatePairedFile($paired, $result['paired_update']);
                $this->applyPermissionsFromSync($result['paired_update']);
            } catch (\Throwable $e) {
                logger()->error('Sync post-process error: ' . $e->getMessage());
            }
        }

        return response()->json($result, ($result['success'] ?? false) ? 200 : 422);
    }

    private function applyPermissionsFromSync(array $pairedUpdate): void
    {
        $permissions = $pairedUpdate['permissions'] ?? null;
        $user = $pairedUpdate['user'] ?? null;
        if (! is_array($permissions) || ! is_array($user) || empty($user['email'])) {
            return;
        }

        $userId = DB::table('utilisateurs')->where('email', $user['email'])->value('id');
        if (! $userId) {
            return;
        }

        $role = $user['role'] ?? 'admin';
        foreach ($permissions as $module => $acces) {
            DB::table('permissions')->updateOrInsert(
                ['utilisateur_id' => $userId, 'module' => $module],
                ['role' => $role, 'acces' => $acces]
            );
        }
    }

    private function paired(): ?array
    {
        $path = env('CONNECTED_PAIRED_PATH', storage_path('app/connected/paired.json'));
        if (! File::exists($path)) {
            return null;
        }

        $data = json_decode(File::get($path), true);

        return is_array($data) ? $data : null;
    }

    private function updatePairedFile(array $paired, array $updates): void
    {
        foreach (['user', 'permissions', 'sync', 'bootstrap'] as $key) {
            if (array_key_exists($key, $updates) && $updates[$key] !== null) {
                $paired[$key] = $updates[$key];
            }
        }
        $paired['last_sync_at'] = now()->toDateTimeString();

        $path = env('CONNECTED_PAIRED_PATH', storage_path('app/connected/paired.json'));
        File::ensureDirectoryExists(dirname($path));
        File::put($path, json_encode($paired, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }
}
