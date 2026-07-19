<!DOCTYPE html>
<html lang="fr">
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Mon espace - Novaskol</title>
<link rel="stylesheet" href="{{ asset('legacy/assets/fontawesome/css/all.min.css') }}">
<style>:root{--bg:#0f172a;--surface:#1e293b;--surface2:#334155;--text:#f1f5f9;--muted:#94a3b8;--line:#334155;--green:#22c55e;--blue:#3b82f6;--orange:#f59e0b;--pink:#ec4899}*{box-sizing:border-box}body{margin:0;font-family:Inter,system-ui,sans-serif;background:var(--bg);color:var(--text);min-height:100vh}.wrap{max-width:1100px;margin:0 auto;padding:24px 16px}.header{display:flex;align-items:center;justify-content:space-between;gap:16px;margin-bottom:28px;flex-wrap:wrap}.header h1{margin:0;font-size:1.4rem;display:flex;align-items:center;gap:10px}.avatar{width:40px;height:40px;border-radius:50%;background:var(--blue);display:grid;place-items:center;font-weight:700;font-size:1.1rem;color:#fff;flex-shrink:0}.greeting{color:var(--muted);font-size:.9rem;margin-top:2px}.grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:14px;margin-bottom:24px}.stat-card{background:var(--surface);border:1px solid var(--line);border-radius:12px;padding:18px;display:flex;align-items:center;gap:14px}.stat-card .icon{width:44px;height:44px;border-radius:10px;display:grid;place-items:center;font-size:1.2rem;flex-shrink:0}.stat-card .info h3{margin:0;font-size:1.3rem}.stat-card .info p{margin:2px 0 0;color:var(--muted);font-size:.82rem}.section-title{font-size:1.05rem;margin:0 0 12px;display:flex;align-items:center;gap:8px}.course-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(260px,1fr));gap:12px;margin-bottom:28px}.course-card{background:var(--surface);border:1px solid var(--line);border-radius:10px;padding:16px;transition:border-color .15s;text-decoration:none;color:var(--text)}.course-card:hover{border-color:var(--blue)}.course-card h3{margin:0 0 6px;font-size:.95rem}.course-card .meta{color:var(--muted);font-size:.78rem;display:flex;gap:10px;margin-bottom:6px}.progress-bar{height:4px;background:var(--surface2);border-radius:2px;overflow:hidden;margin-top:8px}.progress-bar .fill{height:100%;background:var(--green);border-radius:2px;transition:width .3s}.btn{display:inline-flex;align-items:center;gap:6px;padding:8px 14px;border-radius:8px;font-weight:600;font-size:.82rem;border:0;cursor:pointer;text-decoration:none}.btn-primary{background:var(--blue);color:#fff}.btn-outline{background:transparent;border:1px solid var(--line);color:var(--text)}.btn-sm{padding:5px 10px;font-size:.75rem}.nav-links{display:flex;gap:8px;flex-wrap:wrap;margin-bottom:20px}.nav-links a{display:flex;align-items:center;gap:6px;padding:8px 14px;border-radius:8px;background:var(--surface);border:1px solid var(--line);color:var(--text);text-decoration:none;font-size:.85rem;transition:border-color .15s}.nav-links a:hover{border-color:var(--blue)}.empty{text-align:center;padding:40px;color:var(--muted);border:1px dashed var(--line);border-radius:10px}.empty i{font-size:2rem;opacity:.5;display:block;margin-bottom:8px}.flash{padding:10px 14px;border-radius:8px;margin-bottom:14px;font-size:.85rem}.flash-success{background:rgba(34,197,94,.12);color:var(--green);border:1px solid rgba(34,197,94,.2)}</style>
</head>
<body>
<div class="wrap">
    @if (session('success'))
        <div class="flash flash-success"><i class="fa fa-check-circle"></i> {{ session('success') }}</div>
    @endif

    <div class="header">
        <div style="display:flex;align-items:center;gap:12px">
            <div class="avatar">{{ mb_substr($eleve->prenom ?? $eleve->nom, 0, 1) }}</div>
            <div>
                <h1>Bienvenue, {{ $eleve->prenom ?? $eleve->nom }}</h1>
                <div class="greeting">{{ $classe->nom ?? '' }} - {{ $eleve->matricule ?? '' }}</div>
            </div>
        </div>
    </div>

    <div class="nav-links">
        <a href="{{ route('eleve.courses') }}"><i class="fa fa-book"></i> Bibliotheque</a>
        <a href="{{ route('eleve.historique') }}"><i class="fa fa-history"></i> Historique</a>
        <a href="{{ route('eleve.rapport') }}"><i class="fa fa-chart-line"></i> Mon rapport</a>
    </div>

    <div class="grid">
        <div class="stat-card">
            <div class="icon" style="background:rgba(59,130,246,.15);color:var(--blue)"><i class="fa fa-book-open"></i></div>
            <div class="info"><h3>{{ $courses->count() }}</h3><p>Cours disponibles</p></div>
        </div>
        <div class="stat-card">
            <div class="icon" style="background:rgba(34,197,94,.15);color:var(--green)"><i class="fa fa-check-double"></i></div>
            <div class="info"><h3>{{ $progressions->sum('done') ?? 0 }}</h3><p>Chapitres termines</p></div>
        </div>
        <div class="stat-card">
            <div class="icon" style="background:rgba(245,158,11,.15);color:var(--orange)"><i class="fa fa-star"></i></div>
            <div class="info"><h3>{{ count($favIds) }}</h3><p>Favoris</p></div>
        </div>
        <div class="stat-card">
            <div class="icon" style="background:rgba(236,72,153,.15);color:var(--pink)"><i class="fa fa-puzzle-piece"></i></div>
            <div class="info"><h3>{{ $exercicesRecents->count() }}</h3><p>Exercices soumis</p></div>
        </div>
    </div>

    <h2 class="section-title"><i class="fa fa-book" style="color:var(--blue)"></i> Cours disponibles</h2>
    @if ($courses->isEmpty())
        <div class="empty"><i class="fa fa-book-open"></i><p>Aucun cours disponible pour le moment.</p></div>
    @else
        <div class="course-grid">
            @foreach ($courses as $c)
                @php
                    $prog = $progressions->get($c->id);
                    $total = $prog ? $prog->total : 0;
                    $done = $prog ? $prog->done : 0;
                    $pct = $total > 0 ? round(($done / $total) * 100) : 0;
                @endphp
                <a href="{{ route('eleve.course.show', $c->id) }}" class="course-card">
                    <h3>{{ $c->titre }}</h3>
                    <div class="meta">
                        <span>{{ DB::table('matieres')->where('id', $c->matiere_id)->value('nom') ?? 'General' }}</span>
                        @if ($pct > 0)<span>{{ $pct }}%</span>@endif
                    </div>
                    @if ($total > 0)
                        <div class="progress-bar"><div class="fill" style="width:{{ $pct }}%"></div></div>
                    @endif
                </a>
            @endforeach
        </div>
    @endif

    @if ($exercicesRecents->isNotEmpty())
        <h2 class="section-title"><i class="fa fa-history" style="color:var(--orange)"></i> Exercices recents</h2>
        @foreach ($exercicesRecents as $s)
            <div style="background:var(--surface);border:1px solid var(--line);border-radius:8px;padding:12px 16px;margin-bottom:8px;display:flex;justify-content:space-between;align-items:center;gap:12px">
                <div>
                    <strong style="font-size:.9rem">{{ DB::table('exercices')->where('id', $s->exercice_id)->value('titre') }}</strong>
                    <div style="color:var(--muted);font-size:.78rem">Note: {{ $s->score }}/20</div>
                </div>
                <a href="{{ route('eleve.exercices.result', $s->id) }}" class="btn btn-outline btn-sm">Voir</a>
            </div>
        @endforeach
    @endif
</div>
</body>
</html>
