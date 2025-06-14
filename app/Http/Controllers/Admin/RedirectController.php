<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Redirect;
use App\Models\NotFoundLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;

class RedirectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Redirect::with('creator');

        // Filtreleme
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('from_url', 'like', '%' . $request->search . '%')
                  ->orWhere('to_url', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        if ($request->filled('redirect_type')) {
            $query->where('redirect_type', $request->redirect_type);
        }

        // Sıralama
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        
        $allowedSorts = ['from_url', 'to_url', 'redirect_type', 'hit_count', 'is_active', 'created_at'];
        if (in_array($sortBy, $allowedSorts)) {
            $query->orderBy($sortBy, $sortOrder);
        }

        $redirects = $query->paginate(20)->withQueryString();

        // İstatistikler
        $stats = [
            'total_redirects' => Redirect::count(),
            'active_redirects' => Redirect::active()->count(),
            'total_hits' => Redirect::sum('hit_count'),
            'top_redirects' => Redirect::active()->orderBy('hit_count', 'desc')->limit(5)->get(),
        ];

        return view('admin.redirects.index', compact('redirects', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $fromUrl = $request->get('from_url', '');
        return view('admin.redirects.create', compact('fromUrl'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'from_url' => 'required|string|max:500|unique:redirects,from_url',
            'to_url' => 'required|string|max:500',
            'redirect_type' => 'required|in:301,302',
            'description' => 'nullable|string|max:1000',
        ], [
            'from_url.required' => 'Kaynak URL alanı zorunludur.',
            'from_url.unique' => 'Bu URL için zaten bir yönlendirme kuralı mevcut.',
            'to_url.required' => 'Hedef URL alanı zorunludur.',
            'redirect_type.required' => 'Yönlendirme tipi seçilmelidir.',
        ]);

        $redirect = Redirect::create([
            'from_url' => $request->from_url,
            'to_url' => $request->to_url,
            'redirect_type' => $request->redirect_type,
            'description' => $request->description,
            'created_by' => Auth::id(),
        ]);

        // İlgili 404 log'u çözüldü olarak işaretle
        NotFoundLog::where('url', $redirect->from_url)->update(['is_resolved' => true]);

        // Cache temizle
        Cache::forget('redirect_' . md5($redirect->from_url));

        return redirect()->route('admin.redirects.index')
            ->with('success', 'Yönlendirme kuralı başarıyla oluşturuldu.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Redirect $redirect)
    {
        $redirect->load('creator');
        
        // Son 30 günlük hit geçmişi
        $hitHistory = collect(); // Basit implementasyon için boş, gelişmiş versiyonda log tablosu eklenebilir
        
        return view('admin.redirects.show', compact('redirect', 'hitHistory'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Redirect $redirect)
    {
        return view('admin.redirects.edit', compact('redirect'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Redirect $redirect)
    {
        $request->validate([
            'from_url' => 'required|string|max:500|unique:redirects,from_url,' . $redirect->id,
            'to_url' => 'required|string|max:500',
            'redirect_type' => 'required|in:301,302',
            'description' => 'nullable|string|max:1000',
        ], [
            'from_url.required' => 'Kaynak URL alanı zorunludur.',
            'from_url.unique' => 'Bu URL için zaten bir yönlendirme kuralı mevcut.',
            'to_url.required' => 'Hedef URL alanı zorunludur.',
            'redirect_type.required' => 'Yönlendirme tipi seçilmelidir.',
        ]);

        // Eski cache'i temizle
        Cache::forget('redirect_' . md5($redirect->from_url));

        $redirect->update([
            'from_url' => $request->from_url,
            'to_url' => $request->to_url,
            'redirect_type' => $request->redirect_type,
            'description' => $request->description,
        ]);

        // Yeni cache'i temizle
        Cache::forget('redirect_' . md5($redirect->from_url));

        return redirect()->route('admin.redirects.index')
            ->with('success', 'Yönlendirme kuralı başarıyla güncellendi.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Redirect $redirect)
    {
        // Cache temizle
        Cache::forget('redirect_' . md5($redirect->from_url));
        
        $redirect->delete();

        return response()->json([
            'success' => true,
            'message' => 'Yönlendirme kuralı silindi.'
        ]);
    }

    /**
     * Aktif/pasif durumunu değiştir
     */
    public function toggle(Redirect $redirect)
    {
        $redirect->toggleStatus();
        
        // Cache temizle
        Cache::forget('redirect_' . md5($redirect->from_url));

        $status = $redirect->is_active ? 'aktif' : 'pasif';
        
        return response()->json([
            'success' => true,
            'message' => "Yönlendirme kuralı {$status} duruma getirildi.",
            'is_active' => $redirect->is_active
        ]);
    }

    /**
     * Yönlendirmeyi test et
     */
    public function test(Redirect $redirect)
    {
        return response()->json([
            'success' => true,
            'from_url' => $redirect->from_url,
            'to_url' => $redirect->to_url,
            'redirect_type' => $redirect->redirect_type,
            'test_url' => url($redirect->from_url)
        ]);
    }

    /**
     * Toplu silme
     */
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:redirects,id'
        ]);

        $redirects = Redirect::whereIn('id', $request->ids)->get();
        
        // Cache temizle
        foreach ($redirects as $redirect) {
            Cache::forget('redirect_' . md5($redirect->from_url));
        }

        Redirect::whereIn('id', $request->ids)->delete();

        return response()->json([
            'success' => true,
            'message' => count($request->ids) . ' adet yönlendirme kuralı silindi.'
        ]);
    }

    /**
     * Toplu aktif/pasif
     */
    public function bulkToggle(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:redirects,id',
            'status' => 'required|boolean'
        ]);

        $redirects = Redirect::whereIn('id', $request->ids)->get();
        
        // Cache temizle
        foreach ($redirects as $redirect) {
            Cache::forget('redirect_' . md5($redirect->from_url));
        }

        Redirect::whereIn('id', $request->ids)->update(['is_active' => $request->status]);

        $statusText = $request->status ? 'aktif' : 'pasif';
        
        return response()->json([
            'success' => true,
            'message' => count($request->ids) . " adet yönlendirme kuralı {$statusText} duruma getirildi."
        ]);
    }
}
