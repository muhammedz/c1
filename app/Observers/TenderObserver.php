<?php

namespace App\Observers;

use App\Models\Tender;
use App\Traits\LogsActivity;
use Illuminate\Support\Facades\Cache;

class TenderObserver
{
    use LogsActivity;

    public function created(Tender $tender): void
    {
        $this->clearTenderCache();
        $this->logCreated($tender);
    }

    public function updated(Tender $tender): void
    {
        $this->clearTenderCache();
        $this->logUpdated($tender);
    }

    public function deleted(Tender $tender): void
    {
        $this->clearTenderCache();
        $this->logDeleted($tender);
    }

    public function restored(Tender $tender): void
    {
        $this->clearTenderCache();
        $this->logRestored($tender);
    }

    public function forceDeleted(Tender $tender): void
    {
        $this->clearTenderCache();
        $this->logForceDeleted($tender);
    }
    
    private function clearTenderCache(): void
    {
        try {
            $cacheKeys = [
                'tenders',
                'tenders_active',
                'tender_list',
                'latest_tenders',
            ];
            
            foreach ($cacheKeys as $key) {
                Cache::forget($key);
            }
        } catch (\Exception $e) {
            \Log::warning('Tender cache clearing failed: ' . $e->getMessage());
        }
    }

    protected function getExcludedFields(): array
    {
        return ['slug'];
    }
} 