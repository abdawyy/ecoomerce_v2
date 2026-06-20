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
            if (! Schema::hasColumn('site_settings', 'meta_title_en')) {
                $table->string('meta_title_en')->nullable()->after('support_phone');
            }
            if (! Schema::hasColumn('site_settings', 'meta_title_ar')) {
                $table->string('meta_title_ar')->nullable();
            }
            if (! Schema::hasColumn('site_settings', 'meta_description_en')) {
                $table->text('meta_description_en')->nullable();
            }
            if (! Schema::hasColumn('site_settings', 'meta_description_ar')) {
                $table->text('meta_description_ar')->nullable();
            }
            if (! Schema::hasColumn('site_settings', 'meta_keywords_en')) {
                $table->string('meta_keywords_en')->nullable();
            }
            if (! Schema::hasColumn('site_settings', 'meta_keywords_ar')) {
                $table->string('meta_keywords_ar')->nullable();
            }
            if (! Schema::hasColumn('site_settings', 'og_title_en')) {
                $table->string('og_title_en')->nullable();
            }
            if (! Schema::hasColumn('site_settings', 'og_title_ar')) {
                $table->string('og_title_ar')->nullable();
            }
            if (! Schema::hasColumn('site_settings', 'og_description_en')) {
                $table->text('og_description_en')->nullable();
            }
            if (! Schema::hasColumn('site_settings', 'og_description_ar')) {
                $table->text('og_description_ar')->nullable();
            }
            if (! Schema::hasColumn('site_settings', 'twitter_card')) {
                $table->string('twitter_card', 32)->nullable()->default('summary_large_image');
            }
            if (! Schema::hasColumn('site_settings', 'robots')) {
                $table->string('robots', 64)->nullable()->default('index, follow');
            }
            if (! Schema::hasColumn('site_settings', 'canonical_url')) {
                $table->string('canonical_url')->nullable();
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('site_settings')) {
            return;
        }

        Schema::table('site_settings', function (Blueprint $table) {
            $columns = [
                'meta_title_en', 'meta_title_ar', 'meta_description_en', 'meta_description_ar',
                'meta_keywords_en', 'meta_keywords_ar', 'og_title_en', 'og_title_ar',
                'og_description_en', 'og_description_ar', 'twitter_card', 'robots', 'canonical_url',
            ];
            foreach ($columns as $column) {
                if (Schema::hasColumn('site_settings', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
