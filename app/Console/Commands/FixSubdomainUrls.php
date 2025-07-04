<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class FixSubdomainUrls extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'domain:fix-subdomains {--dry-run : Sadece rapor göster, güncelleme yapma}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Subdomain\'lerdeki yanlış www formatını düzeltir (kentrehberi.www.cankaya.bel.tr -> kentrehberi.cankaya.bel.tr)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $isDryRun = $this->option('dry-run');
        
        if ($isDryRun) {
            $this->info('🔍 DRY RUN: Sadece analiz yapılıyor, güncelleme yapılmayacak...');
        } else {
            $this->info('🚀 Subdomain URL düzeltmesi başlatılıyor...');
        }

        // Düzeltilecek subdomain'ler
        $subdomains = [
            'kentrehberi',
            'kultursanat', 
            'dijital',
            'e-belediye',
            'online',
            'portal'
        ];

        // Kontrol edilecek tablolar ve sütunlar
        $updates = [
            ['table' => 'cankaya_houses', 'columns' => ['description', 'location_link']],
            ['table' => 'pages', 'columns' => ['content']],
            ['table' => 'news', 'columns' => ['content']],
            ['table' => 'projects', 'columns' => ['description']],
            ['table' => 'services', 'columns' => ['content']],
            ['table' => 'announcements', 'columns' => ['content']],
            ['table' => 'tenders', 'columns' => ['description', 'content']],
            ['table' => 'archives', 'columns' => ['content']],
            ['table' => 'guide_places', 'columns' => ['content']],
            ['table' => 'mudurlukler', 'columns' => ['content']],
        ];

        $totalUpdated = 0;
        $summary = [];

        $this->info("\n📊 Subdomain analizi başlatılıyor...\n");

        foreach ($subdomains as $subdomain) {
            $wrongPattern = "{$subdomain}.www.cankaya.bel.tr";
            $correctPattern = "{$subdomain}.cankaya.bel.tr";
            
            $this->line("🔍 {$wrongPattern} kontrol ediliyor...");

            foreach ($updates as $update) {
                $tableName = $update['table'];
                
                if (!Schema::hasTable($tableName)) {
                    continue;
                }

                foreach ($update['columns'] as $column) {
                    if (!Schema::hasColumn($tableName, $column)) {
                        continue;
                    }

                    // Etkilenecek kayıtları say
                    $count = DB::table($tableName)
                        ->where($column, 'LIKE', "%{$wrongPattern}%")
                        ->whereNotNull($column)
                        ->count();

                    if ($count > 0) {
                        $summary[] = [
                            'subdomain' => $subdomain,
                            'table' => $tableName,
                            'column' => $column,
                            'count' => $count,
                            'wrong' => $wrongPattern,
                            'correct' => $correctPattern
                        ];
                        
                        $this->line("   📝 {$tableName}.{$column}: {$count} kayıt bulundu");
                        
                        if (!$isDryRun) {
                            $updated = DB::table($tableName)
                                ->where($column, 'LIKE', "%{$wrongPattern}%")
                                ->update([
                                    $column => DB::raw("REPLACE(`{$column}`, '{$wrongPattern}', '{$correctPattern}')")
                                ]);

                            $totalUpdated += $updated;
                            $this->line("      ✅ {$updated} kayıt düzeltildi");
                        }
                    }
                }
            }
        }

        $this->newLine();

        if ($isDryRun) {
            $this->info("📊 SUBDOMAIN ANALIZ RAPORU:");
            if (empty($summary)) {
                $this->info("✅ Düzeltilecek subdomain URL'si bulunamadı!");
            } else {
                $totalCount = array_sum(array_column($summary, 'count'));
                $this->table(
                    ['Subdomain', 'Tablo', 'Sütun', 'Kayıt Sayısı', 'Yanlış Format', 'Doğru Format'],
                    array_map(function($item) {
                        return [
                            $item['subdomain'], 
                            $item['table'], 
                            $item['column'], 
                            $item['count'],
                            $item['wrong'],
                            $item['correct']
                        ];
                    }, $summary)
                );
                $this->info("\n📊 Toplam etkilenecek kayıt: {$totalCount}");
                $this->info("💡 Gerçek düzeltme için: php artisan domain:fix-subdomains");
            }
        } else {
            if ($totalUpdated > 0) {
                $this->info("✅ Toplam {$totalUpdated} subdomain URL'si başarıyla düzeltildi!");
                $this->info("🔗 Yanlış format: subdomain.www.cankaya.bel.tr");
                $this->info("🔗 Doğru format: subdomain.cankaya.bel.tr");
            } else {
                $this->info("ℹ️  Düzeltilecek subdomain URL'si bulunamadı.");
            }
        }

        return 0;
    }
} 