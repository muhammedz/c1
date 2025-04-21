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
        Schema::create('filemanagersystem_media_relations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('media_id');
            $table->string('related_type'); // 'slider', 'news', 'page', vb.
            $table->unsignedBigInteger('related_id'); // Slider ID, News ID vb.
            $table->timestamps();
            
            $table->foreign('media_id')->references('id')->on('filemanagersystem_medias')->onDelete('cascade');
            $table->index(['related_type', 'related_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('filemanagersystem_media_relations');
    }
};
