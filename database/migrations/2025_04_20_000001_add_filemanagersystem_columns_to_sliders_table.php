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
        Schema::table('sliders', function (Blueprint $table) {
            $table->string('filemanagersystem_image')->nullable()->after('subtitle')->comment('FileManagerSystem ile yüklenen slider görseli');
            $table->string('filemanagersystem_image_alt')->nullable()->after('filemanagersystem_image')->comment('Slider görseli alt metni');
            $table->string('filemanagersystem_image_title')->nullable()->after('filemanagersystem_image_alt')->comment('Slider görseli başlığı');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sliders', function (Blueprint $table) {
            $table->dropColumn([
                'filemanagersystem_image',
                'filemanagersystem_image_alt',
                'filemanagersystem_image_title'
            ]);
        });
    }
}; 