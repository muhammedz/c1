<?php

namespace App\Observers;

use App\Models\MenuCategory;
use App\Traits\LogsActivity;
use Illuminate\Support\Facades\Cache;

class MenuCategoryObserver
{
    use LogsActivity;

    public function created(MenuCategory $menuCategory): void
    {
        $this->clearMenuCategoryCache();
        $this->logCreated($menuCategory);
    }

    public function updated(MenuCategory $menuCategory): void
    {
        $this->clearMenuCategoryCache();
        $this->logUpdated($menuCategory);
    }

    public function deleted(MenuCategory $menuCategory): void
    {
        $this->clearMenuCategoryCache();
        $this->logDeleted($menuCategory);
    }

    public function restored(MenuCategory $menuCategory): void
    {
        $this->clearMenuCategoryCache();
        $this->logRestored($menuCategory);
    }

    public function forceDeleted(MenuCategory $menuCategory): void
    {
        $this->clearMenuCategoryCache();
        $this->logForceDeleted($menuCategory);
    }
    
    private function clearMenuCategoryCache(): void
    {
        try {
            $cacheKeys = [
                'menu_categories',
                'menu_categories_active',
                'menu_category_list',
                'navigation_categories',
            ];
            
            foreach ($cacheKeys as $key) {
                Cache::forget($key);
            }
        } catch (\Exception $e) {
            \Log::warning('MenuCategory cache clearing failed: ' . $e->getMessage());
        }
    }

    protected function getExcludedFields(): array
    {
        return ['slug'];
    }
} 