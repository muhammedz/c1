<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;
use DOMDocument;
use DOMXPath;

class PharmacyController extends Controller
{
    private array $headers;
    private string $cookieString;
    private string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = 'https://www.turkiye.gov.tr/saglik-titck-nobetci-eczane-sorgulama';
        $this->headers = [
            'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7',
            'Accept-Language' => 'tr-TR,tr;q=0.9,en;q=0.8',
            'Cache-Control' => 'max-age=0',
            'Connection' => 'keep-alive',
            'User-Agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/132.0.0.0 Safari/537.36',
            'Referer' => 'https://www.turkiye.gov.tr/',
        ];
        $this->cookieString = 'language=tr_TR.UTF-8; w3p=1943251136.20480.0000; _uid=1740222152-6371b0c0-9419-485e-abf4-f1a4d9de5c3b; ridbb=WyI4MjE2YjU0YWRkN2YxOTkwNDU2NmZjMzgzZTIzZGRmZGQ1MTVmYmIxZTIiXQ%3D%3D; TURKIYESESSIONID=ppvh79th8g07afbs5f9geuvefd; TS01c2accc=015c1cbb6d1a639504e1b29b05b98286d479274aaf94c82d859307282d1c86808d72742a98623567bf8f57c330e2ce30a706ea5e64; _lastptts=1740223200';
    }

    /**
     * Nöbetçi eczaneler sayfasını göster
     */
    public function index(Request $request)
    {
        $plateCode = $request->get('plateCode', '06');
        $date = $request->get('date', date('d/m/Y'));
        $district = $request->get('district', 'ÇANKAYA');
        
        $pharmacies = [];
        $error = null;
        $loading = false;
        $isFromCache = false;
        $debugInfo = [];

        // Form gönderilmişse veya ilk yüklemede eczaneleri getir
        if ($request->has('search') || !$request->hasAny(['plateCode', 'date', 'district', 'search'])) {
            
            try {
                // Önce cache'de veri var mı kontrol et
                $cacheKey = "pharmacy_data_{$plateCode}_{$date}_{$district}";
                $globalCacheKey = "pharmacy_global_{$plateCode}_{$date}"; 
                
                $cachedData = Cache::get($cacheKey);
                $globalCachedData = Cache::get($globalCacheKey);
                
                // Rate limiting kontrolü
                $rateLimitKey = 'pharmacy_request_' . $request->ip();
                $isRateLimited = false;
                $timeLeft = 0;
                
                if (Cache::has($rateLimitKey)) {
                    $timeLeft = 4 - (time() - Cache::get($rateLimitKey));
                    if ($timeLeft > 0) {
                        $isRateLimited = true;
                    }
                }

                // Önce cache'den dene
                if ($cachedData && is_array($cachedData) && isset($cachedData['data'])) {
                    $pharmacies = $cachedData['data'];
                    $isFromCache = true;
                    $debugInfo[] = "Cache tarihi: " . ($cachedData['meta']['cached_at'] ?? 'bilinmiyor');
                } elseif ($globalCachedData && is_array($globalCachedData) && isset($globalCachedData['data'])) {
                    // Global cache'den filtrele
                    $allPharmacies = $globalCachedData['data'];
                    $pharmacies = array_filter($allPharmacies, function($pharmacy) use ($district) {
                        return $this->normalizeDistrict($pharmacy['district']) === $this->normalizeDistrict($district);
                    });
                    $pharmacies = array_values($pharmacies);
                    $isFromCache = true;
                    $debugInfo[] = "Cache tarihi: " . ($globalCachedData['meta']['cached_at'] ?? 'bilinmiyor');
                } else {
                    // Cache'de veri yok, API'den çek
                    if ($isRateLimited) {
                        $error = "Çok sık istek gönderdiniz. Lütfen {$timeLeft} saniye sonra tekrar deneyin.";
                    } else {
                        // Rate limit kaydet
                        Cache::put($rateLimitKey, time(), 4);
                        // Token al
                        $token = $this->fetchToken();
                        if (!$token) {
                            throw new Exception('Token alınamadı');
                        }
                        
                        // Form gönder
                        $this->submitSearchForm($token, $plateCode, $date);
                        
                        // Tüm eczaneleri getir
                        $allPharmacies = $this->fetchAllPharmacies();
                        
                        if (count($allPharmacies) > 0) {
                            // Global cache'e tüm eczaneleri kaydet
                            $globalResult = [
                                'data' => $allPharmacies,
                                'meta' => [
                                    'date' => $date,
                                    'plate_code' => $plateCode,
                                    'total_pharmacies' => count($allPharmacies),
                                    'cached_at' => now()->format('d/m/Y H:i:s')
                                ]
                            ];
                            Cache::put($globalCacheKey, $globalResult, 1800); // 30 dakika
                            
                            // İstenen ilçeye göre filtrele
                            $pharmacies = array_filter($allPharmacies, function($pharmacy) use ($district) {
                                return $this->normalizeDistrict($pharmacy['district']) === $this->normalizeDistrict($district);
                            });
                            $pharmacies = array_values($pharmacies);
                            

                            
                            // Spesifik sonucu da cache'le
                            $result = [
                                'data' => $pharmacies,
                                'meta' => [
                                    'date' => $date,
                                    'plate_code' => $plateCode,
                                    'district' => $district,
                                    'total_pharmacies' => count($pharmacies),
                                    'cached_at' => now()->format('d/m/Y H:i:s')
                                ]
                            ];
                            Cache::put($cacheKey, $result, 1800); // 30 dakika
                        } else {
                            // Boş sonucu da cache'le (kısa süre)
                            $emptyResult = [
                                'data' => [],
                                'meta' => [
                                    'date' => $date,
                                    'plate_code' => $plateCode,
                                    'district' => $district,
                                    'total_pharmacies' => 0,
                                    'cached_at' => now()->format('d/m/Y H:i:s'),
                                    'reason' => 'no_data_from_api'
                                ]
                            ];
                            Cache::put($cacheKey, $emptyResult, 1800); // 30 dakika
                        }
                    }
                }

                // Eğer hala sonuç yoksa ve cache'de de yoksa, geçmiş günlerin cache'ine bak
                if (count($pharmacies) === 0 && !$isRateLimited) {
                    $fallbackPharmacies = $this->getFallbackPharmacies($plateCode, $district);
                    if (count($fallbackPharmacies) > 0) {
                        $pharmacies = $fallbackPharmacies;
                        $isFromCache = true;
                        $error = "Güncel veri alınamadı. Geçmiş tarihli veriler gösteriliyor.";
                    }
                }

            } catch (Exception $e) {
                $error = 'Eczane verileri alınırken bir hata oluştu: ' . $e->getMessage();
                
                // Hata durumunda da fallback cache'e bak
                $fallbackPharmacies = $this->getFallbackPharmacies($plateCode, $district);
                if (count($fallbackPharmacies) > 0) {
                    $pharmacies = $fallbackPharmacies;
                    $isFromCache = true;
                    $error = "Güncel veri alınamadı. Geçmiş tarihli veriler gösteriliyor.";
                }
                
                // Hata logla
                Log::error('Pharmacy API Error', [
                    'error' => $e->getMessage(),
                    'date' => $date,
                    'district' => $district,
                    'plate_code' => $plateCode,
                    'debug_info' => $debugInfo
                ]);
            }
        }

        // Sadece cache tarihi debug bilgisi kalacak

        return view('front.pharmacy.index', compact(
            'pharmacies', 
            'error', 
            'loading', 
            'isFromCache', 
            'plateCode', 
            'date', 
            'district',
            'debugInfo'
        ));
    }

    /**
     * İlçe adını normalize et
     */
    private function normalizeDistrict(string $district): string
    {
        return mb_strtoupper(trim($district), 'UTF-8');
    }

    /**
     * Geçmiş cache verilerinden eczane bul
     */
    private function getFallbackPharmacies(string $plateCode, string $district): array
    {
        // Son 7 günün cache'ine bak
        for ($i = 1; $i <= 7; $i++) {
            $fallbackDate = date('d/m/Y', strtotime("-{$i} days"));
            $fallbackCacheKey = "pharmacy_data_{$plateCode}_{$fallbackDate}_{$district}";
            $fallbackGlobalKey = "pharmacy_global_{$plateCode}_{$fallbackDate}";
            
            $fallbackData = Cache::get($fallbackCacheKey);
            if ($fallbackData && is_array($fallbackData) && isset($fallbackData['data']) && count($fallbackData['data']) > 0) {
                return $fallbackData['data'];
            }
            
            $fallbackGlobalData = Cache::get($fallbackGlobalKey);
            if ($fallbackGlobalData && is_array($fallbackGlobalData) && isset($fallbackGlobalData['data'])) {
                $filtered = array_filter($fallbackGlobalData['data'], function($pharmacy) use ($district) {
                    return $this->normalizeDistrict($pharmacy['district']) === $this->normalizeDistrict($district);
                });
                if (count($filtered) > 0) {
                    return array_values($filtered);
                }
            }
        }
        
        return [];
    }

    /**
     * Token al
     */
    private function fetchToken(): ?string
    {
        try {
            $response = Http::withHeaders($this->headers)
                ->withOptions([
                    'cookies' => \GuzzleHttp\Cookie\CookieJar::fromArray(
                        $this->parseCookies($this->cookieString),
                        parse_url($this->baseUrl, PHP_URL_HOST)
                    ),
                    'verify' => false
                ])
                ->get($this->baseUrl);

            if ($response->failed()) {
                Log::error('Token fetch failed', [
                    'status' => $response->status(),
                    'body' => substr($response->body(), 0, 500)
                ]);
                throw new Exception('Token alınırken hata oluştu - Status: ' . $response->status());
            }

            preg_match('/<input type="hidden" name="token" value="([^"]+)"/', $response->body(), $matches);
            
            if (!isset($matches[1])) {
                Log::error('Token not found in response', [
                    'body_preview' => substr($response->body(), 0, 1000)
                ]);
            }

            return $matches[1] ?? null;
        } catch (Exception $e) {
            Log::error('Token fetch exception', ['error' => $e->getMessage()]);
            throw new Exception('Token alınırken hata: ' . $e->getMessage());
        }
    }

    /**
     * Arama formunu gönder
     */
    private function submitSearchForm(string $token, string $plateCode, string $date): void
    {
        try {
            $postData = [
                'plakaKodu' => $plateCode,
                'nobetTarihi' => $date,
                'token' => $token,
                'btn' => 'Sorgula'
            ];

            $response = Http::withHeaders($this->headers)
                ->withOptions([
                    'cookies' => \GuzzleHttp\Cookie\CookieJar::fromArray(
                        $this->parseCookies($this->cookieString),
                        parse_url($this->baseUrl, PHP_URL_HOST)
                    ),
                    'verify' => false
                ])
                ->asForm()
                ->post($this->baseUrl . '?submit', $postData);

            if ($response->failed()) {
                throw new Exception('Form gönderilirken hata oluştu');
            }
        } catch (Exception $e) {
            throw new Exception('Form gönderme hatası: ' . $e->getMessage());
        }
    }

    /**
     * Tüm eczaneleri getir (ilçe filtrelemesi olmadan)
     */
    private function fetchAllPharmacies(): array
    {
        try {
            $response = Http::withHeaders($this->headers)
                ->withOptions([
                    'cookies' => \GuzzleHttp\Cookie\CookieJar::fromArray(
                        $this->parseCookies($this->cookieString),
                        parse_url($this->baseUrl, PHP_URL_HOST)
                    ),
                    'verify' => false
                ])
                ->get($this->baseUrl . '?nobetci=Eczaneler');

            if ($response->failed() || empty($response->body())) {
                Log::error('Pharmacy data fetch failed', [
                    'status' => $response->status(),
                    'body_empty' => empty($response->body()),
                    'body_preview' => substr($response->body(), 0, 500)
                ]);
                return [];
            }

            $dom = new DOMDocument();
            libxml_use_internal_errors(true);
            $dom->loadHTML('<?xml encoding="UTF-8">' . $response->body());
            libxml_clear_errors();

            $xpath = new DOMXPath($dom);
            $table = $xpath->query("//table[@id='searchTable']")->item(0);

            if (!$table) {
                if (strpos($response->body(), 'Sonuç bulunamadı') !== false) {
                    return [];
                }
                return [];
            }

            $rows = $table->getElementsByTagName('tr');
            $pharmacies = [];

            foreach ($rows as $i => $row) {
                if ($i === 0) continue; // Header satırını atla

                $cols = $row->getElementsByTagName('td');

                if ($cols->length > 0) {
                    $district = trim($cols->item(1)->textContent);
                    
                    $rawPhone = trim(preg_replace('/\s+/', ' ', str_replace("Ara", "", $cols->item(2)->textContent)));
                    
                    $pharmacies[] = [
                        'name' => trim($cols->item(0)->textContent) . ' ECZANESİ',
                        'district' => $district,
                        'phone' => $this->formatPhoneNumber($rawPhone),
                        'address' => $this->formatAddress(trim($cols->item(3)->textContent))
                    ];
                }
            }

            return $pharmacies;
        } catch (Exception $e) {
            throw new Exception('Eczane verileri alınırken hata: ' . $e->getMessage());
        }
    }

    /**
     * Eczaneleri getir (belirli ilçe için)
     */
    private function fetchPharmacies(string $targetDistrict = 'ÇANKAYA'): array
    {
        try {
            $response = Http::withHeaders($this->headers)
                ->withOptions([
                    'cookies' => \GuzzleHttp\Cookie\CookieJar::fromArray(
                        $this->parseCookies($this->cookieString),
                        parse_url($this->baseUrl, PHP_URL_HOST)
                    ),
                    'verify' => false
                ])
                ->get($this->baseUrl . '?nobetci=Eczaneler');

            if ($response->failed() || empty($response->body())) {
                return [];
            }

            $dom = new DOMDocument();
            libxml_use_internal_errors(true);
            $dom->loadHTML('<?xml encoding="UTF-8">' . $response->body());
            libxml_clear_errors();

            $xpath = new DOMXPath($dom);
            $table = $xpath->query("//table[@id='searchTable']")->item(0);

            if (!$table) {
                if (strpos($response->body(), 'Sonuç bulunamadı') !== false) {
                    return [];
                }
                return [];
            }

            $rows = $table->getElementsByTagName('tr');
            $pharmacies = [];

            foreach ($rows as $i => $row) {
                if ($i === 0) continue; // Header satırını atla

                $cols = $row->getElementsByTagName('td');

                if ($cols->length > 0) {
                    $district = trim($cols->item(1)->textContent);

                    if (mb_strtoupper($district, 'UTF-8') === mb_strtoupper($targetDistrict, 'UTF-8')) {
                        $rawPhone = trim(preg_replace('/\s+/', ' ', str_replace("Ara", "", $cols->item(2)->textContent)));
                        
                        $pharmacies[] = [
                            'name' => trim($cols->item(0)->textContent) . ' ECZANESİ',
                            'district' => $district,
                            'phone' => $this->formatPhoneNumber($rawPhone),
                            'address' => $this->formatAddress(trim($cols->item(3)->textContent))
                        ];
                    }
                }
            }

            return $pharmacies;
        } catch (Exception $e) {
            throw new Exception('Eczane verileri alınırken hata: ' . $e->getMessage());
        }
    }

    /**
     * Telefon numarasını formatla
     */
    private function formatPhoneNumber(string $phone): string
    {
        // Tüm özel karakterleri ve boşlukları temizle
        $cleanPhone = preg_replace('/[^0-9]/', '', $phone);
        
        // Eğer telefon numarası boşsa, orijinalini döndür
        if (empty($cleanPhone)) {
            return $phone;
        }
        
        // Türkiye telefon numarası formatı (10 haneli)
        if (strlen($cleanPhone) === 10) {
            // 0312 481 8583 formatında döndür
            return substr($cleanPhone, 0, 4) . ' ' . substr($cleanPhone, 4, 3) . ' ' . substr($cleanPhone, 7, 4);
        }
        
        // 11 haneli ise (başında 0 varsa)
        if (strlen($cleanPhone) === 11 && $cleanPhone[0] === '0') {
            // 0312 481 8583 formatında döndür
            return substr($cleanPhone, 0, 4) . ' ' . substr($cleanPhone, 4, 3) . ' ' . substr($cleanPhone, 7, 4);
        }
        
        // Diğer durumlarda orijinal telefonu döndür
        return $phone;
    }

    /**
     * Adres formatını düzenle - sadece baş harfleri büyük yap
     */
    private function formatAddress(string $address): string
    {
        // Boş adres kontrolü
        if (empty(trim($address))) {
            return $address;
        }
        
        // Adresi küçük harfe çevir
        $formattedAddress = mb_strtolower($address, 'UTF-8');
        
        // Her kelimenin baş harfini büyük yap
        $formattedAddress = mb_convert_case($formattedAddress, MB_CASE_TITLE, 'UTF-8');
        
        // Türkçe karakterler için özel düzenleme
        $formattedAddress = str_replace(
            ['İ', 'I'],
            ['İ', 'I'],
            $formattedAddress
        );
        
        return $formattedAddress;
    }

    /**
     * Cookie string'ini parse et
     */
    private function parseCookies(string $cookieString): array
    {
        $cookies = [];
        $parts = explode('; ', $cookieString);

        foreach ($parts as $part) {
            $cookieParts = explode('=', $part, 2);
            if (count($cookieParts) === 2) {
                $cookies[$cookieParts[0]] = $cookieParts[1];
            }
        }

        return $cookies;
    }
} 