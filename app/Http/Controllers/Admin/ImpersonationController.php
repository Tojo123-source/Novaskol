<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ImpersonationController extends Controller
{
    public function index()
    {
        $enseignants = DB::table('utilisateurs')->where('role', 'enseignant')->select('id', 'nom', 'email')->get();
        $eleves = DB::table('eleves')->select('id', 'nom', 'prenom', 'email')->limit(100)->get();
        $parents = DB::table('parents')->select('id', 'nom', 'prenom', 'email')->limit(100)->get();
        $staff = DB::table('staff')->select('id', 'nom', 'prenom', 'email')->limit(100)->get();

        return view('admin.impersonate', compact('enseignants', 'eleves', 'parents', 'staff'));
    }

    public function loginAs(Request $request)
    {
        $data = $request->validate([
            'type' => 'required|in:utilisateur,eleve,parent,staff',
            'id' => 'required|integer',
        ]);

        $admin = session('utilisateur');

        if ($data['type'] === 'utilisateur') {
            $target = DB::table('utilisateurs')->where('id', $data['id'])->firstOrFail();
            $userData = [
                'id' => (int) $target->id,
                'nom' => $target->nom,
                'email' => $target->email,
                'role' => $target->role,
            ];
        } elseif ($data['type'] === 'eleve') {
            $target = DB::table('eleves')->where('id', $data['id'])->firstOrFail();
            $userData = [
                'id' => (int) $target->id,
                'nom' => trim(($target->prenom ?? '') . ' ' . ($target->nom ?? '')),
                'email' => $target->email ?? 'eleve' . $target->id . '@novaskol.local',
                'role' => 'eleve',
            ];
        } elseif ($data['type'] === 'parent') {
            $target = DB::table('parents')->where('id', $data['id'])->firstOrFail();
            $userData = [
                'id' => (int) $target->id,
                'nom' => trim(($target->prenom ?? '') . ' ' . ($target->nom ?? '')),
                'email' => $target->email ?? 'parent' . $target->id . '@novaskol.local',
                'role' => 'parent',
            ];
        } else {
            $target = DB::table('staff')->where('id', $data['id'])->firstOrFail();
            $userData = [
                'id' => (int) $target->id,
                'nom' => trim(($target->prenom ?? '') . ' ' . ($target->nom ?? '')),
                'email' => $target->email ?? 'staff' . $target->id . '@novaskol.local',
                'role' => 'staff',
            ];
        }

        session()->put('impersonator', $admin);
        session()->put('impersonating', true);
        session()->put('utilisateur', $userData);

        ActivityLogger::log('impersonation.login', 'admin', $data['type'], $data['id'], [
            'admin' => $admin['nom'] ?? '',
            'target' => $userData['nom'] ?? '',
        ]);

        return redirect()->route('dashboard')->with('success', 'Connecte en tant que ' . $userData['nom']);
    }

    public function leave()
    {
        if (!session('impersonating') || !session('impersonator')) {
            return redirect()->route('dashboard');
        }

        $admin = session('impersonator');
        session()->forget('impersonator');
        session()->forget('impersonating');
        session()->put('utilisateur', $admin);

        ActivityLogger::log('impersonation.leave', 'admin', null, null, ['admin' => $admin['nom'] ?? '']);
        return redirect()->route('dashboard')->with('success', 'Retour au compte admin.');
    }
}
