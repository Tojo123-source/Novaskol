-- Sauvegarde Novaskol
-- Date: 2026-07-04 12:33:42


DROP TABLE IF EXISTS "bulletins";
CREATE TABLE "bulletins" ("id" integer primary key autoincrement not null, "id_eleve" integer not null, "trimestre" integer, "moyenne" float, "mention" varchar, "appreciation" text, "annee_scolaire" varchar not null, "created_at" datetime, "updated_at" datetime, foreign key("id_eleve") references "eleves"("id") on delete cascade);



DROP TABLE IF EXISTS "cache";
CREATE TABLE "cache" ("key" varchar not null, "value" text not null, "expiration" integer not null, primary key ("key"));



DROP TABLE IF EXISTS "cache_locks";
CREATE TABLE "cache_locks" ("key" varchar not null, "owner" varchar not null, "expiration" integer not null, primary key ("key"));



DROP TABLE IF EXISTS "chat_typing_status";
CREATE TABLE "chat_typing_status" ("conversation_id" integer not null, "user_id" integer not null, "user_role" varchar, "updated_at" datetime default CURRENT_TIMESTAMP, foreign key("conversation_id") references "conversations"("id") on delete cascade, primary key ("conversation_id", "user_id"));



DROP TABLE IF EXISTS "classe_matieres";
CREATE TABLE "classe_matieres" ("id_classe" integer not null, "id_matiere" integer not null, "coefficient" float not null default '1', "created_at" datetime, "updated_at" datetime, foreign key("id_classe") references "classes"("id") on delete cascade, foreign key("id_matiere") references "matieres"("id") on delete cascade, primary key ("id_classe", "id_matiere"));

INSERT INTO "classe_matieres" VALUES ('1','1','2',NULL,NULL);
INSERT INTO "classe_matieres" VALUES ('1','2','2',NULL,NULL);
INSERT INTO "classe_matieres" VALUES ('1','3','2',NULL,NULL);
INSERT INTO "classe_matieres" VALUES ('1','4','2',NULL,NULL);
INSERT INTO "classe_matieres" VALUES ('1','5','2',NULL,NULL);
INSERT INTO "classe_matieres" VALUES ('2','1','2',NULL,NULL);
INSERT INTO "classe_matieres" VALUES ('2','2','2',NULL,NULL);
INSERT INTO "classe_matieres" VALUES ('2','3','2',NULL,NULL);
INSERT INTO "classe_matieres" VALUES ('2','4','2',NULL,NULL);
INSERT INTO "classe_matieres" VALUES ('2','5','2',NULL,NULL);
INSERT INTO "classe_matieres" VALUES ('3','1','2',NULL,NULL);
INSERT INTO "classe_matieres" VALUES ('3','2','2',NULL,NULL);
INSERT INTO "classe_matieres" VALUES ('3','3','2',NULL,NULL);
INSERT INTO "classe_matieres" VALUES ('3','4','2',NULL,NULL);
INSERT INTO "classe_matieres" VALUES ('3','5','2',NULL,NULL);


DROP TABLE IF EXISTS "classes";
CREATE TABLE "classes" ("id" integer primary key autoincrement not null, "nom" varchar not null, "niveau" integer, "created_at" datetime, "updated_at" datetime);

INSERT INTO "classes" VALUES ('1','6eme','6',NULL,NULL);
INSERT INTO "classes" VALUES ('2','3eme','3',NULL,NULL);
INSERT INTO "classes" VALUES ('3','Terminale','0',NULL,NULL);


DROP TABLE IF EXISTS "conversation_participants";
CREATE TABLE "conversation_participants" ("id" integer primary key autoincrement not null, "conversation_id" integer not null, "user_type" varchar check ("user_type" in ('admin', 'enseignant', 'staff', 'parent')) not null, "user_id" integer not null, "joined_at" datetime not null default CURRENT_TIMESTAMP, "created_at" datetime, "updated_at" datetime, foreign key("conversation_id") references "conversations"("id") on delete cascade);

INSERT INTO "conversation_participants" VALUES ('1','1','admin','5','2026-07-04 11:58:41',NULL,NULL);
INSERT INTO "conversation_participants" VALUES ('2','1','enseignant','2','2026-07-04 11:58:41',NULL,NULL);
INSERT INTO "conversation_participants" VALUES ('8','2','admin','1','2026-07-04 11:59:23',NULL,NULL);
INSERT INTO "conversation_participants" VALUES ('9','2','enseignant','2','2026-07-04 11:59:23',NULL,NULL);
INSERT INTO "conversation_participants" VALUES ('10','2','staff','3','2026-07-04 11:59:23',NULL,NULL);
INSERT INTO "conversation_participants" VALUES ('11','2','parent','4','2026-07-04 11:59:23',NULL,NULL);
INSERT INTO "conversation_participants" VALUES ('12','2','admin','5','2026-07-04 11:59:23',NULL,NULL);


DROP TABLE IF EXISTS "conversations";
CREATE TABLE "conversations" ("id" integer primary key autoincrement not null, "type" varchar check ("type" in ('private', 'group')) not null, "name" varchar, "creator_id" integer not null default '0', "avatar" varchar, "is_announcement" tinyint(1) not null default '0', "created_at" datetime, "updated_at" datetime);

INSERT INTO "conversations" VALUES ('1','private',NULL,'5',NULL,'0','2026-07-04 11:58:41','2026-07-04 11:58:56');
INSERT INTO "conversations" VALUES ('2','group','General - Ecole','1',NULL,'1','2026-07-04 11:59:20','2026-07-04 11:59:20');


DROP TABLE IF EXISTS "departements";
CREATE TABLE "departements" ("id" integer primary key autoincrement not null, "nom" varchar not null, "created_at" datetime, "updated_at" datetime);

INSERT INTO "departements" VALUES ('1','Administration',NULL,NULL);
INSERT INTO "departements" VALUES ('2','Comptabilite',NULL,NULL);
INSERT INTO "departements" VALUES ('3','Secretariat',NULL,NULL);
INSERT INTO "departements" VALUES ('4','Pedagogie',NULL,NULL);
INSERT INTO "departements" VALUES ('5','Surveillance',NULL,NULL);
INSERT INTO "departements" VALUES ('6','Maintenance',NULL,NULL);
INSERT INTO "departements" VALUES ('7','Infirmerie',NULL,NULL);
INSERT INTO "departements" VALUES ('8','Bibliotheque',NULL,NULL);
INSERT INTO "departements" VALUES ('9','Informatique',NULL,NULL);


DROP TABLE IF EXISTS "depenses";
CREATE TABLE "depenses" ("id" integer primary key autoincrement not null, "type_id" integer, "personne_id" varchar, "type_personne" varchar, "mois" varchar, "annee_scolaire" varchar not null, "montant" numeric, "description" text, "mode_paiement" varchar, "statut" varchar, "categorie" varchar not null, "created_at" datetime, "updated_at" datetime, "nom_personne" varchar, "date_enregistrement" datetime);



DROP TABLE IF EXISTS "dossiers";
CREATE TABLE "dossiers" ("id" integer primary key autoincrement not null, "nom" varchar not null, "description" text, "eleve_id" integer, "created_at" datetime, "updated_at" datetime, "annee_scolaire" varchar, "mois" varchar, "type_dossier" varchar, "personne_id" integer, "anarana" text, "fichier" varchar, "date_upload" datetime, foreign key("eleve_id") references "eleves"("id") on delete cascade);



DROP TABLE IF EXISTS "ecole";
CREATE TABLE "ecole" ("id" integer primary key autoincrement not null, "nom" varchar, "logo" varchar, "created_at" datetime, "updated_at" datetime);

INSERT INTO "ecole" VALUES ('1','Ecole Demo','logo_1783161509.png',NULL,NULL);


DROP TABLE IF EXISTS "eleves";
CREATE TABLE "eleves" ("id" integer primary key autoincrement not null, "prenom" varchar not null, "nom" varchar not null, "date_naissance" date not null, "lieu_naissance" varchar not null, "adresse" varchar not null, "numero_acte" varchar not null, "fonkotany" varchar not null, "commune" varchar not null, "ecole_ancienne" varchar, "nom_pere" varchar, "nom_mere" varchar, "telephone" varchar not null, "telephone_pere" varchar, "telephone_mere" varchar, "profession_pere" varchar, "profession_mere" varchar, "adresse_pere" varchar, "adresse_mere" varchar, "id_classe" integer not null, "matricule" varchar not null, "annee_scolaire" varchar not null, "genre" varchar check ("genre" in ('F', 'G')) not null default 'G', "statut" varchar check ("statut" in ('nouveau', 'passant', 'redoublant')) not null default 'nouveau', "distance_domicile" integer not null default '0', "est_handicap" integer not null default '0', "photo" varchar not null default 'uploads/eleves/default.jpg', "created_at" datetime, "updated_at" datetime, "qr_token" varchar, foreign key("id_classe") references "classes"("id") on delete cascade);

INSERT INTO "eleves" VALUES ('1','Miora','Rabe','2014-07-02','Antananarivo','Adresse demo','ACTE1','Fkt1','Commune',NULL,NULL,NULL,'0340000010',NULL,NULL,NULL,NULL,NULL,NULL,'1','DEMO0001','2026-2027','F','nouveau','0','0','uploads/eleves/default.jpg',NULL,NULL,'nvs_Kn11UEonYpS96a48e306');
INSERT INTO "eleves" VALUES ('2','Tiana','Ando','2013-07-02','Antananarivo','Adresse demo','ACTE2','Fkt2','Commune',NULL,NULL,NULL,'0340000011',NULL,NULL,NULL,NULL,NULL,NULL,'1','DEMO0002','2026-2027','G','nouveau','0','0','uploads/eleves/default.jpg',NULL,NULL,'nvs_wBq06t7Nyo3v6a48e306');
INSERT INTO "eleves" VALUES ('3','Hery','Rasolonirina','2012-07-02','Antananarivo','Adresse demo','ACTE3','Fkt3','Commune',NULL,NULL,NULL,'0340000012',NULL,NULL,NULL,NULL,NULL,NULL,'1','DEMO0003','2026-2027','G','nouveau','0','0','uploads/eleves/default.jpg',NULL,NULL,'nvs_eEe8gVgSLjWb6a48e306');
INSERT INTO "eleves" VALUES ('4','Nina','Randria','2011-07-02','Antananarivo','Adresse demo','ACTE4','Fkt4','Commune',NULL,NULL,NULL,'0340000013',NULL,NULL,NULL,NULL,NULL,NULL,'1','DEMO0004','2026-2027','F','nouveau','0','0','uploads/eleves/default.jpg',NULL,NULL,'nvs_inAZM9bE9vlh6a48e306');


DROP TABLE IF EXISTS "emploi_du_temps";
CREATE TABLE "emploi_du_temps" ("id" integer primary key autoincrement not null, "classe_id" integer not null, "matiere_id" integer not null, "professeur_id" integer, "jour" varchar not null, "heure_debut" time not null, "heure_fin" time not null, "annee_scolaire" varchar not null, "created_at" datetime, "updated_at" datetime, foreign key("classe_id") references "classes"("id") on delete cascade, foreign key("matiere_id") references "matieres"("id") on delete cascade, foreign key("professeur_id") references "professeurs"("id") on delete set null);

INSERT INTO "emploi_du_temps" VALUES ('1','1','1','1','lundi','08:00:00','10:00:00','2026-2027',NULL,NULL);
INSERT INTO "emploi_du_temps" VALUES ('2','1','1','1','mardi','10:00:00','12:00:00','2026-2027',NULL,NULL);
INSERT INTO "emploi_du_temps" VALUES ('3','1','1','1','mercredi','14:00:00','16:00:00','2026-2027',NULL,NULL);
INSERT INTO "emploi_du_temps" VALUES ('4','1','1','1','jeudi','08:00:00','10:00:00','2026-2027',NULL,NULL);
INSERT INTO "emploi_du_temps" VALUES ('5','1','1','1','vendredi','10:00:00','12:00:00','2026-2027',NULL,NULL);
INSERT INTO "emploi_du_temps" VALUES ('6','1','1','1','samedi','14:00:00','16:00:00','2026-2027',NULL,NULL);
INSERT INTO "emploi_du_temps" VALUES ('7','2','2','1','lundi','08:00:00','10:00:00','2026-2027',NULL,NULL);
INSERT INTO "emploi_du_temps" VALUES ('8','2','2','1','mardi','10:00:00','12:00:00','2026-2027',NULL,NULL);
INSERT INTO "emploi_du_temps" VALUES ('9','2','2','1','mercredi','14:00:00','16:00:00','2026-2027',NULL,NULL);
INSERT INTO "emploi_du_temps" VALUES ('10','2','2','1','jeudi','08:00:00','10:00:00','2026-2027',NULL,NULL);
INSERT INTO "emploi_du_temps" VALUES ('11','2','2','1','vendredi','10:00:00','12:00:00','2026-2027',NULL,NULL);
INSERT INTO "emploi_du_temps" VALUES ('12','2','2','1','samedi','14:00:00','16:00:00','2026-2027',NULL,NULL);
INSERT INTO "emploi_du_temps" VALUES ('13','3','3','1','lundi','08:00:00','10:00:00','2026-2027',NULL,NULL);
INSERT INTO "emploi_du_temps" VALUES ('14','3','3','1','mardi','10:00:00','12:00:00','2026-2027',NULL,NULL);
INSERT INTO "emploi_du_temps" VALUES ('15','3','3','1','mercredi','14:00:00','16:00:00','2026-2027',NULL,NULL);
INSERT INTO "emploi_du_temps" VALUES ('16','3','3','1','jeudi','08:00:00','10:00:00','2026-2027',NULL,NULL);
INSERT INTO "emploi_du_temps" VALUES ('17','3','3','1','vendredi','10:00:00','12:00:00','2026-2027',NULL,NULL);
INSERT INTO "emploi_du_temps" VALUES ('18','3','3','1','samedi','14:00:00','16:00:00','2026-2027',NULL,NULL);


DROP TABLE IF EXISTS "enseignants";
CREATE TABLE "enseignants" ("id" integer primary key autoincrement not null, "nom" varchar not null, "prenom" varchar not null, "email" varchar not null, "telephone" varchar, "specialisation" varchar, "created_at" datetime, "updated_at" datetime, "qr_token" varchar, "matiere" varchar, "annee_scolaire" varchar, "date_embauche" date, "statut" varchar not null default 'actif');



DROP TABLE IF EXISTS "equipements";
CREATE TABLE "equipements" ("id" integer primary key autoincrement not null, "nom" varchar not null, "type" varchar not null, "description" text, "statut" varchar not null default 'disponible', "created_at" datetime, "updated_at" datetime, "quantite" varchar);



DROP TABLE IF EXISTS "evenements";
CREATE TABLE "evenements" ("id" integer primary key autoincrement not null, "titre" varchar not null, "description" text, "date_debut" date not null, "date_fin" date, "lieu" varchar, "annee_scolaire" varchar not null, "created_at" datetime, "updated_at" datetime, "type" varchar, "createur_id" integer, "date_creation" datetime);

INSERT INTO "evenements" VALUES ('1','Réunion parentale','Merci à toi bro','2026-07-07 09:00:00','2026-07-07 17:00:00',NULL,'2026-2027',NULL,NULL,'reunion','5',NULL);


DROP TABLE IF EXISTS "examen_blanc";
CREATE TABLE "examen_blanc" ("id" integer primary key autoincrement not null, "titre" varchar not null, "classe_id" integer not null, "date_debut" date, "date_fin" date, "annee_scolaire" varchar not null, "created_at" datetime, "updated_at" datetime, "eleve_id" integer, "matiere_id" integer, "session" varchar, "note" numeric, "date_examen" date, foreign key("classe_id") references "classes"("id") on delete cascade);



DROP TABLE IF EXISTS "failed_jobs";
CREATE TABLE "failed_jobs" ("id" integer primary key autoincrement not null, "uuid" varchar not null, "connection" text not null, "queue" text not null, "payload" text not null, "exception" text not null, "failed_at" datetime not null default CURRENT_TIMESTAMP);



DROP TABLE IF EXISTS "fichiers";
CREATE TABLE "fichiers" ("id" integer primary key autoincrement not null, "dossier_id" integer, "nom" varchar not null, "chemin" varchar not null, "type_mime" varchar, "taille" integer, "created_at" datetime, "updated_at" datetime, foreign key("dossier_id") references "dossiers"("id") on delete cascade);



