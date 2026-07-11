-- Sauvegarde Novaskol
-- Date: 2026-05-04 14:09:05

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

INSERT INTO `classe_matieres` VALUES ('2','2','1');
INSERT INTO `classe_matieres` VALUES ('2','3','2');
INSERT INTO `classe_matieres` VALUES ('2','5','2');
INSERT INTO `classe_matieres` VALUES ('2','7','3');
INSERT INTO `classe_matieres` VALUES ('2','16','2');
INSERT INTO `classe_matieres` VALUES ('14','2','1');
INSERT INTO `classe_matieres` VALUES ('14','3','1');
INSERT INTO `classe_matieres` VALUES ('14','16','1');
INSERT INTO `classe_matieres` VALUES ('14','17','1');
INSERT INTO `classe_matieres` VALUES ('14','20','1');
INSERT INTO `classe_matieres` VALUES ('14','21','1');
INSERT INTO `classe_matieres` VALUES ('16','3','1');
INSERT INTO `classe_matieres` VALUES ('16','4','1');
INSERT INTO `classe_matieres` VALUES ('16','16','1');
INSERT INTO `classe_matieres` VALUES ('16','17','1');
INSERT INTO `classe_matieres` VALUES ('16','20','1');
INSERT INTO `classe_matieres` VALUES ('16','40','1');


DROP TABLE IF EXISTS `classes`;
CREATE TABLE `classes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `niveau` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=52 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `classes` VALUES ('1','PS',NULL);
INSERT INTO `classes` VALUES ('2','MS',NULL);
INSERT INTO `classes` VALUES ('3','GS',NULL);
INSERT INTO `classes` VALUES ('4','CP',NULL);
INSERT INTO `classes` VALUES ('5','CE1',NULL);
INSERT INTO `classes` VALUES ('6','CE2',NULL);
INSERT INTO `classes` VALUES ('7','CM1',NULL);
INSERT INTO `classes` VALUES ('8','CM2',NULL);
INSERT INTO `classes` VALUES ('9','6e',NULL);
INSERT INTO `classes` VALUES ('10','5e',NULL);
INSERT INTO `classes` VALUES ('11','4e',NULL);
INSERT INTO `classes` VALUES ('12','3e',NULL);
INSERT INTO `classes` VALUES ('13','2nde',NULL);
INSERT INTO `classes` VALUES ('14','1ère',NULL);
INSERT INTO `classes` VALUES ('16','TA','15');
INSERT INTO `classes` VALUES ('17','TL','15');
INSERT INTO `classes` VALUES ('18','TD','15');
INSERT INTO `classes` VALUES ('20','TS','15');
INSERT INTO `classes` VALUES ('21','TOSE','15');
INSERT INTO `classes` VALUES ('50','TC',NULL);
INSERT INTO `classes` VALUES ('51','Cours',NULL);


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
) ENGINE=InnoDB AUTO_INCREMENT=585 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `conversation_participants` VALUES ('483','254','admin','16','2026-02-11 20:29:11');
INSERT INTO `conversation_participants` VALUES ('484','254','enseignant','15','2026-02-11 20:29:11');
INSERT INTO `conversation_participants` VALUES ('485','255','enseignant','15','2026-02-11 20:29:20');
INSERT INTO `conversation_participants` VALUES ('486','255','parent','21','2026-02-11 20:29:20');
INSERT INTO `conversation_participants` VALUES ('487','256','enseignant','15','2026-02-11 20:30:36');
INSERT INTO `conversation_participants` VALUES ('488','256','admin','14','2026-02-11 20:30:36');
INSERT INTO `conversation_participants` VALUES ('489','257','admin','16','2026-02-11 20:33:55');
INSERT INTO `conversation_participants` VALUES ('490','257','enseignant','17','2026-02-11 20:33:55');
INSERT INTO `conversation_participants` VALUES ('491','258','admin','16','2026-02-11 22:42:20');
INSERT INTO `conversation_participants` VALUES ('492','258','admin','19','2026-02-11 22:42:20');
INSERT INTO `conversation_participants` VALUES ('493','259','admin','16','2026-02-11 22:42:21');
INSERT INTO `conversation_participants` VALUES ('494','259','admin','18','2026-02-11 22:42:21');
INSERT INTO `conversation_participants` VALUES ('495','260','admin','16','2026-02-11 22:42:25');
INSERT INTO `conversation_participants` VALUES ('496','260','parent','21','2026-02-11 22:42:25');
INSERT INTO `conversation_participants` VALUES ('497','261','staff','20','2026-02-11 22:48:38');
INSERT INTO `conversation_participants` VALUES ('498','261','admin','14','2026-02-11 22:48:38');
INSERT INTO `conversation_participants` VALUES ('499','262','staff','20','2026-02-11 22:48:39');
INSERT INTO `conversation_participants` VALUES ('500','262','enseignant','15','2026-02-11 22:48:39');
INSERT INTO `conversation_participants` VALUES ('501','263','staff','20','2026-02-11 22:48:39');
INSERT INTO `conversation_participants` VALUES ('502','263','admin','16','2026-02-11 22:48:39');
INSERT INTO `conversation_participants` VALUES ('503','264','staff','20','2026-02-11 22:48:40');
INSERT INTO `conversation_participants` VALUES ('504','264','enseignant','17','2026-02-11 22:48:40');
INSERT INTO `conversation_participants` VALUES ('505','265','staff','20','2026-02-11 22:48:40');
INSERT INTO `conversation_participants` VALUES ('506','265','admin','18','2026-02-11 22:48:40');
INSERT INTO `conversation_participants` VALUES ('507','266','staff','20','2026-02-11 22:48:41');
INSERT INTO `conversation_participants` VALUES ('508','266','admin','19','2026-02-11 22:48:41');
INSERT INTO `conversation_participants` VALUES ('509','267','staff','20','2026-02-11 22:48:41');
INSERT INTO `conversation_participants` VALUES ('510','267','parent','21','2026-02-11 22:48:41');
INSERT INTO `conversation_participants` VALUES ('511','268','enseignant','15','2026-02-11 23:01:48');
INSERT INTO `conversation_participants` VALUES ('512','268','admin','19','2026-02-11 23:01:48');
INSERT INTO `conversation_participants` VALUES ('513','269','enseignant','15','2026-02-11 23:23:36');
INSERT INTO `conversation_participants` VALUES ('514','269','admin','18','2026-02-11 23:23:36');
INSERT INTO `conversation_participants` VALUES ('515','270','admin','19','2026-02-12 01:09:30');
INSERT INTO `conversation_participants` VALUES ('516','271','admin','14','2026-02-12 01:17:50');
INSERT INTO `conversation_participants` VALUES ('517','271','enseignant','15','2026-02-12 01:17:50');
INSERT INTO `conversation_participants` VALUES ('518','271','admin','16','2026-02-12 01:17:50');
INSERT INTO `conversation_participants` VALUES ('519','271','enseignant','17','2026-02-12 01:17:50');
INSERT INTO `conversation_participants` VALUES ('520','271','admin','18','2026-02-12 01:17:50');
INSERT INTO `conversation_participants` VALUES ('521','271','admin','19','2026-02-12 01:17:50');
INSERT INTO `conversation_participants` VALUES ('522','271','staff','20','2026-02-12 01:17:50');
INSERT INTO `conversation_participants` VALUES ('523','271','parent','21','2026-02-12 01:17:50');
INSERT INTO `conversation_participants` VALUES ('524','272','admin','16','2026-02-12 01:19:40');
INSERT INTO `conversation_participants` VALUES ('525','273','admin','19','2026-02-12 01:51:48');
INSERT INTO `conversation_participants` VALUES ('530','275','admin','16','2026-02-12 07:34:31');
INSERT INTO `conversation_participants` VALUES ('531','275','admin','14','2026-02-12 07:34:31');
INSERT INTO `conversation_participants` VALUES ('532','276','admin','19','2026-02-12 13:53:25');
INSERT INTO `conversation_participants` VALUES ('533','276','enseignant','17','2026-02-12 13:53:25');
INSERT INTO `conversation_participants` VALUES ('534','277','admin','19','2026-02-12 13:53:26');
INSERT INTO `conversation_participants` VALUES ('535','277','admin','18','2026-02-12 13:53:26');
INSERT INTO `conversation_participants` VALUES ('536','278','admin','19','2026-02-12 13:53:27');
INSERT INTO `conversation_participants` VALUES ('537','278','parent','21','2026-02-12 13:53:27');
INSERT INTO `conversation_participants` VALUES ('540','279','admin','14','2026-02-12 18:06:43');
INSERT INTO `conversation_participants` VALUES ('541','279','enseignant','17','2026-02-12 18:06:43');
INSERT INTO `conversation_participants` VALUES ('542','280','admin','14','2026-02-12 20:00:54');
INSERT INTO `conversation_participants` VALUES ('543','280','admin','18','2026-02-12 20:00:54');
INSERT INTO `conversation_participants` VALUES ('544','281','admin','14','2026-02-12 20:00:57');
INSERT INTO `conversation_participants` VALUES ('545','281','admin','19','2026-02-12 20:00:57');
INSERT INTO `conversation_participants` VALUES ('546','282','admin','14','2026-02-12 20:02:09');
INSERT INTO `conversation_participants` VALUES ('547','282','parent','21','2026-02-12 20:02:09');
INSERT INTO `conversation_participants` VALUES ('548','283','admin','14','2026-02-12 21:46:15');
INSERT INTO `conversation_participants` VALUES ('549','283','admin','16','2026-02-12 21:46:15');
INSERT INTO `conversation_participants` VALUES ('550','283','admin','18','2026-02-12 21:46:15');
INSERT INTO `conversation_participants` VALUES ('551','283','admin','19','2026-02-12 21:46:15');
INSERT INTO `conversation_participants` VALUES ('552','284','admin','0','2026-02-13 07:10:09');
INSERT INTO `conversation_participants` VALUES ('553','284','admin','14','2026-02-13 07:10:09');
INSERT INTO `conversation_participants` VALUES ('554','285','admin','0','2026-02-13 07:10:11');
INSERT INTO `conversation_participants` VALUES ('555','285','enseignant','15','2026-02-13 07:10:11');
INSERT INTO `conversation_participants` VALUES ('556','286','admin','0','2026-02-13 07:10:11');
INSERT INTO `conversation_participants` VALUES ('557','286','admin','16','2026-02-13 07:10:11');
INSERT INTO `conversation_participants` VALUES ('558','287','admin','0','2026-02-13 07:10:12');
INSERT INTO `conversation_participants` VALUES ('559','287','enseignant','17','2026-02-13 07:10:12');
INSERT INTO `conversation_participants` VALUES ('560','288','admin','0','2026-02-13 07:10:13');
INSERT INTO `conversation_participants` VALUES ('561','288','admin','18','2026-02-13 07:10:13');
INSERT INTO `conversation_participants` VALUES ('562','289','admin','0','2026-02-13 07:10:13');
INSERT INTO `conversation_participants` VALUES ('563','289','admin','19','2026-02-13 07:10:13');
INSERT INTO `conversation_participants` VALUES ('564','290','admin','0','2026-02-13 07:10:15');
INSERT INTO `conversation_participants` VALUES ('565','290','staff','20','2026-02-13 07:10:15');
INSERT INTO `conversation_participants` VALUES ('566','291','admin','0','2026-02-13 07:10:16');
INSERT INTO `conversation_participants` VALUES ('567','291','parent','21','2026-02-13 07:10:16');
INSERT INTO `conversation_participants` VALUES ('572','292','admin','19','2026-02-13 22:14:23');
INSERT INTO `conversation_participants` VALUES ('573','292','admin','16','2026-02-13 22:14:23');
INSERT INTO `conversation_participants` VALUES ('574','292','admin','18','2026-02-13 22:14:23');
INSERT INTO `conversation_participants` VALUES ('575','292','admin','14','2026-02-13 22:14:23');
INSERT INTO `conversation_participants` VALUES ('576','274','admin','16','2026-02-13 22:14:39');
INSERT INTO `conversation_participants` VALUES ('577','274','admin','19','2026-02-13 22:14:39');
INSERT INTO `conversation_participants` VALUES ('578','274','admin','14','2026-02-13 22:14:39');
INSERT INTO `conversation_participants` VALUES ('579','293','enseignant','17','2026-02-14 12:40:03');
INSERT INTO `conversation_participants` VALUES ('580','293','admin','18','2026-02-14 12:40:03');
INSERT INTO `conversation_participants` VALUES ('581','294','enseignant','17','2026-02-14 12:40:04');
INSERT INTO `conversation_participants` VALUES ('582','294','parent','21','2026-02-14 12:40:04');
INSERT INTO `conversation_participants` VALUES ('583','295','enseignant','17','2026-02-14 12:42:15');
INSERT INTO `conversation_participants` VALUES ('584','295','enseignant','15','2026-02-14 12:42:15');


DROP TABLE IF EXISTS `conversations`;
CREATE TABLE `conversations` (
  `id` int NOT NULL AUTO_INCREMENT,
  `type` enum('private','group') NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `creator_id` int NOT NULL DEFAULT '0',
  `avatar` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=296 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `conversations` VALUES ('254','private',NULL,'0',NULL,'2026-02-11 20:29:11','2026-05-04 12:20:04');
INSERT INTO `conversations` VALUES ('255','private',NULL,'0',NULL,'2026-02-11 20:29:20','2026-02-11 20:29:20');
INSERT INTO `conversations` VALUES ('256','private',NULL,'0',NULL,'2026-02-11 20:30:36','2026-03-08 21:14:06');
INSERT INTO `conversations` VALUES ('257','private',NULL,'0',NULL,'2026-02-11 20:33:55','2026-02-14 15:17:00');
INSERT INTO `conversations` VALUES ('258','private',NULL,'0',NULL,'2026-02-11 22:42:20','2026-05-04 12:29:04');
INSERT INTO `conversations` VALUES ('259','private',NULL,'0',NULL,'2026-02-11 22:42:21','2026-02-11 22:42:21');
INSERT INTO `conversations` VALUES ('260','private',NULL,'0',NULL,'2026-02-11 22:42:25','2026-02-11 22:42:25');
INSERT INTO `conversations` VALUES ('261','private',NULL,'0',NULL,'2026-02-11 22:48:38','2026-02-17 07:04:49');
INSERT INTO `conversations` VALUES ('262','private',NULL,'0',NULL,'2026-02-11 22:48:39','2026-02-11 22:48:39');
INSERT INTO `conversations` VALUES ('263','private',NULL,'0',NULL,'2026-02-11 22:48:39','2026-02-17 07:03:20');
INSERT INTO `conversations` VALUES ('264','private',NULL,'0',NULL,'2026-02-11 22:48:40','2026-02-11 22:48:40');
INSERT INTO `conversations` VALUES ('265','private',NULL,'0',NULL,'2026-02-11 22:48:40','2026-02-11 22:48:40');
INSERT INTO `conversations` VALUES ('266','private',NULL,'0',NULL,'2026-02-11 22:48:41','2026-02-14 00:54:47');
INSERT INTO `conversations` VALUES ('267','private',NULL,'0',NULL,'2026-02-11 22:48:41','2026-02-11 22:48:41');
INSERT INTO `conversations` VALUES ('268','private',NULL,'0',NULL,'2026-02-11 23:01:48','2026-02-11 23:01:48');
INSERT INTO `conversations` VALUES ('269','private',NULL,'0',NULL,'2026-02-11 23:23:36','2026-02-11 23:23:36');
INSERT INTO `conversations` VALUES ('270','group','Général','16',NULL,'2026-02-12 01:09:30','2026-02-12 21:43:23');
INSERT INTO `conversations` VALUES ('271','group','Général - École','16',NULL,'2026-02-12 01:17:50','2026-05-04 12:11:18');
INSERT INTO `conversations` VALUES ('272','group','dert','16',NULL,'2026-02-12 01:19:40','2026-02-12 21:43:23');
INSERT INTO `conversations` VALUES ('273','group','Math','16',NULL,'2026-02-12 01:51:48','2026-02-12 21:43:23');
INSERT INTO `conversations` VALUES ('274','group','Team','16','1771010079_698f781fbd57d.jpg','2026-02-12 01:59:14','2026-05-04 12:11:08');
INSERT INTO `conversations` VALUES ('275','private',NULL,'0',NULL,'2026-02-12 07:34:31','2026-02-28 15:33:46');
INSERT INTO `conversations` VALUES ('276','private',NULL,'0',NULL,'2026-02-12 13:53:25','2026-02-26 16:20:25');
INSERT INTO `conversations` VALUES ('277','private',NULL,'0',NULL,'2026-02-12 13:53:25','2026-02-13 23:40:26');
INSERT INTO `conversations` VALUES ('278','private',NULL,'0',NULL,'2026-02-12 13:53:27','2026-02-12 13:53:27');
INSERT INTO `conversations` VALUES ('279','private',NULL,'0',NULL,'2026-02-12 18:06:43','2026-02-26 20:38:38');
INSERT INTO `conversations` VALUES ('280','private',NULL,'0',NULL,'2026-02-12 20:00:54','2026-02-12 20:00:54');
INSERT INTO `conversations` VALUES ('281','private',NULL,'0',NULL,'2026-02-12 20:00:57','2026-02-28 00:23:46');
INSERT INTO `conversations` VALUES ('282','private',NULL,'0',NULL,'2026-02-12 20:02:09','2026-02-12 20:02:09');
INSERT INTO `conversations` VALUES ('283','group','Administrateur','14',NULL,'2026-02-12 21:46:15','2026-05-04 12:28:37');
INSERT INTO `conversations` VALUES ('284','private',NULL,'0',NULL,'2026-02-13 07:10:09','2026-02-13 07:10:09');
INSERT INTO `conversations` VALUES ('285','private',NULL,'0',NULL,'2026-02-13 07:10:11','2026-02-13 07:10:11');
INSERT INTO `conversations` VALUES ('286','private',NULL,'0',NULL,'2026-02-13 07:10:11','2026-02-13 07:10:11');
INSERT INTO `conversations` VALUES ('287','private',NULL,'0',NULL,'2026-02-13 07:10:12','2026-02-13 07:10:12');
INSERT INTO `conversations` VALUES ('288','private',NULL,'0',NULL,'2026-02-13 07:10:13','2026-02-13 07:10:13');
INSERT INTO `conversations` VALUES ('289','private',NULL,'0',NULL,'2026-02-13 07:10:13','2026-02-13 07:10:13');
INSERT INTO `conversations` VALUES ('290','private',NULL,'0',NULL,'2026-02-13 07:10:15','2026-02-13 07:10:15');
INSERT INTO `conversations` VALUES ('291','private',NULL,'0',NULL,'2026-02-13 07:10:16','2026-02-13 07:10:16');
INSERT INTO `conversations` VALUES ('292','group','RH','19','1771010063_698f780f2a2c7.png','2026-02-13 20:29:43','2026-02-13 22:14:23');
INSERT INTO `conversations` VALUES ('293','private',NULL,'0',NULL,'2026-02-14 12:40:03','2026-02-14 12:40:03');
INSERT INTO `conversations` VALUES ('294','private',NULL,'0',NULL,'2026-02-14 12:40:04','2026-02-14 12:40:04');
INSERT INTO `conversations` VALUES ('295','private',NULL,'0',NULL,'2026-02-14 12:42:15','2026-02-14 14:38:22');


DROP TABLE IF EXISTS `departements`;
CREATE TABLE `departements` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



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

INSERT INTO `depenses` VALUES ('32','0','13','staff','Février','2025-2026','45000.00','Paiement salaire','Espèces','complet','Salaire','2026-02-07 16:15:22','RANDRIAMBOLANIAINA Avotra Fenosoa');
INSERT INTO `depenses` VALUES ('33','0','1','professeur','Janvier','2025-2026','250000.00','Paiement salaire','Espèces','complet','Salaire','2026-02-07 19:29:41','RANDRIAMIFALY Tojo Nambinina');
INSERT INTO `depenses` VALUES ('34','0','4','professeur','Janvier','2025-2026','300000.00','Paiement salaire','Espèces','complet','Salaire','2026-02-27 23:51:10','PERSEVERANCE Pain');
INSERT INTO `depenses` VALUES ('35','0','2','professeur','Janvier','2025-2026','250000.00','Paiement salaire','Espèces','complet','Salaire','2026-02-28 08:19:20','RANDRIAMIFALY Heriniaina');


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

INSERT INTO `dossiers` VALUES ('19','2025-2026','Février','enseignant',NULL,'KOTO Kanto','Description simpl de la date d\'intégration','MY ENGLISH.docx','2026-02-17 18:44:39');
INSERT INTO `dossiers` VALUES ('20','2025-2026','Février','enseignant',NULL,'KOTO Kanto','Description simpl de la date d\'intégration','MY ENGLISH.docx','2026-02-17 18:46:45');
INSERT INTO `dossiers` VALUES ('22','2025-2026','Février','eleve',NULL,'Anio HENRY','Dossier rentrée scolaire.','La meilleure manière de faire de l.docx','2026-02-26 16:29:22');
INSERT INTO `dossiers` VALUES ('23','2025-2026','Février','eleve',NULL,'Anio HENRY','Dossier rentrée scolaire.','La meilleure manière de faire de l.docx','2026-02-26 16:30:52');
INSERT INTO `dossiers` VALUES ('24','2025-2026','Mars','enseignant',NULL,'Mathieu','Dossier personnel','ertt.png','2026-02-26 16:41:21');
INSERT INTO `dossiers` VALUES ('25','2025-2026','Février','eleve',NULL,'Fitia','Farnay','DEADLINE FINITION.txt','2026-02-26 16:43:04');
INSERT INTO `dossiers` VALUES ('26','2026-2027','Mars','eleve',NULL,'Faly ANDRIA','Carte capturée au GAB','La meilleure manière de faire de l.docx','2026-02-27 15:15:33');
INSERT INTO `dossiers` VALUES ('27','2025-2026','Janvier','eleve',NULL,'Koto Nandra','Fandoavambola','ertt.png','2026-02-27 20:23:14');


DROP TABLE IF EXISTS `ecole`;
CREATE TABLE `ecole` (
  `id` int NOT NULL,
  `nom` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `logo` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `ecole` VALUES ('1','Lycée Privée Novaskol','logo_1770356369.png');
INSERT INTO `ecole` VALUES ('-2147483495','Novaskol.mg','logo_1770356369.png');
INSERT INTO `ecole` VALUES ('-2147483495','Novaskol.mg','logo_1770356369.png');


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

INSERT INTO `eleves` VALUES ('237','20250001','RHON','Mano','2016-05-06','Ambatondrazaka','2345678912','LOT HASH','ACT32','Andraisoro','Antananarivo','Lycée Avaratra','6','Uploads/default.jpg','2025-2026','ANDRIANE Elidia','ELIANE Ronjo','1','G','nouveau','1');
INSERT INTO `eleves` VALUES ('238','20250002','RHAM','Elia','2018-05-07','Ambatondrazaka','2345678913','LOT HASH','ACT33','Andraisoro','Antananarivo','Lycée Avaratra','6','Uploads/default.jpg','2025-2026','ANDRIANE Elidi','ELIANE Ron','0','F','passant','0');
INSERT INTO `eleves` VALUES ('239','20250003','DENJI','Lita','2020-05-08','Ambatondrazaka','2345678914','LOT HASH','ACT34','Andraisoro','Antananarivo','Lycée Avaratra','6','Uploads/default.jpg','2025-2026','ANDRIANE Eliia','ELIAE Ronjo','1','G','redoublant','1');
INSERT INTO `eleves` VALUES ('240','20250004','DIARA','Toavina','2019-05-09','Ambatondrazaka','2345678915','LOT HASH','ACT35','Andraisoro','Antananarivo','Lycée Avaratra','6','Uploads/default.jpg','2025-2026','ANDRIANE lidia','ELIANE Rnjo','0','F','nouveau','0');
INSERT INTO `eleves` VALUES ('241','20250005','ARIAME','Victoria','2017-05-10','Ambatondrazaka','2345678916','LOT HASH','ACT36','Andraisoro','Antananarivo','Lycée Avaratra','6','Uploads/default.jpg','2025-2026','ANDRINE Elidia','ELANE Ronjo','1','G','passant','1');
INSERT INTO `eleves` VALUES ('242','20250006','DROUNE','Ariane','2018-05-11','Ambatondrazaka','2345678917','LOT HASH','ACT37','Andraisoro','Antananarivo','Lycée Avaratra','6','Uploads/default.jpg','2025-2026','NDRIANE Elidia','LIANE Ronjo','0','F','redoublant','0');
INSERT INTO `eleves` VALUES ('243','20250007','DJIANDE','Roundro','2019-05-12','Ambatondrazaka','2345678918','LOT HASH','ACT38','Andraisoro','Antananarivo','Lycée Avaratra','6','Uploads/default.jpg','2025-2026','ANDRIANE Elid','ELIE Ronjo','1','G','nouveau','1');
INSERT INTO `eleves` VALUES ('244','20250008','DJEDE','Eldia','2016-05-13','Ambatondrazaka','2345678919','LOT HASH','ACT39','Andraisoro','Antananarivo','Lycée Avaratra','6','Uploads/default.jpg','2025-2026','ANDRIANE idia','ELIAN Ronjo','0','F','passant','0');
INSERT INTO `eleves` VALUES ('245','20250009','DJOUDE','Douane','2016-05-14','Ambatondrazaka','2345678920','LOT HASH','ACT40','Andraisoro','Antananarivo','Lycée Avaratra','6','Uploads/default.jpg','2025-2026','ANIANE Elidia','ELNE Ronjo','1','G','redoublant','1');
INSERT INTO `eleves` VALUES ('246','20250010','RHON','Fils','2015-05-06','Ambatondrazaka','1478523698','LOT soa','ACT78','Anjanahary','Antananarivo','Lycée Andrombe','8','Uploads/default.jpg','2025-2026','ELIE randira','RONDRO andry','1','G','nouveau','1');
INSERT INTO `eleves` VALUES ('247','20250011','RHAM','Faly','2015-05-07','Ambatondrazaka','1478523699','LOT soa','ACT78','Anjanahary','Antananarivo','Lycée Andrombe','8','Uploads/default.jpg','2025-2026','ELIE randira','RONDRO andry','0','F','passant','0');
INSERT INTO `eleves` VALUES ('248','20250012','DENJI','Fondro','2015-05-08','Ambatondrazaka','1478523700','LOT soa','ACT78','Anjanahary','Antananarivo','Lycée Andrombe','8','Uploads/default.jpg','2025-2026','ELIE randira','RONDRO andry','1','G','redoublant','1');
INSERT INTO `eleves` VALUES ('249','20250013','DIARA','Fadnry','2015-05-09','Ambatondrazaka','1478523701','LOT soa','ACT78','Anjanahary','Antananarivo','Lycée Andrombe','8','Uploads/default.jpg','2025-2026','ELIE randira','RONDRO andry','0','F','nouveau','0');
INSERT INTO `eleves` VALUES ('250','20250014','ARIAME','Doune','2015-05-10','Ambatondrazaka','1478523702','LOT soa','ACT78','Anjanahary','Antananarivo','Lycée Andrombe','8','Uploads/default.jpg','2025-2026','ELIE randira','RONDRO andry','1','G','passant','1');
INSERT INTO `eleves` VALUES ('251','20250015','DROUNE','Andry','2015-05-11','Ambatondrazaka','1478523703','LOT soa','ACT78','Anjanahary','Antananarivo','Lycée Andrombe','8','Uploads/default.jpg','2025-2026','ELIE randira','RONDRO andry','0','F','redoublant','0');
INSERT INTO `eleves` VALUES ('252','20250016','DJIANDE','Douane','2015-05-12','Ambatondrazaka','1478523704','LOT soa','ACT78','Anjanahary','Antananarivo','Lycée Andrombe','8','Uploads/default.jpg','2025-2026','ELIE randira','RONDRO andry','1','G','nouveau','1');
INSERT INTO `eleves` VALUES ('253','20250017','DJEDE','Indry','2015-05-13','Ambatondrazaka','1478523705','LOT soa','ACT78','Anjanahary','Antananarivo','Lycée Andrombe','8','Uploads/default.jpg','2025-2026','ELIE randira','RONDRO andry','0','F','passant','0');
INSERT INTO `eleves` VALUES ('254','20250018','DJOUDE','Adnria','2015-05-14','Ambatondrazaka','1478523706','LOT soa','ACT78','Anjanahary','Antananarivo','Lycée Andrombe','8','Uploads/default.jpg','2025-2026','ELIE randira','RONDRO andry','1','G','redoublant','1');
INSERT INTO `eleves` VALUES ('255','20250019','RHON','Diane','2015-05-06','Ambatondrazaka','1478523698','LOT soa','ACT78','Anjanahary','Antananarivo','Lycée Andrombe','9','Uploads/default.jpg','2025-2026','ELIE randira','RONDRO andry','1','G','nouveau','1');
INSERT INTO `eleves` VALUES ('256','20250020','RHAM','Andry','2015-05-07','Ambatondrazaka','1478523699','LOT soa','ACT78','Anjanahary','Antananarivo','Lycée Andrombe','9','Uploads/default.jpg','2025-2026','ELIE randira','RONDRO andry','0','F','passant','0');
INSERT INTO `eleves` VALUES ('257','20250021','DENJI','Rondro','2015-05-08','Ambatondrazaka','1478523700','LOT soa','ACT78','Anjanahary','Antananarivo','Lycée Andrombe','9','Uploads/default.jpg','2025-2026','ELIE randira','RONDRO andry','1','G','redoublant','1');
INSERT INTO `eleves` VALUES ('258','20250022','DIARA','Drouane','2015-05-09','Ambatondrazaka','1478523701','LOT soa','ACT78','Anjanahary','Antananarivo','Lycée Andrombe','9','Uploads/default.jpg','2025-2026','ELIE randira','RONDRO andry','0','F','nouveau','0');
INSERT INTO `eleves` VALUES ('259','20250023','ARIAME','Elia','2015-05-10','Ambatondrazaka','1478523702','LOT soa','ACT78','Anjanahary','Antananarivo','Lycée Andrombe','9','Uploads/default.jpg','2025-2026','ELIE randira','RONDRO andry','1','G','passant','1');
INSERT INTO `eleves` VALUES ('260','20250024','DROUNE','Driane','2015-05-11','Ambatondrazaka','1478523703','LOT soa','ACT78','Anjanahary','Antananarivo','Lycée Andrombe','9','Uploads/default.jpg','2025-2026','ELIE randira','RONDRO andry','0','F','redoublant','0');
INSERT INTO `eleves` VALUES ('261','20250025','DJIANDE','Louane','2015-05-12','Ambatondrazaka','1478523704','LOT soa','ACT78','Anjanahary','Antananarivo','Lycée Andrombe','9','Uploads/default.jpg','2025-2026','ELIE randira','RONDRO andry','1','G','nouveau','1');
INSERT INTO `eleves` VALUES ('262','20250026','DJEDE','Sane','2015-05-13','Ambatondrazaka','1478523705','LOT soa','ACT78','Anjanahary','Antananarivo','Lycée Andrombe','9','Uploads/default.jpg','2025-2026','ELIE randira','RONDRO andry','0','F','passant','0');
INSERT INTO `eleves` VALUES ('263','20250027','DJOUDE','Fiadanana','2015-05-14','Ambatondrazaka','1478523706','LOT soa','ACT78','Anjanahary','Antananarivo','Lycée Andrombe','9','Uploads/default.jpg','2025-2026','ELIE randira','RONDRO andry','1','G','redoublant','1');
INSERT INTO `eleves` VALUES ('264','20250028','RHON','Toa','2015-05-06','Ambatondrazaka','1478523698','LOT soa','ACT78','Anjanahary','Antananarivo','Lycée Andrombe','11','Uploads/default.jpg','2025-2026','ELIE randira','RONDRO andry','1','G','nouveau','1');
INSERT INTO `eleves` VALUES ('265','20250029','RHAM','Andry','2015-05-07','Ambatondrazaka','1478523699','LOT soa','ACT78','Anjanahary','Antananarivo','Lycée Andrombe','11','Uploads/default.jpg','2025-2026','ELIE randira','RONDRO andry','0','F','passant','0');
INSERT INTO `eleves` VALUES ('266','20250030','DENJI','Rija','2015-05-08','Ambatondrazaka','1478523700','LOT soa','ACT78','Anjanahary','Antananarivo','Lycée Andrombe','11','Uploads/default.jpg','2025-2026','ELIE randira','RONDRO andry','1','G','redoublant','1');
INSERT INTO `eleves` VALUES ('267','20250031','DIARA','Adnroau','2015-05-09','Ambatondrazaka','1478523701','LOT soa','ACT78','Anjanahary','Antananarivo','Lycée Andrombe','11','Uploads/default.jpg','2025-2026','ELIE randira','RONDRO andry','0','F','nouveau','0');
INSERT INTO `eleves` VALUES ('268','20250032','ARIAME','Doua','2015-05-10','Ambatondrazaka','1478523702','LOT soa','ACT78','Anjanahary','Antananarivo','Lycée Andrombe','11','Uploads/default.jpg','2025-2026','ELIE randira','RONDRO andry','1','G','passant','1');
INSERT INTO `eleves` VALUES ('269','20250033','DROUNE','Riane','2015-05-11','Ambatondrazaka','1478523703','LOT soa','ACT78','Anjanahary','Antananarivo','Lycée Andrombe','11','Uploads/default.jpg','2025-2026','ELIE randira','RONDRO andry','0','F','redoublant','0');
INSERT INTO `eleves` VALUES ('270','20250034','DJIANDE','Ouane','2015-05-12','Ambatondrazaka','1478523704','LOT soa','ACT78','Anjanahary','Antananarivo','Lycée Andrombe','11','Uploads/default.jpg','2025-2026','ELIE randira','RONDRO andry','1','G','nouveau','1');
INSERT INTO `eleves` VALUES ('271','20250035','DJEDE','Diane','2015-05-13','Ambatondrazaka','1478523705','LOT soa','ACT78','Anjanahary','Antananarivo','Lycée Andrombe','11','Uploads/default.jpg','2025-2026','ELIE randira','RONDRO andry','0','F','passant','0');
INSERT INTO `eleves` VALUES ('272','20250036','DJOUDE','Rouane','2015-05-14','Ambatondrazaka','1478523706','LOT soa','ACT78','Anjanahary','Antananarivo','Lycée Andrombe','11','Uploads/default.jpg','2025-2026','ELIE randira','RONDRO andry','1','G','redoublant','1');
INSERT INTO `eleves` VALUES ('273','20250037','RHON','Fitia','2015-05-06','Ambatondrazaka','1478523698','LOT soa','ACT78','Anjanahary','Antananarivo','Lycée Andrombe','13','Uploads/default.jpg','2025-2026','ELIE randira','RONDRO andry','1','G','nouveau','1');
INSERT INTO `eleves` VALUES ('274','20250038','RHAM','Valy','2015-05-07','Ambatondrazaka','1478523699','LOT soa','ACT78','Anjanahary','Antananarivo','Lycée Andrombe','13','Uploads/default.jpg','2025-2026','ELIE randira','RONDRO andry','0','F','passant','0');
INSERT INTO `eleves` VALUES ('275','20250039','DENJI','Avotra','2015-05-08','Ambatondrazaka','1478523700','LOT soa','ACT78','Anjanahary','Antananarivo','Lycée Andrombe','13','Uploads/default.jpg','2025-2026','ELIE randira','RONDRO andry','1','G','redoublant','1');
INSERT INTO `eleves` VALUES ('276','20250040','DIARA','Didy','2015-05-09','Ambatondrazaka','1478523701','LOT soa','ACT78','Anjanahary','Antananarivo','Lycée Andrombe','13','Uploads/default.jpg','2025-2026','ELIE randira','RONDRO andry','0','F','nouveau','0');
INSERT INTO `eleves` VALUES ('277','20250041','ARIAME','Doua','2015-05-10','Ambatondrazaka','1478523702','LOT soa','ACT78','Anjanahary','Antananarivo','Lycée Andrombe','13','Uploads/default.jpg','2025-2026','ELIE randira','RONDRO andry','1','G','passant','1');
INSERT INTO `eleves` VALUES ('278','20250042','DROUNE','Dinah','2015-05-11','Ambatondrazaka','1478523703','LOT soa','ACT78','Anjanahary','Antananarivo','Lycée Andrombe','13','Uploads/default.jpg','2025-2026','ELIE randira','RONDRO andry','0','F','redoublant','0');
INSERT INTO `eleves` VALUES ('279','20250043','DJIANDE','Andry','2015-05-12','Ambatondrazaka','1478523704','LOT soa','ACT78','Anjanahary','Antananarivo','Lycée Andrombe','13','Uploads/default.jpg','2025-2026','ELIE randira','RONDRO andry','1','G','nouveau','1');
INSERT INTO `eleves` VALUES ('280','20250044','DJEDE','Rouane','2015-05-13','Ambatondrazaka','1478523705','LOT soa','ACT78','Anjanahary','Antananarivo','Lycée Andrombe','13','Uploads/default.jpg','2025-2026','ELIE randira','RONDRO andry','0','F','passant','0');
INSERT INTO `eleves` VALUES ('281','20250045','DJOUDE','Ariane','2015-05-14','Ambatondrazaka','1478523706','LOT soa','ACT78','Anjanahary','Antananarivo','Lycée Andrombe','13','Uploads/default.jpg','2025-2026','ELIE randira','RONDRO andry','1','G','redoublant','1');
INSERT INTO `eleves` VALUES ('282','20250046','OLANA','Misy','2010-05-15','Antananarivo','0123456789','Lot 123','ACT123','Ambohijanahary','Antananarivo','Lycée Moderne','14','Uploads/default.jpg','2025-2026','Paul Dupont','Marie Dupont','1','G','nouveau','0');
INSERT INTO `eleves` VALUES ('283','20250047','OLANA','Fisy','2010-05-16','Antananarivo','0123456790','Lot 124','ACT124','Ambohijanahary','Antananarivo','Lycée Moderne','14','Uploads/default.jpg','2025-2026','Paul Dupont','Marie Dupont','1','F','nouveau','0');
INSERT INTO `eleves` VALUES ('284','20250048','OLANA','Firy','2010-05-17','Antananarivo','0123456791','Lot 125','ACT125','Ambohijanahary','Antananarivo','Lycée Moderne','14','Uploads/default.jpg','2025-2026','Paul Dupont','Marie Dupont','1','G','nouveau','0');
INSERT INTO `eleves` VALUES ('285','20250049','OLANA','Ampio','2010-05-18','Antananarivo','0123456792','Lot 126','ACT126','Ambohijanahary','Antananarivo','Lycée Moderne','14','Uploads/default.jpg','2025-2026','Paul Dupont','Marie Dupont','1','F','nouveau','0');
INSERT INTO `eleves` VALUES ('286','20250050','OLANA','Aho','2010-05-19','Antananarivo','0123456793','Lot 127','ACT127','Ambohijanahary','Antananarivo','Lycée Moderne','14','Uploads/default.jpg','2025-2026','Paul Dupont','Marie Dupont','1','F','nouveau','0');
INSERT INTO `eleves` VALUES ('287','20250051','OLANA','Tompo','2010-05-20','Antananarivo','0123456794','Lot 128','ACT128','Ambohijanahary','Antananarivo','Lycée Moderne','14','Uploads/default.jpg','2025-2026','Paul Dupont','Marie Dupont','1','G','nouveau','0');
INSERT INTO `eleves` VALUES ('288','20250052','OLANA','Matoky','2010-05-21','Antananarivo','0123456795','Lot 129','ACT129','Ambohijanahary','Antananarivo','Lycée Moderne','14','Uploads/default.jpg','2025-2026','Paul Dupont','Marie Dupont','1','F','nouveau','0');
INSERT INTO `eleves` VALUES ('289','20250053','OLANA','Anao','2010-05-22','Antananarivo','0123456796','Lot 130','ACT130','Ambohijanahary','Antananarivo','Lycée Moderne','14','Uploads/default.jpg','2025-2026','Paul Dupont','Marie Dupont','1','G','nouveau','0');
INSERT INTO `eleves` VALUES ('290','20250054','OLANA','Aho','2010-05-23','Antananarivo','0123456797','Lot 131','ACT131','Ambohijanahary','Antananarivo','Lycée Moderne','14','Uploads/default.jpg','2025-2026','Paul Dupont','Marie Dupont','0','F','nouveau','0');
INSERT INTO `eleves` VALUES ('295','20250055','ANIO','Tody','2019-05-15','Antananarivo','1478523698','LOT 456','act 356','Albohidahy','Antananarivo','Lycée Ampitatafika','2','Uploads/1771477461_1753116347_2.jpg','2025-2026','HERY naso','Ando RAVOJAS','1','G','nouveau','1');
INSERT INTO `eleves` VALUES ('296','20250056','ANIO','Fyh','2019-05-16','Antananarivo','1478523699','LOT 457','act 357','Albohidahy','Antananarivo','Lycée Ampitatafika','2','Uploads/1772259083_1753116419_6.jpg','2025-2026','HERY nas','Ando RAVOJAE','0','F','passant','0');
INSERT INTO `eleves` VALUES ('297','20250057','ANIO','Fano','2019-05-17','Antananarivo','1478523700','LOT 458','act 358','Albohidahy','Antananarivo','Lycée Ampitatafika','2','Uploads/default.jpg','2025-2026','HERY nasol','Ando RAVOJAT','1','G','nouveau','1');
INSERT INTO `eleves` VALUES ('298','20250058','ANIO','Fonja','2019-05-18','Antananarivo','1478523701','LOT 459','act 359','Albohidahy','Antananarivo','Lycée Ampitatafika','2','Uploads/default.jpg','2025-2026','HERY nasoloa','Ando RAVOJAF','0','F','passant','1');
INSERT INTO `eleves` VALUES ('299','20250059','ANIO','Fery','2019-05-19','Antananarivo','1478523702','LOT 460','act 360','Albohidahy','Antananarivo','Lycée Ampitatafika','2','Uploads/default.jpg','2025-2026','HERY nasoloe','Ando RAVOJAG','1','G','nouveau','0');
INSERT INTO `eleves` VALUES ('300','20250060','ANIO','Foniah','2019-05-20','Antananarivo','1478523703','LOT 461','act 361','Albohidahy','Antananarivo','Lycée Ampitatafika','2','Uploads/1771527696_1753117388_7.jpg','2025-2026','HERY nasolor','Ando RAVOJAH','0','F','redoublant','1');
INSERT INTO `eleves` VALUES ('301','20250061','ANIO','Fanih','2019-05-21','Antananarivo','1478523704','LOT 462','act 362','Albohidahy','Antananarivo','Lycée Ampitatafika','2','Uploads/1771479579_1753116482_9.jpg','2025-2026','HERY nasolort','Ando RAVOJAL','1','G','nouveau','1');
INSERT INTO `eleves` VALUES ('302','20250062','ANIO','Faniahy','2019-05-22','Antananarivo','1478523705','LOT 463','act 363','Albohidahy','Antananarivo','Lycée Ampitatafika','2','Uploads/1761980145_images.png','2025-2026','HERY nasoloe','Ando RAVOJAO','1','F','redoublant','0');
INSERT INTO `eleves` VALUES ('303','20250063','ANIO','Faniho','2019-05-23','Antananarivo','1478523706','LOT 464','act 364','Albohidahy','Antananarivo','Lycée Ampitatafika','2','Uploads/1771477398_1753116398_5.jpg','2025-2026','HERY rrna','Ando RAVOJA','1','G','redoublant','1');
INSERT INTO `eleves` VALUES ('304','20260001','Dupont','Jean','2010-05-15','Antananarivo','0123456789','Lot 123','ACT123','Ambohijanahary','Antananarivo','Lycée Moderne','1','Uploads/default.jpg','2026-2027','Paul Dupont','Marie Dupont','1','G','nouveau','0');
INSERT INTO `eleves` VALUES ('305','20250064','ANIO','Tody','2019-05-15','Antananarivo','1478523698','LOT 456','act 356','Albohidahy','Antananarivo','Lycée Ampitatafika','16','Uploads/default.jpg','2025-2026','HERY naso','Ando RAVOJAS','1','G','nouveau','1');
INSERT INTO `eleves` VALUES ('306','20250065','ANIO','Fyh','2019-05-16','Antananarivo','1478523699','LOT 457','act 357','Albohidahy','Antananarivo','Lycée Ampitatafika','16','Uploads/default.jpg','2025-2026','HERY nas','Ando RAVOJAE','0','F','passant','0');
INSERT INTO `eleves` VALUES ('307','20250066','ANIO','Fano','2019-05-17','Antananarivo','1478523700','LOT 458','act 358','Albohidahy','Antananarivo','Lycée Ampitatafika','16','Uploads/default.jpg','2025-2026','HERY nasol','Ando RAVOJAT','1','G','nouveau','1');
INSERT INTO `eleves` VALUES ('308','20250067','ANIO','Fonja','2019-05-18','Antananarivo','1478523701','LOT 459','act 359','Albohidahy','Antananarivo','Lycée Ampitatafika','16','Uploads/default.jpg','2025-2026','HERY nasoloa','Ando RAVOJAF','0','F','passant','1');
INSERT INTO `eleves` VALUES ('309','20250068','ANIO','Fery','2019-05-19','Antananarivo','1478523702','LOT 460','act 360','Albohidahy','Antananarivo','Lycée Ampitatafika','16','Uploads/default.jpg','2025-2026','HERY nasoloe','Ando RAVOJAG','1','G','nouveau','0');
INSERT INTO `eleves` VALUES ('310','20250069','ANIO','Foniah','2019-05-20','Antananarivo','1478523703','LOT 461','act 361','Albohidahy','Antananarivo','Lycée Ampitatafika','16','Uploads/default.jpg','2025-2026','HERY nasolor','Ando RAVOJAH','0','F','redoublant','1');
INSERT INTO `eleves` VALUES ('311','20250070','ANIO','Fanih','2019-05-21','Antananarivo','1478523704','LOT 462','act 362','Albohidahy','Antananarivo','Lycée Ampitatafika','16','Uploads/default.jpg','2025-2026','HERY nasolort','Ando RAVOJAL','1','G','nouveau','1');
INSERT INTO `eleves` VALUES ('312','20250071','ANIO','Faniahy','2019-05-22','Antananarivo','1478523705','LOT 463','act 363','Albohidahy','Antananarivo','Lycée Ampitatafika','16','Uploads/default.jpg','2025-2026','HERY nasoloe','Ando RAVOJAO','1','F','redoublant','0');
INSERT INTO `eleves` VALUES ('313','20250072','ANIO','Faniho','2019-05-23','Antananarivo','1478523706','LOT 464','act 364','Albohidahy','Antananarivo','Lycée Ampitatafika','16','Uploads/default.jpg','2025-2026','HERY rrna','Ando RAVOJA','1','G','redoublant','1');
INSERT INTO `eleves` VALUES ('314','20250073','NANDRA','Koto','2005-10-01','Adiranaina','0371415214','Lot ANTSOY','Act145','Ankavanana','Antanananarivo','Lycée Andrakona','51','Uploads/1772088485_geralt-ai-generated-9811472_1280.jpg','2025-2026','Tojo_pro','Reko ANDRY','1','G','nouveau','1');


DROP TABLE IF EXISTS `emploi_du_temps`;
CREATE TABLE `emploi_du_temps` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_classe` int NOT NULL,
  `data_json` text COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_classe` (`id_classe`),
  CONSTRAINT `emploi_du_temps_ibfk_1` FOREIGN KEY (`id_classe`) REFERENCES `classes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=53 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `emploi_du_temps` VALUES ('34','1','[{\"heure\":\"07h00-10h00\",\"lundi\":\"Science Economique\",\"mardi\":\"SVT\",\"mercredi\":\"EPS\",\"jeudi\":\"Mathématique\",\"vendredi\":\"Physique\",\"samedi\":\"Cours\"},{\"heure\":\"10h00-10h15\",\"lundi\":\" 🕘\",\"mardi\":\"   🕘\",\"mercredi\":\"  🕘\",\"jeudi\":\"  🕘\",\"vendredi\":\"   🕘\",\"samedi\":\"  🕘\"},{\"heure\":\"10h15-12h00\",\"lundi\":\"Français\",\"mardi\":\"Physique-Chimie\",\"mercredi\":\"Français\",\"jeudi\":\"Philosophie\",\"vendredi\":\"Français\",\"samedi\":\"Cours\"},{\"heure\":\"12h00-13h00\",\"lundi\":\"❌❌❌❌❌\",\"mardi\":\"❌❌❌❌❌\",\"mercredi\":\"❌❌❌❌❌\",\"jeudi\":\"❌❌❌❌❌\",\"vendredi\":\"❌❌❌❌❌\",\"samedi\":\"❌❌❌❌❌\"},{\"heure\":\"13h00-15h00\",\"lundi\":\"Anglais\",\"mardi\":\"Histo-géo\",\"mercredi\":\"Informatique\",\"jeudi\":\"Anglais\",\"vendredi\":\"SVT\",\"samedi\":\"Cours\"},{\"heure\":\"15h00-15h15\",\"lundi\":\" 🕘\",\"mardi\":\" 🕘\",\"mercredi\":\" 🕘\",\"jeudi\":\" 🕘\",\"vendredi\":\"  🕘\",\"samedi\":\"  🕘\"},{\"heure\":\"15h15-17h00\",\"lundi\":\"Philosophie\",\"mardi\":\"SES\",\"mercredi\":\"Etude\",\"jeudi\":\"Malagasy\",\"vendredi\":\"SES\",\"samedi\":\"Cours\"}]');
