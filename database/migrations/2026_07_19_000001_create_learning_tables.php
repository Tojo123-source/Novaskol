<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Courses
        if (!Schema::hasTable('courses')) {
            Schema::create('courses', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('enseignant_id')->index();
                $table->unsignedBigInteger('matiere_id')->nullable()->index();
                $table->string('titre', 200);
                $table->text('description')->nullable();
                $table->string('niveau', 50)->nullable();
                $table->string('image', 255)->nullable();
                $table->enum('statut', ['brouillon', 'publie', 'archive'])->default('brouillon');
                $table->timestamps();
            });
        }

        // 2. Course Chapters
        if (!Schema::hasTable('course_chapitres')) {
            Schema::create('course_chapitres', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('course_id')->index();
                $table->string('titre', 200);
                $table->text('description')->nullable();
                $table->integer('ordre')->default(0);
                $table->enum('statut', ['publie', 'masque'])->default('publie');
                $table->timestamps();
            });
        }

        // 3. Chapter Files (PDFs, videos, documents)
        if (!Schema::hasTable('course_fichiers')) {
            Schema::create('course_fichiers', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('chapitre_id')->index();
                $table->enum('type', ['pdf', 'video', 'document', 'image', 'audio', 'other'])->default('document');
                $table->string('nom_original', 255);
                $table->string('fichier_path', 500);
                $table->string('mime_type', 100)->nullable();
                $table->bigInteger('taille')->default(0);
                $table->timestamps();
            });
        }

        // 4. Student Course Progress
        if (!Schema::hasTable('course_progression')) {
            Schema::create('course_progression', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('eleve_id')->index();
                $table->unsignedBigInteger('chapitre_id')->index();
                $table->boolean('termine')->default(false);
                $table->integer('score')->nullable();
                $table->timestamp('completed_at')->nullable();
                $table->timestamps();
                $table->unique(['eleve_id', 'chapitre_id']);
            });
        }

        // 5. Student Favorite Courses
        if (!Schema::hasTable('course_favoris')) {
            Schema::create('course_favoris', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('eleve_id')->index();
                $table->unsignedBigInteger('course_id')->index();
                $table->timestamps();
                $table->unique(['eleve_id', 'course_id']);
            });
        }

        // 6. Exercises
        if (!Schema::hasTable('exercices')) {
            Schema::create('exercices', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('chapitre_id')->index();
                $table->string('titre', 200);
                $table->text('description')->nullable();
                $table->enum('type', ['qcm', 'vrai_faux', 'texte', 'appariement'])->default('qcm');
                $table->integer('temps_limite')->nullable()->comment('en secondes');
                $table->boolean('publie')->default(false);
                $table->timestamps();
            });
        }

        // 7. Exercise Questions
        if (!Schema::hasTable('exercice_questions')) {
            Schema::create('exercice_questions', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('exercice_id')->index();
                $table->text('question');
                $table->json('options')->nullable();
                $table->text('reponse_correcte');
                $table->integer('points')->default(1);
                $table->integer('ordre')->default(0);
                $table->timestamps();
            });
        }

        // 8. Student Exercise Submissions
        if (!Schema::hasTable('exercice_soumissions')) {
            Schema::create('exercice_soumissions', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('exercice_id')->index();
                $table->unsignedBigInteger('eleve_id')->index();
                $table->json('reponses');
                $table->decimal('score', 5, 2)->default(0);
                $table->integer('temps_realise')->nullable()->comment('en secondes');
                $table->timestamp('termine_le')->nullable();
                $table->timestamps();
                $table->index(['eleve_id', 'exercice_id']);
            });
        }

        // 9. School Activation (replaces old licence)
        if (!Schema::hasTable('activations')) {
            Schema::create('activations', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('ecole_id')->default(1)->index();
                $table->string('cle_activation', 100)->unique();
                $table->enum('statut', ['active', 'expiree', 'en_attente', 'caduque'])->default('en_attente');
                $table->date('date_activation')->nullable();
                $table->date('date_expiration')->nullable();
                $table->integer('max_eleves')->default(0);
                $table->decimal('montant', 12, 2)->default(0);
                $table->string('email_contact', 200)->nullable();
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        }

        // 10. Activity History Log
        if (!Schema::hasTable('historique_actions')) {
            Schema::create('historique_actions', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('utilisateur_id')->nullable()->index();
                $table->string('action', 100);
                $table->string('module', 80)->nullable();
                $table->string('cible_type', 100)->nullable();
                $table->unsignedBigInteger('cible_id')->nullable();
                $table->json('details')->nullable();
                $table->string('ip', 45)->nullable();
                $table->string('user_agent', 500)->nullable();
                $table->timestamps();
                $table->index(['module', 'action']);
                $table->index('created_at');
            });
        }

        // 11. Anonymous Reports (signalements)
        if (!Schema::hasTable('signalements')) {
            Schema::create('signalements', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('eleve_id')->nullable()->index();
                $table->string('type', 50)->default('autre');
                $table->text('message');
                $table->boolean('anonyme')->default(true);
                $table->boolean('traite')->default(false);
                $table->timestamp('traite_le')->nullable();
                $table->unsignedBigInteger('traite_par')->nullable();
                $table->text('reponse')->nullable();
                $table->timestamps();
            });
        }

        // 12. Add display_name and cours_en_ligne fields to teacher_lessons
        if (Schema::hasTable('teacher_lessons') && !Schema::hasColumn('teacher_lessons', 'course_id')) {
            Schema::table('teacher_lessons', function (Blueprint $table) {
                $table->unsignedBigInteger('course_id')->nullable()->after('id');
                $table->boolean('is_online_session')->default(false)->after('notes');
                $table->dateTime('session_date')->nullable()->after('is_online_session');
                $table->text('session_invitation')->nullable()->after('session_date');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('signalements');
        Schema::dropIfExists('historique_actions');
        Schema::dropIfExists('activations');
        Schema::dropIfExists('exercice_soumissions');
        Schema::dropIfExists('exercice_questions');
        Schema::dropIfExists('exercices');
        Schema::dropIfExists('course_favoris');
        Schema::dropIfExists('course_progression');
        Schema::dropIfExists('course_fichiers');
        Schema::dropIfExists('course_chapitres');
        Schema::dropIfExists('courses');

        if (Schema::hasColumn('teacher_lessons', 'course_id')) {
            Schema::table('teacher_lessons', function (Blueprint $table) {
                $table->dropColumn(['course_id', 'is_online_session', 'session_date', 'session_invitation']);
            });
        }
    }
};
