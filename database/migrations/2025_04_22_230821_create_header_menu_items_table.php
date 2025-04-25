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
        Schema::create('header_menu_items', function (Blueprint $table) {
            $table->id();
            $table->string('title', 100);
            $table->string('url', 255)->nullable();
            $table->integer('order')->default(0);
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->string('icon', 50)->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('has_mega_menu')->default(false);
            $table->string('mega_menu_type', 20)->nullable()->default('normal');
            $table->boolean('is_bold')->default(false);
            $table->string('custom_class', 100)->nullable();
            $table->string('target', 20)->nullable()->default('_self');
            $table->boolean('mobile_visibility')->default(true);
            $table->timestamps();
            
            // Foreign key
            $table->foreign('parent_id')->references('id')->on('header_menu_items')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('header_menu_items');
    }
};
