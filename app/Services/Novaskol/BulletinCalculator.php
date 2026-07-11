<?php

namespace App\Services\Novaskol;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class BulletinCalculator
{
    public array $periodLabels = [
        'B1' => '1er Bimestre',
        'B2' => '2e Bimestre',
        'T1' => '1er Trimestre',
        'T2' => '2e Trimestre',
        'T3' => '3e Trimestre',
    ];

    public function validPeriods(): array
    {
        return array_keys($this->periodLabels);
    }

    public function classBulletins(int $classeId, string $periode, string $annee): array
    {
        $classe = DB::table('classes')->select('id', 'nom')->where('id', $classeId)->first();
        $students = DB::table('eleves')
            ->select('id', 'nom', 'prenom', 'matricule', 'id_classe')
            ->where('id_classe', $classeId)
            ->where('annee_scolaire', $annee)
            ->orderBy('nom')
            ->orderBy('prenom')
            ->get();
        $subjects = $this->subjects($classeId);
        $notesByPeriod = $this->notesByPeriod($students->pluck('id')->all(), $annee);
        $periodAverages = $this->periodAveragesForStudents($students, $subjects, $notesByPeriod);
        $finalAverages = $this->finalAverages($periodAverages, $periode, $students);
        $generalRanks = $this->ranks($finalAverages);
        $subjectRanks = $this->subjectRanks($students, $subjects, $notesByPeriod[$periode] ?? []);
        $classAverage = $this->averageOf($finalAverages);

        return [
            'classe' => $classe,
            'students' => $students,
            'subjects' => $subjects,
            'notesByPeriod' => $notesByPeriod,
            'periodAverages' => $periodAverages,
            'finalAverages' => $finalAverages,
            'generalRanks' => $generalRanks,
            'subjectRanks' => $subjectRanks,
            'classAverage' => $classAverage,
            'rankedCount' => count(array_filter($finalAverages, fn ($value) => is_numeric($value))),
        ];
    }

    public function studentBulletin(int $studentId, string $periode, string $annee): ?array
    {
        $student = DB::table('eleves as e')
            ->join('classes as c', 'e.id_classe', '=', 'c.id')
            ->select('e.id', 'e.nom', 'e.prenom', 'e.matricule', 'e.id_classe', 'c.nom as classe')
            ->where('e.id', $studentId)
            ->where('e.annee_scolaire', $annee)
            ->first();

        if (! $student) {
            return null;
        }

        $classData = $this->classBulletins((int) $student->id_classe, $periode, $annee);
        $notes = $classData['notesByPeriod'][$periode][$studentId] ?? [];
        $finalAverage = $classData['finalAverages'][$studentId] ?? null;

        return $classData + [
            'student' => $student,
            'notes' => $notes,
            'finalAverage' => $finalAverage,
            'generalRank' => $classData['generalRanks'][$studentId] ?? null,
            'remark' => $this->generalRemark($finalAverage),
        ];
    }

