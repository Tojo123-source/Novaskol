<?php

namespace App\Http\Controllers\Eleve;

use App\Http\Controllers\Controller;
use App\Services\Novaskol\ModuleRegistry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StudentPortalController extends Controller
{
    public function __invoke(Request $request, ModuleRegistry $modules)
    {
        $user = session('utilisateur');
        abort_unless($user && ($user['role'] ?? '') === 'eleve', 403);

        $eleve = DB::table('eleves')
            ->leftJoin('classes', 'classes.id', '=', 'eleves.id_classe')
            ->where('eleves.email', $user['email'])
            ->select('eleves.*', 'classes.nom as classe_nom')
            ->first();
        abort_unless($eleve, 404);

        $month = (int) $request->query('mois', now()->month);
        $year = (int) $request->query('annee', now()->year);

        $notes = $this->notes((int) $eleve->id);
        $courses = DB::table('courses')->where('statut', 'publie')->orderByDesc('created_at')->limit(6)->get();
        $progressions = DB::table('course_progression')
            ->join('course_chapitres', 'course_chapitres.id', '=', 'course_progression.chapitre_id')
            ->where('course_progression.eleve_id', $eleve->id)
            ->select('course_chapitres.course_id', DB::raw('COUNT(*) as total'), DB::raw('SUM(course_progression.termine) as done'))
            ->groupBy('course_chapitres.course_id')
            ->get()->keyBy('course_id');
        $favIds = DB::table('course_favoris')->where('eleve_id', $eleve->id)->pluck('course_id')->toArray();

        return view('eleve.portal', [
            'activeModule' => 'eleve_portal',
            'modules' => $modules->all(),
            'userPermissions' => [],
            'ecole' => $this->school(),
            'user' => $user,
            'eleve' => $eleve,
            'notes' => $notes,
            'courses' => $courses,
            'progressions' => $progressions,
            'favIds' => $favIds,
            'calMonth' => $month,
            'calYear' => $year,
            'attendance' => $this->attendanceCalendar((int) $eleve->id, $eleve->id_classe, $month, $year),
            'notifications' => DB::table('notifications')
                ->where(function ($n) use ($user) {
                    $n->whereNull('destinataire_id')->orWhere('destinataire_id', (int) $user['id']);
                })
                ->orderByDesc('date_creation')
                ->limit(5)
                ->get(),
        ]);
    }

    private function notes(int $studentId)
    {
        $matJoin = DB::getSchemaBuilder()->hasColumn('notes', 'id_matiere') ? 'n.id_matiere' : 'n.matiere_id';
        $stuJoin = DB::getSchemaBuilder()->hasColumn('notes', 'id_eleve') ? 'n.id_eleve' : 'n.eleve_id';

        return DB::table('notes as n')
            ->leftJoin('matieres as m', 'm.id', '=', $matJoin)
            ->select(
                'n.periode',
                'm.nom as matiere',
                DB::raw('COALESCE(n.note, n.valeur) as note'),
                'n.coefficient',
                'n.annee_scolaire'
            )
            ->where($stuJoin, $studentId)
            ->orderByDesc('n.annee_scolaire')
            ->orderBy('n.periode')
            ->orderBy('m.nom')
            ->limit(80)
            ->get();
    }

    private function attendanceCalendar(int $studentId, ?int $classeId, int $month, int $year): array
    {
        $records = DB::table('presence_eleves')
            ->where('eleve_id', $studentId)
            ->whereYear('date_jour', $year)
            ->whereMonth('date_jour', $month)
            ->select('date_jour', 'session_jour', 'statut', 'type_scan', 'commentaire', 'scan_mode')
            ->orderBy('date_jour')
            ->orderBy('session_jour')
            ->get();

        $dayNames = ['monday' => 'lundi', 'tuesday' => 'mardi', 'wednesday' => 'mercredi', 'thursday' => 'jeudi', 'friday' => 'vendredi', 'saturday' => 'samedi'];

        $days = [];
        $first = \Carbon\Carbon::create($year, $month, 1);
        $daysInMonth = $first->daysInMonth;

        for ($d = 1; $d <= $daysInMonth; $d++) {
            $date = \Carbon\Carbon::create($year, $month, $d);
            $dayEnglish = strtolower($date->format('l'));
            $dayFr = $dayNames[$dayEnglish] ?? '';

            $edt = collect();
            if ($classeId && $dayFr) {
                $edt = DB::table('emploi_du_temps as e')
                    ->leftJoin('matieres as m', 'e.matiere_id', '=', 'm.id')
                    ->where('e.classe_id', $classeId)
                    ->where('e.jour', $dayFr)
                    ->orderBy('e.heure_debut')
                    ->get(['e.heure_debut', 'e.heure_fin', 'm.nom as matiere_nom']);
            }

            $dayRecords = $records->filter(fn ($r) => (int) \Carbon\Carbon::parse($r->date_jour)->format('d') === $d);

            $mappedEdt = $edt->map(fn ($s) => [
                'heure' => substr($s->heure_debut, 0, 5) . '-' . substr($s->heure_fin, 0, 5),
                'matiere' => $s->matiere_nom ?? '',
            ])->values()->all();

            if ($dayRecords->isNotEmpty()) {
                $status = $dayRecords->first()->statut;
                foreach ($dayRecords as $r) {
                    if ($r->statut === 'retard') $status = 'retard';
                }
                $details = [];
                foreach ($dayRecords as $r) {
                    $details[] = [
                        'session' => $r->session_jour,
                        'type_scan' => $r->type_scan,
                        'statut' => $r->statut,
                        'commentaire' => $r->commentaire,
                        'scan_mode' => $r->scan_mode,
                        'heure' => null,
                    ];
                }
                $days[$d] = ['status' => $status, 'details' => $details, 'edt' => $mappedEdt];
            } else {
                $days[$d] = ['status' => null, 'details' => [], 'edt' => $mappedEdt];
            }
        }

        return $days;
    }

    public function chat(Request $request, ModuleRegistry $modules)
    {
        $user = session('utilisateur');
        abort_unless($user && ($user['role'] ?? '') === 'eleve', 403);

        $eleve = DB::table('eleves')->where('email', $user['email'])->first();
        abort_unless($eleve, 404);

        $classe = DB::table('classes')->where('id', $eleve->id_classe)->first();

        $with = $request->query('with');
        $id = (int) $request->query('id', 0);
        $contact = null;
        $contactType = null;
        $conversationId = 0;

        if ($with && $id) {
            if ($with === 'classmate') {
                $contact = DB::table('eleves')->where('id', $id)->first();
                $contactType = 'classmate';
                if ($contact) {
                    $conversationId = $this->studentConversation((int) $eleve->id, (int) $contact->id);
                }
            } elseif ($with === 'teacher') {
                $contact = DB::table('professeurs')->where('id', $id)->first();
                $contactType = 'teacher';
                if ($contact) {
                    $teacherUser = DB::table('utilisateurs')->where('email', $contact->email)->first();
                    if ($teacherUser) {
                        $conversationId = $this->privateConversationWith((int) $teacherUser->id);
                    }
                }
            } elseif ($with === 'admin') {
                $contact = DB::table('utilisateurs')->where('id', $id)->first();
                $contactType = 'admin';
                if ($contact) {
                    $conversationId = $this->privateConversationWith((int) $contact->id);
                }
            }
        }

        $classmates = DB::table('eleves')
            ->where('id_classe', $eleve->id_classe)
            ->where('id', '!=', $eleve->id)
            ->orderBy('nom')->orderBy('prenom')
            ->get();

        $teacherIds = DB::table('professeurs_classes')->where('classe_id', $eleve->id_classe)->pluck('professeur_id');
        $teachers = DB::table('professeurs')
            ->whereIn('id', $teacherIds)
            ->orderBy('nom')->orderBy('prenom')
            ->get();

        $admins = DB::table('utilisateurs')->where('role', 'admin')->orderBy('nom')->get();

        return view('eleve.chat', [
            'activeModule' => 'eleve_chat',
            'modules' => $modules->all(),
            'userPermissions' => [],
            'ecole' => $this->school(),
            'user' => $user,
            'eleve' => $eleve,
            'classe' => $classe,
            'classmates' => $classmates,
            'teachers' => $teachers,
            'admins' => $admins,
            'contact' => $contact,
            'contactType' => $contactType,
            'conversationId' => $conversationId,
        ]);
    }

    public function anonymous(Request $request)
    {
        $user = session('utilisateur');
        abort_unless($user && ($user['role'] ?? '') === 'eleve', 403);

        $eleve = DB::table('eleves')->where('email', $user['email'])->first();
        abort_unless($eleve, 404);

        if ($request->isMethod('POST')) {
            $data = $request->validate([
                'type' => 'required|string|max:50',
                'message' => 'required|string|max:5000',
            ]);

            DB::table('signalements')->insert([
                'eleve_id' => $eleve->id,
                'type' => $data['type'],
                'message' => $data['message'],
                'anonyme' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return redirect()->route('eleve.portal.anonymous')->with('success', 'Votre message a ete envoye de maniere anonyme.');
        }

        $signalements = DB::table('signalements')
            ->where('eleve_id', $eleve->id)
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        return view('eleve.anonymous', [
            'ecole' => $this->school(),
            'user' => $user,
            'eleve' => $eleve,
            'signalements' => $signalements,
        ]);
    }

    private function studentConversation(int $studentId1, int $studentId2): int
    {
        $conv = DB::table('conversations as c')
            ->join('conversation_participants as a', function ($j) use ($studentId1) {
                $j->on('a.conversation_id', '=', 'c.id')->where('a.user_id', '=', $studentId1)->where('a.user_type', '=', 'eleve');
            })
            ->join('conversation_participants as b', function ($j) use ($studentId2) {
                $j->on('b.conversation_id', '=', 'c.id')->where('b.user_id', '=', $studentId2)->where('b.user_type', '=', 'eleve');
            })
            ->where('c.type', 'private')
            ->select('c.id')
            ->first();

        if ($conv) {
            return (int) $conv->id;
        }

        return DB::transaction(function () use ($studentId1, $studentId2) {
            $id = DB::table('conversations')->insertGetId([
                'type' => 'private', 'name' => null,
                'created_at' => now(), 'updated_at' => now(),
            ]);
            DB::table('conversation_participants')->insert([
                ['conversation_id' => $id, 'user_type' => 'eleve', 'user_id' => $studentId1, 'joined_at' => now()],
                ['conversation_id' => $id, 'user_type' => 'eleve', 'user_id' => $studentId2, 'joined_at' => now()],
            ]);
            return $id;
        });
    }

    private function privateConversationWith(int $recipientId): int
    {
        if ($recipientId === (int) session('utilisateur.id')) {
            return 0;
        }

        $conv = DB::table('conversations as c')
            ->join('conversation_participants as a', function ($j) {
                $j->on('a.conversation_id', '=', 'c.id')->where('a.user_id', '=', session('utilisateur.id'))->where('a.user_type', '=', 'eleve');
            })
            ->join('conversation_participants as b', function ($j) use ($recipientId) {
                $recipientRole = DB::table('utilisateurs')->where('id', $recipientId)->value('role') ?: 'staff';
                $j->on('b.conversation_id', '=', 'c.id')->where('b.user_id', '=', $recipientId)->where('b.user_type', '=', $recipientRole);
            })
            ->where('c.type', 'private')
            ->select('c.id')
            ->first();

        if ($conv) {
            return (int) $conv->id;
        }

        $recipientRole = DB::table('utilisateurs')->where('id', $recipientId)->value('role') ?: 'staff';

        return DB::transaction(function () use ($recipientId, $recipientRole) {
            $id = DB::table('conversations')->insertGetId([
                'type' => 'private', 'name' => null,
                'created_at' => now(), 'updated_at' => now(),
            ]);
            DB::table('conversation_participants')->insert([
                ['conversation_id' => $id, 'user_type' => 'eleve', 'user_id' => session('utilisateur.id'), 'joined_at' => now()],
                ['conversation_id' => $id, 'user_type' => $recipientRole, 'user_id' => $recipientId, 'joined_at' => now()],
            ]);
            return $id;
        });
    }

    private function school(): object
    {
        return DB::table('ecole')->select('nom', 'logo')->first() ?: (object) [
            'nom' => 'Ecole',
            'logo' => 'novaskol.png',
        ];
    }
}
