<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('classes')) {
            Schema::create('classes', function (Blueprint $table) {
                $table->id();
                $table->string('nom', 50);
                $table->integer('niveau')->nullable();
                $table->timestamps();

                $table->index('niveau');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('classes');
    }
};
