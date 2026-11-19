<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            if (! Schema::hasColumn('site_settings', 'legal_terms_en')) {
                $table->text('legal_terms_en')->nullable();
            }
            if (! Schema::hasColumn('site_settings', 'legal_terms_ar')) {
                $table->text('legal_terms_ar')->nullable();
            }
            if (! Schema::hasColumn('site_settings', 'legal_privacy_en')) {
                $table->text('legal_privacy_en')->nullable();
            }
            if (! Schema::hasColumn('site_settings', 'legal_privacy_ar')) {
                $table->text('legal_privacy_ar')->nullable();
            }
            if (! Schema::hasColumn('site_settings', 'legal_cookies_en')) {
                $table->text('legal_cookies_en')->nullable();
            }
            if (! Schema::hasColumn('site_settings', 'legal_cookies_ar')) {
                $table->text('legal_cookies_ar')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->dropColumn([
                'legal_terms_en',
                'legal_terms_ar',
                'legal_privacy_en',
                'legal_privacy_ar',
                'legal_cookies_en',
                'legal_cookies_ar',
            ]);
        });
    }
};
