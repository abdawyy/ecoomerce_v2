(function () {
    const STORAGE_KEY = 'hayah-storefront-theme';

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
    }

    function updateToggleUi(theme) {
        document.querySelectorAll('[data-storefront-theme-toggle]').forEach(function (btn) {
            const icon = btn.querySelector('i');
            const isDark = theme === 'dark';

            if (icon) {
                icon.className = isDark ? 'bi bi-sun-fill' : 'bi bi-moon-stars-fill';
            }

            btn.setAttribute(
                'aria-label',
                isDark
                    ? (btn.dataset.labelLight || 'Switch to light mode')
                    : (btn.dataset.labelDark || 'Switch to dark mode')
            );
        });
    }

    function toggleTheme() {
        const current = document.documentElement.getAttribute('data-bs-theme') || 'light';
        applyTheme(current === 'dark' ? 'light' : 'dark');
    }

    window.StorefrontTheme = {
        get: () => document.documentElement.getAttribute('data-bs-theme') || 'light',
        apply: applyTheme,
        toggle: toggleTheme,
    };

    applyTheme(getPreferredTheme());

    document.addEventListener('click', function (event) {
        const btn = event.target.closest('[data-storefront-theme-toggle]');
        if (btn) {
            event.preventDefault();
            toggleTheme();
        }
    });
})();
