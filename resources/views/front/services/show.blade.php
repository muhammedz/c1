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
            display: none;
        }
        
        #sidebar.active {
            display: block;
            position: fixed;
            top: 15px;
            left: 15px;
            right: 15px;
            width: auto;
            z-index: 100;
        }
        
        #sidebar.scrolledDown .sticky-container {
            position: relative;
            top: 0;
            left: 0 !important; /* Mobilde varsayılan pozisyon, JS'in eklediği style'ı ezmek için !important kullanıldı */
            width: 100% !important; /* Tam genişlik */
            transform: none; /* Transform değerini kaldırdık */
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
                    <a href="#basvuru-sureci" class="inline-flex items-center px-5 py-2.5 bg-[#e6a23c] text-white rounded-md hover:bg-[#e6a23c]/90 transition-colors font-medium shadow-lg shadow-[#e6a23c]/20">
                        <span class="material-icons mr-2 text-sm">edit_document</span>
                        Başvuru Yap
                    </a>
                    @if(isset($service->features['is_standard_forms_visible']) && $service->features['is_standard_forms_visible'])
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
                                <span class="font-bold">444 1 234</span>
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
                    @if(isset($service->features['is_purpose_visible']) && $service->features['is_purpose_visible'])
                    <a href="#hizmetin-amaci" class="flex items-center px-4 py-2 rounded-lg text-gray-600 hover:bg-[#00352b]/10 hover:text-[#00352b] font-medium transition-colors">
                        <i class="material-icons mr-3 text-lg">lightbulb</i>
                        Hizmetin Amacı
                    </a>
                    @endif
                    @if(isset($service->features['is_who_can_apply_visible']) && $service->features['is_who_can_apply_visible'])
                    <a href="#kimler-basvurabilir" class="flex items-center px-4 py-2 rounded-lg text-gray-600 hover:bg-[#00352b]/10 hover:text-[#00352b] font-medium transition-colors">
                        <i class="material-icons mr-3 text-lg">people</i>
                        Kimler Başvurabilir
                    </a>
                    @endif
                    @if(isset($service->features['is_requirements_visible']) && $service->features['is_requirements_visible'])
                    <a href="#basvuru-sartlari" class="flex items-center px-4 py-2 rounded-lg text-gray-600 hover:bg-[#00352b]/10 hover:text-[#00352b] font-medium transition-colors">
                        <i class="material-icons mr-3 text-lg">assignment</i>
                        Başvuru Şartları
                    </a>
                    @endif
                    @if(isset($service->features['is_application_process_visible']) && $service->features['is_application_process_visible'])
                    <a href="#basvuru-sureci" class="flex items-center px-4 py-2 rounded-lg text-gray-600 hover:bg-[#00352b]/10 hover:text-[#00352b] font-medium transition-colors">
                        <i class="material-icons mr-3 text-lg">timeline</i>
                        Başvuru Süreci
                    </a>
                    @endif
                    @if(isset($service->features['is_standard_forms_visible']) && $service->features['is_standard_forms_visible'])
                    <a href="#standart-formlar" class="flex items-center px-4 py-2 rounded-lg text-gray-600 hover:bg-[#00352b]/10 hover:text-[#00352b] font-medium transition-colors">
                        <i class="material-icons mr-3 text-lg">description</i>
                        Standart Formlar
                    </a>
                    @endif
                    @if(isset($service->features['is_processing_times_visible']) && $service->features['is_processing_times_visible'])
                    <a href="#islem-suresi" class="flex items-center px-4 py-2 rounded-lg text-gray-600 hover:bg-[#00352b]/10 hover:text-[#00352b] font-medium transition-colors">
                        <i class="material-icons mr-3 text-lg">schedule</i>
                        İşlem Süresi
                    </a>
                    @endif
                    @if(isset($service->features['is_fees_visible']) && $service->features['is_fees_visible'])
                    <a href="#ucretler" class="flex items-center px-4 py-2 rounded-lg text-gray-600 hover:bg-[#00352b]/10 hover:text-[#00352b] font-medium transition-colors">
                        <i class="material-icons mr-3 text-lg">payment</i>
                        Ücretler
                    </a>
                    @endif
                    @if(isset($service->features['is_additional_info_visible']) && $service->features['is_additional_info_visible'])
                    <a href="#diger-bilgiler" class="flex items-center px-4 py-2 rounded-lg text-gray-600 hover:bg-[#00352b]/10 hover:text-[#00352b] font-medium transition-colors">
                        <i class="material-icons mr-3 text-lg">info</i>
                        Diğer Bilgiler
                    </a>
                    @endif
                    <a href="#ilgili-haberler" class="flex items-center px-4 py-2 rounded-lg text-gray-600 hover:bg-[#00352b]/10 hover:text-[#00352b] font-medium transition-colors">
                        <i class="material-icons mr-3 text-lg">article</i>
                        İlgili Haberler
                    </a>
                </nav>
            </div>
        </div>
        
        <!-- Sağ İçerik Alanı -->
        <div class="service-content-area">
            <!-- Hizmetin Amacı -->
            @if(isset($service->features['is_purpose_visible']) && $service->features['is_purpose_visible'])
            <section id="hizmetin-amaci" class="service-content-section">
                <h2>Hizmetin Amacı</h2>
                @if(isset($service->features['service_purpose']) && !empty($service->features['service_purpose']))
                    {!! $service->features['service_purpose'] !!}
                @else
                <p class="mb-4">{{ $service->title }} hizmetimiz, vatandaşlarımızın belediye kaynaklarından en hızlı ve verimli şekilde faydalanabilmesini amaçlamaktadır. Bu hizmet kapsamında, yaşam kalitenizi artıracak çözümler sunmak ve kent yaşamını daha konforlu hale getirmek için çalışmaktayız.</p>
                <p class="mb-4">Belediyemiz tarafından sunulan bu hizmet, toplumsal ihtiyaçları karşılarken çevreye duyarlı, sürdürülebilir ve teknolojik yenilikleri içeren bir yaklaşımla tasarlanmıştır.</p>
                
                <div class="bg-[#00352b]/10 border-l-4 border-[#00352b] p-4 my-6 rounded-r">
                    <p class="text-[#00352b] font-medium">Hizmet Hakkında Önemli Not</p>
                    <p class="text-sm text-[#00352b]/90 mt-1">Bu hizmetten faydalanabilmek için belediyemize başvuruda bulunmanız ve gerekli şartları sağlamanız gerekmektedir. Detaylı bilgi için aşağıdaki bölümleri inceleyebilirsiniz.</p>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6">
                    <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
                        <div class="flex items-center mb-3">
                            <span class="material-icons text-[#00352b] mr-2">check_circle</span>
                            <h3 class="font-semibold text-gray-800">Kaliteli Hizmet</h3>
                        </div>
                        <p class="text-sm text-gray-600">En yüksek standartlarda hizmet sunuyoruz.</p>
                    </div>
                    <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
                        <div class="flex items-center mb-3">
                            <span class="material-icons text-[#20846c] mr-2">speed</span>
                            <h3 class="font-semibold text-gray-800">Hızlı İşlem</h3>
                        </div>
                        <p class="text-sm text-gray-600">Başvurularınız mümkün olan en kısa sürede sonuçlandırılır.</p>
                    </div>
                    <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
                        <div class="flex items-center mb-3">
                            <span class="material-icons text-[#e6a23c] mr-2">support_agent</span>
                            <h3 class="font-semibold text-gray-800">7/24 Destek</h3>
                        </div>
                        <p class="text-sm text-gray-600">Sorularınız için her zaman yanınızdayız.</p>
                    </div>
                </div>
                @endif
            </section>
            @endif
            
            <!-- Kimler Başvurabilir -->
            @if(isset($service->features['is_who_can_apply_visible']) && $service->features['is_who_can_apply_visible'])
            <section id="kimler-basvurabilir" class="service-content-section">
                <h2>Kimler Başvurabilir</h2>
                @if(isset($service->features['who_can_apply']) && !empty($service->features['who_can_apply']))
                    {!! $service->features['who_can_apply'] !!}
                @else
                <p class="mb-5">{{ $service->title }} hizmetimizden aşağıdaki koşulları sağlayan vatandaşlarımız faydalanabilir:</p>
                
                <div class="space-y-4 mb-6">
                    <div class="flex items-start">
                        <div class="bg-[#00352b]/10 p-2 rounded-full mr-3 mt-1">
                            <span class="material-icons text-[#00352b] text-sm">person</span>
                        </div>
                        <div>
                            <h3 class="font-medium text-gray-800 mb-1">Belediye Sınırları İçinde İkamet Edenler</h3>
                            <p class="text-gray-600 text-sm">Belediyemiz sınırları içinde ikamet eden tüm vatandaşlarımız bu hizmete başvurabilir.</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start">
                        <div class="bg-[#00352b]/10 p-2 rounded-full mr-3 mt-1">
                            <span class="material-icons text-[#00352b] text-sm">business</span>
                        </div>
                        <div>
                            <h3 class="font-medium text-gray-800 mb-1">Belediye Sınırları İçindeki İşletmeler</h3>
                            <p class="text-gray-600 text-sm">Belediyemiz sınırları içinde faaliyet gösteren işletmeler, firmalar ve kurumlar.</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start">
                        <div class="bg-[#00352b]/10 p-2 rounded-full mr-3 mt-1">
                            <span class="material-icons text-[#00352b] text-sm">diversity_3</span>
                        </div>
                        <div>
                            <h3 class="font-medium text-gray-800 mb-1">Sivil Toplum Kuruluşları</h3>
                            <p class="text-gray-600 text-sm">Belediyemiz sınırları içinde faaliyet gösteren dernekler, vakıflar ve diğer sivil toplum kuruluşları.</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start">
                        <div class="bg-[#00352b]/10 p-2 rounded-full mr-3 mt-1">
                            <span class="material-icons text-[#00352b] text-sm">school</span>
                        </div>
                        <div>
                            <h3 class="font-medium text-gray-800 mb-1">Eğitim Kurumları</h3>
                            <p class="text-gray-600 text-sm">Belediyemiz sınırları içinde bulunan okullar, kurslar ve diğer eğitim kurumları.</p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-[#e6a23c]/10 border-l-4 border-[#e6a23c] p-4 rounded-r">
                    <div class="flex items-center">
                        <span class="material-icons text-[#e6a23c] mr-2">info</span>
                        <p class="text-[#e6a23c]/90 font-medium">Özel Durum Bildirimi</p>
                    </div>
                    <p class="text-sm text-[#e6a23c]/80 mt-1">Belediye sınırları dışında ikamet eden vatandaşlarımız için özel durum değerlendirmesi yapılabilmektedir. Detaylı bilgi için lütfen belediyemizle iletişime geçiniz.</p>
                </div>
                @endif
            </section>
            @endif
            
            <!-- Başvuru Şartları -->
            @if(isset($service->features['is_requirements_visible']) && $service->features['is_requirements_visible'])
            <section id="basvuru-sartlari" class="service-content-section">
                <h2>Başvuru Şartları</h2>
                @if(isset($service->features['requirements']) && !empty($service->features['requirements']))
                    {!! $service->features['requirements'] !!}
                @else
                <p class="mb-4">{{ $service->title }} hizmetimize başvurabilmek için aşağıdaki şartları sağlamanız gerekmektedir:</p>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div class="bg-white border border-gray-200 rounded-lg p-5">
                        <h3 class="font-semibold text-lg text-[#00352b] mb-3 border-b pb-2">Gerekli Belgeler</h3>
                        <ul class="space-y-3">
                            <li class="flex items-center">
                                <span class="material-icons text-[#00352b] mr-2 text-sm">check</span>
                                <span class="text-gray-700">Kimlik fotokopisi (T.C. Kimlik Kartı / Nüfus Cüzdanı)</span>
                            </li>
                            <li class="flex items-center">
                                <span class="material-icons text-[#00352b] mr-2 text-sm">check</span>
                                <span class="text-gray-700">İkametgah belgesi (son 3 ay içinde alınmış)</span>
                            </li>
                            <li class="flex items-center">
                                <span class="material-icons text-[#00352b] mr-2 text-sm">check</span>
                                <span class="text-gray-700">Başvuru formu (doldurulmuş ve imzalanmış)</span>
                            </li>
                            <li class="flex items-center">
                                <span class="material-icons text-[#00352b] mr-2 text-sm">check</span>
                                <span class="text-gray-700">Hizmet türüne göre ek belgeler (varsa)</span>
                            </li>
                        </ul>
                    </div>
                    
                    <div class="bg-white border border-gray-200 rounded-lg p-5">
                        <h3 class="font-semibold text-lg text-[#00352b] mb-3 border-b pb-2">Koşullar</h3>
                        <ul class="space-y-3">
                            <li class="flex items-start">
                                <span class="material-icons text-[#20846c] mr-2 text-sm mt-1">info</span>
                                <span class="text-gray-700">18 yaşını doldurmuş olmak</span>
                            </li>
                            <li class="flex items-start">
                                <span class="material-icons text-[#20846c] mr-2 text-sm mt-1">info</span>
                                <span class="text-gray-700">Belediye sınırları içinde ikamet ediyor olmak</span>
                            </li>
                            <li class="flex items-start">
                                <span class="material-icons text-[#20846c] mr-2 text-sm mt-1">info</span>
                                <span class="text-gray-700">Daha önce aynı hizmetten faydalanmamış olmak (yılda bir kez başvuru yapılabilir)</span>
                            </li>
                            <li class="flex items-start">
                                <span class="material-icons text-[#20846c] mr-2 text-sm mt-1">info</span>
                                <span class="text-gray-700">Belediyeye borcunun bulunmaması</span>
                            </li>
                        </ul>
                        </div>
                        </div>
                
                <div class="bg-red-50 border-l-4 border-red-400 p-4 rounded-r">
                    <div class="flex items-center">
                        <span class="material-icons text-red-500 mr-2">warning</span>
                        <p class="text-red-700 font-medium">Önemli Uyarı</p>
                    </div>
                    <p class="text-sm text-red-600 mt-1">Eksik belge ile yapılan başvurular değerlendirmeye alınmamaktadır. Lütfen başvuru öncesi tüm belgelerin eksiksiz olduğundan emin olunuz.</p>
                </div>
                @endif
            </section>
            @endif
            
            <!-- Başvuru Süreci -->
            @if(isset($service->features['is_application_process_visible']) && $service->features['is_application_process_visible'])
            <section id="basvuru-sureci" class="service-content-section">
                <h2>Başvuru Süreci</h2>
                @if(isset($service->features['application_process']) && !empty($service->features['application_process']))
                    {!! $service->features['application_process'] !!}
                @else
                <p class="mb-5">{{ $service->title }} hizmetimiz için başvuru sürecinde izlemeniz gereken adımlar aşağıda belirtilmiştir:</p>
                
                <div class="relative border-l-2 border-gray-200 ml-3 pl-8 pb-1">
                    <div class="mb-8 relative">
                        <div class="absolute -left-10 top-0 bg-[#00352b] text-white rounded-full w-6 h-6 flex items-center justify-center">
                            <span>1</span>
                        </div>
                        <h3 class="font-semibold text-gray-800 mb-2">Ön Başvuru</h3>
                        <p class="text-gray-600 mb-3">Online sistem üzerinden veya belediye binasında başvuru formunu doldurarak ön başvurunuzu yapabilirsiniz.</p>
                        <div class="flex flex-wrap gap-2">
                            <a href="/online-basvuru" class="inline-flex items-center px-3 py-1.5 bg-[#00352b]/10 text-[#00352b] text-sm rounded-md hover:bg-[#00352b]/20 transition-colors">
                                <span class="material-icons text-sm mr-1">computer</span>
                                Online Başvuru
                            </a>
                            <a href="/basvuru-formu" class="inline-flex items-center px-3 py-1.5 bg-gray-50 text-gray-700 text-sm rounded-md hover:bg-gray-100 transition-colors">
                                <span class="material-icons text-sm mr-1">download</span>
                                Formu İndir
                            </a>
                        </div>
                    </div>
                    
                    <div class="mb-8 relative">
                        <div class="absolute -left-10 top-0 bg-[#00352b] text-white rounded-full w-6 h-6 flex items-center justify-center">
                            <span>2</span>
                        </div>
                        <h3 class="font-semibold text-gray-800 mb-2">Belge Teslimi</h3>
                        <p class="text-gray-600">Gerekli belgelerinizi eksiksiz şekilde belediyemize şahsen teslim ediniz veya online olarak yükleyiniz.</p>
                    </div>
                    
                    <div class="mb-8 relative">
                        <div class="absolute -left-10 top-0 bg-[#00352b] text-white rounded-full w-6 h-6 flex items-center justify-center">
                            <span>3</span>
                        </div>
                        <h3 class="font-semibold text-gray-800 mb-2">Değerlendirme</h3>
                        <p class="text-gray-600">Başvurunuz ilgili birimlerimiz tarafından değerlendirilir. Değerlendirme süreci ortalama 5-7 iş günü sürmektedir.</p>
                    </div>
                    
                    <div class="mb-8 relative">
                        <div class="absolute -left-10 top-0 bg-[#00352b] text-white rounded-full w-6 h-6 flex items-center justify-center">
                            <span>4</span>
                        </div>
                        <h3 class="font-semibold text-gray-800 mb-2">Sonuç Bildirimi</h3>
                        <p class="text-gray-600">Başvurunuzun sonucu size SMS, e-posta veya telefon yoluyla bildirilecektir.</p>
                    </div>
                </div>
                @endif
            </section>
            @endif
            
            <!-- İşlem Süresi -->
            @if(isset($service->features['is_processing_times_visible']) && $service->features['is_processing_times_visible'])
            <section id="islem-suresi" class="service-content-section">
                <h2>İşlem Süresi</h2>
                @if(isset($service->features['processing_times']) && is_array($service->features['processing_times']) && !empty($service->features['processing_times']))
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
                @else
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
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 border-b text-sm">Başvuru Değerlendirme</td>
                                <td class="px-6 py-4 border-b text-sm">3-5 İş Günü</td>
                                <td class="px-6 py-4 border-b text-sm">Başvurunuzun ilk değerlendirmesi</td>
                            </tr>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 border-b text-sm">Evrak Kontrolü</td>
                                <td class="px-6 py-4 border-b text-sm">1-2 İş Günü</td>
                                <td class="px-6 py-4 border-b text-sm">Gerekli belgelerin kontrol edilmesi</td>
                            </tr>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 border-b text-sm">Onay Süreci</td>
                                <td class="px-6 py-4 border-b text-sm">2-3 İş Günü</td>
                                <td class="px-6 py-4 border-b text-sm">Yetkili birimlerden onay alınması</td>
                            </tr>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 border-b text-sm">Hizmet Sunumu</td>
                                <td class="px-6 py-4 border-b text-sm">5-7 İş Günü</td>
                                <td class="px-6 py-4 border-b text-sm">Hizmetin gerçekleştirilmesi ve tamamlanması</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                @endif
                
                <div class="mt-4 bg-blue-50 border-l-4 border-blue-400 p-4 rounded-r">
                    <div class="flex items-center">
                        <span class="material-icons text-blue-500 mr-2">info</span>
                        <p class="text-blue-700 font-medium">Bilgilendirme</p>
                    </div>
                    <p class="text-sm text-blue-600 mt-1">Belirtilen süreler ortalama değerlerdir. İşlem yoğunluğu, başvuru sayısı veya özel durumlar nedeniyle değişiklik gösterebilir.</p>
                </div>
            </section>
            @endif
            
            <!-- Ücretler -->
            @if(isset($service->features['is_fees_visible']) && $service->features['is_fees_visible'])
            <section id="ucretler" class="service-content-section">
                <h2>Ücretler</h2>
                @if(isset($service->features['fees']) && is_array($service->features['fees']) && !empty($service->features['fees']))
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
                @else
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
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 border-b text-sm font-medium text-gray-800">Standart Paket</td>
                                <td class="px-6 py-4 border-b text-sm text-gray-700">Temel hizmetler ve destek içerir</td>
                                <td class="px-6 py-4 border-b text-sm">
                                    <span class="inline-block bg-blue-50 text-blue-700 px-3 py-1 rounded-full font-medium">Ücretsiz</span>
                                </td>
                            </tr>
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 border-b text-sm font-medium text-gray-800">Gelişmiş Paket</td>
                                <td class="px-6 py-4 border-b text-sm text-gray-700">Standart hizmetler + öncelikli destek</td>
                                <td class="px-6 py-4 border-b text-sm">
                                    <span class="inline-block bg-blue-50 text-blue-700 px-3 py-1 rounded-full font-medium">150 ₺</span>
                                </td>
                            </tr>
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 border-b text-sm font-medium text-gray-800">Premium Paket</td>
                                <td class="px-6 py-4 border-b text-sm text-gray-700">Tüm hizmetler + özel hizmet danışmanı</td>
                                <td class="px-6 py-4 border-b text-sm">
                                    <span class="inline-block bg-blue-50 text-blue-700 px-3 py-1 rounded-full font-medium">300 ₺</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                @endif
                
                <div class="mt-6 bg-[#00352b]/10 p-5 rounded-lg flex items-start">
                    <div class="flex-shrink-0 mr-4">
                        <span class="material-icons text-[#00352b] text-3xl">payments</span>
                    </div>
                    <div>
                        <h3 class="font-semibold text-lg text-[#00352b] mb-2">Ödeme Seçenekleri</h3>
                        <p class="text-gray-700 text-sm mb-4">Belediyemiz hizmet ücretlerinizi aşağıdaki yöntemlerle ödeyebilirsiniz:</p>
                        
                        @if(isset($service->features['payment_options']) && is_array($service->features['payment_options']) && !empty($service->features['payment_options']))
                            <div class="grid grid-cols-1 sm:grid-cols-1 gap-3">
                                @foreach($service->features['payment_options'] as $option)
                                <div class="flex items-center">
                                    <span class="material-icons text-gray-500 mr-2 text-sm">payment</span>
                                    <div>
                                        <span class="text-sm font-medium text-gray-700">{{ $option['method'] ?? '' }}</span>
                                        @if(isset($option['term']) && !empty($option['term']))
                                        <span class="text-sm text-gray-500 ml-2">({{ $option['term'] }})</span>
                                        @endif
                                        @if(isset($option['description']) && !empty($option['description']))
                                        <p class="text-xs text-gray-600 mt-1">{{ $option['description'] }}</p>
                                        @endif
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div class="flex items-center">
                                    <span class="material-icons text-gray-500 mr-2 text-sm">credit_card</span>
                                    <span class="text-sm text-gray-700">Kredi/Banka Kartı</span>
                                </div>
                                <div class="flex items-center">
                                    <span class="material-icons text-gray-500 mr-2 text-sm">account_balance</span>
                                    <span class="text-sm text-gray-700">Havale/EFT</span>
                                </div>
                                <div class="flex items-center">
                                    <span class="material-icons text-gray-500 mr-2 text-sm">apps</span>
                                    <span class="text-sm text-gray-700">Online Ödeme</span>
                                </div>
                                <div class="flex items-center">
                                    <span class="material-icons text-gray-500 mr-2 text-sm">local_atm</span>
                                    <span class="text-sm text-gray-700">Belediye Vezneleri</span>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </section>
            @endif
            
            <!-- Diğer Bilgiler -->
            @if(isset($service->features['is_additional_info_visible']) && $service->features['is_additional_info_visible'])
            <section id="diger-bilgiler" class="service-content-section">
                <h2>Diğer Bilgiler</h2>
                @if(isset($service->features['additional_info']) && !empty($service->features['additional_info']))
                    {!! $service->features['additional_info'] !!}
                @else
                <p class="mb-4">{{ $service->title }} hizmetimiz ile ilgili bilmeniz gereken diğer önemli bilgiler:</p>
                
                <div class="space-y-6 mb-6">
                    <div class="bg-white border border-gray-200 rounded-lg p-5">
                        <h3 class="font-semibold text-lg text-[#00352b] mb-3 border-b pb-2">Sık Sorulan Sorular</h3>
                        <div class="space-y-4">
                            <div>
                                <h4 class="font-medium text-gray-800 mb-1">Başvuru durumumu nasıl öğrenebilirim?</h4>
                                <p class="text-gray-600 text-sm">Başvuru durumunuzu online sistem üzerinden takip edebilir veya çağrı merkezimizi arayabilirsiniz.</p>
                            </div>
                            <div>
                                <h4 class="font-medium text-gray-800 mb-1">Eksik belge tamamlama süresi var mı?</h4>
                                <p class="text-gray-600 text-sm">Eksik belge tamamlama için başvuru tarihinden itibaren 15 gün süreniz bulunmaktadır.</p>
                            </div>
                            <div>
                                <h4 class="font-medium text-gray-800 mb-1">Bu hizmeti yılda kaç kez kullanabilirim?</h4>
                                <p class="text-gray-600 text-sm">Bu hizmet, yılda bir kez kullanılabilmektedir. Özel durumlar için belediyemizle iletişime geçebilirsiniz.</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white border border-gray-200 rounded-lg p-5">
                        <h3 class="font-semibold text-lg text-[#00352b] mb-3 border-b pb-2">İlgili Mevzuat</h3>
                        <ul class="space-y-2">
                            <li class="flex items-start">
                                <span class="material-icons text-gray-500 mr-2 text-sm mt-0.5">description</span>
                                <span class="text-gray-700 text-sm">5393 Sayılı Belediye Kanunu</span>
                            </li>
                            <li class="flex items-start">
                                <span class="material-icons text-gray-500 mr-2 text-sm mt-0.5">description</span>
                                <span class="text-gray-700 text-sm">2464 Sayılı Belediye Gelirleri Kanunu</span>
                            </li>
                            <li class="flex items-start">
                                <span class="material-icons text-gray-500 mr-2 text-sm mt-0.5">description</span>
                                <span class="text-gray-700 text-sm">Belediye Hizmetleri Uygulama Yönetmeliği</span>
                            </li>
                        </ul>
                    </div>
                </div>
                
                <div class="bg-[#e6a23c]/10 border-l-4 border-[#e6a23c] p-4 rounded-r mb-6">
                    <div class="flex items-center">
                        <span class="material-icons text-[#e6a23c] mr-2">tips_and_updates</span>
                        <p class="text-[#e6a23c]/90 font-medium">Yararlı İpucu</p>
                    </div>
                    <p class="text-sm text-[#e6a23c]/80 mt-1">Başvuru formunu online olarak doldurarak işlemlerinizi daha hızlı gerçekleştirebilirsiniz.</p>
                </div>
                
                <div class="bg-gray-50 rounded-lg p-5">
                    <h3 class="font-semibold text-lg text-gray-800 mb-4">İletişim Bilgileri</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="flex items-start">
                            <span class="material-icons text-[#00352b] mr-3 mt-1">phone</span>
                            <div>
                                <p class="font-medium text-gray-700 mb-1">Telefon</p>
                                <p class="text-gray-600 text-sm">444 1 234</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <span class="material-icons text-[#00352b] mr-3 mt-1">email</span>
                            <div>
                                <p class="font-medium text-gray-700 mb-1">E-posta</p>
                                <p class="text-gray-600 text-sm">hizmet@belediye.gov.tr</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <span class="material-icons text-[#00352b] mr-3 mt-1">location_on</span>
                            <div>
                                <p class="font-medium text-gray-700 mb-1">Adres</p>
                                <p class="text-gray-600 text-sm">Belediye Hizmet Binası, Merkez Mah. Belediye Cad. No:1</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <span class="material-icons text-[#00352b] mr-3 mt-1">schedule</span>
                            <div>
                                <p class="font-medium text-gray-700 mb-1">Çalışma Saatleri</p>
                                <p class="text-gray-600 text-sm">Pazartesi - Cuma: 08:30 - 17:00</p>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </section>
            @endif
            
            <!-- Standart Formlar -->
            @if(isset($service->features['is_standard_forms_visible']) && $service->features['is_standard_forms_visible'])
            <section id="standart-formlar" class="service-content-section">
                <h2>Standart Formlar</h2>
                @if(isset($service->features['standard_forms']) && !empty($service->features['standard_forms']))
                    {!! $service->features['standard_forms'] !!}
                    
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
                @else
                <p class="mb-5">{{ $service->title }} hizmetimiz için gerekli olan tüm formları aşağıda bulabilirsiniz:</p>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div class="bg-white border border-gray-200 rounded-lg overflow-hidden hover:shadow-md transition-all">
                        <div class="p-5 flex flex-col h-full">
                            <div class="flex items-center mb-3">
                                <span class="material-icons text-[#00352b] mr-2">description</span>
                                <h3 class="font-semibold text-gray-800">Başvuru Formu</h3>
                            </div>
                            <p class="text-sm text-gray-600 mb-4">Hizmet başvurusu için doldurulması gereken temel form. Eksiksiz doldurulmalıdır.</p>
                            <div class="mt-auto flex items-center">
                                <a href="/uploads/forms/basvuru-formu.pdf" class="inline-flex items-center text-[#00352b] hover:text-[#20846c] transition-colors">
                                    <span class="material-icons mr-1 text-sm">file_download</span>
                                    <span class="text-sm font-medium">İndir (PDF)</span>
                                </a>
                                <span class="mx-2 text-gray-300">|</span>
                                <a href="/online-formlar/basvuru" class="inline-flex items-center text-[#00352b] hover:text-[#20846c] transition-colors">
                                    <span class="material-icons mr-1 text-sm">edit</span>
                                    <span class="text-sm font-medium">Online Doldur</span>
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white border border-gray-200 rounded-lg overflow-hidden hover:shadow-md transition-all">
                        <div class="p-5 flex flex-col h-full">
                            <div class="flex items-center mb-3">
                                <span class="material-icons text-[#00352b] mr-2">description</span>
                                <h3 class="font-semibold text-gray-800">Taahhütname</h3>
                            </div>
                            <p class="text-sm text-gray-600 mb-4">Hizmet sürecinde uyulması gereken kuralları içeren taahhütname formu.</p>
                            <div class="mt-auto flex items-center">
                                <a href="/uploads/forms/taahhutname.pdf" class="inline-flex items-center text-[#00352b] hover:text-[#20846c] transition-colors">
                                    <span class="material-icons mr-1 text-sm">file_download</span>
                                    <span class="text-sm font-medium">İndir (PDF)</span>
                                </a>
                                <span class="mx-2 text-gray-300">|</span>
                                <a href="/online-formlar/taahhutname" class="inline-flex items-center text-[#00352b] hover:text-[#20846c] transition-colors">
                                    <span class="material-icons mr-1 text-sm">edit</span>
                                    <span class="text-sm font-medium">Online Doldur</span>
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white border border-gray-200 rounded-lg overflow-hidden hover:shadow-md transition-all">
                        <div class="p-5 flex flex-col h-full">
                            <div class="flex items-center mb-3">
                                <span class="material-icons text-[#00352b] mr-2">description</span>
                                <h3 class="font-semibold text-gray-800">Bilgilendirme Formu</h3>
                            </div>
                            <p class="text-sm text-gray-600 mb-4">Hizmet hakkında detaylı bilgileri içeren formudur.</p>
                            <div class="mt-auto flex items-center">
                                <a href="/uploads/forms/bilgilendirme-formu.pdf" class="inline-flex items-center text-[#00352b] hover:text-[#20846c] transition-colors">
                                    <span class="material-icons mr-1 text-sm">file_download</span>
                                    <span class="text-sm font-medium">İndir (PDF)</span>
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white border border-gray-200 rounded-lg overflow-hidden hover:shadow-md transition-all">
                        <div class="p-5 flex flex-col h-full">
                            <div class="flex items-center mb-3">
                                <span class="material-icons text-[#00352b] mr-2">description</span>
                                <h3 class="font-semibold text-gray-800">İtiraz Formu</h3>
                            </div>
                            <p class="text-sm text-gray-600 mb-4">Hizmet sürecinde itiraz etmek istediğiniz durumlar için doldurulması gereken formdur.</p>
                            <div class="mt-auto flex items-center">
                                <a href="/uploads/forms/itiraz-formu.pdf" class="inline-flex items-center text-[#00352b] hover:text-[#20846c] transition-colors">
                                    <span class="material-icons mr-1 text-sm">file_download</span>
                                    <span class="text-sm font-medium">İndir (PDF)</span>
                                </a>
                                <span class="mx-2 text-gray-300">|</span>
                                <a href="/online-formlar/itiraz" class="inline-flex items-center text-[#00352b] hover:text-[#20846c] transition-colors">
                                    <span class="material-icons mr-1 text-sm">edit</span>
                                    <span class="text-sm font-medium">Online Doldur</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="bg-[#00352b]/5 p-5 rounded-lg border-l-4 border-[#00352b]">
                    <div class="flex items-start">
                        <span class="material-icons text-[#00352b] mt-1 mr-3">help</span>
                        <div>
                            <h3 class="font-semibold text-[#00352b] mb-2">Formlara Erişimde Sorun mu Yaşıyorsunuz?</h3>
                            <p class="text-sm text-gray-700">Formların basılı nüshalarını belediye binasında Vatandaş İlişkileri Masası'ndan temin edebilirsiniz. Dilerseniz <a href="/iletisim" class="text-blue-600 hover:underline">bizimle iletişime geçin</a>, formları posta yoluyla tarafınıza ulaştıralım.</p>
                        </div>
                    </div>
                </div>
                @endif
            </section>
            @endif
            
            <!-- İlgili Haberler -->
            <section id="ilgili-haberler" class="service-content-section">
                <h2>İlgili Haberler</h2>
                <p class="mb-5">{{ $service->title }} hizmetimizle ilgili son haberler ve duyurular:</p>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-white border border-gray-200 rounded-lg overflow-hidden hover:shadow-md transition-shadow">
                        <img src="https://via.placeholder.com/600x300" alt="Haber Görseli" class="w-full h-48 object-cover">
                        <div class="p-4">
                            <div class="flex items-center text-xs text-gray-500 mb-2">
                                <span class="material-icons text-xs mr-1">calendar_today</span>
                                <span>15 Nisan 2023</span>
                            </div>
                            <h3 class="font-semibold text-[#00352b] mb-2">{{ $service->title }} Hizmetimizde Yeni Dönem</h3>
                            <p class="text-sm text-gray-600 mb-3">Belediyemiz {{ $service->title }} hizmetinde yeni bir dönem başlatıyor. Artık daha hızlı ve etkili hizmet sunumu için sistem yenilendi.</p>
                            <a href="#" class="text-[#00352b] text-sm font-medium hover:text-[#20846c] transition-colors">Devamını Oku</a>
                        </div>
                    </div>
                    
                    <div class="bg-white border border-gray-200 rounded-lg overflow-hidden hover:shadow-md transition-shadow">
                        <img src="https://via.placeholder.com/600x300" alt="Haber Görseli" class="w-full h-48 object-cover">
                        <div class="p-4">
                            <div class="flex items-center text-xs text-gray-500 mb-2">
                                <span class="material-icons text-xs mr-1">calendar_today</span>
                                <span>28 Mart 2023</span>
                            </div>
                            <h3 class="font-semibold text-[#00352b] mb-2">{{ $service->title }} Başvurularında Rekor Artış</h3>
                            <p class="text-sm text-gray-600 mb-3">Son aylarda {{ $service->title }} hizmetimize başvurular yüzde 40 oranında arttı. Vatandaşlarımızın yoğun ilgisi için teşekkür ederiz.</p>
                            <a href="#" class="text-[#00352b] text-sm font-medium hover:text-[#20846c] transition-colors">Devamını Oku</a>
                        </div>
                    </div>
                </div>
                
                <div class="text-center mt-8">
                    <a href="/haberler" class="inline-flex items-center px-4 py-2 bg-[#00352b]/10 text-[#00352b] rounded-md hover:bg-[#00352b]/20 transition-colors">
                        <span>Tüm Haberleri Görüntüle</span>
                        <span class="material-icons ml-1">arrow_forward</span>
                    </a>
                </div>
            </section>
        </div>
    </div>
</div>
@endsection

@section('after_scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
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
            
            // Mobil görünümde sidebar davranışı
            if (window.innerWidth <= 768) {
                if (currentScrollY > 200) {
                    // Aşağı kaydırıldıysa sidebar'ı göster
                    sidebarElement.classList.add('active');
                } else {
                    // Sayfanın üst kısmındaysa sidebar'ı gizle
                    sidebarElement.classList.remove('active');
                }
            }
        });
    });
</script>
@endsection 
