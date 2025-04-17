<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('page_settings', function (Blueprint $table) {
            $table->id();
            $table->string('hero_badge_text')->nullable()->default('Bilgi Bankası');
            $table->string('hero_title')->nullable()->default('Bilgiye Hızlı Erişim');
            $table->string('hero_title_highlight')->nullable()->default('Hızlı');
            $table->text('hero_description')->nullable();
            $table->string('search_title')->nullable()->default('Ne aramak istersiniz?');
            $table->string('search_placeholder')->nullable()->default('Anahtar kelime yazın...');
            $table->string('search_button_text')->nullable()->default('Aramayı Başlat');
            $table->string('popular_searches_title')->nullable()->default('Popüler aramalar:');
            $table->json('popular_searches')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('page_settings');
    }
};
