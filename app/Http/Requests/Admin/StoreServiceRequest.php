<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreServiceRequest extends FormRequest
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
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:services,slug',
            'summary' => 'nullable|string|max:1000',
            'content' => 'required|string',
            'image' => 'required|string',
            'gallery' => 'nullable|array',
            'gallery.*' => 'nullable|string',
            'features' => 'nullable|array',
            'features.*' => 'nullable|string',
            'cta_text' => 'nullable|string|max:255',
            'cta_url' => 'nullable|string|max:255',
            'category_ids' => 'nullable|array',
            'category_ids.*' => 'exists:service_categories,id',
            'tags' => 'nullable|string',
            'status' => 'required|in:draft,published',
            'is_featured' => 'nullable|boolean',
            'is_headline' => 'nullable|boolean',
            'published_at' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:published_at',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:1000',
            'meta_keywords' => 'nullable|string|max:255',
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
            'image.image' => 'Ana görsel geçerli bir resim dosyası olmalıdır.',
            'image.mimes' => 'Ana görsel jpeg, png, jpg veya gif formatında olmalıdır.',
            'image.max' => 'Ana görsel en fazla 2MB olabilir.',
            'status.required' => 'Durum alanı zorunludur.',
            'categories.required' => 'En az bir hizmet kategorisi seçmelisiniz.',
            'categories.min' => 'En az bir hizmet kategorisi seçmelisiniz.',
            'gallery.*.image' => 'Galeri resimleri geçerli bir resim dosyası olmalıdır.',
            'gallery.*.mimes' => 'Galeri resimleri jpeg, png, jpg veya gif formatında olmalıdır.',
            'gallery.*.max' => 'Galeri resimleri en fazla 2MB olabilir.',
            'end_date.after_or_equal' => 'Bitiş tarihi, yayınlanma tarihinden sonra olmalıdır.'
        ];
    }
}
