<?php
// Sistem bilgilerini görüntüleyen bir test sayfası
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Storage Erişim Testi</h1>";

// Dosya yollarını kontrol et
echo "<h2>Dosya Yolları:</h2>";
echo "Çalışılan dizin: " . __DIR__ . "<br>";
echo "Public storage dizini: " . __DIR__ . "/storage" . "<br>";
$storageExists = is_dir(__DIR__ . "/storage") ? "Evet" : "Hayır";
echo "Public storage klasörü var mı? " . $storageExists . "<br>";

// Dosya kontrolleri
$testFile = __DIR__ . "/storage/photos/1/baskan.jpg";
$testFileExists = file_exists($testFile) ? "Evet" : "Hayır";
echo "baskan.jpg dosyası var mı? " . $testFileExists . "<br>";

if (file_exists($testFile)) {
    echo "Dosya boyutu: " . filesize($testFile) . " bytes<br>";
    echo "Dosya izinleri: " . substr(sprintf('%o', fileperms($testFile)), -4) . "<br>";
    $fileInfo = pathinfo($testFile);
    echo "Dosya adı: " . $fileInfo['basename'] . "<br>";
    echo "Dosya uzantısı: " . $fileInfo['extension'] . "<br>";
}

// Dizin izinleri
$photosDir = __DIR__ . "/storage/photos/1";
if (is_dir($photosDir)) {
    echo "Photos dizin izinleri: " . substr(sprintf('%o', fileperms($photosDir)), -4) . "<br>";
}

// Sembolik bağlantı kontrolü
$target = readlink(__DIR__ . "/storage");
echo "Sembolik bağlantı hedefi: " . ($target ? $target : "Bağlantı yok") . "<br>";

// URL testi
echo "<h2>URL Testi:</h2>";
$baseUrl = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
$baseUrl .= "://" . $_SERVER['HTTP_HOST'];
echo "Base URL: " . $baseUrl . "<br>";

$storagePath = "/storage/photos/1/baskan.jpg";
$storageUrl = $baseUrl . $storagePath;
echo "Storage URL: " . $storageUrl . "<br>";

// Resmi göstermeyi deneyelim
echo "<h2>Resim Testi:</h2>";
echo "<img src='$storagePath' style='max-width:300px;border:1px solid #ddd;'><br>";
echo "<p>Eğer yukarıda resim görünüyorsa, erişim çalışıyor.</p>";

// CURL ile dosya erişimi dene
echo "<h2>CURL Testi:</h2>";
if (function_exists('curl_init')) {
    $ch = curl_init($storageUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, true);
    curl_setopt($ch, CURLOPT_NOBODY, true);
    curl_exec($ch);
    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    echo "HTTP Durumu: " . $status . "<br>";
    if ($status == 200) {
        echo "CURL erişimi başarılı!<br>";
    } else {
        echo "CURL erişimi başarısız!<br>";
    }
} else {
    echo "CURL bu sunucuda etkinleştirilmemiş.<br>";
}

// Laravel'in asset fonksiyonunu kullanarak test et (Laravel framework olmadan)
echo "<h2>Farklı URL Yapıları Testi:</h2>";
$assetUrl = $baseUrl . "/storage/photos/1/baskan.jpg";
echo "Normal URL: <a href='$assetUrl' target='_blank'>$assetUrl</a><br>";
echo "<img src='$assetUrl' style='max-width:100px;border:1px solid #ddd;'><br>";

// Farklı erişim yollarını test et
$directUrl = $baseUrl . "/public/storage/photos/1/baskan.jpg";
echo "Public ile URL: <a href='$directUrl' target='_blank'>$directUrl</a><br>";
echo "<img src='$directUrl' style='max-width:100px;border:1px solid #ddd;'><br>";

$relativeUrl = "storage/photos/1/baskan.jpg";
echo "Relative URL: <a href='$relativeUrl' target='_blank'>$relativeUrl</a><br>";
echo "<img src='$relativeUrl' style='max-width:100px;border:1px solid #ddd;'><br>";
?> 