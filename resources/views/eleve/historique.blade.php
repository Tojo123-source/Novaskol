<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Historique - Novaskol</title>
    <link rel="stylesheet" href="{{ asset('legacy/assets/fontawesome/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('legacy/fa/css/font-awesome.min.css') }}">
    @include('modules.professeur.bulletin.partials.styles')
    <style>
        .student-wrap{max-width:800px;margin:88px 20px 20px 256px}
        :root{--blue:#3b82f6;--muted:var(--text-sec);--line:var(--border);--green:var(--success)}
        h1{font-size:1.15rem;margin:0 0 20px}.section{margin-bottom:28px}.section h2{font-size:.95rem;margin:0 0 10px;display:flex;align-items:center;gap:8px;color:var(--muted)}.item{background:var(--surface);border:1px solid var(--line);border-radius:8px;padding:10px 14px;margin-bottom:6px;display:flex;justify-content:space-between;align-items:center;font-size:.85rem}.item .date{color:var(--muted);font-size:.78rem}.btn{display:inline-flex;align-items:center;gap:6px;padding:4px 10px;border-radius:6px;font-size:.75rem;border:0;cursor:pointer;text-decoration:none;font-weight:600}.btn-outline{background:transparent;border:1px solid var(--line);color:var(--text)}.empty{text-align:center;padding:40px;color:var(--muted);font-size:.85rem}.pagination{display:flex;gap:6px;justify-content:center;margin-top:16px;flex-wrap:wrap}.pagination a,.pagination span{padding:5px 10px;border-radius:6px;background:var(--surface);border:1px solid var(--line);color:var(--text);text-decoration:none;font-size:.82rem}
        @media(max-width:1180px){.student-wrap{margin-left:16px;margin-right:16px}}
        @media(max-width:700px){.student-wrap{margin-top:100px}}
    </style>
</head>
<body>
@include('modules.professeur.bulletin.partials.shell', ['activeModule' => 'eleve_historique'])
<header>
    <div class="header-left">
        <button class="burger-menu" onclick="toggleSidebar()"><i class="fa fa-bars"></i></button>
        <button id="fullscreen-btn" onclick="toggleFullscreen()"><i class="fa fa-expand"></i></button>
    </div>
    <div class="header-center">Mon historique</div>
</header>
<div class="student-wrap">
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
<script>
function toggleSub(e){const s=e.nextElementSibling,a=e.querySelector('.arrow');s.style.display=s.style.display==='block'?'none':'block';a.classList.toggle('fa-chevron-down');a.classList.toggle('fa-chevron-up')}
function toggleSidebar(){document.getElementById('sidebar').classList.toggle('active');}
function toggleFullscreen(){const i=document.getElementById('fullscreen-icon');if(!document.fullscreenElement){document.documentElement.requestFullscreen();i.classList.replace('fa-expand','fa-compress')}else{document.exitFullscreen();i.classList.replace('fa-compress','fa-expand')}}
</script>
</body>
</html>
