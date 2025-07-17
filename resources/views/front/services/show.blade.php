@extends('layouts.front')

@section('title', $service->title)

@section('meta_description', $service->meta_description ?? Str::limit(strip_tags($service->summary), 160))

@section('css')
<style>
    :root {
        --header-height: 96px; /* Varsayılan header yüksekliği */
        --primary-color: #00352b; /* Belediye ana rengi */
        --primary-light: rgba(0, 53, 43, 0.1); /* Belediye ana renginin açık tonu */
        --primary-dark: #002a22; /* Belediye ana renginin koyu tonu */
        --secondary-color: #20846c; /* Belediye ikincil rengi */
        --accent-color: #e6a23c; /* Belediye vurgu rengi */
    }
    
    .service-layout-container {
        max-width: 1280px;
        margin: 0 auto;
        padding: 0 1rem;
    }
    
    .service-grid-layout {
        display: grid;
        grid-template-columns: 300px 1fr;
        gap: 2rem;
        margin-top: -20px;
        align-items: flex-start; /* İki sütunun da üstten başlamasını sağlar */
    }
    
    .service-grid-layout::before {
        content: "";
        display: block;
        grid-column: 1;
        min-height: 1px;
    }
    
    .service-content-area {
        grid-column: 2;
        padding-top: 0; /* Üst padding'i kaldırdık */
    }
    
    .service-content-section {
        background-color: white;
        border-radius: 12px;
        box-shadow: 0 2px 15px rgba(0, 0, 0, 0.05);
        padding: 30px;
        margin-bottom: 30px;
        border-top: 4px solid var(--primary-color);
    }
    
    .service-content-section h2 {
        font-size: 24px;
        font-weight: 700;
        color: var(--primary-color);
        margin-top: 0;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 1px solid #e5e7eb;
    }
    
    @media (max-width: 768px) {
        .service-grid-layout {
            grid-template-columns: 1fr;
            gap: 1rem;
        }
        
        .service-content-area {
            grid-column: 1;
        }
        
        .service-grid-layout::before {
            display: none;
        }
    }

    /* İçindekiler stillerini sol tarafta sticky olacak şekilde düzenle */
    #sidebar {
        grid-column: 1;
        align-self: flex-start; /* Üst hizalama */
        position: relative;
        width: 300px; /* Sabit genişlik tanımı */
        margin-top: 0; /* İçindekiler menüsünün üst marjinini sıfırla */
    }

    #sidebar .sticky-container {
        width: 100%;
        position: relative;
        background: white;
        border-radius: 8px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        transition: none; /* Animasyonları kaldır */
        max-height: calc(100vh - 120px);
        overflow-y: auto;
        padding: 30px; /* Sağdaki içerik bölümü ile aynı padding değeri */
    }

    #sidebar.scrolledDown .sticky-container {
        position: fixed;
        top: 20px;
        width: 300px; /* Sabit genişlik */
        z-index: 40;
    }

    #sidebar .sticky-container::-webkit-scrollbar {
        width: 4px;
    }

    #sidebar .sticky-container::-webkit-scrollbar-track {
        background: #f5f5f5;
        border-radius: 10px;
    }

    #sidebar .sticky-container::-webkit-scrollbar-thumb {
        background: #00352b;
        border-radius: 10px;
    }

    @media (max-width: 768px) {
        #sidebar {
            display: none !important;
        }
    }
</style>
@endsection

