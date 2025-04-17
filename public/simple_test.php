<?php
// Hata gösterimi açık
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "<h1>Basit Dosya Erişim Testi</h1>";

// Yollar
$root = $_SERVER['DOCUMENT_ROOT'];
$storagePath = $root . '/storage';
$targetPath = $storagePath . '/photos/1/anitkabir.jpg';

echo "Document Root: $root <br>";
echo "Storage Path: $storagePath <br>";
echo "Target Path: $targetPath <br><br>";

// Dizin Kontrolü
echo "Storage Dizini Var mı? " . (is_dir($storagePath) ? 'Evet' : 'Hayır') . "<br>";
echo "Storage Dizini Okunabilir mi? " . (is_readable($storagePath) ? 'Evet' : 'Hayır') . "<br><br>";

// Doğrudan dosya kontrolü
echo "Hedef Dosya Var mı? " . (file_exists($targetPath) ? 'Evet' : 'Hayır') . "<br>";

if(file_exists($targetPath)) {
    echo "Dosya Boyutu: " . filesize($targetPath) . " bytes<br>";
    echo "Dosya Okunabilir mi? " . (is_readable($targetPath) ? 'Evet' : 'Hayır') . "<br>";
    echo "Dosya İzinleri: " . decoct(fileperms($targetPath) & 0777) . "<br><br>";
    
    // Dosyayı görüntülemeye çalış
    echo "<h2>Resim (HTML img tag ile):</h2>";
    echo "<img src='/storage/photos/1/anitkabir.jpg' style='max-width:300px'><br>";
    
    // Base64 ile dosya içeriğini gösterme
    echo "<h2>Resim (Base64 ile):</h2>";
    $imageData = base64_encode(file_get_contents($targetPath));
    echo "<img src='data:image/jpeg;base64,$imageData' style='max-width:300px'><br>";
} else {
    echo "<br>Dosya bulunamadı. Alternatif yolları deneyeceğim:<br>";
    
    // Alternatif yolları dene
    $alt1 = $root . '/../storage/app/public/photos/1/anitkabir.jpg';
    $alt2 = __DIR__ . '/storage/photos/1/anitkabir.jpg';
    $alt3 = dirname(__DIR__) . '/storage/app/public/photos/1/anitkabir.jpg';
    
    echo "Alt Yol 1 ($alt1): " . (file_exists($alt1) ? 'Dosya var' : 'Dosya yok') . "<br>";
    echo "Alt Yol 2 ($alt2): " . (file_exists($alt2) ? 'Dosya var' : 'Dosya yok') . "<br>";
    echo "Alt Yol 3 ($alt3): " . (file_exists($alt3) ? 'Dosya var' : 'Dosya yok') . "<br>";
}

// Dizin listesi
echo "<h2>Storage Dizin İçeriği:</h2>";
if(is_dir($storagePath) && is_readable($storagePath)) {
    $files = scandir($storagePath);
    echo "<pre>";
    print_r($files);
    echo "</pre>";
    
    // Storage/photos dizinini kontrol et
    $photosDir = $storagePath . '/photos';
    if(is_dir($photosDir)) {
        echo "<h3>Photos Dizin İçeriği:</h3>";
        $photoFiles = scandir($photosDir);
        echo "<pre>";
        print_r($photoFiles);
        echo "</pre>";
        
        // Storage/photos/1 dizinini kontrol et
        $subDir = $photosDir . '/1';
        if(is_dir($subDir)) {
            echo "<h3>Photos/1 Dizin İçeriği:</h3>";
            $subFiles = scandir($subDir);
            echo "<pre>";
            print_r($subFiles);
            echo "</pre>";
        }
    }
} else {
    echo "Storage dizini okunamıyor veya mevcut değil.<br>";
}

echo "<h2>Test Bitti</h2>"; 