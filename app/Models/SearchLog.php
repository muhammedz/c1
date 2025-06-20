<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class SearchLog extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'query',
        'results_count',
        'ip_address',
        'user_agent',
        'searched_at',
    ];
    
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'searched_at' => 'datetime',
        'results_count' => 'integer',
    ];
    
    /**
     * Arama logunu kaydet
     */
    public static function logSearch($query, $resultsCount = 0, $request = null)
    {
        if (empty(trim($query))) {
            return;
        }
        
        return self::create([
            'query' => trim($query),
            'results_count' => $resultsCount,
            'ip_address' => $request ? $request->ip() : request()->ip(),
            'user_agent' => $request ? $request->userAgent() : request()->userAgent(),
            'searched_at' => now(),
        ]);
    }
    
    /**
     * En çok aranan kelimeleri getir
     */
    public static function getPopularSearches($limit = 10, $days = 30)
    {
        $results = self::select('query')
            ->selectRaw('COUNT(*) as search_count')
            ->selectRaw('MAX(searched_at) as last_searched_raw')
            ->where('searched_at', '>=', Carbon::now()->subDays($days))
            ->groupBy('query')
            ->orderByDesc('search_count')
            ->limit($limit)
            ->get();
            
        // last_searched'i Carbon instance'a çevir
        $results->each(function ($item) {
            $item->last_searched = Carbon::parse($item->last_searched_raw);
            unset($item->last_searched_raw);
        });
        
        return $results;
    }
    
    /**
     * Son aramaları getir
     */
    public static function getRecentSearches($limit = 50)
    {
        return self::orderByDesc('searched_at')
            ->limit($limit)
            ->get();
    }
    
    /**
     * Arama istatistiklerini getir
     */
    public static function getSearchStats($days = 30)
    {
        $startDate = Carbon::now()->subDays($days);
        
        return [
            'total_searches' => self::where('searched_at', '>=', $startDate)->count(),
            'unique_queries' => self::where('searched_at', '>=', $startDate)->distinct('query')->count(),
            'avg_results' => self::where('searched_at', '>=', $startDate)->avg('results_count'),
            'zero_results' => self::where('searched_at', '>=', $startDate)->where('results_count', 0)->count(),
        ];
    }
    
    /**
     * Sonuçsuz aramaları getir
     */
    public static function getZeroResultSearches($limit = 50, $days = 30)
    {
        $results = self::select('query')
            ->selectRaw('COUNT(*) as search_count')
            ->selectRaw('MAX(searched_at) as last_searched_raw')
            ->where('searched_at', '>=', Carbon::now()->subDays($days))
            ->where('results_count', 0)
            ->groupBy('query')
            ->orderByDesc('search_count')
            ->limit($limit)
            ->get();
            
        // last_searched'i Carbon instance'a çevir
        $results->each(function ($item) {
            $item->last_searched = Carbon::parse($item->last_searched_raw);
            unset($item->last_searched_raw);
        });
        
        return $results;
    }
}
