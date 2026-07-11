<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('parent_eleves')) {
            return;
        }

        Schema::create('parent_eleves', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('parent_user_id');
            $table->unsignedInteger('eleve_id');
            $table->string('lien', 30)->default('parent');
            $table->string('nom_contact')->nullable();
            $table->string('telephone', 50)->nullable();
            $table->boolean('principal')->default(true);
            $table->timestamps();

            $table->unique(['parent_user_id', 'eleve_id'], 'parent_eleves_unique_link');
            $table->index('eleve_id');
            $table->index('parent_user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('parent_eleves');
    }
};