INSERT INTO `emploi_du_temps` VALUES ('51','14','{\"2\":{\"heure\":\"09h30-10h00\",\"lundi\":\"MALAGASY\",\"mardi\":\"MALAGASY\",\"mercredi\":\"MALAGASY\",\"jeudi\":\"MALAGASY\",\"vendredi\":\"MALAGASY\",\"samedi\":\"MALAGASY\"},\"3\":{\"heure\":\"10h00-10h30\",\"lundi\":\"MALAGASY\",\"mardi\":\"MALAGASY\",\"mercredi\":\"MALAGASY\",\"jeudi\":\"MALAGASY\",\"vendredi\":\"MALAGASY\",\"samedi\":\"MALAGASY\"},\"4\":{\"heure\":\"11h30-12h00\",\"lundi\":\"\",\"mardi\":\"\",\"mercredi\":\"\",\"jeudi\":\"\",\"vendredi\":\"\",\"samedi\":\"\"},\"5\":{\"heure\":\"11h00-11h30\",\"lundi\":\"MALAGASY\",\"mardi\":\"MALAGASY\",\"mercredi\":\"MALAGASY\",\"jeudi\":\"MALAGASY\",\"vendredi\":\"MALAGASY\",\"samedi\":\"MALAGASY\"}}');


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

INSERT INTO `enseignants` VALUES ('1','Dupont','Jean','jean.dupont@example.com','0123456789','Mathématiques','2025-2026','2023-09-01','actif','2025-08-13 14:29:56');


DROP TABLE IF EXISTS `equipements`;
CREATE TABLE `equipements` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `quantite` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_general_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `equipements` VALUES ('3','Rouleau','10','Pour colorer les mmurs de l\'école');


