<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\CankayaHouse;
use App\Models\CankayaHouseCourse;
use Illuminate\Http\Request;

class CankayaHouseController extends Controller
{
    /**
     * Display a listing of Çankaya Houses.
     */
    public function index(Request $request)
    {
        $searchTerm = $request->get('search');
        
        // Eğer arama varsa, arama sonuçları sayfasını göster
        if ($searchTerm) {
            return $this->search($request);
        }

        $cankayaHouses = CankayaHouse::active()
                                   ->with(['activeCourses' => function($query) {
                                       $query->upcoming()->take(3);
                                   }])
                                   ->withCount('activeCourses')
                                   ->ordered()
                                   ->get();

        return view('front.cankaya-houses.index', compact('cankayaHouses'));
    }

    /**
     * Çankaya Evleri ve kursları arama
     */
    public function search(Request $request)
    {
        $searchTerm = $request->get('search');
        $results = [];
        $total = 0;

        if ($searchTerm) {
            // Çankaya Evlerini ara
            $houses = CankayaHouse::active()
                                 ->where(function($q) use ($searchTerm) {
                                     $q->where('name', 'like', '%' . $searchTerm . '%')
                                       ->orWhere('address', 'like', '%' . $searchTerm . '%')
                                       ->orWhere('description', 'like', '%' . $searchTerm . '%');
                                 })
                                 ->withCount('activeCourses')
                                 ->ordered()
                                 ->get();

            // Kursları ara (tüm kurslar)
            $courses = CankayaHouseCourse::with('cankayaHouse')
                                       ->active()
                                       ->where(function($q) use ($searchTerm) {
                                           $q->where('name', 'like', '%' . $searchTerm . '%')
                                             ->orWhere('description', 'like', '%' . $searchTerm . '%')
                                             ->orWhere('instructor', 'like', '%' . $searchTerm . '%');
                                       })
                                       ->orderByRaw('start_date IS NULL, start_date DESC')
                                       ->get();

            $results = [
                'houses' => $houses,
                'courses' => $courses,
                'total' => $houses->count() + $courses->count()
            ];
            $total = $results['total'];
        }

        return view('front.cankaya-houses.search', compact('searchTerm', 'results', 'total'));
    }

    /**
     * Display the specified Çankaya House.
     */
    public function show(CankayaHouse $cankayaHouse)
    {
        // Sadece aktif evleri göster
        if ($cankayaHouse->status !== 'active') {
            abort(404);
        }

        // Kursları kategorilere ayır
        $upcomingCourses = $cankayaHouse->courses()
                                       ->active()
                                       ->upcoming()
                                       ->orderBy('start_date', 'asc')
                                       ->get();

        $ongoingCourses = $cankayaHouse->courses()
                                      ->active()
                                      ->ongoing()
                                      ->orderBy('start_date', 'asc')
                                      ->get();

        $completedCourses = $cankayaHouse->courses()
                                        ->completed()
                                        ->orderBy('end_date', 'desc')
                                        ->take(5)
                                        ->get();

        // Diğer Çankaya evleri
        $otherHouses = CankayaHouse::active()
                                  ->where('id', '!=', $cankayaHouse->id)
                                  ->withCount('courses')
                                  ->ordered()
                                  ->get();

        return view('front.cankaya-houses.show', compact(
            'cankayaHouse',
            'upcomingCourses',
            'ongoingCourses',
            'completedCourses',
            'otherHouses'
        ));
    }

    /**
     * Get recent courses for homepage or other components.
     */
    public function getRecentCourses($limit = 6)
    {
        return CankayaHouseCourse::with('cankayaHouse')
                                ->active()
                                ->whereNotNull('start_date')
                                ->whereNotNull('end_date')
                                ->recent()
                                ->take($limit)
                                ->get();
    }
}
