<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Archive;

class UpdateArchivesDownloadButtonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Mevcut arşivlere varsayılan değerleri ekle
        Archive::whereNull('show_download_button')->update([
            'show_download_button' => true,
            'download_button_text' => 'Belgeleri İndir'
        ]);
        
        $this->command->info('Mevcut arşivlere varsayılan indirme butonu değerleri eklendi.');
    }
}
