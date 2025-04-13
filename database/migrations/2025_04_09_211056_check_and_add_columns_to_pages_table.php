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
        // view_count kolonunu ekle
        if (!Schema::hasColumn('pages', 'view_count')) {
            Schema::table('pages', function (Blueprint $table) {
                $table->integer('view_count')->default(0);
            });
        }
        
        // summary kolonunu ekle
        if (!Schema::hasColumn('pages', 'summary')) {
            Schema::table('pages', function (Blueprint $table) {
                $table->text('summary')->nullable();
            });
        }
        
        // meta_title kolonunu ekle
        if (!Schema::hasColumn('pages', 'meta_title')) {
            Schema::table('pages', function (Blueprint $table) {
                $table->string('meta_title')->nullable();
            });
        }
        
        // meta_description kolonunu ekle
        if (!Schema::hasColumn('pages', 'meta_description')) {
            Schema::table('pages', function (Blueprint $table) {
                $table->text('meta_description')->nullable();
            });
        }
        
        // meta_keywords kolonunu ekle
        if (!Schema::hasColumn('pages', 'meta_keywords')) {
            Schema::table('pages', function (Blueprint $table) {
                $table->string('meta_keywords')->nullable();
            });
        }
        
        // published_at kolonunu ekle
        if (!Schema::hasColumn('pages', 'published_at')) {
            Schema::table('pages', function (Blueprint $table) {
                $table->timestamp('published_at')->nullable();
            });
        }
        
        // image kolonunu ekle
        if (!Schema::hasColumn('pages', 'image')) {
            Schema::table('pages', function (Blueprint $table) {
                $table->string('image')->nullable();
            });
        }
        
        // gallery kolonunu ekle
        if (!Schema::hasColumn('pages', 'gallery')) {
            Schema::table('pages', function (Blueprint $table) {
                $table->json('gallery')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Bu migration'ın down metodu boş bırakılmıştır çünkü kolonu kaldırmak güvenli değildir
    }
};
