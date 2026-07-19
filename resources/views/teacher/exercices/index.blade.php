<!DOCTYPE html>
<html lang="fr">
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Exercices - Novaskol</title>
<link rel="stylesheet" href="{{ asset('legacy/assets/fontawesome/css/all.min.css') }}">
<style>:root{--bg:#f1f5f9;--surface:#fff;--text:#1e293b;--muted:#64748b;--line:#e2e8f0;--green:#16a34a;--blue:#2563eb;--orange:#ea580c}*{box-sizing:border-box}body{margin:0;font-family:Inter,system-ui,sans-serif;background:var(--bg);color:var(--text);min-height:100vh}.wrap{max-width:1000px;margin:0 auto;padding:24px 16px}.header{display:flex;align-items:center;justify-content:space-between;gap:16px;margin-bottom:24px;flex-wrap:wrap}.header h1{margin:0;font-size:1.4rem;display:flex;align-items:center;gap:8px}.btn{display:inline-flex;align-items:center;gap:6px;padding:8px 14px;border-radius:8px;font-weight:600;font-size:.85rem;border:0;cursor:pointer;text-decoration:none;transition:all .15s}.btn-primary{background:var(--blue);color:#fff}.btn-outline{background:transparent;border:1px solid var(--line);color:var(--text)}.btn-danger{color:#dc2626}.btn-sm{padding:5px 10px;font-size:.78rem}.btn-success{background:var(--green);color:#fff}.card{background:var(--surface);border:1px solid var(--line);border-radius:10px;padding:16px;margin-bottom:12px}.card h3{margin:0 0 4px;font-size:1rem}.card .meta{color:var(--muted);font-size:.82rem;display:flex;gap:12px;flex-wrap:wrap}.badge{display:inline-block;padding:2px 8px;border-radius:12px;font-size:.72rem;font-weight:600;background:#e2e8f0;color:#475569}.badge-success{background:#dcfce7;color:#166534}select{background:var(--surface);border:1px solid var(--line);border-radius:8px;padding:8px 12px;font:inherit;color:var(--text);outline:none}.empty{text-align:center;padding:60px 20px;color:var(--muted)}.empty i{font-size:3rem;opacity:.4;display:block;margin-bottom:12px}.flash{padding:12px 16px;border-radius:8px;margin-bottom:16px;font-size:.9rem}.flash-success{background:#dcfce7;color:#166534;border:1px solid #bbf7d0}</style>
</head>
<body>
<div class="wrap">
    @if (session('success'))
        <div class="flash flash-success"><i class="fa fa-check-circle"></i> {{ session('success') }}</div>
    @endif

    <div class="header">
        <h1><i class="fa fa-puzzle-piece" style="color:var(--blue)"></i> Exercices</h1>
        <div style="display:flex;gap:8px">
            <a href="{{ route('teacher.courses.index') }}" class="btn btn-outline btn-sm"><i class="fa fa-book"></i> Cours</a>
        </div>
    </div>

    <form method="GET" style="margin-bottom:16px">
        <select name="course_id" onchange="this.form.submit()" style="min-width:250px">
            <option value="0">-- Choisir un cours --</option>
            @foreach ($courses as $c)
                <option value="{{ $c->id }}" {{ $courseId == $c->id ? 'selected' : '' }}>{{ $c->titre }}</option>
            @endforeach
        </select>
    </form>

    @if ($exercices->isEmpty())
        <div class="empty">
            <i class="fa fa-puzzle-piece"></i>
            <p>Selectionnez un cours pour voir ses exercices.<br>Ajoutez des exercices depuis la page d'un chapitre.</p>
        </div>
    @else
        @foreach ($exercices as $ex)
            <div class="card">
                <div style="display:flex;justify-content:space-between;align-items:start;gap:8px;flex-wrap:wrap">
                    <div>
                        <h3>{{ $ex->titre }}</h3>
                        <div class="meta">
                            <span><i class="fa fa-list"></i> {{ $ex->questions_count }} questions</span>
                            <span><i class="fa fa-users"></i> {{ $ex->soumissions_count }} soumissions</span>
                            <span><i class="fa fa-tag"></i> {{ $ex->type }}</span>
                            <span><i class="fa fa-folder"></i> {{ $ex->chapitre_titre }}</span>
                            @if ($ex->temps_limite)<span><i class="fa fa-clock"></i> {{ $ex->temps_limite }}s</span>@endif
                        </div>
                    </div>
                    <div style="display:flex;gap:4px">
                        <a href="{{ route('teacher.exercices.edit', $ex->id) }}" class="btn btn-outline btn-sm"><i class="fa fa-edit"></i></a>
                        <form method="POST" action="{{ route('teacher.exercices.destroy', $ex->id) }}" style="display:inline" onsubmit="return confirm('Supprimer cet exercice ?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-outline btn-sm btn-danger"><i class="fa fa-trash"></i></button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    @endif
</div>
</body>
</html>
