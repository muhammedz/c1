<?php

namespace App\Helpers;

class FileManagerHelper
{
    /**
     * Dosya yolundan göreceli URL oluşturur
     *
     * @param string $path
     * @return string
     */
    public static function getFileUrl($path)
    {
        // Doğrudan .env dosyasından APP_URL değerini kullanalım
        $baseUrl = rtrim(env('APP_URL'), '/');
        
        // Başında / yoksa ekle
        if ($path && substr($path, 0, 1) !== '/') {
            $path = '/' . $path;
        }
        
        // Tam URL döndür
        return $baseUrl . $path;
    }
} 