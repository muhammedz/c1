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
        Schema::table('pages', function (Blueprint $table) {
            try {
                $table->integer('view_count')->default(0);
            } catch (\Exception $e) {
                // Kolon zaten var
            }
            
            try {
                $table->text('summary')->nullable();
            } catch (\Exception $e) {
                // Kolon zaten var
            }
            
            try {
                $table->string('meta_title')->nullable();
            } catch (\Exception $e) {
                // Kolon zaten var
            }
            
            try {
                $table->text('meta_description')->nullable();
            } catch (\Exception $e) {
                // Kolon zaten var
            }
            
            try {
                $table->string('meta_keywords')->nullable();
            } catch (\Exception $e) {
                // Kolon zaten var
            }
            
            try {
                $table->timestamp('published_at')->nullable();
            } catch (\Exception $e) {
                // Kolon zaten var
            }
            
            try {
                $table->string('image')->nullable();
            } catch (\Exception $e) {
                // Kolon zaten var
            }
            
            try {
                $table->json('gallery')->nullable();
            } catch (\Exception $e) {
                // Kolon zaten var
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Çok fazla kolonu kaldırmak riskli olabilir, bu yüzden burayı boş bırakıyoruz
    }
};
