<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Novaskol Connecte</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/png" href="{{ asset('novaskol-icon.png') }}">
    <link rel="stylesheet" href="{{ asset('legacy/assets/fontawesome/css/all.min.css') }}">
    <style>
        :root{--bg:#07111f;--panel:#101b2d;--surface:#16243a;--text:#f8fafc;--muted:#9fb0c6;--line:rgba(148,163,184,.22);--green:#00c853;--blue:#38bdf8;--danger:#fb7185}
        *{box-sizing:border-box}
        html,body{margin:0;min-height:100%;max-width:100%;overflow-x:hidden}
        body{font-family:Inter,system-ui,-apple-system,Segoe UI,sans-serif;background:radial-gradient(circle at top left,rgba(0,200,83,.14),transparent 34%),linear-gradient(135deg,#07111f,#0b1220 45%,#07111f);color:var(--text)}
        .wrap{min-height:100vh;display:grid;grid-template-columns:minmax(0,.9fr) minmax(320px,520px);gap:28px;align-items:center;padding:34px clamp(16px,4vw,58px)}
        .brand{min-width:0}
        .logo{display:inline-flex;align-items:center;gap:12px;font-weight:950;font-size:1.7rem;margin-bottom:34px}
        .logo i{color:var(--green)}
        .hero h1{font-size:clamp(2.1rem,5.4vw,5.4rem);line-height:1.02;margin:0 0 18px;letter-spacing:0}
        .hero p{max-width:680px;color:var(--muted);font-size:1.08rem;line-height:1.7;margin:0 0 24px}
        .steps{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:12px;max-width:760px}
        .step{background:rgba(16,27,45,.76);border:1px solid var(--line);border-radius:10px;padding:14px;backdrop-filter:blur(12px)}
        .step b{display:grid;place-items:center;width:30px;height:30px;border-radius:50%;background:rgba(0,200,83,.14);color:var(--green);margin-bottom:10px}
        .step strong{display:block;font-size:.92rem;margin-bottom:6px}
        .step span{display:block;color:var(--muted);font-size:.82rem;line-height:1.42}
        .card{background:rgba(16,27,45,.94);border:1px solid var(--line);border-radius:14px;padding:22px;box-shadow:0 24px 70px rgba(0,0,0,.34)}
        .card h2{margin:0 0 8px;font-size:1.35rem}
        .card > p{margin:0 0 18px;color:var(--muted);line-height:1.55}
        .field{margin-bottom:12px}
        label{display:block;font-size:.82rem;font-weight:850;color:#dbeafe;margin-bottom:7px}
        input,select{width:100%;min-width:0;border:1px solid var(--line);border-radius:10px;background:var(--surface);color:var(--text);padding:12px 13px;font:inherit;outline:none}
        input:focus,select:focus{border-color:var(--blue);box-shadow:0 0 0 3px rgba(56,189,248,.14)}
        .row{display:grid;grid-template-columns:1fr 1fr;gap:10px}
        .actions{display:grid;grid-template-columns:1fr;gap:10px;margin-top:16px}
        button{border:0;border-radius:10px;padding:12px 14px;font-weight:900;cursor:pointer;font:inherit}
        .primary{background:var(--green);color:#03130a}
        .secondary{background:rgba(56,189,248,.12);border:1px solid rgba(56,189,248,.3);color:#bae6fd}
        .status{display:none;margin-top:14px;border-radius:10px;padding:12px;line-height:1.45;font-size:.9rem}
        .status.ok{display:block;background:rgba(0,200,83,.12);border:1px solid rgba(0,200,83,.32);color:#bbf7d0}
        .status.warn{display:block;background:rgba(251,113,133,.12);border:1px solid rgba(251,113,133,.34);color:#fecdd3}
        .summary{display:none;margin-top:16px;border:1px solid var(--line);border-radius:10px;overflow:hidden}
        .summary.active{display:block}
        .summary-row{display:flex;justify-content:space-between;gap:10px;padding:10px 12px;border-bottom:1px solid var(--line);color:var(--muted)}
        .summary-row:last-child{border-bottom:0}
        .summary-row strong{color:var(--text);text-align:right}
        .mini{margin-top:12px;color:var(--muted);font-size:.8rem;line-height:1.45}
        @media(max-width:920px){.wrap{grid-template-columns:1fr;padding:24px 14px}.brand{order:2}.card{order:1}.steps{grid-template-columns:1fr}.hero h1{font-size:2.25rem}.hero p{font-size:.96rem}.logo{margin-bottom:18px}}
        @media(max-width:520px){.wrap{display:block}.card{padding:16px;border-radius:12px}.row{grid-template-columns:1fr}.hero{margin-top:24px}.step{padding:12px}.actions button{width:100%}}
    </style>
</head>
<body>
<main class="wrap">
    <section class="brand">
        <div class="logo"><i class="fa fa-graduation-cap"></i> Novaskol Connecte</div>
        <div class="hero">
            <h1>Connecter cet appareil a une ecole existante</h1>
            <p>Cette edition ne cree pas d'ecole. Elle se lie a l'appareil principal de l'etablissement, puis recupere seulement les donnees autorisees pour le compte connecte.</p>
        </div>
        <div class="steps">
            <div class="step"><b>1</b><strong>Adresse</strong><span>Entrer ou scanner l'adresse locale affichee par l'ecole.</span></div>
            <div class="step"><b>2</b><strong>Compte</strong><span>Saisir le code d'appairage et le compte cree par l'administration.</span></div>
            <div class="step"><b>3</b><strong>Espace</strong><span>Novaskol prepare le role, les permissions et les donnees utiles.</span></div>
        </div>
    </section>

    <section class="card">
        <h2>Connexion a l'ecole</h2>
        <p>Utilisez cette page sur l'appareil a connecter pendant qu'il est sur le meme reseau que l'appareil principal.</p>
        <form id="connectForm">
            <div class="field">
                <label>Adresse locale de l'ecole</label>
                <input id="serverUrl" name="serverUrl" value="{{ rtrim($defaultServer, '/') }}" placeholder="http://192.168.1.15:8001" required>
            </div>
            <div class="row">
                <div class="field">
                    <label>Code d'appairage</label>
                    <input id="pairingCode" name="pairingCode" placeholder="ABC-123" autocomplete="one-time-code" required>
                </div>
                <div class="field">
                    <label>Role</label>
                    <select id="role" name="role">
                        <option value="">Automatique</option>
                        <option value="enseignant">Enseignant</option>
                        <option value="staff">Staff</option>
                        <option value="parent">Parent</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
            </div>
            <div class="field">
                <label>Email du compte</label>
                <input id="email" name="email" type="email" placeholder="nom@ecole.com" required>
            </div>
            <div class="field">
                <label>Mot de passe</label>
                <input id="password" name="password" type="password" required>
            </div>
            <div class="field">
                <label>Nom de cet appareil</label>
                <input id="deviceName" name="deviceName" placeholder="Telephone Tojo">
            </div>
            <div class="actions">
                <button class="secondary" type="button" id="testBtn"><i class="fa fa-wifi"></i> Verifier l'adresse</button>
                <button class="primary" type="submit"><i class="fa fa-link"></i> Connecter l'appareil</button>
            </div>
        </form>
        <div id="status" class="status"></div>
        <div id="summary" class="summary"></div>
        <p class="mini">Les donnees recues sont conservees localement sur cet appareil. Les actions hors connexion seront synchronisees quand l'appareil retrouve l'ecole.</p>
    </section>
</main>
<script>
const statusBox=document.getElementById('status');
const summary=document.getElementById('summary');
function normalizeServer(value){return String(value||'').trim().replace(/\/+$/,'')}
function deviceUuid(){let id=localStorage.getItem('novaskol_connected_device_uuid');if(!id){id=(crypto&&crypto.randomUUID)?crypto.randomUUID():'device-'+Date.now()+'-'+Math.random().toString(16).slice(2);localStorage.setItem('novaskol_connected_device_uuid',id)}return id}
function deviceType(){const w=Math.min(screen.width||innerWidth,innerWidth);return w<=640?'telephone':(w<=1024?'tablette':'pc')}
function showStatus(type,message){statusBox.className='status '+type;statusBox.textContent=message}
async function fetchJson(url,options={}){const res=await fetch(url,options);const data=await res.json().catch(()=>({success:false,message:'Reponse illisible.'}));if(!res.ok||data.success===false){throw new Error(data.message||'Operation impossible.')}return data}
async function checkServer(){
    const base=normalizeServer(document.getElementById('serverUrl').value);
    if(!base){showStatus('warn','Adresse locale requise.');return null}
    const manifest=await fetchJson(base+'/reseau-local/manifest-appareil');
    localStorage.setItem('novaskol_connected_school_url',base);
    showStatus(manifest.pairing?.active?'ok':'warn',manifest.pairing?.active?'Ecole detectee : '+manifest.school.nom+'. Code actif.':'Ecole detectee : '+manifest.school.nom+', mais aucun code actif pour le moment.');
    return manifest;
}
document.getElementById('testBtn').addEventListener('click',async()=>{try{await checkServer()}catch(e){showStatus('warn',e.message)}});
document.getElementById('connectForm').addEventListener('submit',async e=>{
    e.preventDefault();
    summary.className='summary';
    summary.innerHTML='';
    try{
        const manifest=await checkServer();
        const base=normalizeServer(document.getElementById('serverUrl').value);
        const payload={
            code:document.getElementById('pairingCode').value.trim(),
            email:document.getElementById('email').value.trim(),
            password:document.getElementById('password').value,
            role:document.getElementById('role').value||undefined,
            uuid:deviceUuid(),
            nom:document.getElementById('deviceName').value.trim()||undefined,
            type_appareil:deviceType(),
            plateforme:(navigator.userAgent||'').slice(0,120)
        };
        const data=await fetchJson((manifest?.endpoints?.pair)||base+'/reseau-local/appairer-appareil',{method:'POST',headers:{'Content-Type':'application/json','Accept':'application/json'},body:JSON.stringify(payload)});
        localStorage.setItem('novaskol_connected_profile',JSON.stringify({school:data.school,device:data.device,user:data.user,permissions:data.permissions,connected_at:new Date().toISOString()}));
        localStorage.setItem('novaskol_connected_bootstrap',JSON.stringify(data.bootstrap));
        localStorage.setItem('novaskol_connected_school_url',base);
        const rows=(data.bootstrap?.summary||[]).map(r=>`<div class="summary-row"><span>${r.table}</span><strong>${r.total}</strong></div>`).join('');
        summary.innerHTML=`<div class="summary-row"><span>Ecole</span><strong>${data.school.nom}</strong></div><div class="summary-row"><span>Compte</span><strong>${data.user.nom} (${data.user.role})</strong></div>${rows}`;
        summary.className='summary active';
        showStatus('ok','Appareil connecte. Les premieres donnees autorisees sont enregistrees localement.');
    }catch(err){showStatus('warn',err.message)}
});
document.addEventListener('DOMContentLoaded',()=>{
    const saved=localStorage.getItem('novaskol_connected_school_url');
    if(saved)document.getElementById('serverUrl').value=saved;
});
</script>
</body>
</html>
