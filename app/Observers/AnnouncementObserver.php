<?php

namespace App\Observers;

use App\Models\Announcement;
use App\Traits\LogsActivity;
use Illuminate\Support\Facades\Cache;

class AnnouncementObserver
{
    use LogsActivity;

    public function created(Announcement $announcement): void
    {
        $this->clearAnnouncementCache();
        $this->logCreated($announcement);
    }

    public function updated(Announcement $announcement): void
    {
        $this->clearAnnouncementCache();
        $this->logUpdated($announcement);
    }

    public function deleted(Announcement $announcement): void
    {
        $this->clearAnnouncementCache();
        $this->logDeleted($announcement);
    }

    public function restored(Announcement $announcement): void
    {
        $this->clearAnnouncementCache();
        $this->logRestored($announcement);
    }

    public function forceDeleted(Announcement $announcement): void
    {
        $this->clearAnnouncementCache();
        $this->logForceDeleted($announcement);
    }
    
    private function clearAnnouncementCache(): void
    {
        try {
            $cacheKeys = [
                'announcements',
                'announcements_active',
                'announcement_list',
                'latest_announcements',
            ];
            
            foreach ($cacheKeys as $key) {
                Cache::forget($key);
            }
        } catch (\Exception $e) {
            \Log::warning('Announcement cache clearing failed: ' . $e->getMessage());
        }
    }

    protected function getExcludedFields(): array
    {
        return ['slug'];
    }
} 