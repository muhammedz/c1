<?php

namespace App\Observers;

use App\Models\Archive;
use App\Traits\LogsActivity;
use Illuminate\Support\Facades\Cache;

class ArchiveObserver
{
    use LogsActivity;

    /**
     * Handle the Archive "created" event.
     */
    public function created(Archive $archive): void
    {
        // Önbelleği temizle
        $this->clearArchiveCache();
        
        // Activity log kaydet
        $this->logCreated($archive);
    }

    /**
     * Handle the Archive "updated" event.
     */
    public function updated(Archive $archive): void
    {
        // Önbelleği temizle
        $this->clearArchiveCache();
        
        // Activity log kaydet
        $this->logUpdated($archive);
    }

    /**
     * Handle the Archive "deleted" event.
     */
    public function deleted(Archive $archive): void
    {
        // Önbelleği temizle
        $this->clearArchiveCache();
        
        // Activity log kaydet
        $this->logDeleted($archive);
    }

    /**
     * Handle the Archive "restored" event.
     */
    public function restored(Archive $archive): void
    {
        // Önbelleği temizle
        $this->clearArchiveCache();
        
        // Activity log kaydet
        $this->logRestored($archive);
    }

    /**
     * Handle the Archive "force deleted" event.
     */
    public function forceDeleted(Archive $archive): void
    {
        // Önbelleği temizle
        $this->clearArchiveCache();
        
        // Activity log kaydet
        $this->logForceDeleted($archive);
    }
    
    /**
     * Archive cache'ini temizle
     */
    private function clearArchiveCache(): void
    {
        try {
            // Archive ile ilgili cache anahtarlarını temizle
            $cacheKeys = [
                'archives_latest',
                'archives_featured',
                'archives_categories',
                'archives_popular',
                'archives_published',
            ];
            
            foreach ($cacheKeys as $key) {
                Cache::forget($key);
            }
            
        } catch (\Exception $e) {
            // Cache temizleme hatası ana işlemi etkilememelidir
            \Log::warning('Archive cache clearing failed: ' . $e->getMessage());
        }
    }

    /**
     * Archive modeli için hariç tutulacak alanlar
     */
    protected function getExcludedFields(): array
    {
        return [
            'slug', // Slug otomatik oluşturuluyor
            'view_count', // Görüntülenme sayısı değişikliği loglanmasın
        ];
    }
} 