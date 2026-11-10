(function () {
    const STORAGE_KEY = 'hayah-storefront-theme';

    function applyLightTheme() {
        document.documentElement.setAttribute('data-bs-theme', 'light');

        try {
            localStorage.setItem(STORAGE_KEY, 'light');
        } catch (e) {}
    }

    window.StorefrontTheme = {
        get: () => 'light',
        apply: applyLightTheme,
        toggle: applyLightTheme,
    };

    applyLightTheme();
})();
