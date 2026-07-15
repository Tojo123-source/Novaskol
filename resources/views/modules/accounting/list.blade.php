<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Liste paiements</title>
<link rel="stylesheet" href="{{ asset('legacy/assets/fontawesome/css/all.min.css') }}">
@include('modules.professeur.bulletin.partials.styles')
@include('modules.accounting.partials.styles')
<style>
.payment-doc-viewport{width:100%;overflow:hidden;border-radius:8px}
.payment-doc-sheet{transform-origin:top left;will-change:transform}
.payment-doc-sheet .acc-table-wrap{overflow:visible}
@media(max-width:760px){
    .printable-list{padding:12px!important}
    .payment-doc-viewport{border:1px solid var(--border);background:var(--surface)}
    .payment-doc-sheet{width:900px;padding:12px}
    .payment-doc-sheet .acc-table{min-width:0;width:100%;table-layout:fixed}
    .payment-doc-sheet .acc-table th,.payment-doc-sheet .acc-table td{font-size:12px;padding:8px;word-break:break-word;overflow-wrap:anywhere}
    .payment-doc-sheet .acc-table th:nth-child(1),.payment-doc-sheet .acc-table td:nth-child(1){width:16%}
    .payment-doc-sheet .acc-table th:nth-child(2),.payment-doc-sheet .acc-table td:nth-child(2){width:18%}
    .payment-doc-sheet .acc-table th:nth-child(3),.payment-doc-sheet .acc-table td:nth-child(3){width:10%}
    .payment-doc-sheet .acc-table th:nth-child(4),.payment-doc-sheet .acc-table td:nth-child(4){width:15%}
    .payment-doc-sheet .acc-table th:nth-child(5),.payment-doc-sheet .acc-table td:nth-child(5){width:12%}
    .payment-doc-sheet .acc-table th:nth-child(6),.payment-doc-sheet .acc-table td:nth-child(6){width:11%}
    .payment-doc-sheet .acc-table th:nth-child(7),.payment-doc-sheet .acc-table td:nth-child(7){width:18%;text-align:right}
    .payment-doc-sheet h2{font-size:1.05rem;margin-bottom:8px}
    .payment-doc-sheet .print-meta{display:block;color:var(--text-sec);font-size:12px;line-height:1.45;margin-bottom:10px}
}
@media print{
    .payment-doc-viewport{overflow:visible!important;border:0!important;background:white!important}
    .payment-doc-sheet{width:auto!important;height:auto!important;transform:none!important;padding:0!important}
}
</style>
</head>
<body>
@include('modules.professeur.bulletin.partials.shell')
<header>
    <div class="header-left">
        <button class="burger-menu" onclick="toggleSidebar()"><i class="fa fa-bars"></i></button>
        <button id="fullscreen-btn" onclick="toggleFullscreen()"><i class="fa fa-expand"></i></button>
    </div>
    <div class="header-center">Liste des paiements</div>
</header>
<main>
<section class="acc-panel no-print">
    <form method="GET" class="acc-grid">
        <div><label>Annee</label><select name="annee_scolaire"><option value="">Toutes</option>@foreach($annees as $a)<option value="{{ $a }}" @selected($selectedAnnee===$a)>{{ $a }}</option>@endforeach</select></div>
        <div><label>Mois</label><select name="mois"><option value="">Tous</option>@foreach($months as $m)<option value="{{ $m }}" @selected($selectedMonth===$m)>{{ $m }}</option>@endforeach</select></div>
        <button class="kaly">Filtrer</button>
    </form>
</section>
<section class="acc-panel no-print">
    <h2>Resume</h2>
    <div class="acc-grid">
        <div class="money">Revenus: {{ number_format((float)$totalRevenus,0,',',' ') }} {{ novaskol_currency() }}</div>
        <div class="money out">Depenses: {{ number_format((float)$totalDepenses,0,',',' ') }} {{ novaskol_currency() }}</div>
        <div class="money">Solde: {{ number_format((float)($totalRevenus-$totalDepenses),0,',',' ') }} {{ novaskol_currency() }}</div>
        <button class="kaly" onclick="window.print()">Imprimer la liste affichee</button>
    </div>
</section>
<div class="tabs no-print">
    <button class="tab-link active" onclick="showList('revenus',this)">Revenus</button>
    <button class="tab-link" onclick="showList('depenses',this)">Depenses</button>
