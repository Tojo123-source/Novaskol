<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des Matieres - {{ $ecole->nom ?? 'Novaskol' }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{ asset('legacy/assets/fontawesome/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('legacy/assets/sweetalert2/sweetalert2.min.css') }}">
    <script src="{{ asset('legacy/js/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('legacy/assets/sweetalert2/sweetalert2.min.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('legacy/fa/css/font-awesome.min.css') }}">
<style>
    :root {
        --bg: #0a0a0a;
        --card: #14141a;
        --surface: #111827;
        --primary: #00c853;
        --primary-dark: #00a843;
        --primary-glow: rgba(0,200,83,0.18);
        --text: #e5e7eb;
        --text-sec: #9ca3af;
        --border: #1f1f2e;
        --scroll-track: #0f0f11;
        --scroll-thumb: #2a2a3a;
        --scroll-thumb-hover: #00c853;
        --glow: rgba(0,200,83,0.18);
        --danger: #ef4444;
        --success: #10b981;
        --sidebar-width: 240px;
    }
    *::-webkit-scrollbar { width: 6px; }
    *::-webkit-scrollbar-track { background: var(--scroll-track); border-radius: 10px; }
    *::-webkit-scrollbar-thumb { background: var(--scroll-thumb); border-radius: 10px; border: 2px solid var(--scroll-track); }
    *::-webkit-scrollbar-thumb:hover { background: var(--scroll-thumb-hover); }
    * { scrollbar-width: thin; scrollbar-color: var(--scroll-thumb) var(--scroll-track); }
    * { margin:0; padding:0; box-sizing:border-box; }
    body {
        font-family: system-ui, sans-serif;
        background: var(--bg);
        color: var(--text);
        min-height: 100vh;
        transition: background 0.4s ease, color 0.4s ease;
    }
    nav {
        width: var(--sidebar-width);
        background: var(--card);
        backdrop-filter: blur(12px);
        position: fixed;
        left: 0; top: 0; bottom: 0;
        z-index: 1000;
        overflow-y: auto;
        border-right: 1px solid var(--border);
        transition: transform 0.28s ease, background 0.4s ease;
    }
    nav.hidden { transform: translateX(-240px); }
    nav.active { transform: translateX(0); }
    nav .logo { text-align:center; padding: 30px 0 20px; }
    nav .logo img { max-width:72px; border-radius:12px; box-shadow: 0 4px 15px rgba(0,0,0,0.5); }
    nav .logo h3 { font-size: 1rem; padding: 0 10px; margin-top: 10px; }
    nav a, .parent-menu {
        padding: 12px 20px;
        margin: 4px 12px;
        color: var(--text-sec);
        text-decoration:none;
        font-weight:500;
        display: flex;
        align-items: center;
        gap: 12px;
        border-radius: 10px;
        transition: all 0.25s ease;
    }
    nav a:hover, nav a.active, .parent-menu:hover {
        background: rgba(0,200,83,0.12);
        color: var(--text);
    }
    nav a.active {
        background: rgba(0,200,83,0.25);
        color: var(--text);
        font-weight: 600;
        border-left: 4px solid var(--primary);
    }
    .parent-menu { cursor:pointer; justify-content: space-between; }
    .parent-menu span { display:flex; align-items:center; gap:10px; }
    .sub-menu a { padding-left: 48px; font-size: 0.95rem; color: var(--text-sec); }
    main { margin-left: 240px; padding: 92px 20px 40px; transition: margin-left 0.3s; min-height: 100vh; }
    main.full { margin-left: 0; }
    #fullscreen-btn { font-size:1.6rem; background:none; border:none; color:var(--text); cursor:pointer; transition: all 0.2s; }
    #fullscreen-btn:hover { color: var(--primary); transform:scale(1.15); }
    header {
        position: fixed;
        top: 0;
        left: var(--sidebar-width);
        right: 0;
        min-height: 72px;
        background: linear-gradient(135deg, var(--surface), var(--card));
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 999;
        color: var(--text);
        font-size: 1.4rem;
        font-weight: 600;
        box-shadow: 0 4px 20px rgba(0,0,0,0.6);
        border-bottom: 1px solid var(--border);
        transition: left 0.3s, width 0.3s;
        padding: 10px 18px;
    }
    header.full { left: 0; width: 100%; }
    .header-left {
        position: absolute;
        left: 20px;
        display: flex;
        gap: 16px;
        align-items: center;
    }
    #collapse-btn, .burger-menu {
        font-size: 1.6rem;
        background: none;
        border: none;
        color: var(--text);
        cursor: pointer;
        transition: all 0.2s;
    }
    #collapse-btn:hover, .burger-menu:hover {
        color: var(--primary);
        transform: scale(1.15);
    }
    .header-center {
        margin: 0;
        line-height: 1.15;
        min-width: 0;
        max-width: 100%;
        padding: 0 170px 0 84px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        text-align: center;
    }
    .form-container {
        width: 100%;
        max-width: 100%;
        background: var(--bg);
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
        box-sizing: border-box;
        opacity: 0;
        transform: translateY(20px);
        animation: fadeInUp 0.5s ease-out forwards;
    }
    @keyframes fadeInUp { to { opacity: 1; transform: translateY(0); } }
    form.add-matiere-form { display: block; max-width: 550px; }
    form.add-matiere-form input[type="text"] {
        flex-grow: 1;
        padding: 12px 16px;
        font-size: 1.2rem;
        border: 2px solid var(--border);
        border-radius: 8px;
        transition: border-color 0.3s ease;
        font-weight: 600;
        background: var(--surface);
        color: var(--text);
    }
    form.add-matiere-form input[type="text"]:focus {
        border-color: var(--primary);
        outline: none;
    }
    form.add-matiere-form button {
        background-color: var(--primary);
        border: none;
        color: white;
        font-weight: 700;
        font-size: 1.1rem;
        padding: 12px 28px;
        border-radius: 8px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }
    form.add-matiere-form button:hover { background-color: var(--primary-dark); }
    footer {
        text-align: center;
        padding: 2.5rem 1rem 1.5rem;
        color: var(--text-sec);
        font-size: 0.92rem;
        border-top: 1px solid var(--border);
        margin-top: 3rem;
    }
    .matieres-grid, .matieres-assign-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit,minmax(300px,1fr));
        gap: 20px;
        padding-top:20px;
        align-items: stretch;
    }
    .matiere-card, .matiere-assign-card {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 18px;
        padding: 22px 14px;
        border-radius: 28px;
        background: var(--card);
        box-shadow: 0 3px 15px rgba(0,0,0,0.3);
        cursor: pointer;
        transition: background 0.3s, box-shadow 0.3s, transform 0.3s ease;
        min-height: 130px;
        text-align: center;
    }
    .rename-form { display: flex; gap: 12px; align-items: center; }
    .rename-form input[type="text"] {
        flex-grow: 1;
        padding: 10px 14px;
        font-size: 0.8rem;
        border: 2px solid var(--border);
        border-radius: 6px;
        background: var(--surface) !important;
        font-weight: 600;
        color: var(--text);
        transition: border-color 0.3s ease;
    }
    .rename-form input[type="text"]:focus { border-color: var(--primary); outline: none; }
    .rename-btn {
        background-color: #f39c12;
        border: none;
        color: white;
        font-weight: 700;
        padding: 10px 16px;
        border-radius: 6px;
        cursor: pointer;
    }
    .delete-btn {
        margin-top: 12px;
        background-color: var(--danger);
        border: none;
        color: white;
        font-weight: 700;
        padding: 12px;
        border-radius: 6px;
        cursor: pointer;
    }
    .select-classe { text-align: center; margin: 30px 0; }
    .select-classe select {
        padding: 10px 16px;
        border-radius: 8px;
        background: var(--surface);
        color: var(--text);
        border: 1px solid var(--border);
        min-width: 240px;
        font-size: 1.05rem;
    }
    .matiere-assign-card label {
        font-weight: 600;
        font-size: 1.1rem;
        color: var(--text-sec);
        display: flex;
        align-items: center;
        gap: 12px;
        cursor: pointer;
    }
    .coeff-input {
        margin-top: 6px;
        font-size: 1.1rem;
        font-weight: 600;
        padding: 8px 12px;
        border-radius: 8px;
        border: 2px solid var(--border);
        background-color: var(--surface) !important;
        color: var(--text);
        width: 100%;
        box-sizing: border-box;
    }
    .coeff-input:disabled {
        background-color: rgba(0,0,0,0.3) !important;
        color: var(--text-sec);
    }
    .update-btn {
        margin-top: 30px;
        background-color: var(--primary);
        color: white;
        font-weight: 700;
        padding: 14px 36px;
        font-size: 1.25rem;
        border: none;
        border-radius: 10px;
        cursor: pointer;
    }
    @media (max-width: 1100px) {
        nav { transform: translateX(-250px); transition: transform 0.3s ease; }
        nav.active { transform: translateX(0); }
        header { left: 0 !important; width: 100% !important; min-height: 86px !important; padding: 10px 164px 10px 84px !important; }
        .header-left { left: 18px !important; }
        .header-center { font-size: 1.18rem; padding: 0 !important; }
        main{ margin-left: 0 !important; padding: 118px 16px 40px !important; }
        .matieres-grid, .matieres-assign-grid { grid-template-columns: repeat(2,minmax(0,1fr)); }
    }
    @media (max-width: 760px) {
        header { min-height: 126px !important; padding: 58px 12px 12px !important; align-items: flex-start !important; justify-content: flex-start !important; }
        .header-left { position: fixed !important; left: 14px !important; top: 12px !important; z-index: 10050 !important; }
        .header-center { font-size: 1.06rem !important; white-space: normal !important; text-align: left !important; display: -webkit-box !important; -webkit-line-clamp: 2 !important; -webkit-box-orient: vertical !important; overflow: hidden !important; }
        main { padding: 154px 12px 40px !important; }
        .form-container { padding: 0 !important; border: 0 !important; box-shadow: none !important; background: transparent !important; }
        .add-matiere-form { display:grid!important; grid-template-columns:minmax(0,1fr) auto; gap:8px; max-width:none!important; }
        form.add-matiere-form input[type="text"] { min-width:0; padding:10px 11px; font-size:.88rem; border-radius:10px; }
        form.add-matiere-form button { padding:10px 13px; font-size:.82rem; border-radius:10px; white-space:nowrap; }
        .matieres-grid, .matieres-assign-grid { grid-template-columns: repeat(2,minmax(0,1fr)); gap: 10px; padding-top:14px; }
        .matiere-card, .matiere-assign-card { border-radius: 14px; padding: 10px; min-height: 112px; gap:8px; box-shadow:0 6px 18px rgba(0,0,0,.20); }
        .rename-form { flex-direction: column; width: 100%; gap:7px; }
        .rename-form input[type="text"] { width:100%; min-width:0; padding:8px 9px; font-size:.76rem; line-height:1.15; text-align:center; border-radius:8px; }
        .rename-btn { width: 100%; min-height:32px; padding:7px 8px; font-size:.72rem; border-radius:8px; }
        .delete-btn { margin-top:2px; width:34px; height:34px; padding:0; border-radius:999px; font-size:.78rem; }
        .select-classe { margin:20px 0 14px; }
        .select-classe select { width:100%; min-width:0; font-size:.88rem; padding:10px 12px; }
        .matiere-assign-card label { width:100%; font-size:.82rem; line-height:1.15; gap:7px; justify-content:flex-start; overflow-wrap:anywhere; }
        .matiere-assign-card input[type="checkbox"] { width:16px; min-width:16px; height:16px; }
        .coeff-input { margin-top:2px; padding:7px 8px; font-size:.86rem; border-radius:8px; }
        .update-btn { width: 100%; padding:11px 14px; font-size:.9rem; border-radius:10px; }
    }
    @media (max-width: 520px) {
        main { padding-left:10px!important; padding-right:10px!important; }
        .matieres-grid, .matieres-assign-grid { grid-template-columns: repeat(2,minmax(0,1fr)); gap:8px; }
        .matiere-card, .matiere-assign-card { padding:9px 7px; min-height:108px; }
        .rename-form input[type="text"] { font-size:.72rem; padding:7px 6px; }
        .rename-btn { min-height:30px; font-size:.68rem; }
    }
