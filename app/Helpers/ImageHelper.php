<?php

namespace App\Helpers;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ImageHelper
{
    /**
     * Varsayılan görsel URL'i döner
     *
     * @param string $type
     * @return string
     */
    public static function getDefaultImage($type = 'general')
    {
        $defaults = [
            'general' => 'images/default.jpg',
            'avatar' => 'images/default-avatar.jpg',
            'news' => 'images/default-news.jpg',
            'post' => 'images/default-post.jpg',
            'page' => 'images/default-page.jpg',
            'service' => 'images/default-service.jpg',
            'event' => 'images/default-event.jpg',
            'project' => 'images/default-project.jpg',
        ];

        return asset($defaults[$type] ?? $defaults['general']);
    }

    /**
     * Görsel URL'i döner - Bu fonksiyon hem storage hem de public klasöründeki dosyaları kontrol eder
     *
     * @param string|null $path
     * @param bool $logChecks Yolu bulma sürecini loglamak için
     * @return string
     */
    public static function getImageUrl($path, $logChecks = false)
    {
        if (empty($path)) {
            return self::getDefaultImage();
        }

        $originalPath = $path;
        
        // Kontrol sırası için dizi
        $checks = [];
        
        // URL bir tam yol mu kontrol et (https://... şeklinde)
        if (Str::startsWith($path, ['http://', 'https://'])) {
            if ($logChecks) Log::info("Image path is already a full URL: $path");
            return $path;
        }
        
        // path içinde "/storage/" varsa temizle
        if (Str::contains($path, '/storage/')) {
            $path = Str::replaceFirst('/storage/', '', $path);
            $checks[] = "Temizlenmiş yol (storage/ kaldırıldı): $path";
        }
        
        // Public uploads klasöründe doğrudan var mı?
        $uploadsPath = 'uploads/' . $path;
        $checks[] = "Public uploads yolu kontrol ediliyor: $uploadsPath";
        if (file_exists(public_path($uploadsPath))) {
            if ($logChecks) Log::info("Image found in public/uploads: $uploadsPath");
            return asset($uploadsPath);
        }
        
        // Direkt public klasöründe var mı?
        $checks[] = "Doğrudan public yolu kontrol ediliyor: $path";
        if (file_exists(public_path($path))) {
            if ($logChecks) Log::info("Image found directly in public: $path");
            return asset($path);
        }
        
        // Direkt storage/app/public klasöründe var mı?
        $checks[] = "Storage disk yolu kontrol ediliyor: $path";
        if (Storage::disk('public')->exists($path)) {
            if ($logChecks) Log::info("Image found in storage disk: $path");
            return asset('storage/' . $path);
        }
        
        // 'storage/' prefixi ile başlayan bir yol mu?
        if (Str::startsWith($path, 'storage/')) {
            $storagePath = Str::replaceFirst('storage/', '', $path);
            $checks[] = "Storage prefixli yol kontrol ediliyor: $storagePath";
            if (Storage::disk('public')->exists($storagePath)) {
                if ($logChecks) Log::info("Image found in storage after prefix removal: $storagePath");
                return asset('storage/' . $storagePath);
            }
        }
        
        // Dosya bulunamamış, yolları loglayalım
        if ($logChecks) {
            Log::warning("Image not found for path: $originalPath", [
                'original_path' => $originalPath,
                'cleaned_path' => $path,
                'checks_performed' => $checks
            ]);
        }
        
        // Hiçbir yerde bulunamadıysa storage URL'ini döndür (belki başka bir ortamda mevcuttur)
        return asset('storage/' . $path);
    }
    
    /**
     * Debug amaçlı - belirli bir görsel yolunu kontrol edip sonuçları döndür
     *
     * @param string $path
     * @return array
     */
    public static function debugImagePath($path)
    {
        $results = [
            'original_path' => $path,
            'paths_checked' => [],
            'found_at' => null
        ];
        
        // Tam URL kontrolü
        if (Str::startsWith($path, ['http://', 'https://'])) {
            $results['is_url'] = true;
            $results['found_at'] = $path;
            return $results;
        }
        
        // Path temizleme
        $cleanedPath = $path;
        if (Str::contains($path, '/storage/')) {
            $cleanedPath = Str::replaceFirst('/storage/', '', $path);
            $results['cleaned_path'] = $cleanedPath;
        }
        
        // public/uploads kontrolü
        $uploadsPath = 'uploads/' . $cleanedPath;
        $results['paths_checked'][] = [
            'path' => $uploadsPath,
            'full_path' => public_path($uploadsPath),
            'exists' => file_exists(public_path($uploadsPath))
        ];
        
        // Doğrudan public kontrolü
        $results['paths_checked'][] = [
            'path' => $cleanedPath,
            'full_path' => public_path($cleanedPath),
            'exists' => file_exists(public_path($cleanedPath))
        ];
        
        // Storage disk kontrolü
        $results['paths_checked'][] = [
            'path' => $cleanedPath,
            'storage_path' => 'storage/' . $cleanedPath,
            'exists' => Storage::disk('public')->exists($cleanedPath)
        ];
        
        // storage/ prefix kontrolü
        if (Str::startsWith($cleanedPath, 'storage/')) {
            $storageCleanedPath = Str::replaceFirst('storage/', '', $cleanedPath);
            $results['paths_checked'][] = [
                'path' => $storageCleanedPath,
                'storage_path' => 'storage/' . $storageCleanedPath,
                'exists' => Storage::disk('public')->exists($storageCleanedPath)
            ];
        }
        
        // Bulunan ilk yol
        foreach ($results['paths_checked'] as $check) {
            if ($check['exists'] ?? false) {
                if (isset($check['storage_path'])) {
                    $results['found_at'] = asset($check['storage_path']);
                } else {
                    $results['found_at'] = asset($check['path']);
                }
                break;
            }
        }
        
        // Hiçbir şey bulunmadıysa
        if (!$results['found_at']) {
            $results['found_at'] = asset('storage/' . $cleanedPath);
            $results['is_default'] = true;
        }
        
        return $results;
    }
} 