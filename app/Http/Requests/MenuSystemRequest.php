<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MenuSystemRequest extends FormRequest
{
    /**
     * Kullanıcının isteği yapma yetkisi olup olmadığını belirler.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->hasRole('admin');
    }

    /**
     * İstek için geçerlilik kurallarını döndürür.
     */
    public function rules(): array
    {
        $rules = [
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:menu_systems,slug,' . $this->route('menusystem'),
            'type' => 'required|integer|in:1,2,3,4,5,6,7',
            'position' => 'required|string|in:header,footer,sidebar,mobile,main,top,bottom',
            'url' => 'nullable|string|max:255',
            'order' => 'nullable|integer|min:0',
            'status' => 'boolean',
            'description' => 'nullable|string',
            'properties' => 'nullable|array',
        ];

        // Kategori ve öğeler validasyonu (büyük menü ise)
        if ($this->input('type') == 2) {
            $rules['categories'] = 'array|min:1';
            $rules['categories.*.name'] = 'required|string|max:255';
            $rules['categories.*.url'] = 'nullable|string|max:255';
            $rules['categories.*.order'] = 'nullable|integer|min:0';
            $rules['categories.*.status'] = 'boolean';
            
            $rules['categories.*.items'] = 'array';
            $rules['categories.*.items.*.name'] = 'required|string|max:255';
            $rules['categories.*.items.*.url'] = 'required|string|max:255';
            $rules['categories.*.items.*.icon'] = 'nullable|string|max:50';
            $rules['categories.*.items.*.order'] = 'nullable|integer|min:0';
            $rules['categories.*.items.*.status'] = 'boolean';
            $rules['categories.*.items.*.target'] = 'nullable|string|in:_self,_blank,_parent,_top';
            $rules['categories.*.items.*.description'] = 'nullable|string';
        }

        // Açıklama validasyonu
        if ($this->has('description')) {
            $rules['description.text'] = 'nullable|string|max:1000';
            $rules['description.link_text'] = 'nullable|string|max:255';
            $rules['description.link_url'] = 'nullable|string|max:255';
        }

        return $rules;
    }

    /**
     * Doğrulama mesajlarını özelleştir.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Menü adı gereklidir.',
            'type.required' => 'Menü tipi gereklidir.',
            'position.required' => 'Menü konumu gereklidir.',
            'slug.unique' => 'Bu slug zaten kullanılmaktadır.',
            'categories.min' => 'Büyük menüler için en az bir kategori gereklidir.',
            'categories.*.name.required' => 'Kategori adı gereklidir.',
            'categories.*.items.*.name.required' => 'Menü öğesi adı gereklidir.',
            'categories.*.items.*.url.required' => 'Menü öğesi URL\'si gereklidir.',
        ];
    }

    /**
     * İstek verisini hazırla.
     */
    protected function prepareForValidation(): void
    {
        if ($this->has('status')) {
            $this->merge([
                'status' => true,
            ]);
        } else {
            $this->merge([
                'status' => false,
            ]);
        }

        // Slug boşsa otomatik olarak oluştur
        if (!$this->has('slug') || empty($this->input('slug'))) {
            $this->merge([
                'slug' => \Illuminate\Support\Str::slug($this->input('name')),
            ]);
        }
    }
} 