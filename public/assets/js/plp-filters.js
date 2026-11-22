(function () {
    var rootSelector = '#plp-ajax-root';
    var abort = null;

    function plpRoot() {
        return document.querySelector(rootSelector);
    }

    function cleanUrl(url) {
        var next = new URL(url, window.location.origin);
        ['min_price', 'max_price', 'search', 'sort', 'page', 'color', 'size', 'on_sale'].forEach(function (key) {
            var value = next.searchParams.get(key);
            if (value === '' || value === null) {
                next.searchParams.delete(key);
            }
        });
        if (next.searchParams.get('sort') === 'newest') {
            next.searchParams.delete('sort');
        }
        next.searchParams.delete('ajax');
        return next.pathname + next.search + next.hash;
    }

    function hideOpenUi() {
        if (window.bootstrap && window.bootstrap.Dropdown) {
            document.querySelectorAll('.plp-filter-bar [data-bs-toggle="dropdown"]').forEach(function (toggle) {
                var instance = window.bootstrap.Dropdown.getInstance(toggle);
                if (instance) {
                    instance.hide();
                }
            });
        }
        if (window.bootstrap && window.bootstrap.Offcanvas) {
            document.querySelectorAll('.plp-mobile-filter').forEach(function (panel) {
                var instance = window.bootstrap.Offcanvas.getInstance(panel);
                if (instance) {
                    instance.hide();
                }
            });
        }
    }

    function load(url, push) {
        var root = plpRoot();
        if (!root) {
            return;
        }
        var nextUrl = cleanUrl(url);
        if (abort) {
            abort.abort();
        }
        abort = new AbortController();
        root.classList.add('is-loading');
        fetch(nextUrl, {
            headers: {
                'X-PLP-Ajax': '1',
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'text/html',
            },
            signal: abort.signal,
        }).then(function (response) {
            if (!response.ok) {
                throw new Error('plp-ajax-failed');
            }
            return response.text();
        }).then(function (html) {
            var doc = new DOMParser().parseFromString(html, 'text/html');
            var next = doc.querySelector(rootSelector);
            if (!next) {
                window.location.href = nextUrl;
                return;
            }
            var title = next.getAttribute('data-page-title');
            hideOpenUi();
            root.replaceWith(next);
            if (title) {
                document.title = title;
            }
            if (push) {
                history.pushState({ plp: true }, '', nextUrl);
            }
        }).catch(function (error) {
            if (error && error.name === 'AbortError') {
                return;
            }
            window.location.href = nextUrl;
        });
    }

    function plpFilterOptionInput(event) {
        return event.target.closest && event.target.closest('.plp-filter-option')
            ? event.target.closest('.plp-filter-option').querySelector('.plp-filter-option-input')
            : null;
    }

    document.addEventListener('pointerdown', function (event) {
        var input = plpFilterOptionInput(event);
        if (!input || input.type !== 'radio') {
            return;
        }
        input.dataset.wasChecked = input.checked ? '1' : '0';
    });

    document.addEventListener('click', function (event) {
        var input = plpFilterOptionInput(event);
        if (input && input.type === 'radio' && input.dataset.wasChecked === '1') {
            event.preventDefault();
            input.checked = false;
            input.dispatchEvent(new Event('change', { bubbles: true }));
            return;
        }

        if (!plpRoot()) {
            return;
        }

        var chip = event.target.closest('.plp-filter-chip');
        if (chip && chip.href) {
            event.preventDefault();
            load(chip.href, true);
            return;
        }

        var clearLink = event.target.closest('.plp-filter-actions a, .filter-buttons a.btn-filter.clear, .plp-empty-state a.btn-dark');
        if (clearLink && clearLink.href && !clearLink.classList.contains('btn-outline-dark')) {
            event.preventDefault();
            load(clearLink.href, true);
            return;
        }

        var pageLink = event.target.closest('#plp-ajax-root .pagination a');
        if (pageLink && pageLink.href) {
            event.preventDefault();
            load(pageLink.href, true);
        }
    });

    document.addEventListener('change', function (event) {
        var input = event.target;
        if (!input.classList || !input.classList.contains('plp-filter-option-input')) {
            return;
        }
        var text = input.closest('.plp-filter-option') && input.closest('.plp-filter-option').querySelector('span')
            ? input.closest('.plp-filter-option').querySelector('span').textContent.trim()
            : '';
        var toggle = input.closest('.dropdown') ? input.closest('.dropdown').querySelector('[data-bs-toggle="dropdown"]') : null;
        var sectionToggle = input.closest('.filter-section') ? input.closest('.filter-section').querySelector('.filter-section-toggle') : null;
        var emptyLabel = (toggle && toggle.dataset.emptyLabel) || (sectionToggle && sectionToggle.dataset.emptyLabel) || '';
        var buttonLabel = toggle ? toggle.querySelector('.plp-filter-btn-label') : null;
        var sectionLabel = input.closest('.filter-section') ? input.closest('.filter-section').querySelector('.filter-chosen-label') : null;
        if (input.checked) {
            if (buttonLabel && text) {
                buttonLabel.textContent = text;
            }
            if (toggle) {
                toggle.classList.add('is-active');
            }
            if (sectionLabel && text) {
                sectionLabel.textContent = text;
            }
        } else {
            if (buttonLabel && emptyLabel) {
                buttonLabel.textContent = emptyLabel;
            }
            if (toggle) {
                toggle.classList.remove('is-active');
            }
            if (sectionLabel && emptyLabel) {
                sectionLabel.textContent = emptyLabel;
            }
        }
        if (toggle && window.bootstrap && window.bootstrap.Dropdown) {
            window.bootstrap.Dropdown.getOrCreateInstance(toggle).hide();
        }
        if (input.closest('form')) {
            input.closest('form').requestSubmit();
        }
    });

    document.addEventListener('submit', function (event) {
        var form = event.target;
        if (!plpRoot()) {
            return;
        }
        if (!form.classList.contains('plp-filter-bar') && !form.classList.contains('filter-form') && !form.classList.contains('plp-sort-form')) {
            return;
        }
        event.preventDefault();
        var action = form.getAttribute('action') || window.location.pathname;
        var next = new URL(action, window.location.origin);
        var params = new URLSearchParams();
        new FormData(form).forEach(function (value, key) {
            if (String(value).trim() === '') {
                return;
            }
            params.append(key, value);
        });
        next.search = params.toString();
        load(next.toString(), true);
    });

    window.addEventListener('scroll', function (event) {
        var target = event.target;
        if (target && target.closest && target.closest('.plp-filter-menu')) {
            return;
        }
        if (!window.bootstrap || !window.bootstrap.Dropdown) {
            return;
        }
        document.querySelectorAll('.plp-filter-bar [data-bs-toggle="dropdown"]').forEach(function (toggle) {
            var instance = window.bootstrap.Dropdown.getInstance(toggle);
            if (instance) {
                instance.hide();
            }
        });
    }, { passive: true });

    window.addEventListener('popstate', function () {
        if (!plpRoot()) {
            return;
        }
        load(window.location.href, false);
    });
})();
