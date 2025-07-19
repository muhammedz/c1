<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AnalyticsService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

/**
 * Admin Panel Analytics Controller
 * 
 * Google Analytics verilerini admin panelinde gösterir.
 * API kurulum rehberi ve istatistik sayfalarını yönetir.
 */
class AnalyticsController extends Controller
{
    protected $analyticsService;

    public function __construct(AnalyticsService $analyticsService)
    {
        $this->analyticsService = $analyticsService;
    }

    /**
     * Ana istatistikler sayfası
     */
    public function index(Request $request)
    {
        // Servisin aktif olup olmadığını kontrol et
        $isActive = $this->analyticsService->isActive();
        
        if (!$isActive) {
            // API kurulumu gerekli - kurulum sayfasına yönlendir
            return view('admin.analytics.setup', [
                'connection_status' => $this->analyticsService->testConnection()
            ]);
        }

        // Cache freshness kontrolü - ilk giriş veya refresh parametresi varsa
        $forceRefresh = $request->has('refresh');
        $cacheCheck = $this->analyticsService->checkAndRefreshCache(60); // 60 dakika limit
        
        // Eğer cache temizlendi ise sayfa yenileme ile tekrar yönlendir
        if ($cacheCheck['refreshed'] && !$forceRefresh) {
            return redirect()->route('admin.analytics.index', ['refresh' => '1'])
                           ->with('cache_refreshed', $cacheCheck['reason']);
        }

        // İstatistikleri getir
        $stats = $this->analyticsService->getGeneralStats();
        $topPages = $this->analyticsService->getTopPages(10);
        $trafficSources = $this->analyticsService->getTrafficSources();
        $deviceTypes = $this->analyticsService->getDeviceTypes();
        $dailyVisitors = $this->analyticsService->getDailyVisitors(30);

        return view('admin.analytics.index', compact(
            'stats',
            'topPages', 
            'trafficSources',
            'deviceTypes',
            'dailyVisitors',
            'cacheCheck'
        ));
    }

    /**
     * API kurulum rehberi sayfası
     */
    public function setup()
    {
        try {
            $connectionStatus = $this->analyticsService->testConnection();
        } catch (\Exception $e) {
            // Eğer servis hatası varsa varsayılan durum
            $connectionStatus = [
                'status' => 'error',
                'message' => 'Servis hatası: ' . $e->getMessage(),
                'details' => []
            ];
        }
        
        return view('admin.analytics.setup', compact('connectionStatus'));
    }

    /**
     * API bağlantısını test et
     */
    public function testConnection(): JsonResponse
    {
        $result = $this->analyticsService->testConnection();
        
        return response()->json($result);
    }

