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
            $svgContent = $this->icon;
            
            // SVG boyutu ve rengi ayarla
            $size = $this->svg_size ?? 48;
            $color = $this->svg_color ?? '#004d2e';
            
            // Ana SVG elementine CSS class ve stil ekle
            $uniqueClass = 'svg-icon-' . $this->id . '-' . uniqid();
            $style = "width: {$size}px; height: {$size}px;";
            
            if (strpos($svgContent, '<svg') !== false) {
                // SVG'yi normalize et - width ve height'ı kaldır
                $svgContent = preg_replace('/width="[^"]*"/', '', $svgContent);
                $svgContent = preg_replace('/height="[^"]*"/', '', $svgContent);
                
                // Büyük viewBox'lı SVG'leri standart boyuta çevir
                if (strpos($svgContent, 'viewBox="0 0 1250') !== false) {
                    // ViewBox'ı standart boyuta çevir
                    $svgContent = str_replace('viewBox="0 0 1250.000000 1250.000000"', 'viewBox="0 0 24 24"', $svgContent);
                    
                    // Orijinal transform'u kaldır
                    $svgContent = preg_replace('/<g transform="[^"]*"([^>]*)>/', '<g$1>', $svgContent);
                    
                    // Path koordinatlarını ölçeklendir (1250 -> 24 oranında)
                    $svgContent = preg_replace_callback('/d="([^"]*)"/', function($matches) {
                        $path = $matches[1];
                        // Koordinatları ölçeklendir
                        $path = preg_replace_callback('/(-?\d+\.?\d*)/', function($numMatch) {
                            $num = floatval($numMatch[1]);
                            $scaled = $num * 24 / 1250;
                            return number_format($scaled, 3, '.', '');
                        }, $path);
                        return 'd="' . $path . '"';
                    }, $svgContent);
                    
                    $hasLargeViewBox = false; // Artık standart SVG gibi davran
                } else {
                    $hasLargeViewBox = false;
                }
                
                $svgContent = str_replace('<svg', '<svg class="' . $uniqueClass . '" style="' . $style . '"', $svgContent);
                
                // CSS stil ekle - renk ve boyut için
                if ($hasLargeViewBox) {
                    // Büyük viewBox'lı SVG'ler için özel CSS - merkezi hizalama
                    $halfSize = $size / 2;
                    $cssStyle = '<style>.' . $uniqueClass . ' { display: inline-block; vertical-align: middle; overflow: hidden; } .' . $uniqueClass . ' * { fill: ' . $color . ' !important; } .' . $uniqueClass . ' g { transform: translate(' . $halfSize . 'px, ' . $halfSize . 'px) scale(0.08, 0.08); transform-origin: center center; }</style>';
                } else {
                    // Normal SVG'ler için standart CSS
                    $cssStyle = '<style>.' . $uniqueClass . ' { display: inline-block; vertical-align: middle; overflow: visible; } .' . $uniqueClass . ' * { fill: ' . $color . ' !important; }</style>';
                }
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
