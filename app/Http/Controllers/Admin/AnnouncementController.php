<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AnnouncementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $announcements = Announcement::latest()->get();
        return view('admin.announcements.index', compact('announcements'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $positions = ['top' => 'Üst', 'bottom' => 'Alt', 'left' => 'Sol', 'right' => 'Sağ'];
        $pages = [
            'all' => 'Tüm Sayfalar', 
            'home' => 'Ana Sayfa', 
            'services' => 'Hizmetler', 
            'news' => 'Haberler',
            'events' => 'Etkinlikler',
            'contact' => 'İletişim'
        ];
        return view('admin.announcements.create', compact('positions', 'pages'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|max:255',
            'content' => 'required',
            'bg_color' => 'required|max:20',
            'text_color' => 'required|max:20',
            'border_color' => 'required|max:20',
            'icon' => 'required|max:30',
            'position' => 'required|in:top,bottom,left,right',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        $data = $request->all();
        
        // display_pages işleme
        if ($request->has('display_pages')) {
            $data['display_pages'] = $request->display_pages;
        } else {
            $data['display_pages'] = ['all'];
        }
        
        Announcement::create($data);
        
        return redirect()->route('admin.announcements.index')->with('success', 'Duyuru başarıyla oluşturuldu.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $announcement = Announcement::findOrFail($id);
        $positions = ['top' => 'Üst', 'bottom' => 'Alt', 'left' => 'Sol', 'right' => 'Sağ'];
        $pages = [
            'all' => 'Tüm Sayfalar', 
            'home' => 'Ana Sayfa', 
            'services' => 'Hizmetler', 
            'news' => 'Haberler',
            'events' => 'Etkinlikler',
            'contact' => 'İletişim'
        ];
        
        return view('admin.announcements.edit', compact('announcement', 'positions', 'pages'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|max:255',
            'content' => 'required',
            'bg_color' => 'required|max:20',
            'text_color' => 'required|max:20',
            'border_color' => 'required|max:20',
            'icon' => 'required|max:30',
            'position' => 'required|in:top,bottom,left,right',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        $announcement = Announcement::findOrFail($id);
        $data = $request->all();
        
        // display_pages işleme
        if ($request->has('display_pages')) {
            $data['display_pages'] = $request->display_pages;
        } else {
            $data['display_pages'] = ['all'];
        }
        
        $announcement->update($data);
        
        return redirect()->route('admin.announcements.index')->with('success', 'Duyuru başarıyla güncellendi.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $announcement = Announcement::findOrFail($id);
        $announcement->delete();
        
        return redirect()->route('admin.announcements.index')->with('success', 'Duyuru başarıyla silindi.');
    }
    
    /**
     * Toggle active status
     */
    public function toggleActive(string $id)
    {
        $announcement = Announcement::findOrFail($id);
        $announcement->active = !$announcement->active;
        $announcement->save();
        
        return redirect()->route('admin.announcements.index')
            ->with('success', 'Duyuru durumu değiştirildi.');
    }
}
