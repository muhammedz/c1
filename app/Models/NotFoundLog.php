<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class NotFoundLog extends Model
{
    use HasFactory;

    protected $table = '404_logs';

    protected $fillable = [
        'url',
        'referer',
        'user_agent',
        'ip_address',
        'hit_count',
        'first_seen_at',
        'last_seen_at',
        'is_resolved'
    ];

    protected $casts = [
        'first_seen_at' => 'datetime',
        'last_seen_at' => 'datetime',
        'is_resolved' => 'boolean',
    ];

    /**
     * 404 log kaydı oluştur veya güncelle
     */
    public static function logNotFound($url, $referer = null, $userAgent = null, $ipAddress = null)
    {
        $log = self::where('url', $url)->first();
        
        if ($log) {
            // Mevcut kayıt varsa hit count artır ve son görülme tarihini güncelle
            $log->increment('hit_count');
            $log->update(['last_seen_at' => now()]);
        } else {
            // Yeni kayıt oluştur
            self::create([
                'url' => $url,
                'referer' => $referer,
                'user_agent' => $userAgent,
                'ip_address' => $ipAddress,
                'hit_count' => 1,
                'first_seen_at' => now(),
                'last_seen_at' => now(),
            ]);
        }
    }

    /**
     * Çözülmemiş 404'leri getir
     */
    public function scopeUnresolved($query)
    {
        return $query->where('is_resolved', false);
    }

    /**
     * En çok hit alan 404'leri getir
     */
    public function scopeTopHits($query, $limit = 10)
    {
        return $query->orderBy('hit_count', 'desc')->limit($limit);
    }

    /**
     * Son görülen 404'leri getir
     */
    public function scopeRecent($query, $days = 7)
    {
        return $query->where('last_seen_at', '>=', Carbon::now()->subDays($days));
    }

    /**
     * URL'yi temizle (query parametrelerini kaldır)
     */
    public function getCleanUrlAttribute()
    {
        return strtok($this->url, '?');
    }

    /**
     * Çözüldü olarak işaretle
     */
    public function markAsResolved()
    {
        $this->update(['is_resolved' => true]);
    }
}
