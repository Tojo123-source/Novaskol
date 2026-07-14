<?php

namespace App\Http\Controllers;

use App\Services\Novaskol\BulletinCalculator;
use App\Services\Novaskol\ModuleRegistry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BulletinController extends Controller
{
    public function index(ModuleRegistry $modules, BulletinCalculator $calculator)
    {
        $this->ensureSession();

        return view('modules.professeur.bulletin.index', [
            'modules' => $modules->all(),
            'userPermissions' => $this->userPermissions(),
            'ecole' => $this->school(),
            'classes' => $this->classesForUser(),
            'annees' => $this->yearsForUser(),
            'periodLabels' => $calculator->periodLabels,
            'isParent' => session('utilisateur.role') === 'parent',
        ]);
    }

    public function search(Request $request)
    {
        $this->ensureSession();

        $query = trim((string) $request->query('q'));
        $annee = trim((string) $request->query('annee_scolaire'));

        if (mb_strlen($query) < 2 || $annee === '') {
            return response()->json([]);
        }

        $queryBuilder = DB::table('eleves as e')
            ->join('classes as c', 'e.id_classe', '=', 'c.id')
            ->select('e.id', 'e.nom', 'e.prenom', 'e.matricule', 'c.nom as classe')
            ->where('e.annee_scolaire', $annee)
            ->where(function ($builder) use ($query) {
                $builder->where('e.nom', 'like', "%{$query}%")
                    ->orWhere('e.prenom', 'like', "%{$query}%")
                    ->orWhere('e.matricule', 'like', "%{$query}%");
            });

        if (session('utilisateur.role') === 'parent') {
            $queryBuilder->join('parent_eleves as pe', 'pe.eleve_id', '=', 'e.id')
                ->where('pe.parent_user_id', (int) session('utilisateur.id'));
        }

        return $queryBuilder
            ->orderBy('e.nom')
            ->limit(20)
            ->get();
    }

    public function student(Request $request, ModuleRegistry $modules, BulletinCalculator $calculator)
    {
        $this->ensureSession();

        $data = $request->validate([
            'eleve_id' => ['required', 'integer'],
            'periode' => ['required', 'in:B1,B2,T1,T2,T3'],
            'annee_scolaire' => ['required', 'string'],
        ]);

        $bulletin = $calculator->studentBulletin((int) $data['eleve_id'], $data['periode'], $data['annee_scolaire']);

        abort_if(! $bulletin, 404, 'Eleve introuvable.');
        $this->ensureParentOwnsStudent((int) $data['eleve_id']);

        return view('modules.professeur.bulletin.student', [
            'modules' => $modules->all(),
            'userPermissions' => $this->userPermissions(),
            'ecole' => $this->school(),
            'periodLabels' => $calculator->periodLabels,
            'calculator' => $calculator,
            'periode' => $data['periode'],
            'annee' => $data['annee_scolaire'],
        ] + $bulletin);
    }

    public function classe(Request $request, ModuleRegistry $modules, BulletinCalculator $calculator)
    {
        $this->ensureSession();

        $data = $request->validate([
            'classe_id' => ['required', 'integer', 'exists:classes,id'],
            'periode' => ['required', 'in:B1,B2,T1,T2,T3'],
            'annee_scolaire' => ['required', 'string'],
        ]);

        abort_if(session('utilisateur.role') === 'parent', 403, 'Les parents consultent uniquement le bulletin de leurs enfants.');

        $bulletins = $calculator->classBulletins((int) $data['classe_id'], $data['periode'], $data['annee_scolaire']);

        return view('modules.professeur.bulletin.classe', [
            'modules' => $modules->all(),
            'userPermissions' => $this->userPermissions(),
            'ecole' => $this->school(),
            'periodLabels' => $calculator->periodLabels,
            'calculator' => $calculator,
            'periode' => $data['periode'],
            'annee' => $data['annee_scolaire'],
        ] + $bulletins);
    }

    public function annualIndex(ModuleRegistry $modules)
    {
        $this->ensureSession();

        return view('modules.professeur.bulletin.annual-index', [
            'modules' => $modules->all(),
            'userPermissions' => $this->userPermissions(),
            'ecole' => $this->school(),
            'annees' => $this->yearsForUser(),
            'isParent' => session('utilisateur.role') === 'parent',
        ]);
    }

    public function annualStudent(Request $request, ModuleRegistry $modules, BulletinCalculator $calculator)
    {
        $this->ensureSession();

        $data = $request->validate([
            'eleve_id' => ['required', 'integer'],
            'annee_scolaire' => ['required', 'string'],
        ]);

        $bulletin = $calculator->annualStudentBulletin((int) $data['eleve_id'], $data['annee_scolaire']);

        abort_if(! $bulletin, 404, 'Eleve introuvable.');
        $this->ensureParentOwnsStudent((int) $data['eleve_id']);

        return view('modules.professeur.bulletin.annual-student', [
            'modules' => $modules->all(),
            'userPermissions' => $this->userPermissions(),
            'ecole' => $this->school(),
            'periodLabels' => $calculator->periodLabels,
            'calculator' => $calculator,
            'annee' => $data['annee_scolaire'],
        ] + $bulletin);
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

    private function ensureParentOwnsStudent(int $studentId): void
    {
        if (session('utilisateur.role') !== 'parent') {
            return;
        }

        $owns = DB::table('parent_eleves')
            ->where('parent_user_id', (int) session('utilisateur.id'))
            ->where('eleve_id', $studentId)
            ->exists();

        abort_unless($owns, 403, 'Acces refuse a ce bulletin.');
    }

    private function classesForUser()
    {
        if (session('utilisateur.role') !== 'parent') {
            return DB::table('classes')->select('id', 'nom')->orderBy('nom')->get();
        }

        return DB::table('parent_eleves as pe')
            ->join('eleves as e', 'e.id', '=', 'pe.eleve_id')
            ->join('classes as c', 'c.id', '=', 'e.id_classe')
            ->where('pe.parent_user_id', (int) session('utilisateur.id'))
            ->select('c.id', 'c.nom')
            ->distinct()
            ->orderBy('c.nom')
            ->get();
    }

    private function yearsForUser()
    {
        if (session('utilisateur.role') !== 'parent') {
            return DB::table('eleves')->select('annee_scolaire')->distinct()->orderByDesc('annee_scolaire')->pluck('annee_scolaire');
        }

        return DB::table('parent_eleves as pe')
            ->join('eleves as e', 'e.id', '=', 'pe.eleve_id')
            ->where('pe.parent_user_id', (int) session('utilisateur.id'))
            ->select('e.annee_scolaire')
            ->distinct()
            ->orderByDesc('e.annee_scolaire')
            ->pluck('e.annee_scolaire');
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
