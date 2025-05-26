<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Tender extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'unit',
        'summary',
        'kik_no',
        'address',
        'phone',
        'fax',
        'email',
        'document_url',
        'description',
        'delivery_place',
        'delivery_date',
        'tender_address',
        'tender_datetime',
        'content',
        'status',
        'slug',
    ];

    protected $casts = [
        'tender_datetime' => 'datetime',
    ];

    // Slug oluşturma
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($tender) {
            if (empty($tender->slug)) {
                $tender->slug = Str::slug($tender->title);
                
                $latestSlug = 
                    static::whereRaw("slug = '$tender->slug' or slug LIKE '$tender->slug-%'")
                        ->latest('id')
                        ->value('slug');
                
                if ($latestSlug) {
                    $pieces = explode('-', $latestSlug);
                    $number = intval(end($pieces));
                    $tender->slug .= '-' . ($number + 1);
                }
            }
        });
    }

    // Durum için accessor
    public function getStatusTextAttribute()
    {
        return [
            'active' => 'Aktif',
            'completed' => 'Tamamlandı',
            'cancelled' => 'İptal Edildi',
        ][$this->status] ?? 'Bilinmiyor';
    }

    // Aktif ihaleleri getiren scope
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    // Tamamlanan ihaleleri getiren scope
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    // İptal edilen ihaleleri getiren scope
    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }
}
