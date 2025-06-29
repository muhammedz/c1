<?php

namespace App\Services;

use App\Models\Event;
use App\Models\EventCategory;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Carbon\Carbon;
use DOMDocument;
use DOMXPath;

class EventScraperService
{
    /**
     * Türkçe ay isimlerini içeren array
     */
    protected $turkishMonths = [
        'Ocak' => '01',
        'Şubat' => '02',
        'Mart' => '03',
        'Nisan' => '04',
        'Mayıs' => '05',
        'Haziran' => '06',
        'Temmuz' => '07',
        'Ağustos' => '08',
        'Eylül' => '09',
        'Ekim' => '10',
        'Kasım' => '11',
        'Aralık' => '12'
    ];

    /**
     * Tüm sayfaları çekerek işler
     */
    public function scrapeAllPages($connectionOptions = null)
    {
        $baseUrl = 'https://kultursanat.cankaya.bel.tr/etkinlikler';
        $currentPage = 1;
        $hasNextPage = true;
        $totalEvents = 0;
        $newEvents = 0;
        $newCategories = 0;
        $errors = [];
        
        // İlk sayfayı çek ve toplam sayfa sayısını belirle
        $httpOptions = [
            'verify' => false,
            'timeout' => 90,
            'connect_timeout' => 60,
            'headers' => [
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
                'Cache-Control' => 'no-cache',
                'Pragma' => 'no-cache'
            ]
        ];
        
        // Bağlantı seçeneklerini uygula
        if ($connectionOptions && is_array($connectionOptions)) {
            $connectionType = $connectionOptions['type'] ?? 'normal';
            
            if ($connectionType === 'ip' && !empty($connectionOptions['target_ip'])) {
                $targetIp = $connectionOptions['target_ip'];
                $parsedUrl = parse_url($baseUrl);
                $domain = $parsedUrl['host'];
                $port = $parsedUrl['scheme'] === 'https' ? 443 : 80;
                
                // CURL resolve seçeneği ekle
                $httpOptions['curl'] = [
                    CURLOPT_RESOLVE => [
                        "$domain:$port:$targetIp"
                    ]
                ];
                
                Log::info('EventScraperService scrapeAllPages: IP yönlendirmesi aktif', [
                    'domain' => $domain,
                    'port' => $port,
                    'target_ip' => $targetIp,
                    'resolve_entry' => "$domain:$port:$targetIp"
                ]);
            } elseif ($connectionType === 'proxy' && !empty($connectionOptions['proxy_url'])) {
                $proxyUrl = $connectionOptions['proxy_url'];
                $proxyUsername = $connectionOptions['proxy_username'] ?? null;
                $proxyPassword = $connectionOptions['proxy_password'] ?? null;
                
                // Proxy kullanıcı adı ve şifresi varsa kimlik doğrulamalı proxy kullan
                if ($proxyUsername && $proxyPassword) {
                    // Proxy URL'inden protokol ve host:port ayır
                    $parsedUrl = parse_url($proxyUrl);
                    $proxyHost = $parsedUrl['host'] ?? '';
                    $proxyPort = $parsedUrl['port'] ?? '';
                    
                    if ($proxyHost && $proxyPort) {
                        // Kimlik doğrulamalı proxy formatı: http://user:pass@host:port
                        $authenticatedProxy = $parsedUrl['scheme'] . '://' . $proxyUsername . ':' . $proxyPassword . '@' . $proxyHost . ':' . $proxyPort;
                        $httpOptions['proxy'] = $authenticatedProxy;
                    }
                } else {
                    $httpOptions['proxy'] = $proxyUrl;
                }
                
                Log::info('EventScraperService scrapeAllPages: Proxy bağlantısı aktif', [
                    'proxy_url' => $proxyUrl,
                    'has_auth' => !empty($proxyUsername),
                    'final_proxy' => $httpOptions['proxy']
                ]);
            }
        }
        
        $content = Http::withOptions($httpOptions)->get($baseUrl)->body();
        $totalPages = $this->getTotalPageCount($content);
        
        $results = [
            'success' => true,
            'message' => 'İşlem başladı',
            'totalPages' => $totalPages,
            'currentPage' => $currentPage,
            'totalEvents' => 0,
            'newEvents' => 0,
            'newCategories' => 0,
            'errors' => [],
            'pageData' => []
        ];
        
        while ($currentPage <= $totalPages && $hasNextPage) {
            $url = $currentPage === 1 ? $baseUrl : $baseUrl . '?page=' . $currentPage;
            $pageResult = $this->scrapePage($url, $currentPage, $connectionOptions);
            
            // Sayfa sonuçlarını genel sonuçlara ekle
            $totalEvents += $pageResult['totalEvents'];
            $newEvents += $pageResult['newEvents'];
            $newCategories += $pageResult['newCategories'];
            
            if (!empty($pageResult['errors'])) {
                $errors = array_merge($errors, $pageResult['errors']);
            }
            
            $results['pageData'][$currentPage] = $pageResult;
            
            // Sonraki sayfanın olup olmadığını kontrol et
            $hasNextPage = $pageResult['hasNextPage'];
            $currentPage++;
        }
        
        // Genel sonuçları güncelle
        $results['totalEvents'] = $totalEvents;
        $results['newEvents'] = $newEvents;
        $results['newCategories'] = $newCategories;
        $results['errors'] = $errors;
        $results['message'] = 'İşlem tamamlandı. Toplam ' . $totalEvents . ' etkinlik bulundu, ' . 
                            $newEvents . ' yeni etkinlik eklendi, ' . 
                            $newCategories . ' yeni kategori oluşturuldu.';
        
        return $results;
    }