DROP TABLE IF EXISTS `evenements`;
CREATE TABLE `evenements` (
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

INSERT INTO `evenements` VALUES ('70','Réunion','Appel à réunion à tous les administrateurs y compris les staffs','réunion','2026-02-10 09:00:00','2026-02-10 23:00:00','14','2026-02-28 15:08:57');
INSERT INTO `evenements` VALUES ('71','Activité quotidienne','C\'est pour toutes les personnes','','2026-03-09 09:00:00','2026-03-09 17:00:00','14','2026-03-08 21:09:15');
INSERT INTO `evenements` VALUES ('72','Sorite Récréative','Réunion préliminaire','réunion','2026-04-07 09:00:00','2026-04-07 17:00:00','16','2026-04-20 19:39:34');


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

INSERT INTO `examen_blanc` VALUES ('13','312','16','20','1','13.00','2025-2026','2026-05-04');
INSERT INTO `examen_blanc` VALUES ('14','312','16','16','1','14.00','2025-2026','2026-05-04');
INSERT INTO `examen_blanc` VALUES ('15','312','16','17','1','12.00','2025-2026','2026-05-04');
INSERT INTO `examen_blanc` VALUES ('16','312','16','3','1','14.00','2025-2026','2026-05-04');
INSERT INTO `examen_blanc` VALUES ('17','312','16','40','1','15.00','2025-2026','2026-05-04');
INSERT INTO `examen_blanc` VALUES ('18','312','16','4','1','11.00','2025-2026','2026-05-04');
INSERT INTO `examen_blanc` VALUES ('19','311','16','20','1','14.00','2025-2026','2026-05-04');


DROP TABLE IF EXISTS `fichiers`;
CREATE TABLE `fichiers` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom_fichier` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `chemin` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



DROP TABLE IF EXISTS `licence`;
CREATE TABLE `licence` (
  `id` int NOT NULL AUTO_INCREMENT,
  `cle_licence` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `date_debut` date NOT NULL,
  `date_expiration` date NOT NULL,
  `statut` enum('actif','expire') COLLATE utf8mb4_general_ci DEFAULT 'actif',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `licence` VALUES ('1','9d9a4cf1c56efce9e70272f835918a4c781a4a216f543f55a41815fbf955b198','2025-09-09','2026-09-09','actif');


DROP TABLE IF EXISTS `matieres`;
CREATE TABLE `matieres` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `coefficient` int DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=59 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `matieres` VALUES ('1','Expression orale','1');
INSERT INTO `matieres` VALUES ('2','Dessin et coloriage','1');
INSERT INTO `matieres` VALUES ('3','Chant et musique','1');
INSERT INTO `matieres` VALUES ('4','Jeux éducatifs','1');
INSERT INTO `matieres` VALUES ('5','Motricité','1');
INSERT INTO `matieres` VALUES ('6','Pré-lecture','1');
INSERT INTO `matieres` VALUES ('7','Pré-écriture','1');
INSERT INTO `matieres` VALUES ('8','Pré-mathématiques','1');
INSERT INTO `matieres` VALUES ('9','Malagasy','2');
INSERT INTO `matieres` VALUES ('12','Education Civique et Morale','1');
INSERT INTO `matieres` VALUES ('13','SVT','1');
INSERT INTO `matieres` VALUES ('14','Lecture','1');
INSERT INTO `matieres` VALUES ('16','Arts plastiques','1');
INSERT INTO `matieres` VALUES ('17','Chant','1');
INSERT INTO `matieres` VALUES ('18','EPS','1');
INSERT INTO `matieres` VALUES ('19','Exercice physique','1');
INSERT INTO `matieres` VALUES ('20','Anglais','2');
INSERT INTO `matieres` VALUES ('21','Deutsh','1');
INSERT INTO `matieres` VALUES ('26','Education artistique','1');
INSERT INTO `matieres` VALUES ('28','Mathématique','2');
INSERT INTO `matieres` VALUES ('31','Philosophie','2');
INSERT INTO `matieres` VALUES ('32','Histoire-Géographie','2');
INSERT INTO `matieres` VALUES ('33','Espagnol','1');
INSERT INTO `matieres` VALUES ('34','Physique-Chimie','2');
INSERT INTO `matieres` VALUES ('38','ECM','1');
INSERT INTO `matieres` VALUES ('40','Informatique','1');
INSERT INTO `matieres` VALUES ('42','Education pour les jeune','1');
INSERT INTO `matieres` VALUES ('43','SES','1');
INSERT INTO `matieres` VALUES ('44','Malagasy','1');
INSERT INTO `matieres` VALUES ('45','Français','1');
INSERT INTO `matieres` VALUES ('46','Maths','1');
INSERT INTO `matieres` VALUES ('47','Histoire','1');
INSERT INTO `matieres` VALUES ('48','Sciences','1');


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

INSERT INTO `message_reactions` VALUES ('1','474','19','admin','❤️','2026-02-18 10:03:21');
INSERT INTO `message_reactions` VALUES ('2','474','19','admin','👍','2026-02-18 10:04:20');
INSERT INTO `message_reactions` VALUES ('3','508','19','admin','👍','2026-02-18 10:20:18');
INSERT INTO `message_reactions` VALUES ('4','516','17','','❤️','2026-02-18 10:23:39');
INSERT INTO `message_reactions` VALUES ('5','516','19','admin','❤️','2026-02-18 10:23:52');
INSERT INTO `message_reactions` VALUES ('7','529','14','admin','❤️','2026-02-20 08:31:28');
INSERT INTO `message_reactions` VALUES ('8','349','14','admin','❤️','2026-02-25 17:38:40');
INSERT INTO `message_reactions` VALUES ('9','473','19','admin','❤️','2026-02-26 16:15:18');
INSERT INTO `message_reactions` VALUES ('10','534','19','admin','😮','2026-02-26 16:16:27');
INSERT INTO `message_reactions` VALUES ('11','534','17','','😮','2026-02-26 16:18:25');
INSERT INTO `message_reactions` VALUES ('12','534','17','','❤️','2026-02-26 16:18:27');
INSERT INTO `message_reactions` VALUES ('13','511','19','admin','❤️','2026-02-26 16:37:19');
INSERT INTO `message_reactions` VALUES ('14','542','14','admin','❤️','2026-02-28 00:25:08');
INSERT INTO `message_reactions` VALUES ('15','546','16','admin','❤️','2026-04-20 19:54:18');


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
) ENGINE=InnoDB AUTO_INCREMENT=559 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `messages` VALUES ('194','254','admin','16','test','',NULL,NULL,NULL,'2026-02-11 20:29:17','1','1');
INSERT INTO `messages` VALUES ('195','254','admin','16','derre','',NULL,NULL,NULL,'2026-02-11 20:29:44','1','1');
INSERT INTO `messages` VALUES ('196','254','enseignant','15','aloan\'izay','',NULL,NULL,NULL,'2026-02-11 20:29:57','1','1');
INSERT INTO `messages` VALUES ('197','254','enseignant','15','[Image: hot-fuuz - Copie.jpg]','','uploads/chat_files/1770831028_698cbcb461d1d.jpg','hot-fuuz - Copie.jpg','553686','2026-02-11 20:30:28','1','1');
INSERT INTO `messages` VALUES ('198','264','staff','20','salut frère','',NULL,NULL,NULL,'2026-02-11 22:49:13','1','1');
INSERT INTO `messages` VALUES ('199','262','staff','20','test','',NULL,NULL,NULL,'2026-02-11 22:50:34','1','1');
INSERT INTO `messages` VALUES ('200','262','enseignant','15','ouiui','',NULL,NULL,NULL,'2026-02-11 22:50:43','1','1');
INSERT INTO `messages` VALUES ('201','262','enseignant','15','ça roule?','',NULL,NULL,NULL,'2026-02-11 23:00:52','1','1');
INSERT INTO `messages` VALUES ('202','262','staff','20','parfait très bien','',NULL,NULL,NULL,'2026-02-11 23:01:07','1','1');
INSERT INTO `messages` VALUES ('203','262','enseignant','15','alors couvre moi','',NULL,NULL,NULL,'2026-02-11 23:01:31','1','1');
INSERT INTO `messages` VALUES ('204','262','enseignant','15','couvre','',NULL,NULL,NULL,'2026-02-11 23:01:38','1','1');
INSERT INTO `messages` VALUES ('205','262','staff','20','[Image: 1753116466_8.jpg]','','uploads/chat_files/1770840119_698ce037e417e.jpg','1753116466_8.jpg','27058','2026-02-11 23:01:59','1','1');
INSERT INTO `messages` VALUES ('206','262','staff','20','heyeuy','',NULL,NULL,NULL,'2026-02-11 23:12:17','1','1');
INSERT INTO `messages` VALUES ('207','262','staff','20','dedzdzdedzseze','',NULL,NULL,NULL,'2026-02-11 23:23:51','1','1');
INSERT INTO `messages` VALUES ('208','262','enseignant','15','dededzzd','',NULL,NULL,NULL,'2026-02-11 23:23:58','1','1');
INSERT INTO `messages` VALUES ('209','262','staff','20','dererere','',NULL,NULL,NULL,'2026-02-11 23:48:11','1','1');
INSERT INTO `messages` VALUES ('210','262','enseignant','15','rfrrfrfesefesef','',NULL,NULL,NULL,'2026-02-11 23:48:20','1','1');
INSERT INTO `messages` VALUES ('211','262','enseignant','15','dererer','',NULL,NULL,NULL,'2026-02-11 23:51:10','1','1');
INSERT INTO `messages` VALUES ('212','262','staff','20','dede','',NULL,NULL,NULL,'2026-02-11 23:51:16','1','1');
INSERT INTO `messages` VALUES ('213','262','staff','20','dede','',NULL,NULL,NULL,'2026-02-11 23:51:19','1','1');
INSERT INTO `messages` VALUES ('214','262','enseignant','15','derereseress','',NULL,NULL,NULL,'2026-02-11 23:58:27','0','0');
INSERT INTO `messages` VALUES ('215','261','admin','14','dereb','',NULL,NULL,NULL,'2026-02-11 23:59:07','1','1');
INSERT INTO `messages` VALUES ('216','261','staff','20','derederre','',NULL,NULL,NULL,'2026-02-11 23:59:17','1','1');
INSERT INTO `messages` VALUES ('217','261','admin','14','rereserderdr','',NULL,NULL,NULL,'2026-02-11 23:59:26','0','0');
INSERT INTO `messages` VALUES ('218','258','admin','19','salu','',NULL,NULL,NULL,'2026-02-12 00:00:45','1','1');
INSERT INTO `messages` VALUES ('219','258','admin','19','dererrer','',NULL,NULL,NULL,'2026-02-12 00:00:52','1','1');
INSERT INTO `messages` VALUES ('220','258','admin','16','cdehtredsdsde','',NULL,NULL,NULL,'2026-02-12 00:01:02','1','1');
INSERT INTO `messages` VALUES ('221','258','admin','16','[Voice: 2s]','','uploads/chat_files/1770844172_698cf00cefa7b.webm','Voice_2026-02-11_2109.webm','22514','2026-02-12 00:09:32','1','1');
INSERT INTO `messages` VALUES ('222','258','admin','19','deredre','',NULL,NULL,NULL,'2026-02-12 00:13:20','1','1');
INSERT INTO `messages` VALUES ('223','258','admin','16','erdererv','',NULL,NULL,NULL,'2026-02-12 00:14:07','1','1');
INSERT INTO `messages` VALUES ('224','258','admin','19','errrerdererereferrer','',NULL,NULL,NULL,'2026-02-12 00:14:14','1','1');
INSERT INTO `messages` VALUES ('225','258','admin','16','dereresertrererereer','',NULL,NULL,NULL,'2026-02-12 00:18:37','1','1');
INSERT INTO `messages` VALUES ('226','258','admin','19','derecederererrerrresersfrer','',NULL,NULL,NULL,'2026-02-12 00:24:32','1','1');
INSERT INTO `messages` VALUES ('227','258','admin','16','rrretrer','',NULL,NULL,NULL,'2026-02-12 00:24:37','1','1');
INSERT INTO `messages` VALUES ('228','258','admin','19','derrtrertresert','',NULL,NULL,NULL,'2026-02-12 00:40:57','1','1');
INSERT INTO `messages` VALUES ('229','258','admin','16','rreterrer','',NULL,NULL,NULL,'2026-02-12 00:41:06','1','1');
INSERT INTO `messages` VALUES ('230','258','admin','19','derezazerrere','',NULL,NULL,NULL,'2026-02-12 00:44:25','1','1');
INSERT INTO `messages` VALUES ('231','258','admin','16','edererer','',NULL,NULL,NULL,'2026-02-12 00:45:48','1','1');
INSERT INTO `messages` VALUES ('232','258','admin','16','redertder','',NULL,NULL,NULL,'2026-02-12 00:45:53','1','1');
INSERT INTO `messages` VALUES ('233','258','admin','16','derecde','',NULL,NULL,NULL,'2026-02-12 00:53:46','1','1');
INSERT INTO `messages` VALUES ('234','258','admin','19','deded','',NULL,NULL,NULL,'2026-02-12 00:53:50','1','1');
INSERT INTO `messages` VALUES ('235','258','admin','16','dedede','',NULL,NULL,NULL,'2026-02-12 00:54:02','1','1');
INSERT INTO `messages` VALUES ('236','258','admin','19','dede','',NULL,NULL,NULL,'2026-02-12 00:54:10','1','1');
INSERT INTO `messages` VALUES ('237','270','admin','19','dedede','',NULL,NULL,NULL,'2026-02-12 01:09:54','0','0');
INSERT INTO `messages` VALUES ('238','270','admin','19','Les gars salut','',NULL,NULL,NULL,'2026-02-12 01:18:05','0','0');
INSERT INTO `messages` VALUES ('239','271','admin','19','dertee','',NULL,NULL,NULL,'2026-02-12 01:19:00','1','1');
INSERT INTO `messages` VALUES ('240','271','admin','16','derre','',NULL,NULL,NULL,'2026-02-12 01:40:47','1','0');
INSERT INTO `messages` VALUES ('241','271','admin','16','der','',NULL,NULL,NULL,'2026-02-12 01:47:50','1','0');
INSERT INTO `messages` VALUES ('242','271','admin','19','no','',NULL,NULL,NULL,'2026-02-12 01:50:58','1','1');
INSERT INTO `messages` VALUES ('243','273','admin','19','dertt','',NULL,NULL,NULL,'2026-02-12 01:51:55','0','0');
INSERT INTO `messages` VALUES ('244','271','admin','19','dedee','',NULL,NULL,NULL,'2026-02-12 01:58:19','1','1');
INSERT INTO `messages` VALUES ('245','274','admin','19','cool','',NULL,NULL,NULL,'2026-02-12 01:59:28','1','1');
INSERT INTO `messages` VALUES ('246','271','admin','19','dert','',NULL,NULL,NULL,'2026-02-12 02:02:43','1','1');
INSERT INTO `messages` VALUES ('247','271','admin','16','dedede','',NULL,NULL,NULL,'2026-02-12 02:02:58','1','0');
INSERT INTO `messages` VALUES ('248','274','admin','16','path','',NULL,NULL,NULL,'2026-02-12 02:04:19','1','0');
INSERT INTO `messages` VALUES ('249','274','admin','19','brrrr','',NULL,NULL,NULL,'2026-02-12 02:04:28','1','1');
INSERT INTO `messages` VALUES ('250','274','admin','16','het','',NULL,NULL,NULL,'2026-02-12 02:08:42','1','0');
INSERT INTO `messages` VALUES ('251','274','admin','19','fert','',NULL,NULL,NULL,'2026-02-12 02:08:49','1','1');
INSERT INTO `messages` VALUES ('252','271','admin','16','dert','',NULL,NULL,NULL,'2026-02-12 02:09:04','1','0');
INSERT INTO `messages` VALUES ('253','274','admin','16','seryer','',NULL,NULL,NULL,'2026-02-12 02:12:46','1','0');
INSERT INTO `messages` VALUES ('254','274','admin','16','terst','',NULL,NULL,NULL,'2026-02-12 02:12:54','1','0');
INSERT INTO `messages` VALUES ('255','274','admin','19','gttrtr','',NULL,NULL,NULL,'2026-02-12 02:12:58','1','1');
INSERT INTO `messages` VALUES ('256','274','admin','16','fere','',NULL,NULL,NULL,'2026-02-12 02:13:05','1','0');
INSERT INTO `messages` VALUES ('257','274','admin','16','yo','',NULL,NULL,NULL,'2026-02-12 02:13:56','1','0');
INSERT INTO `messages` VALUES ('258','274','admin','19','ert','',NULL,NULL,NULL,'2026-02-12 02:14:03','1','1');
INSERT INTO `messages` VALUES ('259','258','admin','16','[Image: 1753787667_DSC_5225.jpg]','','uploads/chat_files/1770870898_698d5872b01e6.jpg','1753787667_DSC_5225.jpg','873491','2026-02-12 07:34:58','1','1');
INSERT INTO `messages` VALUES ('260','258','admin','19','hey','',NULL,NULL,NULL,'2026-02-12 13:52:15','1','1');
INSERT INTO `messages` VALUES ('261','258','admin','19','oui salut','',NULL,NULL,NULL,'2026-02-12 16:14:19','1','1');
INSERT INTO `messages` VALUES ('262','258','admin','16','quoi que veux tu?','',NULL,NULL,NULL,'2026-02-12 16:14:35','1','1');
INSERT INTO `messages` VALUES ('263','274','admin','16','dedede','',NULL,NULL,NULL,'2026-02-12 16:23:30','1','0');
INSERT INTO `messages` VALUES ('264','271','admin','16','dede','',NULL,NULL,NULL,'2026-02-12 16:24:00','1','0');
INSERT INTO `messages` VALUES ('265','274','admin','19','deded','',NULL,NULL,NULL,'2026-02-12 16:24:05','1','1');
INSERT INTO `messages` VALUES ('266','274','admin','19','dede','',NULL,NULL,NULL,'2026-02-12 16:24:12','1','1');
INSERT INTO `messages` VALUES ('267','271','admin','16','hey','',NULL,NULL,NULL,'2026-02-12 16:26:30','1','0');
INSERT INTO `messages` VALUES ('268','274','admin','19','coucou','',NULL,NULL,NULL,'2026-02-12 16:26:35','1','1');
INSERT INTO `messages` VALUES ('269','271','admin','19','quoi','',NULL,NULL,NULL,'2026-02-12 16:26:42','1','1');
INSERT INTO `messages` VALUES ('270','274','admin','19','[Image: hot-fuuz - Copie.jpg]','','uploads/chat_files/1770903456_698dd7a0b9f18.jpg','hot-fuuz - Copie.jpg','553686','2026-02-12 16:37:36','1','1');
INSERT INTO `messages` VALUES ('271','274','admin','16','dert','',NULL,NULL,NULL,'2026-02-12 16:38:04','1','0');
INSERT INTO `messages` VALUES ('272','274','admin','16','dede','',NULL,NULL,NULL,'2026-02-12 16:38:15','1','0');
INSERT INTO `messages` VALUES ('273','274','admin','16','dede','',NULL,NULL,NULL,'2026-02-12 17:25:31','1','0');
INSERT INTO `messages` VALUES ('274','274','admin','16','dde','',NULL,NULL,NULL,'2026-02-12 17:26:13','1','0');
INSERT INTO `messages` VALUES ('275','274','admin','16','xxx','',NULL,NULL,NULL,'2026-02-12 17:26:53','1','0');
INSERT INTO `messages` VALUES ('276','274','admin','16','[Image: 1753116398_5.jpg]','','uploads/chat_files/1770906423_698de33725d32.jpg','1753116398_5.jpg','25095','2026-02-12 17:27:03','1','0');
INSERT INTO `messages` VALUES ('277','274','admin','16','ddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccfffffffffffffffvvvvvvvvvvvdddddddd','',NULL,NULL,NULL,'2026-02-12 17:28:12','1','0');
INSERT INTO `messages` VALUES ('278','258','admin','16','ccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccc','',NULL,NULL,NULL,'2026-02-12 17:28:55','1','1');
INSERT INTO `messages` VALUES ('279','271','admin','0','xss','',NULL,NULL,NULL,'2026-02-12 17:31:07','1','1');
INSERT INTO `messages` VALUES ('280','271','admin','16','szszs','',NULL,NULL,NULL,'2026-02-12 17:31:12','1','0');
INSERT INTO `messages` VALUES ('281','271','admin','16','szs','',NULL,NULL,NULL,'2026-02-12 17:31:35','1','0');
INSERT INTO `messages` VALUES ('282','271','admin','0','szsz','',NULL,NULL,NULL,'2026-02-12 17:31:37','1','1');
INSERT INTO `messages` VALUES ('283','271','admin','0','dd','',NULL,NULL,NULL,'2026-02-12 17:49:06','1','1');
INSERT INTO `messages` VALUES ('284','271','admin','0','dd','',NULL,NULL,NULL,'2026-02-12 17:54:58','1','1');
INSERT INTO `messages` VALUES ('285','271','admin','16','ddd','',NULL,NULL,NULL,'2026-02-12 17:55:08','1','0');
INSERT INTO `messages` VALUES ('286','271','admin','0','ddedee','',NULL,NULL,NULL,'2026-02-12 18:40:20','1','1');
INSERT INTO `messages` VALUES ('287','271','admin','0','dede','',NULL,NULL,NULL,'2026-02-12 18:40:23','1','1');
INSERT INTO `messages` VALUES ('288','271','admin','14','cool','',NULL,NULL,NULL,'2026-02-12 18:56:20','1','1');
INSERT INTO `messages` VALUES ('289','274','admin','14','feyurttrt','',NULL,NULL,NULL,'2026-02-12 19:06:40','1','1');
INSERT INTO `messages` VALUES ('290','274','admin','14','J\'aimerai bien te parler aujourd\'hui vieux, il ya un trux qui cloche','',NULL,NULL,NULL,'2026-02-12 19:20:41','1','1');
INSERT INTO `messages` VALUES ('291','261','admin','14','ffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffff','',NULL,NULL,NULL,'2026-02-12 20:01:16','0','0');
INSERT INTO `messages` VALUES ('292','261','admin','14','[Image: 1753116347_2.jpg]','','uploads/chat_files/1770916964_698e0c64061b5.jpg','1753116347_2.jpg','41661','2026-02-12 20:22:44','0','0');
INSERT INTO `messages` VALUES ('293','271','admin','14','tream','',NULL,NULL,NULL,'2026-02-12 20:27:31','1','1');
INSERT INTO `messages` VALUES ('294','274','admin','14','team','',NULL,NULL,NULL,'2026-02-12 20:27:37','1','1');
INSERT INTO `messages` VALUES ('295','274','admin','14','dedede','',NULL,NULL,NULL,'2026-02-12 20:42:41','1','1');
INSERT INTO `messages` VALUES ('296','274','admin','14','ddd','',NULL,NULL,NULL,'2026-02-12 20:42:44','1','1');
INSERT INTO `messages` VALUES ('297','274','admin','14','[image: 1753116449_7.jpg]','','uploads/chat_files/1770918187_698e112b44ba4.jpg','1753116449_7.jpg','40611','2026-02-12 20:43:07','1','1');
INSERT INTO `messages` VALUES ('298','274','admin','14','[image: 1753116466_8.jpg]','','uploads/chat_files/1770918799_698e138f6ac5b.jpg','1753116466_8.jpg','27058','2026-02-12 20:53:19','1','1');
INSERT INTO `messages` VALUES ('299','274','admin','14','dernier tes','',NULL,NULL,NULL,'2026-02-12 20:58:10','1','1');
INSERT INTO `messages` VALUES ('300','274','admin','14','[image: 1753116449_7.jpg]','','uploads/chat_files/1770919100_698e14bcaa077.jpg','1753116449_7.jpg','40611','2026-02-12 20:58:20','1','1');
INSERT INTO `messages` VALUES ('301','274','admin','14','tes deux','',NULL,NULL,NULL,'2026-02-12 21:07:47','1','1');
INSERT INTO `messages` VALUES ('302','274','admin','14','[image: 1753116347_2.jpg]','','uploads/chat_files/1770919699_698e1713de6e5.jpg','1753116347_2.jpg','41661','2026-02-12 21:08:19','1','1');
INSERT INTO `messages` VALUES ('303','274','admin','14','ferr','',NULL,NULL,NULL,'2026-02-12 21:36:38','1','1');
INSERT INTO `messages` VALUES ('304','274','admin','14','derter','',NULL,NULL,NULL,'2026-02-12 21:36:45','1','1');
INSERT INTO `messages` VALUES ('305','271','admin','14','dede','',NULL,NULL,NULL,'2026-02-12 21:36:50','1','1');
INSERT INTO `messages` VALUES ('306','283','admin','14','test','',NULL,NULL,NULL,'2026-02-12 21:46:26','1','1');
INSERT INTO `messages` VALUES ('307','283','admin','14','dedede','',NULL,NULL,NULL,'2026-02-12 21:46:39','1','1');
INSERT INTO `messages` VALUES ('308','283','admin','16','test','',NULL,NULL,NULL,'2026-02-12 21:48:31','1','0');
INSERT INTO `messages` VALUES ('309','283','admin','16','okay ça marche vieux','',NULL,NULL,NULL,'2026-02-12 22:08:18','1','0');
INSERT INTO `messages` VALUES ('310','283','admin','19','no, encore une erreurs, nooo','',NULL,NULL,NULL,'2026-02-12 22:08:47','1','1');
INSERT INTO `messages` VALUES ('311','283','admin','19','noooo','',NULL,NULL,NULL,'2026-02-12 22:08:54','1','1');
INSERT INTO `messages` VALUES ('312','283','admin','16','please revien','',NULL,NULL,NULL,'2026-02-12 22:09:02','1','0');
INSERT INTO `messages` VALUES ('313','283','admin','16','[image: Error à régler.png]','','uploads/chat_files/1770923418_698e259a9d6a5.png','Error à régler.png','964841','2026-02-12 22:10:18','1','0');
INSERT INTO `messages` VALUES ('314','283','admin','19','heyy','',NULL,NULL,NULL,'2026-02-12 22:19:49','1','1');
INSERT INTO `messages` VALUES ('315','283','admin','16','quoi?','',NULL,NULL,NULL,'2026-02-12 22:20:13','1','0');
INSERT INTO `messages` VALUES ('316','283','admin','19','e\"','',NULL,NULL,NULL,'2026-02-12 22:21:16','1','1');
INSERT INTO `messages` VALUES ('317','283','admin','16','test','',NULL,NULL,NULL,'2026-02-12 22:26:13','1','0');
INSERT INTO `messages` VALUES ('318','283','admin','16','tes à nouveau','',NULL,NULL,NULL,'2026-02-12 22:26:40','1','0');
INSERT INTO `messages` VALUES ('319','274','admin','16','test encore','',NULL,NULL,NULL,'2026-02-12 22:27:05','1','0');
INSERT INTO `messages` VALUES ('320','274','admin','16','team','',NULL,NULL,NULL,'2026-02-12 22:29:47','1','0');
INSERT INTO `messages` VALUES ('321','274','admin','16','ohh','',NULL,NULL,NULL,'2026-02-12 22:29:54','1','0');
INSERT INTO `messages` VALUES ('322','283','admin','16','test','',NULL,NULL,NULL,'2026-02-12 22:30:07','1','0');
INSERT INTO `messages` VALUES ('323','283','admin','16','test','',NULL,NULL,NULL,'2026-02-12 22:38:16','1','0');
INSERT INTO `messages` VALUES ('324','283','admin','16','encore','',NULL,NULL,NULL,'2026-02-12 22:38:30','1','0');
INSERT INTO `messages` VALUES ('325','274','admin','19','nooo','',NULL,NULL,NULL,'2026-02-12 22:38:47','1','1');
INSERT INTO `messages` VALUES ('326','274','admin','16','[image: hot-fuuz - Copie.jpg]','','uploads/chat_files/1770925149_698e2c5dd9c30.jpg','hot-fuuz - Copie.jpg','553686','2026-02-12 22:39:09','1','0');
INSERT INTO `messages` VALUES ('327','274','admin','19','c\'est quoi?','',NULL,NULL,NULL,'2026-02-12 22:39:44','1','1');
INSERT INTO `messages` VALUES ('328','283','admin','19','ça marche vieux, c\'est bon','',NULL,NULL,NULL,'2026-02-12 22:40:07','1','1');
INSERT INTO `messages` VALUES ('329','274','admin','16','test farany','',NULL,NULL,NULL,'2026-02-12 22:41:15','1','0');
INSERT INTO `messages` VALUES ('330','283','admin','16','je dois le tester encore','',NULL,NULL,NULL,'2026-02-12 22:47:53','1','0');
INSERT INTO `messages` VALUES ('331','283','admin','19','ça va','',NULL,NULL,NULL,'2026-02-12 22:48:01','1','1');
INSERT INTO `messages` VALUES ('332','283','admin','19','test encore','',NULL,NULL,NULL,'2026-02-12 22:48:16','1','1');
INSERT INTO `messages` VALUES ('333','283','admin','16','dernier dernier test avant l\'ajout de l\'avatar','',NULL,NULL,NULL,'2026-02-12 22:50:17','1','0');
INSERT INTO `messages` VALUES ('334','283','admin','19','parfait c\'est rapide','',NULL,NULL,NULL,'2026-02-12 22:50:26','1','1');
INSERT INTO `messages` VALUES ('335','283','admin','16','oui c\'est ultra rapide','',NULL,NULL,NULL,'2026-02-12 22:50:39','1','0');
INSERT INTO `messages` VALUES ('336','283','admin','19','le dernier test avec une vitesse de 350 ms','',NULL,NULL,NULL,'2026-02-12 22:51:24','1','1');
INSERT INTO `messages` VALUES ('337','283','admin','16','woaouuu c\'est ultra rapide','',NULL,NULL,NULL,'2026-02-12 22:51:34','1','0');
INSERT INTO `messages` VALUES ('338','283','admin','16','GG','',NULL,NULL,NULL,'2026-02-12 22:51:38','1','0');
INSERT INTO `messages` VALUES ('339','274','admin','19','c\'est gravement belle','',NULL,NULL,NULL,'2026-02-12 22:51:53','1','1');
INSERT INTO `messages` VALUES ('340','258','admin','19','what the fuck vieux?','',NULL,NULL,NULL,'2026-02-13 00:05:07','1','1');
INSERT INTO `messages` VALUES ('341','258','admin','19','nothing','',NULL,NULL,NULL,'2026-02-13 07:19:15','1','1');
INSERT INTO `messages` VALUES ('342','258','admin','19','ça marhe?','',NULL,NULL,NULL,'2026-02-13 07:20:56','1','1');
INSERT INTO `messages` VALUES ('343','271','admin','16','cool','',NULL,NULL,NULL,'2026-02-13 07:26:47','1','0');
INSERT INTO `messages` VALUES ('344','261','admin','14','hoy','',NULL,NULL,NULL,'2026-02-13 10:57:33','0','0');
INSERT INTO `messages` VALUES ('345','281','admin','19','Hello Tojo😁','',NULL,NULL,NULL,'2026-02-13 10:58:58','1','1');
INSERT INTO `messages` VALUES ('346','281','admin','14','Hello stessy, quelle belle journée hein? 💕😍😉','',NULL,NULL,NULL,'2026-02-13 10:59:56','1','1');
INSERT INTO `messages` VALUES ('347','281','admin','14','J\'avoue j\'ai hâte de te voir','',NULL,NULL,NULL,'2026-02-13 11:00:15','1','1');
INSERT INTO `messages` VALUES ('348','281','admin','19','Je tes vieux 😁🤣','',NULL,NULL,NULL,'2026-02-13 11:02:52','1','1');
INSERT INTO `messages` VALUES ('349','281','admin','19','Salut veilles, je veux te bam bam','',NULL,NULL,NULL,'2026-02-13 11:03:54','1','1');
INSERT INTO `messages` VALUES ('353','283','admin','19','chiao','',NULL,NULL,NULL,'2026-02-13 11:52:41','1','1');
INSERT INTO `messages` VALUES ('354','281','admin','19','[Voice: 0s]','','uploads/chat_files/1770984684_698f14ecefccb.webm','Voice_2026-02-13_1211.webm','110','2026-02-13 15:11:24','1','1');
INSERT INTO `messages` VALUES ('357','283','admin','19','[image: 1753116347_2.jpg]','','uploads/chat_files/1770984784_698f155057169.jpg','1753116347_2.jpg','41661','2026-02-13 15:13:04','1','1');
INSERT INTO `messages` VALUES ('358','281','admin','14','test blablabla,','',NULL,NULL,NULL,'2026-02-13 15:50:40','1','1');
INSERT INTO `messages` VALUES ('359','281','admin','19','quoi?','',NULL,NULL,NULL,'2026-02-13 15:50:54','1','1');
INSERT INTO `messages` VALUES ('360','281','admin','14','non ce n\'est rien','',NULL,NULL,NULL,'2026-02-13 15:51:02','1','1');
INSERT INTO `messages` VALUES ('361','281','admin','14','on test','',NULL,NULL,NULL,'2026-02-13 15:51:09','1','1');
INSERT INTO `messages` VALUES ('362','281','admin','14','encre','',NULL,NULL,NULL,'2026-02-13 15:51:18','1','1');
INSERT INTO `messages` VALUES ('363','281','admin','19','ça foncitonne la photo','',NULL,NULL,NULL,'2026-02-13 16:02:20','1','1');
INSERT INTO `messages` VALUES ('364','281','admin','14','ah ouias','',NULL,NULL,NULL,'2026-02-13 16:02:57','1','1');
INSERT INTO `messages` VALUES ('365','281','admin','14','parfait j\'aime bien','',NULL,NULL,NULL,'2026-02-13 16:03:08','1','1');
INSERT INTO `messages` VALUES ('366','281','admin','19','test maintenant','',NULL,NULL,NULL,'2026-02-13 16:18:53','1','1');
INSERT INTO `messages` VALUES ('367','281','admin','19','cool','',NULL,NULL,NULL,'2026-02-13 16:19:46','1','1');
INSERT INTO `messages` VALUES ('368','281','admin','14','okay','',NULL,NULL,NULL,'2026-02-13 16:19:53','1','1');
INSERT INTO `messages` VALUES ('369','281','admin','14','teg','',NULL,NULL,NULL,'2026-02-13 16:50:37','1','1');
INSERT INTO `messages` VALUES ('370','281','admin','14','test','',NULL,NULL,NULL,'2026-02-13 16:53:02','1','1');
INSERT INTO `messages` VALUES ('371','281','admin','19','oui','',NULL,NULL,NULL,'2026-02-13 16:53:18','1','1');
INSERT INTO `messages` VALUES ('372','281','admin','19','[Image: 1753116419_6.jpg]','','uploads/chat_files/1770990806_698f2cd6e44d1.jpg','1753116419_6.jpg','43167','2026-02-13 16:53:26','1','1');
INSERT INTO `messages` VALUES ('373','281','admin','14','[File: test présence.pdf]','','uploads/chat_files/1770990834_698f2cf225c43.pdf','test présence.pdf','723092','2026-02-13 16:53:54','1','1');
INSERT INTO `messages` VALUES ('374','281','admin','19','[Image: 1753116449_7.jpg]','','uploads/chat_files/1770992563_698f33b37f414.jpg','1753116449_7.jpg','40611','2026-02-13 17:22:43','1','1');
INSERT INTO `messages` VALUES ('375','281','admin','14','[Image: 1753117426_3.jpg]','','uploads/chat_files/1770992945_698f353139cc1.jpg','1753117426_3.jpg','47934','2026-02-13 17:29:05','1','1');
INSERT INTO `messages` VALUES ('376','281','admin','19','tape your message here if you are busy','',NULL,NULL,NULL,'2026-02-13 17:31:36','1','1');
INSERT INTO `messages` VALUES ('377','281','admin','19','okay thanks me later','',NULL,NULL,NULL,'2026-02-13 17:31:47','1','1');
INSERT INTO `messages` VALUES ('378','281','admin','19','[Voice: 5.5s]','','uploads/chat_files/1770994093_698f39ad8538d.webm','Voice_2026-02-13_14-48-13.webm','78542','2026-02-13 17:48:13','1','1');
INSERT INTO `messages` VALUES ('379','281','admin','19','dederr','',NULL,NULL,NULL,'2026-02-13 17:48:20','1','1');
INSERT INTO `messages` VALUES ('380','281','admin','19','dederdede','',NULL,NULL,NULL,'2026-02-13 17:48:30','1','1');
INSERT INTO `messages` VALUES ('381','281','admin','14','[Image: 1754583486_DSC_5326.jpg]','','uploads/chat_files/1770994128_698f39d020850.jpg','1754583486_DSC_5326.jpg','1081334','2026-02-13 17:48:48','1','1');
INSERT INTO `messages` VALUES ('382','281','admin','14','[Voice: 2.8s]','','uploads/chat_files/1770994133_698f39d5ebe41.webm','Voice_2026-02-13_14-48-53.webm','40868','2026-02-13 17:48:53','1','1');
INSERT INTO `messages` VALUES ('383','281','admin','19','Detecetion d\'un coup dans le cours, alors, il faut ajouter un bijou qui capte automatiquement les voix des animaux et surtout tenter de rester en lgnes','',NULL,NULL,NULL,'2026-02-13 17:55:01','1','1');
INSERT INTO `messages` VALUES ('384','281','admin','19','dert','',NULL,NULL,NULL,'2026-02-13 18:43:22','1','1');
INSERT INTO `messages` VALUES ('385','281','admin','19','[Image: 1753116466_8.jpg]','','uploads/chat_files/1770997415_698f46a719426.jpg','1753116466_8.jpg','27058','2026-02-13 18:43:35','1','1');
INSERT INTO `messages` VALUES ('386','281','admin','14','dert','',NULL,NULL,NULL,'2026-02-13 18:44:43','1','1');
INSERT INTO `messages` VALUES ('387','281','admin','14','[Image: 1753117465_3.jpg]','','uploads/chat_files/1770997491_698f46f357b8a.jpg','1753117465_3.jpg','43057','2026-02-13 18:44:51','1','1');
INSERT INTO `messages` VALUES ('388','281','admin','14','[Voice: 1.4s]','','uploads/chat_files/1770997496_698f46f80fe41.webm','Voice_2026-02-13_15-44-56.webm','12854','2026-02-13 18:44:56','1','1');
INSERT INTO `messages` VALUES ('389','258','admin','19','deeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeee','',NULL,NULL,NULL,'2026-02-13 19:13:23','1','1');
INSERT INTO `messages` VALUES ('390','258','admin','19','[Image: 1753116449_7.jpg]','','uploads/chat_files/1770999634_698f4f52da144.jpg','1753116449_7.jpg','40611','2026-02-13 19:20:34','1','1');
INSERT INTO `messages` VALUES ('391','258','admin','19','[Image: 1753116449_7.jpg]','','uploads/chat_files/1770999687_698f4f8797ed9.jpg','1753116449_7.jpg','40611','2026-02-13 19:21:27','1','1');
INSERT INTO `messages` VALUES ('392','258','admin','19','[Voice: 1.5s]','','uploads/chat_files/1770999693_698f4f8d59724.webm','Voice_2026-02-13_16-21-33.webm','13820','2026-02-13 19:21:33','1','1');
INSERT INTO `messages` VALUES ('393','281','admin','14','coucou','',NULL,NULL,NULL,'2026-02-13 19:32:00','1','1');
INSERT INTO `messages` VALUES ('394','281','admin','14','hey','',NULL,NULL,NULL,'2026-02-13 19:32:38','1','1');
INSERT INTO `messages` VALUES ('395','281','admin','14','test farany','',NULL,NULL,NULL,'2026-02-13 19:36:48','1','1');
INSERT INTO `messages` VALUES ('396','281','admin','14','testpigé','',NULL,NULL,NULL,'2026-02-13 19:37:00','1','1');
INSERT INTO `messages` VALUES ('397','281','admin','19','coucou bébé','',NULL,NULL,NULL,'2026-02-13 19:46:20','1','1');
INSERT INTO `messages` VALUES ('398','281','admin','19','ça va','',NULL,NULL,NULL,'2026-02-13 19:46:28','1','1');
INSERT INTO `messages` VALUES ('399','281','admin','14','yes bébé, je vais bien','',NULL,NULL,NULL,'2026-02-13 19:46:43','1','1');
INSERT INTO `messages` VALUES ('400','292','admin','19','dederrr','',NULL,NULL,NULL,'2026-02-13 20:29:59','1','0');
INSERT INTO `messages` VALUES ('401','292','admin','19','derr','',NULL,NULL,NULL,'2026-02-13 20:37:35','1','0');
INSERT INTO `messages` VALUES ('402','292','admin','19','de','',NULL,NULL,NULL,'2026-02-13 20:38:57','1','0');
INSERT INTO `messages` VALUES ('403','271','admin','19','[image: 1753116419_6.jpg]','','uploads/chat_files/1771005001_698f64491114b.jpg','1753116419_6.jpg','43167','2026-02-13 20:50:01','1','1');
INSERT INTO `messages` VALUES ('404','283','admin','16','test envoi','',NULL,NULL,NULL,'2026-02-13 21:00:07','1','0');
INSERT INTO `messages` VALUES ('405','292','admin','16','quoi','',NULL,NULL,NULL,'2026-02-13 21:00:30','1','0');
INSERT INTO `messages` VALUES ('406','292','admin','16','[image: image2.jpg]','','uploads/chat_files/1771005937_698f67f16f078.jpg','image2.jpg','52616','2026-02-13 21:05:37','1','0');
INSERT INTO `messages` VALUES ('407','283','admin','19','ecrit','',NULL,NULL,NULL,'2026-02-13 21:15:35','1','1');
INSERT INTO `messages` VALUES ('408','283','admin','19','quoi,','',NULL,NULL,NULL,'2026-02-13 21:15:55','1','1');
INSERT INTO `messages` VALUES ('409','283','admin','19','C4EST INJUSTE','',NULL,NULL,NULL,'2026-02-13 21:16:03','1','1');
INSERT INTO `messages` VALUES ('410','283','admin','19','[Image envoyée]','','uploads/chat_files/1771006625_698f6aa15e68a.jpg','1753116449_7.jpg','40611','2026-02-13 21:17:05','1','1');
INSERT INTO `messages` VALUES ('411','283','admin','16','BORDER','',NULL,NULL,NULL,'2026-02-13 21:17:31','1','0');
INSERT INTO `messages` VALUES ('412','271','admin','16','sérieux','',NULL,NULL,NULL,'2026-02-13 21:30:22','1','0');
INSERT INTO `messages` VALUES ('413','271','admin','16','derc','',NULL,NULL,NULL,'2026-02-13 21:30:39','1','0');
INSERT INTO `messages` VALUES ('414','271','admin','16','dedede','',NULL,NULL,NULL,'2026-02-13 21:31:06','1','0');
INSERT INTO `messages` VALUES ('415','271','admin','16','[Image envoyée]','','uploads/chat_files/1771007679_698f6ebf7f3ff.jpg','1754548480_DSC_5203.jpg','1100587','2026-02-13 21:34:39','1','0');
INSERT INTO `messages` VALUES ('416','283','admin','19','vieux','',NULL,NULL,NULL,'2026-02-13 21:48:34','1','1');
INSERT INTO `messages` VALUES ('417','271','admin','19','wowww j\'adore ça vieux','',NULL,NULL,NULL,'2026-02-13 22:06:10','1','1');
INSERT INTO `messages` VALUES ('418','271','admin','19','[Image envoyée]','','uploads/chat_files/1771009589_698f7635167d9.jpg','1753116449_7.jpg','40611','2026-02-13 22:06:29','1','1');
INSERT INTO `messages` VALUES ('419','271','admin','19','t\'es sur','',NULL,NULL,NULL,'2026-02-13 22:06:36','1','1');
INSERT INTO `messages` VALUES ('420','271','admin','19','[Image envoyée]','','uploads/chat_files/1771009606_698f7646025a6.jpg','1753116449_7.jpg','40611','2026-02-13 22:06:46','1','1');
INSERT INTO `messages` VALUES ('421','271','admin','19','quoi?','',NULL,NULL,NULL,'2026-02-13 22:07:51','1','1');
INSERT INTO `messages` VALUES ('422','283','admin','19','coucou','',NULL,NULL,NULL,'2026-02-13 22:13:06','1','1');
INSERT INTO `messages` VALUES ('423','283','admin','19','salut','',NULL,NULL,NULL,'2026-02-13 22:13:22','1','1');
INSERT INTO `messages` VALUES ('424','274','admin','16','quoiKN','',NULL,NULL,NULL,'2026-02-13 22:13:41','1','0');
INSERT INTO `messages` VALUES ('425','274','admin','19','rin du tou','',NULL,NULL,NULL,'2026-02-13 22:15:03','1','1');
INSERT INTO `messages` VALUES ('426','274','admin','19','[Image envoyée]','','uploads/chat_files/1771010108_698f783c8b27e.jpg','1753116449_7.jpg','40611','2026-02-13 22:15:08','1','1');
INSERT INTO `messages` VALUES ('427','274','admin','19','cool','',NULL,NULL,NULL,'2026-02-13 22:21:28','1','1');
INSERT INTO `messages` VALUES ('428','274','admin','19','bien','',NULL,NULL,NULL,'2026-02-13 22:21:34','1','1');
INSERT INTO `messages` VALUES ('429','283','admin','16','wowwww ça fonctionne vieux','',NULL,NULL,NULL,'2026-02-13 22:22:05','1','0');
INSERT INTO `messages` VALUES ('430','283','admin','16','[Image envoyée]','','uploads/chat_files/1771010562_698f7a02224dd.jpg','1753117426_3.jpg','47934','2026-02-13 22:22:42','1','0');
INSERT INTO `messages` VALUES ('431','258','admin','19','hey stessy','',NULL,NULL,NULL,'2026-02-13 22:30:21','1','1');
INSERT INTO `messages` VALUES ('432','258','admin','16','non il fait pas comme ça','',NULL,NULL,NULL,'2026-02-13 22:30:42','1','1');
INSERT INTO `messages` VALUES ('433','258','admin','19','alprsdiary','',NULL,NULL,NULL,'2026-02-13 22:31:30','1','1');
INSERT INTO `messages` VALUES ('434','281','admin','14','Salut vieux, tu dois bosser now','',NULL,NULL,NULL,'2026-02-13 22:48:44','1','1');
INSERT INTO `messages` VALUES ('435','276','enseignant','17','Salut','',NULL,NULL,NULL,'2026-02-13 22:51:48','1','1');
INSERT INTO `messages` VALUES ('436','276','admin','19','oui je t\'écoute','',NULL,NULL,NULL,'2026-02-13 22:52:45','1','1');
INSERT INTO `messages` VALUES ('437','281','admin','19','salut','',NULL,NULL,NULL,'2026-02-13 23:40:12','1','1');
INSERT INTO `messages` VALUES ('438','277','admin','19','salut','',NULL,NULL,NULL,'2026-02-13 23:40:26','0','0');
INSERT INTO `messages` VALUES ('439','283','admin','19','[Fichier : ]','','uploads/chat_files/1771018478_698f98eea7b67.jpg','1753117500_9.jpg','38842','2026-02-14 00:34:38','1','1');
INSERT INTO `messages` VALUES ('440','281','admin','19','salut','',NULL,NULL,NULL,'2026-02-14 00:43:28','1','1');
INSERT INTO `messages` VALUES ('441','281','admin','14','heye','',NULL,NULL,NULL,'2026-02-14 00:43:54','1','1');
INSERT INTO `messages` VALUES ('442','281','admin','19','quooi','',NULL,NULL,NULL,'2026-02-14 00:44:07','1','1');
INSERT INTO `messages` VALUES ('443','281','admin','19','répond bon sang','',NULL,NULL,NULL,'2026-02-14 00:44:21','1','1');
INSERT INTO `messages` VALUES ('444','281','admin','14','tu veux quoi toi?','',NULL,NULL,NULL,'2026-02-14 00:44:42','1','1');
INSERT INTO `messages` VALUES ('445','266','staff','20','salut stessy','',NULL,NULL,NULL,'2026-02-14 00:45:31','1','1');
INSERT INTO `messages` VALUES ('446','266','admin','19','t\'es qui toi?','',NULL,NULL,NULL,'2026-02-14 00:45:47','1','1');
INSERT INTO `messages` VALUES ('447','266','admin','19','[Image: 1753117403_5.jpg]','','uploads/chat_files/1771019172_698f9ba453f59.jpg','1753117403_5.jpg','39833','2026-02-14 00:46:12','1','1');
INSERT INTO `messages` VALUES ('448','266','staff','20','oui jte connais','',NULL,NULL,NULL,'2026-02-14 00:47:43','1','1');
INSERT INTO `messages` VALUES ('449','266','staff','20','hey','',NULL,NULL,NULL,'2026-02-14 00:48:11','1','1');
INSERT INTO `messages` VALUES ('450','271','staff','20','c\'est trop','',NULL,NULL,NULL,'2026-02-14 00:51:08','1','1');
INSERT INTO `messages` VALUES ('451','271','admin','19','ouii','',NULL,NULL,NULL,'2026-02-14 00:53:58','1','1');
INSERT INTO `messages` VALUES ('452','266','staff','20','stessy','',NULL,NULL,NULL,'2026-02-14 00:54:47','1','1');
INSERT INTO `messages` VALUES ('453','281','admin','14','steassy?','',NULL,NULL,NULL,'2026-02-14 00:55:58','1','1');
INSERT INTO `messages` VALUES ('454','281','admin','19','ouii','',NULL,NULL,NULL,'2026-02-14 00:56:21','1','1');
INSERT INTO `messages` VALUES ('455','276','enseignant','17','[Image: 1753116449_7.jpg]','','uploads/chat_files/1771062126_6990436eca8a1.jpg','1753116449_7.jpg','40611','2026-02-14 12:42:06','1','1');
INSERT INTO `messages` VALUES ('456','271','admin','19','[Image envoyée]','','uploads/chat_files/1771067801_69905999a97cd.jpg','1754583486_DSC_5326.jpg','1081334','2026-02-14 14:16:41','1','1');
INSERT INTO `messages` VALUES ('457','283','admin','19','SALUT VIEUX','',NULL,NULL,NULL,'2026-02-14 14:18:31','1','1');
INSERT INTO `messages` VALUES ('458','271','admin','19','hey','',NULL,NULL,NULL,'2026-02-14 14:20:39','1','1');
INSERT INTO `messages` VALUES ('459','281','admin','19','test','',NULL,NULL,NULL,'2026-02-14 14:35:58','1','1');
INSERT INTO `messages` VALUES ('460','281','admin','19','test','',NULL,NULL,NULL,'2026-02-14 14:36:08','1','1');
INSERT INTO `messages` VALUES ('461','281','admin','14','tes','',NULL,NULL,NULL,'2026-02-14 14:36:29','1','1');
INSERT INTO `messages` VALUES ('462','281','admin','19','test','',NULL,NULL,NULL,'2026-02-14 14:36:33','1','1');
INSERT INTO `messages` VALUES ('463','281','admin','19','[Image: 1754583486_DSC_5326.jpg]','','uploads/chat_files/1771069014_69905e56435a4.jpg','1754583486_DSC_5326.jpg','1081334','2026-02-14 14:36:54','1','1');
INSERT INTO `messages` VALUES ('464','295','enseignant','17','test','',NULL,NULL,NULL,'2026-02-14 14:38:22','1','1');
INSERT INTO `messages` VALUES ('465','256','admin','14','Manaja','',NULL,NULL,NULL,'2026-02-14 14:39:20','1','1');
INSERT INTO `messages` VALUES ('466','256','enseignant','15','oui','',NULL,NULL,NULL,'2026-02-14 14:39:33','1','1');
INSERT INTO `messages` VALUES ('467','279','admin','14','kimmmm','',NULL,NULL,NULL,'2026-02-14 15:02:50','1','1');
INSERT INTO `messages` VALUES ('468','276','admin','19','Belle photo','',NULL,NULL,NULL,'2026-02-14 15:03:44','1','1');
INSERT INTO `messages` VALUES ('469','276','admin','19','wow','',NULL,NULL,NULL,'2026-02-14 15:03:59','1','1');
INSERT INTO `messages` VALUES ('470','276','enseignant','17','verifie','',NULL,NULL,NULL,'2026-02-14 15:04:35','1','1');
INSERT INTO `messages` VALUES ('471','276','admin','19','ouii','',NULL,NULL,NULL,'2026-02-14 15:04:43','1','1');
INSERT INTO `messages` VALUES ('472','279','enseignant','17','iuiiiiiu','',NULL,NULL,NULL,'2026-02-14 15:04:54','1','1');
INSERT INTO `messages` VALUES ('473','276','enseignant','17','ouii','',NULL,NULL,NULL,'2026-02-14 15:05:18','1','1');
INSERT INTO `messages` VALUES ('474','258','admin','16','heyyy','',NULL,NULL,NULL,'2026-02-14 15:06:14','1','1');
INSERT INTO `messages` VALUES ('475','257','admin','16','kimmmm','',NULL,NULL,NULL,'2026-02-14 15:06:25','1','1');
INSERT INTO `messages` VALUES ('476','257','admin','16','[Image: 1753787640_DSC_5225.jpg]','','uploads/chat_files/1771071420_699067bc1f2cb.jpg','1753787640_DSC_5225.jpg','873491','2026-02-14 15:17:00','1','1');
INSERT INTO `messages` VALUES ('477','271','admin','16','hey','',NULL,NULL,NULL,'2026-02-14 15:30:48','1','0');
INSERT INTO `messages` VALUES ('478','271','admin','16','coollllll','',NULL,NULL,NULL,'2026-02-14 15:31:01','1','0');
INSERT INTO `messages` VALUES ('479','271','admin','16','derrr','',NULL,NULL,NULL,'2026-02-14 15:31:14','1','0');
INSERT INTO `messages` VALUES ('480','271','enseignant','15','hery','',NULL,NULL,NULL,'2026-02-15 12:35:57','1','1');
INSERT INTO `messages` VALUES ('481','271','admin','16','[Image envoyée]','','uploads/chat_files/1771262907_699353bbdca5a.jpg','1753117465_3.jpg','43057','2026-02-16 20:28:27','1','0');
INSERT INTO `messages` VALUES ('482','275','admin','16','Vieux','',NULL,NULL,NULL,'2026-02-16 20:36:35','1','1');
INSERT INTO `messages` VALUES ('483','275','admin','16','[Image: apropos.jpg]','','uploads/chat_files/1771263415_699355b7c7d79.jpg','apropos.jpg','463529','2026-02-16 20:36:55','1','1');
INSERT INTO `messages` VALUES ('484','275','admin','16','Derive','',NULL,NULL,NULL,'2026-02-17 06:49:18','1','1');
INSERT INTO `messages` VALUES ('485','275','admin','16','quoi?','',NULL,NULL,NULL,'2026-02-17 06:49:33','1','1');
INSERT INTO `messages` VALUES ('486','275','admin','16','serey','',NULL,NULL,NULL,'2026-02-17 06:49:49','1','1');
INSERT INTO `messages` VALUES ('487','271','staff','20','Guys','',NULL,NULL,NULL,'2026-02-17 06:59:28','1','1');
INSERT INTO `messages` VALUES ('488','263','staff','20','Hey diary','',NULL,NULL,NULL,'2026-02-17 07:02:57','1','1');
INSERT INTO `messages` VALUES ('489','263','staff','20','salut','',NULL,NULL,NULL,'2026-02-17 07:03:06','1','1');
INSERT INTO `messages` VALUES ('490','263','staff','20','oui ça marche c\'est rapide','',NULL,NULL,NULL,'2026-02-17 07:03:20','1','1');
INSERT INTO `messages` VALUES ('491','275','admin','14','merci pour votre message','',NULL,NULL,NULL,'2026-02-17 07:04:04','1','1');
INSERT INTO `messages` VALUES ('492','261','admin','14','ouii jecrois que c\'est ici','',NULL,NULL,NULL,'2026-02-17 07:04:39','0','0');
INSERT INTO `messages` VALUES ('493','261','admin','14','c\'est ci','',NULL,NULL,NULL,'2026-02-17 07:04:49','0','0');
INSERT INTO `messages` VALUES ('494','275','admin','14','merci encore','',NULL,NULL,NULL,'2026-02-17 07:06:02','1','1');
INSERT INTO `messages` VALUES ('495','275','admin','14','merci encore','',NULL,NULL,NULL,'2026-02-17 07:06:12','1','1');
INSERT INTO `messages` VALUES ('496','275','admin','14','thanks','',NULL,NULL,NULL,'2026-02-17 07:06:23','1','1');
INSERT INTO `messages` VALUES ('497','275','admin','14','thanks','',NULL,NULL,NULL,'2026-02-17 07:07:56','1','1');
INSERT INTO `messages` VALUES ('498','275','admin','14','a lot','',NULL,NULL,NULL,'2026-02-17 07:08:01','1','1');
INSERT INTO `messages` VALUES ('499','275','admin','16','okay','',NULL,NULL,NULL,'2026-02-17 07:08:27','1','1');
INSERT INTO `messages` VALUES ('500','275','admin','14','ouiii','',NULL,NULL,NULL,'2026-02-17 07:10:39','1','1');
INSERT INTO `messages` VALUES ('501','275','admin','16','okey','',NULL,NULL,NULL,'2026-02-17 07:10:45','1','1');
INSERT INTO `messages` VALUES ('502','275','admin','16','quoi','',NULL,NULL,NULL,'2026-02-17 07:10:53','1','1');
INSERT INTO `messages` VALUES ('503','275','admin','16','quoi','',NULL,NULL,NULL,'2026-02-17 07:10:57','1','1');
INSERT INTO `messages` VALUES ('504','275','admin','14','okey','',NULL,NULL,NULL,'2026-02-17 07:11:03','1','1');
INSERT INTO `messages` VALUES ('505','258','admin','19','terr','',NULL,NULL,NULL,'2026-02-18 09:35:50','1','1');
INSERT INTO `messages` VALUES ('506','258','admin','19','ouii','',NULL,NULL,NULL,'2026-02-18 09:37:25','1','1');
INSERT INTO `messages` VALUES ('507','258','admin','19','tes','',NULL,NULL,NULL,'2026-02-18 10:05:20','1','1');
INSERT INTO `messages` VALUES ('508','258','admin','16','saut','',NULL,NULL,NULL,'2026-02-18 10:08:24','1','1');
INSERT INTO `messages` VALUES ('509','258','admin','19','ouiiisalut','',NULL,NULL,NULL,'2026-02-18 10:08:40','1','1');
INSERT INTO `messages` VALUES ('510','258','admin','19','non je test','',NULL,NULL,NULL,'2026-02-18 10:10:09','1','1');
INSERT INTO `messages` VALUES ('511','258','admin','19','salutt','',NULL,NULL,NULL,'2026-02-18 10:20:26','1','1');
INSERT INTO `messages` VALUES ('512','258','admin','19','je suis sur que ça marche','',NULL,NULL,NULL,'2026-02-18 10:20:41','1','1');
INSERT INTO `messages` VALUES ('513','258','admin','16','tu crois vraiment','',NULL,NULL,NULL,'2026-02-18 10:20:50','1','1');
INSERT INTO `messages` VALUES ('514','258','admin','19','et si je tes','',NULL,NULL,NULL,'2026-02-18 10:21:17','1','1');
INSERT INTO `messages` VALUES ('515','276','admin','19','[File: MY ENGLISH.docx]','','uploads/chat_files/1771399323_6995689b0b3cf.docx','MY ENGLISH.docx','17123','2026-02-18 10:22:03','1','1');
INSERT INTO `messages` VALUES ('516','276','admin','19','[Image: er.png]','','uploads/chat_files/1771399335_699568a7b2f4d.png','er.png','364169','2026-02-18 10:22:15','1','1');
INSERT INTO `messages` VALUES ('517','276','enseignant','17','c\'est parfait','',NULL,NULL,NULL,'2026-02-18 10:23:04','1','1');
INSERT INTO `messages` VALUES ('518','276','admin','19','d\'accord','',NULL,NULL,NULL,'2026-02-18 10:23:17','1','1');
INSERT INTO `messages` VALUES ('519','271','admin','19','ouiii','',NULL,NULL,NULL,'2026-02-18 10:28:36','1','1');
INSERT INTO `messages` VALUES ('520','271','enseignant','17','quoiioi?','',NULL,NULL,NULL,'2026-02-18 10:29:13','1','1');
INSERT INTO `messages` VALUES ('521','271','enseignant','17','[Image envoyée]','','uploads/chat_files/1771399764_69956a543807c.jpg','1753117403_5.jpg','39833','2026-02-18 10:29:24','1','1');
INSERT INTO `messages` VALUES ('522','271','enseignant','17','eexxx','',NULL,NULL,NULL,'2026-02-18 10:32:33','1','1');
INSERT INTO `messages` VALUES ('523','276','enseignant','17','okay','',NULL,NULL,NULL,'2026-02-18 10:33:03','1','1');
INSERT INTO `messages` VALUES ('524','275','admin','14','Salut tojo','',NULL,NULL,NULL,'2026-02-20 08:29:33','1','1');
INSERT INTO `messages` VALUES ('525','275','admin','14','T\'es disponible aujourd\'hui?','',NULL,NULL,NULL,'2026-02-20 08:29:52','1','1');
INSERT INTO `messages` VALUES ('526','275','admin','14','oui je suis','',NULL,NULL,NULL,'2026-02-20 08:30:06','1','1');
INSERT INTO `messages` VALUES ('527','275','admin','14','pourquoi?','',NULL,NULL,NULL,'2026-02-20 08:30:10','1','1');
INSERT INTO `messages` VALUES ('528','275','admin','16','non c\'est un truc important','',NULL,NULL,NULL,'2026-02-20 08:30:43','1','1');
INSERT INTO `messages` VALUES ('529','275','admin','16','[Image: COREE.png]','','uploads/chat_files/1771565470_6997f19ebea9e.png','COREE.png','570350','2026-02-20 08:31:10','1','1');
INSERT INTO `messages` VALUES ('530','271','admin','16','yess','',NULL,NULL,NULL,'2026-02-20 08:32:25','1','0');
INSERT INTO `messages` VALUES ('531','276','admin','19','A la prochaine fois mon vieux','',NULL,NULL,NULL,'2026-02-26 16:15:48','1','1');
INSERT INTO `messages` VALUES ('532','276','admin','19','En esperant de te voir à merveille','',NULL,NULL,NULL,'2026-02-26 16:16:00','1','1');
INSERT INTO `messages` VALUES ('533','276','admin','19','prend soin de toi','',NULL,NULL,NULL,'2026-02-26 16:16:07','1','1');
INSERT INTO `messages` VALUES ('534','276','admin','19','[Image: geralt-ai-generated-9811472_1280.jpg]','','uploads/chat_files/1772111776_69a047a082b7c.jpg','geralt-ai-generated-9811472_1280.jpg','18177','2026-02-26 16:16:16','1','1');
INSERT INTO `messages` VALUES ('535','276','enseignant','17','Merci pour otre retour, ça me fait énormement plaisir de partager cette idée avec vous,','',NULL,NULL,NULL,'2026-02-26 16:19:02','1','1');
INSERT INTO `messages` VALUES ('536','276','admin','19','je vous en prie, a la prochaine fois','',NULL,NULL,NULL,'2026-02-26 16:19:34','1','1');
INSERT INTO `messages` VALUES ('537','276','enseignant','17','[Image: er.png]','','uploads/chat_files/1772112000_69a04880a144a.png','er.png','317450','2026-02-26 16:20:00','1','1');
INSERT INTO `messages` VALUES ('538','276','admin','19','[Image: ertt.png]','','uploads/chat_files/1772112025_69a04899d77b3.png','ertt.png','270138','2026-02-26 16:20:25','1','1');
INSERT INTO `messages` VALUES ('539','279','admin','14','kioooo','',NULL,NULL,NULL,'2026-02-26 20:38:38','0','0');
INSERT INTO `messages` VALUES ('540','281','admin','19','salut','',NULL,NULL,NULL,'2026-02-28 00:23:21','1','1');
INSERT INTO `messages` VALUES ('541','281','admin','14','salut','',NULL,NULL,NULL,'2026-02-28 00:23:36','0','0');
INSERT INTO `messages` VALUES ('542','281','admin','19','oui salut a belle','',NULL,NULL,NULL,'2026-02-28 00:23:46','1','1');
INSERT INTO `messages` VALUES ('543','275','admin','16','SALUT MON VIEUX','',NULL,NULL,NULL,'2026-02-28 15:33:01','1','1');
INSERT INTO `messages` VALUES ('544','275','admin','14','Salut ça va?','',NULL,NULL,NULL,'2026-02-28 15:33:18','1','1');
INSERT INTO `messages` VALUES ('545','275','admin','14','[File: La meilleure manière de faire de l.docx]','','uploads/chat_files/1772282014_69a2e09ed75ce.docx','La meilleure manière de faire de l.docx','15136','2026-02-28 15:33:34','1','1');
INSERT INTO `messages` VALUES ('546','275','admin','14','ouiii','','uploads/chat_files/1772282026_69a2e0aa758f2.docx','La meilleure manière de faire de l.docx','15136','2026-02-28 15:33:46','1','1');
INSERT INTO `messages` VALUES ('547','256','admin','14','salut','',NULL,NULL,NULL,'2026-03-08 21:14:06','0','0');
INSERT INTO `messages` VALUES ('548','258','admin','16','Salut stessy','',NULL,NULL,NULL,'2026-04-20 19:54:47','0','0');
INSERT INTO `messages` VALUES ('549','274','admin','16','salut tout le monde','text',NULL,NULL,NULL,'2026-05-04 12:11:08','0','0');
INSERT INTO `messages` VALUES ('550','271','admin','16','oui je test','text',NULL,NULL,NULL,'2026-05-04 12:11:18','0','0');
INSERT INTO `messages` VALUES ('553','258','admin','16','[Fichier : ANGLAIS LS I-II-III TRIM II.pdf]','file','uploads/chat_files/1777897404_cypwluaYWfFZ7Ggg.pdf','ANGLAIS LS I-II-III TRIM II.pdf','129561','2026-05-04 12:23:24','0','0');
INSERT INTO `messages` VALUES ('554','258','admin','16','[Fichier : ANGLAIS LS I-II-III TRIM II.pdf]','file','uploads/chat_files/1777897406_TCDkO4sKRa8I5Ndl.pdf','ANGLAIS LS I-II-III TRIM II.pdf','129561','2026-05-04 12:23:26','0','0');
INSERT INTO `messages` VALUES ('555','283','admin','16','test','text',NULL,NULL,NULL,'2026-05-04 12:24:27','0','0');
INSERT INTO `messages` VALUES ('556','283','admin','16','[Image envoyee]','image','uploads/chat_files/1777897671_VeH9DPwk3VxZzZi6.png','FARANY.png','311588','2026-05-04 12:27:51','0','0');
INSERT INTO `messages` VALUES ('557','283','admin','16','[Fichier : ANGLAIS LS I-II-III TRIM II.pdf]','file','uploads/chat_files/1777897717_duoOOyyvJaZwn2sC.pdf','ANGLAIS LS I-II-III TRIM II.pdf','129561','2026-05-04 12:28:37','0','0');
INSERT INTO `messages` VALUES ('558','258','admin','16','test envoie','image','uploads/chat_files/1777897744_fqtcuOOdEe3Nko56.jpg','008ee79c-c233-439b-8491-6b54896b65ae.jpg','294339','2026-05-04 12:29:04','0','0');


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
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `mpiasa` VALUES ('4','RANDRIAMBOLANIAINA','Fitahiantsoa Fitia','professeur','fitia@gmail.com','0342565825','2025-2026','2025-11-01 10:26:52');
INSERT INTO `mpiasa` VALUES ('5','RANDRIAMBOLANIAINA','Avotra Fenosoa','professeur','fia@gmail.com','0342565821','2025-2026','2025-11-01 10:27:39');
INSERT INTO `mpiasa` VALUES ('6','RANDRIAMBOLANIAINA','Avotra Fenosoa','staff','fa@gmail.com','0342565820','2025-2026','2025-11-01 10:45:12');
INSERT INTO `mpiasa` VALUES ('7','ANDRIAMBOLANIAINA','Fenosoa','staff','fiane@gmail.com','0342565821','2025-2026','2025-11-01 10:46:25');
INSERT INTO `mpiasa` VALUES ('8','EDWARD','Tojo Victoire','professeur','trandriamifalyH@gmail.com','0387729954','2025-2026','2026-05-04 14:47:39');


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
) ENGINE=InnoDB AUTO_INCREMENT=6732 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `notes` VALUES ('6501','286','20','15',NULL,'1',NULL,'2025-2026','T1','regular');
INSERT INTO `notes` VALUES ('6502','286','16','16',NULL,'1',NULL,'2025-2026','T1','regular');
INSERT INTO `notes` VALUES ('6503','286','17','12',NULL,'1',NULL,'2025-2026','T1','regular');
INSERT INTO `notes` VALUES ('6504','286','3','14',NULL,'1',NULL,'2025-2026','T1','regular');
INSERT INTO `notes` VALUES ('6505','286','2','15',NULL,'1',NULL,'2025-2026','T1','regular');
INSERT INTO `notes` VALUES ('6506','286','21','14',NULL,'1',NULL,'2025-2026','T1','regular');
INSERT INTO `notes` VALUES ('6507','302','16','12',NULL,'1',NULL,'2025-2026','B1','regular');
INSERT INTO `notes` VALUES ('6508','302','3','14',NULL,'1',NULL,'2025-2026','B1','regular');
INSERT INTO `notes` VALUES ('6509','302','2','11',NULL,'1',NULL,'2025-2026','B1','regular');
INSERT INTO `notes` VALUES ('6510','302','5','10.5',NULL,'1',NULL,'2025-2026','B1','regular');
INSERT INTO `notes` VALUES ('6511','302','7','8',NULL,'1',NULL,'2025-2026','B1','regular');
INSERT INTO `notes` VALUES ('6512','301','16','15',NULL,'1',NULL,'2025-2026','B1','regular');
INSERT INTO `notes` VALUES ('6513','301','3','16',NULL,'1',NULL,'2025-2026','B1','regular');
INSERT INTO `notes` VALUES ('6514','301','2','13.5',NULL,'1',NULL,'2025-2026','B1','regular');
INSERT INTO `notes` VALUES ('6515','301','5','9.5',NULL,'1',NULL,'2025-2026','B1','regular');
INSERT INTO `notes` VALUES ('6516','301','7','14',NULL,'1',NULL,'2025-2026','B1','regular');
INSERT INTO `notes` VALUES ('6517','303','16','11',NULL,'1',NULL,'2025-2026','B1','regular');
INSERT INTO `notes` VALUES ('6518','303','3','13',NULL,'1',NULL,'2025-2026','B1','regular');
INSERT INTO `notes` VALUES ('6519','303','2','10',NULL,'1',NULL,'2025-2026','B1','regular');
INSERT INTO `notes` VALUES ('6520','303','5','5',NULL,'1',NULL,'2025-2026','B1','regular');
INSERT INTO `notes` VALUES ('6521','303','7','14',NULL,'1',NULL,'2025-2026','B1','regular');
INSERT INTO `notes` VALUES ('6522','297','16','15',NULL,'1',NULL,'2025-2026','B1','regular');
INSERT INTO `notes` VALUES ('6523','297','3','14',NULL,'1',NULL,'2025-2026','B1','regular');
INSERT INTO `notes` VALUES ('6524','297','2','11',NULL,'1',NULL,'2025-2026','B1','regular');
INSERT INTO `notes` VALUES ('6525','297','5','13',NULL,'1',NULL,'2025-2026','B1','regular');
INSERT INTO `notes` VALUES ('6526','297','7','11',NULL,'1',NULL,'2025-2026','B1','regular');
INSERT INTO `notes` VALUES ('6527','299','16','11',NULL,'1',NULL,'2025-2026','B1','regular');
INSERT INTO `notes` VALUES ('6528','299','3','14',NULL,'1',NULL,'2025-2026','B1','regular');
INSERT INTO `notes` VALUES ('6529','299','2','12',NULL,'1',NULL,'2025-2026','B1','regular');
INSERT INTO `notes` VALUES ('6530','299','5','11',NULL,'1',NULL,'2025-2026','B1','regular');
INSERT INTO `notes` VALUES ('6531','299','7','12',NULL,'1',NULL,'2025-2026','B1','regular');
INSERT INTO `notes` VALUES ('6532','300','16','10.5',NULL,'1',NULL,'2025-2026','B1','regular');
INSERT INTO `notes` VALUES ('6533','300','3','17',NULL,'1',NULL,'2025-2026','B1','regular');
INSERT INTO `notes` VALUES ('6534','300','2','14',NULL,'1',NULL,'2025-2026','B1','regular');
INSERT INTO `notes` VALUES ('6535','300','5','16',NULL,'1',NULL,'2025-2026','B1','regular');
INSERT INTO `notes` VALUES ('6536','300','7','18',NULL,'1',NULL,'2025-2026','B1','regular');
INSERT INTO `notes` VALUES ('6537','298','16','19',NULL,'1',NULL,'2025-2026','B1','regular');
INSERT INTO `notes` VALUES ('6538','298','3','19',NULL,'1',NULL,'2025-2026','B1','regular');
INSERT INTO `notes` VALUES ('6539','298','2','18',NULL,'1',NULL,'2025-2026','B1','regular');
INSERT INTO `notes` VALUES ('6540','298','5','19',NULL,'1',NULL,'2025-2026','B1','regular');
INSERT INTO `notes` VALUES ('6541','298','7','18',NULL,'1',NULL,'2025-2026','B1','regular');
INSERT INTO `notes` VALUES ('6542','296','16','12.5',NULL,'1',NULL,'2025-2026','B1','regular');
INSERT INTO `notes` VALUES ('6543','296','3','13',NULL,'1',NULL,'2025-2026','B1','regular');
INSERT INTO `notes` VALUES ('6544','296','2','14',NULL,'1',NULL,'2025-2026','B1','regular');
INSERT INTO `notes` VALUES ('6545','296','5','11',NULL,'1',NULL,'2025-2026','B1','regular');
INSERT INTO `notes` VALUES ('6546','296','7','12',NULL,'1',NULL,'2025-2026','B1','regular');
INSERT INTO `notes` VALUES ('6547','295','16','18',NULL,'1',NULL,'2025-2026','B1','regular');
INSERT INTO `notes` VALUES ('6548','295','3','19',NULL,'1',NULL,'2025-2026','B1','regular');
INSERT INTO `notes` VALUES ('6549','295','2','18',NULL,'1',NULL,'2025-2026','B1','regular');
INSERT INTO `notes` VALUES ('6550','295','5','19',NULL,'1',NULL,'2025-2026','B1','regular');
INSERT INTO `notes` VALUES ('6551','295','7','18',NULL,'1',NULL,'2025-2026','B1','regular');
INSERT INTO `notes` VALUES ('6552','302','16','10',NULL,'1',NULL,'2025-2026','B2','regular');
INSERT INTO `notes` VALUES ('6553','302','3','15',NULL,'1',NULL,'2025-2026','B2','regular');
INSERT INTO `notes` VALUES ('6554','302','2','14.5',NULL,'1',NULL,'2025-2026','B2','regular');
INSERT INTO `notes` VALUES ('6555','302','5','16',NULL,'1',NULL,'2025-2026','B2','regular');
INSERT INTO `notes` VALUES ('6556','302','7','14',NULL,'1',NULL,'2025-2026','B2','regular');
INSERT INTO `notes` VALUES ('6557','301','16','12.5',NULL,'1',NULL,'2025-2026','B2','regular');
INSERT INTO `notes` VALUES ('6558','301','3','10',NULL,'1',NULL,'2025-2026','B2','regular');
INSERT INTO `notes` VALUES ('6559','301','2','9.5',NULL,'1',NULL,'2025-2026','B2','regular');
INSERT INTO `notes` VALUES ('6560','301','5','8',NULL,'1',NULL,'2025-2026','B2','regular');
INSERT INTO `notes` VALUES ('6561','301','7','11',NULL,'1',NULL,'2025-2026','B2','regular');
INSERT INTO `notes` VALUES ('6562','303','16','12.5',NULL,'1',NULL,'2025-2026','B2','regular');
INSERT INTO `notes` VALUES ('6563','303','3','14',NULL,'1',NULL,'2025-2026','B2','regular');
INSERT INTO `notes` VALUES ('6564','303','2','13',NULL,'1',NULL,'2025-2026','B2','regular');
INSERT INTO `notes` VALUES ('6565','303','5','15',NULL,'1',NULL,'2025-2026','B2','regular');
INSERT INTO `notes` VALUES ('6566','303','7','16',NULL,'1',NULL,'2025-2026','B2','regular');
INSERT INTO `notes` VALUES ('6567','297','16','12',NULL,'1',NULL,'2025-2026','B2','regular');
INSERT INTO `notes` VALUES ('6568','297','3','13.5',NULL,'1',NULL,'2025-2026','B2','regular');
INSERT INTO `notes` VALUES ('6569','297','2','14',NULL,'1',NULL,'2025-2026','B2','regular');
INSERT INTO `notes` VALUES ('6570','297','5','17',NULL,'1',NULL,'2025-2026','B2','regular');
INSERT INTO `notes` VALUES ('6571','297','7','15',NULL,'1',NULL,'2025-2026','B2','regular');
INSERT INTO `notes` VALUES ('6572','299','16','14',NULL,'1',NULL,'2025-2026','B2','regular');
INSERT INTO `notes` VALUES ('6573','299','3','15',NULL,'1',NULL,'2025-2026','B2','regular');
INSERT INTO `notes` VALUES ('6574','299','2','16',NULL,'1',NULL,'2025-2026','B2','regular');
INSERT INTO `notes` VALUES ('6575','299','5','18',NULL,'1',NULL,'2025-2026','B2','regular');
INSERT INTO `notes` VALUES ('6576','299','7','14',NULL,'1',NULL,'2025-2026','B2','regular');
INSERT INTO `notes` VALUES ('6577','300','16','17',NULL,'1',NULL,'2025-2026','B2','regular');
INSERT INTO `notes` VALUES ('6578','300','3','18',NULL,'1',NULL,'2025-2026','B2','regular');
INSERT INTO `notes` VALUES ('6579','300','2','19',NULL,'1',NULL,'2025-2026','B2','regular');
INSERT INTO `notes` VALUES ('6580','300','5','17',NULL,'1',NULL,'2025-2026','B2','regular');
INSERT INTO `notes` VALUES ('6581','300','7','15',NULL,'1',NULL,'2025-2026','B2','regular');
INSERT INTO `notes` VALUES ('6582','298','16','17',NULL,'1',NULL,'2025-2026','B2','regular');
INSERT INTO `notes` VALUES ('6583','298','3','18',NULL,'1',NULL,'2025-2026','B2','regular');
INSERT INTO `notes` VALUES ('6584','298','2','19',NULL,'1',NULL,'2025-2026','B2','regular');
INSERT INTO `notes` VALUES ('6585','298','5','18',NULL,'1',NULL,'2025-2026','B2','regular');
INSERT INTO `notes` VALUES ('6586','298','7','17',NULL,'1',NULL,'2025-2026','B2','regular');
INSERT INTO `notes` VALUES ('6587','296','16','14.5',NULL,'1',NULL,'2025-2026','B2','regular');
INSERT INTO `notes` VALUES ('6588','296','3','16',NULL,'1',NULL,'2025-2026','B2','regular');
INSERT INTO `notes` VALUES ('6589','296','2','14',NULL,'1',NULL,'2025-2026','B2','regular');
INSERT INTO `notes` VALUES ('6590','296','5','13',NULL,'1',NULL,'2025-2026','B2','regular');
INSERT INTO `notes` VALUES ('6591','296','7','11',NULL,'1',NULL,'2025-2026','B2','regular');
INSERT INTO `notes` VALUES ('6592','295','16','13',NULL,'1',NULL,'2025-2026','B2','regular');
INSERT INTO `notes` VALUES ('6593','295','3','16',NULL,'1',NULL,'2025-2026','B2','regular');
INSERT INTO `notes` VALUES ('6594','295','2','18',NULL,'1',NULL,'2025-2026','B2','regular');
INSERT INTO `notes` VALUES ('6595','295','5','17',NULL,'1',NULL,'2025-2026','B2','regular');
INSERT INTO `notes` VALUES ('6596','295','7','15',NULL,'1',NULL,'2025-2026','B2','regular');
INSERT INTO `notes` VALUES ('6597','302','16','15',NULL,'1',NULL,'2025-2026','T1','regular');
INSERT INTO `notes` VALUES ('6598','302','3','16',NULL,'1',NULL,'2025-2026','T1','regular');
INSERT INTO `notes` VALUES ('6599','302','2','12.5',NULL,'1',NULL,'2025-2026','T1','regular');
INSERT INTO `notes` VALUES ('6600','302','5','13',NULL,'1',NULL,'2025-2026','T1','regular');
INSERT INTO `notes` VALUES ('6601','302','7','11',NULL,'1',NULL,'2025-2026','T1','regular');
INSERT INTO `notes` VALUES ('6602','301','16','17',NULL,'1',NULL,'2025-2026','T1','regular');
INSERT INTO `notes` VALUES ('6603','301','3','15',NULL,'1',NULL,'2025-2026','T1','regular');
INSERT INTO `notes` VALUES ('6604','301','2','16',NULL,'1',NULL,'2025-2026','T1','regular');
INSERT INTO `notes` VALUES ('6605','301','5','14',NULL,'1',NULL,'2025-2026','T1','regular');
INSERT INTO `notes` VALUES ('6606','301','7','15',NULL,'1',NULL,'2025-2026','T1','regular');
INSERT INTO `notes` VALUES ('6607','303','16','17',NULL,'1',NULL,'2025-2026','T1','regular');
INSERT INTO `notes` VALUES ('6608','303','3','14',NULL,'1',NULL,'2025-2026','T1','regular');
INSERT INTO `notes` VALUES ('6609','303','2','13',NULL,'1',NULL,'2025-2026','T1','regular');
INSERT INTO `notes` VALUES ('6610','303','5','13.5',NULL,'1',NULL,'2025-2026','T1','regular');
INSERT INTO `notes` VALUES ('6611','303','7','14',NULL,'1',NULL,'2025-2026','T1','regular');
INSERT INTO `notes` VALUES ('6612','297','16','16',NULL,'1',NULL,'2025-2026','T1','regular');
INSERT INTO `notes` VALUES ('6613','297','3','15',NULL,'1',NULL,'2025-2026','T1','regular');
INSERT INTO `notes` VALUES ('6614','297','2','14',NULL,'1',NULL,'2025-2026','T1','regular');
INSERT INTO `notes` VALUES ('6615','297','5','13',NULL,'1',NULL,'2025-2026','T1','regular');
INSERT INTO `notes` VALUES ('6616','297','7','15',NULL,'1',NULL,'2025-2026','T1','regular');
INSERT INTO `notes` VALUES ('6617','299','16','10.5',NULL,'1',NULL,'2025-2026','T1','regular');
INSERT INTO `notes` VALUES ('6618','299','3','14',NULL,'1',NULL,'2025-2026','T1','regular');
INSERT INTO `notes` VALUES ('6619','299','2','12',NULL,'1',NULL,'2025-2026','T1','regular');
INSERT INTO `notes` VALUES ('6620','299','5','13',NULL,'1',NULL,'2025-2026','T1','regular');
INSERT INTO `notes` VALUES ('6621','299','7','11',NULL,'1',NULL,'2025-2026','T1','regular');
INSERT INTO `notes` VALUES ('6622','300','16','14',NULL,'1',NULL,'2025-2026','T1','regular');
INSERT INTO `notes` VALUES ('6623','300','3','14',NULL,'1',NULL,'2025-2026','T1','regular');
INSERT INTO `notes` VALUES ('6624','300','2','15',NULL,'1',NULL,'2025-2026','T1','regular');
INSERT INTO `notes` VALUES ('6625','300','5','16',NULL,'1',NULL,'2025-2026','T1','regular');
INSERT INTO `notes` VALUES ('6626','300','7','14',NULL,'1',NULL,'2025-2026','T1','regular');
INSERT INTO `notes` VALUES ('6627','298','16','18',NULL,'1',NULL,'2025-2026','T1','regular');
INSERT INTO `notes` VALUES ('6628','298','3','19',NULL,'1',NULL,'2025-2026','T1','regular');
INSERT INTO `notes` VALUES ('6629','298','2','19',NULL,'1',NULL,'2025-2026','T1','regular');
INSERT INTO `notes` VALUES ('6630','298','5','19',NULL,'1',NULL,'2025-2026','T1','regular');
INSERT INTO `notes` VALUES ('6631','298','7','19',NULL,'1',NULL,'2025-2026','T1','regular');
INSERT INTO `notes` VALUES ('6632','296','16','14',NULL,'1',NULL,'2025-2026','T1','regular');
INSERT INTO `notes` VALUES ('6633','296','3','11',NULL,'1',NULL,'2025-2026','T1','regular');
INSERT INTO `notes` VALUES ('6634','296','2','12',NULL,'1',NULL,'2025-2026','T1','regular');
INSERT INTO `notes` VALUES ('6635','296','5','11',NULL,'1',NULL,'2025-2026','T1','regular');
INSERT INTO `notes` VALUES ('6636','296','7','13',NULL,'1',NULL,'2025-2026','T1','regular');
INSERT INTO `notes` VALUES ('6637','295','16','10.5',NULL,'1',NULL,'2025-2026','T1','regular');
INSERT INTO `notes` VALUES ('6638','295','3','13',NULL,'1',NULL,'2025-2026','T1','regular');
INSERT INTO `notes` VALUES ('6639','295','2','12',NULL,'1',NULL,'2025-2026','T1','regular');
INSERT INTO `notes` VALUES ('6640','295','5','14',NULL,'1',NULL,'2025-2026','T1','regular');
INSERT INTO `notes` VALUES ('6641','295','7','12.5',NULL,'1',NULL,'2025-2026','T1','regular');
INSERT INTO `notes` VALUES ('6642','302','16','13',NULL,'1',NULL,'2025-2026','T2','regular');
INSERT INTO `notes` VALUES ('6643','302','3','15',NULL,'1',NULL,'2025-2026','T2','regular');
INSERT INTO `notes` VALUES ('6644','302','2','14.5',NULL,'1',NULL,'2025-2026','T2','regular');
INSERT INTO `notes` VALUES ('6645','302','5','16',NULL,'1',NULL,'2025-2026','T2','regular');
INSERT INTO `notes` VALUES ('6646','302','7','14',NULL,'1',NULL,'2025-2026','T2','regular');
INSERT INTO `notes` VALUES ('6647','301','16','13.5',NULL,'1',NULL,'2025-2026','T2','regular');
INSERT INTO `notes` VALUES ('6648','301','3','12',NULL,'1',NULL,'2025-2026','T2','regular');
INSERT INTO `notes` VALUES ('6649','301','2','10.5',NULL,'1',NULL,'2025-2026','T2','regular');
INSERT INTO `notes` VALUES ('6650','301','5','14',NULL,'1',NULL,'2025-2026','T2','regular');
INSERT INTO `notes` VALUES ('6651','301','7','15',NULL,'1',NULL,'2025-2026','T2','regular');
INSERT INTO `notes` VALUES ('6652','303','16','14.5',NULL,'1',NULL,'2025-2026','T2','regular');
INSERT INTO `notes` VALUES ('6653','303','3','11',NULL,'1',NULL,'2025-2026','T2','regular');
INSERT INTO `notes` VALUES ('6654','303','2','10.5',NULL,'1',NULL,'2025-2026','T2','regular');
INSERT INTO `notes` VALUES ('6655','303','5','11',NULL,'1',NULL,'2025-2026','T2','regular');
INSERT INTO `notes` VALUES ('6656','303','7','11',NULL,'1',NULL,'2025-2026','T2','regular');
INSERT INTO `notes` VALUES ('6657','297','16','10.5',NULL,'1',NULL,'2025-2026','T2','regular');
INSERT INTO `notes` VALUES ('6658','297','3','9',NULL,'1',NULL,'2025-2026','T2','regular');
INSERT INTO `notes` VALUES ('6659','297','2','14',NULL,'1',NULL,'2025-2026','T2','regular');
INSERT INTO `notes` VALUES ('6660','297','5','17',NULL,'1',NULL,'2025-2026','T2','regular');
INSERT INTO `notes` VALUES ('6661','297','7','15',NULL,'1',NULL,'2025-2026','T2','regular');
INSERT INTO `notes` VALUES ('6662','299','16','13.5',NULL,'1',NULL,'2025-2026','T2','regular');
INSERT INTO `notes` VALUES ('6663','299','3','11',NULL,'1',NULL,'2025-2026','T2','regular');
INSERT INTO `notes` VALUES ('6664','299','2','13',NULL,'1',NULL,'2025-2026','T2','regular');
INSERT INTO `notes` VALUES ('6665','299','5','9.5',NULL,'1',NULL,'2025-2026','T2','regular');
INSERT INTO `notes` VALUES ('6666','299','7','17',NULL,'1',NULL,'2025-2026','T2','regular');
INSERT INTO `notes` VALUES ('6667','300','16','17.5',NULL,'1',NULL,'2025-2026','T2','regular');
INSERT INTO `notes` VALUES ('6668','300','3','16.5',NULL,'1',NULL,'2025-2026','T2','regular');
INSERT INTO `notes` VALUES ('6669','300','2','14.5',NULL,'1',NULL,'2025-2026','T2','regular');
INSERT INTO `notes` VALUES ('6670','300','5','15.5',NULL,'1',NULL,'2025-2026','T2','regular');
INSERT INTO `notes` VALUES ('6671','300','7','13',NULL,'1',NULL,'2025-2026','T2','regular');
INSERT INTO `notes` VALUES ('6672','298','16','13.5',NULL,'1',NULL,'2025-2026','T2','regular');
INSERT INTO `notes` VALUES ('6673','298','3','18',NULL,'1',NULL,'2025-2026','T2','regular');
INSERT INTO `notes` VALUES ('6674','298','2','17',NULL,'1',NULL,'2025-2026','T2','regular');
INSERT INTO `notes` VALUES ('6675','298','5','16',NULL,'1',NULL,'2025-2026','T2','regular');
INSERT INTO `notes` VALUES ('6676','298','7','14',NULL,'1',NULL,'2025-2026','T2','regular');
INSERT INTO `notes` VALUES ('6677','296','16','15',NULL,'1',NULL,'2025-2026','T2','regular');
INSERT INTO `notes` VALUES ('6678','296','3','15',NULL,'1',NULL,'2025-2026','T2','regular');
INSERT INTO `notes` VALUES ('6679','296','2','15',NULL,'1',NULL,'2025-2026','T2','regular');
INSERT INTO `notes` VALUES ('6680','296','5','14',NULL,'1',NULL,'2025-2026','T2','regular');
INSERT INTO `notes` VALUES ('6681','296','7','12.5',NULL,'1',NULL,'2025-2026','T2','regular');
INSERT INTO `notes` VALUES ('6682','295','16','17',NULL,'1',NULL,'2025-2026','T2','regular');
INSERT INTO `notes` VALUES ('6683','295','3','15',NULL,'1',NULL,'2025-2026','T2','regular');
INSERT INTO `notes` VALUES ('6684','295','2','12.5',NULL,'1',NULL,'2025-2026','T2','regular');
INSERT INTO `notes` VALUES ('6685','295','5','14',NULL,'1',NULL,'2025-2026','T2','regular');
INSERT INTO `notes` VALUES ('6686','295','7','12.5',NULL,'1',NULL,'2025-2026','T2','regular');
INSERT INTO `notes` VALUES ('6687','302','16','13.5',NULL,'1',NULL,'2025-2026','T3','regular');
INSERT INTO `notes` VALUES ('6688','302','3','16',NULL,'1',NULL,'2025-2026','T3','regular');
INSERT INTO `notes` VALUES ('6689','302','2','17',NULL,'1',NULL,'2025-2026','T3','regular');
INSERT INTO `notes` VALUES ('6690','302','5','15.5',NULL,'1',NULL,'2025-2026','T3','regular');
INSERT INTO `notes` VALUES ('6691','302','7','10.5',NULL,'1',NULL,'2025-2026','T3','regular');
INSERT INTO `notes` VALUES ('6692','301','16','11.5',NULL,'1',NULL,'2025-2026','T3','regular');
INSERT INTO `notes` VALUES ('6693','301','3','14.5',NULL,'1',NULL,'2025-2026','T3','regular');
INSERT INTO `notes` VALUES ('6694','301','2','13.5',NULL,'1',NULL,'2025-2026','T3','regular');
INSERT INTO `notes` VALUES ('6695','301','5','14',NULL,'1',NULL,'2025-2026','T3','regular');
INSERT INTO `notes` VALUES ('6696','301','7','15',NULL,'1',NULL,'2025-2026','T3','regular');
INSERT INTO `notes` VALUES ('6697','303','16','13.4',NULL,'1',NULL,'2025-2026','T3','regular');
INSERT INTO `notes` VALUES ('6698','303','3','17',NULL,'1',NULL,'2025-2026','T3','regular');
INSERT INTO `notes` VALUES ('6699','303','2','12',NULL,'1',NULL,'2025-2026','T3','regular');
INSERT INTO `notes` VALUES ('6700','303','5','14',NULL,'1',NULL,'2025-2026','T3','regular');
INSERT INTO `notes` VALUES ('6701','303','7','15',NULL,'1',NULL,'2025-2026','T3','regular');
INSERT INTO `notes` VALUES ('6702','297','16','17',NULL,'1',NULL,'2025-2026','T3','regular');
INSERT INTO `notes` VALUES ('6703','297','3','18',NULL,'1',NULL,'2025-2026','T3','regular');
INSERT INTO `notes` VALUES ('6704','297','2','19',NULL,'1',NULL,'2025-2026','T3','regular');
INSERT INTO `notes` VALUES ('6705','297','5','12.5',NULL,'1',NULL,'2025-2026','T3','regular');
INSERT INTO `notes` VALUES ('6706','297','7','14',NULL,'1',NULL,'2025-2026','T3','regular');
INSERT INTO `notes` VALUES ('6707','299','16','9.5',NULL,'1',NULL,'2025-2026','T3','regular');
INSERT INTO `notes` VALUES ('6708','299','3','11.5',NULL,'1',NULL,'2025-2026','T3','regular');
INSERT INTO `notes` VALUES ('6709','299','2','11',NULL,'1',NULL,'2025-2026','T3','regular');
INSERT INTO `notes` VALUES ('6710','299','5','10.5',NULL,'1',NULL,'2025-2026','T3','regular');
INSERT INTO `notes` VALUES ('6711','299','7','13',NULL,'1',NULL,'2025-2026','T3','regular');
INSERT INTO `notes` VALUES ('6712','300','16','14.5',NULL,'1',NULL,'2025-2026','T3','regular');
INSERT INTO `notes` VALUES ('6713','300','3','16',NULL,'1',NULL,'2025-2026','T3','regular');
INSERT INTO `notes` VALUES ('6714','300','2','14',NULL,'1',NULL,'2025-2026','T3','regular');
INSERT INTO `notes` VALUES ('6715','300','5','16',NULL,'1',NULL,'2025-2026','T3','regular');
INSERT INTO `notes` VALUES ('6716','300','7','15',NULL,'1',NULL,'2025-2026','T3','regular');
INSERT INTO `notes` VALUES ('6717','298','16','14.5',NULL,'1',NULL,'2025-2026','T3','regular');
INSERT INTO `notes` VALUES ('6718','298','3','13.5',NULL,'1',NULL,'2025-2026','T3','regular');
INSERT INTO `notes` VALUES ('6719','298','2','14',NULL,'1',NULL,'2025-2026','T3','regular');
INSERT INTO `notes` VALUES ('6720','298','5','14.5',NULL,'1',NULL,'2025-2026','T3','regular');
INSERT INTO `notes` VALUES ('6721','298','7','12.5',NULL,'1',NULL,'2025-2026','T3','regular');
INSERT INTO `notes` VALUES ('6722','296','16','11',NULL,'1',NULL,'2025-2026','T3','regular');
INSERT INTO `notes` VALUES ('6723','296','3','14',NULL,'1',NULL,'2025-2026','T3','regular');
INSERT INTO `notes` VALUES ('6724','296','2','15',NULL,'1',NULL,'2025-2026','T3','regular');
INSERT INTO `notes` VALUES ('6725','296','5','13.5',NULL,'1',NULL,'2025-2026','T3','regular');
INSERT INTO `notes` VALUES ('6726','296','7','14',NULL,'1',NULL,'2025-2026','T3','regular');
INSERT INTO `notes` VALUES ('6727','295','16','16',NULL,'1',NULL,'2025-2026','T3','regular');
INSERT INTO `notes` VALUES ('6728','295','3','15',NULL,'1',NULL,'2025-2026','T3','regular');
INSERT INTO `notes` VALUES ('6729','295','2','14',NULL,'1',NULL,'2025-2026','T3','regular');
INSERT INTO `notes` VALUES ('6730','295','5','12.5',NULL,'1',NULL,'2025-2026','T3','regular');
INSERT INTO `notes` VALUES ('6731','295','7','13',NULL,'1',NULL,'2025-2026','T3','regular');


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
) ENGINE=InnoDB AUTO_INCREMENT=229 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `notifications` VALUES ('160','Paiement','💰 Paiement écolage enregistré pour ANIO Fyh.',NULL,'2026-02-07 19:46:38','','2026-02-07 19:46:38','1');
INSERT INTO `notifications` VALUES ('163','edt','Emploi du temps mis à jour pour la classe 1ère',NULL,'2026-02-17 17:34:20','','2026-02-17 17:34:20','1');
INSERT INTO `notifications` VALUES ('169','Paiement','💰 Paiement écolage enregistré pour ANIO Fery.',NULL,'2026-02-18 05:43:06','','2026-02-18 05:43:06','1');
INSERT INTO `notifications` VALUES ('170','Paiement','💰 Paiement écolage enregistré pour ANIO Fanih.',NULL,'2026-02-18 07:01:09','','2026-02-18 07:01:09','1');
INSERT INTO `notifications` VALUES ('171','presence','Checking de présence d\'un enseignant effectué le 2026-02-18 ',NULL,'2026-02-18 16:21:57','','2026-02-18 16:21:57','1');
INSERT INTO `notifications` VALUES ('172','presence','Checking de présence d\'un enseignant effectué le 2026-02-18 ',NULL,'2026-02-18 16:23:33','','2026-02-18 16:23:33','1');
INSERT INTO `notifications` VALUES ('173','presence','Checking de présence d\'un enseignant effectué le 2026-02-18 ',NULL,'2026-02-18 16:24:41','','2026-02-18 16:24:41','1');
INSERT INTO `notifications` VALUES ('174','presence','Checking de présence d\'un enseignant effectué le 2026-02-18 ',NULL,'2026-02-18 16:26:04','','2026-02-18 16:26:04','1');
INSERT INTO `notifications` VALUES ('175','Paiement','💰 Paiement écolage enregistré pour ANIO Faniahy.',NULL,'2026-02-18 16:27:19','','2026-02-18 16:27:19','1');
INSERT INTO `notifications` VALUES ('176','Paiement','💰 Paiement écolage enregistré pour ANIO Faniho.',NULL,'2026-02-18 16:27:33','','2026-02-18 16:27:33','1');
INSERT INTO `notifications` VALUES ('177','edt','Emploi du temps mis à jour pour la classe 1ère',NULL,'2026-02-20 07:05:25','','2026-02-20 07:05:25','1');
INSERT INTO `notifications` VALUES ('178','dossier','Nouveau dossier déposé : Anio HENRY (eleve)',NULL,'2026-02-26 19:28:40','','2026-02-26 19:28:40','1');
INSERT INTO `notifications` VALUES ('179','dossier','Nouveau dossier déposé : Anio HENRY (eleve)',NULL,'2026-02-26 19:29:22','','2026-02-26 19:29:22','1');
INSERT INTO `notifications` VALUES ('180','dossier','Nouveau dossier déposé : Anio HENRY (eleve)',NULL,'2026-02-26 19:30:52','','2026-02-26 19:30:52','1');
INSERT INTO `notifications` VALUES ('181','dossier','Nouveau dossier déposé : Mathieu (enseignant)',NULL,'2026-02-26 19:41:21','','2026-02-26 19:41:21','1');
INSERT INTO `notifications` VALUES ('182','dossier','Nouveau dossier déposé : Fitia (eleve)',NULL,'2026-02-26 19:43:04','','2026-02-26 19:43:04','1');
INSERT INTO `notifications` VALUES ('183','presence','Checking de présence d\'un employé effectué le 2026-02-27 ',NULL,'2026-02-27 13:49:14','','2026-02-27 13:49:14','1');
INSERT INTO `notifications` VALUES ('184','sauvegarde','Nouvelle sauvegarde : backup_20260227_111142.sql',NULL,'2026-02-27 14:11:43','','2026-02-27 14:11:43','1');
INSERT INTO `notifications` VALUES ('185','edt','Emploi du temps mis à jour pour la classe 1ère',NULL,'2026-02-27 15:26:30','','2026-02-27 15:26:30','1');
INSERT INTO `notifications` VALUES ('186','dossier','Nouveau dossier déposé : Faly ANDRIA (eleve)',NULL,'2026-02-27 18:15:33','','2026-02-27 18:15:33','1');
INSERT INTO `notifications` VALUES ('187','salle','Nouvelle salle ajoutée : Salle de Réunion',NULL,'2026-02-27 18:30:26','','2026-02-27 18:30:26','1');
INSERT INTO `notifications` VALUES ('188','edt','Emploi du temps mis à jour pour la classe 1ère',NULL,'2026-02-27 19:31:08','','2026-02-27 19:31:08','1');
INSERT INTO `notifications` VALUES ('189','presence','Checking de présence d\'un employé effectué le 2026-02-23 ',NULL,'2026-02-27 21:24:24','','2026-02-27 21:24:24','1');
INSERT INTO `notifications` VALUES ('190','presence','Checking de présence d\'un employé effectué le 2026-02-24 ',NULL,'2026-02-27 21:25:16','','2026-02-27 21:25:16','1');
INSERT INTO `notifications` VALUES ('191','presence','Checking de présence d\'un employé effectué le 2026-02-25 ',NULL,'2026-02-27 21:25:28','','2026-02-27 21:25:28','1');
INSERT INTO `notifications` VALUES ('192','presence','Checking de présence d\'un employé effectué le 2026-02-26 ',NULL,'2026-02-27 21:25:47','','2026-02-27 21:25:47','1');
INSERT INTO `notifications` VALUES ('193','presence','Checking de présence d\'un employé effectué le 2026-02-27 ',NULL,'2026-02-27 21:25:57','','2026-02-27 21:25:57','1');
INSERT INTO `notifications` VALUES ('194','reservation','Nouvelle réservation ajoutée dans la salle : Salle de Réunion le 2026-03-03 (23:21 - 13:21)',NULL,'2026-02-27 22:21:39','non lu','2026-02-27 22:21:39','1');
INSERT INTO `notifications` VALUES ('195','edt','Emploi du temps mis à jour pour la classe 1ère',NULL,'2026-02-27 23:04:00','','2026-02-27 23:04:00','1');
INSERT INTO `notifications` VALUES ('196','edt','Emploi du temps mis à jour pour la classe 1ère',NULL,'2026-02-27 23:04:05','','2026-02-27 23:04:05','1');
INSERT INTO `notifications` VALUES ('197','edt','Emploi du temps mis à jour pour la classe 1ère',NULL,'2026-02-27 23:04:17','','2026-02-27 23:04:17','1');
INSERT INTO `notifications` VALUES ('198','edt','Emploi du temps mis à jour pour la classe 1ère',NULL,'2026-02-27 23:04:27','','2026-02-27 23:04:27','1');
INSERT INTO `notifications` VALUES ('199','edt','Emploi du temps mis à jour pour la classe 1ère',NULL,'2026-02-27 23:04:47','','2026-02-27 23:04:47','1');
INSERT INTO `notifications` VALUES ('200','edt','Emploi du temps mis à jour pour la classe 1ère',NULL,'2026-02-27 23:08:08','','2026-02-27 23:08:08','1');
INSERT INTO `notifications` VALUES ('201','edt','Emploi du temps mis à jour pour la classe 1ère',NULL,'2026-02-27 23:08:14','','2026-02-27 23:08:14','1');
INSERT INTO `notifications` VALUES ('202','edt','Emploi du temps mis à jour pour la classe 1ère',NULL,'2026-02-27 23:08:20','','2026-02-27 23:08:20','1');
INSERT INTO `notifications` VALUES ('203','edt','Emploi du temps mis à jour pour la classe 1ère',NULL,'2026-02-27 23:12:29','','2026-02-27 23:12:29','1');
INSERT INTO `notifications` VALUES ('204','evenement','Nouvel événement : Sortie récréative du 2026-03-27T23:14 au 2026-03-31T23:15',NULL,'2026-02-27 23:15:12','','2026-02-27 23:15:12','1');
INSERT INTO `notifications` VALUES ('205','evenement','Nouvel événement : Sortie récréative du 2026-03-29T09:00 au 2026-03-29T10:00',NULL,'2026-02-27 23:15:30','','2026-02-27 23:15:30','1');
INSERT INTO `notifications` VALUES ('206','evenement','Nouvel événement : Réunion du 2026-03-05T23:17 au 2026-03-05T13:19',NULL,'2026-02-27 23:18:05','','2026-02-27 23:18:05','1');
INSERT INTO `notifications` VALUES ('207','info','Réunon imprtant now',NULL,'2026-02-27 23:20:02','','2026-02-27 23:20:02','1');
INSERT INTO `notifications` VALUES ('208','dossier','Nouveau dossier déposé : Koto Nandra (eleve)',NULL,'2026-02-27 23:23:14','','2026-02-27 23:23:14','1');
INSERT INTO `notifications` VALUES ('209','presence','Checking de présence d\'un enseignant effectué le 2026-02-27 ',NULL,'2026-02-27 23:31:45','','2026-02-27 23:31:45','1');
INSERT INTO `notifications` VALUES ('210','presence','Checking de présence d\'un employé effectué le 2026-02-27 ',NULL,'2026-02-27 23:34:02','','2026-02-27 23:34:02','1');
INSERT INTO `notifications` VALUES ('211','Paiement','💰 Paiement écolage enregistré pour ANIO Tody.',NULL,'2026-02-27 23:50:33','','2026-02-27 23:50:33','1');
INSERT INTO `notifications` VALUES ('212','Paiement','💰 Paiement écolage enregistré pour ANIO Fano.',NULL,'2026-02-27 23:50:48','','2026-02-27 23:50:48','1');
INSERT INTO `notifications` VALUES ('213','Paiement','💵 Paiement salaire enregistré pour PERSEVERANCE Pain.',NULL,'2026-02-27 23:51:10','','2026-02-27 23:51:10','1');
INSERT INTO `notifications` VALUES ('214','Paiement','💰 Paiement écolage enregistré pour ANIO Fyh.',NULL,'2026-02-27 23:53:59','','2026-02-27 23:53:59','1');
INSERT INTO `notifications` VALUES ('215','edt','Emploi du temps mis à jour pour la classe 1ère',NULL,'2026-02-28 08:06:51','','2026-02-28 08:06:51','1');
INSERT INTO `notifications` VALUES ('216','presence','Checking de présence d\'un enseignant effectué le 2026-02-28 ',NULL,'2026-02-28 08:14:57','','2026-02-28 08:14:57','1');
INSERT INTO `notifications` VALUES ('217','Paiement','💵 Paiement salaire enregistré pour RANDRIAMIFALY Heriniaina.',NULL,'2026-02-28 08:19:20','','2026-02-28 08:19:20','1');
INSERT INTO `notifications` VALUES ('218','sauvegarde','Nouvelle sauvegarde : backup_20260228_052905.sql',NULL,'2026-02-28 08:29:05','','2026-02-28 08:29:05','1');
INSERT INTO `notifications` VALUES ('219','sauvegarde','Nouvelle sauvegarde : backup_20260228_103722.sql',NULL,'2026-02-28 13:37:22','','2026-02-28 13:37:22','1');
INSERT INTO `notifications` VALUES ('222','Paiement','💰 Paiement écolage enregistré pour ANIO Fyh.',NULL,'2026-04-20 19:55:52','','2026-04-20 19:55:52','0');
INSERT INTO `notifications` VALUES ('223','sauvegarde','Nouvelle sauvegarde : backup_20260420_165903.sql',NULL,'2026-04-20 19:59:04','','2026-04-20 19:59:04','0');
INSERT INTO `notifications` VALUES ('225','presence','Checking de presence d\'un enseignant effectue le 2026-05-04',NULL,'2026-05-04 11:49:28','non lu','2026-05-04 11:49:28','0');
INSERT INTO `notifications` VALUES ('226','Paiement','Paiement ecolage enregistre pour ANIO Fano.',NULL,'2026-05-04 12:57:57','non lu','2026-05-04 12:57:57','0');
INSERT INTO `notifications` VALUES ('227','sauvegarde','Nouvelle sauvegarde : backup_20260504_140611.sql',NULL,'2026-05-04 14:06:12','non lu','2026-05-04 14:06:12','0');
INSERT INTO `notifications` VALUES ('228','sauvegarde','Nouvelle sauvegarde : backup_20260504_140853.sql',NULL,'2026-05-04 14:08:54','non lu','2026-05-04 14:08:54','0');


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
) ENGINE=InnoDB AUTO_INCREMENT=280 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `paiements_assignes` VALUES ('247','25','295','paye',NULL,NULL);
INSERT INTO `paiements_assignes` VALUES ('248','25','296','paye',NULL,NULL);
INSERT INTO `paiements_assignes` VALUES ('249','25','297','paye',NULL,NULL);
INSERT INTO `paiements_assignes` VALUES ('250','25','298','paye',NULL,NULL);
INSERT INTO `paiements_assignes` VALUES ('251','25','299','paye',NULL,NULL);
INSERT INTO `paiements_assignes` VALUES ('252','25','300','paye',NULL,NULL);
INSERT INTO `paiements_assignes` VALUES ('253','25','301','paye',NULL,NULL);
INSERT INTO `paiements_assignes` VALUES ('254','25','302','paye',NULL,NULL);
INSERT INTO `paiements_assignes` VALUES ('255','25','303','paye',NULL,NULL);
INSERT INTO `paiements_assignes` VALUES ('256','26','305','paye',NULL,NULL);
INSERT INTO `paiements_assignes` VALUES ('257','26','306','paye',NULL,NULL);
INSERT INTO `paiements_assignes` VALUES ('258','26','307','paye',NULL,NULL);
INSERT INTO `paiements_assignes` VALUES ('259','26','308','non_paye',NULL,NULL);
INSERT INTO `paiements_assignes` VALUES ('260','26','309','non_paye',NULL,NULL);
INSERT INTO `paiements_assignes` VALUES ('261','26','310','non_paye',NULL,NULL);
INSERT INTO `paiements_assignes` VALUES ('262','26','311','non_paye',NULL,NULL);
INSERT INTO `paiements_assignes` VALUES ('263','26','312','non_paye',NULL,NULL);
INSERT INTO `paiements_assignes` VALUES ('264','26','313','non_paye',NULL,NULL);
INSERT INTO `paiements_assignes` VALUES ('271','27','295','paye',NULL,NULL);
INSERT INTO `paiements_assignes` VALUES ('272','27','296','paye',NULL,NULL);
INSERT INTO `paiements_assignes` VALUES ('273','27','297','paye',NULL,NULL);
INSERT INTO `paiements_assignes` VALUES ('274','27','298','non_paye',NULL,NULL);
INSERT INTO `paiements_assignes` VALUES ('275','27','299','non_paye',NULL,NULL);
INSERT INTO `paiements_assignes` VALUES ('276','27','300','non_paye',NULL,NULL);
INSERT INTO `paiements_assignes` VALUES ('277','27','301','non_paye',NULL,NULL);
INSERT INTO `paiements_assignes` VALUES ('278','27','302','non_paye',NULL,NULL);
INSERT INTO `paiements_assignes` VALUES ('279','27','303','non_paye',NULL,NULL);


DROP TABLE IF EXISTS `parametres`;
CREATE TABLE `parametres` (
  `id` int NOT NULL AUTO_INCREMENT,
  `cle` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `valeur` text COLLATE utf8mb4_general_ci,
  PRIMARY KEY (`id`),
  UNIQUE KEY `cle` (`cle`)
) ENGINE=InnoDB AUTO_INCREMENT=212 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `parametres` VALUES ('1','nom_ecole','Lycée Privée Novaskol');
INSERT INTO `parametres` VALUES ('2','adresse_ecole','LOT II E 69 BIS Tsarahonenana');
INSERT INTO `parametres` VALUES ('3','telephone_ecole','+261 38 77 299 58  /  +261 33 91 307 61');
INSERT INTO `parametres` VALUES ('4','email_ecole','novaskol393@gmail.com');
INSERT INTO `parametres` VALUES ('5','logo_ecole','images/logo_68962d67b7770.png');
INSERT INTO `parametres` VALUES ('6','annee_scolaire','2025-2026');
INSERT INTO `parametres` VALUES ('7','date_debut','2025-08-01');
INSERT INTO `parametres` VALUES ('8','date_fin','2026-08-30');
INSERT INTO `parametres` VALUES ('9','mention_passable','10');
INSERT INTO `parametres` VALUES ('10','mention_assez_bien','12');
INSERT INTO `parametres` VALUES ('11','mention_bien','14');
INSERT INTO `parametres` VALUES ('12','mention_tres_bien','16');
INSERT INTO `parametres` VALUES ('13','notifications_mail','1');
INSERT INTO `parametres` VALUES ('14','code_ecole','');
INSERT INTO `parametres` VALUES ('93','dren','ANALAMANGA');
INSERT INTO `parametres` VALUES ('94','cisco','TANA VILLE');
INSERT INTO `parametres` VALUES ('95','zap','V');
INSERT INTO `parametres` VALUES ('96','code_etablissement','101 010 035');
INSERT INTO `parametres` VALUES ('97','tel_etablissement','038 77 299 58');
INSERT INTO `parametres` VALUES ('98','mail_etablissement','novaskol@gmail.com');
INSERT INTO `parametres` VALUES ('99','nb_comment','FENOMOY IZAY TSY FENO AMIN\'NY TARATASY AFAFAHY.');


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
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `parents` VALUES ('2','HERY rrna','N/A','N/A','N/A','Ando RAVOJA','N/A','N/A','N/A','1478523706','2025-2026','2026-02-19 08:03:18');
INSERT INTO `parents` VALUES ('3','HERY naso','N/A','N/A','N/A','Ando RAVOJAS','N/A','N/A','N/A','1478523698','2025-2026','2026-02-19 08:04:21');
INSERT INTO `parents` VALUES ('4','HERY nasolort','N/A','N/A','N/A','Ando RAVOJAL','N/A','N/A','N/A','1478523704','2025-2026','2026-02-19 08:39:39');
INSERT INTO `parents` VALUES ('5','HERY nasolor','N/A','N/A','N/A','Ando RAVOJAH','N/A','N/A','N/A','1478523703','2025-2026','2026-02-19 22:01:36');
INSERT INTO `parents` VALUES ('6','Tojo_pro','0371512214','Trade','Evyan','Reko ANDRY','0345112245','tODEY','eVOYEE','0371415214','2025-2026','2026-02-26 09:48:05');
INSERT INTO `parents` VALUES ('7','HERY nas','N/A','N/A','N/A','Ando RAVOJAE','N/A','N/A','N/A','1478523699','2025-2026','2026-02-28 09:11:23');


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
) ENGINE=InnoDB AUTO_INCREMENT=916 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `permissions` VALUES ('371','15','enseignant','Administration','ecriture');
INSERT INTO `permissions` VALUES ('372','15','enseignant','dashboard','ecriture');
INSERT INTO `permissions` VALUES ('373','15','enseignant','ecole','ecriture');
INSERT INTO `permissions` VALUES ('374','15','enseignant','Admin','aucun');
INSERT INTO `permissions` VALUES ('375','15','enseignant','inscription','aucun');
INSERT INTO `permissions` VALUES ('376','15','enseignant','liste_classes','aucun');
INSERT INTO `permissions` VALUES ('377','15','enseignant','matieres','aucun');
INSERT INTO `permissions` VALUES ('378','15','enseignant','Enseignantss','aucun');
INSERT INTO `permissions` VALUES ('379','15','enseignant','notes','aucun');
INSERT INTO `permissions` VALUES ('380','15','enseignant','bulletin','aucun');
INSERT INTO `permissions` VALUES ('381','15','enseignant','resultats','aucun');
INSERT INTO `permissions` VALUES ('382','15','enseignant','examen_blanc','aucun');
INSERT INTO `permissions` VALUES ('383','15','enseignant','resultats_examen_blanc','aucun');
INSERT INTO `permissions` VALUES ('384','15','enseignant','Pédagogique','aucun');
INSERT INTO `permissions` VALUES ('385','15','enseignant','emploi_temps','aucun');
INSERT INTO `permissions` VALUES ('386','15','enseignant','fiche_presence','aucun');
INSERT INTO `permissions` VALUES ('387','15','enseignant','calendrier','aucun');
INSERT INTO `permissions` VALUES ('388','15','enseignant','notifications','aucun');
INSERT INTO `permissions` VALUES ('389','15','enseignant','cartes','aucun');
INSERT INTO `permissions` VALUES ('390','15','enseignant','depot_dossier','aucun');
INSERT INTO `permissions` VALUES ('391','15','enseignant','fpe','aucun');
INSERT INTO `permissions` VALUES ('392','15','enseignant','RH','aucun');
INSERT INTO `permissions` VALUES ('393','15','enseignant','enseignants','aucun');
INSERT INTO `permissions` VALUES ('394','15','enseignant','staff','aucun');
INSERT INTO `permissions` VALUES ('395','15','enseignant','presence','aucun');
INSERT INTO `permissions` VALUES ('396','15','enseignant','presence_staff','aucun');
INSERT INTO `permissions` VALUES ('397','15','enseignant','permissions','aucun');
INSERT INTO `permissions` VALUES ('398','15','enseignant','gestion_ressource','aucun');
INSERT INTO `permissions` VALUES ('399','15','enseignant','Paiement','aucun');
INSERT INTO `permissions` VALUES ('400','15','enseignant','detail_paiement','aucun');
INSERT INTO `permissions` VALUES ('401','15','enseignant','comptable','aucun');
INSERT INTO `permissions` VALUES ('402','15','enseignant','liste_paiements','aucun');
INSERT INTO `permissions` VALUES ('403','15','enseignant','facture','aucun');
INSERT INTO `permissions` VALUES ('404','15','enseignant','Sectrapport','aucun');
INSERT INTO `permissions` VALUES ('405','15','enseignant','rapport_comptable','aucun');
INSERT INTO `permissions` VALUES ('406','15','enseignant','rapport_presence','aucun');
INSERT INTO `permissions` VALUES ('407','15','enseignant','rapport_staff','aucun');
INSERT INTO `permissions` VALUES ('408','15','enseignant','evaluation_notes','aucun');
INSERT INTO `permissions` VALUES ('409','15','enseignant','Sectrapp','aucun');
INSERT INTO `permissions` VALUES ('410','15','enseignant','parametres','aucun');
INSERT INTO `permissions` VALUES ('411','15','enseignant','sauvegardes','aucun');
INSERT INTO `permissions` VALUES ('412','15','enseignant','Administration','ecriture');
INSERT INTO `permissions` VALUES ('413','15','enseignant','dashboard','ecriture');
INSERT INTO `permissions` VALUES ('414','15','enseignant','ecole','ecriture');
INSERT INTO `permissions` VALUES ('415','15','enseignant','Admin','aucun');
INSERT INTO `permissions` VALUES ('416','15','enseignant','inscription','aucun');
INSERT INTO `permissions` VALUES ('417','15','enseignant','liste_classes','aucun');
INSERT INTO `permissions` VALUES ('418','15','enseignant','matieres','aucun');
INSERT INTO `permissions` VALUES ('419','15','enseignant','Enseignantss','aucun');
INSERT INTO `permissions` VALUES ('420','15','enseignant','notes','aucun');
INSERT INTO `permissions` VALUES ('421','15','enseignant','bulletin','aucun');
INSERT INTO `permissions` VALUES ('422','15','enseignant','resultats','aucun');
INSERT INTO `permissions` VALUES ('423','15','enseignant','examen_blanc','aucun');
INSERT INTO `permissions` VALUES ('424','15','enseignant','resultats_examen_blanc','aucun');
INSERT INTO `permissions` VALUES ('425','15','enseignant','Pédagogique','aucun');
INSERT INTO `permissions` VALUES ('426','15','enseignant','emploi_temps','aucun');
INSERT INTO `permissions` VALUES ('427','15','enseignant','fiche_presence','aucun');
INSERT INTO `permissions` VALUES ('428','15','enseignant','calendrier','aucun');
INSERT INTO `permissions` VALUES ('429','15','enseignant','notifications','aucun');
INSERT INTO `permissions` VALUES ('430','15','enseignant','cartes','aucun');
INSERT INTO `permissions` VALUES ('431','15','enseignant','depot_dossier','aucun');
INSERT INTO `permissions` VALUES ('432','15','enseignant','fpe','aucun');
INSERT INTO `permissions` VALUES ('433','15','enseignant','RH','aucun');
INSERT INTO `permissions` VALUES ('434','15','enseignant','enseignants','aucun');
INSERT INTO `permissions` VALUES ('435','15','enseignant','staff','aucun');
INSERT INTO `permissions` VALUES ('436','15','enseignant','presence','aucun');
INSERT INTO `permissions` VALUES ('437','15','enseignant','presence_staff','aucun');
INSERT INTO `permissions` VALUES ('438','15','enseignant','permissions','aucun');
INSERT INTO `permissions` VALUES ('439','15','enseignant','gestion_ressource','aucun');
INSERT INTO `permissions` VALUES ('440','15','enseignant','Communication','ecriture');
INSERT INTO `permissions` VALUES ('441','15','enseignant','communication','ecriture');
INSERT INTO `permissions` VALUES ('442','15','enseignant','chat_private','ecriture');
INSERT INTO `permissions` VALUES ('443','15','enseignant','chat_group','ecriture');
INSERT INTO `permissions` VALUES ('444','15','enseignant','Paiement','aucun');
INSERT INTO `permissions` VALUES ('445','15','enseignant','detail_paiement','aucun');
INSERT INTO `permissions` VALUES ('446','15','enseignant','comptable','aucun');
INSERT INTO `permissions` VALUES ('447','15','enseignant','liste_paiements','aucun');
INSERT INTO `permissions` VALUES ('448','15','enseignant','facture','aucun');
INSERT INTO `permissions` VALUES ('449','15','enseignant','Sectrapport','aucun');
INSERT INTO `permissions` VALUES ('450','15','enseignant','rapport_comptable','aucun');
INSERT INTO `permissions` VALUES ('451','15','enseignant','rapport_presence','aucun');
INSERT INTO `permissions` VALUES ('452','15','enseignant','rapport_staff','aucun');
INSERT INTO `permissions` VALUES ('453','15','enseignant','evaluation_notes','aucun');
INSERT INTO `permissions` VALUES ('454','15','enseignant','Sectrapp','aucun');
INSERT INTO `permissions` VALUES ('455','15','enseignant','parametres','aucun');
INSERT INTO `permissions` VALUES ('456','15','enseignant','sauvegardes','aucun');
INSERT INTO `permissions` VALUES ('457','15','enseignant','Administration','masquer');
INSERT INTO `permissions` VALUES ('458','15','enseignant','dashboard','masquer');
INSERT INTO `permissions` VALUES ('459','15','enseignant','ecole','masquer');
INSERT INTO `permissions` VALUES ('460','15','enseignant','Admin','masquer');
INSERT INTO `permissions` VALUES ('461','15','enseignant','inscription','masquer');
INSERT INTO `permissions` VALUES ('462','15','enseignant','liste_classes','masquer');
INSERT INTO `permissions` VALUES ('463','15','enseignant','matieres','masquer');
INSERT INTO `permissions` VALUES ('464','15','enseignant','Enseignantss','ecriture');
INSERT INTO `permissions` VALUES ('465','15','enseignant','notes','ecriture');
INSERT INTO `permissions` VALUES ('466','15','enseignant','bulletin','ecriture');
INSERT INTO `permissions` VALUES ('467','15','enseignant','resultats','ecriture');
INSERT INTO `permissions` VALUES ('468','15','enseignant','examen_blanc','ecriture');
INSERT INTO `permissions` VALUES ('469','15','enseignant','resultats_examen_blanc','ecriture');
INSERT INTO `permissions` VALUES ('470','15','enseignant','Pédagogique','ecriture');
INSERT INTO `permissions` VALUES ('471','15','enseignant','emploi_temps','ecriture');
INSERT INTO `permissions` VALUES ('472','15','enseignant','fiche_presence','masquer');
INSERT INTO `permissions` VALUES ('473','15','enseignant','calendrier','masquer');
INSERT INTO `permissions` VALUES ('474','15','enseignant','notifications','masquer');
INSERT INTO `permissions` VALUES ('475','15','enseignant','cartes','masquer');
INSERT INTO `permissions` VALUES ('476','15','enseignant','depot_dossier','masquer');
INSERT INTO `permissions` VALUES ('477','15','enseignant','fpe','masquer');
INSERT INTO `permissions` VALUES ('478','15','enseignant','RH','ecriture');
INSERT INTO `permissions` VALUES ('479','15','enseignant','enseignants','masquer');
INSERT INTO `permissions` VALUES ('480','15','enseignant','staff','masquer');
INSERT INTO `permissions` VALUES ('481','15','enseignant','presence','ecriture');
INSERT INTO `permissions` VALUES ('482','15','enseignant','presence_staff','masquer');
INSERT INTO `permissions` VALUES ('483','15','enseignant','permissions','masquer');
INSERT INTO `permissions` VALUES ('484','15','enseignant','gestion_ressource','masquer');
INSERT INTO `permissions` VALUES ('485','15','enseignant','Communication','ecriture');
INSERT INTO `permissions` VALUES ('486','15','enseignant','communication','ecriture');
INSERT INTO `permissions` VALUES ('487','15','enseignant','chat_private','ecriture');
INSERT INTO `permissions` VALUES ('488','15','enseignant','chat_group','ecriture');
INSERT INTO `permissions` VALUES ('489','15','enseignant','Paiement','masquer');
INSERT INTO `permissions` VALUES ('490','15','enseignant','detail_paiement','masquer');
INSERT INTO `permissions` VALUES ('491','15','enseignant','comptable','masquer');
INSERT INTO `permissions` VALUES ('492','15','enseignant','liste_paiements','masquer');
INSERT INTO `permissions` VALUES ('493','15','enseignant','facture','masquer');
INSERT INTO `permissions` VALUES ('494','15','enseignant','Sectrapport','aucun');
INSERT INTO `permissions` VALUES ('495','15','enseignant','rapport_comptable','masquer');
INSERT INTO `permissions` VALUES ('496','15','enseignant','rapport_presence','masquer');
INSERT INTO `permissions` VALUES ('497','15','enseignant','rapport_staff','masquer');
INSERT INTO `permissions` VALUES ('498','15','enseignant','evaluation_notes','masquer');
INSERT INTO `permissions` VALUES ('499','15','enseignant','Sectrapp','masquer');
INSERT INTO `permissions` VALUES ('500','15','enseignant','parametres','masquer');
INSERT INTO `permissions` VALUES ('501','15','enseignant','sauvegardes','masquer');
INSERT INTO `permissions` VALUES ('502','15','enseignant','Administration','masquer');
INSERT INTO `permissions` VALUES ('503','15','enseignant','dashboard','masquer');
INSERT INTO `permissions` VALUES ('504','15','enseignant','ecole','masquer');
INSERT INTO `permissions` VALUES ('505','15','enseignant','Admin','masquer');
INSERT INTO `permissions` VALUES ('506','15','enseignant','inscription','masquer');
INSERT INTO `permissions` VALUES ('507','15','enseignant','liste_classes','masquer');
INSERT INTO `permissions` VALUES ('508','15','enseignant','matieres','masquer');
INSERT INTO `permissions` VALUES ('509','15','enseignant','Enseignantss','ecriture');
INSERT INTO `permissions` VALUES ('510','15','enseignant','notes','ecriture');
INSERT INTO `permissions` VALUES ('511','15','enseignant','bulletin','ecriture');
INSERT INTO `permissions` VALUES ('512','15','enseignant','resultats','ecriture');
INSERT INTO `permissions` VALUES ('513','15','enseignant','examen_blanc','ecriture');
INSERT INTO `permissions` VALUES ('514','15','enseignant','resultats_examen_blanc','ecriture');
INSERT INTO `permissions` VALUES ('515','15','enseignant','Pédagogique','ecriture');
INSERT INTO `permissions` VALUES ('516','15','enseignant','emploi_temps','ecriture');
INSERT INTO `permissions` VALUES ('517','15','enseignant','fiche_presence','masquer');
INSERT INTO `permissions` VALUES ('518','15','enseignant','calendrier','masquer');
INSERT INTO `permissions` VALUES ('519','15','enseignant','notifications','masquer');
INSERT INTO `permissions` VALUES ('520','15','enseignant','cartes','masquer');
INSERT INTO `permissions` VALUES ('521','15','enseignant','depot_dossier','masquer');
INSERT INTO `permissions` VALUES ('522','15','enseignant','fpe','masquer');
INSERT INTO `permissions` VALUES ('523','15','enseignant','liste_assurance','masquer');
INSERT INTO `permissions` VALUES ('524','15','enseignant','RH','ecriture');
INSERT INTO `permissions` VALUES ('525','15','enseignant','enseignants','masquer');
INSERT INTO `permissions` VALUES ('526','15','enseignant','staff','masquer');
INSERT INTO `permissions` VALUES ('527','15','enseignant','presence','ecriture');
INSERT INTO `permissions` VALUES ('528','15','enseignant','presence_staff','masquer');
INSERT INTO `permissions` VALUES ('529','15','enseignant','permissions','masquer');
INSERT INTO `permissions` VALUES ('530','15','enseignant','gestion_ressource','masquer');
INSERT INTO `permissions` VALUES ('531','15','enseignant','Communication','ecriture');
INSERT INTO `permissions` VALUES ('532','15','enseignant','communication','ecriture');
INSERT INTO `permissions` VALUES ('533','15','enseignant','chat_private','ecriture');
INSERT INTO `permissions` VALUES ('534','15','enseignant','chat_group','ecriture');
INSERT INTO `permissions` VALUES ('535','15','enseignant','Paiement','masquer');
INSERT INTO `permissions` VALUES ('536','15','enseignant','detail_paiement','masquer');
INSERT INTO `permissions` VALUES ('537','15','enseignant','comptable','masquer');
INSERT INTO `permissions` VALUES ('538','15','enseignant','liste_paiements','masquer');
INSERT INTO `permissions` VALUES ('539','15','enseignant','facture','masquer');
INSERT INTO `permissions` VALUES ('540','15','enseignant','Sectrapport','aucun');
INSERT INTO `permissions` VALUES ('541','15','enseignant','rapport_comptable','masquer');
INSERT INTO `permissions` VALUES ('542','15','enseignant','rapport_presence','masquer');
INSERT INTO `permissions` VALUES ('543','15','enseignant','rapport_staff','masquer');
INSERT INTO `permissions` VALUES ('544','15','enseignant','evaluation_notes','masquer');
INSERT INTO `permissions` VALUES ('545','15','enseignant','Sectrapp','masquer');
INSERT INTO `permissions` VALUES ('546','15','enseignant','parametres','masquer');
INSERT INTO `permissions` VALUES ('547','15','enseignant','sauvegardes','masquer');
INSERT INTO `permissions` VALUES ('548','17','enseignant','Administration','masquer');
INSERT INTO `permissions` VALUES ('549','17','enseignant','dashboard','masquer');
INSERT INTO `permissions` VALUES ('550','17','enseignant','ecole','masquer');
INSERT INTO `permissions` VALUES ('551','17','enseignant','Admin','masquer');
INSERT INTO `permissions` VALUES ('552','17','enseignant','inscription','masquer');
INSERT INTO `permissions` VALUES ('553','17','enseignant','liste_classes','masquer');
INSERT INTO `permissions` VALUES ('554','17','enseignant','matieres','masquer');
INSERT INTO `permissions` VALUES ('555','17','enseignant','Enseignantss','ecriture');
INSERT INTO `permissions` VALUES ('556','17','enseignant','notes','ecriture');
INSERT INTO `permissions` VALUES ('557','17','enseignant','bulletin','ecriture');
INSERT INTO `permissions` VALUES ('558','17','enseignant','resultats','ecriture');
INSERT INTO `permissions` VALUES ('559','17','enseignant','examen_blanc','ecriture');
INSERT INTO `permissions` VALUES ('560','17','enseignant','resultats_examen_blanc','ecriture');
INSERT INTO `permissions` VALUES ('561','17','enseignant','Pédagogique','ecriture');
INSERT INTO `permissions` VALUES ('562','17','enseignant','emploi_temps','ecriture');
INSERT INTO `permissions` VALUES ('563','17','enseignant','fiche_presence','masquer');
INSERT INTO `permissions` VALUES ('564','17','enseignant','calendrier','masquer');
INSERT INTO `permissions` VALUES ('565','17','enseignant','notifications','masquer');
INSERT INTO `permissions` VALUES ('566','17','enseignant','cartes','masquer');
INSERT INTO `permissions` VALUES ('567','17','enseignant','depot_dossier','masquer');
INSERT INTO `permissions` VALUES ('568','17','enseignant','fpe','masquer');
INSERT INTO `permissions` VALUES ('569','17','enseignant','liste_assurance','masquer');
INSERT INTO `permissions` VALUES ('570','17','enseignant','RH','masquer');
INSERT INTO `permissions` VALUES ('571','17','enseignant','enseignants','masquer');
INSERT INTO `permissions` VALUES ('572','17','enseignant','staff','masquer');
INSERT INTO `permissions` VALUES ('573','17','enseignant','presence','ecriture');
INSERT INTO `permissions` VALUES ('574','17','enseignant','presence_staff','masquer');
INSERT INTO `permissions` VALUES ('575','17','enseignant','permissions','masquer');
INSERT INTO `permissions` VALUES ('576','17','enseignant','gestion_ressource','masquer');
INSERT INTO `permissions` VALUES ('577','17','enseignant','Communication','ecriture');
INSERT INTO `permissions` VALUES ('578','17','enseignant','communication','ecriture');
INSERT INTO `permissions` VALUES ('579','17','enseignant','chat_private','ecriture');
INSERT INTO `permissions` VALUES ('580','17','enseignant','chat_group','ecriture');
INSERT INTO `permissions` VALUES ('581','17','enseignant','Paiement','masquer');
INSERT INTO `permissions` VALUES ('582','17','enseignant','detail_paiement','masquer');
INSERT INTO `permissions` VALUES ('583','17','enseignant','comptable','masquer');
INSERT INTO `permissions` VALUES ('584','17','enseignant','liste_paiements','masquer');
INSERT INTO `permissions` VALUES ('585','17','enseignant','facture','masquer');
INSERT INTO `permissions` VALUES ('586','17','enseignant','Sectrapport','ecriture');
INSERT INTO `permissions` VALUES ('587','17','enseignant','rapport_comptable','masquer');
INSERT INTO `permissions` VALUES ('588','17','enseignant','rapport_presence','masquer');
INSERT INTO `permissions` VALUES ('589','17','enseignant','rapport_staff','masquer');
INSERT INTO `permissions` VALUES ('590','17','enseignant','evaluation_notes','ecriture');
INSERT INTO `permissions` VALUES ('591','17','enseignant','Sectrapp','ecriture');
INSERT INTO `permissions` VALUES ('592','17','enseignant','parametres','masquer');
INSERT INTO `permissions` VALUES ('593','17','enseignant','sauvegardes','ecriture');
INSERT INTO `permissions` VALUES ('594','15','enseignant','Administration','masquer');
INSERT INTO `permissions` VALUES ('595','15','enseignant','dashboard','masquer');
INSERT INTO `permissions` VALUES ('596','15','enseignant','ecole','masquer');
INSERT INTO `permissions` VALUES ('597','15','enseignant','Admin','masquer');
INSERT INTO `permissions` VALUES ('598','15','enseignant','inscription','masquer');
INSERT INTO `permissions` VALUES ('599','15','enseignant','liste_classes','masquer');
INSERT INTO `permissions` VALUES ('600','15','enseignant','matieres','masquer');
INSERT INTO `permissions` VALUES ('601','15','enseignant','Enseignantss','ecriture');
INSERT INTO `permissions` VALUES ('602','15','enseignant','notes','ecriture');
INSERT INTO `permissions` VALUES ('603','15','enseignant','bulletin','ecriture');
INSERT INTO `permissions` VALUES ('604','15','enseignant','resultats','ecriture');
INSERT INTO `permissions` VALUES ('605','15','enseignant','examen_blanc','ecriture');
INSERT INTO `permissions` VALUES ('606','15','enseignant','resultats_examen_blanc','ecriture');
INSERT INTO `permissions` VALUES ('607','15','enseignant','Pédagogique','ecriture');
INSERT INTO `permissions` VALUES ('608','15','enseignant','emploi_temps','ecriture');
INSERT INTO `permissions` VALUES ('609','15','enseignant','fiche_presence','masquer');
INSERT INTO `permissions` VALUES ('610','15','enseignant','calendrier','masquer');
INSERT INTO `permissions` VALUES ('611','15','enseignant','notifications','masquer');
INSERT INTO `permissions` VALUES ('612','15','enseignant','cartes','masquer');
INSERT INTO `permissions` VALUES ('613','15','enseignant','depot_dossier','masquer');
INSERT INTO `permissions` VALUES ('614','15','enseignant','fpe','masquer');
INSERT INTO `permissions` VALUES ('615','15','enseignant','liste_assurance','masquer');
INSERT INTO `permissions` VALUES ('616','15','enseignant','RH','ecriture');
INSERT INTO `permissions` VALUES ('617','15','enseignant','enseignants','masquer');
INSERT INTO `permissions` VALUES ('618','15','enseignant','staff','masquer');
INSERT INTO `permissions` VALUES ('619','15','enseignant','presence','ecriture');
INSERT INTO `permissions` VALUES ('620','15','enseignant','presence_staff','masquer');
INSERT INTO `permissions` VALUES ('621','15','enseignant','permissions','masquer');
INSERT INTO `permissions` VALUES ('622','15','enseignant','gestion_ressource','masquer');
INSERT INTO `permissions` VALUES ('623','15','enseignant','Communication','ecriture');
INSERT INTO `permissions` VALUES ('624','15','enseignant','communication','ecriture');
INSERT INTO `permissions` VALUES ('625','15','enseignant','chat_private','ecriture');
INSERT INTO `permissions` VALUES ('626','15','enseignant','chat_group','ecriture');
INSERT INTO `permissions` VALUES ('627','15','enseignant','Paiement','masquer');
INSERT INTO `permissions` VALUES ('628','15','enseignant','detail_paiement','masquer');
INSERT INTO `permissions` VALUES ('629','15','enseignant','comptable','masquer');
INSERT INTO `permissions` VALUES ('630','15','enseignant','liste_paiements','masquer');
INSERT INTO `permissions` VALUES ('631','15','enseignant','facture','masquer');
INSERT INTO `permissions` VALUES ('632','15','enseignant','Sectrapport','ecriture');
INSERT INTO `permissions` VALUES ('633','15','enseignant','rapport_comptable','masquer');
INSERT INTO `permissions` VALUES ('634','15','enseignant','rapport_presence','masquer');
INSERT INTO `permissions` VALUES ('635','15','enseignant','rapport_staff','masquer');
INSERT INTO `permissions` VALUES ('636','15','enseignant','evaluation_notes','ecriture');
INSERT INTO `permissions` VALUES ('637','15','enseignant','Sectrapp','ecriture');
INSERT INTO `permissions` VALUES ('638','15','enseignant','parametres','masquer');
INSERT INTO `permissions` VALUES ('639','15','enseignant','sauvegardes','ecriture');
INSERT INTO `permissions` VALUES ('640','20','staff','Administration','masquer');
INSERT INTO `permissions` VALUES ('641','20','staff','dashboard','masquer');
INSERT INTO `permissions` VALUES ('642','20','staff','ecole','masquer');
INSERT INTO `permissions` VALUES ('643','20','staff','Admin','masquer');
INSERT INTO `permissions` VALUES ('644','20','staff','inscription','masquer');
INSERT INTO `permissions` VALUES ('645','20','staff','liste_classes','masquer');
INSERT INTO `permissions` VALUES ('646','20','staff','matieres','masquer');
INSERT INTO `permissions` VALUES ('647','20','staff','Enseignantss','aucun');
INSERT INTO `permissions` VALUES ('648','20','staff','notes','aucun');
INSERT INTO `permissions` VALUES ('649','20','staff','bulletin','aucun');
INSERT INTO `permissions` VALUES ('650','20','staff','resultats','aucun');
INSERT INTO `permissions` VALUES ('651','20','staff','examen_blanc','aucun');
INSERT INTO `permissions` VALUES ('652','20','staff','resultats_examen_blanc','aucun');
INSERT INTO `permissions` VALUES ('653','20','staff','Pédagogique','aucun');
INSERT INTO `permissions` VALUES ('654','20','staff','emploi_temps','aucun');
INSERT INTO `permissions` VALUES ('655','20','staff','fiche_presence','aucun');
INSERT INTO `permissions` VALUES ('656','20','staff','calendrier','aucun');
INSERT INTO `permissions` VALUES ('657','20','staff','notifications','aucun');
INSERT INTO `permissions` VALUES ('658','20','staff','cartes','aucun');
INSERT INTO `permissions` VALUES ('659','20','staff','depot_dossier','aucun');
INSERT INTO `permissions` VALUES ('660','20','staff','fpe','aucun');
INSERT INTO `permissions` VALUES ('661','20','staff','liste_assurance','aucun');
INSERT INTO `permissions` VALUES ('662','20','staff','RH','aucun');
INSERT INTO `permissions` VALUES ('663','20','staff','enseignants','aucun');
INSERT INTO `permissions` VALUES ('664','20','staff','staff','aucun');
INSERT INTO `permissions` VALUES ('665','20','staff','presence','aucun');
INSERT INTO `permissions` VALUES ('666','20','staff','presence_staff','aucun');
INSERT INTO `permissions` VALUES ('667','20','staff','permissions','aucun');
INSERT INTO `permissions` VALUES ('668','20','staff','gestion_ressource','aucun');
INSERT INTO `permissions` VALUES ('669','20','staff','Communication','aucun');
INSERT INTO `permissions` VALUES ('670','20','staff','communication','aucun');
INSERT INTO `permissions` VALUES ('671','20','staff','chat_private','aucun');
INSERT INTO `permissions` VALUES ('672','20','staff','chat_group','aucun');
INSERT INTO `permissions` VALUES ('673','20','staff','Paiement','aucun');
INSERT INTO `permissions` VALUES ('674','20','staff','detail_paiement','aucun');
INSERT INTO `permissions` VALUES ('675','20','staff','comptable','aucun');
INSERT INTO `permissions` VALUES ('676','20','staff','liste_paiements','aucun');
INSERT INTO `permissions` VALUES ('677','20','staff','facture','aucun');
INSERT INTO `permissions` VALUES ('678','20','staff','Sectrapport','aucun');
INSERT INTO `permissions` VALUES ('679','20','staff','rapport_comptable','aucun');
INSERT INTO `permissions` VALUES ('680','20','staff','rapport_presence','aucun');
INSERT INTO `permissions` VALUES ('681','20','staff','rapport_staff','aucun');
INSERT INTO `permissions` VALUES ('682','20','staff','evaluation_notes','aucun');
INSERT INTO `permissions` VALUES ('683','20','staff','Sectrapp','aucun');
INSERT INTO `permissions` VALUES ('684','20','staff','parametres','aucun');
INSERT INTO `permissions` VALUES ('685','20','staff','sauvegardes','aucun');
INSERT INTO `permissions` VALUES ('686','19','admin','Administration','aucun');
INSERT INTO `permissions` VALUES ('687','19','admin','dashboard','aucun');
INSERT INTO `permissions` VALUES ('688','19','admin','ecole','aucun');
INSERT INTO `permissions` VALUES ('689','19','admin','Admin','aucun');
INSERT INTO `permissions` VALUES ('690','19','admin','inscription','aucun');
INSERT INTO `permissions` VALUES ('691','19','admin','liste_classes','aucun');
INSERT INTO `permissions` VALUES ('692','19','admin','matieres','aucun');
INSERT INTO `permissions` VALUES ('693','19','admin','Enseignantss','aucun');
INSERT INTO `permissions` VALUES ('694','19','admin','notes','aucun');
INSERT INTO `permissions` VALUES ('695','19','admin','bulletin','aucun');
INSERT INTO `permissions` VALUES ('696','19','admin','resultats','aucun');
INSERT INTO `permissions` VALUES ('697','19','admin','examen_blanc','aucun');
INSERT INTO `permissions` VALUES ('698','19','admin','resultats_examen_blanc','aucun');
INSERT INTO `permissions` VALUES ('699','19','admin','Pédagogique','aucun');
INSERT INTO `permissions` VALUES ('700','19','admin','emploi_temps','aucun');
INSERT INTO `permissions` VALUES ('701','19','admin','fiche_presence','aucun');
INSERT INTO `permissions` VALUES ('702','19','admin','calendrier','aucun');
INSERT INTO `permissions` VALUES ('703','19','admin','notifications','aucun');
INSERT INTO `permissions` VALUES ('704','19','admin','cartes','aucun');
INSERT INTO `permissions` VALUES ('705','19','admin','depot_dossier','aucun');
INSERT INTO `permissions` VALUES ('706','19','admin','fpe','aucun');
INSERT INTO `permissions` VALUES ('707','19','admin','liste_assurance','aucun');
INSERT INTO `permissions` VALUES ('708','19','admin','RH','aucun');
INSERT INTO `permissions` VALUES ('709','19','admin','enseignants','aucun');
INSERT INTO `permissions` VALUES ('710','19','admin','staff','aucun');
INSERT INTO `permissions` VALUES ('711','19','admin','presence','aucun');
INSERT INTO `permissions` VALUES ('712','19','admin','presence_staff','aucun');
INSERT INTO `permissions` VALUES ('713','19','admin','permissions','lecture');
INSERT INTO `permissions` VALUES ('714','19','admin','gestion_ressource','aucun');
INSERT INTO `permissions` VALUES ('715','19','admin','Communication','aucun');
INSERT INTO `permissions` VALUES ('716','19','admin','communication','aucun');
INSERT INTO `permissions` VALUES ('717','19','admin','chat_private','aucun');
INSERT INTO `permissions` VALUES ('718','19','admin','chat_group','aucun');
INSERT INTO `permissions` VALUES ('719','19','admin','Paiement','aucun');
INSERT INTO `permissions` VALUES ('720','19','admin','detail_paiement','aucun');
INSERT INTO `permissions` VALUES ('721','19','admin','comptable','aucun');
INSERT INTO `permissions` VALUES ('722','19','admin','liste_paiements','aucun');
INSERT INTO `permissions` VALUES ('723','19','admin','facture','aucun');
INSERT INTO `permissions` VALUES ('724','19','admin','Sectrapport','aucun');
INSERT INTO `permissions` VALUES ('725','19','admin','rapport_comptable','aucun');
INSERT INTO `permissions` VALUES ('726','19','admin','rapport_presence','aucun');
INSERT INTO `permissions` VALUES ('727','19','admin','rapport_staff','aucun');
INSERT INTO `permissions` VALUES ('728','19','admin','evaluation_notes','aucun');
INSERT INTO `permissions` VALUES ('729','19','admin','Sectrapp','aucun');
INSERT INTO `permissions` VALUES ('730','19','admin','parametres','aucun');
INSERT INTO `permissions` VALUES ('731','19','admin','sauvegardes','aucun');
INSERT INTO `permissions` VALUES ('732','19','admin','Administration','aucun');
INSERT INTO `permissions` VALUES ('733','19','admin','dashboard','aucun');
INSERT INTO `permissions` VALUES ('734','19','admin','ecole','aucun');
INSERT INTO `permissions` VALUES ('735','19','admin','Admin','aucun');
INSERT INTO `permissions` VALUES ('736','19','admin','inscription','aucun');
INSERT INTO `permissions` VALUES ('737','19','admin','liste_classes','aucun');
INSERT INTO `permissions` VALUES ('738','19','admin','matieres','aucun');
INSERT INTO `permissions` VALUES ('739','19','admin','Enseignantss','aucun');
INSERT INTO `permissions` VALUES ('740','19','admin','notes','aucun');
INSERT INTO `permissions` VALUES ('741','19','admin','bulletin','aucun');
INSERT INTO `permissions` VALUES ('742','19','admin','resultats','aucun');
INSERT INTO `permissions` VALUES ('743','19','admin','examen_blanc','aucun');
INSERT INTO `permissions` VALUES ('744','19','admin','resultats_examen_blanc','aucun');
INSERT INTO `permissions` VALUES ('745','19','admin','Pédagogique','aucun');
INSERT INTO `permissions` VALUES ('746','19','admin','emploi_temps','aucun');
INSERT INTO `permissions` VALUES ('747','19','admin','fiche_presence','aucun');
INSERT INTO `permissions` VALUES ('748','19','admin','calendrier','aucun');
INSERT INTO `permissions` VALUES ('749','19','admin','notifications','aucun');
INSERT INTO `permissions` VALUES ('750','19','admin','cartes','aucun');
INSERT INTO `permissions` VALUES ('751','19','admin','depot_dossier','aucun');
INSERT INTO `permissions` VALUES ('752','19','admin','fpe','aucun');
INSERT INTO `permissions` VALUES ('753','19','admin','liste_assurance','aucun');
INSERT INTO `permissions` VALUES ('754','19','admin','RH','aucun');
INSERT INTO `permissions` VALUES ('755','19','admin','enseignants','aucun');
INSERT INTO `permissions` VALUES ('756','19','admin','staff','aucun');
INSERT INTO `permissions` VALUES ('757','19','admin','presence','aucun');
INSERT INTO `permissions` VALUES ('758','19','admin','presence_staff','aucun');
INSERT INTO `permissions` VALUES ('759','19','admin','permissions','lecture');
INSERT INTO `permissions` VALUES ('760','19','admin','gestion_ressource','aucun');
INSERT INTO `permissions` VALUES ('761','19','admin','Communication','aucun');
INSERT INTO `permissions` VALUES ('762','19','admin','communication','aucun');
INSERT INTO `permissions` VALUES ('763','19','admin','chat_private','aucun');
INSERT INTO `permissions` VALUES ('764','19','admin','chat_group','aucun');
INSERT INTO `permissions` VALUES ('765','19','admin','Paiement','aucun');
INSERT INTO `permissions` VALUES ('766','19','admin','detail_paiement','aucun');
INSERT INTO `permissions` VALUES ('767','19','admin','comptable','aucun');
INSERT INTO `permissions` VALUES ('768','19','admin','liste_paiements','aucun');
INSERT INTO `permissions` VALUES ('769','19','admin','facture','aucun');
INSERT INTO `permissions` VALUES ('770','19','admin','Sectrapport','aucun');
INSERT INTO `permissions` VALUES ('771','19','admin','rapport_comptable','aucun');
INSERT INTO `permissions` VALUES ('772','19','admin','rapport_presence','aucun');
INSERT INTO `permissions` VALUES ('773','19','admin','rapport_staff','aucun');
INSERT INTO `permissions` VALUES ('774','19','admin','evaluation_notes','aucun');
INSERT INTO `permissions` VALUES ('775','19','admin','Sectrapp','aucun');
INSERT INTO `permissions` VALUES ('776','19','admin','parametres','aucun');
INSERT INTO `permissions` VALUES ('777','19','admin','sauvegardes','aucun');
INSERT INTO `permissions` VALUES ('778','20','staff','Administration','masquer');
INSERT INTO `permissions` VALUES ('779','20','staff','dashboard','masquer');
INSERT INTO `permissions` VALUES ('780','20','staff','ecole','masquer');
INSERT INTO `permissions` VALUES ('781','20','staff','Admin','masquer');
INSERT INTO `permissions` VALUES ('782','20','staff','inscription','masquer');
INSERT INTO `permissions` VALUES ('783','20','staff','liste_classes','masquer');
INSERT INTO `permissions` VALUES ('784','20','staff','matieres','masquer');
INSERT INTO `permissions` VALUES ('785','20','staff','Enseignantss','aucun');
INSERT INTO `permissions` VALUES ('786','20','staff','notes','aucun');
INSERT INTO `permissions` VALUES ('787','20','staff','bulletin','aucun');
INSERT INTO `permissions` VALUES ('788','20','staff','resultats','aucun');
INSERT INTO `permissions` VALUES ('789','20','staff','examen_blanc','aucun');
INSERT INTO `permissions` VALUES ('790','20','staff','resultats_examen_blanc','aucun');
INSERT INTO `permissions` VALUES ('791','20','staff','Pédagogique','aucun');
INSERT INTO `permissions` VALUES ('792','20','staff','emploi_temps','aucun');
INSERT INTO `permissions` VALUES ('793','20','staff','fiche_presence','aucun');
INSERT INTO `permissions` VALUES ('794','20','staff','calendrier','aucun');
INSERT INTO `permissions` VALUES ('795','20','staff','notifications','aucun');
INSERT INTO `permissions` VALUES ('796','20','staff','cartes','aucun');
INSERT INTO `permissions` VALUES ('797','20','staff','depot_dossier','aucun');
INSERT INTO `permissions` VALUES ('798','20','staff','fpe','aucun');
INSERT INTO `permissions` VALUES ('799','20','staff','liste_assurance','aucun');
INSERT INTO `permissions` VALUES ('800','20','staff','RH','aucun');
INSERT INTO `permissions` VALUES ('801','20','staff','enseignants','aucun');
INSERT INTO `permissions` VALUES ('802','20','staff','staff','aucun');
INSERT INTO `permissions` VALUES ('803','20','staff','presence','aucun');
INSERT INTO `permissions` VALUES ('804','20','staff','presence_staff','aucun');
INSERT INTO `permissions` VALUES ('805','20','staff','permissions','aucun');
INSERT INTO `permissions` VALUES ('806','20','staff','gestion_ressource','aucun');
INSERT INTO `permissions` VALUES ('807','20','staff','Communication','aucun');
INSERT INTO `permissions` VALUES ('808','20','staff','communication','aucun');
INSERT INTO `permissions` VALUES ('809','20','staff','chat_private','aucun');
INSERT INTO `permissions` VALUES ('810','20','staff','chat_group','aucun');
INSERT INTO `permissions` VALUES ('811','20','staff','Paiement','aucun');
INSERT INTO `permissions` VALUES ('812','20','staff','detail_paiement','aucun');
INSERT INTO `permissions` VALUES ('813','20','staff','comptable','aucun');
INSERT INTO `permissions` VALUES ('814','20','staff','liste_paiements','aucun');
INSERT INTO `permissions` VALUES ('815','20','staff','facture','aucun');
INSERT INTO `permissions` VALUES ('816','20','staff','Sectrapport','aucun');
INSERT INTO `permissions` VALUES ('817','20','staff','rapport_comptable','aucun');
INSERT INTO `permissions` VALUES ('818','20','staff','rapport_presence','aucun');
INSERT INTO `permissions` VALUES ('819','20','staff','rapport_staff','aucun');
INSERT INTO `permissions` VALUES ('820','20','staff','evaluation_notes','aucun');
INSERT INTO `permissions` VALUES ('821','20','staff','Sectrapp','aucun');
INSERT INTO `permissions` VALUES ('822','20','staff','parametres','aucun');
INSERT INTO `permissions` VALUES ('823','20','staff','sauvegardes','aucun');
INSERT INTO `permissions` VALUES ('824','17','enseignant','Administration','masquer');
INSERT INTO `permissions` VALUES ('825','17','enseignant','dashboard','masquer');
INSERT INTO `permissions` VALUES ('826','17','enseignant','ecole','masquer');
INSERT INTO `permissions` VALUES ('827','17','enseignant','Admin','masquer');
INSERT INTO `permissions` VALUES ('828','17','enseignant','inscription','masquer');
INSERT INTO `permissions` VALUES ('829','17','enseignant','liste_classes','masquer');
INSERT INTO `permissions` VALUES ('830','17','enseignant','matieres','masquer');
INSERT INTO `permissions` VALUES ('831','17','enseignant','Enseignantss','ecriture');
INSERT INTO `permissions` VALUES ('832','17','enseignant','notes','ecriture');
INSERT INTO `permissions` VALUES ('833','17','enseignant','bulletin','ecriture');
INSERT INTO `permissions` VALUES ('834','17','enseignant','resultats','ecriture');
INSERT INTO `permissions` VALUES ('835','17','enseignant','examen_blanc','ecriture');
INSERT INTO `permissions` VALUES ('836','17','enseignant','resultats_examen_blanc','ecriture');
INSERT INTO `permissions` VALUES ('837','17','enseignant','Pédagogique','ecriture');
INSERT INTO `permissions` VALUES ('838','17','enseignant','emploi_temps','ecriture');
INSERT INTO `permissions` VALUES ('839','17','enseignant','fiche_presence','masquer');
INSERT INTO `permissions` VALUES ('840','17','enseignant','calendrier','masquer');
INSERT INTO `permissions` VALUES ('841','17','enseignant','notifications','masquer');
INSERT INTO `permissions` VALUES ('842','17','enseignant','cartes','masquer');
INSERT INTO `permissions` VALUES ('843','17','enseignant','depot_dossier','masquer');
INSERT INTO `permissions` VALUES ('844','17','enseignant','fpe','masquer');
INSERT INTO `permissions` VALUES ('845','17','enseignant','liste_assurance','masquer');
INSERT INTO `permissions` VALUES ('846','17','enseignant','RH','masquer');
INSERT INTO `permissions` VALUES ('847','17','enseignant','enseignants','masquer');
INSERT INTO `permissions` VALUES ('848','17','enseignant','staff','masquer');
INSERT INTO `permissions` VALUES ('849','17','enseignant','presence','ecriture');
INSERT INTO `permissions` VALUES ('850','17','enseignant','presence_staff','masquer');
INSERT INTO `permissions` VALUES ('851','17','enseignant','permissions','masquer');
INSERT INTO `permissions` VALUES ('852','17','enseignant','gestion_ressource','masquer');
INSERT INTO `permissions` VALUES ('853','17','enseignant','Communication','ecriture');
INSERT INTO `permissions` VALUES ('854','17','enseignant','communication','ecriture');
INSERT INTO `permissions` VALUES ('855','17','enseignant','chat_private','ecriture');
INSERT INTO `permissions` VALUES ('856','17','enseignant','chat_group','ecriture');
INSERT INTO `permissions` VALUES ('857','17','enseignant','Paiement','masquer');
INSERT INTO `permissions` VALUES ('858','17','enseignant','detail_paiement','masquer');
INSERT INTO `permissions` VALUES ('859','17','enseignant','comptable','masquer');
INSERT INTO `permissions` VALUES ('860','17','enseignant','liste_paiements','masquer');
INSERT INTO `permissions` VALUES ('861','17','enseignant','facture','masquer');
INSERT INTO `permissions` VALUES ('862','17','enseignant','Sectrapport','ecriture');
INSERT INTO `permissions` VALUES ('863','17','enseignant','rapport_comptable','masquer');
INSERT INTO `permissions` VALUES ('864','17','enseignant','rapport_presence','masquer');
INSERT INTO `permissions` VALUES ('865','17','enseignant','rapport_staff','masquer');
INSERT INTO `permissions` VALUES ('866','17','enseignant','evaluation_notes','ecriture');
INSERT INTO `permissions` VALUES ('867','17','enseignant','Sectrapp','ecriture');
INSERT INTO `permissions` VALUES ('868','17','enseignant','parametres','masquer');
INSERT INTO `permissions` VALUES ('869','17','enseignant','sauvegardes','ecriture');
INSERT INTO `permissions` VALUES ('870','20','staff','Administration','masquer');
INSERT INTO `permissions` VALUES ('871','20','staff','dashboard','masquer');
INSERT INTO `permissions` VALUES ('872','20','staff','ecole','masquer');
INSERT INTO `permissions` VALUES ('873','20','staff','Admin','masquer');
INSERT INTO `permissions` VALUES ('874','20','staff','inscription','masquer');
INSERT INTO `permissions` VALUES ('875','20','staff','liste_classes','masquer');
INSERT INTO `permissions` VALUES ('876','20','staff','matieres','masquer');
INSERT INTO `permissions` VALUES ('877','20','staff','Enseignantss','aucun');
INSERT INTO `permissions` VALUES ('878','20','staff','notes','aucun');
INSERT INTO `permissions` VALUES ('879','20','staff','bulletin','aucun');
INSERT INTO `permissions` VALUES ('880','20','staff','resultats','aucun');
INSERT INTO `permissions` VALUES ('881','20','staff','examen_blanc','aucun');
INSERT INTO `permissions` VALUES ('882','20','staff','resultats_examen_blanc','aucun');
INSERT INTO `permissions` VALUES ('883','20','staff','Pédagogique','aucun');
INSERT INTO `permissions` VALUES ('884','20','staff','emploi_temps','aucun');
INSERT INTO `permissions` VALUES ('885','20','staff','fiche_presence','ecriture');
INSERT INTO `permissions` VALUES ('886','20','staff','calendrier','aucun');
INSERT INTO `permissions` VALUES ('887','20','staff','notifications','aucun');
INSERT INTO `permissions` VALUES ('888','20','staff','cartes','aucun');
INSERT INTO `permissions` VALUES ('889','20','staff','depot_dossier','aucun');
INSERT INTO `permissions` VALUES ('890','20','staff','fpe','aucun');
INSERT INTO `permissions` VALUES ('891','20','staff','liste_assurance','aucun');
INSERT INTO `permissions` VALUES ('892','20','staff','RH','aucun');
INSERT INTO `permissions` VALUES ('893','20','staff','enseignants','aucun');
INSERT INTO `permissions` VALUES ('894','20','staff','staff','aucun');
INSERT INTO `permissions` VALUES ('895','20','staff','presence','aucun');
INSERT INTO `permissions` VALUES ('896','20','staff','presence_staff','aucun');
INSERT INTO `permissions` VALUES ('897','20','staff','permissions','aucun');
INSERT INTO `permissions` VALUES ('898','20','staff','gestion_ressource','aucun');
INSERT INTO `permissions` VALUES ('899','20','staff','Communication','aucun');
INSERT INTO `permissions` VALUES ('900','20','staff','communication','aucun');
INSERT INTO `permissions` VALUES ('901','20','staff','chat_private','aucun');
INSERT INTO `permissions` VALUES ('902','20','staff','chat_group','aucun');
INSERT INTO `permissions` VALUES ('903','20','staff','Paiement','aucun');
INSERT INTO `permissions` VALUES ('904','20','staff','detail_paiement','aucun');
INSERT INTO `permissions` VALUES ('905','20','staff','comptable','aucun');
INSERT INTO `permissions` VALUES ('906','20','staff','liste_paiements','aucun');
INSERT INTO `permissions` VALUES ('907','20','staff','facture','aucun');
INSERT INTO `permissions` VALUES ('908','20','staff','Sectrapport','aucun');
INSERT INTO `permissions` VALUES ('909','20','staff','rapport_comptable','aucun');
INSERT INTO `permissions` VALUES ('910','20','staff','rapport_presence','aucun');
INSERT INTO `permissions` VALUES ('911','20','staff','rapport_staff','aucun');
INSERT INTO `permissions` VALUES ('912','20','staff','evaluation_notes','aucun');
INSERT INTO `permissions` VALUES ('913','20','staff','Sectrapp','aucun');
INSERT INTO `permissions` VALUES ('914','20','staff','parametres','aucun');
INSERT INTO `permissions` VALUES ('915','20','staff','sauvegardes','aucun');


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

INSERT INTO `presence_personnels` VALUES ('83','2','2026','02','2026-02-18','1','2026-02-18 13:21:57','2.00','0');
INSERT INTO `presence_personnels` VALUES ('84','4','2026','02','2026-02-18','1','2026-02-18 13:23:33','3.00','1');
INSERT INTO `presence_personnels` VALUES ('85','6','2026','02','2026-02-18','1','2026-02-18 13:24:41','3.00','0');
INSERT INTO `presence_personnels` VALUES ('86','5','2026','02','2026-02-18','0','2026-02-18 13:26:04','2.00','0');
INSERT INTO `presence_personnels` VALUES ('87','4','2026','02','2026-02-27','1','2026-02-27 20:31:45','3.00','0');
INSERT INTO `presence_personnels` VALUES ('88','5','2026','02','2026-02-28','1','2026-02-28 05:14:57','1.45','1');
INSERT INTO `presence_personnels` VALUES ('89','5','2025-2026','05','2026-05-04','1','2026-05-04 11:49:28','2.00','0');


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
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `presence_staff` VALUES ('11','13','2026','02','2026-02-27','1','2026-02-27 10:49:14','0','0.00');
INSERT INTO `presence_staff` VALUES ('12','14','2026','02','2026-02-23','1','2026-02-27 18:24:24','0','1.00');
INSERT INTO `presence_staff` VALUES ('13','14','2026','02','2026-02-24','1','2026-02-27 18:25:16','1','1.00');
INSERT INTO `presence_staff` VALUES ('14','14','2026','02','2026-02-25','1','2026-02-27 18:25:28','0','1.00');
INSERT INTO `presence_staff` VALUES ('15','14','2026','02','2026-02-26','0','2026-02-27 18:25:47','1','1.00');
INSERT INTO `presence_staff` VALUES ('16','14','2026','02','2026-02-27','1','2026-02-27 18:25:57','0','1.00');
INSERT INTO `presence_staff` VALUES ('17','13','2026','02','2026-02-27','1','2026-02-27 20:34:02','0','0.00');


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
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `professeurs` VALUES ('2','RANDRIAMIFALY','Heriniaina','heru@gmail.com','images/prof_6895d35a32c94.jpg','2025-2026','2025-08-08 10:37:14','0346558998','20000','18','Aucun','Non','0','actif');
INSERT INTO `professeurs` VALUES ('4','PERSEVERANCE','Pain','pain@gmail.com','images/prof_689dd4c1bf6c0.jpg','2025-2026','2025-08-14 12:21:21','0387729952','14000','13','Aucun','Non','0','actif');
INSERT INTO `professeurs` VALUES ('5','EDWARD','Tojo Victoire','trandriamifalyH@gmail.com','images/prof_68af682233af3.jpg','2025-2026','2025-08-27 20:18:42','0387729954','25000','33','Aucun','Non','0','actif');
INSERT INTO `professeurs` VALUES ('6','Nety','VOALOHANY','nety@gmail.com','images/prof_68b7def4e2d8f.jpg','2025-2026','2025-09-03 06:23:48','0335668578','12000','45','Aucun','Non','0','actif');
INSERT INTO `professeurs` VALUES ('8','KOTO','Kanto','koto@gmail.com','images/prof_68b81fc582e1b.jpg','2025-2026','2025-09-03 11:00:21','0387729958','16000','45','Aucun','Non','3','actif');


DROP TABLE IF EXISTS `professeurs_classes`;
CREATE TABLE `professeurs_classes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `professeur_id` int DEFAULT NULL,
  `classe_id` int DEFAULT NULL,
  `annee_scolaire` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `professeur_id` (`professeur_id`),
  KEY `classe_id` (`classe_id`),
  CONSTRAINT `professeurs_classes_ibfk_1` FOREIGN KEY (`professeur_id`) REFERENCES `professeurs` (`id`),
  CONSTRAINT `professeurs_classes_ibfk_2` FOREIGN KEY (`classe_id`) REFERENCES `classes` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `professeurs_classes` VALUES ('2','8','14','2025-2026');


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
) ENGINE=InnoDB AUTO_INCREMENT=119 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `remarques` VALUES ('118','286','T1','Assez-bien','2025-2026');


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

