@extends('layouts.front')

@section('title', $category->name . ' | Kurumsal Kadro')
@section('meta_description', $category->description ? $category->description : $category->name . ' kategorisindeki yetkili üyelerimiz')

@section('content')
<!-- Hero Bölümü - Hizmet Sayfasına Benzer -->
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
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
            <div class="md:col-span-2">
                <h1 class="text-2xl md:text-3xl font-bold text-white mb-2">{{ $category->name }}</h1>
                @if($category->description)
                <p class="text-white/80 text-base mb-2">{{ $category->description }}</p>
                @else
                <p class="text-white/80 text-base mb-2">{{ $category->name }} kategorisinde görev yapan yetkililerimiz hakkında bilgi alabilirsiniz.</p>
                @endif
                
                <nav class="flex" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-3 text-white/70">
                        <li class="inline-flex items-center">
                            <a href="{{ route('front.home') }}" class="inline-flex items-center text-sm hover:text-white">
                                <span class="material-icons text-xs mr-1">home</span>
                                Anasayfa
                            </a>
                        </li>
                        <li aria-current="page">
                            <div class="flex items-center">
                                <span class="material-icons text-xs mx-1">chevron_right</span>
                                <span class="ml-1 text-sm font-medium">{{ $category->name }}</span>
                            </div>
                        </li>
                    </ol>
                </nav>
            </div>
            
            <div class="hidden md:flex justify-end">
                @if($category->image)
                <div class="relative overflow-hidden rounded-lg shadow-xl">
                    <img src="{{ $category->image_url }}" alt="{{ $category->name }}" 
                        class="w-36 h-36 object-cover border-4 border-white/10">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
                </div>
                @else
                <div class="bg-white/10 backdrop-blur-sm p-4 rounded-lg border border-white/20 shadow-lg">
                    <div class="text-white text-center">
                        <span class="material-icons text-3xl text-[#e6a23c] mb-1">groups</span>
                        <h3 class="text-lg font-semibold mb-1">{{ $category->name }}</h3>
                        <p class="text-sm text-white/80">Bu kategoride {{ $category->members()->active()->count() }} aktif üye bulunmaktadır.</p>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Üyeler Bölümü -->
<section class="py-12 bg-slate-100">
    <div class="container max-w-7xl mx-auto px-4">
        @if(count($members) > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-5">
            @foreach($members as $member)
                @if($member->use_custom_link && $member->custom_link)
                <a href="{{ $member->custom_link }}" 
                   @if(filter_var($member->custom_link, FILTER_VALIDATE_URL) && parse_url($member->custom_link, PHP_URL_HOST) !== request()->getHost()) target="_blank" rel="noopener noreferrer" @endif
                   class="member-card bg-white rounded-sm shadow-sm flex flex-col h-full transition hover:shadow-md">
                @elseif($member->show_detail)
                <a href="{{ route('corporate.member', ['categorySlug' => $category->slug, 'memberSlug' => $member->slug]) }}" 
                   class="member-card bg-white rounded-sm shadow-sm flex flex-col h-full transition hover:shadow-md">
                @else
                <div class="member-card bg-white rounded-sm shadow-sm flex flex-col h-full non-clickable-member">
                @endif
                    <!-- Profil Fotoğrafı -->
                    <div class="w-full">
                        <div class="aspect-square w-full overflow-hidden bg-gray-100 flex items-center justify-center">
                            @if($member->image)
                            <img src="{{ asset($member->image) }}" alt="{{ $member->name }}" class="w-full h-full object-cover">
                            @else
                            <span class="material-icons text-gray-300 text-7xl">person</span>
                            @endif
                        </div>
                    </div>
                    
                    <!-- İsim ve Unvan -->
                    <div class="text-center p-4 flex-grow flex flex-col">
                        <h3 class="text-gray-900 font-bold text-lg mb-1">{{ $member->name }}</h3>
                        @if($member->title)
                        <span class="text-gray-700 text-sm block">{{ $member->title }}</span>
                        @endif
                    </div>
                @if($member->use_custom_link && $member->custom_link)
                </a>
                @elseif($member->show_detail)
                </a>
                @else
                </div>
                @endif
            @endforeach
        </div>
        @else
        <div class="bg-white p-8 rounded-lg shadow-md text-center">
            <span class="material-icons text-gray-400 text-5xl mb-4">info</span>
            <h3 class="text-xl font-bold text-gray-800 mb-2">Bu kategoride henüz üye bulunmamaktadır</h3>
            <p class="text-gray-600">Lütfen daha sonra tekrar kontrol ediniz.</p>
        </div>
        @endif
    </div>
</section>
@endsection

@push('styles')
<style>
    .member-card {
        position: relative;
    }
    
    .non-clickable-member {
        cursor: default;
        opacity: 0.95;
    }
    
    .line-clamp-1 {
        display: -webkit-box;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    /* Responsive Ayarları */
    @media (max-width: 640px) {
        .grid {
            gap: 12px;
        }
    }
</style>
@endpush 