<?php

namespace App\Observers;

use App\Models\ProjectCategory;
use App\Traits\LogsActivity;
use Illuminate\Support\Facades\Cache;

class ProjectCategoryObserver
{
    use LogsActivity;

    public function created(ProjectCategory $projectCategory): void
    {
        $this->clearProjectCategoryCache();
        $this->logCreated($projectCategory);
    }

    public function updated(ProjectCategory $projectCategory): void
    {
        $this->clearProjectCategoryCache();
        $this->logUpdated($projectCategory);
    }

    public function deleted(ProjectCategory $projectCategory): void
    {
        $this->clearProjectCategoryCache();
        $this->logDeleted($projectCategory);
    }

    public function restored(ProjectCategory $projectCategory): void
    {
        $this->clearProjectCategoryCache();
        $this->logRestored($projectCategory);
    }

    public function forceDeleted(ProjectCategory $projectCategory): void
    {
        $this->clearProjectCategoryCache();
        $this->logForceDeleted($projectCategory);
    }
    
    private function clearProjectCategoryCache(): void
    {
        try {
            $cacheKeys = [
                'project_categories',
                'project_categories_active',
                'projects_by_category',
                'project_menu',
            ];
            
            foreach ($cacheKeys as $key) {
                Cache::forget($key);
            }
        } catch (\Exception $e) {
            \Log::warning('ProjectCategory cache clearing failed: ' . $e->getMessage());
        }
    }

    protected function getExcludedFields(): array
    {
        return ['slug'];
    }
} 