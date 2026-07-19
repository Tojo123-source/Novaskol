<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title }} - {{ $ecole->nom ?? 'Ecole' }}</title>
    <link rel="stylesheet" href="{{ asset('legacy/assets/fontawesome/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('legacy/fa/css/font-awesome.min.css') }}">
    @include('modules.professeur.bulletin.partials.styles')
    <style>
        .rh-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(210px,1fr)); gap:14px; }
        .rh-panel { background:var(--card); border:1px solid var(--border); border-radius:8px; padding:18px; margin-bottom:18px; }
        .rh-panel h2 { font-size:1.12rem; margin-bottom:16px; }
        .rh-table-wrap { overflow:auto; border:1px solid var(--border); border-radius:8px; background:var(--surface); }
        .rh-table { width:100%; border-collapse:collapse; min-width:980px; }
        .rh-table th,.rh-table td { padding:12px; border-bottom:1px solid var(--border); text-align:left; vertical-align:top; }
        .rh-table th { background:#0f172a; color:var(--primary); font-size:.82rem; text-transform:uppercase; }
        input,select,textarea { width:100%; padding:12px; background:var(--surface); color:var(--text); border:1px solid var(--border); border-radius:8px; }
        input[type="file"] { padding:9px; }
        textarea { min-height:74px; resize:vertical; }
        .btn-line { display:flex; gap:8px; flex-wrap:wrap; align-items:center; }
        .btn-danger { background:#dc2626; color:white; border:0; border-radius:8px; padding:10px 14px; cursor:pointer; font-weight:700; }
        .btn-small { background:#2563eb; color:white; border:0; border-radius:8px; padding:10px 14px; cursor:pointer; font-weight:700; }
        .status { display:inline-block; padding:4px 9px; border-radius:999px; background:rgba(0,200,83,.14); color:var(--primary); font-size:.82rem; font-weight:700; }
        .muted { color:var(--text-sec); font-size:.9rem; }
        .photo { width:46px; height:46px; border-radius:8px; object-fit:cover; background:#0f172a; border:1px solid var(--border); }
        details { margin-top:10px; }
        details summary { cursor:pointer; color:var(--primary); font-weight:700; }
        .checks { display:grid; grid-template-columns:repeat(auto-fit,minmax(220px,1fr)); gap:8px; padding:10px; background:var(--surface); border:1px solid var(--border); border-radius:8px; }
        .checks label { display:flex; gap:8px; align-items:center; margin:0; font-weight:500; color:var(--text); }
        .checks input { width:auto; }
        .class-assignment { display:grid; grid-template-columns:auto 1fr; gap:8px; align-items:start; padding:10px; border:1px solid var(--border); border-radius:8px; background:rgba(255,255,255,.025); }
        .class-assignment select,.class-assignment input[type="text"] { padding:8px; font-size:.88rem; }
        .class-assignment small { color:var(--text-sec); }
        .account-box { margin-top:14px; padding:14px; border:1px solid var(--border); border-radius:8px; background:var(--surface); }
        .account-box strong { display:block; color:var(--primary); margin-bottom:6px; }
        .orphan-list { display:grid; grid-template-columns:repeat(auto-fit,minmax(260px,1fr)); gap:10px; }
        .orphan-card { border:1px solid var(--border); border-radius:8px; padding:12px; background:var(--surface); display:flex; justify-content:space-between; gap:10px; align-items:center; }
        .orphan-card small { color:var(--text-sec); display:block; margin-top:3px; }
        .alert { padding:12px 14px; border-radius:8px; margin-bottom:14px; border:1px solid var(--border); }
        .alert.success { background:rgba(16,185,129,.12); color:#a7f3d0; }
        .alert.error { background:rgba(239,68,68,.12); color:#fecaca; }
        .people-cards { display:grid; grid-template-columns:repeat(auto-fill,minmax(280px,1fr)); gap:16px; }
        .person-card { background:linear-gradient(180deg,var(--surface),var(--card)); border:1px solid var(--border); border-radius:8px; padding:16px; box-shadow:0 16px 36px #0004; display:flex; flex-direction:column; gap:13px; }
        .person-head { display:flex; gap:12px; align-items:center; min-width:0; }
        .person-head .photo { width:58px; height:58px; border-radius:999px; flex:0 0 58px; }
        .person-title { min-width:0; }
        .person-title strong { display:block; font-size:1.02rem; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; }
        .person-meta { display:grid; gap:8px; color:var(--text-sec); font-size:.9rem; }
        .person-meta span { display:flex; align-items:flex-start; gap:8px; min-width:0; }
        .person-meta i { color:var(--primary); width:16px; margin-top:2px; }
        .person-actions { display:flex; gap:8px; flex-wrap:wrap; margin-top:auto; }
        .modal-backdrop { position:fixed; inset:0; background:rgba(0,0,0,.62); z-index:3900; display:none; align-items:flex-start; justify-content:center; overflow:auto; padding:70px 14px 28px; }
        .modal-backdrop.active { display:flex; }
        .rh-modal { width:min(980px,100%); background:var(--card); color:var(--text); border:1px solid var(--border); border-radius:8px; box-shadow:0 28px 80px #000b; padding:18px; }
        .rh-modal-head { display:flex; justify-content:space-between; align-items:center; gap:14px; margin-bottom:14px; }
        .rh-modal-head h2 { margin:0; color:var(--primary); font-size:1.05rem; }
        .modal-close { width:36px; height:36px; border-radius:999px; border:1px solid var(--border); background:var(--surface); color:var(--text); cursor:pointer; font-weight:900; }
        @media(max-width:760px){.people-cards{grid-template-columns:1fr}.rh-modal{padding:14px}}
    </style>
</head>
<body>
@include('modules.professeur.bulletin.partials.shell')
<header>
    <div class="header-left">
        <button class="burger-menu" onclick="toggleSidebar()"><i class="fa fa-bars"></i></button>
        <button id="fullscreen-btn" onclick="toggleFullscreen()"><i class="fa fa-expand"></i></button>
    </div>
    <div class="header-center">{{ $title }}</div>
</header>
<main>
    <section class="rh-panel">
        <h2>Ajouter</h2>
        <form id="addPeopleForm" method="POST" action="{{ $type === 'teachers' ? route('modules.enseignants.store') : route('modules.staff.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="rh-grid">
                <div><label>Nom *</label><input type="text" name="nom" required></div>
                <div><label>Prenom *</label><input type="text" name="prenom" required></div>
                <div><label>Email *</label><input type="email" name="email" required></div>
                <div><label>Telephone *</label><input type="text" name="telephone" required></div>
                <div><label>Annee scolaire *</label><input type="text" name="annee_scolaire" value="{{ $selectedAnnee ?: date('Y').'-'.(date('Y') + 1) }}" required></div>
                @if ($type === 'teachers')
                    <div>
                        <label>Matiere *</label>
                        <select name="matiere_id" required>
                            <option value="">Choisir</option>
                            @foreach ($matieres as $matiere)
                                <option value="{{ $matiere->id }}">{{ $matiere->nom }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div><label>Salaire horaire</label><input type="number" name="salaire_horaire" min="0" step="1" value="0"></div>
                    <div>
                        <label>Autorisation</label>
                        <select name="autorisation_enseigner">
                            <option>Non</option><option>Oui</option><option>En cours</option>
                        </select>
                    </div>
                @else
                    <div>
                        <label>Role *</label>
                        <select name="role_id" required>
                            <option value="">Choisir</option>
                            @foreach ($roles as $role)
                                <option value="{{ $role->id }}">{{ $role->nom }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label>Departement</label>
                        <select name="departement_id">
                            <option value="">Aucun</option>
                            @foreach ($departements as $departement)
                                <option value="{{ $departement->id }}">{{ $departement->nom }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div><label>Salaire base</label><input type="number" name="salaire_base" min="0" step="1" value="0"></div>
                @endif
                <div><label>Diplome pedagogique</label><input type="text" name="diplome_pedagogique" value="Aucun"></div>
                <div><label>Annees experience</label><input type="number" name="annees_experience" min="0" value="0"></div>
                @if($type === 'teachers')<div>
                    <label>Statut</label>
                    <select name="statut"><option value="actif">actif</option><option value="inactif">inactif</option></select>
                </div>@endif
                <div><label>Photo</label><input type="file" name="photo" accept="image/*"></div>
            </div>
            <div class="account-box">
                <strong>Compte de connexion</strong>
                <p class="muted">Le compte {{ $type === 'teachers' ? 'enseignant' : 'staff' }} sera cree ou rattache automatiquement avec cet email. Si le mot de passe est vide sur un nouveau compte, le telephone sera utilise comme mot de passe temporaire.</p>
                <div class="rh-grid">
                    <div><label>Mot de passe initial</label><input type="password" name="mot_de_passe" placeholder="Optionnel"></div>
                </div>
            </div>
            @if ($type === 'teachers')
                <label style="margin-top:14px;">Classes</label>
                <div class="checks">
                    @foreach ($classes as $classe)
                        <div class="class-assignment">
                            <input type="checkbox" name="classes_ids[]" value="{{ $classe->id }}">
                            <div>
                                <label>{{ $classe->nom }}</label>
                                <select name="classes_modes[{{ $classe->id }}]">
                                    <option value="fixe">Classe fixe</option>
                                    <option value="flexible">Intervention flexible</option>
                                </select>
                                <input type="text" name="classes_notes[{{ $classe->id }}]" placeholder="Rubrique, remplacement, groupe temporaire...">
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
            <button class="kaly" type="submit"><i class="fa fa-save"></i> Enregistrer</button>
        </form>
    </section>

    @if(($usersWithoutProfile ?? collect())->isNotEmpty())
        <section class="rh-panel">
            <h2>Comptes {{ $type === 'teachers' ? 'enseignants' : 'staff' }} sans profil</h2>
            <p class="muted">Ces comptes existent deja dans la connexion, mais il manque leur fiche {{ $type === 'teachers' ? 'enseignant' : 'staff' }}. Clique sur Completer, puis choisis les informations manquantes.</p>
            <div class="orphan-list">
                @foreach($usersWithoutProfile as $orphan)
                    <div class="orphan-card">
                        <span><strong>{{ $orphan->nom }}</strong><small>{{ $orphan->email }}</small></span>
                        <button class="btn-small" type="button" onclick="fillRhProfile(@js($orphan->nom), @js($orphan->email))">Completer</button>
                    </div>
                @endforeach
            </div>
        </section>
    @endif

    <section class="rh-panel">
        <div class="btn-line" style="justify-content:space-between; margin-bottom:14px;">
            <h2>Liste</h2>
            <form method="GET" class="btn-line">
                <select name="annee_scolaire" onchange="this.form.submit()">
                    <option value="">Toutes les annees</option>
                    @foreach ($annees as $annee)
                        <option value="{{ $annee }}" @selected($selectedAnnee === $annee)>{{ $annee }}</option>
                    @endforeach
                </select>
            </form>
        </div>
        <div class="people-cards">
            @forelse ($people as $person)
                @php
                    $photoPath = $person->photo ? asset('legacy/'.ltrim($person->photo, '/')) : null;
                @endphp
                <article class="person-card">
                    <div class="person-head">
                        @if($photoPath)<img class="photo" src="{{ $photoPath }}" alt="">@else<div class="photo"></div>@endif
                        <div class="person-title">
                            <strong>{{ $person->nom }} {{ $person->prenom }}</strong>
                            @if($type === 'teachers')<span class="status">{{ $person->statut ?? 'actif' }}</span>@endif
                        </div>
                    </div>
                    <div class="person-meta">
                        <span><i class="fa fa-envelope"></i> {{ $person->email }}</span>
                        <span><i class="fa fa-phone"></i> {{ $person->telephone }}</span>
                        <span><i class="fa fa-calendar"></i> {{ $person->annee_scolaire }}</span>
                        @if ($type === 'teachers')
                            <span><i class="fa fa-book"></i> {{ $person->matiere_nom ?? '-' }}</span>
                            <span><i class="fa fa-users"></i> {{ $person->classes_labels ?: 'Aucune classe' }}</span>
                        @else
                            <span><i class="fa fa-id-badge"></i> {{ $person->role_nom ?? '-' }}</span>
                            <span><i class="fa fa-building"></i> {{ $person->departement_nom ?? 'Aucun departement' }}</span>
                        @endif
                        <span><i class="fa fa-graduation-cap"></i> {{ $person->diplome_pedagogique ?? 'Aucun' }} - {{ $person->annees_experience ?? 0 }} ans</span>
                        <span><i class="fa fa-money"></i> {{ number_format((float) ($type === 'teachers' ? $person->salaire_horaire : $person->salaire_base), 0, ',', ' ') }}</span>
                    </div>
                    <div class="person-actions">
                        <button class="btn-small" type="button" onclick="openRhModal('edit-{{ $person->id }}')"><i class="fa fa-edit"></i> Modifier</button>
                        <form method="POST" action="{{ $type === 'teachers' ? route('modules.enseignants.delete', $person->id) : route('modules.staff.delete', $person->id) }}" class="js-confirm-submit" data-confirm-title="Supprimer cet enregistrement ?" data-confirm-text="Cette action est definitive.">
                            @csrf @method('DELETE')
                            <button class="btn-danger" type="submit"><i class="fa fa-trash"></i></button>
                        </form>
                    </div>
                </article>
                <div class="modal-backdrop" id="edit-{{ $person->id }}">
                    <div class="rh-modal">
                        <div class="rh-modal-head"><h2>Modifier {{ $person->nom }} {{ $person->prenom }}</h2><button class="modal-close" type="button" onclick="closeRhModal('edit-{{ $person->id }}')">x</button></div>
                        <form method="POST" enctype="multipart/form-data" action="{{ $type === 'teachers' ? route('modules.enseignants.update', $person->id) : route('modules.staff.update', $person->id) }}">
                                    @csrf @method('PUT')
                                    <div class="rh-grid">
                                        <div><label>Nom</label><input type="text" name="nom" value="{{ $person->nom }}" required></div>
                                        <div><label>Prenom</label><input type="text" name="prenom" value="{{ $person->prenom }}" required></div>
                                        <div><label>Email</label><input type="email" name="email" value="{{ $person->email }}" required></div>
                                        <div><label>Telephone</label><input type="text" name="telephone" value="{{ $person->telephone }}" required></div>
                                        <div><label>Annee</label><input type="text" name="annee_scolaire" value="{{ $person->annee_scolaire }}" required></div>
                                        @if ($type === 'teachers')
                                            <div><label>Matiere</label><select name="matiere_id" required>@foreach($matieres as $matiere)<option value="{{ $matiere->id }}" @selected((int)$person->matiere_id === (int)$matiere->id)>{{ $matiere->nom }}</option>@endforeach</select></div>
                                            <div><label>Salaire horaire</label><input type="number" name="salaire_horaire" value="{{ $person->salaire_horaire }}" min="0" step="1"></div>
                                            <div><label>Autorisation</label><select name="autorisation_enseigner">@foreach(['Non','Oui','En cours'] as $auth)<option @selected($person->autorisation_enseigner === $auth)>{{ $auth }}</option>@endforeach</select></div>
                                        @else
                                            <div><label>Role</label><select name="role_id" required>@foreach($roles as $role)<option value="{{ $role->id }}" @selected((int)$person->role_id === (int)$role->id)>{{ $role->nom }}</option>@endforeach</select></div>
                                            <div><label>Departement</label><select name="departement_id"><option value="">Aucun</option>@foreach($departements as $departement)<option value="{{ $departement->id }}" @selected((int)($person->departement_id ?? 0) === (int)$departement->id)>{{ $departement->nom }}</option>@endforeach</select></div>
                                            <div><label>Salaire base</label><input type="number" name="salaire_base" value="{{ $person->salaire_base ?? 0 }}" min="0" step="1"></div>
                                        @endif
                                        <div><label>Diplome</label><input type="text" name="diplome_pedagogique" value="{{ $person->diplome_pedagogique ?? '' }}"></div>
                                        <div><label>Experience</label><input type="number" name="annees_experience" value="{{ $person->annees_experience ?? 0 }}" min="0"></div>
                                        @if($type === 'teachers')<div><label>Statut</label><select name="statut"><option value="actif" @selected(($person->statut ?? 'actif') === 'actif')>actif</option><option value="inactif" @selected(($person->statut ?? '') === 'inactif')>inactif</option></select></div>@endif
                                        <div><label>Nouvelle photo</label><input type="file" name="photo" accept="image/*"></div>
                                        <div><label>Nouveau mot de passe</label><input type="password" name="mot_de_passe" placeholder="Laisser vide pour garder l'actuel"></div>
                                    </div>
                                    @if ($type === 'teachers')
                                        <label style="margin-top:12px;">Classes</label>
                                        <div class="checks">
                                            @foreach ($classes as $classe)
                                                @php
                                                    $assignment = ($person->classes_assignments ?? collect())->get($classe->id);
                                                @endphp
                                                <div class="class-assignment">
                                                    <input type="checkbox" name="classes_ids[]" value="{{ $classe->id }}" @checked(in_array((int)$classe->id, $person->classes_ids ?? [], true))>
                                                    <div>
                                                        <label>{{ $classe->nom }}</label>
                                                        <select name="classes_modes[{{ $classe->id }}]">
                                                            <option value="fixe" @selected(($assignment->affectation_type ?? 'fixe') === 'fixe')>Classe fixe</option>
                                                            <option value="flexible" @selected(($assignment->affectation_type ?? '') === 'flexible')>Intervention flexible</option>
                                                        </select>
                                                        <input type="text" name="classes_notes[{{ $classe->id }}]" value="{{ $assignment->commentaire ?? '' }}" placeholder="Rubrique, remplacement, groupe temporaire...">
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                            <button class="btn-small" type="submit" style="margin-top:12px;"><i class="fa fa-edit"></i> Modifier</button>
                        </form>
                    </div>
                </div>
            @empty
                <p class="muted">Aucun enregistrement.</p>
            @endforelse
        </div>
    </section>
    <footer>&copy; {{ date('Y') }} {{ $ecole->nom ?? 'Ecole' }}. Tous droits reserves.</footer>
</main>
<script>
function toggleSidebar(){document.getElementById('sidebar').classList.toggle('active');}
function toggleSub(el){const n=el.nextElementSibling;if(n)n.style.display=n.style.display==='none'?'block':'none';}
function toggleFullscreen(){if(!document.fullscreenElement){document.documentElement.requestFullscreen();}else{document.exitFullscreen();}}
function openRhModal(id){document.getElementById(id)?.classList.add('active')}
function closeRhModal(id){document.getElementById(id)?.classList.remove('active')}
function fillRhProfile(fullName,email){
    const form=document.getElementById('addPeopleForm');
    if(!form)return;
    const parts=String(fullName||'').trim().split(/\s+/);
    form.querySelector('[name="prenom"]').value=parts.length>1?parts.pop():'';
    form.querySelector('[name="nom"]').value=parts.join(' ')||fullName;
    form.querySelector('[name="email"]').value=email;
    form.scrollIntoView({behavior:'smooth',block:'start'});
}
document.addEventListener('click',e=>{if(e.target.classList.contains('modal-backdrop'))e.target.classList.remove('active')})
document.addEventListener('keydown',e=>{if(e.key==='Escape')document.querySelectorAll('.modal-backdrop.active').forEach(m=>m.classList.remove('active'))})
</script>
</body>
</html>
