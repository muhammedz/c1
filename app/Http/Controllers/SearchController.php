<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\News;

class SearchController extends Controller
{
    /**
     * Arama sayfasını görüntüle
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // Basit bir test yanıtı
        return "Arama sayfası çalışıyor!";
        
        /* Orijinal kod
        $query = $request->input('q');
        $results = [];
        
        if ($query) {
            // Önce Hizmetleri Ara
            $services = Service::search($query)
                ->where('status', 'published')
                ->get();
                
            // Sonra Haberleri Ara
            $news = News::search($query)
                ->where('status', 'published')
                ->get();
                
            $results = [
                'services' => $services,
                'news' => $news,
                'total' => $services->count() + $news->count()
            ];
        }
        
        return view('search.index', [
            'query' => $query,
            'results' => $results
        ]);
        */
    }
} 