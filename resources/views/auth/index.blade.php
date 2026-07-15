<!DOCTYPE html>
@php
  $interfaceLanguage = DB::table('parametres')->where('cle', 'langue_interface')->value('valeur') ?: 'fr';
@endphp
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Novaskol - Un systeme de gestion scolaire pour chaque ecole</title>
    <link rel="icon" type="image/png" href="{{ asset('novaskol-icon.png') }}">
<link rel="stylesheet" href="{{ asset('legacy/assets/fontawesome/css/all.min.css') }}">
    <script src="{{ asset('legacy/js/jquery-3.6.0.min.js') }}"></script>
    <script>window.NOVASKOL_INITIAL_LANGUAGE = @json($interfaceLanguage);</script>
    <script src="{{ asset('legacy/js/novaskol-i18n.js') }}"></script>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
  <style>
    :root {
      --primary: #00c853;
      --primary-dark: #00a843;
      --primary-glow: rgba(0,200,83,0.18);
      --bg: #06090f;
      --surface: #0d1117;
      --card: rgba(13,17,23,0.72);
      --text: #f0f6fc;
      --text-sec: #8b949e;
      --border: rgba(48,54,61,0.4);
      --radius: 16px;
      --radius-sm: 10px;
    }
    * { margin:0; padding:0; box-sizing:border-box; }
    *::-webkit-scrollbar { width: 4px; }
    *::-webkit-scrollbar-track { background: var(--bg); }
    *::-webkit-scrollbar-thumb { background: #2a2a3a; border-radius: 10px; }
    *::-webkit-scrollbar-thumb:hover { background: var(--primary); }
    * { scrollbar-width: thin; scrollbar-color: #2a2a3a var(--bg); }
    html { scroll-behavior: smooth; }
    body {
      font-family: 'Inter', system-ui, -apple-system, sans-serif;
      background: var(--bg);
      color: var(--text-sec);
      line-height: 1.7;
      min-height: 100vh;
    }
    .bg-gradient {
      position: fixed; inset: 0;
      background:
        radial-gradient(ellipse 80% 60% at 20% 20%, rgba(0,200,83,0.07) 0%, transparent 50%),
        radial-gradient(ellipse 60% 70% at 80% 80%, rgba(0,200,83,0.04) 0%, transparent 50%),
        radial-gradient(ellipse 50% 50% at 50% 50%, rgba(0,200,83,0.02) 0%, transparent 70%);
      z-index: -2; pointer-events: none;
    }
    header {
      position: fixed; top: 0; left: 0; right: 0;
      background: rgba(6,9,15,0.82);
      backdrop-filter: blur(20px) saturate(1.4);
      -webkit-backdrop-filter: blur(20px) saturate(1.4);
      border-bottom: 1px solid var(--border);
      z-index: 1000;
      padding: 1rem 5%;
      transition: all 0.4s cubic-bezier(0.4,0,0.2,1);
    }
    header.scrolled {
      padding: 0.7rem 5%;
      background: rgba(6,9,15,0.92);
      box-shadow: 0 4px 30px rgba(0,0,0,0.5);
    }
    .header-container {
      max-width: 1280px; margin: 0 auto;
      display: flex; align-items: center; justify-content: space-between;
    }
    .logo {
      font-size: 1.5rem; font-weight: 800;
      color: white; display: flex; align-items: center; gap: 0.6rem;
      letter-spacing: -0.02em;
    }
    .logo i { color: var(--primary); font-size: 1.3rem; }
    .logo span { background: linear-gradient(135deg,#fff 40%,var(--primary)); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
    nav { display: flex; gap: 2rem; }
    nav a {
      color: var(--text-sec); text-decoration: none;
      font-weight: 500; font-size: 0.92rem;
      transition: all 0.3s; position: relative;
      padding: 0.3rem 0;
    }
    nav a:hover, nav a.active { color: white; }
    nav a::after {
      content: ''; position: absolute; bottom: 0; left: 0;
      width: 0; height: 2px;
      background: var(--primary);
      transition: width 0.3s cubic-bezier(0.4,0,0.2,1);
      border-radius: 2px;
    }
    nav a:hover::after, nav a.active::after { width: 100%; }
    .burger { display: none; font-size: 1.6rem; color: var(--text); cursor: pointer; }
    .header-right { display: flex; align-items: center; gap: 0.8rem; }
    .auth-lang-wrap { position: relative; }
    .auth-lang-btn {
      width: 40px; height: 40px; border-radius: 50%;
      border: 1px solid var(--border); background: rgba(255,255,255,0.04);
      color: var(--text-sec); display: grid; place-items: center;
      cursor: pointer; transition: all 0.3s; font-size: 1rem;
    }
    .auth-lang-btn:hover { color: var(--primary); border-color: var(--primary); background: rgba(0,200,83,0.08); }
    .auth-lang-menu {
      position: absolute; top: 48px; right: 0;
      min-width: 80px; padding: 6px; border-radius: var(--radius-sm);
      border: 1px solid var(--border);
      background: rgba(6,9,15,0.96); backdrop-filter: blur(16px);
      box-shadow: 0 20px 60px rgba(0,0,0,0.6);
      display: none; gap: 4px;
    }
    .auth-lang-menu.active { display: grid; }
    .auth-lang-option {
      width: 100%; padding: 6px 10px; border-radius: 8px;
      border: 1px solid transparent; background: transparent;
      color: var(--text-sec); font-weight: 700; font-size: 0.82rem;
      cursor: pointer; text-align: center; transition: all 0.2s;
    }
    .auth-lang-option:hover, .auth-lang-option.active {
      color: var(--primary); background: rgba(0,200,83,0.1); border-color: rgba(0,200,83,0.2);
    }
    main { padding-top: 78px; }
    section { padding: 5rem 5% 6rem; max-width: 1280px; margin: 0 auto; }
    h1, h2 { color: white; font-weight: 800; line-height: 1.12; letter-spacing: -0.03em; }
    h1 { font-size: clamp(2.8rem, 7vw, 5.5rem); margin-bottom: 1.5rem; }
    h2 { font-size: clamp(2rem, 5vw, 3.5rem); margin-bottom: 1.5rem; text-align: center; }
    .hero {
      text-align: center; padding-top: 6vh; min-height: 88vh;
      display: flex; flex-direction: column; justify-content: center;
      position: relative;
    }
    .hero h1 span { background: linear-gradient(135deg,#fff 30%,var(--primary) 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
    .hero p {
      font-size: 1.15rem; max-width: 680px; margin: 0 auto 2.5rem;
      color: var(--text-sec) !important; line-height: 1.7;
    }
    .btn-group { display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap; margin: 2.5rem 0; }
    .btn {
      display: inline-flex; align-items: center; gap: 0.5rem;
      padding: 0.95rem 2rem;
      background: var(--primary); color: #06090f;
      font-weight: 700; font-size: 0.95rem;
      border-radius: 50px; text-decoration: none;
      transition: all 0.35s cubic-bezier(0.4,0,0.2,1);
      box-shadow: 0 8px 30px var(--primary-glow);
      border: 2px solid transparent;
    }
    .btn:hover { transform: translateY(-3px); box-shadow: 0 12px 40px var(--primary-glow); background: var(--primary-dark); }
    .btn-outline { background: transparent; border-color: var(--primary); color: var(--primary); box-shadow: none; }
    .btn-outline:hover { background: var(--primary); color: #06090f; box-shadow: 0 8px 30px var(--primary-glow); }
    .stats {
      display: flex; justify-content: center; gap: 3rem; flex-wrap: wrap;
      margin: 3rem 0 0;
    }
    .stat { text-align: center; }
    .stat-number {
      font-size: 3.2rem; font-weight: 900;
      background: linear-gradient(135deg,var(--primary),#69f0ae);
      -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;
      display: block; line-height: 1.1;
    }
    .stat-label { font-size: 1rem; color: var(--text-sec); margin-top: 0.3rem; display: block; }
    .features-grid {
      display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
      gap: 1.5rem; margin: 3rem 0;
    }
    .feature-card {
      background: var(--card); border: 1px solid var(--border);
      border-radius: var(--radius); padding: 2rem;
      transition: all 0.4s cubic-bezier(0.4,0,0.2,1);
      backdrop-filter: blur(12px); position: relative; overflow: hidden;
    }
    .feature-card::before {
      content: ''; position: absolute; top: 0; left: 0; right: 0; height: 2px;
      background: linear-gradient(90deg,transparent,var(--primary),transparent);
      opacity: 0; transition: opacity 0.4s;
    }
    .feature-card:hover { transform: translateY(-6px); border-color: rgba(0,200,83,0.3); box-shadow: 0 20px 60px rgba(0,0,0,0.3); }
    .feature-card:hover::before { opacity: 1; }
    .feature-icon { font-size: 2.2rem; color: var(--primary); margin-bottom: 1.2rem; }
    .feature-title { color: white; font-size: 1.25rem; font-weight: 700; margin-bottom: 0.8rem; }
    .feature-card p { font-size: 0.92rem; line-height: 1.65; color: var(--text-sec); }
    .auth-section {
      background: rgba(6,9,15,0.6);
      backdrop-filter: blur(20px) saturate(1.4);
      -webkit-backdrop-filter: blur(20px) saturate(1.4);
      border-radius: 20px; padding: 3rem; max-width: 520px;
      margin: 2rem auto; border: 1px solid var(--border);
      box-shadow: 0 20px 80px rgba(0,0,0,0.5);
    }
    .auth-section h2 { margin-bottom: 1.5rem; }
    .auth-box { display: none; }
    .auth-box.active { display: block; }
    .form-group { margin-bottom: 1.2rem; }
    .form-group label { display: block; font-size: 0.85rem; font-weight: 600; color: var(--text-sec); margin-bottom: 0.4rem; }
    input, select, textarea {
      width: 100%; padding: 0.9rem 1.2rem;
      background: rgba(255,255,255,0.04);
      border: 1px solid var(--border); border-radius: var(--radius-sm);
      color: white; font-size: 0.95rem; font-family: inherit;
      transition: all 0.3s;
    }
    input:focus, select:focus, textarea:focus {
      outline: none; border-color: var(--primary);
      box-shadow: 0 0 0 3px rgba(0,200,83,0.12);
      background: rgba(255,255,255,0.06);
    }
    input::placeholder { color: #555; }
    .auth-section .btn { width: 100%; justify-content: center; margin-top: 0.5rem; }
    .error-msg {
      background: rgba(255,80,80,0.12); color: #ff8080;
      padding: 0.8rem 1rem; border-radius: var(--radius-sm);
      margin: 0.8rem 0; text-align: center; font-size: 0.9rem;
      border: 1px solid rgba(255,80,80,0.2);
    }
    .success-msg {
      background: rgba(0,200,83,0.08); color: #69f0ae;
      padding: 0.8rem 1rem; border-radius: var(--radius-sm);
      margin: 0.8rem auto; text-align: center; font-size: 0.9rem;
      max-width: 480px; border: 1px solid rgba(0,200,83,0.15);
    }
    footer {
      text-align: center; padding: 3rem 1rem 2rem;
      color: #555; border-top: 1px solid var(--border); font-size: 0.88rem;
    }
    .floating-particles { position: fixed; inset: 0; pointer-events: none; z-index: -1; overflow: hidden; }
    .particle {
      position: absolute; width: 3px; height: 3px;
      background: var(--primary); border-radius: 50%;
      opacity: 0.4; box-shadow: 0 0 10px var(--primary);
      animation: floatGlow 60s infinite linear;
    }
    @keyframes floatGlow {
      0% { transform: translate(0,0) scale(1); opacity: 0.4; }
      50% { opacity: 0.7; }
      100% { transform: translate(var(--tx,50vw),var(--ty,50vh)) scale(0.4); opacity: 0; }
    }
    @media (max-width: 1100px) {
      section { padding: 4rem 4% 5rem; }
      .features-grid { grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); }
    }
    @media (max-width: 900px) {
      nav {
        display: none; position: absolute; top: 100%; left: 0; right: 0;
        background: rgba(6,9,15,0.96); padding: 1rem 5%;
        flex-direction: column; align-items: center; gap: 1.4rem;
        border-bottom: 1px solid rgba(0,200,83,0.15);
        backdrop-filter: blur(16px);
      }
      nav.active { display: flex; }
      .burger { display: block; }
      .logo { font-size: 1.3rem; }
      .stats { gap: 2rem; }
      .hero { padding-top: 8vh; }
      .hero h1 br { display: none; }
      .hero p { font-size: 1rem; }
    }
    @media (max-width: 640px) {
      header { padding: 0.7rem 4%; }
      main { padding-top: 64px; }
      section { padding: 2.5rem 4% 3rem; }
      .logo { font-size: 1.15rem; }
      .auth-lang-btn { width: 36px; height: 36px; font-size: 0.9rem; }
      .burger { font-size: 1.4rem; }
      nav { gap: 1rem; padding: 0.8rem 4%; }
      h1 { font-size: clamp(2rem, 10vw, 2.8rem); margin-bottom: 0.8rem; }
      h2 { font-size: clamp(1.5rem, 7vw, 2rem); margin-bottom: 1rem; }
      .hero { min-height: auto; padding-top: 3rem; padding-bottom: 2rem; justify-content: flex-start; }
      .hero p { font-size: 0.9rem; line-height: 1.55; margin-bottom: 1rem; }
      .btn-group { gap: 0.7rem; margin: 1.5rem 0; }
      .btn {
        width: 100%; justify-content: center; padding: 0.8rem 1.2rem;
        font-size: 0.88rem; border-radius: 14px;
      }
      .stats { display: grid; grid-template-columns: repeat(3,1fr); gap: 0.6rem; margin: 1.5rem 0 0; }
      .stat { background: var(--card); border: 1px solid var(--border); border-radius: 12px; padding: 0.7rem 0.3rem; }
      .stat-number { font-size: 1.5rem; }
      .stat-label { font-size: 0.65rem; }
      .features-grid { grid-template-columns: 1fr; gap: 0.8rem; margin: 1.5rem 0; }
      .feature-card { padding: 1.25rem; border-radius: 14px; }
      .feature-card:hover { transform: none; }
      .feature-icon { font-size: 1.6rem; margin-bottom: 0.7rem; }
      .feature-title { font-size: 1.05rem; margin-bottom: 0.5rem; }
      .feature-card p, #about > p, #contact > p { font-size: 0.88rem !important; }
      .auth-section { margin: 1rem auto; padding: 1.5rem 1.2rem; border-radius: 16px; }
      .form-group { margin-bottom: 0.8rem; }
      input, select, textarea { padding: 0.8rem 1rem; font-size: 0.9rem; }
      footer { padding: 2rem 1rem 1.5rem; font-size: 0.8rem; }
    }
    @media (max-width: 390px) {
      .logo { font-size: 1rem; }
      h1 { font-size: 1.7rem; }
      .hero p { font-size: 0.82rem; }
      .btn { font-size: 0.82rem; padding: 0.7rem 0.8rem; }
      .stat-number { font-size: 1.3rem; }
      .stat-label { font-size: 0.6rem; }
      .auth-section { padding: 1rem 0.8rem; }
    }
  </style>
</head>
<body>
  <div id="particles" class="floating-particles"></div>

<div class="bg-gradient"></div>

<header id="header">
  <div class="header-container">
    <div class="logo">
      <i class="fas fa-graduation-cap"></i> <span>Novaskol</span>
    </div>
    <nav id="nav">
      <a href="#hero" class="active">Accueil</a>
      <a href="#about">À propos</a>
      <a href="#features">Fonctionnalités</a>
      <a href="#contact">Contact</a>
      <a href="#auth">Connexion</a>
    </nav>
    <div class="header-right">
      <div class="auth-lang-wrap">
        <button type="button" class="auth-lang-btn" title="Langue" onclick="toggleAuthLanguageMenu()"><i class="fa fa-language"></i></button>
        <div class="auth-lang-menu" id="authLanguageMenu">
          @foreach (['fr' => 'FR', 'en' => 'EN', 'de' => 'DE', 'mg' => 'MG', 'es' => 'ES', 'pt' => 'PT'] as $langCode => $langLabel)
            <button type="button" class="auth-lang-option @if($interfaceLanguage === $langCode) active @endif" data-lang-option data-lang-code="{{ $langCode }}">{{ $langLabel }}</button>
          @endforeach
        </div>
      </div>
      <div class="burger" onclick="toggleMenu()"><i class="fas fa-bars"></i></div>
    </div>

  </div>
</header>

<main>
  <section id="hero" class="hero">
    <h1><span>Novaskol</span><br>pour chaque ecole</h1>
    <p>Un systeme de gestion scolaire moderne : installable en local, partageable ecole par ecole, et pret a etre heberge en ligne quand l'etablissement le souhaite.</p>

    <div class="btn-group">
      <a href="#auth" class="btn">Commencer maintenant</a>
      <a href="#contact" class="btn btn-outline">Demander une démo</a>
    </div>

<div class="stats">
  <div class="stat">
    <span class="stat-number" data-count="120">0</span>+
    <span class="stat-label">Établissements</span>
  </div>
  <div class="stat">
    <span class="stat-number" data-count="8500">0</span>+
    <span class="stat-label">Élèves connectés</span>
  </div>
  <div class="stat">
    <span class="stat-number" data-count="94">0</span>%
    <span class="stat-label">Satisfaction</span>
  </div>
</div>  </section>

  <section id="about">
    <h2>Qui sommes-nous ?</h2>
    <p style="max-width:780px; margin:0 auto 3rem; font-size:1.2rem; text-align:center; opacity:0.9;">
      Créée à Antananarivo, Novaskol répond à un besoin simple : permettre aux écoles de gérer leurs données elles-mêmes, même sans connexion permanente. Chaque établissement peut l'utiliser sur un ordinateur local, puis le mettre en ligne plus tard avec son propre domaine.
    </p>

    <div class="features-grid">
      <div class="feature-card">
        <div class="feature-icon"><i class="fas fa-heart"></i></div>
        <div class="feature-title">Pensée pour les écoles modernes</div>
        <p>Fonctionnement local, sauvegardes, paiements échelonnés, calendriers scolaires et modules adaptés aux réalités des établissements.</p>
      </div>
      <div class="feature-card">
        <div class="feature-icon"><i class="fas fa-rocket"></i></div>
        <div class="feature-title">Ultra-moderne</div>
        <p>Interface fluide, notifications, bulletins automatiques, chat privé, canal officiel d'annonces et espaces par rôle.</p>
      </div>
      <div class="feature-card">
        <div class="feature-icon"><i class="fas fa-shield-alt"></i></div>
        <div class="feature-title">Sécurisée & fiable</div>
        <p>Permissions par module, comptes admin/staff/enseignant/parent, sauvegardes SQL et données sous la responsabilité de chaque école.</p>
      </div>
    </div>
  </section>

  <section id="features">
    <h2>Ce que Novaskol vous apporte</h2>
    <div class="features-grid">
      <div class="feature-card">
        <div class="feature-icon"><i class="fas fa-tachometer-alt"></i></div>
        <div class="feature-title">Tableau de bord complet</div>
        <p>Vue 360° : présences, paiements, notes, effectifs, notifications et indicateurs importants.</p>
      </div>
      <div class="feature-card">
        <div class="feature-icon"><i class="fas fa-file-alt"></i></div>
        <div class="feature-title">Bulletins & notes automatisés</div>
        <p>Saisie rapide, calculs instantanés, bulletins trimestriels, annuels, résultats et examens blancs.</p>
      </div>
      <div class="feature-card">
        <div class="feature-icon"><i class="fas fa-comments"></i></div>
        <div class="feature-title">Communication intégrée</div>
        <p>Chat privé, groupes, canal d'annonces de l'école et accès parent encadré.</p>
      </div>
      <div class="feature-card">
        <div class="feature-icon"><i class="fas fa-wallet"></i></div>
        <div class="feature-title">Comptabilité simplifiée</div>
        <p>Suivi des paiements, factures, reçus, revenus, dépenses et rapports imprimables.</p>
      </div>
      <div class="feature-card">
        <div class="feature-icon"><i class="fas fa-calendar-alt"></i></div>
        <div class="feature-title">Emploi du temps & agenda</div>
        <p>Emplois du temps lisibles pour l'administration, les enseignants et les parents selon leurs droits.</p>
      </div>
      <div class="feature-card">
        <div class="feature-icon"><i class="fas fa-user-check"></i></div>
        <div class="feature-title">Présences numériques</div>
        <p>Fiches digitales, rapports, alertes absences.</p>
      </div>
    </div>
  </section>

  <section id="auth" class="auth-section">
    <h2>Espace membre</h2>

    <div class="auth-box active" id="login-form">
      @if ($errors->has('email'))
        <div class="error-msg">{{ $errors->first('email') }}</div>
      @endif
      <form method="POST" action="{{ route('login.attempt') }}">
        @csrf
        <div class="form-group">
          <input type="email" name="email" placeholder="Email" required>
        </div>
        <div class="form-group">
          <select name="role" required>
            <option value="">Votre rôle</option>
            <option value="admin">Administrateur</option>
            <option value="enseignant">Enseignant</option>
            <option value="staff">Personnel</option>
            <option value="parent">Parent</option>
          </select>
        </div>
        <div class="form-group">
          <input type="password" name="password" placeholder="Mot de passe" required>
        </div>
        <button type="submit" class="btn">Se connecter</button>
      </form>

    </div>
  </section>

  <section id="contact">
    <h2>Contactez Novaskol</h2>
    <p style="text-align:center; max-width:720px; margin:0 auto 2.5rem; font-size:1.2rem; opacity:0.9;">
      Une question ? Une démo ? Un partenariat ?<br>
      Nous répondons sous 24h. En mode local, votre message peut être préparé puis envoyé dès qu'une connexion est disponible.
    </p>

    @include('auth.partials.contact-status')

    <div style="max-width:620px; margin:0 auto 4rem;">
      <form action="{{ route('contact.send') }}" method="post">
        @csrf
        <div class="form-group"><input type="text" name="nom" placeholder="Votre nom" required></div>
        <div class="form-group"><input type="email" name="email" placeholder="Votre email" required></div>
        <div class="form-group"><input type="text" name="sujet" placeholder="Sujet" required></div>
        <div class="form-group"><textarea name="message" placeholder="Votre message..." rows="6" required></textarea></div>
        <button type="submit" class="btn">Envoyer</button>
      </form>

      <div style="margin-top: 2.2rem; text-align: center;">
        @include('auth.partials.pending-messages')</div>
    </div>

    <div style="text-align:center; margin:4rem auto; max-width:760px;">
      <h3 style="color:var(--primary); margin-bottom:1.5rem;">Couverture actuelle à Antananarivo et environs</h3>
      <div style="background:rgba(13,17,23,0.7); border:1px solid var(--border); border-radius:16px; padding:1.2rem; box-shadow:0 10px 40px rgba(0,0,0,0.4);">
        <img src="{{ asset('legacy/images/radar-antananarivo.jpg') }}" alt="Couverture réseau / radar Antananarivo" 
             style="width:100%; max-width:680px; border-radius:12px; border:1px solid rgba(0,200,83,0.3);">
        <p style="margin-top:1.2rem; font-size:0.95rem; opacity:0.8;">
          Zone couverte : Antananarivo + périphérie (mise à jour régulière)
        </p>
      </div>
    </div>

    <div style="text-align:center; margin-top:2rem; font-size:1.1rem;">
      <p><strong>Email :</strong> <a href="mailto:novaskol393@gmail.com" style="color:var(--primary);">novaskol393@gmail.com</a></p>
        <p style="margin-top:0.8rem;"><strong>Distribution :</strong> local, réseau privé ou hébergement web</p>
    </div>
  </section>
</main>

<footer>
        &copy; {{ date('Y') }} Novaskol - Solution scolaire moderne - Tous droits réservés
</footer>

<script>
function toggleMenu() {
  document.getElementById('nav').classList.toggle('active');
}

function toggleAuthLanguageMenu() {
  document.getElementById('authLanguageMenu').classList.toggle('active');
}

window.addEventListener('scroll', () => {
  document.getElementById('header').classList.toggle('scrolled', window.scrollY > 80);
});

document.addEventListener('click', (event) => {
  if (!event.target.closest('.auth-lang-wrap')) {
    document.getElementById('authLanguageMenu')?.classList.remove('active');
  }
});

document.querySelectorAll('.auth-lang-option').forEach((button) => {
  button.addEventListener('click', (event) => {
    event.preventDefault();
    event.stopPropagation();
    window.novaskolSetLanguage?.(button.dataset.langCode || 'fr');
    document.getElementById('authLanguageMenu')?.classList.remove('active');
  });
});

document.querySelectorAll('a[href^="#"]').forEach(anchor => {
  anchor.addEventListener('click', function(e) {
    e.preventDefault();
    document.querySelector(this.getAttribute('href')).scrollIntoView({
      behavior: 'smooth'
    });
  });
});

document.addEventListener('DOMContentLoaded', () => {
  const counters = document.querySelectorAll('.stat-number[data-count]');

  const animateCounter = (el) => {
    const target = parseInt(el.getAttribute('data-count'));
    let count = 0;
    const duration = 1800;
    const increment = target / (duration / 16);

    const update = () => {
      count += increment;
      if (count < target) {
        el.textContent = Math.floor(count).toLocaleString('fr-FR');
        requestAnimationFrame(update);
      } else {
        el.textContent = target.toLocaleString('fr-FR');
      }
    };

    const observer = new IntersectionObserver((entries) => {
      if (entries[0].isIntersecting) {
        update();
        observer.disconnect();
      }
    }, { threshold: 0.5 });

    observer.observe(el.closest('.stats'));
  };

  counters.forEach(animateCounter);

  // Scroll auto conditionnel (PHP généré)
  @if ($errors->any())
      document.getElementById('auth').scrollIntoView({behavior:'smooth'});
  @elseif (request('contact'))
      document.getElementById('contact').scrollIntoView({behavior:'smooth'});
  @endif

  document.addEventListener('novaskol:language-changed', () => {
    document.querySelectorAll('.auth-lang-option').forEach((button) => {
      button.classList.toggle('active', button.dataset.langCode === (window.NovaskolI18n?.current?.() || 'fr'));
    });
  });
});
function createParticles() {
  const container = document.getElementById('particles');
  if (!container) return;
  for (let i = 0; i < 40; i++) {
    const p = document.createElement('div');
    p.className = 'particle';
    p.style.left = Math.random() * 100 + '%';
    p.style.top = Math.random() * 100 + '%';
    p.style.animationDuration = (Math.random() * 40 + 20) + 's';
    p.style.animationDelay = Math.random() * 10 + 's';
    p.style.setProperty('--tx', (Math.random() * 80 - 40) + 'vw');
    p.style.setProperty('--ty', (Math.random() * 80 - 40) + 'vh');
    container.appendChild(p);
  }
}
createParticles();

</script>
</body>
</html>



