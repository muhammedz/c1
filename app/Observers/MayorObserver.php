<?php

namespace App\Observers;

use App\Models\Mayor;
use App\Traits\LogsActivity;
use Illuminate\Support\Facades\Cache;

class MayorObserver
{
    use LogsActivity;

    public function created(Mayor $mayor): void
    {
        $this->clearMayorCache();
        $this->logCreated($mayor);
    }

    public function updated(Mayor $mayor): void
    {
        $this->clearMayorCache();
        $this->logUpdated($mayor);
    }

    public function deleted(Mayor $mayor): void
    {
        $this->clearMayorCache();
        $this->logDeleted($mayor);
    }

    public function restored(Mayor $mayor): void
    {
        $this->clearMayorCache();
        $this->logRestored($mayor);
    }

    public function forceDeleted(Mayor $mayor): void
    {
        $this->clearMayorCache();
        $this->logForceDeleted($mayor);
    }
    
    private function clearMayorCache(): void
    {
        try {
            $cacheKeys = [
                'mayor_info',
                'mayor_active',
                'mayor_content',
                'current_mayor',
            ];
            
            foreach ($cacheKeys as $key) {
                Cache::forget($key);
            }
        } catch (\Exception $e) {
            \Log::warning('Mayor cache clearing failed: ' . $e->getMessage());
        }
    }

    protected function getExcludedFields(): array
    {
        return ['slug'];
    }
} 