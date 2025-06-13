@extends('layouts.front')

@section('title', 'Arşivler')
@section('meta_description', 'Kurumumuza ait arşiv belgelerine buradan ulaşabilirsiniz.')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Sayfa Başlığı -->
    <div class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Arşivler</h1>
                    <p class="mt-2 text-gray-600">Kurumumuza ait arşiv belgelerine buradan ulaşabilirsiniz.</p>
                </div>
                <div class="hidden md:flex items-center space-x-2">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                        <i class="fas fa-archive mr-2"></i>
                        {{ $archives->total() }} Arşiv
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Arama Alanı -->
        <div class="bg-white rounded-lg shadow-sm border mb-8">
            <div class="p-4">
                <form method="GET" action="{{ route('archives.index') }}" class="flex flex-wrap items-end gap-3">
                    <!-- Anahtar Kelime -->
                    <div class="w-full md:w-auto flex-1 min-w-[180px]">
                        <label for="search" class="block text-xs font-medium text-gray-500 mb-1">Arama</label>
                        <div class="relative">
                            <input type="text" 
                                   class="w-full pl-8 pr-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-1 focus:ring-blue-500 focus:border-blue-500" 
                                   id="search" 
                                   name="search" 
                                   placeholder="Arşivlerde ara..." 
                                   value="{{ request('search') }}">
                            <div class="absolute inset-y-0 left-0 pl-2 flex items-center pointer-events-none">
                                <i class="fas fa-search text-gray-400"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Butonlar -->
                    <div class="flex space-x-2">
                        <button type="submit" 
                                class="inline-flex items-center px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                            <i class="fas fa-search mr-1"></i>
                            Ara
                        </button>
                        <a href="{{ route('archives.index') }}" 
                           class="inline-flex items-center px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-colors">
                            <i class="fas fa-times mr-1"></i>
                            Temizle
                        </a>
                    </div>
                </form>
            </div>
        </div>

        @if($categories->count() > 0 || $uncategorizedArchives->count() > 0)
            <!-- Kategorilere Göre Arşivler -->
            <div class="space-y-8">
                @foreach($categories as $category)
                    @if($category->archives->count() > 0)
                        <div class="bg-white rounded-lg shadow-sm border overflow-hidden">
                            <!-- Kategori Başlığı -->
                            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 px-6 py-4 border-b">
                                <div class="flex items-center">
                                    @if($category->icon)
                                        <i class="{{ $category->icon }} text-blue-600 mr-3 text-lg"></i>
                                    @endif
                                    <div>
                                        <h2 class="text-xl font-semibold text-gray-900">{{ $category->name }}</h2>
                                        @if($category->description)
                                            <p class="text-sm text-gray-600 mt-1">{{ $category->description }}</p>
                                        @endif
                                    </div>
                                    <div class="ml-auto">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ $category->archives->count() }} Arşiv
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Kategori Arşivleri -->
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Başlık
                                            </th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-32">
                                                Belgeler
                                            </th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-32">
                                                İşlemler
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($category->archives as $archive)
                                            <tr class="hover:bg-gray-50 transition-colors">
                                                <td class="px-6 py-4">
                                                    <div class="flex items-start">
                                                        @if($archive->is_featured)
                                                            <i class="fas fa-star text-yellow-500 mr-2 mt-1 flex-shrink-0"></i>
                                                        @endif
                                                        <div>
                                                            <div class="text-base font-medium text-gray-900">
                                                                <a href="{{ route('archives.show', $archive->slug) }}" 
                                                                   class="hover:text-blue-600 transition-colors">
                                                                    {{ $archive->title }}
                                                                </a>
                                                            </div>
                                                            @if($archive->excerpt)
                                                                <div class="text-sm text-gray-500 mt-1">
                                                                    {{ Str::limit($archive->excerpt, 100) }}
                                                                </div>
                                                            @endif
                                                            <div class="text-xs text-gray-400 mt-1">
                                                                <i class="fas fa-calendar mr-1"></i>
                                                                {{ $archive->formatted_date }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    @if($archive->documents->count() > 0)
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                            <i class="fas fa-file mr-1"></i>
                                                            {{ $archive->documents->count() }} Belge
                                                        </span>
                                                    @else
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                            <i class="fas fa-file mr-1"></i>
                                                            0 Belge
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                    <a href="{{ route('archives.show', $archive->slug) }}" 
                                                       class="inline-flex items-center px-3 py-1 border border-blue-300 text-blue-700 bg-blue-50 hover:bg-blue-100 rounded-md text-sm transition-colors">
                                                        <i class="fas fa-eye mr-1"></i>
                                                        Görüntüle
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif
                @endforeach

                <!-- Kategorisiz Arşivler -->
                @if($uncategorizedArchives->count() > 0)
                    <div class="bg-white rounded-lg shadow-sm border overflow-hidden">
                        <!-- Başlık -->
                        <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-b">
                            <div class="flex items-center">
                                <i class="fas fa-folder-open text-gray-600 mr-3 text-lg"></i>
                                <div>
                                    <h2 class="text-xl font-semibold text-gray-900">Diğer Arşivler</h2>
                                    <p class="text-sm text-gray-600 mt-1">Kategorize edilmemiş arşiv belgeleri</p>
                                </div>
                                <div class="ml-auto">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        {{ $uncategorizedArchives->count() }} Arşiv
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Kategorisiz Arşivler -->
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Başlık
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-32">
                                            Belgeler
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-32">
                                            İşlemler
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($uncategorizedArchives as $archive)
                                        <tr class="hover:bg-gray-50 transition-colors">
                                            <td class="px-6 py-4">
                                                <div class="flex items-start">
                                                    @if($archive->is_featured)
                                                        <i class="fas fa-star text-yellow-500 mr-2 mt-1 flex-shrink-0"></i>
                                                    @endif
                                                    <div>
                                                        <div class="text-base font-medium text-gray-900">
                                                            <a href="{{ route('archives.show', $archive->slug) }}" 
                                                               class="hover:text-blue-600 transition-colors">
                                                                {{ $archive->title }}
                                                            </a>
                                                        </div>
                                                        @if($archive->excerpt)
                                                            <div class="text-sm text-gray-500 mt-1">
                                                                {{ Str::limit($archive->excerpt, 100) }}
                                                            </div>
                                                        @endif
                                                        <div class="text-xs text-gray-400 mt-1">
                                                            <i class="fas fa-calendar mr-1"></i>
                                                            {{ $archive->formatted_date }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($archive->documents->count() > 0)
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                        <i class="fas fa-file mr-1"></i>
                                                        {{ $archive->documents->count() }} Belge
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                        <i class="fas fa-file mr-1"></i>
                                                        0 Belge
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <a href="{{ route('archives.show', $archive->slug) }}" 
                                                   class="inline-flex items-center px-3 py-1 border border-blue-300 text-blue-700 bg-blue-50 hover:bg-blue-100 rounded-md text-sm transition-colors">
                                                    <i class="fas fa-eye mr-1"></i>
                                                    Görüntüle
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
            </div>
        @else
            <!-- Boş Durum -->
            <div class="bg-white rounded-lg shadow-sm border">
                <div class="text-center py-12">
                    <div class="mx-auto h-24 w-24 text-gray-400 mb-4">
                        <i class="fas fa-inbox text-6xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Arşiv bulunamadı</h3>
                    <p class="text-gray-500 mb-6">
                        @if(request('search'))
                            "{{ request('search') }}" araması için sonuç bulunamadı.
                        @else
                            Arşiv belgeleri yayınlandığında burada görüntülenecektir.
                        @endif
                    </p>
                    @if(request('search'))
                        <a href="{{ route('archives.index') }}" 
                           class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                            <i class="fas fa-refresh mr-2"></i>
                            Tüm Arşivleri Görüntüle
                        </a>
                    @endif
                </div>
            </div>
        @endif
    </div>
</div>

@section('css')
<style>
    /* Özel scrollbar stilleri */
    .overflow-x-auto::-webkit-scrollbar {
        height: 6px;
    }
    
    .overflow-x-auto::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 3px;
    }
    
    .overflow-x-auto::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 3px;
    }
    
    .overflow-x-auto::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }
</style>
@endsection
@endsection 