(function () {
    const root = document.getElementById('storefront-confirm');
    if (!root) {
        window.StorefrontConfirm = {
            ask: function () { return Promise.resolve(true); },
        };
        return;
    }

    const titleEl = document.getElementById('storefront-confirm-title');
    const messageEl = document.getElementById('storefront-confirm-message');
    const okBtn = document.getElementById('storefront-confirm-ok');
    const cancelBtn = document.getElementById('storefront-confirm-cancel');

    let resolver = null;
    let lastFocus = null;

    function ds(key, fallback) {
        return root.getAttribute('data-' + key) || fallback || '';
    }

    function setOpen(open) {
        if (open) {
            root.hidden = false;
            root.classList.add('is-open');
            document.body.classList.add('storefront-confirm-open');
            window.setTimeout(function () {
                (cancelBtn || okBtn).focus();
            }, 10);
        } else {
            root.hidden = true;
            root.classList.remove('is-open');
            document.body.classList.remove('storefront-confirm-open');
            if (lastFocus && typeof lastFocus.focus === 'function') {
                lastFocus.focus();
            }
        }
    }

    function finish(value) {
        if (!resolver) {
            return;
        }
        const resolve = resolver;
        resolver = null;
        setOpen(false);
        resolve(value);
    }

    function ask(options) {
        const opts = options || {};
        lastFocus = document.activeElement;
        titleEl.textContent = opts.title || ds('title', 'Confirm');
        messageEl.textContent = opts.message || '';
        okBtn.textContent = opts.confirm || ds('confirm', 'OK');
        cancelBtn.textContent = opts.cancel || ds('cancel', 'Cancel');
        okBtn.classList.toggle('is-danger', !!opts.danger);
        setOpen(true);

        return new Promise(function (resolve) {
            resolver = resolve;
        });
    }

    window.StorefrontConfirm = { ask: ask };

    okBtn.addEventListener('click', function () {
        finish(true);
    });

    root.querySelectorAll('[data-storefront-confirm-dismiss]').forEach(function (el) {
        el.addEventListener('click', function () {
            finish(false);
        });
    });

    document.addEventListener('keydown', function (event) {
        if (!root.classList.contains('is-open')) {
            return;
        }
        if (event.key === 'Escape') {
            event.preventDefault();
            finish(false);
        }
    });

    document.addEventListener('submit', function (event) {
        const form = event.target;
        if (!(form instanceof HTMLFormElement)) {
            return;
        }
        const message = form.getAttribute('data-storefront-confirm');
        if (!message) {
            return;
        }
        if (form.dataset.storefrontConfirmOk === '1') {
            delete form.dataset.storefrontConfirmOk;
            return;
        }
        event.preventDefault();
        event.stopPropagation();
        ask({
            message: message,
            danger: form.hasAttribute('data-storefront-confirm-danger'),
        }).then(function (ok) {
            if (!ok) {
                return;
            }
            form.dataset.storefrontConfirmOk = '1';
            if (typeof form.requestSubmit === 'function') {
                form.requestSubmit();
            } else {
                form.submit();
            }
        });
    });
})();
