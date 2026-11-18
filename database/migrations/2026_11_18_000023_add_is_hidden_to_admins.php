<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('admins') || Schema::hasColumn('admins', 'is_hidden')) {
            return;
        }

        Schema::table('admins', function (Blueprint $table) {
            $table->boolean('is_hidden')->default(false)->after('is_active');
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('admins') || ! Schema::hasColumn('admins', 'is_hidden')) {
            return;
        }

        Schema::table('admins', function (Blueprint $table) {
            $table->dropColumn('is_hidden');
        });
    }
};
