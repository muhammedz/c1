<?php

namespace App\Helpers;

class ImageHelper
{
    /**
     * Görsel URL'lerini düzeltmek için yardımcı fonksiyon
     * @param string $url
     * @return string
     */
    public static function fixImageUrl($url)
    {
        if (empty($url)) {
            return '';
        }
        
        // URL zaten rölatif ise ve doğru formatta ise sadece asset() ile birlikte kullan
        if (strpos($url, '/storage/') === 0) {
            // /storage/ kısmını kaldır
            $url = str_replace('/storage/', '', $url);
        }
        
        // URL'deki yinelenen /storage/ yolunu düzelt
        if (strpos($url, '/storage//storage/') !== false) {
            $url = str_replace('/storage//storage/', '/storage/', $url);
        }
        
        if (strpos($url, '/storage/storage/') !== false) {
            $url = str_replace('/storage/storage/', '/storage/', $url);
        }
        
        return asset('storage/' . $url);
    }
} 