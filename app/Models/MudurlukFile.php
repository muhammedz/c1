<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MudurlukFile extends Model
{
    use HasFactory;

    /**
     * Model için kullanılacak tablo adı.
     *
     * @var string
     */
    protected $table = 'mudurluk_files';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'mudurluk_id',
        'type',
        'title',
        'file_path',
        'file_name',
        'file_size',
        'order_column',
        'is_active'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * İlişkiler
     */

    /**
     * Bu dosyanın ait olduğu müdürlük
     */
    public function mudurluk()
    {
        return $this->belongsTo(Mudurluk::class);
    }

    /**
     * Scope'lar
     */

    /**
     * Aktif dosyaları getir
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Hizmet standartları dosyalarını getir
     */
    public function scopeHizmetStandartlari($query)
    {
        return $query->where('type', 'hizmet_standartlari');
    }

    /**
     * Yönetim şemaları dosyalarını getir
     */
    public function scopeYonetimSemalari($query)
    {
        return $query->where('type', 'yonetim_semalari');
    }

    /**
     * Sıralı olarak getir
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order_column');
    }

    /**
     * Helper metodlar
     */

    /**
     * Dosyanın tam URL'sini getir
     */
    public function getFileUrlAttribute()
    {
        return asset('storage/' . $this->file_path);
    }

    /**
     * Dosya boyutunu human readable formatta getir
     */
    public function getFileSizeFormattedAttribute()
    {
        $bytes = $this->file_size;
        
        if ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        } else {
            return $bytes . ' Bytes';
        }
    }

    /**
     * Dosya tipinin Türkçe karşılığını getir
     */
    public function getTypeDisplayAttribute()
    {
        return match($this->type) {
            'hizmet_standartlari' => 'Hizmet Standartları',
            'yonetim_semalari' => 'Yönetim Şemaları',
            default => ucfirst($this->type)
        };
    }

    /**
     * Download linki için route
     */
    public function getDownloadUrlAttribute()
    {
        return route('mudurlukler.download-file', [
            'mudurluk' => $this->mudurluk->slug,
            'file' => $this->id
        ]);
    }
}
