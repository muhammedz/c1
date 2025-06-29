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
     * Check if the current request is from a mobile device
     *
     * @return bool
     */
    private function isMobileDevice()
    {
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
        return preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i', $userAgent) || 
               preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i', substr($userAgent, 0, 4));
    }

    /**
     * Get the appropriate icon size based on context
     *
     * @return int
     */
    private function getIconSize()
    {
        $isAdmin = strpos($_SERVER['REQUEST_URI'] ?? '', 'admin') !== false;
        
        if ($isAdmin) {
            return 24; // Admin panelde küçük
        } else {
            return $this->isMobileDevice() ? 32 : ($this->svg_size ?? 48);
        }
    }

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
            $size = $this->getIconSize();
            $uniqueClass = 'image-icon-' . $this->id . '-' . uniqid();
            return '<img src="' . $this->icon . '" alt="İkon" class="' . $uniqueClass . '" style="width: ' . $size . 'px; height: ' . $size . 'px; object-fit: contain; display: inline-block; vertical-align: middle;">';
        }
        
        // SVG içeriği kontrolü
        if (str_starts_with($this->icon, '<svg')) {
            $svgContent = $this->icon;
            
            // SVG boyutu ve rengi ayarla
            $size = $this->getIconSize();
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
