<!DOCTYPE html>
@php
  $interfaceLanguage = DB::table('parametres')->where('cle', 'langue_interface')->value('valeur') ?: 'fr';
@endphp
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Novaskol - Un systeme de gestion scolaire pour chaque ecole</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/png" href="{{ asset('novaskol-icon.png') }}">
<link rel="stylesheet" href="{{ asset('legacy/assets/fontawesome/css/all.min.css') }}">
    <script src="{{ asset('legacy/js/jquery-3.6.0.min.js') }}"></script>
    <script>window.NOVASKOL_INITIAL_LANGUAGE = @json($interfaceLanguage);</script>
    <script src="{{ asset('legacy/js/novaskol-i18n.js') }}"></script>
  <style>
    :root {
      --primary: #00c853;
      --primary-dark: #00b140;
      --dark: #0d1117;
      --darker: #06090f;
      --gray: #c9d1d9;
      --light: #f0f6fc;
            --border: #1f1f2e;
            --scroll-track: #0f0f11;
            --scroll-thumb: #2a2a3a;
            --scroll-thumb-hover: #00c853;
      --glow: rgba(0,200,83,0.18);
    }
        * { margin:0; padding:0; box-sizing:border-box; }
        *::-webkit-scrollbar { width: 3px; }
        *::-webkit-scrollbar-track { background: var(--scroll-track); border-radius: 10px; }
        *::-webkit-scrollbar-thumb { background: var(--scroll-thumb); border-radius: 10px; border: 1px solid var(--scroll-track); }
        *::-webkit-scrollbar-thumb:hover { background: var(--scroll-thumb-hover); }
        * { scrollbar-width: thin; scrollbar-color: var(--scroll-thumb) var(--scroll-track); }
    body {
      font-family: system-ui, -apple-system, sans-serif;
      background: var(--darker);
      color: var(--gray);
      line-height: 1.6;
      min-height: 100vh;
    }
    .bg-gradient {
      position: fixed;
      inset: 0;
      background: radial-gradient(circle at 30% 20%, rgba(0,200,83,0.08) 0%, transparent 40%),
                  radial-gradient(circle at 70% 80%, rgba(0,200,83,0.06) 0%, transparent 50%);
      z-index: -2;
      pointer-events: none;
    }
    header {
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      background: rgba(13,17,23,0.92);
      backdrop-filter: blur(16px);
      border-bottom: 1px solid var(--border);
      z-index: 1000;
      padding: 1.1rem 5%;
      transition: all 0.4s;
    }
    header.scrolled {
      padding: 0.8rem 5%;
      box-shadow: 0 4px 20px rgba(0,0,0,0.4);
    }
    .header-container {
      max-width: 1400px;
      margin: 0 auto;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    .logo {
      font-size: 1.8rem;
      font-weight: 800;
      color: white;
      display: flex;
      align-items: center;
      gap: 0.6rem;
    }
    .logo i { color: var(--primary); }
    nav {
      display: flex;
      gap: 2.2rem;
    }
    nav a {
      color: var(--gray);
      text-decoration: none;
      font-weight: 500;
      transition: 0.3s;
      position: relative;
      padding: 0.4rem 0;
    }
    nav a:hover, nav a.active { color: white; }
    nav a::after {
      content: '';
      position: absolute;
      bottom: 0;
      left: 0;
      width: 0;
      height: 2px;
      background: var(--primary);
      transition: width 0.3s;
    }
    nav a:hover::after, nav a.active::after { width: 100%; }
    .burger {
      display:none;
      font-size:2rem;
      color:var(--text);
      cursor:pointer;
    }
    .header-right {
      display:flex;
      align-items:center;
      gap:0.9rem;
      position:relative;
    }
    .auth-lang-wrap { position:relative; }
    .auth-lang-btn {
      width: 42px;
      height: 42px;
      border-radius: 999px;
      border: 1px solid var(--border);
      background: rgba(13,17,23,0.78);
      color: white;
      display:grid;
      place-items:center;
      cursor:pointer;
      box-shadow: 0 8px 24px rgba(0,0,0,0.24);
    }
    .auth-lang-btn:hover { color: var(--primary); transform: translateY(-1px); }
    .auth-lang-menu {
      position:absolute;
      top:52px;
      right:0;
      width:84px;
      padding:8px;
      border-radius:14px;
      border:1px solid var(--border);
      background: rgba(13,17,23,0.96);
      box-shadow: 0 18px 40px rgba(0,0,0,0.38);
      display:none;
      gap:6px;
    }
    .auth-lang-menu.active { display:grid; }
    .auth-lang-option {
      width:100%;
      padding:8px 10px;
      border-radius:10px;
      border:1px solid transparent;
      background:transparent;
      color:white;
      font-weight:800;
      cursor:pointer;
      text-align:center;
    }
    .auth-lang-option:hover,
    .auth-lang-option.active {
      color: var(--primary);
      background: rgba(0,200,83,0.12);
      border-color: rgba(0,200,83,0.24);
    }
    main { padding-top: 90px; }
    section {
      padding: 6rem 5% 8rem;
      max-width: 1400px;
      margin: 0 auto;
    }
    h1, h2 {
      color: white;
      font-weight: 800;
      line-height: 1.15;
    }
    h1 { font-size: clamp(3.2rem, 8vw, 6.8rem); margin-bottom: 1.8rem; }
    h2 { font-size: clamp(2.4rem, 6vw, 4.2rem); margin-bottom: 2rem; }
    .hero {
      text-align: center;
      padding-top: 8vh;
      min-height: 90vh;
      display: flex;
      flex-direction: column;
      justify-content: center;
    }
    .hero p {
      font-size: 1.2rem;
      max-width: 780px;
      margin: 0 auto 3rem;
      color: white !important;
    }
    .btn {
      display: inline-block;
      padding: 1.1rem 2.4rem;
      background: var(--primary);
      color: black;
      font-weight: 700;
      border-radius: 50px;
      text-decoration: none;
      transition: all 0.35s;
      box-shadow: 0 8px 30px var(--glow);
    }
    .btn:hover {
      transform: translateY(-4px);
      box-shadow: 0 16px 50px var(--glow);
      background: var(--primary-dark);
    }
    .btn-outline {
      background: transparent;
      border: 2px solid var(--primary);
      color: var(--primary);
    }
    .btn-outline:hover {
      background: var(--primary);
      color: black;
    }
    .stats {
      display: flex;
      justify-content: center;
      gap: 4rem;
      flex-wrap: wrap;
      margin: 5rem 0;
    }
    .stat {
      text-align: center;
    }
    .stat-number {
      font-size: 3.8rem;
      font-weight: 800;
      color: var(--primary);
      display: block;
    }
    .stat-label { font-size: 1.1rem; opacity: 0.8; }
    .features-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(340px, 1fr));
      gap: 2.2rem;
      margin: 5rem 0;
    }
    .feature-card {
      background: rgba(13,17,23,0.7);
      border: 1px solid var(--border);
      border-radius: 16px;
      padding: 2.2rem;
      transition: all 0.4s;
      backdrop-filter: blur(10px);
    }
    .feature-card:hover {
      transform: translateY(-12px);
      border-color: var(--primary);
      box-shadow: 0 20px 60px rgba(0,200,83,0.12);
    }
    .feature-icon {
      font-size: 2.8rem;
      color: var(--primary);
      margin-bottom: 1.4rem;
    }
    .feature-title {
      color: white;
      font-size: 1.45rem;
      margin-bottom: 1rem;
    }
    .auth-section {
      background: rgba(13,17,23,0.6);
      backdrop-filter: blur(16px);
      border-radius: 24px;
      padding: 4rem 3rem;
      max-width: 720px;
      margin: 4rem auto;
      border: 1px solid var(--border);
      box-shadow: 0 20px 80px rgba(0,0,0,0.5);
    }
    .auth-box {
      display: none;
    }
    .auth-box.active { display: block; }
    .form-group {
      margin-bottom: 1.6rem;
    }
    input, select, textarea {
      width: 100%;
      padding: 1.1rem 1.4rem;
      background: rgba(40,44,52,0.6);
      border: 1px solid #30363d;
      border-radius: 10px;
      color: white;
      font-size: 1.05rem;
    }
    input:focus, select:focus, textarea:focus {
      outline: none;
      border-color: var(--primary);
      box-shadow: 0 0 0 4px rgba(0,200,83,0.2);
    }
    .error-msg {
      background: rgba(255,80,80,0.18);
      color: #ff8080;
      padding: 1rem;
      border-radius: 10px;
      margin: 1.2rem 0;
      text-align: center;
    }
    .success-msg {
       background: rgb(30 61 21 / 18%);
      color: #80ffaa;
      padding: 1rem;
      border-radius: 10px;
      margin: 1.2rem 0;
      text-align: center;
      max-width: 480px;
      margin: 1.2rem auto;
    }
    .toggle-auth {
      color: var(--primary);
      text-decoration: none;
      font-weight: 500;
      cursor: pointer;
    }
    footer {
      text-align: center;
      padding: 4rem 1rem 2rem;
      color: #666;
      border-top: 1px solid var(--border);
    }
    .floating-particles { position:fixed; inset:0; pointer-events:none; z-index:-1; overflow:hidden; }

