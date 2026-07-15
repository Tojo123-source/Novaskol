<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Calendrier academique</title>
<link rel="stylesheet" href="{{ asset('legacy/js/fullcalendar.min.css') }}">
<link rel="stylesheet" href="{{ asset('legacy/assets/fontawesome/css/all.min.css') }}">
<link rel="stylesheet" href="{{ asset('legacy/fa/css/font-awesome.min.css') }}">
<link rel="stylesheet" href="{{ asset('legacy/assets/sweetalert2/sweetalert2.min.css') }}">
<script src="{{ asset('legacy/js/index.global.js') }}"></script>
<script src="{{ asset('legacy/assets/sweetalert2/sweetalert2.min.js') }}"></script>
@include('modules.professeur.bulletin.partials.styles')
<style>
.calendar-shell{display:grid;grid-template-columns:minmax(0,1fr) 280px;gap:18px;align-items:start}.calendar-card{background:var(--card);border:1px solid var(--border);border-radius:8px;padding:18px;box-shadow:0 18px 45px var(--shadow-soft,rgba(0,0,0,.22));min-width:0;overflow:hidden}.calendar-scroll{overflow-x:auto;overflow-y:hidden;-webkit-overflow-scrolling:touch;max-width:100%;width:100%;overscroll-behavior-x:contain}.calendar-side{background:var(--card);border:1px solid var(--border);border-radius:8px;padding:18px;position:sticky;top:90px}.calendar-side h2,.calendar-card h2{font-size:1rem;color:var(--primary);margin:0 0 12px}.legend{display:grid;gap:10px}.legend span{display:flex;align-items:center;gap:10px;color:var(--text-sec);font-weight:700}.legend i{width:12px;height:12px;border-radius:999px;display:inline-block}.hint,.readonly-note{margin-top:18px;padding:12px;border:1px dashed var(--border);border-radius:8px;color:var(--text-sec);line-height:1.55;background:var(--surface)}.readonly-note{border-style:solid;border-left:4px solid var(--primary)}.quick-add{width:100%;margin-top:16px}.fc{color:var(--text);font-family:system-ui,sans-serif}.fc .fc-toolbar-title{font-size:1.25rem;color:var(--text)}.fc-theme-standard td,.fc-theme-standard th,.fc-theme-standard .fc-scrollgrid{border-color:var(--border)}.fc-col-header-cell{background:var(--surface);color:var(--primary);padding:8px 0}.fc-daygrid-day-number{color:var(--text-sec);font-weight:800}.fc-daygrid-day,.fc-list,.fc-list-table td{background:var(--card);color:var(--text)}.fc-day-today{background:rgba(0,200,83,.09)!important}.fc-button{background:var(--primary)!important;border:0!important;color:#062b1d!important;font-weight:900!important;border-radius:8px!important;padding:8px 12px!important}.fc-button:disabled{opacity:.55}.fc-event{border:0!important;border-radius:6px!important;padding:3px 6px!important;font-weight:800;box-shadow:0 5px 16px var(--shadow-soft,rgba(0,0,0,.18))}.swal-calendar-form{display:grid;grid-template-columns:1fr 1fr;gap:12px;text-align:left}.swal-calendar-form label{color:#334155;font-weight:800;font-size:.82rem}.swal-calendar-form input,.swal-calendar-form select,.swal-calendar-form textarea{width:100%;border:1px solid #cbd5e1;border-radius:8px;padding:10px;color:#111827;background:white}.swal-calendar-form textarea{grid-column:1/-1;min-height:90px;resize:vertical}.swal-calendar-form .full{grid-column:1/-1}@media(max-width:980px){.calendar-shell{grid-template-columns:1fr}.calendar-side{position:static}.fc .fc-toolbar{display:grid;gap:10px;justify-content:stretch}.fc .fc-toolbar-title{text-align:center}}@media(max-width:760px){body{overflow-x:hidden}.form-container{padding:16px;overflow-x:hidden}.calendar-shell,.calendar-card,.calendar-side{min-width:0;max-width:100%;overflow:hidden}.calendar-card,.calendar-side{padding:15px}.calendar-scroll{max-width:100%;width:100%;padding-bottom:8px;overflow-x:auto!important;overflow-y:hidden!important}.calendar-scroll #calendar{width:760px!important;min-width:760px!important;max-width:none!important}.calendar-scroll .fc,.calendar-scroll .fc-view-harness,.calendar-scroll .fc-scrollgrid,.calendar-scroll .fc .fc-toolbar{min-width:760px!important}.fc .fc-toolbar-title{font-size:1rem}.swal-calendar-form{grid-template-columns:1fr}}
</style>
</head>
<body>
@include('modules.professeur.bulletin.partials.shell', ['activeModule'=>$activeModule])
<header><div class="header-left"><button class="burger-menu" onclick="toggleSidebar()"><i class="fa fa-bars"></i></button><button id="fullscreen-btn" onclick="toggleFullscreen()"><i id="fullscreen-icon" class="fa fa-expand"></i></button></div><div class="header-center"><i class="fa fa-calendar"></i> Calendrier academique</div></header>
<main>
<div class="form-container">
    <div class="calendar-shell">
        <section class="calendar-card">
            <h2>Planning scolaire</h2>
            <div class="calendar-scroll"><div id="calendar"></div></div>
        </section>
        <aside class="calendar-side">
            <h2>Types d'evenements</h2>
            <div class="legend">
                <span><i style="background:#00c853"></i> Rendez-vous</span>
                <span><i style="background:#3b82f6"></i> Examen</span>
                <span><i style="background:#8b5cf6"></i> Session examen</span>
                <span><i style="background:#f59e0b"></i> Reunion</span>
                <span><i style="background:#14b8a6"></i> Vacance</span>
                <span><i style="background:#ef4444"></i> Evenement scolaire</span>
            </div>
            @if($canWrite)
                <button class="kaly quick-add" type="button" onclick="openEventModal(new Date().toISOString().slice(0,10))"><i class="fa fa-plus"></i> Ajouter aujourd'hui</button>
                <div class="hint">Clique sur une date pour creer un evenement. Les couleurs aident a lire rapidement les examens, reunions et vacances.</div>
            @else
                <div class="readonly-note"><i class="fa fa-eye"></i> Mode lecture : tu peux consulter les evenements academiques, sans ajouter ni modifier le calendrier.</div>
            @endif
        </aside>
    </div>
</div>
<footer>&copy; {{ date('Y') }} {{ $ecole->nom ?? 'Ecole' }}. Tous droits reserves.</footer>
</main>
<script>
function toggleSub(e){const s=e.nextElementSibling,a=e.querySelector('.arrow');s.style.display=s.style.display==='block'?'none':'block';a.classList.toggle('fa-chevron-down');a.classList.toggle('fa-chevron-up')}
function toggleSidebar(){document.getElementById('sidebar').classList.toggle('active');document.querySelector('main').classList.toggle('full-width');document.querySelector('header').classList.toggle('full-width')}
function toggleFullscreen(){const i=document.getElementById('fullscreen-icon');if(!document.fullscreenElement){document.documentElement.requestFullscreen();i.classList.replace('fa-expand','fa-compress')}else{document.exitFullscreen();i.classList.replace('fa-compress','fa-expand')}}
const typeColors={'rendez-vous':'#00c853','examen':'#3b82f6','session examen':'#8b5cf6','reunion':'#f59e0b','vacance':'#14b8a6','evenement scolaire':'#ef4444'};
const canWriteCalendar=@json($canWrite);
let calendar;
function eventColor(type){return typeColors[String(type||'').toLowerCase()]||'#00c853'}
function eventForm(dateStr){return `<div class="swal-calendar-form"><div class="full"><label>Titre</label><input id="titre" placeholder="Titre de l'evenement"></div><div><label>Type</label><select id="type">@foreach($eventTypes as $type)<option value="{{ $type }}">{{ $type }}</option>@endforeach</select></div><div><label>Debut</label><input id="date_debut" type="datetime-local" value="${dateStr}T09:00"></div><div><label>Fin</label><input id="date_fin" type="datetime-local" value="${dateStr}T17:00"></div><textarea id="description" placeholder="Description"></textarea></div>`}
function openEventModal(dateStr){if(!canWriteCalendar)return;Swal.fire({title:'Nouvel evenement',html:eventForm(dateStr),width:680,showCancelButton:true,confirmButtonText:'Ajouter',cancelButtonText:'Annuler',confirmButtonColor:'#00c853',focusConfirm:false,didOpen(){document.getElementById('titre')?.focus()},preConfirm(){const titre=document.getElementById('titre').value.trim();if(!titre){Swal.showValidationMessage('Le titre est obligatoire.');return false}const dateDebut=document.getElementById('date_debut').value;const dateFin=document.getElementById('date_fin').value;if(!dateDebut||!dateFin){Swal.showValidationMessage('Les dates sont obligatoires.');return false}return{titre,type:document.getElementById('type').value,date_debut:dateDebut,date_fin:dateFin,description:document.getElementById('description').value}}}).then(async r=>{if(!r.isConfirmed)return;try{const response=await fetch(@js(route('modules.calendrier.events.store')),{method:'POST',headers:{'Content-Type':'application/json','Accept':'application/json','X-CSRF-TOKEN':document.querySelector('meta[name=csrf-token]').content},body:JSON.stringify(r.value)});const data=await response.json();if(!response.ok){const message=data.message||Object.values(data.errors||{}).flat().join('\n')||'Impossible d ajouter cet evenement.';throw new Error(message)}if(data?.event){calendar.getEventById(String(data.event.id))?.remove();calendar.addEvent(data.event)}Swal.fire({icon:'success',title:'Evenement ajoute',timer:1800,showConfirmButton:false})}catch(error){Swal.fire('Erreur',error.message||'Impossible d ajouter cet evenement.','error')}})}
document.addEventListener('DOMContentLoaded',()=>{const a=document.querySelector('nav a.active'),s=a?.closest('.sub-menu');if(s){s.style.display='block';s.previousElementSibling?.querySelector('.arrow')?.classList.replace('fa-chevron-down','fa-chevron-up')}calendar=new FullCalendar.Calendar(document.getElementById('calendar'),{initialView:'dayGridMonth',locale:'fr',height:'auto',eventDisplay:'block',headerToolbar:{left:'prev,next today',center:'title',right:'dayGridMonth,timeGridWeek,listMonth'},buttonText:{today:"Aujourd'hui",month:'Mois',week:'Semaine',list:'Liste'},events:{url:@js(route('modules.calendrier.events')),failure(){Swal.fire('Erreur','Chargement du calendrier impossible.','error')}},eventDidMount(info){const color=eventColor(info.event.extendedProps.type);info.el.style.background=color;info.el.style.color=color==='#f59e0b'?'#111827':'#fff'},dateClick(info){if(canWriteCalendar)openEventModal(info.dateStr)},eventClick(info){Swal.fire({title:info.event.title,html:`<p><strong>Type :</strong> ${info.event.extendedProps.type||'-'}</p><p><strong>Debut :</strong> ${info.event.start?.toLocaleString('fr-FR')||'-'}</p><p>${info.event.extendedProps.description||''}</p>`,confirmButtonColor:'#00c853'})}});calendar.render();});
</script>
</body>
</html>
