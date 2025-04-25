<?php

// Autoloader yükleniyor
require_once __DIR__ . '/vendor/autoload.php';

// Laravel bootstrap
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Veritabanı bağlantısı
$db = app('db');

// ID 31 olan menüyü sorgula
$menu31 = $db->table('menus')->where('id', 31)->first();
echo "ID 31 olan menü bilgileri:\n";
print_r($menu31);

// Son eklenen 3 menüyü sorgula
$latestMenus = $db->table('menus')->where('type', 'header')->orderBy('id', 'desc')->limit(3)->get();
echo "\nSon 3 header menüsü:\n";
foreach ($latestMenus as $menu) {
    echo "ID: {$menu->id}, Ad: {$menu->name}, Aktif: " . ($menu->is_active ? 'Evet' : 'Hayır') . ", is_mega_menu: " . ($menu->is_mega_menu ? 'Evet' : 'Hayır') . "\n";
}

// Mega menu değerleri
echo "\nis_mega_menu alanı olan kayıtlar:\n";
$columnNames = $db->getSchemaBuilder()->getColumnListing('menus');
echo "Tablo alanları: " . implode(', ', $columnNames) . "\n";

// is_mega_menu ve is_active sütunlarıyla ilgili bir durum kontrolü
$checkColumns = $db->select("
    SELECT 
        COUNT(*) as total_count,
        SUM(CASE WHEN is_mega_menu = 1 THEN 1 ELSE 0 END) as mega_menu_count,
        SUM(CASE WHEN is_active = 1 THEN 1 ELSE 0 END) as active_count
    FROM menus
    WHERE type = 'header'
");
echo "\nHeader tipinde toplam: {$checkColumns[0]->total_count} menü var.\n";
echo "Mega Menü olanlar: {$checkColumns[0]->mega_menu_count} adet.\n";
echo "Aktif olanlar: {$checkColumns[0]->active_count} adet.\n";

// Form gönderimini simüle eden bir metod
function simulateFormSubmission($menuId, $isActive, $isMegaMenu) {
    $db = app('db');
    
    echo "\nID $menuId olan menü güncelleme simülasyonu:\n";
    echo "is_active: " . ($isActive ? '1' : '0') . "\n";
    echo "is_mega_menu: " . ($isMegaMenu ? '1' : '0') . "\n";
    
    $result = $db->table('menus')
        ->where('id', $menuId)
        ->update([
            'is_active' => $isActive,
            'is_mega_menu' => $isMegaMenu,
            'updated_at' => date('Y-m-d H:i:s')
        ]);
        
    echo "Güncelleme sonucu: " . ($result ? 'Başarılı' : 'Başarısız') . "\n";
    
    // Güncellenmiş veriyi kontrol et
    $updatedMenu = $db->table('menus')->where('id', $menuId)->first();
    echo "Güncellenmiş durum:\n";
    echo "ID: {$updatedMenu->id}, Ad: {$updatedMenu->name}, Aktif: " . ($updatedMenu->is_active ? 'Evet' : 'Hayır') . ", is_mega_menu: " . ($updatedMenu->is_mega_menu ? 'Evet' : 'Hayır') . "\n";
    
    return $result;
}

// İlk menü kaydı üzerinde deneme güncelleme işlemi
if ($latestMenus->count() > 0) {
    $testMenu = $latestMenus[0];
    
    // Mevcut değerleri tersine çevir
    simulateFormSubmission(
        $testMenu->id,
        !$testMenu->is_active,
        !$testMenu->is_mega_menu
    );
}

// Belirli bir menü ID'sini kontrol et
$id = 183; // kontrol edilecek ID
$menu = \App\Models\HeaderMenuItem::find($id);

echo "HeaderMenuItem modeli ile arama:\n";
if ($menu) {
    echo "Menü bulundu (HeaderMenuItem):\n";
    print_r($menu->toArray());
} else {
    echo "Menü bulunamadı (HeaderMenuItem model).\n";
}

// Doğrudan menu tablosunda da ara
$directMenu = \Illuminate\Support\Facades\DB::table('menus')->where('id', $id)->first();
echo "\nDoğrudan veritabanında arama:\n";
if ($directMenu) {
    echo "Menü bulundu (DB):\n";
    print_r((array)$directMenu);
} else {
    echo "Menü bulunamadı (DB sorgusu).\n";
}

// Tüm mevcut menülerin ID'lerini listele
$allMenuIds = \Illuminate\Support\Facades\DB::table('menus')
    ->where('type', 'header')
    ->orderBy('id')
    ->pluck('id');

echo "\nMevcut menü ID'leri (ilk 10):\n";
print_r($allMenuIds->take(10)->toArray());
echo "\nToplam menü sayısı: " . $allMenuIds->count() . "\n";

// En büyük ID'yi bul
$maxMenuId = \Illuminate\Support\Facades\DB::table('menus')->max('id');
echo "En büyük menü ID'si: " . $maxMenuId . "\n"; 