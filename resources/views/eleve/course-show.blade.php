<!DOCTYPE html>
<html lang="fr">
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>{{ $course->titre }} - Novaskol</title>
<link rel="stylesheet" href="{{ asset('legacy/assets/fontawesome/css/all.min.css') }}">
<style>:root{--bg:#0f172a;--surface:#1e293b;--surface2:#334155;--text:#f1f5f9;--muted:#94a3b8;--line:#334155;--green:#22c55e;--blue:#3b82f6;--orange:#f59e0b}*{box-sizing:border-box}body{margin:0;font-family:Inter,system-ui,sans-serif;background:var(--bg);color:var(--text)}.wrap{max-width:900px;margin:0 auto;padding:24px 16px}.back{color:var(--muted);text-decoration:none;font-size:.85rem;display:inline-flex;align-items:center;gap:6px;margin-bottom:16px}.back:hover{color:var(--text)}.header{background:var(--surface);border:1px solid var(--line);border-radius:12px;padding:20px 24px;margin-bottom:20px}.header h1{margin:0 0 4px;font-size:1.2rem;display:flex;align-items:center;gap:10px}.header .meta{color:var(--muted);font-size:.82rem;display:flex;gap:12px;flex-wrap:wrap}.fav-btn{background:none;border:0;color:var(--orange);cursor:pointer;font-size:1.2rem;padding:0}.chapitre-card{background:var(--surface);border:1px solid var(--line);border-radius:10px;margin-bottom:10px;overflow:hidden}.ch-h{display:flex;align-items:center;justify-content:space-between;padding:12px 16px;cursor:pointer;gap:10px}.ch-h:hover{background:rgba(255,255,255,.03)}.ch-h .left{display:flex;align-items:center;gap:10px;flex:1;min-width:0}.ch-h .num{width:26px;height:26px;border-radius:50%;background:var(--surface2);display:grid;place-items:center;font-size:.78rem;font-weight:700;flex-shrink:0}.ch-h .num.done{background:var(--green)}.ch-h h3{margin:0;font-size:.9rem}.ch-content{display:none;padding:0 16px 16px;border-top:1px solid var(--line)}.ch-content.open{display:block;padding-top:12px}.fiche{display:flex;align-items:center;gap:10px;padding:6px 0;font-size:.82rem;color:var(--muted)}.fiche i{width:18px}.btn{display:inline-flex;align-items:center;gap:6px;padding:7px 12px;border-radius:6px;font-weight:600;font-size:.78rem;border:0;cursor:pointer;text-decoration:none}.btn-primary{background:var(--blue);color:#fff}.btn-success{background:var(--green);color:#fff}.btn-outline{background:transparent;border:1px solid var(--line);color:var(--text)}.btn-sm{padding:4px 8px;font-size:.72rem}.flash{padding:10px 14px;border-radius:8px;margin-bottom:14px;font-size:.85rem}.flash-success{background:rgba(34,197,94,.12);color:var(--green);border:1px solid rgba(34,197,94,.2)}</style>
</head>
<body>
<div class="wrap">
    @if (session('success'))
        <div class="flash flash-success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('eleve.portal') }}" class="back"><i class="fa fa-arrow-left"></i> Accueil</a>

    <div class="header">
        <div style="display:flex;justify-content:space-between;align-items:start;gap:12px">
            <div style="flex:1">
                <h1>{{ $course->titre }}
                    <form method="POST" action="{{ route('eleve.course.favori', $course->id) }}" style="display:inline">
                        @csrf
                        <button class="fav-btn"><i class="fa {{ $isFav ? 'fa-star' : 'fa-star-o' }}"></i></button>
                    </form>
                </h1>
                <div class="meta">
                    <span><i class="fa fa-tag"></i> {{ $matiere->nom ?? 'General' }}</span>
                    @if ($course->niveau)<span><i class="fa fa-layer-group"></i> {{ $course->niveau }}</span>@endif
                    <span><i class="fa fa-list"></i> {{ $chapitres->count() }} chapitres</span>
                </div>
            </div>
        </div>
        @if ($course->description)
            <p style="color:var(--muted);font-size:.85rem;line-height:1.5;margin:10px 0 0">{{ $course->description }}</p>
        @endif
    </div>

    @foreach ($chapitres as $i => $ch)
        <div class="chapitre-card">
            <div class="ch-h" onclick="this.nextElementSibling.classList.toggle('open')">
                <div class="left">
                    <div class="num {{ $ch->termine ? 'done' : '' }}">
                        @if ($ch->termine)<i class="fa fa-check" style="font-size:.6rem;color:#fff"></i>@else{{ $i + 1 }}@endif
                    </div>
                    <h3>{{ $ch->titre }}</h3>
                </div>
                <div style="display:flex;gap:4px;flex-shrink:0" onclick="event.stopPropagation()">
                    @if ($ch->fichiers->count())
                        <a href="{{ route('teacher.courses.fichiers.download', $ch->fichiers->first()->id) }}" class="btn btn-outline btn-sm"><i class="fa fa-download"></i></a>
                    @endif
                    <a href="{{ route('eleve.exercices.list', $ch->id) }}" class="btn btn-outline btn-sm"><i class="fa fa-puzzle-piece"></i></a>
                    @if (!$ch->termine)
                        <form method="POST" action="{{ route('eleve.chapitre.progresser', $ch->id) }}" style="display:inline">
                            @csrf
                            <button class="btn btn-success btn-sm"><i class="fa fa-check"></i> Terminer</button>
                        </form>
                    @else
                        <span style="font-size:.75rem;color:var(--green);display:flex;align-items:center;gap:4px"><i class="fa fa-check-circle"></i> Fini</span>
                    @endif
                </div>
            </div>
            <div class="ch-content">
                @if ($ch->description)
                    <p style="color:var(--muted);font-size:.82rem;margin:0 0 8px">{{ $ch->description }}</p>
                @endif
                @if ($ch->score !== null)
                    <div style="margin-bottom:8px;font-size:.82rem;color:var(--green)"><i class="fa fa-star"></i> Score exercice precedent: {{ $ch->score }}</div>
                @endif
                @if ($ch->fichiers->count())
                    <div style="margin-top:8px">
                        <strong style="font-size:.82rem;display:block;margin-bottom:4px">Fichiers du chapitre :</strong>
                        @foreach ($ch->fichiers as $f)
                            <div class="fiche">
                                @switch($f->type)
                                    @case('pdf')<i class="fa fa-file-pdf" style="color:#ef4444"></i>@break
                                    @case('video')<i class="fa fa-file-video" style="color:#8b5cf6"></i>@break
                                    @default<i class="fa fa-file" style="color:var(--blue)"></i>
                                @endswitch
                                <span style="flex:1">{{ $f->nom_original }}</span>
                                <a href="{{ route('teacher.courses.fichiers.download', $f->id) }}" class="btn btn-outline btn-sm"><i class="fa fa-download"></i></a>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    @endforeach
</div>
</body>
</html>
