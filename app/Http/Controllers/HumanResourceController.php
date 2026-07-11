<?php

namespace App\Http\Controllers;

use App\Services\Novaskol\ModuleRegistry;
use App\Services\Novaskol\RelationalDeleteService;
use App\Services\QrCodeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class HumanResourceController extends Controller
{
    public function teachers(Request $request, ModuleRegistry $modules)
    {
        $this->ensureSession(['admin', 'enseignant', 'parent', 'staff']);
        $annee = (string) $request->query('annee_scolaire', '');
        $query = DB::table('professeurs as p')
            ->leftJoin('matieres as m', 'm.id', '=', 'p.matiere_id')
            ->select('p.*', 'm.nom as matiere_nom')
            ->orderBy('p.nom')
            ->orderBy('p.prenom');

        if ($annee !== '') {
            $query->where('p.annee_scolaire', $annee);
        }

        $teachers = $query->get()->map(function ($teacher) {
            $assignments = DB::table('professeurs_classes as pc')
                ->join('classes as c', 'c.id', '=', 'pc.classe_id')
                ->where('pc.professeur_id', $teacher->id)
                ->orderBy('c.nom')
                ->select('pc.*', 'c.nom as classe_nom')
                ->get();
            $teacher->classes_ids = $assignments->pluck('classe_id')->map(fn ($id) => (int) $id)->all();
            $teacher->classes_assignments = $assignments->keyBy('classe_id');
            $teacher->classes_labels = $assignments->map(function ($assignment) {
                $mode = ($assignment->affectation_type ?? 'fixe') === 'flexible' ? 'intervention' : 'fixe';
                return $assignment->classe_nom.' ('.$mode.')';
            })->implode(', ');
            return $teacher;
        });

        return $this->view('modules.rh.people', $modules, 'enseignants', [
            'type' => 'teachers',
            'title' => 'Gestion des enseignants',
            'people' => $teachers,
            'usersWithoutProfile' => $this->usersWithoutProfile('enseignant', 'professeurs'),
            'annees' => $this->schoolYearsFrom('professeurs'),
            'selectedAnnee' => $annee,
            'matieres' => DB::table('matieres')->select('id', 'nom')->orderBy('nom')->get(),
            'classes' => DB::table('classes')->select('id', 'nom')->orderBy('nom')->get(),
        ]);
    }

    public function storeTeacher(Request $request)
    {
        $this->ensureSession(['admin', 'enseignant', 'parent', 'staff']);
        $data = $request->validate([
            'nom' => ['required', 'string', 'max:100'],
            'prenom' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:100'],
            'telephone' => ['required', 'string', 'max:20'],
            'annee_scolaire' => ['required', 'string', 'max:20'],
            'matiere_id' => ['required', 'integer'],
            'salaire_horaire' => ['nullable', 'numeric'],
            'diplome_pedagogique' => ['nullable', 'string', 'max:100'],
            'autorisation_enseigner' => ['nullable', 'in:Oui,Non,En cours'],
            'annees_experience' => ['nullable', 'integer', 'min:0'],
            'statut' => ['nullable', 'in:actif,inactif'],
            'classes_ids' => ['array'],
            'classes_ids.*' => ['integer'],
            'classes_modes' => ['array'],
            'classes_modes.*' => ['nullable', 'in:fixe,flexible'],
            'classes_notes' => ['array'],
            'classes_notes.*' => ['nullable', 'string', 'max:255'],
            'mot_de_passe' => ['nullable', 'string', 'min:4', 'max:100'],
            'photo' => ['nullable', 'image', 'max:4096'],
        ]);
        $this->rejectUsedEmail($data['email'], null, 'enseignant', 'professeurs');

        DB::transaction(function () use ($request, $data) {
            $photo = $this->uploadPhoto($request, 'prof_');
            $accountData = array_merge($data, ['photo' => $photo]);
            $qrToken = app(QrCodeService::class)->generateUniqueToken();
            $id = DB::table('professeurs')->insertGetId([
                'nom' => $data['nom'],
                'prenom' => $data['prenom'],
                'email' => $data['email'],
                'telephone' => $data['telephone'],
                'annee_scolaire' => $data['annee_scolaire'],
                'matiere_id' => $data['matiere_id'],
                'salaire_horaire' => $data['salaire_horaire'] ?? 0,
                'diplome_pedagogique' => $data['diplome_pedagogique'] ?? 'Aucun',
                'autorisation_enseigner' => $data['autorisation_enseigner'] ?? 'Non',
                'annees_experience' => $data['annees_experience'] ?? 0,
                'statut' => $data['statut'] ?? 'actif',
                'photo' => $photo,
                'qr_token' => $qrToken,
            ]);
            $this->syncMpiasa($data, 'professeur');
            $this->syncUserAccount($accountData, 'enseignant');
            $this->syncTeacherClasses($id, $data['classes_ids'] ?? [], $data['annee_scolaire']);
        });

        return redirect()->route('modules.enseignants')->with('rh_msg', ['type' => 'success', 'text' => 'Professeur ajoute avec succes.']);
    }

    public function updateTeacher(Request $request, int $id)
    {
        $this->ensureSession(['admin', 'enseignant', 'parent', 'staff']);
        $teacher = DB::table('professeurs')->where('id', $id)->first();
        abort_unless($teacher, 404);
        $data = $request->validate([
            'nom' => ['required', 'string', 'max:100'],
            'prenom' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:100'],
            'telephone' => ['required', 'string', 'max:20'],
            'annee_scolaire' => ['required', 'string', 'max:20'],
            'matiere_id' => ['required', 'integer'],
            'salaire_horaire' => ['nullable', 'numeric'],
            'diplome_pedagogique' => ['nullable', 'string', 'max:100'],
            'autorisation_enseigner' => ['nullable', 'in:Oui,Non,En cours'],
            'annees_experience' => ['nullable', 'integer', 'min:0'],
            'statut' => ['nullable', 'in:actif,inactif'],
            'classes_ids' => ['array'],
            'classes_ids.*' => ['integer'],
            'classes_modes' => ['array'],
            'classes_modes.*' => ['nullable', 'in:fixe,flexible'],
            'classes_notes' => ['array'],
            'classes_notes.*' => ['nullable', 'string', 'max:255'],
            'mot_de_passe' => ['nullable', 'string', 'min:4', 'max:100'],
            'photo' => ['nullable', 'image', 'max:4096'],
        ]);
        $this->rejectUsedEmail($data['email'], $teacher->email, 'enseignant', 'professeurs');

        DB::transaction(function () use ($request, $data, $teacher, $id) {
            $photo = $this->uploadPhoto($request, 'prof_', $teacher->photo);
            $accountData = array_merge($data, ['photo' => $photo]);
            DB::table('professeurs')->where('id', $id)->update([
                'nom' => $data['nom'],
                'prenom' => $data['prenom'],
                'email' => $data['email'],
                'telephone' => $data['telephone'],
                'annee_scolaire' => $data['annee_scolaire'],
                'matiere_id' => $data['matiere_id'],
                'salaire_horaire' => $data['salaire_horaire'] ?? 0,
                'diplome_pedagogique' => $data['diplome_pedagogique'] ?? 'Aucun',
                'autorisation_enseigner' => $data['autorisation_enseigner'] ?? 'Non',
                'annees_experience' => $data['annees_experience'] ?? 0,
                'statut' => $data['statut'] ?? 'actif',
                'photo' => $photo,
            ]);
            $this->syncMpiasa($data, 'professeur', $teacher->email);
            $this->syncUserAccount($accountData, 'enseignant', $teacher->email);
            $this->syncTeacherClasses($id, $data['classes_ids'] ?? [], $data['annee_scolaire']);
        });

        return redirect()->route('modules.enseignants')->with('rh_msg', ['type' => 'success', 'text' => 'Professeur modifie avec succes.']);
    }

    public function deleteTeacher(int $id, RelationalDeleteService $deletions)
    {
        $this->ensureSession(['admin', 'enseignant', 'parent', 'staff']);
        $teacher = DB::table('professeurs')->where('id', $id)->first();
        abort_unless($teacher, 404);
        DB::transaction(function () use ($teacher, $id, $deletions) {
            $user = DB::table('utilisateurs')->where('email', $teacher->email)->where('role', 'enseignant')->first();
            DB::table('mpiasa')->where('email', $teacher->email)->delete();
            DB::table('staff')->where('id', $id)->delete();
            DB::table('presence_personnels')->where('personne_id', $id)->delete();
            if ($user) {
                $deletions->deleteUserRelations((int) $user->id, 'enseignant');
                DB::table('utilisateurs')->where('id', $user->id)->delete();
            }
            $deletions->deleteTeacherRelations($id);
            DB::table('professeurs')->where('id', $id)->delete();
            $this->deleteLegacyPhoto($teacher->photo);
        });

        return redirect()->route('modules.enseignants')->with('rh_msg', ['type' => 'success', 'text' => 'Professeur supprime avec succes.']);
    }

    public function staff(Request $request, ModuleRegistry $modules)
    {
        $this->ensureSession(['admin', 'staff', 'enseignant']);
        $this->ensureStaffLookups();
        $annee = (string) $request->query('annee_scolaire', '');
        $query = DB::table('staff as s')
            ->leftJoin('roles as r', 'r.id', '=', 's.role_id')
            ->leftJoin('departements as d', 'd.id', '=', 's.departement_id')
            ->select('s.*', 'r.nom as role_nom', 'd.nom as departement_nom')
            ->where(function ($q) {
                $q->whereNull('s.poste')->orWhere('s.poste', '!=', 'Enseignant');
            })
            ->orderBy('s.nom')
            ->orderBy('s.prenom');
        if ($annee !== '') {
            $query->where('s.annee_scolaire', $annee);
        }

        return $this->view('modules.rh.people', $modules, 'staff', [
            'type' => 'staff',
            'title' => 'Gestion du staff',
            'people' => $query->get(),
            'usersWithoutProfile' => $this->usersWithoutProfile('staff', 'staff'),
            'annees' => $this->schoolYearsFrom('staff'),
            'selectedAnnee' => $annee,
            'roles' => DB::table('roles')->select('id', 'nom')->orderBy('nom')->get(),
            'departements' => DB::table('departements')->select('id', 'nom')->orderBy('nom')->get(),
        ]);
    }

    public function storeStaff(Request $request)
    {
        $this->ensureSession(['admin', 'staff', 'enseignant']);
        $data = $this->validateStaff($request);
        $this->rejectUsedEmail($data['email'], null, 'staff', 'staff');
        DB::transaction(function () use ($request, $data) {
            $photo = $this->uploadPhoto($request, 'staff_');
            $accountData = array_merge($data, ['photo' => $photo]);
            $qrToken = app(QrCodeService::class)->generateUniqueToken();
            $poste = DB::table('roles')->where('id', $data['role_id'])->value('nom') ?: 'Staff';
            DB::table('staff')->insert([
                'nom' => $data['nom'],
                'prenom' => $data['prenom'],
                'poste' => $poste,
                'email' => $data['email'],
                'telephone' => $data['telephone'],
                'annee_scolaire' => $data['annee_scolaire'],
                'role_id' => $data['role_id'],
                'salaire_base' => $data['salaire_base'] ?? 0,
                'departement_id' => $data['departement_id'] ?? null,
                'diplome_pedagogique' => $data['diplome_pedagogique'] ?? 'Aucun',
                'annees_experience' => $data['annees_experience'] ?? 0,
                'photo' => $photo,
                'qr_token' => $qrToken,
            ]);
            $this->syncMpiasa($data, 'staff');
            $this->syncUserAccount($accountData, 'staff');
        });

        return redirect()->route('modules.staff')->with('rh_msg', ['type' => 'success', 'text' => 'Membre du staff ajoute avec succes.']);
    }

    public function updateStaff(Request $request, int $id)
    {
        $this->ensureSession(['admin', 'staff', 'enseignant']);
        $staff = DB::table('staff')->where('id', $id)->first();
        abort_unless($staff, 404);
        $data = $this->validateStaff($request);
        $this->rejectUsedEmail($data['email'], $staff->email, 'staff', 'staff');
        DB::transaction(function () use ($request, $data, $staff, $id) {
            $photo = $this->uploadPhoto($request, 'staff_', $staff->photo);
            $accountData = array_merge($data, ['photo' => $photo]);
            $poste = DB::table('roles')->where('id', $data['role_id'])->value('nom') ?: 'Staff';
            DB::table('staff')->where('id', $id)->update([
                'nom' => $data['nom'],
                'prenom' => $data['prenom'],
                'poste' => $poste,
                'email' => $data['email'],
                'telephone' => $data['telephone'],
                'annee_scolaire' => $data['annee_scolaire'],
                'role_id' => $data['role_id'],
                'salaire_base' => $data['salaire_base'] ?? 0,
                'departement_id' => $data['departement_id'] ?? null,
                'diplome_pedagogique' => $data['diplome_pedagogique'] ?? 'Aucun',
                'annees_experience' => $data['annees_experience'] ?? 0,
                'photo' => $photo,
            ]);
            $this->syncMpiasa($data, 'staff', $staff->email);
            $this->syncUserAccount($accountData, 'staff', $staff->email);
        });

        return redirect()->route('modules.staff')->with('rh_msg', ['type' => 'success', 'text' => 'Membre du staff modifie avec succes.']);
    }

    public function deleteStaff(int $id, RelationalDeleteService $deletions)
    {
        $this->ensureSession(['admin', 'staff', 'enseignant']);
        $staff = DB::table('staff')->where('id', $id)->first();
        abort_unless($staff, 404);
        DB::transaction(function () use ($staff, $id, $deletions) {
            $user = DB::table('utilisateurs')->where('email', $staff->email)->where('role', 'staff')->first();
            DB::table('mpiasa')->where('email', $staff->email)->delete();
            if ($user) {
                $deletions->deleteUserRelations((int) $user->id, 'staff');
                DB::table('utilisateurs')->where('id', $user->id)->delete();
            }
            $deletions->deleteStaffRelations($id);
            DB::table('staff')->where('id', $id)->delete();
            $this->deleteLegacyPhoto($staff->photo);
        });

        return redirect()->route('modules.staff')->with('rh_msg', ['type' => 'success', 'text' => 'Membre du staff supprime avec succes.']);
    }

    public function teacherPresence(Request $request, ModuleRegistry $modules)
    {
        $this->ensureSession(['admin', 'enseignant', 'parent', 'staff']);
        return $this->presenceView($request, $modules, 'teacher');
    }

    public function staffPresence(Request $request, ModuleRegistry $modules)
    {
        $this->ensureSession(['admin', 'enseignant', 'parent', 'staff']);
        return $this->presenceView($request, $modules, 'staff');
    }

    public function storeTeacherPresence(Request $request)
    {
        $this->ensureSession(['admin', 'enseignant', 'parent', 'staff']);
        abort_unless($this->canManagePresenceRecords(), 403);
        $data = $request->validate([
            'personne_id' => ['required', 'integer'],
            'annee_scolaire' => ['required', 'string', 'max:20'],
            'mois' => ['required', 'string', 'max:20'],
            'date_jour' => ['required', 'date'],
            'horaire' => ['required', 'numeric', 'min:0'],
            'presence' => ['required', 'boolean'],
            'retard' => ['required', 'boolean'],
        ]);
        $personneId = (int) $data['personne_id'];
        if (!DB::table('staff')->where('id', $personneId)->exists()) {
            $teacher = DB::table('professeurs')->where('id', $personneId)->first();
            if ($teacher) {
                DB::table('staff')->insert([
                    'id' => $personneId,
                    'nom' => $teacher->nom,
                    'prenom' => $teacher->prenom,
                    'poste' => 'Enseignant',
                    'email' => $teacher->email,
                    'telephone' => $teacher->telephone,
                    'salaire_base' => $teacher->salaire_horaire ?? 0,
                    'annee_scolaire' => $data['annee_scolaire'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
        DB::table('presence_personnels')->insert($data + [
            'staff_id' => $personneId,
            'date_enregistrement' => now(),
        ]);
        $this->notify('presence', "Checking de presence d'un enseignant effectue le {$data['date_jour']}");
        return redirect()->route('modules.presence', $this->presenceQuery($data))->with('rh_msg', ['type' => 'success', 'text' => 'Presence enregistree avec succes.']);
    }

    public function updateTeacherPresence(Request $request, int $id)
    {
        $this->ensureSession(['admin', 'enseignant', 'parent', 'staff']);
        abort_unless($this->canManagePresenceRecords(), 403);
        $data = $request->validate([
            'annee_scolaire' => ['required', 'string', 'max:20'],
            'mois' => ['required', 'string', 'max:20'],
            'date_jour' => ['required', 'date'],
            'presence' => ['required', 'boolean'],
            'retard' => ['required', 'boolean'],
        ]);
        DB::table('presence_personnels')->where('id', $id)->update($data);
        return redirect()->route('modules.presence', $this->presenceQuery($data))->with('rh_msg', ['type' => 'success', 'text' => 'Presence modifiee avec succes.']);
    }

    public function deleteTeacherPresence(Request $request, int $id)
    {
        $this->ensureSession(['admin', 'enseignant', 'parent', 'staff']);
        abort_unless($this->canManagePresenceRecords(), 403);
        DB::table('presence_personnels')->where('id', $id)->delete();
        return redirect()->route('modules.presence', $request->only(['annee_scolaire', 'mois', 'jour']))->with('rh_msg', ['type' => 'success', 'text' => 'Presence supprimee avec succes.']);
    }

    public function storeStaffPresence(Request $request)
    {
        $this->ensureSession(['admin', 'enseignant', 'parent', 'staff']);
        abort_unless($this->canManagePresenceRecords(), 403);
        $data = $request->validate([
            'personne_id' => ['required', 'integer'],
            'annee_scolaire' => ['required', 'string', 'max:20'],
            'mois' => ['required', 'string', 'max:20'],
            'date_jour' => ['required', 'date'],
            'presence' => ['required', 'boolean'],
            'retard' => ['required', 'boolean'],
            'jours' => ['nullable', 'numeric', 'min:0'],
        ]);
        DB::table('presence_staff')->insert(array_merge($data, ['staff_id' => $data['personne_id'], 'jours' => ((float) ($data['jours'] ?? 0)) > 0 ? (float) $data['jours'] : (($data['presence'] ?? 0) ? 1 : 0), 'date_enregistrement' => now()]));
        $this->notify('presence', "Checking de presence d'un employe effectue le {$data['date_jour']}");
        return redirect()->route('modules.presence-staff', $this->presenceQuery($data))->with('rh_msg', ['type' => 'success', 'text' => 'Presence staff enregistree avec succes.']);
    }

    public function updateStaffPresence(Request $request, int $id)
    {
        $this->ensureSession(['admin', 'enseignant', 'parent', 'staff']);
        abort_unless($this->canManagePresenceRecords(), 403);
        $data = $request->validate([
            'annee_scolaire' => ['required', 'string', 'max:20'],
            'mois' => ['required', 'string', 'max:20'],
            'date_jour' => ['required', 'date'],
            'presence' => ['required', 'boolean'],
            'retard' => ['required', 'boolean'],
            'jours' => ['nullable', 'numeric', 'min:0'],
        ]);
        DB::table('presence_staff')->where('id', $id)->update(array_merge($data, ['jours' => ((float) ($data['jours'] ?? 0)) > 0 ? (float) $data['jours'] : (($data['presence'] ?? 0) ? 1 : 0)]));
        return redirect()->route('modules.presence-staff', $this->presenceQuery($data))->with('rh_msg', ['type' => 'success', 'text' => 'Presence staff modifiee avec succes.']);
    }

    public function deleteStaffPresence(Request $request, int $id)
    {
        $this->ensureSession(['admin', 'enseignant', 'parent', 'staff']);
        abort_unless($this->canManagePresenceRecords(), 403);
        DB::table('presence_staff')->where('id', $id)->delete();
        return redirect()->route('modules.presence-staff', $request->only(['annee_scolaire', 'mois', 'jour']))->with('rh_msg', ['type' => 'success', 'text' => 'Presence staff supprimee avec succes.']);
    }

    public function unifiedPresence(Request $request, ModuleRegistry $modules)
    {
        $this->ensureSession(['admin', 'enseignant', 'parent', 'staff']);
        $this->ensurePresenceIndexes();
        $canManagePresence = $this->canManagePresenceRecords();
        $filterType = (string) $request->query('type', 'all');

        $y = (int) now()->format('Y');
        $m = (int) now()->format('n');
        $defaultAnnee = ($m >= 9 ? $y : ($y - 1)) . '-' . ($m >= 9 ? ($y + 1) : $y);
        $annee = (string) $request->query('annee_scolaire', $defaultAnnee);
        $mois = (string) $request->query('mois', now()->format('m'));
        $jour = (string) $request->query('jour', now()->toDateString());
        if (preg_match('/^\d{1,2}$/', $jour)) {
            $jour = now()->format('Y-m-') . str_pad($jour, 2, '0', STR_PAD_LEFT);
        }

        $today = now()->toDateString();
        $todayScans = collect();

        $staffTable = 'presence_staff';
        if ($filterType !== 'teacher' && Schema::hasTable($staffTable)) {
            $todayScans = $todayScans->concat(
                DB::table("$staffTable as ps")
                    ->leftJoin('staff as p', 'p.id', '=', 'ps.personne_id')
                    ->leftJoin('roles as r', 'r.id', '=', 'p.role_id')
                    ->select('ps.*', 'p.nom', 'p.prenom', 'p.photo', DB::raw("'staff' as person_type"), DB::raw("COALESCE(r.nom, 'Staff') as person_role"))
                    ->whereDate('ps.date_jour', $today)
                    ->orderBy('ps.created_at', 'desc')
                    ->get()
            );
        }

        $teacherTable = 'presence_personnels';
        if ($filterType !== 'staff' && Schema::hasTable($teacherTable)) {
            $todayScans = $todayScans->concat(
                DB::table("$teacherTable as ps")
                    ->leftJoin('professeurs as p', 'p.id', '=', 'ps.personne_id')
                    ->select('ps.*', 'p.nom', 'p.prenom', 'p.photo', DB::raw("'teacher' as person_type"), DB::raw("'Enseignant' as person_role"))
                    ->whereDate('ps.date_jour', $today)
                    ->orderBy('ps.created_at', 'desc')
                    ->get()
            );
        }

        $todayScans = $todayScans->sortByDesc('created_at')->take(50);

        $allPeople = collect();
        if ($filterType !== 'teacher') {
            $allPeople = $allPeople->concat(
                DB::table('staff')
                    ->leftJoin('roles as r', 'r.id', '=', 'staff.role_id')
                    ->select('staff.id', 'staff.nom', 'staff.prenom', DB::raw("'staff' as person_type"), DB::raw("COALESCE(r.nom, 'Staff') as person_role"))
                    ->orderBy('staff.nom')
                    ->get()
            );
        }
        if ($filterType !== 'staff') {
            $allPeople = $allPeople->concat(
                DB::table('professeurs')
                    ->select('id', 'nom', 'prenom', DB::raw("'teacher' as person_type"), DB::raw("'Enseignant' as person_role"))
                    ->orderBy('nom')
                    ->get()
            );
        }
        $allPeople = $allPeople->sortBy('nom')->values();

        return $this->view('modules.rh.presence', $modules, 'presence_unified', [
            'type' => $filterType,
            'title' => 'Pointage unifie',
            'people' => $allPeople,
            'annee' => $annee,
            'mois' => $mois,
            'jour' => $jour,
            'annees' => collect($this->schoolYearsFrom('professeurs'))->merge($this->schoolYearsFrom('staff'))->unique()->values(),
            'canManagePresence' => $canManagePresence,
            'todayScans' => $todayScans,
            'isUnified' => true,
        ]);
    }

    public function permissions(Request $request, ModuleRegistry $modules)
    {
        $this->ensureSession(['admin']);
        $selectedUser = (int) $request->query('utilisateur_id', 0);
        $selectedRole = (string) DB::table('utilisateurs')->where('id', $selectedUser)->value('role');
        if ($selectedRole === 'parent') {
            return redirect()->route('modules.permissions')
                ->with('rh_msg', ['type' => 'warning', 'text' => 'Les comptes parents utilisent des acces automatiques et ne sont pas geres ici.']);
        }
        $permissions = $selectedUser > 0 ? DB::table('permissions')->where('utilisateur_id', $selectedUser)->pluck('acces', 'module')->all() : [];
        if ($selectedRole === 'admin') {
            $permissions = array_fill_keys(array_keys($modules->all()), 'ecriture');
        }

        return $this->view('modules.rh.permissions', $modules, 'permissions', [
            'utilisateurs' => DB::table('utilisateurs')->select('id', 'nom', 'email', 'role')->where('role', '!=', 'parent')->orderBy('role')->orderBy('nom')->get(),
            'selectedUser' => $selectedUser,
            'selectedRole' => $selectedRole,
            'permissionMap' => $permissions,
            'moduleList' => $modules->all(),
            'accessLevels' => ['aucun' => 'Aucun', 'masquer' => 'Masquer', 'lecture' => 'Lecture', 'ecriture' => 'Ecriture'],
        ]);
    }

    public function updatePermissions(Request $request, ModuleRegistry $modules)
    {
        $this->ensureSession(['admin']);
        $data = $request->validate([
            'utilisateur_id' => ['required', 'integer'],
            'role' => ['nullable', 'string', 'max:100'],
            'permissions' => ['array'],
        ]);
        $valid = ['aucun', 'masquer', 'lecture', 'ecriture'];
        DB::transaction(function () use ($data, $modules, $valid) {
            $targetRole = (string) DB::table('utilisateurs')->where('id', $data['utilisateur_id'])->value('role');
            abort_if($targetRole === 'parent', 422, 'Les permissions parent sont automatiques.');
            DB::table('permissions')->where('utilisateur_id', $data['utilisateur_id'])->delete();
            foreach (array_keys($modules->all()) as $module) {
                $access = $targetRole === 'admin' ? 'ecriture' : ($data['permissions'][$module] ?? 'aucun');
                if (! in_array($access, $valid, true)) {
                    $access = 'aucun';
                }
                DB::table('permissions')->insert([
                    'utilisateur_id' => $data['utilisateur_id'],
                    'role' => $targetRole ?: ($data['role'] ?? ''),
                    'module' => $module,
                    'acces' => $access,
                ]);
            }
        });

        return redirect()->route('modules.permissions', ['utilisateur_id' => $data['utilisateur_id']])->with('rh_msg', ['type' => 'success', 'text' => 'Permissions mises a jour avec succes.']);
    }

    public function resources(Request $request, ModuleRegistry $modules)
    {
        $this->ensureSession(['admin', 'staff', 'enseignant', 'parent']);
        $date = (string) $request->query('date_reservation', now()->toDateString());
        $reservations = DB::table('reservations_ressources as r')
            ->join('salles as s', 's.id', '=', 'r.id_salle')
            ->leftJoin('utilisateurs as u', 'u.id', '=', 'r.utilisateur')
            ->select('r.*', 's.nom as salle_nom', 'u.nom as utilisateur_nom')
            ->whereDate('r.date_reservation', $date)
            ->orderBy('r.heure_debut')
            ->get();

        return $this->view('modules.rh.resources', $modules, 'gestion_ressource', [
            'salles' => DB::table('salles')->orderBy('nom')->get(),
            'equipements' => DB::table('equipements')->orderBy('nom')->get(),
            'utilisateurs' => DB::table('utilisateurs')->orderBy('nom')->get(),
            'dateReservation' => $date,
            'reservations' => $reservations,
        ]);
    }

    public function resourceAction(Request $request)
    {
        $this->ensureSession(['admin', 'staff', 'enseignant', 'parent']);
        $action = (string) $request->input('action');
        return match ($action) {
            'add_salle' => $this->addSalle($request),
            'edit_salle' => $this->editSalle($request),
            'delete_salle' => $this->deleteSalle($request),
            'add_equipement' => $this->addEquipement($request),
            'edit_equipement' => $this->editEquipement($request),
            'delete_equipement' => $this->deleteEquipement($request),
            'add_reservation' => $this->addReservation($request),
            'delete_reservation' => $this->deleteReservation($request),
            default => redirect()->route('modules.gestion-ressource')->with('rh_msg', ['type' => 'error', 'text' => 'Action inconnue.']),
        };
    }

    private function presenceView(Request $request, ModuleRegistry $modules, string $type)
    {
        $this->ensurePresenceIndexes();
        $canManagePresence = $this->canManagePresenceRecords();
        $table = $type === 'teacher' ? 'presence_personnels' : 'presence_staff';
        $defaultAnnee = DB::table($table)
            ->whereNotNull('annee_scolaire')
            ->where('annee_scolaire', '!=', '')
            ->orderByDesc('annee_scolaire')
            ->value('annee_scolaire');
        if (!$defaultAnnee) {
            $y = (int) now()->format('Y');
            $m = (int) now()->format('n');
            $defaultAnnee = ($m >= 9 ? $y : ($y - 1)) . '-' . ($m >= 9 ? ($y + 1) : $y);
        }
        $annee = (string) $request->query('annee_scolaire', $defaultAnnee);
        $mois = (string) $request->query('mois', now()->format('m'));
        $jour = (string) $request->query('jour', now()->toDateString());
        if (preg_match('/^\d{1,2}$/', $jour)) {
            $jour = now()->format('Y-m-').str_pad($jour, 2, '0', STR_PAD_LEFT);
        }

        $peopleTable = $type === 'teacher' ? 'professeurs' : 'staff';
        $active = $type === 'teacher' ? 'presence' : 'presence_staff';
        $routePrefix = $type === 'teacher' ? 'modules.presence' : 'modules.presence-staff';

        $records = DB::table("$table as ps")
            ->leftJoin("$peopleTable as p", 'p.id', '=', 'ps.personne_id')
            ->select('ps.*', 'p.nom', 'p.prenom')
            ->where('ps.annee_scolaire', $annee)
            ->where(DB::raw('CAST(ps.mois AS UNSIGNED)'), (int) $mois)
            ->whereDate('ps.date_jour', $jour)
            ->orderBy('p.nom')
            ->when(! $canManagePresence, fn ($q) => $q->whereRaw('1 = 0'))
            ->paginate(20)
            ->withQueryString();

        $todayScans = DB::table("$table as ps")
            ->leftJoin("$peopleTable as p", 'p.id', '=', 'ps.personne_id')
            ->select('ps.*', 'p.nom', 'p.prenom', 'p.photo')
            ->whereDate('ps.date_jour', now()->toDateString())
            ->orderBy('ps.created_at', 'desc')
            ->get();

        return $this->view('modules.rh.presence', $modules, $active, [
            'type' => $type,
            'title' => $type === 'teacher' ? 'Presence des enseignants' : 'Presence du staff',
            'people' => DB::table($peopleTable)->select('id', 'nom', 'prenom')->when($type === 'staff', fn ($q) => $q->where('poste', '!=', 'Enseignant'))->orderBy('nom')->orderBy('prenom')->get(),
            'records' => $records,
            'annee' => $annee,
            'mois' => $mois,
            'jour' => $jour,
            'routePrefix' => $routePrefix,
            'annees' => collect([$annee])->merge($this->schoolYearsFrom($peopleTable))->unique()->values(),
            'canManagePresence' => $canManagePresence,
            'todayScans' => $todayScans,
        ]);
    }

    private function validateStaff(Request $request): array
    {
        return $request->validate([
            'nom' => ['required', 'string', 'max:100'],
            'prenom' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:150'],
            'telephone' => ['required', 'string', 'max:20'],
            'annee_scolaire' => ['required', 'string', 'max:20'],
            'role_id' => ['required', 'integer'],
            'salaire_base' => ['nullable', 'numeric'],
            'departement_id' => ['nullable', 'integer'],
            'diplome_pedagogique' => ['nullable', 'string', 'max:100'],
            'annees_experience' => ['nullable', 'integer', 'min:0'],
            'statut' => ['nullable', 'in:actif,inactif'],
            'mot_de_passe' => ['nullable', 'string', 'min:4', 'max:100'],
            'photo' => ['nullable', 'image', 'max:4096'],
        ]);
    }

    private function ensureStaffLookups(): void
    {
        if (DB::table('roles')->count() === 0) {
            foreach (['Secretaire', 'Comptable', 'RH', 'Assistant', 'Autre'] as $role) {
                DB::table('roles')->insert(['nom' => $role]);
            }
        }

        if (DB::table('departements')->count() === 0) {
            foreach (['Administration', 'Comptabilite', 'Secretariat', 'Pedagogie', 'Surveillance', 'Maintenance', 'Infirmerie', 'Bibliotheque', 'Informatique'] as $departement) {
                DB::table('departements')->insert(['nom' => $departement]);
            }
        }
    }

    private function addSalle(Request $request)
    {
        $data = $request->validate(['nom_salle' => ['required', 'string', 'max:100'], 'capacite' => ['required', 'integer'], 'description_salle' => ['nullable', 'string']]);
        DB::table('salles')->insert(['nom' => $data['nom_salle'], 'capacite' => $data['capacite'], 'description' => $data['description_salle'] ?? '']);
        $this->notify('salle', "Nouvelle salle ajoutee : {$data['nom_salle']}");
        return redirect()->route('modules.gestion-ressource')->with('rh_msg', ['type' => 'success', 'text' => 'Salle ajoutee avec succes.']);
    }

    private function editSalle(Request $request)
    {
        $data = $request->validate(['id_salle' => ['required', 'integer'], 'nom_salle' => ['required', 'string', 'max:100'], 'capacite' => ['required', 'integer'], 'description_salle' => ['nullable', 'string']]);
        DB::table('salles')->where('id', $data['id_salle'])->update(['nom' => $data['nom_salle'], 'capacite' => $data['capacite'], 'description' => $data['description_salle'] ?? '']);
        return redirect()->route('modules.gestion-ressource')->with('rh_msg', ['type' => 'success', 'text' => 'Salle modifiee avec succes.']);
    }

    private function deleteSalle(Request $request)
    {
        DB::table('salles')->where('id', (int) $request->input('id_salle'))->delete();
        return redirect()->route('modules.gestion-ressource')->with('rh_msg', ['type' => 'success', 'text' => 'Salle supprimee avec succes.']);
    }

    private function addEquipement(Request $request)
    {
        $data = $request->validate(['nom_equipement' => ['required', 'string', 'max:100'], 'quantite' => ['required', 'integer'], 'description_equipement' => ['nullable', 'string']]);
        DB::table('equipements')->insert(['nom' => $data['nom_equipement'], 'type' => $data['nom_equipement'], 'quantite' => $data['quantite'], 'description' => $data['description_equipement'] ?? '']);
        return redirect()->route('modules.gestion-ressource')->with('rh_msg', ['type' => 'success', 'text' => 'Equipement ajoute avec succes.']);
    }

    private function editEquipement(Request $request)
    {
        $data = $request->validate(['id_equipement' => ['required', 'integer'], 'nom_equipement' => ['required', 'string', 'max:100'], 'quantite' => ['required', 'integer'], 'description_equipement' => ['nullable', 'string']]);
        DB::table('equipements')->where('id', $data['id_equipement'])->update(['nom' => $data['nom_equipement'], 'quantite' => $data['quantite'], 'description' => $data['description_equipement'] ?? '']);
        return redirect()->route('modules.gestion-ressource')->with('rh_msg', ['type' => 'success', 'text' => 'Equipement modifie avec succes.']);
    }

    private function deleteEquipement(Request $request)
    {
        DB::table('equipements')->where('id', (int) $request->input('id_equipement'))->delete();
        return redirect()->route('modules.gestion-ressource')->with('rh_msg', ['type' => 'success', 'text' => 'Equipement supprime avec succes.']);
    }

    private function addReservation(Request $request)
    {
        $data = $request->validate([
            'id_salle' => ['required', 'integer'],
            'date_reservation' => ['required', 'date'],
            'heure_debut' => ['required', 'date_format:H:i'],
            'heure_fin' => ['required', 'date_format:H:i', 'after:heure_debut'],
            'utilisateur' => ['required', 'integer'],
            'description_reservation' => ['nullable', 'string'],
        ]);
        $conflict = DB::table('reservations_ressources')
            ->where('id_salle', $data['id_salle'])
            ->whereDate('date_reservation', $data['date_reservation'])
            ->where(function ($query) use ($data) {
                $query->where(function ($q) use ($data) {
                    $q->where('heure_debut', '<=', $data['heure_debut'])->where('heure_fin', '>', $data['heure_debut']);
                })->orWhere(function ($q) use ($data) {
                    $q->where('heure_debut', '<', $data['heure_fin'])->where('heure_fin', '>=', $data['heure_fin']);
                })->orWhere(function ($q) use ($data) {
                    $q->where('heure_debut', '>=', $data['heure_debut'])->where('heure_fin', '<=', $data['heure_fin']);
                });
            })->exists();

        if ($conflict) {
            return redirect()->route('modules.gestion-ressource', ['date_reservation' => $data['date_reservation']])->with('rh_msg', ['type' => 'error', 'text' => 'Ce creneau est deja reserve.']);
        }

        DB::table('reservations_ressources')->insert([
            'reservation_id' => 0,
            'ressource_id' => 0,
            'id_salle' => $data['id_salle'],
            'date_reservation' => $data['date_reservation'].' 00:00:00',
            'heure_debut' => $data['heure_debut'],
            'heure_fin' => $data['heure_fin'],
            'utilisateur' => $data['utilisateur'],
            'utilisateur_id' => $data['utilisateur'],
            'statut' => 'confirmé',
            'description' => $data['description_reservation'] ?? '',
        ]);

        return redirect()->route('modules.gestion-ressource', ['date_reservation' => $data['date_reservation']])->with('rh_msg', ['type' => 'success', 'text' => 'Reservation ajoutee avec succes.']);
    }

    private function deleteReservation(Request $request)
    {
        $date = (string) $request->input('date_reservation', now()->toDateString());
        DB::table('reservations_ressources')->where('id', (int) $request->input('id_reservation'))->delete();
        return redirect()->route('modules.gestion-ressource', ['date_reservation' => $date])->with('rh_msg', ['type' => 'success', 'text' => 'Reservation annulee avec succes.']);
    }

    private function view(string $name, ModuleRegistry $modules, string $activeModule, array $data = [])
    {
        return view($name, $data + [
            'modules' => $modules->all(),
            'userPermissions' => $this->userPermissions(),
            'ecole' => $this->school(),
            'activeModule' => $activeModule,
        ]);
    }

    private function syncTeacherClasses(int $professeurId, array $classesIds, string $annee): void
    {
        DB::table('professeurs_classes')->where('professeur_id', $professeurId)->delete();
        foreach (array_unique(array_map('intval', $classesIds)) as $classeId) {
            if ($classeId > 0) {
                $payload = ['professeur_id' => $professeurId, 'classe_id' => $classeId, 'annee_scolaire' => $annee];
                if (DB::getSchemaBuilder()->hasColumn('professeurs_classes', 'affectation_type')) {
                    $mode = request()->input("classes_modes.$classeId", 'fixe');
                    $payload['affectation_type'] = in_array($mode, ['fixe', 'flexible'], true) ? $mode : 'fixe';
                }
                if (DB::getSchemaBuilder()->hasColumn('professeurs_classes', 'commentaire')) {
                    $payload['commentaire'] = request()->input("classes_notes.$classeId");
                }
                DB::table('professeurs_classes')->insert($payload);
            }
        }
    }

    private function syncMpiasa(array $data, string $type, ?string $oldEmail = null): void
    {
        $payload = [
            'nom' => $data['nom'],
            'prenom' => $data['prenom'],
            'email' => $data['email'],
            'telephone' => $data['telephone'],
            'type_personne' => $type,
            'annee_scolaire' => $data['annee_scolaire'],
        ];
        if ($oldEmail && DB::table('mpiasa')->where('email', $oldEmail)->exists()) {
            DB::table('mpiasa')->where('email', $oldEmail)->update($payload);
            return;
        }
        DB::table('mpiasa')->insert($payload);
    }

    private function syncUserAccount(array $data, string $role, ?string $oldEmail = null): void
    {
        $email = $data['email'];
        $user = $oldEmail
            ? DB::table('utilisateurs')->where('email', $oldEmail)->first()
            : DB::table('utilisateurs')->where('email', $email)->first();

        if (! $user) {
            $user = DB::table('utilisateurs')->where('email', $email)->first();
        }

        if ($user && ! in_array($user->role, [$role], true)) {
            throw ValidationException::withMessages([
                'email' => 'Cet email appartient deja a un compte '.$user->role.'.',
            ]);
        }
        if ($user && $oldEmail && $email !== $oldEmail) {
            $duplicate = DB::table('utilisateurs')->where('email', $email)->where('id', '!=', $user->id)->exists();
            if ($duplicate) {
                throw ValidationException::withMessages([
                    'email' => 'Cet email est deja utilise par un autre compte.',
                ]);
            }
        }

        $payload = [
            'nom' => trim($data['nom'].' '.$data['prenom']),
            'email' => $email,
            'role' => $role,
        ];

        if (! empty($data['mot_de_passe'])) {
            $payload['mot_de_passe'] = Hash::make($data['mot_de_passe']);
        }

        if (! empty($data['photo'])) {
            $payload['avatar'] = $data['photo'];
        }

        if ($user) {
            DB::table('utilisateurs')->where('id', $user->id)->update($payload);
            $userId = (int) $user->id;
        } else {
            $userId = DB::table('utilisateurs')->insertGetId($payload + [
                'mot_de_passe' => Hash::make($data['mot_de_passe'] ?: $data['telephone']),
                'avatar' => $data['photo'] ?? 'images/default-avatar.png',
                'cree_le' => now(),
            ]);
        }

        $this->ensureDefaultPermissions($userId, $role);
    }

    private function ensureDefaultPermissions(int $userId, string $role): void
    {
        $existing = DB::table('permissions')->where('utilisateur_id', $userId)->exists();
        if ($existing) {
            return;
        }

        $defaults = $role === 'enseignant'
            ? [
                'notes' => 'ecriture',
                'bulletin' => 'lecture',
                'resultats' => 'lecture',
                'emploi_temps' => 'lecture',
                'calendrier' => 'lecture',
                'notifications' => 'lecture',
                'chat_private' => 'ecriture',
                'chat_group' => 'ecriture',
                'rapport_presence' => 'lecture',
            ]
            : [
                'notifications' => 'lecture',
                'chat_private' => 'ecriture',
                'chat_group' => 'ecriture',
                'presence_staff' => 'lecture',
                'gestion_ressource' => 'lecture',
            ];

        foreach (array_keys(config('novaskol.modules')) as $module) {
            DB::table('permissions')->insert([
                'utilisateur_id' => $userId,
                'role' => $role,
                'module' => $module,
                'acces' => $defaults[$module] ?? 'masquer',
            ]);
        }
    }

    private function usersWithoutProfile(string $role, string $profileTable)
    {
        return DB::table('utilisateurs as u')
            ->leftJoin($profileTable.' as p', 'p.email', '=', 'u.email')
            ->where('u.role', $role)
            ->whereNull('p.id')
            ->select('u.id', 'u.nom', 'u.email', 'u.role')
            ->orderBy('u.nom')
            ->get();
    }

    private function rejectUsedEmail(string $email, ?string $allowedEmail = null, ?string $role = null, ?string $profileTable = null): void
    {
        $mpiasaExists = DB::table('mpiasa')
            ->where('email', $email)
            ->when($allowedEmail, fn ($q) => $q->where('email', '!=', $allowedEmail))
            ->exists();

        if ($mpiasaExists) {
            throw ValidationException::withMessages([
                'email' => 'Cet email est deja utilise.',
            ]);
        }

        if ($profileTable) {
            $profileExists = DB::table($profileTable)
                ->where('email', $email)
                ->when($allowedEmail, fn ($q) => $q->where('email', '!=', $allowedEmail))
                ->exists();

            if ($profileExists) {
                throw ValidationException::withMessages([
                    'email' => 'Une fiche existe deja avec cet email.',
                ]);
            }
        }

        $user = DB::table('utilisateurs')
            ->where('email', $email)
            ->when($allowedEmail, fn ($q) => $q->where('email', '!=', $allowedEmail))
            ->first();

        if ($user && $role && $user->role !== $role) {
            throw ValidationException::withMessages([
                'email' => 'Cet email appartient deja a un compte '.$user->role.'.',
            ]);
        }
    }

    private function uploadPhoto(Request $request, string $prefix, ?string $existing = null): ?string
    {
        if (! $request->hasFile('photo')) {
            return $existing;
        }
        $file = $request->file('photo');
        $dir = public_path('legacy/images');
        if (!is_dir($dir)) mkdir($dir, 0755, true);
        $name = $prefix.Str::random(16).'.'.$file->getClientOriginalExtension();
        $file->move(public_path('legacy/images'), $name);
        $this->deleteLegacyPhoto($existing);
        return 'images/'.$name;
    }

    private function deleteLegacyPhoto(?string $path): void
    {
        if (! $path) {
            return;
        }
        $full = public_path('legacy/'.ltrim($path, '/\\'));
        if (File::exists($full)) {
            File::delete($full);
        }
    }

    private function presenceQuery(array $data): array
    {
        return ['annee_scolaire' => $data['annee_scolaire'], 'mois' => $data['mois'], 'jour' => $data['date_jour']];
    }

    private function schoolYearsFrom(string $table)
    {
        return DB::table($table)->whereNotNull('annee_scolaire')->where('annee_scolaire', '!=', '')->distinct()->orderByDesc('annee_scolaire')->pluck('annee_scolaire');
    }

    private function canManagePresenceRecords(): bool
    {
        $role = session('utilisateur.role');
        if ($role === 'admin') {
            return true;
        }

        if ($role !== 'staff') {
            return false;
        }

        $email = (string) session('utilisateur.email', '');
        $staff = DB::table('staff as s')
            ->leftJoin('roles as r', 'r.id', '=', 's.role_id')
            ->leftJoin('departements as d', 'd.id', '=', 's.departement_id')
            ->where('s.email', $email)
            ->select('r.nom as role_nom', 'd.nom as departement_nom')
            ->first();

        $staffRole = mb_strtolower((string) ($staff->role_nom ?? ''));
        $department = mb_strtolower((string) ($staff->departement_nom ?? ''));

        return str_contains($staffRole, 'rh') && str_contains($department, 'administration');
    }

    private function notify(string $type, string $message, ?int $destinataireId = null): void
    {
        DB::table('notifications')->insert([
            'type' => $type,
            'message' => $message,
            'destinataire_id' => $destinataireId,
            'date_creation' => now(),
            'statut' => 'non lu',
            'date_envoi' => now(),
            'lu' => 0,
            'user_type' => session('utilisateur.role', 'admin'),
            'user_id' => session('utilisateur.id', 0),
            'titre' => $type,
        ]);
    }

    private function userPermissions(): array
    {
        $userId = (int) session('utilisateur.id', 0);
        return $userId > 0 ? DB::table('permissions')->where('utilisateur_id', $userId)->pluck('acces', 'module')->all() : [];
    }

    private function school(): object
    {
        return DB::table('ecole')->select('nom', 'logo')->first() ?: (object) ['nom' => 'Ecole', 'logo' => 'logo.png'];
    }

    private function ensureSession(array $roles): void
    {
        abort_unless(session()->has('utilisateur') && in_array(session('utilisateur.role'), $roles, true), 403);
    }

    private function ensurePresenceIndexes(): void
    {
        if (session()->has('_idx_presence_ok')) return;
        if (DB::getDriverName() !== 'sqlite') return;
        try {
            foreach (['presence_personnels', 'presence_staff'] as $table) {
                $existing = DB::select("SELECT name FROM pragma_index_list('$table')");
                $names = array_map(fn ($i) => $i->name, $existing);
                if (!in_array($table.'_personne_idx', $names, true)) {
                    DB::statement("CREATE INDEX IF NOT EXISTS {$table}_personne_idx ON $table(personne_id)");
                    DB::statement("CREATE INDEX IF NOT EXISTS {$table}_date_idx ON $table(date_jour)");
                    DB::statement("CREATE INDEX IF NOT EXISTS {$table}_annee_idx ON $table(annee_scolaire, mois)");
                }
            }
            session()->put('_idx_presence_ok', true);
        } catch (\Throwable $e) {}
    }
}
