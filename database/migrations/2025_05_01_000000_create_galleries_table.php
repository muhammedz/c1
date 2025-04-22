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
        Schema::create('galleries', function (Blueprint $table) {
            $table->id();
            $table->string('type'); // Galeri tipi (service, project, page vb.)
            $table->unsignedBigInteger('reference_id'); // İlgili kayıt ID'si
            $table->string('file_path'); // Dosya yolu
            $table->string('original_name'); // Orijinal dosya adı
            $table->string('file_name'); // Sistemde saklanan dosya adı
            $table->unsignedBigInteger('file_size'); // Dosya boyutu (byte)
            $table->string('mime_type'); // MIME türü
            $table->integer('order')->default(0); // Sıralama
            $table->timestamps();

            // İlgili kayıt ve tipi için indeks
            $table->index(['type', 'reference_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('galleries');
    }
}; 