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
        // 1. Önce kısıtlamaları kaldır
        Schema::table('header_mega_menus', function (Blueprint $table) {
            // Foreign key kısıtlamalarını tespit et
            $foreignKeys = Schema::getConnection()
                ->getDoctrineSchemaManager()
                ->listTableForeignKeys('header_mega_menus');
                
            // Kısıtlamaları kaldır
            foreach ($foreignKeys as $foreignKey) {
                if ($foreignKey->getLocalColumns() === ['menu_item_id']) {
                    $table->dropForeign($foreignKey->getName());
                }
            }
        });
        
        // 2. Yeni ilişkiyi ekle
        Schema::table('header_mega_menus', function (Blueprint $table) {
            // menu_item_id alanı için yeni foreign key ilişkisi menus tablosuna
            $table->foreign('menu_item_id')
                ->references('id')
                ->on('menus')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // 1. Önce yeni kısıtlamayı kaldır
        Schema::table('header_mega_menus', function (Blueprint $table) {
            $foreignKeys = Schema::getConnection()
                ->getDoctrineSchemaManager()
                ->listTableForeignKeys('header_mega_menus');
                
            foreach ($foreignKeys as $foreignKey) {
                if ($foreignKey->getLocalColumns() === ['menu_item_id']) {
                    $table->dropForeign($foreignKey->getName());
                }
            }
        });
        
        // 2. Eski ilişkiyi geri yükle
        Schema::table('header_mega_menus', function (Blueprint $table) {
            $table->foreign('menu_item_id')
                ->references('id')
                ->on('header_menu_items')
                ->onDelete('cascade');
        });
    }
};