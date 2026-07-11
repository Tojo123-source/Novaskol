<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use App\Services\LocalSyncCatalog;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('novaskol:make-distribution-dumps {--demo}', function () {
    $dir = database_path('distribution');
    File::ensureDirectoryExists($dir);

    $dump = "-- Novaskol - dump vide de distribution\n";
    $dump .= "-- Genere le ".date('Y-m-d H:i:s')."\n\n";
    $dump .= "SET FOREIGN_KEY_CHECKS=0;\n\n";

    foreach (DB::select('SHOW TABLES') as $row) {
        $table = array_values((array) $row)[0];
        $create = DB::selectOne("SHOW CREATE TABLE `$table`");
        $dump .= "DROP TABLE IF EXISTS `$table`;\n";
        $dump .= array_values((array) $create)[1].";\n\n";
    }

    $dump .= "SET FOREIGN_KEY_CHECKS=1;\n";
    File::put($dir.DIRECTORY_SEPARATOR.'dump_empty.sql', $dump);

    if ($this->option('demo') || ! File::exists($dir.DIRECTORY_SEPARATOR.'dump_demo.sql')) {
        File::put($dir.DIRECTORY_SEPARATOR.'dump_demo.sql', "-- Novaskol - dump demo\n-- Reserve aux donnees fictives.\n-- Ne pas copier les donnees reelles d'une ecole ici.\n");
    }

    $this->info('Dumps de distribution generes dans database/distribution.');
})->purpose('Genere les dumps SQL de distribution Novaskol');

Artisan::command('novaskol:release-check', function () {
    $checks = [
        'Fichier env local' => base_path('.env.local.example'),
        'Fichier env production' => base_path('.env.production.example'),
        'Guide ultra simple ecole' => base_path('LIRE_AVANT_INSTALLATION.md'),
        'Guide installation locale' => base_path('docs/INSTALLATION_LOCALE.md'),
        'Guide hebergement' => base_path('docs/HEBERGEMENT.md'),
        'Guide distribution' => base_path('docs/DISTRIBUTION.md'),
        'Guide application Windows' => base_path('docs/APPLICATION_WINDOWS.md'),
        'Guide application autonome' => base_path('docs/APPLICATION_AUTONOME.md'),
        'Guide synchronisation locale' => base_path('docs/SYNCHRONISATION_LOCALE.md'),
        'Guide Novaskol Connecte' => base_path('docs/NOVASKOL_CONNECTE.md'),
        'Plan installateur Windows' => base_path('docs/PLAN_INSTALLATEUR_WINDOWS.md'),
        'Application Novaskol Connecte' => base_path('apps/novaskol-connecte/index.html'),
        'Configuration Novaskol Connecte' => base_path('apps/novaskol-connecte/app.config.json'),
        'Manifest Novaskol Connecte' => base_path('apps/novaskol-connecte/manifest.webmanifest'),
        'Icone Novaskol Connecte' => base_path('apps/novaskol-connecte/assets/icon.svg'),
        'Build Novaskol Connecte' => base_path('tools/connected/Build-Novaskol-Connected.ps1'),
        'Script Inno Setup' => base_path('tools/installer/inno/novaskol-installer.iss'),
        'Guide Inno Setup' => base_path('tools/installer/inno/README.md'),
        'Guide runtime autonome' => base_path('tools/runtime/README.md'),
        'Icone installateur' => base_path('tools/installer/inno/assets/novaskol.ico'),
        'Image installateur' => base_path('tools/installer/inno/assets/novaskol-wizard.bmp'),
        'Mini image installateur' => base_path('tools/installer/inno/assets/novaskol-small.bmp'),
        'Lanceur Windows' => base_path('tools/windows/Start-Novaskol.ps1'),
        'Arret Windows' => base_path('tools/windows/Stop-Novaskol.ps1'),
        'Preparation Windows' => base_path('tools/windows/Prepare-Novaskol-Local.ps1'),
        'Demarrage base autonome' => base_path('tools/windows/Start-Novaskol-Database.ps1'),
        'Arret base autonome' => base_path('tools/windows/Stop-Novaskol-Database.ps1'),
        'Initialisation base autonome' => base_path('tools/windows/Init-Novaskol-Database.ps1'),
        'Construction runtime autonome' => base_path('tools/windows/Build-Novaskol-Runtime.ps1'),
        'Double-clic preparer Windows' => base_path('tools/windows/Preparer-Novaskol.cmd'),
        'Double-clic lancer Windows' => base_path('tools/windows/Lancer-Novaskol.cmd'),
        'Double-clic arreter Windows' => base_path('tools/windows/Arreter-Novaskol.cmd'),
        'Dump vide' => database_path('distribution/dump_empty.sql'),
        'Dump demo' => database_path('distribution/dump_demo.sql'),
        'Assets legacy' => public_path('legacy'),
        'Dossier stockage' => storage_path('app'),
    ];

    $missing = [];
    foreach ($checks as $label => $path) {
        $exists = File::exists($path) || File::isDirectory($path);
        $this->line(($exists ? '<info>[OK]</info> ' : '<error>[MANQUE]</error> ').$label.' : '.$path);
        if (! $exists) {
            $missing[] = $label;
        }
    }

    $this->newLine();
    if ($missing) {
        $this->error('Distribution incomplete : '.implode(', ', $missing));
        return self::FAILURE;
    }

    $this->info('Novaskol est pret cote fichiers de distribution.');
    $this->comment('Derniere verification conseillee : ouvrir /diagnostic-systeme avec un compte admin.');

    return self::SUCCESS;
})->purpose('Verifie les fichiers indispensables avant de distribuer Novaskol');

