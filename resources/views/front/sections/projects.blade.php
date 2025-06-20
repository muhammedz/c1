<!-- #### Projects Section -->
@if(isset($projectSettings) && $projectSettings->is_active)
<section id="projects-section" class="projects-section bg-white w-full px-4 py-12" style="min-height: 400px; display: block !important; visibility: visible !important;">
    <div class="container max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-4 gap-8">
        <!-- Sol Kısım - Kategoriler -->
        <div id="categories-container" class="space-y-3 categories-container">
            <!-- Kategoriler (Radio butonlar olarak) -->
            @forelse($projectCategories as $category)
                <div class="category-radio-container">
                    <input type="radio" id="category-{{ $category->slug }}" name="project-category" value="{{ $category->slug }}" 
                          class="hidden" {{ $loop->first ? 'checked' : '' }}>
                    <label for="category-{{ $category->slug }}" 
                          class="category-btn {{ $loop->first ? 'active' : '' }}">
                        {{ $category->name }}
                    </label>
                </div>
            @empty
                <!-- Varsayılan kategoriler -->
                <div class="category-radio-container">
                    <input type="radio" id="category-gerceklesen" name="project-category" value="gerceklesen" 
                          class="hidden" checked>
                    <label for="category-gerceklesen" 
                          class="category-btn active">
                        Gerçekleşen Projeler
                    </label>
                </div>
                <div class="category-radio-container">
                    <input type="radio" id="category-planlanan" name="project-category" value="planlanan" 
                          class="hidden">
                    <label for="category-planlanan" 
                          class="category-btn">
                        Planlanan Projeler
                    </label>
                </div>
                <div class="category-radio-container">
                    <input type="radio" id="category-devam-eden" name="project-category" value="devam-eden" 
                          class="hidden">
                    <label for="category-devam-eden" 
                          class="category-btn">
                        Devam Eden Projeler
                    </label>
                </div>
                <div class="category-radio-container">
                    <input type="radio" id="category-uluslararasi" name="project-category" value="uluslararasi" 
                          class="hidden">
                    <label for="category-uluslararasi" 
                          class="category-btn">
                        Uluslararası Projeler
                    </label>
                </div>
                <div class="category-radio-container">
                    <input type="radio" id="category-sosyal" name="project-category" value="sosyal" 
                          class="hidden">
                    <label for="category-sosyal" 
                          class="category-btn">
                        Sosyal Projeler
                    </label>
                </div>
            @endforelse
        </div>

        <!-- Sağ Kısım - Projeler -->
        <div class="lg:col-span-3">
            <!-- Başlık ve Tümünü Gör -->
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800" id="categoryTitle" style="font-family: 'TT Norms Pro', sans-serif; font-weight: 700;">
                    @if(count($projectCategories) > 0)
                        {{ $projectCategories->first()->name }}
                    @else
                        Gerçekleşen Projeler
                    @endif
                </h2>
                <div class="flex items-center space-x-4">
                    <!-- Navigation Butonları -->
                    <div class="prev-button w-8 h-8 border border-gray-300 rounded-full flex items-center justify-center cursor-pointer transition-all duration-200 hover:bg-gray-100">
                        <svg class="w-4 h-4 text-gray-600" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </div>
                    
                    <div class="next-button w-8 h-8 border border-gray-300 rounded-full flex items-center justify-center cursor-pointer transition-all duration-200 hover:bg-gray-100">
                        <svg class="w-4 h-4 text-gray-600" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </div>
                    
                    @if($projectSettings->show_view_all_button)
                        <a href="{{ $projectSettings->view_all_url ?? route('front.projects') }}" class="text-gray-600 hover:text-gray-800 view-all-link ml-2">
                            {{ $projectSettings->view_all_text ?? 'Tümünü Gör' }}
                        </a>
                    @endif
                </div>
            </div>

            <!-- Proje Panelleri -->
            <div class="relative projects-container max-w-full">
                <!-- Kategori Panelleri -->
                @foreach($projectCategories as $category)
                    <div class="project-panel {{ !$loop->first ? 'hidden' : '' }}" data-category="{{ $category->slug }}">
                            @php
                                $categoryProjects = \App\Models\Project::where('category_id', $category->id)
                                    ->where('is_active', true)
                                    ->orderBy('project_date', 'desc')
                                    ->orderBy('created_at', 'desc')
                                    ->get();
                                
                            // Projelerin kaydırma için grup grup bölünmesi (2'şer proje)
                                $projectGroups = $categoryProjects->chunk(2);
                            @endphp
                            
                        @if(count($projectGroups) > 0)
                            <div class="project-slides relative">
                                @foreach($projectGroups as $index => $projectGroup)
                                    <div class="project-slide {{ $index > 0 ? 'hidden' : '' }}" data-slide-index="{{ $index }}">
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                            @foreach($projectGroup as $project)
                                                <div class="project-item">
                                                    <a href="{{ route('front.projects.detail', $project->slug) }}" data-url="{{ route('front.projects.detail', $project->slug) }}" class="project-link block aspect-video rounded-2xl overflow-hidden relative group">
                                                        <img src="{{ $project->cover_image_url }}" alt="{{ $project->title }}" class="w-full h-full object-cover">
                                                        <div class="absolute inset-0 bg-black/25"></div>
                                                        <div class="absolute bottom-0 left-0 right-0 p-6">
                                                            <h3 class="text-white text-xl font-bold" style="font-family: 'TT Norms Pro', sans-serif; font-weight: 700;">{{ $project->title }}</h3>
                                                            <p class="text-white/80 mt-2" style="font-family: 'TT Norms Pro', sans-serif; font-weight: 400;">{{ $project->status_text }}</p>
                                                        </div>
                                                    </a>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div class="col-span-2 block aspect-video rounded-2xl overflow-hidden relative group bg-gray-100 flex items-center justify-center">
                                    <p class="text-gray-600 text-center p-6">Bu kategoride henüz proje bulunmuyor.</p>
                                </div>
                                </div>
                            @endif
                    </div>
                @endforeach

                <!-- Eğer veritabanında hiç kategori yoksa varsayılan olarak eski görünümü göster -->
                @if(count($projectCategories) == 0)
                    <!-- Gerçekleşen Projeler Panel -->
                    <div class="project-panel" data-category="gerceklesen">
                        <div class="project-slides relative">
                            <div class="project-slide" data-slide-index="0">
                                <div class="grid gap-4">
                                    <div class="project-item">
                                        <a href="/projeler/demo-proje" data-url="/projeler/demo-proje" class="project-link block aspect-video rounded-2xl overflow-hidden relative group">
                                            <img src="https://images.unsplash.com/photo-1587764379873-97837921fd44" alt="HAYVAN EVİ" class="w-full h-full object-cover">
                                            <div class="absolute inset-0 bg-black/25"></div>
                                            <div class="absolute bottom-0 left-0 right-0 p-6">
                                                <h3 class="text-white text-xl font-bold" style="font-family: 'TT Norms Pro', sans-serif; font-weight: 700;">HAYVAN EVİ</h3>
                                                <p class="text-white/80 mt-2" style="font-family: 'TT Norms Pro', sans-serif; font-weight: 400;">2024'te Tamamlandı</p>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="project-item">
                                        <a href="/projeler/demo-proje" data-url="/projeler/demo-proje" class="project-link block aspect-video rounded-2xl overflow-hidden relative group">
                                            <img src="https://images.unsplash.com/photo-1517245386807-bb43f82c33c4" alt="ÇOCUK EVİ" class="w-full h-full object-cover">
                                            <div class="absolute inset-0 bg-black/25"></div>
                                            <div class="absolute bottom-0 left-0 right-0 p-6">
                                                <h3 class="text-white text-xl font-bold" style="font-family: 'TT Norms Pro', sans-serif; font-weight: 700;">ÇOCUK EVİ</h3>
                                                <p class="text-white/80 mt-2" style="font-family: 'TT Norms Pro', sans-serif; font-weight: 400;">2023'te Tamamlandı</p>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="project-slide hidden" data-slide-index="1">
                                <div class="grid gap-4">
                                    <div class="project-item">
                                        <a href="/projeler/demo-proje" data-url="/projeler/demo-proje" class="project-link block aspect-video rounded-2xl overflow-hidden relative group">
                                            <img src="https://images.unsplash.com/photo-1581094794329-c8112a89af12" alt="EĞİTİM MERKEZİ" class="w-full h-full object-cover">
                                            <div class="absolute inset-0 bg-black/25"></div>
                                            <div class="absolute bottom-0 left-0 right-0 p-6">
                                                <h3 class="text-white text-xl font-bold" style="font-family: 'TT Norms Pro', sans-serif; font-weight: 700;">EĞİTİM MERKEZİ</h3>
                                                <p class="text-white/80 mt-2" style="font-family: 'TT Norms Pro', sans-serif; font-weight: 400;">2022'de Tamamlandı</p>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="project-item">
                                        <a href="/projeler/demo-proje" data-url="/projeler/demo-proje" class="project-link block aspect-video rounded-2xl overflow-hidden relative group">
                                            <img src="https://images.unsplash.com/photo-1513694203232-719a280e022f" alt="SPOR SALONU" class="w-full h-full object-cover">
                                            <div class="absolute inset-0 bg-black/25"></div>
                                            <div class="absolute bottom-0 left-0 right-0 p-6">
                                                <h3 class="text-white text-xl font-bold" style="font-family: 'TT Norms Pro', sans-serif; font-weight: 700;">SPOR SALONU</h3>
                                                <p class="text-white/80 mt-2" style="font-family: 'TT Norms Pro', sans-serif; font-weight: 400;">2022'de Tamamlandı</p>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Planlanan Projeler Panel -->
                    <div class="project-panel hidden" data-category="planlanan">
                        <div class="project-slides relative">
                            <div class="project-slide" data-slide-index="0">
                                <div class="grid gap-4">
                                    <div class="project-item">
                                        <a href="/projeler/demo-proje" data-url="/projeler/demo-proje" class="project-link block aspect-video rounded-2xl overflow-hidden relative group">
                                            <img src="https://images.unsplash.com/photo-1531834685032-c34bf0d84c77" alt="YEŞİL ENERJİ" class="w-full h-full object-cover">
                                            <div class="absolute inset-0 bg-black/25"></div>
                                            <div class="absolute bottom-0 left-0 right-0 p-6">
                                                <h3 class="text-white text-xl font-bold">YEŞİL ENERJİ SANTRALİ</h3>
                                                <p class="text-white/80 mt-2">2025'te Başlayacak</p>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="project-item">
                                        <a href="/projeler/demo-proje" data-url="/projeler/demo-proje" class="project-link block aspect-video rounded-2xl overflow-hidden relative group">
                                            <img src="https://images.unsplash.com/photo-1517048676732-d65bc937f952" alt="AKILLI ULAŞIM" class="w-full h-full object-cover">
                                            <div class="absolute inset-0 bg-black/25"></div>
                                            <div class="absolute bottom-0 left-0 right-0 p-6">
                                                <h3 class="text-white text-xl font-bold">AKILLI ULAŞIM SİSTEMİ</h3>
                                                <p class="text-white/80 mt-2">2025'te Başlayacak</p>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="project-slide hidden" data-slide-index="1">
                                <div class="grid gap-4">
                                    <div class="project-item">
                                        <a href="/projeler/demo-proje" data-url="/projeler/demo-proje" class="project-link block aspect-video rounded-2xl overflow-hidden relative group">
                                            <img src="https://images.unsplash.com/photo-1497366754035-f200968a6e72" alt="MODERN KÜTÜPHANE" class="w-full h-full object-cover">
                                            <div class="absolute inset-0 bg-black/25"></div>
                                            <div class="absolute bottom-0 left-0 right-0 p-6">
                                                <h3 class="text-white text-xl font-bold">MODERN KÜTÜPHANE</h3>
                                                <p class="text-white/80 mt-2">2026'da Başlayacak</p>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="project-item">
                                        <a href="/projeler/demo-proje" data-url="/projeler/demo-proje" class="project-link block aspect-video rounded-2xl overflow-hidden relative group">
                                            <img src="https://images.unsplash.com/photo-1566669437687-7040a066c756" alt="BİLİM MERKEZİ" class="w-full h-full object-cover">
                                            <div class="absolute inset-0 bg-black/25"></div>
                                            <div class="absolute bottom-0 left-0 right-0 p-6">
                                                <h3 class="text-white text-xl font-bold">BİLİM MERKEZİ</h3>
                                                <p class="text-white/80 mt-2">2026'da Başlayacak</p>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Diğer varsayılan paneller benzer şekilde oluşturulabilir -->
                @endif
            </div>
        </div>
    </div>
