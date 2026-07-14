<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription - {{ $ecole->nom ?? 'Ecole' }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('legacy/assets/fontawesome/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('legacy/assets/sweetalert2/sweetalert2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('legacy/fa/css/font-awesome.min.css') }}">
    <script src="{{ asset('legacy/js/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('legacy/assets/sweetalert2/sweetalert2.min.js') }}"></script>
<style>
    :root { --bg:#0a0a0a; --card:#14141a; --surface:#111827; --primary:#00c853; --primary-dark:#00a843; --primary-glow:rgba(0,200,83,.18); --text:#e5e7eb; --text-sec:#9ca3af; --border:#1f1f2e; --danger:#ef4444; --success:#10b981; --sidebar-width:240px; }
    * { margin:0; padding:0; box-sizing:border-box; scrollbar-width:thin; scrollbar-color:#2a2a3a #0f0f11; }
    *::-webkit-scrollbar { width:6px; } *::-webkit-scrollbar-track { background:#0f0f11; border-radius:10px; } *::-webkit-scrollbar-thumb { background:#2a2a3a; border-radius:10px; }
    html.light * { scrollbar-color:#cbd5e1 #eef2f7 !important; }
    html.light *::-webkit-scrollbar-track { background:#eef2f7 !important; }
    html.light *::-webkit-scrollbar-thumb { background:#cbd5e1 !important; border-color:#eef2f7 !important; }
    html.light *::-webkit-scrollbar-thumb:hover { background:#94a3b8 !important; }
    body { font-family:system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif; background:var(--bg); color:var(--text); min-height:100vh; }
    nav { width:var(--sidebar-width); background:var(--card); position:fixed; left:0; top:0; bottom:0; z-index:1000; overflow-y:auto; border-right:1px solid var(--border); transition:transform .28s ease; }
    nav.hidden { transform:translateX(-240px); } nav.active { transform:translateX(0); }
    nav .logo { text-align:center; padding:30px 0 20px; } nav .logo img { max-width:72px; border-radius:12px; box-shadow:0 4px 15px rgba(0,0,0,.5); } nav .logo h3 { font-size:1rem; padding:0 10px; margin-top:10px; }
    nav a, .parent-menu { padding:12px 20px; margin:4px 12px; color:var(--text-sec); text-decoration:none; font-weight:500; display:flex; align-items:center; gap:12px; border-radius:10px; transition:all .25s ease; }
    nav a:hover, nav a.active, .parent-menu:hover { background:rgba(0,200,83,.12); color:var(--text); } nav a.active { background:rgba(0,200,83,.25); color:var(--text); font-weight:600; border-left:4px solid var(--primary); }
    .parent-menu { cursor:pointer; justify-content:space-between; }
    .parent-menu span { display:flex; align-items:center; gap:10px; } .sub-menu a { padding-left:48px; font-size:.95rem; }
    header { position:fixed; top:0; left:var(--sidebar-width); right:0; min-height:72px; background:linear-gradient(135deg,var(--surface),var(--card)); display:flex; align-items:center; justify-content:flex-start; gap:18px; z-index:999; box-shadow:0 4px 20px rgba(0,0,0,.6); border-bottom:1px solid var(--border); transition:left .3s; padding:10px 18px; }
    header.full-width { left:0; } .header-left { display:flex; gap:14px; align-items:center; }
    .burger-menu,#fullscreen-btn { background:none; border:none; color:var(--text); font-size:1.45rem; cursor:pointer; transition:all .2s; } .burger-menu:hover,#fullscreen-btn:hover { color:var(--primary); transform:scale(1.12); }
    .search-container { display:grid; grid-template-columns:minmax(200px,1.2fr) minmax(170px,.8fr) minmax(150px,.6fr) 48px; gap:10px; width:min(920px, calc(100vw - 330px)); margin-left:12px; }
    input, select { width:100%; padding:11px 12px; border:1px solid var(--border); border-radius:8px; background:var(--surface); color:var(--text); outline:none; font-size:.95rem; }
    input:focus, select:focus { border-color:var(--primary); box-shadow:0 0 0 3px var(--primary-glow); }
    button, .button-link { border:none; border-radius:8px; cursor:pointer; font-weight:700; transition:all .2s; text-decoration:none; display:inline-flex; align-items:center; justify-content:center; gap:7px; }
    #btn-rechercher { background:var(--primary); color:#001f10; } button:hover, .button-link:hover { transform:translateY(-1px); }
    main { margin-left:240px; padding:104px 20px 40px; min-height:100vh; transition:margin-left .3s; } main.full-width { margin-left:0; }
    .kio { position:relative; margin:0 0 18px; padding:15px 18px; background:linear-gradient(to right, rgba(0,200,83,.16), transparent); border:1px solid var(--border); border-radius:12px; }
    .kio h2 { font-size:1.35rem; }
    .top-buttons { display:flex; flex-wrap:wrap; gap:10px; margin-bottom:18px; }
    .top-buttons button, .top-buttons a { min-height:40px; padding:0 14px; color:white; background:#034a3b; border:1px solid rgba(0,200,83,.32); }
    .top-buttons button.active { background:var(--primary); color:#02140a; }
    .form-container { background:rgba(20,20,26,.94); border:1px solid var(--border); border-radius:14px; padding:22px; margin:0 0 24px; box-shadow:0 6px 16px rgba(0,0,0,.3); }
    .hidden { display:none !important; }
    .form-section { margin-bottom:24px; } .form-section h3 { color:var(--primary); margin-bottom:14px; font-size:1.05rem; }
    .form-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(230px,1fr)); gap:14px; }
    label { display:block; margin-bottom:7px; color:var(--text-sec); font-size:.9rem; }
    input[type="checkbox"] { width:auto; min-width:20px; height:20px; accent-color:var(--primary); }
    .photo-container { display:flex; gap:10px; align-items:center; }
    .kaly { padding:12px 22px; background:var(--primary); color:#001f10; }
    #eleves-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(300px,1fr)); gap:20px; margin-top:20px; }
    .card { background:var(--card); border:1px solid var(--border); border-left:5px solid var(--primary); border-radius:14px; padding:14px; box-shadow:0 8px 24px rgba(0,0,0,.35); line-height:1.45; animation:fadeUp .25s ease both; }
    .card.nouveau { border-left-color:#f59e0b; } .card.passant { border-left-color:#22c55e; } .card.redoublant { border-left-color:#ef4444; }
    .card img { width:96px; height:96px; object-fit:cover; border-radius:50%; border:2px solid var(--primary); display:block; margin:12px auto; cursor:pointer; }
    .card div { color:var(--text-sec); font-size:.9rem; margin:5px 0; } .card strong { color:var(--text); min-width:108px; display:inline-block; } .card i { color:var(--primary); margin-right:6px; }
    .card-buttons { display:flex; flex-wrap:wrap; gap:7px; justify-content:center; margin-bottom:8px; }
    .card-buttons button { background:transparent; padding:7px 9px; border:1px solid var(--border); font-size:.78rem; color:var(--text); }
    .btn-modifier { color:var(--success) !important; } .btn-supprimer { color:var(--danger) !important; } .btn-details, .btn-parents { color:#38bdf8 !important; }
    .status-badge { padding:3px 7px; border-radius:999px; color:white; font-size:.78rem; } .status-badge.nouveau { background:#f59e0b; } .status-badge.passant { background:#22c55e; } .status-badge.redoublant { background:#ef4444; }
    .empty-state { grid-column:1 / -1; padding:28px; text-align:center; color:var(--text-sec); background:var(--card); border:1px solid var(--border); border-radius:12px; }
    .modal { display:none; position:fixed; inset:0; background:rgba(0,0,0,.76); align-items:center; justify-content:center; z-index:120000; padding:18px; backdrop-filter:blur(4px); }
    .modal.show { display:flex; }
    .modal-content { background:var(--card); border:1px solid var(--border); color:var(--text); border-radius:14px; width:min(980px, 96vw); max-height:min(90vh, calc(100dvh - 36px)); overflow-y:auto; padding:26px; position:relative; box-shadow:0 20px 60px rgba(0,0,0,.6); }
    .modal-content.small { width:min(560px, 96vw); }
    .close { position:absolute; right:22px; top:14px; cursor:pointer; color:var(--danger); font-size:1.8rem; }
    .image-modal-content { position:relative; }
    .image-modal-content .close { right:-14px; top:-18px; background:var(--danger); color:white; width:34px; height:34px; border-radius:50%; display:grid; place-items:center; line-height:1; z-index:3; }
    .image-modal-content img { max-width:90vw; max-height:84vh; border-radius:12px; border:1px solid var(--border); }
    footer { text-align:center; padding:2.5rem 1rem 1.5rem; color:var(--text-sec); font-size:.92rem; border-top:1px solid var(--border); margin-top:3rem; }
    @keyframes fadeUp { from { opacity:0; transform:translateY(8px); } to { opacity:1; transform:translateY(0); } }
    @media (max-width:900px) { nav { transform:translateX(-250px); } nav.active { transform:translateX(0); } header { left:0 !important; min-height:56px; flex-wrap:wrap; } .search-container { width:100%; grid-template-columns:1fr; margin:8px 0 0; } main { margin-left:0 !important; padding-top:238px; } .modal{align-items:flex-start;justify-content:center;padding:12px;overflow-y:auto;} .modal-content{width:100%;max-height:calc(100dvh - 24px);padding:52px 14px 18px;border-radius:12px;} .modal-content>.close{position:sticky;top:8px;float:right;margin:-42px -4px 10px auto;background:var(--card);border:1px solid var(--border);width:38px;height:38px;padding:0;border-radius:999px;z-index:4;display:grid;place-items:center;line-height:1;box-shadow:0 8px 24px rgba(0,0,0,.26);} .image-modal-content{width:100%;min-height:calc(100dvh - 24px);display:grid;place-items:center;padding:54px 8px 16px;} .image-modal-content .close{position:fixed;right:14px;top:14px;background:var(--danger);color:white;width:40px;height:40px;border-radius:999px;display:grid;place-items:center;line-height:1;z-index:2;box-shadow:0 10px 28px rgba(0,0,0,.28);} .image-modal-content img{max-width:94vw;max-height:calc(100dvh - 92px);object-fit:contain;} }
</style>
</head>
<body>
@php
    $legacyBase = 'http://localhost/novaskol/';
    $logo = $ecole->logo ?? 'novaskol.png';
    $logoPath = str_starts_with($logo, 'images/') ? substr($logo, 7) : $logo;
@endphp
@include('partials.global-actions')
<style>
    @media (max-width:1100px) {
        main > header {
            min-height:86px!important;
            padding:8px 168px 8px 82px!important;
            gap:10px!important;
            align-items:center!important;
            z-index:10040!important;
        }
        main > header .header-left {
            position:absolute!important;
            left:18px!important;
            top:50%!important;
            transform:translateY(-50%)!important;
            z-index:10050!important;
            gap:8px!important;
        }
        main > header .burger-menu,
        main > header #fullscreen-btn {
            z-index:10051!important;
        }
        main > header .search-container {
            width:100%!important;
            max-width:none!important;
            margin:0!important;
            grid-template-columns:minmax(150px,1fr) minmax(120px,.62fr) 112px 40px!important;
            gap:7px!important;
        }
        main > header .search-container input,
        main > header .search-container select {
            min-height:38px!important;
            padding:8px 10px!important;
            font-size:.86rem!important;
        }
        main > header #btn-rechercher {
            min-height:38px!important;
            width:40px!important;
            padding:0!important;
        }
    }
    @media (max-width:760px) {
        main > header {
            min-height:132px!important;
            padding:58px 12px 10px!important;
            align-items:flex-start!important;
        }
        main > header .header-left {
            position:fixed!important;
            left:14px!important;
            top:12px!important;
            transform:none!important;
            z-index:10050!important;
        }
        main > header .search-container {
            grid-template-columns:minmax(0,1fr) minmax(106px,.48fr) 40px!important;
            align-items:center!important;
        }
        main > header #search {
            grid-column:1 / -1!important;
        }
        main > header #filter-annee {
            min-width:0!important;
        }
        main {
            padding-top:154px!important;
        }
    }
    @media (max-width:480px) {
        main > header {
            min-height:176px!important;
        }
        main > header .search-container {
            grid-template-columns:1fr 40px!important;
        }
        main > header #filter-classe {
            grid-column:1 / 2!important;
        }
        main > header #filter-annee {
            grid-column:1 / 2!important;
        }
        main > header #btn-rechercher {
            grid-column:2 / 3!important;
            grid-row:2 / 4!important;
            height:83px!important;
            align-self:stretch!important;
        }
        main {
            padding-top:198px!important;
        }
    }
    @media print {
        @page { size:A4 landscape; margin:10mm; }
        *,*::before,*::after { -webkit-print-color-adjust:exact!important; print-color-adjust:exact!important; box-shadow:none!important; text-shadow:none!important; }
        html,body { background:white!important; color:#111!important; margin:0!important; padding:0!important; font-size:11pt!important; }
        nav,header,footer,.top-buttons,#btn-toggle-form,.form-container,.search-container,.burger-menu,#fullscreen-btn,.modal,.novaskol-global-actions,.global-dropdown,.novaskol-loader,.kio { display:none!important; }
        main { margin:0!important; padding:0!important; background:white!important; width:100%!important; }
        #eleves-grid { display:block!important; }
        .card { break-inside:avoid!important; page-break-inside:avoid!important; border:1px solid #555!important; border-left:5px solid #555!important; box-shadow:none!important; border-radius:4px!important; padding:8px!important; margin-bottom:8px!important; }
        .card.nouveau { border-left-color:#f59e0b!important; }
        .card.passant { border-left-color:#22c55e!important; }
        .card.redoublant { border-left-color:#ef4444!important; }
        .card .card-buttons,.card i.fa-edit,.card i.fa-trash,.card i.fa-info-circle,.card i.fa-users { display:none!important; }
        .card img { width:72px!important; height:72px!important; }
        .card strong { color:#111!important; }
        .card div { color:#333!important; }
    }
</style>
<div id="app">
    <nav id="sidebar">
        <div class="logo">
            <img src="{{ asset('legacy/images/'.$logoPath) }}" alt="Logo">
            <h3>{{ $ecole->nom ?? 'Ecole' }}</h3>
        </div>
        @php($openSub = false)
        @foreach ($modules as $module => $info)
            @if (session('utilisateur.role') !== 'admin' && ! empty($info['icon']) && ! in_array($userPermissions[$module] ?? null, ['lecture', 'ecriture'], true)) @continue @endif
            @if (empty($info['icon']))
                @if ($openSub)</div>@php($openSub = false)@endif
                <div class="parent-menu" onclick="toggleSub(this)"><span><i class="fa {{ $info['section_icon'] ?? 'fa-folder-open' }}"></i> {{ preg_replace('/^\s*\|\s*--\s*/', '', $info['label']) }}</span> <i class="fa fa-chevron-down arrow"></i></div>
                <div class="sub-menu" style="display:none;"> @php($openSub = true)
            @else
                @php($href = $module === 'dashboard' ? route('dashboard') : (! empty($info['migrated']) && ! empty($info['route']) ? route($info['route']) : $legacyBase.($info['legacy_url'] ?? $info['url'] ?? '#')))
                <a href="{{ $href }}" @class(['active' => $module === 'inscription'])><i class="fa {{ $info['icon'] }}"></i> <span>{{ $info['label'] }}</span></a>
            @endif
        @endforeach
        @if ($openSub)</div>@endif
    </nav>
    <main>
        <header>
            <div class="header-left">
                <button title="Cacher les menus" class="burger-menu" onclick="toggleSidebar()"><i class="fa fa-bars"></i></button>
                <button title="Plein ecran" id="fullscreen-btn" onclick="toggleFullscreen()"><i id="fullscreen-icon" class="fa fa-expand"></i></button>
            </div>
            <div class="search-container">
                <input type="text" id="search" placeholder="Rechercher par nom, prenom ou matricule">
                <select id="filter-classe">
                    <option value="">-- Choisir une classe --</option>
                    @foreach ($classes as $classe)
                        <option value="{{ $classe->id }}">{{ $classe->nom }}</option>
                    @endforeach
                </select>
                <input type="text" id="filter-annee" value="{{ $currentYear }}" placeholder="Annee scolaire">
                <button title="Filtrer" id="btn-rechercher"><i class="fa fa-search"></i></button>
            </div>
        </header>

        <div class="kio"><h2><i class="fa fa-user-plus"></i> Inscription d'un eleve</h2></div>
        <div class="top-buttons">
            <button id="btn-toggle-form"><i class="fa fa-plus"></i> Ajouter</button>
            <button id="btn-imprimer"><i class="fa fa-print"></i> Imprimer</button>
            <button id="btn-imprimer-photo"><i class="fa fa-camera"></i> Imprimer Photo</button>
            <a class="button-link" href="{{ route('modules.inscription.template') }}" id="btn-modele"><i class="fa fa-file-excel-o"></i> Modele</a>
            <button id="btn-importer"><i class="fa fa-upload"></i> Importer</button>
        </div>

        <div class="form-container hidden">
            <form method="POST" enctype="multipart/form-data" id="form-inscription">
                @include('modules.administration.partials.student-form-fields', ['prefix' => '', 'classes' => $classes, 'currentYear' => $currentYear, 'includeMatricule' => false])
                <button class="kaly" type="submit">Enregistrer</button>
            </form>
        </div>

        <div id="eleves-grid"></div>

        <div id="modal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <h2>Modifier un eleve</h2>
                <form id="form-modifier" enctype="multipart/form-data">
                    <input type="hidden" name="id" id="modal-id">
                    @include('modules.administration.partials.student-form-fields', ['prefix' => 'modal-', 'classes' => $classes, 'currentYear' => $currentYear, 'includeMatricule' => true])
                    <button class="kaly" type="submit">Modifier</button>
                </form>
            </div>
        </div>

        <div id="modal-parents" class="modal">
            <div class="modal-content small">
                <span class="close">&times;</span>
                <h2>Informations des parents</h2>
                <div class="form-grid">
                    <div><label>Pere</label><input id="parent-nom_pere" readonly></div>
                    <div><label>Mere</label><input id="parent-nom_mere" readonly></div>
                    <div><label>Telephone pere</label><input id="parent-telephone_pere" readonly></div>
                    <div><label>Telephone mere</label><input id="parent-telephone_mere" readonly></div>
                    <div><label>Profession pere</label><input id="parent-profession_pere" readonly></div>
                    <div><label>Profession mere</label><input id="parent-profession_mere" readonly></div>
                    <div><label>Adresse pere</label><input id="parent-adresse_pere" readonly></div>
                    <div><label>Adresse mere</label><input id="parent-adresse_mere" readonly></div>
                    <div><label>Annee scolaire</label><input id="parent-annee_scolaire" readonly></div>
                </div>
            </div>
        </div>

        <div id="modal-import" class="modal">
            <div class="modal-content small">
                <span class="close">&times;</span>
                <h2>Importer des eleves</h2>
                <form id="form-import" enctype="multipart/form-data">
                    <label for="import-file">Fichier xlsx :</label>
                    <input type="file" name="file" id="import-file" accept=".xlsx,.xls" required>
                    <button class="kaly" type="submit">Importer</button>
                </form>
            </div>
        </div>

        <div id="image-modal" class="modal">
            <div class="image-modal-content">
                <span class="close">&times;</span>
                <img id="image-modal-img" src="" alt="Photo eleve">
            </div>
        </div>

        <footer>&copy; {{ date('Y') }} {{ $ecole->nom ?? 'Ecole' }}. Tous droits reserves.</footer>
    </main>
</div>
<script>
const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
const inscriptionBase = '{{ url('/inscription') }}';
const searchUrl = '{{ route('modules.inscription.search') }}';
const parentsUrl = '{{ route('modules.inscription.parents') }}';
const importUrl = '{{ route('modules.inscription.import') }}';
const schoolName = @js($ecole->nom ?? 'Ecole');

function toggleSidebar(){const sidebar=document.querySelector('nav');const mainContent=document.querySelector('main');const header=document.querySelector('header');if(window.innerWidth<=1100){sidebar.classList.toggle('active');}else{sidebar.classList.toggle('hidden');mainContent.classList.toggle('full-width');header.classList.toggle('full-width');}}
function toggleSub(elem){const sub=elem.nextElementSibling;const arrow=elem.querySelector('.arrow');if(sub.style.display==='none'||sub.style.display===''){sub.style.display='block';arrow.classList.add('open');}else{sub.style.display='none';arrow.classList.remove('open');}}
function toggleFullscreen(){const icon=document.getElementById('fullscreen-icon');if(!document.fullscreenElement){document.documentElement.requestFullscreen().catch(err=>console.error(err));icon.classList.replace('fa-expand','fa-compress');}else{document.exitFullscreen();icon.classList.replace('fa-compress','fa-expand');}}
function refreshStudents(){let q=$('#search').val().trim();let classe=$('#filter-classe').val();let annee=$('#filter-annee').val().trim()||@js($currentYear);$('#filter-annee').val(annee);$.get(searchUrl,{q,classe,annee},function(data){$('#eleves-grid').html(data.grid);});}
function getPrintTitle(){const query=$('#search').val().trim();const annee=$('#filter-annee').val().trim();const classeName=$('#filter-classe option:selected').text()!=='-- Choisir une classe --'?$('#filter-classe option:selected').text():'';let parts=[];if(query)parts.push(`Recherche "${query}"`);if(classeName)parts.push(`Classe ${classeName}`);if(annee)parts.push(annee);return (parts.length?parts.join(', '):'Liste complete des eleves')+' - '+schoolName;}
function printRows(photoOnly=false){const query=$('#search').val().trim();const classe=$('#filter-classe').val();const annee=$('#filter-annee').val();const title=getPrintTitle();const logoUrl=$('nav .logo img').attr('src')||'{{ asset("legacy/images/logo.png") }}';$.get(searchUrl,{q:query,classe,annee,print:true},function(data){let body=data.table;if(photoOnly){let cells=body.match(/<td[^>]*>.*?<\/td>/g)||[];let rows='<tr>';let current='';let col=0;for(let cell of cells){if(col===0||col===1||col===2||col===3||col===19||col===20)current+=cell;col++;if(col===21){rows+=current+'</tr>';current='<tr>';col=0;}}body=rows+(current?current+'</tr>':'');}const html=`<html><head><title>${title}</title><style>@page{size:A4 landscape;margin:12mm}*,*::before,*::after{-webkit-print-color-adjust:exact!important;print-color-adjust:exact!important}body{font-family:Arial,sans-serif;color:#000;margin:0;padding:0}.print-header{text-align:center;margin-bottom:20px;border-bottom:2px solid #0f70c0;padding-bottom:12px}.print-header img{max-width:80px;height:auto;display:block;margin:0 auto 6px}.print-header h1{font-size:16pt;color:#0f70c0;margin:4px 0}.print-header .school{font-size:11pt;color:#555;margin:2px 0}.print-header .subtitle{font-size:10pt;color:#111;margin:2px 0}h2{text-align:center;color:#0f70c0;margin-bottom:20px;font-size:13pt}table{width:100%;border-collapse:collapse;font-size:${photoOnly?'10pt':'8.5pt'}}th,td{border:1px solid #555;padding:5px 4px;text-align:center;vertical-align:middle}th{background:#0f70c0;color:#fff;font-weight:700}tr:nth-child(even){background:#f5f8fc}img.photo-eleve{width:${photoOnly?'65px':'50px'};height:${photoOnly?'65px':'50px'};border-radius:50%;object-fit:cover;border:1px solid #ddd}</style></head><body><div class="print-header"><img src="${logoUrl}" alt="Logo"><h1>${schoolName}</h1><div class="subtitle">${title}${photoOnly?' - Avec photos':''}</div></div><table><thead><tr>${photoOnly?'<th>Photo</th><th>Matricule</th><th>Prenom</th><th>Nom</th><th>Classe</th><th>Annee</th>':'<th>Photo</th><th>Matricule</th><th>Prenom</th><th>Nom</th><th>Date naissance</th><th>Age</th><th>Lieu naissance</th><th>Pere</th><th>Mere</th><th>Telephone</th><th>Adresse</th><th>N Acte</th><th>Fonkotany</th><th>Commune</th><th>Ecole precedente</th><th>Distance >5km</th><th>Genre</th><th>Statut</th><th>Handicap</th><th>Classe</th><th>Annee</th>'}</tr></thead><tbody>${body}</tbody></table></body></html>`;if(window.desktopShell&&typeof window.desktopShell.printHtml==='function'){window.desktopShell.printHtml(html);}else{const w=window.open('','_blank');w.document.write(html);w.document.close();w.focus();w.print();}});}
function fillStudentModal(data){$('#modal-id').val(data.id||'');$('#modal-matricule').val(data.matricule||'');$('#modal-prenom').val(data.prenom||'');$('#modal-nom').val(data.nom||'');$('#modal-date_naissance').val(data.date_naissance||'');$('#modal-lieu_naissance').val(data.lieu_naissance||'');$('#modal-nom_pere').val(data.nom_pere||'');$('#modal-nom_mere').val(data.nom_mere||'');$('#modal-telephone').val(data.telephone||'');$('#modal-adresse').val(data.adresse||'');$('#modal-numero_acte').val(data.numero_acte||'');$('#modal-fonkotany').val(data.fonkotany||'');$('#modal-commune').val(data.commune||'');$('#modal-ecole_ancienne').val(data.ecole_ancienne||'');$('#modal-classe_id').val(data.classe||'');$('#modal-annee_scolaire').val(data.annee||'');$('#modal-distance_domicile').prop('checked',data.distance==1);$('#modal-genre').val(data.genre||'');$('#modal-statut').val(data.statut||'');$('#modal-est_handicap').prop('checked',data.handicap==1);$('#modal-supprimer_photo').prop('checked',false);$('#modal-creer_compte_parent').prop('checked',false);$('#modal-parent_lien').val('pere');$('#modal-parent_nom_compte,#modal-parent_email_compte,#modal-parent_mot_de_passe').val('');}
function loadParents(nom_pere,nom_mere,annee,callback){$.get(parentsUrl,{nom_pere,nom_mere,annee_scolaire:annee},callback);}
$(document).ready(function(){
    const active=document.querySelector('nav a.active');if(active){const sub=active.closest('.sub-menu');if(sub){sub.style.display='block';const parent=sub.previousElementSibling;if(parent){const arrow=parent.querySelector('.arrow');if(arrow)arrow.classList.add('open');}}}
    $('#btn-rechercher').on('click', refreshStudents);
    $('#btn-toggle-form').on('click', function(){$('.form-container').toggleClass('hidden');$(this).toggleClass('active');$(this).html($('.form-container').hasClass('hidden')?'<i class="fa fa-plus"></i> Ajouter':'<i class="fa fa-minus"></i> Masquer');});
    $('#btn-importer').on('click', function(){$('#modal-import').addClass('show').show();$('#import-file').val('');});
    $('.modal .close').on('click', function(){$(this).closest('.modal').removeClass('show').hide();});
    $('#form-inscription').on('submit', function(e){e.preventDefault();$.ajax({url:inscriptionBase,type:'POST',data:new FormData(this),processData:false,contentType:false,headers:{'X-CSRF-TOKEN':csrfToken},success:function(res){Swal.fire({title:'Succes !',text:res.message,icon:'success',timer:1800,showConfirmButton:false});$('#form-inscription')[0].reset();refreshStudents();},error:function(xhr){Swal.fire('Erreur',xhr.responseJSON?.message||'Probleme serveur','error');}});});
    $('#form-modifier').on('submit', function(e){e.preventDefault();const id=$('#modal-id').val();$.ajax({url:`${inscriptionBase}/${id}`,type:'POST',data:new FormData(this),processData:false,contentType:false,headers:{'X-CSRF-TOKEN':csrfToken},success:function(res){$('#modal').removeClass('show').hide();Swal.fire({title:'Succes !',text:res.message,icon:'success',timer:1800,showConfirmButton:false});refreshStudents();},error:function(xhr){Swal.fire('Erreur',xhr.responseJSON?.message||'Probleme serveur','error');}});});
    $('#form-import').on('submit', function(e){e.preventDefault();$.ajax({url:importUrl,type:'POST',data:new FormData(this),processData:false,contentType:false,headers:{'X-CSRF-TOKEN':csrfToken},success:function(res){$('#modal-import').removeClass('show').hide();Swal.fire({title:res.status==='success'?'Succes':'Attention',text:res.message,icon:res.status==='success'?'success':'warning'});refreshStudents();},error:function(xhr){Swal.fire('Erreur',xhr.responseJSON?.message||'Import impossible','error');}});});
    $(document).on('click','.btn-modifier',function(){const data=$(this).data();fillStudentModal(data);loadParents(data.nom_pere||'',data.nom_mere||'',data.annee||'',function(parent){$('#modal-telephone_pere').val(parent.telephone_pere||'');$('#modal-telephone_mere').val(parent.telephone_mere||'');$('#modal-profession_pere').val(parent.profession_pere||'');$('#modal-profession_mere').val(parent.profession_mere||'');$('#modal-adresse_pere').val(parent.adresse_pere||'');$('#modal-adresse_mere').val(parent.adresse_mere||'');$('#modal').addClass('show').show();});});
    $(document).on('click','.btn-parents',function(){const nom_pere=$(this).data('nom_pere')||'';const nom_mere=$(this).data('nom_mere')||'';const annee=$(this).data('annee_scolaire')||'';loadParents(nom_pere,nom_mere,annee,function(parent){$('#parent-nom_pere').val(nom_pere||'N/A');$('#parent-nom_mere').val(nom_mere||'N/A');$('#parent-annee_scolaire').val(annee||'N/A');$('#parent-telephone_pere').val(parent.telephone_pere||'N/A');$('#parent-telephone_mere').val(parent.telephone_mere||'N/A');$('#parent-profession_pere').val(parent.profession_pere||'N/A');$('#parent-profession_mere').val(parent.profession_mere||'N/A');$('#parent-adresse_pere').val(parent.adresse_pere||'N/A');$('#parent-adresse_mere').val(parent.adresse_mere||'N/A');$('#modal-parents').addClass('show').show();});});
    $(document).on('click','.btn-supprimer',function(){const id=$(this).data('id');Swal.fire({title:'Confirmer la suppression ?',text:'Cette action est irreversible !',icon:'warning',showCancelButton:true,confirmButtonColor:'#d33',cancelButtonColor:'#3085d6',confirmButtonText:'Oui, supprimer',cancelButtonText:'Annuler'}).then((result)=>{if(result.isConfirmed){$.ajax({url:`${inscriptionBase}/${id}`,type:'DELETE',headers:{'X-CSRF-TOKEN':csrfToken},success:function(response){if(response==='ok'){Swal.fire({title:'Succes !',text:'Eleve supprime avec succes',icon:'success',timer:1800,showConfirmButton:false});refreshStudents();}else{Swal.fire('Erreur',response,'error');}},error:function(xhr){Swal.fire('Erreur',xhr.responseText||'Probleme serveur','error');}});}});});
    $(document).on('click','.btn-details',function(){const d=$(this).data();Swal.fire({title:`${d.prenom||''} ${d.nom||''}`,html:`<div style="text-align:left"><p><b>Matricule:</b> ${d.matricule||''}</p><p><b>Classe:</b> ${d.classe_nom||''}</p><p><b>Annee:</b> ${d.annee||''}</p><p><b>Telephone:</b> ${d.telephone||''}</p><p><b>Adresse:</b> ${d.adresse||''}</p></div>`,imageUrl:d.photo,imageWidth:110,imageHeight:110});});
    $(document).on('click','.card img',function(){$('#image-modal-img').attr('src',$(this).attr('src'));$('#image-modal').addClass('show').show();});
    $('#btn-imprimer').on('click',()=>printRows(false));$('#btn-imprimer-photo').on('click',()=>printRows(true));
    refreshStudents();
});
</script>
</body>
</html>
