# Hedef Kitle Modülü - Geliştirme Yol Haritası

## 1. Genel Bakış

Hedef Kitle modülü, içeriklerin (haber, hizmet, sayfa, proje) belirli hedef kitlelere göre kategorize edilmesini ve filtrelenmesini sağlayan bir yapıdır. Bu modül sayesinde kullanıcılar içerikleri hedef kitleye göre görüntüleyebilecek, yöneticiler ise içerik eklerken ilgili hedef kitleleri seçebilecektir.

## 2. Veritabanı Yapısı

### 2.1. Tablolar

#### `hedef_kitle` Tablosu
- `id`: Birincil anahtar
- `baslik`: Hedef kitle adı (ör. "Aile", "Gençler", "Yaşlılar")
- `slug`: URL-dostu isim (ör. "aile", "gencler", "yaslilar")
- `aciklama`: Hedef kitle hakkında açıklama
- `aktif`: Aktiflik durumu (boolean)
- `sira`: Görüntüleme sırası
- `olusturma_tarihi`: Oluşturulma tarihi
- `guncelleme_tarihi`: Son güncellenme tarihi

#### İlişki Tabloları
Her içerik türü için bir ilişki tablosu:

1. `haber_hedef_kitle` Tablosu
   - `haber_id`: Foreign key, haberler tablosuna referans
   - `hedef_kitle_id`: Foreign key, hedef_kitle tablosuna referans

2. `hizmet_hedef_kitle` Tablosu
   - `hizmet_id`: Foreign key, hizmetler tablosuna referans
   - `hedef_kitle_id`: Foreign key, hedef_kitle tablosuna referans

3. `sayfa_hedef_kitle` Tablosu 
   - `sayfa_id`: Foreign key, sayfalar tablosuna referans
   - `hedef_kitle_id`: Foreign key, hedef_kitle tablosuna referans

4. `proje_hedef_kitle` Tablosu
   - `proje_id`: Foreign key, projeler tablosuna referans
   - `hedef_kitle_id`: Foreign key, hedef_kitle tablosuna referans

### Veritabanı Yapısı Kontrol Listesi
- [ ] `hedef_kitle` tablosu oluşturuldu
- [ ] `haber_hedef_kitle` ilişki tablosu oluşturuldu
- [ ] `hizmet_hedef_kitle` ilişki tablosu oluşturuldu
- [ ] `sayfa_hedef_kitle` ilişki tablosu oluşturuldu
- [ ] `proje_hedef_kitle` ilişki tablosu oluşturuldu

## 3. Backend Geliştirme

### 3.1. Model Oluşturma
- HedefKitle modeli oluşturulacak
- İlgili içerik modelleri (Haber, Hizmet, Sayfa, Proje) ile many-to-many ilişkiler kurulacak

### 3.2. API Endpoint'leri
- `GET /api/hedef-kitleler`: Tüm hedef kitleleri listeler
- `GET /api/hedef-kitleler/{slug}`: Belirli bir hedef kitleyi getirir
- `GET /api/hedef-kitleler/{slug}/haberler`: Belirli hedef kitleye ait haberleri listeler
- `GET /api/hedef-kitleler/{slug}/hizmetler`: Belirli hedef kitleye ait hizmetleri listeler
- `GET /api/hedef-kitleler/{slug}/sayfalar`: Belirli hedef kitleye ait sayfaları listeler
- `GET /api/hedef-kitleler/{slug}/projeler`: Belirli hedef kitleye ait projeleri listeler

### 3.3. Admin Paneli İşlemleri
- Hedef Kitle ekleme/düzenleme/silme işlemleri için admin panel sayfaları
- İçerik ekleme/düzenleme formlarına hedef kitle seçimi alanı eklenmesi
  - Haber ekleme formuna hedef kitle seçimi
  - Hizmet ekleme formuna hedef kitle seçimi
  - Sayfa ekleme formuna hedef kitle seçimi
  - Proje ekleme formuna hedef kitle seçimi

### Backend Geliştirme Kontrol Listesi
- [ ] HedefKitle modeli oluşturuldu
- [ ] Haber modeli ile many-to-many ilişki kuruldu
- [ ] Hizmet modeli ile many-to-many ilişki kuruldu
- [ ] Sayfa modeli ile many-to-many ilişki kuruldu
- [ ] Proje modeli ile many-to-many ilişki kuruldu
- [ ] `GET /api/hedef-kitleler` endpoint'i oluşturuldu
- [ ] `GET /api/hedef-kitleler/{slug}` endpoint'i oluşturuldu
- [ ] `GET /api/hedef-kitleler/{slug}/haberler` endpoint'i oluşturuldu
- [ ] `GET /api/hedef-kitleler/{slug}/hizmetler` endpoint'i oluşturuldu
- [ ] `GET /api/hedef-kitleler/{slug}/sayfalar` endpoint'i oluşturuldu
- [ ] `GET /api/hedef-kitleler/{slug}/projeler` endpoint'i oluşturuldu
- [ ] Admin paneli hedef kitle yönetim sayfası oluşturuldu
- [ ] Haber ekleme formuna hedef kitle seçimi eklendi
- [ ] Hizmet ekleme formuna hedef kitle seçimi eklendi
- [ ] Sayfa ekleme formuna hedef kitle seçimi eklendi
- [ ] Proje ekleme formuna hedef kitle seçimi eklendi

## 4. Frontend Geliştirme

