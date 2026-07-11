-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: May 04, 2026 at 08:24 AM
-- Server version: 8.0.31
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bulletin_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `bulletins`
--

DROP TABLE IF EXISTS `bulletins`;
CREATE TABLE IF NOT EXISTS `bulletins` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_eleve` int DEFAULT NULL,
  `trimestre` int DEFAULT NULL,
  `moyenne` float DEFAULT NULL,
  `mention` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `appreciation` text COLLATE utf8mb4_general_ci,
  PRIMARY KEY (`id`),
  KEY `id_eleve` (`id_eleve`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `classes`
--

DROP TABLE IF EXISTS `classes`;
CREATE TABLE IF NOT EXISTS `classes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `niveau` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=52 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `classes`
--

INSERT INTO `classes` (`id`, `nom`, `niveau`) VALUES
(1, 'PS', NULL),
(2, 'MS', NULL),
(3, 'GS', NULL),
(4, 'CP', NULL),
(5, 'CE1', NULL),
(6, 'CE2', NULL),
(7, 'CM1', NULL),
(8, 'CM2', NULL),
(9, '6e', NULL),
(10, '5e', NULL),
(11, '4e', NULL),
(12, '3e', NULL),
(13, '2nde', NULL),
(14, '1ère', NULL),
(16, 'TA', 15),
(17, 'TL', 15),
(18, 'TD', 15),
(20, 'TS', 15),
(21, 'TOSE', 15),
(50, 'TC', NULL),
(51, 'Cours', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `classe_matieres`
--

DROP TABLE IF EXISTS `classe_matieres`;
CREATE TABLE IF NOT EXISTS `classe_matieres` (
  `id_classe` int NOT NULL,
  `id_matiere` int NOT NULL,
  `coefficient` float NOT NULL DEFAULT '1',
  PRIMARY KEY (`id_classe`,`id_matiere`),
  KEY `id_matiere` (`id_matiere`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `classe_matieres`
--

INSERT INTO `classe_matieres` (`id_classe`, `id_matiere`, `coefficient`) VALUES
(2, 2, 1),
(2, 3, 2),
(2, 5, 2),
(2, 7, 3),
(2, 16, 2),
(14, 2, 1),
(14, 3, 1),
(14, 16, 1),
(14, 17, 1),
(14, 20, 1),
(14, 21, 1),
(16, 3, 1),
(16, 4, 1),
(16, 16, 1),
(16, 17, 1),
(16, 20, 1),
(16, 40, 1);

-- --------------------------------------------------------

--
-- Table structure for table `conversations`
--

DROP TABLE IF EXISTS `conversations`;
CREATE TABLE IF NOT EXISTS `conversations` (
  `id` int NOT NULL AUTO_INCREMENT,
  `type` enum('private','group') NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `creator_id` int NOT NULL DEFAULT '0',
  `avatar` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=296 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `conversations`
--

INSERT INTO `conversations` (`id`, `type`, `name`, `creator_id`, `avatar`, `created_at`, `updated_at`) VALUES
(254, 'private', NULL, 0, NULL, '2026-02-11 17:29:11', '2026-02-11 17:29:11'),
(255, 'private', NULL, 0, NULL, '2026-02-11 17:29:20', '2026-02-11 17:29:20'),
(256, 'private', NULL, 0, NULL, '2026-02-11 17:30:36', '2026-03-08 18:14:06'),
(257, 'private', NULL, 0, NULL, '2026-02-11 17:33:55', '2026-02-14 12:17:00'),
(258, 'private', NULL, 0, NULL, '2026-02-11 19:42:20', '2026-04-20 16:54:47'),
(259, 'private', NULL, 0, NULL, '2026-02-11 19:42:21', '2026-02-11 19:42:21'),
(260, 'private', NULL, 0, NULL, '2026-02-11 19:42:25', '2026-02-11 19:42:25'),
(261, 'private', NULL, 0, NULL, '2026-02-11 19:48:38', '2026-02-17 04:04:49'),
(262, 'private', NULL, 0, NULL, '2026-02-11 19:48:39', '2026-02-11 19:48:39'),
(263, 'private', NULL, 0, NULL, '2026-02-11 19:48:39', '2026-02-17 04:03:20'),
(264, 'private', NULL, 0, NULL, '2026-02-11 19:48:40', '2026-02-11 19:48:40'),
(265, 'private', NULL, 0, NULL, '2026-02-11 19:48:40', '2026-02-11 19:48:40'),
(266, 'private', NULL, 0, NULL, '2026-02-11 19:48:41', '2026-02-13 21:54:47'),
(267, 'private', NULL, 0, NULL, '2026-02-11 19:48:41', '2026-02-11 19:48:41'),
(268, 'private', NULL, 0, NULL, '2026-02-11 20:01:48', '2026-02-11 20:01:48'),
(269, 'private', NULL, 0, NULL, '2026-02-11 20:23:36', '2026-02-11 20:23:36'),
(270, 'group', 'Général', 16, NULL, '2026-02-11 22:09:30', '2026-02-12 18:43:23'),
(271, 'group', 'Général - École', 16, NULL, '2026-02-11 22:17:50', '2026-02-20 05:32:25'),
(272, 'group', 'dert', 16, NULL, '2026-02-11 22:19:40', '2026-02-12 18:43:23'),
(273, 'group', 'Math', 16, NULL, '2026-02-11 22:51:48', '2026-02-12 18:43:23'),
(274, 'group', 'Team', 16, '1771010079_698f781fbd57d.jpg', '2026-02-11 22:59:14', '2026-02-13 19:21:34'),
(275, 'private', NULL, 0, NULL, '2026-02-12 04:34:31', '2026-02-28 12:33:46'),
(276, 'private', NULL, 0, NULL, '2026-02-12 10:53:25', '2026-02-26 13:20:25'),
(277, 'private', NULL, 0, NULL, '2026-02-12 10:53:25', '2026-02-13 20:40:26'),
(278, 'private', NULL, 0, NULL, '2026-02-12 10:53:27', '2026-02-12 10:53:27'),
(279, 'private', NULL, 0, NULL, '2026-02-12 15:06:43', '2026-02-26 17:38:38'),
(280, 'private', NULL, 0, NULL, '2026-02-12 17:00:54', '2026-02-12 17:00:54'),
(281, 'private', NULL, 0, NULL, '2026-02-12 17:00:57', '2026-02-27 21:23:46'),
(282, 'private', NULL, 0, NULL, '2026-02-12 17:02:09', '2026-02-12 17:02:09'),
(283, 'group', 'Administrateur', 14, NULL, '2026-02-12 18:46:15', '2026-02-14 11:18:31'),
(284, 'private', NULL, 0, NULL, '2026-02-13 04:10:09', '2026-02-13 04:10:09'),
(285, 'private', NULL, 0, NULL, '2026-02-13 04:10:11', '2026-02-13 04:10:11'),
(286, 'private', NULL, 0, NULL, '2026-02-13 04:10:11', '2026-02-13 04:10:11'),
(287, 'private', NULL, 0, NULL, '2026-02-13 04:10:12', '2026-02-13 04:10:12'),
(288, 'private', NULL, 0, NULL, '2026-02-13 04:10:13', '2026-02-13 04:10:13'),
(289, 'private', NULL, 0, NULL, '2026-02-13 04:10:13', '2026-02-13 04:10:13'),
(290, 'private', NULL, 0, NULL, '2026-02-13 04:10:15', '2026-02-13 04:10:15'),
(291, 'private', NULL, 0, NULL, '2026-02-13 04:10:16', '2026-02-13 04:10:16'),
(292, 'group', 'RH', 19, '1771010063_698f780f2a2c7.png', '2026-02-13 17:29:43', '2026-02-13 19:14:23'),
(293, 'private', NULL, 0, NULL, '2026-02-14 09:40:03', '2026-02-14 09:40:03'),
(294, 'private', NULL, 0, NULL, '2026-02-14 09:40:04', '2026-02-14 09:40:04'),
(295, 'private', NULL, 0, NULL, '2026-02-14 09:42:15', '2026-02-14 11:38:22');

-- --------------------------------------------------------

--
-- Table structure for table `conversation_participants`
--

DROP TABLE IF EXISTS `conversation_participants`;
CREATE TABLE IF NOT EXISTS `conversation_participants` (
  `id` int NOT NULL AUTO_INCREMENT,
  `conversation_id` int NOT NULL,
  `user_type` enum('admin','enseignant','staff','parent') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `user_id` int NOT NULL,
  `joined_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_participant` (`conversation_id`,`user_type`,`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=585 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `conversation_participants`
--

INSERT INTO `conversation_participants` (`id`, `conversation_id`, `user_type`, `user_id`, `joined_at`) VALUES
(483, 254, 'admin', 16, '2026-02-11 17:29:11'),
(484, 254, 'enseignant', 15, '2026-02-11 17:29:11'),
(485, 255, 'enseignant', 15, '2026-02-11 17:29:20'),
(486, 255, 'parent', 21, '2026-02-11 17:29:20'),
(487, 256, 'enseignant', 15, '2026-02-11 17:30:36'),
(488, 256, 'admin', 14, '2026-02-11 17:30:36'),
(489, 257, 'admin', 16, '2026-02-11 17:33:55'),
(490, 257, 'enseignant', 17, '2026-02-11 17:33:55'),
(491, 258, 'admin', 16, '2026-02-11 19:42:20'),
(492, 258, 'admin', 19, '2026-02-11 19:42:20'),
(493, 259, 'admin', 16, '2026-02-11 19:42:21'),
(494, 259, 'admin', 18, '2026-02-11 19:42:21'),
(495, 260, 'admin', 16, '2026-02-11 19:42:25'),
(496, 260, 'parent', 21, '2026-02-11 19:42:25'),
(497, 261, 'staff', 20, '2026-02-11 19:48:38'),
(498, 261, 'admin', 14, '2026-02-11 19:48:38'),
(499, 262, 'staff', 20, '2026-02-11 19:48:39'),
(500, 262, 'enseignant', 15, '2026-02-11 19:48:39'),
(501, 263, 'staff', 20, '2026-02-11 19:48:39'),
(502, 263, 'admin', 16, '2026-02-11 19:48:39'),
(503, 264, 'staff', 20, '2026-02-11 19:48:40'),
(504, 264, 'enseignant', 17, '2026-02-11 19:48:40'),
(505, 265, 'staff', 20, '2026-02-11 19:48:40'),
(506, 265, 'admin', 18, '2026-02-11 19:48:40'),
(507, 266, 'staff', 20, '2026-02-11 19:48:41'),
(508, 266, 'admin', 19, '2026-02-11 19:48:41'),
(509, 267, 'staff', 20, '2026-02-11 19:48:41'),
(510, 267, 'parent', 21, '2026-02-11 19:48:41'),
(511, 268, 'enseignant', 15, '2026-02-11 20:01:48'),
(512, 268, 'admin', 19, '2026-02-11 20:01:48'),
(513, 269, 'enseignant', 15, '2026-02-11 20:23:36'),
(514, 269, 'admin', 18, '2026-02-11 20:23:36'),
(515, 270, 'admin', 19, '2026-02-11 22:09:30'),
(516, 271, 'admin', 14, '2026-02-11 22:17:50'),
(517, 271, 'enseignant', 15, '2026-02-11 22:17:50'),
(518, 271, 'admin', 16, '2026-02-11 22:17:50'),
(519, 271, 'enseignant', 17, '2026-02-11 22:17:50'),
(520, 271, 'admin', 18, '2026-02-11 22:17:50'),
(521, 271, 'admin', 19, '2026-02-11 22:17:50'),
(522, 271, 'staff', 20, '2026-02-11 22:17:50'),
(523, 271, 'parent', 21, '2026-02-11 22:17:50'),
(524, 272, 'admin', 16, '2026-02-11 22:19:40'),
(525, 273, 'admin', 19, '2026-02-11 22:51:48'),
(530, 275, 'admin', 16, '2026-02-12 04:34:31'),
(531, 275, 'admin', 14, '2026-02-12 04:34:31'),
(532, 276, 'admin', 19, '2026-02-12 10:53:25'),
(533, 276, 'enseignant', 17, '2026-02-12 10:53:25'),
(534, 277, 'admin', 19, '2026-02-12 10:53:26'),
(535, 277, 'admin', 18, '2026-02-12 10:53:26'),
(536, 278, 'admin', 19, '2026-02-12 10:53:27'),
(537, 278, 'parent', 21, '2026-02-12 10:53:27'),
(540, 279, 'admin', 14, '2026-02-12 15:06:43'),
(541, 279, 'enseignant', 17, '2026-02-12 15:06:43'),
(542, 280, 'admin', 14, '2026-02-12 17:00:54'),
(543, 280, 'admin', 18, '2026-02-12 17:00:54'),
(544, 281, 'admin', 14, '2026-02-12 17:00:57'),
(545, 281, 'admin', 19, '2026-02-12 17:00:57'),
(546, 282, 'admin', 14, '2026-02-12 17:02:09'),
(547, 282, 'parent', 21, '2026-02-12 17:02:09'),
(548, 283, 'admin', 14, '2026-02-12 18:46:15'),
(549, 283, 'admin', 16, '2026-02-12 18:46:15'),
(550, 283, 'admin', 18, '2026-02-12 18:46:15'),
(551, 283, 'admin', 19, '2026-02-12 18:46:15'),
(552, 284, 'admin', 0, '2026-02-13 04:10:09'),
(553, 284, 'admin', 14, '2026-02-13 04:10:09'),
(554, 285, 'admin', 0, '2026-02-13 04:10:11'),
(555, 285, 'enseignant', 15, '2026-02-13 04:10:11'),
(556, 286, 'admin', 0, '2026-02-13 04:10:11'),
(557, 286, 'admin', 16, '2026-02-13 04:10:11'),
(558, 287, 'admin', 0, '2026-02-13 04:10:12'),
(559, 287, 'enseignant', 17, '2026-02-13 04:10:12'),
(560, 288, 'admin', 0, '2026-02-13 04:10:13'),
(561, 288, 'admin', 18, '2026-02-13 04:10:13'),
(562, 289, 'admin', 0, '2026-02-13 04:10:13'),
(563, 289, 'admin', 19, '2026-02-13 04:10:13'),
(564, 290, 'admin', 0, '2026-02-13 04:10:15'),
(565, 290, 'staff', 20, '2026-02-13 04:10:15'),
(566, 291, 'admin', 0, '2026-02-13 04:10:16'),
(567, 291, 'parent', 21, '2026-02-13 04:10:16'),
(572, 292, 'admin', 19, '2026-02-13 19:14:23'),
(573, 292, 'admin', 16, '2026-02-13 19:14:23'),
(574, 292, 'admin', 18, '2026-02-13 19:14:23'),
(575, 292, 'admin', 14, '2026-02-13 19:14:23'),
(576, 274, 'admin', 16, '2026-02-13 19:14:39'),
(577, 274, 'admin', 19, '2026-02-13 19:14:39'),
(578, 274, 'admin', 14, '2026-02-13 19:14:39'),
(579, 293, 'enseignant', 17, '2026-02-14 09:40:03'),
(580, 293, 'admin', 18, '2026-02-14 09:40:03'),
(581, 294, 'enseignant', 17, '2026-02-14 09:40:04'),
(582, 294, 'parent', 21, '2026-02-14 09:40:04'),
(583, 295, 'enseignant', 17, '2026-02-14 09:42:15'),
(584, 295, 'enseignant', 15, '2026-02-14 09:42:15');

-- --------------------------------------------------------

--
-- Table structure for table `departements`
--

DROP TABLE IF EXISTS `departements`;
CREATE TABLE IF NOT EXISTS `departements` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `depenses`
--

DROP TABLE IF EXISTS `depenses`;
CREATE TABLE IF NOT EXISTS `depenses` (
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

--
-- Dumping data for table `depenses`
--

INSERT INTO `depenses` (`id`, `type_id`, `personne_id`, `type_personne`, `mois`, `annee_scolaire`, `montant`, `description`, `mode_paiement`, `statut`, `categorie`, `date_enregistrement`, `nom_personne`) VALUES
(32, 0, '13', 'staff', 'Février', '2025-2026', '45000.00', 'Paiement salaire', 'Espèces', 'complet', 'Salaire', '2026-02-07 13:15:22', 'RANDRIAMBOLANIAINA Avotra Fenosoa'),
(33, 0, '1', 'professeur', 'Janvier', '2025-2026', '250000.00', 'Paiement salaire', 'Espèces', 'complet', 'Salaire', '2026-02-07 16:29:41', 'RANDRIAMIFALY Tojo Nambinina'),
(34, 0, '4', 'professeur', 'Janvier', '2025-2026', '300000.00', 'Paiement salaire', 'Espèces', 'complet', 'Salaire', '2026-02-27 20:51:10', 'PERSEVERANCE Pain'),
(35, 0, '2', 'professeur', 'Janvier', '2025-2026', '250000.00', 'Paiement salaire', 'Espèces', 'complet', 'Salaire', '2026-02-28 05:19:20', 'RANDRIAMIFALY Heriniaina');

-- --------------------------------------------------------

--
-- Table structure for table `dossiers`
--

DROP TABLE IF EXISTS `dossiers`;
CREATE TABLE IF NOT EXISTS `dossiers` (
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

--
-- Dumping data for table `dossiers`
--

INSERT INTO `dossiers` (`id`, `annee_scolaire`, `mois`, `type_dossier`, `personne_id`, `anarana`, `description`, `fichier`, `date_upload`) VALUES
(19, '2025-2026', 'Février', 'enseignant', NULL, 'KOTO Kanto', 'Description simpl de la date d\'intégration', 'MY ENGLISH.docx', '2026-02-17 15:44:39'),
(20, '2025-2026', 'Février', 'enseignant', NULL, 'KOTO Kanto', 'Description simpl de la date d\'intégration', 'MY ENGLISH.docx', '2026-02-17 15:46:45'),
(22, '2025-2026', 'Février', 'eleve', NULL, 'Anio HENRY', 'Dossier rentrée scolaire.', 'La meilleure manière de faire de l.docx', '2026-02-26 13:29:22'),
(23, '2025-2026', 'Février', 'eleve', NULL, 'Anio HENRY', 'Dossier rentrée scolaire.', 'La meilleure manière de faire de l.docx', '2026-02-26 13:30:52'),
(24, '2025-2026', 'Mars', 'enseignant', NULL, 'Mathieu', 'Dossier personnel', 'ertt.png', '2026-02-26 13:41:21'),
(25, '2025-2026', 'Février', 'eleve', NULL, 'Fitia', 'Farnay', 'DEADLINE FINITION.txt', '2026-02-26 13:43:04'),
(26, '2026-2027', 'Mars', 'eleve', NULL, 'Faly ANDRIA', 'Carte capturée au GAB', 'La meilleure manière de faire de l.docx', '2026-02-27 12:15:33'),
(27, '2025-2026', 'Janvier', 'eleve', NULL, 'Koto Nandra', 'Fandoavambola', 'ertt.png', '2026-02-27 17:23:14');

-- --------------------------------------------------------

--
-- Table structure for table `ecole`
--

DROP TABLE IF EXISTS `ecole`;
CREATE TABLE IF NOT EXISTS `ecole` (
  `id` int NOT NULL,
  `nom` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `logo` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ecole`
--

INSERT INTO `ecole` (`id`, `nom`, `logo`) VALUES
(-2147483495, 'Novaskol.mg', 'logo_1770356369.png'),
(-2147483495, 'Novaskol.mg', 'logo_1770356369.png');

-- --------------------------------------------------------

--
-- Table structure for table `eleves`
--

DROP TABLE IF EXISTS `eleves`;
CREATE TABLE IF NOT EXISTS `eleves` (
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
  KEY `id_classe` (`id_classe`)
) ENGINE=InnoDB AUTO_INCREMENT=315 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `eleves`
--

INSERT INTO `eleves` (`id`, `matricule`, `nom`, `prenom`, `date_naissance`, `lieu_naissance`, `telephone`, `adresse`, `numero_acte`, `fonkotany`, `commune`, `ecole_ancienne`, `id_classe`, `photo`, `annee_scolaire`, `nom_pere`, `nom_mere`, `distance_domicile`, `genre`, `statut`, `est_handicap`) VALUES
(237, '20250001', 'RHON', 'Mano', '2016-05-06', 'Ambatondrazaka', '2345678912', 'LOT HASH', 'ACT32', 'Andraisoro', 'Antananarivo', 'Lycée Avaratra', 6, 'Uploads/default.jpg', '2025-2026', 'ANDRIANE Elidia', 'ELIANE Ronjo', 1, 'G', 'nouveau', 1),
(238, '20250002', 'RHAM', 'Elia', '2018-05-07', 'Ambatondrazaka', '2345678913', 'LOT HASH', 'ACT33', 'Andraisoro', 'Antananarivo', 'Lycée Avaratra', 6, 'Uploads/default.jpg', '2025-2026', 'ANDRIANE Elidi', 'ELIANE Ron', 0, 'F', 'passant', 0),
(239, '20250003', 'DENJI', 'Lita', '2020-05-08', 'Ambatondrazaka', '2345678914', 'LOT HASH', 'ACT34', 'Andraisoro', 'Antananarivo', 'Lycée Avaratra', 6, 'Uploads/default.jpg', '2025-2026', 'ANDRIANE Eliia', 'ELIAE Ronjo', 1, 'G', 'redoublant', 1),
(240, '20250004', 'DIARA', 'Toavina', '2019-05-09', 'Ambatondrazaka', '2345678915', 'LOT HASH', 'ACT35', 'Andraisoro', 'Antananarivo', 'Lycée Avaratra', 6, 'Uploads/default.jpg', '2025-2026', 'ANDRIANE lidia', 'ELIANE Rnjo', 0, 'F', 'nouveau', 0),
(241, '20250005', 'ARIAME', 'Victoria', '2017-05-10', 'Ambatondrazaka', '2345678916', 'LOT HASH', 'ACT36', 'Andraisoro', 'Antananarivo', 'Lycée Avaratra', 6, 'Uploads/default.jpg', '2025-2026', 'ANDRINE Elidia', 'ELANE Ronjo', 1, 'G', 'passant', 1),
(242, '20250006', 'DROUNE', 'Ariane', '2018-05-11', 'Ambatondrazaka', '2345678917', 'LOT HASH', 'ACT37', 'Andraisoro', 'Antananarivo', 'Lycée Avaratra', 6, 'Uploads/default.jpg', '2025-2026', 'NDRIANE Elidia', 'LIANE Ronjo', 0, 'F', 'redoublant', 0),
(243, '20250007', 'DJIANDE', 'Roundro', '2019-05-12', 'Ambatondrazaka', '2345678918', 'LOT HASH', 'ACT38', 'Andraisoro', 'Antananarivo', 'Lycée Avaratra', 6, 'Uploads/default.jpg', '2025-2026', 'ANDRIANE Elid', 'ELIE Ronjo', 1, 'G', 'nouveau', 1),
(244, '20250008', 'DJEDE', 'Eldia', '2016-05-13', 'Ambatondrazaka', '2345678919', 'LOT HASH', 'ACT39', 'Andraisoro', 'Antananarivo', 'Lycée Avaratra', 6, 'Uploads/default.jpg', '2025-2026', 'ANDRIANE idia', 'ELIAN Ronjo', 0, 'F', 'passant', 0),
(245, '20250009', 'DJOUDE', 'Douane', '2016-05-14', 'Ambatondrazaka', '2345678920', 'LOT HASH', 'ACT40', 'Andraisoro', 'Antananarivo', 'Lycée Avaratra', 6, 'Uploads/default.jpg', '2025-2026', 'ANIANE Elidia', 'ELNE Ronjo', 1, 'G', 'redoublant', 1),
(246, '20250010', 'RHON', 'Fils', '2015-05-06', 'Ambatondrazaka', '1478523698', 'LOT soa', 'ACT78', 'Anjanahary', 'Antananarivo', 'Lycée Andrombe', 8, 'Uploads/default.jpg', '2025-2026', 'ELIE randira', 'RONDRO andry', 1, 'G', 'nouveau', 1),
(247, '20250011', 'RHAM', 'Faly', '2015-05-07', 'Ambatondrazaka', '1478523699', 'LOT soa', 'ACT78', 'Anjanahary', 'Antananarivo', 'Lycée Andrombe', 8, 'Uploads/default.jpg', '2025-2026', 'ELIE randira', 'RONDRO andry', 0, 'F', 'passant', 0),
(248, '20250012', 'DENJI', 'Fondro', '2015-05-08', 'Ambatondrazaka', '1478523700', 'LOT soa', 'ACT78', 'Anjanahary', 'Antananarivo', 'Lycée Andrombe', 8, 'Uploads/default.jpg', '2025-2026', 'ELIE randira', 'RONDRO andry', 1, 'G', 'redoublant', 1),
(249, '20250013', 'DIARA', 'Fadnry', '2015-05-09', 'Ambatondrazaka', '1478523701', 'LOT soa', 'ACT78', 'Anjanahary', 'Antananarivo', 'Lycée Andrombe', 8, 'Uploads/default.jpg', '2025-2026', 'ELIE randira', 'RONDRO andry', 0, 'F', 'nouveau', 0),
(250, '20250014', 'ARIAME', 'Doune', '2015-05-10', 'Ambatondrazaka', '1478523702', 'LOT soa', 'ACT78', 'Anjanahary', 'Antananarivo', 'Lycée Andrombe', 8, 'Uploads/default.jpg', '2025-2026', 'ELIE randira', 'RONDRO andry', 1, 'G', 'passant', 1),
(251, '20250015', 'DROUNE', 'Andry', '2015-05-11', 'Ambatondrazaka', '1478523703', 'LOT soa', 'ACT78', 'Anjanahary', 'Antananarivo', 'Lycée Andrombe', 8, 'Uploads/default.jpg', '2025-2026', 'ELIE randira', 'RONDRO andry', 0, 'F', 'redoublant', 0),
(252, '20250016', 'DJIANDE', 'Douane', '2015-05-12', 'Ambatondrazaka', '1478523704', 'LOT soa', 'ACT78', 'Anjanahary', 'Antananarivo', 'Lycée Andrombe', 8, 'Uploads/default.jpg', '2025-2026', 'ELIE randira', 'RONDRO andry', 1, 'G', 'nouveau', 1),
(253, '20250017', 'DJEDE', 'Indry', '2015-05-13', 'Ambatondrazaka', '1478523705', 'LOT soa', 'ACT78', 'Anjanahary', 'Antananarivo', 'Lycée Andrombe', 8, 'Uploads/default.jpg', '2025-2026', 'ELIE randira', 'RONDRO andry', 0, 'F', 'passant', 0),
(254, '20250018', 'DJOUDE', 'Adnria', '2015-05-14', 'Ambatondrazaka', '1478523706', 'LOT soa', 'ACT78', 'Anjanahary', 'Antananarivo', 'Lycée Andrombe', 8, 'Uploads/default.jpg', '2025-2026', 'ELIE randira', 'RONDRO andry', 1, 'G', 'redoublant', 1),
(255, '20250019', 'RHON', 'Diane', '2015-05-06', 'Ambatondrazaka', '1478523698', 'LOT soa', 'ACT78', 'Anjanahary', 'Antananarivo', 'Lycée Andrombe', 9, 'Uploads/default.jpg', '2025-2026', 'ELIE randira', 'RONDRO andry', 1, 'G', 'nouveau', 1),
(256, '20250020', 'RHAM', 'Andry', '2015-05-07', 'Ambatondrazaka', '1478523699', 'LOT soa', 'ACT78', 'Anjanahary', 'Antananarivo', 'Lycée Andrombe', 9, 'Uploads/default.jpg', '2025-2026', 'ELIE randira', 'RONDRO andry', 0, 'F', 'passant', 0),
(257, '20250021', 'DENJI', 'Rondro', '2015-05-08', 'Ambatondrazaka', '1478523700', 'LOT soa', 'ACT78', 'Anjanahary', 'Antananarivo', 'Lycée Andrombe', 9, 'Uploads/default.jpg', '2025-2026', 'ELIE randira', 'RONDRO andry', 1, 'G', 'redoublant', 1),
(258, '20250022', 'DIARA', 'Drouane', '2015-05-09', 'Ambatondrazaka', '1478523701', 'LOT soa', 'ACT78', 'Anjanahary', 'Antananarivo', 'Lycée Andrombe', 9, 'Uploads/default.jpg', '2025-2026', 'ELIE randira', 'RONDRO andry', 0, 'F', 'nouveau', 0),
(259, '20250023', 'ARIAME', 'Elia', '2015-05-10', 'Ambatondrazaka', '1478523702', 'LOT soa', 'ACT78', 'Anjanahary', 'Antananarivo', 'Lycée Andrombe', 9, 'Uploads/default.jpg', '2025-2026', 'ELIE randira', 'RONDRO andry', 1, 'G', 'passant', 1),
(260, '20250024', 'DROUNE', 'Driane', '2015-05-11', 'Ambatondrazaka', '1478523703', 'LOT soa', 'ACT78', 'Anjanahary', 'Antananarivo', 'Lycée Andrombe', 9, 'Uploads/default.jpg', '2025-2026', 'ELIE randira', 'RONDRO andry', 0, 'F', 'redoublant', 0),
(261, '20250025', 'DJIANDE', 'Louane', '2015-05-12', 'Ambatondrazaka', '1478523704', 'LOT soa', 'ACT78', 'Anjanahary', 'Antananarivo', 'Lycée Andrombe', 9, 'Uploads/default.jpg', '2025-2026', 'ELIE randira', 'RONDRO andry', 1, 'G', 'nouveau', 1),
(262, '20250026', 'DJEDE', 'Sane', '2015-05-13', 'Ambatondrazaka', '1478523705', 'LOT soa', 'ACT78', 'Anjanahary', 'Antananarivo', 'Lycée Andrombe', 9, 'Uploads/default.jpg', '2025-2026', 'ELIE randira', 'RONDRO andry', 0, 'F', 'passant', 0),
(263, '20250027', 'DJOUDE', 'Fiadanana', '2015-05-14', 'Ambatondrazaka', '1478523706', 'LOT soa', 'ACT78', 'Anjanahary', 'Antananarivo', 'Lycée Andrombe', 9, 'Uploads/default.jpg', '2025-2026', 'ELIE randira', 'RONDRO andry', 1, 'G', 'redoublant', 1),
(264, '20250028', 'RHON', 'Toa', '2015-05-06', 'Ambatondrazaka', '1478523698', 'LOT soa', 'ACT78', 'Anjanahary', 'Antananarivo', 'Lycée Andrombe', 11, 'Uploads/default.jpg', '2025-2026', 'ELIE randira', 'RONDRO andry', 1, 'G', 'nouveau', 1),
(265, '20250029', 'RHAM', 'Andry', '2015-05-07', 'Ambatondrazaka', '1478523699', 'LOT soa', 'ACT78', 'Anjanahary', 'Antananarivo', 'Lycée Andrombe', 11, 'Uploads/default.jpg', '2025-2026', 'ELIE randira', 'RONDRO andry', 0, 'F', 'passant', 0),
(266, '20250030', 'DENJI', 'Rija', '2015-05-08', 'Ambatondrazaka', '1478523700', 'LOT soa', 'ACT78', 'Anjanahary', 'Antananarivo', 'Lycée Andrombe', 11, 'Uploads/default.jpg', '2025-2026', 'ELIE randira', 'RONDRO andry', 1, 'G', 'redoublant', 1),
(267, '20250031', 'DIARA', 'Adnroau', '2015-05-09', 'Ambatondrazaka', '1478523701', 'LOT soa', 'ACT78', 'Anjanahary', 'Antananarivo', 'Lycée Andrombe', 11, 'Uploads/default.jpg', '2025-2026', 'ELIE randira', 'RONDRO andry', 0, 'F', 'nouveau', 0),
(268, '20250032', 'ARIAME', 'Doua', '2015-05-10', 'Ambatondrazaka', '1478523702', 'LOT soa', 'ACT78', 'Anjanahary', 'Antananarivo', 'Lycée Andrombe', 11, 'Uploads/default.jpg', '2025-2026', 'ELIE randira', 'RONDRO andry', 1, 'G', 'passant', 1),
(269, '20250033', 'DROUNE', 'Riane', '2015-05-11', 'Ambatondrazaka', '1478523703', 'LOT soa', 'ACT78', 'Anjanahary', 'Antananarivo', 'Lycée Andrombe', 11, 'Uploads/default.jpg', '2025-2026', 'ELIE randira', 'RONDRO andry', 0, 'F', 'redoublant', 0),
(270, '20250034', 'DJIANDE', 'Ouane', '2015-05-12', 'Ambatondrazaka', '1478523704', 'LOT soa', 'ACT78', 'Anjanahary', 'Antananarivo', 'Lycée Andrombe', 11, 'Uploads/default.jpg', '2025-2026', 'ELIE randira', 'RONDRO andry', 1, 'G', 'nouveau', 1),
(271, '20250035', 'DJEDE', 'Diane', '2015-05-13', 'Ambatondrazaka', '1478523705', 'LOT soa', 'ACT78', 'Anjanahary', 'Antananarivo', 'Lycée Andrombe', 11, 'Uploads/default.jpg', '2025-2026', 'ELIE randira', 'RONDRO andry', 0, 'F', 'passant', 0),
(272, '20250036', 'DJOUDE', 'Rouane', '2015-05-14', 'Ambatondrazaka', '1478523706', 'LOT soa', 'ACT78', 'Anjanahary', 'Antananarivo', 'Lycée Andrombe', 11, 'Uploads/default.jpg', '2025-2026', 'ELIE randira', 'RONDRO andry', 1, 'G', 'redoublant', 1),
(273, '20250037', 'RHON', 'Fitia', '2015-05-06', 'Ambatondrazaka', '1478523698', 'LOT soa', 'ACT78', 'Anjanahary', 'Antananarivo', 'Lycée Andrombe', 13, 'Uploads/default.jpg', '2025-2026', 'ELIE randira', 'RONDRO andry', 1, 'G', 'nouveau', 1),
(274, '20250038', 'RHAM', 'Valy', '2015-05-07', 'Ambatondrazaka', '1478523699', 'LOT soa', 'ACT78', 'Anjanahary', 'Antananarivo', 'Lycée Andrombe', 13, 'Uploads/default.jpg', '2025-2026', 'ELIE randira', 'RONDRO andry', 0, 'F', 'passant', 0),
(275, '20250039', 'DENJI', 'Avotra', '2015-05-08', 'Ambatondrazaka', '1478523700', 'LOT soa', 'ACT78', 'Anjanahary', 'Antananarivo', 'Lycée Andrombe', 13, 'Uploads/default.jpg', '2025-2026', 'ELIE randira', 'RONDRO andry', 1, 'G', 'redoublant', 1),
(276, '20250040', 'DIARA', 'Didy', '2015-05-09', 'Ambatondrazaka', '1478523701', 'LOT soa', 'ACT78', 'Anjanahary', 'Antananarivo', 'Lycée Andrombe', 13, 'Uploads/default.jpg', '2025-2026', 'ELIE randira', 'RONDRO andry', 0, 'F', 'nouveau', 0),
(277, '20250041', 'ARIAME', 'Doua', '2015-05-10', 'Ambatondrazaka', '1478523702', 'LOT soa', 'ACT78', 'Anjanahary', 'Antananarivo', 'Lycée Andrombe', 13, 'Uploads/default.jpg', '2025-2026', 'ELIE randira', 'RONDRO andry', 1, 'G', 'passant', 1),
(278, '20250042', 'DROUNE', 'Dinah', '2015-05-11', 'Ambatondrazaka', '1478523703', 'LOT soa', 'ACT78', 'Anjanahary', 'Antananarivo', 'Lycée Andrombe', 13, 'Uploads/default.jpg', '2025-2026', 'ELIE randira', 'RONDRO andry', 0, 'F', 'redoublant', 0),
(279, '20250043', 'DJIANDE', 'Andry', '2015-05-12', 'Ambatondrazaka', '1478523704', 'LOT soa', 'ACT78', 'Anjanahary', 'Antananarivo', 'Lycée Andrombe', 13, 'Uploads/default.jpg', '2025-2026', 'ELIE randira', 'RONDRO andry', 1, 'G', 'nouveau', 1),
(280, '20250044', 'DJEDE', 'Rouane', '2015-05-13', 'Ambatondrazaka', '1478523705', 'LOT soa', 'ACT78', 'Anjanahary', 'Antananarivo', 'Lycée Andrombe', 13, 'Uploads/default.jpg', '2025-2026', 'ELIE randira', 'RONDRO andry', 0, 'F', 'passant', 0),
(281, '20250045', 'DJOUDE', 'Ariane', '2015-05-14', 'Ambatondrazaka', '1478523706', 'LOT soa', 'ACT78', 'Anjanahary', 'Antananarivo', 'Lycée Andrombe', 13, 'Uploads/default.jpg', '2025-2026', 'ELIE randira', 'RONDRO andry', 1, 'G', 'redoublant', 1),
(282, '20250046', 'OLANA', 'Misy', '2010-05-15', 'Antananarivo', '0123456789', 'Lot 123', 'ACT123', 'Ambohijanahary', 'Antananarivo', 'Lycée Moderne', 14, 'Uploads/default.jpg', '2025-2026', 'Paul Dupont', 'Marie Dupont', 1, 'G', 'nouveau', 0),
(283, '20250047', 'OLANA', 'Fisy', '2010-05-16', 'Antananarivo', '0123456790', 'Lot 124', 'ACT124', 'Ambohijanahary', 'Antananarivo', 'Lycée Moderne', 14, 'Uploads/default.jpg', '2025-2026', 'Paul Dupont', 'Marie Dupont', 1, 'F', 'nouveau', 0),
(284, '20250048', 'OLANA', 'Firy', '2010-05-17', 'Antananarivo', '0123456791', 'Lot 125', 'ACT125', 'Ambohijanahary', 'Antananarivo', 'Lycée Moderne', 14, 'Uploads/default.jpg', '2025-2026', 'Paul Dupont', 'Marie Dupont', 1, 'G', 'nouveau', 0),
(285, '20250049', 'OLANA', 'Ampio', '2010-05-18', 'Antananarivo', '0123456792', 'Lot 126', 'ACT126', 'Ambohijanahary', 'Antananarivo', 'Lycée Moderne', 14, 'Uploads/default.jpg', '2025-2026', 'Paul Dupont', 'Marie Dupont', 1, 'F', 'nouveau', 0),
(286, '20250050', 'OLANA', 'Aho', '2010-05-19', 'Antananarivo', '0123456793', 'Lot 127', 'ACT127', 'Ambohijanahary', 'Antananarivo', 'Lycée Moderne', 14, 'Uploads/default.jpg', '2025-2026', 'Paul Dupont', 'Marie Dupont', 1, 'F', 'nouveau', 0),
(287, '20250051', 'OLANA', 'Tompo', '2010-05-20', 'Antananarivo', '0123456794', 'Lot 128', 'ACT128', 'Ambohijanahary', 'Antananarivo', 'Lycée Moderne', 14, 'Uploads/default.jpg', '2025-2026', 'Paul Dupont', 'Marie Dupont', 1, 'G', 'nouveau', 0),
(288, '20250052', 'OLANA', 'Matoky', '2010-05-21', 'Antananarivo', '0123456795', 'Lot 129', 'ACT129', 'Ambohijanahary', 'Antananarivo', 'Lycée Moderne', 14, 'Uploads/default.jpg', '2025-2026', 'Paul Dupont', 'Marie Dupont', 1, 'F', 'nouveau', 0),
(289, '20250053', 'OLANA', 'Anao', '2010-05-22', 'Antananarivo', '0123456796', 'Lot 130', 'ACT130', 'Ambohijanahary', 'Antananarivo', 'Lycée Moderne', 14, 'Uploads/default.jpg', '2025-2026', 'Paul Dupont', 'Marie Dupont', 1, 'G', 'nouveau', 0),
(290, '20250054', 'OLANA', 'Aho', '2010-05-23', 'Antananarivo', '0123456797', 'Lot 131', 'ACT131', 'Ambohijanahary', 'Antananarivo', 'Lycée Moderne', 14, 'Uploads/default.jpg', '2025-2026', 'Paul Dupont', 'Marie Dupont', 0, 'F', 'nouveau', 0),
(295, '20250055', 'ANIO', 'Tody', '2019-05-15', 'Antananarivo', '1478523698', 'LOT 456', 'act 356', 'Albohidahy', 'Antananarivo', 'Lycée Ampitatafika', 2, 'Uploads/1771477461_1753116347_2.jpg', '2025-2026', 'HERY naso', 'Ando RAVOJAS', 1, 'G', 'nouveau', 1),
(296, '20250056', 'ANIO', 'Fyh', '2019-05-16', 'Antananarivo', '1478523699', 'LOT 457', 'act 357', 'Albohidahy', 'Antananarivo', 'Lycée Ampitatafika', 2, 'Uploads/1772259083_1753116419_6.jpg', '2025-2026', 'HERY nas', 'Ando RAVOJAE', 0, 'F', 'passant', 0),
(297, '20250057', 'ANIO', 'Fano', '2019-05-17', 'Antananarivo', '1478523700', 'LOT 458', 'act 358', 'Albohidahy', 'Antananarivo', 'Lycée Ampitatafika', 2, 'Uploads/default.jpg', '2025-2026', 'HERY nasol', 'Ando RAVOJAT', 1, 'G', 'nouveau', 1),
(298, '20250058', 'ANIO', 'Fonja', '2019-05-18', 'Antananarivo', '1478523701', 'LOT 459', 'act 359', 'Albohidahy', 'Antananarivo', 'Lycée Ampitatafika', 2, 'Uploads/default.jpg', '2025-2026', 'HERY nasoloa', 'Ando RAVOJAF', 0, 'F', 'passant', 1),
(299, '20250059', 'ANIO', 'Fery', '2019-05-19', 'Antananarivo', '1478523702', 'LOT 460', 'act 360', 'Albohidahy', 'Antananarivo', 'Lycée Ampitatafika', 2, 'Uploads/default.jpg', '2025-2026', 'HERY nasoloe', 'Ando RAVOJAG', 1, 'G', 'nouveau', 0),
(300, '20250060', 'ANIO', 'Foniah', '2019-05-20', 'Antananarivo', '1478523703', 'LOT 461', 'act 361', 'Albohidahy', 'Antananarivo', 'Lycée Ampitatafika', 2, 'Uploads/1771527696_1753117388_7.jpg', '2025-2026', 'HERY nasolor', 'Ando RAVOJAH', 0, 'F', 'redoublant', 1),
(301, '20250061', 'ANIO', 'Fanih', '2019-05-21', 'Antananarivo', '1478523704', 'LOT 462', 'act 362', 'Albohidahy', 'Antananarivo', 'Lycée Ampitatafika', 2, 'Uploads/1771479579_1753116482_9.jpg', '2025-2026', 'HERY nasolort', 'Ando RAVOJAL', 1, 'G', 'nouveau', 1),
(302, '20250062', 'ANIO', 'Faniahy', '2019-05-22', 'Antananarivo', '1478523705', 'LOT 463', 'act 363', 'Albohidahy', 'Antananarivo', 'Lycée Ampitatafika', 2, 'Uploads/1761980145_images.png', '2025-2026', 'HERY nasoloe', 'Ando RAVOJAO', 1, 'F', 'redoublant', 0),
(303, '20250063', 'ANIO', 'Faniho', '2019-05-23', 'Antananarivo', '1478523706', 'LOT 464', 'act 364', 'Albohidahy', 'Antananarivo', 'Lycée Ampitatafika', 2, 'Uploads/1771477398_1753116398_5.jpg', '2025-2026', 'HERY rrna', 'Ando RAVOJA', 1, 'G', 'redoublant', 1),
(304, '20260001', 'Dupont', 'Jean', '2010-05-15', 'Antananarivo', '0123456789', 'Lot 123', 'ACT123', 'Ambohijanahary', 'Antananarivo', 'Lycée Moderne', 1, 'Uploads/default.jpg', '2026-2027', 'Paul Dupont', 'Marie Dupont', 1, 'G', 'nouveau', 0),
(305, '20250064', 'ANIO', 'Tody', '2019-05-15', 'Antananarivo', '1478523698', 'LOT 456', 'act 356', 'Albohidahy', 'Antananarivo', 'Lycée Ampitatafika', 16, 'Uploads/default.jpg', '2025-2026', 'HERY naso', 'Ando RAVOJAS', 1, 'G', 'nouveau', 1),
(306, '20250065', 'ANIO', 'Fyh', '2019-05-16', 'Antananarivo', '1478523699', 'LOT 457', 'act 357', 'Albohidahy', 'Antananarivo', 'Lycée Ampitatafika', 16, 'Uploads/default.jpg', '2025-2026', 'HERY nas', 'Ando RAVOJAE', 0, 'F', 'passant', 0),
(307, '20250066', 'ANIO', 'Fano', '2019-05-17', 'Antananarivo', '1478523700', 'LOT 458', 'act 358', 'Albohidahy', 'Antananarivo', 'Lycée Ampitatafika', 16, 'Uploads/default.jpg', '2025-2026', 'HERY nasol', 'Ando RAVOJAT', 1, 'G', 'nouveau', 1),
(308, '20250067', 'ANIO', 'Fonja', '2019-05-18', 'Antananarivo', '1478523701', 'LOT 459', 'act 359', 'Albohidahy', 'Antananarivo', 'Lycée Ampitatafika', 16, 'Uploads/default.jpg', '2025-2026', 'HERY nasoloa', 'Ando RAVOJAF', 0, 'F', 'passant', 1),
(309, '20250068', 'ANIO', 'Fery', '2019-05-19', 'Antananarivo', '1478523702', 'LOT 460', 'act 360', 'Albohidahy', 'Antananarivo', 'Lycée Ampitatafika', 16, 'Uploads/default.jpg', '2025-2026', 'HERY nasoloe', 'Ando RAVOJAG', 1, 'G', 'nouveau', 0),
(310, '20250069', 'ANIO', 'Foniah', '2019-05-20', 'Antananarivo', '1478523703', 'LOT 461', 'act 361', 'Albohidahy', 'Antananarivo', 'Lycée Ampitatafika', 16, 'Uploads/default.jpg', '2025-2026', 'HERY nasolor', 'Ando RAVOJAH', 0, 'F', 'redoublant', 1),
(311, '20250070', 'ANIO', 'Fanih', '2019-05-21', 'Antananarivo', '1478523704', 'LOT 462', 'act 362', 'Albohidahy', 'Antananarivo', 'Lycée Ampitatafika', 16, 'Uploads/default.jpg', '2025-2026', 'HERY nasolort', 'Ando RAVOJAL', 1, 'G', 'nouveau', 1),
(312, '20250071', 'ANIO', 'Faniahy', '2019-05-22', 'Antananarivo', '1478523705', 'LOT 463', 'act 363', 'Albohidahy', 'Antananarivo', 'Lycée Ampitatafika', 16, 'Uploads/default.jpg', '2025-2026', 'HERY nasoloe', 'Ando RAVOJAO', 1, 'F', 'redoublant', 0),
(313, '20250072', 'ANIO', 'Faniho', '2019-05-23', 'Antananarivo', '1478523706', 'LOT 464', 'act 364', 'Albohidahy', 'Antananarivo', 'Lycée Ampitatafika', 16, 'Uploads/default.jpg', '2025-2026', 'HERY rrna', 'Ando RAVOJA', 1, 'G', 'redoublant', 1),
(314, '20250073', 'NANDRA', 'Koto', '2005-10-01', 'Adiranaina', '0371415214', 'Lot ANTSOY', 'Act145', 'Ankavanana', 'Antanananarivo', 'Lycée Andrakona', 51, 'Uploads/1772088485_geralt-ai-generated-9811472_1280.jpg', '2025-2026', 'Tojo_pro', 'Reko ANDRY', 1, 'G', 'nouveau', 1);

-- --------------------------------------------------------

--
-- Table structure for table `emploi_du_temps`
--

DROP TABLE IF EXISTS `emploi_du_temps`;
CREATE TABLE IF NOT EXISTS `emploi_du_temps` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_classe` int NOT NULL,
  `data_json` text COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_classe` (`id_classe`)
) ENGINE=InnoDB AUTO_INCREMENT=52 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `emploi_du_temps`
--

INSERT INTO `emploi_du_temps` (`id`, `id_classe`, `data_json`) VALUES
(34, 1, '[{\"heure\":\"07h00-10h00\",\"lundi\":\"Science Economique\",\"mardi\":\"SVT\",\"mercredi\":\"EPS\",\"jeudi\":\"Mathématique\",\"vendredi\":\"Physique\",\"samedi\":\"Cours\"},{\"heure\":\"10h00-10h15\",\"lundi\":\" 🕘\",\"mardi\":\"   🕘\",\"mercredi\":\"  🕘\",\"jeudi\":\"  🕘\",\"vendredi\":\"   🕘\",\"samedi\":\"  🕘\"},{\"heure\":\"10h15-12h00\",\"lundi\":\"Français\",\"mardi\":\"Physique-Chimie\",\"mercredi\":\"Français\",\"jeudi\":\"Philosophie\",\"vendredi\":\"Français\",\"samedi\":\"Cours\"},{\"heure\":\"12h00-13h00\",\"lundi\":\"❌❌❌❌❌\",\"mardi\":\"❌❌❌❌❌\",\"mercredi\":\"❌❌❌❌❌\",\"jeudi\":\"❌❌❌❌❌\",\"vendredi\":\"❌❌❌❌❌\",\"samedi\":\"❌❌❌❌❌\"},{\"heure\":\"13h00-15h00\",\"lundi\":\"Anglais\",\"mardi\":\"Histo-géo\",\"mercredi\":\"Informatique\",\"jeudi\":\"Anglais\",\"vendredi\":\"SVT\",\"samedi\":\"Cours\"},{\"heure\":\"15h00-15h15\",\"lundi\":\" 🕘\",\"mardi\":\" 🕘\",\"mercredi\":\" 🕘\",\"jeudi\":\" 🕘\",\"vendredi\":\"  🕘\",\"samedi\":\"  🕘\"},{\"heure\":\"15h15-17h00\",\"lundi\":\"Philosophie\",\"mardi\":\"SES\",\"mercredi\":\"Etude\",\"jeudi\":\"Malagasy\",\"vendredi\":\"SES\",\"samedi\":\"Cours\"}]'),
(51, 14, '{\"2\":{\"heure\":\"09h30-10h00\",\"lundi\":\"MALAGASY\",\"mardi\":\"MALAGASY\",\"mercredi\":\"MALAGASY\",\"jeudi\":\"MALAGASY\",\"vendredi\":\"MALAGASY\",\"samedi\":\"MALAGASY\"},\"3\":{\"heure\":\"10h00-10h30\",\"lundi\":\"MALAGASY\",\"mardi\":\"MALAGASY\",\"mercredi\":\"MALAGASY\",\"jeudi\":\"MALAGASY\",\"vendredi\":\"MALAGASY\",\"samedi\":\"MALAGASY\"},\"4\":{\"heure\":\"11h30-12h00\",\"lundi\":\"\",\"mardi\":\"\",\"mercredi\":\"\",\"jeudi\":\"\",\"vendredi\":\"\",\"samedi\":\"\"},\"5\":{\"heure\":\"11h00-11h30\",\"lundi\":\"MALAGASY\",\"mardi\":\"MALAGASY\",\"mercredi\":\"MALAGASY\",\"jeudi\":\"MALAGASY\",\"vendredi\":\"MALAGASY\",\"samedi\":\"MALAGASY\"}}');

-- --------------------------------------------------------

--
-- Table structure for table `enseignants`
--

DROP TABLE IF EXISTS `enseignants`;
CREATE TABLE IF NOT EXISTS `enseignants` (
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

--
-- Dumping data for table `enseignants`
--

INSERT INTO `enseignants` (`id`, `nom`, `prenom`, `email`, `telephone`, `matiere`, `annee_scolaire`, `date_embauche`, `statut`, `created_at`) VALUES
(1, 'Dupont', 'Jean', 'jean.dupont@example.com', '0123456789', 'Mathématiques', '2025-2026', '2023-09-01', 'actif', '2025-08-13 11:29:56');

-- --------------------------------------------------------

--
-- Table structure for table `equipements`
--

DROP TABLE IF EXISTS `equipements`;
CREATE TABLE IF NOT EXISTS `equipements` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `quantite` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_general_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `equipements`
--

INSERT INTO `equipements` (`id`, `nom`, `quantite`, `description`) VALUES
(3, 'Rouleau', '10', 'Pour colorer les mmurs de l\'école');

-- --------------------------------------------------------

--
-- Table structure for table `evenements`
--

DROP TABLE IF EXISTS `evenements`;
CREATE TABLE IF NOT EXISTS `evenements` (
  `id` int NOT NULL AUTO_INCREMENT,
  `titre` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `description` text COLLATE utf8mb4_general_ci,
  `type` enum('rendez-vous','examen','session examen','réunion','vacance','évènement scolaire') COLLATE utf8mb4_general_ci NOT NULL,
  `date_debut` datetime NOT NULL,
  `date_fin` datetime NOT NULL,
  `createur_id` int DEFAULT NULL,
  `date_creation` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=73 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `evenements`
--

INSERT INTO `evenements` (`id`, `titre`, `description`, `type`, `date_debut`, `date_fin`, `createur_id`, `date_creation`) VALUES
(70, 'Réunion', 'Appel à réunion à tous les administrateurs y compris les staffs', 'réunion', '2026-02-10 09:00:00', '2026-02-10 23:00:00', 14, '2026-02-28 12:08:57'),
(71, 'Activité quotidienne', 'C\'est pour toutes les personnes', '', '2026-03-09 09:00:00', '2026-03-09 17:00:00', 14, '2026-03-08 18:09:15'),
(72, 'Sorite Récréative', 'Réunion préliminaire', 'réunion', '2026-04-07 09:00:00', '2026-04-07 17:00:00', 16, '2026-04-20 16:39:34');

-- --------------------------------------------------------

--
-- Table structure for table `examen_blanc`
--

DROP TABLE IF EXISTS `examen_blanc`;
CREATE TABLE IF NOT EXISTS `examen_blanc` (
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
  KEY `matiere_id` (`matiere_id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `examen_blanc`
--

INSERT INTO `examen_blanc` (`id`, `eleve_id`, `classe_id`, `matiere_id`, `session`, `note`, `annee_scolaire`, `date_examen`) VALUES
(13, 312, 16, 20, '1', '13.00', '2025-2026', '2026-02-19'),
(14, 312, 16, 16, '1', '14.00', '2025-2026', '2026-02-19'),
(15, 312, 16, 17, '1', '12.00', '2025-2026', '2026-02-19'),
(16, 312, 16, 3, '1', '14.00', '2025-2026', '2026-02-19'),
(17, 312, 16, 40, '1', '15.00', '2025-2026', '2026-02-19'),
(18, 312, 16, 4, '1', '11.00', '2025-2026', '2026-02-19'),
(19, 311, 16, 20, '1', '14.00', '2025-2026', '2026-02-28');

-- --------------------------------------------------------

--
-- Table structure for table `fichiers`
--

DROP TABLE IF EXISTS `fichiers`;
CREATE TABLE IF NOT EXISTS `fichiers` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom_fichier` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `chemin` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `licence`
--

DROP TABLE IF EXISTS `licence`;
CREATE TABLE IF NOT EXISTS `licence` (
  `id` int NOT NULL AUTO_INCREMENT,
  `cle_licence` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `date_debut` date NOT NULL,
  `date_expiration` date NOT NULL,
  `statut` enum('actif','expire') COLLATE utf8mb4_general_ci DEFAULT 'actif',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `licence`
--

INSERT INTO `licence` (`id`, `cle_licence`, `date_debut`, `date_expiration`, `statut`) VALUES
(1, '9d9a4cf1c56efce9e70272f835918a4c781a4a216f543f55a41815fbf955b198', '2025-09-09', '2026-09-09', 'actif');

-- --------------------------------------------------------

--
-- Table structure for table `matieres`
--

DROP TABLE IF EXISTS `matieres`;
CREATE TABLE IF NOT EXISTS `matieres` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `coefficient` int DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=59 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `matieres`
--

INSERT INTO `matieres` (`id`, `nom`, `coefficient`) VALUES
(1, 'Expression orale', 1),
(2, 'Dessin et coloriage', 1),
(3, 'Chant et musique', 1),
(4, 'Jeux éducatifs', 1),
(5, 'Motricité', 1),
(6, 'Pré-lecture', 1),
(7, 'Pré-écriture', 1),
(8, 'Pré-mathématiques', 1),
(9, 'Malagasy', 2),
(12, 'Education Civique et Morale', 1),
(13, 'SVT', 1),
(14, 'Lecture', 1),
(16, 'Arts plastiques', 1),
(17, 'Chant', 1),
(18, 'EPS', 1),
(19, 'Exercice physique', 1),
(20, 'Anglais', 2),
(21, 'Deutsh', 1),
(26, 'Education artistique', 1),
(28, 'Mathématique', 2),
(31, 'Philosophie', 2),
(32, 'Histoire-Géographie', 2),
(33, 'Espagnol', 1),
(34, 'Physique-Chimie', 2),
(38, 'ECM', 1),
(40, 'Informatique', 1),
(42, 'Education pour les jeune', 1),
(43, 'SES', 1),
(44, 'Malagasy', 1),
(45, 'Français', 1),
(46, 'Maths', 1),
(47, 'Histoire', 1),
(48, 'Sciences', 1);

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

DROP TABLE IF EXISTS `messages`;
CREATE TABLE IF NOT EXISTS `messages` (
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
  KEY `conversation_id` (`conversation_id`)
) ENGINE=InnoDB AUTO_INCREMENT=549 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `conversation_id`, `sender_type`, `sender_id`, `content`, `type`, `file_path`, `file_name`, `file_size`, `created_at`, `is_read`, `is_delivered`) VALUES
(194, 254, 'admin', 16, 'test', '', NULL, NULL, NULL, '2026-02-11 17:29:17', 1, 1),
(195, 254, 'admin', 16, 'derre', '', NULL, NULL, NULL, '2026-02-11 17:29:44', 1, 1),
(196, 254, 'enseignant', 15, 'aloan\'izay', '', NULL, NULL, NULL, '2026-02-11 17:29:57', 1, 1),
(197, 254, 'enseignant', 15, '[Image: hot-fuuz - Copie.jpg]', '', 'uploads/chat_files/1770831028_698cbcb461d1d.jpg', 'hot-fuuz - Copie.jpg', 553686, '2026-02-11 17:30:28', 1, 1),
(198, 264, 'staff', 20, 'salut frère', '', NULL, NULL, NULL, '2026-02-11 19:49:13', 1, 1),
(199, 262, 'staff', 20, 'test', '', NULL, NULL, NULL, '2026-02-11 19:50:34', 1, 1),
(200, 262, 'enseignant', 15, 'ouiui', '', NULL, NULL, NULL, '2026-02-11 19:50:43', 1, 1),
(201, 262, 'enseignant', 15, 'ça roule?', '', NULL, NULL, NULL, '2026-02-11 20:00:52', 1, 1),
(202, 262, 'staff', 20, 'parfait très bien', '', NULL, NULL, NULL, '2026-02-11 20:01:07', 1, 1),
(203, 262, 'enseignant', 15, 'alors couvre moi', '', NULL, NULL, NULL, '2026-02-11 20:01:31', 1, 1),
(204, 262, 'enseignant', 15, 'couvre', '', NULL, NULL, NULL, '2026-02-11 20:01:38', 1, 1),
(205, 262, 'staff', 20, '[Image: 1753116466_8.jpg]', '', 'uploads/chat_files/1770840119_698ce037e417e.jpg', '1753116466_8.jpg', 27058, '2026-02-11 20:01:59', 1, 1),
(206, 262, 'staff', 20, 'heyeuy', '', NULL, NULL, NULL, '2026-02-11 20:12:17', 1, 1),
(207, 262, 'staff', 20, 'dedzdzdedzseze', '', NULL, NULL, NULL, '2026-02-11 20:23:51', 1, 1),
(208, 262, 'enseignant', 15, 'dededzzd', '', NULL, NULL, NULL, '2026-02-11 20:23:58', 1, 1),
(209, 262, 'staff', 20, 'dererere', '', NULL, NULL, NULL, '2026-02-11 20:48:11', 1, 1),
(210, 262, 'enseignant', 15, 'rfrrfrfesefesef', '', NULL, NULL, NULL, '2026-02-11 20:48:20', 1, 1),
(211, 262, 'enseignant', 15, 'dererer', '', NULL, NULL, NULL, '2026-02-11 20:51:10', 1, 1),
(212, 262, 'staff', 20, 'dede', '', NULL, NULL, NULL, '2026-02-11 20:51:16', 1, 1),
(213, 262, 'staff', 20, 'dede', '', NULL, NULL, NULL, '2026-02-11 20:51:19', 1, 1),
(214, 262, 'enseignant', 15, 'derereseress', '', NULL, NULL, NULL, '2026-02-11 20:58:27', 0, 0),
(215, 261, 'admin', 14, 'dereb', '', NULL, NULL, NULL, '2026-02-11 20:59:07', 1, 1),
(216, 261, 'staff', 20, 'derederre', '', NULL, NULL, NULL, '2026-02-11 20:59:17', 1, 1),
(217, 261, 'admin', 14, 'rereserderdr', '', NULL, NULL, NULL, '2026-02-11 20:59:26', 0, 0),
(218, 258, 'admin', 19, 'salu', '', NULL, NULL, NULL, '2026-02-11 21:00:45', 1, 1),
(219, 258, 'admin', 19, 'dererrer', '', NULL, NULL, NULL, '2026-02-11 21:00:52', 1, 1),
(220, 258, 'admin', 16, 'cdehtredsdsde', '', NULL, NULL, NULL, '2026-02-11 21:01:02', 1, 1),
(221, 258, 'admin', 16, '[Voice: 2s]', '', 'uploads/chat_files/1770844172_698cf00cefa7b.webm', 'Voice_2026-02-11_2109.webm', 22514, '2026-02-11 21:09:32', 1, 1),
(222, 258, 'admin', 19, 'deredre', '', NULL, NULL, NULL, '2026-02-11 21:13:20', 1, 1),
(223, 258, 'admin', 16, 'erdererv', '', NULL, NULL, NULL, '2026-02-11 21:14:07', 1, 1),
(224, 258, 'admin', 19, 'errrerdererereferrer', '', NULL, NULL, NULL, '2026-02-11 21:14:14', 1, 1),
(225, 258, 'admin', 16, 'dereresertrererereer', '', NULL, NULL, NULL, '2026-02-11 21:18:37', 1, 1),
(226, 258, 'admin', 19, 'derecederererrerrresersfrer', '', NULL, NULL, NULL, '2026-02-11 21:24:32', 1, 1),
(227, 258, 'admin', 16, 'rrretrer', '', NULL, NULL, NULL, '2026-02-11 21:24:37', 1, 1),
(228, 258, 'admin', 19, 'derrtrertresert', '', NULL, NULL, NULL, '2026-02-11 21:40:57', 1, 1),
(229, 258, 'admin', 16, 'rreterrer', '', NULL, NULL, NULL, '2026-02-11 21:41:06', 1, 1),
(230, 258, 'admin', 19, 'derezazerrere', '', NULL, NULL, NULL, '2026-02-11 21:44:25', 1, 1),
(231, 258, 'admin', 16, 'edererer', '', NULL, NULL, NULL, '2026-02-11 21:45:48', 1, 1),
(232, 258, 'admin', 16, 'redertder', '', NULL, NULL, NULL, '2026-02-11 21:45:53', 1, 1),
(233, 258, 'admin', 16, 'derecde', '', NULL, NULL, NULL, '2026-02-11 21:53:46', 1, 1),
(234, 258, 'admin', 19, 'deded', '', NULL, NULL, NULL, '2026-02-11 21:53:50', 1, 1),
(235, 258, 'admin', 16, 'dedede', '', NULL, NULL, NULL, '2026-02-11 21:54:02', 1, 1),
(236, 258, 'admin', 19, 'dede', '', NULL, NULL, NULL, '2026-02-11 21:54:10', 1, 1),
(237, 270, 'admin', 19, 'dedede', '', NULL, NULL, NULL, '2026-02-11 22:09:54', 0, 0),
(238, 270, 'admin', 19, 'Les gars salut', '', NULL, NULL, NULL, '2026-02-11 22:18:05', 0, 0),
(239, 271, 'admin', 19, 'dertee', '', NULL, NULL, NULL, '2026-02-11 22:19:00', 1, 0),
(240, 271, 'admin', 16, 'derre', '', NULL, NULL, NULL, '2026-02-11 22:40:47', 1, 0),
(241, 271, 'admin', 16, 'der', '', NULL, NULL, NULL, '2026-02-11 22:47:50', 1, 0),
(242, 271, 'admin', 19, 'no', '', NULL, NULL, NULL, '2026-02-11 22:50:58', 1, 0),
(243, 273, 'admin', 19, 'dertt', '', NULL, NULL, NULL, '2026-02-11 22:51:55', 0, 0),
(244, 271, 'admin', 19, 'dedee', '', NULL, NULL, NULL, '2026-02-11 22:58:19', 1, 0),
(245, 274, 'admin', 19, 'cool', '', NULL, NULL, NULL, '2026-02-11 22:59:28', 1, 0),
(246, 271, 'admin', 19, 'dert', '', NULL, NULL, NULL, '2026-02-11 23:02:43', 1, 0),
(247, 271, 'admin', 16, 'dedede', '', NULL, NULL, NULL, '2026-02-11 23:02:58', 1, 0),
(248, 274, 'admin', 16, 'path', '', NULL, NULL, NULL, '2026-02-11 23:04:19', 1, 0),
(249, 274, 'admin', 19, 'brrrr', '', NULL, NULL, NULL, '2026-02-11 23:04:28', 1, 0),
(250, 274, 'admin', 16, 'het', '', NULL, NULL, NULL, '2026-02-11 23:08:42', 1, 0),
(251, 274, 'admin', 19, 'fert', '', NULL, NULL, NULL, '2026-02-11 23:08:49', 1, 0),
(252, 271, 'admin', 16, 'dert', '', NULL, NULL, NULL, '2026-02-11 23:09:04', 1, 0),
(253, 274, 'admin', 16, 'seryer', '', NULL, NULL, NULL, '2026-02-11 23:12:46', 1, 0),
(254, 274, 'admin', 16, 'terst', '', NULL, NULL, NULL, '2026-02-11 23:12:54', 1, 0),
(255, 274, 'admin', 19, 'gttrtr', '', NULL, NULL, NULL, '2026-02-11 23:12:58', 1, 0),
(256, 274, 'admin', 16, 'fere', '', NULL, NULL, NULL, '2026-02-11 23:13:05', 1, 0),
(257, 274, 'admin', 16, 'yo', '', NULL, NULL, NULL, '2026-02-11 23:13:56', 1, 0),
(258, 274, 'admin', 19, 'ert', '', NULL, NULL, NULL, '2026-02-11 23:14:03', 1, 0),
(259, 258, 'admin', 16, '[Image: 1753787667_DSC_5225.jpg]', '', 'uploads/chat_files/1770870898_698d5872b01e6.jpg', '1753787667_DSC_5225.jpg', 873491, '2026-02-12 04:34:58', 1, 1),
(260, 258, 'admin', 19, 'hey', '', NULL, NULL, NULL, '2026-02-12 10:52:15', 1, 1),
(261, 258, 'admin', 19, 'oui salut', '', NULL, NULL, NULL, '2026-02-12 13:14:19', 1, 1),
(262, 258, 'admin', 16, 'quoi que veux tu?', '', NULL, NULL, NULL, '2026-02-12 13:14:35', 1, 1),
(263, 274, 'admin', 16, 'dedede', '', NULL, NULL, NULL, '2026-02-12 13:23:30', 1, 0),
(264, 271, 'admin', 16, 'dede', '', NULL, NULL, NULL, '2026-02-12 13:24:00', 1, 0),
(265, 274, 'admin', 19, 'deded', '', NULL, NULL, NULL, '2026-02-12 13:24:05', 1, 0),
(266, 274, 'admin', 19, 'dede', '', NULL, NULL, NULL, '2026-02-12 13:24:12', 1, 0),
(267, 271, 'admin', 16, 'hey', '', NULL, NULL, NULL, '2026-02-12 13:26:30', 1, 0),
(268, 274, 'admin', 19, 'coucou', '', NULL, NULL, NULL, '2026-02-12 13:26:35', 1, 0),
(269, 271, 'admin', 19, 'quoi', '', NULL, NULL, NULL, '2026-02-12 13:26:42', 1, 0),
(270, 274, 'admin', 19, '[Image: hot-fuuz - Copie.jpg]', '', 'uploads/chat_files/1770903456_698dd7a0b9f18.jpg', 'hot-fuuz - Copie.jpg', 553686, '2026-02-12 13:37:36', 1, 0),
(271, 274, 'admin', 16, 'dert', '', NULL, NULL, NULL, '2026-02-12 13:38:04', 1, 0),
(272, 274, 'admin', 16, 'dede', '', NULL, NULL, NULL, '2026-02-12 13:38:15', 1, 0),
(273, 274, 'admin', 16, 'dede', '', NULL, NULL, NULL, '2026-02-12 14:25:31', 1, 0),
(274, 274, 'admin', 16, 'dde', '', NULL, NULL, NULL, '2026-02-12 14:26:13', 1, 0),
(275, 274, 'admin', 16, 'xxx', '', NULL, NULL, NULL, '2026-02-12 14:26:53', 1, 0),
(276, 274, 'admin', 16, '[Image: 1753116398_5.jpg]', '', 'uploads/chat_files/1770906423_698de33725d32.jpg', '1753116398_5.jpg', 25095, '2026-02-12 14:27:03', 1, 0),
(277, 274, 'admin', 16, 'ddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccfffffffffffffffvvvvvvvvvvvdddddddd', '', NULL, NULL, NULL, '2026-02-12 14:28:12', 1, 0),
(278, 258, 'admin', 16, 'ccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccc', '', NULL, NULL, NULL, '2026-02-12 14:28:55', 1, 1),
(279, 271, 'admin', 0, 'xss', '', NULL, NULL, NULL, '2026-02-12 14:31:07', 1, 0),
(280, 271, 'admin', 16, 'szszs', '', NULL, NULL, NULL, '2026-02-12 14:31:12', 1, 0),
(281, 271, 'admin', 16, 'szs', '', NULL, NULL, NULL, '2026-02-12 14:31:35', 1, 0),
(282, 271, 'admin', 0, 'szsz', '', NULL, NULL, NULL, '2026-02-12 14:31:37', 1, 0),
(283, 271, 'admin', 0, 'dd', '', NULL, NULL, NULL, '2026-02-12 14:49:06', 1, 0),
(284, 271, 'admin', 0, 'dd', '', NULL, NULL, NULL, '2026-02-12 14:54:58', 1, 0),
(285, 271, 'admin', 16, 'ddd', '', NULL, NULL, NULL, '2026-02-12 14:55:08', 1, 0),
(286, 271, 'admin', 0, 'ddedee', '', NULL, NULL, NULL, '2026-02-12 15:40:20', 1, 0),
(287, 271, 'admin', 0, 'dede', '', NULL, NULL, NULL, '2026-02-12 15:40:23', 1, 0),
(288, 271, 'admin', 14, 'cool', '', NULL, NULL, NULL, '2026-02-12 15:56:20', 1, 0),
(289, 274, 'admin', 14, 'feyurttrt', '', NULL, NULL, NULL, '2026-02-12 16:06:40', 1, 0),
(290, 274, 'admin', 14, 'J\'aimerai bien te parler aujourd\'hui vieux, il ya un trux qui cloche', '', NULL, NULL, NULL, '2026-02-12 16:20:41', 1, 0),
(291, 261, 'admin', 14, 'ffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffff', '', NULL, NULL, NULL, '2026-02-12 17:01:16', 0, 0),
(292, 261, 'admin', 14, '[Image: 1753116347_2.jpg]', '', 'uploads/chat_files/1770916964_698e0c64061b5.jpg', '1753116347_2.jpg', 41661, '2026-02-12 17:22:44', 0, 0),
(293, 271, 'admin', 14, 'tream', '', NULL, NULL, NULL, '2026-02-12 17:27:31', 1, 0),
(294, 274, 'admin', 14, 'team', '', NULL, NULL, NULL, '2026-02-12 17:27:37', 1, 0),
(295, 274, 'admin', 14, 'dedede', '', NULL, NULL, NULL, '2026-02-12 17:42:41', 1, 0),
(296, 274, 'admin', 14, 'ddd', '', NULL, NULL, NULL, '2026-02-12 17:42:44', 1, 0),
(297, 274, 'admin', 14, '[image: 1753116449_7.jpg]', '', 'uploads/chat_files/1770918187_698e112b44ba4.jpg', '1753116449_7.jpg', 40611, '2026-02-12 17:43:07', 1, 0),
(298, 274, 'admin', 14, '[image: 1753116466_8.jpg]', '', 'uploads/chat_files/1770918799_698e138f6ac5b.jpg', '1753116466_8.jpg', 27058, '2026-02-12 17:53:19', 1, 0),
(299, 274, 'admin', 14, 'dernier tes', '', NULL, NULL, NULL, '2026-02-12 17:58:10', 1, 0),
(300, 274, 'admin', 14, '[image: 1753116449_7.jpg]', '', 'uploads/chat_files/1770919100_698e14bcaa077.jpg', '1753116449_7.jpg', 40611, '2026-02-12 17:58:20', 1, 0),
(301, 274, 'admin', 14, 'tes deux', '', NULL, NULL, NULL, '2026-02-12 18:07:47', 1, 0),
(302, 274, 'admin', 14, '[image: 1753116347_2.jpg]', '', 'uploads/chat_files/1770919699_698e1713de6e5.jpg', '1753116347_2.jpg', 41661, '2026-02-12 18:08:19', 1, 0),
(303, 274, 'admin', 14, 'ferr', '', NULL, NULL, NULL, '2026-02-12 18:36:38', 1, 0),
(304, 274, 'admin', 14, 'derter', '', NULL, NULL, NULL, '2026-02-12 18:36:45', 1, 0),
(305, 271, 'admin', 14, 'dede', '', NULL, NULL, NULL, '2026-02-12 18:36:50', 1, 0),
(306, 283, 'admin', 14, 'test', '', NULL, NULL, NULL, '2026-02-12 18:46:26', 1, 0),
(307, 283, 'admin', 14, 'dedede', '', NULL, NULL, NULL, '2026-02-12 18:46:39', 1, 0),
(308, 283, 'admin', 16, 'test', '', NULL, NULL, NULL, '2026-02-12 18:48:31', 1, 0),
(309, 283, 'admin', 16, 'okay ça marche vieux', '', NULL, NULL, NULL, '2026-02-12 19:08:18', 1, 0),
(310, 283, 'admin', 19, 'no, encore une erreurs, nooo', '', NULL, NULL, NULL, '2026-02-12 19:08:47', 1, 0),
(311, 283, 'admin', 19, 'noooo', '', NULL, NULL, NULL, '2026-02-12 19:08:54', 1, 0),
(312, 283, 'admin', 16, 'please revien', '', NULL, NULL, NULL, '2026-02-12 19:09:02', 1, 0),
(313, 283, 'admin', 16, '[image: Error à régler.png]', '', 'uploads/chat_files/1770923418_698e259a9d6a5.png', 'Error à régler.png', 964841, '2026-02-12 19:10:18', 1, 0),
(314, 283, 'admin', 19, 'heyy', '', NULL, NULL, NULL, '2026-02-12 19:19:49', 1, 0),
(315, 283, 'admin', 16, 'quoi?', '', NULL, NULL, NULL, '2026-02-12 19:20:13', 1, 0),
(316, 283, 'admin', 19, 'e\"', '', NULL, NULL, NULL, '2026-02-12 19:21:16', 1, 0),
(317, 283, 'admin', 16, 'test', '', NULL, NULL, NULL, '2026-02-12 19:26:13', 1, 0),
(318, 283, 'admin', 16, 'tes à nouveau', '', NULL, NULL, NULL, '2026-02-12 19:26:40', 1, 0),
(319, 274, 'admin', 16, 'test encore', '', NULL, NULL, NULL, '2026-02-12 19:27:05', 1, 0),
(320, 274, 'admin', 16, 'team', '', NULL, NULL, NULL, '2026-02-12 19:29:47', 1, 0),
(321, 274, 'admin', 16, 'ohh', '', NULL, NULL, NULL, '2026-02-12 19:29:54', 1, 0),
(322, 283, 'admin', 16, 'test', '', NULL, NULL, NULL, '2026-02-12 19:30:07', 1, 0),
(323, 283, 'admin', 16, 'test', '', NULL, NULL, NULL, '2026-02-12 19:38:16', 1, 0),
(324, 283, 'admin', 16, 'encore', '', NULL, NULL, NULL, '2026-02-12 19:38:30', 1, 0),
(325, 274, 'admin', 19, 'nooo', '', NULL, NULL, NULL, '2026-02-12 19:38:47', 1, 0),
(326, 274, 'admin', 16, '[image: hot-fuuz - Copie.jpg]', '', 'uploads/chat_files/1770925149_698e2c5dd9c30.jpg', 'hot-fuuz - Copie.jpg', 553686, '2026-02-12 19:39:09', 1, 0),
(327, 274, 'admin', 19, 'c\'est quoi?', '', NULL, NULL, NULL, '2026-02-12 19:39:44', 1, 0),
(328, 283, 'admin', 19, 'ça marche vieux, c\'est bon', '', NULL, NULL, NULL, '2026-02-12 19:40:07', 1, 0),
(329, 274, 'admin', 16, 'test farany', '', NULL, NULL, NULL, '2026-02-12 19:41:15', 1, 0),
(330, 283, 'admin', 16, 'je dois le tester encore', '', NULL, NULL, NULL, '2026-02-12 19:47:53', 1, 0),
(331, 283, 'admin', 19, 'ça va', '', NULL, NULL, NULL, '2026-02-12 19:48:01', 1, 0),
(332, 283, 'admin', 19, 'test encore', '', NULL, NULL, NULL, '2026-02-12 19:48:16', 1, 0),
(333, 283, 'admin', 16, 'dernier dernier test avant l\'ajout de l\'avatar', '', NULL, NULL, NULL, '2026-02-12 19:50:17', 1, 0),
(334, 283, 'admin', 19, 'parfait c\'est rapide', '', NULL, NULL, NULL, '2026-02-12 19:50:26', 1, 0),
(335, 283, 'admin', 16, 'oui c\'est ultra rapide', '', NULL, NULL, NULL, '2026-02-12 19:50:39', 1, 0),
(336, 283, 'admin', 19, 'le dernier test avec une vitesse de 350 ms', '', NULL, NULL, NULL, '2026-02-12 19:51:24', 1, 0),
(337, 283, 'admin', 16, 'woaouuu c\'est ultra rapide', '', NULL, NULL, NULL, '2026-02-12 19:51:34', 1, 0),
(338, 283, 'admin', 16, 'GG', '', NULL, NULL, NULL, '2026-02-12 19:51:38', 1, 0),
(339, 274, 'admin', 19, 'c\'est gravement belle', '', NULL, NULL, NULL, '2026-02-12 19:51:53', 1, 0),
(340, 258, 'admin', 19, 'what the fuck vieux?', '', NULL, NULL, NULL, '2026-02-12 21:05:07', 1, 1),
(341, 258, 'admin', 19, 'nothing', '', NULL, NULL, NULL, '2026-02-13 04:19:15', 1, 1),
(342, 258, 'admin', 19, 'ça marhe?', '', NULL, NULL, NULL, '2026-02-13 04:20:56', 1, 1),
(343, 271, 'admin', 16, 'cool', '', NULL, NULL, NULL, '2026-02-13 04:26:47', 1, 0),
(344, 261, 'admin', 14, 'hoy', '', NULL, NULL, NULL, '2026-02-13 07:57:33', 0, 0),
(345, 281, 'admin', 19, 'Hello Tojo😁', '', NULL, NULL, NULL, '2026-02-13 07:58:58', 1, 1),
(346, 281, 'admin', 14, 'Hello stessy, quelle belle journée hein? 💕😍😉', '', NULL, NULL, NULL, '2026-02-13 07:59:56', 1, 1),
(347, 281, 'admin', 14, 'J\'avoue j\'ai hâte de te voir', '', NULL, NULL, NULL, '2026-02-13 08:00:15', 1, 1),
(348, 281, 'admin', 19, 'Je tes vieux 😁🤣', '', NULL, NULL, NULL, '2026-02-13 08:02:52', 1, 1),
(349, 281, 'admin', 19, 'Salut veilles, je veux te bam bam', '', NULL, NULL, NULL, '2026-02-13 08:03:54', 1, 1),
(353, 283, 'admin', 19, 'chiao', '', NULL, NULL, NULL, '2026-02-13 08:52:41', 1, 0),
(354, 281, 'admin', 19, '[Voice: 0s]', '', 'uploads/chat_files/1770984684_698f14ecefccb.webm', 'Voice_2026-02-13_1211.webm', 110, '2026-02-13 12:11:24', 1, 1),
(357, 283, 'admin', 19, '[image: 1753116347_2.jpg]', '', 'uploads/chat_files/1770984784_698f155057169.jpg', '1753116347_2.jpg', 41661, '2026-02-13 12:13:04', 1, 0),
(358, 281, 'admin', 14, 'test blablabla,', '', NULL, NULL, NULL, '2026-02-13 12:50:40', 1, 1),
(359, 281, 'admin', 19, 'quoi?', '', NULL, NULL, NULL, '2026-02-13 12:50:54', 1, 1),
(360, 281, 'admin', 14, 'non ce n\'est rien', '', NULL, NULL, NULL, '2026-02-13 12:51:02', 1, 1),
(361, 281, 'admin', 14, 'on test', '', NULL, NULL, NULL, '2026-02-13 12:51:09', 1, 1),
(362, 281, 'admin', 14, 'encre', '', NULL, NULL, NULL, '2026-02-13 12:51:18', 1, 1),
(363, 281, 'admin', 19, 'ça foncitonne la photo', '', NULL, NULL, NULL, '2026-02-13 13:02:20', 1, 1),
(364, 281, 'admin', 14, 'ah ouias', '', NULL, NULL, NULL, '2026-02-13 13:02:57', 1, 1),
(365, 281, 'admin', 14, 'parfait j\'aime bien', '', NULL, NULL, NULL, '2026-02-13 13:03:08', 1, 1),
(366, 281, 'admin', 19, 'test maintenant', '', NULL, NULL, NULL, '2026-02-13 13:18:53', 1, 1),
(367, 281, 'admin', 19, 'cool', '', NULL, NULL, NULL, '2026-02-13 13:19:46', 1, 1),
(368, 281, 'admin', 14, 'okay', '', NULL, NULL, NULL, '2026-02-13 13:19:53', 1, 1),
(369, 281, 'admin', 14, 'teg', '', NULL, NULL, NULL, '2026-02-13 13:50:37', 1, 1),
(370, 281, 'admin', 14, 'test', '', NULL, NULL, NULL, '2026-02-13 13:53:02', 1, 1),
(371, 281, 'admin', 19, 'oui', '', NULL, NULL, NULL, '2026-02-13 13:53:18', 1, 1),
(372, 281, 'admin', 19, '[Image: 1753116419_6.jpg]', '', 'uploads/chat_files/1770990806_698f2cd6e44d1.jpg', '1753116419_6.jpg', 43167, '2026-02-13 13:53:26', 1, 1),
(373, 281, 'admin', 14, '[File: test présence.pdf]', '', 'uploads/chat_files/1770990834_698f2cf225c43.pdf', 'test présence.pdf', 723092, '2026-02-13 13:53:54', 1, 1),
(374, 281, 'admin', 19, '[Image: 1753116449_7.jpg]', '', 'uploads/chat_files/1770992563_698f33b37f414.jpg', '1753116449_7.jpg', 40611, '2026-02-13 14:22:43', 1, 1),
(375, 281, 'admin', 14, '[Image: 1753117426_3.jpg]', '', 'uploads/chat_files/1770992945_698f353139cc1.jpg', '1753117426_3.jpg', 47934, '2026-02-13 14:29:05', 1, 1),
(376, 281, 'admin', 19, 'tape your message here if you are busy', '', NULL, NULL, NULL, '2026-02-13 14:31:36', 1, 1),
(377, 281, 'admin', 19, 'okay thanks me later', '', NULL, NULL, NULL, '2026-02-13 14:31:47', 1, 1),
(378, 281, 'admin', 19, '[Voice: 5.5s]', '', 'uploads/chat_files/1770994093_698f39ad8538d.webm', 'Voice_2026-02-13_14-48-13.webm', 78542, '2026-02-13 14:48:13', 1, 1),
(379, 281, 'admin', 19, 'dederr', '', NULL, NULL, NULL, '2026-02-13 14:48:20', 1, 1),
(380, 281, 'admin', 19, 'dederdede', '', NULL, NULL, NULL, '2026-02-13 14:48:30', 1, 1),
(381, 281, 'admin', 14, '[Image: 1754583486_DSC_5326.jpg]', '', 'uploads/chat_files/1770994128_698f39d020850.jpg', '1754583486_DSC_5326.jpg', 1081334, '2026-02-13 14:48:48', 1, 1),
(382, 281, 'admin', 14, '[Voice: 2.8s]', '', 'uploads/chat_files/1770994133_698f39d5ebe41.webm', 'Voice_2026-02-13_14-48-53.webm', 40868, '2026-02-13 14:48:53', 1, 1),
(383, 281, 'admin', 19, 'Detecetion d\'un coup dans le cours, alors, il faut ajouter un bijou qui capte automatiquement les voix des animaux et surtout tenter de rester en lgnes', '', NULL, NULL, NULL, '2026-02-13 14:55:01', 1, 1),
(384, 281, 'admin', 19, 'dert', '', NULL, NULL, NULL, '2026-02-13 15:43:22', 1, 1),
(385, 281, 'admin', 19, '[Image: 1753116466_8.jpg]', '', 'uploads/chat_files/1770997415_698f46a719426.jpg', '1753116466_8.jpg', 27058, '2026-02-13 15:43:35', 1, 1),
(386, 281, 'admin', 14, 'dert', '', NULL, NULL, NULL, '2026-02-13 15:44:43', 1, 1),
(387, 281, 'admin', 14, '[Image: 1753117465_3.jpg]', '', 'uploads/chat_files/1770997491_698f46f357b8a.jpg', '1753117465_3.jpg', 43057, '2026-02-13 15:44:51', 1, 1),
(388, 281, 'admin', 14, '[Voice: 1.4s]', '', 'uploads/chat_files/1770997496_698f46f80fe41.webm', 'Voice_2026-02-13_15-44-56.webm', 12854, '2026-02-13 15:44:56', 1, 1),
(389, 258, 'admin', 19, 'deeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeee', '', NULL, NULL, NULL, '2026-02-13 16:13:23', 1, 1),
(390, 258, 'admin', 19, '[Image: 1753116449_7.jpg]', '', 'uploads/chat_files/1770999634_698f4f52da144.jpg', '1753116449_7.jpg', 40611, '2026-02-13 16:20:34', 1, 1),
(391, 258, 'admin', 19, '[Image: 1753116449_7.jpg]', '', 'uploads/chat_files/1770999687_698f4f8797ed9.jpg', '1753116449_7.jpg', 40611, '2026-02-13 16:21:27', 1, 1),
(392, 258, 'admin', 19, '[Voice: 1.5s]', '', 'uploads/chat_files/1770999693_698f4f8d59724.webm', 'Voice_2026-02-13_16-21-33.webm', 13820, '2026-02-13 16:21:33', 1, 1),
(393, 281, 'admin', 14, 'coucou', '', NULL, NULL, NULL, '2026-02-13 16:32:00', 1, 1),
(394, 281, 'admin', 14, 'hey', '', NULL, NULL, NULL, '2026-02-13 16:32:38', 1, 1),
(395, 281, 'admin', 14, 'test farany', '', NULL, NULL, NULL, '2026-02-13 16:36:48', 1, 1),
(396, 281, 'admin', 14, 'testpigé', '', NULL, NULL, NULL, '2026-02-13 16:37:00', 1, 1),
(397, 281, 'admin', 19, 'coucou bébé', '', NULL, NULL, NULL, '2026-02-13 16:46:20', 1, 1),
(398, 281, 'admin', 19, 'ça va', '', NULL, NULL, NULL, '2026-02-13 16:46:28', 1, 1),
(399, 281, 'admin', 14, 'yes bébé, je vais bien', '', NULL, NULL, NULL, '2026-02-13 16:46:43', 1, 1),
(400, 292, 'admin', 19, 'dederrr', '', NULL, NULL, NULL, '2026-02-13 17:29:59', 1, 0),
(401, 292, 'admin', 19, 'derr', '', NULL, NULL, NULL, '2026-02-13 17:37:35', 1, 0),
(402, 292, 'admin', 19, 'de', '', NULL, NULL, NULL, '2026-02-13 17:38:57', 1, 0),
(403, 271, 'admin', 19, '[image: 1753116419_6.jpg]', '', 'uploads/chat_files/1771005001_698f64491114b.jpg', '1753116419_6.jpg', 43167, '2026-02-13 17:50:01', 1, 0),
(404, 283, 'admin', 16, 'test envoi', '', NULL, NULL, NULL, '2026-02-13 18:00:07', 1, 0),
(405, 292, 'admin', 16, 'quoi', '', NULL, NULL, NULL, '2026-02-13 18:00:30', 1, 0),
(406, 292, 'admin', 16, '[image: image2.jpg]', '', 'uploads/chat_files/1771005937_698f67f16f078.jpg', 'image2.jpg', 52616, '2026-02-13 18:05:37', 1, 0),
(407, 283, 'admin', 19, 'ecrit', '', NULL, NULL, NULL, '2026-02-13 18:15:35', 1, 0),
(408, 283, 'admin', 19, 'quoi,', '', NULL, NULL, NULL, '2026-02-13 18:15:55', 1, 0),
(409, 283, 'admin', 19, 'C4EST INJUSTE', '', NULL, NULL, NULL, '2026-02-13 18:16:03', 1, 0),
(410, 283, 'admin', 19, '[Image envoyée]', '', 'uploads/chat_files/1771006625_698f6aa15e68a.jpg', '1753116449_7.jpg', 40611, '2026-02-13 18:17:05', 1, 0),
(411, 283, 'admin', 16, 'BORDER', '', NULL, NULL, NULL, '2026-02-13 18:17:31', 1, 0),
(412, 271, 'admin', 16, 'sérieux', '', NULL, NULL, NULL, '2026-02-13 18:30:22', 1, 0),
(413, 271, 'admin', 16, 'derc', '', NULL, NULL, NULL, '2026-02-13 18:30:39', 1, 0),
(414, 271, 'admin', 16, 'dedede', '', NULL, NULL, NULL, '2026-02-13 18:31:06', 1, 0),
(415, 271, 'admin', 16, '[Image envoyée]', '', 'uploads/chat_files/1771007679_698f6ebf7f3ff.jpg', '1754548480_DSC_5203.jpg', 1100587, '2026-02-13 18:34:39', 1, 0),
(416, 283, 'admin', 19, 'vieux', '', NULL, NULL, NULL, '2026-02-13 18:48:34', 1, 0),
(417, 271, 'admin', 19, 'wowww j\'adore ça vieux', '', NULL, NULL, NULL, '2026-02-13 19:06:10', 1, 0),
(418, 271, 'admin', 19, '[Image envoyée]', '', 'uploads/chat_files/1771009589_698f7635167d9.jpg', '1753116449_7.jpg', 40611, '2026-02-13 19:06:29', 1, 0),
(419, 271, 'admin', 19, 't\'es sur', '', NULL, NULL, NULL, '2026-02-13 19:06:36', 1, 0),
(420, 271, 'admin', 19, '[Image envoyée]', '', 'uploads/chat_files/1771009606_698f7646025a6.jpg', '1753116449_7.jpg', 40611, '2026-02-13 19:06:46', 1, 0),
(421, 271, 'admin', 19, 'quoi?', '', NULL, NULL, NULL, '2026-02-13 19:07:51', 1, 0),
(422, 283, 'admin', 19, 'coucou', '', NULL, NULL, NULL, '2026-02-13 19:13:06', 1, 0),
(423, 283, 'admin', 19, 'salut', '', NULL, NULL, NULL, '2026-02-13 19:13:22', 1, 0),
(424, 274, 'admin', 16, 'quoiKN', '', NULL, NULL, NULL, '2026-02-13 19:13:41', 1, 0),
(425, 274, 'admin', 19, 'rin du tou', '', NULL, NULL, NULL, '2026-02-13 19:15:03', 1, 0),
(426, 274, 'admin', 19, '[Image envoyée]', '', 'uploads/chat_files/1771010108_698f783c8b27e.jpg', '1753116449_7.jpg', 40611, '2026-02-13 19:15:08', 1, 0),
(427, 274, 'admin', 19, 'cool', '', NULL, NULL, NULL, '2026-02-13 19:21:28', 1, 0),
(428, 274, 'admin', 19, 'bien', '', NULL, NULL, NULL, '2026-02-13 19:21:34', 1, 0),
(429, 283, 'admin', 16, 'wowwww ça fonctionne vieux', '', NULL, NULL, NULL, '2026-02-13 19:22:05', 1, 0),
(430, 283, 'admin', 16, '[Image envoyée]', '', 'uploads/chat_files/1771010562_698f7a02224dd.jpg', '1753117426_3.jpg', 47934, '2026-02-13 19:22:42', 1, 0),
(431, 258, 'admin', 19, 'hey stessy', '', NULL, NULL, NULL, '2026-02-13 19:30:21', 1, 1),
(432, 258, 'admin', 16, 'non il fait pas comme ça', '', NULL, NULL, NULL, '2026-02-13 19:30:42', 1, 1),
(433, 258, 'admin', 19, 'alprsdiary', '', NULL, NULL, NULL, '2026-02-13 19:31:30', 1, 1),
(434, 281, 'admin', 14, 'Salut vieux, tu dois bosser now', '', NULL, NULL, NULL, '2026-02-13 19:48:44', 1, 1),
(435, 276, 'enseignant', 17, 'Salut', '', NULL, NULL, NULL, '2026-02-13 19:51:48', 1, 1),
(436, 276, 'admin', 19, 'oui je t\'écoute', '', NULL, NULL, NULL, '2026-02-13 19:52:45', 1, 1),
(437, 281, 'admin', 19, 'salut', '', NULL, NULL, NULL, '2026-02-13 20:40:12', 1, 1),
(438, 277, 'admin', 19, 'salut', '', NULL, NULL, NULL, '2026-02-13 20:40:26', 0, 0),
(439, 283, 'admin', 19, '[Fichier : ]', '', 'uploads/chat_files/1771018478_698f98eea7b67.jpg', '1753117500_9.jpg', 38842, '2026-02-13 21:34:38', 1, 0),
(440, 281, 'admin', 19, 'salut', '', NULL, NULL, NULL, '2026-02-13 21:43:28', 1, 1),
(441, 281, 'admin', 14, 'heye', '', NULL, NULL, NULL, '2026-02-13 21:43:54', 1, 1),
(442, 281, 'admin', 19, 'quooi', '', NULL, NULL, NULL, '2026-02-13 21:44:07', 1, 1),
(443, 281, 'admin', 19, 'répond bon sang', '', NULL, NULL, NULL, '2026-02-13 21:44:21', 1, 1),
(444, 281, 'admin', 14, 'tu veux quoi toi?', '', NULL, NULL, NULL, '2026-02-13 21:44:42', 1, 1),
(445, 266, 'staff', 20, 'salut stessy', '', NULL, NULL, NULL, '2026-02-13 21:45:31', 1, 1),
(446, 266, 'admin', 19, 't\'es qui toi?', '', NULL, NULL, NULL, '2026-02-13 21:45:47', 1, 1),
(447, 266, 'admin', 19, '[Image: 1753117403_5.jpg]', '', 'uploads/chat_files/1771019172_698f9ba453f59.jpg', '1753117403_5.jpg', 39833, '2026-02-13 21:46:12', 1, 1),
(448, 266, 'staff', 20, 'oui jte connais', '', NULL, NULL, NULL, '2026-02-13 21:47:43', 1, 1),
(449, 266, 'staff', 20, 'hey', '', NULL, NULL, NULL, '2026-02-13 21:48:11', 1, 1),
(450, 271, 'staff', 20, 'c\'est trop', '', NULL, NULL, NULL, '2026-02-13 21:51:08', 1, 0),
(451, 271, 'admin', 19, 'ouii', '', NULL, NULL, NULL, '2026-02-13 21:53:58', 1, 0),
(452, 266, 'staff', 20, 'stessy', '', NULL, NULL, NULL, '2026-02-13 21:54:47', 1, 1),
(453, 281, 'admin', 14, 'steassy?', '', NULL, NULL, NULL, '2026-02-13 21:55:58', 1, 1),
(454, 281, 'admin', 19, 'ouii', '', NULL, NULL, NULL, '2026-02-13 21:56:21', 1, 1),
(455, 276, 'enseignant', 17, '[Image: 1753116449_7.jpg]', '', 'uploads/chat_files/1771062126_6990436eca8a1.jpg', '1753116449_7.jpg', 40611, '2026-02-14 09:42:06', 1, 1),
(456, 271, 'admin', 19, '[Image envoyée]', '', 'uploads/chat_files/1771067801_69905999a97cd.jpg', '1754583486_DSC_5326.jpg', 1081334, '2026-02-14 11:16:41', 1, 0),
(457, 283, 'admin', 19, 'SALUT VIEUX', '', NULL, NULL, NULL, '2026-02-14 11:18:31', 1, 0),
(458, 271, 'admin', 19, 'hey', '', NULL, NULL, NULL, '2026-02-14 11:20:39', 1, 0),
(459, 281, 'admin', 19, 'test', '', NULL, NULL, NULL, '2026-02-14 11:35:58', 1, 1),
(460, 281, 'admin', 19, 'test', '', NULL, NULL, NULL, '2026-02-14 11:36:08', 1, 1),
(461, 281, 'admin', 14, 'tes', '', NULL, NULL, NULL, '2026-02-14 11:36:29', 1, 1),
(462, 281, 'admin', 19, 'test', '', NULL, NULL, NULL, '2026-02-14 11:36:33', 1, 1),
(463, 281, 'admin', 19, '[Image: 1754583486_DSC_5326.jpg]', '', 'uploads/chat_files/1771069014_69905e56435a4.jpg', '1754583486_DSC_5326.jpg', 1081334, '2026-02-14 11:36:54', 1, 1),
(464, 295, 'enseignant', 17, 'test', '', NULL, NULL, NULL, '2026-02-14 11:38:22', 1, 1),
(465, 256, 'admin', 14, 'Manaja', '', NULL, NULL, NULL, '2026-02-14 11:39:20', 1, 1),
(466, 256, 'enseignant', 15, 'oui', '', NULL, NULL, NULL, '2026-02-14 11:39:33', 1, 1),
(467, 279, 'admin', 14, 'kimmmm', '', NULL, NULL, NULL, '2026-02-14 12:02:50', 1, 1),
(468, 276, 'admin', 19, 'Belle photo', '', NULL, NULL, NULL, '2026-02-14 12:03:44', 1, 1),
(469, 276, 'admin', 19, 'wow', '', NULL, NULL, NULL, '2026-02-14 12:03:59', 1, 1),
(470, 276, 'enseignant', 17, 'verifie', '', NULL, NULL, NULL, '2026-02-14 12:04:35', 1, 1),
(471, 276, 'admin', 19, 'ouii', '', NULL, NULL, NULL, '2026-02-14 12:04:43', 1, 1),
(472, 279, 'enseignant', 17, 'iuiiiiiu', '', NULL, NULL, NULL, '2026-02-14 12:04:54', 1, 1),
(473, 276, 'enseignant', 17, 'ouii', '', NULL, NULL, NULL, '2026-02-14 12:05:18', 1, 1),
(474, 258, 'admin', 16, 'heyyy', '', NULL, NULL, NULL, '2026-02-14 12:06:14', 1, 1),
(475, 257, 'admin', 16, 'kimmmm', '', NULL, NULL, NULL, '2026-02-14 12:06:25', 1, 1),
(476, 257, 'admin', 16, '[Image: 1753787640_DSC_5225.jpg]', '', 'uploads/chat_files/1771071420_699067bc1f2cb.jpg', '1753787640_DSC_5225.jpg', 873491, '2026-02-14 12:17:00', 1, 1),
(477, 271, 'admin', 16, 'hey', '', NULL, NULL, NULL, '2026-02-14 12:30:48', 1, 0),
(478, 271, 'admin', 16, 'coollllll', '', NULL, NULL, NULL, '2026-02-14 12:31:01', 1, 0),
(479, 271, 'admin', 16, 'derrr', '', NULL, NULL, NULL, '2026-02-14 12:31:14', 1, 0),
(480, 271, 'enseignant', 15, 'hery', '', NULL, NULL, NULL, '2026-02-15 09:35:57', 1, 0),
(481, 271, 'admin', 16, '[Image envoyée]', '', 'uploads/chat_files/1771262907_699353bbdca5a.jpg', '1753117465_3.jpg', 43057, '2026-02-16 17:28:27', 1, 0),
(482, 275, 'admin', 16, 'Vieux', '', NULL, NULL, NULL, '2026-02-16 17:36:35', 1, 1),
(483, 275, 'admin', 16, '[Image: apropos.jpg]', '', 'uploads/chat_files/1771263415_699355b7c7d79.jpg', 'apropos.jpg', 463529, '2026-02-16 17:36:55', 1, 1),
(484, 275, 'admin', 16, 'Derive', '', NULL, NULL, NULL, '2026-02-17 03:49:18', 1, 1),
(485, 275, 'admin', 16, 'quoi?', '', NULL, NULL, NULL, '2026-02-17 03:49:33', 1, 1),
(486, 275, 'admin', 16, 'serey', '', NULL, NULL, NULL, '2026-02-17 03:49:49', 1, 1),
(487, 271, 'staff', 20, 'Guys', '', NULL, NULL, NULL, '2026-02-17 03:59:28', 1, 0),
(488, 263, 'staff', 20, 'Hey diary', '', NULL, NULL, NULL, '2026-02-17 04:02:57', 1, 1),
(489, 263, 'staff', 20, 'salut', '', NULL, NULL, NULL, '2026-02-17 04:03:06', 1, 1),
(490, 263, 'staff', 20, 'oui ça marche c\'est rapide', '', NULL, NULL, NULL, '2026-02-17 04:03:20', 1, 1),
(491, 275, 'admin', 14, 'merci pour votre message', '', NULL, NULL, NULL, '2026-02-17 04:04:04', 1, 1),
(492, 261, 'admin', 14, 'ouii jecrois que c\'est ici', '', NULL, NULL, NULL, '2026-02-17 04:04:39', 0, 0),
(493, 261, 'admin', 14, 'c\'est ci', '', NULL, NULL, NULL, '2026-02-17 04:04:49', 0, 0),
(494, 275, 'admin', 14, 'merci encore', '', NULL, NULL, NULL, '2026-02-17 04:06:02', 1, 1),
(495, 275, 'admin', 14, 'merci encore', '', NULL, NULL, NULL, '2026-02-17 04:06:12', 1, 1),
(496, 275, 'admin', 14, 'thanks', '', NULL, NULL, NULL, '2026-02-17 04:06:23', 1, 1),
(497, 275, 'admin', 14, 'thanks', '', NULL, NULL, NULL, '2026-02-17 04:07:56', 1, 1),
(498, 275, 'admin', 14, 'a lot', '', NULL, NULL, NULL, '2026-02-17 04:08:01', 1, 1),
(499, 275, 'admin', 16, 'okay', '', NULL, NULL, NULL, '2026-02-17 04:08:27', 1, 1),
(500, 275, 'admin', 14, 'ouiii', '', NULL, NULL, NULL, '2026-02-17 04:10:39', 1, 1),
(501, 275, 'admin', 16, 'okey', '', NULL, NULL, NULL, '2026-02-17 04:10:45', 1, 1),
(502, 275, 'admin', 16, 'quoi', '', NULL, NULL, NULL, '2026-02-17 04:10:53', 1, 1),
(503, 275, 'admin', 16, 'quoi', '', NULL, NULL, NULL, '2026-02-17 04:10:57', 1, 1),
(504, 275, 'admin', 14, 'okey', '', NULL, NULL, NULL, '2026-02-17 04:11:03', 1, 1),
(505, 258, 'admin', 19, 'terr', '', NULL, NULL, NULL, '2026-02-18 06:35:50', 1, 1),
(506, 258, 'admin', 19, 'ouii', '', NULL, NULL, NULL, '2026-02-18 06:37:25', 1, 1),
(507, 258, 'admin', 19, 'tes', '', NULL, NULL, NULL, '2026-02-18 07:05:20', 1, 1),
(508, 258, 'admin', 16, 'saut', '', NULL, NULL, NULL, '2026-02-18 07:08:24', 1, 1),
(509, 258, 'admin', 19, 'ouiiisalut', '', NULL, NULL, NULL, '2026-02-18 07:08:40', 1, 1),
(510, 258, 'admin', 19, 'non je test', '', NULL, NULL, NULL, '2026-02-18 07:10:09', 1, 1),
(511, 258, 'admin', 19, 'salutt', '', NULL, NULL, NULL, '2026-02-18 07:20:26', 1, 1),
(512, 258, 'admin', 19, 'je suis sur que ça marche', '', NULL, NULL, NULL, '2026-02-18 07:20:41', 1, 1),
(513, 258, 'admin', 16, 'tu crois vraiment', '', NULL, NULL, NULL, '2026-02-18 07:20:50', 1, 1),
(514, 258, 'admin', 19, 'et si je tes', '', NULL, NULL, NULL, '2026-02-18 07:21:17', 1, 1),
(515, 276, 'admin', 19, '[File: MY ENGLISH.docx]', '', 'uploads/chat_files/1771399323_6995689b0b3cf.docx', 'MY ENGLISH.docx', 17123, '2026-02-18 07:22:03', 1, 1),
(516, 276, 'admin', 19, '[Image: er.png]', '', 'uploads/chat_files/1771399335_699568a7b2f4d.png', 'er.png', 364169, '2026-02-18 07:22:15', 1, 1),
(517, 276, 'enseignant', 17, 'c\'est parfait', '', NULL, NULL, NULL, '2026-02-18 07:23:04', 1, 1),
(518, 276, 'admin', 19, 'd\'accord', '', NULL, NULL, NULL, '2026-02-18 07:23:17', 1, 1),
(519, 271, 'admin', 19, 'ouiii', '', NULL, NULL, NULL, '2026-02-18 07:28:36', 1, 0),
(520, 271, 'enseignant', 17, 'quoiioi?', '', NULL, NULL, NULL, '2026-02-18 07:29:13', 1, 0),
(521, 271, 'enseignant', 17, '[Image envoyée]', '', 'uploads/chat_files/1771399764_69956a543807c.jpg', '1753117403_5.jpg', 39833, '2026-02-18 07:29:24', 1, 0),
(522, 271, 'enseignant', 17, 'eexxx', '', NULL, NULL, NULL, '2026-02-18 07:32:33', 1, 0),
(523, 276, 'enseignant', 17, 'okay', '', NULL, NULL, NULL, '2026-02-18 07:33:03', 1, 1),
(524, 275, 'admin', 14, 'Salut tojo', '', NULL, NULL, NULL, '2026-02-20 05:29:33', 1, 1),
(525, 275, 'admin', 14, 'T\'es disponible aujourd\'hui?', '', NULL, NULL, NULL, '2026-02-20 05:29:52', 1, 1),
(526, 275, 'admin', 14, 'oui je suis', '', NULL, NULL, NULL, '2026-02-20 05:30:06', 1, 1),
(527, 275, 'admin', 14, 'pourquoi?', '', NULL, NULL, NULL, '2026-02-20 05:30:10', 1, 1),
(528, 275, 'admin', 16, 'non c\'est un truc important', '', NULL, NULL, NULL, '2026-02-20 05:30:43', 1, 1),
(529, 275, 'admin', 16, '[Image: COREE.png]', '', 'uploads/chat_files/1771565470_6997f19ebea9e.png', 'COREE.png', 570350, '2026-02-20 05:31:10', 1, 1),
(530, 271, 'admin', 16, 'yess', '', NULL, NULL, NULL, '2026-02-20 05:32:25', 1, 0),
(531, 276, 'admin', 19, 'A la prochaine fois mon vieux', '', NULL, NULL, NULL, '2026-02-26 13:15:48', 1, 1),
(532, 276, 'admin', 19, 'En esperant de te voir à merveille', '', NULL, NULL, NULL, '2026-02-26 13:16:00', 1, 1),
(533, 276, 'admin', 19, 'prend soin de toi', '', NULL, NULL, NULL, '2026-02-26 13:16:07', 1, 1),
(534, 276, 'admin', 19, '[Image: geralt-ai-generated-9811472_1280.jpg]', '', 'uploads/chat_files/1772111776_69a047a082b7c.jpg', 'geralt-ai-generated-9811472_1280.jpg', 18177, '2026-02-26 13:16:16', 1, 1),
(535, 276, 'enseignant', 17, 'Merci pour otre retour, ça me fait énormement plaisir de partager cette idée avec vous,', '', NULL, NULL, NULL, '2026-02-26 13:19:02', 1, 1),
(536, 276, 'admin', 19, 'je vous en prie, a la prochaine fois', '', NULL, NULL, NULL, '2026-02-26 13:19:34', 1, 1),
(537, 276, 'enseignant', 17, '[Image: er.png]', '', 'uploads/chat_files/1772112000_69a04880a144a.png', 'er.png', 317450, '2026-02-26 13:20:00', 1, 1),
(538, 276, 'admin', 19, '[Image: ertt.png]', '', 'uploads/chat_files/1772112025_69a04899d77b3.png', 'ertt.png', 270138, '2026-02-26 13:20:25', 1, 1),
(539, 279, 'admin', 14, 'kioooo', '', NULL, NULL, NULL, '2026-02-26 17:38:38', 0, 0),
(540, 281, 'admin', 19, 'salut', '', NULL, NULL, NULL, '2026-02-27 21:23:21', 1, 1),
(541, 281, 'admin', 14, 'salut', '', NULL, NULL, NULL, '2026-02-27 21:23:36', 0, 0),
(542, 281, 'admin', 19, 'oui salut a belle', '', NULL, NULL, NULL, '2026-02-27 21:23:46', 1, 1),
(543, 275, 'admin', 16, 'SALUT MON VIEUX', '', NULL, NULL, NULL, '2026-02-28 12:33:01', 1, 1),
(544, 275, 'admin', 14, 'Salut ça va?', '', NULL, NULL, NULL, '2026-02-28 12:33:18', 1, 1),
(545, 275, 'admin', 14, '[File: La meilleure manière de faire de l.docx]', '', 'uploads/chat_files/1772282014_69a2e09ed75ce.docx', 'La meilleure manière de faire de l.docx', 15136, '2026-02-28 12:33:34', 1, 1),
(546, 275, 'admin', 14, 'ouiii', '', 'uploads/chat_files/1772282026_69a2e0aa758f2.docx', 'La meilleure manière de faire de l.docx', 15136, '2026-02-28 12:33:46', 1, 1),
(547, 256, 'admin', 14, 'salut', '', NULL, NULL, NULL, '2026-03-08 18:14:06', 0, 0),
(548, 258, 'admin', 16, 'Salut stessy', '', NULL, NULL, NULL, '2026-04-20 16:54:47', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `message_reactions`
--

DROP TABLE IF EXISTS `message_reactions`;
CREATE TABLE IF NOT EXISTS `message_reactions` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `message_id` bigint UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `user_type` enum('admin','professeur','staff','etudiant') COLLATE utf8mb4_unicode_ci NOT NULL,
  `emoji` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_reaction` (`message_id`,`user_id`,`user_type`,`emoji`),
  KEY `idx_message` (`message_id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `message_reactions`
--

INSERT INTO `message_reactions` (`id`, `message_id`, `user_id`, `user_type`, `emoji`, `created_at`) VALUES
(1, 474, 19, 'admin', '❤️', '2026-02-18 10:03:21'),
(2, 474, 19, 'admin', '👍', '2026-02-18 10:04:20'),
(3, 508, 19, 'admin', '👍', '2026-02-18 10:20:18'),
(4, 516, 17, '', '❤️', '2026-02-18 10:23:39'),
(5, 516, 19, 'admin', '❤️', '2026-02-18 10:23:52'),
(7, 529, 14, 'admin', '❤️', '2026-02-20 08:31:28'),
(8, 349, 14, 'admin', '❤️', '2026-02-25 17:38:40'),
(9, 473, 19, 'admin', '❤️', '2026-02-26 16:15:18'),
(10, 534, 19, 'admin', '😮', '2026-02-26 16:16:27'),
(11, 534, 17, '', '😮', '2026-02-26 16:18:25'),
(12, 534, 17, '', '❤️', '2026-02-26 16:18:27'),
(13, 511, 19, 'admin', '❤️', '2026-02-26 16:37:19'),
(14, 542, 14, 'admin', '❤️', '2026-02-28 00:25:08'),
(15, 546, 16, 'admin', '❤️', '2026-04-20 19:54:18');

-- --------------------------------------------------------

--
-- Table structure for table `mpiasa`
--

DROP TABLE IF EXISTS `mpiasa`;
CREATE TABLE IF NOT EXISTS `mpiasa` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `prenom` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `type_personne` enum('professeur','staff') COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `telephone` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `annee_scolaire` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `date_inscription` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mpiasa`
--

INSERT INTO `mpiasa` (`id`, `nom`, `prenom`, `type_personne`, `email`, `telephone`, `annee_scolaire`, `date_inscription`) VALUES
(4, 'RANDRIAMBOLANIAINA', 'Fitahiantsoa Fitia', 'professeur', 'fitia@gmail.com', '0342565825', '2025-2026', '2025-11-01 07:26:52'),
(5, 'RANDRIAMBOLANIAINA', 'Avotra Fenosoa', 'professeur', 'fia@gmail.com', '0342565821', '2025-2026', '2025-11-01 07:27:39'),
(6, 'RANDRIAMBOLANIAINA', 'Avotra Fenosoa', 'staff', 'fa@gmail.com', '0342565820', '2025-2026', '2025-11-01 07:45:12'),
(7, 'ANDRIAMBOLANIAINA', 'Fenosoa', 'staff', 'fiane@gmail.com', '0342565821', '2025-2026', '2025-11-01 07:46:25');

-- --------------------------------------------------------

--
-- Table structure for table `notes`
--

DROP TABLE IF EXISTS `notes`;
CREATE TABLE IF NOT EXISTS `notes` (
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
  KEY `id_matiere` (`id_matiere`)
) ENGINE=InnoDB AUTO_INCREMENT=6732 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notes`
--

INSERT INTO `notes` (`id`, `id_eleve`, `id_matiere`, `note`, `trimestre`, `coefficient`, `remarque`, `annee_scolaire`, `periode`, `type_note`) VALUES
(6501, 286, 20, 15, NULL, 1, NULL, '2025-2026', 'T1', 'regular'),
(6502, 286, 16, 16, NULL, 1, NULL, '2025-2026', 'T1', 'regular'),
(6503, 286, 17, 12, NULL, 1, NULL, '2025-2026', 'T1', 'regular'),
(6504, 286, 3, 14, NULL, 1, NULL, '2025-2026', 'T1', 'regular'),
(6505, 286, 2, 15, NULL, 1, NULL, '2025-2026', 'T1', 'regular'),
(6506, 286, 21, 14, NULL, 1, NULL, '2025-2026', 'T1', 'regular'),
(6507, 302, 16, 12, NULL, 1, NULL, '2025-2026', 'B1', 'regular'),
(6508, 302, 3, 14, NULL, 1, NULL, '2025-2026', 'B1', 'regular'),
(6509, 302, 2, 11, NULL, 1, NULL, '2025-2026', 'B1', 'regular'),
(6510, 302, 5, 10.5, NULL, 1, NULL, '2025-2026', 'B1', 'regular'),
(6511, 302, 7, 8, NULL, 1, NULL, '2025-2026', 'B1', 'regular'),
(6512, 301, 16, 15, NULL, 1, NULL, '2025-2026', 'B1', 'regular'),
(6513, 301, 3, 16, NULL, 1, NULL, '2025-2026', 'B1', 'regular'),
(6514, 301, 2, 13.5, NULL, 1, NULL, '2025-2026', 'B1', 'regular'),
(6515, 301, 5, 9.5, NULL, 1, NULL, '2025-2026', 'B1', 'regular'),
(6516, 301, 7, 14, NULL, 1, NULL, '2025-2026', 'B1', 'regular'),
(6517, 303, 16, 11, NULL, 1, NULL, '2025-2026', 'B1', 'regular'),
(6518, 303, 3, 13, NULL, 1, NULL, '2025-2026', 'B1', 'regular'),
(6519, 303, 2, 10, NULL, 1, NULL, '2025-2026', 'B1', 'regular'),
(6520, 303, 5, 5, NULL, 1, NULL, '2025-2026', 'B1', 'regular'),
(6521, 303, 7, 14, NULL, 1, NULL, '2025-2026', 'B1', 'regular'),
(6522, 297, 16, 15, NULL, 1, NULL, '2025-2026', 'B1', 'regular'),
(6523, 297, 3, 14, NULL, 1, NULL, '2025-2026', 'B1', 'regular'),
(6524, 297, 2, 11, NULL, 1, NULL, '2025-2026', 'B1', 'regular'),
(6525, 297, 5, 13, NULL, 1, NULL, '2025-2026', 'B1', 'regular'),
(6526, 297, 7, 11, NULL, 1, NULL, '2025-2026', 'B1', 'regular'),
(6527, 299, 16, 11, NULL, 1, NULL, '2025-2026', 'B1', 'regular'),
(6528, 299, 3, 14, NULL, 1, NULL, '2025-2026', 'B1', 'regular'),
(6529, 299, 2, 12, NULL, 1, NULL, '2025-2026', 'B1', 'regular'),
(6530, 299, 5, 11, NULL, 1, NULL, '2025-2026', 'B1', 'regular'),
(6531, 299, 7, 12, NULL, 1, NULL, '2025-2026', 'B1', 'regular'),
(6532, 300, 16, 10.5, NULL, 1, NULL, '2025-2026', 'B1', 'regular'),
(6533, 300, 3, 17, NULL, 1, NULL, '2025-2026', 'B1', 'regular'),
(6534, 300, 2, 14, NULL, 1, NULL, '2025-2026', 'B1', 'regular'),
(6535, 300, 5, 16, NULL, 1, NULL, '2025-2026', 'B1', 'regular'),
(6536, 300, 7, 18, NULL, 1, NULL, '2025-2026', 'B1', 'regular'),
(6537, 298, 16, 19, NULL, 1, NULL, '2025-2026', 'B1', 'regular'),
(6538, 298, 3, 19, NULL, 1, NULL, '2025-2026', 'B1', 'regular'),
(6539, 298, 2, 18, NULL, 1, NULL, '2025-2026', 'B1', 'regular'),
(6540, 298, 5, 19, NULL, 1, NULL, '2025-2026', 'B1', 'regular'),
(6541, 298, 7, 18, NULL, 1, NULL, '2025-2026', 'B1', 'regular'),
(6542, 296, 16, 12.5, NULL, 1, NULL, '2025-2026', 'B1', 'regular'),
(6543, 296, 3, 13, NULL, 1, NULL, '2025-2026', 'B1', 'regular'),
(6544, 296, 2, 14, NULL, 1, NULL, '2025-2026', 'B1', 'regular'),
(6545, 296, 5, 11, NULL, 1, NULL, '2025-2026', 'B1', 'regular'),
(6546, 296, 7, 12, NULL, 1, NULL, '2025-2026', 'B1', 'regular'),
(6547, 295, 16, 18, NULL, 1, NULL, '2025-2026', 'B1', 'regular'),
(6548, 295, 3, 19, NULL, 1, NULL, '2025-2026', 'B1', 'regular'),
(6549, 295, 2, 18, NULL, 1, NULL, '2025-2026', 'B1', 'regular'),
(6550, 295, 5, 19, NULL, 1, NULL, '2025-2026', 'B1', 'regular'),
(6551, 295, 7, 18, NULL, 1, NULL, '2025-2026', 'B1', 'regular'),
(6552, 302, 16, 10, NULL, 1, NULL, '2025-2026', 'B2', 'regular'),
(6553, 302, 3, 15, NULL, 1, NULL, '2025-2026', 'B2', 'regular'),
(6554, 302, 2, 14.5, NULL, 1, NULL, '2025-2026', 'B2', 'regular'),
(6555, 302, 5, 16, NULL, 1, NULL, '2025-2026', 'B2', 'regular'),
(6556, 302, 7, 14, NULL, 1, NULL, '2025-2026', 'B2', 'regular'),
(6557, 301, 16, 12.5, NULL, 1, NULL, '2025-2026', 'B2', 'regular'),
(6558, 301, 3, 10, NULL, 1, NULL, '2025-2026', 'B2', 'regular'),
(6559, 301, 2, 9.5, NULL, 1, NULL, '2025-2026', 'B2', 'regular'),
(6560, 301, 5, 8, NULL, 1, NULL, '2025-2026', 'B2', 'regular'),
(6561, 301, 7, 11, NULL, 1, NULL, '2025-2026', 'B2', 'regular'),
(6562, 303, 16, 12.5, NULL, 1, NULL, '2025-2026', 'B2', 'regular'),
(6563, 303, 3, 14, NULL, 1, NULL, '2025-2026', 'B2', 'regular'),
(6564, 303, 2, 13, NULL, 1, NULL, '2025-2026', 'B2', 'regular'),
(6565, 303, 5, 15, NULL, 1, NULL, '2025-2026', 'B2', 'regular'),
(6566, 303, 7, 16, NULL, 1, NULL, '2025-2026', 'B2', 'regular'),
(6567, 297, 16, 12, NULL, 1, NULL, '2025-2026', 'B2', 'regular'),
(6568, 297, 3, 13.5, NULL, 1, NULL, '2025-2026', 'B2', 'regular'),
(6569, 297, 2, 14, NULL, 1, NULL, '2025-2026', 'B2', 'regular'),
(6570, 297, 5, 17, NULL, 1, NULL, '2025-2026', 'B2', 'regular'),
(6571, 297, 7, 15, NULL, 1, NULL, '2025-2026', 'B2', 'regular'),
(6572, 299, 16, 14, NULL, 1, NULL, '2025-2026', 'B2', 'regular'),
(6573, 299, 3, 15, NULL, 1, NULL, '2025-2026', 'B2', 'regular'),
(6574, 299, 2, 16, NULL, 1, NULL, '2025-2026', 'B2', 'regular'),
(6575, 299, 5, 18, NULL, 1, NULL, '2025-2026', 'B2', 'regular'),
(6576, 299, 7, 14, NULL, 1, NULL, '2025-2026', 'B2', 'regular'),
(6577, 300, 16, 17, NULL, 1, NULL, '2025-2026', 'B2', 'regular'),
(6578, 300, 3, 18, NULL, 1, NULL, '2025-2026', 'B2', 'regular'),
(6579, 300, 2, 19, NULL, 1, NULL, '2025-2026', 'B2', 'regular'),
(6580, 300, 5, 17, NULL, 1, NULL, '2025-2026', 'B2', 'regular'),
(6581, 300, 7, 15, NULL, 1, NULL, '2025-2026', 'B2', 'regular'),
(6582, 298, 16, 17, NULL, 1, NULL, '2025-2026', 'B2', 'regular'),
(6583, 298, 3, 18, NULL, 1, NULL, '2025-2026', 'B2', 'regular'),
(6584, 298, 2, 19, NULL, 1, NULL, '2025-2026', 'B2', 'regular'),
(6585, 298, 5, 18, NULL, 1, NULL, '2025-2026', 'B2', 'regular'),
(6586, 298, 7, 17, NULL, 1, NULL, '2025-2026', 'B2', 'regular'),
(6587, 296, 16, 14.5, NULL, 1, NULL, '2025-2026', 'B2', 'regular'),
(6588, 296, 3, 16, NULL, 1, NULL, '2025-2026', 'B2', 'regular'),
(6589, 296, 2, 14, NULL, 1, NULL, '2025-2026', 'B2', 'regular'),
(6590, 296, 5, 13, NULL, 1, NULL, '2025-2026', 'B2', 'regular'),
(6591, 296, 7, 11, NULL, 1, NULL, '2025-2026', 'B2', 'regular'),
(6592, 295, 16, 13, NULL, 1, NULL, '2025-2026', 'B2', 'regular'),
(6593, 295, 3, 16, NULL, 1, NULL, '2025-2026', 'B2', 'regular'),
(6594, 295, 2, 18, NULL, 1, NULL, '2025-2026', 'B2', 'regular'),
(6595, 295, 5, 17, NULL, 1, NULL, '2025-2026', 'B2', 'regular'),
(6596, 295, 7, 15, NULL, 1, NULL, '2025-2026', 'B2', 'regular'),
(6597, 302, 16, 15, NULL, 1, NULL, '2025-2026', 'T1', 'regular'),
(6598, 302, 3, 16, NULL, 1, NULL, '2025-2026', 'T1', 'regular'),
(6599, 302, 2, 12.5, NULL, 1, NULL, '2025-2026', 'T1', 'regular'),
(6600, 302, 5, 13, NULL, 1, NULL, '2025-2026', 'T1', 'regular'),
(6601, 302, 7, 11, NULL, 1, NULL, '2025-2026', 'T1', 'regular'),
(6602, 301, 16, 17, NULL, 1, NULL, '2025-2026', 'T1', 'regular'),
(6603, 301, 3, 15, NULL, 1, NULL, '2025-2026', 'T1', 'regular'),
(6604, 301, 2, 16, NULL, 1, NULL, '2025-2026', 'T1', 'regular'),
(6605, 301, 5, 14, NULL, 1, NULL, '2025-2026', 'T1', 'regular'),
(6606, 301, 7, 15, NULL, 1, NULL, '2025-2026', 'T1', 'regular'),
(6607, 303, 16, 17, NULL, 1, NULL, '2025-2026', 'T1', 'regular'),
(6608, 303, 3, 14, NULL, 1, NULL, '2025-2026', 'T1', 'regular'),
(6609, 303, 2, 13, NULL, 1, NULL, '2025-2026', 'T1', 'regular'),
(6610, 303, 5, 13.5, NULL, 1, NULL, '2025-2026', 'T1', 'regular'),
(6611, 303, 7, 14, NULL, 1, NULL, '2025-2026', 'T1', 'regular'),
(6612, 297, 16, 16, NULL, 1, NULL, '2025-2026', 'T1', 'regular'),
(6613, 297, 3, 15, NULL, 1, NULL, '2025-2026', 'T1', 'regular'),
(6614, 297, 2, 14, NULL, 1, NULL, '2025-2026', 'T1', 'regular'),
(6615, 297, 5, 13, NULL, 1, NULL, '2025-2026', 'T1', 'regular'),
(6616, 297, 7, 15, NULL, 1, NULL, '2025-2026', 'T1', 'regular'),
(6617, 299, 16, 10.5, NULL, 1, NULL, '2025-2026', 'T1', 'regular'),
(6618, 299, 3, 14, NULL, 1, NULL, '2025-2026', 'T1', 'regular'),
(6619, 299, 2, 12, NULL, 1, NULL, '2025-2026', 'T1', 'regular'),
(6620, 299, 5, 13, NULL, 1, NULL, '2025-2026', 'T1', 'regular'),
(6621, 299, 7, 11, NULL, 1, NULL, '2025-2026', 'T1', 'regular'),
(6622, 300, 16, 14, NULL, 1, NULL, '2025-2026', 'T1', 'regular'),
(6623, 300, 3, 14, NULL, 1, NULL, '2025-2026', 'T1', 'regular'),
(6624, 300, 2, 15, NULL, 1, NULL, '2025-2026', 'T1', 'regular'),
(6625, 300, 5, 16, NULL, 1, NULL, '2025-2026', 'T1', 'regular'),
(6626, 300, 7, 14, NULL, 1, NULL, '2025-2026', 'T1', 'regular'),
(6627, 298, 16, 18, NULL, 1, NULL, '2025-2026', 'T1', 'regular'),
(6628, 298, 3, 19, NULL, 1, NULL, '2025-2026', 'T1', 'regular'),
(6629, 298, 2, 19, NULL, 1, NULL, '2025-2026', 'T1', 'regular'),
(6630, 298, 5, 19, NULL, 1, NULL, '2025-2026', 'T1', 'regular'),
(6631, 298, 7, 19, NULL, 1, NULL, '2025-2026', 'T1', 'regular'),
(6632, 296, 16, 14, NULL, 1, NULL, '2025-2026', 'T1', 'regular'),
(6633, 296, 3, 11, NULL, 1, NULL, '2025-2026', 'T1', 'regular'),
(6634, 296, 2, 12, NULL, 1, NULL, '2025-2026', 'T1', 'regular'),
(6635, 296, 5, 11, NULL, 1, NULL, '2025-2026', 'T1', 'regular'),
(6636, 296, 7, 13, NULL, 1, NULL, '2025-2026', 'T1', 'regular'),
(6637, 295, 16, 10.5, NULL, 1, NULL, '2025-2026', 'T1', 'regular'),
(6638, 295, 3, 13, NULL, 1, NULL, '2025-2026', 'T1', 'regular'),
(6639, 295, 2, 12, NULL, 1, NULL, '2025-2026', 'T1', 'regular'),
(6640, 295, 5, 14, NULL, 1, NULL, '2025-2026', 'T1', 'regular'),
(6641, 295, 7, 12.5, NULL, 1, NULL, '2025-2026', 'T1', 'regular'),
(6642, 302, 16, 13, NULL, 1, NULL, '2025-2026', 'T2', 'regular'),
(6643, 302, 3, 15, NULL, 1, NULL, '2025-2026', 'T2', 'regular'),
(6644, 302, 2, 14.5, NULL, 1, NULL, '2025-2026', 'T2', 'regular'),
(6645, 302, 5, 16, NULL, 1, NULL, '2025-2026', 'T2', 'regular'),
(6646, 302, 7, 14, NULL, 1, NULL, '2025-2026', 'T2', 'regular'),
(6647, 301, 16, 13.5, NULL, 1, NULL, '2025-2026', 'T2', 'regular'),
(6648, 301, 3, 12, NULL, 1, NULL, '2025-2026', 'T2', 'regular'),
(6649, 301, 2, 10.5, NULL, 1, NULL, '2025-2026', 'T2', 'regular'),
(6650, 301, 5, 14, NULL, 1, NULL, '2025-2026', 'T2', 'regular'),
(6651, 301, 7, 15, NULL, 1, NULL, '2025-2026', 'T2', 'regular'),
(6652, 303, 16, 14.5, NULL, 1, NULL, '2025-2026', 'T2', 'regular'),
(6653, 303, 3, 11, NULL, 1, NULL, '2025-2026', 'T2', 'regular'),
(6654, 303, 2, 10.5, NULL, 1, NULL, '2025-2026', 'T2', 'regular'),
(6655, 303, 5, 11, NULL, 1, NULL, '2025-2026', 'T2', 'regular'),
(6656, 303, 7, 11, NULL, 1, NULL, '2025-2026', 'T2', 'regular'),
(6657, 297, 16, 10.5, NULL, 1, NULL, '2025-2026', 'T2', 'regular'),
(6658, 297, 3, 9, NULL, 1, NULL, '2025-2026', 'T2', 'regular'),
(6659, 297, 2, 14, NULL, 1, NULL, '2025-2026', 'T2', 'regular'),
(6660, 297, 5, 17, NULL, 1, NULL, '2025-2026', 'T2', 'regular'),
(6661, 297, 7, 15, NULL, 1, NULL, '2025-2026', 'T2', 'regular'),
(6662, 299, 16, 13.5, NULL, 1, NULL, '2025-2026', 'T2', 'regular'),
(6663, 299, 3, 11, NULL, 1, NULL, '2025-2026', 'T2', 'regular'),
(6664, 299, 2, 13, NULL, 1, NULL, '2025-2026', 'T2', 'regular'),
(6665, 299, 5, 9.5, NULL, 1, NULL, '2025-2026', 'T2', 'regular'),
(6666, 299, 7, 17, NULL, 1, NULL, '2025-2026', 'T2', 'regular'),
(6667, 300, 16, 17.5, NULL, 1, NULL, '2025-2026', 'T2', 'regular'),
(6668, 300, 3, 16.5, NULL, 1, NULL, '2025-2026', 'T2', 'regular'),
(6669, 300, 2, 14.5, NULL, 1, NULL, '2025-2026', 'T2', 'regular'),
(6670, 300, 5, 15.5, NULL, 1, NULL, '2025-2026', 'T2', 'regular'),
(6671, 300, 7, 13, NULL, 1, NULL, '2025-2026', 'T2', 'regular'),
(6672, 298, 16, 13.5, NULL, 1, NULL, '2025-2026', 'T2', 'regular'),
(6673, 298, 3, 18, NULL, 1, NULL, '2025-2026', 'T2', 'regular'),
(6674, 298, 2, 17, NULL, 1, NULL, '2025-2026', 'T2', 'regular'),
(6675, 298, 5, 16, NULL, 1, NULL, '2025-2026', 'T2', 'regular'),
(6676, 298, 7, 14, NULL, 1, NULL, '2025-2026', 'T2', 'regular'),
(6677, 296, 16, 15, NULL, 1, NULL, '2025-2026', 'T2', 'regular'),
(6678, 296, 3, 15, NULL, 1, NULL, '2025-2026', 'T2', 'regular'),
(6679, 296, 2, 15, NULL, 1, NULL, '2025-2026', 'T2', 'regular'),
(6680, 296, 5, 14, NULL, 1, NULL, '2025-2026', 'T2', 'regular'),
(6681, 296, 7, 12.5, NULL, 1, NULL, '2025-2026', 'T2', 'regular'),
(6682, 295, 16, 17, NULL, 1, NULL, '2025-2026', 'T2', 'regular'),
(6683, 295, 3, 15, NULL, 1, NULL, '2025-2026', 'T2', 'regular'),
(6684, 295, 2, 12.5, NULL, 1, NULL, '2025-2026', 'T2', 'regular'),
(6685, 295, 5, 14, NULL, 1, NULL, '2025-2026', 'T2', 'regular'),
(6686, 295, 7, 12.5, NULL, 1, NULL, '2025-2026', 'T2', 'regular'),
(6687, 302, 16, 13.5, NULL, 1, NULL, '2025-2026', 'T3', 'regular'),
(6688, 302, 3, 16, NULL, 1, NULL, '2025-2026', 'T3', 'regular'),
(6689, 302, 2, 17, NULL, 1, NULL, '2025-2026', 'T3', 'regular'),
(6690, 302, 5, 15.5, NULL, 1, NULL, '2025-2026', 'T3', 'regular'),
(6691, 302, 7, 10.5, NULL, 1, NULL, '2025-2026', 'T3', 'regular'),
(6692, 301, 16, 11.5, NULL, 1, NULL, '2025-2026', 'T3', 'regular'),
(6693, 301, 3, 14.5, NULL, 1, NULL, '2025-2026', 'T3', 'regular'),
(6694, 301, 2, 13.5, NULL, 1, NULL, '2025-2026', 'T3', 'regular'),
(6695, 301, 5, 14, NULL, 1, NULL, '2025-2026', 'T3', 'regular'),
(6696, 301, 7, 15, NULL, 1, NULL, '2025-2026', 'T3', 'regular'),
(6697, 303, 16, 13.4, NULL, 1, NULL, '2025-2026', 'T3', 'regular'),
(6698, 303, 3, 17, NULL, 1, NULL, '2025-2026', 'T3', 'regular'),
(6699, 303, 2, 12, NULL, 1, NULL, '2025-2026', 'T3', 'regular'),
(6700, 303, 5, 14, NULL, 1, NULL, '2025-2026', 'T3', 'regular'),
(6701, 303, 7, 15, NULL, 1, NULL, '2025-2026', 'T3', 'regular'),
(6702, 297, 16, 17, NULL, 1, NULL, '2025-2026', 'T3', 'regular'),
(6703, 297, 3, 18, NULL, 1, NULL, '2025-2026', 'T3', 'regular'),
(6704, 297, 2, 19, NULL, 1, NULL, '2025-2026', 'T3', 'regular'),
(6705, 297, 5, 12.5, NULL, 1, NULL, '2025-2026', 'T3', 'regular'),
(6706, 297, 7, 14, NULL, 1, NULL, '2025-2026', 'T3', 'regular'),
(6707, 299, 16, 9.5, NULL, 1, NULL, '2025-2026', 'T3', 'regular'),
(6708, 299, 3, 11.5, NULL, 1, NULL, '2025-2026', 'T3', 'regular'),
(6709, 299, 2, 11, NULL, 1, NULL, '2025-2026', 'T3', 'regular'),
(6710, 299, 5, 10.5, NULL, 1, NULL, '2025-2026', 'T3', 'regular'),
(6711, 299, 7, 13, NULL, 1, NULL, '2025-2026', 'T3', 'regular'),
(6712, 300, 16, 14.5, NULL, 1, NULL, '2025-2026', 'T3', 'regular'),
(6713, 300, 3, 16, NULL, 1, NULL, '2025-2026', 'T3', 'regular'),
(6714, 300, 2, 14, NULL, 1, NULL, '2025-2026', 'T3', 'regular'),
(6715, 300, 5, 16, NULL, 1, NULL, '2025-2026', 'T3', 'regular'),
(6716, 300, 7, 15, NULL, 1, NULL, '2025-2026', 'T3', 'regular'),
(6717, 298, 16, 14.5, NULL, 1, NULL, '2025-2026', 'T3', 'regular'),
(6718, 298, 3, 13.5, NULL, 1, NULL, '2025-2026', 'T3', 'regular'),
(6719, 298, 2, 14, NULL, 1, NULL, '2025-2026', 'T3', 'regular'),
(6720, 298, 5, 14.5, NULL, 1, NULL, '2025-2026', 'T3', 'regular'),
(6721, 298, 7, 12.5, NULL, 1, NULL, '2025-2026', 'T3', 'regular'),
(6722, 296, 16, 11, NULL, 1, NULL, '2025-2026', 'T3', 'regular'),
(6723, 296, 3, 14, NULL, 1, NULL, '2025-2026', 'T3', 'regular'),
(6724, 296, 2, 15, NULL, 1, NULL, '2025-2026', 'T3', 'regular'),
(6725, 296, 5, 13.5, NULL, 1, NULL, '2025-2026', 'T3', 'regular'),
(6726, 296, 7, 14, NULL, 1, NULL, '2025-2026', 'T3', 'regular'),
(6727, 295, 16, 16, NULL, 1, NULL, '2025-2026', 'T3', 'regular'),
(6728, 295, 3, 15, NULL, 1, NULL, '2025-2026', 'T3', 'regular'),
(6729, 295, 2, 14, NULL, 1, NULL, '2025-2026', 'T3', 'regular'),
(6730, 295, 5, 12.5, NULL, 1, NULL, '2025-2026', 'T3', 'regular'),
(6731, 295, 7, 13, NULL, 1, NULL, '2025-2026', 'T3', 'regular');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
CREATE TABLE IF NOT EXISTS `notifications` (
  `id` int NOT NULL AUTO_INCREMENT,
  `type` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `message` text COLLATE utf8mb4_general_ci NOT NULL,
  `destinataire_id` int DEFAULT NULL,
  `date_creation` timestamp NULL DEFAULT NULL,
  `statut` enum('non lu','lu') COLLATE utf8mb4_general_ci DEFAULT 'non lu',
  `date_envoi` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `lu` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=224 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `type`, `message`, `destinataire_id`, `date_creation`, `statut`, `date_envoi`, `lu`) VALUES
(159, 'Paiement', '💵 Paiement salaire enregistré pour RANDRIAMIFALY Tojo Nambinina.', NULL, '2026-02-07 16:29:41', '', '2026-02-07 16:29:41', 1),
(160, 'Paiement', '💰 Paiement écolage enregistré pour ANIO Fyh.', NULL, '2026-02-07 16:46:38', '', '2026-02-07 16:46:38', 1),
(163, 'edt', 'Emploi du temps mis à jour pour la classe 1ère', NULL, '2026-02-17 14:34:20', '', '2026-02-17 14:34:20', 1),
(169, 'Paiement', '💰 Paiement écolage enregistré pour ANIO Fery.', NULL, '2026-02-18 02:43:06', '', '2026-02-18 02:43:06', 1),
(170, 'Paiement', '💰 Paiement écolage enregistré pour ANIO Fanih.', NULL, '2026-02-18 04:01:09', '', '2026-02-18 04:01:09', 1),
(171, 'presence', 'Checking de présence d\'un enseignant effectué le 2026-02-18 ', NULL, '2026-02-18 13:21:57', '', '2026-02-18 13:21:57', 1),
(172, 'presence', 'Checking de présence d\'un enseignant effectué le 2026-02-18 ', NULL, '2026-02-18 13:23:33', '', '2026-02-18 13:23:33', 1),
(173, 'presence', 'Checking de présence d\'un enseignant effectué le 2026-02-18 ', NULL, '2026-02-18 13:24:41', '', '2026-02-18 13:24:41', 1),
(174, 'presence', 'Checking de présence d\'un enseignant effectué le 2026-02-18 ', NULL, '2026-02-18 13:26:04', '', '2026-02-18 13:26:04', 1),
(175, 'Paiement', '💰 Paiement écolage enregistré pour ANIO Faniahy.', NULL, '2026-02-18 13:27:19', '', '2026-02-18 13:27:19', 1),
(176, 'Paiement', '💰 Paiement écolage enregistré pour ANIO Faniho.', NULL, '2026-02-18 13:27:33', '', '2026-02-18 13:27:33', 1),
(177, 'edt', 'Emploi du temps mis à jour pour la classe 1ère', NULL, '2026-02-20 04:05:25', '', '2026-02-20 04:05:25', 1),
(178, 'dossier', 'Nouveau dossier déposé : Anio HENRY (eleve)', NULL, '2026-02-26 16:28:40', '', '2026-02-26 16:28:40', 1),
(179, 'dossier', 'Nouveau dossier déposé : Anio HENRY (eleve)', NULL, '2026-02-26 16:29:22', '', '2026-02-26 16:29:22', 1),
(180, 'dossier', 'Nouveau dossier déposé : Anio HENRY (eleve)', NULL, '2026-02-26 16:30:52', '', '2026-02-26 16:30:52', 1),
(181, 'dossier', 'Nouveau dossier déposé : Mathieu (enseignant)', NULL, '2026-02-26 16:41:21', '', '2026-02-26 16:41:21', 1),
(182, 'dossier', 'Nouveau dossier déposé : Fitia (eleve)', NULL, '2026-02-26 16:43:04', '', '2026-02-26 16:43:04', 1),
(183, 'presence', 'Checking de présence d\'un employé effectué le 2026-02-27 ', NULL, '2026-02-27 10:49:14', '', '2026-02-27 10:49:14', 1),
(184, 'sauvegarde', 'Nouvelle sauvegarde : backup_20260227_111142.sql', NULL, '2026-02-27 11:11:43', '', '2026-02-27 11:11:43', 1),
(185, 'edt', 'Emploi du temps mis à jour pour la classe 1ère', NULL, '2026-02-27 12:26:30', '', '2026-02-27 12:26:30', 1),
(186, 'dossier', 'Nouveau dossier déposé : Faly ANDRIA (eleve)', NULL, '2026-02-27 15:15:33', '', '2026-02-27 15:15:33', 1),
(187, 'salle', 'Nouvelle salle ajoutée : Salle de Réunion', NULL, '2026-02-27 15:30:26', '', '2026-02-27 15:30:26', 1),
(188, 'edt', 'Emploi du temps mis à jour pour la classe 1ère', NULL, '2026-02-27 16:31:08', '', '2026-02-27 16:31:08', 1),
(189, 'presence', 'Checking de présence d\'un employé effectué le 2026-02-23 ', NULL, '2026-02-27 18:24:24', '', '2026-02-27 18:24:24', 1),
(190, 'presence', 'Checking de présence d\'un employé effectué le 2026-02-24 ', NULL, '2026-02-27 18:25:16', '', '2026-02-27 18:25:16', 1),
(191, 'presence', 'Checking de présence d\'un employé effectué le 2026-02-25 ', NULL, '2026-02-27 18:25:28', '', '2026-02-27 18:25:28', 1),
(192, 'presence', 'Checking de présence d\'un employé effectué le 2026-02-26 ', NULL, '2026-02-27 18:25:47', '', '2026-02-27 18:25:47', 1),
(193, 'presence', 'Checking de présence d\'un employé effectué le 2026-02-27 ', NULL, '2026-02-27 18:25:57', '', '2026-02-27 18:25:57', 1),
(194, 'reservation', 'Nouvelle réservation ajoutée dans la salle : Salle de Réunion le 2026-03-03 (23:21 - 13:21)', NULL, '2026-02-27 19:21:39', 'non lu', '2026-02-27 19:21:39', 1),
(195, 'edt', 'Emploi du temps mis à jour pour la classe 1ère', NULL, '2026-02-27 20:04:00', '', '2026-02-27 20:04:00', 1),
(196, 'edt', 'Emploi du temps mis à jour pour la classe 1ère', NULL, '2026-02-27 20:04:05', '', '2026-02-27 20:04:05', 1),
(197, 'edt', 'Emploi du temps mis à jour pour la classe 1ère', NULL, '2026-02-27 20:04:17', '', '2026-02-27 20:04:17', 1),
(198, 'edt', 'Emploi du temps mis à jour pour la classe 1ère', NULL, '2026-02-27 20:04:27', '', '2026-02-27 20:04:27', 1),
(199, 'edt', 'Emploi du temps mis à jour pour la classe 1ère', NULL, '2026-02-27 20:04:47', '', '2026-02-27 20:04:47', 1),
(200, 'edt', 'Emploi du temps mis à jour pour la classe 1ère', NULL, '2026-02-27 20:08:08', '', '2026-02-27 20:08:08', 1),
(201, 'edt', 'Emploi du temps mis à jour pour la classe 1ère', NULL, '2026-02-27 20:08:14', '', '2026-02-27 20:08:14', 1),
(202, 'edt', 'Emploi du temps mis à jour pour la classe 1ère', NULL, '2026-02-27 20:08:20', '', '2026-02-27 20:08:20', 1),
(203, 'edt', 'Emploi du temps mis à jour pour la classe 1ère', NULL, '2026-02-27 20:12:29', '', '2026-02-27 20:12:29', 1),
(204, 'evenement', 'Nouvel événement : Sortie récréative du 2026-03-27T23:14 au 2026-03-31T23:15', NULL, '2026-02-27 20:15:12', '', '2026-02-27 20:15:12', 1),
(205, 'evenement', 'Nouvel événement : Sortie récréative du 2026-03-29T09:00 au 2026-03-29T10:00', NULL, '2026-02-27 20:15:30', '', '2026-02-27 20:15:30', 1),
(206, 'evenement', 'Nouvel événement : Réunion du 2026-03-05T23:17 au 2026-03-05T13:19', NULL, '2026-02-27 20:18:05', '', '2026-02-27 20:18:05', 1),
(207, 'info', 'Réunon imprtant now', NULL, '2026-02-27 20:20:02', '', '2026-02-27 20:20:02', 1),
(208, 'dossier', 'Nouveau dossier déposé : Koto Nandra (eleve)', NULL, '2026-02-27 20:23:14', '', '2026-02-27 20:23:14', 1),
(209, 'presence', 'Checking de présence d\'un enseignant effectué le 2026-02-27 ', NULL, '2026-02-27 20:31:45', '', '2026-02-27 20:31:45', 1),
(210, 'presence', 'Checking de présence d\'un employé effectué le 2026-02-27 ', NULL, '2026-02-27 20:34:02', '', '2026-02-27 20:34:02', 1),
(211, 'Paiement', '💰 Paiement écolage enregistré pour ANIO Tody.', NULL, '2026-02-27 20:50:33', '', '2026-02-27 20:50:33', 1),
(212, 'Paiement', '💰 Paiement écolage enregistré pour ANIO Fano.', NULL, '2026-02-27 20:50:48', '', '2026-02-27 20:50:48', 1),
(213, 'Paiement', '💵 Paiement salaire enregistré pour PERSEVERANCE Pain.', NULL, '2026-02-27 20:51:10', '', '2026-02-27 20:51:10', 1),
(214, 'Paiement', '💰 Paiement écolage enregistré pour ANIO Fyh.', NULL, '2026-02-27 20:53:59', '', '2026-02-27 20:53:59', 1),
(215, 'edt', 'Emploi du temps mis à jour pour la classe 1ère', NULL, '2026-02-28 05:06:51', '', '2026-02-28 05:06:51', 1),
(216, 'presence', 'Checking de présence d\'un enseignant effectué le 2026-02-28 ', NULL, '2026-02-28 05:14:57', '', '2026-02-28 05:14:57', 1),
(217, 'Paiement', '💵 Paiement salaire enregistré pour RANDRIAMIFALY Heriniaina.', NULL, '2026-02-28 05:19:20', '', '2026-02-28 05:19:20', 1),
(218, 'sauvegarde', 'Nouvelle sauvegarde : backup_20260228_052905.sql', NULL, '2026-02-28 05:29:05', '', '2026-02-28 05:29:05', 1),
(219, 'sauvegarde', 'Nouvelle sauvegarde : backup_20260228_103722.sql', NULL, '2026-02-28 10:37:22', '', '2026-02-28 10:37:22', 1),
(222, 'Paiement', '💰 Paiement écolage enregistré pour ANIO Fyh.', NULL, '2026-04-20 16:55:52', '', '2026-04-20 16:55:52', 0),
(223, 'sauvegarde', 'Nouvelle sauvegarde : backup_20260420_165903.sql', NULL, '2026-04-20 16:59:04', '', '2026-04-20 16:59:04', 0);

-- --------------------------------------------------------

--
-- Table structure for table `paiements`
--

DROP TABLE IF EXISTS `paiements`;
CREATE TABLE IF NOT EXISTS `paiements` (
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

-- --------------------------------------------------------

--
-- Table structure for table `paiements_assignes`
--

DROP TABLE IF EXISTS `paiements_assignes`;
CREATE TABLE IF NOT EXISTS `paiements_assignes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `type_id` int NOT NULL,
  `eleve_id` int NOT NULL,
  `statut` enum('non_paye','paye') COLLATE utf8mb4_general_ci DEFAULT 'non_paye',
  `type_personne` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `person_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `type_id` (`type_id`),
  KEY `eleve_id` (`eleve_id`)
) ENGINE=InnoDB AUTO_INCREMENT=280 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `paiements_assignes`
--

INSERT INTO `paiements_assignes` (`id`, `type_id`, `eleve_id`, `statut`, `type_personne`, `person_id`) VALUES
(247, 25, 295, 'paye', NULL, NULL),
(248, 25, 296, 'paye', NULL, NULL),
(249, 25, 297, 'paye', NULL, NULL),
(250, 25, 298, 'paye', NULL, NULL),
(251, 25, 299, 'paye', NULL, NULL),
(252, 25, 300, 'paye', NULL, NULL),
(253, 25, 301, 'paye', NULL, NULL),
(254, 25, 302, 'paye', NULL, NULL),
(255, 25, 303, 'paye', NULL, NULL),
(256, 26, 305, 'paye', NULL, NULL),
(257, 26, 306, 'paye', NULL, NULL),
(258, 26, 307, 'non_paye', NULL, NULL),
(259, 26, 308, 'non_paye', NULL, NULL),
(260, 26, 309, 'non_paye', NULL, NULL),
(261, 26, 310, 'non_paye', NULL, NULL),
(262, 26, 311, 'non_paye', NULL, NULL),
(263, 26, 312, 'non_paye', NULL, NULL),
(264, 26, 313, 'non_paye', NULL, NULL),
(271, 27, 295, 'paye', NULL, NULL),
(272, 27, 296, 'paye', NULL, NULL),
(273, 27, 297, 'paye', NULL, NULL),
(274, 27, 298, 'non_paye', NULL, NULL),
(275, 27, 299, 'non_paye', NULL, NULL),
(276, 27, 300, 'non_paye', NULL, NULL),
(277, 27, 301, 'non_paye', NULL, NULL),
(278, 27, 302, 'non_paye', NULL, NULL),
(279, 27, 303, 'non_paye', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `parametres`
--

DROP TABLE IF EXISTS `parametres`;
CREATE TABLE IF NOT EXISTS `parametres` (
  `id` int NOT NULL AUTO_INCREMENT,
  `cle` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `valeur` text COLLATE utf8mb4_general_ci,
  PRIMARY KEY (`id`),
  UNIQUE KEY `cle` (`cle`)
) ENGINE=InnoDB AUTO_INCREMENT=212 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `parametres`
--

INSERT INTO `parametres` (`id`, `cle`, `valeur`) VALUES
(1, 'nom_ecole', 'Lycée Privée Novaskol'),
(2, 'adresse_ecole', 'LOT II E 69 BIS Tsarahonenana'),
(3, 'telephone_ecole', '+261 38 77 299 58  /  +261 33 91 307 61'),
(4, 'email_ecole', 'novaskol393@gmail.com'),
(5, 'logo_ecole', 'images/logo_68962d67b7770.png'),
(6, 'annee_scolaire', '2025-2026'),
(7, 'date_debut', '2025-08-01'),
(8, 'date_fin', '2026-08-30'),
(9, 'mention_passable', '10'),
(10, 'mention_assez_bien', '12'),
(11, 'mention_bien', '14'),
(12, 'mention_tres_bien', '16'),
(13, 'notifications_mail', '1'),
(14, 'code_ecole', ''),
(93, 'dren', 'ANALAMANGA'),
(94, 'cisco', 'TANA VILLE'),
(95, 'zap', 'V'),
(96, 'code_etablissement', '101 010 035'),
(97, 'tel_etablissement', '038 77 299 58'),
(98, 'mail_etablissement', 'novaskol@gmail.com'),
(99, 'nb_comment', 'FENOMOY IZAY TSY FENO AMIN\'NY TARATASY AFAFAHY.');

-- --------------------------------------------------------

--
-- Table structure for table `parents`
--

DROP TABLE IF EXISTS `parents`;
CREATE TABLE IF NOT EXISTS `parents` (
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
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `parents`
--

INSERT INTO `parents` (`id`, `nom_pere`, `telephone_pere`, `profession_pere`, `adresse_pere`, `nom_mere`, `telephone_mere`, `profession_mere`, `adresse_mere`, `telephone`, `annee_scolaire`, `created_at`) VALUES
(2, 'HERY rrna', 'N/A', 'N/A', 'N/A', 'Ando RAVOJA', 'N/A', 'N/A', 'N/A', '1478523706', '2025-2026', '2026-02-19 05:03:18'),
(3, 'HERY naso', 'N/A', 'N/A', 'N/A', 'Ando RAVOJAS', 'N/A', 'N/A', 'N/A', '1478523698', '2025-2026', '2026-02-19 05:04:21'),
(4, 'HERY nasolort', 'N/A', 'N/A', 'N/A', 'Ando RAVOJAL', 'N/A', 'N/A', 'N/A', '1478523704', '2025-2026', '2026-02-19 05:39:39'),
(5, 'HERY nasolor', 'N/A', 'N/A', 'N/A', 'Ando RAVOJAH', 'N/A', 'N/A', 'N/A', '1478523703', '2025-2026', '2026-02-19 19:01:36'),
(6, 'Tojo_pro', '0371512214', 'Trade', 'Evyan', 'Reko ANDRY', '0345112245', 'tODEY', 'eVOYEE', '0371415214', '2025-2026', '2026-02-26 06:48:05'),
(7, 'HERY nas', 'N/A', 'N/A', 'N/A', 'Ando RAVOJAE', 'N/A', 'N/A', 'N/A', '1478523699', '2025-2026', '2026-02-28 06:11:23');

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
CREATE TABLE IF NOT EXISTS `permissions` (
  `id` int NOT NULL AUTO_INCREMENT,
  `utilisateur_id` int NOT NULL,
  `role` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `module` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `acces` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `utilisateur_id` (`utilisateur_id`)
) ENGINE=InnoDB AUTO_INCREMENT=916 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `utilisateur_id`, `role`, `module`, `acces`) VALUES
(371, 15, 'enseignant', 'Administration', 'ecriture'),
(372, 15, 'enseignant', 'dashboard', 'ecriture'),
(373, 15, 'enseignant', 'ecole', 'ecriture'),
(374, 15, 'enseignant', 'Admin', 'aucun'),
(375, 15, 'enseignant', 'inscription', 'aucun'),
(376, 15, 'enseignant', 'liste_classes', 'aucun'),
(377, 15, 'enseignant', 'matieres', 'aucun'),
(378, 15, 'enseignant', 'Enseignantss', 'aucun'),
(379, 15, 'enseignant', 'notes', 'aucun'),
(380, 15, 'enseignant', 'bulletin', 'aucun'),
(381, 15, 'enseignant', 'resultats', 'aucun'),
(382, 15, 'enseignant', 'examen_blanc', 'aucun'),
(383, 15, 'enseignant', 'resultats_examen_blanc', 'aucun'),
(384, 15, 'enseignant', 'Pédagogique', 'aucun'),
(385, 15, 'enseignant', 'emploi_temps', 'aucun'),
(386, 15, 'enseignant', 'fiche_presence', 'aucun'),
(387, 15, 'enseignant', 'calendrier', 'aucun'),
(388, 15, 'enseignant', 'notifications', 'aucun'),
(389, 15, 'enseignant', 'cartes', 'aucun'),
(390, 15, 'enseignant', 'depot_dossier', 'aucun'),
(391, 15, 'enseignant', 'fpe', 'aucun'),
(392, 15, 'enseignant', 'RH', 'aucun'),
(393, 15, 'enseignant', 'enseignants', 'aucun'),
(394, 15, 'enseignant', 'staff', 'aucun'),
(395, 15, 'enseignant', 'presence', 'aucun'),
(396, 15, 'enseignant', 'presence_staff', 'aucun'),
(397, 15, 'enseignant', 'permissions', 'aucun'),
(398, 15, 'enseignant', 'gestion_ressource', 'aucun'),
(399, 15, 'enseignant', 'Paiement', 'aucun'),
(400, 15, 'enseignant', 'detail_paiement', 'aucun'),
(401, 15, 'enseignant', 'comptable', 'aucun'),
(402, 15, 'enseignant', 'liste_paiements', 'aucun'),
(403, 15, 'enseignant', 'facture', 'aucun'),
(404, 15, 'enseignant', 'Sectrapport', 'aucun'),
(405, 15, 'enseignant', 'rapport_comptable', 'aucun'),
(406, 15, 'enseignant', 'rapport_presence', 'aucun'),
(407, 15, 'enseignant', 'rapport_staff', 'aucun'),
(408, 15, 'enseignant', 'evaluation_notes', 'aucun'),
(409, 15, 'enseignant', 'Sectrapp', 'aucun'),
(410, 15, 'enseignant', 'parametres', 'aucun'),
(411, 15, 'enseignant', 'sauvegardes', 'aucun'),
(412, 15, 'enseignant', 'Administration', 'ecriture'),
(413, 15, 'enseignant', 'dashboard', 'ecriture'),
(414, 15, 'enseignant', 'ecole', 'ecriture'),
(415, 15, 'enseignant', 'Admin', 'aucun'),
(416, 15, 'enseignant', 'inscription', 'aucun'),
(417, 15, 'enseignant', 'liste_classes', 'aucun'),
(418, 15, 'enseignant', 'matieres', 'aucun'),
(419, 15, 'enseignant', 'Enseignantss', 'aucun'),
(420, 15, 'enseignant', 'notes', 'aucun'),
(421, 15, 'enseignant', 'bulletin', 'aucun'),
(422, 15, 'enseignant', 'resultats', 'aucun'),
(423, 15, 'enseignant', 'examen_blanc', 'aucun'),
(424, 15, 'enseignant', 'resultats_examen_blanc', 'aucun'),
(425, 15, 'enseignant', 'Pédagogique', 'aucun'),
(426, 15, 'enseignant', 'emploi_temps', 'aucun'),
(427, 15, 'enseignant', 'fiche_presence', 'aucun'),
(428, 15, 'enseignant', 'calendrier', 'aucun'),
(429, 15, 'enseignant', 'notifications', 'aucun'),
(430, 15, 'enseignant', 'cartes', 'aucun'),
(431, 15, 'enseignant', 'depot_dossier', 'aucun'),
(432, 15, 'enseignant', 'fpe', 'aucun'),
(433, 15, 'enseignant', 'RH', 'aucun'),
(434, 15, 'enseignant', 'enseignants', 'aucun'),
(435, 15, 'enseignant', 'staff', 'aucun'),
(436, 15, 'enseignant', 'presence', 'aucun'),
(437, 15, 'enseignant', 'presence_staff', 'aucun'),
(438, 15, 'enseignant', 'permissions', 'aucun'),
(439, 15, 'enseignant', 'gestion_ressource', 'aucun'),
(440, 15, 'enseignant', 'Communication', 'ecriture'),
(441, 15, 'enseignant', 'communication', 'ecriture'),
(442, 15, 'enseignant', 'chat_private', 'ecriture'),
(443, 15, 'enseignant', 'chat_group', 'ecriture'),
(444, 15, 'enseignant', 'Paiement', 'aucun'),
(445, 15, 'enseignant', 'detail_paiement', 'aucun'),
(446, 15, 'enseignant', 'comptable', 'aucun'),
(447, 15, 'enseignant', 'liste_paiements', 'aucun'),
(448, 15, 'enseignant', 'facture', 'aucun'),
(449, 15, 'enseignant', 'Sectrapport', 'aucun'),
(450, 15, 'enseignant', 'rapport_comptable', 'aucun'),
(451, 15, 'enseignant', 'rapport_presence', 'aucun'),
(452, 15, 'enseignant', 'rapport_staff', 'aucun'),
(453, 15, 'enseignant', 'evaluation_notes', 'aucun'),
(454, 15, 'enseignant', 'Sectrapp', 'aucun'),
(455, 15, 'enseignant', 'parametres', 'aucun'),
(456, 15, 'enseignant', 'sauvegardes', 'aucun'),
(457, 15, 'enseignant', 'Administration', 'masquer'),
(458, 15, 'enseignant', 'dashboard', 'masquer'),
(459, 15, 'enseignant', 'ecole', 'masquer'),
(460, 15, 'enseignant', 'Admin', 'masquer'),
(461, 15, 'enseignant', 'inscription', 'masquer'),
(462, 15, 'enseignant', 'liste_classes', 'masquer'),
(463, 15, 'enseignant', 'matieres', 'masquer'),
(464, 15, 'enseignant', 'Enseignantss', 'ecriture'),
(465, 15, 'enseignant', 'notes', 'ecriture'),
(466, 15, 'enseignant', 'bulletin', 'ecriture'),
(467, 15, 'enseignant', 'resultats', 'ecriture'),
(468, 15, 'enseignant', 'examen_blanc', 'ecriture'),
(469, 15, 'enseignant', 'resultats_examen_blanc', 'ecriture'),
(470, 15, 'enseignant', 'Pédagogique', 'ecriture'),
(471, 15, 'enseignant', 'emploi_temps', 'ecriture'),
(472, 15, 'enseignant', 'fiche_presence', 'masquer'),
(473, 15, 'enseignant', 'calendrier', 'masquer'),
(474, 15, 'enseignant', 'notifications', 'masquer'),
(475, 15, 'enseignant', 'cartes', 'masquer'),
(476, 15, 'enseignant', 'depot_dossier', 'masquer'),
(477, 15, 'enseignant', 'fpe', 'masquer'),
(478, 15, 'enseignant', 'RH', 'ecriture'),
(479, 15, 'enseignant', 'enseignants', 'masquer'),
(480, 15, 'enseignant', 'staff', 'masquer'),
(481, 15, 'enseignant', 'presence', 'ecriture'),
(482, 15, 'enseignant', 'presence_staff', 'masquer'),
(483, 15, 'enseignant', 'permissions', 'masquer'),
(484, 15, 'enseignant', 'gestion_ressource', 'masquer'),
(485, 15, 'enseignant', 'Communication', 'ecriture'),
(486, 15, 'enseignant', 'communication', 'ecriture'),
(487, 15, 'enseignant', 'chat_private', 'ecriture'),
(488, 15, 'enseignant', 'chat_group', 'ecriture'),
(489, 15, 'enseignant', 'Paiement', 'masquer'),
(490, 15, 'enseignant', 'detail_paiement', 'masquer'),
(491, 15, 'enseignant', 'comptable', 'masquer'),
(492, 15, 'enseignant', 'liste_paiements', 'masquer'),
(493, 15, 'enseignant', 'facture', 'masquer'),
(494, 15, 'enseignant', 'Sectrapport', 'aucun'),
(495, 15, 'enseignant', 'rapport_comptable', 'masquer'),
(496, 15, 'enseignant', 'rapport_presence', 'masquer'),
(497, 15, 'enseignant', 'rapport_staff', 'masquer'),
(498, 15, 'enseignant', 'evaluation_notes', 'masquer'),
(499, 15, 'enseignant', 'Sectrapp', 'masquer'),
(500, 15, 'enseignant', 'parametres', 'masquer'),
(501, 15, 'enseignant', 'sauvegardes', 'masquer'),
(502, 15, 'enseignant', 'Administration', 'masquer'),
(503, 15, 'enseignant', 'dashboard', 'masquer'),
(504, 15, 'enseignant', 'ecole', 'masquer'),
(505, 15, 'enseignant', 'Admin', 'masquer'),
(506, 15, 'enseignant', 'inscription', 'masquer'),
(507, 15, 'enseignant', 'liste_classes', 'masquer'),
(508, 15, 'enseignant', 'matieres', 'masquer'),
(509, 15, 'enseignant', 'Enseignantss', 'ecriture'),
(510, 15, 'enseignant', 'notes', 'ecriture'),
(511, 15, 'enseignant', 'bulletin', 'ecriture'),
(512, 15, 'enseignant', 'resultats', 'ecriture'),
(513, 15, 'enseignant', 'examen_blanc', 'ecriture'),
(514, 15, 'enseignant', 'resultats_examen_blanc', 'ecriture'),
(515, 15, 'enseignant', 'Pédagogique', 'ecriture'),
(516, 15, 'enseignant', 'emploi_temps', 'ecriture'),
(517, 15, 'enseignant', 'fiche_presence', 'masquer'),
(518, 15, 'enseignant', 'calendrier', 'masquer'),
(519, 15, 'enseignant', 'notifications', 'masquer'),
(520, 15, 'enseignant', 'cartes', 'masquer'),
(521, 15, 'enseignant', 'depot_dossier', 'masquer'),
(522, 15, 'enseignant', 'fpe', 'masquer'),
(523, 15, 'enseignant', 'liste_assurance', 'masquer'),
(524, 15, 'enseignant', 'RH', 'ecriture'),
(525, 15, 'enseignant', 'enseignants', 'masquer'),
(526, 15, 'enseignant', 'staff', 'masquer'),
(527, 15, 'enseignant', 'presence', 'ecriture'),
(528, 15, 'enseignant', 'presence_staff', 'masquer'),
(529, 15, 'enseignant', 'permissions', 'masquer'),
(530, 15, 'enseignant', 'gestion_ressource', 'masquer'),
(531, 15, 'enseignant', 'Communication', 'ecriture'),
(532, 15, 'enseignant', 'communication', 'ecriture'),
(533, 15, 'enseignant', 'chat_private', 'ecriture'),
(534, 15, 'enseignant', 'chat_group', 'ecriture'),
(535, 15, 'enseignant', 'Paiement', 'masquer'),
(536, 15, 'enseignant', 'detail_paiement', 'masquer'),
(537, 15, 'enseignant', 'comptable', 'masquer'),
(538, 15, 'enseignant', 'liste_paiements', 'masquer'),
(539, 15, 'enseignant', 'facture', 'masquer'),
(540, 15, 'enseignant', 'Sectrapport', 'aucun'),
(541, 15, 'enseignant', 'rapport_comptable', 'masquer'),
(542, 15, 'enseignant', 'rapport_presence', 'masquer'),
(543, 15, 'enseignant', 'rapport_staff', 'masquer'),
(544, 15, 'enseignant', 'evaluation_notes', 'masquer'),
(545, 15, 'enseignant', 'Sectrapp', 'masquer'),
(546, 15, 'enseignant', 'parametres', 'masquer'),
(547, 15, 'enseignant', 'sauvegardes', 'masquer'),
(548, 17, 'enseignant', 'Administration', 'masquer'),
(549, 17, 'enseignant', 'dashboard', 'masquer'),
(550, 17, 'enseignant', 'ecole', 'masquer'),
(551, 17, 'enseignant', 'Admin', 'masquer'),
(552, 17, 'enseignant', 'inscription', 'masquer'),
(553, 17, 'enseignant', 'liste_classes', 'masquer'),
(554, 17, 'enseignant', 'matieres', 'masquer'),
(555, 17, 'enseignant', 'Enseignantss', 'ecriture'),
(556, 17, 'enseignant', 'notes', 'ecriture'),
(557, 17, 'enseignant', 'bulletin', 'ecriture'),
(558, 17, 'enseignant', 'resultats', 'ecriture'),
(559, 17, 'enseignant', 'examen_blanc', 'ecriture'),
(560, 17, 'enseignant', 'resultats_examen_blanc', 'ecriture'),
(561, 17, 'enseignant', 'Pédagogique', 'ecriture'),
(562, 17, 'enseignant', 'emploi_temps', 'ecriture'),
(563, 17, 'enseignant', 'fiche_presence', 'masquer'),
(564, 17, 'enseignant', 'calendrier', 'masquer'),
(565, 17, 'enseignant', 'notifications', 'masquer'),
(566, 17, 'enseignant', 'cartes', 'masquer'),
(567, 17, 'enseignant', 'depot_dossier', 'masquer'),
(568, 17, 'enseignant', 'fpe', 'masquer'),
(569, 17, 'enseignant', 'liste_assurance', 'masquer'),
(570, 17, 'enseignant', 'RH', 'masquer'),
(571, 17, 'enseignant', 'enseignants', 'masquer'),
(572, 17, 'enseignant', 'staff', 'masquer'),
(573, 17, 'enseignant', 'presence', 'ecriture'),
(574, 17, 'enseignant', 'presence_staff', 'masquer'),
(575, 17, 'enseignant', 'permissions', 'masquer'),
(576, 17, 'enseignant', 'gestion_ressource', 'masquer'),
(577, 17, 'enseignant', 'Communication', 'ecriture'),
(578, 17, 'enseignant', 'communication', 'ecriture'),
(579, 17, 'enseignant', 'chat_private', 'ecriture'),
(580, 17, 'enseignant', 'chat_group', 'ecriture'),
(581, 17, 'enseignant', 'Paiement', 'masquer'),
(582, 17, 'enseignant', 'detail_paiement', 'masquer'),
(583, 17, 'enseignant', 'comptable', 'masquer'),
(584, 17, 'enseignant', 'liste_paiements', 'masquer'),
(585, 17, 'enseignant', 'facture', 'masquer'),
(586, 17, 'enseignant', 'Sectrapport', 'ecriture'),
(587, 17, 'enseignant', 'rapport_comptable', 'masquer'),
(588, 17, 'enseignant', 'rapport_presence', 'masquer'),
(589, 17, 'enseignant', 'rapport_staff', 'masquer'),
(590, 17, 'enseignant', 'evaluation_notes', 'ecriture'),
(591, 17, 'enseignant', 'Sectrapp', 'ecriture'),
(592, 17, 'enseignant', 'parametres', 'masquer'),
(593, 17, 'enseignant', 'sauvegardes', 'ecriture'),
(594, 15, 'enseignant', 'Administration', 'masquer'),
(595, 15, 'enseignant', 'dashboard', 'masquer'),
(596, 15, 'enseignant', 'ecole', 'masquer'),
(597, 15, 'enseignant', 'Admin', 'masquer'),
(598, 15, 'enseignant', 'inscription', 'masquer'),
(599, 15, 'enseignant', 'liste_classes', 'masquer'),
(600, 15, 'enseignant', 'matieres', 'masquer'),
(601, 15, 'enseignant', 'Enseignantss', 'ecriture'),
(602, 15, 'enseignant', 'notes', 'ecriture'),
(603, 15, 'enseignant', 'bulletin', 'ecriture'),
(604, 15, 'enseignant', 'resultats', 'ecriture'),
(605, 15, 'enseignant', 'examen_blanc', 'ecriture'),
(606, 15, 'enseignant', 'resultats_examen_blanc', 'ecriture'),
(607, 15, 'enseignant', 'Pédagogique', 'ecriture'),
(608, 15, 'enseignant', 'emploi_temps', 'ecriture'),
(609, 15, 'enseignant', 'fiche_presence', 'masquer'),
(610, 15, 'enseignant', 'calendrier', 'masquer'),
(611, 15, 'enseignant', 'notifications', 'masquer'),
(612, 15, 'enseignant', 'cartes', 'masquer'),
(613, 15, 'enseignant', 'depot_dossier', 'masquer'),
(614, 15, 'enseignant', 'fpe', 'masquer'),
(615, 15, 'enseignant', 'liste_assurance', 'masquer'),
(616, 15, 'enseignant', 'RH', 'ecriture'),
(617, 15, 'enseignant', 'enseignants', 'masquer'),
(618, 15, 'enseignant', 'staff', 'masquer'),
(619, 15, 'enseignant', 'presence', 'ecriture'),
(620, 15, 'enseignant', 'presence_staff', 'masquer'),
(621, 15, 'enseignant', 'permissions', 'masquer'),
(622, 15, 'enseignant', 'gestion_ressource', 'masquer'),
(623, 15, 'enseignant', 'Communication', 'ecriture'),
(624, 15, 'enseignant', 'communication', 'ecriture'),
(625, 15, 'enseignant', 'chat_private', 'ecriture'),
(626, 15, 'enseignant', 'chat_group', 'ecriture'),
(627, 15, 'enseignant', 'Paiement', 'masquer'),
(628, 15, 'enseignant', 'detail_paiement', 'masquer'),
(629, 15, 'enseignant', 'comptable', 'masquer'),
(630, 15, 'enseignant', 'liste_paiements', 'masquer'),
(631, 15, 'enseignant', 'facture', 'masquer'),
(632, 15, 'enseignant', 'Sectrapport', 'ecriture'),
(633, 15, 'enseignant', 'rapport_comptable', 'masquer'),
(634, 15, 'enseignant', 'rapport_presence', 'masquer'),
(635, 15, 'enseignant', 'rapport_staff', 'masquer'),
(636, 15, 'enseignant', 'evaluation_notes', 'ecriture'),
(637, 15, 'enseignant', 'Sectrapp', 'ecriture'),
(638, 15, 'enseignant', 'parametres', 'masquer'),
(639, 15, 'enseignant', 'sauvegardes', 'ecriture'),
(640, 20, 'staff', 'Administration', 'masquer'),
(641, 20, 'staff', 'dashboard', 'masquer'),
(642, 20, 'staff', 'ecole', 'masquer'),
(643, 20, 'staff', 'Admin', 'masquer'),
(644, 20, 'staff', 'inscription', 'masquer'),
(645, 20, 'staff', 'liste_classes', 'masquer'),
(646, 20, 'staff', 'matieres', 'masquer'),
(647, 20, 'staff', 'Enseignantss', 'aucun'),
(648, 20, 'staff', 'notes', 'aucun'),
(649, 20, 'staff', 'bulletin', 'aucun'),
(650, 20, 'staff', 'resultats', 'aucun'),
(651, 20, 'staff', 'examen_blanc', 'aucun'),
(652, 20, 'staff', 'resultats_examen_blanc', 'aucun'),
(653, 20, 'staff', 'Pédagogique', 'aucun'),
(654, 20, 'staff', 'emploi_temps', 'aucun'),
(655, 20, 'staff', 'fiche_presence', 'aucun'),
(656, 20, 'staff', 'calendrier', 'aucun'),
(657, 20, 'staff', 'notifications', 'aucun'),
(658, 20, 'staff', 'cartes', 'aucun'),
(659, 20, 'staff', 'depot_dossier', 'aucun'),
(660, 20, 'staff', 'fpe', 'aucun'),
(661, 20, 'staff', 'liste_assurance', 'aucun'),
(662, 20, 'staff', 'RH', 'aucun'),
(663, 20, 'staff', 'enseignants', 'aucun'),
(664, 20, 'staff', 'staff', 'aucun'),
(665, 20, 'staff', 'presence', 'aucun'),
(666, 20, 'staff', 'presence_staff', 'aucun'),
(667, 20, 'staff', 'permissions', 'aucun'),
(668, 20, 'staff', 'gestion_ressource', 'aucun'),
(669, 20, 'staff', 'Communication', 'aucun'),
(670, 20, 'staff', 'communication', 'aucun'),
(671, 20, 'staff', 'chat_private', 'aucun'),
(672, 20, 'staff', 'chat_group', 'aucun'),
(673, 20, 'staff', 'Paiement', 'aucun'),
(674, 20, 'staff', 'detail_paiement', 'aucun'),
(675, 20, 'staff', 'comptable', 'aucun'),
(676, 20, 'staff', 'liste_paiements', 'aucun'),
(677, 20, 'staff', 'facture', 'aucun'),
(678, 20, 'staff', 'Sectrapport', 'aucun'),
(679, 20, 'staff', 'rapport_comptable', 'aucun'),
(680, 20, 'staff', 'rapport_presence', 'aucun'),
(681, 20, 'staff', 'rapport_staff', 'aucun'),
(682, 20, 'staff', 'evaluation_notes', 'aucun'),
(683, 20, 'staff', 'Sectrapp', 'aucun'),
(684, 20, 'staff', 'parametres', 'aucun'),
(685, 20, 'staff', 'sauvegardes', 'aucun'),
(686, 19, 'admin', 'Administration', 'aucun'),
(687, 19, 'admin', 'dashboard', 'aucun'),
(688, 19, 'admin', 'ecole', 'aucun'),
(689, 19, 'admin', 'Admin', 'aucun'),
(690, 19, 'admin', 'inscription', 'aucun'),
(691, 19, 'admin', 'liste_classes', 'aucun'),
(692, 19, 'admin', 'matieres', 'aucun'),
(693, 19, 'admin', 'Enseignantss', 'aucun'),
(694, 19, 'admin', 'notes', 'aucun'),
(695, 19, 'admin', 'bulletin', 'aucun'),
(696, 19, 'admin', 'resultats', 'aucun'),
(697, 19, 'admin', 'examen_blanc', 'aucun'),
(698, 19, 'admin', 'resultats_examen_blanc', 'aucun'),
(699, 19, 'admin', 'Pédagogique', 'aucun'),
(700, 19, 'admin', 'emploi_temps', 'aucun'),
(701, 19, 'admin', 'fiche_presence', 'aucun'),
(702, 19, 'admin', 'calendrier', 'aucun'),
(703, 19, 'admin', 'notifications', 'aucun'),
(704, 19, 'admin', 'cartes', 'aucun'),
(705, 19, 'admin', 'depot_dossier', 'aucun'),
(706, 19, 'admin', 'fpe', 'aucun'),
(707, 19, 'admin', 'liste_assurance', 'aucun'),
(708, 19, 'admin', 'RH', 'aucun'),
(709, 19, 'admin', 'enseignants', 'aucun'),
(710, 19, 'admin', 'staff', 'aucun'),
(711, 19, 'admin', 'presence', 'aucun'),
(712, 19, 'admin', 'presence_staff', 'aucun'),
(713, 19, 'admin', 'permissions', 'lecture'),
(714, 19, 'admin', 'gestion_ressource', 'aucun'),
(715, 19, 'admin', 'Communication', 'aucun'),
(716, 19, 'admin', 'communication', 'aucun'),
(717, 19, 'admin', 'chat_private', 'aucun'),
(718, 19, 'admin', 'chat_group', 'aucun'),
(719, 19, 'admin', 'Paiement', 'aucun'),
(720, 19, 'admin', 'detail_paiement', 'aucun'),
(721, 19, 'admin', 'comptable', 'aucun'),
(722, 19, 'admin', 'liste_paiements', 'aucun'),
(723, 19, 'admin', 'facture', 'aucun'),
(724, 19, 'admin', 'Sectrapport', 'aucun'),
(725, 19, 'admin', 'rapport_comptable', 'aucun'),
(726, 19, 'admin', 'rapport_presence', 'aucun'),
(727, 19, 'admin', 'rapport_staff', 'aucun'),
(728, 19, 'admin', 'evaluation_notes', 'aucun'),
(729, 19, 'admin', 'Sectrapp', 'aucun'),
(730, 19, 'admin', 'parametres', 'aucun'),
(731, 19, 'admin', 'sauvegardes', 'aucun'),
(732, 19, 'admin', 'Administration', 'aucun'),
(733, 19, 'admin', 'dashboard', 'aucun'),
(734, 19, 'admin', 'ecole', 'aucun'),
(735, 19, 'admin', 'Admin', 'aucun'),
(736, 19, 'admin', 'inscription', 'aucun'),
(737, 19, 'admin', 'liste_classes', 'aucun'),
(738, 19, 'admin', 'matieres', 'aucun'),
(739, 19, 'admin', 'Enseignantss', 'aucun'),
(740, 19, 'admin', 'notes', 'aucun'),
(741, 19, 'admin', 'bulletin', 'aucun'),
(742, 19, 'admin', 'resultats', 'aucun'),
(743, 19, 'admin', 'examen_blanc', 'aucun'),
(744, 19, 'admin', 'resultats_examen_blanc', 'aucun'),
(745, 19, 'admin', 'Pédagogique', 'aucun'),
(746, 19, 'admin', 'emploi_temps', 'aucun'),
(747, 19, 'admin', 'fiche_presence', 'aucun'),
(748, 19, 'admin', 'calendrier', 'aucun'),
(749, 19, 'admin', 'notifications', 'aucun'),
(750, 19, 'admin', 'cartes', 'aucun'),
(751, 19, 'admin', 'depot_dossier', 'aucun'),
(752, 19, 'admin', 'fpe', 'aucun'),
(753, 19, 'admin', 'liste_assurance', 'aucun'),
(754, 19, 'admin', 'RH', 'aucun'),
(755, 19, 'admin', 'enseignants', 'aucun'),
(756, 19, 'admin', 'staff', 'aucun'),
(757, 19, 'admin', 'presence', 'aucun'),
(758, 19, 'admin', 'presence_staff', 'aucun'),
(759, 19, 'admin', 'permissions', 'lecture'),
(760, 19, 'admin', 'gestion_ressource', 'aucun'),
(761, 19, 'admin', 'Communication', 'aucun'),
(762, 19, 'admin', 'communication', 'aucun'),
(763, 19, 'admin', 'chat_private', 'aucun'),
(764, 19, 'admin', 'chat_group', 'aucun'),
(765, 19, 'admin', 'Paiement', 'aucun'),
(766, 19, 'admin', 'detail_paiement', 'aucun'),
(767, 19, 'admin', 'comptable', 'aucun'),
(768, 19, 'admin', 'liste_paiements', 'aucun'),
(769, 19, 'admin', 'facture', 'aucun'),
(770, 19, 'admin', 'Sectrapport', 'aucun'),
(771, 19, 'admin', 'rapport_comptable', 'aucun'),
(772, 19, 'admin', 'rapport_presence', 'aucun'),
(773, 19, 'admin', 'rapport_staff', 'aucun'),
(774, 19, 'admin', 'evaluation_notes', 'aucun'),
(775, 19, 'admin', 'Sectrapp', 'aucun'),
(776, 19, 'admin', 'parametres', 'aucun'),
(777, 19, 'admin', 'sauvegardes', 'aucun'),
(778, 20, 'staff', 'Administration', 'masquer'),
(779, 20, 'staff', 'dashboard', 'masquer'),
(780, 20, 'staff', 'ecole', 'masquer'),
(781, 20, 'staff', 'Admin', 'masquer'),
(782, 20, 'staff', 'inscription', 'masquer'),
(783, 20, 'staff', 'liste_classes', 'masquer'),
(784, 20, 'staff', 'matieres', 'masquer'),
(785, 20, 'staff', 'Enseignantss', 'aucun'),
(786, 20, 'staff', 'notes', 'aucun'),
(787, 20, 'staff', 'bulletin', 'aucun'),
(788, 20, 'staff', 'resultats', 'aucun'),
(789, 20, 'staff', 'examen_blanc', 'aucun'),
(790, 20, 'staff', 'resultats_examen_blanc', 'aucun'),
(791, 20, 'staff', 'Pédagogique', 'aucun'),
(792, 20, 'staff', 'emploi_temps', 'aucun'),
(793, 20, 'staff', 'fiche_presence', 'aucun'),
(794, 20, 'staff', 'calendrier', 'aucun'),
(795, 20, 'staff', 'notifications', 'aucun'),
(796, 20, 'staff', 'cartes', 'aucun'),
(797, 20, 'staff', 'depot_dossier', 'aucun'),
(798, 20, 'staff', 'fpe', 'aucun'),
(799, 20, 'staff', 'liste_assurance', 'aucun'),
(800, 20, 'staff', 'RH', 'aucun'),
(801, 20, 'staff', 'enseignants', 'aucun'),
(802, 20, 'staff', 'staff', 'aucun'),
(803, 20, 'staff', 'presence', 'aucun'),
(804, 20, 'staff', 'presence_staff', 'aucun'),
(805, 20, 'staff', 'permissions', 'aucun'),
(806, 20, 'staff', 'gestion_ressource', 'aucun'),
(807, 20, 'staff', 'Communication', 'aucun'),
(808, 20, 'staff', 'communication', 'aucun'),
(809, 20, 'staff', 'chat_private', 'aucun'),
(810, 20, 'staff', 'chat_group', 'aucun'),
(811, 20, 'staff', 'Paiement', 'aucun'),
(812, 20, 'staff', 'detail_paiement', 'aucun'),
(813, 20, 'staff', 'comptable', 'aucun'),
(814, 20, 'staff', 'liste_paiements', 'aucun'),
(815, 20, 'staff', 'facture', 'aucun'),
(816, 20, 'staff', 'Sectrapport', 'aucun'),
(817, 20, 'staff', 'rapport_comptable', 'aucun'),
(818, 20, 'staff', 'rapport_presence', 'aucun'),
(819, 20, 'staff', 'rapport_staff', 'aucun'),
(820, 20, 'staff', 'evaluation_notes', 'aucun'),
(821, 20, 'staff', 'Sectrapp', 'aucun'),
(822, 20, 'staff', 'parametres', 'aucun'),
(823, 20, 'staff', 'sauvegardes', 'aucun'),
(824, 17, 'enseignant', 'Administration', 'masquer'),
(825, 17, 'enseignant', 'dashboard', 'masquer'),
(826, 17, 'enseignant', 'ecole', 'masquer'),
(827, 17, 'enseignant', 'Admin', 'masquer'),
(828, 17, 'enseignant', 'inscription', 'masquer'),
(829, 17, 'enseignant', 'liste_classes', 'masquer'),
(830, 17, 'enseignant', 'matieres', 'masquer'),
(831, 17, 'enseignant', 'Enseignantss', 'ecriture'),
(832, 17, 'enseignant', 'notes', 'ecriture'),
(833, 17, 'enseignant', 'bulletin', 'ecriture'),
(834, 17, 'enseignant', 'resultats', 'ecriture'),
(835, 17, 'enseignant', 'examen_blanc', 'ecriture'),
(836, 17, 'enseignant', 'resultats_examen_blanc', 'ecriture'),
(837, 17, 'enseignant', 'Pédagogique', 'ecriture'),
(838, 17, 'enseignant', 'emploi_temps', 'ecriture'),
(839, 17, 'enseignant', 'fiche_presence', 'masquer'),
(840, 17, 'enseignant', 'calendrier', 'masquer'),
(841, 17, 'enseignant', 'notifications', 'masquer'),
(842, 17, 'enseignant', 'cartes', 'masquer'),
(843, 17, 'enseignant', 'depot_dossier', 'masquer'),
(844, 17, 'enseignant', 'fpe', 'masquer'),
(845, 17, 'enseignant', 'liste_assurance', 'masquer'),
(846, 17, 'enseignant', 'RH', 'masquer'),
(847, 17, 'enseignant', 'enseignants', 'masquer'),
(848, 17, 'enseignant', 'staff', 'masquer'),
(849, 17, 'enseignant', 'presence', 'ecriture'),
(850, 17, 'enseignant', 'presence_staff', 'masquer'),
(851, 17, 'enseignant', 'permissions', 'masquer'),
(852, 17, 'enseignant', 'gestion_ressource', 'masquer'),
(853, 17, 'enseignant', 'Communication', 'ecriture'),
(854, 17, 'enseignant', 'communication', 'ecriture'),
(855, 17, 'enseignant', 'chat_private', 'ecriture'),
(856, 17, 'enseignant', 'chat_group', 'ecriture'),
(857, 17, 'enseignant', 'Paiement', 'masquer'),
(858, 17, 'enseignant', 'detail_paiement', 'masquer'),
(859, 17, 'enseignant', 'comptable', 'masquer'),
(860, 17, 'enseignant', 'liste_paiements', 'masquer'),
(861, 17, 'enseignant', 'facture', 'masquer'),
(862, 17, 'enseignant', 'Sectrapport', 'ecriture'),
(863, 17, 'enseignant', 'rapport_comptable', 'masquer'),
(864, 17, 'enseignant', 'rapport_presence', 'masquer'),
(865, 17, 'enseignant', 'rapport_staff', 'masquer'),
(866, 17, 'enseignant', 'evaluation_notes', 'ecriture'),
(867, 17, 'enseignant', 'Sectrapp', 'ecriture'),
(868, 17, 'enseignant', 'parametres', 'masquer'),
(869, 17, 'enseignant', 'sauvegardes', 'ecriture'),
(870, 20, 'staff', 'Administration', 'masquer'),
(871, 20, 'staff', 'dashboard', 'masquer'),
(872, 20, 'staff', 'ecole', 'masquer'),
(873, 20, 'staff', 'Admin', 'masquer'),
(874, 20, 'staff', 'inscription', 'masquer'),
(875, 20, 'staff', 'liste_classes', 'masquer'),
(876, 20, 'staff', 'matieres', 'masquer'),
(877, 20, 'staff', 'Enseignantss', 'aucun'),
(878, 20, 'staff', 'notes', 'aucun'),
(879, 20, 'staff', 'bulletin', 'aucun'),
(880, 20, 'staff', 'resultats', 'aucun'),
(881, 20, 'staff', 'examen_blanc', 'aucun'),
(882, 20, 'staff', 'resultats_examen_blanc', 'aucun'),
(883, 20, 'staff', 'Pédagogique', 'aucun'),
(884, 20, 'staff', 'emploi_temps', 'aucun'),
(885, 20, 'staff', 'fiche_presence', 'ecriture'),
(886, 20, 'staff', 'calendrier', 'aucun'),
(887, 20, 'staff', 'notifications', 'aucun'),
(888, 20, 'staff', 'cartes', 'aucun'),
(889, 20, 'staff', 'depot_dossier', 'aucun'),
(890, 20, 'staff', 'fpe', 'aucun'),
(891, 20, 'staff', 'liste_assurance', 'aucun'),
(892, 20, 'staff', 'RH', 'aucun'),
(893, 20, 'staff', 'enseignants', 'aucun'),
(894, 20, 'staff', 'staff', 'aucun'),
(895, 20, 'staff', 'presence', 'aucun'),
(896, 20, 'staff', 'presence_staff', 'aucun'),
(897, 20, 'staff', 'permissions', 'aucun'),
(898, 20, 'staff', 'gestion_ressource', 'aucun'),
(899, 20, 'staff', 'Communication', 'aucun'),
(900, 20, 'staff', 'communication', 'aucun'),
(901, 20, 'staff', 'chat_private', 'aucun'),
(902, 20, 'staff', 'chat_group', 'aucun'),
(903, 20, 'staff', 'Paiement', 'aucun'),
(904, 20, 'staff', 'detail_paiement', 'aucun'),
(905, 20, 'staff', 'comptable', 'aucun'),
(906, 20, 'staff', 'liste_paiements', 'aucun'),
(907, 20, 'staff', 'facture', 'aucun'),
(908, 20, 'staff', 'Sectrapport', 'aucun'),
(909, 20, 'staff', 'rapport_comptable', 'aucun'),
(910, 20, 'staff', 'rapport_presence', 'aucun'),
(911, 20, 'staff', 'rapport_staff', 'aucun'),
(912, 20, 'staff', 'evaluation_notes', 'aucun'),
(913, 20, 'staff', 'Sectrapp', 'aucun'),
(914, 20, 'staff', 'parametres', 'aucun'),
(915, 20, 'staff', 'sauvegardes', 'aucun');

-- --------------------------------------------------------

--
-- Table structure for table `personnes`
--

DROP TABLE IF EXISTS `personnes`;
CREATE TABLE IF NOT EXISTS `personnes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `prenom` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `classes` enum('eleve','professeur','staff') COLLATE utf8mb4_general_ci NOT NULL,
  `id_personne` int NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `type_personne` (`classes`,`id_personne`)
) ENGINE=InnoDB AUTO_INCREMENT=69 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `presence_personnels`
--

DROP TABLE IF EXISTS `presence_personnels`;
CREATE TABLE IF NOT EXISTS `presence_personnels` (
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
) ENGINE=InnoDB AUTO_INCREMENT=89 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `presence_personnels`
--

INSERT INTO `presence_personnels` (`id`, `personne_id`, `annee_scolaire`, `mois`, `date_jour`, `presence`, `date_enregistrement`, `horaire`, `retard`) VALUES
(83, 2, '2026', '02', '2026-02-18', 1, '2026-02-18 10:21:57', '2.00', 0),
(84, 4, '2026', '02', '2026-02-18', 1, '2026-02-18 10:23:33', '3.00', 1),
(85, 6, '2026', '02', '2026-02-18', 1, '2026-02-18 10:24:41', '3.00', 0),
(86, 5, '2026', '02', '2026-02-18', 0, '2026-02-18 10:26:04', '2.00', 0),
(87, 4, '2026', '02', '2026-02-27', 1, '2026-02-27 17:31:45', '3.00', 0),
(88, 5, '2026', '02', '2026-02-28', 1, '2026-02-28 02:14:57', '1.45', 1);

-- --------------------------------------------------------

--
-- Table structure for table `presence_eleves`
--

DROP TABLE IF EXISTS `presence_eleves`;
CREATE TABLE IF NOT EXISTS `presence_eleves` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `eleve_id` bigint unsigned NOT NULL,
  `classe_id` bigint unsigned NOT NULL,
  `annee_scolaire` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `mois` tinyint unsigned NOT NULL,
  `date_jour` date NOT NULL,
  `session_jour` enum('matin','apres_midi') COLLATE utf8mb4_general_ci NOT NULL,
  `statut` enum('present','absent','retard') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'present',
  `commentaire` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `presence_eleves_unique_day_session` (`eleve_id`,`date_jour`,`session_jour`),
  KEY `presence_eleves_classe_annee_mois` (`classe_id`,`annee_scolaire`,`mois`),
  KEY `presence_eleves_date_statut` (`date_jour`,`statut`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `presence_staff`
--

DROP TABLE IF EXISTS `presence_staff`;
CREATE TABLE IF NOT EXISTS `presence_staff` (
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
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `presence_staff`
--

INSERT INTO `presence_staff` (`id`, `personne_id`, `annee_scolaire`, `mois`, `date_jour`, `presence`, `date_enregistrement`, `retard`, `jours`) VALUES
(11, 13, '2026', '02', '2026-02-27', 1, '2026-02-27 07:49:14', 0, '0.00'),
(12, 14, '2026', '02', '2026-02-23', 1, '2026-02-27 15:24:24', 0, '1.00'),
(13, 14, '2026', '02', '2026-02-24', 1, '2026-02-27 15:25:16', 1, '1.00'),
(14, 14, '2026', '02', '2026-02-25', 1, '2026-02-27 15:25:28', 0, '1.00'),
(15, 14, '2026', '02', '2026-02-26', 0, '2026-02-27 15:25:47', 1, '1.00'),
(16, 14, '2026', '02', '2026-02-27', 1, '2026-02-27 15:25:57', 0, '1.00'),
(17, 13, '2026', '02', '2026-02-27', 1, '2026-02-27 17:34:02', 0, '0.00');

-- --------------------------------------------------------

--
-- Table structure for table `professeurs`
--

DROP TABLE IF EXISTS `professeurs`;
CREATE TABLE IF NOT EXISTS `professeurs` (
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
  KEY `matiere_id` (`matiere_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `professeurs`
--

INSERT INTO `professeurs` (`id`, `nom`, `prenom`, `email`, `photo`, `annee_scolaire`, `date_inscription`, `telephone`, `salaire_horaire`, `matiere_id`, `diplome_pedagogique`, `autorisation_enseigner`, `annees_experience`, `statut`) VALUES
(2, 'RANDRIAMIFALY', 'Heriniaina', 'heru@gmail.com', 'images/prof_6895d35a32c94.jpg', '2025-2026', '2025-08-08 07:37:14', '0346558998', '20000', 18, 'Aucun', 'Non', 0, 'actif'),
(4, 'PERSEVERANCE', 'Pain', 'pain@gmail.com', 'images/prof_689dd4c1bf6c0.jpg', '2025-2026', '2025-08-14 09:21:21', '0387729952', '14000', 13, 'Aucun', 'Non', 0, 'actif'),
(5, 'EDWARD', 'Tojo Victoire', 'trandriamifalyH@gmail.com', 'images/prof_68af682233af3.jpg', '2025-2026', '2025-08-27 17:18:42', '0387729954', '25000', 33, 'Aucun', 'Non', 0, 'actif'),
(6, 'Nety', 'VOALOHANY', 'nety@gmail.com', 'images/prof_68b7def4e2d8f.jpg', '2025-2026', '2025-09-03 03:23:48', '0335668578', '12000', 45, 'Aucun', 'Non', 0, 'actif'),
(8, 'KOTO', 'Kanto', 'koto@gmail.com', 'images/prof_68b81fc582e1b.jpg', '2025-2026', '2025-09-03 08:00:21', '0387729958', '16000', 45, 'Aucun', 'Non', 3, 'actif');

-- --------------------------------------------------------

--
-- Table structure for table `professeurs_classes`
--

DROP TABLE IF EXISTS `professeurs_classes`;
CREATE TABLE IF NOT EXISTS `professeurs_classes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `professeur_id` int DEFAULT NULL,
  `classe_id` int DEFAULT NULL,
  `annee_scolaire` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `professeur_id` (`professeur_id`),
  KEY `classe_id` (`classe_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `professeurs_classes`
--

INSERT INTO `professeurs_classes` (`id`, `professeur_id`, `classe_id`, `annee_scolaire`) VALUES
(2, 8, 14, '2025-2026');

-- --------------------------------------------------------

--
-- Table structure for table `remarques`
--

DROP TABLE IF EXISTS `remarques`;
CREATE TABLE IF NOT EXISTS `remarques` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_eleve` int DEFAULT NULL,
  `periode` varchar(10) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `remarque` text COLLATE utf8mb4_general_ci,
  `annee_scolaire` varchar(10) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_eleve` (`id_eleve`)
) ENGINE=InnoDB AUTO_INCREMENT=119 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `remarques`
--

INSERT INTO `remarques` (`id`, `id_eleve`, `periode`, `remarque`, `annee_scolaire`) VALUES
(118, 286, 'T1', 'Assez-bien', '2025-2026');

-- --------------------------------------------------------

--
-- Table structure for table `remarques_examen_blanc`
--

DROP TABLE IF EXISTS `remarques_examen_blanc`;
CREATE TABLE IF NOT EXISTS `remarques_examen_blanc` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_eleve` int NOT NULL,
  `session` varchar(2) COLLATE utf8mb4_general_ci NOT NULL,
  `annee_scolaire` varchar(9) COLLATE utf8mb4_general_ci NOT NULL,
  `remarque` text COLLATE utf8mb4_general_ci,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_eleve` (`id_eleve`,`session`,`annee_scolaire`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reservations`
--

DROP TABLE IF EXISTS `reservations`;
CREATE TABLE IF NOT EXISTS `reservations` (
  `id_salle` int NOT NULL,
  `nom` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `salle` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `date_reservation` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_salle`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reservations_ressources`
--

DROP TABLE IF EXISTS `reservations_ressources`;
CREATE TABLE IF NOT EXISTS `reservations_ressources` (
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

--
-- Dumping data for table `reservations_ressources`
--

INSERT INTO `reservations_ressources` (`id`, `id_salle`, `date_reservation`, `utilisateur`, `utilisateur_id`, `heure_debut`, `heure_fin`, `statut`, `description`) VALUES
(9, 6, '2026-02-28 00:00:00', 0, 0, '22:40:00', '12:40:00', 'confirmé', 'Appel à réunion pour tous les enseignants le lundi. Evitez le retard. Cordialement'),
(11, 6, '2026-02-27 00:00:00', 0, 0, '23:51:00', '13:51:00', 'confirmé', 'Réunion spéciale pour nous'),
(12, 6, '2026-03-01 00:00:00', 0, 0, '22:10:00', '23:10:00', 'confirmé', 'Test résérvation'),
(13, 6, '2026-02-25 00:00:00', 0, 0, '23:17:00', '12:18:00', 'confirmé', 'rettt'),
(14, 6, '2026-02-25 00:00:00', 0, 0, '19:22:00', '21:22:00', 'confirmé', 'dede'),
(16, 6, '2026-03-03 00:00:00', 19, 0, '23:21:00', '13:21:00', 'confirmé', 'Réunion administratifs importants');

-- --------------------------------------------------------

--
-- Table structure for table `ressources`
--

DROP TABLE IF EXISTS `ressources`;
CREATE TABLE IF NOT EXISTS `ressources` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `categorie` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `quantite` int DEFAULT '1',
  `statut` enum('disponible','réservé','en maintenance') COLLATE utf8mb4_general_ci DEFAULT 'disponible',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `retards_personnels`
--

DROP TABLE IF EXISTS `retards_personnels`;
CREATE TABLE IF NOT EXISTS `retards_personnels` (
  `id` int NOT NULL AUTO_INCREMENT,
  `personne_id` int NOT NULL,
  `annee_scolaire` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `mois` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `date_jour` date NOT NULL,
  `retard` tinyint(1) DEFAULT '0',
  `date_enregistrement` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `revenus`
--

DROP TABLE IF EXISTS `revenus`;
CREATE TABLE IF NOT EXISTS `revenus` (
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
) ENGINE=InnoDB AUTO_INCREMENT=85 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `revenus`
--

INSERT INTO `revenus` (`id`, `type_id`, `personne_id`, `type_personne`, `classes`, `mois`, `annee_scolaire`, `montant`, `description`, `mode_paiement`, `statut`, `categorie`, `date_enregistrement`, `nom_personne`) VALUES
(71, 25, '296', 'eleve', 'MS', 'Janvier', '2025-2026', '500000.00', 'Paiement écolage Ecolage', 'Espèces', 'complet', 'Écolage', '2026-02-07 16:46:38', 'ANIO Fyh'),
(72, 25, '300', 'eleve', 'MS', 'Janvier', '2025-2026', '500000.00', 'Paiement écolage Ecolage', 'Espèces', 'complet', 'Écolage', '2026-02-07 16:59:58', 'ANIO Foniah'),
(73, 25, '295', 'eleve', 'MS', 'Janvier', '2025-2026', '500000.00', 'Paiement écolage Ecolage', 'Espèces', 'complet', 'Écolage', '2026-02-18 02:25:48', 'ANIO Tody'),
(74, 25, '297', 'eleve', 'MS', 'Janvier', '2025-2026', '500000.00', 'Paiement écolage Ecolage', 'Espèces', 'complet', 'Écolage', '2026-02-18 02:26:05', 'ANIO Fano'),
(75, 25, '298', 'eleve', 'MS', 'Janvier', '2025-2026', '500000.00', 'Paiement écolage Ecolage', 'Espèces', 'complet', 'Écolage', '2026-02-18 02:33:14', 'ANIO Fonja'),
(76, 25, '299', 'eleve', 'MS', 'Janvier', '2025-2026', '500000.00', 'Paiement écolage Ecolage', 'Espèces', 'complet', 'Écolage', '2026-02-18 02:43:06', 'ANIO Fery'),
(77, 25, '301', 'eleve', 'MS', 'Janvier', '2025-2026', '500000.00', 'Paiement écolage Ecolage', 'Espèces', 'complet', 'Écolage', '2026-02-18 04:01:09', 'ANIO Fanih'),
(78, 25, '302', 'eleve', 'MS', 'Janvier', '2025-2026', '500000.00', 'Paiement écolage Ecolage', 'Espèces', 'complet', 'Écolage', '2026-02-18 13:27:19', 'ANIO Faniahy'),
(79, 25, '303', 'eleve', 'MS', 'Janvier', '2025-2026', '500000.00', 'Paiement écolage Ecolage', 'Espèces', 'complet', 'Écolage', '2026-02-18 13:27:33', 'ANIO Faniho'),
(80, 27, '295', 'eleve', 'MS', 'Avril', '2025-2026', '150000.00', 'Paiement écolage Droit D\\\'inscription', 'Espèces', 'complet', 'Écolage', '2026-02-27 20:50:33', 'ANIO Tody'),
(81, 27, '297', 'eleve', 'MS', 'Avril', '2025-2026', '150000.00', 'Paiement écolage Droit D\\\'inscription', 'Espèces', 'complet', 'Écolage', '2026-02-27 20:50:48', 'ANIO Fano'),
(82, 26, '306', 'eleve', 'TA', 'Mars', '2025-2026', '200000.00', 'Paiement écolage Ecolage', 'Espèces', 'complet', 'Écolage', '2026-02-27 20:53:59', 'ANIO Fyh'),
(83, 26, '305', 'eleve', 'TA', 'Mars', '2025-2026', '200000.00', 'Paiement écolage Ecolage', 'Espèces', 'complet', 'Écolage', '2026-02-28 17:19:41', 'ANIO Tody'),
(84, 27, '296', 'eleve', 'MS', 'Avril', '2025-2026', '150000.00', 'Paiement écolage Droit D\\\'inscription', 'Espèces', 'complet', 'Écolage', '2026-04-20 16:55:52', 'ANIO Fyh');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
CREATE TABLE IF NOT EXISTS `roles` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `nom`) VALUES
(1, 'Secrétaire'),
(2, 'Comptable'),
(3, 'RH'),
(4, 'Assistant'),
(5, 'Autre');

-- --------------------------------------------------------

--
-- Table structure for table `salaires_assignes`
--

DROP TABLE IF EXISTS `salaires_assignes`;
CREATE TABLE IF NOT EXISTS `salaires_assignes` (
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

--
-- Dumping data for table `salaires_assignes`
--

INSERT INTO `salaires_assignes` (`id`, `personne_id`, `type_personne`, `mois`, `annee_scolaire`, `statut`, `date_creation`) VALUES
(1, 13, 'staff', 'Février', '2025-2026', 'paye', '2026-02-07 16:14:44'),
(2, 14, 'staff', 'Février', '2025-2026', 'non_paye', '2026-02-07 16:14:44'),
(3, 1, 'professeur', 'Janvier', '2025-2026', 'paye', '2026-02-07 19:12:21'),
(4, 2, 'professeur', 'Janvier', '2025-2026', 'paye', '2026-02-07 19:12:21'),
(5, 4, 'professeur', 'Janvier', '2025-2026', 'paye', '2026-02-07 19:12:21'),
(6, 5, 'professeur', 'Janvier', '2025-2026', 'non_paye', '2026-02-07 19:12:21'),
(7, 6, 'professeur', 'Janvier', '2025-2026', 'non_paye', '2026-02-07 19:12:21'),
(8, 7, 'professeur', 'Janvier', '2025-2026', 'non_paye', '2026-02-07 19:12:21'),
(9, 8, 'professeur', 'Janvier', '2025-2026', 'non_paye', '2026-02-07 19:12:21');

-- --------------------------------------------------------

--
-- Table structure for table `salles`
--

DROP TABLE IF EXISTS `salles`;
CREATE TABLE IF NOT EXISTS `salles` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `capacite` varchar(30) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_general_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `salles`
--

INSERT INTO `salles` (`id`, `nom`, `capacite`, `description`) VALUES
(6, 'Salle de Réunion', '30', 'C\'est une salle pour faire une réunion dédié aux enseignants seulement');

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

DROP TABLE IF EXISTS `staff`;
CREATE TABLE IF NOT EXISTS `staff` (
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
  KEY `role_id` (`role_id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `staff`
--

INSERT INTO `staff` (`id`, `nom`, `prenom`, `email`, `photo`, `annee_scolaire`, `date_inscription`, `telephone`, `salaire_base`, `role_id`, `diplome_pedagogique`, `annees_experience`, `statut`, `departement_id`) VALUES
(13, 'RANDRIAMBOLANIAINA', 'Avotra Fenosoa', 'fa@gmail.com', 'images/staff_6905ba8893745.jpg', '2025-2026', '2025-11-01 07:45:12', '0342565820', '16000', 2, 'Master', 3, 'actif', 0),
(14, 'ANDRIAMBOLANIAINA', 'Fenosoa', 'fiane@gmail.com', 'images/staff_6905bad10b1aa.jpg', '2025-2026', '2025-11-01 07:46:25', '0342565821', '16000', 3, 'Doctorat', 3, 'actif', 0);

-- --------------------------------------------------------

--
-- Table structure for table `types_paiements`
--

DROP TABLE IF EXISTS `types_paiements`;
CREATE TABLE IF NOT EXISTS `types_paiements` (
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
  PRIMARY KEY (`id`)
) ;

--
-- Dumping data for table `types_paiements`
--

INSERT INTO `types_paiements` (`id`, `nom`, `montant`, `mois`, `date_creation`, `classe`, `date_debut`, `date_fin`, `id_classe`, `type_personne`, `person_id`, `annee_scolaire`) VALUES
(25, 'Ecolage', '500000.00', '[\"Janvier\"]', '2026-02-07 16:46:24', 'MS', '2026-02-07', '2026-02-19', 2, NULL, NULL, '2025-2026'),
(26, 'Ecolage', '200000.00', '[\"Mars\"]', '2026-02-27 20:42:28', 'TA', '2026-02-27', '2026-03-13', 16, NULL, NULL, '2025-2026'),
(27, 'Droit D\'inscription', '150000.00', '[\"Avril\"]', '2026-02-27 20:45:26', 'MS', '2026-02-28', '2026-03-11', 2, NULL, NULL, '2025-2026');

-- --------------------------------------------------------

--
-- Table structure for table `typing_status`
--

DROP TABLE IF EXISTS `typing_status`;
CREATE TABLE IF NOT EXISTS `typing_status` (
  `conversation_id` int NOT NULL,
  `user_id` int NOT NULL,
  `user_type` enum('admin','enseignant','staff','parent') NOT NULL DEFAULT 'admin',
  `is_typing` tinyint UNSIGNED NOT NULL DEFAULT '0',
  `last_update` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`conversation_id`,`user_id`,`user_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `typing_status`
--

INSERT INTO `typing_status` (`conversation_id`, `user_id`, `user_type`, `is_typing`, `last_update`) VALUES
(256, 14, 'admin', 0, '2026-03-08 21:14:07'),
(256, 15, 'enseignant', 0, '2026-02-14 14:39:34'),
(257, 16, 'admin', 0, '2026-02-14 15:06:26'),
(258, 16, 'admin', 0, '2026-04-20 19:54:49'),
(258, 19, 'admin', 0, '2026-02-18 10:21:18'),
(261, 14, 'admin', 0, '2026-02-17 07:04:50'),
(263, 20, 'staff', 0, '2026-02-17 07:03:21'),
(266, 19, 'admin', 0, '2026-02-14 00:45:48'),
(266, 20, 'staff', 0, '2026-02-14 00:54:48'),
(270, 19, 'admin', 1, '2026-02-12 01:18:02'),
(271, 19, 'admin', 1, '2026-02-12 01:18:59'),
(275, 14, 'admin', 0, '2026-02-28 15:34:04'),
(275, 16, 'admin', 0, '2026-02-28 15:33:01'),
(276, 17, 'enseignant', 0, '2026-02-26 16:19:03'),
(276, 19, 'admin', 0, '2026-02-26 16:19:35'),
(277, 19, 'admin', 0, '2026-02-13 23:40:27'),
(279, 14, 'admin', 0, '2026-02-26 20:38:38'),
(279, 17, 'enseignant', 0, '2026-02-14 15:04:55'),
(281, 14, 'admin', 0, '2026-02-28 00:23:37'),
(281, 19, 'admin', 0, '2026-02-28 00:23:47'),
(295, 17, 'enseignant', 0, '2026-02-14 14:38:23');

-- --------------------------------------------------------

--
-- Table structure for table `utilisateurs`
--

DROP TABLE IF EXISTS `utilisateurs`;
CREATE TABLE IF NOT EXISTS `utilisateurs` (
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
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `utilisateurs`
--

INSERT INTO `utilisateurs` (`id`, `nom`, `email`, `mot_de_passe`, `avatar`, `role`, `cree_le`, `last_activity`) VALUES
(14, 'Tojo Nambinina RANDRIAMIFALY', 'novaskol@gmail.com', '$2y$10$23QkY2SdyMu4qA2qxqCGOeTTSpXk4DlXGoNairCQG1LJeXeNyQyXK', '1770991584_698f2fe08ffa1.jpg', 'admin', '2026-02-06 11:32:56', '2026-03-08 21:14:27'),
(15, 'test', 'test@gmail.com', '$2y$10$vaymU5mHH0auLnnv/ArlyuVLLbIXAvCazE1IMzMDoSQ7RkexuvhTC', 'images/default-avatar.png', 'enseignant', '2026-02-10 17:49:48', '2026-02-16 20:22:08'),
(16, 'diary', 'diary@gmail.com', '$2y$10$ViWkvkKjn/K4Lw66..1u4Om08yRMf2esNrCWugORitWGK/PImHz4a', '1771006501_698f6a25aa699.jpg', 'admin', '2026-02-10 17:58:21', '2026-04-20 19:54:56'),
(17, 'Eivan KIMBERLEY', 'kim@gmail.com', '$2y$10$x9j9VZNbriiGtBzkdPCXwuz0YeDy7wa7u6h/HQsp1i1efczCOZxOK', 'images/default-avatar.png', 'enseignant', '2026-02-11 16:26:29', '2026-02-26 20:48:51'),
(18, 'Henry FALY', 'henry@gmail.com', '$2y$10$F5Kbh.sMtq.SsyUtumGw5uMW.eagGgUoUdEkiqSIxLEN6GQBeADzu', 'images/default-avatar.png', 'admin', '2026-02-11 16:57:39', '2026-02-11 16:57:45'),
(19, 'Stessy BELLA', 'stessy@gmail.com', '$2y$10$0w9FQqoDjX299E804aE/k.ZbDdokHVaUmoqdEP32hKjwpKM6BavSu', '1770991187_698f2e5378b8a.jpg', 'admin', '2026-02-11 16:58:31', '2026-03-05 20:03:22'),
(20, 'Firmin', 'Firmin@gmail.com', '$2y$10$Jvxe78z9sUspB49qIGl9Fuh/kL0V7SZWVQdwFU1XYImmpbqGkCU7.', 'images/default-avatar.png', 'staff', '2026-02-11 17:47:05', '2026-03-05 20:54:21'),
(21, 'Neny', 'neny@gmail.com', '$2y$10$fV6ZaBt6C3Y6UXXD.EXR1.zCD7oN1abh1A3Qfjdburf9rJmRDi6Pq', 'images/default-avatar.png', 'parent', '2026-02-11 20:04:04', '2026-02-11 20:18:40');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bulletins`
--
ALTER TABLE `bulletins`
  ADD CONSTRAINT `bulletins_ibfk_1` FOREIGN KEY (`id_eleve`) REFERENCES `eleves` (`id`);

--
-- Constraints for table `classe_matieres`
--
ALTER TABLE `classe_matieres`
  ADD CONSTRAINT `classe_matieres_ibfk_1` FOREIGN KEY (`id_classe`) REFERENCES `classes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `classe_matieres_ibfk_2` FOREIGN KEY (`id_matiere`) REFERENCES `matieres` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `conversation_participants`
--
ALTER TABLE `conversation_participants`
  ADD CONSTRAINT `conversation_participants_ibfk_1` FOREIGN KEY (`conversation_id`) REFERENCES `conversations` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `eleves`
--
ALTER TABLE `eleves`
  ADD CONSTRAINT `eleves_ibfk_1` FOREIGN KEY (`id_classe`) REFERENCES `classes` (`id`);

--
-- Constraints for table `emploi_du_temps`
--
ALTER TABLE `emploi_du_temps`
  ADD CONSTRAINT `emploi_du_temps_ibfk_1` FOREIGN KEY (`id_classe`) REFERENCES `classes` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `examen_blanc`
--
ALTER TABLE `examen_blanc`
  ADD CONSTRAINT `examen_blanc_ibfk_1` FOREIGN KEY (`eleve_id`) REFERENCES `eleves` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `examen_blanc_ibfk_2` FOREIGN KEY (`classe_id`) REFERENCES `classes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `examen_blanc_ibfk_3` FOREIGN KEY (`matiere_id`) REFERENCES `matieres` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`conversation_id`) REFERENCES `conversations` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `notes`
--
ALTER TABLE `notes`
  ADD CONSTRAINT `fk_notes_eleve` FOREIGN KEY (`id_eleve`) REFERENCES `eleves` (`id`),
  ADD CONSTRAINT `fk_notes_matiere` FOREIGN KEY (`id_matiere`) REFERENCES `matieres` (`id`),
  ADD CONSTRAINT `notes_ibfk_1` FOREIGN KEY (`id_eleve`) REFERENCES `eleves` (`id`),
  ADD CONSTRAINT `notes_ibfk_2` FOREIGN KEY (`id_matiere`) REFERENCES `matieres` (`id`);

--
-- Constraints for table `paiements_assignes`
--
ALTER TABLE `paiements_assignes`
  ADD CONSTRAINT `paiements_assignes_ibfk_1` FOREIGN KEY (`type_id`) REFERENCES `types_paiements` (`id`),
  ADD CONSTRAINT `paiements_assignes_ibfk_2` FOREIGN KEY (`eleve_id`) REFERENCES `eleves` (`id`);

--
-- Constraints for table `permissions`
--
ALTER TABLE `permissions`
  ADD CONSTRAINT `permissions_ibfk_1` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateurs` (`id`);

--
-- Constraints for table `professeurs`
--
ALTER TABLE `professeurs`
  ADD CONSTRAINT `professeurs_ibfk_1` FOREIGN KEY (`matiere_id`) REFERENCES `matieres` (`id`);

--
-- Constraints for table `professeurs_classes`
--
ALTER TABLE `professeurs_classes`
  ADD CONSTRAINT `professeurs_classes_ibfk_1` FOREIGN KEY (`professeur_id`) REFERENCES `professeurs` (`id`),
  ADD CONSTRAINT `professeurs_classes_ibfk_2` FOREIGN KEY (`classe_id`) REFERENCES `classes` (`id`);

--
-- Constraints for table `remarques`
--
ALTER TABLE `remarques`
  ADD CONSTRAINT `remarques_ibfk_1` FOREIGN KEY (`id_eleve`) REFERENCES `eleves` (`id`);

--
-- Constraints for table `remarques_examen_blanc`
--
ALTER TABLE `remarques_examen_blanc`
  ADD CONSTRAINT `remarques_examen_blanc_ibfk_1` FOREIGN KEY (`id_eleve`) REFERENCES `eleves` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `staff`
--
ALTER TABLE `staff`
  ADD CONSTRAINT `staff_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
