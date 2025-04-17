<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class CorporateCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'image',
        'status',
        'order'
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
