@extends('adminlte::page')

@section('title', 'Footer Yönetimi')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Footer Yönetimi</h1>
        <a href="{{ route('admin.footer.menus.index') }}" class="btn btn-primary">
            <i class="fas fa-list"></i> Menüleri Yönet
        </a>
    </div>
@stop

@section('content')
    @if(session('success'))
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            {{ session('success') }}
        </div>
    @endif

    <div class="row">
        <!-- Footer Ayarları -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-cogs"></i> Footer Ayarları
                    </h3>
                </div>
                <form action="{{ route('admin.footer.settings.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <!-- Logo Yükleme -->
                        <div class="form-group">
                            <label for="logo">Logo</label>
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="logo" name="logo" accept="image/*">
                                    <label class="custom-file-label" for="logo">Logo seçin...</label>
                                </div>
                            </div>
                            @if($settings->logo)
                                <div class="mt-2">
                                    <img src="{{ $settings->logo_url }}" alt="Logo" class="img-thumbnail" style="max-height: 100px;">
                                    <a href="{{ route('admin.footer.logo.delete') }}" class="btn btn-sm btn-danger ml-2" 
                                       onclick="return confirm('Logo silinsin mi?')" 
                                       data-method="delete">
                                        <i class="fas fa-trash"></i> Sil
                                    </a>
                                </div>
                            @endif
                            @error('logo')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Şirket Bilgileri -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="company_name">Şirket Adı</label>
                                    <input type="text" class="form-control @error('company_name') is-invalid @enderror" 
                                           id="company_name" name="company_name" 
                                           value="{{ old('company_name', $settings->company_name) }}" required>
                                    @error('company_name')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="company_subtitle">Alt Başlık</label>
                                    <input type="text" class="form-control @error('company_subtitle') is-invalid @enderror" 
                                           id="company_subtitle" name="company_subtitle" 
                                           value="{{ old('company_subtitle', $settings->company_subtitle) }}" required>
                                    @error('company_subtitle')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Adres Bilgileri -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="address_line1">Adres 1. Satır</label>
                                    <input type="text" class="form-control @error('address_line1') is-invalid @enderror" 
                                           id="address_line1" name="address_line1" 
                                           value="{{ old('address_line1', $settings->address_line1) }}" required>
                                    @error('address_line1')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="address_line2">Adres 2. Satır</label>
                                    <input type="text" class="form-control @error('address_line2') is-invalid @enderror" 
                                           id="address_line2" name="address_line2" 
                                           value="{{ old('address_line2', $settings->address_line2) }}" required>
                                    @error('address_line2')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- İletişim Bilgileri -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="contact_center_title">İletişim Merkezi Başlığı</label>
                                    <input type="text" class="form-control @error('contact_center_title') is-invalid @enderror" 
                                           id="contact_center_title" name="contact_center_title" 
                                           value="{{ old('contact_center_title', $settings->contact_center_title) }}" required>
                                    @error('contact_center_title')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="contact_center_phone">İletişim Merkezi Telefonu</label>
                                    <input type="text" class="form-control @error('contact_center_phone') is-invalid @enderror" 
                                           id="contact_center_phone" name="contact_center_phone" 
                                           value="{{ old('contact_center_phone', $settings->contact_center_phone) }}" required>
                                    @error('contact_center_phone')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="whatsapp_title">WhatsApp Başlığı</label>
                                    <input type="text" class="form-control @error('whatsapp_title') is-invalid @enderror" 
                                           id="whatsapp_title" name="whatsapp_title" 
                                           value="{{ old('whatsapp_title', $settings->whatsapp_title) }}" required>
                                    @error('whatsapp_title')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="whatsapp_number">WhatsApp Numarası</label>
                                    <input type="text" class="form-control @error('whatsapp_number') is-invalid @enderror" 
                                           id="whatsapp_number" name="whatsapp_number" 
                                           value="{{ old('whatsapp_number', $settings->whatsapp_number) }}" required>
                                    @error('whatsapp_number')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email_title">E-posta Başlığı</label>
                                    <input type="text" class="form-control @error('email_title') is-invalid @enderror" 
                                           id="email_title" name="email_title" 
                                           value="{{ old('email_title', $settings->email_title) }}" required>
                                    @error('email_title')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email_address">E-posta Adresi</label>
                                    <input type="email" class="form-control @error('email_address') is-invalid @enderror" 
                                           id="email_address" name="email_address" 
                                           value="{{ old('email_address', $settings->email_address) }}" required>
                                    @error('email_address')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="kep_title">KEP Başlığı</label>
                                    <input type="text" class="form-control @error('kep_title') is-invalid @enderror" 
                                           id="kep_title" name="kep_title" 
                                           value="{{ old('kep_title', $settings->kep_title) }}" required>
                                    @error('kep_title')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="kep_address">KEP Adresi</label>
                                    <input type="text" class="form-control @error('kep_address') is-invalid @enderror" 
                                           id="kep_address" name="kep_address" 
                                           value="{{ old('kep_address', $settings->kep_address) }}" required>
                                    @error('kep_address')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Copyright Metinleri -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="copyright_left">Sol Copyright Metni</label>
                                    <textarea class="form-control @error('copyright_left') is-invalid @enderror" 
                                              id="copyright_left" name="copyright_left" rows="3" required>{{ old('copyright_left', $settings->copyright_left) }}</textarea>
                                    @error('copyright_left')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="copyright_right">Sağ Copyright Metni</label>
                                    <textarea class="form-control @error('copyright_right') is-invalid @enderror" 
                                              id="copyright_right" name="copyright_right" rows="3" required>{{ old('copyright_right', $settings->copyright_right) }}</textarea>
                                    @error('copyright_right')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Sosyal Medya Linkleri -->
                        <hr>
                        <h5><i class="fab fa-facebook mr-2"></i>Sosyal Medya Linkleri</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="facebook_url"><i class="fab fa-facebook-f mr-1"></i>Facebook URL</label>
                                    <input type="url" class="form-control @error('facebook_url') is-invalid @enderror" 
                                           id="facebook_url" name="facebook_url" 
                                           value="{{ old('facebook_url', $settings->facebook_url) }}"
                                           placeholder="https://facebook.com/cankayabelediyesi">
                                    @error('facebook_url')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="instagram_url"><i class="fab fa-instagram mr-1"></i>Instagram URL</label>
                                    <input type="url" class="form-control @error('instagram_url') is-invalid @enderror" 
                                           id="instagram_url" name="instagram_url" 
                                           value="{{ old('instagram_url', $settings->instagram_url) }}"
                                           placeholder="https://instagram.com/cankayabelediyesi">
                                    @error('instagram_url')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="twitter_url"><i class="fab fa-x-twitter mr-1"></i>Twitter/X URL</label>
                                    <input type="url" class="form-control @error('twitter_url') is-invalid @enderror" 
                                           id="twitter_url" name="twitter_url" 
                                           value="{{ old('twitter_url', $settings->twitter_url) }}"
                                           placeholder="https://twitter.com/cankayabelediye">
                                    @error('twitter_url')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="youtube_url"><i class="fab fa-youtube mr-1"></i>YouTube URL</label>
                                    <input type="url" class="form-control @error('youtube_url') is-invalid @enderror" 
                                           id="youtube_url" name="youtube_url" 
                                           value="{{ old('youtube_url', $settings->youtube_url) }}"
                                           placeholder="https://youtube.com/@cankayabelediyesi">
                                    @error('youtube_url')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="linkedin_url"><i class="fab fa-linkedin mr-1"></i>LinkedIn URL</label>
                                    <input type="url" class="form-control @error('linkedin_url') is-invalid @enderror" 
                                           id="linkedin_url" name="linkedin_url" 
                                           value="{{ old('linkedin_url', $settings->linkedin_url) }}"
                                           placeholder="https://linkedin.com/company/cankaya-belediyesi">
                                    @error('linkedin_url')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Kaydet
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Menü Özeti -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-list"></i> Menü Özeti
                    </h3>
                </div>
                <div class="card-body">
                    @forelse($menus as $menu)
                        <div class="d-flex justify-content-between align-items-center mb-2 p-2 border rounded">
                            <div>
                                <strong>{{ $menu->title }}</strong>
                                <br>
                                <small class="text-muted">{{ $menu->activeLinks->count() }} link</small>
                            </div>
                            <div>
                                @if($menu->is_active)
                                    <span class="badge badge-success">Aktif</span>
                                @else
                                    <span class="badge badge-secondary">Pasif</span>
                                @endif
                                <a href="{{ route('admin.footer.menus.links.index', $menu) }}" class="btn btn-sm btn-primary ml-1">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted">Henüz menü oluşturulmamış.</p>
                    @endforelse
                    
                    <div class="mt-3">
                        <a href="{{ route('admin.footer.menus.create') }}" class="btn btn-success btn-block">
                            <i class="fas fa-plus"></i> Yeni Menü Ekle
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <style>
        .img-thumbnail {
            max-height: 100px;
        }
    </style>
@stop

@section('js')
    <script>
        $(document).ready(function() {
            // Logo dosya seçimi
            $('.custom-file-input').on('change', function() {
                let fileName = $(this).val().split('\\').pop();
                $(this).siblings('.custom-file-label').addClass('selected').html(fileName);
            });

            // Logo silme işlemi
            $('[data-method="delete"]').on('click', function(e) {
                e.preventDefault();
                if (confirm('Logo silinsin mi?')) {
                    let form = $('<form>', {
                        'method': 'POST',
                        'action': $(this).attr('href')
                    });
                    form.append($('<input>', {
                        'type': 'hidden',
                        'name': '_token',
                        'value': $('meta[name="csrf-token"]').attr('content')
                    }));
                    form.append($('<input>', {
                        'type': 'hidden',
                        'name': '_method',
                        'value': 'DELETE'
                    }));
                    $('body').append(form);
                    form.submit();
                }
            });
        });
    </script>
@stop