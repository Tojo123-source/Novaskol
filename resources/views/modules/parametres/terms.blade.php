<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Conditions d'utilisation - {{ $ecole->nom ?? 'Novaskol' }}</title>
    <link rel="stylesheet" href="{{ asset('legacy/assets/fontawesome/css/all.min.css') }}">
    @include('modules.professeur.bulletin.partials.styles')
    @include('modules.parametres.partials.styles')
    <style>
        .legal-wrap{display:grid;gap:18px}
        .legal-hero,.legal-card{background:var(--card);border:1px solid var(--border);border-radius:8px;padding:22px}
        .legal-hero h2{margin:0 0 8px;color:var(--primary)}
        .legal-hero p,.legal-card p,.legal-card li{line-height:1.7;color:var(--text)}
        .legal-card h3{margin:0 0 12px;color:var(--primary)}
        .legal-card ul{margin:0;padding-left:20px}
    </style>
</head>
<body>
@include('modules.professeur.bulletin.partials.shell')
<header><div class="header-left"><button class="burger-menu" onclick="toggleSidebar()"><i class="fa fa-bars"></i></button><button id="fullscreen-btn" onclick="toggleFullscreen()"><i class="fa fa-expand"></i></button></div><div class="header-center"><i class="fa fa-file-text"></i> Conditions d'utilisation</div></header>
<main>
    <section class="legal-wrap">
        <article class="legal-hero">
            <h2>Cadre d'utilisation de Novaskol</h2>
            <p>Ces conditions encadrent l'utilisation de Novaskol dans l'etablissement. Elles aident la direction, les responsables, le staff, les enseignants et les parents a utiliser le systeme de maniere claire, ordonnee et responsable.</p>
        </article>
        <article class="legal-card">
            <h3>1. Usage reserve a l'etablissement</h3>
            <ul>
                <li>Novaskol doit etre utilise pour la gestion scolaire, administrative, pedagogique et comptable de l'ecole.</li>
                <li>L'etablissement reste responsable des donnees saisies et des acces accordes.</li>
            </ul>
        </article>
        <article class="legal-card">
            <h3>2. Comptes et responsabilites</h3>
            <ul>
                <li>Chaque compte utilisateur doit correspondre a une personne identifiee.</li>
                <li>Le partage d'un meme compte entre plusieurs personnes est deconseille.</li>
                <li>Les comptes sensibles doivent etre geres par des responsables autorises.</li>
            </ul>
        </article>
        <article class="legal-card">
            <h3>3. Respect des donnees</h3>
            <ul>
                <li>Les utilisateurs ne doivent ni modifier ni supprimer des informations sans autorisation.</li>
                <li>Les parents, enseignants et staff doivent respecter les limites de leurs permissions.</li>
                <li>Les documents imprimes ou exportes doivent etre verifies avant diffusion.</li>
            </ul>
        </article>
        <article class="legal-card">
            <h3>4. Continuité du service</h3>
            <ul>
                <li>L'ecole doit realiser des sauvegardes regulieres.</li>
                <li>Avant toute restauration, mise a jour ou modification importante, une sauvegarde recente est obligatoire.</li>
                <li>Le poste principal et les appareils relies doivent etre proteges contre les acces non autorises.</li>
            </ul>
        </article>
        <article class="legal-card">
            <h3>5. Bon fonctionnement</h3>
            <ul>
                <li>Le personnel doit suivre le guide d'utilisation avant d'utiliser une fonction sensible.</li>
                <li>Les anomalies doivent etre signalees avant d'essayer des manipulations risquant d'endommager la base.</li>
                <li>Les etablissements sont encourages a tenir une routine de verification du diagnostic systeme.</li>
            </ul>
        </article>
    </section>
    <footer>&copy; {{ date('Y') }} {{ $ecole->nom ?? 'Ecole' }}</footer>
</main>
<script>function toggleSidebar(){document.getElementById('sidebar').classList.toggle('active')}function toggleSub(e){const n=e.nextElementSibling;if(n)n.style.display=n.style.display==='none'?'block':'none'}function toggleFullscreen(){document.fullscreenElement?document.exitFullscreen():document.documentElement.requestFullscreen()}</script>
</body>
</html>
