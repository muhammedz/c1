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
        Schema::table('menu_systems', function (Blueprint $table) {
            $table->string('footer_text')->nullable()->after('description')->comment('Açıklama yazısı');
            $table->string('footer_link')->nullable()->after('footer_text')->comment('Açıklama linki');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('menu_systems', function (Blueprint $table) {
            $table->dropColumn('footer_text');
            $table->dropColumn('footer_link');
        });
    }
};
