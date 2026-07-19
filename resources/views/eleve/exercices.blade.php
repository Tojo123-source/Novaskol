<!DOCTYPE html>
<html lang="fr">
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Exercices - {{ $ch->titre }}</title>
<link rel="stylesheet" href="{{ asset('legacy/assets/fontawesome/css/all.min.css') }}">
<style>:root{--bg:#0f172a;--surface:#1e293b;--text:#f1f5f9;--muted:#94a3b8;--line:#334155;--green:#22c55e;--blue:#3b82f6;--orange:#f59e0b}*{box-sizing:border-box}body{margin:0;font-family:Inter,system-ui,sans-serif;background:var(--bg);color:var(--text)}.wrap{max-width:700px;margin:0 auto;padding:24px 16px}.back{color:var(--muted);text-decoration:none;font-size:.85rem;margin-bottom:16px;display:inline-flex;align-items:center;gap:6px}.back:hover{color:var(--text)}h1{font-size:1.2rem;margin:0 0 16px}.card{background:var(--surface);border:1px solid var(--line);border-radius:10px;padding:16px;margin-bottom:10px;display:flex;justify-content:space-between;align-items:center;gap:12px}.card h3{margin:0 0 2px;font-size:.92rem}.card .meta{color:var(--muted);font-size:.78rem}.btn{display:inline-flex;align-items:center;gap:6px;padding:7px 14px;border-radius:6px;font-weight:600;font-size:.82rem;border:0;cursor:pointer;text-decoration:none}.btn-primary{background:var(--blue);color:#fff}.btn-success{background:var(--green);color:#fff}.btn-outline{background:transparent;border:1px solid var(--line);color:var(--text)}.btn-sm{padding:4px 8px;font-size:.72rem}.badge{padding:2px 8px;border-radius:10px;font-size:.72rem;background:var(--line);color:var(--muted)}.badge-success{background:rgba(34,197,94,.15);color:var(--green)}.flash{padding:10px 14px;border-radius:8px;margin-bottom:14px;font-size:.85rem}.flash-success{background:rgba(34,197,94,.12);color:var(--green);border:1px solid rgba(34,197,94,.2)}.empty{text-align:center;padding:40px;color:var(--muted)}.empty i{font-size:2rem;opacity:.4;display:block;margin-bottom:8px}</style>
</head>
<body>
<div class="wrap">
    @if (session('success'))
        <div class="flash flash-success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('eleve.course.show', $course->id) }}" class="back"><i class="fa fa-arrow-left"></i> {{ $course->titre }}</a>
    <h1><i class="fa fa-puzzle-piece" style="color:var(--blue)"></i> Exercices - {{ $ch->titre }}</h1>

    @if ($exercices->isEmpty())
        <div class="empty"><i class="fa fa-puzzle-piece"></i><p>Aucun exercice pour ce chapitre.</p></div>
    @else
        @foreach ($exercices as $ex)
            @php $soum = $soumissions->get($ex->id); @endphp
            <div class="card">
                <div>
                    <h3>{{ $ex->titre }}</h3>
                    <div class="meta">
                        <span>{{ $ex->type }}</span>
                        @if ($ex->temps_limite)<span>{{ $ex->temps_limite }}s</span>@endif
                        @if ($soum && $soum->termine_le)
                            <span class="badge badge-success">Score: {{ $soum->score }}/20</span>
                        @endif
                    </div>
                </div>
                @if ($soum && $soum->termine_le)
                    <a href="{{ route('eleve.exercices.result', $soum->id) }}" class="btn btn-outline btn-sm">Resultat</a>
                @elseif ($soum && !$soum->termine_le)
                    <a href="{{ route('eleve.exercices.show', $ex->id) }}" class="btn btn-primary btn-sm">Continuer</a>
                @else
                    <a href="{{ route('eleve.exercices.show', $ex->id) }}" class="btn btn-primary btn-sm">Commencer</a>
                @endif
            </div>
        @endforeach
    @endif
</div>
</body>
</html>
