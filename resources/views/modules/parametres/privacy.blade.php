<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Politique de confidentialite - {{ $ecole->nom ?? 'Novaskol' }}</title>
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
<header><div class="header-left"><button class="burger-menu" onclick="toggleSidebar()"><i class="fa fa-bars"></i></button><button id="fullscreen-btn" onclick="toggleFullscreen()"><i class="fa fa-expand"></i></button></div><h1><i class="fa fa-shield"></i> Politique de confidentialite</h1></header>
<main>
    <section class="legal-wrap">
        <article class="legal-hero">
            <h2>Protection des donnees Novaskol</h2>
            <p>Novaskol traite les informations scolaires, administratives, comptables et de communication de l'etablissement. Cette politique explique comment l'ecole doit proteger ces donnees, limiter les acces et organiser les sauvegardes.</p>
        </article>
        <article class="legal-card">
            <h3>1. Donnees concernees</h3>
            <ul>
                <li>Informations de l'ecole, des eleves, des parents, des enseignants et du staff.</li>
                <li>Notes, bulletins, presences, paiements, factures, recus et historiques de discussion.</li>
                <li>Comptes utilisateurs, permissions, journaux de travail et sauvegardes.</li>
            </ul>
        </article>
        <article class="legal-card">
            <h3>2. Regles d'acces</h3>
            <ul>
                <li>Chaque utilisateur doit disposer d'un compte personnel protege par mot de passe.</li>
                <li>Les permissions doivent etre limitees au strict besoin de travail.</li>
                <li>Les rapports sensibles et les donnees de paie doivent etre reserves aux comptes autorises.</li>
            </ul>
        </article>
        <article class="legal-card">
            <h3>3. Sauvegardes et restauration</h3>
            <ul>
                <li>Une sauvegarde reguliere doit etre realisee par l'ecole et copiee hors du poste principal.</li>
                <li>Les fichiers de sauvegarde doivent etre conserves dans un emplacement sur.</li>
                <li>En cas de restauration, l'ecole doit verifier la date de la base avant de l'appliquer.</li>
            </ul>
        </article>
        <article class="legal-card">
            <h3>4. Reseau local et appareils</h3>
            <ul>
                <li>Seuls les appareils connectes au reseau autorise de l'ecole doivent utiliser Novaskol.</li>
                <li>L'appareil principal doit rester protege, stable et reserve aux responsables.</li>
                <li>En cas d'acces multi-appareils, l'ecole doit surveiller qui se connecte au meme Wi-Fi.</li>
            </ul>
        </article>
        <article class="legal-card">
            <h3>5. Bonnes pratiques</h3>
            <ul>
                <li>Changer les mots de passe par defaut apres installation.</li>
                <li>Fermer la session lorsqu'un poste n'est plus surveille.</li>
                <li>Ne pas partager les exports, sauvegardes ou captures d'ecran en dehors de l'ecole sans autorisation.</li>
            </ul>
        </article>
    </section>
    <footer>&copy; {{ date('Y') }} {{ $ecole->nom ?? 'Ecole' }}</footer>
</main>
<script>function toggleSidebar(){document.getElementById('sidebar').classList.toggle('active')}function toggleSub(e){const n=e.nextElementSibling;if(n)n.style.display=n.style.display==='none'?'block':'none'}function toggleFullscreen(){document.fullscreenElement?document.exitFullscreen():document.documentElement.requestFullscreen()}</script>
</body>
</html>
