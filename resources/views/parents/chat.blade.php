<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Responsable - {{ $ecole->nom ?? 'Novaskol' }}</title>
    <link rel="stylesheet" href="{{ asset('legacy/assets/fontawesome/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('legacy/fa/css/font-awesome.min.css') }}">
    @include('modules.professeur.bulletin.partials.styles')
    <style>
        .parent-chat{display:grid;grid-template-columns:310px minmax(0,1fr);gap:16px;height:calc(100vh - 130px);min-height:560px}
        .chat-card{background:var(--card);border:1px solid var(--border);border-radius:8px;box-shadow:0 12px 30px var(--shadow-soft);overflow:hidden;min-height:0}
        .responsible{padding:16px}.responsible img{width:48px;height:48px;border-radius:12px;object-fit:cover;border:1px solid var(--border)}.responsible h2{margin:0;color:var(--text)}.responsible p{color:var(--text-sec);line-height:1.5}
        .responsible-title{padding:8px 6px 14px;border-bottom:1px solid var(--border);margin-bottom:10px}.responsible-title strong{color:var(--primary);display:block;margin-bottom:4px}
        .responsible-list{display:grid;gap:9px;max-height:calc(100vh - 260px);overflow:auto}.responsible-link{display:flex;align-items:center;gap:10px;padding:10px;border:1px solid var(--border);border-radius:8px;background:var(--surface);color:var(--text);text-decoration:none}.responsible-link.active,.responsible-link:hover{border-color:var(--primary);background:rgba(0,200,83,.12)}.responsible-link span{min-width:0}.responsible-link strong{display:block;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}.responsible-link small{color:var(--text-sec)}
        .status-dot{width:10px;height:10px;border-radius:50%;display:inline-block;background:#64748b}.status-dot.online{background:#22c55e;box-shadow:0 0 0 3px rgba(34,197,94,.14)}
        .chat-head{height:70px;display:flex;align-items:center;gap:12px;padding:14px 16px;border-bottom:1px solid var(--border);background:var(--surface)}
        .chat-head img{width:44px;height:44px;border-radius:10px;object-fit:cover}.chat-head strong{display:block}.muted{color:var(--text-sec);font-size:.88rem}
        .messages{height:calc(100% - 150px);overflow:auto;padding:18px;display:flex;flex-direction:column;gap:12px}
        .bubble{max-width:min(680px,84%);padding:11px 13px;border-radius:8px;background:#1f2937;border:1px solid var(--border);align-self:flex-start}.bubble.mine{align-self:flex-end;background:rgba(0,200,83,.18);border-color:rgba(0,200,83,.35)}
        .bubble-name{color:var(--primary);font-weight:800;font-size:.82rem;margin-bottom:4px}.bubble-text{white-space:pre-wrap;overflow-wrap:anywhere;line-height:1.45}.bubble-meta{margin-top:6px;font-size:.76rem;color:var(--text-sec);display:flex;justify-content:flex-end;gap:8px}
        .bubble img.chat-image{max-width:260px;max-height:220px;border-radius:8px;display:block;margin-top:8px;border:1px solid var(--border)}.file-link{display:inline-flex;align-items:center;gap:8px;color:#bfdbfe;margin-top:8px;text-decoration:none;font-weight:700}
        .composer{min-height:80px;border-top:1px solid var(--border);padding:12px;display:grid;grid-template-columns:1fr auto auto;gap:10px;align-items:center;background:var(--surface)}.composer input[type="text"]{width:100%;padding:12px;border-radius:8px;border:1px solid var(--border);background:var(--card);color:var(--text)}
        .file-btn,.send-btn{border:0;border-radius:8px;padding:12px 15px;cursor:pointer;font-weight:900;color:white}.file-btn{background:#2563eb}.send-btn{background:var(--primary);color:#062b1d}
        .preview{display:none;position:absolute;left:12px;bottom:84px;width:min(320px,calc(100% - 24px));align-items:center;gap:10px;padding:9px 34px 9px 9px;background:var(--surface);border:1px solid rgba(96,165,250,.35);border-radius:8px;color:#bfdbfe;box-shadow:0 10px 26px rgba(0,0,0,.35);z-index:20}.preview img{width:48px;height:48px;border-radius:8px;object-fit:cover}.preview button{position:absolute;right:7px;top:6px;background:#ef4444;color:white;border:0;border-radius:50%;width:22px;height:22px;cursor:pointer}
        .chat-main{position:relative}.chat-error{display:none;grid-column:1/-1;color:#fecaca;background:rgba(239,68,68,.12);border:1px solid rgba(239,68,68,.25);border-radius:8px;padding:8px 10px}.empty-state{padding:28px;text-align:center;color:var(--text-sec)}
        @media(max-width:1100px){.parent-chat{grid-template-columns:1fr;height:auto}.messages{height:420px}}
        @media(max-width:700px){.parent-chat{margin-top:122px}.chat-head{height:auto;align-items:flex-start}.responsible{padding:14px}.composer{grid-template-columns:1fr auto;gap:8px}.composer input[type="text"]{grid-column:1 / -1}}
    </style>
</head>
<body>
@include('modules.professeur.bulletin.partials.shell', ['activeModule' => 'parent_chat'])
<header>
    <div class="header-left">
        <button class="burger-menu" onclick="toggleSidebar()"><i class="fa fa-bars"></i></button>
        <button id="fullscreen-btn" onclick="toggleFullscreen()"><i class="fa fa-expand"></i></button>
    </div>
    <h1>Contacter le responsable</h1>
</header>
<main class="parent-chat">
    <aside class="chat-card responsible">
        @if($responsibles->isNotEmpty())
            <div class="responsible-title">
                <strong>Assistants administration</strong>
                <small class="muted">Choisis le responsable a contacter.</small>
            </div>
            <div class="responsible-list">
                @foreach($responsibles as $staff)
                    <a class="responsible-link {{ $responsible && (int) $responsible->id === (int) $staff->id ? 'active' : '' }}" href="{{ route('parent.chat', ['responsable' => $staff->id]) }}">
                        <img src="{{ $staff->avatar_url }}" alt="">
                        <span>
                            <strong>{{ $staff->nom }}</strong>
                            <small><i class="status-dot {{ $staff->is_online ? 'online' : '' }}"></i> {{ $staff->staff_role ?? 'Assistant' }} - {{ $staff->departement ?? 'Administration' }}</small>
                        </span>
                    </a>
                @endforeach
            </div>
        @else
            <div class="empty-state">Aucun staff Assistant du departement Administration n'est encore disponible.</div>
        @endif
    </aside>
    <section class="chat-card chat-main">
        @if($responsible && $conversationId)
            <div class="chat-head">
                <img src="{{ $responsible->avatar_url }}" alt="">
                <div><strong>{{ $responsible->nom }}</strong><span class="muted">{{ $responsible->staff_role ?? 'Assistant' }} - {{ $responsible->departement ?? 'Administration' }}</span></div>
            </div>
            <div class="messages" id="messages"></div>
            <div class="preview" id="filePreview"><span id="previewContent"></span><button type="button" onclick="clearFilePreview()">x</button></div>
            <form class="composer" id="composer" enctype="multipart/form-data">
                <div class="chat-error" id="chatError"></div>
                <input type="text" name="content" id="messageInput" placeholder="Ecrire au responsable">
                <label class="file-btn"><i class="fa fa-paperclip"></i><input type="file" name="file" id="fileInput" style="display:none"></label>
                <button class="send-btn" type="submit"><i class="fa fa-send"></i></button>
            </form>
        @else
            <div class="empty-state">Discussion indisponible pour le moment.</div>
        @endif
    </section>
</main>
<script>
const csrf = document.querySelector('meta[name="csrf-token"]').content;
const conversationId = {{ (int) $conversationId }};
const chatMessagesBaseUrl = @json(url('/chat/messages'));
let lastId = 0;
let initialMessagesLoaded = false;
function toggleSidebar(){document.getElementById('sidebar').classList.toggle('active');}
function toggleSub(el){const n=el.nextElementSibling;if(n)n.style.display=n.style.display==='none'?'block':'none';}
function toggleFullscreen(){if(!document.fullscreenElement){document.documentElement.requestFullscreen();}else{document.exitFullscreen();}}
function escapeHtml(v){return String(v).replace(/[&<>"']/g, s => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#039;'}[s]));}
function messageHtml(m){const file=m.file_url?(m.type==='image'?`<a href="${m.file_url}" target="_blank"><img class="chat-image" src="${m.file_url}" alt=""></a>`:`<a class="file-link" href="${m.file_url}" target="_blank"><i class="fa fa-file"></i>${escapeHtml(m.file_name||'Fichier')}</a>`):'';return `<div class="bubble ${m.mine?'mine':''}">${m.mine?'':`<div class="bubble-name">${escapeHtml(m.sender_name)}</div>`}<div class="bubble-text">${escapeHtml(m.content||'')}</div>${file}<div class="bubble-meta"><span>${escapeHtml(m.created_label||'')}</span>${m.mine?`<span>${m.is_read?'lu':(m.is_delivered?'recu':'envoye')}</span>`:''}</div></div>`}
function showError(text){const e=document.getElementById('chatError'); if(e){e.textContent=text; e.style.display='block';}}
function hideError(){const e=document.getElementById('chatError'); if(e){e.textContent=''; e.style.display='none';}}
async function loadMessages(){if(!conversationId)return;const res=await fetch(`${chatMessagesBaseUrl}/${conversationId}?after=${lastId}`);if(!res.ok)return;const data=await res.json();if(!data.success)return;const box=document.getElementById('messages');data.messages.forEach(m=>{lastId=Math.max(lastId,Number(m.id));box.insertAdjacentHTML('beforeend',messageHtml(m));if(initialMessagesLoaded&&!m.mine)playChatSound();});if(data.messages.length)box.scrollTop=box.scrollHeight;initialMessagesLoaded=true;}
document.getElementById('composer')?.addEventListener('submit',async e=>{e.preventDefault();hideError();const form=new FormData(e.currentTarget);const res=await fetch(`${chatMessagesBaseUrl}/${conversationId}`,{method:'POST',headers:{'X-CSRF-TOKEN':csrf,'Accept':'application/json'},body:form});let data={};try{data=await res.json();}catch(_){}if(res.ok&&data.success){e.currentTarget.reset();clearFilePreview();if(data.message){lastId=Math.max(lastId,Number(data.message.id));document.getElementById('messages').insertAdjacentHTML('beforeend',messageHtml(data.message));document.getElementById('messages').scrollTop=document.getElementById('messages').scrollHeight;}await loadMessages();}else{showError(data.message||'Envoi impossible.');}});
document.getElementById('fileInput')?.addEventListener('change',e=>{const file=e.target.files[0];const wrap=document.getElementById('filePreview');const content=document.getElementById('previewContent');if(!file){clearFilePreview();return;}if(file.type.startsWith('image/')){const url=URL.createObjectURL(file);content.innerHTML=`<img src="${url}" alt=""><span>${escapeHtml(file.name)} (${Math.round(file.size/1024)} Ko)</span>`;}else{content.innerHTML=`<i class="fa fa-file"></i> ${escapeHtml(file.name)} (${Math.round(file.size/1024)} Ko)`;}wrap.style.display='flex';});
function clearFilePreview(){const input=document.getElementById('fileInput');if(input)input.value='';const wrap=document.getElementById('filePreview');if(wrap)wrap.style.display='none';}
function playChatSound(){try{const ctx=new(window.AudioContext||window.webkitAudioContext)();const osc=ctx.createOscillator();const gain=ctx.createGain();osc.frequency.value=660;gain.gain.value=.05;osc.connect(gain);gain.connect(ctx.destination);osc.start();setTimeout(()=>{osc.stop();ctx.close();},110)}catch(e){}}
loadMessages();
if(conversationId)setInterval(loadMessages,1800);
</script>
</body>
</html>
