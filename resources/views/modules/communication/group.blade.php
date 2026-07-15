@php
    $selectedGroup = $groups->firstWhere('id', $selectedGroupId);
    $mobileState = $selectedGroup ? 'mobile-chat-open' : 'mobile-chat-list';
    $groupAvatar = null;
    $membersByGroup = [];
    $selectedIsAnnouncement = false;
    $canWriteSelectedGroup = false;
    $isGroupOwner = false;
    if ($selectedGroup) {
        $groupAvatar = $selectedGroup->avatar ? asset('legacy/uploads/group_avatars/'.$selectedGroup->avatar) : asset('legacy/images/default-group.png');
        $membersByGroup = \Illuminate\Support\Facades\DB::table('conversation_participants as cp')
            ->join('utilisateurs as u', 'u.id', '=', 'cp.user_id')
            ->where('cp.conversation_id', $selectedGroup->id)
            ->pluck('u.id')
            ->map(fn ($id) => (int) $id)
            ->all();
        $selectedIsAnnouncement = (int) ($selectedGroup->is_announcement ?? 0) === 1;
        $canWriteSelectedGroup = $selectedIsAnnouncement ? $currentUserRole === 'admin' : ($canWriteChatGroup ?? false);
        $isGroupOwner = (int) $selectedGroup->creator_id === $currentUserId;
    }
