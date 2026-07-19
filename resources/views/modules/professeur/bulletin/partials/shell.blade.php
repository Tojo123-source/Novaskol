@php
    $legacyBase = 'http://localhost/novaskol/';
    $logo = $ecole->logo ?? 'novaskol.png';
    $logoPath = str_starts_with($logo, 'images/') ? substr($logo, 7) : $logo;
    $currentUserId = (int) session('utilisateur.id', 0);
    $currentUser = $currentUserId ? DB::table('utilisateurs')->where('id', $currentUserId)->first(['id','nom','email','role','avatar']) : null;
    $currentRole = session('utilisateur.role');
    $notificationLink = $currentRole === 'parent' ? route('parent.portal') : route('modules.notifications');
    $messageLink = route('modules.chat-prive');
    $userAvatar = trim((string) ($currentUser->avatar ?? ''));
    if ($userAvatar === '') {
        $userAvatarUrl = asset('legacy/images/default-avatar.png');
    } elseif (str_starts_with($userAvatar, 'images/') || str_starts_with($userAvatar, 'uploads/')) {
        $userAvatarUrl = asset('legacy/'.$userAvatar);
    } else {
        $userAvatarUrl = asset('legacy/uploads/avatars/'.$userAvatar);
    }
    $latestNotifications = DB::table('notifications')
        ->where(function ($n) use ($currentUserId, $currentRole) {
            if ($currentRole === 'parent') {
                $n->where('destinataire_id', $currentUserId);
            } else {
                $n->whereNull('destinataire_id')->orWhere('destinataire_id', $currentUserId);
            }
        })
        ->orderByDesc('date_creation')
        ->limit(10)
        ->get(['id','type','message','date_creation','lu','statut']);
    $unreadNotifications = DB::table('notifications')
        ->where(function ($n) use ($currentUserId, $currentRole) {
            if ($currentRole === 'parent') {
                $n->where('destinataire_id', $currentUserId);
            } else {
                $n->whereNull('destinataire_id')->orWhere('destinataire_id', $currentUserId);
            }
        })
        ->where(function ($q) {
            $q->where('lu', 0)->orWhere('statut', 'non lu');
        })
        ->count();
    $latestMessages = $currentUserId ? DB::table('messages as m')
        ->join('conversation_participants as cp', 'cp.conversation_id', '=', 'm.conversation_id')
        ->join('conversations as c', 'c.id', '=', 'm.conversation_id')
        ->leftJoin('utilisateurs as u', 'u.id', '=', 'm.sender_id')
        ->where('cp.user_id', $currentUserId)
        ->where('m.sender_id', '!=', $currentUserId)
        ->select('m.*', 'u.nom as sender_name', 'c.type as conversation_type', 'c.name as conversation_name')
        ->orderByDesc('m.created_at')
        ->limit(6)
        ->get() : collect();
    $unreadMessages = $currentUserId ? DB::table('messages as m')
        ->join('conversation_participants as cp', 'cp.conversation_id', '=', 'm.conversation_id')
        ->where('cp.user_id', $currentUserId)
        ->where('m.sender_id', '!=', $currentUserId)
        ->where('m.is_read', 0)
        ->count() : 0;
    $interfaceLanguage = DB::table('parametres')->where('cle', 'langue_interface')->value('valeur') ?: 'fr';
