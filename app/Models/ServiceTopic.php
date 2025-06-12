<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ServiceTopic extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name',
        'slug',
        'description',
        'icon',
        'color',
        'order',
        'is_active',
        'meta_title',
        'meta_description'
    ];
    
    protected $casts = [
        'is_active' => 'boolean',
    ];
    
    // Slug otomatik oluşturma
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = $value;
        if (!$this->slug) {
            $this->attributes['slug'] = Str::slug($value);
        }
    }
    
    // İlişkiler
    
    // Konudaki hizmetler
    public function services()
    {
        return $this->belongsToMany(Service::class, 'service_service_topic', 'service_topic_id', 'service_id')
                    ->withTimestamps();
    }
    
    // Yardımcı metodlar
    
    // Aktif konuları getir
    public static function getActive()
    {
        return self::where('is_active', true)
                   ->orderBy('order', 'asc')
                   ->get();
    }
    
    // Slug'a göre konu getir
    public static function getBySlug($slug)
    {
        return self::where('slug', $slug)
                   ->where('is_active', true)
                   ->first();
    }
    
    // Scope'lar
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
    
    public function scopeOrdered($query)
    {
        return $query->orderBy('order', 'asc');
    }
    
    // URL için
    public function getRouteKeyName()
    {
        return 'slug';
    }
    
    // SEO title
    public function getSeoTitleAttribute()
    {
        return $this->meta_title ?: $this->name . ' Hizmetleri | Çankaya Belediyesi';
    }
    
    // SEO description
    public function getSeoDescriptionAttribute()
    {
        return $this->meta_description ?: 'Çankaya Belediyesi ' . $this->name . ' konusundaki tüm hizmetleri keşfedin.';
    }
}
