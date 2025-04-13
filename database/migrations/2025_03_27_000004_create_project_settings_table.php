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
        // Tablo var mı diye kontrol et
        if (!Schema::hasTable('project_settings')) {
            // Tablo yoksa oluştur
            Schema::create('project_settings', function (Blueprint $table) {
                $table->id();
                $table->string('section_title')->default('Projelerimiz');
                $table->text('section_description')->nullable();
                $table->integer('items_per_page')->default(6);
                $table->boolean('is_active')->default(true);
                $table->boolean('show_categories_filter')->default(true);
                $table->boolean('show_view_all')->default(true);
                $table->string('view_all_text')->nullable();
                $table->string('view_all_url')->nullable();
                $table->timestamps();
            });
        } else {
            // Tablo varsa sütunların varlığını kontrol et ve ekle
            Schema::table('project_settings', function (Blueprint $table) {
                if (!Schema::hasColumn('project_settings', 'show_categories_filter')) {
                    $table->boolean('show_categories_filter')->default(true);
                }
                
                if (!Schema::hasColumn('project_settings', 'show_view_all')) {
                    $table->boolean('show_view_all')->default(true);
                }
                
                if (!Schema::hasColumn('project_settings', 'view_all_text')) {
                    $table->string('view_all_text')->nullable();
                }
                
                if (!Schema::hasColumn('project_settings', 'view_all_url')) {
                    $table->string('view_all_url')->nullable();
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Sadece belirli sütunları geri alıyoruz
        if (Schema::hasTable('project_settings')) {
            Schema::table('project_settings', function (Blueprint $table) {
                $columns = [
                    'show_categories_filter',
                    'show_view_all',
                    'view_all_text',
                    'view_all_url'
                ];
                
                foreach ($columns as $column) {
                    if (Schema::hasColumn('project_settings', $column)) {
                        $table->dropColumn($column);
                    }
                }
            });
        }
    }
}; 