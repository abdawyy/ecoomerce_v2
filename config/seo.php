<?php

return [
    'default_robots' => 'index, follow',
    'admin_robots' => 'noindex, nofollow',
    'sitemap_cache_minutes' => 60,
    'static_pages' => [
        'home' => ['route' => 'home', 'path' => '/'],
        'contact' => ['route' => 'contact.show', 'path' => '/contact'],
        'legal' => ['route' => 'legal', 'path' => '/legal'],
        'cart' => ['route' => 'cart.index', 'path' => '/cart'],
        'checkout' => ['route' => 'checkout', 'path' => '/checkout'],
        'guides' => ['route' => 'guides.index', 'path' => '/guides'],
    ],
];
