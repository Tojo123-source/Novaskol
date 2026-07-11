<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('conversations') || Schema::hasColumn('conversations', 'is_announcement')) {
            return;
        }

        Schema::table('conversations', function (Blueprint $table) {
            $table->boolean('is_announcement')->default(false)->after('avatar');
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('conversations') || ! Schema::hasColumn('conversations', 'is_announcement')) {
            return;
        }

        Schema::table('conversations', function (Blueprint $table) {
            $table->dropColumn('is_announcement');
        });
    }
};