    /**
     * Belirtilen sayfadaki etkinlikleri çek ve işle
     */
    public function scrapePage($url, $page = 1, $connectionOptions = null)
    {
        try {
            // HTTP seçeneklerini hazırla
            $httpOptions = [
                'verify' => false,
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
            ];
            
            // Bağlantı seçeneklerini uygula
            if ($connectionOptions && is_array($connectionOptions)) {
                $connectionType = $connectionOptions['type'] ?? 'normal';
                
                if ($connectionType === 'ip' && !empty($connectionOptions['target_ip'])) {
                    $targetIp = $connectionOptions['target_ip'];
                    $parsedUrl = parse_url($url);
                    $domain = $parsedUrl['host'];
                    $port = $parsedUrl['scheme'] === 'https' ? 443 : 80;
                    
                    // CURL resolve seçeneği ekle
                    $httpOptions['curl'] = [
                        CURLOPT_RESOLVE => [
                            "$domain:$port:$targetIp"
                        ]
                    ];
                    
                    Log::info('EventScraperService: IP yönlendirmesi aktif', [
                        'domain' => $domain,
                        'port' => $port,
                        'target_ip' => $targetIp,
                        'resolve_entry' => "$domain:$port:$targetIp",
                        'url' => $url,
                        'page' => $page
                    ]);
                } elseif ($connectionType === 'proxy' && !empty($connectionOptions['proxy_url'])) {
                    $proxyUrl = $connectionOptions['proxy_url'];
                    $proxyUsername = $connectionOptions['proxy_username'] ?? null;
                    $proxyPassword = $connectionOptions['proxy_password'] ?? null;
                    
                    // Proxy kullanıcı adı ve şifresi varsa kimlik doğrulamalı proxy kullan
                    if ($proxyUsername && $proxyPassword) {
                        // Proxy URL'inden protokol ve host:port ayır
                        $parsedUrl = parse_url($proxyUrl);
                        $proxyHost = $parsedUrl['host'] ?? '';
                        $proxyPort = $parsedUrl['port'] ?? '';
                        
                        if ($proxyHost && $proxyPort) {
                            // Kimlik doğrulamalı proxy formatı: http://user:pass@host:port
                            $authenticatedProxy = $parsedUrl['scheme'] . '://' . $proxyUsername . ':' . $proxyPassword . '@' . $proxyHost . ':' . $proxyPort;
                            $httpOptions['proxy'] = $authenticatedProxy;
                        }
                    } else {
                        $httpOptions['proxy'] = $proxyUrl;
                    }
                    
                    Log::info('EventScraperService: Proxy bağlantısı aktif', [
                        'proxy_url' => $proxyUrl,
                        'has_auth' => !empty($proxyUsername),
                        'final_proxy' => $httpOptions['proxy'],
                        'url' => $url,
                        'page' => $page
                    ]);
                }
            }
            
            // Sayfayı HTTP ile çek - SSL doğrulamasını devre dışı bırak
            $response = Http::withOptions($httpOptions)->get($url);
            $content = $response->body();
            
            // DOM oluştur
            $dom = new DOMDocument();
            @$dom->loadHTML(mb_convert_encoding($content, 'HTML-ENTITIES', 'UTF-8'));
            $xpath = new DOMXPath($dom);
            
            // Sayfa yapısını analiz et
            $eventNodes = $xpath->query('//div[contains(@class, "event-card")]');
            $eventCount = $eventNodes->length;
            
            $processedEvents = 0;
            $newEvents = 0;
            $newCategories = 0;
            $errors = [];
            
            // Her etkinliği işle
            foreach ($eventNodes as $eventNode) {
                try {
                    $eventData = $this->extractEventData($eventNode, $xpath);
                    
                    // Kategoriyi kontrol et ve gerekirse oluştur
                    $category = $this->findOrCreateCategory($eventData['category']);
                    if ($category->wasRecentlyCreated) {
                        $newCategories++;
                    }
                    
                    // Etkinliğin mevcut olup olmadığını kontrol et
                    $existingEvent = $this->checkExistingEvent($eventData);
                    
                    if (!$existingEvent) {
                        // Yeni etkinlik oluştur
                        $event = $this->createEvent($eventData, $category->id, $connectionOptions);
                        $newEvents++;
                    }
                    
                    $processedEvents++;
                } catch (\Exception $e) {
                    Log::error('Etkinlik işlenirken hata oluştu', [
                        'error' => $e->getMessage(),
                        'event' => $eventData ?? 'Data extraction failed'
                    ]);
                    $errors[] = 'Etkinlik işleme hatası: ' . $e->getMessage();
                }
            }
            
            // Toplam sayfa sayısını belirle
            $totalPages = $this->getTotalPageCount($content);
            $hasNextPage = $page < $totalPages;
            
            return [
                'success' => true,
                'page' => $page,
                'totalPages' => $totalPages,
                'totalEvents' => $eventCount,
                'processedEvents' => $processedEvents,
                'newEvents' => $newEvents,
                'newCategories' => $newCategories,
                'hasNextPage' => $hasNextPage,
                'errors' => $errors
            ];
            
        } catch (\Exception $e) {
            Log::error('Sayfa çekilirken hata oluştu', [
                'url' => $url,
                'page' => $page,
                'error' => $e->getMessage()
            ]);
            
            return [
                'success' => false,
                'page' => $page,
                'error' => 'Sayfa çekilirken hata: ' . $e->getMessage(),
                'totalEvents' => 0,
                'processedEvents' => 0,
                'newEvents' => 0,
                'newCategories' => 0,
                'hasNextPage' => false,
                'errors' => [$e->getMessage()]
            ];
        }
    }

