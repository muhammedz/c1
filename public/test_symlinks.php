<?php

// Test sayfası
echo '<h1>Symlinks PHP Test</h1>';

$path = 'photos/1/baskan.jpg';
$fullPath = __DIR__ . '/../storage/app/public/' . $path;

echo 'Tam dosya yolu: ' . $fullPath . '<br>';
echo 'Dosya mevcut mu: ' . (file_exists($fullPath) ? 'Evet' : 'Hayır') . '<br>';

if (file_exists($fullPath)) {
    echo 'Dosya boyutu: ' . filesize($fullPath) . ' bytes<br>';
    
    if (function_exists('mime_content_type')) {
        echo 'MIME tipi: ' . mime_content_type($fullPath) . '<br>';
    } else {
        echo 'mime_content_type fonksiyonu yok<br>';
        // Alternatif mime tespiti
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        echo 'MIME tipi (finfo): ' . finfo_file($finfo, $fullPath) . '<br>';
        finfo_close($finfo);
    }
    
    echo '<h2>Resim Gösterme Denemeleri:</h2>';
    
    // 1. Base64 ile gösterme
    $data = base64_encode(file_get_contents($fullPath));
    $src = 'data:image/jpeg;base64,'.$data;
    echo '<h3>1. Base64 ile görüntüleme:</h3>';
    echo "<img src='$src' style='max-width:300px'><br>";
    
    // 2. Doğrudan PHP stream ile gösterme
    echo '<h3>2. Aşağıdaki script ile görüntüleme (/test_image.php?path=' . $path . ')</h3>';
    echo "<img src='/test_image.php?path=photos/1/baskan.jpg' style='max-width:300px'><br>";
    
    // 3. symlinks.php ile test
    echo '<h3>3. symlinks.php ile görüntüleme:</h3>';
    echo "<img src='/symlinks.php?path=photos/1/baskan.jpg' style='max-width:300px'><br>";
} 