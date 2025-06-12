@extends('adminlte::page')

@section('title', 'Yeni Kurumsal Kadro Kategorisi Ekle')

@section('content_header')
    <h1>Yeni Kurumsal Kadro Kategorisi Ekle</h1>
@stop

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Yeni Kurumsal Kadro Kategorisi Ekle</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.corporate.categories.index') }}" class="btn btn-default btn-sm">
                            <i class="fas fa-arrow-left"></i> Listeye Dön
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.corporate.categories.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="name">Kategori Adı <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="slug">Slug</label>
                            <input type="text" class="form-control" id="slug" name="slug" value="{{ old('slug') }}">
                            <small class="text-muted">Boş bırakırsanız, isimden otomatik oluşturulacaktır.</small>
                        </div>
                        
                        <div class="form-group">
                            <label for="description">Açıklama</label>
                            <textarea class="form-control" id="description" name="description" rows="3">{{ old('description') }}</textarea>
                        </div>
                        
                        <div class="form-group">
                            <label for="image">Kategori Görseli (Dosya Yükleme)</label>
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="image" name="image" accept="image/*">
                                    <label class="custom-file-label" for="image">Dosya Seç</label>
                                </div>
                            </div>
                            <small class="text-muted">İzin verilen dosya türleri: JPG, JPEG, PNG, GIF. Maksimum boyut: 2MB</small>
                        </div>

                        <!-- FileManagerSystem Görsel -->
                        <div class="form-group">
                            <label for="filemanagersystem_image">Kategori Görseli (Medya Kütüphanesi)</label>
                            <div class="input-group">
                                <input type="text" class="form-control @error('filemanagersystem_image') is-invalid @enderror" id="filemanagersystem_image" name="filemanagersystem_image" value="{{ old('filemanagersystem_image') }}" readonly>
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-primary" id="filemanagersystem_image_button">
                                        <i class="fas fa-image"></i> Görsel Seç
                                    </button>
                                </div>
                            </div>
                            <div id="filemanagersystem_image_preview" class="mt-2" style="display: none;">
                                <img src="" alt="Önizleme" class="img-thumbnail" style="max-height: 200px;">
                            </div>
                            @error('filemanagersystem_image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="filemanagersystem_image_alt">Görsel Alt Metni</label>
                                    <input type="text" class="form-control @error('filemanagersystem_image_alt') is-invalid @enderror" id="filemanagersystem_image_alt" name="filemanagersystem_image_alt" value="{{ old('filemanagersystem_image_alt') }}">
                                    <small class="text-muted">Görsel yüklenemediğinde gösterilecek metin.</small>
                                    @error('filemanagersystem_image_alt')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="filemanagersystem_image_title">Görsel Başlığı</label>
                                    <input type="text" class="form-control @error('filemanagersystem_image_title') is-invalid @enderror" id="filemanagersystem_image_title" name="filemanagersystem_image_title" value="{{ old('filemanagersystem_image_title') }}">
                                    <small class="text-muted">Görsel üzerine gelindiğinde gösterilecek metin.</small>
                                    @error('filemanagersystem_image_title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="order">Sıralama</label>
                            <input type="number" class="form-control" id="order" name="order" value="{{ old('order', 0) }}">
                        </div>
                        
                        <div class="form-group">
                            <label for="status">Durum</label>
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="status" name="status" value="1" {{ old('status', '1') == '1' ? 'checked' : '' }}>
                                <label class="custom-control-label" for="status">Aktif</label>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Kaydet</button>
                            <a href="{{ route('admin.corporate.categories.index') }}" class="btn btn-secondary">İptal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- MediaPicker Modal -->
<div class="modal fade" id="mediapickerModal" tabindex="-1" role="dialog" aria-labelledby="mediapickerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document" style="max-width: 90%;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="mediapickerModalLabel">Medya Seçici</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Kapat">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-0">
                <iframe id="mediapickerFrame" style="width: 100%; height: 80vh; border: none;"></iframe>
            </div>
        </div>
    </div>
</div>
@stop

@section('js')
<script src="{{ asset('js/slug-helper.js') }}"></script>
<script>
    $(document).ready(function () {
        // Slug oluşturucu - Turkish character desteği ile
        SlugHelper.autoSlug('#name', '#slug');
        
        // Input file görselleştirme
        bsCustomFileInput.init();
        
        // Görsel önizleme
        $('#image').on('change', function() {
            var input = this;
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                
                reader.onload = function(e) {
                    // Önizleme gösterilecek bir div eklemek isterseniz buraya ekleyebilirsiniz
                };
                
                reader.readAsDataURL(input.files[0]);
            }
        });

        // FileManagerSystem entegrasyonu
        $('#filemanagersystem_image_button').on('click', function() {
            try {
                const input = $('#filemanagersystem_image');
                const preview = $('#filemanagersystem_image_preview');
                const previewImg = preview.find('img');
                
                console.log('MediaPicker açılıyor...');
                
                // Geçici ID oluştur - bu sistemde temp_ ile başlayan ID'ler özel işlenir
                const tempId = 'temp_' + Date.now();
                const relatedType = 'corporate_category';
                
                // Medya seçici URL - geçici ID ile oluştur
                const mediapickerUrl = '/admin/filemanagersystem/mediapicker?type=image&related_type=' + 
                    encodeURIComponent(relatedType) + '&related_id=' + encodeURIComponent(tempId);
                
                console.log('MediaPicker URL:', mediapickerUrl);
                
                // Modal açılıp iframe yüklenmesi
                $('#mediapickerModal').modal('show');
                $('#mediapickerFrame').attr('src', mediapickerUrl);
                
                // iframe'den mesaj dinleme ve hata yakalama
                function handleMediaSelection(event) {
                    try {
                        console.log('🔍 Medya mesajı alındı:');
                        console.log('- Kaynak origin:', event.origin);
                        console.log('- Ham veri:', event.data);
                        
                        if (!event.data) {
                            console.log('Boş mesaj alındı, işlem yapılmıyor');
                            return;
                        }
                        
                        // Sadece mediaSelected veya mediapickerError tipindeki mesajları işle
                        if (event.data.type === 'mediaSelected') {
                            console.log('✅ Seçilen medya:', event.data);
                            
                            // Media ID veya URL kontrolü
                            let mediaValue = '';
                            let previewUrl = '';
                            let displayText = '';
                            
                            if (event.data.mediaId) {
                                console.log('📋 Medya ID alındı:', event.data.mediaId);
                                mediaValue = event.data.mediaId;
                                previewUrl = '/admin/filemanagersystem/media/preview/' + event.data.mediaId;
                                displayText = 'Kategori Görseli (ID: ' + event.data.mediaId + ')';
                            }
                            else if (event.data.mediaUrl) {
                                console.log('🔗 Medya URL alındı:', event.data.mediaUrl);
                                mediaValue = event.data.mediaUrl.trim();
                                previewUrl = mediaValue;
                                // URL'i kısaltarak göster (çok uzun olmasın)
                                let shortUrl = event.data.mediaUrl;
                                if (shortUrl.length > 40) {
                                    shortUrl = shortUrl.substring(0, 20) + '...' + shortUrl.substring(shortUrl.length - 20);
                                }
                                displayText = 'URL: ' + shortUrl;
                            }
                            
                            if (mediaValue) {
                                // Gizli input'a değeri kaydet, görünen input'a kullanıcı dostu metin göster 
                                input.val(mediaValue);
                                displayInput.val('Seçilen görsel: ' + displayText);
                                
                                // Önizleme göster
                                previewImg.attr('src', previewUrl);
                                preview.show();
                                
                                console.log('✓ Medya değeri işlendi:', mediaValue);
                            } else {
                                console.error('❌ Medya değeri alınamadı');
                                alert('Medya bilgisi alınamadı. Lütfen tekrar deneyin.');
                            }
                            
                            // Modalı kapat
                            $('#mediapickerModal').modal('hide');
                        } else if (event.data.type === 'mediapickerError') {
                            // Medya seçicide bir hata oluştu
                            console.error('❌ Medya seçici hatası:', event.data);
                            alert('Medya seçici hatası: ' + (event.data.message || 'Bilinmeyen hata'));
                            $('#mediapickerModal').modal('hide');
                        } else if (event.data.type === 'mediapickerLoaded') {
                            console.log('ℹ️ Medya seçici yüklendi');
                        } else {
                            console.log('ℹ️ Bilinmeyen medya mesajı:', event.data);
                        }
                    } catch (error) {
                        console.error('❌ Medya seçimi işlenirken hata oluştu:', error);
                        alert('Medya seçimi işlenirken bir hata oluştu: ' + error.message);
                    }
                }
                
                // Mevcut event listener'ı kaldır ve yenisini ekle
                window.removeEventListener('message', handleMediaSelection);
                window.addEventListener('message', handleMediaSelection);
                
                // Iframe yüklenmesini kontrol et
                $('#mediapickerFrame').on('load', function() {
                    console.log('🟢 Medya iframe yüklendi');
                }).on('error', function(error) {
                    console.error('🔴 MediaPicker iframe yüklenirken hata oluştu:', error);
                    alert('Medya seçici yüklenirken bir hata oluştu. Lütfen sayfayı yenileyip tekrar deneyin.');
                    $('#mediapickerModal').modal('hide');
                });
                
                // Modal kapatıldığında iframe kaynağını temizle
                $('#mediapickerModal').on('hidden.bs.modal', function() {
                    console.log('🔴 Medya seçici kapatıldı');
                    $('#mediapickerFrame').attr('src', 'about:blank');
                });
            } catch (error) {
                console.error('❌ Medya seçici açılırken hata oluştu:', error);
                alert('Medya seçici açılırken bir hata oluştu: ' + error.message);
            }
        });
    });
</script>
@stop 