    public function annualStudentBulletin(int $studentId, string $annee): ?array
    {
        $student = DB::table('eleves as e')
            ->join('classes as c', 'e.id_classe', '=', 'c.id')
            ->select('e.id', 'e.nom', 'e.prenom', 'e.matricule', 'e.id_classe', 'c.nom as classe')
            ->where('e.id', $studentId)
            ->where('e.annee_scolaire', $annee)
            ->first();

        if (! $student) {
            return null;
        }

        $classe = DB::table('classes')->select('id', 'nom')->where('id', $student->id_classe)->first();
        $students = DB::table('eleves')
            ->select('id', 'nom', 'prenom', 'matricule', 'id_classe')
            ->where('id_classe', $student->id_classe)
            ->where('annee_scolaire', $annee)
            ->orderBy('nom')
            ->orderBy('prenom')
            ->get();
        $subjects = $this->subjects((int) $student->id_classe);
        $notesByPeriod = $this->notesByPeriod($students->pluck('id')->all(), $annee);
        $periodAverages = $this->periodAveragesForStudents($students, $subjects, $notesByPeriod);

        $combinedAverages = [];

        foreach ($students as $classStudent) {
            $studentAverages = [];

            foreach ($this->validPeriods() as $period) {
                $studentAverages[$period] = $periodAverages[$period][$classStudent->id] ?? null;
                $combinedAverages[$period][$classStudent->id] = $this->combinedAverage($studentAverages, $period);
            }
        }

        $annualAverages = [];

        foreach ($students as $classStudent) {
            $annualAverages[$classStudent->id] = $this->annualAverage([
                'T1' => $combinedAverages['T1'][$classStudent->id] ?? null,
                'T2' => $combinedAverages['T2'][$classStudent->id] ?? null,
                'T3' => $combinedAverages['T3'][$classStudent->id] ?? null,
            ]);
        }

        $annualRanks = $this->ranks($annualAverages);
        $subjectRanksByPeriod = [];

        foreach ($this->validPeriods() as $period) {
            $subjectRanksByPeriod[$period] = $this->subjectRanks($students, $subjects, $notesByPeriod[$period] ?? []);
        }

        $studentAnnualAverage = $annualAverages[$studentId] ?? null;

        return [
            'student' => $student,
            'classe' => $classe,
            'students' => $students,
            'subjects' => $subjects,
            'notesByPeriod' => $notesByPeriod,
            'periodAverages' => $periodAverages,
            'combinedAverages' => $combinedAverages,
            'annualAverages' => $annualAverages,
            'annualAverage' => $studentAnnualAverage,
            'annualClassAverage' => $this->averageOf($annualAverages),
            'annualRank' => $annualRanks[$studentId] ?? null,
            'annualRanks' => $annualRanks,
            'subjectRanksByPeriod' => $subjectRanksByPeriod,
            'rankedCount' => count(array_filter($annualAverages, fn ($value) => is_numeric($value))),
            'decision' => $this->decision($studentAnnualAverage),
            'remark' => $this->generalRemark($studentAnnualAverage),
            'usesBimesters' => $this->schoolUsesBimesters(),
        ];
    }

    public function subjects(int $classeId): Collection
    {
        return DB::table('classe_matieres as cm')
            ->join('matieres as m', 'cm.id_matiere', '=', 'm.id')
            ->select('m.id', 'm.nom', 'cm.coefficient')
            ->where('cm.id_classe', $classeId)
            ->orderBy('m.nom')
            ->get();
    }

    public function notesByPeriod(array $studentIds, string $annee): array
    {
        if (empty($studentIds)) {
            return [];
        }

        $rows = DB::table('notes')
            ->select('id_eleve', 'eleve_id', 'id_matiere', 'matiere_id', 'periode', 'trimestre', 'note', 'valeur')
            ->where(function ($q) use ($studentIds) {
                $q->whereIn('id_eleve', $studentIds)
                  ->orWhereIn('eleve_id', $studentIds);
            })
            ->where('annee_scolaire', $annee)
            ->get();

        $notes = [];

        foreach ($rows as $row) {
            $eleveId = $row->id_eleve ?? $row->eleve_id;
            $matiereId = $row->id_matiere ?? $row->matiere_id;
            $periode = $row->periode ?? ('T' . $row->trimestre);
            $note = $row->note ?? $row->valeur;

            if ($eleveId && $matiereId && $periode && is_numeric($note)) {
                $notes[$periode][(int) $eleveId][(int) $matiereId] = (float) $note;
            }
        }

        return $notes;
    }

    public function periodAveragesForStudents(Collection $students, Collection $subjects, array $notesByPeriod): array
    {
        $averages = [];

        foreach ($this->validPeriods() as $periode) {
            foreach ($students as $student) {
                $averages[$periode][$student->id] = $this->periodAverage($notesByPeriod[$periode][$student->id] ?? [], $subjects);
            }
        }

        return $averages;
    }

    public function periodAverage(array $notes, Collection $subjects): ?float
    {
        $total = 0;
        $coefTotal = 0;

        foreach ($subjects as $subject) {
            $note = $notes[$subject->id] ?? null;

            if (is_numeric($note) && (float) $note > 0 && (float) $note <= 20) {
                $total += (float) $note * (float) $subject->coefficient;
                $coefTotal += (float) $subject->coefficient;
            }
        }

        return $coefTotal > 0 ? round($total / $coefTotal, 2) : null;
    }

