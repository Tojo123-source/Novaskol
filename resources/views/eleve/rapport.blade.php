<!DOCTYPE html>
<html lang="fr">
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Mon rapport - Novaskol</title>
<link rel="stylesheet" href="{{ asset('legacy/assets/fontawesome/css/all.min.css') }}">
<style>:root{--bg:#0f172a;--surface:#1e293b;--text:#f1f5f9;--muted:#94a3b8;--line:#334155;--green:#22c55e;--blue:#3b82f6;--orange:#f59e0b;--red:#ef4444;--pink:#ec4899}*{box-sizing:border-box}body{margin:0;font-family:Inter,system-ui,sans-serif;background:var(--bg);color:var(--text)}.wrap{max-width:800px;margin:0 auto;padding:24px 16px}.back{color:var(--muted);text-decoration:none;font-size:.85rem;display:inline-flex;align-items:center;gap:6px;margin-bottom:16px}.back:hover{color:var(--text)}h1{font-size:1.15rem;margin:0 0 20px}.grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:12px;margin-bottom:24px}.stat{background:var(--surface);border:1px solid var(--line);border-radius:10px;padding:14px;text-align:center}.stat .num{font-size:1.5rem;font-weight:800}.stat .lbl{color:var(--muted);font-size:.8rem;margin-top:2px}.section{margin-bottom:24px}.section h2{font-size:.95rem;margin:0 0 10px;display:flex;align-items:center;gap:8px;color:var(--muted)}table{width:100%;border-collapse:collapse;background:var(--surface);border-radius:8px;overflow:hidden;font-size:.85rem}th,td{padding:8px 12px;text-align:left;border-bottom:1px solid var(--line)}th{color:var(--muted);font-weight:600;font-size:.78rem}.bar-wrap{background:var(--line);border-radius:4px;height:6px;overflow:hidden;margin-top:10px}.bar-fill{height:100%;border-radius:4px;transition:width .3s}.empty{text-align:center;padding:30px;color:var(--muted);font-size:.85rem}</style>
</head>
<body>
<div class="wrap">
    <a href="{{ route('eleve.portal') }}" class="back"><i class="fa fa-arrow-left"></i> Accueil</a>
    <h1><i class="fa fa-chart-line" style="color:var(--blue)"></i> Mon rapport d'apprentissage</h1>

    <div class="grid">
        <div class="stat"><div class="num" style="color:var(--blue)">{{ $totalCourses }}</div><div class="lbl">Cours disponibles</div></div>
        <div class="stat"><div class="num" style="color:var(--green)">{{ $completedChapitres }}/{{ $totalChapitres }}</div><div class="lbl">Chapitres termines</div></div>
        <div class="stat"><div class="num" style="color:var(--orange)">{{ round($avgScore, 1) }}/20</div><div class="lbl">Moyenne exercices</div></div>
        <div class="stat"><div class="num" style="color:var(--pink)">{{ round($moyenneNotes, 1) }}/20</div><div class="lbl">Moyenne notes</div></div>
    </div>

    @php $progPct = $totalChapitres > 0 ? round(($completedChapitres / $totalChapitres) * 100) : 0; @endphp
    <div class="section">
        <h2><i class="fa fa-tasks" style="color:var(--green)"></i> Progression globale</h2>
        <div class="bar-wrap"><div class="bar-fill" style="width:{{ $progPct }}%;background:var(--green)"></div></div>
        <div style="text-align:right;font-size:.78rem;color:var(--muted);margin-top:4px">{{ $progPct }}%</div>
    </div>

    @if ($parMatiere->isNotEmpty())
        <div class="section">
            <h2><i class="fa fa-tag" style="color:var(--blue)"></i> Performance par matiere</h2>
            <table><thead><tr><th>Matiere</th><th>Moyenne</th><th>Exercices</th></tr></thead>
                <tbody>
                @foreach ($parMatiere as $m)
                    <tr>
                        <td><strong>{{ $m->matiere }}</strong></td>
                        <td style="color:{{ $m->avg_score >= 12 ? 'var(--green)' : ($m->avg_score >= 8 ? 'var(--orange)' : 'var(--red)') }}">{{ round($m->avg_score, 1) }}/20</td>
                        <td>{{ $m->total }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    @endif

    @if ($evolution->isNotEmpty())
        <div class="section">
            <h2><i class="fa fa-line-chart" style="color:var(--orange)"></i> Evolution mensuelle</h2>
            @php $maxScore = $evolution->max('score_moyen') ?: 1; @endphp
            @foreach ($evolution as $e)
                <div style="display:flex;align-items:center;gap:8px;margin-bottom:4px;font-size:.8rem">
                    <span style="width:60px;flex-shrink:0">{{ $e->mois }}</span>
                    <div style="flex:1;background:var(--line);border-radius:4px;height:16px;overflow:hidden">
                        <div class="bar-fill" style="width:{{ ($e->score_moyen / 20) * 100 }}%;background:var(--blue)"></div>
                    </div>
                    <span style="width:40px;text-align:right;color:var(--muted)">{{ round($e->score_moyen, 1) }}</span>
                </div>
            @endforeach
        </div>
    @endif

    @if ($parMatiere->isNotEmpty())
        @php
            $sorted = $parMatiere->sortByDesc('avg_score');
            $forte = $sorted->first();
            $faible = $sorted->last();
        @endphp
        <div class="grid" style="grid-template-columns:1fr 1fr">
            @if ($forte)
            <div class="stat">
                <div style="font-size:.8rem;color:var(--muted)">Matiere forte</div>
                <div class="num" style="font-size:1.1rem;color:var(--green);margin-top:4px">{{ $forte->matiere }}</div>
                <div class="lbl">{{ round($forte->avg_score, 1) }}/20</div>
            </div>
            @endif
            @if ($faible && $faible->matiere !== $forte->matiere)
            <div class="stat">
                <div style="font-size:.8rem;color:var(--muted)">Matiere a ameliorer</div>
                <div class="num" style="font-size:1.1rem;color:var(--orange);margin-top:4px">{{ $faible->matiere }}</div>
                <div class="lbl">{{ round($faible->avg_score, 1) }}/20</div>
            </div>
            @endif
        </div>
    @endif
</div>
</body>
</html>
