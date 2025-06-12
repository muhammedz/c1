/**
 * Türkçe karakterleri destekleyen slug helper fonksiyonları
 * @author Çankaya Belediyesi
 */

window.SlugHelper = (function() {
    'use strict';
    
    // Türkçe karakter dönüşüm haritası
    const turkishCharMap = {
        'ğ': 'g', 'Ğ': 'G',
        'ü': 'u', 'Ü': 'U', 
        'ş': 's', 'Ş': 'S',
        'ı': 'i', 'İ': 'I',
        'ö': 'o', 'Ö': 'O',
        'ç': 'c', 'Ç': 'C'
    };

    /**
     * Türkçe karakterleri destekleyen slug oluşturur
     * @param {string} text - Slug'a dönüştürülecek metin
     * @param {string} separator - Ayırıcı karakter (varsayılan: '-')
     * @returns {string} Slug
     */
    function createSlug(text, separator = '-') {
        if (!text || typeof text !== 'string') {
            return '';
        }
        
        // Türkçe karakterleri dönüştür
        for (const [turkishChar, latinChar] of Object.entries(turkishCharMap)) {
            text = text.replace(new RegExp(turkishChar, 'g'), latinChar);
        }
        
        return text
            .toString()
            .toLowerCase()
            .trim()
            .replace(/\s+/g, separator)           // Boşlukları ayırıcı ile değiştir
            .replace(/[^a-z0-9\-_]/g, '')        // Sadece alfanümerik, tire ve alt çizgi
            .replace(/[\-_]+/g, separator)       // Birden fazla ayırıcıyı tek ayırıcıya dönüştür
            .replace(new RegExp('^[\\-_]+'), '') // Baştaki ayırıcıları kaldır
            .replace(new RegExp('[\\-_]+$'), '');// Sondaki ayırıcıları kaldır
    }

    /**
     * Form input'una slug otomatik oluşturma işlevselliği ekler
     * @param {string} sourceSelector - Kaynak input seçicisi
     * @param {string} targetSelector - Hedef slug input seçicisi
     * @param {string} previewSelector - Önizleme element seçicisi (opsiyonel)
     */
    function autoSlug(sourceSelector, targetSelector, previewSelector = null) {
        const sourceInput = document.querySelector(sourceSelector);
        const targetInput = document.querySelector(targetSelector);
        const previewElement = previewSelector ? document.querySelector(previewSelector) : null;
        
        if (!sourceInput || !targetInput) {
            console.warn('SlugHelper: Kaynak veya hedef element bulunamadı');
            return;
        }
        
        let isManuallyChanged = false;
        
        // Kaynak input değiştiğinde
        sourceInput.addEventListener('input', function() {
            if (!isManuallyChanged || targetInput.value === '') {
                const slug = createSlug(this.value);
                targetInput.value = slug;
                
                if (previewElement) {
                    previewElement.textContent = slug || '-';
                }
            }
        });
        
        // Hedef input manuel değiştirildiğinde
        targetInput.addEventListener('input', function() {
            isManuallyChanged = true;
            const slug = createSlug(this.value);
            this.value = slug;
            
            if (previewElement) {
                previewElement.textContent = slug || '-';
            }
        });
        
        // Slug yenileme butonu varsa
        const regenerateButton = document.querySelector(targetSelector + '-regenerate');
        if (regenerateButton) {
            regenerateButton.addEventListener('click', function() {
                const slug = createSlug(sourceInput.value);
                targetInput.value = slug;
                isManuallyChanged = false;
                
                if (previewElement) {
                    previewElement.textContent = slug || '-';
                }
            });
        }
    }

    /**
     * jQuery uyumluluğu için
     */
    if (typeof $ !== 'undefined') {
        $.fn.autoSlug = function(targetSelector, previewSelector = null) {
            return this.each(function() {
                autoSlug('#' + this.id, targetSelector, previewSelector);
            });
        };
    }

    // Public API
    return {
        create: createSlug,
        autoSlug: autoSlug,
        turkishCharMap: turkishCharMap
    };
})();

// Global olarak erişilebilir fonksiyon (geriye uyumluluk için)
window.createSlug = window.SlugHelper.create;
window.slugify = window.SlugHelper.create; 