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
use App\Http\Controllers\AnnouncementFrontController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TinyMCEController;
use App\Http\Controllers\Admin\ServiceSettingsController;
use App\Http\Controllers\Admin\DepartmentController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

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

Route::get('/', [App\Http\Controllers\FrontController::class, 'index'])->name('front.home');

// Kurumsal Kadro Frontend Route'ları
Route::get('/kurumsal-kadro', [App\Http\Controllers\CorporateController::class, 'index'])->name('corporate.index');

// Kurumsal Kadro Kategori ve Üye Rotaları - Daha spesifik rotaları önce tanımla
Route::get('/{categorySlug}/{memberSlug}', [App\Http\Controllers\CorporateController::class, 'showMember'])
    ->name('corporate.member')
    ->where('categorySlug', '^(?!admin|login|register|password|kurumsal-kadro|projeler|etkinlikler|hizmetler|sayfalar).*$');

Route::get('/{categorySlug}', [App\Http\Controllers\CorporateController::class, 'showCategory'])
    ->name('corporate.category')
    ->where('categorySlug', '^(?!admin|login|register|password|kurumsal-kadro|projeler|etkinlikler|hizmetler|sayfalar).*$');

// Proje Detay Sayfaları
Route::get('/projects', [App\Http\Controllers\FrontController::class, 'projects'])->name('front.projects');
Route::get('/projects/{slug}', [App\Http\Controllers\FrontController::class, 'projectDetail'])->name('front.projects.detail');
Route::get('/project-category/{slug}', [App\Http\Controllers\FrontController::class, 'projectCategory'])->name('front.projects.category');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Admin Panel Route Tanımlamaları
Route::middleware(['auth', 'role:admin'])->name('admin.')->prefix('admin')->group(function () {
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [DashboardController::class, 'profile'])->name('profile');
    Route::put('/profile', [DashboardController::class, 'updateProfile'])->name('profile.update');
    
    // Test Sayfası
    Route::get('/test', [TestController::class, 'index'])->name('test.index');
    Route::get('/test-departments', [TestDepartmentController::class, 'index'])->name('test-departments.index');
    
    // Sayfa Ayarları
    Route::get('pages/settings', [PageSettingController::class, 'edit'])->name('pages.settings.edit');
    Route::put('pages/settings', [PageSettingController::class, 'update'])->name('pages.settings.update');
    
    // Sayfa Yönetimi
    Route::resource('pages', App\Http\Controllers\Admin\PageController::class);
    Route::post('pages/{page}/toggle-featured', [App\Http\Controllers\Admin\PageController::class, 'toggleFeatured'])->name('pages.toggle-featured');
    Route::post('pages/{page}/toggle-status', [App\Http\Controllers\Admin\PageController::class, 'toggleStatus'])->name('pages.toggle-status');
    Route::post('pages/update-featured-order', [App\Http\Controllers\Admin\PageController::class, 'updateFeaturedOrder'])->name('pages.update-featured-order');
    Route::post('pages/upload-gallery-image', [App\Http\Controllers\Admin\PageController::class, 'uploadGalleryImage'])->name('pages.upload-gallery-image');
    
    // File Manager Routes
    Route::get('/file-manager-page', [App\Http\Controllers\Admin\FileManagerController::class, 'index'])->name('file-manager');
    
    // Laravel File Manager rotalarını filemanager prefix'i ile tanımla
    Route::group(['prefix' => 'filemanager', 'middleware' => ['web']], function () {
        \UniSharp\LaravelFilemanager\Lfm::routes();
    });
    
    // TinyMCE için resim yükleme
    Route::post('/tinymce/upload', [App\Http\Controllers\TinyMCEController::class, 'upload'])->name('tinymce.upload');
    
    // Haberler Yönetimi
    Route::resource('news', App\Http\Controllers\Admin\NewsController::class);
    Route::post('/news/{news}/toggle-headline', [App\Http\Controllers\Admin\NewsController::class, 'toggleHeadline'])->name('news.toggle-headline');
    Route::post('/news/{news}/toggle-featured', [App\Http\Controllers\Admin\NewsController::class, 'toggleFeatured'])->name('news.toggle-featured');
    Route::post('/news/{news}/toggle-status', [App\Http\Controllers\Admin\NewsController::class, 'toggleStatus'])->name('news.toggle-status');
    Route::post('/news/update-headline-order', [App\Http\Controllers\Admin\NewsController::class, 'updateHeadlineOrder'])->name('news.update-headline-order');
    Route::get('/news/{news}/toggle-archive', [App\Http\Controllers\Admin\NewsController::class, 'toggleArchive'])->name('news.toggle-archive');
    Route::post('/news/upload-gallery-image', [App\Http\Controllers\Admin\NewsController::class, 'uploadGalleryImage'])->name('news.upload-gallery-image');
    
    // Kategori Yönetimi
    Route::resource('news-categories', NewsCategoryController::class)->names('news-categories');
    Route::post('/news-categories/update-order', [NewsCategoryController::class, 'updateOrder'])->name('news-categories.update-order');
    
    // Etiket Yönetimi
    Route::resource('news-tags', NewsTagController::class)->names('news-tags');
    Route::get('/news-tags/cleanup', [NewsTagController::class, 'cleanup'])->name('news-tags.cleanup');
    Route::get('/news-tags/search', [NewsTagController::class, 'search'])->name('news-tags.search');
    
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
    Route::get('/projects/create', [ProjectManagerController::class, 'createProject'])->name('projects.create');
    Route::get('/projects/{id}/edit', [ProjectManagerController::class, 'editProject'])->name('projects.edit');
    
    // Proje Kategori İşlemleri
    Route::post('/projects/categories', [ProjectManagerController::class, 'storeCategory'])->name('projects.categories.store');
    Route::put('/projects/categories/{id}', [ProjectManagerController::class, 'updateCategory'])->name('projects.categories.update');
    Route::delete('/projects/categories/{id}', [ProjectManagerController::class, 'deleteCategory'])->name('projects.categories.delete');
    Route::post('/projects/categories/update-order', [ProjectManagerController::class, 'updateCategoryOrder'])->name('projects.categories.update-order');
    Route::post('/projects/categories/{id}/toggle-visibility', [ProjectManagerController::class, 'toggleCategoryVisibility'])->name('projects.categories.toggle-visibility');
    
    // Proje İşlemleri
    Route::post('/projects', [ProjectManagerController::class, 'storeProject'])->name('admin.projects.store2');
    Route::put('/projects/{id}', [ProjectManagerController::class, 'updateProject'])->name('projects.update');
    Route::delete('/projects/{id}', [ProjectManagerController::class, 'deleteProject'])->name('projects.delete');
    Route::post('/projects/update-order', [ProjectManagerController::class, 'updateProjectOrder'])->name('projects.update-order');
    Route::post('/projects/{id}/toggle-visibility', [ProjectManagerController::class, 'toggleProjectVisibility'])->name('projects.toggle-visibility');
    Route::post('/projects/{id}/toggle-homepage', [ProjectManagerController::class, 'toggleProjectHomepageVisibility'])->name('projects.toggle-homepage');
    
    // Proje Görselleri İşlemleri
    Route::delete('/project-images/{id}', [ProjectManagerController::class, 'deleteProjectImage'])->name('projects.images.delete');
    Route::post('/project-images/update-order', [ProjectManagerController::class, 'updateProjectImagesOrder'])->name('projects.images.update-order');
    
    // Proje Ayarları
    Route::post('/projects/settings', [ProjectManagerController::class, 'updateSettings'])->name('projects.settings.update');
    Route::post('/projects/toggle-module-visibility', [ProjectManagerController::class, 'toggleProjectModuleVisibility'])->name('projects.toggle-module-visibility');
    
    // Hizmetler Yönetimi
    Route::resource('services', ServiceController::class);
    Route::post('/services/{service}/toggle-headline', [ServiceController::class, 'toggleHeadline'])->name('services.toggle-headline');
    Route::post('/services/{service}/toggle-featured', [ServiceController::class, 'toggleFeatured'])->name('services.toggle-featured');
    Route::post('/services/{service}/toggle-status', [ServiceController::class, 'toggleStatus'])->name('services.toggle-status');
    Route::post('/services/update-headline-order', [ServiceController::class, 'updateHeadlineOrder'])->name('services.update-headline-order');
    Route::post('/services/upload-gallery-image', [ServiceController::class, 'uploadGalleryImage'])->name('services.upload-gallery-image');
    
    // Hizmet Kategorileri Yönetimi
    Route::resource('service-categories', ServiceCategoryController::class)->names('service-categories');
    Route::post('/service-categories/update-order', [ServiceCategoryController::class, 'updateOrder'])->name('service-categories.update-order');
    
    // Hizmet Etiketleri Yönetimi
    Route::resource('service-tags', ServiceTagController::class)->names('service-tags');
    Route::get('/service-tags/cleanup', [ServiceTagController::class, 'cleanup'])->name('service-tags.cleanup');
    Route::get('/service-tags/search', [ServiceTagController::class, 'search'])->name('service-tags.search');
    
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
});

