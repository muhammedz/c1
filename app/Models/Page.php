<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Carbon\Carbon;

class Page extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'slug',
        'content',
        'summary',
        'image',
        'gallery',
        'is_featured',
        'view_count',
        'status',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'published_at',
        'end_date',
        'template',
        'order',
        'created_by',
        'updated_by',
    ];
    
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_featured' => 'boolean',
        'is_scheduled' => 'boolean',
        'published_at' => 'datetime',
        'end_date' => 'datetime',
        'gallery' => 'array'
    ];
    
    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();
        
        // Otomatik slug oluşturma (yeni kayıt için)
        static::creating(function ($page) {
            if (empty($page->slug)) {
                $page->slug = Str::slug($page->title);
            }
        });
        
        // Slug güncelleme (mevcut kayıt için)
        static::updating(function ($page) {
            // Eğer slug boşsa title'dan oluştur
            if (empty($page->slug) && !empty($page->title)) {
                $page->slug = Str::slug($page->title);
            }
        });
    }
    
    /**
     * Get the user that created the page.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    
    /**
     * Get the user that last updated the page.
     */
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
    
    /**
     * Get published pages.
     */
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
    
    /**
     * Get featured pages.
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }
    
    /**
     * Get popular pages.
     */
    public function scopePopular($query, $limit = 5)
    {
        return $query->published()->orderBy('view_count', 'desc')->limit($limit);
    }
    
    /**
     * Get related pages.
     */
    public function getRelatedPages($limit = 4)
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
    
    /**
     * Increment views.
     */
    public function incrementViews()
    {
        $this->increment('view_count');
    }
    
    /**
     * Get featured pages.
     */
    public static function getFeaturedPages()
    {
        return self::published()
            ->where('is_featured', true)
            ->orderBy('featured_order', 'asc')
            ->take(4)
            ->get();
    }
    
    /**
     * Get normal pages.
     */
    public static function getNormalPages($limit = 6)
    {
        return self::published()
            ->where('is_featured', false)
            ->orderBy('created_at', 'desc')
            ->take($limit)
            ->get();
    }
    
    /**
     * Check if maximum featured pages are reached.
     */
    public static function maxFeaturedPagesReached()
    {
        return self::where('is_featured', true)->count() >= 4;
    }
    
    /**
     * Get page by slug.
     */
    public static function getBySlug($slug)
    {
        return self::published()
            ->where('slug', $slug)
            ->first();
    }
    
    /**
     * Get the categories for the page.
     */
    public function categories()
    {
        return $this->belongsToMany(PageCategory::class, 'page_category', 'page_id', 'category_id');
    }
    
    /**
     * Get the tags for the page.
     */
    public function tags()
    {
        return $this->belongsToMany(PageTag::class, 'page_tag', 'page_id', 'tag_id');
    }
    
    /**
     * Get tags as string.
     */
    public function getTagsAsString()
    {
        // tags collection'ını array'e çevirip implode
        if ($this->tags && $this->tags->count() > 0) {
            $tagNames = $this->tags->pluck('name')->toArray();
            return implode(',', $tagNames);
        }
        return '';
    }
    
    /**
     * Get the image attribute with correct URL path.
     *
     * @param  string|null  $value
     * @return string|null
     */
    public function getImageAttribute($value)
    {
        if (empty($value)) {
            return null;
        }
        
        // Windows'taki yol ayırıcıları düzelt
        $value = str_replace('\\', '/', $value);
        
        // Eğer yol 'storage/' ile başlıyorsa
        if (Str::startsWith($value, 'storage/')) {
            return url($value);
        }
        
        // Eğer public/storage/ ile başlıyorsa
        if (Str::startsWith($value, 'public/storage/')) {
            return url(Str::replaceFirst('public/', '', $value));
        }
        
        // Eğer tam URL ise
        if (Str::startsWith($value, ['http://', 'https://'])) {
            return $value;
        }
        
        // Diğer tüm durumlar için
        return asset($value);
    }
    
    /**
     * Get the featured_image attribute as an alias to image.
     *
     * @return string|null
     */
    public function getFeaturedImageAttribute()
    {
        return $this->image;
    }
}
