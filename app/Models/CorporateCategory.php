<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Str;
use App\Models\FileManagerSystem\MediaRelation;
use App\Models\FileManagerSystem\Media;

class CorporateCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'image',
        'status',
        'order',
        'filemanagersystem_image',
        'filemanagersystem_image_alt',
        'filemanagersystem_image_title'
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    /**
     * Her kategorinin birden çok üyesi olabilir
     */
    public function members()
    {
        return $this->hasMany(CorporateMember::class);
    }

    /**
     * Kategoriye ait medya ilişkilerini getir - MorphMany ilişkisi ile
     */
    public function mediaRelations(): MorphMany
    {
        return $this->morphMany(MediaRelation::class, 'related', 'related_type', 'related_id');
    }

    /**
     * Kategoriye bağlı medya dosyalarını getir
     */
    public function media()
    {
        return $this->hasManyThrough(
            Media::class,
            MediaRelation::class,
            'related_id',
            'id',
            'id',
            'media_id'
        )->where('related_type', 'corporate_category');
    }

    /**
     * FileManagerSystem görselinin tam URL'ini döndürür
     */
    public function getFilemanagersystemImageUrlAttribute(): ?string
    {
        if (empty($this->filemanagersystem_image)) {
            return null;
        }
        
        // Eğer URL zaten tam bir URL ise (http:// ile başlıyorsa) direkt döndür
        if (strpos($this->filemanagersystem_image, 'http://') === 0 || strpos($this->filemanagersystem_image, 'https://') === 0) {
            return $this->filemanagersystem_image;
        }
        
        // Media ID kontrolü - /uploads/media/ID formatı
        if (preg_match('#^/uploads/media/(\d+)$#', $this->filemanagersystem_image, $matches)) {
            $mediaId = $matches[1];
            $media = Media::find($mediaId);
            
            if ($media) {
                // Medya bulundu, URL'i döndür
                return asset($media->url);
            }
            
            // Medya bulunamadı, ilişkili medyaları kontrol et
            $relatedMedia = $this->media()->first();
            if ($relatedMedia) {
                return asset($relatedMedia->url);
            }
        }
        
        // URL'i olduğu gibi döndür
        return asset($this->filemanagersystem_image);
    }

    /**
     * Sadece aktif kategorileri getiren scope
     */
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    /**
     * Sıralamaya göre kategorileri getiren scope
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order', 'asc');
    }

    /**
     * Otomatik slug oluşturma
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });

        static::updating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });
    }

    /**
     * Resim URL'ini oluşturan metot
     */
    public function getImageUrlAttribute()
    {
        if (!$this->image) {
            return null;
        }

        // Eğer tam URL ise direkt döndür
        if (Str::startsWith($this->image, ['http://', 'https://'])) {
            return $this->image;
        }
        
        // Eğer /storage/ ile başlıyorsa, domain ekle ve döndür
        if (Str::startsWith($this->image, '/storage/')) {
            return url($this->image);
        }
        
        // Normal durumda storage yolunu ekle
        return asset('storage/' . $this->image);
    }
}
