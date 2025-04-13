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
        Schema::create('quick_menu_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('quick_menu_categories')->onDelete('cascade');
            $table->string('title'); // Menü öğesi başlığı
            $table->string('url'); // Yönlendirilecek bağlantı
            $table->string('icon')->nullable(); // Opsiyonel ikon (Material Icon vb.)
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
        Schema::dropIfExists('quick_menu_items');
    }
};
