<?php

namespace App\Observers;

use App\Models\News;
use App\Models\NewsTag;
use App\Traits\LogsActivity;
use Illuminate\Support\Facades\Cache;

class NewsObserver
{
    use LogsActivity;

    /**
     * Handle the News "created" event.
     */
    public function created(News $news): void
    {
        // Önbelleği temizle
        $this->clearNewsCache();
        
        // Activity log kaydet
        $this->logCreated($news);
    }

    /**
     * Handle the News "updated" event.
     */
    public function updated(News $news): void
    {
        // Önbelleği temizle
        $this->clearNewsCache();
        
        // Activity log kaydet
        $this->logUpdated($news);
    }

    /**
     * Handle the News "deleted" event.
     */
    public function deleted(News $news): void
    {
        // Önbelleği temizle
        $this->clearNewsCache();
        
        // Etiket kullanım sayısını güncelle
        foreach ($news->tags as $tag) {
            $tag->decrementUsage();
        }
        
        // Activity log kaydet
        $this->logDeleted($news);
    }

    /**
     * Handle the News "restored" event.
     */
    public function restored(News $news): void
    {
        // Önbelleği temizle
        $this->clearNewsCache();
        
        // Etiket kullanım sayısını güncelle
        foreach ($news->tags as $tag) {
            $tag->incrementUsage();
        }
        
        // Activity log kaydet
        $this->logRestored($news);
    }

    /**
     * Handle the News "force deleted" event.
     */
    public function forceDeleted(News $news): void
    {
        // Önbelleği temizle
        $this->clearNewsCache();
        
        // Activity log kaydet
        $this->logForceDeleted($news);
    }
    
    /**
     * News cache'ini temizle
     */
    private function clearNewsCache(): void
    {
        try {
            // News ile ilgili cache anahtarlarını temizle
            $cacheKeys = [
                'news_latest',
                'news_featured',
                'news_categories',
                'news_tags',
                'news_sidebar',
                'news_archive',
            ];
            
            foreach ($cacheKeys as $key) {
                Cache::forget($key);
            }
            
            // Ek news cache anahtarları da temizlenebilir
            $patterns = [
                'news_',
                'headlines_',
                'featured_news_',
                'popular_news_',
            ];
            
            foreach ($patterns as $pattern) {
                // Basit pattern matching ile cache temizleme
                for ($i = 0; $i < 100; $i++) {
                    Cache::forget($pattern . $i);
                    Cache::forget($pattern . 'page_' . $i);
                }
            }
            
        } catch (\Exception $e) {
            // Cache temizleme hatası ana işlemi etkilememelidir
            \Log::warning('News cache clearing failed: ' . $e->getMessage());
        }
    }

    /**
     * News modeli için hariç tutulacak alanlar
     */
    protected function getExcludedFields(): array
    {
        return [
            'views', // Görüntülenme sayısı değişikliği loglanmasın
            'view_count', // Görüntülenme sayısı değişikliği loglanmasın
            'slug', // Slug otomatik oluşturuluyor
        ];
    }
}
