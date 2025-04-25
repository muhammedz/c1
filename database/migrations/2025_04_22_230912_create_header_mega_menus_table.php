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
        Schema::create('header_mega_menus', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('menu_item_id');
            $table->string('title', 100);
            $table->text('content')->nullable();
            $table->string('custom_class', 100)->nullable();
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->string('column_width', 20)->nullable()->default('1/4');
            $table->string('background_color', 20)->nullable();
            $table->timestamps();
            
            // Foreign key
            $table->foreign('menu_item_id')->references('id')->on('header_menu_items')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('header_mega_menus');
    }
};
