<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class VerifyDomainUrls extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'domain:verify-urls {--show-samples : Örnek URL\'leri göster}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Veritabanında kalan eski cankaya.bel.tr URL\'lerini kontrol eder';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $showSamples = $this->option('show-samples');
        
        $this->info('🔍 Domain URL doğrulaması başlatılıyor...');
        $this->newLine();

        // Kontrol edilecek tablolar ve sütunlar
        $tables = [
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

        $foundIssues = [];
        $totalOldUrls = 0;
        $totalNewUrls = 0;

        foreach ($tables as $tableInfo) {
            $tableName = $tableInfo['table'];
            
            if (!Schema::hasTable($tableName)) {
                continue;
            }

            foreach ($tableInfo['columns'] as $column) {
                if (!Schema::hasColumn($tableName, $column)) {
                    continue;
                }

                // Eski URL'leri kontrol et
                $oldUrlCount = DB::table($tableName)
                    ->where(function($query) use ($column) {
                        $query->where($column, 'LIKE', '%https://cankaya.bel.tr%')
                              ->orWhere($column, 'LIKE', '%http://cankaya.bel.tr%')
                              ->orWhere($column, 'LIKE', '%//cankaya.bel.tr%');
                    })
                    ->whereNotNull($column)
                    ->count();

                // Yeni URL'leri kontrol et
                $newUrlCount = DB::table($tableName)
                    ->where(function($query) use ($column) {
                        $query->where($column, 'LIKE', '%https://www.cankaya.bel.tr%')
                              ->orWhere($column, 'LIKE', '%http://www.cankaya.bel.tr%')
                              ->orWhere($column, 'LIKE', '%//www.cankaya.bel.tr%');
                    })
                    ->whereNotNull($column)
                    ->count();

                if ($oldUrlCount > 0) {
                    $foundIssues[] = [
                        'table' => $tableName,
                        'column' => $column,
                        'old_count' => $oldUrlCount,
                        'new_count' => $newUrlCount
                    ];
                }

                $totalOldUrls += $oldUrlCount;
                $totalNewUrls += $newUrlCount;
            }
        }

        // Sonuçları göster
        if (empty($foundIssues)) {
            $this->info('✅ Mükemmel! Veritabanında eski URL bulunamadı.');
            if ($totalNewUrls > 0) {
                $this->info("🔗 Toplam {$totalNewUrls} yeni format URL bulundu.");
            }
        } else {
            $this->warn("⚠️  {$totalOldUrls} eski URL bulundu!");
            $this->newLine();
            
            $this->table(
                ['Tablo', 'Sütun', 'Eski URL', 'Yeni URL'],
                array_map(function($issue) {
                    return [
                        $issue['table'],
                        $issue['column'],
                        $issue['old_count'],
                        $issue['new_count']
                    ];
                }, $foundIssues)
            );

            if ($showSamples) {
                $this->newLine();
                $this->info('📋 Örnek eski URL\'ler:');
                
                foreach ($foundIssues as $issue) {
                    if ($issue['old_count'] > 0) {
                        $samples = DB::table($issue['table'])
                            ->select(['id', $issue['column']])
                            ->where(function($query) use ($issue) {
                                $column = $issue['column'];
                                $query->where($column, 'LIKE', '%https://cankaya.bel.tr%')
                                      ->orWhere($column, 'LIKE', '%http://cankaya.bel.tr%')
                                      ->orWhere($column, 'LIKE', '%//cankaya.bel.tr%');
                            })
                            ->whereNotNull($issue['column'])
                            ->limit(3)
                            ->get();

                        foreach ($samples as $sample) {
                            $content = $sample->{$issue['column']};
                            $preview = strlen($content) > 100 ? substr($content, 0, 100) . '...' : $content;
                            $this->line("   {$issue['table']}.{$issue['column']} (ID: {$sample->id}): {$preview}");
                        }
                    }
                }
            }

            $this->newLine();
            $this->info('💡 Kalan URL\'leri güncellemek için:');
            $this->line('   php artisan domain:update-urls');
        }

        // File sistem kontrolü
        $this->newLine();
        $this->info('📁 Hardcoded URL kontrolleri:');
        $this->checkHardcodedUrls();

        return $totalOldUrls > 0 ? 1 : 0;
    }

    /**
     * Dosyalarda hardcoded URL'leri kontrol et
     */
    private function checkHardcodedUrls()
    {
        $filesToCheck = [
            'resources/views/frontend/corporate/baskan.blade.php',
            'resources/views/front/sections/slider.blade.php',
            'resources/views/auth/login.blade.php',
        ];

        $foundHardcoded = [];

        foreach ($filesToCheck as $file) {
            if (file_exists(base_path($file))) {
                $content = file_get_contents(base_path($file));
                
                // Eski URL'leri ara
                $oldUrlMatches = [];
                preg_match_all('/https?:\/\/cankaya\.bel\.tr[^\s"\'<>]*/', $content, $oldUrlMatches);
                preg_match_all('/\/\/cankaya\.bel\.tr[^\s"\'<>]*/', $content, $protocolRelativeMatches);
                
                $totalMatches = count($oldUrlMatches[0]) + count($protocolRelativeMatches[0]);
                
                if ($totalMatches > 0) {
                    $foundHardcoded[] = [
                        'file' => $file,
                        'count' => $totalMatches
                    ];
                }
            }
        }

        if (empty($foundHardcoded)) {
            $this->info('   ✅ Dosyalarda eski URL bulunamadı.');
        } else {
            foreach ($foundHardcoded as $item) {
                $this->warn("   ⚠️  {$item['file']}: {$item['count']} eski URL");
            }
        }
    }
} 