<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Foreign key kontrolleri devre dışı bırak
DB::statement('SET FOREIGN_KEY_CHECKS=0;');

// header_mega_menu_items tablosunu temizle
DB::table('header_mega_menu_items')->delete();
echo "header_mega_menu_items tablosu temizlendi.\n";

// header_mega_menus tablosunu temizle
DB::table('header_mega_menus')->delete();
echo "header_mega_menus tablosu temizlendi.\n";

// menus tablosundaki header tipindeki tüm kayıtları sil
$deletedCount = DB::table('menus')->where('type', 'header')->delete();
echo "menus tablosundan {$deletedCount} header kayıt silindi.\n";

// Foreign key kontrollerini yeniden etkinleştir
DB::statement('SET FOREIGN_KEY_CHECKS=1;');

echo "İşlem tamamlandı. Şimdi seeder'ları tekrar çalıştırabilirsiniz.\n"; 