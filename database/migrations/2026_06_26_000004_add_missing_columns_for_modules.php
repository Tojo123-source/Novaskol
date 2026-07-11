<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ===== professeurs =====
        if (Schema::hasTable('professeurs')) {
            if (!Schema::hasColumn('professeurs', 'matiere_id')) {
                Schema::table('professeurs', function (Blueprint $t) {
                    $t->unsignedBigInteger('matiere_id')->nullable()->after('specialisation');
                    $t->foreign('matiere_id')->references('id')->on('matieres')->onDelete('set null');
                });
            }
            if (!Schema::hasColumn('professeurs', 'salaire_horaire')) {
                Schema::table('professeurs', function (Blueprint $t) {
                    $t->decimal('salaire_horaire', 10, 2)->default(0)->after('matiere_id');
                });
            }
            if (!Schema::hasColumn('professeurs', 'annee_scolaire')) {
                Schema::table('professeurs', function (Blueprint $t) {
                    $t->string('annee_scolaire', 20)->nullable()->after('salaire_horaire');
                });
            }
            if (!Schema::hasColumn('professeurs', 'diplome_pedagogique')) {
                Schema::table('professeurs', function (Blueprint $t) {
                    $t->string('diplome_pedagogique', 100)->nullable()->after('annee_scolaire');
                });
            }
            if (!Schema::hasColumn('professeurs', 'autorisation_enseigner')) {
                Schema::table('professeurs', function (Blueprint $t) {
                    $t->string('autorisation_enseigner', 50)->nullable()->after('diplome_pedagogique');
                });
            }
            if (!Schema::hasColumn('professeurs', 'annees_experience')) {
                Schema::table('professeurs', function (Blueprint $t) {
                    $t->integer('annees_experience')->default(0)->after('autorisation_enseigner');
                });
            }
            if (!Schema::hasColumn('professeurs', 'statut')) {
                Schema::table('professeurs', function (Blueprint $t) {
                    $t->string('statut', 50)->default('actif')->after('annees_experience');
                });
            }
        }

        // ===== staff =====
        if (Schema::hasTable('staff')) {
            if (!Schema::hasColumn('staff', 'salaire_base')) {
                Schema::table('staff', function (Blueprint $t) {
                    $t->decimal('salaire_base', 10, 2)->default(0)->after('role_id');
                });
            }
            if (!Schema::hasColumn('staff', 'annee_scolaire')) {
                Schema::table('staff', function (Blueprint $t) {
                    $t->string('annee_scolaire', 20)->nullable()->after('salaire_base');
                });
            }
            if (!Schema::hasColumn('staff', 'diplome_pedagogique')) {
                Schema::table('staff', function (Blueprint $t) {
                    $t->string('diplome_pedagogique', 100)->nullable()->after('annee_scolaire');
                });
            }
            if (!Schema::hasColumn('staff', 'annees_experience')) {
                Schema::table('staff', function (Blueprint $t) {
                    $t->integer('annees_experience')->default(0)->after('diplome_pedagogique');
                });
            }
            if (!Schema::hasColumn('staff', 'photo')) {
                Schema::table('staff', function (Blueprint $t) {
                    $t->string('photo', 255)->nullable()->after('annees_experience');
                });
            }
        }

        // ===== reservations_ressources =====
        if (Schema::hasTable('reservations_ressources')) {
            if (!Schema::hasColumn('reservations_ressources', 'id_salle')) {
                Schema::table('reservations_ressources', function (Blueprint $t) {
                    $t->unsignedBigInteger('id_salle')->nullable()->after('id');
                    $t->foreign('id_salle')->references('id')->on('salles')->onDelete('cascade');
                });
            }
            if (!Schema::hasColumn('reservations_ressources', 'date_reservation')) {
                Schema::table('reservations_ressources', function (Blueprint $t) {
                    $t->date('date_reservation')->nullable()->after('id_salle');
                });
            }
            if (!Schema::hasColumn('reservations_ressources', 'heure_debut')) {
                Schema::table('reservations_ressources', function (Blueprint $t) {
                    $t->time('heure_debut')->nullable()->after('date_reservation');
                });
            }
            if (!Schema::hasColumn('reservations_ressources', 'heure_fin')) {
                Schema::table('reservations_ressources', function (Blueprint $t) {
                    $t->time('heure_fin')->nullable()->after('heure_debut');
                });
            }
            if (!Schema::hasColumn('reservations_ressources', 'utilisateur')) {
                Schema::table('reservations_ressources', function (Blueprint $t) {
                    $t->unsignedBigInteger('utilisateur')->nullable()->after('heure_fin');
                    $t->foreign('utilisateur')->references('id')->on('utilisateurs')->onDelete('set null');
                });
            }
            if (!Schema::hasColumn('reservations_ressources', 'utilisateur_id')) {
                Schema::table('reservations_ressources', function (Blueprint $t) {
                    $t->unsignedBigInteger('utilisateur_id')->nullable()->after('utilisateur');
                });
            }
            if (!Schema::hasColumn('reservations_ressources', 'statut')) {
                Schema::table('reservations_ressources', function (Blueprint $t) {
                    $t->string('statut', 50)->nullable()->after('utilisateur_id');
                });
            }
            if (!Schema::hasColumn('reservations_ressources', 'description')) {
                Schema::table('reservations_ressources', function (Blueprint $t) {
                    $t->text('description')->nullable()->after('statut');
                });
            }
        }

        // ===== types_paiements =====
        if (Schema::hasTable('types_paiements')) {
            if (!Schema::hasColumn('types_paiements', 'montant')) {
                Schema::table('types_paiements', function (Blueprint $t) {
                    $t->decimal('montant', 10, 2)->default(0)->after('description');
                });
            }
            if (!Schema::hasColumn('types_paiements', 'mois')) {
                Schema::table('types_paiements', function (Blueprint $t) {
                    $t->text('mois')->nullable()->after('montant');
                });
            }
            if (!Schema::hasColumn('types_paiements', 'date_creation')) {
                Schema::table('types_paiements', function (Blueprint $t) {
                    $t->datetime('date_creation')->nullable()->after('mois');
                });
            }
            if (!Schema::hasColumn('types_paiements', 'annee_scolaire')) {
                Schema::table('types_paiements', function (Blueprint $t) {
                    $t->string('annee_scolaire', 20)->nullable()->after('date_creation');
                });
            }
            if (!Schema::hasColumn('types_paiements', 'classe')) {
                Schema::table('types_paiements', function (Blueprint $t) {
                    $t->string('classe', 100)->nullable()->after('annee_scolaire');
                });
            }
            if (!Schema::hasColumn('types_paiements', 'date_debut')) {
                Schema::table('types_paiements', function (Blueprint $t) {
                    $t->date('date_debut')->nullable()->after('classe');
                });
            }
            if (!Schema::hasColumn('types_paiements', 'date_fin')) {
                Schema::table('types_paiements', function (Blueprint $t) {
                    $t->date('date_fin')->nullable()->after('date_debut');
                });
            }
            if (!Schema::hasColumn('types_paiements', 'id_classe')) {
                Schema::table('types_paiements', function (Blueprint $t) {
                    $t->unsignedBigInteger('id_classe')->nullable()->after('date_fin');
                });
            }
        }

        // ===== paiements_assignes =====
        if (Schema::hasTable('paiements_assignes')) {
            if (!Schema::hasColumn('paiements_assignes', 'type_id')) {
                Schema::table('paiements_assignes', function (Blueprint $t) {
                    $t->unsignedBigInteger('type_id')->nullable()->after('id');
                    $t->foreign('type_id')->references('id')->on('types_paiements')->onDelete('cascade');
                });
            }
        }

        // ===== salaires_assignes =====
        if (Schema::hasTable('salaires_assignes')) {
            if (!Schema::hasColumn('salaires_assignes', 'personne_id')) {
                Schema::table('salaires_assignes', function (Blueprint $t) {
                    $t->unsignedBigInteger('personne_id')->nullable()->after('id');
                });
            }
            if (!Schema::hasColumn('salaires_assignes', 'type_personne')) {
                Schema::table('salaires_assignes', function (Blueprint $t) {
                    $t->string('type_personne', 50)->nullable()->after('personne_id');
                });
            }
            if (!Schema::hasColumn('salaires_assignes', 'date_creation')) {
                Schema::table('salaires_assignes', function (Blueprint $t) {
                    $t->datetime('date_creation')->nullable()->after('statut');
                });
            }
        }

        // ===== depenses =====
        if (Schema::hasTable('depenses')) {
            if (!Schema::hasColumn('depenses', 'nom_personne')) {
                Schema::table('depenses', function (Blueprint $t) {
                    $t->string('nom_personne', 150)->nullable()->after('categorie');
                });
            }
            if (!Schema::hasColumn('depenses', 'date_enregistrement')) {
                Schema::table('depenses', function (Blueprint $t) {
                    $t->datetime('date_enregistrement')->nullable()->after('nom_personne');
                });
            }
        }

        // ===== revenus =====
        if (Schema::hasTable('revenus')) {
            if (!Schema::hasColumn('revenus', 'type_id')) {
                Schema::table('revenus', function (Blueprint $t) {
                    $t->unsignedBigInteger('type_id')->nullable()->after('id');
                });
            }
            if (!Schema::hasColumn('revenus', 'personne_id')) {
                Schema::table('revenus', function (Blueprint $t) {
                    $t->string('personne_id', 50)->nullable()->after('type_id');
                });
            }
            if (!Schema::hasColumn('revenus', 'type_personne')) {
                Schema::table('revenus', function (Blueprint $t) {
                    $t->string('type_personne', 50)->nullable()->after('personne_id');
                });
            }
            if (!Schema::hasColumn('revenus', 'classes')) {
                Schema::table('revenus', function (Blueprint $t) {
                    $t->string('classes', 100)->nullable()->after('type_personne');
                });
            }
            if (!Schema::hasColumn('revenus', 'mode_paiement')) {
                Schema::table('revenus', function (Blueprint $t) {
                    $t->string('mode_paiement', 50)->nullable()->after('statut');
                });
            }
            if (!Schema::hasColumn('revenus', 'categorie')) {
                Schema::table('revenus', function (Blueprint $t) {
                    $t->string('categorie', 255)->nullable()->after('mode_paiement');
                });
            }
            if (!Schema::hasColumn('revenus', 'nom_personne')) {
                Schema::table('revenus', function (Blueprint $t) {
                    $t->string('nom_personne', 150)->nullable()->after('categorie');
                });
            }
            if (!Schema::hasColumn('revenus', 'date_enregistrement')) {
                Schema::table('revenus', function (Blueprint $t) {
                    $t->datetime('date_enregistrement')->nullable()->after('nom_personne');
                });
            }
            if (!Schema::hasColumn('revenus', 'statut')) {
                Schema::table('revenus', function (Blueprint $t) {
                    $t->string('statut', 50)->nullable()->after('date_enregistrement');
                });
            }
        }

        // ===== presence_personnels =====
        if (Schema::hasTable('presence_personnels')) {
            if (!Schema::hasColumn('presence_personnels', 'personne_id')) {
                Schema::table('presence_personnels', function (Blueprint $t) {
                    $t->unsignedBigInteger('personne_id')->nullable()->after('id');
                });
            }
            if (!Schema::hasColumn('presence_personnels', 'presence')) {
                Schema::table('presence_personnels', function (Blueprint $t) {
                    $t->boolean('presence')->default(0)->after('date_jour');
                });
            }
            if (!Schema::hasColumn('presence_personnels', 'retard')) {
                Schema::table('presence_personnels', function (Blueprint $t) {
                    $t->boolean('retard')->default(0)->after('presence');
                });
            }
            if (!Schema::hasColumn('presence_personnels', 'horaire')) {
                Schema::table('presence_personnels', function (Blueprint $t) {
                    $t->decimal('horaire', 8, 2)->default(0)->after('retard');
                });
            }
            if (!Schema::hasColumn('presence_personnels', 'annee_scolaire')) {
                Schema::table('presence_personnels', function (Blueprint $t) {
                    $t->string('annee_scolaire', 20)->nullable()->after('horaire');
                });
            }
            if (!Schema::hasColumn('presence_personnels', 'mois')) {
                Schema::table('presence_personnels', function (Blueprint $t) {
                    $t->string('mois', 20)->nullable()->after('annee_scolaire');
                });
            }
            if (!Schema::hasColumn('presence_personnels', 'date_enregistrement')) {
                Schema::table('presence_personnels', function (Blueprint $t) {
                    $t->datetime('date_enregistrement')->nullable()->after('mois');
                });
            }
        }

        // ===== presence_staff =====
        if (Schema::hasTable('presence_staff')) {
            if (!Schema::hasColumn('presence_staff', 'personne_id')) {
                Schema::table('presence_staff', function (Blueprint $t) {
                    $t->unsignedBigInteger('personne_id')->nullable()->after('id');
                });
            }
            if (!Schema::hasColumn('presence_staff', 'presence')) {
                Schema::table('presence_staff', function (Blueprint $t) {
                    $t->boolean('presence')->default(0)->after('date_jour');
                });
            }
            if (!Schema::hasColumn('presence_staff', 'retard')) {
                Schema::table('presence_staff', function (Blueprint $t) {
                    $t->boolean('retard')->default(0)->after('presence');
                });
            }
            if (!Schema::hasColumn('presence_staff', 'jours')) {
                Schema::table('presence_staff', function (Blueprint $t) {
                    $t->decimal('jours', 8, 2)->default(0)->after('retard');
                });
            }
            if (!Schema::hasColumn('presence_staff', 'annee_scolaire')) {
                Schema::table('presence_staff', function (Blueprint $t) {
                    $t->string('annee_scolaire', 20)->nullable()->after('jours');
                });
            }
            if (!Schema::hasColumn('presence_staff', 'mois')) {
                Schema::table('presence_staff', function (Blueprint $t) {
                    $t->string('mois', 20)->nullable()->after('annee_scolaire');
                });
            }
            if (!Schema::hasColumn('presence_staff', 'date_enregistrement')) {
                Schema::table('presence_staff', function (Blueprint $t) {
                    $t->datetime('date_enregistrement')->nullable()->after('mois');
                });
            }
        }

        // ===== notes =====
        if (Schema::hasTable('notes')) {
            if (!Schema::hasColumn('notes', 'periode')) {
                Schema::table('notes', function (Blueprint $t) {
                    $t->string('periode', 10)->nullable()->after('trimestre');
                });
            }
            if (!Schema::hasColumn('notes', 'coefficient')) {
                Schema::table('notes', function (Blueprint $t) {
                    $t->float('coefficient')->default(1)->after('valeur');
                });
            }
        }

        // ===== examen_blanc =====
        if (Schema::hasTable('examen_blanc')) {
            if (!Schema::hasColumn('examen_blanc', 'eleve_id')) {
                Schema::table('examen_blanc', function (Blueprint $t) {
                    $t->unsignedBigInteger('eleve_id')->nullable()->after('id');
                    $t->foreign('eleve_id')->references('id')->on('eleves')->onDelete('cascade');
                });
            }
            if (!Schema::hasColumn('examen_blanc', 'matiere_id')) {
                Schema::table('examen_blanc', function (Blueprint $t) {
                    $t->unsignedBigInteger('matiere_id')->nullable()->after('eleve_id');
                    $t->foreign('matiere_id')->references('id')->on('matieres')->onDelete('cascade');
                });
            }
            if (!Schema::hasColumn('examen_blanc', 'note')) {
                Schema::table('examen_blanc', function (Blueprint $t) {
                    $t->float('note')->nullable()->after('matiere_id');
                });
            }
            if (!Schema::hasColumn('examen_blanc', 'session')) {
                Schema::table('examen_blanc', function (Blueprint $t) {
                    $t->string('session', 20)->nullable()->after('note');
                });
            }
        }

        // ===== salles =====
        if (Schema::hasTable('salles')) {
            if (!Schema::hasColumn('salles', 'capacite')) {
                Schema::table('salles', function (Blueprint $t) {
                    $t->integer('capacite')->default(0)->after('numero');
                });
            }
        }

        // ===== equipements =====
        if (Schema::hasTable('equipements')) {
            if (!Schema::hasColumn('equipements', 'quantite')) {
                Schema::table('equipements', function (Blueprint $t) {
                    $t->integer('quantite')->default(1)->after('description');
                });
            }
        }
    }

    public function down(): void
    {
        // professeurs
        foreach (['matiere_id', 'salaire_horaire', 'annee_scolaire', 'diplome_pedagogique', 'autorisation_enseigner', 'annees_experience', 'statut'] as $col) {
            if (Schema::hasColumn('professeurs', $col)) {
                Schema::table('professeurs', fn (Blueprint $t) => $t->dropColumn($col));
            }
        }

        // staff
        foreach (['salaire_base', 'annee_scolaire', 'diplome_pedagogique', 'annees_experience', 'photo'] as $col) {
            if (Schema::hasColumn('staff', $col)) {
                Schema::table('staff', fn (Blueprint $t) => $t->dropColumn($col));
            }
        }

        // reservations_ressources
        foreach (['id_salle', 'date_reservation', 'heure_debut', 'heure_fin', 'utilisateur', 'utilisateur_id', 'statut', 'description'] as $col) {
            if (Schema::hasColumn('reservations_ressources', $col)) {
                Schema::table('reservations_ressources', fn (Blueprint $t) => $t->dropColumn($col));
            }
        }

        // types_paiements
        foreach (['montant', 'mois', 'date_creation', 'annee_scolaire', 'classe', 'date_debut', 'date_fin', 'id_classe'] as $col) {
            if (Schema::hasColumn('types_paiements', $col)) {
                Schema::table('types_paiements', fn (Blueprint $t) => $t->dropColumn($col));
            }
        }

        // paiements_assignes
        if (Schema::hasColumn('paiements_assignes', 'type_id')) {
            Schema::table('paiements_assignes', fn (Blueprint $t) => $t->dropColumn('type_id'));
        }

        // salaires_assignes
        foreach (['personne_id', 'type_personne', 'date_creation'] as $col) {
            if (Schema::hasColumn('salaires_assignes', $col)) {
                Schema::table('salaires_assignes', fn (Blueprint $t) => $t->dropColumn($col));
            }
        }

        // depenses
        foreach (['nom_personne', 'date_enregistrement'] as $col) {
            if (Schema::hasColumn('depenses', $col)) {
                Schema::table('depenses', fn (Blueprint $t) => $t->dropColumn($col));
            }
        }

        // revenus
        foreach (['type_id', 'personne_id', 'type_personne', 'classes', 'mode_paiement', 'categorie', 'nom_personne', 'date_enregistrement', 'statut'] as $col) {
            if (Schema::hasColumn('revenus', $col)) {
                Schema::table('revenus', fn (Blueprint $t) => $t->dropColumn($col));
            }
        }

        // presence tables
        foreach (['presence_personnels', 'presence_staff'] as $table) {
            foreach (['personne_id', 'presence', 'retard', 'horaire', 'jours', 'annee_scolaire', 'mois', 'date_enregistrement'] as $col) {
                if (Schema::hasColumn($table, $col)) {
                    Schema::table($table, fn (Blueprint $t) => $t->dropColumn($col));
                }
            }
        }

        // notes
        foreach (['coefficient'] as $col) {
            if (Schema::hasColumn('notes', $col)) {
                Schema::table('notes', fn (Blueprint $t) => $t->dropColumn($col));
            }
        }

        // examen_blanc
        foreach (['eleve_id', 'matiere_id', 'note', 'session'] as $col) {
            if (Schema::hasColumn('examen_blanc', $col)) {
                Schema::table('examen_blanc', fn (Blueprint $t) => $t->dropColumn($col));
            }
        }

        if (Schema::hasColumn('salles', 'capacite')) {
            Schema::table('salles', fn (Blueprint $t) => $t->dropColumn('capacite'));
        }
        if (Schema::hasColumn('equipements', 'quantite')) {
            Schema::table('equipements', fn (Blueprint $t) => $t->dropColumn('quantite'));
        }
    }
};
