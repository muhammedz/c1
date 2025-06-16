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
        Schema::table('service_settings', function (Blueprint $table) {
            $table->string('hero_subtitle')->nullable()->after('hero_title_highlight');
            $table->string('hero_image')->nullable()->after('hero_description');
            $table->string('meta_title')->nullable()->after('popular_searches');
            $table->text('meta_description')->nullable()->after('meta_title');
            $table->text('meta_keywords')->nullable()->after('meta_description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('service_settings', function (Blueprint $table) {
            $table->dropColumn(['hero_subtitle', 'hero_image', 'meta_title', 'meta_description', 'meta_keywords']);
        });
    }
};
