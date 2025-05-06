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
        Schema::create('hedef_kitle_news', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('hedef_kitle_id');
            $table->unsignedBigInteger('news_id');
            $table->timestamps();

            $table->unique(['hedef_kitle_id', 'news_id']);
            
            $table->foreign('hedef_kitle_id')->references('id')->on('hedef_kitleler')->onDelete('cascade');
            // Veritabanında tablolar arası uyumsuzluk olabilir, bu yüzden aşağıdaki ilişkiyi şu an eklemiyoruz
            // $table->foreign('news_id')->references('id')->on('news')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hedef_kitle_news');
    }
};
