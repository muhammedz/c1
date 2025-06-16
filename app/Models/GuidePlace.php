<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class GuidePlace extends Model
{
    use HasFactory;

    protected $fillable = [
        'guide_category_id',
        'title',
        'slug',
        'content',
        'address',
        'phone',
        'email',
        'website',
        'maps_link',
        'latitude',
        'longitude',
        'working_hours',
        'sort_order',
        'is_active',
        'meta_title',
        'meta_description'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8'
    ];

    // Boot method for auto slug generation
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($place) {
            if (empty($place->slug)) {
                $place->slug = Str::slug($place->title);
            }
        });
        
        static::updating(function ($place) {
            if ($place->isDirty('title') && empty($place->slug)) {
                $place->slug = Str::slug($place->title);
            }
        });
    }

    // Relationships
    public function category()
    {
        return $this->belongsTo(GuideCategory::class, 'guide_category_id');
    }

    public function images()
    {
        return $this->hasMany(GuidePlaceImage::class)->orderBy('sort_order');
    }

    public function featuredImage()
    {
        return $this->hasOne(GuidePlaceImage::class)->where('is_featured', true);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('title');
    }

    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('guide_category_id', $categoryId);
    }

    // Accessors
    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function getMetaTitleAttribute($value)
    {
        return $value ?: $this->title;
    }

    public function getMetaDescriptionAttribute($value)
    {
        return $value ?: Str::limit(strip_tags($this->content), 160);
    }

    public function getExcerptAttribute()
    {
        return Str::limit(strip_tags($this->content), 200);
    }

    public function hasCoordinates()
    {
        return !is_null($this->latitude) && !is_null($this->longitude);
    }

    public function getFirstImageAttribute()
    {
        return $this->images()->first();
    }

    public function getFeaturedImageUrlAttribute()
    {
        $featuredImage = $this->featuredImage;
        if ($featuredImage) {
            return asset('storage/' . $featuredImage->image_path);
        }
        
        $firstImage = $this->images()->first();
        if ($firstImage) {
            return asset('storage/' . $firstImage->image_path);
        }
        
        return asset('images/no-image.jpg'); // Default image
    }
}
