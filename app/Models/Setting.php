<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'group',
        'display_name',
        'type',
        'options',
        'description',
        'is_public',
        'is_required',
        'order'
    ];

    protected $casts = [
        'is_public' => 'boolean',
        'is_required' => 'boolean',
        'options' => 'array',
    ];

    /**
     * Belirli bir ayarın değerini getir
     */
    public static function get($key, $default = null)
    {
        $setting = self::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    /**
     * Belirli bir ayarı güncelle veya oluştur
     */
    public static function set($key, $value, $group = 'general')
    {
        return self::updateOrCreate(
            ['key' => $key],
            ['value' => $value, 'group' => $group]
        );
    }

    /**
     * Grup bazında ayarları getir
     */
    public static function getByGroup($group)
    {
        return self::where('group', $group)->orderBy('order')->get();
    }

    /**
     * Public ayarları getir (frontend için)
     */
    public static function getPublic()
    {
        return self::where('is_public', true)->get()->keyBy('key');
    }
}
