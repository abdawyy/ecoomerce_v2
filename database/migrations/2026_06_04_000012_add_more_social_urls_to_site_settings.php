<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            foreach (['linkedin', 'telegram', 'pinterest', 'snapchat'] as $platform) {
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
                'linkedin_url',
                'telegram_url',
                'pinterest_url',
                'snapchat_url',
            ]);
        });
    }
};
