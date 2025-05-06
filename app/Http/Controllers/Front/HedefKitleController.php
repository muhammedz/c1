<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\HedefKitle;
use App\Models\News;
use Illuminate\Http\Request;

class HedefKitleController extends Controller
{
    /**
     * Tüm hedef kitleleri listele
     */
    public function index(Request $request)
    {
        $hedefKitleler = HedefKitle::where('is_active', true)
            ->orderBy('order')
            ->get();

        return view('front.hedef-kitleler.index', compact('hedefKitleler'));
    }

    /**
     * Belirli bir hedef kitleye ait haberleri listele
     */
    public function show($slug)
    {
        $hedefKitle = HedefKitle::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $news = News::whereHas('hedefKitleler', function($query) use ($hedefKitle) {
                $query->where('hedef_kitleler.id', $hedefKitle->id);
            })
            ->with(['category', 'categories'])
            ->where('status', 'published')
            ->orderBy('published_at', 'desc')
            ->paginate(8);
            
        // İlişkili hizmetleri getir
        $services = \App\Models\Service::whereHas('hedefKitleler', function($query) use ($hedefKitle) {
                $query->where('hedef_kitleler.id', $hedefKitle->id);
            })
            ->where('status', 'published')
            ->orderBy('created_at', 'desc')
            ->paginate(8);

        return view('front.hedef-kitleler.show', compact('hedefKitle', 'news', 'services'));
    }
} 