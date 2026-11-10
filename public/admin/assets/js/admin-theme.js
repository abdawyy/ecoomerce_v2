(function () {
    const STORAGE_KEY = 'hayah-admin-theme';

    function getPreferredTheme() {
        try {
            const stored = localStorage.getItem(STORAGE_KEY);
            if (stored === 'light' || stored === 'dark') {
                return stored;
            }
        } catch (e) {}

        return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
    }

    function applyTheme(theme) {
        const resolved = theme === 'dark' ? 'dark' : 'light';
        document.documentElement.setAttribute('data-bs-theme', resolved);

        try {
            localStorage.setItem(STORAGE_KEY, resolved);
        } catch (e) {}

        updateToggleUi(resolved);
        window.dispatchEvent(new CustomEvent('admin-theme-changed', { detail: { theme: resolved } }));
    }

    function updateToggleUi(theme) {
        const btn = document.getElementById('admin-theme-toggle');
        const icon = document.getElementById('admin-theme-icon');
        if (!btn || !icon) {
            return;
        }

        const isDark = theme === 'dark';
        icon.className = isDark ? 'bi bi-sun-fill' : 'bi bi-moon-stars-fill';
        btn.setAttribute(
            'aria-label',
            isDark
                ? (btn.dataset.labelLight || 'Switch to light mode')
                : (btn.dataset.labelDark || 'Switch to dark mode')
        );
        btn.setAttribute('title', btn.getAttribute('aria-label'));
    }

    function toggleTheme() {
        const current = document.documentElement.getAttribute('data-bs-theme') || 'light';
        applyTheme(current === 'dark' ? 'light' : 'dark');
    }

    window.AdminTheme = {
        get: () => document.documentElement.getAttribute('data-bs-theme') || 'light',
        apply: applyTheme,
        toggle: toggleTheme,
        chartColors: function () {
            const dark = this.get() === 'dark';
            return {
                grid: dark ? 'rgba(255,255,255,0.08)' : 'rgba(0,0,0,0.08)',
                text: dark ? '#8b949e' : '#6b7280',
                revenueLine: '#22c55e',
                revenueFill: dark ? 'rgba(34,197,94,0.15)' : 'rgba(25,135,84,0.1)',
                barPrimary: dark ? '#3db8f0' : '#0d6efd',
                barSecondary: dark ? '#6b7280' : '#6c757d',
            };
        },
    };

    function setupMobileFilters() {
        const mobile = window.matchMedia('(max-width: 768px)');

        function filtersActive() {
            const params = new URLSearchParams(window.location.search);
            for (const [key, value] of params.entries()) {
                if (key === 'page') {
                    continue;
                }
                if (value !== '') {
                    return true;
                }
            }
            return false;
        }

        document.querySelectorAll('.filter-card').forEach(function (card) {
            const header = card.querySelector('.filter-header');
            const body = card.querySelector('.filter-body');
            if (!header || !body || header.dataset.filterToggle === '1') {
                return;
            }
            header.dataset.filterToggle = '1';
            header.setAttribute('role', 'button');
            header.setAttribute('tabindex', '0');

            function sync() {
                if (!mobile.matches) {
                    card.classList.remove('is-collapsed');
                    header.setAttribute('aria-expanded', 'true');
                    return;
                }
                const open = filtersActive();
                card.classList.toggle('is-collapsed', !open);
                header.setAttribute('aria-expanded', open ? 'true' : 'false');
            }

            header.addEventListener('click', function () {
                if (!mobile.matches) {
                    return;
                }
                const collapsed = card.classList.toggle('is-collapsed');
                header.setAttribute('aria-expanded', collapsed ? 'false' : 'true');
            });
            header.addEventListener('keydown', function (event) {
                if (event.key !== 'Enter' && event.key !== ' ') {
                    return;
                }
                event.preventDefault();
                header.click();
            });

            sync();
            mobile.addEventListener('change', sync);
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        const btn = document.getElementById('admin-theme-toggle');
        if (btn) {
            btn.addEventListener('click', toggleTheme);
        }
        updateToggleUi(document.documentElement.getAttribute('data-bs-theme') || getPreferredTheme());
        setupMobileFilters();
    });
})();
