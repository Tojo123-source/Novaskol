<?php

namespace App\Services\Novaskol;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class RelationalDeleteService
{
    private array $columns = [];

    public function deleteStudentRelations(int $studentId): void
    {
        foreach ([
            ['presence_eleves', 'eleve_id'],
            ['parent_eleves', 'eleve_id'],
            ['paiements_assignes', 'eleve_id'],
            ['notes', 'id_eleve'],
            ['bulletins', 'id_eleve'],
            ['remarques', 'id_eleve'],
            ['remarques_examen_blanc', 'id_eleve'],
            ['examen_blanc', 'eleve_id'],
        ] as [$table, $column]) {
            $this->deleteBy($table, $column, $studentId);
        }

        $this->deletePersonDocuments($studentId, ['eleve']);
        $this->deleteAccountingRows($studentId, ['eleve', 'etudiant', 'étudiant']);
    }

    public function deleteClassRelations(int $classId): void
    {
        $this->deleteBy('presence_eleves', 'classe_id', $classId);
        $this->deleteBy('classe_matieres', 'id_classe', $classId);
        $this->deleteBy('emploi_du_temps', 'id_classe', $classId);
        $this->deleteBy('examen_blanc', 'classe_id', $classId);
        $this->deleteBy('professeurs_classes', 'classe_id', $classId);

        if ($this->hasColumn('eleves', 'id_classe')) {
            DB::table('eleves')->where('id_classe', $classId)->update(['id_classe' => null]);
        }
    }

    public function deleteSubjectRelations(int $subjectId): void
    {
        $this->deleteBy('classe_matieres', 'id_matiere', $subjectId);
        $this->deleteBy('notes', 'id_matiere', $subjectId);
        $this->deleteBy('examen_blanc', 'matiere_id', $subjectId);

        if ($this->hasColumn('professeurs', 'matiere_id')) {
            DB::table('professeurs')->where('matiere_id', $subjectId)->update(['matiere_id' => null]);
        }
    }

    public function deleteTeacherRelations(int $teacherId): void
    {
        foreach ([
            ['teacher_tasks', 'professeur_id'],
            ['teacher_lessons', 'professeur_id'],
            ['professeurs_classes', 'professeur_id'],
            ['presence_personnels', 'personne_id'],
        ] as [$table, $column]) {
            $this->deleteBy($table, $column, $teacherId);
        }

        $this->deleteSalaryRows($teacherId, 'professeur');
        $this->deletePersonDocuments($teacherId, ['enseignant', 'professeur']);
        $this->deleteAccountingRows($teacherId, ['professeur', 'enseignant']);
    }

    public function deleteStaffRelations(int $staffId): void
    {
        $this->deleteBy('presence_staff', 'personne_id', $staffId);
        $this->deleteSalaryRows($staffId, 'staff');
        $this->deletePersonDocuments($staffId, ['staff']);
        $this->deleteAccountingRows($staffId, ['staff']);
    }

    public function deleteUserRelations(int $userId, ?string $role = null): void
    {
        $this->deleteBy('permissions', 'utilisateur_id', $userId);
        $this->deleteBy('parent_eleves', 'parent_user_id', $userId);
        $this->deleteBy('conversation_participants', 'user_id', $userId, $role ? ['user_type' => $role] : []);
        $this->deleteBy('typing_status', 'user_id', $userId, $role ? ['user_type' => $role] : []);
        $this->deleteBy('message_reactions', 'user_id', $userId);
    }

    private function deleteSalaryRows(int $personId, string $personType): void
    {
        $this->deleteBy('salaires_assignes', 'personne_id', $personId, ['type_personne' => $personType]);
    }

    private function deletePersonDocuments(int $personId, array $types): void
    {
        if (! $this->hasColumn('dossiers', 'personne_id')) {
            return;
        }

        $query = DB::table('dossiers')->where('personne_id', $personId);
        if ($this->hasColumn('dossiers', 'type_dossier')) {
            $query->whereIn('type_dossier', $types);
        }
        $query->delete();
    }

    private function deleteAccountingRows(int $personId, array $types): void
    {
        foreach (['paiements', 'revenus', 'depenses'] as $table) {
            if (! $this->hasColumn($table, 'personne_id')) {
                continue;
            }

            $query = DB::table($table)->where('personne_id', (string) $personId);
            if ($this->hasColumn($table, 'type_personne')) {
                $query->whereIn('type_personne', $types);
            }
            $query->delete();
        }
    }

    private function deleteBy(string $table, string $column, mixed $value, array $extraWhere = []): void
    {
        if (! $this->hasColumn($table, $column)) {
            return;
        }

        $query = DB::table($table)->where($column, $value);
        foreach ($extraWhere as $extraColumn => $extraValue) {
            if ($this->hasColumn($table, $extraColumn)) {
                $query->where($extraColumn, $extraValue);
            }
        }
        $query->delete();
    }

    private function hasColumn(string $table, string $column): bool
    {
        if (! array_key_exists($table, $this->columns)) {
            $this->columns[$table] = Schema::hasTable($table) ? Schema::getColumnListing($table) : [];
        }

        return in_array($column, $this->columns[$table], true);
    }
}
