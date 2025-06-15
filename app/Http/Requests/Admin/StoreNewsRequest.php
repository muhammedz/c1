<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreNewsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Şimdilik herkese izin veriyoruz, daha sonra yetkilendirme eklenecek
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|string',
            'status' => 'required|in:published,draft',
            'categories' => 'required|array|min:1',
            'categories.*' => 'exists:news_categories,id',
            'published_at' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:published_at',
            'summary' => 'nullable|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'gallery' => 'nullable|array',
            'gallery.*' => 'string',
            'is_headline' => 'nullable|boolean',
            'is_featured' => 'nullable|boolean',
            'tags' => 'nullable|string',
            'files' => 'nullable|array',
            'files.*' => 'file|max:51200|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,txt,zip,rar',
            'names' => 'nullable|array',
            'names.*' => 'nullable|string|max:255'
        ];
    }

    /**
     * Get custom error messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Başlık alanı zorunludur.',
            'content.required' => 'İçerik alanı zorunludur.',
            'status.required' => 'Durum alanı zorunludur.',
            'categories.required' => 'En az bir kategori seçmelisiniz.',
            'categories.min' => 'En az bir kategori seçmelisiniz.',
            'end_date.after_or_equal' => 'Bitiş tarihi, yayınlanma tarihinden sonra olmalıdır.'
        ];
    }
} 