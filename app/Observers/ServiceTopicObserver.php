<?php

namespace App\Observers;

use App\Models\ServiceTopic;
use App\Traits\LogsActivity;
use Illuminate\Support\Facades\Cache;

class ServiceTopicObserver
{
    use LogsActivity;

    public function created(ServiceTopic $serviceTopic): void
    {
        $this->clearServiceTopicCache();
        $this->logCreated($serviceTopic);
    }

    public function updated(ServiceTopic $serviceTopic): void
    {
        $this->clearServiceTopicCache();
        $this->logUpdated($serviceTopic);
    }

    public function deleted(ServiceTopic $serviceTopic): void
    {
        $this->clearServiceTopicCache();
        $this->logDeleted($serviceTopic);
    }

    public function restored(ServiceTopic $serviceTopic): void
    {
        $this->clearServiceTopicCache();
        $this->logRestored($serviceTopic);
    }

    public function forceDeleted(ServiceTopic $serviceTopic): void
    {
        $this->clearServiceTopicCache();
        $this->logForceDeleted($serviceTopic);
    }
    
    private function clearServiceTopicCache(): void
    {
        try {
            $cacheKeys = [
                'service_topics',
                'service_topics_active',
                'services_by_topic',
                'popular_topics',
            ];
            
            foreach ($cacheKeys as $key) {
                Cache::forget($key);
            }
        } catch (\Exception $e) {
            \Log::warning('ServiceTopic cache clearing failed: ' . $e->getMessage());
        }
    }

    protected function getExcludedFields(): array
    {
        return ['slug'];
    }
} 