{{--
    Basit Sayfa Geri Bildirim Component'i
    
    Kullanım:
    @include('components.page-feedback', [
        'pageUrl' => request()->url(),
        'pageTitle' => $service->title ?? 'Sayfa'
    ])
--}}

<div class="page-feedback-widget bg-white border rounded-lg p-8 mt-8 mx-auto shadow-sm">
    <!-- Soru -->
    <div id="feedback-question" class="flex items-center justify-center space-x-4">
        <span class="text-lg font-medium text-gray-800">Bu sayfa size yardımcı oldu mu?</span>
        
        <button 
            type="button" 
            class="feedback-btn px-4 py-2 border border-gray-300 rounded-md text-blue-600 hover:bg-gray-50 transition"
            data-helpful="true"
        >
            Evet
        </button>
        
        <button 
            type="button" 
            class="feedback-btn px-4 py-2 border border-gray-300 rounded-md text-blue-600 hover:bg-gray-50 transition"
            data-helpful="false"
        >
            Hayır
        </button>
    </div>

    <!-- Yükleniyor -->
    <div id="feedback-loading" class="hidden text-center py-4">
        <span class="text-gray-600 text-lg">Gönderiliyor...</span>
    </div>

    <!-- Başarı -->
    <div id="feedback-success" class="hidden text-center py-4">
        <span class="text-green-600 font-medium text-lg">Geri bildiriminiz için teşekkürler!</span>
    </div>

    <!-- Hata -->
    <div id="feedback-error" class="hidden text-center py-4">
        <span class="text-red-600 text-lg" id="error-message">Bir hata oluştu.</span>
    </div>

    <!-- Zaten verildi -->
    <div id="feedback-already-given" class="hidden text-center py-4">
        <span class="text-yellow-600 text-lg">Bu sayfa için daha önce geri bildirim verdiniz.</span>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const pageUrl = '{{ $pageUrl }}';
    const pageTitle = '{{ $pageTitle }}';
    
    const feedbackQuestion = document.getElementById('feedback-question');
    const feedbackLoading = document.getElementById('feedback-loading');
    const feedbackSuccess = document.getElementById('feedback-success');
    const feedbackError = document.getElementById('feedback-error');
    const feedbackAlreadyGiven = document.getElementById('feedback-already-given');
    const errorMessage = document.getElementById('error-message');
    
    // Sayfa yüklendiğinde kontrol et
    checkUserFeedback();
    
    // Buton event'leri
    document.querySelectorAll('.feedback-btn').forEach(button => {
        button.addEventListener('click', function() {
            const isHelpful = this.dataset.helpful === 'true';
            sendFeedback(isHelpful);
        });
    });
    
    function checkUserFeedback() {
        fetch(`/api/page-feedback/check?page_url=${encodeURIComponent(pageUrl)}`)
            .then(response => response.json())
            .then(data => {
                if (data.success && data.data.has_feedback) {
                    showAlreadyGiven();
                }
            })
            .catch(() => {
                // Hata durumunda butonları göster
            });
    }
    
    function sendFeedback(isHelpful) {
        showLoading();
        
        fetch('/api/page-feedback', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                page_url: pageUrl,
                page_title: pageTitle,
                is_helpful: isHelpful
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showSuccess();
            } else {
                showError(data.message);
            }
        })
        .catch(() => {
            showError('Bir hata oluştu.');
        });
    }
    
    function hideAll() {
        feedbackQuestion.classList.add('hidden');
        feedbackLoading.classList.add('hidden');
        feedbackSuccess.classList.add('hidden');
        feedbackError.classList.add('hidden');
        feedbackAlreadyGiven.classList.add('hidden');
    }
    
    function showLoading() {
        hideAll();
        feedbackLoading.classList.remove('hidden');
    }
    
    function showSuccess() {
        hideAll();
        feedbackSuccess.classList.remove('hidden');
    }
    
    function showError(message) {
        hideAll();
        errorMessage.textContent = message;
        feedbackError.classList.remove('hidden');
        
        // 3 saniye sonra soruyu tekrar göster
        setTimeout(() => {
            hideAll();
            feedbackQuestion.classList.remove('hidden');
        }, 3000);
    }
    
    function showAlreadyGiven() {
        hideAll();
        feedbackAlreadyGiven.classList.remove('hidden');
    }
});
</script> 