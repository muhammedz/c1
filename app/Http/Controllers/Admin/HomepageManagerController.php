<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProfileSettings;
use App\Models\MobileAppSettings;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use App\Models\FeaturedService;

class HomepageManagerController extends Controller
{
    /**
     * Anasayfa yönetimi index sayfasını gösterir
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.homepage.index');
    }
    
    /**
     * Profil bilgileri düzenleme sayfasını gösterir
     *
     * @return \Illuminate\Http\Response
     */
    public function profileInfo()
    {
        // İlk veya varsayılan profil ayarlarını al
        $profileSettings = ProfileSettings::firstOrNew();
        
        return view('admin.homepage.profile-info', compact('profileSettings'));
    }
    
    /**
     * Profil bilgilerini günceller
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateProfileInfo(Request $request)
    {
        $request->validate([
            'name' => 'nullable|string|max:255',
            'title' => 'nullable|string|max:255',
            'filemanagersystem_profile_photo' => 'nullable|string',
            'filemanagersystem_profile_photo_alt' => 'nullable|string|max:255',
            'filemanagersystem_profile_photo_title' => 'nullable|string|max:255',
            'facebook_url' => 'nullable|url',
            'instagram_url' => 'nullable|url',
            'twitter_url' => 'nullable|url',
            'youtube_url' => 'nullable|url',
            'filemanagersystem_contact_image' => 'nullable|string',
            'filemanagersystem_contact_image_alt' => 'nullable|string|max:255',
            'filemanagersystem_contact_image_title' => 'nullable|string|max:255',
        ]);
        
        // İlk kaydı al veya yeni oluştur
        $profileSettings = ProfileSettings::firstOrNew(['id' => 1]);
        
        // Metin verilerini güncelle
        $profileSettings->name = $request->name;
        $profileSettings->title = $request->title;
        $profileSettings->facebook_url = $request->facebook_url;
        $profileSettings->instagram_url = $request->instagram_url;
        $profileSettings->twitter_url = $request->twitter_url;
        $profileSettings->youtube_url = $request->youtube_url;
        
        // FileManagerSystem profil fotoğrafı
        if ($request->has('filemanagersystem_profile_photo')) {
            $profileSettings->filemanagersystem_profile_photo = $request->filemanagersystem_profile_photo;
            $profileSettings->filemanagersystem_profile_photo_alt = $request->filemanagersystem_profile_photo_alt;
            $profileSettings->filemanagersystem_profile_photo_title = $request->filemanagersystem_profile_photo_title;
        }
        
        // FileManagerSystem iletişim görseli
        if ($request->has('filemanagersystem_contact_image')) {
            $profileSettings->filemanagersystem_contact_image = $request->filemanagersystem_contact_image;
            $profileSettings->filemanagersystem_contact_image_alt = $request->filemanagersystem_contact_image_alt;
            $profileSettings->filemanagersystem_contact_image_title = $request->filemanagersystem_contact_image_title;
        }
        
        $profileSettings->save();
        
        return redirect()->route('admin.homepage.profile-info')->with('success', 'Profil bilgileri başarıyla güncellendi.');
    }

    /**
     * Mobil uygulama ayarları sayfasını gösterir
     *
     * @return \Illuminate\Http\Response
     */
    public function mobileApp()
    {
        // İlk veya varsayılan mobil uygulama ayarlarını al
        $mobileAppSettings = MobileAppSettings::firstOrNew();
        
        return view('admin.homepage.mobile-app', compact('mobileAppSettings'));
    }

