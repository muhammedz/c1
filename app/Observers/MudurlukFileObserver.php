<?php

namespace App\Observers;

use App\Models\MudurlukFile;
use App\Traits\LogsActivity;
use Illuminate\Support\Facades\Cache;

class MudurlukFileObserver
{
    use LogsActivity;

    public function created(MudurlukFile $mudurlukFile): void
    {
        $this->clearMudurlukFileCache();
        $this->logCreated($mudurlukFile);
    }

    public function updated(MudurlukFile $mudurlukFile): void
    {
        $this->clearMudurlukFileCache();
        $this->logUpdated($mudurlukFile);
    }

    public function deleted(MudurlukFile $mudurlukFile): void
    {
        $this->clearMudurlukFileCache();
        $this->logDeleted($mudurlukFile);
    }

    public function restored(MudurlukFile $mudurlukFile): void
    {
        $this->clearMudurlukFileCache();
        $this->logRestored($mudurlukFile);
    }

    public function forceDeleted(MudurlukFile $mudurlukFile): void
    {
        $this->clearMudurlukFileCache();
        $this->logForceDeleted($mudurlukFile);
    }
    
    private function clearMudurlukFileCache(): void
    {
        try {
            $cacheKeys = [
                'mudurluk_files',
                'mudurluk_files_active',
                'files_by_mudurluk',
                'department_files',
            ];
            
            foreach ($cacheKeys as $key) {
                Cache::forget($key);
            }
        } catch (\Exception $e) {
            \Log::warning('MudurlukFile cache clearing failed: ' . $e->getMessage());
        }
    }

    protected function getExcludedFields(): array
    {
        return ['slug'];
    }
} 