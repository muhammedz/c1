<?php

namespace App\Helpers;

use App\Models\Setting;

class SeoHelper
{
    /**
     * Anasayfa SEO başlığını getir
     *
     * @return string
     */
    public static function getHomepageTitle()
    {
        return Setting::get('homepage_title', 'Çankaya Belediyesi - Resmi Web Sitesi');
    }

    /**
     * Anasayfa SEO açıklamasını getir
     *
     * @return string
     */
    public static function getHomepageDescription()
    {
        return Setting::get('homepage_description', 'Çankaya Belediyesi resmi web sitesi. Hizmetlerimiz, duyurularımız, projelerimiz ve etkinliklerimiz hakkında güncel bilgilere ulaşın.');
    }

    /**
     * Sayfa başlığı oluştur
     *
     * @param string|null $pageTitle
     * @return string
     */
    public static function generateTitle($pageTitle = null)
    {
        $siteTitle = self::getHomepageTitle();
        
        if ($pageTitle) {
            return $pageTitle . ' - ' . $siteTitle;
        }
        
        return $siteTitle;
    }

    /**
     * Meta açıklama oluştur
     *
     * @param string|null $description
     * @return string
     */
    public static function generateDescription($description = null)
    {
        if ($description) {
            return $description;
        }
        
        return self::getHomepageDescription();
    }

    /**
     * Tüm SEO ayarlarını getir
     *
     * @return array
     */
    public static function getAllSeoSettings()
    {
        return [
            'homepage_title' => self::getHomepageTitle(),
            'homepage_description' => self::getHomepageDescription(),
        ];
    }
} 