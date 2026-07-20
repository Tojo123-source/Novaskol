<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Resultat - {{ $exercice->titre }}</title>
    <link rel="stylesheet" href="{{ asset('legacy/assets/fontawesome/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('legacy/fa/css/font-awesome.min.css') }}">
    @include('modules.professeur.bulletin.partials.styles')
    <style>
        .student-wrap{max-width:600px;margin:88px 20px 20px 256px;text-align:center}
        :root{--blue:#3b82f6;--orange:#f59e0b;--muted:var(--text-sec);--line:var(--border);--green:var(--success);--red:var(--danger)}
        .score-circle{width:120px;height:120px;border-radius:50%;display:flex;flex-direction:column;align-items:center;justify-content:center;margin:0 auto 16px;font-size:2rem;font-weight:800;border:4px solid var(--green);color:var(--green)}.score-circle .label{font-size:.75rem;font-weight:400;color:var(--muted)}.detail{text-align:left;background:var(--surface);border:1px solid var(--line);border-radius:10px;padding:16px;margin-top:16px}.detail-item{display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid var(--line);font-size:.85rem}.btn{display:inline-flex;align-items:center;gap:6px;padding:8px 16px;border-radius:8px;font-weight:600;font-size:.85rem;border:0;cursor:pointer;text-decoration:none}.btn-outline{background:transparent;border:1px solid var(--line);color:var(--text)}h1{font-size:1.15rem;margin:0 0 20px}
        @media(max-width:1180px){.student-wrap{margin-left:16px;margin-right:16px}}
        @media(max-width:700px){.student-wrap{margin-top:100px}}
        @media(max-width:480px){.score-circle{width:100px;height:100px;font-size:1.6rem}}
    </style>
</head>
<body>
@include('modules.professeur.bulletin.partials.shell', ['activeModule' => 'eleve_exercice_result'])
<header>
    <div class="header-left">
        <button class="burger-menu" onclick="toggleSidebar()"><i class="fa fa-bars"></i></button>
        <button id="fullscreen-btn" onclick="toggleFullscreen()"><i class="fa fa-expand"></i></button>
    </div>
    <div class="header-center">Resultat - {{ $exercice->titre }}</div>
</header>
<div class="student-wrap">
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
<script>
function toggleSub(e){const s=e.nextElementSibling,a=e.querySelector('.arrow');s.style.display=s.style.display==='block'?'none':'block';a.classList.toggle('fa-chevron-down');a.classList.toggle('fa-chevron-up')}
function toggleSidebar(){document.getElementById('sidebar').classList.toggle('active');}
function toggleFullscreen(){const i=document.getElementById('fullscreen-icon');if(!document.fullscreenElement){document.documentElement.requestFullscreen();i.classList.replace('fa-expand','fa-compress')}else{document.exitFullscreen();i.classList.replace('fa-compress','fa-expand')}}
</script>
</body>
</html>
