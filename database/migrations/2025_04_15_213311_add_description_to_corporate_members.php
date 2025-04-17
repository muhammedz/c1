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
            $table->text('description')->nullable()->after('short_description');
            // Eğer biography alanı varsa silmek için
            if (Schema::hasColumn('corporate_members', 'biography')) {
                $table->dropColumn('biography');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('corporate_members', function (Blueprint $table) {
            $table->dropColumn('description');
        });
    }
};
