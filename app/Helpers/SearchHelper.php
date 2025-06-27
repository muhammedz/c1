<?php

if (!function_exists('highlightSearchTerm')) {
    /**
     * Arama terimini metinde vurgular
     *
     * @param string $text Vurgulanacak metin
     * @param string $searchTerm Arama terimi
     * @return string Vurgulanmış metin
     */
    function highlightSearchTerm($text, $searchTerm)
    {
        if (!$searchTerm || empty(trim($searchTerm))) {
            return $text;
        }
        
        $pattern = '/(' . preg_quote(trim($searchTerm), '/') . ')/iu';
        return preg_replace($pattern, '<span class="highlight">$1</span>', $text);
    }
} 