<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePageRequest extends FormRequest
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
            'image' => 'required|string',
            'status' => 'required|in:published,draft',
            'categories' => 'required|array|min:1',
            'categories.*' => 'exists:page_categories,id',
            'published_at' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:published_at',
            'summary' => 'nullable|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
            'gallery' => 'nullable|array',
            'gallery.*' => 'string',
            'is_featured' => 'nullable|boolean',
            'tags' => 'nullable|string',
            'template' => 'nullable|string'
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
            'image.required' => 'Ana görsel alanı zorunludur.',
            'status.required' => 'Durum alanı zorunludur.',
            'categories.required' => 'En az bir sayfa kategorisi seçmelisiniz.',
            'categories.min' => 'En az bir sayfa kategorisi seçmelisiniz.',
            'end_date.after_or_equal' => 'Bitiş tarihi, yayınlanma tarihinden sonra olmalıdır.'
        ];
    }
} 