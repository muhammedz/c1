<?php

namespace App\Observers;

use App\Models\ServiceTag;
use App\Traits\LogsActivity;
use Illuminate\Support\Facades\Cache;

class ServiceTagObserver
{
    use LogsActivity;

    public function created(ServiceTag $serviceTag): void
    {
        $this->clearServiceTagCache();
        $this->logCreated($serviceTag);
    }

    public function updated(ServiceTag $serviceTag): void
    {
        $this->clearServiceTagCache();
        $this->logUpdated($serviceTag);
    }

    public function deleted(ServiceTag $serviceTag): void
    {
        $this->clearServiceTagCache();
        $this->logDeleted($serviceTag);
    }

    public function restored(ServiceTag $serviceTag): void
    {
        $this->clearServiceTagCache();
        $this->logRestored($serviceTag);
    }

    public function forceDeleted(ServiceTag $serviceTag): void
    {
        $this->clearServiceTagCache();
        $this->logForceDeleted($serviceTag);
    }
    
    private function clearServiceTagCache(): void
    {
        try {
            $cacheKeys = [
                'service_tags',
                'service_tags_active',
                'services_by_tag',
                'popular_service_tags',
            ];
            
            foreach ($cacheKeys as $key) {
                Cache::forget($key);
            }
        } catch (\Exception $e) {
            \Log::warning('ServiceTag cache clearing failed: ' . $e->getMessage());
        }
    }

    protected function getExcludedFields(): array
    {
        return ['slug'];
    }
} 