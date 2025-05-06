<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class HedefKitle extends Model
{
    use HasFactory;
    
    /**
     * Model için kullanılacak tablo adı.
     *
     * @var string
     */
    protected $table = 'hedef_kitleler';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'slug',
        'description',
        'order',
        'is_active'
    ];
    
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean'
    ];
    
    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();
        
        // Otomatik slug oluşturma
        static::creating(function ($hedefKitle) {
            if (empty($hedefKitle->slug)) {
                $hedefKitle->slug = Str::slug($hedefKitle->name);
            }
        });
    }
    
    /**
     * Get active hedef kitleler.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Haberleri getir
     */
    public function news()
    {
        return $this->belongsToMany(News::class, 'hedef_kitle_news');
    }
    
    /**
     * Hizmetleri getir
     */
    public function services()
    {
        return $this->belongsToMany(Service::class, 'hedef_kitle_service', 'hedef_kitle_id', 'service_id')
                   ->withTimestamps();
    }
} 