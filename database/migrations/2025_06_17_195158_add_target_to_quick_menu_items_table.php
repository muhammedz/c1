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
        Schema::table('quick_menu_items', function (Blueprint $table) {
            $table->string('target')->default('_self')->after('url'); // _self: aynı sekme, _blank: yeni sekme
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quick_menu_items', function (Blueprint $table) {
            $table->dropColumn('target');
        });
    }
};
