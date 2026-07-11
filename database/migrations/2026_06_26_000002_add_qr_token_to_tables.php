<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private array $tables = [
        'eleves' => ['after' => 'matricule'],
        'enseignants' => ['after' => 'email'],
        'professeurs' => ['after' => 'email'],
        'staff' => ['after' => 'email'],
        'utilisateurs' => ['after' => 'email'],
    ];

    public function up(): void
    {
        foreach ($this->tables as $table => $opts) {
            if (Schema::hasTable($table) && !Schema::hasColumn($table, 'qr_token')) {
                Schema::table($table, function (Blueprint $t) use ($opts) {
                    $t->string('qr_token', 100)->nullable()->unique()->after($opts['after']);
                });
            }
        }
    }

    public function down(): void
    {
        foreach ($this->tables as $table => $opts) {
            if (Schema::hasColumn($table, 'qr_token')) {
                Schema::table($table, function (Blueprint $t) {
                    $t->dropColumn('qr_token');
                });
            }
        }
    }
};
