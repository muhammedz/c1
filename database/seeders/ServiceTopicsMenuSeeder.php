<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ServiceTopic;
use App\Models\MenuSystemItem;
use Illuminate\Support\Facades\DB;

class ServiceTopicsMenuSeeder extends Seeder
{
    /**
     * Hizmet konularını menü sistemine aktarır.
     * Menu ID = 6 için hizmet konularını menü öğeleri olarak ekler.
     */
    public function run(): void
    {
        $this->command->info('🚀 Hizmet konuları menü sistemine aktarılıyor...');
        
        try {
            // 1. Mevcut menü ID=6 öğelerini sil
            $deletedCount = DB::table('menu_system_items')->where('menu_id', 6)->count();
            DB::table('menu_system_items')->where('menu_id', 6)->delete();
            
            if ($deletedCount > 0) {
                $this->command->info("🗑️  Menu ID=6'da {$deletedCount} mevcut öğe silindi.");
            }
            
            // 2. Hizmet konularını al
            $serviceTopics = ServiceTopic::active()->ordered()->get();
            
            if ($serviceTopics->isEmpty()) {
                $this->command->error('❌ Hizmet konuları bulunamadı! Önce ServiceTopicSeeder çalıştırın.');
                return;
            }
            
            $this->command->info("📋 {$serviceTopics->count()} hizmet konusu bulundu.");
            
            // 3. Her konu için menü öğesi oluştur
            $createdCount = 0;
            
            foreach ($serviceTopics as $topic) {
                MenuSystemItem::create([
                    'menu_id' => 6,
                    'parent_id' => null,
                    'title' => $topic->name,
                    'url' => '/hizmetler/kategoriler/' . $topic->slug,
                    'order' => $topic->order,
                    'status' => true,
                    'new_tab' => false,
                    'icon' => $topic->icon,
                    'target' => '_self',
                    'description' => $topic->description,
                    'properties' => null
                ]);
                
                $createdCount++;
                $this->command->line("  ✅ {$topic->name} ({$topic->icon}) - /hizmetler/kategoriler/{$topic->slug}");
            }
            
            $this->command->info("🎉 Başarılı! {$createdCount} hizmet konusu Menu ID=6'ya eklendi!");
            $this->command->info("🌐 Kontrol URL: http://localhost:8000/admin/menusystem/6/items");
            
        } catch (\Exception $e) {
            $this->command->error("❌ Hata oluştu: " . $e->getMessage());
            throw $e;
        }
    }
} 