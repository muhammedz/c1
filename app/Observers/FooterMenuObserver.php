<?php

namespace App\Observers;

use App\Models\FooterMenu;
use App\Traits\LogsActivity;
use Illuminate\Support\Facades\Cache;

class FooterMenuObserver
{
    use LogsActivity;

    public function created(FooterMenu $footerMenu): void
    {
        $this->clearFooterMenuCache();
        $this->logCreated($footerMenu);
    }

    public function updated(FooterMenu $footerMenu): void
    {
        $this->clearFooterMenuCache();
        $this->logUpdated($footerMenu);
    }

    public function deleted(FooterMenu $footerMenu): void
    {
        $this->clearFooterMenuCache();
        $this->logDeleted($footerMenu);
    }

    public function restored(FooterMenu $footerMenu): void
    {
        $this->clearFooterMenuCache();
        $this->logRestored($footerMenu);
    }

    public function forceDeleted(FooterMenu $footerMenu): void
    {
        $this->clearFooterMenuCache();
        $this->logForceDeleted($footerMenu);
    }
    
    private function clearFooterMenuCache(): void
    {
        try {
            $cacheKeys = [
                'footer_menu',
                'footer_menu_active',
                'footer_navigation',
                'footer_links',
            ];
            
            foreach ($cacheKeys as $key) {
                Cache::forget($key);
            }
        } catch (\Exception $e) {
            \Log::warning('FooterMenu cache clearing failed: ' . $e->getMessage());
        }
    }

    protected function getExcludedFields(): array
    {
        return ['slug'];
    }
} 