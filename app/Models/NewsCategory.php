<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class NewsCategory extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name',
        'slug',
        'icon',
        'description',
        'parent_id',
        'order',
        'is_active'
    ];
    
    protected $casts = [
        'is_active' => 'boolean',
    ];
    
    // Slug otomatik oluşturma
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }
    
    // İlişkiler
    
    // Üst kategoriler
    public function parent()
    {
        return $this->belongsTo(NewsCategory::class, 'parent_id');
    }
    
    // Alt kategoriler
    public function children()
    {
        return $this->hasMany(NewsCategory::class, 'parent_id');
    }
    
    // Kategorideki haberler
    public function news()
    {
        return $this->belongsToMany(News::class, 'news_category', 'category_id', 'news_id')
                    ->withTimestamps();
    }
    
    // Yardımcı metodlar
    
    // Aktif kategorileri getir
    public static function getActive()
    {
        return self::where('is_active', true)
                   ->orderBy('order', 'asc')
                   ->get();
    }
    
    // Ana kategorileri getir
    public static function getMainCategories()
    {
        return self::whereNull('parent_id')
                   ->where('is_active', true)
                   ->orderBy('order', 'asc')
                   ->get();
    }
    
    // Slug'a göre kategori getir
    public static function getBySlug($slug)
    {
        return self::where('slug', $slug)
                   ->where('is_active', true)
                   ->first();
    }
} 