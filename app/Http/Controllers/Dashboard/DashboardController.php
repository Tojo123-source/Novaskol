<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Services\Novaskol\ModuleRegistry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
    public function __invoke(Request $request, ModuleRegistry $modules)
    {
        if (! session()->has('utilisateur')) {
            return redirect()->route('login');
        }

        if (session('utilisateur.role') !== 'admin') {
            return redirect()->route('role.dashboard');
        }

        $this->touchUserActivity();

        $isConnected = config('novaskol.edition', 'principal') === 'connecte'
            || File::exists(env('CONNECTED_PAIRED_PATH', storage_path('app/connected/paired.json')));

        $annees = $this->safeQuery('eleves', fn () => DB::table('eleves')
            ->select('annee_scolaire')
            ->distinct()
            ->orderByDesc('annee_scolaire')
            ->get()
            ->map(fn ($row) => ['annee_scolaire' => $row->annee_scolaire])
            ->all(), []);

        $anneeScolaire = $request->query('annee_scolaire')
            ?: ($annees[0]['annee_scolaire'] ?? date('Y').'-'.(date('Y') + 1));

        $mois = ['Jan', 'Fev', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Aou', 'Sep', 'Oct', 'Nov', 'Dec'];
        $revenusMensuels = [];
        $depensesMensuelles = [];

        $hasRevenus = Schema::hasTable('revenus');
        $hasDepenses = Schema::hasTable('depenses');

        foreach (range(1, 12) as $monthNumber) {
            $revenusMensuels[] = $hasRevenus ? (float) DB::table('revenus')
                ->where('annee_scolaire', $anneeScolaire)
                ->where(function ($query) use ($monthNumber) {
                    foreach ($this->monthAliases($monthNumber) as $alias) {
                        $query->orWhere('mois', 'like', "%{$alias}%");
                    }
                })
                ->sum('montant') : 0;

            $depensesMensuelles[] = $hasDepenses ? (float) DB::table('depenses')
                ->where('annee_scolaire', $anneeScolaire)
                ->where(function ($query) use ($monthNumber) {
                    foreach ($this->monthAliases($monthNumber) as $alias) {
                        $query->orWhere('mois', 'like', "%{$alias}%");
                    }
                })
                ->sum('montant') : 0;
        }

        $totalRevenus = array_sum($revenusMensuels);
        $totalDepenses = array_sum($depensesMensuelles);

        return view('dashboard.index', [
            'modules' => $modules->all(),
            'user' => session('utilisateur'),
            'userPermissions' => $this->userPermissions(),
            'ecole' => $this->school(),
            'annees' => $annees,
            'anneeScolaire' => $anneeScolaire,
            'totaleleves' => $this->safeCount('eleves', $anneeScolaire),
            'totalparent' => $this->safeCount('parents', $anneeScolaire),
            'totalenseignants' => $this->safeCount('professeurs', $anneeScolaire),
            'totalstaff' => $this->safeQuery('staff', fn () => DB::table('staff')
                ->where('annee_scolaire', $anneeScolaire)
                ->where(function ($q) {
                    $q->whereNull('poste')->orWhere('poste', '!=', 'Enseignant');
                })
                ->count(), 0),
            'presenceToday' => (Schema::hasColumn('presence_personnels', 'presence') && Schema::hasColumn('presence_personnels', 'date_jour')) ? $this->safeQuery('presence_personnels', fn () => round((float) (DB::table('presence_personnels')->whereDate('date_jour', today())->avg('presence') ?? 0) * 100, 1), 0) : 0,
            'impayeCount' => $this->safeQuery('paiements', fn () => DB::table('paiements')->where('statut', '!=', 'complet')->where('annee_scolaire', $anneeScolaire)->count(), 0),
            'revenuMois' => $hasRevenus ? (float) DB::table('revenus')
                ->where('annee_scolaire', $anneeScolaire)
                ->where(function ($query) {
                    foreach ($this->monthAliases((int) now()->format('n')) as $alias) {
                        $query->orWhere('mois', 'like', "%{$alias}%");
                    }
                })
                ->sum('montant') : 0,
            'totalRevenus' => $totalRevenus,
            'totalDepenses' => $totalDepenses,
            'benefice' => $totalRevenus - $totalDepenses,
            'prochainEvent' => $this->safeQuery('evenements', fn () => DB::table('evenements')->whereDate('date_debut', '>=', today())->orderBy('date_debut')->first(), null),
            'notifications' => $this->safeQuery('notifications', fn () => DB::table('notifications')->orderByDesc('date_creation')->limit(10)->get(), collect()),
            'unreadCount' => $this->unreadNotificationsCount(),
            'isConnected' => $isConnected,
            'mois' => $mois,
            'revenusMensuels' => $revenusMensuels,
            'depensesMensuelles' => $depensesMensuelles,
        ]);
    }

    public function events()
    {
        if (! Schema::hasTable('evenements')) {
            return [];
        }

        return DB::table('evenements')
            ->select('id', 'titre as title', 'date_debut as start', 'date_fin as end', 'type', 'description')
            ->get();
    }

    public function storeEvent(Request $request)
    {
        $this->ensureEventTypeColumnIsFlexible();

        $data = $request->validate([
            'titre' => ['required', 'string', 'max:255'],
            'type' => ['required', 'string', 'max:100'],
            'date_debut' => ['required', 'date'],
            'date_fin' => ['required', 'date'],
            'description' => ['nullable', 'string'],
        ]);

        $id = DB::table('evenements')->insertGetId([
            'titre' => $data['titre'],
            'type' => $this->normalizeEventType($data['type']),
            'date_debut' => \Carbon\Carbon::parse($data['date_debut'])->format('Y-m-d H:i:s'),
            'date_fin' => \Carbon\Carbon::parse($data['date_fin'])->format('Y-m-d H:i:s'),
            'description' => $data['description'] ?? '',
            'createur_id' => session('utilisateur.id', 0),
            'annee_scolaire' => $this->currentSchoolYear(),
        ]);

        return [
            'success' => true,
            'event' => [
                'id' => $id,
                'title' => $data['titre'],
                'start' => \Carbon\Carbon::parse($data['date_debut'])->format('Y-m-d H:i:s'),
                'end' => \Carbon\Carbon::parse($data['date_fin'])->format('Y-m-d H:i:s'),
                'type' => $this->normalizeEventType($data['type']),
                'description' => $data['description'] ?? '',
            ],
        ];
    }

    private function normalizeEventType(string $type): string
    {
        return match (strtolower(trim($type))) {
            'réunion' => 'reunion',
            'évènement scolaire', 'événement scolaire' => 'evenement scolaire',
            default => strtolower(trim($type)),
        };
    }

    private function ensureEventTypeColumnIsFlexible(): void
    {
        if (DB::getDriverName() === 'mysql' && Schema::hasTable('evenements')) {
            DB::statement("ALTER TABLE `evenements` MODIFY `type` varchar(100) NOT NULL");
        }
    }

    public function markNotificationRead(int $id)
    {
        if (! Schema::hasTable('notifications')) {
            return ['success' => false, 'new_count' => 0];
        }

        DB::table('notifications')->where('id', $id)->update(['lu' => 1, 'statut' => 'lu']);

        return ['success' => true, 'new_count' => $this->unreadNotificationsCount()];
    }

    public function deleteNotification(int $id)
    {
        if (! Schema::hasTable('notifications')) {
            return ['success' => false, 'new_count' => 0];
        }

        DB::table('notifications')->where('id', $id)->delete();

        return ['success' => true, 'new_count' => $this->unreadNotificationsCount()];
    }

    public function markAllNotificationsRead()
    {
        if (! Schema::hasTable('notifications')) {
            return ['success' => false, 'new_count' => 0];
        }

        DB::table('notifications')->where(function ($query) {
            $query->where('lu', 0)->orWhere('statut', 'non lu');
        })->update(['lu' => 1, 'statut' => 'lu']);

        return ['success' => true, 'new_count' => 0];
    }

    private function unreadNotificationsCount(): int
    {
        if (! Schema::hasTable('notifications')) {
            return 0;
        }

        return DB::table('notifications')
            ->where(function ($query) {
                $query->where('lu', 0)->orWhere('statut', 'non lu');
            })
            ->count();
    }

    public function pollNotifications()
    {
        if (! Schema::hasTable('notifications')) {
            return response()->json(['notifications' => collect(), 'unread' => 0]);
        }

        $currentUserId = (int) session('utilisateur.id', 0);
        $latest = DB::table('notifications')
            ->when(DB::getSchemaBuilder()->hasColumn('notifications', 'destinataire_id'), fn ($q) => $q->where(fn ($n) => $n->whereNull('destinataire_id')->orWhere('destinataire_id', $currentUserId)))
            ->orderByDesc('date_creation')
            ->limit(10)
            ->get();
        $unread = DB::table('notifications')
            ->when(DB::getSchemaBuilder()->hasColumn('notifications', 'destinataire_id'), fn ($q) => $q->where(fn ($n) => $n->whereNull('destinataire_id')->orWhere('destinataire_id', $currentUserId)))
            ->where(fn ($q) => $q->where('lu', 0)->orWhere('statut', 'non lu'))
            ->count();

        return response()->json(['notifications' => $latest, 'unread' => $unread]);
    }

    private function touchUserActivity(): void
    {
        if (session('utilisateur.id') && Schema::hasTable('utilisateurs')) {
            DB::table('utilisateurs')
                ->where('id', session('utilisateur.id'))
                ->update(['last_activity' => now()]);
        }
    }

    private function userPermissions(): array
    {
        if (! session('utilisateur.id') || ! Schema::hasTable('permissions')) {
            return [];
        }

        return DB::table('permissions')
            ->where('utilisateur_id', session('utilisateur.id'))
            ->pluck('acces', 'module')
            ->all();
    }

    private function school(): object
    {
        if (! Schema::hasTable('ecole')) {
            return (object) ['nom' => 'Ecole', 'logo' => 'novaskol.png'];
        }

        return DB::table('ecole')->select('nom', 'logo')->first() ?: (object) [
            'nom' => 'Ecole',
            'logo' => 'novaskol.png',
        ];
    }

    private function safeQuery(string $table, callable $query, mixed $default = null): mixed
    {
        if (! Schema::hasTable($table)) {
            return $default instanceof \Closure ? $default() : $default;
        }

        return $query();
    }

    private function safeCount(string $table, string $anneeScolaire): int
    {
        if (! Schema::hasTable($table)) {
            return 0;
        }

        return DB::table($table)->where('annee_scolaire', $anneeScolaire)->count();
    }

    private function monthAliases(int $month): array
    {
        return match ($month) {
            1 => ['01', '1', 'Jan', 'Janvier', 'January'],
            2 => ['02', '2', 'Fev', 'Fevrier', 'Fev.', 'Fév', 'Février', 'February'],
            3 => ['03', '3', 'Mar', 'Mars', 'March'],
            4 => ['04', '4', 'Avr', 'Avril', 'April'],
            5 => ['05', '5', 'Mai', 'May'],
            6 => ['06', '6', 'Juin', 'June'],
            7 => ['07', '7', 'Juil', 'Juillet', 'July'],
            8 => ['08', '8', 'Aou', 'Aout', 'Août', 'August'],
            9 => ['09', '9', 'Sep', 'Sept', 'Septembre', 'September'],
            10 => ['10', 'Oct', 'Octobre', 'October'],
            11 => ['11', 'Nov', 'Novembre', 'November'],
            12 => ['12', 'Dec', 'Decembre', 'Déc', 'Décembre', 'December'],
            default => [(string) $month],
        };
    }

    private function schoolYears()
    {
        if (! Schema::hasTable('parametres')) {
            return collect();
        }

        return DB::table('parametres')
            ->where('cle', 'like', '%annee_scolaire%')
            ->orWhere('cle', 'annee_scolaire')
            ->pluck('valeur')
            ->unique()
            ->sortDesc()
            ->values();
    }

    private function currentSchoolYear(): string
    {
        return (string) ($this->schoolYears()->first() ?: now()->format('Y').'-'.(now()->year + 1));
    }
}
