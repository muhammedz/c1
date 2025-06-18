<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\NewsController;
use App\Http\Controllers\Admin\NewsCategoryController;
use App\Http\Controllers\Admin\NewsTagController;
use App\Http\Controllers\Admin\FileManagerController;
use App\Http\Controllers\Admin\ProjectManagerController;
use App\Http\Controllers\Admin\EventManagerController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\ServiceCategoryController;
use App\Http\Controllers\Admin\ServiceTagController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\PageCategoryController;
use App\Http\Controllers\Admin\PageTagController;
use App\Http\Controllers\Admin\PageSettingController;
use App\Http\Controllers\Admin\AnnouncementController;
use App\Http\Controllers\Admin\TestController;
use App\Http\Controllers\Admin\TestDepartmentController;
use App\Http\Controllers\Admin\HedefKitleController;
use App\Http\Controllers\Admin\ArchiveController;
use App\Http\Controllers\Admin\ArchiveDocumentController;
use App\Http\Controllers\Admin\ArchiveDocumentCategoryController;
use App\Http\Controllers\AnnouncementFrontController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TinyMCEController;
use App\Http\Controllers\Admin\ServiceSettingsController;
use App\Http\Controllers\Admin\DepartmentController;
use App\Http\Controllers\FileManagerSystem\FilemanagersystemController;
use App\Http\Controllers\FileManagerSystem\FilemanagersystemFolderController;
use App\Http\Controllers\FileManagerSystem\FilemanagersystemMediaController;
use App\Http\Controllers\FileManagerSystem\FilemanagersystemCategoryController;
use App\Http\Controllers\FileManagerSystem\MediaPickerController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\PasswordProtectionController;
use App\Http\Controllers\Auth\UpdatePasswordController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SearchPageController;
use App\Http\Controllers\Admin\ServicesUnitController;
use App\Http\Controllers\Admin\ServiceTopicController;
use App\Http\Controllers\Admin\FooterController;
use App\Http\Controllers\Admin\FooterMenuController;
use App\Http\Controllers\Admin\FooterMenuLinkController;
use App\Http\Controllers\Admin\NotFoundController;
use App\Http\Controllers\Admin\RedirectController;
use App\Http\Controllers\PharmacyController;
use Illuminate\Support\Facades\Cache;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Arama route'u - SearchService kullanarak
Route::get('/arama', function() {
    $query = request()->input('q');
    
    // Yeni SearchService'i kullan
    $searchService = new \App\Services\SearchService();
    $results = $searchService->search($query ?? '');
    
    // Arama yapıldıysa loglama
    if (!empty($query)) {
        \App\Models\SearchLog::logSearch($query, $results['total'] ?? 0);
    }
    
    return view('search.index', [
        'query' => $query,
        'results' => $results
    ]);
})->name('search');

Route::get('/', [App\Http\Controllers\FrontController::class, 'index'])->name('front.home');

// İletişim Sayfası
Route::get('/iletisim', [App\Http\Controllers\FrontController::class, 'iletisim'])->name('front.iletisim');

// Ön Yüz Haber Rotaları
Route::prefix('haberler')->name('news.')->group(function () {
    Route::get('/', [App\Http\Controllers\Front\NewsController::class, 'index'])->name('index');
    Route::get('/kategori/{slug}', [App\Http\Controllers\Front\NewsController::class, 'category'])->name('category');
    Route::get('/{newsSlug}/belgeler/{document}/indir', [App\Http\Controllers\Front\NewsController::class, 'downloadDocument'])->name('documents.download');
    Route::get('/{slug}', [App\Http\Controllers\Front\NewsController::class, 'show'])->name('show');
});

// Başkan Sayfası (Özel rotaları wildcard rotalardan önce tanımlıyoruz)
Route::get('/baskan', [App\Http\Controllers\FrontController::class, 'baskan'])->name('front.baskan');

// Ön Yüz Hedef Kitle Rotaları
Route::prefix('hedef-kitleler')->name('hedefkitleler.')->group(function () {
    Route::get('/', [App\Http\Controllers\Front\HedefKitleController::class, 'index'])->name('index');
    Route::get('/{slug}', [App\Http\Controllers\Front\HedefKitleController::class, 'show'])->name('show');
});

// İhaleler için frontend rotaları - Wildcard route'lardan önce tanımlanmalı
Route::prefix('ihaleler')->name('tenders.')->group(function () {
    Route::get('/', [App\Http\Controllers\Front\TenderController::class, 'index'])->name('index');
    Route::get('/tamamlananlar', [App\Http\Controllers\Front\TenderController::class, 'completed'])->name('completed');
    Route::get('/iptal-edilenler', [App\Http\Controllers\Front\TenderController::class, 'cancelled'])->name('cancelled');
    Route::get('/{slug}', [App\Http\Controllers\Front\TenderController::class, 'show'])->name('show');
});

// Çankaya Evleri Frontend Route'ları
Route::prefix('cankaya-evleri')->name('cankaya-houses.')->group(function () {
    Route::get('/', [App\Http\Controllers\Front\CankayaHouseController::class, 'index'])->name('index');
    Route::get('/{cankayaHouse}', [App\Http\Controllers\Front\CankayaHouseController::class, 'show'])->name('show');
});

// Kurumsal Kadro Frontend Route'ları
Route::get('/kurumsal-kadro', [App\Http\Controllers\CorporateController::class, 'index'])->name('corporate.index');

// Kurumsal Kadro Kategori ve Üye Rotaları - Güvenli prefix ile
Route::prefix('kurumsal')->name('corporate.')->group(function () {
    Route::get('/{categorySlug}/{memberSlug}', [App\Http\Controllers\CorporateController::class, 'showMember'])->name('member');
    Route::get('/{categorySlug}', [App\Http\Controllers\CorporateController::class, 'showCategory'])->name('category');
});

// Proje Detay Sayfaları
Route::get('/projeler', [App\Http\Controllers\FrontController::class, 'projects'])->name('front.projects');
Route::get('/projeler/{slug}', [App\Http\Controllers\FrontController::class, 'projectDetail'])->name('front.projects.detail');
Route::get('/projeler-kategori/{slug}', [App\Http\Controllers\FrontController::class, 'projectCategory'])->name('front.projects.category');

// Ön Yüz Arşiv Rotaları
Route::prefix('arsivler')->name('archives.')->group(function () {
    Route::get('/', [App\Http\Controllers\Front\ArchiveController::class, 'index'])->name('index');
    Route::get('/{slug}', [App\Http\Controllers\Front\ArchiveController::class, 'show'])->name('show');
});

// Ön Yüz Rehber Rotaları
Route::prefix('rehber')->name('guide.')->group(function () {
    Route::get('/', [App\Http\Controllers\Front\GuideController::class, 'index'])->name('index');
    Route::get('/arama', [App\Http\Controllers\Front\GuideController::class, 'search'])->name('search');
    Route::get('/konum', [App\Http\Controllers\Front\GuideController::class, 'getPlacesByLocation'])->name('location');
    Route::get('/istatistikler', [App\Http\Controllers\Front\GuideController::class, 'getCategoryStats'])->name('stats');
    Route::get('/{category:slug}', [App\Http\Controllers\Front\GuideController::class, 'category'])->name('category');
    Route::get('/{category:slug}/{place:slug}', [App\Http\Controllers\Front\GuideController::class, 'place'])->name('place');
});

// Nöbetçi Eczaneler Sayfası
Route::get('/nobetci-eczaneler', [PharmacyController::class, 'index'])->name('pharmacy.index');

// Geçici cache temizleme route'u
Route::get('/nobetci-eczaneler/cache-temizle-gizli', function() {
    Cache::flush(); // Tüm cache'i temizle
    return "Cache temizlendi! Artık /nobetci-eczaneler sayfasına gidebilirsin.";
});

// Side Menu API - Mobil menü için
Route::get('/api/menu-items/{menuId}', [App\Http\Controllers\Admin\MenuSystemController::class, 'getMenuItemsForSideMenu'])->name('api.menu-items');

