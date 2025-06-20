<?php

namespace App\Observers;

use App\Models\MenuSystem;
use App\Traits\LogsActivity;
use Illuminate\Support\Facades\Cache;

class MenuSystemObserver
{
    use LogsActivity;

    public function created(MenuSystem $menuSystem): void
    {
        $this->clearMenuSystemCache();
        $this->logCreated($menuSystem);
    }

    public function updated(MenuSystem $menuSystem): void
    {
        $this->clearMenuSystemCache();
        $this->logUpdated($menuSystem);
    }

    public function deleted(MenuSystem $menuSystem): void
    {
        $this->clearMenuSystemCache();
        $this->logDeleted($menuSystem);
    }

    public function restored(MenuSystem $menuSystem): void
    {
        $this->clearMenuSystemCache();
        $this->logRestored($menuSystem);
    }

    public function forceDeleted(MenuSystem $menuSystem): void
    {
        $this->clearMenuSystemCache();
        $this->logForceDeleted($menuSystem);
    }
    
    private function clearMenuSystemCache(): void
    {
        try {
            $cacheKeys = [
                'menu_system',
                'menu_system_active',
                'main_menu',
                'navigation_menu',
            ];
            
            foreach ($cacheKeys as $key) {
                Cache::forget($key);
            }
        } catch (\Exception $e) {
            \Log::warning('MenuSystem cache clearing failed: ' . $e->getMessage());
        }
    }

    protected function getExcludedFields(): array
    {
        return ['slug'];
    }
} 