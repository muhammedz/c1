<?php
// Eski değerleri göster
echo "ESKİ DEĞERLER:<br>";
echo "post_max_size: " . ini_get('post_max_size') . "<br>";
echo "upload_max_filesize: " . ini_get('upload_max_filesize') . "<br>";

// Runtime'da ayarları değiştirmeyi dene
ini_set('post_max_size', '10M');
ini_set('upload_max_filesize', '10M');

// Yeni değerleri göster
echo "<br>YENİ DEĞERLER:<br>";
echo "post_max_size: " . ini_get('post_max_size') . "<br>";
echo "upload_max_filesize: " . ini_get('upload_max_filesize') . "<br>";

// Bu ayarların hangi kapsamda değiştirilebileceğini göster
echo "<br>DEĞİŞTİRİLEBİLİRLİK MODLARI:<br>";
$postMaxSizeInfo = ini_get_all('post_max_size');
$uploadMaxFilesizeInfo = ini_get_all('upload_max_filesize');

echo "post_max_size modifiable: " . ($postMaxSizeInfo['post_max_size']['access'] ?? 'bilgi yok') . "<br>";
echo "upload_max_filesize modifiable: " . ($uploadMaxFilesizeInfo['upload_max_filesize']['access'] ?? 'bilgi yok') . "<br>";

// PHP_INI_PERDIR (6) veya PHP_INI_SYSTEM (4) ise, runtime'da değiştirilemez
echo "<br>Not: PHP_INI_USER=1, PHP_INI_PERDIR=6, PHP_INI_SYSTEM=4, PHP_INI_ALL=7<br>";
echo "Eğer değer 6 veya 4 ise, runtime'da değiştirilemez ve php.ini dosyasının güncellenmesi gerekir.<br>";
?> 