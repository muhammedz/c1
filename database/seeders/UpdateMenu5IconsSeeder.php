<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MenuSystemItem;
use Illuminate\Support\Facades\DB;

class UpdateMenu5IconsSeeder extends Seeder
{
    /**
     * Menü ID 5 öğelerinin ikonlarını isimlerine göre günceller.
     * Kurumsal menüsü için uygun ikonlar atar.
     */
    public function run(): void
    {
        $this->command->info('🎨 Menü ID 5 (Kurumsal) ikonları güncelleniyor...');
        
        try {
            // İkon eşleştirmeleri - Kurumsal menüsü için özel
            $iconMappings = [
                // Belediye Yönetimi
                'belediye başkanı' => 'fas fa-user-crown',
                'başkan' => 'fas fa-user-tie',
                'başkan yardımcıları' => 'fas fa-users-cog',
                'yardımcı' => 'fas fa-user-friends',
                'meclis' => 'fas fa-gavel',
                'encümen' => 'fas fa-users',
                'makam' => 'fas fa-building',
                
                // Müdürlükler ve Organizasyon
                'müdürlük' => 'fas fa-sitemap',
                'müdür' => 'fas fa-user-cog',
                
                // Kurumsal Kimlik ve Misyon
                'misyon' => 'fas fa-bullseye',
                'vizyon' => 'fas fa-eye',
                'kimlik' => 'fas fa-id-card',
                'kurumsal kimlik' => 'fas fa-fingerprint',
                'politika' => 'fas fa-clipboard-list',
                'standart' => 'fas fa-award',
                'hizmet standart' => 'fas fa-medal',
                
                // Şirketler ve İştirakler
                'belpet' => 'fas fa-gas-pump',
                'çanpaş' => 'fas fa-bus',
                'belde' => 'fas fa-city',
                'imar' => 'fas fa-drafting-compass',
                'a.ş.' => 'fas fa-building',
                'şirket' => 'fas fa-industry',
                'iştirak' => 'fas fa-handshake',
                
                // Çevre ve Sürdürülebilirlik
                'iklim' => 'fas fa-cloud-sun',
                'değişiklik' => 'fas fa-exchange-alt',
                'sıfır atık' => 'fas fa-recycle',
                'atık' => 'fas fa-trash-alt',
                'çevre' => 'fas fa-leaf',
                
                // Sosyal Sorumluluk
                'engelsiz' => 'fas fa-wheelchair',
                'engelli' => 'fas fa-universal-access',
                'uluslararası' => 'fas fa-globe',
                'iş birlik' => 'fas fa-handshake',
                'işbirlik' => 'fas fa-hands-helping',
                
                // Tarih ve Kültür
                'çankaya' => 'fas fa-map-marker-alt',
                'tarih' => 'fas fa-history',
                'tarihçe' => 'fas fa-scroll',
                'antik' => 'fas fa-landmark',
                'anıtkabir' => 'fas fa-monument',
                'kültürel miras' => 'fas fa-university',
                'kültür' => 'fas fa-theater-masks',
                'miras' => 'fas fa-crown',
                
                // Yaşam ve Toplum
                'ekonomik' => 'fas fa-chart-line',
                'yaşam' => 'fas fa-heart',
                'doğal yapı' => 'fas fa-mountain',
                'doğa' => 'fas fa-tree',
                'rakam' => 'fas fa-calculator',
                'istatistik' => 'fas fa-chart-bar',
                
                // Güvenlik ve Teknoloji
                'bilgi güvenlik' => 'fas fa-shield-alt',
                'güvenlik' => 'fas fa-lock',
                'bilgi' => 'fas fa-info-circle',
            ];
            
            // Menü ID 5'deki tüm öğeleri al
            $items = MenuSystemItem::where('menu_id', 5)->get();
            
            if ($items->isEmpty()) {
                $this->command->warn('⚠️  Menü ID 5\'de öğe bulunamadı!');
                return;
            }
            
            $this->command->info("📋 {$items->count()} öğe bulundu, ikonlar güncelleniyor...");
            
            $updatedCount = 0;
            
            foreach ($items as $item) {
                $title = strtolower($item->title);
                $icon = $this->findBestIcon($title, $iconMappings);
                
                if ($icon) {
                    $item->icon = $icon;
                    $item->save();
                    $updatedCount++;
                    
                    $this->command->line("  ✅ {$item->title} → {$icon}");
                } else {
                    // Kurumsal menüsü için varsayılan ikon
                    $defaultIcon = 'fas fa-building';
                    $item->icon = $defaultIcon;
                    $item->save();
                    $updatedCount++;
                    
                    $this->command->line("  🔹 {$item->title} → {$defaultIcon} (varsayılan)");
                }
            }
            
            $this->command->info("🎉 Başarılı! {$updatedCount} öğenin ikonu güncellendi!");
            $this->command->info("🌐 Kontrol URL: http://localhost:8000/admin/menusystem/5/items");
            
        } catch (\Exception $e) {
            $this->command->error("❌ Hata oluştu: " . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Başlığa en uygun ikonu bulur
     */
    private function findBestIcon(string $title, array $iconMappings): ?string
    {
        // Önce tam eşleşme ara
        if (isset($iconMappings[$title])) {
            return $iconMappings[$title];
        }
        
        // Sonra kısmi eşleşmeleri ara (uzun kelimeleri önce kontrol et)
        $sortedMappings = $iconMappings;
        uksort($sortedMappings, function($a, $b) {
            return strlen($b) - strlen($a);
        });
        
        foreach ($sortedMappings as $keyword => $icon) {
            if (str_contains($title, $keyword)) {
                return $icon;
            }
        }
        
        return null;
    }
} 