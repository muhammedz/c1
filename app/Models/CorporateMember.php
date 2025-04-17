<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

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
        'show_detail'
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
