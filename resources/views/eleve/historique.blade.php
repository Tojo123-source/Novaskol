<!DOCTYPE html>
<html lang="fr">
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Historique - Novaskol</title>
<link rel="stylesheet" href="{{ asset('legacy/assets/fontawesome/css/all.min.css') }}">
<style>:root{--bg:#0f172a;--surface:#1e293b;--text:#f1f5f9;--muted:#94a3b8;--line:#334155;--green:#22c55e;--blue:#3b82f6}*{box-sizing:border-box}body{margin:0;font-family:Inter,system-ui,sans-serif;background:var(--bg);color:var(--text)}.wrap{max-width:800px;margin:0 auto;padding:24px 16px}.back{color:var(--muted);text-decoration:none;font-size:.85rem;display:inline-flex;align-items:center;gap:6px;margin-bottom:16px}.back:hover{color:var(--text)}h1{font-size:1.15rem;margin:0 0 20px}.section{margin-bottom:28px}.section h2{font-size:.95rem;margin:0 0 10px;display:flex;align-items:center;gap:8px;color:var(--muted)}.item{background:var(--surface);border:1px solid var(--line);border-radius:8px;padding:10px 14px;margin-bottom:6px;display:flex;justify-content:space-between;align-items:center;font-size:.85rem}.item .date{color:var(--muted);font-size:.78rem}.btn{display:inline-flex;align-items:center;gap:6px;padding:4px 10px;border-radius:6px;font-size:.75rem;border:0;cursor:pointer;text-decoration:none;font-weight:600}.btn-outline{background:transparent;border:1px solid var(--line);color:var(--text)}.empty{text-align:center;padding:40px;color:var(--muted);font-size:.85rem}.pagination{display:flex;gap:6px;justify-content:center;margin-top:16px;flex-wrap:wrap}.pagination a,.pagination span{padding:5px 10px;border-radius:6px;background:var(--surface);border:1px solid var(--line);color:var(--text);text-decoration:none;font-size:.82rem}</style>
</head>
<body>
<div class="wrap">
    <a href="{{ route('eleve.portal') }}" class="back"><i class="fa fa-arrow-left"></i> Accueil</a>
    <h1><i class="fa fa-history" style="color:var(--blue)"></i> Mon historique</h1>

    <div class="section">
        <h2><i class="fa fa-check-circle" style="color:var(--green)"></i> Progression des chapitres</h2>
        @if ($progress->isEmpty())
            <div class="empty">Aucun chapitre suivi.</div>
        @else
            @foreach ($progress as $p)
                <div class="item">
                    <div>
                        <strong>{{ $p->course_titre }}</strong> - {{ $p->chapitre_titre }}
                        @if ($p->termine)<span style="color:var(--green);margin-left:6px">✓</span>@endif
                    </div>
                    <div class="date">{{ $p->created_at ? date('d/m/Y', strtotime($p->created_at)) : '' }}</div>
                </div>
            @endforeach
        @endif
    </div>

    <div class="section">
        <h2><i class="fa fa-puzzle-piece" style="color:var(--blue)"></i> Exercices soumis</h2>
        @if ($submissions->isEmpty())
            <div class="empty">Aucun exercice soumis.</div>
        @else
            @foreach ($submissions as $s)
                <div class="item">
                    <div>
                        <strong>{{ $s->exercice_titre }}</strong>
                        <span style="margin-left:8px;color:var(--muted)">Score: {{ $s->score }}/20</span>
                    </div>
                    <div style="display:flex;align-items:center;gap:8px">
                        <div class="date">{{ date('d/m/Y', strtotime($s->created_at)) }}</div>
                        <a href="{{ route('eleve.exercices.result', $s->id) }}" class="btn btn-outline">Voir</a>
                    </div>
                </div>
            @endforeach
            {{ $submissions->links() }}
        @endif
    </div>
</div>
</body>
</html>