@endphp
<link rel="stylesheet" href="{{ asset('legacy/fa/css/font-awesome.min.css') }}">
<link rel="stylesheet" href="{{ asset('legacy/assets/sweetalert2/sweetalert2.min.css') }}">
<script src="{{ asset('legacy/assets/sweetalert2/sweetalert2.min.js') }}"></script>
<script>window.NOVASKOL_INITIAL_LANGUAGE = @json($interfaceLanguage);</script>
<script src="{{ asset('legacy/js/novaskol-i18n.js') }}"></script>
<script>
(function(){
    const theme = localStorage.getItem('novaskol-theme') || localStorage.getItem('theme');
    document.documentElement.classList.toggle('light', theme === 'light');
})();
</script>
<style>
    :root.light,html.light{color-scheme:light;--bg:#f3f6fb!important;--card:#ffffff!important;--surface:#f8fafc!important;--primary:#059669!important;--primary-dark:#047857!important;--primary-glow:rgba(5,150,105,.16)!important;--text:#111827!important;--text-sec:#475569!important;--border:#d8e0ea!important;--danger:#dc2626!important;--success:#059669!important;--scroll-track:#eef2f7!important;--scroll-thumb:#cbd5e1!important;--scroll-thumb-hover:#94a3b8!important;--shadow-strong:rgba(15,23,42,.16)!important;--shadow-soft:rgba(15,23,42,.10)!important;--header-bg:linear-gradient(135deg,#ffffff,#eef5f1)!important;--nav-active:rgba(5,150,105,.14)!important;--nav-hover:rgba(5,150,105,.09)!important;--input-bg:#ffffff!important;--table-head:#e8f7ef!important}
    html.light,html.light body,html.light main,:root.light body,:root.light main{background:var(--bg)!important;color:var(--text)!important}
    html.light nav,html.light header,html.light .form-container,html.light .feature-item,html.light .card,html.light .panel,html.light .role-hero,html.light .role-card,html.light .module-tile,html.light .teacher-box,html.light .modal-content,html.light .eleves-modal-content,html.light .acc-panel,html.light .rh-panel,html.light .report-panel,html.light .calendar-side,html.light .chat-panel,html.light .notif-card,html.light .fpe-panel,html.light .assurance-panel,html.light .fiche-wrapper,html.light .presence-panel,html.light .depot-panel,:root.light nav,:root.light header,:root.light .form-container,:root.light .feature-item,:root.light .card,:root.light .panel,:root.light .role-hero,:root.light .role-card,:root.light .module-tile,:root.light .teacher-box,:root.light .modal-content,:root.light .eleves-modal-content,:root.light .acc-panel,:root.light .rh-panel,:root.light .report-panel,:root.light .calendar-side,:root.light .chat-panel,:root.light .notif-card,:root.light .fpe-panel,:root.light .assurance-panel,:root.light .fiche-wrapper,:root.light .presence-panel,:root.light .depot-panel{background:var(--card)!important;color:var(--text)!important;border-color:var(--border)!important;box-shadow:0 8px 24px rgba(15,23,42,.08)!important}
    :root.light header{background:var(--header-bg)!important}
    :root.light nav a:hover,:root.light nav a.active,:root.light .parent-menu:hover{background:var(--nav-hover)!important;color:var(--text)!important}
    :root.light nav a.active{background:var(--nav-active)!important;border-left-color:var(--primary)!important}
    html.light input,html.light select,html.light textarea,html.light .search-input,:root.light input,:root.light select,:root.light textarea,:root.light .search-input{background:#fff!important;color:#111827!important;border-color:var(--border)!important}
    :root.light table:not(.bulletin-table):not(.payslip-table) th,:root.light .eleves-table th,:root.light .notes-table th,:root.light .result-table th,:root.light .report-table th,:root.light .rh-table th,:root.light .acc-table th{background:#e8f7ef!important;color:#065f46!important}
    :root.light table:not(.bulletin-table):not(.payslip-table) td,:root.light .notes-table td,:root.light .result-table td,:root.light .report-table td,:root.light .rh-table td,:root.light .acc-table td{background:#fff!important;color:#111827!important;border-color:var(--border)!important}
    :root.light .table-wrapper,:root.light table:not(.bulletin-table):not(.payslip-table){background:#fff!important;color:#111827!important;border-color:var(--border)!important}
    :root.light table:not(.bulletin-table):not(.payslip-table) td:first-child,:root.light table:not(.bulletin-table):not(.payslip-table) th:first-child{background:#f1f5f9!important;color:#111827!important}
    :root.light .print-header,:root.light .print-header h2{color:var(--text)!important}
    :root.light .notes-table,:root.light .result-table,:root.light .report-table,:root.light .acc-table,:root.light .rh-table{background:#fff!important;color:#111827!important}
    :root.light .notes-table td:first-child,:root.light .notes-table th:first-child,:root.light .coef-row td,:root.light .section-row td{background:#f1f5f9!important;color:#111827!important}
    :root.light .kio,:root.light .form-container,:root.light .results,:root.light .acc-table-wrap,:root.light .rh-table-wrap,:root.light .report-table-wrap,:root.light .chart-card,:root.light .kpi,:root.light .rank-card{background:var(--card)!important;color:var(--text)!important;border-color:var(--border)!important}
    :root.light .muted,:root.light small,:root.light label,:root.light .card div,:root.light .feature-text p{color:#64748b!important}
    html.light .results,html.light .empty-state,html.light .notice,html.light .result-item,html.light .filters,html.light .filters-grid,html.light .actions,html.light .person-card,html.light .person-meta,html.light .assignment-row,html.light .perm-card,html.light .stat-card,html.light .lesson-card,html.light .task-card{background:var(--card)!important;color:var(--text)!important;border-color:var(--border)!important}
    html.light .sub-menu,html.light .parent-menu,html.light nav a{color:var(--text-sec)!important}
    html.light nav a.active,html.light nav a:hover,html.light .parent-menu:hover{background:var(--nav-hover)!important;color:var(--text)!important}
    html.light .novaskol-loader.active{background:rgba(248,250,252,.72)!important}
    html.light .loader-ring{border-color:rgba(5,150,105,.18)!important;border-top-color:var(--primary)!important}
    :root.light .global-dropdown,:root.light .loader-box{box-shadow:0 18px 45px rgba(15,23,42,.18)!important}
    html.light *{scrollbar-color:#cbd5e1 #eef2f7!important}
    html.light *::-webkit-scrollbar-track{background:#eef2f7!important}
    html.light *::-webkit-scrollbar-thumb{background:#cbd5e1!important;border-color:#eef2f7!important}
    html.light *::-webkit-scrollbar-thumb:hover{background:#94a3b8!important}
    .swal2-container{z-index:250000!important}
    .swal2-popup{z-index:250001!important}
    header{padding-left:96px!important;padding-right:220px!important}
    header h1,.header-center{max-width:100%!important;overflow:hidden!important;text-overflow:ellipsis!important;white-space:nowrap!important}
    header .header-left{position:absolute!important;left:20px!important;top:50%!important;transform:translateY(-50%)!important;display:flex!important;align-items:center!important;gap:14px!important}
    header .header-left,.burger-menu,#fullscreen-btn{z-index:2801!important;pointer-events:auto!important}
    .burger-menu,#fullscreen-btn,.fullscreen-btn{width:40px!important;height:40px!important;min-width:40px!important;border-radius:999px!important;border:1px solid var(--border,#1f1f2e)!important;background:var(--card,#14141a)!important;color:var(--text,#e5e7eb)!important;display:grid!important;place-items:center!important;box-shadow:0 8px 24px rgba(0,0,0,.24)!important;cursor:pointer!important;transition:transform .18s ease,color .18s ease,background .18s ease!important;position:relative!important;flex:0 0 40px!important}.burger-menu:hover,#fullscreen-btn:hover,.fullscreen-btn:hover{color:var(--primary,#00c853)!important;transform:translateY(-1px)!important}.burger-menu i,#fullscreen-btn i,.fullscreen-btn i{font-size:1rem!important;line-height:1!important;pointer-events:none!important}
    .novaskol-global-actions{position:fixed;top:16px;right:22px;z-index:11000;display:flex;align-items:center;gap:10px;pointer-events:auto!important}
    .global-icon-btn,.profile-trigger{width:40px;height:40px;border-radius:999px;border:1px solid var(--border,#1f1f2e);background:var(--card,#14141a);color:var(--text,#e5e7eb);display:grid;place-items:center;cursor:pointer;box-shadow:0 8px 24px #0004;position:relative;pointer-events:auto!important}
    .global-lang-wrap{position:relative}
    .global-lang-menu{position:fixed;top:66px;right:22px;width:84px;padding:8px;background:var(--card,#14141a);border:1px solid var(--border,#1f1f2e);border-radius:14px;box-shadow:0 18px 55px #0009;z-index:11110;display:none}
    .global-lang-menu.active{display:grid;gap:6px}
    .global-lang-option{width:100%;padding:8px 10px;border-radius:10px;border:1px solid transparent;background:transparent;color:var(--text,#e5e7eb);font-weight:800;cursor:pointer;text-align:center}
    .global-lang-option:hover,.global-lang-option.active{background:rgba(0,200,83,.12);border-color:rgba(0,200,83,.22);color:var(--primary,#00c853)}
    .global-icon-btn:hover,.profile-trigger:hover{color:var(--primary,#00c853);transform:translateY(-1px)}
    .profile-trigger img{width:100%;height:100%;border-radius:999px;object-fit:cover;pointer-events:none}.global-icon-btn i{pointer-events:none}.global-badge{position:absolute;top:-5px;right:-5px;background:#ef4444;color:white;border-radius:999px;min-width:18px;height:18px;font-size:.68rem;display:flex;align-items:center;justify-content:center;font-weight:800;padding:0 5px;pointer-events:none}
    .global-dropdown{position:fixed;top:66px;right:22px;width:min(370px,calc(100vw - 24px));max-height:72vh;overflow:auto;background:var(--card,#14141a);border:1px solid var(--border,#1f1f2e);border-radius:8px;box-shadow:0 18px 55px #0009;z-index:11100;display:none;padding:14px;color:var(--text,#e5e7eb)}
    .global-dropdown.active{display:block}.global-drop-head{display:flex;justify-content:space-between;align-items:center;margin-bottom:10px}.global-drop-head h3{margin:0;color:var(--primary,#00c853);font-size:1rem}.global-item{padding:11px;border-bottom:1px solid var(--border,#1f1f2e);color:var(--text,#e5e7eb)}.global-item:last-child{border-bottom:0}.global-item small{display:block;color:var(--text-sec,#9ca3af);margin-top:4px}.profile-card{text-align:center}.profile-card img{width:82px;height:82px;border-radius:999px;object-fit:cover;border:2px solid var(--primary,#00c853);margin-bottom:10px}.profile-tabs{display:flex;gap:8px;margin:12px 0}.profile-tabs button{flex:1;padding:9px;border-radius:8px;border:1px solid var(--border,#1f1f2e);background:var(--surface,#111827);color:var(--text,#e5e7eb);cursor:pointer}.profile-pane{display:none;text-align:left}.profile-pane.active{display:block}.profile-pane label{display:block;margin:10px 0 6px;color:var(--text-sec,#9ca3af)}.profile-pane input{width:100%;padding:10px;background:var(--surface,#111827);color:var(--text,#e5e7eb);border:1px solid var(--border,#1f1f2e);border-radius:8px}.profile-actions{display:flex;gap:8px;justify-content:flex-end;margin-top:12px}.global-primary{background:var(--primary,#00c853);color:#062b1d;border:0;border-radius:8px;padding:10px 12px;font-weight:800;cursor:pointer}.global-danger{background:#ef4444;color:white;border:0;border-radius:8px;padding:10px 12px;font-weight:800;cursor:pointer}.global-link{color:var(--primary,#00c853);text-decoration:none}.novaskol-loader{position:fixed;inset:0;background:rgba(0,0,0,.58);z-index:5000;display:none;align-items:center;justify-content:center;backdrop-filter:blur(4px)}.novaskol-loader.active{display:flex}.loader-box{background:var(--card,#14141a);border:1px solid var(--border,#1f1f2e);border-radius:8px;padding:24px 30px;text-align:center;color:var(--text,#e5e7eb);box-shadow:0 20px 60px #0008}.loader-ring{width:48px;height:48px;border-radius:50%;border:4px solid rgba(0,200,83,.18);border-top-color:var(--primary,#00c853);animation:novaspin .8s linear infinite;margin:0 auto 12px}@keyframes novaspin{to{transform:rotate(360deg)}}@media(max-width:900px){header{padding-left:82px!important;padding-right:150px!important;z-index:10040!important}header .header-left{position:fixed!important;left:14px!important;top:14px!important;transform:none!important;z-index:10050!important}.burger-menu,#fullscreen-btn,.fullscreen-btn{z-index:10051!important}.novaskol-global-actions{right:12px;top:14px}.global-icon-btn,.profile-trigger{width:36px;height:36px}.global-dropdown{right:12px;top:60px}}
    .global-item-actions{display:flex;justify-content:flex-end;gap:8px;margin-top:10px}.global-mini{border:0;border-radius:8px;padding:7px 10px;color:white;font-weight:800;cursor:pointer}.global-mini.success{background:#00a843}.global-mini.danger{background:#ef4444}@media(max-width:1100px){nav#sidebar{z-index:10040!important;padding-top:76px!important;box-shadow:18px 0 48px rgba(0,0,0,.28)!important}nav#sidebar .logo{padding:14px 0 18px!important}body.novaskol-sidebar-open{overflow:hidden!important}}@media(max-width:760px){header{padding-left:64px!important;padding-right:60px!important}header h1,.header-center{font-size:.92rem!important;text-align:left!important}}@media(max-width:700px){nav#sidebar{padding-top:78px!important}}@media print{.novaskol-global-actions,.global-dropdown,.novaskol-loader{display:none!important}}
</style>
<nav id="sidebar">
    <div class="logo">
        <img src="{{ asset('legacy/images/'.$logoPath) }}" alt="Logo">
        <h3>{{ $ecole->nom ?? 'Ecole' }}</h3>
    </div>
    @php
        $openSub = false;
        $moduleKeys = array_keys($modules);
        $canSeeModule = function (string $moduleKey) use ($currentRole, $userPermissions): bool {
            if ($currentRole === 'admin') {
                return true;
            }

            return in_array($userPermissions[$moduleKey] ?? null, ['lecture', 'ecriture'], true);
        };
    @endphp
    @if ($currentRole !== 'admin')
        <a href="{{ route('role.dashboard') }}" @class(['active' => ($activeModule ?? '') === 'role_dashboard'])>
            <i class="fa fa-th-large"></i> <span>Mon espace</span>
        </a>
    @endif
    @if ($currentRole === 'parent')
        <a href="{{ route('parent.portal') }}" @class(['active' => ($activeModule ?? '') === 'parent_portal'])>
            <i class="fa fa-child"></i> <span>Suivi des enfants</span>
        </a>
    @endif
    @foreach ($moduleKeys as $moduleIndex => $module)
        @php
            $info = $modules[$module];
        @endphp
        @if (! empty($info['icon']) && ! $canSeeModule($module))
            @continue
        @endif
        @if (empty($info['icon']))
            @if ($openSub)
                </div>
                @php
                    $openSub = false;
                @endphp
            @endif
            @php
                $sectionContainsActive = false;
                $sectionHasVisibleModule = false;
                for ($i = $moduleIndex + 1; $i < count($moduleKeys); $i++) {
                    $nextModule = $moduleKeys[$i];
                    $nextInfo = $modules[$nextModule];
                    if (empty($nextInfo['icon'])) {
                        break;
                    }
                    if (! $canSeeModule($nextModule)) {
                        continue;
                    }
                    $sectionHasVisibleModule = true;
                    if ($nextModule === ($activeModule ?? 'bulletin')) {
                        $sectionContainsActive = true;
                        break;
                    }
                }
            @endphp
            @if (! $sectionHasVisibleModule)
                @continue
            @endif
            <div class="parent-menu" onclick="toggleSub(this)">
                <span><i class="fa {{ $info['section_icon'] ?? 'fa-folder-open' }}"></i> {{ preg_replace('/^\s*\|\s*--\s*/', '', $info['label']) }}</span>
                <i class="fa fa-chevron-down arrow"></i>
            </div>
            <div class="sub-menu" style="display:{{ $sectionContainsActive ? 'block' : 'none' }};">
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
            <a href="{{ $href }}" @class(['active' => $module === ($activeModule ?? 'bulletin')])>
                <i class="fa {{ $info['icon'] }}"></i> <span>{{ $info['label'] }}</span>
            </a>
        @endif
    @endforeach
    @if ($openSub)
        </div>
    @endif
</nav>
<div class="novaskol-global-actions">
    @php($isConnectedMode = config('novaskol.edition', 'principal') === 'connecte' || Illuminate\Support\Facades\File::exists(env('CONNECTED_PAIRED_PATH', storage_path('app/connected/paired.json'))))
    @if($isConnectedMode)
    <button class="global-icon-btn" type="button" title="Synchronisation" data-action="sync" onclick="novaskolManualSync()" style="color:var(--primary);">
        <i class="fa fa-refresh"></i><span class="global-badge" id="novaskolSyncBadge" style="display:none;background:var(--primary);color:#000;">!</span>
    </button>
    @endif
    <div class="global-lang-wrap">
        <button class="global-icon-btn" type="button" title="Langue" data-lang-trigger data-action="language" onclick="novaskolToggleDrop('globalLanguage')"><i class="fa fa-language"></i></button>
        <div id="globalLanguage" class="global-lang-menu">
            @foreach (['fr' => 'FR', 'en' => 'EN', 'de' => 'DE', 'mg' => 'MG', 'es' => 'ES', 'pt' => 'PT'] as $langCode => $langLabel)
                <button type="button" class="global-lang-option @if($interfaceLanguage === $langCode) active @endif" data-lang-option data-lang-code="{{ $langCode }}" onclick="novaskolSetLanguage('{{ $langCode }}')">{{ $langLabel }}</button>
            @endforeach
        </div>
    </div>
    <button class="global-icon-btn" type="button" title="Mode sombre / clair" data-theme-toggle data-action="theme" onclick="novaskolToggleTheme()"><i class="fa fa-moon"></i></button>
    <button class="global-icon-btn" type="button" title="Notifications" data-action="notifications" onclick="novaskolToggleDrop('globalNotifications')"><i class="fa fa-bell"></i><span class="global-badge" data-notification-badge style="display:{{ $unreadNotifications > 0 ? 'flex' : 'none' }}">{{ $unreadNotifications }}</span></button>
    <button class="global-icon-btn" type="button" title="Messages" data-action="messages" onclick="novaskolToggleDrop('globalMessages')"><i class="fa fa-comments"></i><span class="global-badge" data-message-badge style="display:{{ $unreadMessages > 0 ? 'flex' : 'none' }}">{{ $unreadMessages }}</span></button>
    <button class="profile-trigger" type="button" title="Profil" data-action="profile" onclick="novaskolToggleDrop('globalProfile')"><img src="{{ $userAvatarUrl }}" alt="Profil"></button>
</div>
<div id="globalNotifications" class="global-dropdown">
    <div class="global-drop-head"><h3>Notifications</h3><a class="global-link" href="{{ $notificationLink }}">Voir tout</a></div>
    @forelse($latestNotifications as $notification)
        <div class="global-item" data-notification-id="{{ $notification->id }}" @style(['background:rgba(0,200,83,.08)' => ! (int) ($notification->lu ?? 0) || ($notification->statut ?? '') === 'non lu'])>
            <strong>{{ ucfirst($notification->type) }}</strong>
            <div>{{ $notification->message }}</div>
            <small>{{ $notification->date_creation ? \Carbon\Carbon::parse($notification->date_creation)->format('d/m/Y H:i') : '' }}</small>
            <div class="global-item-actions">
                @if(! (int) ($notification->lu ?? 0) || ($notification->statut ?? '') === 'non lu')
                    <button type="button" class="global-mini success" onclick="novaskolMarkNotificationRead({{ $notification->id }}, this)">Lu</button>
                @endif
                <button type="button" class="global-mini danger" onclick="novaskolDeleteNotification({{ $notification->id }}, this)">Supprimer</button>
            </div>
        </div>
    @empty
        <div class="global-item">Aucune notification.</div>
    @endforelse
</div>
<div id="globalMessages" class="global-dropdown">
    <div class="global-drop-head"><h3>Messages</h3><a class="global-link" href="{{ $messageLink }}">Ouvrir chat</a></div>
    @forelse($latestMessages as $message)
        <div class="global-item"><strong>{{ $message->conversation_type === 'group' ? ($message->conversation_name ?: 'Groupe') : ($message->sender_name ?: 'Utilisateur') }}</strong><div>{{ $message->type === 'text' ? \Illuminate\Support\Str::limit($message->content, 90) : ucfirst($message->type).' recu' }}</div><small>{{ $message->created_at ? \Carbon\Carbon::parse($message->created_at)->format('d/m/Y H:i') : '' }}</small></div>
    @empty
        <div class="global-item">Aucun message recent.</div>
    @endforelse
</div>
<div id="globalProfile" class="global-dropdown">
    <div class="profile-card">
        <img src="{{ $userAvatarUrl }}" alt="Profil">
        <h3>{{ $currentUser->nom ?? session('utilisateur.nom', 'Utilisateur') }}</h3>
        <small>{{ $currentUser->email ?? session('utilisateur.email', '') }} - {{ $currentUser->role ?? session('utilisateur.role', '') }}</small>
    </div>
    <div class="profile-tabs"><button type="button" onclick="novaskolProfileTab('profileInfo')">Profil</button>@if($isConnectedMode)<button type="button" class="global-danger" onclick="novaskolRetourAppairage()" style="border-color:var(--danger);color:var(--danger);flex:1.5;"><i class="fa fa-unlink"></i> Appairage</button>@else<button type="button" onclick="novaskolProfileTab('profilePass')">Compte</button>@endif</div>
    <form id="profileInfo" class="profile-pane active" method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
        @csrf
        <label>Nom</label><input name="nom" value="{{ $currentUser->nom ?? '' }}" required>
        <label>Email</label><input type="email" name="email" value="{{ $currentUser->email ?? '' }}" required>
        <label>Photo de profil</label><input type="file" name="avatar" accept="image/*">
        <div class="profile-actions"><button class="global-primary">Enregistrer</button></div>
    </form>
    @if(!$isConnectedMode)
    <form id="profilePass" class="profile-pane" method="POST" action="{{ route('profile.password') }}">
        @csrf
        <label>Nouveau mot de passe</label><input type="password" name="mot_de_passe" required minlength="6">
        <label>Confirmation</label><input type="password" name="mot_de_passe_confirmation" required minlength="6">
        <div class="profile-actions"><button class="global-primary">Modifier</button></div>
    </form>
    @endif
    <form method="POST" action="{{ route('logout') }}" class="profile-actions">@csrf<button class="global-danger"><i class="fa fa-power-off"></i> Deconnexion</button></form>
</div>
<div class="novaskol-loader" id="novaskolLoader"><div class="loader-box"><div class="loader-ring"></div><strong>Chargement...</strong></div></div>
<script>
function novaskolToggleDrop(id){document.querySelectorAll('.global-dropdown').forEach(d=>{if(d.id!==id)d.classList.remove('active')});document.getElementById(id)?.classList.toggle('active')}
function novaskolProfileTab(id){document.querySelectorAll('.profile-pane').forEach(p=>p.classList.remove('active'));document.getElementById(id)?.classList.add('active')}
function novaskolSyncThemeIcon(){document.querySelectorAll('.novaskol-global-actions [data-theme-toggle] i').forEach(i=>{i.classList.toggle('fa-sun',document.documentElement.classList.contains('light'));i.classList.toggle('fa-moon',!document.documentElement.classList.contains('light'))})}
function novaskolApplyTheme(){const theme=localStorage.getItem('novaskol-theme')||localStorage.getItem('theme')||'dark';const root=document.documentElement;root.dataset.theme=theme;root.classList.toggle('light',theme==='light');if(theme==='light'){const vars={'--bg':'#f3f6fb','--card':'#ffffff','--surface':'#f8fafc','--primary':'#059669','--primary-dark':'#047857','--primary-glow':'rgba(5,150,105,.16)','--text':'#111827','--text-sec':'#475569','--border':'#d8e0ea','--shadow-strong':'rgba(15,23,42,.16)','--shadow-soft':'rgba(15,23,42,.10)','--header-bg':'linear-gradient(135deg,#ffffff,#eef5f1)','--nav-active':'rgba(5,150,105,.14)','--nav-hover':'rgba(5,150,105,.09)','--input-bg':'#ffffff'};Object.entries(vars).forEach(([k,v])=>root.style.setProperty(k,v,'important'));}else{['--bg','--card','--surface','--primary','--primary-dark','--primary-glow','--text','--text-sec','--border','--shadow-strong','--shadow-soft','--header-bg','--nav-active','--nav-hover','--input-bg'].forEach(k=>root.style.removeProperty(k));}novaskolSyncThemeIcon()}
function novaskolToggleTheme(){const next=document.documentElement.classList.contains('light')?'dark':'light';localStorage.setItem('novaskol-theme',next);localStorage.setItem('theme',next);novaskolApplyTheme();document.dispatchEvent(new Event('themeChanged'))}
function novaskolSetLanguage(lang){try{if(window.NovaskolI18n)window.NovaskolI18n.apply(lang)}catch(e){}localStorage.setItem('novaskol-language',lang);document.documentElement.lang=lang;const labels={fr:'Langue active : Francais',en:'Active language: English',de:'Aktive Sprache: Deutsch',mg:'Fiteny voafidy: Malagasy',es:'Idioma activo: Espanol',pt:'Idioma ativo: Portugues'};try{if(typeof Swal!=='undefined')Swal.fire({toast:true,position:'top-end',timer:1600,showConfirmButton:false,icon:'success',title:labels[lang]||labels.fr})}catch(e){}}
function novaskolShowLoader(){const loader=document.getElementById('novaskolLoader');if(!loader)return;loader.classList.add('active');clearTimeout(window.__novaskolLoaderTimer);window.__novaskolLoaderTimer=setTimeout(novaskolHideLoader,7000)}
function novaskolHideLoader(){clearTimeout(window.__novaskolLoaderTimer);document.getElementById('novaskolLoader')?.classList.remove('active')}
function novaskolUpdateNotificationBadges(count){document.querySelectorAll('[data-notification-badge]').forEach(b=>{b.textContent=count;b.style.display=count>0?'flex':'none'})}
function novaskolUpdateMessageBadges(count){document.querySelectorAll('[data-message-badge]').forEach(b=>{b.textContent=count;b.style.display=count>0?'flex':'none'})}
async function novaskolRefreshUnreadMessages(){try{const r=await fetch('{{ route('modules.chat.unread') }}',{headers:{'Accept':'application/json'}});if(!r.ok)return;const data=await r.json();if(data.success)novaskolUpdateMessageBadges(Number(data.unread||0));}catch(e){}}
function novaskolHeaderSidebar(){return document.getElementById('sidebar')||document.querySelector('nav')}
function novaskolSetHeaderButtonIcons(){const sidebar=novaskolHeaderSidebar();const mobile=window.innerWidth<=1100;const hidden=sidebar?(sidebar.classList.contains('hidden')||(mobile&&!sidebar.classList.contains('active'))):false;document.querySelectorAll('.burger-menu').forEach(btn=>{btn.type='button';btn.title=hidden?'Afficher les menus':'Cacher les menus';if(!btn.querySelector('i'))btn.innerHTML='<i class="fa fa-bars"></i>';btn.querySelectorAll('i').forEach(i=>{i.classList.remove('fa-times','fa-close','fa-chevron-left','fa-chevron-right','fa-navicon');i.classList.add('fa','fa-bars')})});const full=!!document.fullscreenElement;document.querySelectorAll('#fullscreen-btn,.fullscreen-btn').forEach(btn=>{btn.type='button';btn.title=full?'Quitter le plein ecran':'Plein ecran';if(!btn.querySelector('i'))btn.innerHTML='<i class="fa fa-expand"></i>';btn.querySelectorAll('i').forEach(i=>{i.classList.remove('fa-expand','fa-compress','fa-arrows-alt');i.classList.add('fa',full?'fa-compress':'fa-expand')})})}
function novaskolCloseMobileSidebar(){const sidebar=novaskolHeaderSidebar();if(!sidebar)return;sidebar.classList.remove('active');document.body.classList.remove('novaskol-sidebar-open');novaskolSetHeaderButtonIcons()}
function novaskolToggleSidebar(){const sidebar=novaskolHeaderSidebar();if(!sidebar)return;const mobile=window.innerWidth<=1100;const hiddenNext=mobile?false:!sidebar.classList.contains('hidden');if(mobile){sidebar.classList.toggle('active');document.body.classList.toggle('novaskol-sidebar-open',sidebar.classList.contains('active'))}else{document.body.classList.remove('novaskol-sidebar-open');sidebar.classList.toggle('hidden',hiddenNext);sidebar.classList.toggle('active',!hiddenNext);document.querySelectorAll('main,header').forEach(el=>{el.classList.toggle('full-width',hiddenNext);el.classList.toggle('full',hiddenNext)})}novaskolSetHeaderButtonIcons()}
function novaskolToggleFullscreen(){if(!document.fullscreenElement){document.documentElement.requestFullscreen?.().catch(()=>{})}else{document.exitFullscreen?.().catch(()=>{})}setTimeout(novaskolSetHeaderButtonIcons,120)}
function novaskolSyncHeaderButtons(){window.toggleSidebar=novaskolToggleSidebar;window.toggleFullscreen=novaskolToggleFullscreen;document.querySelectorAll('.burger-menu,#fullscreen-btn,.fullscreen-btn').forEach(btn=>{btn.onclick=null;btn.removeAttribute('onclick')});document.querySelectorAll('.burger-menu').forEach(btn=>{if(btn.dataset.novaskolHeaderReady==='1')return;btn.dataset.novaskolHeaderReady='1';btn.addEventListener('click',e=>{e.preventDefault();e.stopPropagation();novaskolToggleSidebar()})});document.querySelectorAll('#fullscreen-btn,.fullscreen-btn').forEach(btn=>{if(btn.dataset.novaskolHeaderReady==='1')return;btn.dataset.novaskolHeaderReady='1';btn.addEventListener('click',e=>{e.preventDefault();e.stopPropagation();novaskolToggleFullscreen()})});novaskolSetHeaderButtonIcons()}
function novaskolLocalDeviceType(){const w=window.innerWidth||1024;if(w<=760)return 'telephone';if(w<=1100)return 'tablette';return 'pc'}
function novaskolLocalDeviceUuid(){let uuid=localStorage.getItem('novaskol-local-device-uuid');if(!uuid){uuid=(window.crypto&&crypto.randomUUID?crypto.randomUUID():(Date.now()+'-'+Math.random()).replace('.',''));localStorage.setItem('novaskol-local-device-uuid',uuid)}return uuid}
async function novaskolRegisterLocalDevice(){try{const host=location.hostname;if(['localhost','127.0.0.1','::1'].includes(host))return;const uuid=novaskolLocalDeviceUuid();const type=novaskolLocalDeviceType();const nom=(type==='telephone'?'Telephone':(type==='tablette'?'Tablette':'PC'))+' - '+(navigator.platform||host);await fetch('{{ route('modules.reseau-local.device.register') }}',{method:'POST',headers:{'Content-Type':'application/json','Accept':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'},body:JSON.stringify({uuid,nom,type_appareil:type,plateforme:(navigator.userAgent||'').slice(0,80)})});}catch(e){}}
async function novaskolMarkNotificationRead(id,btn){const r=await fetch(`/dashboard/notifications/${id}/read`,{method:'POST',headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}','Accept':'application/json'}});const data=await r.json();if(data.success){const item=btn.closest('[data-notification-id]');if(item)item.style.background='';btn.remove();novaskolUpdateNotificationBadges(data.new_count)}}
async function novaskolDeleteNotification(id,btn){const ok=window.Swal?await Swal.fire({icon:'warning',title:'Supprimer ?',text:'Cette notification sera supprimee.',showCancelButton:true,confirmButtonText:'Supprimer',cancelButtonText:'Annuler'}):{isConfirmed:confirm('Supprimer cette notification ?')};if(!ok.isConfirmed)return;const r=await fetch(`/dashboard/notifications/${id}`,{method:'DELETE',headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}','Accept':'application/json'}});const data=await r.json();if(data.success){btn.closest('[data-notification-id]')?.remove();novaskolUpdateNotificationBadges(data.new_count);if(!document.querySelector('#globalNotifications [data-notification-id]'))document.getElementById('globalNotifications').insertAdjacentHTML('beforeend','<div class="global-item">Aucune notification.</div>')}}
novaskolApplyTheme();
document.addEventListener('DOMContentLoaded',()=>{novaskolHideLoader();novaskolSyncHeaderButtons();novaskolApplyTheme();if(window.NovaskolI18n)window.NovaskolI18n.apply(window.NovaskolI18n.current());document.querySelectorAll('.novaskol-global-actions [onclick]:not([data-lang-option])').forEach(btn=>{btn.onclick=null;btn.removeAttribute('onclick')});document.querySelectorAll('.novaskol-global-actions [data-action="theme"]').forEach(btn=>btn.addEventListener('click',e=>{e.preventDefault();e.stopPropagation();novaskolToggleTheme()}));document.querySelectorAll('.novaskol-global-actions [data-action="notifications"]').forEach(btn=>btn.addEventListener('click',e=>{e.preventDefault();e.stopPropagation();novaskolToggleDrop('globalNotifications')}));document.querySelectorAll('.novaskol-global-actions [data-action="messages"]').forEach(btn=>btn.addEventListener('click',e=>{e.preventDefault();e.stopPropagation();novaskolToggleDrop('globalMessages')}));document.querySelectorAll('.novaskol-global-actions [data-action="profile"]').forEach(btn=>btn.addEventListener('click',e=>{e.preventDefault();e.stopPropagation();novaskolToggleDrop('globalProfile')}));});
document.addEventListener('DOMContentLoaded',()=>{novaskolRegisterLocalDevice();setInterval(novaskolRegisterLocalDevice,120000)});
document.addEventListener('DOMContentLoaded',()=>{document.querySelectorAll('[data-lang-trigger]').forEach(btn=>btn.addEventListener('click',e=>{e.preventDefault();e.stopPropagation();novaskolToggleDrop('globalLanguage')}));document.querySelectorAll('[data-lang-option]').forEach(btn=>btn.addEventListener('click',e=>{e.preventDefault();e.stopPropagation();window.novaskolSetLanguage?.(btn.dataset.langCode||'fr');document.getElementById('globalLanguage')?.classList.remove('active')}))});
document.addEventListener('fullscreenchange',novaskolSetHeaderButtonIcons);
let _rTimer;window.addEventListener('resize',function(){clearTimeout(_rTimer);_rTimer=setTimeout(function(){novaskolSetHeaderButtonIcons();if(window.innerWidth>1100)document.body.classList.remove('novaskol-sidebar-open')},120)});
document.addEventListener('click',e=>{if(!e.target.closest('.global-dropdown,.novaskol-global-actions'))document.querySelectorAll('.global-dropdown').forEach(d=>d.classList.remove('active'));const sidebar=novaskolHeaderSidebar();if(window.innerWidth<=1100&&sidebar?.classList.contains('active')&&!e.target.closest('nav,#sidebar,.burger-menu,.mobile-menu-btn'))novaskolCloseMobileSidebar()});
setInterval(novaskolRefreshUnreadMessages,4000);
setTimeout(novaskolRefreshUnreadMessages,1200);
document.addEventListener('submit',async e=>{if(e.defaultPrevented)return;const form=e.target.closest('form');if(!form||form.dataset.confirmed==='1'||form.hasAttribute('data-no-confirm'))return;const spoof=(form.querySelector('input[name="_method"]')?.value||'').toUpperCase();const method=spoof||String(form.method||'GET').toUpperCase();const isMutation=String(form.method||'').toLowerCase()==='post'||['POST','PUT','PATCH','DELETE'].includes(method);if(!isMutation)return;e.preventDefault();const deleting=method==='DELETE'||form.classList.contains('js-confirm-submit');const ok=window.Swal?await Swal.fire({icon:deleting?'warning':'question',title:form.dataset.confirmTitle||(deleting?'Confirmer la suppression ?':'Confirmer l enregistrement ?'),text:form.dataset.confirmText||(deleting?'Cette action est definitive.':'Les informations seront enregistrees.'),showCancelButton:true,confirmButtonText:deleting?'Oui, supprimer':'Oui, continuer',cancelButtonText:'Annuler',confirmButtonColor:'#00c853',cancelButtonColor:'#ef4444'}):{isConfirmed:confirm(form.dataset.confirmTitle||'Confirmer ?')};if(ok.isConfirmed){form.dataset.confirmed='1';novaskolShowLoader();form.submit();}});
document.addEventListener('submit',e=>{setTimeout(()=>{if(!e.defaultPrevented&&!e.target.hasAttribute('data-no-loader'))novaskolShowLoader()},0)});
document.addEventListener('click',e=>{const a=e.target.closest('a');if(a&&a.href&&!a.target&&a.href!==location.href&&a.getAttribute('href')!=='#'&&!a.hasAttribute('download'))novaskolShowLoader()});
window.addEventListener('pageshow',novaskolHideLoader);
window.addEventListener('pageshow',novaskolApplyTheme);
window.addEventListener('load',()=>{novaskolHideLoader();novaskolApplyTheme()});
window.addEventListener('storage',e=>{if(['novaskol-theme','theme'].includes(e.key))novaskolApplyTheme()});
if(window.jQuery)jQuery(document).ajaxStop(novaskolHideLoader);
@if(session('success'))
document.addEventListener('DOMContentLoaded',()=>Swal.fire({icon:'success',title:'Succes',text:@json(session('success')),timer:2300,showConfirmButton:false}));
@endif
@php
    $novaskolFlash = null;
    foreach (['accounting_msg', 'communication_msg', 'rh_msg', 'pedagogy_msg'] as $flashKey) {
        if (session($flashKey)) {
            $flash = session($flashKey);
            $novaskolFlash = [
                'type' => $flash['type'] ?? 'info',
                'title' => ($flash['type'] ?? '') === 'success' ? 'Succes' : (($flash['type'] ?? '') === 'error' ? 'Erreur' : 'Information'),
                'text' => $flash['text'] ?? '',
            ];
            break;
        }
    }
    if (! $novaskolFlash && session('error')) {
        $novaskolFlash = ['type' => 'error', 'title' => 'Erreur', 'text' => session('error')];
    }
@endphp
@if(($novaskolFlash ?? false))
document.addEventListener('DOMContentLoaded',()=>Swal.fire({icon:@json($novaskolFlash['type']),title:@json($novaskolFlash['title']),text:@json($novaskolFlash['text']),timer:@json($novaskolFlash['type'] === 'success' ? 4000 : 5000),showConfirmButton:false}));
@endif
@if($errors ?? false)
@if($errors->any())
document.addEventListener('DOMContentLoaded',()=>Swal.fire({icon:'error',title:'Erreur',text:@json($errors->first())}));
@endif
@endif
</script>
<script>
@if($isConnectedMode ?? false)
async function novaskolManualSync() {
    const btn = document.querySelector('[data-action="sync"] i');
    const badge = document.getElementById('novaskolSyncBadge');
    try {
        if (btn) btn.className = 'fa fa-spinner fa-spin';
        if (badge) badge.style.display = 'none';
        if (window.NovaskolConnected && typeof NovaskolConnected.syncNow === 'function') {
            await NovaskolConnected.syncNow();
        } else {
            const r = await fetch('/connected/sync/run', { headers: { 'Accept': 'application/json' } });
            const data = await r.json();
            if (!data.success) throw new Error(data.message || 'Sync failed');
        }
        if (typeof Swal !== 'undefined') {
            Swal.fire({ toast: true, position: 'top-end', timer: 2000, showConfirmButton: false, icon: 'success', title: 'Synchronisation reussie' });
        }
    } catch (e) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({ icon: 'warning', title: 'Sync impossible', text: e.message || 'Verifiez que le serveur principal est accessible sur le meme WiFi.' });
        }
        if (badge) badge.style.display = 'flex';
    } finally {
        if (btn) setTimeout(() => { btn.className = 'fa fa-refresh'; }, 800);
    }
}
async function novaskolRetourAppairage() {
    const ok = typeof Swal !== 'undefined'
        ? await Swal.fire({ icon: 'warning', title: 'Retour a l\'appairage ?', text: 'Vous serez redirige vers la page de configuration.', showCancelButton: true, confirmButtonText: 'Oui', cancelButtonText: 'Annuler', confirmButtonColor: '#ef4444' })
        : { isConfirmed: confirm('Retour a l\'appairage ?') };
    if (!ok.isConfirmed) return;
    localStorage.removeItem('novaskol_connected_profile');
    localStorage.removeItem('novaskol_connected_bootstrap');
    localStorage.removeItem('novaskol_connected_queue');
    if (window.connectedDesktop && typeof window.connectedDesktop.disconnect === 'function') {
        await window.connectedDesktop.disconnect();
    } else {
        try {
            await fetch('/connected/disconnect', { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' } });
        } catch (e) {}
        window.location.href = '/';
    }
}
@endif
</script>
<script src="{{ asset('js/novaskol-connected-bridge.js') }}?v=1.0.1"></script>
