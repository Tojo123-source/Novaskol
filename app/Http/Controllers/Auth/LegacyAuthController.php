<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\QrCodeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;

class LegacyAuthController extends Controller
{
    public function show()
    {
        if (config('novaskol.edition', 'principal') === 'connecte') {
            return redirect()->route('connected.setup');
        }

        if (! $this->isInstalled()) {
            return redirect()->route('installation.show');
        }

        return view('auth.index');
    }

    public function login(Request $request)
    {
        if (config('novaskol.edition', 'principal') === 'connecte') {
            return redirect()->route('connected.setup');
        }

        if (! $this->isInstalled()) {
            return redirect()->route('installation.show');
        }

        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
            'role' => ['required', 'in:admin,enseignant,staff,parent'],
        ]);

        $user = DB::table('utilisateurs')
            ->where('email', $credentials['email'])
            ->where('role', $credentials['role'])
            ->first();

        if (! $user || ! Hash::check($credentials['password'], $user->mot_de_passe)) {
            return back()
                ->withErrors(['email' => 'Email, role ou mot de passe incorrect.'])
                ->onlyInput('email', 'role');
        }

        $request->session()->put('utilisateur', [
            'id' => $user->id,
            'nom' => $user->nom,
            'email' => $user->email,
            'role' => $user->role,
        ]);

        if ($user->role === 'admin') {
            $this->grantAdminPermissions((int) $user->id);
        }

        return redirect()->route($user->role === 'admin' ? 'dashboard' : 'role.dashboard');
    }

    public function register(Request $request)
    {
        if (config('novaskol.edition', 'principal') === 'connecte') {
            return redirect()->route('connected.setup');
        }

        if (! $this->isInstalled()) {
            return redirect()->route('installation.show');
        }

        $data = $request->validate([
            'nom' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'password' => ['required', 'string'],
            'password_confirm' => ['required', 'same:password'],
            'role' => ['required', 'in:admin,enseignant,staff,parent'],
        ], [
            'password_confirm.same' => 'Les mots de passe ne correspondent pas.',
        ]);

        $exists = DB::table('utilisateurs')->where('email', $data['email'])->exists();

        if ($exists) {
            return back()
                ->withErrors(['register' => 'Email deja utilise.'])
                ->withInput($request->except('password', 'password_confirm'))
                ->with('auth_box', 'register');
        }

        $userId = DB::table('utilisateurs')->insertGetId([
            'nom' => $data['nom'],
            'email' => $data['email'],
            'mot_de_passe' => Hash::make($data['password']),
            'role' => $data['role'],
            'qr_token' => app(QrCodeService::class)->generateUniqueToken(),
        ]);

        if ($data['role'] === 'admin') {
            $this->grantAdminPermissions((int) $userId);
        }

        return redirect()->route('login', ['inscription' => 'ok']);
    }

    public function logout(Request $request)
    {
        $request->session()->forget('utilisateur');
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    private function grantAdminPermissions(int $userId): void
    {
        foreach (array_keys(config('novaskol.modules')) as $module) {
            DB::table('permissions')->updateOrInsert(
                ['utilisateur_id' => $userId, 'module' => $module],
                ['role' => 'admin', 'acces' => 'ecriture']
            );
        }
    }

    private function isInstalled(): bool
    {
        return File::exists(storage_path('app/novaskol-installed.lock'));
    }
}
