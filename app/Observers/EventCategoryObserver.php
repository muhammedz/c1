<?php

namespace App\Observers;

use App\Models\EventCategory;
use App\Traits\LogsActivity;
use Illuminate\Support\Facades\Cache;

class EventCategoryObserver
{
    use LogsActivity;

    public function created(EventCategory $eventCategory): void
    {
        $this->clearEventCategoryCache();
        $this->logCreated($eventCategory);
    }

    public function updated(EventCategory $eventCategory): void
    {
        $this->clearEventCategoryCache();
        $this->logUpdated($eventCategory);
    }

    public function deleted(EventCategory $eventCategory): void
    {
        $this->clearEventCategoryCache();
        $this->logDeleted($eventCategory);
    }

    public function restored(EventCategory $eventCategory): void
    {
        $this->clearEventCategoryCache();
        $this->logRestored($eventCategory);
    }

    public function forceDeleted(EventCategory $eventCategory): void
    {
        $this->clearEventCategoryCache();
        $this->logForceDeleted($eventCategory);
    }
    
    private function clearEventCategoryCache(): void
    {
        try {
            $cacheKeys = [
                'event_categories',
                'event_categories_active',
                'events_by_category',
                'event_menu',
            ];
            
            foreach ($cacheKeys as $key) {
                Cache::forget($key);
            }
        } catch (\Exception $e) {
            \Log::warning('EventCategory cache clearing failed: ' . $e->getMessage());
        }
    }

    protected function getExcludedFields(): array
    {
        return ['slug'];
    }
} 