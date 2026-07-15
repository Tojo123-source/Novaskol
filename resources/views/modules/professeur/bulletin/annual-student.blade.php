<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Bulletin annuel - {{ $student->prenom }} {{ $student->nom }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{ asset('legacy/assets/fontawesome/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('legacy/fa/css/font-awesome.min.css') }}">
    <script src="{{ asset('legacy/js/jquery-3.6.0.min.js') }}"></script>
    @include('modules.professeur.bulletin.partials.styles')
    <style>
        .annual-page { max-width:1180px; }
        .annual-summary { display:grid; grid-template-columns:repeat(auto-fit,minmax(180px,1fr)); gap:12px; margin:18px 0; }
        .summary-card { border:1px solid #cbd5e1; border-radius:10px; padding:12px; background:#f8fafc; text-align:center; }
        .summary-label { color:#64748b; font-size:.82rem; margin-bottom:5px; }
        .summary-value { font-size:1.2rem; font-weight:800; color:#0f172a; }
        .decision-admis { color:#16a34a; }
        .decision-non-admis { color:#dc2626; }
        .annual-table { font-size:.82rem; }
        .annual-table th { white-space:nowrap; }
        .annual-table .period-head { background:#0f766e; }
        @media screen and (max-width:900px) {
            .annual-page { width:1180px!important; max-width:1180px!important; min-width:1180px!important; }
            .annual-summary { grid-template-columns:repeat(4,1fr)!important; gap:12px!important; }
            .summary-card { padding:12px!important; border-radius:10px!important; }
            .summary-value { font-size:1.2rem!important; }
            .annual-table { min-width:0!important; font-size:.82rem!important; }
        }
        @media print {
            .annual-page { width:100%!important; max-width:none!important; min-width:0!important; zoom:1!important; transform:none!important; }
            .annual-summary { grid-template-columns:repeat(4,1fr)!important; }
        }
    </style>
</head>
<body>
@include('modules.professeur.bulletin.partials.shell')
<header>
    <div class="header-left">
        <button class="burger-menu" onclick="toggleSidebar()"><i class="fa fa-bars"></i></button>
        <button id="fullscreen-btn" onclick="toggleFullscreen()"><i id="fullscreen-icon" class="fa fa-expand"></i></button>
    </div>
    <div class="header-center"><i class="fa fa-calendar-check"></i> Bulletin annuel de {{ $student->prenom }} {{ $student->nom }}</div>
</header>
<main>
    <div class="print-actions">
        <a class="action-btn" href="{{ route('modules.bulletin.annual') }}"><i class="fa fa-arrow-left"></i> Retour</a>
        <button class="kaly" onclick="window.print()"><i class="fa fa-print"></i> Imprimer</button>
        <button class="kaly" style="background:#333!important" onclick="window.print()"><i class="fa fa-file-pdf-o"></i> Apercu PDF</button>
        </div>
    <section class="bulletin-page annual-page">
        <div class="bulletin-header">
            <h2>{{ $ecole->nom ?? 'Ecole' }}</h2>
            <p>Bulletin annuel - {{ $annee }}</p>
        </div>
        <div class="bulletin-body">
            <div class="student-info">
                <div class="info-box"><div class="info-label">Eleve</div><div class="info-value">{{ $student->prenom }} {{ $student->nom }}</div></div>
                <div class="info-box"><div class="info-label">Classe</div><div class="info-value">{{ $student->classe }}</div></div>
                <div class="info-box"><div class="info-label">Matricule</div><div class="info-value">{{ $student->matricule ?? '-' }}</div></div>
                <div class="info-box"><div class="info-label">Annee scolaire</div><div class="info-value">{{ $annee }}</div></div>
            </div>
            <div class="annual-summary">
                <div class="summary-card"><div class="summary-label">Moyenne annuelle</div><div class="summary-value">{{ is_numeric($annualAverage) ? number_format($annualAverage, 2, ',', ' ') : '-' }}</div></div>
                <div class="summary-card"><div class="summary-label">Rang annuel</div><div class="summary-value">{{ is_numeric($annualRank) ? $annualRank.' / '.$rankedCount : '-' }}</div></div>
                <div class="summary-card"><div class="summary-label">Moyenne classe</div><div class="summary-value">{{ is_numeric($annualClassAverage) ? number_format($annualClassAverage, 2, ',', ' ') : $annualClassAverage }}</div></div>
                <div class="summary-card"><div class="summary-label">Decision</div><div @class(['summary-value', 'decision-admis' => $decision === 'Admis', 'decision-non-admis' => $decision !== 'Admis'])>{{ $decision }}</div></div>
            </div>
            <div class="bulletin-table-wrap">
                <table class="bulletin-table annual-table">
                    <thead>
                        <tr>
                            <th rowspan="2">Matiere</th>
                            <th rowspan="2">Coeff.</th>
                            @foreach (['B1', 'B2', 'T1', 'T2', 'T3'] as $period)
                                <th colspan="2" class="period-head">{{ $period }}</th>
                            @endforeach
                        </tr>
                        <tr>
                            @foreach (['B1', 'B2', 'T1', 'T2', 'T3'] as $period)
                                <th>Note</th><th>Rang</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($subjects as $subject)
                            <tr>
                                <td>{{ $subject->nom }}</td>
                                <td>{{ $subject->coefficient }}</td>
                                @foreach (['B1', 'B2', 'T1', 'T2', 'T3'] as $period)
                                    @php
                                        $note = $notesByPeriod[$period][$student->id][$subject->id] ?? null;
                                        $rank = $subjectRanksByPeriod[$period][$subject->id][$student->id] ?? null;
                                    @endphp
                                    <td>{{ is_numeric($note) ? number_format($note, 2, ',', ' ') : '-' }}</td>
                                    <td>{{ $rank ?? '-' }}</td>
                                @endforeach
                            </tr>
                        @endforeach
                        <tr class="moyenne-row">
                            <td colspan="2">Moyenne periode</td>
                            @foreach (['B1', 'B2', 'T1', 'T2', 'T3'] as $period)
                                @php($avg = $combinedAverages[$period][$student->id] ?? null)
                                <td colspan="2">{{ is_numeric($avg) ? number_format($avg, 2, ',', ' ') : '-' }}</td>
                            @endforeach
                        </tr>
                        <tr class="moyenne-row">
                            <td colspan="2">Rang periode</td>
                            @foreach (['B1', 'B2', 'T1', 'T2', 'T3'] as $period)
                                @php($periodRanks = $calculator->ranks($combinedAverages[$period] ?? []))
                                @php($currentPeriodRank = $periodRanks[$student->id] ?? null)
                                @php($periodCount = count(array_filter($combinedAverages[$period] ?? [], fn ($value) => is_numeric($value))))
                                <td colspan="2">{{ is_numeric($currentPeriodRank) ? $currentPeriodRank.' / '.$periodCount : '-' }}</td>
                            @endforeach
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="remark-box">
                <strong>Remarque generale :</strong> {{ $remark }}
            </div>
        </div>
    </section>
    <footer>&copy; {{ date('Y') }} {{ $ecole->nom ?? 'Ecole' }}. Tous droits reserves.</footer>
</main>
<script>
function toggleSub(el){const sub=el.nextElementSibling;const arrow=el.querySelector('.arrow');sub.style.display=sub.style.display==='block'?'none':'block';arrow.classList.toggle('fa-chevron-down');arrow.classList.toggle('fa-chevron-up');}
function toggleSidebar(){document.getElementById('sidebar').classList.toggle('active');document.querySelector('main').classList.toggle('full-width');document.querySelector('header').classList.toggle('full-width');}
function toggleFullscreen(){const icon=document.getElementById('fullscreen-icon');if(!document.fullscreenElement){document.documentElement.requestFullscreen();icon.classList.replace('fa-expand','fa-compress');}else{document.exitFullscreen();icon.classList.replace('fa-compress','fa-expand');}}
document.addEventListener('DOMContentLoaded',()=>{const active=document.querySelector('nav a.active');if(active){const sub=active.closest('.sub-menu');if(sub){sub.style.display='block';const arrow=sub.previousElementSibling?.querySelector('.arrow');if(arrow){arrow.classList.replace('fa-chevron-down','fa-chevron-up');}}}});
</script>
</body>
</html>
