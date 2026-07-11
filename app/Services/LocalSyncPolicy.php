<?php

namespace App\Services;

class LocalSyncPolicy
{
    public function canReceiveChange(object $user, string $table, string $operation, array $payload = []): bool
    {
        $role = (string) ($user->role ?? '');
        $table = trim($table);
        $operation = trim($operation);

        if (! in_array($operation, ['create', 'update', 'delete'], true)) {
            return false;
        }

        if ($table === 'utilisateurs' && $operation === 'update' && (int) ($payload['id'] ?? 0) === (int) ($user->id ?? 0)) {
            return true;
        }

        if ($role === 'admin') {
            return in_array($table, $this->adminWritableTables(), true);
        }

        if ($role === 'enseignant') {
            return in_array($table, $this->teacherWritableTables(), true);
        }

        if ($role === 'staff') {
            return in_array($table, $this->staffWritableTables(), true);
        }

        if ($role === 'parent') {
            return in_array($table, $this->parentWritableTables(), true);
        }

        return false;
    }

    public function writableTablesForRole(string $role): array
    {
        return match ($role) {
            'admin' => $this->adminWritableTables(),
            'enseignant' => $this->teacherWritableTables(),
            'staff' => $this->staffWritableTables(),
            'parent' => $this->parentWritableTables(),
            default => [],
        };
    }

    private function adminWritableTables(): array
    {
        return [
            'ecole',
            'parametres',
            'classes',
            'matieres',
            'classe_matieres',
            'eleves',
            'parents',
            'parent_eleves',
            'professeurs',
            'professeurs_classes',
            'staff',
            'mpiasa',
            'utilisateurs',
            'permissions',
            'roles',
            'departements',
            'notes',
            'remarques',
            'examen_blanc',
            'remarques_examen_blanc',
            'bulletins',
            'emploi_du_temps',
            'evenements',
            'presence_eleves',
            'presence_personnels',
            'presence_staff',
            'paiements',
            'paiements_assignes',
            'types_paiements',
            'revenus',
            'depenses',
            'salaires_assignes',
            'dossiers',
            'fichiers',
            'notifications',
            'conversations',
            'conversation_participants',
            'messages',
            'message_reactions',
            'typing_status',
            'teacher_lessons',
            'teacher_tasks',
        ];
    }

    private function teacherWritableTables(): array
    {
        return [
            'notes',
            'remarques',
            'examen_blanc',
            'remarques_examen_blanc',
            'presence_eleves',
            'teacher_lessons',
            'teacher_tasks',
            'messages',
            'message_reactions',
            'typing_status',
        ];
    }

    private function staffWritableTables(): array
    {
        return [
            'presence_eleves',
            'presence_personnels',
            'presence_staff',
            'dossiers',
            'fichiers',
            'notifications',
            'evenements',
            'emploi_du_temps',
            'messages',
            'message_reactions',
            'typing_status',
        ];
    }

    private function parentWritableTables(): array
    {
        return [
            'messages',
            'message_reactions',
            'typing_status',
        ];
    }
}
