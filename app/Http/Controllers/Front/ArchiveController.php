<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Archive;
use Illuminate\Http\Request;

class ArchiveController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Archive::published()->with(['user', 'documents']);

        // Arama
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->search($search);
        }

        // Öne çıkan arşivler önce
        $query->orderBy('is_featured', 'desc')
              ->orderBy('published_at', 'desc');

        $archives = $query->paginate(12);

        return view('front.archives.index', compact('archives'));
    }

    /**
     * Display the specified resource.
     */
    public function show($slug)
    {
        $archive = Archive::where('slug', $slug)
                         ->published()
                         ->with(['user', 'documents'])
                         ->firstOrFail();

        // Görüntülenme sayısını artır
        $archive->incrementViewCount();

        return view('front.archives.show', compact('archive'));
    }
} 