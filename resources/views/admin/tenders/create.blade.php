@extends('adminlte::page')

@section('title', 'Yeni İhale Ekle')

@section('content_header')
    <h1>Yeni İhale Ekle</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">İhale Bilgileri</h3>
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

            <form action="{{ route('admin.tenders.store') }}" method="POST">
                @csrf
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="title">İhale Konusu <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="title" name="title" value="{{ old('title') }}" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="unit">İhale Birimi <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="unit" name="unit" value="{{ old('unit') }}" required>
                        </div>
                    </div>
                </div>
                
                <div class="form-group mt-3">
                    <label for="summary">İhale Kısa Özet</label>
                    <textarea class="form-control" id="summary" name="summary" rows="3">{{ old('summary') }}</textarea>
                </div>
                
                <div class="row mt-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="kik_no">KİK Kayıt No</label>
                            <input type="text" class="form-control" id="kik_no" name="kik_no" value="{{ old('kik_no') }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="tender_datetime">İhale Tarihi/Saati</label>
                            <input type="datetime-local" class="form-control" id="tender_datetime" name="tender_datetime" value="{{ old('tender_datetime') }}">
                        </div>
                    </div>
                </div>
                
                <div class="form-group mt-3">
                    <label for="address">İdare'nin Adresi</label>
                    <textarea class="form-control" id="address" name="address" rows="3">{{ old('address') }}</textarea>
                </div>
                
                <div class="row mt-3">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="phone">İdare'nin Telefon Numarası</label>
                            <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone') }}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="fax">İdare'nin Faks</label>
                            <input type="text" class="form-control" id="fax" name="fax" value="{{ old('fax') }}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="email">İdare'nin E-Postası</label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}">
                        </div>
                    </div>
                </div>
                
                <div class="form-group mt-3">
                    <label for="document_url">Döküman URL</label>
                    <input type="text" class="form-control" id="document_url" name="document_url" value="{{ old('document_url') }}">
                </div>
                
                <div class="form-group mt-3">
                    <label for="description">İhale Konusu, Hizmetin Niteliği, Türü ve Miktarı</label>
                    <textarea class="form-control" id="description" name="description" rows="4">{{ old('description') }}</textarea>
                </div>
                
                <div class="row mt-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="delivery_place">Teslim Yeri</label>
                            <input type="text" class="form-control" id="delivery_place" name="delivery_place" value="{{ old('delivery_place') }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="delivery_date">Teslim Tarihi</label>
                            <input type="text" class="form-control" id="delivery_date" name="delivery_date" value="{{ old('delivery_date') }}">
                        </div>
                    </div>
                </div>
                
                <div class="form-group mt-3">
                    <label for="tender_address">İhale'nin Yapılacağı Adres</label>
                    <textarea class="form-control" id="tender_address" name="tender_address" rows="3">{{ old('tender_address') }}</textarea>
                </div>
                
                <div class="form-group mt-3">
                    <label for="content">İhale Metni</label>
                    <textarea class="form-control tinymce" id="content" name="content" rows="10">{{ old('content') }}</textarea>
                </div>
                
                <div class="form-group mt-3">
                    <label for="status">Durum <span class="text-danger">*</span></label>
                    <select class="form-control" id="status" name="status" required>
                        <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Tamamlandı</option>
                        <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>İptal Edildi</option>
                    </select>
                </div>
                
                <div class="form-group mt-4">
                    <button type="submit" class="btn btn-primary">Kaydet</button>
                    <a href="{{ route('admin.tenders.index') }}" class="btn btn-secondary">İptal</a>
                </div>
            </form>
        </div>
    </div>
@stop

@section('js')
    <script>
        // TinyMCE'yi dinamik olarak yükle
        var script = document.createElement('script');
        script.type = 'text/javascript';
        script.src = '{{ asset("vendor/tinymce/tinymce/js/tinymce/tinymce.min.js") }}';
        document.head.appendChild(script);
        
        script.onload = function() {
            console.log('TinyMCE yüklendi');
            
            // TinyMCE Editör
            tinymce.init({
                selector: '.tinymce',
                plugins: 'preview importcss searchreplace autolink autosave save directionality code visualblocks visualchars fullscreen image link media template codesample table charmap pagebreak nonbreaking anchor insertdatetime advlist lists wordcount help charmap quickbars emoticons',
                menubar: 'file edit view insert format tools table help',
                toolbar: 'undo redo | bold italic underline strikethrough | fontfamily image fontsize blocks | alignleft aligncenter alignright alignjustify | outdent indent |  numlist bullist | forecolor backcolor removeformat | pagebreak | charmap emoticons | fullscreen preview save print | insertfile media template link anchor codesample | ltr rtl',
                toolbar_sticky: true,
                image_advtab: false,
                height: 500,
                quickbars_selection_toolbar: 'bold italic | quicklink h2 h3 blockquote quickimage quicktable',
                quickbars_insert_toolbar: 'quickimage | quicktable quicklink hr',
                quickbars_insert_toolbar_hover: false,
                quickbars_image_toolbar: false,
                noneditable_class: 'mceNonEditable',
                language: 'tr',
                language_url: '/js/tinymce/langs/tr.js', // Türkçe dil dosyası
                toolbar_mode: 'sliding',
                contextmenu: 'link table',  // image'i çıkardık
                skin: 'oxide',
                content_css: 'default',
                content_style: 'body { font-family: -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif; font-size: 16px; }',
                relative_urls: false,
                remove_script_host: false,
                convert_urls: true,
                branding: false,
                promotion: false,
                paste_data_images: true, // Panodaki resimlerin yapıştırılmasını sağlar
                images_upload_url: '{{ route("admin.tinymce.upload") }}',
                images_upload_credentials: true
            });
        };
    </script>
@stop 