<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Carbon\Carbon;

class News extends Model
{
    use HasFactory;

    protected $table = 'news';
    
    protected $fillable = [
        'title', 
        'slug', 
        'summary',
        'content', 
        'image', 
        'gallery',
        'is_headline', 
        'headline_order', 
        'is_featured',
        'view_count',
        'status',
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
        'gallery' => 'array'
    ];
    
    // İlişkiler
    
    // Kategoriler
    public function categories()
    {
        return $this->belongsToMany(NewsCategory::class, 'news_category', 'news_id', 'category_id')
                   ->withTimestamps();
    }
    
    // Etiketler
    public function tags()
    {
        return $this->belongsToMany(NewsTag::class, 'news_tag', 'news_id', 'tag_id')
                   ->withTimestamps();
    }
    
    // Kapsamlar (Scopes)
    
    // Yayında olan haberler
    public function scopePublished($query)
    {
        return $query->where('status', 'published')
                    ->where(function($q) {
                        $q->where('is_scheduled', false)
                          ->orWhere(function($q2) {
                              $q2->where('is_scheduled', true)
                                 ->where('published_at', '<=', Carbon::now())
                                 ->where(function($q3) {
                                     $q3->whereNull('end_date')
                                        ->orWhere('end_date', '>=', Carbon::now());
                                 });
                          });
                    });
    }
    
    // Öne çıkarılan haberler
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }
    
    // Popüler haberler
    public function scopePopular($query, $limit = 5)
    {
        return $query->published()->orderBy('view_count', 'desc')->limit($limit);
    }
    
    // Yardımcı metodlar
    
    // Manşet haberlerini getir
    public static function getHeadlines()
    {
        return self::published()
            ->where('is_headline', true)
            ->orderBy('headline_order', 'asc')
            ->take(4)
            ->get();
    }
    
    // Normal haberleri getir
    public static function getNormalNews($limit = 6)
    {
        return self::published()
            ->where('is_headline', false)
            ->orderBy('created_at', 'desc')
            ->take($limit)
            ->get();
    }
    
    // Öne çıkan haberleri getir
    public static function getFeatured($limit = 6)
    {
        return self::featured()->published()->orderBy('published_at', 'desc')->take($limit)->get();
    }
    
    // Maksimum manşet sayısını kontrol et
    public static function maxHeadlinesReached()
    {
        return self::where('is_headline', true)->count() >= 4;
    }
    
    // Slug'a göre haber getir
    public static function getBySlug($slug)
    {
        return self::published()
            ->where('slug', $slug)
            ->first();
    }
    
    // İlgili haberleri getir
    public function getRelatedNews($limit = 4)
    {
        $categories = $this->categories->pluck('id')->toArray();
        $tags = $this->tags->pluck('id')->toArray();
        
        if (empty($categories) && empty($tags)) {
            return self::published()
                      ->where('id', '!=', $this->id)
                      ->orderBy('published_at', 'desc')
                      ->limit($limit)
                      ->get();
        }
        
        return self::published()
                  ->where('id', '!=', $this->id)
                  ->where(function($query) use ($categories, $tags) {
                      if (!empty($categories)) {
                          $query->whereHas('categories', function($q) use ($categories) {
                              $q->whereIn('id', $categories);
                          });
                      }
                      
                      if (!empty($tags)) {
                          $query->orWhereHas('tags', function($q) use ($tags) {
                              $q->whereIn('id', $tags);
                          });
                      }
                  })
                  ->orderBy('published_at', 'desc')
                  ->limit($limit)
                  ->get();
    }
    
    // Görüntüleme sayısını artır
    public function incrementViews()
    {
        $this->increment('view_count');
    }
}
