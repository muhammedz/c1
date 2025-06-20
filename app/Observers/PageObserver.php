<?php

namespace App\Observers;

use App\Models\Page;
use App\Traits\LogsActivity;
use Illuminate\Support\Facades\Cache;

class PageObserver
{
    use LogsActivity;

    /**
     * Handle the Page "created" event.
     */
    public function created(Page $page): void
    {
        $this->clearPageCache();
        $this->logCreated($page);
    }

    /**
     * Handle the Page "updated" event.
     */
    public function updated(Page $page): void
    {
        $this->clearPageCache();
        $this->logUpdated($page);
    }

    /**
     * Handle the Page "deleted" event.
     */
    public function deleted(Page $page): void
    {
        $this->clearPageCache();
        $this->logDeleted($page);
    }

    /**
     * Handle the Page "restored" event.
     */
    public function restored(Page $page): void
    {
        $this->clearPageCache();
        $this->logRestored($page);
    }

    /**
     * Handle the Page "force deleted" event.
     */
    public function forceDeleted(Page $page): void
    {
        $this->clearPageCache();
        $this->logForceDeleted($page);
    }

    /**
     * Page cache'ini temizle
     */
    private function clearPageCache(): void
    {
        try {
            $cacheKeys = [
                'pages_menu',
                'pages_featured',
                'pages_latest',
                'pages_categories',
                'pages_sidebar',
            ];
            
            foreach ($cacheKeys as $key) {
                Cache::forget($key);
            }
        } catch (\Exception $e) {
            \Log::warning('Page cache clearing failed: ' . $e->getMessage());
        }
    }

    /**
     * Page modeli için hariç tutulacak alanlar
     */
    protected function getExcludedFields(): array
    {
        return [
            'views',
            'slug',
        ];
    }
}
