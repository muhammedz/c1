<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tooltip alanını service_topics tablosuna ekler
     * Admin panelinden hizmet konuları için özel tooltip metni tanımlanabilir
     */
    public function up(): void
    {
        Schema::table('service_topics', function (Blueprint $table) {
            // Tooltip text alanını description'dan sonra ekle
            $table->string('tooltip_text', 255)->nullable()->after('description')
                  ->comment('Kategori başlığına hover edildiğinde gösterilecek özel tooltip metni');
        });
    }

    /**
     * Tooltip alanını service_topics tablosundan kaldırır
     */
    public function down(): void
    {
        Schema::table('service_topics', function (Blueprint $table) {
            $table->dropColumn('tooltip_text');
        });
    }
};
