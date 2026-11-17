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
            foreach ([
                'hero_title_en', 'hero_title_ar', 'hero_lead_en', 'hero_lead_ar',
                'collection_title_en', 'collection_title_ar', 'collection_lead_en', 'collection_lead_ar',
            ] as $column) {
                if (! Schema::hasColumn('site_settings', $column)) {
                    $table->text($column)->nullable();
                }
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('site_settings')) {
            return;
        }

        Schema::table('site_settings', function (Blueprint $table) {
            foreach ([
                'hero_title_en', 'hero_title_ar', 'hero_lead_en', 'hero_lead_ar',
                'collection_title_en', 'collection_title_ar', 'collection_lead_en', 'collection_lead_ar',
            ] as $column) {
                if (Schema::hasColumn('site_settings', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
