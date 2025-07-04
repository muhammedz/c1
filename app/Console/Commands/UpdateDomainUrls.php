<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class UpdateDomainUrls extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'domain:update-urls {--dry-run : Sadece rapor göster, güncelleme yapma} {--backup : Güncelleme öncesi backup al}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'cankaya.bel.tr URL\'lerini www.cankaya.bel.tr olarak günceller';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $isDryRun = $this->option('dry-run');
        $createBackup = $this->option('backup');
        
        if ($isDryRun) {
            $this->info('🔍 DRY RUN: Sadece analiz yapılıyor, güncelleme yapılmayacak...');
        } else {
            $this->info('🚀 URL güncellemesi başlatılıyor...');
            
            if ($createBackup) {
                $this->info('💾 Backup alınıyor...');
                $this->createBackup();
            }
        }

        // Güncellenecek tablolar ve sütunlar
        $updates = [
            // Ana content alanları ve resimler
            ['table' => 'pages', 'columns' => ['content', 'image', 'filemanagersystem_image', 'filemanagersystem_image_alt', 'filemanagersystem_image_title', 'filemanagersystem_gallery', 'gallery']],
            ['table' => 'news', 'columns' => ['content', 'image', 'filemanagersystem_image', 'filemanagersystem_image_alt', 'filemanagersystem_image_title', 'filemanagersystem_gallery', 'gallery']],
            ['table' => 'sliders', 'columns' => ['image', 'filemanagersystem_image']],
            ['table' => 'events', 'columns' => ['description', 'cover_image']],
            ['table' => 'projects', 'columns' => ['description', 'cover_image']],
            ['table' => 'mayor_content', 'columns' => ['description', 'image', 'filemanagersystem_image']],
            ['table' => 'corporate_categories', 'columns' => ['description', 'image', 'filemanagersystem_image']],
            ['table' => 'corporate_members', 'columns' => ['description', 'filemanagersystem_image']],
            ['table' => 'services', 'columns' => ['content', 'image', 'gallery']],
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
            
            // Öne çıkan hizmetler ve diğer URL alanları
            ['table' => 'featured_services', 'columns' => ['url']],
            ['table' => 'services', 'columns' => ['content', 'image', 'cta_url']],
            ['table' => 'search_quick_links', 'columns' => ['url']],
            ['table' => 'search_priority_links', 'columns' => ['url']],
            ['table' => 'service_settings', 'columns' => ['hero_image']],
            
            // Slider ve buton URL'leri
            ['table' => 'sliders', 'columns' => ['button_url', 'filemanagersystem_image']],
            ['table' => 'announcements', 'columns' => ['content', 'button_url']],
            
            // Event ve project alanları
            ['table' => 'events', 'columns' => ['description', 'cover_image', 'external_url', 'register_url']],
            ['table' => 'project_settings', 'columns' => ['view_all_url']],
            
            // Dosya yolları ve galeri
            ['table' => 'galleries', 'columns' => ['file_path']],
            ['table' => 'guide_place_images', 'columns' => ['image_path']],
            ['table' => 'event_images', 'columns' => ['image_path']],
            ['table' => 'project_images', 'columns' => ['image_path']],
            ['table' => 'project_gallery', 'columns' => ['image_path']],
            ['table' => 'news_documents', 'columns' => ['file_path']],
            ['table' => 'archive_documents', 'columns' => ['file_path']],
            ['table' => 'mudurluk_files', 'columns' => ['file_path']],
            ['table' => 'media_relations', 'columns' => ['file_path']],
            ['table' => 'media', 'columns' => ['file_path']],
            
            // Sosyal medya ve profil URL'leri
            ['table' => 'profile_settings', 'columns' => ['facebook_url', 'instagram_url', 'twitter_url', 'youtube_url']],
            ['table' => 'footer_settings', 'columns' => ['facebook_url', 'instagram_url', 'twitter_url', 'youtube_url', 'linkedin_url']],
            
            // Mobil app ve logo plan URL'leri
            ['table' => 'mobile_app_settings', 'columns' => ['link_card_1_url', 'link_card_2_url', 'link_card_3_url']],
            ['table' => 'logo_plan_settings', 'columns' => ['card1_url', 'card2_url', 'logo_url']],
            
            // Tender ve arşiv
            ['table' => 'tenders', 'columns' => ['description', 'content', 'document_url']],
            ['table' => 'archives', 'columns' => ['content', 'download_button_url']],
            
            // FileManager System
            ['table' => 'filemanagersystem_medias', 'columns' => ['url', 'path', 'webp_url']],
            
            // Activity logs
            ['table' => 'activity_logs', 'columns' => ['url']],
            
            // Çankaya Evleri
            ['table' => 'cankaya_houses', 'columns' => ['description', 'images', 'location_link']],
        ];

        $totalUpdated = 0;
        $summary = [];

        $this->info("\n📊 Analiz başlatılıyor...\n");

        foreach ($updates as $update) {
            $tableName = $update['table'];
            
            if (!Schema::hasTable($tableName)) {
                $this->warn("⚠️  Tablo bulunamadı: {$tableName}");
                continue;
            }

            foreach ($update['columns'] as $column) {
                if (!Schema::hasColumn($tableName, $column)) {
                    continue;
                }

                // Etkilenecek kayıtları say
                $count = DB::table($tableName)
                    ->where(function($query) use ($column) {
                        $query->where($column, 'LIKE', '%https://cankaya.bel.tr%')
                              ->orWhere($column, 'LIKE', '%http://cankaya.bel.tr%')
                              ->orWhere($column, 'LIKE', '%//cankaya.bel.tr%');
                    })
                    ->whereNotNull($column)
                    ->count();

                if ($count > 0) {
                    $summary[] = [
                        'table' => $tableName,
                        'column' => $column,
                        'count' => $count
                    ];
                    
                    $this->line("   📝 {$tableName}.{$column}: {$count} kayıt bulundu");
                    
                    if (!$isDryRun) {
                        $updated = 0;
                        
                        // HTTPS güncelleme
                        $httpsUpdated = DB::table($tableName)
                            ->where($column, 'LIKE', '%https://cankaya.bel.tr%')
                            ->update([
                                $column => DB::raw("REPLACE(`{$column}`, 'https://cankaya.bel.tr', 'https://www.cankaya.bel.tr')")
                            ]);

                        // HTTP güncelleme
                        $httpUpdated = DB::table($tableName)
                            ->where($column, 'LIKE', '%http://cankaya.bel.tr%')
                            ->update([
                                $column => DB::raw("REPLACE(`{$column}`, 'http://cankaya.bel.tr', 'https://www.cankaya.bel.tr')")
                            ]);

                        // Protocol-relative URL güncelleme (//cankaya.bel.tr)
                        $protocolRelativeUpdated = DB::table($tableName)
                            ->where($column, 'LIKE', '%//cankaya.bel.tr%')
                            ->where($column, 'NOT LIKE', '%://www.cankaya.bel.tr%')
                            ->update([
                                $column => DB::raw("REPLACE(`{$column}`, '//cankaya.bel.tr', '//www.cankaya.bel.tr')")
                            ]);

                        $updated = $httpsUpdated + $httpUpdated + $protocolRelativeUpdated;
                        $totalUpdated += $updated;
                        
                        $this->line("      ✅ {$updated} kayıt güncellendi");
                    }
                }
            }
        }

        $this->newLine();

        if ($isDryRun) {
            $this->info("📊 ANALIZ RAPORU:");
            if (empty($summary)) {
                $this->info("✅ Güncellenecek URL bulunamadı!");
            } else {
                $totalCount = array_sum(array_column($summary, 'count'));
                $this->table(
                    ['Tablo', 'Sütun', 'Kayıt Sayısı'],
                    array_map(function($item) {
                        return [$item['table'], $item['column'], $item['count']];
                    }, $summary)
                );
                $this->info("\n📊 Toplam etkilenecek kayıt: {$totalCount}");
                $this->info("💡 Gerçek güncelleme için: php artisan domain:update-urls");
                $this->info("💡 Backup ile güncelleme için: php artisan domain:update-urls --backup");
            }
        } else {
            if ($totalUpdated > 0) {
                $this->info("✅ Toplam {$totalUpdated} URL başarıyla güncellendi!");
                $this->info("🔗 Eski URL: https://cankaya.bel.tr");
                $this->info("🔗 Yeni URL: https://www.cankaya.bel.tr");
                
                // Doğrulama kontrolü öner
                $this->newLine();
                $this->info("🔍 Doğrulama için aşağıdaki komutları çalıştırabilirsiniz:");
                $this->line("   php artisan domain:verify-urls");
            } else {
                $this->info("ℹ️  Güncellenecek URL bulunamadı.");
            }
        }

        return 0;
    }

    /**
     * Basit backup oluştur
     */
    private function createBackup()
    {
        $timestamp = now()->format('Y_m_d_H_i_s');
        $backupFile = "backup_domain_update_{$timestamp}.sql";
        
        try {
            $database = config('database.connections.mysql.database');
            $username = config('database.connections.mysql.username');
            $password = config('database.connections.mysql.password');
            $host = config('database.connections.mysql.host');
            
            $command = "mysqldump -h {$host} -u {$username} -p{$password} {$database} > storage/app/{$backupFile}";
            
            if (exec($command) !== false) {
                $this->info("💾 Backup oluşturuldu: storage/app/{$backupFile}");
            } else {
                $this->warn("⚠️  Backup oluşturulamadı. Manuel backup almanız önerilir.");
            }
        } catch (\Exception $e) {
            $this->warn("⚠️  Backup hatası: " . $e->getMessage());
        }
    }
} 