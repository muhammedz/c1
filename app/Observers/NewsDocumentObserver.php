<?php

namespace App\Observers;

use App\Models\NewsDocument;
use App\Traits\LogsActivity;
use Illuminate\Support\Facades\Cache;

class NewsDocumentObserver
{
    use LogsActivity;

    public function created(NewsDocument $newsDocument): void
    {
        $this->clearNewsDocumentCache();
        $this->logCreated($newsDocument);
    }

    public function updated(NewsDocument $newsDocument): void
    {
        $this->clearNewsDocumentCache();
        $this->logUpdated($newsDocument);
    }

    public function deleted(NewsDocument $newsDocument): void
    {
        $this->clearNewsDocumentCache();
        $this->logDeleted($newsDocument);
    }

    public function restored(NewsDocument $newsDocument): void
    {
        $this->clearNewsDocumentCache();
        $this->logRestored($newsDocument);
    }

    public function forceDeleted(NewsDocument $newsDocument): void
    {
        $this->clearNewsDocumentCache();
        $this->logForceDeleted($newsDocument);
    }
    
    private function clearNewsDocumentCache(): void
    {
        try {
            $cacheKeys = [
                'news_documents',
                'news_documents_active',
                'documents_by_news',
                'news_attachments',
            ];
            
            foreach ($cacheKeys as $key) {
                Cache::forget($key);
            }
        } catch (\Exception $e) {
            \Log::warning('NewsDocument cache clearing failed: ' . $e->getMessage());
        }
    }

    protected function getExcludedFields(): array
    {
        return ['slug'];
    }
} 