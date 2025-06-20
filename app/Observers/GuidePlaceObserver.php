<?php

namespace App\Observers;

use App\Models\GuidePlace;
use App\Traits\LogsActivity;
use Illuminate\Support\Facades\Cache;

class GuidePlaceObserver
{
    use LogsActivity;

    /**
     * Handle the GuidePlace "created" event.
     */
    public function created(GuidePlace $guidePlace): void
    {
        // Önbelleği temizle
        $this->clearGuideCache();
        
        // Activity log kaydet
        $this->logCreated($guidePlace);
    }

    /**
     * Handle the GuidePlace "updated" event.
     */
    public function updated(GuidePlace $guidePlace): void
    {
        // Önbelleği temizle
        $this->clearGuideCache();
        
        // Activity log kaydet
        $this->logUpdated($guidePlace);
    }

    /**
     * Handle the GuidePlace "deleted" event.
     */
    public function deleted(GuidePlace $guidePlace): void
    {
        // Önbelleği temizle
        $this->clearGuideCache();
        
        // Activity log kaydet
        $this->logDeleted($guidePlace);
    }

    /**
     * Handle the GuidePlace "restored" event.
     */
    public function restored(GuidePlace $guidePlace): void
    {
        // Önbelleği temizle
        $this->clearGuideCache();
        
        // Activity log kaydet
        $this->logRestored($guidePlace);
    }

    /**
     * Handle the GuidePlace "force deleted" event.
     */
    public function forceDeleted(GuidePlace $guidePlace): void
    {
        // Önbelleği temizle
        $this->clearGuideCache();
        
        // Activity log kaydet
        $this->logForceDeleted($guidePlace);
    }
    
    /**
     * Guide cache'ini temizle
     */
    private function clearGuideCache(): void
    {
        try {
            // Guide ile ilgili cache anahtarlarını temizle
            $cacheKeys = [
                'guide_categories',
                'guide_places',
                'guide_featured',
                'guide_latest',
                'guide_popular',
            ];
            
            foreach ($cacheKeys as $key) {
                Cache::forget($key);
            }
            
        } catch (\Exception $e) {
            // Cache temizleme hatası ana işlemi etkilememelidir
            \Log::warning('Guide cache clearing failed: ' . $e->getMessage());
        }
    }

    /**
     * GuidePlace modeli için hariç tutulacak alanlar
     */
    protected function getExcludedFields(): array
    {
        return [
            'slug', // Slug otomatik oluşturuluyor
        ];
    }
} 