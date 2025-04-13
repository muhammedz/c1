{{-- Frontend Duyuru Bileşeni --}}
@if(isset($announcements) && count($announcements) > 0)
<div class="announcements-container">
    @foreach($announcements as $announcement)
    <div id="announcement-{{ $announcement->id }}" class="announcement-item position-{{ $announcement->position }}" style="display: none;"
         data-id="{{ $announcement->id }}" 
         data-position="{{ $announcement->position }}"
         data-bg-color="{{ $announcement->bg_color }}"
         data-text-color="{{ $announcement->text_color }}"
         data-border-color="{{ $announcement->border_color }}">
        <div class="announcement-content" style="background-color: {{ $announcement->bg_color }}; color: {{ $announcement->text_color }}; border-color: {{ $announcement->border_color }};">
            <div class="d-flex align-items-start">
                <i class="material-icons mr-2">{{ $announcement->icon }}</i>
                <div class="flex-grow-1">
                    <h5 class="announcement-title">{{ $announcement->title }}</h5>
                    <p class="announcement-text">{{ $announcement->content }}</p>
                    @if($announcement->button_text && $announcement->button_url)
                    <a href="{{ $announcement->button_url }}" class="announcement-button" style="color: {{ $announcement->text_color }}; border-color: {{ $announcement->text_color }};">
                        {{ $announcement->button_text }}
                    </a>
                    @endif
                </div>
                <button type="button" class="close-announcement" onclick="closeAnnouncement({{ $announcement->id }})">
                    <i class="material-icons">close</i>
                </button>
            </div>
        </div>
    </div>
    @endforeach
</div>

<style>
.announcements-container {
    position: relative;
    z-index: 9999;
}

.announcement-item {
    position: fixed;
    transition: all 0.3s ease;
    max-width: 400px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
    opacity: 0;
    transform: translateY(100px);
    z-index: 50;
}

.announcement-item.show {
    opacity: 1;
    transform: translateY(0);
}

.announcement-item.position-top {
    top: 20px;
    right: 20px;
}

.announcement-item.position-bottom {
    bottom: 20px;
    right: 20px;
}

.announcement-item.position-left {
    bottom: 20px;
    left: 20px;
}

.announcement-item.position-right {
    bottom: 20px;
    right: 20px;
}

.announcement-content {
    padding: 16px;
    border-left-width: 4px;
    border-left-style: solid;
    border-radius: 4px;
}

.announcement-title {
    font-weight: bold;
    margin-bottom: 8px;
    font-size: 16px;
}

.announcement-text {
    font-size: 14px;
    margin-bottom: 10px;
}

.announcement-button {
    display: inline-block;
    padding: 4px 12px;
    border: 1px solid;
    border-radius: 4px;
    font-size: 13px;
    text-decoration: none;
    transition: all 0.2s;
    background-color: transparent;
}

.announcement-button:hover {
    opacity: 0.8;
    text-decoration: none;
}

.close-announcement {
    background: transparent;
    border: none;
    color: inherit;
    opacity: 0.6;
    cursor: pointer;
    padding: 0;
    margin-left: 10px;
    transition: opacity 0.2s;
}

.close-announcement:hover {
    opacity: 1;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Duyuruları göster (gecikmeli olarak)
    setTimeout(function() {
        showAnnouncements();
    }, 1000);
    
    // Sayfa kapatılırken görüntülenen duyuruları kaydet
    window.addEventListener('beforeunload', function() {
        const visibleAnnouncements = document.querySelectorAll('.announcement-item.show');
        visibleAnnouncements.forEach(function(element) {
            const id = element.dataset.id;
            markAnnouncementAsViewed(id);
        });
    });
});

// Duyuruları gösterme fonksiyonu
function showAnnouncements() {
    const announcements = document.querySelectorAll('.announcement-item');
    let delay = 0;
    
    announcements.forEach(function(announcement, index) {
        setTimeout(function() {
            announcement.style.display = 'block';
            
            setTimeout(function() {
                announcement.classList.add('show');
            }, 50);
            
        }, delay);
        
        // Her duyuru arasına 500ms gecikme ekle
        delay += 500;
    });
}

// Duyuruyu kapatma fonksiyonu
function closeAnnouncement(id) {
    const announcement = document.getElementById('announcement-' + id);
    
    if (announcement) {
        announcement.classList.remove('show');
        
        setTimeout(function() {
            announcement.style.display = 'none';
        }, 300);
        
        // Duyurunun görüntülendiğini kaydet
        markAnnouncementAsViewed(id);
    }
}

// Duyurunun görüntülendiğini backend'e bildir
function markAnnouncementAsViewed(id) {
    fetch('/announcements/mark-viewed', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            id: id
        })
    })
    .catch(function(error) {
        console.error('Duyuru görüntüleme kaydı hatası:', error);
    });
}
</script>
@endif 