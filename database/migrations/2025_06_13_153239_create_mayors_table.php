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
        Schema::create('mayors', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Ad Soyad
            $table->string('title')->default('Belediye Başkanı'); // Unvan
            $table->string('profile_image')->nullable(); // Profil fotoğrafı
            $table->text('biography')->nullable(); // Biyografi
            $table->string('social_twitter')->nullable(); // Twitter hesabı
            $table->string('social_instagram')->nullable(); // Instagram hesabı
            $table->string('social_facebook')->nullable(); // Facebook hesabı
            $table->string('social_linkedin')->nullable(); // LinkedIn hesabı
            $table->string('social_email')->nullable(); // Email
            $table->string('hero_bg_color')->default('#00352b'); // Hero arka plan rengi
            $table->string('hero_bg_image')->nullable(); // Hero arka plan görseli
            $table->string('page_title')->default('Başkanımız'); // Sayfa başlığı
            $table->text('meta_description')->nullable(); // Meta açıklama
            $table->boolean('is_active')->default(true); // Aktif/Pasif
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mayors');
    }
};
