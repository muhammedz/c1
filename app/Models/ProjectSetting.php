<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectSetting extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'section_title',
        'section_description',
        'is_active',
        'items_per_page',
        'show_categories',
        'show_view_all_button',
        'view_all_text',
        'view_all_url',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
        'items_per_page' => 'integer',
        'show_categories' => 'boolean',
        'show_view_all_button' => 'boolean',
    ];

    /**
     * Ayarları getir, eğer yoksa varsayılan ayarları oluştur
     */
    public static function getSettings()
    {
        $settings = self::first();
        
        if (!$settings) {
            $settings = self::create([
                'section_title' => 'Projelerimiz',
                'is_active' => true,
                'items_per_page' => 6,
                'show_categories' => true,
                'show_view_all_button' => true,
                'view_all_text' => 'Tümünü Gör',
            ]);
        }
        
        return $settings;
    }
} 