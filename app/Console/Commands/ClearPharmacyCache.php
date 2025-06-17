<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class ClearPharmacyCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pharmacy:clear-cache';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Nöbetçi eczane cache\'ini temizle';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $clearedCount = 0;
        
        // Bilinen pharmacy cache anahtarlarını temizle
        $plateCode = '06'; // Ankara
        $today = date('d/m/Y');
        $districts = ['ÇANKAYA', 'KEÇİÖREN', 'YENIMAHALLE', 'MAMAK', 'ALTINDAĞ', 'ETİMESGUT', 'SİNCAN', 'PURSAKLAR'];
        
        // Bugün ve son 7 günün cache'lerini temizle
        for ($i = 0; $i <= 7; $i++) {
            $date = date('d/m/Y', strtotime("-{$i} days"));
            
            // Global cache
            $globalKey = "pharmacy_global_{$plateCode}_{$date}";
            if (Cache::has($globalKey)) {
                Cache::forget($globalKey);
                $clearedCount++;
            }
            
            // İlçe bazlı cache'ler
            foreach ($districts as $district) {
                $cacheKey = "pharmacy_data_{$plateCode}_{$date}_{$district}";
                if (Cache::has($cacheKey)) {
                    Cache::forget($cacheKey);
                    $clearedCount++;
                }
            }
        }
        
        // Rate limit cache'lerini de temizle
        $rateLimitPattern = 'pharmacy_request_';
        // Bu kısmı basit tutuyoruz, çünkü IP bazlı olduğu için tam listesini bilemeyiz
        
        $this->info("Pharmacy cache temizlendi: {$clearedCount} anahtar silindi.");
        $this->info('Tarih: ' . now()->format('d/m/Y H:i:s'));
        
        return 0;
    }
}
