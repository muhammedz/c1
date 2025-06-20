<?php

namespace App\Observers;

use App\Models\MayorContent;
use App\Traits\LogsActivity;
use Illuminate\Support\Facades\Cache;

class MayorContentObserver
{
    use LogsActivity;

    public function created(MayorContent $mayorContent): void
    {
        $this->clearMayorContentCache();
        $this->logCreated($mayorContent);
    }

    public function updated(MayorContent $mayorContent): void
    {
        $this->clearMayorContentCache();
        $this->logUpdated($mayorContent);
    }

    public function deleted(MayorContent $mayorContent): void
    {
        $this->clearMayorContentCache();
        $this->logDeleted($mayorContent);
    }

    public function restored(MayorContent $mayorContent): void
    {
        $this->clearMayorContentCache();
        $this->logRestored($mayorContent);
    }

    public function forceDeleted(MayorContent $mayorContent): void
    {
        $this->clearMayorContentCache();
        $this->logForceDeleted($mayorContent);
    }
    
    private function clearMayorContentCache(): void
    {
        try {
            $cacheKeys = [
                'mayor_content',
                'mayor_content_active',
                'mayor_info',
                'mayor_biography',
            ];
            
            foreach ($cacheKeys as $key) {
                Cache::forget($key);
            }
        } catch (\Exception $e) {
            \Log::warning('MayorContent cache clearing failed: ' . $e->getMessage());
        }
    }

    protected function getExcludedFields(): array
    {
        return ['slug'];
    }
} 