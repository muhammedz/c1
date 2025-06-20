<?php

namespace App\Observers;

use App\Models\Setting;
use App\Traits\LogsActivity;
use Illuminate\Support\Facades\Cache;

class SettingObserver
{
    use LogsActivity;

    /**
     * Handle the Setting "created" event.
     */
    public function created(Setting $setting): void
    {
        $this->clearSettingsCache();
        $this->createCustomLog($setting, 'created', "Yeni ayar eklendi: {$setting->key}");
    }

    /**
     * Handle the Setting "updated" event.
     */
    public function updated(Setting $setting): void
    {
        $this->clearSettingsCache();
        
        // Setting güncellemelerinde özel açıklama ekle
        $oldValue = $setting->getOriginal('value');
        $newValue = $setting->value;
        
        $description = "Ayar güncellendi: {$setting->key} (Eski: {$oldValue} → Yeni: {$newValue})";
        $this->createCustomLog($setting, 'updated', $description);
    }

    /**
     * Handle the Setting "deleted" event.
     */
    public function deleted(Setting $setting): void
    {
        $this->clearSettingsCache();
        $this->createCustomLog($setting, 'deleted', "Ayar silindi: {$setting->key}");
    }

    /**
     * Handle the Setting "restored" event.
     */
    public function restored(Setting $setting): void
    {
        $this->clearSettingsCache();
        $this->createCustomLog($setting, 'restored', "Ayar geri yüklendi: {$setting->key}");
    }

    /**
     * Handle the Setting "force deleted" event.
     */
    public function forceDeleted(Setting $setting): void
    {
        $this->clearSettingsCache();
        $this->createCustomLog($setting, 'force_deleted', "Ayar kalıcı olarak silindi: {$setting->key}");
    }

    /**
     * Settings cache'ini temizle
     */
    private function clearSettingsCache(): void
    {
        try {
            // Tüm ayarlar cache'ini temizle
            Cache::forget('settings');
            Cache::forget('site_settings');
            Cache::forget('app_settings');
            
            // Belirli ayar cache'lerini temizle
            $settingKeys = [
                'site_title',
                'site_description',
                'site_logo',
                'site_favicon',
                'contact_info',
                'social_media',
            ];
            
            foreach ($settingKeys as $key) {
                Cache::forget("setting_{$key}");
            }
        } catch (\Exception $e) {
            \Log::warning('Settings cache clearing failed: ' . $e->getMessage());
        }
    }
}
