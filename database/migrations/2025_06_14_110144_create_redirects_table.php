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
        Schema::create('redirects', function (Blueprint $table) {
            $table->id();
            $table->string('from_url', 500)->unique(); // Eski URL
            $table->string('to_url', 500); // Yeni URL
            $table->enum('redirect_type', ['301', '302'])->default('301'); // Yönlendirme tipi
            $table->boolean('is_active')->default(true); // Aktif mi?
            $table->integer('hit_count')->default(0); // Kaç kez kullanıldı
            $table->unsignedBigInteger('created_by')->nullable(); // Oluşturan admin
            $table->text('description')->nullable(); // Açıklama
            $table->timestamps();
            
            // Indexler
            $table->index(['from_url', 'is_active']);
            $table->index('is_active');
            $table->index('hit_count');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('redirects');
    }
};
