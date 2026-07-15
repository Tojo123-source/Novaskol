<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Comptes utilisateurs</title>
    <link rel="stylesheet" href="{{ asset('legacy/assets/fontawesome/css/all.min.css') }}">
    @include('modules.professeur.bulletin.partials.styles')
    @include('modules.parametres.partials.styles')
    <style>
        .account-kpis{display:grid;grid-template-columns:repeat(auto-fit,minmax(150px,1fr));gap:12px;margin-bottom:18px}
        .account-kpi{background:var(--surface);border:1px solid var(--border);border-radius:8px;padding:14px}
        .account-kpi span{display:block;color:var(--text-sec);font-size:.82rem;text-transform:uppercase;font-weight:800}
        .account-kpi strong{display:block;color:var(--primary);font-size:1.55rem;margin-top:5px}
        .account-toolbar{display:grid;grid-template-columns:minmax(220px,1fr) 190px auto;gap:12px;margin-bottom:16px}
        .account-list{display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:14px}
        .account-card{background:var(--surface);border:1px solid var(--border);border-radius:8px;padding:15px;display:grid;gap:12px}
        .account-top{display:flex;gap:12px;align-items:center;min-width:0}
        .account-avatar{width:54px;height:54px;border-radius:8px;object-fit:cover;border:1px solid var(--border);background:var(--card)}
        .account-name{min-width:0}.account-name strong{display:block;color:var(--text);white-space:nowrap;overflow:hidden;text-overflow:ellipsis}.account-name span{display:block;color:var(--text-sec);font-size:.86rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
        .role-badge{display:inline-flex;width:max-content;align-items:center;gap:6px;padding:5px 9px;border-radius:999px;background:rgba(0,200,83,.14);color:var(--primary);font-weight:900;font-size:.78rem;text-transform:uppercase}
        .account-meta{display:grid;gap:7px;color:var(--text-sec);font-size:.9rem}
        .account-actions{display:flex;gap:8px;justify-content:flex-end;align-items:center}
        .empty-box{padding:30px;text-align:center;color:var(--text-sec);border:1px dashed var(--border);border-radius:8px;background:var(--surface)}
        .pagination-wrap{margin-top:18px}.pagination-wrap nav{display:flex;justify-content:center}
        @media(max-width:760px){.account-toolbar{grid-template-columns:1fr}.account-actions{justify-content:stretch}.account-actions button{width:100%}}
    </style>
</head>
<body>
@include('modules.professeur.bulletin.partials.shell')
<header><div class="header-left"><button class="burger-menu" onclick="toggleSidebar()"><i class="fa fa-bars"></i></button><button id="fullscreen-btn" onclick="toggleFullscreen()"><i class="fa fa-expand"></i></button></div><div class="header-center"><i class="fa fa-id-card"></i> Comptes utilisateurs</div></header>
<main>
    <section class="settings-panel">
        <div class="settings-section">
            <h2>Maintenance des comptes</h2>
            <div class="account-kpis">
                <div class="account-kpi"><span>Admins</span><strong>{{ (int) ($stats['admin'] ?? 0) }}</strong></div>
                <div class="account-kpi"><span>Enseignants</span><strong>{{ (int) ($stats['enseignant'] ?? 0) }}</strong></div>
                <div class="account-kpi"><span>Staff</span><strong>{{ (int) ($stats['staff'] ?? 0) }}</strong></div>
                <div class="account-kpi"><span>Parents</span><strong>{{ (int) ($stats['parent'] ?? 0) }}</strong></div>
            </div>

            <form class="account-toolbar" method="GET" action="{{ route('modules.comptes-utilisateurs') }}">
                <input type="search" name="q" value="{{ $filters['search'] ?? '' }}" placeholder="Rechercher un nom, email ou eleve">
                <select name="role">
                    <option value="">Tous les roles</option>
                    <option value="parent" @selected(($filters['role'] ?? '') === 'parent')>Parents</option>
                    <option value="enseignant" @selected(($filters['role'] ?? '') === 'enseignant')>Enseignants</option>
                    <option value="staff" @selected(($filters['role'] ?? '') === 'staff')>Staff</option>
                    <option value="admin" @selected(($filters['role'] ?? '') === 'admin')>Admins</option>
                </select>
                <button class="kaly" type="submit"><i class="fa fa-search"></i> Filtrer</button>
            </form>
        </div>

        @if (session('success'))
            <div class="alert success">{{ session('success') }}</div>
        @endif
        @if ($errors->any())
            <div class="alert error">{{ $errors->first() }}</div>
        @endif

        @if ($accounts->isEmpty())
            <div class="empty-box">Aucun compte trouve pour ce filtre.</div>
        @else
            <div class="account-list">
                @foreach ($accounts as $account)
                    @php
                        $avatar = trim((string) ($account->avatar ?? ''));
                        $avatarUrl = $avatar === '' ? asset('legacy/images/default-avatar.png') : (str_starts_with($avatar, 'images/') || str_starts_with($avatar, 'uploads/') ? asset('legacy/'.$avatar) : asset('legacy/uploads/avatars/'.$avatar));
                    @endphp
                    <article class="account-card">
                        <div class="account-top">
                            <img class="account-avatar" src="{{ $avatarUrl }}" alt="">
                            <div class="account-name">
                                <strong>{{ $account->nom }}</strong>
                                <span>{{ $account->email }}</span>
                            </div>
                        </div>
                        <span class="role-badge"><i class="fa fa-user"></i> {{ $account->role }}</span>
                        <div class="account-meta">
                            <div><strong>Creation:</strong> {{ $account->cree_le ? date('d/m/Y H:i', strtotime($account->cree_le)) : 'Non precise' }}</div>
                            <div><strong>Derniere activite:</strong> {{ $account->last_activity ? date('d/m/Y H:i', strtotime($account->last_activity)) : 'Jamais' }}</div>
                            @if ($account->role === 'parent')
                                <div><strong>Enfants lies:</strong> {{ (int) $account->enfants_count }}{{ $account->enfants ? ' - '.$account->enfants : '' }}</div>
                            @endif
                        </div>
                        <div class="account-actions">
                            @if ($account->role === 'admin')
                                <span class="muted">Protege</span>
                            @else
                                <form method="POST" action="{{ route('modules.comptes-utilisateurs.delete', $account->id) }}" class="js-confirm-submit" data-confirm-title="Supprimer ce compte ?" data-confirm-text="Les permissions, liens parent-eleve et discussions privees liees a ce compte seront nettoyes.">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn-small delete" type="submit"><i class="fa fa-trash"></i> Supprimer</button>
                                </form>
                            @endif
                        </div>
                    </article>
                @endforeach
            </div>
            <div class="pagination-wrap">{{ $accounts->links() }}</div>
        @endif
    </section>
    <footer>&copy; {{ date('Y') }} {{ $ecole->nom ?? 'Ecole' }}. Tous droits reserves.</footer>
</main>
<script>function toggleSidebar(){document.getElementById('sidebar').classList.toggle('active')}function toggleSub(e){const n=e.nextElementSibling;if(n)n.style.display=n.style.display==='none'?'block':'none'}function toggleFullscreen(){document.fullscreenElement?document.exitFullscreen():document.documentElement.requestFullscreen()}</script>
</body>
</html>
