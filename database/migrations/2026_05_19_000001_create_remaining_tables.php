<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Roles
        if (!Schema::hasTable('roles')) {
            Schema::create('roles', function (Blueprint $table) {
                $table->id();
                $table->string('nom', 100);
                $table->text('description')->nullable();
                $table->timestamps();
            });
        }

        // Dossiers
        if (!Schema::hasTable('dossiers')) {
            Schema::create('dossiers', function (Blueprint $table) {
                $table->id();
                $table->string('nom', 150);
                $table->text('description')->nullable();
                $table->unsignedBigInteger('eleve_id')->nullable();
                $table->timestamps();

                $table->foreign('eleve_id')->references('id')->on('eleves')->onDelete('cascade');
            });
        }

        // Fichiers
        if (!Schema::hasTable('fichiers')) {
            Schema::create('fichiers', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('dossier_id')->nullable();
                $table->string('nom', 255);
                $table->string('chemin', 255);
                $table->string('type_mime', 100)->nullable();
                $table->integer('taille')->nullable();
                $table->timestamps();

                $table->foreign('dossier_id')->references('id')->on('dossiers')->onDelete('cascade');
            });
        }

        // Typing Status
        if (!Schema::hasTable('typing_status')) {
            Schema::create('typing_status', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('conversation_id');
                $table->enum('user_type', ['admin', 'enseignant', 'staff', 'parent']);
                $table->unsignedBigInteger('user_id');
                $table->timestamp('expires_at');
                $table->timestamps();

                $table->unique(['conversation_id', 'user_type', 'user_id']);
                $table->foreign('conversation_id')->references('id')->on('conversations')->onDelete('cascade');
            });
        }

        // Personnes
        if (!Schema::hasTable('personnes')) {
            Schema::create('personnes', function (Blueprint $table) {
                $table->id();
                $table->string('nom', 100);
                $table->string('prenom', 100);
                $table->string('email', 100)->nullable();
                $table->string('telephone', 50)->nullable();
                $table->timestamps();
            });
        }

        // Enseignants
        if (!Schema::hasTable('enseignants')) {
            Schema::create('enseignants', function (Blueprint $table) {
                $table->id();
                $table->string('nom', 100);
                $table->string('prenom', 100);
                $table->string('email', 100)->unique();
                $table->string('telephone', 50)->nullable();
                $table->string('specialisation', 100)->nullable();
                $table->timestamps();
            });
        }

        // Equipements
        if (!Schema::hasTable('equipements')) {
            Schema::create('equipements', function (Blueprint $table) {
                $table->id();
                $table->string('nom', 100);
                $table->string('type', 50);
                $table->text('description')->nullable();
                $table->string('statut', 50)->default('disponible');
                $table->timestamps();
            });
        }

        // Ressources
        if (!Schema::hasTable('ressources')) {
            Schema::create('ressources', function (Blueprint $table) {
                $table->id();
                $table->string('nom', 100);
                $table->string('type', 50);
                $table->text('description')->nullable();
                $table->integer('quantite')->default(1);
                $table->timestamps();
            });
        }

        // Salles
        if (!Schema::hasTable('salles')) {
            Schema::create('salles', function (Blueprint $table) {
                $table->id();
                $table->string('nom', 100);
                $table->string('numero', 50)->nullable();
                $table->integer('capacite')->nullable();
                $table->text('description')->nullable();
                $table->timestamps();
            });
        }

        // Reservations
        if (!Schema::hasTable('reservations')) {
            Schema::create('reservations', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('salle_id');
                $table->string('type_reservation', 50);
                $table->string('nom_responsable', 100);
                $table->date('date_debut');
                $table->date('date_fin')->nullable();
                $table->string('statut', 50)->default('confirmee');
                $table->timestamps();

                $table->foreign('salle_id')->references('id')->on('salles')->onDelete('cascade');
            });
        }

        // Reservations Ressources
        if (!Schema::hasTable('reservations_ressources')) {
            Schema::create('reservations_ressources', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('reservation_id');
                $table->unsignedBigInteger('ressource_id');
                $table->integer('quantite')->default(1);
                $table->timestamps();

                $table->foreign('reservation_id')->references('id')->on('reservations')->onDelete('cascade');
                $table->foreign('ressource_id')->references('id')->on('ressources')->onDelete('cascade');
            });
        }

        // Licence
        if (!Schema::hasTable('licence')) {
            Schema::create('licence', function (Blueprint $table) {
                $table->id();
                $table->string('cle_licence', 255)->unique();
                $table->string('proprietaire', 100);
                $table->date('date_activation')->nullable();
                $table->date('date_expiration')->nullable();
                $table->string('statut', 50)->default('active');
                $table->text('donnees_licence')->nullable();
                $table->timestamps();
            });
        }

        // Mpiasa (appears to be a local model, leave empty structure)
        if (!Schema::hasTable('mpiasa')) {
            Schema::create('mpiasa', function (Blueprint $table) {
                $table->id();
                $table->string('nom', 100);
                $table->text('description')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('mpiasa');
        Schema::dropIfExists('licence');
        Schema::dropIfExists('reservations_ressources');
        Schema::dropIfExists('reservations');
        Schema::dropIfExists('salles');
        Schema::dropIfExists('ressources');
        Schema::dropIfExists('equipements');
        Schema::dropIfExists('enseignants');
        Schema::dropIfExists('personnes');
        Schema::dropIfExists('typing_status');
        Schema::dropIfExists('fichiers');
        Schema::dropIfExists('dossiers');
        Schema::dropIfExists('roles');
    }
};