@endphp
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Chat groupe - {{ $ecole->nom ?? 'Ecole' }}</title>
    <link rel="stylesheet" href="{{ asset('legacy/assets/fontawesome/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('legacy/fa/css/font-awesome.min.css') }}">
    @include('modules.professeur.bulletin.partials.styles')
    <style>
        .group-shell { display:grid; grid-template-columns:320px minmax(0,1fr); gap:16px; height:calc(100vh - 130px); min-height:590px; }
        .panel { background:var(--card); border:1px solid var(--border); border-radius:8px; overflow:hidden; min-height:0; position:relative; }
        .conversation-panel { display:flex; flex-direction:column; }
        .panel-pad { padding:14px; border-bottom:1px solid var(--border); }
        input,select,textarea { width:100%; padding:12px; background:var(--surface); color:var(--text); border:1px solid var(--border); border-radius:8px; }
        .groups { height:calc(100% - 70px); overflow:auto; }
        .group-link { display:flex; gap:11px; align-items:center; padding:12px 14px; color:var(--text); text-decoration:none; border-bottom:1px solid rgba(255,255,255,.04); }
        .group-link:hover,.group-link.active { background:rgba(0,200,83,.12); }
        .avatar { width:42px; height:42px; border-radius:8px; object-fit:cover; border:1px solid var(--border); background:#0f172a; flex:0 0 auto; }
        .grow { min-width:0; flex:1; }
        .grow strong { display:block; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
        .muted { color:var(--text-sec); font-size:.86rem; }
        .unread { background:var(--primary); color:white; border-radius:999px; min-width:22px; height:22px; display:inline-flex; align-items:center; justify-content:center; font-size:.78rem; font-weight:800; }
        .status-dot { width:10px; height:10px; border-radius:999px; display:inline-block; border:2px solid var(--card); background:#22c55e; box-shadow:0 0 0 3px rgba(34,197,94,.14); }
        .chat-head { min-height:62px; display:flex; align-items:center; gap:12px; padding:12px 16px; border-bottom:1px solid var(--border); background:var(--card); position:relative; }
        .group-trigger { display:flex; align-items:center; gap:12px; background:transparent; border:0; color:var(--text); text-align:left; cursor:pointer; flex:1; min-width:0; overflow:hidden; padding:0; }
        .chat-head .avatar { width:46px; height:46px; border-radius:999px; border:1px solid var(--border); }
        .chat-head-copy { display:flex; flex-direction:column; gap:3px; min-width:0; }
        .chat-head-copy strong { display:block; max-width:340px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; }
        .chat-head-copy .muted { white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
        .chat-head-actions { display:flex; align-items:center; gap:10px; flex:0 0 auto; }
        .announcement-badge { display:inline-flex; align-items:center; gap:6px; width:max-content; margin-top:4px; padding:4px 8px; border-radius:999px; background:rgba(245,158,11,.16); color:#facc15; font-size:.74rem; font-weight:900; }
        .messages { flex:1; min-height:0; overflow:auto; padding:18px; display:flex; flex-direction:column; gap:12px; }
        .bubble { max-width:min(700px,84%); padding:11px 13px; border-radius:18px; background:#1f2937; border:1px solid var(--border); align-self:flex-start; }
        .bubble.mine { align-self:flex-end; background:rgba(0,200,83,.18); border-color:rgba(0,200,83,.35); }
        .bubble.context-target { outline:2px solid rgba(0,200,83,.45); }
        .bubble-name { color:var(--primary); font-weight:800; font-size:.82rem; margin-bottom:4px; }
        .bubble-text { white-space:pre-wrap; overflow-wrap:anywhere; line-height:1.45; }
        .bubble-meta { margin-top:6px; font-size:.76rem; color:var(--text-sec); text-align:right; display:flex; justify-content:flex-end; gap:8px; align-items:center; }
        .meta-status { font-weight:900; letter-spacing:-1px; }
        .meta-status.sent,.meta-status.delivered { color:#94a3b8; }
        .meta-status.read { color:#22c55e; }
        .chat-image { max-width:260px; max-height:220px; border-radius:12px; display:block; margin-top:8px; border:1px solid var(--border); }
        .file-link { display:inline-flex; align-items:center; gap:8px; color:#bfdbfe; margin-top:8px; text-decoration:none; font-weight:700; }
        .composer { min-height:78px; border-top:1px solid var(--border); padding:12px; display:grid; grid-template-columns:1fr auto auto; gap:10px; align-items:center; background:var(--card); }
        .readonly-note { min-height:78px; border-top:1px solid var(--border); padding:14px 16px; display:flex; align-items:center; gap:10px; color:var(--text-sec); background:var(--card); font-weight:700; }
        .file-btn,.send-btn,.small-btn,.danger-btn,.ghost-btn { border:0; border-radius:999px; padding:12px 15px; cursor:pointer; font-weight:800; color:white; display:grid; place-items:center; }
        .file-btn,.small-btn { background:#2563eb; }
        .send-btn { background:var(--primary); }
        .danger-btn { background:#dc2626; }
        .ghost-btn { background:rgba(255,255,255,.08); color:var(--text); border-radius:12px; padding:10px 14px; }
        .empty-state { height:100%; display:flex; align-items:center; justify-content:center; color:var(--text-sec); text-align:center; padding:30px; }
        .group-form { display:grid; gap:12px; margin-top:12px; padding-top:12px; border-top:1px solid var(--border); }
        .members { display:grid; grid-template-columns:repeat(auto-fit,minmax(135px,1fr)); gap:8px; max-height:180px; overflow:auto; padding:10px; border:1px solid var(--border); border-radius:8px; background:var(--surface); }
        .members label { display:flex; align-items:center; gap:7px; margin:0; color:var(--text); font-weight:500; }
        .members input { width:auto; }
        .preview { display:none; position:absolute; left:12px; bottom:82px; width:auto; max-width:min(220px,calc(100% - 24px)); align-items:flex-start; gap:0; padding:0; background:transparent; border:0; border-radius:0; color:var(--text); box-shadow:none; z-index:20; }
        .preview img { width:78px; height:78px; border-radius:10px; object-fit:cover; display:block; border:1px solid var(--border); box-shadow:0 8px 22px rgba(0,0,0,.28); }
        .preview .file-chip { display:inline-flex; align-items:center; gap:8px; padding:10px 14px; background:var(--card); border:1px solid var(--border); border-radius:999px; box-shadow:0 8px 22px rgba(0,0,0,.22); font-weight:700; }
        .preview button { position:absolute; right:-8px; top:-8px; background:#dc2626; border:0; color:#fff; cursor:pointer; font-weight:900; width:24px; height:24px; border-radius:50%; line-height:24px; box-shadow:0 6px 18px rgba(0,0,0,.24); }
        .chat-error { display:none; grid-column:1/-1; color:#fecaca; background:rgba(239,68,68,.12); border:1px solid rgba(239,68,68,.25); border-radius:8px; padding:8px 10px; }
        .modal-backdrop { display:none; position:fixed; inset:0; background:rgba(0,0,0,.62); z-index:2000; align-items:center; justify-content:center; padding:20px; }
        .profile-modal { width:min(480px,100%); background:var(--card); border:1px solid var(--border); border-radius:8px; padding:22px; box-shadow:0 20px 50px rgba(0,0,0,.45); }
        .profile-top { display:flex; gap:14px; align-items:center; margin-bottom:16px; }
        .profile-top img { width:76px; height:76px; border-radius:12px; object-fit:cover; border:1px solid var(--border); }
        .profile-line { padding:10px 0; border-top:1px solid var(--border); color:var(--text-sec); }
        .modal-close { float:right; background:#dc2626; color:white; border:0; border-radius:8px; padding:8px 11px; cursor:pointer; }
        .message-menu { position:fixed; z-index:4500; min-width:170px; display:none; background:var(--card); border:1px solid var(--border); border-radius:8px; box-shadow:0 20px 55px #0009; padding:7px; }
        .message-menu button { width:100%; display:flex; gap:8px; align-items:center; padding:9px 10px; border:0; border-radius:7px; background:transparent; color:var(--text); cursor:pointer; font-weight:800; text-align:left; }
        .message-menu button:hover { background:rgba(0,200,83,.12); }
        .typing-line { display:none; color:var(--primary); font-size:.85rem; padding:0 18px 8px; align-items:center; gap:8px; }
        .typing-dots { display:inline-flex; gap:4px; align-items:center; }
        .typing-dots span { width:6px; height:6px; border-radius:999px; background:currentColor; opacity:.35; animation:typingPulse 1s infinite ease-in-out; }
        .typing-dots span:nth-child(2) { animation-delay:.18s; }
        .typing-dots span:nth-child(3) { animation-delay:.36s; }
        @keyframes typingPulse { 0%,80%,100% { transform:translateY(0); opacity:.25; } 40% { transform:translateY(-3px); opacity:1; } }
        .mobile-only { display:none; }
        .desktop-only { display:block; }
        .mobile-list-head { display:none; }
        .mobile-list-head-main { display:flex; align-items:center; gap:12px; min-width:0; }
        .mobile-top-menu { position:relative; flex:0 0 auto; }
        .mobile-top-menu summary { list-style:none; width:40px; height:40px; border-radius:999px; border:1px solid var(--border); display:grid; place-items:center; color:var(--text); cursor:pointer; background:var(--surface); }
        .mobile-top-menu summary::-webkit-details-marker { display:none; }
        .mobile-menu-card { position:absolute; right:0; top:52px; width:230px; padding:10px; border-radius:18px; background:var(--card); border:1px solid var(--border); box-shadow:0 20px 45px rgba(0,0,0,.26); display:grid; gap:6px; z-index:60; }
        .mobile-menu-card button,.mobile-menu-card a { width:100%; display:flex; align-items:center; gap:10px; border:0; background:transparent; color:var(--text); border-radius:12px; padding:12px 14px; text-decoration:none; font-weight:700; text-align:left; cursor:pointer; }
        .mobile-menu-card button:hover,.mobile-menu-card a:hover { background:var(--nav-hover); }
        .mobile-back-btn,.mobile-menu-btn { display:none; }
        .mobile-quick-actions { display:none; }
        .mobile-sheet { width:min(680px,100%); max-height:min(90vh,900px); overflow:auto; background:var(--card); border:1px solid var(--border); border-radius:24px; padding:18px; box-shadow:0 20px 55px rgba(0,0,0,.38); }
        .mobile-sheet h2 { margin:0 0 6px; }
        .mobile-sheet .muted { margin-bottom:10px; }
        .member-search { margin-bottom:10px; }
        .mobile-modal-actions { display:flex; gap:10px; justify-content:flex-end; margin-top:14px; }
        .media-viewer { width:min(980px,100%); max-height:90vh; background:var(--card); border:1px solid var(--border); border-radius:18px; padding:18px; box-shadow:0 24px 60px rgba(0,0,0,.38); display:flex; flex-direction:column; gap:14px; }
        .media-viewer-head { display:flex; align-items:center; justify-content:space-between; gap:12px; }
        .media-viewer-head h3 { margin:0; font-size:1rem; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; }
        .media-viewer-body { min-height:320px; max-height:calc(90vh - 120px); display:grid; place-items:center; overflow:auto; background:var(--surface); border:1px solid var(--border); border-radius:14px; }
        .media-viewer-body img { max-width:100%; max-height:calc(90vh - 160px); object-fit:contain; }
        .media-viewer-body iframe { width:min(900px,100%); height:calc(90vh - 170px); border:0; background:#fff; }
        .media-viewer-actions { display:flex; justify-content:flex-end; gap:10px; }
        .text-link-btn { display:inline-flex; align-items:center; gap:8px; padding:10px 14px; border-radius:12px; border:1px solid var(--border); background:var(--surface); color:var(--text); text-decoration:none; font-weight:700; }
        @media(max-width:1100px){
            .group-shell{grid-template-columns:1fr;height:auto;gap:12px}
            .panel{min-height:420px}
            .groups{height:280px}
            .messages{min-height:420px}
            .composer{grid-template-columns:1fr auto}
            .file-btn{grid-column:1/2}
            .send-btn{grid-column:2/3}
            .preview{bottom:92px}
        }
        @media(max-width:700px){
            body>header,.novaskol-global-actions{display:none!important}
            html,body{background:var(--bg)!important}
            body{overscroll-behavior:none}
            main{margin:0!important;padding:0!important;height:var(--novaskol-vh,100dvh)!important;max-width:none!important;overflow:hidden!important}
            .group-shell{display:block;height:var(--novaskol-vh,100dvh);min-height:var(--novaskol-vh,100dvh)}
            .panel{border:0;border-radius:0;box-shadow:none;min-height:var(--novaskol-vh,100dvh);background:var(--bg)}
            .conversation-panel{display:flex;flex-direction:column;height:var(--novaskol-vh,100dvh);overflow:hidden;background:
                radial-gradient(circle at 20% 10%, rgba(0,200,83,.08) 0, rgba(0,200,83,0) 26%),
                radial-gradient(circle at 80% 20%, rgba(255,255,255,.08) 0, rgba(255,255,255,0) 24%),
                linear-gradient(180deg, rgba(255,255,255,.03), rgba(255,255,255,0)),
                var(--bg)}
            body.mobile-chat-list .conversation-panel{display:none}
            body.mobile-chat-open .list-panel{display:none}
            .mobile-only,.mobile-list-head,.mobile-back-btn,.mobile-menu-btn,.mobile-quick-actions{display:flex}
            .desktop-only{display:none!important}
            .mobile-list-head{position:sticky;top:0;z-index:10060;align-items:center;justify-content:space-between;padding:22px 18px 14px;background:var(--bg)}
            .mobile-list-head h2{margin:0;font-size:2rem;color:var(--text);line-height:1.1}
            .panel-pad{display:none}
            .contact-search,.group-search-wrap{padding:0 18px 12px;border-bottom:0;background:var(--bg)}
            .group-search-wrap input{border-radius:999px;padding:14px 16px;background:var(--card);border-color:var(--border);font-size:1rem}
            .mobile-quick-actions{gap:10px;padding:0 18px 16px;background:var(--bg)}
            .mobile-quick-actions .ghost-btn{flex:1;justify-content:center;border-radius:16px}
            .groups{height:calc(var(--novaskol-vh,100dvh) - 190px);padding:0 10px 12px;background:var(--bg)}
            .group-link{padding:14px 10px;border-bottom:1px solid rgba(255,255,255,.04);border-radius:18px;gap:12px}
            .group-link:hover,.group-link.active{background:var(--nav-hover)}
            .avatar{width:52px;height:52px;border-radius:999px;border-color:rgba(255,255,255,.06)}
            .grow strong{font-size:1rem}
            .muted{font-size:.9rem}
            .chat-head{position:sticky;top:0;z-index:30;padding:12px;background:var(--card);color:var(--text);border-bottom:1px solid var(--border);gap:10px;box-shadow:0 8px 18px rgba(15,23,42,.08)}
            .group-trigger{color:var(--text)}
            .group-trigger .avatar{width:48px;height:48px;border-color:var(--border)}
            .chat-head-copy strong{max-width:none;font-size:1rem}
            .mobile-back-btn,.mobile-menu-btn{width:40px;height:40px;align-items:center;justify-content:center;border-radius:999px;text-decoration:none;color:var(--text);flex:0 0 auto;background:var(--surface);border:1px solid var(--border)}
            .mobile-menu-btn{position:relative;z-index:10061}
            .messages{flex:1;min-height:0;padding:16px 12px 18px;gap:10px;background:transparent;overflow:auto;overscroll-behavior:contain}
            .bubble{max-width:86%;padding:11px 12px;border-radius:18px;background:var(--card);color:var(--text);border-color:var(--border);box-shadow:0 8px 14px rgba(30,41,59,.08)}
            .bubble.mine{background:rgba(0,200,83,.10)}
            .bubble-meta{font-size:.72rem;color:var(--text-sec)}
            .composer{position:sticky;bottom:0;z-index:24;display:flex;align-items:center;gap:8px;padding:10px 12px calc(14px + env(safe-area-inset-bottom));background:linear-gradient(180deg,rgba(0,0,0,0),rgba(15,23,42,.06) 25%,rgba(15,23,42,.1))}
            .composer input[type="text"]{flex:1;min-width:0;height:48px;border-radius:999px;padding:0 16px;border-color:var(--border);background:var(--card);color:var(--text)}
            .file-btn,.send-btn{width:44px;height:44px;padding:0;border-radius:999px;background:transparent;color:var(--text);border:1px solid transparent;box-shadow:none}
            .file-btn:hover,.send-btn:hover,.file-btn:focus-visible,.send-btn:focus-visible{background:rgba(0,200,83,.10);color:var(--primary);border-color:rgba(0,200,83,.18)}
            .readonly-note{background:var(--card);color:var(--text-sec);border-top-color:var(--border)}
            .preview{left:14px;bottom:86px}
            .chat-error{position:absolute;left:12px;right:12px;top:-46px;z-index:10}
            .empty-state{display:none}
            .typing-line{padding:0 16px 8px;color:var(--primary)}
            .mobile-sheet{width:100%;height:var(--novaskol-vh,100dvh);max-height:none;border-radius:0;padding:16px}
            .chat-head-actions{gap:8px}
            .media-viewer{width:100%;height:var(--novaskol-vh,100dvh);max-height:none;border-radius:0;padding:14px}
            .media-viewer-body{max-height:none;min-height:0;flex:1}
            .media-viewer-body iframe,.media-viewer-body img{max-height:100%;height:100%}
        }
    </style>
</head>
<body class="{{ $mobileState }}">
@include('modules.professeur.bulletin.partials.shell')
<header>
    <div class="header-left">
        <button class="burger-menu" onclick="toggleSidebar()"><i class="fa fa-bars"></i></button>
        <button id="fullscreen-btn" onclick="toggleFullscreen()"><i class="fa fa-expand"></i></button>
    </div>
    <div class="header-center">Chat groupe</div>
</header>
<main>
    <section class="group-shell">
        <aside class="panel list-panel">
            <div class="mobile-list-head">
                <div class="mobile-list-head-main">
                    <button class="mobile-menu-btn" type="button" onclick="toggleSidebar()"><i class="fa fa-bars"></i></button>
                    <h2>Groupes</h2>
                </div>
                <details class="mobile-top-menu">
                    <summary><i class="fa fa-ellipsis-v"></i></summary>
                    <div class="mobile-menu-card">
                        @if ($canManageGroups ?? false)
                            <button type="button" onclick="openModal('createGroupModal')"><i class="fa fa-users"></i> Nouveau groupe</button>
                        @endif
                        <button type="button" onclick="novaskolToggleTheme()"><i class="fa fa-adjust"></i> Mode clair / sombre</button>
                    </div>
                </details>
            </div>
            <div class="panel-pad desktop-only">
                @if ($canManageGroups ?? false)
                    <details>
                        <summary><i class="fa fa-plus"></i> Nouveau groupe</summary>
                        <form class="group-form" method="POST" action="{{ route('modules.chat-groupe.store') }}" enctype="multipart/form-data">
                            @csrf
                            <input type="text" name="name" placeholder="Nom du groupe" required>
                            <input type="file" name="avatar" accept="image/*">
                            <div class="members">
                                @foreach ($users as $user)
                                    <label><input type="checkbox" name="members[]" value="{{ $user->id }}"> {{ $user->nom }}</label>
                                @endforeach
                            </div>
                            <button class="small-btn" type="submit">Creer</button>
                        </form>
                    </details>
                @else
                    <div class="muted"><i class="fa fa-bullhorn"></i> Canal d'annonces de l'ecole</div>
                @endif
            </div>
            <div class="group-search-wrap"><input id="groupSearch" type="text" placeholder="Rechercher un groupe"></div>
            @if ($canManageGroups ?? false)
                <div class="mobile-quick-actions">
                    <button type="button" class="ghost-btn" onclick="openModal('createGroupModal')"><i class="fa fa-users"></i> Nouveau groupe</button>
                </div>
            @endif
            <div class="groups" id="groups">
                @foreach ($groups as $group)
                    @php
                        $avatar = $group->avatar ? asset('legacy/uploads/group_avatars/'.$group->avatar) : asset('legacy/images/default-group.png');
                        $isAnnouncement = (int) ($group->is_announcement ?? 0) === 1;
                    @endphp
                    <a class="group-link {{ $selectedGroupId === (int) $group->id ? 'active' : '' }}" href="{{ route('modules.chat-groupe', ['group' => $group->id]) }}" data-name="{{ strtolower($group->name) }}" data-group-id="{{ $group->id }}">
                        <img class="avatar" src="{{ $avatar }}" alt="">
                        <span class="grow">
                            <strong>{{ $group->name }}</strong>
                            <span class="muted"><i class="{{ $isAnnouncement ? 'fa fa-bullhorn' : 'status-dot' }}"></i> {{ $isAnnouncement ? 'annonces officielles' : ((int)$group->creator_id === $currentUserId ? 'createur' : 'membre') }}</span>
                        </span>
                        @if ($group->unread_count > 0)<span class="unread">{{ $group->unread_count }}</span>@endif
                    </a>
                @endforeach
            </div>
        </aside>
        <section class="panel conversation-panel">
            @if ($selectedGroup)
                <div class="chat-head">
                    <a class="mobile-back-btn" href="{{ route('modules.chat-groupe') }}"><i class="fa fa-arrow-left"></i></a>
                    <button class="group-trigger" type="button" onclick="openGroupInfo()">
                        <img class="avatar" src="{{ $groupAvatar }}" alt="">
                        <span class="chat-head-copy">
                            <strong>{{ $selectedGroup->name }}</strong>
                            <span class="muted" style="display:block;">{{ count($membersByGroup) }} membre(s)</span>
                            @if($selectedIsAnnouncement)<span class="announcement-badge"><i class="fa fa-bullhorn"></i> Lecture pour tous, publication admin</span>@endif
                        </span>
                    </button>
                    <div class="chat-head-actions">
                        <details class="mobile-top-menu">
                            <summary><i class="fa fa-ellipsis-v"></i></summary>
                            <div class="mobile-menu-card">
                                <button type="button" onclick="openGroupInfo()"><i class="fa fa-info-circle"></i> Infos du groupe</button>
                                @if ($isGroupOwner && ! $selectedIsAnnouncement && ($canManageGroups ?? false))
                                    <button type="button" onclick="openModal('editGroupModal')"><i class="fa fa-edit"></i> Modifier le groupe</button>
                                    <button type="button" onclick="submitDeleteGroup('deleteGroupFormMobile')"><i class="fa fa-trash"></i> Supprimer le groupe</button>
                                @endif
                                <button type="button" onclick="refreshMessages()"><i class="fa fa-refresh"></i> Recharger</button>
                                <button type="button" onclick="novaskolToggleTheme()"><i class="fa fa-adjust"></i> Mode clair / sombre</button>
                            </div>
                        </details>
                    </div>
                </div>
                <div class="messages" id="messages"></div>
                <div class="typing-line" id="typingLine">Vous ecrivez...</div>
                <div class="preview" id="filePreview"><div id="previewContent"></div><button type="button" onclick="clearFilePreview()">x</button></div>
                @if ($canWriteSelectedGroup)
                    <form class="composer" id="composer" enctype="multipart/form-data">
                        <div class="chat-error" id="chatError"></div>
                        <input type="text" name="content" id="messageInput" placeholder="{{ $selectedIsAnnouncement ? 'Publier une annonce officielle' : 'Ecrire dans le groupe' }}">
                        <label class="file-btn"><i class="fa fa-paperclip"></i><input type="file" name="file" id="fileInput" style="display:none"></label>
                        <button class="send-btn" type="submit"><i class="fa fa-send"></i></button>
                    </form>
                @else
                    <div class="readonly-note"><i class="fa fa-lock"></i> Canal en lecture seule. Seuls les administrateurs peuvent publier une annonce ici.</div>
                @endif
            @else
                <div class="empty-state">Choisis un groupe ou cree une nouvelle discussion.</div>
            @endif
        </section>
    </section>
    @if ($selectedGroup)
        <div class="modal-backdrop" id="groupModal" onclick="if(event.target===this)closeGroupInfo()">
            <div class="profile-modal">
                <button class="modal-close" onclick="closeGroupInfo()">x</button>
                <div class="profile-top">
                    <img src="{{ $groupAvatar }}" alt="">
                    <div><h2>{{ $selectedGroup->name }}</h2><div class="muted">Groupe #{{ $selectedGroup->id }}</div></div>
                </div>
                <div class="profile-line"><strong>Createur:</strong> {{ $isGroupOwner ? 'Vous' : 'Utilisateur #'.$selectedGroup->creator_id }}</div>
                <div class="profile-line"><strong>Membres:</strong> {{ count($membersByGroup ?? []) }}</div>
                <div class="profile-line"><strong>Derniere mise a jour:</strong> {{ $selectedGroup->updated_at }}</div>
            </div>
        </div>
    @endif
    @if ($canManageGroups ?? false)
        <div class="modal-backdrop" id="createGroupModal" onclick="if(event.target===this)closeModal('createGroupModal')">
            <div class="mobile-sheet">
                <button class="modal-close" type="button" onclick="closeModal('createGroupModal')">x</button>
                <h2>Nouveau groupe</h2>
                <div class="muted">Choisis les membres et le nom du groupe.</div>
                <form method="POST" action="{{ route('modules.chat-groupe.store') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="text" name="name" placeholder="Nom du groupe" required>
                    <input type="file" name="avatar" accept="image/*">
                    <input class="member-search" type="text" placeholder="Rechercher un membre" oninput="filterMemberList(this,'createMembers')">
                    <div class="members" id="createMembers">
                        @foreach ($users as $user)
                            <label data-search="{{ strtolower($user->nom) }}"><input type="checkbox" name="members[]" value="{{ $user->id }}"> {{ $user->nom }}</label>
                        @endforeach
                    </div>
                    <div class="mobile-modal-actions">
                        <button class="ghost-btn" type="button" onclick="closeModal('createGroupModal')">Annuler</button>
                        <button class="small-btn" type="submit">Creer</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
    @if ($selectedGroup && $isGroupOwner && ! $selectedIsAnnouncement && ($canManageGroups ?? false))
        <div class="modal-backdrop" id="editGroupModal" onclick="if(event.target===this)closeModal('editGroupModal')">
            <div class="mobile-sheet">
                <button class="modal-close" type="button" onclick="closeModal('editGroupModal')">x</button>
                <h2>Modifier le groupe</h2>
                <div class="muted">Mets a jour le nom, l'image ou les membres.</div>
                <form method="POST" action="{{ route('modules.chat-groupe.update', $selectedGroup->id) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="text" name="name" value="{{ $selectedGroup->name }}" required>
                    <input type="file" name="avatar" accept="image/*">
                    <input class="member-search" type="text" placeholder="Rechercher un membre" oninput="filterMemberList(this,'editMembers')">
                    <div class="members" id="editMembers">
                        @foreach ($users as $user)
                            <label data-search="{{ strtolower($user->nom) }}"><input type="checkbox" name="members[]" value="{{ $user->id }}" @checked(in_array((int) $user->id, $membersByGroup, true))> {{ $user->nom }}</label>
                        @endforeach
                    </div>
                    <div class="mobile-modal-actions">
                        <button class="ghost-btn" type="button" onclick="closeModal('editGroupModal')">Annuler</button>
                        <button class="small-btn" type="submit">Enregistrer</button>
                    </div>
                </form>
                <form id="deleteGroupFormMobile" method="POST" action="{{ route('modules.chat-groupe.delete', $selectedGroup->id) }}" class="js-confirm-submit" data-confirm-title="Supprimer ce groupe ?" data-confirm-text="Le groupe et ses messages seront supprimes.">
                    @csrf
                    @method('DELETE')
                </form>
            </div>
        </div>
    @endif
    <div class="message-menu" id="messageMenu">
        <button type="button" data-action="copy"><i class="fa fa-copy"></i> Copier</button>
        <button type="button" data-action="edit" data-mine-only="1"><i class="fa fa-edit"></i> Modifier</button>
        <button type="button" data-action="delete" data-mine-only="1"><i class="fa fa-trash"></i> Supprimer</button>
    </div>
    <div class="modal-backdrop" id="mediaModal" onclick="if(event.target===this)closeMediaModal()">
        <div class="media-viewer">
            <div class="media-viewer-head">
                <h3 id="mediaModalTitle">Apercu</h3>
                <button class="modal-close" type="button" onclick="closeMediaModal()">x</button>
            </div>
            <div class="media-viewer-body" id="mediaModalBody"></div>
            <div class="media-viewer-actions">
                <a class="text-link-btn" id="mediaModalDownload" href="#" download><i class="fa fa-download"></i> Telecharger</a>
            </div>
        </div>
    </div>
</main>
<script>
const csrf = document.querySelector('meta[name="csrf-token"]').content;
const conversationId = {{ (int) $selectedGroupId }};
const canWriteSelectedGroup = @json($canWriteSelectedGroup ?? false);
const activeGroupId = {{ (int) $selectedGroupId }};
const chatMessagesBaseUrl = @json(url('/chat/messages'));
const chatMessageBaseUrl = @json(url('/chat/message'));
const chatTypingBaseUrl = @json(url('/chat/typing'));
let lastId = 0;
let initialMessagesLoaded = false;
let selectedMessage = null;
let longPressTimer = null;
let typingActive = false;
let typingIdleTimer = null;
function resetComposerState(form){form?.reset();const input=document.getElementById('messageInput');const file=document.getElementById('fileInput');const preview=document.getElementById('filePreview');const previewContent=document.getElementById('previewContent');if(input){input.value='';input.focus();}if(file) file.value='';if(previewContent) previewContent.innerHTML='';if(preview) preview.style.display='none';}
function toggleSidebar(){document.getElementById('sidebar').classList.toggle('active');}
function toggleSub(el){const n=el.nextElementSibling;if(n)n.style.display=n.style.display==='none'?'block':'none';}
function toggleFullscreen(){if(!document.fullscreenElement){document.documentElement.requestFullscreen();}else{document.exitFullscreen();}}
function openGroupInfo(){document.getElementById('groupModal').style.display='flex';}
function closeGroupInfo(){document.getElementById('groupModal').style.display='none';}
function openModal(id){document.getElementById(id)?.style.setProperty('display','flex');}
function closeModal(id){document.getElementById(id)?.style.setProperty('display','none');}
function openMediaModal(kind, href, title){const modal=document.getElementById('mediaModal');const body=document.getElementById('mediaModalBody');const heading=document.getElementById('mediaModalTitle');const download=document.getElementById('mediaModalDownload');if(!modal||!body||!heading||!download)return;heading.textContent=title||'Apercu';body.innerHTML=kind==='image'?'<img src="'+href+'" alt="">':'<iframe src="'+href+'" title="'+escapeHtml(title||'Fichier')+'"></iframe>';download.href=href;download.setAttribute('download',title||'telechargement');modal.style.display='flex';}
function closeMediaModal(){const body=document.getElementById('mediaModalBody');const modal=document.getElementById('mediaModal');if(body) body.innerHTML='';if(modal) modal.style.display='none';}
function submitDeleteGroup(formId='deleteGroupFormMobile'){const form=document.getElementById(formId);if(!form)return;if(typeof form.requestSubmit==='function'){form.requestSubmit();return;}form.submit();}
function showError(text){const e=document.getElementById('chatError');if(e){e.textContent=text;e.style.display='block';}}
function hideError(){const e=document.getElementById('chatError');if(e){e.style.display='none';e.textContent='';}}
function refreshMessages(){lastId=0;initialMessagesLoaded=false;const box=document.getElementById('messages');if(box)box.innerHTML='';loadMessages();}
function clearActiveUnreadBadge(){document.querySelector('.group-link.active .unread')?.remove();}
function syncMobileViewport(){const vh=window.visualViewport?window.visualViewport.height:window.innerHeight;document.documentElement.style.setProperty('--novaskol-vh',vh+'px');setTimeout(()=>keepLatestVisible(),40);}
function keepLatestVisible(force=false){const box=document.getElementById('messages');const input=document.getElementById('messageInput');if(!box)return;const nearBottom=(box.scrollHeight-box.scrollTop-box.clientHeight)<140;const typing=input&&document.activeElement===input;if(force||typing||nearBottom)box.scrollTop=box.scrollHeight;}
function filterMemberList(input,listId){const q=String(input.value||'').toLowerCase().trim();document.querySelectorAll('#'+listId+' label[data-search]').forEach(label=>{label.style.display=!q||label.dataset.search.includes(q)?'flex':'none';});}
function escapeHtml(v){return String(v).replace(/[&<>\"']/g,s=>({'&':'&amp;','<':'&lt;','>':'&gt;','\"':'&quot;',"'":'&#039;'}[s]));}
document.getElementById('groupSearch')?.addEventListener('input',e=>{const q=String(e.target.value||'').toLowerCase().trim();document.querySelectorAll('#groups .group-link').forEach(link=>{link.style.display=!q||String(link.dataset.name||'').includes(q)?'flex':'none';});});
function messageAttachmentHtml(m){if(!m.file_url)return '';if(m.type==='image') return '<a href="'+m.file_url+'" class="js-media-link" data-kind="image" data-title="'+escapeHtml(m.file_name||'Image')+'"><img class="chat-image" src="'+m.file_url+'" alt=""></a>';return '<a class="file-link js-media-link" href="'+m.file_url+'" data-kind="file" data-title="'+escapeHtml(m.file_name||'Fichier')+'"><i class="fa fa-file"></i>'+escapeHtml(m.file_name||'Fichier')+'</a>';}
function messageStatusHtml(m){if(m.is_read) return '<span class="meta-status read">&#10003;&#10003;</span>';if(m.is_delivered) return '<span class="meta-status delivered">&#10003;&#10003;</span>';return '<span class="meta-status sent">&#10003;</span>';}
function messageHtml(m){const file=messageAttachmentHtml(m);return '<div class="bubble '+(m.mine?'mine':'')+'" data-message-id="'+m.id+'" data-mine="'+(m.mine?'1':'0')+'" data-content="'+escapeHtml(m.content||'')+'">'+(m.mine?'':'<div class="bubble-name">'+escapeHtml(m.sender_name)+'</div>')+'<div class="bubble-text">'+escapeHtml(m.content||'')+'</div>'+(file?'<div class="bubble-attachment">'+file+'</div>':'')+'<div class="bubble-meta"><span class="bubble-time">'+escapeHtml(m.created_label||'')+'</span>'+(m.mine?messageStatusHtml(m):'')+'</div></div>';}
function syncMessageNode(node,m){node.dataset.content=m.content||'';const text=node.querySelector('.bubble-text');if(text) text.textContent=m.content||'';const attachmentHtml=messageAttachmentHtml(m);let attachment=node.querySelector('.bubble-attachment');if(attachmentHtml){if(!attachment){attachment=document.createElement('div');attachment.className='bubble-attachment';node.querySelector('.bubble-meta')?.before(attachment);}attachment.innerHTML=attachmentHtml;}else if(attachment){attachment.remove();}const meta=node.querySelector('.bubble-meta');if(meta){const time=meta.querySelector('.bubble-time');if(time) time.textContent=m.created_label||'';const currentStatus=meta.querySelector('.meta-status');if(m.mine){const temp=document.createElement('div');temp.innerHTML=messageStatusHtml(m);if(currentStatus) currentStatus.replaceWith(temp.firstElementChild);else meta.appendChild(temp.firstElementChild);}else if(currentStatus){currentStatus.remove();}}}
function renderTyping(users=[]){const line=document.getElementById('typingLine');if(!line)return;if(!users.length){line.style.display='none';line.innerHTML='';return;}let label='';if(users.length===1)label=escapeHtml(users[0])+' ecrit';else if(users.length===2)label=escapeHtml(users[0])+' et '+escapeHtml(users[1])+' ecrivent';else label='Plusieurs personnes ecrivent';line.innerHTML='<span>'+label+'</span><span class="typing-dots"><span></span><span></span><span></span></span>';line.style.display='flex';}
async function sendTyping(active){if(!conversationId) return;if(active===typingActive&&active) return;typingActive=active;try{await fetch(chatTypingBaseUrl+'/'+conversationId,{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':csrf,'Accept':'application/json'},body:JSON.stringify({typing:active})});}catch(_){}}
function queueTyping(){if(!conversationId) return;sendTyping(true);clearTimeout(typingIdleTimer);typingIdleTimer=setTimeout(()=>sendTyping(false),1400);}
async function loadMessages(){if(!conversationId) return;const box=document.getElementById('messages');if(!box) return;const stickToBottom=!initialMessagesLoaded||(box.scrollHeight-box.scrollTop-box.clientHeight<120);const res=await fetch(chatMessagesBaseUrl+'/'+conversationId,{headers:{'Accept':'application/json'}});const data=await res.json();if(!data.success) return;let appended=false;data.messages.forEach(m=>{lastId=Math.max(lastId,Number(m.id));const existing=box.querySelector('.bubble[data-message-id="'+m.id+'"]');if(existing){syncMessageNode(existing,m);return;}box.insertAdjacentHTML('beforeend',messageHtml(m));appended=true;if(initialMessagesLoaded&&!m.mine) playChatSound();});if((appended&&stickToBottom)||!initialMessagesLoaded||document.activeElement===document.getElementById('messageInput')) box.scrollTop=box.scrollHeight;initialMessagesLoaded=true;clearActiveUnreadBadge();renderTyping(data.typing_users||[]);}
document.getElementById('composer')?.addEventListener('submit',async e=>{e.preventDefault();hideError();clearTimeout(typingIdleTimer);sendTyping(false);const form=new FormData(e.currentTarget);const res=await fetch(chatMessagesBaseUrl+'/'+conversationId,{method:'POST',headers:{'X-CSRF-TOKEN':csrf,'Accept':'application/json'},body:form});let data={};try{data=await res.json();}catch(_){ }if(res.ok&&data.success){resetComposerState(e.currentTarget);if(data.message){lastId=Math.max(lastId,Number(data.message.id));document.getElementById('messages').insertAdjacentHTML('beforeend',messageHtml(data.message));document.getElementById('messages').scrollTop=document.getElementById('messages').scrollHeight;}await loadMessages();}else{showError(data.message||data.error||'Envoi impossible pour le moment.');}});
document.getElementById('fileInput')?.addEventListener('change',e=>{const file=e.target.files[0];const wrap=document.getElementById('filePreview');const content=document.getElementById('previewContent');if(!file){clearFilePreview();return;}if(file.type.startsWith('image/')){const url=URL.createObjectURL(file);content.innerHTML='<img src="'+url+'" alt="">';}else{content.innerHTML='<span class="file-chip"><i class="fa fa-file"></i> Fichier joint</span>';}wrap.style.display='flex';});
function clearFilePreview(){const input=document.getElementById('fileInput');if(input) input.value='';const content=document.getElementById('previewContent');if(content) content.innerHTML='';const wrap=document.getElementById('filePreview');if(wrap) wrap.style.display='none';}
function playChatSound(){try{const ctx=new (window.AudioContext||window.webkitAudioContext)();const osc=ctx.createOscillator();const gain=ctx.createGain();osc.type='sine';osc.frequency.value=660;gain.gain.value=.05;osc.connect(gain);gain.connect(ctx.destination);osc.start();setTimeout(()=>{osc.stop();ctx.close();},110)}catch(e){}}
function openMessageMenu(x,y,bubble){selectedMessage=bubble;document.querySelectorAll('.bubble').forEach(b=>b.classList.remove('context-target'));bubble.classList.add('context-target');const mine=bubble.dataset.mine==='1';document.querySelectorAll('#messageMenu [data-mine-only]').forEach(btn=>btn.style.display=(mine&&canWriteSelectedGroup)?'flex':'none');const menu=document.getElementById('messageMenu');menu.style.left=Math.min(x,window.innerWidth-190)+'px';menu.style.top=Math.min(y,window.innerHeight-150)+'px';menu.style.display='block'}
function closeMessageMenu(){document.getElementById('messageMenu').style.display='none';document.querySelectorAll('.bubble.context-target').forEach(b=>b.classList.remove('context-target'))}
document.getElementById('messages')?.addEventListener('contextmenu',e=>{const bubble=e.target.closest('.bubble');if(!bubble)return;e.preventDefault();openMessageMenu(e.clientX,e.clientY,bubble)});
document.getElementById('messages')?.addEventListener('touchstart',e=>{const bubble=e.target.closest('.bubble');if(!bubble)return;longPressTimer=setTimeout(()=>openMessageMenu(e.touches[0].clientX,e.touches[0].clientY,bubble),520)},{passive:true});
document.getElementById('messages')?.addEventListener('click',e=>{const link=e.target.closest('.js-media-link');if(!link)return;e.preventDefault();openMediaModal(link.dataset.kind||'file',link.getAttribute('href'),link.dataset.title||'Apercu');});
['touchend','touchmove','touchcancel'].forEach(evt=>document.getElementById('messages')?.addEventListener(evt,()=>clearTimeout(longPressTimer),{passive:true}));
document.addEventListener('click',e=>{if(!e.target.closest('#messageMenu'))closeMessageMenu()});
document.getElementById('messageMenu')?.addEventListener('click',async e=>{const btn=e.target.closest('button');if(!btn||!selectedMessage)return;const id=selectedMessage.dataset.messageId;const content=selectedMessage.dataset.content||'';if(btn.dataset.action==='copy'){navigator.clipboard?.writeText(content);closeMessageMenu();return;}if(btn.dataset.action==='edit'){const r=await Swal.fire({title:'Modifier le message',input:'textarea',inputValue:content,showCancelButton:true,confirmButtonText:'Modifier',cancelButtonText:'Annuler'});if(!r.isConfirmed)return;const res=await fetch(chatMessageBaseUrl+'/'+id,{method:'PUT',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':csrf,'Accept':'application/json'},body:JSON.stringify({content:r.value})});const data=await res.json();if(data.success){selectedMessage.querySelector('.bubble-text').textContent=data.message.content;selectedMessage.dataset.content=data.message.content;}}if(btn.dataset.action==='delete'){const ok=await Swal.fire({title:'Supprimer ce message ?',icon:'warning',showCancelButton:true,confirmButtonText:'Supprimer',cancelButtonText:'Annuler'});if(!ok.isConfirmed)return;const res=await fetch(chatMessageBaseUrl+'/'+id,{method:'DELETE',headers:{'X-CSRF-TOKEN':csrf,'Accept':'application/json'}});const data=await res.json();if(data.success)selectedMessage.remove();}closeMessageMenu();});
document.getElementById('messageInput')?.addEventListener('input',()=>{queueTyping();keepLatestVisible(true)});
document.getElementById('messageInput')?.addEventListener('focus',()=>keepLatestVisible(true));
window.visualViewport?.addEventListener('resize', syncMobileViewport);
window.addEventListener('resize', syncMobileViewport);
window.addEventListener('load', syncMobileViewport);
syncMobileViewport();
loadMessages();
if(conversationId) setInterval(loadMessages, 700);
</script>
</body>
</html>
