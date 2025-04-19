<?php

// Veritabanı bağlantısı için Laravel'ı başlat
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "Mevcut migration kayıtları:\n";
$existingMigrations = DB::table('migrations')->get();
foreach ($existingMigrations as $migration) {
    echo "- {$migration->migration} (Batch: {$migration->batch})\n";
}

// En son batch numarasını al
$lastBatch = DB::table('migrations')->max('batch') + 1;
echo "\nSon batch numarası: $lastBatch\n\n";

// Eksik migrationları ekle
$migrations = [
    '2025_04_18_142046_create_filemanagersystem_medias_table',
    '2025_04_18_142056_create_filemanagersystem_medias_table',
    '2025_04_18_142128_create_filemanagersystem_folders_table'
];

foreach ($migrations as $migration) {
    $exists = DB::table('migrations')->where('migration', $migration)->exists();
    
    if (!$exists) {
        DB::table('migrations')->insert([
            'migration' => $migration,
            'batch' => $lastBatch
        ]);
        echo "Migration eklendi: $migration (Batch: $lastBatch)\n";
    } else {
        echo "Migration zaten var: $migration\n";
    }
}

echo "\nSon migration durumu:\n";
$finalMigrations = DB::table('migrations')->get();
foreach ($finalMigrations as $migration) {
    echo "- {$migration->migration} (Batch: {$migration->batch})\n";
}

echo "\nİşlem tamamlandı!\n"; 