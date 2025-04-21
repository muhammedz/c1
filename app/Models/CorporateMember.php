<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Str;
use App\Models\FileManagerSystem\MediaRelation;
use App\Models\FileManagerSystem\Media;

class CorporateMember extends Model
{
    use HasFactory;

    protected $fillable = [
        'corporate_category_id',
        'name',
        'slug',
        'title',
        'image',
        'short_description',
        'description',
        'facebook',
        'twitter',
        'instagram',
        'linkedin',
        'status',
        'order',
        'show_detail',
        'filemanagersystem_image',
        'filemanagersystem_image_alt',
        'filemanagersystem_image_title'
    ];

    protected $casts = [
        'status' => 'boolean',
        'show_detail' => 'boolean',
    ];

    /**
     * Üye bir kategoriye aittir
     */
    public function category()
    {
        return $this->belongsTo(CorporateCategory::class, 'corporate_category_id');
    }

    /**
     * Üyeye ait medya ilişkilerini getir - MorphMany ilişkisi ile
     */
    public function mediaRelations(): MorphMany
    {
        return $this->morphMany(MediaRelation::class, 'related', 'related_type', 'related_id');
    }

    /**
     * Üyeye bağlı medya dosyalarını getir
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
        )->where('related_type', 'corporate_member');
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
        
        // Doğrudan dosya yolu ise, tam URL oluştur
        if (Str::startsWith($this->filemanagersystem_image, '/uploads/')) {
            return url($this->filemanagersystem_image);
        }
        
        // Media ID kontrolü - /uploads/media/ID formatı
        if (preg_match('#^/uploads/media/(\d+)$#', $this->filemanagersystem_image, $matches)) {
            // ID yerine direkt uploads/media/ şeklinde dosya yolu döndür
            return url($this->filemanagersystem_image);
        }
        
        // /admin/filemanagersystem/media/preview/ID formatı kontrolü
        if (preg_match('#/media/preview/(\d+)#', $this->filemanagersystem_image, $matches)) {
            $mediaId = $matches[1];
            // Yaşayan kodu korumak için yine benzer bir URL döndürüyoruz
            // ama ideal olarak bunu da uploads/media/ yoluna çevirmeliyiz
            return url('/uploads/media/' . $mediaId);
        }
        
        // İlişkili medya kontrolü - doğrudan ID verilmişse
        if (is_numeric($this->filemanagersystem_image)) {
            $mediaId = $this->filemanagersystem_image;
            
            // Media tablosundan gerçek dosya yolunu alabiliriz, fakat
            // bunun yerine basit olarak '/uploads/media/ID' formatını kullanıyoruz
            return url('/uploads/media/' . $mediaId);
        }
        
        // URL'i olduğu gibi döndür - başına / ekleyerek
        if (!Str::startsWith($this->filemanagersystem_image, '/')) {
            return url('/' . $this->filemanagersystem_image);
        }
        
        return url($this->filemanagersystem_image);
    }

    /**
     * Sadece aktif üyeleri getiren scope
     */
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    /**
     * Sıralamaya göre üyeleri getiren scope
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

        static::creating(function ($member) {
            if (empty($member->slug)) {
                $member->slug = Str::slug($member->name);
            }
        });

        static::updating(function ($member) {
            if (empty($member->slug)) {
                $member->slug = Str::slug($member->name);
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
