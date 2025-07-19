<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\UpdateAnalyticsCache;

/**
 * Analytics Cache Güncelleme Command'ı
 * 
 * Bu command analytics cache'ini güncellemek için job'u tetikler.
 * Cron job veya manual olarak çalıştırılabilir.
 */
class UpdateAnalyticsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'analytics:update-cache {--force : Cache güncellemeyi zorla}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Google Analytics verilerini çeker ve cache\'ler';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $force = $this->option('force');
        
        $this->info('Analytics cache güncelleme başlatılıyor...');
        
        if ($force) {
            $this->warn('Zorla güncelleme modu aktif.');
        }
        
        try {
            // Job'u queue'ya ekle
            UpdateAnalyticsCache::dispatch();
            
            $this->info('✅ Analytics cache güncelleme job\'u başarıyla kuyruğa eklendi.');
            $this->line('');
            $this->comment('📊 Veriler yakında güncellenecek...');
            $this->comment('📁 Cache konumu: ' . config('cache.default'));
            $this->comment('⏱️  Cache süresi: ' . config('analytics.cache_lifetime_in_minutes') . ' dakika');
            
            // Queue çalışıp çalışmadığını kontrol et
            if (config('queue.default') === 'sync') {
                $this->warn('⚠️  Queue driver "sync" olarak ayarlanmış. Job anında çalıştırılacak.');
            } else {
                $this->info('🔄 Queue worker çalıştığından emin olun: php artisan queue:work');
            }
            
            return Command::SUCCESS;
            
        } catch (\Exception $e) {
            $this->error('❌ Analytics cache güncelleme hatası: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
