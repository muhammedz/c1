<?php

namespace App\Observers;

use App\Models\HedefKitle;
use App\Traits\LogsActivity;
use Illuminate\Support\Facades\Cache;

class HedefKitleObserver
{
    use LogsActivity;

    public function created(HedefKitle $hedefKitle): void
    {
        $this->clearHedefKitleCache();
        $this->logCreated($hedefKitle);
    }

    public function updated(HedefKitle $hedefKitle): void
    {
        $this->clearHedefKitleCache();
        $this->logUpdated($hedefKitle);
    }

    public function deleted(HedefKitle $hedefKitle): void
    {
        $this->clearHedefKitleCache();
        $this->logDeleted($hedefKitle);
    }

    public function restored(HedefKitle $hedefKitle): void
    {
        $this->clearHedefKitleCache();
        $this->logRestored($hedefKitle);
    }

    public function forceDeleted(HedefKitle $hedefKitle): void
    {
        $this->clearHedefKitleCache();
        $this->logForceDeleted($hedefKitle);
    }
    
    private function clearHedefKitleCache(): void
    {
        try {
            $cacheKeys = [
                'hedef_kitle',
                'hedef_kitle_active',
                'target_audiences',
                'audience_list',
            ];
            
            foreach ($cacheKeys as $key) {
                Cache::forget($key);
            }
        } catch (\Exception $e) {
            \Log::warning('HedefKitle cache clearing failed: ' . $e->getMessage());
        }
    }

    protected function getExcludedFields(): array
    {
        return ['slug'];
    }
} 