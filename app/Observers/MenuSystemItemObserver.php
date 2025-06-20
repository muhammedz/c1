<?php

namespace App\Observers;

use App\Models\MenuSystemItem;
use App\Traits\LogsActivity;
use Illuminate\Support\Facades\Cache;

class MenuSystemItemObserver
{
    use LogsActivity;

    public function created(MenuSystemItem $menuSystemItem): void
    {
        $this->clearMenuSystemItemCache();
        $this->logCreated($menuSystemItem);
    }

    public function updated(MenuSystemItem $menuSystemItem): void
    {
        $this->clearMenuSystemItemCache();
        $this->logUpdated($menuSystemItem);
    }

    public function deleted(MenuSystemItem $menuSystemItem): void
    {
        $this->clearMenuSystemItemCache();
        $this->logDeleted($menuSystemItem);
    }

    public function restored(MenuSystemItem $menuSystemItem): void
    {
        $this->clearMenuSystemItemCache();
        $this->logRestored($menuSystemItem);
    }

    public function forceDeleted(MenuSystemItem $menuSystemItem): void
    {
        $this->clearMenuSystemItemCache();
        $this->logForceDeleted($menuSystemItem);
    }
    
    private function clearMenuSystemItemCache(): void
    {
        try {
            $cacheKeys = [
                'menu_system_items',
                'menu_system_items_active',
                'menu_items',
                'navigation_items',
            ];
            
            foreach ($cacheKeys as $key) {
                Cache::forget($key);
            }
        } catch (\Exception $e) {
            \Log::warning('MenuSystemItem cache clearing failed: ' . $e->getMessage());
        }
    }

    protected function getExcludedFields(): array
    {
        return ['slug'];
    }
} 