// Proje Yönetimi Rotaları
Route::prefix('admin/projects')->name('admin.projects.')->middleware(['auth'])->group(function () {
    Route::get('/', [App\Http\Controllers\Admin\ProjectManagerController::class, 'index'])->name('index');
    Route::get('/create', [App\Http\Controllers\Admin\ProjectManagerController::class, 'create'])->name('create');
    Route::post('/store', [App\Http\Controllers\Admin\ProjectManagerController::class, 'store'])->name('store');
    Route::get('/edit/{id}', [App\Http\Controllers\Admin\ProjectManagerController::class, 'edit'])->name('edit');
    Route::post('/update/{id}', [App\Http\Controllers\Admin\ProjectManagerController::class, 'update'])->name('update');
    Route::delete('/delete/{id}', [App\Http\Controllers\Admin\ProjectManagerController::class, 'delete'])->name('delete');
    
    // Proje Görünürlük ve Sıralama İşlemleri
    Route::post('/{id}/toggle-visibility', [App\Http\Controllers\Admin\ProjectManagerController::class, 'toggleVisibility'])->name('toggle-visibility');
    Route::post('/{id}/toggle-homepage', [App\Http\Controllers\Admin\ProjectManagerController::class, 'toggleHomepage'])->name('toggle-homepage');
    Route::post('/update-order', [App\Http\Controllers\Admin\ProjectManagerController::class, 'updateOrder'])->name('update-order');
    
    // Kategori Yönetimi
    Route::get('/categories', [App\Http\Controllers\Admin\ProjectManagerController::class, 'categories'])->name('categories');
    Route::post('/categories/store', [App\Http\Controllers\Admin\ProjectManagerController::class, 'storeCategory'])->name('categories.store');
    Route::post('/categories/update/{id}', [App\Http\Controllers\Admin\ProjectManagerController::class, 'updateCategory'])->name('categories.update');
    Route::delete('/categories/delete/{id}', [App\Http\Controllers\Admin\ProjectManagerController::class, 'deleteCategory'])->name('categories.delete');
    Route::post('/categories/{id}/toggle-visibility', [App\Http\Controllers\Admin\ProjectManagerController::class, 'toggleCategoryVisibility'])->name('categories.toggle-visibility');
    Route::post('/categories/update-order', [App\Http\Controllers\Admin\ProjectManagerController::class, 'updateCategoryOrder'])->name('categories.update-order');
    
    // Ayarlar
    Route::get('/settings', [App\Http\Controllers\Admin\ProjectManagerController::class, 'settings'])->name('settings');
    Route::post('/settings/update', [App\Http\Controllers\Admin\ProjectManagerController::class, 'updateSettings'])->name('settings.update');
    Route::post('/toggle-module-visibility', [App\Http\Controllers\Admin\ProjectManagerController::class, 'toggleModuleVisibility'])->name('toggle-module-visibility');
});

