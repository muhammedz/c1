<?php

namespace App\Repositories;

use App\Models\Page;
use Illuminate\Support\Str;

class PageRepository extends BaseRepository
{
    /**
     * PageRepository constructor.
     * 
     * @param Page $model
     */
    public function __construct(Page $model)
    {
        parent::__construct($model);
    }
    
    /**
     * Filtrelere göre sayfaları getir
     * 
     * @param array $filters
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getFilteredPages(array $filters)
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
            $query->where('is_archived', true);
        } else {
            $query->where('is_archived', false);
        }
        
        // Sıralama
        $sortField = $filters['sort'] ?? 'created_at';
        $sortDirection = $filters['direction'] ?? 'desc';
        $query->orderBy($sortField, $sortDirection);
        
        return $query->paginate(15)->appends($filters);
    }
    
    /**
     * Sayfa oluştur
     * 
     * @param array $data
     * @return Page
     */
    public function createPage(array $data)
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
        
        // Yeni sayfa oluştur
        $page = $this->create($data);
        
        // Kategorileri ekle
        if (isset($data['categories']) && is_array($data['categories']) && !empty($data['categories'])) {
            $page->categories()->sync($data['categories']);
        }
        
        return $page;
    }
    
    /**
     * Sayfayı güncelle
     * 
     * @param array $data
     * @param int $id
     * @return bool
     */
    public function updatePage(array $data, $id)
    {
        $page = $this->find($id);
        
        // Slug kontrolü ve güncelleme
        if (isset($data['slug']) && !empty($data['slug'])) {
            $baseSlug = Str::slug($data['slug']);
            $slug = $baseSlug;
            $counter = 1;
            
            // Eğer slug zaten varsa ve mevcut sayfanın slug'ı değilse, sonuna numara ekle
            while ($this->model->where('slug', $slug)->where('id', '!=', $id)->exists()) {
                $slug = $baseSlug . '-' . $counter;
                $counter++;
            }
            
            $data['slug'] = $slug;
        } elseif (isset($data['title']) && (empty($data['slug']) || $data['slug'] === '')) {
            // Slug boşsa title'dan oluştur
            $baseSlug = Str::slug($data['title']);
            $slug = $baseSlug;
            $counter = 1;
            
            // Eğer slug zaten varsa ve mevcut sayfanın slug'ı değilse, sonuna numara ekle
            while ($this->model->where('slug', $slug)->where('id', '!=', $id)->exists()) {
                $slug = $baseSlug . '-' . $counter;
                $counter++;
            }
            
            $data['slug'] = $slug;
        }
        
        // Temel verileri güncelle
        $updated = $page->update($data);
        
        // Kategorileri güncelle
        if (isset($data['categories']) && is_array($data['categories']) && !empty($data['categories'])) {
            $page->categories()->sync($data['categories']);
        }
        
        return $updated;
    }
    
    /**
     * Etiketleri senkronize et
     * 
     * @param Page $page
     * @param string $tagString
     * @return void
     */
    public function syncTags(Page $page, string $tagString)
    {
        $tagNames = array_filter(array_map('trim', explode(',', $tagString)));
        $tagIds = [];
        
        foreach ($tagNames as $tagName) {
            $tag = \App\Models\PageTag::firstOrCreate(['name' => $tagName]);
            $tagIds[] = $tag->id;
        }
        
        $page->tags()->sync($tagIds);
        
        // Kullanım sayısını güncelle
        \App\Models\PageTag::whereIn('id', $tagIds)->increment('usage_count');
    }
    
    /**
     * Öne çıkarma durumunu değiştir
     * 
     * @param Page $page
     * @return bool
     */
    public function toggleFeatured(Page $page)
    {
        if (!$page->is_featured) {
            // Maksimum öne çıkan sayfa sayısı kontrolü
            if ($this->model->where('is_featured', true)->count() >= 4) {
                return false;
            }
            
            $page->is_featured = true;
            $page->featured_order = $this->model->where('is_featured', true)->count() + 1;
        } else {
            $oldOrder = $page->featured_order;
            $page->is_featured = false;
            $page->featured_order = null;
            
            // Diğer öne çıkan sayfaların sırasını güncelle
            $this->model->where('is_featured', true)
                        ->where('featured_order', '>', $oldOrder)
                        ->decrement('featured_order');
        }
        
        return $page->save();
    }
    
    /**
     * Öne çıkan sayfaları getir
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getFeaturedPages()
    {
        return $this->model->where('is_featured', true)
                           ->orderBy('featured_order', 'asc')
                           ->get();
    }
    
    /**
     * Öne çıkan sayfaları getir
     * 
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getFeaturedPagesLimit($limit = 6)
    {
        return $this->model->where('is_featured', true)
                           ->where('status', 'published')
                           ->orderBy('published_at', 'desc')
                           ->limit($limit)
                           ->get();
    }
} 