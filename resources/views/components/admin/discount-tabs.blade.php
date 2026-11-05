@php
    $isRtl = app()->getLocale() === 'ar';
    $tabs = [
        [
            'route' => 'discountCodes.list',
            'label' => __('discount_codes.tab_codes'),
            'active' => request()->routeIs('discountCodes.list'),
        ],
        [
            'route' => 'discountCodes.edit',
            'label' => __('discount_codes.tab_add'),
            'active' => request()->routeIs('discountCodes.edit'),
        ],
        [
            'route' => 'discountCodes.freeShipping',
            'label' => __('discount_codes.tab_free_shipping'),
            'active' => request()->routeIs('discountCodes.freeShipping'),
        ],
    ];
@endphp

<ul class="nav nav-tabs mb-4" dir="{{ $isRtl ? 'rtl' : 'ltr' }}">
    @foreach ($tabs as $tab)
        <li class="nav-item">
            <a class="nav-link {{ $tab['active'] ? 'active' : '' }}"
               href="{{ route($tab['route']) }}">{{ $tab['label'] }}</a>
        </li>
    @endforeach
</ul>
