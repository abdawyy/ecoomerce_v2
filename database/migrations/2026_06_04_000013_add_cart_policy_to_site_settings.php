<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            if (! Schema::hasColumn('site_settings', 'cart_policy_title_en')) {
                $table->string('cart_policy_title_en', 255)->nullable();
            }
            if (! Schema::hasColumn('site_settings', 'cart_policy_title_ar')) {
                $table->string('cart_policy_title_ar', 255)->nullable();
            }
            if (! Schema::hasColumn('site_settings', 'cart_policy_body_en')) {
                $table->text('cart_policy_body_en')->nullable();
            }
            if (! Schema::hasColumn('site_settings', 'cart_policy_body_ar')) {
                $table->text('cart_policy_body_ar')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->dropColumn([
                'cart_policy_title_en',
                'cart_policy_title_ar',
                'cart_policy_body_en',
                'cart_policy_body_ar',
            ]);
        });
    }
};
