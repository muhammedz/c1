/**
 * Font Awesome Icon Picker
 * Simple icon picker for Font Awesome icons
 */

// Global setupIconPickers fonksiyonu
window.setupIconPickers = function() {
    console.log('Setting up icon pickers globally');
    
    document.querySelectorAll('.icon-picker').forEach(function(input) {
        // Eğer zaten işlenmişse atla
        if (input.closest('.icon-picker-wrapper')) {
            return;
        }
        
        const inputVal = input.value;
        
        // İnput'u wrap et
        const wrapper = document.createElement('div');
        wrapper.className = 'input-group icon-picker-wrapper';
        input.parentNode.insertBefore(wrapper, input);
        wrapper.appendChild(input);
        
        // İkon önizleme elementi
        const iconPreview = document.createElement('div');
        iconPreview.className = 'input-group-prepend icon-preview';
        iconPreview.innerHTML = `
            <span class="input-group-text" style="padding: 0.5rem 1rem;">
                <i class="${inputVal ? 'fas fa-' + inputVal : 'fas fa-icons'}" style="font-size: 24px;"></i>
            </span>
        `;
        
        // İkon seç butonu
        const selectButton = document.createElement('div');
        selectButton.className = 'input-group-append';
        selectButton.innerHTML = `
            <button class="btn btn-outline-secondary icon-picker-btn" type="button">
                İkon Seç
            </button>
        `;
        
        // Elementleri ekleme
        wrapper.insertBefore(iconPreview, input);
        wrapper.appendChild(selectButton);
    });
};
 
// Önce DOM yüklendiğinde çalışacak kod
document.addEventListener('DOMContentLoaded', function() {
    // Modal HTML'ini ekle
    if (!document.getElementById('iconPickerModal')) {
        const modalHTML = `
            <div class="modal fade" id="iconPickerModal" tabindex="-1" role="dialog" aria-labelledby="iconPickerModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="iconPickerModalLabel">İkon Seçici</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                                        </div>
                                        <input type="text" class="form-control" id="iconSearch" placeholder="İkon ara...">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                        <label class="btn btn-outline-primary active">
                                            <input type="radio" name="iconType" value="fas" checked> Solid
                                        </label>
                                        <label class="btn btn-outline-primary">
                                            <input type="radio" name="iconType" value="far"> Regular
                                        </label>
                                        <label class="btn btn-outline-primary">
                                            <input type="radio" name="iconType" value="fab"> Brand
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="icons-container" style="max-height: 400px; overflow-y: auto;">
                                <div class="row" id="iconsGrid"></div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">İptal</button>
                        </div>
                    </div>
                </div>
            </div>
        `;
        const modalContainer = document.createElement('div');
        modalContainer.innerHTML = modalHTML;
        document.body.appendChild(modalContainer.firstElementChild);
    }
    
    // İkon Picker başlat
    initIconPicker();
});

