<?php

namespace App\Observers;

use App\Models\CorporateCategory;
use App\Traits\LogsActivity;
use Illuminate\Support\Facades\Cache;

class CorporateCategoryObserver
{
    use LogsActivity;

    /**
     * Handle the CorporateCategory "created" event.
     */
    public function created(CorporateCategory $corporateCategory): void
    {
        $this->clearCorporateCache();
        $this->logCreated($corporateCategory);
    }

    /**
     * Handle the CorporateCategory "updated" event.
     */
    public function updated(CorporateCategory $corporateCategory): void
    {
        $this->clearCorporateCache();
        $this->logUpdated($corporateCategory);
    }

    /**
     * Handle the CorporateCategory "deleted" event.
     */
    public function deleted(CorporateCategory $corporateCategory): void
    {
        $this->clearCorporateCache();
        $this->logDeleted($corporateCategory);
    }

    /**
     * Handle the CorporateCategory "restored" event.
     */
    public function restored(CorporateCategory $corporateCategory): void
    {
        $this->clearCorporateCache();
        $this->logRestored($corporateCategory);
    }

    /**
     * Handle the CorporateCategory "force deleted" event.
     */
    public function forceDeleted(CorporateCategory $corporateCategory): void
    {
        $this->clearCorporateCache();
        $this->logForceDeleted($corporateCategory);
    }

    /**
     * Corporate cache'ini temizle
     */
    private function clearCorporateCache(): void
    {
        try {
            $cacheKeys = [
                'corporate_categories',
                'corporate_members',
                'corporate_menu',
                'corporate_featured',
            ];
            
            foreach ($cacheKeys as $key) {
                Cache::forget($key);
            }
        } catch (\Exception $e) {
            \Log::warning('Corporate cache clearing failed: ' . $e->getMessage());
        }
    }

    /**
     * CorporateCategory modeli için hariç tutulacak alanlar
     */
    protected function getExcludedFields(): array
    {
        return [
            'slug',
        ];
    }
}
