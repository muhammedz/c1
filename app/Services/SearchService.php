<?php

namespace App\Services;

use App\Models\Service;
use App\Models\News;
use App\Models\Page;
use App\Models\Project;
use App\Models\GuidePlace;
use App\Models\CankayaHouse;
use App\Models\Mudurluk;
use App\Models\Archive;
use App\Models\SearchPriorityLink;
use Illuminate\Support\Collection;

class SearchService
{
    /**
     * Gelişmiş arama işlemi - Kelime sınırları ile optimize edilmiş
     * 
     * @param string $query
     * @return array
     */
    public function search(string $query): array
    {
        if (empty($query) || strlen(trim($query)) < 3) {
            return [
                'priority_links' => collect(),
                'services' => collect(),
                'news' => collect(),
                'pages' => collect(),
                'projects' => collect(),
                'guides' => collect(),
                'cankaya_houses' => collect(),
                'mudurlukler' => collect(),
                'archives' => collect(),
                'total' => 0,
                'min_length_error' => strlen(trim($query)) > 0 && strlen(trim($query)) < 3
            ];
        }

        // Arama sorgusunu normalize et
        $normalizedQuery = $this->normalizeQuery($query);
        
        // Priority Links'i getir (önce)
        $priorityLinks = SearchPriorityLink::getMatchingLinks($query);
        
        // Farklı arama stratejileri uygula
        $services = $this->searchServices($normalizedQuery, $query);
        $news = $this->searchNews($normalizedQuery, $query);
        $pages = $this->searchPages($normalizedQuery, $query);
        $projects = $this->searchProjects($normalizedQuery, $query);
        $guides = $this->searchGuides($normalizedQuery, $query);
        $cankayaHouses = $this->searchCankayaHouses($normalizedQuery, $query);
        $mudurlukler = $this->searchMudurlukler($normalizedQuery, $query);
        $archives = $this->searchArchives($normalizedQuery, $query);
        
        $totalCount = $priorityLinks->count() + $services->count() + $news->count() + $pages->count() + $projects->count() + 
                     $guides->count() + $cankayaHouses->count() + $mudurlukler->count() + $archives->count();
        
        return [
            'priority_links' => $priorityLinks,
            'services' => $services,
            'news' => $news,
            'pages' => $pages,
            'projects' => $projects,
            'guides' => $guides,
            'cankaya_houses' => $cankayaHouses,
            'mudurlukler' => $mudurlukler,
            'archives' => $archives,
            'total' => $totalCount,
            'min_length_error' => false
        ];
    }
    
    /**
     * Sorguyu normalize et (Türkçe karakterler, küçük harf)
     * 
     * @param string $query
     * @return string
     */
    private function normalizeQuery(string $query): string
    {
        $normalized = mb_strtolower($query, 'UTF-8');
        $normalized = str_replace(['ı', 'ğ', 'ü', 'ş', 'ö', 'ç'], ['i', 'g', 'u', 's', 'o', 'c'], $normalized);
        return trim($normalized);
    }
    
    /**
     * Kelime sınırları ile REGEXP oluştur - "kent" != "Başkent"
     * 
     * @param string $word
     * @return string
     */
    private function createWordBoundaryRegex(string $word): string
    {
        // MySQL word boundaries: [[:<:]] ve [[:>:]]
        return "[[:<:]]" . preg_quote($word, '/') . "[[:>:]]";
    }
    
