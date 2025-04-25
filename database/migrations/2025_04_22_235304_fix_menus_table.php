<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Önce mevcut tabloyu yedekleyelim
        if (Schema::hasTable('menus')) {
            // Var olan menus tablosunun yapısını kontrol edelim
            $columns = Schema::getColumnListing('menus');

            // Eksik sütunlar varsa ekleyelim
            Schema::table('menus', function (Blueprint $table) use ($columns) {
                if (!in_array('type', $columns)) {
                    $table->string('type')->default('link')->nullable();
                }
                
                // Diğer eksik sütunları da kontrol edip ekleyelim
                if (!in_array('parent_id', $columns)) {
                    $table->unsignedBigInteger('parent_id')->nullable();
                }
                
                if (!in_array('is_mega', $columns)) {
                    $table->boolean('is_mega')->default(false);
                }
                
                if (!in_array('order', $columns)) {
                    $table->integer('order')->default(0);
                }
                
                if (!in_array('is_active', $columns)) {
                    $table->boolean('is_active')->default(true);
                }
            });
            
            // Yeni eklediğimiz 'type' sütununu güncelliyoruz
            DB::table('menus')->update(['type' => 'header']);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Bu migration'ı geri almak tehlikeli olabilir, 
        // bu yüzden herhangi bir şey yapmıyoruz
    }
};