DROP TABLE IF EXISTS "job_batches";
CREATE TABLE "job_batches" ("id" varchar not null, "name" varchar not null, "total_jobs" integer not null, "pending_jobs" integer not null, "failed_jobs" integer not null, "failed_job_ids" text not null, "options" text, "cancelled_at" integer, "created_at" integer not null, "finished_at" integer, primary key ("id"));



DROP TABLE IF EXISTS "jobs";
CREATE TABLE "jobs" ("id" integer primary key autoincrement not null, "queue" varchar not null, "payload" text not null, "attempts" integer not null, "reserved_at" integer, "available_at" integer not null, "created_at" integer not null);



DROP TABLE IF EXISTS "licence";
CREATE TABLE "licence" ("id" integer primary key autoincrement not null, "cle_licence" varchar not null, "proprietaire" varchar not null, "date_activation" date, "date_expiration" date, "statut" varchar not null default 'active', "donnees_licence" text, "created_at" datetime, "updated_at" datetime);



DROP TABLE IF EXISTS "matieres";
CREATE TABLE "matieres" ("id" integer primary key autoincrement not null, "nom" varchar not null, "code" varchar, "created_at" datetime, "updated_at" datetime);

INSERT INTO "matieres" VALUES ('1','Malagasy','Mala',NULL,NULL);
INSERT INTO "matieres" VALUES ('2','Francais','Fran',NULL,NULL);
INSERT INTO "matieres" VALUES ('3','Mathematiques','Math',NULL,NULL);
INSERT INTO "matieres" VALUES ('4','Histoire-Geographie','Hist',NULL,NULL);
INSERT INTO "matieres" VALUES ('5','Sciences','Scie',NULL,NULL);


DROP TABLE IF EXISTS "message_reactions";
CREATE TABLE "message_reactions" ("id" integer primary key autoincrement not null, "message_id" integer not null, "user_type" varchar check ("user_type" in ('admin', 'enseignant', 'staff', 'parent')) not null, "user_id" integer not null, "emoji" varchar not null, "created_at" datetime, "updated_at" datetime, foreign key("message_id") references "messages"("id") on delete cascade);



DROP TABLE IF EXISTS "messages";
CREATE TABLE "messages" ("id" integer primary key autoincrement not null, "conversation_id" integer not null, "sender_type" varchar not null default 'admin', "sender_id" integer not null default '0', "content" text, "type" varchar not null default 'text', "file_path" varchar, "file_name" varchar, "file_size" integer, "is_read" tinyint(1) not null default '0', "is_delivered" tinyint(1) not null default '0', "deleted_at" datetime, "created_at" datetime, "updated_at" datetime, foreign key("conversation_id") references "conversations"("id") on delete cascade);

INSERT INTO "messages" VALUES ('1','1','admin','5','salut','text',NULL,NULL,NULL,'0','0',NULL,'2026-07-04 11:58:48',NULL);
INSERT INTO "messages" VALUES ('2','1','admin','5','[Image envoyee]','image','uploads/chat_files/1783166336_6TDBg5MXIKAwQn12.png','novaskol.png','899881','0','0',NULL,'2026-07-04 11:58:56',NULL);


DROP TABLE IF EXISTS "migrations";
CREATE TABLE "migrations" ("id" integer primary key autoincrement not null, "migration" varchar not null, "batch" integer not null);

INSERT INTO "migrations" VALUES ('1','0001_01_01_000000_create_users_table','1');
INSERT INTO "migrations" VALUES ('2','0001_01_01_000001_create_cache_table','1');
INSERT INTO "migrations" VALUES ('3','0001_01_01_000002_create_jobs_table','1');
INSERT INTO "migrations" VALUES ('4','2026_05_04_000001_create_presence_eleves_table','1');
INSERT INTO "migrations" VALUES ('5','2026_05_04_000002_create_teacher_workspace_tables','1');
INSERT INTO "migrations" VALUES ('6','2026_05_05_000001_create_parent_eleves_table','1');
INSERT INTO "migrations" VALUES ('7','2026_05_05_000002_add_is_announcement_to_conversations_table','1');
INSERT INTO "migrations" VALUES ('8','2026_05_08_000001_create_offline_sync_tables','1');
INSERT INTO "migrations" VALUES ('9','2026_05_08_000002_create_sync_record_keys_table','1');
INSERT INTO "migrations" VALUES ('10','2026_05_08_000003_extend_sync_devices_for_pairing','1');
INSERT INTO "migrations" VALUES ('11','2026_05_17_000000_create_classes_table','2');
INSERT INTO "migrations" VALUES ('12','2026_05_17_000001_create_eleves_table','2');
INSERT INTO "migrations" VALUES ('13','2026_05_18_000000_create_core_tables','2');
INSERT INTO "migrations" VALUES ('14','2026_05_18_000001_create_utilisateurs_table','2');
INSERT INTO "migrations" VALUES ('15','2026_05_18_000002_create_sessions_table','2');
INSERT INTO "migrations" VALUES ('16','2026_05_18_000003_create_parametres_table','2');
INSERT INTO "migrations" VALUES ('17','2026_05_18_000004_create_ecole_table','2');
INSERT INTO "migrations" VALUES ('18','2026_05_18_000005_create_permissions_table','2');
INSERT INTO "migrations" VALUES ('19','2026_05_19_000000_create_all_missing_tables','2');
INSERT INTO "migrations" VALUES ('20','2026_05_19_000001_create_remaining_tables','2');
INSERT INTO "migrations" VALUES ('21','2026_06_26_000001_add_role_id_to_staff_table','3');
INSERT INTO "migrations" VALUES ('22','2026_06_26_000002_add_qr_token_to_tables','3');
INSERT INTO "migrations" VALUES ('23','2026_06_26_000003_add_scan_fields_to_presence','3');
INSERT INTO "migrations" VALUES ('24','2026_06_27_000001_fix_mpiasa_table','4');
INSERT INTO "migrations" VALUES ('25','2026_06_28_050605_add_statut_to_staff_table','4');
INSERT INTO "migrations" VALUES ('26','2026_06_28_060000_add_original_mysql_columns','4');
INSERT INTO "migrations" VALUES ('27','2026_06_28_070000_add_all_original_mysql_columns','5');
INSERT INTO "migrations" VALUES ('28','2026_06_28_080000_add_mysql_columns_to_parents_table','6');
INSERT INTO "migrations" VALUES ('29','2026_06_28_090000_add_type_scan_to_presence_eleves','7');
INSERT INTO "migrations" VALUES ('30','2026_06_30_100000_add_type_scan_to_presence_tables','8');


DROP TABLE IF EXISTS "mpiasa";
CREATE TABLE "mpiasa" ("id" integer primary key autoincrement not null, "nom" varchar not null, "description" text, "created_at" datetime, "updated_at" datetime, "prenom" varchar, "email" varchar, "telephone" varchar, "type_personne" varchar, "annee_scolaire" varchar);

INSERT INTO "mpiasa" VALUES ('1','Rakoto',NULL,NULL,NULL,'Demo','enseignant.demo@novaskol.local','0340000001','professeur','2026-2027');


DROP TABLE IF EXISTS "notes";
CREATE TABLE "notes" ("id" integer primary key autoincrement not null, "eleve_id" integer not null, "matiere_id" integer not null, "professeur_id" integer, "valeur" float, "trimestre" integer, "annee_scolaire" varchar not null, "observation" text, "created_at" datetime, "updated_at" datetime, "id_eleve" integer, "id_matiere" integer, "note" float, "coefficient" integer not null default '1', "remarque" text, "periode" varchar, "type_note" varchar, foreign key("eleve_id") references "eleves"("id") on delete cascade, foreign key("matiere_id") references "matieres"("id") on delete cascade, foreign key("professeur_id") references "professeurs"("id") on delete set null);

INSERT INTO "notes" VALUES ('1','1','1','1','15',NULL,'2026-2027',NULL,NULL,NULL,'1','1','16','2',NULL,'T1',NULL);
INSERT INTO "notes" VALUES ('2','1','2','1','13',NULL,'2026-2027',NULL,NULL,NULL,'1','2','14','2',NULL,'T1',NULL);
INSERT INTO "notes" VALUES ('3','1','3','1','10',NULL,'2026-2027',NULL,NULL,NULL,'1','3','16','2',NULL,'T1',NULL);
INSERT INTO "notes" VALUES ('4','1','4','1','15',NULL,'2026-2027',NULL,NULL,NULL,'1','4','10','2',NULL,'T1',NULL);
INSERT INTO "notes" VALUES ('5','1','5','1','11',NULL,'2026-2027',NULL,NULL,NULL,'1','5','17','2',NULL,'T1',NULL);
INSERT INTO "notes" VALUES ('6','2','1','1','12',NULL,'2026-2027',NULL,NULL,NULL,'2','1','18','2',NULL,'T1',NULL);
INSERT INTO "notes" VALUES ('7','2','2','1','18',NULL,'2026-2027',NULL,NULL,NULL,'2','2','17','2',NULL,'T1',NULL);
INSERT INTO "notes" VALUES ('8','2','3','1','14',NULL,'2026-2027',NULL,NULL,NULL,'2','3','11','2',NULL,'T1',NULL);
INSERT INTO "notes" VALUES ('9','2','4','1','15',NULL,'2026-2027',NULL,NULL,NULL,'2','4','17','2',NULL,'T1',NULL);
INSERT INTO "notes" VALUES ('10','2','5','1','15',NULL,'2026-2027',NULL,NULL,NULL,'2','5','16','2',NULL,'T1',NULL);
INSERT INTO "notes" VALUES ('11','3','1','1','17',NULL,'2026-2027',NULL,NULL,NULL,'3','1','18','2',NULL,'T1',NULL);
INSERT INTO "notes" VALUES ('12','3','2','1','10',NULL,'2026-2027',NULL,NULL,NULL,'3','2','15','2',NULL,'T1',NULL);
INSERT INTO "notes" VALUES ('13','3','3','1','18',NULL,'2026-2027',NULL,NULL,NULL,'3','3','17','2',NULL,'T1',NULL);
INSERT INTO "notes" VALUES ('14','3','4','1','18',NULL,'2026-2027',NULL,NULL,NULL,'3','4','13','2',NULL,'T1',NULL);
INSERT INTO "notes" VALUES ('15','3','5','1','16',NULL,'2026-2027',NULL,NULL,NULL,'3','5','11','2',NULL,'T1',NULL);
INSERT INTO "notes" VALUES ('16','4','1','1','11',NULL,'2026-2027',NULL,NULL,NULL,'4','1','15','2',NULL,'T1',NULL);
INSERT INTO "notes" VALUES ('17','4','2','1','16',NULL,'2026-2027',NULL,NULL,NULL,'4','2','13','2',NULL,'T1',NULL);
INSERT INTO "notes" VALUES ('18','4','3','1','12',NULL,'2026-2027',NULL,NULL,NULL,'4','3','10','2',NULL,'T1',NULL);
INSERT INTO "notes" VALUES ('19','4','4','1','12',NULL,'2026-2027',NULL,NULL,NULL,'4','4','14','2',NULL,'T1',NULL);
INSERT INTO "notes" VALUES ('20','4','5','1','15',NULL,'2026-2027',NULL,NULL,NULL,'4','5','13','2',NULL,'T1',NULL);


DROP TABLE IF EXISTS "notifications";
CREATE TABLE "notifications" ("id" integer primary key autoincrement not null, "user_type" varchar check ("user_type" in ('admin', 'enseignant', 'staff', 'parent')) not null, "user_id" integer not null, "titre" varchar not null, "contenu" text, "lue" tinyint(1) not null default '0', "read_at" datetime, "created_at" datetime, "updated_at" datetime, "type" varchar, "message" text, "destinataire_id" integer, "date_creation" datetime, "statut" varchar, "date_envoi" datetime, "lu" tinyint(1) not null default '0');

INSERT INTO "notifications" VALUES ('1','admin','5','presence',NULL,'0',NULL,NULL,NULL,'presence','Staff Randria (Staff) : Entree 14:57 (retard)',NULL,'2026-07-04 11:57:39','non lu','2026-07-04 11:57:39','0');
INSERT INTO "notifications" VALUES ('2','admin','5','Paiement',NULL,'0',NULL,NULL,NULL,'Paiement','Paiement ecolage enregistre pour Ando Tiana.',NULL,'2026-07-04 12:00:35','non lu','2026-07-04 12:00:35','0');
INSERT INTO "notifications" VALUES ('3','admin','5','sauvegarde',NULL,'0',NULL,NULL,NULL,'sauvegarde','Nouvelle sauvegarde : backup_20260704_123313.sql',NULL,'2026-07-04 12:33:13','non lu','2026-07-04 12:33:13','0');


DROP TABLE IF EXISTS "paiements";
CREATE TABLE "paiements" ("id" integer primary key autoincrement not null, "type_id" integer, "personne_id" varchar, "type_personne" varchar, "mois" varchar, "annee_scolaire" varchar not null, "montant" numeric, "description" text, "mode_paiement" varchar, "statut" varchar, "categorie" varchar not null, "created_at" datetime, "updated_at" datetime, "nom_personne" varchar, "date_enregistrement" datetime, foreign key("type_id") references "types_paiements"("id") on delete set null);

INSERT INTO "paiements" VALUES ('1','1',NULL,NULL,'["Juillet"]','2026-2027','150000',NULL,NULL,'non_paye','Ecolage',NULL,NULL,NULL,NULL);
INSERT INTO "paiements" VALUES ('2','1',NULL,NULL,'["Juillet"]','2026-2027','150000',NULL,NULL,'non_paye','Ecolage',NULL,NULL,NULL,NULL);
INSERT INTO "paiements" VALUES ('3','1',NULL,NULL,'["Juillet"]','2026-2027','150000',NULL,NULL,'non_paye','Ecolage',NULL,NULL,NULL,NULL);
INSERT INTO "paiements" VALUES ('4','1',NULL,NULL,'["Juillet"]','2026-2027','150000',NULL,NULL,'non_paye','Ecolage',NULL,NULL,NULL,NULL);


DROP TABLE IF EXISTS "paiements_assignes";
CREATE TABLE "paiements_assignes" ("id" integer primary key autoincrement not null, "paiement_id" integer not null, "eleve_id" integer, "professeur_id" integer, "montant" numeric not null, "statut" varchar not null default 'en_attente', "created_at" datetime, "updated_at" datetime, "type_id" integer, "person_id" integer, "type_personne" varchar, foreign key("paiement_id") references "paiements"("id") on delete cascade, foreign key("eleve_id") references "eleves"("id") on delete set null, foreign key("professeur_id") references "professeurs"("id") on delete set null);

INSERT INTO "paiements_assignes" VALUES ('1','1','1',NULL,'150000','non_paye',NULL,NULL,'1',NULL,NULL);
INSERT INTO "paiements_assignes" VALUES ('2','2','2',NULL,'150000','paye',NULL,NULL,'1',NULL,NULL);
INSERT INTO "paiements_assignes" VALUES ('3','3','3',NULL,'150000','non_paye',NULL,NULL,'1',NULL,NULL);
INSERT INTO "paiements_assignes" VALUES ('4','4','4',NULL,'150000','non_paye',NULL,NULL,'1',NULL,NULL);


DROP TABLE IF EXISTS "parametres";
CREATE TABLE "parametres" ("cle" varchar not null, "valeur" text, "created_at" datetime, "updated_at" datetime, primary key ("cle"));

INSERT INTO "parametres" VALUES ('nom_ecole','Ecole Demo',NULL,NULL);
INSERT INTO "parametres" VALUES ('annee_scolaire','2026-2027',NULL,NULL);
INSERT INTO "parametres" VALUES ('mode_installation','demo',NULL,NULL);
INSERT INTO "parametres" VALUES ('sync_device_uuid','bbcc1070-cd7a-4dce-ba43-82e9d450839e',NULL,NULL);


DROP TABLE IF EXISTS "parent_eleves";
CREATE TABLE "parent_eleves" ("id" integer primary key autoincrement not null, "parent_user_id" integer not null, "eleve_id" integer not null, "lien" varchar not null default 'parent', "nom_contact" varchar, "telephone" varchar, "principal" tinyint(1) not null default '1', "created_at" datetime, "updated_at" datetime);

