<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ActivityLogController extends Controller
{
    /**
     * Display a listing of the activity logs.
     */
    public function index(Request $request)
    {
        $query = ActivityLog::with('user')->orderBy('created_at', 'desc');

        // Kullanıcı filtresi
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Model türü filtresi
        if ($request->filled('model_type')) {
            $query->where('model_type', $request->model_type);
        }

        // İşlem türü filtresi
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        // Tarih aralığı filtresi
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Arama filtresi
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('description', 'LIKE', "%{$search}%")
                  ->orWhere('user_name', 'LIKE', "%{$search}%")
                  ->orWhere('model_type', 'LIKE', "%{$search}%");
            });
        }

        $activityLogs = $query->paginate(20)->withQueryString();

        // Filtre seçenekleri için veri
        $users = User::select('id', 'name')->orderBy('name')->get();
        $modelTypes = ActivityLog::select('model_type')
            ->distinct()
            ->orderBy('model_type')
            ->pluck('model_type');
        $actions = ActivityLog::select('action')
            ->distinct()
            ->orderBy('action')
            ->pluck('action');

        return view('admin.activity-logs.index', compact(
            'activityLogs',
            'users',
            'modelTypes',
            'actions'
        ));
    }

    /**
     * Display the specified activity log.
     */
    public function show(ActivityLog $activityLog)
    {
        $activityLog->load('user');
        
        return view('admin.activity-logs.show', compact('activityLog'));
    }

    /**
     * Get activity logs statistics.
     */
    public function stats(Request $request)
    {
        $days = $request->get('days', 7);
        $startDate = Carbon::now()->subDays($days);

        $stats = [
            'total_activities' => ActivityLog::where('created_at', '>=', $startDate)->count(),
            'unique_users' => ActivityLog::where('created_at', '>=', $startDate)
                ->whereNotNull('user_id')
                ->distinct('user_id')
                ->count(),
            'most_active_user' => $this->getMostActiveUser($startDate),
            'activities_by_action' => $this->getActivitiesByAction($startDate),
            'activities_by_model' => $this->getActivitiesByModel($startDate),
            'daily_activities' => $this->getDailyActivities($startDate),
        ];

        return response()->json($stats);
    }

    /**
     * Get most active user.
     */
    private function getMostActiveUser($startDate)
    {
        return ActivityLog::with('user')
            ->select('user_id', \DB::raw('count(*) as activity_count'))
            ->where('created_at', '>=', $startDate)
            ->whereNotNull('user_id')
            ->groupBy('user_id')
            ->orderByDesc('activity_count')
            ->first();
    }

    /**
     * Get activities grouped by action.
     */
    private function getActivitiesByAction($startDate)
    {
        return ActivityLog::select('action', \DB::raw('count(*) as count'))
            ->where('created_at', '>=', $startDate)
            ->groupBy('action')
            ->orderByDesc('count')
            ->get();
    }

    /**
     * Get activities grouped by model type.
     */
    private function getActivitiesByModel($startDate)
    {
        return ActivityLog::select('model_type', \DB::raw('count(*) as count'))
            ->where('created_at', '>=', $startDate)
            ->groupBy('model_type')
            ->orderByDesc('count')
            ->get();
    }

    /**
     * Get daily activities.
     */
    private function getDailyActivities($startDate)
    {
        return ActivityLog::select(
                \DB::raw('DATE(created_at) as date'),
                \DB::raw('count(*) as count')
            )
            ->where('created_at', '>=', $startDate)
            ->groupBy(\DB::raw('DATE(created_at)'))
            ->orderBy('date')
            ->get();
    }


}
