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
        Schema::table('menus', function (Blueprint $table) {
            $table->string('mega_menu_layout')->nullable()->comment('Mega menü tipi: standard, card_grid, custom');
            $table->json('layout_settings')->nullable()->comment('Özel tasarım ayarları için JSON formatında veri');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('menus', function (Blueprint $table) {
            $table->dropColumn('mega_menu_layout');
            $table->dropColumn('layout_settings');
        });
    }
};
