<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Espace enseignant - {{ $ecole->nom ?? 'Novaskol' }}</title>
    <link rel="stylesheet" href="{{ asset('legacy/assets/fontawesome/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('legacy/fa/css/font-awesome.min.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="{{ asset('legacy/vendor/qrcode.min.js') }}"></script>
    @include('modules.professeur.bulletin.partials.styles')
    <style>
        :root { --nv-primary: #0f2942; --nv-gold: #c9a84c; --nv-white: #ffffff; --nv-text: #1e293b; --nv-muted: #64748b; }
        .teacher-hero{margin:96px 24px 18px 264px;padding:22px;border:1px solid var(--border);background:linear-gradient(135deg,var(--card),var(--surface));border-radius:8px;box-shadow:0 16px 36px var(--shadow-soft)}
        .teacher-hero h1{margin:0;color:var(--primary);font-size:1.55rem}.teacher-hero p{margin:8px 0 0;color:var(--text-sec)}
        .teacher-grid{margin:0 24px 24px 264px;display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:12px}.teacher-card,.panel{background:var(--card);border:1px solid var(--border);border-radius:8px;padding:16px;box-shadow:0 10px 26px var(--shadow-soft)}.teacher-card strong{display:block;font-size:1.35rem;color:var(--primary)}.teacher-card span{color:var(--text-sec);font-size:.9rem}
        .espace-cards{margin:0 24px 18px 264px;display:grid;grid-template-columns:repeat(auto-fill,minmax(380px,1fr));gap:14px}
        .id-card {
            position:relative;display:flex;background:var(--nv-white);color:var(--nv-text);
            border-radius:12px;overflow:hidden;border:1px solid #e2e8f0;
            box-shadow:0 2px 12px rgba(15,41,66,.06);font-family:'Inter',sans-serif;min-height:120px;
        }
        .id-card::before {
            content:'';position:absolute;top:0;left:0;width:4px;height:100%;background:var(--nv-gold);
        }
        .id-card.type-enseignant::before { background: #0ea5e9; }
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
            display:inline-block;background:#0ea5e9;color:var(--nv-white);
            border-radius:4px;padding:2px 10px;
            font-size:.62rem;font-weight:700;text-transform:uppercase;
            letter-spacing:.5px;margin-bottom:4px;width:fit-content;
        }
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
        .workspace{margin:0 24px 30px 264px;display:grid;grid-template-columns:minmax(0,1.35fr) minmax(320px,.85fr);gap:16px}.panel h2{margin:0 0 14px;color:var(--primary);font-size:1.05rem}.filter-row,.form-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:10px;align-items:end;margin-bottom:12px}
        input,select,textarea{width:100%;padding:11px;border:1px solid var(--border);border-radius:8px;background:var(--surface);color:var(--text)}textarea{min-height:82px;resize:vertical}.btn{border:0;border-radius:8px;padding:10px 13px;font-weight:800;cursor:pointer}.btn-primary{background:var(--primary);color:#062b1d}.btn-danger{background:#dc2626;color:white}.btn-line{display:flex;gap:8px;flex-wrap:wrap;align-items:center}
        .btn-home{display:inline-flex;align-items:center;gap:8px;text-decoration:none;border:1px solid var(--border);border-radius:8px;padding:10px 13px;color:var(--text);background:var(--surface);font-weight:800}.btn-home:hover{border-color:var(--primary);color:var(--primary)}
        .lesson-list,.task-list{display:grid;gap:10px}.lesson{border:1px solid var(--border);border-radius:8px;padding:13px;background:var(--surface)}.lesson-head{display:flex;justify-content:space-between;gap:10px;align-items:flex-start}.lesson h3{margin:0 0 5px;font-size:1rem}.meta{color:var(--text-sec);font-size:.88rem;display:flex;gap:10px;flex-wrap:wrap}.progress{height:8px;border-radius:999px;background:rgba(255,255,255,.08);overflow:hidden;margin:11px 0}.progress span{display:block;height:100%;background:var(--primary)}.badge{display:inline-flex;border-radius:999px;padding:4px 8px;font-size:.78rem;background:var(--primary-glow);color:var(--primary);font-weight:800}.task{display:grid;grid-template-columns:auto 1fr auto;gap:10px;align-items:center;border:1px solid var(--border);border-radius:8px;padding:10px;background:var(--surface)}.task.done{opacity:.65}.task.done strong{text-decoration:line-through}.empty{padding:16px;border:1px dashed var(--border);border-radius:8px;color:var(--text-sec);text-align:center}
        .class-list{display:grid;gap:8px;margin-bottom:14px}.class-pill{display:flex;justify-content:space-between;gap:10px;padding:10px;border:1px solid var(--border);border-radius:8px;background:var(--surface)}.class-pill small{color:var(--text-sec)}
        :root.light .teacher-hero,:root.light .teacher-card,:root.light .panel,:root.light .lesson,:root.light .task,:root.light .class-pill{background:#fff!important;color:#111827!important;border-color:var(--border)!important;box-shadow:0 8px 24px rgba(15,23,42,.08)!important}
        :root.light .teacher-hero{background:linear-gradient(135deg,#fff,#eef5f1)!important}
        :root.light input,:root.light select,:root.light textarea,:root.light .btn-home{background:#fff!important;color:#111827!important;border-color:var(--border)!important}
        :root.light .progress{background:#e5e7eb!important}
        :root.light .empty,:root.light .meta,:root.light .class-pill small,:root.light .teacher-card span,.teacher-hero p{color:var(--text-sec)!important}
        table th{background:var(--surface);color:var(--text);border-bottom:2px solid var(--border)}table td{color:var(--text)}.modal-overlay{display:none}:root.light table td,:root.light table th{background:transparent!important;color:#111827!important}:root.light .modal-overlay>div{background:#fff!important;border-color:var(--border)!important}:root.light table td{color:#111827!important}
        @media(max-width:1180px){.teacher-hero,.teacher-grid,.workspace,.espace-cards{margin-left:16px;margin-right:16px}.teacher-hero{margin-top:98px}.teacher-grid{grid-template-columns:repeat(2,minmax(0,1fr))}.workspace{grid-template-columns:1fr}.lesson-head{flex-direction:column}.task{grid-template-columns:auto 1fr}.espace-cards{grid-template-columns:1fr}}
        @media(max-width:700px){.teacher-hero{margin-top:122px;padding:18px}.teacher-hero h1{font-size:1.28rem}.teacher-grid{grid-template-columns:1fr}.btn-home{padding:8px 10px;font-size:.88rem}}
    </style>
</head>
<body>
@include('modules.professeur.bulletin.partials.shell', ['activeModule' => $activeModule])
<header>
    <div class="header-left">
        <button class="burger-menu" onclick="toggleSidebar()"><i class="fa fa-bars"></i></button>
        <button id="fullscreen-btn" onclick="toggleFullscreen()"><i class="fa fa-expand"></i></button>
        <a class="btn-home" href="{{ route('role.dashboard') }}"><i class="fa fa-arrow-left"></i> Accueil</a>
    </div>
    <div class="header-center">Espace enseignant</div>
</header>

<section class="teacher-hero">
    <h1>{{ $teacher->nom }} {{ $teacher->prenom }}</h1>
    <p>Journal pedagogique, lecons, rubriques, checklist et planification personnelle.</p>
</section>

@if($cardData)
    <section class="espace-cards">
        <article class="id-card type-enseignant">
            <div class="id-photo-wrap">
                <img class="id-photo" src="{{ asset('legacy/'.ltrim($cardData['photo'], '/')) }}" alt="" loading="lazy">
            </div>
            <div class="id-body">
                <span class="id-badge">{{ $cardData['badge'] }}</span>
                <h3 class="id-name" title="{{ $cardData['nom'] }} {{ $cardData['prenom'] }}">{{ $cardData['nom'] }} {{ $cardData['prenom'] }}</h3>
                <p class="id-meta">
                    <strong>ID :</strong> {{ $cardData['matricule'] }}<br>
                    @if($cardData['dept_info'])<strong>{{ $cardData['dept_label'] }} :</strong> {{ $cardData['dept_info'] }}<br>@endif
                    <strong>Annee :</strong> {{ $cardData['annee_scolaire'] }}
                </p>
                <span class="id-school">{{ $cardData['ecole_nom'] }}</span>
            </div>
            <div class="id-qr-wrap">
                @if($cardData['qr_token'])
                    <div>
                        <div class="id-qr-box" id="qr-card-{{ $cardData['id'] }}" data-qr="novaskol:qr:v1:{{ $cardData['qr_token'] }}"></div>
                        <div class="id-expiry">Exp: {{ now()->addYear()->format('d/m/Y') }}</div>
                    </div>
                @endif
            </div>
        </article>
    </section>
@endif

<section class="teacher-grid">
    <article class="teacher-card"><strong>{{ $stats['lessons'] }}</strong><span>lecons suivies</span></article>
    <article class="teacher-card"><strong>{{ $stats['done'] }}</strong><span>lecons terminees</span></article>
    <article class="teacher-card"><strong>{{ $stats['tasks_open'] }}</strong><span>taches ouvertes</span></article>
    <article class="teacher-card"><strong>{{ $stats['progress'] }}%</strong><span>progression moyenne</span></article>
</section>

<main class="workspace">
    <section class="panel">
        <h2 style="display:flex;align-items:center;justify-content:space-between;cursor:pointer" onclick="toggleJournalForm()">
            <span>Journal des lecons</span>
            <i class="fa fa-chevron-down" id="journalFormArrow"></i>
        </h2>

        <form method="GET" class="filter-row">
            <div><label>Annee</label><input name="annee_scolaire" value="{{ $annee }}"></div>
            <div><label>Classe</label><select name="classe_id"><option value="0">Toutes</option>@foreach($classes as $classe)<option value="{{ $classe->id }}" @selected($selectedClasse===$classe->id)>{{ $classe->nom }}</option>@endforeach</select></div>
            <button class="btn btn-primary">Filtrer</button>
        </form>

        <div id="journalForm" style="display:none;margin-bottom:16px;padding:16px;border:1px solid var(--border);border-radius:8px;background:var(--surface)">
            <h3 style="margin:0 0 12px;color:var(--primary);font-size:.95rem"><i class="fa fa-plus-circle"></i> Ajouter une lecon</h3>
            <form method="POST" action="{{ route('teacher.lessons.store') }}">
                @csrf
                <input type="hidden" name="annee_scolaire" value="{{ $annee }}">
                <div class="form-grid">
                    <div><label>Titre de la lecon</label><input name="titre" required></div>
                    <div><label>Rubrique / chapitre</label><input name="rubrique"></div>
                    <div><label>Classe</label><select name="classe_id"><option value="">General</option>@foreach($classes as $classe)<option value="{{ $classe->id }}">{{ $classe->nom }}</option>@endforeach</select></div>
                    <div><label>Date prevue</label><input type="date" name="date_prevue"></div>
                    <div><label>Statut</label><select name="statut"><option value="a_preparer">A preparer</option><option value="planifie">Planifie</option><option value="en_cours">En cours</option><option value="termine">Termine</option></select></div>
                    <div><label>Progression %</label><input type="number" name="progression" min="0" max="100" value="0"></div>
                </div>
                <div class="form-grid">
                    <div><label>Objectifs</label><textarea name="objectifs"></textarea></div>
                    <div><label>Notes pedagogiques</label><textarea name="notes"></textarea></div>
                </div>
                <button class="btn btn-primary"><i class="fa fa-plus"></i> Ajouter au journal</button>
            </form>
        </div>

        <div style="overflow-x:auto;margin-top:14px">
            <table style="width:100%;border-collapse:collapse;font-size:.88rem">
                <thead>
                    <tr style="background:var(--surface);border-bottom:2px solid var(--border)">
                        <th style="padding:10px 8px;text-align:left;font-weight:700">Titre</th>
                        <th style="padding:10px 8px;text-align:left;font-weight:700">Classe</th>
                        <th style="padding:10px 8px;text-align:left;font-weight:700">Rubrique</th>
                        <th style="padding:10px 8px;text-align:left;font-weight:700">Date</th>
                        <th style="padding:10px 8px;text-align:center;font-weight:700">Progression</th>
                        <th style="padding:10px 8px;text-align:center;font-weight:700">Statut</th>
                        <th style="padding:10px 8px;text-align:center;font-weight:700">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($lessons as $lesson)
                    <tr style="border-bottom:1px solid var(--border)">
                        <td style="padding:10px 8px;font-weight:600">{{ $lesson->titre }}</td>
                        <td style="padding:10px 8px;color:var(--text-sec)">{{ $lesson->classe_nom ?? 'General' }}</td>
                        <td style="padding:10px 8px;color:var(--text-sec)">{{ $lesson->rubrique ?: '-' }}</td>
                        <td style="padding:10px 8px;color:var(--text-sec)">{{ $lesson->date_prevue ? \Carbon\Carbon::parse($lesson->date_prevue)->format('d/m/Y') : '-' }}</td>
                        <td style="padding:10px 8px;text-align:center">
                            <div style="display:flex;align-items:center;gap:6px;justify-content:center">
                                <div style="width:60px;height:6px;border-radius:999px;background:rgba(255,255,255,.1);overflow:hidden"><div style="width:{{ (int) $lesson->progression }}%;height:100%;background:var(--primary)"></div></div>
                                <span style="font-size:.78rem;color:var(--text-sec)">{{ (int) $lesson->progression }}%</span>
                            </div>
                        </td>
                        <td style="padding:10px 8px;text-align:center"><span class="badge">{{ str_replace('_',' ', $lesson->statut) }}</span></td>
                        <td style="padding:10px 8px;text-align:center">
                            <div style="display:flex;gap:4px;justify-content:center">
                                <button class="btn btn-sm" style="background:var(--surface);border:1px solid var(--border);padding:5px 10px;border-radius:6px;cursor:pointer;color:var(--text)" onclick="openEditLesson({{ $lesson->id }})" title="Modifier"><i class="fa fa-edit"></i></button>
                                <form method="POST" action="{{ route('teacher.lessons.delete', $lesson->id) }}" class="js-confirm-submit" data-confirm-title="Supprimer cette lecon ?" style="display:inline">@csrf @method('DELETE')<button class="btn btn-sm" style="background:transparent;border:1px solid #dc2626;padding:5px 10px;border-radius:6px;cursor:pointer;color:#dc2626" title="Supprimer"><i class="fa fa-trash"></i></button></form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" style="padding:20px;text-align:center;color:var(--text-sec)">Aucune lecon pour ce filtre.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @foreach($lessons as $lesson)
        <div id="editLessonModal{{ $lesson->id }}" class="modal-overlay" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.6);z-index:20000;align-items:center;justify-content:center;padding:20px;backdrop-filter:blur(4px)" onclick="if(event.target===this)closeEditLesson({{ $lesson->id }})">
            <div style="background:var(--card);border:1px solid var(--border);border-radius:16px;padding:24px;width:100%;max-width:560px;box-shadow:0 24px 60px rgba(0,0,0,.4)">
                <h3 style="margin:0 0 16px;color:var(--primary);font-size:1.05rem"><i class="fa fa-edit"></i> Modifier la lecon</h3>
                <form method="POST" action="{{ route('teacher.lessons.update', $lesson->id) }}">
                    @csrf @method('PUT')
                    <div class="form-grid">
                        <div><label>Titre</label><input name="titre" value="{{ $lesson->titre }}" required></div>
                        <div><label>Rubrique</label><input name="rubrique" value="{{ $lesson->rubrique }}"></div>
                        <div><label>Classe</label><select name="classe_id"><option value="">General</option>@foreach($classes as $classe)<option value="{{ $classe->id }}" @selected((int)$lesson->classe_id===(int)$classe->id)>{{ $classe->nom }}</option>@endforeach</select></div>
                        <div><label>Date prevue</label><input type="date" name="date_prevue" value="{{ $lesson->date_prevue }}"></div>
                        <div><label>Statut</label><select name="statut">@foreach(['a_preparer'=>'A preparer','planifie'=>'Planifie','en_cours'=>'En cours','termine'=>'Termine'] as $v=>$l)<option value="{{ $v }}" @selected($lesson->statut===$v)>{{ $l }}</option>@endforeach</select></div>
                        <div><label>Progression %</label><input type="number" name="progression" min="0" max="100" value="{{ $lesson->progression }}"></div>
                    </div>
                    <div class="form-grid">
                        <div><label>Objectifs</label><textarea name="objectifs">{{ $lesson->objectifs }}</textarea></div>
                        <div><label>Notes</label><textarea name="notes">{{ $lesson->notes }}</textarea></div>
                    </div>
                    <div style="display:flex;gap:10px;justify-content:flex-end;margin-top:16px">
                        <button type="button" class="btn" style="background:transparent;border:1px solid var(--border);padding:10px 14px;border-radius:8px;cursor:pointer;color:var(--text)" onclick="closeEditLesson({{ $lesson->id }})">Annuler</button>
                        <button class="btn btn-primary" style="padding:10px 14px"><i class="fa fa-save"></i> Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>
        @endforeach
    </section>

    <aside class="panel">
        <h2>Classes et interventions</h2>

        <div class="class-list">
            @forelse($classes as $classe)
                <div class="class-pill"><span>{{ $classe->nom }}</span><small>{{ ($classe->affectation_type ?? 'fixe') === 'flexible' ? 'intervention flexible' : 'classe fixe' }}{{ $classe->commentaire ? ' - '.$classe->commentaire : '' }}</small></div>
            @empty
                <div class="empty">Aucune classe rattachee.</div>
            @endforelse
        </div>

        <h2>Checklist</h2>
        <form method="POST" action="{{ route('teacher.tasks.store') }}" class="form-grid">
            @csrf
            <div><label>Tache</label><input name="titre" required></div>
            <div><label>Echeance</label><input type="date" name="date_echeance"></div>
            <div><label>Priorite</label><select name="priorite"><option value="normale">Normale</option><option value="haute">Haute</option><option value="basse">Basse</option></select></div>
            <button class="btn btn-primary">Ajouter</button>
        </form>
        <div class="task-list">
            @forelse($tasks as $task)
                <form method="POST" action="{{ route('teacher.tasks.toggle', $task->id) }}" class="task {{ $task->termine ? 'done' : '' }}" data-no-confirm>
                    @csrf
                    <button class="btn btn-primary" title="Terminer / rouvrir"><i class="fa {{ $task->termine ? 'fa-check' : 'fa-circle-o' }}"></i></button>
                    <span><strong>{{ $task->titre }}</strong><small class="meta">{{ $task->date_echeance ? \Carbon\Carbon::parse($task->date_echeance)->format('d/m/Y') : 'Sans echeance' }} - {{ $task->priorite }}</small></span>
                    <i class="fa fa-tasks"></i>
                </form>
            @empty
                <div class="empty">Aucune tache.</div>
            @endforelse
        </div>

        <h2 style="margin-top:18px"><i class="fa fa-calendar-check-o"></i> Ma presence</h2>
        @include('partials.presence-calendar', [
            'attendance' => $teacherAttendance,
            'month' => $calMonth,
            'year' => $calYear,
            'baseUrl' => route('teacher.workspace'),
            'label' => 'Presence',
        ])
    </aside>
</main>
<script>
function toggleJournalForm(){
    const f=document.getElementById('journalForm'),a=document.getElementById('journalFormArrow');
    if(!f)return;
    const open=f.style.display!=='none';
    f.style.display=open?'none':'block';
    a.classList.toggle('fa-chevron-down',open);
    a.classList.toggle('fa-chevron-up',!open);
}
function openEditLesson(id){
    const m=document.getElementById('editLessonModal'+id);
    if(m)m.style.display='flex';
}
function closeEditLesson(id){
    const m=document.getElementById('editLessonModal'+id);
    if(m)m.style.display='none';
}
document.addEventListener('DOMContentLoaded',()=>{
    document.querySelectorAll('.id-qr-box').forEach(function(q){
        const u=q.getAttribute('data-qr');
        if(u&&window.QRCode)new QRCode(q,{text:u,width:96,height:96,colorDark:'#0f2942',colorLight:'#ffffff',correctLevel:QRCode.CorrectLevel.H})
    })
})
</script>
</body>
</html>
