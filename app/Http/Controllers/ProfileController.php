<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function update(Request $request)
    {
        $this->ensureSession();
        $userId = (int) session('utilisateur.id');
        $oldEmail = (string) session('utilisateur.email');

        $data = $request->validate([
            'nom' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:100', 'unique:utilisateurs,email,'.$userId],
            'avatar' => ['nullable', 'image', 'mimes:jpg,jpeg,png,gif,webp', 'max:2048'],
        ]);

        $updates = [
            'nom' => trim($data['nom']),
            'email' => trim($data['email']),
        ];

        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $avatar = time().'_'.uniqid().'.'.$file->getClientOriginalExtension();
            $destination = public_path('legacy/uploads/avatars');
            File::ensureDirectoryExists($destination);
            $file->move($destination, $avatar);
            $updates['avatar'] = $avatar;
        }

        DB::table('utilisateurs')->where('id', $userId)->update($updates);
        session(['utilisateur.nom' => $updates['nom'], 'utilisateur.email' => $updates['email']]);
        if (! empty($updates['avatar'])) {
            session(['utilisateur.avatar' => $updates['avatar']]);
        }

        $role = (string) session('utilisateur.role');
        $profileUpdates = [
            'email' => $updates['email'],
        ];
        $nameParts = preg_split('/\s+/', $updates['nom'], 2);
        $profileUpdates['nom'] = $nameParts[0] ?? $updates['nom'];
        $profileUpdates['prenom'] = $nameParts[1] ?? '';
        if (! empty($updates['avatar'])) {
            $profileUpdates['photo'] = str_starts_with($updates['avatar'], 'images/')
                ? $updates['avatar']
                : 'uploads/avatars/'.$updates['avatar'];
        }
        if ($role === 'enseignant') {
            DB::table('professeurs')->where('email', $oldEmail)->update($profileUpdates);
        } elseif ($role === 'staff') {
            DB::table('staff')->where('email', $oldEmail)->update($profileUpdates);
        }

        return back()->with('success', 'Profil mis a jour avec succes.');
    }

    public function password(Request $request)
    {
        $this->ensureSession();
        $data = $request->validate([
            'mot_de_passe' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        DB::table('utilisateurs')->where('id', (int) session('utilisateur.id'))->update([
            'mot_de_passe' => Hash::make($data['mot_de_passe']),
        ]);

        return back()->with('success', 'Mot de passe modifie avec succes.');
    }

    private function ensureSession(): void
    {
        abort_unless(session()->has('utilisateur'), 403);
    }
}
