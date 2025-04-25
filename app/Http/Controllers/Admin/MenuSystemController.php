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
        if ($menu->type == 2 || $menu->type == 3) {
            $menu->load('items');
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
            if ($menu->type == 2) {
                // Menüye ait öğeleri sil
                MenuSystemItem::where('menu_id', $menu->id)->delete();
            }
            
            // Menüyü sil
            $menu->delete();
            
            DB::commit();
            
            // Header menü cache'ini temizle
            app(\App\Services\HeaderService::class)->clearCache();
            
            return redirect()->route('admin.menusystem.index')->with('success', 'Menü başarıyla silindi');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Menü silinirken bir hata oluştu: ' . $e->getMessage());
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
     * Ajax ile yeni menü öğesi kaydeder.
     */
    public function storeItem(Request $request)
    {
        try {
            // Debug için tüm request verilerini logla
            \Log::info('Buton ekleme isteği - ham veri:', $request->all());
            
            // Validasyon
            $request->validate([
                'menu_id' => 'required|integer',
                'title' => 'required|string|max:255',
                'url' => 'nullable|string|max:255',
                'parent_id' => 'nullable|integer',
                'icon' => 'nullable|string|max:50',
                'order' => 'nullable|integer',
                'status' => 'nullable',
                'new_tab' => 'nullable',
                'description' => 'nullable|string'
            ]);
            
            \Log::info('Validasyon başarılı, verileri işleme başlanıyor');
            
            DB::beginTransaction();
            
            // Menu ID kontrolü
            $menuId = $request->menu_id;
            \Log::info('İşlenecek menu_id:', ['menu_id' => $menuId]);
            
            // Menü varlığını kontrol et
            $menu = MenuSystem::find($menuId);
            if (!$menu) {
                throw new \Exception("Menu ID {$menuId} bulunamadı");
            }
            
            // Menü öğesi oluştur
            $menuItem = new MenuSystemItem();
            $menuItem->menu_id = $menuId;
            $menuItem->parent_id = $request->parent_id ?? 0;
            $menuItem->title = $request->title;
            $menuItem->url = $request->url ?? '';
            $menuItem->icon = $request->icon;
            $menuItem->order = $request->order ?? 0;
            
            // Checkbox değerleri kontrolü
            $status = $request->has('status');
            $newTab = $request->has('new_tab');
            
            \Log::info('Checkbox değerleri:', [
                'status_has' => $request->has('status'),
                'status_input' => $request->input('status'),
                'new_tab_has' => $request->has('new_tab'),
                'new_tab_input' => $request->input('new_tab')
            ]);
            
            $menuItem->status = $status ? 1 : 0;
            $menuItem->new_tab = $newTab ? 1 : 0;
            $menuItem->target = $newTab ? '_blank' : '_self';
            $menuItem->description = $request->description;
            
            \Log::info('Kaydedilecek veri:', $menuItem->toArray());
            
            $menuItem->save();
            
            \Log::info('Menü öğesi başarıyla kaydedildi', ['id' => $menuItem->id]);
            
            DB::commit();
            
            // Header menü cache'ini temizle
            app(\App\Services\HeaderService::class)->clearCache();
            
            return response()->json(['success' => true, 'item' => $menuItem]);
        } catch (ValidationException $e) {
            DB::rollBack();
            \Log::error('Validasyon hatası:', ['errors' => $e->errors()]);
            return response()->json(['success' => false, 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Hata oluştu:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Ajax ile menü öğesi günceller.
     */
    public function updateItem(Request $request, $id)
    {
        try {
            // Validasyon
            $request->validate([
                'title' => 'required|string|max:255',
                'url' => 'nullable|string|max:255',
                'parent_id' => 'nullable|exists:menu_system_items,id',
                'icon' => 'nullable|string|max:50',
                'order' => 'nullable|integer',
                'status' => 'boolean',
                'new_tab' => 'boolean',
                'description' => 'nullable|string'
            ]);
            
            DB::beginTransaction();
            
            // Menü öğesini bul ve güncelle
            $item = MenuSystemItem::findOrFail($id);
            $item->parent_id = $request->parent_id;
            $item->title = $request->title;
            $item->url = $request->url;
            $item->icon = $request->icon;
            $item->order = $request->order ?? 0;
            $item->status = $request->input('status') ? 1 : 0;
            $item->new_tab = $request->input('new_tab') ? 1 : 0;
            $item->target = $request->input('new_tab') ? '_blank' : '_self';
            $item->description = $request->description;
            $item->save();
            
            DB::commit();
            
            // Header menü cache'ini temizle
            app(\App\Services\HeaderService::class)->clearCache();
            
            return response()->json(['success' => true, 'item' => $item]);
        } catch (ValidationException $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Ajax ile menü öğesi siler.
     */
    public function destroyItem($id)
    {
        try {
            DB::beginTransaction();
            
            // Menü öğesini bul
            $item = MenuSystemItem::findOrFail($id);
            $menuId = $item->menu_id;
            
            // Alt öğeleri varsa sil
            MenuSystemItem::where('parent_id', $id)->delete();
            
            // Menü öğesini sil
            $item->delete();
            
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
}