INSERT INTO "parent_eleves" VALUES ('1','4','1','parent',NULL,NULL,'1',NULL,NULL);


DROP TABLE IF EXISTS "parents";
CREATE TABLE "parents" ("id" integer primary key autoincrement not null, "nom" varchar not null, "prenom" varchar, "lien" varchar not null, "telephone" varchar, "email" varchar, "adresse" varchar, "profession" varchar, "created_at" datetime, "updated_at" datetime, "nom_pere" varchar, "telephone_pere" varchar, "profession_pere" varchar, "adresse_pere" text, "nom_mere" varchar, "telephone_mere" varchar, "profession_mere" varchar, "adresse_mere" text, "annee_scolaire" varchar);

INSERT INTO "parents" VALUES ('1','Parent','Demo','Pere','0340000200','parent.demo@novaskol.local','Antananarivo','Enseignant',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL);


DROP TABLE IF EXISTS "password_reset_tokens";
CREATE TABLE "password_reset_tokens" ("email" varchar not null, "token" varchar not null, "created_at" datetime, primary key ("email"));



DROP TABLE IF EXISTS "permissions";
CREATE TABLE "permissions" ("id" integer primary key autoincrement not null, "utilisateur_id" integer not null, "module" varchar not null, "role" varchar, "acces" varchar, "created_at" datetime, "updated_at" datetime);

INSERT INTO "permissions" VALUES ('1','1','Administration','admin','ecriture',NULL,NULL);
INSERT INTO "permissions" VALUES ('2','1','dashboard','admin','ecriture',NULL,NULL);
INSERT INTO "permissions" VALUES ('3','1','ecole','admin','ecriture',NULL,NULL);
INSERT INTO "permissions" VALUES ('4','1','Admin','admin','ecriture',NULL,NULL);
INSERT INTO "permissions" VALUES ('5','1','inscription','admin','ecriture',NULL,NULL);
INSERT INTO "permissions" VALUES ('6','1','liste_classes','admin','ecriture',NULL,NULL);
INSERT INTO "permissions" VALUES ('7','1','matieres','admin','ecriture',NULL,NULL);
INSERT INTO "permissions" VALUES ('8','1','Enseignantss','admin','ecriture',NULL,NULL);
INSERT INTO "permissions" VALUES ('9','1','notes','admin','ecriture',NULL,NULL);
INSERT INTO "permissions" VALUES ('10','1','bulletin','admin','ecriture',NULL,NULL);
INSERT INTO "permissions" VALUES ('11','1','resultats','admin','ecriture',NULL,NULL);
INSERT INTO "permissions" VALUES ('12','1','examen_blanc','admin','ecriture',NULL,NULL);
INSERT INTO "permissions" VALUES ('13','1','resultats_examen_blanc','admin','ecriture',NULL,NULL);
INSERT INTO "permissions" VALUES ('14','1','Pedagogique','admin','ecriture',NULL,NULL);
INSERT INTO "permissions" VALUES ('15','1','emploi_temps','admin','ecriture',NULL,NULL);
INSERT INTO "permissions" VALUES ('16','1','fiche_presence','admin','ecriture',NULL,NULL);
INSERT INTO "permissions" VALUES ('17','1','calendrier','admin','ecriture',NULL,NULL);
INSERT INTO "permissions" VALUES ('18','1','notifications','admin','ecriture',NULL,NULL);
INSERT INTO "permissions" VALUES ('19','1','cartes','admin','ecriture',NULL,NULL);
INSERT INTO "permissions" VALUES ('20','1','depot_dossier','admin','ecriture',NULL,NULL);
INSERT INTO "permissions" VALUES ('21','1','fpe','admin','ecriture',NULL,NULL);
INSERT INTO "permissions" VALUES ('22','1','liste_assurance','admin','ecriture',NULL,NULL);
INSERT INTO "permissions" VALUES ('23','1','RH','admin','ecriture',NULL,NULL);
INSERT INTO "permissions" VALUES ('24','1','enseignants','admin','ecriture',NULL,NULL);
INSERT INTO "permissions" VALUES ('25','1','staff','admin','ecriture',NULL,NULL);
INSERT INTO "permissions" VALUES ('26','1','pointage','admin','ecriture',NULL,NULL);
INSERT INTO "permissions" VALUES ('27','1','permissions','admin','ecriture',NULL,NULL);
INSERT INTO "permissions" VALUES ('28','1','gestion_ressource','admin','ecriture',NULL,NULL);
INSERT INTO "permissions" VALUES ('29','1','Communication','admin','ecriture',NULL,NULL);
INSERT INTO "permissions" VALUES ('30','1','communication','admin','ecriture',NULL,NULL);
INSERT INTO "permissions" VALUES ('31','1','chat_private','admin','ecriture',NULL,NULL);
INSERT INTO "permissions" VALUES ('32','1','chat_group','admin','ecriture',NULL,NULL);
INSERT INTO "permissions" VALUES ('33','1','Paiement','admin','ecriture',NULL,NULL);
INSERT INTO "permissions" VALUES ('34','1','detail_paiement','admin','ecriture',NULL,NULL);
INSERT INTO "permissions" VALUES ('35','1','comptable','admin','ecriture',NULL,NULL);
INSERT INTO "permissions" VALUES ('36','1','liste_paiements','admin','ecriture',NULL,NULL);
INSERT INTO "permissions" VALUES ('37','1','facture','admin','ecriture',NULL,NULL);
INSERT INTO "permissions" VALUES ('38','1','Sectrapport','admin','ecriture',NULL,NULL);
INSERT INTO "permissions" VALUES ('39','1','rapport_comptable','admin','ecriture',NULL,NULL);
INSERT INTO "permissions" VALUES ('40','1','rapport_presence','admin','ecriture',NULL,NULL);
INSERT INTO "permissions" VALUES ('41','1','rapport_staff','admin','ecriture',NULL,NULL);
INSERT INTO "permissions" VALUES ('42','1','evaluation_notes','admin','ecriture',NULL,NULL);
INSERT INTO "permissions" VALUES ('43','1','Sectrapp','admin','ecriture',NULL,NULL);
INSERT INTO "permissions" VALUES ('44','1','parametres','admin','ecriture',NULL,NULL);
INSERT INTO "permissions" VALUES ('45','1','comptes_utilisateurs','admin','ecriture',NULL,NULL);
INSERT INTO "permissions" VALUES ('46','1','diagnostic_systeme','admin','ecriture',NULL,NULL);
INSERT INTO "permissions" VALUES ('47','1','apropos_novaskol','admin','ecriture',NULL,NULL);
INSERT INTO "permissions" VALUES ('48','1','reseau_local','admin','ecriture',NULL,NULL);
INSERT INTO "permissions" VALUES ('49','1','guide_utilisation','admin','ecriture',NULL,NULL);
INSERT INTO "permissions" VALUES ('50','1','sauvegardes','admin','ecriture',NULL,NULL);
INSERT INTO "permissions" VALUES ('51','1','parent_portal','admin','ecriture',NULL,NULL);
INSERT INTO "permissions" VALUES ('52','5','Administration','admin','ecriture',NULL,NULL);
INSERT INTO "permissions" VALUES ('53','5','dashboard','admin','ecriture',NULL,NULL);
INSERT INTO "permissions" VALUES ('54','5','ecole','admin','ecriture',NULL,NULL);
INSERT INTO "permissions" VALUES ('55','5','Admin','admin','ecriture',NULL,NULL);
INSERT INTO "permissions" VALUES ('56','5','inscription','admin','ecriture',NULL,NULL);
INSERT INTO "permissions" VALUES ('57','5','liste_classes','admin','ecriture',NULL,NULL);
INSERT INTO "permissions" VALUES ('58','5','matieres','admin','ecriture',NULL,NULL);
INSERT INTO "permissions" VALUES ('59','5','Enseignantss','admin','ecriture',NULL,NULL);
INSERT INTO "permissions" VALUES ('60','5','notes','admin','ecriture',NULL,NULL);
INSERT INTO "permissions" VALUES ('61','5','bulletin','admin','ecriture',NULL,NULL);
INSERT INTO "permissions" VALUES ('62','5','resultats','admin','ecriture',NULL,NULL);
INSERT INTO "permissions" VALUES ('63','5','examen_blanc','admin','ecriture',NULL,NULL);
INSERT INTO "permissions" VALUES ('64','5','resultats_examen_blanc','admin','ecriture',NULL,NULL);
INSERT INTO "permissions" VALUES ('65','5','Pedagogique','admin','ecriture',NULL,NULL);
INSERT INTO "permissions" VALUES ('66','5','emploi_temps','admin','ecriture',NULL,NULL);
INSERT INTO "permissions" VALUES ('67','5','fiche_presence','admin','ecriture',NULL,NULL);
INSERT INTO "permissions" VALUES ('68','5','calendrier','admin','ecriture',NULL,NULL);
INSERT INTO "permissions" VALUES ('69','5','notifications','admin','ecriture',NULL,NULL);
INSERT INTO "permissions" VALUES ('70','5','cartes','admin','ecriture',NULL,NULL);
INSERT INTO "permissions" VALUES ('71','5','depot_dossier','admin','ecriture',NULL,NULL);
INSERT INTO "permissions" VALUES ('72','5','fpe','admin','ecriture',NULL,NULL);
INSERT INTO "permissions" VALUES ('73','5','liste_assurance','admin','ecriture',NULL,NULL);
INSERT INTO "permissions" VALUES ('74','5','RH','admin','ecriture',NULL,NULL);
INSERT INTO "permissions" VALUES ('75','5','enseignants','admin','ecriture',NULL,NULL);
INSERT INTO "permissions" VALUES ('76','5','staff','admin','ecriture',NULL,NULL);
INSERT INTO "permissions" VALUES ('77','5','pointage','admin','ecriture',NULL,NULL);
INSERT INTO "permissions" VALUES ('78','5','permissions','admin','ecriture',NULL,NULL);
INSERT INTO "permissions" VALUES ('79','5','gestion_ressource','admin','ecriture',NULL,NULL);
INSERT INTO "permissions" VALUES ('80','5','Communication','admin','ecriture',NULL,NULL);
INSERT INTO "permissions" VALUES ('81','5','communication','admin','ecriture',NULL,NULL);
INSERT INTO "permissions" VALUES ('82','5','chat_private','admin','ecriture',NULL,NULL);
INSERT INTO "permissions" VALUES ('83','5','chat_group','admin','ecriture',NULL,NULL);
INSERT INTO "permissions" VALUES ('84','5','Paiement','admin','ecriture',NULL,NULL);
INSERT INTO "permissions" VALUES ('85','5','detail_paiement','admin','ecriture',NULL,NULL);
INSERT INTO "permissions" VALUES ('86','5','comptable','admin','ecriture',NULL,NULL);
INSERT INTO "permissions" VALUES ('87','5','liste_paiements','admin','ecriture',NULL,NULL);
INSERT INTO "permissions" VALUES ('88','5','facture','admin','ecriture',NULL,NULL);
INSERT INTO "permissions" VALUES ('89','5','Sectrapport','admin','ecriture',NULL,NULL);
INSERT INTO "permissions" VALUES ('90','5','rapport_comptable','admin','ecriture',NULL,NULL);
INSERT INTO "permissions" VALUES ('91','5','rapport_presence','admin','ecriture',NULL,NULL);
INSERT INTO "permissions" VALUES ('92','5','rapport_staff','admin','ecriture',NULL,NULL);
INSERT INTO "permissions" VALUES ('93','5','evaluation_notes','admin','ecriture',NULL,NULL);
INSERT INTO "permissions" VALUES ('94','5','Sectrapp','admin','ecriture',NULL,NULL);
INSERT INTO "permissions" VALUES ('95','5','parametres','admin','ecriture',NULL,NULL);
INSERT INTO "permissions" VALUES ('96','5','comptes_utilisateurs','admin','ecriture',NULL,NULL);
INSERT INTO "permissions" VALUES ('97','5','diagnostic_systeme','admin','ecriture',NULL,NULL);
INSERT INTO "permissions" VALUES ('98','5','apropos_novaskol','admin','ecriture',NULL,NULL);
INSERT INTO "permissions" VALUES ('99','5','reseau_local','admin','ecriture',NULL,NULL);
INSERT INTO "permissions" VALUES ('100','5','guide_utilisation','admin','ecriture',NULL,NULL);
INSERT INTO "permissions" VALUES ('101','5','sauvegardes','admin','ecriture',NULL,NULL);
INSERT INTO "permissions" VALUES ('102','2','Administration','enseignant','masquer',NULL,NULL);
INSERT INTO "permissions" VALUES ('103','2','dashboard','enseignant','masquer',NULL,NULL);
INSERT INTO "permissions" VALUES ('104','2','ecole','enseignant','masquer',NULL,NULL);
INSERT INTO "permissions" VALUES ('105','2','Admin','enseignant','masquer',NULL,NULL);
INSERT INTO "permissions" VALUES ('106','2','inscription','enseignant','masquer',NULL,NULL);
INSERT INTO "permissions" VALUES ('107','2','liste_classes','enseignant','masquer',NULL,NULL);
INSERT INTO "permissions" VALUES ('108','2','matieres','enseignant','masquer',NULL,NULL);
INSERT INTO "permissions" VALUES ('109','2','Enseignantss','enseignant','masquer',NULL,NULL);
INSERT INTO "permissions" VALUES ('110','2','notes','enseignant','ecriture',NULL,NULL);
INSERT INTO "permissions" VALUES ('111','2','bulletin','enseignant','lecture',NULL,NULL);
INSERT INTO "permissions" VALUES ('112','2','resultats','enseignant','lecture',NULL,NULL);
INSERT INTO "permissions" VALUES ('113','2','examen_blanc','enseignant','masquer',NULL,NULL);
INSERT INTO "permissions" VALUES ('114','2','resultats_examen_blanc','enseignant','masquer',NULL,NULL);
INSERT INTO "permissions" VALUES ('115','2','Pedagogique','enseignant','masquer',NULL,NULL);
INSERT INTO "permissions" VALUES ('116','2','emploi_temps','enseignant','lecture',NULL,NULL);
INSERT INTO "permissions" VALUES ('117','2','fiche_presence','enseignant','masquer',NULL,NULL);
INSERT INTO "permissions" VALUES ('118','2','calendrier','enseignant','lecture',NULL,NULL);
INSERT INTO "permissions" VALUES ('119','2','notifications','enseignant','lecture',NULL,NULL);
INSERT INTO "permissions" VALUES ('120','2','cartes','enseignant','masquer',NULL,NULL);
INSERT INTO "permissions" VALUES ('121','2','depot_dossier','enseignant','masquer',NULL,NULL);
INSERT INTO "permissions" VALUES ('122','2','fpe','enseignant','masquer',NULL,NULL);
INSERT INTO "permissions" VALUES ('123','2','liste_assurance','enseignant','masquer',NULL,NULL);
INSERT INTO "permissions" VALUES ('124','2','RH','enseignant','masquer',NULL,NULL);
INSERT INTO "permissions" VALUES ('125','2','enseignants','enseignant','masquer',NULL,NULL);
INSERT INTO "permissions" VALUES ('126','2','staff','enseignant','masquer',NULL,NULL);
INSERT INTO "permissions" VALUES ('127','2','pointage','enseignant','masquer',NULL,NULL);
INSERT INTO "permissions" VALUES ('128','2','permissions','enseignant','masquer',NULL,NULL);
INSERT INTO "permissions" VALUES ('129','2','gestion_ressource','enseignant','masquer',NULL,NULL);
INSERT INTO "permissions" VALUES ('130','2','Communication','enseignant','masquer',NULL,NULL);
INSERT INTO "permissions" VALUES ('131','2','communication','enseignant','masquer',NULL,NULL);
INSERT INTO "permissions" VALUES ('132','2','chat_private','enseignant','ecriture',NULL,NULL);
INSERT INTO "permissions" VALUES ('133','2','chat_group','enseignant','ecriture',NULL,NULL);
INSERT INTO "permissions" VALUES ('134','2','Paiement','enseignant','masquer',NULL,NULL);
INSERT INTO "permissions" VALUES ('135','2','detail_paiement','enseignant','masquer',NULL,NULL);
INSERT INTO "permissions" VALUES ('136','2','comptable','enseignant','masquer',NULL,NULL);
INSERT INTO "permissions" VALUES ('137','2','liste_paiements','enseignant','masquer',NULL,NULL);
INSERT INTO "permissions" VALUES ('138','2','facture','enseignant','masquer',NULL,NULL);
INSERT INTO "permissions" VALUES ('139','2','Sectrapport','enseignant','masquer',NULL,NULL);
INSERT INTO "permissions" VALUES ('140','2','rapport_comptable','enseignant','masquer',NULL,NULL);
INSERT INTO "permissions" VALUES ('141','2','rapport_presence','enseignant','lecture',NULL,NULL);
INSERT INTO "permissions" VALUES ('142','2','rapport_staff','enseignant','masquer',NULL,NULL);
INSERT INTO "permissions" VALUES ('143','2','evaluation_notes','enseignant','masquer',NULL,NULL);
INSERT INTO "permissions" VALUES ('144','2','Sectrapp','enseignant','masquer',NULL,NULL);
INSERT INTO "permissions" VALUES ('145','2','parametres','enseignant','masquer',NULL,NULL);
INSERT INTO "permissions" VALUES ('146','2','comptes_utilisateurs','enseignant','masquer',NULL,NULL);
INSERT INTO "permissions" VALUES ('147','2','diagnostic_systeme','enseignant','masquer',NULL,NULL);
INSERT INTO "permissions" VALUES ('148','2','apropos_novaskol','enseignant','masquer',NULL,NULL);
INSERT INTO "permissions" VALUES ('149','2','reseau_local','enseignant','masquer',NULL,NULL);
INSERT INTO "permissions" VALUES ('150','2','guide_utilisation','enseignant','masquer',NULL,NULL);
INSERT INTO "permissions" VALUES ('151','2','sauvegardes','enseignant','masquer',NULL,NULL);
INSERT INTO "permissions" VALUES ('152','3','Administration','staff','aucun',NULL,NULL);
INSERT INTO "permissions" VALUES ('153','3','dashboard','staff','lecture',NULL,NULL);
INSERT INTO "permissions" VALUES ('154','3','ecole','staff','lecture',NULL,NULL);
INSERT INTO "permissions" VALUES ('155','3','Admin','staff','aucun',NULL,NULL);
INSERT INTO "permissions" VALUES ('156','3','inscription','staff','aucun',NULL,NULL);
INSERT INTO "permissions" VALUES ('157','3','liste_classes','staff','aucun',NULL,NULL);
INSERT INTO "permissions" VALUES ('158','3','matieres','staff','aucun',NULL,NULL);
INSERT INTO "permissions" VALUES ('159','3','Enseignantss','staff','aucun',NULL,NULL);
INSERT INTO "permissions" VALUES ('160','3','notes','staff','aucun',NULL,NULL);
INSERT INTO "permissions" VALUES ('161','3','bulletin','staff','aucun',NULL,NULL);
INSERT INTO "permissions" VALUES ('162','3','resultats','staff','aucun',NULL,NULL);
INSERT INTO "permissions" VALUES ('163','3','examen_blanc','staff','aucun',NULL,NULL);
INSERT INTO "permissions" VALUES ('164','3','resultats_examen_blanc','staff','aucun',NULL,NULL);
INSERT INTO "permissions" VALUES ('165','3','Pedagogique','staff','aucun',NULL,NULL);
INSERT INTO "permissions" VALUES ('166','3','emploi_temps','staff','aucun',NULL,NULL);
INSERT INTO "permissions" VALUES ('167','3','fiche_presence','staff','aucun',NULL,NULL);
INSERT INTO "permissions" VALUES ('168','3','calendrier','staff','aucun',NULL,NULL);
INSERT INTO "permissions" VALUES ('169','3','notifications','staff','aucun',NULL,NULL);
INSERT INTO "permissions" VALUES ('170','3','cartes','staff','aucun',NULL,NULL);
INSERT INTO "permissions" VALUES ('171','3','depot_dossier','staff','aucun',NULL,NULL);
INSERT INTO "permissions" VALUES ('172','3','fpe','staff','aucun',NULL,NULL);
INSERT INTO "permissions" VALUES ('173','3','liste_assurance','staff','aucun',NULL,NULL);
INSERT INTO "permissions" VALUES ('174','3','RH','staff','aucun',NULL,NULL);
INSERT INTO "permissions" VALUES ('175','3','enseignants','staff','aucun',NULL,NULL);
INSERT INTO "permissions" VALUES ('176','3','staff','staff','aucun',NULL,NULL);
INSERT INTO "permissions" VALUES ('177','3','pointage','staff','aucun',NULL,NULL);
INSERT INTO "permissions" VALUES ('178','3','permissions','staff','aucun',NULL,NULL);
INSERT INTO "permissions" VALUES ('179','3','gestion_ressource','staff','aucun',NULL,NULL);
INSERT INTO "permissions" VALUES ('180','3','Communication','staff','aucun',NULL,NULL);
INSERT INTO "permissions" VALUES ('181','3','communication','staff','aucun',NULL,NULL);
INSERT INTO "permissions" VALUES ('182','3','chat_private','staff','aucun',NULL,NULL);
INSERT INTO "permissions" VALUES ('183','3','chat_group','staff','aucun',NULL,NULL);
INSERT INTO "permissions" VALUES ('184','3','Paiement','staff','aucun',NULL,NULL);
INSERT INTO "permissions" VALUES ('185','3','detail_paiement','staff','aucun',NULL,NULL);
INSERT INTO "permissions" VALUES ('186','3','comptable','staff','aucun',NULL,NULL);
INSERT INTO "permissions" VALUES ('187','3','liste_paiements','staff','aucun',NULL,NULL);
INSERT INTO "permissions" VALUES ('188','3','facture','staff','aucun',NULL,NULL);
INSERT INTO "permissions" VALUES ('189','3','Sectrapport','staff','aucun',NULL,NULL);
INSERT INTO "permissions" VALUES ('190','3','rapport_comptable','staff','aucun',NULL,NULL);
INSERT INTO "permissions" VALUES ('191','3','rapport_presence','staff','aucun',NULL,NULL);
INSERT INTO "permissions" VALUES ('192','3','rapport_staff','staff','aucun',NULL,NULL);
INSERT INTO "permissions" VALUES ('193','3','evaluation_notes','staff','aucun',NULL,NULL);
INSERT INTO "permissions" VALUES ('194','3','Sectrapp','staff','aucun',NULL,NULL);
INSERT INTO "permissions" VALUES ('195','3','parametres','staff','aucun',NULL,NULL);
INSERT INTO "permissions" VALUES ('196','3','comptes_utilisateurs','staff','aucun',NULL,NULL);
INSERT INTO "permissions" VALUES ('197','3','diagnostic_systeme','staff','aucun',NULL,NULL);
INSERT INTO "permissions" VALUES ('198','3','apropos_novaskol','staff','aucun',NULL,NULL);
INSERT INTO "permissions" VALUES ('199','3','reseau_local','staff','aucun',NULL,NULL);
INSERT INTO "permissions" VALUES ('200','3','guide_utilisation','staff','aucun',NULL,NULL);
INSERT INTO "permissions" VALUES ('201','3','sauvegardes','staff','aucun',NULL,NULL);
INSERT INTO "permissions" VALUES ('202','4','chat_group','parent','lecture',NULL,NULL);