</section>

<style>
/* Projeler bölümü temel stilleri - Güçlendirilmiş */
#projects-section {
    min-height: 400px !important;
    display: block !important;
    visibility: visible !important;
    opacity: 1 !important; 
    z-index: 10 !important;
    position: relative !important;
    pointer-events: auto !important;
}

/* Projeler bölümünün gizlenmesini önle */
#projects-section.hidden,
#projects-section[style*="display: none"],
#projects-section[style*="visibility: hidden"],
#projects-section[style*="opacity: 0"] {
    display: block !important;
    visibility: visible !important;
    opacity: 1 !important;
}

/* Proje panelleri için stiller */
.project-panel {
    width: 100% !important;
    transition: opacity 0.3s ease-in-out !important;
}

.project-panel.hidden {
    display: none !important;
}

.project-panel:not(.hidden) {
    display: block !important;
    visibility: visible !important;
    opacity: 1 !important;
}

/* Proje slides için stiller */
.project-slides {
    width: 100% !important;
    position: relative !important;
    overflow: hidden !important;
}

.project-slide {
    width: 100% !important;
    transition: all 0.3s ease-in-out !important;
    position: relative !important;
}

.project-slide.hidden {
    display: none !important;
    visibility: hidden !important;
    opacity: 0 !important;
    position: absolute !important;
    top: 0 !important;
    left: 0 !important;
    z-index: -1 !important;
}