// Font Awesome ikon listesi
const faIcons = {
    solid: [
        'address-book', 'address-card', 'adjust', 'anchor', 'archive', 'at', 'baby', 'balance-scale', 'ban', 'bars',
        'bell', 'bicycle', 'binoculars', 'birthday-cake', 'bolt', 'book', 'bookmark', 'box', 'briefcase',
        'building', 'bullhorn', 'bullseye', 'bus', 'calculator', 'calendar', 'camera', 'car', 'cart-plus', 'certificate',
        'chart-bar', 'chart-line', 'chart-pie', 'check', 'check-circle', 'check-square', 'circle', 'clipboard',
        'clock', 'clone', 'cloud', 'cog', 'comment', 'comment-alt', 'comments', 'compass', 'copy', 'credit-card',
        'crown', 'database', 'desktop', 'download', 'edit', 'ellipsis-h', 'ellipsis-v', 'envelope', 'envelope-open',
        'exchange-alt', 'exclamation', 'exclamation-circle', 'exclamation-triangle', 'external-link-alt', 'eye',
        'eye-slash', 'file', 'file-alt', 'file-archive', 'file-audio', 'file-code', 'file-excel', 'file-image',
        'file-pdf', 'file-powerpoint', 'file-video', 'file-word', 'filter', 'flag', 'folder', 'folder-open',
        'frown', 'futbol', 'gem', 'gift', 'globe', 'graduation-cap', 'hand-point-down', 'hand-point-left',
        'hand-point-right', 'hand-point-up', 'handshake', 'hashtag', 'heart', 'history', 'home', 'hospital',
        'hourglass', 'image', 'images', 'inbox', 'info', 'info-circle', 'key', 'keyboard', 'language', 'laptop',
        'leaf', 'lemon', 'life-ring', 'lightbulb', 'link', 'list', 'list-alt', 'list-ol', 'list-ul', 'lock',
        'lock-open', 'long-arrow-alt-down', 'long-arrow-alt-left', 'long-arrow-alt-right', 'long-arrow-alt-up',
        'map', 'map-marker', 'map-marker-alt', 'map-pin', 'medal', 'meh', 'microphone', 'minus', 'minus-circle',
        'minus-square', 'mobile', 'mobile-alt', 'money-bill', 'moon', 'motorcycle', 'newspaper', 'paperclip',
        'paste', 'pause', 'pause-circle', 'pen', 'pen-square', 'pencil-alt', 'percentage', 'phone', 'phone-alt',
        'plane', 'play', 'play-circle', 'plus', 'plus-circle', 'plus-square', 'power-off', 'print', 'question',
        'question-circle', 'redo', 'redo-alt', 'reply', 'reply-all', 'save', 'search', 'search-minus', 'search-plus',
        'share', 'share-alt', 'shield-alt', 'shopping-bag', 'shopping-basket', 'shopping-cart', 'sign-in-alt',
        'sign-out-alt', 'signal', 'sitemap', 'sliders-h', 'smile', 'sort', 'sort-alpha-down', 'sort-alpha-up',
        'sort-amount-down', 'sort-amount-up', 'sort-down', 'sort-up', 'spinner', 'star', 'star-half',
        'step-backward', 'step-forward', 'sticky-note', 'stop', 'stop-circle', 'store', 'sync', 'table', 'tag',
        'tags', 'tasks', 'text-height', 'text-width', 'th', 'th-large', 'th-list', 'thumbs-down', 'thumbs-up',
        'times', 'times-circle', 'tint', 'toggle-off', 'toggle-on', 'tools', 'trash', 'trash-alt', 'trophy',
        'truck', 'undo', 'undo-alt', 'unlock', 'unlock-alt', 'upload', 'user', 'user-circle', 'user-plus',
        'user-minus', 'user-times', 'users', 'utensil-spoon', 'utensils', 'video', 'volume-down', 'volume-mute',
        'volume-off', 'volume-up', 'wheelchair', 'wifi', 'wrench', 
        
        // Erişilebilirlik İkonları
        'universal-access', 'blind', 'deaf', 'low-vision', 'american-sign-language-interpreting',
        'assistive-listening-systems', 'audio-description', 'closed-captioning', 'wheelchair-alt',
        'tty', 'sign-language', 'accessible-icon', 'hands-helping', 'braille',
        
        // Tıbbi İkonlar
        'ambulance', 'h-square', 'plus-square', 'stethoscope', 'pills', 'thermometer', 'procedure', 'medkit',
        'first-aid', 'heart', 'heartbeat', 'notes-medical', 'hospital-alt', 'prescription',
        
        // Belediye/Kamu İkonları
        'city', 'university', 'school', 'building', 'landmark', 'monument', 'archway', 'bridge',
        'road', 'traffic-light', 'street-view', 'hotel', 'store', 'store-alt', 'store-alt-slash',
        'swimming-pool', 'water', 'house-user', 'house', 'home', 'park',
        
        // Sosyal/İnsan İkonları
        'child', 'female', 'male', 'restroom', 'people-arrows', 'people-carry', 'person-booth',
        'user-friends', 'users', 'baby', 'baby-carriage', 'walking', 'running', 'hiking',
        'biking', 'skating', 'skiing', 'swimming', 'praying-hands'
    ],
    regular: [
        'address-book', 'address-card', 'bell', 'bookmark', 'calendar', 'calendar-alt', 'chart-bar',
        'clock', 'clone', 'comment', 'comment-alt', 'comments', 'compass', 'copy', 'credit-card',
        'envelope', 'envelope-open', 'eye', 'file', 'file-alt', 'file-archive', 'file-audio',
        'file-code', 'file-excel', 'file-image', 'file-pdf', 'file-powerpoint', 'file-video',
        'file-word', 'flag', 'folder', 'folder-open', 'frown', 'heart', 'hospital', 'hourglass',
        'image', 'images', 'keyboard', 'lemon', 'life-ring', 'lightbulb', 'map', 'meh', 'minus-square',
        'money-bill-alt', 'moon', 'newspaper', 'object-group', 'paper-plane', 'pause-circle',
        'play-circle', 'plus-square', 'question-circle', 'save', 'share-square', 'smile',
        'star', 'star-half', 'sticky-note', 'stop-circle', 'sun', 'thumbs-down', 'thumbs-up',
        'times-circle', 'user', 'user-circle', 'window-close', 'window-maximize', 'window-minimize',
        'window-restore'
    ],
    brands: [
        'accessible-icon', '500px', 'adn', 'amazon', 'android', 'angellist', 'apple', 'behance', 'behance-square', 'bitbucket', 'bitcoin',
        'blackberry', 'btc', 'buysellads', 'cc-amex', 'cc-diners-club', 'cc-discover', 'cc-jcb',
        'cc-mastercard', 'cc-paypal', 'cc-stripe', 'cc-visa', 'chrome', 'codepen', 'connectdevelop',
        'css3', 'dashcube', 'delicious', 'deviantart', 'digg', 'dribbble', 'dropbox', 'drupal',
        'edge', 'etsy', 'expeditedssl', 'facebook', 'facebook-f', 'facebook-messenger',
        'facebook-square', 'firefox', 'flickr', 'flipboard', 'foursquare', 'free-code-camp',
        'github', 'github-alt', 'github-square', 'gitlab', 'gitter', 'glide', 'glide-g', 'google',
        'google-drive', 'google-play', 'google-plus', 'google-plus-g', 'google-plus-square',
        'google-wallet', 'gratipay', 'grav', 'hacker-news', 'houzz', 'html5', 'imdb', 'instagram',
        'internet-explorer', 'ioxhost', 'itunes', 'itunes-note', 'jenkins', 'joomla', 'jsfiddle',
        'lastfm', 'lastfm-square', 'leanpub', 'linkedin', 'linkedin-in', 'linux', 'maxcdn', 'medium',
        'meetup', 'microsoft', 'odnoklassniki', 'odnoklassniki-square', 'opencart', 'openid',
        'opera', 'optin-monster', 'pagelines', 'patreon', 'paypal', 'periscope', 'phabricator',
        'phoenix-framework', 'php', 'pinterest', 'pinterest-p', 'pinterest-square', 'playstation',
        'product-hunt', 'qq', 'quora', 'ravelry', 'rebel', 'reddit', 'reddit-alien', 'reddit-square',
        'renren', 'safari', 'sass', 'schlix', 'scribd', 'searchengin', 'sellcast', 'sellsy', 'servicestack',
        'shirtsinbulk', 'simplybuilt', 'sistrix', 'skyatlas', 'skype', 'slack', 'slack-hash', 'slideshare',
        'snapchat', 'snapchat-ghost', 'snapchat-square', 'soundcloud', 'speakap', 'spotify', 'stack-exchange',
        'stack-overflow', 'staylinked', 'steam', 'steam-square', 'steam-symbol', 'sticker-mule', 'strava',
        'stripe', 'stripe-s', 'studiovinari', 'stumbleupon', 'stumbleupon-circle', 'superpowers', 'telegram',
        'telegram-plane', 'tencent-weibo', 'themeisle', 'trello', 'tripadvisor', 'tumblr', 'tumblr-square',
        'twitch', 'twitter', 'twitter-square', 'typo3', 'uber', 'uikit', 'uniregistry', 'untappd', 'usb',
        'viacoin', 'viadeo', 'viadeo-square', 'viber', 'vimeo', 'vimeo-square', 'vimeo-v', 'vine', 'vk', 'vnv', 'vuejs', 'watchman-monitoring',
        'waze', 'weebly', 'weibo', 'weixin', 'whatsapp', 'whatsapp-square', 'whmcs', 'wikipedia-w', 'windows',
        'wix', 'wizards-of-the-coast', 'wodu', 'wolf-pack-battalion', 'wordpress', 'wordpress-simple', 'wpbeginner',
        'wpexplorer', 'wpforms', 'wpressr', 'xbox', 'xing', 'xing-square', 'y-combinator', 'yahoo', 'yammer',
        'yandex', 'yandex-international', 'yarn', 'yelp', 'yoast', 'youtube', 'youtube-square', 'zhihu'
    ]
};