    /**
     * Etkinlik HTML düğümünden veri çıkarır
     */
    protected function extractEventData($eventNode, $xpath)
    {
        // Başlık - birden fazla alternatif sorgu dene
        $titleNode = null;
        $titleSelectors = [
            './/h3[contains(@class, "card-title")]',
            './/div[contains(@class, "card-title")]',
            './/h2[@class="etkinlik-adi"]',
            './/div[contains(@class, "title")]',
            './/h4',
            './/h3',
            './/h2',
            './/strong',
            './/b',
        ];
        
        // Her bir seçici ile başlık elementini bulmaya çalış
        foreach ($titleSelectors as $selector) {
            $node = $xpath->query($selector, $eventNode)->item(0);
            if ($node) {
                $titleNode = $node;
                break;
            }
        }

        // Başlık bulunamadıysa, alternatif yöntem dene
        if (!$titleNode) {
            $textNodes = $xpath->query('.//*[not(self::script) and not(self::style)]', $eventNode);
            $titleNode = $textNodes->item(0);
        }
        
        $title = $titleNode ? trim($titleNode->textContent) : 'İsimsiz Etkinlik';
        
        // Başlıktaki gereksiz karakterleri temizle
        $title = preg_replace('/\s+/', ' ', $title);
        $title = trim($title);
        
        // Başlığı Türkçe karakter duyarlı şekilde formatla
        $title = $this->formatTurkishTitle($title);
        
        // Çok kısa başlıkları atla
        if (strlen($title) < 3) {
            $title = 'Etkinlik ' . time() . rand(100, 999);
        }
        
        // Kategori (tür)
        $categoryNode = $xpath->query('.//span[contains(@class, "event-category")]', $eventNode)->item(0);
        if (!$categoryNode) {
            $categoryNode = $xpath->query('.//h3[@class="etkinlik-tur"]', $eventNode)->item(0);
        }
        $category = $categoryNode ? trim($categoryNode->textContent) : 'Genel';
        
        // Kategori boşsa veya çok kısaysa Genel kullan
        if (empty($category) || strlen($category) < 2) {
            $category = 'Genel';
        }

        // Eğer başlık ile kategori aynıysa, bu bir sorundur - detaylı logla
        if ($title === $category) {
            Log::warning('DİKKAT: Başlık ve kategori aynı değere sahip!', [
                'title' => $title,
                'category' => $category
            ]);
        }
        
        // Tarih
        $dateNode = $xpath->query('.//span[contains(@class, "event-date")]', $eventNode)->item(0);
        if (!$dateNode) {
            $dateNodes = $xpath->query('.//div[contains(@class, "date-head") and contains(., "Tarih")]/following-sibling::span[@class="etkinlik-tarih col-10"]', $eventNode);
            if ($dateNodes->length > 0) {
                $dateNode = $dateNodes->item(0);
            }
        }
        $dateText = $dateNode ? trim($dateNode->textContent) : null;
        
        // Saat
        $timeNode = $xpath->query('.//span[contains(@class, "event-time")]', $eventNode)->item(0);
        if (!$timeNode) {
            $timeNodes = $xpath->query('.//div[contains(@class, "date-head") and contains(., "Saat")]/following-sibling::span[@class="etkinlik-tarih col-10"]', $eventNode);
            if ($timeNodes->length > 0) {
                $timeNode = $timeNodes->item(0);
            }
        }
        $timeText = $timeNode ? trim($timeNode->textContent) : null;
        
        // Yer
        $locationNode = $xpath->query('.//span[contains(@class, "event-location")]', $eventNode)->item(0);
        if (!$locationNode) {
            $locationNodes = $xpath->query('.//div[contains(@class, "date-head") and contains(., "Yer")]/following-sibling::span[@class="etkinlik-tarih col-10"]', $eventNode);
            if ($locationNodes->length > 0) {
                $locationNode = $locationNodes->item(0);
            }
        }
        $location = $locationNode ? trim($locationNode->textContent) : null;
        
        // Görsel - önce img.img-fluid, sonra diğer img elementlerini deneyelim
        $imageNodes = $xpath->query('.//img[@class="img-fluid"]', $eventNode);
        $imageUrl = null;
        
        if ($imageNodes->length > 0) {
            $imageNode = $imageNodes->item(0);
            
            // Önce srcset'i kontrol et (daha yüksek çözünürlük için)
            $srcset = $imageNode->getAttribute('srcset');
            
            if (!empty($srcset)) {
                // Srcset formatı: "url1 1x, url2 2x"
                if (preg_match('/([^\s]+)\s+2x/', $srcset, $matches)) {
                    $imageUrl = $matches[1]; // 2x (yüksek çözünürlük) değerini kullan
                } elseif (preg_match('/([^\s]+)\s+1x/', $srcset, $matches)) {
                    $imageUrl = $matches[1]; // 1x değerini kullan
                }
            }
            
            // Srcset bulunamadıysa src'yi dene
            if (empty($imageUrl)) {
                $imageUrl = $imageNode->getAttribute('src');
            }
        }
        
        // Eğer hala resim bulunamadıysa, herhangi bir img etiketini deneyelim
        if (empty($imageUrl) || $imageUrl === "data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7") {
            $imgNodes = $xpath->query('.//img', $eventNode);
            foreach ($imgNodes as $img) {
                $src = $img->getAttribute('src');
                if (!empty($src) && strpos($src, 'data:image') !== 0 && $src !== "data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7") {
                    $imageUrl = $src;
                    break;
                }
            }
        }
        
        // Detay linki - Next.js yapısına uygun strateji
        $detailUrl = null;
        
        // Strateji 1: Eğer eventNode kendisi bir <a> etiketiyse
        if ($eventNode->nodeName === 'a') {
            $detailUrl = $eventNode->getAttribute('href');
            Log::info('EventNode kendisi bir a etiketi', [
                'title' => $title,
                'href' => $detailUrl
            ]);
        }
        
        // Strateji 2: Parent a etiketini ara (Next.js yapısı)
        if (empty($detailUrl)) {
            $parentLink = $xpath->query('./ancestor::a[1]', $eventNode);
            if ($parentLink->length > 0) {
                $detailUrl = $parentLink->item(0)->getAttribute('href');
                Log::info('Parent a etiketi bulundu', [
                    'title' => $title,
                    'href' => $detailUrl
                ]);
            }
        }
        
        // Strateji 3: İçindeki a etiketlerini ara
        if (empty($detailUrl)) {
            $linkNodes = $xpath->query('.//a', $eventNode);
            
            Log::info('İçindeki a etiketleri aranıyor', [
                'title' => $title,
                'linkNodes_count' => $linkNodes->length
            ]);
            
            if ($linkNodes->length > 0) {
                $detailUrl = $linkNodes->item(0)->getAttribute('href');
                Log::info('İçindeki a etiketi bulundu', [
                    'title' => $title,
                    'href' => $detailUrl
                ]);
            }
        }
        
        // Strateji 4: Eğer a etiketi bulunamadıysa, başlıktan URL oluştur
        if (empty($detailUrl) && !empty($title)) {
            // Türkçe karakterleri İngilizce karşılıklarına çevir
            $slug = $this->turkishToEnglish($title);
            
            // URL-friendly slug oluştur
            $slug = strtolower($slug);
            $slug = preg_replace('/[^a-z0-9\s-]/', '', $slug);
            $slug = preg_replace('/[\s-]+/', '-', $slug);
            $slug = trim($slug, '-');
            
            // Detay URL'sini oluştur
            $detailUrl = 'https://kultursanat.cankaya.bel.tr/etkinlikler/' . $slug;
            
            Log::info('Başlıktan detay URL oluşturuldu', [
                'title' => $title,
                'slug' => $slug,
                'detailUrl' => $detailUrl
            ]);
        }
        
        // URL'in tam olduğunu kontrol et
        if ($imageUrl && !Str::startsWith($imageUrl, ['http://', 'https://'])) {
            $imageUrl = 'https://kultursanat.cankaya.bel.tr' . ($imageUrl[0] === '/' ? '' : '/') . $imageUrl;
        }
        
        if ($detailUrl && !Str::startsWith($detailUrl, ['http://', 'https://'])) {
            $detailUrl = 'https://kultursanat.cankaya.bel.tr' . ($detailUrl[0] === '/' ? '' : '/') . $detailUrl;
        }
        
        // Mükerrer domain kontrolü
        if ($imageUrl && strpos($imageUrl, 'https://kultursanat.cankaya.bel.tr/https://') !== false) {
            $imageUrl = str_replace('https://kultursanat.cankaya.bel.tr/https://', 'https://', $imageUrl);
        }
        
        // URL'deki boşlukları kodla
        if ($imageUrl && strpos($imageUrl, ' ') !== false) {
            $imageUrl = str_replace(' ', '%20', $imageUrl);
        }

        // Tarihi ve saati Carbon nesnesine çevir
        $startDate = $this->parseDate($dateText, $timeText);
        $endDate = null; // End date bilgisi yoksa null olarak bırak
        
        // Detay URL'inden açıklama çekmeye çalış
        $description = 'Etkinlik detayları için tıklayınız.';
        if ($detailUrl) {
            try {
                $description = $this->scrapeEventDescription($detailUrl);
            } catch (\Exception $e) {
                Log::warning('Etkinlik detayı çekilemedi', [
                    'url' => $detailUrl,
                    'error' => $e->getMessage()
                ]);
            }
        }

        // Benzersiz bir slug oluştur - başlık, tarih ve rastgele string kombinasyonu
        $uniqueSlug = Str::slug($title) . '-' . date('Ymd') . '-' . Str::random(5);
        
        return [
            'title' => $title,
            'slug' => $uniqueSlug, // Benzersiz slug kullan
            'category' => $category,
            'description' => $description,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'location' => $location,
            'image_url' => $imageUrl,
            'detail_url' => $detailUrl, // createEvent metodunda external_url olarak kaydedilecek
            'external_id' => $this->generateExternalId($title, $startDate, $location),
            'dateText' => $dateText,
            'timeText' => $timeText
        ];
    }

