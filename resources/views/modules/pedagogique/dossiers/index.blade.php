<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Depot dossier</title>
<link rel="stylesheet" href="{{ asset('legacy/assets/fontawesome/css/all.min.css') }}">
<link rel="stylesheet" href="{{ asset('legacy/fa/css/font-awesome.min.css') }}">
@include('modules.professeur.bulletin.partials.styles')
<style>
.dossier-layout{display:grid;grid-template-columns:minmax(300px,420px) minmax(0,1fr);gap:18px;align-items:start}.dossier-panel{background:var(--card);border:1px solid var(--border);border-radius:8px;padding:18px;box-shadow:0 16px 40px #0004;min-width:0;overflow:hidden}.dossier-panel h2{font-size:1rem;color:var(--primary);margin:0 0 16px}.field-grid{display:grid;gap:13px}.field label{display:block;color:var(--text-sec);font-weight:800;margin-bottom:7px}.field input,.field select{width:100%;padding:12px;border-radius:8px;border:1px solid var(--border);background:var(--surface);color:var(--text);outline:none}.field input:focus,.field select:focus{border-color:var(--primary);box-shadow:0 0 0 3px rgba(0,200,83,.16)}.file-drop{border:1px dashed rgba(0,200,83,.55);background:rgba(0,200,83,.06);border-radius:8px;padding:14px}.file-drop input{border:0;background:transparent;padding:0}.filter-row{display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:12px;margin-bottom:14px}.data-table{width:100%;border-collapse:collapse;min-width:850px}.data-table th,.data-table td{border-bottom:1px solid var(--border);padding:12px;text-align:left;vertical-align:top}.data-table th{background:#034a3b;color:white;text-transform:uppercase;font-size:.78rem}.data-table a{color:var(--primary);font-weight:800;text-decoration:none}.badge-type{display:inline-flex;padding:5px 9px;border-radius:999px;background:rgba(59,130,246,.14);color:#93c5fd;font-weight:800}.danger-small{background:#dc2626;color:white;border:0;border-radius:8px;padding:8px 11px;cursor:pointer;font-weight:800}.empty-state{text-align:center;color:var(--text-sec);padding:18px}@media(max-width:1000px){.dossier-layout{grid-template-columns:1fr}.data-table{min-width:760px}}@media(max-width:760px){.form-container{padding:16px}.dossier-panel{padding:15px}.filter-row{grid-template-columns:1fr}.filter-row .kaly,.dossier-panel .kaly{width:100%}.field-grid{gap:11px}.table-wrapper{overflow:auto;-webkit-overflow-scrolling:touch;margin-inline:-4px}.data-table{min-width:640px}}
</style>
</head>
<body>
@include('modules.professeur.bulletin.partials.shell', ['activeModule'=>$activeModule])
<header><div class="header-left"><button class="burger-menu" onclick="toggleSidebar()"><i class="fa fa-bars"></i></button><button id="fullscreen-btn" onclick="toggleFullscreen()"><i id="fullscreen-icon" class="fa fa-expand"></i></button></div><h1><i class="fa fa-download"></i> Depot dossier</h1></header>
<main>
<div class="form-container">
    <div class="dossier-layout">
        <section class="dossier-panel">
            <h2><i class="fa fa-cloud-upload"></i> Nouveau dossier</h2>
            <form method="POST" action="{{ route('modules.depot-dossier.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="field-grid">
                    <div class="field"><label>Type de dossier</label><select name="classes"><option value="eleve">Eleve</option><option value="enseignant">Enseignant</option></select></div>
                    <div class="field"><label>Nom du dossier</label><input name="anarana" placeholder="Ex: Certificat medical" required></div>
                    <div class="field"><label>Description</label><input name="description" placeholder="Details utiles pour retrouver le fichier"></div>
                    <div class="field"><label>Mois</label><select name="mois">@foreach($months as $month)<option value="{{ $month }}" @selected($selectedMonth===$month)>{{ $month }}</option>@endforeach</select></div>
                    <div class="field"><label>Annee scolaire</label><select name="annee_scolaire">@foreach($annees as $annee)<option value="{{ $annee }}" @selected($selectedAnnee===$annee)>{{ $annee }}</option>@endforeach</select></div>
                    <div class="field file-drop"><label>Fichier a deposer</label><input type="file" name="fichier" required></div>
                </div>
                <button class="kaly"><i class="fa fa-upload"></i> Deposer</button>
            </form>
        </section>
        <section class="dossier-panel">
            <h2><i class="fa fa-folder-open"></i> Dossiers enregistres</h2>
            <form method="GET" action="{{ route('modules.depot-dossier') }}" class="filter-row">
                <div class="field"><label>Annee</label><select name="annee_scolaire">@foreach($annees as $annee)<option value="{{ $annee }}" @selected($selectedAnnee===$annee)>{{ $annee }}</option>@endforeach</select></div>
                <div class="field"><label>Mois</label><select name="mois">@foreach($months as $month)<option value="{{ $month }}" @selected($selectedMonth===$month)>{{ $month }}</option>@endforeach</select></div>
                <div class="field"><label>Recherche</label><input name="search_nom" value="{{ $search }}" placeholder="Nom du dossier"></div>
                <button class="kaly"><i class="fa fa-search"></i> Rechercher</button>
            </form>
            <div class="table-wrapper">
                <table class="data-table">
                    <thead><tr><th>ID</th><th>Type</th><th>Nom</th><th>Description</th><th>Fichier</th><th>Date</th><th></th></tr></thead>
                    <tbody>
                    @forelse($dossiers as $dossier)
                        <tr>
                            <td>{{ $dossier->id }}</td>
                            <td><span class="badge-type">{{ $dossier->type_dossier }}</span></td>
                            <td><strong>{{ $dossier->anarana }}</strong></td>
                            <td>{{ $dossier->description ?: '-' }}</td>
                            <td><a href="{{ asset('legacy/Uploads/'.$dossier->fichier) }}" download><i class="fa fa-download"></i> Telecharger</a></td>
                            <td>{{ $dossier->date_upload }}</td>
                            <td><form method="POST" action="{{ route('modules.depot-dossier.delete',$dossier->id) }}" class="js-confirm-submit" data-confirm-title="Supprimer ce dossier ?" data-confirm-text="Le dossier et son fichier seront supprimes.">@csrf @method('DELETE')<button class="danger-small"><i class="fa fa-trash"></i></button></form></td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="empty-state">Aucun dossier trouve.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</div>
<footer>&copy; {{ date('Y') }} {{ $ecole->nom ?? 'Ecole' }}. Tous droits reserves.</footer>
</main>
<script>
function toggleSub(e){const s=e.nextElementSibling,a=e.querySelector('.arrow');s.style.display=s.style.display==='block'?'none':'block';a.classList.toggle('fa-chevron-down');a.classList.toggle('fa-chevron-up')}
function toggleSidebar(){document.getElementById('sidebar').classList.toggle('active');document.querySelector('main').classList.toggle('full-width');document.querySelector('header').classList.toggle('full-width')}
function toggleFullscreen(){const i=document.getElementById('fullscreen-icon');if(!document.fullscreenElement){document.documentElement.requestFullscreen();i.classList.replace('fa-expand','fa-compress')}else{document.exitFullscreen();i.classList.replace('fa-compress','fa-expand')}}
document.addEventListener('DOMContentLoaded',()=>{const a=document.querySelector('nav a.active'),s=a?.closest('.sub-menu');if(s){s.style.display='block';s.previousElementSibling?.querySelector('.arrow')?.classList.replace('fa-chevron-down','fa-chevron-up')}})
</script>
</body>
</html>
