<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\EventScraperService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class EventScrapeController extends Controller
{
    protected $scraperService;

    public function __construct(EventScraperService $scraperService)
    {
        $this->scraperService = $scraperService;
    }

    /**
     * Etkinlik kontrol etme sayfasını göster
     */
    public function check()
    {
        $lastScrape = cache()->get('last_event_scrape');
        return view('admin.events.check', compact('lastScrape'));
    }

    /**
     * Etkinlikleri çek ve işle
     */
    public function scrape(Request $request)
    {
        $page = $request->input('page', 1);
        $baseUrl = 'https://kultursanat.cankaya.bel.tr/etkinlikler';
        $url = $page > 1 ? $baseUrl . "?page=" . $page : $baseUrl;
        
        try {
            $result = $this->scraperService->scrapePage($url, $page);
            
            // Son çekme zamanını cache'e kaydet
            cache()->put('last_event_scrape', now(), now()->addDays(30));
            
            return response()->json($result);
        } catch (\Exception $e) {
            Log::error('Etkinlik çekme hatası: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
                'page' => $page
            ], 500);
        }
    }
    
    /**
     * Tüm etkinlikleri toplu olarak çek ve işle
     * Bu endpoint Ajax isteğine yanıt verir ve tüm sayfaları tarayarak etkinlikleri çeker
     */
    public function scrapeAll()
    {
        try {
            $result = $this->scraperService->scrapeAllPages();
            
            return response()->json($result);
        } catch (\Exception $e) {
            Log::error('Toplu etkinlik çekme hatası: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Verilen URL üzerinden etkinlikleri önizle (veritabanına kaydetmeden)
     */
    public function preview(Request $request)
    {
        $url = $request->input('url');
        $limit = $request->input('limit', 1);
        
        if (empty($url)) {
            return response()->json([
                'success' => false,
                'message' => 'URL parametresi gereklidir.'
            ], 400);
        }
        
        try {
            Log::info('Etkinlik önizleme başlatıldı', [
                'url' => $url,
                'limit' => $limit,
                'timestamp' => now()->toDateTimeString()
            ]);
            
            // HTML içeriğini çek - daha fazla seçenek ile
            Log::info('HTTP isteği gönderiliyor', ['url' => $url]);
            
            // Retry mekanizması ile HTTP istemcisi
            $maxRetries = 3;
            $retryDelay = 2; // saniye
            
            for ($attempt = 1; $attempt <= $maxRetries; $attempt++) {
                try {
                    Log::info("HTTP isteği deneme #{$attempt}", ['url' => $url]);
                    
                    $httpClient = Http::withOptions([
                        'verify' => false,       // SSL doğrulamasını atla
                        'timeout' => 90,         // 90 saniye timeout (daha da artırıldı)
                        'connect_timeout' => 60, // 60 saniye bağlantı timeout (artırıldı)
                        'allow_redirects' => [
                            'max' => 10,
                            'strict' => false,
                            'referer' => true,
                            'protocols' => ['http', 'https']
                        ],
                        'headers' => [
                            'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
                            'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
                            'Accept-Language' => 'tr-TR,tr;q=0.9,en;q=0.8',
                            'Accept-Encoding' => 'gzip, deflate',
                            'Connection' => 'keep-alive',
                            'Upgrade-Insecure-Requests' => '1',
                            'Cache-Control' => 'no-cache',
                            'Pragma' => 'no-cache'
                        ]
                    ]);
                    
                    $response = $httpClient->get($url);
                    
                    // Başarılı ise döngüden çık
                    break;
                    
                } catch (\Illuminate\Http\Client\ConnectionException $e) {
                    Log::warning("Bağlantı hatası deneme #{$attempt}", [
                        'url' => $url,
                        'error' => $e->getMessage(),
                        'attempt' => $attempt,
                        'max_retries' => $maxRetries
                    ]);
                    
                    if ($attempt === $maxRetries) {
                        // Son deneme de başarısız
                        throw $e;
                    }
                    
                    // Bir sonraki deneme için bekle
                    sleep($retryDelay * $attempt); // Her denemede bekleme süresini artır
                    continue;
                                 }
             }
            
            if ($response->failed()) {
                Log::error('URL yanıtı başarısız', [
                    'status' => $response->status(),
                    'reason' => $response->reason(),
                    'url' => $url
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'URL çekilemedi: ' . $response->status() . ' ' . $response->reason()
                ], 422);
            }
            
            $html = $response->body();
            
            if (empty($html)) {
                Log::error('URL yanıtı boş', ['url' => $url]);
                return response()->json([
                    'success' => false,
                    'message' => 'URL içeriği boş geldi.'
                ], 422);
            }
            
            Log::info('HTML içeriği alındı', [
                'length' => strlen($html),
                'first_100_chars' => substr($html, 0, 100)
            ]);
            
            // DOM işlemleri için DOMDocument ve XPath kullan
            $dom = new \DOMDocument();
            
            // Uyarıları geçici olarak kapat
            $internalErrors = libxml_use_internal_errors(true);
            
            // HTML'i yükle
            $loaded = @$dom->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'));
            
            // Yükleme başarısız olduysa
            if (!$loaded) {
                $errors = libxml_get_errors();
                libxml_clear_errors();
                libxml_use_internal_errors($internalErrors);
                
                Log::error('HTML yükleme hatası', [
                    'errors' => $errors,
                    'html_sample' => substr($html, 0, 500)
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'HTML içeriği işlenemedi. XML/HTML hataları mevcut.',
                    'html' => substr($html, 0, 1000) // İlk 1000 karakteri gönder
                ], 422);
            }
            
            // Libxml hata ayıklama modunu eski haline getir
            libxml_use_internal_errors($internalErrors);
            
            $xpath = new \DOMXPath($dom);
            
            // Etkinlik elementlerini bul - farklı class isimleri için alternatif sorgular dene
            Log::info('Etkinlik düğümleri aranıyor');
            $eventNodes = $xpath->query('//div[contains(@class, "event-card")]');
            
            // Eğer birincil sorgu sonuç vermediyse alternatif sorgular dene
            if ($eventNodes->length === 0) {
                Log::info('Birincil sorgu sonuç vermedi, alternatif sorgu deneniyor');
                $eventNodes = $xpath->query('//div[contains(@class, "upcomming-event-wrapper")]');
            }
            
            // Yine sonuç yoksa daha genel bir sorgu dene
            if ($eventNodes->length === 0) {
                Log::info('İkincil sorgu sonuç vermedi, daha genel sorgu deneniyor');
                $eventNodes = $xpath->query('//div[contains(@class, "col-md-6")]/div');
            }
            
            // Farklı HTML yapıları için ek sorgu dene
            if ($eventNodes->length === 0) {
                Log::info('Üçüncü sorgu sonuç vermedi, ekstra sorgular deneniyor');
                $eventNodes = $xpath->query('//div[contains(@class, "card")]');
            }
            
            // Farklı HTML yapıları için ek sorgu dene 2
            if ($eventNodes->length === 0) {
                Log::info('Dördüncü sorgu sonuç vermedi, ekstra sorgular 2 deneniyor');
                $eventNodes = $xpath->query('//div[contains(@class, "etkinlik")]');
            }
            
            // Hala bulunamadıysa etkinlik kartları yok
            if ($eventNodes->length === 0) {
                Log::error('Etkinlik kartları bulunamadı', [
                    'url' => $url,
                    'html_sample' => substr($html, 0, 1000)
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'Sayfada etkinlik kartları bulunamadı. Lütfen HTML yapısını kontrol edin.',
                    'html' => substr($html, 0, 3000) // İlk 3000 karakteri gönder
                ], 422);
            }
            
            // Çekilen etkinlikleri hazırla
            $events = [];
            $count = 0;
            
            Log::info('Etkinlik kartları bulundu', [
                'count' => $eventNodes->length
            ]);
            
            foreach ($eventNodes as $eventNode) {
                if ($count >= $limit) {
                    break;
                }
                
                $eventData = $this->extractEventDataWithHtml($eventNode, $xpath);
                
                // Görsel URL'sinde mükerrer domain sorunu kontrolü
                if (!empty($eventData['imageUrl'])) {
                    $originalUrl = $eventData['imageUrl'];
                    
                    // Mükerrer domain düzeltmesi
                    $domainPattern = 'https://kultursanat.cankaya.bel.tr/';
                    
                    // 1. Durum: domain/domain/path formatı
                    if (strpos($eventData['imageUrl'], $domainPattern . 'https://') === 0) {
                        $eventData['imageUrl'] = str_replace($domainPattern . 'https://', 'https://', $eventData['imageUrl']);
                        Log::info('Önizlemede mükerrer domain düzeltildi (1. format)', [
                            'orijinal' => $originalUrl,
                            'yeni' => $eventData['imageUrl']
                        ]);
                    }
                    
                    // 2. Durum: Doğrudan domain tekrarı
                    if (strpos($eventData['imageUrl'], $domainPattern . $domainPattern) === 0) {
                        $eventData['imageUrl'] = str_replace($domainPattern . $domainPattern, $domainPattern, $eventData['imageUrl']);
                        Log::info('Önizlemede mükerrer domain düzeltildi (2. format)', [
                            'orijinal' => $originalUrl,
                            'yeni' => $eventData['imageUrl']
                        ]);
                    }
                    
                    // 3. Durum: Genel regex kullanarak kontrol
                    if (preg_match('/(https?:\/\/[^\/]+)\/(https?:\/\/)/', $eventData['imageUrl'])) {
                        $eventData['imageUrl'] = preg_replace('/(https?:\/\/[^\/]+)\/(https?:\/\/)/', '$2', $eventData['imageUrl']);
                        Log::info('Önizlemede mükerrer domain regex ile düzeltildi', [
                            'orijinal' => $originalUrl,
                            'yeni' => $eventData['imageUrl']
                        ]);
                    }
                    
                    // URL'deki boşlukları temizle
                    if (strpos($eventData['imageUrl'], ' ') !== false) {
                        $eventData['imageUrl'] = str_replace(' ', '%20', $eventData['imageUrl']);
                        Log::info('Önizlemede URL boşlukları kodlandı', [
                            'orijinal' => $originalUrl,
                            'yeni' => $eventData['imageUrl']
                        ]);
                    }
                }
                
                Log::info('Etkinlik verisi çıkarıldı', [
                    'title' => $eventData['title'],
                    'category' => $eventData['category'],
                    'imageUrl' => $eventData['imageUrl'],
                    'dateText' => $eventData['dateText'],
                    'timeText' => $eventData['timeText'],
                    'location' => $eventData['location']
                ]);
                
                $events[] = $eventData;
                $count++;
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Etkinlikler başarıyla önizlendi.',
                'count' => count($events),
                'events' => $events
            ]);
            
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            Log::error('Bağlantı hatası: ' . $e->getMessage(), [
                'url' => $url,
                'limit' => $limit,
                'error_type' => 'connection_timeout'
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Hedef web sitesine bağlanılamadı. Lütfen internet bağlantınızı kontrol edin veya daha sonra tekrar deneyin. Hata: ' . $e->getMessage()
            ], 500);
        } catch (\Illuminate\Http\Client\RequestException $e) {
            Log::error('HTTP istek hatası: ' . $e->getMessage(), [
                'url' => $url,
                'limit' => $limit,
                'error_type' => 'http_request'
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Web sitesinden veri alınırken hata oluştu. Hedef site geçici olarak erişilemeyebilir. Hata: ' . $e->getMessage()
            ], 500);
        } catch (\Exception $e) {
            Log::error('Etkinlik önizleme hatası: ' . $e->getMessage(), [
                'url' => $url,
                'limit' => $limit,
                'trace' => $e->getTraceAsString(),
                'error_type' => 'general'
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Etkinlik önizleme sırasında beklenmeyen bir hata oluştu. Lütfen daha sonra tekrar deneyin. Hata: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Etkinlik HTML düğümünden veri çıkar ve HTML öğelerini de içer
     */
    protected function extractEventDataWithHtml($eventNode, $xpath)
    {
        // Etkinlik HTML'i (debugging için)
        $fullHtml = $eventNode->ownerDocument->saveHTML($eventNode);
        
        // Başlık - h2.etkinlik-adi içinden
        $titleNode = $xpath->query('.//h2[@class="etkinlik-adi"]', $eventNode)->item(0);
        $title = $titleNode ? trim($titleNode->textContent) : 'İsimsiz Etkinlik';
        
        // Başlıktaki gereksiz karakterleri temizle
        $title = preg_replace('/\s+/', ' ', $title);
        $title = trim($title);
        
        // Çok kısa başlıkları atla
        if (strlen($title) < 3) {
            $title = 'Etkinlik ' . time() . rand(100, 999);
        }
        
        // Kategori (tür) - h3.etkinlik-tur içinden
        $categoryNode = $xpath->query('.//h3[@class="etkinlik-tur"]', $eventNode)->item(0);
        $category = $categoryNode ? trim($categoryNode->textContent) : 'Genel';
        
        // Kategori boşsa veya çok kısaysa Genel kullan
        if (empty($category) || strlen($category) < 2) {
            $category = 'Genel';
        }
        
        // Tarih - span.etkinlik-tarih (Tarih başlığının yanındaki)
        $dateNodes = $xpath->query('.//div[contains(@class, "date-head") and contains(., "Tarih")]/following-sibling::span[@class="etkinlik-tarih col-10"]', $eventNode);
        $dateText = '';
        
        if ($dateNodes->length > 0) {
            $dateText = trim($dateNodes->item(0)->textContent);
        }
        
        // Saat - span.etkinlik-tarih (Saat başlığının yanındaki)
        $timeNodes = $xpath->query('.//div[contains(@class, "date-head") and contains(., "Saat")]/following-sibling::span[@class="etkinlik-tarih col-10"]', $eventNode);
        $timeText = '';
        
        if ($timeNodes->length > 0) {
            $timeText = trim($timeNodes->item(0)->textContent);
        }
        
        // Yer - span.etkinlik-tarih (Yer başlığının yanındaki)
        $locationNodes = $xpath->query('.//div[contains(@class, "date-head") and contains(., "Yer")]/following-sibling::span[@class="etkinlik-tarih col-10"]', $eventNode);
        $location = '';
        
        if ($locationNodes->length > 0) {
            $location = trim($locationNodes->item(0)->textContent);
        }
        
        // Görsel - birden fazla strateji deniyoruz
        $imageUrl = null;
        $imageHtml = '';
        
        // Strateji 1: img.img-fluid elementini doğrudan bul
        $imageNodes = $xpath->query('.//img[@class="img-fluid"]', $eventNode);
        
        if ($imageNodes->length > 0) {
            $imageNode = $imageNodes->item(0);
            $imageHtml = $imageNode->ownerDocument->saveHTML($imageNode);
            
            // Önce srcset'i kontrol et (daha yüksek çözünürlük için)
            $srcset = $imageNode->getAttribute('srcset');
            
            if (!empty($srcset)) {
                Log::info('Bulunan srcset değeri:', ['srcset' => $srcset]);
                
                // Srcset formatı: "url1 1x, url2 2x"
                if (preg_match('/([^\s]+)\s+2x/', $srcset, $matches)) {
                    $imageUrl = $matches[1]; // 2x (yüksek çözünürlük) değerini kullan
                } elseif (preg_match('/([^\s]+)\s+1x/', $srcset, $matches)) {
                    $imageUrl = $matches[1]; // 1x değerini kullan
                }
                
                // Srcset'te regex çalışmadıysa manuel olarak ","'e göre bölerek deneyelim
                if (empty($imageUrl) && strpos($srcset, ',') !== false) {
                    $srcsetParts = explode(',', $srcset);
                    $firstPart = trim($srcsetParts[0]);
                    // Boyut bilgisini (1x, 2x) kaldır
                    $imageUrl = preg_replace('/\s+\d+x$/', '', $firstPart);
                    Log::info('Srcset manuel parçalandı:', ['imageUrl' => $imageUrl]);
                }
            }
            
            // Srcset bulunamadıysa src'yi dene
            if (empty($imageUrl)) {
                $imageUrl = $imageNode->getAttribute('src');
                Log::info('Src özniteliğinden resim URL alındı:', ['imageUrl' => $imageUrl]);
                
                // Base64 encoded resimleri (inline data URI) kullanma
                if (strpos($imageUrl, 'data:image') === 0) {
                    Log::warning('Base64 kodlu görsel, atlanıyor', ['imageUrl' => $imageUrl]);
                    $imageUrl = null;
                }
            }
        }
        
        // Strateji 2: Herhangi bir img elementini ara
        if (empty($imageUrl) || $imageUrl === "data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7") {
            $imgNodes = $xpath->query('.//img', $eventNode);
            foreach ($imgNodes as $img) {
                $src = $img->getAttribute('src');
                if (!empty($src) && strpos($src, 'data:image') !== 0 && $src !== "data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7") {
                    $imageUrl = $src;
                    $imageHtml = $img->ownerDocument->saveHTML($img);
                    Log::info('Alternatif img elementinden URL alındı:', ['imageUrl' => $imageUrl]);
                    break;
                }
            }
        }
        
        // Strateji 3: HTML içinde srcset ve src pattern'lerini ara
        if (empty($imageUrl) || $imageUrl === "data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7") {
            // HTML içinde srcset attributünü arayalım
            if (preg_match('/srcset="([^"]+)\s+1x,\s+([^"]+)\s+2x"/', $fullHtml, $matches)) {
                $imageUrl = !empty($matches[2]) ? $matches[2] : $matches[1];
                Log::info('HTML içinde srcset bulundu:', ['imageUrl' => $imageUrl]);
            } 
            // Alternatif olarak src özelliğini arayalım - tüm URL formatlarına izin ver
            elseif (preg_match('/src="((?:https?:\/\/)?[^"]+\/[^"]+\.(jpg|jpeg|png|gif))"/', $fullHtml, $matches)) {
                $imageUrl = $matches[1];
                Log::info('HTML içinde src bulundu:', ['imageUrl' => $imageUrl]);
            }
        }
        
        // Strateji 4: Genel HTTP URL formatlarını kontrol et
        if (empty($imageUrl)) {
            if (preg_match('/https?:\/\/[^\s"\']+\.(jpg|jpeg|png|gif|webp)/i', $fullHtml, $matches)) {
                $imageUrl = $matches[0];
                Log::info('HTML içinde genel URL formatı bulundu:', ['imageUrl' => $imageUrl]);
            }
        }
        
        // Mükerrer domain kontrolü ve düzeltme işlemleri
        if (!empty($imageUrl)) {
            $originalUrl = $imageUrl;
            
            // 1. Kontrol: https://kultursanat.cankaya.bel.tr/https://kultursanat.cankaya.bel.tr/ formatı
            $domainPattern = 'https://kultursanat.cankaya.bel.tr/';
            if (strpos($imageUrl, $domainPattern . 'https://') !== false) {
                $imageUrl = str_replace($domainPattern . 'https://', 'https://', $imageUrl);
                Log::info('Mükerrer domain temizlendi (1. format):', [
                    'orijinal' => $originalUrl,
                    'yeni' => $imageUrl
                ]);
            }
            
            // 2. Kontrol: https://kultursanat.cankaya.bel.tr/https://kultursanat.cankaya.bel.tr/ formatı (alternatif kontrol)
            if (strpos($imageUrl, $domainPattern . 'https://kultursanat.cankaya.bel.tr/') !== false) {
                $imageUrl = str_replace($domainPattern . 'https://kultursanat.cankaya.bel.tr/', $domainPattern, $imageUrl);
                Log::info('Mükerrer domain temizlendi (2. format):', [
                    'orijinal' => $originalUrl,
                    'yeni' => $imageUrl
                ]);
            }
            
            // 3. Kontrol: Genel regex ile domain/domain formatını kontrol et
            if (preg_match('/(https?:\/\/[^\/]+)\/(https?:\/\/)/', $imageUrl, $matches)) {
                $imageUrl = preg_replace('/(https?:\/\/[^\/]+)\/(https?:\/\/)/', '$2', $imageUrl);
                Log::info('Mükerrer domain regex ile temizlendi:', [
                    'orijinal' => $originalUrl,
                    'yeni' => $imageUrl,
                    'matches' => $matches
                ]);
            }
            
            // URL'deki boşlukları kodla
            if (strpos($imageUrl, ' ') !== false) {
                $imageUrl = str_replace(' ', '%20', $imageUrl);
                Log::info('URL boşlukları kodlandı:', [
                    'orijinal' => $originalUrl,
                    'yeni' => $imageUrl
                ]);
            }
        }
        
        // URL'in tam olduğunu kontrol et
        if ($imageUrl && !filter_var($imageUrl, FILTER_VALIDATE_URL)) {
            $originalUrl = $imageUrl;
            // Temel URL oluştur
            $baseUrl = 'https://kultursanat.cankaya.bel.tr';
            
            // / ile başlıyorsa doğrudan ekle
            if (strpos($imageUrl, '/') === 0) {
                $imageUrl = $baseUrl . $imageUrl;
            } else {
                // Değilse / ile birleştir
                $imageUrl = $baseUrl . '/' . $imageUrl;
            }
            
            Log::info('URL tam formata dönüştürüldü:', [
                'orijinal' => $originalUrl,
                'yeni' => $imageUrl
            ]);
        }
        
        // Detay linki - ana a etiketi
        $detailUrl = null;
        $linkNodes = $xpath->query('.//a', $eventNode);
        
        if ($linkNodes->length > 0) {
            $detailUrl = $linkNodes->item(0)->getAttribute('href');
            
            // Tam URL değilse başına domain ekle
            if ($detailUrl && !filter_var($detailUrl, FILTER_VALIDATE_URL)) {
                $baseUrl = 'https://kultursanat.cankaya.bel.tr';
                
                // / ile başlıyorsa doğrudan ekle
                if (strpos($detailUrl, '/') === 0) {
                    $detailUrl = $baseUrl . $detailUrl;
                } else {
                    // Değilse / ile birleştir
                    $detailUrl = $baseUrl . '/' . $detailUrl;
                }
            }
        }
        
        // Debug için log
        \Illuminate\Support\Facades\Log::info('Önizleme için etkinlik verisi oluşturuldu', [
            'title' => $title,
            'category' => $category,
            'dateText' => $dateText,
            'timeText' => $timeText,
            'location' => $location,
            'imageUrl' => $imageUrl,
            'imageUrlLength' => $imageUrl ? strlen($imageUrl) : 0,
            'detailUrl' => $detailUrl,
        ]);
        
        return [
            'title' => $title,
            'category' => $category,
            'dateText' => $dateText,
            'timeText' => $timeText,
            'location' => $location,
            'imageUrl' => $imageUrl,
            'imageHtml' => $imageHtml,
            'detailUrl' => $detailUrl,
            'fullHtml' => $fullHtml,
            // Tarih ayrıştırma için ek alanlar
            'dateNum' => preg_match('/(\d+)/', $dateText, $matches) ? $matches[1] : null,
            'dateDay' => preg_match('/([A-Za-zıİğĞüÜşŞöÖçÇ]+)\s+\d+/', $dateText, $matches) ? $matches[1] : null,
        ];
    }

    /**
     * Etkinlik ekle
     */
    public function add(Request $request)
    {
        try {
            // Post edilen etkinlik verilerini al
            $eventData = $request->all();
            
            // Loglama ekle
            Log::info('Etkinlik ekleme isteği', [
                'etkinlik_verisi' => $eventData
            ]);
            
            // Etkinlik servisini çağır
            $service = new EventScraperService();
            $result = $service->addSingleEventFromData($eventData);
            
            // Sonucu logla
            Log::info('Etkinlik ekleme sonucu', [
                'sonuç' => $result
            ]);
            
            return response()->json($result);
            
        } catch (\Exception $e) {
            Log::error('Etkinlik ekleme hatası', [
                'hata' => $e->getMessage(),
                'satır' => $e->getLine(),
                'dosya' => $e->getFile()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Sistem hatası: ' . $e->getMessage()
            ]);
        }
    }
} 