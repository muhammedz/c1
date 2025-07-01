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
        Schema::table('mayor_content', function (Blueprint $table) {
            $table->string('filemanagersystem_image')->nullable()->after('image');
            $table->string('filemanagersystem_image_alt')->nullable()->after('filemanagersystem_image');
            $table->string('filemanagersystem_image_title')->nullable()->after('filemanagersystem_image_alt');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mayor_content', function (Blueprint $table) {
            $table->dropColumn([
                'filemanagersystem_image',
                'filemanagersystem_image_alt',
                'filemanagersystem_image_title'
            ]);
        });
    }
}; 