DROP TABLE IF EXISTS "personnes";
CREATE TABLE "personnes" ("id" integer primary key autoincrement not null, "nom" varchar not null, "prenom" varchar not null, "email" varchar, "telephone" varchar, "created_at" datetime, "updated_at" datetime);



DROP TABLE IF EXISTS "presence_eleves";
CREATE TABLE "presence_eleves" ("id" integer primary key autoincrement not null, "eleve_id" integer not null, "classe_id" integer not null, "annee_scolaire" varchar not null, "mois" integer not null, "date_jour" date not null, "session_jour" varchar check ("session_jour" in ('matin', 'apres_midi')) not null, "statut" varchar check ("statut" in ('present', 'absent', 'retard')) not null default 'present', "commentaire" varchar, "created_at" datetime, "updated_at" datetime, "scan_mode" varchar, "scanned_by" integer, "type_scan" varchar);

INSERT INTO "presence_eleves" VALUES ('17','1','3','2026-2027','6','2026-06-02','matin','present',NULL,NULL,NULL,NULL,NULL,'entree');
INSERT INTO "presence_eleves" VALUES ('18','1','3','2026-2027','6','2026-06-02','apres_midi','absent',NULL,NULL,NULL,NULL,NULL,NULL);
INSERT INTO "presence_eleves" VALUES ('19','2','3','2026-2027','6','2026-06-02','matin','present',NULL,NULL,NULL,NULL,NULL,'entree');
INSERT INTO "presence_eleves" VALUES ('20','2','3','2026-2027','6','2026-06-02','apres_midi','present',NULL,NULL,NULL,NULL,NULL,'entree');
INSERT INTO "presence_eleves" VALUES ('21','3','3','2026-2027','6','2026-06-02','matin','absent',NULL,NULL,NULL,NULL,NULL,NULL);
INSERT INTO "presence_eleves" VALUES ('22','3','3','2026-2027','6','2026-06-02','apres_midi','absent',NULL,NULL,NULL,NULL,NULL,NULL);
INSERT INTO "presence_eleves" VALUES ('23','4','3','2026-2027','6','2026-06-02','matin','retard',NULL,NULL,NULL,NULL,NULL,'entree');
INSERT INTO "presence_eleves" VALUES ('24','4','3','2026-2027','6','2026-06-02','apres_midi','present',NULL,NULL,NULL,NULL,NULL,'entree');
INSERT INTO "presence_eleves" VALUES ('25','1','3','2026-2027','6','2026-06-03','matin','present',NULL,NULL,NULL,NULL,NULL,'entree');
INSERT INTO "presence_eleves" VALUES ('26','1','3','2026-2027','6','2026-06-03','apres_midi','absent',NULL,NULL,NULL,NULL,NULL,NULL);
INSERT INTO "presence_eleves" VALUES ('27','2','3','2026-2027','6','2026-06-03','matin','absent',NULL,NULL,NULL,NULL,NULL,NULL);
INSERT INTO "presence_eleves" VALUES ('28','2','3','2026-2027','6','2026-06-03','apres_midi','absent',NULL,NULL,NULL,NULL,NULL,NULL);
INSERT INTO "presence_eleves" VALUES ('29','3','3','2026-2027','6','2026-06-03','matin','retard',NULL,NULL,NULL,NULL,NULL,'entree');
INSERT INTO "presence_eleves" VALUES ('30','3','3','2026-2027','6','2026-06-03','apres_midi','absent',NULL,NULL,NULL,NULL,NULL,NULL);
INSERT INTO "presence_eleves" VALUES ('31','4','3','2026-2027','6','2026-06-03','matin','retard',NULL,NULL,NULL,NULL,NULL,'entree');
INSERT INTO "presence_eleves" VALUES ('32','4','3','2026-2027','6','2026-06-03','apres_midi','present',NULL,NULL,NULL,NULL,NULL,'entree');
INSERT INTO "presence_eleves" VALUES ('33','1','3','2026-2027','6','2026-06-04','matin','present',NULL,NULL,NULL,NULL,NULL,'entree');
INSERT INTO "presence_eleves" VALUES ('34','1','3','2026-2027','6','2026-06-04','apres_midi','absent',NULL,NULL,NULL,NULL,NULL,NULL);
INSERT INTO "presence_eleves" VALUES ('35','2','3','2026-2027','6','2026-06-04','matin','retard',NULL,NULL,NULL,NULL,NULL,'entree');
INSERT INTO "presence_eleves" VALUES ('36','2','3','2026-2027','6','2026-06-04','apres_midi','present',NULL,NULL,NULL,NULL,NULL,'entree');
INSERT INTO "presence_eleves" VALUES ('37','3','3','2026-2027','6','2026-06-04','matin','present',NULL,NULL,NULL,NULL,NULL,'entree');
INSERT INTO "presence_eleves" VALUES ('38','3','3','2026-2027','6','2026-06-04','apres_midi','present',NULL,NULL,NULL,NULL,NULL,'entree');
INSERT INTO "presence_eleves" VALUES ('39','4','3','2026-2027','6','2026-06-04','matin','present',NULL,NULL,NULL,NULL,NULL,'entree');
INSERT INTO "presence_eleves" VALUES ('40','4','3','2026-2027','6','2026-06-04','apres_midi','present',NULL,NULL,NULL,NULL,NULL,'entree');
INSERT INTO "presence_eleves" VALUES ('41','1','3','2026-2027','6','2026-06-05','matin','present',NULL,NULL,NULL,NULL,NULL,'entree');
INSERT INTO "presence_eleves" VALUES ('42','1','3','2026-2027','6','2026-06-05','apres_midi','present',NULL,NULL,NULL,NULL,NULL,'entree');
INSERT INTO "presence_eleves" VALUES ('43','2','3','2026-2027','6','2026-06-05','matin','present',NULL,NULL,NULL,NULL,NULL,'entree');
INSERT INTO "presence_eleves" VALUES ('44','2','3','2026-2027','6','2026-06-05','apres_midi','absent',NULL,NULL,NULL,NULL,NULL,NULL);
INSERT INTO "presence_eleves" VALUES ('45','3','3','2026-2027','6','2026-06-05','matin','present',NULL,NULL,NULL,NULL,NULL,'entree');
INSERT INTO "presence_eleves" VALUES ('46','3','3','2026-2027','6','2026-06-05','apres_midi','retard',NULL,NULL,NULL,NULL,NULL,'entree');
INSERT INTO "presence_eleves" VALUES ('47','4','3','2026-2027','6','2026-06-05','matin','absent',NULL,NULL,NULL,NULL,NULL,NULL);
INSERT INTO "presence_eleves" VALUES ('48','4','3','2026-2027','6','2026-06-05','apres_midi','present',NULL,NULL,NULL,NULL,NULL,'entree');
INSERT INTO "presence_eleves" VALUES ('49','1','3','2026-2027','6','2026-06-08','matin','present',NULL,NULL,NULL,NULL,NULL,'entree');
INSERT INTO "presence_eleves" VALUES ('50','1','3','2026-2027','6','2026-06-08','apres_midi','absent',NULL,NULL,NULL,NULL,NULL,NULL);
INSERT INTO "presence_eleves" VALUES ('51','2','3','2026-2027','6','2026-06-08','matin','retard',NULL,NULL,NULL,NULL,NULL,'entree');
INSERT INTO "presence_eleves" VALUES ('52','2','3','2026-2027','6','2026-06-08','apres_midi','absent',NULL,NULL,NULL,NULL,NULL,NULL);
INSERT INTO "presence_eleves" VALUES ('53','3','3','2026-2027','6','2026-06-08','matin','present',NULL,NULL,NULL,NULL,NULL,'entree');
INSERT INTO "presence_eleves" VALUES ('54','3','3','2026-2027','6','2026-06-08','apres_midi','present',NULL,NULL,NULL,NULL,NULL,'entree');
INSERT INTO "presence_eleves" VALUES ('55','4','3','2026-2027','6','2026-06-08','matin','present',NULL,NULL,NULL,NULL,NULL,'entree');
INSERT INTO "presence_eleves" VALUES ('56','4','3','2026-2027','6','2026-06-08','apres_midi','present',NULL,NULL,NULL,NULL,NULL,'entree');
INSERT INTO "presence_eleves" VALUES ('57','1','3','2026-2027','6','2026-06-09','matin','absent',NULL,NULL,NULL,NULL,NULL,NULL);
INSERT INTO "presence_eleves" VALUES ('58','1','3','2026-2027','6','2026-06-09','apres_midi','retard',NULL,NULL,NULL,NULL,NULL,'entree');
INSERT INTO "presence_eleves" VALUES ('59','2','3','2026-2027','6','2026-06-09','matin','retard',NULL,NULL,NULL,NULL,NULL,'entree');
INSERT INTO "presence_eleves" VALUES ('60','2','3','2026-2027','6','2026-06-09','apres_midi','present',NULL,NULL,NULL,NULL,NULL,'entree');
INSERT INTO "presence_eleves" VALUES ('61','3','3','2026-2027','6','2026-06-09','matin','retard',NULL,NULL,NULL,NULL,NULL,'entree');
INSERT INTO "presence_eleves" VALUES ('62','3','3','2026-2027','6','2026-06-09','apres_midi','present',NULL,NULL,NULL,NULL,NULL,'entree');
INSERT INTO "presence_eleves" VALUES ('63','4','3','2026-2027','6','2026-06-09','matin','absent',NULL,NULL,NULL,NULL,NULL,NULL);
INSERT INTO "presence_eleves" VALUES ('64','4','3','2026-2027','6','2026-06-09','apres_midi','present',NULL,NULL,NULL,NULL,NULL,'entree');
INSERT INTO "presence_eleves" VALUES ('65','1','3','2026-2027','6','2026-06-10','matin','present',NULL,NULL,NULL,NULL,NULL,'entree');
INSERT INTO "presence_eleves" VALUES ('66','1','3','2026-2027','6','2026-06-10','apres_midi','present',NULL,NULL,NULL,NULL,NULL,'entree');
INSERT INTO "presence_eleves" VALUES ('67','2','3','2026-2027','6','2026-06-10','matin','present',NULL,NULL,NULL,NULL,NULL,'entree');
INSERT INTO "presence_eleves" VALUES ('68','2','3','2026-2027','6','2026-06-10','apres_midi','present',NULL,NULL,NULL,NULL,NULL,'entree');
INSERT INTO "presence_eleves" VALUES ('69','3','3','2026-2027','6','2026-06-10','matin','absent',NULL,NULL,NULL,NULL,NULL,NULL);
INSERT INTO "presence_eleves" VALUES ('70','3','3','2026-2027','6','2026-06-10','apres_midi','present',NULL,NULL,NULL,NULL,NULL,'entree');
INSERT INTO "presence_eleves" VALUES ('71','4','3','2026-2027','6','2026-06-10','matin','present',NULL,NULL,NULL,NULL,NULL,'entree');
INSERT INTO "presence_eleves" VALUES ('72','4','3','2026-2027','6','2026-06-10','apres_midi','present',NULL,NULL,NULL,NULL,NULL,'entree');
INSERT INTO "presence_eleves" VALUES ('73','1','3','2026-2027','6','2026-06-11','matin','retard',NULL,NULL,NULL,NULL,NULL,'entree');
INSERT INTO "presence_eleves" VALUES ('74','1','3','2026-2027','6','2026-06-11','apres_midi','retard',NULL,NULL,NULL,NULL,NULL,'entree');
INSERT INTO "presence_eleves" VALUES ('75','2','3','2026-2027','6','2026-06-11','matin','present',NULL,NULL,NULL,NULL,NULL,'entree');
INSERT INTO "presence_eleves" VALUES ('76','2','3','2026-2027','6','2026-06-11','apres_midi','absent',NULL,NULL,NULL,NULL,NULL,NULL);
INSERT INTO "presence_eleves" VALUES ('77','3','3','2026-2027','6','2026-06-11','matin','retard',NULL,NULL,NULL,NULL,NULL,'entree');
INSERT INTO "presence_eleves" VALUES ('78','3','3','2026-2027','6','2026-06-11','apres_midi','absent',NULL,NULL,NULL,NULL,NULL,NULL);
INSERT INTO "presence_eleves" VALUES ('79','4','3','2026-2027','6','2026-06-11','matin','retard',NULL,NULL,NULL,NULL,NULL,'entree');
INSERT INTO "presence_eleves" VALUES ('80','4','3','2026-2027','6','2026-06-11','apres_midi','present',NULL,NULL,NULL,NULL,NULL,'entree');
INSERT INTO "presence_eleves" VALUES ('81','1','3','2026-2027','6','2026-06-12','matin','present',NULL,NULL,NULL,NULL,NULL,'entree');
INSERT INTO "presence_eleves" VALUES ('82','1','3','2026-2027','6','2026-06-12','apres_midi','absent',NULL,NULL,NULL,NULL,NULL,NULL);
INSERT INTO "presence_eleves" VALUES ('83','2','3','2026-2027','6','2026-06-12','matin','retard',NULL,NULL,NULL,NULL,NULL,'entree');
INSERT INTO "presence_eleves" VALUES ('84','2','3','2026-2027','6','2026-06-12','apres_midi','present',NULL,NULL,NULL,NULL,NULL,'entree');
INSERT INTO "presence_eleves" VALUES ('85','3','3','2026-2027','6','2026-06-12','matin','absent',NULL,NULL,NULL,NULL,NULL,NULL);
INSERT INTO "presence_eleves" VALUES ('86','3','3','2026-2027','6','2026-06-12','apres_midi','absent',NULL,NULL,NULL,NULL,NULL,NULL);
INSERT INTO "presence_eleves" VALUES ('87','4','3','2026-2027','6','2026-06-12','matin','absent',NULL,NULL,NULL,NULL,NULL,NULL);
INSERT INTO "presence_eleves" VALUES ('88','4','3','2026-2027','6','2026-06-12','apres_midi','present',NULL,NULL,NULL,NULL,NULL,'entree');
INSERT INTO "presence_eleves" VALUES ('89','1','3','2026-2027','6','2026-06-15','matin','present',NULL,NULL,NULL,NULL,NULL,'entree');
INSERT INTO "presence_eleves" VALUES ('90','1','3','2026-2027','6','2026-06-15','apres_midi','present',NULL,NULL,NULL,NULL,NULL,'entree');
INSERT INTO "presence_eleves" VALUES ('91','2','3','2026-2027','6','2026-06-15','matin','retard',NULL,NULL,NULL,NULL,NULL,'entree');
INSERT INTO "presence_eleves" VALUES ('92','2','3','2026-2027','6','2026-06-15','apres_midi','present',NULL,NULL,NULL,NULL,NULL,'entree');
INSERT INTO "presence_eleves" VALUES ('93','3','3','2026-2027','6','2026-06-15','matin','present',NULL,NULL,NULL,NULL,NULL,'entree');
INSERT INTO "presence_eleves" VALUES ('94','3','3','2026-2027','6','2026-06-15','apres_midi','absent',NULL,NULL,NULL,NULL,NULL,NULL);
INSERT INTO "presence_eleves" VALUES ('95','4','3','2026-2027','6','2026-06-15','matin','present',NULL,NULL,NULL,NULL,NULL,'entree');
INSERT INTO "presence_eleves" VALUES ('96','4','3','2026-2027','6','2026-06-15','apres_midi','present',NULL,NULL,NULL,NULL,NULL,'entree');
INSERT INTO "presence_eleves" VALUES ('97','1','3','2026-2027','6','2026-06-16','matin','present',NULL,NULL,NULL,NULL,NULL,'entree');
INSERT INTO "presence_eleves" VALUES ('98','1','3','2026-2027','6','2026-06-16','apres_midi','present',NULL,NULL,NULL,NULL,NULL,'entree');
INSERT INTO "presence_eleves" VALUES ('99','2','3','2026-2027','6','2026-06-16','matin','absent',NULL,NULL,NULL,NULL,NULL,NULL);
INSERT INTO "presence_eleves" VALUES ('100','2','3','2026-2027','6','2026-06-16','apres_midi','present',NULL,NULL,NULL,NULL,NULL,'entree');
INSERT INTO "presence_eleves" VALUES ('101','3','3','2026-2027','6','2026-06-16','matin','present',NULL,NULL,NULL,NULL,NULL,'entree');
INSERT INTO "presence_eleves" VALUES ('102','3','3','2026-2027','6','2026-06-16','apres_midi','present',NULL,NULL,NULL,NULL,NULL,'entree');
INSERT INTO "presence_eleves" VALUES ('103','4','3','2026-2027','6','2026-06-16','matin','present',NULL,NULL,NULL,NULL,NULL,'entree');
INSERT INTO "presence_eleves" VALUES ('104','4','3','2026-2027','6','2026-06-16','apres_midi','present',NULL,NULL,NULL,NULL,NULL,'entree');
INSERT INTO "presence_eleves" VALUES ('105','1','3','2026-2027','6','2026-06-17','matin','absent',NULL,NULL,NULL,NULL,NULL,NULL);
INSERT INTO "presence_eleves" VALUES ('106','1','3','2026-2027','6','2026-06-17','apres_midi','present',NULL,NULL,NULL,NULL,NULL,'entree');
INSERT INTO "presence_eleves" VALUES ('107','2','3','2026-2027','6','2026-06-17','matin','retard',NULL,NULL,NULL,NULL,NULL,'entree');
INSERT INTO "presence_eleves" VALUES ('108','2','3','2026-2027','6','2026-06-17','apres_midi','absent',NULL,NULL,NULL,NULL,NULL,NULL);
INSERT INTO "presence_eleves" VALUES ('109','3','3','2026-2027','6','2026-06-17','matin','retard',NULL,NULL,NULL,NULL,NULL,'entree');
INSERT INTO "presence_eleves" VALUES ('110','3','3','2026-2027','6','2026-06-17','apres_midi','present',NULL,NULL,NULL,NULL,NULL,'entree');
INSERT INTO "presence_eleves" VALUES ('111','4','3','2026-2027','6','2026-06-17','matin','absent',NULL,NULL,NULL,NULL,NULL,NULL);
INSERT INTO "presence_eleves" VALUES ('112','4','3','2026-2027','6','2026-06-17','apres_midi','retard',NULL,NULL,NULL,NULL,NULL,'entree');
INSERT INTO "presence_eleves" VALUES ('113','1','3','2026-2027','6','2026-06-18','matin','present',NULL,NULL,NULL,NULL,NULL,'entree');
INSERT INTO "presence_eleves" VALUES ('114','1','3','2026-2027','6','2026-06-18','apres_midi','present',NULL,NULL,NULL,NULL,NULL,'entree');
INSERT INTO "presence_eleves" VALUES ('115','2','3','2026-2027','6','2026-06-18','matin','present',NULL,NULL,NULL,NULL,NULL,'entree');
INSERT INTO "presence_eleves" VALUES ('116','2','3','2026-2027','6','2026-06-18','apres_midi','present',NULL,NULL,NULL,NULL,NULL,'entree');
INSERT INTO "presence_eleves" VALUES ('117','3','3','2026-2027','6','2026-06-18','matin','absent',NULL,NULL,NULL,NULL,NULL,NULL);
INSERT INTO "presence_eleves" VALUES ('118','3','3','2026-2027','6','2026-06-18','apres_midi','absent',NULL,NULL,NULL,NULL,NULL,NULL);
INSERT INTO "presence_eleves" VALUES ('119','4','3','2026-2027','6','2026-06-18','matin','absent',NULL,NULL,NULL,NULL,NULL,NULL);
INSERT INTO "presence_eleves" VALUES ('120','4','3','2026-2027','6','2026-06-18','apres_midi','absent',NULL,NULL,NULL,NULL,NULL,NULL);
INSERT INTO "presence_eleves" VALUES ('121','1','3','2026-2027','6','2026-06-19','matin','retard',NULL,NULL,NULL,NULL,NULL,'entree');
INSERT INTO "presence_eleves" VALUES ('122','1','3','2026-2027','6','2026-06-19','apres_midi','present',NULL,NULL,NULL,NULL,NULL,'entree');
INSERT INTO "presence_eleves" VALUES ('123','2','3','2026-2027','6','2026-06-19','matin','present',NULL,NULL,NULL,NULL,NULL,'entree');
INSERT INTO "presence_eleves" VALUES ('124','2','3','2026-2027','6','2026-06-19','apres_midi','present',NULL,NULL,NULL,NULL,NULL,'entree');
INSERT INTO "presence_eleves" VALUES ('125','3','3','2026-2027','6','2026-06-19','matin','present',NULL,NULL,NULL,NULL,NULL,'entree');
INSERT INTO "presence_eleves" VALUES ('126','3','3','2026-2027','6','2026-06-19','apres_midi','present',NULL,NULL,NULL,NULL,NULL,'entree');
INSERT INTO "presence_eleves" VALUES ('127','4','3','2026-2027','6','2026-06-19','matin','present',NULL,NULL,NULL,NULL,NULL,'entree');
INSERT INTO "presence_eleves" VALUES ('128','4','3','2026-2027','6','2026-06-19','apres_midi','present',NULL,NULL,NULL,NULL,NULL,'entree');
INSERT INTO "presence_eleves" VALUES ('129','1','3','2026-2027','6','2026-06-22','matin','retard',NULL,NULL,NULL,NULL,NULL,'entree');
INSERT INTO "presence_eleves" VALUES ('130','1','3','2026-2027','6','2026-06-22','apres_midi','absent',NULL,NULL,NULL,NULL,NULL,NULL);
INSERT INTO "presence_eleves" VALUES ('131','2','3','2026-2027','6','2026-06-22','matin','absent',NULL,NULL,NULL,NULL,NULL,NULL);
INSERT INTO "presence_eleves" VALUES ('132','2','3','2026-2027','6','2026-06-22','apres_midi','present',NULL,NULL,NULL,NULL,NULL,'entree');
INSERT INTO "presence_eleves" VALUES ('133','3','3','2026-2027','6','2026-06-22','matin','present',NULL,NULL,NULL,NULL,NULL,'entree');
INSERT INTO "presence_eleves" VALUES ('134','3','3','2026-2027','6','2026-06-22','apres_midi','present',NULL,NULL,NULL,NULL,NULL,'entree');
INSERT INTO "presence_eleves" VALUES ('135','4','3','2026-2027','6','2026-06-22','matin','present',NULL,NULL,NULL,NULL,NULL,'entree');
INSERT INTO "presence_eleves" VALUES ('136','4','3','2026-2027','6','2026-06-22','apres_midi','present',NULL,NULL,NULL,NULL,NULL,'entree');
INSERT INTO "presence_eleves" VALUES ('137','1','3','2026-2027','6','2026-06-23','matin','present',NULL,NULL,NULL,NULL,NULL,'entree');
INSERT INTO "presence_eleves" VALUES ('138','1','3','2026-2027','6','2026-06-23','apres_midi','present',NULL,NULL,NULL,NULL,NULL,'entree');
INSERT INTO "presence_eleves" VALUES ('139','2','3','2026-2027','6','2026-06-23','matin','present',NULL,NULL,NULL,NULL,NULL,'entree');
INSERT INTO "presence_eleves" VALUES ('140','2','3','2026-2027','6','2026-06-23','apres_midi','present',NULL,NULL,NULL,NULL,NULL,'entree');
INSERT INTO "presence_eleves" VALUES ('141','3','3','2026-2027','6','2026-06-23','matin','present',NULL,NULL,NULL,NULL,NULL,'entree');
INSERT INTO "presence_eleves" VALUES ('142','3','3','2026-2027','6','2026-06-23','apres_midi','present',NULL,NULL,NULL,NULL,NULL,'entree');
INSERT INTO "presence_eleves" VALUES ('143','4','3','2026-2027','6','2026-06-23','matin','absent',NULL,NULL,NULL,NULL,NULL,NULL);
INSERT INTO "presence_eleves" VALUES ('144','4','3','2026-2027','6','2026-06-23','apres_midi','retard',NULL,NULL,NULL,NULL,NULL,'entree');
INSERT INTO "presence_eleves" VALUES ('145','1','3','2026-2027','6','2026-06-24','matin','retard',NULL,NULL,NULL,NULL,NULL,'entree');
INSERT INTO "presence_eleves" VALUES ('146','1','3','2026-2027','6','2026-06-24','apres_midi','present',NULL,NULL,NULL,NULL,NULL,'entree');
INSERT INTO "presence_eleves" VALUES ('147','2','3','2026-2027','6','2026-06-24','matin','present',NULL,NULL,NULL,NULL,NULL,'entree');
INSERT INTO "presence_eleves" VALUES ('148','2','3','2026-2027','6','2026-06-24','apres_midi','present',NULL,NULL,NULL,NULL,NULL,'entree');
INSERT INTO "presence_eleves" VALUES ('149','3','3','2026-2027','6','2026-06-24','matin','present',NULL,NULL,NULL,NULL,NULL,'entree');
INSERT INTO "presence_eleves" VALUES ('150','3','3','2026-2027','6','2026-06-24','apres_midi','present',NULL,NULL,NULL,NULL,NULL,'entree');
INSERT INTO "presence_eleves" VALUES ('151','4','3','2026-2027','6','2026-06-24','matin','absent',NULL,NULL,NULL,NULL,NULL,NULL);
INSERT INTO "presence_eleves" VALUES ('152','4','3','2026-2027','6','2026-06-24','apres_midi','present',NULL,NULL,NULL,NULL,NULL,'entree');
INSERT INTO "presence_eleves" VALUES ('153','1','3','2026-2027','6','2026-06-25','matin','present',NULL,NULL,NULL,NULL,NULL,'entree');
INSERT INTO "presence_eleves" VALUES ('154','1','3','2026-2027','6','2026-06-25','apres_midi','retard',NULL,NULL,NULL,NULL,NULL,'entree');
INSERT INTO "presence_eleves" VALUES ('155','2','3','2026-2027','6','2026-06-25','matin','absent',NULL,NULL,NULL,NULL,NULL,NULL);
INSERT INTO "presence_eleves" VALUES ('156','2','3','2026-2027','6','2026-06-25','apres_midi','present',NULL,NULL,NULL,NULL,NULL,'entree');
INSERT INTO "presence_eleves" VALUES ('157','3','3','2026-2027','6','2026-06-25','matin','absent',NULL,NULL,NULL,NULL,NULL,NULL);
INSERT INTO "presence_eleves" VALUES ('158','3','3','2026-2027','6','2026-06-25','apres_midi','absent',NULL,NULL,NULL,NULL,NULL,NULL);
INSERT INTO "presence_eleves" VALUES ('159','4','3','2026-2027','6','2026-06-25','matin','present',NULL,NULL,NULL,NULL,NULL,'entree');
INSERT INTO "presence_eleves" VALUES ('160','4','3','2026-2027','6','2026-06-25','apres_midi','present',NULL,NULL,NULL,NULL,NULL,'entree');
INSERT INTO "presence_eleves" VALUES ('161','1','3','2026-2027','6','2026-06-26','matin','absent',NULL,NULL,NULL,NULL,NULL,NULL);
INSERT INTO "presence_eleves" VALUES ('162','1','3','2026-2027','6','2026-06-26','apres_midi','absent',NULL,NULL,NULL,NULL,NULL,NULL);
INSERT INTO "presence_eleves" VALUES ('163','2','3','2026-2027','6','2026-06-26','matin','absent',NULL,NULL,NULL,NULL,NULL,NULL);
INSERT INTO "presence_eleves" VALUES ('164','2','3','2026-2027','6','2026-06-26','apres_midi','absent',NULL,NULL,NULL,NULL,NULL,NULL);
INSERT INTO "presence_eleves" VALUES ('165','3','3','2026-2027','6','2026-06-26','matin','present',NULL,NULL,NULL,NULL,NULL,'entree');
INSERT INTO "presence_eleves" VALUES ('166','3','3','2026-2027','6','2026-06-26','apres_midi','present',NULL,NULL,NULL,NULL,NULL,'entree');
INSERT INTO "presence_eleves" VALUES ('167','4','3','2026-2027','6','2026-06-26','matin','retard',NULL,NULL,NULL,NULL,NULL,'entree');
INSERT INTO "presence_eleves" VALUES ('168','4','3','2026-2027','6','2026-06-26','apres_midi','present',NULL,NULL,NULL,NULL,NULL,'entree');
INSERT INTO "presence_eleves" VALUES ('169','1','3','2026-2027','6','2026-06-29','matin','present',NULL,NULL,NULL,NULL,NULL,'entree');
INSERT INTO "presence_eleves" VALUES ('170','1','3','2026-2027','6','2026-06-29','apres_midi','retard',NULL,NULL,NULL,NULL,NULL,'entree');
INSERT INTO "presence_eleves" VALUES ('171','2','3','2026-2027','6','2026-06-29','matin','present',NULL,NULL,NULL,NULL,NULL,'entree');
INSERT INTO "presence_eleves" VALUES ('172','2','3','2026-2027','6','2026-06-29','apres_midi','present',NULL,NULL,NULL,NULL,NULL,'entree');
INSERT INTO "presence_eleves" VALUES ('173','3','3','2026-2027','6','2026-06-29','matin','present',NULL,NULL,NULL,NULL,NULL,'entree');
INSERT INTO "presence_eleves" VALUES ('174','3','3','2026-2027','6','2026-06-29','apres_midi','retard',NULL,NULL,NULL,NULL,NULL,'entree');
INSERT INTO "presence_eleves" VALUES ('175','4','3','2026-2027','6','2026-06-29','matin','absent',NULL,NULL,NULL,NULL,NULL,NULL);
INSERT INTO "presence_eleves" VALUES ('176','4','3','2026-2027','6','2026-06-29','apres_midi','absent',NULL,NULL,NULL,NULL,NULL,NULL);
INSERT INTO "presence_eleves" VALUES ('177','1','3','2026-2027','6','2026-06-30','matin','present',NULL,NULL,NULL,NULL,NULL,'entree');
INSERT INTO "presence_eleves" VALUES ('178','1','3','2026-2027','6','2026-06-30','apres_midi','absent',NULL,NULL,NULL,NULL,NULL,NULL);
INSERT INTO "presence_eleves" VALUES ('179','2','3','2026-2027','6','2026-06-30','matin','present',NULL,NULL,NULL,NULL,NULL,'entree');
INSERT INTO "presence_eleves" VALUES ('180','2','3','2026-2027','6','2026-06-30','apres_midi','present',NULL,NULL,NULL,NULL,NULL,'entree');
INSERT INTO "presence_eleves" VALUES ('181','3','3','2026-2027','6','2026-06-30','matin','absent',NULL,NULL,NULL,NULL,NULL,NULL);
INSERT INTO "presence_eleves" VALUES ('182','3','3','2026-2027','6','2026-06-30','apres_midi','present',NULL,NULL,NULL,NULL,NULL,'entree');
INSERT INTO "presence_eleves" VALUES ('183','4','3','2026-2027','6','2026-06-30','matin','absent',NULL,NULL,NULL,NULL,NULL,NULL);
INSERT INTO "presence_eleves" VALUES ('184','4','3','2026-2027','6','2026-06-30','apres_midi','present',NULL,NULL,NULL,NULL,NULL,'entree');
INSERT INTO "presence_eleves" VALUES ('185','1','3','2026-2027','7','2026-07-01','matin','present',NULL,NULL,NULL,NULL,NULL,'entree');
INSERT INTO "presence_eleves" VALUES ('186','1','3','2026-2027','7','2026-07-01','apres_midi','absent',NULL,NULL,NULL,NULL,NULL,NULL);
INSERT INTO "presence_eleves" VALUES ('187','2','3','2026-2027','7','2026-07-01','matin','absent',NULL,NULL,NULL,NULL,NULL,NULL);
INSERT INTO "presence_eleves" VALUES ('188','2','3','2026-2027','7','2026-07-01','apres_midi','present',NULL,NULL,NULL,NULL,NULL,'entree');
INSERT INTO "presence_eleves" VALUES ('189','3','3','2026-2027','7','2026-07-01','matin','present',NULL,NULL,NULL,NULL,NULL,'entree');
INSERT INTO "presence_eleves" VALUES ('190','3','3','2026-2027','7','2026-07-01','apres_midi','present',NULL,NULL,NULL,NULL,NULL,'entree');
INSERT INTO "presence_eleves" VALUES ('191','4','3','2026-2027','7','2026-07-01','matin','present',NULL,NULL,NULL,NULL,NULL,'entree');
INSERT INTO "presence_eleves" VALUES ('192','4','3','2026-2027','7','2026-07-01','apres_midi','present',NULL,NULL,NULL,NULL,NULL,'entree');
INSERT INTO "presence_eleves" VALUES ('193','1','3','2026-2027','7','2026-07-02','matin','present',NULL,NULL,NULL,NULL,NULL,'entree');
INSERT INTO "presence_eleves" VALUES ('194','1','3','2026-2027','7','2026-07-02','apres_midi','retard',NULL,NULL,NULL,NULL,NULL,'entree');
INSERT INTO "presence_eleves" VALUES ('195','2','3','2026-2027','7','2026-07-02','matin','present',NULL,NULL,NULL,NULL,NULL,'entree');
INSERT INTO "presence_eleves" VALUES ('196','2','3','2026-2027','7','2026-07-02','apres_midi','present',NULL,NULL,NULL,NULL,NULL,'entree');
INSERT INTO "presence_eleves" VALUES ('197','3','3','2026-2027','7','2026-07-02','matin','retard',NULL,NULL,NULL,NULL,NULL,'entree');
INSERT INTO "presence_eleves" VALUES ('198','3','3','2026-2027','7','2026-07-02','apres_midi','retard',NULL,NULL,NULL,NULL,NULL,'entree');
INSERT INTO "presence_eleves" VALUES ('199','4','3','2026-2027','7','2026-07-02','matin','absent',NULL,NULL,NULL,NULL,NULL,NULL);
INSERT INTO "presence_eleves" VALUES ('200','4','3','2026-2027','7','2026-07-02','apres_midi','present',NULL,NULL,NULL,NULL,NULL,'entree');