    /**
     * Mobil uygulama ayarlarını günceller
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateMobileApp(Request $request)
    {
        $request->validate([
            'app_name' => 'nullable|string|max:255',
            'app_subtitle' => 'nullable|string|max:255',
            'app_description' => 'nullable|string',
            'app_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'phone_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'app_store_link' => 'nullable|url',
            'google_play_link' => 'nullable|url',
            'link_card_1_title' => 'nullable|string|max:255',
            'link_card_1_url' => 'nullable|url',
            'link_card_1_icon' => 'nullable|string|max:50',
            'link_card_2_title' => 'nullable|string|max:255',
            'link_card_2_url' => 'nullable|url',
            'link_card_2_icon' => 'nullable|string|max:50',
            'link_card_3_title' => 'nullable|string|max:255',
            'link_card_3_url' => 'nullable|url',
            'link_card_3_icon' => 'nullable|string|max:50',
        ]);
        
        // İlk kaydı al veya yeni oluştur
        $mobileAppSettings = MobileAppSettings::firstOrNew(['id' => 1]);
        
        // Metin verilerini güncelle
        $mobileAppSettings->app_name = $request->app_name;
        $mobileAppSettings->app_subtitle = $request->app_subtitle;
        $mobileAppSettings->app_description = $request->app_description;
        $mobileAppSettings->app_store_link = $request->app_store_link;
        $mobileAppSettings->google_play_link = $request->google_play_link;
        
        // Link kartları verilerini güncelle
        $mobileAppSettings->link_card_1_title = $request->link_card_1_title;
        $mobileAppSettings->link_card_1_url = $request->link_card_1_url;
        $mobileAppSettings->link_card_1_icon = $request->link_card_1_icon;
        
        $mobileAppSettings->link_card_2_title = $request->link_card_2_title;
        $mobileAppSettings->link_card_2_url = $request->link_card_2_url;
        $mobileAppSettings->link_card_2_icon = $request->link_card_2_icon;
        
        $mobileAppSettings->link_card_3_title = $request->link_card_3_title;
        $mobileAppSettings->link_card_3_url = $request->link_card_3_url;
        $mobileAppSettings->link_card_3_icon = $request->link_card_3_icon;
        
        // Uygulama logosu güncelle
        if ($request->hasFile('app_logo')) {
            // Eğer varsa eski dosyayı sil
            if ($mobileAppSettings->app_logo) {
                Storage::disk('public')->delete($mobileAppSettings->app_logo);
            }
            
            // Yeni logoyu yükle
            $appLogoPath = $request->file('app_logo')->store('mobile-app', 'public');
            $mobileAppSettings->app_logo = $appLogoPath;
        }
        
        // Telefon görseli güncelle
        if ($request->hasFile('phone_image')) {
            // Eğer varsa eski dosyayı sil
            if ($mobileAppSettings->phone_image) {
                Storage::disk('public')->delete($mobileAppSettings->phone_image);
            }
            
            // Yeni görseli yükle
            $phoneImagePath = $request->file('phone_image')->store('mobile-app', 'public');
            $mobileAppSettings->phone_image = $phoneImagePath;
        }
        
        $mobileAppSettings->save();
        
        return redirect()->route('admin.homepage.mobile-app')->with('success', 'Mobil uygulama ayarları başarıyla güncellendi.');
    }

    /**
     * Mobil uygulama görünürlüğünü değiştirir
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function toggleMobileAppVisibility(Request $request)
    {
        try {
            // İlk kaydı al veya yeni oluştur
            $mobileAppSettings = MobileAppSettings::firstOrNew(['id' => 1]);
            
            // Görünürlüğü değiştir (true ise false, false ise true yap)
            $mobileAppSettings->is_active = !$mobileAppSettings->is_active;
            $mobileAppSettings->save();
            
            // Yeni durum ve başarı mesajı
            $status = $mobileAppSettings->is_active;
            $message = $status 
                ? 'Mobil uygulama bölümü başarıyla aktifleştirildi.' 
                : 'Mobil uygulama bölümü başarıyla pasifleştirildi.';
            
            return response()->json([
                'success' => true,
                'is_active' => $status,
                'message' => $message
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Görünürlük değiştirilemedi: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Öne çıkan hizmetler sayfasını gösterir
     *
     * @return \Illuminate\Http\Response
     */
    public function featuredServices()
    {
        // Ayarları al veya varsayılan oluştur
        $settings = \App\Models\FeaturedServiceSetting::firstOrCreate();
        
        // Tüm hizmetleri sıralı bir şekilde al
        $services = \App\Models\FeaturedService::orderBy('order')->get();
        
        return view('admin.homepage.featured-services', compact('settings', 'services'));
    }

