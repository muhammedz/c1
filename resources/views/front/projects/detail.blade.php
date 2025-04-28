@extends('layouts.front')

@section('title', isset($project) ? $project->title : 'Proje Detayı')

@section('content')
<div class="container max-w-7xl mx-auto px-4 py-12">
    @if(isset($project))
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">{{ $project->title }}</h1>
            <div class="flex items-center text-gray-600 mb-6">
                <span class="mr-4">{{ $project->status_text }}</span>
                @if($project->category)
                    <span>Kategori: {{ $project->category->name }}</span>
                @endif
            </div>
            
            @if($project->cover_image_url)
                <div class="w-full aspect-video rounded-xl overflow-hidden mb-8">
                    <img src="{{ $project->cover_image_url }}" alt="{{ $project->title }}" class="w-full h-full object-cover">
                </div>
            @endif
            
            <div class="prose max-w-none mb-12">
                {!! $project->content !!}
            </div>
            
            @if($project->gallery && $project->gallery->count() > 0)
                <div class="mt-10">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6">Proje Galerisi</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($project->gallery as $image)
                            <div class="rounded-xl overflow-hidden aspect-video">
                                <img src="{{ $image->image_url }}" alt="{{ $project->title }} görseli" class="w-full h-full object-cover">
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
        
        @if(isset($relatedProjects) && $relatedProjects->count() > 0)
            <div class="mt-16">
                <h2 class="text-2xl font-bold text-gray-800 mb-6">Benzer Projeler</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($relatedProjects as $relatedProject)
                        <a href="{{ route('front.projects.detail', $relatedProject->slug) }}" class="block rounded-xl overflow-hidden group">
                            <div class="aspect-video relative">
                                <img src="{{ $relatedProject->cover_image_url }}" alt="{{ $relatedProject->title }}" class="w-full h-full object-cover">
                                <div class="absolute inset-0 bg-black bg-opacity-40 group-hover:bg-opacity-60 transition"></div>
                                <div class="absolute bottom-0 left-0 right-0 p-4">
                                    <h3 class="text-white text-lg font-semibold">{{ $relatedProject->title }}</h3>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    @else
        <div class="text-center py-20">
            <h1 class="text-3xl font-bold text-gray-800 mb-4">Proje bulunamadı</h1>
            <p class="text-gray-600 mb-8">Aradığınız proje bulunamadı veya kaldırılmış olabilir.</p>
            <a href="{{ route('front.projects') }}" class="inline-block bg-blue-600 text-white px-6 py-3 rounded-md font-medium">Tüm Projeleri Görüntüle</a>
        </div>
    @endif
</div>
@endsection 