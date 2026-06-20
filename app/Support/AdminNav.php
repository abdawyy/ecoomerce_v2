<?php

namespace App\Support;

use Illuminate\Support\Facades\Route;

class AdminNav
{
    public static function sections(array $notify = []): array
    {
        $notify = $notify ?: [
            'pending_orders' => 0,
            'unread_messages' => 0,
            'new_users' => 0,
            'new_guests' => 0,
        ];

        $allowAdminRegister = Route::has('admin.register');

        return [
            [
                'items' => [
                    self::link('admin.dashboard', 'admin_sidebar.dashboard', 'bi-grid', Route::is('admin.dashboard')),
                    self::link('admin.analytics', 'analytics.title', 'bi-graph-up', Route::is('admin.analytics*')),
                ],
            ],
            [
                'heading' => 'admin_sidebar.section_operations',
                'items' => [
                    self::link('order.list', 'admin_sidebar.order_list', 'bi-cart3', Route::is('order.*'), 'pending_orders', $notify, route('order.list').'?status=Pending'),
                    self::link('admin.contact.list', 'admin_sidebar.messages_list', 'bi-envelope', Route::is('admin.contact.*'), 'unread_messages', $notify, null, 'danger'),
                    self::collapse('customers-nav', 'admin_sidebar.customers', 'bi-people', Route::is('users.*') || Route::is('admin.guest.*'), [
                        self::child('users.list', 'admin_sidebar.user_list', Route::is('users.*'), 'new_users', $notify, 'info'),
                        self::child('admin.guest.list', 'admin_sidebar.guest_list', Route::is('admin.guest.*'), 'new_guests', $notify, 'info'),
                    ]),
                ],
            ],
            [
                'heading' => 'admin_sidebar.section_catalog',
                'items' => [
                    self::collapse('products-nav', 'admin_sidebar.products', 'bi-box-seam', Route::is('products.*'), [
                        self::child('products.list', 'admin_sidebar.view_products', Route::is('products.list')),
                        self::child('products.edit', 'admin_sidebar.add_product', Route::is('products.edit')),
                    ]),
                    self::collapse('categories-nav', 'admin_sidebar.categories', 'bi-tags', Route::is('categories.*'), [
                        self::child('categories.list', 'admin_sidebar.view_categories', Route::is('categories.list')),
                        self::child('categories.edit', 'admin_sidebar.add_category', Route::is('categories.edit')),
                    ]),
                    self::collapse('discount-nav', 'admin_sidebar.discount_codes', 'bi-percent', Route::is('discountCodes.*'), [
                        self::child('discountCodes.list', 'admin_sidebar.view_discount_codes', Route::is('discountCodes.list')),
                        self::child('discountCodes.edit', 'admin_sidebar.add_discount_code', Route::is('discountCodes.edit')),
                    ]),
                ],
            ],
            [
                'heading' => 'admin_sidebar.section_store',
                'items' => [
                    self::link('admin.settings.branding', 'admin_sidebar.branding', 'bi-palette', Route::is('admin.settings.branding')),
                    self::link('admin.settings.seo', 'seo.title', 'bi-search', Route::is('admin.settings.seo')),
                    self::collapse('cities-nav', 'admin_sidebar.cities', 'bi-geo-alt', Route::is('cities.*'), [
                        self::child('cities.list', 'admin_sidebar.view_cities', Route::is('cities.list')),
                        self::child('cities.edit', 'admin_sidebar.add_city', Route::is('cities.edit')),
                    ]),
                    self::collapse('types-nav', 'admin_sidebar.types', 'bi-layers', Route::is('type.*'), [
                        self::child('type.list', 'admin_sidebar.view_types', Route::is('type.list')),
                        self::child('type.edit', 'admin_sidebar.add_type', Route::is('type.edit')),
                    ]),
                ],
            ],
            [
                'heading' => 'admin_sidebar.section_system',
                'items' => [
                    self::collapse('admins-nav', 'admin_sidebar.admins', 'bi-shield-lock', Route::is('admin.list') || ($allowAdminRegister && Route::is('admin.register')), array_filter([
                        self::child('admin.list', 'admin_sidebar.view_admins', Route::is('admin.list')),
                        $allowAdminRegister ? self::child('admin.register', 'admin_sidebar.add_admin', Route::is('admin.register')) : null,
                    ])),
                ],
            ],
        ];
    }

    protected static function link(
        string $route,
        string $labelKey,
        string $icon,
        bool $active,
        ?string $badgeKey = null,
        array $notify = [],
        ?string $url = null,
        string $badgeVariant = 'warning'
    ): array {
        return [
            'type' => 'link',
            'route' => $route,
            'label' => $labelKey,
            'icon' => $icon,
            'active' => $active,
            'badge' => $badgeKey ? ($notify[$badgeKey] ?? 0) : 0,
            'badge_variant' => $badgeVariant,
            'url' => $url ?? route($route),
        ];
    }

    protected static function child(
        string $route,
        string $labelKey,
        bool $active,
        ?string $badgeKey = null,
        array $notify = [],
        string $badgeVariant = 'warning'
    ): array {
        return [
            'route' => $route,
            'label' => $labelKey,
            'active' => $active,
            'badge' => $badgeKey ? ($notify[$badgeKey] ?? 0) : 0,
            'badge_variant' => $badgeVariant,
        ];
    }

    protected static function collapse(
        string $id,
        string $labelKey,
        string $icon,
        bool $open,
        array $children
    ): array {
        return [
            'type' => 'collapse',
            'id' => $id,
            'label' => $labelKey,
            'icon' => $icon,
            'open' => $open,
            'children' => array_values(array_filter($children)),
        ];
    }

    public static function notificationTotal(array $notify): int
    {
        return (int) ($notify['pending_orders'] ?? 0)
            + (int) ($notify['unread_messages'] ?? 0)
            + (int) ($notify['new_users'] ?? 0)
            + (int) ($notify['new_guests'] ?? 0);
    }
}
