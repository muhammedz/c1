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
        Schema::create('filemanagersystem_media_categories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('media_id');
            $table->unsignedBigInteger('category_id');
            $table->timestamps();

            // Foreign key ilişkileri
            $table->foreign('media_id')
                  ->references('id')
                  ->on('filemanagersystem_medias')
                  ->onDelete('cascade');
                  
            $table->foreign('category_id')
                  ->references('id')
                  ->on('filemanagersystem_categories')
                  ->onDelete('cascade');
                  
            // Aynı media_id ve category_id kombinasyonu tekrar etmesin
            $table->unique(['media_id', 'category_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('filemanagersystem_media_categories');
    }
};
