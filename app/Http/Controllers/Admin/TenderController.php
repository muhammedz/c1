<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tender;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class TenderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tenders = Tender::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.tenders.index', compact('tenders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.tenders.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'unit' => 'required|string|max:255',
            'summary' => 'nullable|string',
            'kik_no' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:255',
            'fax' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'document_url' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'delivery_place' => 'nullable|string|max:255',
            'delivery_date' => 'nullable|string|max:255',
            'tender_address' => 'nullable|string',
            'tender_datetime' => 'nullable|date',
            'content' => 'nullable|string',
            'status' => 'required|in:active,completed,cancelled',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $request->all();
        
        // Slug oluştur
        $data['slug'] = Str::slug($request->title);
        
        // Aynı slugdan varsa numaralandır
        $count = Tender::where('slug', $data['slug'])->count();
        if ($count > 0) {
            $data['slug'] = $data['slug'] . '-' . ($count + 1);
        }

        Tender::create($data);

        return redirect()->route('admin.tenders.index')
            ->with('success', 'İhale başarıyla oluşturuldu.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $tender = Tender::findOrFail($id);
        return view('admin.tenders.show', compact('tender'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $tender = Tender::findOrFail($id);
        return view('admin.tenders.edit', compact('tender'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'unit' => 'required|string|max:255',
            'summary' => 'nullable|string',
            'kik_no' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:255',
            'fax' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'document_url' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'delivery_place' => 'nullable|string|max:255',
            'delivery_date' => 'nullable|string|max:255',
            'tender_address' => 'nullable|string',
            'tender_datetime' => 'nullable|date',
            'content' => 'nullable|string',
            'status' => 'required|in:active,completed,cancelled',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $tender = Tender::findOrFail($id);
        $data = $request->all();
        
        // Başlık değiştiyse yeni slug oluştur
        if ($request->title != $tender->title) {
            $data['slug'] = Str::slug($request->title);
            
            // Aynı slugdan varsa ve bu kayıt değilse numaralandır
            $count = Tender::where('slug', $data['slug'])->where('id', '!=', $id)->count();
            if ($count > 0) {
                $data['slug'] = $data['slug'] . '-' . ($count + 1);
            }
        }

        $tender->update($data);

        return redirect()->route('admin.tenders.index')
            ->with('success', 'İhale başarıyla güncellendi.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $tender = Tender::findOrFail($id);
        $tender->delete();

        return redirect()->route('admin.tenders.index')
            ->with('success', 'İhale başarıyla silindi.');
    }
    
    /**
     * Toggle the status of the specified tender.
     */
    public function toggleStatus(string $id)
    {
        $tender = Tender::findOrFail($id);
        
        switch ($tender->status) {
            case 'active':
                $tender->status = 'completed';
                break;
            case 'completed':
                $tender->status = 'active';
                break;
            case 'cancelled':
                $tender->status = 'active';
                break;
            default:
                $tender->status = 'active';
        }
        
        $tender->save();
        
        return redirect()->back()->with('success', 'İhale durumu güncellendi.');
    }
}
