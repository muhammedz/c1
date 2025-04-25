<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Tüm menu tiplerini listele
$types = DB::table('menus')->select('type')->distinct()->get();
echo "Mevcut menu tipleri:\n";
foreach ($types as $type) {
    echo "- " . ($type->type ?? 'NULL') . "\n";
}

// Her tipten kaç menu var
$counts = DB::table('menus')->select('type', DB::raw('count(*) as count'))->groupBy('type')->get();
echo "\nHer tipten kaç menu var:\n";
foreach ($counts as $count) {
    echo "- " . ($count->type ?? 'NULL') . ": " . $count->count . "\n";
}

// İlk birkaç menu kaydını göster
$menus = DB::table('menus')->take(5)->get();
echo "\nÖrnek menu kayıtları:\n";
foreach ($menus as $menu) {
    echo "- ID: {$menu->id}, İsim: {$menu->name}, Tip: {$menu->type}\n";
} 