<?php

namespace Database\Seeders;

use App\Models\SeoPage;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class SeoPagesSeeder extends Seeder
{
    public function run(): void
    {
        if (! Schema::hasTable('seo_pages')) {
            return;
        }

        $pages = ['home', 'contact', 'legal', 'cart', 'checkout', 'guides'];

        foreach ($pages as $key) {
            SeoPage::firstOrCreate(['page_key' => $key], ['is_indexable' => $key !== 'cart' && $key !== 'checkout']);
        }
    }
}
