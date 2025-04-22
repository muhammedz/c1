# Haber API Dokümantasyonu

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
            "image": "https://cankaya.epoxsoft.net.tr",
            "image_alt": "Görsel alt metni",
            "image_title": "Görsel başlığı",
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
    ],
    "pagination": {
        "total": 50,
        "count": 10,
        "per_page": 10,
        "current_page": 1,
        "total_pages": 5,
        "links": {
            "first": "https://cankaya.epoxsoft.net.tr/api/news?page=1",
            "last": "https://cankaya.epoxsoft.net.tr/api/news?page=5",
            "prev": null,
            "next": "https://cankaya.epoxsoft.net.tr/api/news?page=2"
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
    "image": "https://cankaya.epoxsoft.net.tr",
    "image_alt": "Görsel alt metni",
    "image_title": "Görsel başlığı",
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
    ],
    "gallery": [
        {
            "id": 10,
            "url": "https://cankaya.epoxsoft.net.tr",
            "alt": "Galeri görsel açıklaması",
            "title": "Galeri görsel başlığı"
        }
    ],
    "meta": {
        "title": "Haber meta başlığı",
        "description": "Haber meta açıklaması",
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

### 4. Haber Kategorileri

```
GET /api/news/categories
```

### 5. Kategoriye Göre Haberler

```
GET /api/news/category/{category_id}
```

**Parametreler:**
- `sort_by` (opsiyonel): Sıralama alanı, varsayılan "published_at"
- `sort_direction` (opsiyonel): Sıralama yönü, varsayılan "desc"
- `per_page` (opsiyonel): Sayfa başına haber sayısı, varsayılan 10

### 6. Haberlerde Arama

```
GET /api/news/search
```

**Parametreler:**
- `query` (zorunlu): Arama sorgusu
- `per_page` (opsiyonel): Sayfa başına haber sayısı, varsayılan 10

## Cevap Alanları Açıklamaları

### Haber Nesnesi Alanları
- `id`: Haber ID'si
- `title`: Haber başlığı
- `slug`: Haber URL slug'ı
- `summary`: Haber özeti
- `content`: Haber içeriği (HTML formatında)
- `image`: Haber ana görseli URL'i (https://cankaya.epoxsoft.net.tr)
- `image_alt`: Haber ana görseli alt metni (SEO için)
- `image_title`: Haber ana görseli başlığı
- `is_headline`: Manşet haberi olup olmadığı (true ise ⭐ Manşet Haber)
- `is_featured`: Öne çıkan haber olup olmadığı
- `view_count`: Görüntülenme sayısı
- `status`: Haber durumu (published, draft vs.)
- `published_at`: Yayınlanma tarihi
- `category`: Haber kategorisi
- `tags`: Habere ait etiketler
- `gallery`: Haber galerisi (varsa)
- `meta`: SEO meta alanları
- `created_at`: Oluşturulma tarihi
- `updated_at`: Güncellenme tarihi

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
- Resim alanları, filemanagersystem tarafından yönetilen "https://cankaya.epoxsoft.net.tr" formatında döner
- Haber görsel URL'i (`image`) iki farklı kaynaktan gelebilir:
  - Öncelikle `filemanagersystem_image_url` değeri kontrol edilir
  - Bu değer yoksa normal `image` alanı kullanılır
  - Her iki alan da boşsa null değeri döner 