<?php
// Hata raporlama
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "<h1>Storage Erişim Testi</h1>";

// Dosya yollarını kontrol et
$storagePublicPath = storage_path('app/public');
$publicStoragePath = public_path('storage');

echo "<h2>Dosya Yolları:</h2>";
echo "Storage Public Path: " . $storagePublicPath . "<br>";
echo "Public Storage Path: " . $publicStoragePath . "<br>";

// Dizin kontrolü
echo "<h2>Dizin Kontrolü:</h2>";
echo "Storage Public Dizini Mevcut: " . (is_dir($storagePublicPath) ? 'Evet' : 'Hayır') . "<br>";
echo "Public Storage Dizini Mevcut: " . (is_dir($publicStoragePath) ? 'Evet' : 'Hayır') . "<br>";

// Photos dizini kontrolü
$photosPath = $storagePublicPath . '/photos/1';
$publicPhotosPath = $publicStoragePath . '/photos/1';

echo "Photos Dizini Mevcut: " . (is_dir($photosPath) ? 'Evet' : 'Hayır') . "<br>";
echo "Public Photos Dizini Mevcut: " . (is_dir($publicPhotosPath) ? 'Evet' : 'Hayır') . "<br>";

// Dosya kontrolü
$filePath = $photosPath . '/anitkabir.jpg';
$publicFilePath = $publicPhotosPath . '/anitkabir.jpg';

echo "<h2>Dosya Kontrolü:</h2>";
echo "Original File Path: " . $filePath . "<br>";
echo "Public File Path: " . $publicFilePath . "<br>";
echo "Dosya Mevcut (Storage): " . (file_exists($filePath) ? 'Evet' : 'Hayır') . "<br>";
echo "Dosya Mevcut (Public): " . (file_exists($publicFilePath) ? 'Evet' : 'Hayır') . "<br>";

// Dosya izinleri
if (file_exists($filePath)) {
    echo "Dosya İzinleri (Storage): " . decoct(fileperms($filePath) & 0777) . "<br>";
}
if (file_exists($publicFilePath)) {
    echo "Dosya İzinleri (Public): " . decoct(fileperms($publicFilePath) & 0777) . "<br>";
}

// Sembolik bağlantı kontrolü
echo "<h2>Sembolik Bağlantı Kontrolü:</h2>";
echo "Public Storage is Symlink: " . (is_link($publicStoragePath) ? 'Evet' : 'Hayır') . "<br>";
if (is_link($publicStoragePath)) {
    echo "Symlink Target: " . readlink($publicStoragePath) . "<br>";
}

// Storage URL oluşturma
echo "<h2>URL Test:</h2>";
echo "Storage URL: " . asset('storage/photos/1/anitkabir.jpg') . "<br>";

// Server bilgileri
echo "<h2>Server Bilgileri:</h2>";
echo "Server Software: " . $_SERVER['SERVER_SOFTWARE'] . "<br>";
echo "Document Root: " . $_SERVER['DOCUMENT_ROOT'] . "<br>";
echo "Script Filename: " . $_SERVER['SCRIPT_FILENAME'] . "<br>";

// Plesk özel değişkenleri
echo "<h2>Plesk Bilgileri:</h2>";
foreach ($_SERVER as $key => $value) {
    if (strpos($key, 'PLESK') !== false) {
        echo "$key: $value<br>";
    }
}

// Dosya erişim testi
echo "<h2>Dosya Okuma Testi:</h2>";
try {
    if (file_exists($filePath)) {
        $fileContent = file_get_contents($filePath);
        echo "Dosya Okunabildi: " . (is_readable($filePath) ? 'Evet' : 'Hayır') . "<br>";
        echo "Dosya Boyutu: " . filesize($filePath) . " bytes<br>";
    } else {
        echo "Dosya bulunamadı!<br>";
    }
} catch (Exception $e) {
    echo "Hata: " . $e->getMessage() . "<br>";
}

// .htaccess dosyasının varlığını kontrol et
echo "<h2>.htaccess Kontrolü:</h2>";
$htaccessPath = $publicStoragePath . '/.htaccess';
echo ".htaccess Mevcut: " . (file_exists($htaccessPath) ? 'Evet' : 'Hayır') . "<br>";
if (file_exists($htaccessPath) && is_readable($htaccessPath)) {
    echo "İçerik:<br><pre>" . htmlspecialchars(file_get_contents($htaccessPath)) . "</pre>";
} 