.particle { position:absolute; width:4px; height:4px; background:var(--primary); border-radius:50%; opacity:0.6; box-shadow:0 0 15px var(--primary); animation:floatGlow 60s infinite linear; }

@keyframes floatGlow { 0% { transform:translate(0,0) scale(1); opacity:0.6; } 50% { opacity:0.9; } 100% { transform:translate(var(--tx,50vw),var(--ty,50vh)) scale(0.6); opacity:0; } }

    @media (max-width: 1100px) {
      section { padding: 5rem 4% 6rem; }
      .features-grid { grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); }
      .stats { gap: 2rem; }
    }
    @media (max-width: 900px) {
  nav {
    display:none;
    position:absolute;
    top:100%;
    left:0;
    right:0;
    background:var(--darker);
    padding:1.2rem 5%;
    flex-direction:column;
    align-items:center;
    gap:1.6rem;
    border-bottom:1px solid rgba(0,200,83,0.25);
  }

  nav.active {
    display:flex;
  }

  .burger {
    display:block;
  }
      .header-container { gap: 1rem; }
      .logo { font-size: 1.45rem; }
      .stats { gap: 2.5rem; }
      .hero { padding-top: 12vh; }
      .auth-section { padding: 2.2rem 1.2rem; border-radius: 18px; }
    }
    @media (max-width: 640px) {
      header { padding: .72rem 4%; }
      header.scrolled { padding: .62rem 4%; }
      main { padding-top: 70px; }
      section { padding: 2.35rem 4% 3rem; }
      .header-container { min-height: 44px; }
      .logo { font-size: 1.18rem; gap: .45rem; }
      .logo i { font-size: 1.25rem; }
      .auth-lang-btn { width: 38px; height: 38px; }
      .burger { font-size: 1.55rem; line-height: 1; }
      nav { gap: .95rem; padding: .95rem 4%; }
      h1 { font-size: clamp(2.1rem, 11.5vw, 3.15rem); margin-bottom: .9rem; line-height: 1.05; }
      h2 { font-size: clamp(1.65rem, 9vw, 2.2rem); margin-bottom: 1.05rem; }
      .hero {
        min-height: auto;
        padding-top: 2.1rem;
        padding-bottom: 2.2rem;
        justify-content: flex-start;
      }
      .hero p {
        font-size: .92rem;
        line-height: 1.55;
        margin-bottom: 1.25rem;
        max-width: 95%;
      }
      .hero > div[style*="margin: 3rem"] {
        margin: 1.15rem 0 1.35rem !important;
        gap: .75rem !important;
        display: grid !important;
        grid-template-columns: 1fr 1fr;
      }
      .btn {
        width: 100%;
        max-width: none;
        text-align: center;
        padding: .88rem .75rem;
        border-radius: 16px;
        font-size: .88rem;
        box-shadow: 0 8px 24px rgba(0,200,83,.14);
      }
      .stats {
        display: grid;
        grid-template-columns: repeat(3, minmax(0,1fr));
        gap: .65rem;
        margin: 1.25rem 0 0;
      }
      .stat {
        background: rgba(13,17,23,.72);
        border: 1px solid rgba(0,200,83,.16);
        border-radius: 14px;
        padding: .72rem .35rem;
      }
      .stat-number { font-size: 1.55rem; line-height: 1.05; }
      .stat-label {
        display: block;
        margin-top: .25rem;
        font-size: .68rem;
        line-height: 1.2;
      }
      .features-grid {
        grid-template-columns: 1fr;
        gap: .9rem;
        margin: 1.5rem 0;
      }
      .feature-card { padding: 1.1rem; border-radius: 14px; }
      .feature-card:hover { transform: none; }
      .feature-icon { font-size: 1.65rem; margin-bottom: .55rem; }
      .feature-title { font-size: 1.05rem; margin-bottom: .45rem; }
      .feature-card p,
      #about > p,
      #contact > p {
        font-size: .9rem !important;
        line-height: 1.55;
      }
      .auth-section {
        margin: 1.2rem auto;
        padding: 1.35rem 1rem;
      }
      .form-group { margin-bottom: .9rem; }
      input, select, textarea { padding: .9rem 1rem; font-size: .95rem; }
      footer { padding: 2rem 1rem 1.4rem; font-size: .82rem; }
    }
    @media (max-width: 390px) {
      .logo { font-size: 1.05rem; }
      h1 { font-size: 2rem; }
      .hero p { font-size: .86rem; }
      .btn { font-size: .8rem; padding: .82rem .55rem; }
      .stat-number { font-size: 1.35rem; }
      .stat-label { font-size: .62rem; }
    }
  </style>
</head>
<body>
  <div id="particles" class="floating-particles"></div>

<div class="bg-gradient"></div>

<header id="header">
  <div class="header-container">
    <div class="logo">
      <i class="fas fa-graduation-cap"></i> Novaskol
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
    <h1>Novaskol<br>pour chaque ecole</h1>
    <p>Un systeme de gestion scolaire moderne : installable en local, partageable ecole par ecole, et pret a etre heberge en ligne quand l'etablissement le souhaite.</p>

    <div style="margin: 3rem 0; display: flex; gap: 1.6rem; justify-content: center; flex-wrap: wrap;">
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