    /**
     * Hizmetlerde arama yap - İyileştirilmiş algoritma
     * 
     * @param string $normalizedQuery
     * @param string $originalQuery
     * @return Collection
     */
    private function searchServices(string $normalizedQuery, string $originalQuery): Collection
    {
        $results = collect();
        
        // 1. Scout ile tam arama
        try {
            $scoutResults = Service::search($normalizedQuery)
                ->where('status', 'published')
                ->get();
            $results = $results->merge($scoutResults);
        } catch (\Exception $e) {
            // Scout hatası durumunda devam et
        }
        
        // 2. Tam cümle/ifade arama (öncelik)
        $exactResults = Service::where('status', 'published')
            ->where(function($q) use ($originalQuery, $normalizedQuery) {
                $q->where('title', 'LIKE', "%{$originalQuery}%")
                  ->orWhere('title', 'LIKE', "%{$normalizedQuery}%");
            })
            ->get();
        
        $existingIds = $results->pluck('id')->toArray();
        $additionalResults = $exactResults->whereNotIn('id', $existingIds);
        $results = $results->merge($additionalResults);
        
        // 3. Kelime sınırları ile arama (daha kesin) - Sadece yeterli sonuç yoksa
        if ($results->count() < 3) {
            $words = explode(' ', $normalizedQuery);
            if (count($words) > 1) {
                foreach ($words as $word) {
                    // Minimum 5 karakter ve blacklist kontrolü (daha katı)
                    if (strlen($word) >= 5 && !$this->isBlacklistedWord($word)) {
                        $wordRegex = $this->createWordBoundaryRegex($word);
                        
                        $wordResults = Service::where('status', 'published')
                            ->where('title', 'REGEXP', $wordRegex)
                            ->get();
                        
                        $existingIds = $results->pluck('id')->toArray();
                        $additionalResults = $wordResults->whereNotIn('id', $existingIds);
                        $results = $results->merge($additionalResults);
                    }
                }
            }
        }
        
        // 4. Fallback: LIKE arama - sadece çok az sonuç varsa
        if ($results->count() < 2) {
            $words = explode(' ', $normalizedQuery);
            foreach ($words as $word) {
                if (strlen($word) >= 4 && !$this->isBlacklistedWord($word)) {
                    $wordResults = Service::where('status', 'published')
                        ->where('title', 'LIKE', "%{$word}%")
                        ->limit(3)
                        ->get();
                    
                    $existingIds = $results->pluck('id')->toArray();
                    $additionalResults = $wordResults->whereNotIn('id', $existingIds);
                    $results = $results->merge($additionalResults);
                }
            }
        }
        
        return $results;
    }
    
    /**
     * Haberlerde arama yap - İyileştirilmiş algoritma
     * 
     * @param string $normalizedQuery
     * @param string $originalQuery
     * @return Collection
     */
    private function searchNews(string $normalizedQuery, string $originalQuery): Collection
    {
        $results = collect();
        
        // 1. Scout ile tam arama
        try {
            $scoutResults = News::search($normalizedQuery)
                ->where('status', 'published')
                ->get();
            $results = $results->merge($scoutResults);
        } catch (\Exception $e) {
            // Scout hatası durumunda devam et
        }
        
        // 2. Tam cümle/ifade arama (öncelik)
        $exactResults = News::where('status', 'published')
            ->where(function($q) use ($originalQuery, $normalizedQuery) {
                $q->where('title', 'LIKE', "%{$originalQuery}%")
                  ->orWhere('title', 'LIKE', "%{$normalizedQuery}%");
            })
            ->get();
        
        $existingIds = $results->pluck('id')->toArray();
        $additionalResults = $exactResults->whereNotIn('id', $existingIds);
        $results = $results->merge($additionalResults);
        
        // 3. Kelime sınırları ile arama - Sadece yeterli sonuç yoksa
        if ($results->count() < 3) {
            $words = explode(' ', $normalizedQuery);
            if (count($words) > 1) {
                foreach ($words as $word) {
                    if (strlen($word) >= 5 && !$this->isBlacklistedWord($word)) {
                        $wordRegex = $this->createWordBoundaryRegex($word);
                        
                        $wordResults = News::where('status', 'published')
                            ->where('title', 'REGEXP', $wordRegex)
                            ->get();
                        
                        $existingIds = $results->pluck('id')->toArray();
                        $additionalResults = $wordResults->whereNotIn('id', $existingIds);
                        $results = $results->merge($additionalResults);
                    }
                }
            }
        }
        
        // 4. Fallback: LIKE arama
        if ($results->count() < 2) {
            $words = explode(' ', $normalizedQuery);
            foreach ($words as $word) {
                if (strlen($word) >= 4 && !$this->isBlacklistedWord($word)) {
                    $wordResults = News::where('status', 'published')
                        ->where('title', 'LIKE', "%{$word}%")
                        ->limit(3)
                        ->get();
                    
                    $existingIds = $results->pluck('id')->toArray();
                    $additionalResults = $wordResults->whereNotIn('id', $existingIds);
                    $results = $results->merge($additionalResults);
                }
            }
        }
        
        return $results;
    }
    
