<?php

namespace App\Observers;

use App\Models\Category;
use App\Traits\LogsActivity;
use Illuminate\Support\Facades\Cache;

class CategoryObserver
{
    use LogsActivity;

    public function created(Category $category): void
    {
        $this->clearCategoryCache();
        $this->logCreated($category);
    }

    public function updated(Category $category): void
    {
        $this->clearCategoryCache();
        $this->logUpdated($category);
    }

    public function deleted(Category $category): void
    {
        $this->clearCategoryCache();
        $this->logDeleted($category);
    }

    public function restored(Category $category): void
    {
        $this->clearCategoryCache();
        $this->logRestored($category);
    }

    public function forceDeleted(Category $category): void
    {
        $this->clearCategoryCache();
        $this->logForceDeleted($category);
    }
    
    private function clearCategoryCache(): void
    {
        try {
            $cacheKeys = [
                'categories',
                'categories_active',
                'category_list',
                'all_categories',
            ];
            
            foreach ($cacheKeys as $key) {
                Cache::forget($key);
            }
        } catch (\Exception $e) {
            \Log::warning('Category cache clearing failed: ' . $e->getMessage());
        }
    }

    protected function getExcludedFields(): array
    {
        return ['slug'];
    }
} 