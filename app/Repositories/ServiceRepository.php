<?php

namespace App\Repositories;

use App\Models\Service;
use Illuminate\Support\Str;

class ServiceRepository extends BaseRepository
{
    /**
     * ServiceRepository constructor.
     * 
     * @param Service $model
     */
    public function __construct(Service $model)
    {
        parent::__construct($model);
    }
    
    /**
     * Filtrelere göre hizmetleri getir
     * 
     * @param array $filters
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getFilteredServices(array $filters)
    {
        $query = $this->model->query()->with(['categories', 'unit', 'serviceTopics', 'hedefKitleler', 'newsCategories']);
        
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
        
        // Sıralama
        $sortField = $filters['sort'] ?? 'created_at';
        $sortDirection = $filters['direction'] ?? 'desc';
        $query->orderBy($sortField, $sortDirection);
        
        return $query->paginate(15)->appends($filters);
    }
    
    /**
     * Hizmet oluştur
     * 
     * @param array $data
     * @return Service
     */
    public function createService(array $data)
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
        
        // Yeni hizmet oluştur
        $service = $this->create($data);
        
        // Kategorileri ekle
        if (isset($data['categories']) && is_array($data['categories'])) {
            $service->categories()->sync($data['categories']);
        }
        
        return $service;
    }
    
    /**
     * Hizmeti güncelle
     * 
     * @param array $data
     * @param int $id
     * @return bool
     */
    public function updateService(array $data, $id)
    {
        $service = $this->find($id);
        
        // Temel verileri güncelle
        $updated = $service->update($data);
        
        // Kategorileri güncelle
        if (isset($data['categories']) && is_array($data['categories'])) {
            $service->categories()->sync($data['categories']);
        }
        
        return $updated;
    }
    
    /**
     * Etiketleri senkronize et
     * 
     * @param Service $service
     * @param string $tagString
     * @return void
     */
    public function syncTags(Service $service, string $tagString)
    {
        $tagNames = array_filter(array_map('trim', explode(',', $tagString)));
        $tagIds = [];
        
        foreach ($tagNames as $tagName) {
            $tag = \App\Models\ServiceTag::firstOrCreate(['name' => $tagName]);
            $tagIds[] = $tag->id;
        }
        
        $service->tags()->sync($tagIds);
        
        // Kullanım sayısını güncelle
        \App\Models\ServiceTag::whereIn('id', $tagIds)->increment('usage_count');
    }
    
    /**
     * Manşet durumunu değiştir
     * 
     * @param Service $service
     * @return bool
     */
    public function toggleHeadline(Service $service)
    {
        if (!$service->is_headline) {
            // Maksimum manşet sayısı kontrolü
            if ($this->model->where('is_headline', true)->count() >= 4) {
                return false;
            }
            
            $service->is_headline = true;
            $service->headline_order = $this->model->where('is_headline', true)->count() + 1;
        } else {
            $oldOrder = $service->headline_order;
            $service->is_headline = false;
            $service->headline_order = null;
            
            // Diğer manşetlerin sırasını güncelle
            $this->model->where('is_headline', true)
                        ->where('headline_order', '>', $oldOrder)
                        ->decrement('headline_order');
        }
        
        return $service->save();
    }
    
    /**
     * Manşet hizmetlerini getir
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getHeadlines()
    {
        return $this->model->where('is_headline', true)
                           ->orderBy('headline_order', 'asc')
                           ->get();
    }
    
    /**
     * Öne çıkan hizmetleri getir
     * 
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getFeaturedServices($limit = 6)
    {
        return $this->model->where('is_featured', true)
                           ->where('status', 'published')
                           ->orderBy('published_at', 'desc')
                           ->limit($limit)
                           ->get();
    }
} 