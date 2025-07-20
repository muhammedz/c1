<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Arama ayarlarına hedef kitle filtresi gösterme seçeneği ekler
     * Bu alan admin panelden hedef kitle filtresini açıp kapatmayı sağlar
     */
    public function up(): void
    {
        Schema::table('search_settings', function (Blueprint $table) {
            // Hedef kitle filtresini göster/gizle ayarı
            $table->boolean('show_hedef_kitle_filter')
                  ->default(false)
                  ->after('search_in_mudurluk_files')
                  ->comment('Arama sayfasında hedef kitle filtresini göster');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('search_settings', function (Blueprint $table) {
            $table->dropColumn('show_hedef_kitle_filter');
        });
    }
};
