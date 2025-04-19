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
        'is_public'
    ];

    protected $casts = [
        'is_public' => 'boolean',
        'size' => 'integer',
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
     * Dosyanın resim olup olmadığını kontrol eder
     */
    public function isImage()
    {
        return strpos($this->mime_type, 'image/') === 0;
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
} 