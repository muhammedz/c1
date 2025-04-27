<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\MenuSystem;
use App\Models\MenuSystemItem;

class UpdateMenuItemsTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Mevcut tüm menüleri getir
        $menus = MenuSystem::all();
        
        foreach ($menus as $menu) {
            // Menü tipine göre item_type değeri ata
            if ($menu->type == 2) { // Büyük Menü
                // Bu menüye ait tüm öğelere item_type=1 ata
                MenuSystemItem::where('menu_id', $menu->id)
                    ->update(['item_type' => 1]);
                
                $this->command->info("Menü #" . $menu->id . " (" . $menu->name . ") için " . 
                    MenuSystemItem::where('menu_id', $menu->id)->count() . " öğe güncellendi. (item_type=1)");
            } elseif ($menu->type == 3) { // Buton Menü
                // Bu menüye ait tüm öğelere item_type=2 ata
                MenuSystemItem::where('menu_id', $menu->id)
                    ->update(['item_type' => 2]);
                
                $this->command->info("Menü #" . $menu->id . " (" . $menu->name . ") için " . 
                    MenuSystemItem::where('menu_id', $menu->id)->count() . " öğe güncellendi. (item_type=2)");
            }
        }
        
        $this->command->info('Tüm menü öğeleri güncellendi.');
    }
}
