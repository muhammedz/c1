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
        Schema::create('filemanagersystem_medias', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('original_name');
            $table->string('mime_type');
            $table->string('extension')->nullable();
            $table->string('size')->comment('byte cinsinden dosya boyutu');
            $table->string('path');
            $table->string('url')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('folder_id')->nullable();
            $table->boolean('is_public')->default(false);
            $table->timestamps();
            
            // Foreign key kısıtlamaları şimdilik kaldırıldı
            // $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            // $table->foreign('folder_id')->references('id')->on('filemanagersystem_folders')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('filemanagersystem_medias');
    }
};
