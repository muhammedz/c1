<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Doctrine\DBAL\Types\Type;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('page_settings', function (Blueprint $table) {
            // Önce mevcut tabloyu temizleyelim
            DB::table('page_settings')->delete();
            
            // Sütunları nullable yapalım
            $table->string('hero_badge_text')->nullable()->change();
            $table->string('hero_title')->nullable()->change();
            $table->string('hero_title_highlight')->nullable()->change();
            $table->string('search_title')->nullable()->change();
            $table->string('search_placeholder')->nullable()->change();
            $table->string('search_button_text')->nullable()->change();
            $table->string('popular_searches_title')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('page_settings', function (Blueprint $table) {
            $table->string('hero_badge_text')->nullable(false)->change();
            $table->string('hero_title')->nullable(false)->change();
            $table->string('hero_title_highlight')->nullable(false)->change();
            $table->string('search_title')->nullable(false)->change();
            $table->string('search_placeholder')->nullable(false)->change();
            $table->string('search_button_text')->nullable(false)->change();
            $table->string('popular_searches_title')->nullable(false)->change();
        });
    }
};
