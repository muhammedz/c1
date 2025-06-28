<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FooterSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'logo',
        'address_line1',
        'address_line2',
        'contact_center_title',
        'contact_center_phone',
        'whatsapp_title',
        'whatsapp_number',
        'email_title',
        'email_address',
        'kep_title',
        'kep_address',
        'copyright_left',
        'copyright_right',
        'facebook_url',
        'instagram_url',
        'twitter_url',
        'youtube_url',
        'linkedin_url'
    ];

    public static function getSettings()
    {
        return static::first() ?? new static();
    }

    public function getLogoUrlAttribute()
    {
        if (!$this->logo) {
            return null;
        }
        
        // URL'deki boşluk karakterlerini %20 ile değiştir
        $logoPath = str_replace(' ', '%20', $this->logo);
        return asset('uploads/' . $logoPath);
    }
}
