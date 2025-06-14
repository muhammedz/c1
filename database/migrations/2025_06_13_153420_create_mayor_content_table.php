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
        Schema::create('mayor_content', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mayor_id')->constrained('mayors')->onDelete('cascade'); // Foreign Key
            $table->enum('type', ['story', 'agenda', 'gallery']); // İçerik türü
            $table->string('title'); // Başlık
            $table->text('description')->nullable(); // Açıklama/İçerik
            $table->string('image')->nullable(); // Görsel
            $table->json('extra_data')->nullable(); // Ek bilgiler (JSON)
            $table->integer('sort_order')->default(0); // Sıralama
            $table->boolean('is_active')->default(true); // Aktif/Pasif
            $table->timestamps();
            
            // İndeksler
            $table->index(['mayor_id', 'type']);
            $table->index(['type', 'is_active', 'sort_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mayor_content');
    }
};
