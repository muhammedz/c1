@extends('layouts.front')

@section('title', 'Sunucu Hatası')
@section('meta_description', 'Sunucuda bir hata oluştu. Kısa süre içinde düzeltilecektir.')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-red-50 to-red-200 flex items-center justify-center px-4">
    <div class="max-w-2xl mx-auto text-center">
        <!-- 500 Büyük Sayı -->
        <div class="relative mb-8">
            <h1 class="text-9xl md:text-[12rem] font-bold text-red-300 select-none">500</h1>
            <div class="absolute inset-0 flex items-center justify-center">
                <div class="bg-white rounded-full p-8 shadow-2xl">
                    <i class="fas fa-exclamation-triangle text-6xl text-red-400"></i>
                </div>
            </div>
        </div>

        <!-- Başlık ve Açıklama -->
        <div class="mb-8 animate-fade-in">
            <h2 class="text-3xl md:text-4xl font-bold text-red-700 mb-4">
                Sunucu Hatası
            </h2>
            <p class="text-lg text-red-600 mb-6 leading-relaxed">
                Sunucuda beklenmeyen bir hata oluştu.<br>
                Teknik ekibimiz bu durumdan haberdar edildi ve en kısa sürede düzeltilecektir.<br>
                <span class="font-semibold text-green-600" id="countdown">5</span> saniye içinde ana sayfaya yönlendirileceksiniz.
            </p>
        </div>

        <!-- Butonlar -->
        <div class="flex flex-col sm:flex-row gap-4 justify-center items-center mb-8">
            <a href="{{ route('front.home') }}" 
               class="inline-flex items-center px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105">
                <i class="fas fa-home mr-2"></i>
                Ana Sayfaya Dön
            </a>
            
            <button onclick="location.reload()" 
                    class="inline-flex items-center px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105">
                <i class="fas fa-redo mr-2"></i>
                Sayfayı Yenile
            </button>
            
            <button onclick="history.back()" 
                    class="inline-flex items-center px-6 py-3 bg-slate-600 hover:bg-slate-700 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105">
                <i class="fas fa-arrow-left mr-2"></i>
                Geri Dön
            </button>
        </div>

        <!-- Bilgi Kutusu -->
        <div class="max-w-md mx-auto mb-8 bg-white rounded-lg shadow-lg p-6">
            <div class="flex items-center mb-4">
                <i class="fas fa-info-circle text-blue-500 text-xl mr-3"></i>
                <h3 class="font-semibold text-gray-800">Ne Yapabilirsiniz?</h3>
            </div>
            <ul class="text-left text-gray-600 space-y-2">
                <li class="flex items-center">
                    <i class="fas fa-check text-green-500 mr-2"></i>
                    Birkaç dakika bekleyip tekrar deneyin
                </li>
                <li class="flex items-center">
                    <i class="fas fa-check text-green-500 mr-2"></i>
                    Sayfayı yenileyin
                </li>
                <li class="flex items-center">
                    <i class="fas fa-check text-green-500 mr-2"></i>
                    Ana sayfadan tekrar başlayın
                </li>
            </ul>
        </div>

        <!-- İletişim Bilgisi -->
        <div class="mb-8">
            <p class="text-sm text-red-600 mb-2">
                Sorun devam ederse bizimle iletişime geçin:
            </p>
            <a href="{{ route('front.iletisim') }}" 
               class="inline-flex items-center text-red-700 hover:text-red-800 font-medium transition-colors duration-300">
                <i class="fas fa-envelope mr-2"></i>
                İletişim Sayfası
            </a>
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
        <div class="absolute top-10 left-10 w-20 h-20 bg-red-200 rounded-full opacity-20 animate-pulse"></div>
        <div class="absolute bottom-10 right-10 w-16 h-16 bg-orange-200 rounded-full opacity-20 animate-pulse" style="animation-delay: 1s;"></div>
        <div class="absolute top-1/2 left-5 w-12 h-12 bg-yellow-200 rounded-full opacity-20 animate-pulse" style="animation-delay: 2s;"></div>
        <div class="absolute top-1/4 right-1/4 w-8 h-8 bg-pink-200 rounded-full opacity-20 animate-pulse" style="animation-delay: 3s;"></div>
    </div>
</div>
@endsection

@section('after_scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let countdown = 5;
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

/* Gradient animasyonu */
@keyframes gradientShift {
    0% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
}

.bg-gradient-to-br {
    background-size: 200% 200%;
    animation: gradientShift 10s ease infinite;
}
</style>
@endsection 