<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        foreach (array_keys(config('branding.social_platforms', [])) as $platform) {
            $column = "{$platform}_hidden";
            if (! Schema::hasColumn('site_settings', $column)) {
                Schema::table('site_settings', function (Blueprint $table) use ($column) {
                    $table->boolean($column)->default(false);
                });
            }
        }
    }

    public function down(): void
    {
        $columns = [];
        foreach (array_keys(config('branding.social_platforms', [])) as $platform) {
            $column = "{$platform}_hidden";
            if (Schema::hasColumn('site_settings', $column)) {
                $columns[] = $column;
            }
        }
        if ($columns !== []) {
            Schema::table('site_settings', function (Blueprint $table) use ($columns) {
                $table->dropColumn($columns);
            });
        }
    }
};
