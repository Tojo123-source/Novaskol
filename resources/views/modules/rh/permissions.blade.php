<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Permissions - {{ $ecole->nom ?? 'Ecole' }}</title>
    <link rel="stylesheet" href="{{ asset('legacy/assets/fontawesome/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('legacy/fa/css/font-awesome.min.css') }}">
    @include('modules.professeur.bulletin.partials.styles')
    <style>
        .rh-panel { background:var(--card); border:1px solid var(--border); border-radius:8px; padding:18px; margin-bottom:18px; }
        .rh-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(240px,1fr)); gap:14px; align-items:end; }
        input,select { width:100%; padding:12px; background:var(--surface); color:var(--text); border:1px solid var(--border); border-radius:8px; }
        .rh-table-wrap { overflow:auto; -webkit-overflow-scrolling:touch; max-width:100%; border:1px solid var(--border); border-radius:8px; background:var(--surface); }
        .rh-table { width:100%; border-collapse:collapse; min-width:720px; }
        .rh-table th,.rh-table td { padding:12px; border-bottom:1px solid var(--border); text-align:left; }
        .rh-table th { background:#0f172a; color:var(--primary); font-size:.82rem; text-transform:uppercase; }
        .section-row td { color:var(--primary); font-weight:800; background:#111827; }
        .alert { padding:12px 14px; border-radius:8px; margin-bottom:14px; border:1px solid var(--border); }
        .alert.success { background:rgba(16,185,129,.12); color:#a7f3d0; }
        .alert.error { background:rgba(239,68,68,.12); color:#fecaca; }
        .muted { color:var(--text-sec); }
        @media (max-width:760px) {
            .rh-panel { padding:15px; }
            .rh-grid { grid-template-columns:1fr; }
            .rh-table-wrap { overflow:visible; border:0; background:transparent; }
            .rh-table { min-width:0; }
            .rh-table thead { display:none; }
            .rh-table,.rh-table tbody,.rh-table tr,.rh-table td { display:block; width:100%; }
            .rh-table tr { margin-bottom:14px; border:1px solid var(--border); border-radius:10px; overflow:hidden; background:var(--card); }
            .rh-table td { position:relative; padding:12px 14px 12px 44%; min-height:50px; }
            .rh-table td::before { content:attr(data-label); position:absolute; left:14px; top:12px; width:calc(44% - 20px); color:var(--text-sec); font-weight:800; font-size:.76rem; text-transform:uppercase; line-height:1.25; }
            .section-row td { padding:12px 14px; }
            .section-row td::before { display:none; }
        }
    </style>
</head>
<body>
@include('modules.professeur.bulletin.partials.shell')
<header>
    <div class="header-left">
        <button class="burger-menu" onclick="toggleSidebar()"><i class="fa fa-bars"></i></button>
        <button id="fullscreen-btn" onclick="toggleFullscreen()"><i class="fa fa-expand"></i></button>
    </div>
    <h1>Gestion des permissions</h1>
</header>
<main>
    <section class="rh-panel">
        <form method="GET" class="rh-grid">
            <div>
                <label>Utilisateur</label>
                <select name="utilisateur_id" required>
                    <option value="">Choisir</option>
                    @foreach ($utilisateurs as $utilisateur)
                        <option value="{{ $utilisateur->id }}" @selected($selectedUser === (int) $utilisateur->id)>{{ $utilisateur->nom }} - {{ $utilisateur->email }} ({{ $utilisateur->role }})</option>
                    @endforeach
                </select>
            </div>
            <button class="kaly" type="submit"><i class="fa fa-search"></i> Charger</button>
        </form>
    </section>

    @if ($selectedUser)
        <section class="rh-panel">
            <form method="POST" action="{{ route('modules.permissions.update') }}">
                @csrf
                <input type="hidden" name="utilisateur_id" value="{{ $selectedUser }}">
                <input type="hidden" name="role" value="{{ $selectedRole }}">
                <div class="rh-table-wrap">
                    <table class="rh-table">
                        <thead><tr><th>Module</th><th>Acces</th></tr></thead>
                        <tbody>
                        @foreach ($moduleList as $key => $info)
                            @if (! empty($info['section']))
                                <tr class="section-row"><td colspan="2">{{ preg_replace('/^\s*\|\s*--\s*/', '', $info['label']) }}</td></tr>
                            @else
                                <tr>
                                    <td data-label="Module"><i class="fa {{ $info['icon'] ?? 'fa-circle' }}"></i> {{ $info['label'] ?? $key }}</td>
                                    <td data-label="Acces">
                                        <select name="permissions[{{ $key }}]">
                                            @foreach ($accessLevels as $value => $label)
                                                <option value="{{ $value }}" @selected(($permissionMap[$key] ?? 'aucun') === $value)>{{ $label }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <button class="kaly" type="submit"><i class="fa fa-save"></i> Enregistrer les permissions</button>
            </form>
        </section>
    @else
        <section class="rh-panel"><p class="muted">Choisis un utilisateur pour modifier ses permissions.</p></section>
    @endif
    <footer>&copy; {{ date('Y') }} {{ $ecole->nom ?? 'Ecole' }}. Tous droits reserves.</footer>
</main>
<script>
function toggleSidebar(){document.getElementById('sidebar').classList.toggle('active');}
function toggleSub(el){const n=el.nextElementSibling;if(n)n.style.display=n.style.display==='none'?'block':'none';}
function toggleFullscreen(){if(!document.fullscreenElement){document.documentElement.requestFullscreen();}else{document.exitFullscreen();}}
</script>
</body>
</html>
