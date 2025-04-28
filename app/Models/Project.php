<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Project extends Model
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
        'completion_percentage',
        'project_date',
        'cover_image',
        'order',
        'is_active',
        'show_on_homepage',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'completion_percentage' => 'integer',
        'project_date' => 'date',
        'is_active' => 'boolean',
        'show_on_homepage' => 'boolean',
        'order' => 'integer',
    ];

    /**
     * Auto-set the slug from the title if not provided.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($project) {
            if (empty($project->slug)) {
                $project->slug = Str::slug($project->title);
            }
        });

        static::updating(function ($project) {
            if (empty($project->slug)) {
                $project->slug = Str::slug($project->title);
            }
        });
    }

    /**
     * Get the category that owns the project.
     */
    public function category()
    {
        return $this->belongsTo(ProjectCategory::class, 'category_id');
    }

    /**
     * Get the gallery images for the project.
     */
    public function gallery()
    {
        return $this->hasMany(ProjectImage::class, 'project_id')->orderBy('order', 'asc');
    }

    /**
     * Get the full URL for the cover image.
     */
    public function getCoverImageUrlAttribute()
    {
        if (!$this->cover_image) {
            return asset('images/no-image.jpg');
        }
        
        // Eğer zaten tam URL ise
        if (Str::startsWith($this->cover_image, ['http://', 'https://'])) {
            return $this->cover_image;
        }
        
        return asset('storage/' . $this->cover_image);
    }

    /**
     * Get the status badge HTML.
     */
    public function getStatusBadgeAttribute()
    {
        $percentage = $this->completion_percentage;
        
        if ($percentage >= 100) {
            return '<span class="badge badge-success">Tamamlandı</span>';
        } elseif ($percentage >= 75) {
            return '<span class="badge badge-info">Son Aşamada (' . $percentage . '%)</span>';
        } elseif ($percentage >= 50) {
            return '<span class="badge badge-primary">Devam Ediyor (' . $percentage . '%)</span>';
        } elseif ($percentage >= 25) {
            return '<span class="badge badge-warning">Başlangıç Aşamasında (' . $percentage . '%)</span>';
        } else {
            return '<span class="badge badge-secondary">Planlanıyor (' . $percentage . '%)</span>';
        }
    }

    /**
     * Format the project date.
     */
    public function getFormattedDateAttribute()
    {
        return $this->project_date->format('d.m.Y');
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
     * Scope a query to only include active projects.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include projects that should be shown on the homepage.
     */
    public function scopeHomepage($query)
    {
        return $query->where('show_on_homepage', true)->where('is_active', true);
    }

    /**
     * Scope a query to filter by category.
     */
    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    /**
     * Projeye ait görseller
     */
    public function images()
    {
        return $this->hasMany(ProjectImage::class, 'project_id')->orderBy('order');
    }

    /**
     * Tüm aktif projeleri kategori sırasına ve proje sırasına göre getirir
     */
    public static function getAllActiveProjects()
    {
        try {
            return self::with('category')
                ->where('is_active', true)
                ->leftJoin('project_categories', 'projects.category_id', '=', 'project_categories.id')
                ->orderBy('project_categories.order', 'asc')
                ->orderBy('projects.order', 'asc')
                ->select('projects.*')
                ->get();
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Proje listesi hatası: ' . $e->getMessage());
            // Hata durumunda daha basit bir sorgu ile sadece projeleri getirelim
            return self::where('is_active', true)
                ->orderBy('order', 'asc')
                ->get();
        }
    }

    /**
     * Belirli bir kategorideki aktif projeleri sıralı olarak getirir
     */
    public static function getByCategorySlug($categorySlug)
    {
        $category = ProjectCategory::where('slug', $categorySlug)->first();
        
        if (!$category) {
            return collect();
        }
        
        return self::where('category_id', $category->id)
            ->where('is_active', true)
            ->orderBy('order')
            ->get();
    }

    /**
     * Ana sayfada gösterilecek projeleri getirir
     */
    public static function getHomepageProjects()
    {
        return self::where('is_active', true)
            ->where('show_on_homepage', true)
            ->orderBy('order')
            ->get();
    }

    /**
     * Slug değerine göre projeyi bul
     */
    public static function findBySlug($slug)
    {
        return self::where('slug', $slug)->first();
    }
} 