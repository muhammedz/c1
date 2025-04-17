<?php

namespace App\Helpers;

class ImageHelper
{
    /**
     * Verilen storage resim URL'sini düzeltir ve proxy üzerinden sunar
     *
     * @param string $path Resim yolu
     * @return string Düzeltilmiş URL
     */
    public static function fixImageUrl($path)
    {
        // URL içinde storage/ ifadesi var mı kontrol et
        if (strpos($path, 'storage/') !== false) {
            // /storage/ ifadesini çıkar ve symlinks.php üzerinden yönlendir
            $path = preg_replace('/^\/?storage\//', '', $path);
            return url('/symlinks.php?path=' . $path);
        }
        
        return $path;
    }
    
    /**
     * Storage içindeki resim için asset URL oluştur
     *
     * @param string $path Resim yolu (storage/ olmadan)
     * @return string Düzeltilmiş URL
     */
    public static function storageUrl($path)
    {
        return url('/symlinks.php?path=' . $path);
    }
} 