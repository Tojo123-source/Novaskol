<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mon espace eleve - {{ $ecole->nom ?? 'Novaskol' }}</title>
    <link rel="stylesheet" href="{{ asset('legacy/assets/fontawesome/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('legacy/fa/css/font-awesome.min.css') }}">
    @include('modules.professeur.bulletin.partials.styles')
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="{{ asset('legacy/vendor/qrcode.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>
    <style>
        :root { --nv-primary: #0f2942; --nv-gold: #c9a84c; }
        body { font-family: 'Inter', sans-serif; background: var(--bg); color: var(--text); }
        .student-wrap { margin: 88px 20px 20px 256px; }
        .student-hero { background: linear-gradient(135deg, var(--card), var(--surface)); border: 1px solid var(--border); border-radius: 12px; padding: 18px 22px; box-shadow: 0 8px 24px var(--shadow-soft); }
        .student-hero h1 { margin: 0; color: var(--primary); font-size: 1.3rem; }
        .student-hero p { margin: 4px 0 0; color: var(--text-sec); font-size: .85rem; }
        .quick-links { display: flex; flex-wrap: wrap; gap: 8px; margin-top: 14px; }
        .quick-links a { display: inline-flex; align-items: center; gap: 8px; padding: 10px 16px; border-radius: 8px; border: 1px solid var(--border); background: var(--surface); color: var(--text); text-decoration: none; font-weight: 600; font-size: .85rem; transition: all .15s; }
        .quick-links a:hover, .quick-links a.active { border-color: var(--primary); color: var(--primary); background: var(--primary-glow); }
        .portal-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-top: 18px; }
        .portal-full { grid-column: 1 / -1; }
        .portal-card { background: var(--card); border: 1px solid var(--border); border-radius: 10px; padding: 18px; box-shadow: 0 4px 16px var(--shadow-soft); }
        .portal-card h2 { margin: 0 0 12px; color: var(--primary); font-size: .95rem; display: flex; align-items: center; gap: 8px; }
        .portal-card h2 i { width: 26px; height: 26px; border-radius: 6px; display: grid; place-items: center; background: var(--primary-glow); color: var(--primary); font-size: .8rem; }

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
        .stat-pill.orange { border-color: #d9770633; background: #d9770611; color: #d97706; }
        .stat-pill.blue { border-color: #2563eb33; background: #2563eb11; color: #2563eb; }

        .data-table { width: 100%; border-collapse: collapse; font-size: .8rem; }
        .data-table th { text-align: left; padding: 7px 5px; border-bottom: 2px solid var(--border); color: var(--text-sec); font-weight: 600; font-size: .7rem; text-transform: uppercase; letter-spacing: .3px; }
        .data-table td { padding: 6px 5px; border-bottom: 1px solid var(--border); color: var(--text); }
        .data-table .note { font-weight: 700; }

        .course-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap: 10px; }
        .course-card { display: block; background: var(--surface); border: 1px solid var(--border); border-radius: 8px; padding: 14px; text-decoration: none; color: var(--text); transition: border-color .15s; }
        .course-card:hover { border-color: var(--primary); }
        .course-card h3 { margin: 0 0 4px; font-size: .88rem; }
        .course-card .meta { color: var(--text-sec); font-size: .75rem; display: flex; gap: 8px; justify-content: space-between; }
        .progress-bar { height: 4px; background: var(--surface); border-radius: 2px; overflow: hidden; margin-top: 8px; }
        .progress-bar .fill { height: 100%; background: var(--primary); border-radius: 2px; }

        .side-list { display: grid; gap: 8px; }
        .side-item { padding: 10px; border: 1px solid var(--border); border-radius: 8px; background: var(--surface); }
        .side-item strong { display: block; color: var(--text); font-size: .82rem; }
        .side-item small, .side-item .msg { display: block; color: var(--text-sec); font-size: .75rem; margin-top: 3px; }
        .empty-state { padding: 16px; text-align: center; color: var(--text-sec); font-size: .82rem; }

        @media(max-width:1180px) { .student-wrap { margin-left: 16px; margin-right: 16px; } .portal-grid { grid-template-columns: 1fr; } .id-card-preview { flex-wrap: wrap; } .id-card-preview .qr-wrap { flex: 1; } }
        .id-dl-btn{display:flex;align-items:center;justify-content:center;gap:4px;width:100%;margin-top:6px;padding:5px;font-size:.65rem;font-weight:600;border:1px solid #e2e8f0;border-radius:6px;background:#fff;color:var(--nv-primary);cursor:pointer;transition:all .15s}
        .id-dl-btn:hover{background:var(--nv-primary);color:#fff;border-color:var(--nv-primary)}
        @media(max-width:700px) { .student-wrap { margin-top: 100px; } .info-grid { grid-template-columns: 1fr; } .quick-links a { width: 100%; justify-content: center; } }
    </style>
</head>
<body>
@include('modules.professeur.bulletin.partials.shell', ['activeModule' => 'eleve_portal'])
<header>
    <div class="header-left">
        <button class="burger-menu" onclick="toggleSidebar()"><i class="fa fa-bars"></i></button>
        <button id="fullscreen-btn" onclick="toggleFullscreen()"><i class="fa fa-expand"></i></button>
    </div>
    <div class="header-center">Mon espace eleve</div>
</header>

<div class="student-wrap">
    <section class="student-hero">
        <h1>Bienvenue, {{ $eleve->prenom ?? $eleve->nom }}</h1>
        <p>{{ $eleve->classe_nom ?? '' }} - {{ $eleve->matricule ?? $eleve->id }}</p>
        <div class="quick-links">
            <a href="{{ route('eleve.courses') }}"><i class="fa fa-book"></i> Bibliotheque</a>
            <a href="{{ route('eleve.portal.chat') }}"><i class="fa fa-comments"></i> Chat prive</a>
            <a href="{{ route('eleve.historique') }}"><i class="fa fa-history"></i> Historique</a>
            <a href="{{ route('eleve.rapport') }}"><i class="fa fa-chart-line"></i> Mon rapport</a>
        </div>
    </section>

    <div class="portal-grid">
        <div class="portal-card portal-full">
            <h2><i class="fa fa-id-card"></i> Ma carte d'identite</h2>
            @php
                $photo = $eleve->photo ?: 'Uploads/default.jpg';
                $qrToken = $eleve->qr_token ?? '';
            @endphp
            <div class="id-card-preview">
                <div class="photo-wrap">
                    <img class="photo" src="{{ asset('legacy/'.ltrim($photo,'/')) }}" alt="" loading="lazy">
                </div>
                <div class="body">
                    <span class="badge">Eleve</span>
                    <h3 class="name">{{ $eleve->nom }} {{ $eleve->prenom }}</h3>
                    <p class="meta">
                        <strong>ID :</strong> {{ $eleve->matricule ?? $eleve->id }}<br>
                        @if($eleve->classe_nom)<strong>Classe :</strong> {{ $eleve->classe_nom }}<br>@endif
                        <strong>Annee :</strong> {{ $eleve->annee_scolaire }}
                    </p>
                    <span class="school">{{ $ecole->nom ?? 'NOVASKOL' }}</span>
                </div>
                <div class="qr-wrap">
                    @if($qrToken)
                        <div>
                            <div class="qr-box" id="qrStudent{{ $eleve->id }}" data-qr="novaskol:qr:v1:{{ $qrToken }}"></div>
                            <div class="expiry">Exp: {{ now()->addYear()->format('d/m/Y') }}</div>
                        </div>
                    @else
                        <div>
                            <div class="qr-box" style="background:#f1f5f9;color:#94a3b8;font-size:.65rem;text-align:center;display:grid;place-items:center;width:110px;height:110px;border-radius:8px;line-height:1.4;">
                                QR<br>non<br>disponible
                            </div>
                        </div>
                    @endif
                    <button onclick="downloadStudentCard(this)" class="id-dl-btn" title="Telecharger la carte"><i class="fa fa-download"></i></button>
                </div>
            </div>
        </div>

        <div class="portal-card">
            <h2><i class="fa fa-user"></i> Mes informations</h2>
            <div class="info-grid">
                <div><label>Nom</label><span>{{ $eleve->nom }}</span></div>
                <div><label>Prenom</label><span>{{ $eleve->prenom }}</span></div>
                <div><label>Matricule</label><span>{{ $eleve->matricule ?? 'N/A' }}</span></div>
                <div><label>Classe</label><span>{{ $eleve->classe_nom ?? 'N/A' }}</span></div>
                <div><label>Annee scolaire</label><span>{{ $eleve->annee_scolaire }}</span></div>
                @if($eleve->telephone)<div><label>Telephone</label><span>{{ $eleve->telephone }}</span></div>@endif
                <div><label>Date naissance</label><span>{{ $eleve->date_naissance ? \Carbon\Carbon::parse($eleve->date_naissance)->format('d/m/Y') : 'N/A' }}</span></div>
            </div>
        </div>

        <div class="portal-card">
            <h2><i class="fa fa-calendar-check-o"></i> Ma presence</h2>
            @include('partials.presence-calendar', [
                'attendance' => $attendance,
                'month' => $calMonth,
                'year' => $calYear,
                'baseUrl' => route('eleve.portal'),
                'label' => 'Ma presence',
            ])
        </div>

        <div class="portal-card portal-full">
            <h2><i class="fa fa-book"></i> Mes notes</h2>
            @if($notes->isNotEmpty())
                @php $grouped = $notes->groupBy('periode'); @endphp
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

        <div class="portal-card portal-full">
            <h2><i class="fa fa-book-open"></i> Mes cours en ligne</h2>
            @if($courses->isNotEmpty())
                <div class="course-grid">
                    @foreach($courses as $c)
                    @php
                        $prog = $progressions->get($c->id);
                        $total = $prog ? $prog->total : 0;
                        $done = $prog ? $prog->done : 0;
                        $pct = $total > 0 ? round(($done / $total) * 100) : 0;
                        $chIds = DB::table('course_chapitres')->where('course_id', $c->id)->pluck('id');
                        $exCount = DB::table('exercices')->whereIn('chapitre_id', $chIds)->count();
                        $ficCount = DB::table('course_fichiers')->whereIn('chapitre_id', $chIds)->count();
                    @endphp
                        <a href="{{ route('eleve.course.show', $c->id) }}" class="course-card">
                            <div class="meta">
                                <span>{{ DB::table('matieres')->where('id', $c->matiere_id)->value('nom') ?? 'General' }}</span>
                                @if ($pct > 0) <span>{{ $pct }}%</span> @endif
                            </div>
                            <h3>{{ $c->titre }}</h3>
                            <div class="meta" style="margin-top:4px">
                                <span><i class="fa fa-puzzle-piece"></i> {{ $exCount }} ex.</span>
                                <span><i class="fa fa-file"></i> {{ $ficCount }} fich.</span>
                            </div>
                            @if ($total > 0)
                                <div class="progress-bar"><div class="fill" style="width:{{ $pct }}%"></div></div>
                            @endif
                        </a>
                    @endforeach
                </div>
                <a href="{{ route('eleve.courses') }}" style="display:inline-flex;align-items:center;gap:6px;margin-top:10px;color:var(--primary);font-size:.85rem;font-weight:600;text-decoration:none">
                    Voir tous les cours <i class="fa fa-arrow-right"></i>
                </a>
            @else
                <div class="empty-state">Aucun cours disponible pour le moment.</div>
            @endif
        </div>

        <div class="portal-card">
            <h2><i class="fa fa-calendar"></i> Evenements</h2>
            <div class="side-list">
                @php $events = DB::table('evenements')->where('date_fin', '>=', now())->orderBy('date_debut')->limit(6)->get(); @endphp
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
</div>
<script>
document.addEventListener('DOMContentLoaded',()=>{
    document.querySelectorAll('.qr-box').forEach(function(q){
        const u=q.getAttribute('data-qr');
        if(u&&window.QRCode)new QRCode(q,{text:u,width:100,height:100,colorDark:'#0f2942',colorLight:'#ffffff',correctLevel:QRCode.CorrectLevel.H})
    })
})
function downloadStudentCard(btn){
    const card=btn.closest('.id-card-preview');
    if(!card)return;
    const name=card.querySelector('.name')?.textContent||'carte';
    html2canvas(card,{scale:2,useCORS:true,backgroundColor:'#ffffff'}).then(canvas=>{
        const a=document.createElement('a');
        a.href=canvas.toDataURL('image/png');
        a.download='carte-'+name.trim().replace(/\s+/g,'-')+'.png';
        a.click();
    })
}
function toggleSub(e){const s=e.nextElementSibling,a=e.querySelector('.arrow');s.style.display=s.style.display==='block'?'none':'block';a.classList.toggle('fa-chevron-down');a.classList.toggle('fa-chevron-up')}
function toggleSidebar(){document.getElementById('sidebar').classList.toggle('active');}
function toggleFullscreen(){const i=document.getElementById('fullscreen-icon');if(!document.fullscreenElement){document.documentElement.requestFullscreen();i.classList.replace('fa-expand','fa-compress')}else{document.exitFullscreen();i.classList.replace('fa-compress','fa-expand')}}
</script>
</body>
</html>
