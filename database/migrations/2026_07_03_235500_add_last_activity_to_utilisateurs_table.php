<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('utilisateurs', 'last_activity')) {
            Schema::table('utilisateurs', function (Blueprint $table) {
                $table->dateTime('last_activity')->nullable()->after('avatar');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('utilisateurs', 'last_activity')) {
            Schema::table('utilisateurs', function (Blueprint $table) {
                $table->dropColumn('last_activity');
            });
        }
    }
};