DROP TABLE IF EXISTS "presence_personnels";
CREATE TABLE "presence_personnels" ("id" integer primary key autoincrement not null, "staff_id" integer not null, "date_jour" date not null, "statut" varchar check ("statut" in ('present', 'absent', 'retard')) not null default 'present', "commentaire" varchar, "created_at" datetime, "updated_at" datetime, "scan_mode" varchar, "scanned_by" integer, "personne_id" integer, "annee_scolaire" varchar, "mois" varchar, "date_enregistrement" datetime, "horaire" numeric, "presence" tinyint(1), "retard" tinyint(1) not null default '0', "session_jour" varchar, "type_scan" varchar, "heure_entree" varchar, "heure_sortie" varchar, foreign key("staff_id") references "staff"("id") on delete cascade);

INSERT INTO "presence_personnels" VALUES ('3','1','2026-06-02','retard',NULL,NULL,NULL,NULL,NULL,'1','2026-2027','06',NULL,'4','1','1','matin','entree','07:45:00','12:00:00');
INSERT INTO "presence_personnels" VALUES ('4','1','2026-06-02','present',NULL,NULL,NULL,NULL,NULL,'1','2026-2027','06',NULL,'4','1','0','apres_midi','entree','07:45:00','12:00:00');
INSERT INTO "presence_personnels" VALUES ('5','1','2026-06-03','retard',NULL,NULL,NULL,NULL,NULL,'1','2026-2027','06',NULL,'4','1','1','matin','entree','07:45:00','12:00:00');
INSERT INTO "presence_personnels" VALUES ('6','1','2026-06-03','retard',NULL,NULL,NULL,NULL,NULL,'1','2026-2027','06',NULL,'4','1','1','apres_midi','entree','07:45:00','12:00:00');
INSERT INTO "presence_personnels" VALUES ('7','1','2026-06-04','absent',NULL,NULL,NULL,NULL,NULL,'1','2026-2027','06',NULL,'4','0','0','matin',NULL,'07:45:00',NULL);
INSERT INTO "presence_personnels" VALUES ('8','1','2026-06-04','retard',NULL,NULL,NULL,NULL,NULL,'1','2026-2027','06',NULL,'4','1','1','apres_midi','entree','07:45:00','12:00:00');
INSERT INTO "presence_personnels" VALUES ('9','1','2026-06-05','present',NULL,NULL,NULL,NULL,NULL,'1','2026-2027','06',NULL,'4','1','0','matin','entree','07:45:00','12:00:00');
INSERT INTO "presence_personnels" VALUES ('10','1','2026-06-05','present',NULL,NULL,NULL,NULL,NULL,'1','2026-2027','06',NULL,'4','1','0','apres_midi','entree','07:45:00','12:00:00');
INSERT INTO "presence_personnels" VALUES ('11','1','2026-06-08','retard',NULL,NULL,NULL,NULL,NULL,'1','2026-2027','06',NULL,'4','1','1','matin','entree','07:45:00','12:00:00');
INSERT INTO "presence_personnels" VALUES ('12','1','2026-06-08','retard',NULL,NULL,NULL,NULL,NULL,'1','2026-2027','06',NULL,'4','1','1','apres_midi','entree','07:45:00','12:00:00');
INSERT INTO "presence_personnels" VALUES ('13','1','2026-06-09','present',NULL,NULL,NULL,NULL,NULL,'1','2026-2027','06',NULL,'4','1','0','matin','entree','07:45:00','12:00:00');
INSERT INTO "presence_personnels" VALUES ('14','1','2026-06-09','present',NULL,NULL,NULL,NULL,NULL,'1','2026-2027','06',NULL,'4','1','0','apres_midi','entree','07:45:00','12:00:00');
INSERT INTO "presence_personnels" VALUES ('15','1','2026-06-10','absent',NULL,NULL,NULL,NULL,NULL,'1','2026-2027','06',NULL,'4','0','0','matin',NULL,'07:45:00',NULL);
INSERT INTO "presence_personnels" VALUES ('16','1','2026-06-10','present',NULL,NULL,NULL,NULL,NULL,'1','2026-2027','06',NULL,'4','1','0','apres_midi','entree','07:45:00','12:00:00');
INSERT INTO "presence_personnels" VALUES ('17','1','2026-06-11','present',NULL,NULL,NULL,NULL,NULL,'1','2026-2027','06',NULL,'4','1','0','matin','entree','07:45:00','12:00:00');
INSERT INTO "presence_personnels" VALUES ('18','1','2026-06-11','absent',NULL,NULL,NULL,NULL,NULL,'1','2026-2027','06',NULL,'4','0','0','apres_midi',NULL,'07:45:00',NULL);
INSERT INTO "presence_personnels" VALUES ('19','1','2026-06-12','present',NULL,NULL,NULL,NULL,NULL,'1','2026-2027','06',NULL,'4','1','0','matin','entree','07:45:00','12:00:00');
INSERT INTO "presence_personnels" VALUES ('20','1','2026-06-12','present',NULL,NULL,NULL,NULL,NULL,'1','2026-2027','06',NULL,'4','1','0','apres_midi','entree','07:45:00','12:00:00');
INSERT INTO "presence_personnels" VALUES ('21','1','2026-06-15','absent',NULL,NULL,NULL,NULL,NULL,'1','2026-2027','06',NULL,'4','0','0','matin',NULL,'07:45:00',NULL);
INSERT INTO "presence_personnels" VALUES ('22','1','2026-06-15','retard',NULL,NULL,NULL,NULL,NULL,'1','2026-2027','06',NULL,'4','1','1','apres_midi','entree','07:45:00','12:00:00');
INSERT INTO "presence_personnels" VALUES ('23','1','2026-06-16','retard',NULL,NULL,NULL,NULL,NULL,'1','2026-2027','06',NULL,'4','1','1','matin','entree','07:45:00','12:00:00');
INSERT INTO "presence_personnels" VALUES ('24','1','2026-06-16','present',NULL,NULL,NULL,NULL,NULL,'1','2026-2027','06',NULL,'4','1','0','apres_midi','entree','07:45:00','12:00:00');
INSERT INTO "presence_personnels" VALUES ('25','1','2026-06-17','absent',NULL,NULL,NULL,NULL,NULL,'1','2026-2027','06',NULL,'4','0','0','matin',NULL,'07:45:00',NULL);
INSERT INTO "presence_personnels" VALUES ('26','1','2026-06-17','absent',NULL,NULL,NULL,NULL,NULL,'1','2026-2027','06',NULL,'4','0','0','apres_midi',NULL,'07:45:00',NULL);
INSERT INTO "presence_personnels" VALUES ('27','1','2026-06-18','present',NULL,NULL,NULL,NULL,NULL,'1','2026-2027','06',NULL,'4','1','0','matin','entree','07:45:00','12:00:00');
INSERT INTO "presence_personnels" VALUES ('28','1','2026-06-18','present',NULL,NULL,NULL,NULL,NULL,'1','2026-2027','06',NULL,'4','1','0','apres_midi','entree','07:45:00','12:00:00');
INSERT INTO "presence_personnels" VALUES ('29','1','2026-06-19','present',NULL,NULL,NULL,NULL,NULL,'1','2026-2027','06',NULL,'4','1','0','matin','entree','07:45:00','12:00:00');
INSERT INTO "presence_personnels" VALUES ('30','1','2026-06-19','present',NULL,NULL,NULL,NULL,NULL,'1','2026-2027','06',NULL,'4','1','0','apres_midi','entree','07:45:00','12:00:00');
INSERT INTO "presence_personnels" VALUES ('31','1','2026-06-22','present',NULL,NULL,NULL,NULL,NULL,'1','2026-2027','06',NULL,'4','1','0','matin','entree','07:45:00','12:00:00');
INSERT INTO "presence_personnels" VALUES ('32','1','2026-06-22','present',NULL,NULL,NULL,NULL,NULL,'1','2026-2027','06',NULL,'4','1','0','apres_midi','entree','07:45:00','12:00:00');
INSERT INTO "presence_personnels" VALUES ('33','1','2026-06-23','present',NULL,NULL,NULL,NULL,NULL,'1','2026-2027','06',NULL,'4','1','0','matin','entree','07:45:00','12:00:00');
INSERT INTO "presence_personnels" VALUES ('34','1','2026-06-23','absent',NULL,NULL,NULL,NULL,NULL,'1','2026-2027','06',NULL,'4','0','0','apres_midi',NULL,'07:45:00',NULL);
INSERT INTO "presence_personnels" VALUES ('35','1','2026-06-24','present',NULL,NULL,NULL,NULL,NULL,'1','2026-2027','06',NULL,'4','1','0','matin','entree','07:45:00','12:00:00');
INSERT INTO "presence_personnels" VALUES ('36','1','2026-06-24','present',NULL,NULL,NULL,NULL,NULL,'1','2026-2027','06',NULL,'4','1','0','apres_midi','entree','07:45:00','12:00:00');
INSERT INTO "presence_personnels" VALUES ('37','1','2026-06-25','present',NULL,NULL,NULL,NULL,NULL,'1','2026-2027','06',NULL,'4','1','0','matin','entree','07:45:00','12:00:00');
INSERT INTO "presence_personnels" VALUES ('38','1','2026-06-25','retard',NULL,NULL,NULL,NULL,NULL,'1','2026-2027','06',NULL,'4','1','1','apres_midi','entree','07:45:00','12:00:00');
INSERT INTO "presence_personnels" VALUES ('39','1','2026-06-26','present',NULL,NULL,NULL,NULL,NULL,'1','2026-2027','06',NULL,'4','1','0','matin','entree','07:45:00','12:00:00');
INSERT INTO "presence_personnels" VALUES ('40','1','2026-06-26','retard',NULL,NULL,NULL,NULL,NULL,'1','2026-2027','06',NULL,'4','1','1','apres_midi','entree','07:45:00','12:00:00');
INSERT INTO "presence_personnels" VALUES ('41','1','2026-06-29','present',NULL,NULL,NULL,NULL,NULL,'1','2026-2027','06',NULL,'4','1','0','matin','entree','07:45:00','12:00:00');
INSERT INTO "presence_personnels" VALUES ('42','1','2026-06-29','retard',NULL,NULL,NULL,NULL,NULL,'1','2026-2027','06',NULL,'4','1','1','apres_midi','entree','07:45:00','12:00:00');
INSERT INTO "presence_personnels" VALUES ('43','1','2026-06-30','absent',NULL,NULL,NULL,NULL,NULL,'1','2026-2027','06',NULL,'4','0','0','matin',NULL,'07:45:00',NULL);
INSERT INTO "presence_personnels" VALUES ('44','1','2026-06-30','retard',NULL,NULL,NULL,NULL,NULL,'1','2026-2027','06',NULL,'4','1','1','apres_midi','entree','07:45:00','12:00:00');
INSERT INTO "presence_personnels" VALUES ('45','1','2026-07-01','retard',NULL,NULL,NULL,NULL,NULL,'1','2026-2027','07',NULL,'4','1','1','matin','entree','07:45:00','12:00:00');
INSERT INTO "presence_personnels" VALUES ('46','1','2026-07-01','retard',NULL,NULL,NULL,NULL,NULL,'1','2026-2027','07',NULL,'4','1','1','apres_midi','entree','07:45:00','12:00:00');
INSERT INTO "presence_personnels" VALUES ('47','1','2026-07-02','retard',NULL,NULL,NULL,NULL,NULL,'1','2026-2027','07',NULL,'4','1','1','matin','entree','07:45:00','12:00:00');
INSERT INTO "presence_personnels" VALUES ('48','1','2026-07-02','absent',NULL,NULL,NULL,NULL,NULL,'1','2026-2027','07',NULL,'4','0','0','apres_midi',NULL,'07:45:00',NULL);