    /**
     * Etkinlik detay sayfasından açıklama çeker
     */
    protected function scrapeEventDescription($url)
    {
        try {
            // URL'in tam olduğunu kontrol et
            if (!Str::startsWith($url, ['http://', 'https://'])) {
                $url = 'https://kultursanat.cankaya.bel.tr' . $url;
            }
            
            $response = Http::withOptions([
                'verify' => false,
                'timeout' => 90,
                'connect_timeout' => 60,
                'headers' => [
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
                    'Cache-Control' => 'no-cache',
                    'Pragma' => 'no-cache'
                ]
            ])->get($url);
            $content = $response->body();
            
            $dom = new DOMDocument();
            @$dom->loadHTML(mb_convert_encoding($content, 'HTML-ENTITIES', 'UTF-8'));
            $xpath = new DOMXPath($dom);
            
            $descriptionNode = $xpath->query('//div[contains(@class, "event-description")]')->item(0);
            
            if ($descriptionNode) {
                return trim($descriptionNode->textContent);
            }
            
            return 'Etkinlik detayları için tıklayınız.';
        } catch (\Exception $e) {
            Log::error('Etkinlik açıklaması çekilemedi', [
                'url' => $url,
                'error' => $e->getMessage()
            ]);
            return 'Etkinlik detayları için tıklayınız.';
        }
    }

