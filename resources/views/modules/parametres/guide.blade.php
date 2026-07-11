<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Guide d'utilisation - {{ $ecole->nom ?? 'Novaskol' }}</title>
    <link rel="stylesheet" href="{{ asset('legacy/assets/fontawesome/css/all.min.css') }}">
    @include('modules.professeur.bulletin.partials.styles')
    @include('modules.parametres.partials.styles')
    <style>
        .guide-hero{background:linear-gradient(135deg,var(--card),var(--surface));border:1px solid var(--border);border-radius:8px;padding:24px;margin-bottom:18px}
        .guide-hero h2{margin:0 0 8px;color:var(--primary);font-size:1.65rem}.guide-hero p{color:var(--text-sec);max-width:920px;line-height:1.65}
        .guide-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:16px}.guide-card{background:var(--card);border:1px solid var(--border);border-radius:8px;padding:18px}
        .guide-card h3{margin:0 0 12px;color:var(--primary);display:flex;align-items:center;gap:10px}.guide-card ol,.guide-card ul{padding-left:20px;color:var(--text);line-height:1.7}.guide-card li{margin-bottom:6px}
        .warning-box{margin-top:18px;background:rgba(245,158,11,.1);border:1px solid rgba(245,158,11,.35);border-radius:8px;padding:16px;color:var(--text)}
        .guide-flow{display:grid;gap:10px}.flow-step{display:flex;gap:12px;align-items:flex-start;padding:12px;border:1px solid var(--border);border-radius:8px;background:var(--surface)}.flow-step b{display:grid;place-items:center;min-width:30px;height:30px;border-radius:999px;background:var(--primary);color:#062b1d}
        @media(max-width:760px){
            main{padding-left:12px!important;padding-right:12px!important;overflow-x:hidden}
            header h1{font-size:1.05rem;line-height:1.2;text-align:center}
            .guide-hero{padding:16px;margin-bottom:12px}
            .guide-hero h2{font-size:1.18rem;line-height:1.25}
            .guide-hero p,.guide-card li,.warning-box{font-size:.9rem;line-height:1.5}
            .guide-grid{grid-template-columns:1fr;gap:10px}
            .guide-card{padding:14px;border-radius:10px}
            .guide-card h3{font-size:1rem;line-height:1.25}
            .guide-card ol,.guide-card ul{padding-left:18px}
            .flow-step{padding:10px;gap:10px}
            .flow-step b{min-width:28px;height:28px;font-size:.88rem}
        }
    </style>
</head>
<body>
@include('modules.professeur.bulletin.partials.shell')
<header><div class="header-left"><button class="burger-menu" onclick="toggleSidebar()"><i class="fa fa-bars"></i></button><button id="fullscreen-btn" onclick="toggleFullscreen()"><i class="fa fa-expand"></i></button></div><h1><i class="fa fa-life-ring"></i> Guide d'utilisation</h1></header>
<main>
    <section class="guide-hero">
        <h2>Guide complet Novaskol</h2>
        <p>Ce guide accompagne une ecole depuis la premiere configuration jusqu'aux operations sensibles. Il suit l'ordre logique d'utilisation pour eviter les blocages : configurer l'ecole, creer les classes, inscrire les eleves, affecter les matieres, saisir les notes, gerer les paiements puis publier les rapports.</p>
    </section>

    <section class="guide-grid">
        <article class="guide-card">
            <h3><i class="fa fa-flag-checkered"></i> Demarrage</h3>
            <ol>
                <li>Ouvrir Parametres et completer le nom, logo, annee scolaire, devise et langue.</li>
                <li>Creer les comptes staff responsables avant de distribuer les acces.</li>
                <li>Verifier Diagnostic systeme avant une vraie utilisation.</li>
                <li>Creer une premiere sauvegarde apres configuration.</li>
            </ol>
        </article>
        <article class="guide-card">
            <h3><i class="fa fa-users"></i> Eleves et classes</h3>
            <ol>
                <li>Creer les classes dans Liste classes.</li>
                <li>Ajouter les matieres puis les affecter a chaque classe.</li>
                <li>Inscrire les eleves ou utiliser l'importation avec le modele correct.</li>
                <li>Associer les parents dans la fiche eleve si l'espace parent est utilise.</li>
            </ol>
        </article>
        <article class="guide-card">
            <h3><i class="fa fa-book"></i> Notes et bulletins</h3>
            <ol>
                <li>Choisir la classe, periode et annee scolaire.</li>
                <li>Saisir les notes sur 20. La remarque se remplit automatiquement.</li>
                <li>Verifier les resultats avant d'imprimer les bulletins.</li>
                <li>Utiliser Bulletin annuel en fin d'annee.</li>
            </ol>
        </article>
        <article class="guide-card">
            <h3><i class="fa fa-money"></i> Comptabilite</h3>
            <ol>
                <li>Creer les types de paiement et leurs echeances.</li>
                <li>Utiliser Details paiement pour voir complet, partiel et non paye.</li>
                <li>Enregistrer les paiements dans Comptable.</li>
                <li>Imprimer recus, factures et listes uniquement apres verification.</li>
            </ol>
        </article>
        <article class="guide-card">
            <h3><i class="fa fa-shield"></i> Securite</h3>
            <ul>
                <li>Ne pas donner le bloc Comptable aux enseignants.</li>
                <li>Les rapports salaires complets sont reserves admin et staff RH Administration.</li>
                <li>Les enseignants et staff simples voient uniquement leur propre rapport.</li>
                <li>Les presences sont modifiables seulement par les comptes autorises.</li>
            </ul>
        </article>
        <article class="guide-card">
            <h3><i class="fa fa-database"></i> Sauvegarde</h3>
            <ol>
                <li>Faire une sauvegarde chaque semaine minimum.</li>
                <li>Avant mise a jour, creer une sauvegarde manuelle.</li>
                <li>Copier les fichiers `.sql` sur cle USB ou disque externe.</li>
                <li>Ne jamais partager la sauvegarde d'une ecole avec une autre ecole.</li>
            </ol>
        </article>
        <article class="guide-card">
            <h3><i class="fa fa-wifi"></i> Reseau local</h3>
            <ol>
                <li>Choisir un seul PC principal pour garder la base active de Novaskol.</li>
                <li>Ouvrir le module Reseau local pour recuperer l'adresse locale de l'ecole.</li>
                <li>Connecter les autres PC ou telephones au meme Wi-Fi.</li>
                <li>Scanner le QR code ou ouvrir l'adresse locale depuis l'autre appareil.</li>
            </ol>
        </article>
    </section>

    <section class="guide-card" style="margin-top:18px">
        <h3><i class="fa fa-road"></i> Ordre recommande pour une nouvelle ecole</h3>
        <div class="guide-flow">
            <div class="flow-step"><b>1</b><span>Parametres : ecole, devise, annee scolaire, langue, logo.</span></div>
            <div class="flow-step"><b>2</b><span>Administration : classes, matieres, inscriptions eleves, parents.</span></div>
            <div class="flow-step"><b>3</b><span>RH : enseignants, staff, permissions et roles.</span></div>
            <div class="flow-step"><b>4</b><span>Pedagogique : emploi du temps, calendrier, presence eleves.</span></div>
            <div class="flow-step"><b>5</b><span>Professeur : notes, bulletins, resultats et examen blanc.</span></div>
            <div class="flow-step"><b>6</b><span>Comptable : frais, paiements, recus, rapports et sauvegarde.</span></div>
            <div class="flow-step"><b>7</b><span>Reseau local : partager Novaskol sur le meme Wi-Fi pour les autres appareils de l'ecole.</span></div>
        </div>
    </section>

    <div class="warning-box"><strong>Interdiction importante :</strong> ne jamais supprimer une donnee sensible sans sauvegarde recente. Pour les comptes utilisateurs, verifier les permissions avant de donner l'acces a un bloc.</div>
    <footer>&copy; {{ date('Y') }} {{ $ecole->nom ?? 'Ecole' }}</footer>
</main>
<script>function toggleSidebar(){document.getElementById('sidebar').classList.toggle('active')}function toggleSub(e){const n=e.nextElementSibling;if(n)n.style.display=n.style.display==='none'?'block':'none'}function toggleFullscreen(){document.fullscreenElement?document.exitFullscreen():document.documentElement.requestFullscreen()}</script>
</body>
</html>
