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
        // URL güncellemesi yapılacak tablolar ve sütunlar
        $updates = [
            // Ana content alanları
            ['table' => 'pages', 'columns' => ['content', 'image', 'filemanagersystem_image']],
            ['table' => 'news', 'columns' => ['content', 'image', 'filemanagersystem_image']],
            ['table' => 'sliders', 'columns' => ['filemanagersystem_image']],
            ['table' => 'events', 'columns' => ['description', 'cover_image']],
            ['table' => 'projects', 'columns' => ['description', 'cover_image']],
            ['table' => 'mayor_content', 'columns' => ['description', 'filemanagersystem_image']],
            ['table' => 'corporate_categories', 'columns' => ['description', 'filemanagersystem_image']],
            ['table' => 'corporate_members', 'columns' => ['description', 'filemanagersystem_image']],
            ['table' => 'services', 'columns' => ['content']],
            ['table' => 'announcements', 'columns' => ['content']],
            ['table' => 'tenders', 'columns' => ['description', 'content']],
            ['table' => 'archives', 'columns' => ['content']],
            ['table' => 'guide_places', 'columns' => ['content']],
            ['table' => 'mudurlukler', 'columns' => ['content']],
            // FileManagerSystem
            ['table' => 'filemanagersystem_medias', 'columns' => ['url', 'path']],
            
            // Menü tabloları
            ['table' => 'menus', 'columns' => ['url']],
            ['table' => 'menu_items', 'columns' => ['url']],
            ['table' => 'menu_system_items', 'columns' => ['url']],
            ['table' => 'menu_categories', 'columns' => ['url']],
            ['table' => 'menusystem', 'columns' => ['url']],
            ['table' => 'header_menu_items', 'columns' => ['url']],
            ['table' => 'header_mega_menu_items', 'columns' => ['url']],
            ['table' => 'footer_menu_links', 'columns' => ['url']],
            ['table' => 'quick_menu_items', 'columns' => ['url']],
            ['table' => 'menu_tags', 'columns' => ['url']],
            ['table' => 'menu_cards', 'columns' => ['url']],
            ['table' => 'menu_descriptions', 'columns' => ['link_url']],
            
            // Çankaya Evleri
            ['table' => 'cankaya_houses', 'columns' => ['description', 'images', 'location_link']],
        ];

        echo "🚀 Domain URL güncellemesi başlatılıyor...\n";
        $totalUpdated = 0;

        foreach ($updates as $update) {
            $tableName = $update['table'];
            
            // Tablo var mı kontrol et
            if (!Schema::hasTable($tableName)) {
                echo "⚠️  Tablo bulunamadı: {$tableName}\n";
                continue;
            }

            foreach ($update['columns'] as $column) {
                // Sütun var mı kontrol et
                if (!Schema::hasColumn($tableName, $column)) {
                    continue;
                }

                // Etkilenecek kayıt sayısını kontrol et
                $count = DB::table($tableName)
                    ->where(function($query) use ($column) {
                        $query->where($column, 'LIKE', '%https://cankaya.bel.tr%')
                              ->orWhere($column, 'LIKE', '%http://cankaya.bel.tr%')
                              ->orWhere($column, 'LIKE', '%//cankaya.bel.tr%');
                    })
                    ->whereNotNull($column)
                    ->count();

                if ($count > 0) {
                    echo "📝 {$tableName}.{$column}: {$count} kayıt güncelleniyor...\n";

                    // HTTPS URL'leri güncelle
                    $httpsUpdated = DB::table($tableName)
                        ->where($column, 'LIKE', '%https://cankaya.bel.tr%')
                        ->update([
                            $column => DB::raw("REPLACE(`{$column}`, 'https://cankaya.bel.tr', 'https://www.cankaya.bel.tr')")
                        ]);

                    // HTTP URL'leri güncelle
                    $httpUpdated = DB::table($tableName)
                        ->where($column, 'LIKE', '%http://cankaya.bel.tr%')
                        ->update([
                            $column => DB::raw("REPLACE(`{$column}`, 'http://cankaya.bel.tr', 'https://www.cankaya.bel.tr')")
                        ]);

                    // Protocol-relative URL'leri güncelle (//cankaya.bel.tr)
                    $protocolRelativeUpdated = DB::table($tableName)
                        ->where($column, 'LIKE', '%//cankaya.bel.tr%')
                        ->where($column, 'NOT LIKE', '%://www.cankaya.bel.tr%')
                        ->update([
                            $column => DB::raw("REPLACE(`{$column}`, '//cankaya.bel.tr', '//www.cankaya.bel.tr')")
                        ]);

                    $updated = $httpsUpdated + $httpUpdated + $protocolRelativeUpdated;
                    $totalUpdated += $updated;
                    
                    echo "   ✅ {$updated} kayıt güncellendi\n";
                }
            }
        }

        echo "🎉 Toplam {$totalUpdated} URL başarıyla güncellendi!\n";
        echo "🔗 Eski URL: https://cankaya.bel.tr → Yeni URL: https://www.cankaya.bel.tr\n";
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Geri alma işlemi - ihtiyaç halinde www. kısmını kaldır
        $updates = [
            ['table' => 'pages', 'columns' => ['content', 'image', 'filemanagersystem_image']],
            ['table' => 'news', 'columns' => ['content', 'image', 'filemanagersystem_image']],
            ['table' => 'sliders', 'columns' => ['filemanagersystem_image']],
            ['table' => 'events', 'columns' => ['description', 'cover_image']],
            ['table' => 'projects', 'columns' => ['description', 'cover_image']],
            ['table' => 'mayor_content', 'columns' => ['description', 'filemanagersystem_image']],
            ['table' => 'corporate_categories', 'columns' => ['description', 'filemanagersystem_image']],
            ['table' => 'corporate_members', 'columns' => ['description', 'filemanagersystem_image']],
            ['table' => 'services', 'columns' => ['content']],
            ['table' => 'announcements', 'columns' => ['content']],
            ['table' => 'tenders', 'columns' => ['description', 'content']],
            ['table' => 'archives', 'columns' => ['content']],
            ['table' => 'guide_places', 'columns' => ['content']],
            ['table' => 'mudurlukler', 'columns' => ['content']],
            ['table' => 'filemanagersystem_medias', 'columns' => ['url', 'path']],
            
            // Menü tabloları
            ['table' => 'menus', 'columns' => ['url']],
            ['table' => 'menu_items', 'columns' => ['url']],
            ['table' => 'menu_system_items', 'columns' => ['url']],
            ['table' => 'menu_categories', 'columns' => ['url']],
            ['table' => 'menusystem', 'columns' => ['url']],
            ['table' => 'header_menu_items', 'columns' => ['url']],
            ['table' => 'header_mega_menu_items', 'columns' => ['url']],
            ['table' => 'footer_menu_links', 'columns' => ['url']],
            ['table' => 'quick_menu_items', 'columns' => ['url']],
            ['table' => 'menu_tags', 'columns' => ['url']],
            ['table' => 'menu_cards', 'columns' => ['url']],
            ['table' => 'menu_descriptions', 'columns' => ['link_url']],
            
            // Çankaya Evleri
            ['table' => 'cankaya_houses', 'columns' => ['description', 'images', 'location_link']],
        ];

        echo "↩️  Domain URL geri alma işlemi başlatılıyor...\n";

        foreach ($updates as $update) {
            $tableName = $update['table'];
            
            if (!Schema::hasTable($tableName)) {
                continue;
            }

            foreach ($update['columns'] as $column) {
                if (!Schema::hasColumn($tableName, $column)) {
                    continue;
                }

                // www.cankaya.bel.tr'yi cankaya.bel.tr'ye çevir
                DB::table($tableName)
                    ->where($column, 'LIKE', '%https://www.cankaya.bel.tr%')
                    ->update([
                        $column => DB::raw("REPLACE(`{$column}`, 'https://www.cankaya.bel.tr', 'https://cankaya.bel.tr')")
                    ]);

                DB::table($tableName)
                    ->where($column, 'LIKE', '%//www.cankaya.bel.tr%')
                    ->update([
                        $column => DB::raw("REPLACE(`{$column}`, '//www.cankaya.bel.tr', '//cankaya.bel.tr')")
                    ]);
            }
        }

        echo "✅ Geri alma işlemi tamamlandı\n";
    }
};
