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
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable(); // Kullanıcı ID'si (guest için null)
            $table->string('user_name')->nullable(); // Kullanıcı adı (snapshot için)
            $table->string('model_type'); // Model sınıfı (News, Page, Service vs.)
            $table->unsignedBigInteger('model_id')->nullable(); // Model ID'si
            $table->string('action'); // İşlem türü (created, updated, deleted, restored)
            $table->json('old_values')->nullable(); // Eski değerler
            $table->json('new_values')->nullable(); // Yeni değerler
            $table->text('description')->nullable(); // İşlem açıklaması
            $table->ipAddress('ip_address')->nullable(); // IP adresi
            $table->text('user_agent')->nullable(); // Tarayıcı bilgisi
            $table->string('url')->nullable(); // İşlemin yapıldığı URL
            $table->timestamps();
            
            // İndeksler
            $table->index('user_id');
            $table->index('model_type');
            $table->index('model_id');
            $table->index('action');
            $table->index(['model_type', 'model_id']);
            $table->index(['user_id', 'created_at']);
            $table->index('created_at');
            
            // Foreign key - şimdilik kaldırıyoruz, sonra ekleyebiliriz
            // $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
