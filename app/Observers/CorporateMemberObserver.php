<?php

namespace App\Observers;

use App\Models\CorporateMember;
use App\Traits\LogsActivity;
use Illuminate\Support\Facades\Cache;

class CorporateMemberObserver
{
    use LogsActivity;

    /**
     * Handle the CorporateMember "created" event.
     */
    public function created(CorporateMember $corporateMember): void
    {
        $this->clearCorporateCache();
        $this->logCreated($corporateMember);
    }

    /**
     * Handle the CorporateMember "updated" event.
     */
    public function updated(CorporateMember $corporateMember): void
    {
        $this->clearCorporateCache();
        $this->logUpdated($corporateMember);
    }

    /**
     * Handle the CorporateMember "deleted" event.
     */
    public function deleted(CorporateMember $corporateMember): void
    {
        $this->clearCorporateCache();
        $this->logDeleted($corporateMember);
    }

    /**
     * Handle the CorporateMember "restored" event.
     */
    public function restored(CorporateMember $corporateMember): void
    {
        $this->clearCorporateCache();
        $this->logRestored($corporateMember);
    }

    /**
     * Handle the CorporateMember "force deleted" event.
     */
    public function forceDeleted(CorporateMember $corporateMember): void
    {
        $this->clearCorporateCache();
        $this->logForceDeleted($corporateMember);
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
     * CorporateMember modeli için hariç tutulacak alanlar
     */
    protected function getExcludedFields(): array
    {
        return [
            'slug',
        ];
    }
}
