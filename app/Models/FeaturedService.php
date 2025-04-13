<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeaturedService extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'icon',
        'url',
        'order',
        'is_active',
    ];
    
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
    ];
    
    /**
     * Get the HTML for the icon.
     *
     * @return string
     */
    public function getIconHtmlAttribute()
    {
        if (empty($this->icon)) {
            return '<i class="fas fa-cube"></i>';
        }
        
        // SVG içeriği kontrolü
        if (str_starts_with($this->icon, '<svg')) {
            return $this->icon;
        }
        
        // Font Awesome ikonu (tam sınıf adı ile)
        return '<i class="' . $this->icon . '"></i>';
    }
    
    /**
     * Get all active services ordered by the 'order' field
     */
    public static function getActiveServices()
    {
        return self::where('is_active', true)
            ->orderBy('order')
            ->get();
    }
}
