<?php

namespace App\Http\Controllers;

use App\Services\Novaskol\ModuleRegistry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class SchoolController extends Controller
{
    public function show(ModuleRegistry $modules)
    {
        if (! session()->has('utilisateur')) {
            return redirect()->route('login');
        }

        if (! in_array(session('utilisateur.role'), ['admin', 'enseignant', 'parent'], true)) {
            return redirect()->route('login');
        }

        return view('modules.administration.ecole', [
            'modules' => $modules->all(),
            'userPermissions' => $this->userPermissions(),
            'ecole' => $this->school(),
        ]);
    }

    public function update(Request $request)
    {
        if (! in_array(session('utilisateur.role'), ['admin', 'enseignant'], true)) {
            return response()->json(['status' => 'error', 'message' => 'Non autorise'], 403);
        }

        $data = $request->validate([
            'nom_ecole' => ['required', 'string', 'max:255'],
            'logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,gif', 'max:10240'],
        ]);

        $school = DB::table('ecole')->select('id', 'nom', 'logo')->first();
        $logoPath = $school->logo ?? 'novaskol.png';

        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $logoPath = 'logo_'.time().'.'.$file->getClientOriginalExtension();
            $destination = public_path('legacy/images');

            File::ensureDirectoryExists($destination);
            $file->move($destination, $logoPath);

            $oldLogo = $school->logo ?? null;
            if ($oldLogo && $oldLogo !== 'novaskol.png') {
                $oldPath = public_path('legacy/images/'.$oldLogo);
                if (File::exists($oldPath)) {
                    File::delete($oldPath);
                }
            }
        }

        if ($school) {
            DB::table('ecole')->where('id', $school->id)->update([
                'nom' => trim($data['nom_ecole']),
                'logo' => $logoPath,
            ]);
        } else {
            DB::table('ecole')->insert([
                'id' => 1,
                'nom' => trim($data['nom_ecole']),
                'logo' => $logoPath,
            ]);
        }

        return [
            'status' => 'success',
            'message' => 'Modification reussie !',
            'nom' => trim($data['nom_ecole']),
            'logo' => $logoPath,
        ];
    }

    private function userPermissions(): array
    {
        if (! session('utilisateur.id')) {
            return [];
        }

        return DB::table('permissions')
            ->where('utilisateur_id', session('utilisateur.id'))
            ->pluck('acces', 'module')
            ->all();
    }

    private function school(): object
    {
        return DB::table('ecole')->select('id', 'nom', 'logo')->first() ?: (object) [
            'id' => 1,
            'nom' => 'Ecole',
            'logo' => 'novaskol.png',
        ];
    }
}
