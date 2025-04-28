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
        Schema::create('mobile_app_settings', function (Blueprint $table) {
            $table->id();
            $table->string('app_logo')->nullable();
            $table->string('app_header_image')->nullable();
            $table->string('app_name')->nullable();
            $table->string('app_subtitle')->nullable();
            $table->text('app_description')->nullable();
            $table->string('phone_image')->nullable();
            $table->string('app_store_link')->nullable();
            $table->string('google_play_link')->nullable();
            $table->string('link_card_1_title')->nullable();
            $table->string('link_card_1_url')->nullable();
            $table->string('link_card_1_icon')->nullable();
            $table->string('link_card_2_title')->nullable();
            $table->string('link_card_2_url')->nullable();
            $table->string('link_card_2_icon')->nullable();
            $table->string('link_card_3_title')->nullable();
            $table->string('link_card_3_url')->nullable();
            $table->string('link_card_3_icon')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mobile_app_settings');
    }
};
