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
        Schema::create('header_settings', function (Blueprint $table) {
            $table->id();
            $table->string('logo_path')->nullable();
            $table->string('secondary_logo_path')->nullable();
            $table->string('slogan_path')->nullable();
            $table->boolean('show_search_button')->default(true);
            $table->string('header_bg_color', 20)->default('#ffffff');
            $table->string('header_text_color', 20)->default('#00352b');
            $table->integer('header_height')->default(96);
            $table->boolean('sticky_header')->default(false);
            $table->text('custom_css')->nullable();
            $table->text('additional_scripts')->nullable();
            $table->text('custom_header_html')->nullable();
            $table->string('mobile_logo_path')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('header_settings');
    }
};
