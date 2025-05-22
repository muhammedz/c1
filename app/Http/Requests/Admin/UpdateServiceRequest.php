<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateServiceRequest extends FormRequest
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
        $service = $this->route('service');
        
        return [
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:services,slug,' . $service->id,
            'summary' => 'nullable|string|max:1000',
            'content' => 'required|string',
            'image' => 'nullable|string',
            'delete_image' => 'nullable|boolean',
            'gallery' => 'nullable|array',
            'gallery.*' => 'nullable|string',
            'features' => 'nullable|array',
            'features.*' => 'nullable|string',
            'cta_text' => 'nullable|string|max:255',
            'cta_url' => 'nullable|string|max:255',
            'category_ids' => 'nullable|array',
            'category_ids.*' => 'exists:service_categories,id',
            'hedef_kitleler' => 'nullable|array',
            'hedef_kitleler.*' => 'exists:hedef_kitleler,id',
            'tags' => 'nullable|string',
            'status' => 'required|in:draft,published',
            'is_featured' => 'nullable|boolean',
            'is_headline' => 'nullable|boolean',
            'published_at' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:published_at',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:1000',
            'meta_keywords' => 'nullable|string|max:255',
            'details' => 'nullable|array',
            'details.service_purpose' => 'nullable|string',
            'details.is_purpose_visible' => 'nullable|boolean',
            'details.who_can_apply' => 'nullable|string',
            'details.is_who_can_apply_visible' => 'nullable|boolean',
            'details.requirements' => 'nullable|string',
            'details.is_requirements_visible' => 'nullable|boolean',
            'details.application_process' => 'nullable|string',
            'details.is_application_process_visible' => 'nullable|boolean',
            'details.processing_times' => 'nullable|array',
            'details.is_processing_times_visible' => 'nullable|boolean',
            'details.fees' => 'nullable|array',
            'details.is_fees_visible' => 'nullable|boolean',
            'details.payment_options' => 'nullable|array',
            'details.is_payment_options_visible' => 'nullable|boolean',
            'details.additional_info' => 'nullable|string',
            'details.is_additional_info_visible' => 'nullable|boolean',
            'details.standard_forms' => 'nullable|string',
            'details.is_standard_forms_visible' => 'nullable|boolean',
            'services_unit_id' => 'nullable|exists:services_units,id',
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
