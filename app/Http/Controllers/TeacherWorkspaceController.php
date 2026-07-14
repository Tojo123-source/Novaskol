<?php

namespace App\Http\Controllers;

use App\Services\Novaskol\ModuleRegistry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TeacherWorkspaceController extends Controller
{
    public function index(Request $request, ModuleRegistry $modules)
    {
        $teacher = $this->teacher();
        $annee = (string) $request->query('annee_scolaire', $teacher->annee_scolaire ?: $this->currentYear());
        $classeId = (int) $request->query('classe_id', 0);

        $classes = DB::table('professeurs_classes as pc')
            ->join('classes as c', 'c.id', '=', 'pc.classe_id')
            ->where('pc.professeur_id', $teacher->id)
            ->select('c.id', 'c.nom', 'pc.affectation_type', 'pc.commentaire')
            ->orderBy('c.nom')
            ->get();

        $lessons = DB::table('teacher_lessons as l')
            ->leftJoin('classes as c', 'c.id', '=', 'l.classe_id')
            ->leftJoin('matieres as m', 'm.id', '=', 'l.matiere_id')
            ->where('l.professeur_id', $teacher->id)
            ->where('l.annee_scolaire', $annee)
            ->when($classeId > 0, fn ($q) => $q->where('l.classe_id', $classeId))
            ->select('l.*', 'c.nom as classe_nom', 'm.nom as matiere_nom')
            ->orderByRaw(DB::getDriverName() === 'sqlite'
                ? "CASE l.statut WHEN 'en_cours' THEN 1 WHEN 'planifie' THEN 2 WHEN 'a_preparer' THEN 3 WHEN 'termine' THEN 4 ELSE 5 END"
                : "FIELD(l.statut, 'en_cours', 'planifie', 'a_preparer', 'termine')")
            ->orderBy('l.date_prevue')
            ->get();

        $tasks = DB::table('teacher_tasks as t')
            ->leftJoin('teacher_lessons as l', 'l.id', '=', 't.lesson_id')
            ->where('t.professeur_id', $teacher->id)
            ->select('t.*', 'l.titre as lesson_title')
            ->orderBy('t.termine')
            ->orderByRaw('t.date_echeance IS NULL, t.date_echeance')
            ->limit(80)
            ->get();

        $cardData = $this->teacherCardData($teacher);
        $calMonth = (int) $request->query('mois', now()->month);
        $calYear = (int) $request->query('annee', now()->year);
        $teacherAttendance = $this->teacherAttendance((int) $teacher->id, $calMonth, $calYear);

        return view('teacher.workspace', [
            'activeModule' => '',
            'modules' => $modules->all(),
            'userPermissions' => $this->userPermissions(),
            'ecole' => $this->school(),
            'teacher' => $teacher,
            'cardData' => $cardData,
            'calMonth' => $calMonth,
            'calYear' => $calYear,
            'teacherAttendance' => $teacherAttendance,
            'annee' => $annee,
            'annees' => $this->years($teacher),
            'classes' => $classes,
            'selectedClasse' => $classeId,
            'lessons' => $lessons,
            'tasks' => $tasks,
            'stats' => [
                'lessons' => $lessons->count(),
                'done' => $lessons->where('statut', 'termine')->count(),
                'tasks_open' => $tasks->where('termine', 0)->count(),
                'progress' => round((float) $lessons->avg('progression'), 1),
            ],
        ]);
    }

    public function storeLesson(Request $request)
    {
        $teacher = $this->teacher();
        $data = $request->validate([
            'classe_id' => ['nullable', 'integer'],
            'titre' => ['required', 'string', 'max:180'],
            'rubrique' => ['nullable', 'string', 'max:120'],
            'annee_scolaire' => ['required', 'string', 'max:20'],
            'date_prevue' => ['nullable', 'date'],
            'statut' => ['required', 'in:a_preparer,planifie,en_cours,termine'],
            'progression' => ['nullable', 'integer', 'min:0', 'max:100'],
            'objectifs' => ['nullable', 'string'],
            'notes' => ['nullable', 'string'],
        ]);

        DB::table('teacher_lessons')->insert($data + [
            'professeur_id' => $teacher->id,
            'matiere_id' => $teacher->matiere_id,
            'date_realisee' => $data['statut'] === 'termine' ? now()->toDateString() : null,
            'progression' => $data['progression'] ?? 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return back()->with('success', 'Lecon ajoutee dans votre journal pedagogique.');
    }

    public function updateLesson(Request $request, int $id)
    {
        $teacher = $this->teacher();
        $lesson = DB::table('teacher_lessons')->where('id', $id)->where('professeur_id', $teacher->id)->first();
        abort_unless($lesson, 404);

        $data = $request->validate([
            'classe_id' => ['nullable', 'integer'],
            'titre' => ['required', 'string', 'max:180'],
            'rubrique' => ['nullable', 'string', 'max:120'],
            'date_prevue' => ['nullable', 'date'],
            'statut' => ['required', 'in:a_preparer,planifie,en_cours,termine'],
            'progression' => ['required', 'integer', 'min:0', 'max:100'],
            'objectifs' => ['nullable', 'string'],
            'notes' => ['nullable', 'string'],
        ]);

        DB::table('teacher_lessons')->where('id', $id)->update($data + [
            'date_realisee' => $data['statut'] === 'termine' ? ($lesson->date_realisee ?: now()->toDateString()) : null,
            'updated_at' => now(),
        ]);

        return back()->with('success', 'Lecon mise a jour.');
    }

    public function deleteLesson(int $id)
    {
        $teacher = $this->teacher();
        DB::transaction(function () use ($teacher, $id) {
            DB::table('teacher_tasks')->where('professeur_id', $teacher->id)->where('lesson_id', $id)->delete();
            DB::table('teacher_lessons')->where('id', $id)->where('professeur_id', $teacher->id)->delete();
        });

        return back()->with('success', 'Lecon supprimee.');
    }

    public function storeTask(Request $request)
    {
        $teacher = $this->teacher();
        $data = $request->validate([
            'lesson_id' => ['nullable', 'integer'],
            'titre' => ['required', 'string', 'max:180'],
            'date_echeance' => ['nullable', 'date'],
            'priorite' => ['required', 'in:basse,normale,haute'],
        ]);

        DB::table('teacher_tasks')->insert($data + [
            'professeur_id' => $teacher->id,
            'termine' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return back()->with('success', 'Tache ajoutee.');
    }

    public function toggleTask(int $id)
    {
        $teacher = $this->teacher();
        $task = DB::table('teacher_tasks')->where('id', $id)->where('professeur_id', $teacher->id)->first();
        abort_unless($task, 404);
        $done = ! (bool) $task->termine;

        DB::table('teacher_tasks')->where('id', $id)->update([
            'termine' => $done,
            'completed_at' => $done ? now() : null,
            'updated_at' => now(),
        ]);

        return back()->with('success', $done ? 'Tache terminee.' : 'Tache remise en cours.');
    }

    private function teacherAttendance(int $teacherId, int $month, int $year): array
    {
        $records = DB::table('presence_personnels')
            ->where(function ($q) use ($teacherId) {
                $q->where('personne_id', $teacherId)->orWhere('staff_id', $teacherId);
            })
            ->whereYear('date_jour', $year)
            ->whereMonth('date_jour', $month)
            ->select('date_jour', 'session_jour', 'presence', 'retard', 'horaire', 'type_scan', 'heure_entree', 'heure_sortie', 'commentaire', 'scan_mode')
            ->orderBy('date_jour')
            ->orderBy('session_jour')
            ->get();

        $dayNames = ['monday' => 'lundi', 'tuesday' => 'mardi', 'wednesday' => 'mercredi', 'thursday' => 'jeudi', 'friday' => 'vendredi', 'saturday' => 'samedi'];
        $days = [];
        $first = \Carbon\Carbon::create($year, $month, 1);
        $daysInMonth = $first->daysInMonth;

        for ($d = 1; $d <= $daysInMonth; $d++) {
            $date = \Carbon\Carbon::create($year, $month, $d);
            $dayEnglish = strtolower($date->format('l'));
            $dayFr = $dayNames[$dayEnglish] ?? '';

            $edt = collect();
            if ($dayFr) {
                $edt = DB::table('emploi_du_temps as e')
                    ->leftJoin('matieres as m', 'e.matiere_id', '=', 'm.id')
                    ->leftJoin('classes as c', 'e.classe_id', '=', 'c.id')
                    ->where('e.professeur_id', $teacherId)
                    ->where('e.jour', $dayFr)
                    ->orderBy('e.heure_debut')
                    ->get(['e.heure_debut', 'e.heure_fin', 'm.nom as matiere_nom', 'c.nom as classe_nom']);
            }

            $mappedEdt = $edt->map(fn ($s) => [
                'heure' => substr($s->heure_debut, 0, 5) . '-' . substr($s->heure_fin, 0, 5),
                'matiere' => $s->matiere_nom ?? '',
                'classe' => $s->classe_nom ?? '',
            ])->values()->all();

            $dayRecords = $records->filter(fn ($r) => (int) \Carbon\Carbon::parse($r->date_jour)->format('d') === $d);

            if ($dayRecords->isNotEmpty()) {
                $status = 'absent';
                $details = [];
                foreach ($dayRecords as $r) {
                    $s = $r->presence ? ($r->retard ? 'retard' : 'present') : 'absent';
                    if ($s === 'retard' || ($s === 'present' && $status !== 'retard')) $status = $s;
                    $details[] = [
                        'session' => $r->session_jour,
                        'type_scan' => $r->type_scan,
                        'statut' => $s,
                        'heure' => $r->horaire ? number_format((float) $r->horaire, 2) . 'h' : ($r->heure_entree ?? ''),
                        'commentaire' => $r->commentaire,
                        'scan_mode' => $r->scan_mode,
                        'heure_entree' => $r->heure_entree,
                        'heure_sortie' => $r->heure_sortie,
                    ];
                }
                $days[$d] = ['status' => $status, 'details' => $details, 'edt' => $mappedEdt];
            } else {
                $days[$d] = ['status' => null, 'details' => [], 'edt' => $mappedEdt];
            }
        }

        return $days;
    }

    private function teacherCardData(object $teacher): array
    {
        $ecole = $this->school();
        $matiere = DB::table('matieres')->where('id', $teacher->matiere_id ?? 0)->value('nom');
        $annee = $teacher->annee_scolaire ?? session('annee_scolaire') ?? $this->currentYear();

        return [
            'id' => $teacher->id,
            'nom' => $teacher->nom,
            'prenom' => $teacher->prenom,
            'photo' => ($teacher->photo ?? '') ?: 'Uploads/default.jpg',
            'qr_token' => $teacher->qr_token ?? '',
            'matricule' => $teacher->matricule ?? $teacher->id,
            'badge' => 'Enseignant',
            'badge_type' => 'enseignant',
            'dept_label' => 'Matiere',
            'dept_info' => $matiere ?? '',
            'annee_scolaire' => $annee,
            'ecole_nom' => $ecole->nom ?? 'NOVASKOL',
        ];
    }

    private function teacher(): object
    {
        abort_unless(session()->has('utilisateur') && in_array(session('utilisateur.role'), ['admin', 'enseignant'], true), 403);

        $user = session('utilisateur');
        if (($user['role'] ?? '') === 'admin') {
            $teacher = DB::table('professeurs')->orderBy('nom')->first();
        } else {
            $teacher = DB::table('professeurs')->where('email', $user['email'] ?? '')->first();
        }

        abort_unless($teacher, 404, 'Profil enseignant introuvable.');

        return $teacher;
    }

    private function years(object $teacher)
    {
        $lessonYears = DB::table('teacher_lessons')
            ->where('professeur_id', $teacher->id)
            ->whereNotNull('annee_scolaire')
            ->pluck('annee_scolaire');

        $assignmentYears = DB::table('professeurs_classes')
            ->where('professeur_id', $teacher->id)
            ->whereNotNull('annee_scolaire')
            ->pluck('annee_scolaire');

        return $lessonYears
            ->merge($assignmentYears)
            ->filter()
            ->unique()
            ->sortDesc()
            ->values();
    }

    private function currentYear(): string
    {
        return now()->format('Y').'-'.(now()->year + 1);
    }

    private function userPermissions(): array
    {
        $id = (int) session('utilisateur.id', 0);
        return $id ? DB::table('permissions')->where('utilisateur_id', $id)->pluck('acces', 'module')->all() : [];
    }

    private function school(): object
    {
        return DB::table('ecole')->select('nom', 'logo')->first() ?: (object) ['nom' => 'Ecole', 'logo' => 'novaskol.png'];
    }
}
