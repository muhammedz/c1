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
        
        // LogoPlanSettings - URL'leri temizleyerek al
        $logoPlans = \App\Models\LogoPlanSettings::first() ?? new \App\Models\LogoPlanSettings();
        $this->cleanLogoPlansUrls($logoPlans);
        
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
            
            try {
                $upcomingEvents = Event::active()
                    ->orderBy('start_date', 'asc')
                    ->limit($limit)
                    ->get();
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

    /**
     * LogoPlanSettings için URL'leri temizler
     * 
     * @param \App\Models\LogoPlanSettings $logoPlans
     * @return void
     */
    protected function cleanLogoPlansUrls($logoPlans)
    {
        if (!$logoPlans) return;
        
        // Logo imaj URL'sini temizle
        if ($logoPlans->logo_image) {
            $logoPlans->logo_image = $this->cleanImageUrl($logoPlans->logo_image);
        }
        
        // Kart 2 imaj URL'sini temizle
        if ($logoPlans->card2_image) {
            $logoPlans->card2_image = $this->cleanImageUrl($logoPlans->card2_image);
        }
    }
    
    /**
     * Bir imaj URL'sini temizler
     * 
     * @param string $imageUrl
     * @return string
     */
    protected function cleanImageUrl($imageUrl)
    {
        if (empty($imageUrl)) return $imageUrl;
        
        // URL türünü kontrol et
        if (filter_var($imageUrl, FILTER_VALIDATE_URL)) {
            // Eğer tam URL ise
            $urlParts = parse_url($imageUrl);
            $pathName = $urlParts['path'] ?? '';
            
            // /storage/ kısmını da çıkar (eğer varsa)
            if (strpos($pathName, '/storage/') !== false) {
                $cleanPath = explode('/storage/', $pathName)[1] ?? $pathName;
                // uploads/ ile başlayacak şekilde düzenle
                return 'uploads/' . ltrim($cleanPath, '/');
            } else {
                // Eğer /storage/ yoksa, / ile başlayan path'i temizle
                $cleanPath = ltrim($pathName, '/');
                // uploads/ ile başlamıyorsa ekle
                return strpos($cleanPath, 'uploads/') === 0 ? $cleanPath : 'uploads/' . $cleanPath;
            }
        } else if (strpos($imageUrl, '/storage/') === 0) {
            // Sadece /storage/ ile başlıyorsa, bu kısmı çıkar ve uploads/ ekle
            $cleanPath = substr($imageUrl, strlen('/storage/'));
            return 'uploads/' . ltrim($cleanPath, '/');
        } else if (strpos($imageUrl, 'storage/') === 0) {
            // storage/ ile başlıyorsa, bu kısmı çıkar ve uploads/ ekle
            $cleanPath = substr($imageUrl, strlen('storage/'));
            return 'uploads/' . ltrim($cleanPath, '/');
        }
        
        // Eğer zaten uploads/ ile başlıyorsa olduğu gibi bırak
        if (strpos($imageUrl, 'uploads/') === 0) {
            return $imageUrl;
        }
        
        // Diğer durumlarda uploads/ ekle
        return 'uploads/' . ltrim($imageUrl, '/');
    }

    /**
     * Başkan sayfasını gösterir
     *
     * @return \Illuminate\View\View
     */
    public function baskan()
    {
        $mayor = \App\Models\Mayor::getActive();
        
        if (!$mayor) {
            // Eğer aktif başkan yoksa 404
            abort(404, 'Başkan bilgisi bulunamadı.');
        }

        // İçerikleri çek
        $stories = $mayor->stories;
        $agenda = $mayor->agenda;
        $values = $mayor->values;
        $gallery = $mayor->gallery;

        // ID 15 numaralı kategoriden başkan haberlerini çek
        $mayorNews = \App\Models\News::whereHas('categories', function($query) {
                $query->where('news_categories.id', 15);
            })
            ->where('status', 'published')
            ->orderBy('published_at', 'desc')
            ->take(4) // Son 4 haber
            ->get();

        return view('frontend.corporate.baskan', compact('mayor', 'stories', 'agenda', 'values', 'gallery', 'mayorNews'));
    }

    /**
     * İletişim sayfasını gösterir
     */
    public function iletisim()
    {
        return view('front.iletisim');
    }
} 