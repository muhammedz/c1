<?php

namespace App\Observers;

use App\Models\PageTag;
use App\Traits\LogsActivity;
use Illuminate\Support\Facades\Cache;

class PageTagObserver
{
    use LogsActivity;

    public function created(PageTag $pageTag): void
    {
        $this->clearPageTagCache();
        $this->logCreated($pageTag);
    }

    public function updated(PageTag $pageTag): void
    {
        $this->clearPageTagCache();
        $this->logUpdated($pageTag);
    }

    public function deleted(PageTag $pageTag): void
    {
        $this->clearPageTagCache();
        $this->logDeleted($pageTag);
    }

    public function restored(PageTag $pageTag): void
    {
        $this->clearPageTagCache();
        $this->logRestored($pageTag);
    }

    public function forceDeleted(PageTag $pageTag): void
    {
        $this->clearPageTagCache();
        $this->logForceDeleted($pageTag);
    }
    
    private function clearPageTagCache(): void
    {
        try {
            $cacheKeys = [
                'page_tags',
                'page_tags_active',
                'pages_by_tag',
                'popular_page_tags',
            ];
            
            foreach ($cacheKeys as $key) {
                Cache::forget($key);
            }
        } catch (\Exception $e) {
            \Log::warning('PageTag cache clearing failed: ' . $e->getMessage());
        }
    }

    protected function getExcludedFields(): array
    {
        return ['slug'];
    }
} 