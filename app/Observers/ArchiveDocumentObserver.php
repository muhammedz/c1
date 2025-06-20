<?php

namespace App\Observers;

use App\Models\ArchiveDocument;
use App\Traits\LogsActivity;
use Illuminate\Support\Facades\Cache;

class ArchiveDocumentObserver
{
    use LogsActivity;

    public function created(ArchiveDocument $archiveDocument): void
    {
        $this->clearArchiveDocumentCache();
        $this->logCreated($archiveDocument);
    }

    public function updated(ArchiveDocument $archiveDocument): void
    {
        $this->clearArchiveDocumentCache();
        $this->logUpdated($archiveDocument);
    }

    public function deleted(ArchiveDocument $archiveDocument): void
    {
        $this->clearArchiveDocumentCache();
        $this->logDeleted($archiveDocument);
    }

    public function restored(ArchiveDocument $archiveDocument): void
    {
        $this->clearArchiveDocumentCache();
        $this->logRestored($archiveDocument);
    }

    public function forceDeleted(ArchiveDocument $archiveDocument): void
    {
        $this->clearArchiveDocumentCache();
        $this->logForceDeleted($archiveDocument);
    }
    
    private function clearArchiveDocumentCache(): void
    {
        try {
            $cacheKeys = [
                'archive_documents',
                'archive_documents_active',
                'documents_by_archive',
                'latest_documents',
            ];
            
            foreach ($cacheKeys as $key) {
                Cache::forget($key);
            }
        } catch (\Exception $e) {
            \Log::warning('ArchiveDocument cache clearing failed: ' . $e->getMessage());
        }
    }

    protected function getExcludedFields(): array
    {
        return ['slug'];
    }
} 