<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MayorContent extends Model
{
    use HasFactory;

    protected $table = 'mayor_content';

    protected $fillable = [
        'mayor_id',
        'type',
        'title',
        'description',
        'image',
        'filemanagersystem_image',
        'filemanagersystem_image_alt',
        'filemanagersystem_image_title',
        'extra_data',
        'sort_order',
        'is_active'
    ];

    protected $casts = [
        'extra_data' => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * İçerik türleri
     */
    const TYPE_STORY = 'story';
    const TYPE_AGENDA = 'agenda';
    const TYPE_GALLERY = 'gallery';

    /**
     * Başkan ilişkisi
     */
    public function mayor()
    {
        return $this->belongsTo(Mayor::class);
    }

    /**
     * Görsel URL'ini getir (FileManagerSystem öncelikli)
     */
    public function getImageUrlAttribute()
    {
        // Önce FileManagerSystem görselini kontrol et
        if ($this->filemanagersystem_image_url) {
            return $this->filemanagersystem_image_url;
        }
        
        // Sonra eski image field'ını kontrol et
        if (!$this->image) {
            return null;
        }

        if (filter_var($this->image, FILTER_VALIDATE_URL)) {
            return $this->image;
        }

        return asset('uploads/' . $this->image);
    }

    /**
     * Türe göre scope
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Aktif içerikler
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Sıralı içerikler
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }

    /**
     * Hikayeler
     */
    public function scopeStories($query)
    {
        return $query->ofType(self::TYPE_STORY);
    }

    /**
     * Gündem
     */
    public function scopeAgenda($query)
    {
        return $query->ofType(self::TYPE_AGENDA);
    }



    /**
     * Galeri
     */
    public function scopeGallery($query)
    {
        return $query->ofType(self::TYPE_GALLERY);
    }

    /**
     * FileManagerSystem medya ilişkileri
     */
    public function media()
    {
        return $this->hasManyThrough(
            \App\Models\FileManagerSystem\Media::class,
            \App\Models\FileManagerSystem\MediaRelation::class,
            'related_id',
            'id',
            'id',
            'media_id'
        )->where('related_type', 'mayor_content');
    }

    /**
     * Ana görsel için medya ilişkisi
     */
    public function featuredImage()
    {
        return $this->media()->where('field_name', 'featured_image')->first();
    }

    /**
     * FileManagerSystem görselinin tam URL'ini döndürür
     */
    public function getFilemanagersystemImageUrlAttribute(): ?string
    {
        if (empty($this->filemanagersystem_image)) {
            return null;
        }
        
        // Eğer URL zaten tam bir URL ise direkt döndür
        if (strpos($this->filemanagersystem_image, 'http://') === 0 || strpos($this->filemanagersystem_image, 'https://') === 0) {
            return $this->filemanagersystem_image;
        }
        
        // Media ID kontrolü - /uploads/media/ID formatı
        if (preg_match('#^/uploads/media/(\d+)$#', $this->filemanagersystem_image, $matches)) {
            $mediaId = $matches[1];
            $media = \App\Models\FileManagerSystem\Media::find($mediaId);
            
            if ($media) {
                return asset($media->url);
            }
            
            // Medya bulunamadı, ilişkili medyaları kontrol et
            $relatedMedia = $this->featuredImage();
            if ($relatedMedia) {
                return asset($relatedMedia->url);
            }
        }
        
        return asset($this->filemanagersystem_image);
    }

    /**
     * Extra data'dan belirli bir değeri getir
     */
    public function getExtraValue($key, $default = null)
    {
        return $this->extra_data[$key] ?? $default;
    }

    /**
     * Extra data'ya değer ekle
     */
    public function setExtraValue($key, $value)
    {
        $extraData = $this->extra_data ?? [];
        $extraData[$key] = $value;
        $this->extra_data = $extraData;
        return $this;
    }
}
