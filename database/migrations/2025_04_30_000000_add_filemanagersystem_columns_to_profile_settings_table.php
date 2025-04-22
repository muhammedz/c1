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
        Schema::table('profile_settings', function (Blueprint $table) {
            $table->string('filemanagersystem_profile_photo')->nullable()->after('profile_photo');
            $table->string('filemanagersystem_profile_photo_alt')->nullable()->after('filemanagersystem_profile_photo');
            $table->string('filemanagersystem_profile_photo_title')->nullable()->after('filemanagersystem_profile_photo_alt');
            $table->string('filemanagersystem_contact_image')->nullable()->after('contact_image');
            $table->string('filemanagersystem_contact_image_alt')->nullable()->after('filemanagersystem_contact_image');
            $table->string('filemanagersystem_contact_image_title')->nullable()->after('filemanagersystem_contact_image_alt');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('profile_settings', function (Blueprint $table) {
            $table->dropColumn('filemanagersystem_profile_photo');
            $table->dropColumn('filemanagersystem_profile_photo_alt');
            $table->dropColumn('filemanagersystem_profile_photo_title');
            $table->dropColumn('filemanagersystem_contact_image');
            $table->dropColumn('filemanagersystem_contact_image_alt');
            $table->dropColumn('filemanagersystem_contact_image_title');
        });
    }
}; 