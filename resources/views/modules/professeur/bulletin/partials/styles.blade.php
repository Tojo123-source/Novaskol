<style>
    :root { --bg:#0a0a0a; --card:#14141a; --surface:#111827; --primary:#00c853; --primary-dark:#00a843; --primary-glow:rgba(0,200,83,.18); --text:#e5e7eb; --text-sec:#9ca3af; --border:#1f1f2e; --danger:#ef4444; --success:#10b981; --sidebar-width:240px; --shadow-strong:rgba(0,0,0,.6); --shadow-soft:rgba(0,0,0,.3); --header-bg:linear-gradient(135deg,var(--surface),var(--card)); --nav-active:rgba(0,200,83,.25); --nav-hover:rgba(0,200,83,.12); --table-head:#0f172a; --input-bg:var(--surface); }
    :root.light { --bg:#f3f6fb; --card:#ffffff; --surface:#f8fafc; --primary:#059669; --primary-dark:#047857; --primary-glow:rgba(5,150,105,.16); --text:#111827; --text-sec:#475569; --border:#d8e0ea; --danger:#dc2626; --success:#059669; --shadow-strong:rgba(15,23,42,.16); --shadow-soft:rgba(15,23,42,.10); --header-bg:linear-gradient(135deg,#ffffff,#eef5f1); --nav-active:rgba(5,150,105,.14); --nav-hover:rgba(5,150,105,.09); --table-head:#e8f7ef; --input-bg:#ffffff; }
    * { margin:0; padding:0; box-sizing:border-box; scrollbar-width:thin; scrollbar-color:var(--border) var(--surface); }
    .swal2-container { z-index:250000!important; }
    .swal2-popup { z-index:250001!important; }
    body { font-family:system-ui, sans-serif; background:var(--bg); color:var(--text); min-height:100vh; }
    nav { width:var(--sidebar-width); background:var(--card); position:fixed; left:0; top:0; bottom:0; z-index:1000; overflow-y:auto; border-right:1px solid var(--border); transition:transform .28s ease; }
    nav.hidden { transform:translateX(-240px); } nav.active { transform:translateX(0); }
    nav .logo { text-align:center; padding:30px 0 20px; } nav .logo img { max-width:72px; border-radius:12px; box-shadow:0 4px 15px var(--shadow-soft); } nav .logo h3 { font-size:1rem; padding:0 10px; margin-top:10px; }
    nav a, .parent-menu { padding:12px 20px; margin:4px 12px; color:var(--text-sec); text-decoration:none; font-weight:500; display:flex; align-items:center; gap:12px; border-radius:10px; transition:all .25s ease; }
    nav a:hover, nav a.active, .parent-menu:hover { background:var(--nav-hover); color:var(--text); } nav a.active { background:var(--nav-active); color:var(--text); font-weight:600; border-left:4px solid var(--primary); }
    .parent-menu { cursor:pointer; justify-content:space-between; } .sub-menu a { padding-left:48px; font-size:.95rem; }
    header { position:fixed; top:0; left:var(--sidebar-width); right:0; min-height:70px; background:var(--header-bg); display:flex; align-items:center; justify-content:center; z-index:999; box-shadow:0 4px 20px var(--shadow-strong); border-bottom:1px solid var(--border); transition:left .3s; padding:10px 24px; }
    header.full-width { left:0; } .header-left { position:absolute; left:20px; display:flex; gap:16px; align-items:center; }
    h1 { font-size:1.35rem; font-weight:bold; margin:0; min-width:0; max-width:100%; text-align:center; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; } .burger-menu,#fullscreen-btn { background:none; border:none; color:var(--text); font-size:1.45rem; cursor:pointer; transition:all .2s; }
    .burger-menu:hover,#fullscreen-btn:hover { color:var(--primary); transform:scale(1.12); }
    main { margin-left:var(--sidebar-width); padding:90px 20px 50px; min-height:100vh; transition:margin-left .3s; }
    main.full-width { margin-left:0; }
    .form-container { background:var(--card); border:1px solid var(--border); border-radius:12px; padding:25px; box-shadow:0 4px 15px var(--shadow-soft); }
    label { display:block; margin-bottom:8px; font-weight:600; color:var(--text-sec); }
    select, input[type="text"] { width:100%; padding:14px; font-size:.95rem; background:var(--input-bg); border:1px solid var(--border); border-radius:8px; color:var(--text); }
    select:focus, input[type="text"]:focus { border-color:var(--primary); box-shadow:0 0 0 3px var(--primary-glow); outline:none; }
    .kaly,.action-btn { display:inline-flex; align-items:center; justify-content:center; gap:8px; padding:13px 22px; background:var(--primary); color:white; border:none; border-radius:8px; font-size:1rem; font-weight:700; cursor:pointer; text-decoration:none; margin-top:15px; }
    .kaly:hover,.action-btn:hover { background:var(--primary-dark); transform:translateY(-1px); }
    .results { margin-top:16px; max-height:300px; overflow-y:auto; border-radius:8px; border:1px solid var(--border); background:var(--surface); }
    .result-item { padding:13px 16px; cursor:pointer; border-bottom:1px solid var(--border); color:var(--text); }
    .result-item:hover { background:var(--nav-hover); }
    .filters-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(230px,1fr)); gap:18px; }
    .actions { display:flex; flex-wrap:wrap; gap:12px; margin-top:12px; }
    .bulletin-page { width:100%; max-width:950px; margin:0 auto 34px; background:white; color:#111827; border-radius:12px; box-shadow:0 6px 20px rgba(0,0,0,.25); overflow:hidden; page-break-after:always; }
    .bulletin-header { background:linear-gradient(135deg,#1e3a8a,#3b82f6); color:white; padding:18px; text-align:center; }
    .bulletin-body { padding:22px; }
    .student-info { display:grid; grid-template-columns:repeat(auto-fit,minmax(180px,1fr)); gap:10px; margin-bottom:18px; }
    .info-box { border:1px solid #d1d5db; border-radius:8px; padding:10px; background:#f9fafb; }
    .info-label { color:#6b7280; font-size:.8rem; margin-bottom:3px; }
    .info-value { font-weight:700; }
    .bulletin-table-wrap { width:100%; overflow:auto; -webkit-overflow-scrolling:touch; border:1px solid #cbd5e1; border-radius:10px; background:white; }
    .bulletin-table { width:100%; border-collapse:collapse; font-size:.92rem; }
    .bulletin-table th,.bulletin-table td { border:1px solid #cbd5e1; padding:8px; text-align:center; }
    .bulletin-table th { background:#1e40af; color:white; }
    .bulletin-table td:first-child,.bulletin-table th:first-child { text-align:left; }
    .moyenne-row { background:#dbeafe; font-weight:700; }
    .remark-box { margin-top:14px; padding:12px; border:1px solid #cbd5e1; border-radius:8px; background:#f8fafc; }
    .print-actions { display:flex; justify-content:center; gap:10px; margin-bottom:18px; }
    footer { text-align:center; padding:2.5rem 1rem 1.5rem; color:var(--text-sec); font-size:.92rem; border-top:1px solid var(--border); margin-top:3rem; }
    :root.light .form-container,
    :root.light .acc-panel,
    :root.light .report-card,
    :root.light .ped-card,
    :root.light .calendar-side,
    :root.light .chat-panel,
    :root.light .notif-card,
    :root.light .profile-modal { box-shadow:0 8px 24px rgba(15,23,42,.08); }
    :root.light .acc-table th,
    :root.light .presence-table th,
    :root.light .report-table th,
    :root.light .liste-table th,
    :root.light .fiche-table th { background:#e8f7ef!important; color:#065f46!important; }
    :root.light .chat-head,
    :root.light .composer,
    :root.light .fc-col-header-cell { background:#f8fafc!important; color:#111827!important; }
    :root.light .bubble { background:#ffffff!important; border-color:#d8e0ea!important; color:#111827!important; }
    :root.light .bubble.mine { background:#dcfce7!important; border-color:#86efac!important; }
    :root.light .preview,
    :root.light .message-menu,
    :root.light .global-dropdown,
    :root.light .loader-box { box-shadow:0 18px 45px rgba(15,23,42,.18)!important; }
    :root.light input,
    :root.light select,
    :root.light textarea { background:#ffffff; color:#111827; border-color:var(--border); }
    :root.light .muted,
    :root.light small { color:#64748b; }
    @media (max-width:1100px) { nav{transform:translateX(-250px);z-index:10040;padding-top:76px;box-shadow:18px 0 48px rgba(0,0,0,.28);} nav.active{transform:translateX(0);} nav .logo{padding:14px 0 18px;} body.novaskol-sidebar-open{overflow:hidden;} header{left:0!important;padding:12px 164px 12px 86px!important;justify-content:flex-start!important;} .header-left{left:16px!important;gap:10px!important;} h1{font-size:1.12rem!important;text-align:left!important;} main{margin-left:0!important;padding:94px 14px 100px!important;} }
    @media (max-width:700px) { nav{padding-top:78px;} header{min-height:92px!important;padding:54px 12px 10px 12px!important;align-items:flex-start!important;} .header-left{position:fixed!important;left:12px!important;top:12px!important;transform:none!important;z-index:10050!important;} h1{font-size:1rem!important;white-space:normal!important;display:-webkit-box!important;-webkit-line-clamp:2!important;-webkit-box-orient:vertical!important;line-height:1.15!important;} main{padding:122px 10px 100px!important;} .form-container{padding:16px;} .print-actions{flex-direction:column}.bulletin-page{margin-bottom:22px;border-radius:10px}.bulletin-body{padding:16px}.student-info{grid-template-columns:1fr 1fr}.info-box{padding:9px}.bulletin-table-wrap{margin-inline:-2px}.bulletin-table{min-width:620px;font-size:.84rem}.bulletin-table th,.bulletin-table td{padding:7px}.remark-box{padding:10px;font-size:.88rem;} }
    @media (max-width:520px) { .student-info{grid-template-columns:1fr}.bulletin-table{min-width:560px;font-size:.8rem} }
    @media screen and (max-width:700px) { .kaly,.action-btn,button.kaly,a.kaly{min-height:40px!important;padding:10px 13px!important;border-radius:10px!important;font-size:.88rem!important;line-height:1.15!important;gap:6px!important;margin-top:9px!important;box-shadow:0 8px 20px rgba(0,0,0,.10)!important}.actions{gap:8px!important}.actions .kaly,.actions .action-btn{flex:1 1 calc(50% - 8px)!important;min-width:135px!important}.filters-grid{grid-template-columns:1fr!important;gap:12px!important}.results{max-height:240px!important}.result-item{padding:10px 12px!important;border-radius:8px!important;margin:6px!important;background:var(--card)!important}.result-item strong{font-size:.92rem!important;line-height:1.25!important}.result-item small{font-size:.76rem!important;line-height:1.25!important;display:block!important;overflow-wrap:anywhere!important} }
    @media screen and (max-width:430px) { .actions .kaly,.actions .action-btn{flex-basis:100%!important;min-width:0!important}.kaly,.action-btn,button.kaly,a.kaly{width:100%;max-width:100%} }
    @media screen and (max-width:900px) { body:has(.bulletin-page) main{overflow-x:hidden!important}.bulletin-page{width:950px!important;max-width:950px!important;min-width:950px!important;margin-left:0!important;margin-right:0!important;zoom:.72;transform-origin:top left;}.bulletin-header{padding:18px!important}.bulletin-body{padding:22px!important}.student-info{grid-template-columns:repeat(auto-fit,minmax(180px,1fr))!important}.info-box{padding:10px!important}.bulletin-table{min-width:0!important;font-size:.92rem!important}.bulletin-table th,.bulletin-table td{padding:8px!important}.remark-box{padding:12px!important;font-size:1rem!important}.bulletin-table-wrap{margin-inline:0!important;overflow:visible!important} }
    @media screen and (max-width:760px) { .bulletin-page{zoom:.62;margin-bottom:24px!important} }
    @media screen and (max-width:700px) { .bulletin-page{zoom:.52;margin-bottom:24px!important} }
    @media screen and (max-width:600px) { .bulletin-page{zoom:.44;margin-bottom:22px!important} }
    @media screen and (max-width:520px) { .bulletin-page{zoom:.36;margin-bottom:20px!important} }
    @media screen and (max-width:380px) { .bulletin-page{zoom:.34;margin-bottom:18px!important} }
        @media print {
            @page { size:A4 portrait; margin:8mm; }
            *,*::before,*::after { -webkit-print-color-adjust:exact!important; print-color-adjust:exact!important; box-shadow:none!important; text-shadow:none!important; }
            html,body { background:white!important; color:#000!important; margin:0!important; padding:0!important; }
            nav,header,footer,.print-actions,.novaskol-global-actions,.global-dropdown,.novaskol-loader,.actions { display:none!important; }
            main { margin:0!important; padding:0!important; background:white!important; width:100%!important; }
            .form-container,.rh-panel,.calendar-card,.calendar-side,.ped-card,.notif-card,.chat-panel,.acc-panel,.report-panel,.chart-card,.rank-card,.panel { background:white!important; color:#000!important; border-color:#999!important; border-radius:0!important; box-shadow:none!important; }
            .bulletin-page { width:100%!important; max-width:none!important; min-width:0!important; zoom:1!important; transform:none!important; box-shadow:none!important; border-radius:0!important; margin:0 auto!important; page-break-after:always; break-after:page; overflow:visible!important; }
            .bulletin-page:last-child { page-break-after:auto!important; break-after:auto!important; }
            .bulletin-header { background:linear-gradient(135deg,#1e3a8a,#3b82f6)!important; color:white!important; border-bottom:2px solid #1e3a8a!important; -webkit-print-color-adjust:exact!important; print-color-adjust:exact!important; }
            .bulletin-table th { background:#1e40af!important; color:white!important; }
            .bulletin-table td { border-color:#cbd5e1!important; }
            .moyenne-row { background:#dbeafe!important; color:#111!important; }
            .remark-box { background:#f8fafc!important; border-color:#cbd5e1!important; color:#111!important; }
            .info-box { background:#f9fafb!important; border-color:#d1d5db!important; color:#111!important; }
            .student-info .info-value { color:#111!important; }
            table,th,td { border-color:#cbd5e1!important; }
        }
</style>