</style>
</head>
<body>
@php
    $legacyBase = 'http://localhost/novaskol/';
    $logo = $ecole->logo ?? 'logo.png';
    $logoPath = str_starts_with($logo, 'images/') ? substr($logo, 7) : $logo;
    $msg = session('matiere_msg');
@endphp
@include('partials.global-actions')
<nav id="sidebar">
    <div class="logo">
        <img src="{{ asset('legacy/images/'.$logoPath) }}" alt="Logo">
        <h3>{{ $ecole->nom ?? 'Ecole' }}</h3>
    </div>
    @php
        $openSub = false;
    @endphp
    @foreach ($modules as $module => $info)
        @if (session('utilisateur.role') !== 'admin' && ! empty($info['icon']) && ! in_array($userPermissions[$module] ?? null, ['lecture', 'ecriture'], true))
            @continue
        @endif
        @if (empty($info['icon']))
            @if ($openSub)
                </div>
                @php
                    $openSub = false;
                @endphp
            @endif
            <div class="parent-menu" onclick="toggleSub(this)">
                <span><i class="fa {{ $info['section_icon'] ?? 'fa-folder-open' }}"></i> {{ preg_replace('/^\s*\|\s*--\s*/', '', $info['label']) }}</span>
                <i class="fa fa-chevron-down arrow"></i>
            </div>
            <div class="sub-menu" style="display:none;">
            @php
                $openSub = true;
            @endphp
        @else
            @php
                $href = $module === 'dashboard'
                    ? route('dashboard')
                    : (! empty($info['migrated']) && ! empty($info['route'])
                        ? route($info['route'])
                        : $legacyBase.($info['legacy_url'] ?? $info['url'] ?? '#'));
            @endphp
            <a href="{{ $href }}" @class(['active' => $module === 'matieres'])>
                <i class="fa {{ $info['icon'] }}"></i> <span>{{ $info['label'] }}</span>
            </a>
        @endif
    @endforeach
    @if ($openSub)
        </div>
    @endif
