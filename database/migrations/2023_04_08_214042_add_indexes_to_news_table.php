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
        Schema::table('news', function (Blueprint $table) {
            // Filtreleme işlemlerinde sık kullanılan alanlara index ekle
            $table->index('status');
            $table->index('is_headline');
            $table->index('is_featured');
            $table->index('is_scheduled');
            $table->index('published_at');
            $table->index('end_date');
            $table->index('created_at');
            
            // Sıralama ve manşet için çoklu index
            $table->index(['is_headline', 'headline_order']);
            
            // Öne çıkan haberler ve yayın tarihi için çoklu index
            $table->index(['is_featured', 'published_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('news', function (Blueprint $table) {
            // Tekli indexleri kaldır
            $table->dropIndex(['status']);
            $table->dropIndex(['is_headline']);
            $table->dropIndex(['is_featured']);
            $table->dropIndex(['is_scheduled']);
            $table->dropIndex(['published_at']);
            $table->dropIndex(['end_date']);
            $table->dropIndex(['created_at']);
            
            // Çoklu indexleri kaldır
            $table->dropIndex(['is_headline', 'headline_order']);
            $table->dropIndex(['is_featured', 'published_at']);
        });
    }
}; 