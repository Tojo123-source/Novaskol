<?php

namespace App\Http\Controllers;

use App\Services\Novaskol\ModuleRegistry;
use App\Services\Novaskol\RelationalDeleteService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SubjectController extends Controller
{
    public function show(Request $request, ModuleRegistry $modules)
    {
        if (! session()->has('utilisateur')) {
            return redirect()->route('login');
        }

        if (! in_array(session('utilisateur.role'), ['admin', 'enseignant', 'parent', 'staff'], true)) {
            return redirect()->route('login');
        }

        $classeId = (int) $request->query('classe_id', 0);

        return view('modules.administration.matieres', [
            'modules' => $modules->all(),
            'userPermissions' => $this->userPermissions(),
            'ecole' => $this->school(),
            'classes' => DB::table('classes')->select('id', 'nom')->orderBy('nom')->get(),
            'matieres' => DB::table('matieres')->select('id', 'nom')->orderBy('nom')->get(),
            'classeId' => $classeId,
            'assignedSubjects' => $this->assignedSubjects($classeId),
        ]);
    }

    public function store(Request $request)
    {
        $this->ensureSession();

        $nom = trim((string) $request->input('nouvelle_matiere'));

        if ($nom === '') {
            return back()->with('matiere_msg', ['type' => 'error', 'text' => 'Le nom de la matiere est requis.']);
        }

        $exists = DB::table('matieres')->where('nom', $nom)->exists();

        if ($exists) {
            return back()->with('matiere_msg', ['type' => 'error', 'text' => 'Cette matiere existe deja.']);
        }

        DB::table('matieres')->insert(['nom' => $nom]);

        return back()->with('matiere_msg', ['type' => 'success', 'text' => "Matiere « {$nom} » ajoutee avec succes !"]);
    }

    public function rename(Request $request)
    {
        $this->ensureSession();

        $id = (int) $request->input('matiere_id_rename');
        $nom = trim((string) $request->input('nouveau_nom_rename'));

        if ($id <= 0 || $nom === '') {
            return back()->with('matiere_msg', ['type' => 'error', 'text' => 'Donnees invalides.']);
        }

        $exists = DB::table('matieres')->where('nom', $nom)->where('id', '!=', $id)->exists();

        if ($exists) {
            return back()->with('matiere_msg', ['type' => 'error', 'text' => 'Ce nom est deja utilise.']);
        }

        DB::table('matieres')->where('id', $id)->update(['nom' => $nom]);

        return back()->with('matiere_msg', ['type' => 'success', 'text' => 'Matiere renommee avec succes !']);
    }

    public function destroy(Request $request, RelationalDeleteService $deletions)
    {
        $this->ensureSession();

        $id = (int) $request->input('matiere_id_delete');

        if ($id <= 0) {
            return back()->with('matiere_msg', ['type' => 'error', 'text' => 'ID invalide.']);
        }

        $deletions->deleteSubjectRelations($id);
        DB::table('matieres')->where('id', $id)->delete();

        return back()->with('matiere_msg', ['type' => 'success', 'text' => 'Matiere supprimee avec succes.']);
    }

    public function updateAssignments(Request $request)
    {
        $this->ensureSession();

        $classeId = (int) $request->input('classe_id');
        $matieres = $request->input('matieres', []);
        $coefficients = $request->input('coefficients', []);

        if ($classeId <= 0) {
            return redirect()->route('modules.matieres')
                ->with('matiere_msg', ['type' => 'error', 'text' => 'Classe invalide.']);
        }

        DB::table('classe_matieres')->where('id_classe', $classeId)->delete();

        foreach ($matieres as $matiereId) {
            $matiereId = (int) $matiereId;
            $coefficient = isset($coefficients[$matiereId]) ? (float) $coefficients[$matiereId] : 1;
            $coefficient = $coefficient > 0 ? $coefficient : 1;

            DB::table('classe_matieres')->insert([
                'id_classe' => $classeId,
                'id_matiere' => $matiereId,
                'coefficient' => $coefficient,
            ]);
        }

        return redirect()->route('modules.matieres', ['classe_id' => $classeId])
            ->with('matiere_msg', ['type' => 'success', 'text' => 'Affectation des matieres mise a jour !']);
    }

    private function assignedSubjects(int $classeId): array
    {
        if ($classeId <= 0) {
            return [];
        }

        return DB::table('classe_matieres')
            ->where('id_classe', $classeId)
            ->pluck('coefficient', 'id_matiere')
            ->all();
    }

    private function ensureSession(): void
    {
        abort_unless(session()->has('utilisateur'), 403);
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

    private function school(): object
    {
        return DB::table('ecole')->select('nom', 'logo')->first() ?: (object) [
            'nom' => 'Ecole',
            'logo' => 'novaskol.png',
        ];
    }
}