</nav>
<header>
    <div class="header-left">
        <button title="Cacher les modules" class="burger-menu" onclick="toggleSidebar()"><i class="fa fa-bars"></i></button>
        <button title="Plein ecran" id="fullscreen-btn" onclick="toggleFullscreen()"><i id="fullscreen-icon" class="fa fa-expand"></i></button>
    </div>
    <div class="header-center">
        <i class="fa fa-book"></i> Gestion des matieres
    </div>
</header>

<main>
    <div class="form-container">
        <form method="POST" action="{{ route('modules.matieres.store') }}" class="add-matiere-form">
            @csrf
            <input type="text" name="nouvelle_matiere" placeholder="Nouvelle matiere..." required autocomplete="off">
            <button type="submit">Ajouter</button>
        </form>

        <section class="matieres-grid">
            @foreach ($matieres as $matiere)
                <div class="matiere-card">
                    <form method="POST" action="{{ route('modules.matieres.rename') }}" class="rename-form">
                        @csrf
                        <input type="hidden" name="matiere_id_rename" value="{{ $matiere->id }}">
                        <input type="text" name="nouveau_nom_rename" value="{{ $matiere->nom }}" required>
                        <button type="submit" class="rename-btn">Save</button>
                    </form>
                    <form method="POST" action="{{ route('modules.matieres.delete') }}" class="delete-form">
                        @csrf
                        <input type="hidden" name="matiere_id_delete" value="{{ $matiere->id }}">
                        <button type="button" class="delete-btn"><i class="fa fa-trash"></i></button>
                    </form>
                </div>
            @endforeach
        </section>

        <form method="GET" action="{{ route('modules.matieres') }}" class="select-classe">
            <select name="classe_id" onchange="this.form.submit()">
                <option value="">-- Selectionner une classe --</option>
                @foreach ($classes as $classe)
                    <option value="{{ $classe->id }}" @selected($classe->id == $classeId)>{{ $classe->nom }}</option>
                @endforeach
            </select>
        </form>

        @if ($classeId > 0)
            <form method="POST" action="{{ route('modules.matieres.assignments') }}">
                @csrf
                <input type="hidden" name="classe_id" value="{{ $classeId }}">
                <section class="matieres-assign-grid">
                    @foreach ($matieres as $matiere)
                        @php
                            $checked = array_key_exists($matiere->id, $assignedSubjects);
                            $coef = $checked ? $assignedSubjects[$matiere->id] : 1;
                        @endphp
                        <div class="matiere-assign-card">
                            <label>
                                <input type="checkbox" name="matieres[]" value="{{ $matiere->id }}"
                                       @checked($checked)
                                       onchange="this.closest('.matiere-assign-card').querySelector('.coeff-input').disabled = !this.checked">
                                {{ $matiere->nom }}
                            </label>
                            <input type="number" name="coefficients[{{ $matiere->id }}]" value="{{ $coef }}" min="0.1" step="0.1" class="coeff-input" @disabled(! $checked)>
                        </div>
                    @endforeach
                </section>
                <button type="submit" class="update-btn">Enregistrer les modifications</button>
            </form>
        @endif
    </div>
    <footer>
        &copy; {{ date('Y') }} {{ $ecole->nom ?? 'Ecole' }}. Tous droits reserves.
    </footer>
