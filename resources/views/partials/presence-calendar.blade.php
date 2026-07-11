<div class="presence-calendar">
    @php
        $prev = \Carbon\Carbon::create($year, $month, 1)->subMonth();
        $next = \Carbon\Carbon::create($year, $month, 1)->addMonth();
        $prevMonth = $prev->month;
        $prevYear = $prev->year;
        $nextMonth = $next->month;
        $nextYear = $next->year;
        $first = \Carbon\Carbon::create($year, $month, 1);
        $daysInMonth = $first->daysInMonth;
        $startDow = ($first->dayOfWeek === 0 ? 6 : $first->dayOfWeek - 1);
        $today = now()->format('Y-m-d');
        $sep = str_contains($baseUrl, '?') ? '&' : '?';
    @endphp
    <div class="cal-nav">
        <a href="{{ $baseUrl }}{{ $sep }}mois={{ $prevMonth }}&annee={{ $prevYear }}" class="cal-arrow"><i class="fa fa-chevron-left"></i></a>
        <span class="cal-title">{{ $label }} — {{ $first->locale('fr')->isoFormat('MMMM YYYY') }}</span>
        <a href="{{ $baseUrl }}{{ $sep }}mois={{ $nextMonth }}&annee={{ $nextYear }}" class="cal-arrow"><i class="fa fa-chevron-right"></i></a>
    </div>
    <div class="cal-grid">
        <div class="cal-day-header">Lun</div>
        <div class="cal-day-header">Mar</div>
        <div class="cal-day-header">Mer</div>
        <div class="cal-day-header">Jeu</div>
        <div class="cal-day-header">Ven</div>
        <div class="cal-day-header">Sam</div>
        <div class="cal-day-header">Dim</div>
        @for($i = 0; $i < $startDow; $i++)
            <div class="cal-day cal-empty"></div>
        @endfor
        @for($day = 1; $day <= $daysInMonth; $day++)
            @php
                $dateStr = sprintf('%04d-%02d-%02d', $year, $month, $day);
                $entry = $attendance[$day] ?? null;
                $status = $entry['status'] ?? null;
                $cssClass = match ($status) { 'present' => 'cal-present', 'absent' => 'cal-absent', 'retard' => 'cal-retard', default => 'cal-none' };
                $isToday = $dateStr === $today;
                $jsonDetails = htmlspecialchars(json_encode($entry['details'] ?? []), ENT_QUOTES, 'UTF-8');
                $jsonEdt = htmlspecialchars(json_encode($entry['edt'] ?? []), ENT_QUOTES, 'UTF-8');
            @endphp
            <div class="cal-day {{ $cssClass }} @if($isToday) cal-today @endif"
                 onclick="openPresenceModal(this)" data-details='{{ $jsonDetails }}' data-edt='{{ $jsonEdt }}' data-date="{{ $dateStr }}" data-status="{{ $status ?? 'nodata' }}">
                <span class="cal-num">{{ $day }}</span>
                @if($status)
                    <span class="cal-dot"></span>
                @endif
            </div>
        @endfor
    </div>
    <div class="cal-legend">
        <span><span class="cal-dot" style="background:#059669"></span> Present</span>
        <span><span class="cal-dot" style="background:#dc2626"></span> Absent</span>
        <span><span class="cal-dot" style="background:#d97706"></span> Retard</span>
        <span><span class="cal-dot" style="background:#94a3b8"></span> Aucune donnee</span>
    </div>
</div>

<div id="presenceModal" class="presence-modal-overlay" onclick="closePresenceModal(event)" style="display:none">
    <div class="presence-modal">
        <div class="presence-modal-header">
            <span id="pmDate"></span>
            <span id="pmStatus" class="pm-badge"></span>
            <button onclick="closePresenceModal()" style="background:none;border:none;color:#94a3b8;font-size:1.2rem;cursor:pointer">&times;</button>
        </div>
        <div class="presence-modal-body" id="pmBody"></div>
        <div class="presence-modal-section" id="pmEdtSection" style="display:none">
            <div class="pm-section-title"><i class="fa fa-clock-o"></i> Emploi du temps</div>
            <div id="pmEdtBody"></div>
        </div>
    </div>
</div>

