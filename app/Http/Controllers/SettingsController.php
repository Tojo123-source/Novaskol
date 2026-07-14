<?php

namespace App\Http\Controllers;

use App\Services\Novaskol\ModuleRegistry;
use App\Services\LocalSyncCatalog;
use App\Services\LocalSyncPolicy;
use Illuminate\Http\Request;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class SettingsController extends Controller
{
    private array $keys = [
        'nom_ecole', 'code_ecole', 'adresse_ecole', 'telephone_ecole', 'email_ecole',
        'annee_scolaire', 'date_debut', 'date_fin', 'mention_passable', 'mention_assez_bien',
        'mention_bien', 'mention_tres_bien', 'notifications_mail', 'logo_ecole', 'dren',
        'cisco', 'zap', 'code_etablissement', 'tel_etablissement', 'mail_etablissement', 'nb_comment',
        'devise_nom', 'devise_symbole', 'langue_interface', 'appareil_principal_nom',
        'sync_device_uuid', 'sync_pairing_code', 'sync_pairing_expires_at',
    ];

    public function parameters(ModuleRegistry $modules)
    {
        $this->ensureSession();

        return $this->view('modules.parametres.parameters', $modules, 'parametres', [
            'params' => $this->params(),
        ]);
    }

    public function saveParameters(Request $request)
    {
        $this->ensureSession();

        $data = $request->validate([
            'nom_ecole' => ['nullable', 'string', 'max:255'],
            'code_ecole' => ['nullable', 'string', 'max:100'],
            'adresse_ecole' => ['nullable', 'string', 'max:255'],
            'telephone_ecole' => ['nullable', 'string', 'max:100'],
            'email_ecole' => ['nullable', 'email', 'max:255'],
            'annee_scolaire' => ['nullable', 'string', 'max:20'],
            'date_debut' => ['nullable', 'date'],
            'date_fin' => ['nullable', 'date'],
            'mention_passable' => ['nullable', 'numeric', 'between:0,20'],
            'mention_assez_bien' => ['nullable', 'numeric', 'between:0,20'],
            'mention_bien' => ['nullable', 'numeric', 'between:0,20'],
            'mention_tres_bien' => ['nullable', 'numeric', 'between:0,20'],
            'notifications_mail' => ['nullable', 'in:1'],
            'dren' => ['nullable', 'string', 'max:255'],
            'cisco' => ['nullable', 'string', 'max:255'],
            'zap' => ['nullable', 'string', 'max:255'],
            'code_etablissement' => ['nullable', 'string', 'max:255'],
            'tel_etablissement' => ['nullable', 'string', 'max:100'],
            'mail_etablissement' => ['nullable', 'email', 'max:255'],
            'nb_comment' => ['nullable', 'string', 'max:2000'],
            'devise_nom' => ['nullable', 'string', 'max:80'],
            'devise_symbole' => ['nullable', 'string', 'max:12'],
            'langue_interface' => ['nullable', 'in:fr,en,de,mg,es,pt'],
            'logo_ecole' => ['nullable', 'image', 'mimes:jpg,jpeg,png,gif,webp', 'max:2048'],
        ]);

        $data['notifications_mail'] = $request->boolean('notifications_mail') ? '1' : '0';
        $existing = $this->params();

        if ($request->hasFile('logo_ecole')) {
            $file = $request->file('logo_ecole');
            $name = 'logo_'.time().'.'.$file->getClientOriginalExtension();
            $destination = public_path('legacy/images');
            File::ensureDirectoryExists($destination);
            $file->move($destination, $name);
            $data['logo_ecole'] = 'images/'.$name;

            DB::table('ecole')->updateOrInsert(['id' => 1], [
                'nom' => $data['nom_ecole'] ?: ($existing['nom_ecole'] ?? 'Ecole'),
                'logo' => $name,
            ]);
        } elseif (($data['nom_ecole'] ?? '') !== '') {
            DB::table('ecole')->updateOrInsert(['id' => 1], [
                'nom' => trim($data['nom_ecole']),
                'logo' => $this->school()->logo ?? 'novaskol.png',
            ]);
        }

        foreach ($this->keys as $key) {
            $value = array_key_exists($key, $data)
                ? (string) $data[$key]
                : (string) ($existing[$key] ?? '');
            DB::table('parametres')->updateOrInsert(['cle' => $key], ['valeur' => $value]);
        }

        return redirect()->route('modules.parametres')->with('success', 'Parametres enregistres avec succes.');
    }

    public function backups(ModuleRegistry $modules)
    {
        $this->ensureSession();
        $backupDir = $this->backupDir();
        File::ensureDirectoryExists($backupDir);

        $backups = collect(File::files($backupDir))
            ->filter(fn ($file) => str_ends_with($file->getFilename(), '.sql'))
            ->sortByDesc(fn ($file) => $file->getMTime())
            ->values()
            ->map(fn ($file) => [
                'name' => $file->getFilename(),
                'date' => date('d/m/Y H:i:s', $file->getMTime()),
                'size' => round($file->getSize() / 1024 / 1024, 2).' Mo',
                'is_auto_restore' => str_starts_with($file->getFilename(), 'auto_before_restore_'),
            ]);

        return $this->view('modules.parametres.backups', $modules, 'sauvegardes', compact('backups'));
    }

    public function about(ModuleRegistry $modules)
    {
        $this->ensureSession();

        $backupDir = $this->backupDir();
        File::ensureDirectoryExists($backupDir);

        $lastBackup = collect(File::files($backupDir))
            ->filter(fn ($file) => str_ends_with($file->getFilename(), '.sql'))
            ->sortByDesc(fn ($file) => $file->getMTime())
            ->first();

        $params = $this->params();

        return $this->view('modules.parametres.about', $modules, 'apropos_novaskol', [
            'params' => $params,
            'version' => $params['novaskol_version'] ?? config('app.version', '1.0.0'),
            'modeInstallation' => $params['mode_installation'] ?? 'production',
            'appUrl' => config('app.url'),
            'appEnv' => config('app.env'),
            'debug' => (bool) config('app.debug'),
            'basePath' => base_path(),
            'databaseName' => config('database.connections.'.config('database.default').'.database'),
            'backupCount' => collect(File::files($backupDir))->filter(fn ($file) => str_ends_with($file->getFilename(), '.sql'))->count(),
            'lastBackup' => $lastBackup ? [
                'name' => $lastBackup->getFilename(),
                'date' => date('d/m/Y H:i:s', $lastBackup->getMTime()),
                'size' => round($lastBackup->getSize() / 1024 / 1024, 2).' Mo',
            ] : null,
            'distributionReady' => File::exists(database_path('distribution/dump_empty.sql'))
                && File::exists(database_path('distribution/dump_demo.sql'))
                && File::exists(base_path('LIRE_AVANT_INSTALLATION.md')),
        ]);
    }

    public function guide(ModuleRegistry $modules)
    {
        $this->ensureSession();

        return $this->view('modules.parametres.guide', $modules, 'guide_utilisation', [
            'params' => $this->params(),
        ]);
    }

    public function privacy(ModuleRegistry $modules)
    {
        $this->ensureSession();

        return $this->view('modules.parametres.privacy', $modules, 'parametres', [
            'params' => $this->params(),
        ]);
    }

    public function terms(ModuleRegistry $modules)
    {
        $this->ensureSession();

        return $this->view('modules.parametres.terms', $modules, 'parametres', [
            'params' => $this->params(),
        ]);
    }

    public function localNetwork(ModuleRegistry $modules)
    {
        $this->ensureSession();
        $this->ensureSyncTables();

        $params = $this->params();
        $hostname = php_uname('n') ?: gethostname() ?: 'Appareil principal';
        $deviceName = trim((string) ($params['appareil_principal_nom'] ?? '')) ?: $hostname;
        $port = $this->applicationPort();
        $localIp = $this->localIpv4();
        $localUrl = $localIp ? 'http://'.$localIp.':'.$port : null;
        $networkReady = $localIp ? $this->isTcpReachable($localIp, $port) : false;
        $firewallStatus = $this->firewallStatus($port);
        $currentDevice = $this->ensureCurrentSyncDevice($deviceName, $hostname, $localIp);
        $pairingCode = trim((string) ($params['sync_pairing_code'] ?? ''));
        $pairingExpires = trim((string) ($params['sync_pairing_expires_at'] ?? ''));
        $pairingExpiryAt = null;
        if ($pairingExpires !== '') {
            try {
                $pairingExpiryAt = \Carbon\Carbon::parse($pairingExpires);
            } catch (\Throwable) {
                $pairingExpiryAt = null;
            }
        }
        $pairingActive = $pairingCode !== '' && $pairingExpiryAt && now()->lessThan($pairingExpiryAt);
        $connectedUrl = $localUrl ? $localUrl.'/connecte'.($pairingActive ? '?code='.rawurlencode($pairingCode) : '') : null;
        $networkStats = [
            'devices' => DB::table('sync_devices')->count(),
            'trusted_devices' => DB::table('sync_devices')->where('autorise', true)->count(),
            'known_records' => Schema::hasTable('sync_record_keys') ? DB::table('sync_record_keys')->count() : 0,
            'open_conflicts' => DB::table('sync_conflicts')->whereNull('resolution')->count(),
        ];
        $recentDevices = DB::table('sync_devices')
            ->orderByDesc('dernier_contact_at')
            ->orderByDesc('updated_at')
            ->limit(6)
            ->get();
        $recentBatches = DB::table('sync_batches')
            ->orderByDesc('created_at')
            ->limit(6)
            ->get();
        $recentConflicts = DB::table('sync_conflicts')
            ->whereNull('resolution')
            ->orderByDesc('created_at')
            ->limit(6)
            ->get();

        return $this->view('modules.parametres.local-network', $modules, 'reseau_local', [
            'deviceName' => $deviceName,
            'hostname' => $hostname,
            'port' => $port,
            'localIp' => $localIp,
            'localUrl' => $localUrl,
            'connectedUrl' => $connectedUrl,
            'networkReady' => $networkReady,
            'firewallStatus' => $firewallStatus,
            'currentDevice' => $currentDevice,
            'networkStats' => $networkStats,
            'recentDevices' => $recentDevices,
            'recentBatches' => $recentBatches,
            'recentConflicts' => $recentConflicts,
            'pairingCode' => $pairingActive ? $pairingCode : '',
            'pairingExpires' => $pairingActive ? $pairingExpiryAt : null,
            'params' => $params,
        ]);
    }

    public function saveLocalNetwork(Request $request)
    {
        $this->ensureSession();

        $data = $request->validate([
            'appareil_principal_nom' => ['nullable', 'string', 'max:120'],
        ]);

        DB::table('parametres')->updateOrInsert(
            ['cle' => 'appareil_principal_nom'],
            ['valeur' => trim((string) ($data['appareil_principal_nom'] ?? ''))]
        );

        return redirect()->route('modules.reseau-local')->with('success', 'Nom de l appareil principal enregistre avec succes.');
    }

    public function generatePairingCode()
    {
        $this->ensureSession();
        abort_unless(session('utilisateur.role') === 'admin', 403);

        $alphabet = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
        $prefix = '';
        for ($i = 0; $i < 3; $i++) {
            $prefix .= $alphabet[random_int(0, strlen($alphabet) - 1)];
        }
        $code = $prefix.'-'.random_int(100, 999);
        $expires = now()->addMinutes(30);

        DB::table('parametres')->updateOrInsert(['cle' => 'sync_pairing_code'], ['valeur' => $code]);
        DB::table('parametres')->updateOrInsert(['cle' => 'sync_pairing_expires_at'], ['valeur' => $expires->toDateTimeString()]);

        return redirect()->route('modules.reseau-local')->with('success', 'Code d appairage genere pour 30 minutes.');
    }

    public function createLocalSyncBatch(LocalSyncCatalog $catalog)
    {
        $this->ensureSession();
        abort_unless(session('utilisateur.role') === 'admin', 403);
        $this->ensureSyncTables();

        $deviceUuid = (string) DB::table('parametres')->where('cle', 'sync_device_uuid')->value('valeur');
        if ($deviceUuid === '') {
            $hostname = php_uname('n') ?: gethostname() ?: 'Appareil principal';
            $device = $this->ensureCurrentSyncDevice($hostname, $hostname, $this->localIpv4());
            $deviceUuid = (string) $device->uuid;
        }

        $batch = $catalog->createSnapshotBatch($deviceUuid, (int) session('utilisateur.id'));

        return redirect()->route('modules.reseau-local')->with('success', 'Lot local prepare : '.$batch->total_changements.' donnee(s) reperee(s).');
    }

    public function toggleLocalDevice(int $id)
    {
        $this->ensureSession();
        abort_unless(session('utilisateur.role') === 'admin', 403);
        $this->ensureSyncTables();

        $device = DB::table('sync_devices')->where('id', $id)->first();
        abort_unless($device, 404);

        $currentUuid = (string) DB::table('parametres')->where('cle', 'sync_device_uuid')->value('valeur');
        abort_if($device->uuid === $currentUuid, 422, 'L appareil principal doit rester autorise.');

        DB::table('sync_devices')->where('id', $id)->update([
            'autorise' => ! (bool) $device->autorise,
            'updated_at' => now(),
        ]);

        return redirect()->route('modules.reseau-local')->with('success', (bool) $device->autorise ? 'Appareil revoque.' : 'Appareil autorise.');
    }

    public function renameLocalDevice(Request $request, int $id)
    {
        $this->ensureSession();
        abort_unless(session('utilisateur.role') === 'admin', 403);
        $this->ensureSyncTables();

        $data = $request->validate([
            'nom' => ['required', 'string', 'max:160'],
        ]);

        $device = DB::table('sync_devices')->where('id', $id)->first();
        abort_unless($device, 404);

        DB::table('sync_devices')->where('id', $id)->update([
            'nom' => trim((string) $data['nom']),
            'updated_at' => now(),
        ]);

        return redirect()->route('modules.reseau-local')->with('success', 'Nom de l appareil mis a jour.');
    }

    public function deleteLocalDevice(int $id)
    {
        $this->ensureSession();
        abort_unless(session('utilisateur.role') === 'admin', 403);
        $this->ensureSyncTables();

        $device = DB::table('sync_devices')->where('id', $id)->first();
        abort_unless($device, 404);

        $currentUuid = (string) DB::table('parametres')->where('cle', 'sync_device_uuid')->value('valeur');
        abort_if($device->uuid === $currentUuid, 422, 'L appareil principal ne peut pas etre supprime.');

        DB::table('sync_devices')->where('id', $id)->delete();

        return redirect()->route('modules.reseau-local')->with('success', 'Appareil supprime du suivi local.');
    }

    public function registerLocalDevice(Request $request)
    {
        abort_unless(session()->has('utilisateur') && in_array(session('utilisateur.role'), ['admin', 'staff', 'enseignant', 'parent'], true), 403);
        $this->ensureSyncTables();

        $data = $request->validate([
            'uuid' => ['required', 'string', 'max:64'],
            'nom' => ['nullable', 'string', 'max:160'],
            'type_appareil' => ['nullable', 'string', 'max:40'],
            'plateforme' => ['nullable', 'string', 'max:255'],
            'web_login' => ['nullable', 'boolean'],
        ]);

        $uuid = trim((string) $data['uuid']);
        abort_if($uuid === '', 422);

        $existing = DB::table('sync_devices')->where('uuid', $uuid)->first();
        $payload = [
            'nom' => trim((string) ($data['nom'] ?? '')) ?: 'Appareil connecte',
            'type_appareil' => trim((string) ($data['type_appareil'] ?? '')) ?: 'appareil',
            'role_sync' => 'appareil_connecte',
            'plateforme' => $this->shortDevicePlatform((string) ($data['plateforme'] ?? '')),
            'adresse_ip' => $request->ip(),
            'created_by' => session('utilisateur.id'),
            'utilisateur_id' => session('utilisateur.id'),
            'utilisateur_role' => session('utilisateur.role'),
            'dernier_contact_at' => now(),
            'updated_at' => now(),
        ];

        if ($existing) {
            DB::table('sync_devices')->where('uuid', $uuid)->update($payload);
        } else {
            DB::table('sync_devices')->insert($payload + [
                'uuid' => $uuid,
                'autorise' => false,
                'created_at' => now(),
            ]);
        }

        return response()->json(['success' => true]);
    }

    public function pairConnectedDevice(Request $request, LocalSyncCatalog $catalog, LocalSyncPolicy $policy)
    {
        $this->ensureSyncTables();

        $data = $request->validate([
            'code' => ['required', 'string', 'max:20'],
            'email' => ['required', 'email', 'max:120'],
            'password' => ['required', 'string', 'max:255'],
            'role' => ['nullable', 'in:admin,enseignant,staff,parent'],
            'uuid' => ['required', 'string', 'max:64'],
            'nom' => ['nullable', 'string', 'max:160'],
            'type_appareil' => ['nullable', 'string', 'max:40'],
            'plateforme' => ['nullable', 'string', 'max:255'],
        ]);

        $code = $this->normalizePairingCode((string) $data['code']);
        $expectedRawCode = (string) DB::table('parametres')->where('cle', 'sync_pairing_code')->value('valeur');
        $expectedCode = $this->normalizePairingCode($expectedRawCode);
        $expiresAt = trim((string) DB::table('parametres')->where('cle', 'sync_pairing_expires_at')->value('valeur'));
        $validUntil = null;
        if ($expiresAt !== '') {
            try {
                $validUntil = \Carbon\Carbon::parse($expiresAt);
            } catch (\Throwable) {
                $validUntil = null;
            }
        }

        if ($expectedCode === '' || ! $validUntil || now()->greaterThan($validUntil) || ! hash_equals($expectedCode, $code)) {
            return response()->json([
                'success' => false,
                'message' => 'Code d appairage invalide ou expire. Regenerez un code depuis Reseau local puis reessayez.',
            ], 422);
        }

        $userQuery = DB::table('utilisateurs')->where('email', trim((string) $data['email']));
        if (! empty($data['role'])) {
            $userQuery->where('role', $data['role']);
        }
        $user = $userQuery->first();

        if (! $user || ! Hash::check((string) $data['password'], (string) $user->mot_de_passe)) {
            return response()->json([
                'success' => false,
                'message' => 'Compte ou mot de passe incorrect.',
            ], 422);
        }

        $uuid = trim((string) $data['uuid']);
        abort_if($uuid === '', 422);

        $deviceName = trim((string) ($data['nom'] ?? '')) ?: $this->defaultConnectedDeviceName((string) ($data['type_appareil'] ?? 'appareil'), (string) $user->nom);
        $payload = [
            'nom' => $deviceName,
            'type_appareil' => trim((string) ($data['type_appareil'] ?? '')) ?: 'appareil',
            'role_sync' => 'appareil_connecte',
            'plateforme' => $this->shortDevicePlatform((string) ($data['plateforme'] ?? '')),
            'adresse_ip' => $request->ip(),
            'code_appairage' => $expectedRawCode,
            'autorise' => true,
            'created_by' => (int) $user->id,
            'utilisateur_id' => (int) $user->id,
            'utilisateur_role' => (string) $user->role,
            'paired_at' => now(),
            'dernier_contact_at' => now(),
            'last_bootstrap_at' => now(),
            'updated_at' => now(),
        ];

        $existing = DB::table('sync_devices')->where('uuid', $uuid)->first();
        if ($existing) {
            DB::table('sync_devices')->where('uuid', $uuid)->update($payload);
        } else {
            DB::table('sync_devices')->insert($payload + [
                'uuid' => $uuid,
                'created_at' => now(),
            ]);
        }

        $bootstrap = $catalog->bootstrapForUser($user);

        if ($request->boolean('web_login')) {
            $request->session()->put('utilisateur', [
                'id' => (int) $user->id,
                'nom' => $user->nom,
                'email' => $user->email,
                'role' => $user->role,
            ]);
        }

        $batchUuid = (string) Str::uuid();
        DB::table('sync_batches')->insert([
            'uuid' => $batchUuid,
            'device_uuid' => $uuid,
            'direction' => 'export',
            'statut' => 'termine',
            'total_changements' => (int) collect($bootstrap['summary'])->sum('total'),
            'total_appliques' => (int) collect($bootstrap['summary'])->sum('total'),
            'total_conflits' => 0,
            'resume_json' => json_encode(['type' => 'bootstrap_appareil_connecte', 'summary' => $bootstrap['summary']], JSON_UNESCAPED_UNICODE),
            'message_erreur' => null,
            'demarre_at' => now(),
            'termine_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Appareil connecte avec succes.',
            'school' => [
                'id' => $this->school()->id,
                'nom' => $this->school()->nom,
                'logo' => $this->school()->logo,
            ],
            'device' => [
                'uuid' => $uuid,
                'nom' => $deviceName,
                'autorise' => true,
                'paired_at' => now()->toDateTimeString(),
            ],
            'user' => [
                'id' => (int) $user->id,
                'nom' => $user->nom,
                'email' => $user->email,
                'role' => $user->role,
                'avatar' => $user->avatar ?? null,
            ],
            'permissions' => DB::table('permissions')->where('utilisateur_id', (int) $user->id)->pluck('acces', 'module')->all(),
            'bootstrap' => $bootstrap,
            'sync' => [
                'device_uuid' => $uuid,
                'writable_tables' => $policy->writableTablesForRole((string) $user->role),
                'endpoints' => [
                    'bootstrap' => '/reseau-local/bootstrap-appareil',
                    'push_batch' => '/reseau-local/recevoir-lot',
                ],
            ],
            'redirect_url' => $user->role === 'admin' ? '/dashboard' : '/mon-espace',
        ]);
    }

    public function connectedDeviceManifest(Request $request)
    {
        $this->ensureSyncTables();

        $params = $this->params();
        $pairingCode = trim((string) ($params['sync_pairing_code'] ?? ''));
        $pairingExpires = trim((string) ($params['sync_pairing_expires_at'] ?? ''));
        $pairingExpiryAt = null;
        if ($pairingExpires !== '') {
            try {
                $pairingExpiryAt = \Carbon\Carbon::parse($pairingExpires);
            } catch (\Throwable) {
                $pairingExpiryAt = null;
            }
        }

        $pairingActive = $pairingCode !== '' && $pairingExpiryAt && now()->lessThan($pairingExpiryAt);
        $incomingCode = trim((string) $request->query('code', ''));
        $incomingCodeValid = null;
        if ($incomingCode !== '') {
            $incomingCodeValid = $pairingActive
                && $this->normalizePairingCode($incomingCode) === $this->normalizePairingCode($pairingCode);
        }
        $school = $this->school();

        return response()->json([
            'success' => true,
            'edition' => config('novaskol.edition', 'principal'),
            'server_time' => now()->toDateTimeString(),
            'school' => [
                'id' => (int) $school->id,
                'nom' => $school->nom,
                'logo' => $school->logo,
            ],
            'pairing' => [
                'required' => true,
                'active' => $pairingActive,
                'code_valid' => $incomingCodeValid,
                'expires_at' => $pairingActive ? $pairingExpiryAt->toDateTimeString() : null,
            ],
            'endpoints' => [
                'pair' => $request->getSchemeAndHttpHost().'/reseau-local/appairer-appareil',
                'bootstrap' => $request->getSchemeAndHttpHost().'/reseau-local/bootstrap-appareil',
                'push_batch' => $request->getSchemeAndHttpHost().'/reseau-local/recevoir-lot',
            ],
        ]);
    }

    public function connectedDeviceBootstrap(Request $request, LocalSyncCatalog $catalog, LocalSyncPolicy $policy)
    {
        $this->ensureSyncTables();

        $data = $request->validate([
            'device_uuid' => ['required', 'string', 'max:64'],
        ]);

        $device = DB::table('sync_devices')
            ->where('uuid', trim((string) $data['device_uuid']))
            ->where('autorise', true)
            ->first();

        if (! $device || ! $device->utilisateur_id) {
            return response()->json([
                'success' => false,
                'message' => 'Appareil non autorise ou non appaire.',
            ], 403);
        }

        $user = DB::table('utilisateurs')->where('id', (int) $device->utilisateur_id)->first();
        if (! $user) {
            return response()->json([
                'success' => false,
                'message' => 'Compte utilisateur introuvable.',
            ], 404);
        }

        DB::table('sync_devices')->where('id', $device->id)->update([
            'adresse_ip' => $request->ip(),
            'dernier_contact_at' => now(),
            'last_bootstrap_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'user' => [
                'id' => (int) $user->id,
                'nom' => $user->nom,
                'email' => $user->email,
                'role' => $user->role,
                'avatar' => $user->avatar ?? null,
            ],
            'permissions' => DB::table('permissions')->where('utilisateur_id', (int) $user->id)->pluck('acces', 'module')->all(),
            'bootstrap' => $catalog->bootstrapForUser($user),
            'sync' => [
                'device_uuid' => (string) $device->uuid,
                'writable_tables' => $policy->writableTablesForRole((string) $user->role),
                'endpoints' => [
                    'bootstrap' => '/reseau-local/bootstrap-appareil',
                    'push_batch' => '/reseau-local/recevoir-lot',
                ],
            ],
        ]);
    }

    public function receiveConnectedDeviceBatch(Request $request, LocalSyncPolicy $policy)
    {
        $this->ensureSyncTables();

        $data = $request->validate([
            'device_uuid' => ['required', 'string', 'max:64'],
            'batch_uuid' => ['nullable', 'string', 'max:64'],
            'changes' => ['required', 'array', 'max:1000'],
            'changes.*.uuid' => ['nullable', 'string', 'max:64'],
            'changes.*.module' => ['nullable', 'string', 'max:80'],
            'changes.*.table_name' => ['required', 'string', 'max:100'],
            'changes.*.record_uuid' => ['required', 'string', 'max:64'],
            'changes.*.operation' => ['required', 'in:create,update,delete'],
            'changes.*.payload' => ['nullable', 'array'],
            'changes.*.checksum' => ['nullable', 'string', 'max:128'],
            'changes.*.action_at' => ['nullable', 'date'],
        ]);

        $deviceUuid = trim((string) $data['device_uuid']);
        $device = DB::table('sync_devices')
            ->where('uuid', $deviceUuid)
            ->where('autorise', true)
            ->first();

        if (! $device || ! $device->utilisateur_id) {
            return response()->json([
                'success' => false,
                'message' => 'Appareil non autorise ou non appaire.',
            ], 403);
        }

        $user = DB::table('utilisateurs')->where('id', (int) $device->utilisateur_id)->first();
        if (! $user) {
            return response()->json([
                'success' => false,
                'message' => 'Compte utilisateur introuvable.',
            ], 404);
        }

        $batchUuid = trim((string) ($data['batch_uuid'] ?? '')) ?: (string) Str::uuid();
        $accepted = 0;
        $refused = 0;
        $applied = 0;
        $acceptedRecordUuids = [];
        $refusedRecordUuids = [];
        $now = now();

        DB::transaction(function () use ($data, $deviceUuid, $device, $user, $policy, $batchUuid, &$accepted, &$refused, &$applied, &$acceptedRecordUuids, &$refusedRecordUuids, $now) {
            DB::table('sync_batches')->updateOrInsert(
                ['uuid' => $batchUuid],
                [
                    'device_uuid' => $deviceUuid,
                    'direction' => 'push',
                    'statut' => 'reception',
                    'total_changements' => count($data['changes']),
                    'total_appliques' => 0,
                    'total_conflits' => 0,
                    'resume_json' => null,
                    'message_erreur' => null,
                    'demarre_at' => $now,
                    'termine_at' => null,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );

            foreach ($data['changes'] as $change) {
                $payload = $change['payload'] ?? [];
                $allowed = $policy->canReceiveChange($user, (string) $change['table_name'], (string) $change['operation'], $payload);
                $status = $allowed ? 'recu' : 'refuse';
                $message = $allowed ? null : 'Operation non autorisee pour le role '.$user->role.'.';
                $changeUuid = trim((string) ($change['uuid'] ?? '')) ?: (string) Str::uuid();

                if ($allowed) {
                    try {
                        $wasApplied = $this->applyConnectedChange(
                            (string) $change['table_name'],
                            (string) $change['operation'],
                            (string) $change['record_uuid'],
                            $payload,
                            $now
                        );
                        $status = $wasApplied ? 'applique' : 'recu';
                        $applied += $wasApplied ? 1 : 0;
                    } catch (\Throwable $exception) {
                        $allowed = false;
                        $status = 'refuse';
                        $message = 'Application impossible : '.$exception->getMessage();
                    }
                }

                DB::table('sync_changes')->updateOrInsert(
                    ['uuid' => $changeUuid],
                    [
                        'batch_uuid' => $batchUuid,
                        'device_uuid' => $deviceUuid,
                        'utilisateur_id' => (int) $user->id,
                        'module' => $change['module'] ?? null,
                        'table_name' => (string) $change['table_name'],
                        'record_uuid' => (string) $change['record_uuid'],
                        'operation' => (string) $change['operation'],
                        'payload_json' => json_encode($payload, JSON_UNESCAPED_UNICODE),
                        'checksum' => $change['checksum'] ?? hash('sha256', json_encode($payload, JSON_UNESCAPED_UNICODE)),
                        'statut' => $status,
                        'message_erreur' => $message,
                        'action_at' => ! empty($change['action_at']) ? \Carbon\Carbon::parse($change['action_at']) : $now,
                        'applique_at' => $status === 'applique' ? $now : null,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]
                );

                if ($allowed) {
                    $acceptedRecordUuids[] = (string) $change['record_uuid'];
                    $accepted++;
                } else {
                    $refusedRecordUuids[] = (string) $change['record_uuid'];
                    $refused++;
                    DB::table('sync_conflicts')->updateOrInsert(
                        ['change_uuid' => $changeUuid],
                        [
                            'uuid' => (string) Str::uuid(),
                            'device_uuid' => $deviceUuid,
                            'table_name' => (string) $change['table_name'],
                            'record_uuid' => (string) $change['record_uuid'],
                            'type_conflit' => 'permission_refusee',
                            'donnees_locales_json' => null,
                            'donnees_entrantes_json' => json_encode($payload, JSON_UNESCAPED_UNICODE),
                            'resolution' => null,
                            'resolu_par' => null,
                            'resolu_at' => null,
                            'created_at' => $now,
                            'updated_at' => $now,
                        ]
                    );
                }
            }

            DB::table('sync_batches')->where('uuid', $batchUuid)->update([
                'statut' => $refused > 0 ? 'a_verifier' : 'recu',
                'total_appliques' => $applied,
                'total_conflits' => $refused,
                'resume_json' => json_encode([
                    'type' => 'lot_appareil_connecte',
                    'role' => $user->role,
                    'accepted' => $accepted,
                    'applied' => $applied,
                    'refused' => $refused,
                ], JSON_UNESCAPED_UNICODE),
                'termine_at' => $now,
                'updated_at' => $now,
            ]);

            DB::table('sync_devices')->where('id', $device->id)->update([
                'dernier_contact_at' => $now,
                'updated_at' => $now,
            ]);

            $this->pruneSyncHistory();
        });

        return response()->json([
            'success' => true,
            'message' => $refused > 0 ? 'Lot recu avec des elements a verifier.' : 'Lot recu avec succes.',
            'batch_uuid' => $batchUuid,
            'accepted' => $accepted,
            'applied' => $applied,
            'refused' => $refused,
            'accepted_record_uuids' => $acceptedRecordUuids,
            'refused_record_uuids' => $refusedRecordUuids,
            'next_bootstrap' => app(LocalSyncCatalog::class)->bootstrapForUser($user),
        ]);
    }

    private function applyConnectedChange(string $table, string $operation, string $recordUuid, array $payload, $now): bool
    {
        $table = trim($table);
        $recordUuid = trim($recordUuid);

        if ($table === '' || $recordUuid === '' || ! Schema::hasTable($table) || ! Schema::hasColumn($table, 'id')) {
            throw new \RuntimeException('Table ou identifiant local invalide.');
        }

        $incomingId = (int) ($payload['id'] ?? 0);
        $payload = $this->filterIncomingPayload($table, $payload, $now);

        if ($operation === 'create') {
            if (empty($payload)) {
                throw new \RuntimeException('Donnees entrantes vides.');
            }

            $existing = DB::table('sync_record_keys')->where('record_uuid', $recordUuid)->first();
            if ($existing && $existing->table_name === $table) {
                DB::table($table)->where('id', (int) $existing->record_id)->update($payload);
                return true;
            }
            if ($existing && $existing->table_name !== $table) {
                throw new \RuntimeException('Identifiant deja utilise par une autre table.');
            }

            $naturalRecordId = $this->findNaturalSyncRecord($table, $payload);
            if ($naturalRecordId) {
                DB::table($table)->where('id', $naturalRecordId)->update($payload);
                DB::table('sync_record_keys')->updateOrInsert(
                    ['record_uuid' => $recordUuid],
                    [
                        'table_name' => $table,
                        'record_id' => $naturalRecordId,
                        'checksum' => hash('sha256', json_encode($payload, JSON_UNESCAPED_UNICODE)),
                        'last_seen_at' => $now,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]
                );

                return true;
            }

            $id = DB::table($table)->insertGetId($payload);
            DB::table('sync_record_keys')->updateOrInsert(
                ['record_uuid' => $recordUuid],
                [
                    'table_name' => $table,
                    'record_id' => $id,
                    'checksum' => hash('sha256', json_encode($payload, JSON_UNESCAPED_UNICODE)),
                    'last_seen_at' => $now,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );

            return true;
        }

        $key = DB::table('sync_record_keys')
            ->where('record_uuid', $recordUuid)
            ->where('table_name', $table)
            ->first();

        if (! $key && $incomingId > 0 && DB::table($table)->where('id', $incomingId)->exists()) {
            DB::table('sync_record_keys')->updateOrInsert(
                ['record_uuid' => $recordUuid],
                [
                    'table_name' => $table,
                    'record_id' => $incomingId,
                    'checksum' => hash('sha256', json_encode($payload, JSON_UNESCAPED_UNICODE)),
                    'last_seen_at' => $now,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );
            $key = DB::table('sync_record_keys')
                ->where('record_uuid', $recordUuid)
                ->where('table_name', $table)
                ->first();
        }

        if (! $key) {
            throw new \RuntimeException('Enregistrement cible introuvable.');
        }

        if ($operation === 'update') {
            if (empty($payload)) {
                throw new \RuntimeException('Aucune donnee a mettre a jour.');
            }
            DB::table($table)->where('id', (int) $key->record_id)->update($payload);
            DB::table('sync_record_keys')->where('id', $key->id)->update([
                'checksum' => hash('sha256', json_encode($payload, JSON_UNESCAPED_UNICODE)),
                'last_seen_at' => $now,
                'updated_at' => $now,
            ]);
            return true;
        }

        if ($operation === 'delete') {
            DB::table($table)->where('id', (int) $key->record_id)->delete();
            DB::table('sync_record_keys')->where('id', $key->id)->delete();
            return true;
        }

        return false;
    }

    private function findNaturalSyncRecord(string $table, array $payload): ?int
    {
        $keysByTable = [
            'notes' => ['id_eleve', 'id_matiere', 'periode', 'annee_scolaire'],
            'remarques' => ['id_eleve', 'periode', 'annee_scolaire'],
            'examen_blanc' => ['eleve_id', 'matiere_id', 'session', 'annee_scolaire'],
            'remarques_examen_blanc' => ['id_eleve', 'session', 'annee_scolaire'],
            'presence_eleves' => ['eleve_id', 'date_jour', 'session_jour'],
        ];

        $keys = $keysByTable[$table] ?? null;
        if (! $keys) {
            return null;
        }

        foreach ($keys as $key) {
            if (! array_key_exists($key, $payload) || $payload[$key] === null || $payload[$key] === '') {
                return null;
            }
        }

        $query = DB::table($table);
        foreach ($keys as $key) {
            $query->where($key, $payload[$key]);
        }

        $id = $query->value('id');
        return $id ? (int) $id : null;
    }

    private function filterIncomingPayload(string $table, array $payload, $now): array
    {
        $columns = Schema::getColumnListing($table);
        $blocked = ['id'];
        $clean = [];

        if ($table === 'utilisateurs' && isset($payload['avatar_data']) && in_array('avatar', $columns, true)) {
            $clean['avatar'] = $this->storeIncomingDataImage((string) $payload['avatar_data'], public_path('legacy/uploads/avatars'), 'avatar_');
        }

        if ($table === 'ecole' && isset($payload['logo_data']) && in_array('logo', $columns, true)) {
            $clean['logo'] = $this->storeIncomingDataImage((string) $payload['logo_data'], public_path('legacy/images'), 'logo_');
        }

        if ($table === 'eleves' && isset($payload['photo_data']) && in_array('photo', $columns, true)) {
            $clean['photo'] = 'uploads/eleves/'.$this->storeIncomingDataImage((string) $payload['photo_data'], public_path('legacy/uploads/eleves'), 'eleve_');
        }

        foreach ($payload as $key => $value) {
            if (in_array($key, $blocked, true) || str_ends_with((string) $key, '_data') || ! in_array($key, $columns, true)) {
                continue;
            }
            if ($table === 'eleves' && $key === 'photo' && isset($clean['photo'])) {
                continue;
            }
            if ($table === 'utilisateurs' && $key === 'mot_de_passe') {
                if (blank($value)) {
                    continue;
                }
                $value = Hash::make((string) $value);
            }
            $clean[$key] = $value;
        }

        if (in_array('created_at', $columns, true) && empty($clean['created_at'])) {
            $clean['created_at'] = $now;
        }
        if (in_array('updated_at', $columns, true) && empty($clean['updated_at'])) {
            $clean['updated_at'] = $now;
        }

        return $clean;
    }

    private function storeIncomingDataImage(string $dataUrl, string $directory, string $prefix): string
    {
        if (! preg_match('/^data:image\/(png|jpe?g|gif|webp);base64,(.+)$/i', $dataUrl, $matches)) {
            throw new \RuntimeException('Image entrante invalide.');
        }

        $extension = strtolower($matches[1]) === 'jpeg' ? 'jpg' : strtolower($matches[1]);
        $binary = base64_decode($matches[2], true);
        if ($binary === false || strlen($binary) > 5 * 1024 * 1024) {
            throw new \RuntimeException('Image entrante trop lourde ou illisible.');
        }

        File::ensureDirectoryExists($directory);
        $name = $prefix.time().'_'.Str::random(10).'.'.$extension;
        File::put($directory.DIRECTORY_SEPARATOR.$name, $binary);

        return $name;
    }

    private function normalizePairingCode(string $code): string
    {
        return strtoupper((string) preg_replace('/[^A-Za-z0-9]/', '', $code));
    }

    private function shortDevicePlatform(string $platform): ?string
    {
        $platform = trim(preg_replace('/\s+/', ' ', $platform) ?? '');
        if ($platform === '') {
            return null;
        }

        return Str::limit($platform, 78, '');
    }

    private function pruneSyncHistory(): void
    {
        if (! Schema::hasTable('sync_batches') || ! Schema::hasTable('sync_changes')) {
            return;
        }

        $keepBatchIds = DB::table('sync_batches')
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->limit(150)
            ->pluck('id')
            ->map(fn ($id) => (int) $id)
            ->all();

        if (empty($keepBatchIds)) {
            return;
        }

        $oldBatchUuids = DB::table('sync_batches')
            ->whereNotIn('id', $keepBatchIds)
            ->pluck('uuid')
            ->filter()
            ->values()
            ->all();

        if (! empty($oldBatchUuids)) {
            $conflictChanges = Schema::hasTable('sync_conflicts')
                ? DB::table('sync_conflicts')->whereNull('resolution')->pluck('change_uuid')->filter()->values()->all()
                : [];

            DB::table('sync_changes')
                ->whereIn('batch_uuid', $oldBatchUuids)
                ->when(! empty($conflictChanges), fn ($query) => $query->whereNotIn('uuid', $conflictChanges))
                ->delete();

            DB::table('sync_batches')->whereNotIn('id', $keepBatchIds)->delete();
        }

        if (Schema::hasTable('sync_conflicts')) {
            DB::table('sync_conflicts')
                ->whereNotNull('resolution')
                ->where('updated_at', '<', now()->subDays(90))
                ->delete();
        }
    }

    public function createBackup()
    {
        $this->ensureSession();
        File::ensureDirectoryExists($this->backupDir());

        $name = 'backup_'.date('Ymd_His').'.sql';
        $path = $this->backupDir().DIRECTORY_SEPARATOR.$name;
        File::put($path, $this->databaseDump());

        if (DB::getSchemaBuilder()->hasTable('notifications')) {
            $columns = DB::getSchemaBuilder()->getColumnListing('notifications');
            $notification = [
                'type' => 'sauvegarde',
                'message' => 'Nouvelle sauvegarde : '.$name,
                'destinataire_id' => null,
                'date_creation' => now(),
                'statut' => 'non lu',
                'user_type' => session('utilisateur.role', 'admin'),
                'user_id' => session('utilisateur.id', 0),
                'titre' => 'sauvegarde',
            ];
            if (in_array('date_envoi', $columns, true)) {
                $notification['date_envoi'] = now();
            }
            if (in_array('lu', $columns, true)) {
                $notification['lu'] = 0;
            }
            DB::table('notifications')->insert(array_intersect_key($notification, array_flip($columns)));
        }

        return redirect()->route('modules.sauvegardes')->with('success', 'Sauvegarde creee : '.$name);
    }

    public function downloadBackup(string $file): BinaryFileResponse
    {
        $this->ensureSession();
        $path = $this->backupPath($file);
        abort_unless(File::exists($path), 404);

        return response()->download($path);
    }

    public function deleteBackup(string $file)
    {
        $this->ensureSession();
        $path = $this->backupPath($file);
        abort_unless(File::exists($path), 404);
        File::delete($path);

        return redirect()->route('modules.sauvegardes')->with('success', 'Sauvegarde supprimee avec succes.');
    }

    public function restoreBackup(string $file)
    {
        $this->ensureSession();
        $path = $this->backupPath($file);
        abort_unless(File::exists($path), 404);

        File::ensureDirectoryExists($this->backupDir());

        $currentSnapshot = 'auto_before_restore_'.date('Ymd_His').'.sql';
        File::put($this->backupDir().DIRECTORY_SEPARATOR.$currentSnapshot, $this->databaseDump());

        $sql = File::get($path);
        $statements = $this->splitSqlStatements($sql);

        try {
            DB::disconnect();

            foreach ($statements as $statement) {
                $trimmed = trim($statement);
                if ($trimmed === '') {
                    continue;
                }

                DB::unprepared($trimmed);
            }

            DB::purge();
            DB::reconnect();

            $this->insertNotification('restauration', 'Base restauree depuis : '.basename($file));

            return redirect()->route('modules.sauvegardes')->with('success', 'Restauration terminee : '.basename($file).'. Une sauvegarde automatique de l etat precedent a ete creee : '.$currentSnapshot);
        } catch (\Throwable $e) {
            DB::purge();
            DB::reconnect();

            return redirect()->route('modules.sauvegardes')->with('error', 'Restauration echouee : '.$e->getMessage());
        }
    }

    private function databaseDump(): string
    {
        $dump = "-- Sauvegarde Novaskol\n-- Date: ".date('Y-m-d H:i:s')."\n\n";
        foreach (DB::select("SELECT name FROM sqlite_master WHERE type='table' ORDER BY name") as $row) {
            $table = $row->name;
            if ($table === 'sqlite_sequence') continue;
            $create = DB::selectOne("SELECT sql FROM sqlite_master WHERE name='$table' AND type='table'");
            $dump .= "\nDROP TABLE IF EXISTS \"$table\";\n";
            if ($create && $create->sql) $dump .= $create->sql.";\n\n";
            foreach (DB::table($table)->get() as $record) {
                $values = collect((array) $record)->map(fn ($value) => $value === null ? 'NULL' : DB::getPdo()->quote((string) $value))->implode(',');
                $dump .= "INSERT INTO \"$table\" VALUES ($values);\n";
            }
            $dump .= "\n";
        }
        return $dump;
    }

    private function splitSqlStatements(string $sql): array
    {
        $statements = [];
        $buffer = '';
        $inSingle = false;
        $inDouble = false;
        $length = strlen($sql);

        for ($i = 0; $i < $length; $i++) {
            $char = $sql[$i];
            $next = $i + 1 < $length ? $sql[$i + 1] : null;

            if (! $inSingle && ! $inDouble && $char === '-' && $next === '-') {
                while ($i < $length && $sql[$i] !== "\n") {
                    $i++;
                }
                continue;
            }

            if (! $inSingle && ! $inDouble && $char === '#') {
                while ($i < $length && $sql[$i] !== "\n") {
                    $i++;
                }
                continue;
            }

            if ($char === "'" && ! $inDouble) {
                $escaped = $i > 0 && $sql[$i - 1] === '\\';
                if (! $escaped) {
                    $inSingle = ! $inSingle;
                }
            } elseif ($char === '"' && ! $inSingle) {
                $escaped = $i > 0 && $sql[$i - 1] === '\\';
                if (! $escaped) {
                    $inDouble = ! $inDouble;
                }
            }

            if ($char === ';' && ! $inSingle && ! $inDouble) {
                $buffer .= $char;
                $statements[] = $buffer;
                $buffer = '';
                continue;
            }

            $buffer .= $char;
        }

        if (trim($buffer) !== '') {
            $statements[] = $buffer;
        }

        return $statements;
    }

    private function ensureSyncTables(): void
    {
        if (! Schema::hasTable('sync_devices')) {
            Schema::create('sync_devices', function (Blueprint $table) {
                $table->id();
                $table->string('uuid', 64)->unique();
                $table->string('nom', 160);
                $table->string('type_appareil', 40)->default('pc');
                $table->string('role_sync', 40)->default('appareil_connecte');
                $table->string('plateforme', 80)->nullable();
                $table->string('adresse_ip', 80)->nullable();
                $table->string('code_appairage', 20)->nullable()->index();
                $table->boolean('autorise')->default(false);
                $table->unsignedBigInteger('created_by')->nullable();
                $table->timestamp('dernier_contact_at')->nullable();
                $table->timestamps();
            });
        }
        if (Schema::hasTable('sync_devices')) {
            foreach ([
                'utilisateur_id' => fn (Blueprint $table) => $table->unsignedBigInteger('utilisateur_id')->nullable()->index()->after('created_by'),
                'utilisateur_role' => fn (Blueprint $table) => $table->string('utilisateur_role', 40)->nullable()->index()->after('utilisateur_id'),
                'paired_at' => fn (Blueprint $table) => $table->timestamp('paired_at')->nullable()->after('utilisateur_role'),
                'last_bootstrap_at' => fn (Blueprint $table) => $table->timestamp('last_bootstrap_at')->nullable()->after('dernier_contact_at'),
            ] as $column => $definition) {
                if (! Schema::hasColumn('sync_devices', $column)) {
                    Schema::table('sync_devices', $definition);
                }
            }
        }

        if (! Schema::hasTable('sync_batches')) {
            Schema::create('sync_batches', function (Blueprint $table) {
                $table->id();
                $table->string('uuid', 64)->unique();
                $table->string('device_uuid', 64)->index();
                $table->string('direction', 20)->default('push');
                $table->string('statut', 30)->default('en_attente')->index();
                $table->unsignedInteger('total_changements')->default(0);
                $table->unsignedInteger('total_appliques')->default(0);
                $table->unsignedInteger('total_conflits')->default(0);
                $table->longText('resume_json')->nullable();
                $table->text('message_erreur')->nullable();
                $table->timestamp('demarre_at')->nullable();
                $table->timestamp('termine_at')->nullable();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('sync_changes')) {
            Schema::create('sync_changes', function (Blueprint $table) {
                $table->id();
                $table->string('uuid', 64)->unique();
                $table->string('batch_uuid', 64)->nullable()->index();
                $table->string('device_uuid', 64)->index();
                $table->unsignedBigInteger('utilisateur_id')->nullable()->index();
                $table->string('module', 80)->nullable()->index();
                $table->string('table_name', 100)->index();
                $table->string('record_uuid', 64)->index();
                $table->string('operation', 20)->index();
                $table->longText('payload_json')->nullable();
                $table->string('checksum', 128)->nullable();
                $table->string('statut', 30)->default('en_attente')->index();
                $table->text('message_erreur')->nullable();
                $table->timestamp('action_at')->nullable()->index();
                $table->timestamp('applique_at')->nullable();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('sync_conflicts')) {
            Schema::create('sync_conflicts', function (Blueprint $table) {
                $table->id();
                $table->string('uuid', 64)->unique();
                $table->string('change_uuid', 64)->nullable()->index();
                $table->string('device_uuid', 64)->index();
                $table->string('table_name', 100)->index();
                $table->string('record_uuid', 64)->index();
                $table->string('type_conflit', 80)->default('modification_concurrente');
                $table->longText('donnees_locales_json')->nullable();
                $table->longText('donnees_entrantes_json')->nullable();
                $table->string('resolution', 40)->nullable();
                $table->unsignedBigInteger('resolu_par')->nullable();
                $table->timestamp('resolu_at')->nullable();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('sync_record_keys')) {
            Schema::create('sync_record_keys', function (Blueprint $table) {
                $table->id();
                $table->string('table_name', 100)->index();
                $table->unsignedBigInteger('record_id')->index();
                $table->string('record_uuid', 64)->unique();
                $table->string('checksum', 128)->nullable();
                $table->timestamp('last_seen_at')->nullable();
                $table->timestamps();
                $table->unique(['table_name', 'record_id']);
            });
        }
    }

    private function ensureCurrentSyncDevice(string $deviceName, string $hostname, ?string $localIp): object
    {
        $uuid = (string) DB::table('parametres')->where('cle', 'sync_device_uuid')->value('valeur');
        if ($uuid === '') {
            $uuid = (string) Str::uuid();
            DB::table('parametres')->updateOrInsert(['cle' => 'sync_device_uuid'], ['valeur' => $uuid]);
        }

        DB::table('sync_devices')->updateOrInsert(
            ['uuid' => $uuid],
            [
                'nom' => $deviceName,
                'type_appareil' => 'pc',
                'role_sync' => 'appareil_principal',
                'plateforme' => 'Windows / '.$hostname,
                'adresse_ip' => $localIp,
                'autorise' => true,
                'created_by' => session('utilisateur.id'),
                'dernier_contact_at' => now(),
                'updated_at' => now(),
                'created_at' => now(),
            ]
        );

        return DB::table('sync_devices')->where('uuid', $uuid)->first();
    }

    private function params(): array
    {
        $defaults = [
            'mention_passable' => '10',
            'mention_assez_bien' => '12',
            'mention_bien' => '14',
            'mention_tres_bien' => '16',
            'notifications_mail' => '1',
            'logo_ecole' => '',
            'devise_nom' => 'Ariary',
            'devise_symbole' => 'Ar',
            'langue_interface' => 'fr',
            'appareil_principal_nom' => '',
            'sync_device_uuid' => '',
            'sync_pairing_code' => '',
            'sync_pairing_expires_at' => '',
        ];

        return array_replace($defaults, DB::table('parametres')->pluck('valeur', 'cle')->all());
    }

    private function applicationPort(): int
    {
        $port = parse_url((string) config('app.url', ''), PHP_URL_PORT);

        return $port ? (int) $port : 8001;
    }

    private function localIpv4(): ?string
    {
        if (function_exists('net_get_interfaces')) {
            try {
                $interfaces = net_get_interfaces();
                foreach ($interfaces as $interface) {
                    foreach (($interface['unicast'] ?? []) as $address) {
                        $ip = $address['address'] ?? null;
                        if (! is_string($ip) || ! filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
                            continue;
                        }
                        if ($this->isPrivateIpv4($ip)) {
                            return $ip;
                        }
                    }
                }
            } catch (\Throwable) {
                // Fallback below.
            }
        }

        $fallback = gethostbyname(gethostname() ?: '');
        if (is_string($fallback) && $this->isPrivateIpv4($fallback)) {
            return $fallback;
        }

        return null;
    }

    private function isPrivateIpv4(string $ip): bool
    {
        if ($ip === '127.0.0.1') {
            return false;
        }

        return str_starts_with($ip, '10.')
            || str_starts_with($ip, '192.168.')
            || preg_match('/^172\.(1[6-9]|2\d|3[0-1])\./', $ip) === 1;
    }

    private function isTcpReachable(string $host, int $port): bool
    {
        try {
            $socket = @fsockopen($host, $port, $errno, $errstr, 1.5);
            if ($socket) {
                fclose($socket);

                return true;
            }
        } catch (\Throwable) {
            return false;
        }

        return false;
    }

    private function firewallStatus(int $port): array
    {
        $script = base_path('tools/windows/Autoriser-Reseau-Novaskol.cmd');
        $status = [
            'windows' => PHP_OS_FAMILY === 'Windows',
            'configured' => false,
            'rule' => 'Novaskol-Local-'.$port,
            'script' => $script,
            'command' => $script,
            'message' => 'Sur Windows, autorisez Novaskol dans le pare-feu pour les telephones et PC du meme Wi-Fi.',
        ];

        if (! $status['windows']) {
            $status['message'] = 'Le pare-feu automatique concerne uniquement Windows.';

            return $status;
        }

        try {
            $rule = @shell_exec('netsh advfirewall firewall show rule name="'.$status['rule'].'" 2>NUL');
            if (is_string($rule) && str_contains($rule, $status['rule'])) {
                $status['configured'] = str_contains(strtolower($rule), 'enabled') || str_contains(strtolower($rule), 'active');
                $status['message'] = 'Regle pare-feu Novaskol detectee pour le port '.$port.'.';
            }
        } catch (\Throwable) {
            // Windows or hosting may disable shell access. The manual script remains available.
        }

        if (! file_exists($script)) {
            $status['message'] = 'Script pare-feu introuvable. Reinstallez ou mettez a jour Novaskol.';
        }

        return $status;
    }

    private function backupDir(): string
    {
        return public_path('backups');
    }

    private function backupPath(string $file): string
    {
        return $this->backupDir().DIRECTORY_SEPARATOR.basename($file);
    }

    private function insertNotification(string $type, string $message): void
    {
        if (! DB::getSchemaBuilder()->hasTable('notifications')) {
            return;
        }

        $columns = DB::getSchemaBuilder()->getColumnListing('notifications');
        $notification = [
            'type' => Str::limit($type, 50, ''),
            'message' => $message,
            'destinataire_id' => null,
            'date_creation' => now(),
            'statut' => 'non lu',
        ];

        if (in_array('date_envoi', $columns, true)) {
            $notification['date_envoi'] = now();
        }
        if (in_array('lu', $columns, true)) {
            $notification['lu'] = 0;
        }
        if (in_array('user_type', $columns, true)) {
            $notification['user_type'] = session('utilisateur.role', 'admin');
        }
        if (in_array('user_id', $columns, true)) {
            $notification['user_id'] = session('utilisateur.id', 0);
        }
        if (in_array('titre', $columns, true)) {
            $notification['titre'] = $type;
        }

        DB::table('notifications')->insert(array_intersect_key($notification, array_flip($columns)));
    }

    private function defaultConnectedDeviceName(string $type, string $userName): string
    {
        $label = match ($type) {
            'telephone' => 'Telephone',
            'tablette' => 'Tablette',
            'pc' => 'PC',
            default => 'Appareil',
        };

        return trim($label.' - '.$userName);
    }

    private function view(string $name, ModuleRegistry $modules, string $activeModule, array $data = [])
    {
        return view($name, $data + [
            'modules' => $modules->all(),
            'userPermissions' => $this->userPermissions(),
            'ecole' => $this->school(),
            'activeModule' => $activeModule,
        ]);
    }

    private function ensureSession(): void
    {
        abort_unless(session()->has('utilisateur') && in_array(session('utilisateur.role'), ['admin', 'staff', 'enseignant'], true), 403);
    }

    private function userPermissions(): array
    {
        $id = (int) session('utilisateur.id', 0);
        return $id ? DB::table('permissions')->where('utilisateur_id', $id)->pluck('acces', 'module')->all() : [];
    }

    private function school(): object
    {
        return DB::table('ecole')->select('id', 'nom', 'logo')->first() ?: (object) ['id' => 1, 'nom' => 'Ecole', 'logo' => 'novaskol.png'];
    }
}