    /**
     * Sayfalarda arama yap - İyileştirilmiş algoritma
     * 
     * @param string $normalizedQuery
     * @param string $originalQuery
     * @return Collection
     */
    private function searchPages(string $normalizedQuery, string $originalQuery): Collection
    {
        $results = collect();
        
        // 1. Tam cümle/ifade arama (öncelik)
        $exactResults = Page::published()
            ->where(function($q) use ($originalQuery, $normalizedQuery) {
                $q->where('title', 'LIKE', "%{$originalQuery}%")
                  ->orWhere('title', 'LIKE', "%{$normalizedQuery}%")
                  ->orWhere('content', 'LIKE', "%{$originalQuery}%")
                  ->orWhere('content', 'LIKE', "%{$normalizedQuery}%")
                  ->orWhere('summary', 'LIKE', "%{$originalQuery}%")
                  ->orWhere('summary', 'LIKE', "%{$normalizedQuery}%");
            })
            ->get();
        
        $results = $results->merge($exactResults);
        
        // 2. Kelime sınırları ile arama - Sadece yeterli sonuç yoksa
        if ($results->count() < 3) {
            $words = explode(' ', $normalizedQuery);
            if (count($words) > 1) {
                foreach ($words as $word) {
                    if (strlen($word) >= 5 && !$this->isBlacklistedWord($word)) {
                        $wordRegex = $this->createWordBoundaryRegex($word);
                        
                        $wordResults = Page::published()
                            ->where(function($q) use ($wordRegex) {
                                $q->where('title', 'REGEXP', $wordRegex)
                                  ->orWhere('summary', 'REGEXP', $wordRegex);
                            })
                            ->get();
                        
                        $existingIds = $results->pluck('id')->toArray();
                        $additionalResults = $wordResults->whereNotIn('id', $existingIds);
                        $results = $results->merge($additionalResults);
                    }
                }
            }
        }
        
        return $results;
    }
    
    /**
     * Projelerde arama yap - İyileştirilmiş algoritma
     */
    private function searchProjects(string $normalizedQuery, string $originalQuery): Collection
    {
        $results = collect();
        
        // Tam ifade arama
        $exactResults = Project::where('is_active', true)
            ->where(function($q) use ($originalQuery, $normalizedQuery) {
                $q->where('title', 'LIKE', "%{$originalQuery}%")
                  ->orWhere('title', 'LIKE', "%{$normalizedQuery}%");
            })
            ->with('category')
            ->get();
        
        $results = $results->merge($exactResults);
        
        // Kelime sınırları ile arama
        if ($results->count() < 3) {
            $words = explode(' ', $normalizedQuery);
            foreach ($words as $word) {
                if (strlen($word) >= 5 && !$this->isBlacklistedWord($word)) {
                    $wordRegex = $this->createWordBoundaryRegex($word);
                    
                    $wordResults = Project::where('is_active', true)
                        ->where('title', 'REGEXP', $wordRegex)
                        ->with('category')
                        ->get();
                    
                    $existingIds = $results->pluck('id')->toArray();
                    $additionalResults = $wordResults->whereNotIn('id', $existingIds);
                    $results = $results->merge($additionalResults);
                }
            }
        }
        
        return $results;
    }
    
    /**
     * Rehber yerlerinde arama yap - İyileştirilmiş algoritma
     */
    private function searchGuides(string $normalizedQuery, string $originalQuery): Collection
    {
        $results = collect();
        
        // Tam ifade arama
        $exactResults = GuidePlace::where('is_active', true)
            ->where(function($q) use ($originalQuery, $normalizedQuery) {
                $q->where('title', 'LIKE', "%{$originalQuery}%")
                  ->orWhere('title', 'LIKE', "%{$normalizedQuery}%");
            })
            ->with('category')
            ->get();
        
        $results = $results->merge($exactResults);
        
        // Kelime sınırları ile arama
        if ($results->count() < 3) {
            $words = explode(' ', $normalizedQuery);
            foreach ($words as $word) {
                if (strlen($word) >= 5 && !$this->isBlacklistedWord($word)) {
                    $wordRegex = $this->createWordBoundaryRegex($word);
                    
                    $wordResults = GuidePlace::where('is_active', true)
                        ->where('title', 'REGEXP', $wordRegex)
                        ->with('category')
                        ->get();
                    
                    $existingIds = $results->pluck('id')->toArray();
                    $additionalResults = $wordResults->whereNotIn('id', $existingIds);
                    $results = $results->merge($additionalResults);
                }
            }
        }
        
        return $results;
    }
    
