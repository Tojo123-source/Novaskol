<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Exercices - {{ $ch->titre }}</title>
    <link rel="stylesheet" href="{{ asset('legacy/assets/fontawesome/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('legacy/fa/css/font-awesome.min.css') }}">
    @include('modules.professeur.bulletin.partials.styles')
    <style>
        .student-wrap{max-width:700px;margin:88px 20px 20px 256px}
        :root{--blue:#3b82f6;--orange:#f59e0b;--muted:var(--text-sec);--line:var(--border);--green:var(--success)}
        h1{font-size:1.2rem;margin:0 0 16px}.card{background:var(--surface);border:1px solid var(--line);border-radius:10px;padding:16px;margin-bottom:10px;display:flex;justify-content:space-between;align-items:center;gap:12px}.card h3{margin:0 0 2px;font-size:.92rem}.card .meta{color:var(--muted);font-size:.78rem}.btn{display:inline-flex;align-items:center;gap:6px;padding:7px 14px;border-radius:6px;font-weight:600;font-size:.82rem;border:0;cursor:pointer;text-decoration:none}.btn-primary{background:var(--blue);color:#fff}.btn-success{background:var(--green);color:#fff}.btn-outline{background:transparent;border:1px solid var(--line);color:var(--text)}.btn-sm{padding:4px 8px;font-size:.72rem}.badge{padding:2px 8px;border-radius:10px;font-size:.72rem;background:var(--line);color:var(--muted)}.badge-success{background:rgba(34,197,94,.15);color:var(--green)}.flash{padding:10px 14px;border-radius:8px;margin-bottom:14px;font-size:.85rem}.flash-success{background:rgba(34,197,94,.12);color:var(--green);border:1px solid rgba(34,197,94,.2)}.empty{text-align:center;padding:40px;color:var(--muted)}.empty i{font-size:2rem;opacity:.4;display:block;margin-bottom:8px}
        @media(max-width:1180px){.student-wrap{margin-left:16px;margin-right:16px}}
        @media(max-width:700px){.student-wrap{margin-top:100px}}
    </style>
</head>
<body>
@include('modules.professeur.bulletin.partials.shell', ['activeModule' => 'eleve_exercices'])
<header>
    <div class="header-left">
        <button class="burger-menu" onclick="toggleSidebar()"><i class="fa fa-bars"></i></button>
        <button id="fullscreen-btn" onclick="toggleFullscreen()"><i class="fa fa-expand"></i></button>
    </div>
    <div class="header-center">Exercices - {{ $ch->titre }}</div>
</header>
<div class="student-wrap">
    @if (session('success'))
        <div class="flash flash-success">{{ session('success') }}</div>
    @endif

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
<script>
function toggleSub(e){const s=e.nextElementSibling,a=e.querySelector('.arrow');s.style.display=s.style.display==='block'?'none':'block';a.classList.toggle('fa-chevron-down');a.classList.toggle('fa-chevron-up')}
function toggleSidebar(){document.getElementById('sidebar').classList.toggle('active');}
function toggleFullscreen(){const i=document.getElementById('fullscreen-icon');if(!document.fullscreenElement){document.documentElement.requestFullscreen();i.classList.replace('fa-expand','fa-compress')}else{document.exitFullscreen();i.classList.replace('fa-compress','fa-expand')}}
</script>
</body>
</html>
