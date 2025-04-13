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
        Schema::create('profile_settings', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable(); // Kişi adı
            $table->string('title')->nullable(); // Kişi ünvanı
            $table->string('profile_photo')->nullable(); // Profil fotoğrafı yolu
            $table->string('facebook_url')->nullable(); // Facebook bağlantısı
            $table->string('instagram_url')->nullable(); // Instagram bağlantısı
            $table->string('twitter_url')->nullable(); // Twitter bağlantısı
            $table->string('youtube_url')->nullable(); // YouTube bağlantısı
            $table->string('contact_image')->nullable(); // İletişim görseli yolu
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profile_settings');
    }
};
