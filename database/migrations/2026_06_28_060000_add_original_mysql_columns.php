<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // =============================================
        // 1. professeurs — add missing columns
        // =============================================
        if (Schema::hasTable('professeurs')) {
            Schema::table('professeurs', function (Blueprint $t) {
                if (!Schema::hasColumn('professeurs', 'annee_scolaire')) {
                    $t->string('annee_scolaire', 10)->nullable();
                }
                if (!Schema::hasColumn('professeurs', 'matiere_id')) {
                    $t->unsignedBigInteger('matiere_id')->nullable();
                }
                if (!Schema::hasColumn('professeurs', 'salaire_horaire')) {
                    $t->decimal('salaire_horaire', 10, 0)->nullable();
                }
                if (!Schema::hasColumn('professeurs', 'diplome_pedagogique')) {
                    $t->string('diplome_pedagogique', 100)->default('Aucun');
                }
                if (!Schema::hasColumn('professeurs', 'autorisation_enseigner')) {
                    $t->string('autorisation_enseigner', 20)->default('Non');
                }
                if (!Schema::hasColumn('professeurs', 'annees_experience')) {
                    $t->integer('annees_experience')->default(0);
                }
                if (!Schema::hasColumn('professeurs', 'statut')) {
                    $t->string('statut', 20)->default('actif');
                }
                if (!Schema::hasColumn('professeurs', 'date_inscription')) {
                    $t->timestamp('date_inscription')->nullable();
                }
            });

            if (Schema::hasColumn('professeurs', 'matiere_id')) {
                try {
                    Schema::table('professeurs', function (Blueprint $t) {
                        $t->foreign('matiere_id')->references('id')->on('matieres')->onDelete('set null');
                    });
                } catch (\Throwable $e) {
                    // Foreign key may already exist or matieres table may be empty
                }
            }
        }

        // =============================================
        // 2. staff — add missing columns
        // =============================================
        if (Schema::hasTable('staff')) {
            Schema::table('staff', function (Blueprint $t) {
                if (!Schema::hasColumn('staff', 'photo')) {
                    $t->string('photo', 255)->nullable();
                }
                if (!Schema::hasColumn('staff', 'annee_scolaire')) {
                    $t->string('annee_scolaire', 20)->nullable();
                }
                if (!Schema::hasColumn('staff', 'date_inscription')) {
                    $t->timestamp('date_inscription')->nullable();
                }
                if (!Schema::hasColumn('staff', 'salaire_base')) {
                    $t->decimal('salaire_base', 10, 0)->nullable();
                }
                if (!Schema::hasColumn('staff', 'diplome_pedagogique')) {
                    $t->string('diplome_pedagogique', 100)->default('Aucun');
                }
                if (!Schema::hasColumn('staff', 'annees_experience')) {
                    $t->integer('annees_experience')->default(0);
                }
                if (!Schema::hasColumn('staff', 'statut')) {
                    $t->string('statut', 20)->default('actif');
                }
            });
        }

        // =============================================
        // 3. presence_personnels — add missing columns + session_jour + fix unique index
        // =============================================
        if (Schema::hasTable('presence_personnels')) {
            Schema::table('presence_personnels', function (Blueprint $t) {
                if (!Schema::hasColumn('presence_personnels', 'personne_id')) {
                    $t->unsignedBigInteger('personne_id')->nullable();
                }
                if (!Schema::hasColumn('presence_personnels', 'annee_scolaire')) {
                    $t->string('annee_scolaire', 20)->nullable();
                }
                if (!Schema::hasColumn('presence_personnels', 'mois')) {
                    $t->string('mois', 20)->nullable();
                }
                if (!Schema::hasColumn('presence_personnels', 'date_enregistrement')) {
                    $t->timestamp('date_enregistrement')->nullable();
                }
                if (!Schema::hasColumn('presence_personnels', 'horaire')) {
                    $t->decimal('horaire', 10, 2)->nullable();
                }
                if (!Schema::hasColumn('presence_personnels', 'presence')) {
                    $t->boolean('presence')->nullable();
                }
                if (!Schema::hasColumn('presence_personnels', 'retard')) {
                    $t->boolean('retard')->default(false);
                }
                if (!Schema::hasColumn('presence_personnels', 'session_jour')) {
                    $t->string('session_jour', 20)->nullable();
                }
            });

            // Drop old unique index (staff_id, date_jour) → create (staff_id, date_jour, session_jour)
            DB::statement('DROP INDEX IF EXISTS "presence_personnels_staff_id_date_jour_unique"');
            Schema::table('presence_personnels', function (Blueprint $t) {
                $t->unique(['staff_id', 'date_jour', 'session_jour']);
            });
        }

        // =============================================
        // 4. presence_staff — add missing columns + session_jour + fix unique index
        // =============================================
        if (Schema::hasTable('presence_staff')) {
            Schema::table('presence_staff', function (Blueprint $t) {
                if (!Schema::hasColumn('presence_staff', 'personne_id')) {
                    $t->unsignedBigInteger('personne_id')->nullable();
                }
                if (!Schema::hasColumn('presence_staff', 'annee_scolaire')) {
                    $t->string('annee_scolaire', 20)->nullable();
                }
                if (!Schema::hasColumn('presence_staff', 'mois')) {
                    $t->string('mois', 20)->nullable();
                }
                if (!Schema::hasColumn('presence_staff', 'date_enregistrement')) {
                    $t->timestamp('date_enregistrement')->nullable();
                }
                if (!Schema::hasColumn('presence_staff', 'presence')) {
                    $t->boolean('presence')->nullable();
                }
                if (!Schema::hasColumn('presence_staff', 'retard')) {
                    $t->boolean('retard')->default(false);
                }
                if (!Schema::hasColumn('presence_staff', 'jours')) {
                    $t->decimal('jours', 10, 2)->nullable();
                }
                if (!Schema::hasColumn('presence_staff', 'session_jour')) {
                    $t->string('session_jour', 20)->nullable();
                }
            });

            DB::statement('DROP INDEX IF EXISTS "presence_staff_staff_id_date_jour_unique"');
            Schema::table('presence_staff', function (Blueprint $t) {
                $t->unique(['staff_id', 'date_jour', 'session_jour']);
            });
        }

        // =============================================
        // 5. enseignants — add missing columns from original schema
        // =============================================
        if (Schema::hasTable('enseignants')) {
            Schema::table('enseignants', function (Blueprint $t) {
                if (!Schema::hasColumn('enseignants', 'matiere')) {
                    $t->string('matiere', 100)->nullable();
                }
                if (!Schema::hasColumn('enseignants', 'annee_scolaire')) {
                    $t->string('annee_scolaire', 10)->nullable();
                }
                if (!Schema::hasColumn('enseignants', 'date_embauche')) {
                    $t->date('date_embauche')->nullable();
                }
                if (!Schema::hasColumn('enseignants', 'statut')) {
                    $t->string('statut', 20)->default('actif');
                }
            });
        }
    }

    public function down(): void
    {
    }
};
