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
        Schema::table('menu_system_items', function (Blueprint $table) {
            $table->string('icon')->default('fa-home')->after('parent_id');
            $table->unsignedInteger('category_id')->default(1)->after('icon');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('menu_system_items', function (Blueprint $table) {
            $table->dropColumn('icon');
            $table->dropColumn('category_id');
        });
    }
}; 