Auth::routes();

// Arama Rotası - Yeni spesifik isimle - şimdilik devre dışı
// Route::get('/search-page', [App\Http\Controllers\SearchController::class, 'index'])->name('search');

// Arama Test Rotası - şimdilik devre dışı
// Route::get('/arama-test', [App\Http\Controllers\SearchPageController::class, 'index'])->name('search.test');



// Admin Panel Route Tanımlamaları
Route::middleware(['auth', 'role:admin'])->name('admin.')->prefix('admin')->group(function () {
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [DashboardController::class, 'profile'])->name('profile');
    Route::put('/profile', [DashboardController::class, 'updateProfile'])->name('profile.update');
    
    // Test Sayfası
    Route::get('/test', [TestController::class, 'index'])->name('test.index');
    Route::get('/test-departments', [TestDepartmentController::class, 'index'])->name('test-departments.index');
    
    // Arşivler Yönetimi
    Route::resource('archives', ArchiveController::class);
    Route::post('archives/bulk-action', [ArchiveController::class, 'bulkAction'])->name('archives.bulk-action');
    Route::post('archives/{archive}/restore', [ArchiveController::class, 'restore'])->name('archives.restore');
    Route::delete('archives/{archive}/force-delete', [ArchiveController::class, 'forceDelete'])->name('archives.force-delete');
    
    // Arşiv Belgeleri Yönetimi
    Route::post('archives/{archive}/documents', [ArchiveDocumentController::class, 'store'])->name('archives.documents.store');
    Route::post('archives/{archive}/documents/bulk-store', [ArchiveDocumentController::class, 'bulkStore'])->name('archives.documents.bulk-store');
    Route::post('archives/{archive}/documents/bulk-delete', [ArchiveDocumentController::class, 'bulkDelete'])->name('archives.documents.bulk-delete');
    Route::put('archive-documents/{document}', [ArchiveDocumentController::class, 'update'])->name('archive-documents.update');
    Route::delete('archive-documents/{document}', [ArchiveDocumentController::class, 'destroy'])->name('archive-documents.destroy');
    Route::post('archives/{archive}/documents/sort', [ArchiveDocumentController::class, 'updateSortOrder'])->name('archives.documents.sort');
    Route::post('archive-documents/{document}/update-sort', [ArchiveDocumentController::class, 'updateSort'])->name('archive-documents.update-sort');
    Route::post('archive-documents/{document}/toggle-status', [ArchiveDocumentController::class, 'toggleStatus'])->name('archive-documents.toggle-status');

    // Archive Document Categories
    Route::resource('archive-document-categories', ArchiveDocumentCategoryController::class);
    Route::get('archives/{archive}/categories', [ArchiveDocumentCategoryController::class, 'getArchiveCategories'])->name('archives.categories');
    Route::post('archive-document-categories/update-order', [ArchiveDocumentCategoryController::class, 'updateOrder'])->name('archive-document-categories.update-order');
    
    // İhaleler Yönetimi
    Route::resource('tenders', App\Http\Controllers\Admin\TenderController::class);
    Route::post('tenders/{id}/toggle-status', [App\Http\Controllers\Admin\TenderController::class, 'toggleStatus'])->name('tenders.toggle-status');
    

    
    // Sayfa Ayarları
    Route::get('pages/settings', [PageSettingController::class, 'edit'])->name('pages.settings.edit');
    Route::put('pages/settings', [PageSettingController::class, 'update'])->name('pages.settings.update');
    
    // Sayfa Yönetimi
    Route::resource('pages', App\Http\Controllers\Admin\PageController::class);
    Route::post('pages/{page}/toggle-featured', [App\Http\Controllers\Admin\PageController::class, 'toggleFeatured'])->name('pages.toggle-featured');
    Route::post('pages/{page}/toggle-status', [App\Http\Controllers\Admin\PageController::class, 'toggleStatus'])->name('pages.toggle-status');
    Route::post('pages/update-featured-order', [App\Http\Controllers\Admin\PageController::class, 'updateFeaturedOrder'])->name('pages.update-featured-order');
    Route::post('pages/upload-gallery-image', [App\Http\Controllers\Admin\PageController::class, 'uploadGalleryImage'])->name('pages.upload-gallery-image');
    
    // TinyMCE için resim yükleme
    Route::post('tinymce/upload', [TinyMCEController::class, 'upload'])->name('tinymce.upload');
    
    // Haberler Yönetimi
    Route::resource('news', NewsController::class);
    Route::post('/news/{news}/toggle-headline', [NewsController::class, 'toggleHeadline'])->name('news.toggle-headline');
    Route::post('/news/{news}/toggle-featured', [NewsController::class, 'toggleFeatured'])->name('news.toggle-featured');
    Route::post('/news/{news}/toggle-status', [NewsController::class, 'toggleStatus'])->name('news.toggle-status');
    Route::post('/news/update-headline-order', [NewsController::class, 'updateHeadlineOrder'])->name('news.update-headline-order');
    Route::get('/news/{news}/toggle-archive', [NewsController::class, 'toggleArchive'])->name('news.toggle-archive');
    Route::post('/news/upload-gallery-image', [NewsController::class, 'uploadGalleryImage'])->name('news.upload-gallery-image');
    Route::post('/news/bulk-action', [NewsController::class, 'bulkAction'])->name('news.bulk-action');
    
    // Haber Belgeleri Yönetimi
    Route::post('/news/{news}/documents', [App\Http\Controllers\Admin\NewsDocumentController::class, 'store'])->name('news.documents.store');
    Route::post('/news/{news}/documents/bulk', [App\Http\Controllers\Admin\NewsDocumentController::class, 'bulkStore'])->name('news.documents.bulk-store');
    Route::put('/news/{news}/documents/{document}', [App\Http\Controllers\Admin\NewsDocumentController::class, 'update'])->name('news.documents.update');
    Route::delete('/news/{news}/documents/{document}', [App\Http\Controllers\Admin\NewsDocumentController::class, 'destroy'])->name('news.documents.destroy');
    Route::delete('/news/{news}/documents', [App\Http\Controllers\Admin\NewsDocumentController::class, 'bulkDestroy'])->name('news.documents.bulk-destroy');
    Route::get('/news/{news}/documents/{document}/download', [App\Http\Controllers\Admin\NewsDocumentController::class, 'download'])->name('news.documents.download');
    
    // Kategori Yönetimi
    Route::resource('news-categories', NewsCategoryController::class)->names('news-categories');
    Route::post('/news-categories/update-order', [NewsCategoryController::class, 'updateOrder'])->name('news-categories.update-order');
    
    // Etiket Yönetimi
    Route::resource('news-tags', NewsTagController::class)->names('news-tags');
    Route::get('/news-tags/cleanup', [NewsTagController::class, 'cleanup'])->name('news-tags.cleanup');
    Route::get('/news-tags/search', [NewsTagController::class, 'search'])->name('news-tags.search');
    
    // Hedef Kitle Yönetimi
    Route::resource('hedef-kitleler', HedefKitleController::class)->parameters(['hedef-kitleler' => 'hedefKitle'])->names('hedef-kitleler');
    Route::post('/hedef-kitleler/update-order', [HedefKitleController::class, 'updateOrder'])->name('hedef-kitleler.update-order');
    Route::get('/hedef-kitleler/{hedefKitle}/get-news', [HedefKitleController::class, 'getNews'])->name('hedef-kitleler.get-news');
    
    // Kullanıcı Yönetimi
    Route::resource('users', UserController::class);
    
    // Rol Yönetimi
    Route::resource('roles', RoleController::class);
    
    // Anasayfa Yönetimi
    Route::get('/homepage', [App\Http\Controllers\Admin\HomepageManagerController::class, 'index'])->name('homepage.index');
    Route::get('/homepage/profile-info', [App\Http\Controllers\Admin\HomepageManagerController::class, 'profileInfo'])->name('homepage.profile-info');
    Route::post('/homepage/profile-info', [App\Http\Controllers\Admin\HomepageManagerController::class, 'updateProfileInfo'])->name('homepage.update-profile-info');
    Route::get('/homepage/mobile-app', [App\Http\Controllers\Admin\HomepageManagerController::class, 'mobileApp'])->name('homepage.mobile-app');
    Route::post('/homepage/mobile-app', [App\Http\Controllers\Admin\HomepageManagerController::class, 'updateMobileApp'])->name('homepage.update-mobile-app');
    Route::post('/homepage/mobile-app/toggle-visibility', [App\Http\Controllers\Admin\HomepageManagerController::class, 'toggleMobileAppVisibility'])->name('homepage.toggle-mobile-app-visibility');
    
    // Logo ve Planlar Yönetimi
    Route::get('/homepage/logo-and-plans', [App\Http\Controllers\Admin\HomepageManagerController::class, 'logoAndPlans'])->name('homepage.logo-and-plans');
    Route::post('/homepage/logo-and-plans', [App\Http\Controllers\Admin\HomepageManagerController::class, 'updateLogoAndPlans'])->name('homepage.update-logo-and-plans');
    Route::post('/homepage/logo-and-plans/toggle-visibility', [App\Http\Controllers\Admin\HomepageManagerController::class, 'toggleLogoAndPlansVisibility'])->name('homepage.toggle-logo-and-plans-visibility');
    
    // Öne Çıkan Hizmetler Yönetimi
    Route::get('/homepage/featured-services', [App\Http\Controllers\Admin\HomepageManagerController::class, 'featuredServices'])->name('homepage.featured-services');
    Route::post('/homepage/featured-services/settings', [App\Http\Controllers\Admin\HomepageManagerController::class, 'updateFeaturedServiceSettings'])->name('homepage.update-featured-service-settings');
    Route::post('/homepage/featured-services', [App\Http\Controllers\Admin\HomepageManagerController::class, 'storeFeaturedService'])->name('homepage.store-featured-service');
    Route::put('/homepage/featured-services/{id}', [App\Http\Controllers\Admin\HomepageManagerController::class, 'updateFeaturedService'])->name('homepage.update-featured-service');
    Route::delete('/homepage/featured-services/{id}', [App\Http\Controllers\Admin\HomepageManagerController::class, 'deleteFeaturedService'])->name('homepage.delete-featured-service');
    Route::post('/homepage/featured-services/{id}/toggle-visibility', [App\Http\Controllers\Admin\HomepageManagerController::class, 'toggleFeaturedServiceVisibility'])->name('homepage.toggle-featured-service-visibility');
    Route::post('/homepage/featured-services/toggle-visibility', [App\Http\Controllers\Admin\HomepageManagerController::class, 'toggleFeaturedServicesVisibility'])->name('homepage.toggle-featured-services-visibility');
    Route::post('/homepage/featured-services/update-order', [App\Http\Controllers\Admin\HomepageManagerController::class, 'updateFeaturedServicesOrder'])->name('homepage.update-featured-services-order');
    
    // Projeler Yönetimi
    Route::get('/projects', [ProjectManagerController::class, 'index'])->name('projects.index');
    Route::get('/projects/categories', [ProjectManagerController::class, 'categories'])->name('projects.categories');
    Route::get('/projects/settings', [ProjectManagerController::class, 'settings'])->name('projects.settings');
    Route::get('/projects/create', [ProjectManagerController::class, 'create'])->name('projects.create');
    Route::get('/projects/{id}/edit', [ProjectManagerController::class, 'editProject'])->name('projects.edit');
    
    // Proje Kategori İşlemleri
    Route::post('/projects/categories', [ProjectManagerController::class, 'storeCategory'])->name('projects.categories.store');
    Route::put('/projects/categories/{id}', [ProjectManagerController::class, 'updateCategory'])->name('projects.categories.update');
    Route::delete('/projects/categories/{id}', [ProjectManagerController::class, 'deleteCategory'])->name('projects.categories.delete');
    Route::post('/projects/categories/update-order', [ProjectManagerController::class, 'updateCategoryOrder'])->name('projects.categories.update-order');
    Route::post('/projects/categories/{id}/toggle-visibility', [ProjectManagerController::class, 'toggleCategoryVisibility'])->name('projects.categories.toggle-visibility');
    
    // Proje İşlemleri
    Route::post('/projects', [ProjectManagerController::class, 'store'])->name('projects.store');
    Route::get('/projects/{id}', [ProjectManagerController::class, 'show'])->name('projects.show');
    Route::put('/projects/{id}', [ProjectManagerController::class, 'updateProject'])->name('projects.update');
    Route::delete('/projects/{id}', [ProjectManagerController::class, 'delete'])->name('projects.delete');
    Route::post('/projects/update-order', [ProjectManagerController::class, 'updateOrder'])->name('projects.update-order');
    Route::post('/projects/{id}/toggle-visibility', [ProjectManagerController::class, 'toggleVisibility'])->name('projects.toggle-visibility');
    Route::post('/projects/{id}/toggle-homepage', [ProjectManagerController::class, 'toggleHomepage'])->name('projects.toggle-homepage');
    
    // Proje Ayarları
    Route::post('/projects/settings', [ProjectManagerController::class, 'updateSettings'])->name('projects.settings.update');
    Route::post('/projects/toggle-module-visibility', [ProjectManagerController::class, 'toggleModuleVisibility'])->name('projects.toggle-module-visibility');
    
    // Hizmetler Yönetimi
    Route::prefix('services')->name('services.')->group(function () {
        Route::get('/', [ServiceController::class, 'index'])->name('index');
        Route::get('/create', [ServiceController::class, 'create'])->name('create');
        Route::post('/', [ServiceController::class, 'store'])->name('store');
        Route::get('/{service}/edit', [ServiceController::class, 'edit'])->name('edit');
        Route::put('/{service}', [ServiceController::class, 'update'])->name('update');
        Route::delete('/{service}', [ServiceController::class, 'destroy'])->name('destroy');
        Route::post('/update-order', [ServiceController::class, 'updateOrder'])->name('update-order');
        Route::post('/{service}/toggle-headline', [ServiceController::class, 'toggleHeadline'])->name('toggle-headline');
        Route::post('/{service}/toggle-featured', [ServiceController::class, 'toggleFeatured'])->name('toggle-featured');
        Route::post('/{service}/toggle-archive', [ServiceController::class, 'toggleArchive'])->name('toggle-archive');
        Route::post('/{service}/toggle-status', [ServiceController::class, 'toggleStatus'])->name('toggle-status');
        Route::post('/upload-gallery-image', [ServiceController::class, 'uploadGalleryImage'])->name('upload-gallery-image');
        
        // Hizmet Ayarları
        Route::get('/services-settings', [ServiceController::class, 'settings'])->name('services-settings');
        Route::post('/services-settings', [ServiceController::class, 'updateSettings'])->name('services-settings.update');

        // Birimler route'ları
        Route::get('/units', [ServicesUnitController::class, 'index'])->name('units.index');
        Route::get('/units/create', [ServicesUnitController::class, 'create'])->name('units.create');
        Route::post('/units', [ServicesUnitController::class, 'store'])->name('units.store');
        Route::get('/units/{unit}/edit', [ServicesUnitController::class, 'edit'])->name('units.edit');
        Route::put('/units/{unit}', [ServicesUnitController::class, 'update'])->name('units.update');
        Route::delete('/units/{unit}', [ServicesUnitController::class, 'destroy'])->name('units.destroy');
        Route::post('/units/update-order', [ServicesUnitController::class, 'updateOrder'])->name('units.update-order');
    });
    
    // Müdürlükler Kategorisi Yönetimi
    Route::resource('mudurlukler-kategorisi', ServiceCategoryController::class)->names('service-categories');
    Route::post('/mudurlukler-kategorisi/update-order', [ServiceCategoryController::class, 'updateOrder'])->name('service-categories.update-order');
    
    // Hizmet Etiketleri Yönetimi
    Route::resource('service-tags', ServiceTagController::class)->names('service-tags');
    Route::get('/service-tags/cleanup', [ServiceTagController::class, 'cleanup'])->name('service-tags.cleanup');
    Route::get('/service-tags/search', [ServiceTagController::class, 'search'])->name('service-tags.search');
    
    // Hizmet Konuları Yönetimi
    Route::get('hizmet-konulari', [App\Http\Controllers\Admin\ServiceTopicController::class, 'index'])->name('service-topics.index');
    Route::get('hizmet-konulari/create', [App\Http\Controllers\Admin\ServiceTopicController::class, 'create'])->name('service-topics.create');
    Route::post('hizmet-konulari', [App\Http\Controllers\Admin\ServiceTopicController::class, 'store'])->name('service-topics.store');
    Route::get('hizmet-konulari/cleanup', [App\Http\Controllers\Admin\ServiceTopicController::class, 'cleanup'])->name('service-topics.cleanup');
    Route::get('hizmet-konulari/{serviceTopic}', [App\Http\Controllers\Admin\ServiceTopicController::class, 'show'])->name('service-topics.show');
    Route::get('hizmet-konulari/{serviceTopic}/edit', [App\Http\Controllers\Admin\ServiceTopicController::class, 'edit'])->name('service-topics.edit');
    Route::put('hizmet-konulari/{serviceTopic}', [App\Http\Controllers\Admin\ServiceTopicController::class, 'update'])->name('service-topics.update');
    Route::delete('hizmet-konulari/{serviceTopic}', [App\Http\Controllers\Admin\ServiceTopicController::class, 'destroy'])->name('service-topics.destroy');
    Route::post('/hizmet-konulari/update-order', [App\Http\Controllers\Admin\ServiceTopicController::class, 'updateOrder'])->name('service-topics.update-order');
    

    
    // Sayfa Kategorileri Yönetimi
    Route::resource('page-categories', App\Http\Controllers\Admin\PageCategoryController::class)->names('page-categories');
    Route::post('/page-categories/update-order', [App\Http\Controllers\Admin\PageCategoryController::class, 'updateOrder'])->name('page-categories.update-order');
    
    // Sayfa Etiketleri Yönetimi
    Route::resource('page-tags', App\Http\Controllers\Admin\PageTagController::class)->names('page-tags');
    Route::get('/page-tags/cleanup', [App\Http\Controllers\Admin\PageTagController::class, 'cleanup'])->name('page-tags.cleanup');
    Route::get('/page-tags/search', [App\Http\Controllers\Admin\PageTagController::class, 'search'])->name('page-tags.search');
    
    // Duyurular Yönetimi
    Route::resource('announcements', AnnouncementController::class);
    Route::post('/announcements/{id}/toggle-active', [AnnouncementController::class, 'toggleActive'])->name('announcements.toggle-active');

    // Başkan Sayfası Yönetimi
    Route::prefix('mayor')->name('mayor.')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\MayorController::class, 'index'])->name('index');
        Route::get('/{mayor}/edit', [App\Http\Controllers\Admin\MayorController::class, 'edit'])->name('edit');
        Route::put('/{mayor}', [App\Http\Controllers\Admin\MayorController::class, 'update'])->name('update');
        Route::post('/{mayor}/toggle-status', [App\Http\Controllers\Admin\MayorController::class, 'toggleStatus'])->name('toggle-status');
    });
    
    // Başkan İçerik Yönetimi
    Route::prefix('mayor-content')->name('mayor-content.')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\MayorContentController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\Admin\MayorContentController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\Admin\MayorContentController::class, 'store'])->name('store');
        Route::post('/bulk-upload', [App\Http\Controllers\Admin\MayorContentController::class, 'bulkUpload'])->name('bulk-upload');
        Route::get('/{mayorContent}', [App\Http\Controllers\Admin\MayorContentController::class, 'show'])->name('show');
        Route::get('/{mayorContent}/edit', [App\Http\Controllers\Admin\MayorContentController::class, 'edit'])->name('edit');
        Route::put('/{mayorContent}', [App\Http\Controllers\Admin\MayorContentController::class, 'update'])->name('update');
        Route::delete('/{mayorContent}', [App\Http\Controllers\Admin\MayorContentController::class, 'destroy'])->name('destroy');
        Route::post('/{mayorContent}/toggle-status', [App\Http\Controllers\Admin\MayorContentController::class, 'toggleStatus'])->name('toggle-status');
        Route::post('/update-order', [App\Http\Controllers\Admin\MayorContentController::class, 'updateOrder'])->name('update-order');
        Route::post('/bulk-action', [App\Http\Controllers\Admin\MayorContentController::class, 'bulkAction'])->name('bulk-action');
    });

    // Kurumsal Kadro Yönetimi
    Route::prefix('corporate')->name('corporate.')->group(function () {
        Route::resource('categories', App\Http\Controllers\Admin\CorporateCategoryController::class);
        Route::post('categories/update-order', [App\Http\Controllers\Admin\CorporateCategoryController::class, 'updateOrder'])->name('categories.update-order');
        
        // Üye Yönetimi
        Route::get('categories/{category}/members', [App\Http\Controllers\Admin\CorporateMemberController::class, 'index'])->name('members.index');
        Route::get('categories/{category}/members/create', [App\Http\Controllers\Admin\CorporateMemberController::class, 'create'])->name('members.create');
        Route::post('categories/{category}/members', [App\Http\Controllers\Admin\CorporateMemberController::class, 'store'])->name('members.store');
        Route::get('members/{member}/edit', [App\Http\Controllers\Admin\CorporateMemberController::class, 'edit'])->name('members.edit');
        Route::put('members/{member}', [App\Http\Controllers\Admin\CorporateMemberController::class, 'update'])->name('members.update');
        Route::delete('members/{member}', [App\Http\Controllers\Admin\CorporateMemberController::class, 'destroy'])->name('members.destroy');
        Route::post('members/order', [App\Http\Controllers\Admin\CorporateMemberController::class, 'order'])->name('members.order');
    });

    // Çankaya Evleri Yönetimi
    Route::resource('cankaya-houses', App\Http\Controllers\Admin\CankayaHouseController::class)->names('cankaya-houses');
    Route::get('cankaya-houses-all-info', [App\Http\Controllers\Admin\CankayaHouseController::class, 'allInfo'])->name('cankaya-houses.all-info');
    Route::post('cankaya-houses/{cankayaHouse}/toggle-status', [App\Http\Controllers\Admin\CankayaHouseController::class, 'toggleStatus'])->name('cankaya-houses.toggle-status');
    Route::delete('cankaya-houses/{cankayaHouse}/remove-image', [App\Http\Controllers\Admin\CankayaHouseController::class, 'removeImage'])->name('cankaya-houses.remove-image');
    
    // Çankaya Evi Kursları Yönetimi - Geçici olarak kapatıldı
    // Route::resource('cankaya-house-courses', App\Http\Controllers\Admin\CankayaHouseCourseController::class)->names('cankaya-house-courses');
    // Route::post('cankaya-house-courses/{cankayaHouseCourse}/toggle-status', [App\Http\Controllers\Admin\CankayaHouseCourseController::class, 'toggleStatus'])->name('cankaya-house-courses.toggle-status');
    // Route::get('cankaya-houses/{cankayaHouse}/courses', [App\Http\Controllers\Admin\CankayaHouseCourseController::class, 'getCoursesByHouse'])->name('cankaya-houses.courses');

    // Menü Sistemi Rotaları
    Route::prefix('menusystem')->as('menusystem.')->group(function () {
        // Ana menü routes
        Route::get('/', [App\Http\Controllers\Admin\MenuSystemController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\Admin\MenuSystemController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\Admin\MenuSystemController::class, 'store'])->name('store');
        Route::get('/{menusystem}/edit', [App\Http\Controllers\Admin\MenuSystemController::class, 'edit'])->name('edit');
        Route::put('/{menusystem}', [App\Http\Controllers\Admin\MenuSystemController::class, 'update'])->name('update');
        Route::delete('/{menusystem}', [App\Http\Controllers\Admin\MenuSystemController::class, 'destroy'])->name('destroy');
        Route::post('/order', [App\Http\Controllers\Admin\MenuSystemController::class, 'updateOrder'])->name('order');
        Route::get('/type-form', [App\Http\Controllers\Admin\MenuSystemController::class, 'getMenuTypeForm'])->name('type-form');
        Route::post('/update-status', [App\Http\Controllers\Admin\MenuSystemController::class, 'updateStatus'])->name('update-status');
        
        // Menü öğelerini görüntüleme rotası
        Route::get('/{menusystem}/items', [App\Http\Controllers\Admin\MenuSystemController::class, 'showItems'])->name('items');
        
        // Menü açıklama bilgilerini güncelleme rotası
        Route::put('/{menusystem}/footer-info', [App\Http\Controllers\Admin\MenuSystemController::class, 'updateFooterInfo'])->name('update-footer-info');
        
        // Parent items için ajax route
        Route::get('/get-parent-items', [App\Http\Controllers\Admin\MenuSystemController::class, 'getParentItems'])->name('get-parent-items');
        
        // Menu Items routes
        Route::post('/items/store', [App\Http\Controllers\Admin\MenuSystemController::class, 'storeItem'])->name('items.store');
        Route::get('/items/{item}/edit', [App\Http\Controllers\Admin\MenuSystemController::class, 'editItem'])->name('items.edit');
        Route::put('/items/{item}', [App\Http\Controllers\Admin\MenuSystemController::class, 'updateItem'])->name('items.update');
        Route::delete('/items/{item}', [App\Http\Controllers\Admin\MenuSystemController::class, 'destroyItem'])->name('items.destroy');
        Route::post('/items/order', [App\Http\Controllers\Admin\MenuSystemController::class, 'updateItemOrder'])->name('items.order');
        Route::post('/items/update-status', [App\Http\Controllers\Admin\MenuSystemController::class, 'updateItemStatus'])->name('items.update_status');
        Route::get('/items/{menu_id}', [App\Http\Controllers\Admin\MenuSystemController::class, 'getItems'])->name('items.getItems');
        
        // Menu Category routes (menusystem altında)
        Route::get('/menu-categories', [App\Http\Controllers\Admin\MenuCategoryController::class, 'index'])->name('categories.index');
        Route::get('/menu-categories/create', [App\Http\Controllers\Admin\MenuCategoryController::class, 'create'])->name('categories.create');
        Route::post('/menu-categories', [App\Http\Controllers\Admin\MenuCategoryController::class, 'store'])->name('categories.store');
        Route::get('/menu-categories/{category}/edit', [App\Http\Controllers\Admin\MenuCategoryController::class, 'edit'])->name('categories.edit');
        Route::put('/menu-categories/{category}', [App\Http\Controllers\Admin\MenuCategoryController::class, 'update'])->name('categories.update');
        Route::delete('/menu-categories/{category}', [App\Http\Controllers\Admin\MenuCategoryController::class, 'destroy'])->name('categories.destroy');
        Route::post('/menu-categories/order', [App\Http\Controllers\Admin\MenuCategoryController::class, 'updateOrder'])->name('categories.order');
        Route::post('/menu-categories/update-status', [App\Http\Controllers\Admin\MenuCategoryController::class, 'updateStatus'])->name('categories.update-status');
        
        // Menu Item routes (menusystem altında)
        Route::get('/menu-items', [App\Http\Controllers\Admin\MenuItemController::class, 'index'])->name('menu-items.index');
        Route::get('/menu-items/create', [App\Http\Controllers\Admin\MenuItemController::class, 'create'])->name('menu-items.create');
        Route::post('/menu-items', [App\Http\Controllers\Admin\MenuItemController::class, 'store'])->name('menu-items.store');
        Route::get('/menu-items/{item}/edit', [App\Http\Controllers\Admin\MenuItemController::class, 'edit'])->name('menu-items.edit');
        Route::put('/menu-items/{item}', [App\Http\Controllers\Admin\MenuItemController::class, 'update'])->name('menu-items.update');
        Route::delete('/menu-items/{item}', [App\Http\Controllers\Admin\MenuItemController::class, 'destroy'])->name('menu-items.destroy');
        Route::post('/menu-items/order', [App\Http\Controllers\Admin\MenuItemController::class, 'updateOrder'])->name('menu-items.order');
        Route::get('/menu-items/icons', [App\Http\Controllers\Admin\MenuItemController::class, 'getIcons'])->name('menu-items.icons');
    });
    
    // Footer Yönetimi
    Route::prefix('footer')->name('footer.')->group(function () {
        // Ana footer yönetimi
        Route::get('/', [App\Http\Controllers\Admin\FooterController::class, 'index'])->name('index');
        Route::post('/settings', [App\Http\Controllers\Admin\FooterController::class, 'updateSettings'])->name('settings.update');
        Route::delete('/logo', [App\Http\Controllers\Admin\FooterController::class, 'deleteLogo'])->name('logo.delete');
        
        // Footer menüleri
        Route::resource('menus', App\Http\Controllers\Admin\FooterMenuController::class);
        Route::post('menus/order', [App\Http\Controllers\Admin\FooterMenuController::class, 'updateOrder'])->name('menus.order');
        Route::post('menus/{footerMenu}/toggle', [App\Http\Controllers\Admin\FooterMenuController::class, 'toggleStatus'])->name('menus.toggle');
        
        // Footer menü linkleri
        Route::prefix('menus/{footerMenu}/links')->name('menus.links.')->group(function () {
            Route::get('/', [App\Http\Controllers\Admin\FooterMenuLinkController::class, 'index'])->name('index');
            Route::get('/create', [App\Http\Controllers\Admin\FooterMenuLinkController::class, 'create'])->name('create');
            Route::post('/', [App\Http\Controllers\Admin\FooterMenuLinkController::class, 'store'])->name('store');
            Route::get('/{link}/edit', [App\Http\Controllers\Admin\FooterMenuLinkController::class, 'edit'])->name('edit');
            Route::put('/{link}', [App\Http\Controllers\Admin\FooterMenuLinkController::class, 'update'])->name('update');
            Route::delete('/{link}', [App\Http\Controllers\Admin\FooterMenuLinkController::class, 'destroy'])->name('destroy'); // Tekrar aktif - ama destroy metodu devre dışı
            Route::post('/order', [App\Http\Controllers\Admin\FooterMenuLinkController::class, 'updateOrder'])->name('order');
            Route::post('/{link}/toggle', [App\Http\Controllers\Admin\FooterMenuLinkController::class, 'toggleStatus'])->name('toggle');
        });
    });
    
    // 404 Takip ve Yönlendirme Yönetimi
    Route::prefix('404-management')->name('404-logs.')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\NotFoundController::class, 'index'])->name('index');
        Route::get('/{notFoundLog}', [App\Http\Controllers\Admin\NotFoundController::class, 'show'])->name('show');
        Route::post('/{notFoundLog}/resolve', [App\Http\Controllers\Admin\NotFoundController::class, 'resolve'])->name('resolve');
        Route::post('/bulk-resolve', [App\Http\Controllers\Admin\NotFoundController::class, 'bulkResolve'])->name('bulk-resolve');
        Route::post('/bulk-delete', [App\Http\Controllers\Admin\NotFoundController::class, 'bulkDelete'])->name('bulk-delete');
        Route::post('/clear-all', [App\Http\Controllers\Admin\NotFoundController::class, 'clearAll'])->name('clear-all');
        Route::delete('/{notFoundLog}', [App\Http\Controllers\Admin\NotFoundController::class, 'destroy'])->name('destroy');
        Route::get('/{notFoundLog}/create-redirect', [App\Http\Controllers\Admin\NotFoundController::class, 'createRedirect'])->name('create-redirect');
        Route::get('/dashboard-widget', [App\Http\Controllers\Admin\NotFoundController::class, 'dashboardWidget'])->name('dashboard-widget');
    });
    
    // Yönlendirme Kuralları Yönetimi
    Route::resource('redirects', App\Http\Controllers\Admin\RedirectController::class);
    Route::post('redirects/{redirect}/toggle', [App\Http\Controllers\Admin\RedirectController::class, 'toggle'])->name('redirects.toggle');
    Route::post('redirects/{redirect}/test', [App\Http\Controllers\Admin\RedirectController::class, 'test'])->name('redirects.test');
    Route::post('redirects/bulk-delete', [App\Http\Controllers\Admin\RedirectController::class, 'bulkDelete'])->name('redirects.bulk-delete');
    Route::post('redirects/bulk-toggle', [App\Http\Controllers\Admin\RedirectController::class, 'bulkToggle'])->name('redirects.bulk-toggle');
});

