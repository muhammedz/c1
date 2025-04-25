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
        Schema::table('menus', function (Blueprint $table) {
            // HeaderMenuItem modeli için gerekli sütunları ekle
            if (!Schema::hasColumn('menus', 'mega_menu_type')) {
                $table->string('mega_menu_type')->nullable()->after('is_mega_menu');
            }
            
            if (!Schema::hasColumn('menus', 'is_bold')) {
                $table->boolean('is_bold')->default(false)->after('is_active');
            }
            
            if (!Schema::hasColumn('menus', 'custom_class')) {
                $table->string('custom_class')->nullable()->after('is_bold');
            }
            
            if (!Schema::hasColumn('menus', 'target')) {
                $table->string('target')->default('_self')->after('custom_class');
            }
            
            if (!Schema::hasColumn('menus', 'mobile_visibility')) {
                $table->boolean('mobile_visibility')->default(true)->after('target');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('menus', function (Blueprint $table) {
            // Eklenen sütunları kaldır
            $table->dropColumn('mega_menu_type');
            $table->dropColumn('is_bold');
            $table->dropColumn('custom_class');
            $table->dropColumn('target');
            $table->dropColumn('mobile_visibility');
        });
    }
};
