<div class="icon-selector">
    <div class="form-group">
        <label for="icon">İkon Seçimi</label>
        <div class="input-group">
            <input type="text" class="form-control @error('icon') is-invalid @enderror" id="icon" name="icon" value="{{ $item->icon ?? old('icon', 'link') }}" readonly>
            <div class="input-group-append">
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#iconSelectorModal">
                    <i class="mdi mdi-grid"></i> İkon Seç
                </button>
            </div>
            @error('icon')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>
        <div class="mt-2" id="selected-icon">
            <span class="material-icons">{{ $item->icon ?? old('icon', 'link') }}</span>
            <span class="ml-2">{{ $item->icon ?? old('icon', 'link') }}</span>
        </div>
    </div>
</div>

<!-- İkon Seçici Modal -->
<div class="modal fade" id="iconSelectorModal" tabindex="-1" role="dialog" aria-labelledby="iconSelectorModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="iconSelectorModalLabel">Material Icons Seçici</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <input type="text" class="form-control" id="icon-search" placeholder="İkon ara...">
                </div>
                <div class="icons-container" id="icons-list">
                    <!-- İkonlar AJAX ile yüklenecek -->
                    <div class="text-center py-3">
                        <div class="spinner-border text-primary" role="status">
                            <span class="sr-only">Yükleniyor...</span>
                        </div>
                        <p class="mt-2">İkonlar yükleniyor...</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Kapat</button>
            </div>
        </div>
    </div>
</div>

<style>
    .icons-container {
        max-height: 400px;
        overflow-y: auto;
        padding: 10px;
    }
    
    .icons-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(80px, 1fr));
        gap: 10px;
    }
    
    .icon-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        border: 1px solid #eee;
        border-radius: 5px;
        padding: 10px 5px;
        cursor: pointer;
        text-align: center;
        transition: all 0.2s;
    }
    
    .icon-item:hover {
        background-color: #f8f9fa;
        border-color: #dee2e6;
    }
    
    .icon-item.selected {
        background-color: #e9ecef;
        border-color: #ced4da;
    }
    
    .icon-item .material-icons {
        font-size: 24px;
        margin-bottom: 5px;
    }
    
    .icon-item .icon-name {
        font-size: 10px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        width: 100%;
    }
</style>

<script>
    // Bu script sadece bir kere çalışacak şekilde sayfa yüklendiğinde çalışır
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof iconSelectorInitialized === 'undefined') {
            window.iconSelectorInitialized = true;
            initIconSelector();
        }
    });
    
    function initIconSelector() {
        const iconSearch = document.getElementById('icon-search');
        const iconsList = document.getElementById('icons-list');
        
        // İkon listesini yükle
        fetch('{{ route("menusystem.items.icons") }}')
            .then(response => response.json())
            .then(icons => {
                renderIconList(icons);
            })
            .catch(error => {
                iconsList.innerHTML = `<div class="alert alert-danger">İkonlar yüklenirken bir hata oluştu: ${error.message}</div>`;
            });
        
        // İkon arama işlemi
        iconSearch.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const icons = document.querySelectorAll('.icon-item');
            
            icons.forEach(icon => {
                const iconName = icon.dataset.name.toLowerCase();
                if (iconName.includes(searchTerm)) {
                    icon.style.display = 'flex';
                } else {
                    icon.style.display = 'none';
                }
            });
        });
        
        // İkon seçme işlemi
        iconsList.addEventListener('click', function(e) {
            const iconItem = e.target.closest('.icon-item');
            if (iconItem) {
                const iconName = iconItem.dataset.name;
                
                document.getElementById('icon').value = iconName;
                document.getElementById('selected-icon').innerHTML = `
                    <span class="material-icons">${iconName}</span>
                    <span class="ml-2">${iconName}</span>
                `;
                
                // Tüm seçili ikonları temizle
                document.querySelectorAll('.icon-item').forEach(i => {
                    i.classList.remove('selected');
                });
                
                // Seçilen ikonu işaretle
                iconItem.classList.add('selected');
                
                // Modalı kapat
                $('#iconSelectorModal').modal('hide');
            }
        });
    }
    
    function renderIconList(icons) {
        const iconsList = document.getElementById('icons-list');
        const selectedIcon = document.getElementById('icon').value;
        
        // HTML içeriğini oluştur
        let html = '<div class="icons-grid">';
        
        icons.forEach(icon => {
            const isSelected = icon === selectedIcon ? 'selected' : '';
            html += `
                <div class="icon-item ${isSelected}" data-name="${icon}">
                    <span class="material-icons">${icon}</span>
                    <span class="icon-name">${icon}</span>
                </div>
            `;
        });
        
        html += '</div>';
        
        // HTML içeriğini ata
        iconsList.innerHTML = html;
    }
</script> 