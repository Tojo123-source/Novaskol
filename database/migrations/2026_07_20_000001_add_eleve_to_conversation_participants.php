<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE conversation_participants MODIFY COLUMN user_type ENUM('admin','enseignant','staff','parent','eleve') NOT NULL");
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE conversation_participants MODIFY COLUMN user_type ENUM('admin','enseignant','staff','parent') NOT NULL");
        }
    }
};
