@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Dosya Yöneticisi</h3>
                    
                    <div class="card-tools">
                        <div class="btn-group">
                            <button type="button" class="btn btn-sm btn-primary" id="showAllFilesBtn">
                                <i class="fas fa-images"></i> Tüm Dosyalar
                            </button>
                            <button type="button" class="btn btn-sm btn-success" id="showContentFilesBtn">
                                <i class="fas fa-tags"></i> Bu İçeriğe Ait Dosyalar
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="fm-container">
                        <div class="loading">
                            <div class="spinner-border text-primary" role="status">
                                <span class="sr-only">Yükleniyor...</span>
                            </div>
                        </div>
                        <div id="fm"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('css')
<link rel="stylesheet" href="{{ asset('vendor/file-manager/css/file-manager.css') }}">
<style>
    .fm-container {
        height: 600px;
        position: relative;
    }
    .loading {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        z-index: 9999;
        display: none;
    }
</style>
@endsection

@section('js')
<script src="{{ asset('vendor/file-manager/js/file-manager.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // URL parametrelerini al
        const urlParams = new URLSearchParams(window.location.search);
        const relatedTo = urlParams.get('related_to') || '';
        const relatedId = urlParams.get('related_id') || '';
        const type = urlParams.get('type') || 'image';
        
        console.log('Custom File Manager Parametreleri:', {
            relatedTo: relatedTo,
            relatedId: relatedId,
            type: type
        });
        
        // File Manager'ı başlat
        const fm = document.getElementById('fm');
        let fileManager = null;
        
        // Original useFile fonksiyonunu sakla
        const originalUseFile = window.useFile;
        
        // useFile fonksiyonunu override et
        window.useFile = function(fileUrl) {
            console.log('useFile çağırıldı:', fileUrl);
            
            try {
                // fileUrl bir URL string'i veya DOM elementi olabilir
                let url = fileUrl;
                
                // Eğer bir DOM elementi ise URL'yi al
                if (fileUrl instanceof HTMLElement) {
                    // a etiketinden url'yi al
                    url = fileUrl.getAttribute('data-url') || fileUrl.getAttribute('href');
                    console.log('DOM elementinden URL alındı:', url);
                }
                
                // İlgili medya dosyasını kaydet
                if (relatedTo && relatedId) {
                    console.log('Medya ilişkisi kaydediliyor...');
                    
                    $.ajax({
                        url: '/admin/filemanager/save-media-relation',
                        method: 'POST',
                        data: {
                            file_path: url,
                            related_to: relatedTo,
                            related_id: relatedId,
                            _token: $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            console.log('Medya ilişkisi kaydedildi:', response);
                        },
                        error: function(xhr) {
                            console.error('Medya ilişkisi kaydedilemedi:', xhr.responseText);
                        }
                    });
                } else {
                    console.log('Medya ilişkisi parametreleri eksik, kayıt yapılmadı.');
                }
                
                // Eğer fileUrl içinde / yoksa, tam URL oluştur
                if (url && url.indexOf('/') !== 0 && url.indexOf('http') !== 0) {
                    url = '/' + url;
                }
                
                console.log('Seçilen dosya URL:', url);
                
                if (window.opener && typeof window.opener.SetUrl === 'function') {
                    console.log('window.opener.SetUrl fonksiyonu çağırılıyor...');
                    window.opener.SetUrl(url);
                    window.close();
                } else {
                    console.log('Ana pencereye mesaj gönderiliyor...');
                    // Ana pencereye postMessage ile iletişim kur
                    window.parent.postMessage({
                        type: 'fileSelected',
                        url: url
                    }, '*');
                    
                    // Modal kullanılıyorsa, kapat
                    if (window.parent !== window) {
                        console.log('Iframe içinde çalışıyor, parent mesajı gönderildi.');
                    }
                }
            } catch (error) {
                console.error('useFile fonksiyonunda hata:', error);
            }
        };
        
        try {
            // File Manager konfigürasyonu
            fileManager = new FileManager({
                elementId: 'fm',
                lang: 'tr',
                height: 600,
                baseUrl: '/admin/filemanager',
                dawnload: true,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
            });
            
            console.log('File Manager başlatıldı, içerik türü:', type);
            
            // Eğer görsel seçimi ise, sadece görselleri göster
            if (type === 'image') {
                fileManager.setFilter('image');
            }
            
            console.log('File Manager filtresi ayarlandı:', type);
            
            // Tüm dosyaları göster butonu
            $('#showAllFilesBtn').on('click', function() {
                console.log('Tüm dosyalar butonu tıklandı');
                $('.loading').show();
                
                // FileManager'ın normal URL'sine git
                fileManager.refresh();
                
                setTimeout(function() {
                    $('.loading').hide();
                }, 1000);
            });
            
            // İçeriğe ait dosyaları göster butonu
            $('#showContentFilesBtn').on('click', function() {
                console.log('İçeriğe ait dosyalar butonu tıklandı');
                showContentFiles();
            });
            
            // Sayfa yüklendiğinde içeriğe ait dosyaları göster
            if (relatedTo && relatedId) {
                console.log('Sayfa yüklendiğinde içeriğe ait dosyalar gösteriliyor');
                setTimeout(function() {
                    showContentFiles();
                }, 1000);
            }
            
            // İçeriğe ait dosyaları göster fonksiyonu
            function showContentFiles() {
                if (!relatedTo || !relatedId) {
                    console.error('İçerik parametreleri eksik!');
                    return;
                }
                
                console.log('İçeriğe ait dosyalar getiriliyor:', relatedTo, relatedId);
                $('.loading').show();
                
                // API isteği ile içeriğe ait dosyaları getir
                $.ajax({
                    url: '/admin/api/content-files',
                    method: 'GET',
                    data: {
                        related_to: relatedTo,
                        related_id: relatedId
                    },
                    success: function(response) {
                        console.log('API yanıtı:', response);
                        
                        // Eğer sonuçlar varsa, custom içeriği göster
                        if (response && response.result && response.result.items) {
                            console.log('İçeriğe ait dosya sayısı:', response.result.items.length);
                            
                            // File Manager'ın dosyaları göstermesi için JS fonksiyonlarını kullan
                            const files = response.result.items;
                            
                            // File Manager'a özel veri formatı
                            const data = {
                                result: {
                                    items: files,
                                    paginator: null
                                }
                            };
                            
                            // FileManager custom data ile yenile
                            if (typeof fileManager.customData === 'function') {
                                console.log('FileManager.customData metodu çağırılıyor');
                                fileManager.customData(data);
                            } else {
                                console.log('FileManager.customData metodu bulunamadı, alternatif yöntem kullanılıyor');
                                // Alternatif yöntem: DOM manipülasyonu ile içerik ekleme
                                const fileContainer = document.querySelector('.fm-content .fm-items');
                                
                                if (fileContainer) {
                                    fileContainer.innerHTML = '';
                                    
                                    files.forEach(function(file) {
                                        const fileItem = document.createElement('div');
                                        fileItem.className = 'fm-item';
                                        fileItem.setAttribute('data-url', file.url);
                                        fileItem.addEventListener('click', function() {
                                            useFile(file.url);
                                        });
                                        
                                        let fileContent = '';
                                        if (file.is_image) {
                                            fileContent = `<div class="fm-item-icon"><img src="${file.thumb_url}" alt="${file.name}"></div>`;
                                        } else {
                                            fileContent = `<div class="fm-item-icon"><i class="fas ${file.icon}"></i></div>`;
                                        }
                                        
                                        fileContent += `<div class="fm-item-name">${file.name}</div>`;
                                        fileItem.innerHTML = fileContent;
                                        
                                        fileContainer.appendChild(fileItem);
                                    });
                                }
                            }
                        } else {
                            console.log('İçeriğe ait dosya bulunamadı');
                        }
                        
                        $('.loading').hide();
                    },
                    error: function(xhr, status, error) {
                        console.error('API hatası:', xhr.responseText);
                        $('.loading').hide();
                        
                        // Hata durumunda normal File Manager'a dön
                        fileManager.refresh();
                    }
                });
            }
            
            // File Manager yüklendikten sonra
            fm.addEventListener('fm-loaded', function() {
                console.log('File Manager yüklendi');
                
                // useFile fonksiyonunu override et (yüklemeden sonra tekrar)
                window.useFile = function(fileUrl) {
                    console.log('useFile (loaded sonrası) çağırıldı:', fileUrl);
                    
                    try {
                        // fileUrl bir URL string'i veya DOM elementi olabilir
                        let url = fileUrl;
                        
                        // Eğer bir DOM elementi ise URL'yi al
                        if (fileUrl instanceof HTMLElement) {
                            // a etiketinden url'yi al
                            url = fileUrl.getAttribute('data-url') || fileUrl.getAttribute('href');
                            console.log('DOM elementinden URL alındı:', url);
                        }
                        
                        // İlgili medya dosyasını kaydet
                        if (relatedTo && relatedId) {
                            console.log('Medya ilişkisi kaydediliyor...');
                            
                            $.ajax({
                                url: '/admin/filemanager/save-media-relation',
                                method: 'POST',
                                data: {
                                    file_path: url,
                                    related_to: relatedTo,
                                    related_id: relatedId,
                                    _token: $('meta[name="csrf-token"]').attr('content')
                                },
                                success: function(response) {
                                    console.log('Medya ilişkisi kaydedildi:', response);
                                },
                                error: function(xhr) {
                                    console.error('Medya ilişkisi kaydedilemedi:', xhr.responseText);
                                }
                            });
                        }
                        
                        // Eğer fileUrl içinde / yoksa, tam URL oluştur
                        if (url && url.indexOf('/') !== 0 && url.indexOf('http') !== 0) {
                            url = '/' + url;
                        }
                        
                        console.log('Seçilen dosya URL:', url);
                        
                        if (window.opener && typeof window.opener.SetUrl === 'function') {
                            console.log('window.opener.SetUrl fonksiyonu çağırılıyor...');
                            window.opener.SetUrl(url);
                            window.close();
                        } else {
                            console.log('Ana pencereye mesaj gönderiliyor...');
                            // Ana pencereye postMessage ile iletişim kur
                            window.parent.postMessage({
                                type: 'fileSelected',
                                url: url
                            }, '*');
                            
                            // Modal kullanılıyorsa, kapat
                            if (window.parent !== window) {
                                console.log('Iframe içinde çalışıyor, parent mesajı gönderildi.');
                            }
                        }
                    } catch (error) {
                        console.error('useFile fonksiyonunda hata:', error);
                    }
                };
                
                // Eğer içeriğe ait dosyaları göstermek gerekiyorsa
                if (relatedTo && relatedId) {
                    console.log('File Manager yüklendikten sonra içeriğe ait dosyalar gösteriliyor');
                    showContentFiles();
                }
            });
            
            // Seçilen dosya event listener'ı
            fm.addEventListener('fm-selection', function(e) {
                console.log('Dosya seçildi:', e.detail);
            });
            
            window.addEventListener('message', function(event) {
                console.log('PostMessage alındı:', event.data);
                
                if (event.data && event.data.type === 'fileSelected') {
                    console.log('Seçilen dosya URL:', event.data.url);
                }
            });
            
        } catch (error) {
            console.error('File Manager başlatılırken hata:', error);
        }
    });
</script>
@endsection 