@extends('layouts.front')

@section('title', 'Sayfa Bulunamadı')
@section('meta_description', 'Aradığınız sayfa bulunamadı. Ana sayfaya yönlendiriliyorsunuz.')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-200 flex items-center justify-center px-4">
    <div class="max-w-2xl mx-auto text-center">
        <!-- 404 Büyük Sayı -->
        <div class="relative mb-8">
            <h1 class="text-9xl md:text-[12rem] font-bold text-slate-300 select-none">404</h1>
            <div class="absolute inset-0 flex items-center justify-center">
                <div class="bg-white rounded-full p-8 shadow-2xl">
                    <i class="fas fa-search text-6xl text-slate-400"></i>
                </div>
            </div>
        </div>

        <!-- Başlık ve Açıklama -->
        <div class="mb-8 animate-fade-in">
            <h2 class="text-3xl md:text-4xl font-bold text-slate-700 mb-4">
                Sayfa Bulunamadı
            </h2>
            <p class="text-lg text-slate-600 mb-6 leading-relaxed">
                Aradığınız sayfa mevcut değil veya taşınmış olabilir.<br>
                <span class="font-semibold text-green-600" id="countdown">3</span> saniye içinde ana sayfaya yönlendirileceksiniz.
            </p>
        </div>

        <!-- Butonlar -->
        <div class="flex flex-col sm:flex-row gap-4 justify-center items-center mb-8">
            <a href="{{ route('front.home') }}" 
               class="inline-flex items-center px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105">
                <i class="fas fa-home mr-2"></i>
                Ana Sayfaya Dön
            </a>
            
            <button onclick="history.back()" 
                    class="inline-flex items-center px-6 py-3 bg-slate-600 hover:bg-slate-700 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105">
                <i class="fas fa-arrow-left mr-2"></i>
                Geri Dön
            </button>
        </div>

        <!-- Arama Kutusu -->
        <div class="max-w-md mx-auto mb-8">
            <form action="{{ route('search') }}" method="GET" class="relative">
                <input type="text" 
                       name="q" 
                       placeholder="Aradığınızı buradan bulabilirsiniz..." 
                       class="w-full px-4 py-3 pr-12 border border-slate-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition-all duration-300">
                <button type="submit" 
                        class="absolute right-2 top-1/2 transform -translate-y-1/2 bg-green-600 hover:bg-green-700 text-white p-2 rounded-md transition-colors duration-300">
                    <i class="fas fa-search"></i>
                </button>
            </form>
        </div>

        <!-- Hızlı Linkler -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 max-w-2xl mx-auto">
            <a href="{{ route('news.index') }}" 
               class="flex flex-col items-center p-4 bg-white rounded-lg shadow-md hover:shadow-lg transition-all duration-300 transform hover:scale-105 group">
                <i class="fas fa-newspaper text-2xl text-blue-600 mb-2 group-hover:text-blue-700"></i>
                <span class="text-sm font-medium text-slate-700 group-hover:text-slate-900">Haberler</span>
            </a>
            
            <a href="{{ route('services.index') }}" 
               class="flex flex-col items-center p-4 bg-white rounded-lg shadow-md hover:shadow-lg transition-all duration-300 transform hover:scale-105 group">
                <i class="fas fa-cogs text-2xl text-green-600 mb-2 group-hover:text-green-700"></i>
                <span class="text-sm font-medium text-slate-700 group-hover:text-slate-900">Hizmetler</span>
            </a>
            
            <a href="{{ route('front.projects') }}" 
               class="flex flex-col items-center p-4 bg-white rounded-lg shadow-md hover:shadow-lg transition-all duration-300 transform hover:scale-105 group">
                <i class="fas fa-project-diagram text-2xl text-purple-600 mb-2 group-hover:text-purple-700"></i>
                <span class="text-sm font-medium text-slate-700 group-hover:text-slate-900">Projeler</span>
            </a>
            
            <a href="{{ route('front.iletisim') }}" 
               class="flex flex-col items-center p-4 bg-white rounded-lg shadow-md hover:shadow-lg transition-all duration-300 transform hover:scale-105 group">
                <i class="fas fa-envelope text-2xl text-red-600 mb-2 group-hover:text-red-700"></i>
                <span class="text-sm font-medium text-slate-700 group-hover:text-slate-900">İletişim</span>
            </a>
        </div>

        <!-- Dekoratif Elementler -->
        <div class="absolute top-10 left-10 w-20 h-20 bg-green-200 rounded-full opacity-20 animate-pulse"></div>
        <div class="absolute bottom-10 right-10 w-16 h-16 bg-blue-200 rounded-full opacity-20 animate-pulse" style="animation-delay: 1s;"></div>
        <div class="absolute top-1/2 left-5 w-12 h-12 bg-purple-200 rounded-full opacity-20 animate-pulse" style="animation-delay: 2s;"></div>
    </div>
</div>
@endsection

@section('after_scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let countdown = 3;
    const countdownElement = document.getElementById('countdown');
    
    const timer = setInterval(function() {
        countdown--;
        countdownElement.textContent = countdown;
        
        if (countdown <= 0) {
            clearInterval(timer);
            window.location.href = '{{ route('front.home') }}';
        }
    }, 1000);
    
    // Sayfa görünür olduğunda animasyonları başlat
    const elements = document.querySelectorAll('.animate-fade-in');
    elements.forEach((el, index) => {
        setTimeout(() => {
            el.style.opacity = '0';
            el.style.transform = 'translateY(20px)';
            el.style.transition = 'all 0.6s ease';
            
            setTimeout(() => {
                el.style.opacity = '1';
                el.style.transform = 'translateY(0)';
            }, 100);
        }, index * 200);
    });
});
</script>
@endsection

@section('css')
<style>
@keyframes fadeIn {
    from { 
        opacity: 0; 
        transform: translateY(20px); 
    }
    to { 
        opacity: 1; 
        transform: translateY(0); 
    }
}

.animate-fade-in {
    animation: fadeIn 0.8s ease-out;
}

@keyframes pulse {
    0%, 100% {
        transform: scale(1);
        opacity: 0.2;
    }
    50% {
        transform: scale(1.1);
        opacity: 0.3;
    }
}

.animate-pulse {
    animation: pulse 3s ease-in-out infinite;
}

/* Hover efektleri */
.group:hover .fas {
    transform: scale(1.1);
    transition: transform 0.3s ease;
}

/* Responsive ayarlar */
@media (max-width: 640px) {
    .text-9xl {
        font-size: 6rem;
    }
}
</style>
@endsection 