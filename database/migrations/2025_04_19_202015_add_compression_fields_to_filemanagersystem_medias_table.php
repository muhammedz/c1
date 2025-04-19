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
        Schema::table('filemanagersystem_medias', function (Blueprint $table) {
            $table->unsignedBigInteger('original_size')->nullable()->comment('Orijinal dosya boyutu (byte)');
            $table->unsignedBigInteger('compressed_size')->nullable()->comment('Sıkıştırılmış dosya boyutu (byte)');
            $table->decimal('compression_rate', 5, 2)->nullable()->comment('Sıkıştırma oranı (%)');
            $table->string('webp_url')->nullable()->comment('WebP formatındaki dosyanın URL\'i');
            $table->string('webp_path')->nullable()->comment('WebP formatındaki dosyanın yolu');
            $table->boolean('has_webp')->default(false)->comment('WebP versiyonu var mı');
            $table->integer('width')->nullable()->comment('Resmin genişliği (px)');
            $table->integer('height')->nullable()->comment('Resmin yüksekliği (px)');
            $table->tinyInteger('compression_quality')->nullable()->comment('Sıkıştırma kalitesi (0-100)');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('filemanagersystem_medias', function (Blueprint $table) {
            $table->dropColumn([
                'original_size',
                'compressed_size',
                'compression_rate',
                'webp_url',
                'webp_path',
                'has_webp',
                'width',
                'height',
                'compression_quality'
            ]);
        });
    }
};
