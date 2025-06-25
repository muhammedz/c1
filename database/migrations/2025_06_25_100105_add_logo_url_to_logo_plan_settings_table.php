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
        Schema::table('logo_plan_settings', function (Blueprint $table) {
            $table->string('logo_url')->nullable()->after('logo_bg_color'); // Logo kartına tıklandığında gidilecek URL
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('logo_plan_settings', function (Blueprint $table) {
            $table->dropColumn('logo_url');
        });
    }
};
