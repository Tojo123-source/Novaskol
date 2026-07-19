<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Matieres
        if (!Schema::hasTable('matieres')) {
            Schema::create('matieres', function (Blueprint $table) {
                $table->id();
                $table->string('nom', 100);
                $table->string('code', 20)->nullable();
                $table->timestamps();
            });
        }

        // Classe Matieres
        if (!Schema::hasTable('classe_matieres')) {
            Schema::create('classe_matieres', function (Blueprint $table) {
                $table->unsignedBigInteger('id_classe');
                $table->unsignedBigInteger('id_matiere');
                $table->float('coefficient')->default(1);
                $table->timestamps();

                $table->primary(['id_classe', 'id_matiere']);
                $table->foreign('id_classe')->references('id')->on('classes')->onDelete('cascade');
                $table->foreign('id_matiere')->references('id')->on('matieres')->onDelete('cascade');
            });
        }

        // Parents
        if (!Schema::hasTable('parents')) {
            Schema::create('parents', function (Blueprint $table) {
                $table->id();
                $table->string('nom', 150);
                $table->string('prenom', 150)->nullable();
                $table->string('lien', 50);
                $table->string('telephone', 50)->nullable();
                $table->string('email', 100)->nullable();
                $table->string('adresse', 255)->nullable();
                $table->string('profession', 150)->nullable();
                $table->timestamps();

                $table->index('lien');
            });
        }

        // Parent Eleves
        if (!Schema::hasTable('parent_eleves')) {
            Schema::create('parent_eleves', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('parent_id');
                $table->unsignedBigInteger('eleve_id');
                $table->timestamps();

                $table->unique(['parent_id', 'eleve_id']);
                $table->foreign('parent_id')->references('id')->on('parents')->onDelete('cascade');
                $table->foreign('eleve_id')->references('id')->on('eleves')->onDelete('cascade');
            });
        }

        // Professeurs
        if (!Schema::hasTable('professeurs')) {
            Schema::create('professeurs', function (Blueprint $table) {
                $table->id();
                $table->string('nom', 100);
                $table->string('prenom', 100);
                $table->string('email', 100)->unique();
                $table->string('telephone', 50)->nullable();
                $table->string('specialisation', 100)->nullable();
                $table->text('biographie')->nullable();
                $table->string('photo', 255)->nullable();
                $table->timestamps();
            });
        }

        // Professeurs Classes
        if (!Schema::hasTable('professeurs_classes')) {
            Schema::create('professeurs_classes', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('professeur_id');
                $table->unsignedBigInteger('classe_id');
                $table->unsignedBigInteger('matiere_id')->nullable();
                $table->string('annee_scolaire', 20);
                $table->enum('affectation_type', ['fixe', 'flexible'])->default('fixe');
                $table->string('commentaire', 255)->nullable();
                $table->timestamps();

                $table->unique(['professeur_id', 'classe_id', 'matiere_id', 'annee_scolaire'], 'prof_classes_unique');
                $table->foreign('professeur_id')->references('id')->on('professeurs')->onDelete('cascade');
                $table->foreign('classe_id')->references('id')->on('classes')->onDelete('cascade');
                $table->foreign('matiere_id')->references('id')->on('matieres')->onDelete('set null');
            });
        }

        // Notes
        if (!Schema::hasTable('notes')) {
            Schema::create('notes', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('eleve_id');
                $table->unsignedBigInteger('matiere_id');
                $table->unsignedBigInteger('professeur_id')->nullable();
                $table->float('valeur')->nullable();
                $table->integer('trimestre')->nullable();
                $table->string('annee_scolaire', 20);
                $table->text('observation')->nullable();
                $table->timestamps();

                $table->index(['eleve_id', 'matiere_id', 'trimestre']);
                $table->foreign('eleve_id')->references('id')->on('eleves')->onDelete('cascade');
                $table->foreign('matiere_id')->references('id')->on('matieres')->onDelete('cascade');
                $table->foreign('professeur_id')->references('id')->on('professeurs')->onDelete('set null');
            });
        }

        // Remarques
        if (!Schema::hasTable('remarques')) {
            Schema::create('remarques', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('eleve_id');
                $table->string('titre', 100);
                $table->text('contenu');
                $table->integer('trimestre')->nullable();
                $table->string('annee_scolaire', 20);
                $table->timestamps();

                $table->foreign('eleve_id')->references('id')->on('eleves')->onDelete('cascade');
            });
        }

        // Examen Blanc
        if (!Schema::hasTable('examen_blanc')) {
            Schema::create('examen_blanc', function (Blueprint $table) {
                $table->id();
                $table->string('titre', 100);
                $table->unsignedBigInteger('classe_id');
                $table->date('date_debut')->nullable();
                $table->date('date_fin')->nullable();
                $table->string('annee_scolaire', 20);
                $table->timestamps();

                $table->foreign('classe_id')->references('id')->on('classes')->onDelete('cascade');
            });
        }

        // Remarques Examen Blanc
        if (!Schema::hasTable('remarques_examen_blanc')) {
            Schema::create('remarques_examen_blanc', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('eleve_id');
                $table->unsignedBigInteger('examen_id');
                $table->text('contenu');
                $table->timestamps();

                $table->foreign('eleve_id')->references('id')->on('eleves')->onDelete('cascade');
                $table->foreign('examen_id')->references('id')->on('examen_blanc')->onDelete('cascade');
            });
        }

        // Bulletins
        if (!Schema::hasTable('bulletins')) {
            Schema::create('bulletins', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('id_eleve');
                $table->integer('trimestre')->nullable();
                $table->float('moyenne')->nullable();
                $table->string('mention', 100)->nullable();
                $table->text('appreciation')->nullable();
                $table->string('annee_scolaire', 20);
                $table->timestamps();

                $table->index('id_eleve');
                $table->foreign('id_eleve')->references('id')->on('eleves')->onDelete('cascade');
            });
        }

        // Emploi du Temps
        if (!Schema::hasTable('emploi_du_temps')) {
            Schema::create('emploi_du_temps', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('classe_id');
                $table->unsignedBigInteger('matiere_id');
                $table->unsignedBigInteger('professeur_id')->nullable();
                $table->string('jour', 20);
                $table->time('heure_debut');
                $table->time('heure_fin');
                $table->string('annee_scolaire', 20);
                $table->timestamps();

                $table->foreign('classe_id')->references('id')->on('classes')->onDelete('cascade');
                $table->foreign('matiere_id')->references('id')->on('matieres')->onDelete('cascade');
                $table->foreign('professeur_id')->references('id')->on('professeurs')->onDelete('set null');
            });
        }

        // Evenements
        if (!Schema::hasTable('evenements')) {
            Schema::create('evenements', function (Blueprint $table) {
                $table->id();
                $table->string('titre', 150);
                $table->text('description')->nullable();
                $table->date('date_debut');
                $table->date('date_fin')->nullable();
                $table->string('lieu', 255)->nullable();
                $table->string('annee_scolaire', 20);
                $table->timestamps();
            });
        }

        // Departements
        if (!Schema::hasTable('departements')) {
            Schema::create('departements', function (Blueprint $table) {
                $table->id();
                $table->string('nom', 100);
                $table->timestamps();
            });
        }

        // Staff
        if (!Schema::hasTable('staff')) {
            Schema::create('staff', function (Blueprint $table) {
                $table->id();
                $table->string('nom', 100);
                $table->string('prenom', 100);
                $table->string('poste', 100);
                $table->unsignedBigInteger('departement_id')->nullable();
                $table->string('email', 100)->nullable();
                $table->string('telephone', 50)->nullable();
                $table->timestamps();

                $table->foreign('departement_id')->references('id')->on('departements')->onDelete('set null');
            });
        }

        // Types Paiements
        if (!Schema::hasTable('types_paiements')) {
            Schema::create('types_paiements', function (Blueprint $table) {
                $table->id();
                $table->string('nom', 100);
                $table->text('description')->nullable();
                $table->timestamps();
            });
        }

        // Paiements
        if (!Schema::hasTable('paiements')) {
            Schema::create('paiements', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('type_id')->nullable();
                $table->string('personne_id', 20)->nullable();
                $table->string('type_personne', 20)->nullable();
                $table->string('mois', 20)->nullable();
                $table->string('annee_scolaire', 20);
                $table->decimal('montant', 10, 2)->nullable();
                $table->text('description')->nullable();
                $table->string('mode_paiement')->nullable();
                $table->string('statut')->nullable();
                $table->string('categorie', 255);
                $table->timestamps();

                $table->foreign('type_id')->references('id')->on('types_paiements')->onDelete('set null');
            });
        }

        // Paiements Assignes
        if (!Schema::hasTable('paiements_assignes')) {
            Schema::create('paiements_assignes', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('paiement_id');
                $table->unsignedBigInteger('eleve_id')->nullable();
                $table->unsignedBigInteger('professeur_id')->nullable();
                $table->decimal('montant', 10, 2);
                $table->string('statut', 50)->default('en_attente');
                $table->timestamps();

                $table->foreign('paiement_id')->references('id')->on('paiements')->onDelete('cascade');
                $table->foreign('eleve_id')->references('id')->on('eleves')->onDelete('set null');
                $table->foreign('professeur_id')->references('id')->on('professeurs')->onDelete('set null');
            });
        }

        // Revenus
        if (!Schema::hasTable('revenus')) {
            Schema::create('revenus', function (Blueprint $table) {
                $table->id();
                $table->string('source', 150);
                $table->decimal('montant', 10, 2);
                $table->string('mois', 20)->nullable();
                $table->string('annee_scolaire', 20);
                $table->text('description')->nullable();
                $table->timestamps();
            });
        }

        // Depenses
        if (!Schema::hasTable('depenses')) {
            Schema::create('depenses', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('type_id')->nullable();
                $table->string('personne_id', 20)->nullable();
                $table->string('type_personne', 20)->nullable();
                $table->string('mois', 20)->nullable();
                $table->string('annee_scolaire', 20);
                $table->decimal('montant', 10, 2)->nullable();
                $table->text('description')->nullable();
                $table->string('mode_paiement')->nullable();
                $table->string('statut')->nullable();
                $table->string('categorie', 255);
                $table->timestamps();
            });
        }

        // Salaires Assignes
        if (!Schema::hasTable('salaires_assignes')) {
            Schema::create('salaires_assignes', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('staff_id');
                $table->decimal('montant', 10, 2);
                $table->string('mois', 20);
                $table->string('annee_scolaire', 20);
                $table->string('statut', 50)->default('non_paye');
                $table->timestamps();

                $table->foreign('staff_id')->references('id')->on('staff')->onDelete('cascade');
            });
        }

        // Conversations
        if (!Schema::hasTable('conversations')) {
            Schema::create('conversations', function (Blueprint $table) {
                $table->id();
                $table->enum('type', ['private', 'group']);
                $table->string('name', 100)->nullable();
                $table->unsignedBigInteger('creator_id')->default(0);
                $table->string('avatar', 255)->nullable();
                $table->boolean('is_announcement')->default(0);
                $table->timestamps();
            });
        }

        // Conversation Participants
        if (!Schema::hasTable('conversation_participants')) {
            Schema::create('conversation_participants', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('conversation_id');
                $table->enum('user_type', ['admin', 'enseignant', 'staff', 'parent']);
                $table->unsignedBigInteger('user_id');
                $table->timestamp('joined_at')->useCurrent();
                $table->timestamps();

                $table->unique(['conversation_id', 'user_type', 'user_id'], 'conv_participants_cu_unique');
                $table->foreign('conversation_id')->references('id')->on('conversations')->onDelete('cascade');
            });
        }

        // Messages
        if (!Schema::hasTable('messages')) {
            Schema::create('messages', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('conversation_id');
                $table->string('sender_type', 40)->default('admin');
                $table->unsignedBigInteger('sender_id')->default(0);
                $table->text('content')->nullable();
                $table->string('type', 50)->default('text');
                $table->string('file_path', 255)->nullable();
                $table->string('file_name', 255)->nullable();
                $table->unsignedBigInteger('file_size')->nullable();
                $table->boolean('is_read')->default(false);
                $table->boolean('is_delivered')->default(false);
                $table->timestamp('deleted_at')->nullable();
                $table->timestamps();

                $table->index('conversation_id');
                $table->index('sender_id');
                $table->foreign('conversation_id')->references('id')->on('conversations')->onDelete('cascade');
            });
        }

        // Message Reactions
        if (!Schema::hasTable('message_reactions')) {
            Schema::create('message_reactions', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('message_id');
                $table->enum('user_type', ['admin', 'enseignant', 'staff', 'parent']);
                $table->unsignedBigInteger('user_id');
                $table->string('emoji', 10);
                $table->timestamps();

                $table->unique(['message_id', 'user_type', 'user_id', 'emoji']);
                $table->foreign('message_id')->references('id')->on('messages')->onDelete('cascade');
            });
        }

        // Notifications
        if (!Schema::hasTable('notifications')) {
            Schema::create('notifications', function (Blueprint $table) {
                $table->id();
                $table->enum('user_type', ['admin', 'enseignant', 'staff', 'parent']);
                $table->unsignedBigInteger('user_id');
                $table->string('titre', 150);
                $table->text('contenu')->nullable();
                $table->boolean('lue')->default(0);
                $table->timestamp('read_at')->nullable();
                $table->timestamps();

                $table->index(['user_type', 'user_id', 'lue']);
            });
        }

        // Presence Personnels
        if (!Schema::hasTable('presence_personnels')) {
            Schema::create('presence_personnels', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('staff_id');
                $table->date('date_jour');
                $table->enum('statut', ['present', 'absent', 'retard'])->default('present');
                $table->string('commentaire', 255)->nullable();
                $table->timestamps();

                $table->unique(['staff_id', 'date_jour']);
                $table->foreign('staff_id')->references('id')->on('staff')->onDelete('cascade');
            });
        }

        // Presence Staff (duplicate de presence_personnels)
        if (!Schema::hasTable('presence_staff')) {
            Schema::create('presence_staff', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('staff_id');
                $table->date('date_jour');
                $table->enum('statut', ['present', 'absent', 'retard'])->default('present');
                $table->string('commentaire', 255)->nullable();
                $table->timestamps();

                $table->unique(['staff_id', 'date_jour']);
                $table->foreign('staff_id')->references('id')->on('staff')->onDelete('cascade');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('presence_staff');
        Schema::dropIfExists('presence_personnels');
        Schema::dropIfExists('notifications');
        Schema::dropIfExists('message_reactions');
        Schema::dropIfExists('messages');
        Schema::dropIfExists('conversation_participants');
        Schema::dropIfExists('conversations');
        Schema::dropIfExists('salaires_assignes');
        Schema::dropIfExists('depenses');
        Schema::dropIfExists('revenus');
        Schema::dropIfExists('paiements_assignes');
        Schema::dropIfExists('paiements');
        Schema::dropIfExists('types_paiements');
        Schema::dropIfExists('staff');
        Schema::dropIfExists('departements');
        Schema::dropIfExists('evenements');
        Schema::dropIfExists('emploi_du_temps');
        Schema::dropIfExists('bulletins');
        Schema::dropIfExists('remarques_examen_blanc');
        Schema::dropIfExists('examen_blanc');
        Schema::dropIfExists('remarques');
        Schema::dropIfExists('notes');
        Schema::dropIfExists('professeurs_classes');
        Schema::dropIfExists('professeurs');
        Schema::dropIfExists('parent_eleves');
        Schema::dropIfExists('parents');
        Schema::dropIfExists('classe_matieres');
        Schema::dropIfExists('matieres');
    }
};
