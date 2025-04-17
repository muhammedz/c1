<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CorporateCategory;
use App\Models\CorporateMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CorporateMemberController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, $category_id = null)
    {
        $query = CorporateMember::with('category')->orderBy('order', 'asc');
        
        if ($category_id) {
            $query->where('corporate_category_id', $category_id);
            $category = CorporateCategory::findOrFail($category_id);
            $members = $query->get();
            return view('admin.corporate.members.index', compact('members', 'category'));
        } else {
            $members = $query->get();
            return view('admin.corporate.members.index', compact('members'));
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($category_id = null)
    {
        $categories = CorporateCategory::orderBy('name', 'asc')->pluck('name', 'id');
        $selectedCategory = $category_id;
        return view('admin.corporate.members.create', compact('categories', 'selectedCategory'));
    }

    /**
     * Handle image path processing to ensure correct storage format
     * 
     * @param string|null $imagePath
     * @return string|null
     */
    protected function processImagePath(?string $imagePath): ?string
    {
        if (!$imagePath) {
            return null;
        }
        
        // URL'leri temizle - tam URL şeklinde gelmişse
        if (Str::startsWith($imagePath, ['http://', 'https://'])) {
            $parsedUrl = parse_url($imagePath);
            $path = $parsedUrl['path'] ?? '';
            
            // /storage/ ile başlıyorsa storage/ olarak kaydet
            if (Str::startsWith($path, '/storage/')) {
                return Str::replaceFirst('/storage/', '', $path);
            }
            
            return $path;
        }
        
        // Storage ile başlıyorsa storagei sil
        if (Str::startsWith($imagePath, '/storage/')) {
            return Str::replaceFirst('/storage/', '', $imagePath);
        }
        
        return $imagePath;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:corporate_members,slug',
            'corporate_category_id' => 'required|exists:corporate_categories,id',
            'title' => 'nullable|string|max:255',
            'short_description' => 'nullable|string',
            'description' => 'nullable|string',
            'facebook' => 'nullable|string|max:255',
            'twitter' => 'nullable|string|max:255',
            'instagram' => 'nullable|string|max:255',
            'linkedin' => 'nullable|string|max:255',
            'website' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'order' => 'nullable|integer',
            'selected_image' => 'nullable|string|max:255',
            'show_detail' => 'nullable|boolean',
        ]);

        if (empty($request->slug)) {
            $request->merge(['slug' => Str::slug($request->name)]);
        }

        $data = $request->except(['_token', 'image', 'selected_image']);
        
        // Eğer selected_image yollanmışsa, image alanı olarak kaydet
        if ($request->filled('selected_image')) {
            $data['image'] = $this->processImagePath($request->selected_image);
        }
        
        // Status checkbox'ından gelmiyorsa false yap
        $data['status'] = $request->has('status') ? 1 : 0;
        
        // show_detail checkbox'ından gelmiyorsa false yap
        $data['show_detail'] = $request->has('show_detail') ? 1 : 0;

        CorporateMember::create($data);

        return redirect()->route('admin.corporate.members.index', $request->corporate_category_id)
            ->with('success', 'Kurumsal kadro üyesi başarıyla oluşturuldu.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $member = CorporateMember::findOrFail($id);
        $categories = CorporateCategory::orderBy('name', 'asc')->pluck('name', 'id');
        return view('admin.corporate.members.edit', compact('member', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CorporateMember $member)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:corporate_members,slug,'.$member->id,
            'corporate_category_id' => 'required|exists:corporate_categories,id',
            'title' => 'nullable|string|max:255',
            'short_description' => 'nullable|string',
            'description' => 'nullable|string',
            'facebook' => 'nullable|string|max:255',
            'twitter' => 'nullable|string|max:255',
            'instagram' => 'nullable|string|max:255',
            'linkedin' => 'nullable|string|max:255',
            'website' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'order' => 'nullable|integer',
            'selected_image' => 'nullable|string|max:255',
            'show_detail' => 'nullable|boolean',
        ]);

        if (empty($request->slug)) {
            $request->merge(['slug' => Str::slug($request->name)]);
        }

        $data = $request->except(['_token', '_method', 'image', 'selected_image']);
        
        // Eğer selected_image yollanmışsa, image alanı olarak kaydet
        if ($request->filled('selected_image')) {
            $data['image'] = $this->processImagePath($request->selected_image);
        }
        
        // Status checkbox'ından gelmiyorsa false yap
        $data['status'] = $request->has('status') ? 1 : 0;
        
        // show_detail checkbox'ından gelmiyorsa false yap
        $data['show_detail'] = $request->has('show_detail') ? 1 : 0;

        $member->update($data);

        return redirect()->route('admin.corporate.members.index', $request->corporate_category_id)
            ->with('success', 'Kurumsal kadro üyesi başarıyla güncellendi.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $member = CorporateMember::findOrFail($id);
        $categoryId = $member->corporate_category_id;

        // Üye resmini sil
        if ($member->image) {
            Storage::disk('public')->delete($member->image);
        }

        $member->delete();

        return redirect()
            ->route('admin.corporate.members.index', ['category' => $categoryId])
            ->with('success', 'Üye başarıyla silindi.');
    }

    /**
     * Üyelerin sıralamasını günceller
     */
    public function order(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*' => 'required|integer|exists:corporate_members,id',
        ]);

        foreach ($request->items as $index => $id) {
            CorporateMember::where('id', $id)->update(['order' => $index + 1]);
        }

        return response()->json(['success' => true]);
    }
}
