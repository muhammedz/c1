<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PageFeedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Admin Paneli Sayfa Geri Bildirim Controller
 * 
 * Bu controller admin panelinde geri bildirim istatistiklerini gösterir
 * ve geri bildirim yönetimi işlemlerini gerçekleştirir.
 */
class PageFeedbackController extends Controller
{
    /**
     * Geri bildirim istatistikleri ana sayfası
     * 
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // Genel istatistikler
        $generalStats = PageFeedback::getGeneralStats();
        
        // Sayfa bazlı istatistikler (sayfalama ile)
        $perPage = $request->get('per_page', 20);
        $search = $request->get('search');
        
        $query = PageFeedback::selectRaw('
                page_url,
                page_title,
                COUNT(*) as total_feedbacks,
                SUM(CASE WHEN is_helpful = 1 THEN 1 ELSE 0 END) as helpful_count,
                SUM(CASE WHEN is_helpful = 0 THEN 1 ELSE 0 END) as not_helpful_count,
                ROUND((SUM(CASE WHEN is_helpful = 1 THEN 1 ELSE 0 END) / COUNT(*)) * 100, 1) as helpful_percentage,
                MAX(created_at) as last_feedback_at
            ')
            ->groupBy('page_url', 'page_title');
        
        // Arama filtresi
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('page_title', 'like', '%' . $search . '%')
                  ->orWhere('page_url', 'like', '%' . $search . '%');
            });
        }
        
        // Sıralama
        $sortBy = $request->get('sort_by', 'total_feedbacks');
        $sortDirection = $request->get('sort_direction', 'desc');
        
        $query->orderBy($sortBy, $sortDirection);
        
        $pageStats = $query->paginate($perPage);
        
        // Son geri bildirimler
        $recentFeedbacks = PageFeedback::with([])
            ->select('page_title', 'page_url', 'is_helpful', 'created_at', 'user_ip')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        
        // Aylık trend verileri (son 6 ay)
        $monthlyTrends = PageFeedback::selectRaw('
                DATE_FORMAT(created_at, "%Y-%m") as month,
                COUNT(*) as total_feedbacks,
                SUM(CASE WHEN is_helpful = 1 THEN 1 ELSE 0 END) as helpful_count,
                SUM(CASE WHEN is_helpful = 0 THEN 1 ELSE 0 END) as not_helpful_count
            ')
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->get();
        
        return view('admin.page-feedback.index', compact(
            'generalStats',
            'pageStats',
            'recentFeedbacks',
            'monthlyTrends'
        ));
    }
    
    /**
     * Belirli bir sayfa için detaylı geri bildirim bilgileri
     * 
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function show(Request $request)
    {
        $pageUrl = $request->get('page_url');
        
        if (!$pageUrl) {
            return redirect()->route('admin.page-feedback.index')
                ->with('error', 'Sayfa URL\'si belirtilmedi.');
        }
        
        // Sayfa istatistikleri
        $pageStats = PageFeedback::getPageStats($pageUrl);
        
        // Sayfa başlığını al
        $pageTitle = PageFeedback::where('page_url', $pageUrl)->value('page_title');
        
        // Sayfa için geri bildirimler (sayfalama ile)
        $feedbacks = PageFeedback::where('page_url', $pageUrl)
            ->orderBy('created_at', 'desc')
            ->paginate(50);
        
        // Günlük trend verileri (son 30 gün)
        $dailyTrends = PageFeedback::selectRaw('
                DATE(created_at) as date,
                COUNT(*) as total_feedbacks,
                SUM(CASE WHEN is_helpful = 1 THEN 1 ELSE 0 END) as helpful_count,
                SUM(CASE WHEN is_helpful = 0 THEN 1 ELSE 0 END) as not_helpful_count
            ')
            ->where('page_url', $pageUrl)
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();
        
        return view('admin.page-feedback.show', compact(
            'pageUrl',
            'pageTitle',
            'pageStats',
            'feedbacks',
            'dailyTrends'
        ));
    }
    
    /**
     * Geri bildirim silme işlemi
     * 
     * @param PageFeedback $feedback
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(PageFeedback $feedback)
    {
        try {
            $feedback->delete();
            
            return redirect()->back()
                ->with('success', 'Geri bildirim başarıyla silindi.');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Geri bildirim silinirken bir hata oluştu.');
        }
    }
    
    /**
     * Belirli bir sayfanın tüm geri bildirimlerini silme
     * 
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroyPageFeedbacks(Request $request)
    {
        $pageUrl = $request->get('page_url');
        
        if (!$pageUrl) {
            return redirect()->back()
                ->with('error', 'Sayfa URL\'si belirtilmedi.');
        }
        
        try {
            $deletedCount = PageFeedback::where('page_url', $pageUrl)->delete();
            
            return redirect()->route('admin.page-feedback.index')
                ->with('success', "Sayfa için {$deletedCount} geri bildirim silindi.");
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Geri bildirimler silinirken bir hata oluştu.');
        }
    }
    
    /**
     * Geri bildirim istatistiklerini JSON formatında döner (AJAX için)
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getStats(Request $request)
    {
        $timeRange = $request->get('time_range', '30'); // Varsayılan 30 gün
        
        $stats = PageFeedback::selectRaw('
                COUNT(*) as total_feedbacks,
                SUM(CASE WHEN is_helpful = 1 THEN 1 ELSE 0 END) as helpful_count,
                SUM(CASE WHEN is_helpful = 0 THEN 1 ELSE 0 END) as not_helpful_count,
                COUNT(DISTINCT page_url) as total_pages
            ')
            ->when($timeRange !== 'all', function($query) use ($timeRange) {
                $query->where('created_at', '>=', now()->subDays($timeRange));
            })
            ->first();
        
        $total = $stats->total_feedbacks ?? 0;
        $helpful = $stats->helpful_count ?? 0;
        $notHelpful = $stats->not_helpful_count ?? 0;
        
        return response()->json([
            'total_feedbacks' => $total,
            'helpful_count' => $helpful,
            'not_helpful_count' => $notHelpful,
            'total_pages' => $stats->total_pages ?? 0,
            'helpful_percentage' => $total > 0 ? round(($helpful / $total) * 100, 1) : 0,
            'not_helpful_percentage' => $total > 0 ? round(($notHelpful / $total) * 100, 1) : 0,
        ]);
    }
    
    /**
     * Geri bildirim verilerini Excel formatında dışa aktarır
     * 
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function export(Request $request)
    {
        $pageUrl = $request->get('page_url');
        
        $query = PageFeedback::query();
        
        if ($pageUrl) {
            $query->where('page_url', $pageUrl);
        }
        
        $feedbacks = $query->orderBy('created_at', 'desc')->get();
        
        $filename = 'sayfa-geri-bildirimler-' . date('Y-m-d') . '.csv';
        
        return response()->streamDownload(function () use ($feedbacks) {
            $output = fopen('php://output', 'w');
            
            // UTF-8 BOM for Excel compatibility
            fwrite($output, "\xEF\xBB\xBF");
            
            // CSV başlıkları
            fputcsv($output, [
                'Sayfa Başlığı',
                'Sayfa URL\'si',
                'Geri Bildirim',
                'Kullanıcı IP',
                'Tarayıcı Bilgisi',
                'Tarih'
            ]);
            
            // Veri satırları
            foreach ($feedbacks as $feedback) {
                fputcsv($output, [
                    $feedback->page_title,
                    $feedback->page_url,
                    $feedback->is_helpful ? 'Yardımcı oldu' : 'Yardımcı olmadı',
                    $feedback->user_ip,
                    $feedback->user_agent,
                    $feedback->created_at->format('d.m.Y H:i')
                ]);
            }
            
            fclose($output);
        }, $filename, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }
}
