<?php

namespace App\Http\Controllers;

use App\Services\Novaskol\ModuleRegistry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GradeController extends Controller
{
    private array $periodLabels = [
        'B1' => '1er Bimestre',
        'B2' => '2e Bimestre',
        'T1' => '1er Trimestre',
        'T2' => '2e Trimestre',
        'T3' => '3e Trimestre',
    ];

    public function index(Request $request, ModuleRegistry $modules)
    {
        $this->ensureSession();

        $classeId = (int) $request->query('classe', 0);
        $periode = (string) $request->query('periode', 'T1');
        $annee = (string) $request->query('annee_scolaire', $this->currentSchoolYear());
        $classes = $this->availableClassesForUser($annee);

        if (! array_key_exists($periode, $this->periodLabels)) {
            $periode = 'T1';
        }
        if ($classeId > 0 && session('utilisateur.role') === 'enseignant' && ! $classes->pluck('id')->contains($classeId)) {
            $classeId = 0;
        }

        return view('modules.professeur.notes', [
            'modules' => $modules->all(),
            'userPermissions' => $this->userPermissions(),
            'ecole' => $this->school(),
            'classes' => $classes,
            'annees' => DB::table('eleves')->select('annee_scolaire')->distinct()->orderByDesc('annee_scolaire')->pluck('annee_scolaire'),
            'periodLabels' => $this->periodLabels,
            'selectedClasse' => $classeId,
            'selectedPeriode' => $periode,
            'selectedAnnee' => $annee,
            'classeNom' => DB::table('classes')->where('id', $classeId)->value('nom') ?: '',
            'matieres' => $this->subjectsForClass($classeId),
            'eleves' => $this->studentsForClass($classeId, $annee),
            'notesExistantes' => $this->existingGrades($classeId, $annee, $periode),
            'remarquesExistantes' => $this->existingRemarks($classeId, $annee, $periode),
        ]);
    }

    public function store(Request $request)
    {
        $this->ensureSession();

        $data = $request->validate([
            'classe' => ['required', 'integer', 'exists:classes,id'],
            'periode' => ['required', 'in:B1,B2,T1,T2,T3'],
            'annee_scolaire' => ['required', 'string', 'max:10'],
            'notes' => ['array'],
            'remarques' => ['array'],
        ]);

        $classeId = (int) $data['classe'];
        abort_unless($this->canTeacherUseClass($classeId, $data['annee_scolaire']), 403);
        $periode = $data['periode'];
        $annee = $data['annee_scolaire'];
        $notes = $this->filterNotesForCurrentTeacher($classeId, $request->input('notes', []));
        $remarques = $request->input('remarques', []);

        DB::transaction(function () use ($notes, $remarques, $periode, $annee) {
            foreach ($notes as $eleveId => $matiereNotes) {
                foreach ((array) $matiereNotes as $matiereId => $note) {
                    $note = trim((string) $note);

                    if ($note === '' || ! is_numeric($note) || (float) $note < 0 || (float) $note > 20) {
                        DB::table('notes')
                            ->where('id_eleve', (int) $eleveId)
                            ->where('id_matiere', (int) $matiereId)
                            ->where('periode', $periode)
                            ->where('annee_scolaire', $annee)
                            ->delete();
                        continue;
                    }

                    $trimestre = match ($periode) { 'T1' => 1, 'T2' => 2, 'T3' => 3, default => 0 };

                    DB::table('notes')->updateOrInsert(
                        [
                            'id_eleve' => (int) $eleveId,
                            'id_matiere' => (int) $matiereId,
                            'periode' => $periode,
                            'annee_scolaire' => $annee,
                        ],
                        [
                            'eleve_id' => (int) $eleveId,
                            'matiere_id' => (int) $matiereId,
                            'note' => (float) $note,
                            'valeur' => (float) $note,
                            'trimestre' => $trimestre,
                        ]
                    );
                }
            }

            foreach ($remarques as $eleveId => $remarque) {
                $remarque = trim((string) $remarque);

                if ($remarque === '') {
                    DB::table('remarques')
                        ->where('id_eleve', (int) $eleveId)
                        ->where('periode', $periode)
                        ->where('annee_scolaire', $annee)
                        ->delete();
                    continue;
                }

                $trimestre = match ($periode) { 'T1' => 1, 'T2' => 2, 'T3' => 3, default => 0 };

                DB::table('remarques')->updateOrInsert(
                    [
                        'id_eleve' => (int) $eleveId,
                        'periode' => $periode,
                        'annee_scolaire' => $annee,
                    ],
                    [
                        'eleve_id' => (int) $eleveId,
                        'titre' => "Remarque $periode",
                        'contenu' => $remarque,
                        'remarque' => $remarque,
                        'trimestre' => $trimestre,
                    ]
                );
            }
        });

        return redirect()->route('modules.notes', [
            'classe' => $classeId,
            'periode' => $periode,
            'annee_scolaire' => $annee,
        ])->with('notes_msg', ['type' => 'success', 'text' => 'Notes et remarques enregistrees avec succes.']);
    }

    private function subjectsForClass(int $classeId)
    {
        if ($classeId <= 0) {
            return collect();
        }

        $query = DB::table('classe_matieres as cm')
            ->join('matieres as m', 'cm.id_matiere', '=', 'm.id')
            ->select('m.id', 'm.nom', 'cm.coefficient')
            ->where('cm.id_classe', $classeId)
            ->orderBy('m.nom');

        if (session('utilisateur.role') === 'enseignant') {
            $teacher = $this->currentTeacher();
            if ($teacher?->matiere_id) {
                $query->where('m.id', $teacher->matiere_id);
            }
        }

        return $query->get();
    }

    private function studentsForClass(int $classeId, string $annee)
    {
        if ($classeId <= 0 || $annee === '') {
            return collect();
        }

        return DB::table('eleves')
            ->select('id', 'nom', 'prenom')
            ->where('id_classe', $classeId)
            ->where('annee_scolaire', $annee)
            ->orderBy('nom')
            ->orderBy('prenom')
            ->get();
    }

    private function existingGrades(int $classeId, string $annee, string $periode): array
    {
        if ($classeId <= 0 || $annee === '') {
            return [];
        }

        $rows = DB::table('notes as n')
            ->join('eleves as e', DB::raw('COALESCE(n.eleve_id, n.id_eleve)'), '=', 'e.id')
            ->where('e.id_classe', $classeId)
            ->where('e.annee_scolaire', $annee)
            ->where(function ($q) use ($periode) {
                $q->where('n.periode', $periode)
                  ->orWhere('n.trimestre', match ($periode) { 'T1' => 1, 'T2' => 2, 'T3' => 3, default => 0 });
            })
            ->where('n.annee_scolaire', $annee)
            ->select('n.eleve_id', 'n.id_eleve', 'n.id_matiere', 'n.matiere_id', 'n.note', 'n.valeur')
            ->get();

        $grades = [];

        foreach ($rows as $row) {
            $eleveId = $row->eleve_id ?? $row->id_eleve;
            $matiereId = $row->id_matiere ?? $row->matiere_id;
            $note = $row->note ?? $row->valeur;
            if ($eleveId && $matiereId) {
                $grades[(int) $eleveId][(int) $matiereId] = $note;
            }
        }

        return $grades;
    }

    private function existingRemarks(int $classeId, string $annee, string $periode): array
    {
        if ($classeId <= 0 || $annee === '') {
            return [];
        }

        $studentIds = DB::table('eleves')
            ->where('id_classe', $classeId)
            ->where('annee_scolaire', $annee)
            ->pluck('id');

        $rows = DB::table('remarques')
            ->where('annee_scolaire', $annee)
            ->where(function ($q) use ($periode) {
                $q->where('periode', $periode)
                  ->orWhere('trimestre', match ($periode) { 'T1' => 1, 'T2' => 2, 'T3' => 3, default => 0 });
            })
            ->where(function ($q) use ($studentIds) {
                $q->whereIn('id_eleve', $studentIds)
                  ->orWhereIn('eleve_id', $studentIds);
            })
            ->selectRaw("COALESCE(id_eleve, eleve_id) as eleve_id_out, COALESCE(remarque, contenu) as remark_text")
            ->get();

        $remarks = [];
        foreach ($rows as $row) {
            $remarks[(int) $row->eleve_id_out] = $row->remark_text;
        }

        return $remarks;
    }

    private function currentSchoolYear(): string
    {
        $latest = DB::table('eleves')
            ->whereNotNull('annee_scolaire')
            ->where('annee_scolaire', '!=', '')
            ->orderByDesc('annee_scolaire')
            ->value('annee_scolaire');

        if ($latest) {
            return (string) $latest;
        }

        $year = (int) now()->format('Y');

        return $year.'-'.($year + 1);
    }

    private function userPermissions(): array
    {
        if (! session('utilisateur.id')) {
            return [];
        }

        return DB::table('permissions')
            ->where('utilisateur_id', session('utilisateur.id'))
            ->pluck('acces', 'module')
            ->all();
    }

    private function ensureSession(): void
    {
        abort_unless(session()->has('utilisateur') && in_array(session('utilisateur.role'), ['admin', 'enseignant', 'staff', 'parent'], true), 403);
    }

    private function availableClassesForUser(string $annee)
    {
        if (session('utilisateur.role') !== 'enseignant') {
            return DB::table('classes')->select('id', 'nom')->orderBy('nom')->get();
        }

        $teacher = $this->currentTeacher();
        if (! $teacher) {
            return collect();
        }

        return DB::table('professeurs_classes as pc')
            ->join('classes as c', 'c.id', '=', 'pc.classe_id')
            ->when($teacher->matiere_id, function ($query) use ($teacher) {
                $query->join('classe_matieres as cm', function ($join) use ($teacher) {
                    $join->on('cm.id_classe', '=', 'c.id')
                        ->where('cm.id_matiere', '=', $teacher->matiere_id);
                });
            })
            ->where('pc.professeur_id', $teacher->id)
            ->select('c.id', 'c.nom as classe_nom', DB::raw(novaskol_concat('c.nom', "' - '", "CASE WHEN COALESCE(pc.affectation_type, 'fixe')='flexible' THEN 'intervention' ELSE 'fixe' END").' as nom'))
            ->distinct()
            ->orderBy('classe_nom')
            ->get();
    }

    private function canTeacherUseClass(int $classeId, string $annee): bool
    {
        if (session('utilisateur.role') !== 'enseignant') {
            return true;
        }

        $teacher = $this->currentTeacher();
        if (! $teacher) {
            return false;
        }

        $assigned = DB::table('professeurs_classes')
            ->where('professeur_id', $teacher->id)
            ->where('classe_id', $classeId)
            ->exists();

        if (! $assigned) {
            return false;
        }

        if (! $teacher->matiere_id) {
            return false;
        }

        return DB::table('classe_matieres')
            ->where('id_classe', $classeId)
            ->where('id_matiere', $teacher->matiere_id)
            ->exists();
    }

    private function filterNotesForCurrentTeacher(int $classeId, array $notes): array
    {
        if (session('utilisateur.role') !== 'enseignant') {
            return $notes;
        }

        $allowedSubjectIds = $this->subjectsForClass($classeId)
            ->pluck('id')
            ->map(fn ($id) => (int) $id)
            ->all();

        if (empty($allowedSubjectIds)) {
            return [];
        }

        $filtered = [];
        foreach ($notes as $studentId => $subjectNotes) {
            foreach ((array) $subjectNotes as $subjectId => $note) {
                if (in_array((int) $subjectId, $allowedSubjectIds, true)) {
                    $filtered[$studentId][$subjectId] = $note;
                }
            }
        }

        return $filtered;
    }

    private function currentTeacher(): ?object
    {
        if (session('utilisateur.role') !== 'enseignant') {
            return null;
        }

        return DB::table('professeurs')->where('email', session('utilisateur.email'))->first();
    }

    private function school(): object
    {
        return DB::table('ecole')->select('nom', 'logo')->first() ?: (object) [
            'nom' => 'Ecole',
            'logo' => 'logo.png',
        ];
    }
}