.project-slide:not(.hidden) {
    display: block !important;
    visibility: visible !important;
    opacity: 1 !important;
    position: relative !important;
    z-index: 1 !important;
}

/* Proje grid sistemi */
.project-slide .grid {
    display: grid !important;
    grid-template-columns: repeat(1, 1fr) !important;
    gap: 1rem !important;
}

@media (min-width: 640px) {
    .project-slide .grid {
        grid-template-columns: repeat(2, 1fr) !important;
    }
}

/* Proje item'ları */
.project-item {
    width: 100% !important;
    display: block !important;
}

.project-link {
    display: block !important;
    width: 100% !important;
    aspect-ratio: 16/9 !important;
    border-radius: 1rem !important;
    overflow: hidden !important;
    position: relative !important;
    transition: transform 0.3s ease !important;
}

.project-link:hover {
    transform: scale(1.02) !important;
}

.project-link img {
    width: 100% !important;
    height: 100% !important;
    object-fit: cover !important;
}

/* Kategori radio butonları için stiller - Güçlendirilmiş */
.category-radio-container {
    position: relative !important;
    display: block !important;
    visibility: visible !important;
    opacity: 1 !important;
    pointer-events: auto !important;
    user-select: none !important;
    z-index: 20 !important;
}

.category-radio-container.hidden,
.category-radio-container[style*="display: none"],
.category-radio-container[style*="visibility: hidden"] {
    display: block !important;
    visibility: visible !important;
    opacity: 1 !important;
}

