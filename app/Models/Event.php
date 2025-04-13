<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class Event extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'slug',
        'description',
        'category_id',
        'cover_image',
        'start_date',
        'end_date',
        'location',
        'address',
        'organizer',
        'order',
        'is_active',
        'show_on_homepage',
        'is_featured',
        'register_required',
        'register_url',
        'max_participants',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'is_active' => 'boolean',
        'show_on_homepage' => 'boolean',
        'is_featured' => 'boolean',
        'register_required' => 'boolean',
        'order' => 'integer',
        'max_participants' => 'integer',
    ];

    /**
     * Auto-set the slug from the title if not provided.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($event) {
            if (empty($event->slug)) {
                $event->slug = Str::slug($event->title);
            }
            
            // Etkinlik oluşturma öncesinde loglama
            Log::info('Etkinlik oluşturuluyor', [
                'event_title' => $event->title,
                'cover_image' => $event->cover_image,
                'cover_image_type' => gettype($event->cover_image),
                'cover_image_empty' => empty($event->cover_image)
            ]);
        });

        static::updating(function ($event) {
            if (empty($event->slug)) {
                $event->slug = Str::slug($event->title);
            }
            
            // Etkinlik güncelleme öncesinde loglama
            Log::info('Etkinlik güncelleniyor', [
                'event_id' => $event->id,
                'event_title' => $event->title,
                'cover_image' => $event->cover_image,
                'cover_image_type' => gettype($event->cover_image),
                'cover_image_empty' => empty($event->cover_image),
                'dirty' => $event->isDirty('cover_image') ? 'evet' : 'hayır',
                'original' => $event->getOriginal('cover_image')
            ]);
        });
        
        static::created(function ($event) {
            // Etkinlik oluşturma sonrasında loglama
            Log::info('Etkinlik oluşturuldu', [
                'event_id' => $event->id,
                'event_title' => $event->title,
                'cover_image' => $event->cover_image,
                'cover_image_type' => gettype($event->cover_image),
                'cover_image_empty' => empty($event->cover_image)
            ]);
        });
    }

    /**
     * Get the category that owns the event.
     */
    public function category()
    {
        return $this->belongsTo(EventCategory::class);
    }

    /**
     * Get the images for the event.
     */
    public function images()
    {
        return $this->hasMany(EventImage::class)->orderBy('order');
    }

    /**
     * Get the full URL for the cover image.
     */
    public function getCoverImageUrlAttribute()
    {
        Log::info('getCoverImageUrlAttribute çağrıldı - Event ID: ' . $this->id);
        
        if (!$this->cover_image) {
            Log::info('cover_image değeri boş, varsayılan görsel döndürülüyor');
            return asset('images/no-image.jpg');
        }
        
        Log::info('cover_image değeri işleniyor', [
            'cover_image' => $this->cover_image,
            'cover_image_type' => gettype($this->cover_image),
            'is_absolute_url' => Str::startsWith($this->cover_image, ['http://', 'https://'])
        ]);
        
        // Mutlak URL ise doğrudan döndür
        if (Str::startsWith($this->cover_image, ['http://', 'https://'])) {
            Log::info('Mutlak URL döndürülüyor');
            return $this->cover_image;
        }
        
        // İmaj dosyasının adını al
        $filename = basename($this->cover_image);
        
        // Tüm olası yolları kontrol edelim
        $possiblePaths = [
            // 1. 'events/' ile başlıyorsa web.php route ile dön
            'url' => Str::startsWith($this->cover_image, 'events/') ? url('/events/' . $filename) : null,
            
            // 2. Public içinde varsa doğrudan dön
            'public' => file_exists(public_path('events/' . $filename)) ? url('events/' . $filename) : null,
            
            // 3. Storage içinde varsa, events route ile dön
            'storage' => file_exists(storage_path('app/public/events/' . $filename)) ? url('/events/' . $filename) : null,
            
            // 4. Alternatif dizinde varsa
            'assets' => file_exists(public_path('assets/images/events/' . $filename)) ? url('assets/images/events/' . $filename) : null,
            
            // 5. Eski storage URL'i
            'storage_asset' => asset('storage/' . $this->cover_image)
        ];
        
        Log::info('Olası görsel yolları kontrol ediliyor', [
            'original_path' => $this->cover_image,
            'filename' => $filename,
            'possible_paths' => $possiblePaths
        ]);
        
        // İlk çalışan yolu döndür
        foreach ($possiblePaths as $type => $url) {
            if ($url && $type != 'storage_asset') {
                Log::info("Görsel bulundu: $type yolu kullanılıyor", ['url' => $url]);
                return $url;
            }
        }
        
        // Hiçbir path bulunamadıysa, storage URL'i döndür
        Log::info('Görsel fiziksel olarak bulunamadı, varsayılan storage yolu döndürülüyor', [
            'url' => $possiblePaths['storage_asset']
        ]);
        
        return $possiblePaths['storage_asset'];
    }

    /**
     * Check if the event is upcoming.
     */
    public function getIsUpcomingAttribute()
    {
        return $this->start_date->isFuture();
    }

    /**
     * Check if the event is ongoing.
     */
    public function getIsOngoingAttribute()
    {
        $now = now();
        return $this->start_date->isPast() && ($this->end_date ? $this->end_date->isFuture() : false);
    }

    /**
     * Check if the event is past.
     */
    public function getIsPastAttribute()
    {
        return $this->end_date ? $this->end_date->isPast() : $this->start_date->isPast();
    }

    /**
     * Get the status badge HTML.
     */
    public function getStatusBadgeAttribute()
    {
        if ($this->is_upcoming) {
            return '<span class="badge badge-primary">Yaklaşan Etkinlik</span>';
        } elseif ($this->is_ongoing) {
            return '<span class="badge badge-success">Devam Ediyor</span>';
        } else {
            return '<span class="badge badge-secondary">Tamamlandı</span>';
        }
    }

    /**
     * Format the start date.
     */
    public function getFormattedStartDateAttribute()
    {
        return $this->start_date->format('d.m.Y H:i');
    }

    /**
     * Format the end date.
     */
    public function getFormattedEndDateAttribute()
    {
        return $this->end_date ? $this->end_date->format('d.m.Y H:i') : null;
    }

    /**
     * Türkçe ay isimleriyle formatlanmış başlangıç tarihi.
     */
    public function getTurkishStartDateAttribute()
    {
        $ay_isimleri = [
            1 => 'Ocak', 2 => 'Şubat', 3 => 'Mart', 4 => 'Nisan',
            5 => 'Mayıs', 6 => 'Haziran', 7 => 'Temmuz', 8 => 'Ağustos',
            9 => 'Eylül', 10 => 'Ekim', 11 => 'Kasım', 12 => 'Aralık'
        ];
        
        $gun = $this->start_date->format('d');
        $ay = $ay_isimleri[$this->start_date->format('n')];
        $yil = $this->start_date->format('Y');
        
        return $gun . ' ' . $ay . ' ' . $yil;
    }

    /**
     * Türkçe ay isimleriyle formatlanmış bitiş tarihi.
     */
    public function getTurkishEndDateAttribute()
    {
        if (!$this->end_date) {
            return null;
        }
        
        $ay_isimleri = [
            1 => 'Ocak', 2 => 'Şubat', 3 => 'Mart', 4 => 'Nisan',
            5 => 'Mayıs', 6 => 'Haziran', 7 => 'Temmuz', 8 => 'Ağustos',
            9 => 'Eylül', 10 => 'Ekim', 11 => 'Kasım', 12 => 'Aralık'
        ];
        
        $gun = $this->end_date->format('d');
        $ay = $ay_isimleri[$this->end_date->format('n')];
        $yil = $this->end_date->format('Y');
        
        return $gun . ' ' . $ay . ' ' . $yil;
    }

    /**
     * Get a short excerpt of the description.
     */
    public function getExcerptAttribute($length = 100)
    {
        $plainText = strip_tags($this->description);
        
        if (strlen($plainText) <= $length) {
            return $plainText;
        }
        
        return substr($plainText, 0, $length) . '...';
    }

    /**
     * Scope a query to only include active events.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include events that should be shown on the homepage.
     */
    public function scopeHomepage($query)
    {
        return $query->where('show_on_homepage', true)->where('is_active', true);
    }

    /**
     * Scope a query to only include featured events.
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true)->where('is_active', true);
    }

    /**
     * Scope a query to only include upcoming events.
     */
    public function scopeUpcoming($query)
    {
        return $query->where('start_date', '>=', now())->where('is_active', true);
    }

    /**
     * Scope a query to only include past events.
     */
    public function scopePast($query)
    {
        return $query->where(function($q) {
            $q->where('end_date', '<', now())
              ->orWhere(function($q2) {
                  $q2->whereNull('end_date')
                     ->where('start_date', '<', now());
              });
        })->where('is_active', true);
    }

    /**
     * Scope a query to filter by category.
     */
    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    /**
     * Get all active events.
     */
    public static function getAllActiveEvents()
    {
        return self::with('category')
            ->where('is_active', true)
            ->orderBy('start_date', 'asc')
            ->get();
    }

    /**
     * Get upcoming events with optional limit.
     */
    public static function getUpcomingEvents($limit = null)
    {
        $query = self::with('category')
            ->where('is_active', true)
            ->where('start_date', '>=', now())
            ->orderBy('start_date', 'asc');
        
        return $limit ? $query->limit($limit)->get() : $query->get();
    }

    /**
     * Get events for the homepage.
     */
    public static function getHomepageEvents()
    {
        return self::where('is_active', true)
            ->where('show_on_homepage', true)
            ->orderBy('start_date', 'asc')
            ->get();
    }

    /**
     * Find an event by slug.
     */
    public static function findBySlug($slug)
    {
        return self::where('slug', $slug)->first();
    }
}
