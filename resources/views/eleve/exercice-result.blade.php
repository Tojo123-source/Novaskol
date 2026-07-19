<!DOCTYPE html>
<html lang="fr">
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Resultat - {{ $exercice->titre }}</title>
<link rel="stylesheet" href="{{ asset('legacy/assets/fontawesome/css/all.min.css') }}">
<style>:root{--bg:#0f172a;--surface:#1e293b;--text:#f1f5f9;--muted:#94a3b8;--line:#334155;--green:#22c55e;--blue:#3b82f6;--orange:#f59e0b;--red:#ef4444}*{box-sizing:border-box}body{margin:0;font-family:Inter,system-ui,sans-serif;background:var(--bg);color:var(--text)}.wrap{max-width:600px;margin:0 auto;padding:24px 16px;text-align:center}.score-circle{width:120px;height:120px;border-radius:50%;display:flex;flex-direction:column;align-items:center;justify-content:center;margin:0 auto 16px;font-size:2rem;font-weight:800;border:4px solid var(--green);color:var(--green)}.score-circle .label{font-size:.75rem;font-weight:400;color:var(--muted)}.detail{text-align:left;background:var(--surface);border:1px solid var(--line);border-radius:10px;padding:16px;margin-top:16px}.detail-item{display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid var(--line);font-size:.85rem}.btn{display:inline-flex;align-items:center;gap:6px;padding:8px 16px;border-radius:8px;font-weight:600;font-size:.85rem;border:0;cursor:pointer;text-decoration:none}.btn-outline{background:transparent;border:1px solid var(--line);color:var(--text)}h1{font-size:1.15rem;margin:0 0 20px}@media(max-width:480px){.score-circle{width:100px;height:100px;font-size:1.6rem}}</style>
</head>
<body>
<div class="wrap">
    <h1><i class="fa fa-star" style="color:var(--orange)"></i> {{ $exercice->titre }}</h1>

    @php
        $totalPts = $questions->sum('points');
        $score = $soumission->score;
        $pourcentage = $totalPts > 0 ? round(($score / 20) * 100) : 0;
        $couleur = $pourcentage >= 80 ? 'var(--green)' : ($pourcentage >= 50 ? 'var(--orange)' : 'var(--red)');
    @endphp

    <div class="score-circle" style="border-color:{{ $couleur }};color:{{ $couleur }}">
        {{ $score }}/20
        <span class="label">{{ $pourcentage }}%</span>
    </div>

    <div style="color:var(--muted);font-size:.85rem;margin-bottom:16px">
        @if ($soumission->temps_realise)
            <span><i class="fa fa-clock"></i> Temps: {{ gmdate('i:s', $soumission->temps_realise) }}</span>
        @endif
        <span style="margin-left:12px"><i class="fa fa-calendar"></i> {{ $soumission->created_at->format('d/m/Y H:i') }}</span>
    </div>

    <div class="detail">
        @foreach ($questions as $i => $q)
            @php
                $rep = collect($reponses)->firstWhere('question_id', $q->id);
                $correct = $rep['correct'] ?? false;
            @endphp
            <div class="detail-item">
                <span>Q{{ $i + 1 }}: {{ mb_substr($q->question, 0, 60) }}{{ mb_strlen($q->question) > 60 ? '...' : '' }}</span>
                <span style="color:{{ $correct ? 'var(--green)' : 'var(--red)' }}">
                    <i class="fa {{ $correct ? 'fa-check-circle' : 'fa-times-circle' }}"></i>
                    {{ $correct ? '+' . $q->points : '0' }} pt
                </span>
            </div>
        @endforeach
    </div>

    <div style="margin-top:20px">
        <a href="{{ route('eleve.exercices.list', $ch->id ?? $exercice->chapitre_id) }}" class="btn btn-outline"><i class="fa fa-arrow-left"></i> Retour</a>
    </div>
</div>
</body>
</html>
