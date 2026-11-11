(function () {
    const cfg = window.StorefrontPresence || {};
    const pingUrl = cfg.pingUrl;
    const leaveUrl = cfg.leaveUrl;
    if (!pingUrl || !leaveUrl) {
        return;
    }

    const intervalMs = Math.max(10, Number(cfg.intervalSeconds) || 15) * 1000;
    let timer = null;
    let left = false;

    function csrfToken() {
        const meta = document.querySelector('meta[name="csrf-token"]');
        return meta ? meta.getAttribute('content') : '';
    }

    function form() {
        const data = new FormData();
        data.append('_token', csrfToken());
        return data;
    }

    function ping() {
        if (document.visibilityState !== 'visible') {
            return;
        }
        left = false;
        fetch(pingUrl, {
            method: 'POST',
            body: form(),
            credentials: 'same-origin',
            headers: {
                Accept: 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            },
        }).catch(function () {});
    }

    function leave() {
        if (left) {
            return;
        }
        left = true;
        stop();
        const data = form();
        if (navigator.sendBeacon) {
            navigator.sendBeacon(leaveUrl, data);
            return;
        }
        fetch(leaveUrl, {
            method: 'POST',
            body: data,
            credentials: 'same-origin',
            keepalive: true,
        }).catch(function () {});
    }

    function stop() {
        if (timer) {
            clearInterval(timer);
            timer = null;
        }
    }

    function start() {
        stop();
        ping();
        timer = setInterval(ping, intervalMs);
    }

    document.addEventListener('visibilitychange', function () {
        if (document.visibilityState === 'hidden') {
            leave();
            return;
        }
        start();
    });
    window.addEventListener('pagehide', leave);
    window.addEventListener('freeze', leave);
    start();
})();
