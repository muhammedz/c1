@extends('layouts.front')

@section('title', 'Projelerimiz')

@section('content')
<div class="container max-w-7xl mx-auto px-4 py-12">
    <div class="text-center mb-12">
        <h1 class="text-3xl font-bold text-gray-800 mb-4">Projelerimiz</h1>
        <p class="text-gray-600 max-w-2xl mx-auto">Belediyemizin gerçekleştirdiği ve devam eden tüm projeler hakkında ayrıntılı bilgi edinebilirsiniz.</p>
    </div>
    
    <!-- Kategori Filtreleme -->
    @if(isset($categories) && $categories->count() > 0)
        <div class="mb-10">
            <div class="flex flex-wrap items-center gap-3 justify-center">
                <a href="{{ route('front.projects') }}" class="px-5 py-2 rounded-full border {{ !isset($selectedCategory) ? 'bg-blue-600 text-white' : 'border-gray-300 hover:bg-gray-100' }}">
                    Tümü
                </a>
                @foreach($categories as $category)
                    <a href="{{ route('front.projects', ['category' => $category->id]) }}" 
                       class="px-5 py-2 rounded-full border {{ isset($selectedCategory) && $selectedCategory->id == $category->id ? 'bg-blue-600 text-white' : 'border-gray-300 hover:bg-gray-100' }}">
                        {{ $category->name }}
                        <span class="ml-2 text-sm">{{ $category->activeProjects->count() }}</span>
                    </a>
                @endforeach
            </div>
        </div>
    @endif
    
    <!-- Projeler Listesi -->
    @if(isset($projects) && $projects->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($projects as $project)
                <a href="{{ route('front.projects.detail', $project->slug) }}" class="block rounded-xl overflow-hidden shadow-lg group">
                    <div class="aspect-video relative">
                        <img src="{{ $project->cover_image_url }}" alt="{{ $project->title }}" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-black bg-opacity-40 group-hover:bg-opacity-60 transition"></div>
                        <div class="absolute bottom-0 left-0 right-0 p-6">
                            <h3 class="text-white text-xl font-bold">{{ $project->title }}</h3>
                            <p class="text-white/80 mt-2">{{ $project->status_text }}</p>
                        </div>
                    </div>
                    <div class="p-6 bg-white">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-500">
                                {{ $project->category ? $project->category->name : 'Genel' }}
                            </span>
                            <span class="text-blue-600 font-medium">Detaylar</span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
        
        <!-- Pagination -->
        @if($projects->hasPages())
            <div class="mt-10">
                {{ $projects->links() }}
            </div>
        @endif
    @else
        <div class="text-center py-20 border border-gray-200 rounded-lg">
            <h2 class="text-xl font-medium text-gray-700 mb-2">Proje bulunamadı</h2>
            <p class="text-gray-500">Seçili kategoride henüz proje bulunmuyor.</p>
        </div>
    @endif
</div>
@endsection 