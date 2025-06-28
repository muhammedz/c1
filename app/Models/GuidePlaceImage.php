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
        // FileManagerSystem resmi kontrolü
        if (str_starts_with($this->image_path, 'filemanagersystem/')) {
            $mediaId = str_replace('filemanagersystem/', '', $this->image_path);
            
            // Media modelinden gerçek URL'i al
            $media = \App\Models\FileManagerSystem\Media::find($mediaId);
            if ($media) {
                return asset($media->url);
            }
            
            // Fallback: preview URL'i
            return url('/admin/filemanagersystem/media/preview/' . $mediaId);
        }
        
        return asset('storage/' . $this->image_path);
    }

    public function getThumbnailUrlAttribute()
    {
        // FileManagerSystem resmi kontrolü
        if (str_starts_with($this->image_path, 'filemanagersystem/')) {
            $mediaId = str_replace('filemanagersystem/', '', $this->image_path);
            
            // Media modelinden gerçek URL'i al (thumbnail varsa onu kullan)
            $media = \App\Models\FileManagerSystem\Media::find($mediaId);
            if ($media) {
                // Eğer thumbnail varsa onu kullan, yoksa ana resmi
                return asset($media->url);
            }
            
            // Fallback: preview URL'i
            return url('/admin/filemanagersystem/media/preview/' . $mediaId . '?size=thumbnail');
        }
        
        $pathInfo = pathinfo($this->image_path);
        $thumbnailPath = $pathInfo['dirname'] . '/thumbnails/' . $pathInfo['filename'] . '_thumb.' . $pathInfo['extension'];
        
        if (Storage::disk('public')->exists($thumbnailPath)) {
            return asset('storage/' . $thumbnailPath);
        }
        
        return $this->image_url;
    }

    public function getMediumUrlAttribute()
    {
        // FileManagerSystem resmi kontrolü
        if (str_starts_with($this->image_path, 'filemanagersystem/')) {
            $mediaId = str_replace('filemanagersystem/', '', $this->image_path);
            
            // Media modelinden gerçek URL'i al
            $media = \App\Models\FileManagerSystem\Media::find($mediaId);
            if ($media) {
                return asset($media->url);
            }
            
            // Fallback: preview URL'i
            return url('/admin/filemanagersystem/media/preview/' . $mediaId . '?size=medium');
        }
        
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
            // FileManagerSystem resmi kontrolü
            if (str_starts_with($image->image_path, 'filemanagersystem/')) {
                $mediaId = str_replace('filemanagersystem/', '', $image->image_path);
                
                // MediaRelation'ı sil
                \App\Models\FileManagerSystem\MediaRelation::where('media_id', $mediaId)
                    ->where('related_type', 'guide_place')
                    ->where('related_id', $image->guide_place_id)
                    ->where('field_name', 'images')
                    ->delete();
                
                return; // FileManagerSystem dosyalarını silme, sadece ilişkiyi kaldır
            }
            
            // Geleneksel dosya silme
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