// Aktif hedef Input
let activeIconInput = null;

// İkon seçici başlatma
function initIconPicker() {
    console.log('Icon Picker Initialized');
    
    // İkon picker input'ları hazırla
    window.setupIconPickers();
    
    // İkon arama işlevi
    document.getElementById('iconSearch').addEventListener('keyup', function() {
        const searchText = this.value.toLowerCase();
        
        document.querySelectorAll('.icon-item').forEach(function(item) {
            const iconName = item.getAttribute('data-icon').toLowerCase();
            if (iconName.includes(searchText)) {
                item.parentElement.style.display = '';
            } else {
                item.parentElement.style.display = 'none';
            }
        });
    });
    
    // İkon tipi değiştirme
    document.querySelectorAll('input[name="iconType"]').forEach(function(radio) {
        radio.addEventListener('change', function() {
            if (this.checked) {
                renderIconsGrid(this.value);
            }
        });
    });
    
    // İlk kez ikon gridini oluştur
    renderIconsGrid('fas');
    
    // İkon tıklama olaylarını delegasyon ile ekle
    document.getElementById('iconsGrid').addEventListener('click', function(e) {
        const iconItem = e.target.closest('.icon-item');
        if (iconItem) {
            const icon = iconItem.getAttribute('data-icon');
            const prefix = iconItem.getAttribute('data-prefix');
            selectIcon(icon, prefix);
        }
    });
    
    // Sayfa genelinde İkon Seç butonlarını dinle
    document.addEventListener('click', function(e) {
        if (e.target.matches('.icon-picker-btn') || e.target.closest('.icon-picker-btn')) {
            const btn = e.target.matches('.icon-picker-btn') ? e.target : e.target.closest('.icon-picker-btn');
            const wrapper = btn.closest('.icon-picker-wrapper');
            
            if (wrapper) {
                const input = wrapper.querySelector('.icon-picker');
                if (input) {
                    activeIconInput = input;
                    
                    // İlgili ikon tipine göre grid'i yeniden oluştur
                    const iconType = document.querySelector('input[name="iconType"]:checked')?.value || 'fas';
                    renderIconsGrid(iconType);
                    
                    // Modal'ı göster
                    $('#iconPickerModal').modal('show');
                }
            }
        }
    });
    
    // SVG yükleme özelliğini kurulum
    setupSvgUpload();
}

