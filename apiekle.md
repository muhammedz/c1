# News Modülü API Entegrasyon Planı

Bu doküman, mevcut News (Haber) modülünden dışarıya API sunmak için izlenecek adımları detaylandırır.

## 1. API Controller Oluşturma ✅
- [ ] `app/Http/Controllers/Api/NewsApiController.php` dosyasını oluştur
- [ ] Controller içinde aşağıdaki metodları ekle:
  - [ ] `index()` - Tüm haberleri listeler
  - [ ] `show($slug)` - Belirli bir haberin detaylarını gösterir
  - [ ] `getFeatured()` - Öne çıkan haberleri listeler
  - [ ] `getCategories()` - Haber kategorilerini listeler
  - [ ] `getByCategory($category_id)` - Kategoriye göre haberleri listeler
  - [ ] `search($query)` - Haberlerde arama yapar

## 2. API Resource Sınıfları Oluşturma ✅
- [ ] `app/Http/Resources/NewsResource.php` - Tek bir haber için kaynak sınıfı
- [ ] `app/Http/Resources/NewsCollection.php` - Haber koleksiyonu için kaynak sınıfı
- [ ] `app/Http/Resources/NewsCategoryResource.php` - Haber kategorileri için kaynak sınıfı

## 3. API Route'larını Tanımlama ✅
- [ ] `routes/api.php` dosyasına aşağıdaki route'ları ekle:
  - [ ] `GET /api/news` - Tüm haberleri listeler
  - [ ] `GET /api/news/{slug}` - Belirli bir haberin detaylarını gösterir
  - [ ] `GET /api/news/featured` - Öne çıkan haberleri listeler
  - [ ] `GET /api/news/categories` - Tüm haber kategorilerini listeler
  - [ ] `GET /api/news/category/{id}` - Belirli bir kategorideki haberleri listeler
  - [ ] `GET /api/news/search` - Haberlerde arama yapar

## 4. API Güvenlik Mekanizması Kurulumu ✅
- [ ] Sanctum konfigürasyonunu güncelle
- [ ] `app/Http/Middleware/ApiKeyMiddleware.php` oluştur
- [ ] Middleware'i route'lara uygula
- [ ] Token oluşturma ve yönetimi için `ApiTokenController` ekle

## 5. API Dokümantasyonu Oluşturma ✅
- [ ] `api-dokuman.html` dosyasını oluştur
- [ ] Tüm endpoint'leri açıkla
- [ ] Örnek istek ve yanıtları belirt
- [ ] Hata kodlarını ve mesajlarını belirt
- [ ] Kimlik doğrulama yöntemlerini açıkla

## 6. Test ve Doğrulama ✅
- [ ] Postman koleksiyonu oluştur
- [ ] Tüm endpoint'leri test et
- [ ] Hata durumlarını test et
- [ ] Canlı ortama geçmeden önce güvenlik kontrollerini yap

## 7. Canlıya Alma ve Bakım ✅
- [ ] API versiyonlama stratejisini belirle 
- [ ] Rate limiting uygula
- [ ] Monitör ve loglama stratejisini belirle
- [ ] API kullanım istatistiklerini takip etme sistemi ekle

## 8. İleri Seviye Özellikler (Opsiyonel) ✅
- [ ] OAuth2 entegrasyonu
- [ ] GraphQL desteği
- [ ] Web kancaları (webhooks) ekleme
- [ ] Real-time API özellikleri (websockets)

## Kaynaklar ve Notlar

- Laravel Sanctum: https://laravel.com/docs/10.x/sanctum
- API Resource: https://laravel.com/docs/10.x/eloquent-resources
- API uç noktaları: `/api/news/*`
- Güvenlik: API Key veya Bearer Token kullanılacak

---

*Not: Yapılan her adımın yanındaki kutucuğu işaretleyin. Tüm adımlar tamamlandığında ilgili ana başlığın sağındaki kutucuğu işaretleyin.* 