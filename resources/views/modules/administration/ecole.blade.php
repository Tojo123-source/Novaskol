<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Details de l'ecole - {{ $ecole->nom ?? 'Ecole' }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
    *::-webkit-scrollbar { width: 3px; }
    *::-webkit-scrollbar-track { background: var(--scroll-track); border-radius: 10px; }
    *::-webkit-scrollbar-thumb { background: var(--scroll-thumb); border-radius: 10px; border: 1px solid var(--scroll-track); }
    *::-webkit-scrollbar-thumb:hover { background: var(--scroll-thumb-hover); }
    * { scrollbar-width: thin; scrollbar-color: var(--scroll-thumb) var(--scroll-track); }
    * { margin:0; padding:0; box-sizing:border-box; }
    body {
        font-family: system-ui, -apple-system, sans-serif;
        background: var(--bg);
        color: var(--text);
        min-height: 100vh;
        line-height: 1.5;
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
        transition: transform 0.28s ease;
    }
    nav.hidden { transform: translateX(-100%); }
    nav.active { transform: translateX(0); }
    nav .logo { text-align: center; padding: 30px 0 20px; }
    nav .logo img {
        max-width: 72px;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.5);
    }
    nav .logo h3 { font-size: 1rem; padding: 0 10px; margin-top: 10px; }
    nav a, .parent-menu {
        padding: 12px 20px;
        margin: 4px 12px;
        color: var(--text-sec);
        text-decoration: none;
        font-weight: 500;
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
        color: white;
        font-weight: 600;
        border-left: 4px solid var(--primary);
    }
    .parent-menu { cursor: pointer; justify-content: space-between; }
    .parent-menu span { display:flex; align-items:center; gap:10px; }
    .sub-menu a { padding-left: 48px; font-size: 0.95rem; color: var(--text-sec); }
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
    header.full-width { left: 0; width: 100%; }
    .header-left {
        position: absolute;
        left: 20px;
        display: flex;
        gap: 16px;
        align-items: center;
    }
    h2 {
        font-size: 1.5rem;
        font-weight: bold;
        line-height: 1.15;
        margin: 0;
        min-width: 0;
        max-width: 100%;
        padding: 0 170px 0 84px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        text-align: center;
    }
    #fullscreen-btn, .burger-menu {
        font-size: 1.6rem;
        background: none;
        border: none;
        color: var(--text);
        cursor: pointer;
        transition: all 0.2s;
    }
    #fullscreen-btn:hover, .burger-menu:hover {
        color: var(--primary);
        transform: scale(1.15);
    }
    main {
        margin-left: var(--sidebar-width);
        padding: 90px 20px 40px;
        min-height: 100vh;
        transition: margin-left 0.3s ease;
    }
    main.full-width { margin-left: 0; }
    .form-container {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: 16px;
        padding: 2rem;
        box-shadow: 0 6px 22px rgba(0,0,0,0.4);
        max-width: 810px;
        margin: 2rem auto;
        animation: fadeIn 0.6s ease-out;
        width: 100%;
        min-width: 0;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    h1, h3 { color: var(--text); margin-bottom: 1.2rem; }
    label {
        display: block;
        margin: 1.1rem 0 0.4rem;
        font-weight: 500;
        color: var(--text-sec);
    }
    input[type="text"],
    input[type="file"],
    select {
        width: 100%;
        padding: 0.9rem 1.1rem;
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 10px;
        color: var(--text);
        font-size: 1rem;
        transition: border-color 0.2s;
    }
    input:focus, select:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(0,200,83,0.15);
    }
    .submit-btn {
        margin-top: 1.8rem;
        padding: 0.95rem 2.2rem;
        background: var(--primary);
        color: #000;
        border: none;
        border-radius: 10px;
        font-weight: 600;
        font-size: 1.05rem;
        cursor: pointer;
        transition: all 0.25s;
    }
    .submit-btn:hover {
        background: var(--primary-dark);
        transform: translateY(-2px);
        box-shadow: 0 8px 25px var(--primary-glow);
    }
    .ecole-form img {
        max-width: 220px;
        height: auto;
        margin: 1.8rem auto;
        display: block;
        border-radius: 12px;
        border: 1px solid var(--border);
        box-shadow: 0 4px 16px rgba(0,0,0,0.4);
    }
    footer {
        text-align: center;
        padding: 2.5rem 1rem 1.5rem;
        color: var(--text-sec) !important;
        font-size: 0.92rem;
        border-top: 1px solid var(--border);
        margin-top: 3rem;
    }
    @media (max-width: 1100px) {
        nav { transform: translateX(-100%); }
        nav.active { transform: translateX(0); }
        main, header { margin-left: 0 !important; left: 0 !important; width: 100%; }
        header { min-height: 86px; padding: 10px 164px 10px 84px; }
        .header-left { left: 18px; }
        h2 { font-size: 1.18rem; padding: 0; }
        main { padding-top: 118px; }
    }
    @media (max-width: 760px) {
        header {
            min-height: 126px;
            padding: 58px 12px 12px;
            align-items: flex-start;
            justify-content: flex-start;
        }
        .header-left {
            position: fixed;
            left: 14px;
            top: 12px;
            z-index: 10050;
        }
        h2 {
            font-size: 1.08rem;
            white-space: normal;
            text-align: left;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        main { padding: 154px 12px 40px; }
        .form-container { padding: 1.4rem; margin: 1.5rem 0; }
        .ecole-form img { max-width: 180px; margin: 1.2rem auto; }
    }
    @media (max-width: 520px) {
        h2 { font-size: 1rem; }
    }
</style>
</head>
<body>
@php
    $legacyBase = 'http://localhost/novaskol/';
    $logo = $ecole->logo ?? 'novaskol.png';
    $logoPath = str_starts_with($logo, 'images/') ? substr($logo, 7) : $logo;
@endphp
@include('partials.global-actions')
<div id="app">
    <nav id="sidebar">
        <div class="logo">
            <img src="{{ asset('legacy/images/'.$logoPath) }}" alt="Logo">
            <h3>{{ $ecole->nom ?? 'Ecole' }}</h3>
        </div>
        @php($openSub = false)
        @foreach ($modules as $module => $info)
            @if (session('utilisateur.role') !== 'admin' && ! empty($info['icon']) && ! in_array($userPermissions[$module] ?? null, ['lecture', 'ecriture'], true))
                @continue
            @endif

            @if (empty($info['icon']))
                @if ($openSub)
                    </div>
                    @php($openSub = false)
                @endif
                <div class="parent-menu" onclick="toggleSub(this)">
                    <span><i class="fa {{ $info['section_icon'] ?? 'fa-folder-open' }}"></i> {{ preg_replace('/^\s*\|\s*--\s*/', '', $info['label']) }}</span>
                    <i class="fa fa-chevron-down arrow"></i>
                </div>
                <div class="sub-menu" style="display:none;">
                @php($openSub = true)
            @else
                @php($href = $module === 'dashboard' ? route('dashboard') : (! empty($info['migrated']) && ! empty($info['route']) ? route($info['route']) : $legacyBase.($info['legacy_url'] ?? $info['url'] ?? '#')))
                <a href="{{ $href }}" @class(['active' => $module === 'ecole'])>
                    <i class="fa {{ $info['icon'] }}"></i> <span>{{ $info['label'] }}</span>
                </a>
            @endif
        @endforeach
        @if ($openSub)
            </div>
        @endif
    </nav>

    <main>
        <header>
            <div class="header-left">
                <button title="Cacher les modules" class="burger-menu" onclick="toggleSidebar()"><i class="fa fa-bars"></i></button>
                <button title="Plein ecran" id="fullscreen-btn" onclick="toggleFullscreen()"><i id="fullscreen-icon" class="fa fa-expand"></i></button>
            </div>
            <h2><i class="fa fa-building"></i> Details de l'ecole</h2>
        </header>

        <div class="form-container">
            <form class="ecole-form" method="POST" action="{{ route('modules.ecole.update') }}" enctype="multipart/form-data">
                @csrf
                <p>NB : Vous devez ajouter un seul logo !</p>
                <label for="logo">Logo de l'ecole :</label>
                <input type="file" name="logo" accept="image/*">
                <label for="nom_ecole">Nom de l'ecole :</label>
                <input type="text" name="nom_ecole" value="{{ $ecole->nom ?? '' }}" placeholder="Entrer ici le nom de votre etablissement" required>
                <button class="submit-btn" type="submit">Enregistrer</button>
                <img src="{{ asset('legacy/images/'.$logoPath) }}?t={{ time() }}" alt="Logo">
            </form>
        </div>

        <footer>
            &copy; {{ date('Y') }} {{ $ecole->nom ?? 'Ecole' }}. Tous droits reserves.
        </footer>
    </main>
</div>

<script>
$(document).ready(function() {
    $('select[name="type"]').on('change', function() {
        if ($(this).val() === 'etudiant') {
            $('select[name="classe"]').prop('disabled', false);
        } else {
            $('select[name="classe"]').prop('disabled', true);
        }
    });
});

function toggleSidebar() {
    const sidebar = document.querySelector('nav');
    const mainContent = document.querySelector('main');
    const header = document.querySelector('header');
    const isMobile = window.innerWidth <= 1100;

    if (isMobile) {
        sidebar.classList.toggle('active');
        sidebar.classList.toggle('hidden');
        mainContent.classList.toggle('full-width');
        header.classList.toggle('full-width');
    } else {
        sidebar.classList.toggle('hidden');
        mainContent.classList.toggle('full-width');
        header.classList.toggle('full-width');
    }
}

function toggleSub(elem) {
    const sub = elem.nextElementSibling;
    const arrow = elem.querySelector('.arrow');
    if (sub.style.display === 'none' || sub.style.display === '') {
        sub.style.display = 'block';
        arrow.classList.add('open');
    } else {
        sub.style.display = 'none';
        arrow.classList.remove('open');
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const active = document.querySelector('nav a.active');
    if (active) {
        const sub = active.closest('.sub-menu');
        if (sub) {
            sub.style.display = 'block';
            const parent = sub.previousElementSibling;
            if (parent) {
                const arrow = parent.querySelector('.arrow');
                if (arrow) arrow.classList.add('open');
            }
        }
    }
});

function toggleFullscreen() {
    const icon = document.getElementById('fullscreen-icon');
    if (!document.fullscreenElement) {
        document.documentElement.requestFullscreen().catch(err => {
            console.error(`Erreur lors de la tentative de passage en plein ecran: ${err.message}`);
        });
        icon.classList.replace('fa-expand', 'fa-compress');
    } else {
        document.exitFullscreen();
        icon.classList.replace('fa-compress', 'fa-expand');
    }
}

$(document).ready(function () {
    $('form.ecole-form').on('submit', function (e) {
        e.preventDefault();
        const formData = new FormData(this);
        $.ajax({
            url: '{{ route('modules.ecole.update') }}',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')},
            success: function (response) {
                try {
                    const data = typeof response === 'string' ? JSON.parse(response) : response;
                    if (data.status === 'success') {
                        const src = '{{ asset('legacy/images') }}/' + data.logo + '?t=' + new Date().getTime();
                        $('.logo h3').text(data.nom);
                        $('.logo img').attr('src', src);
                        $('input[name="nom_ecole"]').val(data.nom);
                        $('.ecole-form img').attr('src', src);

                        Swal.fire({
                            title: 'Succes !',
                            text: data.message || 'Ecole mise a jour avec succes',
                            icon: 'success',
                            timer: 1800,
                            showConfirmButton: false
                        });
                    } else {
                        Swal.fire({
                            title: 'Erreur',
                            text: data.message || 'Impossible de mettre a jour les informations',
                            icon: 'error'
                        });
                    }
                } catch (e) {
                    Swal.fire({
                        title: 'Erreur',
                        text: 'Reponse invalide du serveur',
                        icon: 'error'
                    });
                }
            },
            error: function (xhr) {
                const message = xhr.responseJSON?.message || 'Erreur de connexion au serveur (' + xhr.status + ')';
                Swal.fire({
                    title: 'Erreur',
                    text: message,
                    icon: 'error'
                });
            }
        });
    });
});
</script>
</body>
</html>
