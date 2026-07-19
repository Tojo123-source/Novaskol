<!DOCTYPE html>
@php
  $interfaceLanguage = DB::table('parametres')->where('cle', 'langue_interface')->value('valeur') ?: 'fr';
@endphp
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Novaskol - Systeme de gestion scolaire pour chaque ecole</title>
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
      --primary: #00c853; --primary-dark: #00a843; --primary-glow: rgba(0,200,83,0.18);
      --bg: #06090f; --surface: #0d1117; --card: rgba(13,17,23,0.72);
      --text: #f0f6fc; --text-sec: #8b949e; --border: rgba(48,54,61,0.4);
      --radius: 16px; --radius-sm: 10px;
    }
    *{margin:0;padding:0;box-sizing:border-box}
    *::-webkit-scrollbar{width:4px}
    *::-webkit-scrollbar-track{background:var(--bg)}
    *::-webkit-scrollbar-thumb{background:#2a2a3a;border-radius:10px}
    *::-webkit-scrollbar-thumb:hover{background:var(--primary)}
    *{scrollbar-width:thin;scrollbar-color:#2a2a3a var(--bg)}
    html{scroll-behavior:smooth}
    body{
      font-family:'Inter',system-ui,-apple-system,sans-serif;
      background:var(--bg);color:var(--text-sec);
      line-height:1.7;min-height:100vh;
    }
    .bg-gradient{
      position:fixed;inset:0;
      background:
        radial-gradient(ellipse 80% 60% at 20% 20%, rgba(0,200,83,0.07) 0%, transparent 50%),
        radial-gradient(ellipse 60% 70% at 80% 80%, rgba(0,200,83,0.04) 0%, transparent 50%),
        radial-gradient(ellipse 50% 50% at 50% 50%, rgba(0,200,83,0.02) 0%, transparent 70%);
      z-index:-2;pointer-events:none;
    }
    .hero-glow{
      position:absolute;top:-20%;left:50%;transform:translateX(-50%);
      width:90vw;height:80vh;
      background:radial-gradient(ellipse 50% 50% at center, rgba(0,200,83,0.08) 0%, transparent 70%);
      pointer-events:none;z-index:-1;
    }
    header{
      position:fixed;top:0;left:0;right:0;
      background:rgba(6,9,15,0.82);
      backdrop-filter:blur(20px) saturate(1.4);
      -webkit-backdrop-filter:blur(20px) saturate(1.4);
      border-bottom:1px solid var(--border);z-index:1000;
      padding:1rem 5%;transition:all 0.4s cubic-bezier(0.4,0,0.2,1);
    }
    header.scrolled{padding:0.7rem 5%;background:rgba(6,9,15,0.92);box-shadow:0 4px 30px rgba(0,0,0,0.5)}
    .header-container{max-width:1280px;margin:0 auto;display:flex;align-items:center;justify-content:space-between}
    .logo{font-size:1.5rem;font-weight:800;color:white;display:flex;align-items:center;gap:0.6rem;letter-spacing:-0.02em}
    .logo i{color:var(--primary);font-size:1.3rem}
    .logo span{background:linear-gradient(135deg,#fff 40%,var(--primary));-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text}
    nav{display:flex;gap:2rem;align-items:center}
    nav a{
      color:var(--text-sec);text-decoration:none;
      font-weight:500;font-size:0.92rem;
      transition:all 0.3s;position:relative;padding:0.3rem 0;
    }
    nav a:hover,nav a.active{color:white}
    nav a::after{
      content:'';position:absolute;bottom:0;left:0;
      width:0;height:2px;background:var(--primary);
      transition:width 0.3s cubic-bezier(0.4,0,0.2,1);border-radius:2px;
    }
    nav a:hover::after,nav a.active::after{width:100%}
    .burger{display:none;font-size:1.6rem;color:var(--text);cursor:pointer}
    .header-right{display:flex;align-items:center;gap:0.8rem}
    .header-btn{
      display:inline-flex;align-items:center;gap:0.4rem;
      padding:0.5rem 1.2rem;background:var(--primary);color:#06090f;
      font-weight:700;font-size:0.82rem;border-radius:50px;text-decoration:none;
      transition:all 0.3s;border:2px solid transparent;
    }
    .header-btn:hover{background:var(--primary-dark);transform:translateY(-1px)}
    .auth-lang-wrap{position:relative}
    .auth-lang-btn{
      width:40px;height:40px;border-radius:50%;
      border:1px solid var(--border);background:rgba(255,255,255,0.04);
      color:var(--text-sec);display:grid;place-items:center;
      cursor:pointer;transition:all 0.3s;font-size:1rem;
    }
    .auth-lang-btn:hover{color:var(--primary);border-color:var(--primary);background:rgba(0,200,83,0.08)}
    .auth-lang-menu{
      position:absolute;top:48px;right:0;min-width:80px;padding:6px;
      border-radius:var(--radius-sm);border:1px solid var(--border);
      background:rgba(6,9,15,0.96);backdrop-filter:blur(16px);
      box-shadow:0 20px 60px rgba(0,0,0,0.6);display:none;gap:4px;
    }
    .auth-lang-menu.active{display:grid}
    .auth-lang-option{
      width:100%;padding:6px 10px;border-radius:8px;
      border:1px solid transparent;background:transparent;
      color:var(--text-sec);font-weight:700;font-size:0.82rem;
      cursor:pointer;text-align:center;transition:all 0.2s;
    }
    .auth-lang-option:hover,.auth-lang-option.active{
      color:var(--primary);background:rgba(0,200,83,0.1);border-color:rgba(0,200,83,0.2);
    }
    main{padding-top:78px}
    section{padding:5rem 5% 6rem;max-width:1280px;margin:0 auto}
    h1,h2{color:white;font-weight:800;line-height:1.12;letter-spacing:-0.03em}
    h1{font-size:clamp(2.8rem,7vw,5.5rem);margin-bottom:1.5rem}
    h2{font-size:clamp(2rem,5vw,3.5rem);margin-bottom:0.8rem;text-align:center}
    .section-sub{text-align:center;max-width:680px;margin:0 auto 3rem;font-size:1.05rem;opacity:0.8}
    .hero{
      text-align:center;padding-top:6vh;min-height:88vh;
      display:flex;flex-direction:column;justify-content:center;
      position:relative;
    }
    .hero h1 span{background:linear-gradient(135deg,#fff 30%,var(--primary) 100%);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text}
    .hero p{
      font-size:1.15rem;max-width:680px;margin:0 auto 2.5rem;
      color:var(--text-sec) !important;line-height:1.7;
    }
    .btn-group{display:flex;gap:1rem;justify-content:center;flex-wrap:wrap;margin:2.5rem 0}
    .btn{
      display:inline-flex;align-items:center;gap:0.5rem;
      padding:0.95rem 2rem;background:var(--primary);color:#06090f;
      font-weight:700;font-size:0.95rem;border-radius:50px;text-decoration:none;
      transition:all 0.35s cubic-bezier(0.4,0,0.2,1);
      box-shadow:0 8px 30px var(--primary-glow);border:2px solid transparent;
    }
    .btn:hover{transform:translateY(-3px);box-shadow:0 12px 40px var(--primary-glow);background:var(--primary-dark)}
    .btn-outline{background:transparent;border-color:var(--primary);color:var(--primary);box-shadow:none}
    .btn-outline:hover{background:var(--primary);color:#06090f;box-shadow:0 8px 30px var(--primary-glow)}
    .btn-lg{padding:1.1rem 2.5rem;font-size:1.05rem}
    .btn-sm{padding:0.6rem 1.2rem;font-size:0.82rem}
    .stats{display:flex;justify-content:center;gap:3rem;flex-wrap:wrap;margin:3rem 0 0}
    .stat{text-align:center}
    .stat-number{
      font-size:3.2rem;font-weight:900;
      background:linear-gradient(135deg,var(--primary),#69f0ae);
      -webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;
      display:block;line-height:1.1;
    }
    .stat-label{font-size:1rem;color:var(--text-sec);margin-top:0.3rem;display:block}
    .features-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(300px,1fr));gap:1.5rem;margin:3rem 0}
    .feature-card{
      background:var(--card);border:1px solid var(--border);
      border-radius:var(--radius);padding:2rem;
      transition:all 0.4s cubic-bezier(0.4,0,0.2,1);
      backdrop-filter:blur(12px);position:relative;overflow:hidden;
    }
    .feature-card::before{
      content:'';position:absolute;top:0;left:0;right:0;height:2px;
      background:linear-gradient(90deg,transparent,var(--primary),transparent);
      opacity:0;transition:opacity 0.4s;
    }
    .feature-card:hover{transform:translateY(-6px);border-color:rgba(0,200,83,0.3);box-shadow:0 20px 60px rgba(0,0,0,0.3)}
    .feature-card:hover::before{opacity:1}
    .feature-icon{font-size:2.2rem;color:var(--primary);margin-bottom:1.2rem}
    .feature-title{color:white;font-size:1.25rem;font-weight:700;margin-bottom:0.8rem}
    .feature-card p{font-size:0.92rem;line-height:1.65;color:var(--text-sec)}

    .role-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:1.2rem;margin:3rem auto;max-width:1100px}
    .role-card{
      background:var(--card);border:1px solid var(--border);border-radius:var(--radius);
      padding:1.8rem 1.2rem;text-align:center;cursor:default;
      transition:all 0.4s cubic-bezier(0.4,0,0.2,1);position:relative;overflow:hidden;
    }
    .role-card:hover{transform:translateY(-4px);border-color:rgba(0,200,83,0.3)}
    .role-card .role-icon{font-size:2.4rem;margin-bottom:1rem;display:block}
    .role-card .role-name{color:white;font-weight:700;font-size:1rem;margin-bottom:0.4rem}
    .role-card .role-desc{font-size:0.82rem;color:var(--text-sec);line-height:1.5}

    .cta-section{
      text-align:center;padding:4rem 5%;margin:2rem auto;
      background:linear-gradient(135deg,rgba(0,200,83,0.04),rgba(0,200,83,0.01));
      border:1px solid rgba(0,200,83,0.12);border-radius:var(--radius);
      max-width:960px;
    }
    .cta-section h3{color:white;font-size:1.6rem;font-weight:800;margin-bottom:0.6rem}
    .cta-section p{color:var(--text-sec);margin-bottom:1.5rem;font-size:0.95rem}

    .auth-section{
      background:rgba(6,9,15,0.6);
      backdrop-filter:blur(20px) saturate(1.4);
      -webkit-backdrop-filter:blur(20px) saturate(1.4);
      border-radius:20px;padding:3rem;max-width:520px;
      margin:2rem auto;border:1px solid var(--border);
      box-shadow:0 20px 80px rgba(0,0,0,0.5);
    }
    .auth-section h2{margin-bottom:1.5rem}
    .auth-box{display:none}
    .auth-box.active{display:block}
    .form-group{margin-bottom:1.2rem}
    .form-group label{display:block;font-size:0.85rem;font-weight:600;color:var(--text-sec);margin-bottom:0.4rem}
    input,select,textarea{
      width:100%;padding:0.9rem 1.2rem;
      background:rgba(0,0,0,0.6);
      border:1px solid var(--border);border-radius:var(--radius-sm);
      color:white;font-size:0.95rem;font-family:inherit;transition:all 0.3s;
    }
    input:focus,select:focus,textarea:focus{
      outline:none;border-color:var(--primary);
      box-shadow:0 0 0 3px rgba(0,200,83,0.12);background:rgba(0,0,0,0.75);
    }
    input::placeholder{color:#888}
    select option{background:#0d1117;color:white;padding:8px}
    .auth-section .btn{width:100%;justify-content:center;margin-top:0.5rem}
    .error-msg{
      background:rgba(255,80,80,0.12);color:#ff8080;
      padding:0.8rem 1rem;border-radius:var(--radius-sm);
      margin:0.8rem 0;text-align:center;font-size:0.9rem;border:1px solid rgba(255,80,80,0.2);
    }
    .success-msg{
      background:rgba(0,200,83,0.08);color:#69f0ae;
      padding:0.8rem 1rem;border-radius:var(--radius-sm);
      margin:0.8rem auto;text-align:center;font-size:0.9rem;
      max-width:480px;border:1px solid rgba(0,200,83,0.15);
    }

    .whatsapp-btn{
      position:fixed;bottom:24px;right:24px;z-index:9999;
      width:56px;height:56px;border-radius:50%;
      background:#25d366;color:white;border:none;
      display:grid;place-items:center;cursor:pointer;
      font-size:1.6rem;box-shadow:0 4px 20px rgba(37,211,102,0.4);
      transition:all 0.3s cubic-bezier(0.4,0,0.2,1);
      text-decoration:none;
    }
    .whatsapp-btn:hover{transform:scale(1.1);box-shadow:0 8px 30px rgba(37,211,102,0.5)}
    .whatsapp-tooltip{
      position:absolute;right:64px;top:50%;transform:translateY(-50%);
      background:var(--card);color:var(--text);padding:8px 14px;
      border-radius:10px;font-size:0.82rem;white-space:nowrap;
      border:1px solid var(--border);opacity:0;pointer-events:none;
      transition:all 0.3s;
    }
    .whatsapp-btn:hover .whatsapp-tooltip{opacity:1}

    footer{
      text-align:center;padding:3rem 1rem 2rem;
      color:#555;border-top:1px solid var(--border);font-size:0.88rem;
    }
    .footer-grid{
      display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));
      gap:2rem;max-width:960px;margin:0 auto 2rem;text-align:left;
    }
    .footer-col h4{color:var(--text);font-size:0.95rem;font-weight:700;margin-bottom:0.8rem}
    .footer-col a,.footer-col p{color:#555;font-size:0.85rem;text-decoration:none;display:block;margin-bottom:0.4rem}
    .footer-col a:hover{color:var(--primary)}

    .floating-particles{position:fixed;inset:0;pointer-events:none;z-index:-1;overflow:hidden}
    .particle{
      position:absolute;width:3px;height:3px;
      background:var(--primary);border-radius:50%;
      opacity:0.4;box-shadow:0 0 10px var(--primary);
      animation:floatGlow 60s infinite linear;
    }
    @keyframes floatGlow{
      0%{transform:translate(0,0) scale(1);opacity:0.4}
      50%{opacity:0.7}
      100%{transform:translate(var(--tx,50vw),var(--ty,50vh)) scale(0.4);opacity:0}
    }

    .fade-in{opacity:0;transform:translateY(30px);transition:all 0.7s cubic-bezier(0.4,0,0.2,1)}
    .fade-in.visible{opacity:1;transform:translateY(0)}
    .fade-in-left{opacity:0;transform:translateX(-40px);transition:all 0.7s cubic-bezier(0.4,0,0.2,1)}
    .fade-in-left.visible{opacity:1;transform:translateX(0)}
    .fade-in-right{opacity:0;transform:translateX(40px);transition:all 0.7s cubic-bezier(0.4,0,0.2,1)}
    .fade-in-right.visible{opacity:1;transform:translateX(0)}
    .fade-in-scale{opacity:0;transform:scale(0.92);transition:all 0.6s cubic-bezier(0.4,0,0.2,1)}
    .fade-in-scale.visible{opacity:1;transform:scale(1)}
    .stagger-1{transition-delay:0.1s}
    .stagger-2{transition-delay:0.2s}
    .stagger-3{transition-delay:0.3s}
    .stagger-4{transition-delay:0.4s}
    .stagger-5{transition-delay:0.5s}

    @media(max-width:1100px){section{padding:4rem 4% 5rem}.features-grid{grid-template-columns:repeat(auto-fit,minmax(260px,1fr))}}
    @media(max-width:900px){
      nav{display:none;position:absolute;top:100%;left:0;right:0;background:rgba(6,9,15,0.96);padding:1rem 5%;flex-direction:column;align-items:center;gap:1.4rem;border-bottom:1px solid rgba(0,200,83,0.15);backdrop-filter:blur(16px)}
      nav.active{display:flex}
      .header-btn{display:none}
      .burger{display:block}
      .logo{font-size:1.3rem}
      .stats{gap:2rem}
      .hero{padding-top:8vh}
      .hero h1 br{display:none}
      .hero p{font-size:1rem}
      .role-grid{grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:0.8rem}
    }
    @media(max-width:640px){
      header{padding:0.7rem 4%}
      main{padding-top:64px}
      section{padding:2.5rem 4% 3rem}
      .logo{font-size:1.15rem}
      .auth-lang-btn{width:36px;height:36px;font-size:0.9rem}
      .burger{font-size:1.4rem}
      nav{gap:1rem;padding:0.8rem 4%}
      h1{font-size:clamp(2rem,10vw,2.8rem);margin-bottom:0.8rem}
      h2{font-size:clamp(1.5rem,7vw,2rem);margin-bottom:0.8rem}
      .section-sub{font-size:0.92rem;margin-bottom:2rem}
      .hero{min-height:auto;padding-top:3rem;padding-bottom:2rem;justify-content:flex-start}
      .hero p{font-size:0.9rem;line-height:1.55;margin-bottom:1rem}
      .btn-group{gap:0.7rem;margin:1.5rem 0}
      .btn{width:100%;justify-content:center;padding:0.8rem 1.2rem;font-size:0.88rem;border-radius:14px}
      .btn-lg{padding:0.9rem 1.5rem;font-size:1rem}
      .stats{display:grid;grid-template-columns:repeat(3,1fr);gap:0.6rem;margin:1.5rem 0 0}
      .stat{background:var(--card);border:1px solid var(--border);border-radius:12px;padding:0.7rem 0.3rem}
      .stat-number{font-size:1.5rem}
      .stat-label{font-size:0.65rem}
      .features-grid{grid-template-columns:1fr;gap:0.8rem;margin:1.5rem 0}
      .feature-card{padding:1.25rem;border-radius:14px}
      .feature-card:hover{transform:none}
      .feature-icon{font-size:1.6rem;margin-bottom:0.7rem}
      .feature-title{font-size:1.05rem;margin-bottom:0.5rem}
      .feature-card p,#about>p,#contact>p{font-size:0.88rem !important}
      .role-grid{grid-template-columns:repeat(2,1fr);gap:0.6rem}
      .role-card{padding:1.2rem 0.8rem}
      .role-card .role-icon{font-size:1.8rem}
      .role-card .role-name{font-size:0.88rem}
      .role-card .role-desc{font-size:0.78rem}
      .auth-section{margin:1rem auto;padding:1.5rem 1.2rem;border-radius:16px}
      .form-group{margin-bottom:0.8rem}
      input,select,textarea{padding:0.8rem 1rem;font-size:0.9rem}
      .cta-section{padding:2.5rem 1rem;border-radius:14px}
      .cta-section h3{font-size:1.3rem}
      .footer-grid{grid-template-columns:1fr 1fr;gap:1.2rem}
      footer{padding:2rem 1rem 1.5rem;font-size:0.8rem}
      .whatsapp-btn{width:48px;height:48px;font-size:1.4rem;bottom:16px;right:16px}
      .whatsapp-tooltip{display:none}
    }
    @media(max-width:390px){
      .logo{font-size:1rem}
      h1{font-size:1.7rem}
      .hero p{font-size:0.82rem}
      .btn{font-size:0.82rem;padding:0.7rem 0.8rem}
      .stat-number{font-size:1.3rem}
      .stat-label{font-size:0.6rem}
      .role-grid{grid-template-columns:1fr 1fr}
      .auth-section{padding:1rem 0.8rem}
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
      <a href="#espaces">Espaces</a>
      <a href="#features">Fonctionnalités</a>
      <a href="#contact">Contact</a>
    </nav>
    <div class="header-right">
      <a href="#auth" class="header-btn"><i class="fas fa-sign-in-alt"></i> Connexion</a>
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
    <div class="hero-glow"></div>
    <div class="fade-in visible">
      <h1><span>Novaskol</span><br>pour chaque ecole</h1>
      <p>Un systeme de gestion scolaire moderne : installable en local, partageable ecole par ecole, et pret a etre heberge en ligne quand l'etablissement le souhaite.</p>
      <div class="btn-group">
        <a href="#auth" class="btn btn-lg">Commencer maintenant</a>
        <a href="#contact" class="btn btn-outline btn-lg">Demander une demo</a>
      </div>
      <div class="stats">
        <div class="stat"><span class="stat-number" data-count="120">0</span>+<span class="stat-label">Etablissements</span></div>
        <div class="stat"><span class="stat-number" data-count="8500">0</span>+<span class="stat-label">Eleves connectes</span></div>
        <div class="stat"><span class="stat-number" data-count="94">0</span>%<span class="stat-label">Satisfaction</span></div>
      </div>
    </div>
  </section>

  <section id="about">
    <h2 class="fade-in">Qui sommes-nous ?</h2>
    <p class="section-sub fade-in stagger-1">
      Creee a Antananarivo, Novaskol repond a un besoin simple : permettre aux ecoles de gerer leurs donnees elles-memes, meme sans connexion permanente.
    </p>
    <div class="features-grid">
      <div class="feature-card fade-in stagger-1">
        <div class="feature-icon"><i class="fas fa-heart"></i></div>
        <div class="feature-title">Pensee pour les ecoles modernes</div>
        <p>Fonctionnement local, sauvegardes, paiements echelonnes, calendriers scolaires et modules adaptes aux realites des etablissements.</p>
      </div>
      <div class="feature-card fade-in stagger-2">
        <div class="feature-icon"><i class="fas fa-rocket"></i></div>
        <div class="feature-title">Ultra-moderne</div>
        <p>Interface fluide, notifications, bulletins automatiques, chat prive, canal officiel d'annonces et espaces par role.</p>
      </div>
      <div class="feature-card fade-in stagger-3">
        <div class="feature-icon"><i class="fas fa-shield-alt"></i></div>
        <div class="feature-title">Securisee & fiable</div>
        <p>Permissions par module, comptes admin/staff/enseignant/parent, sauvegardes SQL et donnees sous la responsabilite de chaque ecole.</p>
      </div>
    </div>
  </section>

  <section id="espaces">
    <h2 class="fade-in">5 espaces, un seul logiciel</h2>
    <p class="section-sub fade-in stagger-1">Chaque acteur de l'etablissement dispose d'un espace dedie avec les outils adaptes a son role.</p>
    <div class="role-grid">
      <div class="role-card fade-in-scale stagger-1">
        <span class="role-icon">👨‍💼</span>
        <div class="role-name">Chef d'etablissement</div>
        <div class="role-desc">Tableau de bord 360°, gestion des permissions, rapports comptables et pedagogiques.</div>
      </div>
      <div class="role-card fade-in-scale stagger-2">
        <span class="role-icon">👨‍🏫</span>
        <div class="role-name">Enseignant</div>
        <div class="role-desc">Saisie des notes, pointage de presence, consultation emploi du temps, espace de travail.</div>
      </div>
      <div class="role-card fade-in-scale stagger-3">
        <span class="role-icon">👥</span>
        <div class="role-name">Personnel (Staff)</div>
        <div class="role-desc">RH, comptabilite, gestion des ressources, pointage et suivi administratif.</div>
      </div>
      <div class="role-card fade-in-scale stagger-4">
        <span class="role-icon">👨‍👩‍👧</span>
        <div class="role-name">Parent</div>
        <div class="role-desc">Suivi des eleves, messagerie, notifications, consultation des notes et paiements.</div>
      </div>
      <div class="role-card fade-in-scale stagger-5">
        <span class="role-icon">🎓</span>
        <div class="role-name">Eleve</div>
        <div class="role-desc">Acces aux notes, emploi du temps, communications et suivi de la scolarite.</div>
      </div>
    </div>
    <div class="cta-section fade-in">
      <h3>Pret a passer a la vitesse superieure ?</h3>
      <p>Decouvrez comment Novaskol peut transformer la gestion de votre etablissement.</p>
      <a href="#auth" class="btn btn-lg">Reservez votre demo</a>
    </div>
  </section>

  <section id="features">
    <h2 class="fade-in">Ce que Novaskol vous apporte</h2>
    <p class="section-sub fade-in stagger-1">Une solution complete pour la gestion scolaire, de l'inscription a la comptabilite.</p>
    <div class="features-grid">
      <div class="feature-card fade-in stagger-1">
        <div class="feature-icon"><i class="fas fa-tachometer-alt"></i></div>
        <div class="feature-title">Tableau de bord complet</div>
        <p>Vue 360° : presences, paiements, notes, effectifs, notifications et indicateurs importants.</p>
      </div>
      <div class="feature-card fade-in stagger-2">
        <div class="feature-icon"><i class="fas fa-file-alt"></i></div>
        <div class="feature-title">Bulletins & notes automatises</div>
        <p>Saisie rapide, calculs instantanes, bulletins trimestriels, annuels, resultats et examens blancs.</p>
      </div>
      <div class="feature-card fade-in stagger-3">
        <div class="feature-icon"><i class="fas fa-comments"></i></div>
        <div class="feature-title">Communication integree</div>
        <p>Chat prive, groupes, canal d'annonces de l'ecole et acces parent encadre.</p>
      </div>
      <div class="feature-card fade-in stagger-1">
        <div class="feature-icon"><i class="fas fa-wallet"></i></div>
        <div class="feature-title">Comptabilite simplifiee</div>
        <p>Suivi des paiements, factures, recus, revenus, depenses et rapports imprimables.</p>
      </div>
      <div class="feature-card fade-in stagger-2">
        <div class="feature-icon"><i class="fas fa-calendar-alt"></i></div>
        <div class="feature-title">Emploi du temps & agenda</div>
        <p>Emplois du temps lisibles pour l'administration, les enseignants et les parents selon leurs droits.</p>
      </div>
      <div class="feature-card fade-in stagger-3">
        <div class="feature-icon"><i class="fas fa-user-check"></i></div>
        <div class="feature-title">Presences numeriques</div>
        <p>Fiches digitales, rapports, alertes absences et pointage par QR code.</p>
      </div>
    </div>
  </section>

  <div class="cta-section fade-in">
    <h3>Vous voulez en savoir plus ?</h3>
    <p>Contactez-nous pour une demonstration personnalisee de Novaskol.</p>
    <a href="#contact" class="btn btn-lg btn-outline">Demander une demo</a>
  </div>

  <section id="auth" class="auth-section fade-in">
    <h2>Espace membre</h2>
    <div class="auth-box active" id="login-form">
      @if ($errors->has('email'))
        <div class="error-msg">{{ $errors->first('email') }}</div>
      @endif
      <form method="POST" action="{{ route('login.attempt') }}">
        @csrf
        <div class="form-group"><input type="email" name="email" placeholder="Email" required></div>
        <div class="form-group">
          <select name="role" required>
            <option value="">Votre role</option>
            <option value="admin">Administrateur</option>
            <option value="enseignant">Enseignant</option>
            <option value="staff">Personnel</option>
            <option value="parent">Parent</option>
            <option value="eleve">Eleve</option>
          </select>
        </div>
        <div class="form-group"><input type="password" name="password" placeholder="Mot de passe" required></div>
        <button type="submit" class="btn">Se connecter</button>
      </form>
    </div>
  </section>

  <section id="contact">
    <h2 class="fade-in">Contactez Novaskol</h2>
    <p class="section-sub fade-in stagger-1">
      Une question ? Une demo ? Un partenariat ?<br>
      Nous repondons sous 24h.
    </p>

    @include('auth.partials.contact-status')

    <div class="fade-in" style="max-width:620px;margin:0 auto 4rem">
      <form action="{{ route('contact.send') }}" method="post">
        @csrf
        <div class="form-group"><input type="text" name="nom" placeholder="Votre nom" required></div>
        <div class="form-group"><input type="email" name="email" placeholder="Votre email" required></div>
        <div class="form-group"><input type="text" name="sujet" placeholder="Sujet" required></div>
        <div class="form-group"><textarea name="message" placeholder="Votre message..." rows="6" required></textarea></div>
        <button type="submit" class="btn">Envoyer</button>
      </form>
      <div style="margin-top:2.2rem;text-align:center">@include('auth.partials.pending-messages')</div>
    </div>

    <div class="fade-in" style="text-align:center;margin:4rem auto;max-width:760px">
      <h3 style="color:var(--primary);margin-bottom:1.5rem">Couverture actuelle a Antananarivo et environs</h3>
      <div style="background:rgba(13,17,23,0.7);border:1px solid var(--border);border-radius:16px;padding:1.2rem;box-shadow:0 10px 40px rgba(0,0,0,0.4)">
        <img src="{{ asset('legacy/images/radar-antananarivo.jpg') }}" alt="Couverture reseau / radar Antananarivo"
             style="width:100%;max-width:680px;border-radius:12px;border:1px solid rgba(0,200,83,0.3)">
        <p style="margin-top:1.2rem;font-size:0.95rem;opacity:0.8">Zone couverte : Antananarivo + peripherie (mise a jour reguliere)</p>
      </div>
    </div>
  </section>
</main>

<a href="https://wa.me/261387729958?text=Bonjour%20Novaskol%2C%20je%20souhaite%20en%20savoir%20plus%20sur%20votre%20solution" class="whatsapp-btn" target="_blank" title="Contactez-nous sur WhatsApp">
  <i class="fab fa-whatsapp"></i>
  <span class="whatsapp-tooltip">Besoin d'aide ?</span>
</a>

<footer>
  <div class="footer-grid">
    <div class="footer-col">
      <h4>Novaskol</h4>
      <p>Solution de gestion scolaire moderne, installable en local et hebergeable en ligne.</p>
    </div>
    <div class="footer-col">
      <h4>Liens</h4>
      <a href="#hero">Accueil</a>
      <a href="#about">A propos</a>
      <a href="#espaces">Espaces</a>
      <a href="#features">Fonctionnalites</a>
      <a href="#contact">Contact</a>
    </div>
    <div class="footer-col">
      <h4>Legal</h4>
      <a href="{{ route('public.mentions-legales') }}">Mentions legales</a>
      <a href="{{ route('public.confidentialite') }}">Confidentialite</a>
      <a href="{{ route('public.cgu') }}">CGU</a>
      <a href="{{ route('public.cookies') }}">Cookies</a>
    </div>
    <div class="footer-col">
      <h4>Contact</h4>
      <p><i class="fas fa-user" style="width:16px;color:var(--primary)"></i> RANDRIAMIFALY Tojo Nambinina</p>
      <p><i class="fas fa-envelope" style="width:16px;color:var(--primary)"></i> tojo.devpro@gmail.com</p>
      <p><i class="fab fa-whatsapp" style="width:16px;color:#25d366"></i> +261 38 772 9958</p>
    </div>
  </div>
  <div style="border-top:1px solid var(--border);padding-top:1.5rem;margin-top:1rem">
    &copy; {{ date('Y') }} Novaskol - Solution scolaire moderne - Developpe par RANDRIAMIFALY Tojo Nambinina
  </div>
</footer>

<script>
function toggleMenu(){document.getElementById('nav').classList.toggle('active')}
function toggleAuthLanguageMenu(){document.getElementById('authLanguageMenu').classList.toggle('active')}

window.addEventListener('scroll',()=>{document.getElementById('header').classList.toggle('scrolled',window.scrollY>80)})

document.addEventListener('click',(e)=>{if(!e.target.closest('.auth-lang-wrap'))document.getElementById('authLanguageMenu')?.classList.remove('active')})

document.querySelectorAll('.auth-lang-option').forEach(b=>{b.addEventListener('click',e=>{e.preventDefault();e.stopPropagation();window.novaskolSetLanguage?.(b.dataset.langCode||'fr');document.getElementById('authLanguageMenu')?.classList.remove('active')})})

document.querySelectorAll('a[href^="#"]').forEach(a=>{a.addEventListener('click',function(e){e.preventDefault();document.querySelector(this.getAttribute('href'))?.scrollIntoView({behavior:'smooth'})})})

document.addEventListener('DOMContentLoaded',()=>{
  const observer=new IntersectionObserver((entries)=>{entries.forEach(e=>{if(e.isIntersecting)e.target.classList.add('visible')})},{threshold:0.1})
  document.querySelectorAll('.fade-in,.fade-in-left,.fade-in-right,.fade-in-scale').forEach(el=>observer.observe(el))

  const counters=document.querySelectorAll('.stat-number[data-count]')
  counters.forEach(el=>{
    const target=parseInt(el.getAttribute('data-count'))
    let count=0;const duration=1800;const inc=target/(duration/16)
    const cb=new IntersectionObserver((entries)=>{
      if(entries[0].isIntersecting){
        const update=()=>{count+=inc;if(count<target){el.textContent=Math.floor(count).toLocaleString('fr-FR');requestAnimationFrame(update)}else el.textContent=target.toLocaleString('fr-FR')}
        update();cb.disconnect()
      }
    },{threshold:0.5})
    cb.observe(el.closest('.stats'))
  })

  @if ($errors->any()) document.getElementById('auth').scrollIntoView({behavior:'smooth'})
  @elseif (request('contact')) document.getElementById('contact').scrollIntoView({behavior:'smooth'})
  @endif

  document.addEventListener('novaskol:language-changed',()=>{document.querySelectorAll('.auth-lang-option').forEach(b=>{b.classList.toggle('active',b.dataset.langCode===(window.NovaskolI18n?.current?.()||'fr'))})})
})

function createParticles(){
  const c=document.getElementById('particles');if(!c)return
  for(let i=0;i<40;i++){const p=document.createElement('div');p.className='particle';p.style.left=Math.random()*100+'%';p.style.top=Math.random()*100+'%';p.style.animationDuration=(Math.random()*40+20)+'s';p.style.animationDelay=Math.random()*10+'s';p.style.setProperty('--tx',(Math.random()*80-40)+'vw');p.style.setProperty('--ty',(Math.random()*80-40)+'vh');c.appendChild(p)}
}
createParticles();
</script>
</body>
</html>