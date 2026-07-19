<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('eleves', function (Blueprint $table) {
            if (!Schema::hasColumn('eleves', 'email')) {
                $table->string('email', 100)->nullable()->unique()->after('telephone');
            }
            if (!Schema::hasColumn('eleves', 'mot_de_passe')) {
                $table->string('mot_de_passe', 255)->nullable()->after('email');
            }
            if (!Schema::hasColumn('eleves', 'last_activity')) {
                $table->dateTime('last_activity')->nullable()->after('mot_de_passe');
            }
        });
    }

    public function down(): void
    {
        Schema::table('eleves', function (Blueprint $table) {
            $table->dropColumn(['email', 'mot_de_passe', 'last_activity']);
        });
    }
};
