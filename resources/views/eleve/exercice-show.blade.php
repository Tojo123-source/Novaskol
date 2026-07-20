<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>{{ $exercice->titre }} - Novaskol</title>
    <link rel="stylesheet" href="{{ asset('legacy/assets/fontawesome/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('legacy/fa/css/font-awesome.min.css') }}">
    @include('modules.professeur.bulletin.partials.styles')
    <style>
        .student-wrap{max-width:700px;margin:88px 20px 20px 256px}
        :root{--blue:#3b82f6;--orange:#f59e0b;--muted:var(--text-sec);--line:var(--border);--green:var(--success)}
        .header-info{text-align:center;margin-bottom:24px}.header-info h1{font-size:1.2rem;margin:0}.header-info .meta{color:var(--muted);font-size:.82rem;margin-top:4px}.timer{font-size:1.1rem;font-weight:700;color:var(--orange)}.card{background:var(--surface);border:1px solid var(--line);border-radius:10px;padding:20px;margin-bottom:14px}.card .q-num{color:var(--muted);font-size:.8rem;margin-bottom:4px}.card .question{font-size:.95rem;margin-bottom:12px;line-height:1.5}.options label{display:flex;align-items:center;gap:8px;padding:8px 12px;margin-bottom:4px;border-radius:6px;border:1px solid var(--line);cursor:pointer;font-size:.88rem;transition:border-color .15s}.options label:hover{border-color:var(--blue)}.options input{width:auto;margin:0}.reponse-texte{width:100%;padding:10px 12px;border:1px solid var(--line);border-radius:6px;background:var(--bg);color:var(--text);font:inherit;outline:none}.btn{display:inline-flex;align-items:center;gap:6px;padding:10px 20px;border-radius:8px;font-weight:600;font-size:.9rem;border:0;cursor:pointer;text-decoration:none}.btn-primary{background:var(--blue);color:#fff}.btn-primary:hover{background:#2563eb}.btn-success{background:var(--green);color:#fff}.actions{text-align:center;margin-top:20px}.flash{padding:10px 14px;border-radius:8px;margin-bottom:14px;font-size:.85rem}.flash-warn{background:rgba(245,158,11,.12);color:var(--orange);border:1px solid rgba(245,158,11,.2)}
        @media(max-width:1180px){.student-wrap{margin-left:16px;margin-right:16px}}
        @media(max-width:700px){.student-wrap{margin-top:100px}}
    </style>
</head>
<body>
@include('modules.professeur.bulletin.partials.shell', ['activeModule' => 'eleve_exercice'])
<header>
    <div class="header-left">
        <button class="burger-menu" onclick="toggleSidebar()"><i class="fa fa-bars"></i></button>
        <button id="fullscreen-btn" onclick="toggleFullscreen()"><i class="fa fa-expand"></i></button>
    </div>
    <div class="header-center">{{ $exercice->titre }}</div>
</header>
<div class="student-wrap">
    <div class="header-info">
        <h1>{{ $exercice->titre }}</h1>
        <div class="meta">
            <span>{{ $ch->titre }}</span>
            @if ($exercice->temps_limite)
                <span class="timer" id="timer">{{ gmdate('i:s', $exercice->temps_limite) }}</span>
            @endif
            <span>{{ $questions->count() }} questions</span>
        </div>
    </div>

    @if ($exercice->temps_limite)
        <div class="flash flash-warn"><i class="fa fa-clock"></i> Temps limite: {{ gmdate('i:s', $exercice->temps_limite) }}</div>
    @endif

    <form method="POST" action="{{ route('eleve.exercices.submit', $exercice->id) }}" id="exerciseForm">
        @csrf
        <input type="hidden" name="started_at" value="{{ now() }}" id="startedAt">

        @foreach ($questions as $i => $q)
            <div class="card">
                <div class="q-num">Question {{ $i + 1 }} / {{ $questions->count() }} ({{ $q->points }} pt(s))</div>
                <div class="question">{{ $q->question }}</div>
                @if ($q->options)
                    @php $opts = json_decode($q->options, true) ?? []; @endphp
                    <div class="options">
                        @foreach ($opts as $opt)
                            <label>
                                <input type="{{ $exercice->type === 'qcm' ? 'radio' : 'checkbox' }}"
                                       name="reponses[{{ $q->id }}]{{ $exercice->type === 'qcm' ? '' : '[]' }}"
                                       value="{{ $opt }}">
                                {{ $opt }}
                            </label>
                        @endforeach
                    </div>
                @else
                    <input class="reponse-texte" name="reponses[{{ $q->id }}]" placeholder="Votre reponse..." {{ $exercice->type === 'vrai_faux' ? 'list=vraiFauxOptions' : '' }}>
                    @if ($exercice->type === 'vrai_faux')
                        <datalist id="vraiFauxOptions">
                            <option value="Vrai"><option value="Faux">
                        </datalist>
                    @endif
                @endif
            </div>
        @endforeach

        <div class="actions">
            <button type="submit" class="btn btn-success" onclick="return confirm('Soumettre votre exercice ?')">
                <i class="fa fa-check-circle"></i> Soumettre mes reponses
            </button>
        </div>
    </form>
</div>

@if ($exercice->temps_limite)
<script>
(function(){
    const total = {{ $exercice->temps_limite }};
    let remaining = total;
    const timerEl = document.getElementById('timer');
    const interval = setInterval(() => {
        remaining--;
        if (remaining <= 0) {
            clearInterval(interval);
            document.getElementById('exerciseForm').submit();
        }
        const min = Math.floor(remaining / 60);
        const sec = remaining % 60;
        timerEl.textContent = String(min).padStart(2, '0') + ':' + String(sec).padStart(2, '0');
        if (remaining < 60) timerEl.style.color = '#ef4444';
    }, 1000);
})();
</script>
@endif
<script>
function toggleSub(e){const s=e.nextElementSibling,a=e.querySelector('.arrow');s.style.display=s.style.display==='block'?'none':'block';a.classList.toggle('fa-chevron-down');a.classList.toggle('fa-chevron-up')}
function toggleSidebar(){document.getElementById('sidebar').classList.toggle('active');}
function toggleFullscreen(){const i=document.getElementById('fullscreen-icon');if(!document.fullscreenElement){document.documentElement.requestFullscreen();i.classList.replace('fa-expand','fa-compress')}else{document.exitFullscreen();i.classList.replace('fa-compress','fa-expand')}}
</script>
</body>
</html>