// Etkinlik Yönetimi Rotaları
Route::prefix('admin/events')->name('admin.events.')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/', [App\Http\Controllers\Admin\EventManagerController::class, 'index'])->name('index');
    Route::get('/create', [App\Http\Controllers\Admin\EventManagerController::class, 'create'])->name('create');
    Route::post('/store', [App\Http\Controllers\Admin\EventManagerController::class, 'store'])->name('store');
    Route::get('/edit/{id}', [App\Http\Controllers\Admin\EventManagerController::class, 'edit'])->name('edit');
    Route::post('/update/{id}', [App\Http\Controllers\Admin\EventManagerController::class, 'update'])->name('update');
    Route::delete('/delete/{id}', [App\Http\Controllers\Admin\EventManagerController::class, 'delete'])->name('delete');
    
    // Toplu işlem rotası
    Route::post('/bulk-actions', [App\Http\Controllers\Admin\EventManagerController::class, 'bulkActions'])->name('bulk-actions');
    
    // Etkinlik Görünürlük ve Sıralama İşlemleri
    Route::post('/{id}/toggle-visibility', [App\Http\Controllers\Admin\EventManagerController::class, 'toggleVisibility'])->name('toggle-visibility');
    Route::post('/{id}/toggle-homepage', [App\Http\Controllers\Admin\EventManagerController::class, 'toggleHomepage'])->name('toggle-homepage');
    Route::post('/{id}/toggle-featured', [App\Http\Controllers\Admin\EventManagerController::class, 'toggleFeatured'])->name('toggle-featured');
    Route::post('/update-order', [App\Http\Controllers\Admin\EventManagerController::class, 'updateOrder'])->name('update-order');
    
    // Kategori Yönetimi
    Route::get('/categories', [App\Http\Controllers\Admin\EventManagerController::class, 'categories'])->name('categories');
    Route::post('/categories/store', [App\Http\Controllers\Admin\EventManagerController::class, 'storeCategory'])->name('categories.store');
    Route::post('/categories/update/{id}', [App\Http\Controllers\Admin\EventManagerController::class, 'updateCategory'])->name('categories.update');
    Route::delete('/categories/delete/{id}', [App\Http\Controllers\Admin\EventManagerController::class, 'deleteCategory'])->name('categories.delete');
    Route::post('/categories/{id}/toggle', [App\Http\Controllers\Admin\EventManagerController::class, 'toggleCategoryVisibility'])->name('categories.toggle');
    Route::post('/categories/order', [App\Http\Controllers\Admin\EventManagerController::class, 'updateCategoryOrder'])->name('categories.order');
    
    // Ayarlar
    Route::get('/settings', [App\Http\Controllers\Admin\EventManagerController::class, 'settings'])->name('settings');
    Route::post('/settings/update', [App\Http\Controllers\Admin\EventManagerController::class, 'updateSettings'])->name('settings.update');
    Route::post('/toggle-module-visibility', [App\Http\Controllers\Admin\EventManagerController::class, 'toggleModuleVisibility'])->name('toggle-module-visibility');
    
    // Etkinlik Veri Çekme
    Route::get('/check', [App\Http\Controllers\Admin\EventScrapeController::class, 'check'])->name('check');
    Route::post('/scrape', [App\Http\Controllers\Admin\EventScrapeController::class, 'scrape'])->name('scrape');
    Route::post('/scrape-all', [App\Http\Controllers\Admin\EventScrapeController::class, 'scrapeAll'])->name('scrape-all');
    Route::post('/preview', [App\Http\Controllers\Admin\EventScrapeController::class, 'preview'])->name('preview');
    Route::post('/add-single-event', [App\Http\Controllers\Admin\EventScrapeController::class, 'addSingleEvent'])->name('add-single-event');
});

