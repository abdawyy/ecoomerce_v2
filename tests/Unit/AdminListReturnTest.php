<?php

namespace Tests\Unit;

use App\Support\AdminListReturn;
use Tests\TestCase;

class AdminListReturnTest extends TestCase
{
    public function test_it_rejects_urls_outside_admin(): void
    {
        $this->assertFalse(AdminListReturn::isSafe('https://evil.test/admin/products'));
        $this->assertFalse(AdminListReturn::isSafe('/products'));
        $this->assertFalse(AdminListReturn::isSafe(''));
        $this->assertTrue(AdminListReturn::isSafe(url('/admin/products/list?search=shirt')));
    }

    public function test_it_builds_add_url_with_remembered_list(): void
    {
        $list = url('/admin/products/list?search=shirt&category_id=2&page=3');
        session(['admin.list_url.products' => $list]);

        $add = AdminListReturn::addUrl('products.edit', 'products');

        $this->assertStringContainsString('/admin/products/edit', $add);
        $this->assertStringContainsString('return=', $add);
        $this->assertStringContainsString(rawurlencode($list), $add);
        $this->assertSame($list, AdminListReturn::fieldValue('products'));
    }

    public function test_it_strips_page_and_keeps_filters(): void
    {
        $url = url('/admin/products/list?search=shirt&page=4&category_id=2#old');

        $this->assertSame(
            url('/admin/products/list?search=shirt&category_id=2').'#old',
            AdminListReturn::withoutPage($url)
        );
    }
}
