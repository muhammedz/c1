@extends('layouts.front')

@section('title', $tender->title)
@section('meta_description', Str::limit($tender->summary, 160))

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        <nav class="flex mb-6" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('front.home') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                        </svg>
                        Anasayfa
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <a href="{{ route('tenders.index') }}" class="ml-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ml-2">İhaleler</a>
                    </div>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2 line-clamp-1">{{ $tender->title }}</span>
                    </div>
                </li>
            </ol>
        </nav>

        <!-- Ana İçerik Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            <!-- Sol Taraf - Ana İçerik -->
            <div class="lg:col-span-3">
                <!-- İhale Başlık ve Durum -->
                <div class="bg-white rounded-lg shadow-sm border p-6 mb-6">
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-4">{{ $tender->title }}</h1>
                    
                    <div class="flex flex-wrap items-center gap-3 mb-2">
                        @php
                            $statusClass = match($tender->status) {
                                'active' => 'bg-green-100 text-green-800',
                                'completed' => 'bg-gray-100 text-gray-800',
                                'cancelled' => 'bg-red-100 text-red-800',
                                default => 'bg-gray-100 text-gray-800'
                            };
                        @endphp
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $statusClass }}">
                            {{ $tender->status_text }}
                        </span>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                            {{ $tender->unit }}
                        </span>
                    </div>
                    
                    <div class="flex flex-wrap items-center gap-4 text-sm text-gray-500 mt-3">
                        @if($tender->kik_no)
                            <div class="flex items-center">
                                <i class="fas fa-hashtag mr-2"></i>
                                KİK No: {{ $tender->kik_no }}
                            </div>
                        @endif
                        
                        @if($tender->tender_datetime)
                            <div class="flex items-center">
                                <i class="far fa-calendar-alt mr-2"></i>
                                {{ $tender->tender_datetime->format('d.m.Y H:i') }}
                            </div>
                        @endif
                    </div>
                </div>
                
                <!-- İhale Özeti -->
                @if($tender->summary)
                    <div class="bg-white rounded-lg shadow-sm border mb-6">
                        <div class="border-b border-gray-200 px-6 py-4">
                            <h2 class="text-lg font-semibold text-gray-900">İhale Özeti</h2>
                        </div>
                        <div class="p-6">
                            <p class="text-gray-700">{{ $tender->summary }}</p>
                        </div>
                    </div>
                @endif
                
                <!-- İhale Detayları -->
                <div class="bg-white rounded-lg shadow-sm border mb-6">
                    <div class="border-b border-gray-200 px-6 py-4">
                        <h2 class="text-lg font-semibold text-gray-900">İhale Detayları</h2>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <dl class="divide-y divide-gray-200">
                                    <div class="py-3 grid grid-cols-3 gap-2">
                                        <dt class="text-sm font-medium text-gray-500">İhale Konusu:</dt>
                                        <dd class="text-sm text-gray-900 col-span-2">{{ $tender->title }}</dd>
                                    </div>
                                    <div class="py-3 grid grid-cols-3 gap-2">
                                        <dt class="text-sm font-medium text-gray-500">İhale Birimi:</dt>
                                        <dd class="text-sm text-gray-900 col-span-2">{{ $tender->unit }}</dd>
                                    </div>
                                    @if($tender->kik_no)
                                    <div class="py-3 grid grid-cols-3 gap-2">
                                        <dt class="text-sm font-medium text-gray-500">KİK Kayıt No:</dt>
                                        <dd class="text-sm text-gray-900 col-span-2">{{ $tender->kik_no }}</dd>
                                    </div>
                                    @endif
                                    @if($tender->tender_datetime)
                                    <div class="py-3 grid grid-cols-3 gap-2">
                                        <dt class="text-sm font-medium text-gray-500">İhale Tarihi/Saati:</dt>
                                        <dd class="text-sm text-gray-900 col-span-2">{{ $tender->tender_datetime->format('d.m.Y H:i') }}</dd>
                                    </div>
                                    @endif
                                    @if($tender->delivery_place)
                                    <div class="py-3 grid grid-cols-3 gap-2">
                                        <dt class="text-sm font-medium text-gray-500">Teslim Yeri:</dt>
                                        <dd class="text-sm text-gray-900 col-span-2">{{ $tender->delivery_place }}</dd>
                                    </div>
                                    @endif
                                    @if($tender->delivery_date)
                                    <div class="py-3 grid grid-cols-3 gap-2">
                                        <dt class="text-sm font-medium text-gray-500">Teslim Tarihi:</dt>
                                        <dd class="text-sm text-gray-900 col-span-2">{{ $tender->delivery_date }}</dd>
                                    </div>
                                    @endif
                                </dl>
                            </div>
                            <div>
                                <dl class="divide-y divide-gray-200">
                                    @if($tender->address)
                                    <div class="py-3 grid grid-cols-3 gap-2">
                                        <dt class="text-sm font-medium text-gray-500">İdare'nin Adresi:</dt>
                                        <dd class="text-sm text-gray-900 col-span-2">{{ $tender->address }}</dd>
                                    </div>
                                    @endif
                                    @if($tender->phone)
                                    <div class="py-3 grid grid-cols-3 gap-2">
                                        <dt class="text-sm font-medium text-gray-500">İdare'nin Telefonu:</dt>
                                        <dd class="text-sm text-gray-900 col-span-2">{{ $tender->phone }}</dd>
                                    </div>
                                    @endif
                                    @if($tender->fax)
                                    <div class="py-3 grid grid-cols-3 gap-2">
                                        <dt class="text-sm font-medium text-gray-500">İdare'nin Faksı:</dt>
                                        <dd class="text-sm text-gray-900 col-span-2">{{ $tender->fax }}</dd>
                                    </div>
                                    @endif
                                    @if($tender->email)
                                    <div class="py-3 grid grid-cols-3 gap-2">
                                        <dt class="text-sm font-medium text-gray-500">İdare'nin E-Postası:</dt>
                                        <dd class="text-sm text-gray-900 col-span-2">{{ $tender->email }}</dd>
                                    </div>
                                    @endif
                                    @if($tender->document_url)
                                    <div class="py-3 grid grid-cols-3 gap-2">
                                        <dt class="text-sm font-medium text-gray-500">Döküman URL:</dt>
                                        <dd class="text-sm text-gray-900 col-span-2">
                                            <a href="{{ $tender->document_url }}" target="_blank" class="text-blue-600 hover:underline">
                                                {{ $tender->document_url }}
                                            </a>
                                        </dd>
                                    </div>
                                    @endif
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- İhale Açıklaması -->
                @if($tender->description)
                    <div class="bg-white rounded-lg shadow-sm border mb-6">
                        <div class="border-b border-gray-200 px-6 py-4">
                            <h2 class="text-lg font-semibold text-gray-900">İhale Konusu, Hizmetin Niteliği, Türü ve Miktarı</h2>
                        </div>
                        <div class="p-6">
                            <p class="text-gray-700">{{ $tender->description }}</p>
                        </div>
                    </div>
                @endif
                
                <!-- İhale Adresi -->
                @if($tender->tender_address)
                    <div class="bg-white rounded-lg shadow-sm border mb-6">
                        <div class="border-b border-gray-200 px-6 py-4">
                            <h2 class="text-lg font-semibold text-gray-900">İhale'nin Yapılacağı Adres</h2>
                        </div>
                        <div class="p-6">
                            <p class="text-gray-700">{{ $tender->tender_address }}</p>
                        </div>
                    </div>
                @endif
                
                <!-- İhale İçeriği -->
                @if($tender->content)
                    <div class="bg-white rounded-lg shadow-sm border mb-6">
                        <div class="border-b border-gray-200 px-6 py-4">
                            <h2 class="text-lg font-semibold text-gray-900">İhale Metni</h2>
                        </div>
                        <div class="p-6 prose max-w-none">
                            {!! $tender->content !!}
                        </div>
                    </div>
                @endif
            </div>
            
            <!-- Sağ Taraf - Yan Sütun -->
            <div class="lg:col-span-1">
                <!-- İhale Tarihi Kartı -->
                <div class="bg-white rounded-lg shadow-sm border mb-6">
                    <div class="border-b border-gray-200 px-4 py-3">
                        <h3 class="text-lg font-semibold text-gray-900">İhale Tarihi</h3>
                    </div>
                    <div class="p-4">
                        @if($tender->tender_datetime)
                            <div class="text-center py-4">
                                <div class="text-4xl font-bold text-blue-600">{{ $tender->tender_datetime->format('d') }}</div>
                                <div class="text-lg font-medium text-gray-900">{{ $tender->tender_datetime->translatedFormat('F Y') }}</div>
                                <div class="text-sm text-gray-500 mt-1">{{ $tender->tender_datetime->format('H:i') }}</div>
                                
                                @php
                                    $daysLeft = $tender->tender_datetime->diffInDays(now());
                                    $isPast = $tender->tender_datetime->isPast();
                                @endphp
                                
                                @if($tender->status == 'active')
                                    @if(!$isPast)
                                        <div class="mt-3 inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                            <i class="far fa-clock mr-1"></i>
                                            {{ $daysLeft }} gün kaldı
                                        </div>
                                    @else
                                        <div class="mt-3 inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                            <i class="fas fa-exclamation-circle mr-1"></i>
                                            Bugün son gün
                                        </div>
                                    @endif
                                @endif
                            </div>
                        @else
                            <div class="text-center py-4 text-gray-500">
                                <i class="far fa-calendar-times text-3xl mb-2"></i>
                                <p>İhale tarihi belirtilmemiş.</p>
                            </div>
                        @endif
                    </div>
                </div>
                
                <!-- İhale Durumları -->
                <div class="bg-white rounded-lg shadow-sm border mb-6">
                    <div class="border-b border-gray-200 px-4 py-3">
                        <h3 class="text-lg font-semibold text-gray-900">İhale Durumları</h3>
                    </div>
                    <div class="p-4">
                        <nav class="flex flex-col">
                            <a href="{{ route('tenders.index') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-50 rounded-md transition-colors">
                                <i class="fas fa-play-circle text-green-600 mr-3"></i>
                                <span>Aktif İhaleler</span>
                            </a>
                            <a href="{{ route('tenders.completed') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-50 rounded-md transition-colors">
                                <i class="fas fa-check-circle text-gray-600 mr-3"></i>
                                <span>Tamamlanan İhaleler</span>
                            </a>
                            <a href="{{ route('tenders.cancelled') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-50 rounded-md transition-colors">
                                <i class="fas fa-times-circle text-red-600 mr-3"></i>
                                <span>İptal Edilen İhaleler</span>
                            </a>
                        </nav>
                    </div>
                </div>
                
                <!-- İhale Dökümanları -->
                @if($tender->document_url)
                    <div class="bg-white rounded-lg shadow-sm border mb-6">
                        <div class="border-b border-gray-200 px-4 py-3">
                            <h3 class="text-lg font-semibold text-gray-900">İhale Dökümanları</h3>
                        </div>
                        <div class="p-4">
                            <a href="{{ $tender->document_url }}" target="_blank" 
                               class="flex items-center justify-center px-4 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                                <i class="fas fa-download mr-2"></i>
                                İhale Dökümanlarını İndir
                            </a>
                        </div>
                    </div>
                @endif
                
                <!-- İletişim Kartı -->
                <div class="bg-white rounded-lg shadow-sm border">
                    <div class="border-b border-gray-200 px-4 py-3">
                        <h3 class="text-lg font-semibold text-gray-900">İletişim</h3>
                    </div>
                    <div class="p-4">
                        <div class="flex flex-col space-y-3 text-sm">
                            @if($tender->phone)
                                <div class="flex">
                                    <i class="fas fa-phone text-blue-600 w-5 mt-1"></i>
                                    <div class="ml-3">
                                        <p class="text-gray-500">Telefon</p>
                                        <p class="text-gray-900 font-medium">{{ $tender->phone }}</p>
                                    </div>
                                </div>
                            @endif
                            
                            @if($tender->email)
                                <div class="flex">
                                    <i class="fas fa-envelope text-blue-600 w-5 mt-1"></i>
                                    <div class="ml-3">
                                        <p class="text-gray-500">E-posta</p>
                                        <p class="text-gray-900 font-medium">
                                            <a href="mailto:{{ $tender->email }}" class="hover:text-blue-600">{{ $tender->email }}</a>
                                        </p>
                                    </div>
                                </div>
                            @endif
                            
                            @if($tender->address)
                                <div class="flex">
                                    <i class="fas fa-map-marker-alt text-blue-600 w-5 mt-1"></i>
                                    <div class="ml-3">
                                        <p class="text-gray-500">Adres</p>
                                        <p class="text-gray-900">{{ $tender->address }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('css')
<style>
    /* TailwindCSS prose eklentisi yerine basit içerik stilleri */
    .prose {
        color: #374151;
        max-width: 65ch;
    }
    .prose p {
        margin-top: 1.25em;
        margin-bottom: 1.25em;
    }
    .prose a {
        color: #2563eb;
        text-decoration: underline;
        font-weight: 500;
    }
    .prose strong {
        font-weight: 600;
        color: #111827;
    }
    .prose ol,
    .prose ul {
        margin-top: 1.25em;
        margin-bottom: 1.25em;
        padding-left: 1.625em;
    }
    .prose ol {
        list-style-type: decimal;
    }
    .prose ul {
        list-style-type: disc;
    }
    .prose h1, 
    .prose h2,
    .prose h3,
    .prose h4 {
        color: #111827;
        font-weight: 600;
        margin-top: 2em;
        margin-bottom: 1em;
        line-height: 1.1;
    }
    .prose h1 {
        font-size: 2.25em;
    }
    .prose h2 {
        font-size: 1.5em;
    }
    .prose h3 {
        font-size: 1.25em;
    }
    .prose h4 {
        font-size: 1em;
    }
</style>
@endsection 