### 4.1. Hedef Kitle Sayfası (/{slug})
- Belirli bir hedef kitleye ait tüm içeriklerin listelendiği sayfa
- Bölümler halinde haberler, hizmetler, sayfalar ve projeler gösterilecek
- Filtreleme ve sıralama özellikleri

### 4.2. İçerik Sayfalarında Filtreleme
- Haber listesi sayfasında hedef kitleye göre filtreleme seçeneği
- Hizmet listesi sayfasında hedef kitleye göre filtreleme seçeneği
- Sayfa listesi sayfasında hedef kitleye göre filtreleme seçeneği
- Proje listesi sayfasında hedef kitleye göre filtreleme seçeneği

### 4.3. Navigasyon ve UI
- Ana menüde hedef kitle bölümü
- İçerik detay sayfalarında ilgili hedef kitlelerin gösterilmesi
- Hedef kitle etiketleri (kategori görünümünde)
- Hedef kitle sayfalarında SEO optimizasyonu

### Frontend Geliştirme Kontrol Listesi
- [ ] Hedef kitle detay sayfası (/{slug}) tasarımı yapıldı
- [ ] Hedef kitle detay sayfası içerik listesi bileşenleri oluşturuldu
- [ ] Hedef kitle detay sayfasında filtreleme ve sıralama özellikleri eklendi
- [ ] Haber listesi sayfasında hedef kitle filtresi eklendi
- [ ] Hizmet listesi sayfasında hedef kitle filtresi eklendi
- [ ] Sayfa listesi sayfasında hedef kitle filtresi eklendi
- [ ] Proje listesi sayfasında hedef kitle filtresi eklendi
- [ ] Ana menüye hedef kitle bölümü eklendi
- [ ] İçerik detay sayfalarında ilgili hedef kitleler gösterildi
- [ ] Hedef kitle etiketleri tasarımı yapıldı
- [ ] Hedef kitle sayfaları SEO optimizasyonu yapıldı

## 5. Entegrasyon ve Test

### 5.1. İçerik Ekleme Test Senaryoları
- Yeni haber eklerken hedef kitle seçimi testi
- Yeni hizmet eklerken hedef kitle seçimi testi
- Yeni sayfa eklerken hedef kitle seçimi testi
- Yeni proje eklerken hedef kitle seçimi testi

### 5.2. Filtreleme Test Senaryoları
- Hedef kitle sayfasının doğru içerikleri listelemesi
- İçerik listesi sayfalarında filtrelemenin doğru çalışması
- Sayfalama ve filtreleme kombinasyonunun test edilmesi

### Test Kontrol Listesi
- [ ] Haber ekleme formunda hedef kitle seçimi test edildi
- [ ] Hizmet ekleme formunda hedef kitle seçimi test edildi
- [ ] Sayfa ekleme formunda hedef kitle seçimi test edildi
- [ ] Proje ekleme formunda hedef kitle seçimi test edildi
- [ ] Hedef kitle sayfası içerik listeleme testi yapıldı
- [ ] Haber sayfasında hedef kitle filtresi test edildi
- [ ] Hizmet sayfasında hedef kitle filtresi test edildi
- [ ] Sayfa sayfasında hedef kitle filtresi test edildi
- [ ] Proje sayfasında hedef kitle filtresi test edildi
- [ ] Sayfalama ve filtreleme kombinasyonu test edildi

## 6. Geliştirme Aşamaları

### Aşama 1: Veritabanı ve Model Yapısı (1 hafta)
- Veritabanı tablolarının oluşturulması
- Model sınıflarının oluşturulması
- Temel CRUD işlemlerinin gerçekleştirilmesi

### Aşama 2: Admin Panel Entegrasyonu (1 hafta)
- Hedef Kitle yönetim arayüzü
- İçerik formlarına hedef kitle seçim alanlarının eklenmesi
- Admin panel testleri

### Aşama 3: API ve Frontend Geliştirme (2 hafta)
- API endpoint'lerinin oluşturulması
- Hedef kitle detay sayfası geliştirme
- İçerik listesi sayfalarına filtreleme özelliği ekleme

### Aşama 4: Test ve Optimizasyon (1 hafta)
- Kapsamlı test senaryolarının uygulanması
- Performans optimizasyonu
- SEO ayarları

### Geliştirme Aşamaları Kontrol Listesi
- [ ] Aşama 1: Veritabanı ve Model Yapısı tamamlandı
- [ ] Aşama 2: Admin Panel Entegrasyonu tamamlandı
- [ ] Aşama 3: API ve Frontend Geliştirme tamamlandı
- [ ] Aşama 4: Test ve Optimizasyon tamamlandı

## 7. Notlar ve Öneriler

- Hedef kitle seçiminin zorunlu veya isteğe bağlı olması kararlaştırılmalı
- İçerik türlerine göre hedef kitle önem derecesi belirlenebilir
- Hedef kitlelerin birbiriyle ilişkili olması durumu (üst-alt hedef kitle) için ek geliştirme yapılabilir
- Kullanıcı bazlı hedef kitle özelleştirmesi için personalizasyon özelliği eklenebilir 

### Notlar ve Kararlar Kontrol Listesi
- [ ] Hedef kitle seçiminin zorunlu/isteğe bağlı olma durumu kararlaştırıldı
- [ ] İçerik türlerine göre hedef kitle önem derecesi belirlendi
- [ ] Hedef kitlelerin hiyerarşik yapısı kararlaştırıldı
- [ ] Personalizasyon özelliği eklenip eklenmeyeceği kararlaştırıldı 