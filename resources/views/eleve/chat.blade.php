<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Messagerie - {{ $ecole->nom ?? 'Novaskol' }}</title>
    <link rel="stylesheet" href="{{ asset('legacy/assets/fontawesome/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('legacy/fa/css/font-awesome.min.css') }}">
    @include('modules.professeur.bulletin.partials.styles')
    <style>
        body { font-family: 'Inter', sans-serif; background: var(--bg); color: var(--text); }
        .eleve-chat { display: grid; grid-template-columns: 280px minmax(0,1fr); gap: 16px; margin: 88px 20px 20px 256px; height: calc(100vh - 130px); min-height: 500px; }
        .chat-card { background: var(--card); border: 1px solid var(--border); border-radius: 8px; box-shadow: 0 12px 30px var(--shadow-soft); overflow: hidden; min-height: 0; }
        .contacts-wrap { padding: 14px; }
        .contacts-wrap .section-title { color: var(--primary); font-weight: 700; font-size: .85rem; display: flex; align-items: center; gap: 6px; margin: 12px 0 8px; padding-bottom: 6px; border-bottom: 1px solid var(--border); }
        .contacts-wrap .section-title:first-child { margin-top: 0; }
        .contact-list { display: grid; gap: 6px; max-height: 300px; overflow: auto; }
        .contact-link { display: flex; align-items: center; gap: 10px; padding: 9px 10px; border: 1px solid var(--border); border-radius: 8px; background: var(--surface); color: var(--text); text-decoration: none; transition: all .12s; }
        .contact-link.active, .contact-link:hover { border-color: var(--primary); background: var(--primary-glow); }
        .contact-link .avatar { width: 36px; height: 36px; border-radius: 50%; background: var(--primary-glow); color: var(--primary); display: grid; place-items: center; font-weight: 700; font-size: .85rem; flex-shrink: 0; }
        .contact-link span { min-width: 0; }
        .contact-link strong { display: block; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; font-size: .85rem; }
        .contact-link small { color: var(--text-sec); font-size: .72rem; display: block; }
        .status-dot { width: 8px; height: 8px; border-radius: 50%; display: inline-block; background: #64748b; margin-right: 4px; }
        .status-dot.online { background: #22c55e; }

        .chat-main { display: flex; flex-direction: column; }
        .chat-head { height: 64px; display: flex; align-items: center; gap: 12px; padding: 12px 16px; border-bottom: 1px solid var(--border); background: var(--surface); flex-shrink: 0; }
        .chat-head .avatar { width: 40px; height: 40px; border-radius: 50%; background: var(--primary-glow); color: var(--primary); display: grid; place-items: center; font-weight: 700; font-size: .9rem; flex-shrink: 0; }
        .chat-head strong { display: block; font-size: .95rem; }
        .chat-head small { color: var(--text-sec); font-size: .78rem; }

        .messages { flex: 1; overflow: auto; padding: 16px 18px; display: flex; flex-direction: column; gap: 10px; }
        .bubble { max-width: min(680px, 84%); padding: 10px 13px; border-radius: 8px; background: #1f2937; border: 1px solid var(--border); align-self: flex-start; }
        .bubble.mine { align-self: flex-end; background: rgba(0,200,83,.18); border-color: rgba(0,200,83,.35); }
        .bubble-name { color: var(--primary); font-weight: 800; font-size: .8rem; margin-bottom: 3px; }
        .bubble-text { white-space: pre-wrap; overflow-wrap: anywhere; line-height: 1.45; font-size: .88rem; }
        .bubble-meta { margin-top: 5px; font-size: .72rem; color: var(--text-sec); display: flex; justify-content: flex-end; gap: 8px; }
        .bubble img.chat-image { max-width: 240px; max-height: 200px; border-radius: 8px; display: block; margin-top: 6px; }

        .composer { min-height: 72px; border-top: 1px solid var(--border); padding: 10px 12px; display: grid; grid-template-columns: 1fr auto auto; gap: 8px; align-items: center; background: var(--surface); flex-shrink: 0; }
        .composer input[type="text"] { width: 100%; padding: 11px; border-radius: 8px; border: 1px solid var(--border); background: var(--card); color: var(--text); font-size: .88rem; }
        .file-btn, .send-btn { border: 0; border-radius: 8px; padding: 11px 14px; cursor: pointer; font-weight: 800; color: white; }
        .file-btn { background: #2563eb; }
        .send-btn { background: var(--primary); color: #062b1d; }
        .preview { display: none; position: absolute; left: 12px; bottom: 76px; width: min(300px, calc(100% - 24px)); align-items: center; gap: 8px; padding: 8px 32px 8px 8px; background: var(--surface); border: 1px solid var(--border); border-radius: 8px; z-index: 20; }
        .preview img { width: 40px; height: 40px; border-radius: 6px; object-fit: cover; }
        .preview button { position: absolute; right: 6px; top: 5px; background: #ef4444; color: white; border: 0; border-radius: 50%; width: 20px; height: 20px; cursor: pointer; font-size: .7rem; }
        .chat-main { position: relative; }
        .chat-error { display: none; color: #fecaca; background: rgba(239,68,68,.12); border: 1px solid rgba(239,68,68,.25); border-radius: 8px; padding: 7px 10px; font-size: .82rem; }
        .empty-state { padding: 40px; text-align: center; color: var(--text-sec); }

        @media(max-width:1180px) { .eleve-chat { margin-left: 16px; margin-right: 16px; } }
        @media(max-width:900px) { .eleve-chat { grid-template-columns: 1fr; height: auto; } .messages { height: 380px; } .contacts-wrap .contact-list { max-height: 200px; } }
        @media(max-width:700px) { .eleve-chat { margin-top: 110px; } }
    </style>
</head>
<body>
@include('modules.professeur.bulletin.partials.shell', ['activeModule' => 'eleve_chat'])
<header>
    <div class="header-left">
        <button class="burger-menu" onclick="toggleSidebar()"><i class="fa fa-bars"></i></button>
        <button id="fullscreen-btn" onclick="toggleFullscreen()"><i class="fa fa-expand"></i></button>
    </div>
    <div class="header-center">Messagerie</div>
</header>

<main class="eleve-chat">
    <aside class="chat-card contacts-wrap">
        @if($classmates->isNotEmpty())
            <div class="section-title"><i class="fa fa-users"></i> Ma classe : {{ $classe->nom ?? '' }}</div>
            <div class="contact-list">
                @foreach($classmates as $cm)
                    @php $initial = mb_substr($cm->prenom ?? $cm->nom, 0, 1); @endphp
                    <a class="contact-link {{ $contact && (int) $contact->id === (int) $cm->id && $contactType === 'classmate' ? 'active' : '' }}"
                       href="{{ route('eleve.portal.chat', ['with' => 'classmate', 'id' => $cm->id]) }}">
                        <div class="avatar">{{ $initial }}</div>
                        <span><strong>{{ $cm->prenom }} {{ $cm->nom }}</strong><small><span class="status-dot"></span> Camarade</small></span>
                    </a>
                @endforeach
            </div>
        @endif

        <div class="section-title"><i class="fa fa-chalkboard-teacher"></i> Mes enseignants</div>
        <div class="contact-list">
            @forelse($teachers as $t)
                @php $initial = mb_substr($t->prenom ?? $t->nom, 0, 1); @endphp
                <a class="contact-link {{ $contact && (int) $contact->id === (int) $t->id && $contactType === 'teacher' ? 'active' : '' }}"
                   href="{{ route('eleve.portal.chat', ['with' => 'teacher', 'id' => $t->id]) }}">
                    <div class="avatar">{{ $initial }}</div>
                    <span><strong>{{ $t->prenom }} {{ $t->nom }}</strong><small><span class="status-dot"></span> Enseignant</small></span>
                </a>
            @empty
                <div class="empty-state" style="padding:12px">Aucun enseignant</div>
            @endforelse
        </div>

        <div class="section-title"><i class="fa fa-user-tie"></i> Staff</div>
        <div class="contact-list" style="max-height:160px">
            @forelse($staff as $s)
                @php $initial = mb_substr($s->nom, 0, 1); @endphp
                <a class="contact-link {{ $contact && (int) $contact->id === (int) $s->id && $contactType === 'staff' ? 'active' : '' }}"
                   href="{{ route('eleve.portal.chat', ['with' => 'staff', 'id' => $s->id]) }}">
                    <div class="avatar">{{ $initial }}</div>
                    <span><strong>{{ $s->nom }}</strong><small><span class="status-dot"></span> Staff</small></span>
                </a>
            @empty
                <div class="empty-state" style="padding:12px">Aucun membre du staff</div>
            @endforelse
        </div>

        <div class="section-title"><i class="fa fa-users"></i> Chat Groupe</div>
        <div class="contact-list" style="max-height:160px">
            @forelse($groupConversations as $g)
                <a class="contact-link {{ $contact && (int) $conversationId === (int) $g->id && $contactType === 'group' ? 'active' : '' }}"
                   href="{{ route('eleve.portal.chat', ['group' => $g->id]) }}">
                    <div class="avatar"><i class="fa fa-users" style="font-size:.8rem"></i></div>
                    <span><strong>{{ $g->name }}</strong><small>Groupe</small></span>
                </a>
            @empty
                <div class="empty-state" style="padding:12px">Aucun groupe. Creez-en un !</div>
            @endforelse
            <button onclick="document.getElementById('createGroupModal').style.display='flex'" class="contact-link" style="border-style:dashed;justify-content:center;color:var(--primary)">
                <i class="fa fa-plus-circle"></i> <strong>Creer un groupe</strong>
            </button>
        </div>
    </aside>

    <section class="chat-card chat-main">
        @if($contact && $conversationId)
            <div class="chat-head">
                <div class="avatar">{{ mb_substr($contact->prenom ?? $contact->nom, 0, 1) }}</div>
                <div>
                    <strong>{{ $contact->prenom ?? '' }} {{ $contact->nom }}</strong>
                    <small>{{ $contactType === 'teacher' ? 'Enseignant' : ($contactType === 'staff' ? 'Staff' : ($contactType === 'group' ? 'Groupe' : 'Camarade')) }}</small>
                </div>
            </div>
            <div class="messages" id="messages"></div>
            <div class="preview" id="filePreview"><span id="previewContent"></span><button type="button" onclick="clearFilePreview()">x</button></div>
            <form class="composer" id="composer" enctype="multipart/form-data">
                <div class="chat-error" id="chatError"></div>
                <input type="text" name="content" id="messageInput" placeholder="Ecrire un message...">
                <label class="file-btn"><i class="fa fa-paperclip"></i><input type="file" name="file" id="fileInput" style="display:none"></label>
                <button class="send-btn" type="submit"><i class="fa fa-send"></i></button>
            </form>
        @else
            <div class="empty-state">
                <i class="fa fa-comments" style="font-size:2.5rem;opacity:.4;display:block;margin-bottom:10px"></i>
                <p>Selectionnez un contact pour discuter.</p>
            </div>
        @endif
    </section>
</main>

<div id="createGroupModal" style="display:none;position:fixed;inset:0;z-index:30000;background:rgba(0,0,0,.6);align-items:center;justify-content:center" onclick="if(event.target===this)this.style.display='none'">
    <div style="background:var(--card);border:1px solid var(--border);border-radius:12px;padding:24px;width:min(420px,calc(100vw - 32px));max-height:80vh;overflow:auto">
        <h2 style="margin:0 0 16px;font-size:1.1rem;color:var(--primary)"><i class="fa fa-users"></i> Creer un groupe</h2>
        <form method="POST" action="{{ route('eleve.portal.chat.create-group') }}">
            @csrf
            <label style="display:block;font-size:.82rem;color:var(--text-sec);margin-bottom:4px">Nom du groupe</label>
            <input type="text" name="name" required maxlength="100" style="width:100%;padding:9px 12px;border-radius:8px;border:1px solid var(--border);background:var(--surface);color:var(--text);margin-bottom:14px">
            <label style="display:block;font-size:.82rem;color:var(--text-sec);margin-bottom:6px">Ajouter des camarades</label>
            <div style="display:grid;gap:6px;max-height:240px;overflow:auto;margin-bottom:14px">
                @foreach($classmates as $cm)
                    <label style="display:flex;align-items:center;gap:8px;padding:7px 10px;border:1px solid var(--border);border-radius:8px;background:var(--surface);cursor:pointer;font-size:.85rem">
                        <input type="checkbox" name="members[]" value="{{ $cm->id }}">
                        {{ $cm->prenom }} {{ $cm->nom }}
                    </label>
                @endforeach
            </div>
            <div style="display:flex;gap:8px;justify-content:flex-end">
                <button type="button" onclick="document.getElementById('createGroupModal').style.display='none'" style="padding:8px 16px;border-radius:8px;border:1px solid var(--border);background:transparent;color:var(--text);cursor:pointer">Annuler</button>
                <button type="submit" style="padding:8px 16px;border-radius:8px;border:0;background:var(--primary);color:#fff;font-weight:600;cursor:pointer"><i class="fa fa-check"></i> Creer</button>
            </div>
        </form>
    </div>
</div>

<script>
const csrf = document.querySelector('meta[name="csrf-token"]').content;
const conversationId = {{ (int) ($conversationId ?? 0) }};
const chatMessagesBaseUrl = @json(url('/chat/messages'));
let lastId = 0;
let initialMessagesLoaded = false;

function escapeHtml(v){return String(v).replace(/[&<>"']/g, s => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#039;'}[s]));}
function messageHtml(m){
    const file=m.file_url?(m.type==='image'?`<a href="${m.file_url}" target="_blank"><img class="chat-image" src="${m.file_url}" alt=""></a>`:`<a class="file-link" href="${m.file_url}" target="_blank"><i class="fa fa-file"></i>${escapeHtml(m.file_name||'Fichier')}</a>`):'';
    return `<div class="bubble ${m.mine?'mine':''}">${m.mine?'':`<div class="bubble-name">${escapeHtml(m.sender_name)}</div>`}<div class="bubble-text">${escapeHtml(m.content||'')}</div>${file}<div class="bubble-meta"><span>${escapeHtml(m.created_label||'')}</span>${m.mine?`<span>${m.is_read?'lu':(m.is_delivered?'recu':'envoye')}</span>`:''}</div></div>`;
}
function showError(text){const e=document.getElementById('chatError');if(e){e.textContent=text;e.style.display='block';}}
function hideError(){const e=document.getElementById('chatError');if(e){e.textContent='';e.style.display='none';}}

async function loadMessages(){
    if(!conversationId)return;
    const res=await fetch(`${chatMessagesBaseUrl}/${conversationId}?after=${lastId}`);
    if(!res.ok)return;
    const data=await res.json();
    if(!data.success)return;
    const box=document.getElementById('messages');
    data.messages.forEach(m=>{
        lastId=Math.max(lastId,Number(m.id));
        box.insertAdjacentHTML('beforeend',messageHtml(m));
        if(initialMessagesLoaded&&!m.mine) playChatSound();
    });
    if(data.messages.length) box.scrollTop=box.scrollHeight;
    initialMessagesLoaded=true;
}

document.getElementById('composer')?.addEventListener('submit',async e=>{
    e.preventDefault();hideError();
    const form=new FormData(e.currentTarget);
    const res=await fetch(`${chatMessagesBaseUrl}/${conversationId}`,{
        method:'POST',headers:{'X-CSRF-TOKEN':csrf,'Accept':'application/json'},body:form
    });
    let data={}; try{data=await res.json();}catch(_){}
    if(res.ok&&data.success){
        e.currentTarget.reset();clearFilePreview();
        if(data.message){
            lastId=Math.max(lastId,Number(data.message.id));
            document.getElementById('messages').insertAdjacentHTML('beforeend',messageHtml(data.message));
            document.getElementById('messages').scrollTop=document.getElementById('messages').scrollHeight;
        }
        await loadMessages();
    }else{showError(data.message||'Envoi impossible.');}
});

document.getElementById('fileInput')?.addEventListener('change',e=>{
    const file=e.target.files[0];const wrap=document.getElementById('filePreview');const content=document.getElementById('previewContent');
    if(!file){clearFilePreview();return;}
    if(file.type.startsWith('image/')){const url=URL.createObjectURL(file);content.innerHTML=`<img src="${url}" alt=""><span>${escapeHtml(file.name)} (${Math.round(file.size/1024)} Ko)</span>`;}
    else{content.innerHTML=`<i class="fa fa-file"></i> ${escapeHtml(file.name)} (${Math.round(file.size/1024)} Ko)`;}
    wrap.style.display='flex';
});
function clearFilePreview(){const input=document.getElementById('fileInput');if(input)input.value='';const wrap=document.getElementById('filePreview');if(wrap)wrap.style.display='none';}
function playChatSound(){try{const ctx=new(window.AudioContext||window.webkitAudioContext)();const osc=ctx.createOscillator();const gain=ctx.createGain();osc.frequency.value=660;gain.gain.value=.05;osc.connect(gain);gain.connect(ctx.destination);osc.start();setTimeout(()=>{osc.stop();ctx.close();},110)}catch(e){}}
function toggleSub(e){const s=e.nextElementSibling,a=e.querySelector('.arrow');s.style.display=s.style.display==='block'?'none':'block';a.classList.toggle('fa-chevron-down');a.classList.toggle('fa-chevron-up');}
function toggleSidebar(){document.getElementById('sidebar').classList.toggle('active');}
function toggleFullscreen(){const i=document.getElementById('fullscreen-icon');if(!document.fullscreenElement){document.documentElement.requestFullscreen();i.classList.replace('fa-expand','fa-compress')}else{document.exitFullscreen();i.classList.replace('fa-compress','fa-expand')}}

loadMessages();
if(conversationId) setInterval(loadMessages, 1800);
</script>
</body>
</html>
