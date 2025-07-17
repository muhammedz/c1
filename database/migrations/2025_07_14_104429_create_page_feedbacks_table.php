<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Sayfa Geri Bildirim Modülü için veritabanı tablosu oluşturur.
     * Bu tablo kullanıcıların hizmet sayfalarına verdiği geri bildirimleri saklar.
     */
    public function up(): void
    {
        Schema::create('page_feedbacks', function (Blueprint $table) {
            $table->id();
            
            // Sayfa bilgileri
            $table->string('page_url', 500)->comment('Geri bildirim verilen sayfa URL\'si');
            $table->string('page_title', 255)->comment('Sayfa başlığı (admin panelde görüntüleme için)');
            
            // Geri bildirim bilgisi
            $table->boolean('is_helpful')->comment('true=Bu sayfa yardımcı oldu, false=Bu sayfa yardımcı olmadı');
            
            // Kullanıcı bilgileri (anonim takip için)
            $table->string('user_ip', 45)->comment('Kullanıcı IP adresi (IPv6 desteği için)');
            $table->text('user_agent')->nullable()->comment('Tarayıcı bilgisi (istatistik amaçlı)');
            
            // Zaman damgaları
            $table->timestamps();
            
            // İndeksler (performans için)
            $table->index('page_url', 'idx_page_url');
            $table->index('is_helpful', 'idx_is_helpful');
            $table->index('created_at', 'idx_created_at');
            $table->index(['page_url', 'created_at'], 'idx_page_url_created_at');
            $table->index(['user_ip', 'page_url'], 'idx_user_ip_page_url');
        });
    }

    /**
     * Reverse the migrations.
     * 
     * Sayfa geri bildirim tablosunu siler.
     */
    public function down(): void
    {
        Schema::dropIfExists('page_feedbacks');
    }
};