    /**
     * Öne çıkan hizmetler genel ayarlarını günceller
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateFeaturedServiceSettings(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'swiper_items_per_view' => 'required|integer|min:1|max:10',
            'swiper_autoplay' => 'nullable|integer|min:1000',
        ]);
        
        // Ayarları al veya oluştur
        $settings = \App\Models\FeaturedServiceSetting::firstOrCreate();
        
        // Ayarları güncelle
        $settings->title = $request->title;
        $settings->swiper_items_per_view = $request->swiper_items_per_view;
        $settings->swiper_autoplay = $request->swiper_autoplay;
        $settings->save();
        
        return redirect()->route('admin.homepage.featured-services')
            ->with('success', 'Öne çıkan hizmetler ayarları başarıyla güncellendi.');
    }

    /**
     * Store a newly created featured service.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function storeFeaturedService(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'icon' => 'required|string',
            'url' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            // İkon değerini kontrol et
            $icon = $request->icon;
            $isSvg = str_starts_with($icon, '<svg');
            
            Log::info('FeaturedService ekleniyor', [
                'title' => $request->title,
                'icon_is_svg' => $isSvg,
                'icon_length' => strlen($icon),
            ]);

            FeaturedService::create([
                'title' => $request->title,
                'icon' => $icon,
                'url' => $request->url,
                'is_active' => 1,
                'order' => FeaturedService::max('order') + 1
            ]);

            return redirect()->back()->with('success', 'Hizmet başarıyla eklendi.');
        } catch (\Exception $e) {
            Log::error('FeaturedService eklenirken hata oluştu', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()->with('error', 'Hizmet eklenirken bir hata oluştu: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Update the specified featured service.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function updateFeaturedService(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'icon' => 'required|string',
            'url' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $service = FeaturedService::findOrFail($id);
            
            // İkon değerini kontrol et
            $icon = $request->icon;
            $isSvg = str_starts_with($icon, '<svg');
            
            Log::info('FeaturedService güncelleniyor', [
                'id' => $id,
                'title' => $request->title,
                'icon_is_svg' => $isSvg,
                'icon_length' => strlen($icon),
            ]);

            $service->update([
                'title' => $request->title,
                'icon' => $icon,
                'url' => $request->url,
                'is_active' => $request->has('is_active') ? 1 : $service->is_active,
            ]);

            return redirect()->back()->with('success', 'Hizmet başarıyla güncellendi.');
        } catch (\Exception $e) {
            Log::error('FeaturedService güncellenirken hata oluştu', [
                'id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()->with('error', 'Hizmet güncellenirken bir hata oluştu: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Bir öne çıkan hizmeti siler
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteFeaturedService($id)
    {
        // Hizmeti bul ve sil
        $service = \App\Models\FeaturedService::findOrFail($id);
        $service->delete();
        
        // Kalan hizmetlerin sıralamasını güncelle
        $remainingServices = \App\Models\FeaturedService::orderBy('order')->get();
        foreach ($remainingServices as $index => $service) {
            $service->order = $index + 1;
            $service->save();
        }
        
        return redirect()->route('admin.homepage.featured-services')
            ->with('success', 'Hizmet başarıyla silindi.');
    }

    /**
     * Bir öne çıkan hizmetin görünürlüğünü değiştirir
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function toggleFeaturedServiceVisibility(Request $request, $id)
    {
        try {
            // Hizmeti bul
            $service = \App\Models\FeaturedService::findOrFail($id);
            
            // Görünürlüğü değiştir
            $service->is_active = !$service->is_active;
            $service->save();
            
            // Yeni durum ve başarı mesajı
            $status = $service->is_active;
            $message = $status 
                ? 'Hizmet başarıyla aktifleştirildi.' 
                : 'Hizmet başarıyla pasifleştirildi.';
            
            return response()->json([
                'success' => true,
                'is_active' => $status,
                'message' => $message
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Görünürlük değiştirilemedi: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Öne çıkan hizmetler bölümünün görünürlüğünü değiştirir
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function toggleFeaturedServicesVisibility(Request $request)
    {
        try {
            // Ayarları al veya oluştur
            $settings = \App\Models\FeaturedServiceSetting::firstOrCreate();
            
            // Görünürlüğü değiştir
            $settings->is_active = !$settings->is_active;
            $settings->save();
            
            // Yeni durum ve başarı mesajı
            $status = $settings->is_active;
            $message = $status 
                ? 'Öne çıkan hizmetler bölümü başarıyla aktifleştirildi.' 
                : 'Öne çıkan hizmetler bölümü başarıyla pasifleştirildi.';
            
            return response()->json([
                'success' => true,
                'is_active' => $status,
                'message' => $message
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Görünürlük değiştirilemedi: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Öne çıkan hizmetlerin sıralama düzenini günceller
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateFeaturedServicesOrder(Request $request)
    {
        try {
            $order = $request->input('order');
            
            // Her hizmetin sıra numarasını güncelle
            foreach ($order as $position => $id) {
                $service = \App\Models\FeaturedService::findOrFail($id);
                $service->order = $position + 1;
                $service->save();
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Hizmet sıralaması başarıyla güncellendi.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Sıralama güncellenemedi: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Logo ve planlar sayfasını gösterir
     *
     * @return \Illuminate\Http\Response
     */
    public function logoAndPlans()
    {
        // İlk veya varsayılan logo ve plan ayarlarını al
        $logoPlans = \App\Models\LogoPlanSettings::firstOrNew();
        
        return view('admin.homepage.logo-and-plans', compact('logoPlans'));
    }

