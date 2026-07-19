<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $q = $request->input('q', '');
        if (mb_strlen($q) < 2) {
            return view('admin.search', ['results' => collect(), 'q' => $q]);
        }

        $term = "%{$q}%";
        $results = collect();

        // Eleves
        $eleves = DB::table('eleves')
            ->where('nom', 'like', $term)
            ->orWhere('prenom', 'like', $term)
            ->orWhere('matricule', 'like', $term)
            ->orWhere('email', 'like', $term)
            ->limit(20)->get()
            ->map(fn ($e) => ['type' => 'eleve', 'id' => $e->id, 'nom' => trim(($e->prenom ?? '') . ' ' . ($e->nom ?? '')), 'detail' => $e->matricule ?? '', 'url' => route('modules.inscription') . '?search=' . urlencode($q)]);

        if ($eleves->count()) $results = $results->concat($eleves);

        // Enseignants / Utilisateurs
        $users = DB::table('utilisateurs')
            ->where('nom', 'like', $term)
            ->orWhere('email', 'like', $term)
            ->limit(20)->get()
            ->map(fn ($u) => ['type' => 'utilisateur', 'id' => $u->id, 'nom' => $u->nom, 'detail' => $u->role . ' - ' . $u->email, 'url' => route('modules.comptes-utilisateurs')]);

        if ($users->count()) $results = $results->concat($users);

        // Parents
        $parents = DB::table('parents')
            ->where('nom', 'like', $term)
            ->orWhere('prenom', 'like', $term)
            ->orWhere('email', 'like', $term)
            ->limit(10)->get()
            ->map(fn ($p) => ['type' => 'parent', 'id' => $p->id, 'nom' => trim(($p->prenom ?? '') . ' ' . ($p->nom ?? '')), 'detail' => $p->email ?? '', 'url' => route('modules.ecole')]);

        if ($parents->count()) $results = $results->concat($parents);

        // Courses
        $courses = DB::table('courses')
            ->where('titre', 'like', $term)
            ->orWhere('description', 'like', $term)
            ->limit(10)->get()
            ->map(fn ($c) => ['type' => 'course', 'id' => $c->id, 'nom' => $c->titre, 'detail' => $c->statut, 'url' => '']);

        if ($courses->count()) $results = $results->concat($courses);

        // Paiements
        $paiements = DB::table('paiements')
            ->where('motif', 'like', $term)
            ->orWhere('reference', 'like', $term)
            ->limit(10)->get()
            ->map(fn ($p) => ['type' => 'paiement', 'id' => $p->id, 'nom' => $p->motif ?? 'Paiement #' . $p->id, 'detail' => $p->reference ?? '', 'url' => route('modules.comptable')]);

        if ($paiements->count()) $results = $results->concat($paiements);

        return view('admin.search', compact('results', 'q'));
    }
}
