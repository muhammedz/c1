<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Foreign key kontrollerini devre dışı bırak
Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=0;');

// Tüm header tipindeki menüleri sil
$count = Illuminate\Support\Facades\DB::table('menus')->where('type', 'header')->delete();
echo "Silinen menü sayısı: $count\n";

// Foreign key kontrollerini etkinleştir
Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=1;'); 