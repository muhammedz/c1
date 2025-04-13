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
        Schema::create('quick_menu_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Kategori adı
            $table->string('description')->nullable(); // Açıklama
            $table->string('icon'); // Material Icons veya Font Awesome ikon kodu
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
        Schema::dropIfExists('quick_menu_categories');
    }
};
