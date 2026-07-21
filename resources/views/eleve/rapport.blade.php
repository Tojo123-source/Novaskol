<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Mon rapport - Novaskol</title>
    <link rel="stylesheet" href="{{ asset('legacy/assets/fontawesome/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('legacy/fa/css/font-awesome.min.css') }}">
    @include('modules.professeur.bulletin.partials.styles')
    @include('modules.reports.partials.styles')
    <script src="{{ asset('legacy/js/chart.min.js') }}"></script>
</head>
<body>
@include('modules.professeur.bulletin.partials.shell', ['activeModule' => 'eleve_rapport'])
<header>
    <div class="header-left">
        <button class="burger-menu" onclick="toggleSidebar()"><i class="fa fa-bars"></i></button>
        <button id="fullscreen-btn" onclick="toggleFullscreen()"><i class="fa fa-expand"></i></button>
    </div>
    <div class="header-center">Mon rapport d'apprentissage</div>
</header>
<main>
    @php
        $progPct = $totalChapitres > 0 ? round(($completedChapitres / $totalChapitres) * 100) : 0;
        $sorted = $parMatiere->isNotEmpty() ? $parMatiere->sortByDesc('avg_score') : collect();
        $forte = $sorted->first();
        $faible = $sorted->last();
    @endphp

    <section class="kpis">
        <div class="kpi"><span>Cours disponibles</span><strong>{{ $totalCourses }}</strong></div>
        <div class="kpi"><span>Chapitres termines</span><strong>{{ $completedChapitres }}/{{ $totalChapitres }}</strong></div>
        <div class="kpi"><span>Moyenne exercices</span><strong>{{ number_format($avgScore, 1, ',', ' ') }}/20</strong></div>
        <div class="kpi"><span>Moyenne notes</span><strong>{{ number_format($moyenneNotes, 1, ',', ' ') }}/20</strong></div>
        <div class="kpi"><span>Progression</span><strong>{{ $progPct }}%</strong></div>
    </section>

    @if ($coursesList->isNotEmpty())
    <section class="report-panel">
        <h2>Cours disponibles</h2>
        <div class="report-table-wrap">
            <table class="report-table">
                <thead><tr><th>Cours</th><th>Progression</th><th>Statut</th></tr></thead>
                <tbody>
                @foreach ($coursesList as $c)
                    @php
                        $prog = $progressionDetails->get($c->id);
                        $totalCh = DB::table('course_chapitres')->where('course_id', $c->id)->where('statut', 'publie')->count();
                        $doneCh = $prog ? (int) $prog->termines : 0;
                        $pct = $totalCh > 0 ? round(($doneCh / $totalCh) * 100) : 0;
                    @endphp
                    <tr>
                        <td><strong>{{ $c->titre }}</strong></td>
                        <td>{{ $doneCh }}/{{ $totalCh }} chapitres</td>
                        <td>
                            <div style="display:flex;align-items:center;gap:6px">
                                <div style="flex:1;max-width:100px;height:6px;background:var(--border);border-radius:3px;overflow:hidden">
                                    <div style="height:100%;width:{{ $pct }}%;background:{{ $pct >= 100 ? 'var(--success)' : 'var(--primary)' }};border-radius:3px"></div>
                                </div>
                                <span style="font-size:.8rem;color:var(--text-sec)">{{ $pct }}%</span>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </section>
    @endif

    @if ($parMatiere->isNotEmpty())
    <section class="report-panel">
        <h2>Performance par matiere</h2>
        <div class="report-table-wrap">
            <table class="report-table">
                <thead><tr><th>Matiere</th><th>Moyenne</th><th>Exercices</th></tr></thead>
                <tbody>
                @foreach ($parMatiere as $m)
                    <tr>
                        <td><strong>{{ $m->matiere }}</strong></td>
                        <td class="money" style="color:{{ $m->avg_score >= 12 ? 'var(--success)' : ($m->avg_score >= 8 ? 'var(--orange)' : 'var(--danger)') }}">{{ number_format($m->avg_score, 1, ',', ' ') }}/20</td>
                        <td>{{ $m->total }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </section>
    @endif

    @if ($forte || $faible)
    <section class="kpis" style="grid-template-columns:1fr 1fr">
        @if ($forte)
        <div class="kpi"><span>Matiere forte</span><strong style="color:var(--success);font-size:1.1rem">{{ $forte->matiere }}</strong></div>
        @endif
        @if ($faible && $faible->matiere !== $forte->matiere)
        <div class="kpi"><span>Matiere a ameliorer</span><strong style="color:var(--orange);font-size:1.1rem">{{ $faible->matiere }}</strong></div>
        @endif
    </section>
    @endif

    <section class="chart-grid">
        @if ($parMatiere->isNotEmpty())
        <div class="chart-card">
            <h2>Moyenne par matiere</h2>
            <canvas id="subjectChart"></canvas>
        </div>
        @endif
        @if ($evolution->isNotEmpty())
        <div class="chart-card">
            <h2>Evolution mensuelle</h2>
            <canvas id="evolutionChart"></canvas>
        </div>
        @endif
        @if ($totalChapitres > 0)
        <div class="chart-card">
            <h2>Progression</h2>
            <canvas id="progressChart"></canvas>
        </div>
        @endif
    </section>
</main>
<script>
function toggleSub(e){const s=e.nextElementSibling,a=e.querySelector('.arrow');s.style.display=s.style.display==='block'?'none':'block';a.classList.toggle('fa-chevron-down');a.classList.toggle('fa-chevron-up')}
function toggleSidebar(){document.getElementById('sidebar').classList.toggle('active')}
function toggleFullscreen(){document.fullscreenElement?document.exitFullscreen():document.documentElement.requestFullscreen()}
const chartOpts={responsive:true,maintainAspectRatio:false,plugins:{legend:{labels:{color:'#e5e7eb'}}},scales:{x:{ticks:{color:'#9ca3af'},grid:{color:'rgba(156,163,175,.12)'}},y:{ticks:{color:'#9ca3af'},grid:{color:'rgba(156,163,175,.12)'}}}};
@if ($parMatiere->isNotEmpty())
new Chart(document.getElementById('subjectChart'),{type:'bar',data:{labels:@json($parMatiere->pluck('matiere')->values()),datasets:[{label:'Moyenne',data:@json($parMatiere->pluck('avg_score')->values()),backgroundColor:['#3b82f6','#f59e0b','#00c853','#8b5cf6','#ef4444','#14b8a6','#ec4899','#6366f1']}]},options:chartOpts});
@endif
@if ($evolution->isNotEmpty())
new Chart(document.getElementById('evolutionChart'),{type:'line',data:{labels:@json($evolution->pluck('mois')->values()),datasets:[{label:'Score moyen',data:@json($evolution->pluck('score_moyen')->values()),borderColor:'#00c853',backgroundColor:'rgba(0,200,83,.08)',fill:true,tension:.35}]},options:chartOpts});
@endif
@if ($totalChapitres > 0)
new Chart(document.getElementById('progressChart'),{type:'doughnut',data:{labels:['Termines','Restants'],datasets:[{data:[{{ $completedChapitres }},{{ max(0,$totalChapitres-$completedChapitres) }}],backgroundColor:['#00c853','rgba(156,163,175,.24)']}]},options:{responsive:true,maintainAspectRatio:false,plugins:{legend:{labels:{color:'#e5e7eb'}}}}});
@endif
</script>
</body>
</html>
