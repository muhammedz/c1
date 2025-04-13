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
            // is_published alanını kaldırıp status alanını ekle
            $table->dropColumn('is_published');
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft')->after('featured_image');
            
            // Galeri için
            $table->json('gallery')->nullable()->after('featured_image');
            
            // Öne çıkarma ve popülerlik için
            $table->boolean('is_featured')->default(false)->after('gallery');
            $table->integer('featured_order')->default(0)->after('is_featured');
            $table->integer('view_count')->default(0)->after('featured_order');
            
            // Planlama ve zamanlama için
            $table->boolean('is_scheduled')->default(false)->after('status');
            $table->timestamp('end_date')->nullable()->after('published_at');
            
            // Özet ve içerik ayrımı
            $table->text('summary')->nullable()->after('slug');
            
            // featured_image sütununu yeniden adlandır
            $table->renameColumn('featured_image', 'image');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            // Eklenen alanları kaldır
            $table->dropColumn([
                'gallery',
                'is_featured',
                'featured_order',
                'view_count',
                'status',
                'is_scheduled',
                'end_date',
                'summary'
            ]);
            
            // image sütununu yeniden adlandır
            $table->renameColumn('image', 'featured_image');
            
            // is_published alanını geri ekle
            $table->boolean('is_published')->default(false)->after('image');
        });
    }
};
