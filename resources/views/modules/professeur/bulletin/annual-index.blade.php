<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Bulletin annuel - {{ $ecole->nom ?? 'Ecole' }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{ asset('legacy/assets/fontawesome/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('legacy/assets/sweetalert2/sweetalert2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('legacy/fa/css/font-awesome.min.css') }}">
    <script src="{{ asset('legacy/js/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('legacy/assets/sweetalert2/sweetalert2.min.js') }}"></script>
    @include('modules.professeur.bulletin.partials.styles')
</head>
<body>
@include('modules.professeur.bulletin.partials.shell')
<header>
    <div class="header-left">
        <button class="burger-menu" onclick="toggleSidebar()"><i class="fa fa-bars"></i></button>
        <button id="fullscreen-btn" onclick="toggleFullscreen()"><i id="fullscreen-icon" class="fa fa-expand"></i></button>
    </div>
    <h1><i class="fa fa-calendar-check"></i> Bulletin annuel</h1>
</header>
<main>
    <div class="form-container">
        <div class="filters-grid">
            <div>
                <label for="annee_scolaire">Annee scolaire</label>
                <select id="annee_scolaire" name="annee_scolaire">
                    @foreach ($annees as $annee)
                        <option value="{{ $annee }}">{{ $annee }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="search">Rechercher un eleve</label>
                <input type="text" id="search" placeholder="Nom, prenom ou matricule">
            </div>
        </div>
        <div class="actions">
            <button class="kaly" id="search-student"><i class="fa fa-search"></i> Rechercher</button>
            <a class="action-btn" href="{{ route('modules.bulletin') }}"><i class="fa fa-arrow-left"></i> Retour bulletin</a>
        </div>
        @if($isParent ?? false)
            <div class="readonly-note"><i class="fa fa-lock"></i> Espace parent : la recherche affiche uniquement vos enfants rattaches.</div>
        @endif
        <div id="results" class="results" style="display:none;"></div>
    </div>
    <footer>&copy; {{ date('Y') }} {{ $ecole->nom ?? 'Ecole' }}. Tous droits reserves.</footer>
</main>
<script>
function toggleSub(el){const sub=el.nextElementSibling;const arrow=el.querySelector('.arrow');sub.style.display=sub.style.display==='block'?'none':'block';arrow.classList.toggle('fa-chevron-down');arrow.classList.toggle('fa-chevron-up');}
function toggleSidebar(){document.getElementById('sidebar').classList.toggle('active');document.querySelector('main').classList.toggle('full-width');document.querySelector('header').classList.toggle('full-width');}
function toggleFullscreen(){const icon=document.getElementById('fullscreen-icon');if(!document.fullscreenElement){document.documentElement.requestFullscreen();icon.classList.replace('fa-expand','fa-compress');}else{document.exitFullscreen();icon.classList.replace('fa-compress','fa-expand');}}
document.addEventListener('DOMContentLoaded',()=>{const active=document.querySelector('nav a.active');if(active){const sub=active.closest('.sub-menu');if(sub){sub.style.display='block';const arrow=sub.previousElementSibling?.querySelector('.arrow');if(arrow){arrow.classList.replace('fa-chevron-down','fa-chevron-up');}}}});
$('#search, #search-student').on('input click', function(){
    const query = $('#search').val().trim();
    const annee = $('#annee_scolaire').val();
    if (query.length < 2 || !annee) { $('#results').hide().empty(); return; }
    $.get('{{ route('modules.bulletin.search') }}', {q: query, annee_scolaire: annee}, function(data) {
        const box = $('#results').empty().show();
        if (!data.length) { box.html('<div class="result-item">Aucun eleve trouve.</div>'); return; }
        data.forEach(function(eleve) {
            $('<div class="result-item"></div>')
                .html(`<strong>${eleve.prenom} ${eleve.nom}</strong><br><small>${eleve.classe || ''} - ${eleve.matricule || ''}</small>`)
                .on('click', function(){ window.location.href = `{{ route('modules.bulletin.annual.student') }}?eleve_id=${eleve.id}&annee_scolaire=${annee}`; })
                .appendTo(box);
        });
    });
});
</script>
</body>
</html>
