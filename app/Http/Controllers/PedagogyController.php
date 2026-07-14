<?php

namespace App\Http\Controllers;

use App\Services\Novaskol\ModuleRegistry;
use App\Services\QrCodeService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;

class PedagogyController extends Controller
{
    private array $months = ['Janvier', 'Fevrier', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Aout', 'Septembre', 'Octobre', 'Novembre', 'Decembre'];

    public function presence(Request $request, ModuleRegistry $modules)
    {
        $this->ensureSession();
        $this->ensureStudentPresenceTable();
        $annee = (string) $request->input('annee_scolaire', $this->currentSchoolYear());
        $monthInput = $request->input('mois', (int) now()->format('n'));
        $month = is_numeric($monthInput) ? (int) $monthInput : max(1, array_search($monthInput, $this->months, true) + 1);
        $classeId = (int) $request->input('classe_id', 0);
        $mode = (string) $request->input('mode', 'fiche');
        $selectedDate = (string) $request->input('date_jour', now()->setDate((int) explode('-', $annee)[0], max(1, min(12, $month)), 1)->toDateString());
        $statusFilter = (string) $request->input('statut', '');
        $days = $this->workingDays($annee, $month);
        $students = $classeId > 0 ? DB::table('eleves')->select('id', 'nom', 'prenom', 'matricule')->where('id_classe', $classeId)->where('annee_scolaire', $annee)->orderBy('nom')->orderBy('prenom')->get() : collect();
        $presenceRows = collect();
        $presenceMap = collect();
        $presenceSummary = ['present' => 0, 'absent' => 0, 'retard' => 0, 'total' => 0];
        if ($classeId > 0) {
            $presenceRows = DB::table('presence_eleves')
                ->where('classe_id', $classeId)
                ->where('annee_scolaire', $annee)
                ->where('mois', $month)
                ->whereDate('date_jour', $selectedDate)
                ->get();
            $presenceMap = $presenceRows->groupBy(fn ($row) => $row->eleve_id.'_'.$row->session_jour);
            $presenceSummary = [
                'present' => (int) $presenceRows->where('statut', 'present')->count(),
                'absent' => (int) $presenceRows->where('statut', 'absent')->count(),
                'retard' => (int) $presenceRows->where('statut', 'retard')->count(),
                'total' => max(1, $students->count() * 2),
            ];
            if (in_array($statusFilter, ['present', 'absent', 'retard'], true)) {
                $matchingIds = $presenceRows->where('statut', $statusFilter)->pluck('eleve_id')->unique()->all();
                $students = $students->whereIn('id', $matchingIds)->values();
            }
        }

        return $this->view('modules.pedagogique.presence.index', $modules, 'fiche_presence', [
            'annees' => $this->schoolYears(),
            'classes' => $this->classes(),
            'selectedAnnee' => $annee,
            'selectedMonth' => $month,
            'selectedClasse' => $classeId,
            'selectedMode' => $mode,
            'selectedDate' => $selectedDate,
            'statusFilter' => $statusFilter,
            'monthLabels' => $this->months,
            'days' => $days,
            'dayGroups' => array_chunk($days, 4),
            'students' => $students,
            'presenceMap' => $presenceMap,
            'presenceSummary' => $presenceSummary,
            'classeNom' => DB::table('classes')->where('id', $classeId)->value('nom') ?: '',
            'generated' => $request->has('generer') || $classeId > 0,
            'todayScans' => $mode === 'scan' && $classeId > 0
                ? DB::table('presence_eleves')
                    ->where('classe_id', $classeId)
                    ->whereDate('date_jour', now()->toDateString())
                    ->orderBy('created_at', 'desc')
                    ->get() : collect(),
        ]);
    }

    public function storeStudentPresence(Request $request)
    {
        $this->ensureSession();
        $this->ensureStudentPresenceTable();

        $data = $request->validate([
            'annee_scolaire' => ['required', 'string', 'max:20'],
            'mois' => ['required', 'integer', 'between:1,12'],
            'classe_id' => ['required', 'integer'],
            'date_jour' => ['required', 'date'],
            'presence' => ['array'],
            'commentaires' => ['array'],
        ]);

        $classeId = (int) $data['classe_id'];
        $annee = (string) $data['annee_scolaire'];
        $month = (int) $data['mois'];
        $date = (string) $data['date_jour'];
        $presence = $data['presence'] ?? [];
        $comments = $data['commentaires'] ?? [];
        $allowed = ['present', 'absent', 'retard'];

        DB::transaction(function () use ($presence, $comments, $classeId, $annee, $month, $date, $allowed) {
            foreach ($presence as $eleveId => $sessions) {
                foreach (['matin', 'apres_midi'] as $session) {
                    $statut = (string) ($sessions[$session] ?? 'present');
                    if (! in_array($statut, $allowed, true)) {
                        $statut = 'present';
                    }

                    DB::table('presence_eleves')->updateOrInsert(
                        ['eleve_id' => (int) $eleveId, 'date_jour' => $date, 'session_jour' => $session, 'type_scan' => 'entree'],
                        [
                            'classe_id' => $classeId,
                            'annee_scolaire' => $annee,
                            'mois' => $month,
                            'statut' => $statut,
                            'commentaire' => trim((string) ($comments[$eleveId] ?? '')) ?: null,
                            'type_scan' => 'entree',
                            'updated_at' => now(),
                            'created_at' => now(),
                        ]
                    );

                    DB::table('presence_eleves')->updateOrInsert(
                        ['eleve_id' => (int) $eleveId, 'date_jour' => $date, 'session_jour' => $session, 'type_scan' => 'sortie'],
                        [
                            'classe_id' => $classeId,
                            'annee_scolaire' => $annee,
                            'mois' => $month,
                            'statut' => $statut,
                            'commentaire' => trim((string) ($comments[$eleveId] ?? '')) ?: null,
                            'type_scan' => 'sortie',
                            'updated_at' => now(),
                            'created_at' => now(),
                        ]
                    );
                }
            }
        });

        return redirect()->route('modules.presence-etudiant', [
            'mode' => 'numerique',
            'generer' => 1,
            'annee_scolaire' => $annee,
            'mois' => $month,
            'classe_id' => $classeId,
            'date_jour' => $date,
        ])->with('pedagogy_msg', ['type' => 'success', 'text' => 'Presence numerique enregistree avec succes.']);
    }

    public function calendar(ModuleRegistry $modules)
    {
        $this->ensureSession();

        return $this->view('modules.pedagogique.calendrier.index', $modules, 'calendrier', [
            'eventTypes' => ['rendez-vous', 'examen', 'session examen', 'reunion', 'vacance', 'evenement scolaire'],
            'canWrite' => $this->canWriteModule('calendrier'),
        ]);
    }

    public function calendarEvents()
    {
        $this->ensureSession();

        return DB::table('evenements')
            ->select('id', 'titre as title', 'date_debut as start', 'date_fin as end', 'type', 'description')
            ->get();
    }

    public function storeCalendarEvent(Request $request)
    {
        $this->ensureSession();
        $this->ensureEventTypeColumnIsFlexible();

        $data = $request->validate([
            'titre' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'type' => ['required', 'string', 'max:100'],
            'date_debut' => ['required', 'date'],
            'date_fin' => ['required', 'date'],
        ]);

        $id = DB::transaction(function () use ($data) {
            $id = DB::table('evenements')->insertGetId([
                'titre' => $data['titre'],
                'description' => $data['description'] ?? '',
                'type' => $this->normalizeEventType($data['type']),
                'date_debut' => \Carbon\Carbon::parse($data['date_debut'])->format('Y-m-d H:i:s'),
                'date_fin' => \Carbon\Carbon::parse($data['date_fin'])->format('Y-m-d H:i:s'),
                'createur_id' => session('utilisateur.id'),
                'annee_scolaire' => $this->currentSchoolYear(),
            ]);

            $this->notify('evenement', "Nouvel evenement : {$data['titre']} du {$data['date_debut']} au {$data['date_fin']}");

            return $id;
        });

        return [
            'success' => true,
            'message' => 'Evenement ajoute avec succes.',
            'event' => [
                'id' => $id,
                'title' => $data['titre'],
                'start' => \Carbon\Carbon::parse($data['date_debut'])->format('Y-m-d H:i:s'),
                'end' => \Carbon\Carbon::parse($data['date_fin'])->format('Y-m-d H:i:s'),
                'type' => $this->normalizeEventType($data['type']),
                'description' => $data['description'] ?? '',
            ],
        ];
    }

    private function normalizeEventType(string $type): string
    {
        return match (strtolower(trim($type))) {
            'réunion' => 'reunion',
            'évènement scolaire', 'événement scolaire' => 'evenement scolaire',
            default => strtolower(trim($type)),
        };
    }

    private function ensureEventTypeColumnIsFlexible(): void
    {
        if (DB::getDriverName() === 'mysql' && Schema::hasTable('evenements')) {
            DB::statement("ALTER TABLE `evenements` MODIFY `type` varchar(100) NOT NULL");
        }
    }

    public function notifications(Request $request, ModuleRegistry $modules)
    {
        $this->ensureSession();
        $type = (string) $request->query('type', '');
        $date = (string) $request->query('date', now()->toDateString());
        $query = DB::table('notifications')->orderByDesc('date_creation')->orderByDesc('id');
        if ($type !== '') {
            $query->where('type', $type);
        }
        if ($date !== '') {
            $query->whereDate('date_creation', $date);
        }

        return $this->view('modules.pedagogique.notifications.index', $modules, 'notifications', [
            'notifications' => $query->limit(300)->get(),
            'types' => DB::table('notifications')->select('type')->where('type', '!=', '')->distinct()->orderBy('type')->pluck('type'),
            'selectedType' => $type,
            'selectedDate' => $date,
        ]);
    }

    public function storeNotification(Request $request)
    {
        $this->ensureSession();
        $data = $request->validate([
            'type' => ['required', 'string', 'max:100'],
            'message' => ['required', 'string'],
            'destinataire_id' => ['nullable', 'integer'],
        ]);

        $this->notify($data['type'], $data['message'], $data['destinataire_id'] ?? null);

        return redirect()->route('modules.notifications')->with('pedagogy_msg', ['type' => 'success', 'text' => 'Notification ajoutee avec succes.']);
    }

    public function deleteNotification(int $id)
    {
        $this->ensureSession();
        DB::table('notifications')->where('id', $id)->delete();

        return ['success' => true];
    }

    public function deleteAllNotifications(Request $request)
    {
        $this->ensureSession();

        $type = (string) $request->query('type', '');
        $date = (string) $request->query('date', '');
        $query = DB::table('notifications');

        if ($type !== '') {
            $query->where('type', $type);
        }
        if ($date !== '') {
            $query->whereDate('date_creation', $date);
        }

        return [
            'success' => true,
            'deleted' => $query->delete(),
        ];
    }

    public function cards(Request $request, ModuleRegistry $modules)
    {
        $this->ensureSession();
        $type = (string) $request->input('type', '');
        $annee = (string) $request->input('annee', $this->currentSchoolYear());
        $classeId = (int) $request->input('classe', 0);
        $people = collect();

        if ($request->isMethod('post') && $type !== '') {
            if ($type === 'etudiant' && $classeId > 0) {
                $people = DB::table('eleves as e')->leftJoin('classes as c', 'e.id_classe', '=', 'c.id')
                    ->select('e.id', 'e.nom', 'e.prenom', 'e.photo', 'e.annee_scolaire', 'e.matricule', 'e.telephone', 'e.date_naissance', 'e.qr_token', 'c.nom as nom_classe')
                    ->where('e.id_classe', $classeId)->where('e.annee_scolaire', $annee)->orderBy('e.nom')->get();
            } elseif ($type === 'enseignant') {
                $people = DB::table('professeurs as p')->leftJoin('matieres as m', 'p.matiere_id', '=', 'm.id')->select('p.id', 'p.nom', 'p.prenom', 'p.photo', 'p.annee_scolaire', 'p.telephone', 'p.qr_token', 'm.nom as nom_matiere')->where('p.annee_scolaire', $annee)->orderBy('p.nom')->get();
            } elseif ($type === 'staff') {
                $people = DB::table('staff as s')->leftJoin('departements as d', 's.departement_id', '=', 'd.id')->select('s.id', 's.nom', 's.prenom', 's.photo', 's.annee_scolaire', 's.telephone', 's.qr_token', 's.poste', 'd.nom as nom_departement')->where('s.annee_scolaire', $annee)->where(function ($q) { $q->whereNull('s.poste')->orWhere('s.poste', '!=', 'Enseignant'); })->orderBy('s.nom')->get();
            }
            $people = $this->ensureQrTokens($people, $type);
        }

        return $this->view('modules.pedagogique.cartes.index', $modules, 'cartes', [
            'annees' => $this->schoolYears(),
            'classes' => $this->classes(),
            'selectedType' => $type,
            'selectedAnnee' => $annee,
            'selectedClasse' => $classeId,
            'people' => $people,
        ]);
    }

    public function cardsConnecte(Request $request)
    {
        $this->ensureSession();
        $type = (string) $request->input('type', '');
        $classeId = (int) $request->input('classe', 0);
        $search = (string) $request->input('search', '');
        $people = collect();

        if ($type !== '') {
            $annee = $this->currentSchoolYear();
            if ($type === 'etudiant') {
                $q = DB::table('eleves as e')->leftJoin('classes as c', 'e.id_classe', '=', 'c.id')
                    ->select('e.id', 'e.nom', 'e.prenom', 'e.photo', 'e.matricule', 'e.telephone', 'e.qr_token', 'c.nom as nom_classe')
                    ->where('e.annee_scolaire', $annee);
                if ($classeId > 0) $q->where('e.id_classe', $classeId);
                if ($search !== '') $q->where(function($q) use ($search) { $q->where('e.nom', 'like', "%{$search}%")->orWhere('e.prenom', 'like', "%{$search}%")->orWhere('e.matricule', 'like', "%{$search}%"); });
                $people = $q->orderBy('e.nom')->get();
            } elseif ($type === 'enseignant') {
                $q = DB::table('professeurs')->select('id', 'nom', 'prenom', 'photo', 'telephone', 'qr_token')->where('annee_scolaire', $annee);
                if ($search !== '') $q->where(function($q) use ($search) { $q->where('nom', 'like', "%{$search}%")->orWhere('prenom', 'like', "%{$search}%"); });
                $people = $q->orderBy('nom')->get();
            } elseif ($type === 'staff') {
                $q = DB::table('staff as s')->leftJoin('departements as d', 's.departement_id', '=', 'd.id')->select('s.id', 's.nom', 's.prenom', 's.photo', 's.telephone', 's.qr_token', 's.poste', 'd.nom as nom_departement')->where('s.annee_scolaire', $annee)->where(function ($q) { $q->whereNull('s.poste')->orWhere('s.poste', '!=', 'Enseignant'); });
                if ($search !== '') $q->where(function($q) use ($search) { $q->where('s.nom', 'like', "%{$search}%")->orWhere('s.prenom', 'like', "%{$search}%"); });
                $people = $q->orderBy('s.nom')->get();
            }
            $people = $this->ensureQrTokens($people, $type);
        }

        return view('modules.pedagogique.cartes.connecte', [
            'classes' => $this->classes(),
            'selectedType' => $type,
            'selectedClasse' => $classeId,
            'people' => $people,
        ]);
    }

    public function documents(Request $request, ModuleRegistry $modules)
    {
        $this->ensureSession();
        $annee = (string) $request->query('annee_scolaire', $this->currentSchoolYear());
        $mois = (string) $request->query('mois', $this->months[(int) now()->format('n') - 1]);
        $search = (string) $request->query('search_nom', '');
        $query = DB::table('dossiers')->where('annee_scolaire', $annee)->where('mois', $mois)->orderByDesc('date_upload');
        if ($search !== '') {
            $query->where('anarana', 'like', "%{$search}%");
        }

        return $this->view('modules.pedagogique.dossiers.index', $modules, 'depot_dossier', [
            'annees' => $this->schoolYears(),
            'months' => $this->months,
            'selectedAnnee' => $annee,
            'selectedMonth' => $mois,
            'search' => $search,
            'dossiers' => $query->get(),
        ]);
    }

    public function storeDocument(Request $request)
    {
        $this->ensureSession();
        $data = $request->validate([
            'classes' => ['required', 'in:eleve,enseignant'],
            'anarana' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'mois' => ['required', 'string', 'max:20'],
            'annee_scolaire' => ['required', 'string', 'max:20'],
            'fichier' => ['required', 'file', 'max:20480'],
        ]);

        $dir = public_path('legacy/Uploads');
        File::ensureDirectoryExists($dir);
        $file = $request->file('fichier');
        $name = $file->getClientOriginalName();
        $file->move($dir, $name);

        DB::transaction(function () use ($data, $name) {
            DB::table('dossiers')->insert([
                'nom' => $data['anarana'],
                'annee_scolaire' => $data['annee_scolaire'],
                'mois' => $data['mois'],
                'type_dossier' => $data['classes'],
                'anarana' => $data['anarana'],
                'description' => $data['description'] ?? '',
                'fichier' => $name,
                'date_upload' => now(),
            ]);
            $this->notify('dossier', "Nouveau dossier depose : {$data['anarana']} ({$data['classes']})");
        });

        return redirect()->route('modules.depot-dossier', ['annee_scolaire' => $data['annee_scolaire'], 'mois' => $data['mois']])
            ->with('pedagogy_msg', ['type' => 'success', 'text' => 'Dossier depose avec succes.']);
    }

    public function deleteDocument(int $id)
    {
        $this->ensureSession();
        $file = DB::table('dossiers')->where('id', $id)->value('fichier');
        if ($file) {
            File::delete(public_path('legacy/Uploads/'.$file));
        }
        DB::table('dossiers')->where('id', $id)->delete();

        return redirect()->route('modules.depot-dossier')->with('pedagogy_msg', ['type' => 'success', 'text' => 'Dossier supprime.']);
    }

    public function fpe(Request $request, ModuleRegistry $modules)
    {
        $this->ensureSession();
        $annee = (string) $request->query('annee', $this->currentSchoolYear());
        $classeId = (int) $request->query('classe', 0);
        $version = (string) $request->query('version', 'simple');
        $fpe = $this->buildFpeData($annee, $classeId);

        return $this->view('modules.pedagogique.fpe.index', $modules, 'fpe', [
            'annees' => $this->schoolYears(),
            'classes' => $fpe['classes'],
            'selectedAnnee' => $annee,
            'selectedClasse' => $classeId,
            'version' => $version,
            'fpe' => $fpe,
            'params' => $this->params(['nom_ecole', 'code_etablissement', 'dren', 'cisco', 'zap']),
        ]);
    }

    public function assurance(Request $request, ModuleRegistry $modules)
    {
        $this->ensureSession();
        $annee = (string) $request->query('annee', $this->currentSchoolYear());
        $classeId = (int) $request->query('classe', 0);
        $query = DB::table('eleves as e')->leftJoin('classes as c', 'e.id_classe', '=', 'c.id')->select('e.*', 'c.nom as classe_nom')->where('e.annee_scolaire', $annee)->orderBy('c.id')->orderBy('e.nom');
        if ($classeId > 0) {
            $query->where('e.id_classe', $classeId);
        }

        $students = $query->get();
        $grouped = $students->groupBy('classe_nom');
        $privateLevels = $this->classCounts($annee);

        return $this->view('modules.pedagogique.assurance.index', $modules, 'liste_assurance', [
            'annees' => $this->schoolYears(),
            'classes' => $this->classes(),
            'selectedAnnee' => $annee,
            'selectedClasse' => $classeId,
            'students' => $students,
            'groupedStudents' => $grouped,
            'privateLevels' => $privateLevels,
            'publicLevels' => array_fill_keys(['PS','MS','GS','CP','CE1','CE2','CM1','CM2','6èm','5èm','4èm','3èm','2nde','1ère','Tle'], 0),
            'totalSomme' => $students->count() * 500,
            'sommeWords' => ucfirst($this->numberToFrenchWords($students->count() * 500)).' '.(DB::table('parametres')->where('cle', 'devise_nom')->value('valeur') ?: 'Ariary'),
            'params' => $this->params(['nom_ecole', 'code_etablissement', 'dren', 'cisco', 'zap', 'tel_etablissement', 'mail_etablissement', 'nb_comment']),
        ]);
    }

    private function view(string $name, ModuleRegistry $modules, string $activeModule, array $data = [])
    {
        return view($name, $data + [
            'modules' => $modules->all(),
            'userPermissions' => $this->userPermissions(),
            'ecole' => $this->school(),
            'activeModule' => $activeModule,
        ]);
    }

    private function notify(string $type, string $message, ?int $destinataireId = null): void
    {
        DB::table('notifications')->insert([
            'type' => $type,
            'message' => $message,
            'destinataire_id' => $destinataireId,
            'date_creation' => now(),
            'statut' => 'non lu',
            'date_envoi' => now(),
            'lu' => 0,
            'user_type' => session('utilisateur.role', 'admin'),
            'user_id' => session('utilisateur.id', 0),
            'titre' => $type,
        ]);
    }

    private function ensureStudentPresenceTable(): void
    {
        if (Schema::hasTable('presence_eleves')) {
            return;
        }

        Schema::create('presence_eleves', function ($table) {
            $table->id();
            $table->unsignedBigInteger('eleve_id');
            $table->unsignedBigInteger('classe_id');
            $table->string('annee_scolaire', 20);
            $table->unsignedTinyInteger('mois');
            $table->date('date_jour');
            $table->enum('session_jour', ['matin', 'apres_midi']);
            $table->enum('statut', ['present', 'absent', 'retard'])->default('present');
            $table->string('commentaire', 255)->nullable();
            $table->timestamps();
            $table->unique(['eleve_id', 'date_jour', 'session_jour'], 'presence_eleves_unique_day_session');
            $table->index(['classe_id', 'annee_scolaire', 'mois']);
            $table->index(['date_jour', 'statut']);
        });
    }

    private function workingDays(string $schoolYear, int $month): array
    {
        $year = (int) explode('-', $schoolYear)[0];
        $date = now()->setDate($year, max(1, min(12, $month)), 1)->startOfDay();
        $end = $date->copy()->endOfMonth();
        $days = [];
        while ($date <= $end) {
            if ((int) $date->format('N') <= 5) {
                $days[] = $date->toDateString();
            }
            $date->addDay();
        }
        return $days;
    }

    private function classes()
    {
        return DB::table('classes')->select('id', 'nom', 'niveau')->orderBy('nom')->get();
    }

    private function schoolYears()
    {
        return DB::table('eleves')->select('annee_scolaire')->whereNotNull('annee_scolaire')->where('annee_scolaire', '!=', '')->distinct()->orderByDesc('annee_scolaire')->pluck('annee_scolaire');
    }

    private function currentSchoolYear(): string
    {
        return (string) ($this->schoolYears()->first() ?: now()->format('Y').'-'.(now()->year + 1));
    }

    private function params(array $keys): array
    {
        return DB::table('parametres')->whereIn('cle', $keys)->pluck('valeur', 'cle')->all();
    }

    private function buildFpeData(string $annee, int $classeId = 0): array
    {
        $classes = DB::table('classes')->select('id', 'nom', 'niveau')->get()->map(function ($classe) {
            $classe->niveau_sort = $this->classSortLevel($classe->nom, $classe->niveau);
            return $classe;
        })->sortBy('niveau_sort')->values();

        if ($classeId > 0) {
            $classes = $classes->filter(fn ($classe) => (int) $classe->id === $classeId)->values();
        }

        $preschoolNames = ['PS', 'MS', 'GS'];
        $primaryNames = ['CP', 'CE1', 'CE2', 'CM1', 'CM2'];
        $collegeNames = ['6E', '5E', '4E', '3E'];

        $preschoolClasses = $classes->filter(fn ($c) => in_array($this->normalizeClassName($c->nom), $preschoolNames, true))->values();
        $primaryClasses = $classes->filter(fn ($c) => in_array($this->normalizeClassName($c->nom), $primaryNames, true))->values();
        $collegeClasses = $classes->filter(fn ($c) => in_array($this->normalizeClassName($c->nom), $collegeNames, true))->values();
        $lyceeClasses = $classes->reject(fn ($c) => in_array($this->normalizeClassName($c->nom), array_merge($preschoolNames, $primaryNames, $collegeNames), true))->values();

        $categoriesF4 = [
            'effectif' => 'EFFECTIF',
            'passants' => 'DONT PASSANTS',
            'redoublants' => 'DONT REDOUBLANTS',
            'nouveaux' => 'DONT NOUVEAUX',
            'distance' => "Nombre d'eleves habitant a plus de 5 km",
        ];
        $categoriesPrimary = [
            'effectif' => 'EFFECTIF',
            'passants' => 'DONT PASSANTS',
            'redoublants' => 'DONT REDOUBLANTS',
            'transferts' => 'DONT TRANSFERTS',
            'cran' => 'DONT ISSUS DU CRAN',
            'alphabetisation' => "DONT ISSUS DE L'ALPHABETISATION",
            'distance' => "Nombre d'eleves habitant a plus de 2 km",
        ];
        $primaryAges = ['Moins de 3 ans', '3 ans', '4 ans', '5 ans', '6 ans ou plus', 'Moins de 6 ans', '6 ans', '7 ans', '8 ans', '9 ans', '10 ans', '11 ans ou plus'];
        $preschoolSpecific = ['Moins de 3 ans', '3 ans', '4 ans', '5 ans', '6 ans ou plus'];
        $primarySpecific = ['Moins de 6 ans', '6 ans', '7 ans', '8 ans', '9 ans', '10 ans', '11 ans ou plus'];
        $redoublantsAges = $primarySpecific;
        $agesSecondary = array_merge(['Moins de 11 ans'], range(11, 18), ['Plus de 18 ans']);

        $primaryAll = $preschoolClasses->merge($primaryClasses)->values();
        $secondaryAll = $collegeClasses->merge($lyceeClasses)->values();

        $dataPrimary = [];
        $totalsPrimary = [];
        foreach ($categoriesPrimary as $key => $label) {
            $dataPrimary[$key] = [];
            $totalsPrimary[$key] = ['G' => 0, 'F' => 0];
            foreach ($primaryAll as $classe) {
                $counts = $this->genderCounts($classe->id, $annee, $this->conditionForCategory($key, true));
                $dataPrimary[$key][$classe->nom] = $counts;
                $totalsPrimary[$key]['G'] += $counts['G'];
                $totalsPrimary[$key]['F'] += $counts['F'];
            }
        }

        $dataPrimaryAge = [];
        $totalsPrimaryAge = $this->emptyGenderRows($primaryAges);
        foreach ($primaryAll as $classe) {
            $rows = $this->ageGenderRows($classe->id, $annee);
            $classData = $this->emptyGenderRows($primaryAges);
            $isPreschool = $preschoolClasses->contains('id', $classe->id);
            foreach ($rows as $row) {
                $ageKey = $this->primaryAgeKey((int) $row->age, $isPreschool);
                if ($ageKey) {
                    $genre = $row->genre === 'F' ? 'F' : 'G';
                    $classData[$ageKey][$genre]++;
                    $totalsPrimaryAge[$ageKey][$genre]++;
                }
            }
            $dataPrimaryAge[$classe->nom] = $classData;
        }

        $dataRedoublants = [];
        $totalsRedoublants = [];
        foreach (['CP', 'CM2'] as $className) {
            $classe = $primaryClasses->first(fn ($c) => strtoupper($c->nom) === $className);
            $dataRedoublants[$className] = $this->emptyGenderRows($redoublantsAges);
            $totalsRedoublants[$className] = ['G' => 0, 'F' => 0];
            if ($classe) {
                foreach ($this->ageGenderRows($classe->id, $annee, "statut = 'redoublant'") as $row) {
                    $ageKey = $this->redoublantAgeKey((int) $row->age);
                    if ($ageKey) {
                        $genre = $row->genre === 'F' ? 'F' : 'G';
                        $dataRedoublants[$className][$ageKey][$genre]++;
                        $totalsRedoublants[$className][$genre]++;
                    }
                }
            }
        }

        $dataF4 = [];
        $totalsF4 = [];
        $subtotalsCollegeF4 = [];
        $subtotalsLyceeF4 = [];
        foreach ($categoriesF4 as $key => $label) {
            $dataF4[$key] = [];
            $totalsF4[$key] = ['G' => 0, 'F' => 0, 'T' => 0];
            $subtotalsCollegeF4[$key] = ['G' => 0, 'F' => 0, 'T' => 0];
            $subtotalsLyceeF4[$key] = ['G' => 0, 'F' => 0, 'T' => 0];
            foreach ($secondaryAll as $classe) {
                $counts = $this->genderCountsWithTotal($classe->id, $annee, $this->conditionForCategory($key, false));
                $dataF4[$key][$classe->nom] = $counts;
                foreach (['G', 'F', 'T'] as $col) {
                    $totalsF4[$key][$col] += $counts[$col];
                    if ($collegeClasses->contains('id', $classe->id)) {
                        $subtotalsCollegeF4[$key][$col] += $counts[$col];
                    } else {
                        $subtotalsLyceeF4[$key][$col] += $counts[$col];
                    }
                }
            }
        }

        $dataF5 = [];
        $totalsF5 = $this->emptyGenderRows($agesSecondary);
        $subtotalsCollegeF5 = $this->emptyGenderRows($agesSecondary);
        $subtotalsLyceeF5 = $this->emptyGenderRows($agesSecondary);
        foreach ($secondaryAll as $classe) {
            $classData = $this->emptyGenderRows($agesSecondary);
            foreach ($this->ageGenderRows($classe->id, $annee) as $row) {
                $ageKey = $this->secondaryAgeKey((int) $row->age);
                $genre = $row->genre === 'F' ? 'F' : 'G';
                $classData[$ageKey][$genre]++;
                $totalsF5[$ageKey][$genre]++;
                if ($collegeClasses->contains('id', $classe->id)) {
                    $subtotalsCollegeF5[$ageKey][$genre]++;
                } else {
                    $subtotalsLyceeF5[$ageKey][$genre]++;
                }
            }
            $dataF5[$classe->nom] = $classData;
        }

        return compact(
            'classes',
            'preschoolClasses',
            'primaryClasses',
            'collegeClasses',
            'lyceeClasses',
            'categoriesF4',
            'categoriesPrimary',
            'primaryAges',
            'preschoolSpecific',
            'primarySpecific',
            'redoublantsAges',
            'agesSecondary',
            'dataPrimary',
            'totalsPrimary',
            'dataPrimaryAge',
            'totalsPrimaryAge',
            'dataRedoublants',
            'totalsRedoublants',
            'dataF4',
            'totalsF4',
            'subtotalsCollegeF4',
            'subtotalsLyceeF4',
            'dataF5',
            'totalsF5',
            'subtotalsCollegeF5',
            'subtotalsLyceeF5'
        );
    }

    private function conditionForCategory(string $key, bool $primary): string
    {
        return match ($key) {
            'passants' => "statut = 'passant'",
            'redoublants' => "statut = 'redoublant'",
            'nouveaux' => "statut = 'nouveau'",
            'transferts' => "statut = 'transfert'",
            'cran' => "statut = 'cran'",
            'alphabetisation' => "statut = 'alphabetisation'",
            'distance' => 'distance_domicile = 1',
            default => '',
        };
    }

    private function genderCounts(int $classeId, string $annee, string $condition = ''): array
    {
        $query = DB::table('eleves')->select('genre')->where('id_classe', $classeId)->where('annee_scolaire', $annee);
        if ($condition !== '') {
            $query->whereRaw($condition);
        }
        $counts = ['G' => 0, 'F' => 0];
        foreach ($query->get() as $row) {
            $counts[$row->genre === 'F' ? 'F' : 'G']++;
        }
        return $counts;
    }

    private function genderCountsWithTotal(int $classeId, string $annee, string $condition = ''): array
    {
        $counts = $this->genderCounts($classeId, $annee, $condition);
        $counts['T'] = $counts['G'] + $counts['F'];
        return $counts;
    }

    private function ageGenderRows(int $classeId, string $annee, string $condition = '')
    {
        $driver = DB::getDriverName();
        $ageExpr = $driver === 'sqlite'
            ? "CAST((julianday('now') - julianday(date_naissance)) / 365 AS INTEGER)"
            : 'FLOOR(DATEDIFF(CURDATE(), date_naissance)/365)';
        $query = DB::table('eleves')
            ->select('genre', DB::raw("{$ageExpr} AS age"))
            ->where('id_classe', $classeId)
            ->where('annee_scolaire', $annee)
            ->whereNotNull('date_naissance');
        if ($condition !== '') {
            $query->whereRaw($condition);
        }
        return $query->get();
    }

    private function emptyGenderRows(array $keys): array
    {
        $rows = [];
        foreach ($keys as $key) {
            $rows[$key] = ['G' => 0, 'F' => 0];
        }
        return $rows;
    }

    private function primaryAgeKey(int $age, bool $isPreschool): ?string
    {
        if ($isPreschool) {
            if ($age < 3) return 'Moins de 3 ans';
            if ($age === 3) return '3 ans';
            if ($age === 4) return '4 ans';
            if ($age === 5) return '5 ans';
            return '6 ans ou plus';
        }
        if ($age < 6) return 'Moins de 6 ans';
        if ($age >= 11) return '11 ans ou plus';
        return $age.' ans';
    }

    private function redoublantAgeKey(int $age): string
    {
        if ($age < 6) return 'Moins de 6 ans';
        if ($age >= 11) return '11 ans ou plus';
        return $age.' ans';
    }

    private function secondaryAgeKey(int $age): string|int
    {
        if ($age < 11) return 'Moins de 11 ans';
        if ($age > 18) return 'Plus de 18 ans';
        return $age;
    }

    private function classSortLevel(string $name, mixed $niveau): int
    {
        if ($niveau !== null) {
            return (int) $niveau;
        }
        return match ($this->normalizeClassName($name)) {
            'PS' => 1, 'MS' => 2, 'GS' => 3, 'CP' => 4, 'CE1' => 5, 'CE2' => 6, 'CM1' => 7, 'CM2' => 8,
            '6E' => 9, '5E' => 10, '4E' => 11, '3E' => 12, '2NDE' => 13, '1ERE' => 14,
            'TA', 'TL', 'TD', 'TC', 'TS', 'TOSE', 'TLE', 'T' => 15,
            default => 16,
        };
    }

    private function normalizeClassName(string $name): string
    {
        return strtoupper(str_replace(['È', 'É', 'Ê', 'è', 'é', 'ê', '1ÈRE', '1ÈRE'], ['E', 'E', 'E', 'E', 'E', 'E', '1ERE', '1ERE'], trim($name)));
    }

    private function classCounts(string $annee): array
    {
        return DB::table('classes as c')
            ->leftJoin('eleves as e', function ($join) use ($annee) {
                $join->on('e.id_classe', '=', 'c.id')->where('e.annee_scolaire', '=', $annee);
            })
            ->select('c.nom', DB::raw('COUNT(e.id) as total'))
            ->groupBy('c.nom')
            ->get()
            ->pluck('total', 'nom')
            ->map(fn ($value) => (int) $value)
            ->all();
    }

    private function numberToFrenchWords(int $number): string
    {
        $units = ['', 'un', 'deux', 'trois', 'quatre', 'cinq', 'six', 'sept', 'huit', 'neuf', 'dix', 'onze', 'douze', 'treize', 'quatorze', 'quinze', 'seize'];
        $tens = ['', '', 'vingt', 'trente', 'quarante', 'cinquante', 'soixante'];
        if ($number === 0) {
            return 'zero';
        }
        if ($number < 17) {
            return $units[$number];
        }
        if ($number < 20) {
            return 'dix-'.$units[$number - 10];
        }
        if ($number < 70) {
            $ten = intdiv($number, 10);
            $unit = $number % 10;
            return $tens[$ten].($unit ? '-'.$units[$unit] : '');
        }
        if ($number < 100) {
            return $this->numberToFrenchWords($number - 60) === 'dix' ? 'soixante-dix' : 'soixante-'.$this->numberToFrenchWords($number - 60);
        }
        if ($number < 1000) {
            $hundred = intdiv($number, 100);
            $rest = $number % 100;
            return ($hundred > 1 ? $units[$hundred].' ' : '').'cent'.($rest ? ' '.$this->numberToFrenchWords($rest) : '');
        }
        if ($number < 1000000) {
            $thousand = intdiv($number, 1000);
            $rest = $number % 1000;
            return ($thousand > 1 ? $this->numberToFrenchWords($thousand).' ' : '').'mille'.($rest ? ' '.$this->numberToFrenchWords($rest) : '');
        }
        $million = intdiv($number, 1000000);
        $rest = $number % 1000000;
        return $this->numberToFrenchWords($million).' million'.($rest ? ' '.$this->numberToFrenchWords($rest) : '');
    }

    private function userPermissions(): array
    {
        if (! session('utilisateur.id')) {
            return [];
        }
        return DB::table('permissions')->where('utilisateur_id', session('utilisateur.id'))->pluck('acces', 'module')->all();
    }

    private function canWriteModule(string $module): bool
    {
        if (session('utilisateur.role') === 'admin') {
            return true;
        }

        return ($this->userPermissions()[$module] ?? null) === 'ecriture';
    }

    public function qrCodeImage(string $token)
    {
        $qr = app(QrCodeService::class);
        $resolved = $qr->resolveToken($token);
        if (!$resolved) {
            abort(404, 'QR code invalide');
        }

        $payload = json_encode($qr->payloadForToken($token), JSON_THROW_ON_ERROR);
        $asset = asset('legacy/vendor/qrcode.min.js');

        return response(<<<HTML
<!DOCTYPE html>
<html lang="fr">
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>QR Novaskol</title><script src="{$asset}"></script></head>
<body style="margin:0;min-height:100vh;display:grid;place-items:center;background:#fff">
<div id="qr" style="width:400px;height:400px;padding:12px;background:#fff"></div>
<script>new QRCode(document.getElementById('qr'),{text:{$payload},width:376,height:376,colorDark:'#000000',colorLight:'#ffffff',correctLevel:QRCode.CorrectLevel.M});</script>
</body>
</html>
HTML, 200)->header('Content-Type', 'text/html; charset=UTF-8');
    }

    public function qrPresenceScan(Request $request, string $token)
    {
        $resolved = app(QrCodeService::class)->resolveToken($token);
        if (!$resolved) {
            return response()->json(['success' => false, 'message' => 'QR code invalide'], 404);
        }

        $scannedBy = session('utilisateur.id');
        $now = now();
        $localNow = $this->localNow();
        $type = $resolved['type'];
        $data = $resolved['data'];
        $today = $localNow->toDateString();
        $heure = $localNow->format('H:i');
        $session = $this->determineCurrentSession();
        $mois = $localNow->format('m');

        $scanTimeFormatted = $localNow->format('H:i');
        $person = [
            'nom' => $data->nom ?? '',
            'prenom' => $data->prenom ?? '',
            'type' => $type,
            'id' => $data->id ?? '',
            'matricule' => $data->matricule ?? $data->id ?? '',
            'photo' => $data->photo ?? '',
            'scan_time' => $scanTimeFormatted,
            'scan_date' => $today,
            'annee_scolaire' => $this->currentSchoolYear(),
            'mois' => $mois,
        ];

        if ($type === 'eleve') {
            $this->ensureStudentPresenceTable();
            $student = DB::table('eleves')->where('id', $data->id)->first(['id', 'id_classe', 'annee_scolaire']);
            $person['role'] = 'Eleve';
            if (!$student) {
                return response()->json(['success' => false, 'message' => 'Eleve introuvable'], 404);
            }

            $classeId = (int) $student->id_classe;
            $anneeScolaire = $student->annee_scolaire ?: $this->currentSchoolYear();

            // Determine which of 4 scan types from EDT & existing state
            $scanInfo = $this->determineStudentScanType($classeId, $heure, (int) $data->id, $today);
            $effectiveSession = $scanInfo['session'];
            $effectiveTypeScan = $scanInfo['type_scan'];
            $edtSlot = $scanInfo['slot'];
            $retard = $scanInfo['retard'];

            if ($edtSlot) {
                $person['matiere'] = $edtSlot->matiere_nom;
            }

            // If sortie, check there's an existing entree to close
            if ($effectiveTypeScan === 'sortie') {
                $entreeRecord = DB::table('presence_eleves')
                    ->where('eleve_id', $data->id)
                    ->where('date_jour', $today)
                    ->where('session_jour', $effectiveSession)
                    ->where('type_scan', 'entree')
                    ->first();

                if (!$entreeRecord) {
                    // No entree to close → force entree
                    $effectiveTypeScan = 'entree';
                    $scanInfo['type_scan'] = 'entree';
                }
            }

            // Check duplicate scan
            $existing = DB::table('presence_eleves')
                ->where('eleve_id', $data->id)
                ->where('date_jour', $today)
                ->where('session_jour', $effectiveSession)
                ->where('type_scan', $effectiveTypeScan)
                ->first();

            if ($existing) {
                $person['scan_type'] = $effectiveTypeScan;
                $person['session'] = $effectiveSession;
                $person['retard'] = (bool) $retard;
                $person['edt'] = $this->getClassEdtForToday($classeId);
                return response()->json([
                    'success' => true,
                    'message' => 'Deja scanne (' . $effectiveTypeScan . ' ' . $effectiveSession . ')',
                    'person' => $person,
                ]);
            }

            $statut = $retard ? 'retard' : $this->determinePresenceStatut($heure, $edtSlot->heure_debut ?? null);

            DB::table('presence_eleves')->insert([
                'eleve_id' => $data->id,
                'classe_id' => $classeId,
                'annee_scolaire' => $anneeScolaire,
                'mois' => $mois,
                'date_jour' => $today,
                'session_jour' => $effectiveSession,
                'type_scan' => $effectiveTypeScan,
                'statut' => $statut,
                'scan_mode' => 'qr_code',
                'scanned_by' => $scannedBy,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            $person['scan_type'] = $effectiveTypeScan;
            $person['session'] = $effectiveSession;
            $person['retard'] = (bool) $retard;
            $person['edt'] = $this->getClassEdtForToday($classeId);

            $label = $effectiveTypeScan === 'entree' ? 'Entree' : 'Sortie';

            return response()->json([
                'success' => true,
                'message' => ($retard ? 'Retard' : $label)
                    . ($edtSlot ? ' - ' . $edtSlot->matiere_nom : '') . ' pour ' . ($data->prenom ?? ''),
                'person' => $person,
            ]);
        }

        if ($type === 'enseignant') {
            $profId = $this->resolveProfesseurId($resolved, $data);
            $teacherData = $profId ? DB::table('professeurs')->where('id', $profId)->first() : null;

            if ($teacherData) {
                $person['nom'] = $teacherData->nom ?? $person['nom'];
                $person['prenom'] = $teacherData->prenom ?? $person['prenom'];
                $person['photo'] = $teacherData->photo ?? $person['photo'];
                $person['matricule'] = 'ENS-' . $profId;
                $person['salaire_horaire'] = $teacherData->salaire_horaire ?? 0;
            }
            $person['role'] = 'Enseignant';

            $edtSlot = null;
            $dayFr = $this->dayNameFrench();
            if ($profId && $dayFr) {
                // Build candidate list: (classe_id, matiere_id) pairs from professeurs_classes
                $pairs = DB::table('professeurs_classes')
                    ->where('professeur_id', $profId)
                    ->whereNotNull('classe_id')
                    ->select('classe_id', 'matiere_id')
                    ->get();

                $allMatieres = [];
                foreach ($pairs as $p) {
                    $mid = $p->matiere_id ? (int) $p->matiere_id : 0;
                    if ($mid) $allMatieres[] = $mid;
                }
                if ($teacherData && !empty($teacherData->matiere_id)) {
                    $tid = (int) $teacherData->matiere_id;
                    $allMatieres[] = $tid;
                }
                $allMatieres = array_unique($allMatieres);

                // Time tolerance: scan up to 30 min BEFORE slot start
                $timeStart = date('H:i', strtotime('2000-01-01 ' . $heure) - 1800);

                // Time helper: slot ending after scan AND (starting within 30 min OR currently active)
                $slotQuery = function($q, $dayFr, $heure, $timeStart) {
                    return $q->where('e.jour', $dayFr)
                        ->where('e.heure_fin', '>', $heure)
                        ->where(function($sq) use ($timeStart, $heure) {
                            $sq->where('e.heure_debut', '>=', $timeStart)
                               ->orWhere(function($sq2) use ($heure) {
                                   $sq2->where('e.heure_debut', '<=', $heure)
                                       ->where('e.heure_fin', '>', $heure);
                               });
                        })
                        ->orderBy('e.heure_debut');
                };

                // 1) Direct EDT via professeur_id
                $base1 = DB::table('emploi_du_temps as e')
                    ->leftJoin('classes as c', 'c.id', '=', 'e.classe_id')
                    ->leftJoin('matieres as m', 'm.id', '=', 'e.matiere_id')
                    ->where('e.professeur_id', $profId);
                $edtSlot = $slotQuery($base1, $dayFr, $heure, $timeStart)
                    ->first(['e.*', 'c.nom as classe_nom', 'm.nom as matiere_nom']);

                // 2) By teacher's subject matiere_id (from professeurs + professeurs_classes)
                if (!$edtSlot && $allMatieres) {
                    $base2 = DB::table('emploi_du_temps as e')
                        ->leftJoin('classes as c', 'c.id', '=', 'e.classe_id')
                        ->leftJoin('matieres as m', 'm.id', '=', 'e.matiere_id')
                        ->whereIn('e.matiere_id', $allMatieres);
                    $edtSlot = $slotQuery($base2, $dayFr, $heure, $timeStart)
                        ->first(['e.*', 'c.nom as classe_nom', 'm.nom as matiere_nom']);
                }
            }

            if ($edtSlot) {
                // EDT found → new entree for this slot (each slot gets its own record)
                $this->ensureStaffRecord($profId, $teacherData);
                $hDebut = strtotime('2000-01-01 ' . $edtSlot->heure_debut);
                $hFin = strtotime('2000-01-01 ' . $edtSlot->heure_fin);
                $hMaintenant = strtotime('2000-01-01 ' . $heure);
                $horaire = round(($hFin - $hDebut) / 3600, 1);
                $retard = ($hMaintenant - $hDebut) > 900 ? 1 : 0;

                $this->ensurePresencePersonnelsSession();

                $recordId = DB::table('presence_personnels')->insertGetId([
                    'personne_id' => $profId,
                    'staff_id' => $profId,
                    'date_jour' => $today,
                    'session_jour' => $session,
                    'type_scan' => 'entree',
                    'heure_entree' => $heure,
                    'statut' => $retard ? 'retard' : 'present',
                    'horaire' => $horaire,
                    'presence' => 1,
                    'retard' => $retard,
                    'annee_scolaire' => $this->currentSchoolYear(),
                    'mois' => (string) $mois,
                    'date_enregistrement' => $now,
                    'scan_mode' => 'qr_code',
                    'scanned_by' => $scannedBy,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);

                $person['record_id'] = $recordId;
                $person['classe'] = $edtSlot->classe_nom;
                $person['matiere'] = $edtSlot->matiere_nom;
                $person['horaire'] = $horaire;
                $person['retard'] = (bool) $retard;
                $person['edt'] = $this->getTeacherEdtForToday($profId);
                $person['scan_type'] = 'entree';
                $person['session'] = $session;
                $person['heure_entree'] = $heure;
                $person['heure_sortie'] = null;

                $retardMin = $retard ? round(($hMaintenant - $hDebut) / 60) : 0;
                $suffix = $retard ? ' (+' . $retardMin . 'min)' : '';
                $msg = 'Entree ' . $heure . ' - ' . $edtSlot->matiere_nom . ' ' . $edtSlot->classe_nom . ' [' . $edtSlot->heure_debut . '-' . $edtSlot->heure_fin . ']' . $suffix;
                $this->notify('presence', ($teacherData->prenom ?? '') . ' ' . ($teacherData->nom ?? '') . ' (Enseignant) : ' . $msg);
                return response()->json(['success' => true, 'message' => $msg, 'person' => $person]);
            }

            // No EDT at current time → find latest open entree and mark sortie
            if ($profId) $this->ensureStaffRecord($profId, $teacherData);
            $this->ensurePresencePersonnelsSession();
            $latestEntree = DB::table('presence_personnels')
                ->where('personne_id', $profId ?: 0)
                ->where('date_jour', $today)
                ->where('presence', 1)
                ->whereNull('heure_sortie')
                ->orderByDesc('id')
                ->first();

            if ($latestEntree) {
                DB::table('presence_personnels')
                    ->where('id', $latestEntree->id)
                    ->update([
                        'type_scan' => 'sortie',
                        'heure_sortie' => $heure,
                        'updated_at' => $now,
                    ]);
                $person['scan_type'] = 'sortie';
                $person['session'] = $session;
                $person['record_id'] = $latestEntree->id;
                $person['horaire'] = $latestEntree->horaire ?? 0;
                $person['classe'] = $latestEntree->classe_nom ?? '';
                $person['matiere'] = $latestEntree->matiere_nom ?? '';
                $person['heure_entree'] = $latestEntree->heure_entree ?? substr($latestEntree->created_at ?? '', 11, 5);
                $person['heure_sortie'] = $heure;
                $this->notify('presence', ($teacherData->prenom ?? '') . ' ' . ($teacherData->nom ?? '') . ' (Enseignant) : Sortie ' . $heure . ' (entree ' . ($latestEntree->heure_entree ?? '?') . ')');
                return response()->json(['success' => true, 'message' => 'Sortie - journee terminee', 'person' => $person]);
            }

            // No open entree and no EDT → ignore (can't record without context)
            return response()->json(['success' => false, 'message' => 'Aucun EDT trouve pour cette heure', 'person' => $person]);
        }

        if ($type === 'staff') {
            $presenceTable = 'presence_staff';
            if (!Schema::hasTable($presenceTable)) {
                return response()->json(['success' => false, 'message' => 'Table de presence non trouvee'], 500);
            }

            if (!Schema::hasColumn($presenceTable, 'session_jour')) {
                Schema::table($presenceTable, function ($table) {
                    $table->string('session_jour', 20)->nullable()->after('date_jour');
                });
            }

            // Include staff role in response
            $staffData = DB::table('staff')->where('id', (int) $data->id)->first(['role_id']);
            if ($staffData && $staffData->role_id) {
                $person['role'] = DB::table('roles')->where('id', $staffData->role_id)->value('nom') ?: '';
            } else {
                $person['role'] = '';
            }

            // Staff: UNIQUE record per day. First scan = entree, second scan = sortie (update)
            $existingRecord = DB::table($presenceTable)
                ->where('staff_id', (int) $data->id)
                ->where('date_jour', $today)
                ->first();

            if ($existingRecord) {
                // Second scan → update as sortie (keep jours=1, they're paid by day)
                DB::table($presenceTable)
                    ->where('id', $existingRecord->id)
                    ->update([
                        'type_scan' => 'sortie',
                        'heure_sortie' => $heure,
                        'statut' => 'present',
                        'updated_at' => $now,
                    ]);
                $person['record_id'] = $existingRecord->id;
                $person['scan_type'] = 'sortie';
                $person['session'] = 'matin';
                $person['retard'] = false;
                $person['heure_entree'] = $existingRecord->heure_entree ?? substr($existingRecord->created_at ?? '', 11, 5);
                $person['heure_sortie'] = $heure;

                $this->notify('presence', ($data->prenom ?? '') . ' ' . ($data->nom ?? '') . ' (' . ($person['role'] ?: 'Staff') . ') : Sortie ' . $heure . ' (entree ' . ($existingRecord->heure_entree ?? '?') . ')');
                return response()->json([
                    'success' => true,
                    'message' => 'Sortie enregistree - journee complete pour ' . ($data->prenom ?? ''),
                    'person' => $person,
                ]);
            }

            // First scan → Entree (1 jour counted immediately for daily pay)
            $staffRetard = $this->computeStaffRetard($heure);
            $recordId = DB::table($presenceTable)->insertGetId([
                'staff_id' => (int) $data->id,
                'personne_id' => (int) $data->id,
                'date_jour' => $today,
                'session_jour' => 'matin',
                'type_scan' => 'entree',
                'heure_entree' => $heure,
                'statut' => $staffRetard ? 'retard' : 'present',
                'presence' => 1,
                'retard' => $staffRetard,
                'jours' => 1,
                'annee_scolaire' => $this->currentSchoolYear(),
                'mois' => (string) $mois,
                'date_enregistrement' => $now,
                'scan_mode' => 'qr_code',
                'scanned_by' => $scannedBy,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
            $person['record_id'] = $recordId;
            $person['scan_type'] = 'entree';
            $person['session'] = 'matin';
            $person['retard'] = false;
            $person['heure_entree'] = $heure;
            $person['jours'] = 1;

            $retardLabel = $staffRetard ? ' (retard)' : '';
            $this->notify('presence', ($data->prenom ?? '') . ' ' . ($data->nom ?? '') . ' (' . ($person['role'] ?: 'Staff') . ') : Entree ' . $heure . $retardLabel);
            return response()->json([
                'success' => true,
                'message' => 'Entree enregistree pour ' . ($data->prenom ?? ''),
                'person' => $person,
            ]);
        }

        return response()->json(['success' => false, 'message' => 'Type non reconnu'], 400);
    }

    private function ensureStaffRecord(int $profId, ?object $teacherData): void
    {
        if (!DB::table('staff')->where('id', $profId)->exists() && $teacherData) {
            DB::table('staff')->insert([
                'id' => $profId,
                'nom' => $teacherData->nom ?? '',
                'prenom' => $teacherData->prenom ?? '',
                'poste' => 'Enseignant',
                'email' => $teacherData->email ?? '',
                'telephone' => $teacherData->telephone ?? '',
                'salaire_base' => $teacherData->salaire_horaire ?? 0,
                'annee_scolaire' => $teacherData->annee_scolaire ?? $this->currentSchoolYear(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    private function ensurePresencePersonnelsSession(): void
    {
        if (!Schema::hasColumn('presence_personnels', 'session_jour') && Schema::hasTable('presence_personnels')) {
            Schema::table('presence_personnels', function ($table) {
                $table->enum('session_jour', ['matin', 'apres_midi'])->nullable()->after('date_jour');
            });
        }
    }

    private function resolveProfesseurId(array $resolved, object $data): ?int
    {
        if ($resolved['table'] === 'professeurs') return (int) $data->id;

        $email = trim((string) ($data->email ?? ''));
        if ($email) {
            $prof = DB::table('professeurs')->where('email', $email)->first(['id']);
            if ($prof) return (int) $prof->id;
        }

        $prof = DB::table('professeurs')
            ->where('nom', $data->nom ?? '')
            ->where('prenom', $data->prenom ?? '')
            ->first(['id']);
        return $prof ? (int) $prof->id : null;
    }

    private function dayNameFrench(): string
    {
        $map = ['monday' => 'lundi', 'tuesday' => 'mardi', 'wednesday' => 'mercredi',
                'thursday' => 'jeudi', 'friday' => 'vendredi', 'saturday' => 'samedi'];
        return $map[strtolower($this->localNow()->format('l'))] ?? '';
    }

    private function getTeacherEdtForToday(int $profId): array
    {
        $dayFr = $this->dayNameFrench();
        if (!$dayFr) return [];

        $rows = DB::table('emploi_du_temps as e')
            ->join('professeurs_classes as pc', function ($j) {
                $j->on('pc.classe_id', '=', 'e.classe_id')->on('pc.matiere_id', '=', 'e.matiere_id');
            })
            ->join('classes as c', 'c.id', '=', 'e.classe_id')
            ->join('matieres as m', 'm.id', '=', 'e.matiere_id')
            ->where('pc.professeur_id', $profId)
            ->where('e.jour', $dayFr)
            ->orderBy('e.heure_debut')
            ->get(['e.heure_debut', 'e.heure_fin', 'c.nom as classe_nom', 'm.nom as matiere_nom']);

        $edt = [];
        foreach ($rows as $row) {
            $edt[] = [
                'heure' => str_replace(':', 'h', $row->heure_debut) . '-' . str_replace(':', 'h', $row->heure_fin),
                'matiere' => $row->matiere_nom,
                'classe' => $row->classe_nom,
            ];
        }
        return $edt;
    }

    private function findClassCurrentSlot(int $classeId, Carbon $now, string $heure): ?object
    {
        $dayFr = $this->dayNameFrench();
        if (!$dayFr) return null;

        $slot = DB::table('emploi_du_temps as e')
            ->leftJoin('matieres as m', 'e.matiere_id', '=', 'm.id')
            ->where('e.classe_id', $classeId)
            ->where('e.jour', $dayFr)
            ->where('e.heure_debut', '<=', $heure)
            ->where('e.heure_fin', '>', $heure)
            ->orderBy('e.heure_debut')
            ->first(['e.*', 'm.nom as matiere_nom']);

        if ($slot) {
            $hDebut = (int) explode(':', $slot->heure_debut)[0];
            $slot->_session = $hDebut < 13 ? 'matin' : 'apres_midi';
        }

        return $slot;
    }

    private function getClassEdtForToday(int $classeId): array
    {
        $dayMap = [
            'monday' => 'lundi', 'tuesday' => 'mardi', 'wednesday' => 'mercredi',
            'thursday' => 'jeudi', 'friday' => 'vendredi', 'saturday' => 'samedi',
        ];
        $dayFr = $dayMap[strtolower(now()->format('l'))] ?? '';
        if (!$dayFr) return [];

        $rows = DB::table('emploi_du_temps as e')
            ->leftJoin('matieres as m', 'e.matiere_id', '=', 'm.id')
            ->where('e.classe_id', $classeId)
            ->where('e.jour', $dayFr)
            ->orderBy('e.heure_debut')
            ->get(['e.heure_debut', 'e.heure_fin', 'm.nom as matiere_nom']);

        $edt = [];
        foreach ($rows as $row) {
            $edt[] = [
                'heure' => str_replace(':', 'h', $row->heure_debut) . '-' . str_replace(':', 'h', $row->heure_fin),
                'matiere' => $row->matiere_nom ?? '',
            ];
        }
        return $edt;
    }

    private function determineStudentScanType(int $classeId, string $heure, ?int $studentId = null, ?string $today = null): array
    {
        $dayFr = $this->dayNameFrench();
        $result = ['session' => 'matin', 'type_scan' => 'entree', 'slot' => null, 'retard' => 0];

        if (!$dayFr) {
            $result['session'] = ((int) explode(':', $heure)[0]) < 13 ? 'matin' : 'apres_midi';
            return $result;
        }

        // Get today's EDT slots for this class, ordered by time
        $slots = DB::table('emploi_du_temps as e')
            ->leftJoin('matieres as m', 'e.matiere_id', '=', 'm.id')
            ->where('e.classe_id', $classeId)
            ->where('e.jour', $dayFr)
            ->orderBy('e.heure_debut')
            ->get(['e.*', 'm.nom as matiere_nom']);

        if ($slots->isEmpty()) {
            $result['session'] = ((int) explode(':', $heure)[0]) < 13 ? 'matin' : 'apres_midi';
            return $result;
        }

        // Split slots into morning and afternoon
        $morning = $slots->filter(fn($s) => (int) explode(':', $s->heure_debut)[0] < 13);
        $afternoon = $slots->filter(fn($s) => (int) explode(':', $s->heure_debut)[0] >= 13);

        $hMaintenant = strtotime('2000-01-01 ' . $heure);

        // Determine which session we're in
        $hNow = (int) explode(':', $heure)[0];
        $result['session'] = $hNow < 13 ? 'matin' : 'apres_midi';
        $activeSlots = $result['session'] === 'matin' ? $morning : $afternoon;

        // Check current slot
        $currentSlot = null;
        foreach ($slots as $s) {
            $hs = strtotime('2000-01-01 ' . $s->heure_debut);
            $he = strtotime('2000-01-01 ' . $s->heure_fin);
            if ($hMaintenant >= $hs && $hMaintenant <= $he) {
                $currentSlot = $s;
                break;
            }
        }

        // Check if student already has an "entree" for this session today
        $hasExistingEntree = false;
        if ($studentId && $today) {
            $hasExistingEntree = DB::table('presence_eleves')
                ->where('eleve_id', $studentId)
                ->where('date_jour', $today)
                ->where('session_jour', $result['session'])
                ->where('type_scan', 'entree')
                ->exists();
        }

        // If already has entree → sortie (second scan of the session)
        // Otherwise → entree (first scan of the session)
        if ($hasExistingEntree) {
            $result['type_scan'] = 'sortie';
            if ($currentSlot) {
                $result['slot'] = $currentSlot;
                $result['retard'] = 0;
            }
            return $result;
        }

        // First scan of this session → determine if entree (normal) or retard
        if ($currentSlot) {
            $result['slot'] = $currentSlot;
            $slotStart = strtotime('2000-01-01 ' . $currentSlot->heure_debut);
            $diffStart = $hMaintenant - $slotStart;

            // Within the first 30 min of the slot → entree (with possible retard)
            if ($diffStart <= 1800) {
                $result['type_scan'] = 'entree';
                $result['retard'] = $diffStart > 900 ? 1 : 0; // >15min = retard
            } else {
                // More than 30 min into the slot → still entree (late but still entry)
                $result['type_scan'] = 'entree';
                $result['retard'] = 1;
            }
        } elseif ($activeSlots->isNotEmpty()) {
            // Not in a slot but session has slots → check proximity
            $firstSlot = $activeSlots->first();
            $firstStart = strtotime('2000-01-01 ' . $firstSlot->heure_debut);
            $lastSlot = $activeSlots->last();
            $lastEnd = strtotime('2000-01-01 ' . $lastSlot->heure_fin);

            if ($hMaintenant < $firstStart - 1800) {
                // More than 30 min before first slot → entree (early)
                $result['type_scan'] = 'entree';
                $result['retard'] = 0;
            } elseif ($hMaintenant <= $lastEnd) {
                // Within session hours → entree (arriving during session)
                $result['type_scan'] = 'entree';
                $result['retard'] = $hMaintenant > $firstStart + 900 ? 1 : 0;
            } else {
                // After session ended → sortie
                $result['type_scan'] = 'sortie';
            }
        } else {
            // No slots for this session → default
            $result['type_scan'] = 'entree';
        }

        return $result;
    }

    private function localNow(): Carbon
    {
        $tz = config('app.scan_timezone');
        if ($tz && $tz !== 'UTC') {
            try { return now($tz); } catch (\Throwable $e) {}
        }
        return now('Indian/Antananarivo');
    }

    private function determineCurrentSession(): string
    {
        $h = (int) $this->localNow()->format('G');
        return $h < 13 ? 'matin' : 'apres_midi';
    }

    private function determinePresenceStatut(string $heure, ?string $slotStart = null): string
    {
        $h = (int) explode(':', $heure)[0];
        $m = (int) explode(':', $heure)[1];
        $minutes = $h * 60 + $m;

        if ($slotStart) {
            $sh = (int) explode(':', $slotStart)[0];
            $sm = (int) explode(':', $slotStart)[1];
            $debutCours = $sh * 60 + $sm;
        } else {
            $debutCours = 7 * 60 + 30;
        }

        $tolerance = 15;
        if ($minutes <= $debutCours + $tolerance) return 'present';
        if ($minutes <= $debutCours + 45) return 'retard';
        return 'present';
    }

    private function computeStaffRetard(string $heure): int
    {
        $h = (int) explode(':', $heure)[0];
        $m = (int) explode(':', $heure)[1];
        $minutes = $h * 60 + $m;
        $debut = 7 * 60 + 30; // default start 07:30
        return ($minutes > $debut + 15) ? 1 : 0;
    }

    private function ensureQrTokens($people, string $type)
    {
        $table = match ($type) {
            'etudiant' => 'eleves',
            'enseignant' => 'professeurs',
            'staff' => 'staff',
            default => null,
        };

        if (!$table || !Schema::hasColumn($table, 'qr_token')) {
            return $people;
        }

        $qr = app(QrCodeService::class);
        return $people->map(function ($person) use ($qr, $table) {
            if (empty($person->qr_token) && !empty($person->id)) {
                $person->qr_token = $qr->ensureToken($table, 'qr_token', (int) $person->id);
            }
            return $person;
        });
    }

    private function ensureSession(): void
    {
        abort_unless(session()->has('utilisateur') && in_array(session('utilisateur.role'), ['admin', 'enseignant', 'parent', 'staff'], true), 403);
    }

    private function school(): object
    {
        return DB::table('ecole')->select('nom', 'logo')->first() ?: (object) ['nom' => 'Ecole', 'logo' => 'novaskol.png'];
    }
}