INSERT INTO `reservations_ressources` VALUES ('9','6','2026-02-28 00:00:00','0','0','22:40:00','12:40:00','confirmé','Appel à réunion pour tous les enseignants le lundi. Evitez le retard. Cordialement');
INSERT INTO `reservations_ressources` VALUES ('11','6','2026-02-27 00:00:00','0','0','23:51:00','13:51:00','confirmé','Réunion spéciale pour nous');
INSERT INTO `reservations_ressources` VALUES ('12','6','2026-03-01 00:00:00','0','0','22:10:00','23:10:00','confirmé','Test résérvation');
INSERT INTO `reservations_ressources` VALUES ('13','6','2026-02-25 00:00:00','0','0','23:17:00','12:18:00','confirmé','rettt');
INSERT INTO `reservations_ressources` VALUES ('14','6','2026-02-25 00:00:00','0','0','19:22:00','21:22:00','confirmé','dede');
INSERT INTO `reservations_ressources` VALUES ('16','6','2026-03-03 00:00:00','19','0','23:21:00','13:21:00','confirmé','Réunion administratifs importants');


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
) ENGINE=InnoDB AUTO_INCREMENT=86 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `revenus` VALUES ('71','25','296','eleve','MS','Janvier','2025-2026','500000.00','Paiement écolage Ecolage','Espèces','complet','Écolage','2026-02-07 19:46:38','ANIO Fyh');
INSERT INTO `revenus` VALUES ('72','25','300','eleve','MS','Janvier','2025-2026','500000.00','Paiement écolage Ecolage','Espèces','complet','Écolage','2026-02-07 19:59:58','ANIO Foniah');
INSERT INTO `revenus` VALUES ('73','25','295','eleve','MS','Janvier','2025-2026','500000.00','Paiement écolage Ecolage','Espèces','complet','Écolage','2026-02-18 05:25:48','ANIO Tody');
INSERT INTO `revenus` VALUES ('74','25','297','eleve','MS','Janvier','2025-2026','500000.00','Paiement écolage Ecolage','Espèces','complet','Écolage','2026-02-18 05:26:05','ANIO Fano');
INSERT INTO `revenus` VALUES ('75','25','298','eleve','MS','Janvier','2025-2026','500000.00','Paiement écolage Ecolage','Espèces','complet','Écolage','2026-02-18 05:33:14','ANIO Fonja');
INSERT INTO `revenus` VALUES ('76','25','299','eleve','MS','Janvier','2025-2026','500000.00','Paiement écolage Ecolage','Espèces','complet','Écolage','2026-02-18 05:43:06','ANIO Fery');
INSERT INTO `revenus` VALUES ('77','25','301','eleve','MS','Janvier','2025-2026','500000.00','Paiement écolage Ecolage','Espèces','complet','Écolage','2026-02-18 07:01:09','ANIO Fanih');
INSERT INTO `revenus` VALUES ('78','25','302','eleve','MS','Janvier','2025-2026','500000.00','Paiement écolage Ecolage','Espèces','complet','Écolage','2026-02-18 16:27:19','ANIO Faniahy');
INSERT INTO `revenus` VALUES ('79','25','303','eleve','MS','Janvier','2025-2026','500000.00','Paiement écolage Ecolage','Espèces','complet','Écolage','2026-02-18 16:27:33','ANIO Faniho');
INSERT INTO `revenus` VALUES ('80','27','295','eleve','MS','Avril','2025-2026','150000.00','Paiement écolage Droit D\\\'inscription','Espèces','complet','Écolage','2026-02-27 23:50:33','ANIO Tody');
INSERT INTO `revenus` VALUES ('81','27','297','eleve','MS','Avril','2025-2026','150000.00','Paiement écolage Droit D\\\'inscription','Espèces','complet','Écolage','2026-02-27 23:50:48','ANIO Fano');
INSERT INTO `revenus` VALUES ('82','26','306','eleve','TA','Mars','2025-2026','200000.00','Paiement écolage Ecolage','Espèces','complet','Écolage','2026-02-27 23:53:59','ANIO Fyh');
INSERT INTO `revenus` VALUES ('83','26','305','eleve','TA','Mars','2025-2026','200000.00','Paiement écolage Ecolage','Espèces','complet','Écolage','2026-02-28 20:19:41','ANIO Tody');
INSERT INTO `revenus` VALUES ('84','27','296','eleve','MS','Avril','2025-2026','150000.00','Paiement écolage Droit D\\\'inscription','Espèces','complet','Écolage','2026-04-20 19:55:52','ANIO Fyh');
INSERT INTO `revenus` VALUES ('85','26','307','eleve','TA','Janvier','2025-2026','200000.00','Paiement ecolage','Especes','complet','Ecolage','2026-05-04 12:57:57','ANIO Fano');


