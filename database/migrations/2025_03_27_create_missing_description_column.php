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
        // Eğer projects tablosu varsa ve description sütunu yoksa ekle
        if (Schema::hasTable('projects') && !Schema::hasColumn('projects', 'description')) {
            Schema::table('projects', function (Blueprint $table) {
                $table->text('description')->after('slug'); // 'slug' sütunundan sonra ekle
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Migration'ı geri almak için description sütununu kaldır
        if (Schema::hasTable('projects') && Schema::hasColumn('projects', 'description')) {
            Schema::table('projects', function (Blueprint $table) {
                $table->dropColumn('description');
            });
        }
    }
}; 