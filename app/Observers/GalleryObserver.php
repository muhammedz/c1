<?php

namespace App\Observers;

use App\Models\Gallery;
use App\Traits\LogsActivity;
use Illuminate\Support\Facades\Cache;

class GalleryObserver
{
    use LogsActivity;

    public function created(Gallery $gallery): void
    {
        $this->clearGalleryCache();
        $this->logCreated($gallery);
    }

    public function updated(Gallery $gallery): void
    {
        $this->clearGalleryCache();
        $this->logUpdated($gallery);
    }

    public function deleted(Gallery $gallery): void
    {
        $this->clearGalleryCache();
        $this->logDeleted($gallery);
    }

    public function restored(Gallery $gallery): void
    {
        $this->clearGalleryCache();
        $this->logRestored($gallery);
    }

    public function forceDeleted(Gallery $gallery): void
    {
        $this->clearGalleryCache();
        $this->logForceDeleted($gallery);
    }
    
    private function clearGalleryCache(): void
    {
        try {
            $cacheKeys = [
                'galleries',
                'galleries_active',
                'gallery_list',
                'featured_galleries',
            ];
            
            foreach ($cacheKeys as $key) {
                Cache::forget($key);
            }
        } catch (\Exception $e) {
            \Log::warning('Gallery cache clearing failed: ' . $e->getMessage());
        }
    }

    protected function getExcludedFields(): array
    {
        return ['slug'];
    }
} 