<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('presence_eleves')) {
            return;
        }

        Schema::create('presence_eleves', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('eleve_id');
            $table->unsignedBigInteger('classe_id');
            $table->string('annee_scolaire', 20);
            $table->unsignedTinyInteger('mois');
            $table->date('date_jour');
            $table->enum('session_jour', ['matin', 'apres_midi']);
            $table->enum('statut', ['present', 'absent', 'retard'])->default('present');
            $table->string('commentaire', 255)->nullable();
            $table->timestamps();

            $table->unique(['eleve_id', 'date_jour', 'session_jour'], 'presence_eleves_unique_day_session');
            $table->index(['classe_id', 'annee_scolaire', 'mois']);
            $table->index(['date_jour', 'statut']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('presence_eleves');
    }
};
