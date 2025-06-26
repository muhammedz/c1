<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SearchPriorityLink extends Model
{
    use HasFactory;

    protected $fillable = [
        'search_keywords',
        'title',
        'url',
        'description',
        'icon',
        'priority',
        'is_active',
        'click_count'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'priority' => 'integer',
        'click_count' => 'integer'
    ];

    /**
     * Arama kelimelerine göre eşleşen linkleri getir
     *
     * @param string $searchQuery
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getMatchingLinks($searchQuery)
    {
        if (empty($searchQuery)) {
            return collect();
        }

        // Arama sorgusunu normalize et (Türkçe karakterler)
        $normalizedQuery = mb_strtolower($searchQuery, 'UTF-8');
        $normalizedQuery = str_replace(['ı', 'ğ', 'ü', 'ş', 'ö', 'ç'], ['i', 'g', 'u', 's', 'o', 'c'], $normalizedQuery);

        return self::where('is_active', true)
            ->where(function($query) use ($searchQuery, $normalizedQuery) {
                // Orijinal arama terimi ile eşleşme
                $query->where('search_keywords', 'LIKE', "%{$searchQuery}%")
                      // Normalize edilmiş terim ile eşleşme
                      ->orWhere('search_keywords', 'LIKE', "%{$normalizedQuery}%");
            })
            ->orderBy('priority')
            ->orderBy('title')
            ->get();
    }

    /**
     * Aktif linkleri getir
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Öncelik sırasına göre sırala
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('priority')->orderBy('title');
    }

    /**
     * Tıklama sayısına göre sırala (en çok tıklanan önce)
     */
    public function scopeOrderByClicks($query)
    {
        return $query->orderBy('click_count', 'desc');
    }

    /**
     * Tıklama sayısını artır
     */
    public function incrementClickCount()
    {
        $this->increment('click_count');
    }

    /**
     * Search keywords'ü array olarak getir
     */
    public function getKeywordsArrayAttribute()
    {
        return array_map('trim', explode(',', $this->search_keywords));
    }

    /**
     * URL'nin tam halini getir
     */
    public function getFullUrlAttribute()
    {
        // Eğer URL zaten tam bir URL ise
        if (str_starts_with($this->url, 'http://') || str_starts_with($this->url, 'https://')) {
            return $this->url;
        }

        // Eğer URL / ile başlamıyorsa ekle
        $url = str_starts_with($this->url, '/') ? $this->url : '/' . $this->url;
        
        return url($url);
    }
}
