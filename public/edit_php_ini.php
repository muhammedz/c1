<?php
// PHP.ini dosyasının konumunu al
$phpIniPath = php_ini_loaded_file();
echo "PHP.ini dosyası: " . $phpIniPath . "<br>";

if (!$phpIniPath) {
    die("PHP.ini dosyası bulunamadı!");
}

// Dosyayı oku
$phpIniContent = file_get_contents($phpIniPath);
if (!$phpIniContent) {
    die("PHP.ini dosyası okunamadı!");
}

echo "PHP.ini dosyası okundu.<br>";

// Değişiklikleri yap
$phpIniContent = preg_replace('/post_max_size\s*=\s*(\d+)M/i', 'post_max_size = 10M', $phpIniContent);
$phpIniContent = preg_replace('/upload_max_filesize\s*=\s*(\d+)M/i', 'upload_max_filesize = 10M', $phpIniContent);

// Geçici olarak yeni bir php.ini dosyası oluştur
$tempIniFile = __DIR__ . '/temp_php.ini';
file_put_contents($tempIniFile, $phpIniContent);

echo "Yeni PHP.ini dosyası oluşturuldu: " . $tempIniFile . "<br>";
echo "Bu dosyayı orijinal PHP.ini dosyasıyla değiştirin ve sunucuyu yeniden başlatın.<br>";
echo "<hr>";
echo "Değişiklikleri görmek için: <a href='check_upload_config.php'>Ayarları Kontrol Et</a>";
?> 