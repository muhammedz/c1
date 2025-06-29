@extends('layouts.front')

@section('title', $member->name . ' | ' . $category->name)
@section('meta_description', $member->short_description ?: $member->name . ' - ' . $member->title)

@section('content')
<!-- Hero Bölümü - Kategori Sayfasına Benzer -->
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
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3 md:py-4 relative z-10">
        <div class="grid grid-cols-1 gap-4 items-center">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-white mb-2">{{ $member->name }}</h1>
                <h2 class="text-white/80 text-lg mb-3">{{ $member->title }}</h2>
                
                @if($member->short_description)
                <p class="text-white/80 text-base mb-3">{{ $member->short_description }}</p>
                @endif
                
                <nav class="flex" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-3 text-white/70">
                        <li class="inline-flex items-center">
                            <a href="{{ route('front.home') }}" class="inline-flex items-center text-sm hover:text-white">
                                <span class="material-icons text-xs mr-1">home</span>
                                Anasayfa
                            </a>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <span class="material-icons text-xs mx-1">chevron_right</span>
                                <a href="{{ route('corporate.category', ['categorySlug' => $member->category->slug]) }}" class="ml-1 text-sm hover:text-white">{{ $member->category->name }}</a>
                            </div>
                        </li>
                        <li aria-current="page">
                            <div class="flex items-center">
                                <span class="material-icons text-xs mx-1">chevron_right</span>
                                <span class="ml-1 text-sm font-medium">{{ $member->name }}</span>
                            </div>
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<!-- Biyografi İçerik Bölümü -->
<section class="py-12 bg-slate-100">
    <div class="container max-w-7xl mx-auto px-4">
        <div class="bg-white shadow-md rounded-xl overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center">
                <h2 class="text-2xl font-bold text-gray-800">Biyografi</h2>
                
                <!-- Sosyal Medya Linkleri -->
                <div class="flex space-x-3">
                    @if($member->facebook)
                    <a href="{{ $member->facebook }}" target="_blank" class="bg-slate-100 hover:bg-slate-200 text-gray-700 p-2 rounded-full transition-colors">
                        <i class="fab fa-facebook-f text-lg"></i>
                    </a>
                    @endif
                    
                    @if($member->twitter)
                    <a href="{{ $member->twitter }}" target="_blank" class="bg-slate-100 hover:bg-slate-200 text-gray-700 p-2 rounded-full transition-colors">
                        <span class="text-white font-bold" style="font-family: serif; font-size: 14px;">𝕏</span>
                    </a>
                    @endif
                    
                    @if($member->instagram)
                    <a href="{{ $member->instagram }}" target="_blank" class="bg-slate-100 hover:bg-slate-200 text-gray-700 p-2 rounded-full transition-colors">
                        <i class="fab fa-instagram text-lg"></i>
                    </a>
                    @endif
                    
                    @if($member->linkedin)
                    <a href="{{ $member->linkedin }}" target="_blank" class="bg-slate-100 hover:bg-slate-200 text-gray-700 p-2 rounded-full transition-colors">
                        <i class="fab fa-linkedin-in text-lg"></i>
                    </a>
                    @endif
                </div>
            </div>
            
            <div class="p-6">
                <div class="prose prose-lg max-w-none prose-headings:text-gray-800 prose-p:text-gray-700 prose-a:text-[#00352b] prose-a:no-underline hover:prose-a:text-[#20846c] hover:prose-a:underline prose-img:rounded-lg">
                    @if($member->description)
                        {!! $member->description !!}
                    @else
                        <p class="text-gray-600">Henüz biyografi bilgisi girilmemiştir.</p>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Geri Dön Butonu -->
        <div class="mt-8 text-center">
            <a href="{{ route('corporate.category', $category->slug) }}" 
               class="inline-flex items-center px-5 py-2.5 rounded-lg bg-white shadow hover:shadow-md border border-gray-200 text-gray-700 hover:bg-gray-50 transition-colors">
                <span class="material-icons mr-2">arrow_back</span>
                {{ $category->name }} Sayfasına Dön
            </a>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
    .biography-content {
        line-height: 1.8;
    }
    .biography-content img {
        max-width: 100%;
        height: auto;
    }
</style>
@endpush 