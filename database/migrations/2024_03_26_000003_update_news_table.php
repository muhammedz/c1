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
            // Galeri için
            $table->json('gallery')->nullable()->after('image');
            
            // Öne çıkarma ve popülerlik için
            $table->boolean('is_featured')->default(false)->after('is_headline');
            $table->integer('view_count')->default(0)->after('is_featured');
            
            // Planlama ve zamanlama için
            $table->boolean('is_scheduled')->default(false)->after('status');
            $table->timestamp('end_date')->nullable()->after('published_at');
            
            // Özet ve içerik ayrımı
            $table->text('summary')->nullable()->after('slug');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('news', function (Blueprint $table) {
            $table->dropColumn([
                'gallery',
                'is_featured',
                'view_count',
                'is_scheduled',
                'end_date',
                'summary'
            ]);
        });
    }
}; 