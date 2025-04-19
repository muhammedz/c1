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
        Schema::create('filemanagersystem_relations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('media_id');
            $table->unsignedBigInteger('related_id');
            $table->string('related_type');
            $table->string('field_name')->nullable();
            $table->integer('order')->default(0);
            $table->json('custom_properties')->nullable();
            $table->timestamps();
            
            // Foreign key ilişkileri - sistem hazır olduğunda aktifleştirilecek
            // $table->foreign('media_id')->references('id')->on('filemanagersystem_medias')->onDelete('cascade');
            
            // Birleşik index ekliyoruz
            $table->index(['related_id', 'related_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('filemanagersystem_relations');
    }
};
