<!DOCTYPE html>
<html lang="fr">
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Recherche - Novaskol</title>
<link rel="stylesheet" href="{{ asset('legacy/assets/fontawesome/css/all.min.css') }}">
<style>:root{--bg:#f1f5f9;--surface:#fff;--text:#1e293b;--muted:#64748b;--line:#e2e8f0;--blue:#2563eb;--green:#16a34a}*{box-sizing:border-box}body{margin:0;font-family:Inter,system-ui,sans-serif;background:var(--bg);color:var(--text)}.wrap{max-width:700px;margin:0 auto;padding:24px 16px}h1{font-size:1.3rem;margin:0 0 20px;display:flex;align-items:center;gap:8px}.search-box{display:flex;gap:8px;margin-bottom:20px}input{flex:1;padding:12px 16px;border:1px solid var(--line);border-radius:10px;font:inherit;outline:none}input:focus{border-color:var(--blue)}.btn{display:inline-flex;align-items:center;gap:6px;padding:10px 18px;border-radius:8px;font-weight:600;font-size:.9rem;border:0;cursor:pointer;text-decoration:none}.btn-primary{background:var(--blue);color:#fff}.result-item{display:flex;align-items:center;gap:12px;padding:12px 16px;background:var(--surface);border:1px solid var(--line);border-radius:8px;margin-bottom:6px;text-decoration:none;color:var(--text);transition:border-color .15s}.result-item:hover{border-color:var(--blue)}.result-item .icon{width:36px;height:36px;border-radius:8px;display:grid;place-items:center;font-size:1rem;flex-shrink:0}.result-item .info{flex:1;min-width:0}.result-item .info .name{font-weight:600;font-size:.9rem}.result-item .info .detail{color:var(--muted);font-size:.8rem}.badge{padding:2px 8px;border-radius:8px;font-size:.72rem;font-weight:600}.badge-eleve{background:#dbeafe;color:#1e40af}.badge-utilisateur{background:#fef3c7;color:#92400e}.badge-parent{background:#dcfce7;color:#166534}.badge-course{background:#f3e8ff;color:#6b21a8}.badge-paiement{background:#fce7f3;color:#9d174d}.empty{text-align:center;padding:60px 20px;color:var(--muted)}.empty i{font-size:3rem;opacity:.4;display:block;margin-bottom:10px}</style>
</head>
<body>
<div class="wrap">
    <h1><i class="fa fa-search" style="color:var(--blue)"></i> Recherche universelle</h1>

    <form class="search-box" method="GET">
        <input name="q" placeholder="Rechercher un eleve, enseignant, parent, cours, paiement..." value="{{ $q }}" autofocus>
        <button class="btn btn-primary"><i class="fa fa-search"></i></button>
    </form>

    @if (mb_strlen($q) < 2)
        <div class="empty"><i class="fa fa-search"></i><p>Saisissez au moins 2 caracteres pour lancer la recherche.</p></div>
    @elseif ($results->isEmpty())
        <div class="empty"><i class="fa fa-search-minus"></i><p>Aucun resultat pour "{{ $q }}"</p></div>
    @else
        <p style="color:var(--muted);margin-bottom:12px;font-size:.85rem">{{ $results->count() }} resultat(s) pour "{{ $q }}"</p>
        @foreach ($results as $r)
            @php
                $colors = ['eleve' => '#dbeafe', 'utilisateur' => '#fef3c7', 'parent' => '#dcfce7', 'course' => '#f3e8ff', 'paiement' => '#fce7f3'];
                $icons = ['eleve' => 'fa-user-graduate', 'utilisateur' => 'fa-user', 'parent' => 'fa-users', 'course' => 'fa-book', 'paiement' => 'fa-money'];
            @endphp
            @if ($r['url'])
                <a href="{{ $r['url'] }}" class="result-item">
            @else
                <div class="result-item">
            @endif
                <div class="icon" style="background:{{ $colors[$r['type']] ?? '#f1f5f9' }};color:var(--text)">
                    <i class="fa {{ $icons[$r['type']] ?? 'fa-file' }}"></i>
                </div>
                <div class="info">
                    <div class="name">{{ $r['nom'] }}</div>
                    <div class="detail">{{ $r['detail'] }}</div>
                </div>
                <span class="badge badge-{{ $r['type'] }}">{{ $r['type'] }}</span>
            @if ($r['url'])
                </a>
            @else
                </div>
            @endif
        @endforeach
    @endif
</div>
</body>
</html>
