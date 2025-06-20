<?php

namespace App\Observers;

use App\Models\GuideCategory;
use App\Traits\LogsActivity;
use Illuminate\Support\Facades\Cache;

class GuideCategoryObserver
{
    use LogsActivity;

    /**
     * Handle the GuideCategory "created" event.
     */
    public function created(GuideCategory $guideCategory): void
    {
        // Önbelleği temizle
        $this->clearGuideCache();
        
        // Activity log kaydet
        $this->logCreated($guideCategory);
    }

    /**
     * Handle the GuideCategory "updated" event.
     */
    public function updated(GuideCategory $guideCategory): void
    {
        // Önbelleği temizle
        $this->clearGuideCache();
        
        // Activity log kaydet
        $this->logUpdated($guideCategory);
    }

    /**
     * Handle the GuideCategory "deleted" event.
     */
    public function deleted(GuideCategory $guideCategory): void
    {
        // Önbelleği temizle
        $this->clearGuideCache();
        
        // Activity log kaydet
        $this->logDeleted($guideCategory);
    }

    /**
     * Handle the GuideCategory "restored" event.
     */
    public function restored(GuideCategory $guideCategory): void
    {
        // Önbelleği temizle
        $this->clearGuideCache();
        
        // Activity log kaydet
        $this->logRestored($guideCategory);
    }

    /**
     * Handle the GuideCategory "force deleted" event.
     */
    public function forceDeleted(GuideCategory $guideCategory): void
    {
        // Önbelleği temizle
        $this->clearGuideCache();
        
        // Activity log kaydet
        $this->logForceDeleted($guideCategory);
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
     * GuideCategory modeli için hariç tutulacak alanlar
     */
    protected function getExcludedFields(): array
    {
        return [
            'slug', // Slug otomatik oluşturuluyor
        ];
    }
} 