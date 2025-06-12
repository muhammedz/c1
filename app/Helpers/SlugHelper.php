<?php

namespace App\Helpers;

use Illuminate\Support\Str;

class SlugHelper
{
    /**
     * Türkçe karakterleri Latin karakterlere dönüştürme haritası
     */
    private static array $turkishCharMap = [
        'ğ' => 'g', 'Ğ' => 'G',
        'ü' => 'u', 'Ü' => 'U', 
        'ş' => 's', 'Ş' => 'S',
        'ı' => 'i', 'İ' => 'I',
        'ö' => 'o', 'Ö' => 'O',
        'ç' => 'c', 'Ç' => 'C'
    ];

    /**
     * Türkçe karakterleri destekleyen slug oluşturur
     *
     * @param string $text
     * @param string $separator
     * @return string
     */
    public static function create(string $text, string $separator = '-'): string
    {
        // Türkçe karakterleri dönüştür
        $text = self::convertTurkishChars($text);
        
        // Laravel'in slug fonksiyonunu kullan
        return Str::slug($text, $separator);
    }

    /**
     * Türkçe karakterleri Latin karakterlere dönüştürür
     *
     * @param string $text
     * @return string
     */
    public static function convertTurkishChars(string $text): string
    {
        return str_replace(
            array_keys(self::$turkishCharMap),
            array_values(self::$turkishCharMap),
            $text
        );
    }

    /**
     * Benzersiz slug oluşturur
     *
     * @param string $text
     * @param string $model Model sınıfı
     * @param string $field Slug alanı adı
     * @param int|null $excludeId Hariç tutulacak ID (güncelleme için)
     * @param string $separator
     * @return string
     */
    public static function createUnique(
        string $text, 
        string $model, 
        string $field = 'slug', 
        ?int $excludeId = null,
        string $separator = '-'
    ): string {
        $baseSlug = self::create($text, $separator);
        $slug = $baseSlug;
        $counter = 1;

        // Model'in varlığını kontrol et
        if (!class_exists($model)) {
            return $slug;
        }

        // Benzersizlik kontrolü
        while (self::slugExists($model, $field, $slug, $excludeId)) {
            $slug = $baseSlug . $separator . $counter;
            $counter++;
        }

        return $slug;
    }

    /**
     * Slug'ın var olup olmadığını kontrol eder
     *
     * @param string $model
     * @param string $field
     * @param string $slug
     * @param int|null $excludeId
     * @return bool
     */
    private static function slugExists(string $model, string $field, string $slug, ?int $excludeId = null): bool
    {
        $query = $model::where($field, $slug);
        
        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }
        
        return $query->exists();
    }

    /**
     * JavaScript için Türkçe karakter haritasını JSON olarak döndürür
     *
     * @return string
     */
    public static function getTurkishCharMapForJs(): string
    {
        return json_encode(self::$turkishCharMap);
    }

    /**
     * JavaScript slug fonksiyonu kodunu döndürür
     *
     * @return string
     */
    public static function getJsSlugFunction(): string
    {
        $charMap = self::getTurkishCharMapForJs();
        
        return "
        function createSlug(text, separator = '-') {
            const turkishCharMap = $charMap;
            
            // Türkçe karakterleri dönüştür
            for (const [turkishChar, latinChar] of Object.entries(turkishCharMap)) {
                text = text.replace(new RegExp(turkishChar, 'g'), latinChar);
            }
            
            return text
                .toString()
                .toLowerCase()
                .trim()
                .replace(/\\s+/g, separator)           // Boşlukları ayırıcı ile değiştir
                .replace(/[^a-z0-9\\-_]/g, '')        // Sadece alfanümerik, tire ve alt çizgi
                .replace(/[\\-_]+/g, separator)       // Birden fazla ayırıcıyı tek ayırıcıya dönüştür
                .replace(new RegExp('^[\\-_]+'), '')  // Baştaki ayırıcıları kaldır
                .replace(new RegExp('[\\-_]+$'), ''); // Sondaki ayırıcıları kaldır
        }
        ";
    }
} 