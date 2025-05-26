<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tender;

class TenderController extends Controller
{
    /**
     * İhalelerin listesini göster
     */
    public function index(Request $request)
    {
        $query = Tender::query();
        
        // Durum filtresi
        $status = $request->get('status', 'active');
        if ($status && in_array($status, ['active', 'completed', 'cancelled'])) {
            $query->where('status', $status);
        } else {
            $query->where('status', 'active'); // Varsayılan olarak aktif ihaleler
        }
        
        // Arama filtresi
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                  ->orWhere('summary', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%");
            });
        }
        
        // Birim filtresi
        if ($request->filled('unit')) {
            $query->where('unit', $request->get('unit'));
        }
        
        // İhale türü filtresi (başlığa göre)
        if ($request->filled('tender_type')) {
            $tenderType = $request->get('tender_type');
            $query->where('title', 'LIKE', "%{$tenderType}%");
        }
        
        // Tarih aralığı filtresi
        if ($request->filled('start_date')) {
            $query->whereDate('tender_datetime', '>=', $request->get('start_date'));
        }
        
        if ($request->filled('end_date')) {
            $query->whereDate('tender_datetime', '<=', $request->get('end_date'));
        }
        
        // Sıralama
        $query->orderBy('tender_datetime', 'desc');
        
        // Sayfalama
        $tenders = $query->paginate(15);
        
        // Birim listesi (filtreleme için)
        $units = Tender::distinct()->pluck('unit')->filter()->sort()->values();
        
        return view('front.tenders.index', compact('tenders', 'units'));
    }
    
    /**
     * Tamamlanmış ihaleleri göster
     */
    public function completed(Request $request)
    {
        $request->merge(['status' => 'completed']);
        return $this->index($request);
    }
    
    /**
     * İptal edilmiş ihaleleri göster
     */
    public function cancelled(Request $request)
    {
        $request->merge(['status' => 'cancelled']);
        return $this->index($request);
    }

    /**
     * İhalelerin detayını göster
     */
    public function show($slug)
    {
        $tender = Tender::where('slug', $slug)->firstOrFail();
        
        return view('front.tenders.show', compact('tender'));
    }
}
