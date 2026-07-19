<?php

namespace App\Http\Controllers\Eleve;

use App\Http\Controllers\Controller;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExerciseController extends Controller
{
    private function student()
    {
        abort_unless(session()->has('utilisateur') && session('utilisateur.role') === 'eleve', 403);
        $user = session('utilisateur');
        $eleve = DB::table('eleves')->where('email', $user['email'])->first();
        abort_unless($eleve, 404);
        return $eleve;
    }

    public function list(int $chapitreId)
    {
        $this->student();
        $ch = DB::table('course_chapitres')->where('id', $chapitreId)->where('statut', 'publie')->firstOrFail();
        $course = DB::table('courses')->where('id', $ch->course_id)->where('statut', 'publie')->firstOrFail();

        $exercices = DB::table('exercices')->where('chapitre_id', $chapitreId)->where('publie', true)->get();
        $soumissions = DB::table('exercice_soumissions')
            ->whereIn('exercice_id', $exercices->pluck('id'))
            ->where('eleve_id', $this->student()->id)
            ->get()->keyBy('exercice_id');

        return view('eleve.exercices', compact('ch', 'course', 'exercices', 'soumissions'));
    }

    public function show(int $id)
    {
        $eleve = $this->student();
        $exercice = DB::table('exercices')->where('id', $id)->where('publie', true)->firstOrFail();
        $ch = DB::table('course_chapitres')->where('id', $exercice->chapitre_id)->firstOrFail();
        $course = DB::table('courses')->where('id', $ch->course_id)->firstOrFail();

        $existing = DB::table('exercice_soumissions')
            ->where('exercice_id', $id)->where('eleve_id', $eleve->id)
            ->orderByDesc('created_at')->first();

        if ($existing && $existing->termine_le) {
            return redirect()->route('eleve.exercices.result', $existing->id);
        }

        $questions = DB::table('exercice_questions')
            ->where('exercice_id', $id)
            ->orderBy('ordre')
            ->get();

        return view('eleve.exercice-show', compact('exercice', 'ch', 'course', 'questions'));
    }

    public function submit(Request $request, int $id)
    {
        $eleve = $this->student();
        $exercice = DB::table('exercices')->where('id', $id)->where('publie', true)->firstOrFail();

        $existing = DB::table('exercice_soumissions')
            ->where('exercice_id', $id)->where('eleve_id', $eleve->id)
            ->whereNotNull('termine_le')->exists();

        if ($existing) {
            return redirect()->route('eleve.exercices.list', $exercice->chapitre_id)->with('error', 'Exercice deja soumis.');
        }

        $questions = DB::table('exercice_questions')->where('exercice_id', $id)->orderBy('ordre')->get();
        $reponses = $request->input('reponses', []);
        $totalPoints = 0;
        $obtenus = 0;
        $details = [];

        foreach ($questions as $q) {
            $totalPoints += $q->points;
            $userAnswer = $reponses[$q->id] ?? '';
            $correct = strtolower(trim((string) $userAnswer)) === strtolower(trim($q->reponse_correcte));

            if ($q->options) {
                $opts = json_decode($q->options, true);
                if (is_array($opts)) {
                    $correct = is_array($userAnswer)
                        ? empty(array_diff($userAnswer, json_decode($q->reponse_correcte, true) ?? []))
                        : in_array($userAnswer, json_decode($q->reponse_correcte, true) ?? []);
                }
            }

            if ($correct) $obtenus += $q->points;
            $details[] = [
                'question_id' => $q->id,
                'user_answer' => $userAnswer,
                'correct' => $correct,
                'points' => $correct ? $q->points : 0,
            ];
        }

        $start = $request->input('started_at');
        $temps = $start ? max(1, now()->diffInRealSeconds(\Carbon\Carbon::parse($start))) : null;

        DB::table('exercice_soumissions')->insert([
            'exercice_id' => $id,
            'eleve_id' => $eleve->id,
            'reponses' => json_encode($details, JSON_UNESCAPED_UNICODE),
            'score' => $totalPoints > 0 ? round(($obtenus / $totalPoints) * 20, 2) : 0,
            'temps_realise' => $temps,
            'termine_le' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        ActivityLogger::log('exercice.submit', 'exercices', 'exercices', $id, ['score' => $obtenus . '/' . $totalPoints]);
        return redirect()->route('eleve.exercices.list', $exercice->chapitre_id)->with('success', 'Exercice soumis ! Score: ' . $obtenus . '/' . $totalPoints);
    }

    public function result(int $id)
    {
        $eleve = $this->student();
        $soumission = DB::table('exercice_soumissions')->where('id', $id)->where('eleve_id', $eleve->id)->firstOrFail();
        $exercice = DB::table('exercices')->where('id', $soumission->exercice_id)->firstOrFail();
        $questions = DB::table('exercice_questions')->where('exercice_id', $soumission->exercice_id)->orderBy('ordre')->get();
        $reponses = json_decode($soumission->reponses, true);
        $ch = DB::table('course_chapitres')->where('id', $exercice->chapitre_id)->first();

        return view('eleve.exercice-result', compact('soumission', 'exercice', 'questions', 'reponses', 'ch'));
    }
}
