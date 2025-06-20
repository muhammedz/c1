<?php

namespace App\Observers;

use App\Models\Event;
use App\Traits\LogsActivity;
use Illuminate\Support\Facades\Cache;

class EventObserver
{
    use LogsActivity;

    /**
     * Handle the Event "created" event.
     */
    public function created(Event $event): void
    {
        $this->clearEventCache();
        $this->logCreated($event);
    }

    /**
     * Handle the Event "updated" event.
     */
    public function updated(Event $event): void
    {
        $this->clearEventCache();
        $this->logUpdated($event);
    }

    /**
     * Handle the Event "deleted" event.
     */
    public function deleted(Event $event): void
    {
        $this->clearEventCache();
        $this->logDeleted($event);
    }

    /**
     * Handle the Event "restored" event.
     */
    public function restored(Event $event): void
    {
        $this->clearEventCache();
        $this->logRestored($event);
    }

    /**
     * Handle the Event "force deleted" event.
     */
    public function forceDeleted(Event $event): void
    {
        $this->clearEventCache();
        $this->logForceDeleted($event);
    }

    /**
     * Event cache'ini temizle
     */
    private function clearEventCache(): void
    {
        try {
            $cacheKeys = [
                'events_upcoming',
                'events_featured',
                'events_categories',
                'events_calendar',
                'events_sidebar',
            ];
            
            foreach ($cacheKeys as $key) {
                Cache::forget($key);
            }
        } catch (\Exception $e) {
            \Log::warning('Event cache clearing failed: ' . $e->getMessage());
        }
    }

    /**
     * Event modeli için hariç tutulacak alanlar
     */
    protected function getExcludedFields(): array
    {
        return [
            'views',
            'slug',
        ];
    }
}
