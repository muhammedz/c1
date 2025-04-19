<?php
// PHP yükleme limitlerini kontrol etmek için
echo "PHP Yükleme Limitleri:<br>";
echo "post_max_size: " . ini_get('post_max_size') . "<br>";
echo "upload_max_filesize: " . ini_get('upload_max_filesize') . "<br>";
echo "memory_limit: " . ini_get('memory_limit') . "<br>";
echo "max_file_uploads: " . ini_get('max_file_uploads') . "<br>";

// Kullanılan PHP.ini dosyası
echo "<br>PHP.ini dosyası: " . php_ini_loaded_file() . "<br>";

// Tüm ayarları kontrol et
echo "<br>Tüm Ayarlar:<br>";
echo "<pre>";
print_r(ini_get_all());
echo "</pre>";
?> 