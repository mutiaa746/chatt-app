<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Chat App</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --bg-app:        #0f1117;
            --bg-sidebar:    #161b22;
            --bg-chat:       #0d1117;
            --bg-bubble-me:  #2ea043;
            --bg-bubble-you: #21262d;
            --accent:        #2ea043;
            --accent-light:  #3fb950;
            --text-primary:  #e6edf3;
            --text-secondary:#8b949e;
            --text-muted:    #484f58;
            --border:        #30363d;
            --online:        #3fb950;
            --offline:       #8b949e;
            --hover:         #1c2128;
            --active:        #1f2937;
            --danger:        #f85149;
        }
        html, body { height: 100%; font-family: 'Plus Jakarta Sans', sans-serif; background: var(--bg-app); color: var(--text-primary); overflow: hidden; }
        .app { display: flex; height: 100vh; }
        .sidebar { width: 320px; min-width: 320px; background: var(--bg-sidebar); border-right: 1px solid var(--border); display: flex; flex-direction: column; overflow: hidden; }
        .sidebar-header { padding: 16px 20px; border-bottom: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; gap: 12px; }
        .app-logo { display: flex; align-items: center; gap: 10px; }
        .app-logo svg { color: var(--accent); }
        .app-logo span { font-weight: 700; font-size: 17px; }
        .btn-icon { width: 34px; height: 34px; border-radius: 50%; border: none; background: transparent; color: var(--text-secondary); cursor: pointer; display: flex; align-items: center; justify-content: center; transition: background .15s, color .15s; }
        .btn-icon:hover { background: var(--hover); color: var(--text-primary); }
        .my-profile { padding: 12px 20px; border-bottom: 1px solid var(--border); display: flex; align-items: center; gap: 12px; }
        .avatar { position: relative; flex-shrink: 0; }
        .avatar-circle { width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 15px; background: linear-gradient(135deg, #2ea043, #1a7431); color: white; text-transform: uppercase; user-select: none; }
        .avatar-circle.sm { width: 36px; height: 36px; font-size: 13px; }
        .avatar-circle.group-av { background: linear-gradient(135deg, #388bfd, #1158c7); }
        .status-dot { position: absolute; bottom: 1px; right: 1px; width: 10px; height: 10px; border-radius: 50%; border: 2px solid var(--bg-sidebar); }
        .status-dot.online { background: var(--online); }
        .status-dot.offline { background: var(--offline); }
        .profile-info { flex: 1; min-width: 0; }
        .profile-name { font-size: 14px; font-weight: 600; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .profile-status { font-size: 11px; color: var(--online); }
        .btn-logout { font-size: 12px; padding: 5px 12px; border-radius: 6px; border: 1px solid var(--border); background: transparent; color: var(--text-secondary); cursor: pointer; transition: .15s; white-space: nowrap; }
        .btn-logout:hover { background: var(--danger); border-color: var(--danger); color: white; }
        .search-wrap { padding: 12px 16px; border-bottom: 1px solid var(--border); position: relative; }
        .search-input { width: 100%; background: var(--bg-chat); border: 1px solid var(--border); border-radius: 8px; padding: 7px 12px 7px 34px; color: var(--text-primary); font-size: 13px; font-family: inherit; outline: none; transition: border-color .15s; }
        .search-input:focus { border-color: var(--accent); }
        .search-wrap svg { position: absolute; left: 28px; top: 50%; transform: translateY(-50%); color: var(--text-muted); pointer-events: none; }
        .tabs { display: flex; padding: 0 16px; border-bottom: 1px solid var(--border); gap: 4px; }
        .tab-btn { flex: 1; padding: 10px 0; background: transparent; border: none; border-bottom: 2px solid transparent; color: var(--text-secondary); font-size: 13px; font-weight: 500; cursor: pointer; transition: .15s; font-family: inherit; }
        .tab-btn.active { color: var(--accent); border-bottom-color: var(--accent); }
        .contact-list { flex: 1; overflow-y: auto; scrollbar-width: thin; scrollbar-color: var(--border) transparent; }
        .contact-item { display: flex; align-items: center; gap: 12px; padding: 10px 16px; cursor: pointer; transition: background .12s; border-bottom: 1px solid rgba(48,54,61,0.3); }
        .contact-item:hover { background: var(--hover); }
        .contact-item.active { background: var(--active); }
        .contact-info { flex: 1; min-width: 0; }
        .contact-name { font-size: 14px; font-weight: 600; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .contact-preview { font-size: 12px; color: var(--text-secondary); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; margin-top: 2px; }
        .contact-meta { display: flex; flex-direction: column; align-items: flex-end; gap: 4px; }
        .unread-badge { min-width: 18px; height: 18px; background: var(--accent); border-radius: 9px; font-size: 11px; font-weight: 700; color: white; display: flex; align-items: center; justify-content: center; padding: 0 5px; }
        .chat-area { flex: 1; display: flex; flex-direction: column; background: var(--bg-chat); overflow: hidden; }
        .chat-empty { flex: 1; display: flex; flex-direction: column; align-items: center; justify-content: center; color: var(--text-muted); gap: 12px; }
        .chat-empty svg { opacity: .3; }
        .chat-empty h3 { font-size: 18px; color: var(--text-secondary); font-weight: 600; }
        .chat-empty p { font-size: 13px; }
        .chat-header { padding: 14px 20px; border-bottom: 1px solid var(--border); display: flex; align-items: center; gap: 12px; background: var(--bg-sidebar); }
        .chat-header-info { flex: 1; }
        .chat-header-name { font-size: 15px; font-weight: 700; }
        .chat-header-status { font-size: 12px; color: var(--text-secondary); }
        .chat-header-status.is-online { color: var(--online); }
        .messages-wrap { flex: 1; overflow-y: auto; padding: 16px 20px; display: flex; flex-direction: column; gap: 2px; scrollbar-width: thin; scrollbar-color: var(--border) transparent; }
        .date-divider { text-align: center; font-size: 11px; color: var(--text-muted); margin: 10px 0; display: flex; align-items: center; gap: 8px; }
        .date-divider::before, .date-divider::after { content: ''; flex: 1; height: 1px; background: var(--border); }
        .bubble-wrap { display: flex; flex-direction: column; max-width: 68%; margin-bottom: 2px; }
        .bubble-wrap.mine { align-self: flex-end; align-items: flex-end; }
        .bubble-wrap.theirs { align-self: flex-start; align-items: flex-start; }
        .bubble { padding: 8px 12px; border-radius: 16px; font-size: 14px; line-height: 1.5; word-break: break-word; }
        .bubble-wrap.mine .bubble { background: var(--bg-bubble-me); color: #fff; border-bottom-right-radius: 4px; }
        .bubble-wrap.theirs .bubble { background: var(--bg-bubble-you); color: var(--text-primary); border-bottom-left-radius: 4px; }
        .bubble-sender { font-size: 11px; font-weight: 700; color: var(--accent-light); margin-bottom: 2px; padding: 0 4px; }
        .bubble-time { font-size: 10px; color: rgba(255,255,255,0.45); margin-top: 3px; padding: 0 4px; }
        .bubble-wrap.theirs .bubble-time { color: var(--text-muted); }
        .bubble-wrap.gap-top { margin-top: 10px; }
        .chat-input-area { padding: 12px 20px; border-top: 1px solid var(--border); display: flex; align-items: flex-end; gap: 10px; background: var(--bg-sidebar); }
        .msg-input { flex: 1; background: var(--bg-chat); border: 1px solid var(--border); border-radius: 24px; padding: 10px 18px; color: var(--text-primary); font-size: 14px; font-family: inherit; outline: none; resize: none; max-height: 120px; line-height: 1.5; transition: border-color .15s; }
        .msg-input:focus { border-color: var(--accent); }
        .msg-input::placeholder { color: var(--text-muted); }
        .btn-send { width: 42px; height: 42px; border-radius: 50%; border: none; background: var(--accent); color: white; cursor: pointer; display: flex; align-items: center; justify-content: center; flex-shrink: 0; transition: background .15s, transform .1s; }
        .btn-send:hover { background: var(--accent-light); }
        .btn-send:active { transform: scale(.93); }
        .typing-indicator { font-size: 12px; color: var(--text-secondary); padding: 4px 20px; min-height: 20px; font-style: italic; }
        .spinner { width: 24px; height: 24px; border: 3px solid var(--border); border-top-color: var(--accent); border-radius: 50%; animation: spin .7s linear infinite; margin: auto; }
        @keyframes spin { to { transform: rotate(360deg); } }
        .modal-backdrop { position: fixed; inset: 0; background: rgba(0,0,0,.6); display: flex; align-items: center; justify-content: center; z-index: 100; }
        .modal { background: var(--bg-sidebar); border: 1px solid var(--border); border-radius: 14px; padding: 24px; width: 400px; max-width: 90vw; }
        .modal h3 { font-size: 16px; font-weight: 700; margin-bottom: 16px; }
        .modal input { width: 100%; padding: 8px 12px; background: var(--bg-chat); border: 1px solid var(--border); border-radius: 8px; color: var(--text-primary); font-size: 13px; font-family: inherit; outline: none; margin-bottom: 10px; }
        .modal input:focus { border-color: var(--accent); }
        .modal label { font-size: 12px; color: var(--text-secondary); margin-bottom: 4px; display: block; }
        .modal-actions { display: flex; gap: 8px; margin-top: 16px; justify-content: flex-end; }
        .btn-primary { padding: 8px 18px; border-radius: 8px; border: none; background: var(--accent); color: white; font-size: 13px; font-weight: 600; font-family: inherit; cursor: pointer; }
        .btn-primary:hover { background: var(--accent-light); }
        .btn-secondary { padding: 8px 18px; border-radius: 8px; border: 1px solid var(--border); background: transparent; color: var(--text-secondary); font-size: 13px; font-family: inherit; cursor: pointer; }
        .btn-secondary:hover { background: var(--hover); color: var(--text-primary); }
        .member-list { max-height: 180px; overflow-y: auto; border: 1px solid var(--border); border-radius: 8px; padding: 6px; margin-bottom: 10px; }
        .member-item { display: flex; align-items: center; gap: 8px; padding: 6px 8px; border-radius: 6px; cursor: pointer; }
        .member-item:hover { background: var(--hover); }
        .member-item input[type=checkbox] { accent-color: var(--accent); width: 15px; height: 15px; cursor: pointer; }
        .member-item label { font-size: 13px; cursor: pointer; }
        .toast { position: fixed; bottom: 24px; right: 24px; background: var(--bg-sidebar); border: 1px solid var(--border); border-radius: 10px; padding: 10px 16px; font-size: 13px; color: var(--text-primary); z-index: 200; animation: slideUp .25s ease; }
        @keyframes slideUp { from { opacity:0; transform:translateY(10px); } to { opacity:1; transform:translateY(0); } }
        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: var(--border); border-radius: 4px; }
    </style>
</head>
<body>
<div class="app">
    <aside class="sidebar">
        <div class="sidebar-header">
            <div class="app-logo">
                <svg width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                </svg>
                <span>ChatApp</span>
            </div>
            <button class="btn-icon" title="Buat Grup" onclick="openCreateGroup()">
                <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
            </button>
        </div>
        <div class="my-profile">
            <div class="avatar">
                <div class="avatar-circle">{{ substr(auth()->user()->name, 0, 2) }}</div>
                <span class="status-dot online"></span>
            </div>
            <div class="profile-info">
                <div class="profile-name">{{ auth()->user()->name }}</div>
                <div class="profile-status">● Online</div>
            </div>
            <form method="POST" action="{{ route('logout') }}" style="margin:0">
                @csrf
                <button class="btn-logout" type="submit">Keluar</button>
            </form>
        </div>
        <div class="search-wrap">
            <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <circle cx="11" cy="11" r="8"/><path stroke-linecap="round" d="M21 21l-4.35-4.35"/>
            </svg>
            <input class="search-input" type="text" placeholder="Cari percakapan..." id="searchInput" oninput="filterContacts()">
        </div>
        <div class="tabs">
            <button class="tab-btn active" onclick="switchTab('users', this)">Pengguna</button>
            <button class="tab-btn" onclick="switchTab('groups', this)">Grup</button>
        </div>
        <div class="contact-list" id="contactList">
            <div style="padding:20px;text-align:center;"><div class="spinner"></div></div>
        </div>
    </aside>

    <main class="chat-area" id="chatArea">
        <div class="chat-empty" id="chatEmpty">
            <svg width="64" height="64" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
            </svg>
            <h3>Pilih percakapan</h3>
            <p>Klik nama seseorang untuk mulai chat</p>
        </div>
        <div id="chatMain" style="display:none;flex:1;flex-direction:column;overflow:hidden;">
            <div class="chat-header" id="chatHeader"></div>
            <div class="messages-wrap" id="messagesWrap"></div>
            <div class="typing-indicator" id="typingIndicator"></div>
            <div class="chat-input-area">
                <textarea class="msg-input" id="msgInput" placeholder="Ketik pesan..." rows="1"
                    onkeydown="handleKey(event)" oninput="autoResize(this)"></textarea>
                <button class="btn-send" onclick="sendMessage()">
                    <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                    </svg>
                </button>
            </div>
        </div>
    </main>
</div>

<!-- Modal Buat Grup -->
<div class="modal-backdrop" id="modalGroup" style="display:none">
    <div class="modal">
        <h3>Buat Grup Baru</h3>
        <label>Nama Grup</label>
        <input type="text" id="groupName" placeholder="Masukkan nama grup...">
        <label>Pilih Anggota</label>
        <div class="member-list" id="memberList"></div>
        <div class="modal-actions">
            <button class="btn-secondary" onclick="closeModal('modalGroup')">Batal</button>
            <button class="btn-primary" onclick="submitCreateGroup()">Buat Grup</button>
        </div>
    </div>
</div>

<!-- Modal Tambah Anggota -->
<div class="modal-backdrop" id="modalAddMember" style="display:none">
    <div class="modal">
        <h3>Tambah Anggota Grup</h3>
        <label>Pilih Pengguna</label>
        <div class="member-list" id="addMemberList"></div>
        <div class="modal-actions">
            <button class="btn-secondary" onclick="closeModal('modalAddMember')">Batal</button>
            <button class="btn-primary" onclick="submitAddMember()">Tambah</button>
        </div>
    </div>
</div>

<script>
const ME   = {{ auth()->id() }};
const CSRF = document.querySelector('meta[name=csrf-token]').content;

let currentType    = null;
let currentId      = null;
let currentTab     = 'users';
let allUsers       = [];
let allGroups      = {!! json_encode(auth()->user()->groups()->with('members')->get()->map(fn($g) => [
    'id'      => $g->id,
    'name'    => $g->name,
    'members' => $g->members->map(fn($m) => ['id' => $m->id, 'name' => $m->name])->values(),
])->values()) !!};
let currentChannel = null;

window.addEventListener('DOMContentLoaded', async () => {
    await loadUsers();
    updateMyStatus(true);
    window.addEventListener('beforeunload', () => updateMyStatus(false));
    setInterval(loadUsers, 30000);
});

async function updateMyStatus(online) {
    try {
        await fetch('/status', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
            body: JSON.stringify({ is_online: online })
        });
    } catch(e) {}
}

async function loadUsers() {
    try {
        const res = await fetch('/users');
        allUsers  = await res.json();
        renderContactList();
    } catch(e) {
        document.getElementById('contactList').innerHTML =
            '<p style="padding:20px;text-align:center;color:var(--text-muted);font-size:13px;">Gagal memuat pengguna</p>';
    }
}

function renderContactList() {
    const list = document.getElementById('contactList');
    const q    = document.getElementById('searchInput').value.toLowerCase();

    if (currentTab === 'users') {
        const filtered = allUsers.filter(u => u.name.toLowerCase().includes(q));
        if (!filtered.length) {
            list.innerHTML = '<p style="padding:20px;text-align:center;color:var(--text-muted);font-size:13px;">Tidak ada pengguna</p>';
            return;
        }
        list.innerHTML = filtered.map(u => `
            <div class="contact-item ${currentType==='user'&&currentId===u.id?'active':''}"
                 onclick="openChat('user',${u.id},'${escHtml(u.name)}',${u.is_online},'${escHtml(u.last_seen)}')">
                <div class="avatar">
                    <div class="avatar-circle sm">${u.name.substring(0,2).toUpperCase()}</div>
                    <span class="status-dot ${u.is_online?'online':'offline'}"></span>
                </div>
                <div class="contact-info">
                    <div class="contact-name">${escHtml(u.name)}</div>
                    <div class="contact-preview">${u.is_online?'<span style="color:var(--online)">Online</span>':escHtml(u.last_seen)}</div>
                </div>
                ${u.unread?`<div class="contact-meta"><span class="unread-badge">${u.unread}</span></div>`:''}
            </div>`).join('');
    } else {
        if (!allGroups.length) {
            list.innerHTML = '<p style="padding:20px;text-align:center;color:var(--text-muted);font-size:13px;">Belum ada grup</p>';
            return;
        }
        const filtered = allGroups.filter(g => g.name.toLowerCase().includes(q));
        list.innerHTML = filtered.map(g => `
            <div class="contact-item ${currentType==='group'&&currentId===g.id?'active':''}"
                 onclick="openChat('group',${g.id},'${escHtml(g.name)}',false,'${g.members.length} anggota')">
                <div class="avatar">
                    <div class="avatar-circle sm group-av">${g.name.substring(0,2).toUpperCase()}</div>
                </div>
                <div class="contact-info">
                    <div class="contact-name">${escHtml(g.name)}</div>
                    <div class="contact-preview">${g.members.length} anggota</div>
                </div>
            </div>`).join('');
    }
}

function switchTab(tab, btn) {
    currentTab = tab;
    document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    renderContactList();
}

function filterContacts() { renderContactList(); }

async function openChat(type, id, name, isOnline, statusText) {
    currentType = type;
    currentId   = id;

    document.getElementById('chatEmpty').style.display = 'none';
    document.getElementById('chatMain').style.display = 'flex';

    const avatarClass = type==='group' ? 'avatar-circle group-av' : 'avatar-circle';
    document.getElementById('chatHeader').innerHTML = `
        <div class="avatar">
            <div class="${avatarClass}">${name.substring(0,2).toUpperCase()}</div>
            ${type==='user'?`<span class="status-dot ${isOnline?'online':'offline'}"></span>`:''}
        </div>
        <div class="chat-header-info">
            <div class="chat-header-name">${escHtml(name)}</div>
            <div class="chat-header-status ${isOnline&&type==='user'?'is-online':''}">${isOnline&&type==='user'?'Online':escHtml(statusText)}</div>
        </div>
        ${type==='group'?`
        <button class="btn-icon" onclick="openAddMember()" title="Tambah Anggota">
            <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
            </svg>
        </button>`:''}`;

    const wrap = document.getElementById('messagesWrap');
    wrap.innerHTML = '<div class="spinner" style="margin-top:40px;"></div>';

    const url = type==='user' ? `/messages/${id}` : `/groups/${id}/messages`;
    const res = await fetch(url);
    const msgs = await res.json();

    renderMessages(msgs);
    renderContactList();
}

function renderMessages(msgs) {
    const wrap = document.getElementById('messagesWrap');
    if (!msgs.length) {
        wrap.innerHTML = '<p style="text-align:center;color:var(--text-muted);font-size:13px;margin-top:40px;">Belum ada pesan. Mulai percakapan!</p>';
        return;
    }
    let html = '', lastDate = '', lastSender = null;
    msgs.forEach(msg => {
        const isGap = lastSender !== null && lastSender !== msg.sender_id;
        if (msg.date !== lastDate) {
            lastDate = msg.date;
            html += `<div class="date-divider">${msg.date}</div>`;
        }
        const mine = msg.is_mine;
        const showSender = !mine && currentType==='group' && (lastSender !== msg.sender_id);
        html += `<div class="bubble-wrap ${mine?'mine':'theirs'}${isGap?' gap-top':''}">
            ${showSender?`<div class="bubble-sender">${escHtml(msg.sender_name)}</div>`:''}
            <div class="bubble">${escHtml(msg.message)}</div>
            <div class="bubble-time">${msg.created_at}</div>
        </div>`;
        lastSender = msg.sender_id;
    });
    wrap.innerHTML = html;
    scrollToBottom();
}

function appendMessage(msg) {
    const wrap = document.getElementById('messagesWrap');
    const empty = wrap.querySelector('p');
    if (empty) empty.remove();
    const div = document.createElement('div');
    div.className = `bubble-wrap ${msg.is_mine?'mine':'theirs'} gap-top`;
    const showSender = !msg.is_mine && currentType==='group';
    div.innerHTML = `
        ${showSender?`<div class="bubble-sender">${escHtml(msg.sender_name)}</div>`:''}
        <div class="bubble">${escHtml(msg.message)}</div>
        <div class="bubble-time">${msg.created_at}</div>`;
    wrap.appendChild(div);
    scrollToBottom();
}

async function sendMessage() {
    const input = document.getElementById('msgInput');
    const text  = input.value.trim();
    if (!text || !currentId) return;
    input.value = '';
    autoResize(input);

    const url  = currentType==='user' ? '/messages' : `/groups/${currentId}/messages`;
    const body = currentType==='user'
        ? { message: text, receiver_id: currentId }
        : { message: text };

    const res = await fetch(url, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
        body: JSON.stringify(body)
    });
    if (res.ok) {
        const msg = await res.json();
        appendMessage(msg);
    } else {
        showToast('Gagal mengirim pesan');
    }
}

function handleKey(e) {
    if (e.key==='Enter' && !e.shiftKey) { e.preventDefault(); sendMessage(); }
}

function openCreateGroup() {
    document.getElementById('groupName').value = '';
    document.getElementById('memberList').innerHTML = allUsers.map(u => `
        <div class="member-item">
            <input type="checkbox" id="m${u.id}" value="${u.id}">
            <label for="m${u.id}">${escHtml(u.name)}</label>
        </div>`).join('');
    document.getElementById('modalGroup').style.display = 'flex';
}

async function submitCreateGroup() {
    const name    = document.getElementById('groupName').value.trim();
    const checked = [...document.querySelectorAll('#memberList input:checked')].map(c => parseInt(c.value));
    if (!name) return showToast('Nama grup tidak boleh kosong');
    if (!checked.length) return showToast('Pilih minimal 1 anggota');
    const res = await fetch('/groups', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
        body: JSON.stringify({ name, members: checked })
    });
    if (res.ok) {
        const group = await res.json();
        allGroups.push(group);
        closeModal('modalGroup');
        switchTab('groups', document.querySelectorAll('.tab-btn')[1]);
        showToast(`Grup "${name}" berhasil dibuat!`);
    } else {
        showToast('Gagal membuat grup');
    }
}

function openAddMember() {
    const group = allGroups.find(g => g.id === currentId);
    const existingIds = group ? group.members.map(m => m.id) : [];
    const available = allUsers.filter(u => !existingIds.includes(u.id));

    if (!available.length) {
        showToast('Semua pengguna sudah menjadi anggota');
        return;
    }

    document.getElementById('addMemberList').innerHTML = available.map(u => `
        <div class="member-item">
            <input type="checkbox" id="am${u.id}" value="${u.id}">
            <label for="am${u.id}">${escHtml(u.name)}</label>
        </div>`).join('');
    document.getElementById('modalAddMember').style.display = 'flex';
}

async function submitAddMember() {
    const checked = [...document.querySelectorAll('#addMemberList input:checked')].map(c => parseInt(c.value));
    if (!checked.length) return showToast('Pilih minimal 1 anggota');

    const res = await fetch(`/groups/${currentId}/members`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
        body: JSON.stringify({ members: checked })
    });

    if (res.ok) {
        const group = await res.json();
        const idx = allGroups.findIndex(g => g.id === currentId);
        if (idx !== -1) allGroups[idx] = group;
        closeModal('modalAddMember');
        renderContactList();
        showToast('Anggota berhasil ditambahkan!');
    } else {
        showToast('Gagal menambahkan anggota');
    }
}

function closeModal(id) { document.getElementById(id).style.display = 'none'; }

function scrollToBottom() {
    const wrap = document.getElementById('messagesWrap');
    wrap.scrollTop = wrap.scrollHeight;
}

function autoResize(el) {
    el.style.height = 'auto';
    el.style.height = Math.min(el.scrollHeight, 120) + 'px';
}

function escHtml(str) {
    return String(str).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}

function showToast(msg) {
    const t = document.createElement('div');
    t.className = 'toast';
    t.textContent = msg;
    document.body.appendChild(t);
    setTimeout(() => t.remove(), 3000);
}
</script>
</body>
</html>