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
        // menu_systems tablosundaki type alanının comment kısmını güncelle
        DB::statement("ALTER TABLE `menu_systems` CHANGE `type` `type` INT NOT NULL DEFAULT '1' COMMENT '1: Ana Menü, 2: Alt Menü, 3: Buton Menü'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Eskiye döndür
        DB::statement("ALTER TABLE `menu_systems` CHANGE `type` `type` INT NOT NULL DEFAULT '1' COMMENT '1: Ana Menü, 2: Alt Menü, 3: Kategori Menüsü'");
    }
}; 