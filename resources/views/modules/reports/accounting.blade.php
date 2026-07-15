<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Rapport comptable</title>
    <link rel="stylesheet" href="{{ asset('legacy/assets/fontawesome/css/all.min.css') }}">
    @include('modules.professeur.bulletin.partials.styles')
    @include('modules.reports.partials.styles')
    <script src="{{ asset('legacy/js/chart.min.js') }}"></script>
    <style>
        .accounting-page main { max-width: 1400px; margin: 0 auto; }
        .acc-stats-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 14px; margin: 20px 0; }
        .acc-stat { background: var(--card); border: 1px solid var(--border); border-radius: 14px; padding: 20px; text-align: center; transition: transform 0.2s, box-shadow 0.2s; }
        .acc-stat:hover { transform: translateY(-3px); box-shadow: 0 8px 24px var(--glow); }
        .acc-stat .acc-icon { width: 48px; height: 48px; border-radius: 12px; display: grid; place-items: center; margin: 0 auto 12px; font-size: 1.2rem; }
        .acc-stat .acc-icon.green { background: rgba(16,185,129,0.12); color: #10b981; }
        .acc-stat .acc-icon.red { background: rgba(239,68,68,0.12); color: #ef4444; }
        .acc-stat .acc-icon.blue { background: rgba(59,130,246,0.12); color: #3b82f6; }
        .acc-stat span { display: block; color: var(--text-sec); font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; font-weight: 600; margin-bottom: 6px; }
        .acc-stat strong { display: block; font-size: 1.6rem; font-weight: 800; color: var(--text); }
        .acc-stat .green { color: #10b981; }
        .acc-stat .red { color: #ef4444; }
        .acc-stat .neutral { color: var(--text); }
        .section-title { display: flex; align-items: center; gap: 10px; margin: 28px 0 16px; font-size: 1.1rem; font-weight: 800; color: var(--text); }
        .section-title i { color: var(--primary); font-size: 1.1rem; }
        @media (max-width: 900px) { .acc-stats-grid { grid-template-columns: 1fr 1fr; } }
        @media (max-width: 600px) { .acc-stats-grid { grid-template-columns: 1fr; } .acc-stat strong { font-size: 1.3rem; } }
        @media print {
            @page { size: A4 landscape; margin: 6mm; }
            *, *::before, *::after { -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }
            html, body { background: #fff !important; margin: 0 !important; padding: 0 !important; color: #111 !important; }
            nav, header, footer, .no-print { display: none !important; }
            main { margin: 0 !important; padding: 0 !important; max-width: none !important; }
            .acc-stats-grid { grid-template-columns: repeat(3, 1fr) !important; gap: 6px !important; margin: 0 0 8px !important; }
            .acc-stat { background: #f8fafc !important; border: 1px solid #cbd5e1 !important; box-shadow: none !important; padding: 10px !important; border-radius: 0 !important; break-inside: avoid; }
            .acc-stat strong { color: #111 !important; font-size: 1.2rem !important; }
            .acc-stat span { font-size: 0.65rem !important; }
            .acc-stat .acc-icon { display: none !important; }
            .section-title { margin: 14px 0 8px !important; font-size: 0.95rem !important; }
            .section-title i { display: none !important; }
            .chart-card { background: #fff !important; border: 1px solid #cbd5e1 !important; break-inside: avoid; padding: 8px !important; border-radius: 0 !important; }
            .chart-card h2 { font-size: 9pt !important; margin: 0 0 6px !important; }
            .chart-grid { grid-template-columns: 1fr 1fr !important; gap: 6px !important; }
            .chart-grid .chart-card:nth-child(1),
            .chart-grid .chart-card:nth-child(2) { break-inside: avoid; }
            .report-panel:not(.no-print) { border: 0 !important; padding: 0 !important; margin: 0 !important; }
        }
    </style>
</head>
<body class="accounting-report">
@include('modules.professeur.bulletin.partials.shell')
<header>
    <div class="header-left">
        <button class="burger-menu" onclick="toggleSidebar()"><i class="fa fa-bars"></i></button>
        <button id="fullscreen-btn" onclick="toggleFullscreen()"><i class="fa fa-expand"></i></button>
    </div>
    <div class="header-center"><i class="fas fa-calculator" style="color:var(--primary);margin-right:8px;"></i> Rapport comptable</div>
</header>
<main>
    <section class="report-panel no-print">
        <form method="GET" class="report-grid">
            <div>
                <label>Annee</label>
                <select name="annee_scolaire">
                    <option value="">Toutes</option>
                    @foreach($annees as $a)
                        <option value="{{ $a }}" @selected($selectedAnnee === $a)>{{ $a }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label>Mois</label>
                <select name="mois">
                    <option value="">Tous</option>
                    @foreach($months as $m)
                        <option value="{{ $m }}" @selected($selectedMonth === $m)>{{ $m }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label>Semaine du</label>
                <input type="date" name="week_start" value="{{ $weekStart }}">
            </div>
            <button class="kaly"><i class="fas fa-filter"></i> Filtrer</button>
            <button type="button" class="kaly" onclick="window.print()"><i class="fas fa-print"></i> Imprimer</button>
        </form>
    </section>

    <div class="acc-stats-grid">
        <div class="acc-stat">
            <div class="acc-icon green"><i class="fas fa-arrow-up"></i></div>
            <span>Total revenus</span>
            <strong class="green">{{ number_format($totalRevenus, 0, ',', ' ') }} {{ novaskol_currency() }}</strong>
        </div>
        <div class="acc-stat">
            <div class="acc-icon red"><i class="fas fa-arrow-down"></i></div>
            <span>Total depenses</span>
            <strong class="red">{{ number_format($totalDepenses, 0, ',', ' ') }} {{ novaskol_currency() }}</strong>
        </div>
        <div class="acc-stat">
            <div class="acc-icon blue"><i class="fas fa-balance-scale"></i></div>
            <span>Solde</span>
            <strong class="{{ $solde >= 0 ? 'green' : 'red' }}">{{ number_format($solde, 0, ',', ' ') }} {{ novaskol_currency() }}</strong>
        </div>
    </div>

    <div class="section-title"><i class="fas fa-chart-line"></i> Analyses financieres</div>

    <section class="chart-grid">
        <div class="chart-card" style="grid-column:1/-1">
            <h2><i class="fas fa-calendar-week" style="color:var(--primary);margin-right:6px;"></i> Evolution hebdomadaire</h2>
            <canvas id="weekly"></canvas>
        </div>
        <div class="chart-card">
            <h2><i class="fas fa-chart-bar" style="color:var(--primary);margin-right:6px;"></i> Evolution mensuelle</h2>
            <canvas id="monthly"></canvas>
        </div>
        <div class="chart-card">
            <h2><i class="fas fa-chart-pie" style="color:var(--primary);margin-right:6px;"></i> Repartition globale</h2>
            <canvas id="global"></canvas>
        </div>
        <div class="chart-card">
            <h2><i class="fas fa-tags" style="color:var(--primary);margin-right:6px;"></i> Categories revenus</h2>
            <canvas id="revCat"></canvas>
        </div>
        <div class="chart-card">
            <h2><i class="fas fa-tags" style="color:var(--primary);margin-right:6px;"></i> Categories depenses</h2>
            <canvas id="depCat"></canvas>
        </div>
    </section>

    <footer>&copy; {{ date('Y') }} {{ $ecole->nom ?? 'Ecole' }}</footer>
</main>
<script>
function toggleSidebar(){document.getElementById('sidebar').classList.toggle('active')}
function toggleSub(e){const n=e.nextElementSibling;if(n)n.style.display=n.style.display==='none'?'block':'none'}
function toggleFullscreen(){document.fullscreenElement?document.exitFullscreen():document.documentElement.requestFullscreen()}

let accChartInstances = [];

function getAccChartTheme() {
    const theme = localStorage.getItem('novaskol-theme') || localStorage.getItem('theme') || 'dark';
    const isDark = theme === 'dark';
    return {
        isDark,
        textColor: isDark ? '#f8fafc' : '#0f172a',
        gridColor: isDark ? 'rgba(255,255,255,0.10)' : 'rgba(0,0,0,0.07)',
        tooltipBg: isDark ? 'rgba(15,23,42,0.96)' : 'rgba(255,255,255,0.97)',
        tooltipTitle: isDark ? '#f8fafc' : '#0f172a',
        tooltipBody: isDark ? '#cbd5e1' : '#475569',
        tooltipBorder: isDark ? 'rgba(255,255,255,0.08)' : 'rgba(0,0,0,0.06)'
    };
}

function accMakeOpts(theme) {
    return {
        responsive: true,
        maintainAspectRatio: false,
        animation: { duration: 1000, easing: 'easeOutQuart' },
        plugins: {
            legend: { labels: { color: theme.textColor, font: { size: 12, weight: '700' }, usePointStyle: true, pointStyle: 'circle', padding: 16 } },
            tooltip: {
                backgroundColor: theme.tooltipBg, titleColor: theme.tooltipTitle, bodyColor: theme.tooltipBody,
                borderColor: theme.tooltipBorder, borderWidth: 1, padding: 14, cornerRadius: 12, boxPadding: 8, usePointStyle: true, caretSize: 8,
                titleFont: { weight: '700', size: 13 }, bodyFont: { size: 12 }
            }
        },
        scales: {
            x: { ticks: { color: theme.textColor, font: { size: 12, weight: '700' } }, grid: { display: false } },
            y: { ticks: { color: theme.textColor, font: { size: 12, weight: '700' }, callback: function(v) { return new Intl.NumberFormat('fr-FR',{notation:'compact'}).format(v); } }, grid: { color: theme.gridColor, drawBorder: false } }
        }
    };
}

function accPieTooltip(theme) {
    return {
        backgroundColor: theme.tooltipBg, titleColor: theme.tooltipTitle, bodyColor: theme.tooltipBody,
        borderColor: theme.tooltipBorder, borderWidth: 1, padding: 12, cornerRadius: 10,
        callbacks: {
            label: function(ctx) {
                const t = ctx.dataset.data.reduce((a,b)=>a+b,0);
                const p = t > 0 ? ((ctx.raw/t)*100).toFixed(1) : 0;
                return ctx.label + ': ' + new Intl.NumberFormat('fr-FR').format(ctx.raw) + ' {{ novaskol_currency() }} (' + p + '%)';
            }
        }
    };
}

function accPieLegend(theme, size) {
    return { position: 'bottom', labels: { color: theme.textColor, font: { size: size || 12, weight: '600' }, usePointStyle: true, pointStyle: 'circle', padding: 16 } };
}

function initAccCharts() {
    accChartInstances.forEach(c => c.destroy());
    accChartInstances = [];
    const t = getAccChartTheme();
    const months = @json($months);

    accChartInstances.push(new Chart(document.getElementById('weekly'), { type: 'line', data: {
        labels: @json($weekLabels),
        datasets: [
            { label: 'Revenus', data: @json($weeklyRevenus), borderColor: '#10b981', backgroundColor: 'rgba(16,185,129,0.12)', fill: true, tension: 0.4, pointRadius: 4, pointHoverRadius: 6, borderWidth: 2 },
            { label: 'Depenses', data: @json($weeklyDepenses), borderColor: '#ef4444', backgroundColor: 'rgba(239,68,68,0.10)', fill: true, tension: 0.4, pointRadius: 4, pointHoverRadius: 6, borderWidth: 2 }
        ]
    }, options: { ...accMakeOpts(t), plugins: { ...accMakeOpts(t).plugins, tooltip: { ...accMakeOpts(t).plugins.tooltip, mode: 'index', intersect: false } } } }));

    accChartInstances.push(new Chart(document.getElementById('monthly'), { type: 'bar', data: {
        labels: months,
        datasets: [
            { label: 'Revenus', data: @json($monthlyRevenus), backgroundColor: 'rgba(16,185,129,0.7)', borderColor: '#10b981', borderWidth: 2, borderRadius: 4, barPercentage: 0.65 },
            { label: 'Depenses', data: @json($monthlyDepenses), backgroundColor: 'rgba(239,68,68,0.7)', borderColor: '#ef4444', borderWidth: 2, borderRadius: 4, barPercentage: 0.65 }
        ]
    }, options: accMakeOpts(t) }));

    accChartInstances.push(new Chart(document.getElementById('global'), { type: 'doughnut', data: {
        labels: ['Revenus', 'Depenses'],
        datasets: [{ data: [{{ $totalRevenus }}, {{ $totalDepenses }}], backgroundColor: ['rgba(16,185,129,0.85)', 'rgba(239,68,68,0.85)'], borderColor: ['#10b981', '#ef4444'], borderWidth: 2, hoverOffset: 8 }]
    }, options: { responsive: true, maintainAspectRatio: false, animation: { duration: 1000 }, plugins: { legend: accPieLegend(t, 12), tooltip: accPieTooltip(t) } } }));

    accChartInstances.push(new Chart(document.getElementById('revCat'), { type: 'pie', data: {
        labels: @json($revenusCategories->pluck('categorie')),
        datasets: [{ data: @json($revenusCategories->pluck('total')), backgroundColor: ['rgba(16,185,129,0.85)','rgba(52,211,153,0.85)','rgba(14,165,233,0.85)','rgba(59,130,246,0.85)','rgba(139,92,246,0.85)'], borderColor: ['#10b981','#34d399','#0ea5e9','#3b82f6','#8b5cf6'], borderWidth: 2 }]
    }, options: { responsive: true, maintainAspectRatio: false, animation: { duration: 1000 }, plugins: { legend: accPieLegend(t, 10), tooltip: accPieTooltip(t) } } }));

    accChartInstances.push(new Chart(document.getElementById('depCat'), { type: 'pie', data: {
        labels: @json($depensesCategories->pluck('categorie')),
        datasets: [{ data: @json($depensesCategories->pluck('total')), backgroundColor: ['rgba(239,68,68,0.85)','rgba(249,115,22,0.85)','rgba(245,158,11,0.85)','rgba(168,85,247,0.85)','rgba(100,116,139,0.85)'], borderColor: ['#ef4444','#f97316','#f59e0b','#a855f7','#64748b'], borderWidth: 2 }]
    }, options: { responsive: true, maintainAspectRatio: false, animation: { duration: 1000 }, plugins: { legend: accPieLegend(t, 10), tooltip: accPieTooltip(t) } } }));
}

initAccCharts();
let _accThemeGuard = false;
document.addEventListener('themeChanged', function _accOnTheme() {
  if (_accThemeGuard) return;
  _accThemeGuard = true;
  initAccCharts();
  setTimeout(() => _accThemeGuard = false, 300);
});
</script>
</body>
</html>