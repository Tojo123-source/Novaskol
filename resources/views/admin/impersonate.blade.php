<!DOCTYPE html>
<html lang="fr">
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Connexion en tant que - Novaskol</title>
<link rel="stylesheet" href="{{ asset('legacy/assets/fontawesome/css/all.min.css') }}">
<style>:root{--bg:#f1f5f9;--surface:#fff;--text:#1e293b;--muted:#64748b;--line:#e2e8f0;--blue:#2563eb;--green:#16a34a;--orange:#ea580c}*{box-sizing:border-box}body{margin:0;font-family:Inter,system-ui,sans-serif;background:var(--bg);color:var(--text)}.wrap{max-width:900px;margin:0 auto;padding:24px 16px}h1{font-size:1.3rem;margin:0 0 20px;display:flex;align-items:center;gap:8px}.tabs{display:flex;gap:4px;margin-bottom:20px;border-bottom:2px solid var(--line)}.tab{padding:10px 18px;cursor:pointer;font-weight:600;font-size:.85rem;color:var(--muted);border-bottom:2px solid transparent;margin-bottom:-2px;transition:all .15s}.tab.active{color:var(--blue);border-color:var(--blue)}.tab-content{display:none}.tab-content.active{display:block}.search-box{margin-bottom:14px}input{width:100%;padding:10px 14px;border:1px solid var(--line);border-radius:8px;font:inherit;outline:none}input:focus{border-color:var(--blue)}table{width:100%;border-collapse:collapse;font-size:.85rem;background:var(--surface);border-radius:8px;overflow:hidden}th,td{padding:8px 12px;text-align:left;border-bottom:1px solid var(--line)}th{color:var(--muted);font-weight:600;font-size:.78rem;background:#f8fafc}tr:hover{background:#f8fafc}.btn{display:inline-flex;align-items:center;gap:6px;padding:5px 12px;border-radius:6px;font-weight:600;font-size:.78rem;border:0;cursor:pointer;text-decoration:none}.btn-primary{background:var(--blue);color:#fff}.btn-sm{padding:4px 8px;font-size:.72rem}.flash{padding:12px 16px;border-radius:8px;margin-bottom:16px;font-size:.9rem}.flash-success{background:#dcfce7;color:#166534;border:1px solid #bbf7d0}.warn{background:#fef3c7;color:#92400e;border:1px solid #fde68a;border-radius:8px;padding:12px 16px;margin-bottom:16px;font-size:.85rem;display:flex;align-items:center;gap:8px}@media(max-width:640px){td,th{padding:6px 8px;font-size:.78rem}}</style>
</head>
<body>
<div class="wrap">
    @if (session('success'))
        <div class="flash flash-success">{{ session('success') }}</div>
    @endif

    <div class="warn"><i class="fa fa-shield"></i> Toute connexion en tant qu'utilisateur est enregistree dans le journal d'activite.</div>

    <h1><i class="fa fa-user-secret" style="color:var(--orange)"></i> Se connecter en tant que</h1>

    <div class="tabs">
        <div class="tab active" onclick="switchTab('enseignants',this)">Enseignants</div>
        <div class="tab" onclick="switchTab('eleves',this)">Eleves</div>
        <div class="tab" onclick="switchTab('parents',this)">Parents</div>
        <div class="tab" onclick="switchTab('staff',this)">Personnel</div>
    </div>

    <div id="tab-enseignants" class="tab-content active">
        <div class="search-box"><input type="text" placeholder="Rechercher un enseignant..." oninput="filterTable('tab-enseignants',this.value)"></div>
        @if ($enseignants->isEmpty())
            <p style="color:var(--muted);text-align:center;padding:30px">Aucun enseignant.</p>
        @else
            <table><thead><tr><th>Nom</th><th>Email</th><th></th></tr></thead>
                <tbody>
                @foreach ($enseignants as $u)
                    <tr>
                        <td>{{ $u->nom }}</td><td>{{ $u->email }}</td>
                        <td style="text-align:right">
                            <form method="POST" action="{{ route('admin.impersonate.login-as') }}" style="display:inline" onsubmit="return confirm('Se connecter en tant que {{ addslashes($u->nom) }} ?')">
                                @csrf
                                <input type="hidden" name="type" value="utilisateur">
                                <input type="hidden" name="id" value="{{ $u->id }}">
                                <button class="btn btn-primary btn-sm"><i class="fa fa-sign-in-alt"></i> Connecter</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif
    </div>

    <div id="tab-eleves" class="tab-content">
        <div class="search-box"><input type="text" placeholder="Rechercher un eleve..." oninput="filterTable('tab-eleves',this.value)"></div>
        @if ($eleves->isEmpty())
            <p style="color:var(--muted);text-align:center;padding:30px">Aucun eleve.</p>
        @else
            <table><thead><tr><th>Nom</th><th>Prenom</th><th>Email</th><th></th></tr></thead>
                <tbody>
                @foreach ($eleves as $u)
                    <tr>
                        <td>{{ $u->nom }}</td><td>{{ $u->prenom ?? '' }}</td><td>{{ $u->email ?? '' }}</td>
                        <td style="text-align:right">
                            <form method="POST" action="{{ route('admin.impersonate.login-as') }}" style="display:inline" onsubmit="return confirm('Se connecter en tant que {{ addslashes($u->prenom ?? $u->nom) }} ?')">
                                @csrf
                                <input type="hidden" name="type" value="eleve">
                                <input type="hidden" name="id" value="{{ $u->id }}">
                                <button class="btn btn-primary btn-sm"><i class="fa fa-sign-in-alt"></i> Connecter</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif
    </div>

    <div id="tab-parents" class="tab-content">
        <div class="search-box"><input type="text" placeholder="Rechercher un parent..." oninput="filterTable('tab-parents',this.value)"></div>
        @if ($parents->isEmpty())
            <p style="color:var(--muted);text-align:center;padding:30px">Aucun parent.</p>
        @else
            <table><thead><tr><th>Nom</th><th>Prenom</th><th>Email</th><th></th></tr></thead>
                <tbody>
                @foreach ($parents as $u)
                    <tr>
                        <td>{{ $u->nom }}</td><td>{{ $u->prenom ?? '' }}</td><td>{{ $u->email ?? '' }}</td>
                        <td style="text-align:right">
                            <form method="POST" action="{{ route('admin.impersonate.login-as') }}" style="display:inline" onsubmit="return confirm('Se connecter en tant que {{ addslashes($u->prenom ?? $u->nom) }} ?')">
                                @csrf
                                <input type="hidden" name="type" value="parent">
                                <input type="hidden" name="id" value="{{ $u->id }}">
                                <button class="btn btn-primary btn-sm"><i class="fa fa-sign-in-alt"></i> Connecter</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif
    </div>

    <div id="tab-staff" class="tab-content">
        <div class="search-box"><input type="text" placeholder="Rechercher un membre du personnel..." oninput="filterTable('tab-staff',this.value)"></div>
        @if ($staff->isEmpty())
            <p style="color:var(--muted);text-align:center;padding:30px">Aucun personnel.</p>
        @else
            <table><thead><tr><th>Nom</th><th>Prenom</th><th>Email</th><th></th></tr></thead>
                <tbody>
                @foreach ($staff as $u)
                    <tr>
                        <td>{{ $u->nom }}</td><td>{{ $u->prenom ?? '' }}</td><td>{{ $u->email ?? '' }}</td>
                        <td style="text-align:right">
                            <form method="POST" action="{{ route('admin.impersonate.login-as') }}" style="display:inline" onsubmit="return confirm('Se connecter en tant que {{ addslashes($u->prenom ?? $u->nom) }} ?')">
                                @csrf
                                <input type="hidden" name="type" value="staff">
                                <input type="hidden" name="id" value="{{ $u->id }}">
                                <button class="btn btn-primary btn-sm"><i class="fa fa-sign-in-alt"></i> Connecter</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif
    </div>
</div>

<script>
function switchTab(tab, el) {
    document.querySelectorAll('.tab-content').forEach(t => t.classList.remove('active'));
    document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
    document.getElementById('tab-' + tab).classList.add('active');
    el.classList.add('active');
}
function filterTable(tabId, value) {
    const rows = document.getElementById(tabId).querySelectorAll('tbody tr');
    const term = value.toLowerCase();
    rows.forEach(r => {
        r.style.display = r.textContent.toLowerCase().includes(term) ? '' : 'none';
    });
}
</script>
</body>
</html>
