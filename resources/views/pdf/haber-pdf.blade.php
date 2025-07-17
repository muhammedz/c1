{{--
    Haber PDF Template
    
    Bu template, haber içeriğini PDF formatında gösterir.
    DomPDF kullanarak HTML'i PDF'e çevirir.
    
    Kullanım:
    Pdf::loadView('pdf.haber-pdf', compact('news'))
--}}

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $news->title }}</title>
    
    <style>
        /* PDF için özel CSS stilleri */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            line-height: 1.6;
            color: #333;
            background: #fff;
        }
        
        .container {
            max-width: 100%;
            margin: 0 auto;
            padding: 20px;
        }
        
        .header {
            border-bottom: 1px solid #ddd;
            padding-bottom: 15px;
            margin-bottom: 25px;
        }
        
        .text-center {
            text-align: center;
        }
        
        .text-sm {
            font-size: 11px;
        }
        
        .text-gray-600 {
            color: #666;
        }
        
        .mb-4 {
            margin-bottom: 10px;
        }
        
        .news-title {
            font-size: 20px;
            font-weight: bold;
            color: #333;
            margin-bottom: 15px;
            line-height: 1.4;
        }
        
        .news-meta {
            background: #f8f9fa;
            padding: 15px;
            border-left: 4px solid #0066cc;
            margin-bottom: 25px;
        }
        
        .news-meta-item {
            margin-bottom: 8px;
            font-size: 11px;
            color: #666;
        }
        
        .news-meta-item strong {
            color: #333;
        }
        
        .news-summary {
            background: #e3f2fd;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 25px;
            font-style: italic;
            border-left: 4px solid #2196f3;
        }
        
        .news-content {
            text-align: justify;
            line-height: 1.8;
        }
        
        .news-content p {
            margin-bottom: 15px;
        }
        
        .news-content h1, .news-content h2, .news-content h3 {
            color: #0066cc;
            margin-top: 20px;
            margin-bottom: 10px;
        }
        
        .news-content h1 { font-size: 18px; }
        .news-content h2 { font-size: 16px; }
        .news-content h3 { font-size: 14px; }
        
        .news-content ul, .news-content ol {
            margin-left: 20px;
            margin-bottom: 15px;
        }
        
        .news-content li {
            margin-bottom: 5px;
        }
        
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
        
        .page-break {
            page-break-before: always;
        }
        
        /* Tablo stilleri */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        
        table th, table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        
        table th {
            background-color: #f8f9fa;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="text-center">
                <p class="text-sm text-gray-600 mb-4">Orijinal haber: {{ request()->getSchemeAndHttpHost() }}/haberler/{{ $news->slug }}</p>
            </div>
        </div>
        
        <!-- Haber Başlığı -->
        <h1 class="news-title">{{ $news->title }}</h1>
        
        <!-- Haber Meta Bilgileri -->
        <div class="news-meta">
            <div class="news-meta-item">
                <strong>Yayın Tarihi:</strong> {{ $news->published_at ? $news->published_at->format('d.m.Y H:i') : $news->created_at->format('d.m.Y H:i') }}
            </div>
            
            @if($news->category)
            <div class="news-meta-item">
                <strong>Kategori:</strong> {{ $news->category->name }}
            </div>
            @endif
            
            @if($news->categories && $news->categories->count() > 0)
            <div class="news-meta-item">
                <strong>Kategoriler:</strong> 
                @foreach($news->categories as $category)
                    {{ $category->name }}@if(!$loop->last), @endif
                @endforeach
            </div>
            @endif
            
            @if($news->tags && $news->tags->count() > 0)
            <div class="news-meta-item">
                <strong>Etiketler:</strong> 
                @foreach($news->tags as $tag)
                    {{ $tag->name }}@if(!$loop->last), @endif
                @endforeach
            </div>
            @endif
            
            <div class="news-meta-item">
                <strong>PDF Oluşturma Tarihi:</strong> {{ now()->format('d.m.Y H:i') }}
            </div>
        </div>
        
        <!-- Haber Özeti -->
        @if($news->summary)
        <div class="news-summary">
            <strong>Özet:</strong> {{ $news->summary }}
        </div>
        @endif
        
        <!-- Haber İçeriği -->
        <div class="news-content">
            {!! $news->content !!}
        </div>
        
        <!-- Footer -->
        <div class="footer">
            <!-- Footer içeriği kaldırıldı -->
        </div>
    </div>
</body>
</html> 