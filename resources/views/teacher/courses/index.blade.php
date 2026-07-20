<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mes cours - {{ $ecole->nom ?? 'Novaskol' }}</title>
    <link rel="stylesheet" href="{{ asset('legacy/assets/fontawesome/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('legacy/fa/css/font-awesome.min.css') }}">
    @include('modules.professeur.bulletin.partials.styles')
    <style>
        * { box-sizing: border-box; }
        .wrap { max-width: 1200px; margin: 0 auto; padding: 24px 16px; }
        .page-header { display: flex; align-items: center; justify-content: space-between; gap: 16px; margin-bottom: 24px; flex-wrap: wrap; }
        .page-header h1 { margin: 0; font-size: 1.5rem; display: flex; align-items: center; gap: 10px; color: var(--text); }
        .btn { display: inline-flex; align-items: center; gap: 6px; padding: 10px 18px; border-radius: 10px; font-weight: 600; font-size: .88rem; border: 0; cursor: pointer; text-decoration: none; transition: all .18s; }
        .btn-primary { background: var(--primary); color: #fff; box-shadow: 0 4px 12px rgba(0,200,83,.25); }
        .btn-primary:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(0,200,83,.35); }
        .btn-outline { background: transparent; border: 1px solid var(--border); color: var(--text); }
        .btn-outline:hover { background: var(--surface); border-color: var(--primary); color: var(--primary); }
        .btn-sm { padding: 7px 12px; font-size: .8rem; }
        .btn-success { background: var(--primary); color: #fff; }
        .btn-danger { background: #dc2626; color: #fff; }
        select { background: var(--surface); border: 1px solid var(--border); border-radius: 10px; padding: 10px 14px; font: inherit; color: var(--text); outline: none; min-width: 200px; }
        select:focus { border-color: var(--primary); }
        .grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(340px, 1fr)); gap: 16px; }
        .card { background: var(--card); border: 1px solid var(--border); border-radius: 14px; padding: 22px; box-shadow: 0 8px 24px var(--shadow-soft); transition: all .2s; position: relative; overflow: hidden; }
        .card:hover { transform: translateY(-2px); box-shadow: 0 12px 36px var(--shadow-strong); }
        .card::before { content: ''; position: absolute; top: 0; left: 0; width: 4px; height: 100%; background: var(--primary); border-radius: 14px 0 0 14px; opacity: .6; }
        .card .meta { display: flex; gap: 12px; color: var(--text-sec); font-size: .8rem; margin-bottom: 10px; flex-wrap: wrap; }
        .card .meta i { width: 14px; text-align: center; }
        .card h3 { margin: 0 0 6px; font-size: 1.1rem; }
        .card h3 a { color: var(--text); text-decoration: none; }
        .card h3 a:hover { color: var(--primary); }
        .card p { color: var(--text-sec); font-size: .85rem; line-height: 1.5; margin: 8px 0 0; }
        .badge { display: inline-block; padding: 3px 10px; border-radius: 999px; font-size: .7rem; font-weight: 700; text-transform: uppercase; letter-spacing: .3px; }
        .badge-brouillon { background: rgba(245,158,11,.15); color: #f59e0b; }
        .badge-publie { background: rgba(5,150,105,.15); color: var(--primary); }
        .badge-archive { background: rgba(100,116,139,.15); color: #94a3b8; }
        .card-actions { display: flex; gap: 6px; margin-top: 14px; flex-wrap: wrap; }
        .flash { padding: 14px 18px; border-radius: 10px; margin-bottom: 16px; font-size: .9rem; display: flex; align-items: center; gap: 10px; }
        .flash-success { background: rgba(5,150,105,.12); color: var(--primary); border: 1px solid rgba(5,150,105,.2); }
        .empty { padding: 60px 20px; text-align: center; color: var(--text-sec); }
        .empty i { font-size: 3.5rem; opacity: .3; display: block; margin-bottom: 14px; }
        .filtres { display: flex; gap: 12px; align-items: center; flex-wrap: wrap; margin-bottom: 24px; padding: 16px; background: var(--card); border: 1px solid var(--border); border-radius: 12px; }

        .modal-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,.6); z-index: 20000; align-items: center; justify-content: center; padding: 20px; backdrop-filter: blur(4px); }
        .modal-overlay.active { display: flex; }
        .modal { background: var(--card); border: 1px solid var(--border); border-radius: 16px; padding: 28px; width: 100%; max-width: 520px; box-shadow: 0 24px 60px rgba(0,0,0,.4); }
        .modal h2 { margin: 0 0 18px; color: var(--primary); font-size: 1.15rem; display: flex; align-items: center; gap: 8px; }
        .modal label { display: block; margin: 12px 0 5px; color: var(--text-sec); font-size: .82rem; font-weight: 600; }
        .modal input, .modal select, .modal textarea { width: 100%; padding: 11px; border: 1px solid var(--border); border-radius: 10px; background: var(--surface); color: var(--text); font-size: .9rem; }
        .modal textarea { min-height: 80px; resize: vertical; }
        .modal-actions { display: flex; gap: 10px; justify-content: flex-end; margin-top: 20px; }
        @media(max-width:760px) { .grid { grid-template-columns: 1fr; } .page-header { flex-direction: column; align-items: flex-start; } .card-actions { flex-wrap: wrap; } }
        :root.light .card { background: #fff !important; color: var(--text) !important; border-color: var(--border) !important; }
        :root.light .modal { background: #fff !important; }
        :root.light .filtres { background: #fff !important; }
        :root.light select { background: #fff !important; color: #111827 !important; }
        :root.light .modal input, :root.light .modal select, :root.light .modal textarea { background: #f8fafc !important; color: #111827 !important; }
    </style>
</head>
<body>
@include('modules.professeur.bulletin.partials.shell', ['activeModule' => 'teacher_courses'])
<header>
    <div class="header-left">
        <button class="burger-menu" onclick="toggleSidebar()"><i class="fa fa-bars"></i></button>
        <button id="fullscreen-btn" onclick="toggleFullscreen()"><i class="fa fa-expand"></i></button>
    </div>
    <div class="header-center">Mes cours</div>
</header>
<main>
<div class="wrap">
    @if (session('success'))
        <div class="flash flash-success"><i class="fa fa-check-circle"></i> {{ session('success') }}</div>
    @endif

    <div class="page-header">
        <h1><i class="fa fa-book" style="color:var(--primary)"></i> Mes cours</h1>
        <div style="display:flex;gap:8px">
            <a href="{{ route('teacher.workspace') }}" class="btn btn-outline btn-sm"><i class="fa fa-book-open"></i> Journal</a>
            <a href="{{ route('teacher.exercices.index') }}" class="btn btn-outline btn-sm"><i class="fa fa-puzzle-piece"></i> Exercices</a>
            <button class="btn btn-primary btn-sm" onclick="openModal('courseModal')"><i class="fa fa-plus"></i> Nouveau cours</button>
        </div>
    </div>

    <div class="filtres">
        <form method="GET" style="display:flex;gap:8px;align-items:center;width:100%">
            <i class="fa fa-filter" style="color:var(--text-sec)"></i>
            <select name="matiere_id" onchange="this.form.submit()">
                <option value="0">Toutes les matieres</option>
                @foreach ($matieres as $m)
                    <option value="{{ $m->id }}" {{ $matiereId == $m->id ? 'selected' : '' }}>{{ $m->nom }}</option>
                @endforeach
            </select>
        </form>
    </div>

    @if ($courses->isEmpty())
        <div class="empty">
            <i class="fa fa-book-open"></i>
            <p style="margin:0 0 16px;font-size:1rem">Aucun cours pour le moment.</p>
            <button class="btn btn-primary" onclick="openModal('courseModal')"><i class="fa fa-plus"></i> Creer mon premier cours</button>
        </div>
    @else
        <div class="grid">
            @foreach ($courses as $c)
                @php $chCount = DB::table('course_chapitres')->where('course_id', $c->id)->count(); @endphp
                <div class="card">
                    <div class="meta">
                        <span><i class="fa fa-tag"></i> {{ DB::table('matieres')->where('id', $c->matiere_id)->value('nom') ?? 'General' }}</span>
                        <span><i class="fa fa-layer-group"></i> {{ $chCount }} chapitre{{ $chCount > 1 ? 's' : '' }}</span>
                        <span class="badge badge-{{ $c->statut }}">{{ $c->statut }}</span>
                    </div>
                    <h3><a href="{{ route('teacher.courses.show', $c->id) }}">{{ $c->titre }}</a></h3>
                    @if ($c->niveau)<span style="color:var(--text-sec);font-size:.8rem"><i class="fa fa-graduation-cap"></i> {{ $c->niveau }}</span>@endif
                    @if ($c->description)
                        <p>{{ mb_substr($c->description, 0, 150) }}{{ mb_strlen($c->description) > 150 ? '...' : '' }}</p>
                    @endif
                    <div class="card-actions">
                        <a href="{{ route('teacher.courses.show', $c->id) }}" class="btn btn-outline btn-sm"><i class="fa fa-eye"></i> Voir</a>
                        <button class="btn btn-outline btn-sm" onclick="openEditCourse({{ $c->id }},'{{ addslashes($c->titre) }}','{{ addslashes($c->description) }}',{{ $c->matiere_id ?? 0 }},'{{ addslashes($c->niveau ?? '') }}','{{ $c->statut }}')"><i class="fa fa-edit"></i></button>
                        <form method="POST" action="{{ route('teacher.courses.destroy', $c->id) }}" style="display:inline" onsubmit="return confirm('Supprimer ce cours et tous ses chapitres ?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-outline btn-sm" style="color:#dc2626"><i class="fa fa-trash"></i></button>
                        </form>
                        @if ($c->statut !== 'publie')
                            <form method="POST" action="{{ route('teacher.courses.update', $c->id) }}" style="display:inline">
                                @csrf
                                <input type="hidden" name="titre" value="{{ $c->titre }}">
                                <input type="hidden" name="statut" value="publie">
                                <input type="hidden" name="matiere_id" value="{{ $c->matiere_id }}">
                                <button class="btn btn-success btn-sm"><i class="fa fa-check"></i> Publier</button>
                            </form>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

<!-- Create Course Modal -->
<div id="courseModal" class="modal-overlay" onclick="if(event.target===this)closeModal('courseModal')">
    <div class="modal">
        <h2><i class="fa fa-plus-circle" style="color:var(--primary)"></i> Nouveau cours</h2>
        <form method="POST" action="{{ route('teacher.courses.store') }}">
            @csrf
            <label>Titre du cours *</label>
            <input name="titre" required maxlength="200" placeholder="ex: Mathematiques 6eme">
            <label>Matiere</label>
            <select name="matiere_id">
                <option value="">-- Selectionner --</option>
                @foreach ($matieres as $m)
                    <option value="{{ $m->id }}">{{ $m->nom }}</option>
                @endforeach
            </select>
            <label>Niveau</label>
            <input name="niveau" maxlength="50" placeholder="ex: 6eme, 5eme, Terminale">
            <label>Description</label>
            <textarea name="description" rows="3" maxlength="5000" placeholder="Decrivez le contenu du cours..."></textarea>
            <div class="modal-actions">
                <button type="button" class="btn btn-outline" onclick="closeModal('courseModal')">Annuler</button>
                <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Creer</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Course Modal -->
<div id="editCourseModal" class="modal-overlay" onclick="if(event.target===this)closeModal('editCourseModal')">
    <div class="modal">
        <h2><i class="fa fa-edit" style="color:var(--primary)"></i> Modifier le cours</h2>
        <form method="POST" action="" id="editCourseForm">
            @csrf
            <label>Titre *</label>
            <input name="titre" id="edit_titre" required maxlength="200">
            <label>Matiere</label>
            <select name="matiere_id" id="edit_matiere_id">
                <option value="">-- Selectionner --</option>
                @foreach ($matieres as $m)
                    <option value="{{ $m->id }}">{{ $m->nom }}</option>
                @endforeach
            </select>
            <label>Niveau</label>
            <input name="niveau" id="edit_niveau" maxlength="50">
            <label>Description</label>
            <textarea name="description" id="edit_description" rows="3"></textarea>
            <label>Statut</label>
            <select name="statut" id="edit_statut">
                <option value="brouillon">Brouillon</option>
                <option value="publie">Publie</option>
                <option value="archive">Archive</option>
            </select>
            <div class="modal-actions">
                <button type="button" class="btn btn-outline" onclick="closeModal('editCourseModal')">Annuler</button>
                <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Enregistrer</button>
            </div>
        </form>
    </div>
</div>
</main>
<script>
function toggleSub(e){const s=e.nextElementSibling,a=e.querySelector('.arrow');s.style.display=s.style.display==='block'?'none':'block';a.classList.toggle('fa-chevron-down');a.classList.toggle('fa-chevron-up')}
function toggleSidebar(){document.getElementById('sidebar').classList.toggle('active')}
function toggleFullscreen(){document.fullscreenElement?document.exitFullscreen():document.documentElement.requestFullscreen()}
function openModal(id){document.getElementById(id).classList.add('active')}
function closeModal(id){document.getElementById(id).classList.remove('active')}
function openEditCourse(id, titre, desc, matiereId, niveau, statut){
    document.getElementById('editCourseForm').action = '{{ url("enseignant/cours") }}/' + id;
    document.getElementById('edit_titre').value = titre;
    document.getElementById('edit_description').value = desc;
    document.getElementById('edit_niveau').value = niveau;
    document.getElementById('edit_statut').value = statut;
    if(document.getElementById('edit_matiere_id')) document.getElementById('edit_matiere_id').value = matiereId;
    openModal('editCourseModal');
}
@if ($errors->any())
    openModal('courseModal');
@endif
</script>
</body>
</html>