// Etkinlik Yönetimi Rotaları
Route::prefix('admin/events')->name('admin.events.')->middleware(['auth'])->group(function () {
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

// Ön Yüz Proje Rotaları
Route::get('/projeler', [App\Http\Controllers\Front\FrontController::class, 'projects'])->name('front.projects');
Route::get('/projeler/{slug}', [App\Http\Controllers\Front\FrontController::class, 'projectDetail'])->name('front.projects.detail');

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
    Route::get('/kategori/{slug}', [App\Http\Controllers\Front\ServiceController::class, 'category'])->name('category');
    Route::get('/{slug}', [App\Http\Controllers\Front\ServiceController::class, 'show'])->name('show');
});

// Doğrudan hizmet route'u ekle
Route::get('/hizmet/{slug}', [App\Http\Controllers\Front\ServiceController::class, 'show'])->name('hizmet');

// Ön Yüz Sayfa Rotaları
Route::prefix('sayfalar')->name('pages.')->group(function () {
    Route::get('/', [App\Http\Controllers\Front\PageController::class, 'index'])->name('index');
    Route::get('/kategori/{slug}', [App\Http\Controllers\Front\PageController::class, 'category'])->name('category');
    Route::get('/{slug}', [App\Http\Controllers\Front\PageController::class, 'show'])->name('show');
});

