<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Debug - Hata Detayları</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f8f9fa;
            color: #333;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .error-header {
            background: #dc3545;
            color: white;
            padding: 20px;
            margin: -30px -30px 30px -30px;
            border-radius: 8px 8px 0 0;
        }
        .error-title {
            font-size: 24px;
            margin: 0 0 10px 0;
        }
        .error-subtitle {
            font-size: 16px;
            opacity: 0.9;
            margin: 0;
        }
        .detail-section {
            margin-bottom: 30px;
        }
        .detail-title {
            font-size: 18px;
            font-weight: bold;
            color: #495057;
            margin-bottom: 10px;
            padding-bottom: 5px;
            border-bottom: 2px solid #e9ecef;
        }
        .detail-content {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            border-left: 4px solid #007bff;
            font-family: 'Courier New', monospace;
            white-space: pre-wrap;
            word-break: break-all;
        }
        .trace-content {
            max-height: 400px;
            overflow-y: auto;
            font-size: 12px;
            line-height: 1.4;
        }
        .back-button {
            display: inline-block;
            background: #007bff;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
        }
        .back-button:hover {
            background: #0056b3;
            color: white;
            text-decoration: none;
        }
        .timestamp {
            color: #6c757d;
            font-size: 14px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="error-header">
            <h1 class="error-title">🚨 Hata Detayları</h1>
            <p class="error-subtitle">{{ $message ?? 'Bir hata oluştu' }}</p>
        </div>

        <div class="timestamp">
            <strong>Zaman:</strong> {{ now()->format('d.m.Y H:i:s') }}
        </div>

        @if(isset($details) && is_array($details))
            @foreach($details as $title => $content)
                <div class="detail-section">
                    <div class="detail-title">{{ $title }}</div>
                    <div class="detail-content {{ $title === 'Trace' ? 'trace-content' : '' }}">{{ $content }}</div>
                </div>
            @endforeach
        @endif

        @if(isset($error))
            <div class="detail-section">
                <div class="detail-title">Exception Sınıfı</div>
                <div class="detail-content">{{ get_class($error) }}</div>
            </div>

            @if($error->getPrevious())
                <div class="detail-section">
                    <div class="detail-title">Önceki Hata</div>
                    <div class="detail-content">{{ $error->getPrevious()->getMessage() }}</div>
                </div>
            @endif
        @endif

        <div class="detail-section">
            <div class="detail-title">Sistem Bilgileri</div>
            <div class="detail-content">
PHP Versiyonu: {{ PHP_VERSION }}
Laravel Versiyonu: {{ app()->version() }}
Çalışma Ortamı: {{ app()->environment() }}
Debug Modu: {{ config('app.debug') ? 'Açık' : 'Kapalı' }}
            </div>
        </div>

        <a href="javascript:history.back()" class="back-button">← Geri Dön</a>
        <a href="{{ route('admin.events.index') }}" class="back-button">📋 Etkinlikler Listesi</a>
    </div>
</body>
</html> 