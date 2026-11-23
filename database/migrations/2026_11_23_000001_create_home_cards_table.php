<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('home_cards')) {
            Schema::create('home_cards', function (Blueprint $table) {
                $table->id();
                $table->unsignedSmallInteger('sort_order')->default(0);
                $table->string('title_en')->nullable();
                $table->string('title_ar')->nullable();
                $table->string('image_path')->nullable();
                $table->string('filter_type', 32)->default('all');
                $table->string('filter_value')->nullable();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('site_settings') || DB::table('home_cards')->exists()) {
            return;
        }

        $settings = DB::table('site_settings')->first();
        if (! $settings) {
            return;
        }

        $now = now();
        $rows = [];
        foreach ([1, 2] as $slot) {
            $image = Schema::hasColumn('site_settings', "category_image_{$slot}_path")
                ? ($settings->{"category_image_{$slot}_path"} ?? null)
                : null;
            $titleEn = Schema::hasColumn('site_settings', "home_tile_{$slot}_title_en")
                ? ($settings->{"home_tile_{$slot}_title_en"} ?? null)
                : null;
            $titleAr = Schema::hasColumn('site_settings', "home_tile_{$slot}_title_ar")
                ? ($settings->{"home_tile_{$slot}_title_ar"} ?? null)
                : null;
            $categoryId = Schema::hasColumn('site_settings', "home_category_{$slot}_id")
                ? ($settings->{"home_category_{$slot}_id"} ?? null)
                : null;

            if (! $image && ! $titleEn && ! $titleAr && ! $categoryId) {
                continue;
            }

            $rows[] = [
                'sort_order' => $slot,
                'title_en' => $titleEn,
                'title_ar' => $titleAr,
                'image_path' => $image,
                'filter_type' => $categoryId ? 'category' : 'all',
                'filter_value' => $categoryId ? (string) $categoryId : null,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        if ($rows !== []) {
            DB::table('home_cards')->insert($rows);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('home_cards');
    }
};
