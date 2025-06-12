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
        Schema::table('corporate_members', function (Blueprint $table) {
            $table->boolean('use_custom_link')->default(false)->after('show_detail');
            $table->string('custom_link', 500)->nullable()->after('use_custom_link');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('corporate_members', function (Blueprint $table) {
            $table->dropColumn(['use_custom_link', 'custom_link']);
        });
    }
};
