<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mon espace - {{ $ecole->nom ?? 'Novaskol' }}</title>
    <link rel="stylesheet" href="{{ asset('legacy/assets/fontawesome/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('legacy/fa/css/font-awesome.min.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="{{ asset('legacy/vendor/qrcode.min.js') }}"></script>
    <script src="{{ asset('legacy/js/html2canvas.min.js') }}"></script>
    @include('modules.professeur.bulletin.partials.styles')
    <style>
        .connecte-header-actions{display:flex;gap:10px;flex-wrap:wrap}.connecte-btn{padding:10px 20px;border-radius:999px;border:1px solid var(--border);background:var(--card);color:var(--text);cursor:pointer;font-size:.9rem;font-weight:600;display:flex;align-items:center;gap:8px;box-shadow:0 4px 14px var(--shadow-soft);transition:all .2s ease}.connecte-btn-sync{color:var(--primary);border-color:rgba(0,200,83,.3)}.connecte-btn-sync:hover{background:rgba(0,200,83,.12);transform:translateY(-1px)}.connecte-btn-compte:hover{background:rgba(255,255,255,.06);transform:translateY(-1px)}
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
        .badge-dl-btn {display:block;width:100%;margin-top:4px;padding:4px 6px;border:1px solid #e2e8f0;border-radius:4px;background:#f8fafc;color:var(--nv-primary);font-size:.6rem;cursor:pointer;text-align:center;transition:all .15s;}
        .badge-dl-btn:hover {background:var(--nv-primary);color:#fff;border-color:var(--nv-primary);}

        @media(max-width:1180px){.role-hero,.role-grid,.workspace,.espace-cards{margin-left:16px;margin-right:16px}.role-grid{grid-template-columns:repeat(2,minmax(0,1fr))}.workspace{grid-template-columns:1fr}.role-hero{margin-top:98px}.module-list{grid-template-columns:1fr}.espace-cards{grid-template-columns:1fr}}
        @media(max-width:700px){.role-grid{grid-template-columns:1fr}.role-hero{margin-top:122px;padding:18px}.role-hero h1{font-size:1.3rem}.role-card{padding:16px}}
    </style>
</head>
<body>
@include('modules.professeur.bulletin.partials.shell', ['activeModule' => 'role_dashboard'])
<header style="display:flex;align-items:center;gap:12px;flex-wrap:wrap">
    <div class="header-left">
        <button class="burger-menu" onclick="toggleSidebar()"><i class="fa fa-bars"></i></button>
        <button id="fullscreen-btn" onclick="toggleFullscreen()"><i class="fa fa-expand"></i></button>
    </div>
    <h1 style="flex:1">Mon espace</h1>
</header>

<section class="role-hero">
    <h1>Bonjour {{ $user['nom'] ?? 'Utilisateur' }}</h1>
    <p>Voici votre espace {{ $user['role'] ?? 'utilisateur' }}. Les raccourcis ci-dessous suivent exactement vos permissions.</p>

    @if(config('app.connected_mode'))
    <div class="connecte-header-actions" style="margin-top:14px">
        <button onclick="runSync()" id="connecte-sync-btn" title="Synchroniser" class="connecte-btn connecte-btn-sync">&#x21BB; Synchroniser</button>
        <button onclick="showAccountMenu()" title="Compte" class="connecte-btn connecte-btn-compte">&#x2630; Compte</button>
    </div>
    @endif

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
                            <button class="badge-dl-btn" onclick="downloadBadge(this)" data-card-id="{{ $card['id'] }}" title="Telecharger le badge"><i class="fa fa-download"></i></button>
                        </div>
                    @endif
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
    </aside>
</main>
<script>
document.addEventListener('DOMContentLoaded',()=>{
    document.querySelectorAll('.id-qr-box').forEach(function(q){
        const u=q.getAttribute('data-qr');
        if(u&&window.QRCode)new QRCode(q,{text:u,width:96,height:96,colorDark:'#0f2942',colorLight:'#ffffff',correctLevel:QRCode.CorrectLevel.H})
    })
})
function downloadBadge(btn){
    const card=btn.closest('.id-card');
    if(!card)return;
    btn.disabled=true;btn.innerHTML='<i class="fa fa-spinner fa-spin"></i>';
    const name=card.querySelector('.id-name')?.textContent?.trim()||'badge';
    html2canvas(card,{scale:3,backgroundColor:'#ffffff',useCORS:true,logging:false,width:card.scrollWidth,height:card.scrollHeight}).then(function(canvas){
        const link=document.createElement('a');
        link.download=name.replace(/\s+/g,'_')+'_badge.png';
        link.href=canvas.toDataURL('image/png');
        link.click();
        btn.disabled=false;btn.innerHTML='<i class="fa fa-download"></i>';
    }).catch(function(){
        btn.disabled=false;btn.innerHTML='<i class="fa fa-download"></i>';
        Swal.fire({icon:'error',title:'Erreur',text:'Impossible de telecharger le badge',confirmButtonColor:'#00c853'});
    });
}
@if(config('app.connected_mode'))
function showToast(msg,ok){
    var t=document.createElement('div');
    t.textContent=msg;
    t.style.cssText='position:fixed;top:70px;right:16px;z-index:100000;background:'+(ok?'#16a34a':'#dc2626')+';color:#fff;padding:10px 18px;border-radius:8px;font-size:13px;font-weight:600;box-shadow:0 4px 16px rgba(0,0,0,0.4);transition:opacity .3s';
    document.body.appendChild(t);
    setTimeout(function(){t.style.opacity='0';setTimeout(function(){t.remove()},400)},3000);
}
function runSync(){
    var btn=document.getElementById('connecte-sync-btn');if(!btn)return;
    btn.disabled=true;btn.style.opacity='0.5';
    fetch('/connected/sync/run',{headers:{'Accept':'application/json'}})
        .then(function(r){return r.json()})
        .then(function(d){
            var ok=d&&d.success;
            btn.disabled=false;btn.style.opacity='';
            showToast(ok?'\u2713 Synchronis\u00e9':'\u2717 Erreur: '+(d.message||'inconnue'),ok);
            if(ok)setTimeout(function(){location.reload()},1200);
        })['catch'](function(){
            btn.disabled=false;btn.style.opacity='';
            showToast('\u2717 Erreur reseau',false);
        });
}
function showAccountMenu(){
    var box=document.createElement('div');
    box.style.cssText='position:fixed;inset:0;z-index:100001;background:rgba(0,0,0,0.6);display:flex;align-items:center;justify-content:center';
    var inner=document.createElement('div');
    inner.style.cssText='background:#1e293b;border:1px solid #334155;border-radius:16px;padding:28px 32px;max-width:420px;width:90%;color:#f1f5f9;font-family:sans-serif';
    inner.innerHTML='<h3 style="margin:0 0 6px;font-size:18px">Gestion du compte</h3><p style="margin:0 0 20px;color:#94a3b8;font-size:14px">Que souhaitez-vous faire ?</p>';
    var b1=document.createElement('button');
    b1.textContent='\u21A9 Revenir au parainage';
    b1.style.cssText='display:block;width:100%;margin-bottom:10px;background:#dc2626;color:#fff;border:none;border-radius:12px;padding:12px;font-size:14px;font-weight:600;cursor:pointer';
    b1.onclick=function(){box.remove();try{window.connectedDesktop.disconnect()}catch(e){fetch('/connected/disconnect',{method:'POST',headers:{'Content-Type':'application/json','Accept':'application/json'}}).then(function(){location.reload()})['catch'](function(){location.reload()})}};
    var b2=document.createElement('button');
    b2.textContent='\u279C Changer d\'utilisateur';
    b2.style.cssText='display:block;width:100%;margin-bottom:10px;background:#2563eb;color:#fff;border:none;border-radius:12px;padding:12px;font-size:14px;font-weight:600;cursor:pointer';
    b2.onclick=function(){box.remove();switchUserForm()};
    var b3=document.createElement('button');
    b3.textContent='Annuler';
    b3.style.cssText='display:block;width:100%;background:#334155;color:#94a3b8;border:none;border-radius:12px;padding:12px;font-size:14px;cursor:pointer';
    b3.onclick=function(){box.remove()};
    inner.appendChild(b1);inner.appendChild(b2);inner.appendChild(b3);box.appendChild(inner);document.body.appendChild(box);
}
function switchUserForm(){
    var box=document.createElement('div');
    box.style.cssText='position:fixed;inset:0;z-index:100001;background:rgba(0,0,0,0.6);display:flex;align-items:center;justify-content:center';
    var inner=document.createElement('div');
    inner.style.cssText='background:#1e293b;border:1px solid #334155;border-radius:16px;padding:28px 32px;max-width:420px;width:90%;color:#f1f5f9;font-family:sans-serif';
    inner.innerHTML='<h3 style="margin:0 0 6px;font-size:18px">Changer d\'utilisateur</h3><p style="margin:0 0 16px;color:#94a3b8;font-size:14px">Connectez-vous avec un autre compte sur le m\u00EAme serveur.</p>';
    var inp=document.createElement('input');inp.type='email';inp.placeholder='Email';inp.value='';
    inp.style.cssText='display:block;width:100%;margin-bottom:10px;background:#0f172a;color:#f1f5f9;border:1px solid #334155;border-radius:10px;padding:12px;font-size:14px;outline:none';
    var sel=document.createElement('select');
    sel.style.cssText='display:block;width:100%;margin-bottom:10px;background:#0f172a;color:#f1f5f9;border:1px solid #334155;border-radius:10px;padding:12px;font-size:14px;outline:none;cursor:pointer';
    var roles=['admin','enseignant','staff','parent'];
    for(var i=0;i<roles.length;i++){var opt=document.createElement('option');opt.value=roles[i];opt.textContent=roles[i];sel.appendChild(opt)}
    var inp2=document.createElement('input');inp2.type='password';inp2.placeholder='Mot de passe';inp2.value='';
    inp2.style.cssText='display:block;width:100%;margin-bottom:16px;background:#0f172a;color:#f1f5f9;border:1px solid #334155;border-radius:10px;padding:12px;font-size:14px;outline:none';
    var st=document.createElement('div');st.style.cssText='margin-bottom:10px;font-size:13px;color:#94a3b8';
    var btn=document.createElement('button');
    btn.textContent='Se connecter';
    btn.style.cssText='display:block;width:100%;margin-bottom:8px;background:#2563eb;color:#fff;border:none;border-radius:12px;padding:12px;font-size:14px;font-weight:600;cursor:pointer';
    btn.onclick=function(){
        btn.disabled=true;btn.textContent='...';st.textContent='Connexion...';st.style.color='#94a3b8';
        fetch('/connected/switch-user',{method:'POST',headers:{'Content-Type':'application/json','Accept':'application/json'},body:JSON.stringify({email:inp.value,password:inp2.value,role:sel.value})})
            .then(function(r){return r.json()})
            .then(function(d){
                if(d&&d.success){st.textContent='\u2713 Succ\u00E8s ! Rechargement...';st.style.color='#4ade80';setTimeout(function(){location.reload()},800)}
                else{btn.disabled=false;btn.textContent='Se connecter';st.textContent='\u2717 '+(d.message||'Echec connexion');st.style.color='#f87171'}
            })['catch'](function(){btn.disabled=false;btn.textContent='Se connecter';st.textContent='\u2717 Erreur reseau';st.style.color='#f87171'});
    };
    var cancel=document.createElement('button');
    cancel.textContent='Annuler';
    cancel.style.cssText='display:block;width:100%;background:#334155;color:#94a3b8;border:none;border-radius:12px;padding:12px;font-size:14px;cursor:pointer';
    cancel.onclick=function(){box.remove()};
    inner.appendChild(inp);inner.appendChild(sel);inner.appendChild(inp2);inner.appendChild(st);inner.appendChild(btn);inner.appendChild(cancel);
    box.appendChild(inner);document.body.appendChild(box);
}
@endif
</script>
</body>
</html>
