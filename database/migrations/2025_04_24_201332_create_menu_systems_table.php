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
        Schema::create('menu_systems', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->integer('type')->default(1)->comment('1: Ana Menü, 2: Alt Menü, 3: Kategori Menüsü');
            $table->string('position')->default('header')->comment('header, footer, sidebar, mobile, main, top, bottom');
            $table->string('url')->nullable();
            $table->integer('order')->default(0);
            $table->boolean('status')->default(true);
            $table->text('description')->nullable();
            $table->json('properties')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menu_systems');
    }
};
