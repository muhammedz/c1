<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SearchSetting extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'placeholder',
        'max_quick_links',
        'max_popular_queries',
        'show_quick_links',
        'show_popular_queries',
        'search_in_mudurluk_files',
        'show_hedef_kitle_filter',
        'show_date_filter',
    ];
    
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'show_quick_links' => 'boolean',
        'show_popular_queries' => 'boolean',
        'search_in_mudurluk_files' => 'boolean',
        'show_hedef_kitle_filter' => 'boolean',
        'show_date_filter' => 'boolean',
        'max_quick_links' => 'integer',
        'max_popular_queries' => 'integer',
    ];
    
    /**
     * Get the current search settings or create default
     */
    public static function getSettings()
    {
        $settings = self::first();
        
        if (!$settings) {
            $settings = self::create([
                'title' => 'Arama',
                'placeholder' => 'Ne aramıştınız?',
                'max_quick_links' => 4,
                'max_popular_queries' => 4,
                'show_quick_links' => true,
                'show_popular_queries' => true,
                'search_in_mudurluk_files' => false,
                'show_hedef_kitle_filter' => false,
                'show_date_filter' => false,
            ]);
        }
        
        return $settings;
    }
}
