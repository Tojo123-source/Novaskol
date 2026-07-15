<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Resultats automatiques - {{ $ecole->nom ?? 'Ecole' }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{ asset('legacy/assets/fontawesome/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('legacy/assets/sweetalert2/sweetalert2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('legacy/fa/css/font-awesome.min.css') }}">
    <script src="{{ asset('legacy/js/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('legacy/assets/sweetalert2/sweetalert2.min.js') }}"></script>
    @include('modules.professeur.bulletin.partials.styles')
    <style>
        .result-table { width:100%; border-collapse:collapse; min-width:960px; background:var(--card); color:var(--text); border-radius:8px; overflow:hidden; }
        .result-table th,.result-table td { border:1px solid var(--border); padding:10px; text-align:center; font-size:.86rem; }
        .result-table th { background:#034a3b; color:white; }
        .result-table td:nth-child(2), .result-table th:nth-child(2) { text-align:left; min-width:220px; }
        .coef-row td { font-weight:700; color:var(--primary); background:#0f172a; }
        .moyenne-classe { margin:18px auto; padding:14px; border-radius:8px; text-align:center; font-weight:800; font-size:1.1rem; border:1px solid var(--border); }
        .signatures { display:flex; justify-content:space-between; margin-top:38px; gap:40px; }
        .signatures div { text-align:center; flex:1; color:var(--text-sec); }
        .signatures div div { border-bottom:1px solid var(--text-sec); height:42px; margin-bottom:8px; }
        .table-wrapper { overflow:auto; -webkit-overflow-scrolling:touch; max-width:100%; border:1px solid var(--border); border-radius:8px; }
        .form-container { overflow:hidden; }
        @media screen and (max-width:900px) {
            body.result-report main { overflow-x:hidden!important; }
            body.result-report .table-wrapper { overflow:visible!important; border:0!important; }
            body.result-report .print-header,
            body.result-report .table-wrapper,
            body.result-report .moyenne-classe,
            body.result-report .signatures {
                width:960px!important;
                max-width:960px!important;
                min-width:960px!important;
                margin-left:0!important;
                margin-right:0!important;
                zoom:.72;
                transform-origin:top left;
            }
            body.result-report .result-table { min-width:0!important; font-size:.86rem!important; }
            body.result-report .result-table th,
            body.result-report .result-table td { padding:10px!important; }
            body.result-report .result-table td:nth-child(2),
            body.result-report .result-table th:nth-child(2) { min-width:220px!important; }
            body.result-report .signatures { display:flex!important; flex-direction:row!important; gap:40px!important; }
        }
        @media screen and (max-width:760px){body.result-report .print-header,body.result-report .table-wrapper,body.result-report .moyenne-classe,body.result-report .signatures{zoom:.62}}
        @media screen and (max-width:700px){body.result-report .print-header,body.result-report .table-wrapper,body.result-report .moyenne-classe,body.result-report .signatures{zoom:.52}}
        @media screen and (max-width:600px){body.result-report .print-header,body.result-report .table-wrapper,body.result-report .moyenne-classe,body.result-report .signatures{zoom:.44}}
        @media screen and (max-width:520px){body.result-report .print-header,body.result-report .table-wrapper,body.result-report .moyenne-classe,body.result-report .signatures{zoom:.37}}
        @media screen and (max-width:380px){body.result-report .print-header,body.result-report .table-wrapper,body.result-report .moyenne-classe,body.result-report .signatures{zoom:.35}}
        @media print {
            @page { size:A4 landscape; margin:8mm; }
            *,*::before,*::after { -webkit-print-color-adjust:exact!important; print-color-adjust:exact!important; }
            nav,header,footer,.filters-grid,.actions,.novaskol-global-actions,.global-dropdown,.novaskol-loader { display:none!important; }
            html,body,main,.form-container { background:#fff!important; color:#111!important; margin:0!important; padding:0!important; border:0!important; box-shadow:none!important; }
            .print-header { display:block!important; text-align:center!important; margin:0 0 6mm!important; color:#111!important; border-bottom:2px solid #0f70c0!important; padding-bottom:4mm!important; }
            .print-header .school-logo { max-width:70px!important; height:auto!important; margin:0 auto 4px!important; display:block!important; }
            .print-header h2 { text-align:center!important; color:#047857!important; font-size:15pt!important; margin:4px 0!important; }
            .print-header p { font-size:9pt!important; color:#333!important; margin:2px 0!important; }
            .table-wrapper { overflow:visible!important; border:0!important; width:auto!important; max-width:none!important; min-width:0!important; zoom:1!important; transform:none!important; }
            .print-header,.moyenne-classe,.signatures{width:auto!important;max-width:none!important;min-width:0!important;zoom:1!important;transform:none!important}
            .result-table{width:100%!important;color:#000!important;background:#fff!important;font-size:8pt!important;min-width:0!important;table-layout:fixed!important;border-collapse:collapse!important;}
            .result-table th,.result-table td{border:1px solid #555!important;color:#000!important;background:#fff!important;padding:4px!important;word-break:break-word!important;}
            .result-table th{background:#034a3b!important;color:#fff!important;}
            .result-table td:nth-child(2),.result-table th:nth-child(2){background:#f1f5f9!important;color:#111!important;}
            .coef-row td{background:#e5e7eb!important;color:#000!important;}
            .coef-row td:empty, .result-table td:empty { border:0!important; background:transparent!important; }
            .moyenne-classe{color:#000!important;border:1px solid #555!important;display:block!important;background:#f0fdf4!important;text-align:center!important;padding:6px!important;margin-top:6mm!important;}
            .signatures{display:flex!important;justify-content:space-between!important;margin-top:8mm!important;gap:40px!important;}
            .signatures div{flex:1!important;text-align:center!important;border-top:2px solid #333!important;padding-top:8px!important;color:#555!important;}
            .signatures div div{display:none!important;}
        }
    </style>
</head>
<body class="result-report">
@php
    $logo = $ecole->logo ?? 'novaskol.png';
    $logoPath = str_starts_with($logo, 'images/') ? substr($logo, 7) : $logo;
@endphp
@include('modules.professeur.bulletin.partials.shell', ['activeModule' => 'resultats'])
<header>
    <div class="header-left">
        <button class="burger-menu" onclick="toggleSidebar()"><i class="fa fa-bars"></i></button>
        <button id="fullscreen-btn" onclick="toggleFullscreen()"><i id="fullscreen-icon" class="fa fa-expand"></i></button>
    </div>
    <div class="header-center"><i class="fa fa-line-chart"></i> Resultat en un clic</div>
</header>
<main>
    <div class="form-container">
        <form method="GET" action="{{ route('modules.resultats') }}">
            <div class="filters-grid">
                <div>
                    <label for="annee_scolaire">Annee scolaire</label>
                    <select name="annee_scolaire" id="annee_scolaire" required onchange="this.form.submit()">
                        <option value="">Choisir annee scolaire</option>
                        @foreach ($annees as $annee)
                            <option value="{{ $annee }}" @selected($selectedAnnee === $annee)>{{ $annee }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="periode">Periode</label>
                    <select name="periode" id="periode" required onchange="this.form.submit()">
                        <option value="">Choisir periode</option>
                        @foreach ($periodLabels as $key => $label)
                            <option value="{{ $key }}" @selected($selectedPeriode === $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="id_classe">Classe</label>
                    <select name="id_classe" id="id_classe" required onchange="this.form.submit()">
                        <option value="">Choisir classe</option>
                        @foreach ($classes as $classe)
                            <option value="{{ $classe->id }}" @selected($selectedClasse === $classe->id)>{{ $classe->nom }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </form>

        @if ($resultData)
            @php
                $studentsById = $resultData['students']->keyBy('id');
                $notes = $resultData['notesByPeriod'][$selectedPeriode] ?? [];
            @endphp
            <div class="print-header">
                <img class="school-logo" src="{{ asset('legacy/images/'.$logoPath) }}" alt="Logo" style="display:none">
                <h2>{{ $ecole->nom ?? 'Ecole' }}</h2>
                <p>Resultats - {{ $selectedAnnee }} - {{ $periodLabels[$selectedPeriode] ?? $selectedPeriode }} - Classe {{ $resultData['classe']->nom ?? '' }}</p>
            </div>
            <div class="table-wrapper">
                <table class="result-table">
                    <thead>
                        <tr>
                            <th>Rang</th>
                            <th>Nom de l'eleve</th>
                            @foreach ($resultData['subjects'] as $subject)
                                <th>{{ $subject->nom }}</th>
                            @endforeach
                            <th>Total</th>
                            <th>Moyenne</th>
                        </tr>
                        <tr class="coef-row">
                            <td></td>
                            <td>Coefficient</td>
                            @foreach ($resultData['subjects'] as $subject)
                                <td>{{ $subject->coefficient }}</td>
                            @endforeach
                            <td></td>
                            <td></td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($resultData['orderedIds'] as $studentId)
                            @php
                                $student = $studentsById[$studentId] ?? null;
                                $total = 0;
                                $coefTotal = 0;
                            @endphp
                            @if ($student)
                                <tr>
                                    <td>{{ $resultData['resultRanks'][$studentId] ?? '-' }}</td>
                                    <td>{{ $student->prenom }} {{ $student->nom }}</td>
                                    @foreach ($resultData['subjects'] as $subject)
                                        @php
                                            $note = $notes[$studentId][$subject->id] ?? null;
                                            $noteValue = is_numeric($note) ? (float) $note : 0;
                                            $total += $noteValue * (float) $subject->coefficient;
                                            $coefTotal += (float) $subject->coefficient;
                                        @endphp
                                        <td>{{ is_numeric($note) ? number_format($note, 2, ',', ' ') : '-' }}</td>
                                    @endforeach
                                    <td>{{ number_format($total, 2, ',', ' ') }}</td>
                                    <td><strong>{{ is_numeric($resultData['resultAverages'][$studentId] ?? null) ? number_format($resultData['resultAverages'][$studentId], 2, ',', ' ') : '-' }}</strong></td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="moyenne-classe">Moyenne generale de la classe : {{ is_numeric($resultData['resultClassAverage']) ? number_format($resultData['resultClassAverage'], 2, ',', ' ') : $resultData['resultClassAverage'] }}</div>
            <div class="signatures">
                <div><div></div>Directeur</div>
                <div><div></div>Enseignants</div>
            </div>
            <div class="actions"><button class="kaly" onclick="window.print()"><i class="fa fa-print"></i> Imprimer</button></div>
        @endif
    </div>
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
