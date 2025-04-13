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
    public function scrapeAllPages()
    {
        $baseUrl = 'https://kultursanat.cankaya.bel.tr/etkinlikler';
        $currentPage = 1;
        $hasNextPage = true;
        $totalEvents = 0;
        $newEvents = 0;
        $newCategories = 0;
        $errors = [];
        
        // İlk sayfayı çek ve toplam sayfa sayısını belirle
        $content = Http::get($baseUrl)->body();
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
            $pageResult = $this->scrapePage($url, $currentPage);
            
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
        
        Log::info('Etkinlik çekme işlemi tamamlandı', $results);
        
        return $results;
    }

    /**
     * Belirtilen sayfadaki etkinlikleri çek ve işle
     */
    public function scrapePage($url, $page = 1)
    {
        Log::info('Etkinlik sayfası çekiliyor', ['url' => $url, 'page' => $page]);
        
        try {
            // Sayfayı HTTP ile çek - SSL doğrulamasını devre dışı bırak
            $response = Http::withOptions([
                'verify' => false,
                'timeout' => 30
            ])->get($url);
            $content = $response->body();
            
            // DOM oluştur
            $dom = new DOMDocument();
            @$dom->loadHTML(mb_convert_encoding($content, 'HTML-ENTITIES', 'UTF-8'));
            $xpath = new DOMXPath($dom);
            
            // Sayfa yapısını analiz et
            $eventNodes = $xpath->query('//div[contains(@class, "event-card")]');
            $eventCount = $eventNodes->length;
            
            Log::info('Etkinlikler bulundu', ['count' => $eventCount, 'page' => $page]);
            
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
                        $event = $this->createEvent($eventData, $category->id);
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
        // Debug için tüm kartın HTML çıktısını logla
        $fullHtml = $eventNode->ownerDocument->saveHTML($eventNode);
        Log::info('Etkinlik kartı HTML yapısı', [
            'html' => $fullHtml
        ]);
        
        // Başlık - birden fazla alternatif sorgu deneyelim
        $titleNode = null;
        $titleSelectors = [
            './/h3[contains(@class, "card-title")]',  // Kart başlık stilinde h3
            './/div[contains(@class, "card-title")]', // Kart başlık stilinde div
            './/h2[@class="etkinlik-adi"]',           // Özel class ile h2
            './/div[contains(@class, "title")]',      // title sınıfı içeren div
            './/h4',                                  // h4 elementi
            './/h3',                                  // herhangi bir h3
            './/h2',                                  // herhangi bir h2
            './/strong',                              // kalın yazı
            './/b',                                   // kalın yazı (alternatif)
        ];
        
        // Her bir seçici ile başlık elementini bulmaya çalış
        foreach ($titleSelectors as $selector) {
            $node = $xpath->query($selector, $eventNode)->item(0);
            if ($node) {
                $titleNode = $node;
                Log::info('Başlık elementi bulundu', [
                    'selector' => $selector,
                    'text' => trim($node->textContent)
                ]);
                break;
            }
        }

        // Başlık bulunamadıysa, alternatif yöntem dene - belki sayfadaki tüm metinleri kontrol et
        if (!$titleNode) {
            // Olası başlık olabilecek tüm metinleri içeren elementleri bul
            $textNodes = $xpath->query('.//*[not(self::script) and not(self::style)]', $eventNode);
            foreach ($textNodes as $node) {
                $text = trim($node->textContent);
                if (strlen($text) > 5 && strlen($text) < 100) {
                    Log::info('Olası başlık metni', ['text' => $text]);
                }
            }
            
            // Son çare - ilk anlamlı metin
            $titleNode = $textNodes->item(0);
        }
        
        $title = $titleNode ? trim($titleNode->textContent) : 'İsimsiz Etkinlik';
        
        // Başlıktaki gereksiz karakterleri temizle
        $title = preg_replace('/\s+/', ' ', $title);
        $title = trim($title);
        
        // Başlığı ilk harfleri büyük olacak şekilde düzenle
        $title = mb_convert_case($title, MB_CASE_TITLE, "UTF-8");
        Log::info('Başlık düzenlendi: İlk harfler büyük yapıldı', [
            'original' => trim($titleNode->textContent),
            'formatted' => $title
        ]);
        
        // Çok kısa başlıkları atla
        if (strlen($title) < 3) {
            $title = 'Etkinlik ' . time() . rand(100, 999);
        }
        
        // Kategori (tür) - bu kısmı olduğu gibi bırak
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
        
        // Detay linki
        $linkNode = $xpath->query('.//a[contains(@class, "event-link")]', $eventNode)->item(0);
        if (!$linkNode) {
            $linkNodes = $xpath->query('.//a', $eventNode);
            if ($linkNodes->length > 0) {
                $linkNode = $linkNodes->item(0);
            }
        }
        $detailUrl = $linkNode ? $linkNode->getAttribute('href') : null;
        
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
        
        Log::info('Etkinlik verisi çıkarıldı', [
            'title' => $title,
            'category' => $category,
            'dateText' => $dateText,
            'timeText' => $timeText,
            'location' => $location,
            'imageUrl' => $imageUrl,
            'detailUrl' => $detailUrl
        ]);
        
        return [
            'title' => $title,
            'slug' => $uniqueSlug, // Benzersiz slug kullan
            'category' => $category,
            'description' => $description,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'location' => $location,
            'image_url' => $imageUrl,
            'detail_url' => $detailUrl,
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
                'timeout' => 30
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
        // Benzersiz bir external_id ile kontrol et
        return Event::where('slug', $eventData['slug'])
            ->where('start_date', $eventData['start_date'])
            ->first();
    }

    /**
     * Yeni bir etkinlik oluşturur
     */
    protected function createEvent($eventData, $categoryId)
    {
        // Log başlangıcı
        Log::info('createEvent metoduna giriş - Etkinlik oluşturma başlıyor', [
            'event_title' => $eventData['title'],
            'category_id' => $categoryId
        ]);
        
        // Görsel URL'sinden görseli indir
        $coverImage = null;
        if (!empty($eventData['image_url'])) {
            Log::info('Etkinlik için görsel indirme başlıyor', [
                'event_title' => $eventData['title'],
                'image_url' => $eventData['image_url'],
                'image_url_type' => gettype($eventData['image_url']),
                'image_url_empty' => empty($eventData['image_url']) ? 'evet' : 'hayır'
            ]);
            
            // Görsel URL'sini kontrol et - base64 vb. değilse indir
            if (strpos($eventData['image_url'], 'data:image') === 0) {
                Log::warning('Base64 kodlu görsel, indirme atlanıyor', [
                    'image_url_prefix' => substr($eventData['image_url'], 0, 30) . '...'
                ]);
            } else {
                // Tüm boşlukları kodla ve URL'yi temizle
                $cleanImageUrl = str_replace(' ', '%20', trim($eventData['image_url']));
                
                // Mükerrer domain kontrolü
                if (strpos($cleanImageUrl, 'https://kultursanat.cankaya.bel.tr/https://') !== false) {
                    $originalUrl = $cleanImageUrl;
                    $cleanImageUrl = str_replace('https://kultursanat.cankaya.bel.tr/https://', 'https://', $cleanImageUrl);
                    
                    Log::info('Mükerrer domain temizlendi', [
                        'original' => $originalUrl,
                        'cleaned' => $cleanImageUrl
                    ]);
                }
                
                $coverImage = $this->downloadImage($cleanImageUrl);
                
                if ($coverImage) {
                    Log::info('Görsel başarıyla indirildi ve kaydedildi', [
                        'relative_path' => $coverImage
                    ]);
                } else {
                    Log::warning('Görsel indirilemedi veya kaydedilemedi, URL olduğu gibi kullanılacak', [
                        'image_url' => $cleanImageUrl
                    ]);
                }
            }
        } else {
            Log::warning('Etkinlik için görsel URL\'i bulunamadı', [
                'event_title' => $eventData['title']
            ]);
        }

        // Slug'ı benzersiz hale getir - başlıkla beraber tarih ve rastgele karakter ekle
        $uniqueSlug = Str::slug($eventData['title']) . '-' . date('Ymd') . '-' . Str::random(5);
        Log::info('Benzersiz slug oluşturuldu', [
            'original_slug' => $eventData['slug'] ?? Str::slug($eventData['title']),
            'unique_slug' => $uniqueSlug
        ]);

        try {
            $event = new Event();
            $event->title = $eventData['title'];
            $event->slug = $uniqueSlug; // Benzersiz slug kullan
            $event->description = $eventData['description'] ?? 'Etkinlik detayları için tıklayınız.';
            $event->category_id = $categoryId;
            $event->start_date = $eventData['start_date'] ?? now();
            $event->end_date = $eventData['end_date'] ?? ($eventData['start_date'] ? (clone $eventData['start_date'])->addHours(2) : now()->addHours(2));
            $event->location = $eventData['location'] ?? 'Belirtilmemiş';
            
            // İndirilen görsel varsa kaydet, yoksa URL'yi direkt kaydet
            $event->cover_image = $coverImage ?? $eventData['image_url'] ?? null;
            
            $event->order = 0;
            $event->is_active = true;
            $event->show_on_homepage = true;
            $event->is_featured = false;
            $event->register_required = false;
            
            // Kaydetmeden önce verileri logla
            Log::info('Etkinlik veritabanına kaydediliyor', [
                'title' => $event->title,
                'slug' => $event->slug,
                'category_id' => $event->category_id,
                'start_date' => $event->start_date->toDateTimeString(),
                'end_date' => $event->end_date ? $event->end_date->toDateTimeString() : null,
                'location' => $event->location,
                'cover_image' => $event->cover_image,
                'cover_image_type' => gettype($event->cover_image)
            ]);
            
            $event->save();
            
            Log::info('Etkinlik başarıyla kaydedildi', [
                'event_id' => $event->id,
                'event_title' => $event->title,
                'cover_image' => $event->cover_image,
                'cover_image_type' => gettype($event->cover_image),
                'cover_image_empty' => empty($event->cover_image) ? 'evet' : 'hayır'
            ]);
            
            return $event;
            
        } catch (\Exception $e) {
            Log::error('Etkinlik kaydedilirken hata oluştu', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
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
            '#3490dc', // Mavi
            '#38c172', // Yeşil
            '#e3342f', // Kırmızı
            '#f6993f', // Turuncu
            '#9561e2', // Mor
            '#f66d9b', // Pembe
            '#6cb2eb', // Açık Mavi
            '#ffed4a', // Sarı
            '#4dc0b5', // Turkuaz
            '#6574cd', // İndigo
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
            
            // Sayfalama bulunamazsa veya beklenen formatta değilse 1 döndür
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
    protected function downloadImage($imageUrl)
    {
        // URL boşsa null dön
        if (empty($imageUrl)) {
            Log::warning('Resim URL\'i boş, indirme atlanıyor');
            return null;
        }
        
        // Data URI (base64) görsel ise atla
        if (strpos($imageUrl, 'data:image') === 0) {
            Log::warning('Base64 kodlu görsel, indirme atlanıyor');
            return null;
        }
        
        try {
            // URL'i temizle ve normalize et
            $imageUrl = trim($imageUrl);
            
            // Debug için URL'yi logla
            Log::info('İndirmeye çalışılan resim URL', [
                'url' => $imageUrl,
                'length' => strlen($imageUrl)
            ]);
            
            // Mükerrer domain kontrolü: https://kultursanat.cankaya.bel.tr/https://
            if (strpos($imageUrl, 'https://kultursanat.cankaya.bel.tr/https://') !== false) {
                $originalUrl = $imageUrl;
                $imageUrl = str_replace('https://kultursanat.cankaya.bel.tr/https://', 'https://', $imageUrl);
                
                Log::info('Mükerrer domain tespit edildi ve düzeltildi', [
                    'original' => $originalUrl,
                    'corrected' => $imageUrl
                ]);
            }
            
            // Görsel indirme başlat
            Log::info('Görsel indirme başladı', ['url' => $imageUrl]);
            
            // URL kodlamaları düzelt (boşlukları %20 ile değiştir)
            $imageUrl = str_replace(' ', '%20', $imageUrl);
            Log::info('URL kodlamaları düzeltildi', ['encoded_url' => $imageUrl]);
            
            // HTTP isteği yaparak görseli indir - SSL doğrulamasını devre dışı bırak
            Log::info('Görsel indirme HTTP isteği başlatılıyor');
            $response = Http::withOptions([
                'verify' => false,
                'timeout' => 30
            ])->get($imageUrl);
            
            // İndirme başarısız olursa log kaydı oluştur ve null dön
            if ($response->failed()) {
                Log::error('Görsel indirilemedi - HTTP hatası', [
                    'url' => $imageUrl,
                    'status_code' => $response->status(),
                    'reason' => $response->reason()
                ]);
                return null;
            }
            
            $imageContent = $response->body();
            $contentLength = strlen($imageContent);
            $contentType = $response->header('Content-Type');
            
            Log::info('Görsel içeriği alındı', [
                'url' => $imageUrl,
                'content_length' => $contentLength,
                'content_type' => $contentType
            ]);
            
            // İçerik türü kontrolü - HTML geliyorsa resim değildir
            if ($contentType && strpos($contentType, 'text/html') !== false) {
                Log::error('İndirilen içerik bir görsel değil', [
                    'url' => $imageUrl, 
                    'content_type' => $contentType,
                    'content_start' => substr($imageContent, 0, 100)
                ]);
                return null;
            }
            
            // Dosya adı oluştur
            $extension = 'jpg'; // Varsayılan uzantı
            if ($contentType) {
                if (strpos($contentType, 'image/jpeg') !== false || strpos($contentType, 'image/jpg') !== false) {
                    $extension = 'jpg';
                } elseif (strpos($contentType, 'image/png') !== false) {
                    $extension = 'png';
                } elseif (strpos($contentType, 'image/gif') !== false) {
                    $extension = 'gif';
                } elseif (strpos($contentType, 'image/webp') !== false) {
                    $extension = 'webp';
                }
            }
            
            $filename = 'event_' . time() . '_' . Str::random(10) . '.' . $extension;
            Log::info('Dosya adı oluşturuldu', ['filename' => $filename, 'extension' => $extension]);
            
            // Public dizinin var olup olmadığını kontrol et
            $publicPath = public_path('events');
            if (!file_exists($publicPath)) {
                $mkdirResult = mkdir($publicPath, 0755, true);
                Log::info('Public dizini oluşturma', [
                    'path' => $publicPath, 
                    'result' => $mkdirResult ? 'başarılı' : 'başarısız'
                ]);
            } else {
                Log::info('Public events dizini zaten var', ['path' => $publicPath]);
            }
            
            // Dosyayı kaydet
            $filePath = $publicPath . '/' . $filename;
            $writeResult = file_put_contents($filePath, $imageContent);
            
            if ($writeResult === false) {
                Log::error('Dosya kaydedilemedi', ['path' => $filePath]);
                return null;
            }
            
            Log::info('Dosya public dizine kaydedildi', [
                'path' => $filePath,
                'result' => 'başarılı',
                'bytes_written' => $writeResult
            ]);
            
            // Başarılı bir şekilde kaydedildiyse, relative path döndür
            $relativePath = 'events/' . $filename;
            Log::info('Resim public dizine başarıyla kaydedildi', [
                'path' => $filePath,
                'relative_path' => $relativePath,
                'url' => url($relativePath)
            ]);
            
            return $relativePath;
            
        } catch (\Exception $e) {
            Log::error('Görsel indirme ve kaydetme işlemi başarısız', [
                'url' => $imageUrl,
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }

    /**
     * Önizlemeden gelen tek bir etkinliği ekler
     */
    public function addSingleEventFromData($eventData)
    {
        try {
            // Verileri kontrol et
            if (empty($eventData['title'])) {
                return [
                    'success' => false,
                    'message' => 'Etkinlik başlığı bulunamadı'
                ];
            }

            // Başlığı ilk harfleri büyük olacak şekilde düzenle
            if (!empty($eventData['title'])) {
                $originalTitle = $eventData['title'];
                $eventData['title'] = mb_convert_case($eventData['title'], MB_CASE_TITLE, "UTF-8");
                Log::info('addSingleEventFromData: Başlık formatı düzenlendi', [
                    'original' => $originalTitle,
                    'formatted' => $eventData['title']
                ]);
            }

            // Kategoriyi kontrol et
            $category = $this->findOrCreateCategory($eventData['category'] ?? 'Genel');
            
            // Tarihi işle
            $parsedDate = null;
            
            // Öncelikle direkt dateText ve timeText varsa onları kullan
            if (!empty($eventData['dateText'])) {
                $parsedDate = $this->parseDate($eventData['dateText'], $eventData['timeText'] ?? '');
            }
            
            // Eğer geçerli bir tarih elde edemediyse, şimdiyi kullan
            if (!$parsedDate || !($parsedDate instanceof \Carbon\Carbon)) {
                $parsedDate = now();
            }
            
            $eventData['start_date'] = $parsedDate;
            $eventData['end_date'] = (clone $parsedDate)->addHours(2);
            
            // Benzersiz slug oluştur
            $eventData['slug'] = Str::slug($eventData['title']) . '-' . date('Ymd') . '-' . Str::random(5);
            Log::info('addSingleEventFromData: Benzersiz slug oluşturuldu', [
                'title' => $eventData['title'],
                'slug' => $eventData['slug']
            ]);
            
            // Açıklama kontrolü
            if (empty($eventData['description'])) {
                $eventData['description'] = 'Etkinlik detayları için tıklayınız.';
            }
            
            // Resim URL'si kontrolü - imageUrl parametresini image_url'e taşı
            if (!empty($eventData['imageUrl'])) {
                Log::info('imageUrl parametresi image_url parametresine taşınıyor', [
                    'imageUrl' => $eventData['imageUrl']
                ]);
                $eventData['image_url'] = $eventData['imageUrl'];
            }
            
            // Görsel URL'sini kontrol et ve düzelt
            if (!empty($eventData['image_url']) && strpos($eventData['image_url'], 'data:image') !== 0) {
                // Mükerrer domain ve URL formatı düzeltme işlemleri
                if (strpos($eventData['image_url'], 'https://kultursanat.cankaya.bel.tr/https://') !== false) {
                    $eventData['image_url'] = str_replace('https://kultursanat.cankaya.bel.tr/https://', 'https://', $eventData['image_url']);
                }
                
                // URL'deki boşlukları kodla
                if (strpos($eventData['image_url'], ' ') !== false) {
                    $eventData['image_url'] = str_replace(' ', '%20', $eventData['image_url']);
                }
            }
            
            // Etkinliğin mevcut olup olmadığını kontrol et
            $existingEvent = $this->checkExistingEvent($eventData);
            
            if ($existingEvent) {
                return [
                    'success' => false, 
                    'message' => 'Bu etkinlik zaten sisteme eklenmiş',
                    'event_id' => $existingEvent->id
                ];
            }
            
            // Etkinliği oluştur
            $event = $this->createEvent($eventData, $category->id);
            
            return [
                'success' => true,
                'message' => 'Etkinlik başarıyla eklendi',
                'event_id' => $event->id,
                'event_title' => $event->title
            ];
            
        } catch (\Exception $e) {
            Log::error('Tek etkinlik ekleme hatası', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return [
                'success' => false,
                'message' => 'Sistem hatası: ' . $e->getMessage()
            ];
        }
    }
} 