<?php

namespace App\Http\Controllers;

use App\Services\Novaskol\ModuleRegistry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ParentPortalController extends Controller
{
    public function __invoke(Request $request, ModuleRegistry $modules)
    {
        $user = session('utilisateur');
        abort_unless($user && ($user['role'] ?? '') === 'parent', 403);

        $children = $this->children((int) $user['id']);
        $selectedId = (int) $request->query('eleve', $children->first()->id ?? 0);
        $child = $children->firstWhere('id', $selectedId) ?: $children->first();

        $month = (int) $request->query('mois', now()->month);
        $year = (int) $request->query('annee', now()->year);

        return view('parents.portal', [
            'activeModule' => 'parent_portal',
            'modules' => $modules->all(),
            'userPermissions' => [],
            'ecole' => $this->school(),
            'user' => $user,
            'children' => $children,
            'child' => $child,
            'calMonth' => $month,
            'calYear' => $year,
            'attendance' => $child ? $this->attendanceCalendar((int) $child->id, $month, $year) : [],
            'notes' => $child ? $this->notes((int) $child->id) : collect(),
            'paymentsDue' => $child ? $this->paymentsDue((int) $child->id) : collect(),
            'paymentsDone' => $child ? $this->paymentsDone((int) $child->id) : collect(),
            'events' => DB::table('evenements')->where('date_fin', '>=', now())->orderBy('date_debut')->limit(6)->get(),
            'notifications' => DB::table('notifications')
                ->where('destinataire_id', (int) $user['id'])
                ->orderByDesc('date_creation')
                ->limit(6)
                ->get(),
        ]);
    }

    private function children(int $userId)
    {
        return DB::table('parent_eleves as pe')
            ->join('eleves as e', 'e.id', '=', 'pe.eleve_id')
            ->leftJoin('classes as c', 'c.id', '=', 'e.id_classe')
            ->select('e.*', 'c.nom as classe_nom', 'pe.lien', 'pe.nom_contact')
            ->where('pe.parent_user_id', $userId)
            ->orderBy('e.nom')
            ->orderBy('e.prenom')
            ->get();
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

    private function attendanceCalendar(int $studentId, int $month, int $year): array
    {
        $child = DB::table('eleves')->where('id', $studentId)->first();
        $classeId = $child->id_classe ?? 0;

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

    private function paymentsDue(int $studentId)
    {
        return DB::table('paiements_assignes as pa')
            ->leftJoin('types_paiements as tp', 'tp.id', '=', 'pa.type_id')
            ->select('pa.*', 'tp.nom', 'tp.montant')
            ->where('pa.eleve_id', $studentId)
            ->where('pa.statut', '!=', 'paye')
            ->orderByDesc('pa.id')
            ->limit(20)
            ->get();
    }

    private function paymentsDone(int $studentId)
    {
        return DB::table('revenus')
            ->where('personne_id', (string) $studentId)
            ->whereIn('type_personne', ['eleve', 'etudiant', 'étudiant'])
            ->orderByDesc('date_enregistrement')
            ->limit(20)
            ->get();
    }

    private function school(): object
    {
        return DB::table('ecole')->select('nom', 'logo')->first() ?: (object) [
            'nom' => 'Ecole',
            'logo' => 'logo.png',
        ];
    }
}
