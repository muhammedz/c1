<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HeaderSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'logo_path',
        'secondary_logo_path',
        'slogan_path',
        'show_search_button',
        'header_bg_color',
        'header_text_color',
        'header_height',
        'sticky_header',
        'custom_css',
        'additional_scripts',
        'custom_header_html',
        'mobile_logo_path',
    ];

    protected $casts = [
        'show_search_button' => 'boolean',
        'sticky_header' => 'boolean',
        'header_height' => 'integer',
    ];

    /**
     * Varsayılan header ayarlarını getir veya oluştur
     */
    public static function getSettings()
    {
        $settings = self::first();
        
        if (!$settings) {
            $settings = self::create([
                'logo_path' => null,
                'secondary_logo_path' => null,
                'slogan_path' => null,
                'show_search_button' => true,
                'header_bg_color' => '#ffffff',
                'header_text_color' => '#00352b',
                'header_height' => 96,
                'sticky_header' => false,
                'custom_css' => null,
                'additional_scripts' => null,
                'custom_header_html' => null,
                'mobile_logo_path' => null,
            ]);
        }
        
        return $settings;
    }
} 