<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Services\AnalyticsService;
use Illuminate\Support\Facades\Log;

/**
 * Analytics Cache Güncelleme Job'u
 * 
 * Bu job analytics verilerini periyodik olarak çeker ve cache'ler.
 * Böylece kullanıcılar sayfayı açtığında API çağrısı yapmak zorunda kalmaz.
 */
class UpdateAnalyticsCache implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Job kaç kez denenmesi gerektiğini belirtir
     */
    public $tries = 3;

    /**
     * Job timeout süresi (saniye)
     */
    public $timeout = 300; // 5 dakika

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        // İsteğe bağlı: Specific queue'ya atamak için
        $this->onQueue('analytics');
    }

    /**
     * Execute the job.
     */
    public function handle(AnalyticsService $analyticsService): void
    {
        try {
            Log::info('Analytics cache güncelleme job\'u başlatıldı.');

            // Analytics servisinin aktif olup olmadığını kontrol et
            if (!$analyticsService->isActive()) {
                Log::warning('Analytics servisi aktif değil. Job sonlandırıldı.');
                return;
            }

            // Cache'i temizle
            $analyticsService->clearCache();
            Log::info('Analytics cache temizlendi.');

            // Temel verileri çekerek cache'le
            $stats = $analyticsService->getGeneralStats();
            Log::info('Genel istatistikler cache\'lendi.', ['users_count' => $stats['total_users'] ?? 0]);

            // En popüler sayfaları cache'le
            $topPages = $analyticsService->getTopPages(10);
            Log::info('Popüler sayfalar cache\'lendi.', ['pages_count' => count($topPages)]);

            // Günlük ziyaretçi verilerini cache'le
            $dailyData = $analyticsService->getDailyVisitors(30);
            Log::info('Günlük veriler cache\'lendi.', ['days_count' => count($dailyData)]);

            // Trafik kaynakları
            $trafficSources = $analyticsService->getTrafficSources();
            Log::info('Trafik kaynakları cache\'lendi.');

            // Cihaz türleri
            $deviceTypes = $analyticsService->getDeviceTypes();
            Log::info('Cihaz türleri cache\'lendi.');

            Log::info('Analytics cache güncelleme job\'u başarıyla tamamlandı.');

        } catch (\Exception $e) {
            Log::error('Analytics cache güncelleme hatası: ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString()
            ]);

            // Job'u yeniden dene
            $this->fail($e);
        }
    }

    /**
     * Job başarısız olduğunda çalışır
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('Analytics cache güncelleme job\'u başarısız oldu: ' . $exception->getMessage(), [
            'exception' => $exception
        ]);
    }
}