/* Kategori butonları */
.category-btn {
    display: block !important;
    width: 100% !important;
    text-align: left !important;
    padding: 1rem !important;
    border-radius: 0.375rem !important;
    background-color: #f3f4f6 !important;
    color: #1f2937 !important;
    cursor: pointer !important;
    transition: all 0.3s ease !important;
    user-select: none !important;
    pointer-events: auto !important;
    border: none !important;
    font-weight: 500 !important;
    font-size: 0.875rem !important;
    line-height: 1.25rem !important;
}

.category-btn:hover {
    background-color: #e5e7eb !important;
    transform: translateY(-1px) !important;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1) !important;
}

.category-btn.active {
    background-color: #4A5568 !important;
    color: white !important;
    font-weight: 600 !important;
}

/* Radio input'ları gizle */
input[name="project-category"] {
    display: none !important;
}

/* Kategoriler container */
#categories-container {
    display: block !important;
    visibility: visible !important;
    opacity: 1 !important;
    pointer-events: auto !important;
    z-index: 15 !important;
}

#categories-container.hidden,
#categories-container[style*="display: none"],
#categories-container[style*="visibility: hidden"] {
    display: block !important;
    visibility: visible !important;
    opacity: 1 !important;
}

/* Projeler container */
.projects-container {
    width: 100% !important;
    position: relative !important;
    min-height: 300px !important;
}

