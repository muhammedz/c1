<?php

namespace App\Services\FileManagerSystem;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;

class FilemanagersystemService
{
    /**
     * Dosya yönetim sistemi ayarlarını yükler.
     *
     * @return array
     */
    public function loadConfig()
    {
        return [
            'allowed_file_types' => Config::get('filemanagersystem.allowed_file_types', []),
            'max_file_size' => Config::get('filemanagersystem.max_file_size', 0),
            'storage_path' => Config::get('filemanagersystem.storage_path', 'uploads'),
            'thumbnail_sizes' => Config::get('filemanagersystem.thumbnail_sizes', []),
            'default_folder' => Config::get('filemanagersystem.default_folder', 'uploads'),
        ];
    }

    /**
     * Dosya yönetim sistemi ayarlarını günceller.
     *
     * @param array $settings
     * @return void
     */
    public function updateConfig(array $settings)
    {
        foreach ($settings as $key => $value) {
            Config::set('filemanagersystem.' . $key, $value);
        }
    }

    /**
     * Kullanıcının dosya yönetim sistemi yetkilerini kontrol eder.
     *
     * @param string $permission
     * @return bool
     */
    public function hasPermission(string $permission)
    {
        $user = auth()->user();
        
        if (!$user) {
            return false;
        }

        // Admin her şeye erişebilir
        if ($user->hasRole('admin')) {
            return true;
        }

        // Rol bazlı yetkilendirme
        switch ($permission) {
            case 'upload':
                return $user->hasRole(['editor', 'contributor']);
            case 'delete':
                return $user->hasRole('editor');
            case 'manage_folders':
                return $user->hasRole('editor');
            case 'manage_categories':
                return $user->hasRole('editor');
            case 'view':
                return $user->hasRole(['editor', 'contributor', 'viewer']);
            default:
                return false;
        }
    }

    /**
     * Dosya yönetim sisteminin durumunu kontrol eder.
     *
     * @return array
     */
    public function checkSystemStatus()
    {
        $status = [
            'storage' => [
                'writable' => is_writable(Storage::disk('public')->path('')),
                'free_space' => disk_free_space(Storage::disk('public')->path('')),
                'total_space' => disk_total_space(Storage::disk('public')->path('')),
            ],
            'thumbnails' => [
                'writable' => is_writable(Storage::disk('public')->path('thumbnails')),
                'exists' => Storage::disk('public')->exists('thumbnails'),
            ],
            'config' => [
                'valid' => $this->validateConfig(),
            ],
        ];

        return $status;
    }

    /**
     * Dosya yönetim sistemi yapılandırmasını doğrular.
     *
     * @return bool
     */
    private function validateConfig()
    {
        $config = $this->loadConfig();

        // Gerekli ayarların varlığını kontrol et
        $required = ['allowed_file_types', 'max_file_size', 'storage_path', 'thumbnail_sizes', 'default_folder'];
        foreach ($required as $key) {
            if (!isset($config[$key])) {
                return false;
            }
        }

        // Dosya tiplerinin geçerli olduğunu kontrol et
        foreach ($config['allowed_file_types'] as $type) {
            if (!preg_match('/^[a-z]+\/[a-z0-9\-\.\+]+$/', $type)) {
                return false;
            }
        }

        // Depolama yolunun geçerli olduğunu kontrol et
        if (!preg_match('/^[a-zA-Z0-9\/\-_]+$/', $config['storage_path'])) {
            return false;
        }

        // Thumbnail boyutlarının geçerli olduğunu kontrol et
        foreach ($config['thumbnail_sizes'] as $size) {
            if (!isset($size['name'], $size['width'], $size['height'])) {
                return false;
            }
            if (!is_numeric($size['width']) || !is_numeric($size['height'])) {
                return false;
            }
        }

        return true;
    }
} 