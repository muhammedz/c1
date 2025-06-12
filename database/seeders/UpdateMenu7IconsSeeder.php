<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MenuSystemItem;
use Illuminate\Support\Facades\DB;

class UpdateMenu7IconsSeeder extends Seeder
{
    /**
     * Menü ID 7 öğelerinin ikonlarını isimlerine göre günceller.
     * Duyurular menüsü için uygun ikonlar atar.
     */
    public function run(): void
    {
        $this->command->info('🎨 Menü ID 7 (Duyurular) ikonları güncelleniyor...');
        
        try {
            // İkon eşleştirmeleri - Duyurular menüsü için özel
            $iconMappings = [
                // Etkinlikler
                'etkinlik' => 'fas fa-calendar-alt',
                'tüm etkinlik' => 'fas fa-calendar-check',
                
                // Planlar ve Belgeler
                'askı' => 'fas fa-thumbtack',
                'plan' => 'fas fa-map-marked-alt',
                'askıdaki plan' => 'fas fa-clipboard-list',
                
                // Meclis ve Yönetim
                'meclis' => 'fas fa-gavel',
                'karar' => 'fas fa-balance-scale',
                'güncel' => 'fas fa-clock',
                
                // İhaleler
                'ihale' => 'fas fa-handshake',
                'ihaleler' => 'fas fa-handshake',
                
                // Stratejik Belgeler
                'stratejik' => 'fas fa-chart-line',
                'yürürlük' => 'fas fa-check-circle',
                'strateji' => 'fas fa-bullseye',
                
                // Yayınlar ve Dergiler
                'karanfil' => 'fas fa-flower',
                'dergi' => 'fas fa-newspaper',
                'magazin' => 'fas fa-book-open',
                
                // Kadın ve Sosyal
                'kadın' => 'fas fa-venus',
                'bülten' => 'fas fa-file-alt',
                'bulletin' => 'fas fa-bullhorn',
            ];
            
            // Menü ID 7'deki tüm öğeleri al
            $items = MenuSystemItem::where('menu_id', 7)->get();
            
            if ($items->isEmpty()) {
                $this->command->warn('⚠️  Menü ID 7\'de öğe bulunamadı!');
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
                    // Duyurular menüsü için varsayılan ikon
                    $defaultIcon = 'fas fa-bullhorn';
                    $item->icon = $defaultIcon;
                    $item->save();
                    $updatedCount++;
                    
                    $this->command->line("  🔹 {$item->title} → {$defaultIcon} (varsayılan)");
                }
            }
            
            $this->command->info("🎉 Başarılı! {$updatedCount} öğenin ikonu güncellendi!");
            $this->command->info("🌐 Kontrol URL: http://localhost:8000/admin/menusystem/7/items");
            
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