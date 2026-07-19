<?php

namespace App\Http\Controllers;

use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ActivationController extends Controller
{
    public function index()
    {
        $activation = DB::table('activations')->where('ecole_id', 1)->first();
        $ecole = DB::table('ecole')->first();
        $totalEleves = DB::table('eleves')->count();

        return view('modules.parametres.activations', compact('activation', 'ecole', 'totalEleves'));
    }

    public function activate(Request $request)
    {
        $data = $request->validate([
            'cle_activation' => 'required|string|max:100',
        ]);

        $existing = DB::table('activations')->where('cle_activation', $data['cle_activation'])->first();

        if (!$existing) {
            // Vérifier le format de la clé (simulé - en production, vérifier via API)
            if (!preg_match('/^NVK-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{4}$/', $data['cle_activation'])) {
                return back()->withErrors(['cle_activation' => 'Format de cle invalide.'])->withInput();
            }

            $totalEleves = DB::table('eleves')->count();
            $montant = $totalEleves * 1500;

            DB::table('activations')->insert([
                'ecole_id' => 1,
                'cle_activation' => $data['cle_activation'],
                'statut' => 'active',
                'date_activation' => now()->toDateString(),
                'date_expiration' => now()->addYear()->toDateString(),
                'max_eleves' => max(50, $totalEleves + 10),
                'montant' => $montant,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            DB::table('activations')->where('id', $existing->id)->update([
                'statut' => 'active',
                'date_activation' => now()->toDateString(),
                'date_expiration' => now()->addYear()->toDateString(),
                'updated_at' => now(),
            ]);
        }

        ActivityLogger::log('activation.active', 'activation', 'activations', $existing->id ?? null);
        return redirect()->route('modules.parametres.activations')->with('success', 'Ecole activee avec succes.');
    }

    public function desactiver(int $id)
    {
        DB::table('activations')->where('id', $id)->update(['statut' => 'expiree', 'updated_at' => now()]);
        ActivityLogger::log('activation.desactive', 'activation', 'activations', $id);
        return redirect()->route('modules.parametres.activations')->with('success', 'Activation desactivee.');
    }
}
