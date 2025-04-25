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
        Schema::create('header_mega_menu_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('mega_menu_id');
            $table->string('title', 100);
            $table->string('url', 255)->nullable();
            $table->string('icon', 50)->nullable();
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->string('custom_class', 100)->nullable();
            $table->string('target', 20)->nullable()->default('_self');
            $table->timestamps();
            
            // Foreign key
            $table->foreign('mega_menu_id')->references('id')->on('header_mega_menus')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('header_mega_menu_items');
    }
};
