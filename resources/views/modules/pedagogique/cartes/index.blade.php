<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Generation de cartes</title>
    <link rel="stylesheet" href="{{ asset('legacy/assets/fontawesome/css/all.min.css') }}">
    @include('modules.professeur.bulletin.partials.styles')
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="{{ asset('legacy/vendor/qrcode.min.js') }}"></script>
    <style>
        :root { --nv-primary: #0f2942; --nv-gold: #c9a84c; --nv-white: #ffffff; --nv-text: #1e293b; --nv-muted: #64748b; }
        body { background: var(--bg); color: var(--text); font-family: 'Inter', sans-serif; }
        .card-toolbar { display: flex; gap: 12px; flex-wrap: wrap; align-items: center; margin-bottom: 20px; }
        .card-toolbar .kaly { font-family: 'Inter', sans-serif; }
        .cards-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(340px, 1fr)); gap: 16px; margin-top: 20px; }
        .id-card {
            position: relative; display: flex; background: var(--nv-white); color: var(--nv-text);
            border-radius: 12px; overflow: hidden; border: 1px solid #e2e8f0;
            box-shadow: 0 2px 12px rgba(15,41,66,.06);
            transition: transform .15s, box-shadow .15s; font-family: 'Inter', sans-serif; min-height: 130px;
        }
        .id-card:hover { transform: translateY(-2px); box-shadow: 0 6px 24px rgba(15,41,66,.1); }
        .id-card::before {
            content: ''; position: absolute; top: 0; left: 0; width: 4px; height: 100%; background: var(--nv-gold);
        }
        .id-card.type-enseignant::before { background: #0ea5e9; }
        .id-card.type-staff::before { background: #10b981; }
        .id-photo-wrap {
            flex: 0 0 108px; display: flex; align-items: center; justify-content: center;
            padding: 12px 0 12px 12px;
        }
        .id-photo {
            width: 94px; height: 120px; object-fit: cover; border-radius: 8px;
            border: 2px solid #e2e8f0; background: #f1f5f9; display: block;
        }
        .id-body {
            flex: 1; padding: 12px 10px; display: flex; flex-direction: column;
            justify-content: center; min-width: 0;
        }
        .id-badge {
            display: inline-block; background: var(--nv-primary); color: var(--nv-white);
            border-radius: 4px; padding: 3px 12px;
            font-size: .65rem; font-weight: 700; text-transform: uppercase;
            letter-spacing: .5px; margin-bottom: 5px; width: fit-content;
        }
        .id-card.type-enseignant .id-badge { background: #0ea5e9; }
        .id-card.type-staff .id-badge { background: #10b981; }
        .id-name {
            color: var(--nv-primary) !important; margin: 0 0 2px 0;
            font-size: .9rem; font-weight: 700; line-height: 1.25;
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        }
        .id-meta { font-size: .7rem; color: var(--nv-muted); line-height: 1.55; margin: 0; }
        .id-meta strong { color: var(--nv-primary); font-weight: 600; }
        .id-school {
            display: block; margin-top: 4px; font-size: .65rem; color: var(--nv-gold);
            font-weight: 700; border-top: 1px solid #e2e8f0; padding-top: 4px;
            letter-spacing: .3px; text-transform: uppercase; line-height: 1.2;
        }
        .id-qr-wrap {
            flex: 0 0 138px; display: flex; align-items: center; justify-content: center;
            padding: 8px; background: #f8fafc; border-left: 1px solid #e2e8f0;
        }
        .id-qr-box {
            display: grid; place-items: center;
            width: 120px; height: 120px; border-radius: 8px; background: var(--nv-white);
        }
        .id-qr-box canvas, .id-qr-box img { width: 110px !important; height: 110px !important; }
        .id-expiry { font-size: .55rem; color: var(--nv-muted); text-align: center; margin-top: 3px; }
        @media print {
            @page { size: A4 landscape; margin: 8mm; }
            *,*::before,*::after { -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }
            body { background: #fff !important; margin: 0 !important; padding: 0 !important; font-family: 'Inter', sans-serif !important; }
            nav, header, footer, .novaskol-global-actions, .global-dropdown, .novaskol-loader,
            .card-toolbar, .filters-grid, .actions, .kaly { display: none !important; }
            main { padding: 0 !important; margin: 0 !important; max-width: none !important; width: auto !important; }
            .form-container { background: none !important; border: 0 !important; box-shadow: none !important; padding: 0 !important; margin: 0 !important; display: block !important; }
            .form-container > form { display: none !important; }
            .cards-grid {
                display: grid !important;
                grid-template-columns: repeat(3, 1fr) !important;
                gap: 3mm !important;
                margin: 0 !important;
                padding: 0 !important;
                width: 100% !important;
            }
            .id-card {
                break-inside: avoid !important; page-break-inside: avoid !important;
                border: 0.5mm solid #bcbcbc !important; box-shadow: none !important;
                border-radius: 2.5mm !important; min-height: 0 !important;
                width: auto !important; font-family: 'Inter', sans-serif !important;
            }
            .id-card::before { width: 1mm !important; }
            .id-photo-wrap { flex: 0 0 24mm !important; padding: 1.5mm 0 1.5mm 1.5mm !important; }
            .id-photo { width: 21mm !important; height: 27mm !important; border-radius: 1.5mm !important; border-width: 0.3mm !important; }
            .id-body { padding: 1.5mm 1mm !important; }
            .id-badge { font-size: 5pt !important; padding: 0.3mm 2mm !important; border-radius: 0.8mm !important; margin-bottom: 0.5mm !important; }
            .id-name { font-size: 7pt !important; margin-bottom: 0.2mm !important; }
            .id-meta { font-size: 5.5pt !important; line-height: 1.4 !important; }
            .id-school { font-size: 5pt !important; margin-top: 0.5mm !important; padding-top: 0.5mm !important; border-top-width: 0.3mm !important; }
            .id-qr-wrap { flex: 0 0 30mm !important; padding: 1.5mm !important; border-left-width: 0.3mm !important; }
            .id-qr-box { width: 26mm !important; height: 26mm !important; border-radius: 1mm !important; }
            .id-qr-box canvas, .id-qr-box img { width: 24mm !important; height: 24mm !important; }
            .id-expiry { font-size: 4.5pt !important; margin-top: 0.3mm !important; }
            .cards-grid > :nth-child(9n) { page-break-after: always !important; break-after: page !important; }
        }
    </style>
</head>
<body>
@include('modules.professeur.bulletin.partials.shell', ['activeModule' => $activeModule ?? 'cartes'])
<header>
    <div class="header-left">
        <button class="burger-menu" onclick="toggleSidebar()"><i class="fa fa-bars"></i></button>
        <button id="fullscreen-btn" onclick="toggleFullscreen()"><i id="fullscreen-icon" class="fa fa-expand"></i></button>
    </div>
    <div class="header-center"><i class="fa fa-id-card"></i> Generation de cartes</div>
</header>
<main>
    <div class="form-container">
        <form method="POST" action="{{ route('modules.cartes') }}">
            @csrf
            <div class="filters-grid">
                <div>
                    <label>Type</label>
                    <select name="type" onchange="this.form.submit()">
                        <option value="">Choisir</option>
                        <option value="etudiant" @selected($selectedType==='etudiant')>Eleves</option>
                        <option value="enseignant" @selected($selectedType==='enseignant')>Enseignants</option>
                        <option value="staff" @selected($selectedType==='staff')>Staff</option>
                    </select>
                </div>
                <div>
                    <label>Annee</label>
                    <select name="annee">
                        @foreach($annees as $annee)
                            <option value="{{ $annee }}" @selected($selectedAnnee===$annee)>{{ $annee }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label>Classe</label>
                    <select name="classe" @disabled($selectedType!=='etudiant')>
                        <option value="">Toutes</option>
                        @foreach($classes as $classe)
                            <option value="{{ $classe->id }}" @selected($selectedClasse===$classe->id)>{{ $classe->nom }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="card-toolbar">
                <button class="kaly"><i class="fa fa-id-card"></i> Generer</button>
                <button type="button" class="kaly" onclick="window.print()"><i class="fa fa-print"></i> Imprimer (3x3/A4)</button>
                <a href="{{ route('modules.cartes.connecte') }}?type={{ $selectedType }}&classe={{ $selectedClasse }}" class="kaly" style="background:#0f2942 !important"><i class="fa fa-mobile"></i> Connecte</a>
            </div>
        </form>

        <div class="cards-grid">
            @foreach($people as $person)
                @php
                    $photo = $person->photo ?: 'Uploads/default.jpg';
                    $qrToken = $person->qr_token ?? '';
                    $typeClass = $selectedType;
                    $statusLabel = $selectedType === 'etudiant' ? 'Eleve' : ucfirst($selectedType);
                    $deptInfo = '';
                    $deptLabel = '';
                    if ($selectedType === 'etudiant') {
                        $deptInfo = $person->nom_classe ?? '';
                        $deptLabel = 'Classe';
                    } elseif ($selectedType === 'enseignant') {
                        $deptInfo = $person->nom_matiere ?? '';
                        $deptLabel = 'Matiere';
                    } elseif ($selectedType === 'staff') {
                        $deptInfo = $person->nom_departement ?? $person->poste ?? 'Staff';
                        $deptLabel = 'Fonction';
                    }
                    $expiryDate = now()->addYear()->format('d/m/Y');
                @endphp
                <article class="id-card type-{{ $typeClass }}">
                    <div class="id-photo-wrap">
                        <img class="id-photo" src="{{ asset('legacy/'.ltrim($photo,'/')) }}" alt="" loading="lazy">
                    </div>
                    <div class="id-body">
                        <span class="id-badge">{{ $statusLabel }}</span>
                        <h3 class="id-name" title="{{ $person->nom }} {{ $person->prenom }}">{{ $person->nom }} {{ $person->prenom }}</h3>
                        <p class="id-meta">
                            <strong>ID :</strong> {{ $person->matricule ?? $person->id }}<br>
                            @if($deptInfo)<strong>{{ $deptLabel }} :</strong> {{ $deptInfo }}<br>@endif
                            <strong>Annee :</strong> {{ $person->annee_scolaire }}
                        </p>
                        <span class="id-school">{{ $ecole->nom ?? 'NOVASKOL' }}</span>
                    </div>
                    <div class="id-qr-wrap">
                        @if($qrToken)
                            <div>
                                <div class="id-qr-box" id="qr-{{ $person->id }}" data-qr="novaskol:qr:v1:{{ $qrToken }}"></div>
                                <div class="id-expiry">Exp: {{ $expiryDate }}</div>
                            </div>
                        @endif
                    </div>
                </article>
            @endforeach
        </div>

        @if(request()->isMethod('post') && $people->isEmpty())
            <p style="text-align:center;color:var(--text-sec);margin-top:20px">Aucune donnee trouvee pour ces criteres.</p>
        @endif
    </div>
    <footer>&copy; {{ date('Y') }} {{ $ecole->nom ?? 'NOVASKOL' }}. Tous droits reserves.</footer>
</main>
<script>
function toggleSub(e){const s=e.nextElementSibling,a=e.querySelector('.arrow');s.style.display=s.style.display==='block'?'none':'block';a.classList.toggle('fa-chevron-down');a.classList.toggle('fa-chevron-up')}
function toggleSidebar(){document.getElementById('sidebar').classList.toggle('active');document.querySelector('main').classList.toggle('full-width');document.querySelector('header').classList.toggle('full-width')}
function toggleFullscreen(){const i=document.getElementById('fullscreen-icon');if(!document.fullscreenElement){document.documentElement.requestFullscreen();i.classList.replace('fa-expand','fa-compress')}else{document.exitFullscreen();i.classList.replace('fa-compress','fa-expand')}}
document.addEventListener('DOMContentLoaded',()=>{
    const a=document.querySelector('nav a.active'),s=a?.closest('.sub-menu');
    if(s){s.style.display='block';s.previousElementSibling?.querySelector('.arrow')?.classList.replace('fa-chevron-down','fa-chevron-up')}
    document.querySelectorAll('.id-qr-box').forEach(function(q){
        const u=q.getAttribute('data-qr');
        if(u&&window.QRCode)new QRCode(q,{text:u,width:96,height:96,colorDark:'#0f2942',colorLight:'#ffffff',correctLevel:QRCode.CorrectLevel.H})
    })
})
</script>
</body>
</html>