    /**
     * Türkçe tarih metinlerini Carbon tarih nesnesine çevirir
     */
    protected function parseDate($dateText, $timeText)
    {
        if (!$dateText) {
            return now(); // Varsayılan olarak şimdiki zamanı kullan
        }
        
        try {
            // Örnek format: "12 Haziran 2023"
            $dateParts = explode(' ', trim($dateText));
            
            if (count($dateParts) >= 3) {
                $day = $dateParts[0];
                $monthName = $dateParts[1];
                $year = $dateParts[2];
                
                // Türkçe ay adını sayısal değere çevir
                $month = $this->turkishMonths[$monthName] ?? '01';
                
                // Saat bilgisini ayır (örn: "19:00")
                $timeParts = explode(':', $timeText ?? '00:00');
                $hour = $timeParts[0] ?? '00';
                $minute = $timeParts[1] ?? '00';
                
                // Carbon nesnesi oluştur
                return Carbon::create($year, $month, $day, $hour, $minute, 0);
            }
        } catch (\Exception $e) {
            Log::error('Tarih ayrıştırılamadı', [
                'dateText' => $dateText,
                'timeText' => $timeText,
                'error' => $e->getMessage()
            ]);
        }
        
        return now(); // Hata durumunda şimdiki zamanı kullan
    }

    /**
     * Kategoriyi bulur veya oluşturur
     */
    protected function findOrCreateCategory($categoryName)
    {
        $slug = Str::slug($categoryName);
        
        return EventCategory::firstOrCreate(
            ['slug' => $slug],
            [
                'name' => $categoryName,
                'description' => $categoryName . ' kategorisindeki etkinlikler',
                'color' => $this->generateRandomColor(),
                'order' => EventCategory::count() + 1,
                'is_active' => true
            ]
        );
    }

    /**
     * Belirtilen etkinlik verilerine sahip mevcut bir etkinlik olup olmadığını kontrol eder
     */
    protected function checkExistingEvent($eventData)
    {
        // Çoklu kontrol stratejisi - aynı etkinliğin farklı şekillerde eklenmesini engelle
        
        // 1. External ID ile kontrol (en güvenilir)
        if (!empty($eventData['external_id'])) {
            $existingByExternalId = Event::where('external_id', $eventData['external_id'])->first();
            if ($existingByExternalId) {
                Log::info('Mevcut etkinlik bulundu (external_id)', [
                    'title' => $eventData['title'],
                    'external_id' => $eventData['external_id'],
                    'existing_id' => $existingByExternalId->id
                ]);
                return $existingByExternalId;
            }
        }
        
        // 2. External URL ile kontrol
        if (!empty($eventData['detail_url'])) {
            $existingByUrl = Event::where('external_url', $eventData['detail_url'])->first();
            if ($existingByUrl) {
                Log::info('Mevcut etkinlik bulundu (external_url)', [
                    'title' => $eventData['title'],
                    'external_url' => $eventData['detail_url'],
                    'existing_id' => $existingByUrl->id
                ]);
                return $existingByUrl;
            }
        }
        
        // 3. Başlık ve tarih kombinasyonu ile kontrol
        $titleSlug = Str::slug($eventData['title']);
        $existingByTitleAndDate = Event::where('title', $eventData['title'])
            ->where('start_date', $eventData['start_date'])
            ->first();
            
        if ($existingByTitleAndDate) {
            Log::info('Mevcut etkinlik bulundu (title + date)', [
                'title' => $eventData['title'],
                'start_date' => $eventData['start_date'],
                'existing_id' => $existingByTitleAndDate->id
            ]);
            return $existingByTitleAndDate;
        }
        
        // 4. Benzer başlık kontrolü (Türkçe karakter farklılıkları için)
        $normalizedTitle = $this->normalizeTitle($eventData['title']);
        $existingBySimilarTitle = Event::whereRaw('LOWER(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(title, "ç", "c"), "ğ", "g"), "ı", "i"), "ö", "o"), "ş", "s"), "ü", "u")) = ?', [
            strtolower($normalizedTitle)
        ])->where('start_date', $eventData['start_date'])->first();
        
        if ($existingBySimilarTitle) {
            Log::info('Mevcut etkinlik bulundu (normalized title + date)', [
                'title' => $eventData['title'],
                'normalized_title' => $normalizedTitle,
                'start_date' => $eventData['start_date'],
                'existing_id' => $existingBySimilarTitle->id
            ]);
            return $existingBySimilarTitle;
        }
        
        // Hiçbir eşleşme bulunamadı
        Log::info('Yeni etkinlik - duplicate bulunamadı', [
            'title' => $eventData['title'],
            'external_id' => $eventData['external_id'] ?? 'yok',
            'external_url' => $eventData['detail_url'] ?? 'yok'
        ]);
        
        return null;
    }

