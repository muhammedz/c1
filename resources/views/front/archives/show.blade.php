@extends('layouts.front')

@section('title', $archive->title)
@section('meta_description', Str::limit($archive->excerpt, 160))

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
                    <i class="fas fa-archive text-xs mr-1"></i>
                    <span>Arşiv Belgeleri</span>
                </div>
                <h1 class="text-3xl md:text-4xl font-bold text-white mb-4">
                    @if($archive->is_featured)
                        <i class="fas fa-star text-yellow-400 mr-2"></i>
                    @endif
                    {{ $archive->title }}
                </h1>
                @if($archive->excerpt)
                    <p class="text-white/80 text-lg mb-5">{{ $archive->excerpt }}</p>
                @else
                    <p class="text-white/80 text-lg mb-5">Bu arşiv belgesi ile ilgili tüm detayları ve indirilebilir dosyaları aşağıda bulabilirsiniz.</p>
                @endif
                
                <div class="flex flex-wrap gap-4">
                    @if($archive->documents->count() > 0)
                        <a href="#belgeler" class="inline-flex items-center px-5 py-2.5 bg-[#e6a23c] text-white rounded-md hover:bg-[#e6a23c]/90 transition-colors font-medium shadow-lg shadow-[#e6a23c]/20">
                            <i class="fas fa-download mr-2 text-sm"></i>
                            Belgeleri İndir
                        </a>
                    @endif
                    <a href="{{ route('archives.index') }}" class="inline-flex items-center px-5 py-2.5 bg-white/10 text-white border border-white/20 rounded-md hover:bg-white/20 transition-colors shadow-lg shadow-black/5">
                        <i class="fas fa-arrow-left mr-2 text-sm"></i>
                        Tüm Arşivler
                    </a>
                </div>
            </div>
            <div class="hidden md:flex justify-end">
                <div class="bg-white/10 backdrop-blur-sm p-6 rounded-lg border border-white/20 shadow-lg">
                    <div class="text-white text-center">
                        <i class="fas fa-phone text-4xl text-[#e6a23c] mb-2"></i>
                        <h3 class="text-xl font-semibold mb-2">İletişim</h3>
                        <p class="text-sm text-white/80 mb-4">Arşiv belgeleri hakkında bilgi almak için bizimle iletişime geçebilirsiniz.</p>
                        <div class="mt-4 pt-4 border-t border-white/10">
                            <div class="flex items-center justify-center text-[#e6a23c]">
                                <i class="fas fa-phone mr-2"></i>
                                <span class="font-bold">444 1 234</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Arşiv Özeti -->
        @if($archive->excerpt)
            <div class="bg-white rounded-lg shadow-sm border mb-6">
                <div class="border-b border-gray-200 px-6 py-4">
                    <h2 class="text-lg font-semibold text-gray-900">Özet</h2>
                </div>
                <div class="p-6">
                    <p class="text-gray-700">{{ $archive->excerpt }}</p>
                </div>
            </div>
        @endif
        
        <!-- Arşiv İçeriği -->
        @if($archive->content)
            <div class="bg-white rounded-lg shadow-sm border mb-6">
                <div class="p-6">
                    <div class="prose max-w-none text-gray-700">
                        {!! $archive->content !!}
                    </div>
                </div>
            </div>
        @endif

        <!-- Belgeler Tablosu -->
        @if($archive->documents->count() > 0)
            <div id="belgeler" class="bg-white rounded-lg shadow-sm border mb-6">
                <div class="border-b border-gray-200 px-6 py-4">
                    <h2 class="text-lg font-semibold text-gray-900">
                        <i class="fas fa-file-alt mr-2"></i>
                        Belgeler ({{ $archive->documents->count() }})
                    </h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-2/5">
                                    Belge Adı
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/4">
                                    Açıklama
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/5">
                                    Dosya Bilgisi
                                </th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider w-1/5">
                                    İşlemler
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($archive->documents as $document)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <i class="{{ $document->icon_class }} text-gray-400 mr-3"></i>
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $document->name }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900">
                                            {{ $document->description ?: '-' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900">
                                            {{ $document->file_name }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            {{ $document->formatted_size }} • {{ $document->file_type }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex items-center justify-end space-x-2">
                                            <a href="{{ $document->download_url }}" 
                                               class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors"
                                               download>
                                                <i class="fas fa-download mr-2"></i>
                                                İndir
                                            </a>
                                            <a href="{{ $document->download_url }}" 
                                               class="inline-flex items-center px-3 py-2 border border-gray-300 text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors"
                                               target="_blank">
                                                <i class="fas fa-eye mr-2"></i>
                                                Görüntüle
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        <!-- Geri Dön -->
        <div class="bg-white rounded-lg shadow-sm border">
            <div class="p-6">
                <a href="{{ route('archives.index') }}" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Arşivlere Geri Dön
                </a>
            </div>
        </div>
    </div>
</div>
@endsection 