<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Acces refuse - Novaskol</title>
    <link rel="stylesheet" href="{{ asset('legacy/assets/fontawesome/css/all.min.css') }}">
    <script>
        (function(){
            const theme = localStorage.getItem('novaskol-theme') || localStorage.getItem('theme');
            document.documentElement.classList.toggle('light', theme === 'light');
        })();
    </script>
    <style>
        :root{--bg:#0a0a0a;--card:#14141a;--surface:#111827;--primary:#00c853;--text:#e5e7eb;--text-sec:#9ca3af;--border:#1f1f2e;--danger:#ef4444}
        :root.light{--bg:#f3f6fb;--card:#ffffff;--surface:#f8fafc;--primary:#059669;--text:#111827;--text-sec:#475569;--border:#d8e0ea;--danger:#dc2626}
        *{box-sizing:border-box}
        body{margin:0;min-height:100vh;display:grid;place-items:center;background:var(--bg);color:var(--text);font-family:system-ui,-apple-system,BlinkMacSystemFont,"Segoe UI",sans-serif;padding:22px}
        .panel{width:min(620px,100%);background:var(--card);border:1px solid var(--border);border-radius:10px;padding:32px;box-shadow:0 22px 70px rgba(0,0,0,.34);text-align:center}
        .icon{width:74px;height:74px;border-radius:999px;display:grid;place-items:center;margin:0 auto 18px;background:rgba(239,68,68,.12);color:var(--danger);font-size:2rem}
        h1{margin:0 0 10px;color:var(--primary);font-size:1.8rem}
        p{margin:0 auto 22px;color:var(--text-sec);line-height:1.55;max-width:480px}
        .actions{display:flex;flex-wrap:wrap;gap:12px;justify-content:center}
        a,button{border:0;border-radius:8px;padding:12px 16px;font-weight:800;cursor:pointer;text-decoration:none;font-size:.96rem}
        .primary{background:var(--primary);color:#062b1d}
        .secondary{background:var(--surface);color:var(--text);border:1px solid var(--border)}
        .danger{background:var(--danger);color:white}
        small{display:block;margin-top:18px;color:var(--text-sec)}
    </style>
</head>
<body>
    <main class="panel">
        <div class="icon"><i class="fa fa-lock"></i></div>
        <h1>Acces non autorise</h1>
        <p>
            Votre compte n'a pas l'autorisation d'ouvrir cette page.
            Vous pouvez revenir a votre espace ou demarrer une nouvelle session.
        </p>
        <div class="actions">
            @if(session()->has('utilisateur'))
                <a class="secondary" href="{{ route('role.dashboard') }}"><i class="fa fa-th-large"></i> Mon espace</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="danger" type="submit"><i class="fa fa-refresh"></i> Nouvelle session</button>
                </form>
            @endif
            <a class="primary" href="{{ route('login') }}"><i class="fa fa-sign-in"></i> Connexion</a>
        </div>
        <small>Erreur 403 - Permission insuffisante</small>
    </main>
</body>
</html>
