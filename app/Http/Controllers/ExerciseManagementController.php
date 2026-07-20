<?php

namespace App\Http\Controllers;

use App\Services\ActivityLogger;
use App\Services\Novaskol\ModuleRegistry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExerciseManagementController extends Controller
{
    private function ensureTeacher(): array
    {
        $u = session('utilisateur');
        abort_unless($u && in_array($u['role'] ?? '', ['admin', 'enseignant'], true), 403);
        return $u;
    }

    public function index(Request $request, ModuleRegistry $modules)
    {
        $user = $this->ensureTeacher();
        $courseId = (int) $request->query('course_id', 0);

        $courses = DB::table('courses')->where('enseignant_id', $user['id'])->orderByDesc('created_at')->get();
        $exercices = collect();

        if ($courseId) {
            $chIds = DB::table('course_chapitres')->where('course_id', $courseId)->pluck('id');
            $exercices = DB::table('exercices')
                ->join('course_chapitres', 'course_chapitres.id', '=', 'exercices.chapitre_id')
                ->whereIn('exercices.chapitre_id', $chIds)
                ->select('exercices.*', 'course_chapitres.titre as chapitre_titre')
                ->orderByDesc('exercices.created_at')
                ->get();

            foreach ($exercices as $ex) {
                $ex->questions_count = DB::table('exercice_questions')->where('exercice_id', $ex->id)->count();
                $ex->soumissions_count = DB::table('exercice_soumissions')->where('exercice_id', $ex->id)->count();
            }
        }

        return view('teacher.exercices.index', compact('courses', 'exercices', 'courseId') + [
            'modules' => $modules->all(),
            'ecole' => DB::table('ecole')->select('nom', 'logo')->first() ?: (object) ['nom' => 'Ecole', 'logo' => 'novaskol.png'],
            'userPermissions' => [],
            'activeModule' => 'teacher_exercices',
        ]);
    }

    public function create(Request $request, int $chapitreId, ModuleRegistry $modules)
    {
        $this->ensureTeacher();
        $ch = DB::table('course_chapitres')->where('id', $chapitreId)->firstOrFail();
        $course = DB::table('courses')->where('id', $ch->course_id)->firstOrFail();

        return view('teacher.exercices.create', compact('ch', 'course') + [
            'modules' => $modules->all(),
            'ecole' => DB::table('ecole')->select('nom', 'logo')->first() ?: (object) ['nom' => 'Ecole', 'logo' => 'novaskol.png'],
            'userPermissions' => [],
            'activeModule' => 'teacher_exercices',
        ]);
    }

    public function store(Request $request)
    {
        $this->ensureTeacher();
        $data = $request->validate([
            'chapitre_id' => 'required|integer|exists:course_chapitres,id',
            'titre' => 'required|string|max:200',
            'description' => 'nullable|string|max:5000',
            'type' => 'required|in:qcm,vrai_faux,texte,appariement',
            'temps_limite' => 'nullable|integer|min:0|max:3600',
            'questions' => 'required|array|min:1',
            'questions.*.question' => 'required|string',
            'questions.*.options' => 'nullable|array',
            'questions.*.reponse_correcte' => 'required|string',
            'questions.*.points' => 'required|integer|min:1|max:100',
        ]);

        $exId = DB::table('exercices')->insertGetId([
            'chapitre_id' => $data['chapitre_id'],
            'titre' => $data['titre'],
            'description' => $data['description'] ?? '',
            'type' => $data['type'],
            'temps_limite' => $data['temps_limite'],
            'publie' => $request->boolean('publie'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        foreach ($data['questions'] as $i => $q) {
            DB::table('exercice_questions')->insert([
                'exercice_id' => $exId,
                'question' => $q['question'],
                'options' => isset($q['options']) ? json_encode($q['options'], JSON_UNESCAPED_UNICODE) : null,
                'reponse_correcte' => $q['reponse_correcte'],
                'points' => $q['points'],
                'ordre' => $i,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        ActivityLogger::log('exercice.create', 'exercices', 'exercices', $exId, ['titre' => $data['titre']]);
        return redirect()->route('teacher.exercices.index', ['course_id' => $this->getCourseFromChapitre($data['chapitre_id'])])
            ->with('success', 'Exercice cree avec ' . count($data['questions']) . ' questions.');
    }

    public function edit(int $id, ModuleRegistry $modules)
    {
        $this->ensureTeacher();
        $exercice = DB::table('exercices')->where('id', $id)->firstOrFail();
        $ch = DB::table('course_chapitres')->where('id', $exercice->chapitre_id)->firstOrFail();
        $course = DB::table('courses')->where('id', $ch->course_id)->firstOrFail();
        $questions = DB::table('exercice_questions')->where('exercice_id', $id)->orderBy('ordre')->get();

        return view('teacher.exercices.edit', compact('exercice', 'ch', 'course', 'questions') + [
            'modules' => $modules->all(),
            'ecole' => DB::table('ecole')->select('nom', 'logo')->first() ?: (object) ['nom' => 'Ecole', 'logo' => 'novaskol.png'],
            'userPermissions' => [],
            'activeModule' => 'teacher_exercices',
        ]);
    }

    public function update(Request $request, int $id)
    {
        $this->ensureTeacher();
        $exercice = DB::table('exercices')->where('id', $id)->firstOrFail();

        $data = $request->validate([
            'titre' => 'required|string|max:200',
            'description' => 'nullable|string|max:5000',
            'type' => 'required|in:qcm,vrai_faux,texte,appariement',
            'temps_limite' => 'nullable|integer|min:0|max:3600',
            'publie' => 'nullable|boolean',
        ]);

        DB::table('exercices')->where('id', $id)->update([
            'titre' => $data['titre'],
            'description' => $data['description'] ?? '',
            'type' => $data['type'],
            'temps_limite' => $data['temps_limite'],
            'publie' => $request->boolean('publie'),
            'updated_at' => now(),
        ]);

        if ($request->has('questions')) {
            $questions = $request->input('questions', []);
            DB::table('exercice_questions')->where('exercice_id', $id)->delete();
            foreach ($questions as $i => $q) {
                DB::table('exercice_questions')->insert([
                    'exercice_id' => $id,
                    'question' => $q['question'],
                    'options' => isset($q['options']) ? json_encode($q['options'], JSON_UNESCAPED_UNICODE) : null,
                    'reponse_correcte' => $q['reponse_correcte'],
                    'points' => $q['points'],
                    'ordre' => $i,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        ActivityLogger::log('exercice.update', 'exercices', 'exercices', $id);
        return redirect()->route('teacher.exercices.index', ['course_id' => $this->getCourseFromChapitre($exercice->chapitre_id)])
            ->with('success', 'Exercice mis a jour.');
    }

    public function destroy(int $id)
    {
        $this->ensureTeacher();
        $exercice = DB::table('exercices')->where('id', $id)->firstOrFail();
        DB::table('exercice_soumissions')->where('exercice_id', $id)->delete();
        DB::table('exercice_questions')->where('exercice_id', $id)->delete();
        DB::table('exercices')->where('id', $id)->delete();
        ActivityLogger::log('exercice.delete', 'exercices', 'exercices', $id);
        return redirect()->route('teacher.exercices.index', ['course_id' => $this->getCourseFromChapitre($exercice->chapitre_id)])
            ->with('success', 'Exercice supprime.');
    }

    private function getCourseFromChapitre(int $chapitreId): int
    {
        return (int) DB::table('course_chapitres')->where('id', $chapitreId)->value('course_id');
    }
}
