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

        // Şimdilik kategoriler boş olarak tanımlıyoruz
        // Gelecekte kategori sistemi eklendiğinde burası güncellenecek
        $categories = collect();
        $uncategorizedArchives = $archives;

        return view('front.archives.index', compact('archives', 'categories', 'uncategorizedArchives'));
    }

    /**
     * Display the specified resource.
     */
    public function show($slug)
    {
        $archive = Archive::where('slug', $slug)
                         ->published()
                         ->with(['user', 'documents.category', 'documentCategories'])
                         ->firstOrFail();

        // Görüntülenme sayısını artır
        $archive->incrementViewCount();

        // Belgeleri kategorilere göre gruplandır
        $categorizedDocuments = [];
        $uncategorizedDocuments = [];

        foreach ($archive->documents as $document) {
            if ($document->category) {
                $categoryId = $document->category->id;
                if (!isset($categorizedDocuments[$categoryId])) {
                    $categorizedDocuments[$categoryId] = [
                        'category' => $document->category,
                        'documents' => []
                    ];
                }
                $categorizedDocuments[$categoryId]['documents'][] = $document;
            } else {
                $uncategorizedDocuments[] = $document;
            }
        }

        // Kategorileri sıraya göre sırala
        uasort($categorizedDocuments, function($a, $b) {
            return $a['category']->order <=> $b['category']->order;
        });

        return view('front.archives.show', compact('archive', 'categorizedDocuments', 'uncategorizedDocuments'));
    }
} 