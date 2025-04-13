<?php

namespace App\Providers;

use App\Models\Announcement;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AnnouncementServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        View::composer('*', function ($view) {
            // Admin bölümünde filtreleme uygulanmasın
            $currentPath = Request::path();
            
            // Admin sayfalarında işlem yapma
            if (strpos($currentPath, 'admin') === 0) {
                return;
            }
            
            // Mevcut sayfayı belirle
            $currentPage = 'home';
            
            // Mevcut sayfa tipini belirle
            if ($currentPath === '/') {
                $currentPage = 'home';
            } elseif (strpos($currentPath, 'services') === 0) {
                $currentPage = 'services';
            } elseif (strpos($currentPath, 'news') === 0) {
                $currentPage = 'news';
            } elseif (strpos($currentPath, 'events') === 0) {
                $currentPage = 'events';
            } elseif (strpos($currentPath, 'contact') === 0) {
                $currentPage = 'contact';
            }
            
            // Aktif duyuruları al
            $announcements = Announcement::getActiveForPage($currentPage);
            
            // Kullanıcının cookie'sindeki görüntülenen duyuruları al
            $viewedAnnouncements = [];
            if (Cookie::has('viewed_announcements')) {
                $viewedAnnouncements = json_decode(Cookie::get('viewed_announcements'), true);
            }
            
            // Duyuruları filtrele - görüntüleme sınırına göre
            $filteredAnnouncements = $announcements->filter(function ($announcement) use ($viewedAnnouncements) {
                // Eğer max_views_per_user sınırsızsa (0) veya duyuru daha önce hiç görüntülenmediyse
                if ($announcement->max_views_per_user === 0) {
                    return true;
                }
                
                // Bu duyurunun kaç kez görüntülendiğini say
                $viewCount = 0;
                foreach ($viewedAnnouncements as $viewedId) {
                    if ($viewedId == $announcement->id) {
                        $viewCount++;
                    }
                }
                
                // Max görüntüleme sayısına ulaşılmadıysa göster
                return $viewCount < $announcement->max_views_per_user;
            });
            
            $view->with('announcements', $filteredAnnouncements);
        });
    }
}
