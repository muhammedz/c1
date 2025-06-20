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
        Schema::table('archives', function (Blueprint $table) {
            $table->boolean('show_download_button')->default(true)->after('is_featured');
            $table->string('download_button_text')->default('Belgeleri İndir')->after('show_download_button');
            $table->string('download_button_url')->nullable()->after('download_button_text');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('archives', function (Blueprint $table) {
            $table->dropColumn(['show_download_button', 'download_button_text', 'download_button_url']);
        });
    }
};
