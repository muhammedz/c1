<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class GuidePlaceImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'guide_place_id',
        'image_path',
        'alt_text',
        'sort_order',
        'is_featured'
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'sort_order' => 'integer'
    ];

    // Relationships
    public function place()
    {
        return $this->belongsTo(GuidePlace::class, 'guide_place_id');
    }

    // Scopes
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }

    // Accessors
    public function getImageUrlAttribute()
    {
        return asset('storage/' . $this->image_path);
    }

    public function getThumbnailUrlAttribute()
    {
        $pathInfo = pathinfo($this->image_path);
        $thumbnailPath = $pathInfo['dirname'] . '/thumbnails/' . $pathInfo['filename'] . '_thumb.' . $pathInfo['extension'];
        
        if (Storage::disk('public')->exists($thumbnailPath)) {
            return asset('storage/' . $thumbnailPath);
        }
        
        return $this->image_url;
    }

    public function getMediumUrlAttribute()
    {
        $pathInfo = pathinfo($this->image_path);
        $mediumPath = $pathInfo['dirname'] . '/medium/' . $pathInfo['filename'] . '_medium.' . $pathInfo['extension'];
        
        if (Storage::disk('public')->exists($mediumPath)) {
            return asset('storage/' . $mediumPath);
        }
        
        return $this->image_url;
    }

    // Boot method
    protected static function boot()
    {
        parent::boot();
        
        static::deleting(function ($image) {
            // Delete physical files when model is deleted
            if (Storage::disk('public')->exists($image->image_path)) {
                Storage::disk('public')->delete($image->image_path);
            }
            
            // Delete thumbnails and medium sizes
            $pathInfo = pathinfo($image->image_path);
            $thumbnailPath = $pathInfo['dirname'] . '/thumbnails/' . $pathInfo['filename'] . '_thumb.' . $pathInfo['extension'];
            $mediumPath = $pathInfo['dirname'] . '/medium/' . $pathInfo['filename'] . '_medium.' . $pathInfo['extension'];
            
            if (Storage::disk('public')->exists($thumbnailPath)) {
                Storage::disk('public')->delete($thumbnailPath);
            }
            
            if (Storage::disk('public')->exists($mediumPath)) {
                Storage::disk('public')->delete($mediumPath);
            }
        });
    }
}
