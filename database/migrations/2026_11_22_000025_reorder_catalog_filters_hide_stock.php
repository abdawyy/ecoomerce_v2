<?php

use App\Services\CatalogFilterService;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('catalog_filter_groups')) {
            return;
        }

        foreach (CatalogFilterService::KEYS as $position => $key) {
            DB::table('catalog_filter_groups')->where('key', $key)->update([
                'sort_order' => $position,
                'is_visible' => 1,
                'updated_at' => now(),
            ]);
        }

        DB::table('catalog_filter_groups')->where('key', 'stock')->update([
            'is_visible' => 0,
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        if (! Schema::hasTable('catalog_filter_groups')) {
            return;
        }

        $previous = ['category', 'price', 'type', 'season', 'color', 'size', 'stock', 'on_sale'];
        foreach ($previous as $position => $key) {
            DB::table('catalog_filter_groups')->where('key', $key)->update([
                'sort_order' => $position,
                'is_visible' => 1,
                'updated_at' => now(),
            ]);
        }
    }
};
