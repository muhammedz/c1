<!-- #### Projects Section -->
@if(isset($projectSettings) && $projectSettings->is_active)
<section id="projects-section" class="projects-section bg-white w-full px-4 py-12" style="min-height: 400px; display: block !important; visibility: visible !important;">
    <div class="container max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-4 gap-8">
        <!-- Sol Kısım - Kategoriler -->
        <div class="space-y-3 categories-container">
            <!-- Kategoriler (Radio butonlar olarak) -->
            @forelse($projectCategories as $category)
                <div class="category-radio-container">
                    <input type="radio" id="category-{{ $category->slug }}" name="project-category" value="{{ $category->slug }}" 
                          class="hidden" {{ $loop->first ? 'checked' : '' }}>
                    <label for="category-{{ $category->slug }}" 
                          class="w-full text-left block bg-gray-200 hover:bg-gray-300 rounded-md p-4 font-medium category-btn {{ $loop->first ? 'active' : 'text-gray-800' }}">
                        {{ $category->name }}
                    </label>
                </div>
            @empty
                <!-- Varsayılan kategoriler -->
                <div class="category-radio-container">
                    <input type="radio" id="category-gerceklesen" name="project-category" value="gerceklesen" 
                          class="hidden" checked>
                    <label for="category-gerceklesen" 
                          class="w-full text-left block bg-gray-200 hover:bg-gray-300 rounded-md p-4 font-medium category-btn active">
                        Gerçekleşen Projeler
                    </label>
                </div>
                <div class="category-radio-container">
                    <input type="radio" id="category-planlanan" name="project-category" value="planlanan" 
                          class="hidden">
                    <label for="category-planlanan" 
                          class="w-full text-left block bg-gray-200 hover:bg-gray-300 rounded-md p-4 font-medium text-gray-800 category-btn">
                        Planlanan Projeler
                    </label>
                </div>
                <div class="category-radio-container">
                    <input type="radio" id="category-devam-eden" name="project-category" value="devam-eden" 
                          class="hidden">
                    <label for="category-devam-eden" 
                          class="w-full text-left block bg-gray-200 hover:bg-gray-300 rounded-md p-4 font-medium text-gray-800 category-btn">
                        Devam Eden Projeler
                    </label>
                </div>
                <div class="category-radio-container">
                    <input type="radio" id="category-uluslararasi" name="project-category" value="uluslararasi" 
                          class="hidden">
                    <label for="category-uluslararasi" 
                          class="w-full text-left block bg-gray-200 hover:bg-gray-300 rounded-md p-4 font-medium text-gray-800 category-btn">
                        Uluslararası Projeler
                    </label>
                </div>
                <div class="category-radio-container">
                    <input type="radio" id="category-sosyal" name="project-category" value="sosyal" 
                          class="hidden">
                    <label for="category-sosyal" 
                          class="w-full text-left block bg-gray-200 hover:bg-gray-300 rounded-md p-4 font-medium text-gray-800 category-btn">
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
                @if($projectSettings->show_view_all_button)
                    <a href="{{ $projectSettings->view_all_url ?? route('front.projects') }}" class="text-gray-600 hover:text-gray-800 view-all-link">
                        {{ $projectSettings->view_all_text ?? 'Tümünü Gör' }}
                    </a>
                @endif
            </div>

            <!-- Proje Panelleri -->
            <div class="relative projects-container max-w-full">
                <!-- Navigation Butonları -->
                <div class="absolute left-0 top-1/2 -translate-y-1/2 -mx-4 -translate-x-4 z-10 w-8 h-8 bg-white rounded-full shadow-lg border border-gray-800 flex items-center justify-center prev-button">
                    <svg class="w-5 h-5 text-gray-600" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </div>

                <div class="absolute right-0 top-1/2 -translate-y-1/2 -mx-4 translate-x-4 z-10 w-8 h-8 bg-white rounded-full shadow-lg border border-gray-800 flex items-center justify-center next-button">
                    <svg class="w-5 h-5 text-gray-600" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </div>

                <!-- Kategori Panelleri -->
                @foreach($projectCategories as $category)
                    <div class="project-panel {{ !$loop->first ? 'hidden' : '' }}" data-category="{{ $category->slug }}">
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-2 xl:grid-cols-2 gap-4">
                            @php
                                $categoryProjects = \App\Models\Project::where('category_id', $category->id)
                                    ->where('is_active', true)
                                    ->orderBy('order')
                                    ->get();
                                
                                // Projelerin kaydırma için grup grup bölünmesi
                                $projectGroups = $categoryProjects->chunk(2);
                            @endphp
                            
                            <div class="project-slides relative">
                                @foreach($projectGroups as $index => $projectGroup)
                                    <div class="project-slide {{ $index > 0 ? 'hidden' : '' }}" data-slide-index="{{ $index }}">
                                        <div class="grid gap-4">
                                            @foreach($projectGroup as $project)
                                                <div class="project-item">
                                                    <a href="{{ route('front.projects.detail', $project->slug) }}" data-url="{{ route('front.projects.detail', $project->slug) }}" class="project-link block aspect-video rounded-2xl overflow-hidden relative group">
                                                        <img src="{{ $project->cover_image_url }}" alt="{{ $project->title }}" class="w-full h-full object-cover">
                                                        <div class="absolute inset-0 bg-black/60"></div>
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
                            
                            @if(count($projectGroups) <= 0)
                                <div class="col-span-2 block aspect-video rounded-2xl overflow-hidden relative group bg-gray-100 flex items-center justify-center">
                                    <p class="text-gray-600 text-center p-6">Bu kategoride henüz proje bulunmuyor.</p>
                                </div>
                            @endif
                        </div>
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
                                            <div class="absolute inset-0 bg-black/60"></div>
                                            <div class="absolute bottom-0 left-0 right-0 p-6">
                                                <h3 class="text-white text-xl font-bold" style="font-family: 'TT Norms Pro', sans-serif; font-weight: 700;">HAYVAN EVİ</h3>
                                                <p class="text-white/80 mt-2" style="font-family: 'TT Norms Pro', sans-serif; font-weight: 400;">2024'te Tamamlandı</p>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="project-item">
                                        <a href="/projeler/demo-proje" data-url="/projeler/demo-proje" class="project-link block aspect-video rounded-2xl overflow-hidden relative group">
                                            <img src="https://images.unsplash.com/photo-1517245386807-bb43f82c33c4" alt="ÇOCUK EVİ" class="w-full h-full object-cover">
                                            <div class="absolute inset-0 bg-black/60"></div>
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
                                            <div class="absolute inset-0 bg-black/60"></div>
                                            <div class="absolute bottom-0 left-0 right-0 p-6">
                                                <h3 class="text-white text-xl font-bold" style="font-family: 'TT Norms Pro', sans-serif; font-weight: 700;">EĞİTİM MERKEZİ</h3>
                                                <p class="text-white/80 mt-2" style="font-family: 'TT Norms Pro', sans-serif; font-weight: 400;">2022'de Tamamlandı</p>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="project-item">
                                        <a href="/projeler/demo-proje" data-url="/projeler/demo-proje" class="project-link block aspect-video rounded-2xl overflow-hidden relative group">
                                            <img src="https://images.unsplash.com/photo-1513694203232-719a280e022f" alt="SPOR SALONU" class="w-full h-full object-cover">
                                            <div class="absolute inset-0 bg-black/60"></div>
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
                                            <div class="absolute inset-0 bg-black/60"></div>
                                            <div class="absolute bottom-0 left-0 right-0 p-6">
                                                <h3 class="text-white text-xl font-bold">YEŞİL ENERJİ SANTRALİ</h3>
                                                <p class="text-white/80 mt-2">2025'te Başlayacak</p>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="project-item">
                                        <a href="/projeler/demo-proje" data-url="/projeler/demo-proje" class="project-link block aspect-video rounded-2xl overflow-hidden relative group">
                                            <img src="https://images.unsplash.com/photo-1517048676732-d65bc937f952" alt="AKILLI ULAŞIM" class="w-full h-full object-cover">
                                            <div class="absolute inset-0 bg-black/60"></div>
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
                                            <div class="absolute inset-0 bg-black/60"></div>
                                            <div class="absolute bottom-0 left-0 right-0 p-6">
                                                <h3 class="text-white text-xl font-bold">MODERN KÜTÜPHANE</h3>
                                                <p class="text-white/80 mt-2">2026'da Başlayacak</p>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="project-item">
                                        <a href="/projeler/demo-proje" data-url="/projeler/demo-proje" class="project-link block aspect-video rounded-2xl overflow-hidden relative group">
                                            <img src="https://images.unsplash.com/photo-1566669437687-7040a066c756" alt="BİLİM MERKEZİ" class="w-full h-full object-cover">
                                            <div class="absolute inset-0 bg-black/60"></div>
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
/* Projeler bölümü temel stilleri */
#projects-section {
    min-height: 400px;
    display: block !important;
    visibility: visible !important;
    opacity: 1 !important; 
    z-index: 10;
    position: relative;
}

