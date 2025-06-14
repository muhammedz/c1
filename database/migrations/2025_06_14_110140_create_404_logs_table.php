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
        Schema::create('404_logs', function (Blueprint $table) {
            $table->id();
            $table->string('url', 500)->index(); // 404 veren URL
            $table->string('referer', 500)->nullable(); // Nereden geldiği
            $table->text('user_agent')->nullable(); // Tarayıcı bilgisi
            $table->string('ip_address', 45)->nullable(); // IP adresi
            $table->integer('hit_count')->default(1); // Kaç kez 404 verdiği
            $table->timestamp('first_seen_at'); // İlk görülme tarihi
            $table->timestamp('last_seen_at'); // Son görülme tarihi
            $table->boolean('is_resolved')->default(false); // Çözüldü mü?
            $table->timestamps();
            
            // Indexler
            $table->index(['url', 'is_resolved']);
            $table->index('hit_count');
            $table->index('last_seen_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('404_logs');
    }
};
