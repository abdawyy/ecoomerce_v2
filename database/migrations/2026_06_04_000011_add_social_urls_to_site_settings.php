<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            foreach (['instagram', 'facebook', 'tiktok', 'youtube', 'whatsapp', 'twitter'] as $platform) {
                $column = "{$platform}_url";
                if (! Schema::hasColumn('site_settings', $column)) {
                    $table->string($column, 500)->nullable();
                }
            }
        });
    }

    public function down(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->dropColumn([
                'instagram_url',
                'facebook_url',
                'tiktok_url',
                'youtube_url',
                'whatsapp_url',
                'twitter_url',
            ]);
        });
    }
};
