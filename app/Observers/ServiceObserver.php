<?php

namespace App\Observers;

use App\Models\Service;
use App\Traits\LogsActivity;
use Illuminate\Support\Facades\Cache;

class ServiceObserver
{
    use LogsActivity;

    /**
     * Handle the Service "created" event.
     */
    public function created(Service $service): void
    {
        $this->clearServiceCache();
        $this->logCreated($service);
    }

    /**
     * Handle the Service "updated" event.
     */
    public function updated(Service $service): void
    {
        $this->clearServiceCache();
        $this->logUpdated($service);
    }

    /**
     * Handle the Service "deleted" event.
     */
    public function deleted(Service $service): void
    {
        $this->clearServiceCache();
        $this->logDeleted($service);
    }

    /**
     * Handle the Service "restored" event.
     */
    public function restored(Service $service): void
    {
        $this->clearServiceCache();
        $this->logRestored($service);
    }

    /**
     * Handle the Service "force deleted" event.
     */
    public function forceDeleted(Service $service): void
    {
        $this->clearServiceCache();
        $this->logForceDeleted($service);
    }

    /**
     * Service cache'ini temizle
     */
    private function clearServiceCache(): void
    {
        try {
            $cacheKeys = [
                'services_menu',
                'services_featured',
                'services_categories',
                'services_popular',
                'services_sidebar',
            ];
            
            foreach ($cacheKeys as $key) {
                Cache::forget($key);
            }
        } catch (\Exception $e) {
            \Log::warning('Service cache clearing failed: ' . $e->getMessage());
        }
    }

    /**
     * Service modeli için hariç tutulacak alanlar
     */
    protected function getExcludedFields(): array
    {
        return [
            'views',
            'view_count', // Görüntülenme sayısı değişikliği loglanmasın
            'slug',
        ];
    }
}
