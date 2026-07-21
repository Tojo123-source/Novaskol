<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Generation de cartes</title>
    <link rel="stylesheet" href="{{ asset('legacy/assets/fontawesome/css/all.min.css') }}">
    @include('modules.professeur.bulletin.partials.styles')
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="{{ asset('legacy/vendor/qrcode.min.js') }}"></script>
    <style>
        :root {
            --card-bg: linear-gradient(145deg, #0f1a2e 0%, #1a2a44 100%);
            --card-bg-fallback: #0f1a2e;
            --card-border: rgba(201, 168, 76, 0.3);
            --card-text: #f0f4ff;
            --card-text-sec: #a0b4d0;
            --card-gold: #c9a84c;
            --card-accent-etudiant: #3b82f6;
            --card-accent-enseignant: #0ea5e9;
            --card-accent-staff: #10b981;
        }
        body { background: var(--bg); color: var(--text); font-family: 'Inter', sans-serif; }
        .card-toolbar { display: flex; gap: 12px; flex-wrap: wrap; align-items: center; margin-bottom: 20px; }
        .card-toolbar .kaly { font-family: 'Inter', sans-serif; }
        .cards-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(210px, 1fr));
            gap: 20px;
            margin-top: 20px;
            padding: 10px;
        }
        .id-card {
            position: relative;
            display: flex;
            flex-direction: column;
            background: var(--card-bg-fallback);
            background: var(--card-bg);
            color: var(--card-text);
            border-radius: 12px;
            border: 1px solid var(--card-border);
            box-shadow: 0 4px 20px rgba(0,0,0,0.25), 0 1px 3px rgba(0,0,0,0.15);
            overflow: hidden;
            font-family: 'Inter', sans-serif;
            transition: transform .2s, box-shadow .2s;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        .id-card:hover { transform: translateY(-3px); box-shadow: 0 8px 32px rgba(0,0,0,0.35); }
        .id-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 3px;
            background: var(--card-gold);
        }
        .id-card.type-enseignant::before { background: var(--card-accent-enseignant); }
        .id-card.type-staff::before { background: var(--card-accent-staff); }
        .id-card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 12px 6px;
        }
        .id-card-header .logo-mark {
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: .7rem;
            font-weight: 800;
            color: var(--card-gold);
            letter-spacing: .5px;
            text-transform: uppercase;
        }
        .id-card-header .logo-mark i { font-size: .82rem; }
        .id-card-header .school-name {
            font-size: .55rem;
            font-weight: 700;
            color: var(--card-text-sec);
            text-align: right;
            line-height: 1.25;
            text-transform: uppercase;
            letter-spacing: .3px;
        }
        .id-photo-section {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 4px 12px 2px;
        }
        .id-photo-wrap {
            position: relative;
            width: 68px;
            height: 68px;
            border-radius: 50%;
            border: 2.5px solid var(--card-gold);
            overflow: hidden;
            background: rgba(255,255,255,0.08);
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .id-card.type-enseignant .id-photo-wrap { border-color: var(--card-accent-enseignant); }
        .id-card.type-staff .id-photo-wrap { border-color: var(--card-accent-staff); }
        .id-photo {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }
        .id-badge-wrap {
            display: flex;
            justify-content: center;
            padding: 3px 12px 2px;
        }
        .id-badge {
            display: inline-block;
            background: var(--card-gold);
            color: #0f1a2e;
            border-radius: 20px;
            padding: 2px 14px;
            font-size: .55rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .8px;
        }
        .id-card.type-enseignant .id-badge { background: var(--card-accent-enseignant); color: #fff; }
        .id-card.type-staff .id-badge { background: var(--card-accent-staff); color: #fff; }
        .id-body {
            flex: 1;
            padding: 4px 12px 8px;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }
        .id-name {
            color: #fff !important;
            margin: 0 0 1px 0;
            font-size: .82rem;
            font-weight: 700;
            line-height: 1.2;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            max-width: 100%;
        }
        .id-meta {
            font-size: .6rem;
            color: var(--card-text-sec);
            line-height: 1.5;
            margin: 0;
            text-align: center;
        }
        .id-meta strong { color: var(--card-gold); font-weight: 600; }
        .id-card.type-enseignant .id-meta strong { color: var(--card-accent-enseignant); }
        .id-card.type-staff .id-meta strong { color: var(--card-accent-staff); }
        .id-school {
            display: block;
            margin-top: 3px;
            font-size: .5rem;
            color: var(--card-text-sec);
            font-weight: 600;
            letter-spacing: .5px;
            text-transform: uppercase;
            line-height: 1.2;
            opacity: .7;
        }
        .id-footer {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            padding: 4px 10px 8px;
            border-top: 1px solid rgba(255,255,255,0.06);
            margin-top: auto;
        }
        .id-signature {
            font-size: .45rem;
            color: var(--card-text-sec);
            opacity: .6;
            text-align: left;
            font-style: italic;
        }
        .id-qr-section {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .id-qr-box {
            width: 44px;
            height: 44px;
            border-radius: 4px;
            background: rgba(255,255,255,0.95);
            display: grid;
            place-items: center;
        }
        .id-qr-box canvas, .id-qr-box img { width: 40px !important; height: 40px !important; }
        .id-expiry {
            font-size: .4rem;
            color: var(--card-text-sec);
            opacity: .7;
            margin-top: 1px;
            text-align: center;
        }
        @media print {
            @page { size: A4 portrait; margin: 10mm; }
            *,*::before,*::after { -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }
            body { background: #fff !important; margin: 0 !important; padding: 0 !important; }
            nav, header, footer, .card-toolbar, .filters-grid, .kaly, .no-print { display: none !important; }
            main { padding: 0 !important; margin: 0 !important; max-width: none !important; width: auto !important; }
            .form-container { background: none !important; border: 0 !important; box-shadow: none !important; padding: 0 !important; margin: 0 !important; display: block !important; }
            .form-container > form { display: none !important; }
            .cards-grid {
                display: grid !important;
                grid-template-columns: repeat(3, 54mm) !important;
                gap: 20px !important;
                margin: 0 auto !important;
                padding: 0 !important;
                justify-content: center !important;
                width: fit-content !important;
            }
            .id-card {
                break-inside: avoid !important;
                page-break-inside: avoid !important;
                border: 0.3mm solid rgba(201,168,76,0.4) !important;
                box-shadow: none !important;
                border-radius: 2.5mm !important;
                width: 54mm !important;
                height: 85.6mm !important;
                background: linear-gradient(145deg, #0f1a2e 0%, #1a2a44 100%) !important;
                display: flex !important;
                flex-direction: column !important;
            }
            .id-card::before { height: 0.5mm !important; }
            .id-card-header { padding: 2.5mm 2.5mm 1.5mm !important; }
            .id-card-header .logo-mark { font-size: 5pt !important; }
            .id-card-header .logo-mark i { font-size: 6pt !important; }
            .id-card-header .school-name { font-size: 4pt !important; }
            .id-photo-section { padding: 1.5mm 2.5mm 1mm !important; }
            .id-photo-wrap { width: 16mm !important; height: 16mm !important; border-width: 0.5mm !important; border-radius: 50% !important; }
            .id-badge-wrap { padding: 1mm 2.5mm 0.5mm !important; }
            .id-badge { font-size: 4.5pt !important; padding: 0.3mm 3mm !important; border-radius: 3mm !important; }
            .id-body { padding: 1.5mm 2.5mm 2mm !important; }
            .id-name { font-size: 6.5pt !important; white-space: normal !important; overflow: visible !important; text-overflow: clip !important; }
            .id-meta { font-size: 5pt !important; line-height: 1.4 !important; }
            .id-school { font-size: 4pt !important; margin-top: 0.5mm !important; }
            .id-footer { padding: 1.5mm 2.5mm 2mm !important; border-top-width: 0.3mm !important; }
            .id-signature { font-size: 4pt !important; }
            .id-qr-box { width: 12mm !important; height: 12mm !important; border-radius: 0.5mm !important; }
            .id-qr-box canvas, .id-qr-box img { width: 10.5mm !important; height: 10.5mm !important; }
            .id-expiry { font-size: 3.5pt !important; }
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
                    $statusPrint = $selectedType === 'etudiant' ? 'ELEVE' : strtoupper($selectedType);
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
                    <div class="id-card-header">
                        <div class="logo-mark"><i class="fa fa-graduation-cap"></i> Novaskol</div>
                        <div class="school-name">{{ $ecole->nom ?? 'LYCEE NOVASKOL' }}</div>
                    </div>
                    <div class="id-photo-section">
                        <div class="id-photo-wrap">
                            <img class="id-photo" src="{{ asset('legacy/'.ltrim($photo,'/')) }}" alt="" loading="lazy">
                        </div>
                    </div>
                    <div class="id-badge-wrap">
                        <span class="id-badge">{{ $statusPrint }}</span>
                    </div>
                    <div class="id-body">
                        <h3 class="id-name" title="{{ $person->nom }} {{ $person->prenom }}">{{ $person->nom }} {{ $person->prenom }}</h3>
                        <p class="id-meta">
                            <strong>ID :</strong> {{ $person->matricule ?? $person->id }}<br>
                            @if($deptInfo)<strong>{{ $deptLabel }} :</strong> {{ $deptInfo }}<br>@endif
                            <strong>Annee :</strong> {{ $person->annee_scolaire ?? $selectedAnnee }}
                        </p>
                        <span class="id-school">{{ $ecole->nom ?? 'NOVASKOL' }}</span>
                    </div>
                    <div class="id-footer">
                        <div class="id-signature">Signature</div>
                        <div class="id-qr-section">
                            @if($qrToken)
                                <div class="id-qr-box" id="qr-{{ $person->id }}" data-qr="novaskol:qr:v1:{{ $qrToken }}"></div>
                                <div class="id-expiry">Exp: {{ $expiryDate }}</div>
                            @else
                                <div style="font-size:.45rem;color:var(--card-text-sec);opacity:.6">QR non disp.</div>
                            @endif
                        </div>
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
        if(u&&window.QRCode)new QRCode(q,{text:u,width:80,height:80,colorDark:'#ffffff',colorLight:'transparent',correctLevel:QRCode.CorrectLevel.H})
    })
})
</script>
</body>
</html>
