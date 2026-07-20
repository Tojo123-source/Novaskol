<?php

namespace App\Http\Controllers;

use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class CourseController extends Controller
{
    private function ensureTeacher(): array
    {
        $u = session('utilisateur');
        abort_unless($u && in_array($u['role'] ?? '', ['admin', 'enseignant'], true), 403);
        return $u;
    }

    public function index(Request $request)
    {
        $user = $this->ensureTeacher();
        $matiereId = (int) $request->query('matiere_id', 0);

        $courses = DB::table('courses')
            ->where('enseignant_id', $user['id'])
            ->when($matiereId > 0, fn ($q) => $q->where('matiere_id', $matiereId))
            ->orderByDesc('created_at')
            ->get();
        $matieres = DB::table('matieres')->orderBy('nom')->get();

        return view('teacher.courses.index', compact('courses', 'matieres', 'matiereId'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'titre' => 'required|string|max:200',
            'description' => 'nullable|string|max:5000',
            'matiere_id' => 'nullable|integer|exists:matieres,id',
            'niveau' => 'nullable|string|max:50',
        ]);
        $user = $this->ensureTeacher();

        $id = DB::table('courses')->insertGetId([
            'enseignant_id' => $user['id'],
            'matiere_id' => $data['matiere_id'],
            'titre' => $data['titre'],
            'description' => $data['description'] ?? '',
            'niveau' => $data['niveau'], 'statut' => 'brouillon',
            'created_at' => now(), 'updated_at' => now(),
        ]);
        ActivityLogger::log('course.create', 'courses', 'courses', $id, ['titre' => $data['titre']]);
        return redirect()->route('teacher.courses.show', $id)->with('success', 'Cours cree.');
    }

    public function show(int $id)
    {
        $user = $this->ensureTeacher();
        $course = DB::table('courses')->where('id', $id)->where('enseignant_id', $user['id'])->firstOrFail();
        $chapitres = DB::table('course_chapitres')->where('course_id', $id)->orderBy('ordre')->get();

        foreach ($chapitres as $ch) {
            $ch->fichiers = DB::table('course_fichiers')->where('chapitre_id', $ch->id)->orderByDesc('created_at')->get();
        }
        $matieres = DB::table('matieres')->orderBy('nom')->get();

        return view('teacher.courses.show', compact('course', 'chapitres', 'matieres'));
    }

    public function update(Request $request, int $id)
    {
        $user = $this->ensureTeacher();
        $course = DB::table('courses')->where('id', $id)->where('enseignant_id', $user['id'])->firstOrFail();

        $data = $request->validate([
            'titre' => 'required|string|max:200',
            'description' => 'nullable|string|max:5000',
            'matiere_id' => 'nullable|integer|exists:matieres,id',
            'niveau' => 'nullable|string|max:50',
            'statut' => 'required|in:brouillon,publie,archive',
        ]);
        DB::table('courses')->where('id', $id)->update([
            'titre' => $data['titre'], 'description' => $data['description'] ?? '',
            'matiere_id' => $data['matiere_id'], 'niveau' => $data['niveau'],
            'statut' => $data['statut'], 'updated_at' => now(),
        ]);
        ActivityLogger::log('course.update', 'courses', 'courses', $id, ['titre' => $data['titre']]);
        return redirect()->route('teacher.courses.show', $id)->with('success', 'Cours mis a jour.');
    }

    public function destroy(int $id)
    {
        $user = $this->ensureTeacher();
        $course = DB::table('courses')->where('id', $id)->where('enseignant_id', $user['id'])->firstOrFail();
        $chIds = DB::table('course_chapitres')->where('course_id', $id)->pluck('id');

        foreach (DB::table('course_fichiers')->whereIn('chapitre_id', $chIds)->get() as $f) {
            $p = storage_path('app/' . $f->fichier_path);
            if (File::exists($p)) File::delete($p);
        }
        DB::table('course_fichiers')->whereIn('chapitre_id', $chIds)->delete();
        DB::table('course_progression')->whereIn('chapitre_id', $chIds)->delete();
        DB::table('course_favoris')->where('course_id', $id)->delete();
        DB::table('course_chapitres')->where('course_id', $id)->delete();
        DB::table('courses')->where('id', $id)->delete();

        ActivityLogger::log('course.delete', 'courses', 'courses', $id);
        return redirect()->route('teacher.courses.index')->with('success', 'Cours supprime.');
    }

    // --- CHAPTERS ---
    public function storeChapitre(Request $request, int $courseId)
    {
        $user = $this->ensureTeacher();
        DB::table('courses')->where('id', $courseId)->where('enseignant_id', $user['id'])->firstOrFail();

        $data = $request->validate([
            'titre' => 'required|string|max:200',
            'description' => 'nullable|string|max:5000',
        ]);
        $max = DB::table('course_chapitres')->where('course_id', $courseId)->max('ordre') ?? 0;
        $id = DB::table('course_chapitres')->insertGetId([
            'course_id' => $courseId, 'titre' => $data['titre'],
            'description' => $data['description'] ?? '', 'ordre' => $max + 1,
            'created_at' => now(), 'updated_at' => now(),
        ]);
        ActivityLogger::log('chapitre.create', 'courses', 'course_chapitres', $id);
        return redirect()->route('teacher.courses.show', $courseId)->with('success', 'Chapitre ajoute.');
    }

    public function updateChapitre(Request $request, int $id)
    {
        $ch = DB::table('course_chapitres')->where('id', $id)->firstOrFail();
        $user = $this->ensureTeacher();
        DB::table('courses')->where('id', $ch->course_id)->where('enseignant_id', $user['id'])->firstOrFail();

        $data = $request->validate([
            'titre' => 'required|string|max:200',
            'description' => 'nullable|string|max:5000',
            'statut' => 'required|in:publie,masque',
        ]);
        DB::table('course_chapitres')->where('id', $id)->update([
            'titre' => $data['titre'], 'description' => $data['description'] ?? '',
            'statut' => $data['statut'], 'updated_at' => now(),
        ]);
        ActivityLogger::log('chapitre.update', 'courses', 'course_chapitres', $id);
        return redirect()->route('teacher.courses.show', $ch->course_id)->with('success', 'Chapitre mis a jour.');
    }

    public function destroyChapitre(int $id)
    {
        $ch = DB::table('course_chapitres')->where('id', $id)->firstOrFail();
        $user = $this->ensureTeacher();
        DB::table('courses')->where('id', $ch->course_id)->where('enseignant_id', $user['id'])->firstOrFail();

        foreach (DB::table('course_fichiers')->where('chapitre_id', $id)->get() as $f) {
            $p = storage_path('app/' . $f->fichier_path);
            if (File::exists($p)) File::delete($p);
        }
        DB::table('course_fichiers')->where('chapitre_id', $id)->delete();
        DB::table('course_progression')->where('chapitre_id', $id)->delete();
        DB::table('course_chapitres')->where('id', $id)->delete();

        ActivityLogger::log('chapitre.delete', 'courses', 'course_chapitres', $id);
        return redirect()->route('teacher.courses.show', $ch->course_id)->with('success', 'Chapitre supprime.');
    }

    // --- FILES ---
    public function storeFichier(Request $request, int $chapitreId)
    {
        $ch = DB::table('course_chapitres')->where('id', $chapitreId)->firstOrFail();
        $user = $this->ensureTeacher();
        DB::table('courses')->where('id', $ch->course_id)->where('enseignant_id', $user['id'])->firstOrFail();

        $request->validate(['fichier' => 'required|file|max:102400']);
        $file = $request->file('fichier');
        $ext = strtolower($file->getClientOriginalExtension());
        $map = ['pdf' => 'pdf', 'mp4' => 'video', 'webm' => 'video', 'doc' => 'document', 'docx' => 'document', 'jpg' => 'image', 'jpeg' => 'image', 'png' => 'image', 'gif' => 'image', 'mp3' => 'audio'];
        $type = $map[$ext] ?? 'other';
        $path = $file->store('course-files/' . $chapitreId, 'local');

        DB::table('course_fichiers')->insert([
            'chapitre_id' => $chapitreId, 'type' => $type,
            'nom_original' => $file->getClientOriginalName(), 'fichier_path' => $path,
            'mime_type' => $file->getMimeType(), 'taille' => $file->getSize(),
            'created_at' => now(), 'updated_at' => now(),
        ]);
        return redirect()->route('teacher.courses.show', $ch->course_id)->with('success', 'Fichier ajoute.');
    }

    public function destroyFichier(int $id)
    {
        $f = DB::table('course_fichiers')->where('id', $id)->firstOrFail();
        $ch = DB::table('course_chapitres')->where('id', $f->chapitre_id)->firstOrFail();
        $user = $this->ensureTeacher();
        DB::table('courses')->where('id', $ch->course_id)->where('enseignant_id', $user['id'])->firstOrFail();

        $p = storage_path('app/' . $f->fichier_path);
        if (File::exists($p)) File::delete($p);
        DB::table('course_fichiers')->where('id', $id)->delete();
        return redirect()->route('teacher.courses.show', $ch->course_id)->with('success', 'Fichier supprime.');
    }

    // --- SESSIONS ---
    public function storeSession(Request $request)
    {
        $data = $request->validate([
            'titre' => 'required|string|max:200',
            'description' => 'nullable|string|max:5000',
            'classe_id' => 'required|integer|exists:classes,id',
            'matiere_id' => 'required|integer|exists:matieres,id',
            'session_date' => 'required|date',
            'course_id' => 'nullable|integer|exists:courses,id',
        ]);
        $user = $this->ensureTeacher();
        $id = DB::table('teacher_lessons')->insertGetId([
            'professeur_id' => $this->getProfesseurId($user),
            'classe_id' => $data['classe_id'], 'matiere_id' => $data['matiere_id'],
            'course_id' => $data['course_id'], 'titre' => $data['titre'],
            'annee_scolaire' => now()->format('Y') . '-' . (now()->year + 1),
            'statut' => 'planifie', 'is_online_session' => true,
            'session_date' => $data['session_date'],
            'session_invitation' => bin2hex(random_bytes(16)),
            'created_at' => now(), 'updated_at' => now(),
        ]);
        ActivityLogger::log('session.create', 'cours_en_ligne', 'teacher_lessons', $id);
        return redirect()->route('teacher.courses.index')->with('success', 'Seance planifiee.');
    }

    private function getProfesseurId(array $user): int
    {
        $p = DB::table('professeurs')->where('email', $user['email'])->first();
        return $p ? (int) $p->id : (int) $user['id'];
    }

    public function downloadFichier(int $id)
    {
        $f = DB::table('course_fichiers')->where('id', $id)->firstOrFail();
        $path = storage_path('app/' . $f->fichier_path);
        if (!File::exists($path)) abort(404);
        return response()->download($path, $f->nom_original);
    }
}
