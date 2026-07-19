<?php

namespace App\Http\Controllers\Eleve;

use App\Http\Controllers\Controller;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StudentCourseController extends Controller
{
    private function student()
    {
        abort_unless(session()->has('utilisateur') && session('utilisateur.role') === 'eleve', 403);
        $user = session('utilisateur');
        $eleve = DB::table('eleves')->where('email', $user['email'])->first();
        abort_unless($eleve, 404, 'Profil eleve introuvable.');
        return $eleve;
    }

    public function portal()
    {
        $eleve = $this->student();
        $classe = DB::table('classes')->where('id', $eleve->id_classe)->first();

        $courses = DB::table('courses')
            ->where('statut', 'publie')
            ->orderByDesc('created_at')
            ->get();

        $favIds = DB::table('course_favoris')->where('eleve_id', $eleve->id)->pluck('course_id')->toArray();
        $progressions = DB::table('course_progression')
            ->join('course_chapitres', 'course_chapitres.id', '=', 'course_progression.chapitre_id')
            ->where('course_progression.eleve_id', $eleve->id)
            ->select('course_chapitres.course_id', DB::raw('COUNT(*) as total'), DB::raw('SUM(course_progression.termine) as done'))
            ->groupBy('course_chapitres.course_id')
            ->get()->keyBy('course_id');

        $exercicesRecents = DB::table('exercice_soumissions')
            ->where('eleve_id', $eleve->id)
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        return view('eleve.portal', compact('eleve', 'classe', 'courses', 'favIds', 'progressions', 'exercicesRecents'));
    }

    public function courses()
    {
        $eleve = $this->student();
        $search = request('search');

        $courses = DB::table('courses')
            ->where('statut', 'publie')
            ->when($search, fn ($q) => $q->where('titre', 'like', "%{$search}%"))
            ->orderByDesc('created_at')
            ->paginate(12);

        $favIds = DB::table('course_favoris')->where('eleve_id', $eleve->id)->pluck('course_id')->toArray();
        $matieres = DB::table('matieres')->orderBy('nom')->get();

        return view('eleve.courses', compact('courses', 'favIds', 'matieres', 'search'));
    }

    public function showCourse(int $id)
    {
        $eleve = $this->student();
        $course = DB::table('courses')->where('id', $id)->where('statut', 'publie')->firstOrFail();
        $matiere = DB::table('matieres')->where('id', $course->matiere_id)->first();

        $chapitres = DB::table('course_chapitres')
            ->where('course_id', $id)
            ->where('statut', 'publie')
            ->orderBy('ordre')
            ->get();

        foreach ($chapitres as $ch) {
            $ch->fichiers = DB::table('course_fichiers')->where('chapitre_id', $ch->id)->orderByDesc('created_at')->get();
            $prog = DB::table('course_progression')->where('eleve_id', $eleve->id)->where('chapitre_id', $ch->id)->first();
            $ch->termine = $prog && $prog->termine;
            $ch->score = $prog ? $prog->score : null;
        }

        $isFav = DB::table('course_favoris')->where('eleve_id', $eleve->id)->where('course_id', $id)->exists();

        return view('eleve.course-show', compact('course', 'matiere', 'chapitres', 'isFav'));
    }

    public function toggleFavori(int $courseId)
    {
        $eleve = $this->student();
        $existing = DB::table('course_favoris')->where('eleve_id', $eleve->id)->where('course_id', $courseId);
        if ($existing->exists()) {
            $existing->delete();
            return back()->with('success', 'Retire des favoris.');
        }
        DB::table('course_favoris')->insert(['eleve_id' => $eleve->id, 'course_id' => $courseId, 'created_at' => now(), 'updated_at' => now()]);
        return back()->with('success', 'Ajoute aux favoris.');
    }

    public function progresser(int $chapitreId)
    {
        $eleve = $this->student();
        $ch = DB::table('course_chapitres')->where('id', $chapitreId)->firstOrFail();

        DB::table('course_progression')->updateOrInsert(
            ['eleve_id' => $eleve->id, 'chapitre_id' => $chapitreId],
            ['termine' => true, 'completed_at' => now(), 'updated_at' => now()]
        );
        return back()->with('success', 'Chapitre marque comme termine.');
    }

    public function historique()
    {
        $eleve = $this->student();

        $submissions = DB::table('exercice_soumissions')
            ->join('exercices', 'exercices.id', '=', 'exercice_soumissions.exercice_id')
            ->where('exercice_soumissions.eleve_id', $eleve->id)
            ->select('exercice_soumissions.*', 'exercices.titre as exercice_titre')
            ->orderByDesc('exercice_soumissions.created_at')
            ->paginate(20);

        $progress = DB::table('course_progression')
            ->join('course_chapitres', 'course_chapitres.id', '=', 'course_progression.chapitre_id')
            ->join('courses', 'courses.id', '=', 'course_chapitres.course_id')
            ->where('course_progression.eleve_id', $eleve->id)
            ->select('courses.titre as course_titre', 'course_chapitres.titre as chapitre_titre', 'course_progression.*')
            ->orderByDesc('course_progression.created_at')
            ->get();

        return view('eleve.historique', compact('submissions', 'progress'));
    }

    public function rapport()
    {
        $eleve = $this->student();
        $userId = session('utilisateur.id');

        $totalCourses = DB::table('courses')->where('statut', 'publie')->count();
        $chapitreIds = DB::table('course_chapitres')->where('statut', 'publie')->pluck('id');
        $totalChapitres = $chapitreIds->count();
        $completedChapitres = DB::table('course_progression')
            ->where('eleve_id', $eleve->id)->where('termine', true)->count();

        $avgScore = DB::table('exercice_soumissions')
            ->where('eleve_id', $eleve->id)->avg('score') ?? 0;

        $parMatiere = DB::table('exercice_soumissions')
            ->join('exercices', 'exercices.id', '=', 'exercice_soumissions.exercice_id')
            ->join('course_chapitres', 'course_chapitres.id', '=', 'exercices.chapitre_id')
            ->join('courses', 'courses.id', '=', 'course_chapitres.course_id')
            ->join('matieres', 'matieres.id', '=', 'courses.matiere_id')
            ->where('exercice_soumissions.eleve_id', $eleve->id)
            ->select('matieres.nom as matiere', DB::raw('AVG(exercice_soumissions.score) as avg_score'), DB::raw('COUNT(*) as total'))
            ->groupBy('matieres.id', 'matieres.nom')
            ->get();

        $notes = DB::table('notes')->where('eleve_id', $eleve->id)->get();
        $moyenneNotes = $notes->avg('note') ?: 0;

        // Monthly evolution
        $evolution = DB::table('exercice_soumissions')
            ->where('eleve_id', $eleve->id)
            ->where('created_at', '>=', now()->subMonths(6))
            ->select(DB::raw("DATE_FORMAT(created_at, '%Y-%m') as mois"), DB::raw('AVG(score) as score_moyen'))
            ->groupBy('mois')
            ->orderBy('mois')
            ->get();

        return view('eleve.rapport', compact('eleve', 'totalCourses', 'totalChapitres', 'completedChapitres', 'avgScore', 'parMatiere', 'moyenneNotes', 'evolution'));
    }
}
