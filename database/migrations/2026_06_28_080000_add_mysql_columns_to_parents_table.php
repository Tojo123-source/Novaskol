<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('parents')) {
            Schema::table('parents', function (Blueprint $t) {
                if (!Schema::hasColumn('parents', 'nom_pere')) {
                    $t->string('nom_pere', 255)->nullable();
                }
                if (!Schema::hasColumn('parents', 'telephone_pere')) {
                    $t->string('telephone_pere', 20)->nullable();
                }
                if (!Schema::hasColumn('parents', 'profession_pere')) {
                    $t->string('profession_pere', 255)->nullable();
                }
                if (!Schema::hasColumn('parents', 'adresse_pere')) {
                    $t->text('adresse_pere')->nullable();
                }
                if (!Schema::hasColumn('parents', 'nom_mere')) {
                    $t->string('nom_mere', 255)->nullable();
                }
                if (!Schema::hasColumn('parents', 'telephone_mere')) {
                    $t->string('telephone_mere', 20)->nullable();
                }
                if (!Schema::hasColumn('parents', 'profession_mere')) {
                    $t->string('profession_mere', 255)->nullable();
                }
                if (!Schema::hasColumn('parents', 'adresse_mere')) {
                    $t->text('adresse_mere')->nullable();
                }
                if (!Schema::hasColumn('parents', 'annee_scolaire')) {
                    $t->string('annee_scolaire', 9)->nullable();
                }
            });
        }
    }

    public function down(): void
    {
    }
};