    /**
     * Yeni bir etkinlik oluşturur
     */
    protected function createEvent($eventData, $categoryId, $connectionOptions = null)
    {
        // Görsel URL'sinden görseli indir
        $coverImage = null;
        if (!empty($eventData['image_url'])) {
            // Görsel URL'sini kontrol et - base64 vb. değilse indir
            if (strpos($eventData['image_url'], 'data:image') !== 0) {
                // Tüm boşlukları kodla ve URL'yi temizle
                $cleanImageUrl = str_replace(' ', '%20', trim($eventData['image_url']));
                
                // Mükerrer domain kontrolü
                if (strpos($cleanImageUrl, 'https://kultursanat.cankaya.bel.tr/https://') !== false) {
                    $cleanImageUrl = str_replace('https://kultursanat.cankaya.bel.tr/https://', 'https://', $cleanImageUrl);
                }
                
                $coverImage = $this->downloadImage($cleanImageUrl, $connectionOptions);
            }
        }

        // Slug'ı benzersiz hale getir - başlıkla beraber tarih ve rastgele karakter ekle
        $uniqueSlug = Str::slug($eventData['title']) . '-' . date('Ymd') . '-' . Str::random(5);

        try {
            $event = new Event();
            $event->title = $eventData['title'];
            $event->slug = $uniqueSlug;
            $event->description = $eventData['description'] ?? 'Etkinlik detayları için tıklayınız.';
            $event->category_id = $categoryId;
            $event->start_date = $eventData['start_date'] ?? now();
            $event->end_date = $eventData['end_date'] ?? ($eventData['start_date'] ? (clone $eventData['start_date'])->addHours(2) : now()->addHours(2));
            $event->location = $eventData['location'] ?? 'Belirtilmemiş';
            
            // İndirilen görsel varsa kaydet, yoksa URL'yi direkt kaydet
            $event->cover_image = $coverImage ?? $eventData['image_url'] ?? null;
            
            // External alanları kaydet
            $event->external_id = $eventData['external_id'] ?? null;
            $event->external_url = $eventData['detail_url'] ?? null;
            
            $event->order = 0;
            $event->is_active = true;
            $event->show_on_homepage = true;
            $event->is_featured = false;
            $event->register_required = false;
            
            $event->save();
            
            return $event;
            
        } catch (\Exception $e) {
            Log::error('Etkinlik kaydedilirken hata oluştu', [
                'error' => $e->getMessage(),
                'title' => $eventData['title'],
                'category_id' => $categoryId
            ]);
            
            throw $e;
        }
    }

    /**
     * Etkinlik için benzersiz bir ID oluşturur
     */
    protected function generateExternalId($title, $startDate, $location)
    {
        $rawId = $title . $startDate->format('Y-m-d') . ($location ?? '');
        return md5($rawId);
    }

    /**
     * Kategori için rastgele bir renk oluşturur
     */
    protected function generateRandomColor()
    {
        $colors = [
            '#3490dc', '#38c172', '#e3342f', '#f6993f', '#9561e2',
            '#f66d9b', '#6cb2eb', '#ffed4a', '#4dc0b5', '#6574cd',
        ];
        
        return $colors[array_rand($colors)];
    }

    /**
     * HTML içeriğinden toplam sayfa sayısını belirle
     */
    protected function getTotalPageCount($content)
    {
        try {
            $dom = new DOMDocument();
            @$dom->loadHTML(mb_convert_encoding($content, 'HTML-ENTITIES', 'UTF-8'));
            $xpath = new DOMXPath($dom);
            
            // Sayfalama elemanlarını bul
            $paginationItems = $xpath->query('//ul[contains(@class, "pagination")]/li');
            
            if ($paginationItems->length > 0) {
                // Sondan bir önceki öğe genellikle son sayfa numarasıdır
                $lastPageItem = $paginationItems->item($paginationItems->length - 2);
                if ($lastPageItem) {
                    $lastPage = trim($lastPageItem->textContent);
                    if (is_numeric($lastPage)) {
                        return (int)$lastPage;
                    }
                }
            }
            
            return 1;
        } catch (\Exception $e) {
            Log::error('Toplam sayfa sayısı belirlenirken hata oluştu', [
                'error' => $e->getMessage()
            ]);
            return 1;
        }
    }

