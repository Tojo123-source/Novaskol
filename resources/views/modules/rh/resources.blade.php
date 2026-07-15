<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gestion des ressources - {{ $ecole->nom ?? 'Ecole' }}</title>
    <link rel="stylesheet" href="{{ asset('legacy/assets/fontawesome/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('legacy/fa/css/font-awesome.min.css') }}">
    @include('modules.professeur.bulletin.partials.styles')
    <style>
        .rh-columns { display:grid; grid-template-columns:repeat(auto-fit,minmax(330px,1fr)); gap:18px; }
        .rh-panel { background:var(--card); border:1px solid var(--border); border-radius:8px; padding:18px; margin-bottom:18px; }
        .rh-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(170px,1fr)); gap:12px; align-items:end; }
        input,select,textarea { width:100%; padding:12px; background:var(--surface); color:var(--text); border:1px solid var(--border); border-radius:8px; }
        textarea { min-height:80px; resize:vertical; }
        .rh-table-wrap { overflow:auto; border:1px solid var(--border); border-radius:8px; background:var(--surface); margin-top:14px; }
        .rh-table { width:100%; border-collapse:collapse; min-width:620px; }
        .rh-table th,.rh-table td { padding:12px; border-bottom:1px solid var(--border); text-align:left; vertical-align:top; }
        .rh-table th { background:#0f172a; color:var(--primary); font-size:.82rem; text-transform:uppercase; }
        .btn-danger { background:#dc2626; color:white; border:0; border-radius:8px; padding:9px 12px; cursor:pointer; font-weight:700; }
        .btn-small { background:#2563eb; color:white; border:0; border-radius:8px; padding:9px 12px; cursor:pointer; font-weight:700; }
        .muted { color:var(--text-sec); }
        .alert { padding:12px 14px; border-radius:8px; margin-bottom:14px; border:1px solid var(--border); }
        .alert.success { background:rgba(16,185,129,.12); color:#a7f3d0; }
        .alert.error { background:rgba(239,68,68,.12); color:#fecaca; }
        details summary { cursor:pointer; color:var(--primary); font-weight:700; }
        @media(max-width:1100px){.rh-columns{grid-template-columns:1fr}}@media(max-width:760px){.rh-grid{grid-template-columns:1fr}.rh-table{font-size:.82rem}.rh-table th,.rh-table td{padding:8px 6px}}
    </style>
</head>
<body>
@include('modules.professeur.bulletin.partials.shell')
<header>
    <div class="header-left">
        <button class="burger-menu" onclick="toggleSidebar()"><i class="fa fa-bars"></i></button>
        <button id="fullscreen-btn" onclick="toggleFullscreen()"><i class="fa fa-expand"></i></button>
    </div>
    <div class="header-center">Gestion des ressources</div>
</header>
<main>
    <div class="rh-columns">
        <section class="rh-panel">
            <h2 style="margin-bottom:14px;">Salles</h2>
            <form method="POST" action="{{ route('modules.gestion-ressource.action') }}" class="rh-grid">
                @csrf
                <input type="hidden" name="action" value="add_salle">
                <div><label>Nom salle</label><input type="text" name="nom_salle" required></div>
                <div><label>Capacite</label><input type="number" name="capacite" min="0" required></div>
                <div><label>Description</label><input type="text" name="description_salle"></div>
                <button class="kaly" type="submit"><i class="fa fa-plus"></i> Ajouter</button>
            </form>
            <div class="rh-table-wrap">
                <table class="rh-table">
                    <thead><tr><th>Nom</th><th>Capacite</th><th>Description</th><th>Actions</th></tr></thead>
                    <tbody>
                    @forelse ($salles as $salle)
                        <tr>
                            <td>{{ $salle->nom }}</td>
                            <td>{{ $salle->capacite }}</td>
                            <td>{{ $salle->description }}</td>
                            <td>
                                <form method="POST" action="{{ route('modules.gestion-ressource.action') }}" style="display:inline" class="js-confirm-submit" data-confirm-title="Supprimer cette salle ?" data-confirm-text="La salle sera retiree des ressources.">
                                    @csrf
                                    <input type="hidden" name="action" value="delete_salle">
                                    <input type="hidden" name="id_salle" value="{{ $salle->id }}">
                                    <button class="btn-danger" type="submit"><i class="fa fa-trash"></i></button>
                                </form>
                                <details style="margin-top:8px;">
                                    <summary>Modifier</summary>
                                    <form method="POST" action="{{ route('modules.gestion-ressource.action') }}" class="rh-grid" style="margin-top:8px;">
                                        @csrf
                                        <input type="hidden" name="action" value="edit_salle">
                                        <input type="hidden" name="id_salle" value="{{ $salle->id }}">
                                        <input type="text" name="nom_salle" value="{{ $salle->nom }}" required>
                                        <input type="number" name="capacite" value="{{ $salle->capacite }}" required>
                                        <input type="text" name="description_salle" value="{{ $salle->description }}">
                                        <button class="btn-small" type="submit">OK</button>
                                    </form>
                                </details>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="muted">Aucune salle.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <section class="rh-panel">
            <h2 style="margin-bottom:14px;">Equipements</h2>
            <form method="POST" action="{{ route('modules.gestion-ressource.action') }}" class="rh-grid">
                @csrf
                <input type="hidden" name="action" value="add_equipement">
                <div><label>Nom equipement</label><input type="text" name="nom_equipement" required></div>
                <div><label>Quantite</label><input type="number" name="quantite" min="0" required></div>
                <div><label>Description</label><input type="text" name="description_equipement"></div>
                <button class="kaly" type="submit"><i class="fa fa-plus"></i> Ajouter</button>
            </form>
            <div class="rh-table-wrap">
                <table class="rh-table">
                    <thead><tr><th>Nom</th><th>Quantite</th><th>Description</th><th>Actions</th></tr></thead>
                    <tbody>
                    @forelse ($equipements as $equipement)
                        <tr>
                            <td>{{ $equipement->nom }}</td>
                            <td>{{ $equipement->quantite }}</td>
                            <td>{{ $equipement->description }}</td>
                            <td>
                                <form method="POST" action="{{ route('modules.gestion-ressource.action') }}" style="display:inline" class="js-confirm-submit" data-confirm-title="Supprimer cet equipement ?" data-confirm-text="Cet equipement sera retire des ressources.">
                                    @csrf
                                    <input type="hidden" name="action" value="delete_equipement">
                                    <input type="hidden" name="id_equipement" value="{{ $equipement->id }}">
                                    <button class="btn-danger" type="submit"><i class="fa fa-trash"></i></button>
                                </form>
                                <details style="margin-top:8px;">
                                    <summary>Modifier</summary>
                                    <form method="POST" action="{{ route('modules.gestion-ressource.action') }}" class="rh-grid" style="margin-top:8px;">
                                        @csrf
                                        <input type="hidden" name="action" value="edit_equipement">
                                        <input type="hidden" name="id_equipement" value="{{ $equipement->id }}">
                                        <input type="text" name="nom_equipement" value="{{ $equipement->nom }}" required>
                                        <input type="number" name="quantite" value="{{ $equipement->quantite }}" required>
                                        <input type="text" name="description_equipement" value="{{ $equipement->description }}">
                                        <button class="btn-small" type="submit">OK</button>
                                    </form>
                                </details>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="muted">Aucun equipement.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>

    <section class="rh-panel">
        <h2 style="margin-bottom:14px;">Reservations des salles</h2>
        <form method="GET" class="rh-grid" style="margin-bottom:14px;">
            <div><label>Date</label><input type="date" name="date_reservation" value="{{ $dateReservation }}"></div>
            <button class="kaly" type="submit"><i class="fa fa-calendar"></i> Charger</button>
        </form>
        <form method="POST" action="{{ route('modules.gestion-ressource.action') }}" class="rh-grid">
            @csrf
            <input type="hidden" name="action" value="add_reservation">
            <div>
                <label>Salle</label>
                <select name="id_salle" required>
                    <option value="">Choisir</option>
                    @foreach ($salles as $salle)
                        <option value="{{ $salle->id }}">{{ $salle->nom }}</option>
                    @endforeach
                </select>
            </div>
            <div><label>Date</label><input type="date" name="date_reservation" value="{{ $dateReservation }}" required></div>
            <div><label>Debut</label><input type="time" name="heure_debut" required></div>
            <div><label>Fin</label><input type="time" name="heure_fin" required></div>
            <div>
                <label>Utilisateur</label>
                <select name="utilisateur" required>
                    <option value="">Choisir</option>
                    @foreach ($utilisateurs as $utilisateur)
                        <option value="{{ $utilisateur->id }}">{{ $utilisateur->nom }}</option>
                    @endforeach
                </select>
            </div>
            <div><label>Description</label><input type="text" name="description_reservation"></div>
            <button class="kaly" type="submit"><i class="fa fa-save"></i> Reserver</button>
        </form>
        <div class="rh-table-wrap">
            <table class="rh-table">
                <thead><tr><th>Salle</th><th>Utilisateur</th><th>Horaire</th><th>Description</th><th>Statut</th><th>Actions</th></tr></thead>
                <tbody>
                @forelse ($reservations as $reservation)
                    <tr>
                        <td>{{ $reservation->salle_nom }}</td>
                        <td>{{ $reservation->utilisateur_nom ?? $reservation->utilisateur }}</td>
                        <td>{{ substr($reservation->heure_debut, 0, 5) }} - {{ substr($reservation->heure_fin, 0, 5) }}</td>
                        <td>{{ $reservation->description }}</td>
                        <td>{{ $reservation->statut }}</td>
                        <td>
                            <form method="POST" action="{{ route('modules.gestion-ressource.action') }}" class="js-confirm-submit" data-confirm-title="Annuler cette reservation ?" data-confirm-text="La reservation sera supprimee du planning.">
                                @csrf
                                <input type="hidden" name="action" value="delete_reservation">
                                <input type="hidden" name="id_reservation" value="{{ $reservation->id }}">
                                <input type="hidden" name="date_reservation" value="{{ $dateReservation }}">
                                <button class="btn-danger" type="submit"><i class="fa fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="muted">Aucune reservation pour cette date.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </section>
    <footer>&copy; {{ date('Y') }} {{ $ecole->nom ?? 'Ecole' }}. Tous droits reserves.</footer>
</main>
<script>
function toggleSidebar(){document.getElementById('sidebar').classList.toggle('active');}
function toggleSub(el){const n=el.nextElementSibling;if(n)n.style.display=n.style.display==='none'?'block':'none';}
function toggleFullscreen(){if(!document.fullscreenElement){document.documentElement.requestFullscreen();}else{document.exitFullscreen();}}
</script>
</body>
</html>
