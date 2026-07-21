<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Details Paiement</title>
<link rel="stylesheet" href="{{ asset('legacy/assets/fontawesome/css/all.min.css') }}">
@include('modules.professeur.bulletin.partials.styles')
@include('modules.accounting.partials.styles')
<style>
.month-pills{display:grid;grid-template-columns:repeat(auto-fit,minmax(118px,1fr));gap:8px;background:var(--surface);border:1px solid var(--border);border-radius:8px;padding:10px}.month-pills label{display:flex;align-items:center;gap:8px;margin:0;color:var(--text);font-weight:700;padding:8px;border-radius:7px;background:rgba(255,255,255,.025);cursor:pointer}.month-pills input{width:auto}.unpaid-btn{background:#f59e0b;color:#111827;border:0;border-radius:8px;padding:9px 12px;font-weight:900;cursor:pointer}.modal-backdrop{position:fixed;inset:0;background:rgba(0,0,0,.65);z-index:3900;display:none;align-items:center;justify-content:center;overflow:auto;padding:24px 14px}.modal-backdrop.active{display:flex}.pay-modal{width:min(760px,100%);max-height:calc(100vh - 48px);overflow:hidden;display:flex;flex-direction:column;background:var(--card);border:1px solid var(--border);border-radius:8px;box-shadow:0 28px 80px #000b;padding:18px;color:var(--text)}.pay-modal-head{display:flex;align-items:center;justify-content:space-between;gap:14px;margin-bottom:14px}.pay-modal-head h2{margin:0;color:var(--primary);font-size:1.05rem}.modal-close{width:36px;height:36px;border-radius:999px;border:1px solid var(--border);background:var(--surface);color:var(--text);cursor:pointer;font-weight:900}.unpaid-list{display:grid;gap:9px;max-height:58vh;overflow:auto;padding-right:4px}.unpaid-row{display:flex;align-items:center;justify-content:space-between;gap:12px;padding:11px;border:1px solid var(--border);border-radius:8px;background:var(--surface)}.unpaid-row strong{display:block}.pay-link{background:var(--primary);color:#062b1d;text-decoration:none;border-radius:8px;padding:8px 12px;font-weight:900;white-space:nowrap}.month-list{color:var(--text-sec);font-size:.9rem;line-height:1.45}.reminder-strip{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:12px}.reminder-card{padding:14px;border:1px solid var(--border);border-radius:8px;background:var(--surface)}.reminder-card strong{display:block;font-size:1.35rem;color:var(--primary)}.reminder-card span{color:var(--text-sec);font-size:.9rem}.reminder-list{margin-top:12px;display:grid;gap:8px}.reminder-item{display:flex;justify-content:space-between;gap:10px;padding:10px;border-radius:8px;border:1px solid var(--border);background:rgba(245,158,11,.08)}.reminder-item b{color:#f59e0b}@media(max-width:720px){.modal-backdrop{padding:12px}.pay-modal{max-height:calc(100dvh - 24px);padding:14px}.pay-modal-head{align-items:flex-start}.pay-modal-head h2{font-size:.94rem;line-height:1.35}.unpaid-list{max-height:62dvh}.unpaid-row{align-items:flex-start;flex-direction:column}.pay-link{width:100%;text-align:center}.reminder-strip{grid-template-columns:1fr}.reminder-item{flex-direction:column}}
</style>
</head>
<body>
@include('modules.professeur.bulletin.partials.shell')
<header><div class="header-left"><button class="burger-menu" onclick="toggleSidebar()"><i class="fa fa-bars"></i></button><button id="fullscreen-btn" onclick="toggleFullscreen()"><i class="fa fa-expand"></i></button></div><div class="header-center">Details paiement</div></header>
<main>
<section class="acc-panel no-print">
    <form method="GET" class="acc-grid">
        <div><label>Type</label><select name="type_selection" onchange="this.form.submit()"><option value="etudiant" @selected($selectedType==='etudiant')>Etudiant</option><option value="enseignant" @selected($selectedType==='enseignant')>Enseignant</option><option value="staff" @selected($selectedType==='staff')>Staff</option></select></div>
        <div><label>Annee scolaire</label><select name="annee_scolaire" onchange="this.form.submit()">@foreach($annees as $annee)<option value="{{ $annee }}" @selected($selectedAnnee===$annee)>{{ $annee }}</option>@endforeach</select></div>
        <div><label>Classe</label><select name="classe_id" onchange="this.form.submit()"><option value="0">Toutes</option>@foreach($classes as $classe)<option value="{{ $classe->id }}" @selected($selectedClasse===$classe->id)>{{ $classe->nom }}</option>@endforeach</select></div>
    </form>
</section>
<section class="acc-panel">
    <h2>Rappels automatiques</h2>
    <div class="reminder-strip">
        <div class="reminder-card"><strong>{{ $reminderStats['overdue_count'] ?? 0 }}</strong><span>paiements en retard</span></div>
        <div class="reminder-card"><strong>{{ $reminderStats['soon_count'] ?? 0 }}</strong><span>paiements a echeance proche</span></div>
        <div class="reminder-card"><strong>{{ $createdReminders ?? 0 }}</strong><span>notification(s) creee(s) aujourd'hui</span></div>
    </div>
    <div class="reminder-list">
        @foreach(array_slice($reminderStats['overdue'] ?? [], 0, 4) as $r)
            <div class="reminder-item"><span><b>En retard</b> - {{ $r['nom'] }} {{ $r['classe'] ? '('.$r['classe'].')' : '' }}</span><span>{{ $r['unpaid'] }} non payes</span></div>
        @endforeach
        @foreach(array_slice($reminderStats['soon'] ?? [], 0, 3) as $r)
            <div class="reminder-item"><span><b>Bientot</b> - {{ $r['nom'] }} {{ $r['classe'] ? '('.$r['classe'].')' : '' }}</span><span>{{ $r['unpaid'] }} non payes</span></div>
        @endforeach
    </div>
</section>
<section class="acc-panel no-print">
    <h2>Ajouter type paiement</h2>
    <form method="POST" action="{{ route('modules.detail-paiement.type.store') }}" class="acc-grid">@csrf
        <input type="hidden" name="type_selection" value="{{ $selectedType }}">
        <div><label>Nom</label><input name="nom_type" required></div>
        <div><label>Montant</label><input type="number" step="0.01" name="montant" required></div>
        <div style="grid-column:1/-1"><label>Mois concernes</label><div class="month-pills">@foreach($months as $m)<label><input type="checkbox" name="mois[]" value="{{ $m }}"> {{ $m }}</label>@endforeach</div></div>
        @if($selectedType === 'etudiant')<div><label>Classe</label><select name="classe" required>@foreach($classes as $classe)<option value="{{ $classe->id }}">{{ $classe->nom }}</option>@endforeach</select></div>@endif
        <div><label>Annee</label><input name="annee_scolaire" value="{{ $selectedAnnee }}" required></div>
        <div><label>Date debut</label><input type="date" name="date_debut" required></div>
        <div><label>Date fin</label><input type="date" name="date_fin" required></div>
        <button class="kaly" type="submit">Ajouter</button>
    </form>
</section>
<section class="acc-panel">
    <h2>Types de paiement</h2>
    <div class="acc-table-wrap"><table class="acc-table"><thead><tr><th>Nom</th><th>{{ $selectedType==='etudiant' ? 'Classe' : 'Type' }}</th><th>Mois</th><th>Montant</th><th>Complet</th><th>Partiel</th><th>Non paye</th><th>Action</th></tr></thead><tbody>
    @forelse($studentTypes as $t)
        <tr>
            <td>{{ $t->nom }}</td>
            <td>{{ $t->classe }}</td>
            <td class="month-list">{{ implode(', ', json_decode($t->mois ?: '[]', true) ?: []) }}</td>
            <td class="money">{{ number_format((float)$t->montant,0,',',' ') }} {{ novaskol_currency() }}</td>
            <td>{{ $t->total_complet }}</td>
            <td>{{ $t->total_partiel }}</td>
            <td><button class="unpaid-btn" type="button" onclick="openPayModal('unpaid-type-{{ $t->id }}')">{{ $t->total_non_paye + $t->total_partiel }} a regulariser</button></td>
<td><form method="POST" action="{{ route('modules.detail-paiement.type.delete',$t->id) }}" class="js-confirm-submit" data-confirm-title="Supprimer ce type ?" data-confirm-text="Ce type de paiement sera retire.">@csrf @method('DELETE')<button class="danger-btn">Supprimer</button></form></td>
        </tr>
    @empty
        <tr><td colspan="8" class="muted">Aucun type.</td></tr>
    @endforelse
    </tbody></table></div>
</section>
@foreach($studentTypes as $t)
    <div class="modal-backdrop" id="unpaid-type-{{ $t->id }}">
        <div class="pay-modal">
            <div class="pay-modal-head">
                <h2>{{ $t->nom }} - {{ $t->total_complet }} complet / {{ $t->total_partiel }} partiel / {{ $t->total_non_paye }} non paye</h2>
                <button class="modal-close" type="button" onclick="closePayModal('unpaid-type-{{ $t->id }}')">x</button>
            </div>
            <div class="unpaid-list">
                @forelse($t->unpaid as $u)
                    <div class="unpaid-row">
                        <span>
                            <strong>{{ $u->nom }} {{ $u->prenom }}</strong>
                            <small class="muted">
                                @if($selectedType==='etudiant')
                                    {{ $u->classe }} -
                                @endif
                                {{ $u->payment_status === 'partiel' ? 'Partiel' : 'Non paye' }}
                                @if($u->montant_restant > 0)
                                    - Reste {{ number_format((float)$u->montant_restant,0,',',' ') }} {{ novaskol_currency() }}
                                @endif
                            </small>
                        </span>
                        @if($selectedType==='etudiant')
                            <a class="pay-link" href="{{ route('modules.comptable',['kind'=>'ecolage','eleve_id'=>$u->id,'type_id'=>$t->id,'annee_scolaire'=>$selectedAnnee]) }}">Payer</a>
                        @else
                            <a class="pay-link" href="{{ route('modules.comptable',['kind'=>'type_paiement','personne_id'=>$u->id,'type_personne'=>$selectedType==='enseignant'?'professeur':'staff','type_id'=>$t->id,'montant'=>$u->montant_restant,'annee_scolaire'=>$selectedAnnee]) }}">Payer</a>
                        @endif
                    </div>
                @empty
                    <div class="muted">Tout le monde a paye pour ce type.</div>
                @endforelse
            </div>
        </div>
    </div>
@endforeach
@if(in_array($selectedType, ['enseignant', 'staff'], true))
<section class="acc-panel no-print">
    <h2>Assigner mois salaires</h2>
    <form method="POST" action="{{ route('modules.detail-paiement.salaires') }}" class="acc-grid">@csrf
        <input type="hidden" name="type_selection" value="{{ $selectedType }}">
        <div style="grid-column:1/-1"><label>Mois concernes</label><div class="month-pills">@foreach($months as $m)<label><input type="checkbox" name="mois[]" value="{{ $m }}"> {{ $m }}</label>@endforeach</div></div>
        <div><label>Annee</label><input name="annee_scolaire" value="{{ $selectedAnnee }}" required></div>
        <button class="kaly">Assigner</button>
    </form>
</section>
<section class="acc-panel">
    <h2>Mois assignes</h2>
    <div class="acc-table-wrap"><table class="acc-table"><thead><tr><th>Mois</th><th>Complet</th><th>Partiel</th><th>Non paye</th></tr></thead><tbody>
    @forelse($salaryMonths as $m)
        <tr><td>{{ $m->mois }}</td><td>{{ $m->total_complet }}</td><td>{{ $m->total_partiel }}</td><td><button class="unpaid-btn" type="button" onclick="openPayModal('unpaid-salary-{{ \Illuminate\Support\Str::slug($m->mois) }}')">{{ $m->total_non_paye + $m->total_partiel }} a regulariser</button></td></tr>
    @empty
        <tr><td colspan="4" class="muted">Aucun mois.</td></tr>
    @endforelse
    </tbody></table></div>
</section>
@foreach($salaryMonths as $m)
    <div class="modal-backdrop" id="unpaid-salary-{{ \Illuminate\Support\Str::slug($m->mois) }}">
        <div class="pay-modal">
            <div class="pay-modal-head">
                <h2>{{ $m->mois }} - {{ $m->total_complet }} complet / {{ $m->total_partiel }} partiel / {{ $m->total_non_paye }} non paye</h2>
                <button class="modal-close" type="button" onclick="closePayModal('unpaid-salary-{{ \Illuminate\Support\Str::slug($m->mois) }}')">x</button>
            </div>
            <div class="unpaid-list">
                @forelse($m->unpaid as $u)
                    <div class="unpaid-row">
                        <span>
                            <strong>{{ $u->nom }} {{ $u->prenom }}</strong>
                            <small class="muted">
                                {{ $u->payment_status === 'partiel' ? 'Partiel' : 'Non paye' }}
                                @if($u->montant_restant > 0)
                                    - Reste {{ number_format((float)$u->montant_restant,0,',',' ') }} {{ novaskol_currency() }}
                                @endif
                            </small>
                        </span>
                        <a class="pay-link" href="{{ route('modules.comptable',['kind'=>'salaire','personne_id'=>$u->id,'type_personne'=>$selectedType==='enseignant'?'professeur':'staff','mois'=>$m->mois,'annee_scolaire'=>$selectedAnnee]) }}">Payer</a>
                    </div>
                @empty
                    <div class="muted">Tout le monde a ete paye pour ce mois.</div>
                @endforelse
            </div>
        </div>
    </div>
@endforeach
@endif
<footer>&copy; {{ date('Y') }} {{ $ecole->nom ?? 'Ecole' }}</footer>
</main>
<script>
function toggleSidebar(){document.getElementById('sidebar').classList.toggle('active')}
function toggleSub(e){const n=e.nextElementSibling;if(n)n.style.display=n.style.display==='none'?'block':'none'}
function toggleFullscreen(){document.fullscreenElement?document.exitFullscreen():document.documentElement.requestFullscreen()}
function openPayModal(id){document.getElementById(id)?.classList.add('active')}
function closePayModal(id){document.getElementById(id)?.classList.remove('active')}
document.addEventListener('click',e=>{if(e.target.classList.contains('modal-backdrop'))e.target.classList.remove('active')})
document.addEventListener('keydown',e=>{if(e.key==='Escape')document.querySelectorAll('.modal-backdrop.active').forEach(m=>m.classList.remove('active'))})
</script>
</body>
</html>