    public function combinedAverage(array $periodAverages, string $periode): ?float
    {
        return match ($periode) {
            'B1', 'B2' => $periodAverages[$periode] ?? null,
            'T1' => isset($periodAverages['B1'], $periodAverages['T1'])
                ? round(($periodAverages['B1'] + $periodAverages['T1']) / 2, 2)
                : ($periodAverages['T1'] ?? null),
            'T2' => isset($periodAverages['B2'], $periodAverages['T2'])
                ? round(($periodAverages['B2'] + $periodAverages['T2']) / 2, 2)
                : ($periodAverages['T2'] ?? null),
            'T3' => $periodAverages['T3'] ?? null,
            default => null,
        };
    }

    public function finalAverages(array $periodAverages, string $periode, Collection $students): array
    {
        $final = [];

        foreach ($students as $student) {
            $studentAverages = [];

            foreach ($this->validPeriods() as $period) {
                $studentAverages[$period] = $periodAverages[$period][$student->id] ?? null;
            }

            $final[$student->id] = $this->combinedAverage($studentAverages, $periode);
        }

        return $final;
    }

    public function ranks(array $averages): array
    {
        arsort($averages);

        $ranks = [];
        $position = 1;

        foreach ($averages as $studentId => $average) {
            $ranks[$studentId] = is_numeric($average) ? $position++ : null;
        }

        return $ranks;
    }

    public function subjectRanks(Collection $students, Collection $subjects, array $notesForPeriod): array
    {
        $ranks = [];
        $total = $students->count();

        foreach ($subjects as $subject) {
            $notes = [];

            foreach ($students as $student) {
                $notes[$student->id] = $notesForPeriod[$student->id][$subject->id] ?? null;
            }

            uasort($notes, function ($a, $b) {
                if ($a === null) {
                    return 1;
                }

                if ($b === null) {
                    return -1;
                }

                return $b <=> $a;
            });

            $rank = 1;
            $previous = null;
            $position = 1;

            foreach ($notes as $studentId => $note) {
                if ($note === null) {
                    $ranks[$subject->id][$studentId] = null;
                    continue;
                }

                if ($previous !== null && $note < $previous) {
                    $rank = $position;
                }

                $ranks[$subject->id][$studentId] = "{$rank} / {$total}";
                $previous = $note;
                $position++;
            }
        }

        return $ranks;
    }

    public function averageOf(array $averages): string|float
    {
        $values = array_values(array_filter($averages, fn ($value) => is_numeric($value)));

        return empty($values) ? 'N/A' : round(array_sum($values) / count($values), 2);
    }

    public function annualAverage(array $trimesterAverages): ?float
    {
        $values = array_values(array_filter($trimesterAverages, fn ($value) => is_numeric($value)));

        return empty($values) ? null : round(array_sum($values) / count($values), 2);
    }

    public function decision(mixed $average): string
    {
        if (! is_numeric($average)) {
            return 'Non determinee';
        }

        return (float) $average >= 10 ? 'Admis' : 'Non admis';
    }

    public function schoolUsesBimesters(): bool
    {
        return DB::table('notes')->whereIn('periode', ['B1', 'B2'])->exists();
    }

    public function subjectRemark(mixed $note): string
    {
        if (! is_numeric($note)) {
            return '-';
        }

        $note = (float) $note;

        if ($note >= 15) {
            return 'Tres bien';
        }

        if ($note >= 14) {
            return 'Bien';
        }

        if ($note >= 12) {
            return 'Assez bien';
        }

        if ($note >= 10) {
            return 'Passable';
        }

        return 'Mediocre';
    }

    public function generalRemark(mixed $average): string
    {
        if (! is_numeric($average)) {
            return 'A evaluer';
        }

        $average = (float) $average;

        if ($average >= 18) {
            return 'Excellent, continuation dans cet effort!';
        }

        if ($average >= 16) {
            return 'Tres bien! Continuez vos excellents efforts.';
        }

        if ($average >= 14) {
            return 'Bien. A maintenir cet excellent niveau.';
        }

        if ($average >= 12) {
            return 'Satisfaisant. Augmentez vos efforts pour ameliorer.';
        }

        if ($average >= 10) {
            return "Passable. Beaucoup d'efforts sont necessaires.";
        }

        if ($average >= 8) {
            return 'Faible. Effort soutenu et encadrement necessaires.';
        }

        return 'Tres faible. Revision complete requise.';
    }
}
