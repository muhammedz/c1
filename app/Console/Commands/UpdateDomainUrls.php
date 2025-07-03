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