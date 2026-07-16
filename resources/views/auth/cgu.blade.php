<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Conditions générales d'utilisation - Novaskol</title>
  <link rel="icon" type="image/png" href="{{ asset('novaskol-icon.png') }}">
  <style>
    :root {
      --primary: #00c853; --primary-dark: #00a843; --bg: #06090f;
      --surface: #0d1117; --card: rgba(13,17,23,0.72);
      --text: #f0f6fc; --text-sec: #8b949e; --border: rgba(48,54,61,0.4);
      --radius: 16px;
    }
    * { margin:0; padding:0; box-sizing:border-box; }
    html { scroll-behavior: smooth; }
    body {
      font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
      background: var(--bg); color: var(--text-sec); line-height: 1.7; font-size: 15px;
    }
    .container { max-width: 820px; margin: 0 auto; padding: 3rem 5% 5rem; }
    h1 { color: var(--text); font-size: 2.2rem; font-weight: 800; margin-bottom: 0.5rem; }
    h2 { color: var(--primary); font-size: 1.3rem; font-weight: 700; margin: 2rem 0 0.8rem; }
    h3 { color: var(--text); font-size: 1rem; font-weight: 600; margin: 1.5rem 0 0.6rem; }
    p, li { font-size: 0.92rem; margin-bottom: 0.6rem; }
    ul { padding-left: 1.4rem; margin-bottom: 1rem; }
    a { color: var(--primary); text-decoration: none; }
    a:hover { text-decoration: underline; }
    hr { border: none; border-top: 1px solid var(--border); margin: 2rem 0; }
    .back { display: inline-block; margin-bottom: 2rem; color: var(--text-sec); font-size: 0.88rem; }
    .back:hover { color: var(--primary); text-decoration: none; }
    .meta { color: var(--text-sec); font-size: 0.85rem; opacity: 0.7; margin-bottom: 2rem; }
  </style>
</head>
<body>
<div class="container">
  <a href="/" class="back">&larr; Retour à l'accueil</a>
  <h1>Conditions générales d'utilisation</h1>
  <p class="meta">Dernière mise à jour : 17 juillet 2026</p>

  <h2>1. Objet</h2>
  <p>Les présentes CGU régissent l'accès et l'utilisation de la solution Novaskol, système de gestion scolaire accessible en ligne sur <a href="https://novaskol.alwaysdata.net">novaskol.alwaysdata.net</a> ou installé localement sur un poste dédié.</p>

  <h2>2. Accès et inscription</h2>
  <p>L'accès à Novaskol est réservé aux établissements scolaires et à leurs membres (administrateurs, enseignants, personnel, parents). Chaque utilisateur doit disposer d'un compte valide créé par un administrateur de l'établissement.</p>
  <p>L'utilisateur s'engage à :</p>
  <ul>
    <li>Fournir des informations exactes lors de son inscription</li>
    <li>Ne pas partager ses identifiants de connexion</li>
    <li>Informer l'administrateur en cas de perte ou vol de mot de passe</li>
  </ul>

  <h2>3. Responsabilités</h2>
  <p>Novaskol met à disposition un outil de gestion. L'établissement est seul responsable :</p>
  <ul>
    <li>De l'exactitude des données saisies</li>
    <li>De la gestion des accès et permissions</li>
    <li>De la conformité de l'utilisation avec la réglementation en vigueur</li>
    <li>De la conservation et de la sécurité de ses propres données</li>
  </ul>

  <h2>4. Propriété intellectuelle</h2>
  <p>Le logiciel Novaskol, son code source, son design et ses contenus sont protégés par le droit d'auteur. Toute reproduction, modification ou redistribution sans autorisation est interdite.</p>

  <h2>5. Données personnelles</h2>
  <p>L'utilisation de Novaskol implique le traitement de données personnelles conformément à la <a href="{{ route('public.confidentialite') }}">Politique de confidentialité</a>.</p>

  <h2>6. Disponibilité</h2>
  <p>Novaskol s'efforce d'assurer une disponibilité maximale du service en ligne. Toutefois, des opérations de maintenance peuvent entraîner des interruptions temporaires. En mode local (desktop), le fonctionnement est indépendant de la connexion internet.</p>

  <h2>7. Modification des CGU</h2>
  <p>Novaskol se réserve le droit de modifier les présentes CGU à tout moment. Les utilisateurs seront informés des modifications substantielles.</p>

  <h2>8. Loi applicable</h2>
  <p>Les présentes CGU sont soumises au droit malgache. Tout litige relève de la compétence des tribunaux d'Antananarivo.</p>

  <hr>
  <p style="text-align:center;"><a href="/">Retour à l'accueil</a></p>
</div>
</body>
</html>