<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * This migration creates the core utilisateurs table that is required by the application.
     * It's safe to run even if the table already exists (won't duplicate).
     */
    public function up(): void
    {
        // Create utilisateurs table if it doesn't exist
        if (!Schema::hasTable('utilisateurs')) {
            Schema::create('utilisateurs', function (Blueprint $table) {
                $table->id();
                $table->string('nom', 100);
                $table->string('email', 100)->unique();
                $table->string('mot_de_passe', 255);
                $table->string('avatar', 255)->nullable()->default('images/default-avatar.png');
                $table->enum('role', ['admin', 'enseignant', 'staff', 'parent'])->default('enseignant');
                $table->dateTime('cree_le')->useCurrent();
                $table->dateTime('last_activity')->nullable();
                $table->timestamps();
            });
        } else {
            // If table exists but column doesn't, add the column
            if (!Schema::hasColumn('utilisateurs', 'last_activity')) {
                Schema::table('utilisateurs', function (Blueprint $table) {
                    $table->dateTime('last_activity')->nullable()->after('cree_le');
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('utilisateurs');
    }
};
