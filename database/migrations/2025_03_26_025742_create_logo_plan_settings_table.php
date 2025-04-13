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
        Schema::create('logo_plan_settings', function (Blueprint $table) {
            $table->id();
            $table->boolean('is_active')->default(true); // Bölümün görünürlüğü
            
            // 1. Kart - Sol üst (Yazı sayfaya gidecek)
            $table->string('card1_title')->nullable(); // Kart başlığı
            $table->string('card1_icon')->nullable(); // Font awesome veya svg ikon
            $table->string('card1_url')->nullable(); // Yönlendirilecek sayfa URL'i
            
            // 2. Kart - Sol alt (Stratejik Plan)
            $table->string('card2_title')->nullable(); // Kart başlığı
            $table->string('card2_image')->nullable(); // Görsel dosya yolu
            $table->string('card2_url')->nullable(); // Yönlendirilecek sayfa veya indirme linki
            
            // 3. Kart - Büyük Logo
            $table->string('logo_title')->nullable(); // Logo başlığı veya açıklaması
            $table->string('logo_image')->nullable(); // Logo görsel dosya yolu
            $table->string('logo_bg_color')->default('#004d2e'); // Arkaplan rengi
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('logo_plan_settings');
    }
};
