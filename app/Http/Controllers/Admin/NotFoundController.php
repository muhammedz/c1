<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NotFoundLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class NotFoundController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = NotFoundLog::query();

        // Filtreleme
        if ($request->filled('search')) {
            $query->where('url', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('status')) {
            if ($request->status === 'resolved') {
                $query->where('is_resolved', true);
            } elseif ($request->status === 'unresolved') {
                $query->where('is_resolved', false);
            }
        }

        if ($request->filled('date_from')) {
            $query->where('last_seen_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where('last_seen_at', '<=', $request->date_to . ' 23:59:59');
        }

        // Sıralama
        $sortBy = $request->get('sort_by', 'last_seen_at');
        $sortOrder = $request->get('sort_order', 'desc');
        
        $allowedSorts = ['url', 'hit_count', 'first_seen_at', 'last_seen_at', 'is_resolved'];
        if (in_array($sortBy, $allowedSorts)) {
            $query->orderBy($sortBy, $sortOrder);
        }

        $logs = $query->paginate(20)->withQueryString();

        // İstatistikler
        $stats = [
            'total_404s' => NotFoundLog::count(),
            'unresolved_404s' => NotFoundLog::unresolved()->count(),
            'today_404s' => NotFoundLog::whereDate('last_seen_at', today())->count(),
            'top_404s' => NotFoundLog::unresolved()->topHits(5)->get(),
        ];

        return view('admin.404-logs.index', compact('logs', 'stats'));
    }

    /**
     * Display the specified resource.
     */
    public function show(NotFoundLog $notFoundLog)
    {
        // Son 30 günlük hit geçmişi (günlük bazda)
        $hitHistory = NotFoundLog::selectRaw('DATE(last_seen_at) as date, COUNT(*) as hits')
            ->where('url', $notFoundLog->url)
            ->where('last_seen_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return view('admin.404-logs.show', compact('notFoundLog', 'hitHistory'));
    }

    /**
     * Çözüldü olarak işaretle
     */
    public function resolve(Request $request, NotFoundLog $notFoundLog)
    {
        $notFoundLog->markAsResolved();
        
        // Cache temizle
        Cache::forget('redirect_' . md5($notFoundLog->url));
        
        return response()->json([
            'success' => true,
            'message' => 'URL çözüldü olarak işaretlendi.'
        ]);
    }

    /**
     * Toplu çözüldü işaretleme
     */
    public function bulkResolve(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:404_logs,id'
        ]);

        NotFoundLog::whereIn('id', $request->ids)->update(['is_resolved' => true]);

        return response()->json([
            'success' => true,
            'message' => count($request->ids) . ' adet URL çözüldü olarak işaretlendi.'
        ]);
    }

    /**
     * Yönlendirme oluşturma sayfası
     */
    public function createRedirect(NotFoundLog $notFoundLog)
    {
        return view('admin.404-logs.create-redirect', compact('notFoundLog'));
    }

    /**
     * Silme işlemi
     */
    public function destroy(NotFoundLog $notFoundLog)
    {
        $notFoundLog->delete();

        return response()->json([
            'success' => true,
            'message' => '404 log kaydı silindi.'
        ]);
    }

    /**
     * Toplu silme
     */
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:404_logs,id'
        ]);

        NotFoundLog::whereIn('id', $request->ids)->delete();

        return response()->json([
            'success' => true,
            'message' => count($request->ids) . ' adet 404 log kaydı silindi.'
        ]);
    }

    /**
     * Dashboard widget verisi
     */
    public function dashboardWidget()
    {
        $data = [
            'today_404s' => NotFoundLog::whereDate('last_seen_at', today())->count(),
            'week_404s' => NotFoundLog::where('last_seen_at', '>=', now()->subWeek())->count(),
            'unresolved_count' => NotFoundLog::unresolved()->count(),
            'top_404s' => NotFoundLog::unresolved()->topHits(5)->get(['url', 'hit_count']),
        ];

        return response()->json($data);
    }

    /**
     * Tüm 404 kayıtlarını temizle
     */
    public function clearAll()
    {
        $totalCount = NotFoundLog::count();
        
        if ($totalCount === 0) {
            return response()->json([
                'success' => false,
                'message' => 'Temizlenecek 404 log kaydı bulunamadı.'
            ]);
        }

        NotFoundLog::truncate();

        return response()->json([
            'success' => true,
            'message' => $totalCount . ' adet 404 log kaydı başarıyla temizlendi.'
        ]);
    }
}