</main>

<script>
function toggleSub(el) {
    const sub = el.nextElementSibling;
    sub.style.display = sub.style.display === 'block' ? 'none' : 'block';
    el.querySelector('.arrow').classList.toggle('fa-chevron-down');
    el.querySelector('.arrow').classList.toggle('fa-chevron-up');
}
function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const main = document.querySelector('main');
    const header = document.querySelector('header');
    if (window.innerWidth <= 1100) {
        sidebar.classList.toggle('active');
    } else {
        sidebar.classList.toggle('hidden');
        main.classList.toggle('full');
        header.classList.toggle('full');
    }
}
function toggleFullscreen() {
    const icon = document.getElementById('fullscreen-icon');
    if (!document.fullscreenElement) {
        document.documentElement.requestFullscreen();
        icon.classList.replace('fa-expand', 'fa-compress');
    } else {
        document.exitFullscreen();
        icon.classList.replace('fa-compress', 'fa-expand');
    }
}
@if ($msg)
Swal.fire({
    title: '{{ $msg['type'] === 'success' ? 'Succes' : 'Erreur' }}',
    text: @json($msg['text']),
    icon: '{{ $msg['type'] }}',
    timer: 2200,
    showConfirmButton: false,
    position: 'center'
});
@endif
document.querySelectorAll('.delete-btn').forEach(btn => {
    btn.addEventListener('click', function(e) {
        e.preventDefault();
        const form = this.closest('.delete-form');
        Swal.fire({
            title: 'Confirmer la suppression ?',
            text: "Cette action est irreversible",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Oui, supprimer',
            cancelButtonText: 'Annuler'
        }).then((result) => {
            if (result.isConfirmed) form.submit();
        });
    });
});
document.addEventListener('DOMContentLoaded', () => {
    const active = document.querySelector('nav a.active');
    if (active) {
        const sub = active.closest('.sub-menu');
        if (sub) {
            sub.style.display = 'block';
            const parent = sub.previousElementSibling;
            if (parent) parent.querySelector('.arrow')?.classList.replace('fa-chevron-down', 'fa-chevron-up');
        }
    }
});
</script>
</body>
</html>
