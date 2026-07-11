<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('presence_eleves')) return;

        // Add type_scan column
        if (!Schema::hasColumn('presence_eleves', 'type_scan')) {
            Schema::table('presence_eleves', function (Blueprint $t) {
                $t->string('type_scan', 20)->nullable()->after('session_jour');
            });
        }

        // Drop old unique index
        foreach (['presence_eleves_eleve_id_date_jour_session_jour_unique', 'presence_eleves_unique_day_session'] as $idx) {
            try {
                DB::statement("DROP INDEX IF EXISTS \"{$idx}\"");
            } catch (\Throwable $e) {}
        }

        // Create new unique index with type_scan
        try {
            Schema::table('presence_eleves', function (Blueprint $t) {
                $t->unique(['eleve_id', 'date_jour', 'session_jour', 'type_scan']);
            });
        } catch (\Throwable $e) {}

        // Backfill existing rows: matin → entree, apres_midi → sortie
        DB::table('presence_eleves')
            ->whereNull('type_scan')
            ->where('session_jour', 'matin')
            ->update(['type_scan' => 'entree']);

        DB::table('presence_eleves')
            ->whereNull('type_scan')
            ->where('session_jour', 'apres_midi')
            ->update(['type_scan' => 'sortie']);
    }

    public function down(): void
    {
    }
};
