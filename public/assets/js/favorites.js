(function () {
    var labels = window.StorefrontFavorites || {};

    function productIdOf(button) {
        return button ? button.getAttribute('data-product-id') : '';
    }

    function heartsFor(productId) {
        return document.querySelectorAll('.product-fav-form [data-product-id="' + CSS.escape(productId) + '"]');
    }

    function setHeart(button, favorited) {
        button.classList.toggle('is-active', favorited);
        button.setAttribute('aria-label', favorited ? (labels.removeLabel || '') : (labels.addLabel || ''));
        var icon = button.querySelector('i');
        if (!icon) {
            return;
        }
        icon.classList.toggle('bi-heart-fill', favorited);
        icon.classList.toggle('bi-heart', !favorited);
    }

    function setPending(productId, pending) {
        heartsFor(productId).forEach(function (button) {
            var form = button.closest('form');
            if (form) {
                form.dataset.pending = pending ? '1' : '0';
            }
            button.disabled = pending;
        });
    }

    function removeFavoriteCard(productId) {
        var root = document.getElementById('account-favorites');
        if (!root) {
            return;
        }

        root.querySelectorAll('[data-favorite-item][data-product-id="' + CSS.escape(productId) + '"]').forEach(function (item) {
            item.remove();
        });

        var grid = root.querySelector('[data-favorites-grid]');
        if (!grid || grid.querySelector('[data-favorite-item]')) {
            return;
        }

        grid.classList.add('d-none');
        var empty = root.querySelector('[data-favorites-empty]');
        if (empty) {
            empty.classList.remove('d-none');
        }
    }

    document.addEventListener('submit', function (event) {
        var form = event.target;
        if (!(form instanceof HTMLFormElement) || !form.classList.contains('product-fav-form')) {
            return;
        }

        event.preventDefault();

        var button = form.querySelector('[data-product-id]');
        var productId = productIdOf(button);
        if (!productId || form.dataset.pending === '1') {
            return;
        }

        var token = form.querySelector('input[name="_token"]');
        setPending(productId, true);

        fetch(form.action, {
            method: 'POST',
            headers: {
                Accept: 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': token ? token.value : '',
            },
            body: new FormData(form),
            credentials: 'same-origin',
        })
            .then(function (response) {
                return response.json().then(function (data) {
                    if (!response.ok) {
                        throw new Error((data && data.message) || '');
                    }
                    return data;
                });
            })
            .then(function (data) {
                var favorited = !!data.favorited;
                heartsFor(productId).forEach(function (heart) {
                    setHeart(heart, favorited);
                });
                if (!favorited) {
                    removeFavoriteCard(productId);
                }
            })
            .catch(function () {})
            .finally(function () {
                setPending(productId, false);
            });
    });
})();