DROP TABLE IF EXISTS `roles`;
CREATE TABLE `roles` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `roles` VALUES ('1','Secrétaire');
INSERT INTO `roles` VALUES ('2','Comptable');
INSERT INTO `roles` VALUES ('3','RH');
INSERT INTO `roles` VALUES ('4','Assistant');
INSERT INTO `roles` VALUES ('5','Autre');


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

INSERT INTO `salaires_assignes` VALUES ('1','13','staff','Février','2025-2026','paye','2026-02-07 16:14:44');
INSERT INTO `salaires_assignes` VALUES ('2','14','staff','Février','2025-2026','non_paye','2026-02-07 16:14:44');
INSERT INTO `salaires_assignes` VALUES ('3','1','professeur','Janvier','2025-2026','paye','2026-02-07 19:12:21');
INSERT INTO `salaires_assignes` VALUES ('4','2','professeur','Janvier','2025-2026','paye','2026-02-07 19:12:21');
INSERT INTO `salaires_assignes` VALUES ('5','4','professeur','Janvier','2025-2026','paye','2026-02-07 19:12:21');
INSERT INTO `salaires_assignes` VALUES ('6','5','professeur','Janvier','2025-2026','non_paye','2026-02-07 19:12:21');
INSERT INTO `salaires_assignes` VALUES ('7','6','professeur','Janvier','2025-2026','non_paye','2026-02-07 19:12:21');
INSERT INTO `salaires_assignes` VALUES ('8','7','professeur','Janvier','2025-2026','non_paye','2026-02-07 19:12:21');
INSERT INTO `salaires_assignes` VALUES ('9','8','professeur','Janvier','2025-2026','non_paye','2026-02-07 19:12:21');


