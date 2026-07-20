<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Message anonyme - {{ $ecole->nom ?? 'Novaskol' }}</title>
    <link rel="stylesheet" href="{{ asset('legacy/assets/fontawesome/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('legacy/fa/css/font-awesome.min.css') }}">
    @include('modules.professeur.bulletin.partials.styles')
    <style>
        body { font-family: 'Inter', sans-serif; background: var(--bg); color: var(--text); }
        .student-wrap { margin: 88px 20px 20px 256px; max-width: 700px; }
        .hero { background: linear-gradient(135deg, var(--card), var(--surface)); border: 1px solid var(--border); border-radius: 12px; padding: 22px; box-shadow: 0 8px 24px var(--shadow-soft); margin-bottom: 18px; }
        .hero h1 { margin: 0; color: var(--primary); font-size: 1.3rem; }
        .hero p { margin: 6px 0 0; color: var(--text-sec); font-size: .85rem; }
        .card { background: var(--card); border: 1px solid var(--border); border-radius: 10px; padding: 20px; box-shadow: 0 4px 16px var(--shadow-soft); margin-bottom: 16px; }
        .card h2 { margin: 0 0 14px; color: var(--primary); font-size: 1rem; display: flex; align-items: center; gap: 8px; }
        label { display: block; margin: 10px 0 5px; color: var(--text-sec); font-size: .82rem; font-weight: 600; }
        select, textarea { width: 100%; padding: 11px; border: 1px solid var(--border); border-radius: 8px; background: var(--surface); color: var(--text); font-size: .9rem; }
        textarea { min-height: 120px; resize: vertical; }
        .btn { border: 0; border-radius: 8px; padding: 11px 18px; font-weight: 700; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; }
        .btn-primary { background: var(--primary); color: #062b1d; }
        .btn-outline { background: transparent; border: 1px solid var(--border); color: var(--text); }
        .flash { padding: 12px 16px; border-radius: 8px; margin-bottom: 16px; font-size: .9rem; display: flex; align-items: center; gap: 8px; }
        .flash-success { background: #064e3b; color: #6ee7b7; border: 1px solid #065f46; }
        .anonyme-badge { display: inline-flex; align-items: center; gap: 6px; padding: 5px 12px; border-radius: 20px; background: rgba(236,72,153,.15); color: #ec4899; font-weight: 700; font-size: .8rem; margin-bottom: 10px; }
        .history-item { padding: 12px; border: 1px solid var(--border); border-radius: 8px; background: var(--surface); margin-bottom: 8px; }
        .history-item .type { font-weight: 700; font-size: .85rem; color: var(--primary); }
        .history-item .msg { color: var(--text); margin: 4px 0; font-size: .88rem; }
        .history-item .date { color: var(--text-sec); font-size: .75rem; }
        .history-item .reponse { margin-top: 8px; padding: 8px; border-left: 3px solid var(--primary); background: rgba(0,200,83,.08); border-radius: 4px; }
        .empty-state { padding: 30px; text-align: center; color: var(--text-sec); }
        @media(max-width:1180px) { .student-wrap { margin-left: 16px; margin-right: 16px; } }
        @media(max-width:700px) { .student-wrap { margin-top: 100px; } }
    </style>
</head>
<body>
@include('modules.professeur.bulletin.partials.shell', ['activeModule' => 'eleve_anonymous'])
<header>
    <div class="header-left">
        <button class="burger-menu" onclick="toggleSidebar()"><i class="fa fa-bars"></i></button>
        <button id="fullscreen-btn" onclick="toggleFullscreen()"><i class="fa fa-expand"></i></button>
    </div>
    <div class="header-center">Message anonyme</div>
</header>

<div class="student-wrap">
    @if (session('success'))
        <div class="flash flash-success"><i class="fa fa-check-circle"></i> {{ session('success') }}</div>
    @endif

    <section class="hero">
        <div class="anonyme-badge"><i class="fa fa-user-secret"></i> 100% ANONYME</div>
        <h1>Signaler ou suggerer anonymement</h1>
        <p>Votre identite reste completement confidentielle. L'administration lira votre message sans savoir qui vous etes.</p>
    </section>

    <div class="card">
        <h2><i class="fa fa-pencil-alt"></i> Envoyer un message</h2>
        <form method="POST">
            @csrf
            <label>Type de message</label>
            <select name="type" required>
                <option value="suggestion">Suggestion / Idee</option>
                <option value="reclamation">Reclamation</option>
                <option value="harcelement">Harcelement / Intimidation</option>
                <option value="tricherie">Tricherie / Fraude</option>
                <option value="securite">Probleme de securite</option>
                <option value="autre">Autre</option>
            </select>
            <label>Votre message</label>
            <textarea name="message" required placeholder="Ecrivez votre message ici..."></textarea>
            <button class="btn btn-primary" style="margin-top:12px"><i class="fa fa-send"></i> Envoyer anonymement</button>
        </form>
    </div>

    @if($signalements->isNotEmpty())
        <div class="card">
            <h2><i class="fa fa-history"></i> Mes signalements</h2>
            @foreach($signalements as $s)
                <div class="history-item">
                    <div class="type">{{ ucfirst($s->type) }}</div>
                    <div class="msg">{{ $s->message }}</div>
                    <div class="date">{{ Carbon\Carbon::parse($s->created_at)->format('d/m/Y H:i') }} - {{ $s->traite ? 'Traite' : 'En attente' }}</div>
                    @if($s->reponse)
                        <div class="reponse"><strong>Reponse :</strong> {{ $s->reponse }}</div>
                    @endif
                </div>
            @endforeach
        </div>
    @endif
</div>
<script>
function toggleSub(e){const s=e.nextElementSibling,a=e.querySelector('.arrow');s.style.display=s.style.display==='block'?'none':'block';a.classList.toggle('fa-chevron-down');a.classList.toggle('fa-chevron-up');}
function toggleSidebar(){document.getElementById('sidebar').classList.toggle('active');}
function toggleFullscreen(){const i=document.getElementById('fullscreen-icon');if(!document.fullscreenElement){document.documentElement.requestFullscreen();i.classList.replace('fa-expand','fa-compress')}else{document.exitFullscreen();i.classList.replace('fa-compress','fa-expand')}}
</script>
</body>
</html>
