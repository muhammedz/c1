<?php

namespace App\Observers;

use App\Models\ArchiveDocumentCategory;
use App\Traits\LogsActivity;
use Illuminate\Support\Facades\Cache;

class ArchiveDocumentCategoryObserver
{
    use LogsActivity;

    public function created(ArchiveDocumentCategory $archiveDocumentCategory): void
    {
        $this->clearArchiveDocumentCategoryCache();
        $this->logCreated($archiveDocumentCategory);
    }

    public function updated(ArchiveDocumentCategory $archiveDocumentCategory): void
    {
        $this->clearArchiveDocumentCategoryCache();
        $this->logUpdated($archiveDocumentCategory);
    }

    public function deleted(ArchiveDocumentCategory $archiveDocumentCategory): void
    {
        $this->clearArchiveDocumentCategoryCache();
        $this->logDeleted($archiveDocumentCategory);
    }

    public function restored(ArchiveDocumentCategory $archiveDocumentCategory): void
    {
        $this->clearArchiveDocumentCategoryCache();
        $this->logRestored($archiveDocumentCategory);
    }

    public function forceDeleted(ArchiveDocumentCategory $archiveDocumentCategory): void
    {
        $this->clearArchiveDocumentCategoryCache();
        $this->logForceDeleted($archiveDocumentCategory);
    }
    
    private function clearArchiveDocumentCategoryCache(): void
    {
        try {
            $cacheKeys = [
                'archive_document_categories',
                'archive_document_categories_active',
                'documents_by_category',
                'archive_categories',
            ];
            
            foreach ($cacheKeys as $key) {
                Cache::forget($key);
            }
        } catch (\Exception $e) {
            \Log::warning('ArchiveDocumentCategory cache clearing failed: ' . $e->getMessage());
        }
    }

    protected function getExcludedFields(): array
    {
        return ['slug'];
    }
} 