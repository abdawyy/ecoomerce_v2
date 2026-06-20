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
            if (! Schema::hasColumn('site_settings', 'invoice_notes_en')) {
                $table->text('invoice_notes_en')->nullable()->after('pdf_thank_you_ar');
            }
            if (! Schema::hasColumn('site_settings', 'invoice_notes_ar')) {
                $table->text('invoice_notes_ar')->nullable()->after('invoice_notes_en');
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('site_settings')) {
            return;
        }

        Schema::table('site_settings', function (Blueprint $table) {
            foreach (['invoice_notes_en', 'invoice_notes_ar'] as $column) {
                if (Schema::hasColumn('site_settings', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
