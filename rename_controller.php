<?php

// Controller dosyasını yeniden adlandır
$categoryControllerPath = __DIR__ . '/app/Http/Controllers/Admin/CategoryController.php';
$newsCategoryControllerPath = __DIR__ . '/app/Http/Controllers/Admin/NewsCategoryController.php';

if (file_exists($categoryControllerPath)) {
    if (copy($categoryControllerPath, $newsCategoryControllerPath)) {
        echo "Controller başarıyla kopyalandı\n";
        
        if (unlink($categoryControllerPath)) {
            echo "Eski controller dosyası silindi\n";
        } else {
            echo "Eski controller dosyası silinemedi\n";
        }
    } else {
        echo "Controller kopyalanamadı\n";
    }
} else {
    echo "CategoryController.php dosyası bulunamadı\n";
}

// View klasörünü temizle
$categoriesViewPath = __DIR__ . '/resources/views/admin/categories';

if (is_dir($categoriesViewPath)) {
    // Klasör içeriğini sil
    $files = glob($categoriesViewPath . '/*');
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
    if (rmdir($categoriesViewPath)) {
        echo "Kategoriler klasörü silindi\n";
    } else {
        echo "Kategoriler klasörü silinemedi\n";
    }
} else {
    echo "Kategoriler view klasörü bulunamadı\n";
}

echo "İşlem tamamlandı\n"; 