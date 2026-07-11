<?php

namespace App\Http\Controllers;

use App\Services\Novaskol\ModuleRegistry;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;

class SystemDiagnosticController extends Controller
{
    public function __invoke(ModuleRegistry $modules)
    {
        abort_unless(session()->has('utilisateur') && session('utilisateur.role') === 'admin', 403);

        $checks = [
            $this->check('PHP', PHP_VERSION, version_compare(PHP_VERSION, '8.2.0', '>='), 'PHP 8.2 ou plus recommande'),
            $this->check('Base de donnees', config('database.connections.mysql.database'), $this->databaseOk(), 'Connexion MySQL'),
            $this->check('APP_KEY', config('app.key') ? 'Configuree' : 'Manquante', (bool) config('app.key'), 'Cle Laravel'),
            $this->check('Mode debug', config('app.debug') ? 'Actif' : 'Desactive', ! config('app.debug') || app()->environment('local'), 'Desactiver en production'),
            $this->check('Dossier storage', storage_path(), is_writable(storage_path()), 'Ecriture requise'),
            $this->check('Dossier uploads', public_path('legacy/uploads'), $this->writableDir(public_path('legacy/uploads')), 'Photos, fichiers, chat'),
            $this->check('Dossier sauvegardes', public_path('backups'), $this->writableDir(public_path('backups')), 'Sauvegardes SQL'),
            $this->check('Dump vide', database_path('distribution/dump_empty.sql'), File::exists(database_path('distribution/dump_empty.sql')), 'Distribution locale'),
            $this->check('Installation', File::exists(storage_path('app/novaskol-installed.lock')) ? 'Initialisee' : 'Non initialisee', true, 'Etat Novaskol'),
        ];

        return view('modules.parametres.diagnostic', [
            'modules' => $modules->all(),
            'userPermissions' => $this->userPermissions(),
            'ecole' => $this->school(),
            'activeModule' => 'diagnostic_systeme',
            'checks' => $checks,
            'allOk' => collect($checks)->every(fn ($check) => $check['ok']),
        ]);
    }

    private function check(string $label, string $value, bool $ok, string $note): array
    {
        return compact('label', 'value', 'ok', 'note');
    }

    private function databaseOk(): bool
    {
        try {
            DB::select('select 1');
            return Schema::hasTable('utilisateurs');
        } catch (\Throwable) {
            return false;
        }
    }

    private function writableDir(string $path): bool
    {
        File::ensureDirectoryExists($path);

        return is_writable($path);
    }

    private function userPermissions(): array
    {
        $id = (int) session('utilisateur.id', 0);
        return $id ? DB::table('permissions')->where('utilisateur_id', $id)->pluck('acces', 'module')->all() : [];
    }

    private function school(): object
    {
        return DB::table('ecole')->select('id', 'nom', 'logo')->first() ?: (object) ['id' => 1, 'nom' => 'Ecole', 'logo' => 'logo.png'];
    }
}
