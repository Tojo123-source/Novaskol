<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Notifications</title>
<link rel="stylesheet" href="{{ asset('legacy/assets/fontawesome/css/all.min.css') }}">
<link rel="stylesheet" href="{{ asset('legacy/fa/css/font-awesome.min.css') }}">
<link rel="stylesheet" href="{{ asset('legacy/assets/sweetalert2/sweetalert2.min.css') }}">
<script src="{{ asset('legacy/assets/sweetalert2/sweetalert2.min.js') }}"></script>
@include('modules.professeur.bulletin.partials.styles')
<style>
.notif-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:14px}
.notif-card{border:1px solid var(--border);border-radius:8px;padding:14px;background:var(--surface)}
.notif-card small{color:var(--text-sec)}
.notif-filters{display:grid;grid-template-columns:repeat(auto-fit,minmax(210px,1fr));gap:12px;margin:20px 0}
.notif-filters input,.notif-filters select{width:100%;padding:12px;border-radius:8px;border:1px solid var(--border);background:var(--surface);color:var(--text)}
.notif-actions{display:flex;align-items:end;gap:8px;flex-wrap:wrap}
.danger-action{background:#fee2e2!important;color:#991b1b!important;border-color:#fecaca!important}
[data-theme="dark"] .danger-action{background:rgba(239,68,68,.18)!important;color:#fecaca!important;border-color:rgba(239,68,68,.35)!important}
.empty-notif{text-align:center;color:var(--text-sec);padding:22px;border:1px dashed var(--border);border-radius:8px}
@media(max-width:560px){.notif-actions .kaly{width:100%;justify-content:center}.notif-grid{grid-template-columns:1fr}}
</style>
</head>
<body>
@include('modules.professeur.bulletin.partials.shell', ['activeModule'=>$activeModule])
<header>
    <div class="header-left">
        <button class="burger-menu" onclick="toggleSidebar()"><i class="fa fa-bars"></i></button>
        <button id="fullscreen-btn" onclick="toggleFullscreen()"><i id="fullscreen-icon" class="fa fa-expand"></i></button>
    </div>
    <div class="header-center"><i class="fa fa-bell"></i> Notifications</div>
</header>
<main>
<div class="form-container">
    <form method="POST" action="{{ route('modules.notifications.store') }}">
        @csrf
        <div class="filters-grid">
            <div><label>Type</label><input type="text" name="type" required></div>
            <div><label>Destinataire ID</label><input type="number" name="destinataire_id"></div>
            <div><label>Message</label><input type="text" name="message" required></div>
        </div>
        <button class="kaly"><i class="fa fa-plus"></i> Ajouter</button>
    </form>

    <form method="GET" action="{{ route('modules.notifications') }}" class="notif-filters">
        <div><label>Jour</label><input type="date" name="date" value="{{ $selectedDate }}"></div>
        <div>
            <label>Type</label>
            <select name="type">
                <option value="">Tous</option>
                @foreach($types as $type)
                    <option value="{{ $type }}" @selected($selectedType===$type)>{{ $type }}</option>
                @endforeach
            </select>
        </div>
        <div class="notif-actions">
            <button class="kaly"><i class="fa fa-filter"></i> Filtrer</button>
            <a class="kaly" href="{{ route('modules.notifications', ['date' => now()->toDateString()]) }}">Aujourd'hui</a>
            @if($notifications->count() > 0)
                <button class="kaly danger-action" type="button" onclick="deleteAllNotifs()"><i class="fa fa-trash"></i> Supprimer tout</button>
            @endif
        </div>
    </form>

    <div class="notif-grid">
        @forelse($notifications as $notif)
            <div class="notif-card">
                <strong>{{ $notif->type }}</strong>
                <p>{{ $notif->message }}</p>
                <small>{{ $notif->date_creation }} - {{ $notif->statut }}</small>
                <div><button class="kaly" onclick="deleteNotif({{ $notif->id }})"><i class="fa fa-trash"></i> Supprimer</button></div>
            </div>
        @empty
            <div class="empty-notif">Aucune notification pour ce filtre.</div>
        @endforelse
    </div>
</div>
<footer>&copy; {{ date('Y') }} {{ $ecole->nom ?? 'Ecole' }}. Tous droits reserves.</footer>
</main>
<script>
function toggleSub(e){const s=e.nextElementSibling,a=e.querySelector('.arrow');s.style.display=s.style.display==='block'?'none':'block';a.classList.toggle('fa-chevron-down');a.classList.toggle('fa-chevron-up')}
function toggleSidebar(){document.getElementById('sidebar').classList.toggle('active');document.querySelector('main').classList.toggle('full-width');document.querySelector('header').classList.toggle('full-width')}
function toggleFullscreen(){const i=document.getElementById('fullscreen-icon');if(!document.fullscreenElement){document.documentElement.requestFullscreen();i.classList.replace('fa-expand','fa-compress')}else{document.exitFullscreen();i.classList.replace('fa-compress','fa-expand')}}
function csrfToken(){return document.querySelector('meta[name=csrf-token]').content}
function currentFilterQuery(){
    const params = new URLSearchParams();
    const date = document.querySelector('.notif-filters [name="date"]')?.value || '';
    const type = document.querySelector('.notif-filters [name="type"]')?.value || '';
    if (date) params.set('date', date);
    if (type) params.set('type', type);
    return params.toString();
}
function deleteNotif(id){
    Swal.fire({title:'Supprimer ?',text:'Cette notification sera retiree definitivement.',icon:'warning',showCancelButton:true,confirmButtonText:'Oui',cancelButtonText:'Annuler',confirmButtonColor:'#ef4444'})
        .then(r=>{if(r.isConfirmed)fetch(`{{ url('/notifications') }}/${id}`,{method:'DELETE',headers:{'X-CSRF-TOKEN':csrfToken(),'Accept':'application/json'}}).then(()=>location.reload())})
}
function deleteAllNotifs(){
    Swal.fire({
        title:'Supprimer tout ?',
        text:'Toutes les notifications affichees par le filtre actuel seront supprimees.',
        icon:'warning',
        showCancelButton:true,
        confirmButtonText:'Supprimer tout',
        cancelButtonText:'Annuler',
        confirmButtonColor:'#ef4444'
    }).then(async r=>{
        if(!r.isConfirmed)return;
        const query = currentFilterQuery();
        const response = await fetch(`{{ route('modules.notifications.delete-all') }}${query ? `?${query}` : ''}`, {
            method:'DELETE',
            headers:{'X-CSRF-TOKEN':csrfToken(),'Accept':'application/json'}
        });
        const data = await response.json().catch(()=>({deleted:0}));
        await Swal.fire({icon:'success',title:'Notifications supprimees',text:`${data.deleted || 0} notification(s) supprimee(s).`,timer:1600,showConfirmButton:false});
        location.reload();
    });
}
document.addEventListener('DOMContentLoaded',()=>{const a=document.querySelector('nav a.active'),s=a?.closest('.sub-menu');if(s){s.style.display='block';s.previousElementSibling?.querySelector('.arrow')?.classList.replace('fa-chevron-down','fa-chevron-up')}})
</script>
</body>
</html>
