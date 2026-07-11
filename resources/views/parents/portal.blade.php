<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Espace parent - {{ $ecole->nom ?? 'Novaskol' }}</title>
    <link rel="stylesheet" href="{{ asset('legacy/assets/fontawesome/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('legacy/fa/css/font-awesome.min.css') }}">
    @include('modules.professeur.bulletin.partials.styles')
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="{{ asset('legacy/vendor/qrcode.min.js') }}"></script>
    <style>
        :root { --nv-primary: #0f2942; --nv-gold: #c9a84c; }
        body { font-family: 'Inter', sans-serif; background: var(--bg); color: var(--text); }
        .parent-wrap { margin: 88px 20px 20px 256px; }
        .parent-hero { background: linear-gradient(135deg, var(--card), var(--surface)); border: 1px solid var(--border); border-radius: 12px; padding: 18px 22px; box-shadow: 0 8px 24px var(--shadow-soft); }
        .parent-hero h1 { margin: 0; color: var(--primary); font-size: 1.3rem; }
        .parent-hero p { margin: 4px 0 0; color: var(--text-sec); font-size: .85rem; }
        .child-tabs { display: flex; flex-wrap: wrap; gap: 8px; margin-top: 12px; }
        .child-tabs a { padding: 8px 14px; border-radius: 8px; border: 1px solid var(--border); background: var(--surface); color: var(--text); text-decoration: none; font-weight: 700; font-size: .85rem; transition: all .15s; }
        .child-tabs a.active, .child-tabs a:hover { border-color: var(--primary); color: var(--primary); background: var(--primary-glow); }
        .portal-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-top: 18px; }
        .portal-full { grid-column: 1 / -1; }
        .portal-card { background: var(--card); border: 1px solid var(--border); border-radius: 10px; padding: 18px; box-shadow: 0 4px 16px var(--shadow-soft); }
        .portal-card h2 { margin: 0 0 12px; color: var(--primary); font-size: .95rem; display: flex; align-items: center; gap: 8px; }
        .portal-card h2 i { width: 26px; height: 26px; border-radius: 6px; display: grid; place-items: center; background: var(--primary-glow); color: var(--primary); font-size: .8rem; }

        /* ID Card */
        .id-card-preview { display: flex; background: #fff; color: #1e293b; border-radius: 12px; overflow: hidden; border: 1px solid #e2e8f0; box-shadow: 0 2px 12px rgba(15,41,66,.06); font-family: 'Inter', sans-serif; max-width: 520px; }
        .id-card-preview .photo-wrap { flex: 0 0 108px; display: flex; align-items: center; justify-content: center; padding: 12px 0 12px 12px; }
        .id-card-preview .photo { width: 94px; height: 120px; object-fit: cover; border-radius: 8px; border: 2px solid #e2e8f0; background: #f1f5f9; }
        .id-card-preview .body { flex: 1; padding: 12px 10px; display: flex; flex-direction: column; justify-content: center; min-width: 0; }
        .id-card-preview .badge { display: inline-block; background: var(--nv-primary); color: #fff; border-radius: 4px; padding: 3px 12px; font-size: .65rem; font-weight: 700; text-transform: uppercase; letter-spacing: .5px; margin-bottom: 5px; width: fit-content; }
        .id-card-preview .name { color: var(--nv-primary) !important; margin: 0 0 2px; font-size: .9rem; font-weight: 700; }
        .id-card-preview .meta { font-size: .7rem; color: #64748b; line-height: 1.55; margin: 0; }
        .id-card-preview .meta strong { color: var(--nv-primary); font-weight: 600; }
        .id-card-preview .school { display: block; margin-top: 4px; font-size: .65rem; color: var(--nv-gold); font-weight: 700; border-top: 1px solid #e2e8f0; padding-top: 4px; letter-spacing: .3px; text-transform: uppercase; }
        .id-card-preview .qr-wrap { flex: 0 0 138px; display: flex; align-items: center; justify-content: center; padding: 8px; background: #f8fafc; border-left: 1px solid #e2e8f0; }
        .id-card-preview .qr-box { width: 110px; height: 110px; display: grid; place-items: center; }
        .id-card-preview .qr-box canvas, .id-card-preview .qr-box img { width: 100px !important; height: 100px !important; }
        .id-card-preview .expiry { font-size: .5rem; color: #94a3b8; text-align: center; margin-top: 2px; }

        .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; }
        .info-grid label { font-size: .7rem; color: var(--text-sec); display: block; }
        .info-grid span { font-size: .82rem; color: var(--text); font-weight: 600; display: block; }
        .stat-row { display: flex; gap: 10px; flex-wrap: wrap; }
        .stat-pill { display: flex; align-items: center; gap: 5px; padding: 7px 12px; border-radius: 8px; border: 1px solid var(--border); font-size: .8rem; font-weight: 600; }
        .stat-pill i { font-size: .85rem; }
        .stat-pill.green { border-color: #05966933; background: #05966911; color: #059669; }
        .stat-pill.red { border-color: #dc262633; background: #dc262611; color: #dc2626; }
        .stat-pill.orange { border-color: #d9770633; background: #d9770611; color: #d97706; }
        .data-table { width: 100%; border-collapse: collapse; font-size: .8rem; }
        .data-table th { text-align: left; padding: 7px 5px; border-bottom: 2px solid var(--border); color: var(--text-sec); font-weight: 600; font-size: .7rem; text-transform: uppercase; letter-spacing: .3px; }
        .data-table td { padding: 6px 5px; border-bottom: 1px solid var(--border); color: var(--text); }
        .data-table .note { font-weight: 700; }
        .side-list { display: grid; gap: 8px; }
        .side-item { padding: 10px; border: 1px solid var(--border); border-radius: 8px; background: var(--surface); }
        .side-item strong { display: block; color: var(--text); font-size: .82rem; }
        .side-item small, .side-item .msg { display: block; color: var(--text-sec); font-size: .75rem; margin-top: 3px; }
        .empty-state { padding: 16px; text-align: center; color: var(--text-sec); font-size: .82rem; }

        @media(max-width:1180px) { .parent-wrap { margin-left: 16px; margin-right: 16px; } .portal-grid { grid-template-columns: 1fr; } .id-card-preview { flex-wrap: wrap; } .id-card-preview .qr-wrap { flex: 1; } }
        @media(max-width:700px) { .parent-wrap { margin-top: 100px; } .info-grid { grid-template-columns: 1fr; } .child-tabs a { width: 100%; text-align: center; } }
    </style>
</head>
<body>
@include('modules.professeur.bulletin.partials.shell', ['activeModule' => 'parent_portal'])
<header>
    <div class="header-left">
        <button class="burger-menu" onclick="toggleSidebar()"><i class="fa fa-bars"></i></button>
        <button id="fullscreen-btn" onclick="toggleFullscreen()"><i id="fullscreen-icon" class="fa fa-expand"></i></button>
    </div>
    <h1>Espace parent</h1>
</header>

<div class="parent-wrap">
    <section class="parent-hero">
        <h1>Suivi scolaire des enfants</h1>
        <p>{{ $children->count() }} enfant(s) rattache(s) a votre compte.</p>
        @if($children->isNotEmpty())
            <div class="child-tabs">
                @foreach($children as $student)
                    <a href="{{ route('parent.portal', ['eleve' => $student->id]) }}" @class(['active' => $child && $child->id === $student->id])>
                        <i class="fa fa-user-graduate"></i> {{ $student->prenom }} {{ $student->nom }}
                    </a>
                @endforeach
            </div>
        @endif
    </section>

    @if(! $child)
        <div class="empty-state" style="margin-top:18px">Aucun eleve n'est encore rattache a ce compte parent.</div>
    @else
        <div class="portal-grid">
            <div class="portal-card portal-full">
                <h2><i class="fa fa-id-card"></i> Carte d'identite de {{ $child->prenom }}</h2>
                @php
                    $photo = $child->photo ?: 'Uploads/default.jpg';
                    $qrToken = $child->qr_token ?? '';
                @endphp
                <div class="id-card-preview">
                    <div class="photo-wrap">
                        <img class="photo" src="{{ asset('legacy/'.ltrim($photo,'/')) }}" alt="" loading="lazy">
                    </div>
                    <div class="body">
                        <span class="badge">Eleve</span>
                        <h3 class="name">{{ $child->nom }} {{ $child->prenom }}</h3>
                        <p class="meta">
                            <strong>ID :</strong> {{ $child->matricule ?? $child->id }}<br>
                            @if($child->classe_nom)<strong>Classe :</strong> {{ $child->classe_nom }}<br>@endif
                            <strong>Annee :</strong> {{ $child->annee_scolaire }}
                        </p>
                        <span class="school">{{ $ecole->nom ?? 'NOVASKOL' }}</span>
                    </div>
                    <div class="qr-wrap">
                        @if($qrToken)
                            <div>
                                <div class="qr-box" id="qrChild{{ $child->id }}" data-qr="novaskol:qr:v1:{{ $qrToken }}"></div>
                                <div class="expiry">Exp: {{ now()->addYear()->format('d/m/Y') }}</div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="portal-card">
                <h2><i class="fa fa-user"></i> Informations</h2>
                <div class="info-grid">
                    <div><label>Nom</label><span>{{ $child->nom }}</span></div>
                    <div><label>Prenom</label><span>{{ $child->prenom }}</span></div>
                    <div><label>Matricule</label><span>{{ $child->matricule ?? 'N/A' }}</span></div>
                    <div><label>Classe</label><span>{{ $child->classe_nom ?? 'N/A' }}</span></div>
                    <div><label>Lien familial</label><span>{{ ucfirst($child->lien ?? 'Parent') }}</span></div>
                    <div><label>Annee scolaire</label><span>{{ $child->annee_scolaire }}</span></div>
                    @if($child->telephone)<div><label>Telephone</label><span>{{ $child->telephone }}</span></div>@endif
                    <div><label>Date naissance</label><span>{{ $child->date_naissance ? \Carbon\Carbon::parse($child->date_naissance)->format('d/m/Y') : 'N/A' }}</span></div>
                </div>
            </div>

            <div class="portal-card">
                <h2><i class="fa fa-calendar-check-o"></i> Calendrier de presence</h2>
                @include('partials.presence-calendar', [
                    'attendance' => $attendance,
                    'month' => $calMonth,
                    'year' => $calYear,
                    'baseUrl' => route('parent.portal', ['eleve' => $child->id]),
                    'label' => $child->prenom,
                ])
            </div>

            <div class="portal-card portal-full">
                <h2><i class="fa fa-book"></i> Dernieres notes</h2>
                @if($notes->isNotEmpty())
                    @php($grouped = $notes->groupBy('periode'))
                    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(260px,1fr));gap:12px">
                        @foreach($grouped as $periode => $periodeNotes)
                            <div>
                                <h3 style="font-size:.78rem;color:var(--primary);margin:0 0 6px">Periode {{ $periode }}</h3>
                                <table class="data-table">
                                    <thead><tr><th>Matiere</th><th style="text-align:right">Note</th><th style="text-align:right">Coef</th></tr></thead>
                                    <tbody>
                                        @foreach($periodeNotes as $n)
                                            <tr>
                                                <td>{{ $n->matiere ?? 'N/A' }}</td>
                                                <td class="note" style="text-align:right">{{ $n->note ?? '-' }}</td>
                                                <td style="text-align:right">{{ $n->coefficient ?? '1' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="empty-state">Aucune note disponible.</div>
                @endif
            </div>

            <div class="portal-card">
                <h2><i class="fa fa-money"></i> Paiements</h2>
                @if($paymentsDue->isEmpty() && $paymentsDone->isEmpty())
                    <div class="empty-state">Aucun paiement.</div>
                @else
                    <table class="data-table">
                        <thead><tr><th>Type</th><th style="text-align:right">Montant</th><th>Statut</th></tr></thead>
                        <tbody>
                            @foreach($paymentsDue as $p)
                                <tr><td>{{ $p->nom ?? 'Paiement' }}</td><td style="text-align:right">{{ number_format((float)($p->montant ?? 0),0,',',' ') }}</td><td style="color:#d97706">En attente</td></tr>
                            @endforeach
                            @foreach($paymentsDone as $p)
                                <tr><td>{{ $p->categorie ?? 'Paiement' }}</td><td style="text-align:right">{{ number_format((float)$p->montant,0,',',' ') }}</td><td style="color:#059669">Paye</td></tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>

            <div class="portal-card">
                <h2><i class="fa fa-calendar"></i> Evenements</h2>
                <div class="side-list">
                    @forelse($events as $event)
                        <div class="side-item">
                            <strong>{{ $event->titre }}</strong>
                            <small>{{ \Carbon\Carbon::parse($event->date_debut)->format('d/m/Y H:i') }} - {{ ucfirst($event->type) }}</small>
                        </div>
                    @empty
                        <div class="empty-state">Aucun evenement a venir.</div>
                    @endforelse
                </div>
            </div>
        </div>
    @endif
</div>
<script>
document.addEventListener('DOMContentLoaded',()=>{
    document.querySelectorAll('.qr-box').forEach(function(q){
        const u=q.getAttribute('data-qr');
        if(u&&window.QRCode)new QRCode(q,{text:u,width:100,height:100,colorDark:'#0f2942',colorLight:'#ffffff',correctLevel:QRCode.CorrectLevel.H})
    })
})
function toggleSub(e){const s=e.nextElementSibling,a=e.querySelector('.arrow');s.style.display=s.style.display==='block'?'none':'block';a.classList.toggle('fa-chevron-down');a.classList.toggle('fa-chevron-up')}
function toggleSidebar(){document.getElementById('sidebar').classList.toggle('active');document.querySelector('main').classList.toggle('full-width');document.querySelector('header').classList.toggle('full-width')}
function toggleFullscreen(){const i=document.getElementById('fullscreen-icon');if(!document.fullscreenElement){document.documentElement.requestFullscreen();i.classList.replace('fa-expand','fa-compress')}else{document.exitFullscreen();i.classList.replace('fa-compress','fa-expand')}}
</script>
</body>
</html>