// Ön Yüz Etkinlik Rotaları
Route::prefix('etkinlikler')->name('events.')->group(function () {
    Route::get('/', [App\Http\Controllers\EventController::class, 'index'])->name('index');
    Route::get('/takvim', [App\Http\Controllers\EventController::class, 'calendar'])->name('calendar');
    Route::get('/kategori/{slug}', [App\Http\Controllers\EventController::class, 'category'])->name('category');
    Route::get('/{slug}', [App\Http\Controllers\EventController::class, 'show'])->name('show');
});

// Ön Yüz Hizmet Rotaları
Route::prefix('hizmetler')->name('services.')->group(function () {
    Route::get('/', [App\Http\Controllers\Front\ServiceController::class, 'index'])->name('index');
    Route::get('/kategoriler', [App\Http\Controllers\ServiceTopicController::class, 'index'])->name('topics.index');
    Route::get('/kategoriler/{slug}', [App\Http\Controllers\ServiceTopicController::class, 'show'])->name('topics.show');
    Route::get('/kategori/{slug}', [App\Http\Controllers\Front\ServiceController::class, 'category'])->name('category');
    Route::get('/{slug}', [App\Http\Controllers\Front\ServiceController::class, 'show'])->name('show');
});

// Doğrudan hizmet route'u - prefix ile güvenli hale getirildi
Route::get('/hizmet/{slug}', [App\Http\Controllers\Front\ServiceController::class, 'show'])->name('hizmet');

