<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE conversation_participants MODIFY COLUMN user_type ENUM('admin','enseignant','staff','parent','eleve') NOT NULL");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE conversation_participants MODIFY COLUMN user_type ENUM('admin','enseignant','staff','parent') NOT NULL");
    }
};