/* Kategori radio butonları için stiller */
.category-radio-container {
    position: relative;
    display: block;
    width: 100%;
}

.category-radio-container input[type="radio"]:checked + label {
    background-color: #4A5568;
    color: white;
}

/* Proje panelleri */
.project-panel {
    width: 100%;
    transition: all 0.3s ease;
}

.project-panel.hidden {
    display: none;
}

/* Proje slides için stiller */
.project-slide {
    width: 100%;
}

/* Proje kartları */
.project-item {
    height: auto;
    width: 100%;
    margin-bottom: 1.5rem;
}

.project-item a.project-link {
    min-height: 253px;
    height: 245px;
    display: block;
    width: 100%;
    position: relative;
}

.project-item a.project-link img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

/* Grid düzeltmeleri */
.project-slide .grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1.5rem;
}

@media (max-width: 640px) {
    .project-slide .grid {
        grid-template-columns: 1fr;
    }
    
    .project-item a.project-link {
        min-height: 150px;
        height: 150px;
    }
}

/* Proje kartları için hover efekti */
.project-item a:hover .absolute.inset-0 {
    background-color: rgba(0, 0, 0, 0.7);
}

/* Tıklamalarda aktif görünümü korumak için */
.projects-section a,
.projects-section a:active,
.projects-section a:focus {
    outline: none;
    -webkit-tap-highlight-color: transparent;
}

