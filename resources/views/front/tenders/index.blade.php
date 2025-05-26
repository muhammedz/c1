@extends('layouts.front')

@section('title', 'İhaleler')
@section('meta_description', 'Güncel ihaleler listesi')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Sayfa Başlığı -->
    <div class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">İhaleler</h1>
                    <p class="mt-2 text-gray-600">Güncel ihale duyuruları ve başvuru bilgileri</p>
                </div>
                <div class="hidden md:flex items-center space-x-2">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                        <i class="fas fa-gavel mr-2"></i>
                        {{ $tenders->total() }} İhale
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Filtreleme Alanı -->
        <div class="bg-white rounded-lg shadow-sm border mb-8">
            <div class="p-4">
                <form method="GET" action="{{ route('tenders.index') }}" id="filterForm" class="flex flex-wrap items-end gap-3">
                    <!-- Anahtar Kelime -->
                    <div class="w-full md:w-auto flex-1 min-w-[180px]">
                        <label for="search" class="block text-xs font-medium text-gray-500 mb-1">Arama</label>
                        <div class="relative">
                            <input type="text" 
                                   class="w-full pl-8 pr-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-1 focus:ring-blue-500 focus:border-blue-500" 
                                   id="search" 
                                   name="search" 
                                   placeholder="İhale ara..." 
                                   value="{{ request('search') }}">
                            <div class="absolute inset-y-0 left-0 pl-2 flex items-center pointer-events-none">
                                <i class="fas fa-search text-gray-400"></i>
                            </div>
                        </div>
                    </div>

                    <!-- İhale Birimi -->
                    <div class="w-full md:w-auto">
                        <label for="unit" class="block text-xs font-medium text-gray-500 mb-1">Birim</label>
                        <select class="w-full md:w-[140px] px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-1 focus:ring-blue-500 focus:border-blue-500" 
                                id="unit" 
                                name="unit">
                            <option value="">Tümü</option>
                            @foreach($units as $unit)
                                <option value="{{ $unit }}" {{ request('unit') == $unit ? 'selected' : '' }}>
                                    {{ $unit }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Durum -->
                    <div class="w-full md:w-auto">
                        <label for="status" class="block text-xs font-medium text-gray-500 mb-1">Durum</label>
                        <select class="w-full md:w-[140px] px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-1 focus:ring-blue-500 focus:border-blue-500" 
                                id="status" 
                                name="status">
                            <option value="">Tümü</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Tamamlandı</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>İptal</option>
                        </select>
                    </div>

                    <!-- İhale Türü -->
                    <div class="w-full md:w-auto">
                        <label for="tender_type" class="block text-xs font-medium text-gray-500 mb-1">Tür</label>
                        <select class="w-full md:w-[140px] px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-1 focus:ring-blue-500 focus:border-blue-500" 
                                id="tender_type" 
                                name="tender_type">
                            <option value="">Tümü</option>
                            <option value="Yapım İşi" {{ request('tender_type') == 'Yapım İşi' ? 'selected' : '' }}>Yapım İşi</option>
                            <option value="Mal Alımı" {{ request('tender_type') == 'Mal Alımı' ? 'selected' : '' }}>Mal Alımı</option>
                            <option value="Hizmet Alımı" {{ request('tender_type') == 'Hizmet Alımı' ? 'selected' : '' }}>Hizmet Alımı</option>
                            <option value="Taşınmaz Mal" {{ request('tender_type') == 'Taşınmaz Mal' ? 'selected' : '' }}>Taşınmaz</option>
                        </select>
                    </div>

                    <!-- Tarih Aralığı -->
                    <div class="w-full md:w-auto flex space-x-2">
                        <div>
                            <label for="start_date" class="block text-xs font-medium text-gray-500 mb-1">Başlangıç</label>
                            <input type="date" 
                                   class="w-full md:w-[130px] px-2 py-2 text-sm border border-gray-300 rounded-lg focus:ring-1 focus:ring-blue-500 focus:border-blue-500" 
                                   id="start_date" 
                                   name="start_date" 
                                   value="{{ request('start_date') }}">
                        </div>
                        <div>
                            <label for="end_date" class="block text-xs font-medium text-gray-500 mb-1">Bitiş</label>
                            <input type="date" 
                                   class="w-full md:w-[130px] px-2 py-2 text-sm border border-gray-300 rounded-lg focus:ring-1 focus:ring-blue-500 focus:border-blue-500" 
                                   id="end_date" 
                                   name="end_date" 
                                   value="{{ request('end_date') }}">
                        </div>
                    </div>

                    <!-- Butonlar -->
                    <div class="flex space-x-2 md:ml-auto">
                        <button type="submit" 
                                class="inline-flex items-center px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg">
                            <i class="fas fa-search mr-1"></i>
                            Ara
                        </button>
                        <a href="{{ route('tenders.index') }}" 
                           class="inline-flex items-center px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg">
                            <i class="fas fa-times mr-1"></i>
                            Temizle
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- İhale Durumu Sekmeleri -->
        <div class="bg-white rounded-lg shadow-sm border mb-8">
            <div class="border-b border-gray-200">
                <nav class="flex space-x-8 px-6" aria-label="Tabs">
                    <a href="{{ route('tenders.index', array_merge(request()->all(), ['status' => 'active'])) }}" 
                       class="py-4 px-1 border-b-2 font-medium text-sm transition-colors {{ !request('status') || request('status') == 'active' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                        <i class="fas fa-play-circle mr-2"></i>
                        Aktif İhaleler
                    </a>
                    <a href="{{ route('tenders.index', array_merge(request()->all(), ['status' => 'completed'])) }}" 
                       class="py-4 px-1 border-b-2 font-medium text-sm transition-colors {{ request('status') == 'completed' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                        <i class="fas fa-check-circle mr-2"></i>
                        Tamamlanan İhaleler
                    </a>
                    <a href="{{ route('tenders.index', array_merge(request()->all(), ['status' => 'cancelled'])) }}" 
                       class="py-4 px-1 border-b-2 font-medium text-sm transition-colors {{ request('status') == 'cancelled' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                        <i class="fas fa-times-circle mr-2"></i>
                        İptal Edilen İhaleler
                    </a>
                </nav>
            </div>
        </div>

        @if($tenders->count() > 0)
            <!-- İhale Tablosu -->
            <div class="bg-white rounded-lg shadow-sm border overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-32">
                                    Tarih
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-48">
                                    Birim
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-32">
                                    Tür
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Konu
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-24">
                                    Durum
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-32">
                                    İşlemler
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($tenders as $tender)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($tender->tender_datetime)
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $tender->tender_datetime->format('d.m.Y') }}
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                {{ $tender->tender_datetime->format('H:i') }}
                                            </div>
                                        @else
                                            <span class="text-sm text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $tender->unit }}
                                        </div>
                                        @if($tender->kik_no)
                                            <div class="text-sm text-gray-500">
                                                KİK: {{ $tender->kik_no }}
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $type = 'Diğer';
                                            $badgeClass = 'bg-gray-100 text-gray-800';
                                            
                                            if(str_contains(strtolower($tender->title), 'yapım')) {
                                                $type = 'Yapım İşi';
                                                $badgeClass = 'bg-blue-100 text-blue-800';
                                            } elseif(str_contains(strtolower($tender->title), 'mal')) {
                                                $type = 'Mal Alımı';
                                                $badgeClass = 'bg-green-100 text-green-800';
                                            } elseif(str_contains(strtolower($tender->title), 'hizmet')) {
                                                $type = 'Hizmet Alımı';
                                                $badgeClass = 'bg-purple-100 text-purple-800';
                                            } elseif(str_contains(strtolower($tender->title), 'taşınmaz')) {
                                                $type = 'Taşınmaz Mal';
                                                $badgeClass = 'bg-yellow-100 text-yellow-800';
                                            }
                                        @endphp
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $badgeClass }}">
                                            {{ $type }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-gray-900">
                                            <a href="{{ route('tenders.show', $tender->slug) }}" 
                                               class="hover:text-blue-600 transition-colors">
                                                {{ Str::limit($tender->title, 80) }}
                                            </a>
                                        </div>
                                        @if($tender->summary)
                                            <div class="text-sm text-gray-500 mt-1">
                                                {{ Str::limit($tender->summary, 100) }}
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $statusClass = match($tender->status) {
                                                'active' => 'bg-green-100 text-green-800',
                                                'completed' => 'bg-gray-100 text-gray-800',
                                                'cancelled' => 'bg-red-100 text-red-800',
                                                default => 'bg-gray-100 text-gray-800'
                                            };
                                            
                                            $statusText = match($tender->status) {
                                                'active' => 'Aktif',
                                                'completed' => 'Tamamlandı',
                                                'cancelled' => 'İptal Edildi',
                                                default => 'Bilinmiyor'
                                            };
                                        @endphp
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusClass }}">
                                            {{ $statusText }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-2">
                                            <a href="{{ route('tenders.show', $tender->slug) }}" 
                                               class="inline-flex items-center px-3 py-1 border border-blue-300 text-blue-700 bg-blue-50 hover:bg-blue-100 rounded-md text-sm transition-colors">
                                                <i class="fas fa-eye mr-1"></i>
                                                Görüntüle
                                            </a>
                                            <button type="button" 
                                                    class="inline-flex items-center px-3 py-1 border border-yellow-300 text-yellow-700 bg-yellow-50 hover:bg-yellow-100 rounded-md text-sm transition-colors">
                                                <i class="fas fa-info-circle mr-1"></i>
                                                Detay
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Sayfalama -->
            @if($tenders->hasPages())
                <div class="mt-8 flex justify-center">
                    <div class="bg-white rounded-lg shadow-sm border px-4 py-3">
                        {{ $tenders->appends(request()->query())->links() }}
                    </div>
                </div>
            @endif
        @else
            <!-- Boş Durum -->
            <div class="bg-white rounded-lg shadow-sm border">
                <div class="text-center py-12">
                    <div class="mx-auto h-24 w-24 text-gray-400 mb-4">
                        <i class="fas fa-inbox text-6xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">İhale bulunamadı</h3>
                    <p class="text-gray-500 mb-6">Aradığınız kriterlere uygun ihale bulunmamaktadır.</p>
                    <a href="{{ route('tenders.index') }}" 
                       class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                        <i class="fas fa-refresh mr-2"></i>
                        Tüm İhaleleri Görüntüle
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

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

@section('after_scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Form otomatik gönderimi için
    const filterForm = document.getElementById('filterForm');
    const selectElements = filterForm.querySelectorAll('select');
    
    selectElements.forEach(select => {
        select.addEventListener('change', function() {
            // Sayfa numarasını sıfırla
            const pageInput = filterForm.querySelector('input[name="page"]');
            if (pageInput) {
                pageInput.remove();
            }
            
            // Formu gönder
            filterForm.submit();
        });
    });
    
    // Arama inputu için enter tuşu
    const searchInput = document.getElementById('search');
    searchInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            filterForm.submit();
        }
    });
    
    // Responsive tablo için yatay kaydırma uyarısı
    const tableContainer = document.querySelector('.overflow-x-auto');
    if (tableContainer && window.innerWidth < 768) {
        tableContainer.addEventListener('scroll', function() {
            if (this.scrollLeft > 0) {
                this.classList.add('shadow-inner');
            } else {
                this.classList.remove('shadow-inner');
            }
        });
    }
});
</script>
@endsection 