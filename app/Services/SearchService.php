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
     * Gelişmiş arama işlemi - MySQL 9.x uyumlu
     * Hedef kitle filtresi ve tarih sıralaması ile arama yapabilir
     * 
     * @param string $query Arama sorgusu
     * @param string|null $hedefKitleSlug Hedef kitle slug'ı (opsiyonel filtre)
     * @param string|null $dateSort Tarih sıralaması (desc=yeniden eskiye, asc=eskiden yeniye)
     * @return array
     */
    public function search(string $query, ?string $hedefKitleSlug = null, ?string $dateSort = null): array
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
        
        // Hedef kitle ID'sini bul (eğer filtre varsa)
        $hedefKitleId = null;
        if (!empty($hedefKitleSlug)) {
            $hedefKitle = \App\Models\HedefKitle::where('slug', $hedefKitleSlug)
                ->where('is_active', true)
                ->first();
            $hedefKitleId = $hedefKitle?->id;
            

        }
        
        // Farklı arama stratejileri uygula
        $services = $this->searchServices($normalizedQuery, $query, $hedefKitleId, $dateSort);
        $news = $this->searchNews($normalizedQuery, $query, $hedefKitleId, $dateSort);
        
        // Hedef kitle filtresi seçildiğinde sadece News ve Service ara
        if ($hedefKitleId) {
            // Hedef kitle filtresi aktifken diğer kategoriler boş
            $pages = collect();
            $projects = collect();
            $guides = collect();
            $cankayaHouses = collect();
            $mudurlukler = collect();
            $archives = collect();
        } else {
            // Normal arama - tüm kategoriler
            $pages = $this->searchPages($normalizedQuery, $query);
            $projects = $this->searchProjects($normalizedQuery, $query);
            $guides = $this->searchGuides($normalizedQuery, $query);
            $cankayaHouses = $this->searchCankayaHouses($normalizedQuery, $query);
            $mudurlukler = $this->searchMudurlukler($normalizedQuery, $query);
            $archives = $this->searchArchives($normalizedQuery, $query);
        }
        
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
     * Kelime sınırları ile REGEXP oluştur - MySQL 9.x uyumlu
     * 
     * @param string $word
     * @return string
     */
    private function createWordBoundaryRegex(string $word): string
    {
        // MySQL 9.x için \\b kelime sınırları kullan
        return '\\b' . preg_quote($word, '/') . '\\b';
    }
    
    /**
     * Hizmetlerde arama yap - İyileştirilmiş algoritma
     * Hedef kitle filtresi ve tarih sıralaması ile desteklenir
     * 
     * @param string $normalizedQuery
     * @param string $originalQuery
     * @param int|null $hedefKitleId
     * @param string|null $dateSort
     * @return Collection
     */
    private function searchServices(string $normalizedQuery, string $originalQuery, ?int $hedefKitleId = null, ?string $dateSort = null): Collection
    {
        $results = collect();
        

        
        // 1. Scout ile tam arama - hedef kitle filtresi ile
        try {
            // Hedef kitle filtresi varsa önce filtrelenmiş ID'leri al
            if ($hedefKitleId) {
                $filteredServiceIds = Service::where('status', 'published')
                    ->whereHas('hedefKitleler', function($q) use ($hedefKitleId) {
                        $q->where('hedef_kitleler.id', $hedefKitleId);
                    })->pluck('id')->toArray();
                
                if (!empty($filteredServiceIds)) {
                    $scoutResults = Service::search($normalizedQuery)
                        ->where('status', 'published')
                        ->whereIn('id', $filteredServiceIds)
                        ->get();
                    $results = $results->merge($scoutResults);
                }
                // Eğer hedef kitlede hiç hizmet yoksa Scout'u hiç çalıştırma
            } else {
                // Hedef kitle filtresi yoksa normal arama
                $scoutResults = Service::search($normalizedQuery)
                    ->where('status', 'published')
                    ->get();
                $results = $results->merge($scoutResults);
            }
        } catch (\Exception $e) {
            // Scout hatası durumunda devam et
        }
        
        // 2. Tam cümle/ifade arama (öncelik)
        $exactQuery = Service::where('status', 'published')
            ->where(function($q) use ($originalQuery, $normalizedQuery) {
                $q->where('title', 'LIKE', "%{$originalQuery}%")
                  ->orWhere('title', 'LIKE', "%{$normalizedQuery}%");
            });
        
        // Hedef kitle filtresi uygula
        if ($hedefKitleId) {
            $exactQuery->whereHas('hedefKitleler', function($q) use ($hedefKitleId) {
                $q->where('hedef_kitleler.id', $hedefKitleId);
            });
        }
        
        $exactResults = $exactQuery->get();
        
        $existingIds = $results->pluck('id')->toArray();
        $additionalResults = $exactResults->whereNotIn('id', $existingIds);
        $results = $results->merge($additionalResults);
        
        // 3. Kelime sınırları ile arama - Sadece yeterli sonuç yoksa
        if ($results->count() < 3) {
            $words = explode(' ', $normalizedQuery);
            if (count($words) > 1) {
                foreach ($words as $word) {
                    // Minimum 5 karakter ve blacklist kontrolü
                    if (strlen($word) >= 5 && !$this->isBlacklistedWord($word)) {
                        try {
                            $wordRegex = $this->createWordBoundaryRegex($word);
                            
                            $wordQuery = Service::where('status', 'published')
                                ->where('title', 'REGEXP', $wordRegex);
                            
                            // Hedef kitle filtresi uygula
                            if ($hedefKitleId) {
                                $wordQuery->whereHas('hedefKitleler', function($q) use ($hedefKitleId) {
                                    $q->where('hedef_kitleler.id', $hedefKitleId);
                                });
                            }
                            
                            $wordResults = $wordQuery->get();
                            
                            $existingIds = $results->pluck('id')->toArray();
                            $additionalResults = $wordResults->whereNotIn('id', $existingIds);
                            $results = $results->merge($additionalResults);
                        } catch (\Exception $e) {
                            // REGEXP hatası durumunda LIKE kullan
                            $wordQuery = Service::where('status', 'published')
                                ->where('title', 'LIKE', "%{$word}%")
                                ->limit(3);
                            
                            // Hedef kitle filtresi uygula
                            if ($hedefKitleId) {
                                $wordQuery->whereHas('hedefKitleler', function($q) use ($hedefKitleId) {
                                    $q->where('hedef_kitleler.id', $hedefKitleId);
                                });
                            }
                            
                            $wordResults = $wordQuery->get();
                            
                            $existingIds = $results->pluck('id')->toArray();
                            $additionalResults = $wordResults->whereNotIn('id', $existingIds);
                            $results = $results->merge($additionalResults);
                        }
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
        
        // Hedef kitle filtresi final kontrolü - tüm sonuçları filtrele
        if ($hedefKitleId) {
            $results = $results->filter(function($service) use ($hedefKitleId) {
                return $service->hedefKitleler->contains('id', $hedefKitleId);
            });
        }
        
        // Tarih sıralaması uygula
        if ($dateSort && $results->count() > 0) {
            if ($dateSort === 'asc') {
                // Eskiden yeniye
                $results = $results->sortBy('published_at');
            } else {
                // Yeniden eskiye (varsayılan)
                $results = $results->sortByDesc('published_at');
            }
        }
        
        return $results;
    }
    
    /**
     * Haberlerde arama yap - İyileştirilmiş algoritma
     * Hedef kitle filtresi ve tarih sıralaması ile desteklenir
     * 
     * @param string $normalizedQuery
     * @param string $originalQuery
     * @param int|null $hedefKitleId
     * @param string|null $dateSort
     * @return Collection
     */
    private function searchNews(string $normalizedQuery, string $originalQuery, ?int $hedefKitleId = null, ?string $dateSort = null): Collection
    {
        $results = collect();
        
        // 1. Scout ile tam arama - hedef kitle filtresi ile
        try {
            // Hedef kitle filtresi varsa önce filtrelenmiş ID'leri al
            if ($hedefKitleId) {
                $filteredNewsIds = News::where('status', 'published')
                    ->whereHas('hedefKitleler', function($q) use ($hedefKitleId) {
                        $q->where('hedef_kitleler.id', $hedefKitleId);
                    })->pluck('id')->toArray();
                
                if (!empty($filteredNewsIds)) {
                    $scoutResults = News::search($normalizedQuery)
                        ->where('status', 'published')
                        ->whereIn('id', $filteredNewsIds)
                        ->get();
                    $results = $results->merge($scoutResults);
                }
                // Eğer hedef kitlede hiç haber yoksa Scout'u hiç çalıştırma
            } else {
                // Hedef kitle filtresi yoksa normal arama
                $scoutResults = News::search($normalizedQuery)
                    ->where('status', 'published')
                    ->get();
                $results = $results->merge($scoutResults);
            }
        } catch (\Exception $e) {
            // Scout hatası durumunda devam et
        }
        
        // 2. Tam cümle/ifade arama (öncelik)
        $exactQuery = News::where('status', 'published')
            ->where(function($q) use ($originalQuery, $normalizedQuery) {
                $q->where('title', 'LIKE', "%{$originalQuery}%")
                  ->orWhere('title', 'LIKE', "%{$normalizedQuery}%");
            });
        
        // Hedef kitle filtresi uygula
        if ($hedefKitleId) {
            $exactQuery->whereHas('hedefKitleler', function($q) use ($hedefKitleId) {
                $q->where('hedef_kitleler.id', $hedefKitleId);
            });
        }
        
        $exactResults = $exactQuery->get();
        
        $existingIds = $results->pluck('id')->toArray();
        $additionalResults = $exactResults->whereNotIn('id', $existingIds);
        $results = $results->merge($additionalResults);
        
        // 3. Kelime sınırları ile arama - Sadece yeterli sonuç yoksa
        if ($results->count() < 3) {
            $words = explode(' ', $normalizedQuery);
            if (count($words) > 1) {
                foreach ($words as $word) {
                    if (strlen($word) >= 5 && !$this->isBlacklistedWord($word)) {
                        try {
                            $wordRegex = $this->createWordBoundaryRegex($word);
                            
                            $wordQuery = News::where('status', 'published')
                                ->where('title', 'REGEXP', $wordRegex);
                            
                            // Hedef kitle filtresi uygula
                            if ($hedefKitleId) {
                                $wordQuery->whereHas('hedefKitleler', function($q) use ($hedefKitleId) {
                                    $q->where('hedef_kitleler.id', $hedefKitleId);
                                });
                            }
                            
                            $wordResults = $wordQuery->get();
                            
                            $existingIds = $results->pluck('id')->toArray();
                            $additionalResults = $wordResults->whereNotIn('id', $existingIds);
                            $results = $results->merge($additionalResults);
                        } catch (\Exception $e) {
                            // REGEXP hatası durumunda LIKE kullan
                            $wordQuery = News::where('status', 'published')
                                ->where('title', 'LIKE', "%{$word}%")
                                ->limit(3);
                            
                            // Hedef kitle filtresi uygula
                            if ($hedefKitleId) {
                                $wordQuery->whereHas('hedefKitleler', function($q) use ($hedefKitleId) {
                                    $q->where('hedef_kitleler.id', $hedefKitleId);
                                });
                            }
                            
                            $wordResults = $wordQuery->get();
                            
                            $existingIds = $results->pluck('id')->toArray();
                            $additionalResults = $wordResults->whereNotIn('id', $existingIds);
                            $results = $results->merge($additionalResults);
                        }
                    }
                }
            }
        }
        
        // 4. Fallback: LIKE arama
        if ($results->count() < 2) {
            $words = explode(' ', $normalizedQuery);
            foreach ($words as $word) {
                if (strlen($word) >= 4 && !$this->isBlacklistedWord($word)) {
                    $wordQuery = News::where('status', 'published')
                        ->where('title', 'LIKE', "%{$word}%")
                        ->limit(3);
                    
                    // Hedef kitle filtresi uygula
                    if ($hedefKitleId) {
                        $wordQuery->whereHas('hedefKitleler', function($q) use ($hedefKitleId) {
                            $q->where('hedef_kitleler.id', $hedefKitleId);
                        });
                    }
                    
                    $wordResults = $wordQuery->get();
                    
                    $existingIds = $results->pluck('id')->toArray();
                    $additionalResults = $wordResults->whereNotIn('id', $existingIds);
                    $results = $results->merge($additionalResults);
                }
            }
        }
        
        // Hedef kitle filtresi final kontrolü - tüm sonuçları filtrele
        if ($hedefKitleId) {
            $results = $results->filter(function($news) use ($hedefKitleId) {
                return $news->hedefKitleler->contains('id', $hedefKitleId);
            });
        }
        
        // Tarih sıralaması uygula
        if ($dateSort && $results->count() > 0) {
            if ($dateSort === 'asc') {
                // Eskiden yeniye
                $results = $results->sortBy('published_at');
            } else {
                // Yeniden eskiye (varsayılan)
                $results = $results->sortByDesc('published_at');
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
                        try {
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
                        } catch (\Exception $e) {
                            // REGEXP hatası durumunda LIKE kullan
                            $wordResults = Page::published()
                                ->where(function($q) use ($word) {
                                    $q->where('title', 'LIKE', "%{$word}%")
                                      ->orWhere('summary', 'LIKE', "%{$word}%");
                                })
                                ->limit(3)
                                ->get();
                            
                            $existingIds = $results->pluck('id')->toArray();
                            $additionalResults = $wordResults->whereNotIn('id', $existingIds);
                            $results = $results->merge($additionalResults);
                        }
                    }
                }
            }
        }
        
        return $results;
    }
    
    /**
     * Projelerde arama yap - Basitleştirilmiş
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
        
        // LIKE ile kelime arama (REGEXP yerine)
        if ($results->count() < 3) {
            $words = explode(' ', $normalizedQuery);
            foreach ($words as $word) {
                if (strlen($word) >= 5 && !$this->isBlacklistedWord($word)) {
                    $wordResults = Project::where('is_active', true)
                        ->where('title', 'LIKE', "%{$word}%")
                        ->with('category')
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
     * Rehber yerlerinde arama yap - Basitleştirilmiş
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
        
        // LIKE ile kelime arama
        if ($results->count() < 3) {
            $words = explode(' ', $normalizedQuery);
            foreach ($words as $word) {
                if (strlen($word) >= 5 && !$this->isBlacklistedWord($word)) {
                    $wordResults = GuidePlace::where('is_active', true)
                        ->where('title', 'LIKE', "%{$word}%")
                        ->with('category')
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
     * Çankaya Evlerinde arama yap - Basitleştirilmiş
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
        
        // LIKE ile kelime arama
        if ($results->count() < 3) {
            $words = explode(' ', $normalizedQuery);
            foreach ($words as $word) {
                if (strlen($word) >= 5 && !$this->isBlacklistedWord($word)) {
                    $wordResults = CankayaHouse::where('status', 'active')
                        ->where('name', 'LIKE', "%{$word}%")
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
     * Müdürlüklerde arama yap - Basitleştirilmiş
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
        
        // LIKE ile kelime arama
        if ($results->count() < 3) {
            $words = explode(' ', $normalizedQuery);
            foreach ($words as $word) {
                if (strlen($word) >= 5 && !$this->isBlacklistedWord($word)) {
                    $wordResults = Mudurluk::where('is_active', true)
                        ->where('name', 'LIKE', "%{$word}%")
                        ->limit(3)
                        ->get();
                    
                    $existingIds = $results->pluck('id')->toArray();
                    $additionalResults = $wordResults->whereNotIn('id', $existingIds);
                    $results = $results->merge($additionalResults);
                }
            }
        }
        
        // Müdürlük dosyalarında arama
        try {
            $searchSettings = \App\Models\SearchSetting::getSettings();
            if ($searchSettings->search_in_mudurluk_files) {
                $fileResults = $this->searchMudurlukFiles($normalizedQuery, $originalQuery);
                $results = $results->merge($fileResults);
            }
        } catch (\Exception $e) {
            // SearchSetting modeli yoksa devam et
        }
        
        return $results;
    }
    
    /**
     * Arşivlerde arama yap - Basitleştirilmiş
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
        
        // LIKE ile kelime arama
        if ($results->count() < 3) {
            $words = explode(' ', $normalizedQuery);
            foreach ($words as $word) {
                if (strlen($word) >= 5 && !$this->isBlacklistedWord($word)) {
                    $wordResults = Archive::where('status', 'published')
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
     * Müdürlük dosyalarında arama yap
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
     * İyileştirilmiş Blacklist - MySQL REGEXP sorunları için
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
            
            // Problematik kelimeler - şimdilik kaldırıldı
            // 'kent' - çok kısıtlayıcı olduğu için kaldırıldı
            
            // İngilizce yaygın kelimeler
            'the', 'and', 'that', 'with', 'from', 'have', 'this', 'they'
        ];
        
        return in_array($word, $blacklist);
    }
}
