<?php

namespace App\Observers;

use App\Models\CankayaHouse;
use App\Traits\LogsActivity;
use Illuminate\Support\Facades\Cache;

class CankayaHouseObserver
{
    use LogsActivity;

    public function created(CankayaHouse $cankayaHouse): void
    {
        $this->clearCankayaHouseCache();
        $this->logCreated($cankayaHouse);
    }

    public function updated(CankayaHouse $cankayaHouse): void
    {
        $this->clearCankayaHouseCache();
        $this->logUpdated($cankayaHouse);
    }

    public function deleted(CankayaHouse $cankayaHouse): void
    {
        $this->clearCankayaHouseCache();
        $this->logDeleted($cankayaHouse);
    }

    public function restored(CankayaHouse $cankayaHouse): void
    {
        $this->clearCankayaHouseCache();
        $this->logRestored($cankayaHouse);
    }

    public function forceDeleted(CankayaHouse $cankayaHouse): void
    {
        $this->clearCankayaHouseCache();
        $this->logForceDeleted($cankayaHouse);
    }
    
    private function clearCankayaHouseCache(): void
    {
        try {
            $cacheKeys = [
                'cankaya_houses',
                'cankaya_houses_active',
                'cankaya_house_list',
                'featured_houses',
            ];
            
            foreach ($cacheKeys as $key) {
                Cache::forget($key);
            }
        } catch (\Exception $e) {
            \Log::warning('CankayaHouse cache clearing failed: ' . $e->getMessage());
        }
    }

    protected function getExcludedFields(): array
    {
        return ['slug'];
    }
} 