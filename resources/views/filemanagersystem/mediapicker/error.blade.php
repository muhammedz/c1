<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medya Seçici - Hata</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            padding: 20px;
        }
        .error-container {
            max-width: 600px;
            margin: 50px auto;
            padding: 30px;
            background-color: white;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .error-title {
            color: #dc3545;
            margin-bottom: 20px;
        }
        .error-message {
            margin-bottom: 20px;
        }
        .refresh-btn {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="error-container">
            <h3 class="error-title">
                <i class="fas fa-exclamation-triangle"></i> 
                Medya Seçici Yüklenirken Hata Oluştu
            </h3>
            
            <div class="error-message">
                <p>{{ $error ?? 'Beklenmeyen bir hata oluştu. Lütfen tekrar deneyin.' }}</p>
            </div>
            
            <div class="error-details mt-4">
                <p>Aşağıdaki adımları deneyebilirsiniz:</p>
                <ul>
                    <li>Sayfayı yenileyin</li>
                    <li>Tarayıcı önbelleğini temizleyin</li>
                    <li>Farklı bir tarayıcı kullanın</li>
                    <li>Sistem yöneticisiyle iletişime geçin</li>
                </ul>
            </div>
            
            <div class="refresh-btn">
                <button type="button" class="btn btn-primary" onclick="window.location.reload()">
                    <i class="fas fa-sync"></i> Sayfayı Yenile
                </button>
                <button type="button" class="btn btn-secondary" onclick="window.parent.closeMediaPicker()">
                    <i class="fas fa-times"></i> Kapat
                </button>
            </div>
        </div>
    </div>

    <script>
        function closeMediaPicker() {
            try {
                window.parent.postMessage({
                    type: 'mediapickerError',
                    message: '{{ $error ?? "Beklenmeyen bir hata oluştu" }}'
                }, '*');
            } catch (e) {
                console.error("Hata bildirme hatası:", e);
            }
            
            // Modal kapatma denemesi
            try {
                window.parent.$('#mediapickerModal').modal('hide');
            } catch (e) {
                console.error("Modal kapatma hatası:", e);
            }
        }
    </script>
</body>
</html> 