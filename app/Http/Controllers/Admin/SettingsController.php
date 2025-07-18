<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    /**
     * Ayarlar ana sayfası
     */
    public function index()
    {
        $settings = Setting::whereIn('group', ['seo', 'general', 'preloader', 'security', 'file_management'])->get()->keyBy('key');
        
        return view('admin.settings.index', compact('settings'));
    }

    /**
     * SEO ayarlarını güncelle
     */
    public function updateSeo(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'homepage_title' => 'required|string|max:60',
            'homepage_description' => 'required|string|max:160',
        ], [
            'homepage_title.required' => 'Anasayfa başlığı zorunludur.',
            'homepage_title.max' => 'Anasayfa başlığı en fazla 60 karakter olabilir.',
            'homepage_description.required' => 'Anasayfa açıklaması zorunludur.',
            'homepage_description.max' => 'Anasayfa açıklaması en fazla 160 karakter olabilir.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Anasayfa title ayarını güncelle veya oluştur
        Setting::updateOrCreate(
            ['key' => 'homepage_title', 'group' => 'seo'],
            [
                'value' => $request->homepage_title,
                'display_name' => 'Anasayfa Başlığı',
                'type' => 'text',
                'description' => 'Web sitesinin anasayfa meta başlığı',
                'is_public' => true,
                'order' => 1
            ]
        );

        // Anasayfa description ayarını güncelle veya oluştur
        Setting::updateOrCreate(
            ['key' => 'homepage_description', 'group' => 'seo'],
            [
                'value' => $request->homepage_description,
                'display_name' => 'Anasayfa Açıklaması',
                'type' => 'textarea',
                'description' => 'Web sitesinin anasayfa meta açıklaması',
                'is_public' => true,
                'order' => 2
            ]
        );

        return redirect()->route('admin.settings.index')
            ->with('success', 'SEO ayarları başarıyla güncellendi.');
    }

    /**
     * Favicon yükle ve güncelle
     */
    public function updateFavicon(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'favicon' => 'required|image|mimes:png,ico,jpg,jpeg|max:2048',
        ], [
            'favicon.required' => 'Favicon dosyası seçilmelidir.',
            'favicon.image' => 'Favicon dosyası geçerli bir resim formatında olmalıdır.',
            'favicon.mimes' => 'Favicon dosyası PNG, ICO, JPG veya JPEG formatında olmalıdır.',
            'favicon.max' => 'Favicon dosyası en fazla 2MB olabilir.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // Eski favicon'u sil
            $oldFavicon = Setting::where('key', 'site_favicon')->first();
            if ($oldFavicon && $oldFavicon->value) {
                $oldPath = public_path('uploads/' . $oldFavicon->value);
                if (file_exists($oldPath)) {
                    unlink($oldPath);
                }
            }

            // uploads klasörünü oluştur (yoksa)
            $uploadDir = public_path('uploads/favicons');
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            // Yeni favicon'u yükle
            $faviconFile = $request->file('favicon');
            $faviconName = 'favicon_' . time() . '.' . $faviconFile->getClientOriginalExtension();
            $faviconPath = 'favicons/' . $faviconName;
            
            // Dosyayı uploads klasörüne taşı
            $faviconFile->move($uploadDir, $faviconName);

            // Veritabanına kaydet
            Setting::updateOrCreate(
                ['key' => 'site_favicon', 'group' => 'general'],
                [
                    'value' => $faviconPath,
                    'display_name' => 'Site Favicon',
                    'type' => 'file',
                    'description' => 'Web sitesinin favicon dosyası',
                    'is_public' => true,
                    'order' => 1
                ]
            );

            return redirect()->route('admin.settings.index')
                ->with('success', 'Favicon başarıyla yüklendi ve güncellendi.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Favicon yüklenirken bir hata oluştu: ' . $e->getMessage());
        }
    }

    /**
     * Favicon'u sil
     */
    public function deleteFavicon()
    {
        try {
            $favicon = Setting::where('key', 'site_favicon')->first();
            
            if ($favicon && $favicon->value) {
                // Dosyayı sil
                $filePath = public_path('uploads/' . $favicon->value);
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
                
                // Veritabanından sil
                $favicon->delete();
            }

            return redirect()->route('admin.settings.index')
                ->with('success', 'Favicon başarıyla silindi.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Favicon silinirken bir hata oluştu: ' . $e->getMessage());
        }
    }

    /**
     * Preloader ayarlarını güncelle
     */
    public function updatePreloader(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'preloader_enabled' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Preloader aktif/pasif ayarını güncelle veya oluştur
        Setting::updateOrCreate(
            ['key' => 'preloader_enabled', 'group' => 'preloader'],
            [
                'value' => $request->has('preloader_enabled') ? '1' : '0',
                'display_name' => 'Preloader Aktif',
                'type' => 'checkbox',
                'description' => 'Sayfa yüklenirken preloader gösterilsin mi?',
                'is_public' => true,
                'order' => 1
            ]
        );

        return redirect()->route('admin.settings.index')
            ->with('success', 'Preloader ayarları başarıyla güncellendi.');
    }

    /**
     * Session timeout ayarlarını güncelle
     */
    public function updateSessionTimeout(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'session_timeout' => 'required|integer|min:5|max:1440',
        ], [
            'session_timeout.required' => 'Oturum süresi zorunludur.',
            'session_timeout.integer' => 'Oturum süresi sayısal bir değer olmalıdır.',
            'session_timeout.min' => 'Oturum süresi en az 5 dakika olmalıdır.',
            'session_timeout.max' => 'Oturum süresi en fazla 1440 dakika (24 saat) olabilir.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Session timeout ayarını güncelle veya oluştur
        Setting::updateOrCreate(
            ['key' => 'session_timeout', 'group' => 'security'],
            [
                'value' => $request->session_timeout,
                'display_name' => 'Oturum Süresi (Dakika)',
                'type' => 'number',
                'description' => 'Admin paneli oturum zaman aşımı süresi (dakika cinsinden)',
                'is_public' => false,
                'order' => 1
            ]
        );

        return redirect()->route('admin.settings.index')
            ->with('success', 'Oturum süresi ayarları başarıyla güncellendi.');
    }

    /**
     * Dosya yükleme limiti ayarlarını güncelle
     */
    public function updateFileUploadLimit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'max_file_upload_size' => 'required|integer|min:1|max:500',
        ], [
            'max_file_upload_size.required' => 'Dosya yükleme limiti zorunludur.',
            'max_file_upload_size.integer' => 'Dosya yükleme limiti sayısal bir değer olmalıdır.',
            'max_file_upload_size.min' => 'Dosya yükleme limiti en az 1 MB olmalıdır.',
            'max_file_upload_size.max' => 'Dosya yükleme limiti en fazla 500 MB olabilir.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Dosya yükleme limiti ayarını güncelle veya oluştur
        Setting::updateOrCreate(
            ['key' => 'max_file_upload_size', 'group' => 'file_management'],
            [
                'value' => $request->max_file_upload_size,
                'display_name' => 'Maksimum Dosya Boyutu (MB)',
                'type' => 'number',
                'description' => 'Tek seferde yüklenebilecek maksimum dosya boyutu (MB cinsinden)',
                'is_public' => false,
                'order' => 1
            ]
        );

        return redirect()->route('admin.settings.index')
            ->with('success', 'Dosya yükleme limiti ayarları başarıyla güncellendi.');
    }
} 