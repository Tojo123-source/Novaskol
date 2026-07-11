<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add last_activity column to utilisateurs table if it doesn't exist
        // This handles both new installations and upgrades
        if (Schema::hasTable('utilisateurs')) {
            if (!Schema::hasColumn('utilisateurs', 'last_activity')) {
                Schema::table('utilisateurs', function (Blueprint $table) {
                    $table->dateTime('last_activity')->nullable()->after('cree_le');
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('utilisateurs', 'last_activity')) {
            Schema::table('utilisateurs', function (Blueprint $table) {
                $table->dropColumn('last_activity');
            });
        }
    }
};
