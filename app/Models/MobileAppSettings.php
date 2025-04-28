<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MobileAppSettings extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'app_logo',
        'app_header_image',
        'app_name',
        'app_subtitle',
        'app_description',
        'phone_image',
        'app_store_link',
        'google_play_link',
        'link_card_1_title',
        'link_card_1_url',
        'link_card_1_icon',
        'link_card_2_title',
        'link_card_2_url',
        'link_card_2_icon',
        'link_card_3_title',
        'link_card_3_url',
        'link_card_3_icon',
        'is_active',
    ];
}
