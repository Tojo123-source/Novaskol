<?php

namespace App\Http\Controllers;

use App\Services\Novaskol\ModuleRegistry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExamBlankController extends Controller
{
    private array $sessionLabels = [
        '1' => '1er Examen Blanc',
        '2' => '2e Examen Blanc',
        '3' => '3e Examen Blanc',
    ];

    private array $examClassNames = ['CM2', '3e', 'TA', 'TD', 'TS', 'TL', 'TC', 'TOSE'];

    public function index(Request $request, ModuleRegistry $modules)
    {
        $this->ensureSession();

        $classeId = (int) $request->query('classe', 0);
        $session = (string) $request->query('session', '1');
        $annee = (string) $request->query('annee_scolaire', $this->currentSchoolYear());

        if (! array_key_exists($session, $this->sessionLabels)) {
            $session = '1';
        }

        $subjects = $this->subjectsForClass($classeId);

        return view('modules.professeur.examen-blanc.index', [
            'modules' => $modules->all(),
            'userPermissions' => $this->userPermissions(),
            'ecole' => $this->school(),
            'classes' => $this->examClasses(),
            'annees' => $this->schoolYears(),
            'sessionLabels' => $this->sessionLabels,
            'selectedClasse' => $classeId,
            'selectedSession' => $session,
            'selectedAnnee' => $annee,
            'classeNom' => DB::table('classes')->where('id', $classeId)->value('nom') ?: '',
            'matieres' => $subjects,
            'eleves' => $this->studentsForClass($classeId, $annee),
            'notesExistantes' => $this->existingExamNotes($classeId, $annee, $session),
            'remarquesExistantes' => $this->existingExamRemarks($classeId, $annee, $session),
        ]);
    }

    public function store(Request $request)
    {
        $this->ensureSession();

        $data = $request->validate([
            'classe' => ['required', 'integer', 'exists:classes,id'],
            'session' => ['required', 'in:1,2,3'],
            'annee_scolaire' => ['required', 'string', 'max:10'],
            'notes' => ['array'],
            'remarques' => ['array'],
        ]);

        $classeId = (int) $data['classe'];
        $session = $data['session'];
        $annee = $data['annee_scolaire'];
        $notes = $request->input('notes', []);
        $remarques = $request->input('remarques', []);

        DB::transaction(function () use ($classeId, $session, $annee, $notes, $remarques) {
            foreach ($notes as $eleveId => $matiereNotes) {
                foreach ((array) $matiereNotes as $matiereId => $note) {
                    $note = trim((string) $note);

                    if ($note === '' || ! is_numeric($note) || (float) $note < 0 || (float) $note > 20) {
                        continue;
                    }

                    DB::table('examen_blanc')->updateOrInsert(
                        [
                            'eleve_id' => (int) $eleveId,
                            'matiere_id' => (int) $matiereId,
                            'session' => $session,
                            'annee_scolaire' => $annee,
                        ],
                        [
                            'titre' => "Examen $session",
                            'classe_id' => $classeId,
                            'note' => (float) $note,
                            'date_examen' => now()->toDateString(),
                        ]
                    );
                }
            }

            foreach ($remarques as $eleveId => $remarque) {
                $remarque = trim((string) $remarque);

                if ($remarque === '') {
                    continue;
                }

                DB::table('remarques_examen_blanc')->updateOrInsert(
                    [
                        'id_eleve' => (int) $eleveId,
                        'session' => $session,
                        'annee_scolaire' => $annee,
                    ],
                    [
                        'eleve_id' => (int) $eleveId,
                        'examen_id' => 0,
                        'contenu' => $remarque,
                        'remarque' => $remarque,
                    ]
                );
            }
        });

        return redirect()->route('modules.examen-blanc', [
            'classe' => $classeId,
            'session' => $session,
            'annee_scolaire' => $annee,
        ])->with('exam_msg', ['type' => 'success', 'text' => 'Notes et remarques enregistrees avec succes.']);
    }

    public function results(Request $request, ModuleRegistry $modules)
    {
        $this->ensureSession();

        $annee = (string) $request->query('annee_scolaire', '');
        $session = (string) $request->query('session', '');
        $classeId = (int) $request->query('id_classe', 0);
        $resultData = null;

        if ($annee !== '' && array_key_exists($session, $this->sessionLabels) && $classeId > 0) {
            $resultData = $this->examResults($classeId, $session, $annee);
        }

        return view('modules.professeur.examen-blanc.results', [
            'modules' => $modules->all(),
            'userPermissions' => $this->userPermissions(),
            'ecole' => $this->school(),
            'classes' => $this->examClasses(),
            'annees' => $this->schoolYears(),
            'sessionLabels' => $this->sessionLabels,
            'selectedAnnee' => $annee,
            'selectedSession' => $session,
            'selectedClasse' => $classeId,
            'resultData' => $resultData,
        ]);
    }

    private function examResults(int $classeId, string $session, string $annee): array
    {
        $students = $this->studentsForClass($classeId, $annee);
        $subjects = $this->subjectsForClass($classeId);
        $notesBySession = [];
        $averagesBySession = [];

        if ($students->isNotEmpty() && $subjects->isNotEmpty()) {
            $studentIds = $students->pluck('id')->all();
            $subjectIds = $subjects->pluck('id')->all();

            foreach (array_keys($this->sessionLabels) as $examSession) {
                $rows = DB::table('examen_blanc')
                    ->select('eleve_id', 'matiere_id', 'note')
                    ->whereIn('eleve_id', $studentIds)
                    ->whereIn('matiere_id', $subjectIds)
                    ->where('session', $examSession)
                    ->where('annee_scolaire', $annee)
                    ->get();

                foreach ($rows as $row) {
                    $notesBySession[$examSession][$row->eleve_id][$row->matiere_id] = $row->note;
                }

                foreach ($students as $student) {
                    $total = 0;
                    $coefTotal = 0;

                    foreach ($subjects as $subject) {
                        $note = $notesBySession[$examSession][$student->id][$subject->id] ?? 0;
                        $note = is_numeric($note) ? (float) $note : 0;
                        $coef = (float) $subject->coefficient;
                        $total += $note * $coef;
                        $coefTotal += $coef;
                    }

                    $averagesBySession[$examSession][$student->id] = $coefTotal > 0 ? round($total / $coefTotal, 2) : null;
                }
            }
        }

        $averages = [];
        foreach ($students as $student) {
            $averages[$student->id] = $averagesBySession[$session][$student->id] ?? null;
        }

        $orderedIds = array_keys($averages);
        usort($orderedIds, function ($a, $b) use ($averages) {
            return ($averages[$b] ?? -1) <=> ($averages[$a] ?? -1);
        });

        $ranks = [];
        $position = 1;
        foreach ($orderedIds as $studentId) {
            $ranks[$studentId] = $averages[$studentId] !== null ? $position++ : 'Non classe';
        }

        $numericAverages = array_values(array_filter($averages, fn ($value) => is_numeric($value)));

        return [
            'classe' => DB::table('classes')->select('id', 'nom')->where('id', $classeId)->first(),
            'students' => $students,
            'subjects' => $subjects,
            'notes' => $notesBySession[$session] ?? [],
            'averages' => $averages,
            'ranks' => $ranks,
            'orderedIds' => $orderedIds,
            'classAverage' => count($numericAverages) > 0 ? round(array_sum($numericAverages) / count($numericAverages), 2) : 0,
        ];
    }

    private function examClasses()
    {
        return DB::table('classes')
            ->select('id', 'nom')
            ->whereIn('nom', $this->examClassNames)
            ->orderBy('nom')
            ->get();
    }

    private function schoolYears()
    {
        return DB::table('eleves')
            ->select('annee_scolaire')
            ->whereNotNull('annee_scolaire')
            ->where('annee_scolaire', '!=', '')
            ->distinct()
            ->orderByDesc('annee_scolaire')
            ->pluck('annee_scolaire');
    }

    private function subjectsForClass(int $classeId)
    {
        if ($classeId <= 0) {
            return collect();
        }

        return DB::table('classe_matieres as cm')
            ->join('matieres as m', 'cm.id_matiere', '=', 'm.id')
            ->select('m.id', 'm.nom', 'cm.coefficient')
            ->where('cm.id_classe', $classeId)
            ->orderBy('m.nom')
            ->get();
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

    private function existingExamNotes(int $classeId, string $annee, string $session): array
    {
        if ($classeId <= 0 || $annee === '') {
            return [];
        }

        $rows = DB::table('examen_blanc as n')
            ->join('eleves as e', 'n.eleve_id', '=', 'e.id')
            ->where('e.id_classe', $classeId)
            ->where('e.annee_scolaire', $annee)
            ->where('n.session', $session)
            ->where('n.annee_scolaire', $annee)
            ->select('n.eleve_id', 'n.matiere_id', 'n.note')
            ->get();

        $grades = [];
        foreach ($rows as $row) {
            $grades[$row->eleve_id][$row->matiere_id] = $row->note;
        }

        return $grades;
    }

    private function existingExamRemarks(int $classeId, string $annee, string $session): array
    {
        if ($classeId <= 0 || $annee === '') {
            return [];
        }

        return DB::table('remarques_examen_blanc')
            ->where('session', $session)
            ->where('annee_scolaire', $annee)
            ->whereIn('id_eleve', function ($query) use ($classeId, $annee) {
                $query->select('id')
                    ->from('eleves')
                    ->where('id_classe', $classeId)
                    ->where('annee_scolaire', $annee);
            })
            ->pluck('remarque', 'id_eleve')
            ->all();
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
        abort_unless(session()->has('utilisateur') && in_array(session('utilisateur.role'), ['admin', 'enseignant', 'parent', 'staff'], true), 403);
    }

    private function school(): object
    {
        return DB::table('ecole')->select('nom', 'logo')->first() ?: (object) [
            'nom' => 'Ecole',
            'logo' => 'novaskol.png',
        ];
    }
}
