<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventCategory;
use App\Models\EventSettings;
use Illuminate\Http\Request;

class EventController extends Controller
{
    /**
     * Etkinlikler ana sayfası
     */
    public function index(Request $request)
    {
        $settings = EventSettings::first() ?? new EventSettings(['is_active' => true]);
        $categories = EventCategory::active()->orderBy('order')->get();
        
        $categorySlug = $request->query('category');
        $category = null;
        
        if ($categorySlug) {
            $category = EventCategory::where('slug', $categorySlug)->first();
        }
        
        $eventsQuery = Event::active();
        
        if ($category) {
            $eventsQuery->where('category_id', $category->id);
        }
        
        // Geçmiş etkinlikleri dahil et veya etme
        if (!$settings->show_past_events) {
            $eventsQuery->where(function($q) {
                $q->where('start_date', '>=', now())
                  ->orWhere(function($q2) {
                      $q2->whereNotNull('end_date')
                         ->where('end_date', '>=', now());
                  });
            });
        }
        
        $events = $eventsQuery->orderBy('start_date', 'asc')->paginate(12);
        
        return view('front.events.index', compact('events', 'categories', 'category', 'settings'));
    }
    
    /**
     * Etkinlik detay sayfası
     */
    public function show($slug)
    {
        $event = Event::with(['category', 'images'])->where('slug', $slug)->firstOrFail();
        $settings = EventSettings::first() ?? new EventSettings(['is_active' => true]);
        
        // İlgili etkinlikler - aynı kategoriden ya da yakın tarihli
        $relatedEvents = Event::active()
            ->where('id', '!=', $event->id)
            ->where(function($query) use ($event) {
                $query->where('category_id', $event->category_id)
                      ->orWhereBetween('start_date', [
                          $event->start_date->subDays(30),
                          $event->start_date->addDays(30)
                      ]);
            })
            ->limit(3)
            ->get();
        
        return view('front.events.show', compact('event', 'relatedEvents', 'settings'));
    }
    
    /**
     * Kategori sayfası
     */
    public function category($slug)
    {
        $category = EventCategory::where('slug', $slug)->firstOrFail();
        $settings = EventSettings::first() ?? new EventSettings(['is_active' => true]);
        
        $eventsQuery = Event::active()->where('category_id', $category->id);
        
        // Geçmiş etkinlikleri dahil et veya etme
        if (!$settings->show_past_events) {
            $eventsQuery->where('start_date', '>=', now());
        }
        
        $events = $eventsQuery->orderBy('start_date', 'asc')->paginate(12);
        $categories = EventCategory::active()->orderBy('order')->get();
        
        return view('front.events.category', compact('category', 'events', 'categories', 'settings'));
    }
    
    /**
     * Takvim görünümü
     */
    public function calendar()
    {
        $settings = EventSettings::first() ?? new EventSettings(['is_active' => true]);
        $events = Event::active()->get();
        
        // Takvim için event verileri formatlanır
        $calendarEvents = [];
        foreach ($events as $event) {
            $calendarEvents[] = [
                'id' => $event->id,
                'title' => $event->title,
                'start' => $event->start_date->format('Y-m-d H:i:s'),
                'end' => $event->end_date ? $event->end_date->format('Y-m-d H:i:s') : null,
                'url' => route('events.show', $event->slug),
                'color' => $event->category ? $event->category->color : '#3490dc'
            ];
        }
        
        return view('front.events.calendar', compact('calendarEvents', 'settings'));
    }
}