<script>
function openPresenceModal(el) {
    var date = el.getAttribute('data-date');
    var status = el.getAttribute('data-status');
    var details = JSON.parse(el.getAttribute('data-details') || '[]');
    var edt = JSON.parse(el.getAttribute('data-edt') || '[]');
    var statusLabels = { present: 'Present', absent: 'Absent', retard: 'Retard', nodata: 'Aucune donnee' };
    var statusColors = { present: '#059669', absent: '#dc2626', retard: '#d97706', nodata: '#94a3b8' };
    document.getElementById('pmDate').textContent = date;
    var badge = document.getElementById('pmStatus');
    badge.textContent = statusLabels[status] || status;
    badge.style.background = statusColors[status] || '#64748b';
    var html = '';
    if (details.length === 0 && edt.length === 0) {
        html = '<div class="pm-empty">Aucune information pour ce jour.</div>';
    }
    if (details.length > 0) {
        details.forEach(function(d) {
            html += '<div class="pm-row">';
            html += '<div class="pm-row-head">';
            if (d.session) html += '<span class="pm-session">' + d.session + '</span>';
            html += '<span class="pm-statut-badge" style="background:' + (statusColors[d.statut] || '#64748b') + '">' + (statusLabels[d.statut] || d.statut) + '</span>';
            html += '</div>';
            html += '<div class="pm-fields">';
            if (d.type_scan) html += '<div class="pm-field"><span class="pm-label">Scan</span><span>' + d.type_scan + '</span></div>';
            if (d.heure) html += '<div class="pm-field"><span class="pm-label">Heure</span><span>' + d.heure + '</span></div>';
            if (d.heure_entree) html += '<div class="pm-field"><span class="pm-label">Entree</span><span>' + d.heure_entree + '</span></div>';
            if (d.heure_sortie) html += '<div class="pm-field"><span class="pm-label">Sortie</span><span>' + d.heure_sortie + '</span></div>';
            if (d.scan_mode) html += '<div class="pm-field"><span class="pm-label">Mode</span><span>' + d.scan_mode + '</span></div>';
            if (d.commentaire) html += '<div class="pm-field pm-comment"><span class="pm-label">Commentaire</span><span>' + d.commentaire + '</span></div>';
            html += '</div>';
            html += '</div>';
        });
    }
    document.getElementById('pmBody').innerHTML = html;
    if (edt.length > 0) {
        var edtHtml = '';
        edt.forEach(function(s) {
            edtHtml += '<div class="pm-edt-row">';
            edtHtml += '<span class="pm-edt-time">' + s.heure + '</span>';
            edtHtml += '<span class="pm-edt-matiere">' + (s.matiere || 'N/A') + '</span>';
            if (s.classe) edtHtml += '<span class="pm-edt-classe">' + s.classe + '</span>';
            edtHtml += '</div>';
        });
        document.getElementById('pmEdtBody').innerHTML = edtHtml;
        document.getElementById('pmEdtSection').style.display = 'block';
    } else {
        document.getElementById('pmEdtSection').style.display = 'none';
    }
    document.getElementById('presenceModal').style.display = 'flex';
}
function closePresenceModal(e) {
    if (e && e.target !== document.getElementById('presenceModal')) return;
    document.getElementById('presenceModal').style.display = 'none';
}
</script>

