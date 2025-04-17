<?php

namespace App\Services\FileManager;

use UniSharp\LaravelFilemanager\Handlers\LfmPathHelper;

class CustomLfmPathHelper extends LfmPathHelper
{
    /**
     * Get URL for the file relative to the application public directory
     *
     * @param string $path The path to be returned
     * @return string
     */
    public function path($path)
    {
        $finalPath = parent::path($path);
        
        // /storage/ ile başlayan yolları /uploads/ olarak değiştir
        if (strpos($finalPath, '/storage/') === 0) {
            $finalPath = str_replace('/storage/', '/uploads/', $finalPath);
        }
        
        return $finalPath;
    }
    
    /**
     * Get a file URL by its path
     *
     * @param string $path The path to the file
     * @return string
     */
    public function url($path)
    {
        $url = parent::url($path);
        
        // /storage/ ile başlayan URL'leri /uploads/ olarak değiştir
        if (strpos($url, '/storage/') !== false) {
            $url = str_replace('/storage/', '/uploads/', $url);
        }
        
        return $url;
    }
    
    /**
     * Get storage path from the supplied path
     *
     * @param string $path The path to get the storage path from
     * @return string
     */
    public function storage($path)
    {
        // Get storage path from parent
        $storagePath = parent::storage($path);
        
        // Check if the storage path exists
        if (!file_exists($storagePath)) {
            // Convert storage path to uploads path
            $uploadsPath = str_replace(
                storage_path('app/public'),
                public_path('uploads'),
                $storagePath
            );
            
            // If uploads path exists, return it
            if (file_exists($uploadsPath)) {
                return $uploadsPath;
            }
        }
        
        return $storagePath;
    }
} 