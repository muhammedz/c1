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
    public function projects(Request $request)
    {
        $projectSettings = ProjectSetting::getSettings();
        $categories = ProjectCategory::getActiveCategories();
        $selectedCategory = null;
        
        // Eğer ayarlar aktif değilse 404 döndür
        if (!$projectSettings->is_active) {
            abort(404);
        }
        
        try {
            // Kategori filtresi varsa
            if ($request->has('category')) {
                $categoryId = $request->input('category');
                $selectedCategory = ProjectCategory::findOrFail($categoryId);
                $projects = Project::where('is_active', true)
                    ->where('category_id', $categoryId)
                    ->orderBy('order')
                    ->paginate($projectSettings->items_per_page ?? 12);
            } else {
                // Tüm projeler
                $projects = Project::where('is_active', true)
                    ->orderBy('order')
                    ->paginate($projectSettings->items_per_page ?? 12);
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Proje getirme hatası: ' . $e->getMessage());
            // Hata durumunda boş koleksiyon döndür
            $projects = collect();
        }
        
        return view('front.projects.index', compact('projectSettings', 'categories', 'projects', 'selectedCategory'));
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
        
        try {
            $project = Project::with(['category', 'images'])->where('slug', $slug)->where('is_active', true)->firstOrFail();
            
            // İlgili kategorideki diğer projeleri getir (kendisi hariç)
            $relatedProjects = Project::where('category_id', $project->category_id)
                ->where('id', '!=', $project->id)
                ->where('is_active', true)
                ->orderBy('order')
                ->limit(3)
                ->get();
            
            return view('front.projects.detail', compact('project', 'relatedProjects', 'projectSettings'));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Proje detay hatası: ' . $e->getMessage());
            abort(404);
        }
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
        
        try {
            $category = ProjectCategory::where('slug', $slug)->where('is_active', true)->firstOrFail();
            $projects = Project::where('category_id', $category->id)
                ->where('is_active', true)
                ->orderBy('order')
                ->paginate($projectSettings->items_per_page ?? 10);
                
            return view('front.project-category', compact('category', 'categories', 'projects', 'projectSettings'));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Proje kategorisi hatası: ' . $e->getMessage());
            abort(404);
        }
    }
} 