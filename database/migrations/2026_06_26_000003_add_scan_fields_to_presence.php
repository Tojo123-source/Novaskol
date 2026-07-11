<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('presence_eleves')) {
            if (!Schema::hasColumn('presence_eleves', 'scan_mode')) {
                Schema::table('presence_eleves', function (Blueprint $table) {
                    $table->string('scan_mode', 20)->nullable()->after('statut')->comment('manuel, qr_code');
                    $table->unsignedBigInteger('scanned_by')->nullable()->after('scan_mode');
                });
            }
        }

        if (Schema::hasTable('presence_staff')) {
            if (!Schema::hasColumn('presence_staff', 'scan_mode')) {
                Schema::table('presence_staff', function (Blueprint $table) {
                    $table->string('scan_mode', 20)->nullable()->after('statut')->comment('manuel, qr_code');
                    $table->unsignedBigInteger('scanned_by')->nullable()->after('scan_mode');
                });
            }
        }

        if (Schema::hasTable('presence_personnels')) {
            if (!Schema::hasColumn('presence_personnels', 'scan_mode')) {
                Schema::table('presence_personnels', function (Blueprint $table) {
                    $table->string('scan_mode', 20)->nullable()->after('statut')->comment('manuel, qr_code');
                    $table->unsignedBigInteger('scanned_by')->nullable()->after('scan_mode');
                });
            }
        }
    }

    public function down(): void
    {
        $tables = ['presence_eleves', 'presence_staff', 'presence_personnels'];
        foreach ($tables as $table) {
            if (Schema::hasColumn($table, 'scan_mode')) {
                Schema::table($table, function (Blueprint $t) {
                    $t->dropColumn(['scan_mode', 'scanned_by']);
                });
            }
        }
    }
};