/* Responsive düzenlemeler */
@media (max-width: 640px) {
    .project-slide .grid {
        grid-template-columns: 1fr !important;
        gap: 0.75rem !important;
    }
    
    .category-btn {
        padding: 0.375rem 0.75rem !important;
        font-size: 0.875rem !important;
        margin: 0.125rem !important;
    }
}

/* Animasyonlar */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.project-panel:not(.hidden) {
    animation: fadeIn 0.3s ease-out !important;
}

/* Koruma stilleri */
[data-protected="PROJECTS_PROTECTION_2024"] {
    display: block !important;
    visibility: visible !important;
    opacity: 1 !important;
}

/* Navigasyon butonları */
.prev-button, .next-button {
    width: 2rem !important;
    height: 2rem !important;
    border: 1px solid #d1d5db !important;
    border-radius: 50% !important;
    background-color: white !important;
    color: #6b7280 !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    cursor: pointer !important;
    transition: all 0.2s ease !important;
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05) !important;
}

.prev-button:hover, .next-button:hover {
    background-color: #f9fafb !important;
    border-color: #9ca3af !important;
    color: #374151 !important;
    transform: translateY(-1px) !important;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1) !important;
}

.prev-button svg, .next-button svg {
    width: 1rem !important;
    height: 1rem !important;
}
</style>

