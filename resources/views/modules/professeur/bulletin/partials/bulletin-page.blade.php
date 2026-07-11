@php
    $studentNotes = $notesByPeriod[$periode][$student->id] ?? [];
    $average = $finalAverages[$student->id] ?? null;
    $rank = $generalRanks[$student->id] ?? null;
@endphp
<section class="bulletin-page">
    <div class="bulletin-header">
        <h2>{{ $ecole->nom ?? 'Ecole' }}</h2>
        <p>Bulletin - {{ $periodLabels[$periode] ?? $periode }} - {{ $annee }}</p>
    </div>
    <div class="bulletin-body">
        <div class="student-info">
            <div class="info-box"><div class="info-label">Eleve</div><div class="info-value">{{ $student->prenom }} {{ $student->nom }}</div></div>
            <div class="info-box"><div class="info-label">Classe</div><div class="info-value">{{ $student->classe ?? $classe->nom ?? '' }}</div></div>
            <div class="info-box"><div class="info-label">Matricule</div><div class="info-value">{{ $student->matricule ?? '-' }}</div></div>
            <div class="info-box"><div class="info-label">Annee scolaire</div><div class="info-value">{{ $annee }}</div></div>
        </div>
        <div class="bulletin-table-wrap">
            <table class="bulletin-table">
                <thead>
                    <tr>
                        <th>Matiere</th>
                        <th>Coeff.</th>
                        <th>Note / 20</th>
                        <th>Rang</th>
                        <th>Appreciation</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($subjects as $subject)
                        @php
                            $note = $studentNotes[$subject->id] ?? null;
                            $subjectRank = $subjectRanks[$subject->id][$student->id] ?? null;
                        @endphp
                        <tr>
                            <td>{{ $subject->nom }}</td>
                            <td>{{ $subject->coefficient }}</td>
                            <td>{{ is_numeric($note) ? number_format($note, 2, ',', ' ') : '-' }}</td>
                            <td>{{ $subjectRank ?? '-' }}</td>
                            <td>{{ $calculator->subjectRemark($note) }}</td>
                        </tr>
                    @endforeach
                    <tr class="moyenne-row">
                        <td colspan="2">Moyenne generale</td>
                        <td>{{ is_numeric($average) ? number_format($average, 2, ',', ' ') : '-' }}</td>
                        <td colspan="2">Rang : {{ is_numeric($rank) ? $rank.' / '.$rankedCount : '-' }}</td>
                    </tr>
                    <tr class="moyenne-row">
                        <td colspan="2">Moyenne de classe</td>
                        <td colspan="3">{{ is_numeric($classAverage) ? number_format($classAverage, 2, ',', ' ') : $classAverage }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="remark-box">
            <strong>Remarque generale :</strong> {{ $calculator->generalRemark($average) }}
        </div>
    </div>
</section>
