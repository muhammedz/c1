<?php

namespace App\Observers;

use App\Models\MenuItem;
use App\Traits\LogsActivity;
use Illuminate\Support\Facades\Cache;

class MenuItemObserver
{
    use LogsActivity;

    public function created(MenuItem $menuItem): void
    {
        $this->clearMenuItemCache();
        $this->logCreated($menuItem);
    }

    public function updated(MenuItem $menuItem): void
    {
        $this->clearMenuItemCache();
        $this->logUpdated($menuItem);
    }

    public function deleted(MenuItem $menuItem): void
    {
        $this->clearMenuItemCache();
        $this->logDeleted($menuItem);
    }

    public function restored(MenuItem $menuItem): void
    {
        $this->clearMenuItemCache();
        $this->logRestored($menuItem);
    }

    public function forceDeleted(MenuItem $menuItem): void
    {
        $this->clearMenuItemCache();
        $this->logForceDeleted($menuItem);
    }
    
    private function clearMenuItemCache(): void
    {
        try {
            $cacheKeys = [
                'menu_items',
                'menu_items_active',
                'navigation_menu_items',
                'menu_structure',
            ];
            
            foreach ($cacheKeys as $key) {
                Cache::forget($key);
            }
        } catch (\Exception $e) {
            \Log::warning('MenuItem cache clearing failed: ' . $e->getMessage());
        }
    }

    protected function getExcludedFields(): array
    {
        return ['slug'];
    }
} 