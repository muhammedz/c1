<?php

namespace App\Observers;

use App\Models\Project;
use App\Traits\LogsActivity;
use Illuminate\Support\Facades\Cache;

class ProjectObserver
{
    use LogsActivity;

    /**
     * Handle the Project "created" event.
     */
    public function created(Project $project): void
    {
        // Önbelleği temizle
        $this->clearProjectCache();
        
        // Activity log kaydet
        $this->logCreated($project);
    }

    /**
     * Handle the Project "updated" event.
     */
    public function updated(Project $project): void
    {
        // Önbelleği temizle
        $this->clearProjectCache();
        
        // Activity log kaydet
        $this->logUpdated($project);
    }

    /**
     * Handle the Project "deleted" event.
     */
    public function deleted(Project $project): void
    {
        // Önbelleği temizle
        $this->clearProjectCache();
        
        // Activity log kaydet
        $this->logDeleted($project);
    }

    /**
     * Handle the Project "restored" event.
     */
    public function restored(Project $project): void
    {
        // Önbelleği temizle
        $this->clearProjectCache();
        
        // Activity log kaydet
        $this->logRestored($project);
    }

    /**
     * Handle the Project "force deleted" event.
     */
    public function forceDeleted(Project $project): void
    {
        // Önbelleği temizle
        $this->clearProjectCache();
        
        // Activity log kaydet
        $this->logForceDeleted($project);
    }
    
    /**
     * Project cache'ini temizle
     */
    private function clearProjectCache(): void
    {
        try {
            // Project ile ilgili cache anahtarlarını temizle
            $cacheKeys = [
                'projects_homepage',
                'projects_featured',
                'projects_categories',
                'projects_active',
                'projects_latest',
            ];
            
            foreach ($cacheKeys as $key) {
                Cache::forget($key);
            }
            
        } catch (\Exception $e) {
            // Cache temizleme hatası ana işlemi etkilememelidir
            \Log::warning('Project cache clearing failed: ' . $e->getMessage());
        }
    }

    /**
     * Project modeli için hariç tutulacak alanlar
     */
    protected function getExcludedFields(): array
    {
        return [
            'slug', // Slug otomatik oluşturuluyor
        ];
    }
} 