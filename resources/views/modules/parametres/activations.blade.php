<!DOCTYPE html>
<html lang="fr">
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Activation - Novaskol</title>
<link rel="stylesheet" href="{{ asset('legacy/assets/fontawesome/css/all.min.css') }}">
<style>:root{--bg:#f1f5f9;--surface:#fff;--text:#1e293b;--muted:#64748b;--line:#e2e8f0;--green:#16a34a;--blue:#2563eb;--orange:#ea580c;--red:#dc2626}*{box-sizing:border-box}body{margin:0;font-family:Inter,system-ui,sans-serif;background:var(--bg);color:var(--text)}.wrap{max-width:700px;margin:0 auto;padding:24px 16px}h1{font-size:1.3rem;margin:0 0 20px;display:flex;align-items:center;gap:8px}.card{background:var(--surface);border:1px solid var(--line);border-radius:12px;padding:24px;margin-bottom:16px}.card h2{margin:0 0 4px;font-size:1.05rem}.card .statut{display:inline-flex;align-items:center;gap:6px;padding:4px 12px;border-radius:20px;font-size:.8rem;font-weight:600}.statut-active{background:#dcfce7;color:#166534}.statut-expiree{background:#fef3c7;color:#92400e}.statut-en_attente{background:#f1f5f9;color:#475569}.row{display:flex;justify-content:space-between;padding:10px 0;border-bottom:1px solid var(--line);font-size:.9rem}.row:last-child{border:0}.row .label{color:var(--muted)}.row .value{font-weight:600}.btn{display:inline-flex;align-items:center;gap:6px;padding:10px 18px;border-radius:8px;font-weight:600;font-size:.9rem;border:0;cursor:pointer;text-decoration:none}.btn-primary{background:var(--blue);color:#fff}.btn-danger{background:var(--red);color:#fff}.btn-outline{background:transparent;border:1px solid var(--line);color:var(--text)}.btn-sm{padding:6px 12px;font-size:.82rem}label{display:block;font-size:.85rem;font-weight:600;margin-bottom:4px}input{width:100%;border:1px solid var(--line);border-radius:8px;padding:10px 12px;font:inherit;outline:none;margin-bottom:12px}.flash{padding:12px 16px;border-radius:8px;margin-bottom:16px}.flash-success{background:#dcfce7;color:#166534;border:1px solid #bbf7d0}.error{color:var(--red);font-size:.85rem;margin-bottom:10px}@media(max-width:480px){.card{padding:16px}}</style>
</head>
<body>
<div class="wrap">
    @if (session('success'))
        <div class="flash flash-success"><i class="fa fa-check-circle"></i> {{ session('success') }}</div>
    @endif

    <h1><i class="fa fa-key" style="color:var(--blue)"></i> Activation de l'etablissement</h1>

    <div class="card">
        <h2>{{ $ecole->nom ?? 'Etablissement' }}</h2>

        @if ($activation)
            <div style="margin:12px 0">
                <span class="statut statut-{{ $activation->statut }}">
                    <i class="fa {{ $activation->statut === 'active' ? 'fa-check-circle' : 'fa-clock' }}"></i>
                    {{ $activation->statut === 'active' ? 'Active' : ($activation->statut === 'expiree' ? 'Expiree' : 'En attente') }}
                </span>
            </div>

            <div class="row"><span class="label">Cle d'activation</span><span class="value">{{ $activation->cle_activation }}</span></div>
            <div class="row"><span class="label">Activee le</span><span class="value">{{ $activation->date_activation ? date('d/m/Y', strtotime($activation->date_activation)) : '-' }}</span></div>
            <div class="row"><span class="label">Expire le</span><span class="value">{{ $activation->date_expiration ? date('d/m/Y', strtotime($activation->date_expiration)) : '-' }}</span></div>
            <div class="row"><span class="label">Eleves max</span><span class="value">{{ $activation->max_eleves }}</span></div>
            <div class="row"><span class="label">Montant</span><span class="value">{{ number_format($activation->montant, 0, ',', ' ') }} Ar</span></div>
            <div class="row"><span class="label">Eleves actuels</span><span class="value">{{ $totalEleves }}</span></div>

            @if ($activation->statut === 'active')
                <form method="POST" action="{{ route('modules.parametres.activations.desactiver', $activation->id) }}" style="margin-top:16px" onsubmit="return confirm('Desactiver cette activation ?')">
                    @csrf
                    <button class="btn btn-danger btn-sm"><i class="fa fa-times"></i> Desactiver</button>
                </form>
            @endif
        @else
            <div style="text-align:center;padding:20px 0;color:var(--muted)">
                <i class="fa fa-key" style="font-size:2.5rem;opacity:.4;display:block;margin-bottom:8px"></i>
                <p>Aucune activation. Veuillez saisir votre cle d'activation.</p>
            </div>
        @endif
    </div>

    <div class="card">
        <h2><i class="fa fa-plus-circle"></i> Activer ou renouveler</h2>
        <p style="color:var(--muted);font-size:.85rem;margin:4px 0 16px">
            Tarif: <strong>1 500 Ar</strong> par eleve ({{ $totalEleves }} eleves = <strong>{{ number_format($totalEleves * 1500, 0, ',', ' ') }} Ar</strong>)
        </p>

        @if ($errors->has('cle_activation'))
            <div class="error">{{ $errors->first('cle_activation') }}</div>
        @endif

        <form method="POST" action="{{ route('modules.parametres.activations.activate') }}">
            @csrf
            <label>Cle d'activation</label>
            <input name="cle_activation" required maxlength="100" placeholder="NVK-XXXX-XXXX-XXXX" pattern="NVK-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{4}">
            <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> Activer</button>
        </form>
    </div>

    <div class="card" style="font-size:.85rem;color:var(--muted);line-height:1.6">
        <strong style="color:var(--text)">Comment obtenir une cle ?</strong><br>
        Contactez le support Novaskol pour obtenir votre cle d'activation apres paiement.<br>
        Prix: 1 500 Ar par eleve, valable 1 an.
    </div>
</div>
</body>
</html>
