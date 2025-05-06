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
        Schema::create('hedef_kitle_service', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('hedef_kitle_id');
            $table->unsignedBigInteger('service_id');
            $table->timestamps();

            $table->unique(['hedef_kitle_id', 'service_id']);
            
            // Foreign key constraints'i kaldırdık, sadece indexleri koruyoruz
            $table->index('hedef_kitle_id');
            $table->index('service_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hedef_kitle_service');
    }
}; 