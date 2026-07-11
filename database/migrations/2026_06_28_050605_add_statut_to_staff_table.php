<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('staff') && !Schema::hasColumn('staff', 'statut')) {
            Schema::table('staff', function (Blueprint $table) {
                $table->string('statut', 20)->default('actif')->after('annees_experience');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('staff') && Schema::hasColumn('staff', 'statut')) {
            Schema::table('staff', function (Blueprint $table) {
                $table->dropColumn('statut');
            });
        }
    }
};
