<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Menus tablosunda eksik sütunları ekle
        Schema::table('menus', function (Blueprint $table) {
            if (!Schema::hasColumn('menus', 'is_mega_menu')) {
                $table->boolean('is_mega_menu')->default(0)->after('order');
            }
            
            if (!Schema::hasColumn('menus', 'icon')) {
                $table->string('icon')->nullable()->after('is_active');
            }
            
            if (!Schema::hasColumn('menus', 'slug')) {
                $table->string('slug')->nullable()->after('name');
            }
        });
        
        // header_mega_menus tablosunu düzelt
        try {
            // İlk olarak, eğer herhangi bir foreign key varsa kaldır
            DB::statement('ALTER TABLE header_mega_menus DROP FOREIGN KEY IF EXISTS header_mega_menus_menu_item_id_foreign');
            DB::statement('ALTER TABLE header_mega_menus DROP FOREIGN KEY IF EXISTS fk_header_mega_menus_menu_id');
            
            // Şimdi menu_item_id sütununu menus tablosundaki id ile eşleşen tipe dönüştür
            DB::statement('ALTER TABLE header_mega_menus MODIFY menu_item_id BIGINT UNSIGNED');
            
            // Yeni foreign key kısıtlaması ekle
            DB::statement('ALTER TABLE header_mega_menus ADD CONSTRAINT fk_header_mega_menus_menu_id FOREIGN KEY (menu_item_id) REFERENCES menus(id) ON DELETE CASCADE');
        } catch (\Exception $e) {
            // Hata oluşursa log dosyasına kaydet
            \Illuminate\Support\Facades\Log::error('Header mega menu relation migration error: ' . $e->getMessage());
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('menus', function (Blueprint $table) {
            if (Schema::hasColumn('menus', 'is_mega_menu')) {
                $table->dropColumn('is_mega_menu');
            }
            
            if (Schema::hasColumn('menus', 'icon')) {
                $table->dropColumn('icon');
            }
            
            if (Schema::hasColumn('menus', 'slug')) {
                $table->dropColumn('slug');
            }
        });
        
        try {
            // Foreign key kısıtlamasını kaldır
            DB::statement('ALTER TABLE header_mega_menus DROP FOREIGN KEY IF EXISTS fk_header_mega_menus_menu_id');
        } catch (\Exception $e) {
            // Hata oluşursa görmezden gel
        }
    }
};
