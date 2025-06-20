<?php

namespace App\Observers;

use App\Models\SearchSetting;
use App\Traits\LogsActivity;
use Illuminate\Support\Facades\Cache;

class SearchSettingObserver
{
    use LogsActivity;

    public function created(SearchSetting $searchSetting): void
    {
        $this->clearSearchSettingCache();
        $this->logCreated($searchSetting);
    }

    public function updated(SearchSetting $searchSetting): void
    {
        $this->clearSearchSettingCache();
        $this->logUpdated($searchSetting);
    }

    public function deleted(SearchSetting $searchSetting): void
    {
        $this->clearSearchSettingCache();
        $this->logDeleted($searchSetting);
    }

    public function restored(SearchSetting $searchSetting): void
    {
        $this->clearSearchSettingCache();
        $this->logRestored($searchSetting);
    }

    public function forceDeleted(SearchSetting $searchSetting): void
    {
        $this->clearSearchSettingCache();
        $this->logForceDeleted($searchSetting);
    }
    
    private function clearSearchSettingCache(): void
    {
        try {
            $cacheKeys = [
                'search_settings',
                'search_settings_active',
                'search_config',
                'search_parameters',
            ];
            
            foreach ($cacheKeys as $key) {
                Cache::forget($key);
            }
        } catch (\Exception $e) {
            \Log::warning('SearchSetting cache clearing failed: ' . $e->getMessage());
        }
    }

    protected function getExcludedFields(): array
    {
        return ['slug'];
    }
} 