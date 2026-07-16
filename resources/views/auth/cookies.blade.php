<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Politique de cookies - Novaskol</title>
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
  <h1>Politique de cookies</h1>
  <p class="meta">Dernière mise à jour : 17 juillet 2026</p>

  <h2>Que sont les cookies ?</h2>
  <p>Les cookies sont de petits fichiers textes déposés sur votre appareil lors de la consultation d'un site web. Ils permettent de mémoriser vos préférences et d'améliorer votre expérience de navigation.</p>

  <h2>Cookies utilisés sur Novaskol</h2>

  <h3>Cookies strictement nécessaires</h3>
  <p>Ces cookies sont essentiels au fonctionnement du site et ne peuvent pas être désactivés :</p>
  <ul>
    <li><strong>Session Laravel :</strong> cookie de session utilisateur (nommé <code>novaskol_session</code>), supprimé à la fermeture du navigateur</li>
    <li><strong>Token CSRF :</strong> cookie de protection contre les attaques CSRF (nommé <code>XSRF-TOKEN</code>), supprimé à la fermeture du navigateur</li>
  </ul>

  <h3>Cookies de préférence</h3>
  <ul>
    <li><strong>Langue :</strong> mémorise votre choix de langue d'interface (durée : 1 an)</li>
  </ul>

  <h3>Cookies tiers</h3>
  <p>Novaskol n'utilise pas de cookies tiers (analytics, publicité, réseaux sociaux).</p>

  <h2>Durée de conservation</h2>
  <p>Les cookies de session sont supprimés automatiquement à la fermeture du navigateur. Les cookies de préférence sont conservés 1 an maximum.</p>

  <h2>Gestion des cookies</h2>
  <p>Vous pouvez configurer vos préférences de cookies à tout moment depuis les paramètres de votre navigateur :</p>
  <ul>
    <li><a href="https://support.google.com/chrome/answer/95647" target="_blank">Google Chrome</a></li>
    <li><a href="https://support.mozilla.org/kb/effacer-cookies-supprimer-cookies-navigateur" target="_blank">Mozilla Firefox</a></li>
    <li><a href="https://support.apple.com/guide/safari/manage-cookies-and-website-data-sfri11471/mac" target="_blank">Safari</a></li>
    <li><a href="https://support.microsoft.com/edge/delete-cookies" target="_blank">Microsoft Edge</a></li>
  </ul>
  <p>Le refus des cookies strictement nécessaires peut empêcher le bon fonctionnement de l'application.</p>

  <h2>Consentement</h2>
  <p>En utilisant Novaskol, vous consentez à l'utilisation des cookies décrits dans cette politique. Aucun cookie non essentiel n'est déposé sans votre consentement préalable.</p>

  <hr>
  <p style="text-align:center;"><a href="/">Retour à l'accueil</a></p>
</div>
</body>
</html>