(function () {
    const keys = {
        profile: 'novaskol_connected_profile',
        bootstrap: 'novaskol_connected_bootstrap',
        queue: 'novaskol_connected_queue',
        syncLog: 'novaskol_connected_sync_log',
        lastSync: 'novaskol_connected_last_sync'
    };

    function getJson(key, fallback) {
        try {
            return JSON.parse(localStorage.getItem(key) || '') || fallback;
        } catch (_) {
            return fallback;
        }
    }

    function setJson(key, value) {
        localStorage.setItem(key, JSON.stringify(value));
    }

    function profile() {
        return getJson(keys.profile, null);
    }

    function bootstrap() {
        return getJson(keys.bootstrap, null);
    }

    function queue() {
        return getJson(keys.queue, []);
    }

    function saveQueue(items) {
        setJson(keys.queue, items);
        renderStatus();
    }

    function log(entry) {
        const items = [{ at: new Date().toISOString(), ...entry }, ...getJson(keys.syncLog, [])].slice(0, 12);
        setJson(keys.syncLog, items);
        localStorage.setItem(keys.lastSync, new Date().toISOString());
        renderStatus();
    }

    function csrfHeaders() {
        const token = document.querySelector('meta[name="csrf-token"]')?.content;
        return token ? { 'X-CSRF-TOKEN': token } : {};
    }

    async function fetchJson(url, options = {}) {
        const response = await fetch(url, {
            credentials: 'same-origin',
            headers: { 'Accept': 'application/json', ...(options.headers || {}) },
            ...options
        });
        const data = await response.json().catch(() => ({ success: false, message: 'Reponse illisible.' }));
        if (!response.ok || data.success === false) {
            throw new Error(data.message || 'Operation impossible.');
        }
        return data;
    }

    function ensureStatusNode() {
        if (!profile()) return null;
        let node = document.getElementById('novaskolConnectedStatus');
        if (node) return node;

        const style = document.createElement('style');
        style.textContent = `
            .novaskol-connected-status{position:fixed;left:16px;bottom:16px;z-index:12000;display:flex;align-items:center;gap:8px;border:1px solid rgba(0,200,83,.28);background:rgba(6,17,29,.92);color:#dfffe9;border-radius:999px;padding:9px 12px;font:800 12px/1.2 Inter,system-ui,sans-serif;box-shadow:0 12px 34px rgba(0,0,0,.28);backdrop-filter:blur(10px)}
            .novaskol-connected-status span{display:inline-flex;align-items:center;gap:6px}
            .novaskol-connected-status b{display:grid;place-items:center;min-width:19px;height:19px;padding:0 6px;border-radius:999px;background:#00c853;color:#052e16}
            .novaskol-connected-status.warn{border-color:rgba(245,158,11,.36);color:#fde68a}
            .novaskol-connected-status.warn b{background:#f59e0b;color:#111827}
            @media(max-width:700px){.novaskol-connected-status{left:10px;right:10px;bottom:10px;justify-content:center;border-radius:12px}}
            @media print{.novaskol-connected-status{display:none!important}}
        `;
        document.head.appendChild(style);

        node = document.createElement('button');
        node.type = 'button';
        node.id = 'novaskolConnectedStatus';
        node.className = 'novaskol-connected-status';
        node.addEventListener('click', () => syncNow());
        document.body.appendChild(node);
        return node;
    }

    function renderStatus() {
        const p = profile();
        if (!p) return;
        const node = ensureStatusNode();
        if (!node) return;
        const waiting = queue().length;
        const online = navigator.onLine;
        node.classList.toggle('warn', waiting > 0 || !online);
        node.innerHTML = `<span>${online ? 'Novaskol connecte' : 'Hors connexion'}</span><b>${waiting}</b>`;
        node.title = waiting > 0 ? `${waiting} action(s) en attente de synchronisation` : 'Donnees locales a jour. Cliquer pour verifier.';
    }

    async function refreshBootstrap() {
        const p = profile();
        const uuid = p?.device?.uuid;
        if (!uuid || !navigator.onLine) return null;

        const data = await fetchJson('/reseau-local/bootstrap-appareil', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', ...csrfHeaders() },
            body: JSON.stringify({ device_uuid: uuid })
        });
        if (data.bootstrap) setJson(keys.bootstrap, data.bootstrap);
        if (data.sync) {
            const updated = { ...p, sync: data.sync, permissions: data.permissions || p.permissions, connected_at: p.connected_at || new Date().toISOString() };
            setJson(keys.profile, updated);
        }
        log({ type: 'pull', success: true, message: 'Donnees autorisees mises a jour.' });
        return data;
    }

    function queueChange(change) {
        const p = profile();
        if (!p?.device?.uuid) {
            throw new Error('Appareil connecte non initialise.');
        }
        const item = {
            uuid: change.uuid || (crypto.randomUUID ? crypto.randomUUID() : 'change-' + Date.now() + '-' + Math.random().toString(16).slice(2)),
            module: change.module || 'connecte',
            table_name: change.table_name,
            record_uuid: change.record_uuid || (crypto.randomUUID ? crypto.randomUUID() : 'record-' + Date.now() + '-' + Math.random().toString(16).slice(2)),
            operation: change.operation || 'create',
            payload: change.payload || {},
            checksum: change.checksum || null,
            action_at: change.action_at || new Date().toISOString()
        };
        saveQueue([...queue(), item]);
        return item;
    }

    async function pushQueue() {
        const p = profile();
        const uuid = p?.device?.uuid;
        const items = queue();
        if (!uuid || !items.length || !navigator.onLine) return { pushed: 0 };

        const data = await fetchJson('/reseau-local/recevoir-lot', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', ...csrfHeaders() },
            body: JSON.stringify({
                device_uuid: uuid,
                changes: items
            })
        });
        if (data.next_bootstrap) setJson(keys.bootstrap, data.next_bootstrap);
        saveQueue([]);
        log({ type: 'push', success: true, message: data.message || 'Actions synchronisees.', accepted: data.accepted, refused: data.refused });
        return data;
    }

    async function syncNow() {
        try {
            await pushQueue();
            await refreshBootstrap();
            renderStatus();
        } catch (error) {
            log({ type: 'error', success: false, message: error.message });
            renderStatus();
        }
    }

    window.NovaskolConnected = {
        profile,
        bootstrap,
        queue,
        queueChange,
        pushQueue,
        refreshBootstrap,
        syncNow,
        status: () => ({
            connected: !!profile(),
            online: navigator.onLine,
            queued: queue().length,
            lastSync: localStorage.getItem(keys.lastSync)
        })
    };

    document.addEventListener('DOMContentLoaded', () => {
        if (!profile()) return;
        renderStatus();
        refreshBootstrap().catch(() => renderStatus());
        setInterval(() => syncNow(), 180000);
    });
    window.addEventListener('online', syncNow);
    window.addEventListener('offline', renderStatus);
    window.addEventListener('storage', event => {
        if ([keys.profile, keys.queue, keys.bootstrap].includes(event.key)) renderStatus();
    });
})();
