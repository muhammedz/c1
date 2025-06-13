<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ArchiveDocumentCategory extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'archive_id',
        'name',
        'slug',
        'icon',
        'description',
        'color',
        'order',
        'is_active'
    ];
    
    protected $casts = [
        'is_active' => 'boolean',
    ];
    
    // Slug otomatik oluşturma
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($category) {
            if (!$category->slug) {
                $category->slug = Str::slug($category->name);
            }
        });
    }
    
    // İlişkiler
    
    // Arşiv ilişkisi
    public function archive()
    {
        return $this->belongsTo(Archive::class);
    }
    
    // Kategorideki belgeler
    public function documents()
    {
        return $this->hasMany(ArchiveDocument::class, 'category_id');
    }
    
    // Aktif belgeler
    public function activeDocuments()
    {
        return $this->hasMany(ArchiveDocument::class, 'category_id')->where('is_active', true)->orderBy('sort_order');
    }
    
    // Yardımcı metodlar
    
    // Aktif kategorileri getir
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
    
    // Sıralı kategorileri getir
    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }
}
