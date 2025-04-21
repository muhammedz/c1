<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Slider;
use App\Models\FileManagerSystem\Media;
use Illuminate\Support\Facades\DB;
use App\Helpers\FileManagerHelper;

class FixFileUrls extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix:file-urls';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mevcut dosya yollarını düzeltir ve dosyaları doğru klasörlere taşır';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Dosya yolları düzeltiliyor...');

        // 1. Media modelindeki yolları düzelt
        $this->fixMediaFiles();
        
        // 2. Slider modelindeki görselleri düzelt
        $this->fixSliderImages();
        
        // 3. Diğer modelleri ekleyebilirsiniz
        
        $this->info('Dosya yolları başarıyla düzeltildi!');
        
        return 0;
    }
    
    /**
     * Media modelindeki dosya yollarını düzeltir
     */
    private function fixMediaFiles()
    {
        $this->info('Media dosyaları düzeltiliyor...');
        
        // Tüm medya kayıtlarını al
        $medias = Media::all();
        $count = 0;
        
        foreach ($medias as $media) {
            $path = $media->path;
            
            // Klasör yapısı kontrolü
            if (!preg_match('#^(images|documents|videos|audios|archives)/#', $path)) {
                // Dosya uzantısını kontrol et
                $extension = pathinfo($path, PATHINFO_EXTENSION);
                $filename = pathinfo($path, PATHINFO_BASENAME);
                
                // Uzantıya göre klasör belirle
                $folderPath = $this->determineFileType($extension);
                
                // Eski ve yeni yollar
                $oldPath = public_path('uploads/' . $path);
                $newRelativePath = $folderPath . '/' . $filename;
                $newPath = public_path('uploads/' . $newRelativePath);
                
                // Klasör yoksa oluştur
                $folderDir = public_path('uploads/' . $folderPath);
                if (!file_exists($folderDir)) {
                    mkdir($folderDir, 0755, true);
                }
                
                // Dosya mevcutsa taşı
                if (file_exists($oldPath) && $oldPath !== $newPath) {
                    $this->info("Taşınıyor: {$oldPath} -> {$newPath}");
                    rename($oldPath, $newPath);
                    
                    // Veritabanını güncelle
                    $media->path = $newRelativePath;
                    $media->url = FileManagerHelper::getFileUrl('uploads/' . $newRelativePath);
                    $media->save();
                    
                    $count++;
                }
            }
        }
        
        $this->info("{$count} adet Media dosyası düzeltildi.");
    }
    
    /**
     * Slider görsellerini düzeltir
     */
    private function fixSliderImages()
    {
        $this->info('Slider görselleri düzeltiliyor...');
        
        // Tüm sliderları al
        $sliders = Slider::whereNotNull('filemanagersystem_image')
            ->where('filemanagersystem_image', 'like', 'uploads/%')
            ->get();
        
        $count = 0;
        
        foreach ($sliders as $slider) {
            $path = $slider->filemanagersystem_image;
            
            // Doğrudan uploads/ altına kaydedilmiş dosyaları düzelt
            if (preg_match('#^uploads/([^/]+)$#', $path, $matches)) {
                $filename = $matches[1];
                $extension = pathinfo($filename, PATHINFO_EXTENSION);
                
                // Uzantıya göre klasör belirle
                $folderPath = $this->determineFileType($extension);
                
                // Eski ve yeni yollar
                $oldPath = public_path($path);
                $newRelativePath = "uploads/{$folderPath}/{$filename}";
                $newPath = public_path($newRelativePath);
                
                // Klasör yoksa oluştur
                $folderDir = public_path("uploads/{$folderPath}");
                if (!file_exists($folderDir)) {
                    mkdir($folderDir, 0755, true);
                }
                
                // Dosya mevcutsa taşı
                if (file_exists($oldPath) && $oldPath !== $newPath) {
                    $this->info("Taşınıyor: {$oldPath} -> {$newPath}");
                    rename($oldPath, $newPath);
                    
                    // Veritabanını güncelle
                    $slider->filemanagersystem_image = $newRelativePath;
                    $slider->save();
                    
                    $count++;
                }
            }
        }
        
        $this->info("{$count} adet Slider görseli düzeltildi.");
    }
    
    /**
     * Dosya uzantısına göre klasör türünü belirler
     */
    private function determineFileType($extension)
    {
        $extension = strtolower($extension);
        
        $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'svg', 'webp'];
        $videoExtensions = ['mp4', 'mov', 'avi', 'webm', 'flv', 'mkv'];
        $audioExtensions = ['mp3', 'wav', 'ogg', 'aac', 'flac'];
        $archiveExtensions = ['zip', 'rar', 'tar', 'gz', '7z'];
        
        if (in_array($extension, $imageExtensions)) {
            return 'images';
        } elseif (in_array($extension, $videoExtensions)) {
            return 'videos';
        } elseif (in_array($extension, $audioExtensions)) {
            return 'audios';
        } elseif (in_array($extension, $archiveExtensions)) {
            return 'archives';
        } else {
            return 'documents';
        }
    }
} 