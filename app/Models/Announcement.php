<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'title',
        'content',
        'button_text',
        'button_url',
        'bg_color',
        'text_color',
        'border_color',
        'icon',
        'display_pages',
        'max_views_per_user',
        'position',
        'active',
    ];
    
    protected $casts = [
        'display_pages' => 'array',
        'active' => 'boolean',
        'max_views_per_user' => 'integer',
    ];
    
    // Belirli bir sayfa için aktif duyuruları getiren metot
    public static function getActiveForPage($page)
    {
        return self::where('active', true)
            ->where(function($query) use ($page) {
                $query->whereNull('display_pages')
                    ->orWhereJsonContains('display_pages', $page)
                    ->orWhereJsonContains('display_pages', 'all');
            })
            ->get();
    }
}
