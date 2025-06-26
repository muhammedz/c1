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
        Schema::table('search_priority_links', function (Blueprint $table) {
            $table->integer('click_count')->default(0)->after('is_active')->comment('Tıklama sayısı');
            $table->index('click_count');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('search_priority_links', function (Blueprint $table) {
            $table->dropIndex(['click_count']);
            $table->dropColumn('click_count');
        });
    }
};