    /**
     * Çankaya Evlerinde arama yap - İyileştirilmiş algoritma
     */
    private function searchCankayaHouses(string $normalizedQuery, string $originalQuery): Collection
    {
        $results = collect();
        
        // Tam ifade arama
        $exactResults = CankayaHouse::where('status', 'active')
            ->where(function($q) use ($originalQuery, $normalizedQuery) {
                $q->where('name', 'LIKE', "%{$originalQuery}%")
                  ->orWhere('name', 'LIKE', "%{$normalizedQuery}%");
            })
            ->get();
        
        $results = $results->merge($exactResults);
        
        // Kelime sınırları ile arama
        if ($results->count() < 3) {
            $words = explode(' ', $normalizedQuery);
            foreach ($words as $word) {
                if (strlen($word) >= 5 && !$this->isBlacklistedWord($word)) {
                    $wordRegex = $this->createWordBoundaryRegex($word);
                    
                    $wordResults = CankayaHouse::where('status', 'active')
                        ->where('name', 'REGEXP', $wordRegex)
                        ->get();
                    
                    $existingIds = $results->pluck('id')->toArray();
                    $additionalResults = $wordResults->whereNotIn('id', $existingIds);
                    $results = $results->merge($additionalResults);
                }
            }
        }
        
        return $results;
    }
    
    /**
     * Müdürlüklerde arama yap - İyileştirilmiş algoritma
     */
    private function searchMudurlukler(string $normalizedQuery, string $originalQuery): Collection
    {
        $results = collect();
        
        // Tam ifade arama
        $exactResults = Mudurluk::where('is_active', true)
            ->where(function($q) use ($originalQuery, $normalizedQuery) {
                $q->where('name', 'LIKE', "%{$originalQuery}%")
                  ->orWhere('name', 'LIKE', "%{$normalizedQuery}%");
            })
            ->get();
        
        $results = $results->merge($exactResults);
        
        // Kelime sınırları ile arama
        if ($results->count() < 3) {
            $words = explode(' ', $normalizedQuery);
            foreach ($words as $word) {
                if (strlen($word) >= 5 && !$this->isBlacklistedWord($word)) {
                    $wordRegex = $this->createWordBoundaryRegex($word);
                    
                    $wordResults = Mudurluk::where('is_active', true)
                        ->where('name', 'REGEXP', $wordRegex)
                        ->get();
                    
                    $existingIds = $results->pluck('id')->toArray();
                    $additionalResults = $wordResults->whereNotIn('id', $existingIds);
                    $results = $results->merge($additionalResults);
                }
            }
        }
        
        // Müdürlük dosyalarında arama
        $searchSettings = \App\Models\SearchSetting::getSettings();
        if ($searchSettings->search_in_mudurluk_files) {
            $fileResults = $this->searchMudurlukFiles($normalizedQuery, $originalQuery);
            $results = $results->merge($fileResults);
        }
        
        return $results;
    }
    
