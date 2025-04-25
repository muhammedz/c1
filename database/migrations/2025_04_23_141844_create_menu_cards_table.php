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
        Schema::create('menu_cards', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('menu_id');
            $table->string('title');
            $table->string('icon', 100);
            $table->string('url');
            $table->string('data_category', 100)->nullable();
            $table->string('color', 50)->default('#007b32');
            $table->integer('order')->default(0);
            $table->tinyInteger('is_active')->default(1);
            $table->timestamps();
            
            // Foreign key'i kaldırdık
            // $table->foreign('menu_id')->references('id')->on('menus')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menu_cards');
    }
};
