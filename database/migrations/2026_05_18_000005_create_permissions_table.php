<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('permissions')) {
            Schema::create('permissions', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('utilisateur_id')->index();
                $table->string('module', 80)->index();
                $table->string('role', 40)->nullable();
                $table->string('acces', 40)->nullable();
                $table->timestamps();
                $table->unique(['utilisateur_id', 'module']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('permissions');
    }
};
