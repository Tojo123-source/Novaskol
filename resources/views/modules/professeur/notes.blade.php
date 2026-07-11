<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajout des notes - {{ $ecole->nom ?? 'Ecole' }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('legacy/assets/fontawesome/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('legacy/assets/sweetalert2/sweetalert2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('legacy/fa/css/font-awesome.min.css') }}">
    <script src="{{ asset('legacy/js/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('legacy/assets/sweetalert2/sweetalert2.min.js') }}"></script>
<style>
    :root { --bg:#0a0a0a; --card:#14141a; --surface:#111827; --primary:#00c853; --primary-glow:rgba(0,200,83,.18); --text:#e5e7eb; --text-sec:#9ca3af; --border:#1f1f2e; --danger:#ef4444; --success:#10b981; --sidebar-width:240px; }
    * { margin:0; padding:0; box-sizing:border-box; scrollbar-width:thin; scrollbar-color:#2a2a3a #0f0f11; }
    body { font-family:system-ui, sans-serif; background:var(--bg); color:var(--text); min-height:100vh; }
    nav { width:var(--sidebar-width); background:var(--card); position:fixed; left:0; top:0; bottom:0; z-index:1000; overflow-y:auto; border-right:1px solid var(--border); transition:transform .28s ease; }
    nav.hidden { transform:translateX(-240px); } nav.active { transform:translateX(0); }
    nav .logo { text-align:center; padding:30px 0 20px; } nav .logo img { max-width:72px; border-radius:12px; box-shadow:0 4px 15px rgba(0,0,0,.5); } nav .logo h3 { font-size:1rem; padding:0 10px; margin-top:10px; }
    nav a, .parent-menu { padding:12px 20px; margin:4px 12px; color:var(--text-sec); text-decoration:none; font-weight:500; display:flex; align-items:center; gap:12px; border-radius:10px; transition:all .25s ease; }
    nav a:hover, nav a.active, .parent-menu:hover { background:rgba(0,200,83,.12); color:var(--text); } nav a.active { background:rgba(0,200,83,.25); color:var(--text); font-weight:600; border-left:4px solid var(--primary); }
    .parent-menu { cursor:pointer; justify-content:space-between; }
    .parent-menu span { display:flex; align-items:center; gap:10px; } .sub-menu a { padding-left:48px; font-size:.95rem; }
    header { position:fixed; top:0; left:var(--sidebar-width); right:0; min-height:72px; background:linear-gradient(135deg,var(--surface),var(--card)); display:flex; align-items:center; justify-content:center; z-index:999; box-shadow:0 4px 20px rgba(0,0,0,.6); border-bottom:1px solid var(--border); transition:left .3s; padding:10px 18px; }
    header.full-width { left:0; } .header-left { position:absolute; left:20px; display:flex; gap:16px; align-items:center; }
    .burger-menu,#fullscreen-btn { background:none; border:none; color:var(--text); font-size:1.45rem; cursor:pointer; transition:all .2s; border-radius:8px; } .burger-menu:hover,#fullscreen-btn:hover { color:var(--primary); transform:scale(1.12); }
    header h1 { margin:0; line-height:1.15; min-width:0; max-width:100%; padding:0 170px 0 84px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; text-align:center; }
    main { margin-left:240px; padding:92px 20px 40px; min-height:100vh; transition:margin-left .3s; } main.full-width { margin-left:0; }
    .form-container { background:var(--card); border:1px solid var(--border); border-radius:14px; padding:24px; box-shadow:0 6px 16px rgba(0,0,0,.3); }
    .filters { display:flex; flex-wrap:wrap; gap:20px; justify-content:center; margin-bottom:22px; }
    .filters > div { flex:1; min-width:260px; }
    label { display:block; margin-bottom:8px; color:var(--text-sec); font-weight:600; }
    select, input { width:100%; padding:11px 12px; border:1px solid var(--border); border-radius:8px; background:var(--surface); color:var(--text); outline:none; }
    select:focus, input:focus { border-color:var(--primary); box-shadow:0 0 0 3px var(--primary-glow); }
    .print-header { text-align:center; margin:18px 0 20px; color:var(--text); }
    .print-header h2 { color:var(--primary); margin-bottom:8px; }
    .table-wrapper { overflow:auto; border:1px solid var(--border); border-radius:12px; }
    table { width:100%; border-collapse:collapse; min-width:880px; background:#0f1117; }
    th, td { border:1px solid var(--border); padding:10px; text-align:center; vertical-align:middle; }
    th { background:#034a3b; color:#fff; position:sticky; top:0; z-index:1; }
    td:first-child, th:first-child { text-align:left; min-width:180px; max-width:190px; position:sticky; left:0; background:#111827; z-index:2; white-space:normal; overflow-wrap:anywhere; }
    th:first-child { background:#034a3b; z-index:3; }
    .note-input { min-width:90px; text-align:center; }
    .kaly { margin:16px 8px 0 0; padding:12px 18px; background:var(--primary); color:#001f10; border:0; border-radius:8px; cursor:pointer; font-weight:700; }
    .empty-state { padding:26px; color:var(--text-sec); text-align:center; border:1px dashed var(--border); border-radius:12px; }
    footer { text-align:center; padding:2.5rem 1rem 1.5rem; color:var(--text-sec); font-size:.92rem; border-top:1px solid var(--border); margin-top:3rem; }
    @media (max-width:1100px) { nav{transform:translateX(-250px);} nav.active{transform:translateX(0);} header{left:0!important;width:100%!important;min-height:86px!important;padding:10px 164px 10px 84px!important;} .header-left{left:18px!important;} header h1{font-size:1.16rem!important;padding:0!important;} main{margin-left:0!important;padding:118px 16px 40px!important;} .filters>div{min-width:220px;} }
    @media (max-width:760px) { header{min-height:126px!important;padding:58px 12px 12px!important;align-items:flex-start!important;justify-content:flex-start!important;} .header-left{position:fixed!important;left:14px!important;top:12px!important;z-index:10050!important;} header h1{font-size:1.02rem!important;text-align:left!important;white-space:normal!important;display:-webkit-box!important;-webkit-line-clamp:2!important;-webkit-box-orient:vertical!important;overflow:hidden!important;} main{padding:154px 10px 44px!important;} .form-container{padding:12px!important;overflow:hidden;border-radius:12px!important;} .filters{display:grid!important;grid-template-columns:repeat(2,minmax(0,1fr));gap:9px;margin-bottom:14px!important;} .filters>div{min-width:0!important;} .filters>div:first-child{grid-column:1/-1;} label{font-size:.76rem;margin-bottom:5px;} select,input{min-height:39px;padding:8px 9px;font-size:.82rem;border-radius:9px;} .print-header{margin:12px 0 14px!important;} .print-header h2{font-size:1rem!important;line-height:1.18;} .print-header p{font-size:.78rem;line-height:1.25;} .table-wrapper{max-width:100%;margin-inline:0;overflow-x:auto;overflow-y:hidden;-webkit-overflow-scrolling:touch;border-radius:12px;box-shadow:inset -16px 0 18px -18px var(--primary-glow);} table{min-width:640px;font-size:.72rem;table-layout:auto;} th,td{padding:6px 5px;line-height:1.15;} th{top:0;} td:first-child,th:first-child{min-width:112px;max-width:120px;white-space:normal;overflow-wrap:anywhere;} .note-input{min-width:52px;max-width:60px;padding:7px 5px;font-size:.8rem;border-radius:8px;} .remark-input{min-width:112px;padding:7px 6px;font-size:.78rem;border-radius:8px;} #notesForm>.kaly{width:calc(50% - 6px);min-height:40px;margin:12px 4px 0 0;padding:9px 8px;font-size:.78rem;border-radius:10px;} .empty-state{padding:18px 12px;font-size:.88rem;} }
    @media (max-width:520px) { main{padding-top:164px!important;} header{min-height:118px!important;} .form-container{padding:10px!important;} .filters{gap:8px;} table{min-width:580px;font-size:.68rem;} th,td{padding:5px 4px;} td:first-child,th:first-child{min-width:96px;max-width:104px;} .note-input{min-width:46px;max-width:52px;font-size:.76rem;padding:6px 4px;} .remark-input{min-width:96px;font-size:.74rem;} #notesForm>.kaly{width:100%;margin:9px 0 0;font-size:.82rem;} }
    @media print {
        @page { size:A4 landscape; margin:7mm; }
        *,*::before,*::after { -webkit-print-color-adjust:exact!important; print-color-adjust:exact!important; box-shadow:none!important; text-shadow:none!important; }
        html,body { background:white!important; color:#111!important; margin:0!important; padding:0!important; }
        nav,header,.filters,.kaly,footer,.novaskol-global-actions,.global-dropdown,.novaskol-loader { display:none!important; }
        main { margin:0!important; padding:0!important; background:white!important; width:100%!important; }
        .form-container { display:block!important; box-shadow:none!important; border:0!important; background:white!important; color:#111!important; padding:0!important; margin:0!important; }
        .print-header { color:#111!important; margin:0 0 6mm!important; text-align:center!important; }
        .print-header .school-logo { max-width:70px!important; height:auto!important; margin:0 auto 4px!important; display:block!important; }
        .print-header h2 { color:#047857!important; font-size:15pt!important; margin:4px 0!important; }
        .print-header p { font-size:9pt!important; margin:2px 0!important; color:#333!important; }
        .table-wrapper { overflow:visible!important; border:0!important; border-radius:0!important; width:100%!important; }
        table { width:100%!important; min-width:0!important; table-layout:fixed!important; background:white!important; color:#111!important; font-size:7.5pt!important; }
        th,td { border:1px solid #555!important; color:#111!important; padding:3px 4px!important; word-break:break-word!important; vertical-align:middle!important; }
        th { background:#034a3b!important; color:white!important; position:static!important; }
        td:first-child,th:first-child { position:static!important; background:#e5e5e5!important; color:#111!important; min-width:0!important; width:16%!important; }
        #notesForm { display:block!important; }
        input { border:1px solid #ccc!important; background:#fff!important; color:#111!important; padding:2px!important; width:100%!important; text-align:center!important; font-size:7.5pt!important; }
        table#notes-table tbody td { border:1px solid #555!important; background:#fff!important; }
    }
</style>
</head>
<body>
@php
    $legacyBase = 'http://localhost/novaskol/';
    $logo = $ecole->logo ?? 'logo.png';
    $logoPath = str_starts_with($logo, 'images/') ? substr($logo, 7) : $logo;
@endphp
@include('partials.global-actions')
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
            <a href="{{ $href }}" @class(['active' => $module === 'notes'])><i class="fa {{ $info['icon'] }}"></i> <span>{{ $info['label'] }}</span></a>
        @endif
    @endforeach
    @if ($openSub)</div>@endif
</nav>
<header>
    <div class="header-left">
        <button class="burger-menu" onclick="toggleSidebar()"><i class="fa fa-bars"></i></button>
        <button id="fullscreen-btn" onclick="toggleFullscreen()"><i id="fullscreen-icon" class="fa fa-expand"></i></button>
    </div>
    <h1><i class="fa fa-book"></i> Ajout des notes par classe</h1>
</header>
<main>
    <div class="form-container">
        <form method="GET" class="filters">
            <div>
                <label for="classe">Classe</label>
                <select name="classe" id="classe" onchange="this.form.submit()">
                    <option value="">Selectionner une classe</option>
                    @foreach ($classes as $classe)
                        <option value="{{ $classe->id }}" @selected($selectedClasse === $classe->id)>{{ $classe->nom }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="periode">Periode</label>
                <select name="periode" id="periode" onchange="this.form.submit()">
                    @foreach ($periodLabels as $key => $label)
                        <option value="{{ $key }}" @selected($selectedPeriode === $key)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="annee_scolaire">Annee scolaire</label>
                <select name="annee_scolaire" id="annee_scolaire" onchange="this.form.submit()">
                    @foreach ($annees as $annee)
                        <option value="{{ $annee }}" @selected($selectedAnnee === $annee)>{{ $annee }}</option>
                    @endforeach
                </select>
            </div>
        </form>

        @if ($selectedClasse && $eleves->isNotEmpty() && $matieres->isNotEmpty())
            <div class="print-header">
                <img class="school-logo" src="{{ asset('legacy/images/'.$logoPath) }}" alt="Logo" style="display:none">
                <h2>Notes - {{ $periodLabels[$selectedPeriode] }} - {{ $selectedAnnee }}</h2>
                <p>{{ $ecole->nom ?? 'Ecole' }} | Classe : {{ $classeNom }}</p>
                <p><strong>NB : Veuillez entrer les notes des eleves sur 20 !</strong></p>
            </div>
            <form method="POST" action="{{ route('modules.notes.store') }}" id="notesForm">
                @csrf
                <input type="hidden" name="classe" value="{{ $selectedClasse }}">
                <input type="hidden" name="periode" value="{{ $selectedPeriode }}">
                <input type="hidden" name="annee_scolaire" value="{{ $selectedAnnee }}">
                <div class="table-wrapper">
                    <table id="notes-table">
                        <thead>
                            <tr>
                                <th>Eleve</th>
                                @foreach ($matieres as $matiere)
                                    <th>{{ $matiere->nom }} (coef {{ $matiere->coefficient }})</th>
                                @endforeach
                                <th>Remarque</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($eleves as $eleve)
                                <tr>
                                    <td>{{ $eleve->nom }} {{ $eleve->prenom }}</td>
                                    @foreach ($matieres as $matiere)
                                        <td>
                                            <input type="number" class="note-input" name="notes[{{ $eleve->id }}][{{ $matiere->id }}]" value="{{ $notesExistantes[$eleve->id][$matiere->id] ?? '' }}" min="0" max="20" step="0.01">
                                        </td>
                                    @endforeach
                                    <td><input type="text" class="remark-input" name="remarques[{{ $eleve->id }}]" value="{{ $remarquesExistantes[$eleve->id] ?? '' }}"></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <button type="button" class="kaly" id="saveNotesBtn">Enregistrer les notes</button>
                <button type="button" class="kaly" onclick="window.print()">Imprimer</button>
            </form>
        @elseif ($selectedClasse && $matieres->isEmpty())
            <div class="empty-state">Aucune matiere n'est affectee a cette classe pour le moment.</div>
        @elseif ($selectedClasse)
            <div class="empty-state">Aucun eleve trouve pour cette classe et cette annee scolaire.</div>
        @else
            <div class="empty-state">Selectionne une classe pour afficher le tableau des notes.</div>
        @endif
    </div>
    <footer>&copy; {{ date('Y') }} {{ $ecole->nom ?? 'Ecole' }}. Tous droits reserves.</footer>
</main>
<script>
function toggleSub(el){const sub=el.nextElementSibling;const arrow=el.querySelector('.arrow');sub.style.display=sub.style.display==='block'?'none':'block';arrow.classList.toggle('fa-chevron-down');arrow.classList.toggle('fa-chevron-up');}
function toggleSidebar(){const sidebar=document.getElementById('sidebar');const main=document.querySelector('main');const header=document.querySelector('header');if(window.innerWidth<=1100){sidebar.classList.toggle('active');}else{sidebar.classList.toggle('hidden');main.classList.toggle('full-width');header.classList.toggle('full-width');}}
function toggleFullscreen(){const icon=document.getElementById('fullscreen-icon');if(!document.fullscreenElement){document.documentElement.requestFullscreen();icon.classList.replace('fa-expand','fa-compress');}else{document.exitFullscreen();icon.classList.replace('fa-compress','fa-expand');}}
document.addEventListener('DOMContentLoaded',()=>{const active=document.querySelector('nav a.active');if(active){const sub=active.closest('.sub-menu');if(sub){sub.style.display='block';const parent=sub.previousElementSibling;const arrow=parent?.querySelector('.arrow');if(arrow){arrow.classList.replace('fa-chevron-down','fa-chevron-up');}}}});
const autoRemarkLabels=['Mediocre','Passable','Assez bien','Bien','Tres bien','Excellent'];
function autoRemarkFromAverage(avg){
    if(avg >= 18) return 'Excellent';
    if(avg >= 16) return 'Tres bien';
    if(avg >= 14) return 'Bien';
    if(avg >= 12) return 'Assez bien';
    if(avg >= 10) return 'Passable';
    return 'Mediocre';
}
function updateRemarkForRow(row){
    const remark=row.querySelector('.remark-input');
    if(!remark || remark.dataset.manual==='1') return;
    const values=[...row.querySelectorAll('.note-input')].map(i=>parseFloat(i.value)).filter(v=>!Number.isNaN(v));
    if(!values.length) return;
    const avg=values.reduce((a,b)=>a+b,0)/values.length;
    remark.value=autoRemarkFromAverage(avg);
}
document.querySelectorAll('.remark-input').forEach(input=>{
    if(input.value && !autoRemarkLabels.includes(input.value.trim())) input.dataset.manual='1';
    input.addEventListener('input',()=>{input.dataset.manual=input.value.trim()===''?'0':'1';});
});
document.querySelectorAll('.note-input').forEach(input=>{
    input.addEventListener('input',()=>updateRemarkForRow(input.closest('tr')));
    updateRemarkForRow(input.closest('tr'));
});
document.getElementById('saveNotesBtn')?.addEventListener('click',function(){Swal.fire({title:"Confirmer l'enregistrement ?",text:"Les notes et remarques seront sauvegardees pour cette periode et classe.",icon:'question',showCancelButton:true,confirmButtonColor:'#00c853',cancelButtonColor:'#ef4444',confirmButtonText:'Oui, enregistrer',cancelButtonText:'Annuler'}).then((result)=>{if(result.isConfirmed){document.getElementById('notesForm').submit();}});});
@if (session('notes_msg'))
Swal.fire({title:@js(session('notes_msg.type') === 'success' ? 'Succes' : 'Erreur'),text:@js(session('notes_msg.text')),icon:@js(session('notes_msg.type')),timer:3000,showConfirmButton:false});
@endif
</script>
</body>
</html>
