<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>A propos Novaskol</title>
    <link rel="stylesheet" href="{{ asset('legacy/assets/fontawesome/css/all.min.css') }}">
    @include('modules.professeur.bulletin.partials.styles')
    @include('modules.parametres.partials.styles')
    <style>
        .about-hero{display:grid;grid-template-columns:minmax(0,1fr) auto;gap:18px;align-items:center;background:linear-gradient(135deg,rgba(0,200,83,.16),rgba(14,165,233,.1)),var(--surface);border:1px solid var(--border);border-radius:8px;padding:20px;margin-bottom:18px}
        .about-hero h2{margin:0 0 8px;color:var(--primary);font-size:1.7rem}.about-hero p{margin:0;color:var(--text-sec);line-height:1.55}
        .version-badge{display:grid;place-items:center;min-width:128px;min-height:92px;border-radius:8px;background:rgba(0,200,83,.13);border:1px solid rgba(0,200,83,.25);color:var(--text)}
        .version-badge strong{font-size:1.7rem;color:var(--primary)}.version-badge span{color:var(--text-sec);font-weight:800}
        .about-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(250px,1fr));gap:14px}
        .about-card{background:var(--surface);border:1px solid var(--border);border-radius:8px;padding:16px}
        .about-card i{width:38px;height:38px;display:grid;place-items:center;border-radius:8px;background:rgba(0,200,83,.13);color:var(--primary);margin-bottom:10px}
        .about-card h3{margin:0 0 8px;color:var(--text);font-size:1rem}.about-card p{margin:0;color:var(--text-sec);line-height:1.45;word-break:break-word}
        .about-status{display:inline-flex;align-items:center;gap:8px;padding:8px 11px;border-radius:999px;font-weight:900;margin-top:10px}
        .about-status.ok{background:rgba(0,200,83,.14);color:#86efac}.about-status.warn{background:rgba(245,158,11,.14);color:#facc15}.about-status.bad{background:rgba(239,68,68,.14);color:#fca5a5}
        .signature{margin-top:16px;padding:16px;border-radius:8px;border:1px solid var(--border);background:var(--surface)}
        .signature strong{color:var(--primary)}.signature p{margin:6px 0 0;color:var(--text-sec)}
        @media(max-width:720px){.about-hero{grid-template-columns:1fr}.version-badge{place-items:start;align-content:center;padding:14px}}
    </style>
</head>
<body>
@include('modules.professeur.bulletin.partials.shell')
<header>
    <div class="header-left">
        <button class="burger-menu" onclick="toggleSidebar()"><i class="fa fa-bars"></i></button>
        <button id="fullscreen-btn" onclick="toggleFullscreen()"><i class="fa fa-expand"></i></button>
    </div>
    <h1><i class="fa fa-info-circle"></i> A propos Novaskol</h1>
</header>
<main>
    <section class="settings-panel">
        <div class="about-hero">
            <div>
                <h2>Novaskol, gestion scolaire moderne</h2>
                <p>Cette page resume l'etat de l'installation, la version, le mode de fonctionnement, les sauvegardes et les informations utiles avant livraison a une ecole.</p>
            </div>
            <div class="version-badge">
                <span>Version</span>
                <strong>{{ $version }}</strong>
            </div>
        </div>

        <div class="about-grid">
            <article class="about-card">
                <i class="fa fa-school"></i>
                <h3>Etablissement</h3>
                <p><strong>{{ $params['nom_ecole'] ?? ($ecole->nom ?? 'Ecole') }}</strong></p>
                <p>{{ $params['adresse_ecole'] ?? 'Adresse non renseignee' }}</p>
                <p>{{ $params['telephone_ecole'] ?? 'Telephone non renseigne' }}</p>
            </article>
            <article class="about-card">
                <i class="fa fa-toggle-on"></i>
                <h3>Mode installation</h3>
                <p>{{ $modeInstallation === 'demo' ? 'Demonstration avec donnees fictives' : 'Reel / production' }}</p>
                <span class="about-status {{ $modeInstallation === 'demo' ? 'warn' : 'ok' }}"><i class="fa {{ $modeInstallation === 'demo' ? 'fa-flask' : 'fa-check' }}"></i>{{ $modeInstallation === 'demo' ? 'Mode demo' : 'Mode reel' }}</span>
            </article>
            <article class="about-card">
                <i class="fa fa-server"></i>
                <h3>Environnement</h3>
                <p>URL : {{ $appUrl }}</p>
                <p>Environnement : {{ $appEnv }}</p>
                <span class="about-status {{ $debug ? 'warn' : 'ok' }}"><i class="fa {{ $debug ? 'fa-warning' : 'fa-lock' }}"></i>{{ $debug ? 'Debug actif' : 'Debug desactive' }}</span>
            </article>
            <article class="about-card">
                <i class="fa fa-database"></i>
                <h3>Base de donnees</h3>
                <p>{{ $databaseName }}</p>
                <p>Chaque ecole doit garder sa propre base.</p>
            </article>
            <article class="about-card">
                <i class="fa fa-folder-open"></i>
                <h3>Chemin local</h3>
                <p>{{ $basePath }}</p>
            </article>
            <article class="about-card">
                <i class="fa fa-save"></i>
                <h3>Sauvegardes</h3>
                <p>{{ $backupCount }} sauvegarde(s) disponible(s).</p>
                @if($lastBackup)
                    <p>Derniere : {{ $lastBackup['name'] }} - {{ $lastBackup['date'] }} - {{ $lastBackup['size'] }}</p>
                @else
                    <p>Aucune sauvegarde SQL trouvee.</p>
                @endif
            </article>
            <article class="about-card">
                <i class="fa fa-archive"></i>
                <h3>Distribution</h3>
                <p>Dumps, guide simple et fichiers de distribution.</p>
                <span class="about-status {{ $distributionReady ? 'ok' : 'bad' }}"><i class="fa {{ $distributionReady ? 'fa-check' : 'fa-warning' }}"></i>{{ $distributionReady ? 'Pret' : 'A completer' }}</span>
            </article>
            <article class="about-card">
                <i class="fa fa-heart"></i>
                <h3>Signature</h3>
                <p>Novaskol est concu pour les ecoles qui veulent gerer leurs donnees localement ou en ligne, sans melanger les etablissements.</p>
            </article>
        </div>

        <div class="signature">
            <strong>Contact / signature projet</strong>
            <p>Novaskol - Projet de gestion scolaire Laravel, pret pour installation locale, hebergement et application Windows.</p>
        </div>
    </section>
    <footer>&copy; {{ date('Y') }} {{ $ecole->nom ?? 'Ecole' }}. Tous droits reserves.</footer>
</main>
<script>function toggleSidebar(){document.getElementById('sidebar').classList.toggle('active')}function toggleSub(e){const n=e.nextElementSibling;if(n)n.style.display=n.style.display==='none'?'block':'none'}function toggleFullscreen(){document.fullscreenElement?document.exitFullscreen():document.documentElement.requestFullscreen()}</script>
</body>
</html>