<script>
// Projeler bölümü için tamamen izole edilmiş JavaScript kodu
(function() {
    'use strict';
    
    // Namespace oluştur ve global scope'u kirletme
    window.ProjectsManager = window.ProjectsManager || {};
    
    // Diğer JavaScript kodlarının müdahale etmesini engelle
    const PROJECTS_NAMESPACE = 'isolated-projects-' + Date.now();
    
    // Global değişkenler
    let projectsSection = null;
    let categoriesContainer = null;
    let categoryRadios = null;
    let categoryLabels = null;
    let categoryTitle = null;
    let projectPanels = null;
    let isInitialized = false;
    let protectionInterval = null;
    
    // Ana JavaScript dosyasındaki projeler kodlarını devre dışı bırak
    function disableOtherProjectScripts() {
        // changeCategory fonksiyonunu override et
        if (window.changeCategory) {
            window.changeCategory = function() {
                console.log('Eski projeler sistemi devre dışı bırakıldı');
                return false;
            };
        }
        
        // projectCategories değişkenini temizle
        if (window.projectCategories) {
            window.projectCategories = [];
        }
        
        // projectSliders değişkenini temizle
        if (window.projectSliders) {
            window.projectSliders = [];
        }
        
        // Eski event listener'ları kaldır
        const oldProjectElements = document.querySelectorAll('.project-category, .project-slider');
        oldProjectElements.forEach(function(element) {
            const newElement = element.cloneNode(true);
            element.parentNode.replaceChild(newElement, element);
        });
    }
    
    // Projeler bölümünü koruma fonksiyonu - Daha güçlü
    function protectProjectsSection() {
        if (!projectsSection) return;
        
        try {
            // CSS ile zorla görünür yap
            projectsSection.style.setProperty('display', 'block', 'important');
            projectsSection.style.setProperty('visibility', 'visible', 'important');
            projectsSection.style.setProperty('opacity', '1', 'important');
            projectsSection.style.setProperty('position', 'relative', 'important');
            projectsSection.style.setProperty('z-index', '10', 'important');
            projectsSection.style.setProperty('pointer-events', 'auto', 'important');
            
            // Sınıfları kontrol et ve düzelt
            if (projectsSection.classList.contains('hidden')) {
                projectsSection.classList.remove('hidden');
            }
            
            // Data attribute ile koruma işaretle
            projectsSection.setAttribute('data-protected', PROJECTS_NAMESPACE);
            
            // Kategoriler container'ını da koru
            if (categoriesContainer) {
                categoriesContainer.style.setProperty('display', 'block', 'important');
                categoriesContainer.style.setProperty('visibility', 'visible', 'important');
                categoriesContainer.style.setProperty('opacity', '1', 'important');
                categoriesContainer.style.setProperty('pointer-events', 'auto', 'important');
                
                if (categoriesContainer.classList.contains('hidden')) {
                    categoriesContainer.classList.remove('hidden');
                }
                
                categoriesContainer.setAttribute('data-protected', PROJECTS_NAMESPACE);
            }
            
            // Kategori radio container'larını koru
            const radioContainers = document.querySelectorAll('.category-radio-container');
            radioContainers.forEach(function(container) {
                container.style.setProperty('display', 'block', 'important');
                container.style.setProperty('visibility', 'visible', 'important');
                container.style.setProperty('opacity', '1', 'important');
                
                if (container.classList.contains('hidden')) {
                    container.classList.remove('hidden');
                }
            });
            
        } catch (error) {
            console.warn('Projeler koruma hatası:', error);
        }
    }
    
    // Kategori değiştirme fonksiyonu
    function changeCategory(selectedCategory) {
        protectProjectsSection();
        
        const categoryLabel = document.querySelector('label[for="category-' + selectedCategory + '"]');
            
            // Kategori başlığını güncelle
            if (categoryTitle && categoryLabel) {
                categoryTitle.textContent = categoryLabel.textContent.trim();
            }
            
        // Tüm panelleri gizle
        if (projectPanels) {
            projectPanels.forEach(function(panel) {
                panel.classList.add('hidden');
                panel.style.setProperty('display', 'none', 'important');
            });
        }
        
        // Seçili paneli göster
        const selectedPanel = document.querySelector('.project-panel[data-category="' + selectedCategory + '"]');
        if (selectedPanel) {
            selectedPanel.classList.remove('hidden');
            selectedPanel.style.setProperty('display', 'block', 'important');
            selectedPanel.style.setProperty('visibility', 'visible', 'important');
            selectedPanel.style.setProperty('opacity', '1', 'important');
            
            // İlk slide'ı göster, diğerlerini gizle
            const slides = selectedPanel.querySelectorAll('.project-slide');
                    slides.forEach(function(slide, idx) {
                        if (idx === 0) {
                            slide.classList.remove('hidden');
                    slide.style.setProperty('display', 'block', 'important');
                } else {
                    slide.classList.add('hidden');
                    slide.style.setProperty('display', 'none', 'important');
                }
            });
        }
        
        // Kategori butonlarının görünümünü güncelle
        if (categoryLabels) {
            categoryLabels.forEach(function(lbl) {
                lbl.classList.remove('active');
                lbl.classList.add('text-gray-800');
                lbl.style.backgroundColor = '#e5e7eb'; // bg-gray-200
                lbl.style.color = '#1f2937'; // text-gray-800
            });
            
            if (categoryLabel) {
                categoryLabel.classList.add('active');
                categoryLabel.classList.remove('text-gray-800');
                categoryLabel.style.backgroundColor = '#4A5568'; // active background
                categoryLabel.style.color = 'white'; // active text color
            }
        }
        
        // Korumayı yeniden uygula
        setTimeout(protectProjectsSection, 10);
    }
    
    // Event listener'ları kurma fonksiyonu
    function setupEventListeners() {
        // Kategori radio butonları
        if (categoryRadios) {
            categoryRadios.forEach(function(radio) {
                radio.addEventListener('change', function(e) {
                    e.stopPropagation();
                    e.preventDefault();
                    changeCategory(this.value);
            return false;
                }, true);
            });
        }
        
        // Kategori label'ları
        if (categoryLabels) {
            categoryLabels.forEach(function(label) {
                label.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    protectProjectsSection();
                    
                    const radioId = this.getAttribute('for');
                    const radio = document.getElementById(radioId);
                    if (radio) {
                        radio.checked = true;
                        changeCategory(radio.value);
                    }
                    
                    return false;
                }, true);
            });
        }
        
        // Navigasyon butonları
        const prevButton = document.querySelector('#projects-section .prev-button');
        const nextButton = document.querySelector('#projects-section .next-button');
        
        if (prevButton) {
            prevButton.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                protectProjectsSection();
                
                const activePanel = document.querySelector('.project-panel:not(.hidden)');
                if (activePanel) {
                    const slides = activePanel.querySelectorAll('.project-slide');
                    const currentSlide = activePanel.querySelector('.project-slide:not(.hidden)');
                    
                    if (currentSlide && slides.length > 1) {
                        const currentIndex = parseInt(currentSlide.dataset.slideIndex) || 0;
                        const newIndex = (currentIndex - 1 + slides.length) % slides.length;
                
                // Tüm slide'ları gizle
                        slides.forEach(function(slide, idx) {
                    slide.classList.add('hidden');
                            slide.style.setProperty('display', 'none', 'important');
                            slide.style.setProperty('visibility', 'hidden', 'important');
                            slide.style.setProperty('opacity', '0', 'important');
                });
                
                // Yeni slide'ı göster
                        if (slides[newIndex]) {
                slides[newIndex].classList.remove('hidden');
                            slides[newIndex].style.setProperty('display', 'block', 'important');
                            slides[newIndex].style.setProperty('visibility', 'visible', 'important');
                            slides[newIndex].style.setProperty('opacity', '1', 'important');
                            slides[newIndex].style.setProperty('position', 'relative', 'important');
                            slides[newIndex].style.setProperty('z-index', '1', 'important');
                        }
                    }
                }
                
                return false;
            }, true);
        }
        
        if (nextButton) {
            nextButton.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                protectProjectsSection();
                
                const activePanel = document.querySelector('.project-panel:not(.hidden)');
                if (activePanel) {
                    const slides = activePanel.querySelectorAll('.project-slide');
                    const currentSlide = activePanel.querySelector('.project-slide:not(.hidden)');
                    
                    if (currentSlide && slides.length > 1) {
                        const currentIndex = parseInt(currentSlide.dataset.slideIndex) || 0;
                        const newIndex = (currentIndex + 1) % slides.length;
                
                // Tüm slide'ları gizle
                        slides.forEach(function(slide, idx) {
                    slide.classList.add('hidden');
                            slide.style.setProperty('display', 'none', 'important');
                            slide.style.setProperty('visibility', 'hidden', 'important');
                            slide.style.setProperty('opacity', '0', 'important');
                });
                
                // Yeni slide'ı göster
                        if (slides[newIndex]) {
                slides[newIndex].classList.remove('hidden');
                            slides[newIndex].style.setProperty('display', 'block', 'important');
                            slides[newIndex].style.setProperty('visibility', 'visible', 'important');
                            slides[newIndex].style.setProperty('opacity', '1', 'important');
                            slides[newIndex].style.setProperty('position', 'relative', 'important');
                            slides[newIndex].style.setProperty('z-index', '1', 'important');
                        }
                    }
                }
                
                return false;
            }, true);
        }
        
        // Proje linkleri
        const projectLinks = document.querySelectorAll('#projects-section .project-link');
    projectLinks.forEach(function(link) {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
                protectProjectsSection();
                
                const url = this.getAttribute('href') || this.getAttribute('data-url');
                if (url) {
                    setTimeout(function() {
                        window.location.href = url;
                    }, 100);
                }
                
                return false;
            }, true);
        });
        
        // Document click event - boşluğa tıklanma koruması
        document.addEventListener('click', function(e) {
            // Projeler bölümü içinde tıklanmışsa hiçbir şey yapma
            if (projectsSection && projectsSection.contains(e.target)) {
                return;
            }
            
            // Kategoriler container içinde tıklanmışsa hiçbir şey yapma
            if (categoriesContainer && categoriesContainer.contains(e.target)) {
                return;
            }
            
            // Diğer durumlarda projeler bölümünü koru
            protectProjectsSection();
        }, true);
        
        // Window events
        window.addEventListener('load', protectProjectsSection, true);
        window.addEventListener('resize', protectProjectsSection, true);
        window.addEventListener('scroll', protectProjectsSection, true);
    }
    
    // Initialization fonksiyonu
    function initializeProjectsSection() {
        if (isInitialized) return;
        
        // Diğer projeler scriptlerini devre dışı bırak
        disableOtherProjectScripts();
        
        // DOM elementlerini bul
        projectsSection = document.getElementById('projects-section');
        categoriesContainer = document.getElementById('categories-container');
        categoryRadios = document.querySelectorAll('input[name="project-category"]');
        categoryLabels = document.querySelectorAll('.category-btn');
        categoryTitle = document.getElementById('categoryTitle');
        projectPanels = document.querySelectorAll('.project-panel');
        
        if (!projectsSection) {
            console.warn('Projeler bölümü bulunamadı');
            return;
        }
        
        // İlk korumayı uygula
        protectProjectsSection();
        
        // Event listener'ları kur
        setupEventListeners();
        
        // Sürekli koruma (her 25ms'de bir kontrol et)
        if (protectionInterval) {
            clearInterval(protectionInterval);
        }
        protectionInterval = setInterval(protectProjectsSection, 25);
        
        // MutationObserver ile DOM değişikliklerini izle
        if (window.MutationObserver) {
            const observer = new MutationObserver(function(mutations) {
                mutations.forEach(function(mutation) {
                    if (mutation.type === 'attributes' && 
                        (mutation.attributeName === 'style' || 
                         mutation.attributeName === 'class')) {
                        
                        // Sadece korumalı elementleri kontrol et
                        if (mutation.target.getAttribute('data-protected') === PROJECTS_NAMESPACE) {
                            protectProjectsSection();
                        }
                    }
                });
            });
            
            observer.observe(projectsSection, {
                attributes: true,
                attributeFilter: ['style', 'class'],
                subtree: true
            });
            
            if (categoriesContainer) {
                observer.observe(categoriesContainer, {
                    attributes: true,
                    attributeFilter: ['style', 'class'],
                    subtree: true
                });
            }
        }
        
        isInitialized = true;
        
        // İlk kategoriyi seç
        setTimeout(function() {
            const firstRadio = document.querySelector('input[name="project-category"]:checked');
            if (firstRadio) {
                changeCategory(firstRadio.value);
            }
        }, 100);
    }
    
    // DOM hazır olduğunda başlat
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initializeProjectsSection);
    } else {
        initializeProjectsSection();
    }
    
    // Sayfa tamamen yüklendiğinde de kontrol et
    window.addEventListener('load', function() {
        setTimeout(initializeProjectsSection, 200);
    });
    
    // Global scope'a ekle (debugging için)
    window.ProjectsManager.protect = protectProjectsSection;
    window.ProjectsManager.changeCategory = changeCategory;
    window.ProjectsManager.isInitialized = function() { return isInitialized; };
    
})();
</script>
@endif 