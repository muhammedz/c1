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
        Schema::create('mudurluk_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mudurluk_id')->constrained('mudurlukler')->onDelete('cascade');
            $table->enum('type', ['hizmet_standartlari', 'yonetim_semalari']);
            $table->string('title');
            $table->string('file_path');
            $table->string('file_name');
            $table->integer('file_size')->default(0);
            $table->integer('order_column')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            // Indexler
            $table->index(['mudurluk_id', 'type']);
            $table->index(['mudurluk_id', 'is_active', 'order_column']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mudurluk_files');
    }
};
