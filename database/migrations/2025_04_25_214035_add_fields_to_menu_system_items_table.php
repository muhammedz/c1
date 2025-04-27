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
        Schema::table('menu_system_items', function (Blueprint $table) {
            if (!Schema::hasColumn('menu_system_items', 'item_type')) {
                $table->tinyInteger('item_type')->default(1)->after('menu_id')->comment('1=standard, 2=button');
            }
            if (!Schema::hasColumn('menu_system_items', 'button_style')) {
                $table->string('button_style')->nullable()->after('url')->comment('Buton stili: primary, secondary, success, danger, warning, info');
            }
            // icon zaten olabilir, kontrol ediyoruz
            if (!Schema::hasColumn('menu_system_items', 'icon')) {
                $table->string('icon')->nullable()->after('button_style')->comment('Buton ikon sınıfı');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('menu_system_items', function (Blueprint $table) {
            if (Schema::hasColumn('menu_system_items', 'item_type')) {
                $table->dropColumn('item_type');
            }
            if (Schema::hasColumn('menu_system_items', 'button_style')) {
                $table->dropColumn('button_style');
            }
            // icon kolonunu dropdown yapmıyoruz, muhtemelen önceden varolan bir kolon
        });
    }
};