// İkon seçimi gerçekleştiğinde
function selectIcon(icon, prefix) {
    console.log('Selected Icon:', icon, 'Prefix:', prefix);
    
    if (!activeIconInput) {
        console.error('No active input found');
        return;
    }
    
    try {
        // İnput değerini ayarla - ikon adını ve ön ekini birlikte kaydet
        const fullIconClass = prefix + ' fa-' + icon;
        activeIconInput.value = fullIconClass;
        console.log('Setting input value to:', fullIconClass);
        
        // Önizleme ikonunu güncelle
        const wrapper = activeIconInput.closest('.icon-picker-wrapper');
        const previewSpan = wrapper.querySelector('.icon-preview span');
        
        if (previewSpan) {
            previewSpan.innerHTML = `<i class="${fullIconClass} fa-2x"></i>`;
        }
        
        // İkon değerinin doğru şekilde set edildiğinden emin olmak için jQuery kullanarak da değeri ata
        if (window.jQuery) {
            $(activeIconInput).val(fullIconClass);
            console.log('Setting value again with jQuery:', fullIconClass);
            
            // Input değişikliğini tetikle
            $(activeIconInput).trigger('change');
        } else {
            // Vanilla JS ile değişiklik olayını tetikle
            const event = new Event('change', { bubbles: true });
            activeIconInput.dispatchEvent(event);
        }
        
        // Verinin inputa doğru atandığından emin olmak için hemen tekrar kontrol et
        console.log('Value immediately after setting:', activeIconInput.value);
        
        // Eğer boşsa, yeniden atamayı dene
        if (!activeIconInput.value && fullIconClass) {
            setTimeout(() => {
                activeIconInput.value = fullIconClass;
                console.log('Value set after initial delay:', activeIconInput.value);
                
                if (window.jQuery) {
                    $(activeIconInput).val(fullIconClass);
                }
                
                // Gizli bir <input type="hidden"> oluştur
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = 'icon_backup';
                hiddenInput.value = fullIconClass;
                activeIconInput.parentNode.appendChild(hiddenInput);
                console.log('Created backup hidden input with value:', fullIconClass);
            }, 100);
        }
    } catch (error) {
        console.error('Error in selectIcon function:', error);
    }
    
    // Modal'ı kapat
    $('#iconPickerModal').modal('hide');
    
    // Modal kapandığında activeIconInput referansını sıfırla
    $('#iconPickerModal').on('hidden.bs.modal', function () {
        console.log('Modal kapandı, son icon değeri:', activeIconInput ? activeIconInput.value : 'null');
        activeIconInput = null;
        $(this).off('hidden.bs.modal'); // One-time event listener
    });
}

