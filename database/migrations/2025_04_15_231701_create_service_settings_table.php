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
        Schema::create('service_settings', function (Blueprint $table) {
            $table->id();
            $table->string('hero_badge_text')->nullable();
            $table->string('hero_title')->nullable();
            $table->string('hero_title_highlight')->nullable();
            $table->text('hero_description')->nullable();
            $table->string('search_title')->nullable();
            $table->string('search_placeholder')->nullable();
            $table->string('search_button_text')->nullable();
            $table->string('popular_searches_title')->nullable();
            $table->text('popular_searches')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_settings');
    }
};
