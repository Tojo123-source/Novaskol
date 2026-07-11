<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('presence_personnels') && !Schema::hasColumn('presence_personnels', 'type_scan')) {
            Schema::table('presence_personnels', function (Blueprint $t) {
                $t->string('type_scan', 20)->nullable()->after('session_jour');
            });
        }

        if (Schema::hasTable('presence_staff') && !Schema::hasColumn('presence_staff', 'type_scan')) {
            Schema::table('presence_staff', function (Blueprint $t) {
                $t->string('type_scan', 20)->nullable()->after('session_jour');
                $t->string('heure_entree', 10)->nullable()->after('type_scan');
                $t->string('heure_sortie', 10)->nullable()->after('heure_entree');
            });
        }

        if (Schema::hasTable('presence_personnels') && !Schema::hasColumn('presence_personnels', 'heure_entree')) {
            Schema::table('presence_personnels', function (Blueprint $t) {
                $t->string('heure_entree', 10)->nullable()->after('type_scan');
                $t->string('heure_sortie', 10)->nullable()->after('heure_entree');
            });
        }
    }

    public function down(): void
    {
        foreach (['presence_personnels', 'presence_staff'] as $table) {
            if (Schema::hasTable($table)) {
                Schema::table($table, function (Blueprint $t) {
                    $t->dropColumn(['type_scan', 'heure_entree', 'heure_sortie']);
                });
            }
        }
    }
};
