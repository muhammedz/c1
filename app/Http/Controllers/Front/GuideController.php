<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\GuideCategory;
use App\Models\GuidePlace;
use Illuminate\Http\Request;

class GuideController extends Controller
{
    /**
     * Display guide categories
     */
    public function index(Request $request)
    {
        $query = GuideCategory::active()->with(['activePlaces' => function($query) {
            $query->take(6)->with('images');
        }]);
        
        // Arama filtresi
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }
        
        $categories = $query->ordered()->paginate(12);
        
        // SEO bilgileri
        $pageTitle = 'Rehber';
        $pageDescription = 'Çankaya Belediyesi rehber kategorileri ve yer bilgileri';
        
        return view('front.guide.index', compact('categories', 'pageTitle', 'pageDescription'));
    }

    /**
     * Display places in a category
     */
    public function category(GuideCategory $category, Request $request)
    {
        if (!$category->is_active) {
            abort(404);
        }
        
        $query = $category->activePlaces()->with('images');
        
        // Arama filtresi
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%");
            });
        }
        
        $places = $query->ordered()->paginate(12);
        
        // SEO bilgileri
        $pageTitle = $category->meta_title ?: $category->name;
        $pageDescription = $category->meta_description ?: $category->description;
        
        return view('front.guide.category', compact('category', 'places', 'pageTitle', 'pageDescription'));
    }

    /**
     * Display a specific place
     */
    public function place(GuideCategory $category, GuidePlace $place)
    {
        if (!$category->is_active || !$place->is_active || $place->guide_category_id !== $category->id) {
            abort(404);
        }
        
        $place->load(['images' => function($query) {
            $query->ordered();
        }]);
        
        // İlgili yerler (aynı kategoriden)
        $relatedPlaces = GuidePlace::where('guide_category_id', $category->id)
            ->where('id', '!=', $place->id)
            ->active()
            ->with('images')
            ->ordered()
            ->take(6)
            ->get();
        
        // SEO bilgileri
        $pageTitle = $place->meta_title ?: $place->title;
        $pageDescription = $place->meta_description ?: $place->excerpt;
        
        // Breadcrumb
        $breadcrumbs = [
            ['title' => 'Ana Sayfa', 'url' => route('home')],
            ['title' => 'Rehber', 'url' => route('guide.index')],
            ['title' => $category->name, 'url' => route('guide.category', $category->slug)],
            ['title' => $place->title, 'url' => null]
        ];
        
        return view('front.guide.place', compact(
            'category', 
            'place', 
            'relatedPlaces', 
            'pageTitle', 
            'pageDescription', 
            'breadcrumbs'
        ));
    }

    /**
     * Search places
     */
    public function search(Request $request)
    {
        $request->validate([
            'q' => 'required|string|min:2|max:100'
        ]);
        
        $query = $request->q;
        
        $places = GuidePlace::active()
            ->with(['category', 'images'])
            ->where(function($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                  ->orWhere('content', 'like', "%{$query}%")
                  ->orWhere('address', 'like', "%{$query}%");
            })
            ->ordered()
            ->paginate(12);
        
        $categories = GuideCategory::active()
            ->where(function($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('description', 'like', "%{$query}%");
            })
            ->ordered()
            ->get();
        
        // SEO bilgileri
        $pageTitle = "'{$query}' için arama sonuçları";
        $pageDescription = "Rehber içerisinde '{$query}' araması sonuçları";
        
        return view('front.guide.search', compact(
            'places', 
            'categories', 
            'query', 
            'pageTitle', 
            'pageDescription'
        ));
    }

    /**
     * Get places by coordinates (AJAX)
     */
    public function getPlacesByLocation(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'radius' => 'nullable|numeric|min:1|max:50' // km cinsinden
        ]);
        
        $latitude = $request->latitude;
        $longitude = $request->longitude;
        $radius = $request->radius ?? 10; // Varsayılan 10km
        
        // Haversine formülü ile mesafe hesaplama
        $places = GuidePlace::active()
            ->with(['category', 'images'])
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->selectRaw("
                *,
                (6371 * acos(cos(radians(?)) * cos(radians(latitude)) * cos(radians(longitude) - radians(?)) + sin(radians(?)) * sin(radians(latitude)))) AS distance
            ", [$latitude, $longitude, $latitude])
            ->having('distance', '<=', $radius)
            ->orderBy('distance')
            ->take(20)
            ->get();
        
        return response()->json([
            'success' => true,
            'places' => $places->map(function($place) {
                return [
                    'id' => $place->id,
                    'title' => $place->title,
                    'category' => $place->category->name,
                    'address' => $place->address,
                    'phone' => $place->phone,
                    'latitude' => $place->latitude,
                    'longitude' => $place->longitude,
                    'distance' => round($place->distance, 2),
                    'url' => route('guide.place', [$place->category->slug, $place->slug]),
                    'image' => $place->featured_image_url
                ];
            })
        ]);
    }

    /**
     * Get category statistics (AJAX)
     */
    public function getCategoryStats()
    {
        $stats = GuideCategory::active()
            ->withCount('activePlaces')
            ->ordered()
            ->get()
            ->map(function($category) {
                return [
                    'id' => $category->id,
                    'name' => $category->name,
                    'slug' => $category->slug,
                    'icon' => $category->icon,
                    'places_count' => $category->active_places_count,
                    'url' => route('guide.category', $category->slug)
                ];
            });
        
        return response()->json([
            'success' => true,
            'categories' => $stats
        ]);
    }
}
