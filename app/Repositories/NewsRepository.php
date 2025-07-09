<?php

namespace App\Repositories;

use App\Models\News;
use Illuminate\Support\Str;

class NewsRepository extends BaseRepository
{
    /**
     * NewsRepository constructor.
     * 
     * @param News $model
     */
    public function __construct(News $model)
    {
        parent::__construct($model);
    }
    
    /**
     * Filtrelere göre haberleri getir
     * 
     * @param array $filters
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getFilteredNews(array $filters)
    {
        $query = $this->model->query()->with(['categories']);
        
        // Kategori filtresi
        if (isset($filters['category']) && $filters['category']) {
            $categoryId = $filters['category'];
            $query->whereHas('categories', function($q) use ($categoryId) {
                $q->where('category_id', $categoryId);
            });
        }
        
        // Durum filtresi
        if (isset($filters['status']) && $filters['status']) {
            $query->where('status', $filters['status']);
        }
        
        // Arama filtresi
        if (isset($filters['search']) && $filters['search']) {
            $search = $filters['search'];
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%")
                  ->orWhere('summary', 'like', "%{$search}%");
            });
        }
        
        // Arşiv filtresi
        if (isset($filters['archived']) && $filters['archived']) {
            $query->whereNotNull('end_date')
                  ->where('end_date', '<', now());
        } else {
            $query->where(function($q) {
                $q->whereNull('end_date')
                  ->orWhere('end_date', '>=', now());
            });
        }
        
        // Manşet ve özellik filtresi
        if (isset($filters['headline'])) {
            if ($filters['headline'] === 'only') {
                $query->where('is_headline', true);
            } elseif ($filters['headline'] === 'exclude') {
                $query->where('is_headline', false);
            } elseif ($filters['headline'] === 'featured') {
                $query->where('is_featured', true);
            } elseif ($filters['headline'] === 'archived') {
                $query->where('is_archived', true);
            }
            // 'all' değeri için filtreleme yapma, tüm haberleri getir
        }
        
        // Sıralama
        $sortField = $filters['sort'] ?? 'published_at';
        $sortDirection = $filters['direction'] ?? 'desc';
        
        // Sadece yayın tarihine göre sırala (published_at)
        if ($sortField === 'published_at') {
            $query->orderBy('published_at', $sortDirection)
                  ->orderBy('created_at', $sortDirection); // Aynı yayın tarihli haberler için
        } else {
            $query->orderBy($sortField, $sortDirection);
        }
        
        // Eğer başka bir sıralama yapılmışsa da ikincil sıralama olarak published_at ekle
        if ($sortField !== 'published_at') {
            $query->orderBy('published_at', 'desc')
                  ->orderBy('created_at', 'desc');
        }
        
        return $query->paginate(30)->appends($filters);
    }
    
    /**
     * Haber oluştur
     * 
     * @param array $data
     * @return News
     */
    public function createNews(array $data)
    {
        // Slug oluştur ve benzersizliğini kontrol et
        $baseSlug = Str::slug($data['title']);
        $slug = $baseSlug;
        $counter = 1;
        
        // Eğer slug zaten varsa, sonuna numara ekle
        while ($this->model->where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }
        
        $data['slug'] = $slug;
        
        // Yeni haber oluştur
        $news = $this->create($data);
        
        // Kategorileri ekle
        if (isset($data['categories']) && is_array($data['categories'])) {
            $news->categories()->sync($data['categories']);
        }
        
        return $news;
    }
    
    /**
     * Haberi güncelle
     * 
     * @param array $data
     * @param int $id
     * @return bool
     */
    public function updateNews(array $data, $id)
    {
        $news = $this->find($id);
        
        // Boolean alanları doğru formata çevir
        if (isset($data['is_headline'])) {
            $data['is_headline'] = filter_var($data['is_headline'], FILTER_VALIDATE_BOOLEAN);
        }
        
        if (isset($data['is_featured'])) {
            $data['is_featured'] = filter_var($data['is_featured'], FILTER_VALIDATE_BOOLEAN);
        }
        
        // Temel verileri güncelle
        $updated = $news->update($data);
        
        // Kategorileri güncelle
        if (isset($data['categories']) && is_array($data['categories'])) {
            $news->categories()->sync($data['categories']);
        }
        
        return $updated;
    }
    
    /**
     * Etiketleri senkronize et
     * 
     * @param News $news
     * @param string $tagString
     * @return void
     */
    public function syncTags(News $news, string $tagString)
    {
        $tagNames = array_filter(array_map('trim', explode(',', $tagString)));
        $tagIds = [];
        
        foreach ($tagNames as $tagName) {
            $tag = \App\Models\NewsTag::firstOrCreate(['name' => $tagName]);
            $tagIds[] = $tag->id;
        }
        
        $news->tags()->sync($tagIds);
        
        // Kullanım sayısını güncelle
        \App\Models\NewsTag::whereIn('id', $tagIds)->increment('usage_count');
    }
    
    /**
     * Manşet durumunu değiştir
     * 
     * @param News $news
     * @return bool
     */
    public function toggleHeadline(News $news)
    {
        if (!$news->is_headline) {
            // Maksimum manşet sayısı kontrolü
            if ($this->model->where('is_headline', true)->count() >= 4) {
                return false;
            }
            
            $news->is_headline = true;
            $news->headline_order = $this->model->where('is_headline', true)->count() + 1;
        } else {
            $oldOrder = $news->headline_order;
            $news->is_headline = false;
            $news->headline_order = null;
            
            // Diğer manşetlerin sırasını güncelle
            $this->model->where('is_headline', true)
                        ->where('headline_order', '>', $oldOrder)
                        ->decrement('headline_order');
        }
        
        return $news->save();
    }
    
    /**
     * Manşet haberlerini getir
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getHeadlines()
    {
        return $this->model->where('is_headline', true)
                           ->orderBy('published_at', 'desc')
                           ->orderBy('created_at', 'desc')
                           ->get();
    }
    
    /**
     * Öne çıkan haberleri getir
     * 
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getFeaturedNews($limit = 6)
    {
        return $this->model->where('is_featured', true)
                           ->where('status', 'published')
                           ->orderBy('published_at', 'desc')
                           ->limit($limit)
                           ->get();
    }
} 