// İkon girdilerini hazırlama
function setupIconPickers() {
    console.log('Setting up icon pickers');
    
    document.querySelectorAll('.icon-picker').forEach(function(input) {
        // Eğer zaten işlenmişse atla
        if (input.closest('.icon-picker-wrapper')) {
            return;
        }
        
        const inputVal = input.value;
        
        // İnput'u wrap et
        const wrapper = document.createElement('div');
        wrapper.className = 'input-group icon-picker-wrapper';
        input.parentNode.insertBefore(wrapper, input);
        wrapper.appendChild(input);
        
        // İkon önizleme elementi
        const iconPreview = document.createElement('div');
        iconPreview.className = 'input-group-prepend icon-preview';
        iconPreview.innerHTML = `
            <span class="input-group-text" style="padding: 0.5rem 1rem;">
                <i class="${inputVal ? 'fas fa-' + inputVal : 'fas fa-icons'}" style="font-size: 24px;"></i>
            </span>
        `;
        
        // İkon seç butonu
        const selectButton = document.createElement('div');
        selectButton.className = 'input-group-append';
        selectButton.innerHTML = `
            <button class="btn btn-outline-secondary icon-picker-btn" type="button">
                İkon Seç
            </button>
        `;
        
        // Elementleri ekleme
        wrapper.insertBefore(iconPreview, input);
        wrapper.appendChild(selectButton);
    });
}

// İkon gridini oluştur
function renderIconsGrid(iconType) {
    console.log('Rendering icon grid for:', iconType);
    
    let iconsArray;
    let prefix;
    
    if (iconType === 'fas') {
        iconsArray = faIcons.solid;
        prefix = 'fas';
    } else if (iconType === 'far') {
        iconsArray = faIcons.regular;
        prefix = 'far';
    } else if (iconType === 'fab') {
        iconsArray = faIcons.brands;
        prefix = 'fab';
    } else {
        iconsArray = faIcons.solid;
        prefix = 'fas';
    }
    
    const iconsGrid = document.getElementById('iconsGrid');
    iconsGrid.innerHTML = '';
    
    iconsArray.forEach(function(icon) {
        const iconCol = document.createElement('div');
        iconCol.className = 'col-md-3 col-sm-4 col-6 text-center mb-3';
        
        iconCol.innerHTML = `
            <div class="icon-item p-2 border rounded" data-icon="${icon}" data-prefix="${prefix}">
                <i class="${prefix} fa-${icon} fa-2x mb-2"></i>
                <div class="icon-name small text-truncate">${icon}</div>
            </div>
        `;
        
        iconsGrid.appendChild(iconCol);
    });
}

