@php
    $interfaceLanguage = Schema::hasTable('parametres') ? (DB::table('parametres')->where('cle', 'langue_interface')->value('valeur') ?: 'fr') : 'fr';
@endphp
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - {{ $ecole->nom ?? 'Novaskol' }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/png" href="{{ asset('novaskol-icon.png') }}">
    <link rel="stylesheet" href="{{ asset('legacy/js/fullcalendar.min.css') }}">
    <script src="{{ asset('legacy/js/index.global.js') }}"></script>
    <script src="{{ asset('legacy/js/chart.min.js') }}"></script>
    <script src="{{ asset('legacy/js/jquery-3.6.0.min.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('legacy/assets/sweetalert2/sweetalert2.min.css') }}">
    <script src="{{ asset('legacy/assets/sweetalert2/sweetalert2.min.js') }}"></script>
    <script>window.NOVASKOL_INITIAL_LANGUAGE = @json($interfaceLanguage);</script>
    <script src="{{ asset('legacy/js/novaskol-i18n.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('legacy/assets/fontawesome/css/all.min.css') }}">
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
            --shadow-strong: rgba(0,0,0,0.6);
            --shadow-soft: rgba(0,0,0,0.3);
            --header-bg: linear-gradient(135deg, var(--surface), var(--card));
            --nav-active: rgba(0,200,83,0.25);
            --nav-hover: rgba(0,200,83,0.12);
        }
        :root.light {
            --bg: #f3f6fb;
            --card: #ffffff;
            --surface: #f8fafc;
            --primary: #059669;
            --primary-dark: #047857;
            --primary-glow: rgba(5,150,105,0.16);
            --text: #111827;
            --text-sec: #475569;
            --border: #d8e0ea;
            --scroll-track: #eef2f7;
            --scroll-thumb: #cbd5e1;
            --scroll-thumb-hover: #94a3b8;
            --glow: rgba(5,150,105,0.12);
            --danger: #dc2626;
            --success: #059669;
            --shadow-strong: rgba(15,23,42,0.16);
            --shadow-soft: rgba(15,23,42,0.10);
            --header-bg: linear-gradient(135deg,#ffffff,#eef5f1);
            --nav-active: rgba(5,150,105,0.14);
            --nav-hover: rgba(5,150,105,0.09);
        }
        *::-webkit-scrollbar { width: 3px; }
        *::-webkit-scrollbar-track { background: var(--scroll-track); border-radius: 10px; }
        *::-webkit-scrollbar-thumb { background: var(--scroll-thumb); border-radius: 10px; border: 1px solid var(--scroll-track); }
        *::-webkit-scrollbar-thumb:hover { background: var(--scroll-thumb-hover); }
        * { scrollbar-width: thin; scrollbar-color: var(--scroll-thumb) var(--scroll-track); }
        :root.light * { scrollbar-color: #cbd5e1 #eef2f7 !important; }
        :root.light *::-webkit-scrollbar-track { background: #eef2f7 !important; }
        :root.light *::-webkit-scrollbar-thumb { background: #cbd5e1 !important; border-color: #eef2f7 !important; }
        :root.light *::-webkit-scrollbar-thumb:hover { background: #94a3b8 !important; }
        .swal2-container { z-index: 250000 !important; }
        .swal2-popup { z-index: 250001 !important; }
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
            transition: transform 0.28s ease;
        }
        nav.hidden { transform: translateX(-100%); }
        nav.active { transform: translateX(0); }
        nav .logo { text-align:center; padding: 30px 0 20px; }
        nav .logo img { max-width:72px; border-radius:12px; box-shadow: 0 4px 15px var(--shadow-soft); }
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
            cursor: pointer;
            position: relative;
            z-index: 1001;
        }
        nav a { cursor: pointer !important; }
        nav a:hover, nav a.active { background: var(--nav-hover); color: var(--text); }
        nav a.active {
            background: var(--nav-active);
            color: var(--text);
            font-weight: 600;
            border-left: 4px solid var(--primary);
        }
        .parent-menu { cursor:pointer; justify-content: space-between; }
        .parent-menu span { display:flex; align-items:center; gap:10px; }
        .sub-menu a { padding-left: 48px; font-size: 0.95rem; color: var(--text-sec); }
        header {
            position: fixed;
            top: 0;
            left: var(--sidebar-width);
            right: 0;
            height: 70px;
            background: var(--header-bg);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 1.8rem;
            z-index: 999;
            box-shadow: 0 4px 20px var(--shadow-strong);
            border-bottom: 1px solid var(--border);
            transition: left 0.28s ease;
        }
        header.full-width { left: 0; width: 100%; }
        .header-left { display: flex; align-items: center; gap: 1.3rem; }
        .header-center { font-size: 1.35rem; font-weight: 700; color: var(--text); display: flex; align-items: center; gap: 0.8rem; }
        .header-right { display: flex; align-items: center; gap: 1.2rem; position: relative; }
        .burger-menu, #fullscreen-btn {
            width: 40px;
            height: 40px;
            min-width: 40px;
            border-radius: 999px;
            border: 1px solid var(--border);
            background: var(--card);
            color: var(--text);
            display: grid;
            place-items: center;
            box-shadow: 0 8px 24px rgba(0,0,0,.24);
            cursor: pointer;
            transition: transform .18s ease, color .18s ease, background .18s ease;
            flex: 0 0 40px;
        }
        .burger-menu:hover, #fullscreen-btn:hover {
            color: var(--primary);
            transform: translateY(-1px);
        }
        .burger-menu i, #fullscreen-btn i { font-size: 1rem; line-height: 1; pointer-events: none; }
        .notif-bell, .theme-toggle {
            background: none;
            border: none;
            color: var(--text);
            font-size: 1.45rem;
            cursor: pointer;
            transition: all 0.2s;
        }
        .notif-bell:hover, .theme-toggle:hover {
            color: var(--primary);
            transform: scale(1.12);
        }
        .notif-badge {
            position: absolute;
            top: -6px;
            right: -6px;
            background: var(--danger);
            color: white;
            font-size: 0.68rem;
            width: 18px; height: 18px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }
        .notif-dropdown {
            position: absolute;
            top: 68px;
            right: 0;
            width: 340px;
            max-height: 520px;
            overflow-y: auto;
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 14px;
            box-shadow: 0 12px 40px rgba(0,0,0,0.5);
            z-index: 2000;
            display: none;
            padding: 1rem;
        }
        .profile-trigger{width:40px;height:40px;border-radius:999px;border:1px solid var(--border);background:var(--card);padding:0;cursor:pointer;overflow:hidden}.profile-trigger img{width:100%;height:100%;object-fit:cover}.message-bell{background:none;border:none;color:var(--text);font-size:1.45rem;cursor:pointer;position:relative}.message-bell:hover,.profile-trigger:hover{color:var(--primary);transform:scale(1.08)}.profile-dropdown{position:absolute;top:68px;right:0;width:360px;max-height:72vh;overflow:auto;background:var(--card);border:1px solid var(--border);border-radius:14px;box-shadow:0 12px 40px rgba(0,0,0,.5);z-index:2000;display:none;padding:1rem}.profile-dropdown.active{display:block}.profile-card{text-align:center}.profile-card img{width:82px;height:82px;border-radius:999px;object-fit:cover;border:2px solid var(--primary);margin-bottom:10px}.profile-tabs{display:flex;gap:8px;margin:12px 0}.profile-tabs button{flex:1;padding:9px;border-radius:8px;border:1px solid var(--border);background:var(--surface);color:var(--text);cursor:pointer}.profile-pane{display:none;text-align:left}.profile-pane.active{display:block}.profile-pane label{display:block;margin:10px 0 6px;color:var(--text-sec)}.profile-pane input{width:100%;padding:10px;background:var(--surface);color:var(--text);border:1px solid var(--border);border-radius:8px}.profile-actions{display:flex;gap:8px;justify-content:flex-end;margin-top:12px}.global-primary{background:var(--primary);color:#062b1d;border:0;border-radius:8px;padding:10px 12px;font-weight:800;cursor:pointer}.global-danger{background:#ef4444;color:white;border:0;border-radius:8px;padding:10px 12px;font-weight:800;cursor:pointer}.novaskol-loader{position:fixed;inset:0;background:rgba(0,0,0,.58);z-index:5000;display:none;align-items:center;justify-content:center;backdrop-filter:blur(4px)}.novaskol-loader.active{display:flex}.loader-box{background:var(--card);border:1px solid var(--border);border-radius:8px;padding:24px 30px;text-align:center;color:var(--text);box-shadow:0 20px 60px #0008}.loader-ring{width:48px;height:48px;border-radius:50%;border:4px solid rgba(0,200,83,.18);border-top-color:var(--primary);animation:novaspin .8s linear infinite;margin:0 auto 12px}@keyframes novaspin{to{transform:rotate(360deg)}}
        .header-right .theme-toggle,.header-right .notif-bell,.header-right .message-bell,.header-right .profile-trigger,.header-right .lang-trigger{width:40px;height:40px;min-width:40px;border-radius:999px;border:1px solid var(--border);background:var(--card);color:var(--text);display:grid;place-items:center;padding:0;box-shadow:0 8px 24px rgba(0,0,0,.25);position:relative;overflow:visible;cursor:pointer;transition:transform .18s ease,color .18s ease,background .18s ease;flex:0 0 40px}
        .header-right .theme-toggle i,.header-right .notif-bell i,.header-right .message-bell i,.header-right .lang-trigger i{font-size:1rem;line-height:1}
        .connecte-header-actions{display:flex;gap:10px;margin:12px 0 4px;flex-wrap:wrap}.connecte-btn{padding:10px 20px;border-radius:999px;border:1px solid var(--border);background:var(--card);color:var(--text);cursor:pointer;font-size:.9rem;font-weight:600;display:flex;align-items:center;gap:8px;box-shadow:0 4px 14px var(--shadow-soft);transition:all .2s ease}.connecte-btn-sync{color:var(--primary);border-color:rgba(0,200,83,.3)}.connecte-btn-sync:hover{background:rgba(0,200,83,.12);transform:translateY(-1px)}.connecte-btn-compte:hover{background:rgba(255,255,255,.06);transform:translateY(-1px)}
        .header-right .profile-trigger{overflow:hidden}
        .header-right .theme-toggle:hover,.header-right .notif-bell:hover,.header-right .message-bell:hover,.header-right .profile-trigger:hover,.header-right .lang-trigger:hover{color:var(--primary);transform:translateY(-1px)}
        .lang-wrap{position:relative}
        .lang-dropdown{position:absolute;top:52px;right:0;width:84px;padding:8px;background:var(--card);border:1px solid var(--border);border-radius:14px;box-shadow:0 18px 40px rgba(0,0,0,.35);display:none;z-index:2100}
        .lang-dropdown.active{display:grid;gap:6px}
        .lang-option{width:100%;padding:8px 10px;border-radius:10px;border:1px solid transparent;background:transparent;color:var(--text);font-weight:800;cursor:pointer;text-align:center}
        .lang-option:hover,.lang-option.active{background:rgba(0,200,83,.12);border-color:rgba(0,200,83,.24);color:var(--primary)}
        @media (max-width: 1180px) {
            header { padding: 12px 14px; gap: 10px; }
            .header-center { min-width: 0; font-size: 1.12rem; text-align: center; }
            .header-right { gap: .75rem; }
            .stats { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        }
        @media (max-width: 760px) {
            header {
                left: 0 !important;
                width: 100% !important;
                height: auto;
                min-height: 92px;
                display: grid;
                grid-template-columns: auto 1fr auto;
                grid-template-areas:
                    "left spacer right"
                    "title title title";
                align-items: start;
                padding: 12px;
                z-index: 10040;
            }
            header .header-left { position: static; grid-area: left; gap: 8px; }
            .header-center { grid-area: title; justify-content: flex-start; text-align: left; font-size: 1rem; line-height: 1.15; margin-top: 8px; }
            .header-right { grid-area: right; gap: 8px; justify-self: end; }
            .burger-menu, #fullscreen-btn { z-index: 10051; }
            .header-right .theme-toggle,.header-right .notif-bell,.header-right .message-bell,.header-right .profile-trigger,.header-right .lang-trigger { width: 36px; height: 36px; min-width: 36px; }
            main { margin-left: 0 !important; padding-top: 122px; }
            .stats { grid-template-columns: repeat(2, minmax(0, 1fr)); gap: .85rem; margin: 1.25rem 0; }
            .stat-card { min-height: 138px; padding: .9rem .65rem; border-radius: 12px; }
            .stat-icon { width: 38px; height: 38px; border-radius: 10px; margin-bottom: .45rem; font-size: .95rem; }
            .count { font-size: 1.55rem; margin-bottom: .2rem; }
            .stat-card h2:not(.count) { font-size: 1.25rem !important; line-height: 1.15; overflow-wrap: anywhere; }
            .stat-card p { font-size: .78rem; line-height: 1.15; margin: .15rem 0; }
            .stat-card small { font-size: .68rem; line-height: 1.15; }
            .quick-actions { grid-template-columns: repeat(2, minmax(0,1fr)); }
            .kanto { margin-top: 22px !important; }
        }
        @media (max-width: 520px) {
            .quick-actions { grid-template-columns: 1fr; }
            .dashboard-section-title { flex-direction: column; align-items: flex-start; }
            .stats { grid-template-columns: repeat(2, minmax(0, 1fr)); }
            .stat-card { min-height: 132px; padding: .82rem .55rem; }
        }
        .notif-item {
            padding: 0.9rem;
            border-bottom: 1px solid var(--border);
            transition: all 0.2s;
            color: var(--text);
        }
        .notif-item:hover {
            background: rgba(0,200,83,0.08);
            border-radius: 8px;
        }
        .kaly, .kaly1, .kaly2 {
            padding: 14px;
            color: white;
            border: none;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            transition: background .2s;
            grid-column: 1 / -1;
            margin-top: 20px;
        }
        .kaly { background: #0f70c0; }
        .kaly1 { background: var(--primary); }
        .kaly2 { background: var(--danger); }
        .kaly:hover, .kaly1:hover, .kaly2:hover { background: green; }
        main {
            background: var(--bg);
            margin-left: 240px;
            padding: 90px 20px 40px;
            min-height: 100vh;
            transition: margin-left 0.3s;
        }
        main.full-width { margin-left: 0; }
        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 1.4rem;
            margin: 2.5rem 0;
        }
        .stat-card {
            background: linear-gradient(180deg, var(--card), rgba(255,255,255,0.02));
            border: 1px solid var(--border);
            border-radius: 18px;
            padding: 1.6rem 1.4rem;
            text-align: center;
            transition: all 0.28s ease;
            cursor: default;
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 220px;
            text-decoration: none;
            color: var(--text);
        }
        .stat-card::after {
            content: '';
            position: absolute;
            inset: auto -40px -40px auto;
            width: 120px;
            height: 120px;
            background: radial-gradient(circle, var(--primary-glow), transparent 68%);
            pointer-events: none;
        }
        .stat-card-link { cursor: pointer; }
        .stat-icon {
            width: 54px;
            height: 54px;
            margin: 0 auto 0.9rem;
            border-radius: 14px;
            display: grid;
            place-items: center;
            background: rgba(0,200,83,0.12);
            color: var(--primary);
            font-size: 1.25rem;
            border: 1px solid rgba(0,200,83,0.18);
        }
        .stat-card small {
            display: block;
            margin-top: 0.45rem;
            color: var(--text-sec);
            font-size: 0.82rem;
        }
        .stat-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 16px 40px var(--glow);
        }
        .stat-card:hover .stat-icon {
            background: rgba(0,200,83,0.16);
            border-color: rgba(0,200,83,0.32);
            transform: scale(1.04);
        }
        .stat-card p {
            font-size: 1rem;
            font-weight: 700;
        }
        .count {
            font-size: 2.8rem;
            font-weight: 800;
            color: var(--primary);
            display: block;
            margin-bottom: 0.5rem;
        }
        .dashboard-section-title {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            margin: 2.4rem 0 1rem;
        }
        .dashboard-section-title h2 {
            margin: 0;
            font-size: 1.15rem;
            font-weight: 800;
        }
        .dashboard-section-title span {
            color: var(--text-sec);
            font-size: 0.92rem;
        }
        .quick-actions {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(160px,1fr));
            gap: 1.2rem;
            margin: 0 0 2.8rem;
        }
        footer {
            text-align: center;
            padding: 2.5rem 1rem 1.5rem;
            color: var(--text-sec) !important;
            font-size: 0.92rem;
            border-top: 1px solid var(--border);
            margin-top: 3rem;
        }
        .action-btn {
            background: rgba(0,200,83,0.08);
            border: 1px solid var(--primary);
            border-radius: 14px;
            padding: 1.4rem 1rem;
            text-align: center;
            color: var(--primary);
            transition: all 0.35s;
            font-weight: 600;
            text-decoration: none;
        }
        .action-btn:hover {
            background: var(--primary);
            color: #000;
            transform: translateY(-5px);
            box-shadow: 0 10px 25px var(--glow);
        }
        .insight-card, .chart-container {
            background: var(--card);
            border-radius: 16px;
            padding: 1.8rem;
            margin: 2.5rem 0;
            box-shadow: 0 6px 22px var(--shadow-soft);
            border: 1px solid var(--border);
            overflow: hidden;
        }
        .chart-container {
            position: relative;
        }
        .chart-container::after {
            content: '';
            position: absolute;
            top: 0; right: 0;
            width: 200px; height: 200px;
            background: radial-gradient(circle, var(--primary-glow), transparent 70%);
            pointer-events: none;
            opacity: 0.4;
        }
        #calendar {
            background: var(--surface) !important;
            color: var(--text) !important;
            border-radius: 16px;
            padding: 1.25rem;
            transition: background 0.4s ease, box-shadow 0.4s ease;
            box-shadow: inset 0 1px 0 rgba(255,255,255,0.04), 0 8px 32px var(--shadow-soft);
            position: relative;
            z-index: 1;
        }
        .calendar-header-accent {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 16px;
        }
        .calendar-header-accent h2 {
            font-size: 1.1rem;
            font-weight: 800;
            color: var(--text);
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .calendar-header-accent .cal-badge {
            font-size: 0.7rem;
            padding: 4px 12px;
            border-radius: 999px;
            background: var(--primary-glow);
            color: var(--primary);
            font-weight: 700;
        }
        .fc-theme-standard .fc-scrollgrid {
            border: 1px solid var(--border) !important;
            border-radius: 12px;
            overflow: hidden;
        }
        .fc-theme-standard th,
        .fc-theme-standard td {
            border-color: var(--border) !important;
        }
        .fc .fc-daygrid-day {
            transition: background 0.2s ease;
            cursor: pointer;
        }
        .fc .fc-daygrid-day:hover {
            background: rgba(0,200,83,0.06);
        }
        .fc .fc-daygrid-day.fc-day-today {
            background: rgba(0,200,83,0.10) !important;
        }
        .fc .fc-daygrid-day-top {
            justify-content: center;
            padding-top: 2px;
        }
        .fc .fc-daygrid-day-number {
            font-size: 0.82rem;
            font-weight: 700;
            padding: 5px 7px;
            color: var(--text);
            text-decoration: none;
            border-radius: 999px;
            transition: all 0.2s ease;
            min-width: 28px;
            text-align: center;
        }
        .fc .fc-daygrid-day-number:hover {
            background: rgba(0,200,83,0.14);
            color: var(--primary);
            transform: scale(1.05);
        }
        .fc .fc-day-today .fc-daygrid-day-number {
            background: var(--primary);
            color: #000 !important;
            box-shadow: 0 2px 12px var(--primary-glow);
        }
        .fc .fc-col-header-cell {
            padding: 10px 0;
            background: rgba(0,200,83,0.04);
        }
        :root:not(.light) .fc .fc-col-header-cell {
            background: rgba(0,200,83,0.02);
        }
        .fc .fc-col-header-cell-cushion {
            font-size: 0.72rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: var(--text-sec);
            padding: 6px 4px;
            text-decoration: none;
        }
        :root:not(.light) .fc .fc-col-header-cell-cushion {
            color: #6b7280;
        }
        :root.light .fc .fc-col-header-cell-cushion {
            color: #475569;
        }
        .fc .fc-day-other .fc-daygrid-day-top {
            opacity: 0.3;
        }
        .fc .fc-day-other .fc-daygrid-day-number {
            font-weight: 400;
        }
        .fc .fc-day-other.fc-day-today .fc-daygrid-day-number {
            opacity: 0.6;
        }
        .fc .fc-daygrid-more-link {
            font-size: 0.7rem;
            font-weight: 700;
            color: var(--primary);
            padding: 2px 4px;
            border-radius: 4px;
            transition: all 0.2s;
        }
        .fc .fc-daygrid-more-link:hover {
            background: var(--primary-glow);
        }
        .fc .fc-event {
            border: none !important;
            border-radius: 6px !important;
            padding: 2px 6px !important;
            font-size: 0.7rem;
            font-weight: 700;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            cursor: pointer;
            box-shadow: 0 2px 6px rgba(0,0,0,0.12);
            border-left: 3px solid rgba(255,255,255,0.3) !important;
            letter-spacing: 0.01em;
        }
        .fc .fc-event:hover {
            transform: translateY(-1px) scale(1.02);
            box-shadow: 0 4px 14px rgba(0,0,0,0.25);
            z-index: 5;
        }
        .fc .fc-event.event-reunion {
            background: linear-gradient(135deg, #3b82f6, #2563eb) !important;
            color: white !important;
            border-left-color: rgba(255,255,255,0.4) !important;
        }
        .fc .fc-event.event-examen {
            background: linear-gradient(135deg, #ef4444, #dc2626) !important;
            color: white !important;
            border-left-color: rgba(255,255,255,0.4) !important;
        }
        .fc .fc-event.event-vacances {
            background: linear-gradient(135deg, #10b981, #059669) !important;
            color: white !important;
            border-left-color: rgba(255,255,255,0.4) !important;
        }
        .fc .fc-event.event-autre {
            background: linear-gradient(135deg, #8b5cf6, #7c3aed) !important;
            color: white !important;
            border-left-color: rgba(255,255,255,0.4) !important;
        }
        .fc .fc-event.event-evenement-scolaire {
            background: linear-gradient(135deg, #f59e0b, #d97706) !important;
            color: white !important;
            border-left-color: rgba(255,255,255,0.4) !important;
        }
        .fc .fc-event .fc-event-title {
            font-weight: 700;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .fc .fc-event::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(to bottom, rgba(255,255,255,0.1), transparent);
            border-radius: inherit;
            pointer-events: none;
        }
        .fc .fc-more-popover {
            background: var(--card) !important;
            border: 1px solid var(--border) !important;
            border-radius: 14px;
            box-shadow: 0 16px 48px rgba(0,0,0,0.35);
            overflow: hidden;
        }
        .fc .fc-more-popover .fc-popover-header {
            background: var(--surface) !important;
            color: var(--text) !important;
            padding: 12px 16px;
            font-weight: 700;
            font-size: 0.85rem;
            border-bottom: 1px solid var(--border);
        }
        .fc .fc-more-popover .fc-popover-body {
            padding: 10px;
        }
        .fc .fc-more-popover .fc-popover-body .fc-event {
            margin-bottom: 6px;
        }
        .fc .fc-toolbar {
            margin-bottom: 18px !important;
        }
        .fc .fc-toolbar-title {
            font-size: 1.15rem !important;
            font-weight: 800;
            color: var(--text);
            letter-spacing: 0.02em;
        }
        .fc .fc-button {
            background: var(--card) !important;
            border: 1px solid var(--border) !important;
            color: var(--text) !important;
            font-weight: 700;
            border-radius: 8px !important;
            padding: 7px 12px !important;
            transition: all 0.2s ease;
            text-transform: none;
            font-size: 0.8rem !important;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        }
        .fc .fc-button:hover {
            background: var(--nav-hover) !important;
            color: var(--primary) !important;
            border-color: var(--primary) !important;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px var(--glow);
        }
        .fc .fc-button-primary:not(:disabled).fc-button-active,
        .fc .fc-button-primary:not(:disabled):active {
            background: var(--primary) !important;
            border-color: var(--primary) !important;
            color: #000 !important;
            box-shadow: 0 4px 12px var(--primary-glow);
        }
        .fc .fc-button-primary:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
        .fc .fc-today-button {
            background: var(--primary) !important;
            border-color: var(--primary) !important;
            color: #000 !important;
            box-shadow: 0 4px 12px var(--primary-glow);
        }
        .fc .fc-today-button:hover {
            background: var(--primary-dark) !important;
            transform: translateY(-2px);
            box-shadow: 0 6px 16px var(--primary-glow);
        }
        .fc .fc-daygrid-day-events {
            min-height: 18px;
        }
        .calendar-mini-legend {
            display: flex;
            gap: 14px;
            flex-wrap: wrap;
            margin-top: 14px;
            padding: 10px 14px;
            background: rgba(0,200,83,0.04);
            border-radius: 10px;
            border: 1px solid var(--border);
        }
        .calendar-mini-legend span {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 0.72rem;
            color: var(--text-sec);
            font-weight: 500;
        }
        .calendar-mini-legend .dot {
            width: 8px; height: 8px;
            border-radius: 50%;
            flex-shrink: 0;
        }
        .calendar-mini-legend .dot.reunion { background: #3b82f6; }
        .calendar-mini-legend .dot.examen { background: #ef4444; }
        .calendar-mini-legend .dot.vacances { background: #10b981; }
        .calendar-mini-legend .dot.autre { background: #8b5cf6; }
        .calendar-mini-legend .dot.scolaire { background: #f59e0b; }
        @media (max-width: 760px) {
            .insight-card, .chart-container { padding: .82rem; margin: 1.15rem 0; }
            .chart-container h2, .dashboard-section-title h2 { font-size: 1rem; }
            #calendar { padding:.6rem; border-radius:14px; }
            .fc .fc-toolbar { gap:.4rem!important; flex-wrap:wrap!important; }
            .fc .fc-toolbar-title { font-size:1rem!important; }
            .fc .fc-button { padding:5px 9px!important; border-radius:7px!important; font-size:.72rem!important; min-height:32px!important; }
            .fc .fc-daygrid-day-frame { min-height:36px!important; }
            .fc .fc-daygrid-day-number { font-size:.7rem!important; padding:3px 5px!important; min-width:24px!important; }
            .fc .fc-col-header-cell-cushion { font-size:.64rem!important; padding:4px 2px!important; }
            .fc .fc-event { font-size:.62rem!important; padding:1px 4px!important; border-radius:5px!important; border-left-width:2px!important; }
            .calendar-mini-legend { gap:8px; padding:8px 10px; }
            .calendar-mini-legend span { font-size:.66rem; }
        }
        @media (max-width: 380px) {
            #calendar { padding:.35rem; }
            .fc .fc-daygrid-day-frame { min-height:32px!important; }
            .fc .fc-button { padding:4px 6px!important; font-size:.66rem!important; }
            .calendar-mini-legend { flex-direction:column; gap:4px; }
        }
        .chart-wrapper {
            position: relative;
            padding: 0.5rem 0;
        }
        .chart-mini-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(100px, 1fr));
            gap: 8px;
            margin-top: 14px;
        }
        .chart-mini-stat {
            text-align: center;
            padding: 10px 8px;
            background: rgba(0,200,83,0.04);
            border-radius: 10px;
            border: 1px solid var(--border);
        }
        .chart-mini-stat span {
            display: block;
            font-size: 0.65rem;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            color: var(--text-sec);
            font-weight: 600;
        }
        .chart-mini-stat strong {
            display: block;
            font-size: 1rem;
            font-weight: 800;
            color: var(--text);
            margin-top: 2px;
        }
        .chart-mini-stat .green { color: #10b981; }
        .chart-mini-stat .red { color: #ef4444; }
        .modal {
            display: none;
            position: fixed;
            inset: 0;
            z-index: 2000;
            align-items: center;
            justify-content: center;
            background: rgba(0,0,0,0.75);
        }
        .modal-content {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 16px;
            width: 92%;
            max-width: 620px;
            padding: 2rem 2.2rem 2.4rem;
            box-shadow: 0 20px 60px var(--shadow-strong);
        }
        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.8rem;
        }
        .modal-close {
            background: none;
            border: none;
            color: var(--text-sec);
            font-size: 1.8rem;
            cursor: pointer;
        }
        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
        .form-group { margin-bottom: 1.5rem; }
        .form-group label {
            display: block;
            margin-bottom: 0.6rem;
            color: var(--text-sec);
        }
        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 0.9rem 1.1rem;
            border: 1px solid var(--border);
            border-radius: 10px;
            background: var(--surface);
            color: var(--text);
        }
        .btn-submit {
            background: var(--primary);
            color: #103b2e;
            border: none;
            padding: 0.85rem 1.8rem;
            border-radius: 10px;
            font-weight: 600;
            cursor: pointer;
        }
        .btn-cancel {
            background: var(--border);
            color: var(--text);
            border: none;
            padding: 0.85rem 1.8rem;
            border-radius: 10px;
            cursor: pointer;
        }
        .modal-actions { display: flex; justify-content: flex-end; gap: 1rem; }
        .kanto {
            text-align: center;
            margin: 5rem 0 2.5rem;
            margin-top: 0px !important;
        }
        @media (max-width: 900px) {
            nav { transform: translateX(-100%); z-index: 10040; padding-top: 76px; box-shadow: 18px 0 48px rgba(0,0,0,.28); }
            nav.active { transform: translateX(0); }
            nav .logo { padding: 14px 0 18px; }
            body.novaskol-sidebar-open { overflow: hidden; }
            .form-row { grid-template-columns: 1fr; }
        }
        @media (max-width: 760px) {
            .stats {
                grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
                gap: .85rem !important;
                margin: 1.2rem 0 !important;
            }
            .stat-card {
                min-height: 132px !important;
                padding: .82rem .55rem !important;
                border-radius: 12px !important;
            }
            .stat-icon {
                width: 38px !important;
                height: 38px !important;
                border-radius: 10px !important;
                margin-bottom: .42rem !important;
                font-size: .92rem !important;
            }
            .count {
                font-size: 1.45rem !important;
                margin-bottom: .15rem !important;
            }
            .stat-card h2:not(.count) {
                font-size: 1.12rem !important;
                line-height: 1.12 !important;
                overflow-wrap: anywhere;
            }
            .stat-card p {
                font-size: .76rem !important;
                line-height: 1.12 !important;
                margin: .12rem 0 !important;
            }
            .stat-card small {
                font-size: .66rem !important;
                line-height: 1.12 !important;
            }
        }
        @media (max-width: 380px) {
            .stats { gap: .6rem !important; }
            .stat-card { min-height: 126px !important; padding: .72rem .45rem !important; }
        }
    </style>
</head>
<body>
@php
    $legacyBase = (config('app.env') === 'production' ? url('/') : 'http://localhost/novaskol').'/';
    $logo = $ecole->logo ?? 'logo.png';
    $logoPath = str_starts_with($logo, 'images/') ? substr($logo, 7) : $logo;
    $currentUserId = (int) session('utilisateur.id', 0);
    $currentUser = $currentUserId && Schema::hasTable('utilisateurs') ? DB::table('utilisateurs')->where('id', $currentUserId)->first() : null;
    $userAvatar = trim((string) ($currentUser->avatar ?? ''));
    $userAvatarUrl = $userAvatar === '' ? asset('legacy/images/default-avatar.png') : ((str_starts_with($userAvatar, 'images/') || str_starts_with($userAvatar, 'uploads/')) ? asset('legacy/'.$userAvatar) : asset('legacy/uploads/avatars/'.$userAvatar));
    $hasMessages = Schema::hasTable('messages');
    $latestMessages = $currentUserId && $hasMessages ? DB::table('messages as m')->join('conversation_participants as cp', 'cp.conversation_id', '=', 'm.conversation_id')->join('conversations as c', 'c.id', '=', 'm.conversation_id')->leftJoin('utilisateurs as u', 'u.id', '=', 'm.sender_id')->where('cp.user_id', $currentUserId)->where('m.sender_id', '!=', $currentUserId)->select('m.*', 'u.nom as sender_name', 'c.type as conversation_type', 'c.name as conversation_name')->orderByDesc('m.created_at')->limit(6)->get() : collect();
    $unreadMessages = $currentUserId && $hasMessages ? DB::table('messages as m')->join('conversation_participants as cp', 'cp.conversation_id', '=', 'm.conversation_id')->where('cp.user_id', $currentUserId)->where('m.sender_id', '!=', $currentUserId)->where('m.is_read', 0)->count() : 0;
    $dashboardLinks = [
        'students' => route('modules.inscription'),
        'teachers' => route('modules.enseignants'),
        'staff' => route('modules.staff'),
        'teacherPresence' => route('modules.presence'),
        'paymentDetails' => route('modules.detail-paiement'),
        'notes' => route('modules.notes'),
        'studentPresence' => route('modules.presence-etudiant'),
        'bulletin' => route('modules.bulletin'),
        'notifications' => route('modules.notifications'),
    ];
@endphp
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
            <a href="{{ $href }}" @class(['active' => $module === 'dashboard' || (! empty($info['route']) && request()->routeIs($info['route']))])>
                <i class="fa {{ $info['icon'] }}"></i> <span>{{ $info['label'] }}</span>
            </a>
        @endif
    @endforeach
    @if ($openSub)
        </div>
    @endif

</nav>

<header id="main-header">
    <div class="header-left">
        <button title="Cacher les modules" class="burger-menu" onclick="toggleSidebar()"><i class="fa fa-bars"></i></button>
        <button title="Plein ecran" id="fullscreen-btn" onclick="toggleFullscreen()"><i id="fullscreen-icon" class="fa fa-expand"></i></button>
    </div>
    <div class="header-center"><i class="fas fa-tachometer-alt"></i> Tableau de bord</div>
    <div class="header-right">
        <div class="lang-wrap">
            <button type="button" title="Langue" class="lang-trigger" onclick="toggleDashboardDrop('languageDropdown')"><i class="fa fa-language"></i></button>
            <div class="lang-dropdown" id="languageDropdown">
                @foreach (['fr' => 'FR', 'en' => 'EN', 'de' => 'DE', 'mg' => 'MG', 'es' => 'ES', 'pt' => 'PT'] as $langCode => $langLabel)
                    <button type="button" class="lang-option @if($interfaceLanguage === $langCode) active @endif" data-lang-option data-lang-code="{{ $langCode }}" onclick="novaskolSetLanguage('{{ $langCode }}')">{{ $langLabel }}</button>
                @endforeach
            </div>
        </div>
        <button type="button" title="Changer de theme" class="theme-toggle" id="themeToggle">
            <i class="fas fa-moon"></i>
        </button>
        <button type="button" title="Notifications non lues" class="notif-bell" onclick="toggleNotif()">
            <i class="fa fa-bell"></i>
            <span class="notif-badge" id="notifBadge" style="display: {{ $unreadCount > 0 ? 'flex' : 'none' }};">{{ $unreadCount }}</span>
        </button>
        <button type="button" title="Messages non lus" class="message-bell" onclick="toggleDashboardDrop('messageDropdown')">
            <i class="fa fa-comments"></i>
            <span class="notif-badge" style="display: {{ $unreadMessages > 0 ? 'flex' : 'none' }};">{{ $unreadMessages }}</span>
        </button>
        <button type="button" title="Profil" class="profile-trigger" onclick="toggleDashboardDrop('profileDropdown')"><img src="{{ $userAvatarUrl }}" alt="Profil"></button>
    </div>
    <div class="notif-dropdown" id="notifDropdown">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:12px;">
            <h4 style="margin:0; color:var(--primary);">Notifications</h4>
            @if ($unreadCount > 0)
                <button class="kaly mark-all-read-btn" onclick="markAllAsRead()">Tout marquer lu</button>
            @endif
        </div>
        @forelse ($notifications as $notification)
            <div class="notif-item" data-id="{{ $notification->id }}" @style(['background:rgba(0,200,83,0.08)' => ! $notification->lu])>
                <div style="color: green !important;" class="type">[{{ ucfirst($notification->type) }}]</div>
                <div>{{ $notification->message }}</div>
                <div style="color: #256a56 !important;" class="date">{{ \Carbon\Carbon::parse($notification->date_creation)->format('d/m/Y H:i') }}</div>
                <div style="margin-top:8px; text-align:right;">
                    @if (! $notification->lu)
                        <button class="kaly1 mark-read-btn" onclick="markAsRead({{ $notification->id }}, this)">Lu</button>
                    @endif
                    <button class="kaly2 delete-notif-btn" onclick="deleteNotif({{ $notification->id }}, this)">Supprimer</button>
                </div>
            </div>
        @empty
            <div class="notif-item">Aucune notification.</div>
        @endforelse
        <div style="text-align:center; margin-top:12px;">
            <a href="{{ $dashboardLinks['notifications'] }}" style="color:var(--primary);">Voir toutes les notifications</a>
        </div>
    </div>
    <div class="notif-dropdown" id="messageDropdown">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:12px;"><h4 style="margin:0; color:var(--primary);">Messages</h4><a href="{{ route('modules.chat-prive') }}" style="color:var(--primary);">Ouvrir chat</a></div>
        @forelse($latestMessages as $message)
            <div class="notif-item"><strong>{{ $message->conversation_type === 'group' ? ($message->conversation_name ?: 'Groupe') : ($message->sender_name ?: 'Utilisateur') }}</strong><div>{{ $message->type === 'text' ? \Illuminate\Support\Str::limit($message->content, 90) : ucfirst($message->type).' recu' }}</div><div class="date">{{ $message->created_at ? \Carbon\Carbon::parse($message->created_at)->format('d/m/Y H:i') : '' }}</div></div>
        @empty
            <div class="notif-item">Aucun message recent.</div>
        @endforelse
    </div>
    <div class="profile-dropdown" id="profileDropdown">
        <div class="profile-card"><img src="{{ $userAvatarUrl }}" alt="Profil"><h3>{{ $currentUser->nom ?? session('utilisateur.nom', 'Utilisateur') }}</h3><small>{{ $currentUser->email ?? session('utilisateur.email', '') }} - {{ $currentUser->role ?? session('utilisateur.role', '') }}</small></div>
        <div class="profile-tabs"><button type="button" onclick="dashboardProfileTab('dashProfileInfo')">Profil</button><button type="button" onclick="dashboardProfileTab('dashProfilePass')">Compte</button></div>
        <form id="dashProfileInfo" class="profile-pane active" method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">@csrf<label>Nom</label><input name="nom" value="{{ $currentUser->nom ?? '' }}" required><label>Email</label><input type="email" name="email" value="{{ $currentUser->email ?? '' }}" required><label>Photo de profil</label><input type="file" name="avatar" accept="image/*"><div class="profile-actions"><button class="global-primary">Enregistrer</button></div></form>
        <form id="dashProfilePass" class="profile-pane" method="POST" action="{{ route('profile.password') }}">@csrf<label>Nouveau mot de passe</label><input type="password" name="mot_de_passe" required minlength="6"><label>Confirmation</label><input type="password" name="mot_de_passe_confirmation" required minlength="6"><div class="profile-actions"><button class="global-primary">Modifier</button></div></form>
        <form method="POST" action="{{ route('logout') }}" class="profile-actions">@csrf<button class="global-danger"><i class="fa fa-power-off"></i> Deconnexion</button></form>
    </div>
</header>
<div class="novaskol-loader" id="novaskolLoader"><div class="loader-box"><div class="loader-ring"></div><strong>Chargement...</strong></div></div>

<main id="main-content">
    <form method="get" id="annee-form" class="kanto">
        <select title="Filtrer de l'annee scolaire" name="annee_scolaire" onchange="this.form.submit()" style="cursor: pointer;padding:0.9rem 1.2rem; border-radius:12px; background:var(--card); color:var(--text); border:1px solid var(--border); font-size:1.05rem; min-width:230px; box-shadow:0 10px 26px var(--shadow-soft);">
            @foreach ($annees as $annee)
                <option value="{{ $annee['annee_scolaire'] }}" @selected($annee['annee_scolaire'] == $anneeScolaire)>
                    {{ $annee['annee_scolaire'] }}
                </option>
            @endforeach
        </select>
    </form>

    @if(config('app.connected_mode'))
    <div class="connecte-header-actions">
        <button onclick="runSync()" id="connecte-sync-btn" title="Synchroniser" class="connecte-btn connecte-btn-sync">&#x21BB; Synchroniser</button>
        <button onclick="showAccountMenu()" title="Compte" class="connecte-btn connecte-btn-compte">&#x2630; Compte</button>
    </div>
    @endif

    <div class="dashboard-section-title">
        <h2>Vue rapide de l'etablissement</h2>
        <span>Acces direct aux espaces les plus utilises.</span>
    </div>

    <div class="stats">
        <a href="{{ $dashboardLinks['students'] }}" class="stat-card stat-card-link">
            <div class="stat-icon"><i class="fas fa-user-graduate"></i></div>
            <h2 class="count" data-target="{{ $totaleleves }}">0</h2>
            <p>Eleves inscrits</p>
            <small>Acceder aux inscriptions</small>
        </a>
        <a href="{{ $dashboardLinks['teachers'] }}" class="stat-card stat-card-link">
            <div class="stat-icon"><i class="fas fa-chalkboard-teacher"></i></div>
            <h2 class="count" data-target="{{ $totalenseignants }}">0</h2>
            <p>Enseignants</p>
            <small>Gerer le corps enseignant</small>
        </a>
        <a href="{{ $dashboardLinks['staff'] }}" class="stat-card stat-card-link">
            <div class="stat-icon"><i class="fas fa-users"></i></div>
            <h2 class="count" data-target="{{ $totalstaff }}">0</h2>
            <p>Staff</p>
            <small>Voir le personnel administratif</small>
        </a>
        <a href="{{ $dashboardLinks['students'] }}" class="stat-card stat-card-link">
            <div class="stat-icon"><i class="fas fa-people-roof"></i></div>
            <h2 class="count" data-target="{{ $totalparent }}">0</h2>
            <p>Parents</p>
            <small>Suivi des comptes familles</small>
        </a>
        <a href="{{ $dashboardLinks['teacherPresence'] }}" class="stat-card stat-card-link">
            <div class="stat-icon"><i class="fas fa-calendar-check"></i></div>
            <h2 class="count" data-target="{{ round($presenceToday) }}">{{ round($presenceToday) }}%</h2>
            <p>Presence aujourd'hui</p>
            <small>Consulter la presence du personnel</small>
        </a>
        <a href="{{ $dashboardLinks['paymentDetails'] }}" class="stat-card stat-card-link">
            <div class="stat-icon"><i class="fas fa-file-invoice-dollar"></i></div>
            <h2 class="count" data-target="{{ $impayeCount }}">{{ $impayeCount }}</h2>
            <p>Impayes en cours</p>
            <small>Ouvrir les details de paiement</small>
        </a>
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-calendar-alt"></i></div>
            <h2 style="color:#10b981;font-size: 2.2rem;font-weight: 800;">{{ $anneeScolaire }}</h2>
            <p>Annee scolaire active</p>
            <small>Filtre utilise par le tableau de bord</small>
        </div>
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-chart-line"></i></div>
            <h2 style="color:{{ $benefice >= 0 ? '#10b981' : '#ef4444' }}; font-size: 2.2rem;font-weight: 800;">{{ number_format($benefice, 0, ',', ' ') }} {{ novaskol_currency() }}</h2>
            <p>Benefice annuel</p>
            <small>Performance globale</small>
        </div>
    </div>

    <div class="dashboard-section-title">
        <h2>Actions rapides</h2>
        <span>Les operations quotidiennes les plus importantes.</span>
    </div>

    <div class="quick-actions">
        <a href="{{ $dashboardLinks['students'] }}" class="action-btn"><i class="fas fa-user-plus fa-2x"></i><br>Ajouter eleve</a>
        <a href="{{ $dashboardLinks['notes'] }}" class="action-btn"><i class="fas fa-book fa-2x"></i><br>Saisir notes</a>
        <a href="{{ $dashboardLinks['studentPresence'] }}" class="action-btn"><i class="fas fa-check-circle fa-2x"></i><br>Marquer presence</a>
        <a href="{{ $dashboardLinks['bulletin'] }}" class="action-btn"><i class="fas fa-file-alt fa-2x"></i><br>Generer bulletin</a>
        <a href="{{ $dashboardLinks['notifications'] }}" class="action-btn"><i class="fas fa-bell fa-2x"></i><br>Envoyer notification</a>
    </div>

    <div class="insight-card">
        <strong>Prochain evenement :</strong>
        @if ($prochainEvent)
            {{ $prochainEvent->titre }} - {{ \Carbon\Carbon::parse($prochainEvent->date_debut)->format('d M Y à H:i') }}
        @else
            Aucun evenement prevu
        @endif
    </div>

    <div class="chart-container">
        <h2><i class="fas fa-chart-line" style="color:var(--primary);margin-right:6px;"></i> Revenus vs Depenses ({{ $anneeScolaire }})</h2>
        <canvas id="financeChart"></canvas>
        <div class="chart-mini-stats">
            <div class="chart-mini-stat">
                <span>Total Revenus</span>
                <strong class="green">{{ number_format(array_sum($revenusMensuels), 0, ',', ' ') }} {{ novaskol_currency() }}</strong>
            </div>
            <div class="chart-mini-stat">
                <span>Total Depenses</span>
                <strong class="red">{{ number_format(array_sum($depensesMensuelles), 0, ',', ' ') }} {{ novaskol_currency() }}</strong>
            </div>
            <div class="chart-mini-stat">
                <span>Solde</span>
                <strong class="{{ ($totalRevenus ?? 0) - ($totalDepenses ?? 0) >= 0 ? 'green' : 'red' }}">
                    {{ number_format(($totalRevenus ?? 0) - ($totalDepenses ?? 0), 0, ',', ' ') }} {{ novaskol_currency() }}
                </strong>
            </div>
        </div>
    </div>

    <div class="chart-container">
        <div class="calendar-header-accent">
            <h2><i class="fas fa-calendar-alt" style="color:var(--primary);"></i> Calendrier academique</h2>
            <span class="cal-badge"><i class="fas fa-mouse-pointer"></i> Cliquez pour ajouter</span>
        </div>
        <div title="Cliquez sur un jour pour ajouter un evenement" id="calendar"></div>
        <div class="calendar-mini-legend">
            <span><span class="dot reunion"></span> Reunion</span>
            <span><span class="dot examen"></span> Examen</span>
            <span><span class="dot vacances"></span> Vacances</span>
            <span><span class="dot autre"></span> Autre</span>
            <span><span class="dot scolaire"></span> Evenement scolaire</span>
        </div>
    </div>

    <div class="modal" id="eventModal">
        <div class="modal-overlay" onclick="closeModal()"></div>
        <div class="modal-content">
            <div class="modal-header">
                <h3>Ajouter un evenement</h3>
                <button class="modal-close" onclick="closeModal()">x</button>
            </div>
            <form id="eventForm">
                <div class="form-group">
                    <label for="titre">Titre de l'evenement</label>
                    <input type="text" id="titre" name="titre" placeholder="Ex : Reunion parents-profs" required>
                </div>
                <div class="form-group">
                    <label for="type">Type</label>
                    <select id="type" name="type" required>
                        <option value="" disabled selected>Choisir le type</option>
                        <option value="reunion">Reunion</option>
                        <option value="examen">Examen</option>
                        <option value="vacances">Vacances</option>
                        <option value="autre">Autre</option>
                    </select>
                </div>
                <div class="form-row">
                    <div class="form-group half">
                        <label for="date_debut">Date et heure de debut</label>
                        <input type="datetime-local" id="date_debut" name="date_debut" required>
                    </div>
                    <div class="form-group half">
                        <label for="date_fin">Date et heure de fin</label>
                        <input type="datetime-local" id="date_fin" name="date_fin" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="description">Description (facultatif)</label>
                    <textarea id="description" name="description" rows="4" placeholder="Details, lieu, participants..."></textarea>
                </div>
                <div class="modal-actions">
                    <button type="button" class="btn-cancel" onclick="closeModal()">Annuler</button>
                    <button type="submit" class="btn-submit">Ajouter l'evenement</button>
                </div>
            </form>
        </div>
    </div>

    <footer>
        &copy; {{ date('Y') }} {{ $ecole->nom ?? 'Ecole' }}. Tous droits reserves.
    </footer>
</main>

<script>
const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

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
function dashboardSetHeaderButtonIcons() {
    const sidebar = document.querySelector('nav');
    const mobile = window.innerWidth <= 1100;
    const hidden = sidebar ? (sidebar.classList.contains('hidden') || (mobile && !sidebar.classList.contains('active'))) : false;
    document.querySelectorAll('.burger-menu').forEach(btn => {
        btn.type = 'button';
        btn.title = hidden ? 'Afficher les modules' : 'Cacher les modules';
        if (!btn.querySelector('i')) btn.innerHTML = '<i class="fa fa-bars"></i>';
        btn.querySelectorAll('i').forEach(icon => {
            icon.classList.remove('fa-times', 'fa-close', 'fa-chevron-left', 'fa-chevron-right', 'fa-navicon');
            icon.classList.add('fa', 'fa-bars');
        });
    });
    const full = !!document.fullscreenElement;
    document.querySelectorAll('#fullscreen-btn').forEach(btn => {
        btn.type = 'button';
        btn.title = full ? 'Quitter le plein ecran' : 'Plein ecran';
        if (!btn.querySelector('i')) btn.innerHTML = '<i id="fullscreen-icon" class="fa fa-expand"></i>';
        btn.querySelectorAll('i').forEach(icon => {
            icon.classList.remove('fa-expand', 'fa-compress', 'fa-arrows-alt');
            icon.classList.add('fa', full ? 'fa-compress' : 'fa-expand');
        });
    });
}
function toggleSidebar() {
    const sidebar = document.querySelector('nav');
    if (!sidebar) return;
    const mainContent = document.querySelector('main');
    const header = document.querySelector('header');
    const mobile = window.innerWidth <= 1100;
    if (mobile) {
        sidebar.classList.toggle('active');
        document.body.classList.toggle('novaskol-sidebar-open', sidebar.classList.contains('active'));
    } else {
        document.body.classList.remove('novaskol-sidebar-open');
        const hiddenNext = !sidebar.classList.contains('hidden');
        sidebar.classList.toggle('hidden', hiddenNext);
        sidebar.classList.toggle('active', !hiddenNext);
        mainContent?.classList.toggle('full-width', hiddenNext);
        header?.classList.toggle('full-width', hiddenNext);
    }
    dashboardSetHeaderButtonIcons();
}
function closeDashboardMobileSidebar() {
    const sidebar = document.querySelector('nav');
    if (!sidebar) return;
    sidebar.classList.remove('active');
    document.body.classList.remove('novaskol-sidebar-open');
    dashboardSetHeaderButtonIcons();
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
function toggleNotif() {
    const dropdown = document.getElementById('notifDropdown');
    dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
    document.getElementById('messageDropdown').style.display = 'none';
    document.getElementById('profileDropdown').classList.remove('active');
}
function toggleDashboardDrop(id) {
    document.getElementById('notifDropdown').style.display = 'none';
    document.getElementById('messageDropdown').style.display = id === 'messageDropdown' && document.getElementById('messageDropdown').style.display !== 'block' ? 'block' : 'none';
    document.getElementById('profileDropdown').classList.toggle('active', id === 'profileDropdown' && !document.getElementById('profileDropdown').classList.contains('active'));
    document.getElementById('languageDropdown').classList.toggle('active', id === 'languageDropdown' && !document.getElementById('languageDropdown').classList.contains('active'));
    if (id !== 'messageDropdown') document.getElementById('messageDropdown').style.display = 'none';
    if (id !== 'profileDropdown') document.getElementById('profileDropdown').classList.remove('active');
    if (id !== 'languageDropdown') document.getElementById('languageDropdown').classList.remove('active');
}
function dashboardProfileTab(id) {
    document.querySelectorAll('.profile-pane').forEach(p => p.classList.remove('active'));
    document.getElementById(id)?.classList.add('active');
}
function dashboardShowLoader() {
    document.getElementById('novaskolLoader')?.classList.add('active');
}
function dashboardHideLoader() {
    document.getElementById('novaskolLoader')?.classList.remove('active');
}
function markAsRead(id, btn) {
    fetch(`/dashboard/notifications/${id}/read`, {
        method: 'POST',
        headers: {'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json'}
    })
        .then(r => r.json())
        .then(res => {
            if (res.success) {
                btn.closest('.notif-item').style.background = '';
                btn.remove();
                const badge = document.getElementById('notifBadge');
                badge.textContent = res.new_count;
                badge.style.display = res.new_count > 0 ? 'flex' : 'none';
            }
        });
}
async function deleteNotif(id, btn) {
    const ok = await Swal.fire({
        icon: 'warning',
        title: 'Supprimer cette notification ?',
        text: 'Cette notification sera supprimee.',
        showCancelButton: true,
        confirmButtonText: 'Supprimer',
        cancelButtonText: 'Annuler',
        confirmButtonColor: '#00c853',
        cancelButtonColor: '#ef4444'
    });
    if (!ok.isConfirmed) return;
    fetch(`/dashboard/notifications/${id}`, {
        method: 'DELETE',
        headers: {'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json'}
    })
        .then(r => r.json())
        .then(res => {
            if (res.success) {
                btn.closest('.notif-item').remove();
                const badge = document.getElementById('notifBadge');
                badge.textContent = res.new_count;
                badge.style.display = res.new_count > 0 ? 'flex' : 'none';
            }
        });
}
function markAllAsRead() {
    fetch('/dashboard/notifications/read-all', {
        method: 'POST',
        headers: {'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json'}
    })
        .then(r => r.json())
        .then(res => {
            if (res.success) {
                document.querySelectorAll('.notif-item').forEach(item => {
                    item.style.background = '';
                    const btn = item.querySelector('.mark-read-btn');
                    if (btn) btn.remove();
                });
                document.getElementById('notifBadge').style.display = 'none';
                document.querySelector('.mark-all-read-btn')?.remove();
            }
        });
}
document.addEventListener('click', e => {
    if (!e.target.closest('.notif-bell') && !e.target.closest('.message-bell') && !e.target.closest('.profile-trigger') && !e.target.closest('.lang-trigger') && !e.target.closest('.notif-dropdown') && !e.target.closest('.profile-dropdown') && !e.target.closest('.lang-dropdown')) {
        document.getElementById('notifDropdown').style.display = 'none';
        document.getElementById('messageDropdown').style.display = 'none';
        document.getElementById('profileDropdown').classList.remove('active');
        document.getElementById('languageDropdown').classList.remove('active');
    }
});
document.addEventListener('submit', e => { setTimeout(() => { if (!e.defaultPrevented && !e.target.hasAttribute('data-no-loader')) dashboardShowLoader(); }, 0); });
document.addEventListener('click', e => { const a = e.target.closest('a'); if (a && a.href && !a.target && a.getAttribute('href') !== '#' && !a.hasAttribute('download')) dashboardShowLoader(); });
window.addEventListener('pageshow', dashboardHideLoader);
if (window.jQuery) jQuery(document).ajaxStop(dashboardHideLoader);
const counters = document.querySelectorAll('.count');
counters.forEach(c => {
    const target = +c.dataset.target;
    let count = 0;
    const inc = Math.max(target / 60, 1);
    const update = () => {
        count += inc;
        c.textContent = Math.ceil(count) < target ? Math.ceil(count) : target;
        if (count < target) requestAnimationFrame(update);
    };
    new IntersectionObserver(([e]) => { if (e.isIntersecting) update(); }, {threshold:0.5}).observe(c.parentElement);
});
function getChartTheme() {
    const isDark = !document.documentElement.classList.contains('light');
    return {
        isDark,
        textColor: isDark ? '#e5e7eb' : '#0f172a',
        gridColor: isDark ? 'rgba(255,255,255,0.06)' : 'rgba(0,0,0,0.07)',
        revenuGrad: ['rgba(16,185,129,0.85)', 'rgba(16,185,129,0.15)'],
        depenseGrad: ['rgba(239,68,68,0.85)', 'rgba(239,68,68,0.15)']
    };
}

let chartInstance = null;

function initChart() {
    const chartCanvas = document.getElementById('financeChart');
    if (!chartCanvas) return;
    if (chartInstance) { chartInstance.destroy(); chartInstance = null; }
    const chartCtx = chartCanvas.getContext('2d');
    const theme = getChartTheme();
    
    const revenuGradient = chartCtx.createLinearGradient(0, 0, 0, 300);
    revenuGradient.addColorStop(0, theme.revenuGrad[0]);
    revenuGradient.addColorStop(1, theme.revenuGrad[1]);
    
    const depenseGradient = chartCtx.createLinearGradient(0, 0, 0, 300);
    depenseGradient.addColorStop(0, theme.depenseGrad[0]);
    depenseGradient.addColorStop(1, theme.depenseGrad[1]);

chartInstance = new Chart(chartCtx, {
    type: 'bar',
    data: {
        labels: @json($mois),
        datasets: [
            {
                label: 'Revenus',
                data: @json($revenusMensuels),
                backgroundColor: revenuGradient,
                borderColor: '#10b981',
                borderWidth: 2,
                borderRadius: 6,
                barPercentage: 0.6,
                categoryPercentage: 0.75,
                hoverBackgroundColor: 'rgba(16,185,129,0.95)',
                hoverBorderColor: '#059669',
                hoverBorderWidth: 2
            },
            {
                label: 'Depenses',
                data: @json($depensesMensuelles),
                backgroundColor: depenseGradient,
                borderColor: '#ef4444',
                borderWidth: 2,
                borderRadius: 6,
                barPercentage: 0.6,
                categoryPercentage: 0.75,
                hoverBackgroundColor: 'rgba(239,68,68,0.95)',
                hoverBorderColor: '#dc2626',
                hoverBorderWidth: 2
            }
        ]
    },
    options: {
        responsive: true,
        animation: {
            duration: 1200,
            easing: 'easeOutQuart'
        },
        transitions: {
            active: {
                animation: { duration: 150 }
            }
        },
        plugins: {
            legend: {
                position: 'top',
                labels: {
                    color: theme.textColor,
                    font: { weight: '700', size: 12 },
                    padding: 20,
                    usePointStyle: true,
                    pointStyle: 'circle',
                    boxWidth: 10,
                    boxHeight: 10
                }
            },
            tooltip: {
                backgroundColor: theme.isDark ? 'rgba(15,23,42,0.96)' : 'rgba(255,255,255,0.97)',
                titleColor: theme.isDark ? '#f1f5f9' : '#0f172a',
                bodyColor: theme.isDark ? '#94a3b8' : '#475569',
                borderColor: theme.isDark ? 'rgba(255,255,255,0.06)' : 'rgba(0,0,0,0.06)',
                borderWidth: 1,
                padding: 16,
                cornerRadius: 14,
                boxPadding: 10,
                usePointStyle: true,
                caretSize: 10,
                caretPadding: 6,
                titleFont: { weight: '700', size: 13 },
                bodyFont: { size: 12 },
                callbacks: {
                    label: function(context) {
                        const label = context.dataset.label || '';
                        const val = context.raw || 0;
                        return label + ': ' + new Intl.NumberFormat('fr-FR').format(val) + ' {{ novaskol_currency() }}';
                    },
                    afterLabel: function(context) {
                        const total = context.dataset.data.reduce((a,b) => a + b, 0);
                        const pct = total > 0 ? ((context.raw / total) * 100).toFixed(1) : 0;
                        return 'Part: ' + pct + '%';
                    }
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    color: theme.textColor,
                    font: { size: 12, weight: '700' },
                    padding: 10,
                    callback: function(value) {
                        return new Intl.NumberFormat('fr-FR', { notation: 'compact' }).format(value);
                    }
                },
                grid: { color: theme.gridColor, drawBorder: false }
            },
            x: {
                ticks: { color: theme.textColor, font: { size: 12, weight: '700' }, padding: 8 },
                grid: { display: false }
            }
        },
        interaction: {
            mode: 'index',
            intersect: false
        },
        hover: {
            mode: 'index',
            intersect: false,
            animationDuration: 200
        },
        datasets: {
            bar: {
                maxBarThickness: 32
            }
        }
    }
});

} // end initChart

document.addEventListener('DOMContentLoaded', initChart);
document.addEventListener('themeChanged', function() {
    setTimeout(initChart, 50);
});
const calendar = new FullCalendar.Calendar(document.getElementById('calendar'), {
    initialView: 'dayGridMonth',
    locale: 'fr',
    height: window.innerWidth <= 760 ? 390 : 'auto',
    contentHeight: window.innerWidth <= 760 ? 300 : 'auto',
    aspectRatio: window.innerWidth <= 760 ? 1.06 : 1.35,
    dayMaxEvents: window.innerWidth <= 760 ? 1 : true,
    headerToolbar: window.innerWidth <= 760
        ? { left: 'prev,next', center: 'title', right: 'today' }
        : { left: 'prev,next today', center: 'title', right: 'dayGridMonth,timeGridWeek' },
    events: '{{ route('dashboard.events') }}',
    eventClassNames: function(arg) {
        const type = arg.event.extendedProps.type || 'autre';
        return ['event-' + type];
    },
    eventDidMount: function(info) {
        const el = info.el;
        const type = info.event.extendedProps.type || 'autre';
        const typeLabels = { reunion: 'Reunion', examen: 'Examen', vacances: 'Vacances', autre: 'Evenement', 'evenement-scolaire': 'Scolaire' };
        const icons = { reunion: 'fa-handshake', examen: 'fa-pencil-alt', vacances: 'fa-umbrella-beach', autre: 'fa-calendar-star', 'evenement-scolaire': 'fa-school' };
        const icon = icons[type] || 'fa-calendar';
        const titleEl = el.querySelector('.fc-event-title');
        if (titleEl) {
            const iconEl = document.createElement('i');
            iconEl.className = 'fas ' + icon;
            iconEl.style.marginRight = '5px';
            iconEl.style.fontSize = '0.6rem';
            iconEl.style.opacity = '0.9';
            titleEl.prepend(iconEl);
        }
        const desc = info.event.extendedProps.description || '';
        el.title = (typeLabels[type] || type) + ': ' + info.event.title + (desc ? ' - ' + desc : '');
        el.style.cursor = 'pointer';
        if (info.event.extendedProps.type === 'examen') {
            el.style.animation = 'pulse-event 2s ease-in-out infinite';
        }
    },
    loading: function(isLoading) {
        if (!isLoading) {
            document.querySelectorAll('.fc-event').forEach(el => {
                el.addEventListener('mouseenter', function() { this.style.zIndex = '10'; });
                el.addEventListener('mouseleave', function() { this.style.zIndex = ''; });
            });
        }
    },
    dateClick: function(info) {
        openDashboardEventModal(info.dateStr);
    }
});
calendar.render();

const styleSheet = document.createElement('style');
styleSheet.textContent = `@keyframes pulse-event { 0%,100%{opacity:1} 50%{opacity:0.85} }`;
document.head.appendChild(styleSheet);
window.addEventListener('resize', () => {
    const mobile = window.innerWidth <= 760;
    calendar.setOption('height', mobile ? 390 : 'auto');
    calendar.setOption('contentHeight', mobile ? 300 : 'auto');
    calendar.setOption('aspectRatio', mobile ? 1.06 : 1.35);
    calendar.setOption('dayMaxEvents', mobile ? 1 : true);
    calendar.setOption('headerToolbar', mobile ? { left: 'prev,next', center: 'title', right: 'today' } : { left: 'prev,next today', center: 'title', right: 'dayGridMonth,timeGridWeek' });
});

function openDashboardEventModal(dateStr) {
    const start = `${dateStr}T09:00`;
    const end = `${dateStr}T17:00`;
    document.getElementById('date_debut').value = start;
    document.getElementById('date_fin').value = end;
    document.getElementById('eventModal').style.display = 'flex';
    setTimeout(() => document.getElementById('titre')?.focus(), 80);
}

document.getElementById('eventForm').onsubmit = async function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    try {
        const response = await fetch('{{ route('dashboard.events.store') }}', {
            method: 'POST',
            body: formData,
            headers: {'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json'}
        });
        const res = await response.json();
        if (!response.ok) {
            const message = res.message || Object.values(res.errors || {}).flat().join('\n') || 'Impossible d ajouter cet evenement.';
            throw new Error(message);
        }
        if (res.success) {
            if (res.event) {
                calendar.getEventById(String(res.event.id))?.remove();
                calendar.addEvent(res.event);
            }
            Swal.fire({
                title: 'Succes !',
                text: 'Evenement ajoute',
                icon: 'success',
                timer: 1500,
                showConfirmButton: false
            });
            document.getElementById('eventModal').style.display = 'none';
            document.getElementById('eventForm').reset();
        } else {
            Swal.fire('Erreur', 'Impossible d ajouter', 'error');
        }
    } catch (error) {
        Swal.fire('Erreur', error.message || 'Probleme serveur', 'error');
    }
};
function closeModal() {
    document.getElementById('eventModal').style.display = 'none';
}
function toggleFullscreen() {
    if (!document.fullscreenElement) {
        document.documentElement.requestFullscreen?.().catch(err => console.error(err));
    } else {
        document.exitFullscreen?.().catch(err => console.error(err));
    }
    setTimeout(dashboardSetHeaderButtonIcons, 120);
}
document.addEventListener('fullscreenchange', dashboardSetHeaderButtonIcons);
window.addEventListener('resize', dashboardSetHeaderButtonIcons);
window.addEventListener('resize', () => {
    if (window.innerWidth > 1100) document.body.classList.remove('novaskol-sidebar-open');
});
document.addEventListener('click', e => {
    const sidebar = document.querySelector('nav');
    if (window.innerWidth <= 1100 && sidebar?.classList.contains('active') && !e.target.closest('nav,#sidebar,.burger-menu')) {
        closeDashboardMobileSidebar();
    }
});
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.burger-menu,#fullscreen-btn').forEach(btn => {
        btn.onclick = null;
        btn.removeAttribute('onclick');
    });
    document.querySelectorAll('.burger-menu').forEach(btn => btn.addEventListener('click', e => {
        e.preventDefault();
        e.stopPropagation();
        toggleSidebar();
    }));
    document.querySelectorAll('#fullscreen-btn').forEach(btn => btn.addEventListener('click', e => {
        e.preventDefault();
        e.stopPropagation();
        toggleFullscreen();
    }));
    dashboardSetHeaderButtonIcons();
    const toggleBtn = document.getElementById('themeToggle');
    const root = document.documentElement;
    const icon = toggleBtn.querySelector('i');
    const storedTheme = localStorage.getItem('novaskol-theme') || localStorage.getItem('theme');
    if (storedTheme === 'light') {
        root.classList.add('light');
        icon.classList.replace('fa-moon', 'fa-sun');
    }
    toggleBtn.addEventListener('click', () => {
        if (root.classList.contains('light')) {
            root.classList.remove('light');
            icon.classList.replace('fa-sun', 'fa-moon');
            localStorage.setItem('novaskol-theme', 'dark');
            localStorage.setItem('theme', 'dark');
        } else {
            root.classList.add('light');
            icon.classList.replace('fa-moon', 'fa-sun');
            localStorage.setItem('novaskol-theme', 'light');
            localStorage.setItem('theme', 'light');
        }
        document.dispatchEvent(new Event('themeChanged'));
    });
    document.querySelectorAll('.lang-option').forEach(btn => btn.addEventListener('click', () => {
        document.getElementById('languageDropdown')?.classList.remove('active');
    }));
@if(config('app.connected_mode'))
    function showToast(msg,ok){var t=document.createElement('div');t.textContent=msg;t.style.cssText='position:fixed;top:70px;right:16px;z-index:100000;background:'+(ok?'#16a34a':'#dc2626')+';color:#fff;padding:10px 18px;border-radius:8px;font-size:13px;font-weight:600;box-shadow:0 4px 16px rgba(0,0,0,0.4);transition:opacity .3s';document.body.appendChild(t);setTimeout(function(){t.style.opacity='0';setTimeout(function(){t.remove()},400)},3000);}
    function runSync(){var btn=document.getElementById('connecte-sync-btn');if(!btn)return;btn.disabled=true;btn.style.opacity='0.5';fetch('/connected/sync/run',{headers:{'Accept':'application/json'}}).then(function(r){return r.json()}).then(function(d){var ok=d&&d.success;btn.disabled=false;btn.style.opacity='';showToast(ok?'\u2713 Synchronis\u00e9':'\u2717 Erreur: '+(d.message||'inconnue'),ok);if(ok)setTimeout(function(){location.reload()},1200);})['catch'](function(){btn.disabled=false;btn.style.opacity='';showToast('\u2717 Erreur reseau',false);});}
    function showAccountMenu(){var box=document.createElement('div');box.style.cssText='position:fixed;inset:0;z-index:100001;background:rgba(0,0,0,0.6);display:flex;align-items:center;justify-content:center';var inner=document.createElement('div');inner.style.cssText='background:#1e293b;border:1px solid #334155;border-radius:16px;padding:28px 32px;max-width:420px;width:90%;color:#f1f5f9;font-family:sans-serif';inner.innerHTML='<h3 style="margin:0 0 6px;font-size:18px">Gestion du compte</h3><p style="margin:0 0 20px;color:#94a3b8;font-size:14px">Que souhaitez-vous faire ?</p>';var b1=document.createElement('button');b1.textContent='\u21A9 Revenir au parainage';b1.style.cssText='display:block;width:100%;margin-bottom:10px;background:#dc2626;color:#fff;border:none;border-radius:12px;padding:12px;font-size:14px;font-weight:600;cursor:pointer';b1.onclick=function(){box.remove();try{window.connectedDesktop.disconnect()}catch(e){fetch('/connected/disconnect',{method:'POST',headers:{'Content-Type':'application/json','Accept':'application/json'}}).then(function(){location.reload()})['catch'](function(){location.reload()})}};var b2=document.createElement('button');b2.textContent='\u279C Changer d\'utilisateur';b2.style.cssText='display:block;width:100%;margin-bottom:10px;background:#2563eb;color:#fff;border:none;border-radius:12px;padding:12px;font-size:14px;font-weight:600;cursor:pointer';b2.onclick=function(){box.remove();switchUserForm()};var b3=document.createElement('button');b3.textContent='Annuler';b3.style.cssText='display:block;width:100%;background:#334155;color:#94a3b8;border:none;border-radius:12px;padding:12px;font-size:14px;cursor:pointer';b3.onclick=function(){box.remove()};inner.appendChild(b1);inner.appendChild(b2);inner.appendChild(b3);box.appendChild(inner);document.body.appendChild(box);}
    function switchUserForm(){var box=document.createElement('div');box.style.cssText='position:fixed;inset:0;z-index:100001;background:rgba(0,0,0,0.6);display:flex;align-items:center;justify-content:center';var inner=document.createElement('div');inner.style.cssText='background:#1e293b;border:1px solid #334155;border-radius:16px;padding:28px 32px;max-width:420px;width:90%;color:#f1f5f9;font-family:sans-serif';inner.innerHTML='<h3 style="margin:0 0 6px;font-size:18px">Changer d\'utilisateur</h3><p style="margin:0 0 16px;color:#94a3b8;font-size:14px">Connectez-vous avec un autre compte sur le m\u00EAme serveur.</p>';var inp=document.createElement('input');inp.type='email';inp.placeholder='Email';inp.value='';inp.style.cssText='display:block;width:100%;margin-bottom:10px;background:#0f172a;color:#f1f5f9;border:1px solid #334155;border-radius:10px;padding:12px;font-size:14px;outline:none';var sel=document.createElement('select');sel.style.cssText='display:block;width:100%;margin-bottom:10px;background:#0f172a;color:#f1f5f9;border:1px solid #334155;border-radius:10px;padding:12px;font-size:14px;outline:none;cursor:pointer';var roles=['admin','enseignant','staff','parent'];for(var i=0;i<roles.length;i++){var opt=document.createElement('option');opt.value=roles[i];opt.textContent=roles[i];sel.appendChild(opt)};var inp2=document.createElement('input');inp2.type='password';inp2.placeholder='Mot de passe';inp2.value='';inp2.style.cssText='display:block;width:100%;margin-bottom:16px;background:#0f172a;color:#f1f5f9;border:1px solid #334155;border-radius:10px;padding:12px;font-size:14px;outline:none';var st=document.createElement('div');st.style.cssText='margin-bottom:10px;font-size:13px;color:#94a3b8';var btn=document.createElement('button');btn.textContent='Se connecter';btn.style.cssText='display:block;width:100%;margin-bottom:8px;background:#2563eb;color:#fff;border:none;border-radius:12px;padding:12px;font-size:14px;font-weight:600;cursor:pointer';btn.onclick=function(){btn.disabled=true;btn.textContent='...';st.textContent='Connexion...';st.style.color='#94a3b8';fetch('/connected/switch-user',{method:'POST',headers:{'Content-Type':'application/json','Accept':'application/json'},body:JSON.stringify({email:inp.value,password:inp2.value,role:sel.value})}).then(function(r){return r.json()}).then(function(d){if(d&&d.success){st.textContent='\u2713 Succ\u00E8s ! Rechargement...';st.style.color='#4ade80';setTimeout(function(){location.reload()},800)}else{btn.disabled=false;btn.textContent='Se connecter';st.textContent='\u2717 '+(d.message||'Echec connexion');st.style.color='#f87171'}})['catch'](function(){btn.disabled=false;btn.textContent='Se connecter';st.textContent='\u2717 Erreur reseau';st.style.color='#f87171'})};var cancel=document.createElement('button');cancel.textContent='Annuler';cancel.style.cssText='display:block;width:100%;background:#334155;color:#94a3b8;border:none;border-radius:12px;padding:12px;font-size:14px;cursor:pointer';cancel.onclick=function(){box.remove()};inner.appendChild(inp);inner.appendChild(sel);inner.appendChild(inp2);inner.appendChild(st);inner.appendChild(btn);inner.appendChild(cancel);box.appendChild(inner);document.body.appendChild(box);}
@endif
</script>
<script src="{{ asset('js/novaskol-connected-bridge.js') }}?v=1.0.0"></script>
</body>
</html>
