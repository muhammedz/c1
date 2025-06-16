<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\GuideCategory;
use App\Models\GuidePlace;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ImportGuideData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'guide:import {file=rehber.json}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'JSON dosyasından rehber verilerini içe aktar';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $filePath = $this->argument('file');
        
        if (!file_exists($filePath)) {
            $this->error("Dosya bulunamadı: {$filePath}");
            return 1;
        }

        $this->info('JSON dosyası okunuyor...');
        
        $jsonContent = file_get_contents($filePath);
        $data = json_decode($jsonContent, true);
        
        if (!$data) {
            $this->error('JSON dosyası okunamadı veya geçersiz format!');
            return 1;
        }

        $this->info(count($data) . ' kayıt bulundu.');

        try {
            DB::beginTransaction();
            
            // Kategorileri oluştur
            $this->createCategories($data);
            
            // Yerleri oluştur
            $this->createPlaces($data);
            
            DB::commit();
            
            $this->info('✅ Tüm veriler başarıyla aktarıldı!');
            
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error('❌ Hata oluştu: ' . $e->getMessage());
            Log::error('Guide import error: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }

    /**
     * Kategorileri oluştur
     */
    private function createCategories($data)
    {
        $this->info('Kategoriler oluşturuluyor...');
        
        // Benzersiz kategorileri çıkar
        $categories = collect($data)->pluck('Kategori')->unique()->filter()->values();
        
        $bar = $this->output->createProgressBar($categories->count());
        $bar->start();
        
        foreach ($categories as $categoryName) {
            // Kategori zaten var mı kontrol et
            $existingCategory = GuideCategory::where('name', $categoryName)->first();
            
            if (!$existingCategory) {
                GuideCategory::create([
                    'name' => $categoryName,
                    'slug' => Str::slug($categoryName),
                    'description' => null,
                    'icon' => $this->getCategoryIcon($categoryName),
                    'sort_order' => 0,
                    'is_active' => true,
                    'meta_title' => $categoryName,
                    'meta_description' => $categoryName . ' kategorisindeki yerler'
                ]);
            }
            
            $bar->advance();
        }
        
        $bar->finish();
        $this->newLine();
        $this->info('✅ ' . $categories->count() . ' kategori işlendi.');
    }

    /**
     * Yerleri oluştur
     */
    private function createPlaces($data)
    {
        $this->info('Yerler oluşturuluyor...');
        
        $bar = $this->output->createProgressBar(count($data));
        $bar->start();
        
        foreach ($data as $item) {
            // Kategoriyi bul
            $category = GuideCategory::where('name', $item['Kategori'])->first();
            
            if (!$category) {
                $this->warn("Kategori bulunamadı: " . $item['Kategori']);
                continue;
            }

            // Aynı isimde yer var mı kontrol et
            $existingPlace = GuidePlace::where('title', $item['Ad'])->first();
            
            if (!$existingPlace) {
                GuidePlace::create([
                    'guide_category_id' => $category->id,
                    'title' => $item['Ad'],
                    'slug' => $this->generateUniqueSlug($item['Ad']),
                    'content' => null,
                    'address' => $this->cleanAddress($item['Adres']),
                    'phone' => $this->cleanPhone($item['TelefonNo']),
                    'email' => null,
                    'website' => null,
                    'maps_link' => null,
                    'latitude' => null,
                    'longitude' => null,
                    'working_hours' => null,
                    'sort_order' => 0,
                    'is_active' => true,
                    'meta_title' => $item['Ad'],
                    'meta_description' => $item['Ad'] . ' - ' . $item['Kategori']
                ]);
            }
            
            $bar->advance();
        }
        
        $bar->finish();
        $this->newLine();
        $this->info('✅ ' . count($data) . ' yer işlendi.');
    }

    /**
     * Kategori ikonu belirle
     */
    private function getCategoryIcon($categoryName)
    {
        $icons = [
            'Baharevleri' => 'fas fa-seedling',
            'Sağlık Hizmetleri' => 'fas fa-hospital',
            'Nikah Salonları' => 'fas fa-heart',
            'Kent Lokantaları' => 'fas fa-utensils',
            'Çankaya Evleri' => 'fas fa-home',
            'Belediye Hizmet Binaları' => 'fas fa-building',
            'Gündüz Bakımevleri' => 'fas fa-baby',
            'Spor Tesisleri' => 'fas fa-dumbbell',
            'Kültür Merkezleri' => 'fas fa-theater-masks',
            'Kütüphaneler' => 'fas fa-book',
            'Pazar Yerleri' => 'fas fa-shopping-cart',
            'Zabıta Merkezleri' => 'fas fa-shield-alt',
            'Wi-Fi Noktaları' => 'fas fa-wifi',
            'Kadın Danışma Merkezleri' => 'fas fa-female',
            'Gençlik Merkezleri' => 'fas fa-users',
            'Tarihi Yapılar' => 'fas fa-landmark',
            'Kafeler' => 'fas fa-coffee',
            'Diğer' => 'fas fa-map-marker-alt'
        ];

        return $icons[$categoryName] ?? 'fas fa-map-marker-alt';
    }

    /**
     * Benzersiz slug oluştur
     */
    private function generateUniqueSlug($title)
    {
        $slug = Str::slug($title);
        $originalSlug = $slug;
        $counter = 1;
        
        while (GuidePlace::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }
        
        return $slug;
    }

    /**
     * Adres temizle
     */
    private function cleanAddress($address)
    {
        if (empty($address)) {
            return null;
        }
        
        return trim($address);
    }

    /**
     * Telefon numarası temizle
     */
    private function cleanPhone($phone)
    {
        if (empty($phone)) {
            return null;
        }
        
        // Boşlukları ve gereksiz karakterleri temizle
        $phone = preg_replace('/[^\d\+\(\)\-\s]/', '', $phone);
        $phone = trim($phone);
        
        if (empty($phone)) {
            return null;
        }
        
        return $phone;
    }
}
