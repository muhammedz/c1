<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class ServiceSetting extends Model
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
     * Hizmet ayarlarını getir, yoksa varsayılan değerlerle oluştur
     *
     * @return \App\Models\ServiceSetting
     */
    public static function getSettings()
    {
        // Önce tablonun var olup olmadığını kontrol et
        if (!Schema::hasTable('service_settings')) {
            // Tablo yoksa varsayılan ayarları bir model yerine dizi olarak döndür
            return (object)[
                'hero_badge_text' => 'Hizmetlerimiz',
                'hero_title' => 'Size Özel Profesyonel Hizmetler',
                'hero_title_highlight' => 'Profesyonel',
                'hero_description' => 'İhtiyacınıza uygun kaliteli hizmetlerimizi keşfedin ve size nasıl yardımcı olabileceğimizi öğrenin.',
                'search_title' => 'Hangi hizmeti arıyorsunuz?',
                'search_placeholder' => 'Anahtar kelime yazın...',
                'search_button_text' => 'Ara',
                'popular_searches_title' => 'Popüler aramalar:',
                'popular_searches' => [
                    ['text' => 'Eğitim', 'search' => 'eğitim'],
                    ['text' => 'Destek', 'search' => 'destek'],
                    ['text' => 'Danışmanlık', 'search' => 'danışmanlık'],
                ],
            ];
        }
        
        $settings = self::first();
        
        if (!$settings) {
            // Varsayılan değerlerle yeni bir ayar kaydı oluştur
            $settings = self::create([
                'hero_badge_text' => 'Hizmetlerimiz',
                'hero_title' => 'Size Özel Profesyonel Hizmetler',
                'hero_title_highlight' => 'Profesyonel',
                'hero_description' => 'İhtiyacınıza uygun kaliteli hizmetlerimizi keşfedin ve size nasıl yardımcı olabileceğimizi öğrenin.',
                'search_title' => 'Hangi hizmeti arıyorsunuz?',
                'search_placeholder' => 'Anahtar kelime yazın...',
                'search_button_text' => 'Ara',
                'popular_searches_title' => 'Popüler aramalar:',
                'popular_searches' => [
                    ['text' => 'Eğitim', 'search' => 'eğitim'],
                    ['text' => 'Destek', 'search' => 'destek'],
                    ['text' => 'Danışmanlık', 'search' => 'danışmanlık'],
                ],
            ]);
        }
        
        return $settings;
    }
} 