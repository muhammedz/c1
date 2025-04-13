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
        Schema::create('featured_services', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // Hizmet başlığı
            $table->text('icon')->nullable(); // SVG ikonu veya icon sınıfı
            $table->string('url')->nullable(); // Opsiyonel bağlantı
            $table->integer('order')->default(0); // Sıralama için
            $table->boolean('is_active')->default(true); // Hizmetin görünürlüğü
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('featured_services');
    }
}; 