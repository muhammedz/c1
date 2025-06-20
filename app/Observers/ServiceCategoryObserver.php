<?php

namespace App\Observers;

use App\Models\ServiceCategory;
use App\Traits\LogsActivity;
use Illuminate\Support\Facades\Cache;

class ServiceCategoryObserver
{
    use LogsActivity;

    /**
     * Handle the ServiceCategory "created" event.
     */
    public function created(ServiceCategory $serviceCategory): void
    {
        // Önbelleği temizle
        $this->clearServiceCategoryCache();
        
        // Activity log kaydet
        $this->logCreated($serviceCategory);
    }

    /**
     * Handle the ServiceCategory "updated" event.
     */
    public function updated(ServiceCategory $serviceCategory): void
    {
        // Önbelleği temizle
        $this->clearServiceCategoryCache();
        
        // Activity log kaydet
        $this->logUpdated($serviceCategory);
    }

    /**
     * Handle the ServiceCategory "deleted" event.
     */
    public function deleted(ServiceCategory $serviceCategory): void
    {
        // Önbelleği temizle
        $this->clearServiceCategoryCache();
        
        // Activity log kaydet
        $this->logDeleted($serviceCategory);
    }

    /**
     * Handle the ServiceCategory "restored" event.
     */
    public function restored(ServiceCategory $serviceCategory): void
    {
        // Önbelleği temizle
        $this->clearServiceCategoryCache();
        
        // Activity log kaydet
        $this->logRestored($serviceCategory);
    }

    /**
     * Handle the ServiceCategory "force deleted" event.
     */
    public function forceDeleted(ServiceCategory $serviceCategory): void
    {
        // Önbelleği temizle
        $this->clearServiceCategoryCache();
        
        // Activity log kaydet
        $this->logForceDeleted($serviceCategory);
    }
    
    /**
     * ServiceCategory cache'ini temizle
     */
    private function clearServiceCategoryCache(): void
    {
        try {
            // ServiceCategory ile ilgili cache anahtarlarını temizle
            $cacheKeys = [
                'service_categories',
                'service_categories_active',
                'service_menu',
                'categories_with_services',
                'mudurlukler_categories',
            ];
            
            foreach ($cacheKeys as $key) {
                Cache::forget($key);
            }
            
        } catch (\Exception $e) {
            // Cache temizleme hatası ana işlemi etkilememelidir
            \Log::warning('ServiceCategory cache clearing failed: ' . $e->getMessage());
        }
    }

    /**
     * ServiceCategory modeli için hariç tutulacak alanlar
     */
    protected function getExcludedFields(): array
    {
        return [
            'slug', // Slug otomatik oluşturuluyor
        ];
    }
} 