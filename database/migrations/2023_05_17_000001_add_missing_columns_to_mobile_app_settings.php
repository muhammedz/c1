<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMissingColumnsToMobileAppSettings extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('mobile_app_settings', function (Blueprint $table) {
            // Uygulama bilgileri
            if (!Schema::hasColumn('mobile_app_settings', 'app_logo')) {
                $table->string('app_logo')->nullable();
            }
            if (!Schema::hasColumn('mobile_app_settings', 'app_name')) {
                $table->string('app_name')->nullable();
            }
            if (!Schema::hasColumn('mobile_app_settings', 'app_subtitle')) {
                $table->string('app_subtitle')->nullable();
            }
            if (!Schema::hasColumn('mobile_app_settings', 'app_description')) {
                $table->text('app_description')->nullable();
            }
            if (!Schema::hasColumn('mobile_app_settings', 'phone_image')) {
                $table->string('phone_image')->nullable();
            }
            
            // Mağaza bağlantıları
            if (!Schema::hasColumn('mobile_app_settings', 'app_store_link')) {
                $table->string('app_store_link')->nullable();
            }
            if (!Schema::hasColumn('mobile_app_settings', 'google_play_link')) {
                $table->string('google_play_link')->nullable();
            }
            
            // Link kartları
            if (!Schema::hasColumn('mobile_app_settings', 'link_card_1_title')) {
                $table->string('link_card_1_title')->nullable();
            }
            if (!Schema::hasColumn('mobile_app_settings', 'link_card_1_url')) {
                $table->string('link_card_1_url')->nullable();
            }
            if (!Schema::hasColumn('mobile_app_settings', 'link_card_1_icon')) {
                $table->string('link_card_1_icon')->nullable();
            }
            
            if (!Schema::hasColumn('mobile_app_settings', 'link_card_2_title')) {
                $table->string('link_card_2_title')->nullable();
            }
            if (!Schema::hasColumn('mobile_app_settings', 'link_card_2_url')) {
                $table->string('link_card_2_url')->nullable();
            }
            if (!Schema::hasColumn('mobile_app_settings', 'link_card_2_icon')) {
                $table->string('link_card_2_icon')->nullable();
            }
            
            if (!Schema::hasColumn('mobile_app_settings', 'link_card_3_title')) {
                $table->string('link_card_3_title')->nullable();
            }
            if (!Schema::hasColumn('mobile_app_settings', 'link_card_3_url')) {
                $table->string('link_card_3_url')->nullable();
            }
            if (!Schema::hasColumn('mobile_app_settings', 'link_card_3_icon')) {
                $table->string('link_card_3_icon')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mobile_app_settings', function (Blueprint $table) {
            $columns = [
                'app_logo', 'app_name', 'app_subtitle', 'app_description', 'phone_image',
                'app_store_link', 'google_play_link',
                'link_card_1_title', 'link_card_1_url', 'link_card_1_icon',
                'link_card_2_title', 'link_card_2_url', 'link_card_2_icon',
                'link_card_3_title', 'link_card_3_url', 'link_card_3_icon'
            ];
            
            foreach ($columns as $column) {
                if (Schema::hasColumn('mobile_app_settings', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
} 