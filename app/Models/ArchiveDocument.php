<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArchiveDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'archive_id',
        'category_id',
        'name',
        'description',
        'file_path',
        'file_name',
        'file_size',
        'mime_type',
        'download_count',
        'sort_order',
        'is_active'
    ];

    protected $casts = [
        'file_size' => 'integer',
        'download_count' => 'integer',
        'sort_order' => 'integer',
        'is_active' => 'boolean',
    ];

    /**
     * Arşiv ilişkisi
     */
    public function archive()
    {
        return $this->belongsTo(Archive::class);
    }

    /**
     * Kategori ilişkisi
     */
    public function category()
    {
        return $this->belongsTo(ArchiveDocumentCategory::class, 'category_id');
    }

    /**
     * Aktif belgeler scope
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Sıralı belgeler scope
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }

    /**
     * Dosya boyutunu insan tarafından okunabilir formatta döndürür
     */
    public function getHumanReadableSizeAttribute()
    {
        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $bytes > 1024; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }

    /**
     * Dosya boyutunu formatlı şekilde döndürür (alias)
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
            return 'fa-file-image text-success';
        } elseif (strpos($mime, 'video/') === 0) {
            return 'fa-file-video text-info';
        } elseif (strpos($mime, 'audio/') === 0) {
            return 'fa-file-audio text-warning';
        } elseif (strpos($mime, 'application/pdf') === 0) {
            return 'fa-file-pdf text-danger';
        } elseif (strpos($mime, 'application/msword') === 0 || strpos($mime, 'application/vnd.openxmlformats-officedocument.wordprocessingml') === 0) {
            return 'fa-file-word text-primary';
        } elseif (strpos($mime, 'application/vnd.ms-excel') === 0 || strpos($mime, 'application/vnd.openxmlformats-officedocument.spreadsheetml') === 0) {
            return 'fa-file-excel text-success';
        } elseif (strpos($mime, 'application/vnd.ms-powerpoint') === 0 || strpos($mime, 'application/vnd.openxmlformats-officedocument.presentationml') === 0) {
            return 'fa-file-powerpoint text-warning';
        } elseif (strpos($mime, 'text/') === 0) {
            return 'fa-file-alt text-secondary';
        } elseif (strpos($mime, 'application/zip') === 0 || strpos($mime, 'application/x-rar') === 0 || strpos($mime, 'application/x-7z-compressed') === 0) {
            return 'fa-file-archive text-dark';
        }
        
        return 'fa-file text-muted';
    }

    /**
     * Dosya türü açıklaması
     */
    public function getFileTypeAttribute()
    {
        $mime = $this->mime_type;
        
        if (strpos($mime, 'image/') === 0) {
            return 'Resim';
        } elseif (strpos($mime, 'video/') === 0) {
            return 'Video';
        } elseif (strpos($mime, 'audio/') === 0) {
            return 'Ses';
        } elseif (strpos($mime, 'application/pdf') === 0) {
            return 'PDF';
        } elseif (strpos($mime, 'application/msword') === 0 || strpos($mime, 'application/vnd.openxmlformats-officedocument.wordprocessingml') === 0) {
            return 'Word Belgesi';
        } elseif (strpos($mime, 'application/vnd.ms-excel') === 0 || strpos($mime, 'application/vnd.openxmlformats-officedocument.spreadsheetml') === 0) {
            return 'Excel Belgesi';
        } elseif (strpos($mime, 'application/vnd.ms-powerpoint') === 0 || strpos($mime, 'application/vnd.openxmlformats-officedocument.presentationml') === 0) {
            return 'PowerPoint Belgesi';
        } elseif (strpos($mime, 'text/') === 0) {
            return 'Metin Belgesi';
        } elseif (strpos($mime, 'application/zip') === 0 || strpos($mime, 'application/x-rar') === 0) {
            return 'Arşiv Dosyası';
        }
        
        return 'Dosya';
    }

    /**
     * Dosya URL'si
     */
    public function getUrlAttribute()
    {
        return asset($this->file_path);
    }

    /**
     * İndirme URL'si
     */
    public function getDownloadUrlAttribute()
    {
        // Geçici olarak direkt dosya URL'si döndürüyoruz
        // Daha sonra download route'u eklenebilir
        return asset($this->file_path);
    }

    /**
     * İndirme sayısını artır
     */
    public function incrementDownloadCount()
    {
        $this->increment('download_count');
    }

    /**
     * Dosya var mı kontrolü
     */
    public function fileExists()
    {
        return file_exists(public_path($this->file_path));
    }
}