<style>
.presence-calendar { font-family: 'Inter', sans-serif; }
.cal-nav { display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px; }
.cal-arrow { text-decoration: none; color: var(--primary); font-size: .9rem; padding: 4px 8px; border-radius: 4px; border: 1px solid var(--border); }
.cal-arrow:hover { background: var(--primary-glow); }
.cal-title { font-size: .82rem; font-weight: 700; color: var(--primary); }
.cal-grid { display: grid; grid-template-columns: repeat(7, 1fr); gap: 2px; }
.cal-day-header { text-align: center; font-size: .6rem; color: var(--text-sec); font-weight: 700; text-transform: uppercase; padding: 4px 0; letter-spacing: .3px; }
.cal-day { aspect-ratio: 1; display: flex; flex-direction: column; align-items: center; justify-content: center; border-radius: 6px; cursor: default; position: relative; min-height: 32px; }
.cal-day[data-date] { cursor: pointer; }
.cal-day:hover[data-date] { transform: scale(1.08); z-index: 1; }
.cal-num { font-size: .72rem; font-weight: 600; color: var(--text); line-height: 1; }
.cal-today .cal-num { color: var(--primary); font-weight: 800; }
.cal-today { box-shadow: inset 0 0 0 2px var(--primary); }
.cal-present { background: #05966922; }
.cal-absent { background: #dc262622; }
.cal-retard { background: #d9770622; }
.cal-none { background: transparent; }
.cal-empty { background: transparent; }
.cal-dot { display: inline-block; width: 5px; height: 5px; border-radius: 50%; margin-top: 1px; }
.cal-present .cal-dot { background: #059669; }
.cal-absent .cal-dot { background: #dc2626; }
.cal-retard .cal-dot { background: #d97706; }
.cal-legend { display: flex; gap: 12px; margin-top: 8px; flex-wrap: wrap; }
.cal-legend span { font-size: .65rem; color: var(--text-sec); display: flex; align-items: center; gap: 4px; }
.cal-legend .cal-dot { width: 7px; height: 7px; margin: 0; }

.presence-modal-overlay { position: fixed; inset: 0; background: #00000055; z-index: 9999; display: flex; align-items: center; justify-content: center; }
.presence-modal { background: var(--card); border-radius: 12px; width: 480px; max-width: 90vw; max-height: 90vh; overflow-y: auto; box-shadow: 0 16px 48px #00000033; }
.presence-modal-header { display: flex; align-items: center; justify-content: space-between; padding: 14px 16px; border-bottom: 1px solid var(--border); position: sticky; top: 0; background: var(--card); z-index: 1; }
.presence-modal-header #pmDate { font-weight: 700; color: var(--text); font-size: .9rem; }
.pm-badge { padding: 3px 10px; border-radius: 4px; color: #fff; font-size: .7rem; font-weight: 700; text-transform: uppercase; letter-spacing: .3px; }
.presence-modal-body { padding: 12px 16px; }
.pm-row { border: 1px solid var(--border); border-radius: 8px; padding: 0; background: var(--surface); margin-bottom: 8px; overflow: hidden; }
.pm-row:last-child { margin-bottom: 0; }
.pm-row-head { display: flex; justify-content: space-between; align-items: center; padding: 8px 10px; background: var(--primary-glow); border-bottom: 1px solid var(--border); }
.pm-session { font-weight: 700; font-size: .75rem; color: var(--primary); text-transform: uppercase; }
.pm-statut-badge { padding: 2px 8px; border-radius: 3px; color: #fff; font-size: .62rem; font-weight: 700; text-transform: uppercase; letter-spacing: .3px; }
.pm-fields { padding: 6px 10px; }
.pm-field { display: flex; justify-content: space-between; align-items: center; padding: 3px 0; font-size: .78rem; }
.pm-label { color: var(--text-sec); font-weight: 500; }
.pm-comment { border-top: 1px solid var(--border); margin-top: 3px; padding-top: 5px; }
.pm-empty { text-align: center; color: var(--text-sec); padding: 20px; font-size: .8rem; }
.presence-modal-section { border-top: 1px solid var(--border); padding: 12px 16px; }
.pm-section-title { font-size: .82rem; font-weight: 700; color: var(--primary); margin-bottom: 8px; display: flex; align-items: center; gap: 6px; }
.pm-edt-row { display: flex; align-items: center; gap: 8px; padding: 6px 10px; border: 1px solid var(--border); border-radius: 6px; background: var(--surface); margin-bottom: 5px; font-size: .78rem; }
.pm-edt-time { font-weight: 700; color: var(--primary); min-width: 65px; }
.pm-edt-matiere { flex: 1; color: var(--text); }
.pm-edt-classe { color: var(--text-sec); font-size: .72rem; }
:root.light .presence-modal { background: #fff!important; }
:root.light .pm-row { background: #f8fafc!important; }
:root.light .pm-row-head { background: #f1f5f9!important; }
:root.light .pm-edt-row { background: #f8fafc!important; }
:root.light .cal-present { background: #05966911; }
:root.light .cal-absent { background: #dc262611; }
:root.light .cal-retard { background: #d9770611; }
</style>