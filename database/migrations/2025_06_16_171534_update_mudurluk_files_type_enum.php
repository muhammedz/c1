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
        // MySQL için ENUM değerlerini güncelle
        DB::statement("ALTER TABLE mudurluk_files MODIFY COLUMN type ENUM('hizmet_standartlari', 'yonetim_semalari', 'document')");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Eski haline döndür
        DB::statement("ALTER TABLE mudurluk_files MODIFY COLUMN type ENUM('hizmet_standartlari', 'yonetim_semalari')");
    }
};