// Anasayfa Yönetimi Rotaları
Route::prefix('admin/homepage')->name('admin.homepage.')->middleware(['auth'])->group(function () {
    Route::get('/', [App\Http\Controllers\Admin\HomepageController::class, 'index'])->name('index');
    
    // Slider Yönetimi
    Route::get('/sliders', [App\Http\Controllers\Admin\HomepageController::class, 'sliders'])->name('sliders');
    Route::get('/sliders/create', [App\Http\Controllers\Admin\HomepageController::class, 'createSlider'])->name('sliders.create');
    Route::post('/sliders/store', [App\Http\Controllers\Admin\HomepageController::class, 'storeSlider'])->name('sliders.store');
    Route::get('/sliders/edit/{id}', [App\Http\Controllers\Admin\HomepageController::class, 'editSlider'])->name('sliders.edit');
    Route::match(['put', 'post'], '/sliders/update/{id}', [App\Http\Controllers\Admin\HomepageController::class, 'updateSlider'])->name('sliders.update');
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
        Route::post('/{category_id}/items/{id}/update', [App\Http\Controllers\Admin\HomepageController::class, 'updateQuickMenuItem'])->name('items.update');
        Route::delete('/{category_id}/items/{id}/delete', [App\Http\Controllers\Admin\HomepageController::class, 'deleteQuickMenuItem'])->name('items.delete');
        Route::post('/{category_id}/items/order', [App\Http\Controllers\Admin\HomepageController::class, 'updateQuickMenuItemOrder'])->name('items.order');
        Route::post('/{category_id}/items/{id}/toggle', [App\Http\Controllers\Admin\HomepageController::class, 'toggleQuickMenuItem'])->name('items.toggle');
    });
});

// Etkinlik Yönetimi
Route::group(['prefix' => 'events', 'as' => 'events.'], function () {
    Route::get('/', [EventManagerController::class, 'index'])->name('index');
    Route::get('/create', [EventManagerController::class, 'create'])->name('create');
    Route::post('/', [EventManagerController::class, 'store'])->name('store');
    Route::get('/{event}/edit', [EventManagerController::class, 'edit'])->name('edit');
    Route::put('/{event}', [EventManagerController::class, 'update'])->name('update');
    Route::delete('/{event}', [EventManagerController::class, 'destroy'])->name('destroy');
    
    // Etkinlik görünürlüğünü değiştir
    Route::post('/{event}/toggle', [EventManagerController::class, 'toggle'])->name('toggle');
    Route::post('/{event}/toggle-homepage', [EventManagerController::class, 'toggleHomepage'])->name('toggle.homepage');
    Route::post('/{event}/toggle-featured', [EventManagerController::class, 'toggleFeatured'])->name('toggle.featured');
    
    // Etkinlik kategorileri
    Route::get('/categories', [EventManagerController::class, 'categories'])->name('categories');
    Route::post('/categories', [EventManagerController::class, 'storeCategory'])->name('categories.store');
    Route::put('/categories/{id}', [EventManagerController::class, 'updateCategory'])->name('categories.update');
    Route::delete('/categories/{id}', [EventManagerController::class, 'deleteCategory'])->name('categories.delete');
    Route::post('/categories/{id}/toggle', [EventManagerController::class, 'toggleCategoryVisibility'])->name('categories.toggle');
    Route::post('/categories/order', [EventManagerController::class, 'updateCategoryOrder'])->name('categories.order');
    
    // Etkinlik ayarları
    Route::get('/settings', [EventManagerController::class, 'settings'])->name('settings');
    Route::post('/settings', [EventManagerController::class, 'updateSettings'])->name('settings.update');
    
    // Etkinlik tarama ve veri çekme
    Route::get('/check', [EventManagerController::class, 'checkEvents'])->name('check');
    Route::post('/scrape', [EventManagerController::class, 'scrapeEvents'])->name('scrape');
    
    // Etkinlik görselleri için GET route
    Route::get('/{filename}', function($filename) {
        $path = storage_path('app/public/events/' . $filename);
        if (file_exists($path)) {
            return response()->file($path);
        }
        
        return response()->json(['error' => 'Görsel bulunamadı'], 404);
    })->where('filename', '.*\.(?:jpg|jpeg|png|gif|webp)$');
});

// Etkinlik görselleri için route
Route::get('/events/{filename}', function($filename) {
    $path = storage_path('app/public/events/' . $filename);
    if (file_exists($path)) {
        return response()->file($path);
    }
    
    return response()->json(['error' => 'Görsel bulunamadı'], 404);
})->where('filename', '.*\.(?:jpg|jpeg|png|gif|webp)$');

// Frontend Duyuru İşlemleri
Route::post('/announcements/mark-viewed', [AnnouncementFrontController::class, 'markViewed'])->name('announcements.mark-viewed');

