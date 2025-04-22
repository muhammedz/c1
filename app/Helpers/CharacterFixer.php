<?php

/**
 * Karakter düzeltme işlemleri için yardımcı fonksiyonlar
 */

namespace App\Helpers;

class CharacterFixer
{
    /**
     * Türkçe karakterleri düzeltir
     *
     * @param string $text
     * @return string
     */
    public static function fixTurkishChars($text)
    {
        $search = ['ı', 'ğ', 'ü', 'ş', 'ö', 'ç', 'İ', 'Ğ', 'Ü', 'Ş', 'Ö', 'Ç'];
        $replace = ['i', 'g', 'u', 's', 'o', 'c', 'I', 'G', 'U', 'S', 'O', 'C'];
        
        return str_replace($search, $replace, $text);
    }

    /**
     * JSON için karakter kodlamasını düzeltir
     *
     * @param string $text
     * @return string
     */
    public static function fixJsonEncoding($text)
    {
        return json_encode($text, JSON_UNESCAPED_UNICODE);
    }
} 