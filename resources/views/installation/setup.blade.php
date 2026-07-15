<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Premier lancement Novaskol</title>
    <link rel="icon" type="image/png" href="{{ asset('novaskol-icon.png') }}">
    <link rel="stylesheet" href="{{ asset('legacy/assets/fontawesome/css/all.min.css') }}">
    <style>
        :root {
            --bg: #07111d;
            --bg-soft: #0d1725;
            --panel: rgba(11, 18, 30, .9);
            --surface: rgba(255, 255, 255, .05);
            --surface-strong: #131f31;
            --border: rgba(148, 163, 184, .18);
            --primary: #00c853;
            --primary-soft: rgba(0, 200, 83, .14);
            --cyan: #38bdf8;
            --gold: #facc15;
            --text: #f8fafc;
            --muted: #b7c4d6;
            --danger: #ef4444;
            --warning: #f59e0b;
            --shadow: 0 6px 20px rgba(0, 0, 0, .3);
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            min-height: 100vh;
            color: var(--text);
            font-family: Inter, "Segoe UI", system-ui, -apple-system, sans-serif;
            background: #080e18;
        }

        .shell {
            width: min(1240px, calc(100% - 28px));
            margin: 0 auto;
            padding: 28px 0 34px;
        }

        .topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            margin-bottom: 22px;
        }

        .brand {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            font-size: 1.15rem;
            font-weight: 900;
        }

        .brand-mark {
            width: 46px;
            height: 46px;
            border-radius: 14px;
            display: grid;
            place-items: center;
            color: #03150c;
            background: linear-gradient(135deg, var(--primary), #7df0b0);
            box-shadow: 0 16px 32px rgba(0, 200, 83, .24);
            font-size: 1.1rem;
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
            justify-content: flex-end;
        }

        .chip {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 14px;
            border-radius: 999px;
            border: 1px solid var(--border);
            background: rgba(255, 255, 255, .045);
            color: var(--muted);
            font-size: .92rem;
            font-weight: 800;
        }

        .layout {
            display: grid;
            grid-template-columns: minmax(310px, .88fr) minmax(0, 1.12fr);
            gap: 22px;
            align-items: start;
        }

        .panel {
            border: 1px solid var(--border);
            border-radius: 14px;
            background: #101a28;
            box-shadow: var(--shadow);
        }

        .hero-panel {
            overflow: hidden;
            position: sticky;
            top: 18px;
        }

        .hero-visual {
            position: relative;
            min-height: 270px;
            padding: 26px;
            background: #0d1624;
            border-bottom: 1px solid var(--border);
        }

        .eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 9px 12px;
            border-radius: 999px;
            background: rgba(0, 200, 83, .12);
            color: #b9f8d0;
            font-size: .84rem;
            font-weight: 900;
            position: relative;
            z-index: 1;
        }

        h1 {
            margin: 16px 0 14px;
            font-size: clamp(2.2rem, 4.8vw, 4.35rem);
            line-height: .96;
            letter-spacing: 0;
            position: relative;
            z-index: 1;
        }

        .hero-copy {
            margin: 0;
            max-width: 34rem;
            color: var(--muted);
            line-height: 1.66;
            font-size: 1rem;
            position: relative;
            z-index: 1;
        }

        .preview-card {
            margin-top: 24px;
            border-radius: 12px;
            padding: 18px;
            background: #0f1828;
            border: 1px solid var(--border);
        }

        .preview-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 12px;
        }

        .preview-item {
            min-height: 88px;
            border-radius: 10px;
            padding: 14px;
            background: rgba(255, 255, 255, .03);
            border: 1px solid var(--border);
        }

        .preview-item span {
            display: block;
            margin-bottom: 6px;
            color: #8ef0b7;
            font-size: .78rem;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: .04em;
        }

        .preview-item strong,
        .preview-item p {
            margin: 0;
            overflow-wrap: anywhere;
        }

        .preview-item p {
            color: var(--muted);
            line-height: 1.5;
        }

        .hero-details {
            display: grid;
            gap: 12px;
            padding: 22px;
        }

        .detail-card {
            display: grid;
            grid-template-columns: 44px minmax(0, 1fr);
            gap: 12px;
            align-items: start;
            padding: 14px;
            border-radius: 10px;
            background: rgba(255, 255, 255, .03);
            border: 1px solid var(--border);
        }

        .detail-card i {
            width: 44px;
            height: 44px;
            display: grid;
            place-items: center;
            border-radius: 14px;
            background: rgba(0, 200, 83, .12);
            color: #86efac;
        }

        .detail-card strong {
            display: block;
            margin-bottom: 4px;
        }

        .detail-card p {
            margin: 0;
            color: var(--muted);
            line-height: 1.58;
        }

        .assistant-card {
            overflow: hidden;
        }

        .assistant-head {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 16px;
            padding: 24px 24px 18px;
            border-bottom: 1px solid var(--border);
            background: rgba(255, 255, 255, .025);
        }

        .assistant-head h2 {
            margin: 0 0 8px;
            font-size: 1.65rem;
        }

        .assistant-head p {
            margin: 0;
            color: var(--muted);
            line-height: 1.58;
        }

        .assistant-meta {
            display: grid;
            gap: 8px;
            justify-items: end;
            min-width: 180px;
        }

        .steps-bar {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 10px;
            padding: 18px 24px 0;
        }

        .step-pill {
            min-height: 86px;
            padding: 14px;
            border-radius: 10px;
            background: rgba(255, 255, 255, .03);
            border: 1px solid var(--border);
            transition: .18s ease;
        }

        .step-pill small {
            display: inline-block;
            margin-bottom: 8px;
            color: var(--muted);
            font-weight: 900;
        }

        .step-pill strong {
            display: block;
            margin-bottom: 6px;
            font-size: .98rem;
        }

        .step-pill span {
            display: block;
            color: var(--muted);
            line-height: 1.4;
            font-size: .88rem;
        }

        .step-pill.is-current {
            border-color: var(--primary);
            background: rgba(0, 200, 83, .08);
        }

        .step-pill.is-done {
            border-color: #38bdf8;
            background: rgba(56, 189, 248, .06);
        }

        .messages {
            padding: 0 24px;
        }

        .message {
            margin-top: 16px;
            padding: 14px 16px;
            border-radius: 16px;
            border: 1px solid transparent;
            line-height: 1.55;
        }

        .message.success {
            background: rgba(0, 200, 83, .1);
            border-color: rgba(0, 200, 83, .22);
            color: #c7f9d6;
        }

        .message.warning {
            background: rgba(245, 158, 11, .12);
            border-color: rgba(245, 158, 11, .24);
            color: #fde68a;
        }

        .message.danger {
            background: rgba(239, 68, 68, .12);
            border-color: rgba(239, 68, 68, .24);
            color: #fecaca;
        }

        .error-list {
            margin: 16px 24px 0;
            padding: 15px 18px 15px 36px;
            border-radius: 18px;
            background: rgba(239, 68, 68, .12);
            border: 1px solid rgba(239, 68, 68, .26);
            color: #fecaca;
            line-height: 1.58;
        }

        form {
            padding: 22px 24px 24px;
        }

        .form-step {
            display: none;
            animation: rise .18s ease;
        }

        .form-step.is-active {
            display: block;
        }

        @keyframes rise {
            from { opacity: 0; transform: translateY(6px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .section-title {
            margin: 0 0 8px;
            font-size: 1.22rem;
        }

        .section-copy {
            margin: 0 0 18px;
            color: var(--muted);
            line-height: 1.6;
        }

        .mode-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 14px;
        }

        .mode {
            position: relative;
            display: block;
        }

        .mode input {
            position: absolute;
            opacity: 0;
            pointer-events: none;
        }

        .mode-body {
            min-height: 188px;
            display: grid;
            align-content: start;
            gap: 9px;
            padding: 18px;
            border-radius: 12px;
            background: rgba(255, 255, 255, .03);
            border: 1px solid var(--border);
            cursor: pointer;
            transition: .18s ease;
        }

        .mode-body i {
            width: 44px;
            height: 44px;
            display: grid;
            place-items: center;
            border-radius: 10px;
            background: rgba(0, 200, 83, .1);
            color: #86efac;
            font-size: 1rem;
        }

        .mode-body strong {
            font-size: 1rem;
        }

        .mode-body p,
        .mode-body ul {
            margin: 0;
            color: var(--muted);
            line-height: 1.52;
        }

        .mode-body ul {
            padding-left: 18px;
        }

        .mode input:checked + .mode-body {
            border-color: var(--primary);
            background: rgba(0, 200, 83, .08);
            box-shadow: 0 0 0 3px rgba(0, 200, 83, .12);
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 14px;
        }

        .field.full {
            grid-column: 1 / -1;
        }

        .field-label {
            display: block;
            margin-bottom: 8px;
            color: #d9f3df;
            font-size: .94rem;
            font-weight: 900;
        }

        input,
        select {
            width: 100%;
            min-height: 48px;
            padding: 12px 14px;
            border-radius: 14px;
            border: 1px solid var(--border);
            background: #132134;
            color: var(--text);
            font: inherit;
            outline: none;
            transition: .16s ease;
        }

        input:focus,
        select:focus {
            border-color: rgba(0, 200, 83, .8);
            box-shadow: 0 0 0 4px rgba(0, 200, 83, .12);
        }

        .inline-tip {
            margin-top: 14px;
            padding: 14px 15px;
            border-radius: 16px;
            background: rgba(56, 189, 248, .1);
            border: 1px solid rgba(56, 189, 248, .24);
            color: #bae6fd;
            line-height: 1.56;
        }

        .summary-card {
            border-radius: 18px;
            border: 1px solid var(--border);
            background: rgba(255, 255, 255, .03);
            overflow: hidden;
        }

        .summary-row {
            display: grid;
            grid-template-columns: 170px minmax(0, 1fr);
            gap: 14px;
            align-items: start;
            padding: 14px 16px;
            border-bottom: 1px solid var(--border);
        }

        .summary-row:last-child {
            border-bottom: 0;
        }

        .summary-row span {
            color: var(--muted);
            font-weight: 800;
        }

        .summary-row strong {
            overflow-wrap: anywhere;
            text-align: right;
        }

        .final-grid {
            display: grid;
            grid-template-columns: 1.15fr .85fr;
            gap: 16px;
        }

        .checklist {
            margin: 0;
            padding: 0;
            list-style: none;
            display: grid;
            gap: 12px;
        }

        .checklist li {
            display: grid;
            grid-template-columns: 22px minmax(0, 1fr);
            gap: 10px;
            align-items: start;
            color: var(--muted);
            line-height: 1.55;
        }

        .checklist i {
            margin-top: 2px;
            color: #86efac;
        }

        .actions {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            margin-top: 22px;
            flex-wrap: wrap;
        }

        .btn {
            border: 0;
            border-radius: 8px;
            padding: 12px 20px;
            min-width: 140px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            font: inherit;
            font-weight: 700;
            cursor: pointer;
            transition: .16s ease;
        }

        .btn:hover {
            opacity: .88;
        }

        .btn.primary {
            background: var(--primary);
            color: #04150d;
        }

        .btn.secondary {
            background: rgba(255, 255, 255, .06);
            color: var(--text);
            border: 1px solid var(--border);
        }

        .btn.danger-btn {
            width: 100%;
            margin-top: 12px;
            background: var(--danger);
            color: #fff;
        }

        .reset-box {
            margin: 18px 24px 0;
            padding: 16px;
            border-radius: 18px;
            background: rgba(239, 68, 68, .08);
            border: 1px solid rgba(239, 68, 68, .22);
        }

        .reset-box form {
            padding: 0;
        }

        @media (max-width: 1080px) {
            .layout {
                grid-template-columns: 1fr;
            }

            .hero-panel {
                position: static;
            }
        }

        @media (max-width: 780px) {
            .steps-bar,
            .mode-grid,
            .grid,
            .preview-grid,
            .final-grid {
                grid-template-columns: 1fr;
            }

            .assistant-head,
            .topbar,
            .actions {
                flex-direction: column;
                align-items: stretch;
            }

            .assistant-meta {
                justify-items: start;
                min-width: 0;
            }

            .summary-row {
                grid-template-columns: 1fr;
            }

            .summary-row strong {
                text-align: left;
            }
        }

        @media (max-width: 560px) {
            .shell {
                width: min(100% - 18px, 1240px);
                padding-top: 18px;
            }

            .hero-visual,
            .hero-details,
            .assistant-head,
            form {
                padding-left: 18px;
                padding-right: 18px;
            }

            .steps-bar {
                padding: 16px 18px 0;
            }

            .messages,
            .reset-box,
            .error-list {
                margin-left: 18px;
                margin-right: 18px;
            }

            .btn {
                width: 100%;
            }
        }
    </style>
</head>
<body>
<main class="shell">
    <div class="topbar">
        <div class="brand">
            <span class="brand-mark"><i class="fa fa-graduation-cap"></i></span>
            <span>Novaskol · Premier lancement</span>
        </div>
        <div class="topbar-right">
            <span class="chip"><i class="fa fa-desktop"></i> Version locale autonome</span>
            <span class="chip"><i class="fa fa-shield"></i> Données séparées par école</span>
        </div>
    </div>

    <section class="layout">
        <aside class="panel hero-panel">
            <div class="hero-visual">
                <span class="eyebrow"><i class="fa fa-magic"></i> Installation guidée</span>
                <h1>Préparer votre établissement sans stress</h1>
                <p class="hero-copy">
                    Novaskol peut démarrer en base vide pour une vraie école, ou en mode démo pour les présentations.
                    Dans tous les cas, l’établissement garde ses propres données, ses sauvegardes et sa liberté d’hébergement.
                </p>

                <div class="preview-card">
                    <div class="preview-grid">
                        <div class="preview-item">
                            <span>Mode actuel</span>
                            <strong>{{ $currentMode === 'demo' ? 'Démonstration' : 'Réel / production' }}</strong>
                        </div>
                        <div class="preview-item">
                            <span>État</span>
                            <strong>{{ $installed ? 'Instance déjà initialisée' : 'Nouvelle installation' }}</strong>
                        </div>
                        <div class="preview-item">
                            <span>Dump vide</span>
                            <p>{{ $emptyDumpExists ? 'Disponible pour un départ propre.' : 'Manquant dans le paquet actuel.' }}</p>
                        </div>
                        <div class="preview-item">
                            <span>Dump démo</span>
                            <p>{{ $demoDumpExists ? 'Prêt pour les essais et démonstrations.' : 'Absent pour le moment.' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="hero-details">
                <div class="detail-card">
                    <i class="fa fa-database"></i>
                    <div>
                        <strong>Base locale ou démo réversible</strong>
                        <p>Le mode réel démarre proprement. Le mode démo sert aux essais et peut revenir ensuite en mode réel.</p>
                    </div>
                </div>
                <div class="detail-card">
                    <i class="fa fa-user-secret"></i>
                    <div>
                        <strong>Premier administrateur sécurisé</strong>
                        <p>Le compte créé ici reçoit directement tous les accès nécessaires pour finaliser l’école.</p>
                    </div>
                </div>
                <div class="detail-card">
                    <i class="fa fa-cloud"></i>
                    <div>
                        <strong>Même base pour local ou hébergé</strong>
                        <p>Cette version peut vivre sur un PC d’école ou basculer plus tard vers un hébergement dédié.</p>
                    </div>
                </div>
                @if($installedAt)
                    <div class="detail-card">
                        <i class="fa fa-history"></i>
                        <div>
                            <strong>Dernière initialisation</strong>
                            <p>{{ $installedAt }}</p>
                        </div>
                    </div>
                @endif
            </div>
        </aside>

        <section class="panel assistant-card">
            <div class="assistant-head">
                <div>
                    <h2>Assistant Novaskol</h2>
                    <p>En quatre étapes, on prépare le mode de départ, l’identité de l’école et le premier compte administrateur.</p>
                </div>
                <div class="assistant-meta">
                    @if($installed)
                        <span class="chip"><i class="fa fa-lock"></i> Modification réservée à l’admin</span>
                    @else
                        <span class="chip"><i class="fa fa-unlock"></i> Première configuration</span>
                    @endif
                    <span class="chip"><i class="fa fa-check-circle"></i> Parcours guidé et réversible</span>
                </div>
            </div>

            <div class="steps-bar" aria-hidden="true">
                <div class="step-pill is-current" data-progress="1">
                    <small>Étape 1</small>
                    <strong>Mode de départ</strong>
                    <span>Réel ou démonstration</span>
                </div>
                <div class="step-pill" data-progress="2">
                    <small>Étape 2</small>
                    <strong>École</strong>
                    <span>Nom, contacts, année scolaire</span>
                </div>
                <div class="step-pill" data-progress="3">
                    <small>Étape 3</small>
                    <strong>Administrateur</strong>
                    <span>Compte principal</span>
                </div>
                <div class="step-pill" data-progress="4">
                    <small>Étape 4</small>
                    <strong>Validation</strong>
                    <span>Résumé avant lancement</span>
                </div>
            </div>

            @if($errors->any())
                <ul class="error-list">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            @endif

            <div class="messages">
                @if(session('success'))
                    <div class="message success">
                        <i class="fa fa-check-circle"></i> {{ session('success') }}
                    </div>
                @endif

                @if($installed)
                    <div class="message warning">
                        <i class="fa fa-info-circle"></i> Cette instance est déjà initialisée. Vous pouvez ajuster la configuration si vous êtes connecté comme administrateur.
                    </div>
                @endif
            </div>

            @if($currentMode === 'demo')
                <div class="reset-box">
                    <form method="POST" action="{{ route('installation.reset-demo') }}" data-confirm-reset>
                        @csrf
                        <div class="message danger" style="margin:0;">
                            <strong>Nettoyer la démonstration</strong><br>
                            Cette action supprime les données fictives et remet l’instance en mode réel.
                            Tapez exactement <strong>PASSER EN MODE REEL</strong> avant de confirmer.
                        </div>
                        <div style="margin-top:12px;">
                            <input name="confirmation" placeholder="PASSER EN MODE REEL" required>
                        </div>
                        <button class="btn danger-btn" type="submit"><i class="fa fa-trash"></i> Nettoyer la démo et passer en réel</button>
                    </form>
                </div>
            @endif

            <form method="POST" action="{{ route('installation.store') }}" id="installForm">
                @csrf
                @if($installed)
                    <input type="hidden" name="force_update" value="1">
                @endif

                <section class="form-step is-active" data-step="1">
                    <h3 class="section-title">Choisissez le départ qui vous convient</h3>
                    <p class="section-copy">Le mode démo sert à montrer Novaskol rapidement. Le mode réel prépare une école sans données fictives.</p>

                    <div class="mode-grid">
                        <label class="mode">
                            <input type="radio" name="mode" value="empty" @checked(old('mode', $currentMode === 'demo' ? 'demo' : 'empty') === 'empty')>
                            <span class="mode-body">
                                <i class="fa fa-leaf"></i>
                                <strong>Base vide</strong>
                                <p>Pour une école qui démarre sa gestion avec ses propres élèves, classes et paiements.</p>
                                <ul>
                                    <li>aucune donnée fictive</li>
                                    <li>configuration propre dès le début</li>
                                    <li>idéal pour la mise en production</li>
                                </ul>
                            </span>
                        </label>

                        <label class="mode">
                            <input type="radio" name="mode" value="demo" @checked(old('mode', $currentMode === 'demo' ? 'demo' : 'empty') === 'demo')>
                            <span class="mode-body">
                                <i class="fa fa-flask"></i>
                                <strong>Mode démo</strong>
                                <p>Pour les tests, les soutenances ou une démonstration immédiate devant une école.</p>
                                <ul>
                                    <li>classes et élèves fictifs</li>
                                    <li>notes et paiements d’exemple</li>
                                    <li>retour possible en mode réel</li>
                                </ul>
                            </span>
                        </label>
                    </div>

                    <div class="inline-tip"><i class="fa fa-lightbulb-o"></i> Conseil pratique : choisissez le mode démo pour présenter Novaskol, puis repassez plus tard en mode réel sur la machine finale.</div>
                </section>

                <section class="form-step" data-step="2">
                    <h3 class="section-title">Identité de l’établissement</h3>
                    <p class="section-copy">Ces informations alimentent immédiatement l’école, les entêtes, les impressions et les paramètres généraux.</p>

                    <div class="grid">
                        <div class="field">
                            <label class="field-label">Nom de l’école</label>
                            <input name="nom_ecole" value="{{ old('nom_ecole', $setupDefaults['nom_ecole']) }}" required>
                        </div>
                        <div class="field">
                            <label class="field-label">Année scolaire</label>
                            <input name="annee_scolaire" value="{{ old('annee_scolaire', $setupDefaults['annee_scolaire']) }}" required>
                        </div>
                        <div class="field">
                            <label class="field-label">Téléphone</label>
                            <input name="telephone_ecole" value="{{ old('telephone_ecole', $setupDefaults['telephone_ecole']) }}">
                        </div>
                        <div class="field">
                            <label class="field-label">Email école</label>
                            <input type="email" name="email_ecole" value="{{ old('email_ecole', $setupDefaults['email_ecole']) }}">
                        </div>
                        <div class="field full">
                            <label class="field-label">Adresse</label>
                            <input name="adresse_ecole" value="{{ old('adresse_ecole', $setupDefaults['adresse_ecole']) }}">
                        </div>
                    </div>
                </section>

                <section class="form-step" data-step="3">
                    <h3 class="section-title">Créer le premier administrateur</h3>
                    <p class="section-copy">Ce compte ouvre le dashboard principal, la gestion des permissions, les sauvegardes et tous les modules Novaskol.</p>

                    <div class="grid">
                        <div class="field">
                            <label class="field-label">Nom administrateur</label>
                            <input name="admin_nom" value="{{ old('admin_nom', $setupDefaults['admin_nom']) }}" required>
                        </div>
                        <div class="field">
                            <label class="field-label">Email administrateur</label>
                            <input type="email" name="admin_email" value="{{ old('admin_email', $setupDefaults['admin_email']) }}" required>
                        </div>
                        <div class="field">
                            <label class="field-label">Mot de passe</label>
                            <input type="password" name="admin_password" minlength="8" required>
                        </div>
                        <div class="field">
                            <label class="field-label">Confirmation du mot de passe</label>
                            <input type="password" name="admin_password_confirmation" minlength="8" required>
                        </div>
                    </div>

                    <div class="inline-tip"><i class="fa fa-key"></i> Ce compte reçoit automatiquement tous les accès d’administration. Vous pourrez ensuite créer les autres utilisateurs depuis Novaskol.</div>
                </section>

                <section class="form-step" data-step="4">
                    <h3 class="section-title">Validation finale</h3>
                    <p class="section-copy">Relisez le résumé. Après validation, Novaskol enregistrera l’école, préparera le compte admin et vous renverra vers la connexion.</p>

                    <div class="final-grid">
                        <div class="summary-card">
                            <div class="summary-row">
                                <span>Mode choisi</span>
                                <strong data-summary="mode">-</strong>
                            </div>
                            <div class="summary-row">
                                <span>Établissement</span>
                                <strong data-summary="school">-</strong>
                            </div>
                            <div class="summary-row">
                                <span>Année scolaire</span>
                                <strong data-summary="year">-</strong>
                            </div>
                            <div class="summary-row">
                                <span>Téléphone</span>
                                <strong data-summary="phone">-</strong>
                            </div>
                            <div class="summary-row">
                                <span>Administrateur</span>
                                <strong data-summary="admin">-</strong>
                            </div>
                        </div>

                        <div class="summary-card" style="padding:16px;">
                            <ul class="checklist">
                                <li><i class="fa fa-check-circle"></i><span>Les impressions et entêtes utiliseront les infos école enregistrées ici.</span></li>
                                <li><i class="fa fa-check-circle"></i><span>Le mode démo restera réversible depuis cette page pour un administrateur.</span></li>
                                <li><i class="fa fa-check-circle"></i><span>Après connexion, ouvrez le diagnostic système pour vérifier la livraison locale.</span></li>
                            </ul>
                        </div>
                    </div>
                </section>

                <div class="actions">
                    <button class="btn secondary" type="button" id="prevBtn"><i class="fa fa-arrow-left"></i> Retour</button>
                    <button class="btn primary" type="button" id="nextBtn">Continuer <i class="fa fa-arrow-right"></i></button>
                    <button class="btn primary" type="submit" id="submitBtn" style="display:none"><i class="fa fa-magic"></i> Initialiser Novaskol</button>
                </div>
            </form>
        </section>
    </section>
</main>

<script>
(() => {
    const form = document.getElementById('installForm');
    const steps = Array.from(document.querySelectorAll('.form-step'));
    const progress = Array.from(document.querySelectorAll('.step-pill'));
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    const submitBtn = document.getElementById('submitBtn');
    let current = 1;

    function fieldsForStep(step) {
        return Array.from(document.querySelectorAll(`.form-step[data-step="${step}"] input, .form-step[data-step="${step}"] select`));
    }

    function validateStep(step) {
        const fields = fieldsForStep(step).filter(field => !field.disabled && field.offsetParent !== null);
        for (const field of fields) {
            if (!field.checkValidity()) {
                field.reportValidity();
                return false;
            }
        }
        return true;
    }

    function textValue(name, fallback = 'Non renseigné') {
        const field = form.querySelector(`[name="${name}"]`);
        return field && field.value.trim() ? field.value.trim() : fallback;
    }

    function updateSummary() {
        const mode = form.querySelector('[name="mode"]:checked')?.value === 'demo' ? 'Mode démo' : 'Base vide';
        document.querySelector('[data-summary="mode"]').textContent = mode;
        document.querySelector('[data-summary="school"]').textContent = textValue('nom_ecole');
        document.querySelector('[data-summary="year"]').textContent = textValue('annee_scolaire');
        document.querySelector('[data-summary="phone"]').textContent = textValue('telephone_ecole');
        document.querySelector('[data-summary="admin"]').textContent = textValue('admin_email');
    }

    function showStep(step) {
        current = Math.max(1, Math.min(4, step));

        steps.forEach(item => {
            item.classList.toggle('is-active', Number(item.dataset.step) === current);
        });

        progress.forEach(item => {
            const index = Number(item.dataset.progress);
            item.classList.toggle('is-current', index === current);
            item.classList.toggle('is-done', index < current);
        });

        prevBtn.style.visibility = current === 1 ? 'hidden' : 'visible';
        nextBtn.style.display = current === 4 ? 'none' : 'inline-flex';
        submitBtn.style.display = current === 4 ? 'inline-flex' : 'none';

        if (current === 4) {
            updateSummary();
        }
    }

    prevBtn.addEventListener('click', () => showStep(current - 1));

    nextBtn.addEventListener('click', () => {
        if (validateStep(current)) {
            showStep(current + 1);
        }
    });

    form.addEventListener('submit', event => {
        if (!validateStep(current)) {
            event.preventDefault();
            return;
        }

        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Initialisation en cours...';
    });

    document.querySelector('[data-confirm-reset]')?.addEventListener('submit', event => {
        const input = event.currentTarget.querySelector('[name="confirmation"]');
        if (input.value !== 'PASSER EN MODE REEL') {
            event.preventDefault();
            input.reportValidity();
        }
    });

    showStep({{ $errors->any() ? 2 : 1 }});
})();
</script>
</body>
</html>
