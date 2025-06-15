<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Laravel\Scout\Searchable;

class News extends Model
{
    use HasFactory, Searchable;

    protected $table = 'news';
    
    protected $fillable = [
        'title', 
        'slug', 
        'summary',
        'content', 
        'image', 
        'gallery',
        'filemanagersystem_image',
        'filemanagersystem_image_alt',
        'filemanagersystem_image_title',
        'filemanagersystem_gallery',
        'is_headline', 
        'headline_order', 
        'is_featured',
        'view_count',
        'status',
        'is_scheduled',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'category_id',
        'views',
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
        'filemanagersystem_gallery' => 'array',
        'status' => 'string'
    ];
    
    // İlişkiler
    
    // Kategoriler
    public function category()
    {
        return $this->belongsTo(NewsCategory::class, 'category_id');
    }
    
    public function categories()
    {
        return $this->belongsToMany(NewsCategory::class, 'news_category', 'news_id', 'category_id');
    }
    
    // Etiketler
    public function tags()
    {
        return $this->belongsToMany(NewsTag::class, 'news_tag', 'news_id', 'tag_id')
                   ->withTimestamps();
    }
    
    // Hedef Kitleler
    public function hedefKitleler()
    {
        return $this->belongsToMany(HedefKitle::class, 'hedef_kitle_news', 'news_id', 'hedef_kitle_id')
                   ->withTimestamps();
    }
    
    // Belgeler
    public function documents()
    {
        return $this->hasMany(NewsDocument::class)->where('is_active', true)->orderBy('sort_order');
    }
    
    public function allDocuments()
    {
        return $this->hasMany(NewsDocument::class)->orderBy('sort_order');
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
    
    // Admin için tüm manşet haberlerini getir (published olmayanlar dahil)
    public static function getAllHeadlines()
    {
        return self::where('is_headline', true)
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
    public function getRelatedNews($limit = 5)
    {
        return self::active()
            ->published()
            ->where('id', '!=', $this->id)
            ->where('category_id', $this->category_id)
            ->latest('published_at')
            ->limit($limit)
            ->get();
    }
    
    // Görüntüleme sayısını artır
    public function incrementViews()
    {
        $this->increment('views');
    }

    /**
     * Haber'e ait medya ilişkilerini getir - MorphMany ilişkisi ile
     */
    public function mediaRelations(): MorphMany
    {
        return $this->morphMany(\App\Models\FileManagerSystem\MediaRelation::class, 'related', 'related_type', 'related_id');
    }

    /**
     * Haber'e bağlı medya dosyalarını getir
     */
    public function media()
    {
        return $this->hasManyThrough(
            \App\Models\FileManagerSystem\Media::class,
            \App\Models\FileManagerSystem\MediaRelation::class,
            'related_id',
            'id',
            'id',
            'media_id'
        )->where('related_type', 'news');
    }

    /**
     * Ana görsel için medya ilişkisi
     */
    public function featuredImage()
    {
        return $this->media()->where('field_name', 'featured_image')->first();
    }

    /**
     * Galeri görselleri için medya ilişkileri
     */
    public function galleryImages()
    {
        return $this->media()->where('field_name', 'gallery')->orderBy('order')->get();
    }

    /**
     * Haber ana görselinin tam URL'ini döndürür
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
            $media = \App\Models\FileManagerSystem\Media::find($mediaId);
            
            if ($media) {
                // Medya bulundu, URL'i döndür
                return asset($media->url);
            }
            
            // Medya bulunamadı, ilişkili medyaları kontrol et
            $relatedMedia = $this->featuredImage();
            if ($relatedMedia) {
                return asset($relatedMedia->url);
            }
        }
        
        return asset($this->filemanagersystem_image);
    }

    // Otomatik slug oluşturma
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($news) {
            if (!$news->slug) {
                $news->slug = Str::slug($news->title);
            }
        });
    }

    // Scopelar
    public function scopeActive($query)
    {
        return $query->where('status', 'published');
    }

    /**
     * Scout için aranabilir alanların listesi - Sadece başlık
     */
    public function toSearchableArray()
    {
        // Türkçe karakterlerin normalize edilmesi için
        $title = $this->title ? mb_strtolower($this->title, 'UTF-8') : '';
        $title = str_replace(['ı', 'ğ', 'ü', 'ş', 'ö', 'ç'], ['i', 'g', 'u', 's', 'o', 'c'], $title);
        
        return [
            'id' => $this->id,
            'title' => $title,
            'original_title' => $this->title, // Orjinal başlık
            'slug' => $this->slug,
            'type' => 'news', // Tür bilgisi ekledik
            'status' => $this->status,
        ];
    }

    /**
     * Scout için kullanılacak model adı
     */
    public function searchableAs()
    {
        return 'news_index';
    }
}
