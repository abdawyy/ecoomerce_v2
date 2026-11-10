(function () {
    var SEARCH_DELAY = 400;
    var LOCK_MS = 8000;
    var searchTimer = null;

    function formMethod(form) {
        var methodField = form.querySelector('input[name="_method"]');
        var raw = (methodField && methodField.value) || form.getAttribute('method') || 'GET';
        return String(raw).toUpperCase();
    }

    function submitButtons(form) {
        return form.querySelectorAll('button[type="submit"], button:not([type]), input[type="submit"]');
    }

    function setSubmitting(form, locked) {
        form.dataset.storefrontSubmitting = locked ? '1' : '0';
        submitButtons(form).forEach(function (btn) {
            btn.disabled = !!locked;
        });
    }

    function lockForm(form) {
        if (form._storefrontUnlock) {
            clearTimeout(form._storefrontUnlock);
        }
        setSubmitting(form, true);
        form._storefrontUnlock = setTimeout(function () {
            setSubmitting(form, false);
            form._storefrontUnlock = null;
        }, LOCK_MS);
    }

    document.addEventListener('submit', function (event) {
        var form = event.target;
        if (!(form instanceof HTMLFormElement)) {
            return;
        }
        if (form.dataset.noDebounce === '1' || form.hasAttribute('data-no-debounce')) {
            return;
        }
        var method = formMethod(form);
        if (method === 'GET' || method === '') {
            return;
        }
        if (form.dataset.storefrontSubmitting === '1') {
            event.preventDefault();
            return;
        }
        if (searchTimer) {
            clearTimeout(searchTimer);
            searchTimer = null;
        }
        lockForm(form);
    }, true);

    var searchInput = document.querySelector('#searchModal .search-input-full');
    if (!searchInput) {
        return;
    }

    var searchForm = searchInput.closest('form');
    if (!searchForm) {
        return;
    }

    searchInput.addEventListener('input', function () {
        var query = String(searchInput.value || '').trim();
        if (searchTimer) {
            clearTimeout(searchTimer);
            searchTimer = null;
        }
        if (query.length < 2) {
            return;
        }
        searchTimer = setTimeout(function () {
            searchTimer = null;
            if (searchForm.dataset.storefrontSubmitting === '1') {
                return;
            }
            if (typeof searchForm.requestSubmit === 'function') {
                searchForm.requestSubmit();
            } else {
                searchForm.submit();
            }
        }, SEARCH_DELAY);
    });

    searchForm.addEventListener('submit', function () {
        if (searchTimer) {
            clearTimeout(searchTimer);
            searchTimer = null;
        }
    });
})();
