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
        Schema::table('filemanagersystem_media_relations', function (Blueprint $table) {
            $table->string('field_name')->default('default')->after('related_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('filemanagersystem_media_relations', function (Blueprint $table) {
            $table->dropColumn('field_name');
        });
    }
};
