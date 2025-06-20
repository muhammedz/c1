<?php

namespace App\Observers;

use App\Models\HeaderSetting;
use App\Traits\LogsActivity;
use Illuminate\Support\Facades\Cache;

class HeaderSettingObserver
{
    use LogsActivity;

    public function created(HeaderSetting $headerSetting): void
    {
        $this->clearHeaderSettingCache();
        $this->logCreated($headerSetting);
    }

    public function updated(HeaderSetting $headerSetting): void
    {
        $this->clearHeaderSettingCache();
        $this->logUpdated($headerSetting);
    }

    public function deleted(HeaderSetting $headerSetting): void
    {
        $this->clearHeaderSettingCache();
        $this->logDeleted($headerSetting);
    }

    public function restored(HeaderSetting $headerSetting): void
    {
        $this->clearHeaderSettingCache();
        $this->logRestored($headerSetting);
    }

    public function forceDeleted(HeaderSetting $headerSetting): void
    {
        $this->clearHeaderSettingCache();
        $this->logForceDeleted($headerSetting);
    }
    
    private function clearHeaderSettingCache(): void
    {
        try {
            $cacheKeys = [
                'header_settings',
                'header_settings_active',
                'header_config',
                'site_header',
            ];
            
            foreach ($cacheKeys as $key) {
                Cache::forget($key);
            }
        } catch (\Exception $e) {
            \Log::warning('HeaderSetting cache clearing failed: ' . $e->getMessage());
        }
    }

    protected function getExcludedFields(): array
    {
        return ['slug'];
    }
} 