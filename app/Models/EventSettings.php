<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventSettings extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'is_active',
        'title',
        'description',
        'section_title',
        'section_subtitle',
        'homepage_limit',
        'show_past_events',
        'show_category_filter',
        'show_map',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'is_active' => 'boolean',
        'homepage_limit' => 'integer',
        'show_past_events' => 'boolean',
        'show_category_filter' => 'boolean',
        'show_map' => 'boolean',
    ];
}