</div>

<section class="acc-panel printable-list" id="revenus">
    <div class="payment-doc-viewport">
        <div class="payment-doc-sheet">
            <h2>Revenus</h2>
            <div class="print-meta">{{ $ecole->nom ?? 'Ecole' }} - Annee: {{ $selectedAnnee ?: 'Toutes' }} - Mois: {{ $selectedMonth ?: 'Tous' }} - Total: {{ number_format((float)$totalRevenus,0,',',' ') }} {{ novaskol_currency() }}</div>
            <div class="acc-table-wrap">
                <table class="acc-table">
                    <thead><tr><th>Date</th><th>Personne</th><th>Mois</th><th>Categorie</th><th>Mode</th><th>Statut</th><th>Montant</th></tr></thead>
                    <tbody>
                    @forelse($revenus as $r)
                        <tr><td>{{ $r->date_enregistrement }}</td><td>{{ $r->nom_personne }}</td><td>{{ $r->mois }}</td><td>{{ $r->categorie }}</td><td>{{ $r->mode_paiement }}</td><td><span class="badge {{ $r->statut==='partiel'?'warn':'' }}">{{ $r->statut }}</span></td><td class="money">{{ number_format((float)$r->montant,0,',',' ') }} {{ novaskol_currency() }}</td></tr>
                    @empty
                        <tr><td colspan="7" class="muted">Aucun revenu.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

<section class="acc-panel printable-list" id="depenses" style="display:none">
    <div class="payment-doc-viewport">
        <div class="payment-doc-sheet">
            <h2>Depenses</h2>
            <div class="print-meta">{{ $ecole->nom ?? 'Ecole' }} - Annee: {{ $selectedAnnee ?: 'Toutes' }} - Mois: {{ $selectedMonth ?: 'Tous' }} - Total: {{ number_format((float)$totalDepenses,0,',',' ') }} {{ novaskol_currency() }}</div>
            <div class="acc-table-wrap">
                <table class="acc-table">
                    <thead><tr><th>Date</th><th>Personne</th><th>Mois</th><th>Categorie</th><th>Mode</th><th>Statut</th><th>Montant</th></tr></thead>
                    <tbody>
                    @forelse($depenses as $d)
                        <tr><td>{{ $d->date_enregistrement }}</td><td>{{ $d->nom_personne }}</td><td>{{ $d->mois }}</td><td>{{ $d->categorie }}</td><td>{{ $d->mode_paiement }}</td><td><span class="badge {{ $d->statut==='partiel'?'warn':'' }}">{{ $d->statut }}</span></td><td class="money out">{{ number_format((float)$d->montant,0,',',' ') }} {{ novaskol_currency() }}</td></tr>
                    @empty
                        <tr><td colspan="7" class="muted">Aucune depense.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
<footer>&copy; {{ date('Y') }} {{ $ecole->nom ?? 'Ecole' }}</footer>
</main>
<script>
function toggleSidebar(){document.getElementById('sidebar').classList.toggle('active')}
function toggleSub(e){const n=e.nextElementSibling;if(n)n.style.display=n.style.display==='none'?'block':'none'}
function toggleFullscreen(){document.fullscreenElement?document.exitFullscreen():document.documentElement.requestFullscreen()}
function showList(id,btn){document.querySelectorAll('.printable-list').forEach(x=>x.style.display='none');document.getElementById(id).style.display='block';document.querySelectorAll('.tabs .tab-link').forEach(x=>x.classList.remove('active'));btn.classList.add('active');requestAnimationFrame(scalePaymentLists)}
function scalePaymentLists(){
    document.querySelectorAll('.payment-doc-viewport').forEach(view=>{
        const sheet = view.querySelector('.payment-doc-sheet');
        if(!sheet)return;
        if(window.innerWidth > 760){
            sheet.style.transform = '';
            view.style.height = '';
            return;
        }
        const base = 900;
        const available = Math.max(280, view.clientWidth);
        const scale = Math.min(1, available / base);
        sheet.style.transform = `scale(${scale})`;
        view.style.height = `${sheet.scrollHeight * scale}px`;
    });
}
window.addEventListener('resize',scalePaymentLists);
document.addEventListener('DOMContentLoaded',scalePaymentLists);
</script>
</body>
</html>
