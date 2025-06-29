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
            // Icon kolonunu TEXT tipine çevir (PNG base64 verileri için)
            $table->text('icon')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('menu_system_items', function (Blueprint $table) {
            // Geri alma işleminde varchar(191) tipine döndür
            $table->string('icon', 191)->nullable()->change();
        });
    }
};