    /**
     * Logo ve planları günceller
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateLogoAndPlans(Request $request)
    {
        $request->validate([
            'card1_title' => 'nullable|string|max:255',
            'card1_icon' => 'nullable|string|max:255',
            'card1_url' => 'nullable|string|max:255',
            'card2_title' => 'nullable|string|max:255',
            'card2_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'card2_url' => 'nullable|string|max:255',
            'logo_title' => 'nullable|string|max:255',
            'logo_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'logo_bg_color' => 'nullable|string|max:30',
        ]);
        
        // İlk kaydı al veya yeni oluştur
        $logoPlans = \App\Models\LogoPlanSettings::firstOrNew(['id' => 1]);
        
        // Metin verilerini güncelle
        $logoPlans->card1_title = $request->card1_title;
        $logoPlans->card1_icon = $request->card1_icon;
        $logoPlans->card1_url = $request->card1_url;
        $logoPlans->card2_title = $request->card2_title;
        $logoPlans->card2_url = $request->card2_url;
        $logoPlans->logo_title = $request->logo_title;
        $logoPlans->logo_bg_color = $request->logo_bg_color ?: '#004d2e';
        
        // Kart 2 görseli güncelle
        if ($request->hasFile('card2_image')) {
            // Eğer varsa eski dosyayı sil
            if ($logoPlans->card2_image) {
                Storage::disk('public')->delete($logoPlans->card2_image);
            }
            
            // Yeni görseli yükle
            $card2ImagePath = $request->file('card2_image')->store('logo-plans', 'public');
            $logoPlans->card2_image = $card2ImagePath;
        }
        
        // Logo görseli güncelle
        if ($request->hasFile('logo_image')) {
            // Eğer varsa eski dosyayı sil
            if ($logoPlans->logo_image) {
                Storage::disk('public')->delete($logoPlans->logo_image);
            }
            
            // Yeni logoyu yükle
            $logoImagePath = $request->file('logo_image')->store('logo-plans', 'public');
            $logoPlans->logo_image = $logoImagePath;
        }
        
        $logoPlans->save();
        
        return redirect()->route('admin.homepage.logo-and-plans')->with('success', 'Logo ve planlar başarıyla güncellendi.');
    }

    /**
     * Logo ve planlar bölümünün görünürlüğünü değiştirir
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function toggleLogoAndPlansVisibility(Request $request)
    {
        try {
            // İlk kaydı al veya yeni oluştur
            $logoPlans = \App\Models\LogoPlanSettings::firstOrNew(['id' => 1]);
            
            // Görünürlüğü değiştir (true ise false, false ise true yap)
            $logoPlans->is_active = !$logoPlans->is_active;
            $logoPlans->save();
            
            return response()->json([
                'success' => true,
                'message' => $logoPlans->is_active 
                    ? 'Logo ve planlar bölümü görünür yapıldı.' 
                    : 'Logo ve planlar bölümü gizlendi.',
                'is_active' => $logoPlans->is_active
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Bir hata oluştu: ' . $e->getMessage()
            ]);
        }
    }
}
