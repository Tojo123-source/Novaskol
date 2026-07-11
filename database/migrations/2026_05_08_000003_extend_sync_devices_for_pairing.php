<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('sync_devices')) {
            return;
        }

        foreach ([
            'utilisateur_id' => fn (Blueprint $table) => $table->unsignedBigInteger('utilisateur_id')->nullable()->index()->after('created_by'),
            'utilisateur_role' => fn (Blueprint $table) => $table->string('utilisateur_role', 40)->nullable()->index()->after('utilisateur_id'),
            'paired_at' => fn (Blueprint $table) => $table->timestamp('paired_at')->nullable()->after('utilisateur_role'),
            'last_bootstrap_at' => fn (Blueprint $table) => $table->timestamp('last_bootstrap_at')->nullable()->after('dernier_contact_at'),
        ] as $column => $definition) {
            if (! Schema::hasColumn('sync_devices', $column)) {
                Schema::table('sync_devices', $definition);
            }
        }
    }

    public function down(): void
    {
        if (! Schema::hasTable('sync_devices')) {
            return;
        }

        foreach (['last_bootstrap_at', 'paired_at', 'utilisateur_role', 'utilisateur_id'] as $column) {
            if (Schema::hasColumn('sync_devices', $column)) {
                Schema::table('sync_devices', fn (Blueprint $table) => $table->dropColumn($column));
            }
        }
    }
};
