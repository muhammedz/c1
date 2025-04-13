<?php

// Controller dosyasını yeniden adlandır
$tagControllerPath = __DIR__ . '/app/Http/Controllers/Admin/TagController.php';
$newsTagControllerPath = __DIR__ . '/app/Http/Controllers/Admin/NewsTagController.php';

if (file_exists($tagControllerPath)) {
    if (copy($tagControllerPath, $newsTagControllerPath)) {
        echo "Controller başarıyla kopyalandı\n";
        
        if (unlink($tagControllerPath)) {
            echo "Eski controller dosyası silindi\n";
        } else {
            echo "Eski controller dosyası silinemedi\n";
        }
    } else {
        echo "Controller kopyalanamadı\n";
    }
} else {
    echo "TagController.php dosyası bulunamadı\n";
}

// View klasörünü temizle
$tagsViewPath = __DIR__ . '/resources/views/admin/tags';

if (is_dir($tagsViewPath)) {
    // Klasör içeriğini sil
    $files = glob($tagsViewPath . '/*');
    foreach ($files as $file) {
        if (is_file($file)) {
            if (unlink($file)) {
                echo "Dosya silindi: " . basename($file) . "\n";
            } else {
                echo "Dosya silinemedi: " . basename($file) . "\n";
            }
        }
    }
    
    // Klasörü sil
    if (rmdir($tagsViewPath)) {
        echo "Etiketler klasörü silindi\n";
    } else {
        echo "Etiketler klasörü silinemedi\n";
    }
} else {
    echo "Etiketler view klasörü bulunamadı\n";
}

echo "İşlem tamamlandı\n"; 