<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MenuSystemItem;
use Illuminate\Support\Facades\DB;

class UpdateMenu8IconsSeeder extends Seeder
{
    /**
     * Menü ID 8 öğelerinin ikonlarını isimlerine göre günceller.
     */
    public function run(): void
    {
        $this->command->info('🎨 Menü ID 8 ikonları güncelleniyor...');
        
        try {
            // İkon eşleştirmeleri - isim patterns'ına göre
            $iconMappings = [
                // Nikah ve Evlilik
                'nikah' => 'fas fa-heart',
                'konferans' => 'fas fa-users',
                
                // Harita ve Katmanlar
                'harita' => 'fas fa-map',
                'katman' => 'fas fa-layer-group',
                'tüm' => 'fas fa-list',
                
                // Yol ve İnşaat
                'yol' => 'fas fa-road',
                'kaldırım' => 'fas fa-walking',
                'çalışma' => 'fas fa-tools',
                'altyapı' => 'fas fa-hammer',
                'çevre' => 'fas fa-leaf',
                
                // Geri Dönüşüm
                'geri dönüşüm' => 'fas fa-recycle',
                'dönüşüm' => 'fas fa-recycle',
                
                // İnternet ve Teknoloji
                'internet' => 'fas fa-wifi',
                'ücretsiz' => 'fas fa-gift',
                
                // Spor
                'buz' => 'fas fa-skating',
                'spor' => 'fas fa-futbol',
                'yüzme' => 'fas fa-swimmer',
                'havuz' => 'fas fa-water',
                'kompleks' => 'fas fa-building',
                'tesis' => 'fas fa-dumbbell',
                
                // Ulaşım
                'taksi' => 'fas fa-taxi',
                'ulaşım' => 'fas fa-bus',
                'ticaret' => 'fas fa-store',
                
                // Pazar
                'pazar' => 'fas fa-shopping-basket',
                
                // Eğitim ve Çocuk
                'kreş' => 'fas fa-baby',
                'gündüz' => 'fas fa-sun',
                'bakım' => 'fas fa-hands-helping',
                'eğitim' => 'fas fa-graduation-cap',
                
                // Evler ve Konut
                'bahar' => 'fas fa-home',
                'çankaya evleri' => 'fas fa-home-heart',
                'ev' => 'fas fa-home',
                
                // Sosyal Hizmetler
                'kamu' => 'fas fa-landmark',
                'sosyal' => 'fas fa-handshake',
                'hizmet' => 'fas fa-concierge-bell',
                
                // Kültür ve Sanat
                'kültür' => 'fas fa-theater-masks',
                'tiyatro' => 'fas fa-masks-theater',
                'konser' => 'fas fa-music',
                'kütüphane' => 'fas fa-book',
                
                // Toplumsal Yaşam
                'toplumsal' => 'fas fa-people-group',
                'yaşam' => 'fas fa-heart-pulse',
                
                // Acil ve Güvenlik
                'acil' => 'fas fa-exclamation-triangle',
                'toplanma' => 'fas fa-users',
                'zabıta' => 'fas fa-shield-alt',
                'karakol' => 'fas fa-shield',
                
                // Hayvan
                'hayvan' => 'fas fa-paw',
                'barınak' => 'fas fa-home',
                
                // Yemek
                'kent lokantası' => 'fas fa-utensils',
                'lokanta' => 'fas fa-utensils',
                
                // Sağlık
                'ağız' => 'fas fa-tooth',
                'diş' => 'fas fa-tooth',
                'sağlık' => 'fas fa-heartbeat',
                'eczane' => 'fas fa-pills',
                
                // Ödeme
                'ödeme' => 'fas fa-credit-card',
                'tahsilat' => 'fas fa-money-bill-wave',
                
                // Yönetim
                'muhtarlık' => 'fas fa-user-tie',
                'belediye' => 'fas fa-city',
                'bina' => 'fas fa-building',
            ];
            
            // Menü ID 8'deki tüm öğeleri al
            $items = MenuSystemItem::where('menu_id', 8)->get();
            
            if ($items->isEmpty()) {
                $this->command->warn('⚠️  Menü ID 8\'de öğe bulunamadı!');
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
                    // Varsayılan ikon ata
                    $defaultIcon = 'fas fa-map-marker-alt';
                    $item->icon = $defaultIcon;
                    $item->save();
                    $updatedCount++;
                    
                    $this->command->line("  🔹 {$item->title} → {$defaultIcon} (varsayılan)");
                }
            }
            
            $this->command->info("🎉 Başarılı! {$updatedCount} öğenin ikonu güncellendi!");
            $this->command->info("🌐 Kontrol URL: http://localhost:8000/admin/menusystem/8/items");
            
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
        
        // Sonra kısmi eşleşmeleri ara
        foreach ($iconMappings as $keyword => $icon) {
            if (str_contains($title, $keyword)) {
                return $icon;
            }
        }
        
        return null;
    }
} 