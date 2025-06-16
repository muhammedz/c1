<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
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
            'Accept-Language' => 'en-US,en;q=0.9',
            'Cache-Control' => 'max-age=0',
            'Connection' => 'keep-alive',
            'User-Agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/132.0.0.0 Safari/537.36',
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

        // Form gönderilmişse veya ilk yüklemede eczaneleri getir
        if ($request->has('search') || !$request->hasAny(['plateCode', 'date', 'district', 'search'])) {
            try {
                // Rate limiting kontrolü
                $cacheKey = 'pharmacy_request_' . $request->ip();
                if (Cache::has($cacheKey)) {
                    $timeLeft = 5 - (time() - Cache::get($cacheKey));
                    if ($timeLeft > 0) {
                        $error = "Rate limit aşıldı. Lütfen yeni istek için {$timeLeft} saniye bekleyin.";
                        return view('front.pharmacy.index', compact('pharmacies', 'error', 'plateCode', 'date', 'district'));
                    }
                }

                Cache::put($cacheKey, time(), 5);

                // Parametreleri doğrula
                if (!$plateCode || !is_numeric($plateCode)) {
                    $error = 'Geçersiz il/plaka kodu!';
                } elseif (!preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $date)) {
                    $error = 'Geçersiz tarih formatı (örnek: 22/02/2025)';
                } else {
                    // Cache kontrolü
                    $cacheKey = "pharmacy_data_{$plateCode}_{$date}_{$district}";
                    if (Cache::has($cacheKey)) {
                        $cachedData = Cache::get($cacheKey);
                        $pharmacies = $cachedData['data'] ?? [];
                    } else {
                        // Token al
                        $token = $this->fetchToken();
                        if (!$token) {
                            $error = 'Doğrulama tokeni alınamadı!';
                        } else {
                            // Form gönder
                            $this->submitSearchForm($token, $plateCode, $date);
                            
                            // Eczaneleri getir
                            $pharmacies = $this->fetchPharmacies($district);
                            
                            // Sonucu cache'le (5 dakika)
                            $result = [
                                'data' => $pharmacies,
                                'meta' => [
                                    'date' => $date,
                                    'plate_code' => $plateCode,
                                    'district' => $district,
                                    'total_pharmacies' => count($pharmacies)
                                ]
                            ];
                            Cache::put($cacheKey, $result, 300);
                        }
                    }
                }
            } catch (Exception $e) {
                $error = $e->getMessage();
            }
        }

        return view('front.pharmacy.index', compact('pharmacies', 'error', 'plateCode', 'date', 'district'));
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
                throw new Exception('Token alınırken hata oluştu');
            }

            preg_match('/<input type="hidden" name="token" value="([^"]+)"/', $response->body(), $matches);

            return $matches[1] ?? null;
        } catch (Exception $e) {
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
     * Eczaneleri getir
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
                        $pharmacies[] = [
                            'name' => trim($cols->item(0)->textContent) . ' ECZANESİ',
                            'district' => $district,
                            'phone' => trim(preg_replace('/\s+/', ' ', str_replace("Ara", "", $cols->item(2)->textContent))),
                            'address' => trim($cols->item(3)->textContent)
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