<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Evaluation des notes</title>
    <link rel="stylesheet" href="{{ asset('legacy/assets/fontawesome/css/all.min.css') }}">
    @include('modules.professeur.bulletin.partials.styles')
    @include('modules.reports.partials.styles')
    <script src="{{ asset('legacy/js/chart.min.js') }}"></script>
</head>
<body>
@php
    $generalAverage = (float) $avgBySubject->avg('moyenne');
    $bestClass = $avgByClass->sortByDesc('moyenne')->first();
    $bestSubject = $avgBySubject->sortByDesc('moyenne')->first();
    $periods = ['B1', 'B2', 'T1', 'T2', 'T3'];
    $periodClasses = $avgByClassPeriod->pluck('classe')->unique()->values();
@endphp
@include('modules.professeur.bulletin.partials.shell')
<header>
    <div class="header-left">
        <button class="burger-menu" onclick="toggleSidebar()"><i class="fa fa-bars"></i></button>
        <button id="fullscreen-btn" onclick="toggleFullscreen()"><i class="fa fa-expand"></i></button>
    </div>
    <div class="header-center">Evaluation des notes</div>
</header>
<main>
    <section class="report-panel no-print">
        <form method="GET" class="report-grid">
            <div>
                <label>Annee</label>
                <select name="annee_scolaire">
                    @foreach($annees as $a)
                        <option value="{{ $a }}" @selected($selectedAnnee === $a)>{{ $a }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label>Classe</label>
                <select name="classe_id" onchange="this.form.submit()">
                    <option value="">Toutes les classes</option>
                    @foreach($classes as $classe)
                        <option value="{{ $classe->id }}" @selected($selectedClasse === (int) $classe->id)>{{ $classe->nom }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label>Eleve detail</label>
                <select name="eleve_id">
                    <option value="">Vue globale</option>
                    @foreach($studentsForFilter as $student)
                        <option value="{{ $student->id }}" @selected($selectedStudentId === (int) $student->id)>{{ $student->nom }} {{ $student->prenom }}</option>
                    @endforeach
                </select>
            </div>
            <button class="kaly">Analyser</button>
            <button type="button" class="kaly" onclick="window.print()">Imprimer</button>
        </form>
    </section>

    <section class="kpis">
        <div class="kpi"><span>Classes</span><strong>{{ $classes->count() }}</strong></div>
        <div class="kpi"><span>Eleves classes</span><strong>{{ (int) $studentsByClass->sum('total') }}</strong></div>
        <div class="kpi"><span>Moyenne generale</span><strong>{{ number_format($generalAverage, 2, ',', ' ') }}</strong></div>
        <div class="kpi"><span>Meilleure classe</span><strong>{{ $bestClass ? $bestClass->nom.' - '.number_format((float) $bestClass->moyenne, 2, ',', ' ') : '-' }}</strong></div>
        <div class="kpi"><span>Meilleure matiere</span><strong>{{ $bestSubject ? $bestSubject->nom.' - '.number_format((float) $bestSubject->moyenne, 2, ',', ' ') : '-' }}</strong></div>
    </section>

    <section class="report-panel">
        <h2>Presence des eleves</h2>
        <div class="report-table-wrap">
            <table class="report-table">
                <thead><tr><th>Classe</th><th>Presents</th><th>Absents</th><th>Retards</th><th>Taux presence</th></tr></thead>
                <tbody>
                    @forelse($attendanceByClass as $row)
                        <tr>
                            <td>{{ $row->classe }}</td>
                            <td class="money">{{ $row->presents }}</td>
                            <td>{{ $row->absents }}</td>
                            <td>{{ $row->retards }}</td>
                            <td class="money">{{ number_format(((int) $row->presents / max(1, (int) $row->total)) * 100, 1, ',', ' ') }}%</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="muted">Aucune presence numerique enregistree pour ce filtre.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    @if($selectedStudent)
        <section class="report-panel">
            <h2>Detail eleve - {{ $selectedStudent->nom }} {{ $selectedStudent->prenom }}</h2>
            <div class="kpis">
                @php($studentPresence = $attendanceByStudent->first())
                <div class="kpi"><span>Classe</span><strong>{{ $selectedStudent->classe ?? '-' }}</strong></div>
                <div class="kpi"><span>Presents</span><strong>{{ $studentPresence->presents ?? 0 }}</strong></div>
                <div class="kpi"><span>Absences</span><strong>{{ $studentPresence->absents ?? 0 }}</strong></div>
                <div class="kpi"><span>Retards</span><strong>{{ $studentPresence->retards ?? 0 }}</strong></div>
            </div>
            <div class="report-table-wrap">
                <table class="report-table">
                    <thead><tr><th>Matiere</th><th>Periode</th><th>Moyenne</th></tr></thead>
                    <tbody>
                    @forelse($selectedStudentNotes as $note)
                        <tr><td>{{ $note->matiere }}</td><td>{{ $note->periode }}</td><td class="money">{{ number_format((float) $note->moyenne, 2, ',', ' ') }}</td></tr>
                    @empty
                        <tr><td colspan="3" class="muted">Aucune note detaillee pour cet eleve.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    @else
        <section class="report-panel">
            <h2>Eleves a suivre</h2>
            <div class="report-table-wrap">
                <table class="report-table">
                    <thead><tr><th>Eleve</th><th>Classe</th><th>Presents</th><th>Absents</th><th>Retards</th><th>Taux</th></tr></thead>
                    <tbody>
                        @forelse($attendanceByStudent as $row)
                            <tr>
                                <td>{{ $row->nom }} {{ $row->prenom }}</td>
                                <td>{{ $row->classe }}</td>
                                <td>{{ $row->presents }}</td>
                                <td>{{ $row->absents }}</td>
                                <td>{{ $row->retards }}</td>
                                <td class="money">{{ number_format(((int) $row->presents / max(1, (int) $row->total)) * 100, 1, ',', ' ') }}%</td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="muted">Aucun detail de presence disponible.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    @endif

    <section class="chart-grid">
        <div class="chart-card">
            <h2>Effectif par classe</h2>
            <canvas id="studentsClass"></canvas>
        </div>
        <div class="chart-card">
            <h2>Moyenne par classe</h2>
            <canvas id="avgClass"></canvas>
        </div>
        <div class="chart-card">
            <h2>Moyenne par matiere</h2>
            <canvas id="avgSubject"></canvas>
        </div>
        <div class="chart-card">
            <h2>Mentions</h2>
            <canvas id="mentionsChart"></canvas>
        </div>
        <div class="chart-card" style="grid-column:1/-1">
            <h2>Evolution des moyennes par periode</h2>
            <canvas id="periodChart"></canvas>
        </div>
    </section>

    <section class="report-panel">
        <h2>Top 10 eleves</h2>
        <div class="report-table-wrap">
            <table class="report-table">
                <thead>
                    <tr><th>Rang</th><th>Eleve</th><th>Classe</th><th>Moyenne</th></tr>
                </thead>
                <tbody>
                    @forelse($topStudents as $i => $s)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $s->nom }} {{ $s->prenom }}</td>
                            <td>{{ $s->classe }}</td>
                            <td class="money">{{ number_format((float) $s->moyenne, 2, ',', ' ') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="muted">Aucune note.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    <section class="report-panel">
        <h2>Top 3 par classe</h2>
        <div class="top-class-grid">
            @forelse($topByClass as $classe => $students)
                <article class="rank-card">
                    <h3>{{ $classe }}</h3>
                    @foreach($students as $index => $student)
                        <p>
                            <span>{{ $index + 1 }}. {{ $student->nom }} {{ $student->prenom }}</span>
                            <strong>{{ number_format((float) $student->moyenne, 2, ',', ' ') }}</strong>
                        </p>
                    @endforeach
                </article>
            @empty
                <p class="muted">Aucun classement par classe disponible.</p>
            @endforelse
        </div>
    </section>

    <section class="report-panel">
        <h2>Lecture detaillee</h2>
        <div class="report-table-wrap">
            <table class="report-table">
                <thead>
                    <tr><th>Classe</th><th>Moyenne</th><th>Effectif</th><th>Observation</th></tr>
                </thead>
                <tbody>
                    @forelse($avgByClass as $classAvg)
                        <tr>
                            <td>{{ $classAvg->nom }}</td>
                            <td class="money">{{ number_format((float) $classAvg->moyenne, 2, ',', ' ') }}</td>
                            <td>{{ (int) optional($studentsByClass->firstWhere('nom', $classAvg->nom))->total }}</td>
                            <td>{{ $classAvg->moyenne >= 12 ? 'Progression favorable' : 'A renforcer' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="muted">Aucune moyenne par classe.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    <footer>&copy; {{ date('Y') }} {{ $ecole->nom ?? 'Ecole' }}</footer>
</main>
<script>
function toggleSidebar(){document.getElementById('sidebar').classList.toggle('active')}
function toggleSub(e){const n=e.nextElementSibling;if(n)n.style.display=n.style.display==='none'?'block':'none'}
function toggleFullscreen(){document.fullscreenElement?document.exitFullscreen():document.documentElement.requestFullscreen()}
const chartOptions={responsive:true,maintainAspectRatio:false,plugins:{legend:{labels:{color:'#e5e7eb'}}},scales:{x:{ticks:{color:'#9ca3af'},grid:{color:'rgba(156,163,175,.12)'}},y:{ticks:{color:'#9ca3af'},grid:{color:'rgba(156,163,175,.12)'}}}};
new Chart(document.getElementById('studentsClass'),{type:'bar',data:{labels:@json($studentsByClass->pluck('nom')->values()),datasets:[{label:'Eleves',data:@json($studentsByClass->pluck('total')->values()),backgroundColor:'#3b82f6'}]},options:chartOptions});
new Chart(document.getElementById('avgClass'),{type:'bar',data:{labels:@json($avgByClass->pluck('nom')->values()),datasets:[{label:'Moyenne',data:@json($avgByClass->pluck('moyenne')->values()),backgroundColor:'#00c853'}]},options:chartOptions});
new Chart(document.getElementById('avgSubject'),{type:'bar',data:{labels:@json($avgBySubject->pluck('nom')->values()),datasets:[{label:'Moyenne',data:@json($avgBySubject->pluck('moyenne')->values()),backgroundColor:'#f59e0b'}]},options:chartOptions});
new Chart(document.getElementById('mentionsChart'),{type:'doughnut',data:{labels:@json(array_keys($mentions)),datasets:[{data:@json(array_values($mentions)),backgroundColor:['#ef4444','#f59e0b','#3b82f6','#00c853','#8b5cf6']}]},options:{responsive:true,maintainAspectRatio:false,plugins:{legend:{labels:{color:'#e5e7eb'}}}}});
new Chart(document.getElementById('periodChart'),{type:'line',data:{labels:@json($periods),datasets:[
@foreach($periodClasses as $className)
{label:@json($className),data:@json(collect($periods)->map(fn($p)=>(float) optional($avgByClassPeriod->first(fn($row)=>$row->classe===$className && $row->periode===$p))->moyenne)->values()),borderColor:['#00c853','#3b82f6','#f59e0b','#8b5cf6','#ef4444','#14b8a6'][{{ $loop->index }}%6],backgroundColor:'rgba(0,200,83,.08)',fill:false,tension:.35},
@endforeach
]},options:chartOptions});
</script>
</body>
</html>
