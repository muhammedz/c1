<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class NewsDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'news_id',
        'name',
        'description',
        'file_path',
        'file_name',
        'file_size',
        'mime_type',
        'sort_order',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'file_size' => 'integer',
        'sort_order' => 'integer'
    ];

    /**
     * News ile ilişki
     */
    public function news()
    {
        return $this->belongsTo(News::class);
    }

    /**
     * Dosya boyutunu formatlanmış şekilde döndür
     */
    public function getFormattedSizeAttribute()
    {
        $bytes = $this->file_size;
        
        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        } else {
            return $bytes . ' bytes';
        }
    }

    /**
     * Dosya uzantısına göre ikon sınıfı döndür
     */
    public function getIconClassAttribute()
    {
        $extension = strtolower(pathinfo($this->file_name, PATHINFO_EXTENSION));
        
        switch ($extension) {
            case 'pdf':
                return 'fas fa-file-pdf text-danger';
            case 'doc':
            case 'docx':
                return 'fas fa-file-word text-primary';
            case 'xls':
            case 'xlsx':
                return 'fas fa-file-excel text-success';
            case 'ppt':
            case 'pptx':
                return 'fas fa-file-powerpoint text-warning';
            case 'txt':
                return 'fas fa-file-alt text-secondary';
            case 'zip':
            case 'rar':
                return 'fas fa-file-archive text-info';
            default:
                return 'fas fa-file text-muted';
        }
    }

    /**
     * Dosyanın var olup olmadığını kontrol et
     */
    public function fileExists()
    {
        return file_exists(public_path($this->file_path));
    }

    /**
     * Dosyanın tam URL'ini döndür
     */
    public function getUrlAttribute()
    {
        return asset($this->file_path);
    }

    /**
     * Dosyayı sil
     */
    public function deleteFile()
    {
        if ($this->fileExists()) {
            unlink(public_path($this->file_path));
        }
    }

    /**
     * Model silinirken dosyayı da sil
     */
    protected static function boot()
    {
        parent::boot();
        
        static::deleting(function ($document) {
            $document->deleteFile();
        });
    }
}
