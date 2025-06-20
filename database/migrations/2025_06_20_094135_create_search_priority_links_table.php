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
        Schema::create('search_priority_links', function (Blueprint $table) {
            $table->id();
            $table->text('search_keywords')->comment('Arama anahtar kelimeleri (virgülle ayrılmış)');
            $table->string('title')->comment('Link başlığı');
            $table->string('url')->comment('Link URL\'si');
            $table->text('description')->nullable()->comment('Link açıklaması');
            $table->string('icon', 100)->nullable()->comment('Font Awesome icon sınıfı');
            $table->integer('priority')->default(1)->comment('Öncelik sırası (1 en yüksek)');
            $table->boolean('is_active')->default(true)->comment('Aktif durumu');
            $table->timestamps();
            
            // İndeksler
            $table->index('priority');
            $table->index('is_active');
            $table->fullText('search_keywords');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('search_priority_links');
    }
};