// Ön Yüz Müdürlükler Rotaları
Route::prefix('mudurlukler')->name('mudurlukler.')->group(function () {
    Route::get('/', [App\Http\Controllers\Front\MudurlukController::class, 'index'])->name('index');
    Route::get('/{slug}', [App\Http\Controllers\Front\MudurlukController::class, 'show'])->name('show');
    Route::get('/{slug}/download/{file}', [App\Http\Controllers\Front\MudurlukController::class, 'downloadFile'])->name('download-file');
});

// Ön Yüz Sayfa Rotaları
Route::prefix('sayfalar')->name('pages.')->group(function () {
    Route::get('/', [App\Http\Controllers\Front\PageController::class, 'index'])->name('index');
    Route::get('/kategori/{slug}', [App\Http\Controllers\Front\PageController::class, 'category'])->name('category');
    Route::get('/{slug}', [App\Http\Controllers\Front\PageController::class, 'show'])->name('show');
});

// Anasayfa Yönetimi Rotaları
Route::prefix('admin/homepage')->name('admin.homepage.')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/', [App\Http\Controllers\Admin\HomepageController::class, 'index'])->name('index');
    
    // Header Yönetimi
    Route::get('/header', [App\Http\Controllers\Admin\HomepageController::class, 'header'])->name('header');
    Route::post('/header/settings/update', [App\Http\Controllers\Admin\HomepageController::class, 'updateHeaderSettings'])->name('header.settings.update');
    
    // Slider Yönetimi
    Route::get('/sliders', [App\Http\Controllers\Admin\HomepageController::class, 'sliders'])->name('sliders');
    Route::get('/sliders/create', [App\Http\Controllers\Admin\HomepageController::class, 'createSlider'])->name('sliders.create');
    Route::post('/sliders/store', [App\Http\Controllers\Admin\HomepageController::class, 'storeSlider'])->name('sliders.store');
    Route::get('/sliders/edit/{id}', [App\Http\Controllers\Admin\HomepageController::class, 'editSlider'])->name('sliders.edit');
    Route::match(['put', 'patch', 'post'], '/sliders/update/{id}', [App\Http\Controllers\Admin\HomepageController::class, 'updateSlider'])->name('sliders.update');
    Route::delete('/sliders/delete/{id}', [App\Http\Controllers\Admin\HomepageController::class, 'deleteSlider'])->name('sliders.delete');
    Route::post('/sliders/update-order', [App\Http\Controllers\Admin\HomepageController::class, 'updateSliderOrder'])->name('sliders.order');
    Route::post('/sliders/{id}/toggle', [App\Http\Controllers\Admin\HomepageController::class, 'toggleSlider'])->name('sliders.toggle');
    
    // Quick Menu Yönetimi
    Route::prefix('quick-menus')->name('quick-menus.')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\HomepageController::class, 'quickMenus'])->name('index');
        Route::get('/create', [App\Http\Controllers\Admin\HomepageController::class, 'createQuickMenu'])->name('create');
        Route::post('/store', [App\Http\Controllers\Admin\HomepageController::class, 'storeQuickMenu'])->name('store');
        Route::get('/edit/{id}', [App\Http\Controllers\Admin\HomepageController::class, 'editQuickMenu'])->name('edit');
        Route::post('/update/{id}', [App\Http\Controllers\Admin\HomepageController::class, 'updateQuickMenu'])->name('update');
        Route::delete('/delete/{id}', [App\Http\Controllers\Admin\HomepageController::class, 'deleteQuickMenu'])->name('delete');
        Route::post('/order', [App\Http\Controllers\Admin\HomepageController::class, 'updateQuickMenuOrder'])->name('order');
        Route::post('/{id}/toggle', [App\Http\Controllers\Admin\HomepageController::class, 'toggleQuickMenu'])->name('toggle');
        
        // Quick Menu Items Yönetimi
        Route::get('/{category_id}/items', [App\Http\Controllers\Admin\HomepageController::class, 'quickMenuItems'])->name('items');
        Route::get('/{category_id}/items/create', [App\Http\Controllers\Admin\HomepageController::class, 'createQuickMenuItem'])->name('items.create');
        Route::post('/{category_id}/items/store', [App\Http\Controllers\Admin\HomepageController::class, 'storeQuickMenuItem'])->name('items.store');
        Route::get('/{category_id}/items/{id}/edit', [App\Http\Controllers\Admin\HomepageController::class, 'editQuickMenuItem'])->name('items.edit');
        Route::put('/{category_id}/items/{id}/update', [App\Http\Controllers\Admin\HomepageController::class, 'updateQuickMenuItem'])->name('items.update');
        Route::delete('/{category_id}/items/{id}/delete', [App\Http\Controllers\Admin\HomepageController::class, 'deleteQuickMenuItem'])->name('items.delete');
        Route::post('/{category_id}/items/order', [App\Http\Controllers\Admin\HomepageController::class, 'updateQuickMenuItemOrder'])->name('items.order');
        Route::post('/{category_id}/items/{id}/toggle', [App\Http\Controllers\Admin\HomepageController::class, 'toggleQuickMenuItem'])->name('items.toggle');
    });
});

