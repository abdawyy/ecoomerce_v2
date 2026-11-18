<x-account.layout :title="__('account.favorites')">
    <div id="account-favorites">
        <div class="account-card account-empty {{ $favorites->isNotEmpty() ? 'd-none' : '' }}" data-favorites-empty>
            <i class="fa-solid fa-heart d-block"></i>
            <p class="mb-3">{{ __('account.no_favorites') }}</p>
            <a href="{{ route('product.List') }}" class="btn btn-dark">{{ __('account.shop_now') }}</a>
        </div>

        <div class="row g-3 {{ $favorites->isEmpty() ? 'd-none' : '' }}" data-favorites-grid>
            @foreach ($favorites as $product)
                <div class="col-6 col-md-4" data-favorite-item data-product-id="{{ $product->id }}">
                    <x-web.product-card :product="$product" />
                </div>
            @endforeach
        </div>
    </div>
</x-account.layout>