DROP TABLE IF EXISTS `salles`;
CREATE TABLE `salles` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `capacite` varchar(30) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_general_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `salles` VALUES ('6','Salle de Réunion','30','C\'est une salle pour faire une réunion dédié aux enseignants seulement');


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
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `staff` VALUES ('13','RANDRIAMBOLANIAINA','Avotra Fenosoa','fa@gmail.com','images/staff_6905ba8893745.jpg','2025-2026','2025-11-01 10:45:12','0342565820','16000','2','Master','3','actif','0');
INSERT INTO `staff` VALUES ('14','ANDRIAMBOLANIAINA','Fenosoa','fiane@gmail.com','images/staff_6905bad10b1aa.jpg','2025-2026','2025-11-01 10:46:25','0342565821','16000','3','Doctorat','3','actif','0');


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
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `types_paiements` VALUES ('25','Ecolage','500000.00','[\"Janvier\"]','2026-02-07 19:46:24','MS','2026-02-07','2026-02-19','2',NULL,NULL,'2025-2026');
INSERT INTO `types_paiements` VALUES ('26','Ecolage','200000.00','[\"Mars\"]','2026-02-27 23:42:28','TA','2026-02-27','2026-03-13','16',NULL,NULL,'2025-2026');
INSERT INTO `types_paiements` VALUES ('27','Droit D\'inscription','150000.00','[\"Avril\"]','2026-02-27 23:45:26','MS','2026-02-28','2026-03-11','2',NULL,NULL,'2025-2026');


