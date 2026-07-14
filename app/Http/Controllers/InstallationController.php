<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class InstallationController extends Controller
{
    public function show()
    {
        if (config('novaskol.edition', 'principal') === 'connecte') {
            return redirect()->route('connected.setup');
        }

        $this->ensureInstallerAccess();
        $this->ensureCoreTables();

        $params = DB::table('parametres')->pluck('valeur', 'cle')->all();
        $school = DB::table('ecole')->first();
        $lockPath = storage_path('app/novaskol-installed.lock');
        $installedAt = File::exists($lockPath) ? date('d/m/Y H:i', File::lastModified($lockPath)) : null;

        return view('installation.setup', [
            'installed' => $this->isInstalled(),
            'currentMode' => DB::table('parametres')->where('cle', 'mode_installation')->value('valeur') ?: 'production',
            'demoDumpExists' => File::exists(database_path('distribution/dump_demo.sql')),
            'emptyDumpExists' => File::exists(database_path('distribution/dump_empty.sql')),
            'setupDefaults' => [
                'nom_ecole' => $params['nom_ecole'] ?? ($school->nom ?? ''),
                'adresse_ecole' => $params['adresse_ecole'] ?? '',
                'telephone_ecole' => $params['telephone_ecole'] ?? '',
                'email_ecole' => $params['email_ecole'] ?? '',
                'annee_scolaire' => $params['annee_scolaire'] ?? (date('Y').'-'.(date('Y') + 1)),
                'admin_nom' => session('utilisateur.nom', ''),
                'admin_email' => session('utilisateur.email', ''),
            ],
            'installedAt' => $installedAt,
        ]);
    }

    public function store(Request $request)
    {
        abort_if(config('novaskol.edition', 'principal') === 'connecte', 403, 'Cette edition se connecte a une ecole existante.');

        $this->ensureInstallerAccess();
        $this->ensureCoreTables();
        abort_if($this->isInstalled() && ! $request->boolean('force_update'), 403, 'Novaskol est deja initialise.');

        $data = $request->validate([
            'mode' => ['required', 'in:empty,demo'],
            'nom_ecole' => ['required', 'string', 'max:150'],
            'adresse_ecole' => ['nullable', 'string', 'max:255'],
            'telephone_ecole' => ['nullable', 'string', 'max:80'],
            'email_ecole' => ['nullable', 'email', 'max:150'],
            'annee_scolaire' => ['required', 'string', 'max:20'],
            'admin_nom' => ['required', 'string', 'max:120'],
            'admin_email' => ['required', 'email', 'max:120'],
            'admin_password' => ['required', 'string', 'min:8', 'confirmed'],
            'force_update' => ['nullable'],
        ]);

        DB::transaction(function () use ($data) {
            DB::table('ecole')->updateOrInsert(['id' => 1], [
                'nom' => trim($data['nom_ecole']),
                'logo' => DB::table('ecole')->where('id', 1)->value('logo') ?: 'novaskol.png',
            ]);

            foreach ([
                'nom_ecole' => $data['nom_ecole'],
                'adresse_ecole' => $data['adresse_ecole'] ?? '',
                'telephone_ecole' => $data['telephone_ecole'] ?? '',
                'email_ecole' => $data['email_ecole'] ?? '',
                'annee_scolaire' => $data['annee_scolaire'],
                'mode_installation' => $data['mode'],
                'novaskol_version' => config('app.version', '1.0.0'),
            ] as $key => $value) {
                DB::table('parametres')->updateOrInsert(['cle' => $key], ['valeur' => (string) $value]);
            }

            $adminId = DB::table('utilisateurs')->updateOrInsert(
                ['email' => strtolower(trim($data['admin_email'])), 'role' => 'admin'],
                [
                    'nom' => trim($data['admin_nom']),
                    'mot_de_passe' => Hash::make($data['admin_password']),
                    'avatar' => 'images/default-avatar.png',
                ]
            );

            $userId = (int) DB::table('utilisateurs')
                ->where('email', strtolower(trim($data['admin_email'])))
                ->where('role', 'admin')
                ->value('id');

            foreach (array_keys(config('novaskol.modules')) as $module) {
                DB::table('permissions')->updateOrInsert(
                    ['utilisateur_id' => $userId, 'module' => $module],
                    ['role' => 'admin', 'acces' => 'ecriture']
                );
            }

            if ($data['mode'] === 'demo' && ! $this->hasRealSchoolData()) {
                $this->seedDemoData($data['annee_scolaire']);
            }
        });

        File::ensureDirectoryExists(storage_path('app'));
        File::put(storage_path('app/novaskol-installed.lock'), now()->toDateTimeString());

        $admin = DB::table('utilisateurs')
            ->where('email', strtolower(trim($data['admin_email'])))
            ->where('role', 'admin')
            ->first();

        if ($admin) {
            $request->session()->put('utilisateur', [
                'id' => $admin->id,
                'nom' => $admin->nom,
                'email' => $admin->email,
                'role' => $admin->role,
            ]);
        }

        return redirect()->route('dashboard')->with('success', 'Novaskol est prêt. Bienvenue dans votre espace administrateur.');
    }

    public function resetDemo(Request $request)
    {
        $this->ensureAdmin();
        abort_unless(DB::table('parametres')->where('cle', 'mode_installation')->value('valeur') === 'demo', 403);

        $request->validate([
            'confirmation' => ['required', 'in:PASSER EN MODE REEL'],
        ]);

        if (DB::getDriverName() === 'sqlite') {
            DB::statement('PRAGMA foreign_keys = OFF');
        } else {
            DB::statement('SET FOREIGN_KEY_CHECKS=0');
        }

        try {
            foreach ($this->demoResetTables() as $table) {
                if (DB::getSchemaBuilder()->hasTable($table)) {
                    DB::table($table)->truncate();
                }
            }

            DB::table('permissions')
                ->whereIn('utilisateur_id', DB::table('utilisateurs')->where('role', '!=', 'admin')->select('id'))
                ->delete();
            DB::table('utilisateurs')->where('role', '!=', 'admin')->delete();

            DB::table('parametres')->updateOrInsert(['cle' => 'mode_installation'], ['valeur' => 'production']);
        } finally {
            if (DB::getDriverName() === 'sqlite') {
                DB::statement('PRAGMA foreign_keys = ON');
            } else {
                DB::statement('SET FOREIGN_KEY_CHECKS=1');
            }
        }

        return redirect()->route('installation.show')->with('success', 'La demonstration a ete nettoyee. Novaskol est en mode reel.');
    }

    private function isInstalled(): bool
    {
        return File::exists(storage_path('app/novaskol-installed.lock'));
    }

    private function ensureInstallerAccess(): void
    {
        if (! $this->isInstalled()) {
            return;
        }

        $this->ensureAdmin();
    }

    private function ensureAdmin(): void
    {
        abort_unless(session()->has('utilisateur') && session('utilisateur.role') === 'admin', 403);
    }

    private function demoResetTables(): array
    {
        return [
            'bulletins',
            'classe_matieres',
            'classes',
            'conversation_participants',
            'conversations',
            'depenses',
            'dossiers',
            'eleves',
            'emploi_du_temps',
            'enseignants',
            'equipements',
            'evenements',
            'examen_blanc',
            'fichiers',
            'matieres',
            'message_reactions',
            'messages',
            'mpiasa',
            'notes',
            'notifications',
            'paiements',
            'paiements_assignes',
            'parent_eleves',
            'parents',
            'personnes',
            'presence_eleves',
            'presence_personnels',
            'presence_staff',
            'professeurs',
            'professeurs_classes',
            'remarques',
            'remarques_examen_blanc',
            'reservations',
            'reservations_ressources',
            'ressources',
            'retards_personnels',
            'revenus',
            'salaires_assignes',
            'salles',
            'staff',
            'teacher_lessons',
            'teacher_tasks',
            'types_paiements',
            'typing_status',
        ];
    }

    private function hasRealSchoolData(): bool
    {
        return DB::table('eleves')->exists()
            || DB::table('professeurs')->exists()
            || DB::table('staff')->exists()
            || DB::table('notes')->exists()
            || DB::table('revenus')->exists();
    }

    private function seedDemoData(string $year): void
    {
        $classes = [
            ['nom' => '6eme Demo', 'niveau' => 6],
            ['nom' => '3eme Demo', 'niveau' => 3],
            ['nom' => 'Terminale Demo', 'niveau' => 0],
        ];

        $classIds = [];
        foreach ($classes as $class) {
            $classIds[$class['nom']] = DB::table('classes')->insertGetId($class);
        }

        $subjects = [
            ['nom' => 'Malagasy', 'coefficient' => 2],
            ['nom' => 'Francais', 'coefficient' => 2],
            ['nom' => 'Mathematiques', 'coefficient' => 4],
            ['nom' => 'Histoire-Geographie', 'coefficient' => 2],
            ['nom' => 'Sciences', 'coefficient' => 3],
        ];

        $subjectIds = [];
        foreach ($subjects as $subject) {
            $subjectIds[$subject['nom']] = DB::table('matieres')->insertGetId($subject);
        }

        foreach ($classIds as $classId) {
            foreach ($subjectIds as $subjectId) {
                DB::table('classe_matieres')->insert([
                    'id_classe' => $classId,
                    'id_matiere' => $subjectId,
                    'coefficient' => DB::table('matieres')->where('id', $subjectId)->value('coefficient') ?: 1,
                ]);
            }
        }

        $teacherId = DB::table('professeurs')->insertGetId([
            'nom' => 'Rakoto',
            'prenom' => 'Demo',
            'email' => 'enseignant.demo@novaskol.local',
            'telephone' => '0340000001',
            'annee_scolaire' => $year,
            'matiere_id' => $subjectIds['Mathematiques'],
            'salaire_horaire' => 25000,
            'diplome_pedagogique' => 'Licence',
            'autorisation_enseigner' => 'Oui',
            'annees_experience' => 5,
            'statut' => 'actif',
            'photo' => 'images/default-avatar.png',
        ]);

        foreach ($classIds as $classId) {
            DB::table('professeurs_classes')->insert([
                'professeur_id' => $teacherId,
                'classe_id' => $classId,
                'annee_scolaire' => $year,
                'affectation_type' => 'fixe',
                'commentaire' => 'Affectation demo',
            ]);
        }

        DB::table('utilisateurs')->insert([
            'nom' => 'Rakoto Demo',
            'email' => 'enseignant.demo@novaskol.local',
            'mot_de_passe' => Hash::make('demo12345'),
            'role' => 'enseignant',
            'avatar' => 'images/default-avatar.png',
        ]);

        $firstClassId = reset($classIds);
        $students = [
            ['nom' => 'Rabe', 'prenom' => 'Miora', 'genre' => 'F'],
            ['nom' => 'Ando', 'prenom' => 'Tiana', 'genre' => 'G'],
            ['nom' => 'Rasolonirina', 'prenom' => 'Hery', 'genre' => 'G'],
            ['nom' => 'Randria', 'prenom' => 'Nina', 'genre' => 'F'],
        ];

        $studentIds = [];
        foreach ($students as $index => $student) {
            $studentId = DB::table('eleves')->insertGetId([
                'matricule' => 'DEMO'.str_pad((string) ($index + 1), 4, '0', STR_PAD_LEFT),
                'nom' => $student['nom'],
                'prenom' => $student['prenom'],
                'date_naissance' => now()->subYears(12 + $index)->format('Y-m-d'),
                'lieu_naissance' => 'Antananarivo',
                'telephone' => '03400000'.($index + 10),
                'adresse' => 'Adresse demo',
                'id_classe' => $firstClassId,
                'photo' => 'images/default-avatar.png',
                'annee_scolaire' => $year,
                'nom_pere' => 'Pere Demo',
                'nom_mere' => 'Mere Demo',
                'genre' => $student['genre'],
                'statut' => 'nouveau',
            ]);
            $studentIds[] = $studentId;

            foreach ($subjectIds as $subjectId) {
                DB::table('notes')->insert([
                    'id_eleve' => $studentId,
                    'id_matiere' => $subjectId,
                    'periode' => 'T1',
                    'annee_scolaire' => $year,
                    'note' => rand(10, 18),
                    'coefficient' => DB::table('matieres')->where('id', $subjectId)->value('coefficient') ?: 1,
                ]);
            }
        }

        DB::table('types_paiements')->insert([
            'nom' => 'Ecolage demo',
            'montant' => 50000,
            'classe' => 'Toutes',
            'date_debut' => now()->startOfMonth()->format('Y-m-d'),
            'date_fin' => now()->endOfMonth()->format('Y-m-d'),
            'id_classe' => $firstClassId,
            'type_personne' => 'eleve',
            'annee_scolaire' => $year,
        ]);

        // Staff demo
        $staffId = DB::table('staff')->insertGetId([
            'nom' => 'Randria',
            'prenom' => 'Staff',
            'poste' => 'Agent administratif',
            'email' => 'staff.demo@novaskol.local',
            'telephone' => '0340000100',
            'statut' => 'actif',
            'salaire_base' => 200000,
            'annee_scolaire' => $year,
            'photo' => 'images/default-avatar.png',
        ]);

        // Parents demo
        $parentUserId = DB::table('utilisateurs')->insertGetId([
            'nom' => 'Parent Demo',
            'email' => 'parent.demo@novaskol.local',
            'mot_de_passe' => Hash::make('demo12345'),
            'role' => 'parent',
            'avatar' => 'images/default-avatar.png',
        ]);

        DB::table('parents')->insert([
            'nom' => 'Parent',
            'prenom' => 'Demo',
            'lien' => 'Pere',
            'email' => 'parent.demo@novaskol.local',
            'telephone' => '0340000200',
            'profession' => 'Enseignant',
            'adresse' => 'Antananarivo',
        ]);

        if (!empty($studentIds)) {
            DB::table('parent_eleves')->insert([
                'parent_user_id' => $parentUserId,
                'eleve_id' => $studentIds[0],
            ]);
        }

        // Staff user account
        DB::table('utilisateurs')->insert([
            'nom' => 'Randria Staff',
            'email' => 'staff.demo@novaskol.local',
            'mot_de_passe' => Hash::make('demo12345'),
            'role' => 'staff',
            'avatar' => 'images/default-avatar.png',
        ]);

        // EDT (Emploi du temps)
        $dayNames = ['lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi'];
        $timeSlots = [
            ['debut' => '08:00:00', 'fin' => '10:00:00'],
            ['debut' => '10:00:00', 'fin' => '12:00:00'],
            ['debut' => '14:00:00', 'fin' => '16:00:00'],
        ];
        foreach ($classIds as $ci => $cid) {
            $subjKeys = array_values($subjectIds);
            $subjIdx = $ci % count($subjKeys);
            foreach ($dayNames as $di => $day) {
                $slot = $timeSlots[$di % count($timeSlots)];
                DB::table('emploi_du_temps')->insert([
                    'classe_id' => $cid,
                    'professeur_id' => $teacherId,
                    'matiere_id' => $subjKeys[$subjIdx],
                    'jour' => $day,
                    'heure_debut' => $slot['debut'],
                    'heure_fin' => $slot['fin'],
                    'annee_scolaire' => $year,
                ]);
            }
        }

        // Presence records — last 30 days
        $sessionTypes = ['matin', 'apres_midi'];
        $startDate = now()->subDays(30)->format('Y-m-d');
        for ($i = 0; $i < 31; $i++) {
            $date = now()->subDays(30)->addDays($i)->format('Y-m-d');
            $dow = now()->subDays(30)->addDays($i)->dayOfWeek;
            if ($dow === 0 || $dow === 6) continue;
            $month = now()->subDays(30)->addDays($i)->format('m');

            foreach ($studentIds as $sid) {
                foreach ($sessionTypes as $session) {
                    $statut = rand(0, 10) > 2 ? 'present' : 'absent';
                    if ($statut === 'present' && rand(0, 10) > 7) $statut = 'retard';
                    DB::table('presence_eleves')->insert([
                        'eleve_id' => $sid,
                        'classe_id' => $firstClassId,
                        'date_jour' => $date,
                        'session_jour' => $session,
                        'statut' => $statut,
                        'type_scan' => in_array($statut, ['present', 'retard']) ? 'entree' : null,
                        'annee_scolaire' => $year,
                        'mois' => (int) $month,
                    ]);
                }
            }

            // Teacher presence (1 session/day)
            $tStatut = rand(0, 10) > 1 ? 'present' : 'absent';
            if ($tStatut === 'present' && rand(0, 10) > 7) $tStatut = 'retard';
            $tPresent = $tStatut !== 'absent' ? 1 : 0;
            $tRetard = $tStatut === 'retard' ? 1 : 0;
            DB::table('presence_personnels')->insert([
                'personne_id' => $teacherId,
                'staff_id' => $teacherId,
                'date_jour' => $date,
                'statut' => $tStatut,
                'session_jour' => 'matin',
                'presence' => $tPresent,
                'retard' => $tRetard,
                'type_scan' => $tPresent ? 'entree' : null,
                'heure_entree' => $tPresent ? '07:45:00' : null,
                'heure_sortie' => $tPresent ? '12:00:00' : null,
                'horaire' => 4,
                'annee_scolaire' => $year,
                'mois' => (string) $month,
            ]);

            // Staff presence (1 session/day)
            $sStatut = rand(0, 10) > 1 ? 'present' : 'absent';
            if ($sStatut === 'present' && rand(0, 10) > 7) $sStatut = 'retard';
            $sPresent = $sStatut !== 'absent' ? 1 : 0;
            $sRetard = $sStatut === 'retard' ? 1 : 0;
            DB::table('presence_staff')->insert([
                'personne_id' => $staffId,
                'staff_id' => $staffId,
                'date_jour' => $date,
                'statut' => $sStatut,
                'session_jour' => 'matin',
                'presence' => $sPresent,
                'retard' => $sRetard,
                'type_scan' => $sPresent ? 'entree' : null,
                'heure_entree' => $sPresent ? '08:00:00' : null,
                'heure_sortie' => $sPresent ? '17:00:00' : null,
                'jours' => 1,
                'annee_scolaire' => $year,
                'mois' => (string) $month,
            ]);
        }
    }

    private function ensureCoreTables(): void
    {
        if (!Schema::hasTable('ecole')) {
            Schema::create('ecole', function (Blueprint $table) {
                $table->id();
                $table->string('nom', 150)->nullable();
                $table->string('logo', 200)->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('parametres')) {
            Schema::create('parametres', function (Blueprint $table) {
                $table->string('cle', 100)->primary();
                $table->text('valeur')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('utilisateurs')) {
            Schema::create('utilisateurs', function (Blueprint $table) {
                $table->id();
                $table->string('nom', 120);
                $table->string('email', 120);
                $table->string('mot_de_passe', 255);
                $table->string('role', 40)->index();
                $table->string('avatar', 200)->nullable();
                $table->timestamps();
                $table->unique(['email', 'role']);
            });
        }

        if (!Schema::hasTable('permissions')) {
            Schema::create('permissions', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('utilisateur_id')->index();
                $table->string('module', 80)->index();
                $table->string('role', 40)->nullable();
                $table->string('acces', 40)->nullable();
                $table->unique(['utilisateur_id', 'module']);
                $table->timestamps();
            });
        }
    }
}
