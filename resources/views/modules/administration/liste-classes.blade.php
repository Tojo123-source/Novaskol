<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des classes - {{ $ecole->nom ?? 'Ecole' }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('legacy/assets/fontawesome/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('legacy/assets/sweetalert2/sweetalert2.min.css') }}">
    <script src="{{ asset('legacy/js/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('legacy/assets/sweetalert2/sweetalert2.min.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('legacy/fa/css/font-awesome.min.css') }}">
<style>
    :root {
        --bg: #0a0a0a; --card: #14141a; --surface: #111827; --primary: #00c853;
        --primary-dark: #00a843; --primary-glow: rgba(0,200,83,0.18); --text: #e5e7eb;
        --text-sec: #9ca3af; --border: #1f1f2e; --danger: #ef4444; --success: #10b981; --sidebar-width: 240px;
    }
    * { margin:0; padding:0; box-sizing:border-box; scrollbar-width: thin; scrollbar-color: #2a2a3a #0f0f11; }
    *::-webkit-scrollbar { width: 6px; } *::-webkit-scrollbar-track { background:#0f0f11; border-radius:10px; }
    *::-webkit-scrollbar-thumb { background:#2a2a3a; border-radius:10px; border:2px solid #0f0f11; }
    body { font-family: system-ui, sans-serif; background: var(--bg); color: var(--text); min-height: 100vh; }
    nav { width:var(--sidebar-width); background:var(--card); position:fixed; left:0; top:0; bottom:0; z-index:1000; overflow-y:auto; border-right:1px solid var(--border); transition:transform .28s ease; }
    nav.hidden { transform:translateX(-240px); } nav.active { transform:translateX(0); }
    nav .logo { text-align:center; padding:30px 0 20px; } nav .logo img { max-width:72px; border-radius:12px; box-shadow:0 4px 15px rgba(0,0,0,.5); }
    nav .logo h3 { font-size:1rem; padding:0 10px; margin-top:10px; }
    nav a, .parent-menu { padding:12px 20px; margin:4px 12px; color:var(--text-sec); text-decoration:none; font-weight:500; display:flex; align-items:center; gap:12px; border-radius:10px; transition:all .25s ease; }
    nav a:hover, nav a.active, .parent-menu:hover { background:rgba(0,200,83,.12); color:var(--text); }
    nav a.active { background:rgba(0,200,83,.25); color:var(--text); font-weight:600; border-left:4px solid var(--primary); }
    .parent-menu { cursor:pointer; justify-content:space-between; }
    .parent-menu span { display:flex; align-items:center; gap:10px; } .sub-menu a { padding-left:48px; font-size:.95rem; }
    header { position:fixed; top:0; left:var(--sidebar-width); right:0; min-height:72px; background:linear-gradient(135deg,var(--surface),var(--card)); display:flex; align-items:center; justify-content:center; z-index:999; box-shadow:0 4px 20px rgba(0,0,0,.6); border-bottom:1px solid var(--border); transition:left .3s,width .3s; padding:10px 18px; }
    header.full-width { left:0; width:100%; } .header-left { position:absolute; left:20px; display:flex; gap:16px; align-items:center; }
    h1 { font-size:1.5em; font-weight:bold; line-height:1.15; margin:0; min-width:0; max-width:100%; padding:0 170px 0 84px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; text-align:center; } .burger-menu,#fullscreen-btn { background:none; border:none; color:var(--text); font-size:1.45rem; cursor:pointer; transition:all .2s; }
    .burger-menu:hover,#fullscreen-btn:hover { color:var(--primary); transform:scale(1.12); }
    main { margin-left:240px; padding:90px 20px 40px; min-height:100vh; background:var(--bg); transition:margin-left .3s; }
    main.full-width { margin-left:0; }
    .form-container { background:var(--bg); border-radius:14px; padding:24px; margin-bottom:32px; border:1px solid var(--border); box-shadow:0 6px 16px rgba(0,0,0,.3); }
    select#annee_scolaire_filter, #annee_scolaire_modal { padding:10px 16px; border-radius:8px; border:1px solid var(--border); background:var(--surface); color:var(--text); font-size:1rem; min-width:220px; }
    .features-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(320px,1fr)); gap:24px; align-items:stretch; }
    .feature-item { background:var(--card); border-radius:16px; overflow:hidden; border:1px solid var(--border); box-shadow:0 6px 20px rgba(0,0,0,.35); transition:all .35s ease; cursor:pointer; }
    .feature-item:hover { transform:translateY(-10px); box-shadow:0 20px 40px var(--primary-glow); border-color:var(--primary); }
    .feature-item img { width:100%; height:180px; object-fit:cover; border-bottom:1px solid var(--border); }
    .feature-text { padding:20px 24px; } .feature-text h3 { color:var(--text); margin-bottom:8px; font-size:1.4rem; }
    .feature-text p { color:var(--text-sec); font-size:.95rem; line-height:1.5; margin-bottom:16px; }
    .action-buttons { display:flex; gap:8px; flex-wrap:wrap; padding:0 24px 20px; justify-content:center; }
    .action-buttons button { flex:1; min-width:44px; padding:10px; border:none; border-radius:8px; font-weight:600; cursor:pointer; transition:all .2s; font-size:1rem; background:transparent; }
    .action-buttons button:hover { transform:scale(1.1); } .btn-modifier{color:var(--success);} .btn-supprimer{color:var(--danger);} .btn-liste{color:#3b82f6;}
    .btn-ajouter { display:inline-flex; align-items:center; gap:8px; margin:16px 0 32px; padding:12px 24px; background:var(--primary); color:white; border:none; border-radius:10px; font-weight:600; font-size:1.1rem; cursor:pointer; box-shadow:0 4px 12px var(--primary-glow); }
    .modal { display:none; position:fixed; inset:0; background:rgba(0,0,0,.75); align-items:center; justify-content:center; z-index:2000; backdrop-filter:blur(4px); }
    .modal-content,.eleves-modal-content { background:var(--card); border-radius:16px; width:90%; max-width:500px; padding:32px; border:1px solid var(--border); box-shadow:0 20px 60px rgba(0,0,0,.6); color:var(--text); position:relative; }
    .eleves-modal-content { max-width:1100px; max-height:90vh; overflow-y:auto; }
    .close { position:absolute; top:16px; right:24px; font-size:2rem; cursor:pointer; color:#aaa; }
    .modal-content input { width:100%; padding:12px; margin:12px 0; border:1px solid var(--border); border-radius:8px; background:var(--surface); color:var(--text); font-size:1rem; }
    .modal-content button { width:100%; padding:14px; margin-top:16px; background:var(--primary); color:white; border:none; border-radius:8px; font-weight:600; cursor:pointer; }
    .eleves-table { width:100%; border-collapse:collapse; margin-top:20px; font-size:.92rem; }
    .eleves-table th,.eleves-table td { padding:12px 14px; border:1px solid var(--border); text-align:left; }
    .eleves-table th { background:var(--primary); color:#000; font-weight:600; position:sticky; top:0; z-index:10; }
    .btn-imprimer { background:#f59e0b; color:white; padding:12px 24px; border:none; border-radius:8px; font-weight:600; cursor:pointer; margin:16px 0; }
    .kaly { padding:5px; background:#034a3b; color:white; border:none; border-radius:6px; font-weight:600; cursor:pointer; text-decoration:none !important; margin:0 24px 18px; display:inline-block; }
    footer { text-align:center; padding:2.5rem 1rem 1.5rem; color:var(--text-sec); font-size:.92rem; border-top:1px solid var(--border); margin-top:3rem; }
    @media (max-width:1100px) { nav{transform:translateX(-250px);} nav.active{transform:translateX(0);} header{left:0!important;width:100%!important;min-height:86px!important;padding:10px 164px 10px 84px!important;} .header-left{left:18px!important;} header h1{font-size:1.18rem!important;padding:0!important;} main{margin-left:0!important;padding:118px 16px 40px!important;} }
    @media (max-width:760px) { header{min-height:126px!important;padding:58px 12px 12px!important;align-items:flex-start!important;justify-content:flex-start!important;} .header-left{position:fixed!important;left:14px!important;top:12px!important;z-index:10050!important;} header h1{font-size:1.06rem!important;text-align:left!important;white-space:normal!important;display:-webkit-box!important;-webkit-line-clamp:2!important;-webkit-box-orient:vertical!important;overflow:hidden!important;} main{padding:154px 12px 44px!important;} .form-container{padding:16px!important;margin:0 0 20px!important;} .feature-text{padding:18px 18px 14px!important;} .feature-text h3{font-size:1.2rem!important;} .btn-ajouter{width:100%;justify-content:center;} .action-buttons button{min-width:52px;} .features-grid{grid-template-columns:repeat(2,minmax(0,1fr))!important;gap:14px!important;} .feature-item{height:100%;} }
    @media (max-width:520px) { .features-grid{grid-template-columns:1fr!important;} .feature-item img{height:160px!important;} select#annee_scolaire_filter,#annee_scolaire_modal{width:100%;min-width:0;} .modal-content,.eleves-modal-content{width:min(96vw,1100px);padding:20px;} }
    @media print {
        @page{size:A4 landscape;margin:10mm 12mm;}
        *,*::before,*::after{ -webkit-print-color-adjust:exact!important; print-color-adjust:exact!important; }
        html,body{background:white!important;color:#111!important;margin:0!important;padding:0!important;}
        nav,header,footer,.burger-menu,#fullscreen-btn,.btn-ajouter,.features-grid,.form-container>.io,.action-buttons,.kaly,.novaskol-global-actions,.global-dropdown,.novaskol-loader { display:none!important; }
        main { margin:0!important; padding:0!important; background:white!important; width:100%!important; }
        #eleves-modal{position:absolute!important;left:0!important;top:0!important;width:100%!important;height:auto!important;display:block!important;background:white!important;visibility:visible!important;}
        .eleves-modal-content{background:white!important;color:black!important;box-shadow:none!important;border:none!important;padding:0!important;margin:0!important;width:100%!important;visibility:visible!important;}
        .close,.btn-imprimer,.efenina,.annee-scolaire-print{display:none!important;}
        .print-header{text-align:center;margin-bottom:10px;padding-bottom:8px;border-bottom:2px solid #0f70c0;} .print-header img{max-width:65px!important;height:auto;margin:0 auto 6px;display:block;} .print-header h1{font-size:17pt;margin:4px 0 2px;color:#0f70c0;font-weight:bold;}
        #eleves-modal-title{text-align:center;font-size:14pt;color:#111;margin:10px 0;}
        .eleves-table{width:100%;border-collapse:collapse;font-size:9pt;margin-top:6px;}
        .eleves-table th,.eleves-table td{border:1px solid #555!important;padding:4px 6px!important;text-align:left;color:#111!important;}
        .eleves-table th{background:#0f70c0!important;color:white!important;font-weight:bold;font-size:8.5pt;}
        .eleves-table td{background:white!important;}
        .eleves-table tr:nth-child(even) td{background:#f5f8fc!important;}
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
<div id="app">
    <nav id="sidebar">
        <div class="logo">
            <img src="{{ asset('legacy/images/'.$logoPath) }}" alt="Logo">
            <h3 class="menu-text">{{ $ecole->nom ?? 'Ecole' }}</h3>
        </div>
        @php
            $openSub = false;
        @endphp
        @foreach ($modules as $module => $info)
            @if (session('utilisateur.role') !== 'admin' && ! empty($info['icon']) && ! in_array($userPermissions[$module] ?? null, ['lecture', 'ecriture'], true)) @continue @endif
            @if (empty($info['icon']))
                @if ($openSub)
                    </div>
                    @php
                        $openSub = false;
                    @endphp
                @endif
                <div class="parent-menu" onclick="toggleSub(this)"><span class="menu-text"><i class="fa {{ $info['section_icon'] ?? 'fa-folder-open' }}"></i> {{ preg_replace('/^\s*\|\s*--\s*/', '', $info['label']) }}</span> <i class="fa fa-chevron-down arrow"></i></div>
                <div class="sub-menu" style="display:none;">
                @php
                    $openSub = true;
                @endphp
            @else
                @php
                    $href = $module === 'dashboard'
                        ? route('dashboard')
                        : (! empty($info['migrated']) && ! empty($info['route'])
                            ? route($info['route'])
                            : $legacyBase.($info['legacy_url'] ?? $info['url'] ?? '#'));
                @endphp
                <a href="{{ $href }}" @class(['active' => $module === 'liste_classes'])><i class="fa {{ $info['icon'] }}"></i> <span class="menu-text">{{ $info['label'] }}</span></a>
            @endif
        @endforeach
        @if ($openSub)</div>@endif
    </nav>
    <main>
        <header>
            <div class="header-left">
                <button title="Cacher les modules" class="burger-menu" onclick="toggleSidebar()"><i class="fa fa-bars"></i></button>
                <button title="Plein ecran" id="fullscreen-btn" onclick="toggleFullscreen()"><i id="fullscreen-icon" class="fa fa-expand"></i></button>
            </div>
            <h1><i class="fa fa-list"></i> Liste des eleves par classe</h1>
        </header>
        <div class="form-container">
            <div class="io" style="padding-top: 10px; padding-bottom: 30px;">
                <label for="annee_scolaire_filter">Annee scolaire :</label>
                <select id="annee_scolaire_filter" onchange="filterByAnneeScolaire()">
                    <option value="">Toutes les annees</option>
                    @foreach ($anneesScolaires as $annee)
                        <option value="{{ $annee }}" @selected($anneeScolaireFilter === $annee)>{{ $annee }}</option>
                    @endforeach
                </select>
            </div>
            <button class="btn-ajouter" onclick="openModal('ajouter')"><i class="fa fa-plus"></i> Ajouter une classe</button>
            <section id="fonctionnalites" class="features-grid">
                @foreach ($classes as $index => $classe)
                    @php
                        $img = $images[$index % count($images)];
                        $imgPath = preg_replace('#^(img|image)/#', '', $img);
                    @endphp
                    <div class="feature-item">
                        <img src="{{ asset('legacy/images/'.$imgPath) }}" alt="Photo de la classe {{ $classe->nom }}">
                        <div class="feature-text">
                            <h3>Classe {{ $classe->nom }}</h3>
                            <p>Niveau: {{ $classe->niveau ?? '' }}</p>
                            <p>Les enseignants ajoutent les notes de chaque eleve</p>
                        </div>
                        <a class="kaly" href="{{ route('modules.notes', array_filter(['classe' => $classe->id, 'annee_scolaire' => $anneeScolaireFilter ?: null])) }}">Notes</a>
                        <div class="action-buttons">
                            <button class="btn-modifier" onclick="openModal('modifier', '{{ $classe->id }}', @js($classe->nom), '{{ $classe->niveau }}')"><i class="fa fa-edit"></i></button>
                            <button class="btn-supprimer" onclick="confirmSupprimer('{{ $classe->id }}', @js($classe->nom))"><i class="fa fa-trash"></i></button>
                            <button class="btn-liste" onclick="showListeEleves('{{ $classe->id }}', @js($classe->nom))"><i class="fa fa-list"></i></button>
                        </div>
                    </div>
                @endforeach
            </section>
        </div>
        <footer>&copy; {{ date('Y') }} {{ $ecole->nom ?? 'Ecole' }}. Tous droits reserves.</footer>
    </main>
    <div id="modal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">x</span>
            <h2 id="modal-title">Ajouter une classe</h2>
            <form id="modal-form" method="POST">
                <input type="hidden" name="action" id="modal-action" value="ajouter">
                <input type="hidden" name="id" id="modal-id">
                <label for="modal-nom">Nom de la classe :</label>
                <input type="text" name="nom" id="modal-nom" required>
                <label for="modal-niveau">Niveau (optionnel) :</label>
                <input type="number" name="niveau" id="modal-niveau">
                <button type="submit">Enregistrer</button>
            </form>
        </div>
    </div>
    <div id="eleves-modal" class="modal">
        <div class="eleves-modal-content">
            <span class="close" onclick="closeElevesModal()">x</span>
            <button class="btn-imprimer" onclick="printListeEleves()">Imprimer</button>
            <div class="print-header">
                <img style="width: 100px; height: 100px !important;" src="{{ asset('legacy/images/'.$logoPath) }}" alt="Logo">
                <h1>{{ $ecole->nom ?? 'Ecole' }}</h1>
            </div>
            <h2 id="eleves-modal-title">Liste des eleves</h2>
            <div class="efenina" style="margin-bottom: 0px;">
                <label for="annee_scolaire_modal">Annee scolaire :</label>
                <select id="annee_scolaire_modal" onchange="updateListeEleves()">
                    <option value="">Toutes les annees</option>
                    @foreach ($anneesScolaires as $annee)
                        <option value="{{ $annee }}">{{ $annee }}</option>
                    @endforeach
                </select>
            </div>
            <div id="annee-scolaire-print" class="annee-scolaire-print">Annee scolaire : Toutes les annees</div>
            <table class="eleves-table">
                <thead><tr><th>Matricule</th><th>Nom</th><th>Prenom</th><th>Date de naissance</th><th>Genre</th><th>Statut</th><th>Annee scolaire</th><th>Adresse</th><th>Telephone</th><th>Pere</th><th>Mere</th></tr></thead>
                <tbody id="eleves-table-body"></tbody>
            </table>
        </div>
    </div>
</div>
<script>
const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
function toggleSidebar(){const sidebar=document.querySelector('nav');const mainContent=document.querySelector('main');const header=document.querySelector('header');if(window.innerWidth<=1100){sidebar.classList.toggle('active');}else{sidebar.classList.toggle('hidden');mainContent.classList.toggle('full-width');header.classList.toggle('full-width');}}
function toggleSub(elem){const sub=elem.nextElementSibling;const arrow=elem.querySelector('.arrow');if(sub.style.display==='none'||sub.style.display===''){sub.style.display='block';arrow.classList.add('open');}else{sub.style.display='none';arrow.classList.remove('open');}}
document.addEventListener('DOMContentLoaded',function(){const active=document.querySelector('nav a.active');if(active){const sub=active.closest('.sub-menu');if(sub){sub.style.display='block';const parent=sub.previousElementSibling;if(parent){const arrow=parent.querySelector('.arrow');if(arrow)arrow.classList.add('open');}}}});
function openModal(action,id='',nom='',niveau=''){document.getElementById('modal-title').textContent=action==='ajouter'?'Ajouter une classe':'Modifier une classe';document.getElementById('modal-action').value=action;document.getElementById('modal-id').value=id;document.getElementById('modal-nom').value=nom;document.getElementById('modal-niveau').value=niveau||'';document.getElementById('modal').style.display='flex';}
function closeModal(){document.getElementById('modal').style.display='none';}
function confirmSupprimer(id,nom){Swal.fire({title:'Confirmer ?',text:`Supprimer la classe "${nom}" ?`,icon:'warning',showCancelButton:true,confirmButtonColor:'#d33',cancelButtonColor:'#3085d6',confirmButtonText:'Oui, supprimer',cancelButtonText:'Annuler'}).then((result)=>{if(result.isConfirmed){$.ajax({url:`/classes/${id}`,type:'DELETE',headers:{'X-CSRF-TOKEN':csrfToken},success:function(res){Swal.fire({title:'Succes !',text:res.message,icon:'success',timer:2000,showConfirmButton:false});location.reload();},error:function(){Swal.fire('Erreur','Probleme serveur','error');}});}});}
let currentClasseId=null;let currentClasseNom=null;
function showListeEleves(id_classe,nom_classe){currentClasseId=id_classe;currentClasseNom=nom_classe;document.getElementById('eleves-modal-title').textContent=`Eleves de ${nom_classe}`;document.getElementById('eleves-table-body').innerHTML='<tr><td colspan="11">Chargement...</td></tr>';const annee=document.getElementById('annee_scolaire_modal').value||'';document.getElementById('annee-scolaire-print').textContent=`Annee scolaire : ${annee || 'Toutes les annees'}`;$.ajax({url:`/classes/${id_classe}/students`,type:'POST',headers:{'X-CSRF-TOKEN':csrfToken},data:{annee_scolaire:annee},success:function(data){const tbody=document.getElementById('eleves-table-body');tbody.innerHTML='';if(data.length===0){tbody.innerHTML='<tr><td colspan="11">Aucun eleve dans cette classe.</td></tr>';}else{data.forEach(e=>{tbody.innerHTML+=`<tr><td>${e.matricule||'-'}</td><td>${e.nom||'-'}</td><td>${e.prenom||'-'}</td><td>${e.date_naissance||'-'}</td><td>${e.genre==='F'?'Fille':'Garcon'}</td><td>${e.statut||'-'}</td><td>${e.annee_scolaire||'-'}</td><td>${e.adresse||'-'}</td><td>${e.telephone||'-'}</td><td>${e.nom_pere||'-'}</td><td>${e.nom_mere||'-'}</td></tr>`;});}document.getElementById('eleves-modal').style.display='flex';},error:function(){document.getElementById('eleves-table-body').innerHTML='<tr><td colspan="11">Erreur chargement</td></tr>';document.getElementById('eleves-modal').style.display='flex';}});}
function updateListeEleves(){if(currentClasseId&&currentClasseNom)showListeEleves(currentClasseId,currentClasseNom);}
function filterByAnneeScolaire(){const annee=document.getElementById('annee_scolaire_filter').value;const url=new URL(window.location.href);if(annee)url.searchParams.set('annee_scolaire',annee);else url.searchParams.delete('annee_scolaire');window.location.href=url;}
function closeElevesModal(){document.getElementById('eleves-modal').style.display='none';}
function printListeEleves(){window.print();}
function toggleFullscreen(){const icon=document.getElementById('fullscreen-icon');if(!document.fullscreenElement){document.documentElement.requestFullscreen().catch(err=>console.error(err));icon.classList.replace('fa-expand','fa-compress');}else{document.exitFullscreen();icon.classList.replace('fa-compress','fa-expand');}}
$(document).ready(function(){$('#modal-form').on('submit',function(e){e.preventDefault();const action=document.getElementById('modal-action').value;const id=document.getElementById('modal-id').value;const url=action==='ajouter'?'{{ route('modules.liste-classes.store') }}':`/classes/${id}`;const method=action==='ajouter'?'POST':'PUT';$.ajax({url,type:method,data:$(this).serialize(),headers:{'X-CSRF-TOKEN':csrfToken},success:function(res){Swal.fire({title:'Succes !',text:res.message,icon:'success',timer:2000,showConfirmButton:false});closeModal();location.reload();},error:function(xhr){Swal.fire('Erreur',xhr.responseJSON?.message||'Probleme serveur','error');}});});});
</script>
</body>
</html>
