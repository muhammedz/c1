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
        Schema::table('news', function (Blueprint $table) {
            // Önce ana filemanagersystem_image sütununu ekle
            if (!Schema::hasColumn('news', 'filemanagersystem_image')) {
                $table->string('filemanagersystem_image')->nullable();
            }
            
            // Sonra alt ve title sütunlarını ekle
            if (!Schema::hasColumn('news', 'filemanagersystem_image_alt')) {
                $table->string('filemanagersystem_image_alt')->nullable();
            }
            
            if (!Schema::hasColumn('news', 'filemanagersystem_image_title')) {
                $table->string('filemanagersystem_image_title')->nullable();
            }
            
            // Galeri alanı
            if (!Schema::hasColumn('news', 'filemanagersystem_gallery')) {
                $table->json('filemanagersystem_gallery')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('news', function (Blueprint $table) {
            // Reverse işlemi
            $table->dropColumn([
                'filemanagersystem_image',
                'filemanagersystem_image_alt',
                'filemanagersystem_image_title',
                'filemanagersystem_gallery'
            ]);
        });
    }
};
