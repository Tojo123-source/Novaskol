<?php

namespace App\Http\Controllers\Connected;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use App\Services\ConnectedBootstrapImporter;

class ConnectedInitController extends Controller
{
    public function __invoke(Request $request, ConnectedBootstrapImporter $importer)
    {
        try {
            return $this->handleInit($request, $importer);
        } catch (\Throwable $e) {
            logger()->error('ConnectedInitController: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Erreur interne: ' . $e->getMessage()], 500);
            }
            return response()->view('errors.500', ['message' => $e->getMessage()], 500);
        }
    }

    private function handleInit(Request $request, ConnectedBootstrapImporter $importer)
    {
        $pairedPath = env('CONNECTED_PAIRED_PATH', storage_path('app/connected/paired.json'));

        if (!File::exists($pairedPath)) {
            return $this->notPaired($request);
        }

        $paired = json_decode(File::get($pairedPath), true);
        if (!$paired || !isset($paired['user']['email'])) {
            return $this->notPaired($request);
        }

        $this->ensureCoreTables();
        $bootstrapChecksum = isset($paired['bootstrap']) && is_array($paired['bootstrap'])
            ? $importer->checksum($paired['bootstrap'])
            : null;
        $lastBootstrapChecksum = Schema::hasTable('parametres')
            ? (string) DB::table('parametres')->where('cle', 'connected_bootstrap_checksum')->value('valeur')
            : '';
        $shouldImportBootstrap = $bootstrapChecksum && $bootstrapChecksum !== $lastBootstrapChecksum;
        $importResult = $shouldImportBootstrap
            ? $importer->import($paired)
            : ['imported_tables' => 0, 'imported_records' => 0, 'skipped' => true, 'bootstrap_checksum' => $bootstrapChecksum];

        DB::transaction(function () use ($paired, $request, $importResult, $shouldImportBootstrap) {
            if (isset($paired['school'])) {
                DB::table('ecole')->updateOrInsert(
                    ['id' => $paired['school']['id'] ?? 1],
                    [
                        'nom' => $paired['school']['nom'] ?? 'Ecole',
                        'logo' => $paired['school']['logo'] ?? 'novaskol.png',
                    ]
                );
            }

            $userInfo = $paired['user'];
            $authToken = $paired['auth_token'] ?? $userInfo['email'] ?? '';
            $password = Hash::make($authToken);

            $userData = [
                'nom' => $userInfo['nom'] ?? ($userInfo['email'] ?? 'Utilisateur'),
                'mot_de_passe' => $password,
                'role' => $userInfo['role'] ?? 'admin',
                'avatar' => $userInfo['avatar'] ?? 'images/default-avatar.png',
            ];

            $email = $userInfo['email'];
            $role = $userInfo['role'] ?? 'admin';

            $existing = DB::table('utilisateurs')
                ->where('email', $email)
                ->where('role', $role)
                ->first();

            if ($existing) {
                DB::table('utilisateurs')
                    ->where('id', $existing->id)
                    ->update($userData);
                $userId = $existing->id;
            } else {
                $userId = DB::table('utilisateurs')->insertGetId(
                    ['email' => $email] + $userData
                );
            }

            if ($role === 'admin') {
                $this->grantAdminPermissions($userId);
            } elseif ($shouldImportBootstrap && isset($paired['permissions'])) {
                foreach ($paired['permissions'] as $module => $acces) {
                    DB::table('permissions')->updateOrInsert(
                        ['utilisateur_id' => $userId, 'module' => $module],
                        ['role' => $role, 'acces' => $acces]
                    );
                }
            }

            if (isset($paired['school']['nom'])) {
                DB::table('parametres')->updateOrInsert(
                    ['cle' => 'nom_ecole'],
                    ['valeur' => $paired['school']['nom']]
                );
            }
            DB::table('parametres')->updateOrInsert(
                ['cle' => 'mode_installation'],
                ['valeur' => 'production']
            );
            DB::table('parametres')->updateOrInsert(
                ['cle' => 'novaskol_version'],
                ['valeur' => config('app.version', '1.0.0')]
            );
            DB::table('parametres')->updateOrInsert(
                ['cle' => 'connected_last_import_at'],
                ['valeur' => now()->toDateTimeString()]
            );
            DB::table('parametres')->updateOrInsert(
                ['cle' => 'connected_last_import_summary'],
                ['valeur' => json_encode($importResult, JSON_UNESCAPED_UNICODE)]
            );

            File::ensureDirectoryExists(storage_path('app'));
            File::put(storage_path('app/novaskol-installed.lock'), now()->toDateTimeString());

            $request->session()->put('utilisateur', [
                'id' => $userId,
                'nom' => $userInfo['nom'] ?? $email,
                'email' => $email,
                'role' => $role,
            ]);
        });

        $role = $paired['user']['role'] ?? 'admin';
        $redirectUrl = $role === 'admin' ? '/dashboard' : '/mon-espace';

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'redirect' => $redirectUrl]);
        }

        return redirect($redirectUrl);
    }

    private function notPaired(Request $request)
    {
        if ($request->expectsJson()) {
            return response()->json(['success' => false, 'message' => 'Non appaire'], 401);
        }

        return redirect()->route('login');
    }

    private function ensureCoreTables(): void
    {
        if (!Schema::hasTable('ecole')) {
            Schema::create('ecole', function (Blueprint $table) {
                $table->id();
                $table->string('nom', 150)->nullable();
                $table->string('logo', 200)->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('parametres')) {
            Schema::create('parametres', function (Blueprint $table) {
                $table->string('cle', 100)->primary();
                $table->text('valeur')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('utilisateurs')) {
            Schema::create('utilisateurs', function (Blueprint $table) {
                $table->id();
                $table->string('nom', 120);
                $table->string('email', 120);
                $table->string('mot_de_passe', 255);
                $table->string('role', 40)->index();
                $table->string('avatar', 200)->nullable();
                $table->timestamps();
                $table->dateTime('last_activity')->nullable();
                $table->unique(['email', 'role']);
            });
        } elseif (!Schema::hasColumn('utilisateurs', 'last_activity')) {
            Schema::table('utilisateurs', function (Blueprint $table) {
                $table->dateTime('last_activity')->nullable();
            });
        }

        if (!Schema::hasTable('permissions')) {
            Schema::create('permissions', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('utilisateur_id')->index();
                $table->string('module', 80)->index();
                $table->string('role', 40)->nullable();
                $table->string('acces', 40)->nullable();
                $table->unique(['utilisateur_id', 'module']);
                $table->timestamps();
            });
        }
    }

    public function disconnect(Request $request)
    {
        try {
            $pairedPath = env('CONNECTED_PAIRED_PATH', storage_path('app/connected/paired.json'));
            if (File::exists($pairedPath)) {
                File::delete($pairedPath);
            }
            $request->session()->flush();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return response()->json(['success' => true, 'message' => 'Deconnecte.']);
        } catch (\Throwable $e) {
            logger()->error('ConnectedDisconnect: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function switchUser(Request $request)
    {
        try {
            $pairedPath = env('CONNECTED_PAIRED_PATH', storage_path('app/connected/paired.json'));
            if (!File::exists($pairedPath)) {
                return response()->json(['success' => false, 'message' => 'Aucun appairage trouve.'], 400);
            }

            $paired = json_decode(File::get($pairedPath), true);
            $serverUrl = $paired['server_url'] ?? '';

            if (!$serverUrl) {
                return response()->json(['success' => false, 'message' => 'URL du serveur principal introuvable.'], 400);
            }

            $data = $request->validate([
                'email' => ['required', 'email'],
                'password' => ['required', 'string', 'min:1'],
                'role' => ['nullable', 'string', 'max:40'],
            ]);

            $email = $data['email'];
            $password = $data['password'];
            $targetRole = $data['role'] ?? null;

            // If it's the SAME email as paired, just reload (user already logged in)
            if (($paired['user']['email'] ?? '') === $email && (!$targetRole || ($paired['user']['role'] ?? '') === $targetRole)) {
                $request->session()->flush();
                return response()->json(['success' => true, 'message' => 'Meme utilisateur, reinitialisation.']);
            }

            // Authenticate against the local database
            $q = DB::table('utilisateurs')->where('email', $email);
            if ($targetRole) {
                $q->where('role', $targetRole);
            }
            $user = $q->first();

            if (!$user || !Hash::check($password, $user->mot_de_passe)) {
                // Try authenticating against the principal server
                try {
                    $loginResponse = \Illuminate\Support\Facades\Http::timeout(15)
                        ->asForm()
                        ->post(rtrim($serverUrl, '/') . '/login', [
                            'email' => $email,
                            'password' => $password,
                        ]);

                    if ($loginResponse->successful() && $loginResponse->json('success')) {
                        $loginData = $loginResponse->json();
                    } else {
                        return response()->json(['success' => false, 'message' => 'Email ou mot de passe incorrect.'], 401);
                    }
                } catch (\Throwable $httpError) {
                    return response()->json(['success' => false, 'message' => 'Impossible de contacter le serveur principal: ' . $httpError->getMessage()], 502);
                }

                // Get user info from the login response
                $userData = $loginData['user'] ?? [];

                // Fetch fresh bootstrap from principal
                $deviceUuid = $paired['device']['uuid'] ?? $paired['sync']['device_uuid'] ?? '';
                if ($deviceUuid) {
                    $bootstrapResponse = \Illuminate\Support\Facades\Http::timeout(30)
                        ->acceptJson()
                        ->post(rtrim($serverUrl, '/') . '/reseau-local/bootstrap-appareil', [
                            'device_uuid' => $deviceUuid,
                        ]);

                    if ($bootstrapResponse->successful()) {
                        $bootstrapData = $bootstrapResponse->json();

                        $paired['user'] = $bootstrapData['user'] ?? $userData;
                        $paired['permissions'] = $bootstrapData['permissions'] ?? [];
                        $paired['bootstrap'] = $bootstrapData['bootstrap'] ?? [];
                        $paired['sync'] = $bootstrapData['sync'] ?? $paired['sync'] ?? [];
                        File::put($pairedPath, json_encode($paired, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));

                        // Import the new bootstrap
                        $importer = app(ConnectedBootstrapImporter::class);
                        $importer->import($paired);
                    } else {
                        return response()->json(['success' => false, 'message' => 'Echec recuperation des donnees du nouvel utilisateur.'], 502);
                    }
                }
            } else {
                // User found locally, just switch
                $paired['user'] = [
                    'id' => (int) $user->id,
                    'nom' => $user->nom,
                    'email' => $user->email,
                    'role' => $user->role,
                    'avatar' => $user->avatar ?? 'images/default-avatar.png',
                ];
                $freshPermissions = DB::table('permissions')
                    ->where('utilisateur_id', (int) $user->id)
                    ->pluck('acces', 'module')
                    ->all();
                $paired['permissions'] = $freshPermissions;
                File::put($pairedPath, json_encode($paired, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
            }

            $request->session()->flush();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return response()->json(['success' => true, 'message' => 'Utilisateur change avec succes.']);
        } catch (\Throwable $e) {
            logger()->error('ConnectedSwitchUser: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    private function grantAdminPermissions(int $userId): void
    {
        foreach (array_keys(config('novaskol.modules', [])) as $module) {
            DB::table('permissions')->updateOrInsert(
                ['utilisateur_id' => $userId, 'module' => $module],
                ['role' => 'admin', 'acces' => 'ecriture']
            );
        }
    }
}
