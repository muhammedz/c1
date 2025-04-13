<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectSettings extends Model
{
    use HasFactory;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'project_settings';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'section_title',
        'section_description',
        'items_per_page',
        'is_active',
        'show_categories_filter',
        'show_view_all',
        'view_all_text',
        'view_all_url',
    ];
    
    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'is_active' => 'boolean',
        'show_categories_filter' => 'boolean',
        'show_view_all' => 'boolean',
        'items_per_page' => 'integer',
    ];
    
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;
    
    /**
     * Get the settings or create default ones if not existing.
     *
     * @return \App\Models\ProjectSettings
     */
    public static function getSettings()
    {
        $settings = self::first();
        
        if (!$settings) {
            $settings = self::create([
                'section_title' => 'Projelerimiz',
                'section_description' => 'Tamamladığımız ve devam eden projelerimiz',
                'items_per_page' => 6,
                'is_active' => true,
                'show_categories_filter' => true,
                'show_view_all' => true,
                'view_all_text' => 'Tüm Projeleri Gör',
                'view_all_url' => '/projeler',
            ]);
        }
        
        return $settings;
    }
} 