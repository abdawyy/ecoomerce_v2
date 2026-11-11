(function () {
    const cfg = window.StorefrontPresence || {};
    const pingUrl = cfg.pingUrl;
    if (!pingUrl) {
        return;
    }

    const intervalMs = Math.max(10, Number(cfg.intervalSeconds) || 15) * 1000;
    let timer = null;

    function csrfToken() {
        const meta = document.querySelector('meta[name="csrf-token"]');
        return meta ? meta.getAttribute('content') : '';
    }

    function ping() {
        if (document.visibilityState !== 'visible') {
            return;
        }
        const data = new FormData();
        data.append('_token', csrfToken());
        fetch(pingUrl, {
            method: 'POST',
            body: data,
            credentials: 'same-origin',
            headers: {
                Accept: 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            },
        }).catch(function () {});
    }

    function stop() {
        if (timer) {
            clearInterval(timer);
            timer = null;
        }
    }

    function start() {
        if (document.visibilityState !== 'visible') {
            stop();
            return;
        }
        stop();
        ping();
        timer = setInterval(ping, intervalMs);
    }

    document.addEventListener('visibilitychange', start);
    start();
})();
