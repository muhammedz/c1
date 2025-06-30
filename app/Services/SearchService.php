<?php

namespace App\Services;

use App\Models\Service;
use App\Models\News;
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
     * Gelişmiş arama işlemi
     * 
     * @param string $query
     * @return array
     */
    public function search(string $query): array
    {
        if (empty($query)) {
            return [
                'priority_links' => collect(),
                'services' => collect(),
                'news' => collect(),
                'projects' => collect(),
                'guides' => collect(),
                'cankaya_houses' => collect(),
                'mudurlukler' => collect(),
                'archives' => collect(),
                'total' => 0
            ];
        }

        // Arama sorgusunu normalize et
        $normalizedQuery = $this->normalizeQuery($query);
        
        // Priority Links'i getir (önce)
        $priorityLinks = SearchPriorityLink::getMatchingLinks($query);
        
        // Farklı arama stratejileri uygula
        $services = $this->searchServices($normalizedQuery, $query);
        $news = $this->searchNews($normalizedQuery, $query);
        $projects = $this->searchProjects($normalizedQuery, $query);
        $guides = $this->searchGuides($normalizedQuery, $query);
        $cankayaHouses = $this->searchCankayaHouses($normalizedQuery, $query);
        $mudurlukler = $this->searchMudurlukler($normalizedQuery, $query);
        $archives = $this->searchArchives($normalizedQuery, $query);
        
        $totalCount = $priorityLinks->count() + $services->count() + $news->count() + $projects->count() + 
                     $guides->count() + $cankayaHouses->count() + $mudurlukler->count() + $archives->count();
        
        return [
            'priority_links' => $priorityLinks,
            'services' => $services,
            'news' => $news,
            'projects' => $projects,
            'guides' => $guides,
            'cankaya_houses' => $cankayaHouses,
            'mudurlukler' => $mudurlukler,
            'archives' => $archives,
            'total' => $totalCount
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
     * Hizmetlerde arama yap
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
        
        // 2. Veritabanında LIKE ile arama (sadece başlık)
        $likeResults = Service::where('status', 'published')
            ->where(function($q) use ($originalQuery, $normalizedQuery) {
                $q->where('title', 'LIKE', "%{$originalQuery}%")
                  ->orWhere('title', 'LIKE', "%{$normalizedQuery}%");
            })
            ->get();
        
        // Tekrarları önleyerek birleştir
        $existingIds = $results->pluck('id')->toArray();
        $additionalResults = $likeResults->whereNotIn('id', $existingIds);
        $results = $results->merge($additionalResults);
        
        // 3. Kelime kelime arama (sadece başlık)
        $words = explode(' ', $normalizedQuery);
        if (count($words) > 1) {
            foreach ($words as $word) {
                if (strlen($word) > 2) {
                    $wordResults = Service::where('status', 'published')
                        ->where('title', 'LIKE', "%{$word}%")
                        ->get();
                    
                    $existingIds = $results->pluck('id')->toArray();
                    $additionalResults = $wordResults->whereNotIn('id', $existingIds);
                    $results = $results->merge($additionalResults);
                }
            }
        }
        
        // 4. Fuzzy matching (benzer kelimeler) - sadece çok az sonuç varsa
        if ($results->count() < 2) {
            $fuzzyResults = $this->fuzzySearchServices($normalizedQuery);
            $existingIds = $results->pluck('id')->toArray();
            $additionalResults = $fuzzyResults->whereNotIn('id', $existingIds);
            $results = $results->merge($additionalResults);
        }
        
        return $results;
    }
    
    /**
     * Haberlerde arama yap
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
        
        // 2. Veritabanında LIKE ile arama (sadece başlık)
        $likeResults = News::where('status', 'published')
            ->where(function($q) use ($originalQuery, $normalizedQuery) {
                $q->where('title', 'LIKE', "%{$originalQuery}%")
                  ->orWhere('title', 'LIKE', "%{$normalizedQuery}%");
            })
            ->get();
        
        $existingIds = $results->pluck('id')->toArray();
        $additionalResults = $likeResults->whereNotIn('id', $existingIds);
        $results = $results->merge($additionalResults);
        
        // 3. Kelime kelime arama (sadece başlık)
        $words = explode(' ', $normalizedQuery);
        if (count($words) > 1) {
            foreach ($words as $word) {
                if (strlen($word) > 2) {
                    $wordResults = News::where('status', 'published')
                        ->where('title', 'LIKE', "%{$word}%")
                        ->get();
                    
                    $existingIds = $results->pluck('id')->toArray();
                    $additionalResults = $wordResults->whereNotIn('id', $existingIds);
                    $results = $results->merge($additionalResults);
                }
            }
        }
        
        // 4. Fuzzy matching - sadece çok az sonuç varsa
        if ($results->count() < 2) {
            $fuzzyResults = $this->fuzzySearchNews($normalizedQuery);
            $existingIds = $results->pluck('id')->toArray();
            $additionalResults = $fuzzyResults->whereNotIn('id', $existingIds);
            $results = $results->merge($additionalResults);
        }
        
        return $results;
    }
    
    /**
     * Hizmetler için fuzzy search
     * 
     * @param string $query
     * @return Collection
     */
    private function fuzzySearchServices(string $query): Collection
    {
        $results = collect();
        
        // Tek karakter toleransı ile arama
        $variations = $this->generateQueryVariations($query);
        
        foreach ($variations as $variation) {
            $variationResults = Service::where('status', 'published')
                ->where('title', 'LIKE', "%{$variation}%")
                ->limit(5)
                ->get();
            
            $existingIds = $results->pluck('id')->toArray();
            $additionalResults = $variationResults->whereNotIn('id', $existingIds);
            $results = $results->merge($additionalResults);
            
            if ($results->count() >= 10) break;
        }
        
        return $results;
    }
    
    /**
     * Haberler için fuzzy search
     * 
     * @param string $query
     * @return Collection
     */
    private function fuzzySearchNews(string $query): Collection
    {
        $results = collect();
        
        $variations = $this->generateQueryVariations($query);
        
        foreach ($variations as $variation) {
            $variationResults = News::where('status', 'published')
                ->where('title', 'LIKE', "%{$variation}%")
                ->limit(5)
                ->get();
            
            $existingIds = $results->pluck('id')->toArray();
            $additionalResults = $variationResults->whereNotIn('id', $existingIds);
            $results = $results->merge($additionalResults);
            
            if ($results->count() >= 10) break;
        }
        
        return $results;
    }
    
    /**
     * Sorgu varyasyonları oluştur (typo toleransı için)
     * 
     * @param string $query
     * @return array
     */
    private function generateQueryVariations(string $query): array
    {
        $variations = [];
        
        // Orijinal sorgu
        $variations[] = $query;
        
        // Yaygın Türkçe yazım hataları
        $commonMistakes = [
            'egitim' => ['eğitim', 'egitim'],
            'eğitim' => ['egitim', 'eğitim'],
            'eğitin' => ['eğitim', 'egitim'], // eğitin -> eğitim
            'egtin' => ['eğitim', 'egitim'],   // egtin -> eğitim
            'saglik' => ['sağlık', 'saglik'],
            'sağlık' => ['saglik', 'sağlık'],
            'kultur' => ['kültür', 'kultur'],
            'kültür' => ['kultur', 'kültür'],
            'spor' => ['spor'],
            'sosyal' => ['sosyal'],
            'hizmet' => ['hizmet'],
            'destek' => ['destek'],
            'yardim' => ['yardım', 'yardim'],
            'yardım' => ['yardim', 'yardım']
        ];
        
        foreach ($commonMistakes as $mistake => $corrections) {
            if (strpos($query, $mistake) !== false) {
                foreach ($corrections as $correction) {
                    $variations[] = str_replace($mistake, $correction, $query);
                }
            }
        }
        
        // Tek karakter eksik/fazla varyasyonları (sadece son karakter için)
        if (strlen($query) >= 5 && strlen($query) <= 8) {
            // Sadece son karakteri çıkar (ilk karakteri çıkarmayı kaldırdık)
            $variations[] = substr($query, 0, -1);
            
            // Yaygın son ekler
            $variations[] = $query . 'i';
            $variations[] = $query . 'ı';
            $variations[] = $query . 'e';
            $variations[] = $query . 'a';
            $variations[] = $query . 'm'; // eğitin + m = eğitim
        }
        
        // Levenshtein distance ile benzer kelimeler (daha sıkı kontrol)
        if (strlen($query) >= 5) {
            $commonWords = ['eğitim', 'egitim', 'sağlık', 'saglik', 'kültür', 'kultur', 'sosyal', 'hizmet'];
            foreach ($commonWords as $word) {
                if (levenshtein($query, $word) <= 1 && strlen($query) >= strlen($word) - 1) { // En fazla 1 karakter fark ve uzunluk kontrolü
                    $variations[] = $word;
                }
            }
        }
        
        return array_unique($variations);
    }
    
    /**
     * Projelerde arama yap
     * 
     * @param string $normalizedQuery
     * @param string $originalQuery
     * @return Collection
     */
    private function searchProjects(string $normalizedQuery, string $originalQuery): Collection
    {
        $results = collect();
        
        // Aktif projelerde LIKE ile arama (sadece başlık)
        $likeResults = Project::where('is_active', true)
            ->where(function($q) use ($originalQuery, $normalizedQuery) {
                $q->where('title', 'LIKE', "%{$originalQuery}%")
                  ->orWhere('title', 'LIKE', "%{$normalizedQuery}%");
            })
            ->with('category')
            ->get();
        
        $results = $results->merge($likeResults);
        
        // Kelime kelime arama (sadece başlık)
        $words = explode(' ', $normalizedQuery);
        if (count($words) > 1) {
            foreach ($words as $word) {
                if (strlen($word) > 2) {
                    $wordResults = Project::where('is_active', true)
                        ->where('title', 'LIKE', "%{$word}%")
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
     * Rehber yerlerinde arama yap
     * 
     * @param string $normalizedQuery
     * @param string $originalQuery
     * @return Collection
     */
    private function searchGuides(string $normalizedQuery, string $originalQuery): Collection
    {
        $results = collect();
        
        // Aktif rehber yerlerinde LIKE ile arama (sadece başlık)
        $likeResults = GuidePlace::where('is_active', true)
            ->where(function($q) use ($originalQuery, $normalizedQuery) {
                $q->where('title', 'LIKE', "%{$originalQuery}%")
                  ->orWhere('title', 'LIKE', "%{$normalizedQuery}%");
            })
            ->with('category')
            ->get();
        
        $results = $results->merge($likeResults);
        
        // Kelime kelime arama (sadece başlık)
        $words = explode(' ', $normalizedQuery);
        if (count($words) > 1) {
            foreach ($words as $word) {
                if (strlen($word) > 2) {
                    $wordResults = GuidePlace::where('is_active', true)
                        ->where('title', 'LIKE', "%{$word}%")
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
     * Çankaya Evlerinde arama yap
     * 
     * @param string $normalizedQuery
     * @param string $originalQuery
     * @return Collection
     */
    private function searchCankayaHouses(string $normalizedQuery, string $originalQuery): Collection
    {
        $results = collect();
        
        // Aktif Çankaya Evlerinde LIKE ile arama (sadece isim)
        $likeResults = CankayaHouse::where('status', 'active')
            ->where(function($q) use ($originalQuery, $normalizedQuery) {
                $q->where('name', 'LIKE', "%{$originalQuery}%")
                  ->orWhere('name', 'LIKE', "%{$normalizedQuery}%");
            })
            ->get();
        
        $results = $results->merge($likeResults);
        
        // Kelime kelime arama (sadece isim)
        $words = explode(' ', $normalizedQuery);
        if (count($words) > 1) {
            foreach ($words as $word) {
                if (strlen($word) > 2) {
                    $wordResults = CankayaHouse::where('status', 'active')
                        ->where('name', 'LIKE', "%{$word}%")
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
     * Müdürlüklerde arama yap
     * 
     * @param string $normalizedQuery
     * @param string $originalQuery
     * @return Collection
     */
    private function searchMudurlukler(string $normalizedQuery, string $originalQuery): Collection
    {
        $results = collect();
        
        // Aktif müdürlüklerde LIKE ile arama (sadece isim)
        $likeResults = Mudurluk::where('is_active', true)
            ->where(function($q) use ($originalQuery, $normalizedQuery) {
                $q->where('name', 'LIKE', "%{$originalQuery}%")
                  ->orWhere('name', 'LIKE', "%{$normalizedQuery}%");
            })
            ->get();
        
        $results = $results->merge($likeResults);
        
        // Kelime kelime arama (sadece isim)
        $words = explode(' ', $normalizedQuery);
        if (count($words) > 1) {
            foreach ($words as $word) {
                if (strlen($word) > 2) {
                    $wordResults = Mudurluk::where('is_active', true)
                        ->where('name', 'LIKE', "%{$word}%")
                        ->get();
                    
                    $existingIds = $results->pluck('id')->toArray();
                    $additionalResults = $wordResults->whereNotIn('id', $existingIds);
                    $results = $results->merge($additionalResults);
                }
            }
        }
        
        return $results;
    }

    private function searchArchives(string $normalizedQuery, string $originalQuery): Collection
    {
        $results = collect();
        
        // 1. Scout ile tam arama
        try {
            $scoutResults = Archive::search($normalizedQuery)
                ->where('status', 'published')
                ->get();
            $results = $results->merge($scoutResults);
        } catch (\Exception $e) {
            // Scout hatası durumunda devam et
        }
        
        // 2. Veritabanında LIKE ile arama (sadece başlık)
        $likeResults = Archive::where('status', 'published')
            ->where(function($q) use ($originalQuery, $normalizedQuery) {
                $q->where('title', 'LIKE', "%{$originalQuery}%")
                  ->orWhere('title', 'LIKE', "%{$normalizedQuery}%");
            })
            ->get();
        
        $existingIds = $results->pluck('id')->toArray();
        $additionalResults = $likeResults->whereNotIn('id', $existingIds);
        $results = $results->merge($additionalResults);
        
        // 3. Kelime kelime arama (sadece başlık)
        $words = explode(' ', $normalizedQuery);
        if (count($words) > 1) {
            foreach ($words as $word) {
                if (strlen($word) > 2) {
                    $wordResults = Archive::where('status', 'published')
                        ->where('title', 'LIKE', "%{$word}%")
                        ->get();
                    
                    $existingIds = $results->pluck('id')->toArray();
                    $additionalResults = $wordResults->whereNotIn('id', $existingIds);
                    $results = $results->merge($additionalResults);
                }
            }
        }
        
        // 4. Fuzzy matching - sadece çok az sonuç varsa
        if ($results->count() < 2) {
            $fuzzyResults = $this->fuzzySearchArchives($normalizedQuery);
            $existingIds = $results->pluck('id')->toArray();
            $additionalResults = $fuzzyResults->whereNotIn('id', $existingIds);
            $results = $results->merge($additionalResults);
        }
        
        return $results;
    }
    
    /**
     * Arşivler için fuzzy search
     * 
     * @param string $query
     * @return Collection
     */
    private function fuzzySearchArchives(string $query): Collection
    {
        $results = collect();
        
        $variations = $this->generateQueryVariations($query);
        
        foreach ($variations as $variation) {
            $variationResults = Archive::where('status', 'published')
                ->where('title', 'LIKE', "%{$variation}%")
                ->limit(5)
                ->get();
            
            $existingIds = $results->pluck('id')->toArray();
            $additionalResults = $variationResults->whereNotIn('id', $existingIds);
            $results = $results->merge($additionalResults);
            
            if ($results->count() >= 10) break;
        }
        
        return $results;
    }
} 