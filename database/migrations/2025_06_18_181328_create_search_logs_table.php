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
        Schema::create('search_logs', function (Blueprint $table) {
            $table->id();
            $table->string('query'); // Arama kelimesi
            $table->integer('results_count')->default(0); // Sonuç sayısı
            $table->string('ip_address')->nullable(); // IP adresi
            $table->string('user_agent')->nullable(); // User agent
            $table->timestamp('searched_at'); // Arama tarihi
            $table->timestamps();
            
            // İndeksler
            $table->index('query');
            $table->index('searched_at');
            $table->index(['query', 'searched_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('search_logs');
    }
};
