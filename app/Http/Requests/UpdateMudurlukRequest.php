<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateMudurlukRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $mudurlukId = $this->route('mudurluk')?->id ?? $this->route('mudurluk');

        return [
            'name' => 'required|string|max:255',
            'slug' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('mudurlukler', 'slug')->ignore($mudurlukId)
            ],
            'gorev_tanimi_ve_faaliyet_alani' => 'nullable|string',
            'yetki_ve_sorumluluklar' => 'nullable|string',
            'summary' => 'nullable|string|max:1000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'is_active' => 'nullable|boolean',
            'order_column' => 'nullable|integer|min:0',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            
            // Dosya yükleme kuralları
            'hizmet_standartlari_files.*' => 'nullable|file|mimes:pdf|max:10240', // 10MB
            'hizmet_standartlari_titles.*' => 'nullable|string|max:255',
            'yonetim_semalari_files.*' => 'nullable|file|mimes:pdf|max:10240', // 10MB
            'yonetim_semalari_titles.*' => 'nullable|string|max:255',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Müdürlük adı zorunludur.',
            'name.max' => 'Müdürlük adı en fazla 255 karakter olabilir.',
            'slug.unique' => 'Bu slug zaten kullanılmaktadır.',
            'summary.max' => 'Özet en fazla 1000 karakter olabilir.',
            'image.image' => 'Dosya bir resim olmalıdır.',
            'image.mimes' => 'Resim formatı jpeg, png, jpg veya webp olmalıdır.',
            'image.max' => 'Resim boyutu en fazla 2MB olabilir.',
            'meta_title.max' => 'SEO başlığı en fazla 255 karakter olabilir.',
            'meta_description.max' => 'SEO açıklaması en fazla 500 karakter olabilir.',
            
            'hizmet_standartlari_files.*.file' => 'Hizmet standartları dosyası geçerli bir dosya olmalıdır.',
            'hizmet_standartlari_files.*.mimes' => 'Hizmet standartları dosyası PDF formatında olmalıdır.',
            'hizmet_standartlari_files.*.max' => 'Hizmet standartları dosyası en fazla 10MB olabilir.',
            'hizmet_standartlari_titles.*.max' => 'Hizmet standartları başlığı en fazla 255 karakter olabilir.',
            
            'yonetim_semalari_files.*.file' => 'Yönetim şeması dosyası geçerli bir dosya olmalıdır.',
            'yonetim_semalari_files.*.mimes' => 'Yönetim şeması dosyası PDF formatında olmalıdır.',
            'yonetim_semalari_files.*.max' => 'Yönetim şeması dosyası en fazla 10MB olabilir.',
            'yonetim_semalari_titles.*.max' => 'Yönetim şeması başlığı en fazla 255 karakter olabilir.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        if ($this->filled('name') && !$this->filled('slug')) {
            $this->merge([
                'slug' => \Illuminate\Support\Str::slug($this->name)
            ]);
        }

        $this->merge([
            'is_active' => $this->boolean('is_active'),
            'order_column' => $this->integer('order_column', 0),
        ]);
    }
}
