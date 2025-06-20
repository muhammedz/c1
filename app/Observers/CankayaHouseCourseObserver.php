<?php

namespace App\Observers;

use App\Models\CankayaHouseCourse;
use App\Traits\LogsActivity;
use Illuminate\Support\Facades\Cache;

class CankayaHouseCourseObserver
{
    use LogsActivity;

    public function created(CankayaHouseCourse $cankayaHouseCourse): void
    {
        $this->clearCankayaHouseCourseCache();
        $this->logCreated($cankayaHouseCourse);
    }

    public function updated(CankayaHouseCourse $cankayaHouseCourse): void
    {
        $this->clearCankayaHouseCourseCache();
        $this->logUpdated($cankayaHouseCourse);
    }

    public function deleted(CankayaHouseCourse $cankayaHouseCourse): void
    {
        $this->clearCankayaHouseCourseCache();
        $this->logDeleted($cankayaHouseCourse);
    }

    public function restored(CankayaHouseCourse $cankayaHouseCourse): void
    {
        $this->clearCankayaHouseCourseCache();
        $this->logRestored($cankayaHouseCourse);
    }

    public function forceDeleted(CankayaHouseCourse $cankayaHouseCourse): void
    {
        $this->clearCankayaHouseCourseCache();
        $this->logForceDeleted($cankayaHouseCourse);
    }
    
    private function clearCankayaHouseCourseCache(): void
    {
        try {
            $cacheKeys = [
                'cankaya_house_courses',
                'cankaya_house_courses_active',
                'courses_by_house',
                'active_courses',
            ];
            
            foreach ($cacheKeys as $key) {
                Cache::forget($key);
            }
        } catch (\Exception $e) {
            \Log::warning('CankayaHouseCourse cache clearing failed: ' . $e->getMessage());
        }
    }

    protected function getExcludedFields(): array
    {
        return ['slug'];
    }
}