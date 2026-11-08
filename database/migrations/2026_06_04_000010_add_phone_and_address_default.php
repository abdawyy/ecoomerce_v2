<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'phone')) {
                $table->string('phone', 20)->nullable()->after('email');
            }
        });

        if (Schema::hasTable('addresses') && ! Schema::hasColumn('addresses', 'is_default')) {
            Schema::table('addresses', function (Blueprint $table) {
                $table->boolean('is_default')->default(false)->after('phone_number');
            });
        }
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'phone')) {
                $table->dropColumn('phone');
            }
        });

        if (Schema::hasTable('addresses') && Schema::hasColumn('addresses', 'is_default')) {
            Schema::table('addresses', function (Blueprint $table) {
                $table->dropColumn('is_default');
            });
        }
    }
};
