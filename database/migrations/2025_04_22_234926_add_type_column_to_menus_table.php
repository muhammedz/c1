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
        // Önce menus tablosunun var olup olmadığını kontrol edelim
        if (!Schema::hasTable('menus')) {
            // Tablo yoksa oluşturalım
            Schema::create('menus', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('url')->nullable();
                $table->foreignId('parent_id')->nullable()->references('id')->on('menus')->onDelete('set null');
                $table->string('type')->default('link'); // link, header, divider
                $table->integer('order')->default(0);
                $table->boolean('is_mega')->default(false);
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        } else {
            // Tablo varsa eksik sütunları ekleyelim
            Schema::table('menus', function (Blueprint $table) {
                // Her sütun için kontrol edelim
                if (!Schema::hasColumn('menus', 'name')) {
                    $table->string('name')->after('id');
                }
                
                if (!Schema::hasColumn('menus', 'url')) {
                    $table->string('url')->nullable()->after('name');
                }
                
                if (!Schema::hasColumn('menus', 'parent_id')) {
                    $table->foreignId('parent_id')->nullable()->after('url')->references('id')->on('menus')->onDelete('set null');
                }
                
                if (!Schema::hasColumn('menus', 'type')) {
                    $table->string('type')->default('link')->after('parent_id');
                }
                
                if (!Schema::hasColumn('menus', 'order')) {
                    $table->integer('order')->default(0)->after('type');
                }
                
                if (!Schema::hasColumn('menus', 'is_mega')) {
                    $table->boolean('is_mega')->default(false)->after('order');
                }
                
                if (!Schema::hasColumn('menus', 'is_active')) {
                    $table->boolean('is_active')->default(true)->after('is_mega');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Bu migration'ın geriye dönük güvenli bir şekilde kaldırılması zor
        // Bu yüzden sadece type sütununu kaldırıyoruz
        if (Schema::hasTable('menus') && Schema::hasColumn('menus', 'type')) {
            Schema::table('menus', function (Blueprint $table) {
                $table->dropColumn('type');
            });
        }
    }
};
