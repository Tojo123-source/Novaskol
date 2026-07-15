<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Communication - {{ $ecole->nom ?? 'Ecole' }}</title>
    <link rel="stylesheet" href="{{ asset('legacy/assets/fontawesome/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('legacy/fa/css/font-awesome.min.css') }}">
    @include('modules.professeur.bulletin.partials.styles')
    <style>
        .communication-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(300px,1fr)); gap:22px; max-width:1050px; margin:20px auto; }
        .communication-card { background:var(--card); border:1px solid var(--border); border-radius:8px; padding:28px; text-decoration:none; color:var(--text); box-shadow:0 8px 24px rgba(0,0,0,.28); transition:.22s ease; }
        .communication-card:hover { border-color:var(--primary); transform:translateY(-4px); box-shadow:0 14px 34px rgba(0,0,0,.36); }
        .communication-card i { color:var(--primary); font-size:3rem; margin-bottom:18px; }
        .communication-card h2 { font-size:1.35rem; margin-bottom:10px; }
        .communication-card p { color:var(--text-sec); line-height:1.6; min-height:78px; }
        .communication-card span { display:inline-flex; align-items:center; gap:8px; margin-top:20px; padding:11px 18px; background:var(--primary); color:white; border-radius:8px; font-weight:800; }
        @media(max-width:760px){.communication-grid{grid-template-columns:1fr;gap:14px}.communication-card{padding:20px}.communication-card i{font-size:2rem}.communication-card h2{font-size:1.1rem}.communication-card p{font-size:.9rem;min-height:auto}}
    </style>
</head>
<body>
@include('modules.professeur.bulletin.partials.shell')
<header>
    <div class="header-left">
        <button class="burger-menu" onclick="toggleSidebar()"><i class="fa fa-bars"></i></button>
        <button id="fullscreen-btn" onclick="toggleFullscreen()"><i class="fa fa-expand"></i></button>
    </div>
    <div class="header-center">Communication</div>
</header>
<main>
    <section class="communication-grid">
        <a class="communication-card" href="{{ route('modules.chat-prive') }}">
            <i class="fa fa-user"></i>
            <h2>Chat prive</h2>
            <p>Discuter directement avec un utilisateur: administrateur, enseignant, parent ou membre du staff, avec envoi de texte et fichiers.</p>
            <span><i class="fa fa-comments"></i> Ouvrir</span>
        </a>
        <a class="communication-card" href="{{ route('modules.chat-groupe') }}">
            <i class="fa fa-users"></i>
            <h2>Chat groupe</h2>
            <p>Creer des groupes, ajouter des membres, suivre les messages non lus et partager des documents dans une discussion commune.</p>
            <span><i class="fa fa-commenting"></i> Ouvrir</span>
        </a>
    </section>
    <footer>&copy; {{ date('Y') }} {{ $ecole->nom ?? 'Ecole' }}. Tous droits reserves.</footer>
</main>
<script>
function toggleSidebar(){document.getElementById('sidebar').classList.toggle('active');}
function toggleSub(el){const n=el.nextElementSibling;if(n)n.style.display=n.style.display==='none'?'block':'none';}
function toggleFullscreen(){if(!document.fullscreenElement){document.documentElement.requestFullscreen();}else{document.exitFullscreen();}}
</script>
</body>
</html>
