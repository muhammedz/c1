<?php
// Tek amacı bir Storage görselini doğrudan göstermek

$path = $_GET['path'] ?? null;

if (empty($path)) {
    die('Dosya belirtilmedi');
}

// Güvenlik kontrolleri
$path = str_replace(['../', '..\\'], '', $path);
$fullPath = __DIR__ . '/../storage/app/public/' . $path;

if (!file_exists($fullPath)) {
    die('Dosya bulunamadı: ' . $path);
}

// Dosya türünü tespit et
$mime = mime_content_type($fullPath);
if (!$mime) {
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $fullPath);
    finfo_close($finfo);
}

// Sadece resim dosyalarına izin ver
if (!preg_match('#^image/(jpeg|jpg|png|gif|webp)#i', $mime)) {
    die('Geçersiz dosya türü');
}

// Cache başlıkları
header('Cache-Control: public, max-age=31536000');
header('Expires: ' . gmdate('D, d M Y H:i:s', time() + 31536000) . ' GMT');
header('Pragma: public');

// CORS başlıkları
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, OPTIONS');

// MIME tipi ve boyut
header('Content-Type: ' . $mime);
header('Content-Length: ' . filesize($fullPath));

// Dosyayı gönder
readfile($fullPath);
exit; 