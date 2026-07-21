<?php

namespace App\Http\Controllers;

use App\Services\Novaskol\ModuleRegistry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class RoleDashboardController extends Controller
{
    public function __invoke(Request $request, ModuleRegistry $modules)
    {
        $user = session('utilisateur');
        if (! $user) {
            return redirect()->route('login');
        }

        $role = $user['role'] ?? '';

        if ($role === 'admin') {
            return redirect()->route('dashboard');
        }

        $permissions = $this->permissions((int) $user['id']);
        $eleveModules = function () {
            return collect([
                ['key' => 'eleve_portal', 'label' => 'Mon espace', 'icon' => 'fa-th-large', 'route' => 'eleve.portal', 'access' => 'lecture'],
                ['key' => 'eleve_chat', 'label' => 'Messagerie', 'icon' => 'fa-comments', 'route' => 'eleve.portal.chat', 'access' => 'lecture'],
                ['key' => 'eleve_rapport', 'label' => 'Mon rapport', 'icon' => 'fa-chart-line', 'route' => 'eleve.rapport', 'access' => 'lecture'],
                ['key' => 'eleve_courses', 'label' => 'Bibliotheque', 'icon' => 'fa-book', 'route' => 'eleve.courses', 'access' => 'lecture'],
                ['key' => 'eleve_historique', 'label' => 'Historique', 'icon' => 'fa-history', 'route' => 'eleve.historique', 'access' => 'lecture'],
            ]);
        };

        $availableModules = ($role === 'eleve')
            ? $eleveModules()
            : collect($modules->all())
                ->filter(fn (array $module, string $key) => ! empty($module['icon']) && in_array($permissions[$key] ?? null, ['lecture', 'ecriture'], true))
                ->map(fn (array $module, string $key) => [
                    'key' => $key,
                    'label' => $module['label'] ?? $key,
                    'icon' => $module['icon'] ?? 'fa-circle',
                    'route' => $module['route'] ?? null,
                    'access' => $permissions[$key] ?? 'lecture',
                ])
                ->values();

        $ecole = $this->school();
        $calMonth = (int) $request->query('mois', now()->month);
        $calYear = (int) $request->query('annee', now()->year);

        $connectedMode = env('CONNECTED_MODE') === 'true'
            || config('novaskol.edition', 'principal') === 'connecte'
            || File::exists(env('CONNECTED_PAIRED_PATH', storage_path('app/connected/paired.json')));
        $serverUrl = env('CONNECTED_SERVER_URL', '');

        return view('dashboard.role', [
            'activeModule' => '',
            'modules' => $modules->all(),
            'user' => $user,
            'userPermissions' => $permissions,
            'ecole' => $ecole,
            'connectedMode' => $connectedMode,
            'isConnected' => $connectedMode,
            'serverUrl' => $serverUrl,
            'availableModules' => $availableModules,
            'stats' => $this->statsFor($user),
            'teacherWorkspace' => $role === 'enseignant' ? $this->teacherWorkspaceSummary($user) : null,
            'parentPortal' => $role === 'parent' ? $this->parentPortalSummary((int) $user['id']) : null,
            'cardData' => match ($role) {
                'enseignant' => $this->teacherCardData($user, $ecole),
                'staff' => $this->staffCardData($user, $ecole),
                'parent' => $this->parentChildrenCards((int) $user['id'], $ecole),
                'eleve' => $this->studentCardData($user, $ecole),
                default => null,
            },
            'calMonth' => $calMonth,
            'calYear' => $calYear,
            'staffAttendance' => $role === 'staff' ? $this->staffAttendanceCalendar((int) $user['id'], $calMonth, $calYear) : [],
            'latestNotifications' => DB::table('notifications')
                ->when(DB::getSchemaBuilder()->hasColumn('notifications', 'destinataire_id'), fn ($q) => $q->where(function ($n) use ($user) {
                    if (($user['role'] ?? '') === 'parent') {
                        $n->where('destinataire_id', (int) $user['id']);
                    } else {
                        $n->whereNull('destinataire_id')->orWhere('destinataire_id', (int) $user['id']);
                    }
                }))
                ->orderByDesc('date_creation')
                ->limit(5)
                ->get(),
        ]);
    }

    private function permissions(int $userId): array
    {
        $role = session('utilisateur.role');
        if ($role === 'eleve') {
            return [];
        }

        return DB::table('permissions')
            ->where('utilisateur_id', $userId)
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

    private function statsFor(array $user): array
    {
        $role = $user['role'] ?? '';
        $email = $user['email'] ?? '';
        $userId = (int) ($user['id'] ?? 0);

        $base = [
            ['label' => 'Notifications non lues', 'value' => $this->unreadNotifications($userId), 'icon' => 'fa-bell'],
            ['label' => 'Messages non lus', 'value' => $this->unreadMessages($userId), 'icon' => 'fa-comments'],
        ];

        if ($role === 'enseignant') {
            $teacher = DB::table('professeurs')->where('email', $email)->first();
            $teacherId = (int) ($teacher->id ?? 0);

            return array_merge([
                ['label' => 'Classes suivies', 'value' => $teacherId ? DB::table('professeurs_classes')->where('professeur_id', $teacherId)->count() : 0, 'icon' => 'fa-users'],
                ['label' => 'Matiere', 'value' => $teacher?->matiere_id ? (DB::table('matieres')->where('id', $teacher->matiere_id)->value('nom') ?: 'Aucune') : 'Aucune', 'icon' => 'fa-book'],
            ], $base);
        }

        if ($role === 'staff') {
            $staff = DB::table('staff')->where('email', $email)->first();

            return array_merge([
                ['label' => 'Poste', 'value' => $staff?->role_id ? (DB::table('roles')->where('id', $staff->role_id)->value('nom') ?: 'Staff') : 'Staff', 'icon' => 'fa-id-badge'],
                ['label' => 'Presence du mois', 'value' => $this->staffPresenceRate((int) ($staff->id ?? 0)).'%', 'icon' => 'fa-check-circle'],
            ], $base);
        }

        if ($role === 'parent') {
            $children = DB::table('parent_eleves')->where('parent_user_id', $userId)->count();

            return array_merge([
                ['label' => 'Enfants rattaches', 'value' => $children, 'icon' => 'fa-child'],
                ['label' => 'Espace', 'value' => 'Parent', 'icon' => 'fa-users'],
            ], $base);
        }

        if ($role === 'eleve') {
            $eleve = DB::table('eleves')->where('email', $user['email'] ?? '')->first();
            $courseCount = DB::table('courses')->where('statut', 'publie')->count();
            $completedChapitres = $eleve ? DB::table('course_progression')->where('eleve_id', $eleve->id)->where('termine', true)->count() : 0;

            return array_merge([
                ['label' => 'Cours disponibles', 'value' => $courseCount, 'icon' => 'fa-book'],
                ['label' => 'Chapitres termines', 'value' => $completedChapitres, 'icon' => 'fa-check-circle'],
                ['label' => 'Espace', 'value' => 'Eleve', 'icon' => 'fa-user'],
            ], $base);
        }

        return array_merge([
            ['label' => 'Modules ouverts', 'value' => DB::table('permissions')->where('utilisateur_id', $userId)->whereIn('acces', ['lecture', 'ecriture'])->count(), 'icon' => 'fa-th-large'],
            ['label' => 'Espace', 'value' => ucfirst($role ?: 'Utilisateur'), 'icon' => 'fa-user'],
        ], $base);
    }

    private function unreadNotifications(int $userId): int
    {
        $role = session('utilisateur.role');

        return DB::table('notifications')
            ->when(DB::getSchemaBuilder()->hasColumn('notifications', 'destinataire_id'), fn ($q) => $q->where(function ($n) use ($userId, $role) {
                if ($role === 'parent') {
                    $n->where('destinataire_id', $userId);
                } else {
                    $n->whereNull('destinataire_id')->orWhere('destinataire_id', $userId);
                }
            }))
            ->where(function ($q) {
                $q->where('lu', 0)->orWhere('statut', 'non lu');
            })
            ->count();
    }

    private function unreadMessages(int $userId): int
    {
        if ($userId <= 0 || ! DB::getSchemaBuilder()->hasTable('messages')) {
            return 0;
        }

        return DB::table('messages as m')
            ->join('conversation_participants as cp', 'cp.conversation_id', '=', 'm.conversation_id')
            ->where('cp.user_id', $userId)
            ->where('m.sender_id', '!=', $userId)
            ->where('m.is_read', 0)
            ->count();
    }

    private function staffPresenceRate(int $staffId): float
    {
        if ($staffId <= 0 || ! DB::getSchemaBuilder()->hasTable('presence_staff')) {
            return 0;
        }

        return round((float) DB::table('presence_staff')
            ->where('personne_id', $staffId)
            ->whereMonth('date_jour', now()->month)
            ->avg('presence') * 100, 1);
    }

    private function teacherWorkspaceSummary(array $user): ?array
    {
        $teacher = DB::table('professeurs')->where('email', $user['email'] ?? '')->first();
        if (! $teacher) {
            return null;
        }

        return [
            'lessons' => DB::getSchemaBuilder()->hasTable('teacher_lessons') ? DB::table('teacher_lessons')->where('professeur_id', $teacher->id)->count() : 0,
            'tasks' => DB::getSchemaBuilder()->hasTable('teacher_tasks') ? DB::table('teacher_tasks')->where('professeur_id', $teacher->id)->where('termine', 0)->count() : 0,
            'classes' => DB::table('professeurs_classes')->where('professeur_id', $teacher->id)->count(),
            'route' => route('teacher.workspace'),
        ];
    }

    private function parentPortalSummary(int $userId): array
    {
        $children = DB::table('parent_eleves as pe')
            ->join('eleves as e', 'e.id', '=', 'pe.eleve_id')
            ->where('pe.parent_user_id', $userId)
            ->select('e.id', 'e.nom', 'e.prenom')
            ->orderBy('e.nom')
            ->get();

        return [
            'children' => $children,
            'route' => route('parent.portal'),
        ];
    }

    private function teacherCardData(array $user, object $ecole): ?array
    {
        $teacher = DB::table('professeurs')
            ->leftJoin('matieres', 'matieres.id', '=', 'professeurs.matiere_id')
            ->where('professeurs.email', $user['email'] ?? '')
            ->select(
                'professeurs.id',
                'professeurs.nom',
                'professeurs.prenom',
                'professeurs.photo',
                'professeurs.qr_token',
                'matieres.nom as nom_matiere'
            )
            ->first();

        if (! $teacher) {
            return null;
        }

        $qrToken = $teacher->qr_token ?? '';
        $annee = session('annee_scolaire') ?? now()->year;

        return [
            'id' => $teacher->id,
            'nom' => $teacher->nom,
            'prenom' => $teacher->prenom,
            'photo' => $teacher->photo ?: 'Uploads/default.jpg',
            'qr_token' => $qrToken,
            'matricule' => $teacher->id,
            'badge' => 'Enseignant',
            'badge_type' => 'enseignant',
            'dept_label' => 'Matiere',
            'dept_info' => $teacher->nom_matiere ?? '',
            'annee_scolaire' => $annee,
            'ecole_nom' => $ecole->nom ?? 'NOVASKOL',
        ];
    }

    private function staffCardData(array $user, object $ecole): ?array
    {
        $staff = DB::table('staff')
            ->leftJoin('roles', 'roles.id', '=', 'staff.role_id')
            ->leftJoin('departements', 'departements.id', '=', 'staff.departement_id')
            ->where('staff.email', $user['email'] ?? '')
            ->select(
                'staff.id',
                'staff.nom',
                'staff.prenom',
                'staff.photo',
                'staff.qr_token',
                'staff.poste',
                'roles.nom as nom_role',
                'departements.nom as nom_departement'
            )
            ->first();

        if (! $staff) {
            return null;
        }

        $qrToken = $staff->qr_token ?? '';
        $annee = session('annee_scolaire') ?? now()->year;

        return [
            'id' => $staff->id,
            'nom' => $staff->nom,
            'prenom' => $staff->prenom,
            'photo' => $staff->photo ?: 'Uploads/default.jpg',
            'qr_token' => $qrToken,
            'matricule' => $staff->matricule ?? $staff->id,
            'badge' => $staff->nom_role ?? $staff->poste ?? 'Staff',
            'badge_type' => 'staff',
            'dept_label' => $staff->nom_departement ? 'Departement' : 'Fonction',
            'dept_info' => $staff->nom_departement ?? $staff->poste ?? 'Staff',
            'annee_scolaire' => $annee,
            'ecole_nom' => $ecole->nom ?? 'NOVASKOL',
        ];
    }

    private function studentCardData(array $user, object $ecole): ?array
    {
        $eleve = DB::table('eleves')
            ->leftJoin('classes', 'classes.id', '=', 'eleves.id_classe')
            ->where('eleves.email', $user['email'] ?? '')
            ->select('eleves.*', 'classes.nom as nom_classe')
            ->first();

        if (! $eleve) {
            return null;
        }

        $annee = session('annee_scolaire') ?? now()->year;

        return [
            'id' => $eleve->id,
            'nom' => $eleve->nom,
            'prenom' => $eleve->prenom,
            'photo' => $eleve->photo ?: 'Uploads/default.jpg',
            'qr_token' => $eleve->qr_token ?? '',
            'matricule' => $eleve->matricule ?? $eleve->id,
            'badge' => 'Eleve',
            'badge_type' => 'etudiant',
            'dept_label' => 'Classe',
            'dept_info' => $eleve->nom_classe ?? '',
            'annee_scolaire' => $annee,
            'ecole_nom' => $ecole->nom ?? 'NOVASKOL',
        ];
    }

    private function parentChildrenCards(int $userId, object $ecole): ?array
    {
        $children = DB::table('parent_eleves as pe')
            ->join('eleves as e', 'e.id', '=', 'pe.eleve_id')
            ->leftJoin('classes', 'classes.id', '=', 'e.id_classe')
            ->where('pe.parent_user_id', $userId)
            ->select(
                'e.id',
                'e.nom',
                'e.prenom',
                'e.photo',
                'e.qr_token',
                'e.matricule',
                'e.annee_scolaire',
                'classes.nom as nom_classe'
            )
            ->orderBy('e.nom')
            ->get();

        if ($children->isEmpty()) {
            return null;
        }

        $annee = session('annee_scolaire') ?? now()->year;

        return $children->map(function ($child) use ($ecole, $annee) {
            return [
                'id' => $child->id,
                'nom' => $child->nom,
                'prenom' => $child->prenom,
                'photo' => $child->photo ?: 'Uploads/default.jpg',
                'qr_token' => $child->qr_token ?? '',
                'matricule' => $child->matricule ?? $child->id,
                'badge' => 'Eleve',
                'badge_type' => 'etudiant',
                'dept_label' => 'Classe',
                'dept_info' => $child->nom_classe ?? '',
                'annee_scolaire' => $child->annee_scolaire ?? $annee,
                'ecole_nom' => $ecole->nom ?? 'NOVASKOL',
            ];
        })->all();
    }

    private function staffAttendanceCalendar(int $userId, int $month, int $year): array
    {
        $staff = DB::table('staff')->where('email', session('utilisateur.email') ?? '')->first();
        if (! $staff) {
            return [];
        }

        $records = DB::table('presence_staff')
            ->where(function ($q) use ($staff) {
                $q->where('personne_id', $staff->id)->orWhere('staff_id', $staff->id);
            })
            ->whereYear('date_jour', $year)
            ->whereMonth('date_jour', $month)
            ->select('date_jour', 'session_jour', 'presence', 'retard', 'jours', 'type_scan', 'heure_entree', 'heure_sortie', 'commentaire', 'scan_mode')
            ->orderBy('date_jour')
            ->orderBy('session_jour')
            ->get();

        $days = [];
        $first = \Carbon\Carbon::create($year, $month, 1);
        $daysInMonth = $first->daysInMonth;

        for ($d = 1; $d <= $daysInMonth; $d++) {
            $dayRecords = $records->filter(fn ($r) => (int) \Carbon\Carbon::parse($r->date_jour)->format('d') === $d);

            if ($dayRecords->isNotEmpty()) {
                $status = 'absent';
                $details = [];
                foreach ($dayRecords as $r) {
                    $s = $r->presence ? ($r->retard ? 'retard' : 'present') : 'absent';
                    if ($s === 'retard' || ($s === 'present' && $status !== 'retard')) $status = $s;
                    $details[] = [
                        'session' => $r->session_jour,
                        'type_scan' => $r->type_scan,
                        'statut' => $s,
                        'heure' => $r->jours ? number_format((float) $r->jours, 2) . 'j' : ($r->heure_entree ?? ''),
                        'commentaire' => $r->commentaire,
                        'scan_mode' => $r->scan_mode,
                        'heure_entree' => $r->heure_entree,
                        'heure_sortie' => $r->heure_sortie,
                    ];
                }
                $days[$d] = ['status' => $status, 'details' => $details];
            } else {
                $days[$d] = ['status' => null, 'details' => []];
            }
        }

        return $days;
    }
}
