// TinyMCE Dosya Yönetim Sistemi Entegrasyonu
(function() {
    // TinyMCE eklenti tanımı
    tinymce.PluginManager.add('filemanagersystem', function(editor, url) {
        // Dosya seçici modalını aç
        editor.addButton('filemanagersystem', {
            icon: 'image',
            tooltip: 'Dosya Seç',
            onclick: function() {
                // Modalı aç
                $('#filePickerModal').modal('show');
                
                // Seçilen dosyaları işle
                window.tinymceFilePickerCallback = function(selectedFiles) {
                    // Seçilen dosyaları yükle
                    $.get('/filemanagersystem/media', { ids: selectedFiles }, function(files) {
                        files.forEach(function(file) {
                            // Dosya tipine göre içerik ekle
                            if (file.file_type.startsWith('image/')) {
                                // Resim için img etiketi
                                editor.insertContent(
                                    `<img src="${file.file_url}" alt="${file.file_name}" title="${file.file_name}" />`
                                );
                            } else {
                                // Diğer dosyalar için link
                                editor.insertContent(
                                    `<a href="${file.file_url}" target="_blank">${file.file_name}</a>`
                                );
                            }
                        });
                    });
                };
            }
        });

        // Dosya yükleme butonu
        editor.addButton('filemanagersystem_upload', {
            icon: 'upload',
            tooltip: 'Dosya Yükle',
            onclick: function() {
                // Dosya yükleme modalını aç
                $('#fileUploadModal').modal('show');
            }
        });
    });

    // TinyMCE başlatma
    tinymce.init({
        selector: '.tinymce-editor',
        plugins: 'filemanagersystem image link lists table code',
        toolbar: 'filemanagersystem filemanagersystem_upload | formatselect | bold italic | alignleft aligncenter alignright | bullist numlist | link image | code',
        height: 500,
        relative_urls: false,
        remove_script_host: false,
        convert_urls: true,
        file_picker_types: 'file image media',
        file_picker_callback: function(callback, value, meta) {
            // Dosya seçici modalını aç
            $('#filePickerModal').modal('show');
            
            // Seçilen dosyaları işle
            window.tinymceFilePickerCallback = function(selectedFiles) {
                // Seçilen dosyaları yükle
                $.get('/filemanagersystem/media', { ids: selectedFiles }, function(files) {
                    if (files.length > 0) {
                        const file = files[0];
                        callback(file.file_url, {
                            title: file.file_name,
                            alt: file.file_name
                        });
                    }
                });
            };
        }
    });

    // Dosya yükleme işlemi
    $('#fileUploadForm').on('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        $.ajax({
            url: '/filemanagersystem/media',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                // Yüklenen dosyayı editöre ekle
                if (response.file_type.startsWith('image/')) {
                    tinymce.activeEditor.insertContent(
                        `<img src="${response.file_url}" alt="${response.file_name}" title="${response.file_name}" />`
                    );
                } else {
                    tinymce.activeEditor.insertContent(
                        `<a href="${response.file_url}" target="_blank">${response.file_name}</a>`
                    );
                }
                
                // Modalı kapat
                $('#fileUploadModal').modal('hide');
                $(this).trigger('reset');
            },
            error: function(xhr) {
                alert('Dosya yüklenirken bir hata oluştu: ' + xhr.responseJSON.message);
            }
        });
    });
})(); 