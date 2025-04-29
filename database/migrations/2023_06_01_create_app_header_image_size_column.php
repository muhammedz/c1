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
            if (!Schema::hasColumn('mobile_app_settings', 'app_header_image_width')) {
                $table->integer('app_header_image_width')->nullable()->default(320)->after('app_header_image');
            }
            if (!Schema::hasColumn('mobile_app_settings', 'app_header_image_height')) {
                $table->integer('app_header_image_height')->nullable()->default(200)->after('app_header_image_width');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mobile_app_settings', function (Blueprint $table) {
            if (Schema::hasColumn('mobile_app_settings', 'app_header_image_width')) {
                $table->dropColumn('app_header_image_width');
            }
            if (Schema::hasColumn('mobile_app_settings', 'app_header_image_height')) {
                $table->dropColumn('app_header_image_height');
            }
        });
    }
}; 