<?php

namespace App\Http\Controllers;

use App\Services\Novaskol\BulletinCalculator;
use App\Services\Novaskol\ModuleRegistry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ResultController extends Controller
{
    public function index(Request $request, ModuleRegistry $modules, BulletinCalculator $calculator)
    {
        $this->ensureSession();

        $annee = (string) $request->query('annee_scolaire', '');
        $periode = (string) $request->query('periode', '');
        $classeId = (int) $request->query('id_classe', 0);
        $resultData = null;

        if ($annee !== '' && in_array($periode, $calculator->validPeriods(), true) && $classeId > 0) {
            $resultData = $this->classResults($classeId, $periode, $annee, $calculator);
        }

        return view('modules.professeur.resultats.index', [
            'modules' => $modules->all(),
            'userPermissions' => $this->userPermissions(),
            'ecole' => $this->school(),
            'classes' => DB::table('classes')->select('id', 'nom')->orderBy('nom')->get(),
            'annees' => DB::table('eleves')->select('annee_scolaire')->whereNotNull('annee_scolaire')->where('annee_scolaire', '!=', '')->distinct()->orderByDesc('annee_scolaire')->pluck('annee_scolaire'),
            'periodLabels' => $calculator->periodLabels,
            'selectedAnnee' => $annee,
            'selectedPeriode' => $periode,
            'selectedClasse' => $classeId,
            'resultData' => $resultData,
        ]);
    }

    private function classResults(int $classeId, string $periode, string $annee, BulletinCalculator $calculator): array
    {
        $base = $calculator->classBulletins($classeId, $periode, $annee);
        $students = $base['students'];
        $periodAverages = $base['periodAverages'];
        $averages = [];

        foreach ($students as $student) {
            $studentAverages = [];

            foreach ($calculator->validPeriods() as $period) {
                $studentAverages[$period] = $periodAverages[$period][$student->id] ?? null;
            }

            if ($periode === 'T3') {
                $t1 = $calculator->combinedAverage($studentAverages, 'T1');
                $t2 = $calculator->combinedAverage($studentAverages, 'T2');
                $t3 = $studentAverages['T3'] ?? null;
                $valid = array_values(array_filter([$t1, $t2, $t3], fn ($value) => is_numeric($value)));
                $averages[$student->id] = empty($valid) ? null : round(array_sum($valid) / count($valid), 2);
            } else {
                $averages[$student->id] = $calculator->combinedAverage($studentAverages, $periode);
            }
        }

        $ranks = $calculator->ranks($averages);
        $orderedIds = array_keys($averages);
        usort($orderedIds, function ($a, $b) use ($averages) {
            return ($averages[$b] ?? -1) <=> ($averages[$a] ?? -1);
        });

        return $base + [
            'resultAverages' => $averages,
            'resultRanks' => $ranks,
            'orderedIds' => $orderedIds,
            'resultClassAverage' => $calculator->averageOf($averages),
        ];
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
            'logo' => 'logo.png',
        ];
    }
}