DROP TABLE IF EXISTS "presence_staff";
CREATE TABLE "presence_staff" ("id" integer primary key autoincrement not null, "staff_id" integer not null, "date_jour" date not null, "statut" varchar check ("statut" in ('present', 'absent', 'retard')) not null default 'present', "commentaire" varchar, "created_at" datetime, "updated_at" datetime, "scan_mode" varchar, "scanned_by" integer, "personne_id" integer, "annee_scolaire" varchar, "mois" varchar, "date_enregistrement" datetime, "presence" tinyint(1), "retard" tinyint(1) not null default '0', "jours" numeric, "session_jour" varchar, "type_scan" varchar, "heure_entree" varchar, "heure_sortie" varchar, foreign key("staff_id") references "staff"("id") on delete cascade);

INSERT INTO "presence_staff" VALUES ('3','1','2026-06-02','present',NULL,NULL,NULL,NULL,NULL,'1','2026-2027','06',NULL,'1','0','1','matin','entree','08:00:00','17:00:00');
INSERT INTO "presence_staff" VALUES ('4','1','2026-06-02','absent',NULL,NULL,NULL,NULL,NULL,'1','2026-2027','06',NULL,'0','0','1','apres_midi',NULL,'08:00:00',NULL);
INSERT INTO "presence_staff" VALUES ('5','1','2026-06-03','retard',NULL,NULL,NULL,NULL,NULL,'1','2026-2027','06',NULL,'1','1','1','matin','entree','08:00:00','17:00:00');
INSERT INTO "presence_staff" VALUES ('6','1','2026-06-03','present',NULL,NULL,NULL,NULL,NULL,'1','2026-2027','06',NULL,'1','0','1','apres_midi','entree','08:00:00','17:00:00');
INSERT INTO "presence_staff" VALUES ('7','1','2026-06-04','absent',NULL,NULL,NULL,NULL,NULL,'1','2026-2027','06',NULL,'0','0','1','matin',NULL,'08:00:00',NULL);
INSERT INTO "presence_staff" VALUES ('8','1','2026-06-04','absent',NULL,NULL,NULL,NULL,NULL,'1','2026-2027','06',NULL,'0','0','1','apres_midi',NULL,'08:00:00',NULL);
INSERT INTO "presence_staff" VALUES ('9','1','2026-06-05','present',NULL,NULL,NULL,NULL,NULL,'1','2026-2027','06',NULL,'1','0','1','matin','entree','08:00:00','17:00:00');
INSERT INTO "presence_staff" VALUES ('10','1','2026-06-05','present',NULL,NULL,NULL,NULL,NULL,'1','2026-2027','06',NULL,'1','0','1','apres_midi','entree','08:00:00','17:00:00');
INSERT INTO "presence_staff" VALUES ('11','1','2026-06-08','absent',NULL,NULL,NULL,NULL,NULL,'1','2026-2027','06',NULL,'0','0','1','matin',NULL,'08:00:00',NULL);
INSERT INTO "presence_staff" VALUES ('12','1','2026-06-08','present',NULL,NULL,NULL,NULL,NULL,'1','2026-2027','06',NULL,'1','0','1','apres_midi','entree','08:00:00','17:00:00');
INSERT INTO "presence_staff" VALUES ('13','1','2026-06-09','present',NULL,NULL,NULL,NULL,NULL,'1','2026-2027','06',NULL,'1','0','1','matin','entree','08:00:00','17:00:00');
INSERT INTO "presence_staff" VALUES ('14','1','2026-06-09','present',NULL,NULL,NULL,NULL,NULL,'1','2026-2027','06',NULL,'1','0','1','apres_midi','entree','08:00:00','17:00:00');
INSERT INTO "presence_staff" VALUES ('15','1','2026-06-10','absent',NULL,NULL,NULL,NULL,NULL,'1','2026-2027','06',NULL,'0','0','1','matin',NULL,'08:00:00',NULL);
INSERT INTO "presence_staff" VALUES ('16','1','2026-06-10','absent',NULL,NULL,NULL,NULL,NULL,'1','2026-2027','06',NULL,'0','0','1','apres_midi',NULL,'08:00:00',NULL);
INSERT INTO "presence_staff" VALUES ('17','1','2026-06-11','present',NULL,NULL,NULL,NULL,NULL,'1','2026-2027','06',NULL,'1','0','1','matin','entree','08:00:00','17:00:00');
INSERT INTO "presence_staff" VALUES ('18','1','2026-06-11','absent',NULL,NULL,NULL,NULL,NULL,'1','2026-2027','06',NULL,'0','0','1','apres_midi',NULL,'08:00:00',NULL);
INSERT INTO "presence_staff" VALUES ('19','1','2026-06-12','absent',NULL,NULL,NULL,NULL,NULL,'1','2026-2027','06',NULL,'0','0','1','matin',NULL,'08:00:00',NULL);
INSERT INTO "presence_staff" VALUES ('20','1','2026-06-12','retard',NULL,NULL,NULL,NULL,NULL,'1','2026-2027','06',NULL,'1','1','1','apres_midi','entree','08:00:00','17:00:00');
INSERT INTO "presence_staff" VALUES ('21','1','2026-06-15','present',NULL,NULL,NULL,NULL,NULL,'1','2026-2027','06',NULL,'1','0','1','matin','entree','08:00:00','17:00:00');
INSERT INTO "presence_staff" VALUES ('22','1','2026-06-15','present',NULL,NULL,NULL,NULL,NULL,'1','2026-2027','06',NULL,'1','0','1','apres_midi','entree','08:00:00','17:00:00');
INSERT INTO "presence_staff" VALUES ('23','1','2026-06-16','absent',NULL,NULL,NULL,NULL,NULL,'1','2026-2027','06',NULL,'0','0','1','matin',NULL,'08:00:00',NULL);
INSERT INTO "presence_staff" VALUES ('24','1','2026-06-16','present',NULL,NULL,NULL,NULL,NULL,'1','2026-2027','06',NULL,'1','0','1','apres_midi','entree','08:00:00','17:00:00');
INSERT INTO "presence_staff" VALUES ('25','1','2026-06-17','retard',NULL,NULL,NULL,NULL,NULL,'1','2026-2027','06',NULL,'1','1','1','matin','entree','08:00:00','17:00:00');
INSERT INTO "presence_staff" VALUES ('26','1','2026-06-17','absent',NULL,NULL,NULL,NULL,NULL,'1','2026-2027','06',NULL,'0','0','1','apres_midi',NULL,'08:00:00',NULL);
INSERT INTO "presence_staff" VALUES ('27','1','2026-06-18','present',NULL,NULL,NULL,NULL,NULL,'1','2026-2027','06',NULL,'1','0','1','matin','entree','08:00:00','17:00:00');
INSERT INTO "presence_staff" VALUES ('28','1','2026-06-18','present',NULL,NULL,NULL,NULL,NULL,'1','2026-2027','06',NULL,'1','0','1','apres_midi','entree','08:00:00','17:00:00');
INSERT INTO "presence_staff" VALUES ('29','1','2026-06-19','present',NULL,NULL,NULL,NULL,NULL,'1','2026-2027','06',NULL,'1','0','1','matin','entree','08:00:00','17:00:00');
INSERT INTO "presence_staff" VALUES ('30','1','2026-06-19','absent',NULL,NULL,NULL,NULL,NULL,'1','2026-2027','06',NULL,'0','0','1','apres_midi',NULL,'08:00:00',NULL);
INSERT INTO "presence_staff" VALUES ('31','1','2026-06-22','retard',NULL,NULL,NULL,NULL,NULL,'1','2026-2027','06',NULL,'1','1','1','matin','entree','08:00:00','17:00:00');
INSERT INTO "presence_staff" VALUES ('32','1','2026-06-22','retard',NULL,NULL,NULL,NULL,NULL,'1','2026-2027','06',NULL,'1','1','1','apres_midi','entree','08:00:00','17:00:00');
INSERT INTO "presence_staff" VALUES ('33','1','2026-06-23','present',NULL,NULL,NULL,NULL,NULL,'1','2026-2027','06',NULL,'1','0','1','matin','entree','08:00:00','17:00:00');
INSERT INTO "presence_staff" VALUES ('34','1','2026-06-23','retard',NULL,NULL,NULL,NULL,NULL,'1','2026-2027','06',NULL,'1','1','1','apres_midi','entree','08:00:00','17:00:00');
INSERT INTO "presence_staff" VALUES ('35','1','2026-06-24','absent',NULL,NULL,NULL,NULL,NULL,'1','2026-2027','06',NULL,'0','0','1','matin',NULL,'08:00:00',NULL);
INSERT INTO "presence_staff" VALUES ('36','1','2026-06-24','present',NULL,NULL,NULL,NULL,NULL,'1','2026-2027','06',NULL,'1','0','1','apres_midi','entree','08:00:00','17:00:00');
INSERT INTO "presence_staff" VALUES ('37','1','2026-06-25','present',NULL,NULL,NULL,NULL,NULL,'1','2026-2027','06',NULL,'1','0','1','matin','entree','08:00:00','17:00:00');
INSERT INTO "presence_staff" VALUES ('38','1','2026-06-25','present',NULL,NULL,NULL,NULL,NULL,'1','2026-2027','06',NULL,'1','0','1','apres_midi','entree','08:00:00','17:00:00');
INSERT INTO "presence_staff" VALUES ('39','1','2026-06-26','present',NULL,NULL,NULL,NULL,NULL,'1','2026-2027','06',NULL,'1','0','1','matin','entree','08:00:00','17:00:00');
INSERT INTO "presence_staff" VALUES ('40','1','2026-06-26','retard',NULL,NULL,NULL,NULL,NULL,'1','2026-2027','06',NULL,'1','1','1','apres_midi','entree','08:00:00','17:00:00');
INSERT INTO "presence_staff" VALUES ('41','1','2026-06-29','present',NULL,NULL,NULL,NULL,NULL,'1','2026-2027','06',NULL,'1','0','1','matin','entree','08:00:00','17:00:00');
INSERT INTO "presence_staff" VALUES ('42','1','2026-06-29','present',NULL,NULL,NULL,NULL,NULL,'1','2026-2027','06',NULL,'1','0','1','apres_midi','entree','08:00:00','17:00:00');
INSERT INTO "presence_staff" VALUES ('43','1','2026-06-30','absent',NULL,NULL,NULL,NULL,NULL,'1','2026-2027','06',NULL,'0','0','1','matin',NULL,'08:00:00',NULL);
INSERT INTO "presence_staff" VALUES ('44','1','2026-06-30','present',NULL,NULL,NULL,NULL,NULL,'1','2026-2027','06',NULL,'1','0','1','apres_midi','entree','08:00:00','17:00:00');
INSERT INTO "presence_staff" VALUES ('45','1','2026-07-01','retard',NULL,NULL,NULL,NULL,NULL,'1','2026-2027','07',NULL,'1','1','1','matin','entree','08:00:00','17:00:00');
INSERT INTO "presence_staff" VALUES ('46','1','2026-07-01','absent',NULL,NULL,NULL,NULL,NULL,'1','2026-2027','07',NULL,'0','0','1','apres_midi',NULL,'08:00:00',NULL);
INSERT INTO "presence_staff" VALUES ('47','1','2026-07-02','absent',NULL,NULL,NULL,NULL,NULL,'1','2026-2027','07',NULL,'0','0','1','matin',NULL,'08:00:00',NULL);
INSERT INTO "presence_staff" VALUES ('48','1','2026-07-02','absent',NULL,NULL,NULL,NULL,NULL,'1','2026-2027','07',NULL,'0','0','1','apres_midi',NULL,'08:00:00',NULL);
INSERT INTO "presence_staff" VALUES ('49','1','2026-07-04','retard',NULL,'2026-07-04 11:57:39','2026-07-04 11:57:39','qr_code','5','1','2026-2027','07','2026-07-04 11:57:39','1','1','1','matin','entree','14:57',NULL);


