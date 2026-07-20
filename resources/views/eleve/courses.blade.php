<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Bibliotheque - Novaskol</title>
    <link rel="stylesheet" href="{{ asset('legacy/assets/fontawesome/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('legacy/fa/css/font-awesome.min.css') }}">
    @include('modules.professeur.bulletin.partials.styles')
    <style>
        .student-wrap{max-width:1100px;margin:88px 20px 20px 256px}
        :root{--blue:#3b82f6;--orange:#f59e0b;--muted:var(--text-sec);--line:var(--border);--green:var(--success);--surface2:#334155}
        .header{display:flex;align-items:center;justify-content:space-between;gap:16px;margin-bottom:24px;flex-wrap:wrap}.header h1{margin:0;font-size:1.3rem}.btn{display:inline-flex;align-items:center;gap:6px;padding:8px 14px;border-radius:8px;font-weight:600;font-size:.82rem;border:0;cursor:pointer;text-decoration:none}.btn-outline{background:transparent;border:1px solid var(--line);color:var(--text)}.btn-sm{padding:5px 10px;font-size:.75rem}.search-box{display:flex;gap:8px;margin-bottom:20px}input{flex:1;background:var(--surface);border:1px solid var(--line);border-radius:8px;padding:10px 14px;font:inherit;color:var(--text);outline:none;min-width:0}.grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(260px,1fr));gap:12px}.card{background:var(--surface);border:1px solid var(--line);border-radius:10px;padding:16px;text-decoration:none;color:var(--text);display:block;transition:border-color .15s}.card:hover{border-color:var(--blue)}.card h3{margin:0 0 4px;font-size:.95rem}.card .meta{color:var(--muted);font-size:.78rem;display:flex;gap:10px}.fav-btn{float:right;background:none;border:0;color:var(--orange);cursor:pointer;font-size:1.1rem;padding:4px}.pagination{display:flex;gap:6px;justify-content:center;margin-top:24px;flex-wrap:wrap}.pagination a,.pagination span{padding:6px 12px;border-radius:6px;background:var(--surface);border:1px solid var(--line);color:var(--text);text-decoration:none;font-size:.85rem}.pagination .active{background:var(--blue);border-color:var(--blue)}.empty{text-align:center;padding:60px 20px;color:var(--muted)}.empty i{font-size:2.5rem;opacity:.4;display:block;margin-bottom:10px}.flash{padding:10px 14px;border-radius:8px;margin-bottom:14px;font-size:.85rem}.flash-success{background:rgba(34,197,94,.12);color:var(--green);border:1px solid rgba(34,197,94,.2)}
        @media(max-width:1180px){.student-wrap{margin-left:16px;margin-right:16px}}
        @media(max-width:700px){.student-wrap{margin-top:100px}}
    </style>
</head>
<body>
@include('modules.professeur.bulletin.partials.shell', ['activeModule' => 'eleve_courses'])
<header>
    <div class="header-left">
        <button class="burger-menu" onclick="toggleSidebar()"><i class="fa fa-bars"></i></button>
        <button id="fullscreen-btn" onclick="toggleFullscreen()"><i class="fa fa-expand"></i></button>
    </div>
    <div class="header-center">Bibliotheque de cours</div>
</header>
<div class="student-wrap">
    @if (session('success'))
        <div class="flash flash-success">{{ session('success') }}</div>
    @endif

    <div class="header">
        <h1><i class="fa fa-book" style="color:var(--blue)"></i> Bibliotheque de cours</h1>
    </div>

    <form class="search-box" method="GET">
        <input name="search" placeholder="Rechercher un cours..." value="{{ $search }}">
        <button class="btn btn-outline"><i class="fa fa-search"></i></button>
    </form>

    @if ($courses->isEmpty())
        <div class="empty"><i class="fa fa-book-open"></i><p>Aucun cours trouve.</p></div>
    @else
        <div class="grid">
            @foreach ($courses as $c)
                @php $isFav = in_array($c->id, $favIds); @endphp
                <a href="{{ route('eleve.course.show', $c->id) }}" class="card">
                    <form method="POST" action="{{ route('eleve.course.favori', $c->id) }}" style="display:inline" onclick="event.stopPropagation()">
                        @csrf
                        <button class="fav-btn"><i class="fa {{ $isFav ? 'fa-star' : 'fa-star-o' }}"></i></button>
                    </form>
                    <h3>{{ $c->titre }}</h3>
                    <div class="meta">
                        <span>{{ DB::table('matieres')->where('id', $c->matiere_id)->value('nom') ?? 'General' }}</span>
                        @if ($c->niveau)<span>{{ $c->niveau }}</span>@endif
                    </div>
                </a>
            @endforeach
        </div>
        {{ $courses->links() }}
    @endif
</div>
<script>
function toggleSub(e){const s=e.nextElementSibling,a=e.querySelector('.arrow');s.style.display=s.style.display==='block'?'none':'block';a.classList.toggle('fa-chevron-down');a.classList.toggle('fa-chevron-up')}
function toggleSidebar(){document.getElementById('sidebar').classList.toggle('active');}
function toggleFullscreen(){const i=document.getElementById('fullscreen-icon');if(!document.fullscreenElement){document.documentElement.requestFullscreen();i.classList.replace('fa-expand','fa-compress')}else{document.exitFullscreen();i.classList.replace('fa-compress','fa-expand')}}
</script>
</body>
</html>
