<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PageSetting extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'hero_badge_text',
        'hero_title',
        'hero_title_highlight',
        'hero_description',
        'search_title',
        'search_placeholder',
        'search_button_text',
        'popular_searches_title',
        'popular_searches',
    ];
    
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'popular_searches' => 'array',
    ];
    
    /**
     * Get the default settings
     * 
     * @return \App\Models\PageSetting
     */
    public static function getSettings()
    {
        $settings = self::first();
        
        if (!$settings) {
            // Varsayılan değerlerle yeni bir ayar kaydı oluştur
            $settings = self::create([
                'hero_badge_text' => 'Bilgi Bankası',
                'hero_title' => 'Bilgiye Hızlı Erişim',
                'hero_title_highlight' => 'Hızlı',
                'hero_description' => 'İhtiyacınız olan tüm bilgilere kolayca ulaşın ve aradığınız içerikleri keşfedin.',
                'search_title' => 'Ne aramak istersiniz?',
                'search_placeholder' => 'Anahtar kelime yazın...',
                'search_button_text' => 'Aramayı Başlat',
                'popular_searches_title' => 'Popüler aramalar:',
                'popular_searches' => [
                    ['text' => 'Meclis', 'search' => 'meclis'],
                    ['text' => 'Hizmetler', 'search' => 'hizmet'],
                    ['text' => 'Duyurular', 'search' => 'duyuru'],
                ],
            ]);
        }
        
        return $settings;
    }
}
