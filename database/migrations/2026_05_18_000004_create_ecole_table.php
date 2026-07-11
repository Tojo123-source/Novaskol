<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('ecole')) {
            Schema::create('ecole', function (Blueprint $table) {
                $table->id();
                $table->string('nom', 150)->nullable();
                $table->string('logo', 200)->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('ecole');
    }
};
