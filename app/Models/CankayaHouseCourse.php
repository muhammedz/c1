<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class CankayaHouseCourse extends Model
{
    use HasFactory;

    protected $fillable = [
        'cankaya_house_id',
        'name',
        'icon',
        'description',
        'start_date',
        'end_date',
        'instructor',
        'capacity',
        'price',
        'status',
        'order'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'price' => 'decimal:2',
    ];

    // İlişkiler
    public function cankayaHouse()
    {
        return $this->belongsTo(CankayaHouse::class);
    }

    // Scope'lar
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeUpcoming($query)
    {
        return $query->where('start_date', '>', now());
    }

    public function scopeOngoing($query)
    {
        return $query->where('start_date', '<=', now())
                    ->where('end_date', '>=', now());
    }

    public function scopeCompleted($query)
    {
        return $query->where('end_date', '<', now());
    }

    public function scopeRecent($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    // Accessor'lar
    public function getFormattedStartDateAttribute()
    {
        return $this->start_date ? $this->start_date->format('d.m.Y') : null;
    }

    public function getFormattedEndDateAttribute()
    {
        return $this->end_date ? $this->end_date->format('d.m.Y') : null;
    }

    public function getFormattedPriceAttribute()
    {
        return $this->price ? number_format($this->price, 2, ',', '.') . ' ₺' : 'Ücretsiz';
    }

    public function getStatusTextAttribute()
    {
        return match($this->status) {
            'active' => 'Aktif',
            'inactive' => 'Pasif',
            'completed' => 'Tamamlandı',
            default => 'Bilinmiyor'
        };
    }

    public function getIsUpcomingAttribute()
    {
        return $this->start_date && $this->start_date->isFuture();
    }

    public function getIsOngoingAttribute()
    {
        return $this->start_date && $this->end_date && 
               $this->start_date->isPast() && $this->end_date->isFuture();
    }

    public function getIsCompletedAttribute()
    {
        return $this->end_date && $this->end_date->isPast();
    }
}
