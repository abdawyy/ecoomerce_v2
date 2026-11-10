(function () {
    const root = document.getElementById('admin-confirm');
    if (!root) {
        window.AdminConfirm = {
            ask: function () { return Promise.resolve(true); },
            alert: function () { return Promise.resolve(true); },
        };
        return;
    }

    const titleEl = document.getElementById('admin-confirm-title');
    const messageEl = document.getElementById('admin-confirm-message');
    const okBtn = document.getElementById('admin-confirm-ok');
    const cancelBtn = document.getElementById('admin-confirm-cancel');
    const kickerEl = document.getElementById('admin-confirm-kicker');

    let resolver = null;
    let lastFocus = null;
    let mode = 'confirm';
    let pendingAsk = null;
    let lastOkAt = 0;
    let confirmForm = null;

    function ds(key, fallback) {
        return root.getAttribute('data-' + key) || fallback || '';
    }

    function setOpen(open) {
        if (open) {
            root.hidden = false;
            root.classList.add('is-open');
            document.body.classList.add('admin-confirm-open');
            const focusTarget = mode === 'alert' ? okBtn : cancelBtn;
            window.setTimeout(function () {
                (focusTarget || okBtn).focus();
            }, 10);
        } else {
            root.hidden = true;
            root.classList.remove('is-open');
            document.body.classList.remove('admin-confirm-open');
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
        pendingAsk = null;
        setOpen(false);
        resolve(value);
    }

    function lockFormButtons(form) {
        if (!(form instanceof HTMLFormElement)) {
            return;
        }
        form.querySelectorAll('button[type="submit"], button:not([type]), input[type="submit"]').forEach(function (btn) {
            btn.disabled = true;
        });
    }

    function openDialog(options) {
        if (root.classList.contains('is-open') && pendingAsk) {
            return Promise.resolve(false);
        }
        const opts = options || {};
        lastFocus = document.activeElement;
        mode = opts.alert ? 'alert' : 'confirm';
        titleEl.textContent = opts.title || (mode === 'alert' ? ds('alert-title', ds('title')) : ds('title'));
        messageEl.textContent = opts.message || '';
        kickerEl.textContent = ds('site-name', '');

        cancelBtn.hidden = mode === 'alert';
        okBtn.textContent = mode === 'alert' ? ds('ok', 'OK') : ds('confirm', 'Confirm');
        okBtn.classList.toggle('is-danger', !!opts.danger && mode !== 'alert');

        setOpen(true);

        pendingAsk = new Promise(function (resolve) {
            resolver = resolve;
        });
        return pendingAsk;
    }

    function confirmOk() {
        const now = Date.now();
        if (now - lastOkAt < 300) {
            return;
        }
        lastOkAt = now;
        finish(true);
    }

    cancelBtn.addEventListener('click', function () {
        finish(false);
    });
    okBtn.addEventListener('click', confirmOk);
    root.querySelectorAll('[data-admin-confirm-dismiss]').forEach(function (el) {
        if (el === cancelBtn) {
            return;
        }
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
            finish(mode === 'alert');
            return;
        }
        if (event.key === 'Enter' && event.target && event.target.tagName !== 'TEXTAREA' && event.target.tagName !== 'BUTTON') {
            event.preventDefault();
            confirmOk();
            return;
        }
        if (event.key === 'Tab') {
            const focusable = [cancelBtn, okBtn].filter(function (btn) {
                return btn && !btn.hidden;
            });
            if (!focusable.length) {
                return;
            }
            const first = focusable[0];
            const last = focusable[focusable.length - 1];
            if (event.shiftKey && document.activeElement === first) {
                event.preventDefault();
                last.focus();
            } else if (!event.shiftKey && document.activeElement === last) {
                event.preventDefault();
                first.focus();
            }
        }
    });

    window.AdminConfirm = {
        ask: function (options) {
            return openDialog(options || {});
        },
        alert: function (options) {
            const opts = options || {};
            opts.alert = true;
            return openDialog(opts);
        },
        addSizeMessage: function (size) {
            return ds('msg-add-size', 'Add size ":size"?').replace(':size', size);
        },
    };

    function formMethod(form) {
        const methodField = form.querySelector('input[name="_method"]');
        const raw = (methodField && methodField.value) || form.getAttribute('method') || 'GET';
        return String(raw).toUpperCase();
    }

    function shouldSkip(form) {
        if (!(form instanceof HTMLFormElement)) {
            return true;
        }
        if (form.dataset.adminConfirm === 'skip') {
            return true;
        }
        if (form.classList.contains('admin-auth-form')) {
            return true;
        }
        const method = formMethod(form);
        if (method === 'GET' || method === '') {
            return true;
        }
        return false;
    }

    function promptForForm(form) {
        if (form.dataset.adminConfirmMessage) {
            return {
                message: form.dataset.adminConfirmMessage,
                title: form.dataset.adminConfirmTitle || ds('title'),
                danger: form.dataset.adminConfirmDanger === '1',
            };
        }
        const action = form.getAttribute('action') || '';
        if (form.classList.contains('admin-logout-form') || /logout/i.test(action)) {
            return { message: ds('msg-logout'), danger: true };
        }
        if (form.id.indexOf('delete-image') === 0 || form.dataset.adminConfirmDanger === '1' || form.querySelector('.btn-danger')) {
            return { message: ds('msg-delete'), danger: true };
        }
        if (form.classList.contains('table-actions-form')) {
            return { message: ds('msg-status') };
        }
        if (/changeStatus|change-status/i.test(action) || form.querySelector('select[name="status"]')) {
            return { message: ds('msg-order') };
        }
        return { message: ds('msg-save') };
    }

    function orderStatusClasses(status) {
        switch (String(status || '').toLowerCase()) {
            case 'completed': return 'bg-success text-white';
            case 'processing': return 'bg-info text-white';
            case 'cancelled':
            case 'canceled': return 'bg-danger text-white';
            default: return 'bg-warning text-dark';
        }
    }

    function paintOrderStatusSelect(select) {
        if (!select) return;
        select.className = 'status-badge order-status-select ' + orderStatusClasses(select.value);
    }

    function restoreOrderStatusSelect(form) {
        const select = form && form.querySelector('.order-status-select');
        if (!select) return;
        if (select.dataset.original) {
            select.value = select.dataset.original;
        }
        paintOrderStatusSelect(select);
    }

    document.addEventListener('change', function (event) {
        const select = event.target;
        if (!(select instanceof HTMLSelectElement) || !select.classList.contains('order-status-select')) {
            return;
        }
        paintOrderStatusSelect(select);
        if (select.value === select.dataset.original) {
            return;
        }
        if (select.form) {
            select.form.requestSubmit();
        }
    });

    document.addEventListener('submit', function (event) {
        const form = event.target;
        if (shouldSkip(form)) {
            return;
        }
        if (form.dataset.adminConfirmOk === '1') {
            delete form.dataset.adminConfirmOk;
            return;
        }
        event.preventDefault();
        event.stopPropagation();
        if (root.classList.contains('is-open')) {
            return;
        }
        confirmForm = form;
        const prompt = promptForForm(form);
        window.AdminConfirm.ask(prompt).then(function (ok) {
            if (!ok) {
                restoreOrderStatusSelect(form);
                confirmForm = null;
                return;
            }
            lockFormButtons(form);
            form.dataset.adminConfirmOk = '1';
            if (typeof form.requestSubmit === 'function') {
                form.requestSubmit();
            } else {
                HTMLFormElement.prototype.submit.call(form);
            }
        });
    }, true);
})();