@section('content')
<!-- Hero Bölümü -->
<div class="relative bg-gradient-to-r from-[#00352b] to-[#20846c] overflow-hidden">
    <div class="absolute inset-0 opacity-20">
        <!-- Pattern overlay -->
        <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" preserveAspectRatio="none">
            <defs>
                <pattern id="hero-pattern" width="40" height="40" patternUnits="userSpaceOnUse">
                    <path d="M0 20 L40 20 M20 0 L20 40" stroke="currentColor" stroke-width="1" fill="none" />
                </pattern>
            </defs>
            <rect width="100%" height="100%" fill="url(#hero-pattern)" />
        </svg>
    </div>
    
    <!-- Dekoratif şekiller -->
    <div class="absolute -right-20 -bottom-20 w-64 h-64 rounded-full bg-[#e6a23c]/10 blur-3xl"></div>
    <div class="absolute -left-10 top-10 w-40 h-40 rounded-full bg-white/5 blur-2xl"></div>
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 md:py-10 relative z-10">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-center">
            <div class="md:col-span-2">
                <div class="inline-block bg-white/10 backdrop-blur-sm px-4 py-1 rounded-full text-white/90 text-sm mb-3 border border-white/10">
                    <span class="material-icons text-xs align-middle mr-1">local_offer</span>
                    <span>Belediye Hizmetleri</span>
                </div>
                <h1 class="text-3xl md:text-4xl font-bold text-white mb-4">{{ $service->title }}</h1>
                <p class="text-white/80 text-lg mb-5">{{ $service->summary ?? 'Belediyemiz tarafından sunulan bu hizmet ile ilgili tüm detayları bu sayfada bulabilirsiniz.' }}</p>
                
                <div class="flex flex-wrap gap-4">
                    @if(!empty($service->cta_text) && !empty($service->cta_url))
                        <a href="{{ $service->cta_url }}" class="inline-flex items-center px-5 py-2.5 bg-[#e6a23c] text-white rounded-md hover:bg-[#e6a23c]/90 transition-colors font-medium shadow-lg shadow-[#e6a23c]/20" @if(str_starts_with($service->cta_url, 'http')) target="_blank" @endif>
                            <span class="material-icons mr-2 text-sm">edit_document</span>
                            {{ $service->cta_text }}
                        </a>
                    @endif
                    @if(isset($service->features['is_standard_forms_visible']) && $service->features['is_standard_forms_visible'] && (isset($service->features['standard_forms']) && !empty($service->features['standard_forms']) || isset($service->features['documents']) && is_array($service->features['documents']) && !empty($service->features['documents'])))
                    <a href="#standart-formlar" class="inline-flex items-center px-5 py-2.5 bg-white/10 text-white border border-white/20 rounded-md hover:bg-white/20 transition-colors shadow-lg shadow-black/5">
                        <span class="material-icons mr-2 text-sm">download</span>
                        Formları İndir
                    </a>
                    @endif
                </div>
            </div>
            <div class="hidden md:flex justify-end">
                <div class="bg-white/10 backdrop-blur-sm p-6 rounded-lg border border-white/20 shadow-lg">
                    <div class="text-white text-center">
                        <span class="material-icons text-4xl text-[#e6a23c] mb-2">info</span>
                        <h3 class="text-xl font-semibold mb-2">Önemli Bilgi</h3>
                        <p class="text-sm text-white/80">Başvuru için gerekli tüm belgeler ve bilgiler aşağıda listelenmiştir.</p>
                        <div class="mt-4 pt-4 border-t border-white/10">
                            <div class="flex items-center justify-center text-[#e6a23c]">
                                <span class="material-icons mr-2">phone</span>
                                <span class="font-bold">444 06 01</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Ana İçerik Bölümü -->
