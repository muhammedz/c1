<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Mudurluk extends Model
{
    use HasFactory;

    /**
     * Model için kullanılacak tablo adı.
     *
     * @var string
     */
    protected $table = 'mudurlukler';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'slug',
        'gorev_tanimi_ve_faaliyet_alani',
        'yetki_ve_sorumluluklar',
        'summary',
        'image',
        'is_active',
        'order_column',
        'view_count',
        'meta_title',
        'meta_description'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();
        
        // Otomatik slug oluşturma
        static::creating(function ($mudurluk) {
            if (empty($mudurluk->slug)) {
                $mudurluk->slug = Str::slug($mudurluk->name);
                
                // Slug benzersizliği kontrolü
                $counter = 1;
                $originalSlug = $mudurluk->slug;
                while (static::where('slug', $mudurluk->slug)->exists()) {
                    $mudurluk->slug = $originalSlug . '-' . $counter;
                    $counter++;
                }
            }
        });
    }

    /**
     * İlişkiler
     */

    /**
     * Tüm dosyaları getir
     */
    public function files()
    {
        return $this->hasMany(MudurlukFile::class)->orderBy('order_column');
    }

    /**
     * Hizmet standartları dosyalarını getir
     */
    public function hizmetStandartlari()
    {
        return $this->hasMany(MudurlukFile::class)
                   ->where('type', 'hizmet_standartlari')
                   ->where('is_active', true)
                   ->orderBy('order_column');
    }

    /**
     * Yönetim şemaları dosyalarını getir
     */
    public function yonetimSemalari()
    {
        return $this->hasMany(MudurlukFile::class)
                   ->where('type', 'yonetim_semalari')
                   ->where('is_active', true)
                   ->orderBy('order_column');
    }

    /**
     * Hizmet kategorileri ile many-to-many ilişki
     */
    public function serviceCategories()
    {
        return $this->belongsToMany(ServiceCategory::class, 'mudurluk_service_category', 'mudurluk_id', 'service_category_id')
                   ->withTimestamps();
    }

    /**
     * İlgili hizmetleri getir (seçilen kategorilerdeki tüm hizmetler)
     */
    public function relatedServices()
    {
        return Service::whereHas('categories', function($query) {
            $query->whereIn('service_categories.id', $this->serviceCategories()->pluck('service_categories.id'));
        })
        ->where('status', 'published')
        ->with(['categories'])
        ->orderBy('published_at', 'desc');
    }

    /**
     * Scope'lar
     */

    /**
     * Aktif müdürlükleri getir
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Sıralı olarak getir
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order_column')->orderBy('name');
    }

    /**
     * Helper metodlar
     */

    /**
     * Görüntülenme sayısını artır
     */
    public function incrementViews()
    {
        $this->increment('view_count');
    }

    /**
     * Müdürlük URL'sini getir
     */
    public function getUrlAttribute()
    {
        return route('mudurlukler.show', $this->slug);
    }

    /**
     * Kapak resminin tam URL'sini getir
     */
    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return asset('storage/' . $this->image);
        }
        return asset('images/default-mudurluk.jpg');
    }

    /**
     * Kısa özet getir (frontend için)
     */
    public function getShortSummaryAttribute()
    {
        if ($this->summary) {
            return Str::limit($this->summary, 100);
        }
        return 'Bu müdürlük hakkında detaylı bilgi almak için tıklayın.';
    }
}