Artisan::command('novaskol:sync-prepare-records', function () {
    if (! Schema::hasTable('sync_record_keys')) {
        $this->error('La table sync_record_keys est absente. Lancez les migrations Novaskol avant cette commande.');
        return self::FAILURE;
    }

    $created = app(LocalSyncCatalog::class)->prepareRecordKeys();
    $this->info($created.' cle(s) de synchronisation preparee(s).');
    return self::SUCCESS;
})->purpose('Prepare les identifiants internes stables pour une future synchronisation locale');

Artisan::command('novaskol:prepare-package {type=local : local, hosting ou app} {--with-vendor} {--with-node}', function () {
    $type = strtolower((string) $this->argument('type'));
    if (! in_array($type, ['local', 'hosting', 'app'], true)) {
        $this->error('Type invalide. Utilisez local, hosting ou app.');
        return self::FAILURE;
    }

    $this->call('novaskol:release-check');
    if ($this->output->isVerbose()) {
        $this->newLine();
    }

    $stamp = date('Ymd_His');
    $releaseRoot = storage_path('app/distribution');
    $packageName = 'novaskol-'.$type.'-'.$stamp;
    $packageDir = $releaseRoot.DIRECTORY_SEPARATOR.$packageName;

    File::ensureDirectoryExists($releaseRoot);
    if (File::exists($packageDir)) {
        File::deleteDirectory($packageDir);
    }
    File::ensureDirectoryExists($packageDir);

    $withVendor = (bool) $this->option('with-vendor');
    $withNode = (bool) $this->option('with-node');

    $excludedDirs = [
        '.android-sdk',
        '.jdk21',
        '.git',
        '.idea',
        '.vscode',
        'node_modules',
        'public/build',
        'public/hot',
        'public/storage',
        'public/backups',
        'public/legacy/assets/fontawesome-free-7.2.0-web',
        'storage/app/backups',
        'storage/app/release',
        'storage/app/distribution',
        'storage/app/installer',
        'storage/app/private',
        'storage/app/public',
        'storage/app/visual-audit',
        'storage/framework/cache',
        'storage/framework/sessions',
        'storage/framework/testing',
        'storage/framework/views',
        'storage/logs',
        'storage/runtime',
        'vendor',
        'tools/android-sdk',
        'tools/jdk-21',
        'apps/novaskol-connecte/android',
        'apps/novaskol-connecte/node_modules',
        'apps/novaskol-connecte-desktop/node_modules',
        'desktop/node_modules',
        'storage/app/desktop-dist',
        'storage/app/desktop-connecte-dist',
        'bootstrap/cache',
    ];

    if ($withVendor) {
        $excludedDirs = array_values(array_diff($excludedDirs, ['vendor']));
    }
    if ($withNode) {
        $excludedDirs = array_values(array_diff($excludedDirs, ['node_modules']));
    }

    $excludedFiles = [
        '.env',
        '.env.backup',
        '.env.production',
        '.phpunit.result.cache',
        'database/database.sqlite',
        'storage/app/novaskol-db.pid',
        'storage/app/novaskol-installed.lock',
        'public/legacy/assets/fontawesome-free-7.2.0-web.zip',
        'composer.phar',
        'Thumbs.db',
    ];

    $base = base_path();
    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($base, FilesystemIterator::SKIP_DOTS),
        RecursiveIteratorIterator::SELF_FIRST
    );

    foreach ($iterator as $item) {
        $source = $item->getPathname();
        $relative = str_replace('\\', '/', substr($source, strlen($base) + 1));

        $skip = in_array($relative, $excludedFiles, true);
        if (str_starts_with($relative, 'storage/app/desktop-dist')) {
            $skip = true;
        }
        foreach ($excludedDirs as $dir) {
            if ($relative === $dir || str_starts_with($relative, $dir.'/')) {
                $skip = true;
                break;
            }
        }
        if ($skip) {
            continue;
        }

        $target = $packageDir.DIRECTORY_SEPARATOR.str_replace('/', DIRECTORY_SEPARATOR, $relative);
        if ($item->isDir()) {
            File::ensureDirectoryExists($target);
            continue;
        }

        File::ensureDirectoryExists(dirname($target));
        File::copy($source, $target);
    }

    foreach ([
        $packageDir.DIRECTORY_SEPARATOR.'bootstrap'.DIRECTORY_SEPARATOR.'cache',
        $packageDir.DIRECTORY_SEPARATOR.'storage'.DIRECTORY_SEPARATOR.'framework'.DIRECTORY_SEPARATOR.'views',
        $packageDir.DIRECTORY_SEPARATOR.'storage'.DIRECTORY_SEPARATOR.'framework'.DIRECTORY_SEPARATOR.'cache',
        $packageDir.DIRECTORY_SEPARATOR.'storage'.DIRECTORY_SEPARATOR.'logs',
        $packageDir.DIRECTORY_SEPARATOR.'storage'.DIRECTORY_SEPARATOR.'runtime',
    ] as $cleanupPath) {
        if (File::isDirectory($cleanupPath)) {
            File::deleteDirectory($cleanupPath);
        }
    }

    $manifest = [
        'name' => 'Novaskol',
        'type' => $type,
        'created_at' => now()->toDateTimeString(),
        'offline_first' => true,
        'with_vendor' => $withVendor,
        'with_node_modules' => $withNode,
        'entry_points' => [
            'local_installation' => 'docs/INSTALLATION_LOCALE.md',
            'simple_school_guide' => 'LIRE_AVANT_INSTALLATION.md',
            'hosting' => 'docs/HEBERGEMENT.md',
            'distribution' => 'docs/DISTRIBUTION.md',
            'windows_app_plan' => 'docs/APPLICATION_WINDOWS.md',
            'standalone_app_plan' => 'docs/APPLICATION_AUTONOME.md',
            'windows_installer_plan' => 'docs/PLAN_INSTALLATEUR_WINDOWS.md',
            'inno_setup_script' => 'tools/installer/inno/novaskol-installer.iss',
            'inno_setup_guide' => 'tools/installer/inno/README.md',
            'runtime_guide' => 'tools/runtime/README.md',
            'inno_icon' => 'tools/installer/inno/assets/novaskol.ico',
            'inno_wizard_image' => 'tools/installer/inno/assets/novaskol-wizard.bmp',
            'inno_small_image' => 'tools/installer/inno/assets/novaskol-small.bmp',
            'windows_prepare' => 'tools/windows/Prepare-Novaskol-Local.ps1',
            'windows_database_start' => 'tools/windows/Start-Novaskol-Database.ps1',
            'windows_database_stop' => 'tools/windows/Stop-Novaskol-Database.ps1',
            'windows_database_init' => 'tools/windows/Init-Novaskol-Database.ps1',
            'windows_runtime_builder' => 'tools/windows/Build-Novaskol-Runtime.ps1',
            'windows_start' => 'tools/windows/Start-Novaskol.ps1',
            'windows_stop' => 'tools/windows/Stop-Novaskol.ps1',
            'windows_prepare_cmd' => 'tools/windows/Preparer-Novaskol.cmd',
            'windows_start_cmd' => 'tools/windows/Lancer-Novaskol.cmd',
            'windows_stop_cmd' => 'tools/windows/Arreter-Novaskol.cmd',
        ],
        'important' => [
            'Ne pas reutiliser le meme .env entre deux ecoles.',
            'Ne pas melanger les bases de donnees de deux etablissements.',
            'Ouvrir /installation pour finaliser la premiere configuration.',
            'Ouvrir /diagnostic-systeme avant livraison finale.',
        ],
    ];

    File::put(
        $packageDir.DIRECTORY_SEPARATOR.'NOVASKOL_PACKAGE.json',
        json_encode($manifest, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES).PHP_EOL
    );

    $zipPath = $releaseRoot.DIRECTORY_SEPARATOR.$packageName.'.zip';
    if (class_exists(ZipArchive::class)) {
        $zip = new ZipArchive();
        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
            $files = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($packageDir, FilesystemIterator::SKIP_DOTS),
                RecursiveIteratorIterator::LEAVES_ONLY
            );

            foreach ($files as $file) {
                if (! $file->isFile()) {
                    continue;
                }

                $filePath = $file->getPathname();
                $localName = $packageName.'/'.str_replace('\\', '/', substr($filePath, strlen($packageDir) + 1));
                $zip->addFile($filePath, $localName);
            }

            try {
                $zip->close();
                $this->info('Archive creee : '.$zipPath);
            } catch (Throwable $e) {
                $this->warn('Impossible de finaliser le zip : '.$e->getMessage());
                $this->warn('Le dossier de livraison reste pret et sera utilise pour la suite.');
            }
        } else {
            $this->warn('Impossible de creer le zip. Le dossier de livraison est pret.');
        }
    } else {
        $this->warn('Extension PHP zip indisponible. Le dossier de livraison est pret sans archive zip.');
    }

    $this->info('Dossier de livraison : '.$packageDir);

    return self::SUCCESS;
})->purpose('Prepare un dossier de livraison Novaskol local, hosting ou app');

