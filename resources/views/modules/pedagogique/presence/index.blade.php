<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Fiche de Presence - {{ $ecole->nom ?? 'Ecole' }}</title>
<link rel="stylesheet" href="{{ asset('legacy/assets/fontawesome/css/all.min.css') }}">
<link rel="stylesheet" href="{{ asset('legacy/fa/css/font-awesome.min.css') }}">
@include('modules.professeur.bulletin.partials.styles')
<style>
:root{--table-head-bg:rgba(255,255,255,0.08);--table-border:#333}.form-container{background:var(--card)!important;border-radius:12px;padding:28px;border:1px solid var(--border);box-shadow:0 4px 12px rgba(0,0,0,.35);max-width:1600px;margin:0 auto;color:var(--text)!important;overflow:hidden}.kaly{margin-top:15px;padding:12px 24px;background:#0f70c0;color:#fff;border:none;border-radius:8px;cursor:pointer;font-size:15px;font-weight:800;text-decoration:none;display:inline-flex;align-items:center;gap:8px}.school-header{text-align:center;margin-bottom:12px}.school-header img{max-width:140px;max-height:70px;vertical-align:middle}.school-header h1{font-size:18px;margin:6px 0 4px}.school-header p{margin:3px 0;font-size:13px}.table-wrapper{overflow-x:auto;-webkit-overflow-scrolling:touch;max-width:100%;margin:15px 0}.presence-table{width:100%;min-width:980px;border-collapse:collapse;font-size:9.5pt;color:var(--text);background:var(--card);margin-bottom:20px;table-layout:fixed}.presence-table th,.presence-table td{border:1px solid var(--table-border);padding:5px 6px;text-align:center;vertical-align:middle;word-wrap:break-word}.presence-table th{background:var(--table-head-bg);color:white;font-weight:bold}.name-column{width:17%;text-align:left;padding-left:8px;font-size:9.5pt}.par{width:18px;min-width:18px;font-weight:bold}.signature-row td{height:48px;vertical-align:bottom;font-size:9.5pt;background:var(--card)}.page-break{page-break-before:always}.filter-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(210px,1fr));gap:16px;align-items:end;margin-bottom:18px}.filter-grid label,.digital-presence label{display:block;color:var(--text-sec);font-weight:700;margin-bottom:7px}.filter-grid select,.filter-grid input,.digital-presence select,.digital-presence input{width:100%;padding:11px;border-radius:8px;border:1px solid var(--border);background:var(--surface);color:var(--text)}.mode-tabs{display:flex;flex-wrap:wrap;gap:10px;margin:8px 0 20px}.mode-tabs a{padding:11px 16px;border-radius:8px;border:1px solid var(--border);background:var(--surface);color:var(--text);text-decoration:none;font-weight:800}.mode-tabs a.active{background:var(--primary);border-color:var(--primary);color:#062b1d}.presence-kpis{display:grid;grid-template-columns:repeat(auto-fit,minmax(170px,1fr));gap:12px;margin:18px 0}.presence-kpi{background:var(--surface);border:1px solid var(--border);border-radius:8px;padding:14px}.presence-kpi span{display:block;color:var(--text-sec);font-size:.82rem}.presence-kpi strong{font-size:1.45rem;color:var(--primary)}.digital-table{width:100%;border-collapse:collapse;min-width:780px}.digital-table th,.digital-table td{border-bottom:1px solid var(--border);padding:12px;text-align:left}.digital-table th{background:#0f172a;color:var(--primary);text-transform:uppercase;font-size:.78rem}.status-present{color:#86efac}.status-absent{color:#fecaca}.status-retard{color:#fbbf24}.empty-state{padding:22px;text-align:center;border:1px dashed var(--border);border-radius:8px;color:var(--text-sec)}#print-btn{display:inline-flex}
@media(max-width:800px){.form-container{padding:16px!important}.filter-grid{grid-template-columns:1fr}.digital-table{min-width:680px}.kaly{width:100%;justify-content:center}.mode-tabs a{flex:1;text-align:center}.mode-tabs{flex-direction:column}.presence-kpi strong{font-size:1.18rem}}
@media screen and (max-width:900px){body{overflow-x:hidden}.printable-fiche .table-wrapper{overflow:visible!important}.printable-fiche .school-header,.printable-fiche .presence-table{width:980px!important;max-width:980px!important;min-width:980px!important;margin-left:0!important;margin-right:0!important;zoom:.72;transform-origin:top left}.printable-fiche .presence-table{font-size:9.5pt!important;table-layout:fixed!important}.printable-fiche .presence-table th,.printable-fiche .presence-table td{padding:5px 6px!important}.printable-fiche .name-column{width:17%!important;font-size:9.5pt!important}.printable-fiche .par{width:18px!important;min-width:18px!important}.printable-fiche .school-header h1{font-size:18px!important}.printable-fiche .school-header p{font-size:13px!important}}
@media screen and (max-width:760px){.printable-fiche .school-header,.printable-fiche .presence-table{zoom:.62}}
@media screen and (max-width:700px){.printable-fiche .school-header,.printable-fiche .presence-table{zoom:.52}}
@media screen and (max-width:600px){.printable-fiche .school-header,.printable-fiche .presence-table{zoom:.44}}
@media screen and (max-width:520px){.printable-fiche .school-header,.printable-fiche .presence-table{zoom:.37}}
@media screen and (max-width:380px){.printable-fiche .school-header,.printable-fiche .presence-table{zoom:.35}}
@media print{@page{size:A4 landscape;margin:4mm 4mm 6mm 4mm}*,*::before,*::after{background:white!important;color:black!important;border-color:black!important;box-shadow:none!important;text-shadow:none!important;-webkit-print-color-adjust:exact!important;print-color-adjust:exact!important}body,html,main,.form-container,.table-wrapper,.presence-table,.school-header{margin:0!important;padding:0!important;background:white!important;color:black!important;overflow:visible!important;zoom:1!important;transform:none!important}.form-container{display:block!important;border:0!important;box-shadow:none!important}.no-print,nav,header,.form-container>form,#print-btn,footer,#ai-chat-widget,.burger-menu,#fullscreen-btn,.novaskol-global-actions,.global-dropdown,.novaskol-loader{display:none!important}.digital-presence{display:none!important}.printable-fiche{display:block!important}.printable-fiche .school-header,.printable-fiche .presence-table{width:100%!important;max-width:none!important;min-width:0!important;zoom:1!important;transform:none!important}.school-header{margin:0 0 3mm 0!important;padding:0!important}.school-header img{max-width:90px!important;max-height:45px!important}.presence-table{width:100%!important;margin:0!important;padding:0!important;font-size:7.6pt!important;table-layout:fixed!important;border-collapse:collapse!important;page-break-inside:avoid}.presence-table th,.presence-table td{border:.4pt solid black!important;padding:2px 3px!important;background:white!important;color:black!important;font-size:7.6pt!important}.presence-table th{background:#f5f5f5!important}.name-column{width:12%!important;font-size:7.6pt!important}.par{width:12px!important;min-width:12px!important;padding:2px!important}.signature-row td{height:32px!important;padding:2px!important}.page-break{page-break-before:always}}
</style>
<script src="{{ asset('legacy/vendor/qrcode.min.js') }}"></script>
<script src="{{ asset('legacy/vendor/jsQR.js') }}"></script>
</head>
<body>
@include('modules.professeur.bulletin.partials.shell', ['activeModule'=>$activeModule])
<main>
<header><div class="header-left"><button class="burger-menu" onclick="toggleSidebar()"><i class="fa fa-bars"></i></button><button id="fullscreen-btn" onclick="toggleFullscreen()"><i id="fullscreen-icon" class="fa fa-expand"></i></button></div><div class="header-center"><i class="fa fa-check-circle-o"></i> Fiche de Presence</div></header>
<div class="form-container">
    <form class="no-print" method="GET" action="{{ route('modules.presence-etudiant') }}">
        <input type="hidden" name="generer" value="1">
        <input type="hidden" name="mode" value="{{ $selectedMode }}">
        <div class="filter-grid">
            <div><label>Annee scolaire</label><select name="annee_scolaire" required>@foreach($annees as $annee)<option value="{{ $annee }}" @selected($selectedAnnee===$annee)>{{ $annee }}</option>@endforeach</select></div>
            <div><label>Mois</label><select name="mois" required>@foreach($monthLabels as $i=>$label)<option value="{{ $i+1 }}" @selected($selectedMonth===$i+1)>{{ $label }}</option>@endforeach</select></div>
            <div><label>Classe</label><select name="classe_id" required><option value="">Selectionnez</option>@foreach($classes as $classe)<option value="{{ $classe->id }}" @selected($selectedClasse===$classe->id)>{{ $classe->nom }}</option>@endforeach</select></div>
            @if($selectedMode === 'numerique')
                <div><label>Date</label><input type="date" name="date_jour" value="{{ $selectedDate }}"></div>
                <div><label>Filtre statut</label><select name="statut"><option value="">Tous</option><option value="present" @selected($statusFilter==='present')>Presents</option><option value="absent" @selected($statusFilter==='absent')>Absents</option><option value="retard" @selected($statusFilter==='retard')>Retards</option></select></div>
            @endif
            <button class="kaly" type="submit"><i class="fa fa-search"></i> Afficher</button>
        </div>
    </form>

    <div class="mode-tabs no-print">
        <a @class(['active'=>$selectedMode==='fiche']) href="{{ route('modules.presence-etudiant', ['generer'=>1, 'mode'=>'fiche', 'annee_scolaire'=>$selectedAnnee, 'mois'=>$selectedMonth, 'classe_id'=>$selectedClasse]) }}"><i class="fa fa-file-text-o"></i> Fiche vierge</a>
        <a @class(['active'=>$selectedMode==='numerique']) href="{{ route('modules.presence-etudiant', ['generer'=>1, 'mode'=>'numerique', 'annee_scolaire'=>$selectedAnnee, 'mois'=>$selectedMonth, 'classe_id'=>$selectedClasse, 'date_jour'=>$selectedDate]) }}"><i class="fa fa-check-square-o"></i> Presence numerique</a>
        <a @class(['active'=>$selectedMode==='scan']) href="{{ route('modules.presence-etudiant', ['generer'=>1, 'mode'=>'scan', 'annee_scolaire'=>$selectedAnnee, 'mois'=>$selectedMonth, 'classe_id'=>$selectedClasse]) }}"><i class="fa fa-camera"></i> Scan QR</a>
        <button id="print-btn" class="kaly" type="button" onclick="window.print()"><i class="fa fa-print"></i> Imprimer</button>
        <button class="kaly" style="background:#333!important" onclick="window.print()"><i class="fa fa-file-pdf-o"></i> Apercu PDF</button>
    </div>
    
    @if($selectedMode === 'scan')
    <section class="scan-presence">
        <div class="scan-layout">
            <div class="scan-camera">
                <div class="scan-box">
                    <h3><i class="fa fa-camera"></i> Scanner QR</h3>
                    <div class="video-wrap" id="videoWrap">
                        <video id="qr-scanner" autoplay playsinline muted></video>
                        <div class="scan-overlay" id="scanOverlay">
                            <div class="scan-corner tl"></div><div class="scan-corner tr"></div>
                            <div class="scan-corner bl"></div><div class="scan-corner br"></div>
                            <div class="scan-line" id="scanLine"></div>
                        </div>
                        <div class="scan-idle" id="scanIdle">
                            <i class="fa fa-camera"></i>
                            <p>Cliquez sur Demarrer</p>
                        </div>
                        <div class="scan-flash" id="scanFlash"></div>
                    </div>
                    <div class="scan-actions">
                        <button class="kaly" type="button" id="startScanBtn"><i class="fa fa-play"></i> Demarrer</button>
                        <button class="kaly" type="button" id="stopScanBtn" style="display:none;background:#c0392b"><i class="fa fa-stop"></i> Arreter</button>
                    </div>
                    <p class="scan-hint" id="scanHint">Placez le code QR devant la camera</p>
                </div>
            </div>
            <div class="scan-result">
                <div class="scan-result-header">
                    <h3><i class="fa fa-user"></i> Dernier scan</h3>
                    <span id="scanTime"></span>
                </div>
                <div id="scanResultCard" class="result-card" style="display:none">
                    <div class="result-avatar" id="resultAvatar"></div>
                    <div class="result-info">
                        <span class="result-badge" id="resultBadge"></span>
                        <strong id="resultName"></strong>
                        <small id="resultDetail"></small>
                    </div>
                    <div class="result-action" id="resultAction"></div>
                    <div class="result-edt" id="resultEdt" style="display:none;margin-top:10px;width:100%">
                        <h5 style="margin:0 0 6px;font-size:.8rem;color:var(--text-sec)"><i class="fa fa-calendar"></i> Emploi du temps</h5>
                        <div id="edtList"></div>
                    </div>
                </div>
                <div id="scanEmpty" class="scan-empty">
                    <i class="fa fa-qrcode"></i>
                    <p>Aucun scan pour le moment</p>
                </div>
                <div class="scan-history">
                    <h4><i class="fa fa-list"></i> Historique du jour <span id="scanCount">(0)</span></h4>
                    <div id="scannedList"></div>
                </div>
            </div>
        </div>
        <div id="scanStatus" class="scan-toast"></div>
        <div class="scan-table-wrap">
            <h3 style="margin:16px 0 8px;font-size:.95rem"><i class="fa fa-table"></i> Presence du jour <span class="scan-date">({{ now()->format('d/m/Y') }})</span></h3>
            <div class="scan-table-scroll">
                <table class="presence-scan-table" id="todayScanTable">
                    <thead>
                        <tr>
                            <th>Heure</th>
                            <th>Eleve</th>
                            <th>Type</th>
                            <th>Statut</th>
                        </tr>
                    </thead>
                    <tbody id="todayScanBody">
                        @forelse($todayScans as $scan)
                        @php
                            $eleve = DB::table('eleves')->where('id', $scan->eleve_id)->first(['nom', 'prenom', 'matricule']);
                        @endphp
                        <tr>
                            <td class="cell-time">{{ \Carbon\Carbon::parse($scan->created_at)->format('H:i') }}</td>
                            <td class="cell-name">{{ $eleve->prenom ?? '' }} {{ $eleve->nom ?? '' }}<br><small>{{ $eleve->matricule ?? '' }}</small></td>
                            <td class="cell-session {{ $scan->type_scan ?? $scan->session_jour }}">{{ $scan->type_scan === 'entree' ? ($scan->session_jour === 'matin' ? 'Entree M' : 'Entree AM') : ($scan->session_jour === 'matin' ? 'Sortie M' : 'Sortie AM') }}</td>
                            <td class="cell-statut {{ $scan->statut }}">{{ $scan->statut }}</td>
                        </tr>
                        @empty
                        <tr id="noScanRow"><td colspan="4" style="color:var(--text-sec);padding:20px;text-align:center">Aucun scan aujourd'hui</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        var video = document.getElementById('qr-scanner');
        var startBtn = document.getElementById('startScanBtn');
        var stopBtn = document.getElementById('stopScanBtn');
        var status = document.getElementById('scanStatus');
        var scannedList = document.getElementById('scannedList');
        var scanResultCard = document.getElementById('scanResultCard');
        var scanEmpty = document.getElementById('scanEmpty');
        var resultAvatar = document.getElementById('resultAvatar');
        var resultBadge = document.getElementById('resultBadge');
        var resultName = document.getElementById('resultName');
        var resultDetail = document.getElementById('resultDetail');
        var resultAction = document.getElementById('resultAction');
        var resultEdt = document.getElementById('resultEdt');
        var edtList = document.getElementById('edtList');
        var scanTime = document.getElementById('scanTime');
        var scanIdle = document.getElementById('scanIdle');
        var overlay = document.getElementById('scanOverlay');
        var flash = document.getElementById('scanFlash');
        var scanHint = document.getElementById('scanHint');
        var scanCount = document.getElementById('scanCount');
        var lastToken = '';
        var scanCooldown = false;
        var detectedCount = 0;
        var isScanning = false;
        var stream = null;
        var scanLoopId = null;
        var scanCanvas = null;
        var scanCtx = null;
        var frameCount = 0;

        if (typeof jsQR === 'undefined') {
            showStatus('ERREUR: lecteur QR local non charge', 'error');
        }

        function showStatus(msg, type, persist) {
            status.textContent = msg;
            status.className = 'scan-toast ' + type;
            status.style.display = 'block';
            if (!persist) {
                setTimeout(function(){ status.style.display = 'none'; }, 4000);
            }
        }

        function flashOverlay(color) {
            flash.className = 'scan-flash ' + color;
            setTimeout(function(){ flash.className = 'scan-flash'; }, 500);
        }

        function showResult(person) {
            scanEmpty.style.display = 'none';
            scanResultCard.style.display = 'flex';
            resultAvatar.innerHTML = person.photo ? '<img src="{{ asset('legacy') }}/' + person.photo.replace(/^\//,'') + '" alt="">' : '<i class="fa fa-user"></i>';
            resultBadge.textContent = person.type || '';
            resultBadge.className = 'result-badge ' + (person.type === 'eleve' ? 'badge-etudiant' : person.type === 'enseignant' ? 'badge-enseignant' : 'badge-staff');
            resultName.textContent = person.nom + ' ' + person.prenom;
            var detailParts = [person.matricule || person.id || ''];
            if (person.type === 'enseignant') {
                if (person.classe) detailParts.push(person.classe);
                if (person.matiere) detailParts.push(person.matiere);
                if (person.horaire) detailParts.push(person.horaire + 'h');
                if (person.retard) detailParts.push('RETARD');
            }
            resultDetail.textContent = detailParts.join(' | ');
            scanTime.textContent = person.scan_time || new Date().toLocaleTimeString();
            var scanType = person.scan_type || 'entree';
            var session = person.session || 'matin';
            if (scanType === 'entree') {
                var label = session === 'matin' ? 'Entree Matin' : 'Entree AM';
                resultAction.innerHTML = '<span class="action-entree"><i class="fa fa-sign-in"></i> ' + label + '</span>';
                scanResultCard.className = 'result-card entree';
            } else {
                var label = session === 'matin' ? 'Sortie Matin' : 'Sortie AM';
                resultAction.innerHTML = '<span class="action-sortie"><i class="fa fa-sign-out"></i> ' + label + '</span>';
                scanResultCard.className = 'result-card sortie';
            }
            var edtDiv = document.getElementById('resultEdt');
            var edtList = document.getElementById('edtList');
            if (person.edt && person.edt.length > 0) {
                edtList.innerHTML = person.edt.map(function(s) {
                    var line = '<span class="edt-time">' + s.heure + '</span><span class="edt-subject">' + s.matiere + '</span>';
                    if (s.classe) line += '<span class="edt-classe">' + s.classe + '</span>';
                    return '<div class="edt-item">' + line + '</div>';
                }).join('');
                edtDiv.style.display = 'block';
            } else if (person.type !== 'eleve' && person.type !== 'enseignant') {
                edtDiv.style.display = 'none';
            } else {
                edtDiv.style.display = 'block';
                edtList.innerHTML = '<div class="edt-item" style="color:var(--text-sec);font-size:.8rem">Aucun emploi du temps defini</div>';
            }
            scanResultCard.style.animation = 'none';
            setTimeout(function(){ scanResultCard.style.animation = 'resultPop .3s ease'; }, 10);
        }

        function addHistory(person) {
            detectedCount++;
            scanCount.textContent = '(' + detectedCount + ')';
            var empty = scannedList.querySelector('.history-empty');
            if (empty) empty.remove();
            var div = document.createElement('div');
            var st = person.scan_type || 'entree';
            var ss = person.session || 'matin';
            var timeStr = person.scan_time || new Date().toLocaleTimeString([], {hour:'2-digit',minute:'2-digit'});
            div.className = 'history-item ' + (st === 'entree' ? 'hist-entree' : 'hist-sortie');
            div.innerHTML = '<span class="hist-icon"><i class="fa fa-' + (st === 'entree' ? 'sign-in' : 'sign-out') + '"></i></span>' +
                '<span class="hist-name">' + person.nom + ' ' + person.prenom + '</span>' +
                '<span class="hist-session">' + (st === 'entree' ? (ss === 'matin' ? 'EM' : 'EAM') : (ss === 'matin' ? 'SM' : 'SAM')) + '</span>' +
                '<span class="hist-time">' + timeStr + '</span>';
            scannedList.insertBefore(div, scannedList.firstChild);
            var tbody = document.getElementById('todayScanBody');
            if (tbody) {
                var noRow = document.getElementById('noScanRow');
                if (noRow) noRow.remove();
                var tr = document.createElement('tr');
                var scanType = person.scan_type || 'entree';
                var session = person.session || 'matin';
                var typeLabel = scanType === 'entree' ? (session === 'matin' ? 'Entree M' : 'Entree AM') : (session === 'matin' ? 'Sortie M' : 'Sortie AM');
                var sessionClass = session === 'matin' ? 'matin' : 'apres_midi';
                tr.innerHTML = '<td class="cell-time">' + timeStr + '</td>' +
                    '<td class="cell-name">' + (person.nom || '') + ' ' + (person.prenom || '') + '<br><small>' + (person.matricule || '') + '</small></td>' +
                    '<td class="cell-session ' + sessionClass + '">' + typeLabel + '</td>' +
                    '<td class="cell-statut ' + (person.retard ? 'retard' : 'present') + '">' + (person.retard ? 'retard' : 'present') + '</td>';
                tbody.insertBefore(tr, tbody.firstChild);
            }
        }

        function extractQrToken(raw) {
            var value = (raw || '').trim();
            if (!value) return '';
            var personal = value.match(/novaskol:qr:v1:([A-Za-z0-9_-]+)/);
            if (personal) return personal[1];
            try {
                var url = new URL(value, window.location.origin);
                var token = url.searchParams.get('token');
                if (token) return token.trim();
                var parts = url.pathname.split('/').filter(Boolean);
                for (var i = 0; i < parts.length - 1; i++) {
                    if (parts[i] === 'qr-code' || parts[i] === 'qr-presence') return decodeURIComponent(parts[i + 1]);
                }
                var last = parts.pop();
                if (last && last.indexOf('nvs_') === 0) return decodeURIComponent(last);
            } catch (e) {}
            if (value.indexOf('/') !== -1) {
                var fallback = value.split('/').filter(Boolean).pop();
                return decodeURIComponent(fallback || value);
            }
            return value;
        }

        function processToken(token) {
            token = extractQrToken(token);
            if (scanCooldown) return;
            if (token === lastToken) return;
            if (!token) { showStatus('Token invalide', 'error'); return; }
            if (token.indexOf('scan-test') !== -1 || token.indexOf('test:') === 0) {
                showStatus('Lecture QR OK (test)', 'success');
                flashOverlay('green');
                lastToken = token;
                scanCooldown = true;
                setTimeout(function(){ scanCooldown = false; }, 2000);
                return;
            }
            lastToken = token;
            scanCooldown = true;
            flashOverlay('green');
            setTimeout(function(){ scanCooldown = false; }, 2000);

            var url = '{{ url('qr-presence') }}/' + encodeURIComponent(token);
            fetch(url, { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' } })
                .then(function(r){ return r.json(); })
                .then(function(data){
                    if (data.success && data.person) {
                        showStatus(data.message, 'success');
                        showResult(data.person);
                        addHistory(data.person);
                        flashOverlay('green');
                    } else {
                        showStatus(data.message || 'Personne non reconnue', 'error');
                    }
                })
                .catch(function(){
                    showStatus('Erreur reseau', 'error');
                });
        }

        function scanFrame() {
            if (!isScanning) return;
            if (video.readyState === video.HAVE_ENOUGH_DATA && video.videoWidth > 0 && video.videoHeight > 0) {
                scanCanvas.width = video.videoWidth;
                scanCanvas.height = video.videoHeight;
                scanCtx.drawImage(video, 0, 0);
                var imageData = scanCtx.getImageData(0, 0, scanCanvas.width, scanCanvas.height);
                frameCount++;
                if (frameCount % 30 === 0) {
                    scanHint.textContent = 'Analyse... (' + video.videoWidth + 'x' + video.videoHeight + ', trames: ' + frameCount + ')';
                }
                try {
                    if (typeof jsQR === 'undefined') {
                        if (frameCount === 1) showStatus('jsQR non charge', 'error');
                        scanLoopId = requestAnimationFrame(scanFrame);
                        return;
                    }
                    if (frameCount === 1 && stream) {
                        var track = stream.getVideoTracks ? stream.getVideoTracks()[0] : null;
                        if (track) scanHint.textContent = 'Camera: ' + track.label + ' (' + track.readyState + ')';
                    }
                    var code = jsQR(imageData.data, imageData.width, imageData.height, { inversionAttempts: 'attemptBoth' });
                    if (code) {
                        scanHint.textContent = 'QR detecte! ' + code.data.substring(0, 30) + '...';
                        if (!scanCooldown) {
                            if (code.data === 'novaskol:scan-test') {
                                scanCooldown = true;
                                showStatus('Lecture QR OK', 'success');
                                setTimeout(function(){ scanCooldown = false; }, 1200);
                                scanLoopId = requestAnimationFrame(scanFrame);
                                return;
                            }
                            var token = extractQrToken(code.data);
                            if (token) processToken(token);
                        }
                    } else if (frameCount % 60 === 0) {
                        scanHint.textContent = 'Recherche QR... (' + frameCount + ' trames)';
                    }
                } catch(e) {
                    if (frameCount === 1) showStatus('Erreur analyse: ' + e.message, 'error');
                }
            } else if (frameCount % 60 === 0) {
                scanHint.textContent = 'Attente camera... (readyState: ' + video.readyState + ')';
            }
            scanLoopId = requestAnimationFrame(scanFrame);
        }

        function startCamera() {
            if (isScanning) { showStatus('Scan deja en cours', 'error'); return; }
            if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                showStatus('Camera non disponible sur cet appareil', 'error', true);
                return;
            }
            scanHint.textContent = 'Demarrage camera...';
            var camTimeout = setTimeout(function() {
                showStatus('La camera ne repond pas (verifiez permissions Windows)', 'error', true);
                scanHint.textContent = 'Cliquez sur Demarrer pour scanner';
            }, 10000);
            var blackFrameCount = 0;
            var checkFrame = function() {
                if (!stream || !scanCanvas || !scanCtx) return;
                try {
                    scanCtx.drawImage(video, 0, 0, 100, 100);
                    var data = scanCtx.getImageData(0, 0, 100, 100).data;
                    var total = 0;
                    for (var i = 0; i < data.length; i += 4) { total += data[i] + data[i+1] + data[i+2]; }
                    var avgBright = total / (100 * 100 * 3);
                    if (avgBright > 5) {
                        clearTimeout(camTimeout);
                        blackFrameCount = 0;
                    } else {
                        blackFrameCount++;
                        if (blackFrameCount > 6) {
                            showStatus('La camera ne fournit pas d\'image (flux noir). Verifiez permissions Windows.', 'error', true);
                            scanHint.textContent = 'FLUX NOIR - permissions insuffisantes';
                        }
                    }
                } catch(e) {}
            };
            navigator.mediaDevices.getUserMedia({ video: { width: { ideal: 640 }, height: { ideal: 480 } } })
                .then(function(s) {
                    stream = s;
                    video.srcObject = s;
                    video.setAttribute('playsinline', '');
                    var track = s.getVideoTracks()[0];
                    if (track) scanHint.textContent = 'Camera: ' + (track.label || 'inconnue');
                    video.play().then(function() {
                        isScanning = true;
                        scanIdle.style.display = 'none';
                        overlay.style.opacity = '1';
                        overlay.classList.add('active');
                        startBtn.style.display = 'none';
                        stopBtn.style.display = 'inline-flex';
                        var line = document.getElementById('scanLine');
                        if (line) line.style.animation = 'scanMove 2s ease-in-out infinite';
                        scanCanvas = document.createElement('canvas');
                        scanCtx = scanCanvas.getContext('2d');
                        checkFrame.blackInterval = setInterval(checkFrame, 1500);
                        scanFrame();
                    }).catch(function(err) {
                        clearTimeout(camTimeout);
                        showStatus('Erreur lecture video: ' + err.message, 'error');
                    });
                })
                .catch(function(err) {
                    clearTimeout(camTimeout);
                    if (err.name === 'NotAllowedError' || err.name === 'PermissionDeniedError') {
                        showStatus('Acces camera refuse. Activez la camera dans parametres Windows + redemarrez Novaskol.', 'error', true);
                    } else if (err.name === 'NotFoundError') {
                        showStatus('Aucune camera trouvee sur cet appareil', 'error', true);
                    } else {
                        showStatus('Erreur camera: ' + err.message, 'error', true);
                    }
                });
        }

        function stopCamera() {
            if (scanLoopId) { cancelAnimationFrame(scanLoopId); scanLoopId = null; }
            if (typeof checkFrame !== 'undefined' && checkFrame.blackInterval) { clearInterval(checkFrame.blackInterval); }
            if (stream) { stream.getTracks().forEach(function(t){ t.stop(); }); }
            isScanning = false;
            stream = null;
            video.srcObject = null;
            scanIdle.style.display = 'flex';
            overlay.style.opacity = '0';
            overlay.classList.remove('active');
            startBtn.style.display = 'inline-flex';
            stopBtn.style.display = 'none';
            var line = document.getElementById('scanLine');
            if (line) line.style.animation = 'none';
            scanHint.textContent = 'Placez le code QR devant la camera';
        }

        startBtn.addEventListener('click', startCamera);
        stopBtn.addEventListener('click', stopCamera);

        scanHint.textContent = 'Cliquez sur Demarrer pour scanner';
    });
    </script>
    <style>
    .scan-presence{animation:fadeIn .3s ease}@keyframes fadeIn{from{opacity:0;transform:translateY(10px)}to{opacity:1;transform:translateY(0)}}
    @keyframes resultPop{0%{transform:scale(.9);opacity:0}100%{transform:scale(1);opacity:1}}
    @keyframes scanMove{0%{top:5%}50%{top:95%}100%{top:5%}}
    @keyframes flashGreen{0%{opacity:0}15%{opacity:1}100%{opacity:0}}
    .scan-layout{display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-top:16px}
    .scan-box{background:var(--surface);border-radius:12px;padding:16px;border:1px solid var(--border);text-align:center}
    .scan-box h3{margin:0 0 12px;font-size:1.05rem}
    .video-wrap{position:relative;width:100%;max-width:340px;height:280px;margin:0 auto;border-radius:10px;overflow:hidden;background:#0a0f1a}
    #qr-scanner{width:100%;height:100%;object-fit:cover;display:block}
    .scan-idle{position:absolute;inset:0;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:10px;color:#475569;z-index:2;background:#0a0f1a}
    .scan-idle i{font-size:3rem;opacity:.4}.scan-idle p{margin:0;font-size:.88rem}
    .scan-flash{position:absolute;inset:0;z-index:4;pointer-events:none;border-radius:10px;opacity:0;transition:opacity 0s}
    .scan-flash.green{background:rgba(0,200,83,.35);box-shadow:inset 0 0 80px rgba(0,200,83,.5);animation:flashGreen .45s ease}
    .scan-flash.red{background:rgba(239,68,68,.3);box-shadow:inset 0 0 80px rgba(239,68,68,.4);animation:flashGreen .45s ease}
    .scan-overlay{position:absolute;inset:0;pointer-events:none;opacity:0;transition:opacity .3s}
    .scan-overlay.active .scan-corner{border-color:#00c853}
    .scan-corner{position:absolute;width:28px;height:28px;border-color:#475569;border-style:solid;filter:drop-shadow(0 0 4px rgba(0,200,83,.4));transition:border-color .3s}
    .scan-corner.tl{top:10px;left:10px;border-width:3px 0 0 3px;border-radius:3px 0 0 0}
    .scan-corner.tr{top:10px;right:10px;border-width:3px 3px 0 0;border-radius:0 3px 0 0}
    .scan-corner.bl{bottom:10px;left:10px;border-width:0 0 3px 3px;border-radius:0 0 0 3px}
    .scan-corner.br{bottom:10px;right:10px;border-width:0 3px 3px 0;border-radius:0 0 3px 0}
    .scan-line{position:absolute;left:10%;right:10%;height:2px;background:linear-gradient(90deg,transparent,#00c853,transparent);box-shadow:0 0 10px #00c853,0 0 20px rgba(0,200,83,.3);top:5%;opacity:0;transition:opacity .3s}
    .scan-overlay.active .scan-line{opacity:1;animation:scanMove 2s ease-in-out infinite}
    .scan-actions{margin-top:12px;display:flex;gap:10px;justify-content:center}.scan-hint{margin-top:8px;color:var(--text-sec);font-size:.8rem}
    .scan-result{background:var(--surface);border-radius:12px;padding:16px;border:1px solid var(--border);display:flex;flex-direction:column;gap:14px}
    .scan-result-header{display:flex;justify-content:space-between;align-items:center}.scan-result-header h3{margin:0;font-size:1rem}
    #scanTime{font-size:.78rem;color:var(--text-sec)}.result-card{display:flex;flex-wrap:wrap;align-items:center;gap:12px;padding:16px;border-radius:10px;animation:resultPop .3s ease}
    .result-card.entree{background:rgba(0,200,83,.1);border:1px solid rgba(0,200,83,.3)}
    .result-card.sortie{background:rgba(56,189,248,.1);border:1px solid rgba(56,189,248,.3)}
    .result-avatar{width:50px;height:50px;border-radius:50%;overflow:hidden;background:var(--border);display:flex;align-items:center;justify-content:center;flex-shrink:0}
    .result-avatar img{width:100%;height:100%;object-fit:cover}.result-avatar i{font-size:1.4rem;color:var(--text-sec)}
    .result-info{flex:1;min-width:0}.result-badge{display:inline-block;padding:2px 8px;border-radius:4px;font-size:.65rem;font-weight:700;text-transform:uppercase;letter-spacing:.2px;margin-bottom:3px}
    .badge-etudiant{background:#034a3b;color:#fff}.badge-enseignant{background:#0369a1;color:#fff}.badge-staff{background:#6b21a8;color:#fff}
    .result-info strong{display:block;font-size:1rem}.result-info small{color:var(--text-sec);font-size:.78rem}
    .result-action{margin-left:auto;flex-shrink:0}.action-entree,.action-sortie{display:flex;align-items:center;gap:6px;padding:6px 14px;border-radius:20px;font-weight:700;font-size:.82rem}
    .action-entree{background:rgba(0,200,83,.15);color:#00c853}.action-sortie{background:rgba(56,189,248,.15);color:#38bdf8}
    .result-edt{border-top:1px solid var(--border);padding-top:8px;margin-top:8px}.edt-item{display:flex;gap:8px;padding:4px 0;font-size:.78rem;border-bottom:1px solid rgba(255,255,255,.04)}.edt-item:last-child{border-bottom:none}.edt-time{color:var(--text-sec);white-space:nowrap;min-width:70px;font-weight:600}.edt-subject{color:var(--text)}
    .scan-empty{text-align:center;padding:30px 20px;color:var(--text-sec)}.scan-empty i{font-size:2.4rem;display:block;margin-bottom:10px;opacity:.35}.scan-empty p{margin:0;font-size:.92rem}
    .scan-history h4{margin:0 0 8px;font-size:.88rem;color:var(--text-sec)}
    .history-item{display:flex;align-items:center;gap:10px;padding:8px 10px;border-radius:6px;margin-bottom:4px;background:var(--card);font-size:.82rem}
    .history-item.hist-entree{border-left:3px solid #00c853}.history-item.hist-sortie{border-left:3px solid #38bdf8}
    .hist-icon{width:26px;height:26px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:12px;flex-shrink:0}
    .hist-entree .hist-icon{background:rgba(0,200,83,.15);color:#00c853}.hist-sortie .hist-icon{background:rgba(56,189,248,.15);color:#38bdf8}
    .hist-name{flex:1;font-weight:600}.hist-session{font-size:.65rem;font-weight:700;padding:1px 5px;border-radius:3px;background:var(--border);color:var(--text-sec)}.hist-time{color:var(--text-sec);font-size:.75rem}
    .scan-toast{display:none;margin-top:12px;padding:12px 16px;border-radius:8px;text-align:center;font-weight:700}
    .scan-toast.success{background:rgba(0,200,83,.12);color:#00c853;border:1px solid rgba(0,200,83,.3);display:block}
    .scan-toast.error{background:rgba(255,23,68,.12);color:#ff1744;border:1px solid rgba(255,23,68,.3);display:block}
    .scan-table-wrap{margin-top:16px}.scan-date{font-weight:400;color:var(--text-sec);font-size:.82rem}
    .scan-table-scroll{overflow-x:auto;border:1px solid var(--border);border-radius:8px;background:var(--card)}
    .presence-scan-table{width:100%;min-width:500px;border-collapse:collapse;font-size:.84rem}
    .presence-scan-table th,.presence-scan-table td{padding:8px 12px;text-align:left;border-bottom:1px solid var(--border)}
    .presence-scan-table th{background:var(--surface);font-weight:700;color:var(--text);position:sticky;top:0}
    .presence-scan-table tr:last-child td{border-bottom:none}
    .cell-time{white-space:nowrap;color:var(--text-sec);font-size:.78rem;font-weight:600}
    .cell-name strong{display:block}.cell-name small{color:var(--text-sec);font-size:.75rem}
    .cell-session{font-weight:700;font-size:.78rem}.cell-session.matin{color:#00c853}.cell-session.apres_midi{color:#38bdf8}
    .cell-statut{font-weight:600;font-size:.78rem}.cell-statut.present{color:#00c853}.cell-statut.retard{color:#f59e0b}.cell-statut.absent{color:#ff1744}
    @media(max-width:800px){.scan-layout{grid-template-columns:1fr!important}.video-wrap{height:220px!important}.scan-box{order:1}.scan-result{order:2}}
    </style>
    @endif

    @if($selectedMode === 'numerique')
        <section class="digital-presence">
            <div class="presence-kpis">
                <div class="presence-kpi"><span>Presents</span><strong>{{ $presenceSummary['present'] }}</strong></div>
                <div class="presence-kpi"><span>Absents</span><strong>{{ $presenceSummary['absent'] }}</strong></div>
                <div class="presence-kpi"><span>Retards</span><strong>{{ $presenceSummary['retard'] }}</strong></div>
                <div class="presence-kpi"><span>Taux presence</span><strong>{{ number_format(($presenceSummary['present'] / max(1, $presenceSummary['total'])) * 100, 1, ',', ' ') }}%</strong></div>
            </div>
            @if($generated && $students->isNotEmpty())
                <form method="POST" action="{{ route('modules.presence-etudiant.store') }}" id="studentPresenceForm">
                    @csrf
                    <input type="hidden" name="annee_scolaire" value="{{ $selectedAnnee }}">
                    <input type="hidden" name="mois" value="{{ $selectedMonth }}">
                    <input type="hidden" name="classe_id" value="{{ $selectedClasse }}">
                    <input type="hidden" name="date_jour" value="{{ $selectedDate }}">
                    <div class="table-wrapper">
                        <table class="digital-table">
                            <thead><tr><th>Eleve</th><th>Matin</th><th>Apres-midi</th><th>Commentaire</th></tr></thead>
                            <tbody>
                            @foreach($students as $student)
                                @php
                                    $matin = optional(($presenceMap[$student->id.'_matin'] ?? collect())->first())->statut ?? 'present';
                                    $apres = optional(($presenceMap[$student->id.'_apres_midi'] ?? collect())->first())->statut ?? 'present';
                                    $comment = optional(($presenceMap[$student->id.'_matin'] ?? collect())->first())->commentaire ?? optional(($presenceMap[$student->id.'_apres_midi'] ?? collect())->first())->commentaire ?? '';
                                @endphp
                                <tr>
                                    <td><strong>{{ $student->nom }} {{ $student->prenom }}</strong><br><small>{{ $student->matricule ?? '' }}</small></td>
                                    <td><select name="presence[{{ $student->id }}][matin]" class="status-select"><option value="present" @selected($matin==='present')>Present</option><option value="absent" @selected($matin==='absent')>Absent</option><option value="retard" @selected($matin==='retard')>Retard</option></select></td>
                                    <td><select name="presence[{{ $student->id }}][apres_midi]" class="status-select"><option value="present" @selected($apres==='present')>Present</option><option value="absent" @selected($apres==='absent')>Absent</option><option value="retard" @selected($apres==='retard')>Retard</option></select></td>
                                    <td><input name="commentaires[{{ $student->id }}]" value="{{ $comment }}" placeholder="Motif, observation..."></td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <button class="kaly" type="button" id="savePresenceBtn"><i class="fa fa-save"></i> Enregistrer la presence</button>
                </form>
            @else
                <div class="empty-state">Selectionne une classe pour cocher la presence numerique.</div>
            @endif
        </section>
    @endif

    @if($selectedMode === 'fiche')
    <section class="printable-fiche">
        <div class="table-wrapper">
        @if($generated && $students->isNotEmpty() && !empty($dayGroups))
            @php
                $elevesPages = $students->map(fn($e) => $e->nom.' '.$e->prenom)->chunk(28);
            @endphp
            @foreach($dayGroups as $groupIndex=>$jours)
                @foreach($elevesPages as $pageIndex=>$pageEleves)
                    <div class="school-header"><img src="{{ asset('legacy/images/'.($ecole->logo ?? 'novaskol.png')) }}" alt="Logo"><h1>{{ $ecole->nom ?? 'Ecole' }}</h1><p>FICHE DE PRESENCE - {{ $monthLabels[$selectedMonth-1] ?? '' }} {{ $selectedAnnee }}</p><p>Classe : {{ $classeNom }}</p><p>Jours {{ $groupIndex*4+1 }} a {{ min(($groupIndex+1)*4, count($days)) }}</p></div>
                    <table class="presence-table"><thead><tr><th class="name-column" rowspan="4">Nom & Prenom</th>@foreach($jours as $date)<th class="date-header" colspan="12">{{ \Illuminate\Support\Carbon::parse($date)->format('d/m') }}</th>@endforeach</tr><tr>@foreach($jours as $date)<th class="session-header" colspan="6">Matin</th><th class="session-header" colspan="6">Apres-midi</th>@endforeach</tr><tr>@foreach($jours as $date)<th colspan="3">Matiere 1</th><th colspan="3">Matiere 2</th><th colspan="3">Matiere 1</th><th colspan="3">Matiere 2</th>@endforeach</tr><tr>@foreach($jours as $date)@for($i=0;$i<4;$i++)<th class="par">P</th><th class="par">A</th><th class="par">R</th>@endfor @endforeach</tr></thead><tbody>@foreach($pageEleves as $eleve)<tr><td class="name-column">{{ $eleve }}</td>@foreach($jours as $date)@for($i=0;$i<12;$i++)<td class="par"></td>@endfor @endforeach</tr>@endforeach @foreach(['Professeur principal','Matieres enseignees','Signature Direction'] as $label)<tr class="signature-row"><td class="name-column">{{ $label }}</td>@foreach($jours as $date)<td colspan="3"></td><td colspan="3"></td><td colspan="3"></td><td colspan="3"></td>@endforeach</tr>@endforeach</tbody></table>
                    @if(!$loop->last || !$loop->parent->last)<div class="page-break"></div>@endif
                @endforeach
            @endforeach
        @elseif($generated)
            <div class="empty-state no-print">Aucun eleve trouve pour cette classe et cette annee scolaire.</div>
        @endif
        </div>
    </section>
    @endif
</div>
<footer>&copy; {{ date('Y') }} {{ $ecole->nom ?? 'Ecole' }}. Tous droits reserves.</footer>
</main>
<script>
function toggleSidebar(){document.querySelector('nav').classList.toggle('active');document.querySelector('main').classList.toggle('full-width');document.querySelector('header').classList.toggle('full-width')}
function toggleSub(e){const s=e.nextElementSibling,a=e.querySelector('.arrow');s.style.display=s.style.display==='block'?'none':'block';a.classList.toggle('fa-chevron-down');a.classList.toggle('fa-chevron-up')}
function toggleFullscreen(){const i=document.getElementById('fullscreen-icon');if(!document.fullscreenElement){document.documentElement.requestFullscreen();i.classList.replace('fa-expand','fa-compress')}else{document.exitFullscreen();i.classList.replace('fa-compress','fa-expand')}}
document.addEventListener('DOMContentLoaded',()=>{const a=document.querySelector('nav a.active'),s=a?.closest('.sub-menu');if(s){s.style.display='block';s.previousElementSibling?.querySelector('.arrow')?.classList.replace('fa-chevron-down','fa-chevron-up')}})
document.getElementById('savePresenceBtn')?.addEventListener('click',()=>{Swal.fire({title:'Enregistrer la presence ?',text:'Les statuts seront conserves pour cette date.',icon:'question',showCancelButton:true,confirmButtonText:'Oui, enregistrer',cancelButtonText:'Annuler',confirmButtonColor:'#00c853'}).then(r=>{if(r.isConfirmed)document.getElementById('studentPresenceForm').submit()})})
</script>
</body>
</html>
