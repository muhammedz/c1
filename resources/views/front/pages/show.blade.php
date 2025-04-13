@extends('layouts.front')

@section('title', $page->meta_title ?? $page->title)

@section('meta_description', $page->meta_description ?? Str::limit(strip_tags($page->content), 160))

@if($page->meta_keywords)
    @section('meta_keywords', $page->meta_keywords)
@endif

@if($page->image)
    @section('meta_image', asset($page->image))
@endif

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Üst Bilgiler -->
        <div class="mb-8">
            <!-- Kategori ve Tarih Bilgileri -->
            <div class="flex flex-wrap items-center gap-3 text-sm text-gray-500 mb-4">
                <div class="flex items-center">
                    <i class="far fa-calendar-alt mr-1"></i>
                    <span>{{ $page->published_at ? $page->published_at->format('d.m.Y') : $page->created_at->format('d.m.Y') }}</span>
                </div>
                
                <span class="text-gray-300">|</span>
                
                <div class="flex items-center">
                    <i class="far fa-eye mr-1"></i>
                    <span>{{ $page->view_count ?? 0 }} görüntülenme</span>
                </div>
                
                @if($page->categories->count() > 0)
                    <span class="text-gray-300">|</span>
                    
                    <div class="flex items-center flex-wrap gap-2">
                        @foreach($page->categories as $category)
                            <a href="{{ route('pages.category', $category->slug) }}" class="text-primary hover:underline">
                                #{{ $category->name }}
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>
            
            <!-- Başlık -->
            <h1 class="text-3xl md:text-4xl font-bold text-gray-800 mb-6">{{ $page->title }}</h1>
            
            <!-- Özet -->
            @if($page->summary)
                <div class="text-lg text-gray-600 mb-6 font-medium border-l-4 border-primary pl-4 py-2 bg-gray-50">
                    {{ $page->summary }}
                </div>
            @endif
        </div>
        
        <!-- Resim -->
        @if($page->image)
            <div class="mb-8 rounded-xl overflow-hidden shadow-md">
                <img src="{{ asset($page->image) }}" alt="{{ $page->title }}" class="w-full h-auto">
            </div>
        @endif
        
        <!-- İçerik -->
        <article class="prose prose-lg max-w-none mb-10">
            {!! $page->content !!}
        </article>
        
        <!-- Galeri Bölümü -->
        @if(isset($page->gallery) && count($page->gallery) > 0)
            <div class="my-10">
                <h3 class="text-2xl font-bold text-gray-800 mb-6">Galeri</h3>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                    @foreach($page->gallery as $image)
                        <a href="{{ asset($image) }}" data-fancybox="gallery" class="gallery-item block rounded-lg overflow-hidden shadow-sm border border-gray-200 aspect-square bg-gray-100">
                            <img src="{{ asset($image) }}" alt="{{ $page->title }} - Görsel {{ $loop->iteration }}" class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
        
        <!-- Etiketler -->
        @if(isset($page->tags) && count($page->tags) > 0)
            <div class="border-t border-gray-200 pt-6 mt-10">
                <h3 class="text-lg font-bold text-gray-700 mb-4">Etiketler</h3>
                <div class="flex flex-wrap gap-2">
                    @foreach($page->tags as $tag)
                        <a href="{{ route('pages.tag', $tag->slug) }}" class="px-3 py-1 bg-gray-100 text-gray-700 text-sm rounded-full hover:bg-primary hover:text-white transition">
                            {{ $tag->name }}
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
        
        <!-- Paylaşım Butonları -->
        <div class="border-t border-gray-200 pt-6 mt-8">
            <h3 class="text-lg font-bold text-gray-700 mb-4">Bu Sayfayı Paylaş</h3>
            <div class="flex gap-2">
                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('pages.show', $page->slug)) }}" target="_blank" class="w-10 h-10 bg-blue-600 text-white flex items-center justify-center rounded-full hover:bg-blue-700 transition">
                    <i class="fab fa-facebook-f"></i>
                </a>
                <a href="https://twitter.com/intent/tweet?url={{ urlencode(route('pages.show', $page->slug)) }}&text={{ urlencode($page->title) }}" target="_blank" class="w-10 h-10 bg-blue-400 text-white flex items-center justify-center rounded-full hover:bg-blue-500 transition">
                    <i class="fab fa-twitter"></i>
                </a>
                <a href="https://wa.me/?text={{ urlencode($page->title . ' - ' . route('pages.show', $page->slug)) }}" target="_blank" class="w-10 h-10 bg-green-500 text-white flex items-center justify-center rounded-full hover:bg-green-600 transition">
                    <i class="fab fa-whatsapp"></i>
                </a>
                <a href="https://www.linkedin.com/shareArticle?mini=true&url={{ urlencode(route('pages.show', $page->slug)) }}&title={{ urlencode($page->title) }}" target="_blank" class="w-10 h-10 bg-blue-700 text-white flex items-center justify-center rounded-full hover:bg-blue-800 transition">
                    <i class="fab fa-linkedin-in"></i>
                </a>
            </div>
        </div>
        
        <!-- İlgili Sayfalar -->
        @if(isset($relatedPages) && $relatedPages->count() > 0)
            <div class="border-t border-gray-200 pt-8 mt-8">
                <h3 class="text-2xl font-bold text-gray-800 mb-6">İlgili Sayfalar</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach($relatedPages as $relatedPage)
                        <div class="flex gap-4 bg-white rounded-lg border border-gray-200 p-4 hover:shadow-md transition">
                            <div class="flex-shrink-0 w-20 h-20 bg-gray-100 overflow-hidden rounded">
                                @if($relatedPage->image)
                                    <img src="{{ asset($relatedPage->image) }}" alt="{{ $relatedPage->title }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-400">
                                        <i class="fas fa-file-alt text-xl"></i>
                                    </div>
                                @endif
                            </div>
                            
                            <div class="flex-1">
                                <h4 class="font-bold text-gray-800 mb-1 line-clamp-2">
                                    <a href="{{ route('pages.show', $relatedPage->slug) }}" class="hover:text-primary">
                                        {{ $relatedPage->title }}
                                    </a>
                                </h4>
                                
                                <div class="text-xs text-gray-500 mb-2">
                                    {{ $relatedPage->published_at ? $relatedPage->published_at->format('d.m.Y') : $relatedPage->created_at->format('d.m.Y') }}
                                </div>
                                
                                @if($relatedPage->summary)
                                    <p class="text-sm text-gray-600 line-clamp-2">
                                        {{ $relatedPage->summary }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@4.0/dist/fancybox.css" />
<style>
    .prose img {
        border-radius: 0.375rem;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@4.0/dist/fancybox.umd.js"></script>
<script>
    // Galeri için lightbox
    Fancybox.bind("[data-fancybox], .gallery-item", {
        // Options
    });
</script>
@endpush 