// SVG yükleme işlevini kurma
function setupSvgUpload() {
    // Icon Picker modal'a SVG yükleme bölümü ekle
    if (!document.getElementById('svg-upload-section')) {
        const svgUploadHTML = `
            <div id="svg-upload-section" class="mt-4 border-top pt-3">
                <h6>Özel SVG İkon Yükle</h6>
                <div class="custom-file mb-2">
                    <input type="file" class="custom-file-input" id="svg-file-upload" accept=".svg">
                    <label class="custom-file-label" for="svg-file-upload">SVG dosyası seçin...</label>
                </div>
                <div id="svg-preview-container" class="mt-2 text-center d-none">
                    <div class="mb-2" id="svg-preview"></div>
                    <button type="button" class="btn btn-sm btn-primary" id="use-svg-button">Bu SVG'yi Kullan</button>
                </div>
            </div>
        `;
        
        const modalBody = document.querySelector('#iconPickerModal .modal-body');
        if (modalBody) {
            modalBody.insertAdjacentHTML('beforeend', svgUploadHTML);
            
            // SVG dosya yükleme olayını dinle
            const svgFileInput = document.getElementById('svg-file-upload');
            if (svgFileInput) {
                svgFileInput.addEventListener('change', handleSvgFileUpload);
                
                // Custom-file input için dosya adı gösterim özelliği
                svgFileInput.addEventListener('change', function(e) {
                    const fileName = e.target.files[0]?.name || 'SVG dosyası seçin...';
                    const label = e.target.nextElementSibling;
                    if (label) {
                        label.textContent = fileName;
                    }
                });
                
                // SVG kullanma butonu olayını dinle
                document.getElementById('use-svg-button')?.addEventListener('click', function() {
                    const svgContent = document.getElementById('svg-preview')?.innerHTML;
                    if (svgContent && activeIconInput) {
                        activeIconInput.value = svgContent;
                        
                        // Önizleme ikonunu güncelle
                        const wrapper = activeIconInput.closest('.icon-picker-wrapper');
                        const previewIcon = wrapper.querySelector('.icon-preview span');
                        
                        if (previewIcon) {
                            previewIcon.innerHTML = svgContent;
                        }
                        
                        // Change event tetikle
                        const event = new Event('change', { bubbles: true });
                        activeIconInput.dispatchEvent(event);
                        
                        // Modal'ı kapat
                        $('#iconPickerModal').modal('hide');
                    }
                });
            }
        }
    }
}

// SVG Dosya yükleme işleyicisi
function handleSvgFileUpload(e) {
    const file = e.target.files[0];
    if (file && file.type === 'image/svg+xml') {
        const reader = new FileReader();
        
        reader.onload = function(event) {
            const svgContent = event.target.result;
            
            // SVG içeriğini güvenli hale getir
            const sanitizedSvg = sanitizeSvg(svgContent);
            
            // Önizleme konteynerini göster
            const previewContainer = document.getElementById('svg-preview-container');
            const svgPreview = document.getElementById('svg-preview');
            
            if (previewContainer && svgPreview) {
                previewContainer.classList.remove('d-none');
                svgPreview.innerHTML = sanitizedSvg;
                
                // SVG boyutlandırma
                const svgElement = svgPreview.querySelector('svg');
                if (svgElement) {
                    svgElement.setAttribute('width', '48');
                    svgElement.setAttribute('height', '48');
                    svgElement.style.maxWidth = '100%';
                }
            }
        };
        
        reader.readAsText(file);
    } else {
        alert('Lütfen geçerli bir SVG dosyası seçin.');
        e.target.value = '';
    }
}

// SVG içeriğini sanitize etme
function sanitizeSvg(svgContent) {
    // Basit bir sanitizasyon: script tag'lerini kaldır
    let sanitized = svgContent.replace(/<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/gi, '');
    
    // onclick, onload gibi event handler'ları kaldır
    sanitized = sanitized.replace(/\son\w+\s*=\s*["'][^"']*["']/gi, '');
    
    return sanitized;
} 