    /**
     * Analytics cache'ini temizle
     */
    public function clearCache(): JsonResponse
    {
        try {
            $success = $this->analyticsService->clearAllCache();
            
            if ($success) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Analytics cache başarıyla temizlendi!'
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Cache temizlenirken hata oluştu!'
                ], 500);
            }
            
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Cache temizleme hatası: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * AJAX ile günlük ziyaretçi verilerini getir
     */
    public function getDailyVisitorsData(Request $request): JsonResponse
    {
        $days = $request->get('days', 30);
        $days = min(max($days, 7), 365); // 7-365 gün arası

        $data = $this->analyticsService->getDailyVisitors($days);
        
        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }

    /**
     * AJAX ile en popüler sayfaları getir
     */
    public function getTopPagesData(Request $request): JsonResponse
    {
        $limit = $request->get('limit', 10);
        $limit = min(max($limit, 5), 50); // 5-50 arası

        $data = $this->analyticsService->getTopPages($limit);
        
        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }

    /**
     * Detaylı raporlar sayfası
     */
    public function reports()
    {
        if (!$this->analyticsService->isActive()) {
            return redirect()->route('admin.analytics.setup')
                           ->with('error', 'Önce Google Analytics API kurulumunu tamamlayın.');
        }

        // Son 6 ay verilerini al
        $monthlyStats = $this->analyticsService->getDailyVisitors(180);
        $topPages = $this->analyticsService->getTopPages(25);
        $stats = $this->analyticsService->getGeneralStats();

        return view('admin.analytics.reports', compact(
            'monthlyStats',
            'topPages',
            'stats'
        ));
    }

    /**
     * Widget verilerini JSON olarak döndür (Dashboard için)
     */
    public function getWidgetData(): JsonResponse
    {
        if (!$this->analyticsService->isActive()) {
            return response()->json([
                'status' => 'inactive',
                'message' => 'Google Analytics yapılandırması gerekli'
            ]);
        }

        $stats = $this->analyticsService->getGeneralStats();
        
        return response()->json([
            'status' => 'success',
            'stats' => $stats,
            'data' => [
                'today_users' => $stats['today_users'],
                'today_pageviews' => $stats['today_pageviews'],
                'week_growth' => $stats['week_growth'],
                'cached_at' => $stats['cached_at']
            ]
        ]);
    }

    /**
     * Ayarlar sayfası
     */
    public function settings()
    {
        $connectionStatus = $this->analyticsService->testConnection();
        
        // Config değerlerini al
        $currentConfig = [
            'property_id' => config('analytics.property_id'),
            'cache_lifetime' => config('analytics.cache_lifetime_in_minutes'),
            'credentials_path' => config('analytics.service_account_credentials_json'),
            'credentials_exists' => file_exists(config('analytics.service_account_credentials_json'))
        ];

        return view('admin.analytics.settings', compact(
            'connectionStatus',
            'currentConfig'
        ));
    }

    /**
     * JSON credentials içeriğini kaydet
     */
    public function saveCredentials(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'json_content' => 'required|string'
            ]);

            $jsonContent = trim($request->json_content);
            
            // JSON dosyasının geçerli olup olmadığını kontrol et
            $decoded = json_decode($jsonContent, true);
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Geçersiz JSON formatı: ' . json_last_error_msg()
                ], 400);
            }

            // OAuth2 client JSON kontrolü (yaygın hata)
            if (isset($decoded['web']) && isset($decoded['web']['client_id'])) {
                return response()->json([
                    'status' => 'error',
                    'message' => '❌ Bu OAuth 2.0 İstemci Kimliği JSON\'u! Google Cloud Console\'da "Service Account" seçmelisiniz, "OAuth 2.0 İstemci Kimliği" değil!'
                ], 400);
            }

            // Gerekli alanların olup olmadığını kontrol et
            $requiredFields = ['type', 'project_id', 'private_key', 'client_email'];
            foreach ($requiredFields as $field) {
                if (!isset($decoded[$field])) {
                    return response()->json([
                        'status' => 'error',
                        'message' => "JSON içeriğinde gerekli alan eksik: $field. Bu bir Service Account JSON dosyası olmalı!"
                    ], 400);
                }
            }

            // Google Service Account olduğunu kontrol et
            if ($decoded['type'] !== 'service_account') {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Bu bir Service Account JSON dosyası değil. Google Cloud Console\'da "Service Account" oluşturun!'
                ], 400);
            }

            // Hedef klasörü oluştur
            $uploadPath = public_path('uploads/analytics');
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }

            // JSON'ı dosyaya kaydet
            $filename = 'google-analytics-credentials.json';
            $filePath = $uploadPath . '/' . $filename;
            
            // JSON'ı güzel formatlayarak kaydet
            $prettyJson = json_encode($decoded, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
            file_put_contents($filePath, $prettyJson);

            // Dosya izinlerini ayarla
            chmod($filePath, 0644);

            return response()->json([
                'status' => 'success',
                'message' => 'Service Account credentials başarıyla kaydedildi!',
                'details' => [
                    'project_id' => $decoded['project_id'],
                    'client_email' => $decoded['client_email'],
                    'file_path' => $filePath
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Kaydetme hatası: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Trafik kaynaklarını getir (AJAX)
     */
    public function getTrafficSources(): JsonResponse
    {
        $trafficSources = $this->analyticsService->getTrafficSources();
        
        return response()->json($trafficSources);
    }
} 