<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Carbon\Carbon;

class Archive extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'content',
        'excerpt',
        'status',
        'is_featured',
        'view_count',
        'published_at',
        'user_id'
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'view_count' => 'integer',
        'published_at' => 'datetime',
    ];

    protected $dates = [
        'published_at',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    /**
     * Boot method - slug otomatik oluşturma
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($archive) {
            if (empty($archive->slug)) {
                $archive->slug = Str::slug($archive->title);
                
                // Benzersiz slug kontrolü
                $originalSlug = $archive->slug;
                $counter = 1;
                while (static::where('slug', $archive->slug)->exists()) {
                    $archive->slug = $originalSlug . '-' . $counter;
                    $counter++;
                }
            }
        });

        static::updating(function ($archive) {
            if ($archive->isDirty('title') && empty($archive->slug)) {
                $archive->slug = Str::slug($archive->title);
                
                // Benzersiz slug kontrolü
                $originalSlug = $archive->slug;
                $counter = 1;
                while (static::where('slug', $archive->slug)->where('id', '!=', $archive->id)->exists()) {
                    $archive->slug = $originalSlug . '-' . $counter;
                    $counter++;
                }
            }
        });
    }

    /**
     * Kullanıcı ilişkisi
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Belgeler ilişkisi
     */
    public function documents()
    {
        return $this->hasMany(ArchiveDocument::class)->where('is_active', true)->orderBy('sort_order');
    }

    /**
     * Tüm belgeler (aktif/pasif)
     */
    public function allDocuments()
    {
        return $this->hasMany(ArchiveDocument::class)->orderBy('sort_order');
    }

    /**
     * Yayınlanmış arşivler scope
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published')
                    ->where('published_at', '<=', now());
    }

    /**
     * Öne çıkan arşivler scope
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Durum scope
     */
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Arama scope
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function($q) use ($search) {
            $q->where('title', 'LIKE', "%{$search}%")
              ->orWhere('excerpt', 'LIKE', "%{$search}%")
              ->orWhere('content', 'LIKE', "%{$search}%");
        });
    }

    /**
     * Durum metni accessor
     */
    public function getStatusTextAttribute()
    {
        return match($this->status) {
            'draft' => 'Taslak',
            'published' => 'Yayında',
            'archived' => 'Arşivlenmiş',
            default => 'Bilinmiyor'
        };
    }

    /**
     * Formatlı tarih accessor
     */
    public function getFormattedDateAttribute()
    {
        return $this->published_at ? $this->published_at->format('d.m.Y') : $this->created_at->format('d.m.Y');
    }

    /**
     * Formatlı tarih ve saat accessor
     */
    public function getFormattedDateTimeAttribute()
    {
        return $this->published_at ? $this->published_at->format('d.m.Y H:i') : $this->created_at->format('d.m.Y H:i');
    }

    /**
     * Özet accessor - content'ten otomatik özet oluşturma
     */
    public function getAutoExcerptAttribute()
    {
        if ($this->excerpt) {
            return $this->excerpt;
        }
        
        if ($this->content) {
            return Str::limit(strip_tags($this->content), 200);
        }
        
        return '';
    }

    /**
     * URL accessor
     */
    public function getUrlAttribute()
    {
        return route('archives.show', $this->slug);
    }

    /**
     * Görüntülenme sayısını artır
     */
    public function incrementViewCount()
    {
        $this->increment('view_count');
    }

    /**
     * Yayınlanmış mı kontrolü
     */
    public function isPublished()
    {
        return $this->status === 'published' && 
               ($this->published_at === null || $this->published_at <= now());
    }

    /**
     * Öne çıkan mı kontrolü
     */
    public function isFeatured()
    {
        return $this->is_featured;
    }
}
