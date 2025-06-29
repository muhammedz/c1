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
        Schema::table('featured_services', function (Blueprint $table) {
            $table->string('svg_color')->nullable()->after('icon'); // SVG rengi (hex kodu)
            $table->integer('svg_size')->nullable()->after('svg_color'); // SVG boyutu (px)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('featured_services', function (Blueprint $table) {
            $table->dropColumn(['svg_color', 'svg_size']);
        });
    }
};
