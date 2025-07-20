<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Arama sonuçlarına tarih sıralaması filtresi ekler
     * Admin panelden açılıp kapatılabilir
     */
    public function up(): void
    {
        Schema::table('search_settings', function (Blueprint $table) {
            // Tarih filtresi görünürlük kontrolü
            $table->boolean('show_date_filter')->default(false)->after('show_hedef_kitle_filter');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('search_settings', function (Blueprint $table) {
            $table->dropColumn('show_date_filter');
        });
    }
};
