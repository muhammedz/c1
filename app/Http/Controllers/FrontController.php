<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\News;
use App\Models\ProfileSettings;
use App\Models\MobileAppSettings;
use App\Models\Project;
use App\Models\ProjectCategory;
use App\Models\ProjectSetting;
use App\Models\QuickMenuCategory;
use App\Models\Event;
use App\Models\EventSettings;

class FrontController extends Controller
{
    /**
     * Show homepage
     */
    public function index()
    {
        $headlines = News::getHeadlines();
        $normalNews = News::getNormalNews();
        $profileSettings = ProfileSettings::first() ?? new ProfileSettings();
        $mobileAppSettings = MobileAppSettings::first() ?? new MobileAppSettings();
        $featuredServiceSettings = \App\Models\FeaturedServiceSetting::first() ?? new \App\Models\FeaturedServiceSetting();
        $featuredServices = \App\Models\FeaturedService::getActiveServices();
        $logoPlans = \App\Models\LogoPlanSettings::first() ?? new \App\Models\LogoPlanSettings();
        
        // Anasayfada gösterilecek projeler
        $projectSettings = ProjectSetting::getSettings();
        $projectCategories = ProjectCategory::getActiveCategories();
        $projects = Project::getHomepageProjects();
        
        // Hızlı menü kategorileri ve öğelerini getir
        $quickMenuCategories = QuickMenuCategory::active()
            ->with(['activeItems'])
            ->orderBy('order')
            ->get();
        
        // Etkinlikler bölümü için verileri getir
        $eventSettings = EventSettings::first() ?? new EventSettings(['is_active' => true]);
        $upcomingEvents = [];
        
        if ($eventSettings->is_active) {
            $limit = $eventSettings->homepage_limit ?? 6;
            
            // Sorunu tanılamak için alternatif sorgu kullanıyorum
            // Sorgulama yöntemini değiştirip etkinlikleri getirme işlemini log'layarak
            \Illuminate\Support\Facades\Log::info('Etkinlikler getiriliyor', [
                'settings' => $eventSettings->toArray()
            ]);

            try {
                // Önce tüm etkinlikleri getirelim ve ileride sıralama yapalım
                $upcomingEvents = Event::active()
                    ->orderBy('start_date', 'asc')
                    ->limit($limit)
                    ->get();

                \Illuminate\Support\Facades\Log::info('Getirilen etkinlik sayısı: ' . count($upcomingEvents));
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Etkinlik getirme hatası: ' . $e->getMessage());
                $upcomingEvents = [];
            }
        }
        
        return view('front.home', compact(
            'headlines', 
            'normalNews', 
            'profileSettings', 
            'mobileAppSettings',
            'featuredServiceSettings',
            'featuredServices',
            'logoPlans',
            'projectSettings',
            'projectCategories',
            'projects',
            'quickMenuCategories',
            'eventSettings',
            'upcomingEvents'
        ));
    }
    
    /**
     * Tüm projeler sayfası
     */
    public function projects()
    {
        $projectSettings = ProjectSetting::getSettings();
        $projectCategories = ProjectCategory::getActiveCategories();
        
        // Eğer ayarlar aktif değilse 404 döndür
        if (!$projectSettings->is_active) {
            abort(404);
        }
        
        // Tüm aktif projeleri kategorilere göre gruplandır
        $projects = Project::getAllActiveProjects()->groupBy('category_id');
        
        return view('front.projects', compact('projectSettings', 'projectCategories', 'projects'));
    }
    
    /**
     * Proje detay sayfası
     */
    public function projectDetail($slug)
    {
        $projectSettings = ProjectSetting::getSettings();
        
        // Eğer ayarlar aktif değilse 404 döndür
        if (!$projectSettings->is_active) {
            abort(404);
        }
        
        $project = Project::with(['category', 'images'])->where('slug', $slug)->where('is_active', true)->firstOrFail();
        
        // İlgili kategorideki diğer projeleri getir (kendisi hariç)
        $relatedProjects = Project::where('category_id', $project->category_id)
            ->where('id', '!=', $project->id)
            ->where('is_active', true)
            ->orderBy('order')
            ->limit(3)
            ->get();
        
        return view('front.project-detail', compact('project', 'relatedProjects', 'projectSettings'));
    }
    
    /**
     * Kategori bazlı proje listesi
     */
    public function projectCategory($slug)
    {
        $projectSettings = ProjectSetting::getSettings();
        $categories = ProjectCategory::getActiveCategories();
        
        // Eğer ayarlar aktif değilse 404 döndür
        if (!$projectSettings->is_active) {
            abort(404);
        }
        
        $category = ProjectCategory::where('slug', $slug)->where('is_active', true)->firstOrFail();
        $projects = Project::where('category_id', $category->id)
            ->where('is_active', true)
            ->orderBy('order')
            ->paginate($projectSettings->items_per_page);
        
        return view('front.project-category', compact('category', 'categories', 'projects', 'projectSettings'));
    }
} 