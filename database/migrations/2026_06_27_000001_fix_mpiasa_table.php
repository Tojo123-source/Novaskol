<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('mpiasa')) return;

        if (!Schema::hasColumn('mpiasa', 'prenom')) {
            Schema::table('mpiasa', function (Blueprint $table) {
                $table->string('prenom', 100)->nullable()->after('nom');
                $table->string('email', 100)->nullable()->after('prenom');
                $table->string('telephone', 50)->nullable()->after('email');
                $table->string('type_personne', 50)->nullable()->after('telephone');
                $table->string('annee_scolaire', 20)->nullable()->after('type_personne');
            });
        }
    }

    public function down(): void
    {
        if (!Schema::hasTable('mpiasa')) return;
        $cols = ['prenom', 'email', 'telephone', 'type_personne', 'annee_scolaire'];
        foreach ($cols as $c) {
            if (Schema::hasColumn('mpiasa', $c)) {
                Schema::table('mpiasa', function (Blueprint $table) use ($c) {
                    $table->dropColumn($c);
                });
            }
        }
    }
};
