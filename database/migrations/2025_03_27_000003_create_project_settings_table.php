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
        Schema::create('project_settings', function (Blueprint $table) {
            $table->id();
            $table->string('section_title')->default('Projelerimiz'); // Ana bölüm başlığı
            $table->text('section_description')->nullable(); // Bölüm açıklaması
            $table->boolean('is_active')->default(true); // Bölümün görünürlüğü
            $table->integer('items_per_page')->default(6); // Sayfa başına gösterilecek proje sayısı
            $table->boolean('show_categories')->default(true); // Kategori filtreleri gösterilsin mi?
            $table->boolean('show_view_all_button')->default(true); // "Tümünü Gör" butonu gösterilsin mi?
            $table->string('view_all_text')->default('Tümünü Gör'); // "Tümünü Gör" buton metni
            $table->string('view_all_url')->nullable(); // "Tümünü Gör" butonu yönlendirme linki
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_settings');
    }
}; 