<?php
// MediaPicker İlişki Test Sayfası

// DB bağlantısı için Laravel çekirdeğini yükle
require_once __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
use Illuminate\Support\Facades\DB;
use App\Models\FileManagerSystem\MediaRelation;
use App\Models\FileManagerSystem\Media;

echo '<h1>MediaPicker İlişki Testi</h1>';

// Parametreler
$related_type = 'homepage_slider';
$related_id = 1; // Slider ID
$media_id = 18; // Test etmek istediğiniz medya ID'si

// 1. Önce ilişkileri listeleyelim
echo '<h2>Mevcut İlişkiler</h2>';
$relations = DB::table('filemanagersystem_media_relations')
    ->where('related_type', $related_type)
    ->where('related_id', $related_id)
    ->get();

echo '<pre>';
echo "İlişki tipi: {$related_type}\n";
echo "İlişki ID: {$related_id}\n";
echo "İlişki sayısı: " . count($relations) . "\n\n";

if (count($relations) > 0) {
    echo "İlişkiler:\n";
    print_r($relations->toArray());
} else {
    echo "Bu slider için hiç ilişkilendirilmiş medya bulunamadı.\n";
    
    // Test için bir ilişki oluşturalım
    echo "\n<h2>İlişki Oluşturma Testi</h2>";
    
    // İlgili medya var mı?
    $media = Media::find($media_id);
    if ($media) {
        echo "Medya Bulundu: ID {$media_id} - {$media->original_name}\n\n";
        
        // İlişki oluştur
        try {
            $relation = new MediaRelation();
            $relation->media_id = $media_id;
            $relation->related_type = $related_type;
            $relation->related_id = $related_id;
            $relation->save();
            
            echo "İlişki başarıyla oluşturuldu: ID {$relation->id}\n";
        } catch (\Exception $e) {
            echo "HATA: İlişki oluşturulamadı: " . $e->getMessage() . "\n";
        }
    } else {
        echo "HATA: Medya bulunamadı ID: {$media_id}\n";
        
        // Tüm medyaları listele
        echo "\nMevcut Medyalar:\n";
        $allMedia = Media::all();
        foreach ($allMedia as $m) {
            echo "ID: {$m->id} - {$m->original_name}\n";
        }
    }
}

// Medya Picker URL'inin nasıl olması gerektiğini göster
echo "\n<h2>Doğru Medya Seçici URL'i</h2>";
$correctUrl = "/admin/filemanagersystem/mediapicker?related_type={$related_type}&related_id={$related_id}&type=image";
echo "Doğru URL: <a href='{$correctUrl}' target='_blank'>{$correctUrl}</a>\n";

echo '</pre>'; 