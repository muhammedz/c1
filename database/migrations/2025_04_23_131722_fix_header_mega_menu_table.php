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
        // Önce mevcut foreign key kısıtlamasını kaldır (eğer varsa)
        Schema::table('header_mega_menus', function (Blueprint $table) {
            try {
                $foreignKeys = Schema::getConnection()
                    ->getDoctrineSchemaManager()
                    ->listTableForeignKeys('header_mega_menus');
                    
                foreach ($foreignKeys as $foreignKey) {
                    if (in_array('menu_item_id', $foreignKey->getLocalColumns())) {
                        $table->dropForeign($foreignKey->getName());
                    }
                }
            } catch (\Exception $e) {
                // Foreign key bulunamadı, devam et
            }
        });

        // menu_item_id sütununu güncelle
        Schema::table('header_mega_menus', function (Blueprint $table) {
            $table->unsignedBigInteger('menu_item_id')->change();
        });

        // Veritabanı ilişkisini tekrar oluştur
        DB::statement('ALTER TABLE header_mega_menus ADD CONSTRAINT fk_header_mega_menus_menu_id FOREIGN KEY (menu_item_id) REFERENCES menus(id) ON DELETE CASCADE');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // İlişkiyi kaldır
        Schema::table('header_mega_menus', function (Blueprint $table) {
            try {
                $table->dropForeign('fk_header_mega_menus_menu_id');
            } catch (\Exception $e) {
                // Foreign key bulunamadı, devam et
            }
        });
    }
};