DROP TABLE IF EXISTS "professeurs";
CREATE TABLE "professeurs" ("id" integer primary key autoincrement not null, "nom" varchar not null, "prenom" varchar not null, "email" varchar not null, "telephone" varchar, "specialisation" varchar, "biographie" text, "photo" varchar, "created_at" datetime, "updated_at" datetime, "qr_token" varchar, "annee_scolaire" varchar, "matiere_id" integer, "salaire_horaire" numeric, "diplome_pedagogique" varchar not null default ('Aucun'), "autorisation_enseigner" varchar not null default ('Non'), "annees_experience" integer not null default ('0'), "statut" varchar not null default ('actif'), "date_inscription" datetime, foreign key("matiere_id") references "matieres"("id") on delete set null);

INSERT INTO "professeurs" VALUES ('1','Rakoto','Demo','enseignant.demo@novaskol.local','0340000001',NULL,NULL,'images/prof_WT97rKFeSCtY6npx.png',NULL,NULL,'nvs_NSAcQewqHyzc6a48e348','2026-2027','1','25000','Aucun','Non','0','actif',NULL);


DROP TABLE IF EXISTS "professeurs_classes";
CREATE TABLE "professeurs_classes" ("id" integer primary key autoincrement not null, "professeur_id" integer not null, "classe_id" integer not null, "matiere_id" integer, "annee_scolaire" varchar not null, "affectation_type" varchar check ("affectation_type" in ('fixe', 'flexible')) not null default 'fixe', "commentaire" varchar, "created_at" datetime, "updated_at" datetime, foreign key("professeur_id") references "professeurs"("id") on delete cascade, foreign key("classe_id") references "classes"("id") on delete cascade, foreign key("matiere_id") references "matieres"("id") on delete set null);

