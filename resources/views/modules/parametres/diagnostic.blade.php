<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Diagnostic systeme</title>
    <link rel="stylesheet" href="{{ asset('legacy/assets/fontawesome/css/all.min.css') }}">
    @include('modules.professeur.bulletin.partials.styles')
    @include('modules.parametres.partials.styles')
    <style>
        .diag-hero{display:flex;align-items:center;justify-content:space-between;gap:16px;flex-wrap:wrap;background:var(--surface);border:1px solid var(--border);border-radius:8px;padding:18px;margin-bottom:18px}
        .diag-hero>div:first-child,.diag-card{min-width:0}
        .diag-hero strong{display:block;color:var(--primary);font-size:1.25rem;overflow-wrap:anywhere}.diag-hero span{color:var(--text-sec);overflow-wrap:anywhere}
        .diag-status{padding:10px 13px;border-radius:999px;font-weight:900}.diag-status.ok{background:rgba(0,200,83,.14);color:#86efac}.diag-status.warn{background:rgba(245,158,11,.14);color:#facc15}
        .diag-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:13px}
        .diag-card{background:var(--surface);border:1px solid var(--border);border-radius:8px;padding:15px}
        .diag-card h3{margin:0 0 8px;color:var(--text);font-size:1rem;overflow-wrap:anywhere}.diag-card p{margin:0;color:var(--text-sec);line-height:1.45;overflow-wrap:anywhere;word-break:break-word}
        .diag-icon{width:36px;height:36px;border-radius:8px;display:grid;place-items:center;margin-bottom:10px}.diag-icon.ok{background:rgba(0,200,83,.14);color:var(--primary)}.diag-icon.bad{background:rgba(239,68,68,.14);color:#fca5a5}
        @media(max-width:760px){main{overflow-x:hidden}.diag-hero,.diag-card{padding:15px}.diag-status{width:100%;text-align:center}.diag-grid{grid-template-columns:1fr}}
    </style>
</head>
<body>
@include('modules.professeur.bulletin.partials.shell')
<header><div class="header-left"><button class="burger-menu" onclick="toggleSidebar()"><i class="fa fa-bars"></i></button><button id="fullscreen-btn" onclick="toggleFullscreen()"><i class="fa fa-expand"></i></button></div><h1><i class="fa fa-medkit"></i> Diagnostic systeme</h1></header>
<main>
    <section class="settings-panel">
        <div class="diag-hero">
            <div>
                <strong>Etat technique de Novaskol</strong>
                <span>Controle rapide avant distribution locale ou hebergement.</span>
            </div>
            <div class="diag-status {{ $allOk ? 'ok' : 'warn' }}">{{ $allOk ? 'Pret' : 'A verifier' }}</div>
        </div>
        <div class="diag-grid">
            @foreach($checks as $check)
                <article class="diag-card">
                    <div class="diag-icon {{ $check['ok'] ? 'ok' : 'bad' }}"><i class="fa {{ $check['ok'] ? 'fa-check' : 'fa-warning' }}"></i></div>
                    <h3>{{ $check['label'] }}</h3>
                    <p><strong>{{ $check['value'] }}</strong></p>
                    <p>{{ $check['note'] }}</p>
                </article>
            @endforeach
        </div>
    </section>
    <footer>&copy; {{ date('Y') }} {{ $ecole->nom ?? 'Ecole' }}. Tous droits reserves.</footer>
</main>
<script>function toggleSidebar(){document.getElementById('sidebar').classList.toggle('active')}function toggleSub(e){const n=e.nextElementSibling;if(n)n.style.display=n.style.display==='none'?'block':'none'}function toggleFullscreen(){document.fullscreenElement?document.exitFullscreen():document.documentElement.requestFullscreen()}</script>
</body>
</html>