DROP TABLE IF EXISTS `typing_status`;
CREATE TABLE `typing_status` (
  `conversation_id` int NOT NULL,
  `user_id` int NOT NULL,
  `user_type` enum('admin','enseignant','staff','parent') NOT NULL DEFAULT 'admin',
  `is_typing` tinyint unsigned NOT NULL DEFAULT '0',
  `last_update` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`conversation_id`,`user_id`,`user_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `typing_status` VALUES ('256','14','admin','0','2026-03-08 21:14:07');
INSERT INTO `typing_status` VALUES ('256','15','enseignant','0','2026-02-14 14:39:34');
INSERT INTO `typing_status` VALUES ('257','16','admin','0','2026-02-14 15:06:26');
INSERT INTO `typing_status` VALUES ('258','16','admin','0','2026-04-20 19:54:49');
INSERT INTO `typing_status` VALUES ('258','19','admin','0','2026-02-18 10:21:18');
INSERT INTO `typing_status` VALUES ('261','14','admin','0','2026-02-17 07:04:50');
INSERT INTO `typing_status` VALUES ('263','20','staff','0','2026-02-17 07:03:21');
INSERT INTO `typing_status` VALUES ('266','19','admin','0','2026-02-14 00:45:48');
INSERT INTO `typing_status` VALUES ('266','20','staff','0','2026-02-14 00:54:48');
INSERT INTO `typing_status` VALUES ('270','19','admin','1','2026-02-12 01:18:02');
INSERT INTO `typing_status` VALUES ('271','19','admin','1','2026-02-12 01:18:59');
INSERT INTO `typing_status` VALUES ('275','14','admin','0','2026-02-28 15:34:04');
INSERT INTO `typing_status` VALUES ('275','16','admin','0','2026-02-28 15:33:01');
INSERT INTO `typing_status` VALUES ('276','17','enseignant','0','2026-02-26 16:19:03');
INSERT INTO `typing_status` VALUES ('276','19','admin','0','2026-02-26 16:19:35');
INSERT INTO `typing_status` VALUES ('277','19','admin','0','2026-02-13 23:40:27');
INSERT INTO `typing_status` VALUES ('279','14','admin','0','2026-02-26 20:38:38');
INSERT INTO `typing_status` VALUES ('279','17','enseignant','0','2026-02-14 15:04:55');
INSERT INTO `typing_status` VALUES ('281','14','admin','0','2026-02-28 00:23:37');
INSERT INTO `typing_status` VALUES ('281','19','admin','0','2026-02-28 00:23:47');
INSERT INTO `typing_status` VALUES ('295','17','enseignant','0','2026-02-14 14:38:23');


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
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `utilisateurs` VALUES ('14','Tojo Nambinina RANDRIAMIFALY','novaskol@gmail.com','$2y$10$23QkY2SdyMu4qA2qxqCGOeTTSpXk4DlXGoNairCQG1LJeXeNyQyXK','1770991584_698f2fe08ffa1.jpg','admin','2026-02-06 11:32:56','2026-05-04 11:27:45');
INSERT INTO `utilisateurs` VALUES ('15','test','test@gmail.com','$2y$10$vaymU5mHH0auLnnv/ArlyuVLLbIXAvCazE1IMzMDoSQ7RkexuvhTC','images/default-avatar.png','enseignant','2026-02-10 17:49:48','2026-02-16 20:22:08');
INSERT INTO `utilisateurs` VALUES ('16','diary','diary@gmail.com','$2y$10$ViWkvkKjn/K4Lw66..1u4Om08yRMf2esNrCWugORitWGK/PImHz4a','1771006501_698f6a25aa699.jpg','admin','2026-02-10 17:58:21','2026-05-04 14:03:02');
INSERT INTO `utilisateurs` VALUES ('17','Eivan KIMBERLEY','kim@gmail.com','$2y$10$x9j9VZNbriiGtBzkdPCXwuz0YeDy7wa7u6h/HQsp1i1efczCOZxOK','images/default-avatar.png','enseignant','2026-02-11 16:26:29','2026-02-26 20:48:51');
INSERT INTO `utilisateurs` VALUES ('18','Henry FALY','henry@gmail.com','$2y$10$F5Kbh.sMtq.SsyUtumGw5uMW.eagGgUoUdEkiqSIxLEN6GQBeADzu','images/default-avatar.png','admin','2026-02-11 16:57:39','2026-02-11 16:57:45');
INSERT INTO `utilisateurs` VALUES ('19','Stessy BELLA','stessy@gmail.com','$2y$10$0w9FQqoDjX299E804aE/k.ZbDdokHVaUmoqdEP32hKjwpKM6BavSu','1770991187_698f2e5378b8a.jpg','admin','2026-02-11 16:58:31','2026-03-05 20:03:22');
INSERT INTO `utilisateurs` VALUES ('20','Firmin','Firmin@gmail.com','$2y$10$Jvxe78z9sUspB49qIGl9Fuh/kL0V7SZWVQdwFU1XYImmpbqGkCU7.','images/default-avatar.png','staff','2026-02-11 17:47:05','2026-03-05 20:54:21');
INSERT INTO `utilisateurs` VALUES ('21','Neny','neny@gmail.com','$2y$10$fV6ZaBt6C3Y6UXXD.EXR1.zCD7oN1abh1A3Qfjdburf9rJmRDi6Pq','images/default-avatar.png','parent','2026-02-11 20:04:04','2026-02-11 20:18:40');

SET FOREIGN_KEY_CHECKS=1;
