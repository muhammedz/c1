<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Dosya Yönetim Sistemi Ayarları
    |--------------------------------------------------------------------------
    |
    | Bu dosya, dosya yönetim sistemi ile ilgili yapılandırma ayarlarını içerir.
    |
    */

    /*
    |--------------------------------------------------------------------------
    | Resim Sıkıştırma Ayarları
    |--------------------------------------------------------------------------
    |
    | Yüklenen resimlerin otomatik olarak sıkıştırılması için gerekli ayarlar.
    |
    */
    'image_compression' => [
        // Sıkıştırma özelliği aktif mi?
        'enabled' => true,
        
        // Sıkıştırma kalitesi için öntanımlı seviyeler
        'quality_presets' => [
            'low' => 60,
            'medium' => 80,
            'high' => 90
        ],
        
        // Varsayılan sıkıştırma kalitesi
        'default_quality' => 'medium',
        
        // Maksimum boyut ayarları (genişlik x yükseklik)
        'size_presets' => [
            'small' => [1280, 720],     // HD
            'medium' => [1920, 1080],   // Full HD
            'large' => [2560, 1440]     // 2K
        ],
        
        // Varsayılan boyut sınırı
        'default_size' => 'medium',
        
        // Sıkıştırılacak resim formatları
        'supported_formats' => [
            'image/jpeg',
            'image/jpg',
            'image/png',
            'image/gif',
            'image/webp'
        ],
    ],
    
    /*
    |--------------------------------------------------------------------------
    | WebP Dönüştürme Ayarları
    |--------------------------------------------------------------------------
    |
    | Yüklenen resimlerin WebP formatına dönüştürülmesi için gerekli ayarlar.
    |
    */
    'webp_conversion' => [
        // WebP dönüştürme özelliği aktif mi?
        'enabled' => true,
        
        // WebP formatı için kalite ayarı (0-100)
        'quality' => 85,
        
        // Orijinal dosya da korunsun mu?
        'keep_original' => true,
        
        // WebP'ye dönüştürülecek formatlar
        'formats_to_convert' => [
            'image/jpeg',
            'image/jpg',
            'image/png'
        ]
    ],
    
    /*
    |--------------------------------------------------------------------------
    | Dosya Yükleme Ayarları
    |--------------------------------------------------------------------------
    |
    | Dosya yükleme işlemleri için genel ayarlar.
    |
    */
    'upload' => [
        // Varsayılan disk
        'disk' => 'uploads',
        
        // Dosya türlerine göre klasör isimleri
        'folders' => [
            'image' => 'images',
            'video' => 'videos',
            'audio' => 'audios',
            'document' => 'documents',
            'archive' => 'archives',
            'other' => 'others'
        ],
        
        // İzin verilen dosya uzantıları
        'allowed_extensions' => [
            // Resimler
            'jpg', 'jpeg', 'png', 'gif', 'webp', 'svg', 'bmp',
            // Belgeler
            'pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'txt', 'csv',
            // Medya
            'mp3', 'mp4', 'avi', 'mov', 'wmv', 'wav',
            // Arşivler
            'zip', 'rar', '7z', 'tar', 'gz'
        ],
        
        // Maksimum dosya boyutu (MB)
        'max_size' => 10,
    ],
]; 