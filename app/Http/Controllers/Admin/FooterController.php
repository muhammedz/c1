<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FooterMenu;
use App\Models\FooterSetting;
use Illuminate\Support\Facades\Storage;

class FooterController extends Controller
{
    public function index()
    {
        $menus = FooterMenu::with('activeLinks')->active()->ordered()->get();
        $settings = FooterSetting::getSettings();
        
        return view('admin.footer.index', compact('menus', 'settings'));
    }

    public function updateSettings(Request $request)
    {
        $request->validate([
            'company_name' => 'required|string|max:255',
            'company_subtitle' => 'required|string|max:255',
            'address_line1' => 'required|string|max:255',
            'address_line2' => 'required|string|max:255',
            'contact_center_title' => 'required|string|max:255',
            'contact_center_phone' => 'required|string|max:255',
            'whatsapp_title' => 'required|string|max:255',
            'whatsapp_number' => 'required|string|max:255',
            'email_title' => 'required|string|max:255',
            'email_address' => 'required|email|max:255',
            'kep_title' => 'required|string|max:255',
            'kep_address' => 'required|string|max:255',
            'copyright_left' => 'required|string',
            'copyright_right' => 'required|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        $settings = FooterSetting::first();
        if (!$settings) {
            $settings = new FooterSetting();
        }

        // Logo yükleme
        if ($request->hasFile('logo')) {
            // Eski logoyu sil
            if ($settings->logo) {
                Storage::disk('public')->delete($settings->logo);
            }
            
            $logoPath = $request->file('logo')->store('footer/logos', 'public');
            $settings->logo = $logoPath;
        }

        $settings->fill($request->except(['logo']));
        $settings->save();

        return redirect()->back()->with('success', 'Footer ayarları başarıyla güncellendi.');
    }

    public function deleteLogo()
    {
        $settings = FooterSetting::first();
        if ($settings && $settings->logo) {
            Storage::disk('public')->delete($settings->logo);
            $settings->logo = null;
            $settings->save();
        }

        return redirect()->back()->with('success', 'Logo başarıyla silindi.');
    }
}
