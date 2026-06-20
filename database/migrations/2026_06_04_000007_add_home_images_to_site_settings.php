<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('site_settings')) {
            return;
        }

        Schema::table('site_settings', function (Blueprint $table) {
            if (! Schema::hasColumn('site_settings', 'hero_image_path')) {
                $table->string('hero_image_path')->nullable()->after('placeholder_product_path');
            }
            if (! Schema::hasColumn('site_settings', 'category_image_1_path')) {
                $table->string('category_image_1_path')->nullable()->after('hero_image_path');
            }
            if (! Schema::hasColumn('site_settings', 'category_image_2_path')) {
                $table->string('category_image_2_path')->nullable()->after('category_image_1_path');
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('site_settings')) {
            return;
        }

        Schema::table('site_settings', function (Blueprint $table) {
            foreach (['hero_image_path', 'category_image_1_path', 'category_image_2_path'] as $column) {
                if (Schema::hasColumn('site_settings', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
