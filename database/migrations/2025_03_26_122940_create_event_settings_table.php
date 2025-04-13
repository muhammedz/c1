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
        Schema::create('event_settings', function (Blueprint $table) {
            $table->id();
            $table->boolean('is_active')->default(true); // Modül aktif mi?
            $table->string('title')->default('Etkinlikler');
            $table->string('description')->nullable();
            $table->string('section_title')->nullable(); // Ana sayfadaki bölüm başlığı
            $table->string('section_subtitle')->nullable(); // Ana sayfadaki bölüm alt başlığı
            $table->integer('homepage_limit')->default(6); // Ana sayfada gösterilecek etkinlik sayısı
            $table->boolean('show_past_events')->default(false); // Geçmiş etkinlikler gösterilsin mi?
            $table->boolean('show_category_filter')->default(true); // Kategori filtreleme gösterilsin mi?
            $table->boolean('show_map')->default(true); // Harita gösterilsin mi?
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_settings');
    }
};
