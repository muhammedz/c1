<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * Sayfa Geri Bildirim Modeli
 * 
 * Bu model kullanıcıların hizmet sayfalarına verdiği geri bildirimleri yönetir.
 * Kullanıcılar "Bu sayfa size yardımcı oldu mu?" sorusuna evet/hayır cevabı verebilir.
 * 
 * @property int $id
 * @property string $page_url
 * @property string $page_title
 * @property bool $is_helpful
 * @property string $user_ip
 * @property string|null $user_agent
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class PageFeedback extends Model
{
    use HasFactory;

    /**
     * Veritabanı tablosu adı
     */
    protected $table = 'page_feedbacks';

    /**
     * Toplu atama (mass assignment) için izin verilen alanlar
     */
    protected $fillable = [
        'page_url',
        'page_title',
        'is_helpful',
        'user_ip',
        'user_agent',
    ];

    /**
     * Veri tipi dönüşümleri
     */
    protected $casts = [
        'is_helpful' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Belirli bir sayfa için geri bildirim istatistiklerini getirir
     * 
     * @param string $pageUrl
     * @return array
     */
    public static function getPageStats(string $pageUrl): array
    {
        $stats = self::where('page_url', $pageUrl)
            ->selectRaw('
                COUNT(*) as total_feedbacks,
                SUM(CASE WHEN is_helpful = 1 THEN 1 ELSE 0 END) as helpful_count,
                SUM(CASE WHEN is_helpful = 0 THEN 1 ELSE 0 END) as not_helpful_count
            ')
            ->first();

        $total = $stats->total_feedbacks ?? 0;
        $helpful = $stats->helpful_count ?? 0;
        $notHelpful = $stats->not_helpful_count ?? 0;

        return [
            'total_feedbacks' => $total,
            'helpful_count' => $helpful,
            'not_helpful_count' => $notHelpful,
            'helpful_percentage' => $total > 0 ? round(($helpful / $total) * 100, 1) : 0,
            'not_helpful_percentage' => $total > 0 ? round(($notHelpful / $total) * 100, 1) : 0,
        ];
    }

    /**
     * Kullanıcının daha önce bu sayfaya geri bildirim verip vermediğini kontrol eder
     * 
     * @param string $pageUrl
     * @param string $userIp
     * @return bool
     */
    public static function hasUserFeedback(string $pageUrl, string $userIp): bool
    {
        return self::where('page_url', $pageUrl)
            ->where('user_ip', $userIp)
            ->exists();
    }

    /**
     * Yeni geri bildirim oluşturur
     * 
     * @param string $pageUrl
     * @param string $pageTitle
     * @param bool $isHelpful
     * @param string $userIp
     * @param string|null $userAgent
     * @return self
     */
    public static function createFeedback(
        string $pageUrl,
        string $pageTitle,
        bool $isHelpful,
        string $userIp,
        ?string $userAgent = null
    ): self {
        return self::create([
            'page_url' => $pageUrl,
            'page_title' => $pageTitle,
            'is_helpful' => $isHelpful,
            'user_ip' => $userIp,
            'user_agent' => $userAgent,
        ]);
    }

    /**
     * Admin paneli için sayfa bazlı geri bildirim özeti getirir
     * 
     * @param int $limit
     * @return \Illuminate\Support\Collection
     */
    public static function getAdminSummary(int $limit = 10)
    {
        return self::selectRaw('
                page_url,
                page_title,
                COUNT(*) as total_feedbacks,
                SUM(CASE WHEN is_helpful = 1 THEN 1 ELSE 0 END) as helpful_count,
                SUM(CASE WHEN is_helpful = 0 THEN 1 ELSE 0 END) as not_helpful_count,
                ROUND((SUM(CASE WHEN is_helpful = 1 THEN 1 ELSE 0 END) / COUNT(*)) * 100, 1) as helpful_percentage,
                MAX(created_at) as last_feedback_at
            ')
            ->groupBy('page_url', 'page_title')
            ->orderByDesc('total_feedbacks')
            ->limit($limit)
            ->get();
    }

    /**
     * Genel geri bildirim istatistiklerini getirir
     * 
     * @return array
     */
    public static function getGeneralStats(): array
    {
        $stats = self::selectRaw('
                COUNT(*) as total_feedbacks,
                SUM(CASE WHEN is_helpful = 1 THEN 1 ELSE 0 END) as helpful_count,
                SUM(CASE WHEN is_helpful = 0 THEN 1 ELSE 0 END) as not_helpful_count,
                COUNT(DISTINCT page_url) as total_pages
            ')
            ->first();

        $total = $stats->total_feedbacks ?? 0;
        $helpful = $stats->helpful_count ?? 0;
        $notHelpful = $stats->not_helpful_count ?? 0;

        return [
            'total_feedbacks' => $total,
            'helpful_count' => $helpful,
            'not_helpful_count' => $notHelpful,
            'total_pages' => $stats->total_pages ?? 0,
            'helpful_percentage' => $total > 0 ? round(($helpful / $total) * 100, 1) : 0,
            'not_helpful_percentage' => $total > 0 ? round(($notHelpful / $total) * 100, 1) : 0,
        ];
    }
}
