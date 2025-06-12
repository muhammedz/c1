<?php

namespace App\Console\Commands;

use App\Models\FileManagerSystem\Media;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class ClearFileManagerMedia extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'filemanager:clear {--force : Onay istemeden sil}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'File Manager sistemindeki tüm medya dosyalarını temizler';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $mediaCount = Media::count();
        
        if ($mediaCount === 0) {
            $this->info('Silinecek medya dosyası bulunamadı.');
            return;
        }

        $this->info("Toplam {$mediaCount} medya dosyası bulundu.");

        if (!$this->option('force')) {
            if (!$this->confirm('Tüm medya dosyalarını silmek istediğinizden emin misiniz?')) {
                $this->info('İşlem iptal edildi.');
                return;
            }
        }

        $this->info('Medya dosyaları siliniyor...');
        
        $bar = $this->output->createProgressBar($mediaCount);
        $bar->start();

        $deletedCount = 0;
        $errorCount = 0;

        Media::chunk(50, function ($medias) use ($bar, &$deletedCount, &$errorCount) {
            foreach ($medias as $media) {
                try {
                    // Fiziksel dosyaları sil
                    if ($media->path && Storage::disk('uploads')->exists($media->path)) {
                        Storage::disk('uploads')->delete($media->path);
                    }
                    
                    // WebP dosyası varsa sil
                    if ($media->has_webp && $media->webp_path && Storage::disk('uploads')->exists($media->webp_path)) {
                        Storage::disk('uploads')->delete($media->webp_path);
                    }
                    
                    // Veritabanı kaydını sil
                    $media->delete();
                    
                    $deletedCount++;
                } catch (\Exception $e) {
                    $this->error("Dosya silinemedi (ID: {$media->id}): " . $e->getMessage());
                    $errorCount++;
                }
                
                $bar->advance();
            }
        });

        $bar->finish();
        $this->newLine();

        $this->info("✅ {$deletedCount} dosya başarıyla silindi.");
        
        if ($errorCount > 0) {
            $this->warn("⚠️  {$errorCount} dosya silinirken hata oluştu.");
        }

        // Boş klasörleri de temizle
        $this->info('Boş upload klasörleri temizleniyor...');
        $this->cleanEmptyDirectories();
        
        $this->info('🎉 Temizlik işlemi tamamlandı!');
    }

    /**
     * Boş klasörleri temizler
     */
    private function cleanEmptyDirectories()
    {
        $uploadPath = storage_path('app/public/uploads');
        
        if (!is_dir($uploadPath)) {
            return;
        }

        $this->removeEmptyDirectories($uploadPath);
    }

    /**
     * Boş klasörleri recursive olarak siler
     */
    private function removeEmptyDirectories($path)
    {
        if (!is_dir($path)) {
            return;
        }

        $files = array_diff(scandir($path), ['.', '..']);
        
        foreach ($files as $file) {
            $fullPath = $path . DIRECTORY_SEPARATOR . $file;
            if (is_dir($fullPath)) {
                $this->removeEmptyDirectories($fullPath);
            }
        }

        // Klasör boşsa sil
        $files = array_diff(scandir($path), ['.', '..']);
        if (empty($files) && $path !== storage_path('app/public/uploads')) {
            rmdir($path);
            $this->line("Boş klasör silindi: " . basename($path));
        }
    }
} 