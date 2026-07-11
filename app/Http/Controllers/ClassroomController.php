<?php

namespace App\Http\Controllers;

use App\Services\Novaskol\ModuleRegistry;
use App\Services\Novaskol\RelationalDeleteService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClassroomController extends Controller
{
    public function index(Request $request, ModuleRegistry $modules)
    {
        if (! session()->has('utilisateur')) {
            return redirect()->route('login');
        }

        $annee = $request->query('annee_scolaire');
        $classesQuery = DB::table('classes as c')
            ->select('c.id', 'c.nom', 'c.niveau')
            ->distinct()
            ->leftJoin('eleves as e', 'c.id', '=', 'e.id_classe');

        if ($annee) {
            $classesQuery->where('e.annee_scolaire', $annee);
        }

        return view('modules.administration.liste-classes', [
            'modules' => $modules->all(),
            'userPermissions' => $this->userPermissions(),
            'ecole' => $this->school(),
            'anneesScolaires' => DB::table('eleves')->select('annee_scolaire')->distinct()->orderByDesc('annee_scolaire')->pluck('annee_scolaire'),
            'anneeScolaireFilter' => $annee,
            'classes' => $classesQuery->orderBy('c.id')->get(),
            'images' => [
                'img/blog/5.jpg', 'img/course/12.jpg', 'img/hero-bg-1.jpg', 'img/course/1.jpg',
                'img/course/9.jpg', 'img/blog/4.jpg', 'img/categories/3.jpg', 'image/news4.jpg',
                'img/course/6.jpg', 'img/course/7.jpg', 'img/course/10.jpg', 'img/course/8.jpg',
                'img/course/11.jpg', 'img/facilities-img-2.jpg', 'img/blog/3.jpg', 'img/blog/1.jpg',
                'img/events/event-preview.jpg', 'img/blog/2.jpg', 'img/course/content-creator.jpg', 'img/about-img-5.jpg',
            ],
        ]);
    }

    public function store(Request $request)
    {
        $this->ensureSession();

        $data = $request->validate([
            'nom' => ['required', 'string', 'max:50'],
            'niveau' => ['nullable', 'integer'],
        ]);

        DB::table('classes')->insert([
            'nom' => trim($data['nom']),
            'niveau' => $data['niveau'] ?? null,
        ]);

        return ['status' => 'success', 'message' => 'Classe ajoutee avec succes'];
    }

    public function update(Request $request, int $id)
    {
        $this->ensureSession();

        $data = $request->validate([
            'nom' => ['required', 'string', 'max:50'],
            'niveau' => ['nullable', 'integer'],
        ]);

        DB::table('classes')->where('id', $id)->update([
            'nom' => trim($data['nom']),
            'niveau' => $data['niveau'] ?? null,
        ]);

        return ['status' => 'success', 'message' => 'Classe modifiee avec succes'];
    }

    public function destroy(int $id, RelationalDeleteService $deletions)
    {
        $this->ensureSession();

        $deletions->deleteClassRelations($id);
        DB::table('classes')->where('id', $id)->delete();

        return ['status' => 'success', 'message' => 'Classe supprimee avec succes'];
    }

    public function students(Request $request, int $id)
    {
        $this->ensureSession();

        $query = DB::table('eleves')
            ->select('matricule', 'nom', 'prenom', 'date_naissance', 'genre', 'statut', 'annee_scolaire', 'adresse', 'telephone', 'nom_pere', 'nom_mere')
            ->where('id_classe', $id);

        if ($request->input('annee_scolaire')) {
            $query->where('annee_scolaire', $request->input('annee_scolaire'));
        }

        return $query->get();
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
        abort_unless(session()->has('utilisateur'), 403);
    }

    private function school(): object
    {
        return DB::table('ecole')->select('nom', 'logo')->first() ?: (object) [
            'nom' => 'Ecole',
            'logo' => 'logo.png',
        ];
    }
}
