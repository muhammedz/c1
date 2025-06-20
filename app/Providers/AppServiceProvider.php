<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use App\Models\News;
use App\Models\Page;
use App\Models\User;
use App\Models\Service;
use App\Models\Event;
use App\Models\Setting;
use App\Models\CorporateMember;
use App\Models\CorporateCategory;
use App\Models\GuidePlace;
use App\Models\GuideCategory;
use App\Models\Archive;
use App\Models\Project;
use App\Models\Slider;
use App\Models\NewsCategory;
use App\Models\ServiceCategory;
use App\Models\PageCategory;
use App\Models\EventCategory;
use App\Models\ProjectCategory;
use App\Models\NewsTag;
use App\Models\ServiceTag;
use App\Models\PageTag;
use App\Models\Mayor;
use App\Models\MayorContent;
use App\Models\Announcement;
use App\Models\Tender;
use App\Models\CankayaHouse;
use App\Models\CankayaHouseCourse;
use App\Models\MenuSystem;
use App\Models\ArchiveDocumentCategory;
use App\Models\ServiceTopic;
use App\Models\HedefKitle;
use App\Models\Category;
use App\Models\ServicesUnit;
use App\Models\Mudurluk;
use App\Models\MudurlukFile;
use App\Models\SearchSetting;
use App\Models\MenuSystemItem;
use App\Models\MenuCategory;
use App\Models\MenuItem;
use App\Models\FooterMenu;
use App\Models\ArchiveDocument;
use App\Models\NewsDocument;
use App\Models\Gallery;
use App\Models\HeaderSetting;
use App\Models\Redirect;
use App\Observers\NewsObserver;
use App\Observers\PageObserver;
use App\Observers\UserObserver;
use App\Observers\ServiceObserver;
use App\Observers\EventObserver;
use App\Observers\SettingObserver;
use App\Observers\CorporateMemberObserver;
use App\Observers\CorporateCategoryObserver;
use App\Observers\GuidePlaceObserver;
use App\Observers\GuideCategoryObserver;
use App\Observers\ArchiveObserver;
use App\Observers\ProjectObserver;
use App\Observers\SliderObserver;
use App\Observers\NewsCategoryObserver;
use App\Observers\ServiceCategoryObserver;
use App\Observers\PageCategoryObserver;
use App\Observers\EventCategoryObserver;
use App\Observers\ProjectCategoryObserver;
use App\Observers\NewsTagObserver;
use App\Observers\ServiceTagObserver;
use App\Observers\PageTagObserver;
use App\Observers\MayorObserver;
use App\Observers\MayorContentObserver;
use App\Observers\AnnouncementObserver;
use App\Observers\TenderObserver;
use App\Observers\CankayaHouseObserver;
use App\Observers\CankayaHouseCourseObserver;
use App\Observers\MenuSystemObserver;
use App\Observers\ArchiveDocumentCategoryObserver;
use App\Observers\ServiceTopicObserver;
use App\Observers\HedefKitleObserver;
use App\Observers\CategoryObserver;
use App\Observers\ServicesUnitObserver;
use App\Observers\MudurlukObserver;
use App\Observers\MudurlukFileObserver;
use App\Observers\SearchSettingObserver;
use App\Observers\MenuSystemItemObserver;
use App\Observers\MenuCategoryObserver;
use App\Observers\MenuItemObserver;
use App\Observers\FooterMenuObserver;
use App\Observers\ArchiveDocumentObserver;
use App\Observers\NewsDocumentObserver;
use App\Observers\GalleryObserver;
use App\Observers\HeaderSettingObserver;
use App\Observers\RedirectObserver;
use Illuminate\Support\Facades\Blade;
use App\Helpers\ImageHelper;
use App\Helpers\SlugHelper;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);
        
        // Observer sınıflarını kaydet
        News::observe(NewsObserver::class);
        Page::observe(PageObserver::class);
        User::observe(UserObserver::class);
        Service::observe(ServiceObserver::class);
        Event::observe(EventObserver::class);
        Setting::observe(SettingObserver::class);
        CorporateMember::observe(CorporateMemberObserver::class);
        CorporateCategory::observe(CorporateCategoryObserver::class);
        GuidePlace::observe(GuidePlaceObserver::class);
        GuideCategory::observe(GuideCategoryObserver::class);
        Archive::observe(ArchiveObserver::class);
        Project::observe(ProjectObserver::class);
        Slider::observe(SliderObserver::class);
        NewsCategory::observe(NewsCategoryObserver::class);
        ServiceCategory::observe(ServiceCategoryObserver::class);
        
        // Kategori Observer'ları
        PageCategory::observe(PageCategoryObserver::class);
        EventCategory::observe(EventCategoryObserver::class);
        ProjectCategory::observe(ProjectCategoryObserver::class);
        
        // Tag Observer'ları
        NewsTag::observe(NewsTagObserver::class);
        ServiceTag::observe(ServiceTagObserver::class);
        PageTag::observe(PageTagObserver::class);
        
        // Başkan Observer'ları
        Mayor::observe(MayorObserver::class);
        MayorContent::observe(MayorContentObserver::class);
        
        // Duyuru/İhale Observer'ları
        Announcement::observe(AnnouncementObserver::class);
        Tender::observe(TenderObserver::class);
        
        // Çankaya Evleri Observer'ları
        CankayaHouse::observe(CankayaHouseObserver::class);
        CankayaHouseCourse::observe(CankayaHouseCourseObserver::class);
        
        // Menü Observer'ları
        MenuSystem::observe(MenuSystemObserver::class);
        
        // Ek Kategori Observer'ları
        ArchiveDocumentCategory::observe(ArchiveDocumentCategoryObserver::class);
        ServiceTopic::observe(ServiceTopicObserver::class);
        HedefKitle::observe(HedefKitleObserver::class);
        Category::observe(CategoryObserver::class);
        
        // Hizmet Birimleri Observer'ları
        ServicesUnit::observe(ServicesUnitObserver::class);
        
        // Müdürlük Observer'ları
        Mudurluk::observe(MudurlukObserver::class);
        MudurlukFile::observe(MudurlukFileObserver::class);
        
        // Arama Observer'ları
        SearchSetting::observe(SearchSettingObserver::class);
        
        // Ek Menü Observer'ları
        MenuSystemItem::observe(MenuSystemItemObserver::class);
        MenuCategory::observe(MenuCategoryObserver::class);
        MenuItem::observe(MenuItemObserver::class);
        FooterMenu::observe(FooterMenuObserver::class);
        
        // Belge/Medya Observer'ları
        ArchiveDocument::observe(ArchiveDocumentObserver::class);
        NewsDocument::observe(NewsDocumentObserver::class);
        Gallery::observe(GalleryObserver::class);
        
        // Ayar Observer'ları
        HeaderSetting::observe(HeaderSettingObserver::class);
        
        // Diğer Observer'lar
        Redirect::observe(RedirectObserver::class);
        
        // fixImageUrl fonksiyonunu Blade içinde kullanılabilir hale getir
        Blade::directive('fixImageUrl', function ($expression) {
            return "<?php echo App\Helpers\ImageHelper::fixImageUrl($expression); ?>";
        });
        
        // Slug helper için Blade directive'leri
        Blade::directive('slugHelper', function () {
            return "<?php echo App\Helpers\SlugHelper::getJsSlugFunction(); ?>";
        });
        
        Blade::directive('turkishCharMap', function () {
            return "<?php echo App\Helpers\SlugHelper::getTurkishCharMapForJs(); ?>";
        });

        // NOTE: Laravel File Manager kodları kaldırıldı
        // \Illuminate\Support\Facades\URL::forceRootUrl(config('app.url'));
        
        // Laravel File Manager lfm.api.response event listener kaldırıldı
        
        // File Manager için yolları düzelt
        // Uploads klasörünün varlığını kontrol et ve yoksa oluştur
        $uploadsPaths = [
            public_path('uploads'),
            public_path('uploads/photos'),
            public_path('uploads/photos/1'),
            public_path('uploads/photos/shares'),
        ];
        
        foreach ($uploadsPaths as $path) {
            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            } else {
                chmod($path, 0777);
            }
        }

        // JSON_UNESCAPED_UNICODE ekle - Türkçe karakterlerin düzgün görüntülenmesi için
        JsonResource::withoutWrapping();
        
        // JSON kodlama ayarlarını düzenle
        config(['app.json_encoding_options' => JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES]);

        // AdminLTE sayfalarına favicon bilgisini gönder
        View::composer(['adminlte::page', 'adminlte::master'], function ($view) {
            try {
                $siteFavicon = Setting::where('key', 'site_favicon')->first();
                $view->with('siteFavicon', $siteFavicon);
                
                // Eğer özel favicon varsa, AdminLTE'nin varsayılan favicon'unu devre dışı bırak
                if ($siteFavicon && $siteFavicon->value) {
                    $view->with('customFaviconUrl', asset('uploads/' . $siteFavicon->value));
                }
            } catch (\Exception $e) {
                $view->with('siteFavicon', null);
                $view->with('customFaviconUrl', null);
            }
        });

        // Frontend sayfalarına da favicon bilgisini gönder
        View::composer('layouts.front', function ($view) {
            try {
                $siteFavicon = Setting::where('key', 'site_favicon')->first();
                $view->with('siteFavicon', $siteFavicon);
            } catch (\Exception $e) {
                $view->with('siteFavicon', null);
            }
        });
    }
}
