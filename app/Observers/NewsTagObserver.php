<?php

namespace App\Observers;

use App\Models\NewsTag;
use App\Traits\LogsActivity;
use Illuminate\Support\Facades\Cache;

class NewsTagObserver
{
    use LogsActivity;

    public function created(NewsTag $newsTag): void
    {
        $this->clearNewsTagCache();
        $this->logCreated($newsTag);
    }

    public function updated(NewsTag $newsTag): void
    {
        $this->clearNewsTagCache();
        $this->logUpdated($newsTag);
    }

    public function deleted(NewsTag $newsTag): void
    {
        $this->clearNewsTagCache();
        $this->logDeleted($newsTag);
    }

    public function restored(NewsTag $newsTag): void
    {
        $this->clearNewsTagCache();
        $this->logRestored($newsTag);
    }

    public function forceDeleted(NewsTag $newsTag): void
    {
        $this->clearNewsTagCache();
        $this->logForceDeleted($newsTag);
    }
    
    private function clearNewsTagCache(): void
    {
        try {
            $cacheKeys = [
                'news_tags',
                'news_tags_active',
                'news_by_tag',
                'popular_tags',
            ];
            
            foreach ($cacheKeys as $key) {
                Cache::forget($key);
            }
        } catch (\Exception $e) {
            \Log::warning('NewsTag cache clearing failed: ' . $e->getMessage());
        }
    }

    protected function getExcludedFields(): array
    {
        return ['slug'];
    }
} 