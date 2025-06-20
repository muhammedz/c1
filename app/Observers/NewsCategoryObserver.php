<?php

namespace App\Observers;

use App\Models\NewsCategory;
use App\Traits\LogsActivity;
use Illuminate\Support\Facades\Cache;

class NewsCategoryObserver
{
    use LogsActivity;

    /**
     * Handle the NewsCategory "created" event.
     */
    public function created(NewsCategory $newsCategory): void
    {
        // Önbelleği temizle
        $this->clearNewsCategoryCache();
        
        // Activity log kaydet
        $this->logCreated($newsCategory);
    }

    /**
     * Handle the NewsCategory "updated" event.
     */
    public function updated(NewsCategory $newsCategory): void
    {
        // Önbelleği temizle
        $this->clearNewsCategoryCache();
        
        // Activity log kaydet
        $this->logUpdated($newsCategory);
    }

    /**
     * Handle the NewsCategory "deleted" event.
     */
    public function deleted(NewsCategory $newsCategory): void
    {
        // Önbelleği temizle
        $this->clearNewsCategoryCache();
        
        // Activity log kaydet
        $this->logDeleted($newsCategory);
    }

    /**
     * Handle the NewsCategory "restored" event.
     */
    public function restored(NewsCategory $newsCategory): void
    {
        // Önbelleği temizle
        $this->clearNewsCategoryCache();
        
        // Activity log kaydet
        $this->logRestored($newsCategory);
    }

    /**
     * Handle the NewsCategory "force deleted" event.
     */
    public function forceDeleted(NewsCategory $newsCategory): void
    {
        // Önbelleği temizle
        $this->clearNewsCategoryCache();
        
        // Activity log kaydet
        $this->logForceDeleted($newsCategory);
    }
    
    /**
     * NewsCategory cache'ini temizle
     */
    private function clearNewsCategoryCache(): void
    {
        try {
            // NewsCategory ile ilgili cache anahtarlarını temizle
            $cacheKeys = [
                'news_categories',
                'news_categories_active',
                'news_menu',
                'categories_with_news',
                'news_sidebar',
            ];
            
            foreach ($cacheKeys as $key) {
                Cache::forget($key);
            }
            
        } catch (\Exception $e) {
            // Cache temizleme hatası ana işlemi etkilememelidir
            \Log::warning('NewsCategory cache clearing failed: ' . $e->getMessage());
        }
    }

    /**
     * NewsCategory modeli için hariç tutulacak alanlar
     */
    protected function getExcludedFields(): array
    {
        return [
            'slug', // Slug otomatik oluşturuluyor
        ];
    }
} 