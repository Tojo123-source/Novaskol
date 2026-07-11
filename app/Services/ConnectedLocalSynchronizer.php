<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class ConnectedLocalSynchronizer
{
    private array $ignoredTables = [
        'migrations',
        'cache',
        'cache_locks',
        'sessions',
        'jobs',
        'job_batches',
        'failed_jobs',
        'password_reset_tokens',
        'sync_devices',
        'sync_batches',
        'sync_changes',
        'sync_conflicts',
        'sync_record_keys',
    ];

    public function __construct(private ConnectedBootstrapImporter $importer)
    {
    }

    public function sync(array $paired): array
    {
        $serverUrl = rtrim((string) ($paired['server_url'] ?? env('CONNECTED_SERVER_URL', '')), '/');
        $deviceUuid = (string) ($paired['device']['uuid'] ?? $paired['sync']['device_uuid'] ?? '');

        if ($serverUrl === '' || $deviceUuid === '') {
            return [
                'success' => false,
                'message' => 'Appareil connecte non appaire avec une adresse principale valide.',
            ];
        }

        try {
            $changes = $this->detectChanges($paired, false);
        } catch (\Throwable $e) {
            logger()->error('detectChanges failed: ' . $e->getMessage());
            $changes = [];
        }

        $pushResult = ['success' => true, 'message' => 'Rien a envoyer.'];

        if ($changes) {
            try {
                $pushResult = $this->pushChanges($serverUrl, $deviceUuid, $changes);
            } catch (\Throwable $e) {
                logger()->error('pushChanges failed: ' . $e->getMessage());
                return [
                    'success' => false,
                    'message' => 'Envoi au principal impossible: ' . $e->getMessage(),
                    'detected_changes' => count($changes),
                ];
            }

            if (! ($pushResult['success'] ?? false)) {
                return $pushResult + ['detected_changes' => count($changes)];
            }

            try {
                $this->persistAcceptedKeys($changes, $pushResult);
            } catch (\Throwable $e) {
                logger()->error('persistAcceptedKeys failed: ' . $e->getMessage());
            }

            if (isset($pushResult['next_bootstrap']) && is_array($pushResult['next_bootstrap'])) {
                try {
                    $this->importer->import($paired + ['bootstrap' => $pushResult['next_bootstrap']]);
                } catch (\Throwable $e) {
                    logger()->error('import next_bootstrap failed: ' . $e->getMessage());
                }
            }
        }

        try {
            $refreshResult = $this->refreshBootstrap($serverUrl, $deviceUuid, $paired);
        } catch (\Throwable $e) {
            logger()->error('Bootstrap refresh failed: ' . $e->getMessage());
            $refreshResult = ['success' => false, 'message' => $e->getMessage()];
        }

        return [
            'success' => true,
            'message' => $pushResult['message'] ?? 'Synchronisation terminee.',
            'detected_changes' => count($changes),
            'pushed_changes' => (int) ($pushResult['accepted'] ?? count($changes)),
            'applied_changes' => (int) ($pushResult['applied'] ?? 0),
            'refused_changes' => (int) ($pushResult['refused'] ?? 0),
            'refreshed' => (bool) ($refreshResult['success'] ?? false),
            'import' => $refreshResult['import'] ?? null,
            'paired_update' => $refreshResult['paired_update'] ?? null,
        ];
    }

    private function persistAcceptedKeys(array $changes, array $result): void
    {
        $acceptedUuids = array_map('strval', is_array($result['accepted_record_uuids'] ?? null) ? $result['accepted_record_uuids'] : []);
        $acceptAll = empty($acceptedUuids) && (int) ($result['refused'] ?? 0) === 0;

        foreach ($changes as $change) {
            $recordUuid = (string) ($change['record_uuid'] ?? '');
            if (! $acceptAll && ! in_array($recordUuid, $acceptedUuids, true)) {
                continue;
            }

            $table = (string) ($change['table_name'] ?? '');
            $id = (int) ($change['payload']['id'] ?? 0);
            if ($table === '' || $id <= 0) {
                continue;
            }

            $existing = DB::table('sync_record_keys')
                ->where('table_name', $table)
                ->where('record_id', $id)
                ->first();

            if ($existing) {
                DB::table('sync_record_keys')->where('id', $existing->id)->update([
                    'record_uuid' => $recordUuid,
                    'checksum' => $change['checksum'] ?? $this->checksum($change['payload'] ?? []),
                    'last_seen_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                DB::table('sync_record_keys')->insert([
                    'table_name' => $table,
                    'record_id' => $id,
                    'record_uuid' => $recordUuid,
                    'checksum' => $change['checksum'] ?? $this->checksum($change['payload'] ?? []),
                    'last_seen_at' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    public function status(array $paired): array
    {
        $changes = $this->detectChanges($paired, false);

        return [
            'success' => true,
            'pending_changes' => count($changes),
            'tables' => collect($changes)->groupBy('table_name')->map->count()->all(),
        ];
    }

    private function detectChanges(array $paired, bool $persistNewKeys = true): array
    {
        $tables = $this->writableTables($paired);
        $changes = [];

        foreach ($tables as $table) {
            if (! $this->canTrackTable($table)) {
                continue;
            }

            $known = DB::table('sync_record_keys')->where('table_name', $table)->get()->keyBy('record_id');

            foreach ($known as $recordId => $key) {
                $row = DB::table($table)->where('id', (int) $recordId)->first();
                if (! $row) {
                    $changes[] = $this->change($table, 'delete', (string) $key->record_uuid, ['id' => (int) $recordId], (string) ($key->checksum ?? ''));
                    continue;
                }

                $payload = (array) $row;
                $checksum = $this->checksum($payload);
                if ($checksum !== (string) ($key->checksum ?? '')) {
                    $changes[] = $this->change($table, 'update', (string) $key->record_uuid, $payload, $checksum);
                }
            }

            $knownIds = $known->keys()->map(fn ($id) => (int) $id)->all();
            DB::table($table)
                ->select('*')
                ->when($knownIds, fn ($query) => $query->whereNotIn('id', $knownIds))
                ->orderBy('id')
                ->chunk(200, function ($rows) use ($table, $persistNewKeys, &$changes) {
                    foreach ($rows as $row) {
                        $payload = (array) $row;
                        $recordUuid = (string) Str::uuid();
                        $checksum = $this->checksum($payload);

                        if ($persistNewKeys) {
                            DB::table('sync_record_keys')->insert([
                                'table_name' => $table,
                                'record_id' => (int) $payload['id'],
                                'record_uuid' => $recordUuid,
                                'checksum' => $checksum,
                                'last_seen_at' => now(),
                                'created_at' => now(),
                                'updated_at' => now(),
                            ]);
                        }

                        $changes[] = $this->change($table, 'create', $recordUuid, $payload, $checksum);
                    }
                });
        }

        return $changes;
    }

    private function pushChanges(string $serverUrl, string $deviceUuid, array $changes): array
    {
        $response = Http::timeout(30)->acceptJson()->post($serverUrl.'/reseau-local/recevoir-lot', [
            'device_uuid' => $deviceUuid,
            'batch_uuid' => (string) Str::uuid(),
            'changes' => $changes,
        ]);

        if (! $response->successful() || $response->json('success') === false) {
            return [
                'success' => false,
                'message' => $response->json('message') ?: 'Envoi des changements refuse par le principal.',
                'status' => $response->status(),
            ];
        }

        $data = $response->json();
        $this->markAcceptedChanges($changes, $data);

        return $data;
    }

    private function refreshBootstrap(string $serverUrl, string $deviceUuid, array $paired): array
    {
        $response = Http::timeout(30)->acceptJson()->post($serverUrl.'/reseau-local/bootstrap-appareil', [
            'device_uuid' => $deviceUuid,
        ]);

        if (! $response->successful() || $response->json('success') === false) {
            return [
                'success' => false,
                'message' => $response->json('message') ?: 'Actualisation impossible depuis le principal.',
                'status' => $response->status(),
            ];
        }

        $data = $response->json();
        $import = isset($data['bootstrap']) && is_array($data['bootstrap'])
            ? $this->importer->import($paired + ['bootstrap' => $data['bootstrap']])
            : null;

        return [
            'success' => true,
            'import' => $import,
            'paired_update' => [
                'user' => $data['user'] ?? null,
                'permissions' => $data['permissions'] ?? null,
                'sync' => $data['sync'] ?? null,
                'bootstrap' => $data['bootstrap'] ?? null,
            ],
        ];
    }

    private function markAcceptedChanges(array $changes, array $result): void
    {
        if (($result['success'] ?? false) !== true) {
            return;
        }

        $acceptedRecordUuids = array_map(
            'strval',
            is_array($result['accepted_record_uuids'] ?? null) ? $result['accepted_record_uuids'] : []
        );
        $acceptAll = empty($acceptedRecordUuids) && (int) ($result['refused'] ?? 0) === 0;

        foreach ($changes as $change) {
            $recordUuid = (string) ($change['record_uuid'] ?? '');
            if (! $acceptAll && ! in_array($recordUuid, $acceptedRecordUuids, true)) {
                continue;
            }

            if ($change['operation'] === 'delete') {
                DB::table('sync_record_keys')->where('record_uuid', $recordUuid)->delete();
                continue;
            }

            DB::table('sync_record_keys')->where('record_uuid', $recordUuid)->update([
                'checksum' => $change['checksum'] ?? $this->checksum($change['payload'] ?? []),
                'last_seen_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    private function writableTables(array $paired): array
    {
        $tables = $paired['sync']['writable_tables'] ?? [];

        if (! is_array($tables) || empty($tables)) {
            $role = (string) ($paired['user']['role'] ?? '');
            $tables = app(LocalSyncPolicy::class)->writableTablesForRole($role);
        }

        return collect($tables)
            ->map(fn ($table) => $table === 'utilisateur' ? 'utilisateurs' : (string) $table)
            ->reject(fn ($table) => in_array($table, $this->ignoredTables, true))
            ->unique()
            ->values()
            ->all();
    }

    private function canTrackTable(string $table): bool
    {
        return $table !== ''
            && ! in_array($table, $this->ignoredTables, true)
            && Schema::hasTable($table)
            && Schema::hasTable('sync_record_keys')
            && Schema::hasColumn($table, 'id');
    }

    private function change(string $table, string $operation, string $recordUuid, array $payload, string $checksum): array
    {
        return [
            'uuid' => (string) Str::uuid(),
            'module' => 'connecte_desktop',
            'table_name' => $table,
            'record_uuid' => $recordUuid,
            'operation' => $operation,
            'payload' => $payload,
            'checksum' => $checksum,
            'action_at' => now()->toDateTimeString(),
        ];
    }

    private function checksum(array $payload): string
    {
        return hash('sha256', json_encode($payload, JSON_UNESCAPED_UNICODE));
    }
}
