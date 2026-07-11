<?php

namespace App\Http\Controllers;

use App\Services\Novaskol\ModuleRegistry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ScheduleController extends Controller
{
    private array $days = ['lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi'];

    public function index(Request $request, ModuleRegistry $modules)
    {
        $this->ensureSession();

        $classes = $this->classesForUser();
        $classeId = (int) $request->query('classe', 0);
        if (session('utilisateur.role') === 'parent') {
            $allowedClassIds = $classes->pluck('id')->map(fn ($id) => (int) $id)->all();
            if ($classeId <= 0) {
                $classeId = (int) ($classes->first()->id ?? 0);
            } elseif (! in_array($classeId, $allowedClassIds, true)) {
                abort(403, 'Acces refuse a cet emploi du temps.');
            }
        }
        $schedule = $this->scheduleForClass($classeId);

        return view('modules.pedagogique.emploi-du-temps.index', [
            'modules' => $modules->all(),
            'userPermissions' => $this->userPermissions(),
            'ecole' => $this->school(),
            'classes' => $classes,
            'selectedClasse' => $classeId,
            'classeNom' => DB::table('classes')->where('id', $classeId)->value('nom') ?: '',
            'emploi' => $schedule,
            'jours' => $this->days,
            'canWrite' => $this->canWriteModule('emploi_temps'),
        ]);
    }

    public function store(Request $request)
    {
        $this->ensureSession();

        $data = $request->validate([
            'classe' => ['required', 'integer', 'exists:classes,id'],
            'emploi' => ['array'],
        ]);

        $classeId = (int) $data['classe'];
        $slots = $this->cleanSchedule($request->input('emploi', []));
        $classeNom = DB::table('classes')->where('id', $classeId)->value('nom') ?: 'Inconnue';
        $now = now();

        DB::transaction(function () use ($classeId, $slots, $classeNom, $now) {
            DB::table('emploi_du_temps')->where('classe_id', $classeId)->delete();

            $inserted = 0;
            foreach ($slots as $slot) {
                preg_match('/^(\d{2})h(\d{2})-(\d{2})h(\d{2})$/', $slot['heure'], $m);
                if (!$m) continue;
                $heureDebut = $m[1] . ':' . $m[2];
                $heureFin = $m[3] . ':' . $m[4];

                foreach ($this->days as $day) {
                    $matiereNom = trim((string) ($slot[$day] ?? ''));
                    if ($matiereNom === '') continue;

                    $matiereId = DB::table('matieres')->whereRaw('LOWER(nom) = ?', [mb_strtolower($matiereNom)])->value('id');
                    if (!$matiereId) {
                        $matiereId = DB::table('matieres')->insertGetId([
                            'nom' => $matiereNom,
                            'created_at' => $now,
                            'updated_at' => $now,
                        ]);
                    }

                    $anneeScolaire = $this->currentSchoolYear();

                    $profId = DB::table('professeurs_classes')
                        ->where('classe_id', $classeId)
                        ->where('matiere_id', $matiereId)
                        ->where('annee_scolaire', $anneeScolaire)
                        ->value('professeur_id');

                    DB::table('emploi_du_temps')->insert([
                        'classe_id' => $classeId,
                        'matiere_id' => $matiereId,
                        'professeur_id' => $profId,
                        'jour' => $day,
                        'heure_debut' => $heureDebut,
                        'heure_fin' => $heureFin,
                        'annee_scolaire' => $anneeScolaire,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);
                    $inserted++;
                }
            }

            // Notification EDT (structure de table variable, ignoree)
        });

        if (empty($slots)) {
            return redirect()->route('modules.emploi-temps', ['classe' => $classeId])
                ->with('schedule_msg', ['type' => 'warning', 'title' => 'Attention', 'text' => "Aucun creneau valide n'a ete saisi."]);
        }

        return redirect()->route('modules.emploi-temps', ['classe' => $classeId])
            ->with('schedule_msg', ['type' => 'success', 'title' => 'Succes', 'text' => "Emploi du temps sauvegarde avec succes pour la classe {$classeNom}."]);
    }

    private function cleanSchedule(array $schedule): array
    {
        $clean = [];

        foreach ($schedule as $index => $slot) {
            $start = (string) ($slot['heure_debut'] ?? '');
            $end = (string) ($slot['heure_fin'] ?? '');

            if (! preg_match('/^\d{2}:\d{2}$/', $start) || ! preg_match('/^\d{2}:\d{2}$/', $end)) {
                continue;
            }

            $row = [
                'heure' => str_replace(':', 'h', $start).'-'.str_replace(':', 'h', $end),
            ];

            foreach ($this->days as $day) {
                $row[$day] = trim((string) ($slot[$day] ?? ''));
            }

            $clean[$index] = $row;
        }

        return $clean;
    }

    private function scheduleForClass(int $classeId): array
    {
        if ($classeId <= 0) {
            return [];
        }

        $rows = DB::table('emploi_du_temps as e')
            ->leftJoin('matieres as m', 'e.matiere_id', '=', 'm.id')
            ->where('e.classe_id', $classeId)
            ->orderBy('e.heure_debut')
            ->orderBy('e.jour')
            ->get(['e.*', 'm.nom as matiere_nom']);

        if ($rows->isEmpty()) {
            return [];
        }

        $grouped = [];
        foreach ($rows as $row) {
            $key = $row->heure_debut . '-' . $row->heure_fin;
            if (!isset($grouped[$key])) {
                $grouped[$key] = [
                    'heure' => str_replace(':', 'h', $row->heure_debut) . '-' . str_replace(':', 'h', $row->heure_fin),
                    'order' => $row->heure_debut,
                ];
                foreach ($this->days as $day) {
                    $grouped[$key][$day] = '';
                }
            }
            $grouped[$key][$row->jour] = $row->matiere_nom ?? '';
        }

        usort($grouped, fn($a, $b) => $a['order'] <=> $b['order']);

        return array_values($grouped);
    }

    private function classesForUser()
    {
        if (session('utilisateur.role') !== 'parent') {
            return DB::table('classes')->select('id', 'nom')->orderBy('nom')->get();
        }

        return DB::table('parent_eleves as pe')
            ->join('eleves as e', 'e.id', '=', 'pe.eleve_id')
            ->join('classes as c', 'c.id', '=', 'e.id_classe')
            ->where('pe.parent_user_id', (int) session('utilisateur.id'))
            ->select('c.id', 'c.nom')
            ->distinct()
            ->orderBy('c.nom')
            ->get();
    }

    private function userPermissions(): array
    {
        if (! session('utilisateur.id')) {
            return [];
        }

        return DB::table('permissions')
            ->where('utilisateur_id', session('utilisateur.id'))
            ->pluck('acces', 'module')
            ->all();
    }

    private function canWriteModule(string $module): bool
    {
        if (session('utilisateur.role') === 'admin') {
            return true;
        }

        return ($this->userPermissions()[$module] ?? null) === 'ecriture';
    }

    private function currentSchoolYear(): string
    {
        $now = now();
        $year = (int) $now->format('Y');
        $month = (int) $now->format('n');
        if ($month >= 9) {
            return $year . '-' . ($year + 1);
        }
        return ($year - 1) . '-' . $year;
    }

    private function ensureSession(): void
    {
        abort_unless(session()->has('utilisateur') && in_array(session('utilisateur.role'), ['admin', 'enseignant', 'parent', 'staff'], true), 403);
    }

    private function school(): object
    {
        return DB::table('ecole')->select('nom', 'logo')->first() ?: (object) [
            'nom' => 'Ecole',
            'logo' => 'logo.png',
        ];
    }
}
