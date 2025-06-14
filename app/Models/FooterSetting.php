<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FooterSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'logo',
        'company_name',
        'company_subtitle',
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
        'copyright_right'
    ];

    public static function getSettings()
    {
        return static::first() ?? new static();
    }

    public function getLogoUrlAttribute()
    {
        return $this->logo ? asset('uploads/' . $this->logo) : null;
    }
}
