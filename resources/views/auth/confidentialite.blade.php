<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Politique de confidentialité - Novaskol</title>
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
  <h1>Politique de confidentialité</h1>
  <p class="meta">Dernière mise à jour : 17 juillet 2026</p>

  <h2>Données collectées</h2>
  <p>Dans le cadre de l'utilisation de Novaskol, les données suivantes peuvent être collectées :</p>
  <ul>
    <li><strong>Données d'identification :</strong> nom, prénom, email, rôle, photo de profil</li>
    <li><strong>Données scolaires :</strong> notes, bulletins, présences, emplois du temps</li>
    <li><strong>Données de communication :</strong> messages privés et de groupe</li>
    <li><strong>Données administratives :</strong> paiements, factures, contrats</li>
    <li><strong>Données de connexion :</strong> adresse IP, journaux d'accès</li>
  </ul>

  <h2>Finalités du traitement</h2>
  <p>Les données sont traitées pour :</p>
  <ul>
    <li>La gestion administrative et pédagogique de l'établissement</li>
    <li>La communication entre les membres de l'établissement</li>
    <li>Le suivi des présences et des paiements</li>
    <li>L'amélioration du service</li>
  </ul>

  <h2>Base légale</h2>
  <p>Le traitement repose sur l'exécution d'une mission d'intérêt public ou relevant de l'exercice de l'autorité publique (RGPD art. 6.1.e) et sur le consentement explicite des utilisateurs.</p>

  <h2>Destinataires des données</h2>
  <p>Les données sont accessibles uniquement aux membres autorisés de l'établissement selon leur rôle (admin, enseignant, staff, parent). Aucune donnée n'est revendue à des tiers.</p>

  <h2>Durée de conservation</h2>
  <p>Les données sont conservées pendant toute la durée d'utilisation de la solution par l'établissement. En cas de résiliation, les données peuvent être exportées puis supprimées sous 30 jours.</p>

  <h2>Vos droits</h2>
  <p>Conformément au RGPD, vous disposez des droits suivants :</p>
  <ul>
    <li>Droit d'accès à vos données</li>
    <li>Droit de rectification des données inexactes</li>
    <li>Droit à l'effacement (droit à l'oubli)</li>
    <li>Droit à la limitation du traitement</li>
    <li>Droit à la portabilité des données</li>
    <li>Droit d'opposition au traitement</li>
  </ul>
  <p>Pour exercer ces droits, contactez-nous à : <a href="mailto:novaskol393@gmail.com">novaskol393@gmail.com</a></p>

  <h2>Sécurité</h2>
  <p>Novaskol met en œuvre des mesures techniques et organisationnelles pour protéger vos données : accès par mot de passe (bcrypt), sessions en base de données, isolation Electron (contextIsolation), permissions granulaires par module.</p>

  <hr>
  <p style="text-align:center;"><a href="/">Retour à l'accueil</a></p>
</div>
</body>
</html>