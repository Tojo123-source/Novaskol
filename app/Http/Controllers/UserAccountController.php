<?php

namespace App\Http\Controllers;

use App\Services\Novaskol\ModuleRegistry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserAccountController extends Controller
{
    public function index(Request $request, ModuleRegistry $modules)
    {
        $this->ensureAdmin();
        $this->ensureIndexes();

        $role = (string) $request->query('role', '');
        $search = trim((string) $request->query('q', ''));

        $orderExpr = DB::getDriverName() === 'sqlite'
            ? "CASE u.role WHEN 'parent' THEN 1 WHEN 'enseignant' THEN 2 WHEN 'staff' THEN 3 WHEN 'admin' THEN 4 ELSE 5 END"
            : "FIELD(u.role, 'parent', 'enseignant', 'staff', 'admin')";

        $accounts = DB::table('utilisateurs as u')
            ->leftJoin('parent_eleves as pe', 'pe.parent_user_id', '=', 'u.id')
            ->leftJoin('eleves as e', 'e.id', '=', 'pe.eleve_id')
            ->select(
                'u.id',
                'u.nom',
                'u.email',
                'u.role',
                'u.avatar',
                'u.cree_le',
                'u.last_activity',
                DB::raw('COUNT(DISTINCT pe.eleve_id) as enfants_count')
            )
            ->when($role !== '', fn ($query) => $query->where('u.role', $role))
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('u.nom', 'like', "%{$search}%")
                        ->orWhere('u.email', 'like', "%{$search}%")
                        ->orWhere('u.role', 'like', "%{$search}%")
                        ->orWhere('e.nom', 'like', "%{$search}%")
                        ->orWhere('e.prenom', 'like', "%{$search}%");
                });
            })
            ->groupBy('u.id', 'u.nom', 'u.email', 'u.role', 'u.avatar', 'u.cree_le', 'u.last_activity')
            ->orderByRaw($orderExpr)
            ->orderBy('u.nom')
            ->paginate(24)
            ->withQueryString();

        // Fetch enfants list separately to avoid SQLite GROUP_CONCAT limitations
        $parentIds = $accounts->where('role', 'parent')->pluck('id');
        $enfantsMap = [];
        if ($parentIds->isNotEmpty()) {
            $raw = DB::table('parent_eleves as pe')
                ->join('eleves as e', 'e.id', '=', 'pe.eleve_id')
                ->whereIn('pe.parent_user_id', $parentIds)
                ->select('pe.parent_user_id', DB::raw("e.prenom || ' ' || e.nom as enfant_name"))
                ->get();
            foreach ($raw as $row) {
                $enfantsMap[$row->parent_user_id][] = $row->enfant_name;
            }
        }
        $accounts->each(function ($account) use ($enfantsMap) {
            $account->enfants = isset($enfantsMap[$account->id]) ? implode(', ', $enfantsMap[$account->id]) : null;
        });

        $stats = DB::table('utilisateurs')
            ->select('role', DB::raw('COUNT(*) as total'))
            ->groupBy('role')
            ->pluck('total', 'role');

        return view('modules.parametres.accounts', [
            'modules' => $modules->all(),
            'userPermissions' => $this->userPermissions(),
            'ecole' => $this->school(),
            'activeModule' => 'comptes_utilisateurs',
            'accounts' => $accounts,
            'stats' => $stats,
            'filters' => compact('role', 'search'),
        ]);
    }

    public function destroy(int $id)
    {
        $this->ensureAdmin();
        abort_if($id === (int) session('utilisateur.id'), 422, 'Impossible de supprimer votre propre compte pendant la session.');

        $user = DB::table('utilisateurs')->where('id', $id)->first();
        abort_unless($user, 404);
        abort_if($user->role === 'admin', 422, 'Les comptes administrateurs doivent etre geres manuellement pour eviter une suppression critique.');

        DB::transaction(function () use ($user, $id) {
            $privateConversationIds = DB::table('conversation_participants as cp')
                ->join('conversations as c', 'c.id', '=', 'cp.conversation_id')
                ->where('cp.user_id', $id)
                ->where('cp.user_type', $user->role)
                ->where('c.type', 'private')
                ->pluck('c.id')
                ->map(fn ($value) => (int) $value)
                ->all();

            if (! empty($privateConversationIds)) {
                DB::table('messages')->whereIn('conversation_id', $privateConversationIds)->delete();
                DB::table('conversation_participants')->whereIn('conversation_id', $privateConversationIds)->delete();
                DB::table('conversations')->whereIn('id', $privateConversationIds)->delete();
            }

            DB::table('messages')
                ->where('sender_id', $id)
                ->where('sender_type', $user->role)
                ->delete();

            DB::table('conversation_participants')
                ->where('user_id', $id)
                ->where('user_type', $user->role)
                ->delete();

            if ($user->role === 'parent') {
                DB::table('parent_eleves')->where('parent_user_id', $id)->delete();
            }

            DB::table('permissions')->where('utilisateur_id', $id)->delete();
            DB::table('utilisateurs')->where('id', $id)->delete();
        });

        return redirect()->route('modules.comptes-utilisateurs')->with('success', 'Compte supprime proprement.');
    }

    private function ensureIndexes(): void
    {
        if (DB::getDriverName() !== 'sqlite') return;
        try {
            $idx = DB::select("SELECT name FROM pragma_index_list('parent_eleves')");
            $names = array_map(fn ($i) => $i->name, $idx);
            if (!in_array('pe_user_idx', $names, true)) {
                DB::statement('CREATE INDEX IF NOT EXISTS pe_user_idx ON parent_eleves(parent_user_id)');
                DB::statement('CREATE INDEX IF NOT EXISTS pe_eleve_idx ON parent_eleves(eleve_id)');
            }
        } catch (\Throwable $e) {}
    }

    private function ensureAdmin(): void
    {
        abort_unless(session()->has('utilisateur') && session('utilisateur.role') === 'admin', 403);
    }

    private function userPermissions(): array
    {
        $id = (int) session('utilisateur.id', 0);
        return $id ? DB::table('permissions')->where('utilisateur_id', $id)->pluck('acces', 'module')->all() : [];
    }

    private function school(): object
    {
        return DB::table('ecole')->select('id', 'nom', 'logo')->first() ?: (object) ['id' => 1, 'nom' => 'Ecole', 'logo' => 'novaskol.png'];
    }
}
