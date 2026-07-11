<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('eleves')) {
            Schema::create('eleves', function (Blueprint $table) {
                $table->id();
                $table->string('prenom', 100);
                $table->string('nom', 100);
                $table->date('date_naissance');
                $table->string('lieu_naissance', 150);
                $table->string('adresse', 255);
                $table->string('numero_acte', 100);
                $table->string('fonkotany', 150);
                $table->string('commune', 150);
                $table->string('ecole_ancienne', 150)->nullable();
                $table->string('nom_pere', 150)->nullable();
                $table->string('nom_mere', 150)->nullable();
                $table->string('telephone', 50);
                $table->string('telephone_pere', 50)->nullable();
                $table->string('telephone_mere', 50)->nullable();
                $table->string('profession_pere', 150)->nullable();
                $table->string('profession_mere', 150)->nullable();
                $table->string('adresse_pere', 255)->nullable();
                $table->string('adresse_mere', 255)->nullable();
                $table->unsignedBigInteger('id_classe');
                $table->string('matricule', 100)->unique();
                $table->string('annee_scolaire', 20)->index();
                $table->enum('genre', ['F', 'G'])->default('G');
                $table->enum('statut', ['nouveau', 'passant', 'redoublant'])->default('nouveau');
                $table->tinyInteger('distance_domicile')->default(0);
                $table->tinyInteger('est_handicap')->default(0);
                $table->string('photo', 255)->default('uploads/eleves/default.jpg');
                $table->timestamps();

                $table->index(['annee_scolaire', 'id_classe']);
                $table->index(['nom', 'prenom']);
                $table->foreign('id_classe')->references('id')->on('classes')->onDelete('cascade');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('eleves');
    }
};
