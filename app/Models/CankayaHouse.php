<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class CankayaHouse extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'address',
        'phone',
        'location_link',
        'images',
        'status',
        'order'
    ];

    protected $casts = [
        'images' => 'array',
    ];

    // Slug otomatik oluşturma
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->slug)) {
                $model->slug = Str::slug($model->name);
            }
        });

        static::updating(function ($model) {
            if ($model->isDirty('name') && empty($model->slug)) {
                $model->slug = Str::slug($model->name);
            }
        });
    }

    // İlişkiler
    public function courses()
    {
        return $this->hasMany(CankayaHouseCourse::class);
    }

    public function activeCourses()
    {
        return $this->hasMany(CankayaHouseCourse::class)->where('status', 'active');
    }

    // Scope'lar
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order', 'asc')->orderBy('name', 'asc');
    }

    // Accessor'lar
    public function getFirstImageAttribute()
    {
        if ($this->images && is_array($this->images) && count($this->images) > 0) {
            return $this->images[0];
        }
        return null;
    }

    public function getImageCountAttribute()
    {
        return $this->images ? count($this->images) : 0;
    }

    // Route model binding için - Frontend için slug, admin için id
    public function getRouteKeyName()
    {
        // Admin route'larında id kullan, frontend'te slug kullan
        if (request()->is('admin/*')) {
            return 'id';
        }
        return 'slug';
    }
}
