<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('site_settings') || Schema::hasColumn('site_settings', 'hero_image_mobile_path')) {
            return;
        }

        Schema::table('site_settings', function (Blueprint $table) {
            $table->string('hero_image_mobile_path')->nullable()->after('hero_image_path');
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('site_settings') || ! Schema::hasColumn('site_settings', 'hero_image_mobile_path')) {
            return;
        }

        Schema::table('site_settings', function (Blueprint $table) {
            $table->dropColumn('hero_image_mobile_path');
        });
    }
};
