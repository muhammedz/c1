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
        Schema::create('sliders', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // Slider başlığı
            $table->string('subtitle')->nullable(); // Alt başlık (opsiyonel)
            $table->string('image'); // Slider görseli
            $table->string('button_text')->nullable(); // Buton metni (opsiyonel)
            $table->string('button_url')->nullable(); // Buton linki (opsiyonel)
            $table->integer('order')->default(0); // Görüntülenme sırası
            $table->boolean('is_active')->default(true); // Aktif/pasif durumu
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sliders');
    }
};
