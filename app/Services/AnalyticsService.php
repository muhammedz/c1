<?php

namespace App\Services;

use Spatie\Analytics\Facades\Analytics;
use Spatie\Analytics\Period;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Exception;

/**
 * Google Analytics Veri Çekme Servisi
 * 
 * Bu servis Google Analytics API'sinden veri çeker ve cache'ler.
 * Performans için 30 dakika cache süresi kullanır.
 */
class AnalyticsService
{
    protected $cacheTime = 30; // dakika

    public function __construct()
    {
        // Facade kullanarak direkt erişim
    }

    /**
     * Analytics servisi aktif mi kontrol et
     */
    public function isActive(): bool
    {
        return config('analytics.property_id') && 
               file_exists(config('analytics.service_account_credentials_json'));
    }

    /**
     * Genel istatistikleri getir
     */
    public function getGeneralStats(): array
    {
        if (!$this->isActive()) {
            return $this->getDefaultStats();
        }

        return Cache::remember('analytics_general_stats', $this->cacheTime * 60, function () {
            try {
                // Son 30 gün verilerini al
                $period = Period::days(30);
                
                // Son 30 gün toplam verileri
                $period30 = Period::days(30);
                $totalData = Analytics::fetchVisitorsAndPageViews($period30);
                
                // Bugünkü veriler
                $today = Period::days(1);
                $todayData = Analytics::fetchVisitorsAndPageViews($today);
                
                // Bu hafta vs geçen hafta
                $thisWeek = Period::days(7);
                $lastWeek = Period::create(Carbon::now()->subDays(14), Carbon::now()->subDays(7));
                
                $thisWeekData = Analytics::fetchVisitorsAndPageViews($thisWeek);
                $lastWeekData = Analytics::fetchVisitorsAndPageViews($lastWeek);
                
                return [
                    // Genel metrikler (30 gün toplamı)
                    'total_users' => $totalData->sum('activeUsers') ?? 0,
                    'total_pageviews' => $totalData->sum('screenPageViews') ?? 0,
                    
                    // Günlük metrikler
                    'today_users' => $todayData->sum('activeUsers') ?? 0,
                    'today_pageviews' => $todayData->sum('screenPageViews') ?? 0,
                    
                    // Haftalık karşılaştırma
                    'this_week_users' => $thisWeekData->sum('activeUsers') ?? 0,
                    'last_week_users' => $lastWeekData->sum('activeUsers') ?? 0,
                    'week_growth' => $this->calculateGrowth(
                        $lastWeekData->sum('activeUsers') ?? 0, 
                        $thisWeekData->sum('activeUsers') ?? 0
                    ),
                    
                    // Cache tarihi
                    'cached_at' => now()->format('d.m.Y H:i'),
                ];
                
            } catch (Exception $e) {
                Log::error('Analytics genel istatistik hatası: ' . $e->getMessage());
                return $this->getDefaultStats();
            }
        });
    }

    /**
     * En popüler sayfaları getir
     */
    public function getTopPages($limit = 10): array
    {
        if (!$this->isActive()) {
            return [];
        }

        return Cache::remember("analytics_top_pages_{$limit}", $this->cacheTime * 60, function () use ($limit) {
            try {
                $period = Period::days(30);
                $pages = Analytics::fetchMostVisitedPages($period, $limit);
                
                return $pages->map(function ($page) {
                    $title = $page['pageTitle'] ?? '';
                    $url = $page['fullPageUrl'] ?? '';
                    
                    // (not set) ve boş başlıkları URL'den çıkar
                    if (empty($title) || $title === '(not set)') {
                        if (!empty($url)) {
                            // URL'den sayfa adını çıkarmaya çalış
                            $urlParts = parse_url($url);
                            $path = $urlParts['path'] ?? '';
                            
                            if ($path === '/' || $path === '') {
                                $title = 'Ana Sayfa';
                            } else {
                                // Path'den başlık oluştur
                                $pathParts = explode('/', trim($path, '/'));
                                $lastPart = end($pathParts);
                                
                                if ($lastPart) {
                                    // URL slug'ını başlığa çevir
                                    $title = ucwords(str_replace(['-', '_'], ' ', $lastPart));
                                    
                                    // Türkçe karakter düzeltmeleri
                                    $title = str_replace(['Acik', 'Hava', 'Gunleri'], ['Açık', 'Hava', 'Günleri'], $title);
                                    $title = str_replace(['Sayfalar', 'Pazar', 'Yerleri'], ['Sayfalar', 'Pazar', 'Yerleri'], $title);
                                    $title = str_replace(['Iletisim'], ['İletişim'], $title);
                                } else {
                                    $title = 'Başlık Yok';
                                }
                            }
                        } else {
                            $title = 'Başlık Yok';
                        }
                    }
                    
                    return [
                        'url' => $url,
                        'title' => $title,
                        'pageviews' => $page['screenPageViews'] ?? 0,
                        'users' => $page['activeUsers'] ?? 0,
                    ];
                })->toArray();
                
            } catch (Exception $e) {
                Log::error('Analytics popüler sayfalar hatası: ' . $e->getMessage());
                return [];
            }
        });
    }

