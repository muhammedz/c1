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
        // menus tablosuna slug sütunu ekleme
        if (Schema::hasTable('menus') && !Schema::hasColumn('menus', 'slug')) {
            Schema::table('menus', function (Blueprint $table) {
                $table->string('slug')->nullable()->after('name');
            });
        }

        // icon sütunu ekleme
        if (Schema::hasTable('menus') && !Schema::hasColumn('menus', 'icon')) {
            Schema::table('menus', function (Blueprint $table) {
                $table->string('icon')->nullable()->after('order');
            });
        }

        // is_mega_menu sütunu ekleme (eğer is_mega yoksa)
        if (Schema::hasTable('menus') && !Schema::hasColumn('menus', 'has_mega_menu') && !Schema::hasColumn('menus', 'is_mega')) {
            Schema::table('menus', function (Blueprint $table) {
                $table->boolean('has_mega_menu')->default(false)->after('order');
            });
        }

        // is_mega varsa is_mega_menu'ya rename etme
        if (Schema::hasTable('menus') && Schema::hasColumn('menus', 'is_mega') && !Schema::hasColumn('menus', 'has_mega_menu')) {
            Schema::table('menus', function (Blueprint $table) {
                $table->renameColumn('is_mega', 'has_mega_menu');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // is_mega_menu sütununu is_mega'ya geri rename etme
        if (Schema::hasTable('menus') && Schema::hasColumn('menus', 'has_mega_menu') && !Schema::hasColumn('menus', 'is_mega')) {
            Schema::table('menus', function (Blueprint $table) {
                $table->renameColumn('has_mega_menu', 'is_mega');
            });
        }

        // icon sütununu kaldırma
        if (Schema::hasTable('menus') && Schema::hasColumn('menus', 'icon')) {
            Schema::table('menus', function (Blueprint $table) {
                $table->dropColumn('icon');
            });
        }

        // slug sütununu kaldırma
        if (Schema::hasTable('menus') && Schema::hasColumn('menus', 'slug')) {
            Schema::table('menus', function (Blueprint $table) {
                $table->dropColumn('slug');
            });
        }
    }
}; 