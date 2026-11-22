<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('categories') || ! Schema::hasTable('products')) {
            return;
        }

        $categories = DB::table('categories')->where('is_active', 1)->orderBy('id')->get();
        $groups = [];
        foreach ($categories as $category) {
            $key = mb_strtolower(trim((string) $category->name));
            if ($key === '') {
                continue;
            }
            $groups[$key][] = $category;
        }

        foreach ($groups as $rows) {
            if (count($rows) < 2) {
                continue;
            }

            $counts = [];
            foreach ($rows as $row) {
                $counts[$row->id] = DB::table('products')->where('category_id', $row->id)->count();
            }

            usort($rows, function ($a, $b) use ($counts) {
                $cmp = ($counts[$b->id] ?? 0) <=> ($counts[$a->id] ?? 0);

                return $cmp !== 0 ? $cmp : ((int) $a->id <=> (int) $b->id);
            });

            $keeperId = (int) $rows[0]->id;
            $dupeIds = array_map(fn ($row) => (int) $row->id, array_slice($rows, 1));

            DB::table('products')->whereIn('category_id', $dupeIds)->update([
                'category_id' => $keeperId,
            ]);

            if (Schema::hasTable('catalog_filter_options')) {
                foreach ($dupeIds as $dupeId) {
                    $keeperSaved = DB::table('catalog_filter_options')
                        ->where('group_key', 'category')
                        ->where('value', (string) $keeperId)
                        ->exists();

                    if ($keeperSaved) {
                        DB::table('catalog_filter_options')
                            ->where('group_key', 'category')
                            ->where('value', (string) $dupeId)
                            ->delete();
                    } else {
                        DB::table('catalog_filter_options')
                            ->where('group_key', 'category')
                            ->where('value', (string) $dupeId)
                            ->update(['value' => (string) $keeperId]);
                    }
                }
            }

            DB::table('categories')->whereIn('id', $dupeIds)->update([
                'is_active' => 0,
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        // Duplicate category rows stay inactive; products stay on the kept category.
    }
};
