<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('professeurs_classes')) {
            Schema::table('professeurs_classes', function (Blueprint $table) {
                if (! Schema::hasColumn('professeurs_classes', 'affectation_type')) {
                    $table->enum('affectation_type', ['fixe', 'flexible'])->default('fixe')->after('annee_scolaire');
                }
                if (! Schema::hasColumn('professeurs_classes', 'commentaire')) {
                    $table->string('commentaire', 255)->nullable()->after('affectation_type');
                }
            });
        }

        if (! Schema::hasTable('teacher_lessons')) {
            Schema::create('teacher_lessons', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('professeur_id');
                $table->unsignedBigInteger('classe_id')->nullable();
                $table->unsignedBigInteger('matiere_id')->nullable();
                $table->string('annee_scolaire', 20)->nullable();
                $table->string('titre', 180);
                $table->string('rubrique', 120)->nullable();
                $table->date('date_prevue')->nullable();
                $table->date('date_realisee')->nullable();
                $table->enum('statut', ['a_preparer', 'planifie', 'en_cours', 'termine'])->default('a_preparer');
                $table->unsignedTinyInteger('progression')->default(0);
                $table->text('objectifs')->nullable();
                $table->text('notes')->nullable();
                $table->timestamps();

                $table->index(['professeur_id', 'annee_scolaire', 'statut']);
                $table->index(['classe_id', 'matiere_id']);
            });
        }

        if (! Schema::hasTable('teacher_tasks')) {
            Schema::create('teacher_tasks', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('professeur_id');
                $table->unsignedBigInteger('lesson_id')->nullable();
                $table->string('titre', 180);
                $table->date('date_echeance')->nullable();
                $table->enum('priorite', ['basse', 'normale', 'haute'])->default('normale');
                $table->boolean('termine')->default(false);
                $table->timestamp('completed_at')->nullable();
                $table->timestamps();

                $table->index(['professeur_id', 'termine', 'date_echeance']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('teacher_tasks');
        Schema::dropIfExists('teacher_lessons');

        if (Schema::hasTable('professeurs_classes')) {
            Schema::table('professeurs_classes', function (Blueprint $table) {
                if (Schema::hasColumn('professeurs_classes', 'commentaire')) {
                    $table->dropColumn('commentaire');
                }
                if (Schema::hasColumn('professeurs_classes', 'affectation_type')) {
                    $table->dropColumn('affectation_type');
                }
            });
        }
    }
};
