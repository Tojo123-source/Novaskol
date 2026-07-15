<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajout des notes d'examen blanc - {{ $ecole->nom ?? 'Ecole' }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('legacy/assets/fontawesome/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('legacy/assets/sweetalert2/sweetalert2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('legacy/fa/css/font-awesome.min.css') }}">
    <script src="{{ asset('legacy/js/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('legacy/assets/sweetalert2/sweetalert2.min.js') }}"></script>
    @include('modules.professeur.bulletin.partials.styles')
    <style>
        .table-wrapper { overflow:auto; margin:25px 0; border:1px solid var(--border); border-radius:8px; }
        .notes-table { width:max-content; min-width:980px; border-collapse:collapse; color:var(--text); background:#0f1117; }
        .notes-table th,.notes-table td { border:1px solid var(--border); padding:10px; text-align:center; min-width:102px; }
        .notes-table th { background:rgba(0,200,83,.18); color:var(--primary); font-weight:800; }
        .notes-table td:first-child,.notes-table th:first-child { text-align:left; min-width:180px; max-width:190px; position:sticky; left:0; background:#111827; z-index:2; white-space:normal; overflow-wrap:anywhere; }
        .notes-table th:first-child { background:#034a3b; color:white; z-index:3; }
        .note-input { width:100%; min-width:70px; padding:9px 7px; text-align:center; background:var(--surface); color:var(--text); border:1px solid var(--border); border-radius:6px; }
        .remark-input { min-width:150px; }
        .empty-state { padding:26px; color:var(--text-sec); text-align:center; border:1px dashed var(--border); border-radius:12px; margin-top:18px; }
        .message { padding:14px; border-radius:6px; margin-bottom:18px; text-align:center; border:1px solid var(--success); color:var(--success); background:rgba(16,185,129,.15); }
        .form-container { overflow:hidden; }
        @media (max-width:760px) {
            .form-container { padding:12px!important; border-radius:12px!important; }
            .filters-grid { display:grid!important; grid-template-columns:repeat(2,minmax(0,1fr)); gap:9px!important; margin-bottom:14px!important; }
            .filters-grid>div { min-width:0!important; }
            .filters-grid>div:first-child { grid-column:1/-1; }
            label { font-size:.76rem; margin-bottom:5px; }
            select,input { min-height:39px; padding:8px 9px; font-size:.82rem; border-radius:9px; }
            .print-header { margin:12px 0 14px!important; }
            .print-header h2 { font-size:1rem!important; line-height:1.18; }
            .print-header p { font-size:.78rem; line-height:1.25; }
            .table-wrapper { max-width:100%; margin:14px 0; overflow-x:auto; overflow-y:hidden; -webkit-overflow-scrolling:touch; border-radius:12px; box-shadow:inset -16px 0 18px -18px var(--primary-glow); }
            .notes-table { min-width:640px; font-size:.72rem; }
            .notes-table th,.notes-table td { padding:6px 5px; min-width:58px; line-height:1.15; }
            .notes-table td:first-child,.notes-table th:first-child { min-width:112px; max-width:120px; white-space:normal; overflow-wrap:anywhere; }
            .note-input { min-width:52px; max-width:60px; padding:7px 5px; font-size:.8rem; border-radius:8px; }
            .remark-input { min-width:112px; padding:7px 6px; font-size:.78rem; border-radius:8px; }
            .actions { display:grid!important; grid-template-columns:repeat(2,minmax(0,1fr)); gap:8px; }
            .actions .kaly { width:100%; min-height:40px; margin:0; padding:9px 8px; font-size:.78rem; border-radius:10px; }
            .empty-state { padding:18px 12px; font-size:.88rem; }
        }
        @media (max-width:520px) {
            .form-container { padding:10px!important; }
            .filters-grid { gap:8px!important; }
            .notes-table { min-width:580px; font-size:.68rem; }
            .notes-table th,.notes-table td { padding:5px 4px; }
            .notes-table td:first-child,.notes-table th:first-child { min-width:96px; max-width:104px; }
            .note-input { min-width:46px; max-width:52px; font-size:.76rem; padding:6px 4px; }
            .remark-input { min-width:96px; font-size:.74rem; }
            .actions { grid-template-columns:1fr; }
        }
        @media print {
            @page{size:A4 landscape;margin:7mm;}
            *,*::before,*::after{-webkit-print-color-adjust:exact!important;print-color-adjust:exact!important;box-shadow:none!important;text-shadow:none!important;}
            nav,header,footer,.filters-grid,.actions,.message,.novaskol-global-actions,.global-dropdown,.novaskol-loader{display:none!important;}
            html,body,main{background:white!important;color:#111!important;margin:0!important;padding:0!important;}
            .form-container{display:block!important;background:white!important;color:#111!important;border:0!important;box-shadow:none!important;padding:0!important;margin:0!important;}
            .print-header{text-align:center!important;margin:0 0 6mm!important;color:#111!important;}
            .print-header h2{color:#047857!important;font-size:15pt!important;}
            .table-wrapper{overflow:visible!important;border:0!important;width:100%!important;}
            .notes-table{width:100%!important;min-width:0!important;table-layout:fixed!important;background:white!important;color:#111!important;font-size:7.5pt!important;}
            .notes-table th,.notes-table td{border:1px solid #000!important;color:#111!important;background:white!important;padding:3px 4px!important;word-break:break-word!important;min-width:0!important;}
            .notes-table th{background:#034a3b!important;color:white!important;}
            .notes-table td:first-child,.notes-table th:first-child{position:static!important;background:#e5e5e5!important;color:#111!important;width:16%!important;min-width:0!important;}
            input{border:0!important;background:transparent!important;color:#111!important;padding:0!important;width:100%!important;text-align:center!important;font-size:7.5pt!important;}
        }
    </style>
</head>
<body>
@include('modules.professeur.bulletin.partials.shell', ['activeModule' => 'examen_blanc'])
<header>
    <div class="header-left">
        <button class="burger-menu" onclick="toggleSidebar()"><i class="fa fa-bars"></i></button>
        <button id="fullscreen-btn" onclick="toggleFullscreen()"><i id="fullscreen-icon" class="fa fa-expand"></i></button>
    </div>
    <div class="header-center"><i class="fa fa-graduation-cap"></i> Ajout des notes d'examen blanc</div>
</header>
<main>
    <div class="form-container">
        <form method="GET" action="{{ route('modules.examen-blanc') }}">
            <div class="filters-grid">
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
                    <label for="session">Session</label>
                    <select name="session" id="session" onchange="this.form.submit()">
                        @foreach ($sessionLabels as $key => $label)
                            <option value="{{ $key }}" @selected($selectedSession === $key)>{{ $label }}</option>
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
            </div>
        </form>

        @if ($selectedClasse && $eleves->isNotEmpty() && $matieres->isNotEmpty())
            <div class="print-header">
                <p>Classe : {{ $classeNom }} | {{ $ecole->nom ?? 'Ecole' }}</p>
                <h2>Notes - {{ $sessionLabels[$selectedSession] }} - {{ $selectedAnnee }}</h2>
                <p><strong>NB : Veuillez entrer les notes des eleves sur 20 !</strong></p>
            </div>
            <form method="POST" action="{{ route('modules.examen-blanc.store') }}" id="examNotesForm">
                @csrf
                <input type="hidden" name="classe" value="{{ $selectedClasse }}">
                <input type="hidden" name="session" value="{{ $selectedSession }}">
                <input type="hidden" name="annee_scolaire" value="{{ $selectedAnnee }}">
                <div class="table-wrapper">
                    <table class="notes-table">
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
                <div class="actions">
                    <button type="button" class="kaly" id="saveExamNotesBtn"><i class="fa fa-save"></i> Enregistrer les notes</button>
                    <button type="button" class="kaly" onclick="window.print()"><i class="fa fa-print"></i> Imprimer</button>
                </div>
            </form>
        @elseif ($selectedClasse && $matieres->isEmpty())
            <div class="empty-state">Aucune matiere n'est affectee a cette classe d'examen.</div>
        @elseif ($selectedClasse)
            <div class="empty-state">Aucun eleve trouve pour cette classe et cette annee scolaire.</div>
        @else
            <div class="empty-state">Selectionne une classe d'examen pour afficher le tableau des notes.</div>
        @endif
    </div>
    <footer>&copy; {{ date('Y') }} {{ $ecole->nom ?? 'Ecole' }}. Tous droits reserves.</footer>
</main>
<script>
function toggleSub(el){const sub=el.nextElementSibling;const arrow=el.querySelector('.arrow');sub.style.display=sub.style.display==='block'?'none':'block';arrow.classList.toggle('fa-chevron-down');arrow.classList.toggle('fa-chevron-up');}
function toggleSidebar(){document.getElementById('sidebar').classList.toggle('active');document.querySelector('main').classList.toggle('full-width');document.querySelector('header').classList.toggle('full-width');}
function toggleFullscreen(){const icon=document.getElementById('fullscreen-icon');if(!document.fullscreenElement){document.documentElement.requestFullscreen();icon.classList.replace('fa-expand','fa-compress');}else{document.exitFullscreen();icon.classList.replace('fa-compress','fa-expand');}}
document.addEventListener('DOMContentLoaded',()=>{const active=document.querySelector('nav a.active');if(active){const sub=active.closest('.sub-menu');if(sub){sub.style.display='block';const arrow=sub.previousElementSibling?.querySelector('.arrow');if(arrow){arrow.classList.replace('fa-chevron-down','fa-chevron-up');}}}});
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
document.getElementById('saveExamNotesBtn')?.addEventListener('click',function(){Swal.fire({title:"Confirmer l'enregistrement ?",text:"Les notes et remarques seront sauvegardees pour cet examen blanc.",icon:'question',showCancelButton:true,confirmButtonColor:'#00c853',cancelButtonColor:'#ef4444',confirmButtonText:'Oui, enregistrer',cancelButtonText:'Annuler'}).then((result)=>{if(result.isConfirmed){document.getElementById('examNotesForm').submit();}});});
@if (session('exam_msg'))
Swal.fire({title:'Succes',text:@js(session('exam_msg.text')),icon:@js(session('exam_msg.type')),timer:3000,showConfirmButton:false});
@endif
</script>
</body>
</html>