Artisan::command('novaskol:prepare-installer-source {--with-vendor}', function () {
    $this->call('novaskol:prepare-package', [
        'type' => 'app',
        '--with-vendor' => (bool) $this->option('with-vendor'),
    ]);

    $releaseRoot = storage_path('app/distribution');
    $latest = collect(File::directories($releaseRoot))
        ->filter(fn ($dir) => str_contains(basename($dir), 'novaskol-app-'))
        ->sortByDesc(fn ($dir) => File::lastModified($dir))
        ->first();

    if (! $latest) {
        $this->error('Aucun paquet app trouve.');
        return self::FAILURE;
    }

    $target = $releaseRoot.DIRECTORY_SEPARATOR.'novaskol-app-latest';
    if (File::exists($target)) {
        File::deleteDirectory($target);
    }

    File::copyDirectory($latest, $target);

    foreach ([
        $target.DIRECTORY_SEPARATOR.'bootstrap'.DIRECTORY_SEPARATOR.'cache',
        $target.DIRECTORY_SEPARATOR.'storage'.DIRECTORY_SEPARATOR.'framework'.DIRECTORY_SEPARATOR.'views',
        $target.DIRECTORY_SEPARATOR.'storage'.DIRECTORY_SEPARATOR.'framework'.DIRECTORY_SEPARATOR.'cache',
        $target.DIRECTORY_SEPARATOR.'storage'.DIRECTORY_SEPARATOR.'logs',
        $target.DIRECTORY_SEPARATOR.'storage'.DIRECTORY_SEPARATOR.'runtime',
    ] as $cleanupPath) {
        if (File::isDirectory($cleanupPath)) {
            File::deleteDirectory($cleanupPath);
        }
    }

    $this->info('Source installateur preparee : '.$target);
    $this->comment('Vous pouvez maintenant compiler tools/installer/inno/novaskol-installer.iss avec Inno Setup.');

    return self::SUCCESS;
})->purpose('Prepare le dossier novaskol-app-latest pour compiler l installateur Inno Setup');
