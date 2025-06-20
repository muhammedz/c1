<?php

namespace App\Observers;

use App\Models\Redirect;
use App\Traits\LogsActivity;
use Illuminate\Support\Facades\Cache;

class RedirectObserver
{
    use LogsActivity;

    public function created(Redirect $redirect): void
    {
        $this->clearRedirectCache();
        $this->logCreated($redirect);
    }

    public function updated(Redirect $redirect): void
    {
        $this->clearRedirectCache();
        $this->logUpdated($redirect);
    }

    public function deleted(Redirect $redirect): void
    {
        $this->clearRedirectCache();
        $this->logDeleted($redirect);
    }

    public function restored(Redirect $redirect): void
    {
        $this->clearRedirectCache();
        $this->logRestored($redirect);
    }

    public function forceDeleted(Redirect $redirect): void
    {
        $this->clearRedirectCache();
        $this->logForceDeleted($redirect);
    }
    
    private function clearRedirectCache(): void
    {
        try {
            $cacheKeys = [
                'redirects',
                'redirects_active',
                'redirect_rules',
                'url_redirects',
            ];
            
            foreach ($cacheKeys as $key) {
                Cache::forget($key);
            }
        } catch (\Exception $e) {
            \Log::warning('Redirect cache clearing failed: ' . $e->getMessage());
        }
    }

    protected function getExcludedFields(): array
    {
        return ['slug'];
    }
} 