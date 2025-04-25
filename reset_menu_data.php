<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Foreign key kontrollerini devre dışı bırak
Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=0;');

// İlişkili tüm tabloları temizle
$menuCards = Illuminate\Support\Facades\DB::table('menu_cards')->delete();
echo "Silinen menü kartı sayısı: $menuCards\n";

$menuTags = Illuminate\Support\Facades\DB::table('menu_tags')->delete();
echo "Silinen menü etiketi sayısı: $menuTags\n";

$headerMegaMenuItems = Illuminate\Support\Facades\DB::table('header_mega_menu_items')->delete();
echo "Silinen mega menü öğesi sayısı: $headerMegaMenuItems\n";

$headerMegaMenus = Illuminate\Support\Facades\DB::table('header_mega_menus')->delete();
echo "Silinen mega menü sayısı: $headerMegaMenus\n";

// Tüm header tipindeki menüleri sil
$menus = Illuminate\Support\Facades\DB::table('menus')->where('type', 'header')->delete();
echo "Silinen menü sayısı: $menus\n";

// Foreign key kontrollerini etkinleştir
Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=1;');

echo "Tüm menü verileri başarıyla silindi!\n"; 