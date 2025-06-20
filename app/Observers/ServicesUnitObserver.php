<?php

namespace App\Observers;

use App\Models\ServicesUnit;
use App\Traits\LogsActivity;
use Illuminate\Support\Facades\Cache;

class ServicesUnitObserver
{
    use LogsActivity;

    public function created(ServicesUnit $servicesUnit): void
    {
        $this->clearServicesUnitCache();
        $this->logCreated($servicesUnit);
    }

    public function updated(ServicesUnit $servicesUnit): void
    {
        $this->clearServicesUnitCache();
        $this->logUpdated($servicesUnit);
    }

    public function deleted(ServicesUnit $servicesUnit): void
    {
        $this->clearServicesUnitCache();
        $this->logDeleted($servicesUnit);
    }

    public function restored(ServicesUnit $servicesUnit): void
    {
        $this->clearServicesUnitCache();
        $this->logRestored($servicesUnit);
    }

    public function forceDeleted(ServicesUnit $servicesUnit): void
    {
        $this->clearServicesUnitCache();
        $this->logForceDeleted($servicesUnit);
    }
    
    private function clearServicesUnitCache(): void
    {
        try {
            $cacheKeys = [
                'services_units',
                'services_units_active',
                'service_units_list',
                'units_by_service',
            ];
            
            foreach ($cacheKeys as $key) {
                Cache::forget($key);
            }
        } catch (\Exception $e) {
            \Log::warning('ServicesUnit cache clearing failed: ' . $e->getMessage());
        }
    }

    protected function getExcludedFields(): array
    {
        return ['slug'];
    }
} 