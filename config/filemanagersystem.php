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
    | Güvenlik Ayarları
    |--------------------------------------------------------------------------
    |
    | Dosya yükleme güvenliği için kritik ayarlar.
    |
    */
    'security' => [
        // Dosya adı güvenlik kontrolü
        'sanitize_filename' => true,
        
        // Magic number kontrolü (dosya içeriği doğrulama)
        'validate_magic_numbers' => true,
        
        // Executable dosya kontrolü
        'block_executable_files' => true,
        
        // Maksimum dosya boyutu (MB)
        'max_file_size' => 50,
        
        // İzin verilen dosya uzantıları
        'allowed_extensions' => [
            'jpg', 'jpeg', 'png', 'gif', 'webp', 'svg', 'bmp',
            'pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'txt', 'csv',
            'mp4', 'avi', 'mov', 'mp3', 'wav', 'ogg',
            'zip', 'rar', 'tar', 'gz'
        ],
        
        // İzin verilen MIME türleri
        'allowed_mime_types' => [
            // Resimler
            'image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp', 'image/svg+xml', 'image/bmp',
            // Belgeler
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/vnd.ms-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'application/vnd.ms-powerpoint',
            'application/vnd.openxmlformats-officedocument.presentationml.presentation',
            'text/plain', 'text/csv',
            // Medya
            'video/mp4', 'video/avi', 'video/quicktime', 'video/x-msvideo',
            'audio/mpeg', 'audio/wav', 'audio/ogg',
            // Arşivler
            'application/zip', 'application/x-rar-compressed', 'application/x-tar', 'application/gzip'
        ],
        
        // Yasaklı dosya uzantıları
        'blocked_extensions' => [
            'php', 'php3', 'php4', 'php5', 'phtml', 'pht', 'phps',
            'asp', 'aspx', 'jsp', 'jspx',
            'exe', 'bat', 'cmd', 'com', 'scr', 'msi',
            'sh', 'bash', 'zsh', 'fish',
            'js', 'vbs', 'ps1', 'py', 'rb', 'pl',
            'htaccess', 'htpasswd', 'ini', 'conf'
        ],
        
        // Güvenlik logları
        'log_security_events' => true,
        
        // Rate limiting (dakika başına maksimum yükleme)
        'upload_rate_limit' => 20,
    ],

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
        
        // Maksimum resim boyutları (güvenlik için)
        'max_dimensions' => [
            'width' => 4096,
            'height' => 4096
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
        
        // Dosya adı temizleme
        'sanitize_filename' => true,
        
        // Benzersiz dosya adı oluşturma
        'generate_unique_names' => true,
        
        // Dosya yolu güvenliği
        'prevent_path_traversal' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Eski Ayarlar (Geriye Uyumluluk)
    |--------------------------------------------------------------------------
    |
    | Eski sistemle uyumluluk için korunan ayarlar.
    |
    */
    
    // Eski allowed_file_types ayarı (güvenlik ayarlarından alınır)
    'allowed_file_types' => [
        'image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp', 'image/svg+xml', 'image/bmp',
        'application/pdf',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'application/vnd.ms-excel',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'application/vnd.ms-powerpoint',
        'application/vnd.openxmlformats-officedocument.presentationml.presentation',
        'text/plain', 'text/csv',
        'video/mp4', 'video/avi', 'video/quicktime', 'video/x-msvideo',
        'audio/mpeg', 'audio/wav', 'audio/ogg',
        'application/zip', 'application/x-rar-compressed', 'application/x-tar', 'application/gzip'
    ],
    
    // Eski max_file_size ayarı (MB cinsinden)
    'max_file_size' => 50,
]; 