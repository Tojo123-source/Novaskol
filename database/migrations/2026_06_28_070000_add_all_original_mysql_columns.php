<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ==============================================================
        // 1. evenements — missing: type, createur_id, date_creation
        // ==============================================================
        if (Schema::hasTable('evenements')) {
            Schema::table('evenements', function (Blueprint $t) {
                if (!Schema::hasColumn('evenements', 'type')) {
                    $t->string('type', 100)->nullable();
                }
                if (!Schema::hasColumn('evenements', 'createur_id')) {
                    $t->unsignedBigInteger('createur_id')->nullable();
                }
                if (!Schema::hasColumn('evenements', 'date_creation')) {
                    $t->timestamp('date_creation')->nullable();
                }
            });
        }

        // ==============================================================
        // 2. types_paiements — missing: montant, mois, date_creation,
        //    classe, date_debut, date_fin, id_classe, type_personne,
        //    person_id, annee_scolaire
        // ==============================================================
        if (Schema::hasTable('types_paiements')) {
            Schema::table('types_paiements', function (Blueprint $t) {
                if (!Schema::hasColumn('types_paiements', 'montant')) {
                    $t->decimal('montant', 10, 2)->nullable();
                }
                if (!Schema::hasColumn('types_paiements', 'mois')) {
                    $t->text('mois')->nullable();
                }
                if (!Schema::hasColumn('types_paiements', 'date_creation')) {
                    $t->timestamp('date_creation')->nullable();
                }
                if (!Schema::hasColumn('types_paiements', 'classe')) {
                    $t->string('classe', 50)->nullable();
                }
                if (!Schema::hasColumn('types_paiements', 'date_debut')) {
                    $t->date('date_debut')->nullable();
                }
                if (!Schema::hasColumn('types_paiements', 'date_fin')) {
                    $t->date('date_fin')->nullable();
                }
                if (!Schema::hasColumn('types_paiements', 'id_classe')) {
                    $t->unsignedBigInteger('id_classe')->nullable();
                }
                if (!Schema::hasColumn('types_paiements', 'type_personne')) {
                    $t->string('type_personne', 20)->nullable();
                }
                if (!Schema::hasColumn('types_paiements', 'person_id')) {
                    $t->unsignedBigInteger('person_id')->nullable();
                }
                if (!Schema::hasColumn('types_paiements', 'annee_scolaire')) {
                    $t->string('annee_scolaire', 20)->nullable();
                }
            });
        }

        // ==============================================================
        // 3. notes — missing: id_eleve, id_matiere, note, coefficient,
        //    remarque, periode, type_note
        // ==============================================================
        if (Schema::hasTable('notes')) {
            Schema::table('notes', function (Blueprint $t) {
                if (!Schema::hasColumn('notes', 'id_eleve')) {
                    $t->unsignedBigInteger('id_eleve')->nullable();
                }
                if (!Schema::hasColumn('notes', 'id_matiere')) {
                    $t->unsignedBigInteger('id_matiere')->nullable();
                }
                if (!Schema::hasColumn('notes', 'note')) {
                    $t->float('note')->nullable();
                }
                if (!Schema::hasColumn('notes', 'coefficient')) {
                    $t->integer('coefficient')->default(1);
                }
                if (!Schema::hasColumn('notes', 'remarque')) {
                    $t->text('remarque')->nullable();
                }
                if (!Schema::hasColumn('notes', 'periode')) {
                    $t->string('periode', 10)->nullable();
                }
                if (!Schema::hasColumn('notes', 'type_note')) {
                    $t->string('type_note', 20)->nullable();
                }
            });
        }

        // ==============================================================
        // 4. remarques — missing: id_eleve, periode, remarque
        // ==============================================================
        if (Schema::hasTable('remarques')) {
            Schema::table('remarques', function (Blueprint $t) {
                if (!Schema::hasColumn('remarques', 'id_eleve')) {
                    $t->unsignedBigInteger('id_eleve')->nullable();
                }
                if (!Schema::hasColumn('remarques', 'periode')) {
                    $t->string('periode', 10)->nullable();
                }
                if (!Schema::hasColumn('remarques', 'remarque')) {
                    $t->text('remarque')->nullable();
                }
            });
        }

        // ==============================================================
        // 5. examen_blanc — missing: eleve_id, matiere_id, session,
        //    note, date_examen
        // ==============================================================
        if (Schema::hasTable('examen_blanc')) {
            Schema::table('examen_blanc', function (Blueprint $t) {
                if (!Schema::hasColumn('examen_blanc', 'eleve_id')) {
                    $t->unsignedBigInteger('eleve_id')->nullable();
                }
                if (!Schema::hasColumn('examen_blanc', 'matiere_id')) {
                    $t->unsignedBigInteger('matiere_id')->nullable();
                }
                if (!Schema::hasColumn('examen_blanc', 'session')) {
                    $t->string('session', 10)->nullable();
                }
                if (!Schema::hasColumn('examen_blanc', 'note')) {
                    $t->decimal('note', 4, 2)->nullable();
                }
                if (!Schema::hasColumn('examen_blanc', 'date_examen')) {
                    $t->date('date_examen')->nullable();
                }
            });
        }

        // ==============================================================
        // 6. remarques_examen_blanc — missing: id_eleve, session, remarque
        // ==============================================================
        if (Schema::hasTable('remarques_examen_blanc')) {
            Schema::table('remarques_examen_blanc', function (Blueprint $t) {
                if (!Schema::hasColumn('remarques_examen_blanc', 'id_eleve')) {
                    $t->unsignedBigInteger('id_eleve')->nullable();
                }
                if (!Schema::hasColumn('remarques_examen_blanc', 'session')) {
                    $t->string('session', 10)->nullable();
                }
                if (!Schema::hasColumn('remarques_examen_blanc', 'remarque')) {
                    $t->text('remarque')->nullable();
                }
                if (!Schema::hasColumn('remarques_examen_blanc', 'annee_scolaire')) {
                    $t->string('annee_scolaire', 10)->nullable();
                }
            });
        }

        // ==============================================================
        // 7. notifications — missing: type, message, destinataire_id,
        //    date_creation, statut, date_envoi, lu
        // ==============================================================
        if (Schema::hasTable('notifications')) {
            Schema::table('notifications', function (Blueprint $t) {
                if (!Schema::hasColumn('notifications', 'type')) {
                    $t->string('type', 100)->nullable();
                }
                if (!Schema::hasColumn('notifications', 'message')) {
                    $t->text('message')->nullable();
                }
                if (!Schema::hasColumn('notifications', 'destinataire_id')) {
                    $t->unsignedBigInteger('destinataire_id')->nullable();
                }
                if (!Schema::hasColumn('notifications', 'date_creation')) {
                    $t->timestamp('date_creation')->nullable();
                }
                if (!Schema::hasColumn('notifications', 'statut')) {
                    $t->string('statut', 20)->nullable();
                }
                if (!Schema::hasColumn('notifications', 'date_envoi')) {
                    $t->timestamp('date_envoi')->nullable();
                }
                if (!Schema::hasColumn('notifications', 'lu')) {
                    $t->boolean('lu')->default(false);
                }
            });
        }

        // ==============================================================
        // 8. depenses — missing: nom_personne, date_enregistrement
        // ==============================================================
        if (Schema::hasTable('depenses')) {
            Schema::table('depenses', function (Blueprint $t) {
                if (!Schema::hasColumn('depenses', 'nom_personne')) {
                    $t->string('nom_personne', 255)->nullable();
                }
                if (!Schema::hasColumn('depenses', 'date_enregistrement')) {
                    $t->timestamp('date_enregistrement')->nullable();
                }
            });
        }

        // ==============================================================
        // 9. paiements — missing: nom_personne, date_enregistrement
        // ==============================================================
        if (Schema::hasTable('paiements')) {
            Schema::table('paiements', function (Blueprint $t) {
                if (!Schema::hasColumn('paiements', 'nom_personne')) {
                    $t->string('nom_personne', 255)->nullable();
                }
                if (!Schema::hasColumn('paiements', 'date_enregistrement')) {
                    $t->timestamp('date_enregistrement')->nullable();
                }
            });
        }

        // ==============================================================
        // 10. salaires_assignes — missing: personne_id, type_personne,
        //     date_creation
        // ==============================================================
        if (Schema::hasTable('salaires_assignes')) {
            Schema::table('salaires_assignes', function (Blueprint $t) {
                if (!Schema::hasColumn('salaires_assignes', 'personne_id')) {
                    $t->unsignedBigInteger('personne_id')->nullable();
                }
                if (!Schema::hasColumn('salaires_assignes', 'type_personne')) {
                    $t->string('type_personne', 20)->nullable();
                }
                if (!Schema::hasColumn('salaires_assignes', 'date_creation')) {
                    $t->timestamp('date_creation')->nullable();
                }
            });
        }

        // ==============================================================
        // 11. dossiers — missing: annee_scolaire, mois, type_dossier,
        //     personne_id, anarana, fichier, date_upload
        // ==============================================================
        if (Schema::hasTable('dossiers')) {
            Schema::table('dossiers', function (Blueprint $t) {
                if (!Schema::hasColumn('dossiers', 'annee_scolaire')) {
                    $t->string('annee_scolaire', 20)->nullable();
                }
                if (!Schema::hasColumn('dossiers', 'mois')) {
                    $t->string('mois', 20)->nullable();
                }
                if (!Schema::hasColumn('dossiers', 'type_dossier')) {
                    $t->string('type_dossier', 20)->nullable();
                }
                if (!Schema::hasColumn('dossiers', 'personne_id')) {
                    $t->unsignedBigInteger('personne_id')->nullable();
                }
                if (!Schema::hasColumn('dossiers', 'anarana')) {
                    $t->text('anarana')->nullable();
                }
                if (!Schema::hasColumn('dossiers', 'fichier')) {
                    $t->string('fichier', 255)->nullable();
                }
                if (!Schema::hasColumn('dossiers', 'date_upload')) {
                    $t->timestamp('date_upload')->nullable();
                }
            });
        }

        // ==============================================================
        // 12. revenus — missing: type_id, personne_id, type_personne,
        //     classes, mode_paiement, statut, categorie, nom_personne,
        //     date_enregistrement
        // ==============================================================
        if (Schema::hasTable('revenus')) {
            Schema::table('revenus', function (Blueprint $t) {
                if (!Schema::hasColumn('revenus', 'type_id')) {
                    $t->unsignedBigInteger('type_id')->nullable();
                }
                if (!Schema::hasColumn('revenus', 'personne_id')) {
                    $t->string('personne_id', 20)->nullable();
                }
                if (!Schema::hasColumn('revenus', 'type_personne')) {
                    $t->string('type_personne', 20)->nullable();
                }
                if (!Schema::hasColumn('revenus', 'classes')) {
                    $t->string('classes', 20)->nullable();
                }
                if (!Schema::hasColumn('revenus', 'mode_paiement')) {
                    $t->string('mode_paiement')->nullable();
                }
                if (!Schema::hasColumn('revenus', 'statut')) {
                    $t->string('statut')->nullable();
                }
                if (!Schema::hasColumn('revenus', 'categorie')) {
                    $t->string('categorie', 20)->nullable();
                }
                if (!Schema::hasColumn('revenus', 'nom_personne')) {
                    $t->string('nom_personne', 255)->nullable();
                }
                if (!Schema::hasColumn('revenus', 'date_enregistrement')) {
                    $t->timestamp('date_enregistrement')->nullable();
                }
            });
        }

        // ==============================================================
        // 13. paiements_assignes — missing: type_id, eleve_id, person_id,
        //     type_personne
        // ==============================================================
        if (Schema::hasTable('paiements_assignes')) {
            Schema::table('paiements_assignes', function (Blueprint $t) {
                if (!Schema::hasColumn('paiements_assignes', 'type_id')) {
                    $t->unsignedBigInteger('type_id')->nullable();
                }
                if (!Schema::hasColumn('paiements_assignes', 'eleve_id')) {
                    $t->unsignedBigInteger('eleve_id')->nullable();
                }
                if (!Schema::hasColumn('paiements_assignes', 'person_id')) {
                    $t->unsignedBigInteger('person_id')->nullable();
                }
                if (!Schema::hasColumn('paiements_assignes', 'type_personne')) {
                    $t->string('type_personne', 20)->nullable();
                }
            });
        }

        // ==============================================================
        // 14. equipements — missing: quantite (current has type,statut
        //     but code expects quantite)
        // ==============================================================
        if (Schema::hasTable('equipements')) {
            Schema::table('equipements', function (Blueprint $t) {
                if (!Schema::hasColumn('equipements', 'quantite')) {
                    $t->string('quantite', 100)->nullable();
                }
            });
        }

        // ==============================================================
        // 15. reservations_ressources — missing: id_salle, date_reservation,
        //     utilisateur, utilisateur_id, heure_debut, heure_fin, statut,
        //     description
        // ==============================================================
        if (Schema::hasTable('reservations_ressources')) {
            Schema::table('reservations_ressources', function (Blueprint $t) {
                if (!Schema::hasColumn('reservations_ressources', 'id_salle')) {
                    $t->unsignedBigInteger('id_salle')->nullable();
                }
                if (!Schema::hasColumn('reservations_ressources', 'date_reservation')) {
                    $t->datetime('date_reservation')->nullable();
                }
                if (!Schema::hasColumn('reservations_ressources', 'utilisateur')) {
                    $t->unsignedBigInteger('utilisateur')->nullable();
                }
                if (!Schema::hasColumn('reservations_ressources', 'utilisateur_id')) {
                    $t->unsignedBigInteger('utilisateur_id')->nullable();
                }
                if (!Schema::hasColumn('reservations_ressources', 'heure_debut')) {
                    $t->time('heure_debut')->nullable();
                }
                if (!Schema::hasColumn('reservations_ressources', 'heure_fin')) {
                    $t->time('heure_fin')->nullable();
                }
                if (!Schema::hasColumn('reservations_ressources', 'statut')) {
                    $t->string('statut', 20)->nullable();
                }
                if (!Schema::hasColumn('reservations_ressources', 'description')) {
                    $t->text('description')->nullable();
                }
            });
        }

        // ==============================================================
        // 16. chat_typing_status — create via Schema builder
        //     (avoids MySQL ENGINE=InnoDB syntax that crashes SQLite)
        // ==============================================================
        if (!Schema::hasTable('chat_typing_status')) {
            Schema::create('chat_typing_status', function (Blueprint $t) {
                $t->unsignedBigInteger('conversation_id');
                $t->unsignedBigInteger('user_id');
                $t->string('user_role', 50)->nullable();
                $t->timestamp('updated_at')->nullable()->useCurrent();

                $t->primary(['conversation_id', 'user_id']);
                $t->foreign('conversation_id')->references('id')->on('conversations')->onDelete('cascade');
            });
        }

        // ==============================================================
        // 17. Fix evenements date_debut datetime -> datetime if needed
        //     (code inserts datetime values into date column)
        // ==============================================================
        // This is a note: the current evenements.date_debut is 'date' type
        // but the code inserts datetime values like '2026-06-02 09:00:00'.
        // SQLite stores dates as text anyway, so this should work.
        // No schema change needed.
    }

    public function down(): void
    {
        // Not reversing column additions to avoid data loss
    }
};
