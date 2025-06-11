# ROUTE DÜZELTMELERİ SONRASI ÇALIŞTIRMA KOMUTLARI

## 1. Route Cache Temizleme
```bash
php artisan route:clear
php artisan config:clear
php artisan cache:clear
```

## 2. Route Cache Yeniden Oluşturma
```bash
php artisan route:cache
php artisan config:cache
```

## 3. Autoload Yenileme
```bash
composer dump-autoload
```

## 4. Test Komutları
```bash
# Route listesini kontrol et
php artisan route:list

# Belirli route'ları test et
php artisan route:list --name=admin
php artisan route:list --name=filemanagersystem
```

## 5. Middleware Test
```bash
# Middleware listesini kontrol et
php artisan route:list --columns=uri,name,middleware
```

## UYARI: Bu komutları sırayla çalıştırın! 