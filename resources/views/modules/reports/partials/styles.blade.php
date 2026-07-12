<style>
    .report-panel{background:var(--card);border:1px solid var(--border);border-radius:12px;padding:20px;margin-bottom:20px;min-width:0;overflow:hidden}
    .report-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:12px;align-items:end}
    .report-grid label{font-size:.72rem;text-transform:uppercase;letter-spacing:.05em;color:var(--text-sec);margin-bottom:4px;display:block}
    .report-grid button.kaly{padding:10px 20px;border-radius:8px;height:44px}
    .kpis{display:grid;grid-template-columns:repeat(auto-fit,minmax(170px,1fr));gap:12px;margin-bottom:20px}
    .kpi{background:linear-gradient(135deg,#1e293b,#0f172a);border:1px solid var(--border);border-radius:10px;padding:16px 18px;min-width:0}
    .kpi span{display:block;color:var(--text-sec);font-size:.8rem;margin-bottom:6px;text-transform:uppercase;letter-spacing:.04em}
    .kpi strong{font-size:1.4rem;color:#86efac;display:block}
    .kpi.out strong{color:#fecaca}
    input,select{width:100%;padding:10px 12px;background:var(--surface);color:var(--text);border:1px solid var(--border);border-radius:8px;font-size:.88rem;transition:border-color .15s}
    input:focus,select:focus{outline:none;border-color:var(--primary);box-shadow:0 0 0 3px rgba(0,200,83,.15)}
    .chart-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(340px,1fr));gap:18px;min-width:0}
    .chart-card{height:380px;background:var(--card);border:1px solid var(--border);border-radius:12px;padding:18px;display:flex;flex-direction:column;min-width:0;overflow:hidden}
    .chart-card h2{font-size:.95rem;line-height:1.3;margin:0 0 14px;word-break:break-word;color:var(--text);font-weight:600}
    .chart-card canvas{min-height:0;flex:1;max-width:100%!important}
    .report-table-wrap{overflow:auto;-webkit-overflow-scrolling:touch;max-width:100%;border:1px solid var(--border);border-radius:10px;background:var(--surface)}
    .report-table{width:100%;border-collapse:collapse;min-width:760px}
    .report-table th,.report-table td{padding:10px 12px;border-bottom:1px solid var(--border);text-align:left;font-size:.84rem}
    .report-table th{background:#0f172a;color:var(--primary);text-transform:uppercase;font-size:.72rem;letter-spacing:.05em;font-weight:700}
    .report-table tbody tr:hover{background:rgba(0,200,83,.04)}
    .money{font-weight:700;color:#065f46;font-variant-numeric:tabular-nums}
    .money-light{font-weight:600;color:#047857}
    .muted{color:var(--text-sec)}
    /* === Payslip Card (individual) === */
    .payslip-card{background:#fff;color:#0f172a;border:1px solid #e2e8f0;border-radius:14px;padding:28px;box-shadow:0 20px 60px rgba(0,0,0,.08);position:relative;overflow:hidden;min-width:0;max-width:820px;margin:0 auto}
    .payslip-card:before{content:"";position:absolute;top:0;left:0;right:0;height:6px;background:linear-gradient(90deg,#059669,#10b981,#34d399)}
    .payslip-card.payslip-global{max-width:100%;background:linear-gradient(145deg,#fafafa,#fff)}
    .payslip-head{display:flex;justify-content:space-between;gap:16px;align-items:flex-start;border-bottom:2px solid #e2e8f0;padding:0 0 18px;margin-bottom:18px}
    .payslip-brand{display:flex;gap:14px;align-items:center}
    .payslip-logo img{width:56px;height:56px;object-fit:contain;border:1px solid #d1fae5;border-radius:10px;background:#fff;padding:4px;box-shadow:0 2px 6px rgba(0,0,0,.04)}
    .payslip-school{display:block;color:#64748b;font-size:.75rem;text-transform:uppercase;letter-spacing:.06em}
    .payslip-title{display:block;color:#065f46;font-size:1.2rem;font-weight:800;margin-top:2px;text-transform:uppercase;letter-spacing:.02em}
    .payslip-ref{text-align:right}
    .payslip-ref span{display:block;color:#64748b;font-size:.7rem;text-transform:uppercase;letter-spacing:.04em}
    .payslip-ref strong{display:block;color:#0f172a;font-size:.9rem;font-weight:700;margin-top:3px}
    .payslip-meta{display:grid;grid-template-columns:repeat(4,1fr);gap:10px;margin-bottom:20px}
    .pm-item{background:#f1f5f9;border:1px solid #e2e8f0;border-radius:8px;padding:10px 12px}
    .pm-item span{display:block;color:#64748b;font-size:.68rem;text-transform:uppercase;letter-spacing:.04em}
    .pm-item strong{display:block;color:#0f172a;font-size:.88rem;font-weight:700;margin-top:3px}
    .payslip-body{margin-bottom:16px}
    .payslip-table{width:100%;border-collapse:collapse;font-size:.85rem}
    .payslip-table th,.payslip-table td{padding:10px 12px;text-align:left;border:1px solid #e2e8f0;color:#0f172a}
    .payslip-table th{background:#065f46;color:#fff;text-transform:uppercase;font-size:.7rem;letter-spacing:.05em;font-weight:700}
    .payslip-table tbody tr:nth-child(even){background:#f8fafc}
    .payslip-table tbody tr:hover{background:#ecfdf5}
    .payslip-table .deduction-row td{color:#dc2626}
    .payslip-table .deduction-row td.deduction{font-weight:700}
    .payslip-table-global th,.payslip-table-global td{padding:8px 10px;font-size:.8rem;white-space:nowrap}
    .payslip-table-global tfoot th{background:#f1f5f9;color:#0f172a;font-weight:800;border-top:2px solid #065f46}
    .payslip-total{display:flex;justify-content:space-between;align-items:center;background:linear-gradient(135deg,#065f46,#047857);color:#fff;border-radius:10px;padding:14px 18px;margin-top:10px}
    .payslip-total span{text-transform:uppercase;letter-spacing:.06em;font-size:.82rem}
    .payslip-total strong{font-size:1.4rem;color:#bbf7d0;font-weight:800}
    .payslip-summary-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:10px;margin:16px 0}
    .ps-item{background:#f1f5f9;border:1px solid #e2e8f0;border-radius:8px;padding:12px 14px;text-align:center}
    .ps-item span{display:block;color:#64748b;font-size:.7rem;text-transform:uppercase;letter-spacing:.04em;margin-bottom:4px}
    .ps-item strong{font-size:1.1rem;font-weight:800}
    .ps-total strong{color:#0f172a}
    .ps-net{background:linear-gradient(135deg,#065f46,#047857);border-color:#065f46}
    .ps-net span{color:#bbf7d0}
    .ps-net strong{color:#fff;font-size:1.2rem}
    .payslip-stats{display:flex;gap:16px;flex-wrap:wrap;margin-bottom:16px;padding:12px 14px;background:#f8fafc;border:1px solid #e2e8f0;border-radius:8px}
    .pstat{display:flex;align-items:center;gap:6px;font-size:.82rem;color:#475569}
    .pstat strong{color:#0f172a;font-weight:700;margin-left:2px}
    .pstat-dot{width:8px;height:8px;border-radius:50%;display:inline-block}
    .pstat-dot.present{background:#059669}
    .pstat-dot.absent{background:#dc2626}
    .pstat-dot.retard{background:#d97706}
    .pstat-dot.rate{background:#3b82f6}
    .payslip-signatures{display:grid;grid-template-columns:repeat(3,1fr);gap:20px;margin-top:32px}
    .payslip-signatures div{text-align:center;border-top:2px dashed #94a3b8;padding-top:10px;color:#475569;font-weight:600;font-size:.82rem;text-transform:uppercase;letter-spacing:.04em}
    .rank-card{background:#111827;border:1px solid var(--border);border-radius:10px;padding:16px;min-width:0}
    .rank-card h3{margin:0 0 12px;color:var(--primary);font-size:.95rem}
    .rank-card p{display:flex;justify-content:space-between;gap:12px;margin:0;padding:8px 0;border-bottom:1px solid var(--border);font-size:.84rem}
    .rank-card p:last-child{border-bottom:0}
    .rank-card strong{color:#86efac}
@media(max-width:980px){
    .chart-grid{grid-template-columns:1fr}.chart-card{height:340px}
    .report-table{min-width:660px}
    .payslip-meta{grid-template-columns:1fr 1fr}
    .payslip-summary-grid{grid-template-columns:1fr 1fr}
    .payslip-head{flex-direction:column;align-items:flex-start}
    .payslip-ref{text-align:left;width:100%}
}
@media(max-width:760px){
    .report-panel{padding:14px}
    .report-grid,.kpis{grid-template-columns:1fr}
    .kpi{padding:14px}.kpi strong{font-size:1.15rem}
    .chart-card{height:300px;padding:14px}
    .chart-card h2{font-size:.9rem}
    .report-table-wrap{margin-inline:-4px}
    .report-table{min-width:580px;font-size:.8rem}
    .report-table th,.report-table td{padding:8px}
    .payslip-card{padding:18px}
    .payslip-meta{grid-template-columns:1fr}
    .payslip-summary-grid{grid-template-columns:1fr}
    .payslip-table{font-size:.8rem}
    .payslip-table th,.payslip-table td{padding:8px}
    .payslip-total{flex-direction:column;align-items:flex-start;gap:6px}
    .payslip-total strong{font-size:1.2rem}
    .payslip-signatures{grid-template-columns:1fr;gap:14px;margin-top:24px}
    .pstat{font-size:.76rem}
}
@media screen and (max-width:900px){
    body.payslip-report .payslip-card{zoom:.75;transform-origin:top left;margin-bottom:20px}
    body.payslip-report .payslip-head{display:flex!important;flex-direction:column!important;align-items:flex-start!important}
    body.payslip-report .payslip-ref{text-align:left!important}
    body.payslip-report .payslip-meta{grid-template-columns:1fr 1fr!important}
    body.payslip-report .payslip-table{font-size:.82rem!important}
    body.payslip-report .payslip-total{flex-direction:row!important;align-items:center!important}
}
@media screen and (max-width:760px){body.payslip-report .payslip-card{zoom:.68}}
@media screen and (max-width:600px){body.payslip-report .payslip-card{zoom:.55}}
@media screen and (max-width:480px){body.payslip-report .payslip-card{zoom:.45}}
@media screen and (max-width:900px){
    body.accounting-report main{overflow-x:hidden!important}
    body.accounting-report .report-panel{padding:14px!important}
    body.accounting-report .report-grid{grid-template-columns:1fr 1fr!important;gap:10px!important}
    body.accounting-report .report-grid button{width:100%;justify-content:center}
    body.accounting-report .kpis{grid-template-columns:repeat(2,minmax(0,1fr))!important;gap:10px!important}
    body.accounting-report .kpi{padding:12px!important;border-radius:8px!important}
    body.accounting-report .kpi span{font-size:.74rem!important;margin-bottom:5px!important}
    body.accounting-report .kpi strong{font-size:1.02rem!important;line-height:1.18!important}
    body.accounting-report .kpis .kpi:nth-child(3){grid-column:1/-1}
    body.accounting-report .chart-grid{grid-template-columns:1fr!important;gap:12px!important}
    body.accounting-report .chart-card{height:270px!important;padding:12px!important;border-radius:8px!important}
    body.accounting-report .chart-card[style]{grid-column:auto!important}
    body.accounting-report .chart-card h2{font-size:.9rem!important;margin-bottom:8px!important}
}
@media screen and (max-width:520px){
    body.accounting-report .report-grid{grid-template-columns:1fr!important}
    body.accounting-report .chart-card{height:245px!important}
    body.accounting-report .kpi strong{font-size:.92rem!important}
}
    @media print{@page{size:A4 landscape;margin:10mm}
    *,*::before,*::after{-webkit-print-color-adjust:exact!important;print-color-adjust:exact!important}
    html,body{background:#fff!important;margin:0!important;padding:0!important;color:#111!important}
    nav,header,footer,.no-print,.novaskol-global-actions,.global-dropdown,.novaskol-loader{display:none!important}
    main{margin:0!important;padding:0!important;background:#fff!important}
    .report-panel,.chart-card,.rank-card{background:#fff!important;color:#111!important;border:1px solid #cbd5e1!important;box-shadow:none!important;break-inside:avoid;border-radius:0!important}
    .report-table th,.report-table td{color:#111!important;border:1px solid #94a3b8!important}
    .report-table th{background:#ecfdf5!important;color:#065f46!important}
    .chart-grid{grid-template-columns:1fr 1fr}
    .kpi{background:#fff!important;border:1px solid #94a3b8!important}
    .kpi span,.kpi strong,.rank-card h3,.rank-card strong{color:#111!important}
    .kpi strong{color:#065f46!important}
    .kpi.out strong{color:#dc2626!important}
    .money{color:#065f46!important}}
    @media print{body.payslip-report nav,body.payslip-report header,body.payslip-report footer,.no-print{display:none!important}
    body.payslip-report main{margin:0!important;padding:0!important;overflow:visible!important}
    body.payslip-report{background:#fff!important;color:#111!important}
    body.payslip-report .payslip-section{background:#fff!important;border:0!important;padding:0!important;margin:0!important}
    body.payslip-report .payslip-section>h2{display:none!important}
    body.payslip-report .payslip-card{max-width:none!important;min-height:0!important;margin:0!important;background:#fff!important;color:#111!important;border:1px solid #94a3b8!important;box-shadow:none!important;break-after:page;page-break-after:always;break-inside:avoid;page-break-inside:avoid;padding:5mm!important;overflow:visible!important;zoom:1!important;transform:none!important;height:auto!important;max-height:none!important}
    body.payslip-report .payslip-card:last-child{break-after:auto!important;page-break-after:auto!important}
    body.payslip-report .payslip-card:before{background:linear-gradient(90deg,#059669,#10b981)!important;height:4px!important}
    body.payslip-report .payslip-head{border-bottom:2px solid #065f46!important}
    body.payslip-report .payslip-head strong,body.payslip-report .pm-item strong,body.payslip-report .payslip-table td,body.payslip-report .payslip-signatures div,body.payslip-report .pstat strong{color:#111!important}
    body.payslip-report .payslip-head span,body.payslip-report .pm-item span,body.payslip-report .pstat{color:#475569!important}
body.payslip-report .payslip-meta{display:grid!important;grid-template-columns:repeat(2,1fr)!important;gap:2mm!important;margin-bottom:3mm!important}
body.payslip-report .pm-item{padding:2mm!important;font-size:6pt!important;background:#f1f5f9!important;border:1px solid #cbd5e1!important}
body.payslip-report .pm-item strong{font-size:7pt!important}
body.payslip-report .pm-item span{font-size:5.5pt!important}
    body.payslip-report .payslip-table th{background:#065f46!important;color:#fff!important;border-color:#065f46!important}
    body.payslip-report .payslip-table td{border-color:#cbd5e1!important}
    body.payslip-report .payslip-total{background:linear-gradient(135deg,#065f46,#047857)!important;color:#fff!important}
    body.payslip-report .payslip-total strong{color:#bbf7d0!important}
    body.payslip-report .payslip-signatures{display:grid!important;grid-template-columns:repeat(3,1fr)!important;gap:5mm!important;margin-top:5mm!important}
    body.payslip-report .payslip-signatures div{border-top:none!important;border-bottom:2px dashed #94a3b8!important;padding-bottom:6mm!important;font-size:8pt!important;color:#475569!important;min-height:10mm!important}
    body.payslip-report .payslip-table-global tfoot th{background:#f1f5f9!important;color:#111!important;border-top:2px solid #065f46!important}
    body.payslip-report .ps-item{background:#f1f5f9!important;border-color:#cbd5e1!important}
    body.payslip-report .ps-net{background:linear-gradient(135deg,#065f46,#047857)!important}
    body.payslip-report .ps-net strong{color:#fff!important}
    body.payslip-report .payslip-stats{background:#f8fafc!important;border-color:#cbd5e1!important}
    body.payslip-report .payslip-card.payslip-global{background:#fff!important}
    body.payslip-report .indi-break-item{background:#f1f5f9!important;border:1px solid #cbd5e1!important}
    body.payslip-report .indi-break-item.net{background:linear-gradient(135deg,#065f46,#047857)!important;color:#fff!important}
    body.payslip-report .indi-break-item.net strong{color:#bbf7d0!important}}
</style>