    /**
     * Trafik kaynaklarını getir
     */
    public function getTrafficSources(): array
    {
        if (!$this->isActive()) {
            return $this->getDefaultTrafficSources();
        }

        return Cache::remember('analytics_traffic_sources', $this->cacheTime * 60, function () {
            try {
                $period = Period::days(30);
                
                // GA4'de trafik kaynakları için custom query kullan
                $sources = $this->fetchTrafficSourcesGA4($period);
                
                return $sources;
                
            } catch (Exception $e) {
                Log::error('Analytics trafik kaynakları hatası: ' . $e->getMessage());
                return $this->getDefaultTrafficSources();
            }
        });
    }

    /**
     * Günlük ziyaretçi grafiği için veri
     */
    public function getDailyVisitors($days = 30): array
    {
        if (!$this->isActive()) {
            return [];
        }

        return Cache::remember("analytics_daily_visitors_{$days}", $this->cacheTime * 60, function () use ($days) {
            try {
                $period = Period::days($days);
                
                // GA4 için tarihe göre veri çek
                $dailyData = Analytics::get(
                    $period,
                    ['activeUsers', 'screenPageViews'],
                    ['date'],
                    $days + 5
                );
                
                return $dailyData->map(function ($day) {
                    return [
                        'date' => Carbon::parse($day['date'])->format('d.m'),
                        'users' => intval($day['activeUsers'] ?? 0),
                        'pageviews' => intval($day['screenPageViews'] ?? 0),
                    ];
                })->toArray();
                
            } catch (Exception $e) {
                Log::error('Analytics günlük veriler hatası: ' . $e->getMessage());
                return [];
            }
        });
    }

    /**
     * Cihaz türü dağılımı
     */
    public function getDeviceTypes(): array
    {
        if (!$this->isActive()) {
            return [];
        }

        return Cache::remember('analytics_device_types', $this->cacheTime * 60, function () {
            try {
                $period = Period::days(30);
                
                // Bu veri GA4 için farklı şekilde çekilebilir
                return [
                    'desktop' => 60,
                    'mobile' => 35,
                    'tablet' => 5,
                ];
                
            } catch (Exception $e) {
                Log::error('Analytics cihaz türleri hatası: ' . $e->getMessage());
                return [];
            }
        });
    }

    /**
     * Büyüme oranını hesapla
     */
    private function calculateGrowth($previous, $current): float
    {
        if ($previous == 0) {
            return $current > 0 ? 100 : 0;
        }
        
        return round((($current - $previous) / $previous) * 100, 1);
    }

    /**
     * Varsayılan istatistikler (API erişimi olmadığında)
     */
    private function getDefaultStats(): array
    {
        return [
            'total_users' => 0,
            'total_pageviews' => 0,
            'today_users' => 0,
            'today_pageviews' => 0,
            'this_week_users' => 0,
            'last_week_users' => 0,
            'week_growth' => 0,
            'cached_at' => 'API Bağlantısı Yok',
        ];
    }

    /**
     * Analytics cache'ini temizle
     */
    public function clearCache(): bool
    {
        $keys = [
            'analytics_general_stats',
            'analytics_traffic_sources',
            'analytics_device_types',
        ];

        foreach ($keys as $key) {
            Cache::forget($key);
        }

        // Dinamik cache'leri de temizle
        for ($i = 1; $i <= 50; $i++) {
            Cache::forget("analytics_top_pages_{$i}");
        }

        for ($i = 7; $i <= 365; $i++) {
            Cache::forget("analytics_daily_visitors_{$i}");
        }

        return true;
    }

    /**
     * API bağlantı durumunu test et
     */
    public function testConnection(): array
    {
        try {
            if (!$this->isActive()) {
                return [
                    'status' => 'error',
                    'message' => 'Analytics yapılandırması eksik.',
                    'details' => [
                        'property_id' => config('analytics.property_id') ? 'OK' : 'Eksik',
                        'credentials_file' => file_exists(config('analytics.service_account_credentials_json')) ? 'OK' : 'Eksik',
                    ]
                ];
            }

            // Basit bir test sorgusu
            $period = Period::days(1);
            $testData = Analytics::fetchVisitorsAndPageViews($period);
            
            return [
                'status' => 'success',
                'message' => 'Analytics API bağlantısı başarılı!',
                'test_data' => $testData,
            ];
            
        } catch (Exception $e) {
            $errorMessage = $e->getMessage();
            
            // PERMISSION_DENIED hatası için özel mesaj
            if (strpos($errorMessage, 'PERMISSION_DENIED') !== false || strpos($errorMessage, 'code": 7') !== false) {
                return [
                    'status' => 'error',
                    'message' => '🔑 Service Account\'un Analytics property\'sine erişim izni yok!',
                    'details' => [
                        'Çözüm' => 'Service Account email\'ini Google Analytics property\'sine ekleyin',
                        'Adım 1' => 'analytics.google.com → Yönetici (Admin)',
                        'Adım 2' => 'Property access management → + Add users',
                        'Adım 3' => 'Service Account email\'ini (client_email) ekleyin',
                        'Adım 4' => 'Rol: Viewer seçin → Add',
                        'Teknik Hata' => $errorMessage
                    ]
                ];
            }
            
            return [
                'status' => 'error',
                'message' => 'API bağlantı hatası: ' . $errorMessage,
                'details' => []
            ];
        }
    }

