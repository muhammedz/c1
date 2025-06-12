<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CorporateCategory;
use App\Models\CorporateMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Helpers\SlugHelper;

class CorporateMemberController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, $category = null)
    {
        $query = CorporateMember::with('category');

        $categoryObject = null;
        if ($category) {
            $query->where('corporate_category_id', $category);
            $categoryObject = CorporateCategory::findOrFail($category);
        }

        // Arama işlevi
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $members = $query->orderBy('order', 'asc')->orderBy('created_at', 'desc')->paginate(20);
        $categories = CorporateCategory::orderBy('name', 'asc')->pluck('name', 'id');

        return view('admin.corporate.members.index', compact('members', 'categories', 'category', 'categoryObject'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($category)
    {
        $categories = CorporateCategory::orderBy('name', 'asc')->pluck('name', 'id');
        $selectedCategory = $category;
        return view('admin.corporate.members.create', compact('categories', 'selectedCategory'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $category)
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
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'show_detail' => 'nullable',
            'use_custom_link' => 'nullable',
            'custom_link' => 'nullable|url|max:500',
        ]);

        if (empty($request->slug)) {
            $request->merge(['slug' => SlugHelper::createUnique($request->name, CorporateMember::class)]);
        }

        $data = $request->except(['_token']);
        
        // Status checkbox'ından gelmiyorsa false yap
        $data['status'] = $request->has('status') ? 1 : 0;
        
        // show_detail checkbox'ından gelmiyorsa false yap
        $data['show_detail'] = $request->has('show_detail') ? 1 : 0;
        
        // use_custom_link checkbox'ından gelmiyorsa false yap
        $data['use_custom_link'] = $request->has('use_custom_link') ? 1 : 0;

        // Profile image upload
        if ($request->hasFile('profile_image')) {
            $image = $request->file('profile_image');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            
            // /uploads dizinine kaydet
            $imagePath = $image->move(public_path('uploads'), $imageName);
            $data['image'] = 'uploads/' . $imageName;
        }

        $member = CorporateMember::create($data);

        return redirect()->route('admin.corporate.members.index', $category)
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
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'show_detail' => 'nullable',
            'use_custom_link' => 'nullable',
            'custom_link' => 'nullable|url|max:500',
        ]);

        if (empty($request->slug)) {
            $request->merge(['slug' => SlugHelper::createUnique($request->name, CorporateMember::class, 'slug', $member->id)]);
        }

        $data = $request->except(['_token', '_method']);
        
        // Status checkbox'ından gelmiyorsa false yap
        $data['status'] = $request->has('status') ? 1 : 0;
        
        // show_detail checkbox'ından gelmiyorsa false yap
        $data['show_detail'] = $request->has('show_detail') ? 1 : 0;
        
        // use_custom_link checkbox'ından gelmiyorsa false yap
        $data['use_custom_link'] = $request->has('use_custom_link') ? 1 : 0;

        // Profile image upload
        if ($request->hasFile('profile_image')) {
            // Eski resmi sil
            if ($member->image && file_exists(public_path($member->image))) {
                unlink(public_path($member->image));
            }
            
            $image = $request->file('profile_image');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            
            // /uploads dizinine kaydet
            $imagePath = $image->move(public_path('uploads'), $imageName);
            $data['image'] = 'uploads/' . $imageName;
        }

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

        // Eski resmi sil
        if ($member->image && file_exists(public_path($member->image))) {
            unlink(public_path($member->image));
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
