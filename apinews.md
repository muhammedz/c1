# API News Dokümantasyonu

Bu döküman, sistemdeki haber (news) API entegrasyonunu açıklamaktadır.

## Genel Bilgiler

API erişimi için API Key gereklidir. Tüm istekler `api.key` middleware'i ile korunmaktadır.

API ana adresi: `/api/news`

## Kullanılabilir Endpoint'ler

### 1. Tüm Haberleri Listeleme

```
GET /api/news
```

**Parametreler:**
- `sort_by` (opsiyonel): Sıralama alanı, varsayılan "published_at"
- `sort_direction` (opsiyonel): Sıralama yönü, varsayılan "desc"
- `per_page` (opsiyonel): Sayfa başına haber sayısı, varsayılan 10

**Cevap Örneği:**
```json
{
    "data": [
        {
            "id": 1,
            "title": "Haber Başlığı",
            "slug": "haber-basligi",
            "summary": "Haber özeti",
            "content": "Haber içeriği...",
            "image": "/uploads/media/123",
            "is_headline": true,
            "is_featured": true,
            "view_count": 150,
            "status": true,
            "published_at": "2023-10-01 14:30:00",
            "category": {
                "id": 3,
                "name": "Teknoloji",
                "slug": "teknoloji"
            },
            "tags": [
                {
                    "id": 5,
                    "name": "Yazılım",
                    "slug": "yazilim"
                }
            ]
        }
        // Diğer haberler...
    ],
    "pagination": {
        "total": 50,
        "count": 10,
        "per_page": 10,
        "current_page": 1,
        "total_pages": 5,
        "links": {
            "first": "http://example.com/api/news?page=1",
            "last": "http://example.com/api/news?page=5",
            "prev": null,
            "next": "http://example.com/api/news?page=2"
        }
    }
}
```

### 2. Tek Bir Haberin Detayları

```
GET /api/news/{slug}
```

**Cevap Örneği:**
```json
{
    "id": 1,
    "title": "Haber Başlığı",
    "slug": "haber-basligi",
    "summary": "Haber özeti",
    "content": "Haber içeriği...",
    "image": "/uploads/media/123",
    "image_alt": "Görsel açıklaması",
    "image_title": "Görsel başlığı",
    "is_headline": true,
    "headline_order": 1,
    "is_featured": true,
    "view_count": 150,
    "status": true,
    "published_at": "2023-10-01 14:30:00",
    "category": {
        "id": 3,
        "name": "Teknoloji",
        "slug": "teknoloji"
    },
    "tags": [
        {
            "id": 5,
            "name": "Yazılım",
            "slug": "yazilim"
        }
    ],
    "gallery": [
        {
            "id": 10,
            "url": "/storage/uploads/gallery/image1.jpg",
            "alt": "Galeri görseli 1",
            "title": "Galeri başlığı 1"
        }
    ],
    "meta": {
        "title": "Meta başlık",
        "description": "Meta açıklama",
        "keywords": "anahtar, kelimeler"
    },
    "created_at": "2023-09-30 10:15:00",
    "updated_at": "2023-10-01 14:30:00"
}
```

### 3. Öne Çıkan Haberler

```
GET /api/news/featured
```

**Parametreler:**
- `limit` (opsiyonel): Getirilecek haber sayısı, varsayılan 6

**Cevap Örneği:** Tüm haberler listesi formatında döner (limit kadar kayıt içerir).

### 4. Haber Kategorileri

```
GET /api/news/categories
```

**Cevap Örneği:**
```json
[
    {
        "id": 1,
        "name": "Teknoloji",
        "slug": "teknoloji",
        "description": "Teknoloji haberleri",
        "icon": "fa-laptop",
        "image": "/storage/categories/teknoloji.jpg",
        "parent_id": null,
        "order": 1,
        "is_active": true,
        "news_count": 25,
        "meta": {
            "title": "Teknoloji Haberleri",
            "description": "En güncel teknoloji haberleri",
            "keywords": "teknoloji, yazılım, donanım"
        },
        "created_at": "2023-01-01 00:00:00",
        "updated_at": "2023-10-01 10:00:00"
    }
    // Diğer kategoriler...
]
```

### 5. Kategoriye Göre Haberler

```
GET /api/news/category/{category_id}
```

**Parametreler:**
- `sort_by` (opsiyonel): Sıralama alanı, varsayılan "published_at"
- `sort_direction` (opsiyonel): Sıralama yönü, varsayılan "desc"
- `per_page` (opsiyonel): Sayfa başına haber sayısı, varsayılan 10

**Cevap Örneği:** Tüm haberler listesi formatında döner (yalnızca belirtilen kategorideki haberler).

### 6. Haberlerde Arama

```
GET /api/news/search
```

**Parametreler:**
- `query` (zorunlu): Arama sorgusu
- `per_page` (opsiyonel): Sayfa başına haber sayısı, varsayılan 10

**Cevap Örneği:** Tüm haberler listesi formatında döner (arama sorgusuna uyan haberler).

## Hata Durumları

API'den gelebilecek genel hata durumları:

- `400 Bad Request`: İstek parametreleri eksik veya geçersiz
- `401 Unauthorized`: API Key doğrulaması başarısız
- `404 Not Found`: İstenen kaynak bulunamadı
- `429 Too Many Requests`: İstek limiti aşıldı
- `500 Internal Server Error`: Sunucu hatası

## Notlar

- Tüm tarihler "Y-m-d H:i:s" formatında döner
- Sayfalama bilgileri "pagination" anahtarı altında yer alır
- API anahtarı için yetkili kişilere başvurunuz
- Resim alanları, filemanagersystem tarafından yönetilen "/uploads/media/{id}" formatında döner
- Görsel URL'leri doğrudan kullanılabilir veya asset() fonksiyonu ile birleştirilebilir 