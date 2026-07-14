<?php

namespace App\Http\Controllers;

use App\Services\Novaskol\ModuleRegistry;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ReportController extends Controller
{
    private array $months = ['Janvier', 'Fevrier', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Aout', 'Septembre', 'Octobre', 'Novembre', 'Decembre'];

    public function accounting(Request $request, ModuleRegistry $modules)
    {
        $this->ensureSession();
        $annee = (string) $request->query('annee_scolaire', '');
        $month = (string) $request->query('mois', '');
        $revenusQuery = DB::table('revenus')
            ->when($annee, fn ($q) => $q->where('annee_scolaire', $annee))
            ->when($month !== '', fn ($q) => $q->where(function ($sub) use ($month) {
                foreach ($this->monthAliases($month) as $alias) {
                    $sub->orWhere('mois', 'like', "%{$alias}%");
                }
            }));
        $depensesQuery = DB::table('depenses')
            ->when($annee, fn ($q) => $q->where('annee_scolaire', $annee))
            ->when($month !== '', fn ($q) => $q->where(function ($sub) use ($month) {
                foreach ($this->monthAliases($month) as $alias) {
                    $sub->orWhere('mois', 'like', "%{$alias}%");
                }
            }));
        $totalRevenus = (float) (clone $revenusQuery)->sum('montant');
        $totalDepenses = (float) (clone $depensesQuery)->sum('montant');
        $monthlyRevenus = [];
        $monthlyDepenses = [];
        foreach ($this->months as $m) {
            $monthlyRevenus[] = (float) (clone $revenusQuery)->where(function ($query) use ($m) {
                foreach ($this->monthAliases($m) as $alias) {
                    $query->orWhere('mois', 'like', "%{$alias}%");
                }
            })->sum('montant');
            $monthlyDepenses[] = (float) (clone $depensesQuery)->where(function ($query) use ($m) {
                foreach ($this->monthAliases($m) as $alias) {
                    $query->orWhere('mois', 'like', "%{$alias}%");
                }
            })->sum('montant');
        }
        $weekStart = (string) $request->query('week_start', now()->startOfWeek()->toDateString());
        $weekLabels = [];
        $weeklyRevenus = [];
        $weeklyDepenses = [];
        for ($i = 0; $i < 7; $i++) {
            $day = Carbon::parse($weekStart)->addDays($i)->toDateString();
            $weekLabels[] = Carbon::parse($day)->format('d/m');
            $weeklyRevenus[] = (float) DB::table('revenus')->whereDate('date_enregistrement', $day)->when($annee, fn ($q) => $q->where('annee_scolaire', $annee))->sum('montant');
            $weeklyDepenses[] = (float) DB::table('depenses')->whereDate('date_enregistrement', $day)->when($annee, fn ($q) => $q->where('annee_scolaire', $annee))->sum('montant');
        }

        return $this->view('modules.reports.accounting', $modules, 'rapport_comptable', [
            'annees' => $this->years(),
            'selectedAnnee' => $annee,
            'months' => $this->months,
            'selectedMonth' => $month,
            'totalRevenus' => $totalRevenus,
            'totalDepenses' => $totalDepenses,
            'solde' => $totalRevenus - $totalDepenses,
            'monthlyRevenus' => $monthlyRevenus,
            'monthlyDepenses' => $monthlyDepenses,
            'weekStart' => $weekStart,
            'weekLabels' => $weekLabels,
            'weeklyRevenus' => $weeklyRevenus,
            'weeklyDepenses' => $weeklyDepenses,
            'revenusCategories' => (clone $revenusQuery)->select('categorie', DB::raw('SUM(montant) total'))->groupBy('categorie')->orderByDesc('total')->get(),
            'depensesCategories' => (clone $depensesQuery)->select('categorie', DB::raw('SUM(montant) total'))->groupBy('categorie')->orderByDesc('total')->get(),
        ]);
    }

    public function teacherPresence(Request $request, ModuleRegistry $modules)
    {
        $this->ensureSession();
        return $this->presenceReport($request, $modules, 'professeurs', 'presence_personnels', 'professeur', 'rapport_presence', 'Rapport professeur', 'horaire', 'salaire_horaire');
    }

    public function staffPresence(Request $request, ModuleRegistry $modules)
    {
        $this->ensureSession();
        return $this->presenceReport($request, $modules, 'staff', 'presence_staff', 'staff', 'rapport_staff', 'Rapport staff', 'jours', 'salaire_base');
    }

    public function grades(Request $request, ModuleRegistry $modules)
    {
        $this->ensureSession();
        $this->ensureNotesIndexes();
        $annee = (string) $request->query('annee_scolaire', $this->currentYear());
        $classeId = (int) $request->query('classe_id', 0);
        $studentId = (int) $request->query('eleve_id', 0);
        $classes = DB::table('classes')->select('id', 'nom')->orderBy('nom')->get();
        $studentsForFilter = DB::table('eleves')->select('id', 'nom', 'prenom', 'id_classe')->where('annee_scolaire', $annee)->when($classeId > 0, fn ($q) => $q->where('id_classe', $classeId))->orderBy('nom')->orderBy('prenom')->get();
        $studentsByClass = DB::table('eleves as e')->join('classes as c', 'c.id', '=', 'e.id_classe')->where('e.annee_scolaire', $annee)->when($classeId > 0, fn ($q) => $q->where('e.id_classe', $classeId))->groupBy('c.nom')->select('c.nom', DB::raw('COUNT(*) total'))->orderBy('c.nom')->get();
        $stuJoin = DB::raw('COALESCE(n.id_eleve, n.eleve_id)');
        $matJoin = DB::raw('COALESCE(n.id_matiere, n.matiere_id)');
        $noteCol = DB::raw('COALESCE(n.note, n.valeur)');
        $avgBySubject = DB::table('notes as n')->join('matieres as m', 'm.id', '=', $matJoin)->join('eleves as e', 'e.id', '=', $stuJoin)->where('n.annee_scolaire', $annee)->when($classeId > 0, fn ($q) => $q->where('e.id_classe', $classeId))->when($studentId > 0, fn ($q) => $q->where('e.id', $studentId))->groupBy('m.nom')->select('m.nom', DB::raw('AVG(COALESCE(n.note, n.valeur)) moyenne'))->orderBy('m.nom')->get();
        $avgByClass = DB::table('notes as n')->join('eleves as e', 'e.id', '=', $stuJoin)->join('classes as c', 'c.id', '=', 'e.id_classe')->where('n.annee_scolaire', $annee)->when($classeId > 0, fn ($q) => $q->where('e.id_classe', $classeId))->groupBy('c.nom')->select('c.nom', DB::raw('AVG(COALESCE(n.note, n.valeur)) moyenne'))->orderBy('c.nom')->get();
        $avgByClassPeriod = DB::table('notes as n')
            ->join('eleves as e', 'e.id', '=', $stuJoin)
            ->join('classes as c', 'c.id', '=', 'e.id_classe')
            ->where('n.annee_scolaire', $annee)
            ->when($classeId > 0, fn ($q) => $q->where('e.id_classe', $classeId))
            ->when($studentId > 0, fn ($q) => $q->where('e.id', $studentId))
            ->where(function ($q) { $q->whereIn('n.periode', ['B1', 'B2', 'T1', 'T2', 'T3'])->orWhereIn('n.trimestre', [1,2,3]); })
            ->groupBy('c.nom', 'n.periode')
            ->select('c.nom as classe', 'n.periode', DB::raw('AVG(COALESCE(n.note, n.valeur)) moyenne'))
            ->orderBy('c.nom')
            ->get();
        $topStudents = DB::table('notes as n')
            ->join('eleves as e', 'e.id', '=', $stuJoin)
            ->leftJoin('classes as c', 'c.id', '=', 'e.id_classe')
            ->where('n.annee_scolaire', $annee)
            ->when($classeId > 0, fn ($q) => $q->where('e.id_classe', $classeId))
            ->groupBy('e.id', 'e.nom', 'e.prenom', 'c.nom')
            ->select('e.id', 'e.nom', 'e.prenom', 'c.nom as classe', DB::raw('AVG(COALESCE(n.note, n.valeur)) moyenne'))
            ->orderByDesc('moyenne')
            ->limit(10)
            ->get();
        $topByClass = DB::table('notes as n')
            ->join('eleves as e', 'e.id', '=', $stuJoin)
            ->leftJoin('classes as c', 'c.id', '=', 'e.id_classe')
            ->where('n.annee_scolaire', $annee)
            ->when($classeId > 0, fn ($q) => $q->where('e.id_classe', $classeId))
            ->groupBy('e.id', 'e.nom', 'e.prenom', 'c.nom')
            ->select('e.id', 'e.nom', 'e.prenom', 'c.nom as classe', DB::raw('AVG(COALESCE(n.note, n.valeur)) moyenne'))
            ->get()
            ->groupBy(fn ($row) => $row->classe ?: 'Sans classe')
            ->map(fn ($items) => $items->sortByDesc('moyenne')->take(3)->values());
        $mentions = ['Insuffisant' => 0, 'Passable' => 0, 'Assez bien' => 0, 'Bien' => 0, 'Tres bien' => 0];
        foreach (DB::table('notes as n')->join('eleves as e', 'e.id', '=', $stuJoin)->where('n.annee_scolaire', $annee)->when($classeId > 0, fn ($q) => $q->where('e.id_classe', $classeId))->when($studentId > 0, fn ($q) => $q->where('e.id', $studentId))->pluck('n.note') as $note) {
            $n = (float) $note;
            if ($n < 10) $mentions['Insuffisant']++;
            elseif ($n < 12) $mentions['Passable']++;
            elseif ($n < 14) $mentions['Assez bien']++;
            elseif ($n < 16) $mentions['Bien']++;
            else $mentions['Tres bien']++;
        }
        $attendanceByClass = collect();
        $attendanceByStudent = collect();
        $selectedStudent = null;
        $selectedStudentNotes = collect();
        if (Schema::hasTable('presence_eleves')) {
            $attendanceByClass = DB::table('presence_eleves as p')
                ->join('classes as c', 'c.id', '=', 'p.classe_id')
                ->where('p.annee_scolaire', $annee)
                ->when($classeId > 0, fn ($q) => $q->where('p.classe_id', $classeId))
                ->groupBy('c.nom')
                ->select('c.nom as classe', DB::raw("SUM(CASE WHEN p.statut='present' THEN 1 ELSE 0 END) presents"), DB::raw("SUM(CASE WHEN p.statut='absent' THEN 1 ELSE 0 END) absents"), DB::raw("SUM(CASE WHEN p.statut='retard' THEN 1 ELSE 0 END) retards"), DB::raw('COUNT(*) total'))
                ->orderBy('c.nom')
                ->get();
            $attendanceByStudent = DB::table('presence_eleves as p')
                ->join('eleves as e', 'e.id', '=', 'p.eleve_id')
                ->leftJoin('classes as c', 'c.id', '=', 'p.classe_id')
                ->where('p.annee_scolaire', $annee)
                ->when($classeId > 0, fn ($q) => $q->where('p.classe_id', $classeId))
                ->when($studentId > 0, fn ($q) => $q->where('p.eleve_id', $studentId))
                ->groupBy('e.id', 'e.nom', 'e.prenom', 'c.nom')
                ->select('e.id', 'e.nom', 'e.prenom', 'c.nom as classe', DB::raw("SUM(CASE WHEN p.statut='present' THEN 1 ELSE 0 END) presents"), DB::raw("SUM(CASE WHEN p.statut='absent' THEN 1 ELSE 0 END) absents"), DB::raw("SUM(CASE WHEN p.statut='retard' THEN 1 ELSE 0 END) retards"), DB::raw('COUNT(*) total'))
                ->orderBy('c.nom')
                ->orderBy('e.nom')
                ->limit($studentId > 0 ? 1 : 12)
                ->get();
        }
        if ($studentId > 0) {
            $selectedStudent = DB::table('eleves as e')->leftJoin('classes as c', 'c.id', '=', 'e.id_classe')->select('e.*', 'c.nom as classe')->where('e.id', $studentId)->first();
            $selectedStudentNotes = DB::table('notes as n')->join('eleves as e', 'e.id', '=', $stuJoin)->join('matieres as m', 'm.id', '=', $matJoin)->where('e.id', $studentId)->where('n.annee_scolaire', $annee)->groupBy('m.nom', 'n.periode')->select('m.nom as matiere', 'n.periode', DB::raw('AVG(COALESCE(n.note, n.valeur)) moyenne'))->orderBy('m.nom')->get();
        }

        return $this->view('modules.reports.grades', $modules, 'evaluation_notes', [
            'annees' => $this->years(),
            'selectedAnnee' => $annee,
            'selectedClasse' => $classeId,
            'selectedStudentId' => $studentId,
            'classes' => $classes,
            'studentsForFilter' => $studentsForFilter,
            'studentsByClass' => $studentsByClass,
            'avgBySubject' => $avgBySubject,
            'avgByClass' => $avgByClass,
            'avgByClassPeriod' => $avgByClassPeriod,
            'topStudents' => $topStudents,
            'topByClass' => $topByClass,
            'mentions' => $mentions,
            'attendanceByClass' => $attendanceByClass,
            'attendanceByStudent' => $attendanceByStudent,
            'selectedStudent' => $selectedStudent,
            'selectedStudentNotes' => $selectedStudentNotes,
        ]);
    }

    private function presenceReport(Request $request, ModuleRegistry $modules, string $peopleTable, string $presenceTable, string $personType, string $active, string $title, string $unitColumn, string $salaryColumn)
    {
        $canSeeFullReport = $this->canSeeFullPayrollReports();
        $forcedPersonId = $this->forcedPayrollPersonId($personType);
        abort_if(! $canSeeFullReport && $forcedPersonId === null, 403);

        $annee = (string) $request->query('annee_scolaire', $this->currentPresenceYear($presenceTable));
        $month = (string) $request->query('mois', now()->format('m'));
        $personId = $forcedPersonId ?? (int) $request->query('personne_id', 0);
        $cnaps = (float) $request->query('cnaps', 0);
        $ostie = (float) $request->query('ostie', 0);
        $people = DB::table($peopleTable)
            ->select('id', 'nom', 'prenom', $salaryColumn)
            ->when($personType === 'staff', fn ($q) => $q->where('poste', '!=', 'Enseignant'))
            ->when($forcedPersonId !== null, fn ($q) => $q->where('id', $forcedPersonId))
            ->orderBy('nom')
            ->get();
        $query = DB::table($presenceTable.' as pr')
            ->join($peopleTable.' as p', 'p.id', '=', 'pr.personne_id')
            ->when($personId > 0, fn ($q) => $q->where('pr.personne_id', $personId));
        $this->applyYearFilter($query, 'pr.annee_scolaire', $annee);
        $this->applyMonthFilter($query, 'pr.mois', $month, 'pr.date_jour');
        $rows = $query->select('p.id', 'p.nom', 'p.prenom', 'p.'.$salaryColumn, DB::raw('SUM(CASE WHEN pr.presence=1 THEN 1 ELSE 0 END) presents'), DB::raw('SUM(CASE WHEN pr.presence=0 THEN 1 ELSE 0 END) absents'), DB::raw('SUM(CASE WHEN pr.retard=1 THEN 1 ELSE 0 END) retards'), DB::raw('SUM(CASE WHEN pr.presence=1 THEN pr.'.$unitColumn.' ELSE 0 END) total_units'))->groupBy('p.id', 'p.nom', 'p.prenom', 'p.'.$salaryColumn)->orderBy('p.nom')->get();
        $summary = ['presents' => $rows->sum('presents'), 'absents' => $rows->sum('absents'), 'retards' => $rows->sum('retards'), 'units' => $rows->sum('total_units'), 'payroll' => $rows->sum(fn ($r) => (float) $r->total_units * (float) $r->{$salaryColumn})];

        $weeklyQuery = DB::table($presenceTable)
            ->whereBetween('date_jour', [now()->startOfWeek()->toDateString(), now()->endOfWeek()->toDateString()])
            ->when($personId > 0, fn ($q) => $q->where('personne_id', $personId));
        $this->applyYearFilter($weeklyQuery, 'annee_scolaire', $annee);
        $weekly = $weeklyQuery
            ->select('date_jour', DB::raw('SUM(CASE WHEN presence=1 THEN 1 ELSE 0 END) presents'), DB::raw('SUM(CASE WHEN presence=0 THEN 1 ELSE 0 END) absents'), DB::raw('SUM(CASE WHEN retard=1 THEN 1 ELSE 0 END) retards'), DB::raw('SUM(CASE WHEN presence=1 THEN '.$unitColumn.' ELSE 0 END) units'))
            ->groupBy('date_jour')
            ->orderBy('date_jour')
            ->get();

        $monthlyDailyQuery = DB::table($presenceTable)
            ->when($personId > 0, fn ($q) => $q->where('personne_id', $personId));
        $this->applyYearFilter($monthlyDailyQuery, 'annee_scolaire', $annee);
        $this->applyMonthFilter($monthlyDailyQuery, 'mois', $month, 'date_jour');
        $monthlyDaily = $monthlyDailyQuery
            ->select('date_jour', DB::raw('SUM(CASE WHEN presence=1 THEN 1 ELSE 0 END) presents'), DB::raw('SUM(CASE WHEN presence=0 THEN 1 ELSE 0 END) absents'), DB::raw('SUM(CASE WHEN retard=1 THEN 1 ELSE 0 END) retards'), DB::raw('SUM(CASE WHEN presence=1 THEN '.$unitColumn.' ELSE 0 END) units'))
            ->groupBy('date_jour')
            ->orderBy('date_jour')
            ->get();

        $selectedPerson = $personId > 0 ? $people->firstWhere('id', $personId) : null;
        $payslipRows = $rows;
        $pedagogicSummary = collect();
        if ($personType === 'professeur' && DB::getSchemaBuilder()->hasTable('teacher_lessons')) {
            $lessonQuery = DB::table('teacher_lessons as l')
                ->leftJoin('classes as c', 'c.id', '=', 'l.classe_id')
                ->join('professeurs as p', 'p.id', '=', 'l.professeur_id')
                ->when($personId > 0, fn ($q) => $q->where('l.professeur_id', $personId));
            $this->applyYearFilter($lessonQuery, 'l.annee_scolaire', $annee);
            $pedagogicSummary = $lessonQuery
                ->groupBy('p.id', 'p.nom', 'p.prenom', 'c.nom')
                ->select('p.id', 'p.nom', 'p.prenom', 'c.nom as classe', DB::raw('COUNT(l.id) lecons'), DB::raw("SUM(CASE WHEN l.statut='termine' THEN 1 ELSE 0 END) terminees"), DB::raw('AVG(l.progression) progression'))
                ->orderBy('p.nom')
                ->get();
        }

        return $this->view('modules.reports.presence', $modules, $active, compact('annee', 'month', 'personId', 'people', 'rows', 'summary', 'weekly', 'monthlyDaily', 'selectedPerson', 'payslipRows', 'title', 'unitColumn', 'salaryColumn', 'personType', 'cnaps', 'ostie', 'pedagogicSummary', 'canSeeFullReport') + ['annees' => $this->years(), 'months' => $this->months]);
    }

    private function canSeeFullPayrollReports(): bool
    {
        $role = session('utilisateur.role');
        if ($role === 'admin') {
            return true;
        }

        return $role === 'staff' && $this->currentStaffHasRoleAndDepartment('rh', 'administration');
    }

    private function forcedPayrollPersonId(string $personType): ?int
    {
        $role = session('utilisateur.role');
        $email = (string) session('utilisateur.email', '');

        if ($role === 'enseignant' && $personType === 'professeur') {
            return DB::table('professeurs')->where('email', $email)->value('id');
        }

        if ($role === 'staff' && $personType === 'staff' && ! $this->canSeeFullPayrollReports()) {
            return DB::table('staff')->where('email', $email)->value('id');
        }

        return null;
    }

    private function currentStaffHasRoleAndDepartment(string $roleNeedle, string $departmentNeedle): bool
    {
        $email = (string) session('utilisateur.email', '');
        if ($email === '') {
            return false;
        }

        $staff = DB::table('staff as s')
            ->leftJoin('roles as r', 'r.id', '=', 's.role_id')
            ->leftJoin('departements as d', 'd.id', '=', 's.departement_id')
            ->where('s.email', $email)
            ->select('r.nom as role_nom', 'd.nom as departement_nom')
            ->first();

        $role = mb_strtolower((string) ($staff->role_nom ?? ''));
        $department = mb_strtolower((string) ($staff->departement_nom ?? ''));

        return str_contains($role, $roleNeedle) && str_contains($department, $departmentNeedle);
    }

    private function years()
    {
        $query = DB::table('eleves')->select('annee_scolaire')->whereNotNull('annee_scolaire');
        foreach (['revenus', 'depenses', 'presence_personnels', 'presence_staff', 'notes'] as $table) {
            if (Schema::hasTable($table) && Schema::hasColumn($table, 'annee_scolaire')) {
                $query->union(DB::table($table)->select('annee_scolaire')->whereNotNull('annee_scolaire'));
            }
        }

        return $query->distinct()->orderByDesc('annee_scolaire')->pluck('annee_scolaire')->filter()->values();
    }

    private function ensureNotesIndexes(): void
    {
        if (session()->has('_idx_notes_ok')) return;
        if (DB::getDriverName() !== 'sqlite') return;
        try {
            $indexes = DB::select("SELECT name FROM pragma_index_list('notes')");
            $existing = array_map(fn ($i) => $i->name, $indexes);
            if (!in_array('notes_annee_idx', $existing, true)) {
                DB::statement('CREATE INDEX IF NOT EXISTS notes_annee_idx ON notes(annee_scolaire)');
                DB::statement('CREATE INDEX IF NOT EXISTS notes_periode_idx ON notes(periode)');
                DB::statement('CREATE INDEX IF NOT EXISTS notes_eleve_matiere_idx ON notes(id_eleve, id_matiere)');
                DB::statement('CREATE INDEX IF NOT EXISTS notes_composite_idx ON notes(annee_scolaire, periode, id_eleve, id_matiere)');
            }
            session()->put('_idx_notes_ok', true);
        } catch (\Throwable $e) {
            // Index creation is optional performance optimization
        }
    }

    private function currentYear(): string
    {
        return (string) ($this->years()->first() ?: now()->format('Y').'-'.(now()->year + 1));
    }

    private function currentPresenceYear(string $presenceTable): string
    {
        if (Schema::hasTable($presenceTable)) {
            $year = DB::table($presenceTable)
                ->whereNotNull('annee_scolaire')
                ->where('annee_scolaire', '!=', '')
                ->orderByDesc('date_jour')
                ->value('annee_scolaire');

            if ($year) {
                return (string) $year;
            }
        }

        return $this->currentYear();
    }

    private function applyYearFilter($query, string $column, string $year): void
    {
        $aliases = array_values(array_unique(array_filter([$year, ...$this->yearAliases($year)])));
        $query->where(function ($sub) use ($column, $aliases) {
            foreach ($aliases as $alias) {
                $sub->orWhere($column, $alias);
            }
        });
    }

    private function yearAliases(string $year): array
    {
        $year = trim($year);

        if (preg_match('/^(\d{4})\s*-\s*(\d{4})$/', $year, $matches)) {
            return [$matches[1], $matches[2]];
        }

        if (preg_match('/^\d{4}$/', $year)) {
            return [$year.'-'.((int) $year + 1), ((int) $year - 1).'-'.$year];
        }

        return [];
    }

    private function applyMonthFilter($query, string $monthColumn, string $month, ?string $dateColumn = null): void
    {
        $aliases = $this->monthAliases($month);
        $monthNumber = $this->monthNumber($month);

        $query->where(function ($sub) use ($monthColumn, $dateColumn, $aliases, $monthNumber) {
            foreach ($aliases as $alias) {
                $sub->orWhere($monthColumn, 'like', "%{$alias}%");
            }

            if ($dateColumn && $monthNumber) {
                $sub->orWhereMonth($dateColumn, $monthNumber);
            }
        });
    }

    private function monthAliases(string $month): array
    {
        $map = [
            1 => ['01', '1', 'Jan', 'Janvier', 'January'],
            2 => ['02', '2', 'Fev', 'Fevrier', 'Fév', 'Février', 'February'],
            3 => ['03', '3', 'Mar', 'Mars', 'March'],
            4 => ['04', '4', 'Avr', 'Avril', 'April'],
            5 => ['05', '5', 'Mai', 'May'],
            6 => ['06', '6', 'Juin', 'June'],
            7 => ['07', '7', 'Juil', 'Juillet', 'July'],
            8 => ['08', '8', 'Aou', 'Aout', 'Aoû', 'Août', 'August'],
            9 => ['09', '9', 'Sep', 'Sept', 'Septembre', 'September'],
            10 => ['10', 'Oct', 'Octobre', 'October'],
            11 => ['11', 'Nov', 'Novembre', 'November'],
            12 => ['12', 'Dec', 'Decembre', 'Déc', 'Décembre', 'December'],
        ];

        if (is_numeric($month)) {
            return $map[(int) $month] ?? [$month];
        }

        $normalized = trim(mb_strtolower($month));
        foreach ($map as $aliases) {
            foreach ($aliases as $alias) {
                if (mb_strtolower($alias) === $normalized) {
                    return $aliases;
                }
            }
        }

        return [$month];
    }

    private function monthNumber(string $month): ?int
    {
        if (is_numeric($month)) {
            $number = (int) $month;
            return $number >= 1 && $number <= 12 ? $number : null;
        }

        $normalized = trim(mb_strtolower($month));
        foreach (range(1, 12) as $number) {
            foreach ($this->monthAliases((string) $number) as $alias) {
                if (mb_strtolower($alias) === $normalized) {
                    return $number;
                }
            }
        }

        return null;
    }

    private function view(string $name, ModuleRegistry $modules, string $activeModule, array $data = [])
    {
        return view($name, $data + ['modules' => $modules->all(), 'userPermissions' => $this->userPermissions(), 'ecole' => $this->school(), 'activeModule' => $activeModule]);
    }

    private function ensureSession(): void
    {
        abort_unless(session()->has('utilisateur') && in_array(session('utilisateur.role'), ['admin', 'enseignant', 'parent', 'staff'], true), 403);
    }

    private function userPermissions(): array
    {
        $id = (int) session('utilisateur.id', 0);
        return $id ? DB::table('permissions')->where('utilisateur_id', $id)->pluck('acces', 'module')->all() : [];
    }

    private function school(): object
    {
        return DB::table('ecole')->select('nom', 'logo')->first() ?: (object) ['nom' => 'Ecole', 'logo' => 'novaskol.png'];
    }
}
