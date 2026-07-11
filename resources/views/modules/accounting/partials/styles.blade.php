<style>
    .acc-panel { background:var(--card); border:1px solid var(--border); border-radius:8px; padding:18px; margin-bottom:18px; }
    .acc-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(210px,1fr)); gap:14px; align-items:end; }
    .acc-table-wrap { overflow:auto; border:1px solid var(--border); border-radius:8px; background:var(--surface); }
    .acc-table { width:100%; border-collapse:collapse; min-width:850px; }
    .acc-table th,.acc-table td { padding:12px; border-bottom:1px solid var(--border); text-align:left; vertical-align:top; }
    .acc-table th { background:#0f172a; color:var(--primary); font-size:.82rem; text-transform:uppercase; }
    input,select,textarea { width:100%; padding:12px; background:var(--surface); color:var(--text); border:1px solid var(--border); border-radius:8px; }
    textarea { min-height:74px; resize:vertical; }
    .money { font-weight:800; color:#86efac; white-space:nowrap; }
    .money.out { color:#fecaca; }
    .badge { display:inline-flex; align-items:center; padding:4px 9px; border-radius:999px; font-size:.82rem; font-weight:800; background:rgba(0,200,83,.14); color:#86efac; }
    .badge.warn { background:rgba(245,158,11,.14); color:#fbbf24; }
    .danger-btn { background:#dc2626; color:white; border:0; border-radius:8px; padding:9px 12px; cursor:pointer; font-weight:800; }
    .muted { color:var(--text-sec); }
    .tabs { display:flex; flex-wrap:wrap; gap:10px; margin-bottom:16px; }
    .tab-link { padding:11px 16px; border-radius:8px; background:var(--surface); color:var(--text); text-decoration:none; border:1px solid var(--border); font-weight:800; }
    .tab-link.active { background:var(--primary); color:white; border-color:var(--primary); }
    .alert { padding:12px 14px; border-radius:8px; margin-bottom:14px; border:1px solid var(--border); }
    .alert.success { background:rgba(16,185,129,.12); color:#a7f3d0; }
    .alert.error { background:rgba(239,68,68,.12); color:#fecaca; }
    .print-meta { display:none; }
    @media print {
        @page { size:A4 portrait; margin:8mm; }
        html,body{background:white!important;margin:0!important;padding:0!important;color:#111!important;}
        nav,header,footer,.acc-panel.no-print,.print-actions,.no-print,.tabs,.novaskol-global-actions,.global-dropdown,.novaskol-loader{display:none!important;}
        body{background:white!important;color:#111!important;font-family:Arial,sans-serif!important;font-size:10px!important;}
        main{margin:0!important;padding:0!important;min-height:0!important;}
        .acc-panel{display:none!important;box-shadow:none!important;background:white!important;border:0!important;padding:0!important;margin:0!important;}
        .acc-panel.printable-list{display:block!important;}
        .printable-list[style*="display:none"]{display:none!important;}
        .acc-panel h2{color:#065f46!important;text-align:center!important;margin:0 0 8mm!important;font-size:18px!important;}
        .print-meta{display:block!important;text-align:center!important;margin:-5mm 0 5mm!important;color:#334155!important;font-size:10px!important;font-weight:700!important;}
        .acc-table-wrap{overflow:visible!important;border:0!important;background:white!important;}
        .acc-table{width:100%!important;min-width:0!important;border-collapse:collapse!important;table-layout:fixed!important;background:white!important;}
        .acc-table th,.acc-table td{border:1px solid #111!important;padding:4px!important;color:#111!important;word-break:break-word!important;overflow-wrap:anywhere!important;vertical-align:top!important;font-size:8.2px!important;}
        .acc-table th{background:#ecfdf5!important;color:#065f46!important;font-weight:700!important;text-transform:none!important;}
        .acc-table th:nth-child(1),.acc-table td:nth-child(1){width:18%;}
        .acc-table th:nth-child(2),.acc-table td:nth-child(2){width:20%;}
        .acc-table th:nth-child(3),.acc-table td:nth-child(3){width:10%;}
        .acc-table th:nth-child(4),.acc-table td:nth-child(4){width:14%;}
        .acc-table th:nth-child(5),.acc-table td:nth-child(5){width:12%;}
        .acc-table th:nth-child(6),.acc-table td:nth-child(6){width:10%;}
        .acc-table th:nth-child(7),.acc-table td:nth-child(7){width:16%;text-align:right;}
        .badge{background:transparent!important;color:#111!important;padding:0!important;border-radius:0!important;}
        .money,.money.out{color:#111!important;white-space:normal!important;}
    }
</style>