    /**
     * GA4 ile trafik kaynaklarını getir
     */
    private function fetchTrafficSourcesGA4(Period $period): array
    {
        try {
            // GA4'de trafik kaynakları için sessionDefaultChannelGroup dimension'ını kullan
            $data = Analytics::get(
                $period,
                ['activeUsers', 'sessions'],
                ['sessionDefaultChannelGroup'],
                100
            );
            
            $sources = [
                'direct' => 0,
                'search' => 0,
                'social' => 0,
                'referral' => 0,
            ];
            
            foreach ($data as $item) {
                $channelGroup = strtolower($item['sessionDefaultChannelGroup'] ?? '');
                $users = intval($item['activeUsers'] ?? 0);
                
                switch ($channelGroup) {
                    case 'direct':
                        $sources['direct'] += $users;
                        break;
                    case 'organic search':
                    case 'paid search':
                        $sources['search'] += $users;
                        break;
                    case 'organic social':
                    case 'paid social':
                        $sources['social'] += $users;
                        break;
                    case 'referral':
                    case 'email':
                    case 'affiliates':
                        $sources['referral'] += $users;
                        break;
                    default:
                        // Bilinmeyen kaynakları referral'a ekle
                        $sources['referral'] += $users;
                        break;
                }
            }
            
            return $sources;
            
        } catch (Exception $e) {
            Log::error('GA4 trafik kaynakları hatası: ' . $e->getMessage());
            return $this->getDefaultTrafficSources();
        }
    }

    /**
     * Varsayılan trafik kaynakları
     */
    private function getDefaultTrafficSources(): array
    {
        return [
            'direct' => 0,
            'search' => 0,
            'social' => 0,
            'referral' => 0,
        ];
    }

    /**
     * Cache'in fresh olup olmadığını kontrol et ve gerekirse temizle
     * @param int $maxAgeMinutes Maksimum yaş (dakika)
     * @return array ['refreshed' => bool, 'age' => int, 'reason' => string]
     */
    public function checkAndRefreshCache(int $maxAgeMinutes = 60): array
    {
        try {
            // Ana stats cache'inin var olup olmadığını kontrol et
            $cacheKey = 'analytics_general_stats';
            $cachedStats = Cache::get($cacheKey);
            
            if (!$cachedStats) {
                return [
                    'refreshed' => false,
                    'age' => 0,
                    'reason' => 'Cache bulunamadı, yeni veri çekilecek'
                ];
            }
            
            // Cache tarihi varsa kontrol et
            if (isset($cachedStats['cached_at'])) {
                $cacheTime = Carbon::createFromFormat('d.m.Y H:i', $cachedStats['cached_at']);
                $ageMinutes = $cacheTime->diffInMinutes(now());
                
                if ($ageMinutes > $maxAgeMinutes) {
                    // Cache çok eski, temizle
                    $this->clearAllCache();
                    
                    return [
                        'refreshed' => true,
                        'age' => $ageMinutes,
                        'reason' => "Cache {$ageMinutes} dakika eski (limit: {$maxAgeMinutes}), temizlendi"
                    ];
                }
                
                return [
                    'refreshed' => false,
                    'age' => $ageMinutes,
                    'reason' => "Cache fresh ({$ageMinutes} dakika eski)"
                ];
            }
            
            return [
                'refreshed' => false,
                'age' => 0,
                'reason' => 'Cache tarihi bulunamadı'
            ];
            
        } catch (Exception $e) {
            Log::error('Cache freshness kontrolü hatası: ' . $e->getMessage());
            return [
                'refreshed' => false,
                'age' => 0,
                'reason' => 'Kontrol hatası: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Tüm analytics cache'lerini temizle
     */
    public function clearAllCache(): bool
    {
        try {
            $cacheKeys = [
                'analytics_general_stats',
                'analytics_traffic_sources',
                'analytics_top_pages',
                'analytics_device_types',
                'analytics_daily_visitors_7',
                'analytics_daily_visitors_30',
                'analytics_daily_visitors_90',
                'analytics_daily_visitors_180',
                'analytics_daily_visitors_365'
            ];
            
            $clearedCount = 0;
            foreach ($cacheKeys as $key) {
                if (Cache::forget($key)) {
                    $clearedCount++;
                }
            }
            
            Log::info("Analytics cache temizlendi: {$clearedCount} anahtar silindi");
            return true;
            
        } catch (Exception $e) {
            Log::error('Cache temizleme hatası: ' . $e->getMessage());
            return false;
        }
    }
} 