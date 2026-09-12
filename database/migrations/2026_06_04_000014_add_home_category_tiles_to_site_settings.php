<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->unsignedBigInteger('home_category_1_id')->nullable()->after('category_image_2_path');
            $table->unsignedBigInteger('home_category_2_id')->nullable()->after('home_category_1_id');
            $table->string('home_tile_1_title_en')->nullable()->after('home_category_2_id');
            $table->string('home_tile_1_title_ar')->nullable()->after('home_tile_1_title_en');
            $table->string('home_tile_2_title_en')->nullable()->after('home_tile_1_title_ar');
            $table->string('home_tile_2_title_ar')->nullable()->after('home_tile_2_title_en');
        });
    }

    public function down(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->dropColumn([
                'home_category_1_id',
                'home_category_2_id',
                'home_tile_1_title_en',
                'home_tile_1_title_ar',
                'home_tile_2_title_en',
                'home_tile_2_title_ar',
            ]);
        });
    }
};
