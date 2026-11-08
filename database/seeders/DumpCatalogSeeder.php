<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DumpCatalogSeeder extends Seeder
{
    public function run(): void
    {
        $tables = [
            'type',
            'categories',
            'cities',
            'products',
            'product_images',
            'product_items',
            'site_settings',
            'admins',
            'discount_codes',
        ];

        Schema::disableForeignKeyConstraints();

        foreach ($tables as $table) {
            if (! Schema::hasTable($table)) {
                continue;
            }

            $path = database_path('seeders/data/'.$table.'.json');
            if (! is_file($path)) {
                continue;
            }

            $rows = json_decode(file_get_contents($path), true);
            if (! is_array($rows) || $rows === []) {
                continue;
            }

            DB::table($table)->delete();

            foreach (array_chunk($rows, 100) as $chunk) {
                DB::table($table)->insert($chunk);
            }
        }

        Schema::enableForeignKeyConstraints();
    }
};
