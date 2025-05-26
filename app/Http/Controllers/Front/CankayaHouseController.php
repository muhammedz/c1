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
    public function index()
    {
        $cankayaHouses = CankayaHouse::active()
                                   ->with(['activeCourses' => function($query) {
                                       $query->upcoming()->take(3);
                                   }])
                                   ->withCount('activeCourses')
                                   ->ordered()
                                   ->get();

        // Son eklenen kurslar
        $recentCourses = CankayaHouseCourse::with('cankayaHouse')
                                         ->active()
                                         ->recent()
                                         ->take(6)
                                         ->get();

        return view('front.cankaya-houses.index', compact('cankayaHouses', 'recentCourses'));
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
                                ->recent()
                                ->take($limit)
                                ->get();
    }
}