<div class="service-layout-container pt-2">
    <div class="service-grid-layout">
        <!-- Sol Sidebar Menü (İçindekiler) -->
        <div id="sidebar">
            <div class="sticky-container bg-white rounded-lg border-t-4 border-[#00352b]">
                <h3 class="text-2xl font-bold mb-4 text-[#00352b] pb-4 border-b border-gray-200">İçindekiler</h3>
                <nav class="flex flex-col space-y-2">
                    @if(isset($service->features['is_purpose_visible']) && $service->features['is_purpose_visible'] && isset($service->features['service_purpose']) && !empty($service->features['service_purpose']))
                    <a href="#hizmetin-amaci" class="flex items-center px-4 py-2 rounded-lg text-gray-600 hover:bg-[#00352b]/10 hover:text-[#00352b] font-medium transition-colors">
                        <i class="material-icons mr-3 text-lg">lightbulb</i>
                        Hizmetin Amacı
                    </a>
                    @endif
                    @if(isset($service->features['is_who_can_apply_visible']) && $service->features['is_who_can_apply_visible'] && isset($service->features['who_can_apply']) && !empty($service->features['who_can_apply']))
                    <a href="#kimler-basvurabilir" class="flex items-center px-4 py-2 rounded-lg text-gray-600 hover:bg-[#00352b]/10 hover:text-[#00352b] font-medium transition-colors">
                        <i class="material-icons mr-3 text-lg">people</i>
                        Kimler Başvurabilir
                    </a>
                    @endif
                    @if(isset($service->features['is_requirements_visible']) && $service->features['is_requirements_visible'] && isset($service->features['requirements']) && !empty($service->features['requirements']))
                    <a href="#basvuru-sartlari" class="flex items-center px-4 py-2 rounded-lg text-gray-600 hover:bg-[#00352b]/10 hover:text-[#00352b] font-medium transition-colors">
                        <i class="material-icons mr-3 text-lg">assignment</i>
                        Başvuru Şartları
                    </a>
                    @endif
                    @if(isset($service->features['is_application_process_visible']) && $service->features['is_application_process_visible'] && isset($service->features['application_process']) && !empty($service->features['application_process']))
                    <a href="#basvuru-sureci" class="flex items-center px-4 py-2 rounded-lg text-gray-600 hover:bg-[#00352b]/10 hover:text-[#00352b] font-medium transition-colors">
                        <i class="material-icons mr-3 text-lg">timeline</i>
                        Başvuru Süreci
                    </a>
                    @endif
                    @if(isset($service->features['is_standard_forms_visible']) && $service->features['is_standard_forms_visible'] && (isset($service->features['standard_forms']) && !empty($service->features['standard_forms']) || isset($service->features['documents']) && is_array($service->features['documents']) && !empty($service->features['documents'])))
                    <a href="#standart-formlar" class="flex items-center px-4 py-2 rounded-lg text-gray-600 hover:bg-[#00352b]/10 hover:text-[#00352b] font-medium transition-colors">
                        <i class="material-icons mr-3 text-lg">description</i>
                        Standart Formlar
                    </a>
                    @endif
                    @if(isset($service->features['is_processing_times_visible']) && $service->features['is_processing_times_visible'] && isset($service->features['processing_times']) && is_array($service->features['processing_times']) && !empty($service->features['processing_times']))
                    <a href="#islem-suresi" class="flex items-center px-4 py-2 rounded-lg text-gray-600 hover:bg-[#00352b]/10 hover:text-[#00352b] font-medium transition-colors">
                        <i class="material-icons mr-3 text-lg">schedule</i>
                        İşlem Süresi
                    </a>
                    @endif
                    @if(isset($service->features['is_fees_visible']) && $service->features['is_fees_visible'] && isset($service->features['fees']) && is_array($service->features['fees']) && !empty($service->features['fees']))
                    <a href="#ucretler" class="flex items-center px-4 py-2 rounded-lg text-gray-600 hover:bg-[#00352b]/10 hover:text-[#00352b] font-medium transition-colors">
                        <i class="material-icons mr-3 text-lg">payment</i>
                        Ücretler
                    </a>
                    @endif
                    @if(isset($service->features['is_payment_options_visible']) && $service->features['is_payment_options_visible'] && isset($service->features['payment_options']) && is_array($service->features['payment_options']) && !empty($service->features['payment_options']))
                    <a href="#odeme-secenekleri" class="flex items-center px-4 py-2 rounded-lg text-gray-600 hover:bg-[#00352b]/10 hover:text-[#00352b] font-medium transition-colors">
                        <i class="material-icons mr-3 text-lg">payment</i>
                        Ödeme Seçenekleri
                    </a>
                    @endif
                    @if((!isset($service->features['is_additional_info_visible']) || $service->features['is_additional_info_visible']) && isset($service->features['additional_info']) && !empty($service->features['additional_info']))
                    <a href="#diger-bilgiler" class="flex items-center px-4 py-2 rounded-lg text-gray-600 hover:bg-[#00352b]/10 hover:text-[#00352b] font-medium transition-colors">
                        <i class="material-icons mr-3 text-lg">info</i>
                        Diğer Bilgiler
                    </a>
                    @endif
                    @if($relatedNews->count() > 0)
                    <a href="#ilgili-haberler" class="flex items-center px-4 py-2 rounded-lg text-gray-600 hover:bg-[#00352b]/10 hover:text-[#00352b] font-medium transition-colors">
                        <i class="material-icons mr-3 text-lg">article</i>
                        İlgili Haberler
                    </a>
                    @endif
                </nav>
            </div>
        </div>
        
        <!-- Sağ İçerik Alanı -->
        <div class="service-content-area">
            <!-- Hizmetin Amacı -->
            @if(isset($service->features['is_purpose_visible']) && $service->features['is_purpose_visible'] && isset($service->features['service_purpose']) && !empty($service->features['service_purpose']))
            <section id="hizmetin-amaci" class="service-content-section">
                <h2>Hizmetin Amacı</h2>
                {!! $service->features['service_purpose'] !!}
            </section>
            @endif
            
            <!-- Kimler Başvurabilir -->
            @if(isset($service->features['is_who_can_apply_visible']) && $service->features['is_who_can_apply_visible'] && isset($service->features['who_can_apply']) && !empty($service->features['who_can_apply']))
            <section id="kimler-basvurabilir" class="service-content-section">
                <h2>Kimler Başvurabilir</h2>
                {!! $service->features['who_can_apply'] !!}
            </section>
            @endif
            
            <!-- Başvuru Şartları -->
            @if(isset($service->features['is_requirements_visible']) && $service->features['is_requirements_visible'] && isset($service->features['requirements']) && !empty($service->features['requirements']))
            <section id="basvuru-sartlari" class="service-content-section">
                <h2>Başvuru Şartları</h2>
                {!! $service->features['requirements'] !!}
            </section>
            @endif
            
            <!-- Başvuru Süreci -->
            @if(isset($service->features['is_application_process_visible']) && $service->features['is_application_process_visible'] && isset($service->features['application_process']) && !empty($service->features['application_process']))
            <section id="basvuru-sureci" class="service-content-section">
                <h2>Başvuru Süreci</h2>
                {!! $service->features['application_process'] !!}
            </section>
            @endif
            
            <!-- İşlem Süresi -->
            @if(isset($service->features['is_processing_times_visible']) && $service->features['is_processing_times_visible'] && isset($service->features['processing_times']) && is_array($service->features['processing_times']) && !empty($service->features['processing_times']))
            <section id="islem-suresi" class="service-content-section">
                <h2>İşlem Süresi</h2>
                <p class="mb-5">{{ $service->title }} hizmetimizin işlem süreleri aşağıda detaylandırılmıştır:</p>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white border border-gray-200 rounded-lg">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 bg-[#00352b]/5 text-left text-xs font-semibold text-gray-700 uppercase border-b">İşlem Adı</th>
                                <th class="px-6 py-3 bg-[#00352b]/5 text-left text-xs font-semibold text-gray-700 uppercase border-b">Süre</th>
                                <th class="px-6 py-3 bg-[#00352b]/5 text-left text-xs font-semibold text-gray-700 uppercase border-b">Açıklama</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($service->features['processing_times'] as $item)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 border-b text-sm">{{ $item['title'] ?? '' }}</td>
                                <td class="px-6 py-4 border-b text-sm">{{ $item['time'] ?? '' }}</td>
                                <td class="px-6 py-4 border-b text-sm">{{ $item['description'] ?? '' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </section>
            @endif
            
            <!-- Ücretler -->
            @if(isset($service->features['is_fees_visible']) && $service->features['is_fees_visible'] && isset($service->features['fees']) && is_array($service->features['fees']) && !empty($service->features['fees']))
            <section id="ucretler" class="service-content-section">
                <h2>Ücretler</h2>
                <p class="mb-5">{{ $service->title }} hizmetimiz için ücret tarifesi aşağıda belirtilmiştir:</p>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white border border-gray-200 rounded-lg">
                        <thead>
                            <tr>
                                <th class="px-6 py-4 bg-[#00352b]/5 text-left text-sm font-semibold text-gray-700 border-b">Hizmet Paketi</th>
                                <th class="px-6 py-4 bg-[#00352b]/5 text-left text-sm font-semibold text-gray-700 border-b">Açıklama</th>
                                <th class="px-6 py-4 bg-[#00352b]/5 text-left text-sm font-semibold text-gray-700 border-b">Fiyat</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($service->features['fees'] as $fee)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 border-b text-sm font-medium text-gray-800">{{ $fee['package'] ?? '' }}</td>
                                <td class="px-6 py-4 border-b text-sm text-gray-700">{{ $fee['description'] ?? '' }}</td>
                                <td class="px-6 py-4 border-b text-sm">
                                    <span class="inline-block bg-blue-50 text-blue-700 px-3 py-1 rounded-full font-medium">{{ $fee['price'] ?? '' }}</span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </section>
            @endif
            
            <!-- Ödeme Seçenekleri -->
            @if(isset($service->features['is_payment_options_visible']) && $service->features['is_payment_options_visible'] && isset($service->features['payment_options']) && is_array($service->features['payment_options']) && !empty($service->features['payment_options']))
            <section id="odeme-secenekleri" class="service-content-section">
                <h2>Ödeme Seçenekleri</h2>
                <p class="mb-5">{{ $service->title }} hizmetimiz için ödeme seçenekleri aşağıda belirtilmiştir:</p>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white border border-gray-200 rounded-lg">
                        <thead>
                            <tr>
                                <th class="px-6 py-4 bg-[#00352b]/5 text-left text-sm font-semibold text-gray-700 border-b">Ödeme Yöntemi</th>
                                <th class="px-6 py-4 bg-[#00352b]/5 text-left text-sm font-semibold text-gray-700 border-b">Vade</th>
                                <th class="px-6 py-4 bg-[#00352b]/5 text-left text-sm font-semibold text-gray-700 border-b">Açıklama</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($service->features['payment_options'] as $option)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 border-b text-sm font-medium text-gray-800">{{ $option['method'] ?? '' }}</td>
                                <td class="px-6 py-4 border-b text-sm text-gray-700">{{ $option['term'] ?? '' }}</td>
                                <td class="px-6 py-4 border-b text-sm text-gray-700">{{ $option['description'] ?? '' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </section>
            @endif
            
            <!-- Diğer Bilgiler -->
            @if((!isset($service->features['is_additional_info_visible']) || $service->features['is_additional_info_visible']) && isset($service->features['additional_info']) && !empty($service->features['additional_info']))
            <section id="diger-bilgiler" class="service-content-section">
                <h2>Diğer Bilgiler</h2>
                {!! $service->features['additional_info'] !!}
            </section>
            @endif
            
            <!-- Standart Formlar -->
            @if((!isset($service->features['is_standard_forms_visible']) || $service->features['is_standard_forms_visible']) && (isset($service->features['standard_forms']) && !empty($service->features['standard_forms']) || isset($service->features['documents']) && is_array($service->features['documents']) && !empty($service->features['documents'])))
            <section id="standart-formlar" class="service-content-section">
                <h2>Standart Formlar</h2>
                @if(isset($service->features['standard_forms']) && !empty($service->features['standard_forms']))
                    {!! $service->features['standard_forms'] !!}
                @endif
                
                @if(isset($service->features['documents']) && is_array($service->features['documents']) && !empty($service->features['documents']))
                <div class="mt-6">
                    <h3 class="font-semibold text-lg text-gray-800 mb-4">İndirilebilir Dosyalar</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        @foreach($service->features['documents'] as $document)
                        <div class="bg-white border border-gray-200 rounded-lg overflow-hidden hover:shadow-md transition-all">
                            <div class="p-5 flex flex-col h-full">
                                <div class="flex items-center mb-3">
                                    <span class="material-icons text-[#00352b] mr-2">description</span>
                                    <h3 class="font-semibold text-gray-800">{{ $document['name'] }}</h3>
                                </div>
                                @if(isset($document['description']) && !empty($document['description']))
                                <p class="text-sm text-gray-600 mb-4">{{ $document['description'] }}</p>
                                @endif
                                <div class="mt-auto flex items-center">
                                    <a href="{{ $document['file'] }}" target="_blank" class="inline-flex items-center text-[#00352b] hover:text-[#20846c] transition-colors">
                                        <span class="material-icons mr-1 text-sm">file_download</span>
                                        <span class="text-sm font-medium">İndir</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </section>
            @endif
            
            <!-- İlgili Haberler -->
            @if($relatedNews->count() > 0)
            <section id="ilgili-haberler" class="service-content-section">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="mb-0">İlgili Haberler</h2>
                    <div class="text-sm text-gray-500">
                        Toplam {{ $relatedNews->total() }} haber bulundu
                    </div>
                </div>
                <p class="mb-5">{{ $service->title }} hizmetimizle ilgili güncel haberler ve duyurular:</p>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    @foreach($relatedNews as $news)
                    <div class="bg-white border border-gray-200 rounded-lg overflow-hidden hover:shadow-md transition-all">
                        <div class="p-5 flex flex-col h-full">
                            <div class="flex items-center mb-3">
                                <span class="material-icons text-[#00352b] mr-2">article</span>
                                <h3 class="font-semibold text-gray-800">{{ $news->title }}</h3>
                            </div>
                            @if($news->summary)
                            <p class="text-sm text-gray-600 mb-4 flex-grow">{{ Str::limit($news->summary, 120) }}</p>
                            @endif
                            <div class="mt-auto flex items-center justify-between">
                                <a href="{{ route('news.show', $news->slug) }}" class="inline-flex items-center text-[#00352b] hover:text-[#20846c] transition-colors">
                                    <span class="text-sm font-medium">Haberi Oku</span>
                                    <span class="material-icons ml-1 text-sm">arrow_forward</span>
                                </a>
                                @if($news->published_at)
                                <span class="text-xs text-gray-500">{{ $news->published_at->format('d.m.Y') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                                        <!-- Sayfalama -->
                        @if($relatedNews->hasPages())
                        <div class="mt-6">
                            <div class="flex justify-center items-center">
                                <div class="inline-flex items-center space-x-1 bg-gray-50 rounded-lg p-2 shadow-sm">
                                    {{ $relatedNews->appends(request()->except('page'))->fragment('ilgili-haberler')->links('custom.pagination') }}
                                </div>
                            </div>
                        </div>
                        @endif
            </section>
            @endif
            
            <!-- Sayfa Geri Bildirim Widget'ı -->
            @include('components.page-feedback', [
                'pageUrl' => request()->url(),
                'pageTitle' => $service->title
            ])
        </div>
    </div>
</div>
@endsection



@section('after_scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Mobilde sidebar gizli olduğu için JavaScript'i çalıştırma
        if (window.innerWidth <= 768) {
            return;
        }
        
        // Basit smooth scroll davranışı için
        const menuLinks = document.querySelectorAll('#sidebar nav a');
        const sidebarElement = document.getElementById('sidebar');
        const contentArea = document.querySelector('.service-content-area');
        const footer = document.querySelector('footer') || document.querySelector('.footer');
        const lastSection = contentArea.querySelector('.service-content-section:last-child');
        
        // Sidebar offset pozisyonunu hesapla
        let sidebarOffset = sidebarElement.getBoundingClientRect().top + window.scrollY;
        
        // Sidebar'ın pozisyon ve boyutlarını güncelleyen fonksiyon
        function updateSidebarMetrics() {
            // scrolledDown sınıfını geçici olarak kaldır
            const wasScrolledDown = sidebarElement.classList.contains('scrolledDown');
            if (wasScrolledDown) {
                sidebarElement.classList.remove('scrolledDown');
                
                // Inline stilleri temizle
                const stickyContainer = sidebarElement.querySelector('.sticky-container');
                if (stickyContainer) {
                    stickyContainer.removeAttribute('style'); 
                }
            }
            
            // Ölçümleri yeniden hesapla
            sidebarOffset = sidebarElement.getBoundingClientRect().top + window.scrollY;
            
            // scrolledDown sınıfını geri ekle
            if (wasScrolledDown && window.scrollY > sidebarOffset - 20) {
                // scrolledDown ekle
                sidebarElement.classList.add('scrolledDown');
            }
        }
        
        // Sayfa yüklendiğinde ve pencere boyutu değiştiğinde metrikleri güncelle
        updateSidebarMetrics();
        
        // Pencere boyutu değiştiğinde offset'i yeniden hesapla
        window.addEventListener('resize', function() {
            // Mobilde sidebar gizli olduğu için çalıştırma
            if (window.innerWidth <= 768) {
                return;
            }
            updateSidebarMetrics();
        });
        
        menuLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                
                const targetId = this.getAttribute('href');
                const targetSection = document.querySelector(targetId);
                
                if (targetSection) {
                    // Sayfı hedef bölüme doğru kaydır
                    window.scrollTo({
                        top: targetSection.offsetTop - 20, 
                        behavior: 'smooth'
                    });
                }
            });
        });
        
        // Sidebar'ın sayfa kaydırıldıkça davranışı
        window.addEventListener('scroll', function() {
            // Mobilde sidebar gizli olduğu için çalıştırma
            if (window.innerWidth <= 768) {
                return;
            }
            
            const currentScrollY = window.scrollY;
            const windowHeight = window.innerHeight;
            const stickyContainer = sidebarElement.querySelector('.sticky-container');
            
            // Son bölümün bitimini tam olarak hesapla (varsa)
            let exactContentBottom = 0;
            if (lastSection) {
                const lastSectionRect = lastSection.getBoundingClientRect();
                exactContentBottom = lastSectionRect.bottom + window.scrollY;
            } else {
                // Son bölüm yoksa genel içerik alanı bitimini kullan
                exactContentBottom = contentArea.offsetTop + contentArea.offsetHeight;
            }
            
            // Sayfa sonuna yaklaştık mı kontrol et
            const stickyContainerHeight = stickyContainer ? stickyContainer.offsetHeight : 0;
            
            // Sidebar'ın hizalı olması gereken pozisyonu hesapla
            const targetPosition = exactContentBottom - stickyContainerHeight;
            
            // Sidebar'ın içerik alanı bitiminde durması gereken koşulu belirle
            const shouldStopSticky = currentScrollY > targetPosition - 20;
            
            // Footer'ın pozisyonunu hesapla (eğer footer varsa)
            let footerTop = Number.MAX_SAFE_INTEGER;
            if (footer) {
                footerTop = footer.getBoundingClientRect().top + window.scrollY;
            }
            
            if (currentScrollY > sidebarOffset - 20) {
                if (!sidebarElement.classList.contains('scrolledDown')) {
                    sidebarElement.classList.add('scrolledDown');
                }
                
                if (stickyContainer) {
                    // Sticky container'ın sol pozisyonunu güncelle
                    const sidebarRect = sidebarElement.getBoundingClientRect();
                    
                    // İçerik alanı bittiyse içindekiler menüsünü durdur
                    if (shouldStopSticky) {
                        // İçerik alanı sonunda sabit kal
                        sidebarElement.classList.remove('scrolledDown');
                        stickyContainer.style.position = 'absolute';
                        
                        // Hassas hizalama için direkt son bölümün alt konumunu kullan 
                        // (sidebarOffset değerine göre olan mesafe hesaplanır)
                        const exactOffset = exactContentBottom - stickyContainerHeight - sidebarOffset;
                        stickyContainer.style.top = exactOffset + 'px';
                        stickyContainer.style.left = '0';
                    } else {
                        // Footer'a yaklaşırken konumu ayarla (footer varmak ve çakışma olacaksa)
                        const footerDistance = footerTop - (currentScrollY + stickyContainerHeight + 20);
                        
                        if (footerDistance < 0 && footerTop < exactContentBottom) {
                            // Footer görünür olduğunda, içindekiler menüsünün bottom pozisyonunu ayarla
                            stickyContainer.style.position = 'fixed';
                            stickyContainer.style.top = 'auto';
                            stickyContainer.style.bottom = Math.abs(footerDistance) + 20 + 'px';
                            stickyContainer.style.left = sidebarRect.left + 'px';
                        } else {
                            // Normal sticky pozisyon
                            stickyContainer.style.position = 'fixed';
                            stickyContainer.style.top = '20px';
                            stickyContainer.style.bottom = 'auto';
                            stickyContainer.style.left = sidebarRect.left + 'px';
                        }
                    }
                }
            } else {
                // Sticky davranışını deaktif et
                if (sidebarElement.classList.contains('scrolledDown')) {
                    sidebarElement.classList.remove('scrolledDown');
                    
                    if (stickyContainer) {
                        stickyContainer.removeAttribute('style');
                    }
                }
            }
            

        });
    });
</script>
@endsection 
