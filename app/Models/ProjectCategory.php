<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ProjectCategory extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'slug',
        'order',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
    ];

    /**
     * Auto-set the slug from the name if not provided.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });

        static::updating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });
    }

    /**
     * Get the projects for the category.
     */
    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    /**
     * Get active projects for the category.
     */
    public function activeProjects()
    {
        return $this->hasMany(Project::class)->where('is_active', true);
    }

    /**
     * Scope a query to only include active categories.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get the number of active projects in this category.
     */
    public function getActiveProjectsCountAttribute()
    {
        return $this->projects()->where('is_active', true)->count();
    }

    /**
     * Aktif kategorileri sıralı olarak getir
     */
    public static function getActiveCategories()
    {
        return self::where('is_active', true)
            ->orderBy('order')
            ->get();
    }

    /**
     * Kategori slug değerine göre kategoriyi bul
     */
    public static function findBySlug($slug)
    {
        return self::where('slug', $slug)->first();
    }
} 