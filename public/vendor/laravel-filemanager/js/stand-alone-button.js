(function( $ ){

  $.fn.filemanager = function(type, options) {
    type = type || 'file';

    this.on('click', function(e) {
      var route_prefix = (options && options.prefix) ? options.prefix : '/filemanager';
      var target_input = $('#' + $(this).data('input'));
      var target_preview = $('#' + $(this).data('preview'));
      var onFileSelected = options.onFileSelected || null;
      
      window.open(route_prefix + '?type=' + type, 'FileManager', 'width=900,height=600');
      window.SetUrl = function (items) {
        
        // Temel URL'yi al (localhost:8000)
        var baseUrl = window.location.protocol + '//' + window.location.host;
        
        var file_path = items.map(function (item) {
          var url = item.url;
          
          // Eğer tam URL değilse, temel URL ile birleştir
          if (url && url.indexOf('http') !== 0) {
            // Storage yollarını değiştir
            if (url.indexOf('/storage/') !== -1) {
              url = url.replace('/storage/', '/uploads/');
            }
            
            // images yerine photos kullan
            if (url.indexOf('/images/') !== -1) {
              url = url.replace('/images/', '/photos/');
            }
            
            // Yolun başında / yoksa ekle
            if (url.indexOf('/') !== 0) {
              url = '/' + url;
            }
            
            // Tam URL oluştur
            url = baseUrl + url;
          }
          
          return url;
        }).join(',');

        // set the value of the desired input to image url
        target_input.val('').val(file_path).trigger('change');

        // clear previous preview
        target_preview.html('');

        // set or change the preview image src
        items.forEach(function (item) {
          var thumbUrl = item.thumb_url;
          
          // Eğer tam URL değilse, temel URL ile birleştir
          if (thumbUrl && thumbUrl.indexOf('http') !== 0) {
            // Storage yollarını değiştir
            if (thumbUrl.indexOf('/storage/') !== -1) {
              thumbUrl = thumbUrl.replace('/storage/', '/uploads/');
            }
            
            // images yerine photos kullan
            if (thumbUrl.indexOf('/images/') !== -1) {
              thumbUrl = thumbUrl.replace('/images/', '/photos/');
            }
            
            // Yolun başında / yoksa ekle
            if (thumbUrl.indexOf('/') !== 0) {
              thumbUrl = '/' + thumbUrl;
            }
            
            // Tam URL oluştur
            thumbUrl = baseUrl + thumbUrl;
          }
          
          target_preview.append(
            $('<img>').css('height', '5rem').attr('src', thumbUrl)
          );
        });

        // trigger change event
        target_preview.trigger('change');
        
        // Eğer özel bir callback tanımlanmışsa çağır
        if (typeof onFileSelected === 'function') {
          onFileSelected(file_path, items);
        }
      };
      
      return false;
    });
  }

})(jQuery);
