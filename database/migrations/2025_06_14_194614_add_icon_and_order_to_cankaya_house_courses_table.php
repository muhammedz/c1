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
        Schema::table('cankaya_house_courses', function (Blueprint $table) {
            $table->string('icon', 100)->nullable()->after('name');
            $table->integer('order')->default(0)->after('status');
            
            $table->index('order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cankaya_house_courses', function (Blueprint $table) {
            $table->dropIndex(['order']);
            $table->dropColumn(['icon', 'order']);
        });
    }
};
