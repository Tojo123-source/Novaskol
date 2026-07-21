<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mon espace - {{ $ecole->nom ?? 'Novaskol' }}</title>
    <link rel="stylesheet" href="{{ asset('legacy/assets/fontawesome/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('legacy/fa/css/font-awesome.min.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ asset('novaskol-icon.png') }}">
    <script src="{{ asset('legacy/vendor/qrcode.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>
    @include('modules.professeur.bulletin.partials.styles')
    <style>
        .role-hero{margin:96px 24px 24px 264px;padding:26px;border:1px solid var(--border);background:linear-gradient(135deg,var(--card),var(--surface));border-radius:8px;box-shadow:0 16px 36px var(--shadow-soft)}
        .role-hero h1{margin:0 0 8px;color:var(--primary);font-size:1.65rem}
        .role-hero p{margin:0;color:var(--text-sec)}
        .role-grid{margin:0 24px 24px 264px;display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:14px}
        .role-card{background:var(--card);border:1px solid var(--border);border-radius:8px;padding:18px;box-shadow:0 10px 26px var(--shadow-soft)}
        .role-card i{width:38px;height:38px;border-radius:8px;display:grid;place-items:center;background:var(--primary-glow);color:var(--primary);font-size:1.1rem;margin-bottom:12px}
        .role-card strong{display:block;color:var(--text);font-size:1.2rem;line-height:1.25;word-break:break-word}
        .role-card span{display:block;color:var(--text-sec);font-size:.88rem;margin-top:5px}
        .workspace{margin:0 24px 30px 264px;display:grid;grid-template-columns:minmax(0,1.5fr) minmax(300px,.8fr);gap:16px}
        .panel{background:var(--card);border:1px solid var(--border);border-radius:8px;padding:18px;box-shadow:0 10px 26px var(--shadow-soft)}
        .panel h2{margin:0 0 14px;color:var(--primary);font-size:1.05rem}
        .module-list{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:10px}
        .module-tile{display:flex;align-items:center;gap:12px;padding:13px;border:1px solid var(--border);border-radius:8px;background:var(--surface);color:var(--text);text-decoration:none}
        .module-tile:hover{border-color:var(--primary);transform:translateY(-1px)}
        .module-tile i{width:34px;height:34px;border-radius:8px;display:grid;place-items:center;background:var(--primary-glow);color:var(--primary)}
        .module-tile small{display:block;color:var(--text-sec);margin-top:2px}
        .teacher-box{margin-bottom:14px;padding:16px;border:1px solid var(--border);border-radius:8px;background:linear-gradient(135deg,var(--surface),var(--card))}
        .teacher-box strong{display:block;color:var(--primary);font-size:1.05rem;margin-bottom:6px}.teacher-box p{margin:0 0 12px;color:var(--text-sec)}
        .notice{padding:12px 0;border-bottom:1px solid var(--border)}
        .notice:last-child{border-bottom:0}
        .notice strong{display:block;color:var(--text);margin-bottom:3px}
        .notice small{color:var(--text-sec)}
        .empty-state{padding:18px;border:1px dashed var(--border);border-radius:8px;color:var(--text-sec);text-align:center}

        :root { --nv-primary: #0f2942; --nv-gold: #c9a84c; --nv-white: #ffffff; --nv-text: #1e293b; --nv-muted: #64748b; }
        .espace-cards {margin:0 24px 18px 264px;display:grid;grid-template-columns:repeat(auto-fill,minmax(380px,1fr));gap:14px}
        .id-card {
            position:relative;display:flex;background:var(--nv-white);color:var(--nv-text);
            border-radius:12px;overflow:hidden;border:1px solid #e2e8f0;
            box-shadow:0 2px 12px rgba(15,41,66,.06);font-family:'Inter',sans-serif;min-height:120px;
        }
        .id-card::before {
            content:'';position:absolute;top:0;left:0;width:4px;height:100%;background:var(--nv-gold);
        }
        .id-card.type-enseignant::before { background: #0ea5e9; }
        .id-card.type-staff::before { background: #10b981; }
        .id-photo-wrap {
            flex:0 0 100px;display:flex;align-items:center;justify-content:center;
            padding:10px 0 10px 10px;
        }
        .id-photo {
            width:86px;height:110px;object-fit:cover;border-radius:8px;
            border:2px solid #e2e8f0;background:#f1f5f9;display:block;
        }
        .id-body {
            flex:1;padding:10px 8px;display:flex;flex-direction:column;
            justify-content:center;min-width:0;
        }
        .id-badge {
            display:inline-block;background:var(--nv-primary);color:var(--nv-white);
            border-radius:4px;padding:2px 10px;
            font-size:.62rem;font-weight:700;text-transform:uppercase;
            letter-spacing:.5px;margin-bottom:4px;width:fit-content;
        }
        .id-card.type-enseignant .id-badge { background: #0ea5e9; }
        .id-card.type-staff .id-badge { background: #10b981; }
        .id-name {
            color:var(--nv-primary)!important;margin:0 0 1px 0;
            font-size:.85rem;font-weight:700;line-height:1.25;
            white-space:nowrap;overflow:hidden;text-overflow:ellipsis;
        }
        .id-meta {font-size:.66rem;color:var(--nv-muted);line-height:1.5;margin:0;}
        .id-meta strong {color:var(--nv-primary);font-weight:600;}
        .id-school {
            display:block;margin-top:3px;font-size:.6rem;color:var(--nv-gold);
            font-weight:700;border-top:1px solid #e2e8f0;padding-top:3px;
            letter-spacing:.3px;text-transform:uppercase;line-height:1.2;
        }
        .id-qr-wrap {
            flex:0 0 120px;display:flex;align-items:center;justify-content:center;
            padding:6px;background:#f8fafc;border-left:1px solid #e2e8f0;
        }
        .id-qr-box {
            display:grid;place-items:center;
            width:108px;height:108px;border-radius:8px;background:var(--nv-white);
        }
        .id-qr-box canvas,.id-qr-box img {width:96px!important;height:96px!important;}
        .id-expiry {font-size:.5rem;color:var(--nv-muted);text-align:center;margin-top:2px;}

        @media(max-width:1180px){.role-hero,.role-grid,.workspace,.espace-cards{margin-left:16px;margin-right:16px}.role-grid{grid-template-columns:repeat(2,minmax(0,1fr))}.workspace{grid-template-columns:1fr}.role-hero{margin-top:98px}.module-list{grid-template-columns:1fr}.espace-cards{grid-template-columns:1fr}}
        .id-dl-btn{display:flex;align-items:center;justify-content:center;gap:4px;width:100%;margin-top:6px;padding:5px;font-size:.65rem;font-weight:600;border:1px solid var(--border);border-radius:6px;background:var(--nv-white);color:var(--nv-primary);cursor:pointer;transition:all .15s}
        .id-dl-btn:hover{background:var(--nv-primary);color:var(--nv-white);border-color:var(--nv-primary)}
        @media(max-width:700px){.role-grid{grid-template-columns:1fr}.role-hero{margin-top:122px;padding:18px}.role-hero h1{font-size:1.3rem}.role-card{padding:16px}.id-card{flex-wrap:wrap}.id-qr-wrap{flex:1;border-left:0;border-top:1px solid #e2e8f0;padding:12px 8px}}
    </style>
</head>
<body>
@include('modules.professeur.bulletin.partials.shell', ['activeModule' => 'role_dashboard'])
<header>
    <div class="header-left">
        <button class="burger-menu" onclick="toggleSidebar()"><i class="fa fa-bars"></i></button>
        <button id="fullscreen-btn" onclick="toggleFullscreen()"><i class="fa fa-expand"></i></button>
    </div>
    <div class="header-center">Mon espace</div>
</header>

<section class="role-hero">
    <h1>Bonjour {{ $user['nom'] ?? 'Utilisateur' }}</h1>
    <p>Voici votre espace {{ $user['role'] ?? 'utilisateur' }}. Les raccourcis ci-dessous suivent exactement vos permissions.</p>
</section>

@if($cardData)
    <section class="espace-cards">
        @foreach(($user['role'] ?? '') === 'parent' ? $cardData : [$cardData] as $card)
            <article class="id-card type-{{ $card['badge_type'] }}">
                <div class="id-photo-wrap">
                    <img class="id-photo" src="{{ asset('legacy/'.ltrim($card['photo'], '/')) }}" alt="" loading="lazy">
                </div>
                <div class="id-body">
                    <span class="id-badge">{{ $card['badge'] }}</span>
                    <h3 class="id-name" title="{{ $card['nom'] }} {{ $card['prenom'] }}">{{ $card['nom'] }} {{ $card['prenom'] }}</h3>
                    <p class="id-meta">
                        <strong>ID :</strong> {{ $card['matricule'] }}<br>
                        @if($card['dept_info'])<strong>{{ $card['dept_label'] }} :</strong> {{ $card['dept_info'] }}<br>@endif
                        <strong>Annee :</strong> {{ $card['annee_scolaire'] }}
                    </p>
                    <span class="id-school">{{ $card['ecole_nom'] }}</span>
                </div>
                <div class="id-qr-wrap">
                    @if($card['qr_token'])
                        <div>
                            <div class="id-qr-box" id="qr-{{ $card['id'] }}" data-qr="novaskol:qr:v1:{{ $card['qr_token'] }}"></div>
                            <div class="id-expiry">Exp: {{ now()->addYear()->format('d/m/Y') }}</div>
                        </div>
                    @else
                        <div>
                            <div class="id-qr-box" style="background:#f1f5f9;color:#94a3b8;font-size:.62rem;text-align:center;display:grid;place-items:center;width:108px;height:108px;border-radius:8px;line-height:1.4;">
                                QR<br>non<br>disponible
                            </div>
                        </div>
                    @endif
                    <button onclick="downloadCard(this)" class="id-dl-btn" title="Telecharger la carte"><i class="fa fa-download"></i></button>
                </div>
            </article>
        @endforeach
    </section>
@endif

<section class="role-grid">
    @foreach($stats as $stat)
        <article class="role-card">
            <i class="fa {{ $stat['icon'] }}"></i>
            <strong>{{ $stat['value'] }}</strong>
            <span>{{ $stat['label'] }}</span>
        </article>
    @endforeach
</section>

<main class="workspace">
    <section class="panel">
        <h2>Modules disponibles</h2>
        @if($teacherWorkspace)
            <div class="teacher-box">
                <strong>Espace de travail enseignant</strong>
                <p>{{ $teacherWorkspace['classes'] }} classe(s), {{ $teacherWorkspace['lessons'] }} lecon(s), {{ $teacherWorkspace['tasks'] }} tache(s) ouvertes.</p>
                <a class="module-tile" href="{{ route('teacher.courses.index') }}"><i class="fa fa-book"></i><span>Mes cours<small>Creer et gerer les cours, chapitres et fichiers</small></span></a>
                <a class="module-tile" href="{{ route('teacher.exercices.index') }}"><i class="fa fa-puzzle-piece"></i><span>Exercices<small>QCM, Vrai/Faux, exercices interactifs</small></span></a>
                <a class="module-tile" href="{{ $teacherWorkspace['route'] }}"><i class="fa fa-pencil-square-o"></i><span>Journal pedagogique<small>Lecons, rubriques, planning et checklist</small></span></a>
            </div>
        @endif
        @if($parentPortal)
            <div class="teacher-box">
                <strong>Espace parent</strong>
                <p>{{ $parentPortal['children']->count() }} enfant(s) rattache(s) a ce compte.</p>
                <a class="module-tile" href="{{ $parentPortal['route'] }}"><i class="fa fa-child"></i><span>Suivi des enfants<small>Notes, presence, paiements et calendrier</small></span></a>
            </div>
        @endif
        @if($availableModules->isNotEmpty())
            <div class="module-list">
                @foreach($availableModules as $module)
                    @if(! empty($module['route']))
                        <a class="module-tile" href="{{ route($module['route']) }}">
                            <i class="fa {{ $module['icon'] }}"></i>
                            <span>{{ $module['label'] }}<small>{{ $module['access'] === 'ecriture' ? 'Lecture et ecriture' : 'Lecture seule' }}</small></span>
                        </a>
                    @endif
                @endforeach
            </div>
        @else
            <div class="empty-state">Aucun module actif pour ce compte. Un administrateur peut ajuster les permissions.</div>
        @endif
    </section>
    <aside class="panel">
        @if(($user['role'] ?? '') !== 'eleve' && ($user['role'] ?? '') !== 'parent')
            <h2>Dernieres notifications</h2>
            @forelse($latestNotifications as $notification)
                <div class="notice">
                    <strong>{{ ucfirst($notification->type ?? 'Notification') }}</strong>
                    <div>{{ $notification->message ?? '' }}</div>
                    <small>{{ ! empty($notification->date_creation) ? \Carbon\Carbon::parse($notification->date_creation)->format('d/m/Y H:i') : '' }}</small>
                </div>
            @empty
                <div class="empty-state">Aucune notification recente.</div>
            @endforelse
        @endif
        @if(($user['role'] ?? '') === 'staff')
            <h2 style="margin-top:16px"><i class="fa fa-calendar-check-o"></i> Ma presence</h2>
            @include('partials.presence-calendar', [
                'attendance' => $staffAttendance,
                'month' => $calMonth,
                'year' => $calYear,
                'baseUrl' => route('role.dashboard'),
                'label' => 'Presence',
            ])
        @endif
        @if(($user['role'] ?? '') === 'eleve')
            <h2 style="margin-top:0"><i class="fa fa-calendar-check-o"></i> Ma presence</h2>
            @include('partials.presence-calendar', [
                'attendance' => $studentAttendance,
                'month' => $calMonth,
                'year' => $calYear,
                'baseUrl' => route('role.dashboard'),
                'label' => 'Presence',
            ])
        @endif
    </aside>
</main>
<script>
document.addEventListener('DOMContentLoaded',()=>{
    document.querySelectorAll('.id-qr-box').forEach(function(q){
        const u=q.getAttribute('data-qr');
        if(u&&window.QRCode)new QRCode(q,{text:u,width:96,height:96,colorDark:'#0f2942',colorLight:'#ffffff',correctLevel:QRCode.CorrectLevel.H})
    })
})
function downloadCard(btn){
    const card=btn.closest('.id-card');
    if(!card)return;
    const name=card.querySelector('.id-name')?.textContent||'carte';
    html2canvas(card,{scale:2,useCORS:true,backgroundColor:'#ffffff'}).then(canvas=>{
        const a=document.createElement('a');
        a.href=canvas.toDataURL('image/png');
        a.download='carte-'+name.trim().replace(/\s+/g,'-')+'.png';
        a.click();
    })
}
</script>
</body>
</html>
