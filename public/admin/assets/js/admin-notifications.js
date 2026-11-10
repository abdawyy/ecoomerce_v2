(function () {
    const cfg = window.AdminNotifyConfig || {};
    const emptyText = cfg.emptyText || 'No activity';
    let echo = null;
    let channel = null;
    let feedLoaded = false;
    let feedLoading = false;

    function csrfToken() {
        const meta = document.querySelector('meta[name="csrf-token"]');
        return meta ? meta.getAttribute('content') : '';
    }

    function formatBadge(n) {
        n = Number(n) || 0;
        return n > 99 ? '99+' : String(n);
    }

    function setBadgeEl(el, value) {
        if (!el) return;
        const n = Number(value) || 0;
        el.textContent = el.tagName === 'H3' ? String(n) : formatBadge(n);
        if (el.hasAttribute('data-admin-notify-bell') || el.id === 'nav-notify-total' || (el.classList.contains('badge') && el.hasAttribute('data-admin-notify') && el.closest('.sidebar-nav'))) {
            el.classList.toggle('d-none', n <= 0);
        }
    }

    function applyCounts(counts) {
        if (!counts) return;
        const pending = Number(counts.pending_orders) || 0;
        const unread = Number(counts.unread_messages) || 0;
        const users = Number(counts.new_users) || 0;
        const guests = Number(counts.new_guests) || 0;
        const bell = pending + unread;

        document.querySelectorAll('[data-admin-notify]').forEach(function (el) {
            const key = el.getAttribute('data-admin-notify');
            if (key && Object.prototype.hasOwnProperty.call(counts, key)) {
                setBadgeEl(el, counts[key]);
            }
        });

        ['pending-count', 'pending-count-card', 'nav-pending-orders'].forEach(function (id) {
            document.querySelectorAll('#' + id).forEach(function (el) { el.textContent = pending; });
        });
        ['unread-messages-count', 'nav-unread-messages'].forEach(function (id) {
            document.querySelectorAll('#' + id).forEach(function (el) { el.textContent = unread; });
        });
        document.querySelectorAll('#new-users-count').forEach(function (el) { el.textContent = users; });
        document.querySelectorAll('#new-guests-count').forEach(function (el) { el.textContent = guests; });

        setBadgeEl(document.getElementById('nav-notify-total'), bell);
    }

    function showToast(toast) {
        if (!toast || !toast.message || typeof toastr === 'undefined') return;
        const type = toast.type || 'info';
        if (typeof toastr[type] === 'function') {
            toastr[type](toast.message);
        } else {
            toastr.info(toast.message);
        }
    }

    async function fetchCounts() {
        if (!cfg.countsUrl) return;
        try {
            const res = await fetch(cfg.countsUrl, { headers: { Accept: 'application/json' }, credentials: 'same-origin' });
            if (!res.ok) return;
            const data = await res.json();
            applyCounts(data.counts || data);
        } catch (e) {}
    }

    function renderFeed(items) {
        const list = document.getElementById('admin-notify-feed');
        if (!list) return;
        list.replaceChildren();
        if (!items || !items.length) {
            const empty = document.createElement('li');
            empty.className = 'dropdown-item-text text-muted small px-3 py-3';
            empty.textContent = emptyText;
            list.appendChild(empty);
            return;
        }
        items.forEach(function (item) {
            const variant = String(item.variant || 'secondary').replace(/[^a-z0-9-]/gi, '');
            const icon = String(item.icon || 'bi-bell').replace(/[^a-z0-9-]/gi, '');
            const li = document.createElement('li');
            const a = document.createElement('a');
            a.className = 'dropdown-item admin-notify-item py-2 px-3';
            a.href = item.url || '#';
            a.innerHTML = '<div class="d-flex gap-2 align-items-start">' +
                '<span class="admin-notify-icon bg-' + variant + ' bg-opacity-10 text-' + variant + '"><i class="bi ' + icon + '"></i></span>' +
                '<div class="flex-grow-1 min-w-0">' +
                '<div class="fw-semibold small text-truncate"></div>' +
                '<div class="text-muted small text-truncate"></div>' +
                '<div class="text-muted" style="font-size: 0.7rem;"></div>' +
                '</div></div>';
            const texts = a.querySelectorAll('.flex-grow-1 > div');
            texts[0].textContent = item.title || '';
            texts[1].textContent = item.subtitle || '';
            texts[2].textContent = item.ago || '';
            li.appendChild(a);
            list.appendChild(li);
        });
    }

    async function loadFeed(force) {
        if (!cfg.feedUrl || feedLoading) return;
        if (feedLoaded && !force) return;
        feedLoading = true;
        try {
            const res = await fetch(cfg.feedUrl, { headers: { Accept: 'application/json' }, credentials: 'same-origin' });
            if (!res.ok) return;
            const data = await res.json();
            renderFeed(data.items || []);
            feedLoaded = true;
        } catch (e) {
        } finally {
            feedLoading = false;
        }
    }

    function subscribe() {
        if (!echo) return;
        channel = echo.private('admin.notifications');
        channel.listen('.counts.updated', function (payload) {
            applyCounts(payload.counts || payload);
            showToast(payload.toast);
            feedLoaded = false;
        });
    }

    function connectEcho() {
        if (!cfg.enabled || typeof Echo === 'undefined' || typeof Pusher === 'undefined') {
            return;
        }
        if (echo) {
            try { echo.connect(); } catch (e) {}
            subscribe();
            return;
        }
        window.Pusher = Pusher;
        echo = new Echo({
            broadcaster: 'pusher',
            key: cfg.key,
            cluster: cfg.cluster || 'mt1',
            wsHost: cfg.host,
            wsPort: cfg.port,
            wssPort: cfg.port,
            forceTLS: !!cfg.forceTLS,
            encrypted: !!cfg.forceTLS,
            disableStats: true,
            enabledTransports: ['ws', 'wss'],
            authEndpoint: cfg.authEndpoint || '/broadcasting/auth',
            auth: {
                headers: {
                    'X-CSRF-TOKEN': csrfToken(),
                    'X-Requested-With': 'XMLHttpRequest',
                },
            },
        });
        window.Echo = echo;
        subscribe();
    }

    function disconnectEcho() {
        if (!echo) return;
        try {
            echo.disconnect();
        } catch (e) {}
        channel = null;
    }

    document.addEventListener('visibilitychange', function () {
        if (document.hidden) {
            disconnectEcho();
        } else {
            connectEcho();
            fetchCounts();
        }
    });

    document.addEventListener('shown.bs.dropdown', function (event) {
        if (event.target && event.target.closest && event.target.closest('.admin-notify-dropdown')) {
            loadFeed(true);
        }
    });

    connectEcho();
    fetchCounts();
})();
