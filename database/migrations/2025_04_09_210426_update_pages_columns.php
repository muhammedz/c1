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
                $table->enum('status', ['published', 'draft'])->default('published')->after('content');
            } catch (\Exception $e) {
                // Kolon zaten var
            }
            
            try {
                $table->boolean('is_featured')->default(false)->after('content');
            } catch (\Exception $e) {
                // Kolon zaten var
            }
            
            try {
                $table->integer('featured_order')->nullable()->after('is_featured');
            } catch (\Exception $e) {
                // Kolon zaten var
            }
            
            try {
                $table->boolean('is_archived')->default(false)->after('featured_order');
            } catch (\Exception $e) {
                // Kolon zaten var
            }
            
            try {
                $table->text('summary')->nullable()->after('content');
            } catch (\Exception $e) {
                // Kolon zaten var
            }
            
            try {
                $table->integer('view_count')->default(0)->after('is_archived');
            } catch (\Exception $e) {
                // Kolon zaten var
            }
            
            try {
                $table->string('meta_title')->nullable()->after('view_count');
            } catch (\Exception $e) {
                // Kolon zaten var
            }
            
            try {
                $table->text('meta_description')->nullable()->after('meta_title');
            } catch (\Exception $e) {
                // Kolon zaten var
            }
            
            try {
                $table->string('meta_keywords')->nullable()->after('meta_description');
            } catch (\Exception $e) {
                // Kolon zaten var
            }
            
            try {
                $table->timestamp('published_at')->nullable()->after('meta_keywords');
            } catch (\Exception $e) {
                // Kolon zaten var
            }
            
            try {
                $table->string('image')->nullable()->after('content');
            } catch (\Exception $e) {
                // Kolon zaten var
            }
            
            try {
                $table->json('gallery')->nullable()->after('image');
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
        // Gerekirse kolonları tek tek kaldırmak için komutlar eklenebilir
    }
};