// Etkinlik görselleri için route (tekrar eden route kaldırıldı)
Route::get('/events/{filename}', function($filename) {
    $path = storage_path('app/public/events/' . $filename);
    if (file_exists($path)) {
        return response()->file($path);
    }
    
    return response()->json(['error' => 'Görsel bulunamadı'], 404);
})->where('filename', '.*\.(?:jpg|jpeg|png|gif|webp)$');

// Frontend Duyuru İşlemleri
Route::post('/announcements/mark-viewed', [AnnouncementFrontController::class, 'markViewed'])->name('announcements.mark-viewed');

/*
|--------------------------------------------------------------------------
| File Manager System Routes
|--------------------------------------------------------------------------
*/

Route::prefix('admin/filemanagersystem')->name('admin.filemanagersystem.')->middleware(['auth', 'role:admin'])->group(function () {
    // Ana controller
    Route::get('/', [FilemanagersystemController::class, 'index'])->name('index');
    Route::get('/picker', [FilemanagersystemController::class, 'picker'])->name('picker');
    Route::get('/search', [FilemanagersystemController::class, 'search'])->name('search');
    Route::get('/preview/{id}', [FilemanagersystemController::class, 'preview'])->name('preview');
    Route::post('/bulk-actions', [FilemanagersystemController::class, 'bulkActions'])->name('bulk-actions');
    Route::get('/settings', [FilemanagersystemController::class, 'settings'])->name('settings');
    Route::post('/settings', [FilemanagersystemController::class, 'updateSettings'])->name('settings.update');
    Route::get('/dashboard', [FilemanagersystemController::class, 'dashboard'])->name('dashboard');

    // MediaPicker Routes
    Route::get('/mediapicker', [MediaPickerController::class, 'index'])->name('mediapicker.index');
    Route::get('/mediapicker/list', [MediaPickerController::class, 'listMedia'])->name('mediapicker.list');
    Route::post('/mediapicker/upload', [MediaPickerController::class, 'upload'])
        ->middleware('filemanagersystem.upload.security')
        ->name('mediapicker.upload');
    Route::post('/mediapicker/relate', [MediaPickerController::class, 'relateMedia'])->name('mediapicker.relate');
    Route::get('/mediapicker/get-media-url', [MediaPickerController::class, 'getMediaUrl'])->name('mediapicker.get-media-url');
    Route::get('/media/preview/{id}', [MediaPickerController::class, 'mediaPreview'])->name('mediapicker.preview');

    // Klasör işlemleri
    Route::prefix('folders')->name('folders.')->group(function () {
        Route::get('/', [FilemanagersystemFolderController::class, 'index'])->name('index');
        Route::get('/create', [FilemanagersystemFolderController::class, 'create'])->name('create');
        Route::post('/', [FilemanagersystemFolderController::class, 'store'])->name('store');
        Route::get('/{id}', [FilemanagersystemFolderController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [FilemanagersystemFolderController::class, 'edit'])->name('edit');
        Route::put('/{id}', [FilemanagersystemFolderController::class, 'update'])->name('update');
        Route::delete('/{id}', [FilemanagersystemFolderController::class, 'destroy'])->name('destroy');
    });

    // Medya işlemleri
    Route::prefix('media')->name('media.')->group(function () {
        Route::get('/', [FilemanagersystemMediaController::class, 'index'])->name('index');
        Route::get('/create', [FilemanagersystemMediaController::class, 'create'])->name('create');
        Route::post('/', [FilemanagersystemMediaController::class, 'store'])
            ->middleware('filemanagersystem.upload.security')
            ->name('store');
        Route::get('/get-file-path/{id}', [FilemanagersystemMediaController::class, 'getFilePath'])->name('get-file-path');
        Route::get('/{media}', [FilemanagersystemMediaController::class, 'show'])->name('show');
        Route::get('/{media}/edit', [FilemanagersystemMediaController::class, 'edit'])->name('edit');
        Route::put('/{media}', [FilemanagersystemMediaController::class, 'update'])->name('update');
        Route::delete('/{media}', [FilemanagersystemMediaController::class, 'destroy'])->name('destroy');
        Route::get('/{media}/download', [FilemanagersystemMediaController::class, 'download'])->name('download');
    });

    // Kategori işlemleri
    Route::prefix('categories')->name('categories.')->group(function () {
        Route::get('/', [FilemanagersystemCategoryController::class, 'index'])->name('index');
        Route::get('/create', [FilemanagersystemCategoryController::class, 'create'])->name('create');
        Route::post('/', [FilemanagersystemCategoryController::class, 'store'])->name('store');
        Route::get('/{id}', [FilemanagersystemCategoryController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [FilemanagersystemCategoryController::class, 'edit'])->name('edit');
        Route::put('/{id}', [FilemanagersystemCategoryController::class, 'update'])->name('update');
        Route::delete('/{id}', [FilemanagersystemCategoryController::class, 'destroy'])->name('destroy');
    });
});

// Şifre Koruması Route'ları
Route::get('/password-protection', [App\Http\Controllers\PasswordProtectionController::class, 'show'])->name('password.protection');
Route::post('/check-site-password', [App\Http\Controllers\PasswordProtectionController::class, 'check'])->name('check.site.password');

// Şifre Değiştirme Route
Route::post('/update-password', [App\Http\Controllers\Auth\UpdatePasswordController::class, 'update'])->name('profile.password.update');

    // Admin Panel Arama Ayarları Routes
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {
    // Ana Arama Ayarları
    Route::get('/search-settings', [App\Http\Controllers\Admin\SearchSettingController::class, 'index'])->name('search-settings.index');
    Route::post('/search-settings', [App\Http\Controllers\Admin\SearchSettingController::class, 'update'])->name('search-settings.update');
    
    // Hızlı Aramalar
    Route::resource('/search-quick-links', App\Http\Controllers\Admin\SearchQuickLinkController::class)->parameters([
        'search-quick-links' => 'quickLink'
    ])->except(['show']);
    Route::post('/search-quick-links/order', [App\Http\Controllers\Admin\SearchQuickLinkController::class, 'updateOrder'])->name('search-quick-links.order');
    Route::patch('/search-quick-links/{quickLink}/toggle-active', [App\Http\Controllers\Admin\SearchQuickLinkController::class, 'toggleActive'])->name('search-quick-links.toggle-active');
    
    // Popüler Aramalar
    Route::resource('/search-popular-queries', App\Http\Controllers\Admin\SearchPopularQueryController::class)->parameters([
        'search-popular-queries' => 'popularQuery'
    ])->except(['show']);
    Route::post('/search-popular-queries/order', [App\Http\Controllers\Admin\SearchPopularQueryController::class, 'updateOrder'])->name('search-popular-queries.order');
    Route::patch('/search-popular-queries/{popularQuery}/toggle-active', [App\Http\Controllers\Admin\SearchPopularQueryController::class, 'toggleActive'])->name('search-popular-queries.toggle-active');
    Route::get('/search-icons', [App\Http\Controllers\Admin\SearchPopularQueryController::class, 'getIcons'])->name('search-icons');
    
    // Genel Ayarlar Routes
    Route::get('/settings', [App\Http\Controllers\Admin\SettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings/seo', [App\Http\Controllers\Admin\SettingsController::class, 'updateSeo'])->name('settings.seo.update');
    Route::post('/settings/favicon', [App\Http\Controllers\Admin\SettingsController::class, 'updateFavicon'])->name('settings.favicon.update');
    Route::get('/settings/favicon/delete', [App\Http\Controllers\Admin\SettingsController::class, 'deleteFavicon'])->name('settings.favicon.delete');
    
    // Rehber Yönetimi Routes
    Route::resource('guide-categories', App\Http\Controllers\Admin\GuideCategoryController::class);
    Route::post('guide-categories/{guideCategory}/toggle-status', [App\Http\Controllers\Admin\GuideCategoryController::class, 'toggleStatus'])->name('guide-categories.toggle-status');
    Route::post('guide-categories/update-order', [App\Http\Controllers\Admin\GuideCategoryController::class, 'updateOrder'])->name('guide-categories.update-order');
    
    Route::resource('guide-places', App\Http\Controllers\Admin\GuidePlaceController::class);
    Route::get('guide-places-all-info', [App\Http\Controllers\Admin\GuidePlaceController::class, 'allInfo'])->name('guide-places.all-info');
    Route::post('guide-places/{guidePlace}/toggle-status', [App\Http\Controllers\Admin\GuidePlaceController::class, 'toggleStatus'])->name('guide-places.toggle-status');
    Route::post('guide-places/{place}/images', [App\Http\Controllers\Admin\GuidePlaceController::class, 'uploadImages'])->name('guide-places.upload-images');
    Route::delete('guide-place-images/{image}', [App\Http\Controllers\Admin\GuidePlaceController::class, 'deleteImage'])->name('guide-place-images.destroy');
    Route::post('guide-places/{place}/featured-image', [App\Http\Controllers\Admin\GuidePlaceController::class, 'setFeaturedImage'])->name('guide-places.set-featured-image');
    Route::post('guide-places/{place}/image-order', [App\Http\Controllers\Admin\GuidePlaceController::class, 'updateImageOrder'])->name('guide-places.update-image-order');
    
    // Müdürlükler Yönetimi
    Route::resource('mudurlukler', App\Http\Controllers\Admin\MudurlukController::class)->parameters(['mudurlukler' => 'mudurluk']);
    Route::delete('/mudurlukler/remove-file/{file}', [App\Http\Controllers\Admin\MudurlukController::class, 'removeFile'])->name('mudurlukler.remove-file');
    Route::patch('/mudurlukler/toggle-file/{file}', [App\Http\Controllers\Admin\MudurlukController::class, 'toggleFileStatus'])->name('mudurlukler.toggle-file');
    Route::post('/mudurlukler/reorder-files', [App\Http\Controllers\Admin\MudurlukController::class, 'reorderFiles'])->name('mudurlukler.reorder-files');
    
    // Müdürlük Belgeleri Yönetimi
    Route::post('/mudurlukler/{mudurluk}/documents', [App\Http\Controllers\Admin\MudurlukController::class, 'uploadDocument'])->name('mudurlukler.upload-document');
    Route::post('/mudurlukler/{mudurluk}/documents/bulk', [App\Http\Controllers\Admin\MudurlukController::class, 'bulkUploadDocuments'])->name('mudurlukler.bulk-upload-documents');
    Route::put('/mudurlukler/{mudurluk}/documents/{document}', [App\Http\Controllers\Admin\MudurlukController::class, 'updateDocument'])->name('mudurlukler.update-document');
    Route::delete('/mudurlukler/{mudurluk}/documents/{document}', [App\Http\Controllers\Admin\MudurlukController::class, 'destroyDocument'])->name('mudurlukler.destroy-document');
    Route::post('/mudurlukler/{mudurluk}/documents/{document}/toggle-status', [App\Http\Controllers\Admin\MudurlukController::class, 'toggleDocumentStatus'])->name('mudurlukler.toggle-document-status');
});

// Duplicate services route'u kaldırıldı - admin içindeki yeterli

/*
|--------------------------------------------------------------------------
| WILDCARD ROUTES - EN SONDA OLMALI!
|--------------------------------------------------------------------------
| Bu route'lar en genel olanlar olduğu için en sona konulmuştur.
| Üstteki spesifik route'lar önce kontrol edilir.
*/

// UYARI: Bu route'lar çok genel olduğu için en sonda tanımlanmalıdır!

// 404 Fallback Route - Tüm eşleşmeyen URL'leri yakalar ve 404 loglarına ekler
Route::fallback(function (Request $request) {
    // 404 hatasını manuel olarak logla
    try {
        $url = $request->getPathInfo();
        $referer = $request->header('referer');
        $userAgent = $request->header('user-agent');
        $ipAddress = $request->ip();
        
        // Admin paneli ve API isteklerini hariç tut
        $ignoredPaths = ['/admin', '/api', '/favicon.ico', '/robots.txt', '/sitemap.xml', '/.well-known'];
        $shouldIgnore = false;
        
        foreach ($ignoredPaths as $path) {
            if (str_starts_with($url, $path)) {
                $shouldIgnore = true;
                break;
            }
        }
        
        // Dosya uzantıları kontrolü
        $ignoredExtensions = ['.css', '.js', '.png', '.jpg', '.jpeg', '.gif', '.svg', '.ico', '.woff', '.woff2', '.ttf'];
        foreach ($ignoredExtensions as $ext) {
            if (str_ends_with($url, $ext)) {
                $shouldIgnore = true;
                break;
            }
        }
        
        if (!$shouldIgnore) {
            \App\Models\NotFoundLog::logNotFound($url, $referer, $userAgent, $ipAddress);
        }
    } catch (\Exception $e) {
        \Log::error('Fallback 404 logging error: ' . $e->getMessage());
    }
    
    // 404 sayfasını göster
    abort(404);
});



