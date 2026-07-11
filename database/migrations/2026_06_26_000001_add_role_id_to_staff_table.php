<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('staff', 'role_id')) {
            Schema::table('staff', function (Blueprint $table) {
                $table->unsignedBigInteger('role_id')->nullable()->after('departement_id');
                $table->foreign('role_id')->references('id')->on('roles')->onDelete('set null');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('staff', 'role_id')) {
            Schema::table('staff', function (Blueprint $table) {
                $table->dropForeign(['role_id']);
                $table->dropColumn('role_id');
            });
        }
    }
};
