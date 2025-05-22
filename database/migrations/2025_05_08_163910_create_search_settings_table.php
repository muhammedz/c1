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
        Schema::create('search_settings', function (Blueprint $table) {
            $table->id();
            $table->string('title')->default('Arama');
            $table->string('placeholder')->default('Ne aramıştınız?');
            $table->integer('max_quick_links')->default(4);
            $table->integer('max_popular_queries')->default(4);
            $table->boolean('show_quick_links')->default(true);
            $table->boolean('show_popular_queries')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('search_settings');
    }
};
