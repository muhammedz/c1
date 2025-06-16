<?php

namespace App\Models\FileManagerSystem;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Media extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'filemanagersystem_medias';

    protected $fillable = [
        'name', 
        'original_name',
        'mime_type',
        'extension',
        'size',
        'path',
        'url',
        'user_id',
        'folder_id',
        'category_id',
        'is_public',
        'original_size',
        'compressed_size',
        'compression_rate',
        'webp_url',
        'webp_path',
        'has_webp',
        'width',
        'height',
        'compression_quality'
    ];

    protected $casts = [
        'is_public' => 'boolean',
        'size' => 'integer',
        'original_size' => 'integer',
        'compressed_size' => 'integer',
        'compression_rate' => 'float',
        'has_webp' => 'boolean',
        'width' => 'integer',
        'height' => 'integer',
        'compression_quality' => 'integer',
    ];

    /**
     * Dosya sahibi (kullanıcı) ilişkisi
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Dosyanın bulunduğu klasör ilişkisi
     */
    public function folder()
    {
        return $this->belongsTo(Folder::class, 'folder_id');
    }

    /**
     * Dosyanın kategorisi ilişkisi
     */
    public function category()
    {
        // Eğer category_id sütunu yoksa, bu ilişkiyi kapat
        // return $this->belongsTo(Category::class, 'category_id');
        
        // Bunun yerine son kategori ilişkisini döndür
        return $this->belongsToMany(Category::class, 'filemanagersystem_media_categories', 'media_id', 'category_id')
                    ->latest();
    }

    /**
     * Dosyanın birden fazla kategorisi olabilir
     */
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'filemanagersystem_media_categories', 'media_id', 'category_id');
    }

    /**
     * Dosyanın polymorphic ilişkilerini döndürür
     */
    public function relations()
    {
        \Log::debug('Media Model Relations metodu çağrıldı', ['media_id' => $this->id]);
        return $this->hasMany(MediaRelation::class, 'media_id');
    }

    /**
     * Dosyanın boyutunu insan tarafından okunabilir formatta döndürür
     */
    public function getHumanReadableSizeAttribute()
    {
        $bytes = $this->size;
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $bytes > 1024; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }

    /**
     * Dosyanın boyutunu insan tarafından okunabilir formatta döndürür (formattedSize accessor)
     */
    public function getFormattedSizeAttribute()
    {
        return $this->human_readable_size;
    }

    /**
     * Orijinal dosya boyutunu insan tarafından okunabilir formatta döndürür
     */
    public function getFormattedOriginalSizeAttribute()
    {
        if (!$this->original_size) {
            return $this->human_readable_size;
        }
        
        $bytes = $this->original_size;
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $bytes > 1024; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }

    /**
     * Sıkıştırılmış dosya boyutunu insan tarafından okunabilir formatta döndürür
     */
    public function getFormattedCompressedSizeAttribute()
    {
        if (!$this->compressed_size) {
            return null;
        }
        
        $bytes = $this->compressed_size;
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $bytes > 1024; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }

    /**
     * Dosyanın türüne göre ikon sınıfını döndürür
     */
    public function getIconClassAttribute()
    {
        $mime = $this->mime_type;
        
        if (strpos($mime, 'image/') === 0) {
            return 'fa-file-image';
        } elseif (strpos($mime, 'video/') === 0) {
            return 'fa-file-video';
        } elseif (strpos($mime, 'audio/') === 0) {
            return 'fa-file-audio';
        } elseif (strpos($mime, 'application/pdf') === 0) {
            return 'fa-file-pdf';
        } elseif (strpos($mime, 'application/msword') === 0 || strpos($mime, 'application/vnd.openxmlformats-officedocument.wordprocessingml') === 0) {
            return 'fa-file-word';
        } elseif (strpos($mime, 'application/vnd.ms-excel') === 0 || strpos($mime, 'application/vnd.openxmlformats-officedocument.spreadsheetml') === 0) {
            return 'fa-file-excel';
        } elseif (strpos($mime, 'application/vnd.ms-powerpoint') === 0 || strpos($mime, 'application/vnd.openxmlformats-officedocument.presentationml') === 0) {
            return 'fa-file-powerpoint';
        } elseif (strpos($mime, 'text/') === 0) {
            return 'fa-file-alt';
        } elseif (strpos($mime, 'application/zip') === 0 || strpos($mime, 'application/x-rar') === 0) {
            return 'fa-file-archive';
        }
        
        return 'fa-file';
    }
    
    /**
     * Dosyanın türüne göre ikon sınıfını döndürür (metod olarak)
     */
    public function getIconClass()
    {
        return $this->getIconClassAttribute();
    }
    
    /**
     * Dosyanın resim olup olmadığını kontrol eder
     */
    public function isImage()
    {
        return strpos($this->mime_type, 'image/') === 0;
    }
    
    /**
     * Dosyanın sıkıştırılabilir bir resim formatı olup olmadığını kontrol eder
     */
    public function isCompressible()
    {
        if (!$this->isImage()) {
            return false;
        }
        
        $supportedFormats = config('filemanagersystem.image_compression.supported_formats', [
            'image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'
        ]);
        
        return in_array($this->mime_type, $supportedFormats);
    }
    
    /**
     * Dosyanın WebP'ye dönüştürülebilir bir resim formatı olup olmadığını kontrol eder
     */
    public function isConvertibleToWebP()
    {
        if (!$this->isImage()) {
            return false;
        }
        
        $formatsToConvert = config('filemanagersystem.webp_conversion.formats_to_convert', [
            'image/jpeg', 'image/jpg', 'image/png'
        ]);
        
        return in_array($this->mime_type, $formatsToConvert);
    }
    
    /**
     * Dosyanın sıkıştırıldıktan sonraki tasarruf yüzdesini döndürür
     */
    public function getSavingsPercentageAttribute()
    {
        if (!$this->original_size || !$this->compressed_size || $this->original_size <= 0) {
            return 0;
        }
        
        return round(100 - (($this->compressed_size / $this->original_size) * 100), 2);
    }
    
    /**
     * Dosyanın sıkıştırılma tarihini döndürür
     * compression_quality değerinin varlığı, sıkıştırma işleminin yapıldığını gösterir
     */
    public function isCompressed()
    {
        return $this->compression_quality !== null;
    }
    
    /**
     * En uygun dosya URL'ini döndürür
     * WebP destek kontrolü client tarafında yapılmalıdır
     */
    public function getBestFitUrlAttribute()
    {
        if ($this->has_webp && $this->webp_url) {
            return $this->webp_url;
        }
        
        return $this->url;
    }
    
    /**
     * Picture etiketi için HTML kodu döndürür
     * WebP ve orijinal format için fallback içerir
     */
    public function getPictureHtmlAttribute()
    {
        if (!$this->isImage()) {
            return '<img src="' . $this->url . '" alt="' . $this->original_name . '">';
        }
        
        if ($this->has_webp && $this->webp_url) {
            return '<picture>
                <source srcset="' . $this->webp_url . '" type="image/webp">
                <source srcset="' . $this->url . '" type="' . $this->mime_type . '">
                <img src="' . $this->url . '" alt="' . $this->original_name . '" width="' . $this->width . '" height="' . $this->height . '">
            </picture>';
        }
        
        return '<img src="' . $this->url . '" alt="' . $this->original_name . '"' . 
               ($this->width ? ' width="' . $this->width . '"' : '') . 
               ($this->height ? ' height="' . $this->height . '"' : '') . '>';
    }
    
    /**
     * Dosyanın boyutunu insan tarafından okunabilir formatta döndürür (doğrudan metod olarak)
     */
    public function getFormattedSize()
    {
        $bytes = $this->size;
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $bytes > 1024; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }

    /**
     * WebP dosyanın boyutunu insan tarafından okunabilir formatta döndürür
     */
    public function getFormattedWebpSizeAttribute()
    {
        if (!$this->has_webp || empty($this->webp_path) || !file_exists(public_path($this->webp_path))) {
            return null;
        }
        
        // Eğer webp_path tam yol içeriyorsa, public_path ile birleştirmeyin
        $fullPath = strpos($this->webp_path, '/') === 0 
            ? public_path(ltrim($this->webp_path, '/')) 
            : public_path($this->webp_path);
            
        if (!file_exists($fullPath)) {
            return null;
        }
        
        $bytes = filesize($fullPath);
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $bytes > 1024; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }

    /**
     * WebP versiyonu var mı?
     */
    public function getHasWebpVersionAttribute()
    {
        return $this->has_webp == true;
    }
} 