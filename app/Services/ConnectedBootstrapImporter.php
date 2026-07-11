<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Str;

class ConnectedBootstrapImporter
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

    private array $fileColumns = [
        'eleves' => ['photo'],
        'professeurs' => ['photo'],
        'staff' => ['photo'],
        'utilisateurs' => ['avatar', 'photo'],
        'ecole' => ['logo', 'logo_ecole'],
        'personnes' => ['photo'],
        'enseignants' => ['photo'],
        'dossiers' => ['fichier'],
        'fichiers' => ['chemin', 'fichier'],
    ];

    public function import(array $paired): array
    {
        $bootstrap = $paired['bootstrap'] ?? null;
        $tables = is_array($bootstrap) ? ($bootstrap['tables'] ?? []) : [];
        $bootstrapChecksum = is_array($bootstrap) ? $this->checksum($bootstrap) : null;

        if (! is_array($tables) || empty($tables)) {
            return ['imported_tables' => 0, 'imported_records' => 0, 'bootstrap_checksum' => $bootstrapChecksum];
        }

        $this->ensureSyncRecordKeysTable();
        $this->disableForeignKeys();

        $importedTables = 0;
        $importedRecords = 0;

        try {
            foreach ($tables as $rawTable => $records) {
                $table = $this->normalizeTableName((string) $rawTable);
                if ($table === '' || in_array($table, $this->ignoredTables, true) || ! is_iterable($records)) {
                    continue;
                }

                if (! Schema::hasTable($table)) {
                    $this->createTableFromRecords($table, $records);
                }

                if (! Schema::hasTable($table)) {
                    continue;
                }

                $columns = Schema::getColumnListing($table);
                if (! in_array('id', $columns, true)) {
                    continue;
                }

                $tableImported = 0;
                foreach ($records as $record) {
                    $payload = $this->recordPayload($record);
                    if (! is_array($payload) || empty($payload['id'])) {
                        continue;
                    }

                    $clean = $this->filterPayload($table, $payload, $columns);
                    if (empty($clean)) {
                        continue;
                    }

                    DB::table($table)->updateOrInsert(['id' => (int) $payload['id']], $clean);

                    $dbRow = DB::table($table)->where('id', (int) $payload['id'])->first();
                    $dbChecksum = $dbRow ? hash('sha256', json_encode((array) $dbRow, JSON_UNESCAPED_UNICODE)) : '';

                    $this->rememberRecordKey(
                        $table,
                        (int) $payload['id'],
                        is_array($record) ? (string) ($record['record_uuid'] ?? '') : '',
                        $dbChecksum
                    );

                    $this->downloadFilesIfNeeded($table, $clean, $paired);

                    $tableImported++;
                    $importedRecords++;
                }

                if ($tableImported > 0) {
                    $importedTables++;
                }
            }
        } finally {
            $this->enableForeignKeys();
        }

        if ($bootstrapChecksum) {
            $this->rememberBootstrapChecksum($bootstrapChecksum);
        }

        return ['imported_tables' => $importedTables, 'imported_records' => $importedRecords, 'bootstrap_checksum' => $bootstrapChecksum];
    }

    public function checksum(array $bootstrap): string
    {
        return hash('sha256', json_encode($bootstrap, JSON_UNESCAPED_UNICODE));
    }

    private function normalizeTableName(string $table): string
    {
        return $table === 'utilisateur' ? 'utilisateurs' : $table;
    }

    private function canImportTable(string $table): bool
    {
        return $table !== ''
            && ! in_array($table, $this->ignoredTables, true)
            && Schema::hasTable($table);
    }

    private function createTableFromRecords(string $table, iterable $records): void
    {
        $dataColumns = [];
        foreach ($records as $record) {
            $payload = $this->recordPayload($record);
            if (! is_array($payload)) {
                continue;
            }
            foreach ($payload as $key => $value) {
                if ($key === 'id') {
                    continue;
                }
                $dataColumns[$key] = true;
            }
        }

        Schema::create($table, function (Blueprint $table) use ($dataColumns) {
            $table->id();
            foreach (array_keys($dataColumns) as $name) {
                if ($name === 'created_at' || $name === 'updated_at') {
                    continue;
                }
                $table->text($name)->nullable();
            }
            if (! empty($dataColumns['created_at']) || ! empty($dataColumns['updated_at'])) {
                $table->timestamps();
            }
        });

        logger()->info("Table creee dynamiquement: {$table}");
    }

    private function filterPayload(string $table, array $payload, array $columns): array
    {
        $clean = [];
        foreach ($payload as $key => $value) {
            if (in_array($key, $columns, true)) {
                $clean[$key] = $value;
            }
        }

        if ($table === 'utilisateurs' && in_array('mot_de_passe', $columns, true)) {
            if (empty($clean['mot_de_passe'])) {
                $existing = null;
                if (! empty($payload['id'])) {
                    $existing = DB::table('utilisateurs')->where('id', (int) $payload['id'])->value('mot_de_passe');
                }
                $clean['mot_de_passe'] = $existing ?: Hash::make(Str::random(40));
            }
        }

        if (in_array('created_at', $columns, true) && empty($clean['created_at'])) {
            $clean['created_at'] = now();
        }
        if (in_array('updated_at', $columns, true) && empty($clean['updated_at'])) {
            $clean['updated_at'] = now();
        }

        return $clean;
    }

    private function recordPayload(mixed $record): array
    {
        if (! is_array($record)) {
            return [];
        }

        $payload = $record['data'] ?? $record;

        return is_array($payload) ? $payload : [];
    }

    private function rememberRecordKey(string $table, int $recordId, string $recordUuid, string $checksum): void
    {
        if ($recordUuid === '' || ! Schema::hasTable('sync_record_keys')) {
            return;
        }

        $existingForRecord = DB::table('sync_record_keys')
            ->where('table_name', $table)
            ->where('record_id', $recordId)
            ->first();

        if ($existingForRecord) {
            DB::table('sync_record_keys')->where('id', $existingForRecord->id)->update([
                'record_uuid' => $recordUuid,
                'checksum' => $checksum !== '' ? $checksum : null,
                'last_seen_at' => now(),
                'updated_at' => now(),
            ]);

            return;
        }

        $existingForUuid = DB::table('sync_record_keys')->where('record_uuid', $recordUuid)->first();
        if ($existingForUuid) {
            DB::table('sync_record_keys')->where('id', $existingForUuid->id)->update([
                'table_name' => $table,
                'record_id' => $recordId,
                'checksum' => $checksum !== '' ? $checksum : null,
                'last_seen_at' => now(),
                'updated_at' => now(),
            ]);

            return;
        }

        DB::table('sync_record_keys')->insert([
            'table_name' => $table,
            'record_id' => $recordId,
            'record_uuid' => $recordUuid,
            'checksum' => $checksum !== '' ? $checksum : null,
            'last_seen_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    private function ensureSyncRecordKeysTable(): void
    {
        if (Schema::hasTable('sync_record_keys')) {
            return;
        }

        Schema::create('sync_record_keys', function (Blueprint $table) {
            $table->id();
            $table->string('table_name', 100)->index();
            $table->unsignedBigInteger('record_id')->index();
            $table->string('record_uuid', 64)->unique();
            $table->string('checksum', 128)->nullable();
            $table->timestamp('last_seen_at')->nullable();
            $table->timestamps();
            $table->unique(['table_name', 'record_id']);
        });
    }

    private function downloadFilesIfNeeded(string $table, array $clean, array $paired): void
    {
        $serverUrl = $paired['server_url'] ?? '';
        if (! $serverUrl) {
            return;
        }

        $columns = $this->fileColumns[$table] ?? [];
        if (! $columns) {
            return;
        }

        foreach ($columns as $col) {
            $filePath = $clean[$col] ?? '';
            if (! $filePath || ! is_string($filePath) || trim($filePath) === '') {
                continue;
            }
            if (str_contains($filePath, 'default-avatar') || str_contains($filePath, 'default.')) {
                continue;
            }

            $this->downloadFile($serverUrl, $filePath);
        }
    }

    private function downloadFile(string $serverUrl, string $filePath): void
    {
        try {
            $relativePath = ltrim($filePath, '/');
            $localPath = public_path('legacy/' . $relativePath);
            $localDir = dirname($localPath);

            if (file_exists($localPath)) {
                return;
            }

            if (! is_dir($localDir)) {
                @mkdir($localDir, 0755, true);
            }

            $url = rtrim($serverUrl, '/') . '/legacy/' . $relativePath;
            $context = stream_context_create(['http' => ['timeout' => 10, 'follow_location' => true]]);
            $content = @file_get_contents($url, false, $context);

            if ($content !== false) {
                file_put_contents($localPath, $content);
                logger()->info("Fichier telecharge: {$relativePath}");
            } else {
                logger()->warning("Impossible de telecharger: {$relativePath} depuis {$serverUrl}");
            }
        } catch (\Throwable $e) {
            logger()->error("Erreur telechargement fichier {$filePath}: " . $e->getMessage());
        }
    }

    private function rememberBootstrapChecksum(string $checksum): void
    {
        if (! Schema::hasTable('parametres')) {
            return;
        }

        $columns = Schema::getColumnListing('parametres');
        $values = ['valeur' => $checksum];
        if (in_array('updated_at', $columns, true)) {
            $values['updated_at'] = now();
        }
        if (in_array('created_at', $columns, true)) {
            $values['created_at'] = now();
        }

        DB::table('parametres')->updateOrInsert(['cle' => 'connected_bootstrap_checksum'], $values);
    }

    private function disableForeignKeys(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            DB::statement('PRAGMA foreign_keys = OFF');
            return;
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=0');
    }

    private function enableForeignKeys(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            DB::statement('PRAGMA foreign_keys = ON');
            return;
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
}