    /**
     * Resim URL'sinden görsel indir ve kaydet
     */
    protected function downloadImage($imageUrl, $connectionOptions = null)
    {
        // URL boşsa null dön
        if (empty($imageUrl)) {
            return null;
        }
        
        // Data URI (base64) görsel ise atla
        if (strpos($imageUrl, 'data:image') === 0) {
            return null;
        }
        
        try {
            // URL'i temizle ve normalize et
            $imageUrl = trim($imageUrl);
            
            // Mükerrer domain kontrolü
            if (strpos($imageUrl, 'https://kultursanat.cankaya.bel.tr/https://') !== false) {
                $imageUrl = str_replace('https://kultursanat.cankaya.bel.tr/https://', 'https://', $imageUrl);
            }
            
            // URL encode karakterleri düzelt
            $imageUrl = str_replace(['%2F', '%3A'], ['/', ':'], $imageUrl);
            
            // HTTP seçeneklerini hazırla
            $httpOptions = [
                'verify' => false,
                'timeout' => 90,
                'connect_timeout' => 60,
                'headers' => [
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
                    'Cache-Control' => 'no-cache',
                    'Pragma' => 'no-cache'
                ]
            ];
            
            // Bağlantı seçeneklerini uygula (proxy desteği)
            if ($connectionOptions && is_array($connectionOptions)) {
                $connectionType = $connectionOptions['type'] ?? 'normal';
                
                if ($connectionType === 'ip' && !empty($connectionOptions['target_ip'])) {
                    $targetIp = $connectionOptions['target_ip'];
                    $parsedUrl = parse_url($imageUrl);
                    $domain = $parsedUrl['host'];
                    $port = $parsedUrl['scheme'] === 'https' ? 443 : 80;
                    
                    // CURL resolve seçeneği ekle
                    $httpOptions['curl'] = [
                        CURLOPT_RESOLVE => [
                            "$domain:$port:$targetIp"
                        ]
                    ];
                } elseif ($connectionType === 'proxy' && !empty($connectionOptions['proxy_url'])) {
                    $proxyUrl = $connectionOptions['proxy_url'];
                    $proxyUsername = $connectionOptions['proxy_username'] ?? null;
                    $proxyPassword = $connectionOptions['proxy_password'] ?? null;
                    
                    // Proxy kullanıcı adı ve şifresi varsa kimlik doğrulamalı proxy kullan
                    if ($proxyUsername && $proxyPassword) {
                        // Proxy URL'inden protokol ve host:port ayır
                        $parsedUrl = parse_url($proxyUrl);
                        $proxyHost = $parsedUrl['host'] ?? '';
                        $proxyPort = $parsedUrl['port'] ?? '';
                        
                        if ($proxyHost && $proxyPort) {
                            // Kimlik doğrulamalı proxy formatı: http://user:pass@host:port
                            $authenticatedProxy = $parsedUrl['scheme'] . '://' . $proxyUsername . ':' . $proxyPassword . '@' . $proxyHost . ':' . $proxyPort;
                            $httpOptions['proxy'] = $authenticatedProxy;
                        }
                    } else {
                        $httpOptions['proxy'] = $proxyUrl;
                    }
                }
            }
            
            // HTTP isteği gönder
            $response = Http::withOptions($httpOptions)->get($imageUrl);
            
            if ($response->status() !== 200) {
                Log::error('Görsel indirilemedi - HTTP hatası', [
                    'url' => $imageUrl,
                    'status' => $response->status()
                ]);
                return null;
            }
            
            $imageContent = $response->body();
            
            if (empty($imageContent)) {
                return null;
            }
            
            // Content-Type kontrolü
            $contentType = $response->header('Content-Type');
            if (!$contentType || strpos($contentType, 'image/') !== 0) {
                Log::error('İndirilen içerik bir görsel değil', [
                    'url' => $imageUrl,
                    'content_type' => $contentType
                ]);
                return null;
            }
            
            // Dosya uzantısını belirle
            $extension = 'jpg';
            if (strpos($contentType, 'image/png') !== false) {
                $extension = 'png';
            } elseif (strpos($contentType, 'image/gif') !== false) {
                $extension = 'gif';
            } elseif (strpos($contentType, 'image/webp') !== false) {
                $extension = 'webp';
            }
            
            // Dosya adı oluştur
            $filename = 'event_' . time() . '_' . Str::random(10) . '.' . $extension;
            
            // Public dizinini kontrol et
            $publicPath = public_path('events');
            if (!file_exists($publicPath)) {
                mkdir($publicPath, 0755, true);
            }
            
            // Dosyayı kaydet
            $filePath = $publicPath . '/' . $filename;
            if (!file_put_contents($filePath, $imageContent)) {
                Log::error('Dosya kaydedilemedi', ['path' => $filePath]);
                return null;
            }
            
            return 'events/' . $filename;
            
        } catch (\Exception $e) {
            Log::error('Görsel indirme ve kaydetme işlemi başarısız', [
                'url' => $imageUrl,
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }

    /**
     * Başlığı normalize eder (duplicate kontrolü için)
     */
    private function normalizeTitle($title)
    {
        // Türkçe karakterleri İngilizce karşılıklarına çevir
        $normalized = $this->turkishToEnglish($title);
        
        // Küçük harfe çevir
        $normalized = strtolower($normalized);
        
        // Gereksiz karakterleri temizle
        $normalized = preg_replace('/[^a-z0-9\s]/', '', $normalized);
        
        // Çoklu boşlukları tek boşluğa çevir
        $normalized = preg_replace('/\s+/', ' ', $normalized);
        
        // Başındaki ve sonundaki boşlukları temizle
        return trim($normalized);
    }

    /**
     * Türkçe karakterleri İngilizce karşılıklarına çevirir
     */
    private function turkishToEnglish($text)
    {
        $turkishChars = [
            'ç', 'Ç', 'ğ', 'Ğ', 'ı', 'İ', 'ö', 'Ö', 'ş', 'Ş', 'ü', 'Ü'
        ];
        
        $englishChars = [
            'c', 'C', 'g', 'G', 'i', 'I', 'o', 'O', 's', 'S', 'u', 'U'
        ];
        
        return str_replace($turkishChars, $englishChars, $text);
    }

    /**
     * Türkçe karakter destekli başlık formatlaması
     */
    protected function formatTurkishTitle($title)
    {
        if (empty($title)) {
            return $title;
        }
        
        // Önce boşlukları temizle
        $title = trim($title);
        $title = preg_replace('/\s+/', ' ', $title);
        
        // Türkçe karakter dönüşüm tablosu
        $upperToLower = [
            'A' => 'a', 'B' => 'b', 'C' => 'c', 'Ç' => 'ç', 'D' => 'd', 'E' => 'e', 'F' => 'f',
            'G' => 'g', 'Ğ' => 'ğ', 'H' => 'h', 'I' => 'ı', 'İ' => 'i', 'J' => 'j', 'K' => 'k',
            'L' => 'l', 'M' => 'm', 'N' => 'n', 'O' => 'o', 'Ö' => 'ö', 'P' => 'p', 'Q' => 'q',
            'R' => 'r', 'S' => 's', 'Ş' => 'ş', 'T' => 't', 'U' => 'u', 'Ü' => 'ü', 'V' => 'v',
            'W' => 'w', 'X' => 'x', 'Y' => 'y', 'Z' => 'z'
        ];
        
        $lowerToUpper = [
            'a' => 'A', 'b' => 'B', 'c' => 'C', 'ç' => 'Ç', 'd' => 'D', 'e' => 'E', 'f' => 'F',
            'g' => 'G', 'ğ' => 'Ğ', 'h' => 'H', 'ı' => 'I', 'i' => 'İ', 'j' => 'J', 'k' => 'K',
            'l' => 'L', 'm' => 'M', 'n' => 'N', 'o' => 'O', 'ö' => 'Ö', 'p' => 'P', 'q' => 'Q',
            'r' => 'R', 's' => 'S', 'ş' => 'Ş', 't' => 'T', 'u' => 'U', 'ü' => 'Ü', 'v' => 'V',
            'w' => 'W', 'x' => 'X', 'y' => 'Y', 'z' => 'Z'
        ];
        
        // Önce tüm karakterleri küçük harfe çevir
        $lowerTitle = '';
        for ($i = 0; $i < mb_strlen($title, 'UTF-8'); $i++) {
            $char = mb_substr($title, $i, 1, 'UTF-8');
            $lowerTitle .= isset($upperToLower[$char]) ? $upperToLower[$char] : $char;
        }
        
        // Kelimeleri ayır ve her kelimenin ilk harfini büyük yap
        $words = explode(' ', $lowerTitle);
        $formattedWords = [];
        
        foreach ($words as $word) {
            if (empty($word)) continue;
            
            $firstChar = mb_substr($word, 0, 1, 'UTF-8');
            $restChars = mb_substr($word, 1, null, 'UTF-8');
            
            // İlk harfi büyük yap
            $upperFirstChar = isset($lowerToUpper[$firstChar]) ? $lowerToUpper[$firstChar] : $firstChar;
            
            $formattedWords[] = $upperFirstChar . $restChars;
        }
        
        return implode(' ', $formattedWords);
    }

    /**
     * Tekil etkinlik ekleme
     */
    public function addSingleEventFromData($eventData)
    {
        try {
            Log::info('Tek etkinlik ekleme başladı', [
                'gelen_veri' => $eventData
            ]);
            
            // Başlık formatı düzenle - Türkçe karakter duyarlı
            if (isset($eventData['title'])) {
                $originalTitle = $eventData['title'];
                $eventData['title'] = $this->formatTurkishTitle($eventData['title']);
                
                Log::info('Başlık formatlaması', [
                    'orijinal' => $originalTitle,
                    'formatlanmış' => $eventData['title']
                ]);
            }
            
            // Kategori kontrolü
            $categoryName = $eventData['category'] ?? 'Genel';
            $category = $this->findOrCreateCategory($categoryName);
            
            // Slug oluştur
            $baseSlug = Str::slug($eventData['title'] ?? 'etkinlik');
            $uniqueSlug = $baseSlug . '-' . date('Ymd') . '-' . Str::random(5);
            
            // imageUrl parametresi varsa image_url'ye taşı
            if (isset($eventData['imageUrl']) && !isset($eventData['image_url'])) {
                $eventData['image_url'] = $eventData['imageUrl'];
                unset($eventData['imageUrl']);
            }
            
            // Tarih ve saat ayrıştırma
            $startDate = now(); // Varsayılan olarak şimdi
            
            if (isset($eventData['dateText']) && isset($eventData['timeText'])) {
                try {
                    $startDate = $this->parseDate($eventData['dateText'], $eventData['timeText']);
                } catch (\Exception $e) {
                    Log::warning('Tarih ayrıştırılamadı, varsayılan tarih kullanılıyor', [
                        'dateText' => $eventData['dateText'],
                        'timeText' => $eventData['timeText'],
                        'error' => $e->getMessage()
                    ]);
                }
            }
            
            // Event verilerini hazırla
            $processedData = [
                'title' => $eventData['title'] ?? 'İsimsiz Etkinlik',
                'description' => $eventData['description'] ?? 'Etkinlik açıklaması belirtilmemiş.',
                'start_date' => $startDate,
                'end_date' => isset($eventData['end_date']) ? Carbon::parse($eventData['end_date']) : (clone $startDate)->addHours(2),
                'location' => $eventData['location'] ?? 'Belirtilmemiş',
                'image_url' => $eventData['image_url'] ?? $eventData['imageUrl'] ?? null,
                'detail_url' => $eventData['detailUrl'] ?? null,
                'external_id' => $this->generateExternalId($eventData['title'] ?? 'etkinlik', $startDate, $eventData['location'] ?? ''),
                'slug' => $uniqueSlug,
                'category' => $categoryName
            ];
            
            Log::info('İşlenmiş etkinlik verisi', [
                'processed_data' => $processedData
            ]);
            
            // Mevcut etkinlik kontrolü
            $existingEvent = $this->checkExistingEvent($processedData);
            
            if ($existingEvent) {
                return [
                    'success' => false,
                    'message' => 'Bu etkinlik zaten mevcut.',
                    'event_id' => $existingEvent->id
                ];
            }
            
            // Etkinlik oluştur
            $event = $this->createEvent($processedData, $category->id, null);
            
            Log::info('Etkinlik başarıyla oluşturuldu', [
                'event_id' => $event->id,
                'event_title' => $event->title
            ]);
            
            return [
                'success' => true,
                'message' => 'Etkinlik başarıyla eklendi: ' . $event->title,
                'event_id' => $event->id,
                'event' => $event
            ];
            
        } catch (\Exception $e) {
            Log::error('Tek etkinlik ekleme hatası', [
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
                'event_data' => $eventData,
                'trace' => $e->getTraceAsString()
            ]);
            
            return [
                'success' => false,
                'message' => 'Etkinlik eklenirken hata oluştu: ' . $e->getMessage(),
                'event_id' => null
            ];
        }
    }
} 