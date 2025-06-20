<?php

namespace App\Observers;

use App\Models\Slider;
use App\Traits\LogsActivity;
use Illuminate\Support\Facades\Cache;

class SliderObserver
{
    use LogsActivity;

    /**
     * Handle the Slider "created" event.
     */
    public function created(Slider $slider): void
    {
        // Önbelleği temizle
        $this->clearSliderCache();
        
        // Activity log kaydet
        $this->logCreated($slider);
    }

    /**
     * Handle the Slider "updated" event.
     */
    public function updated(Slider $slider): void
    {
        // Önbelleği temizle
        $this->clearSliderCache();
        
        // Activity log kaydet
        $this->logUpdated($slider);
    }

    /**
     * Handle the Slider "deleted" event.
     */
    public function deleted(Slider $slider): void
    {
        // Önbelleği temizle
        $this->clearSliderCache();
        
        // Activity log kaydet
        $this->logDeleted($slider);
    }

    /**
     * Handle the Slider "restored" event.
     */
    public function restored(Slider $slider): void
    {
        // Önbelleği temizle
        $this->clearSliderCache();
        
        // Activity log kaydet
        $this->logRestored($slider);
    }

    /**
     * Handle the Slider "force deleted" event.
     */
    public function forceDeleted(Slider $slider): void
    {
        // Önbelleği temizle
        $this->clearSliderCache();
        
        // Activity log kaydet
        $this->logForceDeleted($slider);
    }
    
    /**
     * Slider cache'ini temizle
     */
    private function clearSliderCache(): void
    {
        try {
            // Slider ile ilgili cache anahtarlarını temizle
            $cacheKeys = [
                'homepage_sliders',
                'sliders_active',
                'sliders_ordered',
                'homepage_content',
                'main_slider',
            ];
            
            foreach ($cacheKeys as $key) {
                Cache::forget($key);
            }
            
        } catch (\Exception $e) {
            // Cache temizleme hatası ana işlemi etkilememelidir
            \Log::warning('Slider cache clearing failed: ' . $e->getMessage());
        }
    }

    /**
     * Slider modeli için hariç tutulacak alanlar
     */
    protected function getExcludedFields(): array
    {
        return [
            // Tüm alanlar loglanacak
        ];
    }
} 