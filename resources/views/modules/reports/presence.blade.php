<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title }}</title>
    <link rel="stylesheet" href="{{ asset('legacy/assets/fontawesome/css/all.min.css') }}">
    @include('modules.professeur.bulletin.partials.styles')
    @include('modules.reports.partials.styles')
    <script src="{{ asset('legacy/js/chart.min.js') }}"></script>
    <style>
        .payslip-card {
            background: #fff;
            color: #0f172a;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 32px;
            box-shadow: 0 25px 70px rgba(0,0,0,0.08);
            position: relative;
            overflow: hidden;
            max-width: 900px;
            margin: 0 auto;
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
        }
        .payslip-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 5px;
            background: linear-gradient(90deg, #059669, #10b981, #34d399, #059669);
            background-size: 300% 100%;
            animation: shimmer 3s ease-in-out infinite;
        }
        @keyframes shimmer { 0%,100%{background-position:0 0} 50%{background-position:100% 0} }
        .payslip-card.payslip-global { max-width: 100%; }
        .payslip-card .watermark {
            position: absolute;
            top: 50%; left: 50%;
            transform: translate(-50%,-50%);
            font-size: 8rem;
            font-weight: 900;
            color: rgba(5,150,105,0.04);
            pointer-events: none;
            letter-spacing: 0.1em;
            white-space: nowrap;
            user-select: none;
        }
        .payslip-head {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 16px;
            border-bottom: 2px solid #e2e8f0;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }
        .payslip-brand { display: flex; gap: 16px; align-items: center; }
        .payslip-logo img {
            width: 60px; height: 60px;
            object-fit: contain;
            border: 2px solid #d1fae5;
            border-radius: 12px;
            background: #fff;
            padding: 6px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.06);
        }
        .payslip-school {
            display: block;
            color: #64748b;
            font-size: 0.72rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            font-weight: 600;
        }
        .payslip-title {
            display: block;
            color: #065f46;
            font-size: 1.3rem;
            font-weight: 800;
            margin-top: 2px;
            letter-spacing: 0.02em;
        }
        .payslip-ref { text-align: right; }
        .payslip-ref span {
            display: block;
            color: #64748b;
            font-size: 0.68rem;
            text-transform: uppercase;
            letter-spacing: 0.06em;
        }
        .payslip-ref strong {
            display: block;
            color: #0f172a;
            font-size: 0.92rem;
            font-weight: 700;
            margin-top: 4px;
        }
        .payslip-meta {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
            margin-bottom: 22px;
        }
        @media (max-width: 600px) {
            .payslip-meta { grid-template-columns: 1fr; }
        }
        .pm-item {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 12px 14px;
            transition: border-color 0.2s;
        }
        .pm-item:hover { border-color: #10b981; }
        .pm-item span {
            display: block;
            color: #64748b;
            font-size: 0.65rem;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            font-weight: 600;
        }
        .pm-item strong {
            display: block;
            color: #0f172a;
            font-size: 0.92rem;
            font-weight: 700;
            margin-top: 4px;
        }
        .payslip-body { margin-bottom: 18px; }
        .payslip-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.85rem;
            border-radius: 10px;
            overflow: hidden;
        }
        .payslip-table th, .payslip-table td {
            padding: 12px 14px;
            text-align: left;
            border: 1px solid #e2e8f0;
            color: #0f172a;
        }
        .payslip-table th {
            background: linear-gradient(135deg, #065f46, #047857);
            color: #fff;
            text-transform: uppercase;
            font-size: 0.68rem;
            letter-spacing: 0.06em;
            font-weight: 700;
        }
        .payslip-table tbody tr:nth-child(even) { background: #f8fafc; }
        .payslip-table tbody tr:hover { background: #ecfdf5; }
        .payslip-table .deduction-row td { color: #dc2626; }
        .payslip-table .deduction-row td.deduction { font-weight: 700; }
        .payslip-table-global { font-size: 0.82rem; }
        .payslip-table-global th, .payslip-table-global td { padding: 10px 12px; white-space: nowrap; }
        .payslip-table-global tbody tr td:first-child {
            font-weight: 600;
            color: #065f46;
        }
        .payslip-table-global tfoot th {
            background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
            color: #0f172a;
            font-weight: 800;
            border-top: 2px solid #065f46;
        }
        .payslip-total {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: linear-gradient(135deg, #065f46, #047857);
            color: #fff;
            border-radius: 12px;
            padding: 16px 20px;
            margin-top: 12px;
        }
        .payslip-total span {
            text-transform: uppercase;
            letter-spacing: 0.06em;
            font-size: 0.82rem;
            font-weight: 600;
        }
        .payslip-total strong {
            font-size: 1.5rem;
            color: #bbf7d0;
            font-weight: 800;
        }
        .payslip-summary-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 10px;
            margin: 18px 0;
        }
        .ps-item {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 14px 16px;
            text-align: center;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .ps-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.06);
        }
        .ps-item span {
            display: block;
            color: #64748b;
            font-size: 0.68rem;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            font-weight: 600;
            margin-bottom: 6px;
        }
        .ps-item strong { font-size: 1.15rem; font-weight: 800; }
        .ps-total strong { color: #0f172a; }
        .ps-net {
            background: linear-gradient(135deg, #065f46, #047857);
            border-color: #065f46;
        }
        .ps-net span { color: #bbf7d0; }
        .ps-net strong { color: #fff; font-size: 1.3rem; }
        .payslip-stats {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
            margin-bottom: 18px;
            padding: 14px 18px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
        }
        .pstat {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.82rem;
            color: #475569;
        }
        .pstat strong { color: #0f172a; font-weight: 700; margin-left: 2px; }
        .pstat-dot {
            width: 10px; height: 10px;
            border-radius: 50%;
            display: inline-block;
            flex-shrink: 0;
        }
        .pstat-dot.present { background: #059669; box-shadow: 0 0 6px rgba(5,150,105,0.3); }
        .pstat-dot.absent { background: #dc2626; box-shadow: 0 0 6px rgba(220,38,38,0.3); }
        .pstat-dot.retard { background: #d97706; box-shadow: 0 0 6px rgba(217,119,6,0.3); }
        .pstat-dot.rate { background: #3b82f6; box-shadow: 0 0 6px rgba(59,130,246,0.3); }
        .payslip-signatures {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 24px;
            margin-top: 36px;
        }
        .payslip-signatures div {
            text-align: center;
            border-top: 2px dashed #94a3b8;
            padding-top: 12px;
            color: #475569;
            font-weight: 600;
            font-size: 0.78rem;
            text-transform: uppercase;
            letter-spacing: 0.06em;
        }
        .global-mode-hint {
            text-align: center;
            padding: 16px;
            background: linear-gradient(135deg, #d1fae5, #a7f3d0);
            border: 1px solid #6ee7b7;
            border-radius: 12px;
            margin-bottom: 20px;
            color: #065f46;
            font-weight: 600;
            font-size: 0.92rem;
        }
        .global-mode-hint i { margin-right: 8px; }
        .payroll-stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
            gap: 12px;
            margin-bottom: 22px;
        }
        .payroll-stat {
            background: linear-gradient(135deg, #f0fdf4, #dcfce7);
            border: 1px solid #86efac;
            border-radius: 12px;
            padding: 16px;
            text-align: center;
        }
        .payroll-stat span {
            display: block;
            color: #065f46;
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            font-weight: 600;
            margin-bottom: 6px;
        }
        .payroll-stat strong {
            display: block;
            font-size: 1.5rem;
            font-weight: 800;
            color: #047857;
        }
        .payroll-stat.highlight {
            background: linear-gradient(135deg, #065f46, #047857);
            border-color: #065f46;
        }
        .payroll-stat.highlight span { color: #bbf7d0; }
        .payroll-stat.highlight strong { color: #fff; }
        .detail-section-global {
            margin-top: 14px;
        }
        .detail-toggle-btn {
            background: none;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 8px 16px;
            color: #475569;
            font-size: 0.82rem;
            cursor: pointer;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .detail-toggle-btn:hover {
            border-color: #10b981;
            color: #065f46;
            background: #f0fdf4;
        }
        .individual-detail-table {
            margin-top: 14px;
            display: none;
        }
        .individual-detail-table.visible { display: block; }
        .indi-payroll-breakdown {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            margin: 16px 0;
        }
        .indi-break-item {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 12px 14px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .indi-break-item span { color: #64748b; font-size: 0.82rem; font-weight: 500; }
        .indi-break-item strong { color: #0f172a; font-size: 1rem; font-weight: 700; }
        .indi-break-item.net {
            grid-column: 1 / -1;
            background: linear-gradient(135deg, #065f46, #047857);
            border-color: #065f46;
        }
        .indi-break-item.net span { color: #bbf7d0; }
        .indi-break-item.net strong { color: #fff; font-size: 1.2rem; }
        .indi-presence-bar {
            display: flex;
            height: 8px;
            border-radius: 4px;
            overflow: hidden;
            margin: 12px 0 6px;
            background: #e2e8f0;
        }
        .indi-presence-bar .bar-present { background: #059669; }
        .indi-presence-bar .bar-absent { background: #dc2626; }
        .indi-presence-bar .bar-retard { background: #d97706; }
        .payslip-eco-ribbon {
            display: inline-block;
            background: #d1fae5;
            color: #065f46;
            padding: 2px 10px;
            border-radius: 4px;
            font-size: 0.65rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }
        .print-btn-row {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-bottom: 16px;
        }
        .print-btn-row button {
            padding: 10px 20px;
            border-radius: 10px;
            border: 1px solid #e2e8f0;
            background: #fff;
            color: #0f172a;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.85rem;
        }
        .print-btn-row button:hover {
            border-color: #10b981;
            color: #065f46;
            background: #f0fdf4;
        }
        .print-btn-row button.primary {
            background: #065f46;
            color: #fff;
            border-color: #065f46;
        }
        .print-btn-row button.primary:hover {
            background: #047857;
        }
        @media (max-width: 980px) {
            .payslip-meta { grid-template-columns: 1fr 1fr; }
            .payslip-summary-grid { grid-template-columns: 1fr 1fr; }
            .payslip-head { flex-direction: column; align-items: flex-start; }
            .payslip-ref { text-align: left; width: 100%; }
            .indi-payroll-breakdown { grid-template-columns: 1fr; }
        }
        @media (max-width: 760px) {
            .payslip-card { padding: 20px; border-radius: 12px; }
            .payslip-meta, .payslip-summary-grid, .payroll-stats-grid { grid-template-columns: 1fr; }
            .payslip-table { font-size: 0.78rem; }
            .payslip-table th, .payslip-table td { padding: 8px; }
            .payslip-total { flex-direction: column; align-items: flex-start; gap: 8px; }
            .payslip-total strong { font-size: 1.2rem; }
            .payslip-signatures { grid-template-columns: 1fr; gap: 16px; margin-top: 24px; }
            .pstat { font-size: 0.76rem; }
            .payroll-stat strong { font-size: 1.2rem; }
        }
        @media print {
            @page { size: A4 portrait; margin: 6mm; }
            *, *::before, *::after { -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }
            html, body { background: #fff !important; margin: 0 !important; padding: 0 !important; color: #111 !important; }
            nav, header, footer, .no-print, .kpis, .chart-grid, .report-panel:not(.payslip-section), .print-btn-row { display: none !important; }
            main { margin: 0 !important; padding: 0 !important; background: #fff !important; }
            .payslip-section { display: block !important; background: #fff !important; border: 0 !important; padding: 0 !important; margin: 0 !important; }
            .payslip-section > h2 { display: none !important; }
            .payslip-card {
                max-width: none !important;
                min-height: 0 !important;
                margin: 0 !important;
                background: #fff !important;
                color: #111 !important;
                border: 1px solid #94a3b8 !important;
                box-shadow: none !important;
                break-after: page;
                page-break-after: always;
                break-inside: avoid;
                padding: 5mm !important;
                zoom: 1 !important;
                transform: none !important;
                border-radius: 0 !important;
                height: auto !important;
                max-height: none !important;
                overflow: visible !important;
            }
            .payslip-card:last-child { break-after: auto !important; page-break-after: auto !important; }
            .payslip-card::before { background: linear-gradient(90deg, #059669, #10b981) !important; height: 4px !important; }
            .payslip-card .watermark { display: none !important; }
            .payslip-head { border-bottom: 2px solid #065f46 !important; }
            .payslip-head strong, .pm-item strong, .payslip-table td, .payslip-signatures div, .pstat strong, .indi-break-item strong { color: #111 !important; }
            .payslip-head span, .pm-item span, .pstat { color: #475569 !important; }
            .pm-item, .payslip-stats, .indi-break-item { background: #f1f5f9 !important; border: 1px solid #cbd5e1 !important; }
            .payslip-table th { background: #065f46 !important; color: #fff !important; border-color: #065f46 !important; }
            .payslip-table td { border-color: #cbd5e1 !important; }
            .payslip-table tbody tr:nth-child(even) { background: #f8fafc !important; }
            .payslip-table-global tfoot th { background: #f1f5f9 !important; color: #111 !important; border-top: 2px solid #065f46 !important; }
            .payslip-total, .ps-net, .payroll-stat.highlight, .indi-break-item.net { background: linear-gradient(135deg, #065f46, #047857) !important; color: #fff !important; }
            .payslip-total strong, .ps-net strong, .payroll-stat.highlight strong, .indi-break-item.net strong { color: #bbf7d0 !important; }
            .payroll-stat { background: #f0fdf4 !important; border-color: #86efac !important; }
            .payroll-stat span { color: #065f46 !important; }
            .payroll-stat strong { color: #047857 !important; }
            .payslip-card.payslip-global { background: #fff !important; }
            .payslip-meta {
                display: grid !important;
                grid-template-columns: repeat(2, 1fr) !important;
                gap: 2mm !important;
                margin-bottom: 3mm !important;
            }
            .pm-item { padding: 2mm !important; font-size: 6pt !important; }
            .pm-item strong { font-size: 7pt !important; }
            .pm-item span { font-size: 5.5pt !important; }
            .payslip-signatures { display: grid !important; grid-template-columns: repeat(3, 1fr) !important; gap: 5mm !important; margin-top: 5mm !important; }
            .payslip-signatures div { border-top: none !important; border-bottom: 2px dashed #94a3b8 !important; padding-bottom: 6mm !important; font-size: 8pt !important; color: #475569 !important; min-height: 10mm !important; }
            .global-mode-hint { display: none !important; }
            .detail-toggle-btn { display: none !important; }
            .individual-detail-table { display: block !important; }
        }
    </style>
</head>
<body class="payslip-report">
@php
    $unitLabel = $unitColumn === 'horaire' ? 'Heures' : 'Jours';
@endphp
@include('modules.professeur.bulletin.partials.shell')
<header>
    <div class="header-left">
        <button class="burger-menu" onclick="toggleSidebar()"><i class="fa fa-bars"></i></button>
        <button id="fullscreen-btn" onclick="toggleFullscreen()"><i class="fa fa-expand"></i></button>
    </div>
    <h1>{{ $title }}</h1>
</header>
<main>
    <section class="report-panel no-print">
        <form method="GET" class="report-grid">
            <div>
                <label>Annee</label>
                <select name="annee_scolaire">
                    @foreach($annees as $a)
                        <option value="{{ $a }}" @selected($annee === $a)>{{ $a }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label>Mois</label>
                <input type="number" name="mois" min="1" max="12" value="{{ $month }}">
            </div>
            <div>
                <label>Personne</label>
                @if($canSeeFullReport)
                    <select name="personne_id">
                        <option value="0">Toutes</option>
                        @foreach($people as $p)
                            <option value="{{ $p->id }}" @selected($personId === $p->id)>{{ $p->nom }} {{ $p->prenom }}</option>
                        @endforeach
                    </select>
                @else
                    <input value="{{ optional($people->first())->nom }} {{ optional($people->first())->prenom }}" readonly>
                    <input type="hidden" name="personne_id" value="{{ $personId }}">
                @endif
            </div>
            <div>
                <label>CNAPS (%)</label>
                <input type="number" name="cnaps" min="0" step="0.1" value="{{ $cnaps }}">
            </div>
            <div>
                <label>OSTIE (%)</label>
                <input type="number" name="ostie" min="0" step="0.1" value="{{ $ostie }}">
            </div>
            <button class="kaly">Filtrer</button>
        </form>
    </section>

    <section class="kpis no-print">
        <div class="kpi"><span>Presents</span><strong>{{ (int) $summary['presents'] }}</strong></div>
        <div class="kpi out"><span>Absents</span><strong>{{ (int) $summary['absents'] }}</strong></div>
        <div class="kpi"><span>Retards</span><strong>{{ (int) $summary['retards'] }}</strong></div>
        <div class="kpi"><span>{{ $unitLabel }}</span><strong>{{ number_format((float) $summary['units'], 1, ',', ' ') }}</strong></div>
        <div class="kpi"><span>Paie estimee</span><strong>{{ number_format((float) $summary['payroll'], 0, ',', ' ') }} {{ novaskol_currency() }}</strong></div>
    </section>

    <section class="chart-grid no-print">
        <div class="chart-card">
            <h2>Presence / absence par personne</h2>
            <canvas id="presenceChart"></canvas>
        </div>
        <div class="chart-card">
            <h2>Evolution hebdomadaire</h2>
            <canvas id="weekChart"></canvas>
        </div>
        <div class="chart-card" style="grid-column:1/-1">
            <h2>Graphique mensuel {{ $selectedPerson ? '- '.$selectedPerson->nom.' '.$selectedPerson->prenom : 'global' }}</h2>
            <canvas id="monthChart"></canvas>
        </div>
    </section>

    @if($personType === 'professeur')
        <section class="report-panel no-print">
            <h2>Suivi pedagogique enseignant</h2>
            <div class="report-table-wrap">
                <table class="report-table">
                    <thead><tr><th>Enseignant</th><th>Classe</th><th>Lecons</th><th>Terminees</th><th>Progression</th></tr></thead>
                    <tbody>
                        @forelse($pedagogicSummary as $row)
                            <tr>
                                <td>{{ $row->nom }} {{ $row->prenom }}</td>
                                <td>{{ $row->classe ?: 'General' }}</td>
                                <td>{{ (int) $row->lecons }}</td>
                                <td>{{ (int) $row->terminees }}</td>
                                <td>{{ number_format((float) $row->progression, 1, ',', ' ') }}%</td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="muted">Aucun journal pedagogique.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    @endif

    <section class="report-panel payslip-section">
        <h2 class="no-print">Fiche de paie</h2>
        @if($payslipRows->isEmpty())
            <p class="muted">Aucune fiche de paie disponible pour ce filtre.</p>
        @elseif(intval($personId) > 0)
            @php $r = $payslipRows->first(); @endphp
            @php
                $gross = (float) $r->total_units * (float) $r->{$salaryColumn};
                $cnapsAmount = $gross * ($cnaps / 100);
                $ostieAmount = $gross * ($ostie / 100);
                $net = max(0, $gross - $cnapsAmount - $ostieAmount);
                $daysTotal = (int) $r->presents + (int) $r->absents;
                $presenceRate = $daysTotal ? ((int) $r->presents / $daysTotal) * 100 : 0;
                $absentPct = $daysTotal ? ((int) $r->absents / $daysTotal) * 100 : 0;
                $retardPct = $daysTotal ? ((int) $r->retards / $daysTotal) * 100 : 0;
            @endphp
            <div class="print-btn-row no-print">
                <button class="primary" onclick="window.print()"><i class="fas fa-print"></i> Imprimer fiche de paie</button>
            </div>
            <div class="payslip-card">
                <div class="watermark">PAIE</div>
                <div class="payslip-head">
                    <div class="payslip-brand">
                        <div class="payslip-logo">
                            <img src="{{ asset('legacy/images/'.(str_starts_with($ecole->logo ?? '', 'images/') ? substr($ecole->logo, 7) : ($ecole->logo ?? 'novaskol.png'))) }}" alt="">
                        </div>
                        <div>
                            <span class="payslip-school">{{ $ecole->nom ?? 'Ecole' }}</span>
                            <strong class="payslip-title">Fiche de paie individuelle</strong>
                            <span class="payslip-eco-ribbon">{{ ucfirst($personType) }}</span>
                        </div>
                    </div>
                    <div class="payslip-ref">
                        <span>Reference</span>
                        <strong>PAY-{{ strtoupper(substr($personType, 0, 3)) }}-{{ $r->id }}-{{ str_pad($month, 2, '0', STR_PAD_LEFT) }}</strong>
                    </div>
                </div>
                <div class="payslip-meta">
                    <div class="pm-item"><span>Beneficiaire</span><strong>{{ $r->nom }} {{ $r->prenom }}</strong></div>
                    <div class="pm-item"><span>Fonction</span><strong>{{ ucfirst($personType) }}</strong></div>
                    <div class="pm-item"><span>Periode</span><strong>{{ str_pad($month, 2, '0', STR_PAD_LEFT) }}/{{ $annee }}</strong></div>
                    <div class="pm-item"><span>Date edition</span><strong>{{ date('d/m/Y') }}</strong></div>
                </div>

                <div class="indi-presence-bar">
                    <div class="bar-present" style="flex:{{ $presenceRate }}"></div>
                    <div class="bar-absent" style="flex:{{ $absentPct }}"></div>
                    <div class="bar-retard" style="flex:{{ $retardPct }}"></div>
                </div>
                <div style="display:flex; gap:16px; font-size:0.72rem; color:#64748b; margin-bottom:16px;">
                    <span><span style="display:inline-block;width:8px;height:8px;border-radius:50%;background:#059669;margin-right:4px;"></span>Present {{ number_format($presenceRate,1) }}%</span>
                    <span><span style="display:inline-block;width:8px;height:8px;border-radius:50%;background:#dc2626;margin-right:4px;"></span>Absent {{ number_format($absentPct,1) }}%</span>
                    <span><span style="display:inline-block;width:8px;height:8px;border-radius:50%;background:#d97706;margin-right:4px;"></span>Retard {{ number_format($retardPct,1) }}%</span>
                </div>

                <div class="payslip-body">
                    <table class="payslip-table">
                        <thead><tr><th>Designation</th><th>Base</th><th>Taux</th><th>Montant</th></tr></thead>
                        <tbody>
                            <tr><td><strong>{{ $unitLabel }} effectues</strong></td><td>{{ number_format((float) $r->total_units, 1, ',', ' ') }}</td><td>{{ number_format((float) $r->{$salaryColumn}, 0, ',', ' ') }} {{ novaskol_currency() }}</td><td class="money">{{ number_format($gross, 0, ',', ' ') }} {{ novaskol_currency() }}</td></tr>
                            <tr><td>Presents</td><td>{{ (int) $r->presents }}</td><td>-</td><td>{{ number_format($presenceRate, 1, ',', ' ') }}%</td></tr>
                            <tr><td>Absents</td><td>{{ (int) $r->absents }}</td><td>Retards</td><td>{{ (int) $r->retards }}</td></tr>
                            <tr class="deduction-row"><td>Retenue CNAPS ({{ $cnaps }}%)</td><td>-</td><td>Deduction</td><td class="deduction">-{{ number_format($cnapsAmount, 0, ',', ' ') }} {{ novaskol_currency() }}</td></tr>
                            <tr class="deduction-row"><td>Retenue OSTIE ({{ $ostie }}%)</td><td>-</td><td>Deduction</td><td class="deduction">-{{ number_format($ostieAmount, 0, ',', ' ') }} {{ novaskol_currency() }}</td></tr>
                        </tbody>
                    </table>
                </div>

                <div class="indi-payroll-breakdown">
                    <div class="indi-break-item"><span>Salaire brut</span><strong>{{ number_format($gross, 0, ',', ' ') }} {{ novaskol_currency() }}</strong></div>
                    <div class="indi-break-item"><span>Taux presence</span><strong>{{ number_format($presenceRate, 1, ',', ' ') }}%</strong></div>
                    <div class="indi-break-item"><span>CNAPS</span><strong>-{{ number_format($cnapsAmount, 0, ',', ' ') }} {{ novaskol_currency() }}</strong></div>
                    <div class="indi-break-item"><span>OSTIE</span><strong>-{{ number_format($ostieAmount, 0, ',', ' ') }} {{ novaskol_currency() }}</strong></div>
                    <div class="indi-break-item net"><span>Net a payer</span><strong>{{ number_format($net, 0, ',', ' ') }} {{ novaskol_currency() }}</strong></div>
                </div>

                <div class="payslip-signatures">
                    <div>Responsable</div>
                    <div>Beneficiaire</div>
                    <div>Cachet & date</div>
                </div>
            </div>
        @else
            @php
                $globalUnits = (float) $payslipRows->sum('total_units');
                $globalPresents = (int) $payslipRows->sum('presents');
                $globalAbsents = (int) $payslipRows->sum('absents');
                $globalRetards = (int) $payslipRows->sum('retards');
                $globalPayroll = $payslipRows->sum(fn($r) => (float) $r->total_units * (float) $r->{$salaryColumn});
                $cnapsAmount = $globalPayroll * ($cnaps / 100);
                $ostieAmount = $globalPayroll * ($ostie / 100);
                $globalNet = max(0, $globalPayroll - $cnapsAmount - $ostieAmount);
                $globalDays = $globalPresents + $globalAbsents;
                $globalRate = $globalDays ? ($globalPresents / $globalDays) * 100 : 0;
            @endphp
            <div class="print-btn-row no-print">
                <button class="primary" onclick="window.print()"><i class="fas fa-print"></i> Imprimer bulletin global</button>
            </div>
            <div class="payslip-card payslip-global">
                <div class="watermark">BULLETIN GLOBAL</div>
                <div class="global-mode-hint">
                    <i class="fas fa-layer-group"></i> Bulletin de paie combine — <strong>{{ $payslipRows->count() }} {{ $personType === 'professeur' ? 'enseignants' : 'agents' }}</strong>
                </div>
                <div class="payslip-head">
                    <div class="payslip-brand">
                        <div class="payslip-logo">
                            <img src="{{ asset('legacy/images/'.(str_starts_with($ecole->logo ?? '', 'images/') ? substr($ecole->logo, 7) : ($ecole->logo ?? 'novaskol.png'))) }}" alt="">
                        </div>
                        <div>
                            <span class="payslip-school">{{ $ecole->nom ?? 'Ecole' }}</span>
                            <strong class="payslip-title">Bulletin de paie global</strong>
                            <span class="payslip-eco-ribbon">{{ $personType === 'professeur' ? 'Enseignants' : 'Personnel' }}</span>
                        </div>
                    </div>
                    <div class="payslip-ref">
                        <span>Periode</span>
                        <strong>{{ str_pad($month, 2, '0', STR_PAD_LEFT) }}/{{ $annee }}</strong>
                    </div>
                </div>
                <div class="payslip-meta">
                    <div class="pm-item"><span>Effectif</span><strong>{{ $payslipRows->count() }} {{ $personType === 'professeur' ? 'enseignants' : 'agents' }}</strong></div>
                    <div class="pm-item"><span>Type</span><strong>{{ ucfirst($personType) }}</strong></div>
                    <div class="pm-item"><span>Periode</span><strong>{{ str_pad($month, 2, '0', STR_PAD_LEFT) }}/{{ $annee }}</strong></div>
                    <div class="pm-item"><span>Date edition</span><strong>{{ date('d/m/Y') }}</strong></div>
                </div>

                <div class="payroll-stats-grid">
                    <div class="payroll-stat"><span>Total presents</span><strong>{{ $globalPresents }}</strong></div>
                    <div class="payroll-stat"><span>Total absents</span><strong>{{ $globalAbsents }}</strong></div>
                    <div class="payroll-stat"><span>Total retards</span><strong>{{ $globalRetards }}</strong></div>
                    <div class="payroll-stat"><span>Masse salariale brute</span><strong>{{ number_format($globalPayroll, 0, ',', ' ') }} {{ novaskol_currency() }}</strong></div>
                    <div class="payroll-stat"><span>Taux presence global</span><strong>{{ number_format($globalRate, 1, ',', ' ') }}%</strong></div>
                    <div class="payroll-stat highlight"><span>Net total a payer</span><strong>{{ number_format($globalNet, 0, ',', ' ') }} {{ novaskol_currency() }}</strong></div>
                </div>

                <div class="payslip-body">
                    <table class="payslip-table payslip-table-global">
                        <thead>
                            <tr>
                                <th>N°</th>
                                <th>Nom & Prenoms</th>
                                <th>{{ $unitLabel }}</th>
                                <th>Presents</th>
                                <th>Absents</th>
                                <th>Retards</th>
                                <th>Taux</th>
                                <th>Brut</th>
                                <th>CNAPS</th>
                                <th>OSTIE</th>
                                <th>Net</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($payslipRows as $i => $r)
                                @php
                                    $g = (float) $r->total_units * (float) $r->{$salaryColumn};
                                    $ca = $g * ($cnaps / 100);
                                    $oa = $g * ($ostie / 100);
                                    $n = max(0, $g - $ca - $oa);
                                @endphp
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td><strong>{{ $r->nom }} {{ $r->prenom }}</strong></td>
                                    <td>{{ number_format((float) $r->total_units, 1, ',', ' ') }}</td>
                                    <td>{{ (int) $r->presents }}</td>
                                    <td>{{ (int) $r->absents }}</td>
                                    <td>{{ (int) $r->retards }}</td>
                                    <td>{{ number_format((float) $r->{$salaryColumn}, 0, ',', ' ') }}</td>
                                    <td class="money">{{ number_format($g, 0, ',', ' ') }}</td>
                                    <td>{{ number_format($ca, 0, ',', ' ') }}</td>
                                    <td>{{ number_format($oa, 0, ',', ' ') }}</td>
                                    <td class="money-light">{{ number_format($n, 0, ',', ' ') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="2">Total</th>
                                <th>{{ number_format($globalUnits, 1, ',', ' ') }}</th>
                                <th>{{ $globalPresents }}</th>
                                <th>{{ $globalAbsents }}</th>
                                <th>{{ $globalRetards }}</th>
                                <th>-</th>
                                <th class="money">{{ number_format($globalPayroll, 0, ',', ' ') }}</th>
                                <th>{{ number_format($cnapsAmount, 0, ',', ' ') }}</th>
                                <th>{{ number_format($ostieAmount, 0, ',', ' ') }}</th>
                                <th class="money-light">{{ number_format($globalNet, 0, ',', ' ') }}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <div class="payslip-stats">
                    <div class="pstat"><span class="pstat-dot present"></span> Presents <strong>{{ $globalPresents }}</strong></div>
                    <div class="pstat"><span class="pstat-dot absent"></span> Absents <strong>{{ $globalAbsents }}</strong></div>
                    <div class="pstat"><span class="pstat-dot retard"></span> Retards <strong>{{ $globalRetards }}</strong></div>
                    <div class="pstat"><span class="pstat-dot rate"></span> Taux presence <strong>{{ number_format($globalRate, 1, ',', ' ') }}%</strong></div>
                    <div class="pstat"><span class="pstat-dot" style="background:#065f46;box-shadow:0 0 6px rgba(5,150,105,0.3);"></span> Net total <strong>{{ number_format($globalNet, 0, ',', ' ') }} {{ novaskol_currency() }}</strong></div>
                </div>

                <div class="payslip-signatures">
                    <div>Responsable</div>
                    <div>Cachet</div>
                    <div>Date</div>
                </div>
            </div>
        @endif
    </section>

    @if($personId === 0 && $payslipRows->isNotEmpty())
    <section class="report-panel no-print">
        <h2>Details des presences</h2>
        <div class="report-table-wrap">
            <table class="report-table">
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Presents</th>
                        <th>Absents</th>
                        <th>Retards</th>
                        <th>{{ $unitLabel }}</th>
                        <th>Taux</th>
                        <th>Brut</th>
                        <th>Net</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rows as $r)
                        @php
                            $g = (float) $r->total_units * (float) $r->{$salaryColumn};
                            $ca = $g * ($cnaps / 100);
                            $oa = $g * ($ostie / 100);
                            $n = max(0, $g - $ca - $oa);
                        @endphp
                        <tr>
                            <td><strong>{{ $r->nom }} {{ $r->prenom }}</strong></td>
                            <td>{{ (int) $r->presents }}</td>
                            <td>{{ (int) $r->absents }}</td>
                            <td>{{ (int) $r->retards }}</td>
                            <td>{{ number_format((float) $r->total_units, 1, ',', ' ') }}</td>
                            <td>{{ number_format((float) $r->{$salaryColumn}, 0, ',', ' ') }} {{ novaskol_currency() }}</td>
                            <td class="money">{{ number_format($g, 0, ',', ' ') }} {{ novaskol_currency() }}</td>
                            <td class="money">{{ number_format($n, 0, ',', ' ') }} {{ novaskol_currency() }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="muted">Aucune presence.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
    @endif
    @if($personId > 0 && $payslipRows->isNotEmpty())
    <section class="report-panel no-print">
        <h2>Detail individuel</h2>
        <div class="report-table-wrap">
            <table class="report-table">
                <thead><tr><th>Designation</th><th>Valeur</th></tr></thead>
                <tbody>
                    @php $r = $payslipRows->first(); @endphp
                    <tr><td>{{ $unitLabel }} effectues</td><td>{{ number_format((float) $r->total_units, 1, ',', ' ') }}</td></tr>
                    <tr><td>Presents</td><td>{{ (int) $r->presents }}</td></tr>
                    <tr><td>Absents</td><td>{{ (int) $r->absents }}</td></tr>
                    <tr><td>Retards</td><td>{{ (int) $r->retards }}</td></tr>
                    <tr><td>Salaire brut</td><td class="money">{{ number_format((float) $r->total_units * (float) $r->{$salaryColumn}, 0, ',', ' ') }} {{ novaskol_currency() }}</td></tr>
                </tbody>
            </table>
        </div>
    </section>
    @endif

    <footer>&copy; {{ date('Y') }} {{ $ecole->nom ?? 'Ecole' }}</footer>
</main>
<script>
function toggleSidebar(){document.getElementById('sidebar').classList.toggle('active')}
function toggleSub(e){const n=e.nextElementSibling;if(n)n.style.display=n.style.display==='none'?'block':'none'}
function toggleFullscreen(){document.fullscreenElement?document.exitFullscreen():document.documentElement.requestFullscreen()}
const isDark = !document.documentElement.classList.contains('light');
const chartTextColor = isDark ? '#e5e7eb' : '#1e293b';
const chartGridColor = isDark ? 'rgba(255,255,255,0.08)' : 'rgba(0,0,0,0.08)';
const chartOpts={responsive:true,maintainAspectRatio:false,plugins:{legend:{labels:{color:chartTextColor,usePointStyle:true,pointStyle:'circle',font:{size:11}}}},scales:{x:{ticks:{color:chartTextColor,font:{size:10}},grid:{display:false}},y:{ticks:{color:chartTextColor,font:{size:10}},grid:{color:chartGridColor}}}};
new Chart(document.getElementById('presenceChart'),{type:'bar',data:{labels:@json($rows->map(fn($r)=>$r->nom.' '.$r->prenom)->values()),datasets:[{label:'Presents',data:@json($rows->pluck('presents')->values()),backgroundColor:'rgba(16,185,129,0.7)',borderColor:'#10b981',borderWidth:2,borderRadius:4},{label:'Absents',data:@json($rows->pluck('absents')->values()),backgroundColor:'rgba(239,68,68,0.7)',borderColor:'#ef4444',borderWidth:2,borderRadius:4},{label:'Retards',data:@json($rows->pluck('retards')->values()),backgroundColor:'rgba(245,158,11,0.7)',borderColor:'#f59e0b',borderWidth:2,borderRadius:4}]},options:chartOpts});
new Chart(document.getElementById('weekChart'),{type:'line',data:{labels:@json($weekly->pluck('date_jour')->values()),datasets:[{label:'Presents',data:@json($weekly->pluck('presents')->values()),borderColor:'#10b981',backgroundColor:'rgba(16,185,129,0.12)',fill:true,tension:.4,pointRadius:4,pointHoverRadius:6},{label:'Absents',data:@json($weekly->pluck('absents')->values()),borderColor:'#ef4444',backgroundColor:'rgba(239,68,68,0.10)',fill:true,tension:.4,pointRadius:4,pointHoverRadius:6},{label:'Retards',data:@json($weekly->pluck('retards')->values()),borderColor:'#f59e0b',backgroundColor:'rgba(245,158,11,0.10)',fill:true,tension:.4,pointRadius:4,pointHoverRadius:6}]},options:{...chartOpts,plugins:{...chartOpts.plugins,tooltip:{mode:'index',intersect:false}}}});
new Chart(document.getElementById('monthChart'),{type:'line',data:{labels:@json($monthlyDaily->pluck('date_jour')->values()),datasets:[{label:'Presents',data:@json($monthlyDaily->pluck('presents')->values()),borderColor:'#10b981',backgroundColor:'rgba(16,185,129,0.10)',fill:true,tension:.4,pointRadius:3,pointHoverRadius:5},{label:'Absents',data:@json($monthlyDaily->pluck('absents')->values()),borderColor:'#ef4444',backgroundColor:'rgba(239,68,68,0.08)',fill:true,tension:.4,pointRadius:3,pointHoverRadius:5},{label:'{{ $unitLabel }}',data:@json($monthlyDaily->pluck('units')->values()),borderColor:'#3b82f6',backgroundColor:'rgba(59,130,246,0.08)',fill:true,tension:.4,pointRadius:3,pointHoverRadius:5}]},options:{...chartOpts,plugins:{...chartOpts.plugins,tooltip:{mode:'index',intersect:false}}}});
</script>
</body>
</html>