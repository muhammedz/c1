<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeaturedServiceSetting extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'is_active',
        'swiper_items_per_view',
        'swiper_autoplay',
    ];
    
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
        'swiper_items_per_view' => 'integer',
        'swiper_autoplay' => 'integer',
    ];
    
    /**
     * Get the services associated with this setting
     */
    public function services()
    {
        return $this->hasMany(FeaturedService::class);
    }
}
