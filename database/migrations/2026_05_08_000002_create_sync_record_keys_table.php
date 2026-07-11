<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('sync_record_keys')) {
            return;
        }

        Schema::create('sync_record_keys', function (Blueprint $table) {
            $table->id();
            $table->string('table_name', 100)->index();
            $table->unsignedBigInteger('record_id')->index();
            $table->string('record_uuid', 64)->unique();
            $table->string('checksum', 128)->nullable();
            $table->timestamp('last_seen_at')->nullable();
            $table->timestamps();

            $table->unique(['table_name', 'record_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sync_record_keys');
    }
};
