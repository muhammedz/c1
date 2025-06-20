<?php

namespace App\Observers;

use App\Models\Mudurluk;
use App\Traits\LogsActivity;
use Illuminate\Support\Facades\Cache;

class MudurlukObserver
{
    use LogsActivity;

    public function created(Mudurluk $mudurluk): void
    {
        $this->clearMudurlukCache();
        $this->logCreated($mudurluk);
    }

    public function updated(Mudurluk $mudurluk): void
    {
        $this->clearMudurlukCache();
        $this->logUpdated($mudurluk);
    }

    public function deleted(Mudurluk $mudurluk): void
    {
        $this->clearMudurlukCache();
        $this->logDeleted($mudurluk);
    }

    public function restored(Mudurluk $mudurluk): void
    {
        $this->clearMudurlukCache();
        $this->logRestored($mudurluk);
    }

    public function forceDeleted(Mudurluk $mudurluk): void
    {
        $this->clearMudurlukCache();
        $this->logForceDeleted($mudurluk);
    }
    
    private function clearMudurlukCache(): void
    {
        try {
            $cacheKeys = [
                'mudurlukler',
                'mudurlukler_active',
                'mudurluk_list',
                'departments',
            ];
            
            foreach ($cacheKeys as $key) {
                Cache::forget($key);
            }
        } catch (\Exception $e) {
            \Log::warning('Mudurluk cache clearing failed: ' . $e->getMessage());
        }
    }

    protected function getExcludedFields(): array
    {
        return ['slug'];
    }
} 