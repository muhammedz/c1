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
            'filemanagersystem_app_logo' => 'nullable|string',
            'filemanagersystem_app_header_image' => 'nullable|string',
            'app_header_image_width' => 'nullable|integer|min:50|max:800',
            'app_header_image_height' => 'nullable|integer|min:50|max:600',
            'filemanagersystem_phone_image' => 'nullable|string',
            'app_store_link' => 'nullable|url',
            'google_play_link' => 'nullable|url',
            'link_card_1_title' => 'nullable|string|max:255',
            'link_card_1_url' => 'nullable|url',
            'link_card_1_icon' => 'nullable|string|max:50',
            'filemanagersystem_link_card_1_icon' => 'nullable|string',
            'link_card_2_title' => 'nullable|string|max:255',
            'link_card_2_url' => 'nullable|url',
            'link_card_2_icon' => 'nullable|string|max:50',
            'filemanagersystem_link_card_2_icon' => 'nullable|string',
            'link_card_3_title' => 'nullable|string|max:255',
            'link_card_3_url' => 'nullable|url',
            'link_card_3_icon' => 'nullable|string|max:50',
            'filemanagersystem_link_card_3_icon' => 'nullable|string',
        ]);
        
        // İlk kaydı al veya yeni oluştur
        $mobileAppSettings = MobileAppSettings::firstOrNew(['id' => 1]);
        
        // Metin verilerini güncelle
        $mobileAppSettings->app_name = $request->app_name;
        $mobileAppSettings->app_subtitle = $request->app_subtitle;
        $mobileAppSettings->app_description = $request->app_description;
        $mobileAppSettings->app_store_link = $request->app_store_link;
        $mobileAppSettings->google_play_link = $request->google_play_link;
        
        // Görsel boyutlarını güncelle
        $mobileAppSettings->app_header_image_width = $request->app_header_image_width ?? 320;
        $mobileAppSettings->app_header_image_height = $request->app_header_image_height ?? 200;
        
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
        
        // MediaPicker ile seçilen görselleri güncelle
        if ($request->filemanagersystem_app_logo) {
            // MediaPicker'dan gelen değer ID ise, gerçek dosya yolunu al
            if (is_numeric($request->filemanagersystem_app_logo)) {
                $media = \App\Models\FileManagerSystem\Media::find($request->filemanagersystem_app_logo);
                if ($media) {
                    // URL'den storage kısmını çıkararak sadece dosya yolunu al
                    $path = str_replace('/storage/', '', $media->url);
                    $mobileAppSettings->app_logo = $path;
                } else {
                    $mobileAppSettings->app_logo = $request->filemanagersystem_app_logo;
                }
            } else {
                $mobileAppSettings->app_logo = $request->filemanagersystem_app_logo;
            }
            
            // Log ile kaydedilen URL'yi kontrol edelim
            \Log::info('MediaPicker app_logo kaydediliyor', [
                'original' => $request->filemanagersystem_app_logo,
                'saved' => $mobileAppSettings->app_logo
            ]);
        }
        
        if ($request->filemanagersystem_app_header_image) {
            // MediaPicker'dan gelen değer ID ise, gerçek dosya yolunu al
            if (is_numeric($request->filemanagersystem_app_header_image)) {
                $media = \App\Models\FileManagerSystem\Media::find($request->filemanagersystem_app_header_image);
                if ($media) {
                    // URL'den storage kısmını çıkararak sadece dosya yolunu al
                    $path = str_replace('/storage/', '', $media->url);
                    $mobileAppSettings->app_header_image = $path;
                } else {
                    $mobileAppSettings->app_header_image = $request->filemanagersystem_app_header_image;
                }
            } else {
                $mobileAppSettings->app_header_image = $request->filemanagersystem_app_header_image;
            }
            
            // Log ile kaydedilen URL'yi kontrol edelim
            \Log::info('MediaPicker app_header_image kaydediliyor', [
                'original' => $request->filemanagersystem_app_header_image,
                'saved' => $mobileAppSettings->app_header_image
            ]);
        }
        
        if ($request->filemanagersystem_phone_image) {
            // MediaPicker'dan gelen değer ID ise, gerçek dosya yolunu al
            if (is_numeric($request->filemanagersystem_phone_image)) {
                $media = \App\Models\FileManagerSystem\Media::find($request->filemanagersystem_phone_image);
                if ($media) {
                    // URL'den storage kısmını çıkararak sadece dosya yolunu al
                    $path = str_replace('/storage/', '', $media->url);
                    $mobileAppSettings->phone_image = $path;
                } else {
                    $mobileAppSettings->phone_image = $request->filemanagersystem_phone_image;
                }
            } else {
                $mobileAppSettings->phone_image = $request->filemanagersystem_phone_image;
            }
            
            // Log ile kaydedilen URL'yi kontrol edelim
            \Log::info('MediaPicker phone_image kaydediliyor', [
                'original' => $request->filemanagersystem_phone_image,
                'saved' => $mobileAppSettings->phone_image
            ]);
        }
        
        // Bağlantı kartları için özel ikon kaydetme
        if ($request->filemanagersystem_link_card_1_icon) {
            // MediaPicker'dan gelen değer ID ise, gerçek dosya yolunu al
            if (is_numeric($request->filemanagersystem_link_card_1_icon)) {
                $media = \App\Models\FileManagerSystem\Media::find($request->filemanagersystem_link_card_1_icon);
                if ($media) {
                    // URL'den storage kısmını çıkararak sadece dosya yolunu al
                    $path = str_replace('/storage/', '', $media->url);
                    $mobileAppSettings->link_card_1_custom_icon = $path;
                } else {
                    $mobileAppSettings->link_card_1_custom_icon = $request->filemanagersystem_link_card_1_icon;
                }
            } else {
                $mobileAppSettings->link_card_1_custom_icon = $request->filemanagersystem_link_card_1_icon;
            }
            
            \Log::info('Bağlantı Kartı 1 özel ikon kaydediliyor', [
                'original' => $request->filemanagersystem_link_card_1_icon,
                'saved' => $mobileAppSettings->link_card_1_custom_icon
            ]);
        }
        
        if ($request->filemanagersystem_link_card_2_icon) {
            // MediaPicker'dan gelen değer ID ise, gerçek dosya yolunu al
            if (is_numeric($request->filemanagersystem_link_card_2_icon)) {
                $media = \App\Models\FileManagerSystem\Media::find($request->filemanagersystem_link_card_2_icon);
                if ($media) {
                    // URL'den storage kısmını çıkararak sadece dosya yolunu al
                    $path = str_replace('/storage/', '', $media->url);
                    $mobileAppSettings->link_card_2_custom_icon = $path;
                } else {
                    $mobileAppSettings->link_card_2_custom_icon = $request->filemanagersystem_link_card_2_icon;
                }
            } else {
                $mobileAppSettings->link_card_2_custom_icon = $request->filemanagersystem_link_card_2_icon;
            }
            
            \Log::info('Bağlantı Kartı 2 özel ikon kaydediliyor', [
                'original' => $request->filemanagersystem_link_card_2_icon,
                'saved' => $mobileAppSettings->link_card_2_custom_icon
            ]);
        }
        
        if ($request->filemanagersystem_link_card_3_icon) {
            // MediaPicker'dan gelen değer ID ise, gerçek dosya yolunu al
            if (is_numeric($request->filemanagersystem_link_card_3_icon)) {
                $media = \App\Models\FileManagerSystem\Media::find($request->filemanagersystem_link_card_3_icon);
                if ($media) {
                    // URL'den storage kısmını çıkararak sadece dosya yolunu al
                    $path = str_replace('/storage/', '', $media->url);
                    $mobileAppSettings->link_card_3_custom_icon = $path;
                } else {
                    $mobileAppSettings->link_card_3_custom_icon = $request->filemanagersystem_link_card_3_icon;
                }
            } else {
                $mobileAppSettings->link_card_3_custom_icon = $request->filemanagersystem_link_card_3_icon;
            }
            
            \Log::info('Bağlantı Kartı 3 özel ikon kaydediliyor', [
                'original' => $request->filemanagersystem_link_card_3_icon,
                'saved' => $mobileAppSettings->link_card_3_custom_icon
            ]);
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
            'card2_image' => 'nullable|string', // FileManagerSystem ile gelecek path
            'card2_url' => 'nullable|string|max:255',
            'logo_title' => 'nullable|string|max:255',
            'logo_image' => 'nullable|string', // FileManagerSystem ile gelecek path
            'logo_bg_color' => 'nullable|string|max:30',
        ]);
        
        try {
            // Form verilerini logla
            \Log::info('Logo ve planlar kayıt isteği', [
                'request_data' => $request->all(),
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent()
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
            
            // Debug bilgisi ekleyelim
            \Log::info('Logo ve planlar güncelleniyor', [
                'card2_image' => $request->card2_image,
                'logo_image' => $request->logo_image,
            ]);
            
            // Kart 2 görseli güncelle - FileManagerSystem
            if ($request->filled('card2_image')) {
                // Gelen URL'yi temizle
                $card2Image = $request->card2_image;
                
                // URL'yi temizle - dış kaynaklı URL'leri ve gereksiz kısımları kaldır
                if (filter_var($card2Image, FILTER_VALIDATE_URL)) {
                    // Eğer tam URL ise
                    $urlParts = parse_url($card2Image);
                    $pathName = $urlParts['path'] ?? '';
                    
                    // /storage/ kısmını da çıkar (eğer varsa)
                    if (strpos($pathName, '/storage/') !== false) {
                        $card2Image = explode('/storage/', $pathName)[1] ?? $pathName;
                    } else {
                        // Eğer /storage/ yoksa, / ile başlayan path'i temizle
                        $card2Image = ltrim($pathName, '/');
                    }
                } else if (strpos($card2Image, '/storage/') === 0) {
                    // Sadece /storage/ ile başlıyorsa, bu kısmı çıkar
                    $card2Image = substr($card2Image, strlen('/storage/'));
                }
                
                // Temizlenmiş URL'yi kaydet
                $logoPlans->card2_image = $card2Image;
                
                \Log::info('Card2 image kaydedildi', [
                    'original_input' => $request->card2_image,
                    'cleaned_path' => $card2Image
                ]);
            }
            
            // Logo görseli güncelle - FileManagerSystem
            if ($request->filled('logo_image')) {
                // Gelen URL'yi temizle
                $logoImage = $request->logo_image;
                
                // URL'yi temizle - dış kaynaklı URL'leri ve gereksiz kısımları kaldır
                if (filter_var($logoImage, FILTER_VALIDATE_URL)) {
                    // Eğer tam URL ise
                    $urlParts = parse_url($logoImage);
                    $pathName = $urlParts['path'] ?? '';
                    
                    // /storage/ kısmını da çıkar (eğer varsa)
                    if (strpos($pathName, '/storage/') !== false) {
                        $logoImage = explode('/storage/', $pathName)[1] ?? $pathName;
                    } else {
                        // Eğer /storage/ yoksa, / ile başlayan path'i temizle
                        $logoImage = ltrim($pathName, '/');
                    }
                } else if (strpos($logoImage, '/storage/') === 0) {
                    // Sadece /storage/ ile başlıyorsa, bu kısmı çıkar
                    $logoImage = substr($logoImage, strlen('/storage/'));
                }
                
                // Temizlenmiş URL'yi kaydet
                $logoPlans->logo_image = $logoImage;
                
                \Log::info('Logo image kaydedildi', [
                    'original_input' => $request->logo_image,
                    'cleaned_path' => $logoImage
                ]);
            }
            
            // Modeli kaydetmeden önce durumu kontrol et
            \Log::info('Kaydetmeden önce LogoPlanSettings modeli', [
                'id' => $logoPlans->id,
                'card1_title' => $logoPlans->card1_title,
                'card1_icon' => $logoPlans->card1_icon,
                'card1_url' => $logoPlans->card1_url,
                'card2_title' => $logoPlans->card2_title,
                'card2_image' => $logoPlans->card2_image,
                'card2_url' => $logoPlans->card2_url,
                'logo_title' => $logoPlans->logo_title,
                'logo_image' => $logoPlans->logo_image,
                'logo_bg_color' => $logoPlans->logo_bg_color,
                'is_active' => $logoPlans->is_active,
            ]);
            
            $result = $logoPlans->save();
            
            if (!$result) {
                \Log::error('LogoPlanSettings kaydedilemedi!');
                return redirect()->route('admin.homepage.logo-and-plans')->with('error', 'Logo ve planlar kaydedilirken bir hata oluştu.');
            }
            
            \Log::info('LogoPlanSettings başarıyla kaydedildi', ['model_id' => $logoPlans->id]);
            
            return redirect()->route('admin.homepage.logo-and-plans')->with('success', 'Logo ve planlar başarıyla güncellendi.');
        } catch (\Exception $e) {
            \Log::error('Logo ve planlar güncellenirken hata oluştu: ' . $e->getMessage(), [
                'exception' => $e,
                'request_data' => $request->all()
            ]);
            
            return redirect()->route('admin.homepage.logo-and-plans')->with('error', 'Bir hata oluştu: ' . $e->getMessage());
        }
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
