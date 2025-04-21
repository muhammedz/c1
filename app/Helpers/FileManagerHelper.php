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
        // URL protokolü ve domain bilgisini sunucudan al
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'] ?? request()->getHost();
        
        // Başında / yoksa ekle
        if ($path && substr($path, 0, 1) !== '/') {
            $path = '/' . $path;
        }
        
        // Tam URL döndür
        return $protocol . '://' . $host . $path;
    }
} 