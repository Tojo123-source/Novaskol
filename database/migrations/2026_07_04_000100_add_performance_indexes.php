<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $tables = [
            'utilisateurs' => ['email', 'role', 'last_activity'],
            'permissions' => ['utilisateur_id', 'module', 'role'],
            'ecole' => ['nom', 'code_postal'],
            'parametres' => ['cle'],
            'classes' => ['nom_classe', 'niveau_classe'],
            'eleves' => ['nom', 'prenom', 'classe_id', 'matricule'],
            'matieres' => ['nom_matiere', 'classe_id'],
            'notes' => ['eleve_id', 'matiere_id', 'classe_id', 'semestre'],
            'presence_eleves' => ['eleve_id', 'date_presence'],
            'paiements' => ['eleve_id', 'classe_id', 'mois', 'annee_scolaire'],
            'emploi_du_temps' => ['classe_id', 'jour_semaine'],
            'evaluations' => ['classe_id', 'matiere_id', 'type_evaluation'],
            'conversations' => ['sender_id', 'receiver_id'],
            'messages' => ['conversation_id', 'sender_id'],
            'trimestre' => ['actif'],
        ];

        foreach ($tables as $table => $columns) {
            if (!Schema::hasTable($table)) continue;
            foreach ($columns as $col) {
                if (!Schema::hasColumn($table, $col)) continue;
                try {
                    Schema::table($table, function (Blueprint $t) use ($col) {
                        $t->index($col);
                    });
                } catch (\Throwable $e) {
                    // index already exists, skip
                }
            }
        }
    }

    public function down(): void
    {
        // Intentionally empty - indexes are safe to keep
    }
};
