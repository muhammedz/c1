<?php

namespace App\Observers;

use App\Models\PageCategory;
use App\Traits\LogsActivity;
use Illuminate\Support\Facades\Cache;

class PageCategoryObserver
{
    use LogsActivity;

    public function created(PageCategory $pageCategory): void
    {
        $this->clearPageCategoryCache();
        $this->logCreated($pageCategory);
    }

    public function updated(PageCategory $pageCategory): void
    {
        $this->clearPageCategoryCache();
        $this->logUpdated($pageCategory);
    }

    public function deleted(PageCategory $pageCategory): void
    {
        $this->clearPageCategoryCache();
        $this->logDeleted($pageCategory);
    }

    public function restored(PageCategory $pageCategory): void
    {
        $this->clearPageCategoryCache();
        $this->logRestored($pageCategory);
    }

    public function forceDeleted(PageCategory $pageCategory): void
    {
        $this->clearPageCategoryCache();
        $this->logForceDeleted($pageCategory);
    }
    
    private function clearPageCategoryCache(): void
    {
        try {
            $cacheKeys = [
                'page_categories',
                'page_categories_active',
                'page_menu',
                'categories_with_pages',
            ];
            
            foreach ($cacheKeys as $key) {
                Cache::forget($key);
            }
        } catch (\Exception $e) {
            \Log::warning('PageCategory cache clearing failed: ' . $e->getMessage());
        }
    }

    protected function getExcludedFields(): array
    {
        return ['slug'];
    }
} 