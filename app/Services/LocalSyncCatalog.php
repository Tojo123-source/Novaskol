<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class LocalSyncCatalog
{
    private array $safeParameterKeys = [
        'nom_ecole',
        'code_ecole',
        'adresse_ecole',
        'telephone_ecole',
        'email_ecole',
        'annee_scolaire',
        'date_debut',
        'date_fin',
        'logo_ecole',
        'devise_nom',
        'devise_symbole',
        'langue_interface',
    ];

    public function tables(): array
    {
        try {
            $database = DB::getDatabaseName();
            $rows = DB::select("SHOW FULL TABLES WHERE Table_type = 'BASE TABLE'");
            $key = 'Tables_in_'.$database;
            $tables = collect($rows)
                ->map(fn ($row) => (array) $row)
                ->map(fn ($row) => $row[$key] ?? reset($row))
                ->filter()
                ->reject(fn ($table) => in_array($table, ['migrations', 'cache', 'cache_locks', 'sessions', 'jobs', 'job_batches', 'failed_jobs', 'password_reset_tokens'], true))
                ->reject(fn ($table) => str_starts_with((string) $table, 'sync_'))
                ->values()
                ->all();

            if (! empty($tables)) {
                return $tables;
            }
        } catch (\Throwable) {
            // Fallback for setup moments where the database is not fully reachable yet.
        }

        return [
            'ecole',
            'parametres',
            'classes',
            'matieres',
            'classe_matieres',
            'eleves',
            'parents',
            'parent_eleves',
            'professeurs',
            'professeurs_classes',
            'staff',
            'mpiasa',
            'utilisateurs',
            'permissions',
            'roles',
            'departements',
            'notes',
            'remarques',
            'examen_blanc',
            'remarques_examen_blanc',
            'bulletins',
            'emploi_du_temps',
            'evenements',
            'presence_eleves',
            'presence_personnels',
            'presence_staff',
            'paiements',
            'paiements_assignes',
            'types_paiements',
            'revenus',
            'depenses',
            'salaires_assignes',
            'dossiers',
            'fichiers',
            'notifications',
            'conversations',
            'conversation_participants',
            'messages',
            'message_reactions',
            'typing_status',
            'teacher_lessons',
            'teacher_tasks',
            'personnes',
            'enseignants',
            'equipements',
            'ressources',
            'reservations',
            'reservations_ressources',
            'salles',
            'licence',
        ];
    }

    public function prepareRecordKeys(): int
    {
        if (! Schema::hasTable('sync_record_keys')) {
            return 0;
        }

        $created = 0;
        foreach ($this->tables() as $table) {
            if ($table === 'sync_record_keys') {
                continue;
            }

            if (! Schema::hasTable($table) || ! Schema::hasColumn($table, 'id')) {
                continue;
            }

            DB::table($table)->select('id')->orderBy('id')->chunk(500, function ($rows) use ($table, &$created) {
                foreach ($rows as $row) {
                    $this->ensureRecordKey($table, (int) $row->id) && $created++;
                }
            });
        }

        return $created;
    }

    public function ensureRecordKey(string $table, int $recordId): bool
    {
        if ($recordId <= 0) {
            return false;
        }

        $exists = DB::table('sync_record_keys')
            ->where('table_name', $table)
            ->where('record_id', $recordId)
            ->exists();

        if ($exists) {
            DB::table('sync_record_keys')
                ->where('table_name', $table)
                ->where('record_id', $recordId)
                ->update(['last_seen_at' => now(), 'updated_at' => now()]);

            return false;
        }

        DB::table('sync_record_keys')->insert([
            'table_name' => $table,
            'record_id' => $recordId,
            'record_uuid' => (string) Str::uuid(),
            'last_seen_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return true;
    }

    public function createSnapshotBatch(string $deviceUuid, ?int $userId = null): object
    {
        $this->prepareRecordKeys();

        $batchUuid = (string) Str::uuid();
        $recordsByTable = DB::table('sync_record_keys')
            ->select('table_name', DB::raw('COUNT(*) as total'))
            ->groupBy('table_name')
            ->orderBy('table_name')
            ->get();

        $total = (int) $recordsByTable->sum('total');
        $summary = [
            'type' => 'apercu_local',
            'tables' => $recordsByTable->map(fn ($row) => [
                'table' => $row->table_name,
                'total' => (int) $row->total,
            ])->values()->all(),
        ];

        DB::table('sync_batches')->insert([
            'uuid' => $batchUuid,
            'device_uuid' => $deviceUuid,
            'direction' => 'export',
            'statut' => 'pret',
            'total_changements' => $total,
            'total_appliques' => 0,
            'total_conflits' => 0,
            'resume_json' => json_encode($summary, JSON_UNESCAPED_UNICODE),
            'message_erreur' => null,
            'demarre_at' => now(),
            'termine_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('sync_changes')->insert([
            'uuid' => (string) Str::uuid(),
            'batch_uuid' => $batchUuid,
            'device_uuid' => $deviceUuid,
            'utilisateur_id' => $userId,
            'module' => 'reseau_local',
            'table_name' => 'sync_record_keys',
            'record_uuid' => $batchUuid,
            'operation' => 'snapshot',
            'payload_json' => json_encode($summary, JSON_UNESCAPED_UNICODE),
            'checksum' => hash('sha256', json_encode($summary)),
            'statut' => 'pret',
            'message_erreur' => null,
            'action_at' => now(),
            'applique_at' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return DB::table('sync_batches')->where('uuid', $batchUuid)->first();
    }

    public function bootstrapForUser(object $user): array
    {
        $this->prepareRecordKeys();

        $role = (string) ($user->role ?? '');
        $tables = [];

        $tables['ecole'] = $this->records('ecole');
        $tables['parametres'] = $this->records('parametres', function ($query) {
            $query->whereIn('cle', $this->safeParameterKeys);
        });
        $tables['utilisateurs'] = $this->records('utilisateurs', null, ['mot_de_passe']);
        $tables['permissions'] = $this->records('permissions', function ($query) use ($user) {
            $query->where('utilisateur_id', (int) $user->id);
        });

        if ($role === 'admin') {
            $tables += $this->adminBootstrap();
        } elseif ($role === 'enseignant') {
            $tables += $this->teacherBootstrap($user);
        } elseif ($role === 'staff') {
            $tables += $this->staffBootstrap($user);
        } elseif ($role === 'parent') {
            $tables += $this->parentBootstrap($user);
        }

        if (! array_key_exists('evenements', $tables)) {
            $tables['evenements'] = $this->records('evenements');
        }

        $tables += $this->communicationBootstrap($user);

        $summary = collect($tables)
            ->map(fn ($records, $table) => ['table' => $table, 'total' => count($records)])
            ->values()
            ->all();

        return [
            'generated_at' => now()->toDateTimeString(),
            'role' => $role,
            'module_catalog' => $this->moduleCatalog(),
            'summary' => $summary,
            'tables' => $tables,
        ];
    }

    private function moduleCatalog(): array
    {
        return collect(config('novaskol.modules', []))
            ->map(function (array $module, string $key) {
                return [
                    'key' => $key,
                    'label' => trim(str_replace('|--', '', (string) ($module['label'] ?? $key))),
                    'icon' => (string) ($module['icon'] ?? ($module['section_icon'] ?? '')),
                    'section' => ! empty($module['section']),
                ];
            })
            ->values()
            ->all();
    }

    private function adminBootstrap(): array
    {
        $tables = [];

        foreach ($this->tables() as $table) {
            $hidden = $table === 'utilisateurs' ? ['mot_de_passe', 'password', 'remember_token'] : [];
            $tables[$table] = $this->records($table, null, $hidden, null);
        }

        return $tables;
    }

    private function teacherBootstrap(object $user): array
    {
        $teacher = Schema::hasTable('professeurs')
            ? DB::table('professeurs')->where('email', $user->email)->first()
            : null;

        if (! $teacher) {
            return [];
        }

        $classIds = Schema::hasTable('professeurs_classes')
            ? DB::table('professeurs_classes')->where('professeur_id', $teacher->id)->pluck('classe_id')->filter()->unique()->values()->all()
            : [];
        $studentIds = Schema::hasTable('eleves') && $classIds
            ? DB::table('eleves')->whereIn('id_classe', $classIds)->pluck('id')->filter()->unique()->values()->all()
            : [];
        $subjectIds = collect([$teacher->matiere_id ?? null])->filter()->unique()->values()->all();

        if (Schema::hasTable('classe_matieres') && $classIds) {
            $subjectIds = collect($subjectIds)
                ->merge(DB::table('classe_matieres')->whereIn('id_classe', $classIds)->pluck('id_matiere'))
                ->filter()
                ->unique()
                ->values()
                ->all();
        }

        return [
            'professeurs' => $this->records('professeurs', fn ($query) => $query->where('id', $teacher->id)),
            'professeurs_classes' => $this->records('professeurs_classes', fn ($query) => $query->where('professeur_id', $teacher->id)),
            'classes' => $this->records('classes', fn ($query) => $classIds ? $query->whereIn('id', $classIds) : $query->whereRaw('1=0')),
            'matieres' => $this->records('matieres', fn ($query) => $subjectIds ? $query->whereIn('id', $subjectIds) : $query->whereRaw('1=0')),
            'classe_matieres' => $this->records('classe_matieres', fn ($query) => $classIds ? $query->whereIn('id_classe', $classIds) : $query->whereRaw('1=0')),
            'eleves' => $this->records('eleves', fn ($query) => $classIds ? $query->whereIn('id_classe', $classIds) : $query->whereRaw('1=0')),
            'notes' => $this->records('notes', fn ($query) => $studentIds && $subjectIds ? $query->whereIn('id_eleve', $studentIds)->whereIn('id_matiere', $subjectIds) : $query->whereRaw('1=0')),
            'examen_blanc' => $this->records('examen_blanc', fn ($query) => $studentIds && $subjectIds ? $query->whereIn('eleve_id', $studentIds)->whereIn('matiere_id', $subjectIds) : $query->whereRaw('1=0')),
            'presence_eleves' => $this->records('presence_eleves', fn ($query) => $studentIds ? $query->whereIn('eleve_id', $studentIds) : $query->whereRaw('1=0')),
            'emploi_du_temps' => $this->records('emploi_du_temps', fn ($query) => $classIds ? $query->whereIn('id_classe', $classIds) : $query->whereRaw('1=0')),
            'teacher_lessons' => $this->records('teacher_lessons', fn ($query) => $query->where('professeur_id', $teacher->id)),
            'teacher_tasks' => $this->records('teacher_tasks', fn ($query) => $query->where('professeur_id', $teacher->id)),
        ];
    }

    private function staffBootstrap(object $user): array
    {
        $staff = Schema::hasTable('staff')
            ? DB::table('staff')->where('email', $user->email)->first()
            : null;

        return [
            'staff' => $staff ? $this->records('staff', fn ($query) => $query->where('id', $staff->id)) : [],
            'roles' => $this->records('roles'),
            'departements' => $this->records('departements'),
            'classes' => $this->records('classes'),
            'eleves' => $this->records('eleves'),
            'matieres' => $this->records('matieres'),
            'classe_matieres' => $this->records('classe_matieres'),
            'professeurs' => $this->records('professeurs'),
            'presence_eleves' => $this->records('presence_eleves'),
            'presence_personnels' => Schema::hasTable('presence_personnels') ? $this->records('presence_personnels') : [],
            'presence_staff' => $this->records('presence_staff'),
            'evenements' => $this->records('evenements'),
            'emploi_du_temps' => $this->records('emploi_du_temps'),
        ];
    }

    private function parentBootstrap(object $user): array
    {
        $studentIds = Schema::hasTable('parent_eleves')
            ? DB::table('parent_eleves')->where('parent_user_id', (int) $user->id)->pluck('eleve_id')->filter()->unique()->values()->all()
            : [];

        $classIds = $studentIds && Schema::hasTable('eleves')
            ? DB::table('eleves')->whereIn('id', $studentIds)->pluck('id_classe')->filter()->unique()->values()->all()
            : [];

        return [
            'parent_eleves' => $this->records('parent_eleves', fn ($query) => $query->where('parent_user_id', (int) $user->id)),
            'eleves' => $this->records('eleves', fn ($query) => $studentIds ? $query->whereIn('id', $studentIds) : $query->whereRaw('1=0')),
            'classes' => $this->records('classes', fn ($query) => $classIds ? $query->whereIn('id', $classIds) : $query->whereRaw('1=0')),
            'bulletins' => $this->records('bulletins', fn ($query) => $studentIds ? $query->whereIn('id_eleve', $studentIds) : $query->whereRaw('1=0')),
            'presence_eleves' => $this->records('presence_eleves', fn ($query) => $studentIds ? $query->whereIn('eleve_id', $studentIds) : $query->whereRaw('1=0')),
            'paiements' => $this->records('paiements', fn ($query) => $studentIds ? $query->where('type_personne', 'eleve')->whereIn('personne_id', array_map('strval', $studentIds)) : $query->whereRaw('1=0')),
            'paiements_assignes' => $this->records('paiements_assignes', fn ($query) => $studentIds ? $query->whereIn('eleve_id', $studentIds) : $query->whereRaw('1=0')),
            'emploi_du_temps' => $this->records('emploi_du_temps', fn ($query) => $classIds ? $query->whereIn('id_classe', $classIds) : $query->whereRaw('1=0')),
        ];
    }

    private function communicationBootstrap(object $user): array
    {
        if (! Schema::hasTable('conversation_participants')) {
            return [];
        }

        $role = (string) ($user->role ?? '');
        $conversationIds = DB::table('conversation_participants')
            ->where('user_id', (int) $user->id)
            ->where('user_type', $role)
            ->pluck('conversation_id')
            ->filter()
            ->unique()
            ->values()
            ->all();

        return [
            'conversations' => $this->records('conversations', fn ($query) => $conversationIds ? $query->whereIn('id', $conversationIds) : $query->whereRaw('1=0')),
            'conversation_participants' => $this->records('conversation_participants', fn ($query) => $conversationIds ? $query->whereIn('conversation_id', $conversationIds) : $query->whereRaw('1=0')),
            'messages' => $this->records('messages', fn ($query) => $conversationIds ? $query->whereIn('conversation_id', $conversationIds) : $query->whereRaw('1=0')),
        ];
    }

    private function records(string $table, ?callable $scope = null, array $hidden = [], ?int $limit = 1000): array
    {
        if (! Schema::hasTable($table) || ! Schema::hasColumn($table, 'id')) {
            return [];
        }

        $query = DB::table($table)->orderBy('id');
        if ($scope) {
            $scope($query);
        }

        if ($limit !== null) {
            $query->limit($limit);
        }

        return $query->get()->map(function ($record) use ($table, $hidden) {
            $row = (array) $record;
            foreach ($hidden as $key) {
                unset($row[$key]);
            }
            $key = DB::table('sync_record_keys')
                ->where('table_name', $table)
                ->where('record_id', (int) $record->id)
                ->first();

            return [
                'record_uuid' => $key->record_uuid ?? null,
                'checksum' => hash('sha256', json_encode($row, JSON_UNESCAPED_UNICODE)),
                'data' => $row,
            ];
        })->values()->all();
    }
}
