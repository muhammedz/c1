<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Carbon\Carbon;

class Service extends Model
{
    use HasFactory;

    protected $table = 'services';
    
    protected $fillable = [
        'title', 
        'slug', 
        'summary',
        'content', 
        'features',
        'image', 
        'gallery',
        'is_headline', 
        'headline_order', 
        'is_featured',
        'view_count',
        'status',
        'cta_text',
        'cta_url',
        'is_scheduled',
        'meta_title',
        'meta_description',
        'published_at',
        'end_date'
    ];
    
    protected $casts = [
        'is_headline' => 'boolean',
        'is_featured' => 'boolean',
        'is_scheduled' => 'boolean',
        'published_at' => 'datetime',
        'end_date' => 'datetime',
        'gallery' => 'array',
        'features' => 'array'
    ];
    
    // İlişkiler
    
    // Kategoriler
    public function categories()
    {
        return $this->belongsToMany(ServiceCategory::class, 'service_category', 'service_id', 'category_id')
                   ->withTimestamps();
    }
    
    // Etiketler
    public function tags()
    {
        return $this->belongsToMany(ServiceTag::class, 'service_tag', 'service_id', 'tag_id')
                   ->withTimestamps();
    }
    
    // Kapsamlar (Scopes)
    
    // Yayında olan hizmetler
    public function scopePublished($query)
    {
        return $query->where('services.status', 'published')
                    ->where(function($q) {
                        $q->where('services.is_scheduled', false)
                          ->orWhere(function($q2) {
                              $q2->where('services.is_scheduled', true)
                                 ->where('services.published_at', '<=', Carbon::now())
                                 ->where(function($q3) {
                                     $q3->whereNull('services.end_date')
                                        ->orWhere('services.end_date', '>=', Carbon::now());
                                 });
                          });
                    });
    }
    
    // Öne çıkarılan hizmetler
    public function scopeFeatured($query)
    {
        return $query->where('services.is_featured', true);
    }
    
    // Popüler hizmetler
    public function scopePopular($query, $limit = 5)
    {
        return $query->published()->orderBy('services.view_count', 'desc')->limit($limit);
    }
    
    // Yardımcı metodlar
    
    // Manşet hizmetlerini getir
    public static function getHeadlines()
    {
        return self::published()
            ->where('services.is_headline', true)
            ->orderBy('services.headline_order', 'asc')
            ->take(4)
            ->get();
    }
    
    // Normal hizmetleri getir
    public static function getNormalServices($limit = 6)
    {
        return self::published()
            ->where('services.is_headline', false)
            ->orderBy('services.created_at', 'desc')
            ->take($limit)
            ->get();
    }
    
    // Öne çıkan hizmetleri getir
    public static function getFeatured($limit = 6)
    {
        return self::featured()->published()->orderBy('services.published_at', 'desc')->take($limit)->get();
    }
    
    // Maksimum manşet sayısını kontrol et
    public static function maxHeadlinesReached()
    {
        return self::where('services.is_headline', true)->count() >= 4;
    }
    
    // Slug'a göre hizmet getir
    public static function getBySlug($slug)
    {
        return self::published()
            ->where('services.slug', $slug)
            ->first();
    }
    
    // İlgili hizmetleri getir
    public function getRelatedServices($limit = 4)
    {
        $categories = $this->categories->pluck('id')->toArray();
        $tags = $this->tags->pluck('id')->toArray();
        
        if (empty($categories) && empty($tags)) {
            return self::published()
                      ->where('services.id', '!=', $this->id)
                      ->orderBy('services.published_at', 'desc')
                      ->limit($limit)
                      ->get();
        }
        
        return self::published()
                  ->where('services.id', '!=', $this->id)
                  ->where(function($query) use ($categories, $tags) {
                      if (!empty($categories)) {
                          $query->whereHas('categories', function($q) use ($categories) {
                              $q->whereIn('service_categories.id', $categories);
                          });
                      }
                      
                      if (!empty($tags)) {
                          $query->orWhereHas('tags', function($q) use ($tags) {
                              $q->whereIn('service_tags.id', $tags);
                          });
                      }
                  })
                  ->orderBy('services.published_at', 'desc')
                  ->limit($limit)
                  ->get();
    }
    
    // Görüntüleme sayısını artır
    public function incrementViews()
    {
        $this->increment('view_count');
        return $this;
    }
    
    /**
     * Galeri özelliğini her zaman dizi olarak dön
     */
    public function getGalleryAttribute($value)
    {
        if (is_null($value)) return [];
        
        if (is_string($value)) {
            try {
                $decoded = json_decode($value, true);
                return is_array($decoded) ? $decoded : [];
            } catch (\Exception $e) {
                return [];
            }
        }
        
        return $value;
    }
    
    /**
     * Galeri özelliğini ayarlarken her zaman JSON olarak kaydet
     */
    public function setGalleryAttribute($value)
    {
        if (is_null($value)) {
            $this->attributes['gallery'] = json_encode([]);
        } elseif (is_array($value)) {
            $this->attributes['gallery'] = json_encode($value);
        } else {
            $this->attributes['gallery'] = json_encode([]);
        }
    }

    /**
     * Features özelliğini her zaman dizi olarak dön
     */
    public function getFeaturesAttribute($value)
    {
        if (is_null($value)) return [];
        
        if (is_string($value)) {
            try {
                $decoded = json_decode($value, true);
                return is_array($decoded) ? $decoded : [];
            } catch (\Exception $e) {
                return [];
            }
        }
        
        return $value;
    }
    
    /**
     * Features özelliğini ayarlarken her zaman JSON olarak kaydet
     */
    public function setFeaturesAttribute($value)
    {
        if (is_null($value)) {
            $this->attributes['features'] = json_encode([]);
        } elseif (is_array($value)) {
            $this->attributes['features'] = json_encode($value);
        } else {
            $this->attributes['features'] = json_encode([]);
        }
    }
}
