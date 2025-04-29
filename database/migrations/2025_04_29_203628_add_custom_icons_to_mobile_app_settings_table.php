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
        Schema::table('mobile_app_settings', function (Blueprint $table) {
            // Özel ikon alanları ekleniyor
            $table->string('link_card_1_custom_icon')->nullable()->after('link_card_1_icon');
            $table->string('link_card_2_custom_icon')->nullable()->after('link_card_2_icon');
            $table->string('link_card_3_custom_icon')->nullable()->after('link_card_3_icon');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mobile_app_settings', function (Blueprint $table) {
            $table->dropColumn('link_card_1_custom_icon');
            $table->dropColumn('link_card_2_custom_icon');
            $table->dropColumn('link_card_3_custom_icon');
        });
    }
};
