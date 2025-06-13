<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Page;
use App\Models\Post;
use App\Models\User;
use App\Models\News;
use App\Models\Service;
use App\Models\Mudurluk;
use App\Models\Project;
use App\Models\CankayaHouse;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Admin panel anasayfa görünümü.
     */
    public function index()
    {
        // İstatistikleri hesapla
        $stats = [
            'total_users' => User::count(),
            'total_posts' => Post::count(),
            'total_pages' => Page::count(),
            'total_categories' => Category::count(),
            'published_posts' => Post::where('is_published', true)->count(),
            'published_pages' => Page::where('is_published', true)->count(),
            'active_categories' => Category::where('is_active', true)->count(),
            // Haber istatistikleri
            'total_news' => News::count(),
            'published_news' => News::where('status', 'published')->count(),
            'headline_news' => News::where('is_headline', true)->count(),
            // Yeni istatistikler
            'total_services' => Service::count(),
            'published_services' => Service::where('status', 'published')->count(),
            'total_mudurlukler' => Mudurluk::count(),
            'active_mudurlukler' => Mudurluk::where('is_active', true)->count(),
            'total_projects' => Project::count(),
            'active_projects' => Project::where('is_active', true)->count(),
            'total_cankaya_houses' => CankayaHouse::count(),
            'active_cankaya_houses' => CankayaHouse::where('status', 'active')->count(),
        ];
        
        // Son eklenen kullanıcılar
        $latest_users = User::latest()->limit(5)->get();
        
        // Son eklenen gönderiler
        $latest_posts = Post::with('creator')->latest()->limit(5)->get();
        
        // Son eklenen haberler
        $latest_news = News::with('category')->latest()->limit(5)->get();
        
        return view('admin.dashboard', compact('stats', 'latest_users', 'latest_posts', 'latest_news'));
    }
    
    /**
     * Admin paneli profil görünümü.
     */
    public function profile()
    {
        return view('admin.profile');
    }
    
    /**
     * Admin paneli profil güncelleme işlemi.
     */
    public function updateProfile(Request $request)
    {
        $user = auth()->user();
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'bio' => 'nullable|string',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        if ($request->hasFile('avatar')) {
            // Avatar yükleme işlemi
            $uploadPath = 'uploads/avatars';
            $extension = $request->avatar->extension();
            $originalFilename = time();
            
            // Benzersiz dosya adı oluştur
            $avatarName = $this->createUniqueFilename($uploadPath, $originalFilename, $extension);
            
            // Eski dosyayı sil
            if ($user->avatar && file_exists(public_path($user->avatar))) {
                unlink(public_path($user->avatar));
            }
            
            $request->avatar->move(public_path($uploadPath), $avatarName);
            $validated['avatar'] = $uploadPath . '/' . $avatarName;
        }
        
        $user->update($validated);
        
        return redirect()->route('admin.profile')->with('success', 'Profil başarıyla güncellendi.');
    }
    
    /**
     * Benzersiz dosya adı oluştur
     * Eğer aynı isimde dosya varsa sonuna sayı ekler (örn: resim_1.jpg, resim_2.jpg)
     *
     * @param string $path Dizin yolu
     * @param string $filename Dosya adı (uzantısız)
     * @param string $extension Dosya uzantısı
     * @return string Benzersiz dosya adı (uzantı dahil)
     */
    private function createUniqueFilename($path, $filename, $extension)
    {
        $fullFilename = $filename . '.' . $extension;
        $fullPath = public_path($path . '/' . $fullFilename);
        
        if (!file_exists($fullPath)) {
            return $fullFilename;
        }
        
        $counter = 1;
        while (file_exists($fullPath)) {
            $fullFilename = $filename . '_' . $counter . '.' . $extension;
            $fullPath = public_path($path . '/' . $fullFilename);
            $counter++;
        }
        
        return $fullFilename;
    }
}
