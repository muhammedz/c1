<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ServiceTopic;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class ServiceTopicController extends Controller
{
    /**
     * Hizmet Konuları listesi
     */
    public function index()
    {
        $serviceTopics = ServiceTopic::withCount('services')
            ->orderBy('order')
            ->paginate(20);
            
        return view('admin.service-topics.index', compact('serviceTopics'));
    }
    
    /**
     * Yeni hizmet konusu oluşturma formu
     */
    public function create()
    {
        return view('admin.service-topics.create');
    }
    
    /**
     * Hizmet konusu kaydetme
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:service_topics,slug',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:255',
            'color' => 'nullable|string|max:7',
            'order' => 'nullable|integer|min:0',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500'
        ]);
        
        try {
            // Slug oluştur
            $slug = $request->slug ?: Str::slug($request->name);
            
            // Slug benzersizliği kontrolü
            $originalSlug = $slug;
            $counter = 1;
            while (ServiceTopic::where('slug', $slug)->exists()) {
                $slug = $originalSlug . '-' . $counter;
                $counter++;
            }
            
            $topicData = [
                'name' => $request->name,
                'slug' => $slug,
                'description' => $request->description,
                'icon' => $request->icon,
                'color' => $request->color,
                'order' => $request->order ?? 0,
                'is_active' => $request->has('is_active') ? 1 : 0,
                'meta_title' => $request->meta_title,
                'meta_description' => $request->meta_description
            ];
            
            $serviceTopic = ServiceTopic::create($topicData);
            
            return redirect()->route('admin.service-topics.index')
                ->with('success', 'Hizmet konusu başarıyla oluşturuldu.');
                
        } catch (\Exception $e) {
            \Log::error('Hizmet konusu oluşturma hatası: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Hizmet konusu oluşturulurken bir hata oluştu.');
        }
    }
    
    /**
     * Hizmet konusu detayları
     */
    public function show(ServiceTopic $serviceTopic)
    {
        $serviceTopic->load(['services' => function($query) {
            $query->published()->orderBy('published_at', 'desc');
        }]);
        
        return view('admin.service-topics.show', compact('serviceTopic'));
    }
    
    /**
     * Hizmet konusu düzenleme formu
     */
    public function edit(ServiceTopic $serviceTopic)
    {
        return view('admin.service-topics.edit', compact('serviceTopic'));
    }
    
    /**
     * Hizmet konusu güncelleme
     */
    public function update(Request $request, ServiceTopic $serviceTopic)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:service_topics,slug,' . $serviceTopic->id,
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:255',
            'color' => 'nullable|string|max:7',
            'order' => 'nullable|integer|min:0',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500'
        ]);
        
        // Slug güncelleme
        $newSlug = $request->slug ?: Str::slug($request->name);
        if ($newSlug !== $serviceTopic->slug) {
            $originalSlug = $newSlug;
            $counter = 1;
            while (ServiceTopic::where('slug', $newSlug)->where('id', '!=', $serviceTopic->id)->exists()) {
                $newSlug = $originalSlug . '-' . $counter;
                $counter++;
            }
        }
        
        $serviceTopic->update([
            'name' => $request->name,
            'slug' => $newSlug,
            'description' => $request->description,
            'icon' => $request->icon,
            'color' => $request->color,
            'order' => $request->order ?? 0,
            'is_active' => $request->has('is_active') ? 1 : 0,
            'meta_title' => $request->meta_title,
            'meta_description' => $request->meta_description
        ]);
        
        return redirect()->route('admin.service-topics.index')
            ->with('success', 'Hizmet konusu başarıyla güncellendi.');
    }
    
    /**
     * Hizmet konusu silme
     */
    public function destroy(ServiceTopic $serviceTopic)
    {
        // İlişkili hizmet kontrolü
        if ($serviceTopic->services()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Bu hizmet konusuna ait hizmetler bulunduğu için silinemez. Önce hizmetleri başka konulara taşıyın veya silin.');
        }
        
        $serviceTopic->delete();
        
        return redirect()->route('admin.service-topics.index')
            ->with('success', 'Hizmet konusu başarıyla silindi.');
    }
    
    /**
     * Hizmet konusu sıralama
     */
    public function updateOrder(Request $request)
    {
        $request->validate([
            'serviceTopics' => 'required|array',
            'serviceTopics.*' => 'exists:service_topics,id'
        ]);
        
        foreach ($request->serviceTopics as $order => $id) {
            ServiceTopic::where('id', $id)->update(['order' => $order + 1]);
        }
        
        return response()->json(['success' => true]);
    }
    
    /**
     * Tüm hizmet konularını temizle
     */
    public function cleanup()
    {
        try {
            DB::beginTransaction();
            
            // Önce hizmet-konu ilişkilerini temizle
            DB::table('service_service_topic')->delete();
            
            // Sonra tüm hizmet konularını sil
            ServiceTopic::truncate();
            
            DB::commit();
            
            return redirect()->route('admin.service-topics.index')
                ->with('success', 'Tüm hizmet konuları başarıyla temizlendi.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Hizmet konuları temizleme hatası: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Hizmet konuları temizlenirken bir hata oluştu: ' . $e->getMessage());
        }
    }
}
