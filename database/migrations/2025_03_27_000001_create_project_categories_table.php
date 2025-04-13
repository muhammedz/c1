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
        Schema::create('project_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Kategori adı (Gerçekleşen Projeler, Planlanan Projeler vb.)
            $table->string('slug')->unique(); // URL-friendly benzersiz tanımlayıcı
            $table->integer('order')->default(0); // Görüntülenme sırası
            $table->boolean('is_active')->default(true); // Aktif/Pasif durumu
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_categories');
    }
}; 