    /**
     * Arşivlerde arama yap - İyileştirilmiş algoritma
     */
    private function searchArchives(string $normalizedQuery, string $originalQuery): Collection
    {
        $results = collect();
        
        // Scout ve tam ifade arama
        try {
            $scoutResults = Archive::search($normalizedQuery)
                ->where('status', 'published')
                ->get();
            $results = $results->merge($scoutResults);
        } catch (\Exception $e) {
            // Scout hatası durumunda devam et
        }
        
        $exactResults = Archive::where('status', 'published')
            ->where(function($q) use ($originalQuery, $normalizedQuery) {
                $q->where('title', 'LIKE', "%{$originalQuery}%")
                  ->orWhere('title', 'LIKE', "%{$normalizedQuery}%");
            })
            ->get();
        
        $existingIds = $results->pluck('id')->toArray();
        $additionalResults = $exactResults->whereNotIn('id', $existingIds);
        $results = $results->merge($additionalResults);
        
        // Kelime sınırları ile arama
        if ($results->count() < 3) {
            $words = explode(' ', $normalizedQuery);
            foreach ($words as $word) {
                if (strlen($word) >= 5 && !$this->isBlacklistedWord($word)) {
                    $wordRegex = $this->createWordBoundaryRegex($word);
                    
                    $wordResults = Archive::where('status', 'published')
                        ->where('title', 'REGEXP', $wordRegex)
                        ->get();
                    
                    $existingIds = $results->pluck('id')->toArray();
                    $additionalResults = $wordResults->whereNotIn('id', $existingIds);
                    $results = $results->merge($additionalResults);
                }
            }
        }
        
        return $results;
    }
    
    /**
     * Müdürlük dosyalarında arama yap - İyileştirilmiş algoritma
     */
    private function searchMudurlukFiles(string $normalizedQuery, string $originalQuery): Collection
    {
        $results = collect();
        
        try {
            // Tam ifade arama
            $fileResults = \App\Models\MudurlukFile::with('mudurluk')
                ->whereHas('mudurluk', function($q) {
                    $q->where('is_active', true);
                })
                ->where(function($q) use ($originalQuery, $normalizedQuery) {
                    $q->where('title', 'LIKE', "%{$originalQuery}%")
                      ->orWhere('title', 'LIKE', "%{$normalizedQuery}%")
                      ->orWhere('file_name', 'LIKE', "%{$originalQuery}%")
                      ->orWhere('file_name', 'LIKE', "%{$normalizedQuery}%");
                })
                ->get();
            
            // Dosya sonuçlarını özel format ile hazırla
            foreach ($fileResults as $file) {
                if ($file->mudurluk) {
                    $extension = strtoupper(pathinfo($file->file_name, PATHINFO_EXTENSION));
                    
                    $fileItem = (object)[
                        'id' => 'mudurluk_file_' . $file->id,
                        'type' => 'mudurluk_file',
                        'title' => $file->mudurluk->name . ' - ' . $file->title,
                        'url' => '/mudurlukler/' . $file->mudurluk->slug,
                        'description' => 'Müdürlük Dosyası: ' . $file->title,
                        'mudurluk_name' => $file->mudurluk->name,
                        'file_title' => $file->title,
                        'file_name' => $file->file_name,
                        'file_extension' => $extension,
                        'file_path' => $file->file_path,
                        'original_file' => $file
                    ];
                    
                    $results->push($fileItem);
                }
            }
            
        } catch (\Exception $e) {
            \Log::error('Müdürlük dosyalarında arama hatası: ' . $e->getMessage());
        }
        
        return $results;
    }
    
    /**
     * İyileştirilmiş Blacklist - daha az kısıtlayıcı
     * 
     * @param string $word
     * @return bool
     */
    private function isBlacklistedWord(string $word): bool
    {
        $blacklist = [
            // Çok yaygın 3 karakterlik kelimeler
            'bir', 'ile', 'den', 'dan', 'ten', 'tan', 'için', 'evi', 'ver', 'dev',
            
            // Çok genel 4+ karakterlik kelimeler (sadece çok sorunlu olanlar)
            'olan', 'göre', 'daha', 'çok', 'tüm', 'her', 'genel', 'özel',
            'büyük', 'küçük', 'yeni', 'eski', 'açık', 'kapalı',
            
            // Belediye genel terimleri (çok geniş sonuç verenler)
            'hizmet', 'hizmetler', 'merkez', 'merkezi', 'alan', 'alani',
            'belediye', 'belediyesi', 'müdür', 'müdürü', 'başkan', 'başkanı',
            
            // İngilizce yaygın kelimeler
            'the', 'and', 'that', 'with', 'from', 'have', 'this', 'they'
        ];
        
        return in_array($word, $blacklist);
    }
}
