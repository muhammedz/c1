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
        // Öncelikle is_featured sütununu ekleyelim
        if (Schema::hasTable('news') && !Schema::hasColumn('news', 'is_featured')) {
            Schema::table('news', function (Blueprint $table) {
                $table->boolean('is_featured')->default(false)->after('is_headline');
            });
        }

        // Şimdi indeksleri kontrol edip eksik olanları ekleyelim
        if (Schema::hasTable('news')) {
            $indexes = $this->getTableIndexes('news');
            
            // Sırayla her indeksi kontrol edelim
            if (!in_array('news_is_headline_index', $indexes)) {
                DB::statement('ALTER TABLE news ADD INDEX news_is_headline_index (is_headline)');
            }
            
            if (!in_array('news_is_featured_index', $indexes)) {
                if (Schema::hasColumn('news', 'is_featured')) {
                    DB::statement('ALTER TABLE news ADD INDEX news_is_featured_index (is_featured)');
                }
            }
            
            if (!in_array('news_status_index', $indexes)) {
                DB::statement('ALTER TABLE news ADD INDEX news_status_index (status)');
            }
            
            if (!in_array('news_published_at_index', $indexes)) {
                DB::statement('ALTER TABLE news ADD INDEX news_published_at_index (published_at)');
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // İndeksleri siler
        // Hataya sebep olmaması için sadece varsa siliyoruz
        if (Schema::hasTable('news')) {
            $indexes = $this->getTableIndexes('news');
            
            if (in_array('news_is_headline_index', $indexes)) {
                DB::statement('ALTER TABLE news DROP INDEX news_is_headline_index');
            }
            
            if (in_array('news_is_featured_index', $indexes)) {
                DB::statement('ALTER TABLE news DROP INDEX news_is_featured_index');
            }
            
            if (in_array('news_status_index', $indexes)) {
                DB::statement('ALTER TABLE news DROP INDEX news_status_index');
            }
            
            if (in_array('news_published_at_index', $indexes)) {
                DB::statement('ALTER TABLE news DROP INDEX news_published_at_index');
            }
        }
    }
    
    /**
     * Bir tablonun tüm indekslerini getirir
     */
    private function getTableIndexes($tableName)
    {
        $indexes = [];
        $results = DB::select("SHOW INDEX FROM {$tableName}");
        
        foreach ($results as $result) {
            $indexes[] = $result->Key_name;
        }
        
        return array_unique($indexes);
    }
}; 