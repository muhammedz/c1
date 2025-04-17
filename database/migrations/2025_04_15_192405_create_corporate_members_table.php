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
        Schema::create('corporate_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('corporate_category_id')->constrained('corporate_categories')->onDelete('cascade');
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('title');
            $table->string('image')->nullable();
            $table->text('short_description')->nullable();
            $table->longText('biography')->nullable();
            $table->string('facebook')->nullable();
            $table->string('twitter')->nullable();
            $table->string('instagram')->nullable();
            $table->string('linkedin')->nullable();
            $table->boolean('status')->default(true);
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('corporate_members');
    }
};
