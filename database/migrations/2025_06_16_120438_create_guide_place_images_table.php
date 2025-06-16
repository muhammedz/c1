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
        Schema::create('guide_place_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('guide_place_id')->constrained()->onDelete('cascade');
            $table->string('image_path', 500);
            $table->string('alt_text')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_featured')->default(false);
            $table->timestamps();
            
            $table->index(['guide_place_id', 'sort_order']);
            $table->index(['guide_place_id', 'is_featured']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guide_place_images');
    }
};