/* Ekstra alan koruma için */
#projects-section * {
    pointer-events: auto !important;
}

#projects-section .project-link {
    cursor: pointer;
}
</style>

<script>
// Ana projelerin DOM yüklendikten sonra çalışacak kod
document.addEventListener('DOMContentLoaded', function() {
    // JavaScript'in çalıştığından emin olmak için section'a sınıf ekle
    var projectsSection = document.getElementById('projects-section');
    if (projectsSection) {
        projectsSection.classList.add('js-initialized');
    }
    
    // Kategori değiştirme işlevi
    var categoryRadios = document.querySelectorAll('input[name="project-category"]');
    var categoryTitle = document.getElementById('categoryTitle');
    var projectPanels = document.querySelectorAll('.project-panel');
    
    categoryRadios.forEach(function(radio) {
        radio.addEventListener('change', function(e) {
            e.stopPropagation();
            
            var selectedCategory = this.value;
            var categoryLabel = document.querySelector('label[for="category-' + selectedCategory + '"]');
            
            // Kategori başlığını güncelle
            if (categoryTitle && categoryLabel) {
                categoryTitle.textContent = categoryLabel.textContent.trim();
            }
            
            // İlgili panel'i göster, diğerlerini gizle
            projectPanels.forEach(function(panel) {
                if (panel.dataset.category === selectedCategory) {
                    panel.classList.remove('hidden');
                    // Kategori değiştiğinde ilk slide'ı göster
                    var slides = panel.querySelectorAll('.project-slide');
                    slides.forEach(function(slide, idx) {
                        if (idx === 0) {
                            slide.classList.remove('hidden');
                        } else {
                            slide.classList.add('hidden');
                        }
                    });
                } else {
                    panel.classList.add('hidden');
                }
            });
        });
    });
    
    // Kategori labelları için tıklama işlevi
    var categoryLabels = document.querySelectorAll('.category-btn');
    categoryLabels.forEach(function(label) {
        label.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            var radioId = this.getAttribute('for');
            var radio = document.getElementById(radioId);
            if (radio) {
                radio.checked = true;
                radio.dispatchEvent(new Event('change'));
            }
            
            // Active class'ını güncelle
            categoryLabels.forEach(function(lbl) {
                lbl.classList.remove('active');
                lbl.classList.add('text-gray-800');
            });
            this.classList.add('active');
            this.classList.remove('text-gray-800');
            
            return false;
        });
    });
    
    // İleri ve geri navigasyon butonları işlevi
    var prevButton = document.querySelector('.prev-button');
    var nextButton = document.querySelector('.next-button');
    
    if(prevButton && nextButton) {
        // Önceki slide'a git
        prevButton.addEventListener('click', function() {
            // Aktif kategori panelini bul
            var activePanel = document.querySelector('.project-panel:not(.hidden)');
            if(activePanel) {
                var slides = activePanel.querySelectorAll('.project-slide');
                var currentSlide = activePanel.querySelector('.project-slide:not(.hidden)');
                var currentIndex = parseInt(currentSlide.dataset.slideIndex);
                var newIndex = (currentIndex - 1 + slides.length) % slides.length;
                
                // Tüm slide'ları gizle
                slides.forEach(function(slide) {
                    slide.classList.add('hidden');
                });
                
                // Yeni slide'ı göster
                slides[newIndex].classList.remove('hidden');
            }
        });
        
        // Sonraki slide'a git
        nextButton.addEventListener('click', function() {
            // Aktif kategori panelini bul
            var activePanel = document.querySelector('.project-panel:not(.hidden)');
            if(activePanel) {
                var slides = activePanel.querySelectorAll('.project-slide');
                var currentSlide = activePanel.querySelector('.project-slide:not(.hidden)');
                var currentIndex = parseInt(currentSlide.dataset.slideIndex);
                var newIndex = (currentIndex + 1) % slides.length;
                
                // Tüm slide'ları gizle
                slides.forEach(function(slide) {
                    slide.classList.add('hidden');
                });
                
                // Yeni slide'ı göster
                slides[newIndex].classList.remove('hidden');
            }
        });
    }
    
    // Sayfa yüklendiğinde proje bölümünün görünürlüğünü zorla
    window.addEventListener('load', function() {
        var projectsSection = document.getElementById('projects-section');
        if (projectsSection) {
            projectsSection.style.display = 'block';
            projectsSection.style.visibility = 'visible';
            projectsSection.style.opacity = '1';
        }
    });
    
    // Projeler bölümünün görünürlüğünü sürekli kontrol et (10ms'de bir)
    setInterval(function() {
        var projectsSection = document.getElementById('projects-section');
        if (projectsSection && (
            projectsSection.style.display === 'none' || 
            projectsSection.style.visibility === 'hidden' || 
            projectsSection.style.opacity === '0'
        )) {
            projectsSection.style.display = 'block';
            projectsSection.style.visibility = 'visible';
            projectsSection.style.opacity = '1';
        }
    }, 10);
    
    // Proje linklerine tıklama işlevi
    var projectLinks = document.querySelectorAll('.project-link');
    projectLinks.forEach(function(link) {
        link.addEventListener('click', function(e) {
            // Tıklama olayını engelle, alanın kaybolmasını önle
            e.preventDefault();
            e.stopPropagation();
            
            // Projeler bölümünün görünür kalmasını sağla
            var projectsSectionBefore = document.getElementById('projects-section');
            if (projectsSectionBefore) {
                projectsSectionBefore.style.display = 'block';
                projectsSectionBefore.style.visibility = 'visible';
                projectsSectionBefore.style.opacity = '1';
            }
            
            // Link URL'sini al ve sayfaya git
            var url = this.getAttribute('href') || this.getAttribute('data-url');
            if (url) {
                document.location.href = url;
            }
            
            return false;
        });
    });
});
</script>
@endif 