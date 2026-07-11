<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Pointage' }} - {{ $ecole->nom ?? 'Ecole' }}</title>
    @php $_tz = config('app.scan_timezone', 'Indian/Antananarivo'); if(!$_tz||$_tz==='UTC')$_tz='Indian/Antananarivo'; @endphp
    <link rel="stylesheet" href="{{ asset('legacy/assets/fontawesome/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('legacy/fa/css/font-awesome.min.css') }}">
    @include('modules.professeur.bulletin.partials.styles')
    <script src="{{ asset('legacy/vendor/qrcode.min.js') }}"></script>
    <script src="{{ asset('legacy/vendor/jsQR.js') }}"></script>
    <style>
        .presence-wrapper { max-width:1100px; margin:0 auto; }
        .presence-main { min-width:0; }
        .top-bar { display:flex; align-items:center; justify-content:space-between; gap:12px; margin-bottom:12px; }
        .top-bar h2 { margin:0; font-size:1.05rem; }
        .type-filter { display:flex; gap:6px; }
        .type-filter button { padding:7px 14px; border-radius:8px; border:1px solid var(--border); background:var(--surface); color:var(--text-sec); cursor:pointer; font-weight:600; font-size:.8rem; transition:all .15s; }
        .type-filter button.active { background:var(--primary); color:#fff; border-color:var(--primary); }
        .type-filter button:hover { border-color:var(--primary); }
        .filter-bar { background:var(--card); border:1px solid var(--border); border-radius:10px; padding:10px 14px; margin-bottom:14px; }
        .filter-row { display:flex; gap:12px; align-items:end; flex-wrap:wrap; }
        .filter-row label { display:block; font-size:.72rem; color:var(--text-sec); margin-bottom:3px; }
        .filter-row select, .filter-row input { padding:6px 10px; border-radius:6px; border:1px solid var(--border); background:var(--surface); color:var(--text); font-size:.82rem; }
        .filter-row select { cursor:pointer; }
        .scan-row { display:grid; grid-template-columns:1fr 1fr; gap:14px; margin-bottom:14px; }
        @media (max-width:800px) { .scan-row { grid-template-columns:1fr; } }
        .scan-panel { background:var(--card); border:1px solid var(--border); border-radius:12px; padding:14px; text-align:center; }
        .scan-panel .preview-box { max-width:380px; margin:0 auto; aspect-ratio:4/3; background:#0a0f1a; border-radius:8px; position:relative; overflow:hidden; }
        .scan-panel .preview-box video { width:100%; height:100%; object-fit:cover; display:block; }
        .qr-placeholder { position:absolute; inset:0; display:flex; flex-direction:column; align-items:center; justify-content:center; gap:8px; color:#555; font-size:.85rem; z-index:1; }
        .qr-placeholder i { font-size:2.4rem; opacity:.35; }
        .scan-btns { display:flex; gap:8px; justify-content:center; margin-top:10px; }
        .scan-btns button { margin:0; padding:9px 16px; font-size:.85rem; }
        .scan-overlay { position:absolute; inset:0; pointer-events:none; opacity:0; transition:opacity .3s; z-index:2; }
        .scan-overlay.active { opacity:1; }
        .scan-corner { position:absolute; width:26px; height:26px; border-color:#475569; border-style:solid; transition:border-color .3s; }
        .scan-overlay.active .scan-corner { border-color:#00c853; }
        .scan-corner.tl{top:8px;left:8px;border-width:3px 0 0 3px;border-radius:3px 0 0 0}
        .scan-corner.tr{top:8px;right:8px;border-width:3px 3px 0 0;border-radius:0 3px 0 0}
        .scan-corner.bl{bottom:8px;left:8px;border-width:0 0 3px 3px;border-radius:0 0 0 3px}
        .scan-corner.br{bottom:8px;right:8px;border-width:0 3px 3px 0;border-radius:0 0 3px 0}
        .scan-line { position:absolute; left:12%; right:12%; height:2px; background:linear-gradient(90deg,transparent,#00c853,transparent); box-shadow:0 0 10px #00c853,0 0 20px rgba(0,200,83,.3); top:5%; opacity:0; transition:opacity .3s; z-index:3; }
        .scan-overlay.active .scan-line { opacity:1; animation:scanMove 2s ease-in-out infinite; }
        @keyframes scanMove{0%{top:5%}50%{top:95%}100%{top:5%}}
        .scan-flash-overlay { position:absolute; inset:0; border-radius:8px; pointer-events:none; z-index:4; opacity:0; transition:opacity 0s; }
        .scan-flash-overlay.green { background:rgba(0,200,83,.35); box-shadow:inset 0 0 80px rgba(0,200,83,.5); animation:flashGreen .45s ease; }
        .scan-flash-overlay.red { background:rgba(239,68,68,.3); box-shadow:inset 0 0 80px rgba(239,68,68,.4); animation:flashGreen .45s ease; }
        @keyframes flashGreen{0%{opacity:0}15%{opacity:1}100%{opacity:0}}
        .scan-toast { display:none; padding:9px 14px; border-radius:8px; text-align:center; font-weight:700; font-size:.82rem; margin-top:8px; }
        .scan-toast.success { display:block; background:rgba(0,200,83,.12); color:#00c853; border:1px solid rgba(0,200,83,.3); }
        .scan-toast.error { display:block; background:rgba(255,23,68,.12); color:#ff1744; border:1px solid rgba(255,23,68,.3); }
        .scan-toast.info { display:block; background:rgba(56,189,248,.12); color:#38bdf8; border:1px solid rgba(56,189,248,.3); }
        .person-card { background:var(--card); border:1px solid var(--border); border-radius:12px; padding:14px; display:none; animation:resultPop .3s ease; }
        .person-card.active { display:block; }
        @keyframes resultPop{0%{transform:scale(.92);opacity:0}100%{transform:scale(1);opacity:1}}
        .person-card .pc-header { display:flex; gap:12px; align-items:center; }
        .person-card .pc-photo { width:60px; height:60px; border-radius:50%; object-fit:cover; background:var(--surface); flex-shrink:0; border:2px solid var(--primary); }
        .person-card .pc-name { font-weight:700; font-size:.95rem; color:var(--text); }
        .person-card .pc-meta { font-size:.75rem; color:var(--text-sec); }
        .person-card .pc-status { display:inline-block; padding:2px 10px; border-radius:5px; font-size:.7rem; font-weight:700; margin-top:4px; }
        .pc-status.entree { background:rgba(0,200,83,.18); color:#00c853; }
        .pc-status.sortie { background:rgba(239,68,68,.18); color:#ef4444; }
        .pc-status.retard { background:rgba(255,183,0,.18); color:#ffb700; }
        .pc-status.present { background:rgba(0,200,83,.12); color:#00c853; }
        .person-card .pc-edt { margin-top:8px; font-size:.75rem; color:var(--text-sec); }
        .person-card .pc-edt span { display:inline-block; background:var(--surface); border:1px solid var(--border); border-radius:4px; padding:2px 8px; margin:2px; }
        .person-card .pc-detail { display:grid; grid-template-columns:1fr 1fr; gap:5px; margin-top:8px; font-size:.75rem; }
        .person-card .pc-detail-item { background:var(--surface); border:1px solid var(--border); border-radius:6px; padding:5px 8px; }
        .person-card .pc-detail-item strong { display:block; color:var(--text-sec); font-size:.62rem; text-transform:uppercase; margin-bottom:2px; }
        .person-card .pc-detail-item span { color:var(--text); font-weight:600; }
        .person-card .pc-tags { margin-top:6px; display:flex; gap:6px; flex-wrap:wrap; }
        .add-form { background:var(--card); border:1px solid var(--border); border-radius:12px; padding:14px; }
        .add-form h4 { margin:0 0 10px; font-size:.88rem; color:var(--text-sec); }
        .add-form h4 i { margin-right:5px; }
        .add-form .form-row { display:grid; grid-template-columns:1fr 1fr; gap:8px; margin-bottom:6px; }
        .add-form .form-row.three { grid-template-columns:1fr 1fr 1fr; }
        @media (max-width:600px) { .add-form .form-row, .add-form .form-row.three { grid-template-columns:1fr; } }
        .add-form label { display:block; font-size:.7rem; color:var(--text-sec); margin-bottom:2px; }
        .add-form select, .add-form input { width:100%; padding:6px 8px; border-radius:6px; border:1px solid var(--border); background:var(--surface); color:var(--text); font-size:.82rem; box-sizing:border-box; }
        .add-form .add-btn { width:100%; margin:0; padding:7px; font-size:.82rem; }
        .scan-badge { display:inline-block; padding:2px 8px; border-radius:4px; font-size:.68rem; font-weight:600; }
        .scan-badge.entree { background:#00c85322; color:#00c853; }
        .scan-badge.sortie { background:#ef444422; color:#ef4444; }
        .scan-badge.retard { background:#ffb70022; color:#ffb700; }
        .scan-badge.present { background:#00c85322; color:#00c853; }
        .history-section { background:var(--card); border:1px solid var(--border); border-radius:12px; padding:16px; }
        .history-section h2 { font-size:1rem; margin:0 0 10px; }
        .hist-count { font-weight:400; color:var(--text-sec); font-size:.82rem; }
        .hist-scroll { max-height:150px; overflow-y:auto; margin-bottom:10px; }
        .history-item { display:flex; align-items:center; gap:10px; padding:6px 10px; border-radius:6px; margin-bottom:3px; background:var(--surface); font-size:.8rem; }
        .history-item .hist-icon { width:24px; height:24px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:11px; flex-shrink:0; background:rgba(0,200,83,.12); color:#00c853; }
        .history-item .hist-name { flex:1; font-weight:600; font-size:.78rem; }
        .history-item .hist-type { font-size:.63rem; }
        .history-item .hist-time { color:var(--text-sec); font-size:.7rem; }
        .hist-empty { text-align:center; color:var(--text-sec); padding:14px; font-size:.85rem; }
        .scan-history { max-height:380px; overflow-y:auto; }
        .scan-history table { width:100%; border-collapse:collapse; font-size:.78rem; }
        .scan-history th, .scan-history td { padding:7px; text-align:left; border-bottom:1px solid var(--border); }
        .scan-history th { position:sticky; top:0; background:var(--card); color:var(--text-sec); font-weight:600; }
    @media print {
        @page { size:A4 landscape; margin:8mm; }
        *,*::before,*::after { -webkit-print-color-adjust:exact!important; print-color-adjust:exact!important; box-shadow:none!important; text-shadow:none!important; }
        html,body { background:white!important; color:#111!important; margin:0!important; padding:0!important; font-size:10pt!important; }
        nav,header,footer,.burger-menu,#fullscreen-btn,.top-bar,.scan-row,.filter-bar,.add-form,.person-card,.scan-panel,.presence-wrapper{display:none!important;}
        main { margin:0!important; padding:0!important; background:white!important; width:100%!important; }
        .novaskol-global-actions,.global-dropdown,.novaskol-loader { display:none!important; }
        .history-section { display:block!important; margin:0!important; padding:0!important; background:white!important; border:none!important; }
        .history-section h2 { font-size:16pt!important; color:#047857!important; text-align:center!important; margin-bottom:12px!important; }
        .hist-scroll { max-height:none!important; overflow:visible!important; }
        .history-item { border:1px solid #ddd!important; background:white!important; margin-bottom:4px!important; page-break-inside:avoid!important; }
        .scan-history { max-height:none!important; overflow:visible!important; }
        .scan-history table { font-size:9pt!important; }
        .scan-history th { background:#047857!important; color:white!important; }
        .scan-history td,.scan-history th { border:1px solid #555!important; padding:6px 8px!important; color:#111!important; }
        .scan-history tr:nth-child(even) td { background:#f5f8fc!important; }
        .scan-badge { -webkit-print-color-adjust:exact!important; print-color-adjust:exact!important; }
        .scan-badge.entree { background:#00c85322!important; color:#00c853!important; }
        .scan-badge.sortie { background:#ef444422!important; color:#ef4444!important; }
        .scan-badge.retard { background:#ffb70022!important; color:#ffb700!important; }
        .scan-badge.present { background:#00c85322!important; color:#00c853!important; }
    }
    </style>
</head>
<body>
@include('modules.professeur.bulletin.partials.shell', ['activeModule' => $isUnified ? 'pointage' : ($type === 'teacher' ? 'presence' : 'presence_staff')])
<header>
    <div class="header-left">
        <button class="burger-menu" onclick="toggleSidebar()"><i class="fa fa-bars"></i></button>
        <button id="fullscreen-btn" onclick="toggleFullscreen()"><i id="fullscreen-icon" class="fa fa-expand"></i></button>
    </div>
    <h1><i class="fa fa-qrcode"></i> {{ $title ?? 'Pointage' }}</h1>
</header>
<main>
    <div class="presence-wrapper">
        <div class="presence-main">
            <div class="top-bar">
                <h2><i class="fa fa-qrcode"></i> Scanneur QR</h2>
                @if($isUnified)
                <div class="type-filter">
                    <button class="{{ $type === 'all' ? 'active' : '' }}" data-type="all">Tous</button>
                    <button class="{{ $type === 'teacher' ? 'active' : '' }}" data-type="teacher">Enseignants</button>
                    <button class="{{ $type === 'staff' ? 'active' : '' }}" data-type="staff">Staff</button>
                </div>
                @endif
            </div>

            <div class="filter-bar">
                <form method="GET" action="{{ $isUnified ? route('modules.pointage') : route($routePrefix) }}">
                    @if($isUnified)
                    <input type="hidden" name="type" value="{{ $type }}">
                    @endif
                    <div class="filter-row">
                        <div>
                            <label>Annee</label>
                            <select name="annee_scolaire" onchange="this.form.submit()">
                                @foreach($annees as $a)
                                    <option value="{{ $a }}" @selected($annee===$a)>{{ $a }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label>Mois</label>
                            <select name="mois" onchange="this.form.submit()">
                                @foreach(['01'=>'Janvier','02'=>'Fevrier','03'=>'Mars','04'=>'Avril','05'=>'Mai','06'=>'Juin','07'=>'Juillet','08'=>'Aout','09'=>'Septembre','10'=>'Octobre','11'=>'Novembre','12'=>'Decembre'] as $v=>$l)
                                    <option value="{{ $v }}" @selected($mois===$v)>{{ $l }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label>Jour</label>
                            <input type="date" name="jour" value="{{ $jour }}" onchange="this.form.submit()">
                        </div>
                    </div>
                </form>
            </div>

            <div class="scan-row">
                <div class="scan-panel">
                    <div class="preview-box" id="qrPreview">
                        <video id="qrVideo" autoplay playsinline muted></video>
                        <canvas id="qrCanvas" style="display:none;"></canvas>
                        <div class="scan-overlay" id="scanOverlay">
                            <div class="scan-corner tl"></div><div class="scan-corner tr"></div>
                            <div class="scan-corner bl"></div><div class="scan-corner br"></div>
                            <div class="scan-line" id="scanLine"></div>
                        </div>
                        <div class="scan-flash-overlay" id="scanFlash"></div>
                        <div class="qr-placeholder" id="qrPlaceholder">
                            <i class="fa fa-camera"></i>
                            <span>Positionnez le QR code devant la camera</span>
                        </div>
                    </div>
                    <div class="scan-btns">
                        <button class="kaly" id="startScanBtn"><i class="fa fa-camera"></i> Scanner</button>
                        <button class="kaly" id="stopScanBtn"><i class="fa fa-stop"></i> Arreter</button>
                    </div>
                    <div class="scan-toast" id="scanToast"></div>
                </div>

                <div class="scan-side">
                    <div class="person-card" id="personCard">
                        <div class="pc-header">
                            <img class="pc-photo" id="pcPhoto" src="" alt="">
                            <div>
                                <div class="pc-name" id="pcName"></div>
                                <div class="pc-meta" id="pcMeta"></div>
                                <span class="pc-status" id="pcStatus"></span>
                            </div>
                        </div>
                        <div class="pc-edt" id="pcEdt"></div>
                        <div class="pc-detail" id="pcDetail"></div>
                        <div class="pc-tags">
                            <span class="scan-badge" id="pcScanType"></span>
                            <span class="scan-badge" id="pcSession"></span>
                        </div>
                    </div>

                    <div class="add-form">
                        <form method="POST" action="{{ $isUnified ? '#' : route($routePrefix.'.store') }}" id="manualAddForm">
                            @csrf
                            <h4><i class="fa fa-plus-circle"></i> Ajout manuel</h4>
                            <div class="form-row">
                                <div>
                                    <label>Personne</label>
                                    <select name="personne_id" id="manualPersonneId" required>
                                        <option value="">--</option>
                                        @foreach($people as $person)
                                            <option value="{{ $person->id }}" data-type="{{ $person->person_type ?? ($type === 'teacher' ? 'teacher' : 'staff') }}">
                                                {{ $person->nom }} {{ $person->prenom }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label>Date</label>
                                    <input type="date" name="date_jour" value="{{ $jour }}" required>
                                </div>
                            </div>
                            <div class="form-row three">
                                <div>
                                    <label>Type</label>
                                    <select name="type_scan" required>
                                        <option value="entree">Entree</option>
                                        <option value="sortie">Sortie</option>
                                    </select>
                                </div>
                                <div>
                                    <label>Statut</label>
                                    <select name="presence" required>
                                        <option value="1">Present</option>
                                        <option value="0">Absent</option>
                                    </select>
                                </div>
                                <div>
                                    <label>Retard</label>
                                    <select name="retard" required>
                                        <option value="0">Non</option>
                                        <option value="1">Oui</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-row">
                                <div>
                                    <label id="unitLabel">Heures</label>
                                    <input type="number" name="unit_value" id="unitValue" min="0" step="0.5" value="0">
                                </div>
                                <div>
                                    <label>&nbsp;</label>
                                    <button class="kaly add-btn"><i class="fa fa-plus"></i> Ajouter</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="history-section">
                <h2><i class="fa fa-history"></i> Aujourd'hui <span class="hist-count">({{ $todayScans->count() }})</span></h2>
                <div id="scanHistoryList" class="hist-scroll">
                    @if($todayScans->count() > 0)
                        @foreach($todayScans as $scan)
                        <div class="history-item">
                            <span class="hist-icon"><i class="fa fa-{{ ($scan->type_scan ?? 'entree') === 'entree' ? 'sign-in' : 'sign-out' }}"></i></span>
                            <span class="hist-name">{{ $scan->prenom ?? '' }} {{ $scan->nom ?? '' }}</span>
                            <span class="hist-type scan-badge {{ $scan->type_scan ?? 'entree' }}">{{ $scan->type_scan ?? 'entree' }}</span>
                            <span class="hist-time">{{ \Carbon\Carbon::parse($scan->created_at)->setTimezone($_tz)->format('H:i') }}</span>
                        </div>
                        @endforeach
                    @else
                    <div class="hist-empty">Aucun scan aujourd'hui</div>
                    @endif
                </div>
                <div class="scan-history">
                    <table>
                        <thead>
                            <tr>
                                <th>Heure</th>
                                <th>Nom</th>
                                <th>Type</th>
                                <th>Scan</th>
                                <th>Statut</th>
                            </tr>
                        </thead>
                        <tbody id="todayScanBody">
                            @forelse($todayScans as $scan)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($scan->created_at)->setTimezone($_tz)->format('H:i') }}</td>
                                <td><strong>{{ $scan->prenom ?? '' }} {{ $scan->nom ?? '' }}</strong>
                                    <small style="color:var(--text-sec);display:block;font-size:.7rem;">
                                        {{ $scan->person_role ?? ($scan->person_type ?? ($type ?? '')) }}
                                        @if($scan->matiere ?? null) - {{ $scan->matiere }} @endif
                                    </small>
                                </td>
                                <td><span class="scan-badge {{ $scan->person_type ?? '' }}">{{ $scan->person_role ?? ($scan->person_type ?? '') }}</span></td>
                                <td><span class="scan-badge {{ $scan->type_scan ?? 'entree' }}">{{ $scan->type_scan ?? 'entree' }}</span></td>
                                <td>
                                    @if($scan->retard ?? false) <span class="scan-badge retard">Retard</span>
                                    @else <span class="scan-badge present">Present</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr id="noScanRow"><td colspan="5" style="text-align:center;color:var(--text-sec);padding:16px;">Aucun scan aujourd'hui</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>
<script>
var qrStream = null;
var scanLoopId = null;
var isScanning = false;
var lastToken = '';
var scanCooldown = false;
var detectedCount = 0;
var scanCanvas = null;
var scanCtx = null;
var frameCount = 0;

function toggleSub(el){var n=el.nextElementSibling;var a=el.querySelector('.arrow');if(n)n.style.display=n.style.display==='block'?'none':'block';if(a){a.classList.toggle('fa-chevron-down');a.classList.toggle('fa-chevron-up')}}

function showToast(msg, type) {
    var t = document.getElementById('scanToast');
    t.textContent = msg;
    t.className = 'scan-toast ' + type;
    setTimeout(function(){ t.className = 'scan-toast'; }, 4000);
}

function flashOverlay(color) {
    var f = document.getElementById('scanFlash');
    f.className = 'scan-flash-overlay ' + color;
    setTimeout(function(){ f.className = 'scan-flash-overlay'; }, 500);
}

function fixPhotoUrl(path) {
    if (!path) return '{{ asset('legacy/images/default-avatar.png') }}';
    if (path.indexOf('http') === 0) return path;
    return '{{ asset('legacy') }}/' + path.replace(/^\//, '');
}

function extractToken(raw) {
    var value = (raw || '').trim();
    if (!value) return '';
    var m = value.match(/novaskol:qr:v1:([A-Za-z0-9_-]+)/);
    if (m) return m[1];
    try {
        var url = new URL(value, window.location.origin);
        var t = url.searchParams.get('token');
        if (t) return t.trim();
        var parts = url.pathname.split('/').filter(Boolean);
        for (var i = 0; i < parts.length - 1; i++) {
            if (parts[i] === 'qr-code' || parts[i] === 'qr-presence') return decodeURIComponent(parts[i + 1]);
        }
        var last = parts.pop();
        if (last && last.indexOf('nvs_') === 0) return decodeURIComponent(last);
    } catch(e) {}
    if (value.indexOf('/') !== -1) {
        return decodeURIComponent(value.split('/').filter(Boolean).pop() || value);
    }
    return value;
}

function processToken(token) {
    token = extractToken(token);
    if (scanCooldown) return;
    if (token === lastToken) return;
    if (!token) { showToast('Token invalide', 'error'); return; }
    lastToken = token;
    scanCooldown = true;
    flashOverlay('green');
    showToast('QR detecte! Verification...', 'info');

    var url = '{{ url('qr-presence') }}/' + encodeURIComponent(token);
    fetch(url, { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' } })
        .then(function(r){ return r.json(); })
        .then(function(data){
            if (data.success && data.person) {
                showToast(data.message || 'Scan reussi', 'success');
                showPersonCard(data.person, data.message);
                addToHistory(data.person);
                addToTodayTable(data.person);
                flashOverlay('green');
                if (navigator.vibrate) navigator.vibrate(80);
            } else {
                flashOverlay('red');
                showToast(data.message || 'Personne non trouvee', 'error');
            }
            setTimeout(function(){ scanCooldown = false; }, 2000);
        })
        .catch(function(){
            flashOverlay('red');
            showToast('Erreur reseau', 'error');
            setTimeout(function(){ scanCooldown = false; }, 2000);
        });
}

function showPersonCard(person, message) {
    var card = document.getElementById('personCard');
    card.style.animation = 'none';
    setTimeout(function(){ card.style.animation = 'resultPop .3s ease'; }, 10);

    document.getElementById('pcPhoto').src = fixPhotoUrl(person.photo);
    document.getElementById('pcName').textContent = (person.prenom || '') + ' ' + (person.nom || '');
    var meta = [];
    if (person.role) meta.push(person.role);
    if (person.matiere) meta.push(person.matiere);
    if (person.classe) meta.push(person.classe);
    document.getElementById('pcMeta').textContent = meta.join(' | ');

    var status = document.getElementById('pcStatus');
    if (person.retard) { status.textContent = 'Retard'; status.className = 'pc-status retard'; }
    else if (person.scan_type === 'entree') { status.textContent = message || 'Entree'; status.className = 'pc-status entree'; }
    else if (person.scan_type === 'sortie') { status.textContent = message || 'Sortie'; status.className = 'pc-status sortie'; }
    else { status.textContent = message || 'Present'; status.className = 'pc-status present'; }

    document.getElementById('pcScanType').textContent = person.scan_type || 'entree';
    document.getElementById('pcScanType').className = 'scan-badge ' + (person.scan_type || 'entree');
    document.getElementById('pcSession').textContent = person.session || '';
    document.getElementById('pcSession').className = 'scan-badge';

    var edtDiv = document.getElementById('pcEdt');
    if (person.edt && person.edt.length > 0) {
        edtDiv.innerHTML = '<strong>EDT:</strong> ' + person.edt.map(function(e){
            return '<span>' + (e.matiere || e.heure || '') + ' ' + (e.classe ? '(' + e.classe + ')' : '') + ' ' + (e.heure_debut ? e.heure_debut + '-' + e.heure_fin : '') + '</span>';
        }).join(' ');
        edtDiv.style.display = 'block';
    } else { edtDiv.style.display = 'none'; }

    var dh = '';
    if (person.heure_entree) dh += '<div class="pc-detail-item"><strong>Entree</strong><span>' + person.heure_entree + '</span></div>';
    if (person.heure_sortie) dh += '<div class="pc-detail-item"><strong>Sortie</strong><span>' + person.heure_sortie + '</span></div>';
    if (person.horaire) dh += '<div class="pc-detail-item"><strong>Volume</strong><span>' + person.horaire + 'h</span></div>';
    if (person.jours) dh += '<div class="pc-detail-item"><strong>Jour</strong><span>' + person.jours + '</span></div>';
    if (person.scan_time) dh += '<div class="pc-detail-item"><strong>Scanne a</strong><span>' + person.scan_time + '</span></div>';
    if (person.type) dh += '<div class="pc-detail-item"><strong>Type</strong><span>' + person.type + '</span></div>';
    var dd = document.getElementById('pcDetail');
    if (dh) { dd.innerHTML = dh; dd.style.display = 'grid'; } else { dd.style.display = 'none'; }

    card.classList.add('active');
}

function addToHistory(person) {
    detectedCount++;
    var h = document.getElementById('scanHistoryList');
    if (!h) return;
    var empty = h.querySelector('.history-empty');
    if (empty) empty.remove();
    var st = person.scan_type || 'entree';
    var timeStr = person.scan_time || new Date().toLocaleTimeString([], {hour:'2-digit',minute:'2-digit'});
    var div = document.createElement('div');
    div.className = 'history-item';
    div.innerHTML = '<span class="hist-icon"><i class="fa fa-' + (st === 'entree' ? 'sign-in' : 'sign-out') + '"></i></span>' +
        '<span class="hist-name">' + (person.prenom || '') + ' ' + (person.nom || '') + '</span>' +
        '<span class="hist-type scan-badge ' + st + '">' + st + '</span>' +
        '<span class="hist-time">' + timeStr + '</span>';
    h.insertBefore(div, h.firstChild);
}

function addToTodayTable(person) {
    var tbody = document.getElementById('todayScanBody');
    if (!tbody) return;
    var noRow = document.getElementById('noScanRow');
    if (noRow) noRow.remove();
    var st = person.scan_type || 'entree';
    var timeStr = person.scan_time || new Date().toLocaleTimeString([], {hour:'2-digit',minute:'2-digit'});
    var ptype = person.type || '';
    var tr = document.createElement('tr');
    tr.innerHTML = '<td>' + timeStr + '</td>' +
        '<td><strong>' + (person.prenom || '') + ' ' + (person.nom || '') + '</strong><small style="color:var(--text-sec);display:block;font-size:.7rem;">' + ptype + (person.matiere ? ' - ' + person.matiere : '') + '</small></td>' +
        '<td><span class="scan-badge ' + ptype + '">' + ptype + '</span></td>' +
        '<td><span class="scan-badge ' + st + '">' + st + '</span></td>' +
        '<td><span class="scan-badge ' + (person.retard ? 'retard' : 'present') + '">' + (person.retard ? 'Retard' : 'Present') + '</span></td>';
    tbody.insertBefore(tr, tbody.firstChild);
}

function scanFrame() {
    if (!isScanning) return;
    var video = document.getElementById('qrVideo');
    if (video.readyState === video.HAVE_ENOUGH_DATA && video.videoWidth > 0 && video.videoHeight > 0) {
        scanCanvas.width = video.videoWidth;
        scanCanvas.height = video.videoHeight;
        scanCtx.drawImage(video, 0, 0);
        var imageData = scanCtx.getImageData(0, 0, scanCanvas.width, scanCanvas.height);
        frameCount++;
        try {
            var code = jsQR(imageData.data, imageData.width, imageData.height, { inversionAttempts: 'attemptBoth' });
            if (code) {
                var token = extractToken(code.data);
                if (token && !scanCooldown) processToken(token);
            }
        } catch(e) {}
    }
    scanLoopId = requestAnimationFrame(scanFrame);
}

function startCamera() {
    if (isScanning) return;
    if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
        showToast('Camera non disponible', 'error');
        return;
    }
    var video = document.getElementById('qrVideo');
    var placeholder = document.getElementById('qrPlaceholder');
    var overlay = document.getElementById('scanOverlay');
    placeholder.style.display = 'none';
    showToast('Demarrage camera...', 'info');

    var camTimeout = setTimeout(function(){
        showToast('La camera ne repond pas (verifiez permissions)', 'error', true);
    }, 10000);

    navigator.mediaDevices.getUserMedia({ video: { width:{ideal:640}, height:{ideal:480} } })
        .then(function(s){
            clearTimeout(camTimeout);
            qrStream = s;
            video.srcObject = s;
            video.setAttribute('playsinline', '');
            video.play().then(function(){
                isScanning = true;
                overlay.classList.add('active');
                document.getElementById('startScanBtn').style.display = 'none';
                document.getElementById('stopScanBtn').style.display = 'inline-flex';
                showToast('Scan en cours...', 'info');
                scanCanvas = document.createElement('canvas');
                scanCtx = scanCanvas.getContext('2d');
                scanFrame();
            }).catch(function(err){
                showToast('Erreur lecture video: ' + err.message, 'error');
            });
        })
        .catch(function(err){
            clearTimeout(camTimeout);
            placeholder.style.display = 'flex';
            if (err.name === 'NotAllowedError' || err.name === 'PermissionDeniedError') {
                showToast('Acces camera refuse - parametres Windows', 'error');
            } else if (err.name === 'NotFoundError') {
                showToast('Aucune camera trouvee', 'error');
            } else {
                showToast('Erreur camera: ' + (err.message || err), 'error');
            }
        });
}

function stopCamera() {
    if (scanLoopId) { cancelAnimationFrame(scanLoopId); scanLoopId = null; }
    if (qrStream) { qrStream.getTracks().forEach(function(t){ t.stop(); }); qrStream = null; }
    isScanning = false;
    var video = document.getElementById('qrVideo');
    video.srcObject = null;
    document.getElementById('qrPlaceholder').style.display = 'flex';
    document.getElementById('scanOverlay').classList.remove('active');
    document.getElementById('startScanBtn').style.display = 'inline-flex';
    document.getElementById('stopScanBtn').style.display = 'none';
}

document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('startScanBtn').addEventListener('click', startCamera);
    document.getElementById('stopScanBtn').addEventListener('click', stopCamera);

    document.querySelectorAll('.type-filter button').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var t = this.dataset.type;
            var url = new URL(window.location.href);
            url.searchParams.set('type', t);
            window.location.href = url.toString();
        });
    });

    document.getElementById('unitValue')?.addEventListener('input', function(){
        var sel = document.getElementById('manualPersonneId');
        var opt = sel.options[sel.selectedIndex];
        var pt = opt ? opt.dataset.type : null;
        var lbl = document.getElementById('unitLabel');
        lbl.textContent = pt === 'teacher' ? 'Heures' : 'Jours';
    });

    document.getElementById('manualPersonneId')?.addEventListener('change', function(){
        var opt = this.options[this.selectedIndex];
        var pt = opt ? opt.dataset.type : null;
        var lbl = document.getElementById('unitLabel');
        var inp = document.getElementById('unitValue');
        lbl.textContent = pt === 'teacher' ? 'Heures' : 'Jours';
        inp.value = pt === 'teacher' ? '0' : '1';
        inp.step = pt === 'teacher' ? '0.5' : '1';
    });

    document.getElementById('manualAddForm')?.addEventListener('submit', function(e) {
        if (this.action === '#') {
            e.preventDefault();
            var fd = new FormData(this);
            var sel = document.getElementById('manualPersonneId');
            var opt = sel.options[sel.selectedIndex];
            var pt = opt ? opt.dataset.type : null;
            if (!pt) { showToast('Selectionnez une personne', 'error'); return; }
            var url = pt === 'teacher'
                ? '{{ route('modules.presence.store') }}'
                : '{{ route('modules.presence-staff.store') }}';
            fd.append('annee_scolaire', '{{ $annee }}');
            fd.append('mois', '{{ $mois }}');
            var uv = document.getElementById('unitValue').value;
            fd.append(pt === 'teacher' ? 'horaire' : 'jours', uv || '0');
            fetch(url, { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }, body: fd })
                .then(function(r){ if(r.redirected) window.location.href=r.url; else if(r.ok) location.reload(); else showToast('Erreur ajout','error'); })
                .catch(function(){ showToast('Erreur reseau','error'); });
        }
    });
});
</script>
</body>
</html>
