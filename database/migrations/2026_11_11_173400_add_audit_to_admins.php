<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('admins')) {
            return;
        }

        Schema::table('admins', function (Blueprint $table) {
            if (! Schema::hasColumn('admins', 'created_by')) {
                $table->integer('created_by')->nullable();
            }
            if (! Schema::hasColumn('admins', 'updated_by')) {
                $table->integer('updated_by')->nullable();
            }
        });

        Schema::table('admins', function (Blueprint $table) {
            try {
                $table->foreign('created_by')->references('id')->on('admins')->nullOnDelete();
            } catch (\Throwable $e) {
            }
            try {
                $table->foreign('updated_by')->references('id')->on('admins')->nullOnDelete();
            } catch (\Throwable $e) {
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('admins')) {
            return;
        }

        Schema::table('admins', function (Blueprint $table) {
            try {
                $table->dropForeign(['created_by']);
            } catch (\Throwable $e) {
            }
            try {
                $table->dropForeign(['updated_by']);
            } catch (\Throwable $e) {
            }
            if (Schema::hasColumn('admins', 'created_by')) {
                $table->dropColumn('created_by');
            }
            if (Schema::hasColumn('admins', 'updated_by')) {
                $table->dropColumn('updated_by');
            }
        });
    }
};
