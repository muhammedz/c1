<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SettingsController extends Controller
{
    /**
     * Ayarlar ana sayfası
     */
    public function index()
    {
        $settings = Setting::where('group', 'seo')->get()->keyBy('key');
        
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
} 