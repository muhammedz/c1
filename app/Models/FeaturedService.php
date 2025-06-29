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
        'svg_color',
        'svg_size',
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
        'svg_size' => 'integer',
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
        
        // Data URL (base64 encoded image) kontrolü
        if (str_starts_with($this->icon, 'data:image/')) {
            return '<img src="' . $this->icon . '" alt="İkon" style="width: 48px; height: 48px; object-fit: contain;">';
        }
        
        // SVG içeriği kontrolü
        if (str_starts_with($this->icon, '<svg')) {
            // SVG'deki duplicate ID'leri benzersiz hale getir
            $svgContent = $this->icon;
            
            // Tüm ID'leri benzersiz yap
            $svgContent = preg_replace_callback('/id="([^"]+)"/', function($matches) {
                return 'id="' . $matches[1] . '_' . $this->id . '_' . uniqid() . '"';
            }, $svgContent);
            
            // SVG boyutu ve rengi ayarla
            $size = $this->svg_size ?? 48;
            $color = $this->svg_color ?? '#004d2e';
            
            // Mevcut style ve fill attribute'larını kaldır
            $svgContent = preg_replace('/style="[^"]*"/', '', $svgContent);
            $svgContent = preg_replace('/fill="[^"]*"/', '', $svgContent);
            
            // SVG içindeki tüm path, circle, rect gibi elementlere fill ekle
            $svgContent = preg_replace('/<(path|circle|rect|ellipse|polygon|polyline)([^>]*?)>/i', 
                '<$1$2 fill="' . $color . '">', $svgContent);
            
            // g elementlerine de fill ekle
            $svgContent = preg_replace('/<g([^>]*?)>/i', '<g$1 fill="' . $color . '">', $svgContent);
            
            // Ana SVG elementine CSS class ekle
            $uniqueClass = 'svg-icon-' . $this->id . '-' . uniqid();
            $style = "width: {$size}px; height: {$size}px;";
            
            if (strpos($svgContent, '<svg') !== false) {
                $svgContent = str_replace('<svg', '<svg class="' . $uniqueClass . '" style="' . $style . '"', $svgContent);
                
                // CSS stil ekle
                $cssStyle = '<style>.' . $uniqueClass . ' * { fill: ' . $color . ' !important; }</style>';
                $svgContent = $cssStyle . $svgContent;
            }
            
            return $svgContent;
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
