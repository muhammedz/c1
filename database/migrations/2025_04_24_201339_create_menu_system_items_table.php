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
        Schema::create('menu_system_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('menu_id')->constrained('menu_systems')->onDelete('cascade');
            $table->foreignId('parent_id')->nullable()->constrained('menu_system_items')->nullOnDelete();
            $table->string('title');
            $table->string('url')->nullable();
            $table->integer('order')->default(0);
            $table->boolean('status')->default(true);
            $table->boolean('new_tab')->default(false);
            $table->string('icon')->nullable();
            $table->string('target')->default('_self'); // _self, _blank, _parent, _top
            $table->text('description')->nullable();
            $table->json('properties')->nullable(); // Ek özellikler için
            $table->timestamps();
            
            // İndeksler
            $table->index('menu_id');
            $table->index('parent_id');
            $table->index(['menu_id', 'parent_id']);
            $table->index(['status', 'order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menu_system_items');
    }
};
