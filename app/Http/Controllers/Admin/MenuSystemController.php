<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MenuSystem;
use App\Models\MenuSystemItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class MenuSystemController extends Controller
{
    /**
     * Menülerin listesini görüntüler.
     */
    public function index(Request $request)
    {
        $query = MenuSystem::query()->withCount('items');

        // Filtreleme işlemleri
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $menus = $query->orderBy('order')->paginate(10);
        
        return view('admin.menusystem.index', compact('menus'));
    }

    /**
     * Yeni menü oluşturma formunu gösterir.
     */
    public function create()
    {
        return view('admin.menusystem.create');
    }

    /**
     * Yeni oluşturulan menüyü kaydeder.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|integer|in:1,2,3',
            'url' => 'nullable|string|max:255',
            'order' => 'nullable|integer',
            'status' => 'boolean',
            'description' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();
            
            $menu = new MenuSystem();
            $menu->name = $request->name;
            $menu->type = $request->type;
            $menu->position = 'header'; // Varsayılan olarak header
            $menu->url = $request->url;
            $menu->order = $request->order ?? 0;
            $menu->status = $request->has('status') ? 1 : 0;
            $menu->description = $request->description;
            $menu->save();
            
            // Menü tipi "büyük menü" (2) ise ve menü öğeleri varsa, menü öğelerini de kaydet
            if ($request->type == 2 && $request->has('items')) {
                $items = json_decode($request->items, true);
                if (!empty($items)) {
                    foreach ($items as $index => $item) {
                        $menuItem = new MenuSystemItem();
                        $menuItem->menu_id = $menu->id;
                        $menuItem->name = $item['name'];
                        $menuItem->url = $item['url'];
                        $menuItem->order = $index;
                        $menuItem->status = 1;
                        $menuItem->parent_id = 0;
                        $menuItem->save();
                    }
                }
            }

            DB::commit();
            
            // Header menü cache'ini temizle
            app(\App\Services\HeaderService::class)->clearCache();
            
            return redirect()->route('admin.menusystem.index')->with('success', 'Menü başarıyla oluşturuldu');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Menü oluşturulurken bir hata oluştu: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Belirtilen menüyü düzenleme formunu gösterir.
     */
    public function edit($id)
    {
        $menu = MenuSystem::findOrFail($id);
        
        // Menü tipi "büyük menü" (2) veya "buton menü" (3) ise menü öğelerini de getir
        if ($menu->type == 2) {
            $menuItems = MenuSystemItem::where('menu_id', $menu->id)
                ->where('item_type', 1)
                ->orderBy('order')
                ->get();
                
            return view('admin.menusystem.edit', compact('menu', 'menuItems'));
        } elseif ($menu->type == 3) {
            $buttonItems = MenuSystemItem::where('menu_id', $menu->id)
                ->where('item_type', 2)
                ->orderBy('order')
                ->get();
                
            return view('admin.menusystem.edit', compact('menu', 'buttonItems'));
        }
        
        return view('admin.menusystem.edit', compact('menu'));
    }

    /**
     * Düzenlenmiş menüyü günceller.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|integer|in:1,2,3',
            'url' => 'nullable|string|max:255',
            'order' => 'nullable|integer',
            'status' => 'boolean',
            'description' => 'nullable|string',
            'footer_text' => 'nullable|string|max:255',
            'footer_link' => 'nullable|string|max:255',
        ]);

        try {
            DB::beginTransaction();
            
            $menu = MenuSystem::findOrFail($id);
            $menu->name = $request->name;
            $menu->type = $request->type;
            // Position değeri değiştirilmeyecek, mevcut değer korunacak
            $menu->url = $request->url;
            $menu->order = $request->order ?? $menu->order;
            $menu->status = $request->has('status') ? 1 : 0;
            $menu->description = $request->description;
            
            // Açıklama yazısı ve linki güncellenmesi
            if ($request->has('footer_text')) {
                $menu->footer_text = $request->footer_text;
            }
            
            if ($request->has('footer_link')) {
                $menu->footer_link = $request->footer_link;
            }
            
            $menu->save();
            
            DB::commit();
            
            // Header menü cache'ini temizle
            app(\App\Services\HeaderService::class)->clearCache();
            
            if ($request->ajax()) {
                return response()->json(['success' => true]);
            }
            
            return redirect()->route('admin.menusystem.index')->with('success', 'Menü başarıyla güncellendi');
        } catch (\Exception $e) {
            DB::rollBack();
            
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
            }
            
            return redirect()->back()->with('error', 'Menü güncellenirken bir hata oluştu: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Belirtilen menüyü siler.
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            
            $menu = MenuSystem::findOrFail($id);
            
            // Menü tipine göre işlem yap
            if ($menu->type == 2 || $menu->type == 3) {
                // Menüye ait öğeleri sil
                MenuSystemItem::where('menu_id', $menu->id)->delete();
            }
            
            // Menüyü sil
            $menu->delete();
            
            DB::commit();
            
            // Header menü cache'ini temizle
            app(\App\Services\HeaderService::class)->clearCache();
            
            return response()->json(['success' => true, 'message' => 'Menü başarıyla silindi']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Menü silinirken bir hata oluştu: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Menülerin sıralama düzenini günceller.
     */
    public function updateOrder(Request $request)
    {
        try {
            DB::beginTransaction();
            
            foreach ($request->items as $item) {
                MenuSystem::where('id', $item['id'])->update(['order' => $item['order']]);
            }
            
            DB::commit();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Menü durumunu günceller.
     */
    public function updateStatus(Request $request)
    {
        try {
            $menu = MenuSystem::findOrFail($request->id);
            $menu->status = $request->status;
            $menu->save();
            
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Menü öğelerini yönetme sayfasını gösterir.
     */
    public function showItems($id)
    {
        $menu = MenuSystem::with(['items' => function ($query) {
            $query->orderBy('id', 'asc');
        }])->findOrFail($id);
        
        if ($menu->type != 2 && $menu->type != 3) {
            return redirect()->route('admin.menusystem.index')->with('error', 'Bu menü türü için öğeler yönetimi desteklenmiyor.');
        }
        
        return view('admin.menusystem.items', compact('menu'));
    }

    /**
     * Menü öğesi ekler.
     */
    public function storeItem(Request $request)
    {
        \Log::info('Buton menü öğesi ekleme isteği geldi:', $request->all());
        
        $request->validate([
            'menu_id' => 'required|exists:menu_systems,id',
            'title' => 'required|string|max:255',
            'url' => 'required|string|max:255',
            'item_type' => 'nullable|integer',
            'button_style' => 'nullable|string|max:50',
            'icon' => 'nullable|string|max:65535',
            'order' => 'nullable|integer|min:0',
            'status' => 'nullable|boolean',
        ]);

        try {
            DB::beginTransaction();
            
            $menuItem = new MenuSystemItem();
            $menuItem->menu_id = $request->menu_id;
            $menuItem->title = $request->title;
            $menuItem->url = $request->url;
            $menuItem->item_type = $request->item_type ?? 1; // Varsayılan olarak standart menü öğesi
            $menuItem->button_style = $request->button_style;
            $menuItem->icon = $request->icon;
            $menuItem->order = $request->order ?? 0;
            $menuItem->status = $request->has('status') ? 1 : 0;
            $menuItem->parent_id = $request->parent_id ?? null;
            
            \Log::info('Kaydedilecek veri:', $menuItem->toArray());
            
            $menuItem->save();
            
            \Log::info('Buton menü öğesi başarıyla kaydedildi. ID: ' . $menuItem->id);
            
            DB::commit();
            
            // Header menü cache'ini temizle
            app(\App\Services\HeaderService::class)->clearCache();
            
            if ($request->ajax()) {
                return response()->json(['success' => true, 'message' => 'Menü öğesi başarıyla eklendi', 'item' => $menuItem]);
            }
            
            return redirect()->back()->with('success', 'Menü öğesi başarıyla eklendi');
        } catch (\Exception $e) {
            DB::rollBack();
            
            \Log::error('Buton menü öğesi eklenirken hata:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
            }
            
            return redirect()->back()->with('error', 'Menü öğesi eklenirken bir hata oluştu: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Menü öğesi düzenleme formunu gösterir.
     */
    public function editItem($id)
    {
        try {
            $menuItem = MenuSystemItem::with('menu')->findOrFail($id);
            
            // Menü öğesinin ait olduğu menüyü al
            $menu = $menuItem->menu;
            
            // Aynı menüdeki diğer öğeleri parent seçimi için al
            $parentItems = MenuSystemItem::where('menu_id', $menu->id)
                ->where('id', '!=', $id)
                ->where('parent_id', 0)
                ->orderBy('order')
                ->get();
            
            return view('admin.menusystem.items.edit', compact('menuItem', 'menu', 'parentItems'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Menü öğesi bulunamadı: ' . $e->getMessage());
        }
    }

    /**
     * Menü öğesini günceller.
     */
    public function updateItem(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'url' => 'required|string|max:255',
            'item_type' => 'nullable|integer',
            'button_style' => 'nullable|string|max:50',
            'icon' => 'nullable|string|max:65535',
            'order' => 'nullable|integer|min:0',
        ]);

        try {
            DB::beginTransaction();
            
            $menuItem = MenuSystemItem::findOrFail($id);
            $menuItem->title = $request->title;
            $menuItem->url = $request->url;
            // item_type değeri değiştirilmemeli, orijinal türünü korumalı
            if ($request->has('button_style')) {
                $menuItem->button_style = $request->button_style;
            }
            if ($request->has('icon')) {
                $menuItem->icon = $request->icon;
            }
            if ($request->has('order')) {
                $menuItem->order = $request->order;
            }
            $menuItem->save();
            
            DB::commit();
            
            // Header menü cache'ini temizle
            app(\App\Services\HeaderService::class)->clearCache();
            
            if ($request->ajax()) {
                return response()->json(['success' => true]);
            }
            
            return redirect()->back()->with('success', 'Menü öğesi başarıyla güncellendi');
        } catch (\Exception $e) {
            DB::rollBack();
            
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
            }
            
            return redirect()->back()->with('error', 'Menü öğesi güncellenirken bir hata oluştu: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Menü öğesini siler.
     */
    public function destroyItem($id)
    {
        try {
            // Menü öğesini bul
            $menuItem = MenuSystemItem::findOrFail($id);
            
            // Menü öğesini sil
            $menuItem->delete();
            
            // Header menü cache'ini temizle
            app(\App\Services\HeaderService::class)->clearCache();
            
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Ajax ile menü öğelerinin sıralamasını günceller.
     */
    public function updateItemOrder(Request $request)
    {
        try {
            DB::beginTransaction();
            
            // Tek bir item için güncelleme
            if ($request->has('item_id') && $request->has('order')) {
                MenuSystemItem::where('id', $request->item_id)->update(['order' => $request->order]);
            } 
            // Toplu güncelleme
            elseif ($request->has('items')) {
                foreach ($request->items as $item) {
                    MenuSystemItem::where('id', $item['id'])->update(['order' => $item['order']]);
                }
            }
            
            DB::commit();
            
            // Header menü cache'ini temizle
            app(\App\Services\HeaderService::class)->clearCache();
            
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Menü öğesinin durumunu günceller.
     */
    public function updateItemStatus(Request $request)
    {
        try {
            $item = MenuSystemItem::findOrFail($request->item_id);
            $item->status = $request->status;
            $item->save();
            
            // Header menü cache'ini temizle
            app(\App\Services\HeaderService::class)->clearCache();
            
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Ajax ile menü ebeveyn öğelerini getirir.
     */
    public function getParentItems(Request $request)
    {
        try {
            $menuId = $request->menu_id;
            $menu = MenuSystem::findOrFail($menuId);
            
            // Tüm menü öğelerini ID'ye göre sıralayarak al
            $items = $menu->items()->orderBy('id', 'asc')->get(['id', 'title']);
            
            return response()->json([
                'success' => true,
                'items' => $items
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Menü açıklama bilgilerini günceller
     */
    public function updateFooterInfo(Request $request, $id)
    {
        $request->validate([
            'footer_text' => 'nullable|string|max:255',
            'footer_link' => 'nullable|string|max:255',
        ]);

        try {
            DB::beginTransaction();
            
            $menu = MenuSystem::findOrFail($id);
            
            // Açıklama yazısı ve linki güncellenmesi
            if ($request->has('footer_text')) {
                $menu->footer_text = $request->footer_text;
            }
            
            if ($request->has('footer_link')) {
                $menu->footer_link = $request->footer_link;
            }
            
            $menu->save();
            
            DB::commit();
            
            // Header menü cache'ini temizle
            app(\App\Services\HeaderService::class)->clearCache();
            
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Belirtilen menüye ait öğeleri getirir (API için)
     */
    public function getItems($menuId)
    {
        try {
            $items = MenuSystemItem::where('menu_id', $menuId)
                ->where('status', true)
                ->orderBy('order')
                ->get(['id', 'title', 'url', 'icon', 'description', 'order']);
            
            return response()->json(['success' => true, 'items' => $items]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Side menu için menü öğelerini getirir (Mobil API)
     */
    public function getMenuItemsForSideMenu($menuId)
    {
        try {
            // Önce MenuSystem olarak dene
            $menu = MenuSystem::find($menuId);
            $menuItems = [];
            $menuName = '';
            $menuType = 2;
            
            if ($menu) {
                // MenuSystem bulundu - HeaderService kullan
                $menuItems = app(\App\Services\HeaderService::class)->getMenuItems($menuId);
                $menuName = $menu->name;
                $menuType = $menu->type;
            } else {
                // MenuSystemItem olarak dene
                $menuItem = \App\Models\MenuSystemItem::find($menuId);
                if ($menuItem) {
                    // MenuSystemItem'ın alt öğelerini al
                    $menuItems = \App\Models\MenuSystemItem::where('parent_id', $menuId)
                        ->where('status', true)
                        ->orderBy('order')
                        ->get();
                    $menuName = $menuItem->title;
                    $menuType = 2;
                } else {
                    throw new \Exception("Menü bulunamadı: ID $menuId");
                }
            }
            
            $formattedItems = [];
            
            foreach ($menuItems as $item) {
                $hasChildren = false;
                
                // Alt öğe kontrolü
                if (isset($item->children)) {
                    $hasChildren = $item->children && $item->children->count() > 0;
                } else {
                    // MenuSystemItem için alt öğe kontrolü
                    $childrenCount = \App\Models\MenuSystemItem::where('parent_id', $item->id)
                        ->where('status', true)
                        ->count();
                    $hasChildren = $childrenCount > 0;
                }
                
                $formattedItem = [
                    'id' => $item->id,
                    'name' => $item->title ?? $item->name,
                    'icon' => $this->getIconForItem($item->title ?? $item->name),
                    'url' => $item->url ?? '#',
                    'hasChildren' => $hasChildren,
                    'level' => 2
                ];
                
                // 3. seviye alt öğeler varsa ekle
                if ($hasChildren) {
                    if (isset($item->children)) {
                        // HeaderService'ten gelen veriler
                        $formattedItem['children'] = $item->children->map(function($child) {
                            $childHasChildren = $child->children && $child->children->count() > 0;
                            
                            return [
                                'id' => $child->id,
                                'name' => $child->title ?? $child->name,
                                'icon' => $this->getIconForItem($child->title ?? $child->name),
                                'url' => $child->url ?? '#',
                                'hasChildren' => $childHasChildren,
                                'level' => 3
                            ];
                        })->toArray();
                    } else {
                        // Doğrudan veritabanından alt öğeleri al
                        $children = \App\Models\MenuSystemItem::where('parent_id', $item->id)
                            ->where('status', true)
                            ->orderBy('order')
                            ->get();
                        
                        $formattedItem['children'] = $children->map(function($child) {
                            $childHasChildren = \App\Models\MenuSystemItem::where('parent_id', $child->id)
                                ->where('status', true)
                                ->count() > 0;
                            
                            return [
                                'id' => $child->id,
                                'name' => $child->title,
                                'icon' => $this->getIconForItem($child->title),
                                'url' => $child->url ?? '#',
                                'hasChildren' => $childHasChildren,
                                'level' => 3
                            ];
                        })->toArray();
                    }
                }
                
                $formattedItems[] = $formattedItem;
            }
            
            return response()->json([
                'success' => true,
                'title' => $menuName,
                'items' => $formattedItems,
                'menuType' => $menuType
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Menü öğesi adına göre uygun ikon döndürür
     */
    private function getIconForItem($itemName)
    {
        $iconMapping = [
            // Kurumsal
            'Belediye Makamları' => 'fas fa-users',
            'Başkan' => 'fas fa-user-tie',
            'Başkan Yardımcıları' => 'fas fa-user-friends',
            'Meclis Üyeleri' => 'fas fa-users-cog',
            'Encümen Üyeleri' => 'fas fa-user-check',
            'Organizasyon Şeması' => 'fas fa-sitemap',
            
            'Kurumsal Politikalar' => 'fas fa-clipboard-list',
            'Hizmet Standartları' => 'fas fa-star',
            'Bilgi Güvenliği' => 'fas fa-shield-alt',
            'Uluslararası İş Birlikleri' => 'fas fa-globe',
            'Engelsiz İş Yerleri' => 'fas fa-wheelchair',
            'Sıfır Atık' => 'fas fa-recycle',
            'İklim Değişikliği' => 'fas fa-thermometer-half',
            'Misyon ve Vizyon' => 'fas fa-bullseye',
            
            'Belediye İştirakleri' => 'fas fa-handshake',
            'Kimlik' => 'fas fa-id-card',
            'Tarihçe' => 'fas fa-history',
            'Antik Tarih' => 'fas fa-landmark',
            'Kültürel Yaşam' => 'fas fa-theater-masks',
            'Doğal Yapı' => 'fas fa-mountain',
            'Ekonomik Yaşam' => 'fas fa-chart-line',
            
            // Hizmetler
            'İş Yerleri' => 'fas fa-store',
            'Kültür' => 'fas fa-palette',
            'Sağlık' => 'fas fa-heartbeat',
            'İmar' => 'fas fa-building',
            'Sosyal Yardım' => 'fas fa-hands-helping',
            'Çevre' => 'fas fa-leaf',
            'Veterinerlik' => 'fas fa-paw',
            'Temizlik' => 'fas fa-broom',
            'Park' => 'fas fa-tree',
            'Fen İşleri' => 'fas fa-tools',
            
            // Duyurular
            'Belediye Duyuruları' => 'fas fa-bullhorn',
            'Etkinlikler' => 'fas fa-calendar-alt',
            'Askıdaki Planlar' => 'fas fa-map',
            'Meclis Kararları' => 'fas fa-gavel',
            'İhaleler' => 'fas fa-handshake',
            'Planlar ve Projeler' => 'fas fa-drafting-compass',
            
            // Varsayılan
            'default' => 'fas fa-circle'
        ];
        
        return $iconMapping[$itemName] ?? $iconMapping['default'];
    }
}
