<?php

return [
    'edition' => env('NOVASKOL_EDITION', 'principal'),
    'offline_first' => true,

    'legacy' => [
        'source_path' => env('NOVASKOL_LEGACY_PATH', 'G:/wamp64/www/novaskol'),
        'dump_path' => database_path('legacy/dump.sql'),
    ],

    'modules' => [
        'Administration' => ['label' => ' |-- Tableau de bord', 'url' => 'dashboard.php', 'icon' => '', 'section' => true, 'section_icon' => 'fa-dashboard'],
        'dashboard' => ['label' => 'Accueil', 'route' => 'dashboard', 'legacy_url' => 'dashboard.php', 'icon' => 'fa-home'],
        'ecole' => ['label' => 'Ecole', 'route' => 'modules.ecole', 'legacy_url' => 'ecole.php', 'icon' => 'fa-building', 'migrated' => true],

        'Admin' => ['label' => ' |-- Administration', 'url' => 'Inscription_etudiant.php', 'icon' => '', 'section' => true, 'section_icon' => 'fa-briefcase'],
        'inscription' => ['label' => 'Inscription', 'route' => 'modules.inscription', 'legacy_url' => 'Inscription_etudiant.php', 'icon' => 'fa-user-plus', 'migrated' => true],
        'liste_classes' => ['label' => 'Liste classes', 'route' => 'modules.liste-classes', 'legacy_url' => 'Liste_par_classe.php', 'icon' => 'fa-list', 'migrated' => true],
        'matieres' => ['label' => 'Matieres', 'route' => 'modules.matieres', 'legacy_url' => 'admin_matieres.php', 'icon' => 'fa-book', 'migrated' => true],

        'Enseignantss' => ['label' => ' |-- Professeur', 'url' => 'ajout_notes_par_classe.php', 'icon' => '', 'section' => true, 'section_icon' => 'fa-graduation-cap'],
        'notes' => ['label' => 'Ajouts des Notes', 'route' => 'modules.notes', 'legacy_url' => 'ajout_notes_par_classe.php', 'icon' => 'fa-book', 'migrated' => true],
        'bulletin' => ['label' => 'Bulletin', 'route' => 'modules.bulletin', 'legacy_url' => 'automatique_bulletin.php', 'icon' => 'fa-file', 'migrated' => true],
        'resultats' => ['label' => 'Resultat', 'route' => 'modules.resultats', 'legacy_url' => 'resultat_automatique.php', 'icon' => 'fa-line-chart', 'migrated' => true],
        'examen_blanc' => ['label' => 'Examen Blanc', 'route' => 'modules.examen-blanc', 'legacy_url' => 'examen_blanc.php', 'icon' => 'fa-graduation-cap', 'migrated' => true],
        'resultats_examen_blanc' => ['label' => 'Resultats Examen Blanc', 'route' => 'modules.resultats-examen-blanc', 'legacy_url' => 'resultats_examen_blanc.php', 'icon' => 'fa-line-chart', 'migrated' => true],

        'Pedagogique' => ['label' => '|-- Pedagogique', 'url' => 'emploi_du_temps.php', 'icon' => '', 'section' => true, 'section_icon' => 'fa-calendar'],
        'emploi_temps' => ['label' => 'Emploi du temps', 'route' => 'modules.emploi-temps', 'legacy_url' => 'emploi_du_temps.php', 'icon' => 'fa-calendar', 'migrated' => true],
        'fiche_presence' => ['label' => 'Fiche de presence', 'route' => 'modules.presence-etudiant', 'legacy_url' => 'presence_etudiant.php', 'icon' => 'fa-check-circle', 'migrated' => true],
        'calendrier' => ['label' => 'Calendrier academique', 'route' => 'modules.calendrier', 'legacy_url' => 'calendrier_academique.php', 'icon' => 'fa-calendar', 'migrated' => true],
        'notifications' => ['label' => 'Notifications', 'route' => 'modules.notifications', 'legacy_url' => 'notification.php', 'icon' => 'fa-bell', 'migrated' => true],
        'cartes' => ['label' => 'Generation de cartes', 'route' => 'modules.cartes', 'legacy_url' => 'carte.php', 'icon' => 'fa-credit-card', 'migrated' => true],
        'depot_dossier' => ['label' => 'Depot Dossier', 'route' => 'modules.depot-dossier', 'legacy_url' => 'depot_dossier.php', 'icon' => 'fa-download', 'migrated' => true],
        'fpe' => ['label' => 'FPE (Effectifs)', 'route' => 'modules.fpe', 'legacy_url' => 'fpe.php', 'icon' => 'fa-table', 'migrated' => true],
        'liste_assurance' => ['label' => 'Liste Assurance', 'route' => 'modules.liste-assurance', 'legacy_url' => 'liste_assurance.php', 'icon' => 'fa-shield', 'migrated' => true],

        'RH' => ['label' => '|-- Ressource Humaine', 'url' => 'enseignants.php', 'icon' => '', 'section' => true, 'section_icon' => 'fa-users'],
        'enseignants' => ['label' => 'Enseignants', 'route' => 'modules.enseignants', 'legacy_url' => 'enseignants.php', 'icon' => 'fa-user', 'migrated' => true],
        'staff' => ['label' => 'Staff', 'route' => 'modules.staff', 'legacy_url' => 'staff.php', 'icon' => 'fa-users', 'migrated' => true],
        'pointage' => ['label' => 'Pointage unifie', 'route' => 'modules.pointage', 'icon' => 'fa-qrcode', 'migrated' => true],
        'permissions' => ['label' => 'Permissions', 'route' => 'modules.permissions', 'legacy_url' => 'permission.php', 'icon' => 'fa-shield', 'migrated' => true],
        'gestion_ressource' => ['label' => 'Gestion des ressources', 'route' => 'modules.gestion-ressource', 'legacy_url' => 'gestion_ressource.php', 'icon' => 'fa-cubes', 'migrated' => true],

        'Communication' => ['label' => '|-- Communication', 'url' => '#', 'icon' => '', 'section' => true, 'section_icon' => 'fa-comments'],
        'communication' => ['label' => 'Communication', 'route' => 'modules.communication', 'legacy_url' => 'communication.php', 'icon' => 'fa-comments', 'migrated' => true],
        'chat_private' => ['label' => 'Chat Prive', 'route' => 'modules.chat-prive', 'legacy_url' => 'chat_private.php', 'icon' => 'fa-user', 'migrated' => true],
        'chat_group' => ['label' => 'Chat Groupe', 'route' => 'modules.chat-groupe', 'legacy_url' => 'chat_group.php', 'icon' => 'fa-users', 'migrated' => true],

        'Paiement' => ['label' => '|-- Comptable', 'url' => 'detail_payement.php', 'icon' => '', 'section' => true, 'section_icon' => 'fa-money'],
        'detail_paiement' => ['label' => 'Details Paiement', 'route' => 'modules.detail-paiement', 'legacy_url' => 'detail_payement.php', 'icon' => 'fa-money', 'migrated' => true],
        'comptable' => ['label' => 'Comptable', 'route' => 'modules.comptable', 'legacy_url' => 'payement.php', 'icon' => 'fa-bank', 'migrated' => true],
        'liste_paiements' => ['label' => 'Liste des paiements', 'route' => 'modules.liste-paiements', 'legacy_url' => 'liste_paiements.php', 'icon' => 'fa-server', 'migrated' => true],
        'facture' => ['label' => 'Facture & Recu', 'route' => 'modules.facture', 'legacy_url' => 'facture.php', 'icon' => 'fa-file-text', 'migrated' => true],

        'Sectrapport' => ['label' => '|-- Rapport', 'url' => 'rapport_comptable.php', 'icon' => '', 'section' => true, 'section_icon' => 'fa-bar-chart'],
        'rapport_comptable' => ['label' => 'Rapport Comptable', 'route' => 'modules.rapport-comptable', 'legacy_url' => 'rapport_comptable.php', 'icon' => 'fa-signal', 'migrated' => true],
        'rapport_presence' => ['label' => 'Rapport professeur', 'route' => 'modules.rapport-presence', 'legacy_url' => 'rapport_presence.php', 'icon' => 'fa-clipboard', 'migrated' => true],
        'rapport_staff' => ['label' => 'Rapport staff', 'route' => 'modules.rapport-staff', 'legacy_url' => 'rapport_staff.php', 'icon' => 'fa-clipboard', 'migrated' => true],
        'evaluation_notes' => ['label' => 'Evaluation des notes', 'route' => 'modules.evaluation-notes', 'legacy_url' => 'rapports.php', 'icon' => 'fa-bar-chart', 'migrated' => true],

        'Sectrapp' => ['label' => '|-- Parametre', 'url' => 'parametres.php', 'icon' => '', 'section' => true, 'section_icon' => 'fa-cog'],
        'parametres' => ['label' => 'Parametres', 'route' => 'modules.parametres', 'legacy_url' => 'parametres.php', 'icon' => 'fa-cog', 'migrated' => true],
        'comptes_utilisateurs' => ['label' => 'Comptes utilisateurs', 'route' => 'modules.comptes-utilisateurs', 'legacy_url' => '#', 'icon' => 'fa-id-card', 'migrated' => true],
        'diagnostic_systeme' => ['label' => 'Diagnostic systeme', 'route' => 'modules.diagnostic-systeme', 'legacy_url' => '#', 'icon' => 'fa-medkit', 'migrated' => true],
        'apropos_novaskol' => ['label' => 'A propos Novaskol', 'route' => 'modules.apropos-novaskol', 'legacy_url' => '#', 'icon' => 'fa-info-circle', 'migrated' => true],
        'reseau_local' => ['label' => 'Reseau local', 'route' => 'modules.reseau-local', 'legacy_url' => '#', 'icon' => 'fa-wifi', 'migrated' => true],
        'guide_utilisation' => ['label' => 'Guide utilisation', 'route' => 'modules.guide-utilisation', 'legacy_url' => '#', 'icon' => 'fa-life-ring', 'migrated' => true],
        'sauvegardes' => ['label' => 'Sauvegardes', 'route' => 'modules.sauvegardes', 'legacy_url' => 'sauvegarde.php', 'icon' => 'fa-database', 'migrated' => true],
    ],
];
