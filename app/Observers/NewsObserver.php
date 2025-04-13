<?php

namespace App\Observers;

use App\Models\News;
use App\Models\NewsTag;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class NewsObserver
{
    /**
     * Handle the News "created" event.
     */
    public function created(News $news): void
    {
        // Önbelleği temizle
        $this->clearNewsCache();
        
        // İşlemi logla
        Log::info('Yeni haber oluşturuldu', ['id' => $news->id, 'title' => $news->title]);
    }

    /**
     * Handle the News "updated" event.
     */
    public function updated(News $news): void
    {
        // Önbelleği temizle
        $this->clearNewsCache();
        
        // İşlemi logla
        Log::info('Haber güncellendi', ['id' => $news->id, 'title' => $news->title]);
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
        
        // İşlemi logla
        Log::info('Haber silindi', ['id' => $news->id, 'title' => $news->title]);
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
        
        // İşlemi logla
        Log::info('Haber geri yüklendi', ['id' => $news->id, 'title' => $news->title]);
    }

    /**
     * Handle the News "force deleted" event.
     */
    public function forceDeleted(News $news): void
    {
        // Önbelleği temizle
        $this->clearNewsCache();
        
        // İşlemi logla
        Log::info('Haber kalıcı olarak silindi', ['id' => $news->id, 'title' => $news->title]);
    }
    
    /**
     * News ile ilgili tüm önbellekleri temizler
     */
    private function clearNewsCache(): void
    {
        Cache::forget('headlines');
        Cache::forget('featured_news');
        Cache::forget('latest_news');
        Cache::forget('popular_news');
        
        // Tüm sayfaları etkileyen önbellekleri temizle
        Cache::tags(['news', 'home'])->flush();
    }
}