INSERT INTO "professeurs_classes" VALUES ('4','1','2',NULL,'2026-2027','fixe',NULL,NULL,NULL);
INSERT INTO "professeurs_classes" VALUES ('5','1','1',NULL,'2026-2027','fixe',NULL,NULL,NULL);
INSERT INTO "professeurs_classes" VALUES ('6','1','3',NULL,'2026-2027','fixe',NULL,NULL,NULL);


DROP TABLE IF EXISTS "remarques";
CREATE TABLE "remarques" ("id" integer primary key autoincrement not null, "eleve_id" integer not null, "titre" varchar not null, "contenu" text not null, "trimestre" integer, "annee_scolaire" varchar not null, "created_at" datetime, "updated_at" datetime, "id_eleve" integer, "periode" varchar, "remarque" text, foreign key("eleve_id") references "eleves"("id") on delete cascade);



DROP TABLE IF EXISTS "remarques_examen_blanc";
CREATE TABLE "remarques_examen_blanc" ("id" integer primary key autoincrement not null, "eleve_id" integer not null, "examen_id" integer not null, "contenu" text not null, "created_at" datetime, "updated_at" datetime, "id_eleve" integer, "session" varchar, "remarque" text, "annee_scolaire" varchar, foreign key("eleve_id") references "eleves"("id") on delete cascade, foreign key("examen_id") references "examen_blanc"("id") on delete cascade);



DROP TABLE IF EXISTS "reservations";
CREATE TABLE "reservations" ("id" integer primary key autoincrement not null, "salle_id" integer not null, "type_reservation" varchar not null, "nom_responsable" varchar not null, "date_debut" date not null, "date_fin" date, "statut" varchar not null default 'confirmee', "created_at" datetime, "updated_at" datetime, foreign key("salle_id") references "salles"("id") on delete cascade);



DROP TABLE IF EXISTS "reservations_ressources";
CREATE TABLE "reservations_ressources" ("id" integer primary key autoincrement not null, "reservation_id" integer not null, "ressource_id" integer not null, "quantite" integer not null default '1', "created_at" datetime, "updated_at" datetime, "id_salle" integer, "date_reservation" datetime, "utilisateur" integer, "utilisateur_id" integer, "heure_debut" time, "heure_fin" time, "statut" varchar, "description" text, foreign key("reservation_id") references "reservations"("id") on delete cascade, foreign key("ressource_id") references "ressources"("id") on delete cascade);



DROP TABLE IF EXISTS "ressources";
CREATE TABLE "ressources" ("id" integer primary key autoincrement not null, "nom" varchar not null, "type" varchar not null, "description" text, "quantite" integer not null default '1', "created_at" datetime, "updated_at" datetime);



DROP TABLE IF EXISTS "revenus";
CREATE TABLE "revenus" ("id" integer primary key autoincrement not null, "source" varchar not null, "montant" numeric not null, "mois" varchar, "annee_scolaire" varchar not null, "description" text, "created_at" datetime, "updated_at" datetime, "type_id" integer, "personne_id" varchar, "type_personne" varchar, "classes" varchar, "mode_paiement" varchar, "statut" varchar, "categorie" varchar, "nom_personne" varchar, "date_enregistrement" datetime);

INSERT INTO "revenus" VALUES ('1','ecolage','150000','Juillet','2026-2027','Paiement ecolage',NULL,NULL,'1','2','eleve','6eme','Especes','complet','Ecolage','Ando Tiana','2026-07-04 12:00:35');


DROP TABLE IF EXISTS "roles";
CREATE TABLE "roles" ("id" integer primary key autoincrement not null, "nom" varchar not null, "description" text, "created_at" datetime, "updated_at" datetime);

INSERT INTO "roles" VALUES ('1','Secretaire',NULL,NULL,NULL);
INSERT INTO "roles" VALUES ('2','Comptable',NULL,NULL,NULL);
INSERT INTO "roles" VALUES ('3','RH',NULL,NULL,NULL);
INSERT INTO "roles" VALUES ('4','Assistant',NULL,NULL,NULL);
INSERT INTO "roles" VALUES ('5','Autre',NULL,NULL,NULL);


DROP TABLE IF EXISTS "salaires_assignes";
CREATE TABLE "salaires_assignes" ("id" integer primary key autoincrement not null, "staff_id" integer not null, "montant" numeric not null, "mois" varchar not null, "annee_scolaire" varchar not null, "statut" varchar not null default 'non_paye', "created_at" datetime, "updated_at" datetime, "personne_id" integer, "type_personne" varchar, "date_creation" datetime, foreign key("staff_id") references "staff"("id") on delete cascade);



DROP TABLE IF EXISTS "salles";
CREATE TABLE "salles" ("id" integer primary key autoincrement not null, "nom" varchar not null, "numero" varchar, "capacite" integer, "description" text, "created_at" datetime, "updated_at" datetime);



DROP TABLE IF EXISTS "sessions";
CREATE TABLE "sessions" ("id" varchar not null, "user_id" integer, "ip_address" varchar, "user_agent" text, "payload" text not null, "last_activity" integer not null, primary key ("id"));

INSERT INTO "sessions" VALUES ('OqDZ1XiFrOB0nhhVEahAgqwMS2p9dpLYyOlxKhty',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.127.0 Chrome/148.0.7778.97 Electron/42.2.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiVGRyTWFhUXBvaU5LU3dxZXNUM0xPbGJmRFdmSm82VEMwMnhrZ1JlRyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=','1783160607');
INSERT INTO "sessions" VALUES ('laLKKO63HYrFRRYXUyFuqJTxhXnGgKh6rW0vy2fm',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','YTo2OntzOjY6Il90b2tlbiI7czo0MDoia21LYnAzR2VUbGdwRkpZVTBjM3FlVjJFTG9ScXE0MXV3OUFBRW9xNSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9jaGF0L3VucmVhZCI7czo1OiJyb3V0ZSI7czoxOToibW9kdWxlcy5jaGF0LnVucmVhZCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6MTE6InV0aWxpc2F0ZXVyIjthOjQ6e3M6MjoiaWQiO2k6NTtzOjM6Im5vbSI7czoxMDoiVG9qbyBBZG1pbiI7czo1OiJlbWFpbCI7czoxNDoidG9qb0BnbWFpbC5jb20iO3M6NDoicm9sZSI7czo1OiJhZG1pbiI7fXM6MTY6Il9pZHhfcHJlc2VuY2Vfb2siO2I6MTtzOjEzOiJfaWR4X25vdGVzX29rIjtiOjE7fQ==','1783168419');


DROP TABLE IF EXISTS "staff";
CREATE TABLE "staff" ("id" integer primary key autoincrement not null, "nom" varchar not null, "prenom" varchar not null, "poste" varchar not null, "departement_id" integer, "email" varchar, "telephone" varchar, "created_at" datetime, "updated_at" datetime, "role_id" integer, "qr_token" varchar, "statut" varchar not null default 'actif', "photo" varchar, "annee_scolaire" varchar, "date_inscription" datetime, "salaire_base" numeric, "diplome_pedagogique" varchar not null default 'Aucun', "annees_experience" integer not null default '0', foreign key("departement_id") references departements("id") on delete set null on update no action, foreign key("role_id") references "roles"("id") on delete set null);

INSERT INTO "staff" VALUES ('1','Randria','Staff','Agent administratif',NULL,'staff.demo@novaskol.local','0340000100',NULL,NULL,NULL,'nvs_2ODUeJVtS6246a48e34c','actif','images/default-avatar.png','2026-2027',NULL,'200000','Aucun','0');


DROP TABLE IF EXISTS "sync_batches";
CREATE TABLE "sync_batches" ("id" integer primary key autoincrement not null, "uuid" varchar not null, "device_uuid" varchar not null, "direction" varchar not null default 'push', "statut" varchar not null default 'en_attente', "total_changements" integer not null default '0', "total_appliques" integer not null default '0', "total_conflits" integer not null default '0', "resume_json" text, "message_erreur" text, "demarre_at" datetime, "termine_at" datetime, "created_at" datetime, "updated_at" datetime);



DROP TABLE IF EXISTS "sync_changes";
CREATE TABLE "sync_changes" ("id" integer primary key autoincrement not null, "uuid" varchar not null, "batch_uuid" varchar, "device_uuid" varchar not null, "utilisateur_id" integer, "module" varchar, "table_name" varchar not null, "record_uuid" varchar not null, "operation" varchar not null, "payload_json" text, "checksum" varchar, "statut" varchar not null default 'en_attente', "message_erreur" text, "action_at" datetime, "applique_at" datetime, "created_at" datetime, "updated_at" datetime);



DROP TABLE IF EXISTS "sync_conflicts";
CREATE TABLE "sync_conflicts" ("id" integer primary key autoincrement not null, "uuid" varchar not null, "change_uuid" varchar, "device_uuid" varchar not null, "table_name" varchar not null, "record_uuid" varchar not null, "type_conflit" varchar not null default 'modification_concurrente', "donnees_locales_json" text, "donnees_entrantes_json" text, "resolution" varchar, "resolu_par" integer, "resolu_at" datetime, "created_at" datetime, "updated_at" datetime);



DROP TABLE IF EXISTS "sync_devices";
CREATE TABLE "sync_devices" ("id" integer primary key autoincrement not null, "uuid" varchar not null, "nom" varchar not null, "type_appareil" varchar not null default 'pc', "role_sync" varchar not null default 'appareil_connecte', "plateforme" varchar, "adresse_ip" varchar, "code_appairage" varchar, "autorise" tinyint(1) not null default '0', "created_by" integer, "utilisateur_id" integer, "utilisateur_role" varchar, "paired_at" datetime, "dernier_contact_at" datetime, "last_bootstrap_at" datetime, "created_at" datetime, "updated_at" datetime);

INSERT INTO "sync_devices" VALUES ('1','bbcc1070-cd7a-4dce-ba43-82e9d450839e','DESKTOP-LTQMMOV','pc','appareil_principal','Windows / DESKTOP-LTQMMOV','192.168.16.100',NULL,'1','5',NULL,NULL,NULL,'2026-07-04 12:10:40',NULL,'2026-07-04 12:10:40','2026-07-04 12:10:40');


DROP TABLE IF EXISTS "sync_record_keys";
CREATE TABLE "sync_record_keys" ("id" integer primary key autoincrement not null, "table_name" varchar not null, "record_id" integer not null, "record_uuid" varchar not null, "checksum" varchar, "last_seen_at" datetime, "created_at" datetime, "updated_at" datetime);



DROP TABLE IF EXISTS "teacher_lessons";
CREATE TABLE "teacher_lessons" ("id" integer primary key autoincrement not null, "professeur_id" integer not null, "classe_id" integer, "matiere_id" integer, "annee_scolaire" varchar, "titre" varchar not null, "rubrique" varchar, "date_prevue" date, "date_realisee" date, "statut" varchar check ("statut" in ('a_preparer', 'planifie', 'en_cours', 'termine')) not null default 'a_preparer', "progression" integer not null default '0', "objectifs" text, "notes" text, "created_at" datetime, "updated_at" datetime);



DROP TABLE IF EXISTS "teacher_tasks";
CREATE TABLE "teacher_tasks" ("id" integer primary key autoincrement not null, "professeur_id" integer not null, "lesson_id" integer, "titre" varchar not null, "date_echeance" date, "priorite" varchar check ("priorite" in ('basse', 'normale', 'haute')) not null default 'normale', "termine" tinyint(1) not null default '0', "completed_at" datetime, "created_at" datetime, "updated_at" datetime);



DROP TABLE IF EXISTS "types_paiements";
CREATE TABLE "types_paiements" ("id" integer primary key autoincrement not null, "nom" varchar not null, "description" text, "created_at" datetime, "updated_at" datetime, "montant" numeric, "mois" text, "date_creation" datetime, "classe" varchar, "date_debut" date, "date_fin" date, "id_classe" integer, "type_personne" varchar, "person_id" integer, "annee_scolaire" varchar);

INSERT INTO "types_paiements" VALUES ('1','Ecolage',NULL,NULL,NULL,'150000','["Juillet"]','2026-07-04 11:59:58','6eme','2026-07-04','2026-07-08','1',NULL,NULL,'2026-2027');


DROP TABLE IF EXISTS "typing_status";
CREATE TABLE "typing_status" ("id" integer primary key autoincrement not null, "conversation_id" integer not null, "user_type" varchar check ("user_type" in ('admin', 'enseignant', 'staff', 'parent')) not null, "user_id" integer not null, "expires_at" datetime not null, "created_at" datetime, "updated_at" datetime, foreign key("conversation_id") references "conversations"("id") on delete cascade);



DROP TABLE IF EXISTS "users";
CREATE TABLE "users" ("id" integer primary key autoincrement not null, "name" varchar not null, "email" varchar not null, "email_verified_at" datetime, "password" varchar not null, "remember_token" varchar, "created_at" datetime, "updated_at" datetime);



DROP TABLE IF EXISTS "utilisateurs";
CREATE TABLE "utilisateurs" ("id" integer primary key autoincrement not null, "nom" varchar not null, "email" varchar not null, "mot_de_passe" varchar not null, "avatar" varchar default 'images/default-avatar.png', "role" varchar check ("role" in ('admin', 'enseignant', 'staff', 'parent')) not null default 'enseignant', "cree_le" datetime not null default CURRENT_TIMESTAMP, "last_activity" datetime, "created_at" datetime, "updated_at" datetime, "qr_token" varchar);

INSERT INTO "utilisateurs" VALUES ('1','Admin Demo','admin@novaskol.local','$2y$10$xIl/9wgfZM1usPFXBAs24eNybAPbmaX9QMUQPIcnp9wHKmI2EIctW','images/default-avatar.png','admin','2026-07-02 18:19:12',NULL,NULL,NULL,NULL);
INSERT INTO "utilisateurs" VALUES ('2','Rakoto Demo','enseignant.demo@novaskol.local','$2y$10$oQpamI8emXhmfrQyJy0L2O/7qOr98suTcf3Yt79hwvqrRYEtDEQV6','images/prof_WT97rKFeSCtY6npx.png','enseignant','2026-07-02 18:19:13',NULL,NULL,NULL,NULL);
INSERT INTO "utilisateurs" VALUES ('3','Randria Staff','staff.demo@novaskol.local','$2y$10$oQpamI8emXhmfrQyJy0L2O/7qOr98suTcf3Yt79hwvqrRYEtDEQV6','images/default-avatar.png','staff','2026-07-02 18:19:13',NULL,NULL,NULL,NULL);
INSERT INTO "utilisateurs" VALUES ('4','Parent Demo','parent.demo@novaskol.local','$2y$10$oQpamI8emXhmfrQyJy0L2O/7qOr98suTcf3Yt79hwvqrRYEtDEQV6','images/default-avatar.png','parent','2026-07-02 18:19:13',NULL,NULL,NULL,NULL);
INSERT INTO "utilisateurs" VALUES ('5','Tojo Admin','tojo@gmail.com','$2y$10$tKSDfVIu8tcUPNnjcGbj3uNqhaf2HTsYoZxfWVfZDiTKKiprHeDJi','images/default-avatar.png','admin','2026-07-04 10:27:45','2026-07-04 12:32:26',NULL,NULL,NULL);

