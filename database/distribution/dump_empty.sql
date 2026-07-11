-- Novaskol - dump vide de distribution
-- Genere le 2026-05-05 17:43:03

SET FOREIGN_KEY_CHECKS=0;

DROP TABLE IF EXISTS `bulletins`;
CREATE TABLE `bulletins` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_eleve` int DEFAULT NULL,
  `trimestre` int DEFAULT NULL,
  `moyenne` float DEFAULT NULL,
  `mention` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `appreciation` text COLLATE utf8mb4_general_ci,
  PRIMARY KEY (`id`),
  KEY `id_eleve` (`id_eleve`),
  CONSTRAINT `bulletins_ibfk_1` FOREIGN KEY (`id_eleve`) REFERENCES `eleves` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `cache`;
CREATE TABLE `cache` (
  `key` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `cache_locks`;
CREATE TABLE `cache_locks` (
  `key` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `classe_matieres`;
CREATE TABLE `classe_matieres` (
  `id_classe` int NOT NULL,
  `id_matiere` int NOT NULL,
  `coefficient` float NOT NULL DEFAULT '1',
  PRIMARY KEY (`id_classe`,`id_matiere`),
  KEY `id_matiere` (`id_matiere`),
  CONSTRAINT `classe_matieres_ibfk_1` FOREIGN KEY (`id_classe`) REFERENCES `classes` (`id`) ON DELETE CASCADE,
  CONSTRAINT `classe_matieres_ibfk_2` FOREIGN KEY (`id_matiere`) REFERENCES `matieres` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `classes`;
CREATE TABLE `classes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `niveau` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=52 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `conversation_participants`;
CREATE TABLE `conversation_participants` (
  `id` int NOT NULL AUTO_INCREMENT,
  `conversation_id` int NOT NULL,
  `user_type` enum('admin','enseignant','staff','parent') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `user_id` int NOT NULL,
  `joined_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_participant` (`conversation_id`,`user_type`,`user_id`),
  CONSTRAINT `conversation_participants_ibfk_1` FOREIGN KEY (`conversation_id`) REFERENCES `conversations` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=817 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

DROP TABLE IF EXISTS `conversations`;
CREATE TABLE `conversations` (
  `id` int NOT NULL AUTO_INCREMENT,
  `type` enum('private','group') NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `creator_id` int NOT NULL DEFAULT '0',
  `avatar` varchar(255) DEFAULT NULL,
  `is_announcement` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=301 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

DROP TABLE IF EXISTS `departements`;
CREATE TABLE `departements` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `depenses`;
CREATE TABLE `depenses` (
  `id` int NOT NULL AUTO_INCREMENT,
  `type_id` int DEFAULT NULL,
  `personne_id` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `type_personne` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `mois` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `annee_scolaire` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `montant` decimal(10,2) DEFAULT NULL,
  `description` text COLLATE utf8mb4_general_ci,
  `mode_paiement` text COLLATE utf8mb4_general_ci,
  `statut` text COLLATE utf8mb4_general_ci,
  `categorie` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `date_enregistrement` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `nom_personne` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_depenses_annee_mois` (`annee_scolaire`,`mois`),
  KEY `idx_depenses_date` (`date_enregistrement`),
  KEY `idx_depenses_filters` (`type_personne`,`mois`,`annee_scolaire`,`date_enregistrement`),
  KEY `idx_nom_personne` (`nom_personne`)
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `dossiers`;
CREATE TABLE `dossiers` (
  `id` int NOT NULL AUTO_INCREMENT,
  `annee_scolaire` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `mois` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `type_dossier` enum('eleve','enseignant') COLLATE utf8mb4_general_ci DEFAULT NULL,
  `personne_id` int DEFAULT NULL,
  `anarana` text COLLATE utf8mb4_general_ci,
  `description` text COLLATE utf8mb4_general_ci,
  `fichier` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `date_upload` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `ecole`;
CREATE TABLE `ecole` (
  `id` int NOT NULL,
  `nom` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `logo` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `eleves`;
CREATE TABLE `eleves` (
  `id` int NOT NULL AUTO_INCREMENT,
  `matricule` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `nom` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `prenom` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `date_naissance` date DEFAULT NULL,
  `lieu_naissance` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `telephone` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `adresse` text COLLATE utf8mb4_general_ci,
  `numero_acte` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `fonkotany` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `commune` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ecole_ancienne` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `id_classe` int DEFAULT NULL,
  `photo` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `annee_scolaire` varchar(10) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `nom_pere` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `nom_mere` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `distance_domicile` tinyint(1) DEFAULT '0',
  `genre` enum('F','G') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'G',
  `statut` enum('passant','redoublant','nouveau') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'nouveau',
  `est_handicap` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_matricule` (`matricule`),
  KEY `id_classe` (`id_classe`),
  CONSTRAINT `eleves_ibfk_1` FOREIGN KEY (`id_classe`) REFERENCES `classes` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=315 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `emploi_du_temps`;
CREATE TABLE `emploi_du_temps` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_classe` int NOT NULL,
  `data_json` text COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_classe` (`id_classe`),
  CONSTRAINT `emploi_du_temps_ibfk_1` FOREIGN KEY (`id_classe`) REFERENCES `classes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=56 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `enseignants`;
CREATE TABLE `enseignants` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `prenom` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `telephone` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `matiere` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `annee_scolaire` varchar(10) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `date_embauche` date DEFAULT NULL,
  `statut` enum('actif','inactif') COLLATE utf8mb4_general_ci DEFAULT 'actif',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `equipements`;
CREATE TABLE `equipements` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `quantite` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_general_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `evenements`;
CREATE TABLE `evenements` (
  `id` int NOT NULL AUTO_INCREMENT,
  `titre` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `description` text COLLATE utf8mb4_general_ci,
  `type` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `date_debut` datetime NOT NULL,
  `date_fin` datetime NOT NULL,
  `createur_id` int DEFAULT NULL,
  `date_creation` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=75 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `examen_blanc`;
CREATE TABLE `examen_blanc` (
  `id` int NOT NULL AUTO_INCREMENT,
  `eleve_id` int NOT NULL,
  `classe_id` int NOT NULL,
  `matiere_id` int NOT NULL,
  `session` varchar(2) COLLATE utf8mb4_general_ci NOT NULL,
  `note` decimal(4,2) DEFAULT NULL,
  `annee_scolaire` varchar(9) COLLATE utf8mb4_general_ci NOT NULL,
  `date_examen` date NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `eleve_id` (`eleve_id`,`matiere_id`,`session`,`annee_scolaire`),
  KEY `classe_id` (`classe_id`),
  KEY `matiere_id` (`matiere_id`),
  CONSTRAINT `examen_blanc_ibfk_1` FOREIGN KEY (`eleve_id`) REFERENCES `eleves` (`id`) ON DELETE CASCADE,
  CONSTRAINT `examen_blanc_ibfk_2` FOREIGN KEY (`classe_id`) REFERENCES `classes` (`id`) ON DELETE CASCADE,
  CONSTRAINT `examen_blanc_ibfk_3` FOREIGN KEY (`matiere_id`) REFERENCES `matieres` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `fichiers`;
CREATE TABLE `fichiers` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom_fichier` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `chemin` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `job_batches`;
CREATE TABLE `job_batches` (
  `id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `jobs`;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `licence`;
CREATE TABLE `licence` (
  `id` int NOT NULL AUTO_INCREMENT,
  `cle_licence` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `date_debut` date NOT NULL,
  `date_expiration` date NOT NULL,
  `statut` enum('actif','expire') COLLATE utf8mb4_general_ci DEFAULT 'actif',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `matieres`;
CREATE TABLE `matieres` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `coefficient` int DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=59 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `message_reactions`;
CREATE TABLE `message_reactions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `message_id` bigint unsigned NOT NULL,
  `user_id` int unsigned NOT NULL,
  `user_type` enum('admin','professeur','staff','etudiant') COLLATE utf8mb4_unicode_ci NOT NULL,
  `emoji` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_reaction` (`message_id`,`user_id`,`user_type`,`emoji`),
  KEY `idx_message` (`message_id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `messages`;
CREATE TABLE `messages` (
  `id` int NOT NULL AUTO_INCREMENT,
  `conversation_id` int NOT NULL,
  `sender_type` enum('admin','enseignant','staff','parent') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `sender_id` int NOT NULL,
  `content` text,
  `type` enum('text','image','file') DEFAULT 'text',
  `file_path` varchar(255) DEFAULT NULL,
  `file_name` varchar(255) DEFAULT NULL,
  `file_size` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `is_read` tinyint(1) DEFAULT '0',
  `is_delivered` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `conversation_id` (`conversation_id`),
  CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`conversation_id`) REFERENCES `conversations` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=575 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `mpiasa`;
CREATE TABLE `mpiasa` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `prenom` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `type_personne` enum('professeur','staff') COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `telephone` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `annee_scolaire` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `date_inscription` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `notes`;
CREATE TABLE `notes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_eleve` int DEFAULT NULL,
  `id_matiere` int DEFAULT NULL,
  `note` float DEFAULT NULL,
  `trimestre` int DEFAULT NULL,
  `coefficient` int NOT NULL DEFAULT '1',
  `remarque` int DEFAULT NULL,
  `annee_scolaire` varchar(9) COLLATE utf8mb4_general_ci NOT NULL,
  `periode` varchar(2) COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'T1' COMMENT 'B1, B2, B3 for bimestres, T1, T2, T3 for trimestres',
  `type_note` enum('regular','exam') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'regular',
  PRIMARY KEY (`id`),
  KEY `id_eleve` (`id_eleve`),
  KEY `id_matiere` (`id_matiere`),
  CONSTRAINT `fk_notes_eleve` FOREIGN KEY (`id_eleve`) REFERENCES `eleves` (`id`),
  CONSTRAINT `fk_notes_matiere` FOREIGN KEY (`id_matiere`) REFERENCES `matieres` (`id`),
  CONSTRAINT `notes_ibfk_1` FOREIGN KEY (`id_eleve`) REFERENCES `eleves` (`id`),
  CONSTRAINT `notes_ibfk_2` FOREIGN KEY (`id_matiere`) REFERENCES `matieres` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6741 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `notifications`;
CREATE TABLE `notifications` (
  `id` int NOT NULL AUTO_INCREMENT,
  `type` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `message` text COLLATE utf8mb4_general_ci NOT NULL,
  `destinataire_id` int DEFAULT NULL,
  `date_creation` timestamp NULL DEFAULT NULL,
  `statut` enum('non lu','lu') COLLATE utf8mb4_general_ci DEFAULT 'non lu',
  `date_envoi` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `lu` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=238 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `paiements`;
CREATE TABLE `paiements` (
  `id` int NOT NULL AUTO_INCREMENT,
  `type_id` int DEFAULT NULL,
  `personne_id` text COLLATE utf8mb4_general_ci,
  `type_personne` text COLLATE utf8mb4_general_ci,
  `mois` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `annee_scolaire` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `montant` decimal(10,2) DEFAULT NULL,
  `mode_paiement` text COLLATE utf8mb4_general_ci,
  `statut` text COLLATE utf8mb4_general_ci,
  `date_paiement` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `description` text COLLATE utf8mb4_general_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `paiements_assignes`;
CREATE TABLE `paiements_assignes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `type_id` int NOT NULL,
  `eleve_id` int NOT NULL,
  `statut` enum('non_paye','paye') COLLATE utf8mb4_general_ci DEFAULT 'non_paye',
  `type_personne` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `person_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `type_id` (`type_id`),
  KEY `eleve_id` (`eleve_id`),
  CONSTRAINT `paiements_assignes_ibfk_1` FOREIGN KEY (`type_id`) REFERENCES `types_paiements` (`id`),
  CONSTRAINT `paiements_assignes_ibfk_2` FOREIGN KEY (`eleve_id`) REFERENCES `eleves` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=289 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `parametres`;
CREATE TABLE `parametres` (
  `id` int NOT NULL AUTO_INCREMENT,
  `cle` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `valeur` text COLLATE utf8mb4_general_ci,
  PRIMARY KEY (`id`),
  UNIQUE KEY `cle` (`cle`)
) ENGINE=InnoDB AUTO_INCREMENT=212 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `parent_eleves`;
CREATE TABLE `parent_eleves` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `parent_user_id` int unsigned NOT NULL,
  `eleve_id` int unsigned NOT NULL,
  `lien` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'parent',
  `nom_contact` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telephone` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `principal` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `parent_eleves_unique_link` (`parent_user_id`,`eleve_id`),
  KEY `parent_eleves_eleve_id_index` (`eleve_id`),
  KEY `parent_eleves_parent_user_id_index` (`parent_user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `parents`;
CREATE TABLE `parents` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom_pere` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telephone_pere` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `profession_pere` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `adresse_pere` text COLLATE utf8mb4_unicode_ci,
  `nom_mere` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telephone_mere` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `profession_mere` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `adresse_mere` text COLLATE utf8mb4_unicode_ci,
  `telephone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `annee_scolaire` varchar(9) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `permissions`;
CREATE TABLE `permissions` (
  `id` int NOT NULL AUTO_INCREMENT,
  `utilisateur_id` int NOT NULL,
  `role` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `module` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `acces` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `utilisateur_id` (`utilisateur_id`),
  CONSTRAINT `permissions_ibfk_1` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateurs` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1815 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `personnes`;
CREATE TABLE `personnes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `prenom` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `classes` enum('eleve','professeur','staff') COLLATE utf8mb4_general_ci NOT NULL,
  `id_personne` int NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `type_personne` (`classes`,`id_personne`)
) ENGINE=InnoDB AUTO_INCREMENT=69 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `presence_eleves`;
CREATE TABLE `presence_eleves` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `eleve_id` bigint unsigned NOT NULL,
  `classe_id` bigint unsigned NOT NULL,
  `annee_scolaire` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mois` tinyint unsigned NOT NULL,
  `date_jour` date NOT NULL,
  `session_jour` enum('matin','apres_midi') COLLATE utf8mb4_unicode_ci NOT NULL,
  `statut` enum('present','absent','retard') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'present',
  `commentaire` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `presence_eleves_unique_day_session` (`eleve_id`,`date_jour`,`session_jour`),
  KEY `presence_eleves_classe_id_annee_scolaire_mois_index` (`classe_id`,`annee_scolaire`,`mois`),
  KEY `presence_eleves_date_jour_statut_index` (`date_jour`,`statut`)
) ENGINE=MyISAM AUTO_INCREMENT=37 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `presence_personnels`;
CREATE TABLE `presence_personnels` (
  `id` int NOT NULL AUTO_INCREMENT,
  `personne_id` int DEFAULT NULL,
  `annee_scolaire` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `mois` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `date_jour` date DEFAULT NULL,
  `presence` tinyint(1) DEFAULT NULL,
  `date_enregistrement` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `horaire` decimal(10,2) NOT NULL,
  `retard` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=90 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `presence_staff`;
CREATE TABLE `presence_staff` (
  `id` int NOT NULL AUTO_INCREMENT,
  `personne_id` int DEFAULT NULL,
  `annee_scolaire` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `mois` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `date_jour` date DEFAULT NULL,
  `presence` tinyint(1) DEFAULT NULL,
  `date_enregistrement` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `retard` tinyint(1) DEFAULT '0',
  `jours` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `professeurs`;
CREATE TABLE `professeurs` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `prenom` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `photo` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `annee_scolaire` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `date_inscription` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `telephone` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `salaire_horaire` decimal(10,0) NOT NULL,
  `matiere_id` int DEFAULT NULL,
  `diplome_pedagogique` varchar(100) COLLATE utf8mb4_general_ci DEFAULT 'Aucun',
  `autorisation_enseigner` enum('Oui','Non','En cours') COLLATE utf8mb4_general_ci DEFAULT 'Non',
  `annees_experience` int DEFAULT '0',
  `statut` enum('actif','inactif') COLLATE utf8mb4_general_ci DEFAULT 'actif',
  PRIMARY KEY (`id`),
  KEY `matiere_id` (`matiere_id`),
  CONSTRAINT `professeurs_ibfk_1` FOREIGN KEY (`matiere_id`) REFERENCES `matieres` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `professeurs_classes`;
CREATE TABLE `professeurs_classes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `professeur_id` int DEFAULT NULL,
  `classe_id` int DEFAULT NULL,
  `annee_scolaire` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `affectation_type` enum('fixe','flexible') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'fixe',
  `commentaire` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `professeur_id` (`professeur_id`),
  KEY `classe_id` (`classe_id`),
  CONSTRAINT `professeurs_classes_ibfk_1` FOREIGN KEY (`professeur_id`) REFERENCES `professeurs` (`id`),
  CONSTRAINT `professeurs_classes_ibfk_2` FOREIGN KEY (`classe_id`) REFERENCES `classes` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `remarques`;
CREATE TABLE `remarques` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_eleve` int DEFAULT NULL,
  `periode` varchar(10) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `remarque` text COLLATE utf8mb4_general_ci,
  `annee_scolaire` varchar(10) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_eleve` (`id_eleve`),
  CONSTRAINT `remarques_ibfk_1` FOREIGN KEY (`id_eleve`) REFERENCES `eleves` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=128 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `remarques_examen_blanc`;
CREATE TABLE `remarques_examen_blanc` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_eleve` int NOT NULL,
  `session` varchar(2) COLLATE utf8mb4_general_ci NOT NULL,
  `annee_scolaire` varchar(9) COLLATE utf8mb4_general_ci NOT NULL,
  `remarque` text COLLATE utf8mb4_general_ci,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_eleve` (`id_eleve`,`session`,`annee_scolaire`),
  CONSTRAINT `remarques_examen_blanc_ibfk_1` FOREIGN KEY (`id_eleve`) REFERENCES `eleves` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `reservations`;
CREATE TABLE `reservations` (
  `id_salle` int NOT NULL,
  `nom` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `salle` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `date_reservation` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_salle`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `reservations_ressources`;
CREATE TABLE `reservations_ressources` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_salle` int NOT NULL,
  `date_reservation` datetime NOT NULL,
  `utilisateur` int NOT NULL,
  `utilisateur_id` int NOT NULL,
  `heure_debut` time NOT NULL,
  `heure_fin` time NOT NULL,
  `statut` enum('confirmé','annulé') COLLATE utf8mb4_general_ci DEFAULT 'confirmé',
  `description` text COLLATE utf8mb4_general_ci,
  PRIMARY KEY (`id`,`id_salle`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `ressources`;
CREATE TABLE `ressources` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `categorie` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `quantite` int DEFAULT '1',
  `statut` enum('disponible','réservé','en maintenance') COLLATE utf8mb4_general_ci DEFAULT 'disponible',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `retards_personnels`;
CREATE TABLE `retards_personnels` (
  `id` int NOT NULL AUTO_INCREMENT,
  `personne_id` int NOT NULL,
  `annee_scolaire` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `mois` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `date_jour` date NOT NULL,
  `retard` tinyint(1) DEFAULT '0',
  `date_enregistrement` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `revenus`;
CREATE TABLE `revenus` (
  `id` int NOT NULL AUTO_INCREMENT,
  `type_id` int DEFAULT NULL,
  `personne_id` text COLLATE utf8mb4_general_ci,
  `type_personne` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `classes` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `mois` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `annee_scolaire` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `montant` decimal(10,2) DEFAULT NULL,
  `description` text COLLATE utf8mb4_general_ci,
  `mode_paiement` text COLLATE utf8mb4_general_ci,
  `statut` text COLLATE utf8mb4_general_ci,
  `categorie` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `date_enregistrement` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `nom_personne` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_revenus_annee_mois` (`annee_scolaire`,`mois`),
  KEY `idx_revenus_date` (`date_enregistrement`),
  KEY `idx_revenus_filters` (`classes`,`mois`,`annee_scolaire`,`date_enregistrement`),
  KEY `idx_nom_personne` (`nom_personne`)
) ENGINE=InnoDB AUTO_INCREMENT=87 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `roles`;
CREATE TABLE `roles` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `salaires_assignes`;
CREATE TABLE `salaires_assignes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `personne_id` int NOT NULL,
  `type_personne` enum('professeur','staff') COLLATE utf8mb4_unicode_ci NOT NULL,
  `mois` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `annee_scolaire` varchar(9) COLLATE utf8mb4_unicode_ci NOT NULL,
  `statut` enum('non_paye','paye','partiel') COLLATE utf8mb4_unicode_ci DEFAULT 'non_paye',
  `date_creation` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_assignment` (`personne_id`,`type_personne`,`mois`,`annee_scolaire`),
  KEY `idx_annee_type_mois` (`annee_scolaire`,`type_personne`,`mois`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `salles`;
CREATE TABLE `salles` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `capacite` varchar(30) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_general_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `sessions`;
CREATE TABLE `sessions` (
  `id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `staff`;
CREATE TABLE `staff` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `prenom` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(150) COLLATE utf8mb4_general_ci NOT NULL,
  `photo` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `annee_scolaire` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `date_inscription` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `telephone` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `salaire_base` decimal(10,0) NOT NULL,
  `role_id` int DEFAULT NULL,
  `diplome_pedagogique` varchar(100) COLLATE utf8mb4_general_ci DEFAULT 'Aucun',
  `annees_experience` int DEFAULT '0',
  `statut` enum('actif','inactif') COLLATE utf8mb4_general_ci DEFAULT 'actif',
  `departement_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `role_id` (`role_id`),
  CONSTRAINT `staff_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `teacher_lessons`;
CREATE TABLE `teacher_lessons` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `professeur_id` bigint unsigned NOT NULL,
  `classe_id` bigint unsigned DEFAULT NULL,
  `matiere_id` bigint unsigned DEFAULT NULL,
  `annee_scolaire` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `titre` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `rubrique` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_prevue` date DEFAULT NULL,
  `date_realisee` date DEFAULT NULL,
  `statut` enum('a_preparer','planifie','en_cours','termine') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'a_preparer',
  `progression` tinyint unsigned NOT NULL DEFAULT '0',
  `objectifs` text COLLATE utf8mb4_unicode_ci,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `teacher_lessons_professeur_id_annee_scolaire_statut_index` (`professeur_id`,`annee_scolaire`,`statut`),
  KEY `teacher_lessons_classe_id_matiere_id_index` (`classe_id`,`matiere_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `teacher_tasks`;
CREATE TABLE `teacher_tasks` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `professeur_id` bigint unsigned NOT NULL,
  `lesson_id` bigint unsigned DEFAULT NULL,
  `titre` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_echeance` date DEFAULT NULL,
  `priorite` enum('basse','normale','haute') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'normale',
  `termine` tinyint(1) NOT NULL DEFAULT '0',
  `completed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `teacher_tasks_professeur_id_termine_date_echeance_index` (`professeur_id`,`termine`,`date_echeance`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `types_paiements`;
CREATE TABLE `types_paiements` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `montant` decimal(10,2) DEFAULT NULL,
  `mois` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `date_creation` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `classe` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `date_debut` date NOT NULL,
  `date_fin` date NOT NULL,
  `id_classe` int NOT NULL,
  `type_personne` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `person_id` int DEFAULT NULL,
  `annee_scolaire` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `types_paiements_chk_1` CHECK (json_valid(`mois`))
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `typing_status`;
CREATE TABLE `typing_status` (
  `conversation_id` int NOT NULL,
  `user_id` int NOT NULL,
  `user_type` enum('admin','enseignant','staff','parent') NOT NULL DEFAULT 'admin',
  `is_typing` tinyint unsigned NOT NULL DEFAULT '0',
  `last_update` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`conversation_id`,`user_id`,`user_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;


DROP TABLE IF EXISTS `sync_record_keys`;
CREATE TABLE `sync_record_keys` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `table_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `record_id` bigint unsigned NOT NULL,
  `record_uuid` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `checksum` varchar(128) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_seen_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `sync_record_keys_record_uuid_unique` (`record_uuid`),
  UNIQUE KEY `sync_record_keys_table_record_unique` (`table_name`,`record_id`),
  KEY `sync_record_keys_table_name_index` (`table_name`),
  KEY `sync_record_keys_record_id_index` (`record_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `sync_devices`;
CREATE TABLE `sync_devices` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nom` varchar(160) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type_appareil` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pc',
  `role_sync` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'appareil_connecte',
  `plateforme` varchar(80) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `adresse_ip` varchar(80) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code_appairage` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `autorise` tinyint(1) NOT NULL DEFAULT '0',
  `created_by` bigint unsigned DEFAULT NULL,
  `utilisateur_id` bigint unsigned DEFAULT NULL,
  `utilisateur_role` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `paired_at` timestamp NULL DEFAULT NULL,
  `dernier_contact_at` timestamp NULL DEFAULT NULL,
  `last_bootstrap_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `sync_devices_uuid_unique` (`uuid`),
  KEY `sync_devices_code_appairage_index` (`code_appairage`),
  KEY `sync_devices_utilisateur_id_index` (`utilisateur_id`),
  KEY `sync_devices_utilisateur_role_index` (`utilisateur_role`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `sync_batches`;
CREATE TABLE `sync_batches` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `device_uuid` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `direction` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'push',
  `statut` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'en_attente',
  `total_changements` int unsigned NOT NULL DEFAULT '0',
  `total_appliques` int unsigned NOT NULL DEFAULT '0',
  `total_conflits` int unsigned NOT NULL DEFAULT '0',
  `resume_json` longtext COLLATE utf8mb4_unicode_ci,
  `message_erreur` text COLLATE utf8mb4_unicode_ci,
  `demarre_at` timestamp NULL DEFAULT NULL,
  `termine_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `sync_batches_uuid_unique` (`uuid`),
  KEY `sync_batches_device_uuid_index` (`device_uuid`),
  KEY `sync_batches_statut_index` (`statut`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `sync_changes`;
CREATE TABLE `sync_changes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch_uuid` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `device_uuid` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `utilisateur_id` bigint unsigned DEFAULT NULL,
  `module` varchar(80) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `table_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `record_uuid` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `operation` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload_json` longtext COLLATE utf8mb4_unicode_ci,
  `checksum` varchar(128) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `statut` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'en_attente',
  `message_erreur` text COLLATE utf8mb4_unicode_ci,
  `action_at` timestamp NULL DEFAULT NULL,
  `applique_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `sync_changes_uuid_unique` (`uuid`),
  KEY `sync_changes_batch_uuid_index` (`batch_uuid`),
  KEY `sync_changes_device_uuid_index` (`device_uuid`),
  KEY `sync_changes_utilisateur_id_index` (`utilisateur_id`),
  KEY `sync_changes_module_index` (`module`),
  KEY `sync_changes_table_name_index` (`table_name`),
  KEY `sync_changes_record_uuid_index` (`record_uuid`),
  KEY `sync_changes_operation_index` (`operation`),
  KEY `sync_changes_statut_index` (`statut`),
  KEY `sync_changes_action_at_index` (`action_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `sync_conflicts`;
CREATE TABLE `sync_conflicts` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `change_uuid` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `device_uuid` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `table_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `record_uuid` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type_conflit` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'modification_concurrente',
  `donnees_locales_json` longtext COLLATE utf8mb4_unicode_ci,
  `donnees_entrantes_json` longtext COLLATE utf8mb4_unicode_ci,
  `resolution` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `resolu_par` bigint unsigned DEFAULT NULL,
  `resolu_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `sync_conflicts_uuid_unique` (`uuid`),
  KEY `sync_conflicts_change_uuid_index` (`change_uuid`),
  KEY `sync_conflicts_device_uuid_index` (`device_uuid`),
  KEY `sync_conflicts_table_name_index` (`table_name`),
  KEY `sync_conflicts_record_uuid_index` (`record_uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `utilisateurs`;
CREATE TABLE `utilisateurs` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `mot_de_passe` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `avatar` varchar(255) COLLATE utf8mb4_general_ci DEFAULT 'images/default-avatar.png',
  `role` enum('admin','enseignant','staff','parent') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'enseignant',
  `cree_le` datetime DEFAULT CURRENT_TIMESTAMP,
  `last_activity` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

SET FOREIGN_KEY_CHECKS=1;
