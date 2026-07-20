<!DOCTYPE html>
<html lang="fr">
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>{{ $course->titre }} - Novaskol</title>
<link rel="stylesheet" href="{{ asset('legacy/assets/fontawesome/css/all.min.css') }}">
<link rel="stylesheet" href="{{ asset('legacy/fa/css/font-awesome.min.css') }}">
@include('modules.professeur.bulletin.partials.styles')
<style>:root{--bg:#f1f5f9;--surface:#fff;--text:#1e293b;--muted:#64748b;--line:#e2e8f0;--green:#16a34a;--blue:#2563eb;--orange:#ea580c}*{box-sizing:border-box}.wrap{max-width:1000px;margin:0 auto;padding:24px 16px}.back{margin-bottom:16px;display:inline-flex;align-items:center;gap:6px;color:var(--muted);text-decoration:none;font-size:.9rem}.back:hover{color:var(--text)}.header-card{background:var(--surface);border:1px solid var(--line);border-radius:12px;padding:24px;margin-bottom:20px}.header-card h1{margin:0 0 6px;font-size:1.4rem}.header-card .meta{color:var(--muted);font-size:.85rem;display:flex;gap:16px;flex-wrap:wrap;margin-bottom:12px}.badge{display:inline-block;padding:2px 10px;border-radius:20px;font-size:.75rem;font-weight:600}.badge-brouillon{background:#fef3c7;color:#92400e}.badge-publie{background:#dcfce7;color:#166534}.badge-archive{background:#f1f5f9;color:#475569}.desc{color:var(--muted);font-size:.9rem;line-height:1.6;margin:0 0 12px}.btn{display:inline-flex;align-items:center;gap:6px;padding:8px 14px;border-radius:8px;font-weight:600;font-size:.85rem;border:0;cursor:pointer;text-decoration:none;transition:all .15s}.btn-primary{background:var(--blue);color:#fff}.btn-success{background:var(--green);color:#fff}.btn-danger{background:#dc2626;color:#fff}.btn-outline{background:transparent;border:1px solid var(--line);color:var(--text)}.btn-outline:hover{background:var(--line)}.btn-sm{padding:5px 10px;font-size:.78rem}.chapitre-card{background:var(--surface);border:1px solid var(--line);border-radius:10px;margin-bottom:12px;overflow:hidden}.chapitre-header{display:flex;align-items:center;justify-content:space-between;padding:14px 16px;cursor:pointer;gap:8px}.chapitre-header:hover{background:#f8fafc}.chapitre-header h3{margin:0;font-size:.95rem;display:flex;align-items:center;gap:8px;flex:1;min-width:0}.chapitre-header .ordre{background:var(--blue);color:#fff;border-radius:50%;width:28px;height:28px;display:grid;place-items:center;font-size:.75rem;font-weight:700;flex-shrink:0}.chapitre-content{display:none;padding:0 16px 16px;border-top:1px solid var(--line)}.chapitre-content.open{display:block}.flash{padding:12px 16px;border-radius:8px;margin-bottom:16px;font-size:.9rem}.flash-success{background:#dcfce7;color:#166534;border:1px solid #bbf7d0}.fichier-item{display:flex;align-items:center;gap:10px;padding:8px 0;border-bottom:1px solid var(--line)}.fichier-item:last-child{border-bottom:0}.fichier-item .icon{width:32px;text-align:center;font-size:1.2rem;color:var(--muted)}.fichier-item .info{flex:1;min-width:0}.fichier-item .name{font-size:.85rem;font-weight:600;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}.fichier-item .size{font-size:.72rem;color:var(--muted)}.modal-overlay{display:none;position:fixed;inset:0;background:rgba(0,0,0,.6);z-index:20000;align-items:center;justify-content:center;padding:20px;backdrop-filter:blur(4px)}.modal-overlay.active{display:flex}.modal{background:var(--surface);border:1px solid var(--line);border-radius:16px;padding:28px;width:100%;max-width:520px;box-shadow:0 24px 60px rgba(0,0,0,.4)}.modal h2{margin:0 0 18px;font-size:1.15rem;display:flex;align-items:center;gap:8px}.modal label{display:block;margin:12px 0 5px;color:var(--muted);font-size:.82rem;font-weight:600}.modal input,.modal select,.modal textarea{width:100%;padding:11px;border:1px solid var(--line);border-radius:10px;font-size:.9rem;outline:none}.modal textarea{min-height:80px;resize:vertical}.modal-actions{display:flex;gap:10px;justify-content:flex-end;margin-top:20px}</style>
</head>
<body>
@include('modules.professeur.bulletin.partials.shell', ['activeModule' => 'teacher_courses'])
<header>
    <div class="header-left">
        <button class="burger-menu" onclick="toggleSidebar()"><i class="fa fa-bars"></i></button>
        <button id="fullscreen-btn" onclick="toggleFullscreen()"><i class="fa fa-expand"></i></button>
    </div>
    <div class="header-center">{{ $course->titre }}</div>
</header>
<main>
<div class="wrap">
    @if (session('success'))
        <div class="flash flash-success"><i class="fa fa-check-circle"></i> {{ session('success') }}</div>
    @endif

    <div class="header-card">
        <div style="display:flex;justify-content:space-between;align-items:start;gap:12px;flex-wrap:wrap">
            <div style="flex:1">
                <h1>{{ $course->titre }}</h1>
                <div class="meta">
                    <span><i class="fa fa-tag"></i> {{ DB::table('matieres')->where('id', $course->matiere_id)->value('nom') ?? 'Toutes' }}</span>
                    <span class="badge badge-{{ $course->statut }}">{{ $course->statut }}</span>
                    @if ($course->niveau)<span><i class="fa fa-layer-group"></i> {{ $course->niveau }}</span>@endif
                    <span><i class="fa fa-list"></i> {{ $chapitres->count() }} chapitres</span>
                </div>
                @if ($course->description)<p class="desc">{{ $course->description }}</p>@endif
            </div>
            <div style="display:flex;gap:6px;flex-wrap:wrap">
                <button class="btn btn-outline btn-sm" onclick="openEdit()"><i class="fa fa-edit"></i> Modifier</button>
                <button class="btn btn-success btn-sm" onclick="openModal('chapitreModal')"><i class="fa fa-plus"></i> Chapitre</button>
                <a href="{{ route('teacher.exercices.index', ['course_id' => $course->id]) }}" class="btn btn-outline btn-sm"><i class="fa fa-puzzle-piece"></i> Exercices</a>
            </div>
        </div>
    </div>

    @if ($chapitres->isEmpty())
        <div style="text-align:center;padding:60px 20px;color:var(--muted)">
            <i class="fa fa-file" style="font-size:3rem;opacity:.4;margin-bottom:12px;display:block"></i>
            <p>Aucun chapitre. Ajoutez votre premier chapitre.</p>
            <button class="btn btn-primary" onclick="openModal('chapitreModal')" style="margin-top:8px"><i class="fa fa-plus"></i> Ajouter un chapitre</button>
        </div>
    @else
        @foreach ($chapitres as $i => $ch)
            <div class="chapitre-card">
                <div class="chapitre-header" onclick="this.nextElementSibling.classList.toggle('open')">
                    <span class="ordre">{{ $i + 1 }}</span>
                    <h3>
                        {{ $ch->titre }}
                        @if ($ch->statut === 'masque')
                            <span class="badge badge-brouillon" style="font-size:.7rem">Masque</span>
                        @endif
                        <span style="font-size:.78rem;color:var(--muted);font-weight:400">{{ $ch->fichiers->count() }} fichier(s)</span>
                    </h3>
                    <div style="display:flex;gap:4px;flex-shrink:0" onclick="event.stopPropagation()">
                        <button class="btn btn-outline btn-sm" onclick="openEditChapitre({{ $ch->id }},'{{ addslashes($ch->titre) }}','{{ addslashes($ch->description) }}','{{ $ch->statut }}')"><i class="fa fa-edit"></i></button>
                        <button class="btn btn-outline btn-sm" onclick="openUpload({{ $ch->id }})"><i class="fa fa-upload"></i></button>
                        <form method="POST" action="{{ route('teacher.courses.chapitres.destroy', $ch->id) }}" style="display:inline" onsubmit="return confirm('Supprimer ce chapitre et ses fichiers ?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-outline btn-sm" style="color:#dc2626"><i class="fa fa-trash"></i></button>
                        </form>
                    </div>
                </div>
                <div class="chapitre-content{{ $loop->first ? ' open' : '' }}">
                    @if ($ch->description)
                        <p style="color:var(--muted);font-size:.85rem;margin:0 0 10px">{{ $ch->description }}</p>
                    @endif

                    @if ($ch->fichiers->isNotEmpty())
                        <div style="margin-bottom:8px">
                            @foreach ($ch->fichiers as $f)
                                <div class="fichier-item">
                                    <div class="icon">
                                        @switch($f->type)
                                            @case('pdf')<i class="fa fa-file-pdf"></i>@break
                                            @case('video')<i class="fa fa-file-video"></i>@break
                                            @case('image')<i class="fa fa-file-image"></i>@break
                                            @case('audio')<i class="fa fa-file-audio"></i>@break
                                            @default<i class="fa fa-file"></i>
                                        @endswitch
                                    </div>
                                    <div class="info">
                                        <div class="name">{{ $f->nom_original }}</div>
                                        <div class="size">{{ round($f->taille / 1024, 1) }} Ko</div>
                                    </div>
                                    <a href="{{ route('teacher.courses.fichiers.download', $f->id) }}" class="btn btn-outline btn-sm"><i class="fa fa-download"></i></a>
                                    <form method="POST" action="{{ route('teacher.courses.fichiers.destroy', $f->id) }}" style="display:inline" onsubmit="return confirm('Supprimer ce fichier ?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-outline btn-sm" style="color:#dc2626"><i class="fa fa-trash"></i></button>
                                    </form>
                                </div>
                            @endforeach
                        </div>
                    @endif
                    <button class="btn btn-outline btn-sm" onclick="openUpload({{ $ch->id }})"><i class="fa fa-upload"></i> Ajouter un fichier</button>
                    <a href="{{ route('teacher.exercices.create', $ch->id) }}" class="btn btn-outline btn-sm"><i class="fa fa-plus-circle"></i> Exercice</a>
                </div>
            </div>
        @endforeach
    @endif
</div>

<!-- Chapitre Modal -->
<div id="chapitreModal" class="modal-overlay" onclick="if(event.target===this)closeModal('chapitreModal')">
    <div class="modal">
        <h2><i class="fa fa-plus-circle" style="color:var(--blue)"></i> Nouveau chapitre</h2>
        <form method="POST" action="{{ route('teacher.courses.chapitres.store', $course->id) }}">
            @csrf
            <label>Titre du chapitre *</label>
            <input name="titre" required maxlength="200" placeholder="ex: Introduction aux fractions">
            <label>Description (optionnelle)</label>
            <textarea name="description" rows="3" maxlength="5000" placeholder="Decrivez le contenu du chapitre..."></textarea>
            <div class="modal-actions">
                <button type="button" class="btn btn-outline" onclick="closeModal('chapitreModal')">Annuler</button>
                <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Ajouter</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Chapitre Modal -->
<div id="editChapitreModal" class="modal-overlay" onclick="if(event.target===this)closeModal('editChapitreModal')">
    <div class="modal">
        <h2><i class="fa fa-edit" style="color:var(--blue)"></i> Modifier le chapitre</h2>
        <form method="POST" action="" id="editChapitreForm">
            @csrf
            <label>Titre *</label>
            <input name="titre" id="editCh_titre" required maxlength="200">
            <label>Description</label>
            <textarea name="description" id="editCh_description" rows="3"></textarea>
            <label>Statut</label>
            <select name="statut" id="editCh_statut">
                <option value="publie">Publie</option>
                <option value="masque">Masque</option>
            </select>
            <div class="modal-actions">
                <button type="button" class="btn btn-outline" onclick="closeModal('editChapitreModal')">Annuler</button>
                <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Enregistrer</button>
            </div>
        </form>
    </div>
</div>

<!-- Upload Modal -->
<div id="uploadModal" class="modal-overlay" onclick="if(event.target===this)closeModal('uploadModal')">
    <div class="modal">
        <h2><i class="fa fa-upload" style="color:var(--blue)"></i> Ajouter un fichier</h2>
        <form method="POST" action="" id="uploadForm" enctype="multipart/form-data">
            @csrf
            <label>Fichier * (PDF, video, image, document - max 100 Mo)</label>
            <input type="file" name="fichier" required>
            <div class="modal-actions">
                <button type="button" class="btn btn-outline" onclick="closeModal('uploadModal')">Annuler</button>
                <button type="submit" class="btn btn-primary"><i class="fa fa-upload"></i> Uploader</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Course Modal -->
<div id="editCourseModal" class="modal-overlay" onclick="if(event.target===this)closeModal('editCourseModal')">
    <div class="modal">
        <h2><i class="fa fa-edit" style="color:var(--blue)"></i> Modifier le cours</h2>
        <form method="POST" action="{{ route('teacher.courses.update', $course->id) }}">
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
function openEdit(){
    document.getElementById('edit_titre').value = '{{ addslashes($course->titre) }}';
    document.getElementById('edit_description').value = '{{ addslashes($course->description ?? '') }}';
    document.getElementById('edit_niveau').value = '{{ addslashes($course->niveau ?? '') }}';
    if(document.getElementById('edit_matiere_id')) document.getElementById('edit_matiere_id').value = '{{ $course->matiere_id }}';
    document.getElementById('edit_statut').value = '{{ $course->statut }}';
    openModal('editCourseModal');
}
function openEditChapitre(id, titre, description, statut) {
    document.getElementById('editChapitreForm').action = '{{ url("enseignant/chapitres") }}/' + id;
    document.getElementById('editCh_titre').value = titre;
    document.getElementById('editCh_description').value = description;
    document.getElementById('editCh_statut').value = statut;
    openModal('editChapitreModal');
}
function openUpload(chapitreId) {
    document.getElementById('uploadForm').action = '{{ url("enseignant/chapitres") }}/' + chapitreId + '/fichiers';
    openModal('uploadModal');
}
</script>
</body>
</html>
