<?php
// Resimlere doğrudan erişim için PHP proxy

$path = $_GET['path'] ?? null;

if (!$path) {
    header('HTTP/1.0 400 Bad Request');
    exit('Dosya yolu belirtilmedi');
}

// Güvenlik için dosya yolu kontrolü
$path = str_replace('../', '', $path);
$fullPath = __DIR__ . '/../storage/app/public/' . $path;

if (!file_exists($fullPath)) {
    header('HTTP/1.0 404 Not Found');
    exit('Dosya bulunamadı: ' . $path);
}

// Dosya türünü belirle
$mime = mime_content_type($fullPath);

// Resim dosyası değilse engelle
if (!strstr($mime, 'image/')) {
    header('HTTP/1.0 403 Forbidden');
    exit('Bu dosya türüne erişim izni yok');
}

// Content-Type başlığını ayarla
header('Content-Type: ' . $mime);
header('Content-Length: ' . filesize($fullPath));

// CORS başlıkları
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: *');

// Caching başlıkları
$lastModified = filemtime($fullPath);
$etag = md5_file($fullPath);
header("Last-Modified: " . gmdate("D, d M Y H:i:s", $lastModified) . " GMT");
header("Etag: \"$etag\"");
header('Cache-Control: public, max-age=31536000');
header('Expires: ' . gmdate('D, d M Y H:i:s', time() + 31536000) . ' GMT');

// Dosyayı gönder
readfile($fullPath);
exit; 