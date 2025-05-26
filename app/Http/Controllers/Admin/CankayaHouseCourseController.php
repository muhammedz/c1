<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CankayaHouse;
use App\Models\CankayaHouseCourse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CankayaHouseCourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = CankayaHouseCourse::with('cankayaHouse');

        // Çankaya evi filtresi
        if ($request->filled('cankaya_house_id')) {
            $query->where('cankaya_house_id', $request->cankaya_house_id);
        }

        // Durum filtresi
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Arama filtresi
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('instructor', 'like', "%{$search}%");
            });
        }

        // Tarih filtresi
        if ($request->filled('date_filter')) {
            switch ($request->date_filter) {
                case 'upcoming':
                    $query->upcoming();
                    break;
                case 'ongoing':
                    $query->ongoing();
                    break;
                case 'completed':
                    $query->completed();
                    break;
            }
        }

        $courses = $query->orderBy('start_date', 'desc')->paginate(15);
        $cankayaHouses = CankayaHouse::active()->ordered()->get();

        return view('admin.cankaya-house-courses.index', compact('courses', 'cankayaHouses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $cankayaHouses = CankayaHouse::active()->ordered()->get();
        $selectedHouseId = $request->get('cankaya_house_id');

        return view('admin.cankaya-house-courses.create', compact('cankayaHouses', 'selectedHouseId'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'cankaya_house_id' => 'required|exists:cankaya_houses,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
            'instructor' => 'nullable|string|max:255',
            'capacity' => 'nullable|integer|min:1',
            'price' => 'nullable|numeric|min:0',
            'status' => 'required|in:active,inactive,completed'
        ]);

        try {
            DB::beginTransaction();

            $course = CankayaHouseCourse::create($request->all());

            DB::commit();

            return redirect()->route('admin.cankaya-house-courses.index')
                           ->with('success', 'Kurs başarıyla oluşturuldu.');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Bir hata oluştu: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(CankayaHouseCourse $cankayaHouseCourse)
    {
        $cankayaHouseCourse->load('cankayaHouse');
        return view('admin.cankaya-house-courses.show', compact('cankayaHouseCourse'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CankayaHouseCourse $cankayaHouseCourse)
    {
        $cankayaHouses = CankayaHouse::active()->ordered()->get();
        return view('admin.cankaya-house-courses.edit', compact('cankayaHouseCourse', 'cankayaHouses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CankayaHouseCourse $cankayaHouseCourse)
    {
        $request->validate([
            'cankaya_house_id' => 'required|exists:cankaya_houses,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'instructor' => 'nullable|string|max:255',
            'capacity' => 'nullable|integer|min:1',
            'price' => 'nullable|numeric|min:0',
            'status' => 'required|in:active,inactive,completed'
        ]);

        try {
            DB::beginTransaction();

            $cankayaHouseCourse->update($request->all());

            DB::commit();

            return redirect()->route('admin.cankaya-house-courses.index')
                           ->with('success', 'Kurs başarıyla güncellendi.');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Bir hata oluştu: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CankayaHouseCourse $cankayaHouseCourse)
    {
        try {
            DB::beginTransaction();

            $cankayaHouseCourse->delete();

            DB::commit();

            return redirect()->route('admin.cankaya-house-courses.index')
                           ->with('success', 'Kurs başarıyla silindi.');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                           ->with('error', 'Bir hata oluştu: ' . $e->getMessage());
        }
    }

    /**
     * Toggle status of the specified resource.
     */
    public function toggleStatus(CankayaHouseCourse $cankayaHouseCourse)
    {
        $newStatus = match($cankayaHouseCourse->status) {
            'active' => 'inactive',
            'inactive' => 'active',
            'completed' => 'active',
            default => 'active'
        };

        $cankayaHouseCourse->update(['status' => $newStatus]);

        return redirect()->back()
                       ->with('success', 'Durum başarıyla güncellendi.');
    }

    /**
     * Get courses for a specific Çankaya House (AJAX)
     */
    public function getCoursesByHouse(Request $request, CankayaHouse $cankayaHouse)
    {
        $courses = $cankayaHouse->courses()
                               ->active()
                               ->orderBy('start_date', 'desc')
                               ->get();

        return response()->json($courses);
    }
}
