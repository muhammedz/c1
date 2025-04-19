<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo "<h2>Yükleme İşlemi Sonuçları</h2>";
    
    // POST verilerini kontrol et
    echo "<h3>POST Verileri:</h3>";
    echo "<pre>";
    print_r($_POST);
    echo "</pre>";
    
    // Dosya yükleme hatalarını kontrol et
    echo "<h3>Dosya Bilgileri:</h3>";
    echo "<pre>";
    print_r($_FILES);
    echo "</pre>";
    
    if (isset($_FILES['userfile']) && $_FILES['userfile']['error'] === UPLOAD_ERR_OK) {
        // Yükleme başarılı
        $fileName = $_FILES['userfile']['name'];
        $fileType = $_FILES['userfile']['type'];
        $fileSize = $_FILES['userfile']['size'];
        $fileTmpName = $_FILES['userfile']['tmp_name'];
        
        echo "<div style='color: green; font-weight: bold;'>Dosya başarıyla yüklendi!</div>";
        echo "Dosya Adı: $fileName<br>";
        echo "Dosya Tipi: $fileType<br>";
        echo "Dosya Boyutu: " . round($fileSize / 1024, 2) . " KB<br>";
        
        // Dosyayı taşı
        $uploadDir = __DIR__ . '/uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        $targetFile = $uploadDir . basename($fileName);
        if (move_uploaded_file($fileTmpName, $targetFile)) {
            echo "<div style='color: green;'>Dosya başarıyla kaydedildi: $targetFile</div>";
        } else {
            echo "<div style='color: red;'>Dosya taşıma hatası!</div>";
        }
    } else {
        // Yükleme hatası
        $uploadError = "";
        if (isset($_FILES['userfile'])) {
            switch ($_FILES['userfile']['error']) {
                case UPLOAD_ERR_INI_SIZE:
                    $uploadError = "Dosya boyutu php.ini'deki upload_max_filesize değerini aşıyor.";
                    break;
                case UPLOAD_ERR_FORM_SIZE:
                    $uploadError = "Dosya boyutu formda belirtilen MAX_FILE_SIZE değerini aşıyor.";
                    break;
                case UPLOAD_ERR_PARTIAL:
                    $uploadError = "Dosya yalnızca kısmen yüklendi.";
                    break;
                case UPLOAD_ERR_NO_FILE:
                    $uploadError = "Hiçbir dosya yüklenmedi.";
                    break;
                case UPLOAD_ERR_NO_TMP_DIR:
                    $uploadError = "Geçici klasör eksik.";
                    break;
                case UPLOAD_ERR_CANT_WRITE:
                    $uploadError = "Dosya diske yazılamadı.";
                    break;
                case UPLOAD_ERR_EXTENSION:
                    $uploadError = "Bir PHP uzantısı dosya yüklemeyi durdurdu.";
                    break;
                default:
                    $uploadError = "Bilinmeyen yükleme hatası.";
                    break;
            }
        }
        echo "<div style='color: red; font-weight: bold;'>Dosya yükleme hatası: $uploadError</div>";
    }
} else {
?>
<!DOCTYPE html>
<html>
<head>
    <title>Basit Dosya Yükleme Testi</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .upload-form {
            border: 1px solid #ccc;
            padding: 20px;
            border-radius: 5px;
            width: 500px;
            margin: 0 auto;
        }
        .upload-form h2 {
            margin-top: 0;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        .btn {
            padding: 8px 15px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .btn:hover {
            background-color: #45a049;
        }
        .info {
            margin-top: 20px;
            padding: 10px;
            background-color: #f8f9fa;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="upload-form">
        <h2>Basit Dosya Yükleme Testi</h2>
        
        <form action="" method="post" enctype="multipart/form-data">
            <!-- 2MB limit -->
            <input type="hidden" name="MAX_FILE_SIZE" value="2097152" /><!-- 2MB -->
            
            <div class="form-group">
                <label for="userfile">Dosya Seçin:</label>
                <input type="file" name="userfile" id="userfile" />
            </div>
            
            <div class="form-group">
                <button type="submit" class="btn">Yükle</button>
            </div>
        </form>
        
        <div class="info">
            <h3>PHP Yükleme Limitleri:</h3>
            <p>
                post_max_size: <?php echo ini_get('post_max_size'); ?><br>
                upload_max_filesize: <?php echo ini_get('upload_max_filesize'); ?><br>
                memory_limit: <?php echo ini_get('memory_limit'); ?><br>
                max_file_uploads: <?php echo ini_get('max_file_uploads'); ?>
            </p>
        </div>
    </div>
</body>
</html>
<?php
}
?> 