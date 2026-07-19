<!DOCTYPE html>
<html lang="fr">
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Mes cours - Novaskol</title>
<link rel="stylesheet" href="{{ asset('legacy/assets/fontawesome/css/all.min.css') }}">
<style>:root{--bg:#f1f5f9;--surface:#fff;--text:#1e293b;--muted:#64748b;--line:#e2e8f0;--green:#16a34a;--blue:#2563eb;--orange:#ea580c}.hljs{display:block;overflow-x:auto;padding:.5em;background:#0d1117;color:#c9d1d9;border-radius:8px;margin:8px 0}*{box-sizing:border-box}body{margin:0;font-family:Inter,system-ui,sans-serif;background:var(--bg);color:var(--text);min-height:100vh}.wrap{max-width:1200px;margin:0 auto;padding:24px 16px}.header{display:flex;align-items:center;justify-content:space-between;gap:16px;margin-bottom:24px;flex-wrap:wrap}.header h1{margin:0;font-size:1.5rem;display:flex;align-items:center;gap:8px}.btn{display:inline-flex;align-items:center;gap:6px;padding:10px 18px;border-radius:8px;font-weight:600;font-size:.9rem;border:0;cursor:pointer;text-decoration:none;transition:all .15s}.btn-primary{background:var(--blue);color:#fff}.btn-primary:hover{background:#1d4ed8}.btn-outline{background:transparent;border:1px solid var(--line);color:var(--text)}.btn-outline:hover{background:var(--line)}.btn-sm{padding:6px 12px;font-size:.82rem}.btn-success{background:var(--green);color:#fff}.btn-danger{background:#dc2626;color:#fff}.btn-warning{background:var(--orange);color:#fff}.filtres{display:flex;gap:8px;align-items:center;flex-wrap:wrap;margin-bottom:20px}select,input{background:var(--surface);border:1px solid var(--line);border-radius:8px;padding:8px 12px;font:inherit;color:var(--text);outline:none}select:focus,input:focus{border-color:var(--blue)}.grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(320px,1fr));gap:16px}.card{background:var(--surface);border:1px solid var(--line);border-radius:12px;padding:20px;transition:box-shadow .15s}.card:hover{box-shadow:0 4px 12px rgba(0,0,0,.08)}.card h3{margin:0 0 6px;font-size:1.05rem}.card h3 a{color:var(--text);text-decoration:none}.card h3 a:hover{color:var(--blue)}.card .meta{display:flex;gap:12px;color:var(--muted);font-size:.82rem;margin-bottom:10px}.card .meta i{width:14px}.card p{color:var(--muted);font-size:.88rem;line-height:1.5;margin:0 0 12px}.badge{display:inline-block;padding:2px 10px;border-radius:20px;font-size:.75rem;font-weight:600}.badge-brouillon{background:#fef3c7;color:#92400e}.badge-publie{background:#dcfce7;color:#166534}.badge-archive{background:#f1f5f9;color:#475569}.card-actions{display:flex;gap:6px;margin-top:12px;flex-wrap:wrap}.empty{text-align:center;padding:60px 20px;color:var(--muted)}.empty i{font-size:3rem;margin-bottom:16px;opacity:.4}.empty p{font-size:1.1rem}.flash{padding:12px 16px;border-radius:8px;margin-bottom:16px;font-size:.9rem}.flash-success{background:#dcfce7;color:#166534;border:1px solid #bbf7d0}.modal-overlay{display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:1000;align-items:center;justify-content:center}.modal-overlay.active{display:flex}.modal{background:var(--surface);border-radius:16px;padding:28px;max-width:520px;width:90%;max-height:80vh;overflow-y:auto}.modal h2{margin:0 0 16px;font-size:1.2rem}.modal label{display:block;font-size:.85rem;font-weight:600;margin-bottom:4px;color:var(--text)}.modal input,.modal select,.modal textarea{width:100%;margin-bottom:12px}.modal-actions{display:flex;gap:8px;justify-content:flex-end;margin-top:16px}@media(max-width:640px){.grid{grid-template-columns:1fr}.header{flex-direction:column;align-items:stretch}}</style>
</head>
<body>
<div class="wrap">
    @if (session('success'))
        <div class="flash flash-success"><i class="fa fa-check-circle"></i> {{ session('success') }}</div>
    @endif

    <div class="header">
        <h1><i class="fa fa-book" style="color:var(--blue)"></i> Mes cours</h1>
        <div style="display:flex;gap:8px">
            <a href="{{ route('teacher.exercices.index') }}" class="btn btn-outline btn-sm"><i class="fa fa-puzzle-piece"></i> Exercices</a>
            <a href="{{ route('teacher.workspace') }}" class="btn btn-outline btn-sm"><i class="fa fa-arrow-left"></i> Espace enseignant</a>
            <button class="btn btn-primary btn-sm" onclick="openModal('courseModal')"><i class="fa fa-plus"></i> Nouveau cours</button>
        </div>
    </div>

    <div class="filtres">
        <form method="GET" style="display:flex;gap:8px;align-items:center">
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
            <p>Aucun cours pour le moment.<br><button class="btn btn-primary" onclick="openModal('courseModal')" style="margin-top:12px">Creer mon premier cours</button></p>
        </div>
    @else
        <div class="grid">
            @foreach ($courses as $c)
                <div class="card">
                    <div class="meta">
                        <span><i class="fa fa-tag"></i> {{ DB::table('matieres')->where('id', $c->matiere_id)->value('nom') ?? 'Toutes' }}</span>
                        <span><i class="fa fa-layer-group"></i> {{ DB::table('course_chapitres')->where('course_id', $c->id)->count() }} chapitres</span>
                    </div>
                    <h3><a href="{{ route('teacher.courses.show', $c->id) }}">{{ $c->titre }}</a></h3>
                    <span class="badge badge-{{ $c->statut }}">{{ $c->statut }}</span>
                    @if ($c->description)
                        <p>{{ mb_substr($c->description, 0, 120) }}{{ mb_strlen($c->description) > 120 ? '...' : '' }}</p>
                    @endif
                    <div class="card-actions">
                        <a href="{{ route('teacher.courses.show', $c->id) }}" class="btn btn-outline btn-sm"><i class="fa fa-eye"></i> Voir</a>
                        <button class="btn btn-outline btn-sm" onclick="openEditCourse({{ $c->id }}, '{{ addslashes($c->titre) }}', '{{ addslashes($c->description) }}', {{ $c->matiere_id ?? 0 }}, '{{ $c->niveau ?? '' }}', '{{ $c->statut }}')"><i class="fa fa-edit"></i></button>
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
        <h2><i class="fa fa-plus-circle" style="color:var(--blue)"></i> Nouveau cours</h2>
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
        <h2><i class="fa fa-edit" style="color:var(--blue)"></i> Modifier le cours</h2>
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

<script>
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
