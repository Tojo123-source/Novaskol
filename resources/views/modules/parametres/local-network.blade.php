<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Reseau local - {{ $ecole->nom ?? 'Novaskol' }}</title>
    <link rel="stylesheet" href="{{ asset('legacy/assets/fontawesome/css/all.min.css') }}">
    @include('modules.professeur.bulletin.partials.styles')
    @include('modules.parametres.partials.styles')
    <style>
        html,body{max-width:100%;overflow-x:hidden}
        main{max-width:100%;overflow-x:hidden}
        .network-hero,.network-grid,.network-follow,.network-follow *,.network-help{box-sizing:border-box;min-width:0}
        .network-hero{display:grid;grid-template-columns:minmax(0,1.35fr) minmax(300px,.95fr);gap:16px;margin-bottom:18px}
        .network-panel,.network-card,.network-qr,.network-help,.network-follow{background:var(--card);border:1px solid var(--border);border-radius:8px}
        .network-panel,.network-qr,.network-help{padding:18px}
        .network-panel h2,.network-card h3,.network-qr h3,.network-help h3,.network-follow h2,.network-follow h3{margin:0 0 10px;color:var(--primary)}
        .network-panel p,.network-help p,.network-help li,.network-card p{color:var(--text-sec);line-height:1.65}
        .network-status{display:inline-flex;align-items:center;gap:8px;padding:8px 12px;border-radius:999px;font-weight:800;font-size:.84rem;margin-top:10px}
        .network-status.ready{background:rgba(0,200,83,.14);border:1px solid rgba(0,200,83,.35);color:#86efac}
        .network-status.waiting{background:rgba(245,158,11,.12);border:1px solid rgba(245,158,11,.35);color:#facc15}
        .network-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(230px,1fr));gap:16px;margin-bottom:18px}
        .network-card{padding:16px}
        .network-card strong{display:block;color:var(--text);font-size:1.15rem;margin-bottom:6px}
        .network-card code{display:block;background:var(--surface);border:1px solid var(--border);border-radius:8px;padding:12px;color:var(--text);font-size:.98rem;word-break:break-all}
        .network-actions{display:flex;gap:10px;flex-wrap:wrap;margin-top:12px}
        .network-copy{border:0;border-radius:8px;padding:11px 14px;background:#0ea5e9;color:#fff;font-weight:800;cursor:pointer}
        .network-copy.secondary{background:var(--surface);color:var(--text);border:1px solid var(--border)}
        .network-qr{display:grid;place-items:center;text-align:center;min-height:100%}
        .network-qr-box{display:grid;place-items:center;width:220px;height:220px;border-radius:16px;background:#fff;padding:12px;margin:8px auto 14px;border:1px solid rgba(15,23,42,.08)}
        .network-qr small{display:block;color:var(--text-sec);max-width:280px;line-height:1.55}
        .network-help ol,.network-help ul{margin:0;padding-left:18px}
        .network-help li{margin-bottom:8px}
        .network-form{display:grid;grid-template-columns:minmax(0,1fr) auto;gap:12px;align-items:end;margin-top:14px}
        .network-form .field{margin:0}
        .network-note{margin-top:14px;padding:12px 14px;border-radius:8px;background:rgba(14,165,233,.1);border:1px solid rgba(14,165,233,.28);color:var(--text)}
        .network-warning{margin-top:12px;padding:12px 14px;border-radius:8px;background:rgba(245,158,11,.12);border:1px solid rgba(245,158,11,.32);color:var(--text);line-height:1.55}
        .network-warning strong{display:block;color:#facc15;margin-bottom:4px}
        .network-follow{padding:18px;margin:18px 0}
        .network-follow-head{display:flex;align-items:flex-start;justify-content:space-between;gap:14px;margin-bottom:16px}
        .network-follow-head p{margin:0;color:var(--text-sec);line-height:1.55;max-width:780px}
        .network-kpis{display:grid;grid-template-columns:repeat(auto-fit,minmax(150px,1fr));gap:12px;margin-bottom:16px}
        .network-kpi{background:var(--surface);border:1px solid var(--border);border-radius:8px;padding:14px}
        .network-kpi span{display:block;color:var(--text-sec);font-size:.82rem;font-weight:800;margin-bottom:6px}
        .network-kpi strong{display:block;color:var(--text);font-size:1.55rem;line-height:1;word-break:break-word}
        .network-follow-grid{display:grid;grid-template-columns:minmax(0,1.05fr) minmax(0,.95fr);gap:14px}
        .network-list{background:var(--surface);border:1px solid var(--border);border-radius:8px;overflow:hidden}
        .network-list-title{display:flex;align-items:center;justify-content:space-between;gap:10px;padding:12px 14px;border-bottom:1px solid var(--border);font-weight:900;color:var(--text)}
        .network-row{display:grid;grid-template-columns:minmax(0,1fr) auto;gap:12px;align-items:center;padding:12px 14px;border-bottom:1px solid var(--border)}
        .network-row:last-child{border-bottom:0}
        .network-row strong{display:block;color:var(--text);font-size:.94rem;line-height:1.25;word-break:break-word}
        .network-row small{display:block;color:var(--text-sec);line-height:1.35;margin-top:3px;word-break:break-word}
        .network-pill{display:inline-flex;align-items:center;gap:6px;white-space:nowrap;border-radius:999px;padding:6px 9px;font-size:.76rem;font-weight:900}
        .network-pill.ok{background:rgba(0,200,83,.13);color:#86efac;border:1px solid rgba(0,200,83,.28)}
        .network-pill.wait{background:rgba(245,158,11,.13);color:#facc15;border:1px solid rgba(245,158,11,.28)}
        .network-pill.bad{background:rgba(239,68,68,.13);color:#fca5a5;border:1px solid rgba(239,68,68,.28)}
        .network-empty{padding:14px;color:var(--text-sec);line-height:1.5}
        .pairing-card{display:grid;grid-template-columns:minmax(0,1fr) auto;gap:12px;align-items:center;background:linear-gradient(135deg,rgba(14,165,233,.12),rgba(0,200,83,.08)),var(--surface);border:1px solid var(--border);border-radius:8px;padding:14px;margin-bottom:16px}
        .pairing-card h3{margin:0 0 6px;color:var(--text)}
        .pairing-card p{margin:0;color:var(--text-sec);line-height:1.45}
        .pairing-code{display:inline-flex;align-items:center;justify-content:center;min-width:126px;border-radius:8px;border:1px dashed rgba(14,165,233,.55);background:rgba(14,165,233,.1);padding:10px 12px;color:var(--text);font-weight:900;letter-spacing:.08em}
        .sync-action-card{display:grid;grid-template-columns:minmax(0,1fr) auto;gap:12px;align-items:center;background:var(--surface);border:1px solid var(--border);border-radius:8px;padding:14px;margin-bottom:16px}
        .sync-action-card h3{margin:0 0 6px;color:var(--text)}
        .sync-action-card p{margin:0;color:var(--text-sec);line-height:1.45}
        .sync-flow{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:10px;margin-bottom:16px}
        .sync-flow-step{background:var(--surface);border:1px solid var(--border);border-radius:8px;padding:12px}
        .sync-flow-step b{display:inline-grid;place-items:center;width:28px;height:28px;border-radius:999px;background:rgba(14,165,233,.14);color:var(--primary);margin-bottom:8px}
        .sync-flow-step strong{display:block;color:var(--text);font-size:.9rem;margin-bottom:5px}
        .sync-flow-step span{display:block;color:var(--text-sec);font-size:.82rem;line-height:1.35}
        .network-device-actions{grid-column:1/-1;display:flex;gap:7px;justify-content:flex-end;flex-wrap:wrap}
        .network-mini-btn{border:1px solid var(--border);background:var(--card);color:var(--text);border-radius:8px;padding:7px 9px;font-size:.78rem;font-weight:900;cursor:pointer}
        .network-mini-btn:hover{border-color:var(--primary);color:var(--primary)}
        .network-mini-btn.danger:hover{border-color:#ef4444;color:#fca5a5}
        .device-name-form{grid-column:1/-1;display:grid;grid-template-columns:minmax(0,1fr) auto;gap:8px;margin-top:4px}
        .device-name-form input{min-width:0;padding:9px 10px;border-radius:8px;border:1px solid var(--border);background:var(--card);color:var(--text)}
        @media(max-width:980px){
            .network-hero{grid-template-columns:1fr}
            .network-form{grid-template-columns:1fr}
            .network-kpis{grid-template-columns:repeat(2,minmax(0,1fr))}
            .network-follow-grid{grid-template-columns:1fr}
        }
        @media(max-width:760px){
            main{padding-left:12px!important;padding-right:12px!important;overflow-x:hidden}
            header h1,.header-center{font-size:1.05rem;line-height:1.2;text-align:center}
            .form-container{padding:0!important}
            .network-hero{gap:12px;margin-bottom:12px}
            .network-panel,.network-card,.network-qr,.network-help,.network-follow{padding:14px;border-radius:10px}
            .network-panel h2,.network-card h3,.network-qr h3,.network-help h3,.network-follow h2,.network-follow h3{font-size:1rem;line-height:1.25}
            .network-panel p,.network-help p,.network-help li,.network-card p{font-size:.9rem;line-height:1.5}
            .network-status{width:100%;justify-content:center;border-radius:8px}
            .network-note{font-size:.88rem;line-height:1.45}
            .network-grid{grid-template-columns:1fr;gap:10px;margin-bottom:12px}
            .network-card strong{font-size:1rem}
            .network-card code{font-size:.82rem;padding:10px;line-height:1.45}
            .network-actions{display:grid;grid-template-columns:1fr;gap:8px}
            .network-copy,.network-actions a{width:100%;text-align:center;justify-content:center}
            .network-qr{min-height:0}
            .network-qr-box{width:min(190px,72vw);height:min(190px,72vw);border-radius:12px;padding:10px}
            .network-qr-box canvas,.network-qr-box img{max-width:100%!important;height:auto!important}
            .network-help ol,.network-help ul{padding-left:16px}
            .network-follow{margin:12px 0}
            .network-follow-head{display:block}
            .network-follow-head p{font-size:.88rem;margin-top:6px}
            .network-kpis{grid-template-columns:repeat(2,minmax(0,1fr));gap:8px}
            .network-kpi{padding:11px}
            .network-kpi span{font-size:.72rem}
            .network-kpi strong{font-size:1.2rem}
            .network-list-title{padding:11px 12px;font-size:.9rem}
            .network-row{grid-template-columns:1fr;gap:8px;padding:11px 12px}
            .network-pill{width:max-content;max-width:100%;white-space:normal;line-height:1.2}
            .pairing-card{grid-template-columns:1fr;padding:12px}
            .pairing-code{width:100%;min-width:0}
            .sync-action-card{grid-template-columns:1fr;padding:12px}
            .sync-action-card button{width:100%}
            .sync-flow{grid-template-columns:1fr;gap:8px}
            .sync-flow-step{padding:11px}
            .network-device-actions{justify-content:flex-start}
            .network-device-actions form,.network-device-actions button{min-width:0}
            .device-name-form{grid-template-columns:1fr}
            .device-name-form button{width:100%}
        }
        @media(max-width:380px){.network-panel,.network-card,.network-qr,.network-help,.network-follow{padding:12px}.network-kpis{grid-template-columns:1fr}}
    </style>
</head>
<body>
@include('modules.professeur.bulletin.partials.shell')
<header>
    <div class="header-left">
        <button class="burger-menu" onclick="toggleSidebar()"><i class="fa fa-bars"></i></button>
        <button id="fullscreen-btn" onclick="toggleFullscreen()"><i class="fa fa-expand"></i></button>
    </div>
    <div class="header-center"><i class="fa fa-wifi"></i> Reseau local</div>
</header>
<main>
    <section class="network-hero">
        <div class="network-panel">
            <h2>Partager Novaskol sur le meme Wi-Fi</h2>
            <p>Choisissez un seul appareil principal dans l'ecole. C'est cet appareil qui garde la base active de Novaskol. Les autres PC, tablettes ou telephones ouvrent simplement l'adresse locale affichee ci-dessous, a condition d'etre connectes au meme reseau local.</p>
            <div class="network-status {{ $networkReady ? 'ready' : 'waiting' }}">
                <i class="fa {{ $networkReady ? 'fa-check-circle' : 'fa-exclamation-triangle' }}"></i>
                {{ $networkReady ? 'Pret a partager' : 'A verifier' }}
            </div>
            <div class="network-note">
                <strong>Important :</strong> l'appareil principal doit rester allume pendant l'utilisation des autres appareils.
            </div>
            <form method="POST" action="{{ route('modules.reseau-local.save') }}" class="network-form">
                @csrf
                <div class="field">
                    <label>Nom de l'appareil principal</label>
                    <input name="appareil_principal_nom" value="{{ old('appareil_principal_nom', $deviceName) }}" placeholder="Ex: Bureau administration">
                </div>
                <button class="kaly" type="submit"><i class="fa fa-save"></i> Enregistrer</button>
            </form>
        </div>

        <aside class="network-qr">
            <h3>Scanner l'application connectee</h3>
            <div id="local-network-qr" class="network-qr-box">
                <span class="muted">{{ $connectedUrl ? 'Generation du QR code...' : 'Adresse locale indisponible.' }}</span>
            </div>
            <small>Le telephone ou le portable doit etre connecte au meme Wi-Fi que l'appareil principal pour ouvrir Novaskol Connecte.</small>
        </aside>
    </section>

    <section class="network-grid">
        <article class="network-card">
            <h3>Appareil principal</h3>
            <strong>{{ $deviceName }}</strong>
            <p>Nom visible pour l'equipe de l'ecole.</p>
        </article>
        <article class="network-card">
            <h3>Nom technique</h3>
            <strong>{{ $hostname }}</strong>
            <p>Nom Windows detecte automatiquement.</p>
        </article>
        <article class="network-card">
            <h3>Adresse locale</h3>
            @if($localUrl)
                <code id="local-network-url">{{ $localUrl }}</code>
                <div class="network-actions">
                    <button class="network-copy" type="button" onclick="copyLocalUrl()"><i class="fa fa-copy"></i> Copier l'adresse</button>
                    <a class="network-copy secondary" href="{{ $localUrl }}" target="_blank" rel="noopener"><i class="fa fa-external-link"></i> Tester l'ouverture</a>
                </div>
            @else
                <strong>Adresse non detectee</strong>
                <p>Novaskol n'a pas trouve d'adresse locale partageable sur ce PC.</p>
            @endif
        </article>
        <article class="network-card">
            <h3>Application connectee</h3>
            @if($connectedUrl)
                <code id="connected-app-url">{{ $connectedUrl }}</code>
                <div class="network-actions">
                    <button class="network-copy" type="button" onclick="copyConnectedUrl()"><i class="fa fa-copy"></i> Copier le lien</button>
                    <a class="network-copy secondary" href="{{ $connectedUrl }}" target="_blank" rel="noopener"><i class="fa fa-mobile"></i> Ouvrir</a>
                </div>
            @else
                <strong>Indisponible</strong>
                <p>L'adresse locale doit etre detectee avant d'ouvrir l'application connectee.</p>
            @endif
        </article>
        <article class="network-card">
            <h3>Port local</h3>
            <strong>{{ $port }}</strong>
            <p>Les autres appareils utilisent ce meme port dans l'adresse locale.</p>
        </article>
        <article class="network-card">
            <h3>Regle simple</h3>
            <strong>Meme Wi-Fi</strong>
            <p>Sans internet, Novaskol reste accessible aux appareils connectes au reseau local de l'ecole.</p>
        </article>
        <article class="network-card">
            <h3>Pare-feu Windows</h3>
            <strong>{{ ($firewallStatus['configured'] ?? false) ? 'Autorise' : 'A autoriser' }}</strong>
            <p>{{ $firewallStatus['message'] ?? 'Autorisez Novaskol pour les appareils du meme Wi-Fi.' }}</p>
            @if(!($firewallStatus['configured'] ?? false) && ($firewallStatus['windows'] ?? false))
                <div class="network-warning">
                    <strong>Si le telephone affiche "Impossible de joindre l'appareil principal"</strong>
                    Ouvrez ce fichier en administrateur sur le PC principal :
                    <code>{{ $firewallStatus['command'] ?? 'tools\\windows\\Autoriser-Reseau-Novaskol.cmd' }}</code>
                </div>
            @endif
        </article>
    </section>

    <section class="network-follow">
        <div class="network-follow-head">
            <div>
                <h2>Suivi du reseau local</h2>
                <p>Cette zone aide l'administration a verifier les appareils autorises, les derniers echanges et les points a traiter avant une utilisation multi-appareils.</p>
            </div>
            <span class="network-pill {{ ($networkStats['open_conflicts'] ?? 0) > 0 ? 'wait' : 'ok' }}">
                <i class="fa {{ ($networkStats['open_conflicts'] ?? 0) > 0 ? 'fa-warning' : 'fa-check-circle' }}"></i>
                {{ ($networkStats['open_conflicts'] ?? 0) > 0 ? 'Verification requise' : 'Situation stable' }}
            </span>
        </div>

        <div class="network-kpis">
            <div class="network-kpi"><span>Appareils connus</span><strong>{{ $networkStats['devices'] ?? 0 }}</strong></div>
            <div class="network-kpi"><span>Appareils autorises</span><strong>{{ $networkStats['trusted_devices'] ?? 0 }}</strong></div>
            <div class="network-kpi"><span>Donnees reperees</span><strong>{{ $networkStats['known_records'] ?? 0 }}</strong></div>
            <div class="network-kpi"><span>Points a verifier</span><strong>{{ $networkStats['open_conflicts'] ?? 0 }}</strong></div>
        </div>

        <div class="pairing-card">
            <div>
                <h3><i class="fa fa-key"></i> Appairage d'un appareil</h3>
                <p>Generez un code temporaire pour connecter l'application Novaskol d'un enseignant, d'un staff, d'un parent ou d'un autre PC a cette ecole.</p>
                @if($pairingCode)
                    <small class="muted">Expire le {{ $pairingExpires->format('d/m/Y H:i') }}.</small>
                @endif
            </div>
            <div>
                @if($pairingCode)
                    <div class="pairing-code">{{ $pairingCode }}</div>
                    <form method="POST" action="{{ route('modules.reseau-local.pairing') }}" style="margin-top:8px">
                        @csrf
                        <button class="network-mini-btn" type="submit"><i class="fa fa-refresh"></i> Renouveler</button>
                    </form>
                @else
                    <form method="POST" action="{{ route('modules.reseau-local.pairing') }}">
                        @csrf
                        <button class="network-copy" type="submit"><i class="fa fa-key"></i> Generer un code</button>
                    </form>
                @endif
            </div>
        </div>

        <div class="sync-flow">
            <div class="sync-flow-step"><b>1</b><strong>Code</strong><span>L'administration genere le code depuis cet appareil principal.</span></div>
            <div class="sync-flow-step"><b>2</b><strong>Compte</strong><span>L'utilisateur entre son email et son mot de passe Novaskol.</span></div>
            <div class="sync-flow-step"><b>3</b><strong>Role</strong><span>Novaskol renvoie automatiquement son role et ses permissions.</span></div>
            <div class="sync-flow-step"><b>4</b><strong>Donnees</strong><span>L'appareil recoit seulement les donnees autorisees pour ce compte.</span></div>
        </div>

        <div class="sync-action-card">
            <div>
                <h3><i class="fa fa-archive"></i> Preparer un lot local</h3>
                <p>Cette action cree un apercu securise des donnees locales. Elle ne modifie aucune donnee et servira a la future synchronisation hors connexion.</p>
            </div>
            <form method="POST" action="{{ route('modules.reseau-local.batches.create') }}">
                @csrf
                <button class="network-copy" type="submit"><i class="fa fa-refresh"></i> Preparer</button>
            </form>
        </div>

        <div class="network-follow-grid">
            <div class="network-list">
                <div class="network-list-title">
                    <span><i class="fa fa-laptop"></i> Appareils recents</span>
                    <small class="muted">{{ $recentDevices->count() }} affiche(s)</small>
                </div>
                @forelse($recentDevices as $device)
                    <div class="network-row">
                        <span>
                            <strong>{{ $device->nom }}</strong>
                            <small>{{ $device->plateforme ?? 'Plateforme non renseignee' }} @if($device->adresse_ip)- {{ $device->adresse_ip }}@endif</small>
                            @if(!empty($device->utilisateur_role))
                                <small>Compte lie : {{ ucfirst($device->utilisateur_role) }} @if(!empty($device->utilisateur_id))#{{ $device->utilisateur_id }}@endif</small>
                            @endif
                            @if(!empty($device->last_bootstrap_at))
                                <small>Dernier paquet envoye : {{ \Carbon\Carbon::parse($device->last_bootstrap_at)->format('d/m/Y H:i') }}</small>
                            @endif
                            <small>Dernier contact : {{ $device->dernier_contact_at ? \Carbon\Carbon::parse($device->dernier_contact_at)->format('d/m/Y H:i') : 'Non encore detecte' }}</small>
                        </span>
                        <span class="network-pill {{ $device->autorise ? 'ok' : 'wait' }}">
                            <i class="fa {{ $device->autorise ? 'fa-check' : 'fa-clock-o' }}"></i>
                            {{ $device->autorise ? 'Autorise' : 'En attente' }}
                        </span>
                        @if(session('utilisateur.role') === 'admin' && $device->uuid !== ($currentDevice->uuid ?? null))
                            <form class="device-name-form" method="POST" action="{{ route('modules.reseau-local.devices.rename', $device->id) }}">
                                @csrf
                                @method('PUT')
                                <input name="nom" value="{{ $device->nom }}" maxlength="160" placeholder="Nom de l'appareil">
                                <button class="network-mini-btn" type="submit">Renommer</button>
                            </form>
                            <div class="network-device-actions">
                                <form method="POST" action="{{ route('modules.reseau-local.devices.toggle', $device->id) }}">
                                    @csrf
                                    <button class="network-mini-btn" type="submit">{{ $device->autorise ? 'Revoquer' : 'Autoriser' }}</button>
                                </form>
                                <form method="POST" action="{{ route('modules.reseau-local.devices.delete', $device->id) }}" onsubmit="return confirmLocalDeviceDelete(event)">
                                    @csrf
                                    @method('DELETE')
                                    <button class="network-mini-btn danger" type="submit">Supprimer</button>
                                </form>
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="network-empty">Aucun appareil n'a encore ete detecte.</div>
                @endforelse
            </div>

            <div class="network-list">
                <div class="network-list-title">
                    <span><i class="fa fa-exchange"></i> Derniers echanges</span>
                    <small class="muted">{{ $recentBatches->count() }} affiche(s)</small>
                </div>
                @forelse($recentBatches as $batch)
                    <div class="network-row">
                        <span>
                            <strong>{{ ucfirst(str_replace('_', ' ', $batch->statut)) }}</strong>
                            <small>{{ ucfirst($batch->direction) }} - {{ (int) $batch->total_appliques }}/{{ (int) $batch->total_changements }} element(s)</small>
                            <small>{{ $batch->created_at ? \Carbon\Carbon::parse($batch->created_at)->format('d/m/Y H:i') : 'Date non renseignee' }}</small>
                        </span>
                        <span class="network-pill {{ $batch->total_conflits > 0 ? 'wait' : ($batch->statut === 'termine' ? 'ok' : 'wait') }}">
                            <i class="fa {{ $batch->total_conflits > 0 ? 'fa-warning' : 'fa-refresh' }}"></i>
                            {{ $batch->total_conflits > 0 ? $batch->total_conflits.' conflit(s)' : ucfirst($batch->statut) }}
                        </span>
                    </div>
                @empty
                    <div class="network-empty">Aucun echange n'a encore ete enregistre.</div>
                @endforelse
            </div>
        </div>

        @if($recentConflicts->count())
            <div class="network-list" style="margin-top:14px">
                <div class="network-list-title">
                    <span><i class="fa fa-warning"></i> Points a verifier</span>
                    <small class="muted">{{ $recentConflicts->count() }} affiche(s)</small>
                </div>
                @foreach($recentConflicts as $conflict)
                    <div class="network-row">
                        <span>
                            <strong>{{ $conflict->table_name }} #{{ $conflict->record_uuid }}</strong>
                            <small>{{ ucfirst(str_replace('_', ' ', $conflict->type_conflit)) }}</small>
                        </span>
                        <span class="network-pill wait"><i class="fa fa-clock-o"></i> A traiter</span>
                    </div>
                @endforeach
            </div>
        @endif
    </section>

    <section class="network-help">
        <h3>Connecter un autre appareil</h3>
        <ol>
            <li>Ouvrir Novaskol sur l'appareil principal de l'ecole.</li>
            <li>Connecter l'autre appareil au meme Wi-Fi, ou au meme routeur local.</li>
            <li>Ouvrir l'adresse locale affichee plus haut, ou scanner le QR code.</li>
            <li>Se connecter avec un compte autorise de l'ecole.</li>
            <li>Une fois l'appareil valide, Novaskol Connecte conserve l'espace du compte et peut garder des actions hors connexion.</li>
            <li>Au retour sur le meme Wi-Fi, utiliser Synchroniser maintenant pour envoyer les actions locales et recevoir les dernieres permissions.</li>
        </ol>
        <h3 style="margin-top:18px">Si un appareil n'arrive pas a se connecter</h3>
        <ul>
            <li>Verifier que l'appareil principal est toujours allume.</li>
            <li>Verifier que les deux appareils sont sur le meme reseau local.</li>
            <li>Autoriser Novaskol dans le pare-feu Windows avec <strong>tools\windows\Autoriser-Reseau-Novaskol.cmd</strong> si le telephone n'arrive pas a joindre l'adresse locale.</li>
            <li>Relancer Novaskol si l'adresse locale ne repond plus.</li>
        </ul>
    </section>

    <footer>&copy; {{ date('Y') }} {{ $ecole->nom ?? 'Ecole' }}. Tous droits reserves.</footer>
</main>
<script src="{{ asset('legacy/vendor/qrcode.min.js') }}"></script>
<script>
function toggleSidebar(){document.getElementById('sidebar').classList.toggle('active')}
function toggleSub(e){const n=e.nextElementSibling;if(n)n.style.display=n.style.display==='none'?'block':'none'}
function toggleFullscreen(){document.fullscreenElement?document.exitFullscreen():document.documentElement.requestFullscreen()}
function copyLocalUrl(){
    const target=document.getElementById('local-network-url');
    if(!target){return}
    const value=target.textContent.trim();
    navigator.clipboard.writeText(value).then(()=>{
        if(window.Swal){Swal.fire({toast:true,position:'top-end',timer:1600,showConfirmButton:false,icon:'success',title:'Adresse locale copiee.'})}
    });
}
function copyConnectedUrl(){
    const target=document.getElementById('connected-app-url');
    if(!target){return}
    const value=target.textContent.trim();
    navigator.clipboard.writeText(value).then(()=>{
        if(window.Swal){Swal.fire({toast:true,position:'top-end',timer:1600,showConfirmButton:false,icon:'success',title:'Lien Novaskol Connecte copie.'})}
    });
}
function confirmLocalDeviceDelete(event){
    if(!window.Swal){return confirm('Supprimer cet appareil du suivi local ?')}
    event.preventDefault();
    Swal.fire({title:'Supprimer cet appareil ?',text:'Il pourra etre ajoute de nouveau plus tard si besoin.',icon:'warning',showCancelButton:true,confirmButtonText:'Supprimer',cancelButtonText:'Annuler'}).then(r=>{if(r.isConfirmed)event.target.submit()});
    return false;
}
document.addEventListener('DOMContentLoaded',()=>{
    const value=@json($connectedUrl ?? $localUrl);
    const box=document.getElementById('local-network-qr');
    if(!value||!box||typeof QRCode==='undefined'){return}
    box.innerHTML='';
    new QRCode(box,{text:value,width:188,height:188,colorDark:'#07111f',colorLight:'#ffffff',correctLevel:QRCode.CorrectLevel.M});
});
</script>
</body>
</html>
