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
        Schema::table('search_settings', function (Blueprint $table) {
            $table->boolean('search_in_mudurluk_files')->default(false)->after('show_popular_queries')
                  ->comment('Müdürlük dosyalarında arama yapmayı etkinleştir');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('search_settings', function (Blueprint $table) {
            $table->dropColumn('search_in_mudurluk_files');
        });
    }
};
