<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Emploi du temps - {{ $ecole->nom ?? 'Ecole' }}</title>
    <link rel="stylesheet" href="{{ asset('legacy/assets/fontawesome/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('legacy/assets/sweetalert2/sweetalert2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('legacy/fa/css/font-awesome.min.css') }}">
    <script src="{{ asset('legacy/js/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('legacy/assets/sweetalert2/sweetalert2.min.js') }}"></script>
    @include('modules.professeur.bulletin.partials.styles')
    <style>
        .controls { text-align:center; margin-bottom:32px; }
        .controls label { margin-right:12px; color:var(--text-sec); font-size:1.05rem; }
        .controls select { max-width:320px; display:inline-block; }
        .ref { text-align:center; font-size:1.2rem; font-weight:700; color:var(--primary); margin:8px 0 24px; }
        .input-group { display:flex; flex-wrap:wrap; gap:10px; margin-bottom:14px; padding:12px; background:rgba(255,255,255,.015); border:1px solid var(--border); border-radius:8px; }
        .input-group input { flex:1; min-width:100px; padding:10px; background:var(--surface); border:1px solid var(--border); border-radius:6px; color:var(--text); font-size:.78rem; }
        .input-group input[type="time"] { max-width:120px; min-width:112px; }
        .danger-btn { background:var(--danger); }
        .table-container { margin-top:36px; overflow-x:auto; }
        .schedule-table { width:100%; min-width:920px; border-collapse:collapse; }
        .schedule-table th,.schedule-table td { padding:12px; border:1px solid var(--border); text-align:center; }
        .schedule-table th { background:rgba(0,200,83,.1); color:var(--primary); font-weight:700; }
        .schedule-table tbody tr:hover td { background:rgba(0,200,83,.04); }
        .empty-state { padding:24px; color:var(--text-sec); text-align:center; border:1px dashed var(--border); border-radius:12px; }
        .readonly-note { width:min(760px,100%); margin:0 auto 22px; padding:14px 16px; border:1px solid var(--border); border-left:4px solid var(--primary); border-radius:8px; background:var(--surface); color:var(--text-sec); }
        @media screen and (max-width:900px){body{overflow-x:hidden}.table-container{overflow:visible!important;margin-top:24px!important}.table-container h2,.schedule-table{width:920px!important;max-width:920px!important;min-width:920px!important;margin-left:0!important;margin-right:0!important;zoom:.72;transform-origin:top left}.schedule-table{font-size:1rem!important}.schedule-table th,.schedule-table td{padding:12px!important}.table-container>.kaly{width:100%;max-width:340px;margin-top:22px!important}}
        @media screen and (max-width:760px){.table-container h2,.schedule-table{zoom:.62}}
        @media screen and (max-width:700px){.table-container h2,.schedule-table{zoom:.52}}
        @media screen and (max-width:600px){.table-container h2,.schedule-table{zoom:.44}}
        @media screen and (max-width:520px){.table-container h2,.schedule-table{zoom:.38}}
        @media screen and (max-width:380px){.table-container h2,.schedule-table{zoom:.36}}
        @media print { @page{size:A4 landscape;margin:8mm;} *,*::before,*::after{-webkit-print-color-adjust:exact!important;print-color-adjust:exact!important;background-image:none!important;} body{background:#fff!important;color:#000;margin:0!important;padding:0!important;overflow:visible!important;} nav,.controls,header,button,footer,#emploi-form,.novaskol-global-actions,.global-dropdown,.novaskol-loader{display:none!important;} main{margin:0!important;padding:0!important;background:#fff!important;overflow:visible!important;} .form-container{display:block!important;background:#fff!important;border:0!important;box-shadow:none!important;padding:0!important;margin:0!important;} .table-container{display:block!important;max-width:100%;margin:0 auto!important;overflow:visible!important;zoom:1!important;transform:none!important;} .table-container h2{text-align:center!important;color:#000!important;margin:0 0 8mm!important;font-size:16pt!important;width:auto!important;max-width:none!important;min-width:0!important;zoom:1!important;transform:none!important;} .schedule-table{width:100%!important;max-width:none!important;min-width:0!important;font-size:13px!important;border-collapse:collapse;border:1px solid #666;table-layout:fixed!important;zoom:1!important;transform:none!important;} .schedule-table th,.schedule-table td{color:#000!important;border:1px solid #999!important;padding:12px 10px!important;text-align:center;background:#fff!important;word-break:break-word!important;} .schedule-table th{background:#e5e5e5!important;color:#000!important;} }
    </style>
</head>
<body>
@include('modules.professeur.bulletin.partials.shell', ['activeModule' => 'emploi_temps'])
<header>
    <div class="header-left">
        <button class="burger-menu" onclick="toggleSidebar()"><i class="fa fa-bars"></i></button>
        <button id="fullscreen-btn" onclick="toggleFullscreen()"><i id="fullscreen-icon" class="fa fa-expand"></i></button>
    </div>
    <h1><i class="fa fa-calendar-plus-o"></i> Emploi du temps</h1>
</header>
<main>
    <div class="form-container">
        <div class="controls">
            <form method="GET" action="{{ route('modules.emploi-temps') }}">
                <label for="classe">Choisir une classe :</label>
                <select name="classe" id="classe" onchange="this.form.submit()">
                    <option value="">-- Selectionner --</option>
                    @foreach ($classes as $classe)
                        <option value="{{ $classe->id }}" @selected($selectedClasse === $classe->id)>{{ $classe->nom }}</option>
                    @endforeach
                </select>
            </form>
        </div>

        @if ($selectedClasse)
            @if ($canWrite)
                <form id="emploi-form" method="POST" action="{{ route('modules.emploi-temps.store') }}">
                    @csrf
                    <input type="hidden" name="classe" value="{{ $selectedClasse }}">
                    <div class="ref"><h2>Ajouter un creneau</h2></div>
                    <div id="creneaux">
                        @foreach ($emploi as $index => $creneau)
                            @php
                                [$heureDebut, $heureFin] = explode('-', str_replace('h', ':', $creneau['heure']));
                            @endphp
                            <div class="input-group" data-index="{{ $index }}">
                                <input type="time" name="emploi[{{ $index }}][heure_debut]" value="{{ $heureDebut }}" required>
                                <input type="time" name="emploi[{{ $index }}][heure_fin]" value="{{ $heureFin }}" required>
                                @foreach ($jours as $jour)
                                    <input type="text" name="emploi[{{ $index }}][{{ $jour }}]" placeholder="{{ ucfirst($jour) }}" value="{{ $creneau[$jour] ?? '' }}">
                                @endforeach
                                <button class="kaly danger-btn" type="button" onclick="removeCreneau(this)">Supprimer</button>
                            </div>
                        @endforeach
                    </div>
                    <div class="actions">
                        <button class="kaly" type="button" onclick="addCreneau()"><i class="fa fa-plus"></i> Ajouter un creneau</button>
                        <button class="kaly" type="submit"><i class="fa fa-save"></i> Enregistrer</button>
                    </div>
                </form>
            @else
                <div class="readonly-note"><i class="fa fa-eye"></i> Mode lecture : tu peux consulter et imprimer l'emploi du temps existant, sans modification.</div>
            @endif

            @if (! empty($emploi))
                <div class="table-container">
                    <h2 style="text-align:center; margin-bottom:20px;">Emploi du temps pour la classe {{ $classeNom }}</h2>
                    <table id="emploi-table" class="schedule-table">
                        <thead>
                            <tr>
                                <th>Heure</th>
                                @foreach ($jours as $jour)
                                    <th>{{ ucfirst($jour) }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($emploi as $creneau)
                                <tr>
                                    <th>{{ $creneau['heure'] ?? '-' }}</th>
                                    @foreach ($jours as $jour)
                                        <td>{{ ($creneau[$jour] ?? '') !== '' ? $creneau[$jour] : '-' }}</td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <button class="kaly" style="margin-top:40px;" onclick="window.print()"><i class="fa fa-print"></i> Imprimer</button>
                    <button class="kaly" style="margin-top:40px;background:#333!important" onclick="window.print()"><i class="fa fa-file-pdf-o"></i> Apercu PDF</button>
                </div>
            @else
                <div class="empty-state">{{ $canWrite ? "Aucun creneau n'est encore enregistre pour cette classe." : "Aucun emploi du temps n'est encore disponible pour cette classe." }}</div>
            @endif
        @else
            <div class="empty-state">Selectionne une classe pour {{ $canWrite ? 'gerer' : 'consulter' }} son emploi du temps.</div>
        @endif
    </div>
    <footer>&copy; {{ date('Y') }} {{ $ecole->nom ?? 'Ecole' }}. Tous droits reserves.</footer>
</main>
<script>
function toggleSub(el){const sub=el.nextElementSibling;const arrow=el.querySelector('.arrow');sub.style.display=sub.style.display==='block'?'none':'block';arrow.classList.toggle('fa-chevron-down');arrow.classList.toggle('fa-chevron-up');}
function toggleSidebar(){document.getElementById('sidebar').classList.toggle('active');document.querySelector('main').classList.toggle('full-width');document.querySelector('header').classList.toggle('full-width');}
function toggleFullscreen(){const icon=document.getElementById('fullscreen-icon');if(!document.fullscreenElement){document.documentElement.requestFullscreen();icon.classList.replace('fa-expand','fa-compress');}else{document.exitFullscreen();icon.classList.replace('fa-compress','fa-expand');}}
document.addEventListener('DOMContentLoaded',()=>{const active=document.querySelector('nav a.active');if(active){const sub=active.closest('.sub-menu');if(sub){sub.style.display='block';const arrow=sub.previousElementSibling?.querySelector('.arrow');if(arrow){arrow.classList.replace('fa-chevron-down','fa-chevron-up');}}}});
let creneauIndex = {{ count($emploi) }};
let lastHeureFin = "07:00";
const existingGroups = document.querySelectorAll('.input-group');
if(existingGroups.length > 0){const last = existingGroups[existingGroups.length - 1].querySelector('input[name$="[heure_fin]"]');if(last && last.value){lastHeureFin = last.value;}}
function addThirtyMinutes(timeStr){let [h,m]=timeStr.split(':').map(Number);m+=30;if(m>=60){m-=60;h+=1;}if(h>=24){h=23;m=59;}return String(h).padStart(2,'0')+':'+String(m).padStart(2,'0');}
function addCreneau(){const container=document.getElementById('creneaux');const start=lastHeureFin;const end=addThirtyMinutes(start);const jours=@js($jours);const div=document.createElement('div');div.className='input-group';div.dataset.index=creneauIndex;const dayInputs=jours.map((jour)=>`<input type="text" name="emploi[${creneauIndex}][${jour}]" placeholder="${jour.charAt(0).toUpperCase()+jour.slice(1)}">`).join('');div.innerHTML=`<input type="time" name="emploi[${creneauIndex}][heure_debut]" value="${start}" required><input type="time" name="emploi[${creneauIndex}][heure_fin]" value="${end}" required>${dayInputs}<button class="kaly danger-btn" type="button" onclick="removeCreneau(this)">Supprimer</button>`;container.appendChild(div);creneauIndex++;lastHeureFin=end;}
function removeCreneau(button){button.closest('.input-group')?.remove();}
@if (session('schedule_msg'))
Swal.fire({title:@js(session('schedule_msg.title')),text:@js(session('schedule_msg.text')),icon:@js(session('schedule_msg.type')),timer:@js(session('schedule_msg.type') === 'success' ? 2500 : 3500),showConfirmButton:false});
@endif
</script>
</body>
</html>
