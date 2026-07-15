<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sauvegarde & restauration</title>
    <link rel="stylesheet" href="{{ asset('legacy/assets/fontawesome/css/all.min.css') }}">
    @include('modules.professeur.bulletin.partials.styles')
    @include('modules.parametres.partials.styles')
    <style>
        .backup-hero{display:grid;grid-template-columns:minmax(0,1.4fr) minmax(280px,.9fr);gap:16px;margin-bottom:18px}
        .backup-intro,.backup-kpis{background:var(--card);border:1px solid var(--border);border-radius:8px;padding:18px}
        .backup-intro h2,.backup-kpis h2{margin:0 0 10px;color:var(--primary)}
        .backup-intro p{margin:0 0 10px;color:var(--text-sec);line-height:1.65}
        .backup-note{padding:12px 14px;border-radius:8px;background:rgba(245,158,11,.1);border:1px solid rgba(245,158,11,.35);color:var(--text)}
        .backup-actions{display:flex;flex-wrap:wrap;gap:10px;margin:0 0 14px}
        .backup-summary{display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:12px}
        .backup-kpi{padding:14px;border-radius:8px;background:var(--surface);border:1px solid var(--border)}
        .backup-kpi span{display:block;color:var(--text-sec);margin-bottom:6px}
        .backup-kpi strong{font-size:1.4rem;color:var(--text)}
        .backup-panel{background:var(--card);border:1px solid var(--border);border-radius:8px;padding:18px}
        .backup-panel h3{margin:0 0 12px;color:var(--primary)}
        .settings-actions{display:flex;flex-wrap:wrap;gap:8px}
        .settings-actions form{margin:0}
        .btn-small.restore{background:#0ea5e9;color:#fff}
        .backup-badge{display:inline-flex;align-items:center;gap:6px;padding:5px 10px;border-radius:999px;background:rgba(14,165,233,.12);border:1px solid rgba(14,165,233,.25);color:#7dd3fc;font-size:.82rem;font-weight:800}
        @media(max-width:980px){.backup-hero{grid-template-columns:1fr}}
        @media(max-width:760px){.backup-panel{padding:15px}.backup-intro,.backup-kpis{padding:15px}.backup-summary{grid-template-columns:1fr}.backup-note{font-size:.92rem;line-height:1.55}}
    </style>
</head>
<body>
@include('modules.professeur.bulletin.partials.shell')
<header><div class="header-left"><button class="burger-menu" onclick="toggleSidebar()"><i class="fa fa-bars"></i></button><button id="fullscreen-btn" onclick="toggleFullscreen()"><i class="fa fa-expand"></i></button></div><div class="header-center"><i class="fa fa-database"></i> Sauvegarde & restauration</div></header>
<main>
    <section class="backup-hero">
        <div class="backup-intro">
            <h2>Gerer les versions de la base de donnees</h2>
            <p>Ce module permet de creer une sauvegarde manuelle, telecharger une version, ou revenir directement vers une sauvegarde precise. Avant chaque restauration, Novaskol cree automatiquement une nouvelle sauvegarde de l'etat courant pour eviter toute perte definitive.</p>
            <div class="backup-note"><strong>Conseil :</strong> avant une grosse modification ou avant de restaurer une ancienne version, gardez toujours au moins une sauvegarde recente externe sur cle USB, disque ou cloud de l'ecole.</div>
        </div>
        <div class="backup-kpis">
            <h2>Etat actuel</h2>
            <div class="backup-summary">
                <div class="backup-kpi"><span>Sauvegardes disponibles</span><strong>{{ $backups->count() }}</strong></div>
                <div class="backup-kpi"><span>Derniere creation</span><strong>{{ $backups->first()['date'] ?? '-' }}</strong></div>
            </div>
        </div>
    </section>

    <section class="backup-panel">
        <div class="backup-actions">
            <form method="POST" action="{{ route('modules.sauvegardes.create') }}">@csrf<button class="kaly"><i class="fa fa-plus-circle"></i> Creer une sauvegarde maintenant</button></form>
        </div>

        <h3>Historique des sauvegardes</h3>
        <div class="settings-table-wrap">
            <table class="settings-table">
                <thead><tr><th>Fichier</th><th>Date</th><th>Taille</th><th>Etat</th><th>Actions</th></tr></thead>
                <tbody>
                    @forelse($backups as $backup)
                        <tr>
                            <td data-label="Fichier">{{ $backup['name'] }}</td>
                            <td data-label="Date">{{ $backup['date'] }}</td>
                            <td data-label="Taille">{{ $backup['size'] }}</td>
                            <td data-label="Etat">
                                @if($backup['is_auto_restore'])
                                    <span class="backup-badge"><i class="fa fa-history"></i> Auto avant restauration</span>
                                @else
                                    <span class="backup-badge"><i class="fa fa-save"></i> Sauvegarde normale</span>
                                @endif
                            </td>
                            <td class="settings-actions" data-label="Actions">
                                <form method="POST" action="{{ route('modules.sauvegardes.restore', $backup['name']) }}" data-confirm-title="Restaurer cette base ?" data-confirm-text="Novaskol reviendra a cette sauvegarde. Une sauvegarde automatique de l etat actuel sera creee avant la restauration.">
                                    @csrf
                                    <button class="btn-small restore"><i class="fa fa-history"></i> Revenir a cette base</button>
                                </form>
                                <a class="btn-small download" href="{{ route('modules.sauvegardes.download', $backup['name']) }}"><i class="fa fa-download"></i> Telecharger</a>
                                <form method="POST" action="{{ route('modules.sauvegardes.delete', $backup['name']) }}" class="js-confirm-submit" data-confirm-title="Supprimer cette sauvegarde ?" data-confirm-text="Le fichier de sauvegarde sera supprime.">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn-small delete"><i class="fa fa-trash"></i> Supprimer</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="muted" data-label="Etat">Aucune sauvegarde pour le moment.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
    <footer>&copy; {{ date('Y') }} {{ $ecole->nom ?? 'Ecole' }}. Tous droits reserves.</footer>
</main>
<script>function toggleSidebar(){document.getElementById('sidebar').classList.toggle('active')}function toggleSub(e){const n=e.nextElementSibling;if(n)n.style.display=n.style.display==='none'?'block':'none'}function toggleFullscreen(){document.fullscreenElement?document.exitFullscreen():document.documentElement.requestFullscreen()}</script>
</body>
</html>
